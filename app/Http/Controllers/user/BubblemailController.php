<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Hash;
use ZipArchive;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class BubblemailController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('userauth');
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function bubble_mail(Request $request)
	{
		return view('user/bubblemail/bubble_mail', array('title' => 'Bubble Mail'));
	}
	public function bubble_load_all_clients(Request $request){
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'email', 'status', 'active', 'id','address1','address2','address3','address4','address5','phone','statement')->orderBy('id','asc')->get();
		$class = \App\Models\CMClass::where('status', 0)->get();
		$output = '';
		$i = 1;
        if(($clientlist))
        {
          foreach($clientlist as $client){
            $disabled = '';
            if($client->active != "")
            {
              if($client->active == 2)
              {
                $disabled='inactive_clients';
              }
              $check_color = \App\Models\CMClass::where('id',$client->active)->first();
              $style="color:#".$check_color->classcolor."";
            }
            else{
              $style="color:#000";
            }

            $messageus_counts = \App\Models\Messageus::where('practice_code',Session::get('user_practice_code'))->where('status',1)->where('client_ids','LIKE','%'.$client->client_id.'%')->get();
            $output.='<tr class="clients_tr '.$disabled.'" data-element="'.$client->client_id.'">
              <td class="client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->client_id.'</td>
              <td class="client_td" data-element="'.$client->client_id.'" style="'.$style.';text-align:center">
              	<img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
              </td>
              <td class="client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->company.'</td>
              <td class="client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.count($messageus_counts).'</td>
            </tr>';
            $i++;
          }
        }
        echo $output;
	}
	public function get_client_message_list(Request $request)
	{
		$client_id = $request->get('client_id');
		$outputmessage='';
        $messageus = \App\Models\Messageus::where('practice_code',Session::get('user_practice_code'))->where('status',1)->where('client_ids','LIKE','%'.$client_id.'%')->orderBy('id','desc')->get();
        if(($messageus))
        {
        	$i = 1;
        	foreach($messageus as $message)
        	{
        		$from = $message->message_from;
                if($from == 0)
                {
                  $mess_from = 'Admin';
                }
                else{
                  $userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from)->first();
                  $mess_from = $userdetails->lastname.' '.$userdetails->firstname;
                }
                $attachemnt = '';
                if($message->attachments != ""){
                	$attachemnt = '<i class="fa fa-download" title="Attachments" style="margin-right: 15px;font-weight: 600;font-size: 18px;"></i>';
                }
                $outputmessage.='<tr class="message_tr" data-element="'.$message->id.'">
                	<td class="message_td" data-element="'.$message->id.'"><spam style="display:none">'.strtotime($message->date_sent).'</spam>'.date('d-M-Y @ H:i', strtotime($message->date_sent)).'</td>
                	<td class="message_td" data-element="'.$message->id.'">'.$mess_from.'</td>
                  	<td class="message_td" data-element="'.$message->id.'">'.$message->subject.' '.$attachemnt.'</td>
                </tr>';
                $i++;
        	}
        }
        else{
        	$outputmessage.='<tr>
        			<td>No Message Found</td>
        			<td></td>
        			<td></td>
        	</tr>';
        }

        echo $outputmessage;
	}
	public function view_bubble_mail_message(Request $request)
	{
		$message_id = $request->get('message_id');
		$message_details = \App\Models\Messageus::where('id',$message_id)->first();

		if($message_details->source == "")
        {
        	$source = 'MessageUS';
        }
        else{
        	$source = $message_details->source;
        }

		$from = $message_details->message_from;
        if($from == 0)
        {
          $mess_from = 'Admin';
        }
        else{
          $userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from)->first();
          $mess_from = $userdetails->lastname.' '.$userdetails->firstname;
        }
        $clients = explode(",",$message_details->client_ids);
        $clienttextbox = '';
        if(count($clients) < 3)
	      {
	        foreach($clients as $client)
	        {
	          $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
	          if($clienttextbox == ""){
	          	$clienttextbox = $client_details->company;
	          }
	          else{
	          	$clienttextbox = $clienttextbox.' , '.$client_details->company;
	          }
	        }
	      }else{
	      	$clienttextbox = count($clients).' Clients';
	      }

	      $fileoutput = '';
	      $files = \App\Models\MessageusFiles::where('message_id',$message_id)->get();
	      if(($files))
	      {
	      	$fileoutput.='<div class="col-md-12"><h5 style="font-weight:800">Attached Files: </h5></div>';
	        foreach($files as $file)
	        {
	          $fileoutput.="<div class='messageus_attachment' style='width:100%;margin-left: 14px;'><a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a></div>";
	        }
	      }
	      if($message_details->attachments != "")
	      {
	      	$fileoutput.='<div class="col-md-12"><h5 style="font-weight:800">Attached Files: </h5></div>';
	      	$attachments = explode("||",$message_details->attachments);
	      	if(($attachments))
	      	{
	      		foreach($attachments as $attach)
	      		{
	      			$exp_attach = explode("/",$attach);
	      			$fileoutput.="<div class='messageus_attachment' style='width:100%;margin-left: 14px;'><a href='".URL::to('/')."/".$attach."' class='messageus_attachment_a' download>".end($exp_attach)."</a></div>";
	      		}
	      	}
	      }

		$output = '<div class="row">
			<div class="col-md-12 padding_00" style="background: #fff;margin-top: 30px;margin-bottom: 30px;padding-top: 20px;padding-bottom: 25px;">
		        <div class="col-md-12">
		          <h5 style="font-weight:800">Header Information:</h5>
		        </div>
		        <div class="col-md-12" style="margin-top:10px">
		        	<div class="col-md-1">
		          		<h5 style="font-weight:800">From: </h5>
		          	</div>
		          	<div class="col-md-11">
		          		<input type="text" name="message_from" class="form-control message_from" value="'.$mess_from.'" readonly style="background:dfdfdf">
		          	</div>
		          	<div class="col-md-1">
		          		<h5 style="font-weight:800">To: </h5>
		          	</div>
		          	<div class="col-md-11">
		          		<input type="text" name="message_to" class="form-control message_to" value="'.$clienttextbox.'" readonly style="background:dfdfdf;font-weight:800;color:blue;cursor:pointer">
		          	</div>
		          	<div class="col-md-1">
		          		<h5 style="font-weight:800">Date: </h5>
		          	</div>
		          	<div class="col-md-11">
		          		<input type="text" name="message_date" class="form-control message_date" value="'.date('d-M-Y @ H:i', strtotime($message_details->date_sent)).'" readonly style="background:dfdfdf">
		          	</div>
		          	<div class="col-md-1">
		          		<h5 style="font-weight:800">Subject: </h5>
		          	</div>
		          	<div class="col-md-11">
		          		<input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" readonly style="background:dfdfdf">
		          	</div>
		          	<div class="col-md-1">
		          		<h5 style="font-weight:800">Source: </h5>
		          	</div>
		          	<div class="col-md-11">
		          		<input type="text" name="message_source" class="form-control message_source" value="'.$source.'" readonly style="background:dfdfdf">
		          	</div>
		        </div>
		    </div>';
		    if($fileoutput != ""){
		    	$output.='<div class="col-md-12 padding_00" style="background: #fff;margin-top: 10px;margin-bottom: 30px;padding-top: 20px;padding-bottom: 25px;">'.$fileoutput.'</div>';
		    }
	        $output.='<div class="col-md-12" style="background: #fff;margin-top: 10px;margin-bottom: 30px;padding-top: 20px;padding-bottom: 25px;">
	          <h5 style="font-weight:800">Message Body: </h5>
	          <div style="width:100%;background: #fff;min-height:300px;padding:10px">
	            '.$message_details->message.'
	          </div>
	        </div>
			<div class="modal fade" id="to_clients_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			        <h5 class="modal-title" id="exampleModalLabel">Clients List</h5>
			      </div>
			      <div class="modal-body">';
				      $clientoutput = '';
			          if(($clients))
			          {
			            foreach($clients as $client)
			            {
			              $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
			              $clientoutput.='';
			              if($client_details){
			              	$clientoutput.="<div class='messageus_attachment' style='width:100%'>".$client_details->company."</div>";
			              }
			            }
			          }
			          $output.=$clientoutput;
			      $output.='</div>
			    </div>
			  </div>
			</div>
	        
	    </div>';
      echo $output;
	}
}