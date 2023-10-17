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
use Illuminate\Http\Request;
class TimemeController extends Controller {
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
	public function time_task(Request $request)
	{	
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$client_id_count = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where("client_id","!=","")->count();
		$time_task = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->get();
		return view('user/timeme/timetask', array('title' => 'Time Task', 'tasklist' => $time_task, 'clientlist' => $clients,"client_id_count" => $client_id_count));
	}
	public function time_task_client_details(Request $request){
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		$i=1;
		if(($clientlist)){
			foreach ($clientlist as $key => $clients) {
				$output.='
						<tr>
							<td>'.$i.'</td>
							<td><input type="checkbox" name="select_client[]" class="select_client class_'.$clients->client_id.'" data-element="'.$clients->client_id.'" value="'.$clients->client_id.'"><label>&nbsp</label></td>
							<td>'.$clients->client_id.'</td>
							<td>'.$clients->firstname.'</td>
							<td>'.$clients->surname.'</td>
							<td width="40%">'.$clients->company.'</td>
						</tr>
				';
				$i++;
			}
		}
		echo $output;
	}
	public function time_task_add(Request $request){
		$type = $request->get("task_type");
		if($type == 0){
			$project = $request->get('select_projects');
			$data['clients'] ='';
			$data['task_name'] = $request->get("task_name");
			$data['task_type'] = $request->get("task_type");
			$data['project_id'] = $project;
		}
		else{
			$data['clients'] = implode(",",$request->get('select_client'));
			$data['task_name'] = $request->get("task_name");
			$data['task_type'] = $request->get("task_type");
		}
		$data['practice_code'] = Session::get('user_practice_code');
		\App\Models\timeTask::insert($data);
		return Redirect::back()->with('message', 'Time Task added Succefully');
	}
	public function time_task_client_counts(Request $request){
		$taskid = base64_decode($request->get('id'));
		$tasklist = \App\Models\timeTask::where('id', $taskid)->first();
		$client_id = explode(",",$tasklist->clients);
		$i=1;
		$output='';
		if(($client_id)){
			foreach ($client_id as $key => $client) {
				$clientdetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client)->first();
				if(($clientdetails)){
						$client_id = $clientdetails->client_id;
						$firstname = $clientdetails->firstname;
						$surname = $clientdetails->surname;
						$company = $clientdetails->company;
				}
				else{
						$client_id = '';
						$firstname = '';
						$surname = '';
						$company = '';
				}
				$output.='
						<tr>
							<td>'.$i.'</td>
							<td>'.$client_id.'</td>
							<td>'.$firstname.'</td>
							<td>'.$surname.'</td>
							<td width="40%">'.$company.'</td>
						</tr>';
				$i++;
			}
		}
		echo $output;		
	}
	public function timetasklock_unlock(Request $request){
		$id = base64_decode($request->get('id'));
		$status = $request->get('status');
		\App\Models\timeTask::where('id', $id)->update(['status' => $status]);
		if($status == '1'){
			return redirect::back()->with('message','Lock Success');
		}
		else{
			return redirect::back()->with('message','Unlock Success');
		}
	}
	public function timetask_edit(Request $request){
		$id = base64_decode($request->get('id'));
		$task_details = \App\Models\timeTask::where('id',$id)->first();
		echo json_encode(array('taskname' => $task_details->task_name,'taskid' => $task_details->id,'clients' => $task_details->clients, 'tasktype' => $task_details->task_type, 'project_id' => $task_details->project_id));
	}
	public function time_task_update(Request $request){
		$taskid = $request->get("taskid");
		$type = $request->get("task_type");
		if($type == 0){
			$data['project_id'] = $request->get('select_projects_edit');
			$data['clients'] = '';
			$data['task_name'] = $request->get("task_name");
			$data['task_type'] = $request->get("task_type");
		}
		else{
			$data['clients'] = implode(",",$request->get('select_client'));
			$data['task_name'] = $request->get("task_name");
			$data['task_type'] = $request->get("task_type");
		}
		\App\Models\timeTask::where('id', $taskid)->update($data);
		return Redirect::back()->with('message', 'Updated Succefully');
	}
	public function time_task_review(Request $request){
		$taskid = base64_decode($request->get("id"));
		$client_ids = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where("client_id","!=","")->get();
		$update_id ='';	
		$commo = '';
		if(($client_ids)){
			foreach ($client_ids as $key => $clienid) {
				if($commo == ''){
					$commo = $clienid->client_id;
				}
				else{
					$commo =  $commo.','. $clienid->client_id;
				}
			}
		}
		$data['clients'] = $commo;
		\App\Models\timeTask::where('id', $taskid)->update($data);
		$clients_count = \App\Models\timeTask::where('id', $taskid)->first();
		$count = count(explode(',',$clients_count->clients));
		echo json_encode(array('reviewcount' => $count, 'taskid' => $taskid));
	}
	public function time_task_review_all(Request $request){
		$task_ids = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 2)->get();
		$array = array();
		if(($task_ids)){
			foreach ($task_ids as $key => $singletask) {
				$taskid = $singletask->id;
				$client_ids = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where("client_id","!=","")->get();
				$update_id ='';	
				$commo = '';
				if(($client_ids)){
					foreach ($client_ids as $key => $clienid) {
						if($commo == ''){
							$commo = $clienid->client_id;
						}
						else{
							$commo =  $commo.','. $clienid->client_id;
						}
					}
				}
				$data['clients'] = $commo;
				\App\Models\timeTask::where('id', $taskid)->update($data);
				$clients_count = \App\Models\timeTask::where('id', $taskid)->first();
				$count = count(explode(',',$clients_count->clients));
				array_push($array,array('reviewcount' => $count, 'taskid' => $taskid));
			}
		}
		echo json_encode($array);
	}
	public function import_client_list_timeme(Request $request)
	{
		$import_file = $_FILES['import_file']['name'];
		$tmp_name = $_FILES['import_file']['tmp_name'];
		$upload_dir = 'public/papers/timeme_import';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		move_uploaded_file($tmp_name, $upload_dir.'/'.$import_file);
		$filepath = $upload_dir.'/'.$import_file;
		$objPHPExcel = IOFactory::load($filepath);
		$i = 0;
		$message = '';
		$client_codes = array();
		$client_firstname = array();
		$client_surname = array();
		$client_company = array();
		$client_errors = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;
			$client_id_header = $worksheet->getCellByColumnAndRow(1, 1); $client_id_header = trim($client_id_header->getValue());
			if($client_id_header == "Client Code")
			{
				for ($row = 2; $row <= $height; ++ $row) {
					$client_code = $worksheet->getCellByColumnAndRow(1,$row); $client_code = trim($client_code->getValue());
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_code)->first();
					if(($client_details))
					{
						if(!in_array($client_code,$client_codes))
						{
							array_push($client_codes,$client_code);
							array_push($client_firstname,$client_details->firstname);
							array_push($client_surname,$client_details->surname);
							array_push($client_company,$client_details->company);
						}
					}
					else{
						if(!in_array($client_code,$client_errors))
						{
							array_push($client_errors,$client_code);
						}
						$i = $i + 1;
						$message = "This file contains Invalid Client ID's as follows";
					}
				}
			}
			else{
				$i = $i + 1;
				$message = 'Invalid CSV File Format';
			}
		}
		echo json_encode(array("error" => $i, "message" => $message, "client_codes" => $client_codes, "client_errors" => $client_errors, "client_firstname" => $client_firstname, "client_surname" => $client_surname, "client_company" => $client_company));
	}
}