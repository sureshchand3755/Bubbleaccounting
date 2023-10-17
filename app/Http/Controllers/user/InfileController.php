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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DateTime;
use Illuminate\Http\Request;
class InfileController extends Controller {
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
		require_once app_path("Http/helpers.php");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function infile(Request $request){	
		if(Session::has('notepad_attach_add')) {
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add')) {
			Session::forget("file_attach_add");
		}
		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
		$infiles = \App\Models\inFile::get();
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 0;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	public function infile_advance(Request $request){	
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 0;
		return view('user/infile/infile_advance', array('title' => 'InFile', 'clientslist' => $clients, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	public function infile_userupdate(Request $request){		
		$id = $request->get('id');	
		$data['complete_by'] = $request->get('users');
		\App\Models\inFile::where('id', $id)->update($data);		
	}
	public function infile_completedate(Request $request){
		$id = $request->get('id');	
		$dd= $request->get('dateval');
		if($dd != "")
		{
			$date = explode('-',$request->get('dateval'));
			if($date[1] == "Jan") { $month = '01'; }
			elseif($date[1] == "Feb") { $month = '02'; }
			elseif($date[1] == "Mar") { $month = '03'; }
			elseif($date[1] == "Apr") { $month = '04'; }
			elseif($date[1] == "May") { $month = '05'; }
			elseif($date[1] == "Jun") { $month = '06'; }
			elseif($date[1] == "Jul") { $month = '07'; }
			elseif($date[1] == "Aug") { $month = '08'; }
			elseif($date[1] == "Sep") { $month = '09'; }
			elseif($date[1] == "Oct") { $month = '10'; }
			elseif($date[1] == "Nov") { $month = '11'; }
			else{ $month = '12'; }
			$data['complete_date'] = $date[2].'-'.$month.'-'.$date[0];
			\App\Models\inFile::where('id', $id)->update($data);
		}
	}
	public function in_file_statusupdate(Request $request){
		$id = $request->get('id');
		$data['status'] = $request->get('status');
		\App\Models\inFile::where('id', $id)->update($data);
	}
	public function in_file_showincomplete(Request $request){
		$value = $request->get('value');
		if($value == 0){
			$infiles = \App\Models\inFile::get();
		}
		else{
			$infiles = \App\Models\inFile::where('status', 0)->get();
		}
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$i=1;
      	$output='';
      	if(($infiles)){
	        foreach ($infiles as $file) {
	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }
	          $companydetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $file->client_id)->first();
	          $attachments = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();
	          $downloadfile='';
	          if(($attachments)){                        
                foreach($attachments as $attachment){
                    $downloadfile.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $downloadfile ='';
              }
              if(($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>';
              }
              else{
                $deleteall = '';
              }
              $notes_attachments = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();
              $download_notes='';
              if(($notes_attachments)){                        
                foreach($notes_attachments as $attachment){
                    $download_notes.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }
              if(($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>
                ';
              }
              else{
                $delete_notes_all = '';
              }
              $attach_notes_only = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',1)->get();
              $notes_only='';
              if(($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){
                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }
              if(($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }
              $userdrop='';
              if(($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }
              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';
              $hard_files = ($file->hard_files != 0)?'YES':'NO';
	      $output.='
	        <tr id="infile_'.$file->id.'">
	          <td '.$color.' valign="top" >'.$i.'</td>
	          <td '.$color.' valign="top">'.$companydetails->company.'</td>
	          <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
	          <td '.$color.'>
	          '.$downloadfile.'
	          <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
	          '.$deleteall.'<br/><br/>
	          <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000;color: #0300c1;">Notes:</div>
	          <div class="clearfix"></div>
	          '.$download_notes.'
	          <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_all.'
	              <div class="img_div" style="z-index:9999999">
	                <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                <input type="hidden" name="_token" value="'.csrf_token().'" />
	                  <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                  <spam class="error_files"></spam>
	                </form>
	                <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
	                <div class="image_div_attachments">
	                  <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                          <input type="hidden" name="_token" value="'.csrf_token().'" />
                      </form>
	                  <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
	                </div>
	              </div>
	              <div class="notepad_div" style="z-index:9999; position:absolute">
	                <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                <input type="hidden" name="_token" value="'.csrf_token().'" />
	                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                  <spam class="error_files_notepad"></spam>
	                </form>
	              </div>
	          </td>
	          <td '.$color.' valign="top">
	          <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
	          <option value="">Select User</option>'.$userdrop.'</select>
	          </td>
	          <td '.$color.' valign="top">
	              <label class="input-group datepicker-only-init">
	                  <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
	                  <span class="input-group-addon">
	                      <i class="glyphicon glyphicon-calendar"></i>
	                  </span>
	              </label>
	          </td>
	          <td '.$color.' valign="top">
	          '.$notes_only.'
	          <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_only.'
	          <div class="notepad_div_notes" style="z-index:9999; position:absolute">
	            <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	            <input type="hidden" name="_token" value="'.csrf_token().'" />
	              <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	              <input type="hidden" name="hidden_id" value="'.$file->id.'">
	              <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	              <spam class="error_files_notepad"></spam>
	            </form>
	          </div>
	          </td>
	          <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
	          <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
	        </tr>';          
	        $i++;
	        }
	      }
      echo $output;	
	}
	public function infile_client_search(Request $request)
	{
		$value = $request->get('term');
		$details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("status", 0)
            ->where(function ($q) use ($value) {
                $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
            })->get();
		$data=array();
		foreach ($details as $single) {
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id);
        }
         if(($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function infile_clientsearchselect(Request $request){
		$id = $request->get('value');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->first();
		echo json_encode(["client_id" => $client_details->client_id]);
	}
	public function infile_imageupload(Request $request)
	{
		$total = count($_FILES['image_file']['name']);
		$id = $request->get('hidden_id');	
		for($i=0; $i<$total; $i++) {
		 	$filename = str_replace("#","",$_FILES['image_file']['name'][$i]);
		 	 $filename = str_replace("#","",$filename);
			 $filename = str_replace("#","",$filename);
			 $filename = str_replace("#","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("%","",$filename);
			 $filename = str_replace("%","",$filename);
			 $filename = str_replace("%","",$filename);
			$data_img = \App\Models\inFile::where('id',$id)->first();
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/infile_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.time();
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['file_id'] = $data_img->id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;
			$data['textstatus'] = 1;
			\App\Models\inFileAttachment::insert($data);
		}
		$dataval['task_notify'] = 1;
		\App\Models\inFile::where('id',$id)->update($dataval);
		$item = \App\Models\inFile::where('id',$id)->first();
		if(($item))
		{
			$client_id = $item->client_id;
		}
		else{
			$client_id = '';
		}
		return redirect('user/infile_search?client_id='.$client_id.'&infile_item='.$id);
	}
	public function infile_upload_images(Request $request)
	{
		$id = $_GET['file_id'];
		$dataval['task_notify'] = 1;
		\App\Models\inFile::where('id',$id)->update($dataval);
		$data_img = \App\Models\inFile::where('id',$id)->first();
		$upload_dir = 'uploads/infile_image';
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
		 		$data['file_id'] = $data_img->id;
		 		if(isset($_POST['hidden_infile_type']))
		 		{
		 			$type = $_POST['hidden_infile_type'];
		 			if($type == 1){ $data['b'] = 1; }
		 			if($type == 2){ $data['p'] = 1; }
		 			if($type == 3){ $data['s'] = 1; }
		 			if($type == 4){ $data['o'] = 1; }
		 		}
				$data['attachment'] = $fname;
				$data['url'] = $upload_dir;
				$data['textstatus'] = 1;
				$data['status'] = 5;
				$id = \App\Models\inFileAttachment::insertDetails($data);
				$infile_item = \App\Models\inFile::where('id',$data_img->id)->first();
				if(($infile_item)) {
					if($infile_item->integrity_check == 1) {
						$datafile['integrity_check'] = 2;
						\App\Models\inFile::where('id',$data_img->id)->update($datafile);
					}
				}
				echo json_encode(array('id' => $id,'filename' => $fname,'file_id' => $data_img->id));
		 	}
		}
	}
	public function remove_dropzone_attachment(Request $request)
	{
		$attachment_id = $_POST['attachment_id'];
		$file_id = $_POST['file_id'];
		$check_network = \App\Models\inFileAttachment::where('id',$attachment_id)->first();
		\App\Models\inFileAttachment::where('id',$attachment_id)->delete();
		if($check_network->status == 0)
		{
			$count = \App\Models\inFileAttachment::where('file_id',$check_network->file_id)->where('status',0)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['task_notify'] = 0;
				\App\Models\inFile::where('id',$check_network->file_id)->update($dataval);
			}
		}	
	}
	public function infile_delete_image(Request $request)
	{
		$imgid = $request->get('imgid');			
		$check_network = \App\Models\inFileAttachment::where('id',$imgid)->first();
		$url = '';
		\App\Models\inFileAttachment::where('id',$imgid)->delete();
		if($check_network->status == 0)
		{
			$file_id = $check_network->file_id;
			$file_details = \App\Models\inFile::where('id',$file_id)->first();
			if(($file_details))
			{
				$url = URL::to('user/infile_search?client_id='.$file_details->client_id.'&infile_item='.$file_id);
			}
			$count = \App\Models\inFileAttachment::where('file_id',$check_network->file_id)->where('status',0)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['task_notify'] = 0;
				\App\Models\inFile::where('id',$check_network->file_id)->update($dataval);
			}
		}
		echo $url;
	}
	public function infile_delete_all_image(Request $request)
	{
		$id = $request->get('id');	
		\App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 0)->delete();
		$count = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{
		}
		else{
			$dataval['task_notify'] = 0;
			\App\Models\inFile::where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_image(Request $request)
	{
		$id = $request->get('id');	
		$details = \App\Models\inFile::where('id',$id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$files = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function infile_download_rename_all_image(Request $request)
	{
		$id = $request->get('id');
		$details = \App\Models\inFile::where('id',$id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$clientname = '';
		if($client_details){
			$clientname = $client_details->company;
		}
		$date_added = '';
		if($details){
			$date_added = $details->date_added;
		}
		$files = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(($files))
		{
			$public_dir=public_path();
			$zipFileName = $clientname.'_'.date('d M Y',strtotime($date_added)).'.zip';
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}

					if(file_exists($file->url.'/'.$file->attachment) && is_file($file->url.'/'.$file->attachment)) {
						$zip->addFile($file->url.'/'.$file->attachment,$filename);
					}
				}
				$zip->close();
			}
			echo $zipFileName;
		}
	}
	public function infile_notepad_upload(Request $request)
	{
		$id = $request->get('hidden_id');
		$data_img = \App\Models\inFile::where('id',$id)->first();
		$company_detail = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $data_img->client_id)->first();
		$count = \App\Models\inFileAttachment::where('file_id',$id)->where('status',0)->where('notes_type',1)->count();
		$counts = (int)$count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 
		$filename = $file_name.'-'.$counts;	
		$contents = $request->get('notepad_contents');
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 0;
		$data['notes_type'] = 1;
		$data['textstatus'] = 1;
		\App\Models\inFileAttachment::insert($data);
		$dataval['task_notify'] = 1;
		\App\Models\inFile::where('id',$id)->update($dataval);
		return redirect::back();
	}
	public function infile_notepad_upload_notes(Request $request)
	{
		$id = $request->get('hidden_id');
		$data_img = \App\Models\inFile::where('id',$id)->first();
		$company_detail = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $data_img->client_id)->first();
		$count = \App\Models\inFileAttachment::where('file_id',$id)->where('status',1)->where('notes_type',1)->count();
		$counts = (int)$count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 
		$filename = $file_name.' - infile - '.$counts;	
		$contents = $request->get('notepad_contents');
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 1;
		$data['notes_type'] = 1;
		$data['textstatus'] = 1;
		\App\Models\inFileAttachment::insert($data);
		return redirect::back();
	}
	public function infile_delete_all_notes_only(Request $request)
	{
		$id = $request->get('id');	
		\App\Models\inFileAttachment::where('file_id',$id)->where('status', 1)->where('notes_type', 1)->delete();
	}
	public function infile_download_all_notes_only(Request $request)
	{
		$id = $request->get('id');	
		$details = \App\Models\inFile::where('id',$id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$files = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 1)->where('notes_type', 1)->get();
		if(($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function infile_delete_all_notes(Request $request)
	{
		$id = $request->get('id');	
		\App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 1)->delete();
		$count = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{
		}
		else{
			$dataval['task_notify'] = 0;
			\App\Models\inFile::where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_notes(Request $request)
	{
		$id = $request->get('id');	
		$details = \App\Models\inFile::where('id',$id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$files = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 1)->get();
		if(($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function infile_commonclient_search(Request $request)
	{
		$value = $request->get('term');
		$details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("status", 0)
            ->where(function ($q) use ($value) {
                $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
            })->get();
		$data=array();
		foreach ($details as $single) {
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id);
        }
         if(($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function infile_commonclientsearchselect(Request $request){
		$id = $request->get('value');
		$infiles = \App\Models\inFile::where('client_id', $id)->get();
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$i=1;
	    $output='';
	    if(($infiles)){
	       foreach ($infiles as $file) {
	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }
	          $companydetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $file->client_id)->first();
	          $attachments = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();
	          $downloadfile='';
	          if(($attachments)){                        
                foreach($attachments as $attachment){
                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
                    if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
                    $downloadfile.= '<div class="file_attachment_div"><input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label> 
                    	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                    	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
                    	<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a>
                    </div>';
                }
              }
              else{
                $downloadfile ='';
              }
              /*<i class="fa fa-download download_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>*/
              if(($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>
                <i class="fa fa-file-text-o summary_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Summary"></i>';
              }
              else{
                $deleteall = '';
              }
              if(($attachments))
              {
                $span = '<span style="color:#000">There are '.count($attachments).' file(s)</span>';
              }
              else{
                $span = '';
              }
              $notes_attachments = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();
              $download_notes='';
              if(($notes_attachments)){                        
                foreach($notes_attachments as $attachment){
                	if($attachment->check_file == 1) { $checked = 'checked'; } else { $checked = ''; }
                    $download_notes.= '<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label><a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }
              if(($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_all = '';
              }
              $attach_notes_only = \App\Models\inFileAttachment::where('file_id',$file->id)->where('status',1)->get();
              $notes_only='';
              if(($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){
                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }
              if(($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }
              $userdrop='';
              if(($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }
              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';
              $hard_files = ($file->hard_files != 0)?'YES':'NO';
	      $output.='
	        <tr id="infile_'.$file->id.'">
              <td '.$color.' valign="top" >'.$i.'</td>
              <td '.$color.' valign="top">'.$companydetails->company.'</td>
              <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
              <td '.$color.'>
              '.$downloadfile.'
              <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
              '.$deleteall.'<br/><br/>
              <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000">Notes</div>
              <div class="clearfix"></div>
              '.$download_notes.'
              <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_all.'
                  <div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                    <input type="hidden" name="_token" value="'.csrf_token().'" />
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                          <input type="hidden" name="_token" value="'.csrf_token().'" />
                      </form>
                      <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                  </div>
                  <div class="notepad_div" style="z-index:9999; position:absolute">
                    <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files_notepad"></spam>
                    </form>
                  </div>
              </td>
              <td '.$color.' valign="top">
              <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
              <option value="">Select User</option>'.$userdrop.'</select>
              </td>
              <td '.$color.' valign="top">
                  <label class="input-group auto_save_date">
                      <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
              </td>
              <td '.$color.' valign="top">
              '.$notes_only.'
              <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_only.'
              <div class="notepad_div_notes" style="z-index:9999; position:absolute">
                <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                  <spam class="error_files_notepad"></spam>
                </form>
              </div>
              </td>
              <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
              <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
            </tr>';      
	        $i++;
	        }
	      }
      echo json_encode(array('result_row' => $output));	
	}
	public function add_notepad_contents(Request $request)
	{
		$contents = $request->get('contents');
		$clientid = $request->get('clientid');
		$company_detail = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clientid)->first();
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if(Session::has('notepad_attach_add'))
		{
			$count = count(Session::get('notepad_attach_add'));
			$counts = (int)$count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	
			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = Session::get('notepad_attach_add');
			array_push($getsession,$arrayval);
		}
		else{
			$count = 0;
			$counts = (int)$count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	
			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = array();
			array_push($getsession,$arrayval);
		}
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$sessn=array('notepad_attach_add' => $getsession);
		Session::put($sessn);
		echo $filename.".txt";
	}
	public function infile_upload_images_add(Request $request)
	{
		$upload_dir = 'uploads/infile_image';
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
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $filename = $upload_dir.'/'.$fname;
			 $filetype=0; 
			if(isset($_POST['hidden_infile_type']))
			{
				$type = $_POST['hidden_infile_type'];
				if($type == 1){ $filetype = 'b'; }
				if($type == 2){ $filetype = 'p'; }
				if($type == 3){ $filetype = 's'; }
				if($type == 4){ $filetype = 'o'; }
			}
			 $arrayval = array('attachment' => $fname,'url' => $upload_dir, 'filetype'=>$filetype);
	 		if(Session::has('file_attach_add'))
			{
				$getsession = Session::get('file_attach_add');
			}
			else{
				$getsession = array();
			}
			array_push($getsession,$arrayval);
			$sessn=array('file_attach_add' => $getsession);
			Session::put($sessn);
			move_uploaded_file($tmpFile,$filename);
		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0,'count'=>count($getsession)));
		}
	}
	public function create_new_file(Request $request)
	{
		$total_count = $request->get("total_count_files");
		$clientid= $request->get('clientid');
		$received_date = date('Y-m-d', strtotime($request->get("received_date"))); //explode('-',$request->get('received_date'));
		$search_type = $request->get('hidden_client_id');
		//$received_date = $date_received[2].'-'.$date_received[0].'-'.$date_received[1];
		//$date_added = explode('-',$request->get('added_date'));
		$added_date = date('Y-m-d', strtotime($request->get("added_date"))); //$date_added[2].'-'.$date_added[0].'-'.$date_added[1];
		$data['client_id'] = $clientid;
		$data['data_received'] = $received_date;
		$data['date_added'] = $added_date;
		$data['description'] = $request->get('description');
		$data['hard_files'] = ($request->get("hard_files_checkbox") != 0)?1:0;
		$data['percent_one'] = '0.00';
		$data['percent_two'] = '9.00';
		$data['percent_three'] = '13.50';
		$data['percent_four'] = '23.00';
		$data['percent_five'] = '21.00';
		$file_id = \App\Models\inFile::insertDetails($data);
		if(Session::has('file_attach_add'))
		{
			$files = Session::get('file_attach_add');
			$upload_dir = 'uploads/infile_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($file_id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
     		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            if(file_exists($dir.'/'.$file))
			            {
			            	rename($dir.'/'.$file,$dirNew.'/'.$file);
			            }
		            }
		            closedir($dh);
		        }
		    }
			foreach($files as $file)
			{				
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				if($file['filetype'] == 'b'){ $dataval['b'] = 1; }
				if($file['filetype'] == 'p'){ $dataval['p'] = 1; }
				if($file['filetype'] == 's'){ $dataval['s'] = 1; }
				if($file['filetype'] == 'o'){ $dataval['o'] = 1; }
				$dataval['file_id'] = $file_id;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				$dataval['textstatus'] = 1;
				\App\Models\inFileAttachment::insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			\App\Models\inFile::where('id',$file_id)->update($datavalinfile);
		}
		if(Session::has('notepad_attach_add'))
		{
			$files = Session::get('notepad_attach_add');
			foreach($files as $file)
			{
				$upload_dir = 'uploads/infile_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($file_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				if(file_exists("uploads/infile_image/temp/".$file['attachment']))
				{
					rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				}
				$dataval['file_id'] = $file_id;
				$dataval['attachment'] = $file['attachment'];
				$dataval['url'] = $upload_dir;
				$dataval['notes_type'] = 1;
				$dataval['textstatus'] = 1;
				\App\Models\inFileAttachment::insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			\App\Models\inFile::where('id',$file_id)->update($datavalinfile);
		}
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clientid)->first();
		if(Session::has('file_attach_add'))
		{
			$countupdated = \App\Models\inFileAttachment::where('file_id',$file_id)->where('notes_type',0)->count();
			return redirect::back()->with('message', 'InFiles for "'.$client_details->company.'" is Created Sucessfully.')->with('countupdated', $countupdated)->with('total_count', $total_count)->with('client_session_id', $clientid)->with('file_id', $file_id)->with('view_button', '<a href="'.URL::to('user/infile_search').'?client_id='.$clientid.'" class="fa fa-eye common_black_button" style="margin-right:13px;margin-top:-9px" title="View Client Infiles"></a>')->with('search_type',$search_type);
		}
		else{
			return redirect::back()->with('message', 'InFiles for "'.$client_details->company.'" is Created Sucessfully.')->with('client_session_id', $clientid)->with('view_button', '<a href="'.URL::to('user/infile_search').'?client_id='.$clientid.'" class="fa fa-eye common_black_button" style="margin-right:13px;margin-top:-9px" title="View Client Infiles"></a>')->with('search_type',$search_type);
		}
	}
	public function clear_session_attachments(Request $request)
	{
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	}
	public function delete_file_link(Request $request)
	{
		$file = 'public/'.$request->get('result');
		if (!unlink($file))
		  {
		  echo ("Error deleting $file");
		  }
		else
		  {
		  echo ("Deleted $file");
		  }
	}
	public function infile_search(Request $request){
		\App\Models\inFileAttachment::where('status',5)->where('secondary',0)->delete();
		$clientid = $request->get('client_id');
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		
		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	    if(isset($_GET['loadtype'])) {
	    	if($_GET['loadtype'] == "1") {
	    		$infiles = \App\Models\inFile::where('client_id', $clientid)->orderBy('id','desc')->get();
	    	}
	    }
	    elseif(isset($_GET['infile_item'])) {
	    	if(isset($_GET['loadtype'])) {
	    		$infiles = \App\Models\inFile::where('client_id', $clientid)->orderBy('id','desc')->get();
	    	}
	    	else {
	    		$infileid = $_GET['infile_item'];
	    		$infile_settings = DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->first();
				$infiles1 = \App\Models\inFile::where('client_id', $clientid)->orderBy('id','desc')->limit($infile_settings->loadcount);

				$openedInfiles = \App\Models\inFile::where('client_id', $clientid)->where('id',$infileid);

				$infiles = $infiles1->union($openedInfiles)->get();
	    	}
	    }
	    else{
	    	$infile_settings = DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->first();
			$infiles = \App\Models\inFile::where('client_id', $clientid)->orderBy('id','desc')->limit($infile_settings->loadcount)->get();
	    }
	    
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 1;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	public function infile_internal(Request $request){
		$id = $request->get('id');	
		$data['internal'] =  $request->get('internal');		
		\App\Models\inFile::where('id', $id)->update($data);
	}
	public function fileattachment_status(Request $request)
	{
		$id = $request->get('id');	
		$status = $request->get('status');	
		$data['check_file'] =  $status;		
		\App\Models\inFileAttachment::where('id', $id)->update($data);
	}
	public function infile_email_notify_tasks_pdf(Request $request)
	{
		$email = $request->get('email');
		$file_id = $request->get('file_id');
		$client_id = $request->get('clientid');
		$time = $request->get('timeval');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$client_id)->first();
		$file_details = \App\Models\inFile::where('id',$file_id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$file_details->client_id)->first();
		$infile_settings = DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $admin_cc = $infile_settings->cc_email;
        $user_details =\App\Models\User::where('user_id',Session::get('userid'))->first();
		$from = $user_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = getEmailLogo('infile');
		$data['file_details'] = $file_details;
		$data['username'] = $user_details->firstname;
		$data['client_name'] = $client_details->company;
		$contentmessage = view('user/file_email_notify_tasks', $data);
		$subject = 'Easypayroll.ie: Infile Task Notification';
		$email = new PHPMailer();
		if($to != '')
		{
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->AddCC($cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
		}
	}
	public function change_attachment_text_status(Request $request)
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 1;
		\App\Models\inFileAttachment::where('id',$id)->update($dataval);
	}
	public function remove_attachment_text_status(Request $request)
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 0;
		$dataval['textval'] = '';
		\App\Models\inFileAttachment::where('id',$id)->update($dataval);
	}
	public function update_fileattachment_textval(Request $request)
	{
		$id = $_GET['id'];
		$dataval['textval'] = $_GET['input'];
		\App\Models\inFileAttachment::where('id',$id)->update($dataval);
	}
	public function get_attachment_details(Request $request)
	{
		$id = $_GET['id'];
		$src = $_GET['element'];
		$details = \App\Models\inFileAttachment::where('id',$id)->first();
		if($details->textval != "" && $details->textstatus == 1)
		{
			$filename = "QuickID_".$details->textval."_".$details->attachment;
		}
		else{
			$filename = '';
		}
		echo $filename;
	}
	public function report_infile(Request $request){
		$id = $request->get('id');
		$infilelist = \App\Models\inFile::groupBy('client_id')->where('status',0)->get();
		if($id == 0){				
				$output = '';
				$i=1;
				if(($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $infile->client_id)->first();	
						if($client_details)	{
							$output.='
								<tr>
									<td>'.$i.'</td>
									<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
									<td align="left">'.$client_details->client_id.'</span></td>
									<td align="left">'.$client_details->firstname.'</td>
									<td align="left">'.$client_details->company.'</td>
								</tr>
							';
						}				
						
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{				
				$output = '';
				$i=1;
				if(($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $infile->client_id)->first();
						if($client_details)	{
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
							';
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
	}
	public function infile_report_incomplete(Request $request){
		$id = $request->get('id');	
		$type = $request->get('type');	
		if($id == 0){
				$infilelist = \App\Models\inFile::groupBy('client_id')->where('status', $id)->get();				
				$output = '';
				$i=1;
				if(($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $infile->client_id)->first();	
						if($type == 1){
							if($client_details) {
								$output.='
									<tr>
										<td>'.$i.'</td>
										<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
										<td align="left">'.$client_details->client_id.'</span></td>
										<td align="left">'.$client_details->firstname.'</td>
										<td align="left">'.$client_details->company.'</td>
									</tr>
								';
							}
						}
						else{
							if($client_details) {
									$output.='
									<tr>
										<td>'.$i.'</td>
										<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
										<td align="left">'.$client_details->client_id.'</span></td>
										<td align="left">'.$client_details->firstname.'</td>
										<td align="left">'.$client_details->company.'</td>
									</tr>
								';
							}	
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{	$infilelist = \App\Models\inFile::groupBy('client_id')->get();
				$output = '';
				$i=1;
				if(($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $infile->client_id)->first();
						if($type == 1){
							if($client_details) {
								$output.='
									<tr>
										<td>'.$i.'</td>
										<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
										<td align="left">'.$client_details->client_id.'</span></td>
										<td align="left">'.$client_details->firstname.'</td>
										<td align="left">'.$client_details->company.'</td>
									</tr>
								';
							}
						}
						else{
							if($client_details) {
									$output.='
									<tr>
										<td>'.$i.'</td>
										<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
										<td align="left">'.$client_details->client_id.'</span></td>
										<td align="left">'.$client_details->firstname.'</td>
										<td align="left">'.$client_details->company.'</td>
									</tr>
								';
							}
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
	}
	public function infile_report_pdf(Request $request)
	{
		$ids = explode(",",$request->get('value'));
		$status = $request->get('status');
		if($status == 1){
			$infile_client = \App\Models\inFile::join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			$infile_client = \App\Models\inFile::join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->get();
		}
		$output = '';
		$i=1;
		if(($infile_client)){
			foreach ($infile_client as $file) {				
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $file->client_id)->first();
				/*$file_count='';
				if(($infile_details)){
					foreach ($infile_details as $single_file) {						
						$file_count.= \App\Models\inFileAttachment::where('file_id', $single_file->id)->count();
					}
				}*/
				$file_count = \App\Models\inFileAttachment::where('file_id', $file->id)->count();
				if(($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = '';
				}			
				$output.='
					<tr>
						<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$clientname.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->data_received.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->date_added.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file_count.'</td>
					</tr>
				';
				$i++;
			}
		}			
		else{
			$output='<td colspan="4" align="center">Empty</td>';
		}
		echo $output;
	}
	public function download_infile_report_pdf(Request $request)
	{
		$htmlval = $request->get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('public/infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}
	public function infile_report_csv(Request $request, $id=""){		
		$ids = explode(",",$request->get('value'));
		$status = $request->get('status');
		if($status == 1){
			$infile_client = \App\Models\inFile::join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			$infile_client = \App\Models\inFile::join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->get();
		}
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'No. of Files');
		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('public/infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = \App\Models\inFileAttachment::where('file_id', $singlefile->id)->count();
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $singlefile->client_id)->first();				
		      	$columns_2 = array($i, $client_details->firstname.' '.$client_details->surname, $singlefile->data_received, $singlefile->date_added, $file_count);
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}
	public function infile_report_csv_single(Request $request, $id=""){		
		$ids = explode(",",$request->get('value'));
		$status = $request->get('status');
		if($status == 1){
			$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->where('status',0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'Complete By', 'Complete Date', 'Description', 'No. of Files', 'Attachments', 'Status');
		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('public/infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = \App\Models\inFileAttachment::where('file_id', $singlefile->id)->count();
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $singlefile->client_id)->first();
				$file_name = \App\Models\inFileAttachment::where('file_id', $singlefile->id)->orderBy('id','asc')->get();
				$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $singlefile->complete_by)->first();
				if(($user_details)){
					$user = $user_details->lastname.' '.$user_details->firstname;
				}
				else{
					$user = 'N/A';
				}
				if($singlefile->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $singlefile->complete_date;
				}
				if($singlefile->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}
				$first_file_name = \App\Models\inFileAttachment::where('file_id', $singlefile->id)->orderBy('id','asc')->first();
				if(($first_file_name))
				{
					if($first_file_name->textstatus == 0){
						$first_file = $first_file_name->attachment;
					}
					else{
						$first_file = $first_file_name->attachment.'('.$first_file_name->textval.')';
					}
				}
				else{
					$first_file = '';
				}
				if($singlefile->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $singlefile->description;
				}
		      	$columns_2 = array($i, $client_details->firstname.' '.$client_details->surname, $singlefile->data_received, $singlefile->date_added, $user, $complete_date,$description, $file_count, $first_file, $status);
				fputcsv($file, $columns_2);
				if(($file_name))
				{
					$isingle = 0;
					foreach($file_name as $single_file)
					{
						if($isingle != 0)
						{
							if($single_file->textstatus==0){
								$columns_3 = array('','','','','','','','',$single_file->attachment,'');
							}
							else{
								$columns_3 = array('','','','','','','','',$single_file->attachment.'('.$single_file->textval.')','');
							}
							fputcsv($file, $columns_3);
						}
						$isingle++;
					}
				}
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}
	public function infile_report_pdf_single(Request $request)
	{
		$ids = explode(",",$request->get('value'));	
		$status = $request->get('status');
		if($status == 1){
			$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->where('status', 0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = \App\Models\inFile::whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}
		$output = '';
		$i=1;
		if(($infile_client)){
			foreach ($infile_client as $file) {
				$file_count = \App\Models\inFileAttachment::where('file_id', $file->id)->count();
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $file->client_id)->first();
				$infile_name = \App\Models\inFileAttachment::where('file_id', $file->id)->get();
				$username =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $file->complete_by)->first();
				$infile_attached='';
				if(($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 0){
								if($file_single->textstatus == 0){
									$infile_attached.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached ='';
				}
				$infile_attached_notes='';
				if(($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 1){
								if($file_single->textstatus == 0){
									$infile_attached_notes.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached_notes.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached_notes ='';
				}
				$compete_notes ='';
				if(($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 1){							
							$compete_notes.= $file_single->attachment.'<br/>';
						}
					}
				}
				else{
					$compete_notes ='';
				}
				if(($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = 'N/A';
				}
				if($file->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $file->complete_date;
				}
				if(($username)){
					$user = $username->lastname.' '.$username->firstname;
				}
				else{
					$user = 'N/A';
				}
				if($file->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}
				if($file->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $file->description;
				}
				$output.='
					<style>									
					.table_td_class_left{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:30%; float:left; padding:3px;}
					.table_td_class_right{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:70%; float:left; padding:3px;}
					</style>
					<div style="width:100%; height:auto; margin-bottom:100px;">
						<table cellspacing="0" cellpadding="0" border="0px" style="width:80%; padding-left:10%; ">
						<tr>
							<td class="table_td_class_left">S.No</td>
							<td class="table_td_class_right">'.$i.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Client Name</td>
							<td class="table_td_class_right">'.$clientname.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Received</td>
							<td class="table_td_class_right">'.$file->data_received.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Added</td>
							<td class="table_td_class_right">'.$file->date_added.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete By</td>
							<td class="table_td_class_right">'.$user.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete date</td>
							<td class="table_td_class_right">'.$complete_date.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Description</td>
							<td class="table_td_class_right">'.$description.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">No. of Files</td>
							<td class="table_td_class_right">'.$file_count.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Files List</td>
							<td class="table_td_class_right">Attachment(s):<br/>'.$infile_attached.'<br/>Note(s):<br/>'.$infile_attached_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Completion Notes:</td>
							<td class="table_td_class_right">'.$compete_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Status</td>
							<td class="table_td_class_right">'.$status.'</td>
						</tr>
						</table>
					</div>			
				';
				$i++;
			}
		}			
		else{
			$output='<tr><td colspan="2" align="center">Empty</td><td>';
		}
		echo $output;
	}
	public function download_infile_report_pdf_single(Request $request)
	{
		$htmlval = $request->get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('public/infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}
	public function infile_task_client_search(Request $request)
	{
		$value = $request->get('client_id');
		$single = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->Where('client_id',$value)->first();
		if(($single))
		{
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
			echo json_encode(array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id));
		}
		else{
			echo json_encode(array('value'=>'No Data Found','id'=>''));
		}
	}
	public function change_attachment_bpso_status(Request $request)
	{
		$type = $request->get('type');
		//$status = $request->get('status');
		$attach_id = $request->get('attachment_id');
		//$data[$type] = $status;
		if($type == 1){
			$data['b'] = 1;
			$data['p'] = 0;
			$data['s'] = 0;
			$data['o'] = 0;
		}
		if($type == 2){
			$data['b'] = 0;
			$data['p'] = 1;
			$data['s'] = 0;
			$data['o'] = 0;
		}
		if($type == 3){
			$data['b'] = 0;
			$data['p'] = 0;
			$data['s'] = 1;
			$data['o'] = 0;
		}
		if($type == 4){
			$data['b'] = 0;
			$data['p'] = 0;
			$data['s'] = 0;
			$data['o'] = 1;
		}
		\App\Models\inFileAttachment::where('id',$attach_id)->update($data);
		\App\Models\inFileAttachment::where('attach_id',$attach_id)->update($data);
	}
	public function infile_download_bpso_all_image(Request $request)
	{
		$type = $request->get('type');
		$idd = $request->get('id');
		$page = $request->get('page');
		$filename = $request->get('filename');

		$details = \App\Models\inFile::where('id',$idd)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$public_dir=public_path();
		if($filename == ''){
			$zipFileName = $client_details->company.'_'.date('d M Y',strtotime($details->date_added)).'_'.time().'.zip';
		}
		else {
			$zipFileName = $filename;
		}

		$pageoffset = $page - 1;
		$offset = $pageoffset * 500;

		$description = RemoveSpecialChar($details->description);
		if($description == ""){
			$description = date('d-M-Y', strtotime($details->date_added));
		}

		$files = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->offset($offset)->limit(500)->get();
		if(is_countable($files) && count($files) > 0)
		{
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}
					$filenameval = $file->url.'/'.$file->attachment;
				
					if (file_exists($filenameval) && is_file($filenameval)) {
						$zip->addFile($file->url.'/'.$file->attachment,$filename);
					}
				}
				$zip->close();
			}
		}

		$total_count = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->count();
		echo json_encode(array('total_count' => $total_count, 'filename' => $zipFileName, 'infile_itemname' => date('d-M-Y', strtotime($details->date_added)).' '.$details->description));
	}
	public function bpso_all_check(Request $request){
		$id = $request->get('id');
		$type = $request->get('type');
		$infile_list = \App\Models\inFileAttachment::where('file_id', $id)->where('notes_type',0)->get();
		if(($infile_list)){
			foreach ($infile_list as $file) {
				$count=$file->b+$file->p+$file->s+$file->o;
				if($count == 0){
					if($type == "1"){
						$data['b'] = 1;
						$data['p'] = 0;
						$data['s'] = 0;
						$data['o'] = 0;
					}
					if($type == "2"){
						$data['b'] = 0;
						$data['p'] = 1;
						$data['s'] = 0;
						$data['o'] = 0;					
					}
					if($type == "3"){
						$data['b'] = 0;
						$data['p'] = 0;
						$data['s'] = 1;
						$data['o'] = 0;					
					}
					if($type == "4"){
						$data['b'] = 0;
						$data['p'] = 0;
						$data['s'] = 0;
						$data['o'] = 1;						
					}
					\App\Models\inFileAttachment::where('id', $file->id)->update($data);				
				}				
			}
		}
		$attachments = \App\Models\inFileAttachment::where('file_id', $id)->where('status',0)->where('notes_type', 0)->where('secondary',0)->get();
		$file = \App\Models\inFile::where('id', $id)->first();
		if($file->status == 0){
	        $staus = 'fa-check'; 
	        $statustooltip = 'Complete Infile';
	        $disable = '';
	        $disable_class = '';
	        $color='';
	      }
	      else{
	        $staus = 'fa-times';
	        $statustooltip = 'InComplete Infile';
	        $disable = 'disabled';
	        $disable_class = 'disable_class';
	        $color = 'style="color:#f00;"';
	      }
		$downloadfile ='<div class="col-md-12">
	              		<table class="table_bspo table-fixed-header" id="bspo_id_'.$file->id.'" style="width:100%;">
	              			<thead>
				                <tr>
				                  <th style="width:18%;text-align:left">
				                  	<a data-toggle="popover" data-container="body" data-placement="right" type="button" data-html="true" href="javascript:" id="login_'.$file->id.'" class="auto_increment" data-element="'.$file->id.'" style="margin-left: 18%;">AutoQue</a>
				                  	<div id="popover-content-login_'.$file->id.'" class="hide">
										<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="purchase_item_auto_'.$file->id.'" value="1"><label for="purchase_item_auto">Purchase Item</label><br/>
										<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="sales_item_auto_'.$file->id.'" value="2"><label for="sales_item_auto">Sales Item</label>
										<div class="form-data">
											<h4>Enter Number:</h4>
											<input type="number" class="form-control auto_number_value_'.$file->id.'" id="auto_number_value" value="">
											<h4>Enter Increment Value:</h4>
											<input type="number" class="form-control inc_number_value_'.$file->id.'" id="inc_number_value" value="">
											<input type="button" name="submit_auto_value" class="submit_auto_value common_black_button" data-element="'.$file->id.'" value="Number Now" style="margin-top: 10px;">
											<input type="button" name="submit_re_number" class="submit_re_number common_black_button" data-element="'.$file->id.'" value="RE-Number">
										</div>
									</div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in B Category" id="'.$file->id.'" data-element="1">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_b_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in B Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in P Category" id="'.$file->id.'" data-element="2">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_p_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in P Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in S Category" id="'.$file->id.'" data-element="3">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_s_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in S Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in O Category" id="'.$file->id.'" data-element="4">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_o_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in O Category"></i>
				                    </div>
				                  </th>
				                  <th class="td_input td_supplier" style="width:10%;font-weight:600;text-align:center" data-element="'.$file->id.'">Supplier/Customer</th>
				                  <th class="td_input td_code" style="font-weight:600;text-align:center;width:5%;">Code</th>
				                  <th class="td_input td_date" style="font-weight:600;text-align:center;width:7%;">Date</th>
				                  <th class="td_input td_percent_one" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_one_text">'.$file->percent_one.'</spam>
				                  	<div class="percent_one_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_one" class="change_percent_one form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_one" class="common_black_button submit_percent_one" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_two" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_two_text">'.$file->percent_two.'</spam>
				                  	<div class="percent_two_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_two" class="change_percent_two form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_two" class="common_black_button submit_percent_two" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_three" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_three_text">'.$file->percent_three.'</spam>
				                  	<div class="percent_three_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_three" class="change_percent_three form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_three" class="common_black_button submit_percent_three" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_five" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_five_text">'.$file->percent_five.'</spam>
				                  	<div class="percent_five_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_five" class="change_percent_five form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_five" class="common_black_button submit_percent_five" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_four" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_four_text">'.$file->percent_four.'</spam>
				                  	<div class="percent_four_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_four" class="change_percent_four form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_four" class="common_black_button submit_percent_four" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input" style="font-weight:600;text-align:center;border-left:1px solid #b5b3b3;width:5%;">Net</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:5%;">VAT</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;border-right:1px solid #b5b3b3;width:5%;">Gross</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Currency</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Value</th>
				                  <th class="td_input" style="width:20px;font-weight:600;text-align:center;width:2%;"></th>
				                </tr>
			                </thead>
			                <tbody>';                   
			                foreach($attachments as $attachment){
			                	$get_sub_attachments = \App\Models\inFileAttachment::where('attach_id',$attachment->id)->where('secondary',1)->orderBy('id','asc')->get();
			                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
								if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
								if($attachment->b == 1) {  $bchecked = 'checked'; } else { $bchecked = ''; }
								if($attachment->p == 1) {  $pchecked = 'checked'; } else { $pchecked = ''; }
								if($attachment->s == 1) {  $schecked = 'checked'; } else { $schecked = ''; }
								if($attachment->o == 1) {  $ochecked = 'checked'; } else { $ochecked = ''; }
								if($attachment->p == 1) { $attach_disabled = ''; }
								elseif($attachment->s == 1) { $attach_disabled = ''; }
								else { $attach_disabled = 'disabled'; }
								if($attachment->flag == 0) { $flag = '<i class="fa fa-flag flag_gray fileattachment"></i>'; }
				                elseif($attachment->flag == 1) { $flag = '<i class="fa fa-flag flag_orange fileattachment"></i>'; }
				                elseif($attachment->flag == 2) { $flag = '<i class="fa fa-flag flag_red fileattachment"></i>'; }
								$downloadfile.= '<tr class="attachment_tr attachment_tr_main" data-element="'.$file->id.'">
									<td style="min-width:300px;max-width:300px;">
										<div class="file_attachment_div" style="width:100%">
										  	<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label>
										  	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
										  	'.$flag.'
										  	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
											<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'" '.$color.' data-toggle="tooltip" title="'.$attachment->attachment.'">'.substr($attachment->attachment,0,15).'</a>
										</div>
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="b_check" id="b_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$bchecked.' title="Bank"><label for="b_check_'.$attachment->id.'" title="Bank">B</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="p_check" id="p_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$pchecked.' title="Purchases"><label for="p_check_'.$attachment->id.'" title="Purchases">P</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="s_check" id="s_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$schecked.' title="Sales"><label for="s_check_'.$attachment->id.'" title="Sales">S</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="o_check" id="o_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$ochecked.' title="Other Sundry"><label for="o_check_'.$attachment->id.'" title="Other Sundry">O</label> 
									</td>';
									if($file->show_previous == 1)
									{
										$downloadfile.='<td class="td_input">
											<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="'.$attachment->supplier.'" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="'.$attachment->code.'" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="'.$attachment->date_attachment.'" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_one).'" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_two).'" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_three).'" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_five).'" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_four).'" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input" style="border-left:1px solid #b5b3b3">
											<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->net).'" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->vat).'" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input" style="border-right:1px solid #b5b3b3">
											<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->gross).'" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="'.$attachment->currency.'" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="'.$attachment->value.'" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>';
									}
									else{
										$downloadfile.='<td class="td_input">
											<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input" style="border-left:1px solid #b5b3b3">
											<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input" style="border-right:1px solid #b5b3b3">
											<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'">
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'">
										</td>';
									}
									$downloadfile.='<td class="td_input">
										<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
										<a href="javascript:" class="fa fa-plus-circle add_secondary" data-element="'.$attachment->id.'" title="Add Seconday Line"></a>
										<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
									</td>
								</tr>';
								if(($get_sub_attachments))
				                {
				                  foreach($get_sub_attachments as $sub)
				                  {
				                    if($sub->p == 1) { $attach_sub_disabled = ''; }
				                    elseif($sub->s == 1) { $attach_sub_disabled = ''; }
				                    else { $attach_sub_disabled = 'disabled'; }
				                    if($sub->textstatus == 1) { $texticonsub="display:none"; $hidesub = 'display:initial'; } else { $texticonsub="display:initial"; $hidesub = 'display:none'; }
				                    if($sub->flag == 0) { $flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>'; }
					                elseif($sub->flag == 1) { $flag_sub = '<i class="fa fa-flag flag_orange_sub fileattachment"></i>'; }
					                elseif($sub->flag == 2) { $flag_sub = '<i class="fa fa-flag flag_red_sub fileattachment"></i>'; }
				                    $downloadfile.= '<tr class="sub_attachment attachment_tr attachment_tr_'.$attachment->id.'" data-element="'.$file->id.'" data-value="'.$sub->id.'">
				                      <td colspan="5">
			                        	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$sub->id.'" value="'.$sub->textval.'" placeholder="Add Text" style="margin-left:7.5%;'.$hidesub.'">
			                        	'.$flag_sub.'
				                      </td>';
				                      if($file->show_previous == 1)
									  {
					                      $downloadfile.= '<td class="td_input">
					                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="'.$sub->supplier.'" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="'.$sub->code.'" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="'.$sub->date_attachment.'" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_one).'" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_two).'" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_three).'" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_five).'" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_four).'" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input" style="border-left:1px solid #b5b3b3">
					                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->net).'" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->vat).'" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input" style="border-right:1px solid #b5b3b3">
					                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->gross).'" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input">
												<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="'.$sub->currency.'" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
											</td>
											<td class="td_input">
												<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="'.$sub->value.'" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
											</td>';
				                  	  }
				                  	  else{
				                  	  	$downloadfile.= '<td class="td_input">
				                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_sub_disabled.'>
										</td>
				                      <td class="td_input">
				                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input" style="border-left:1px solid #b5b3b3">
				                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input" style="border-right:1px solid #b5b3b3">
				                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
										</td>';
				                  	  }
				                      $downloadfile.= '<td class="td_input">
				                        <i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
				                      </td>
				                    </tr>';
				                  }
				                }
				                $downloadfile.='<tr class="show_iframe show_iframe_'.$attachment->id.'" style="display:none;">
			                    	<td colspan="15">
				                    	<a href="javascript:" class="show_iframe_prev common_black_button">Previous</a> 
						          		<a href="javascript:" class="show_iframe_next common_black_button">Next</a> 
						          		<a href="javascript:" class="show_iframe_hide common_black_button">Hide</a>
						          		<label class="pdf_multipage" style="margin-left: 10px;">Note: Multipage</label>
						          		<a href="javascript:" class="show_iframe_download common_black_button" style="float: right;margin-top: -7px;" download>Download</a> 
						          		<div style="width:100%;background:#b0a8a8;height:1200px;margin-top: 13px;">
						          			<iframe name="attachment_pdf" class="attachment_pdf" src="" style="width:100%;height: 1200px;"></iframe>
						          		</div>
						          	</td>
						          	<td colspan="3"></td>
			                    </tr>';
			                }
			            $downloadfile.='
			            	<tr class="p_total_tr_'.$file->id.'" data-element="'.$file->id.'">
			            		<td colspan="5" style="text-align:right"><strong>P Totals</strong></td>
			            		<td></td>
			            		<td></td>
			            		<td></td>
			            		<td class="p_percent_one_total"></td>
			            		<td class="p_percent_two_total"></td>
			            		<td class="p_percent_three_total"></td>
			            		<td class="p_percent_five_total"></td>
			            		<td class="p_percent_four_total"></td>
			            		<td class="p_net_total"></td>
			            		<td class="p_vat_total"></td>
			            		<td class="p_gross_total"></td>
			            		<td class="p_currency_total"></td>
			            		<td class="p_value_total"></td>
			            		<td></td>
			            	</tr>
			            	<tr class="s_total_tr_'.$file->id.'" data-element="'.$file->id.'">
			            		<td colspan="5" style="text-align:right"><strong>S Totals</strong></td>
			            		<td></td>
			            		<td></td>
			            		<td></td>
			            		<td class="s_percent_one_total"></td>
			            		<td class="s_percent_two_total"></td>
			            		<td class="s_percent_three_total"></td>
			            		<td class="s_percent_five_total"></td>
			            		<td class="s_percent_four_total"></td>
			            		<td class="s_net_total"></td>
			            		<td class="s_vat_total"></td>
			            		<td class="s_gross_total"></td>
			            		<td class="s_currency_total"></td>
			            		<td class="s_value_total"></td>
			            		<td></td>
			            	</tr>
			            </tbody></table>
	              	</div>
	              	<div style="float: left;margin-left: 17px;margin-bottom: 10px;">
            			<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
						<i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>
						<i class="fa fa-file-text-o summary_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Summary"></i>
						<i class="fa fa-calculator calculate_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Calculate"></i>
					</div>';
	    echo $downloadfile;
		//echo json_encode(array('id' => $id, 'table_content' => $downloadfile));
	}
	public function infile_incomplete_status(Request $request)
	{
		$status = $request->get('status');
		$data['infile_incomplete'] = $status;
		\App\Models\userLogin::where('id',1)->update($data);
	}
	public function get_supplier_names_from_infile(Request $request)
	{
		$fileid = $request->get('fileid');
		$file = \App\Models\inFile::where('id',$fileid)->first();
		echo $file->supplier;
	}
	public function get_supplier_names_from_infile_client_id(Request $request)
	{
		$client_id = $request->get('client_id');
		$suppliers = \App\Models\InFileSupplier::where('client_id',$client_id)->orderBy('supplier','asc')->get();
		$output = '<table class="table">
		<thead>
			<th>Supplier/Customer</th>
			<th style="width: 135px;">Code</th>
			<th style="text-align:center;">Action</th>
		</thead>
		<tbody id="supplier_client_tbody">';
		if(($suppliers))
		{
			foreach($suppliers as $supplier){
				$output.='<tr class="supplier_client_tr">
					<td class="supplier_client_td"><input type="text" name="supplier_client" class="supplier_client" value="'.$supplier->supplier.'"></td>
					<td class="supplier_client_td"><input type="text" name="code_client" class="code_client" value="'.$supplier->code.'"></td>
					<td class="supplier_client_td" style="text-align:center;vertical-align:middle;border-bottom: 1px solid #e8e4e4;"><a href="javascript:" class="delete_supplier_client fa fa-trash"></a><a href="javascript:" class="add_supplier_client fa fa-plus" style="margin-left:12px"></a></td>
				</tr>';
			}
		}
		else{
			$output.='<tr class="supplier_client_tr">
					<td class="supplier_client_td"><input type="text" name="supplier_client" class="supplier_client" value=""></td>
					<td class="supplier_client_td"><input type="text" name="code_client" class="code_client" value=""></td>
					<td class="supplier_client_td" style="text-align:center;vertical-align:middle;border-bottom: 1px solid #e8e4e4;"><a href="javascript:" class="delete_supplier_client fa fa-trash"></a><a href="javascript:" class="add_supplier_client fa fa-plus" style="margin-left:12px"></a></td>
				</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function build_supplier_names_for_client_id(Request $request)
	{
		$type = $request->get('type');
		$client_id = $request->get('client_id');
		$get_client_suppliers = \App\Models\InFileSupplier::where('client_id',$client_id)->get();
		if(count($get_client_suppliers) > 0){
			if($type == "overwrite"){
				$supplier_text_val = '';
				$arr_supp = array();
				foreach($get_client_suppliers as $supplier){
					$supp = $supplier->supplier;
					if(trim($supp) != "")
					{
						if(!in_array(strtolower(trim($supp)), $arr_supp))
						{
							$code = trim($supplier->code);
							if($supplier_text_val == "")
							{
								if($code != ""){
									$supplier_text_val = trim($supp).' -- '.$code;
								}
								else{
									$supplier_text_val = trim($supp);
								}
							}
							else{
								if($code != ""){
									$supplier_text_val = $supplier_text_val.','.trim($supp).' -- '.$code;
								}
								else{
									$supplier_text_val = $supplier_text_val.','.trim($supp);
								}
							}
							array_push($arr_supp,strtolower(trim($supp)));
						}
					}
				}
				$data['supplier'] = $supplier_text_val;
				\App\Models\inFile::where('client_id',$client_id)->update($data);
				echo $supplier_text_val;
			}
			else{
				$infile_suppliers = \App\Models\inFile::where('client_id',$client_id)->first();
				$supplier_text_val = $infile_suppliers->supplier;
				$arr_supp = array();
				$explode_infile_supplier = explode(',',$supplier_text_val);
				if(count($explode_infile_supplier) > 0){
					foreach($explode_infile_supplier as $explode){
						$explodehyphen = explode('--',$explode);
						if(isset($explodehyphen[0])){
							array_push($arr_supp,strtolower(trim($explodehyphen[0])));
						}
					}
				}
				foreach($get_client_suppliers as $supplier){
					$supp = $supplier->supplier;
					if(trim($supp) != "")
					{
						if(!in_array(strtolower(trim($supp)), $arr_supp))
						{
							$code = trim($supplier->code);
							if($supplier_text_val == "")
							{
								if($code != ""){
									$supplier_text_val = trim($supp).' -- '.$code;
								}
								else{
									$supplier_text_val = trim($supp);
								}
							}
							else{
								if($code != ""){
									$supplier_text_val = $supplier_text_val.','.trim($supp).' -- '.$code;
								}
								else{
									$supplier_text_val = $supplier_text_val.','.trim($supp);
								}
							}
							array_push($arr_supp,strtolower(trim($supp)));
						}
					}
				}
				$data['supplier'] = $supplier_text_val;
				\App\Models\inFile::where('client_id',$client_id)->update($data);
				echo $supplier_text_val;
			}
		}
		else{
			echo 'error';
		}
	}
	public function build_supplier_names_client_for_client_id(Request $request)
	{
		$client_id = $request->get('client_id');
		// $suppliers = \App\Models\inFile::where('client_id',$client_id)->where('supplier','!=',"")->get();
		$suppliers_ids = \App\Models\inFile::where('client_id',$client_id)->where('supplier','!=',"")->pluck('id')->toArray();
		$suppliers = \App\Models\inFileAttachment::whereIn('file_id',$suppliers_ids)->where('supplier','!=','')->pluck('supplier')->toArray();
		\App\Models\InFileSupplier::where('client_id',$client_id)->delete();
		$output = '';
		$arr_supp = array();
		if(($suppliers))
		{
			foreach($suppliers as $supplier)
			{
				$supp = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $supplier);
				if(trim($supp) != "")
				{
					if(!in_array(strtolower(trim($supp)), $arr_supp))
					{
						$get_code = \App\Models\inFileAttachment::whereIn('file_id',$suppliers_ids)->where('supplier',trim($supp))->orderBy('id','desc')->first();
						$code = '';
						if($get_code){
							if($get_code->code != ""){
								$code = $get_code->code;
							}
						}
						$output.='<tr class="supplier_client_tr">
							<td class="supplier_client_td"><input type="text" name="supplier_client" class="supplier_client" value="'.trim($supp).'"></td>
							<td class="supplier_client_td"><input type="text" name="code_client" class="code_client" value="'.$code.'"></td>
							<td class="supplier_client_td" style="text-align:center;vertical-align:middle;border-bottom: 1px solid #e8e4e4;"><a href="javascript:" class="delete_supplier_client fa fa-trash"></a><a href="javascript:" class="add_supplier_client fa fa-plus" style="margin-left:12px"></a></td>
						</tr>';
						array_push($arr_supp,strtolower(trim($supp)));
						$data['client_id'] = $client_id;
						$data['supplier'] = trim($supp);
						$data['code'] = $code;
						\App\Models\InFileSupplier::insert($data);
					}
				}
			}
		}
		echo $output;
	}
	public function set_supplier_names_from_infile(Request $request)
	{
		$client_id = $request->get('client_id');
		$fileid = $request->get('fileid');
		$supplier = explode(",",$request->get('supplier'));
		$supplier_text = '';
		if(($supplier))
		{
			foreach($supplier as $supp)
			{
				if(trim($supp) == "")
				{
				}
				else{
					if($supplier_text == "")
					{
						$supplier_text = trim($supp);
					}
					else{
						$supplier_text = $supplier_text.','.trim($supp);
					}
				}
			}
		}
		$data['supplier'] = $supplier_text;
		\App\Models\inFile::where('client_id',$client_id)->update($data);
		echo $supplier_text;
	}
	public function change_percent_value(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == "one")
		{
			$data['percent_one'] = number_format_invoice_without_comma($value);
		}
		if($type == "two")
		{
			$data['percent_two'] = number_format_invoice_without_comma($value);
		}
		if($type == "three")
		{
			$data['percent_three'] = number_format_invoice_without_comma($value);
		}
		if($type == "four")
		{
			$data['percent_four'] = number_format_invoice_without_comma($value);
		}
		if($type == "five")
		{
			$data['percent_five'] = number_format_invoice_without_comma($value);
		}
		\App\Models\inFile::where('id',$fileid)->update($data);
		echo number_format_invoice($value);
	}
	public function infile_supplier_search(Request $request)
	{
		$value = $request->get('term');
		$fileid = $request->get('fileid');
		$details = \App\Models\inFile::where('id',$fileid)->first();
		$supplier_array = explode(",",$details->supplier);
		$data=array();
		if(($supplier_array))
		{
			foreach($supplier_array as $supplier)
			{
				$supplierlower = strtolower($supplier);
				$valuelower = strtolower($value);
				if (strpos($supplierlower, $valuelower) !== false) {
					$explodehyphen = explode('--',$supplier);
					$data[]=array('value'=>trim($explodehyphen[0]),'id'=>$fileid,'fullname'=>$supplier);
				}
			}
		}
        if(($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function infile_supplier_search_select(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = explode('--',$request->get('value'));
		$supplier = '';
		$code = '';
		if(count($value) == 2){
			$attachmentid = $request->get('attachment_id');
			$data['supplier'] = $value[0];
			$data['code'] = $value[1];
			$supplier = $value[0];
			$code = $value[1];
			\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		}
		else{
			$attachmentid = $request->get('attachment_id');
			$data['supplier'] = $value[0];
			$supplier = $value[0];
			$code = '';
			\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		}
		echo json_encode(array("supplier" => $supplier, 'code' => $code));
	}
	public function update_supplier_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$attachmentid = $request->get('attachmentid');
		$type = $request->get('type');
		if($type == "1")
		{
			$get_suppliers = \App\Models\inFile::where('id',$fileid)->first();
			if(($get_suppliers))
			{
				$suppliers = explode(",",strtolower($get_suppliers->supplier));
				$arr_suppliers = [];
				if(($suppliers))
				{
					foreach($suppliers as $supp){
						$explodehyphen = explode('--',trim($supp));
						array_push($arr_suppliers,strtolower(trim($explodehyphen[0])));
					}
				}
				if (!in_array(strtolower(trim($value)), $arr_suppliers))
				{
					if(trim($get_suppliers->supplier) == "")
					{
						$dataval['supplier'] = trim($value);
					}
					else{
						$dataval['supplier'] = $get_suppliers->supplier.','.trim($value);
					}
					\App\Models\inFile::where('id',$fileid)->update($dataval);
				}
			}
		}
		$data['supplier'] = trim($value);
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
	}
	public function update_percent_one_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = $request->get('attachmentid');
		if($value != "")
		{
			$data['percent_one'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_one'] = '';
		}
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		$infile_attachment = \App\Models\inFileAttachment::where('id',$attachmentid)->first();
		if(($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma((float)$one + (float)$two + (float)$three + (float)$four + (float)$five);
				$file = \App\Models\inFile::where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ((float)$one * (float)$percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ((float)$two * (float)$percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ((float)$three * (float)$percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ((float)$four * (float)$percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ((float)$five * (float)$percent_five) / 100; } else { $five_vat = '0.00'; }
					$vatvalue = number_format_invoice_without_comma((float)$one_vat + (float)$two_vat + (float)$three_vat + (float)$four_vat + (float)$five_vat);
					$grossvalue = number_format_invoice_without_comma((float)$netvalue + (float)$vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;
				\App\Models\inFileAttachment::where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_two_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = $request->get('attachmentid');
		if($value != "")
		{
			$data['percent_two'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_two'] = '';
		}
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		$infile_attachment = \App\Models\inFileAttachment::where('id',$attachmentid)->first();
		if(($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
				$netvalue = number_format_invoice_without_comma((float)$one + (float)$two + (float)$three + (float)$four + (float)$five);
				$file = \App\Models\inFile::where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ((float)$one * (float)$percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ((float)$two * (float)$percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ((float)$three * (float)$percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ((float)$four * (float)$percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ((float)$five * (float)$percent_five) / 100; } else { $five_vat = '0.00'; }
					$vatvalue = number_format_invoice_without_comma((float)$one_vat + (float)$two_vat + (float)$three_vat + (float)$four_vat + (float)$five_vat);
					$grossvalue = number_format_invoice_without_comma((float)$netvalue + (float)$vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;
				\App\Models\inFileAttachment::where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice((float)$value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_three_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = $request->get('attachmentid');
		if($value != "")
		{
			$data['percent_three'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_three'] = '';
		}
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		$infile_attachment = \App\Models\inFileAttachment::where('id',$attachmentid)->first();
		if(($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma((float)$one + (float)$two + (float)$three + (float)$four + (float)$five);
				$file = \App\Models\inFile::where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ((float)$one * (float)$percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ((float)$two * (float)$percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ((float)$three * (float)$percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ((float)$four * (float)$percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ((float)$five * (float)$percent_five) / 100; } else { $five_vat = '0.00'; }
					$vatvalue = number_format_invoice_without_comma((float)$one_vat + (float)$two_vat + (float)$three_vat + (float)$four_vat + (float)$five_vat);
					$grossvalue = number_format_invoice_without_comma((float)$netvalue + (float)$vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;
				\App\Models\inFileAttachment::where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_four_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = $request->get('attachmentid');
		if($value != "")
		{
			$data['percent_four'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_four'] = '';
		}
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		$infile_attachment = \App\Models\inFileAttachment::where('id',$attachmentid)->first();
		if(($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma((float)$one + (float)$two + (float)$three + (float)$four + (float)$five);
				$file = \App\Models\inFile::where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ((float)$one * (float)$percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ((float)$two * (float)$percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ((float)$three * (float)$percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ((float)$four * (float)$percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ((float)$five * (float)$percent_five) / 100; } else { $five_vat = '0.00'; }
					$vatvalue = number_format_invoice_without_comma((float)$one_vat + (float)$two_vat + (float)$three_vat + (float)$four_vat + (float)$five_vat);
					$grossvalue = number_format_invoice_without_comma((float)$netvalue + (float)$vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;
				\App\Models\inFileAttachment::where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_five_infile_attachment(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = $request->get('attachmentid');
		if($value != "")
		{
			$data['percent_five'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_five'] = '';
		}
		\App\Models\inFileAttachment::where('id',$attachmentid)->update($data);
		$infile_attachment = \App\Models\inFileAttachment::where('id',$attachmentid)->first();
		if(($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma((float)$one + (float)$two + (float)$three + (float)$four + (float)$five);
				$file = \App\Models\inFile::where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ((float)$one * (float)$percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ((float)$two * (float)$percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ((float)$three * (float)$percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ((float)$four * (float)$percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ((float)$five * (float)$percent_five) / 100; } else { $five_vat = '0.00'; }
					$vatvalue = number_format_invoice_without_comma((float)$one_vat + (float)$two_vat + (float)$three_vat + (float)$four_vat + (float)$five_vat);
					$grossvalue = number_format_invoice_without_comma((float)$netvalue + (float)$vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;
				\App\Models\inFileAttachment::where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function infile_attachment_date_filled(Request $request)
	{
		$id = $request->get('id');	
		$dd= $request->get('dateval');
		$data['date_attachment'] = $dd;
		\App\Models\inFileAttachment::where('id', $id)->update($data);
	}
	public function save_imported_status(Request $request)
	{
		$file_id = $request->get('file_id');	
		$status= $request->get('status');
		$data['imported_status'] = $status;
		$check_db = \App\Models\inFile::where('id', $file_id)->first();
		if(($check_db))
		{
			if($check_db->imported_date == ""){
				$data['imported_date'] = date('d-M-Y');
			}
		}
		\App\Models\inFile::where('id', $file_id)->update($data);
	}
	public function save_imported_date(Request $request)
	{
		$file_id = $request->get('file_id');	
		$date= $request->get('date');
		$data['imported_date'] = $date;
		\App\Models\inFile::where('id', $file_id)->update($data);
	}
	public function infile_attachment_code_filled(Request $request)
	{
		$id = $request->get('id');	
		$dd= $request->get('code');
		$data['code'] = $dd;
		\App\Models\inFileAttachment::where('id', $id)->update($data);
	}
	public function infile_attachment_currency_filled(Request $request)
	{
		$id = $request->get('id');	
		$dd= $request->get('currency');
		$data['currency'] = $dd;
		\App\Models\inFileAttachment::where('id', $id)->update($data);
	}
	public function infile_attachment_value_filled(Request $request)
	{
		$id = $request->get('id');	
		$dd= $request->get('value');
		$data['value'] = $dd;
		\App\Models\inFileAttachment::where('id', $id)->update($data);
	}
	public function infile_download_bpso_all_image_csv(Request $request)
	{
		$type = $request->get('type');
		$idd = $request->get('id');
		$page = $request->get('page');
		$filename = $request->get('filename');

		if($filename == ''){
			$filenameval = 'Infile_'.$type.'_attachments_'.time().'.csv';
			$details = \App\Models\inFile::where('id',$idd)->first();
			$columns_1 = array('Attachment Text', 'P/S Date', 'Supplier/Customer','Code', $details->percent_one.'%', $details->percent_two.'%', $details->percent_three.'%', $details->percent_five.'%', $details->percent_four.'%', 'Net', 'Vat', 'Gross', 'Currency', 'Value', 'Filename');
			$fileopen = fopen('public/'.$filenameval.'', 'w');
	    	fputcsv($fileopen, $columns_1);
		} else {
			$filenameval = $filename;
			$fileopen = fopen('public/'.$filenameval.'', 'a');
		}

		$pageoffset = $page - 1;
		$offset = $pageoffset * 500;

		$files = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->select('id','textval','date_attachment','supplier','code','percent_one','percent_two','percent_three','percent_five','percent_four','net','vat','gross','currency','value','attachment')->offset($offset)->limit(500)->get();

		$files = json_decode(json_encode($files), true);

		if(count($files))
		{
			foreach($files as $file)
			{
				$id = $file['id'];
				unset($file['id']);

				$date = \DateTime::createFromFormat('d/m/Y', $file['date_attachment']);
				if ($date instanceof \DateTime) {
		            $formattedDate = $date->format('d/m/Y');
		            $file['date_attachment'] = $formattedDate;
		        }
				fputcsv($fileopen, $file);
				$get_subfiles = \App\Models\inFileAttachment::where('attach_id',$id)->where('secondary',1)->select('textval','date_attachment','supplier','code','percent_one','percent_two','percent_three','percent_five','percent_four','net','vat','gross','currency','value')->orderBy('id','asc')->get();
				$get_subfiles = json_decode(json_encode($get_subfiles), true);
				if(count($get_subfiles))
				{
					foreach($get_subfiles as $subfile)
					{
						$subfileDate = \DateTime::createFromFormat('d/m/Y', $subfile['date_attachment']);
		                if ($subfileDate instanceof \DateTime) {
	                        $formattedSubfileDate = $subfileDate->format('d/m/Y');
	                        $subfile['date_attachment'] = $formattedSubfileDate;
	                    }
							
						$subfile['attachment'] = $file['attachment'];
						fputcsv($fileopen, $subfile);
					}
				}
			}
			fclose($fileopen);
		}

		$total_count = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->count();

		echo json_encode(array('total_count' => $total_count, 'filename' => $filenameval));
	}
	public function infile_download_bpso_all_image_both(Request $request)
	{
		$type = $request->get('type');
		$idd = $request->get('id');
		$page = $request->get('page');
		$filename_zip = $request->get('filename_zip');
		$filename_csv = $request->get('filename_csv');

		$details = \App\Models\inFile::where('id',$idd)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$public_dir=public_path();

		if($filename_zip == ''){
			$zipFileName = $client_details->company.'_'.date('d M Y',strtotime($details->date_added)).'_'.time().'.zip';
			$filenameval = 'Infile_'.$type.'_attachments_'.time().'.csv';
		} else {
			$zipFileName = $filename_zip;
			$filenameval = $filename_csv;
		}

		$pageoffset = $page - 1;
		$offset = $pageoffset * 500;

		$files = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->select('id','textval','date_attachment','supplier','code','percent_one','percent_two','percent_three','percent_five','percent_four','net','vat','gross','currency','value','attachment','url')->offset($offset)->limit(500)->get();
		$zip = new ZipArchive;
	    if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
			if(($files))
			{
				if($filename_zip == ''){
					$columns_1 = array('Attachment Text', 'P/S Date', 'Supplier/Customer','Code', $details->percent_one.'%', $details->percent_two.'%', $details->percent_three.'%', $details->percent_five.'%', $details->percent_four.'%', 'Net', 'Vat', 'Gross', 'Currency', 'Value', 'Filename');
					$fileopen = fopen('public/'.$filenameval.'', 'w');
					fputcsv($fileopen, $columns_1);
				}
				else{
					$fileopen = fopen('public/'.$filenameval.'', 'a');
				}

				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}
		            $zip->addFile($file->url.'/'.$file->attachment,$filename);

					$date = \DateTime::createFromFormat('d/m/Y', $file->date_attachment);
					if ($date instanceof \DateTime) {
			            $formattedDate = $date->format('d/m/Y');
			            $file->date_attachment = $formattedDate;
			        }

					$columns_2 = array($file->textval, $file->date_attachment, $file->supplier, $file->code, $file->percent_one, $file->percent_two, $file->percent_three, $file->percent_five, $file->percent_four, $file->net, $file->vat, $file->gross, $file->currency, $file->value, $file->attachment);
					fputcsv($fileopen, $columns_2);

					$get_subfiles = \App\Models\inFileAttachment::where('attach_id',$file->id)->where('secondary',1)->orderBy('id','asc')->get();
					if(($get_subfiles))
					{
						foreach($get_subfiles as $subfile)
						{
							$subfileDate = \DateTime::createFromFormat('d/m/Y', $subfile->date_attachment);
			                if ($subfileDate instanceof \DateTime) {
		                        $formattedSubfileDate = $subfileDate->format('d/m/Y');
		                        $subfile->date_attachment = $formattedSubfileDate;
		                    }
		                    
							$columns_3 = array($subfile->textval, $subfile->date_attachment, $subfile->supplier, $subfile->code, $subfile->percent_one, $subfile->percent_two, $subfile->percent_three, $subfile->percent_five, $subfile->percent_four, $subfile->net, $subfile->vat, $subfile->gross, $subfile->currency, $subfile->value, $file->attachment);
							fputcsv($fileopen, $columns_3);
						}
					}
				}
				fclose($fileopen);

				$zip->addFile('public/'.$filenameval,$filenameval);
			}
			$zip->close();
		}

		$total_count = \App\Models\inFileAttachment::where('file_id',$idd)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->count();

		echo json_encode(array('total_count' => $total_count, 'filename_zip' => $zipFileName, 'filename_csv' => $filenameval));
	}
	public function change_show_hide_ps_status(Request $request)
	{
		$status = $request->get('status');
		$fileid = $request->get('fileid');
		$data['show_previous'] = $status;
		\App\Models\inFile::where('id',$fileid)->update($data);
	}
	public function file_not_supported(Request $request)
	{
		return view('welcome');
	}
	public function check_pdf_pages(Request $request)
	{
 		$path = $request->get('src');
 		$pdf = file_get_contents($path); 
		$number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
		echo $number;
	}
	public function change_flag_status(Request $request)
	{
		$data['flag'] = $request->get('flag_status');
		$fileid = $request->get('fileid');
		\App\Models\inFileAttachment::where('id',$fileid)->update($data);
	}
	public function add_new_secondary_line(Request $request)
	{
		$attach_id = $request->get('attach_id');
		$get_last_attachment = \App\Models\inFileAttachment::where('attach_id',$attach_id)->orderBy('id','desc')->first();
		$getattachment = \App\Models\inFileAttachment::where('id',$attach_id)->first();
		if(($get_last_attachment))
		{
			$get_textval = $get_last_attachment->textval;
			if($get_textval == "")
			{
				$add_textval = "";
			}
			else{
				if(is_numeric($get_textval))
				{
					$add_textval = (float)$get_textval + .001;
				}
				else{
					$add_textval = '';
				}
			}
		}
		else{
			$get_textval = $getattachment->textval;
			if($get_textval == "")
			{
				$add_textval = "";
			}
			else{
				if(is_numeric($get_textval))
				{
					$add_textval = (float)$get_textval + .001;
				}
				else{
					$add_textval = '';
				}
			}
		}
		$data['attach_id'] = $attach_id;
		$data['file_id'] = $getattachment->file_id;
		$data['secondary'] = 1;
		$data['check_file'] = 0;
		$data['attachment'] = "";
		$data['url'] = "";
		$data['textval'] = $add_textval;
		$data['b'] = $getattachment->b;
		$data['p'] = $getattachment->p;
		$data['s'] = $getattachment->s;
		$data['o'] = $getattachment->o;
		$data['supplier'] = "";
		$data['date_attachment'] = "";
		$data['code'] = "";
		$data['percent_one'] = "";
		$data['percent_two'] = "";
		$data['percent_three'] = "";
		$data['percent_four'] = "";
		$data['percent_five'] = "";
		$data['net'] = "";
		$data['vat'] = "";
		$data['gross'] = "";
		$data['flag'] = 0;
		$data['textstatus'] = 1;
		$data['status'] = 0;
		$data['notes_type'] = 0;
		$new_id = \App\Models\inFileAttachment::insertDetails($data);
		if($getattachment->p == 1) { $attach_disabled = ''; }
		elseif($getattachment->s == 1) { $attach_disabled = ''; }
		else { $attach_disabled = 'disabled'; }
		$flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>';
		$output = '<tr class="sub_attachment attachment_tr attachment_tr_'.$getattachment->id.'" data-element="'.$getattachment->file_id.'" data-value="'.$new_id.'">
			<td colspan="5">
            	<input type="text" name="add_text" class="add_text" data-element="'.$new_id.'" value="'.$add_textval.'" placeholder="Add Text" style="margin-left:7.5%;">
            	'.$flag_sub.'
			</td>
			<td class="td_input">
				<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input" style="border-left:1px solid #b5b3b3">
				<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input">
				<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input" style="border-right:1px solid #b5b3b3">
				<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input">
				<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
			</td>
		</tr>';
		echo $output;
	}
	public function check_integrity_files(Request $request)
	{
		$fileid = $request->get('fileid');
		$infile_details = \App\Models\inFile::where('id',$fileid)->first();
		$company = '';
		if(($infile_details))
		{
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$infile_details->client_id)->first();
			$company = $client_details->company;
		}
		$infile_attacments = \App\Models\inFileAttachment::where('file_id',$fileid)->where('secondary',0)->where('notes_type',0)->get();
		$output = '';
		if(($infile_attacments))
		{
			foreach($infile_attacments as $key => $attachment)
			{
				$key = (int)$key + 1;
				$output.='<tr>
					<td>'.$company.'</td>
					<td>'.$infile_details->client_id.'</td>
					<td class="integrity_attachment overflow-wrap-hack" data-element="'.$attachment->id.'" data-file="'.$attachment->file_id.'" data-key="'.$key.'"><div class="content_check">'.$attachment->attachment.'</div></td>
					<td class="integrity_status integrity_status_'.$attachment->id.'"> - </td>
					<td class="action_status action_status_'.$attachment->id.'"> - </td>
					<td>'.$infile_details->description.'</td>
					<td>'.date("d-M-Y", strtotime($infile_details->date_added)).'</td>
				</tr>';
			}
		}
		$dir = "public/papers/infile_report/".$fileid;
		$files = '<h5>Download</h5>';
		$url = '';
		// Open a directory, and read its contents
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
		    while (($file = readdir($dh)) !== false){
		    	if($file != '.' && $file != '..'){
		    		$files.='<p><a href="'.URL::to($dir.'/'.$file).'" download="">'.$file.'</a></p>';
		    		$url = URL::to($dir.'/'.$file);
		    	}
		    }
		    closedir($dh);
		  }
		}
		echo json_encode(array("output" => $output,"files" => $files,'url' => $url));
	}
	public function check_integrity_files_client_id(Request $request)
	{
		$client_id = $request->get('client_id');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$company = $client_details->company;
		$infile_details = \App\Models\inFile::where('client_id',$client_id)->get();
		$output = '';
		$type = $request->get('type');
		$filepath = $request->get('filepath');
		$filename = $request->get('filename');
		if($type == "0")
		{
			$columns = array("Client","Client ID", "Filename", "Status", "Description", "Date Added");
			$upload_dir = 'public/papers/infile_report';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.$client_id;
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$filename = "IntegrityCheckReport".date('d-M-Y')."_".time().".csv";
			$file = fopen($upload_dir."/".$filename,"w");
			fputcsv($file, $columns);
			$filename_url = URL::to($upload_dir."/".$filename);
			$ffname = $filename;
			$filepath = $upload_dir."/".$filename;
		}
		else{
			$file = fopen($filepath,"a");
			$filename_url = URL::to($filepath);
			$ffname = $filename;
			$filepath = $filepath;
		}
		if(($infile_details))
		{
			foreach($infile_details as $infile)
			{
				$fileid = $infile->id;
				$infile_attacments = \App\Models\inFileAttachment::where('file_id',$fileid)->where('secondary',0)->where('notes_type',0)->get();
				if(($infile_attacments))
				{
					foreach($infile_attacments as $key => $attachment)
					{
						if(!file_exists($attachment->url.'/'.$attachment->attachment))
						{
							$status = 'Missing';
						}
						else{
							$status = 'Ok';
						}
						$key = (int)$key + 1;
						$output.='<tr>
							<td>'.$company.'</td>
							<td>'.$infile->client_id.'</td>
							<td class="integrity_attachment overflow-wrap-hack" data-element="'.$attachment->id.'" data-file="'.$attachment->file_id.'" data-key="'.$key.'"><div class="content_check">'.$attachment->attachment.'</div></td>
							<td class="integrity_status integrity_status_'.$attachment->id.'">'.$status.'</td>
							<td class="action_status action_status_'.$attachment->id.'"> - </td>
							<td>'.$infile->description.'</td>
							<td>'.date("d-M-Y", strtotime($infile->date_added)).'</td>
						</tr>';
						if($type == "0")
						{
							$columns1 = array($company,$infile->client_id, $attachment->attachment, $status, $infile->description, date("d-M-Y", strtotime($infile->date_added)));
							fputcsv($file, $columns1);
						}
						else{
							$columns1 = array($company,$infile->client_id, $attachment->attachment, $status, $infile->description, date("d-M-Y", strtotime($infile->date_added)));
							fputcsv($file, $columns1);
						}
					}
				}
			}
		}
		else{
			$output.='<tr>
							<td>'.$company.'</td>
							<td>'.$client_id.'</td>
							<td>No Infile Items Found</td>
							<td>-</td>
							<td> - </td>
							<td> - </td>
							<td> - </td>
						</tr>';
			$columns1 = array($company,$client_id, 'No Infile Items Found', '-', '-', '-');
			fputcsv($file, $columns1);
		}
		fclose($file);
		echo json_encode(array("output" => $output, "url" => $filename_url, "filename" => $ffname, 'filepath' => $filepath));
	}
	public function check_files_in_files(Request $request)
	{
		$fileids = explode(',',$request->get('fileids'));
		$type = $request->get('type');
		$round = $request->get('round');
		$filepath = $request->get('filepath');
		$filename = $request->get('filename');

		$check_files = \App\Models\inFileAttachment::whereIn('id',$fileids)->get();
		if(is_countable($check_files) && count($check_files) > 0)
		{
			if($type == "0") {
				$item_details = \App\Models\inFile::where('id',$check_files[0]->file_id)->first();
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$item_details->client_id)->first();
				$columns = array("Client","Client ID", "Filename", "Status", "Description", "Date Added");
				$upload_dir = 'public/papers/infile_report';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.$check_files[0]->file_id;
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$filename = "IntegrityCheckReport".date('d-M-Y')."_".time().".csv";
				$data['url'] = $upload_dir."/".$filename;
				$data['filename'] = $filename;
				$data['fileid'] = $check_files[0]->file_id;
				\App\Models\InfileCheckReport::insert($data);
				$file = fopen($upload_dir."/".$filename,"w");
				fputcsv($file, $columns);
				$filepath = $upload_dir."/".$filename;
			}
			else{
				$item_details = \App\Models\inFile::where('id',$check_files[0]->file_id)->first();
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$item_details->client_id)->first();
				$file = fopen($filepath,"a");
			}
			$statusval = [];
			foreach($check_files as $check_file) {
				$check_datetime = date('Y-m-d H:i:s');
				$datavalll['integrity_check'] = 1;
				$datavalll['check_date_time'] = $check_datetime;
				\App\Models\inFile::where('id',$check_file->file_id)->update($datavalll);
				if(!file_exists($check_file->url.'/'.$check_file->attachment))
				{
					$status = 'Missing';
				}
				else{
					$status = 'Ok';
				}
				$columns1 = array($client_details->company,$item_details->client_id, $check_file->attachment, $status, $item_details->description, date("d-M-Y", strtotime($item_details->date_added)));
				fputcsv($file, $columns1); //@Optimist
				
				$filename_url = URL::to($filepath);
				$ffname = $filename;
				$filepath = $filepath;

				if(!file_exists($check_file->url.'/'.$check_file->attachment)) {
					$statusval[$check_file->id] = '<spam class="files_spam missing_spam" style="color:#f00;font-weight:600"><spam class="hide_attach" style="display:none">'.strtolower($check_file->attachment).'</spam>Missing</spam><input type="hidden" name="hidden_file_missing" id="hidden_file_missing" class="hidden_file_missing" value="">';
				}
				else{
					$statusval[$check_file->id] = '<spam class="files_spam ok_spam" style="color:green;font-weight:600">Ok</spam>';
				}
			}
			fclose($file);
			
			echo json_encode(array("status" => $statusval, "url" => $filename_url, "filename" => $ffname, 'filepath' => $filepath, 'fileitem_id' => $check_files[0]->file_id));
		}
	}
	public function show_attachments_infile(Request $request)
	{
		$fileid = $request->get('fileid');
		$file = \App\Models\inFile::where('id',$fileid)->first();
		$page = $request->get('page');
		if($page == "0"){
			$attachments = \App\Models\inFileAttachment::where('file_id',$fileid)->where('status',0)->where('notes_type', 0)->where('secondary',0)->orderBy('id','asc')->get();
		}
		else{
			$prevpage = $page - 1;
			$offset = $prevpage * 500;
			$attachments = \App\Models\inFileAttachment::where('file_id',$fileid)->where('status',0)->where('notes_type', 0)->where('secondary',0)->offset($offset)->limit(500)->orderBy('id','asc')->get();
		}
		$total_attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$fileid)->where('status',0)->where('notes_type', 0)->where('secondary',0)->get();
		if($file->status == 0){
	        $staus = 'fa-check edit_status'; 
	        $statustooltip = 'Complete Infile';
	        $disable = '';
	        $disable_class = '';
	        $color='';
	        $color_last='style="border-top:0px solid;border-bottom:1px solid #6a6a6a"';
	    }
	    else{
	        $staus = 'fa-times edit_status incomplete_status';
	        $statustooltip = 'InComplete Infile';
	        $disable = 'disabled';
	        $disable_class = 'disable_class';
	        $color = 'style="color:#f00;"';
	        $color_last = 'style="color:#f00;border-top:0px solid;border-bottom:1px solid #6a6a6a"';
	    }
        $ips_data = 0;
        $downloadfile='';
        $downloadfile_output = '';
        if(($attachments)){  
          $downloadfile.='
          <style>
            .bpso_all_check{font-size:20px; font-weight:700; margin-left:10px;}
            .bpso_all_check:hover{text-decoration:none}
           	.table_bspo .td_input { padding:3px !important; }
          </style>
          <div class="row infile_inner_table_row">
          	<div class="col-md-12">
          		<table class="table_bspo table-fixed-header" id="bspo_id_'.$file->id.'" style="width:100%;">
          			<thead>
		                <tr>
		                  <th style="width:18%;text-align:left">
		                  	<a data-toggle="popover" data-container="body" data-placement="right" type="button" data-html="true" href="javascript:" id="login_'.$file->id.'" class="auto_increment" data-element="'.$file->id.'" style="margin-left: 18%;">AutoQue</a>
		                  	<div id="popover-content-login_'.$file->id.'" class="hide">
								<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="purchase_item_auto_'.$file->id.'" value="1"><label for="purchase_item_auto">Purchase Item</label><br/>
								<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="sales_item_auto_'.$file->id.'" value="2"><label for="sales_item_auto">Sales Item</label>
								<div class="form-data">
									<h4>Enter Number:</h4>
									<input type="number" class="form-control auto_number_value_'.$file->id.'" id="auto_number_value" value="">
									<h4>Enter Increment Value:</h4>
									<input type="number" class="form-control inc_number_value_'.$file->id.'" id="inc_number_value" value="">
									<input type="button" name="submit_auto_value" class="submit_auto_value common_black_button" data-element="'.$file->id.'" value="Number Now" style="margin-top: 10px;">
									<input type="button" name="submit_re_number" class="submit_re_number common_black_button" data-element="'.$file->id.'" value="RE-Number">
								</div>
							</div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in B Category" id="'.$file->id.'" data-element="1">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_b_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in B Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in P Category" id="'.$file->id.'" data-element="2">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_p_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in P Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in S Category" id="'.$file->id.'" data-element="3">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_s_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in S Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in O Category" id="'.$file->id.'" data-element="4">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_o_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in O Category"></i>
		                    </div>
		                  </th>
		                  <th class="td_input td_supplier" style="width:10%;font-weight:600;text-align:center" data-element="'.$file->id.'">Supplier/Customer</th>
		                  <th class="td_input td_code" style="font-weight:600;text-align:center;width:5%;">Code</th>
		                  <th class="td_input td_date" style="font-weight:600;text-align:center;width:7%;">Date</th>
		                  <th class="td_input td_percent_one" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_one_text">'.$file->percent_one.'</spam>
		                  	<div class="percent_one_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_one" class="change_percent_one form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_one" class="common_black_button submit_percent_one" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_two" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_two_text">'.$file->percent_two.'</spam>
		                  	<div class="percent_two_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_two" class="change_percent_two form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_two" class="common_black_button submit_percent_two" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_three" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_three_text">'.$file->percent_three.'</spam>
		                  	<div class="percent_three_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_three" class="change_percent_three form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_three" class="common_black_button submit_percent_three" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_five" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_five_text">'.$file->percent_five.'</spam>
		                  	<div class="percent_five_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_five" class="change_percent_five form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_five" class="common_black_button submit_percent_five" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_four" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_four_text">'.$file->percent_four.'</spam>
		                  	<div class="percent_four_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_four" class="change_percent_four form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_four" class="common_black_button submit_percent_four" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input" style="font-weight:600;text-align:center;border-left:1px solid #b5b3b3;width:5%;">Net</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:5%;">VAT</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;border-right:1px solid #b5b3b3;width:5%;">Gross</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Currency</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Value</th>
		                  <th class="td_input" style="width:20px;font-weight:600;text-align:center;width:2%;"></th>
		                </tr>
	                </thead>
	                <tbody id="bpso_infile_attachments_tbody_'.$file->id.'">';     
              		$ips_data = 0;
	                foreach($attachments as $attachment){
                		$get_sub_attachments = \App\Models\inFileAttachment::where('attach_id',$attachment->id)->where('secondary',1)->orderBy('id','asc')->get();
	                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
						if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
						if($attachment->b == 1) {  $bchecked = 'checked'; } else { $bchecked = ''; }
						if($attachment->p == 1) {  $pchecked = 'checked'; } else { $pchecked = ''; }
						if($attachment->s == 1) {  $schecked = 'checked'; } else { $schecked = ''; }
						if($attachment->o == 1) {  $ochecked = 'checked'; } else { $ochecked = ''; }
						if($attachment->p == 1) { $attach_disabled = ''; }
						elseif($attachment->s == 1) { $attach_disabled = ''; }
						else { $attach_disabled = 'disabled'; }
        				if($attachment->supplier != "" || $attachment->date_attachment != "" || $attachment->percent_one != "" || $attachment->percent_two != "" || $attachment->percent_three != "" || $attachment->percent_four != "" || $attachment->percent_five != "")
		                {
		                  $ips_data++;
		                }
		                if($attachment->flag == 0) { $flag = '<i class="fa fa-flag flag_gray fileattachment"></i>'; }
		                elseif($attachment->flag == 1) { $flag = '<i class="fa fa-flag flag_orange fileattachment"></i>'; }
		                elseif($attachment->flag == 2) { $flag = '<i class="fa fa-flag flag_red fileattachment"></i>'; }
						$downloadfile_output.= '<tr class="attachment_tr attachment_tr_main" data-element="'.$file->id.'">
							<td style="min-width:300px;max-width:300px;">
								<div class="file_attachment_div" style="width:100%">
								  	<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'>
								  	<label for="fileattach_'.$attachment->id.'">&nbsp;</label>
								  	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                					'.$flag.'
                					<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
									<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" '.$color.' data-toggle="tooltip" title="'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.substr($attachment->attachment,0,15).'</a>
								</div>
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="b_check" id="b_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$bchecked.' title="Bank"><label for="b_check_'.$attachment->id.'" title="Bank">B</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="p_check" id="p_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$pchecked.' title="Purchases"><label for="p_check_'.$attachment->id.'" title="Purchases">P</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="s_check" id="s_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$schecked.' title="Sales"><label for="s_check_'.$attachment->id.'" title="Sales">S</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="o_check" id="o_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$ochecked.' title="Other Sundry"><label for="o_check_'.$attachment->id.'" title="Other Sundry">O</label> 
							</td>';
							$downloadfile_output.='<td class="td_input">
								<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input" style="border-left:1px solid #b5b3b3">
								<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input">
								<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input" style="border-right:1px solid #b5b3b3">
								<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input">
								<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>';
							$downloadfile_output.='<td class="td_input">
								<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
								<a href="javascript:" class="fa fa-plus-circle add_secondary '.$disable_class.'" data-element="'.$attachment->id.'" title="Add Seconday Line"></a>
								<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
							</td>
						</tr>';
		                if(($get_sub_attachments))
		                {
		                  foreach($get_sub_attachments as $sub)
		                  {
		                    if($sub->p == 1) { $attach_sub_disabled = ''; }
		                    elseif($sub->s == 1) { $attach_sub_disabled = ''; }
		                    else { $attach_sub_disabled = 'disabled'; }
		                    if($sub->supplier != "" || $sub->date_attachment != "" || $sub->percent_one != "" || $sub->percent_two != "" || $sub->percent_three != "" || $sub->percent_four != "" || $sub->percent_five != "")
			                {
			                  $ips_data++;
			                }
		                    if($sub->textstatus == 1) { $texticonsub="display:none"; $hidesub = 'display:initial'; } else { $texticonsub="display:initial"; $hidesub = 'display:none'; }
		                    if($sub->flag == 0) { $flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>'; }
			                elseif($sub->flag == 1) { $flag_sub = '<i class="fa fa-flag flag_orange_sub fileattachment"></i>'; }
			                elseif($sub->flag == 2) { $flag_sub = '<i class="fa fa-flag flag_red_sub fileattachment"></i>'; }
		                    $downloadfile_output.= '<tr class="sub_attachment attachment_tr attachment_tr_'.$attachment->id.'" data-element="'.$file->id.'" data-value="'.$sub->id.'">
		                      <td colspan="5">
		                        	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$sub->id.'" value="'.$sub->textval.'" placeholder="Add Text" style="margin-left:7.5%;'.$hidesub.'">
		                        	'.$flag_sub.'
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input" style="border-left:1px solid #b5b3b3">
		                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                      <td class="td_input" style="border-right:1px solid #b5b3b3">
		                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                        <td class="td_input">
									<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
								</td>
								<td class="td_input">
									<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
								</td>
		                      <td class="td_input">
		                        <i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
		                      </td>
		                    </tr>';
		                  }
		                }
		                $downloadfile_output.='<tr class="show_iframe show_iframe_'.$attachment->id.'" style="display:none;">
	                    	<td colspan="15">
		                    	<a href="javascript:" class="show_iframe_prev common_black_button">Previous</a> 
				          		<a href="javascript:" class="show_iframe_next common_black_button">Next</a> 
				          		<a href="javascript:" class="show_iframe_hide common_black_button">Hide</a>
				          		<label class="pdf_multipage" style="margin-left: 10px;">Note: Multipage</label>
				          		<a href="javascript:" class="show_iframe_download common_black_button" style="float: right;margin-top: -7px;" download>Download</a> 
				          		<div style="width:100%;background:#b0a8a8;height:1200px;margin-top: 13px;">
				          			<iframe name="attachment_pdf" class="attachment_pdf" src="" style="width:100%;height: 1200px;"></iframe>
				          		</div>
				          	</td>
				          	<td colspan="3"></td>
	                    </tr>';
	                }
			    $downloadfile.=$downloadfile_output.'
			        </tbody>
			        <tbody>
			    	<tr class="p_total_tr_'.$file->id.'" data-element="'.$file->id.'">
	            		<td colspan="5" style="text-align:right"><strong>P Totals</strong></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td class="p_percent_one_total"></td>
	            		<td class="p_percent_two_total"></td>
	            		<td class="p_percent_three_total"></td>
	            		<td class="p_percent_five_total"></td>
	            		<td class="p_percent_four_total"></td>
	            		<td class="p_net_total"></td>
	            		<td class="p_vat_total"></td>
	            		<td class="p_gross_total"></td>
	            		<td class="p_currency_total"></td>
	            		<td class="p_value_total"></td>
	            		<td></td>
	            	</tr>
	            	<tr class="s_total_tr_'.$file->id.'" data-element="'.$file->id.'">
	            		<td colspan="5" style="text-align:right"><strong>S Totals</strong></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td class="s_percent_one_total"></td>
	            		<td class="s_percent_two_total"></td>
	            		<td class="s_percent_three_total"></td>
	            		<td class="s_percent_five_total"></td>
	            		<td class="s_percent_four_total"></td>
	            		<td class="s_net_total"></td>
	            		<td class="s_vat_total"></td>
	            		<td class="s_gross_total"></td>
	            		<td class="s_currency_total"></td>
	            		<td class="s_value_total"></td>
	            		<td></td>
	            	</tr>
			    </tbody></table>
	        </div>
	        <div style="float: left;margin-left: 17px;margin-bottom: 10px;">
    			<i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
    			<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
				<i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>
				<i class="fa fa-file-text-o summary_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Summary"></i>
				<i class="fa fa-calculator calculate_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Calculate"></i>
			</div>
	      </div>';
	    }
	    else{
	        $downloadfile ='<style>
            .bpso_all_check{font-size:20px; font-weight:700; margin-left:10px;}
            .bpso_all_check:hover{text-decoration:none}
           	.table_bspo .td_input { padding:3px !important; }
          </style>
          <div class="row infile_inner_table_row">
          	<div class="col-md-12">
          		<table class="table_bspo table-fixed-header" id="bspo_id_'.$file->id.'" style="width:100%;">
	                <tbody>
	                <tr data-element="'.$file->id.'">
	            		<td colspan="5">
	            		</td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            		<td></td>
	            	</tr>
			    	</tbody>
			    </table>
	        </div>
	        <div style="float: left;margin-left: 17px;margin-bottom: 10px;">
    			<i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
			</div>
	      </div>';
	    }
		if(($total_attachments))
		{
			$span = '<span style="color:#000">There are '.count($total_attachments).' file(s)</span>';
		}
		else{
			$span = '';
		}
		if($ips_data > 0)
		{
			$ps_data_btn = '<i class="fa fa-dot-circle-o" style="color:green;font-size: 20px;"></i>';
		}
		else{
			$ps_data_btn = '';
		}
		$output = $downloadfile.'
			<div style="width:100%; height:auto; float:left; padding-bottom:10px; color: #0300c1;">Notes:
				  <br/> '.$span.'
			</div>
			<div class="clearfix"></div>';
		if($page == "0" || $page == "1") {
		    echo json_encode(array("output" => mb_convert_encoding($output,'UTF-8','UTF-8'),"ps_data_btn" => $ps_data_btn));
		}
      	else{
      	    echo json_encode(array("output" => mb_convert_encoding($downloadfile_output,'UTF-8','UTF-8'),"ps_data_btn" => $ps_data_btn));
      	}
	}
	public function check_missing_files(Request $request)
	{
		$missing_files = strtolower($request->get('missing_files'));
		$path = $request->get('path');
		$files = glob(dirname($path));
		print_r($files);
		exit;
		print_r(getDirContents($path,$missing_files));
	}
	public function import_available_files(Request $request)
	{
		$filename = $request->get('filename');
		$fileid = $request->get('fileid');
		$fileval = $request->get('fileval');
	    $image_parts = explode(";base64,", $fileval);
	    $image_base64 = base64_decode($image_parts[1]);
	    $file = 'uploads/infile_image/'.base64_encode($fileid).'/'.$filename;
	    if(file_put_contents($file, $image_base64))
	    {
			echo "0";
		}
		else{
			echo "1";
		}
	}
	public function get_infile_check_reports(Request $request)
	{
		$fileid = $request->get('fileid');
		$get_reports = \App\Models\InfileCheckReport::where('fileid',$fileid)->get();
		$output = '<h5>Download:</h5>';
		if(($get_reports))
		{
			foreach($get_reports as $report)
			{
				$output.='<p><a href="'.URL::to($report->url).'" download="">'.$report->filename.'</a></p>';
			}
		}
		echo $output;
	}
	public function update_infile_textvalue_item(Request $request)
	{
		$file_id = $request->get('file_id');
		$radio = $request->get('radio');
		$value = $request->get('value');
		$inc = $request->get('inc');
		if($radio == "1")
		{
			$attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$file_id)->where('textval','')->where('p',1)->where('secondary',0)->get();
		}
		else{
			$attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$file_id)->where('textval','')->where('s',1)->where('secondary',0)->get();
		}
		if(($attachments))
		{
			foreach($attachments as $attach)
			{
				$data['textval'] = $value;
				\App\Models\inFileAttachment::where('id',$attach->id)->update($data);
				$get_sub_attachments = \App\Models\inFileAttachment::select('id')->where('attach_id',$attach->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(($get_sub_attachments))
				{
					$sub_attach_value = $value;
					foreach($get_sub_attachments as $sub_attach)
					{
						$sub_attach_value = (float)$sub_attach_value + .001;
						$subdata['textval'] = $sub_attach_value;
						\App\Models\inFileAttachment::where('id',$sub_attach->id)->update($subdata);
					}
				}
				$value = (float)$value + (float)$inc;
			}
		}
	}
	public function renumber_infile_textvalue_item(Request $request)
	{
		$file_id = $request->get('file_id');
		$radio = $request->get('radio');
		$value = $request->get('value');
		$inc = $request->get('inc');
		if($radio == "1")
		{
			$attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$file_id)->where('p',1)->where('secondary',0)->get();
		}
		else{
			$attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$file_id)->where('s',1)->where('secondary',0)->get();
		}
		if(($attachments))
		{
			foreach($attachments as $attach)
			{
				$data['textval'] = $value;
				\App\Models\inFileAttachment::where('id',$attach->id)->update($data);
				$get_sub_attachments = \App\Models\inFileAttachment::select('id')->where('attach_id',$attach->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(($get_sub_attachments))
				{
					$sub_attach_value = $value;
					foreach($get_sub_attachments as $sub_attach)
					{
						$sub_attach_value = (float)$sub_attach_value + .001;
						$subdata['textval'] = $sub_attach_value;
						\App\Models\inFileAttachment::where('id',$sub_attach->id)->update($subdata);
					}
				}
				$value = (float)$value + (float)$inc;
			}
		}
	}
	public function check_secondary_line_has_value(Request $request)
	{
		$fileid = $request->get('file_id');
		$radio = $request->get('radio');
		if($radio == "1")
		{
			$primary_attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$fileid)->where('textval','')->where('secondary',0)->where('p',1)->get();
		}
		else{
			$primary_attachments = \App\Models\inFileAttachment::select('id')->where('file_id',$fileid)->where('textval','')->where('secondary',0)->where('s',1)->get();
		}
		$alertmsg = 0;
		if(($primary_attachments))
		{
			foreach($primary_attachments as $primary)
			{
				$secondary_attachments = \App\Models\inFileAttachment::where('attach_id',$primary->id)->where('secondary',1)->where('textval','!=','')->count();
				if($secondary_attachments > 0)
				{
					$alertmsg = (int)$alertmsg + 1;
				}
			}
		}
		echo $alertmsg;
	}
	public function load_all_clients_infile_advanced(Request $request)
	{
		$output='';
		$i=1;
		$clientslist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		if(($clientslist)){
		foreach ($clientslist as $client) {
		  $clientid = $client->client_id;
		  $firstname = $client->firstname;
		  $lastname = $client->surname;
		  $company = $client->company;
		  if($i < 10)
		  {
		    $i = '0'.$i;
		  }
		  $received = \App\Models\inFile::where('client_id',$client->client_id)->count();
		  $complete = \App\Models\inFile::where('client_id',$client->client_id)->where('status',1)->count();
		  $incomplete = \App\Models\inFile::where('client_id',$client->client_id)->where('status',0)->count();
		  if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
		  if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
		  if($client->active == 2) { $color = 'color:#F00 !important'; }
		  else { $color = 'color:#000 !important'; }
		  $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
		  $output.='
		  <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
		    <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
		    <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
		    <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
		    <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
		    <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
		    <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
		    <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
		      <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
		      <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
		      $infiles = \App\Models\inFile::where('client_id', $client->client_id)->get();
		      if(($infiles))
		      {
		        foreach($infiles as $infile)
		        {
		          $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
		        }
		      }
		    $output.='</td>
		  </tr>';
		  $i++;
		}
		}
		else{
		$output.='
		  <tr>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center">Empty</td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		  </tr>
		';
		}
		echo $output;
	}
	public function load_single_client_infile_advanced(Request $request)
	{
		$client_id = $request->get('client_id');
		$output='';
		$i=1;
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		if(($client)){
		  $clientid = $client->client_id;
		  $firstname = $client->firstname;
		  $lastname = $client->surname;
		  $company = $client->company;
		  if($i < 10)
		  {
		    $i = '0'.$i;
		  }
		  $received = \App\Models\inFile::where('client_id',$client->client_id)->count();
		  $complete = \App\Models\inFile::where('client_id',$client->client_id)->where('status',1)->count();
		  $incomplete = \App\Models\inFile::where('client_id',$client->client_id)->where('status',0)->count();
		  if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
		  if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
		  if($client->active == 2) { $color = 'color:#F00 !important'; }
		  else { $color = 'color:#000 !important'; }
		  $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
		  $output.='
		  <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
		    <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
		    <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
		    <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
		    <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
		    <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
		    <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
		    <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
		      <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
		      <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
		      $infiles = \App\Models\inFile::where('client_id', $client->client_id)->get();
		      if(($infiles))
		      {
		        foreach($infiles as $infile)
		        {
		          $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
		        }
		      }
		    $output.='</td>
		  </tr>';
		  $i++;
		}
		else{
			$output.='
			  <tr>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center">Empty</td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			  </tr>
			';
		}
		echo $output;
	}
	public function infile_edit_description(Request $request)
	{
		$fileid = $request->get('fileid');
		$details = \App\Models\inFile::where('id',$fileid)->first();
		$description = '';
		if(($details))
		{
			$description = $details->description;
		}
		echo $description;
	}
	public function update_edit_description(Request $request)
	{
		$fileid = $request->get('fileid');
		$value = $request->get('value');
		$data['description'] = $value;
		\App\Models\inFile::where('id',$fileid)->update($data);
	}
	public function get_summary_infile_attachments(Request $request){
		$file_id = $request->get('file_id');
		$file_details = \App\Models\inFile::where('id',$file_id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$file_details->client_id)->first();
		$client_id = $file_details->client_id;
		$client_name = $client_details->company;
		$description = $file_details->description;
		$filecount_purchase = \App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->get();
		$filecount_sales = \App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->get();
		$filecount_bank = \App\Models\inFileAttachment::where('file_id',$file_id)->where('b',1)->get();
		$filecount_others = \App\Models\inFileAttachment::where('file_id',$file_id)->where('o',1)->get();
		$filecount_unspecified = \App\Models\inFileAttachment::where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->get();
		$filecount_totals = count($filecount_purchase) + count($filecount_sales) + count($filecount_bank) + count($filecount_others) + count($filecount_unspecified);
		$net_purchase = \App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum('net');
		$net_sales = \App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum('net');
		$net_others = \App\Models\inFileAttachment::where('file_id',$file_id)->where('o',1)->sum('net');
		$net_unspecified = \App\Models\inFileAttachment::where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('net');
		$net_totals = \App\Models\inFileAttachment::where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('net');
		$vat_purchase = \App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum('vat');
		$vat_sales = \App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum('vat');
		$vat_others = \App\Models\inFileAttachment::where('file_id',$file_id)->where('o',1)->sum('vat');
		$vat_unspecified = \App\Models\inFileAttachment::where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('vat');
		$vat_totals = \App\Models\inFileAttachment::where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('vat');
		$gross_purchase = \App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum('gross');
		$gross_sales = \App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum('gross');
		$gross_others = \App\Models\inFileAttachment::where('file_id',$file_id)->where('o',1)->sum('gross');
		$gross_unspecified = \App\Models\inFileAttachment::where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('gross');
		$gross_totals = \App\Models\inFileAttachment::where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('gross');
		$outputresult = '<div class="col-md-12" style="padding:0px">
			<div class="col-md-5" style="padding:0px">
				<label>Client Name:</label> '.$client_name.'
			</div>
			<div class="col-md-3" style="padding:0px">
				<label>Client Code:</label> '.$client_id.'
			</div>
			<div class="col-md-4" style="padding:0px">
				<label>Description:</label> '.$description.'
			</div>
			<table class="table" style="margin-top:20px">
				<thead>
					<th></th>
					<th>Purchase</th>
					<th>Sales</th>
					<th>Bank</th>
					<th>Other</th>
					<th>Unspecified</th>
					<th>NonEuro</th>
					<th>Total</th>
				</thead>
				<tbody>
					<tr>
						<td>FileCount</td>
						<td>'.count($filecount_purchase).'</td>
						<td>'.count($filecount_sales).'</td>
						<td>'.count($filecount_bank).'</td>
						<td>'.count($filecount_others).'</td>
						<td>'.count($filecount_unspecified).'</td>
						<td></td>
						<td>'.$filecount_totals.'</td>
					</tr>
					<tr>
						<td>Net</td>
						<td>'.number_format_invoice($net_purchase).'</td>
						<td>'.number_format_invoice($net_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($net_others).'</td>
						<td>'.number_format_invoice($net_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($net_totals).'</td>
					</tr>
					<tr>
						<td>VAT</td>
						<td>'.number_format_invoice($vat_purchase).'</td>
						<td>'.number_format_invoice($vat_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($vat_others).'</td>
						<td>'.number_format_invoice($vat_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($vat_totals).'</td>
					</tr>
					<tr>
						<td>Gross</td>
						<td>'.number_format_invoice($gross_purchase).'</td>
						<td>'.number_format_invoice($gross_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($gross_others).'</td>
						<td>'.number_format_invoice($gross_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($gross_totals).'</td>
					</tr>
				</tbody>
			</table>
		</div>';
		echo $outputresult;
	}
	public function change_file_status_to_zero(Request $request){
		$fileid = $request->get('fileid');
		$status = $request->get('status');
		if($status == 1){
			\App\Models\inFileAttachment::where('file_id',$fileid)->where('status',5)->where('secondary',0)->delete();
		}
		else{
			$data['status'] = 0;
			\App\Models\inFileAttachment::where('file_id',$fileid)->where('status',5)->where('secondary',0)->update($data);
		}
	}
	public function calculate_infile_attachments_counts(Request $request)
	{
		$file_id = $request->get('file_id');
		$data['p_percent_one_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("percent_one"));
		$data['p_percent_two_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("percent_two"));
		$data['p_percent_three_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("percent_three"));
		$data['p_percent_four_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("percent_four"));
		$data['p_percent_five_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("percent_five"));
		$data['p_net_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("net"));
		$data['p_vat_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("vat"));
		$data['p_gross_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("gross"));
		$data['p_currency_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("currency"));
		$data['p_value_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('p',1)->sum("value"));
		$data['s_percent_one_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("percent_one"));
		$data['s_percent_two_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("percent_two"));
		$data['s_percent_three_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("percent_three"));
		$data['s_percent_four_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("percent_four"));
		$data['s_percent_five_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("percent_five"));
		$data['s_net_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("net"));
		$data['s_vat_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("vat"));
		$data['s_gross_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("gross"));
		$data['s_currency_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("currency"));
		$data['s_value_total'] = number_format_invoice(\App\Models\inFileAttachment::where('file_id',$file_id)->where('s',1)->sum("value"));
		echo json_encode($data);
	}
	public function save_supplier_names_for_client(Request $request){
		$suppliers = json_decode(stripslashes($request->get('suppliers')));
		$codes = json_decode(stripslashes($request->get('codes')));
		$client_id = $request->get('client_id');
		\App\Models\InFileSupplier::where('client_id',$client_id)->delete();
		if(($suppliers)){
			foreach($suppliers as $key => $supplier){
				$data['client_id'] = $client_id;
				$data['supplier'] = $supplier;
				$data['code'] = $codes[$key];
				\App\Models\InFileSupplier::insert($data);
			}
		}
	}
	public function submit_imported_supplier_for_client(Request $request){
		$client_id = $request->get('hidden_infile_client_id');
		if($_FILES['import_supplier_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_supplier_file']['tmp_name'];
			$name=time().'_'.$_FILES['import_supplier_file']['name'];
			$errorlist = array();
			$error_code = "";
			$k = 0;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$chunk_size = 100000;
				$csv_data = array_map('str_getcsv', file($filepath));
				$chunked_data = array_chunk($csv_data, $chunk_size);
				$total_count = count($chunked_data[0]);
				$rowvalue = 2;
				if($total_count < 2){
					$error_code = "3";
					echo json_encode(array("error_code" => $error_code, "output" => ""));
					exit;
				}
				foreach($chunked_data[0] as $key => $row)
				{
					if($key == 0) {
						$column_1 = (isset($row[0])) ? strtolower($row[0]) : '';
						$column_2 = (isset($row[1])) ? strtolower($row[1]) : '';
						$column_3 = (isset($row[2])) ? strtolower($row[2]) : '';
						if($column_1 != "supplier/customer" || $column_2 != "code") {
							$error_code = "1";
							echo json_encode(array("error_code" => $error_code, "output" => ""));
							exit;
						}
						elseif($column_3 != ""){
							$error_code = "2";
							echo json_encode(array("error_code" => $error_code, "output" => ""));
							exit;
						}
					}
					else{
						$row_0 = (isset($row[0])) ? $row[0] : '';
						$row_1 = (isset($row[1])) ? $row[1] : '';
						if($row_0 != ""){
							$row_0 = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $row_0);
							$dataval['client_id'] = $client_id;
							$dataval['supplier'] = $row_0;
							$dataval['code'] = $row_1;
							\App\Models\InFileSupplier::insert($dataval);
						}
					}
				}
				$error_code = "0";
				echo json_encode(array("error_code" => $error_code, "output" => ""));
				exit;
			}
			else{
				$error_code = "2";
				echo json_encode(array("error_code" => $error_code, "output" => ""));
				exit;
			}
		}
	}
	public function export_supplier_names_for_client(Request $request){
		$client_id = $request->get('client_id');
		$suppliers = \App\Models\InFileSupplier::where('client_id',$client_id)->get();
		$filename  = time().'_Infile_Supplier_Code_for_Client_Report.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');
		$columns1 = array('Supplier/Customer','Code');
		fputcsv($file, $columns1);
		if(($suppliers))
		{
			foreach($suppliers as $supplier){
				$columns2 = array($supplier->supplier,$supplier->code);
				fputcsv($file, $columns2);
			}
		}
		else{
			$columns2 = array("No Supplier Found","");
				fputcsv($file, $columns2);
		}
		fclose($file);
		echo $filename;
	}
	public function save_apply_supplier_names_for_client(Request $request){
		$suppliers = json_decode(stripslashes($request->get('suppliers')));
		$codes = json_decode(stripslashes($request->get('codes')));
		$lowersuppliers = array_map('strtolower', $suppliers);
		$trimed_suppliers = array();
		$duplicates = array();
		if(($lowersuppliers)){
			foreach($lowersuppliers as $keyval => $lowersupp){
				$replace_supp = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $lowersupp);
				if(!in_array(trim($replace_supp), $trimed_suppliers))
				{
					array_push($trimed_suppliers,trim($replace_supp));
				}
				else{
					array_push($duplicates,$keyval);
				}
			}
		}
		if(($duplicates) > 0)
		{
			echo json_encode(array("duplicates" => $duplicates));
		}
		else{
			$client_id = $request->get('client_id');
			\App\Models\InFileSupplier::where('client_id',$client_id)->delete();
			if(($suppliers)){
				foreach($suppliers as $key => $supplier){
					$data['client_id'] = $client_id;
					$data['supplier'] = $supplier;
					$data['code'] = $codes[$key];
					\App\Models\InFileSupplier::insert($data);
				}
			}
			$get_suppliers = \App\Models\InFileSupplier::where('client_id',$client_id)->orderBy('supplier','asc')->get();
			$supplier_text = '';
			$arr_supp = array();
			if(($get_suppliers))
			{
				foreach($get_suppliers as $supplier){
					$supp = $supplier->supplier;
					if(!in_array(strtolower(trim($supp)), $arr_supp))
					{
						$code = '';
						if($supplier->code != ""){
							$code =  ' -- '.$supplier->code;
						}
						if($supplier_text == ""){
							$supplier_text = $supplier->supplier.$code;
						}
						else{
							$supplier_text = $supplier_text.','.$supplier->supplier.$code;
						}
						array_push($arr_supp,strtolower(trim($supp)));
					}
				}
			}
			$dataval['supplier'] = $supplier_text;
			\App\Models\inFile::where('client_id',$client_id)->update($dataval);
			echo json_encode(array("duplicates" => 0));
		}
	}
	public function clear_supplier_for_client(Request $request){
		$client_id = $request->get('client_id');
		\App\Models\InFileSupplier::where('client_id',$client_id)->delete();
	}
	public function update_infile_settings(Request $request) {
		$cc_email = $request->get('infile_cc_email');
		$data['cc_email'] = $cc_email;
		$data['loadcount'] = $request->get('loadcount');	

		$check_settings = DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		if($check_settings) {
			DB::table('infile_settings')->where('id',$check_settings->id)->update($data);
		}
		else{
			$data['practice_code'] = Session::get('user_practice_code');
			DB::table('infile_settings')->insert($data);
		}
		return redirect::back()->with('message', 'InFile Setings Saved Sucessfully.');
	}
	public function get_ps_data_items_count(Request $request) {
		$fileid = $request->get('fileid');
		$get_p_count = \App\Models\inFileAttachment::where('file_id',$fileid)->where('p',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->count();
		$get_s_count = \App\Models\inFileAttachment::where('file_id',$fileid)->where('s',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->count();
		echo json_encode(array('p_count' => $get_p_count, 's_count' => $get_s_count));
	}
	public function compare_ps_data(Request $request) {
		$fileid = $request->get('fileid');
		$pinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('p',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
		$sinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('s',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();

		$ptable = '';
		$i = 1;
		if(($pinfiles)) {
			$ptable.='<h4 style="font-weight: 700;">P Class Item Review Result</h4>
			<div class="p_class_table_div" style="max-height: 300px; overflow-y: scroll; scrollbar-color: #3db0e6 #fff;scrollbar-width: thin;">
			<table class="table tablefixedheader">
				<thead>
					<th>S.No</th>
					<th>Primary Que ID</th>
					<th>Issue ID</th>
					<th>Issue Description</th>
				</thead>
				<tbody>';
			foreach($pinfiles as $pinfile) {
				$mainqueid = $pinfile->textval;
				$mainsupplier = strtolower($pinfile->supplier);
				$maindate_attachment = $pinfile->date_attachment;
				$mainnet = $pinfile->net;

				$compare = 0;
				if($mainsupplier != "") {
					if($maindate_attachment != "" && $mainnet != "") {
						$compare = 1;
					}
					elseif($maindate_attachment != "" && $mainnet == "") {
						$compare = 2;
					}
					elseif($maindate_attachment == "" && $mainnet != "") {
						$compare = 3;
					}
				}

				$psubinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('id','!=',$pinfile->id)->where('p',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
				if(($psubinfiles)) {
					foreach($psubinfiles as $psubinfile) {
						$subqueid = $psubinfile->textval;
						$subsupplier = strtolower($psubinfile->supplier);
						$subdate_attachment = $psubinfile->date_attachment;
						$subnet = $psubinfile->net;
						$issue_description = '';
						if($subqueid == ''){
							echo 0;
							exit;
						}

						if($compare == 1) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Exact Match - Text, Date and Net - Match';
							}
							elseif($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
							elseif($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}
						elseif($compare == 2) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
						}
						elseif($compare == 3) {
							if($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}

						if($issue_description != '') {
							$ptable.='<tr>
								<td>'.$i.'</td>
								<td>'.$primary_id.'</td>
								<td>'.$issue_id.'</td>
								<td>'.$issue_description.'</td>
							</tr>';
							$i++;
						}
						
					}
				}
			}
			if($i == 1) {
				$ptable.='<tr>
					<td colspan="4">No Issues Found</td>
				</tr>';
			}
			$ptable.='</tbody>
			</table>
			</div>';
		}

		$stable = '';
		$i = 1;
		if(($sinfiles)) {
			$stable.='<h4 style="font-weight: 700;margin-top:40px">S Class Item Review Result</h4>
			<div class="p_class_table_div" style="max-height: 300px; overflow-y: scroll; scrollbar-color: #3db0e6 #fff;scrollbar-width: thin;">
			<table class="table tablefixedheader">
				<thead>
					<th>S.No</th>
					<th>Primary Que ID</th>
					<th>Issue ID</th>
					<th>Issue Description</th>
				</thead>
				<tbody>';
			foreach($sinfiles as $sinfile) {
				$mainqueid = $sinfile->textval;
				$mainsupplier = strtolower($sinfile->supplier);
				$maindate_attachment = $sinfile->date_attachment;
				$mainnet = $sinfile->net;

				$compare = 0;
				if($mainsupplier != "") {
					if($maindate_attachment != "" && $mainnet != "") {
						$compare = 1;
					}
					elseif($maindate_attachment != "" && $mainnet == "") {
						$compare = 2;
					}
					elseif($maindate_attachment == "" && $mainnet != "") {
						$compare = 3;
					}
				}

				$ssubinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('id','!=',$sinfile->id)->where('s',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
				if(($ssubinfiles)) {
					foreach($ssubinfiles as $ssubinfile) {
						$subqueid = $ssubinfile->textval;
						$subsupplier = strtolower($ssubinfile->supplier);
						$subdate_attachment = $ssubinfile->date_attachment;
						$subnet = $ssubinfile->net;
						$issue_description = '';
						if($subqueid == ''){
							echo 0;
							exit;
						}

						if($compare == 1) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Exact Match - Text, Date and Net - Match';
							}
							elseif($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
							elseif($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}
						elseif($compare == 2) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
						}
						elseif($compare == 3) {
							if($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}
						if($issue_description != '') {
							$stable.='<tr>
								<td>'.$i.'</td>
								<td>'.$primary_id.'</td>
								<td>'.$issue_id.'</td>
								<td>'.$issue_description.'</td>
							</tr>';
							$i++;
						}
						
					}
				}
			}
			if($i == 1) {
				$stable.='<tr>
					<td colspan="4">No Issues Found</td>
				</tr>';
			}
			$stable.='</tbody>
			</table>
			</div>';
		}

		$output = $ptable.$stable;
		echo $output;
	}
	public function export_compare_ps_data(Request $request) {
		$fileid = $request->get('fileid');
		$pinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('p',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
		$sinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('s',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();

		$ptable = '';
		$i = 1;

		$filenameval = 'Infile_DataReview_EXPORT_'.date('d-M-Y H-i').'.csv';
		$columns_1 = array('','','P Class Item Review Result','','');
		$fileopen = fopen('public/'.$filenameval.'', 'w');
    	fputcsv($fileopen, $columns_1);
		if(($pinfiles)) {
			$columns_2 = array('Primary QueID','Supplier/Customer','Date','Net','Issue ID','Issue Description');
			fputcsv($fileopen, $columns_2);
			foreach($pinfiles as $pinfile) {
				$mainqueid = $pinfile->textval;
				$mainsupplier = strtolower($pinfile->supplier);
				$maindate_attachment = $pinfile->date_attachment;
				$mainnet = $pinfile->net;

				$compare = 0;
				if($mainsupplier != "") {
					if($maindate_attachment != "" && $mainnet != "") {
						$compare = 1;
					}
					elseif($maindate_attachment != "" && $mainnet == "") {
						$compare = 2;
					}
					elseif($maindate_attachment == "" && $mainnet != "") {
						$compare = 3;
					}
				}

				$psubinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('id','!=',$pinfile->id)->where('p',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
				if(($psubinfiles)) {
					foreach($psubinfiles as $psubinfile) {
						$subqueid = $psubinfile->textval;
						$subsupplier = strtolower($psubinfile->supplier);
						$subdate_attachment = $psubinfile->date_attachment;
						$subnet = $psubinfile->net;
						$issue_description = '';
						if($subqueid == ''){
							echo 0;
							exit;
						}

						if($compare == 1) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Exact Match - Text, Date and Net - Match';
							}
							elseif($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
							elseif($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}
						elseif($compare == 2) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
						}
						elseif($compare == 3) {
							if($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}

						if($issue_description != '') {
							$columns_3 = array($primary_id,$mainsupplier,$maindate_attachment,$mainnet,$issue_id,$issue_description);
							fputcsv($fileopen, $columns_3);
							$i++;
						}
					}
				}
			}
			if($i==1){
				$columns_4 = array("","","No Issues Found","","");
				fputcsv($fileopen, $columns_4);
			}
		}

		$stable = '';
		$i = 1;

		$columns_1 = array('','','','','');
    	fputcsv($fileopen, $columns_1);

		$columns_1 = array('','','S Class Item Review Result','','');
    	fputcsv($fileopen, $columns_1);

		if(($sinfiles)) {
			$columns_2 = array('Primary QueID','Supplier/Customer','Date','Net','Issue ID','Issue Description');
			fputcsv($fileopen, $columns_2);
			foreach($sinfiles as $sinfile) {
				$mainqueid = $sinfile->textval;
				$mainsupplier = strtolower($sinfile->supplier);
				$maindate_attachment = $sinfile->date_attachment;
				$mainnet = $sinfile->net;

				$compare = 0;
				if($mainsupplier != "") {
					if($maindate_attachment != "" && $mainnet != "") {
						$compare = 1;
					}
					elseif($maindate_attachment != "" && $mainnet == "") {
						$compare = 2;
					}
					elseif($maindate_attachment == "" && $mainnet != "") {
						$compare = 3;
					}
				}

				$ssubinfiles = \App\Models\inFileAttachment::where('file_id',$fileid)->where('id','!=',$sinfile->id)->where('s',1)->where('status', 0)->where('secondary',0)->where('notes_type', 0)->get();
				if(($ssubinfiles)) {
					foreach($ssubinfiles as $ssubinfile) {
						$subqueid = $ssubinfile->textval;
						$subsupplier = strtolower($ssubinfile->supplier);
						$subdate_attachment = $ssubinfile->date_attachment;
						$subnet = $ssubinfile->net;
						$issue_description = '';
						if($subqueid == ''){
							echo 0;
							exit;
						}

						if($compare == 1) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Exact Match - Text, Date and Net - Match';
							}
							elseif($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
							elseif($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}
						elseif($compare == 2) {
							if($mainsupplier == $subsupplier && $maindate_attachment == $subdate_attachment) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Date - Match';
							}
						}
						elseif($compare == 3) {
							if($mainsupplier == $subsupplier && $mainnet == $subnet) {
								$primary_id = $mainqueid;
								$issue_id = $subqueid;
								$issue_description = 'Partial Match - Text and Net - Match';
							}
						}

						if($issue_description != '') {
							$columns_3 = array($primary_id,$mainsupplier,$maindate_attachment,$mainnet,$issue_id,$issue_description);
							fputcsv($fileopen, $columns_3);
							$i++;
						}
						
					}
				}
			}
			if($i == 1) {
				$columns_4 = array("","","No Issues Found","","");
				fputcsv($fileopen, $columns_4);
			}
		}
		fclose($fileopen);

		echo $filenameval;
	}
	public function edit_infile_header_image(Request $request) {
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

                    DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
}
