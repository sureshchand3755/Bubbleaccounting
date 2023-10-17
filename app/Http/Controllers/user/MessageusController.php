<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use DateTime;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class MessageusController extends Controller {
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
		//date_default_timezone_set("Asia/Calcutta");
		require_once app_path("Http/helpers.php");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function directmessaging(Request $request)
	{
		if(isset($_GET['message_id']))
		{
			$message_details = \App\Models\Messageus::where('id',$_GET['message_id'])->first();
			$subject = $message_details->subject;
			$message_body = $message_details->message;
		}
		else{
			$subject = "";
			$message_body = "";
		}
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging',array('subject' => $subject,'message_body' => $message_body));
	}
	public function directmessaging_page_two(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging_page_two',array('clients' => $clients));
	}
	public function directmessaging_page_three(Request $request)
	{
		$message_id = $_GET['message_id'];
		$message_details = \App\Models\Messageus::where('id',$message_id)->first();
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging_page_three',array('message_details' => $message_details));
	}
	public function messageus_groups(Request $request)
	{
		$groups = \App\Models\MessageusGroups::where('practice_code',Session::get('user_practice_code'))->get();
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/messageus_groups',array('groups' => $groups,'clients' => $clients));
	}
	public function messageus_saved_messages(Request $request)
	{
		$messages = \App\Models\Messageus::where('practice_code',Session::get('user_practice_code'))->where('draft_status',1)->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/messageus_saved_messages',array('messages' => $messages));
	}
	public function messageus_upload_images_add(Request $request)
	{
		$message_id = $request->get('message_id');
		if($message_id == "")
		{
			$upload_dir = 'uploads/messageus_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/temp';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			if (!empty($_FILES)) {
				 $tmpFile = $_FILES['file']['tmp_name'];
				 $fname = str_replace("#","",$_FILES['file']['name']);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("%","",$fname);
				 $fname = str_replace("%","",$fname);
				 $fname = str_replace("%","",$fname);
				 $filename = $upload_dir.'/'.$fname;
				 $arrayval = array('attachment' => $fname,'url' => $upload_dir,'content' => "");
		 		if(Session::has('message_file_attach_add'))
				{
					$getsession = Session::get('message_file_attach_add');
				}
				else{
					$getsession = array();
				}
				$getsession = array_values($getsession);
				array_push($getsession,$arrayval);
				$sessn=array('message_file_attach_add' => $getsession);
				Session::put($sessn);
				move_uploaded_file($tmpFile,$filename);
				$key = count($getsession) - 1;
			 	echo json_encode(array('id' => 0,'attachment' => $filename,'filename' => $fname,'file_id' => $key,'message_id' => 0));
			}
		}
		else{
			$upload_dir = 'uploads/messageus_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($message_id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			if (!empty($_FILES)) {
				 $tmpFile = $_FILES['file']['tmp_name'];
				 $fname = str_replace("#","",$_FILES['file']['name']);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("#","",$fname);
				 $fname = str_replace("%","",$fname);
				 $fname = str_replace("%","",$fname);
				 $fname = str_replace("%","",$fname);
				 $filename = $upload_dir.'/'.$fname;
				 $data['filename'] = $fname;
				 $data['url'] = $upload_dir;
				 $data['content'] = "";
				 $data['message_id'] = $message_id;
				$id = \App\Models\MessageusFiles::insertDetails($data);
				move_uploaded_file($tmpFile,$filename);
			 	echo json_encode(array('id' => $id,'attachment' => $filename,'filename' => $fname,'file_id' => $id,'message_id' =>$message_id));
			}
		}
	}
	public function messageus_remove_dropzone_attachment(Request $request)
	{
		$file_id = $_POST['file_id'];
		if(Session::has('message_file_attach_add'))
		{
			$files = Session::get('message_file_attach_add');
			unset($files[$file_id]);
			$getsession = array_values($files);
			$sessn=array('message_file_attach_add' => $getsession);
			Session::put($sessn);
			$output = '';
			if(($getsession))
			{
				foreach($getsession as $key => $sess)
				{
					$output.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$sess['url']."/".$sess['attachment']."' class='messageus_attachment_a' download>".$sess['attachment']."</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach_add' data-task='".$key."'></a> <a href='javascript:' class='fa fa-text-width add_notes_attachment' data-task='".$key."'></a>
						<a href='javascript:' class='fa fa-ellipsis-h attach_content attach_content_".$key."' title='' style='display:none'></a></div>";
				}
			}
			echo $output;
		}
	}
	public function messageus_add_comment_to_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$content = $request->get('textarea');
		$type = $request->get('type');
		if($type == "0")
		{
			if(Session::has('message_file_attach_add'))
			{
				$sessionvalue = Session::get('message_file_attach_add');
				$sessionvalue[$fileid]['content'] = $content;
				$sessn=array('message_file_attach_add' => $sessionvalue);
				Session::put($sessn);
			}
		}
		else{
			$data['content'] = $content;
			\App\Models\MessageusFiles::where('id',$fileid)->update($data);
		}
	}
	public function get_attachment_notes(Request $request)
	{
		$fileid = $request->get('fileid');
		$type = $request->get('type');
		if($type == "0")
		{
			$sessionvalue = Session::get('message_file_attach_add');
			echo $sessionvalue[$fileid]['content'];
		}
		else{
			$attachment = \App\Models\MessageusFiles::where('id',$fileid)->first();
			echo $attachment->content;
		}
	}
	public function message_remove_dropzone_attachment(Request $request)
	{
		$file_id = $_POST['file_id'];
		\App\Models\MessageusFiles::where('id',$file_id)->delete();
	}
	public function save_message_page_one(Request $request)
	{
		$message_id = $request->get('message_id');
		$subject = $request->get('subject');
		$message_body = $request->get('message_body');
		if($message_id == "")
		{
			$data['subject'] = $subject;
			$data['message'] = $message_body;
			$data['practice_code'] = Session::get('user_practice_code');
			$message_id = \App\Models\Messageus::insertDetails($data);
			if(Session::has('message_file_attach_add'))
			{
				$files = Session::get('message_file_attach_add');
				if(($files))
				{
					foreach($files as $file)
					{
						$datafiles['message_id'] = $message_id;
						$datafiles['url'] = $file['url'];
						$datafiles['filename'] = $file['attachment'];
						$datafiles['content'] = $file['content'];
						\App\Models\MessageusFiles::insert($datafiles);
					}
				}
			}
			echo $message_id;
		}
		else{
			$data['subject'] = $subject;
			$data['message'] = $message_body;
			\App\Models\Messageus::where('id',$message_id)->update($data);
			echo $message_id;
		}
	}
	public function save_message_page_two(Request $request)
	{
		$message_id = $request->get('message_id');
		$client_ids = $request->get('client_ids');
		$primary_emails = $request->get('primary_emails');
		$secondary_emails = $request->get('secondary_emails');
		$group_type = $request->get('group_type');
		$group_id = $request->get('group_id');
		$data['client_ids'] = $client_ids;
		$data['primary_emails'] = $primary_emails;
		$data['secondary_emails'] = $secondary_emails;
		$data['group_type'] = $group_type;
		if($group_type == "4")
		{
			$data['group_id'] = $group_id;
		}
		\App\Models\Messageus::where('id',$message_id)->update($data);
	}
	public function send_message_later(Request $request)
	{
		$message_id = $request->get('message_id');
		$data['draft_status'] = 1;
		$data['date_saved'] = date('Y-m-d H:i:s');
		\App\Models\Messageus::where('id',$message_id)->update($data);
	}
	public function send_message_now(Request $request)
	{
		$message_id = $request->get('message_id');
		$from = $request->get('from');

		$message_details = \App\Models\Messageus::where('id',$message_id)->first();
		$primary_emails = explode(',',$message_details->primary_emails);
		$secondary_emails = explode(',',$message_details->secondary_emails);

		$emails = array_merge($primary_emails,$secondary_emails);

		$to = trim($emails[0]);
		$datamessage['message_from'] = $from;
		$datamessage['date_sent'] = date('Y-m-d H:i:s');
		$datamessage['status'] = 1;
		$datamessage['draft_status'] = 0;
		\App\Models\Messageus::where('id',$message_id)->update($datamessage);

		$messageus_settings = DB::table('messageus_settings')->where('practice_code',Session::get('user_practice_code'))->first();

		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from)->first();
		$user_email = $user_details->email;
		$dataemail['logo'] = getEmailLogo('messageus');
		$dataemail['message'] = $message_details->message;
		$subject_email = $message_details->subject;
		$contentmessage = view('emails/messageus/create_messageus_email', $dataemail)->render();
		$admin_cc = $messageus_settings->messageus_cc_email;
		
		$email = new PHPMailer();
		$email->SetFrom($user_email);
		$email->CharSet = "UTF-8";
		$email->IsHTML(true);
		$email->Subject   = $subject_email;
		$email->Body      = $contentmessage;
		$email->AddCC($admin_cc);
		foreach ($emails as $emailval) {
	        $email->addBCC($emailval);
	    }
		$files = \App\Models\MessageusFiles::where('message_id',$message_id)->get();
		if(($files))
		{
			foreach($files as $file)
			{
				$email->AddAttachment( $file->url.'/'.$file->filename , $file->filename );
			}
		}
		$email->Send();
	}
	public function create_group_name(Request $request)
	{
		$grp_name = $request->get('grp_name');
		$data['group_name'] = $grp_name;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\MessageusGroups::insertDetails($data);
		$data['group_tr'] =  '<tr class="group_tr highlight_group" id="group_tr_'.$id.'" data-element="'.$id.'">
                      <td class="group_td" style="border-right:1px solid #000;width:60%">'.$grp_name.'</td>
                      <td class="group_td" style="width:40%;text-align:right">0</td>
                    </tr>';
        $data['group_name'] = $grp_name;
        $data['group_id'] = $id;
        echo json_encode($data);
	}
	public function select_messageus_group(Request $request)
	{
		$group_id = $request->get('group_id');
		$group_details = \App\Models\MessageusGroups::where('id',$group_id)->first();
		if($group_id == "1")
		{
			$data['group_name'] = $group_details->group_name;
	        $data['client_ids'] = $group_details->client_ids;
	        $selected = '';
	        $explode = explode(",",$group_details->client_ids);
	        if($group_details->client_ids != "")
	        {
	        	foreach($explode as $exp)
	        	{
	        		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$exp)->first();
	        		if($client_details){
	        			$get_week_status = \App\Models\task::where('task_week',$group_details->last_week)->where('client_id',$exp)->where('task_status',0)->count();
						$get_month_status = \App\Models\task::where('task_month',$group_details->last_month)->where('client_id',$exp)->where('task_status',0)->count();
						$total_count = $get_week_status + $get_month_status;
						if($total_count == 0)
						{
							$clss = 'selected_complete';
						}
						else{
							$clss = 'selected_donot_complete';
						}
		        		if($client_details->active == "2") { $cls= 'selected_inactive'; } 
		        		else { $cls = 'selected_active'; }
		        		$selected.='<tr class="selected_tr '.$clss.' '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
		        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
		        			<td class="selected_td">'.$exp.'</td>
		        			<td class="selected_td">'.$client_details->company.'</td>
		        			<td class="selected_td">'.$client_details->email.'</td>
		        		</tr>';
	        		}
	        	}
	        }
	        $data['selected_clients'] = $selected;
	        echo json_encode($data);
		}
		else{
			$data['group_name'] = $group_details->group_name;
	        $data['client_ids'] = $group_details->client_ids;
	        $selected = '';
	        $explode = explode(",",$group_details->client_ids);
	        if($group_details->client_ids != "")
	        {
	        	foreach($explode as $exp)
	        	{
	        		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$exp)->first();
	        		if($client_details) {
	        			if($client_details->active == "2") { $cls= 'selected_inactive'; } 
		        		else { $cls = 'selected_active'; }
		        		$selected.='<tr class="selected_tr '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
		        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
		        			<td class="selected_td">'.$exp.'</td>
		        			<td class="selected_td">'.$client_details->company.'</td>
		        			<td class="selected_td">'.$client_details->email.'</td>
		        		</tr>';
	        		}
	        	}
	        }
	        $data['selected_clients'] = $selected;
	        echo json_encode($data);
		}
	}
	public function add_selected_member_to_group(Request $request)
	{
		$grp_id = $request->get('grp_id');
		$client_ids = $request->get('client_ids');
		$selected = '';
		$explode = explode(",",$client_ids);
        if($client_ids != "")
        {
        	foreach($explode as $exp)
        	{
        		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$exp)->first();
        		if($client_details->active == "2") { $cls= 'selected_inactive'; } 
        		else { $cls = 'selected_active'; }
        		$selected.='<tr class="selected_tr '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
        			<td class="selected_td">'.$exp.'</td>
        			<td class="selected_td">'.$client_details->company.'</td>
        			<td class="selected_td">'.$client_details->email.'</td>
        		</tr>';
        	}
        }
        $grp_details = \App\Models\MessageusGroups::where('id',$grp_id)->first();
        $ids = $grp_details->client_ids;
        if($ids == "")
        {
        	$data['client_ids'] = $client_ids;
        }
        else{
        	$data['client_ids'] = $ids.','.$client_ids;
        }
        \App\Models\MessageusGroups::where('id',$grp_id)->update($data);
        echo $selected;
	}
	public function remove_selected_member_to_group(Request $request)
	{
		$grp_id = $request->get('grp_id');
		$client_ids = explode(",",$request->get('client_ids'));
		$grp_details = \App\Models\MessageusGroups::where('id',$grp_id)->first();
        $ids = explode(",",$grp_details->client_ids);
        if($client_ids != "")
        {
        	foreach($client_ids as $client_id)
        	{
        		if(in_array($client_id, $ids))
        		{
        			$key = array_search($client_id, $ids);
        			unset($ids[$key]);
        		}
        	}
        }
        $implode = implode(",",$ids);
        $data['client_ids'] = $implode;
        \App\Models\MessageusGroups::where('id',$grp_id)->update($data);
	}
	public function delete_messageus_groups(Request $request)
	{
		$grp_id = $request->get('grp_id');
		\App\Models\MessageusGroups::where('id',$grp_id)->delete();
	}
	public function delete_saved_message(Request $request)
	{
		$message_id = $_GET['message_id'];
		\App\Models\Messageus::where('id',$message_id)->delete();
		return redirect::back()->with('message', 'Message Deleted successfully');
	}
	public function choose_messageus_from(Request $request)
	{
		$message_id = $request->get('message_id');
		$from = $request->get('from');
		$datamessage['message_from'] = $from;
		\App\Models\Messageus::where('id',$message_id)->update($datamessage);
	}
	public function show_messageus_sample_screen(Request $request)
	{
		$message_id = $request->get('message_id');
		$message_details = \App\Models\Messageus::where('id',$message_id)->first();
		if($message_details->source == "")
		{
			$output = '<div class="row" style="background: #c7c7c7;padding:20px">
		        <div class="col-md-12">
		          <h5 style="font-weight:800">Message Summary:</h5>
		        </div>
		        <div class="col-md-12" style="margin-top:10px">
		          <h5 style="font-weight:800">Message Subject: </h5>
		          <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Message Body: </h5>
		          <div style="width:100%;background: #fff;min-height:300px;padding:10px">
		            '.$message_details->message.'
		          </div>
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Attached Files: </h5>';
		          $fileoutput = '';
		          $files = \App\Models\MessageusFiles::where('message_id',$message_id)->get();
		          if(($files))
		          {
		            foreach($files as $file)
		            {
		              $fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a></div>";
		            }
		          }
		          $output.=$fileoutput;
		        $output.='</div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Clients: </h5>';
		          $clientoutput = '';
		          $clients = explode(",",$message_details->client_ids);
		          if(($clients))
		          {
		            foreach($clients as $client)
		            {
		              $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
		              $companynameval = '';
		              if(($client_details)){
		              	$companynameval = $client_details->company;
		              }
		              $clientoutput.="<div class='messageus_attachment' style='width:100%'>".$companynameval."</div>";
		            }
		          }
		          $output.=$clientoutput;
		        $output.='</div>
		    </div>';
		}
		else{
			$output = '<div class="row" style="background: #c7c7c7;padding:20px">
		        <div class="col-md-12">
		          <h5 style="font-weight:800">Message Summary:</h5>
		        </div>
		        <div class="col-md-12" style="margin-top:10px">
		          <h5 style="font-weight:800">Message Subject: </h5>
		          <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Message Body: </h5>
		          <div id="message_body" style="width:100%;background: #fff;min-height:300px;padding:10px;float: left;">
		            '.$message_details->message.'
		          </div>
		        </div>
		        <div class="col-md-12" style="margin-top:20px">';
		          $fileoutput = '';
		          if($message_details->attachments != "")
		          {
		          	$fileoutput.='<h5 style="font-weight:800">Attached Files: </h5>';
		          	$attachments = explode("||",$message_details->attachments);
		          	if(($attachments))
		          	{
		          		foreach($attachments as $attach)
		          		{
		          			$exp_attach = explode("/",$attach);
		          			$fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$attach."' class='messageus_attachment_a' download>".end($exp_attach)."</a></div>";
		          		}
		          	}
		          }
		          $output.=$fileoutput;
		        $output.='</div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Clients: </h5>';
		          $clientoutput = '';
		          $get_clients = \App\Models\Messageus::where('message_id',$message_details->message_id)->get();
		          if(($get_clients))
		          {
		          	foreach($get_clients as $clients)
		          	{
		          		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clients->client_ids)->first();
		          		$companynameval = '';
			              if(($client_details)){
			              	$companynameval = $client_details->company;
			              }
		          		$clientoutput.="<div class='messageus_attachment' style='width:100%'>".$companynameval."</div>";
		          	}
		          }
		          $output.=$clientoutput;
		        $output.='</div>
		      </div>';
		}
      echo $output;
	}
	public function update_pms_groups(Request $request)
	{
		$current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->orderBy('week_id','desc')->first();
		$current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->orderBy('month_id','desc')->first();
		$tasks = \App\Models\task::where('practice_code', Session::get('user_practice_code'))->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('client_id')->get();
		$client_ids = '';
		$selected = '';
		if(($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->client_id != "")
				{
					$get_week_status = \App\Models\task::where('task_week',$current_week->week_id)->where('client_id',$task->client_id)->where('task_status',0)->count();
					$get_month_status = \App\Models\task::where('task_month',$current_month->month_id)->where('client_id',$task->client_id)->where('task_status',0)->count();
					$total_count = $get_week_status + $get_month_status;
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$task->client_id)->first();
					if($total_count == 0)
					{
						$clss = 'selected_complete';
					}
					else{
						$clss = 'selected_donot_complete';
					}
					if($client_details->active == "2")
					{
						$cls = 'selected_inactive';
					}
					else{
						$cls = 'selected_active';
					}
					$selected.='<tr class="selected_tr '.$clss.' '.$cls.'" id="selected_tr_'.$task->client_id.'" data-element="'.$task->client_id.'">
						<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$task->client_id.'"><label>&nbsp;</label></td>
	        			<td class="selected_td">'.$task->client_id.'</td>
	        			<td class="selected_td">'.$client_details->company.'</td>
	        			<td class="selected_td">'.$client_details->email.'</td>
	        		</tr>';
	        		if($client_ids == "")
	        		{
	        			$client_ids = $task->client_id;
	        		}
	        		else{
	        			$client_ids = $client_ids.','.$task->client_id;
	        		}
				}
			}
		}
		$data['client_ids'] = $client_ids;
		$data['last_week'] = $current_week->week_id;
		$data['last_month'] = $current_month->month_id;
		\App\Models\MessageusGroups::where('id',1)->update($data);
		echo json_encode(array("client_ids" => $client_ids, 'selected' => $selected));
	}
	public function get_pms_clients(Request $request){
		$current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->orderBy('week_id','desc')->first();
		$current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->orderBy('month_id','desc')->first();
		$clients = \App\Models\task::where('practice_code', Session::get('user_practice_code'))->where('client_id','!=','')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('client_id')->pluck('client_id')->toArray();
		$output = '';
		if(($clients))
		{
			foreach($clients as $key => $client){
				$client_week = \App\Models\task::select('task_complete_period_type')->where('client_id',$client)->where('task_week',$current_week->week_id)->orderBy('task_id','desc')->first();
				$task_complete_period_type = 0;
				if(($client_week)){
					$task_complete_period_type = $client_week->task_complete_period_type;
				}else{
					$client_month = \App\Models\task::select('task_complete_period_type')->where('client_id',$client)->where('task_month',$current_month->month_id)->orderBy('task_id','desc')->first();
					if(($client_month)){
						$task_complete_period_type = $client_month->task_complete_period_type;
					}
				}
				$period_only_checked = '';
				$further_notice_checked = '';
				$color = '#000';
				$show_checkbox = 'display:inline';
				if($task_complete_period_type == "1"){
					$period_only_checked = 'checked';
					$color = 'blue';
					$show_checkbox = 'display:none';
				}
				elseif($task_complete_period_type == "2"){
					$further_notice_checked = 'checked';
					$color = '#f00';
					$show_checkbox = 'display:none';
				}
				$client_details = \App\Models\CMClients::select('company')->where('client_id',$client)->first();
				$keyval = $key + 1;
				$output.='<tr>
					<td>
						<input type="checkbox" name="select_messageus_pms_client" class="select_messageus_pms_client" id="select_messageus_pms_client_'.$client.'" value="'.$client.'" data-element="'.$client.'" style="'.$show_checkbox.'">
						<label for="select_messageus_pms_client_'.$client.'" class="select_messageus_pms_client_label" style="color:'.$color.';'.$show_checkbox.'">'.$keyval.'</label>
					</td>
					<td style="color:'.$color.'">'.$client.'</td>
					<td style="color:'.$color.'">'.$client_details->company.'</td>
					<td>
						<div style="width:85%">
							<input type="checkbox" name="donot_process_message_us_'.$client.'" class="donot_process_message_us until_further_notice" id="until_further_notice_'.$client.'" data-element="'.$client.'" '.$further_notice_checked.'><label for="until_further_notice_'.$client.'" style="width:100%;color:'.$color.'">Do Not Process Until Further Notice</label>
							<input type="checkbox" name="donot_process_message_us_'.$client.'" class="donot_process_message_us this_period_only" id="this_period_only_'.$client.'" data-element="'.$client.'" '.$period_only_checked.'><label for="this_period_only_'.$client.'" style="width:100%;color:'.$color.'">Do not Process this Period Only</label>
						</div>
					</td>
				</tr>';
			}
		}
		echo $output;
	}
	public function create_messageus_pms_groups(Request $request){
		$grp_name = $request->get('group_name');
		$clientid = $request->get('clientid');
		$data['group_name'] = $grp_name;
		$data['client_ids'] = $clientid;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\MessageusGroups::insertDetails($data);
		$data['group_tr'] =  '<tr class="group_tr highlight_group" id="group_tr_'.$id.'" data-element="'.$id.'">
                      <td class="group_td" style="border-right:1px solid #000;width:60%">'.$grp_name.'</td>
                      <td class="group_td" style="width:40%;text-align:right">'.count(explode(',',$clientid)).'</td>
                    </tr>';
        $data['group_name'] = $grp_name;
        $data['group_id'] = $id;
        echo json_encode($data);
	}
	public function edit_messageus_header_image(Request $request) {
        $image_width = 0;
        $image_height = 0;
        if (!empty($_FILES)) {
            $image_info = getimagesize($_FILES["file"]["tmp_name"]);
            $image_width = $image_info[0];
            $image_height = $image_info[1];
        }


        if($image_width > 54 && $image_width < 201 && $image_height > 50 && $image_height < 56 ) {
            $upload_dir = 'uploads/email_header_image';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/'.time();
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            if (!empty($_FILES)) {
                $tmpFile = $_FILES['file']['tmp_name'];
                $fname = str_replace("#","",$_FILES['file']['name']);
                $fname = str_replace("#","",$fname);
                $fname = str_replace("#","",$fname);
                $fname = str_replace("#","",$fname);
                $fname = str_replace("&","",$fname);
                $fname = str_replace("&","",$fname);
                $fname = str_replace("&","",$fname);
                $fname = str_replace("&","",$fname);
                $fname = str_replace("%","",$fname);
                $fname = str_replace("%","",$fname);
                $fname = str_replace("%","",$fname);
                $filename = $upload_dir.'/'.$fname;

                if(move_uploaded_file($tmpFile,$filename))
                {
                    $dataval['email_header_url'] = $upload_dir;
                    $dataval['email_header_filename'] = $fname;

                    DB::table('messageus_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
      }
      public function update_messageus_settings(Request $request) {
            $cc_email = $request->get('messageus_cc_email');
            $data['messageus_cc_email'] = $cc_email;

            $check_settings = DB::table('messageus_settings')->where('practice_code',Session::get('user_practice_code'))->first();
            if($check_settings) {
                  DB::table('messageus_settings')->where('id',$check_settings->id)->update($data);
            }
            else{
                  $data['practice_code'] = Session::get('user_practice_code');
                  DB::table('messageus_settings')->insert($data);
            }
            return redirect::back()->with('message', 'MessageUs Setings Saved Sucessfully.');
      }
}