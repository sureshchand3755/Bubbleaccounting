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
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DatePeriod;
use DateInterval;
use Illuminate\Http\Request;
use Log;
class TaskmanagerController extends Controller {
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
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function task_manager(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/task_manager/task_manager', array('title' => 'Bubble - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' =>$park_task_count));
	}
	public function park_task(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}

		$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/task_manager/park_task', array('title' => 'Bubble - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' =>$park_task_count));
	}
	public function taskmanager_search(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/task_manager/task_search', array('title' => 'Bubble - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count));
	}
	public function task_administration(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->offset(0)->limit(500)->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/task_manager/task_administration', array('title' => 'Bubble - Task Manager', 'taskslist' => $tasks,'userlist' => $user,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count));
	}
	public function task_overview(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->get();
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		return view('user/task_manager/task_overview', array('title' => 'Bubble - Task Overview','user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count, 'userlist' => $user,'clientlist' => $clientlist));
	}
	public function show_infiles(Request $request)
	{
		$client_id = $request->get('client_id');
		$ids = explode(",",$request->get('ids'));
		$infiles = \App\Models\inFile::where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function show_progress_infiles(Request $request)
	{
		$client_id = $request->get('client_id');
		$ids = explode(",",$request->get('ids'));
		$infiles = \App\Models\inFile::where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_progress_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function show_completion_infiles(Request $request)
	{
		$client_id = $request->get('client_id');
		$ids = explode(",",$request->get('ids'));
		$infiles = \App\Models\inFile::where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(is_countable($infiles) && count($infiles) > 0)
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_completion_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function show_completion_yearend(Request $request)
	{
		$client_id = $request->get('client_id');
		$ids = explode(",",$request->get('ids'));
		$output = '<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Document</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		$i = 1;
		$yearend =\App\Models\YearClient::where('client_id',$client_id)->orderBy('id','desc')->first();
		if($yearend)
		{
			$setting_id = explode(',',$yearend->setting_id);
			$setting_active = explode(',',$yearend->setting_active);
			foreach($setting_id as $skey => $setting)
			{
				$setting_name = \App\Models\YearSetting::where('id',$setting)->first();
				if($setting_active[$skey] == "0")
				{
					$ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend->id).'');
					if(in_array($setting,$ids))
					{
						$checked = 'checked';
					}
					else{
						$checked = '';
					}
					$output.='<tr>
						<td>'.$i.'</td>
						<td style="text-align:left"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$setting_name->document.'</a></td>
						<td style="text-align:left"><input type="checkbox" name="yearend_check" class="yearend_completion_check" value="'.$setting.'" '.$checked.'><label>&nbsp;</label></td>
					</tr>';
					$i++;
				}
			}
		}
		if($i == 1)
		{
			$output.='<tr>
				<td colspan="3">No Yearend Document Found</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function infile_upload_images_taskmanager_add(Request $request)
	{
		$upload_dir = 'uploads/taskmanager_image';
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
			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);
	 		if(Session::has('task_file_attach_add'))
			{
				$getsession = Session::get('task_file_attach_add');
			}
			else{
				$getsession = array();
			}
			$getsession = array_values($getsession);
			array_push($getsession,$arrayval);
			$sessn=array('task_file_attach_add' => $getsession);
			Session::put($sessn);
			move_uploaded_file($tmpFile,$filename);
			$key = count($getsession) - 1;
		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => $key,'count'=>count($getsession)));
		}
	}
	public function infile_upload_images_taskmanager_progress(Request $request)
	{
		$task_id = $request->get('hidden_task_id_progress');
		$user_id = $request->get('user_id');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
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

			move_uploaded_file($tmpFile,$filename);
			$data['task_id'] = $task_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir; 
			$data['status'] = 1;

			$insertedid = \App\Models\taskmanagerFiles::insertDetails($data);
			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_taskmanager_files?file_id='.$insertedid.'');
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user_id)->first();
			$current_date = date('d-M-Y');
			$message = '<spam style="font-weight:400">'.$fname.'</spam><br/>';		
			// $data_specifics['task_id'] = $task_id;
			// $data_specifics['message'] = $message;
			// $data_specifics['from_user'] = $user_id;
			// \App\Models\taskmanagerSpecifics::insert($data_specifics);
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url, 'from_user' => $user_id, 'message' => $message));
		}
	}
	public function infile_upload_images_taskmanager_completion(Request $request)
	{
		$task_id = $request->get('hidden_task_id_completion');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
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
			move_uploaded_file($tmpFile,$filename);
			$data['task_id'] = $task_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir; 
			$data['status'] = 2;
			$insertedid = \App\Models\taskmanagerFiles::insertDetails($data);
			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_taskmanager_files?file_id='.$insertedid.'');
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
		}
	}
	public function add_taskmanager_notepad_contents(Request $request)
	{
		$contents = $request->get('contents');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if(Session::has('notepad_attach_task_add'))
		{
			$count = count(Session::get('notepad_attach_task_add'));
			$counts = $count + 1;
			$file_name =  time();
			$filename = $file_name.'-'.$counts;	
			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = Session::get('notepad_attach_task_add');
			$getsession = array_values($getsession);
			array_push($getsession,$arrayval);
		}
		else{
			$count = 0;
			$counts = $count + 1;
			$file_name =  time();
			$filename = $file_name.'-'.$counts;	
			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = array();
			array_push($getsession,$arrayval);
		}
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$sessn=array('notepad_attach_task_add' => $getsession);
		Session::put($sessn);
		$key = count($getsession) - 1;
		echo json_encode(array('filename' => $filename.".txt",'file_id' => $key));
	}
	public function taskmanager_notepad_contents_progress(Request $request)
	{
		$contents = $request->get('contents');
		$task_id = $request->get('task_id');
		$user_id = $request->get('user_id');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$count = 0;
		$counts = $count + 1;
		$file_name =  time();
		$filename = $file_name.'-'.$counts;	
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$data['task_id'] = $task_id;
		$data['url'] = $upload_dir;
		$data['filename'] = $filename.".txt";
		$data['status'] = 1;
		$insertedid = \App\Models\taskmanagerNotepad::insertDetails($data);
		$download_url = URL::to($upload_dir."/".$filename.".txt");
		$delete_url = URL::to('user/delete_taskmanager_notepad?file_id='.$insertedid.'');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user_id)->first();
		$current_date = date('d-M-Y');
		$message = '<spam style="color:#006bc7">$$$<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> Has added files to this task on <strong>'.$current_date.'</strong>$$$</spam> <br/><spam style="font-weight:400">'.$filename.'.txt'.'</spam>';
		$data_specifics['task_id'] = $task_id;
		$data_specifics['message'] = $message;
		$data_specifics['from_user'] = $user_id;
		\App\Models\taskmanagerSpecifics::insert($data_specifics);
		$auditdata['user_id'] = $user_id;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Files Added';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
	 	echo json_encode(array('id' => $insertedid,'filename' => $filename.".txt",'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function taskmanager_notepad_contents_completion(Request $request)
	{
		$contents = $request->get('contents');
		$task_id = $request->get('task_id');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$count = 0;
		$counts = $count + 1;
		$file_name =  time();
		$filename = $file_name.'-'.$counts;	
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$data['task_id'] = $task_id;
		$data['url'] = $upload_dir;
		$data['filename'] = $filename.".txt";
		$data['status'] = 2;
		$insertedid = \App\Models\taskmanagerNotepad::insertDetails($data);
		$download_url = URL::to($upload_dir."/".$filename.".txt");
		$delete_url = URL::to('user/delete_taskmanager_notepad?file_id='.$insertedid.'');
	 	echo json_encode(array('id' => $insertedid,'filename' => $filename.".txt",'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function clear_session_task_attachments(Request $request)
	{
		if(Session::has('notepad_attach_task_add'))
		{
			Session::forget("notepad_attach_task_add");
		}
		if(Session::has('task_file_attach_add'))
		{
			Session::forget("task_file_attach_add");
		}
		$dir = "uploads/taskmanager_image/temp";//"path/to/targetFiles";
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
	    $fileid = $request->get('fileid');
		if($fileid == "0")
		{
			echo '';
		}
		else{
			$file = \App\Models\inFile::where('id',$fileid)->first();
			$output = '<table class="table">
			<tbody>';
			if(($file))
			{
				$i = 1;
				if($file->status == 1)
				{
					$red = 'color:#f00';
				}
				else{
					$red = 'color:#000';
				}
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				$output.='<tr>
					<td>'.$i.'</td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><a href="javascript:" class="fa fa-trash remove_infile_link_add" data-element="'.$file->id.'"></a></td>
				</tr>';
				$i++;
			}
			else{
				$output.='<tr>
						<td colspan="3">No Files Found</td>
					</tr>';
			}
			$output.='</tbody>
			</table>';
			echo $output;
		}
	}
	public function tasks_remove_dropzone_attachment(Request $request)
	{
		$file_id = $_POST['file_id'];
		if(Session::has('task_file_attach_add'))
		{
			$files = Session::get('task_file_attach_add');
			unset($files[$file_id]);
			$getsession = array_values($files);
			$sessn=array('task_file_attach_add' => $getsession);
			Session::put($sessn);
		}
	}
	public function tasks_remove_notepad_attachment(Request $request)
	{
		$file_id = $_POST['file_id'];
		if(Session::has('notepad_attach_task_add'))
		{
			$files = Session::get('notepad_attach_task_add');
			unset($files[$file_id]);
			$getsession = array_values($files);
			$sessn=array('notepad_attach_task_add' => $getsession);
			Session::put($sessn);
		}
	}
	public function show_linked_infiles(Request $request)
	{
		$ids = explode(",",$request->get('ids'));
		$infiles = \App\Models\inFile::whereIn('id',$ids)->get();
		$output = '<table class="table">
		<tbody>';
		if(($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
				}
				else{
					$red = 'color:#000';
				}
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				$output.='<tr>
					<td>'.$i.'</td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><a href="javascript:" class="fa fa-trash remove_infile_link_add" data-element="'.$file->id.'"></a></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="3">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function show_linked_progress_infiles(Request $request)
	{
		$ids = explode(",",$request->get('ids'));
		$task_id = $request->get('task_id');
		\App\Models\taskmanagerInfiles::where('task_id',$task_id)->where('status',1)->delete();
		$output = 'Linked Infiles:<br/>';
		if(($ids))
		{
			$i = 1;
			foreach($ids as $id)
			{
				$dataval['task_id'] = $task_id;
				$dataval['infile_id'] = $id;
				$dataval['status'] = 1;
				$insertedid = \App\Models\taskmanagerInfiles::insertDetails($dataval);
				$file = \App\Models\inFile::where('id',$id)->first();
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				$output.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
                <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$insertedid.'').'" class="fa fa-trash delete_attachments"></a>
                </p>';
				$i++;
			}
		}
		echo $output;
	}
	public function show_linked_completion_infiles(Request $request)
	{
		$ids = explode(",",$request->get('ids'));
		$task_id = $request->get('task_id');
		\App\Models\taskmanagerInfiles::where('task_id',$task_id)->where('status',2)->delete();
		$output = 'Linked Infiles: <br/>';
		if(($ids))
		{
			$i = 1;
			foreach($ids as $id)
			{
				$dataval['task_id'] = $task_id;
				$dataval['infile_id'] = $id;
				$dataval['status'] = 2;
				$insertedid = \App\Models\taskmanagerInfiles::insertDetails($dataval);
				$file = \App\Models\inFile::where('id',$id)->first();
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				$output.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
                <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$insertedid.'').'" class="fa fa-trash delete_attachments"></a>
                </p>';
				$i++;
			}
		}
		echo $output;
	}
	public function show_linked_completion_yearend(Request $request)
	{
		$ids = explode(",",$request->get('ids'));
		$task_id = $request->get('task_id');
		\App\Models\taskmanagerYearend::where('task_id',$task_id)->where('status',2)->delete();
		$output = 'Linked Yearend:<br/>';
		if(($ids))
		{
			$i = 1;
			foreach($ids as $id)
			{
				$dataval['task_id'] = $task_id;
				$dataval['setting_id'] = $id;
				$dataval['status'] = 2;
				$insertedid = \App\Models\taskmanagerYearend::insertDetails($dataval);
				$file = \App\Models\YearSetting::where('id',$id)->first();
				$get_client_id = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
				$year_client_id = $get_client_id->client_id;
				$yearend_id =\App\Models\YearClient::where('client_id',$year_client_id)->orderBy('id','desc')->first();
				$ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
				$output.='<p class="link_yearend_p">
							  <a href="'.$ele.'" target="_blank">'.$i.'</a>
                              <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                              <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$insertedid.'').'" class="fa fa-trash delete_attachments"></a>
                          </p>';
				$i++;
			}
		}
		echo $output;
	}
	public function create_new_taskmanager_task(Request $request)
	{
		$action_type = $request->get('action_type');
		$create_taskmanager_type = $request->get('hidden_create_taskmanager_type');
		if($action_type == "1")
		{
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$task_specifics = $request->get('task_specifics_content');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$accept_recurring = $request->get('accept_recurring');
			$user_rating = $request->get('hidden_star_rating_taskmanager');
			$title = '';
			if($clientid == "")
			{
				$task_details = \App\Models\timeTask::where('id', $task_type)->first();
				if(($task_details))
				{
				  $title = $task_details->task_name;
				}
			}
			else{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clientid)->first();
				if(($client_details))
				{
				  $title = $client_details->company.' ('.$clientid.')';
				}
			}
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['user_rating'] = $user_rating;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
			if($accept_recurring == "1")
			{
				$data['recurring_task'] = $recurring;
				$data['recurring_days'] = $days;
			}
			else{
				$data['recurring_task'] = 0;
				$data['recurring_days'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->CharSet = "UTF-8";
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			if($create_taskmanager_type == "infile" || $create_taskmanager_type == "pms"){
				echo json_encode(array('output' => $taskids.' - '.$subject_cls,'taskid' => $task_id));
			}
			elseif($create_taskmanager_type == "taskmanager"){
				$userid = Session::get('taskmanager_user');
				$get_output = get_taskmanager_layouts($task_id,$userid);
				echo json_encode(array('open_tasks_output' => $get_output['open_tasks_output'],'layout' => $get_output['layout'], 'message' => 'New Task has been successfully created! '.$taskids.' - '.$title.' - '.$subject_cls));
			}
			else{
				return redirect::back()->with('message', 'New Task has been successfully created! '.$taskids.' - '.$title.' - '.$subject_cls);
			}
		}
		else{
			$specific_type = $request->get('hidden_specific_type');
			$attachment_type = $request->get('hidden_attachment_type');
			$taskidval = $request->get('hidden_task_id_copy_task');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
			if($specific_type == "1")
			{
				$task_specifics = $task_details->task_specifics;
			}
			else{
				$task_specifics = $request->get('task_specifics_content');
			}
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$title = '';
			if($clientid == "")
			{
				$task_details = \App\Models\timeTask::where('id', $task_type)->first();
				if(($task_details))
				{
				  $title = $task_details->task_name;
				}
			}
			else{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clientid)->first();
				if(($client_details))
				{
				  $title = $client_details->company.' ('.$clientid.')';
				}
			}
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['recurring_task'] = $recurring;
			$data['recurring_days'] = $days;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			if($specific_type == "1")
			{
				$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
				$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->get();
				if(($specifics))
				{
					foreach($specifics as $specific)
					{
						$datacopyspec['task_id'] = $task_id;
						$datacopyspec['message'] = $specific->message;
						$datacopyspec['from_user'] = $specific->from_user;
						$datacopyspec['to_user'] = $specific->to_user;
						$datacopyspec['created_date'] = $specific->created_date;
						$datacopyspec['allocated_date'] = $specific->allocated_date;
						$datacopyspec['due_date'] = $specific->due_date;
						$datacopyspec['status'] = $specific->status;
						\App\Models\taskmanagerSpecifics::insert($datacopyspec);
						$auditdata['user_id'] = $specific->from_user;
						$auditdata['module'] = 'Task Manager';
						$auditdata['event'] = 'Task Created';
						$auditdata['reference'] = $task_id;
						$auditdata['updatetime'] = date('Y-m-d H:i:s');
						\App\Models\AuditTrails::insert($auditdata);
					}
				}
			}
			if($attachment_type == "1")
			{
				$copied_files = $request->get('copy_files');
				$copied_notes = $request->get('copy_notes');
				$copied_infiles = $request->get('copy_infiles');
				if(($copied_files))
				{
					foreach($copied_files as $file)
					{
						$detailsval = \App\Models\taskmanagerFiles::where('id',$file)->first();
						$datafile['task_id'] = $task_id;
						$datafile['url'] = $detailsval->url;
						$datafile['filename'] = $detailsval->filename;
						$datafile['status'] = $detailsval->status;
						\App\Models\taskmanagerFiles::insert($datafile);
					}
				}
				if(($copied_notes))
				{
					foreach($copied_notes as $note)
					{
						$detailsval = \App\Models\taskmanagerNotepad::where('id',$note)->first();
						$datanote['task_id'] = $task_id;
						$datanote['url'] = $detailsval->url;
						$datanote['filename'] = $detailsval->filename;
						$datanote['status'] = $detailsval->status;
						\App\Models\taskmanagerNotepad::insert($datanote);
					}
				}
				if(($copied_infiles))
				{
					foreach($copied_infiles as $infile)
					{
						$detailsval = \App\Models\taskmanagerInfiles::where('id',$infile)->first();
						$datainfile['task_id'] = $task_id;
						$datainfile['infile_id'] = $detailsval->infile_id;
						$datainfile['status'] = $detailsval->status;
						\App\Models\taskmanagerInfiles::insert($datainfile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			if($create_taskmanager_type == "infile"){
				echo json_encode(array('output' => $taskids.' - '.$subject_cls,'taskid' => $task_id));
			}
			elseif($create_taskmanager_type == "taskmanager"){
				$userid = Session::get('taskmanager_user');
				$get_output = get_taskmanager_layouts($task_id,$userid);
				echo json_encode(array('open_tasks_output' => $get_output['open_tasks_output'],'layout' => $get_output['layout']));
			}
			else{
				return redirect::back()->with('message', 'New Task has been successfully created! '.$taskids.' - '.$title.' - '.$subject_cls);
			}
		}
	}
	public function create_new_taskmanager_task_croard(Request $request)
	{
		$action_type = $request->get('action_type');
		if($action_type == "1")
		{
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$task_specifics = $request->get('task_specifics_content');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$accept_recurring = $request->get('accept_recurring');
			$user_rating = $request->get('hidden_star_rating_taskmanager');
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['user_rating'] = $user_rating;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
			if($accept_recurring == "1")
			{
				$data['recurring_task'] = $recurring;
				$data['recurring_days'] = $days;
			}
			else{
				$data['recurring_task'] = 0;
				$data['recurring_days'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$datacroard['task_id'] = $task_id;
			$datacroard['client_id'] = $clientid;
			$datacroard['status'] = 0;
			\App\Models\taskmanagerCroard::insert($datacroard);
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->CharSet = "UTF-8";
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			echo json_encode(array('output' => 'Task : '.$taskids.' - '.$subject_class));
			//return redirect::back()->with('message', 'Task Created successfully');
		}
		else{
			$specific_type = $request->get('hidden_specific_type');
			$attachment_type = $request->get('hidden_attachment_type');
			$taskidval = $request->get('hidden_task_id_copy_task');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
			if($specific_type == "1")
			{
				$task_specifics = $task_details->task_specifics;
			}
			else{
				$task_specifics = $request->get('task_specifics_content');
			}
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['recurring_task'] = $recurring;
			$data['recurring_days'] = $days;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			if($specific_type == "1")
			{
				$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
				$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->get();
				if(($specifics))
				{
					foreach($specifics as $specific)
					{
						$datacopyspec['task_id'] = $task_id;
						$datacopyspec['message'] = $specific->message;
						$datacopyspec['from_user'] = $specific->from_user;
						$datacopyspec['to_user'] = $specific->to_user;
						$datacopyspec['created_date'] = $specific->created_date;
						$datacopyspec['allocated_date'] = $specific->allocated_date;
						$datacopyspec['due_date'] = $specific->due_date;
						$datacopyspec['status'] = $specific->status;
						\App\Models\taskmanagerSpecifics::insert($datacopyspec);
						$auditdata['user_id'] = $specific->from_user;
						$auditdata['module'] = 'Task Manager';
						$auditdata['event'] = 'Task Created';
						$auditdata['reference'] = $task_id;
						$auditdata['updatetime'] = date('Y-m-d H:i:s');
						\App\Models\AuditTrails::insert($auditdata);
					}
				}
			}
			if($attachment_type == "1")
			{
				$copied_files = $request->get('copy_files');
				$copied_notes = $request->get('copy_notes');
				$copied_infiles = $request->get('copy_infiles');
				if(($copied_files))
				{
					foreach($copied_files as $file)
					{
						$detailsval = \App\Models\taskmanagerFiles::where('id',$file)->first();
						$datafile['task_id'] = $task_id;
						$datafile['url'] = $detailsval->url;
						$datafile['filename'] = $detailsval->filename;
						$datafile['status'] = $detailsval->status;
						\App\Models\taskmanagerFiles::insert($datafile);
					}
				}
				if(($copied_notes))
				{
					foreach($copied_notes as $note)
					{
						$detailsval = \App\Models\taskmanagerNotepad::where('id',$note)->first();
						$datanote['task_id'] = $task_id;
						$datanote['url'] = $detailsval->url;
						$datanote['filename'] = $detailsval->filename;
						$datanote['status'] = $detailsval->status;
						\App\Models\taskmanagerNotepad::insert($datanote);
					}
				}
				if(($copied_infiles))
				{
					foreach($copied_infiles as $infile)
					{
						$detailsval = \App\Models\taskmanagerInfiles::where('id',$infile)->first();
						$datainfile['task_id'] = $task_id;
						$datainfile['infile_id'] = $detailsval->infile_id;
						$datainfile['status'] = $detailsval->status;
						\App\Models\taskmanagerInfiles::insert($datainfile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->CharSet = "UTF-8";
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			//return redirect::back()->with('message', 'Task Created successfully');
			echo json_encode(array('output' => 'Task : '.$taskids.' - '.$subject_class));
		}
	}
	public function create_new_taskmanager_task_vat(Request $request)
	{
		$action_type = $request->get('action_type');
		$month = $request->get('hidden_vat_month');
		$vat_client = $request->get('hidden_vat_client_id');
		if($action_type == "1")
		{
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$task_specifics = $request->get('task_specifics_content');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$accept_recurring = $request->get('accept_recurring');
			$user_rating = $request->get('hidden_star_rating_taskmanager');
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['user_rating'] = $user_rating;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
			if($accept_recurring == "1")
			{
				$data['recurring_task'] = $recurring;
				$data['recurring_days'] = $days;
			}
			else{
				$data['recurring_task'] = 0;
				$data['recurring_days'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$datavat['task_id'] = $task_id;
			$datavat['client_id'] = $vat_client;
			$datavat['month'] = $month;
			$datavat['status'] = 0;
			\App\Models\taskmanagerVat::insert($datavat);
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;	
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->CharSet = "UTF-8";
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			echo json_encode(array('output' => 'Task : '.$taskids.' - '.$subject_class));
		}
		else{
			$specific_type = $request->get('hidden_specific_type');
			$attachment_type = $request->get('hidden_attachment_type');
			$taskidval = $request->get('hidden_task_id_copy_task');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
			if($specific_type == "1")
			{
				$task_specifics = $task_details->task_specifics;
			}
			else{
				$task_specifics = $request->get('task_specifics_content');
			}
			$author = $request->get('select_user');
			$author_email = $request->get('author_email');
			$creation_date = $request->get('created_date');
			$allocate_user = $request->get('allocate_user');
			$allocate_email = $request->get('allocate_email');
			$internal_checkbox = $request->get('internal_checkbox');
			$task_type = $request->get('idtask');
			$clientid = $request->get('clientid');
			$subject_class = $request->get('subject_class');
			$due_date = $request->get('due_date');
			$project = $request->get('select_project');
			$project_hours = $request->get('project_hours');
			$project_mins = $request->get('project_mins');
			$recurring = $request->get('recurring_checkbox');
			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}
			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = $request->get('specific_recurring'); }
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			$data['author'] = $author;
			$data['author_email'] = $author_email;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['allocate_email'] = $allocate_email;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['project_id'] = $project;
			$data['project_hours'] = $project_hours;
			$data['project_mins'] = $project_mins;
			$data['recurring_task'] = $recurring;
			$data['recurring_days'] = $days;
			$auto_close = $request->get('auto_close_task');
			$two_bill = $request->get('2_bill_task');
			if($auto_close == "1")
			{
				$data['auto_close'] = 1;
			}
			else{
				$data['auto_close'] = 0;
			}
			if($two_bill == "1")
			{
				$data['two_bill'] = 1;
			}
			else{
				$data['two_bill'] = 0;
			}
            $data['practice_code'] = Session::get('user_practice_code');
			$task_id = \App\Models\taskmanager::insertDetails($data);
			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;
			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }
				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];
					\App\Models\taskmanagerFiles::insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}
					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;
					\App\Models\taskmanagerNotepad::insert($dataval_notepad);
				}
			}
			$infiles = explode(",",$request->get('hidden_infiles_id'));
			if(($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						\App\Models\taskmanagerInfiles::insert($dataval_infile);
					}
				}
			}
			if($specific_type == "1")
			{
				$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
				$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->get();
				if(($specifics))
				{
					foreach($specifics as $specific)
					{
						$datacopyspec['task_id'] = $task_id;
						$datacopyspec['message'] = $specific->message;
						$datacopyspec['from_user'] = $specific->from_user;
						$datacopyspec['to_user'] = $specific->to_user;
						$datacopyspec['created_date'] = $specific->created_date;
						$datacopyspec['allocated_date'] = $specific->allocated_date;
						$datacopyspec['due_date'] = $specific->due_date;
						$datacopyspec['status'] = $specific->status;
						\App\Models\taskmanagerSpecifics::insert($datacopyspec);
						$auditdata['user_id'] = $specific->from_user;
						$auditdata['module'] = 'Task Manager';
						$auditdata['event'] = 'Task Created';
						$auditdata['reference'] = $task_id;
						$auditdata['updatetime'] = date('Y-m-d H:i:s');
						\App\Models\AuditTrails::insert($auditdata);
					}
				}
			}
			if($attachment_type == "1")
			{
				$copied_files = $request->get('copy_files');
				$copied_notes = $request->get('copy_notes');
				$copied_infiles = $request->get('copy_infiles');
				if(($copied_files))
				{
					foreach($copied_files as $file)
					{
						$detailsval = \App\Models\taskmanagerFiles::where('id',$file)->first();
						$datafile['task_id'] = $task_id;
						$datafile['url'] = $detailsval->url;
						$datafile['filename'] = $detailsval->filename;
						$datafile['status'] = $detailsval->status;
						\App\Models\taskmanagerFiles::insert($datafile);
					}
				}
				if(($copied_notes))
				{
					foreach($copied_notes as $note)
					{
						$detailsval = \App\Models\taskmanagerNotepad::where('id',$note)->first();
						$datanote['task_id'] = $task_id;
						$datanote['url'] = $detailsval->url;
						$datanote['filename'] = $detailsval->filename;
						$datanote['status'] = $detailsval->status;
						\App\Models\taskmanagerNotepad::insert($datanote);
					}
				}
				if(($copied_infiles))
				{
					foreach($copied_infiles as $infile)
					{
						$detailsval = \App\Models\taskmanagerInfiles::where('id',$infile)->first();
						$datainfile['task_id'] = $task_id;
						$datainfile['infile_id'] = $detailsval->infile_id;
						$datainfile['status'] = $detailsval->status;
						\App\Models\taskmanagerInfiles::insert($datainfile);
					}
				}
			}
			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
			$author = $author;
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = $allocate_email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;
				$author_email = $author_email;
				$allocated_email = '';
			}
			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;
			\App\Models\taskmanagerSpecifics::insert($data_specifics);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Task Created';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$task_specifics = strip_tags($task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				if(($author_email == "")) {
					$facilitydetails = DB::table('facility')->first();
					$aut_email = $facilitydetails->email;
					$aut_name = 'Admin';
				}
				else{
					$aut_email  = $author_email;
					$aut_name = $user_details->firstname.' '.$user_details->lastname;
				}
				$email = new PHPMailer();
				$email->CharSet = "UTF-8";
				$email->SetFrom($aut_email,$aut_name);
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}
			echo json_encode(array('output' => 'Task : '.$taskids.' - '.$subject_class));
		}
	}
	public function change_taskmanager_user(Request $request)
	{
		$user = $request->get('user');
		$userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $user)->first();
		if($userdetails) {
			$sessn=array('taskmanager_user' => $user);
			Session::put($sessn);

			$sessn=array('taskmanager_user_email' => $userdetails->email);
  			Session::put($sessn);
		}
		
	}
	public function change_auto_close_status(Request $request)
	{
		$taskid = $request->get('task_id');
		$data['auto_close'] = $request->get('status');
		\App\Models\taskmanager::where('id',$taskid)->update($data);
		$allocations = \App\Models\taskmanagerSpecifics::where('task_id',$taskid)->where('to_user','!=','')->where('status','<',3)->limit(1)->orderBy('id','desc')->first();
		if(($allocations))
		{
			$new_allocation = $allocations->from_user;
		}
		else{
			$new_allocation = '0';
		}
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskid)->first();
		if($task_details->auto_close == 1)
		{
			if((Session::get('taskmanager_user')) != $task_details->author)
			{
				if($task_details->author == $new_allocation)
				{
					$show_auto_close_msg = 1;
				}
				else{
					$show_auto_close_msg = 0;
				}
			}
			else{
				$show_auto_close_msg = 0;
			}
		}
		else{
			$show_auto_close_msg = 0;
		}
		echo $show_auto_close_msg;
	}
	public function delete_taskmanager_files(Request $request)
	{
		$file_id = $request->get('file_id');
		$get_details = \App\Models\taskmanagerFiles::where('id',$file_id)->first();
		\App\Models\taskmanagerFiles::where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function delete_taskmanager_notepad(Request $request)
	{
		$file_id = $request->get('file_id');
		$get_details = \App\Models\taskmanagerNotepad::where('id',$file_id)->first();
		\App\Models\taskmanagerNotepad::where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function delete_taskmanager_infiles(Request $request)
	{
		$file_id = $request->get('file_id');
		$get_details = \App\Models\taskmanagerInfiles::where('id',$file_id)->first();
		\App\Models\taskmanagerInfiles::where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function delete_taskmanager_yearend(Request $request)
	{
		$file_id = $request->get('file_id');
		$get_details = \App\Models\taskmanagerYearend::where('id',$file_id)->first();
		\App\Models\taskmanagerYearend::where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function taskmanager_change_due_date(Request $request)
	{
		$task_id = $request->get('task_id');
		$new_date = $request->get('new_date');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$old_due_date = date('d-M-Y',strtotime($task_details->due_date));
		$new_change_date = DateTime::createFromFormat('d-M-Y', $new_date);
		$new_change_date = $new_change_date->format('Y-m-d');
		$data['due_date'] = $new_change_date;
		\App\Models\taskmanager::where('id',$task_id)->update($data);
		$allocated = Session::get('taskmanager_user');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocated)->first();
		$message = '<spam style="color:#006bc7">****<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> has changed the due date of this task to <strong>'.$new_date.'</strong>****</spam>';
		$dataval['task_id'] = $task_id;
		$dataval['message'] = $message;
		$dataval['from_user'] = $allocated;
		$dataval['due_date'] = $new_change_date;
		$dataval['status'] = 3;
		\App\Models\taskmanagerSpecifics::insert($dataval);
		$auditdata['user_id'] = $allocated;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Due Date Changed';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
	    $date1=date_create(date('Y-m-d'));
	    $date2=date_create($new_change_date);
	    $diff=date_diff($date1,$date2);
	    $diffdays = $diff->format("%R%a");
	    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	    elseif($diffdays < 0) { $due_color = '#f00'; }
	    elseif($diffdays > 7) { $due_color = '#000'; }
	    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	    else{ $due_color = '#000'; }
	    if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task_details->author)
	    	{
	    		$dataupdate_spec_status['author_spec_status'] = 0;
				$dataupdate_spec_status['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
	    	}
	    	\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
	    }
	    if($task_details->avoid_email == "0")
	    {
	    	$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr(htmlspecialchars_decode($task_specifics_val),0,30);
	    	}
	    	else{
	    		$subject_cls = htmlspecialchars_decode($task_details->subject);
	    	}
	    	$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->author)->first();
	    	$author_email = $author_details->email;
	    	if($task_details->allocated_to != 0)
	    	{
	    		$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->allocated_to)->first();
	    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
	    		$allocated_email = $allocated_details->email;
	    	}
	    	else{
	    		$dataemail['allocated_name'] = $author_details->lastname.' '.$author_details->firstname;
	    		$allocated_email = '';
	    	}
			$task_specifics = '';
			$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
			$taskmanager_settings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();
			if(($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}
			$task_specifics = strip_tags($task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
	    	$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
			$dataemail['new_due_date'] = $new_date;
			$dataemail['old_due_date'] = $old_due_date;
			$subject_email = 'Task Manager: Due Date Change: '.$subject_cls;
			$contentmessage = view('emails/task_manager/task_manager_due_date_change', $dataemail)->render();
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$email = new PHPMailer();
			$email->CharSet = "UTF-8";
			$email->SetFrom($user_details->email, $user_details->firstname.' '.$user_details->lastname);
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			if($task_details->allocated_to != 0)
	    	{
				$email->AddCC($allocated_email);
			}
			$email->AddCC($taskmanager_settings->taskmanager_cc_email);
			$email->IsHTML(true);
			$email->AddAddress($author_email);
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		
	    }
		echo json_encode(array("new_date" => $new_date,"new_change_date" => $new_change_date, "color" => $due_color));
	}
	public function taskmanager_change_allocations(Request $request)
	{
		$task_id = $request->get('task_id');
		$type = $request->get('type');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		if($task_details->internal == 1)
	    {
	    	$task_specifics_email = 'Internal Task';
	    }
	    else{
	    	$client_id = $task_details->client_id;
	    	if($client_id == "") {
	    		$task_specifics_email = '';
	    	}
	    	else{
	    		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
	    		if(($client_details))
	    		{
	    			$task_specifics_email = $client_details->company.'-'.$client_id;
	    			$task_specifics_email = preg_replace('/[[:^print:]]/', '', $task_specifics_email);
	    		}
	    		else{
	    			$task_specifics_email = '';
	    		}
	    	}
	    }
		if($task_details->allocated_to == "" || $task_details->allocated_to == "0")
		{
			$from_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->author)->first();
			$from = $from_details->lastname.' '.$from_details->firstname;
			$data_specifics['from_user'] = $task_details->author;
		}
		else{
			$from_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->allocated_to)->first();
			$from = $from_details->lastname.' '.$from_details->firstname;
			$data_specifics['from_user'] = $task_details->allocated_to;
		}
		$new_allocation = $request->get('new_allocation');
		$data['allocated_to'] = $new_allocation;
		if($type == "1")
		{
			$data['status'] = 1;
			$data['progress'] = "100";
		}
		\App\Models\taskmanager::where('id',$task_id)->update($data);
		$to_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$new_allocation)->first();
		$to = $to_details->lastname.' '.$to_details->firstname;
		$message = '<spam style="color:#006bc7">++++<strong>'.$from.'</strong> has allocated this task to <strong>'.$to.'</strong> on <strong>'.date('d-M-Y').'</strong>++++</spam>';
		if($type == "0")
		{
			if(Session::has('taskmanager_user'))
		    {
		    	$sess_user = Session::get('taskmanager_user');
		    	if($sess_user == $task_details->author)
		    	{
		    		$dataupdate_spec_status['author_spec_status'] = 0;
					$dataupdate_spec_status['allocated_spec_status'] = 1;
		    	}
		    	else{
		    		$dataupdate_spec_status['author_spec_status'] = 1;
					$dataupdate_spec_status['allocated_spec_status'] = 0;
		    	}
		    	\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		    }
		}
		$data_specifics['task_id'] = $task_id;
		$data_specifics['message'] = $message;
		$data_specifics['to_user'] = $new_allocation;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['status'] = 2;
		\App\Models\taskmanagerSpecifics::insert($data_specifics);
		$auditdata['user_id'] = $new_allocation;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Task Allocated';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
		if($task_details->avoid_email == "0")
	    {
	    	$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr($task_specifics_val,0,30);
	    	}
	    	else{
	    		$subject_cls = $task_details->subject;
	    	}
	    	$allocated_person =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('taskmanager_user'))->first();
	    	$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->author)->first();
	    	$author_email = $author_details->email;
    		$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$new_allocation)->first();
    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
    		$allocated_email = $allocated_details->email;
    		$task_specifics = '';
			$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
			if(($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}
			$task_specifics = strip_tags($task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);

			$taskmanager_settings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();


	    	$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $allocated_person->lastname.' '.$allocated_person->firstname;
			$dataemail['due_date'] = $task_details->due_date;
			$dataemail['allocated_person'] = $allocated_details->lastname.' '.$allocated_details->firstname;
			$dataemail['allocation_date'] = date('d-M-Y');
			$subject_email = 'Task Manager: A Task has been allocated to you: '.$subject_cls.' ('.$task_specifics_email.')';
			$contentmessage = view('emails/task_manager/task_manager_allocation_change', $dataemail)->render();
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$aut_email = $allocated_person->email;
			$aut_name = $allocated_person->firstname.' '.$allocated_person->lastname;
			$email = new PHPMailer();
			$email->CharSet = "UTF-8";
			$email->SetFrom($aut_email,$aut_name);
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$email->AddCC($taskmanager_settings->taskmanager_cc_email);
			$email->IsHTML(true);
			$email->AddAddress( $allocated_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		
	    }
        $task_specifics_val = strip_tags($task_details->task_specifics);
        if($task_details->subject == "") { $subject = substr($task_specifics_val,0,30); }
        else{ $subject = $task_details->subject; }
        if($task_details->auto_close == 1)
        {
        	$close_task = 'auto_close_task_complete';
        }
        else{
        	$close_task = '';
        }
		$pval = '<p data-element="'.$task_details->id.'" data-subject="'.$subject.'" data-author="'.$task_details->author.'" data-allocated="'.$task_details->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task_details->id.' '.$close_task.'" title="Allocate User">'.$from.'->'.$to.'('.date('d-M-Y H:i').')</p>';
		$trval = '<tr><td colspan="2">'.$from.'->'.$to.'('.date('d-M-Y H:i').')</td></tr>';
		echo json_encode(array("pval" => $pval, 'trval' => $trval,'to' => $to));
	}
	public function show_existing_comments(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('id',$task_id)->first();
		$output = '';
		$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $task_details->task_specifics);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$output.= '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval1.'</strong>';
		if(($specifics_first))
		{
			$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specifics_first->message.'</strong>';
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specific->message);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval.'</strong>';
				}
			}
		}
		if(Session::has('taskmanager_user'))
		{
			$session_user = Session::get('taskmanager_user');
			if($task_details->author == $session_user)
			{
				$dataupdate_spec_status['author_spec_status'] = 0;
				if($task_details->author == $task_details->allocated_to)
				{
					$dataupdate_spec_status['allocated_spec_status'] = 0;
				}
			}
			else{
				$dataupdate_spec_status['allocated_spec_status'] = 0;
				if($task_details->author == $task_details->allocated_to)
				{
					$dataupdate_spec_status['author_spec_status'] = 0;
				}
			}
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		}
		$allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(1)->orderBy('id','desc')->first();
		if(($allocations))
		{
			$new_allocation = $allocations->from_user;
		}
		else{
			$new_allocation = '0';
		}
		if($task_details->auto_close == 1)
		{
			if((Session::get('taskmanager_user')) != $task_details->author)
			{
				if($task_details->author == $new_allocation)
				{
					$show_auto_close_msg = 1;
				}
				else{
					$show_auto_close_msg = 0;
				}
			}
			else{
				$show_auto_close_msg = 0;
			}
		}
		else{
			$show_auto_close_msg = 0;
		}
		$task_specifics_val = strip_tags($task_details->task_specifics);
		if($task_details->subject == "") { $subject = substr($task_specifics_val,0,30).' ('.$task_details->taskid.')'; }
	    else{ $subject = $task_details->subject.' ('.$task_details->taskid.')'; }
	    if($task_details->internal == 1)
	    {
	    	$task_specifics_name = 'Internal Task';
	    }
	    else{
	    	$client_id = $task_details->client_id;
	    	if($client_id == "") {
	    		$task_specifics_name = '';
	    	}
	    	else{
	    		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
	    		if(($client_details))
	    		{
	    			$task_specifics_name = $task_details->taskid.' '.$client_details->company.' '.$client_id;
	    		}
	    		else{
	    			$task_specifics_name = '';
	    		}
	    	}
	    }
	    $specifics =1;
	    $user_ratings = user_rating($task_details->id,$specifics);
		echo json_encode(array("output" => $output, "auto_close" => $task_details->auto_close, "show_auto_close_msg" => $show_auto_close_msg, 'title' => 'Title: '.$subject, "task_specifics_name" => $task_specifics_name, "two_bill" => $task_details->two_bill,'user_ratings' => $user_ratings));
	}
	public function add_comment_specifics(Request $request)
	{
		$comment = $request->get('comments');
		$task_id = $request->get('task_id');
		$user = Session::get('taskmanager_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has added the following comment to this Task on <strong>'.date('d M Y').'</strong>####</spam> <br/><spam style="font-weight:400">'.$comment.'</spam>';
		$specficsval = str_replace("&amp;", "&", $message);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$dataval['task_id'] = $task_id;
		$dataval['from_user'] = $user;
		$dataval['created_date'] = date('Y-m-d');
		$dataval['message'] = $specficsval;
		$dataval['status'] = 4;
		\App\Models\taskmanagerSpecifics::insert($dataval);
		$auditdata['user_id'] = $user;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Comments Added';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
		if(Session::has('taskmanager_user'))
		{
			$sess_user = Session::get('taskmanager_user');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
			if($sess_user == $task_details->author)
			{
				$dataupdate_spec_status['allocated_spec_status'] = 1;
			}
			elseif($sess_user == $task_details->allocated_to)
			{
				$dataupdate_spec_status['author_spec_status'] = 1;
			}
			else{
				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
			}
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		}
		echo '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval.'</strong>';
	}
	public function add_comment_and_allocate(Request $request)
	{
		$comment = $request->get('comments');
		$task_id = $request->get('task_id');
		$user = Session::get('taskmanager_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has added the following comment to this Task on <strong>'.date('d M Y').'</strong>####</spam> <br/><spam style="font-weight:400">'.$comment.'</spam>';
		$specficsval = str_replace("&amp;", "&", $message);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$dataval['task_id'] = $task_id;
		$dataval['from_user'] = $user;
		$dataval['created_date'] = date('Y-m-d');
		$dataval['message'] = $specficsval;
		$dataval['status'] = 4;
		\App\Models\taskmanagerSpecifics::insert($dataval);
		$auditdata['user_id'] = $user;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Comments Added';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
		if(Session::has('taskmanager_user'))
		{
			$sess_user = Session::get('taskmanager_user');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
			if($sess_user == $task_details->author)
			{
				$dataupdate_spec_status['allocated_spec_status'] = 1;
			}
			elseif($sess_user == $task_details->allocated_to)
			{
				$dataupdate_spec_status['author_spec_status'] = 1;
			}
			else{
				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
			}
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		}
		$allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(1)->orderBy('id','desc')->first();
		if(($allocations))
		{
			$new_allocation = $allocations->from_user;
		}
		else{
			$new_allocation = '0';
		}
		echo $new_allocation;
	}
	public function add_comment_and_allocate_to(Request $request)
	{
		$comment = $request->get('comments');
		$task_id = $request->get('task_id');
		$allocate_to = $request->get('allocate_to');
		$user = Session::get('taskmanager_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has added the following comment to this Task on <strong>'.date('d M Y').'</strong>####</spam> <br/><spam style="font-weight:400">'.$comment.'</spam>';
		$specficsval = str_replace("&amp;", "&", $message);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$dataval['task_id'] = $task_id;
		$dataval['from_user'] = $user;
		$dataval['created_date'] = date('Y-m-d');
		$dataval['message'] = $specficsval;
		$dataval['status'] = 4;
		\App\Models\taskmanagerSpecifics::insert($dataval);
		$auditdata['user_id'] = $user;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Comments Added';
		$auditdata['reference'] = $task_id;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
		if(Session::has('taskmanager_user'))
		{
			$sess_user = Session::get('taskmanager_user');
			$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
			$dataupdate_spec_status['author_spec_status'] = 1;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		}
		echo $allocate_to;
	}
	public function download_pdf_specifics(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		if($task_details->client_id == "")
		{
			$tasktype_details = \App\Models\timeTask::where('id', $task_details->task_type)->first();
			if(($tasktype_details))
			{
				$title = '<spam>'.$tasktype_details->task_name.'</spam>';
			}
			else{
				$title = '<spam></spam>';
			}
		}
		else{
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task_details->client_id)->first();
			if(($client_details))
			{
			  $title = $client_details->company.' ('.$task_details->client_id.')';
			}
			else{
			  $title = '';
			}
		}
		$output = '<p><strong>Task Specifics Extract for '.$task_details->taskid.', '.$title.', '.$task_details->subject.'</strong></p>';
		$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
		if(($specifics_first))
		{
			$output.='<p><strong style="width:100%;text-align:justify;font-weight:400">'.$specifics_first->message.'</strong></p>';
		}
		$output.= '<p>'.$task_details->task_specifics.'</p>';
		$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
		if(($specifics))
		{
			foreach($specifics as $specific)
			{
				$output.='<p><strong style="width:100%;text-align:justify;font-weight:400">'.$specific->message.'</strong></p>';;
			}
		}
		$pdffilename = 'task_specifics_'.$task_details->taskid.'.pdf';
		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('public/papers/'.$pdffilename.'');
	    echo $pdffilename;
	}
	public function show_all_allocations(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
		$output = '<table class="table">
		<thead>
		<tr>
			<td style="width:35%">Author</td>
			<td style="width:65%">'.$author_details->lastname.' '.$author_details->firstname.'</td>
		</tr>
		<tr>
			<td>Task Created On:</td>
			<td>'.date('d-M-Y', strtotime($task_details->creation_date)).'</td>
		</tr>
		<tr>
			<td>Task Subject:</td>
			<td>'.$task_details->subject.'</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">Allocations History:</td>
		</tr>
		<thead>
		<tbody>';
        $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->orderBy('id','asc')->get();
        if(($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
            $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
            if($allocate->status == "0")
            {
            	$date1=date_create($task_details->creation_date);
                $date2=date_create($allocate->allocated_date);
                $diff=date_diff($date1,$date2);
                $diffdays = $diff->format("%R%a");
            	$output.='<tr>
	            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> Task Closed on '.date('d-M-Y H:i', strtotime($allocate->allocated_date)).'</td>
	            </tr>
	            <tr>
	            	<td colspan="2">This task was open for '.$diffdays.' days</td>
	            </tr>';
            }
            else{
            	$output.='<tr>
	            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')</td>
	            </tr>';
            }
          }
        }
        $output.='</tbody></table>';
        echo $output;
	}
	public function download_pdf_history(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
		$output = '<table class="table">
		<tr>
			<td>Author</td>
			<td>'.$author_details->lastname.' '.$author_details->firstname.'</td>
		</tr>
		<tr>
			<td>Task Created On:</td>
			<td>'.date('d-M-Y', strtotime($task_details->creation_date)).'</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">Allocations History:</td>
		</tr>';
        $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
        if(($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
            $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
            $output.='<tr>
            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')</td>
            </tr>';
          }
        }
        $output.='</table>';
		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('public/papers/Task Allocation History.pdf');
	    echo 'Task Allocation History.pdf';
	}
	public function download_csv_history(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
		$file = fopen('public/papers/Task Allocation History.csv', 'w');
		$columns = array('TASK ALLOCATION HISTORY', '');
	    fputcsv($file, $columns);
	    $columns1 = array('', '');
	    fputcsv($file, $columns1);
	    $columns2 = array('Author', $author_details->lastname.' '.$author_details->firstname);
	    fputcsv($file, $columns2);
	    $columns3 = array('Task Created On:', date('d-M-Y', strtotime($task_details->creation_date)));
	    fputcsv($file, $columns3);
	    $columns4 = array('', '');
	    fputcsv($file, $columns4);
	    $columns5 = array('Allocations History:', '');
	    fputcsv($file, $columns5);
        $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
        if(($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
            $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
            $columns6 = array($fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')','');
	    	fputcsv($file, $columns6);
          }
        }
        fclose($file);
   	 	echo 'Task Allocation History.csv';
	}
	public function copy_task_details(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_specifics = $request->get('task_specifics');
		$task_files = $request->get('task_files');
		$spec_output = '';
		$attach_output = '';
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		if($task_specifics == "1")
		{
			$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
			if(($specifics_first))
			{
				$spec_output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specifics_first->message.'</strong><br/>';
			}
			$spec_output.= $task_details->task_specifics.'<br/>';
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$spec_output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specific->message.'</strong><br/>';
				}
			}
			$data['task_specifics_type'] = "1";
			$data['task_specifics'] = $spec_output;
		}
		else{
			$data['task_specifics_type'] = "2";
			$data['task_specifics'] = $task_details->task_specifics;
		}
		if($task_files == "1")
		{
			$files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',0)->get();
			$notepad = \App\Models\taskmanagerNotepad::where('task_id',$task_id)->where('status',0)->get();
			$infiles = \App\Models\taskmanagerInfiles::where('task_id',$task_id)->where('status',0)->get();
			$attach_output = '<h5 style="margin-top:20px">Attachments from Copied Task</h5>';
			if(($files))
			{
				foreach($files as $file)
				{
					$attach_output.='<input type="checkbox" name="copy_files[]" class="copy_files" id="file_'.$file->id.'" value="'.$file->id.'"><label for="file_'.$file->id.'">'.$file->filename.'</label><br/>';
				}
			}
			if(($notepad))
			{
				foreach($notepad as $note)
				{
					$attach_output.='<input type="checkbox" name="copy_notes[]" class="copy_notes" id="note_'.$note->id.'" value="'.$note->id.'"><label for="note_'.$note->id.'">'.$note->filename.'</label><br/>';
				}
			}
			if(($infiles))
			{
				$i = 1;
				$attach_output.='<p>Linked InFiles</p>';
				foreach($infiles as $infile)
				{
					$infile_details = \App\Models\inFile::where('id',$infile->infile_id)->first();
					$attach_output.='<input type="checkbox" name="copy_infiles" class="copy_infiles" id="infile_'.$infile->id.'" value="'.$infile->id.'"><label for="infile_'.$infile->id.'">'.$i.'&nbsp;&nbsp;'.date('d-M-Y', strtotime($infile_details->date_received)).'&nbsp;&nbsp;'.$infile_details->description.'</label><br/>';
					$i++;
				}
			}
			$data['task_attachment_type'] = "1";
			$data['attached_files'] = $attach_output;
		}
		else{
			$data['task_attachment_type'] = "2";
			$data['attached_files'] = '';
		}
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		if(($task_details))
		{
			$task_specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('status',"1")->orderBy('id','asc')->first();
			$data['creation_date'] = date('d-M-Y');
			$data['allocated_to'] = $task_specifics->to_user;
			$data['client_id'] = $task_details->client_id;
			if($task_details->client_id != "")
			{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$task_details->client_id)->first();
				$data['client_name'] = $client_details->company.' ('.$client_details->client_id.')';
			}
			else{
				$data['client_name'] = '';
			}
			$data['internal'] = $task_details->internal;
			$data['task_type'] = $task_details->task_type;
			$data['subject'] = $task_details->subject;
		}
		echo json_encode($data);
	}
	public function refresh_taskmanager(Request $request)
	{
		$user_id = $request->get('user_id');
		$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$user_id."' OR `author` = '".$user_id."' OR `allocated_to` = '0')");
		$open_tasks = '';
		$layout = '';
		if(($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	          if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          else{ $disabled = ''; $disabled_icon = ''; }
	          $taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task->id)->get();
	          $tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
	          $taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	          $taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	          $two_bill_icon = '';
	            if($task->two_bill == "1")
	            {
	              $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
	            }
	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
	            if(($task_details))
	            {
	            	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
	            }
	            else{
	            	$title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
	            if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
                }
                else{
                  $title = ''.$two_bill_icon;
                }
	          }
	          $author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }
	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          if(Session::has('taskmanager_user'))
              {
                if(Session::get('taskmanager_user') == $task->author) {
                    if(Session::get('taskmanager_user') == $task->allocated_to)
                    {
                      $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
                    }
                    else{
                      $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
                    }
                }
                else{ 
                    $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
                }
              }
              else{
                $author_cls = '';
                $hidden_author_cls = '';
              }
              if($task->auto_close == 1)
	            {
	              $close_task = 'auto_close_task_complete';
	            }
	            else{
	              $close_task = '';
	            }
	          $open_tasks.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
	            <td style="vertical-align: baseline;background: #E1E1E1;width:35%;padding:0px">';
                  $statusi = 0;
                  if(Session::has('taskmanager_user'))
                  {
                    if(Session::get('taskmanager_user') == $task->author) { 
                      if($task->author_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                    else{
                      if($task->allocated_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                  }
                  if($statusi == 0)
                  {
                    $open_tasks.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                  }
	                $open_tasks.='<table class="table">
	                <tr>
	                  <td style="width:25%;background:#E1E1E1;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
	                  <td style="width:75%;background:#E1E1E1">'.$title.'';
	                  if($task->recurring_task > 0)
	                  {
	                    $open_tasks.='<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
	                  }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Subject:</td>
	                  <td style="background: #E1E1E1">'.$subject.'</td>
	                </tr>
	                <tr>';
	                    $date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");
	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
	                  $open_tasks.='<td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Due Date:</td>
	                  <td style="background: #E1E1E1" class="'.$disabled_icon.'">
	                    <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
	                  </td>
	                </tr>
	                <tr>
                        <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Created:</td>
                        <td style="background: #E1E1E1">
                          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
                        </td>
                    </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                  <td style="background: #E1E1E1"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task files:</td>
	                  <td style="background: #E1E1E1">';
	                    $fileoutput = '';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                          <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                          </p>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                  <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
                      if($task->avoid_email == 0) {
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
                      else{
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Allocated to:</td>
	                  <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
	                    &nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
	                    &nbsp;
                        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" '.$disabled.' style="font-weight:800">
                        	<img src="'.URL::to('public/assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
                        </a>
	                  </td>
	                </tr>
	                <tr>
	                  <td colspan="2" id="allocation_history_div_'.$task->id.'">';
	                    $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                        $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User">';
	                          $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
	                          $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='</p>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	              <table class="table">
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td class="'.$disabled_icon.'">
	                    <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Insert Files"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;" title="Create Notes"></a>
	                    <a href="javascript:" class="fa fa-download faprogress_download_all '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Download All Progress FIles"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                    <div class="notepad_div_progress_notes" style="z-index:99999999999999 !important; min-height: 250px; height: auto; position:absolute; padding: 10px;">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label style="font-weight: normal;">Select User</label>
                                      <select class="form-control notepad_user">
                                        <option value="">Select User</option>';
                                        $selected = '';    
                                        $userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('practice',Session::get('user_practice_code'))->get();                  
                                        if(($userlist)){
                                          foreach ($userlist as $user) {
                                            if(Session::has('taskmanager_user'))
                                            {
                                              if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                                              else{ $selected = ''; }
                                            }
                                          $open_tasks.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.'&nbsp;'.$user->firstname.'</option>';
                                          }
                                        }
                                      $open_tasks.='</select>
                                      <spam class="error_notepad_user" style="color:#f00;"></spam>
                                    </div>
                                  </div>
                                  <div class="col-lg-12">
                                    <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents" style="height: 110px !important"></textarea>
                                    <spam class="error_files_notepad" style="color:#f00;"></spam>
                                    <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
                                    <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                  </div>
                                </div>
                              </div>
	                  </td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">';
	                    $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile '.$disabled.'" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #E1E1E1;width:15%">
	              <table class="table" style="margin-bottom: 105px;">
	                <tr>
	                  <td style="background:#E1E1E1" class="'.$disabled_icon.'">
		                  <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;">'.$task->taskid.'</spam>
                          <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
                          <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
                              </a> 
	                  </td>
	                </tr>
	                <tr>
	                  <td style="background:#E1E1E1">
	                  		<spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                              <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>
                              <div class="hidden" id="a1_'.$task->id.'">
                                <div class="popover-heading">
                                  Set Progress Percentage
                                </div>
                                <div class="popover-body">
                                  <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                                  <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                                </div>
                              </div>
                              <div class="progress progress_'.$task->id.'" style="width:60%;margin-bottom:5px">
                                <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                                  '.$task->progress.'%
                                </div>
                              </div>
	                  </td>
	                </tr>
	                <tr>
	                  <td class="last_td_display" style="background:#E1E1E1">';
	                    if($task->status == 1)
	                    {
	                      $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'" style="font-size:12px">Completed</a>';
	                    }
	                    elseif($task->status == 1)
	                    {
	                      if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'" style="font-size:12px">Activate</a>';
	                    }
	                    else{
	                    	if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'" style="font-size:12px">Park Task</a>';
	                    }
	                  $open_tasks.='<a href="javascript:" class="fa fa-files-o integrity_check_for_task common_black_button" data-element="'.$task->id.'" title="Integrity Check"></a></td>
	                </tr>
	                <tr>
	                  <td style="background:#E1E1E1" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Completion Files: <a href="javascript:" class="fa fa-download download_all_completion_files_taskmanager" data-element="'.$task->id.'" data-client="'.$task->client_id.'" title="Download all Completion Files"></a></spam><br/>
	                    <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-plus faplus '.$disabled.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>
	                      <a href="javascript:" class="yearend_link_completion '.$disabled.'" data-element="'.$task->id.'">Yearend</a>';
	                    }
	                    $get_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	                      $get_yearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	                      $idsval = '';
	                      $idsval_yearend = '';
	                      if(($get_infiles))
	                      {
	                      	foreach($get_infiles as $set_infile)
	                      	{
	                      		if($idsval == "")
	                      		{
	                      			$idsval = $set_infile->infile_id;
	                      		}
	                      		else{
	                      			$idsval = $idsval.','.$set_infile->infile_id;
	                      		}
	                      	}
	                      }
	                      if(($get_yearend))
	                      {
	                      	foreach($get_yearend as $set_yearend)
	                      	{
	                      		if($idsval_yearend == "")
	                      		{
	                      			$idsval_yearend = $set_yearend->setting_id;
	                      		}
	                      		else{
	                      			$idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
	                      		}
	                      	}
	                      }
	                    $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="'.$idsval.'">
	                    <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_'.$task->id.'" value="'.$idsval_yearend.'">
	                    <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>';
	                    $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles: <br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
                              if(($taskyearend))
                              {
                                $i=1;
                                  foreach($taskyearend as $yearend)
                                  {
                                    if($yearend->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
                                      $file = \App\Models\YearSetting::where('id',$yearend->setting_id)->first();
                                      $get_client_id = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task->id)->first();
                                      $year_client_id = $get_client_id->client_id;
                                      $yearend_id =\App\Models\YearClient::where('client_id',$year_client_id)->orderBy('id','desc')->first();
                                      $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
                                      $fileoutput.='<p class="link_yearend_p">
                                      <a href="'.$ele.'" target="_blank">'.$i.'</a>
                                      <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                                      <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                    <td style="background:#E1E1E1">
	                    </td>
	                  </tr>
	              </table>
	            </td>
	          </tr>
	          <tr class="empty_tr" style="background: #fff;height:10px">
	            <td style="padding:0px;background: #fff;"></td>
                <td colspan="3" style="background: #fff;height:10px"></td>
	          </tr>';
	          $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
                    <td>
                    	<table style="width:100%">
	                    	<tr>';
	                    	$statusi = 0;
			                  if(Session::has('taskmanager_user'))
			                  {
			                    if(Session::get('taskmanager_user') == $task->author) { 
			                      if($task->author_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                    else{
			                      if($task->allocated_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                  }
			                  if($statusi == 0)
			                  {
			                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
			                    $redlight_value = 0;
			                  }
	                    		$layout.= '
	                    		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
	                    		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
	                    		'.$redlight_indication_layout.'
	                    		</td>
	                    		<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
	                    		<td style="width:30%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'';
	                    			if($task->recurring_task > 0) {
	                                  $layout.= '<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
	                                }
	                    		$layout.= '</td>';
	                            if($task->client_id){
	                              $layout.='<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"><img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>';
	                            }else{
	                              $layout .= '<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"></td>';
	                            }
	                            $layout.='<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
		                            '.$two_bill_icon.'
		                        </td>
	                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td>
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:60%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.' / <spam class="allocated_sort_val">'.$allocated_to.'</spam></td>
		                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800">'.user_rating($task->id).'</td>
	                    	</tr>
                    	</table>
                    </td>
                    <td>
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
	                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
	                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    		</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
	                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
	                    		'.date('d-M-Y', strtotime($task->creation_date)).'
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td>
                    	<table style="width:100%">
	                    	<tr>';
	                            $project_time = '-';
	                            if($task->project_id != 0){
	                              $project_time = $task->project_hours.':'.$task->project_mins;
	                            }
	                            $layout.='<td style="width:25%;padding:10px; font-size:14px; font-weight:800;">'.$project_time.'</td>
			                    		<td class="layout_progress_'.$task->id.'" style="width:30%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
			                   </tr>
	                    </table>
                    </td>
                  </tr>';
	        }
	    }
	    else{
            $open_tasks.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }
        $outputlayout =$layout;
        if(Session::has('taskmanager_user'))
		{
			$sess_userid = Session::get('taskmanager_user');
			if($sess_userid == "")
			{
				$open_task_count = 0;
				$authored_task_count = 0;
			}
			else{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` != '".$sess_userid."' AND (`allocated_to` = '".$sess_userid."' OR `allocated_to` = '0')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$sess_userid."'");
			}
		}
		else{
			$open_task_count = 0;
			$authored_task_count = 0;
		}
		echo json_encode(array("open_tasks" => mb_convert_encoding($open_tasks,'UTF-8','UTF-8'),"layout" => mb_convert_encoding($outputlayout,'UTF-8','UTF-8'),"open_task_count" => ($open_task_count),"authored_task_count" => ($authored_task_count)));
	}
	public function refresh_parktask(Request $request)
	{
		$user_id = $request->get('user_id');
		$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$user_id."' OR `author` = '".$user_id."' OR `allocated_to` = '0')");
		$open_tasks = '';
		$layout = '';
		if(($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	          if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          else{ $disabled = ''; $disabled_icon = ''; }
	          $taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task->id)->get();
	          $tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
	          $taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	          $taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	          $two_bill_icon = '';
                    if($task->two_bill == "1")
                    {
                      $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                    }
	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
	            if(($task_details))
	            {
	            	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
	            }
	            else{
	            	$title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
	            if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
                }
                else{
                  $title = ''.$two_bill_icon;
                }
	          }
	          $author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }
	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          if(Session::has('taskmanager_user'))
              {
                $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr';
              }
              else{
                $author_cls = '';
                $hidden_author_cls = '';
              }
              if($task->auto_close == 1)
	            {
	              $close_task = 'auto_close_task_complete';
	            }
	            else{
	              $close_task = '';
	            }
	          $open_tasks.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
	            <td style="vertical-align: baseline;background: #E1E1E1;width:35%;padding:0px">';
                  $statusi = 0;
                  if(Session::has('taskmanager_user'))
                  {
                    if(Session::get('taskmanager_user') == $task->author) { 
                      if($task->author_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                    else{
                      if($task->allocated_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                  }
                  if($statusi == 0)
                  {
                    $open_tasks.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                  }
	                $open_tasks.='<table class="table">
	                <tr>
	                  <td style="width:25%;background:#E1E1E1;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
	                  <td style="width:75%;background:#E1E1E1">'.$title.'';
	                  if($task->recurring_task > 0)
	                  {
	                    $open_tasks.='<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
	                  }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Subject:</td>
	                  <td style="background: #E1E1E1">'.$subject.'</td>
	                </tr>
	                <tr>';
	                    $date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");
	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
	                  $open_tasks.='<td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Due Date:</td>
	                  <td style="background: #E1E1E1" class="'.$disabled_icon.'">
	                    <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
	                  </td>
	                </tr>
	                <tr>
                        <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Created:</td>
                        <td style="background: #E1E1E1">
                          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
                        </td>
                    </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                  <td style="background: #E1E1E1"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                </tr>
	                <tr>
	                  <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task files:</td>
	                  <td style="background: #E1E1E1">';
	                    $fileoutput = '';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                          <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                          </p>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                  <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
                      if($task->avoid_email == 0) {
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
                      else{
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Allocated to:</td>
	                  <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
	                    &nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
	                    &nbsp;
                        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" '.$disabled.' style="font-weight:800">
                        	<img src="'.URL::to('public/assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
                        </a>
	                  </td>
	                </tr>
	                <tr>
	                  <td colspan="2" id="allocation_history_div_'.$task->id.'">';
	                    $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                        $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User">';
	                          $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
	                          $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='</p>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	              <table class="table">
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td class="'.$disabled_icon.'">
	                    <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Insert Files"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;" title="Create Notes"></a>
	                    <a href="javascript:" class="fa fa-download faprogress_download_all '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Download All Progress FIles"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                    <div class="notepad_div_progress_notes" style="z-index:99999999999999 !important; min-height: 250px; height: auto; position:absolute; padding: 10px;">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label style="font-weight: normal;">Select User</label>
                                      <select class="form-control notepad_user">
                                        <option value="">Select User</option>';
                                        $selected = '';    
                                        $userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('practice',Session::get('user_practice_code'))->get();                  
                                        if(($userlist)){
                                          foreach ($userlist as $user) {
                                            if(Session::has('taskmanager_user'))
                                            {
                                              if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                                              else{ $selected = ''; }
                                            }
                                          $open_tasks.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.'&nbsp;'.$user->firstname.'</option>';
                                          }
                                        }
                                      $open_tasks.='</select>
                                      <spam class="error_notepad_user" style="color:#f00;"></spam>
                                    </div>
                                  </div>
                                  <div class="col-lg-12">
                                    <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents" style="height: 110px !important"></textarea>
                                    <spam class="error_files_notepad" style="color:#f00;"></spam>
                                    <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
                                    <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                  </div>
                                </div>
                              </div>
	                  </td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">';
	                    $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile '.$disabled.'" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #E1E1E1;width:15%">
	              <table class="table" style="margin-bottom: 105px;">
	                <tr>
	                  <td style="background:#E1E1E1" class="'.$disabled_icon.'">
		                  <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;">'.$task->taskid.'</spam>
                          <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
                          <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
                              </a> 
	                  </td>
	                </tr>
	                <tr>
	                  <td style="background:#E1E1E1">
	                  		<spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                              <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>
                              <div class="hidden" id="a1_'.$task->id.'">
                                <div class="popover-heading">
                                  Set Progress Percentage
                                </div>
                                <div class="popover-body">
                                  <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                                  <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                                </div>
                              </div>
                              <div class="progress progress_'.$task->id.'" style="width:60%;margin-bottom:5px">
                                <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                                  '.$task->progress.'%
                                </div>
                              </div>
	                  </td>
	                </tr>
	                <tr>
	                  <td class="last_td_display" style="background:#E1E1E1">';
	                    if($task->status == 1)
	                    {
	                      $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'" style="font-size:12px">Completed</a>';
	                    }
	                    elseif($task->status == 1)
	                    {
	                      if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'" style="font-size:12px">Activate</a>';
	                    }
	                    else{
	                    	if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'" style="font-size:12px">Park Task</a>';
	                    }
	                  $open_tasks.='<a href="javascript:" class="fa fa-files-o integrity_check_for_task common_black_button" data-element="'.$task->id.'" title="Integrity Check"></a></td>
	                </tr>
	                <tr>
	                  <td style="background:#E1E1E1" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Completion Files: <a href="javascript:" class="fa fa-download download_all_completion_files_taskmanager" data-element="'.$task->id.'" data-client="'.$task->client_id.'" title="Download all Completion Files"></a></spam><br/>
	                    <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-plus faplus '.$disabled.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>
	                      <a href="javascript:" class="yearend_link_completion '.$disabled.'" data-element="'.$task->id.'">Yearend</a>';
	                    }
	                    $get_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	                      $get_yearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	                      $idsval = '';
	                      $idsval_yearend = '';
	                      if(($get_infiles))
	                      {
	                      	foreach($get_infiles as $set_infile)
	                      	{
	                      		if($idsval == "")
	                      		{
	                      			$idsval = $set_infile->infile_id;
	                      		}
	                      		else{
	                      			$idsval = $idsval.','.$set_infile->infile_id;
	                      		}
	                      	}
	                      }
	                      if(($get_yearend))
	                      {
	                      	foreach($get_yearend as $set_yearend)
	                      	{
	                      		if($idsval_yearend == "")
	                      		{
	                      			$idsval_yearend = $set_yearend->setting_id;
	                      		}
	                      		else{
	                      			$idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
	                      		}
	                      	}
	                      }
	                    $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="'.$idsval.'">
	                    <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_'.$task->id.'" value="'.$idsval_yearend.'">
	                    <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>';
	                    $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles: <br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
                              if(($taskyearend))
                              {
                                $i=1;
                                  foreach($taskyearend as $yearend)
                                  {
                                    if($yearend->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
                                      $file = \App\Models\YearSetting::where('id',$yearend->setting_id)->first();
                                      $get_client_id = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task->id)->first();
                                      $year_client_id = $get_client_id->client_id;
                                      $yearend_id =\App\Models\YearClient::where('client_id',$year_client_id)->orderBy('id','desc')->first();
                                      $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
                                      $fileoutput.='<p class="link_yearend_p">
                                      <a href="'.$ele.'" target="_blank">'.$i.'</a>
                                      <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                                      <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                    <td style="background:#E1E1E1">
	                    </td>
	                  </tr>
	              </table>
	            </td>
	          </tr>
	          <tr class="empty_tr" style="background: #fff;height:10px">
	            <td style="padding:0px;background: #fff;"></td>
                <td colspan="3" style="background: #fff;height:10px"></td>
	          </tr>';
	          $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
                    <td>
                    	<table style="width:100%">
	                    	<tr>';
	                    	$statusi = 0;
			                  if(Session::has('taskmanager_user'))
			                  {
			                    if(Session::get('taskmanager_user') == $task->author) { 
			                      if($task->author_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                    else{
			                      if($task->allocated_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                  }
			                  if($statusi == 0)
			                  {
			                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
			                    $redlight_value = 0;
			                  }
	                    		$layout.= '
	                    		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
	                    		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
	                    		'.$redlight_indication_layout.'
	                    		</td>
	                    		<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
	                    		<td style="width:30%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'';
	                    			if($task->recurring_task > 0) {
	                                  $layout.= '<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
	                                }
	                    		$layout.= '</td>';
	                            if($task->client_id){
	                              $layout.='<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"><img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>';
	                            }else{
	                              $layout .= '<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"></td>';
	                            }
	                            $layout.='td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
		                            '.$two_bill_icon.'
		                        </td>
	                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td>
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:60%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.' / <spam class="allocated_sort_val">'.$allocated_to.'</spam></td>
		                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800">'.user_rating($task->id).'</td>
	                    	</tr>
                    	</table>
                    </td>
                    <td>
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
	                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
	                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    		</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
	                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
	                    		'.date('d-M-Y', strtotime($task->creation_date)).'
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td>
                    	<table style="width:100%" >
	                    	<tr>';
	                            $project_time = '-';
	                            if($task->project_id != 0){
	                              $project_time = $task->project_hours.':'.$task->project_mins;
	                            }
	                            $layout.='<td style="width:25%;padding:10px; font-size:14px; font-weight:800;">'.$project_time.'</td>
			                    		<td class="layout_progress_'.$task->id.'" style="width:30%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
			                   </tr>
	                    </table>
                    </td>
                  </tr>';
	        }
	    }
	    else{
            $open_tasks.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }
        $outputlayout =$layout;
        if(Session::has('taskmanager_user'))
		{
			$sess_userid = Session::get('taskmanager_user');
			if($sess_userid == "")
			{
				$open_task_count = 0;
				$authored_task_count = 0;
			}
			else{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` != '".$sess_userid."' AND (`allocated_to` = '".$sess_userid."' OR `allocated_to` = '0')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$sess_userid."'");
			}
		}
		else{
			$open_task_count = 0;
			$authored_task_count = 0;
		}
		echo json_encode(array("open_tasks" => $open_tasks,"layout" => $outputlayout,"open_task_count" => ($open_task_count),"authored_task_count" => ($authored_task_count)));
	}
	public function taskmanager_mark_complete(Request $request)
	{
		$taskidval = $request->get('task_id');
		$type = $request->get('type');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
		if(($task_details))
		{
			$author = $task_details->author;
			$allocate_user = Session::get('taskmanager_user');
			if($author == $allocate_user)
			{
				$data['status'] = 1;
				$data['progress'] = "100";
				\App\Models\taskmanager::where('id',$taskidval)->update($data);
				$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
				$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">~~~The Task was closed on <strong>'.date('d-M-Y').'</strong> by <strong>'.$allocated_details->lastname.' '.$allocated_details->firstname.'</strong>~~~</spam>';
				$dataupdate_spec_status['author_spec_status'] = 0;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
				\App\Models\taskmanager::where('id',$taskidval)->update($dataupdate_spec_status);
				$data_specifics['from_user'] = $author;
				$data_specifics['to_user'] = $author;
				$data_specifics['task_id'] = $taskidval;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 0;
				\App\Models\taskmanagerSpecifics::insert($data_specifics);
				$auditdata['user_id'] = $author;
				$auditdata['module'] = 'Task Manager';
				$auditdata['event'] = 'Task Mark Complete';
				$auditdata['reference'] = $taskidval;
				$auditdata['updatetime'] = date('Y-m-d H:i:s');
				\App\Models\AuditTrails::insert($auditdata);
				if($task_details->recurring_task > 0)
				{
					if($task_details->recurring_task == 1)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 1 month'));
						$creation_date = date('Y-m-d', strtotime($creation_date. ' + 1 days'));
					}
					elseif($task_details->recurring_task == 2)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 7 days'));
					}
					elseif($task_details->recurring_task == 3)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 1 days'));
					}
					else
					{
						$days = $task_details->recurring_days;
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + '.$days.' days'));
					}
					$from_date=date_create($task_details->creation_date);
					$to_date=date_create($task_details->due_date);
					$diff=date_diff($from_date,$to_date);
					$difference = $diff->format("%R%a");
					if($difference != 0) {
						$due_date = date('Y-m-d', strtotime($creation_date. ' + '.$difference.' days'));
					} else {
						$due_date = date('Y-m-d', strtotime($creation_date));
					}
					$data['author'] = $task_details->author;
					$data['creation_date'] = $creation_date;
					$data['allocated_to'] = $task_details->allocated_to;
					$data['internal'] = $task_details->internal;
					$data['task_type'] = $task_details->task_type;
					$data['client_id'] = $task_details->client_id;
					$data['subject'] = $task_details->subject;
					$data['task_specifics'] = $task_details->task_specifics;
					$data['due_date'] = $due_date;
					$data['recurring_task'] = $task_details->recurring_task;
					$data['recurring_days'] = $task_details->recurring_days;
					$data['author_spec_status'] = 1;
					$data['allocated_spec_status'] = 1;
					$data['status'] = 0;
					$data['progress'] = "0";
                    $data['practice_code'] = Session::get('user_practice_code');
					$task_id = \App\Models\taskmanager::insertDetails($data);
					$taskids = 'A'.sprintf("%04d", $task_id);
					$dataupdate['taskid'] = $taskids;
					\App\Models\taskmanager::where('id',$task_id)->update($dataupdate);
					if($task_details->retain_specifics == 1)
					{
						$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->get();
						if(($specifics))
						{
							foreach($specifics as $specific)
							{
								$datacopyspec['task_id'] = $task_id;
								$datacopyspec['message'] = $specific->message;
								$datacopyspec['from_user'] = $specific->from_user;
								$datacopyspec['to_user'] = $specific->to_user;
								$datacopyspec['created_date'] = $specific->created_date;
								$datacopyspec['allocated_date'] = $specific->allocated_date;
								$datacopyspec['due_date'] = $specific->due_date;
								$datacopyspec['status'] = $specific->status;
								\App\Models\taskmanagerSpecifics::insert($datacopyspec);
								$auditdata['user_id'] = $specific->from_user;
								$auditdata['module'] = 'Task Manager';
								$auditdata['event'] = 'Task Mark Complete';
								$auditdata['reference'] = $task_id;
								$auditdata['updatetime'] = date('Y-m-d H:i:s');
								\App\Models\AuditTrails::insert($auditdata);
							}
						}
					}
					if($task_details->retain_files == 1)
					{
						$files = \App\Models\taskmanagerFiles::where('task_id',$taskidval)->where('status',0)->get();
						if(($files))
						{
							foreach($files as $file)
							{
								$dataval['task_id'] = $task_id;
				     			$dataval['url'] = $file->url;
								$dataval['filename'] = $file->filename;
								$dataval['status'] = $file->status;
								\App\Models\taskmanagerFiles::insert($dataval);
							}
						}
						$notes = \App\Models\taskmanagerNotepad::where('task_id',$taskidval)->where('status',0)->get();
						if(($notes))
						{
							foreach($notes as $note)
							{
								$dataval['task_id'] = $task_id;
				     			$dataval['url'] = $note->url;
								$dataval['filename'] = $note->filename;
								$dataval['status'] = $note->status;
								\App\Models\taskmanagerNotepad::insert($dataval);
							}
						}
						$infiles = \App\Models\taskmanagerInfiles::where('task_id',$taskidval)->where('status',0)->get();
						if(($infiles))
						{
							foreach($infiles as $infile)
							{
								$dataval['task_id'] = $task_id;
								$dataval['infile_id'] = $infile->infile_id;
								$dataval['status'] = $infile->status;
								\App\Models\taskmanagerInfiles::insert($dataval);
							}
						}
					}
				}
			}
			else{
				if($type == "1")
				{
					$data['status'] = 1;
					$data['progress'] = "100";
				}
				$data['allocated_to'] = $author;
				\App\Models\taskmanager::where('id',$taskidval)->update($data);
				$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
				$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">@@@<strong>'.$allocated_details->lastname.' '.$allocated_details->firstname.'</strong> has stated they have Finished with the task on <strong>'.date('d-M-Y').'</strong> and reallocated it to <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong>@@@</spam>';
				$data_specifics['from_user'] = $allocate_user;
				$data_specifics['to_user'] = $author;
				$data_specifics['task_id'] = $taskidval;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 2;
				\App\Models\taskmanagerSpecifics::insert($data_specifics);
				$auditdata['user_id'] = $allocate_user;
				$auditdata['module'] = 'Task Manager';
				$auditdata['event'] = 'Task Complete';
				$auditdata['reference'] = $taskidval;
				$auditdata['updatetime'] = date('Y-m-d H:i:s');
				\App\Models\AuditTrails::insert($auditdata);
				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
				\App\Models\taskmanager::where('id',$taskidval)->update($dataupdate_spec_status);
				if($task_details->avoid_email == "0")
			    {
			    	$task_specifics_val = strip_tags($task_details->task_specifics);
			    	if($task_details->subject == "")
			    	{
			    		$subject_cls = substr($task_specifics_val,0,30);
			    	}
			    	else{
			    		$subject_cls = $task_details->subject;
			    	}
			    	$allocated_person =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('taskmanager_user'))->first();
			    	$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->author)->first();
			    	$author_email = $author_details->email;
		    		$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->author)->first();
		    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
		    		$allocated_email = $allocated_details->email;
		    		$task_specifics = '';
					$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->orderBy('id','asc')->first();
					if(($specifics_first))
					{
						$task_specifics.=$specifics_first->message;
					}
					$task_specifics.= PHP_EOL.$task_details->task_specifics;
					$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$taskidval)->where('id','!=',$specifics_first->id)->get();
					if(($specifics))
					{
						foreach($specifics as $specific)
						{
							$task_specifics.=PHP_EOL.$specific->message;
						}
					}
					$task_specifics = strip_tags($task_specifics);
					$uploads = 'public/papers/task_specifics.txt';
					$myfile = fopen($uploads, "w") or die("Unable to open file!");
					fwrite($myfile, $task_specifics);
					fclose($myfile);
			    	$dataemail['logo'] = getEmailLogo('taskmanager');
					$dataemail['subject'] = $subject_cls;
					$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
					$dataemail['due_date'] = $task_details->due_date;
					$dataemail['allocated_person'] = $allocated_person->lastname.' '.$allocated_person->firstname;
					$dataemail['allocation_date'] = date('d-M-Y');
					if($task_details->internal == 1)
				    {
				    	$task_specifics_email = 'Internal Task';
				    }
				    else{
				    	$client_id = $task_details->client_id;
				    	if($client_id == "") {
				    		$task_specifics_email = '';
				    	}
				    	else{
				    		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
				    		if(($client_details))
				    		{
				    			$task_specifics_email = $client_details->company.'-'.$client_id;
				    			$task_specifics_email = preg_replace('/[[:^print:]]/', '', $task_specifics_email);
				    		}
				    		else{
				    			$task_specifics_email = '';
				    		}
				    	}
				    }
					$subject_email = 'Task Manager: A Task has been allocated to you: '.$subject_cls.' ('.$task_specifics_email.')';
					$contentmessage = view('emails/task_manager/task_manager_allocation_change', $dataemail)->render();
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$allocated_email = $allocated_person->email;
					$aut_email = $user_details->email;
					$allocated_name = $allocated_person->firstname.' '.$allocated_person->lastname;
					$email = new PHPMailer();
					$email->CharSet = "UTF-8";
					$email->SetFrom($allocated_email,$allocated_name);
					$email->Subject   = $subject_email;
					$email->Body      = $contentmessage;
					
					$taskmanager_settings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();
					$email->AddCC($taskmanager_settings->taskmanager_cc_email);
					$email->IsHTML(true);
					$email->AddAddress( $aut_email );
					$email->AddAttachment( $uploads , 'task_specifics.txt' );		$email->Send();		
			    }
			}
			$title = '';
			if($task_details->client_id == "")
			{
				$tasktype_details = \App\Models\timeTask::where('id', $task_details->task_type)->first();
				if(($tasktype_details))
				{
				  $title = $tasktype_details->task_name;
				}
			}
			else{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task_details->client_id)->first();
				if(($client_details))
				{
				  $title = $client_details->company.' ('.$task_details->client_id.')';
				}
			}
			$task_specifics_val = strip_tags($task_details->task_specifics);
			if($task_details->subject == "") { $subject = substr($task_specifics_val,0,30); }
	        else{ $subject = $task_details->subject; }
			echo 'Task Marked Complete: A'.$taskidval.' - '.$title.' - '.$subject.'';
		}
	}
	public function taskmanager_mark_incomplete(Request $request)
	{
		$taskidval = $request->get('task_id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskidval)->first();
		if(($task_details))
		{
			$data['status'] = 0;
			\App\Models\taskmanager::where('id',$taskidval)->update($data);
		}
	}
	public function search_taskmanager_task(Request $request)
	{
		$author = $request->get('author');
		$open_task = $request->get('open_task');
		$client_id = $request->get('client_id');
		$subject = $request->get('subject');
		$recurring = $request->get('recurring');
		$due_date = $request->get('due_date');
		$creation_date = $request->get('creation_date');
		$make_internal = $request->get('make_internal');
		$select_tasks = $request->get('select_tasks');
		$allocated_to_val = Session::get('taskmanager_user');
		$query = '`practice_code` = "'.Session::get('user_practice_code').'"';
		if($author != ""){ 
		    if($query == "") {
		        $query.= "`author` = '".$author."'";
		    }
		    else {
		        $query.= " AND `author` = '".$author."'";
		    }
		}
		if($open_task == "0"){ 
			if($query == "") { $query.= "(`status` = '1' OR `status` = '2')"; } else { $query.= " AND (`status` = '1' OR `status` = '2')"; }
		}
		elseif($open_task == "1")
		{
			if($query == "") { $query.= "(`status` = '0' OR `status` = '2')"; } else { $query.= " AND (`status` = '0' OR `status` = '2')"; }
		}
		else{
		}
		if($make_internal == "0")
		{
			if($client_id != ""){ if($query == "") { $query.= "`client_id` = '".$client_id."'"; } else { $query.= " AND `client_id` = '".$client_id."'"; } }
		}
		else{
			if($select_tasks != ""){ if($query == "") { $query.= "`task_type` = '".$select_tasks."'"; } else { $query.= " AND `task_type` = '".$select_tasks."'"; } }
		}
		if($recurring != "0"){ if($query == "") { $query.= "`recurring_task` > '0'"; } else { $query.= " AND `recurring_task` > '0'"; } }
		else{ if($query == "") { $query.= "`recurring_task` = '0'"; } else { $query.= " AND `recurring_task` = '0'"; } }
		if($due_date != ""){
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			if($query == "") { $query.= "`due_date` = '".$due_date_change."'"; } else { $query.= " AND `due_date` = '".$due_date_change."'"; } 
		}
		if($creation_date != ""){ 
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			if($query == "") { $query.= "`creation_date` = '".$creation_date_change."'"; } else { $query.= " AND `creation_date` = '".$creation_date_change."'"; }
		}
		if($subject != "") { 
			if($query == "") { $query.= "`subject` LIKE '%".$subject."%' OR SUBSTR(`task_specifics`,0,30) LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%'"; } 
			else { $query.= " AND (`subject` LIKE '%".$subject."%' OR SUBSTR(`task_specifics`,0,30) LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%')"; } 
		}
		$query = "SELECT * FROM `taskmanager` WHERE ".$query."";
		$user_tasks = DB::select($query);
		$count_user_tasks = count($user_tasks);
		$open_tasks = '';
		$layout = '';
		if(($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	        	if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          	else{ 
	          		if($allocated_to_val == $task->allocated_to) { $disabled = ''; $disabled_icon = ''; }
		        	elseif($allocated_to_val == $task->author) { $disabled = ''; $disabled_icon = ''; }
		        	else{ $disabled = 'cant_edit_task'; $disabled_icon = 'cant_edit_task_tr'; }
	          	}
				$taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task->id)->get();
				$tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
				$taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
				$taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
				$two_bill_icon = '';
                if($task->two_bill == "1")
                {
                  $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                }
				if($task->client_id == "")
				{
					$title_lable = 'Task Name:';
					$task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
					if(($task_details))
					{
						$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
					}
					else{
						$title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
					}
				}
				else{
					$title_lable = 'Client:';
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
					if(($client_details))
					{
					  $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
					}
					else{
					  $title = ''.$two_bill_icon;
					}
				}
				if(Session::has('taskmanager_user'))
				{
					if(Session::get('taskmanager_user') == $task->author) {
					    if(Session::get('taskmanager_user') == $task->allocated_to)
					    {
					      $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
					    }
					    else{
					      $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
					    }
					}
					else{ 
					    $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
					}
				}
				else{
					$author_cls = '';
					$hidden_author_cls = '';
				}
		        if($task->auto_close == 1)
	            {
	              $close_task = 'auto_close_task_complete';
	            }
	            else{
	              $close_task = '';
	            }
				$author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
				$task_specifics_val = strip_tags($task->task_specifics);
				if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
				else{ $subject = $task->subject; }
				if($task->allocated_to == 0) { $allocated_to = ''; }
				else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
				$open_tasks.='<tr class="tasks_tr" id="task_tr_'.$task->id.'">
				<td style="vertical-align: baseline;background: #E1E1E1;width:35%;padding:0px">
				  <table class="table">
				    <tr>
				      <td style="width:25%;background:#E1E1E1;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
				      <td style="width:75%;background:#E1E1E1">'.$title.'';
					  if($task->client_id != ""){
						$open_tasks.='<img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />';
					  }
				      if($task->recurring_task > 0)
				      {
				        $open_tasks.='<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
				      }
				      $open_tasks.='</td>
				    </tr>
				    <tr>
				      <td style="background:#E1E1E1;font-weight:700;text-decoration: underline;">Subject:</td>
				      <td style="background:#E1E1E1">'.$subject.'</td>
				    </tr>
				    <tr>';
				        $date1=date_create(date('Y-m-d'));
				        $date2=date_create($task->due_date);
				        $diff=date_diff($date1,$date2);
				        $diffdays = $diff->format("%R%a");
				        if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
				        elseif($diffdays < 0) { $due_color = '#f00'; }
				        elseif($diffdays > 7) { $due_color = '#000'; }
				        elseif($diffdays <= 7) { $due_color = '#00a91d'; }
				        else{ $due_color = '#000'; }
				      $open_tasks.='<td style="background:#E1E1E1;font-weight:700;text-decoration: underline;">Due Date:</td>
				      <td style="background:#E1E1E1" class="'.$disabled_icon.'">
				        <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
				        <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
				      </td>
				    </tr>
				    <tr>
				        <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Created:</td>
				        <td style="background: #E1E1E1">
				          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
				        </td>
				    </tr>';
				    if($task->status == 1)
				    {
				    	$task_spec_closed = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->orderBy('id','desc')->first();
				    	if(($task_spec_closed))
				    	{
				    		$open_tasks.='<tr>
				                <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Closed:</td>
				                <td style="background: #E1E1E1">
				                  <spam>'.date('d-M-Y', strtotime($task_spec_closed->allocated_date)).'</spam>
				                </td>
				            </tr>';
				    	}
				    	else{
				    		$open_tasks.='<tr>
				                <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Closed:</td>
				                <td style="background: #E1E1E1">
				                  <spam></spam>
				                </td>
				            </tr>';
				    	}
				    }
				    $open_tasks.='<tr>
				      <td style="background:#E1E1E1;font-weight:700;text-decoration: underline;">Task Specifics:</td>
				      <td style="background:#E1E1E1"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'" data-clientid="'.$task->client_id.'">'.substr($task_specifics_val,0,30).'...</a></td>
				    </tr>
				    <tr>
				      <td style="background:#E1E1E1;font-weight:700;text-decoration: underline;">Task files:</td>
				      <td style="background:#E1E1E1">';
				        $fileoutput = '';
				        if(($taskfiles))
				        {
				          foreach($taskfiles as $file)
				          {
				            if($file->status == 0)
				            {
				              $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        if(($tasknotepad))
				        {
				          foreach($tasknotepad as $note)
				          {
				            if($note->status == 0)
				            {
				              $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        if(($taskinfiles))
				        {
				          $i=1;
				          foreach($taskinfiles as $infile)
				          {
				            if($infile->status == 0)
				            {
				              if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
				              $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
				              if(($file))
				              {
				              	$ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
				                  $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
				                  <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
				                  </p>';
				                  $i++;
				              }
				            }
				          }
				        }
				        $open_tasks.=$fileoutput;
				      $open_tasks.='</td>
				    </tr>
				  </table>
				</td>
				<td style="vertical-align: baseline;background: #E8E8E8;width:30%">
				  <table class="table">
				    <tr>
				      <td style="width:25%;font-weight:700;text-decoration: underline;background: #E8E8E8">Author:</td>
				      <td style="width:75%;background: #E8E8E8">'.$author->lastname.' '.$author->firstname.'';
				      if($task->avoid_email == 0) {
				        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
				      }
				      else{
				        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
				      }
				      $open_tasks.='</td>
				    </tr>
				    <tr>
				      <td style="background: #E8E8E8"><spam style="font-weight:700;text-decoration: underline;">Allocated to:</spam></td>
				      <td style="background: #E8E8E8" id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
				    </tr>
				    <tr>
				      <td colspan="2" style="background: #E8E8E8">
				        <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
				        <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
				        &nbsp;
				        <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
				        &nbsp;
				        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' title="Request Update" style="font-weight:800">
				        	<img src="'.URL::to('public/assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'" class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
				        </a>
				      </td>
				    </tr>
				    <tr>
				      <td style="background: #E8E8E8" colspan="2" id="allocation_history_div_'.$task->id.'">';
				        $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
				        $output = '';
				        if(($allocations))
				        {
				          foreach($allocations as $allocate)
				          {
				            $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User">';
				              $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
				              $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
				              $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
				            $output.='</p>';
				          }
				        }
				        $open_tasks.=$output;
				      $open_tasks.='</td>
				    </tr>
				  </table>
				</td>
				<td style="vertical-align: baseline;background: #F0F0F0;width:20%">
				  <table class="table">
				    <tr>
				      <td style="background: #F0F0F0"><spam style="font-weight:700;text-decoration: underline;">Progress Files:</spam></td>
				      <td style="background: #F0F0F0"></td>
				    </tr>
				    <tr>
				      <td style="background: #F0F0F0" class="'.$disabled_icon.'">
				        <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Insert Files"></a>
				        <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;" title="Create Notes"></a>
				        <a href="javascript:" class="fa fa-download faprogress_download_all '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Download All Progress FIles"></a>';
				        if($task->client_id != "")
				        {
				          $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
				        }
				        $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
				        <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
				        <div class="notepad_div_progress_notes" style="z-index:99999999999999 !important; min-height: 250px; height: auto; position:absolute; padding: 10px;">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label style="font-weight: normal;">Select User</label>
                                      <select class="form-control notepad_user">
                                        <option value="">Select User</option>';
                                        $selected = '';    
                                        $userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('practice',Session::get('user_practice_code'))->get();                  
                                        if(($userlist)){
                                          foreach ($userlist as $user) {
                                            if(Session::has('taskmanager_user'))
                                            {
                                              if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                                              else{ $selected = ''; }
                                            }
                                          $open_tasks.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.'&nbsp;'.$user->firstname.'</option>';
                                          }
                                        }
                                      $open_tasks.='</select>
                                      <spam class="error_notepad_user" style="color:#f00;"></spam>
                                    </div>
                                  </div>
                                  <div class="col-lg-12">
                                    <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents" style="height: 110px !important"></textarea>
                                    <spam class="error_files_notepad" style="color:#f00;"></spam>
                                    <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
                                    <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                  </div>
                                </div>
                              </div>
				      </td>
				      <td style="background: #F0F0F0"></td>
				    </tr>
				    <tr>
				      <td style="background: #F0F0F0" colspan="2">';
				        $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
				        if(($taskfiles))
				        {
				          foreach($taskfiles as $file)
				          {
				            if($file->status == 1)
				            {
				              $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        $fileoutput.='</div>';
				        $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
				        if(($tasknotepad))
				        {
				          foreach($tasknotepad as $note)
				          {
				            if($note->status == 1)
				            {
				              $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        $fileoutput.='</div>';
				        $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
				        if(($taskinfiles))
				        {
				          $i=1;
				            foreach($taskinfiles as $infile)
				            {
				              if($infile->status == 1)
				              {
				                if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
				                $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
				                if(($file))
				                {
				                	$ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
				                    $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
				                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
				                    </p>';
				                    $i++;
				                }
				              }
				            }
				        }
				        $fileoutput.='</div>';
				        $open_tasks.= $fileoutput;
						$view_task_url=URL::to('user/view_taskmanager_task/'.$task->id);
				      $open_tasks.='</td>
				    </tr>
				  </table>
				</td>
				<td style="vertical-align: baseline;background: #F8F8F8;width:15%">
				  <table class="table" style="margin-bottom: 105px;">
				    <tr>
				      <td style="background:#F8F8F8" class="'.$disabled_icon.'">
				          <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;">'.$task->taskid.'</spam>
				          <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
				          <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
				              </a> 							  
							<a href="'.$view_task_url.'" target="_blank" class="fa fa-expand view_full_task" data-element="<?php echo $task->id; ?>" 
							  title="View Task" style="padding:5px;font-size:20px;font-weight: 800;float: right">
				      </td>
				    </tr>
				    <tr>
				      <td style="background:#F8F8F8">
				      		<spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
				              <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>
				              <div class="hidden" id="a1_'.$task->id.'">
				                <div class="popover-heading">
				                  Set Progress Percentage
				                </div>
				                <div class="popover-body">
				                  <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
				                  <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
				                </div>
				              </div>
				              <div class="progress progress_'.$task->id.'" style="width:60%;margin-bottom:5px">
				                <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
				                  '.$task->progress.'%
				                </div>
				              </div>
				      </td>
				    </tr>
				    <tr>
				      <td class="last_td_display" style="background:#F8F8F8">';
				        if($task->status == 1)
				        {
				          $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'" style="font-size:12px">Completed</a>';
				        }
				        elseif($task->status == 2)
				        {
				          if(Session::has('taskmanager_user'))
				            {
				                $allocated_person = Session::get('taskmanager_user');
				                if($task->author == $allocated_person)
				                {
				                  $complete_button = 'mark_as_complete_author';
				                }
				                else{
				                  $complete_button = 'mark_as_complete';
				                }
				            }
				            else{
				                $complete_button = 'mark_as_complete';
				            }
				          $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.' '.$disabled.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
				          <a href="javascript:" class="common_black_button activate_task_button '.$disabled.'" data-element="'.$task->id.'" style="font-size:12px">Activate</a>';
				        }
				        else{
				        	if(Session::has('taskmanager_user'))
				            {
				                $allocated_person = Session::get('taskmanager_user');
				                if($task->author == $allocated_person)
				                {
				                  $complete_button = 'mark_as_complete_author';
				                }
				                else{
				                  $complete_button = 'mark_as_complete';
				                }
				            }
				            else{
				                $complete_button = 'mark_as_complete';
				            }
				          $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.' '.$disabled.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
				          <a href="javascript:" class="common_black_button park_task_button '.$disabled.'" data-element="'.$task->id.'" style="font-size:12px">Park Task</a>';
				        }
				      $open_tasks.='<a href="javascript:" class="fa fa-files-o integrity_check_for_task common_black_button" data-element="'.$task->id.'" title="Integrity Check"></a></td>
				    </tr>
				    <tr>
				      <td style="background:#F8F8F8" class="'.$disabled_icon.'">
				        <spam style="font-weight:700;text-decoration: underline;">Completion Files: <a href="javascript:" class="fa fa-download download_all_completion_files_taskmanager" data-element="'.$task->id.'" data-client="'.$task->client_id.'" title="Download all Completion Files"></a></spam><br/>
				        <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
				        <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
				        if($task->client_id != "")
				        {
				          $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>
				          <a href="javascript:" class="yearend_link_completion '.$disabled.'" data-element="'.$task->id.'">Yearend</a>';
				        }
				        $get_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
				          $get_yearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
				          $idsval = '';
				          $idsval_yearend = '';
				          if(($get_infiles))
				          {
				          	foreach($get_infiles as $set_infile)
				          	{
				          		if($idsval == "")
				          		{
				          			$idsval = $set_infile->infile_id;
				          		}
				          		else{
				          			$idsval = $idsval.','.$set_infile->infile_id;
				          		}
				          	}
				          }
				          if(($get_yearend))
				          {
				          	foreach($get_yearend as $set_yearend)
				          	{
				          		if($idsval_yearend == "")
				          		{
				          			$idsval_yearend = $set_yearend->setting_id;
				          		}
				          		else{
				          			$idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
				          		}
				          	}
				          }
				        $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
				        <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="'.$idsval.'">
				        <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_'.$task->id.'" value="'.$idsval_yearend.'">
				        <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
				          <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
				          <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
				          <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
				          <spam class="error_files_notepad"></spam>
				        </div>';
				        $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
				        if(($taskfiles))
				        {
				          foreach($taskfiles as $file)
				          {
				            if($file->status == 2)
				            {
				              $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        $fileoutput.='</div>';
				        $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
				        if(($tasknotepad))
				        {
				          foreach($tasknotepad as $note)
				          {
				            if($note->status == 2)
				            {
				              $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
				            }
				          }
				        }
				        $fileoutput.='</div>';
				        $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
				        if(($taskinfiles))
				        {
				          $i=1;
				            foreach($taskinfiles as $infile)
				            {
				              if($infile->status == 2)
				              {
				                if($i == 1) { $fileoutput.='Linked Infiles: <br/>'; }
				                $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
				                $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
				                $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target=_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>
				                <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
				                </p>';
				                $i++;
				              }
				            }
				        }
				        $fileoutput.='</div>';
				        $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
				              if(($taskyearend))
				              {
				                $i=1;
				                  foreach($taskyearend as $yearend)
				                  {
				                    if($yearend->status == 2)
				                    {
				                      if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
				                      $file = \App\Models\YearSetting::where('id',$yearend->setting_id)->first();
				                      $get_client_id = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task->id)->first();
				                      $year_client_id = $get_client_id->client_id;
				                      $yearend_id =\App\Models\YearClient::where('client_id',$year_client_id)->orderBy('id','desc')->first();
				                      $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
				                      $fileoutput.='<p class="link_yearend_p">
				                      <a href="'.$ele.'" target="_blank">'.$i.'</a>
				                      <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
				                      <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
				                      </p>';
				                      $i++;
				                    }
				                  }
				              }
				              $fileoutput.='</div>';
				        $open_tasks.= $fileoutput;
				      $open_tasks.='</td>
				    </tr>
				    <tr>
				        <td style="background:#F8F8F8">
				        </td>
				      </tr>
				  </table>
				</td>
				</tr>
				<tr class="empty_tr" style="background: #fff;height:10px">
					<td style="padding:0px;background: #fff;"></td>
					<td colspan="3" style="background: #fff;height:10px"></td>
				</tr>';
				$layout.= '<tr class="hidden_tasks_tr" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
					<td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
						<table style="width:100%">
					    	<tr>';
					    	$statusi = 0;
					    		$layout.= '
					    		<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
					    		<td style="width:35%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'';
					    			if($task->recurring_task > 0) {
	                                  $layout.= '<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
	                                }
					    		$layout.= '</td>';
	                            if($task->client_id){
	                              $layout.='<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"><img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>';
	                            }else{
	                              $layout .= '<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"></td>';
	                            }
	                            $layout.='<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
					                '.$two_bill_icon.'
					            </td>
					    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
					    	</tr>
					    </table>
					</td>
					<td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
						<table style="width:100%">
					    	<tr>
					    		<td style="width:60%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.' / <spam class="allocated_sort_val">'.$allocated_to.'</spam></td>
		                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800">'.user_rating($task->id).'</td>
					    	</tr>
						</table>
					</td>
					<td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
						<table style="width:100%">
					    	<tr>
					    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
					    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
					    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
					    		</td>
					    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
					    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
					    		'.date('d-M-Y', strtotime($task->creation_date)).'
					    		</td>
					    	</tr>
					    </table>
					</td>
					<td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
						<table style="width:100%">
					    	<tr>
	                    		';
	                            $project_time = '-';
	                            if($task->project_hours != ''){
	                              $project_time = $task->project_hours.':'.$task->project_mins;
	                            }
	                            $layout.='<td style="width:25%;padding:10px; font-size:14px; font-weight:800;">'.$project_time.'</td>
			                    		<td class="layout_progress_'.$task->id.'" style="width:30%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
			                   </tr>
					    </table>
					</td>
				</tr>';
	        }
	    }
	    else{
            $open_tasks.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }
		$outputlayout =$layout;
		echo json_encode(array("open_tasks" => mb_convert_encoding($open_tasks,'UTF-8','UTF-8'),"layout" => mb_convert_encoding($outputlayout,'UTF-8','UTF-8'), 'count_rows'=>$count_user_tasks));
	}
	public function update_taskmanager_details(Request $request)
	{
		$task_id = $request->get('task_id');
		$author = $request->get('author');
		$allocated = $request->get('allocated');
		$specifics = $request->get('specifics');
		$files = $request->get('files');
		$recurring = $request->get('recurring');
		$days = $request->get('days');
		$specific_recurring = $request->get('specific_recurring');
		$task_type = $request->get('task_type');
		$subject = $request->get('subject');
		$project_id = $request->get('project_id');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
		$author_email = $user_details->email;
		$data['author'] = $author;
		$data['author_email'] = $author_email;
		$data['allocated_to'] = $allocated;
		$data['retain_specifics'] = $specifics;
		$data['retain_files'] = $files;
		$data['subject'] = $subject;
		$data['project_id'] = $project_id;
		if($task_type == "0")
		{
			$data['task_type'] = 0;
			$dataval['task_name_val'] = '';
		}
		else{
			$data['task_type'] = $task_type;
			$task_details = \App\Models\timeTask::where('id', $task_type)->first();
            if(($task_details))
            {
              $title = $task_details->task_name;
            }
            else{
              $title = '';
            }
			$dataval['task_name_val'] = $title;
		}
		if($recurring == "1")
		{
			$data['recurring_task'] = $days;
			$dataval['recurring_task'] = 'YES';
			if($days == "1") { $data['recurring_days'] = "30"; $dataval['recurring_days'] = 'Monthly'; }
			elseif($days == "2") { $data['recurring_days'] = "7"; $dataval['recurring_days'] = 'Weekly'; }
			elseif($days == "3") { $data['recurring_days'] = "1"; $dataval['recurring_days'] = 'Daily'; }
			else { $data['recurring_days'] = $specific_recurring; $dataval['recurring_days'] = 'Specific'; }
		}
		else{
			$data['recurring_task'] = 0;
			$dataval['recurring_task'] = 'NO';
			$dataval['recurring_days'] = 'Specific';
		}
		\App\Models\taskmanager::where('id',$task_id)->update($data);
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$project_name = '';
		if($task_details->project_id != 0)
		{
			$details = \App\Models\projects::where('project_id',$task_details->project_id)->first();
			$project_name = $details->project_name;
		}
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
		if(($user_details))
		{
			$author = $user_details->lastname.' '.$user_details->firstname;
		}
		else{
			$author = '';
		}
		$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocated)->first();
		if(($allocated_details))
		{
			$allocated = $allocated_details->lastname.' '.$allocated_details->firstname;
		}
		else{
			$allocated = '';
		}
		if($specifics == "1") { $specificsval = '<i class="fa fa-check"></i>'; }
		else { $specificsval = ''; }
		if($files == "1") { $filesval = '<i class="fa fa-check"></i>'; }
		else { $filesval = ''; }
		$dataval['author'] = $author;
		$dataval['allocated'] = $allocated;
		$dataval['retain_spec'] = $specificsval;
		$dataval['retain_files'] = $filesval;
		$dataval['task_type'] = $task_type;
		$dataval['subject'] = $subject;
		$dataval['project'] = $project_name;
		echo json_encode($dataval);
	}
	public function show_more_tasks(Request $request)
	{
		$page_no = $request->get('page_no');
		$offset = $page_no * 500;
		$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->offset($offset)->limit(500)->get();
		$outputtask = '';
          if(($tasks))
          {
            foreach($tasks as $task)
            {
              $two_bill_icon = '';
	            if($task->two_bill == "1")
	            {
	              $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
	            }
              if($task->client_id == "")
              {
                $title_lable = 'Task Name:';
                $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
                if(($task_details))
                {
                  $title = $task_details->task_name;
                  $tasktitle = $task_details->task_name;
                  $internaltask = 'yes';
                }
                else{
                  $title = '';
                  $tasktitle = '';
                  $internaltask = '';
                }
              }
              else{
                $title_lable = 'Client:';
                $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                  $tasktitle = '';
                  $internaltask = '';
                }
                else{
                  $title = '';
                  $tasktitle = '';
                  $internaltask = '';
                }
              }
              if($task->allocated_to != 0)
              {
                $allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first();
                $allocated_to = $allocated_details->lastname.' '.$allocated_details->firstname;
              }
              else{
                $allocated_to = '-';
              }
              if($task->author != 0)
              {
                $author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
                $author_to = $author_details->lastname.' '.$author_details->firstname;
              }
              else{
                $author_to = '-';
              }
              if($task->status == 1) { $color = 'color:#f00'; $tr_status= 'tr_closed'; } 
              else { $color = ''; $tr_status= ''; }
              if($task->recurring_days == "30"){ $recurring_days = 'Monthly'; }
              elseif($task->recurring_days == "7"){ $recurring_days = 'Weekly'; }
              elseif($task->recurring_days == "1"){ $recurring_days = 'Daily'; }
              else{ $recurring_days = 'Specific'; }
              if($task->recurring_task == "0"){ $recurring_task = 'NO'; }
              else{ $recurring_task = 'YES'; }
              if($task->retain_specifics == "0"){ $retain_specifics = '-'; }
              else{ $retain_specifics = '<i class="fa fa-check"></i>'; }
              if($task->retain_files == "0"){ $retain_files = '-'; }
              else{ $retain_files = '<i class="fa fa-check"></i>'; }
              $project_name = '';
              if($task->project_id != 0)
              {
                $details = \App\Models\projects::where('project_id',$task->project_id)->first();
                $project_name = $details->project_name;
              }
              $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$tr_status.'">
                <td class="taskid_td" style="'.$color.'">'.$task->taskid.'</td>
                <td class="taskid_td task_name_val" style="'.$color.'">'.$title.' '.$two_bill_icon.'</td>
                <td class="author_td" style="'.$color.'">'.$author_to.'</td>
                <td class="allocated_td" style="'.$color.'">'.$allocated_to.'</td>
                <td class="" style="'.$color.';text-align:center">';
                  if($task->status == 2)
                  {
                    $outputtask.='<i class="fa fa-pause" aria-hidden="true"></i><br/>'.date('d-M-Y',strtotime($task->park_date)).'';
                  }
                  else{
                    $outputtask.='-';
                  }
                $outputtask.='</td>
                <td class="retain_spec_td" style="'.$color.'">'.$retain_specifics.'</td>
                <td class="retain_files_td" style="'.$color.'">'.$retain_files.'</td>
                <td class="subject_td" style="'.$color.'">'.$task->subject.'</td>
                <td class="project_td" style="'.$color.'">'.$project_name.'</td>
                <td class="recurring_days_td" style="'.$color.'">'.$recurring_days.'</td>
                <td class="recurring_task_td" style="'.$color.'">'.$recurring_task.'</td>
                <td class="due_date_td" style="'.$color.'">'.$task->due_date.'</td>
                <td style="'.$color.'">
                  <a href="javascript:" class="fa fa-download download_pdf_task" data-element="'.$task->id.'" title="Download PDF"></a>
                  <a href="javascript:" class="fa fa-edit edit_task edit_task_'.$task->id.'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'" data-specifics="'.$task->retain_specifics.'" data-files="'.$task->retain_files.'" data-recurring="'.$task->recurring_task.'" data-projectid="'.$task->project_id.'" title="EDIT TASK"></a>
                </td>
              </tr>';
            }
          }
          echo $outputtask;
	}
	public function download_taskmanager_task_pdf(Request $request)
	{
		$task_id = $request->get('task_id');
		$task = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$open_tasks = '';
	          $taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task->id)->get();
	          $tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
	          $taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	          $taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
	            if(($task_details))
	            {
	            	$title = $task_details->task_name;
	            }
	            else{
	            	$title = '';
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
	            if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
	          }
	          $author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }
	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          $spec_output = '<p>'.trim($task->task_specifics).'</p>';
				$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->get();
				if(($specifics))
				{
					foreach($specifics as $specific)
					{
						$spec_output.='<p style="font-weight:400">'.trim($specific->message).'</p>';
					}
				}
	          $open_tasks.='
	          <div style="width:100%;border:1px solid #000">
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">'.$title_lable.'</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$title.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Author:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$author->lastname.' '.$author->firstname.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Allocated to:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$allocated_to.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Task ID:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$task->taskid.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Subject:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$subject.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">';
	          			$date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");
	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
			          	$open_tasks.='<div style="width:20%">Due Date:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px;color:'.$due_color.' !important;font-weight:800">'.date('d-M-Y', strtotime($task->due_date)).'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Task Specifics:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.trim($spec_output).'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
	                  <div style="width:20%">Task files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput = '';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'<br/>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">
	                    Allocations:
	                  </div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                          $fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
	                          $touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='<br/>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">Progress Files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput ='';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'
	                            <br/>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">Completion Files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput ='';
	                    if(($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles: <br/>'; }
	                            $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'
	                            <br/>';
	                            $i++;
	                          }
	                        }
	                    }
	                    if(($taskyearend))
	                    {
	                      $i=1;
	                        foreach($taskyearend as $yearend)
	                        {
	                          if($yearend->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
	                            $file = \App\Models\YearSetting::where('id',$yearend->setting_id)->first();
	                              $get_client_id = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
	                              $year_client_id = $get_client_id->client_id;
	                              $yearend_id =\App\Models\YearClient::where('client_id',$year_client_id)->orderBy('id','desc')->first();
	                              $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
	                            $fileoutput.=$i.'&nbsp;'.$file->document.'
	                            <br/>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</div>
	                </div>
	          </div>
	          		';
	              $filename = $title.'.pdf';
	              $pdf = PDF::loadHTML($open_tasks);
				    $pdf->setPaper('A4', 'portrait');
				    $pdf->save('public/papers/'.$filename.'');
				    echo $filename;
	}
	public function view_taskmanager_task($task_id = null)
	{
		$task = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$time_task=\App\Models\timeTask::where('id', $task->task_type)->first();
		$taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task_id)->get();
		$author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
		$allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)
			->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
		$output = '';
		if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
		else{ $disabled = ''; $disabled_icon = ''; }
		if($task->auto_close == 1)
		{
			$close_task = 'auto_close_task_complete';
		}
		else{
			$close_task = '';
		}
		if(($allocations))
		{
			foreach($allocations as $allocate)
			{
				$output.='<p data-element="'.$task->id.'" data-subject="'.$task->subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.' '.$close_task.'" title="Allocate User">';
				$fromuser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
				$touser =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
				$output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
				$output.='</p>';
			}
		}
		$task_specific = '';
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $task_details->task_specifics);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$task_specific.= '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval1.'</strong>';
		if(($specifics_first))
		{
			$task_specific.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specifics_first->message.'</strong>';
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specific->message);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$task_specific.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval.'</strong>';
				}
			}
		}
		$specifics =1;
	    $user_ratings = user_rating($task_details->id,$specifics);
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$allocations1 = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(1)->orderBy('id','desc')->first();
		if(($allocations1))
		{
			$new_allocation = $allocations1->from_user;
		}
		else{
			$new_allocation = '0';
		}
		if($task_details->auto_close == 1)
		{
			if((Session::get('taskmanager_user')) != $task_details->author)
			{
				if($task_details->author == $new_allocation)
				{
					$show_auto_close_msg = 1;
				}
				else{
					$show_auto_close_msg = 0;
				}
			}
			else{
				$show_auto_close_msg = 0;
			}
		}
		else{
			$show_auto_close_msg = 0;
		}
		// dd($task_specific);
		// dd($author);
		$tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
		$taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
		$taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
		// dd($time_task);
		$client_id = $task->client_id;
		$mmi_icons='';
		if($client_id != ''){
			$encode_client_id = base64_encode($client_id);
			$year = \App\Models\YearEndYear::where('status', 0)->orderBy('year','desc')->first();
			$year_clients =\App\Models\YearClient::where('year', $year->year)->where('client_id',$client_id)->first(); 
			$prevdate = date("Y-m-05", strtotime("first day of -1 months"));
			$prev_date2 = date('Y-m', strtotime($prevdate));
			$mmi_icons = '<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/ta_allocation?client_id='.$client_id).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-clock-o" title="TA System" aria-hidden="true"></i>
			</a></div> 
			<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/rct_client_manager/'.$client_id.'?active_month='.$prev_date2).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-university" title="RCT Manager" aria-hidden="true"></i>
			</a></div>  
			<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/client_management?client_id='.$client_id).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-users" title="Client Management System" aria-hidden="true"></i>
			</a></div>      
			<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/client_request_manager/'.$encode_client_id).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-envelope" title="Clinet Request System" aria-hidden="true"></i>
			</a></div>      
			<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/infile_search?client_id='.$client_id).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-file-archive-o" title="Infile" aria-hidden="true"></i>
			</a></div>    
			<div style="display:inline-flex;"><a class="quick_links" href="'.URL::to('user/key_docs?client_id='.$client_id).'" style="padding:10px; text-decoration:none;">
			<i class="fa fa-key" title="Keydocs" aria-hidden="true"></i>
			</a></div>';
			if($year_clients){
				$mmi_icons .= '<div style="display:inline-flex;"><a class="quick_links yearend_link" href="'.URL::to('user/yearend_individualclient/'.base64_encode($year_clients->id)).'" style="padding:10px; text-decoration:none;">
				<i class="fa fa-calendar" title="Yearend Manager" aria-hidden="true"></i>
				</a></div>';
			}
		}
		return view('user.task_manager.view_task_manage')->with('title','Bubble - Task Manager')->with('task',$task)->with('time_task',$time_task)
			->with('taskfiles',$taskfiles)->with('author',$author)->with('output',$output)->with('task_specific',$task_specific)
			->with('user_ratings',$user_ratings)->with('user',$user)->with("auto_close", $task_details->auto_close)
			->with("show_auto_close_msg", $show_auto_close_msg)->with('tasknotepad',$tasknotepad)->with('taskinfiles',$taskinfiles)
			->with('taskyearend',$taskyearend)->with('mmi_icons',$mmi_icons);
	}
	public function set_progress_value(Request $request)
	{
		$task_id = $request->get('task_id');
		$value = $request->get('value');
		$data['progress'] = $value;
		\App\Models\taskmanager::where('id',$task_id)->update($data);
	}
	public function set_avoid_email_taskmanager(Request $request)
	{
		$task_id = $request->get('task_id');
		$status = $request->get('status');
		$data['avoid_email'] = $status;
		\App\Models\taskmanager::where('id',$task_id)->update($data);
	}
	public function get_task_redline_notification(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
			$ids = '';
			if(($user_tasks))
			{
				foreach($user_tasks as $task)
				{
					if($task->author == $userid)
					{
						if($task->author_spec_status == "1")
						{
							if($ids == "")
							{
								$ids = $task->id;
							}
							else{
								$ids = $ids.','.$task->id;
							}
						}
					}
					else{
						if($task->allocated_to == $userid)
						{
							if($task->allocated_spec_status == "1")
							{
								if($ids == "")
								{
									$ids = $task->id;
								}
								else{
									$ids = $ids.','.$task->id;
								}
							}
						}
					}
				}
			}
			echo $ids;
		}
	}
	public function request_update(Request $request)
	{
		$task_id = $request->get('task_id');
		$author = $request->get('author');
		$allocated = $request->get('allocated_to');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$task_id)->first();
		if($task_details->allocated_to != 0)
    	{
			$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr($task_specifics_val,0,30);
	    	}
	    	else{
	    		$subject_cls = $task_details->subject;
	    	}
			$allocated = Session::get('taskmanager_user');
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
			$message = '<spam style="color:#006bc7">****<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong>  has requested an Update of task <strong>'.$task_details->taskid.'</strong> - '.$subject_cls.'****</spam>';
			$dataval['task_id'] = $task_id;
			$dataval['from_user'] = $author;
			$dataval['created_date'] = date('Y-m-d');
			$dataval['message'] = $message;
			$dataval['status'] = 4;
			\App\Models\taskmanagerSpecifics::insert($dataval);
			$auditdata['user_id'] = $author;
			$auditdata['module'] = 'Task Manager';
			$auditdata['event'] = 'Request an Update';
			$auditdata['reference'] = $task_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
	    	$author_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$author)->first();
	    	$author_email = $author_details->email;
    		$allocated_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task_details->allocated_to)->first();
    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
    		$allocated_email = $allocated_details->email;
    		if(Session::has('taskmanager_user'))
		    {
		    	$sess_user = Session::get('taskmanager_user');
		    	if($sess_user == $task_details->author)
		    	{
		    		$dataupdate_spec_status['author_spec_status'] = 0;
					$dataupdate_spec_status['allocated_spec_status'] = 1;
		    	}
		    	else{
		    		$dataupdate_spec_status['author_spec_status'] = 1;
					$dataupdate_spec_status['allocated_spec_status'] = 0;
		    	}
		    	\App\Models\taskmanager::where('id',$task_id)->update($dataupdate_spec_status);
		    }
			$task_specifics = '';
			$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->orderBy('id','asc')->first();
			if(($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}
			$task_specifics = strip_tags($task_specifics);
			$uploads = 'public/papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
			$dataemail['logo'] = getEmailLogo('taskmanager');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
			$dataemail['taskid'] = $task_details->taskid;
			$subject_email = 'Task Manager - Update Request for Task: '.$subject_cls;
			$contentmessage = view('emails/task_manager/task_manager_request_update', $dataemail)->render();
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$email = new PHPMailer();
			$email->CharSet = "UTF-8";
			$email->SetFrom($author_details->email, $author_details->firstname.' '.$author_details->lastname);
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$taskmanager_settings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();
			$email->AddCC($taskmanager_settings->taskmanager_cc_email);
			$email->IsHTML(true);
			$email->AddAddress( $allocated_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();	
			echo "1";
    	}	
    	else{
    		echo "0";
    	}
	}
	public function change_task_name_taskmanager(Request $request)
	{
		$taskid = $request->get('taskid');
		$tasktype = $request->get('tasktype');
		$data['task_type'] = $tasktype;
		\App\Models\taskmanager::where('id',$taskid)->update($data);
		$details = \App\Models\timeTask::where('id',$tasktype)->first();
		echo $details->task_name;
	}
	public function park_task_complete(Request $request)
	{
		$taskid = $request->get('task_id');
		$task = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskid)->first();
		$park_date = $request->get('park_date');
		$new_park_date = DateTime::createFromFormat('d-M-Y', $park_date);
		$new_park_date = $new_park_date->format('Y-m-d');
		$data['status'] = 2;
		$data['park_date'] = $new_park_date;
		if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task->author)
	    	{
	    		$data['author_spec_status'] = 0;
				$data['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$data['author_spec_status'] = 1;
				$data['allocated_spec_status'] = 0;
	    	}
	    }
		\App\Models\taskmanager::where('id',$taskid)->update($data);
		$user = Session::get('taskmanager_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has parked this task <strong>'.date('d-M-Y').'</strong> until <strong>'.$park_date.'</strong>####</spam>';
		$data_specifics['from_user'] = $user;
		$data_specifics['to_user'] = 0;
		$data_specifics['task_id'] = $taskid;
		$data_specifics['message'] = $message;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['park_date'] = $new_park_date;
		$data_specifics['status'] = 0;
		\App\Models\taskmanagerSpecifics::insert($data_specifics);
		$auditdata['user_id'] = $user;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Task Parked';
		$auditdata['reference'] = $taskid;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
	}
	public function park_task_incomplete(Request $request)
	{
		$taskid = $request->get('task_id');
		$task = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskid)->first();
		$data['status'] = 0;
		if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task->author)
	    	{
	    		$data['author_spec_status'] = 0;
				$data['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$data['author_spec_status'] = 1;
				$data['allocated_spec_status'] = 0;
	    	}
	    }
		\App\Models\taskmanager::where('id',$taskid)->update($data);
		$user = Session::get('taskmanager_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has activated this task on <strong>'.date('d-M-Y').'</strong>####</spam>';
		$data_specifics['from_user'] = $user;
		$data_specifics['to_user'] = 0;
		$data_specifics['task_id'] = $taskid;
		$data_specifics['message'] = $message;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['status'] = 0;
		\App\Models\taskmanagerSpecifics::insert($data_specifics);
		$auditdata['user_id'] = $user;
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Task Activated';
		$auditdata['reference'] = $taskid;
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
	}
	public function change_taskmanager_park_status(Request $request)
	{
		$userid = $request->get('userid');
		$dataval['park_status'] = 1;
	   \App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$userid)->update($dataval);
	}
	public function reactivate_park_task(Request $request)
	{
		$userid = $request->get('userid');
		$date = date('Y-m-d');
		$park_task = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND `park_date` <= '".$date."' AND `allocated_to` = '".$userid."'");
		if(($park_task))
		{
			foreach($park_task as $task)
			{
				$taskid = $task->id;
				$data['status'] = 0;
				if(Session::has('taskmanager_user'))
			    {
			    	$sess_user = Session::get('taskmanager_user');
			    	if($sess_user == $task->author)
			    	{
			    		$data['author_spec_status'] = 0;
						$data['allocated_spec_status'] = 1;
			    	}
			    	else{
			    		$data['author_spec_status'] = 1;
						$data['allocated_spec_status'] = 0;
			    	}
			    }
				\App\Models\taskmanager::where('id',$taskid)->update($data);
				$user = Session::get('taskmanager_user');
				$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
				$username = $details->lastname.' '.$details->firstname;
				$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has activated this task on <strong>'.date('d-M-Y').'</strong>####</spam>';
				$data_specifics['from_user'] = $user;
				$data_specifics['to_user'] = 0;
				$data_specifics['task_id'] = $taskid;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 0;
				\App\Models\taskmanagerSpecifics::insert($data_specifics);
				$auditdata['user_id'] = $user;
				$auditdata['module'] = 'Task Manager';
				$auditdata['event'] = 'Task Activated';
				$auditdata['reference'] = $taskid;
				$auditdata['updatetime'] = date('Y-m-d H:i:s');
				\App\Models\AuditTrails::insert($auditdata);
			}
		}
	      $dataval['park_status'] = 1;
	     \App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$userid)->update($dataval);
	}
	public function edit_task_details_admin_screen(Request $request)
	{
		$taskid = $request->get('task_id');
		$details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$taskid)->first();
		if(($details))
		{
			echo $details->subject;
		}
		else{
			echo '';
		}
	}
	public function download_export_csv_task_manager(Request $request)
	{
		$view = $request->get('view');
		$user = Session::get('taskmanager_user');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user)->first();
		if($view == "3")
		{
			$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('author',$user)->where('status',0)->get();
			$filename = 'Allocated Task List for '.$user_details->lastname.' '.$user_details->firstname.'.csv';
			$file = fopen('public/papers/Authored Task List for '.$user_details->lastname.' '.$user_details->firstname.'-'.time().'.csv', 'w');
		}
		elseif($view == "2")
		{
			$tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$user."' OR `author` = '".$user."' OR `allocated_to` = '0') AND (`author_spec_status` = '1' OR `allocated_spec_status` = '1')");
			$filename = 'Notified Task List for '.$user_details->lastname.' '.$user_details->firstname.'.csv';
			$file = fopen('public/papers/Notified Task List for '.$user_details->lastname.' '.$user_details->firstname.'-'.time().'.csv', 'w');
		}
		else{
			$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('allocated_to',$user)->where('status',0)->get();
			$filename = 'Allocated Task List for '.$user_details->lastname.' '.$user_details->firstname.'-'.time().'.csv';
			$file = fopen('public/papers/Allocated Task List for '.$user_details->lastname.' '.$user_details->firstname.'-'.time().'.csv', 'w');
		}
		if(($tasks))
		{
			$columns = array('Client/Task Name', 'Subject', 'Author Name', 'Allocated Name', 'Due Date', 'Created Date', 'Task ID', 'P.T', 'Progress');
			fputcsv($file, $columns);
			foreach($tasks as $task)
			{
				if($task->client_id == "")
				{
					$title_lable = 'Task Name:';
					$task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
					if(($task_details))
					{
					  $title = $task_details->task_name;
					}
					else{
					  $title = '';
					}
				}
				else{
					$title_lable = 'Client:';
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
					if(($client_details))
					{
					  $title = $client_details->company.' ('.$task->client_id.')';
					}
					else{
					  $title = ''	;
					}
				}
				if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	            else{ $subject = $task->subject; }
	            if($task->allocated_to == 0) { $allocated_to = 'Open Task'; }
	            else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	            $author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
	            $project_time = '-';
                if($task->project_hours != ''){
                  $project_time = $task->project_hours.':'.$task->project_mins;
                }
			    $columns1 = array($title, $subject, $author->lastname.' '.$author->firstname, $allocated_to, date('d-M-Y', strtotime($task->due_date)), date('d-M-Y', strtotime($task->creation_date)), $task->taskid, $project_time, $task->progress);
			    fputcsv($file, $columns1);
			}
		}
		fclose($file);
   	 	echo $filename;
	}
	public function download_export_csv_task_search(Request $request){
		$author = $request->get('author');
		$open_task = $request->get('open_task');
		$client_id = $request->get('client_id');
		$subject = $request->get('subject');
		$recurring = $request->get('recurring');
		$due_date = $request->get('due_date');
		$creation_date = $request->get('creation_date');
		$make_internal = $request->get('make_internal');
		$select_tasks = $request->get('select_tasks');
		$filename = 'Search Task Lists '.time().'.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		$allocated_to_val = Session::get('taskmanager_user');
		$query = '';
		if($author != ""){ $query.= "`author` = '".$author."'";  }
		if($open_task == "0"){ 
			if($query == "") { $query.= "(`status` = '1' OR `status` = '2')"; } else { $query.= " AND (`status` = '1' OR `status` = '2')"; }
		}
		elseif($open_task == "1")
		{
			if($query == "") { $query.= "(`status` = '0' OR `status` = '2')"; } else { $query.= " AND (`status` = '0' OR `status` = '2')"; }
		}
		else{
		}
		if($make_internal == "0")
		{
			if($client_id != ""){ if($query == "") { $query.= "`client_id` = '".$client_id."'"; } else { $query.= " AND `client_id` = '".$client_id."'"; } }
		}
		else{
			if($select_tasks != ""){ if($query == "") { $query.= "`task_type` = '".$select_tasks."'"; } else { $query.= " AND `task_type` = '".$select_tasks."'"; } }
		}
		if($recurring != "0"){ if($query == "") { $query.= "`recurring_task` > '0'"; } else { $query.= " AND `recurring_task` > '0'"; } }
		else{ if($query == "") { $query.= "`recurring_task` = '0'"; } else { $query.= " AND `recurring_task` = '0'"; } }
		if($due_date != ""){
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			if($query == "") { $query.= "`due_date` = '".$due_date_change."'"; } else { $query.= " AND `due_date` = '".$due_date_change."'"; } 
		}
		if($creation_date != ""){ 
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			if($query == "") { $query.= "`creation_date` = '".$creation_date_change."'"; } else { $query.= " AND `creation_date` = '".$creation_date_change."'"; }
		}
		if($subject != "") { 
			if($query == "") { $query.= "`subject` LIKE '%".$subject."%' OR `task_specifics` LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%'"; } 
			else { $query.= " AND (`subject` LIKE '%".$subject."%' OR `task_specifics` LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%')"; } 
		}
		$query = "SELECT * FROM `taskmanager` WHERE ".$query."";
		$user_tasks = DB::select($query);
		$open_tasks = '';
		$layout = '';
		if(($user_tasks))
	    {
	        $columns = array('Client/Task Name', 'Subject', 'Author Name', 'Allocated Name', 'Due Date', 'Created Date', 'Task ID', 'P.T', 'Progress');
			fputcsv($file, $columns);
			foreach($user_tasks as $task)
			{
				if($task->client_id == "")
				{
					$title_lable = 'Task Name:';
					$task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
					if(($task_details))
					{
					  $title = $task_details->task_name;
					}
					else{
					  $title = '';
					}
				}
				else{
					$title_lable = 'Client:';
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
					if(($client_details))
					{
					  $title = $client_details->company.' ('.$task->client_id.')';
					}
					else{
					  $title = ''	;
					}
				}
				if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	            else{ $subject = $task->subject; }
	            if($task->allocated_to == 0) { $allocated_to = 'Open Task'; }
	            else{ $allocated =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	            $author =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
	            $project_time = '-';
                if($task->project_hours != ''){
                  $project_time = $task->project_hours.':'.$task->project_mins;
                }
			    $columns1 = array($title, $subject, $author->lastname.' '.$author->firstname, $allocated_to, date('d-M-Y', strtotime($task->due_date)), date('d-M-Y', strtotime($task->creation_date)), $task->taskid, $project_time, $task->progress);
			    fputcsv($file, $columns1);
			}
		}
		fclose($file);
   	 	echo $filename;
	}
	public function taskmanager_download_all_progress_files(Request $request)
	{
		$id = $request->get('id');
		$task_details = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		$files = \App\Models\taskmanagerFiles::where('task_id',$id)->where('status',1)->get();
		if(($files))
		{
			$public_dir=public_path();
			$zipFileName = 'Progress Files for Task ID '.$task_details->taskid.'.zip';
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					$filename = $file->filename;
					if(file_exists($file->url.'/'.$file->filename)){
						$zip->addFile($file->url.'/'.$file->filename,$filename);
					}
				}
				$zip->close();
			}
			echo $zipFileName;
		}
	}
	public function integrity_check_all_tasks(Request $request)
	{
		$page_no = $request->get('page_no');
		$offset = $page_no * 100;
		$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->offset($offset)->limit(100)->get();
		$outputtask = '';
          if(($tasks))
          {
            foreach($tasks as $task)
            {
              if($task->client_id == "")
              {
                $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
                if(($task_details))
                {
                  $title = $task_details->task_name;
                }
                else{
                  $title = '';
                }
              }
              else{
                $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
              }
              if($task->status == 1) { $color = 'color:#f00'; } 
              else { $color = ''; }
              $task_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',0)->get();
              $task_file_output = '';
              $task_first = '<td>N/A</td><td style="border-right:1px solid #ddd"></td>';
              $ival = 0;
              if(($task_files))
              {
              	foreach($task_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_first = '<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td style="border-right:1px solid #ddd">'.$status.'</td>';
              			}
              			$ival++;
              		}
              		else{
              			$task_file_output.='<tr>
	              			<td></td>
	              			<td></td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td style="border-right:1px solid #ddd">'.$status.'</td>
	              			<td>N/A</td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td>N/A</td>
	              			<td></td>
	              		</tr>'; 
              		}
              	}
              }
              $progress_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',1)->get();
              $progress_file_output = '';
              $task_second = '<td>N/A</td><td style="border-right:1px solid #ddd"></td>';
              if(($progress_files))
              {
              	foreach($progress_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_second = '<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td style="border-right:1px solid #ddd">'.$status.'</td>';
              			}
              			$ival++;
              		}
              		else{
              			$progress_file_output.='<tr>
	              			<td></td>
	              			<td></td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td>N/A</td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td style="border-right:1px solid #ddd">'.$status.'</td>
	              			<td>N/A</td>
	              			<td></td>
	              		</tr>'; 
              		}
              	}
              }
              $completion_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',2)->get();
              $completion_file_output = '';
              $task_third = '<td>N/A</td><td></td>';
              if(($completion_files))
              {
              	foreach($completion_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_third = '<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td>'.$status.'</td>';
              			}
              			$ival++;
              		}
              		else{
              			$completion_file_output.='<tr>
	              			<td></td>
	              			<td></td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td>N/A</td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td>N/A</td>
	              			<td style="border-right:1px solid #ddd"></td>
	              			<td><a href="'.URL::to($file->url.'/'.$file->filename).'" download data-toggle="tooltip" data-placement="top" title="'.$file->filename.'">'.substr($file->filename,0,25).'</a></td>
	              			<td>'.$status.'</td>
	              		</tr>'; 
              		}
              	}
              }
              $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task">
                <td style="'.$color.'">'.$task->taskid.'</td>
                <td style="'.$color.'">'.$title.'</td>
                <td style="'.$color.';border-right:1px solid #ddd">'.$task->subject.'</td>
                '.$task_first.'
                '.$task_second.'
                '.$task_third.'
              </tr>
              '.$task_file_output.'
              '.$progress_file_output.'
              '.$completion_file_output.'
              ';
            }
          }
          echo $outputtask;
	}
	public function export_integrity_tasks(Request $request)
	{
		$filename = time().'_export taskmanager integrity check.csv';
		if($request->has('filename'))
		{
			$filename = $request->get('filename');
			$filecsv = fopen('public/papers/'.$filename, 'a');
		}
		else{
			$filecsv = fopen('public/papers/'.$filename, 'w');
			$columns = array('Task ID', 'Task Name', 'Subject', 'Task Files', 'Status', 'Progress Files', 'Status', 'Completion Files', 'Status');
		    fputcsv($filecsv, $columns);
		}
		$page_no = $request->get('page_no');
		$offset = $page_no * 100;
		$tasks = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->offset($offset)->limit(100)->get();
		$outputtask = '';
          if(($tasks))
          {
            foreach($tasks as $task)
            {
              if($task->client_id == "")
              {
                $task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
                if(($task_details))
                {
                  $title = $task_details->task_name;
                }
                else{
                  $title = '';
                }
              }
              else{
                $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                if(($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
              }
              $task_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',0)->get();
              $task_file_output = array();
              $task_first = 'N/A';
              $task_first_1 = '';
              $ival = 0;
              if(($task_files))
              {
              	foreach($task_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = 'OK'; } else { $status = 'Missing'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_first = substr($file->filename,0,25);
	              			$task_first_1 = $status;
              			}
              			$ival++;
              		}
              		else{
	              		$task_file_output_arr = array("", "","",$file->filename,$status,"N/A","","N/A","");
	              		array_push($task_file_output,$task_file_output_arr);
              		}
              	}
              }
              $progress_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',1)->get();
              $progress_file_output = array();
              $task_second = 'N/A';
              $task_second_1 = '';
              if(($progress_files))
              {
              	foreach($progress_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = 'OK'; } else { $status = 'Missing'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_second = substr($file->filename,0,25);
	              			$task_second_1 = $status;
              			}
              			$ival++;
              		}
              		else{
              			$progress_file_output_arr = array("", "","","N/A","",$file->filename,$status,"N/A","");
              			array_push($progress_file_output,$progress_file_output_arr);
              		}
              	}
              }
              $completion_files = \App\Models\taskmanagerFiles::where('task_id',$task->id)->where('status',2)->get();
              $completion_file_output = array();
              $task_third = 'N/A';
              $task_third_1 = '';
              if(($completion_files))
              {
              	foreach($completion_files as $key => $file)
              	{
              		$path =  $file->url.'/'.$file->filename;
              		if(file_exists($path)) { $status = 'OK'; } else { $status = 'Missing'; }
              		if($ival == 0)
              		{
              			if($key == 0)
              			{
              				$task_third = substr($file->filename,0,25);
	              			$task_third_1 = $status;
              			}
              			$ival++;
              		}
              		else{
              			$completion_file_output_arr = array("", "","","N/A","","N/A","",$file->filename,$status);
              			array_push($completion_file_output,$completion_file_output_arr);
              		}
              	}
              }
              $columns1 = array($task->taskid, $title, $task->subject, $task_first, $task_first_1, $task_second, $task_second_1, $task_third, $task_third_1);
	    	  fputcsv($filecsv, $columns1);
	    	  if(($task_file_output))
	    	  {
	    	  	foreach($task_file_output as $tt)
	    	  	{
	    	  		fputcsv($filecsv, $tt);
	    	  	}
	    	  }
	    	  if(($progress_file_output))
	    	  {
	    	  	foreach($progress_file_output as $pp)
	    	  	{
	    	  		fputcsv($filecsv, $pp);
	    	  	}
	    	  }
	    	  if(($completion_file_output))
	    	  {
	    	  	foreach($completion_file_output as $cc)
	    	  	{
	    	  		fputcsv($filecsv, $cc);
	    	  	}
	    	  }
            }
          }
          fclose($filecsv);
          echo $filename;
	}
	public function get_author_email_for_taskmanager(Request $request)
	{
		$id = $request->get('value');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$id)->first();
		echo $user_details->email;
	}
	public function check_integrity_for_task(Request $request)
	{
		$task_id = $request->get('task_id');
		$task_files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',0)->get();
		$progress_files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',1)->get();
		$completion_files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',2)->get();
		$output = '<h4>Task Files:</h4>
		<table class="table">
		<thead>
			<th>S.No</th>
			<th>Filename</th>
			<th>Status</th>
		</thead>
		<tbody>';
		if(($task_files))
		{
			$i = 1;
			foreach($task_files as $key => $file)
			{
				$path =  $file->url.'/'.$file->filename;
				if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } 
				else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
				$output.='<tr>
					<td>'.$i.'</td>
					<td>'.$file->filename.'</td>
					<td>'.$status.'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="3">No Files Found</td></tr>';
		}
		$output.='</tbody>
		</table>
		<h4>Progress Files:</h4>
		<table class="table">
		<thead>
			<th>S.No</th>
			<th>Filename</th>
			<th>Status</th>
		</thead>
		<tbody>';
		if(($progress_files))
		{
			$i = 1;
			foreach($progress_files as $key => $file)
			{
				$path =  $file->url.'/'.$file->filename;
				if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } 
				else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
				$output.='<tr>
					<td>'.$i.'</td>
					<td>'.$file->filename.'</td>
					<td>'.$status.'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="3">No Files Found</td></tr>';
		}
		$output.='</tbody>
		</table>
		<h4>Completion Files:</h4>
		<table class="table">
		<thead>
			<th>S.No</th>
			<th>Filename</th>
			<th>Status</th>
		</thead>
		<tbody>';
		if(($completion_files))
		{
			$i = 1;
			foreach($completion_files as $key => $file)
			{
				$path =  $file->url.'/'.$file->filename;
				if(file_exists($path)) { $status = '<spam style="color:green;font-weight:600">OK</spam>'; } 
				else { $status = '<spam style="color:#f00;font-weight:600">Missing</spam>'; }
				$output.='<tr>
					<td>'.$i.'</td>
					<td>'.$file->filename.'</td>
					<td>'.$status.'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="3">No Files Found</td></tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function load_employee_details_overview(Request $request)
	{
		$users =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('practice',Session::get('user_practice_code'))->get();
		if(($users))
		{
			$tdarray = array();
			foreach($users as $user)
			{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `allocated_to` = '".$user->user_id."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND `allocated_to` = '".$user->user_id."'");
				$tdval = '<td>'.count($open_task_count).'</td><td>'.count($park_task_count).'</td>';
				array_push($tdarray, $tdval);
			}
		}
		$data['tdval'] = $tdarray;
		echo json_encode($data);
	}
	public function load_client_details_overview(Request $request)
	{
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		if(($clientlist))
		{
			$tdarray = array();
			foreach($clientlist as $client)
			{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `client_id` = '".$client->client_id."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND `client_id` = '".$client->client_id."'");
				$tdval = '<td>'.count($open_task_count).'</td><td>'.count($park_task_count).'</td>';
				array_push($tdarray, $tdval);
			}
		}
		$data['tdval'] = $tdarray;
		echo json_encode($data);
	}
	public function add_edit_project(Request $request){
		$project_id = base64_decode($request->get('project_id'));
		$data['project_name'] = $request->get('project_name');
		$data['author'] = $request->get('select_author');
		if($project_id == 0){
			\App\Models\projects::insert($data);
			$message = 'Project was successfully Created.';
		}
		else{
			\App\Models\projects::where('project_id', $project_id)->update($data);
			$message = 'Project was successfully Updated.';
		}
		$projectlist = \App\Models\projects::get();
        $output_project='';
        $i=1;
        if(($projectlist)){
          foreach ($projectlist as $project){
            $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $project->author)->first();
            $output_project.='
            <tr>
              <td>'.$i.'</td>
              <td style="background: #fff;">'.$project->project_name.'</td>
              <td style="background: #fff;">'.$user_details->lastname.' '.$user_details->firstname.'</td>
              <td style="width:50px; text-align:center; background: #fff;">
              <a href="javascript:">
                <i class="fa fa-pencil-square edit_project" data-element="'.base64_encode($project->project_id).'"></i>
              </a>
              </td>
            </tr>';
            $i++;
          }
        }
        else{
          $output_project='
            <tr>
              <td></td>
              <td></td>
              <td>No Data found</td>
              <td></td>
            </tr>
          ';
        }
		echo json_encode(array('message' => $message, 'projects' => $output_project));		
	}
	public function project_details(Request $request){
		$id = base64_decode($request->get('id'));
		$project_details = \App\Models\projects::where('project_id', $id)->first();
		echo json_encode(array('id' => base64_encode($id), 'name' => $project_details->project_name, 'author' => $project_details->author));
	}
	public function save_attachments_messages(Request $request){
		$datas = json_decode($request->get('messages'));
		$messages = $datas->message;
		$taskids = $datas->task_id;
		$userids = $datas->user_id;
		$user_id = $userids[0][0];
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user_id)->first();
		$current_date = date('d-M-Y');
		$outout_message = '<spam style="color:#006bc7">$$$<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> Has added files to this task on <strong>'.$current_date.'</strong>$$$</spam> <br/>';
		$data['task_id'] = $taskids[0][0];
		$data['from_user'] = $userids[0][0];
		if(($messages))
		{
			foreach($messages as $key => $message){
				$outout_message.= $message[0];
			}
			$data['message'] = $outout_message;
			\App\Models\taskmanagerSpecifics::insert($data);
		}
		$auditdata['user_id'] = $userids[0][0];
		$auditdata['module'] = 'Task Manager';
		$auditdata['event'] = 'Files Added';
		$auditdata['reference'] = $taskids[0][0];
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
		echo '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">
			'.$outout_message.'
		</dtrong>';
	}
	public function store_user_rating_taskmanager(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$data['user_rating'] = $value;
		\App\Models\taskmanager::where('id',$id)->update($data);
	}
	public function change_taskmanager_view_layout(Request $request){
		$value = $request->get('value');
		$data['taskmanager_view'] = $value;
		\App\Models\userLogin::where('id',1)->update($data);
	}
	public function task_analysis(Request $request)
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		return view('user.task_manager.task_analysis', array('open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count));
	}
	public function load_task_analysis(Request $request)
	{
		$client_id = $request->get('client_id');
		$task_id = $request->get('task_id');
		$from_date = $request->get('from_date');
		$to_date = $request->get('to_date');
		$format_from_date = date('Y-m-d',strtotime($from_date));
		$format_to_date = date('Y-m-d',strtotime($to_date));
		$query = '`practice_code` = "'.Session::get('user_practice_code').'"';
		if($client_id != ""){ 
		    $query.= " AND `client_id` = '".$client_id."'";
		}
		if($task_id != ""){ 
		    $query.= " AND `task_type` = ".$task_id;
		}
		if($from_date != "" && $to_date != ""){ 
		    $query.= " AND creation_date BETWEEN '".$format_from_date."' AND '".$format_to_date."'";
		}
		$query = "SELECT * FROM `taskmanager` WHERE ".$query." ORDER BY creation_date ASC";
		$user_tasks = DB::select($query);
		$layout = '';$i=0;
		$date_starting='';
		$count_dt=0;
		if(($user_tasks))
		{			
			foreach($user_tasks as $task)
			{
				if($i==0){
					$date_starting=$task->creation_date;
					if($format_to_date==''){
						$dt_ending = date('Y-m-d', strtotime($date_starting. ' + 120 days'));
						$period = new DatePeriod(
							new DateTime($date_starting),
							new DateInterval('P1D'),
							new DateTime($dt_ending)
						);
					}
					else{
						$d1 = new Datetime($date_starting);
						$d2 = new Datetime($format_to_date);
						$count_days =  $d2->diff($d1)->format("%a");
						if($count_days>120){
							$dt_ending = date('Y-m-d', strtotime($date_starting. ' + 120 days'));
						}
						else{
							$dt_ending=date('Y-m-d', strtotime($format_to_date. ' + 1 days'));
						}
						$period = new DatePeriod(
							new DateTime($date_starting),
							new DateInterval('P1D'),
							new DateTime($dt_ending)
						);
					}
					$td='';	
					foreach ($period as $key => $value) {				
						$from_ts = strtotime($value->format('d-M-Y'));
						$count_dt++;
						$td .= '<td style="text-align:left; font-weight:bold;border:0px; width:140px" class="'.$from_ts.'">'.$value->format('d-M-y').'</td>';
					}
				}
				if($task->status == 1){
					$task_spec_closed = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->orderBy('id','desc')->first();
					if($task_spec_closed){
						$closed_date = $task_spec_closed->allocated_date;
					}
					else{
						$closed_date = '';
					}
					$task_st_date = new Datetime($task->creation_date);
					$task_ed_date = new Datetime($closed_date);
					$no_of_days =  $task_ed_date->diff($task_st_date)->format("%a");
				}
				else{
					$closed_date = '';
					$no_of_days=0;
				}
				$task_specifics_val = strip_tags($task->task_specifics);
				if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
				else{ $subject = $task->subject; }
				if($task->status == 1){$status='Completed';}else{$status='Open';}
				$layout.='<tr>
					<td align="left"><a href="javascript:" class="task_id" data-element='.$task->id.'>'.$task->taskid.'</a></td>
					<td align="left">'.$subject.'</td>
					<td align="left">'.date('d-M-Y', strtotime($task->creation_date)).'</td>';
				if($task->status == 1){
					$layout.='<td align="left">'.date('d-M-Y', strtotime($closed_date)).'</td>';
				}
				else{
					$layout.='<td align="left"></td>';
				}
				$layout.='<td align="left">'.$status.'</td>';
				if($no_of_days != ''){
					$layout.='<td align="left">'.($no_of_days+1).' days</td>';
				}
				else{
					$layout.='<td align="left"></td>';
				}
				$layout .= '<td class="td_date_val_column" style="display:none;">
				<table>
					<tr>';
					foreach ($period as $key => $value) {
						$from_ts = strtotime($task->creation_date);
						$to_ts = strtotime($closed_date);
						$cur_ts = strtotime($value->format('Y-m-d'));
						$title=date('d-M-Y',strtotime($task->creation_date)).'-'.date('d-M-Y',strtotime($closed_date));
						$layout .= '';
						if($task->status==1){
							if($cur_ts >= $from_ts && $cur_ts <= $to_ts ){
								$layout .= '<td style="position:unset !important;background-color:#3eaadb !important" title="'.$title.'">
								<div style="visibility:hidden;"><span>'.$value->format('d-M-Y').'</span></div>
								</td>';
							}else{
								$layout .= '<td style="position:unset !important;"><div style="visibility:hidden;"><span>'.$value->format('d-M-Y').'</span></div></td>';
							}
						}
						else{
							// echo "current=>".$cur_ts."<pre>";
							// echo "from=>".$from_ts."<pre>";
							// echo "to=>".$to_ts."<pre>";
							if($cur_ts >= $from_ts ){								
								$layout .= '<td style="position:unset !important;background-color:#ff0000b3 !important; font-weight:bold; width:140px"><div style="visibility:hidden;"><span>'.$value->format('d-M-Y').'</span></div></td>';
							}else{
								$layout .= '<td style="position:unset !important; font-weight:bold; width:140px">
								<div style="visibility:hidden;"><span>'.$value->format('d-M-Y').'</span></div>
								</td>';
							}
						}
					}
					$layout .='</tr>
				</table>';
				$layout.='</tr>';
				$i++;
			}		   
		}
		else{
			$layout.='';
			$td='';
		}
		$outputlayout =$layout;
		echo json_encode(array("layout" => mb_convert_encoding($outputlayout,'UTF-8','UTF-8'),"date_layout" => mb_convert_encoding($td,'UTF-8','UTF-8')));
	}
	public function user_get_linked_infile_items(Request $request) {
		$task_id = $request->get('task_id');
		$get_linked_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task_id)->where('status',2)->get();
		$infileids = [];
		if(is_countable($get_linked_infiles) && count($get_linked_infiles) > 0){
			foreach($get_linked_infiles as $infile) {
				array_push($infileids, $infile->infile_id);
			}
		}
		echo json_encode($infileids);
	}
	public function user_download_completion_files(Request $request) {
		$task_id = $request->get('task_id');
		$client_id = $request->get('client_id');
		$public_dir=public_path();
		$zipFileName = 'Task-ID-A'.$task_id.'_CompletionFiles_'.time().'.zip';

		$zip = new ZipArchive;
       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
       		$takmanager_files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',2)->get();
			if(is_countable($takmanager_files) && count($takmanager_files) > 0) {
				foreach($takmanager_files as $file) {
					$filename = $file->filename;
					$filenameval = $file->url.'/'.$file->filename;
					if (file_exists($filenameval) && is_file($filenameval)) {
						$zip->addFile($file->url.'/'.$file->filename,'CompletionFiles/'.$filename);
					}
				}
			}

			$takmanager_notepads = \App\Models\taskmanagerNotepad::where('task_id',$task_id)->where('status',2)->get();
			if(is_countable($takmanager_notepads) && count($takmanager_notepads) > 0) {
				foreach($takmanager_notepads as $notepad) {
					$filename = $notepad->filename;
					$filenameval = $notepad->url.'/'.$notepad->filename;
					if (file_exists($filenameval) && is_file($filenameval)) {
						$zip->addFile($notepad->url.'/'.$notepad->filename,'CompletionNotes/'.$filename);
					}
				}
			}

			$takmanager_yearend = \App\Models\taskmanagerYearend::where('task_id',$task_id)->where('status',2)->get();
			if(is_countable($takmanager_yearend) && count($takmanager_yearend) > 0) {
				foreach($takmanager_yearend as $yearend) {
					$setting_id = $yearend->setting_id;
					$setting_detail = DB::table('year_setting')->where('id',$setting_id)->first();
					$yearend =\App\Models\YearClient::where('client_id',$client_id)->orderBy('id','desc')->first();
					if($yearend) {
						$yearendClientId = $yearend->id;
						$yearendDistributionAttachemnts = \App\Models\YearendDistributionAttachments::where('client_id',$yearendClientId)->where('setting_id',$setting_id)->where('attach_type',0)->get();
						if(is_countable($yearendDistributionAttachemnts) && count($yearendDistributionAttachemnts) > 0) {
							foreach($yearendDistributionAttachemnts as $attachment) {
								$filename = $attachment->attachments;
								$filenameval = $attachment->url.'/'.$attachment->attachments;
								if (file_exists($filenameval) && is_file($filenameval)) {
									$zip->addFile($attachment->url.'/'.$attachment->attachments,'YearendAttachments/'.$setting_detail->document.'/Distribution'.$attachment->distribution_type.'/'.$filename);
								}
							}
						}

						$yearendSupplementary = \App\Models\YearendNotesAttachments::where('client_id',$yearendClientId)->where('setting_id',$setting_id)->where('attach_type',0)->where('note_id',0)->get();
						if(is_countable($yearendSupplementary) && count($yearendSupplementary) > 0) {
							foreach($yearendSupplementary as $attachment) {
								$filename = $attachment->attachments;
								$filenameval = $attachment->url.'/'.$attachment->attachments;
								if (file_exists($filenameval) && is_file($filenameval)) {
									$zip->addFile($attachment->url.'/'.$attachment->attachments,'YearendAttachments/'.$setting_detail->document.'/SupplementaryNotes/'.$filename);
								}
							}
						}
					}
				}
			}

			$zip->close();
		}

		$get_linked_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task_id)->where('status',2)->get();
		$infileids = [];
		if(is_countable($get_linked_infiles) && count($get_linked_infiles) > 0){
			foreach($get_linked_infiles as $infile) {
				array_push($infileids, $infile->infile_id);
			}
		}
		$result['infileids'] = $infileids;
		$result['filename'] = $zipFileName;
		echo json_encode($result);
	}
	public function user_download_completion_files_only(Request $request) {
		$task_id = $request->get('task_id');
		$zipFileName = 'Task-ID-A'.$task_id.'_CompletionFiles_'.time().'.zip';
		$public_dir=public_path();
		$zip = new ZipArchive;
       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
       		$takmanager_files = \App\Models\taskmanagerFiles::where('task_id',$task_id)->where('status',2)->get();
			if(is_countable($takmanager_files) && count($takmanager_files) > 0) {
				foreach($takmanager_files as $file) {
					$filename = $file->filename;
					$filenameval = $file->url.'/'.$file->filename;
					if (file_exists($filenameval) && is_file($filenameval)) {
						$zip->addFile($file->url.'/'.$file->filename,'CompletionFiles/'.$filename);
					}
				}
			}

			$takmanager_notepads = \App\Models\taskmanagerNotepad::where('task_id',$task_id)->where('status',2)->get();
			if(is_countable($takmanager_notepads) && count($takmanager_notepads) > 0) {
				foreach($takmanager_notepads as $notepad) {
					$filename = $notepad->filename;
					$filenameval = $notepad->url.'/'.$notepad->filename;
					if (file_exists($filenameval) && is_file($filenameval)) {
						$zip->addFile($notepad->url.'/'.$notepad->filename,'CompletionNotes/'.$filename);
					}
				}
			}
			$zip->close();
		}
		echo $zipFileName;
	}

	public function infile_download_bpso_all_image_taskmanager(Request $request)
	{
		$id = $request->get('id');
		$page = $request->get('page');
		$filename = $request->get('filename');
		$task_id = $request->get('task_id');

		$details = \App\Models\inFile::where('id',$id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
		$public_dir=public_path();
		if($filename == ''){
			$zipFileName = 'Task-ID-A'.$task_id.'_Infiles_'.time().'.zip';
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

		$files = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 0)->where('secondary',0)->offset($offset)->limit(500)->get();
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
						$zip->addFile($file->url.'/'.$file->attachment,'InfileAttachments/'.$description.'/'.$filename);
					}
				}
				$zip->close();
			}
		}

		$total_count = \App\Models\inFileAttachment::where('file_id',$id)->where('status', 0)->where('notes_type', 0)->where('secondary',0)->count();
		echo json_encode(array('total_count' => $total_count, 'filename' => $zipFileName, 'infile_itemname' => date('d-M-Y', strtotime($details->date_added)).' '.$details->description));
	}

	public function search_taskmanager_consolidate_task(Request $request)
	{
		$author = $request->get('author');
		$open_task = $request->get('open_task');
		$client_id = $request->get('client_id');
		$subject = $request->get('subject');
		$recurring = $request->get('recurring');
		$due_date = $request->get('due_date');
		$creation_date = $request->get('creation_date');
		$make_internal = $request->get('make_internal');
		$select_tasks = $request->get('select_tasks');
		$allocated_to_val = Session::get('taskmanager_user');
		$query = '`practice_code` = "'.Session::get('user_practice_code').'"';
		if($author != ""){ 
		    if($query == "") {
		        $query.= "`author` = '".$author."'";
		    }
		    else {
		        $query.= " AND `author` = '".$author."'";
		    }
		}
		if($open_task == "0"){ 
			if($query == "") { $query.= "(`status` = '1' OR `status` = '2')"; } else { $query.= " AND (`status` = '1' OR `status` = '2')"; }
		}
		elseif($open_task == "1")
		{
			if($query == "") { $query.= "(`status` = '0' OR `status` = '2')"; } else { $query.= " AND (`status` = '0' OR `status` = '2')"; }
		}
		else{
		}
		if($make_internal == "0")
		{
			if($client_id != ""){ if($query == "") { $query.= "`client_id` = '".$client_id."'"; } else { $query.= " AND `client_id` = '".$client_id."'"; } }
		}
		else{
			if($select_tasks != ""){ if($query == "") { $query.= "`task_type` = '".$select_tasks."'"; } else { $query.= " AND `task_type` = '".$select_tasks."'"; } }
		}
		if($recurring != "0"){ if($query == "") { $query.= "`recurring_task` > '0'"; } else { $query.= " AND `recurring_task` > '0'"; } }
		else{ if($query == "") { $query.= "`recurring_task` = '0'"; } else { $query.= " AND `recurring_task` = '0'"; } }
		if($due_date != ""){
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');
			if($query == "") { $query.= "`due_date` = '".$due_date_change."'"; } else { $query.= " AND `due_date` = '".$due_date_change."'"; } 
		}
		if($creation_date != ""){ 
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');
			if($query == "") { $query.= "`creation_date` = '".$creation_date_change."'"; } else { $query.= " AND `creation_date` = '".$creation_date_change."'"; }
		}
		if($subject != "") { 
			if($query == "") { $query.= "`subject` LIKE '%".$subject."%' OR SUBSTR(`task_specifics`,0,30) LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%'"; } 
			else { $query.= " AND (`subject` LIKE '%".$subject."%' OR SUBSTR(`task_specifics`,0,30) LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%')"; } 
		}
		$query = "SELECT * FROM `taskmanager` WHERE ".$query."";
		$user_tasks = DB::select($query);
		$open_tasks = '';
		$layout = '';
		if(($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {	$status = "";
	        	if($task->status == 1 || $task->status == 2){
	        		$status = 'closed';
	        	} else if($task->status == 0 || $task->status == 2) {
	        		$status = 'Open';
	        	}
	        	$two_bill_icon = '';
                
				if($task->client_id == "")
				{
					$title_lable = 'Task Name:';
					$task_details = \App\Models\timeTask::where('id', $task->task_type)->first();
					if(($task_details))
					{
						$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
					}
					else{
						$title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
					}
				}
				else{
					$title_lable = 'Client:';
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
					if(($client_details))
					{
					  $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
					}
					else{
					  $title = ''.$two_bill_icon;
					}
				}
	        	$layout.= '<tr>';
			    	$statusi = 0;
			    		
			    		$layout.= '<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="select_option"> <input type="checkbox" name="task-checkbox" class="taskCheckbox" value='.$task->id.'><label>&nbsp;</label></td>
			    		<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
			    		<td style="width:35%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'';
			    			
			    		$layout.= '</td>';
	                    
	                    $layout.='
			    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$task->subject.'</td>
			    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$status.'</td>
			    	</tr>';
	        }
	    }
	    else{
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }
		$outputlayout =$layout;
		echo json_encode(array("open_tasks" => mb_convert_encoding($open_tasks,'UTF-8','UTF-8'),"layout" => mb_convert_encoding($outputlayout,'UTF-8','UTF-8')));
	}

	public function consolidate_process(Request $request){
		$consolidateType = $request->get('consolidateType');
		$consolidateTaskValue = explode(',',$request->get('consolidateTaskValue'));
		$output = '';
		if($consolidateType == "1") {
			$task_detail_lists = \App\Models\taskmanager::where('practice_code', Session::get('user_practice_code'))->whereIn('id',$consolidateTaskValue)->orderby("id", 'ASC')->get();
			foreach($task_detail_lists as $task_details) {
				$specifics_first = \App\Models\taskmanagerSpecifics::where('task_id',$task_details->id)->orderBy('id','asc')->first();
				
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $task_details->task_specifics);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$specficsval1 = str_replace("<p></p>", "", $specficsval1);
				$output.= '';
				if($specifics_first)
				{
					$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$task_details->taskid."     ".$specifics_first->message.'</strong>
					<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$task_details->taskid."     ".$specficsval1.'</strong>';
					$specifics = \App\Models\taskmanagerSpecifics::where('task_id',$task_details->id)->where('id','!=',$specifics_first->id)->get();
					if(($specifics))
					{
						foreach($specifics as $specific)
						{
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specific->message);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$specficsval = str_replace("<p></p>", "", $specficsval);
							$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$task_details->taskid."     ".$specficsval.'</strong>' ;
						}
					}
				}
			}
		}
		else{
			$fields = ['id as task_id',
						'task_specifics as message',
						'updatetime'];
			$task_detail_lists = \App\Models\taskmanager::select($fields)->where('practice_code', Session::get('user_practice_code'))->whereIn('id',$consolidateTaskValue)->orderby("updatetime", 'ASC')->get()->toArray();
			$fields1 = ['task_id',
						'message',
						'updatetime'];
			$specifics_first = \App\Models\taskmanagerSpecifics::select($fields1)->whereIn('task_id',$consolidateTaskValue)->orderBy('updatetime','asc')->get()->toArray();
			$combined_lists = array_merge($task_detail_lists, $specifics_first);
			// Sort the array 
			usort($combined_lists, 'date_compare');
			if(is_countable($combined_lists) && count($combined_lists) > 0)
			{
				foreach($combined_lists as $specific)
				{
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specific['message']);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$specficsval = str_replace("<p></p>", "", $specficsval);
					$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specific['task_id']."     ".$specficsval.'</strong>' ;
				}
			}
		}
		echo json_encode(array("output" => $output));
	}
	public function edit_taskmanager_header_image(Request $request) {
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

                    DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	public function save_taskmanager_settings(Request $request) {
		$cc_email = $request->get('taskmanager_cc_email');
		$data['taskmanager_cc_email'] = $cc_email;

		$check_settings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		if($check_settings) {
		      DB::table('taskmanager_settings')->where('id',$check_settings->id)->update($data);
		}
		else{
		      $data['practice_code'] = Session::get('user_practice_code');
		      DB::table('taskmanager_settings')->insert($data);
		}
		return redirect::back()->with('message', 'Taskmanager Setings Saved Sucessfully.');
	}
	public function quick_task_view(Request $request) {
		$taskid = $request->get('taskid');
		$checkTaskid = DB::table('taskmanager')->where('taskid',$taskid)->where('practice_code',Session::get('user_practice_code'))->first();
		if($checkTaskid) {
			echo $checkTaskid->id;
		}
		else{
			echo 0;
		}
	}
}
