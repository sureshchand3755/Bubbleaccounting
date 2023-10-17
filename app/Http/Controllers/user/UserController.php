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
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
class UserController extends Controller {
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
		require_once(app_path('Http/helpers.php'));
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function dashboard_analytics(Request $request)
	{
		return view('user/dashboard/dashboad_analytics');
	}
	public function dashboard_system_summary(Request $request)
	{
		$time_job = \App\Models\taskJob::get();
		$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$user_details =\App\Models\userLogin::where('userid',1)->first();
		return view('user/dashboard/dashboard_system_summary', array('title' => 'Bubble - Dashboard', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks,'user_details' => $user_details));
	}
	public function dashboard_user_details(Request $request)
	{
		$userid = Session::get('userid');

		$time_job = \App\Models\taskJob::where('user_id',$userid)->where('active_id',0)->orderBy('start_time', 'asc')->get();
		$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."')"); 
		$audit_trails = \App\Models\AuditTrails::where('user_id',$userid)->where('event', 'Logged In')->orderBy('id','desc')->offset(0)->limit(15)->get();
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $userid)->first();

		return view('user/dashboard/dashboard_user_detail', array('title' => 'Bubble - Dashboard', 'joblist' => $time_job, 'taskslist' => $user_tasks,'audit_trails' => $audit_trails,'user_details' => $user_details));
	}
	public function updatestatus_timetrack(Request $request){
		$jobs = \App\Models\taskJob::where('quick_job',0)->where('updatestatus',0)->get();
		if(($jobs))
		{
			foreach($jobs as $job)
			{
				$dataval['comments'] = $job->comments;
				$datastatus['updatestatus'] = 1;
				\App\Models\taskJob::where('active_id',$job->id)->where('comments','')->update($dataval);
				\App\Models\taskJob::where('id',$job->id)->update($datastatus);
			}
		}
	}
	public function time_track(Request $request)
	{
		if(Session::has('task_job_user'))
		{
			$userid = Session::get('task_job_user');
			$time_job = \App\Models\taskJob::where('user_id',$userid)->where('active_id',0)->orderBy('start_time', 'asc')->get();
		}
		else{
			$time_job = array();
		}
		$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$active_client_list =  \App\Models\ActiveClientList::join('cm_clients','cm_clients.client_id','=','active_client_list.client_id')
			->select('active_client_list.*','cm_clients.company','cm_clients.active')
			->where('active_client_list.practice_code',Session::get('user_practice_code'))
			->where('active_client_list.user_id',$userid)->get();
		return view('user/dashboad_time_track', array('title' => 'Welcome to Bubble', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks, 'active_client_list' => $active_client_list));
	}
	public function getclientlist_for_timemetask(Request $request)
	{
		$tbody='';
		$userid = Session::get('task_job_user');
		if($request->get("type")==1){
			$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
			if(($clients))
			{
				foreach($clients as $client)
				{
					if($client->active == "2") { $cls= 'client_inactive'; } 
					else { $cls = 'client_active'; }
					$tbody .= '<tr class="client_tr '.$cls.'" id="client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">
					<td class="client_td"><input type="checkbox" name="client_exclude[]" class="client_exclude" value="'.$client->client_id.'"><label>&nbsp;</label></td>
					<td class="client_td">'.$client->client_id.'</td>
					<td class="client_td">'.$client->company.'</td>
					<td class="client_td">';
					$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client->client_id.'%')->orderBy('task_name','asc')->get();
					$output = '<select name="client_task_'.$client->client_id.'" class="form-control client_task">
					<option value="">Select Tasks</option>';
					if(($tasks)){
						foreach ($tasks as $single_task) {
							$output.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
						}
					}
					else{
						$output.= '<option value="">No Tasks Found</option>';
					}
					$output.='</select>';
					$tbody .= $output.'</td>
					</tr>';
				}
			}
		}
		else if($request->get("type")==2){
			$clients = \App\Models\ActiveClientList::join('cm_clients','cm_clients.client_id','=','active_client_list.client_id')
				->select('active_client_list.*','cm_clients.company','cm_clients.active')
				->where('active_client_list.practice_code',Session::get('user_practice_code'))
				->where('active_client_list.user_id',$userid)->get();
			if(($clients))
			{
				foreach($clients as $client)
				{
					if($client->active == "2") { $cls= 'client_inactive'; } 
					else { $cls = 'client_active'; }
					$tbody .= '<tr class="client_tr '.$cls.'" id="client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">
					<td class="client_td"><input type="checkbox" name="client_exclude[]" class="client_exclude" value="'.$client->client_id.'"><label>&nbsp;</label></td>
					<td class="client_td">'.$client->client_id.'</td>
					<td class="client_td">'.$client->company.'</td>
					<td class="client_td">';
					$tasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client->client_id.'%')->orderBy('task_name','asc')->get();
					$output = '<select name="client_task_'.$client->client_id.'" class="form-control client_task">
					<option value="">Select Tasks</option>';
					if(($tasks)){
						foreach ($tasks as $single_task) {
							$output.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
						}
					}
					else{
						$output.= '<option value="">No Tasks Found</option>';
					}
					$output.='</select>';
					$tbody .= $output.'</td>
					</tr>';
				}
			}
		}
		echo $tbody;		
	}	
	public function unavailable(Request $request)
	{
		return view('user/unavailable');
	}
	public function manageweek(Request $request)
	{
		$year = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->get();
		return view('user/week', array('title' => 'Select Year', 'yearlist' => $year));
	}
	public function managemonth(Request $request)
	{
		$year = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->get();
		return view('user/month', array('title' => 'Select Year', 'yearlist' => $year));
	}
	public function weekmanage(Request $request, $id=""){
		$id = base64_decode($id);
		$year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id',$id)->first();
		$week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year', $id)->orderBy('week_id','desc')->get();
		return view('user/week_manage', array('title' => 'Select Week', 'weeklist' => $week,'year' => $year));
	}
	public function monthmanage(Request $request, $id=""){
		$id = base64_decode($id);
		$year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id',$id)->first();
		$month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year', $id)->orderBy('month_id','desc')->get();
		return view('user/month_manage', array('title' => 'Select Week', 'monthlist' => $month,'year' => $year));
	}
	public function logout(Request $request){
		if(Session::has('task_job_user'))
		{
			Session::forget('task_job_user');
		}
		Session::forget("userid");
		return redirect(URL::to('/'));
	}
	public function selectweek(Request $request, $id=""){
		$id =base64_decode($id);
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		$user_year = $year_id->year_id;
		// $year_user =  \App\Models\taskYear::where('taskyear_id', $user_year)->first();
		$week_id = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$week2 = $week_id->week_id;
		$year2 = $week_id->year;
		$result_task_standard = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)
								->where('task_week', $week2)
								->where('task_classified',1)->get();
		$result_task_enhanced = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',2)->get();
		$result_task_complex = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',3)->get();

		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$uname = '<option value="">Select Username</option>';
		$email = '<option value="">Select Email</option>';
		$initial = '<option value="">Select Initial</option>';
		if(($userlist)){
			foreach ($userlist as $singleuser) {
					if($uname == '')
					{
						$uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
						$email = '<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';
						$initial = '<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';
					}
					else{
						$uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
						$email = $email.'<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';
						$initial = $initial.'<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';
					}
				}
		}
		
		$classified = \App\Models\Classified::get();
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

		return view('user/select_week', array('title' => 'Week Task', 'yearname' => $year_id, 'weekid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname,'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex, 'userlist' => $userlist));
	}
	public function selectmonth(Request $request, $id=""){
		$id =base64_decode($id);
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		$user_year = $year_id->year_id;
		//$year_user =  \App\Models\taskYear::where('taskyear_id', $user_year)->first();
		$month_id = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;
		$result_task_standard = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',1)->get();
		$result_task_enhanced = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',2)->get();
		$result_task_complex = \App\Models\task::
								join('cm_clients','cm_clients.client_id','=','pms_task.client_id')
								->select('pms_task.*')
								->where('cm_clients.practice_code', Session::get('user_practice_code'))
								->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',3)->get();
	
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$uname = '<option value="">Select Username</option>';
		$email = '<option value="">Select Email</option>';
		$initial = '<option value="">Select Initial</option>';
		if(($userlist)){
			foreach ($userlist as $singleuser) {
					if($uname == '')
					{
						$uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
						$email = '<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';
						$initial = '<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';
					}
					else{
						$uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
						$email = $email.'<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';
						$initial = $initial.'<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';
					}
				}
		}
		$classified = \App\Models\Classified::get();
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/select_month', array('title' => 'Month Task', 'yearname' => $year_id, 'monthid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname, 'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex,'userlist' => $userlist));
	}
	public function addnewtask(Request $request){
		$year = $request->get('year');
		$week = $request->get('weekid');
		$weekid = $request->get('weekid');
		$tastname =  $request->get('tastname');
		$classified =  $request->get('classified');
		$enterhours =  $request->get('enterhours');
		$holiday =  $request->get('holiday');
		$process =  $request->get('process');
		$payslips =  $request->get('payslips');
		$email =  $request->get('email');
		$secondary_email =  $request->get('secondary_email');
		$salutation =  $request->get('salutation');
		$upload =  $request->get('uploadd');
		$task_email =  $request->get('task_email');
		$location =  $request->get('location');	
		$task_enumber =  trim($request->get('task_enumber'));
		$tasklevel =  trim($request->get('tasklevel'));
		$p30_pay =  trim($request->get('pay_p30'));
		$p30_email =  trim($request->get('email_p30'));
		$clientid =  trim($request->get('clientid'));
		$taskid = \App\Models\task::insertDetails(['task_year' => $year, 'task_week' => $week, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation, 'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email, 'client_id' => $clientid,'practice_code' => Session::get('user_practice_code')]);
		$datavalue['task_created_id'] = $taskid;
		\App\Models\task::where('task_id',$taskid)->update($datavalue);
		$dataemployers['emp_no'] = $task_enumber;
		$dataemployers['emp_name'] = $tastname;
		$dataemployers["practice_code"] = Session::get('user_practice_code');
        $check_emp_details = \App\Models\Employers::where('practice_code',Session::get('user_practice_code'))->where('emp_no',$task_enumber)->first();
        if(($check_emp_details)){
        }
        else{
            \App\Models\Employers::insertDetails($dataemployers);
        }
		$emp_client = $request->get('hidden_client_emp');
		$saluation_client = $request->get('hidden_client_salutation');
		$clientid = $request->get('hidden_client_id');
		if($emp_client == 1)
		{
			$data['employer_no'] = trim($request->get('task_enumber'));
		}
		if($saluation_client == 1)
		{
			$data['salutation'] = trim($request->get('salutation'));
		}
		if($emp_client == 1 || $saluation_client == 1)
		{
			\App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->update($data);
		}
		return redirect('user/select_week/'.base64_encode($weekid))->with('message', 'Task Created Success');
	}
	public function addnewtask_month(Request $request){
		$year = $request->get('year');
		$month = $request->get('monthid');
		$monthid = $request->get('monthid');
		$tastname =  $request->get('tastname');
		$classified =  $request->get('classified');
		$enterhours =  $request->get('enterhours');
		$holiday =  $request->get('holiday');
		$process =  $request->get('process');
		$payslips =  $request->get('payslips');
		$email =  $request->get('email');
		$secondary_email =  $request->get('secondary_email');
		$salutation =  $request->get('salutation');
		$upload =  $request->get('uploadd');
		$task_email =  $request->get('task_email');
		$location =  $request->get('location');
		$task_enumber =  trim($request->get('task_enumber'));
		$tasklevel =  trim($request->get('tasklevel'));
		$p30_pay =  trim($request->get('pay_p30'));
		$p30_email =  trim($request->get('email_p30'));
		$clientid =  trim($request->get('clientid'));
		$taskid = \App\Models\task::insertDetails(['task_year' => $year, 'task_month' => $month, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation,  'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email, 'client_id' => $clientid,'practice_code' => Session::get('user_practice_code')]);
		$datavalue['task_created_id'] = $taskid;
		\App\Models\task::where('task_id',$taskid)->update($datavalue);
		$dataemployers['emp_no'] = $task_enumber;
		$dataemployers['emp_name'] = $tastname;
		$dataemployers["practice_code"] = Session::get('user_practice_code');
        $check_emp_details = \App\Models\Employers::where('practice_code',Session::get('user_practice_code'))->where('emp_no',$task_enumber)->first();
        if(($check_emp_details)){
        }
        else{
            \App\Models\Employers::insertDetails($dataemployers);
        }
		$emp_client = $request->get('hidden_client_emp');
		$saluation_client = $request->get('hidden_client_salutation');
		$clientid = $request->get('hidden_client_id');
		if($emp_client == 1)
		{
			$data['employer_no'] = trim($request->get('task_enumber'));
		}
		if($saluation_client == 1)
		{
			$data['salutation'] = trim($request->get('salutation'));
		}
		if($emp_client == 1 || $saluation_client == 1)
		{
			\App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->update($data);
		}
		return redirect('user/select_month/'.base64_encode($monthid))->with('message', 'Task Created Success');
	}
	public function deletetask(Request $request, $id=""){
		$id = base64_decode($id);
		$get_week_month = \App\Models\task::where('task_id',$id)->first();
		\App\Models\task::where('task_id',$id)->delete();
		if($get_week_month)
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if($check_update)
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
			else{
				$get_month_name = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if($check_update)
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
		}
		return Redirect::back();
	}
	public function task_enterhours(Request $request)
	{
		$id = $request->get('id');
		$enterhouse = $request->get('enterhouse');
		\App\Models\task::where('task_id',$id)->update(['enterhours' => $enterhouse]);
	}
	public function task_started_checkbox(Request $request)
	{
		$id = $request->get('id');
		$task_started = $request->get('task_started');
		\App\Models\task::where('task_id',$id)->update(['task_started' => $task_started]);
	}
	public function task_holiday(Request $request)
	{
		$id = $request->get('id');
		$holiday = $request->get('holiday');
		\App\Models\task::where('task_id',$id)->update(['holiday' => $holiday]);
	}
	public function task_process(Request $request)
	{
		$id = $request->get('id');
		$process = $request->get('process');
		\App\Models\task::where('task_id',$id)->update(['process' => $process]);
	}
	public function task_payslips(Request $request)
	{
		$id = $request->get('id');
		$payslips = $request->get('payslips');
		\App\Models\task::where('task_id',$id)->update(['payslips' => $payslips]);
	}
	public function task_email(Request $request)
	{
		$id = $request->get('id');
		$email = $request->get('email');
		\App\Models\task::where('task_id',$id)->update(['email' => $email]);
	}
	public function task_upload(Request $request)
	{
		$id = $request->get('id');
		$upload = $request->get('upload');
		\App\Models\task::where('task_id',$id)->update(['upload' => $upload]);
	}
	public function task_date_update(Request $request)
	{
		$id = $request->get('id');
		$date = $request->get('date');
		$exp = explode('-',$date);
		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];
		\App\Models\task::where('task_id',$id)->update(['date' => $date]);
	}
	public function task_email_update(Request $request)
	{
		$id = $request->get('id');
		$email = $request->get('email');
		\App\Models\task::where('task_id',$id)->update(['task_email' => $email]);
	}
	public function task_users_update(Request $request)
	{
		$id = $request->get('id');
		$users = $request->get('users');
		\App\Models\task::where('task_id',$id)->update(['users' => $users]);
	}
	public function task_classified_update(Request $request)
	{
		$id = $request->get('id');
		$classified = $request->get('classified');
		\App\Models\task::where('task_id',$id)->update(['task_classified' => $classified]);
	}
	public function task_comments_update(Request $request)
	{
		$id = $request->get('id');
		$comments = $request->get('comments');
		\App\Models\task::where('task_id',$id)->update(['comments' => $comments]);
	}
	public function task_liability_update(Request $request)
	{
		$id = $request->get('id');
		$liability = $request->get('liability');
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		\App\Models\task::where('task_id',$id)->update(['liability' => $liability]);
		$get_week_month = \App\Models\task::where('task_id',$id)->first();
		if(($get_week_month))
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(($check_update))
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
			else{
				$get_month_name = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(($check_update))
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
		}
	}
	public function task_image_upload(Request $request)
	{
		$total = count($_FILES['image_file']['name']);
		$id = $request->get('hidden_id');
		$type = $request->get('type');
		for($i=0; $i<$total; $i++) {
		 	$filename = $_FILES['image_file']['name'][$i];
			$data_img = \App\Models\task::where('task_id',$id)->first();
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/task_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['task_id'] = $data_img->task_id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;
			if($type == 2)
			{
				$data['network_attach'] = 1;
			}
			else{
				$data['network_attach'] = 0;
			}
			\App\Models\taskAttached::insert($data);
		}
		if($type == 2)
		{
			$dataval['task_started'] = 1;
			$dataval['task_notify'] = 1;
			\App\Models\task::where('task_id',$id)->update($dataval);
		}
		if($data_img->task_week != 0)
		{
			return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);
		}
		else{
			return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
		}
	}
	public function task_notepad_upload(Request $request)
	{
		$id = $request->get('hidden_id');
		$data_img = \App\Models\task::where('task_id',$id)->first();
		$count = \App\Models\taskAttached::where('task_id',$id)->where('network_attach',1)->count();
		$counts = $count + 1;
		$task_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $data_img->task_name); 
		if($data_img->task_week != 0)
		{
			$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$data_img->task_week)->first();
			$filename = $task_name.' - Week '.$week_details->week.' - '.$counts;
		}
		else{
			$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$data_img->task_month)->first();
			$filename = $task_name.' - Month '.$month_details->month.' - '.$counts;
		}
		$contents = $request->get('notepad_contents');
		$upload_dir = 'uploads/task_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);
		$data['task_id'] = $data_img->task_id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['network_attach'] = 1;
		\App\Models\taskAttached::insert($data);
		$dataval['task_started'] = 1;
		$dataval['task_notify'] = 1;
		\App\Models\task::where('task_id',$id)->update($dataval);
		if($data_img->task_week != 0)
		{
			//return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);
		}
		else{
			//return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
		}
	}
	public function task_delete_image(Request $request)
	{
		$imgid = $request->get('imgid');
		$check_network = \App\Models\taskAttached::where('id',$imgid)->first();
		\App\Models\taskAttached::where('id',$imgid)->delete();
		if($check_network->network_attach == 1)
		{
			$count = \App\Models\taskAttached::where('task_id',$check_network->task_id)->where('network_attach',1)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['task_started'] = 0;
				$dataval['task_notify'] = 0;
				\App\Models\task::where('task_id',$check_network->task_id)->update($dataval);
			}
		}
		elseif($check_network->network_attach == 0)
		{
			$count = \App\Models\taskAttached::where('task_id',$check_network->task_id)->where('network_attach',0)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['liability'] = '';
				\App\Models\task::where('task_id',$check_network->task_id)->update($dataval);
				$get_week_month = \App\Models\task::where('task_id',$check_network->task_id)->first();
				if(($get_week_month))
				{
					if($get_week_month->task_week != 0)
					{
						$get_week_name = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$get_week_month->task_week)->first();
						$dataupdate['period'] = 'week'.$get_week_name->week;
						$dataupdate['year_id'] = $get_week_month->task_year;
						$dataupdate['task_enumber'] = $get_week_month->task_enumber;
						$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
						$dataupdate['value'] = $sum;
						$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
						if(($check_update))
						{
							\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
						}
						else{
							\App\Models\payeP30TaskUpdate::insert($dataupdate);
						}
					}
					else{
						$get_month_name = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$get_week_month->task_month)->first();
						$dataupdate['period'] = 'month'.$get_month_name->month;
						$dataupdate['year_id'] = $get_week_month->task_year;
						$dataupdate['task_enumber'] = $get_week_month->task_enumber;
						$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
						$dataupdate['value'] = $sum;
						$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
						if(($check_update))
						{
							\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
						}
						else{
							\App\Models\payeP30TaskUpdate::insert($dataupdate);
						}
					}
				}
			}
		}
	}
	public function task_delete_all_image(Request $request)
	{
		$taskid = $request->get('taskid');
		\App\Models\taskAttached::where('task_id',$taskid)->where('network_attach',0)->delete();
		\App\Models\task::where('task_id',$taskid)->update(['liability' => ""]);
		$get_week_month = \App\Models\task::where('task_id',$taskid)->first();
		if(($get_week_month))
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(($check_update))
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
			else{
				$get_month_name = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = \App\Models\task::where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = \App\Models\payeP30TaskUpdate::where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(($check_update))
				{
					\App\Models\payeP30TaskUpdate::where('id',$check_update->id)->update($dataupdate);
				}
				else{
					\App\Models\payeP30TaskUpdate::insert($dataupdate);
				}
			}
		}
	}
	public function task_delete_all_image_attachments(Request $request)
	{
		$taskid = $request->get('taskid');
		\App\Models\taskAttached::where('task_id',$taskid)->where('network_attach',1)->delete();
		$dataval['task_started'] = 0;
		$dataval['task_notify'] = 0;
		\App\Models\task::where('task_id',$taskid)->update($dataval);
	}
	public function task_status_update(Request $request)
	{
		$id = $request->get('id');	
		$status = $request->get('status');
		\App\Models\task::where('task_id',$id)->update(['task_status' => $status,'updatetime' => date('Y-m-d H:i:s')]);
		$details = \App\Models\task::where('task_id',$id)->first();
		if($status == 1){
			$payroll = \App\Models\task::where('task_id', $id)->first();
			if($payroll->client_id != ''){
				$data['client_id'] = $payroll->client_id;
				$data['task_id'] = $payroll->task_id;
				$data['year'] = $payroll->task_year;
				$data['week'] = $payroll->task_week;
				$data['month'] = $payroll->task_month;
				$data['email_sent'] = $payroll->last_email_sent;
				\App\Models\payrollTasks::insert([$data]);
			}
		}
      $seperatedate = explode(' ',$details->updatetime);
      $explodedate = explode('-',$seperatedate[0]);
      $explodetime = explode(':',$seperatedate[1]);
      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];
      $time = $explodetime[0].':'.$explodetime[1];
      echo json_encode(["date" => $date,"time" => $time]);
	}
	public function get_week_by_year(Request $request)
	{
		$id = $request->get('id');
		$year = $request->get('year');
		$weeks = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year', $year)->where('week_closed','=', '0000-00-00 00:00:00')->get();
		$output = '<h5 style="font-weight:800">CHOOSE WEEK : </h5>';
		$output.='<ul>';
            if(($weeks)){
              foreach($weeks as $week){
              	$output.='<li><a href="javascript:" class="week_button" data-element="'.$week->week_id.'">Week '.$week->week.'</a></li>';
              }
            }
            else{
              $output.='Week Not Found';
            }  
        $output.='</ul>';
        echo $output;
	}
	public function get_month_by_year(Request $request)
	{
		$id = $request->get('id');
		$year = $request->get('year');
		$months = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year', $year)->where('month_closed','=', '0000-00-00 00:00:00')->get();
		$output = '<h5 style="font-weight:800">CHOOSE MONTH : </h5>';
		$output.='<ul>';
            if(($months)){
              foreach($months as $month){
              	$output.='<li><a href="javascript:" class="month_button" data-element="'.$month->month_id.'">Month '.$month->month.'</a></li>';
              }
            }
            else{
              $output.='Month Not Found';
            }  
        $output.='</ul>';
        echo $output;
	}
	public function copy_task(Request $request)
	{
		$id = $request->get('hidden_task_id');
		$year = $request->get('hidden_copy_year');
		$week = $request->get('hidden_copy_week');
		$month = $request->get('hidden_copy_month');
		$category = $request->get('category_type_copy');
		$taskdetails = \App\Models\task::where('task_id',$id)->first();
		if($taskdetails->task_created_id == 0) { $data['task_created_id'] = $taskdetails->task_id; }
		else{ $data['task_created_id'] = $taskdetails->task_created_id; }
		$data['task_year'] = $year;
		$data['task_week'] = ($week != '')?$week:0;
		$data['task_month'] = ($month != '')?$month:0;
		$data['task_name'] = $taskdetails->task_name;
		$data['task_classified'] = $category;
		$data['enterhours'] = ($taskdetails->enterhours == 2)?2:0;
		$data['holiday'] = ($taskdetails->holiday == 2)?2:0;
		$data['process'] = ($taskdetails->process == 2)?2:0;
		$data['payslips'] = ($taskdetails->payslips == 2)?2:0;
		$data['email'] = ($taskdetails->email == 2)?2:0;
		$data['upload'] = ($taskdetails->upload == 2)?2:0;
		$data['task_email'] = $taskdetails->task_email;
		$data['secondary_email'] = $taskdetails->secondary_email;
		$data['salutation'] = $taskdetails->salutation;
		$data['comments'] = $taskdetails->comments;
		$data['attached'] = $taskdetails->attached;
		$data['network'] = $taskdetails->network;
		$data['task_enumber'] = $taskdetails->task_enumber;
		$data['task_status'] = 0;
		$data['client_id'] = $taskdetails->client_id;
		$data['task_enumber'] = $taskdetails->task_enumber;
		$data['tasklevel'] = $taskdetails->tasklevel;
		$data['p30_pay'] = $taskdetails->p30_pay;
		$data['p30_email'] = $taskdetails->p30_email;
		$data['default_staff'] = $taskdetails->default_staff;
		$data['disclose_liability'] = $taskdetails->disclose_liability;
		$data['distribute_email'] = $taskdetails->distribute_email;
		\App\Models\task::where('task_id',$id)->insert([$data]);
		if($week != '')
		{
			return redirect('user/select_week/'.base64_encode($week))->with('message','Task Copied Successfully');
		}
		elseif($month != '')
		{
			return redirect('user/select_month/'.base64_encode($month))->with('message','Task Copied Successfully');
		}
	}
	public function notify_tasks(Request $request)
	{
		$id = $_GET['id'];
		$value = $_GET['value'];
		$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\taskYear::where('taskyear', $year_id->year_id)->first();
		$result_task = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',$value)->get();
		$output = '<table class="table_bg own_table_white" style="width:100%">';
		$ids = [];
		if(($result_task))
		{
			$output.= '<tr><td>Task Name</td><td>Request</td><td>Primary Email</td><td>Secondary Email</td><td>Emails Sent</td></tr>';
			foreach($result_task as $task)
			{
				$last_email_sent = \App\Models\taskEmailSent::where('task_id',$task->task_id)->where('options','n')->orderBy('id','desc')->first();
				if(($last_email_sent))
				{
					$email_sent = date('d F Y H:i:s', strtotime($last_email_sent->email_sent));
				}
				else{
					$email_sent = '';
				}
				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_complete_period == 1) { $task_label='style="color:#1b0fd4 !important;font-weight:800;text-align:left"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 
				$output.='<tr class="req_pay_'.$task->task_id.'">
					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';
					if($task->task_status == 1 || $task->task_started == 1 || $task->task_complete_period == 1)
					{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';
					}
					else{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';
						array_push($ids,$task->task_id);
					}
				$output.='</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;">'.$email_sent.'</td>
				</tr>';
			}
			$output.='';
		}
		$output.='</table>
		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$pms_admin_details->notify_message.'</textarea>';
		$taskids = implode(',',$ids);
		echo json_encode(array("output" => $output, 'task_ids' => $taskids));
	}
	public function notify_tasks_month(Request $request)
	{
		$id = $_GET['id'];
		$value = $_GET['value'];
		$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\taskYear::where('taskyear', $year_id->year_id)->first();
		$result_task = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$value)->get();
		$output = '<table class="table_bg own_table_white" style="width:100%">';
		$ids = [];
		if(($result_task))
		{
			$output.= '<tr><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td><td>Emails Sent</td></tr>';
			foreach($result_task as $task)
			{
				$last_email_sent = \App\Models\taskEmailSent::where('task_id',$task->task_id)->where('options','n')->orderBy('id','desc')->first();
				if(($last_email_sent))
				{
					$email_sent = date('d F Y H:i:s', strtotime($last_email_sent->email_sent));
				}
				else{
					$email_sent = '';
				}
				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_complete_period == 1) { $task_label='style="color:#1b0fd4 !important;font-weight:800;text-align:left"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 
				$output.='<tr class="req_pay_'.$task->task_id.'">
					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';
					if($task->task_status == 1 || $task->task_started == 1 || $task->task_complete_period == 1)
					{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';
					}
					else{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';
						array_push($ids,$task->task_id);
					}
				$output.='</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;">'.$email_sent.'</td>
				</tr>';
			}
			$output.='';
		}
		$output.='</table>
		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$pms_admin_details->notify_message.'</textarea>
		';
		$taskids = implode(',',$ids);
		echo json_encode(array("output" => $output, 'task_ids' => $taskids));
	}
	public function email_unsent_files(Request $request)
	{
		$task_id = $request->get('email_task_id_value');
		$from_input = $request->get('select_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = $request->get('to_user').','.$request->get('cc_unsent');
		$sentmails = $request->get('to_user').', '.$request->get('cc_unsent');
		$subject = $request->get('subject_unsent'); 
		$message = $request->get('message_editor');
		$attachments = $request->get('check_attachment');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		$taskss_details = \App\Models\task::where('task_id',$task_id)->first();
		if($taskss_details->distribute_email == 1){
			if(($explode))
			{
				foreach($explode as $exp)
				{
					$to = trim($exp);
					$data['logo'] = getEmailLogo('pms');
					$data['message'] = $message;
					$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
	    			$data['signature'] = $pms_admin_details->payroll_signature;
					$contentmessage = view('user/email_share_paper', $data);
					$email = new PHPMailer();
					$email->CharSet = 'UTF-8';
					$email->SetFrom($from, $user_name); //Name is optional
					$email->Subject   = utf8_decode($subject);
					$email->Body      = $contentmessage;
					$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
					$email->IsHTML(true);
					$email->AddAddress( $to );
					$email->Send();
				}
				$date = date('Y-m-d H:i:s');
				$task_details = \App\Models\task::where('task_id',$task_id)->first();
				if(($task_details))
				{
					if($task_details->client_id != "")
					{
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$task_details->client_id)->first();
						$datamessage['message_id'] = time();
						$datamessage['message_from'] = $from_input;
						$datamessage['subject'] = $subject;
						$datamessage['message'] = $message;
						$datamessage['client_ids'] = $task_details->client_id;
						$datamessage['primary_emails'] = $client_details->email;
						$datamessage['secondary_emails'] = $client_details->email2;
						$datamessage['date_sent'] = date('Y-m-d H:i:s');
						$datamessage['date_saved'] = date('Y-m-d H:i:s');
						$datamessage['source'] = "PMS SYSTEM";
						$datamessage['attachments'] = "";
						$datamessage['status'] = 1;
						$datamessage['practice_code'] = Session::get('user_practice_code');
						\App\Models\Messageus::insert($datamessage);
					}
				}
				\App\Models\task::where('task_id',$task_id)->update(['last_email_sent' => $date]);
				$task_details = \App\Models\task::where('task_id',$task_id)->first();
				$last_email_date['task_id'] = $task_id;
				$last_email_date['task_created_id'] = $task_details->task_created_id;
				$last_email_date['email_sent'] = $date;
				$last_email_date['options'] = ($request->get('email_sent_option')!= "")?$request->get('email_sent_option'):'0';
				$last_email_date['task_week'] = $task_details->task_week;
				$last_email_date['task_month'] = $task_details->task_month;
				\App\Models\taskEmailSent::insert($last_email_date);
				$result = \App\Models\task::where('task_id',$task_id)->first();
				if(($result))
				{
					if($result->last_email_sent != '0000-00-00 00:00:00')
	                {
	                    $date = date('d F Y', strtotime($result->last_email_sent));
	                    $time = date('H : i', strtotime($result->last_email_sent));
	                    $last_date = '<p>'.$date.' @ '.$time.'</p>';
	                }
	                else{
	                  $last_date = '';
	                }
				}
				else{
					$last_date = '';
				}
				echo $last_date.'||'.$task_id;
			}
			else{
				echo "1";
			}
		}
		else{
			if(($attachments))
			{
				if(($explode))
				{
					foreach($explode as $exp)
					{
						$to = trim($exp);
						$data['logo'] = getEmailLogo('pms');
						$data['message'] = $message;
						$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
		    			$data['signature'] = $pms_admin_details->payroll_signature;
						$contentmessage = view('user/email_share_paper', $data);
						$email = new PHPMailer();
						$email->CharSet = 'UTF-8';
						$email->SetFrom($from, $user_name); //Name is optional
						$email->Subject   = $subject;
						$email->Body      = $contentmessage;
						$email->set('MIME-Version', '1.0');
						$email->set('Content-Type', 'text/html; charset=UTF-8');
						$email->IsHTML(true);
						$email->AddAddress( $to );
						$attach = '';
						foreach($attachments as $attachment)
						{
							$attachment_details = \App\Models\taskAttached::where('id',$attachment)->first();
							$path = $attachment_details->url.'/'.$attachment_details->attachment;
							$email->AddAttachment( $path , $attachment_details->attachment );
							\App\Models\taskAttached::where('id',$attachment)->update(['status' => 1]);
							$task_id = $attachment_details->task_id;
							if($attach == "")
							{
								$attach = $path;
							}
							else{
								$attach = $attach.'||'.$path;
							}
						}
						$email->Send();
					}
					$date = date('Y-m-d H:i:s');
					$task_details = \App\Models\task::where('task_id',$task_id)->first();
					if(($task_details))
					{
						if($task_details->client_id != "")
						{
							$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$task_details->client_id)->first();
							$datamessage['message_id'] = time();
							$datamessage['message_from'] = $from_input;
							$datamessage['subject'] = $subject;
							$datamessage['message'] = $message;
							$datamessage['client_ids'] = $task_details->client_id;
							$datamessage['primary_emails'] = $client_details->email;
							$datamessage['secondary_emails'] = $client_details->email2;
							$datamessage['date_sent'] = date('Y-m-d H:i:s');
							$datamessage['date_saved'] = date('Y-m-d H:i:s');
							$datamessage['source'] = "PMS SYSTEM";
							$datamessage['attachments'] = $attach;
							$datamessage['status'] = 1;
							$datamessage['practice_code'] = Session::get('user_practice_code');
							\App\Models\Messageus::insert($datamessage);
						}
					}
					\App\Models\task::where('task_id',$task_id)->update(['last_email_sent' => $date]);
					$task_details = \App\Models\task::where('task_id',$task_id)->first();
					$last_email_date['task_id'] = $task_id;
					$last_email_date['task_created_id'] = $task_details->task_created_id;
					$last_email_date['email_sent'] = $date;
					$last_email_date['options'] = ($request->get('email_sent_option')!= "")?$request->get('email_sent_option'):'0';
					$last_email_date['task_week'] = $task_details->task_week;
					$last_email_date['task_month'] = $task_details->task_month;
					\App\Models\taskEmailSent::insert($last_email_date);
					$result = \App\Models\task::where('task_id',$task_id)->first();
					if(($result))
					{
						if($result->last_email_sent != '0000-00-00 00:00:00')
		                {
		                  $get_dates = \App\Models\taskEmailSent::where('task_id',$result->task_id)->where('options','!=','n')->get();
		                  $last_date = '';
		                  if(($get_dates))
		                  {
		                    foreach($get_dates as $dateval)
		                    {
		                      $date = date('d F Y', strtotime($dateval->email_sent));
		                      $time = date('H : i', strtotime($dateval->email_sent));
		                      if($dateval->options != '0')
		                      {
		                        if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
		                        elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
		                        elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
		                        else{ $text= ''; }
		                        $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
		                      }
		                      else{
		                        $itag = '';
		                      }
		                      if($last_date == "")
		                      {
		                        $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
		                      }
		                      else{
		                        $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
		                      }
		                    }
		                  }
		                  else{
		                    $date = date('d F Y', strtotime($result->last_email_sent));
		                    $time = date('H : i', strtotime($result->last_email_sent));
		                    $last_date = '<p>'.$date.' @ '.$time.'</p>';
		                  }
		                }
		                else{
		                  $last_date = '';
		                }
					}
					else{
						$last_date = '';
					}
					echo $last_date.'||'.$task_id;
				}
				else{
					echo "1";
				}
			}
			else{
				echo "2";
			}
		}
	}
	public function email_report_send(Request $request, $id='')
	{
		$type = $request->get('type');
		$week = $request->get('week');
		$month = $request->get('month');
		$year = $request->get('year');
		$hidden_report_type = $request->get('hidden_report_type');
		$from_input = $request->get('select_user_report');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = $request->get('to_user_report').','.$request->get('cc_report');
		$sentemails = $request->get('to_user_report').', '.$request->get('cc_report');
		$subject = $request->get('subject_report'); 
		$message = $request->get('message_report');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentemails;
		if(($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = getEmailLogo('pms');
				$data['message'] = $message;
				$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
	    		$data['signature'] = $pms_admin_details->payroll_signature;
				$contentmessage = view('user/email_share_paper', $data);
				$email = new PHPMailer();
				$email->CharSet = 'UTF-8';
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->set('MIME-Version', '1.0');
				$email->set('Content-Type', 'text/html; charset=UTF-8');
				$email->IsHTML(true);
				$email->AddAddress( $to );
				if($hidden_report_type == "task_report")
				{
					if($type == 'week')
					{
						$path = 'public/papers/Task_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
						$pdfname = 'Task_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
					}
					elseif($type == 'month'){
						$path = 'public/papers/Task_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
						$pdfname = 'Task_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
					}
				}
				elseif($hidden_report_type == "notify_report"){
					if($type == 'week')
					{
						$path = 'public/papers/Notify_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
						$pdfname = 'Notify_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
					}
					elseif($type == 'month'){
						$path = 'public/papers/Notify_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
						$pdfname = 'Notify_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
					}
				}
				$email->AddAttachment( $path , $pdfname );
				$email->Send();	
			}
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
		return Redirect::back()->with('message', 'Email Sent Successfully');
	}
	public function email_report_pdf(Request $request){
		$id = $request->get('id');
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');
	   		 	$subject = 'Easypayroll.ie: Task Report For Year '.$year_id->year_name.' Week'.$year->week.'';	
	   		 	echo $subject;
	}
	public function email_notify_pdf(Request $request){
		$week = $request->get('week');
		$month = $request->get('month');
		$time = $request->get('timeval');
		$email_id = explode("||",$request->get('email'));
		$email = $email_id[0];
		$id = $email_id[1];
		$message = $request->get('message');
		$user_details =\App\Models\User::where('user_id',Session::get('userid'))->first();
		$pmsadmin_details = \App\Models\PmsAdmin::where('practice_code',Session::get('user_practice_code'))->first();
		$admin_cc = $pmsadmin_details->payroll_cc_email;
		$from = $user_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = getEmailLogo('pms');
		$data['message'] = $message;
	    $data['signature'] = $pmsadmin_details->payroll_signature;
		$contentmessage = view('user/email_notify', $data);
		$subject = 'Easypayroll.ie: Notification';
		$email = new PHPMailer();
		$email->CharSet = 'UTF-8';
		if($to != '')
		{
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
		}
		if($week == "0")
		{
			$get_task_details = \App\Models\task::where('task_email',$to)->where('task_month',$month)->where('task_id',$id)->where('client_id','!=','')->first();
		}
		else{
			$get_task_details = \App\Models\task::where('task_email',$to)->where('task_week',$week)->where('task_id',$id)->where('client_id','!=','')->first();
		}
		if(($get_task_details))
		{
			$curr = date('Y-m-d H:i:s');
			$dataval['task_created_id'] = $get_task_details->task_id;
			$dataval['task_week'] = $get_task_details->task_week;
			$dataval['task_month'] = $get_task_details->task_month;
			$dataval['task_id'] = $get_task_details->task_id;
			$dataval['email_sent'] = $curr;
			$dataval['options'] = 'n';
			\App\Models\taskEmailSent::insert($dataval);
			if($get_task_details->client_id != "")
			{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$get_task_details->client_id)->first();
				$datamessage['message_id'] = $time;
				$datamessage['message_from'] = 0;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $get_task_details->client_id;
				$datamessage['primary_emails'] = $client_details->email;
				$datamessage['secondary_emails'] = $client_details->email2;
				$datamessage['date_sent'] = $curr;
				$datamessage['date_saved'] = $curr;
				$datamessage['source'] = "PMS SYSTEM";
				$datamessage['status'] = 1;
				$datamessage['practice_code'] = Session::get('user_practice_code');
				\App\Models\Messageus::insert($datamessage);
			}
		}
	}
	public function email_notify_tasks_pdf(Request $request)
	{
		$email = $request->get('email');
		$task_id = $request->get('task_id');
		$client_id = $request->get('clientid');
		$client_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$client_id)->first();
		$task_details = \App\Models\task::where('task_id',$task_id)->first();
		$year = \App\Models\Year::where('year_id',$task_details->task_year)->first();
		if($task_details->task_week != 0)
		{
			$week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$task_details->task_week)->first();
			$week_month = '<b>Week # : </b>'.$week->week;
		}
		else{
			$month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$task_details->task_month)->first();
			$week_month = '<b>Month # : </b>'.$month->month;
		}
		$year = \App\Models\Year::where('year_id',$task_details->task_year)->first();
		$user_details =\App\Models\User::where('user_id',Session::get('userid'))->first();
		$pmsadmin_details = \App\Models\PmsAdmin::where('practice_code',Session::get('user_practice_code'))->first();
		$admin_cc = $pmsadmin_details->payroll_cc_email;
		$from = $user_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = getEmailLogo('pms');
		$data['task_name'] = $task_details->task_name;
		$data['year'] = $year->year_name;
		$data['week_month'] = $week_month;
		$data['client_name'] = $client_details->firstname;
	    $data['signature'] = $pmsadmin_details->payroll_signature;
		$contentmessage = view('user/email_notify_tasks', $data);
		$subject = 'Easypayroll.ie: Payroll Task Notification';
		$email = new PHPMailer();
		$email->CharSet = 'UTF-8';
		if($to != '')
		{
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
			$email->AddCC($cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
		}
	}
	public function email_notify_pdf_month(Request $request){
		$id = $request->get('id');
		$commaval = $request->get('commaval');
		$explode = explode(',',$commaval);
		$type = $request->get('type');
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$type)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Checkbox</td>
				          </tr>
				        ';
		             if(($result_task)){
		                foreach ($result_task as $result) {
				            $output.= '<tr>';
				                $output.= '<td style="text-align: center;border:1px solid #000;"><label class="task_label">'.$result->task_name.'</label></td><td style="text-align: center;border:1px solid #000;">';
				                if(in_array($result->task_id,$explode))
				                {
				                	$output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                }
				                else{
				                	$output.='<input type="checkbox"><label >&nbsp;</label>';
				                }   
				            $output.='</td></tr>';
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td>
		                </tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Notify_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
	   		 	$subject = 'Easypayroll.ie: Notify Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
	   		 	echo $subject;
	}
	public function alltask_report_pdf(Request $request){
		$id = $request->get('id');
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');
	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function task_complete_report_pdf(Request $request){
		$id = $request->get('id');
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');
	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function task_incomplete_report_pdf(Request $request){
		$id = $request->get('id');
		$year = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id', $id)->first();
		$year_id = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');
	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function email_report_pdf_month(Request $request){
		$id = $request->get('id');
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
				$subject = 'Easypayroll.ie: Task Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
	   		 	echo $subject;
	}
	public function alltask_report_pdf_month(Request $request){
		$id = $request->get('id');
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function task_complete_report_pdf_month(Request $request){
		$id = $request->get('id');
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_completed = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.date('d-M-Y H:i', strtotime($result->updatetime)).'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');	
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function task_incomplete_report_pdf_month(Request $request){
		$id = $request->get('id');
		$year = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$year_id = \App\Models\Year::where('year_id', $year->year)->first();
		//$year_user =  \App\Models\Year::where('year_id', $year_id->year_id)->first();
		$result_task_incomplete = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = \App\Models\task::where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>
		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        ';
		             if(($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function close_create_new_week(Request $request, $id = '')
	{
		$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$id)->first();
		$check_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$week_details->year)->get();
		$yearname = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id',$week_details->year)->first();
		if(count($check_week) <= 52)
		{
			$count_weeks = $week_details->week + 1;
			$current = date('Y-m-d H:i:s');
			 \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$id)->update(['week_closed' => $current]);
			$check_weeks = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$week_details->year)->where('week',$count_weeks)->first();
			if(!($check_weeks))
			{
				$weekid = \App\Models\week::insertDetails(['year' => $week_details->year,'week' => $count_weeks,'practice_code' => Session::get('user_practice_code')]);
				$gettasks = \App\Models\task::where('task_year',$week_details->year)->where('task_week',$id)->get();
				if(($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						if($tasks->task_created_id == 0) { $data['task_created_id'] = $tasks->task_id; }
						else{ $data['task_created_id'] = $tasks->task_created_id; }
						$data['task_year'] = $tasks->task_year;
						$data['task_week'] = $weekid;
						$data['task_name'] = $tasks->task_name;
						$data['task_classified'] = $tasks->task_classified;
						$data['enterhours'] = ($tasks->enterhours == 2)?2:0;
						$data['holiday'] = ($tasks->holiday == 2)?2:0;
						$data['process'] = ($tasks->process == 2)?2:0;
						$data['payslips'] = ($tasks->payslips == 2)?2:0;
						$data['email'] = ($tasks->email == 2)?2:0;
						$data['upload'] = ($tasks->upload == 2)?2:0;
						$data['date'] = $tasks->date;
						$data['task_email'] = $tasks->task_email;
						$data['comments'] = $tasks->comments;
						$data['attached'] = $tasks->attached;
						$data['network'] = $tasks->network;
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;
						$data['client_id'] = $tasks->client_id;
						$data['last_email_sent_carry'] = $tasks->last_email_sent;
						$data['tasklevel'] = $tasks->tasklevel;
						$data['p30_pay'] = $tasks->p30_pay;
						$data['p30_email'] = $tasks->p30_email;
						$data['default_staff'] = $tasks->default_staff;
						$data['scheme_id'] = $tasks->scheme_id;
						$data['disclose_liability'] = $tasks->disclose_liability;
						$data['distribute_email'] = $tasks->distribute_email;
						if($tasks->bi_payroll == 1) {
							$data['bi_payroll'] = 1;
						}else {
							$data['bi_payroll'] = 0;
						}
						if($tasks->bi_payroll == 1 && $tasks->bi_payroll_next_status == 1) {
							$data['task_complete_period'] = 1;
							$data['task_complete_period_type'] = 1;
						}
						else{
							if($tasks->task_complete_period_type == 2){							
								$data['task_complete_period'] = 1;
								$data['task_complete_period_type'] = 2;
							}
							else{
								$data['task_complete_period'] = 0;
								$data['task_complete_period_type'] = 0;							
							}
						}
						$data['practice_code'] = Session::get('user_practice_code');
						$taskidnew = \App\Models\task::insertDetails($data);
						if($tasks->scheme_id > 0)
						{
							$scheme_det = \App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->where('id',$tasks->scheme_id)->first();
							if(($scheme_det))
							{
								$upload_dir = 'uploads/task_image';
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$upload_dir = $upload_dir.'/'.base64_encode($taskidnew);
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$myfile = fopen($upload_dir.'/'.$scheme_det->scheme_name.".txt", "w") or die("Unable to open file!");
								$txt = "This Payroll is to be run under the Scheme: ".$scheme_det->scheme_name."";
								fwrite($myfile, $txt);
								fclose($myfile);
								$datareceived['task_id'] = $taskidnew;
								$datareceived['attachment'] = $scheme_det->scheme_name.".txt";
								$datareceived['url'] = $upload_dir;
								$datareceived['network_attach'] = 1;
								$datareceived['copied'] = 0;
								\App\Models\taskAttached::insert($datareceived);
							}
						}
					}
				}
				return redirect('user/select_week/'.base64_encode($weekid))->with('message','New Week Created Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Sorry! the new week you are trying to create is already exists. Please click Ok to go to that week.<a href="'.URL::to('user/select_week/'.base64_encode($check_weeks->week_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_week">Cancel</a>');
			}
		}
		else{
			return Redirect::back()->with('error', 'This is the Last week of this year '.$yearname->year_name.' so please contact your Admin to create a new year');
		}
	}
	public function close_create_new_month(Request $request, $id = '')
	{
		$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$id)->first();
		$check_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$month_details->year)->get();
		$yearname = \App\Models\Year::where('year_id',$month_details->year)->first();
		if(count($check_month) <= 11)
		{
			$count_months = $month_details->month + 1;
			$current = date('Y-m-d H:i:s');
			\App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$id)->update(['month_closed' => $current]);
			$check_months = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$month_details->year)->where('month',$count_months)->first();
			if(!($check_months))
			{
				$monthid = \App\Models\Month::insertDetails(['year' => $month_details->year,'month' => $count_months,'practice_code' => Session::get('user_practice_code')]);
				$gettasks = \App\Models\task::where('task_year',$month_details->year)->where('task_month',$id)->get();
				if(($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						if($tasks->task_created_id == 0) { $data['task_created_id'] = $tasks->task_id; }
						else{ $data['task_created_id'] = $tasks->task_created_id; }
						$data['task_year'] = $tasks->task_year;
						$data['task_month'] = $monthid;
						$data['task_name'] = $tasks->task_name;
						$data['task_classified'] = $tasks->task_classified;
						$data['enterhours'] = ($tasks->enterhours == 2)?2:0;
						$data['holiday'] = ($tasks->holiday == 2)?2:0;
						$data['process'] = ($tasks->process == 2)?2:0;
						$data['payslips'] = ($tasks->payslips == 2)?2:0;
						$data['email'] = ($tasks->email == 2)?2:0;
						$data['upload'] = ($tasks->upload == 2)?2:0;
						$data['date'] = $tasks->date;
						$data['task_email'] = $tasks->task_email;
						$data['comments'] = $tasks->comments;
						$data['attached'] = $tasks->attached;
						$data['network'] = $tasks->network;
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;
						$data['client_id'] = $tasks->client_id;
						$data['last_email_sent_carry'] = $tasks->last_email_sent;
						$data['tasklevel'] = $tasks->tasklevel;
						$data['p30_pay'] = $tasks->p30_pay;
						$data['p30_email'] = $tasks->p30_email;
						$data['disclose_liability'] = $tasks->disclose_liability;
						$data['distribute_email'] = $tasks->distribute_email;
						$data['default_staff'] = $tasks->default_staff;
						$data['scheme_id'] = $tasks->scheme_id;
						if($tasks->bi_payroll == 1) {
							$data['bi_payroll'] = 1;
						} else {
							$data['bi_payroll'] = 0;
						}
						if($tasks->bi_payroll == 1 && $tasks->bi_payroll_next_status == 1) {
							$data['task_complete_period'] = 1;
							$data['task_complete_period_type'] = 1;
						}
						else{
							if($tasks->task_complete_period_type == 2){							
								$data['task_complete_period'] = 1;
								$data['task_complete_period_type'] = 2;
							}
							else{
								$data['task_complete_period'] = 0;
								$data['task_complete_period_type'] = 0;							
							}
						}
						$data['practice_code'] = Session::get('user_practice_code');
						$taskidnew = \App\Models\task::insertDetails($data);
						if($tasks->scheme_id > 0)
						{
							$scheme_det = \App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->where('id',$tasks->scheme_id)->first();
							if(($scheme_det))
							{
								$upload_dir = 'uploads/task_image';
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$upload_dir = $upload_dir.'/'.base64_encode($taskidnew);
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$myfile = fopen($upload_dir.'/'.$scheme_det->scheme_name.".txt", "w") or die("Unable to open file!");
								$txt = "This Payroll is to be run under the Scheme: ".$scheme_det->scheme_name."";
								fwrite($myfile, $txt);
								fclose($myfile);
								$datareceived['task_id'] = $taskidnew;
								$datareceived['attachment'] = $scheme_det->scheme_name.".txt";
								$datareceived['url'] = $upload_dir;
								$datareceived['network_attach'] = 1;
								$datareceived['copied'] = 0;
								\App\Models\taskAttached::insert($datareceived);
							}
						}
					}
				}
				return redirect('user/select_month/'.base64_encode($monthid))->with('message','New Month Created Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Sorry! the new month you are trying to create is already exists. Please click Ok to go to that month.<a href="'.URL::to('user/select_month/'.base64_encode($check_months->month_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_month">Cancel</a>');
			}
		}
		else{
			return Redirect::back()->with('error', 'This is the Last month of this year '.$yearname->year_name.' so please contact your Admin to create a new year');
		}
	}
	public function edit_task_name(Request $request)
	{
		$task_id = $request->get('task_id');
		$details = \App\Models\task::where('task_id',$task_id)->first();
		if($details->client_id != ''){
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $details->client_id)->first();
			if($client_details->company != "")
			{
				$company = $client_details->company;
			}
			else{
				$company = $client_details->firstname.' '.$client_details->surname;
			}
			$companyname = $company.'-'.$client_details->client_id;
			echo json_encode(["task_name" => $details->task_name,"task_email" => $details->task_email,"secondary_email" => $details->secondary_email,"salutation" => $details->salutation,"network" => $details->network,"category" => $details->task_classified,"task_id" => $details->task_id,'holiday' => $details->holiday,'process' => $details->process,'payslips' => $details->payslips,'email' => $details->email,'upload' => $details->upload,'enterhours' => $details->enterhours, 'enumber' => $details->task_enumber,'tasklevel' => $details->tasklevel,'p30_pay' => $details->p30_pay,'p30_email' => $details->p30_email, 'taxreg' => $client_details->tax_reg1, "primaryemail" => $client_details->email, "firstname" => $client_details->firstname, "companyname" => $companyname,"client_id" => $details->client_id]);
		}
		else{
			echo json_encode(["task_name" => $details->task_name,"task_email" => $details->task_email,"secondary_email" => $details->secondary_email,"salutation" => $details->salutation,"network" => $details->network,"category" => $details->task_classified,"task_id" => $details->task_id,'holiday' => $details->holiday,'process' => $details->process,'payslips' => $details->payslips,'email' => $details->email,'upload' => $details->upload,'enterhours' => $details->enterhours, 'enumber' => $details->task_enumber,'tasklevel' => $details->tasklevel,'p30_pay' => $details->p30_pay,'p30_email' => $details->p30_email,"client_id" => $details->client_id]);
		}
	}
	public function edit_email_unsent_files(Request $request)
	{
		$task_id = $request->get('task_id');
		$result = \App\Models\task::where('task_id',$task_id)->first();
		$files = '';
		$html = '';
		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		if($result->users != '')
		{
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$result->users)->first();
			$html.='<strong>On '.date('d-M-Y H:i').', '.$user_details->lastname.' '.$user_details->firstname.' wrote</strong><br/><br/>';  
		}
		else{
			$html.='<strong>On '.date('d-M-Y H:i').', wrote</strong><br/><br/>';  
		}
		$html.='<strong>'.$result->salutation.'</strong><br/>';  
	      $check_attached = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',0)->where('status',0)->get();
	      if(($check_attached))
	      {
	        $html.='<strong>Task Name :</strong> <spam>'.$result->task_name.'</spam>';  
	        if($result->disclose_liability == 1)
	        {
	        	if($result->liability == "")
	        	{
	        		$lia = '0.00';
	        	}
	        	else{
	        		$lia = $result->liability;
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        	}
	        	$html.='<p>The PAYE/PRSI/USC Liability for this periods payroll is: '.number_format_invoice_without_decimal($lia).'</p>';
	        }
	        $html.='<ul>';
	        foreach($check_attached as $attch)
	        {
	            $html.='<li>'.$attch->attachment.'</li>';
	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';
	        }
	        $html.='</ul>';
	      }
	      if($result->task_week != 0)
	      {
	      	$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$result->task_week)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
	      }
	      else{
	      	$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$result->task_month)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
	      }
	      if($result->secondary_email != '')
	      {
	      	$to_email = $result->task_email.','.$result->secondary_email;
	      }
	      else{
	      	$to_email = $result->task_email;
	      }
	     echo json_encode(["files" => $files,"html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function edit_email_unsent_files_distribute_by_link(Request $request)
	{
		$task_id = $request->get('task_id');
		$result = \App\Models\task::where('task_id',$task_id)->first();
		$files = '';
		$html = '';
		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		$pms_admin_details = \App\Models\PmsAdmin::where('practice_code', Session::get('user_practice_code'))->first();
		$html = $pms_admin_details->distribute_link;
		if($result->task_week != 0)
		{
			$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$result->task_week)->first();
			$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
		}
		else{
			$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$result->task_month)->first();
			$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
		}
		if($result->secondary_email != '')
		{
			$to_email = $result->task_email.','.$result->secondary_email;
		}
		else{
			$to_email = $result->task_email;
		}
	     echo json_encode(["html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function resendedit_email_unsent_files(Request $request)
	{
		$task_id = $request->get('task_id');
		$result = \App\Models\task::where('task_id',$task_id)->first();
		$files = '';
		$html = '';
		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		if($result->users != '')
		{
			$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$result->users)->first();
			$html.='<strong>On '.date('d-M-Y H:i').', '.$user_details->lastname.' '.$user_details->firstname.' wrote</strong><br/><br/>';  
		}
		else{
			$html.='<strong>On '.date('d-M-Y H:i').', wrote</strong><br/><br/>';  
		}
		$html.='<strong>'.$result->salutation.'</strong><br/>';  
	      $check_attached = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',0)->where('status',1)->get();
	      if(($check_attached))
	      {
	        $html.='<strong>Task Name :</strong> <spam>'.$result->task_name.'</spam>';  
	        if($result->disclose_liability == 1)
	        {
	        	if($result->liability == "")
	        	{
	        		$lia = '0.00';
	        	}
	        	else{
	        		$lia = $result->liability;
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        	}
	        	$html.='<p>The PAYE/PRSI/USC Liability for this periods payroll is: '.number_format_invoice_without_decimal($lia).'</p>';
	        }
	        $html.='<ul>';
	        foreach($check_attached as $attch)
	        {
	            $html.='<li>'.$attch->attachment.'</li>';
	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';
	        }
	        $html.='</ul>';
	      }
	      if($result->task_week != 0)
	      {
	      	$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$result->task_week)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
	      }
	      else{
	      	$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$result->task_month)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
	      }
	      if($result->secondary_email != '')
	      {
	      	$to_email = $result->task_email.','.$result->secondary_email;
	      }
	      else{
	      	$to_email = $result->task_email;
	      }
	     echo json_encode(["files" => $files,"html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function edit_task_details(Request $request)
	{
		$id = $request->get('hidden_taskname_id');
		$task_name = $request->get('task_name');
		$task_network = $request->get('task_network');
		$task_category = $request->get('task_category');
		$task_email = $request->get('task_email_edit');
		$secondary_email = $request->get('secondary_email_edit');
		$salutation = $request->get('salutation_edit');
		$enterhours = $request->get('enterhours_edit');
		$holiday = $request->get('holiday_edit');
		$process = $request->get('process_edit');
		$payslips = $request->get('payslips_edit');
		$email = $request->get('email_edit');
		$upload = $request->get('uploadd_edit');
		$enumber = trim($request->get('enumber'));
		$tasklevel =  trim($request->get('tasklevel_edit'));
		$p30_pay =  trim($request->get('pay_p30_edit'));
		$p30_email =  trim($request->get('email_p30_edit'));
		$clientid = $request->get('hidden_client_id_edit');
		$details = \App\Models\task::where('task_id',$id)->first();
		if($details->enterhours == 2)
		{
			if($enterhours == 0)
			{
				$enterhours = 0;
			}
			else{
				$enterhours = $details->enterhours;
			}
		}
		else{
			if($enterhours == 2)
			{
				$enterhours = 2;
			}
			else{
				$enterhours = $details->enterhours;
			}
		}
		if($details->holiday == 2)
		{
			if($holiday == 0)
			{
				$holiday = 0;
			}
			else{
				$holiday = $details->holiday;
			}
		}
		else{
			if($holiday == 2)
			{
				$holiday = 2;
			}
			else{
				$holiday = $details->holiday;
			}
		}
		if($details->process == 2)
		{
			if($process == 0)
			{
				$process = 0;
			}
			else{
				$process = $details->process;
			}
		}
		else{
			if($process == 2)
			{
				$process = 2;
			}
			else{
				$process = $details->process;
			}
		}
		if($details->payslips == 2)
		{
			if($payslips == 0)
			{
				$payslips = 0;
			}
			else{
				$payslips = $details->payslips;
			}
		}
		else{
			if($payslips == 2)
			{
				$payslips = 2;
			}
			else{
				$payslips = $details->payslips;
			}
		}
		if($details->email == 2)
		{
			if($email == 0)
			{
				$email = 0;
			}
			else{
				$email = $details->email;
			}
		}
		else{
			if($email == 2)
			{
				$email = 2;
			}
			else{
				$email = $details->email;
			}
		}
		if($details->upload == 2)
		{
			if($upload == 0)
			{
				$upload = 0;
			}
			else{
				$upload = $details->upload;
			}
		}
		else{
			if($upload == 2)
			{
				$upload = 2;
			}
			else{
				$upload = $details->upload;
			}
		}
		\App\Models\task::where('task_id',$id)->update(['task_name'=>$task_name,'task_email' => $task_email,'secondary_email' => $secondary_email,'salutation' => $salutation,'network' => $task_network,'task_classified' => $task_category,'enterhours' => $enterhours,'holiday' => $holiday,'process' => $process,'payslips' => $payslips,'email' => $email,'upload' => $upload, 'task_enumber' => $enumber,'tasklevel' =>$tasklevel,'p30_email' => $p30_email,'p30_pay' => $p30_pay,'client_id' => $clientid]);
		$emp_client = $request->get('hidden_client_emp_edit');
		$saluation_client = $request->get('hidden_client_salutation_edit');
		$clientid = $request->get('hidden_client_id_edit');
		if($emp_client == 1)
		{
			$data['employer_no'] = trim($request->get('enumber'));
		}
		if($saluation_client == 1)
		{
			$data['salutation'] = trim($request->get('salutation_edit'));
		}
		if($emp_client == 1 || $saluation_client == 1)
		{
			\App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->update($data);
		}
		return Redirect::back()->with('message', 'Task Name and Network Updated successfully');
	}
	public function downloadpdf(Request $request)
	{
		$filepath = $_GET['filename'];
		//header("Content-Type: application/pdf");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Content-Length: '.filesize($filepath));
		//ob_clean();
		//flush();
		try{
			$page = file_get_contents($filepath);
			echo $page;
			header('Set-Cookie: fileDownload=true; path=/');
		}catch(Exception $e){
			header('Set-Cookie: fileDownload=false; path=/');	
		}
		exit;
	}
	public function update_incomplete_status(Request $request)
	{
		$data['week_incomplete'] = $request->get('value');
		\App\Models\userLogin::where('userid',1)->update($data);
	}
	public function update_incomplete_status_month(Request $request)
	{
		$data['month_incomplete'] = $request->get('value');
		\App\Models\userLogin::where('userid',1)->update($data);
	}
	public function vatclients(Request $request)
	{
		$clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->get();
		$vat_only_active_clients = DB::table('vat_clients')
            ->join('cm_clients', 'vat_clients.cm_client_id', '=', 'cm_clients.client_id')
            ->select("vat_clients.*", "cm_clients.status as cm_status")
            ->where('cm_clients.active', '=', 2)
            ->where('vat_clients.status', '=', 0)
            ->get();
		return view('user/vatclients', array('clientlist' => $clients, 'vatOnlyClient' => $vat_only_active_clients));
	}
	public function vat_review(Request $request)
	{
		$clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->get();
		return view('user/vat_review', array('clientlist' => $clients));
	}
	public function load_all_vat_clients(Request $request)
	{
		$clients =\App\Models\vatClients::where('practice_code', Session::get('user_practice_code'))->get();
		$output = '';
		$prev_no_sub_due = 0;
		$curr_no_sub_due = 0;
		$next_no_sub_due = 0;
		$prev_no_sub_os = 0;
		$curr_no_sub_os = 0;
		$next_no_sub_os = 0;
		$prev_no_sub = 0;
		$curr_no_sub = 0;
		$next_no_sub = 0;
		$dummy_prev_month = date('m-Y', strtotime('first day of previous month'));
        $dummy_curr_month = date('m-Y');
        $dummy_next_month = date('m-Y', strtotime('first day of next month'));
        $prev_full_approved = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_prev_month)->where('vat_reviews.approve_status',1)->pluck('vat_reviews.client_id')->toArray();

		$prev_submitted = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_prev_month)->where('vat_reviews.type',3)->pluck('vat_reviews.client_id')->toArray();

		$prev_not_submitted_approved=array_diff($prev_full_approved,$prev_submitted);
		$curr_full_approved = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_curr_month)->where('vat_reviews.approve_status',1)->pluck('vat_reviews.client_id')->toArray();


		$curr_submitted = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_curr_month)->where('vat_reviews.type',3)->pluck('vat_reviews.client_id')->toArray();

		$curr_not_submitted_approved=array_diff($curr_full_approved,$curr_submitted);
		$next_full_approved = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_next_month)->where('vat_reviews.approve_status',1)->pluck('vat_reviews.client_id')->toArray();

		$next_submitted = \App\Models\vatReviews::join('vat_clients','vat_clients.client_id','=','vat_reviews.client_id')->where('vat_clients.practice_code',Session::get('user_practice_code'))->where('vat_reviews.month_year',$dummy_next_month)->where('vat_reviews.type',3)->pluck('vat_reviews.client_id')->toArray();
		
		$next_not_submitted_approved=array_diff($next_full_approved,$next_submitted);
		$prev_approval_text = count($prev_full_approved).' / '.count($prev_not_submitted_approved);
		$curr_approval_text = count($curr_full_approved).' / '.count($curr_not_submitted_approved);
		$next_approval_text = count($next_full_approved).' / '.count($next_not_submitted_approved);
		if(($clients))
		{
			foreach($clients as $client)
			{
				$deactivated_client = '';
				if($client->cm_client_id != "")
				{
					$cm_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->cm_client_id)->first();
					if(($cm_clients))
					{
						if($cm_clients->active == "2")
						{
							$deactivated_client= 'deactivated_tr';
						}
					}
				}
				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}
                $prev_month = date('m-Y', strtotime('first day of previous month'));
                $curr_month = date('m-Y');
                $next_month = date('m-Y', strtotime('first day of next month'));
                $prev_attachment_div = '';
                $prev_refresh_file = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_t1_value = '';
				$prev_t2_value = '';
				$prev_approve_status = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';
				$prev_comments = '';
				$curr_attachment_div = '';
				$curr_refresh_file = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_t1_value = '';
				$curr_t2_value = '';
				$curr_approve_status = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$curr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';
				$curr_comments = '';
				$next_attachment_div = '';
				$next_refresh_file = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_t1_value = '';
				$next_t2_value = '';
				$next_approve_status = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$next_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';
				$next_comments = '';
				$latest_import_id = '';
				$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
            	if(($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}
                $check_reviews_prev = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$prev_month)->get();
                if(($check_reviews_prev))
                {
                	$i= 0; $j=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prev_month.'"></a></p>'; 
                			$prev_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($prev->url.'/'.$prev->filename).'" data-client="'.$client->client_id.'" data-month="'.$prev_month.'"></a>';
                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}
                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }
                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$prev_no_sub = $prev_no_sub + 1;
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right"></a>';
                		}
                		if($prev->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$prev->textval)->first();
                			if(($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$prev->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($prev->type == 6){
                			$prev_checked = 'checked';
                		} 
                		if($prev->type == 7){
                			$prev_t1_value = $prev->t1_value;
                			$prev_t2_value = $prev->t2_value;
                		}
                		if($prev->type == 8){
                			$prev_approve_status = $prev->approve_status;
                		}
                		if($prev->type == 9){
                			$prev_comments = $prev->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			$prev_color_status = 'red_import'; 
                			$prev_color_text = 'Submission O/S';
                			$prev_check_box_color = 'os_td';
                			$prev_no_sub_os = $prev_no_sub_os + 1;
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$curr_month)->get();
                if(($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$curr_month.'"></a></p>'; 
                			$curr_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($curr->url.'/'.$curr->filename).'" data-client="'.$client->client_id.'" data-month="'.$curr_month.'"></a>';
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$curr_no_sub = $curr_no_sub + 1;
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$curr_month.'" style="float:right"></a>';
                		}
                		if($curr->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
                			if(($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$curr->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($curr->type == 7){
                			$curr_t1_value = $curr->t1_value;
                			$curr_t2_value = $curr->t2_value;
                		}
                		if($curr->type == 8){
                			$curr_approve_status = $curr->approve_status;
                		}
                		if($curr->type == 9){
                			$curr_comments = $curr->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			$curr_color_status = 'orange_import'; 
                			$curr_color_text = 'Submission Due';
                			$curr_check_box_color = 'due_td';
                			$curr_no_sub_due = $curr_no_sub_due + 1;
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_next = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$next_month)->get();
                if(($check_reviews_next))
                {
                	$i= 0; $j=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$next_month.'"></a></p>'; 
                			$next_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($next->url.'/'.$next->filename).'" data-client="'.$client->client_id.'" data-month="'.$next_month.'"></a>';
                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}
                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }
                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$next_no_sub = $next_no_sub + 1;
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$next_month.'" style="float:right"></a>';
                		}
                		if($next->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$next->textval)->first();
                			if(($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$next->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                		}
                		if($next->type == 7){
                			$next_t1_value = $next->t1_value;
                			$next_t2_value = $next->t2_value;
                		}
                		if($next->type == 8){
                			$next_approve_status = $next->approve_status;
                		}
                		if($next->type == 9){
                			$next_comments = $next->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			$next_color_status = 'white_import'; 
                			$next_color_text = 'Not Due';
                			$next_check_box_color = 'not_due_td';
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }
                $email_sent_count_prev = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $prev_month)->count();
                $email_sent_count_curr = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $curr_month)->count();
                $email_sent_count_next = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $next_month)->count();
				$output.='<tr class="tasks_tr tasks_tr_'.$client->client_id.' '.$deactivated_client.'">
					<td style="color:'.$fontcolor.'" class="sno_sort_val"><a href="javascript:" class="vat_client_class" data-element="'.$client->client_id.'" data-code="'.$client->cm_client_id.'" data-client="'.$client->name.'">'.$client->cm_client_id.'</a><br>
					<img class="active_client_list_tm1" data-iden="'.$client->cm_client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
					<td style="color:'.$fontcolor.'" class="client_sort_val"><a href="javascript:" class="vat_client_class" data-element="'.$client->client_id.'" data-code="'.$client->cm_client_id.'" data-client="'.$client->name.'">'.$client->name.'</a></td>
					<td style="color:'.$fontcolor.'" class="tax_sort_val">'.$client->taxnumber.'</td>
					<td class="add_files_vat_client_'.$prev_month.'">';
						$output.=get_vat_review_submissions($prev_color_status,$prev_color_text,$prev_text_three,$prev_month,$client->client_id,$prev_checked,$prev_check_box_color,$prev_text_one,$prev_remove_two,$prev_text_two,$prev_t1,$prev_refresh_file,$prev_t2,$prev_attachment_div,$prev_t1_value,$prev_t2_value,$prev_approve_status,$prev_comments, $email_sent_count_prev);
					$output.='</td>
					<td class="add_files_vat_client_'.$curr_month.'">';
						$output.=get_vat_review_submissions($curr_color_status,$curr_color_text,$curr_text_three,$curr_month,$client->client_id,$curr_checked,$curr_check_box_color,$curr_text_one,$curr_remove_two,$curr_text_two,$curr_t1,$curr_refresh_file,$curr_t2,$curr_attachment_div,$curr_t1_value,$curr_t2_value,$curr_approve_status,$curr_comments,$email_sent_count_curr);
					$output.='</td>
					<td class="add_files_vat_client_'.$next_month.'">';
						$output.=get_vat_review_submissions($next_color_status,$next_color_text,$next_text_three,$next_month,$client->client_id,$next_checked,$next_check_box_color,$next_text_one,$next_remove_two,$next_text_two,$next_t1,$next_refresh_file,$next_t2,$next_attachment_div,$next_t1_value,$next_t2_value,$next_approve_status,$next_comments,$email_sent_count_next);
					$output.='</td>
				</tr>';
			}
		}
		$prev_month = date('M-Y', strtotime('first day of previous month'));
		$curr_month = date('M-Y');
		$next_month = date('M-Y', strtotime('first day of next month'));
		echo json_encode(array("output" => $output,"prev_no_sub_due" => $prev_no_sub_due,"curr_no_sub_due" => $curr_no_sub_due,"next_no_sub_due" => $next_no_sub_due,"prev_no_sub_os" => $prev_no_sub_os,"curr_no_sub_os" => $curr_no_sub_os,"next_no_sub_os" => $next_no_sub_os,"prev_no_sub" => $prev_no_sub,"curr_no_sub" => $curr_no_sub,"next_no_sub" => $next_no_sub, "prev_month" => $prev_month, "curr_month" => $curr_month, "next_month" => $next_month,'prev_approval_text' => $prev_approval_text, 'curr_approval_text' => $curr_approval_text, 'next_approval_text' => $next_approval_text));
	}
	public function show_prev_month(Request $request)
	{
		$get_date = '01';
		$month_year = explode("-",$request->get('month_year'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;
		$prevv_month = date('m-Y',strtotime($get_full_date.' -1 month'));
		$currr_month = date('m-Y',strtotime($get_full_date));
		$nextt_month = date('m-Y',strtotime($get_full_date.' +1 month'));
		$current_str = strtotime(date('Y-m-01'));
		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');
		$prev_full_approved = \App\Models\vatReviews::where('month_year',$prevv_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$prev_submitted = \App\Models\vatReviews::where('month_year',$prevv_month)->where('type',3)->pluck('client_id')->toArray();
		$prev_not_submitted_approved=array_diff($prev_full_approved,$prev_submitted);
		$curr_full_approved = \App\Models\vatReviews::where('month_year',$currr_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$curr_submitted = \App\Models\vatReviews::where('month_year',$currr_month)->where('type',3)->pluck('client_id')->toArray();
		$curr_not_submitted_approved=array_diff($curr_full_approved,$curr_submitted);
		$next_full_approved = \App\Models\vatReviews::where('month_year',$nextt_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$next_submitted = \App\Models\vatReviews::where('month_year',$nextt_month)->where('type',3)->pluck('client_id')->toArray();
		$next_not_submitted_approved=array_diff($next_full_approved,$next_submitted);
		$prev_month = '<a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="'.$prevv_month.'"></a>&nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' -1 month')).'">'.date('M-Y',strtotime($get_full_date.' -1 month')).'</a> 
			<label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$prevv_month.'">0</spam></label>
			<label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$prevv_month.'">0</spam></label>
			<label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$prevv_month.'">0</spam></label>
			<a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$prevv_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$prevv_month.'" data-element="'.$prevv_month.'">'.count($prev_full_approved).' / '.count($prev_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$prevv_month.'"></a>';
		$curr_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date)).'">'.date('M-Y',strtotime($get_full_date)).'</a> 
			<label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$currr_month.'">0</spam></label>
			<label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$currr_month.'">0</spam></label>
			<label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$currr_month.'">0</spam></label>
			<a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$currr_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$currr_month.'" data-element="'.$currr_month.'">'.count($curr_full_approved).' / '.count($curr_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$currr_month.'"></a>';
		$next_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' +1 month')).'">'.date('M-Y',strtotime($get_full_date.' +1 month')).'</a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="'.$nextt_month.'"></a> 
			<label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$nextt_month.'">0</spam></label>
			<label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$nextt_month.'">0</spam></label>
			<label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$nextt_month.'">0</spam></label>
			<a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$nextt_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$nextt_month.'" data-element="'.$nextt_month.'">'.count($next_full_approved).' / '.count($next_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$nextt_month.'"></a>';
		$prev_cell = array();
		array_push($prev_cell, $prev_month);
		$curr_cell = array();
		array_push($curr_cell, $curr_month);
		$next_cell = array();
		array_push($next_cell, $next_month);
		$clients =\App\Models\vatClients::where('practice_code', Session::get('user_practice_code'))->get();
		$output = '';
		if(($clients))
		{
			foreach($clients as $client)
			{
				$prev_attachment_div = '';
				$prev_refresh_file = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_t1_value = '';
				$prev_t2_value = '';
				$prev_approve_status = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';
				$prev_comments = '';
				$curr_attachment_div = '';
				$curr_refresh_file = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_t1_value = '';
				$curr_t2_value = '';
				$curr_approve_status = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';
				$curr_comments = '';
				$next_attachment_div = '';
				$next_refresh_file = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_t1_value = '';
				$next_t2_value = '';
				$next_approve_status = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';
				$next_comments = '';
				$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
            	if(($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}
            	$check_reviews_prev = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$prevv_month)->get();
                if(($check_reviews_prev))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'"></a></p>'; 
                			$prev_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($prev->url.'/'.$prev->filename).'" data-client="'.$client->client_id.'" data-month="'.$prevv_month.'"></a>';
                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}
                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }
                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right"></a>';
                		}
                		if($prev->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$prev->textval)->first();
                			if(($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$prev->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($prev->type == 6)
                		{
                			$prev_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($prev->type == 7){
                			$prev_t1_value = $prev->t1_value;
                			$prev_t2_value = $prev->t2_value;
                		}
                		if($prev->type == 8){
                			$prev_approve_status = $prev->approve_status;
                		}
                		if($prev->type == 9){
		        			$prev_comments = $prev->comments;
		        		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			if($current_str > $prev_str)
                			{
                				$prev_color_status = 'red_import'; 
                				$prev_color_text = 'Submission O/S';
                				$prev_check_box_color = 'os_td';
                			}
                			elseif($current_str == $prev_str)
                			{
                				$prev_color_status = 'orange_import'; 
                				$prev_color_text = 'Submission Due';
                				$prev_check_box_color = 'due_td';
                			}
                			else if($current_str < $prev_str)
                			{
                				$prev_color_status = 'white_import'; 
                				$prev_color_text = '`';
                				$prev_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>'; 
                			$curr_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($curr->url.'/'.$curr->filename).'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'"></a>';
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right"></a>';
                		}
                		if($curr->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
                			if(($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$curr->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($curr->type == 7){
                			$curr_t1_value = $curr->t1_value;
                			$curr_t2_value = $curr->t2_value;
                		}
                		if($curr->type == 8){
                			$curr_approve_status = $curr->approve_status;
                		}
                		if($curr->type == 9){
		        			$curr_comments = $curr->comments;
		        		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_next = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$nextt_month)->get();
                if(($check_reviews_next))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a>  
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'"></a></p>'; 
                			$next_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($next->url.'/'.$next->filename).'" data-client="'.$client->client_id.'" data-month="'.$nextt_month.'"></a>';
                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}
                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }
                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right"></a>';
                		}
                		if($next->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$next->textval)->first();
                			if(($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$next->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($next->type == 7){
                			$next_t1_value = $next->t1_value;
                			$next_t2_value = $next->t2_value;
                		}
                		if($next->type == 8){
                			$next_approve_status = $next->approve_status;
                		}
                		if($next->type == 9){
		        			$next_comments = $next->comments;
		        		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			if($current_str > $next_str)
                			{
                				$next_color_status = 'red_import'; 
                				$next_color_text = 'Submission O/S';
                				$next_check_box_color = 'os_td';
                			}
                			else if($current_str == $next_str)
                			{
                				$next_color_status = 'orange_import'; 
                				$next_color_text = 'Submission Due';
                				$next_check_box_color = 'due_td';
                			}
                			else if($current_str < $next_str)
                			{
                				$next_color_status = 'white_import'; 
                				$next_color_text = 'Not Due';
                				$next_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }
                $email_sent_count_prevv = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $prevv_month)->count();
                $email_sent_count_currr = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $currr_month)->count();
                $email_sent_count_nextt = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $nextt_month)->count();
				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}
				array_push($prev_cell, get_vat_review_submissions($prev_color_status,$prev_color_text,$prev_text_three,$prevv_month,$client->client_id,$prev_checked,$prev_check_box_color,$prev_text_one,$prev_remove_two,$prev_text_two,$prev_t1,$prev_refresh_file,$prev_t2,$prev_attachment_div,$prev_t1_value,$prev_t2_value,$prev_approve_status,$prev_comments,$email_sent_count_prevv));
				array_push($curr_cell, get_vat_review_submissions($curr_color_status,$curr_color_text,$curr_text_three,$currr_month,$client->client_id,$curr_checked,$curr_check_box_color,$curr_text_one,$curr_remove_two,$curr_text_two,$curr_t1,$curr_refresh_file,$curr_t2,$curr_attachment_div,$curr_t1_value,$curr_t2_value,$curr_approve_status,$curr_comments,$email_sent_count_currr));
				array_push($next_cell, get_vat_review_submissions($next_color_status,$next_color_text,$next_text_three,$nextt_month,$client->client_id,$next_checked,$next_check_box_color,$next_text_one,$next_remove_two,$next_text_two,$next_t1,$next_refresh_file,$next_t2,$next_attachment_div,$next_t1_value,$next_t2_value,$next_approve_status,$next_comments,$email_sent_count_nextt));
			}
		}
		echo json_encode(array('prev' => $prev_cell,'curr' => $curr_cell,'next' => $next_cell));
	}
	public function show_next_month(Request $request)
	{
		$get_date = '01';
		$month_year = explode("-",$request->get('month_year'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;
		$prevv_month = date('m-Y',strtotime($get_full_date.' -1 month'));
		$currr_month = date('m-Y',strtotime($get_full_date));
		$nextt_month = date('m-Y',strtotime($get_full_date.' +1 month'));
		$current_str = strtotime(date('Y-m-01'));
		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');
		$prev_full_approved = \App\Models\vatReviews::where('month_year',$prevv_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$prev_submitted = \App\Models\vatReviews::where('month_year',$prevv_month)->where('type',3)->pluck('client_id')->toArray();
		$prev_not_submitted_approved=array_diff($prev_full_approved,$prev_submitted);
		$curr_full_approved = \App\Models\vatReviews::where('month_year',$currr_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$curr_submitted = \App\Models\vatReviews::where('month_year',$currr_month)->where('type',3)->pluck('client_id')->toArray();
		$curr_not_submitted_approved=array_diff($curr_full_approved,$curr_submitted);
		$next_full_approved = \App\Models\vatReviews::where('month_year',$nextt_month)->where('approve_status',1)->pluck('client_id')->toArray();
		$next_submitted = \App\Models\vatReviews::where('month_year',$nextt_month)->where('type',3)->pluck('client_id')->toArray();
		$next_not_submitted_approved=array_diff($next_full_approved,$next_submitted);
		$prev_month = '<a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="'.$prevv_month.'"></a>&nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' -1 month')).'">'.date('M-Y',strtotime($get_full_date.' -1 month')).'</a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$prevv_month.'">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$prevv_month.'">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$prevv_month.'">0</spam></label><a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$prevv_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$prevv_month.'" data-element="'.$prevv_month.'">'.count($prev_full_approved).' / '.count($prev_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$prevv_month.'"></a>';
		$curr_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date)).'">'.date('M-Y',strtotime($get_full_date)).'</a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$currr_month.'">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$currr_month.'">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$currr_month.'">0</spam></label><a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$currr_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$currr_month.'" data-element="'.$currr_month.'">'.count($curr_full_approved).' / '.count($curr_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$currr_month.'"></a>';
		$next_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' +1 month')).'">'.date('M-Y',strtotime($get_full_date.' +1 month')).'</a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="'.$nextt_month.'"></a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_'.$nextt_month.'">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_'.$nextt_month.'">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_'.$nextt_month.'">0</spam></label><a href="javascript:" class="approve_label" title="Approved/Approved Unsubmitted" data-element="'.$nextt_month.'">Approve: <spam class="approve_label approve_count approve_count_'.$nextt_month.'" data-element="'.$nextt_month.'">'.count($next_full_approved).' / '.count($next_not_submitted_approved).'</spam></a> <a href="javascript:" class="fa fa-refresh refresh_approval_count" data-element="'.$nextt_month.'"></a>';
		$prev_cell = array();
		array_push($prev_cell, $prev_month);
		$curr_cell = array();
		array_push($curr_cell, $curr_month);
		$next_cell = array();
		array_push($next_cell, $next_month);
		$clients =\App\Models\vatClients::where('practice_code', Session::get('user_practice_code'))->get();
		$output = '';
		if(($clients))
		{
			foreach($clients as $client)
			{
				$prev_attachment_div = '';
				$prev_refresh_file = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_t1_value = '';
				$prev_t2_value = '';
				$prev_approve_status = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';
				$prev_comments = '';
				$curr_attachment_div = '';
				$curr_refresh_file = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_t1_value = '';
				$curr_t2_value = '';
				$curr_approve_status = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';
				$curr_comments = '';
				$next_attachment_div = '';
				$next_refresh_file = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_t1_value = '';
				$next_t2_value = '';
				$next_approve_status = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';
				$next_comments = '';
				$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
            	if(($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}
            	$check_reviews_prev = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$prevv_month)->get();
                if(($check_reviews_prev))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'"></a></p>'; 
                			$prev_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($prev->url.'/'.$prev->filename).'" data-client="'.$client->client_id.'" data-month="'.$prevv_month.'"></a>';
                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}
                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }
                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right"></a>';
                		}
                		if($prev->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$prev->textval)->first();
                			if(($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$prev->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($prev->type == 6)
                		{
                			$prev_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($prev->type == 7){
                			$prev_t1_value = $prev->t1_value;
                			$prev_t2_value = $prev->t2_value;
                		}
                		if($prev->type == 8){
                			$prev_approve_status = $prev->approve_status;
                		}
                		if($prev->type == 9){
                			$prev_comments = $prev->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			if($current_str > $prev_str)
                			{
                				$prev_color_status = 'red_import'; 
                				$prev_color_text = 'Submission O/S';
                				$prev_check_box_color = 'os_td';
                			}
                			elseif($current_str == $prev_str)
                			{
                				$prev_color_status = 'orange_import'; 
                				$prev_color_text = 'Submission Due';
                				$prev_check_box_color = 'due_td';
                			}
                			else if($current_str < $prev_str)
                			{
                				$prev_color_status = 'white_import'; 
                				$prev_color_text = 'Not Due';
                				$prev_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>'; 
                			$curr_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($curr->url.'/'.$curr->filename).'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'"></a>';
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right"></a>';
                		}
                		if($curr->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
                			if(($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$curr->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($curr->type == 7){
                			$curr_t1_value = $curr->t1_value;
                			$curr_t2_value = $curr->t2_value;
                		}
                		if($curr->type == 8){
                			$curr_approve_status = $curr->approve_status;
                		}
                		if($curr->type == 9){
                			$curr_comments = $curr->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_next = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$nextt_month)->get();
                if(($check_reviews_next))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a>  
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'"></a></p>'; 
                			$next_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($next->url.'/'.$next->filename).'" data-client="'.$client->client_id.'" data-month="'.$nextt_month.'"></a>';
                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}
                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }
                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right"></a>';
                		}
                		if($next->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$next->textval)->first();
                			if(($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="mergin-left:5px" download>'.$next->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                			$k = $k + 1;
                		}
                		if($next->type == 7){
                			$next_t1_value = $next->t1_value;
                			$next_t2_value = $next->t2_value;
                		}
                		if($next->type == 8){
                			$next_approve_status = $next->approve_status;
                		}
                		if($next->type == 9){
                			$next_comments = $next->comments;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			if($current_str > $next_str)
                			{
                				$next_color_status = 'red_import'; 
                				$next_color_text = 'Submission O/S';
                				$next_check_box_color = 'os_td';
                			}
                			else if($current_str == $next_str)
                			{
                				$next_color_status = 'orange_import'; 
                				$next_color_text = 'Submission Due';
                				$next_check_box_color = 'due_td';
                			}
                			else if($current_str < $next_str)
                			{
                				$next_color_status = 'white_import'; 
                				$next_color_text = 'Not Due';
                				$next_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }
                $email_sent_count_prevv = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $prevv_month)->count();
                $email_sent_count_currr = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $currr_month)->count();
                $email_sent_count_nextt = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $nextt_month)->count();
				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}
                array_push($prev_cell, get_vat_review_submissions($prev_color_status,$prev_color_text,$prev_text_three,$prevv_month,$client->client_id,$prev_checked,$prev_check_box_color,$prev_text_one,$prev_remove_two,$prev_text_two,$prev_t1,$prev_refresh_file,$prev_t2,$prev_attachment_div,$prev_t1_value,$prev_t2_value,$prev_approve_status,$prev_comments,$email_sent_count_prevv));
				array_push($curr_cell, get_vat_review_submissions($curr_color_status,$curr_color_text,$curr_text_three,$currr_month,$client->client_id,$curr_checked,$curr_check_box_color,$curr_text_one,$curr_remove_two,$curr_text_two,$curr_t1,$curr_refresh_file,$curr_t2,$curr_attachment_div,$curr_t1_value,$curr_t2_value,$curr_approve_status,$curr_comments,$email_sent_count_currr));
				array_push($next_cell, get_vat_review_submissions($next_color_status,$next_color_text,$next_text_three,$nextt_month,$client->client_id,$next_checked,$next_check_box_color,$next_text_one,$next_remove_two,$next_text_two,$next_t1,$next_refresh_file,$next_t2,$next_attachment_div,$next_t1_value,$next_t2_value,$next_approve_status,$next_comments,$email_sent_count_nextt));
			}
		}
		echo json_encode(array('prev' => $prev_cell,'curr' => $curr_cell,'next' => $next_cell));
	}
	public function show_month_in_overlay(Request $request)
	{
		$get_date = '01';
		$month = $request->get('month');
		$month_year = explode("-",$request->get('month'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;
		$currr_month = date('m-Y',strtotime($get_full_date));
		$current_str = strtotime(date('Y-m-01'));
		$curr_str = strtotime($get_full_date);
		$clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '<table class="table">
		<thead>
			<tr>
				<th>Client Code <i class="fa fa-sort code_sort"></i></th>
				<th>Active Client</th>
				<th>Client Name <i class="fa fa-sort client_overlay_sort"></i></th>
				<th>Create Task</th>
				<th style="width: 12%;">Status <i class="fa fa-sort status_sort"></i></th>
				<th>File ID <i class="fa fa-sort id_sort"></i></th>
				<th style="width: 15%;">Records Received <i class="fa fa-sort record_sort"></i></th>
				<th style="width: 10%;">Submitted Date <i class="fa fa-sort date_sort"></i></th>
				<th>Attachments</th>
			</tr>
		</thead>
		<tbody id="overlay_tbody">';
		if(($clients))
		{
			foreach($clients as $client)
			{
				if($client->cm_client_id != "")
				{
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->cm_client_id)->first();
					if(($client_details))
					{
						$company_name = $client_details->company.' - '.$client_details->client_id;
						$cm_client_id = $client_details->client_id;
					}
					else{
						$company_name = '';
						$cm_client_id = '';
					}
				}
				else{
					$company_name = '';
					$cm_client_id = '';
				}
				$curr_attachment_div = '';
				$curr_refresh_file = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '<p>T1: <spam class="t1_spam_overlay"></spam></p>';
				$curr_t2 = '<p>T2: <spam class="t2_spam_overlay"></spam></p>';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'black_td';
				$curr_checked = '';
				$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
            	if(($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}
            	$check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" download>'.$curr->filename.'</a> <a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>';
                			$curr_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file floatnone" data-element="'.URL::to($curr->url.'/'.$curr->filename).'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'"></a>';
                			$curr_t1 ='<p>T1: <spam class="t1_spam_overlay">'.$curr->t1.'</spam></p>';
							$curr_t2 ='<p>T2: <spam class="t2_spam_overlay">'.$curr->t2.'</spam></p>'; 
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import_overlay'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td_overlay';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
                		}
                		if($curr->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
                			if(($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id_overlay" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="mergin-left:5px" download>'.$curr->textval.'</a>'; 
                			}
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import_overlay'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td_overlay';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import_overlay'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td_overlay';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import_overlay'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td_overlay';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import_overlay'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td_overlay';
                		}
                	}
                }
                if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}
				$output.='<tr class="shown_tr shown_tr_'.$client->client_id.'_'.$currr_month.'">
					<td class="code_sort_val">'.$client->cm_client_id.'</td>
					<td style="text-align:center">
						<img class="active_client_list_tm1" data-iden="'.$client->cm_client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
					</td>
					<td class="client_overlay_sort_val">'.$client->name.'</td>
					<td class="vat_create_task_td">
						<a href="javascript:" class="common_black_button create_task_manager" data-client="'.$client->client_id.'" data-month="'.$currr_month.'" data-cmclient="'.$client->cm_client_id.'" data-element="'.$company_name.'" style="clear: both; float: left";>Create Task</a><br/>';
						$get_task_details = \App\Models\taskmanagerVat::where('client_id',$client->client_id)->where('month',$currr_month)->get();
						if(($get_task_details))
						{
							foreach($get_task_details as $task_detail)
							{
								$taskmanager = \App\Models\taskmanager::where('id',$task_detail->task_id)->first();
								$output.='<p style="float: left;margin-top: 10px;font-weight: 600;width:100%">Task : '.$taskmanager->taskid.' - '.$taskmanager->subject.'</p>';
							}
						}
					$output.='</td>
					<td><label class="import_icon_overlay '.$curr_color_status.'">'.$curr_color_text.'</label></td>
					<td class="id_sort_val">'.$curr_text_three.'</td>
					<td><input type="checkbox" class="check_records_received_overlay" id="check_records_received_overlay" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label_overlay '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label>
						<input type="hidden" name="record_sort_val" class="record_sort_val" value="'.$curr_checked.'">
					</td>
					<td>'.$curr_remove_two.'<input type="text" class="submitted_import_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'">
						<input type="hidden" name="date_sort_val" class="date_sort_val" value="'.$curr_text_two.'">
					</td>
					<td><a href="javascript:" class="fa fa-plus add_attachment_month_year_overlay" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a>'.$curr_refresh_file.' <div class="attachment_div_overlay">'.$curr_attachment_div.' '.$curr_t1.' '.$curr_t2.'</div></td>
				</tr>';
			}
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function export_month_in_overlay(Request $request)
	{
		$get_date = '01';
		$month = $request->get('month');
		$month_year = explode("-",$request->get('month'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;
		$currr_month = date('m-Y',strtotime($get_full_date));
		$currrr_month = date('d-M-Y',strtotime($get_full_date));
		$current_str = strtotime(date('Y-m-01'));
		$curr_str = strtotime($get_full_date);
		$filename = 'vat_review_for_'.$currrr_month.'.csv';
		$columns = array('Client Code','Client Name','Status','File ID','Records Received', 'Submitted Date','Attachments');
		$file = fopen('public/papers/vat_review_for_'.$currrr_month.'.csv', 'w');
		fputcsv($file, $columns);
		$clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->get();
		if(($clients))
		{
			foreach($clients as $client)
			{
				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'black_td';
				$curr_checked = '';
				$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
            	if(($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}
            	$check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			if($curr_attachment_div == "")
                			{
                				$curr_attachment_div= $curr->filename; 
                			}
                			else{
                				$curr_attachment_div= $curr_attachment_div.' , '.$curr->filename; 
                			}
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import_overlay'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td_overlay';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
                		}
                		if($curr->type == 4){ 
                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
                			if(($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = $curr->textval; 
                			}
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'Received';
                			$k = $k + 1;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import_overlay'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td_overlay';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import_overlay'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td_overlay';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import_overlay'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td_overlay';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import_overlay'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td_overlay';
                		}
                	}
                }
                if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}
                $columns1 = array(preg_replace('/[^[:print:]]/','',$client->cm_client_id),$client->name,$curr_color_text,$curr_text_three,$curr_checked,$curr_text_two,$curr_attachment_div);
                $columns1 = array_map("utf8_decode", $columns1);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function vat_upload_images(Request $request)
	{
		$client_id = $request->get('hidden_client_id_vat');
		$month_year = $request->get('hidden_month_year_vat');
		$get_vat_details =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$tax_no = $get_vat_details->taxnumber;
		$upload_dir = 'uploads/vat_reviews';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$client_id;
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$month_year;
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
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = $fname;
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);
			$download_url = URL::to($filename);
		 	echo json_encode(array('filename' => $fname,'upload_dir' => $upload_dir,'client_id' => $client_id,'month_year' => $month_year, 'download_url' => $download_url,'tax_no' => $tax_no));
		}
	}
	public function vat_commit_upload_images(Request $request){
		$client_id = $request->get('client_id');
		$month_year = $request->get('month_year');
		$filename = $request->get('filename');
		$url = $request->get('dir');
		$t1 = $request->get('t1');
		$t2 = $request->get('t2');
		$reg = $request->get('reg');
		$data['client_id'] = $client_id;
		$data['month_year'] = $month_year;
		$data['type'] = 1;
		$data['filename'] = $filename;
		$data['url'] = $url;
		$data['t1'] = $t1;
		$data['t2'] = $t2;
		$data['reg'] = $reg;
		$insertedid = \App\Models\vatReviews::insertDetails($data);
		$filename = $url.'/'.$filename;
		$download_url = URL::to($filename);
		$delete_url = URL::to('user/delete_vat_files?file_id='.$insertedid.'');
		echo json_encode(array('id' => $insertedid, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function vat_refresh_upload_images(Request $request) {
		$client_id = $request->get('client_id');
		$month_year = $request->get('month_year');
		$t1 = $request->get('t1');
		$t2 = $request->get('t2');
		$data['t1'] = $t1;
		$data['t2'] = $t2;
		$details = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',1)->first();
		if(($details)){
		\App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',1)->update($data);
		}
	}
	public function vat_upload_csv(Request $request)
	{
		$upload_dir = 'uploads/vat_reviews';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/ros_vat_due';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if (!empty($_FILES)) {
			$fname = time().'.csv';
			$tmpFile = $_FILES['file']['tmp_name'];
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);
			$ufname = str_replace("#","",$_FILES['file']['name']);
			$ufname = str_replace("#","",$ufname);
			$ufname = str_replace("#","",$ufname);
			$ufname = str_replace("#","",$ufname);
			$ufname = str_replace("%","",$ufname);
			$ufname = str_replace("%","",$ufname);
			$ufname = str_replace("%","",$ufname);
			$data['filename'] = $fname;
			$data['uploaded_filename'] = $ufname;
			$data['url'] = $upload_dir;
			$data['import_date'] = date('d/m/Y');
			$data['import_time'] = date('H:i:s');
			$data['practice_code'] = Session::get('user_practice_code');
			$insertedid =\App\Models\vatReviewsImportAttachment::insertDetails($data);
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname));
		}
	}
	public function save_vat_review_date(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$date = $request->get('date');
		$check_month_year_client = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',3)->first();
		if(($check_month_year_client))
		{
			$data['client_id']= $client_id;
			$data['month_year']= $month_year;
			$data['type']= "3";
			$data['textval'] = $date;
			$data['status'] = "0";
		\App\Models\vatReviews::where('id',$check_month_year_client->id)->update($data);
		}
		else{
			$data['client_id']= $client_id;
			$data['month_year']= $month_year;
			$data['type']= "3";
			$data['textval'] = $date;
			$data['status'] = "0";
		\App\Models\vatReviews::insert($data);
		}
	}
	public function save_textval_review(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$textval = $request->get('textval');
		$type = $request->get('type');
		$check_month_year_client = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->first();
		if(($check_month_year_client))
		{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['textval'] = $textval;
		\App\Models\vatReviews::where('id',$check_month_year_client->id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['textval'] = $textval;
		\App\Models\vatReviews::insert($data);
		}
	}
	public function save_t1_textbox(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$textval = $request->get('textval');
		$type = $request->get('type');
		$check_month_year_client = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->first();
		if(($check_month_year_client))
		{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['t1_value'] = $textval;
		\App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['t1_value'] = $textval;
		\App\Models\vatReviews::insert($data);
		}
	}
	public function change_t_approve_status(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$textval = $request->get('textval');
		$type = $request->get('type');
		$check_month_year_client = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->first();
		if ($check_month_year_client) {
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['approve_status'] = $textval;
		\App\Models\vatReviews::where('id',$check_month_year_client->id)->update($data);
		}
		else {
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['approve_status'] = $textval;
		\App\Models\vatReviews::insert($data);
		}
	}
	public function save_t2_textbox(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$textval = $request->get('textval');
		$type = $request->get('type');
		$check_month_year_client = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->first();
		if(($check_month_year_client))
		{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['t2_value'] = $textval;
		\App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['t2_value'] = $textval;
		\App\Models\vatReviews::insert($data);
		}
	}
	public function delete_vat_files(Request $request)
	{
		$fileid = $request->get('file_id');
	\App\Models\vatReviews::where('id',$fileid)->delete();
	}
	public function deactivevatclients(Request $request, $id=""){
		$id = base64_decode($id);
		$deactive =  1;
		\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activevatclients(Request $request, $id=""){
		$id = base64_decode($id);
		$active =  0;
		\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function editvatclients(Request $request, $id=""){
		$id = base64_decode($id);
		$result =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->first();
		if($result->cm_client_id != ''){
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $result->cm_client_id)->first();
			if($client_details->company != "")
			{
				$companyname = $client_details->company.'-'.$client_details->client_id;
			}
			else{
				$companyname = $client_details->firstname.' '.$client_details->surname.'-'.$client_details->client_id;
			}
			echo json_encode(array('name' => $result->name, 'taxnumber' => $result->taxnumber, 'pemail' =>  $result->pemail, 'semail' =>  $result->semail, 'salutation' =>  $result->salutation, 'self_manage' =>  $result->self_manage,'always_nil' =>  $result->always_nil, 'id' => $result->client_id,'taxreg' => $client_details->tax_reg1, "firstname" => $result->name, "companyname" => $companyname,"cm_client_id" => $result->cm_client_id));
		}
		else{
			echo json_encode(array('name' => $result->name, 'taxnumber' => $result->taxnumber, 'pemail' =>  $result->pemail, 'semail' =>  $result->semail, 'salutation' =>  $result->salutation, 'self_manage' =>  $result->self_manage,'always_nil' =>  $result->always_nil, 'id' => $result->client_id,'cm_client_id' => $result->cm_client_id, "firstname" => $result->name));
		}
	}
	public function updatevatclients(Request $request){
		$name = $request->get('name');		
		$pemail = $request->get('pemail');
		$semail = $request->get('semail');
		$salutation = $request->get('salutation');
		$self_manage = $request->get('self');
		$always_nil = $request->get('always_nil');
		$id = $request->get('id');
		$client_id = $request->get('client_id');
		\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->update(['name' => $name, 'pemail' => $pemail, 'semail' => $semail, 'salutation' => $salutation, 'self_manage' => $self_manage, 'always_nil' => $always_nil,'cm_client_id' => $client_id]);
		$check_status =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$id)->first();
		$red =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',1)->count();
		$green =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
		$yellow =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
		$purple =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('self_manage','yes')->count();
		echo json_encode(array('status' => $check_status->status,'cm_client_id' => $check_status->cm_client_id, 'red' => $red, 'green' =>  $green, 'yellow' =>  $yellow, 'purple' =>  $purple));
	}
	public function addvatclients(Request $request){
		$data['name'] = $request->get('name');		
		$data['taxnumber'] = $request->get('taxnumber');	
		$data['pemail'] = $request->get('pemail');
		$data['semail'] = $request->get('semail');
		$data['salutation'] = $request->get('salutation');
		$data['tax_type'] = $request->get('tax_type');
		$data['document_type'] = $request->get('document_type');
		$data['ros_filer'] = $request->get('ros_filer');
		$data['period'] = $request->get('period_add');
		$data['cm_client_id'] = $request->get('cmclientid');
		$explodedate = explode('-',$request->get('due_add'));
		if(count($explodedate) == 3)
		{
			$data['due_date'] = $explodedate[2].'-'.$explodedate[0].'-'.$explodedate[1];
		}
		$data['self_manage'] = $request->get('self');
		$data['always_nil'] = $request->get('always_nil');
		$data['practice_code'] = Session::get('user_practice_code');
		\App\Models\vatClients::insert($data);
		$saluation_client = $request->get('hidden_client_salutation');
		$clientid = $request->get('hidden_client_id');
		if($saluation_client == 1)
		{
			$cmclient['salutation'] = trim($request->get('salutation'));
			\App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->update($cmclient);
		}
		return redirect::back()->with('message','Added Successfully');
	}
	public function checkclientemail(Request $request)
	{
		$email = $request->get('email');
		$client_id = $request->get('client_id');
		$type = $request->get('type');
		if($client_id == "")
		{
			$check =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('pemail', $email)->first();
			$check_sec =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('semail', $email)->first();
		}
		else{
			$check =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id','!=', $client_id)->where('pemail', $email)->first();
			$check_sec =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id','!=', $client_id)->where('semail', $email)->first();
		}
		if(($check) || ($check_sec))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function checkclienttaxnumber(Request $request)
	{
		$tax = $request->get('taxnumber');
		$check =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber', $tax)->first();
		if(($check))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function in_array_r($item , $array){
	    return preg_match('/"'.$item.'"/i' , json_encode($array));
	}
	public function import_form(Request $request)
	{
		if($_FILES['import_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_file']['tmp_name'];
			$name=$_FILES['import_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 500)
					{
						$height = 500;
					}
					else{
						$height = $highestRow;
					}
					$insertrows = array();
					for ($row = 2; $row <= $height; ++ $row) {
						$client_name = $worksheet->getCellByColumnAndRow(1, $row); $client_name = trim($client_name->getValue());
						$ros_filter = $worksheet->getCellByColumnAndRow(2, $row); $ros_filter = trim($ros_filter->getValue());
						$tax_type = $worksheet->getCellByColumnAndRow(3, $row); $tax_type = trim($tax_type->getValue());
						$doc_type = $worksheet->getCellByColumnAndRow(4, $row); $doc_type = trim($doc_type->getValue());
						$tax_no = $worksheet->getCellByColumnAndRow(5, $row);  $tax_no = trim($tax_no->getValue());
						$period = $worksheet->getCellByColumnAndRow(6, $row);  $period = trim($period->getValue());
						$due_date = $worksheet->getCellByColumnAndRow(7, $row);  $due_date = trim($due_date->getValue());
						if($tax_type != 'VAT')
						{
							Session::forget('insertrows');
							return redirect('user/vat_clients?mode=modal')->with('message_import_not_valid', 'This is not a valid VAT import file');
						}
						$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
						$check_array = $this->in_array_r($tax_no,$insertrows);
						if($client_name != "Name not found" && $tax_no != "")
						{
							if(!($check_tax) && $check_array == 0)
							{
								$data['name'] = $client_name;
								$data['ros_filer'] = $ros_filter;
								$data['tax_type'] = $tax_type;
								$data['document_type'] = $doc_type;
								$data['taxnumber'] = $tax_no;
								$data['period'] = $period;
								$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
								$data['self_manage'] = 'no';
								$data['always_nil'] = 'no';
								$data['practice_code'] = Session::get('user_practice_code');
								array_push($insertrows,$data);								
							}
						}
					}
				}
				$sessn=array('insertrows' => $insertrows);
				Session::put($sessn);
				if($height >= $highestRow)
				{
					return redirect('user/vat_clients?mode=modal')->with('message_import', 'Items Listed successfully.');
				}
				else{
					return redirect('user/vat_clients?mode=modal&filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1');
				}
			}
		}
	}
	public function import_form_one(Request $request)
	{
		$name = $request->get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = IOFactory::load($filepath);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$round = $request->get('round');
			$last_height = $request->get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$insertrows = Session::get('insertrows');
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(1, $row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(2, $row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(3, $row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(4, $row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(5, $row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(6, $row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(7, $row);  $due_date = trim($due_date->getValue());
				if($tax_type != 'VAT')
				{
					Session::forget('insertrows');
					return redirect('user/vat_clients?mode=modal')->with('message_import_not_valid', 'This is not a valid VAT import file');
				}
				$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
				$check_array = $this->in_array_r($tax_no,$insertrows);
				if($client_name != "Name not found" && $tax_no != "")
				{
					if(!($check_tax) && $check_array == 0)
					{
						$data['name'] = $client_name;
						$data['ros_filer'] = strtolower($ros_filter);
						$data['tax_type'] = $tax_type;
						$data['document_type'] = $doc_type;
						$data['taxnumber'] = $tax_no;
						$data['period'] = $period;
						$due = explode('-',$due_date);
						if(count($due) == 3)
						{
							$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
						}
						else{
							$due = explode('/',$due_date);
							if(count($due) == 3)
							{
								$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
							}
						}
						$data['self_manage'] = 'no';
						$data['always_nil'] = 'no';
						$data['practice_code'] = Session::get('user_practice_code');
						array_push($insertrows,$data);								
					}
				}
			}
		}
		Session::forget("insertrows");
		$sessn=array('insertrows' => $insertrows);
		Session::put($sessn);
		if($height >= $highestRow)
		{
			return redirect('user/vat_clients?mode=modal')->with('message_import', 'Items Listed successfully.');
		}
		else{
			return redirect('user/vat_clients?mode=modal&filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type=1');
		}
	}
	public function compare_form(Request $request)
	{
		\App\Models\vatClientsCompare::truncate();
		if($_FILES['compare_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['compare_file']['tmp_name'];
			$name=$_FILES['compare_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 500)
					{
						$height = 500;
					}
					else{
						$height = $highestRow;
					}
					$customer_name_label = $worksheet->getCellByColumnAndRow(1, 1); $customer_name_label = trim($customer_name_label->getValue());
					$ros_filter_label = $worksheet->getCellByColumnAndRow(2, 1); $ros_filter_label = trim($ros_filter_label->getValue());
					$insertrows = array();
					$alreadyinsertedrows = array();
					if($customer_name_label == "Customer Name" && $ros_filter_label == "Mandatory ROS filer")
					{
						$alreadyinsertedrows = array();
						for ($row = 2; $row <= $height; ++ $row) {
							$client_name = $worksheet->getCellByColumnAndRow(1, $row); $client_name = trim($client_name->getValue());
							$ros_filter = $worksheet->getCellByColumnAndRow(2, $row); $ros_filter = trim($ros_filter->getValue());
							$tax_type = $worksheet->getCellByColumnAndRow(3, $row); $tax_type = trim($tax_type->getValue());
							$doc_type = $worksheet->getCellByColumnAndRow(4, $row); $doc_type = trim($doc_type->getValue());
							$tax_no = $worksheet->getCellByColumnAndRow(5, $row);  $tax_no = trim($tax_no->getValue());
							$period = $worksheet->getCellByColumnAndRow(6, $row);  $period = trim($period->getValue());
							$due_date = $worksheet->getCellByColumnAndRow(7, $row);  $due_date = trim($due_date->getValue());
							if($tax_type != 'VAT')
							{
								Session::forget('comparerows');
								Session::forget('alreadyinsertedrows');
								return redirect('user/vat_clients?mode=modal')->with('message_import_not_valid', 'This is not a valid VAT import file');
							}
							$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
							if($client_name != "Name not found" && $tax_no != "")
							{
								if(!($check_tax))
								{
									$data['name'] = $client_name;
									$data['ros_filer'] = $ros_filter;
									$data['tax_type'] = $tax_type;
									$data['document_type'] = $doc_type;
									$data['taxnumber'] = $tax_no;
									$data['period'] = $period;
									$data['due_date'] = $due_date;
									array_push($insertrows,$data);								
								}
								else{
									array_push($alreadyinsertedrows,$tax_no);
								}
								$dataall['name'] = $client_name;
								$dataall['ros_filer'] = $ros_filter;
								$dataall['tax_type'] = $tax_type;
								$dataall['document_type'] = $doc_type;
								$dataall['taxnumber'] = $tax_no;
								$dataall['period'] = $period;
								$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
								\App\Models\vatClientsCompare::insert($dataall);
							}
						}
					}
					else{
						echo json_encode(array("status" => "2", "message" => "The File Format is not supported."));
						exit;
					}
				}
				$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);
				Session::put($sessn);
				if($height >= $highestRow)
				{
					$vat_notify = vat_notifications_form();
					echo json_encode($vat_notify);
				}
				else{
					echo json_encode(array("status" => "1", "filename" => $name, "height" => $height, "round" => "2", "highestrow" => $highestRow, "compare_type" => "1"));
				}
			}
		}
	}
	public function compare_form_one(Request $request)
	{
		$name = $request->get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = IOFactory::load($filepath);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$round = $request->get('round');
			$last_height = $request->get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$insertrows = Session::get('comparerows');
			$alreadyinsertedrows = Session::get('alreadyinsertedrows');
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(1, $row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(2, $row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(3, $row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(4, $row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(5, $row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(6, $row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(7, $row);  $due_date = trim($due_date->getValue());
				if($tax_type != 'VAT')
				{
					Session::forget('comparerows');
					Session::forget('alreadyinsertedrows');
					return redirect('user/vat_clients?mode=modal')->with('message_import_not_valid', 'This is not a valid VAT import file');
				}
				$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
				if($client_name != "Name not found" && $tax_no != "")
				{
					if(!($check_tax))
					{
						$data['name'] = $client_name;
						$data['ros_filer'] = strtolower($ros_filter);
						$data['tax_type'] = $tax_type;
						$data['document_type'] = $doc_type;
						$data['taxnumber'] = $tax_no;
						$data['period'] = $period;
						$explode = explode("-",$due_date);
						$data['due_date'] = $explode[2].'-'.$explode[1].'-'.$explode[0];
						array_push($insertrows,$data);								
					}
					else{
						array_push($alreadyinsertedrows,$tax_no);
					}
					$dataall['name'] = $client_name;
					$dataall['ros_filer'] = $ros_filter;
					$dataall['tax_type'] = $tax_type;
					$dataall['document_type'] = $doc_type;
					$dataall['taxnumber'] = $tax_no;
					$dataall['period'] = $period;
					$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
					\App\Models\vatClientsCompare::insert($dataall);
				}
			}
		}
		Session::forget("comparerows");
		$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);
		Session::put($sessn);
		if($height >= $highestRow)
		{
			$vat_notify = vat_notifications_form();
			echo json_encode($vat_notify);
		}
		else{
			echo json_encode(array("status" => "1", "filename" => $name, "height" => $height, "round" => $nextround, "highestrow" => $highestRow, "compare_type" => "1"));
		}
	}
	public function vat_notifications(Request $request)
	{
		$without_emailed =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('pemail','')->where('semail','')->where('status',0)->get();
		$disabled =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',1)->get();
		$with_emailed =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();
		return view('user/vatnotifications', array('without_emailed' => $without_emailed, 'disabled' => $disabled, 'with_emailed' => $with_emailed));
	}
	public function import_sessions(Request $request)
	{
		$sessions = Session::get('insertrows');
		if(($sessions))
		{
			foreach($sessions as $key => $sess)
			{
				\App\Models\vatClients::insert($sess);
			}
			return redirect('user/vat_clients?mode=modal')->with('message', 'Clients Imported successfully.');
			Session::forget('insertrows');
		}
		else{
			Session::forget('insertrows');
			return redirect('user/vat_clients?mode=modal')->with('message', 'Sorry no data was uploaded.');
		}
	}
	public function email_vatnotifications(Request $request){
		$vat_settings = DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->first();

		$admin_cc = $vat_settings->vat_cc_email;
		$client_id = $request->get('client_id');
		$pemail = $request->get('pemail');
		$semail = $request->get('semail');
		$emails = $request->get('emails');
		$explode = explode(",",$emails);
		$exp_emails = array_values(array_filter($explode, 'strlen'));
		$email_sent_to =  implode(" , ",$exp_emails).' , '.$admin_cc;
		$salutation = $request->get('salutation');
		$self_manage = $request->get('self_manage');
		$period = $request->get('period');
		$due_date = $request->get('due_date');
		$time = $request->get('timeval');
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->first();
		$from = $user_details->email;
		$client_details =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$cc = $admin_cc;
		if($pemail != "" && $semail != '')
		{
			$sentemails = $pemail.' , '.$semail.' , '.$cc;
		}
		elseif($pemail != "" && $semail == '')
		{
			$sentemails = $pemail.' , '.$cc;
		}
		elseif($pemail == "" && $semail != '')
		{
			$sentemails = $semail.' , '.$cc;
		}
		if($pemail != "")
		{
			$to = trim($pemail);
			$data['sentmails'] = $sentemails;
			$data['logo'] = getEmailLogo('vat');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $vat_settings->email_signature;
			$contentmessage = view('user/email_vat_notify', $data);
			$subject = 'GBS & Co: VAT Reminder for '.$client_details->name.'';
			$email = new PHPMailer();
			$email->CharSet = 'UTF-8';
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();
			if($client_details->cm_client_id != "")
			{
				$cm_client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_details->cm_client_id)->first();
				$datamessage['message_id'] = $time;
				$datamessage['message_from'] = 0;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $client_details->cm_client_id;
				$datamessage['primary_emails'] = $cm_client_details->email;
				$datamessage['secondary_emails'] = $cm_client_details->email2;
				$datamessage['date_sent'] = date('Y-m-d H:i:s');
				$datamessage['date_saved'] = date('Y-m-d H:i:s');
				$datamessage['source'] = 'VAT SYSTEM';
				$datamessage['status'] = 1;
				$datamessage['practice_code'] = Session::get('user_practice_code');
				\App\Models\Messageus::insert($datamessage);
			}
			$date = date('Y-m-d H:i:s');
			\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->update(['last_email_sent' => $date]);
		}
		if($semail != "")
		{
			$to = trim($semail);
			$data['sentmails'] = $sentemails;
			$data['logo'] = getEmailLogo('vat');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $vat_settings->signature;
			$contentmessage = view('user/email_vat_notify', $data);
			$subject = 'GBS & Co: VAT Reminder for '.$client_details->name.'';
			$email = new PHPMailer();
			$email->CharSet = 'UTF-8';
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
			$date = date('Y-m-d H:i:s');
			\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->update(['last_email_sent' => $date]);
		}
		if($pemail != "" || $semail !="")
		{
			\App\Models\vatClientsEmail::insert(['email_sent' => $date,'client_id' => $client_id]);
		}
	}
	public function email_sents(Request $request)
	{
		$id = base64_decode($request->get('id'));
		$emails_dates =\App\Models\vatClientsEmail::where('client_id',$id)->get();
		$output = '';
		if(($emails_dates))
		{
			$i = 1;
			foreach($emails_dates as $emails)
			{
				$output.='<tr>
				<td>'.$i.'</td>
				<td>'.date('d F Y', strtotime($emails->email_sent)).'</td>
				<td>'.date('H:i', strtotime($emails->email_sent)).'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="3">No Data Found</td></tr>';
		}
		echo $output;
	}
	public function email_sents_save_pdf(Request $request)
	{
		$id = base64_decode($request->get('id'));
		$emails_dates =\App\Models\vatClientsEmail::where('client_id',$id)->get();
		$client_details =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$id)->first();
		$output = '<h4>Email Sent Date & Time For Client : '.$client_details->name.' and Taxnumber : '.$client_details->taxnumber.'</h4>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">S.No</td>
				              <td style="text-align: left;border:1px solid #000;">Date</td>
				              <td style="text-align: left;border:1px solid #000;">Time</td>
				          </tr>
				        ';
						if(($emails_dates))
						{
							$i = 1;
							foreach($emails_dates as $emails)
							{
								$output.='<tr>
								<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('d F Y', strtotime($emails->email_sent)).'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('H:i', strtotime($emails->email_sent)).'</td>
								</tr>';
								$i++;
							}
						}
						else{
							$output.='<tr><td colspan="3" style="text-align: center;border:1px solid #000;">No Data Found</td></tr>';
						}
		$output.='
		</table>';
		$pdf = PDF::loadHTML($output);
	    $pdf->save('public/papers/'.$client_details->name.'_'.$client_details->taxnumber.'.pdf');
	 	$filename = $client_details->name.'_'.$client_details->taxnumber.'.pdf';	
	 	echo $filename;
	}
	public function pdf_without_email(Request $request){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$without_emailed =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('pemail','')->where('semail','')->where('status',0)->get();
		$logo = getEmailLogo('vat');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients Without Email Address: </h4><br/>
					<table style="width:100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">#</td>
				              <td style="text-align: left;border:1px solid #000;">Client Name</td>
				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>
				          </tr>';
						$i=1;
                		if(($without_emailed)){              
                  			foreach($without_emailed as $without){       
                  				if(in_array($without->taxnumber,$taxnumber_compared)) {  
            							$output.='<tr>
						                <td style="text-align: left;border:1px solid #000"><label>'.$i.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$without->name.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$without->taxnumber.'</label></td>
            							</tr>';
               							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Vat_Clients_Without_Email.pdf');
	   		 	echo 'Vat_Clients_Without_Email.pdf';
	}
	public function pdf_disabled(Request $request){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$disabled =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',1)->get();
		$logo = getEmailLogo('vat');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients Without Email Address: </h4><br/>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">#</td>
				              <td style="text-align: left;border:1px solid #000;">Client Name</td>
				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>
				          </tr>';
						$i=1;
                		if(($disabled)){              
                  			foreach($disabled as $disable){       
                  				if(in_array($disable->taxnumber,$taxnumber_compared)) {  
            							$output.='<tr style="text-align:left">
						                <td style="text-align: left; border:1px solid #000"><label>'.$i.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->name.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->taxnumber.'</label></td>
            							</tr>';
               							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Vat_Clients_Disabled.pdf');
	   		 	echo 'Vat_Clients_Disabled.pdf';
	}
	public function pdf_with_email(Request $request){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$with_emailed =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();
		$logo = getEmailLogo('vat');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients that we Can Notify : </h4><br/>
					<table style="width: 100%;border-collapse:collapse;">
				          <tr>
				                <td style="text-align: left; border:1px solid #000; font-size:12px;">#</td>
					            <td style="text-align: left; border:1px solid #000; word-wrap: break-word; font-size:12px;">Client Name</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Tax Regn./Trader No</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Email</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Secondary Email</td> 
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Last Email Sent</td> 
				          </tr>';
						$i=1;
                		if(($with_emailed)){              
                  			foreach($with_emailed as $with){       
                  				if(in_array($with->taxnumber,$taxnumber_compared)) {  
                  					if($with->last_email_sent == "0000-00-00 00:00:00")
                  					{
                  						$dd = '-';
                  					}
                  					else{
                  						$dd = date('d F Y', strtotime($with->last_email_sent));
                  					}
        							$output.='<tr style="text-align:left">
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$i.'</td>
					                <td style="width: 180px;  word-wrap: break-word;text-align: left; border:1px solid #000;font-size:12px;">'.$with->name.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->taxnumber.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->pemail.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->semail.'</td>
					                <td style="width: 75px; text-align: left; border:1px solid #000; font-size:12px;">'.$dd.'</td>
        							</tr>';
           							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="6" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('public/papers/Vat_Clients_With_Email.pdf');
	   		 	echo 'Vat_Clients_With_Email.pdf';
	}
	public function task_client_search(Request $request)
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
	public function task_clientsearchselect(Request $request){
		$id = $request->get('value');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->first();
		echo json_encode(["taxreg" => $client_details->tax_reg1, "primaryemail" => $client_details->email, "firstname" => $client_details->firstname]);
	}
	public function vat_client_search(Request $request)
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
	public function vat_clientsearchselect(Request $request){
		$id = $request->get('value');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->first();
		if($client_details->company != "")
		{
			$company = $client_details->company;
		}
		else{
			$company = $client_details->firstname.' - '.$client_details->surname;
		}
		echo json_encode(["taxreg" => $client_details->tax_reg1, "primaryemail" => $client_details->email, "secondaryemail" => $client_details->email2, "firstname" => $company]);
	}
	public function getclientcompanyname(Request $request)
	{
		$clientid= $request->get('clientid');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->first();
		if($client_details->company != "")
		{
			$company = $client_details->company;
		}
		else{
			$company = $client_details->firstname.' '.$client_details->surname;
		}
		echo $company;
	}
	public function getclientemail(Request $request)
	{
		$clientid= $request->get('clientid');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->first();
		echo $client_details->email;
	}
	public function getclientemail_secondary(Request $request)
	{
		$clientid= $request->get('clientid');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->first();
		echo $client_details->email2;
	}
	public function getclient_salutation(Request $request)
	{
		$clientid= $request->get('clientid');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$clientid)->first();
		echo $client_details->firstname;
	}
	public function taskmanager_upload_images(Request $request)
	{
		$task_id = $_GET['task_id'];
		$type = $_GET['type'];
		$data_img = \App\Models\task::where('task_id',$task_id)->first();
		$upload_dir = 'uploads/task_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $filename = $upload_dir.'/'.$_FILES['file']['name'];
			 $fname = $_FILES['file']['name'];
		 	move_uploaded_file($tmpFile,$filename);
		 	$data['task_id'] = $data_img->task_id;
			$data['attachment'] = $fname;
			$data['url'] = $upload_dir;
			if($type == 2)
			{
				$data['network_attach'] = 1;
				$dataval['task_started'] = 1;
				$dataval['task_notify'] = 1;
				\App\Models\task::where('task_id',$task_id)->update($dataval);
			}
			else{
				$data['network_attach'] = 0;
			}
			$id = \App\Models\taskAttached::insertDetails($data);
		}
		echo json_encode(array('id' => $id,'filename' => $fname,'task_id' => $data_img->task_id));
	}
	public function remove_dropzone_attachment(Request $request)
	{
		$attachment_id = $_POST['attachment_id'];
		$task_id = $_POST['task_id'];
		$check_network = \App\Models\taskAttached::where('id',$attachment_id)->first();
		\App\Models\taskAttached::where('id',$attachment_id)->delete();
		if($check_network->network_attach == 1)
		{
			$count = \App\Models\taskAttached::where('task_id',$check_network->task_id)->where('network_attach',1)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['task_started'] = 0;
				\App\Models\task::where('task_id',$check_network->task_id)->update($dataval);
			}
		}
	}
	public function task_complete_update(Request $request){
		$id = $request->get('id');
		$status = $request->get('status');
		$dontvale = 0;
		$dataval['task_complete_period'] = $status;
		$dataval['task_complete_period_type'] = $dontvale;
		if($status == 1)
		{
			$dataval['task_started'] = 0;
		}
		\App\Models\task::where('task_id', $id)->update($dataval);
	}
	public function task_complete_update_new(Request $request){
		$id = $request->get('id');
		$status = 1;
		$dontvale = $request->get('dontvale');		
		$dataval['task_complete_period'] = $status;
		$dataval['task_complete_period_type'] = $dontvale;
		if($status == 1)
		{
			$dataval['task_started'] = 0;
		}
		\App\Models\task::where('task_id', $id)->update($dataval);
	}
	public function donot_complete_task_details(Request $request){
		$id = $request->get('taskid');
		$task_details = \App\Models\task::where('task_id', $id)->first();
		echo $task_details->task_name;
	}
	public function email_report_generator(Request $request)
	{
		$task_id = $_GET['task_id'];
		$task_details = \App\Models\task::where('task_id',$task_id)->first();
		if($task_details->task_week == 0) { $label = 'Month'; } else { $label = 'Week'; }
		$task_created_id = $task_details->task_created_id;
		$get_email_dates = \App\Models\taskEmailSent::where('task_created_id',$task_created_id)->where('options','!=','n')->get();
		$output = '<h4>Report for Email sent Date & Time</h4>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">S.No</td>
				              <td style="text-align: left;border:1px solid #000;">'.$label.'</td>
				              <td style="text-align: left;border:1px solid #000;">Task Name</td>
				              <td style="text-align: left;border:1px solid #000;">Date</td>
				              <td style="text-align: left;border:1px solid #000;">Time</td>
				          </tr>';
						if(($get_email_dates))
						{
							$i = 1;
							foreach($get_email_dates as $emails)
							{
								$fetch_task = \App\Models\task::where('task_id',$emails->task_id)->first();
								if($fetch_task->task_week == 0) 
								{
									$month_details = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id',$fetch_task->task_month)->first();
									$labelval = $month_details->month;
								}
								else{
									$week_details = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id',$fetch_task->task_week)->first();
									$labelval = $week_details->week;
								}
								if($emails->options != '0'){
									$optionsval = '('.strtoupper($emails->options).')';
								}
								else{
									$optionsval = '';
								}
								$output.='<tr>
								<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
								<td style="text-align: left;border:1px solid #000;">'.$labelval.'</td>
								<td style="text-align: left;border:1px solid #000;">'.$fetch_task->task_name.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('d F Y', strtotime($emails->email_sent)).' '.$optionsval.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('H:i', strtotime($emails->email_sent)).'</td>
								</tr>';
								$i++;
							}
						}
						else{
							$output.='<tr><td colspan="3" style="text-align: center;border:1px solid #000;">No Data Found</td></tr>';
						}
		$output.='</table>';
		$pdf = PDF::loadHTML($output);
	    $pdf->save('public/papers/Report For Task '.$task_details->task_name.'.pdf');
	    echo 'Report For Task '.$task_details->task_name.'.pdf';
	}
	public function task_default_users_update(Request $request)
	{
		$id = $request->get('id');
		$users = $request->get('users');
		\App\Models\task::where('task_id',$id)->update(['default_staff' => $users]);
	}
	public function save_disclose_liability(Request $request)
	{
		$task_id = $request->get('task_id');
		$status = $request->get('status');
		$data['disclose_liability'] = $status;
		\App\Models\task::where('task_id',$task_id)->update($data);
	}
	public function save_distribute_email(Request $request)
	{
		$task_id = $request->get('task_id');
		$status = $request->get('status');
		$data['distribute_email'] = $status;
		\App\Models\task::where('task_id',$task_id)->update($data);
	}
	public function get_clientname_from_pms(Request $request)
	{
		$taskid = $request->get('taskid');
		$get_task = \App\Models\task::where('task_id',$taskid)->first();
		$allocated_email = '';
		if(($get_task))
		{
			if($get_task->client_id == "")
			{
				$clientname = '';
				$client_id = '';
			}
			else{
				$clientdetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$get_task->client_id)->first();
				if(($clientdetails))
				{
					if($clientdetails->company != "")
					{
						$company = $clientdetails->company;
					}
					else{
						$company = $clientdetails->firstname.' '.$clientdetails->surname;
					}
					$clientname = $company.'-'.$clientdetails->client_id;
					$client_id = $get_task->client_id;
				}
				else{
					$clientname = '';
					$client_id = '';
				}
			}
			if($get_task->default_staff != "" && $get_task->default_staff != 0){
				$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$get_task->default_staff)->first();
				$allocated_email = $user_details->email;
			}
		}
		else{
			$clientname = '';
			$client_id = '';
		}
		echo json_encode(array("company" => $clientname,"client_id" => $client_id,"staff" => $get_task->default_staff,'allocated_email' => $allocated_email));
	}
	public function add_scheme(Request $request)
	{
		$data['scheme_name'] = $request->get('scheme_name');
		$data['status'] = $request->get('status');
		$data['practice_code'] = Session::get('user_practice_code');
		$newschemeid = \App\Models\schemes::insertDetails($data);
	      $schemes = \App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->get();
	      $output = '';
	      if(($schemes))
	      {
	      	$i = 1;
	        foreach($schemes as $scheme)
	        {
	          $output.='<tr>
	            <td>'.$i.'</td>
	            <td>'.$scheme->scheme_name.'</td>
	            <td>';
	                if($scheme->status == "1")
                    {
                      $output.='<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=0&id='.$scheme->id.'').'" class="fa fa-times-circle change_scheme_status" data-element="1" title="Closed" style="color:red"></a>';
                    }
                    else{
                      $output.='<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=1&id='.$scheme->id.'').'" class="fa fa-check-circle-o change_scheme_status" data-element="0" title="Open" style="color:green"></a>';
                    }
	            $output.='</td>
	          </tr>';
	          $i++;
	        }
	      }
	      else{
	        $output.='<tr>
	          <td colspan="3">No Schemes Found</td>
	        </tr>';
	      }
	      echo json_encode(array("output" => $output,"option" => '<option value="'.$newschemeid.'">'.$request->get('scheme_name').'</option>'));
	}
	public function set_scheme_for_task(Request $request)
	{
		$data['scheme_id'] = $request->get('scheme');
		$task_id = $request->get('task_id');
		\App\Models\task::where('task_id',$task_id)->update($data);
	}
	public function check_previous_week(Request $request)
	{
		$task_id = $request->get('task_id');
		$status = $request->get('status');
		$datastatus['same_as_last'] = $status;
		\App\Models\task::where('task_id',$task_id)->update($datastatus);
		$week = $request->get('week');
		$get_prev_week= \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_id','<',$week)->orderBy('week_id','desc')->first();
		$prev_week_id = $get_prev_week->week_id;
		$get_curr_tasks = \App\Models\task::where('task_id',$task_id)->first();
		$task_enumber = $get_curr_tasks->task_enumber;
		$get_prev_tasks = \App\Models\task::where('task_enumber',$task_enumber)->where('task_week',$prev_week_id)->get();
		$get_schemes = \App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->get();
		$arr_scheme = array();
		if(($get_schemes))
		{
			foreach($get_schemes as $scheme)
			{
				array_push($arr_scheme,$scheme->scheme_name);
			}
		}
		if($status == "1")
		{
			if(($get_prev_tasks))
			{
				foreach($get_prev_tasks as $tasks)
				{
					$attachments = \App\Models\taskAttached::where('task_id',$tasks->task_id)->where('network_attach',1)->get();
					if(($attachments))
					{
						foreach($attachments as $attachment)
						{
							$get_name = str_replace(".txt", "", $attachment->attachment);
							if (!in_array($get_name, $arr_scheme))
							{
								$data['task_id'] = $task_id;
								$data['attachment'] = $attachment->attachment;
								$data['url'] = $attachment->url;
								$data['network_attach'] = $attachment->network_attach;
								$data['copied'] = 1;
								$id = \App\Models\taskAttached::insertDetails($data);
							}
						}
					}
				}
			}
		}
		else{
			\App\Models\taskAttached::where('task_id',$task_id)->where('copied',1)->delete();
		}
		if($get_curr_tasks->task_status == 1) { $disabled='disabled'; } elseif($get_curr_tasks->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; }
		$output = '';
		$attachments = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',1)->get();
		if(($attachments))
		{
		  $output.='<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
		  $output.='<h5 style="color:#000; font-weight:600">Files Received :</h5>';
		  $output.='<div class="scroll_attachment_div">';
		      foreach($attachments as $attachment)
		      {
		          $output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
		      }
		  $output.='</div>';
		  $datastarted['task_started'] = 1;
		  \App\Models\task::where('task_id',$task_id)->update($datastarted);
		}	
		echo $output;
	}
	public function check_previous_month(Request $request)
	{
		$task_id = $request->get('task_id');
		$status = $request->get('status');
		$datastatus['same_as_last'] = $status;
		\App\Models\task::where('task_id',$task_id)->update($datastatus);
		$month = $request->get('month');
		$get_prev_month= \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id','<',$month)->orderBy('month_id','desc')->first();
		$prev_month_id = $get_prev_month->month_id;
		$get_curr_tasks = \App\Models\task::where('task_id',$task_id)->first();
		$task_enumber = $get_curr_tasks->task_enumber;
		$get_prev_tasks = \App\Models\task::where('task_enumber',$task_enumber)->where('task_month',$prev_month_id)->get();
		if($status == "1")
		{
			if(($get_prev_tasks))
			{
				foreach($get_prev_tasks as $tasks)
				{
					$attachments = \App\Models\taskAttached::where('task_id',$tasks->task_id)->where('network_attach',1)->get();
					if(($attachments))
					{
						foreach($attachments as $attachment)
						{
							$data['task_id'] = $task_id;
							$data['attachment'] = $attachment->attachment;
							$data['url'] = $attachment->url;
							$data['network_attach'] = $attachment->network_attach;
							$data['copied'] = 1;
							$id = \App\Models\taskAttached::insertDetails($data);
						}
					}
				}
			}
		}
		else{
			\App\Models\taskAttached::where('task_id',$task_id)->where('copied',1)->delete();
		}
		if($get_curr_tasks->task_status == 1) { $disabled='disabled'; } elseif($get_curr_tasks->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; }
		$output = '';
		$attachments = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',1)->get();
		if(($attachments))
		{
		  $output.='<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
		  $output.='<h5 style="color:#000; font-weight:600">Files Received :</h5>';
		  $output.='<div class="scroll_attachment_div">';
		      foreach($attachments as $attachment)
		      {
		          $output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
		      }
		  $output.='</div>';
		  $datastarted['task_started'] = 1;
		  \App\Models\task::where('task_id',$task_id)->update($datastarted);
		}
		echo $output;
	}
	public function change_scheme_status(Request $request)
	{
		$data['status'] = $request->get('status');
		$scheme_id = $request->get('id');
		\App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->where('id',$scheme_id)->update($data);
		if($data['status'] == "1")
		{
			echo URL::to('user/change_scheme_status?status=0&id='.$scheme_id.'');
		}
		else{
			echo URL::to('user/change_scheme_status?status=1&id='.$scheme_id.'');
		}
	}
	public function secret_task_button(Request $request)
	{
		$task_id = $request->get('task_id');
		$get_task_details = \App\Models\task::where('task_id',$task_id)->first();
		$data['enterhours'] = ($get_task_details->enterhours == "2")?"2":"1";
		$data['holiday'] = ($get_task_details->holiday == "2")?"2":"1";
		$data['process'] = ($get_task_details->process == "2")?"2":"1";
		$data['payslips'] = ($get_task_details->payslips == "2")?"2":"1";
		$data['email'] = ($get_task_details->email == "2")?"2":"1";
		$data['upload'] = ($get_task_details->upload == "2")?"2":"1";
		\App\Models\task::where('task_id',$task_id)->update($data);
	}
	public function get_pms_file_attachments(Request $request)
	{
		$task_id = $request->get('task_id');
		$type = $request->get('type');
		if($type == "2")
		{
			$attachments = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',1)->get();
			$files_received = '';
	        if(($attachments))
	        {
	          $files_received.='<h5 style="color:#000; font-weight:600">Files Received : <i class="fa fa-minus-square fadeleteall_attachments" data-element="'.$task_id.'" style="margin-top:10px;color:#fff" aria-hidden="true" title="Delete All Attachments"></i></h5>';
	          $files_received.='<div class="scroll_attachment_div">';
	              foreach($attachments as $attachment)
	              {
	                  $files_received.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	              }
	          $files_received.='</div>';
	          	$data['network_attach'] = 1;          	
				$dataval['task_started'] = 1;
				$dataval['task_notify'] = 1;
				\App\Models\task::where('task_id',$task_id)->update($dataval);
	        }
	        $remaining_days = date('t') - date('j');
	        $datareturn['current_day'] = date("l");
	        $datareturn['remaining_days'] = $remaining_days;
	        $datareturn['files_received'] = $files_received;
	        echo json_encode($datareturn);
		}
		else{
			$attachments = \App\Models\taskAttached::where('task_id',$task_id)->where('network_attach',0)->get();
			$files_attached = '';
			$datasa['bi_payroll_next_status'] = 0;
			$bi_payroll_next_status = 0;
			if(($attachments))
			{
			  $files_attached.= '<i class="fa fa-minus-square fadeleteall" data-element="'.$task_id.'" style="margin-top:-18px;margin-left: 21px;" aria-hidden="true" title="Delete All Attachments"></i>';
			  $files_attached.= '<h5 style="color:#000; font-weight:600;position:absolute;">Attachments :</h5>';
			  $files_attached.= '<div class="scroll_attachment_div">';
			    foreach($attachments as $attachment)
			    {
			        $files_attached.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image sample_trash" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
			    }
			  	$files_attached.= '</div>';
			  	$task_details = \App\Models\task::where('task_id',$task_id)->first();
		        if($task_details->bi_payroll == 1)
				{
					$datasa['bi_payroll_next_status'] = 1;
					$bi_payroll_next_status = 1; 
				}
				\App\Models\task::where('task_id',$task_id)->update($datasa);
			}
	         echo json_encode(array("output" => $files_attached,"bi_payroll_next_status" => $bi_payroll_next_status));
		}
	}
	public function current_payroll_list(Request $request)
	{
		$year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
		$current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('week_id','desc')->first();
		$current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('month_id','desc')->first();
		$columns = array('Task Name','Period','Network Location','Primary Email','Secondary Email', 'Number of times Email sent','PayePRSI liability');
		$file = fopen('public/papers/current_payroll_lists.csv', 'w');
		fputcsv($file, $columns);
		$tasks = \App\Models\task::where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->get();
		if(($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_week != 0) { $period = 'Weekly'; }
				else { $period = 'Monthly'; }
				$get_dates = \App\Models\taskEmailSent::where('task_id',$task->task_id)->where('options','!=','n')->get();
				$columns1 = array($task->task_name,$period,$task->network,$task->task_email,$task->secondary_email,count($get_dates),$task->liability);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo 'current_payroll_lists.csv';
	}
	public function start_rating(Request $request)
	{
		$taskid = $request->get('taskid');
		$value = $request->get('value');
		$data['rating'] = $value;
		\App\Models\task::where('task_id',$taskid)->update($data);
	}
	public function load_dashboard_tiles(Request $request)
	{
		$system = $request->get('system');
		$output = '';
		if($system == "cm")
		{
			$output.= '<div class="title">Cm System</div>
              <div class="ul_list">
                <ul>';
                $total_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->count();
                $active_cm_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('active',1)->count();
                  $output.='<li>Total  Clients : '.$total_clients.'</li>
                  <li>Active  Clients : '.$active_cm_clients.'</li>
                  <li><a href="'.URL::to('user/client_account_review').'" style="color:#fff">Client Account Review</a></li>
                </ul>
              </div>';
		}
		elseif($system == "week")
		{
			$output.= '<div class="title">Weekly Payroll</div>
              <div class="ul_list">';
              $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
              $current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('week_id','desc')->first();
              $current_year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id',$current_week->year)->first();
              $no_of_tasks = \App\Models\task::where('task_week',$current_week->week_id)->count();
              $week_completed = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',1)->count();
              $week_donot_completed = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
                $output.='<div class="sub-title">Week #'.$current_week->week.', '.$current_year->year_name.' - '.$no_of_tasks.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed.'</li>
                  <li>Donot Complete tasks : '.$week_donot_completed.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted.'</li>
                </ul>
              </div>';
		}
		elseif($system == "month")
		{
			$output.= '
              <div class="title">Monthly Payroll</div>';
              $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
              $current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('month_id','desc')->first();
              $current_year = \App\Models\Year::where('year_id',$current_month->year)->first();
              $no_of_tasks_month = \App\Models\task::where('task_month',$current_month->month_id)->count();
              $week_completed_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',1)->count();
              $week_donot_completed_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
              $output.='<div class="ul_list">
                <div class="sub-title">Month #'.$current_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_month.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed_month.'</li>
                  <li>Donot Complete tasks : '.$week_donot_completed_month.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted_month.'</li>
                </ul>
              </div>';
		}
		elseif($system == "p30")
		{
			$output.= '
              <div class="title">P30 system</div>
              <div class="ul_list">';
              $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
              
              $current_year = \App\Models\Year::where('year_id',$current_p30_month->year)->first();
              $no_of_tasks_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->count();
              $week_completed_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
              $week_incompleted_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
                $output.='<div class="sub-title">Month #'.$current_p30_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_p30_month.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed_p30_month.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted_p30_month.'</li>
                </ul>
              </div>';
		}
		elseif($system == "vat")
		{
			$output.= '
              <div class="title">VAT system</div>';
              $disabled_clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',1)->count();
              $clients_email =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
              $clients_without_email =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
              $self_manage =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('self_manage','yes')->count();
              $output.='<div class="ul_list">                
                <ul>
                  <li>Disabled Clients : '.$disabled_clients.'</li>
                  <li>Clients With Email : '.$clients_email.'</li>
                  <li>Clients Without Email: '.$clients_without_email.'</li>
                  <li>Self Managed  : '.$self_manage.'</li>
                </ul>
              </div>';
		}
		elseif($system == "infile")
		{
			$output.= '
              <div class="title">In Files System</div>';
              $infile_client = \App\Models\inFile::select('id','client_id')->where('client_id','!=','')->get();
              $array_clientid = array();
              $file_count = 0;
              if(($infile_client))
              {
              	foreach($infile_client as $infile)
              	{
              		if(!in_array($infile->client_id,$array_clientid))
              		{
              			$check_attachment = \App\Models\inFileAttachment::where('file_id',$infile->id)->count();
	              		if($check_attachment > 0)
	              		{
	              			$file_count++;
	              			array_push($array_clientid, $infile->client_id);
	              		}
              		}
              	}
              }
              $infile_complete = \App\Models\inFile::where('status', 1)->count();
              $infile_incomplete = \App\Models\inFile::where('status', 0)->count();
              $output.='<div class="ul_list">                
                <ul>
                  <li>No. of Clients with In Files : '.$file_count.'</li>
                  <li>No. of Complete In Files : '.$infile_complete.'</li>
                  <li>No. of InComplete In Files : '.$infile_incomplete.'</li>
                </ul>
              </div>';
		}
		elseif($system == "yearend")
		{
			$output.= '
              <div class="title">Yearend System</div>
                <div class="ul_list">';
                  $yearend_year = \App\Models\YearEndYear::orderBy('id','desc')->limit(3)->get();
                  if(($yearend_year))
                  {
                    foreach($yearend_year as $year)
                    {
                      $started =\App\Models\YearClient::where('year',$year->year)->where('status',0)->count();
                      $in_progress =\App\Models\YearClient::where('year',$year->year)->where('status',1)->count();
                      $completed =\App\Models\YearClient::where('year',$year->year)->where('status',2)->count();
                        $output.='<div class="col-md-4 col-lg-4">
                          <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : '.$year->year.'</li>
                          <li>Not Started :  '.$started.' Clients</li>
                          <li>Inprogress : '.$in_progress.' Clients</li>
                          <li>Completed : '.$completed.' Clients</li>
                          </ul>
                        </div>';
                    }
                    if(count($yearend_year))
                    {
                      $output.='<div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>';
                    }
                    elseif(count($yearend_year) == 1)
                    { 
                      $output.='<div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>
                      <div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2016</li>
                          <li>No Records found</li>
                        </ul>
                      </div>';
                    } 
                  }
                $output.='</div>';
		}
		elseif($system == "crm")
		{
			$output.= '
              <div class="title">Client Request Manager</div>';
                $i=1;
                $clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
                $countoutstanding = 0;
                $awaiting_request_count = 0;
                $request_count_count = 0;
                if(($clientlist)){              
                  foreach($clientlist as $key => $client){
                    $awaiting_request = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 0)->count();
                    $request_count = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 1)->count();
                    $awaiting_request_count = $awaiting_request_count + $awaiting_request;
                    $request_count_count = $request_count_count + $request_count;
                    $get_req = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 1)->get();
                    if(($get_req))
                    {
                      foreach($get_req as $req)
                      {
                          $check_received_purchase = \App\Models\requestPurchaseInvoice::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_purchase_attached = \App\Models\requestPurchaseAttached::where('request_id',$req->request_id)->where('status',0)->count(); 
                          $check_received_sales = \App\Models\requestSalesInvoice::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_sales_attached = \App\Models\requestSalesAttached::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_bank = \App\Models\requestBankStatement::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_cheque = \App\Models\requestCheque::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_cheque_attached = \App\Models\requestChequeAttached::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_others = \App\Models\requestOthers::where('request_id',$req->request_id)->where('status',0)->count();
                          $check_purchase = \App\Models\requestPurchaseInvoice::where('request_id',$req->request_id)->count();
                          $check_purchase_attached = \App\Models\requestPurchaseAttached::where('request_id',$req->request_id)->count(); 
                          $check_sales = \App\Models\requestSalesInvoice::where('request_id',$req->request_id)->count();
                          $check_sales_attached = \App\Models\requestSalesAttached::where('request_id',$req->request_id)->count();
                          $check_bank = \App\Models\requestBankStatement::where('request_id',$req->request_id)->count();
                          $check_cheque = \App\Models\requestCheque::where('request_id',$req->request_id)->count();
                          $check_cheque_attached = \App\Models\requestChequeAttached::where('request_id',$req->request_id)->count();
                          $check_others = \App\Models\requestOthers::where('request_id',$req->request_id)->count();
                          $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;
                          $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;
                          if($countval_not_received != 0)
                          {
                            $countoutstanding++;
                          }
                      }
                    }
                  }
                }
              $output.='<div class="ul_list">                
                <ul>
                  <li>Total Requests : '.$request_count_count.'</li>
                  <li>Total Outstanding Requests : '.$countoutstanding.'</li>
                  <li>Total Awaiting Approval : '.$awaiting_request_count.'</li>
                </ul>
              </div>';
		}
		echo $output;
	}
	public function load_all_dashboard_tiles(Request $request)
	{
		$output = '<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle cmsystem">
			      <div class="content">
			        <div class="title">Cm System</div>
			        <div class="ul_list">
			          <ul>';
			          $total_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->count();
			          $active_cm_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('active',1)->count();
			            $output.='<li>Total  Clients : '.$total_clients.'</li>
			            <li>Active  Clients : '.$active_cm_clients.'</li>
			            <li><a href="'.URL::to('user/client_account_review').'" style="color:#fff">Client Account Review</a></li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="cm"></a> 
			      	<img src="'.URL::to('public/assets/images/icon_cm_system.jpg') .'">
			      </div>            
			    </div>
			    <div class="more morecmsystem">
			          <a href="'.URL::to('user/client_management').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle week">
			      <div class="content">
			        <div class="title">Weekly Payroll</div>
			        <div class="ul_list">';
			        $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();

			        $current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('week_id','desc')->first();
			        $current_year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where('year_id',$current_week->year)->first();
			        $no_of_tasks = \App\Models\task::where('task_week',$current_week->week_id)->count();
			        $week_completed = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',1)->count();
			        $week_donot_completed = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
			        $week_incompleted = \App\Models\task::where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
			          $output.='<div class="sub-title">Week #'.$current_week->week.', '.$current_year->year_name.' - '.$no_of_tasks.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed.'</li>
			            <li>Donot Complete tasks : '.$week_donot_completed.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="week"></a> 
			      	<img src="'.URL::to('public/assets/images/icon_week_task.jpg').'">
			      </div>            
			    </div>
			    <div class="more moreweek">
			          <a href="'.URL::to('user/manage_week').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle month">
			      <div class="content">
			        <div class="title">Monthly Payroll</div>';
			        $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
			        $current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('month_id','desc')->first();
			        $current_year = \App\Models\Year::where('year_id',$current_month->year)->first();
			        $no_of_tasks_month = \App\Models\task::where('task_month',$current_month->month_id)->count();
			        $week_completed_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',1)->count();
			        $week_donot_completed_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
			        $week_incompleted_month = \App\Models\task::where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
			        $output.='<div class="ul_list">
			          <div class="sub-title">Month #'.$current_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_month.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed_month.'</li>
			            <li>Donot Complete tasks : '.$week_donot_completed_month.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted_month.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="month"></a> 
			      	<img src="'.URL::to('public/assets/images/icon_month_task.jpg').'">
			      </div>            
			    </div>
			    <div class="more moremonth">
			          <a href="'.URL::to('user/manage_month').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle p30">
			      <div class="content">
			        <div class="title">P30 system</div>
			        <div class="ul_list">';
			        $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
			        $current_year = \App\Models\Year::where('year_id',$current_p30_month->year)->first();
			        $no_of_tasks_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->count();
			        $week_completed_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
			        $week_incompleted_p30_month = \App\Models\p30Task::where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
			          $output.='<div class="sub-title">Month #'.$current_p30_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_p30_month.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed_p30_month.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted_p30_month.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="p30"></a> 
			      	<img src="'.URL::to('public/assets/images/icon_p30.jpg').'">
			      </div>            
			    </div>
			    <div class="more morep30">
			          <a href="'.URL::to('user/p30').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="clearfix"></div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle vat">
			      <div class="content">
			        <div class="title">VAT system</div>';
			        $disabled_clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',1)->count();
			        $clients_email =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
			        $clients_without_email =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
			        $self_manage =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('status',0)->where('self_manage','yes')->count();
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>Disabled Clients : '.$disabled_clients.'</li>
			            <li>Clients With Email : '.$clients_email.'</li>
			            <li>Clients Without Email: '.$clients_without_email.'</li>
			            <li>Self Managed  : '.$self_manage.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="vat"></a> 
			      	<img src="'.URL::to('public/assets/images/icon_vat.jpg').'">
			      </div>            
			    </div>
			    <div class="more morevat">
			          <a href="'.URL::to('user/vat_clients').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle infile">
			      <div class="content">
			        <div class="title">In Files System</div>';
			        $infile_client = \App\Models\inFile::select('id','client_id')->where('client_id','!=','')->get();
			        $array_clientid = array();
			        $file_count = 0;
			        if(($infile_client))
			        {
			        	foreach($infile_client as $infile)
			        	{
			        		if(!in_array($infile->client_id,$array_clientid))
			        		{
			        			$check_attachment = \App\Models\inFileAttachment::where('file_id',$infile->id)->count();
			          		if($check_attachment > 0)
			          		{
			          			$file_count++;
			          			array_push($array_clientid, $infile->client_id);
			          		}
			        		}
			        	}
			        }
			        $infile_complete = \App\Models\inFile::where('status', 1)->count();
			        $infile_incomplete = \App\Models\inFile::where('status', 0)->count();
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>No. of Clients with In Files : '.$file_count.'</li>
			            <li>No. of Complete In Files : '.$infile_complete.'</li>
			            <li>No. of InComplete In Files : '.$infile_incomplete.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="infile"></a> 
			      	<img src="'.URL::to('public/assets/images/infile_icon.jpg').'">
			      </div>            
			    </div>
			    <div class="more moreinfile">
			          <a href="'.URL::to('user/in_file_advance').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-5">
			  <div class="dashboard">
			    <div class="dashboard_signle yearend">
			      <div class="content" style="width:100%">
			        <div class="title">Yearend System</div>
			          <div class="ul_list">';
			            $yearend_year = \App\Models\YearEndYear::orderBy('id','desc')->limit(3)->get();
			            if(($yearend_year))
			            {
			              foreach($yearend_year as $year)
			              {
			                $started =\App\Models\YearClient::where('year',$year->year)->where('status',0)->count();
			                $in_progress =\App\Models\YearClient::where('year',$year->year)->where('status',1)->count();
			                $completed =\App\Models\YearClient::where('year',$year->year)->where('status',2)->count();
			                  $output.='<div class="col-md-4 col-lg-4">
			                    <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : '.$year->year.'</li>
			                    <li>Not Started :  '.$started.' Clients</li>
			                    <li>Inprogress : '.$in_progress.' Clients</li>
			                    <li>Completed : '.$completed.' Clients</li>
			                    </ul>
			                  </div>';
			              }
			              if(count($yearend_year) == 2)
			              {
			                $output.'<div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>';
			              }
			              elseif(count($yearend_year) == 1)
			              {
			                $output.='<div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>
			                <div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2016</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>';
			              } 
			            }
			          $output.='</div>
			      </div> 
			      <a href="javascript:" class="fa fa-refresh load_system" data-element="yearend"></a>         
			    </div>
			    <div class="more moreyearend">
			          <a href="'.URL::to('user/year_end_manager').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle crm">
			      <div class="content crm_content">
			        <div class="title">Client Request Manager</div>';
			          $i=1;
			          $clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
			          $countoutstanding = 0;
			          $awaiting_request_count = 0;
			          $request_count_count = 0;
			          if(($clientlist)){              
			            foreach($clientlist as $key => $client){
			              $awaiting_request = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 0)->count();
			              $request_count = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 1)->count();
			              $awaiting_request_count = $awaiting_request_count + $awaiting_request;
			              $request_count_count = $request_count_count + $request_count;
			              $get_req = \App\Models\requestClient::where('client_id', $client->client_id)->where('status', 1)->get();
			              if(($get_req))
			              {
			                foreach($get_req as $req)
			                {
			                    $check_received_purchase = \App\Models\requestPurchaseInvoice::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_purchase_attached = \App\Models\requestPurchaseAttached::where('request_id',$req->request_id)->where('status',0)->count(); 
			                    $check_received_sales = \App\Models\requestSalesInvoice::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_sales_attached = \App\Models\requestSalesAttached::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_bank = \App\Models\requestBankStatement::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_cheque = \App\Models\requestCheque::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_cheque_attached = \App\Models\requestChequeAttached::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_others = \App\Models\requestOthers::where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_purchase = \App\Models\requestPurchaseInvoice::where('request_id',$req->request_id)->count();
			                    $check_purchase_attached = \App\Models\requestPurchaseAttached::where('request_id',$req->request_id)->count(); 
			                    $check_sales = \App\Models\requestSalesInvoice::where('request_id',$req->request_id)->count();
			                    $check_sales_attached = \App\Models\requestSalesAttached::where('request_id',$req->request_id)->count();
			                    $check_bank = \App\Models\requestBankStatement::where('request_id',$req->request_id)->count();
			                    $check_cheque = \App\Models\requestCheque::where('request_id',$req->request_id)->count();
			                    $check_cheque_attached = \App\Models\requestChequeAttached::where('request_id',$req->request_id)->count();
			                    $check_others = \App\Models\requestOthers::where('request_id',$req->request_id)->count();
			                    $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;
			                    $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;
			                    if($countval_not_received != 0)
			                    {
			                      $countoutstanding++;
			                    }
			                }
			              }
			            }
			          }
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>Total Requests : '.$request_count_count.'</li>
			            <li>Total Outstanding Requests : '.$countoutstanding.'</li>
			            <li>Total Awaiting Approval : '.$awaiting_request_count.'</li>
			          </ul>
			        </div>
			      </div>
			      <a href="javascript:" class="fa fa-refresh load_system" data-element="crm"></a>      
			    </div>
			    <div class="more morecrm">
			          <a href="'.URL::to('user/client_request_system').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>';
			echo $output;
	}
	public function change_period_vat_reviews(Request $request)
	{
		$month = $request->get('month');
		$client = $request->get('client');
		$from = $request->get('from');
		$to = $request->get('to');
		$check_reviews = \App\Models\vatReviews::where('client_id',$client)->where('month_year',$month)->where('type',2)->first();
		$data['client_id']= $client;
		$data['month_year']= $month;
		$data['type']= "2";
		$data['from_period'] = $from;
		$data['to_period'] = $to;
		if(($check_reviews))
		{
		\App\Models\vatReviews::where('id',$check_reviews->id)->update($data);
		}
		else{
		\App\Models\vatReviews::insert($data);	
		}
	}
	public function check_submitted_date_vat_reviews(Request $request)
	{
		$month = $request->get('month');
		$client = $request->get('client');
		$curr_date = date('d-M-Y');
		$data['client_id']= $client;
		$data['month_year']= $month;
		$data['type']= "3";
		$data['textval'] = $curr_date;
		$data['status'] = "0";
		$check_reviews = \App\Models\vatReviews::where('client_id',$client)->where('month_year',$month)->where('type',3)->first();
		if(($check_reviews))
		{
		\App\Models\vatReviews::where('id',$check_reviews->id)->update($data);
		}
		else{
		\App\Models\vatReviews::insert($data);	
		}
		echo $curr_date;
	}
	public function check_valid_ros_due(Request $request)
	{
		$filename = $request->get('filename');
		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;

		$objPHPExcel = IOFactory::load($filepath);
		$i = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;
			$client_name_header = $worksheet->getCellByColumnAndRow(1, 1); $client_name_header = trim($client_name_header->getValue());
			$ros_filer_header = $worksheet->getCellByColumnAndRow(2, 1); $ros_filer_header = trim($ros_filer_header->getValue());
			$tax_type_header = $worksheet->getCellByColumnAndRow(3, 1); $tax_type_header = trim($tax_type_header->getValue());
			$doc_type_header = $worksheet->getCellByColumnAndRow(4, 1); $doc_type_header = trim($doc_type_header->getValue());
			$tax_no_header = $worksheet->getCellByColumnAndRow(5, 1); $tax_no_header = trim($tax_no_header->getValue());
			$period_header = $worksheet->getCellByColumnAndRow(6, 1); $period_header = trim($period_header->getValue());
			$due_date_header = $worksheet->getCellByColumnAndRow(7, 1); $due_date_header = trim($due_date_header->getValue());
			if($client_name_header == "Customer Name" && $period_header == "Period" && $due_date_header == "Due Date")
			{
				$client_name = $worksheet->getCellByColumnAndRow(1, 2); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(2, 2); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(3, 2); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(4, 2); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(5, 2);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(6, 2);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(7, 2);  $due_date = trim($due_date->getValue());
				if($tax_type == "VAT" && $doc_type == "VAT3")
				{
					$i = $i + 1;
				}
			}
		}
		$output = '';
		if($i > 0)
		{
			$output.='<tr>
				<td>'.$filename.'</td>
				<td>'.date('d-M-Y').'</td>
				<td>'.date('H:i:s').'</td>
			</tr>';
		}
		echo $output;
	}
	public function process_vat_reviews(Request $request)
	{
		$filename = $request->get('filename');
		$load_all = $request->get('load_all');
		$get_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->get();
		$data['import_id'] = count($get_id) + 1;
		$data['status'] = 1;
		 \App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('filename',$filename)->update($data);
		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;
		$objPHPExcel = IOFactory::load($filepath);
		$i = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			if($highestRow > 500)
			{
				$height = 500;
			}
			else{
				$height = $highestRow;
			}
			$client_name_header = $worksheet->getCellByColumnAndRow(1, 1); $client_name_header = trim($client_name_header->getValue());
			$ros_filer_header = $worksheet->getCellByColumnAndRow(2, 1); $ros_filer_header = trim($ros_filer_header->getValue());
			$tax_type_header = $worksheet->getCellByColumnAndRow(3, 1); $tax_type_header = trim($tax_type_header->getValue());
			$doc_type_header = $worksheet->getCellByColumnAndRow(4, 1); $doc_type_header = trim($doc_type_header->getValue());
			$tax_no_header = $worksheet->getCellByColumnAndRow(5, 1); $tax_no_header = trim($tax_no_header->getValue());
			$period_header = $worksheet->getCellByColumnAndRow(6, 1); $period_header = trim($period_header->getValue());
			$due_date_header = $worksheet->getCellByColumnAndRow(7, 1); $due_date_header = trim($due_date_header->getValue());
			if($client_name_header == "Customer Name" && $period_header == "Period" && $due_date_header == "Due Date")
			{
				for ($row = 2; $row <= $height; ++ $row) {
					$client_name = $worksheet->getCellByColumnAndRow(1,$row); $client_name = trim($client_name->getValue());
					$ros_filter = $worksheet->getCellByColumnAndRow(2,$row); $ros_filter = trim($ros_filter->getValue());
					$tax_type = $worksheet->getCellByColumnAndRow(3,$row); $tax_type = trim($tax_type->getValue());
					$doc_type = $worksheet->getCellByColumnAndRow(4,$row); $doc_type = trim($doc_type->getValue());
					$tax_no = $worksheet->getCellByColumnAndRow(5,$row);  $tax_no = trim($tax_no->getValue());
					$period = $worksheet->getCellByColumnAndRow(6,$row);  $period = trim($period->getValue());
					$due_date = $worksheet->getCellByColumnAndRow(7,$row);  $due_date = trim($due_date->getValue());
					if($tax_type == "VAT" && $doc_type == "VAT3")
					{
						$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
						if(($check_tax))
						{
							$due_month_year = '';
							$exp_due_date = explode("/",$due_date);
							if(count($exp_due_date) > 2)
							{
								$due_month_year =  $exp_due_date[1].'-'.$exp_due_date[2];
							}
							else{
								$exp_due_date_hyphen = explode("-",$due_date);
								if(count($exp_due_date_hyphen) > 2)
								{
									$due_month_year =  $exp_due_date_hyphen[1].'-'.$exp_due_date_hyphen[2];
								}
							}
							$from_period = '';
							$to_period = '';
							$exp_full_period = explode("-",$period);
							if(count($exp_full_period) == 2)
							{
								$exp_from_period = explode("/",trim($exp_full_period[0]));
								if(count($exp_from_period) == 3)
								{
									if($exp_from_period[1] == "01") { $from_month = 'Jan'; }
									elseif($exp_from_period[1] == "02") { $from_month = 'Feb'; }
									elseif($exp_from_period[1] == "03") { $from_month = 'Mar'; }
									elseif($exp_from_period[1] == "04") { $from_month = 'Apr'; }
									elseif($exp_from_period[1] == "05") { $from_month = 'May'; }
									elseif($exp_from_period[1] == "06") { $from_month = 'Jun'; }
									elseif($exp_from_period[1] == "07") { $from_month = 'Jul'; }
									elseif($exp_from_period[1] == "08") { $from_month = 'Aug'; }
									elseif($exp_from_period[1] == "09") { $from_month = 'Sep'; }
									elseif($exp_from_period[1] == "10") { $from_month = 'Oct'; }
									elseif($exp_from_period[1] == "11") { $from_month = 'Nov'; }
									elseif($exp_from_period[1] == "12") { $from_month = 'desc'; }
									$from_period = $from_month.'-'.$exp_from_period[2];
								}
								$exp_to_period = explode("/",trim($exp_full_period[1]));
								if(count($exp_from_period) == 3)
								{
									if($exp_to_period[1] == "01") { $to_month = 'Jan'; }
									elseif($exp_to_period[1] == "02") { $to_month = 'Feb'; }
									elseif($exp_to_period[1] == "03") { $to_month = 'Mar'; }
									elseif($exp_to_period[1] == "04") { $to_month = 'Apr'; }
									elseif($exp_to_period[1] == "05") { $to_month = 'May'; }
									elseif($exp_to_period[1] == "06") { $to_month = 'Jun'; }
									elseif($exp_to_period[1] == "07") { $to_month = 'Jul'; }
									elseif($exp_to_period[1] == "08") { $to_month = 'Aug'; }
									elseif($exp_to_period[1] == "09") { $to_month = 'Sep'; }
									elseif($exp_to_period[1] == "10") { $to_month = 'Oct'; }
									elseif($exp_to_period[1] == "11") { $to_month = 'Nov'; }
									elseif($exp_to_period[1] == "12") { $to_month = 'desc'; }
									$to_period = $to_month.'-'.$exp_to_period[2];
								}
							}
							$check_submitted = \App\Models\vatReviews::where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',2)->first();
							if(($check_submitted))
							{
								$dataval['from_period'] = $from_period;
								$dataval['to_period'] = $to_period;
							\App\Models\vatReviews::where('id',$check_submitted->id)->update($dataval);
							}
							else{
								$dataval['client_id'] = $check_tax->client_id;
								$dataval['month_year'] = $due_month_year;
								$dataval['type'] = 2;
								$dataval['from_period'] = $from_period;
								$dataval['to_period'] = $to_period;
							\App\Models\vatReviews::insert($dataval);
							}
							$check_submitted = \App\Models\vatReviews::where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',4)->first();
							if(($check_submitted))
							{
								$dataid['textval'] = $data['import_id'];
							\App\Models\vatReviews::where('id',$check_submitted->id)->update($dataid);
							}
							else{
								$dataid['client_id'] = $check_tax->client_id;
								$dataid['month_year'] = $due_month_year;
								$dataid['type'] = 4;
								$dataid['textval'] = $data['import_id'];
							\App\Models\vatReviews::insert($dataid);
							}
						}
					}
				}
			}
		}
		if($height >= $highestRow)
		{
			$get_highest_year = \App\Models\vatReviews::select(DB::raw('SUBSTRING(month_year, 4, 6) as vat_year'))->groupBy('month_year')->orderBy('vat_year','desc')->first();

			if($get_highest_year) {
				$vat_year = $get_highest_year->vat_year;

				if(is_numeric($vat_year) == 1){

					$get_vat_years =\App\Models\vatYear::orderBy('id','desc')->get();

					if(is_countable($get_vat_years) && count($get_vat_years) > 0){

						if($vat_year > $get_vat_years[0]->year_name) {
							$next_year = $get_vat_years[0]->year_name + 1;
							for($iyear = $next_year; $iyear <= $vat_year; $iyear++) {
								$datayear['year_name'] = $iyear;
								 \App\Models\vatYear::insert($datayear);
							}
						}
					}
				}
			}
			if($load_all == "1")
			{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.')->with("load_all","1");
			}
			else{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.');
			}
		}
		else{
			return redirect('user/vat_review?filename='.$filename.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1&load_all='.$load_all.'');
		}
	}
	public function process_vat_reviews_one(Request $request)
	{
		$filename = $request->get('filename');
		$load_all = $request->get('load_all');
		$get_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->get();
		$data['import_id'] = ($get_id) + 1;
		 \App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('filename',$filename)->update($data);
		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;
		$objPHPExcel = IOFactory::load($filepath);
		$i = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$round = $request->get('round');
			$last_height = $request->get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(1,$row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(2,$row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(3,$row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(4,$row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(5,$row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(6,$row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(7,$row);  $due_date = trim($due_date->getValue());
				if($tax_type == "VAT" && $doc_type == "VAT3")
				{
					$check_tax =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('taxnumber',$tax_no)->first();
					if(($check_tax))
					{
						$due_month_year = '';
						$exp_due_date = explode("/",$due_date);
						if(count($exp_due_date) > 2)
						{
							$due_month_year =  $exp_due_date[1].'-'.$exp_due_date[2];
						}
						else{
							$exp_due_date_hyphen = explode("-",$due_date);
							if(count($exp_due_date_hyphen) > 2)
							{
								$due_month_year =  $exp_due_date_hyphen[1].'-'.$exp_due_date_hyphen[2];
							}
						}
						$from_period = '';
						$to_period = '';
						$exp_full_period = explode("-",$period);
						if(count($exp_full_period) == 2)
						{
							$exp_from_period = explode("/",trim($exp_full_period[0]));
							if(count($exp_from_period) == 3)
							{
								if($exp_from_period[1] == "01") { $from_month = 'Jan'; }
								elseif($exp_from_period[1] == "02") { $from_month = 'Feb'; }
								elseif($exp_from_period[1] == "03") { $from_month = 'Mar'; }
								elseif($exp_from_period[1] == "04") { $from_month = 'Apr'; }
								elseif($exp_from_period[1] == "05") { $from_month = 'May'; }
								elseif($exp_from_period[1] == "06") { $from_month = 'Jun'; }
								elseif($exp_from_period[1] == "07") { $from_month = 'Jul'; }
								elseif($exp_from_period[1] == "08") { $from_month = 'Aug'; }
								elseif($exp_from_period[1] == "09") { $from_month = 'Sep'; }
								elseif($exp_from_period[1] == "10") { $from_month = 'Oct'; }
								elseif($exp_from_period[1] == "11") { $from_month = 'Nov'; }
								elseif($exp_from_period[1] == "12") { $from_month = 'desc'; }
								$from_period = $from_month.'-'.$exp_from_period[2];
							}
							$exp_to_period = explode("/",trim($exp_full_period[1]));
							if(count($exp_from_period) == 3)
							{
								if($exp_to_period[1] == "01") { $to_month = 'Jan'; }
								elseif($exp_to_period[1] == "02") { $to_month = 'Feb'; }
								elseif($exp_to_period[1] == "03") { $to_month = 'Mar'; }
								elseif($exp_to_period[1] == "04") { $to_month = 'Apr'; }
								elseif($exp_to_period[1] == "05") { $to_month = 'May'; }
								elseif($exp_to_period[1] == "06") { $to_month = 'Jun'; }
								elseif($exp_to_period[1] == "07") { $to_month = 'Jul'; }
								elseif($exp_to_period[1] == "08") { $to_month = 'Aug'; }
								elseif($exp_to_period[1] == "09") { $to_month = 'Sep'; }
								elseif($exp_to_period[1] == "10") { $to_month = 'Oct'; }
								elseif($exp_to_period[1] == "11") { $to_month = 'Nov'; }
								elseif($exp_to_period[1] == "12") { $to_month = 'desc'; }
								$to_period = $to_month.'-'.$exp_to_period[2];
							}
						}
						$check_submitted = \App\Models\vatReviews::where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',2)->first();
						if(($check_submited))
						{
							$dataval['from_period'] = $from_period;
							$dataval['to_period'] = $to_period;
						\App\Models\vatReviews::where('id',$check_submited->id)->update($dataval);
						}
						else{
							$dataval['client_id'] = $check_tax->client_id;
							$dataval['month_year'] = $$due_month_year;
							$dataval['type'] = 2;
							$dataval['from_period'] = $from_period;
							$dataval['to_period'] = $to_period;
						\App\Models\vatReviews::insert($dataval);
						}
						$check_submitted = \App\Models\vatReviews::where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',4)->first();
						if(($check_submited))
						{
							$dataid['textval'] = $data['import_id'];
						\App\Models\vatReviews::where('id',$check_submited->id)->update($dataid);
						}
						else{
							$dataid['client_id'] = $check_tax->client_id;
							$dataid['month_year'] = $$due_month_year;
							$dataid['type'] = 4;
							$dataid['textval'] = $data['import_id'];
						\App\Models\vatReviews::insert($dataid);
						}
					}
				}
			}
		}
		if($height >= $highestRow)
		{
			$get_highest_year = \App\Models\vatReviews::select(DB::raw('SUBSTRING(month_year, 4, 6) as vat_year'))->groupBy('month_year')->orderBy('vat_year','desc')->first();
			
			if($get_highest_year) {
				$vat_year = $get_highest_year->vat_year;
				if(is_numeric($vat_year) == 1){
					$get_vat_years =\App\Models\vatYear::orderBy('id','desc')->get();
					if(is_countable($get_vat_years) && count($get_vat_years) > 0){
						if($vat_year > $get_vat_years[0]->year_name) {
							$next_year = $get_vat_years[0]->year_name + 1;
							for($iyear = $next_year; $iyear <= $vat_year; $iyear++) {
								$datayear['year_name'] = $iyear;
								 \App\Models\vatYear::insert($datayear);
							}
						}
					}
				}
			}
			if($load_all == "1")
			{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.')->with("load_all","1");
			}
			else{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.');
			}
		}
		else{
			return redirect('user/vat_review?filename='.$filename.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type=1&load_all='.$load_all.'');
		}
	}
	public function remove_vat_csv(Request $request, $id = '')
	{
		 \App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->delete();
	}
	public function delete_submitted_vat_review(Request $request)
	{
		$month = $request->get('month');
		$client = $request->get('client');
	\App\Models\vatReviews::where('month_year',$month)->where('client_id',$client)->where('type',3)->delete();
	}
	public function update_records_received(Request $request)
	{
		$client = $request->get('client_id');
		$month = $request->get('month');
		$type = $request->get('type');
		if($type == "1")
		{
			$data['client_id'] = $client;
			$data['month_year'] = $month;
			$data['type'] = 6;
			$data['textval'] = 1;
		\App\Models\vatReviews::insert($data);
		}
		else{
		\App\Models\vatReviews::where('client_id',$client)->where('month_year',$month)->where('type',6)->delete();
		}
	}
	public function show_journal_viewer_by_journal_id(Request $request)
	{
		$journal_id = $request->get('journal_id');
		$details = \App\Models\Journals::where('connecting_journal_reference',$journal_id)->first();
		$get_details = \App\Models\Journals::where('reference',$details->reference)->orderBy('connecting_journal_reference','asc')->get();
		$output = '';
		$total_debit_value = 0;
		$total_credit_value = 0;
		if(($get_details))
		{
			foreach($get_details as $detail)
			{
				$nominal_des = \App\Models\NominalCodes::where('code',$detail->nominal_code)->first();
				$desval = '';
				if(($nominal_des))
				{
					$desval = $nominal_des->description;
				}
				$output.='<tr>
					<td>'.$detail->connecting_journal_reference.'</td>
					<td>'.date('d-M-Y', strtotime($detail->journal_date)).'</td>
					<td>'.$detail->description.'</td>
					<td><a href="javascript:" class="journal_source_link">'.$detail->journal_source.'</a></td>
					<td>'.$detail->nominal_code.'</td>
					<td>'.$desval.'</td>
					<td style="text-align: right;">'.number_format_invoice_empty($detail->dr_value).'</td>
					<td style="text-align: right;">'.number_format_invoice_empty($detail->cr_value).'</td>
				</tr>';
				$total_debit_value = $total_debit_value + number_format_invoice_without_comma($detail->dr_value);
				$total_credit_value = $total_credit_value + number_format_invoice_without_comma($detail->cr_value);
			}
		}
		echo json_encode(array("output" => $output, "total_debit" => number_format_invoice_empty($total_debit_value), "total_credit" => number_format_invoice_empty($total_credit_value)));
	}
	public function download_journal_viewer_by_journal_id(Request $request)
	{
		$journal_id = $request->get('journal_id');
		$details = \App\Models\Journals::where('connecting_journal_reference',$journal_id)->first();
		$get_details = \App\Models\Journals::where('reference',$details->reference)->get();
		$exp_journal = explode(".",$journal_id);
		$output = '
		<h5 style="text-align:center">Journal Viewer for Journal Reference ID - '.$exp_journal[0].'</h5>
		<table style="width: 100%;border-collapse:collapse">
          <tr>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal ID</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Date</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal Description</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal Source</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Nominal Code</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Nominal Description</td>
            <td style="text-align: right;border:1px solid #000;padding:5px">Debit Value</td>
            <td style="text-align: right;border:1px solid #000;padding:5px">Credit Value</td>
          </tr>';
		$total_debit_value = 0;
		$total_credit_value = 0;
		if(($get_details))
		{
			foreach($get_details as $detail)
			{
				$nominal_des = \App\Models\NominalCodes::where('code',$detail->nominal_code)->first();
				$output.='<tr>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->connecting_journal_reference.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.date('d-M-Y', strtotime($detail->journal_date)).'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->description.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->journal_source.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->nominal_code.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$nominal_des->description.'</td>
					<td style="text-align: right;border:1px solid #000;padding:5px">'.number_format_invoice_empty($detail->dr_value).'</td>
					<td style="text-align: right;border:1px solid #000;padding:5px">'.number_format_invoice_empty($detail->cr_value).'</td>
				</tr>';
				$total_debit_value = $total_debit_value + number_format_invoice_without_comma($detail->dr_value);
				$total_credit_value = $total_credit_value + number_format_invoice_without_comma($detail->cr_value);
			}
		}
		$output.='<tr>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;font-weight:800;padding:5px">TOTAL</td>
			<td style="text-align: right;border:1px solid #000;font-weight:800;padding:5px">'.number_format_invoice_empty($total_debit_value).'</td>
			<td style="text-align: right;border:1px solid #000;font-weight:800;padding:5px">'.number_format_invoice_empty($total_credit_value).'</td>
		</tr>';
		$pdf = PDF::loadHTML($output);
		$pdf->save('public/papers/Journal Viewer for Journal reference ID '.$journal_id.'.pdf');
		echo 'Journal Viewer for Journal reference ID '.$journal_id.'.pdf';
	}
	public function get_client_review_for_year(Request $request)
	{
		$year = $request->get('year');
		$prev_year = $year - 1;
		$next_year = $year + 1;
		$client_id = $request->get('client_id');
		$output ='';
		$client =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		for($ival = 0; $ival <= 13; $ival++)
		{
			$month_year_val = '';
			if($ival == 0) { $month = '12-'.$prev_year; $month_year_val = 'Dec - '.$prev_year; }
			elseif($ival == 13) { $month = '01-'.$next_year; $month_year_val = 'Jan - '.$next_year; }
			else{ 
				if($ival == 1) { $month_year_val = 'Jan - '.$year; }
				elseif($ival == 2) { $month_year_val = 'Feb - '.$year; }
				elseif($ival == 3) { $month_year_val = 'Mar - '.$year; }
				elseif($ival == 4) { $month_year_val = 'Apr - '.$year; }
				elseif($ival == 5) { $month_year_val = 'May - '.$year; }
				elseif($ival == 6) { $month_year_val = 'Jun - '.$year; }
				elseif($ival == 7) { $month_year_val = 'Jul - '.$year; }
				elseif($ival == 8) { $month_year_val = 'Aug - '.$year; }
				elseif($ival == 9) { $month_year_val = 'Sep - '.$year; }
				elseif($ival == 10) { $month_year_val = 'Oct - '.$year; }
				elseif($ival == 11) { $month_year_val = 'Nov - '.$year; }
				elseif($ival == 12) { $month_year_val = 'Dec - '.$year; }
				if($ival < 10) { $ivali = '0'.$ival; }
				else { $ivali = $ival; }
				$month = $ivali.'-'.$year;
			}
			$get_date = '01';
			$month_year = explode("-",$month);
			$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;
			$currr_month = date('m-Y',strtotime($get_full_date));
			$current_str = strtotime(date('Y-m-01'));
			$curr_str = strtotime($get_full_date);
			$curr_attachment_div = '';
			$curr_refresh_file = '';
			$curr_text_one = 'No Period';
			$curr_text_two = '';
			$curr_t1 = '<p>T1: <spam class="t1_spam_overlay"></spam></p>';
			$curr_t2 = '<p>T2: <spam class="t2_spam_overlay"></spam></p>';
			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
			$curr_text_three = '';
			$currr_text_three = '';
			$curr_color_status = '';
			$curr_color_text = '';
			$curr_check_box_color = 'black_td';
			$curr_checked = '';
			$class_tr = 'non_returning';
			$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
        	if(($get_latest_import_file_id))
        	{
        		$latest_import_id = $get_latest_import_file_id->import_id;
        	}
        	$check_reviews_curr = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
            if(($check_reviews_curr))
            {
            	$i= 0; $j=0;$k=0;
            	foreach($check_reviews_curr as $curr)
            	{
            		if($curr->type == 1){ 
            			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" download>'.$curr->filename.'</a> <a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>';
            			$curr_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file floatnone" data-element="'.URL::to($curr->url.'/'.$curr->filename).'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'"></a>';
            			$curr_t1 ='<p>T1: <spam class="t1_spam_overlay">'.$curr->t1.'</spam></p>';
						$curr_t2 ='<p>T2: <spam class="t2_spam_overlay">'.$curr->t2.'</spam></p>'; 
            		}
            		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }
            		if($curr->type == 3){ 
            			$curr_text_two = $curr->textval; 
            			$curr_color_status = 'green_import_overlay'; 
            			$curr_color_text = 'Submitted';
            			$class_tr = ''; 
            			$curr_check_box_color = 'submitted_td_overlay';
            			$i = $i + 1; 
            			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
            		}
            		if($curr->type == 4){ 
            			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$curr->textval)->first();
            			if(($get_attachment_download))
            			{
            				$currr_text_three = $curr->textval;
            				$curr_text_three = '<a class="import_file_attachment_id_overlay" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="mergin-left:5px" download>'.$curr->textval.'</a>'; 
            			}
            			$j = $j + 1;
            		}
            		if($curr->type == 6)
            		{
            			$curr_checked = 'checked';
            			$k = $k + 1;
            		}
            	}
            	if($j > 0 && $i == 0)
            	{
            		if($latest_import_id == $currr_text_three)
            		{
            			if($current_str > $curr_str)
            			{
            				$curr_color_status = 'red_import_overlay'; 
            				$curr_color_text = 'Submission O/S';
            				$curr_check_box_color = 'os_td_overlay';
            			}
            			else if($current_str == $curr_str)
            			{
            				$curr_color_status = 'orange_import_overlay'; 
            				$curr_color_text = 'Submission Due';
            				$class_tr = ''; 
            				$curr_check_box_color = 'due_td_overlay';
            			}
            			else if($current_str < $curr_str)
            			{
            				$curr_color_status = 'white_import_overlay'; 
            				$curr_color_text = 'Not Due';
            				$curr_check_box_color = 'not_due_td_overlay';
            			}
            		}
            		else{
            			$curr_color_status = 'blue_import_overlay'; 
            			$curr_color_text = 'Potentially Submitted';
            			$curr_check_box_color = 'ps_td_overlay';
            		}
            	}
            }
            if($client->status == 1) { $fontcolor = 'red'; }
            elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
            elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
            elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
            else{$fontcolor = '#fff';}
            if($curr_attachment_div == "")
            {
            	$attach_disabled = 'disabled';
            }
            else{
            	$attach_disabled = '';
            }
			$output.='<tr class="shownn_tr shown_tr shown_tr_'.$client->client_id.'_'.$currr_month.' '.$class_tr.'">
				<td><input type="checkbox" name="month_download_checkbox" class="month_download_checkbox" id="cli_review_'.$client->client_id.'_'.$currr_month.'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'" '.$attach_disabled.'><label for="cli_review_'.$client->client_id.'_'.$currr_month.'">'.$month_year_val.'</label><input type="hidden" name="month_year_sort_val" class="month_year_sort_val" value="'.strtotime($get_full_date).'"></td>
				<td><label class="import_icon_overlay '.$curr_color_status.'">'.$curr_color_text.'</label></td>
				<td class="period_sort_val">'.$curr_text_one.'</td>
				<td>'.$curr_remove_two.'<input type="text" class="form-control submitted_import_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'">
					<input type="hidden" name="date_sort_val" class="date_sort_val" value="'.$curr_text_two.'">
				</td>
				<td><a href="javascript:" class="fa fa-plus add_attachment_month_year_overlay" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> '.$curr_refresh_file.'<div class="attachment_div_overlay">'.$curr_attachment_div.' '.$curr_t1.' '.$curr_t2.'</div></td>
				<td><input type="checkbox" class="check_records_received_overlay" id="check_records_received_overlay" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label_overlay '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label>
					<input type="hidden" name="record_sort_val" class="record_sort_val" value="'.$curr_checked.'">
				</td>
			</tr>';
		}
		echo $output;
	}
	public function download_selected_periods_vat_attachments(Request $request)
	{
		$client_id = $request->get('client_id');
		$months = explode(",",$request->get('months'));
		$client_details =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$files = \App\Models\vatReviews::whereIn('month_year',$months)->where('client_id',$client_id)->where('type',1)->get();
		if(($files))
		{
			$company_name = str_replace("/", "", $client_details->name);
			$company_name = str_replace("/", "", $company_name);
			$company_name = str_replace("/", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$public_dir=public_path();
			$zipFileName = 'Client Review Attachments for '.$company_name.'_'.time().'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->filename,$file->filename);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function payroll_settings(Request $request)
	{
		return view('user/emailsettings', array('title' => 'Payroll Settings'));
	}
	public function save_payroll_settings(Request $request)
	{
		$signature = $request->get('message_editor');
		$cc = $request->get('payroll_cc_input');
		$data['payroll_signature'] = $signature;
		$data['payroll_cc_email'] = $cc;
		\App\Models\PmsAdmin::where('practice_code',Session::get('user_practice_code'))->update($data);
		return Redirect::back()->with('message',"Payroll Settings Saved successfully.");
	}
	public function update_user_notification(Request $request)
	{
		$notification=$request->get('user_notification');
		\App\Models\PmsAdmin::where('practice_code',Session::get('user_practice_code'))->update(['notify_message'=>$notification]);
		return Redirect::back()->with('message', 'Settings Updated Successfully');
	}
	public function update_distribute_link(Request $request)
	{
		$distribute_link=$request->get('distribute_link');
		\App\Models\PmsAdmin::where('practice_code',Session::get('user_practice_code'))->update(['distribute_link'=>$distribute_link]);
		return Redirect::back()->with('message', 'Settings Updated Successfully');
	}
	public function update_practice_setting(Request $request)
	{
		$data['practice_code'] = ($request->get('practice_code') != "")?$request->get('practice_code'):'GBS';
		$data['practice_name'] = ($request->get('practice_name') != "")?$request->get('practice_name'):'';
		$data['address1'] = ($request->get('address_1') != "")?$request->get('address_1'):'';
		$data['address2'] = ($request->get('address_2') != "")?$request->get('address_2'):'';
		$data['address3'] = ($request->get('address_3') != "")?$request->get('address_3'):'';
		$data['address4'] = ($request->get('address_4') != "")?$request->get('address_4'):'';
		$data['link1'] = ($request->get('link_1') != "")?$request->get('link_1'):'';
		$data['link2'] = ($request->get('link_2') != "")?$request->get('link_2'):'';
		$data['link3'] = ($request->get('link_3') != "")?$request->get('link_3'):'';
		$data['phoneno'] = ($request->get('phone_no') != "")?$request->get('phone_no'):'';

		$check_statement = DB::table('practices')->where('practice_code', $data['practice_code'])->first();
		
		if($check_statement)
		{
			DB::table('practices')->where('id',$check_statement->id)->update($data);
		}
		else{
			DB::table('practices')->insert($data);
		}
		return Redirect::back()->with('message','Settings Saved Successfully');
	}
	public function reset_vat_reviews_folder(Request $request) {
		$dir = 'uploads/vat_reviews';
		$folders = scandir($dir, 0);
		foreach($folders as $folder){
			if($folder != "." && $folder != ".." && $folder != "ros_vat_due"){
				$subfoldersdir = $dir.'/'.$folder;
				$subfolders = scandir($subfoldersdir, 0);
				foreach($subfolders as $subfolder){
					if($subfolder != "." && $subfolder != ".."){
						$filesdir = $subfoldersdir.'/'.$subfolder;
						$files = scandir($filesdir, 0);
						foreach($files as $file){
							if($file != "." && $file != ".."){
								if(!is_dir($filesdir.'/'.$file))
								{
									$fileexp = explode("_",$file);
									$get_new_folder =  $fileexp[0];
									if(!is_dir($filesdir.'/'.$get_new_folder)){
										mkdir($filesdir.'/'.$get_new_folder);
									}
									rename($filesdir.'/'.$file, $filesdir.'/'.$get_new_folder.'/'.$fileexp[1]);
									$check_db = \App\Models\vatReviews::where('filename',$file)->first();
									if(($check_db)){
										$dbval['filename'] = $fileexp[1];
										$dbval['url'] = $filesdir.'/'.$get_new_folder;
									\App\Models\vatReviews::where('id',$check_db->id)->update($dbval);
									}
								}
							}
						}
					}
				}
			}
		}
	}
	public function add_to_employer_list(Request $request){
		$emp_no = $request->get('emp_no');
		$emp_name = $request->get('emp_name');
		$data['emp_no'] = $emp_no;
		$data['emp_name'] = $emp_name;
		$data["practice_code"] = Session::get('user_practice_code');
        $check_details = \App\Models\Employers::where('practice_code',Session::get('user_practice_code'))->where('emp_no',$emp_no)->first();
        if(($check_details)){
            echo '';
        }
        else{
            $insertedid = \App\Models\Employers::insertDetails($data);
    		$output = '<tr>
    			<td>'.$emp_no.'</td>
    			<td>'.$emp_name.'</td>
    			<td class="emp_users_count emp_users_count_'.$insertedid.'">0</td>
    			<td>
    	            <a href="javascript:" class="fa fa-user manage_employer_users" data-element="'.$insertedid.'" title="Manage Users" style="font-size: 20px;"></a>
    	        </td>
    		</tr>';
    		echo $output;
        }
	}
	public function manage_employer_users(Request $request){
		$emp_id = $request->get('emp_id');
		$details = \App\Models\Employers::where('practice_code',Session::get('user_practice_code'))->where('id',$emp_id)->first();
		$data['emp_no'] =  '';
		$data['emp_name'] = '';
		if(($details)){
			$data['emp_no'] =  $details->emp_no;
			$data['emp_name'] = $details->emp_name;
		}
		$html = '';
		$users = \App\Models\EmployerUsers::where('practice_code',Session::get('user_practice_code'))->where('emp_id',$emp_id)->get();
		if(($users)){
			foreach($users as $user){
				if($user->status == 0) { 
					$status = 'Approved'; 
					$btn = '<a href="javascript:" class="fa fa-check" style="color:green" data-element="1" data-id="'.$user->id.'" title="Approved"></a>';
				}
				else { 
					$status = 'Awaiting Approval'; 
					$btn = '<a href="javascript:" class="fa fa-minus change_user_status" style="color:red" data-element="0" data-id="'.$user->id.'" title="Awaiting Approval"></a>';
				}
				$html.='<tr>
					<td>'.$user->emp_no.'</td>
					<td>'.$user->email.'</td>
					<td>'.Crypt::decrypt($user->password).'</td>
					<td class="user_status_td">'.$status.'</td>
					<td>
						'.$btn.'
						<a href="javascript:" class="fa fa-trash delete_emp_users" data-element="'.$user->id.'" title="Delete User"></a>
					</td>
				</tr>';
			}
		}
		else{
			$html.='<tr>
				<td colspan="5" style="text-align:center">No Records Found</td>
			</tr>';
		}
		$data['html'] = $html;
		echo json_encode($data);
	}
	public function insert_employer_user(Request $request){
		$emp_id = $request->get('emp_id');
		$email = $request->get('email');
		$password = $request->get('password');
		$check_email = \App\Models\EmployerUsers::where('practice_code',Session::get('user_practice_code'))->where('email',$email)->first();
		if(($check_email))
		{
			$data['error'] = "1";
			$data['html'] = '';
		}
		else{
			$details = \App\Models\Employers::where('practice_code',Session::get('user_practice_code'))->where('id',$emp_id)->first();
			if(($details))
			{
				$dataval['emp_id'] = $emp_id;
				$dataval['emp_no'] = $details->emp_no;
				$dataval['email'] = $email;
				$dataval['password'] = Crypt::encrypt($password);
				$dataval['status'] = 0;
				$dataval["practice_code"] = Session::get('user_practice_code');
				$insertedid = \App\Models\EmployerUsers::insertDetails($dataval);
				$data['error'] = "0";
				$data['insertedid'] = $insertedid;
				$html = '<tr>
					<td>'.$details->emp_no.'</td>	
					<td>'.$email.'</td>	
					<td>'.$password.'</td>	
					<td class="user_status_td">Approved</td>	
					<td>
						<a href="javascript:" class="fa fa-check" style="color:green" data-element="1" data-id="'.$insertedid.'" title="Approved"></a>
						<a href="javascript:" class="fa fa-trash delete_emp_users" data-element="'.$insertedid.'" title="Delete User"></a>
					</td>
				</tr>';
				$data['html']= $html;
				$users_count = \App\Models\EmployerUsers::where('practice_code',Session::get('user_practice_code'))->where('emp_id',$emp_id)->get();
				$data['user_count'] = count($users_count);
			}
		}
		echo json_encode($data);
	}
	public function change_employer_user_status(Request $request)
	{
		$id = $request->get('id');
		$status = $request->get('status');
		$data['status'] = $status;
		\App\Models\EmployerUsers::where('id',$id)->update($data);
	}
	public function delete_employer_users(Request $request){
		$emp_id = $request->get('emp_id');
		\App\Models\EmployerUsers::where('id',$emp_id)->delete();
	}
	public function user_logging_password_after_login(Request $request){
		$user_id = $request->get('hidden_user_id');
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$user_id)->first();
		if(($user)){
			$data['password'] = Crypt::encrypt($request->get('password_logging'));
			$data['logins'] = $user->logins + 1;
			if($_FILES['profile_img']['name'] != ""){
				$name = $_FILES['profile_img']['name'];
				$tmp_name = $_FILES['profile_img']['tmp_name'];
				$time = time();
				$upload_dir = 'uploads/user_img';
				if(!is_dir($upload_dir)){
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.$time;
				if(!is_dir($upload_dir)){
					mkdir($upload_dir);
				}
				$data['url'] = $upload_dir;
				$data['filename'] = $name;
				move_uploaded_file($tmp_name, $upload_dir.'/'.$name);
			}
			User::where('user_id',$user_id)->update($data);
			$sessn=array('userid' => $user_id);
			Session::put($sessn);
			return redirect('/user/dashboard')->with('message','Password Updated Successfuly');
		}
	}
	public function audit_trail(Request $request){
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$audit_trails = \App\Models\AuditTrails::where('user_id',Session::get('userid'))->orderBy('id','desc')->offset(0)->limit(100)->get();
		return view('user/audit_trails', array('title' => 'Bubble - Dashboard', 'userlist' => $user, 'audit_trails' => $audit_trails));
	}
	public function filter_by_user(Request $request){
		$filter_by = $request->get('filter_by');
		$filter_by_user = $request->get('filter_by_user');
		$audit_trails = \App\Models\AuditTrails::where('user_id',$filter_by_user)->orderBy('id','desc')->offset(0)->limit(100)->get();
		$output = '';
		if(($audit_trails)){
	      foreach($audit_trails as $trails){
	        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
	        if($trails->module == "Login"){
	        	$ref = $user_details->lastname.' '.$user_details->firstname;
	        }
	        elseif($trails->module == "Task Manager"){
	        	$ref = 'Task ID - A'.$trails->reference;
	        }
	        else{
              $ref = $trails->reference;
            }
	        $output.='<tr>
	          <td>'.date('d-M-Y H:i', strtotime($trails->updatetime)).'</td>
	          <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
	          <td>'.$trails->module.'</td>
	          <td>'.$trails->event.'</td>
	          <td>'.$ref.'</td>
	        </tr>';
	      }
	    }
	    else{
	      $output.='<tr><td style="text-align:center">No Audit Trails Found</td><td></td><td></td><td></td><td></td></tr>';
	    }
	    $audit_trails_counts = \App\Models\AuditTrails::where('user_id',$filter_by_user)->get();
	    echo json_encode(array("output" => $output, "count" => ($audit_trails_counts)));
	}
	public function filter_by_module(Request $request){
		$filter_by = $request->get('filter_by');
		$filter_by_module = $request->get('filter_by_module');
		if($filter_by_module == "1"){
			$audit_trails = \App\Models\AuditTrails::where('module','Login')->orderBy('id','desc')->offset(0)->limit(100)->get();
		}
		elseif($filter_by_module == "2"){
			$audit_trails = \App\Models\AuditTrails::where('module','Task Manager')->orderBy('id','desc')->offset(0)->limit(100)->get();
		}
		elseif($filter_by_module == "3"){
			$audit_trails = \App\Models\AuditTrails::where('module','2Bill Manager')->orderBy('id','desc')->offset(0)->limit(100)->get();
		}
		elseif($filter_by_module == "4"){
			$audit_trails = \App\Models\AuditTrails::where('module','CRO ARD')->orderBy('id','desc')->offset(0)->limit(100)->get();
		}
		$output = '';
		if(($audit_trails)){
	      foreach($audit_trails as $trails){
	        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
	        if($trails->module == "Login"){
	        	$ref = $user_details->lastname.' '.$user_details->firstname;
	        }
	        elseif($trails->module == "Task Manager"){
	        	$ref = 'Task ID - A'.$trails->reference;
	        }
	        else{
              $ref = $trails->reference;
            }
	        $output.='<tr>
	          <td>'.date('d-M-Y H:i', strtotime($trails->updatetime)).'</td>
	          <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
	          <td>'.$trails->module.'</td>
	          <td>'.$trails->event.'</td>
	          <td>'.$ref.'</td>
	        </tr>';
	      }
	    }
	    else{
	      $output.='<tr><td style="text-align:center">No Audit Trails Found</td><td></td><td></td><td></td><td></td></tr>';
	    }
	    if($filter_by_module == "1"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','login')->get();
	    }
	    elseif($filter_by_module == "2"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','Task Manager')->get();
	    }
	    elseif($filter_by_module == "3"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','2Bill Manager')->get();
	    }
	    elseif($filter_by_module == "4"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','CRO ARD')->get();
	    }
	    echo json_encode(array("output" => $output, "count" => ($audit_trails_counts)));
	}
	public function show_more_user_audit(Request $request)
	{
		$page_no = $request->get('page_no');
		$offset = $page_no * 100;
		$filter_by_user = $request->get('filter_by_user');
		$audit_trails = \App\Models\AuditTrails::where('user_id',$filter_by_user)->orderBy('id','desc')->offset($offset)->limit(100)->get();
		$output = '';
		if(($audit_trails)){
	      foreach($audit_trails as $trails){
	        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
	        if($trails->module == "Login"){
	        	$ref = $user_details->lastname.' '.$user_details->firstname;
	        }
	        elseif($trails->module == "Task Manager"){
	        	$ref = 'Task ID - A'.$trails->reference;
	        }
	        else{
              $ref = $trails->reference;
            }
	        $output.='<tr>
	          <td>'.date('d-M-Y H:i', strtotime($trails->updatetime)).'</td>
	          <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
	          <td>'.$trails->module.'</td>
	          <td>'.$trails->event.'</td>
	          <td>'.$ref.'</td>
	        </tr>';
	      }
	    }
	    else{
	      $output.='<tr><td style="text-align:center">No Audit Trails Found</td><td></td><td></td><td></td><td></td></tr>';
	    }
	    $audit_trails_counts = \App\Models\AuditTrails::where('user_id',$filter_by_user)->get();
	    echo json_encode(array("output" => $output, "count" => ($audit_trails_counts)));
	}
	public function show_more_module_audit(Request $request)
	{
		$page_no = $request->get('page_no');
		$offset = $page_no * 100;
		$filter_by_module = $request->get('filter_by_module');
		if($filter_by_module == "1"){
			$audit_trails = \App\Models\AuditTrails::where('module','Login')->orderBy('id','desc')->offset($offset)->limit(100)->get();
		}
		elseif($filter_by_module == "2"){
			$audit_trails = \App\Models\AuditTrails::where('module','Task Manager')->orderBy('id','desc')->offset($offset)->limit(100)->get();
		}
		elseif($filter_by_module == "3"){
			$audit_trails = \App\Models\AuditTrails::where('module','2Bill Manager')->orderBy('id','desc')->offset($offset)->limit(100)->get();
		}
		elseif($filter_by_module == "4"){
			$audit_trails = \App\Models\AuditTrails::where('module','CRO ARD')->orderBy('id','desc')->offset($offset)->limit(100)->get();
		}
		$output = '';
		if(($audit_trails)){
	      foreach($audit_trails as $trails){
	        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
	        if($trails->module == "Login"){
	        	$ref = $user_details->lastname.' '.$user_details->firstname;
	        }
	        elseif($trails->module == "Task Manager"){
	        	$ref = 'Task ID - A'.$trails->reference;
	        }
	        else{
              $ref = $trails->reference;
            }
	        $output.='<tr>
	          <td>'.date('d-M-Y H:i', strtotime($trails->updatetime)).'</td>
	          <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
	          <td>'.$trails->module.'</td>
	          <td>'.$trails->event.'</td>
	          <td>'.$ref.'</td>
	        </tr>';
	      }
	    }
	    else{
	      $output.='<tr><td style="text-align:center">No Audit Trails Found</td><td></td><td></td><td></td><td></td></tr>';
	    }
	    if($filter_by_module == "1"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','login')->get();
	    }
	    elseif($filter_by_module == "2"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','Task Manager')->get();
	    }
	    elseif($filter_by_module == "3"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','2Bill Manager')->get();
	    }
	    elseif($filter_by_module == "4"){
	    	$audit_trails_counts = \App\Models\AuditTrails::where('module','CRO ARD')->get();
	    }
	    echo json_encode(array("output" => $output, "count" => ($audit_trails_counts)));
	}
	public function update_bi_payroll_status(Request $request){
		$taskid = $request->get('taskid');
		$data['bi_payroll'] = $request->get('status');
		$attachments = \App\Models\taskAttached::where('task_id',$taskid)->where('network_attach',0)->get();
		$task_details = \App\Models\task::where('task_id',$taskid)->first();
		$data['bi_payroll_next_status'] = 0;
		$bi_payroll_next_status = 0;
		if($data['bi_payroll'] == 1)
		{
			if(($attachments))
			{
				$data['bi_payroll_next_status'] = 1;
				$bi_payroll_next_status = 1; 
			}
		}
		\App\Models\task::where('task_id',$taskid)->update($data);
		echo $bi_payroll_next_status;
	}	
	public function refresh_vat_approval_count(Request $request)
	{
		$month_year = $request->get('month_year');
		$prev_full_approved = \App\Models\vatReviews::where('month_year',$month_year)->where('approve_status',1)->pluck('client_id')->toArray();
		$prev_submitted = \App\Models\vatReviews::where('month_year',$month_year)->where('type',3)->pluck('client_id')->toArray();
		$prev_not_submitted_approved=array_diff($prev_full_approved,$prev_submitted);
		echo count($prev_full_approved).' / '.count($prev_not_submitted_approved);
	}
	public function show_vat_submission_approval_for_month(Request $request)
	{
		$get_date = '01';
		$month_year = $request->get('month_year');
		$exp_month_year = explode('-',$month_year);
		$get_full_date = $exp_month_year[1].'-'.$exp_month_year[0].'-'.$get_date;
		$current_str = strtotime(date('Y-m-01'));
		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');
		if($exp_month_year[0] == "01") { $month_name = 'January'; }
		elseif($exp_month_year[0] == "02") { $month_name = 'February'; }
		elseif($exp_month_year[0] == "03") { $month_name = 'March'; }
		elseif($exp_month_year[0] == "04") { $month_name = 'April'; }
		elseif($exp_month_year[0] == "05") { $month_name = 'May'; }
		elseif($exp_month_year[0] == "06") { $month_name = 'June'; }
		elseif($exp_month_year[0] == "07") { $month_name = 'July'; }
		elseif($exp_month_year[0] == "08") { $month_name = 'August'; }
		elseif($exp_month_year[0] == "09") { $month_name = 'September'; }
		elseif($exp_month_year[0] == "10") { $month_name = 'October'; }
		elseif($exp_month_year[0] == "11") { $month_name = 'November'; }
		elseif($exp_month_year[0] == "12") { $month_name = 'December'; }
		$set_month_year_name = $month_name.' '.$exp_month_year[1];
		$clients =\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		$prev_no_sub_due = 0;
		$prev_no_sub_os = 0;
		$prev_no_sub = 0;
		$output = '<table class="table own_table_white">
        	<thead>
          		<th style="background:#000;color:#fff">Client Code <i class="fa fa-sort code_approval_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff">Active Client</th>
          		<th style="background:#000;color:#fff">Client Name <i class="fa fa-sort client_approval_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff">Status <i class="fa fa-sort status_approval_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff">Submission <i class="fa fa-sort submission_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff">Approval Values <i class="fa fa-sort approval_values_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff">Approve Status <i class="fa fa-sort approval_sort" style="float:right" aria-hidden="true"></i></th>
          		<th style="background:#000;color:#fff"></th>
          		<th style="background:#000;color:#fff">Comment <i class="fa fa-sort comments_sort" style="float:right" aria-hidden="true"></i></th>
          	<thead>
          	<tbody id="approval_tbody">';
				if(($clients))
				{
					foreach($clients as $client)
					{
						$deactivated_client = '';
						if($client->cm_client_id != "")
						{
							$cm_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->cm_client_id)->first();
							if(($cm_clients))
							{
								if($cm_clients->active == "2")
								{
									$deactivated_client= 'deactivated_tr';
								}
							}
						}
						if($client->status == 1) { $fontcolor = 'red'; }
		                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
		                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
		                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
		                else{$fontcolor = '#fff';}
		                $prev_month = $month_year;
		                $prev_attachment_div = '';
		                $prev_refresh_file = '';
						$prev_text_one = 'No Period';
						$prev_text_two = '';
						$prev_t1 = '';
						$prev_t2 = '';
						$prev_t1_value = '';
						$prev_t2_value = '';
						$prev_comments = '';
						$prev_approve_status = '';
						$prev_submitted_tr = 'vat_submitted_clients_tr';
						$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right;display:none"></a>';
						$prev_text_three = '';
						$prevv_text_three = '';
						$prev_color_status = '';
						$prev_color_text = '';
						$prev_check_box_color = 'blacK_td';
						$prev_checked = '';
						$latest_import_id = '';
						$get_latest_import_file_id =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('status',1)->orderBy('id','desc')->first();
		            	if(($get_latest_import_file_id))
		            	{
		            		$latest_import_id = $get_latest_import_file_id->import_id;
		            	}
		                $check_reviews_prev = \App\Models\vatReviews::where('client_id',$client->client_id)->where('month_year',$prev_month)->get();
		                if(($check_reviews_prev))
		                {
		                	$i= 0; $j=0;
		                	foreach($check_reviews_prev as $prev)
		                	{
		                		if($prev->type == 1){ 
		                			$ext = explode('.',$prev->filename);
		                			if(end($ext) == "pdf") {
		                				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
		                			}
		                			if(end($ext) == "doc" || end($ext) == "docx") {
		                				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
		                			}
		                			if(end($ext) == "xls" || end($ext) == "xlsx") {
		                				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
		                			}
		                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
		                				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
		                			}
		                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
		                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prev_month.'"></a></p>'; 
		                			$prev_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($prev->url.'/'.$prev->filename).'" data-client="'.$client->client_id.'" data-month="'.$prev_month.'" style="float:none;margin-left:15px"></a>';
		                			$prev_t1 = $prev->t1;
		                			$prev_t2 = $prev->t2;
		                		}
		                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }
		                		if($prev->type == 3){ 
		                			$prev_text_two = $prev->textval; 
		                			$prev_color_status = 'green_import'; 
		                			$prev_color_text = 'Submitted'; 
		                			$prev_check_box_color = 'submitted_td';
		                			$prev_no_sub = $prev_no_sub + 1;
		                			$i = $i + 1; 
		                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right"></a>';
		                		}
		                		if($prev->type == 4){ 
		                			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$prev->textval)->first();
		                			if(($get_attachment_download))
		                			{
		                				$prevv_text_three = $prev->textval;
		                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$prev->textval.'</a>'; 
		                			}
		                			$j = $j + 1;
		                		}
		                		if($prev->type == 6){
		                			$prev_checked = 'checked';
		                		} 
		                		if($prev->type == 7){
		                			$prev_t1_value = $prev->t1_value;
		                			$prev_t2_value = $prev->t2_value;
		                		}
		                		if($prev->type == 8){
		                			$prev_approve_status = $prev->approve_status;
		                		}
		                		if($prev->type == 9){
		                			$prev_comments = $prev->comments;
		                		}
		                	}
		                	if($j > 0 && $i == 0)
		                	{
		                		if($latest_import_id == $prevv_text_three)
		                		{
		                			if($current_str > $curr_str)
			            			{
			            				$prev_color_status = 'red_import'; 
			            				$prev_color_text = 'Submission O/S';
			            				$prev_check_box_color = 'os_td';
			            				$prev_submitted_tr = '';
			            			}
			            			else if($current_str == $curr_str)
			            			{
			            				$prev_color_status = 'orange_import'; 
			            				$prev_color_text = 'Submission Due';
			            				$prev_check_box_color = 'due_td';
			            				$prev_submitted_tr = '';
			            			}
			            			else if($current_str < $curr_str)
			            			{
			            				$curr_color_status = 'white_import'; 
			            				$curr_color_text = 'Not Due';
			            				$curr_check_box_color = 'not_due_td';
			            			}
			            			$prev_no_sub_os = $prev_no_sub_os + 1;
		                		}
		                		else{
		                			$prev_color_status = 'blue_import'; 
		                			$prev_color_text = 'Potentially Submitted';
		                			$prev_check_box_color = 'ps_td';
		                		}
		                	}
		                }
		                if($prev_t1_value != "" || $prev_t2_value != "")
		                {
		                	$prev_t3_value = (int)$prev_t1_value - (int)$prev_t2_value;
		                }
		                else{
		                	$prev_t3_value = '';
		                }
		                $email_sent_count = DB::table('vat_review_email_send')->where('client_id',$client->client_id)->where('month_year', $prev_month)->count();
						$output.='<tr class="approval_tasks_tr tasks_tr_'.$client->client_id.' '.$deactivated_client.' '.$prev_submitted_tr.'">
							<td style="color:'.$fontcolor.';border-bottom:1px solid #ccc" class="code_approval_sort_val">'.$client->cm_client_id.'</td>
							<td style="color:'.$fontcolor.';border-bottom:1px solid #ccc;text-align:center">
								<img class="active_client_list_tm1" data-iden="'.$client->cm_client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
							</td>
							<td style="color:'.$fontcolor.';border-bottom:1px solid #ccc" class="client_approval_sort_val">'.$client->name.'</td>
							<td style="border-bottom:1px solid #ccc" class="add_files_vat_client_'.$prev_month.'">
								<p style="text-align:center"><label class="status_approval_sort_val import_icon '.$prev_color_status.'" style="width:auto">'.$prev_color_text.'</label></p>
							</td>
							<td style="border-bottom:1px solid #ccc" class="add_files_vat_client_'.$prev_month.'">
								<label>Submitted:</label>
								<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right" value="'.$prev_text_two.'">'.$prev_remove_two.'
								<p>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$prev_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a></p> 
								<p><div class="attachment_div">'.$prev_attachment_div.'</div></p>
								<div style="width:100%">T1: <spam class="t1_spam">'.$prev_t1.'</spam> '.$prev_refresh_file.'</div>
								<div style="width:100%">T2: <spam class="t2_spam">'.$prev_t2.'</spam></div>
							</td>
							<td style="border-bottom:1px solid #ccc" class="add_files_vat_client_'.$prev_month.'">
								<div class="approve_t1_div" style="float:none;text-align:left;">T1: <input type="text" class="approve_t1_textbox" id="approve_t1_textbox" value="'.$prev_t1_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"></div>
								<div class="approve_t2_div" style="float:none;text-align:left;margin-top:10px">T2: <input type="text" class="approve_t2_textbox" id="approve_t2_textbox" value="'.$prev_t2_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"></div>
								<div class="approve_t3_div" style="float:none;text-align:left;margin-top:10px">T3: <input type="text" class="approve_t3_textbox" id="approve_t3_textbox" value="'.$prev_t3_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client->client_id.'" style="width:45%;background:#dfdfdf" readonly></div>
							</td>
							<td style="border-bottom:1px solid #ccc" class="add_files_vat_client_'.$prev_month.'">';
								if($prev_approve_status == 0 || $prev_approve_status == '0' || $prev_approve_status == ''){
									$output.='<a href="javascript:" class="common_black_button approve_t_button" data-value="1" style="background:#f00;color:#fff;margin-top:8px;float:left;margin-left: 24px;" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"> Approve</a>';
								}
								else{
									$output.='<a href="javascript:" class="common_black_button approve_t_button" data-value="0" style="background:green;color:#fff;margin-top:8px;float:left;margin-left: 24px;" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"> Approved </a>';
								}
							$output.='</td>
							<td style="border-bottom:1px solid #ccc" class="add_files_vat_client_'.$prev_month.'">
							<div class="approve_t1_div" style="float:none;text-align:left;">
								<a href="javascript:" class="fa fa-file-text-o approval_summary_vat" data-month="'.$prev_month.'" data-client="'.$client->client_id.'" title="Client Approval Summary" style="margin-top:11px;font-size:19px;"></a>
								</div>';
								if($email_sent_count > 0) {
									$output.= '<div class="approve_t2_div" style="float:none;text-align:left;margin-top:10px"">
									<a href="javascript:" class="fa fa-envelope vat_review_email email_icon_'.$client->client_id.'_'.$prev_month.'" data-value="0" style="font-size:19px; color:green;" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"  title="VAT Review Notification"></a>
									</div>';
								} else {
									$output.= '<div class="approve_t2_div" style="float:none;text-align:left;margin-top:10px"">
									<a href="javascript:" class="fa fa-envelope vat_review_email email_icon_'.$client->client_id.'_'.$prev_month.'"  data-value="0" style="font-size:19px;" data-month="'.$prev_month.'" data-client="'.$client->client_id.'"  title="VAT Review Notification"></a>
									</div>';
								}
							

							$output.= '</td>
							<td style="border-bottom:1px solid #ccc" class="comment_'.$prev_month.'">
								<textarea name="comments_approval" class="form-control comments_approval comments_approval_'.$prev_month.'_'.$client->client_id.'" data-month="'.$prev_month.'" data-client="'.$client->client_id.'">'.$prev_comments.'</textarea>
							</td>
						</tr>';
					}
				}
			$output.='</tbody>
		</table>';
		echo json_encode(array("output" => $output,"month_year_name" => $set_month_year_name));
	}
	public function save_approval_comments_textbox(Request $request)
	{
		$client_id = $request->get('client');
		$month_year = $request->get('month_year');
		$textval = $request->get('textval');
		$type = $request->get('type');

		\App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->delete();
		
		$data['client_id'] = $client_id;
		$data['month_year'] = $month_year;
		$data['type'] = $type;
		$data['comments'] = $textval;
		\App\Models\vatReviews::insert($data);
	}
	public function approval_summary_content(Request $request)
	{
		$client_id = $request->get('client');
		$get_date = '01';
		$month_year = $request->get('month_year');
		$exp_month_year = explode('-',$month_year);
		$get_full_date = $exp_month_year[1].'-'.$exp_month_year[0].'-'.$get_date;
		$current_str = strtotime(date('Y-m-01'));
		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');
		$output = '';
		$prev_no_sub_due = 0;
		$prev_no_sub_os = 0;
		$prev_no_sub = 0;
        $prev_month = $month_year;
        $prev_attachment_div = '';
        $prev_refresh_file = '';
		$prev_text_one = 'No Period';
		$prev_text_two = '';
		$prev_t1 = '';
		$prev_t2 = '';
		$prev_t1_value = '';
		$prev_t2_value = '';
		$prev_comments = '';
		$prev_approve_status = '';
		$prev_submitted_tr = 'vat_submitted_clients_tr';
		$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client_id.'" data-element="'.$prev_month.'" style="float:right;display:none"></a>';
		$prev_text_three = '';
		$prevv_text_three = '';
		$prev_color_status = '';
		$prev_color_text = '';
		$prev_check_box_color = 'blacK_td';
		$prev_checked = '';
        $check_reviews_prev = \App\Models\vatReviews::where('client_id',$client_id)->where('month_year',$prev_month)->get();
        if(($check_reviews_prev))
        {
        	$i= 0; $j=0;
        	foreach($check_reviews_prev as $prev)
        	{
        		if($prev->type == 1){ 
        			$ext = explode('.',$prev->filename);
        			if(end($ext) == "pdf") {
        				$img = '<img src="'.URL::to('public/assets/images/pdf.png').'" style="width:100px">';
        			}
        			if(end($ext) == "doc" || end($ext) == "docx") {
        				$img = '<img src="'.URL::to('public/assets/images/file.png').'" style="width:100px">';
        			}
        			if(end($ext) == "xls" || end($ext) == "xlsx") {
        				$img = '<img src="'.URL::to('public/assets/images/excel.png').'" style="width:100px">';
        			}
        			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
        				$img = '<img src="'.URL::to('public/assets/images/image.png').'" style="width:100px">';
        			}
        			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
        			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client_id.'" data-element="'.$prev_month.'"></a></p>'; 
        			$prev_refresh_file = '<a href="javascript:" class="fa fa-refresh refresh_submitted_file" data-element="'.URL::to($prev->url.'/'.$prev->filename).'" data-client="'.$client_id.'" data-month="'.$prev_month.'" style="float:none;margin-left:15px"></a>';
        			$prev_t1 = $prev->t1;
        			$prev_t2 = $prev->t2;
        		}
        		if($prev->type == 3){ 
        			$prev_text_two = $prev->textval; 
        			$prev_color_status = 'green_import'; 
        			$prev_color_text = 'Submitted'; 
        			$prev_check_box_color = 'submitted_td';
        			$prev_no_sub = $prev_no_sub + 1;
        			$i = $i + 1; 
        			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client_id.'" data-element="'.$prev_month.'" style="float:right"></a>';
        		}
        		if($prev->type == 4){ 
        			$get_attachment_download =\App\Models\vatReviewsImportAttachment::where('practice_code',Session::get('user_practice_code'))->where('import_id',$prev->textval)->first();
        			if(($get_attachment_download))
        			{
        				$prevv_text_three = $prev->textval;
        				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="margin-left:5px" download>'.$prev->textval.'</a>'; 
        			}
        			$j = $j + 1;
        		}
        		if($prev->type == 6){
        			$prev_checked = 'checked';
        		} 
        		if($prev->type == 7){
        			$prev_t1_value = $prev->t1_value;
        			$prev_t2_value = $prev->t2_value;
        		}
        		if($prev->type == 8){
        			$prev_approve_status = $prev->approve_status;
        		}
        		if($prev->type == 9){
        			$prev_comments = $prev->comments;
        		}
        	}
        	$latest_import_id = '';
        	if($j > 0 && $i == 0)
        	{
        		if($latest_import_id == $prevv_text_three)
        		{
        			if($current_str > $curr_str)
        			{
        				$prev_color_status = 'red_import'; 
        				$prev_color_text = 'Submission O/S';
        				$prev_check_box_color = 'os_td';
        				$prev_submitted_tr = '';
        			}
        			else if($current_str == $curr_str)
        			{
        				$prev_color_status = 'orange_import'; 
        				$prev_color_text = 'Submission Due';
        				$prev_check_box_color = 'due_td';
        				$prev_submitted_tr = '';
        			}
        			else if($current_str < $curr_str)
        			{
        				$curr_color_status = 'white_import'; 
        				$curr_color_text = 'Not Due';
        				$curr_check_box_color = 'not_due_td';
        			}
        			$prev_no_sub_os = $prev_no_sub_os + 1;
        		}
        		else{
        			$prev_color_status = 'blue_import'; 
        			$prev_color_text = 'Potentially Submitted';
        			$prev_check_box_color = 'ps_td';
        		}
        	}
        }
        if($prev_t1_value != "" || $prev_t2_value != "")
        {
        	$prev_t3_value = (int)$prev_t1_value - (int)$prev_t2_value;
        }
        else{
        	$prev_t3_value = '';
        }
		$output = '<div class="row tasks_tr_'.$client_id.'">
			<div class="col-md-6 add_files_vat_client_'.$month_year.'" style="min-height:200px;border-right:1px solid #ccc">
				<h4 style="font-weight:600">Submission Details</h4>
				<label style="margin-top:15px">Submitted:</label>
				<input type="text" class="submitted_import" data-client="'.$client_id.'" data-element="'.$prev_month.'" style="float:right" value="'.$prev_text_two.'">'.$prev_remove_two.'
				<p>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$prev_month.'" data-client="'.$client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a></p> 
				<p><div class="attachment_div">'.$prev_attachment_div.'</div></p>
				<div style="width:100%">T1: <spam class="t1_spam">'.$prev_t1.'</spam> '.$prev_refresh_file.'</div>
				<div style="width:100%">T2: <spam class="t2_spam">'.$prev_t2.'</spam></div>
			</div>
			<div class="col-md-6 add_files_vat_client_'.$month_year.'" style="min-height:200px">
				<h4 style="font-weight:600">Approval Details</h4>
				<div class="approve_t1_div" style="float:none;text-align:left;margin-top:22px">T1: <input type="text" class="approve_t1_textbox" id="approve_t1_textbox" value="'.$prev_t1_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client_id.'"></div>
				<div class="approve_t2_div" style="float:none;text-align:left;margin-top:10px">T2: <input type="text" class="approve_t2_textbox" id="approve_t2_textbox" value="'.$prev_t2_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client_id.'"></div>
				<div class="approve_t3_div" style="float:none;text-align:left;margin-top:10px">T3: <input type="text" class="approve_t3_textbox" id="approve_t3_textbox" value="'.$prev_t3_value.'" oninput="keypressonlynumber(this)" data-month="'.$prev_month.'" data-client="'.$client_id.'" style="width:45%;background:#dfdfdf" readonly></div>';
				if($prev_approve_status == 0 || $prev_approve_status == '0' || $prev_approve_status == ''){
					$output.='<a href="javascript:" class="common_black_button approve_t_button" data-value="1" style="background:#f00;color:#fff;margin-top:8px;float:left;margin-left: 24px;" data-month="'.$prev_month.'" data-client="'.$client_id.'"> Approve</a>';
				}
				else{
					$output.='<a href="javascript:" class="common_black_button approve_t_button" data-value="0" style="background:green;color:#fff;margin-top:8px;float:left;margin-left: 24px;" data-month="'.$prev_month.'" data-client="'.$client_id.'"> Approved </a>';
				}
			$output.='</div>
			<div class="col-md-12 comment_'.$month_year.'" style="margin-top:-9px">
				<h4>Comments:</h4>
				<textarea name="comments_approval" class="form-control comments_approval comments_approval_'.$month_year.'_'.$client_id.'" data-month="'.$month_year.'" data-client="'.$client_id.'">'.$prev_comments.'</textarea>
			</div>
		</div>';
		echo $output;
	}
	public function remove_duplicate_journals(Request $request){
		$get_duplicates = DB::select('SELECT `reference`, (`reference`) FROM `journals` WHERE `journal_source` = "RCPT" OR `journal_source` = "PAY" GROUP BY `reference` HAVING (`reference`) > 2');
		if(($get_duplicates)) {
			foreach($get_duplicates as $duplicate){
				$ref = $duplicate->reference;
				$type =  substr($ref,0,3);
				if($type == "RCP"){
					$ref_id = str_replace('RCPT_','',$ref);
				}
				elseif($type == "PAY"){
					$ref_id = str_replace('PAY_','',$ref);
				}
				$get_ids = \App\Models\Journals::where('reference',$ref)->orderBy('id','asc')->orderBy('connecting_journal_reference','asc')->get();
				$journal_id_array = array();
				$first_id = 0;
				if($type == "RCP"){
					if(($get_ids)) {
						foreach($get_ids as $key => $id){
							if($key == 0){
								$dataupdate['journal_id'] = $id->connecting_journal_reference;
								\App\Models\receipts::where('id',$ref_id)->update($dataupdate);
								$first_id = $id->connecting_journal_reference;
							}
							if(in_array($id->connecting_journal_reference,$journal_id_array))
							{	
								\App\Models\Journals::where('id',$id->id)->delete();
							}
							else{
								array_push($journal_id_array,$id->connecting_journal_reference);
							}
							\App\Models\Journals::where('reference',$ref)->where('connecting_journal_reference','not like', "%".$first_id."%")->delete();
						}
					}
				}
				if($type == "PAY"){
					if(($get_ids)) {
						foreach($get_ids as $key => $id){
							if($key == 0){
								$dataupdate['journal_id'] = $id->connecting_journal_reference;
								\App\Models\payments::where('payments_id',$ref_id)->update($dataupdate);
								$first_id = $id->connecting_journal_reference;
							}
							if(in_array($id->connecting_journal_reference,$journal_id_array))
							{	
								\App\Models\Journals::where('id',$id->id)->delete();
							}
							else{
								array_push($journal_id_array,$id->connecting_journal_reference);
							}
							\App\Models\Journals::where('reference',$ref)->where('connecting_journal_reference','not like', "%".$first_id."%")->delete();
						}
					}
				}
			}
		}
	}
	public function update_user_signature(Request $request)
	{
		$data['email_signature']=$request->get('user_signature');
		$data['vat_cc_email'] = $request->get('vat_cc_email');

		DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->update($data);

		return Redirect::back()->with('message', 'Settings Updated Successfully');
	}
	public function update_vat_review_settings(Request $request) 
	{
		$data['subject'] = $request->get('vat_review_subject');
		$data['period_end'] = $request->get('vat_review_period_end');
		$data['note'] = $request->get('vat_review_notes');
		$data['breakdown'] = $request->get('vat_review_breakdown');
		$data['signature'] = $request->get('vat_review_signature');
		$data['include_client_name'] = $request->get('vat_review_client_name_subject');

		\App\Models\vatReviewSettings::where('practice_code',Session::get('user_practice_code'))->update($data);
		return Redirect::back()->with('message', 'VAT Review Settings Updated Successfully');
	}

	public function email_vat_notification_details(Request $request) {
		$client_id = $request->get('client_id');

		$month = $request->get('month');

		$exp_month = explode('-',$month);
		$selected_month_year = $exp_month[1].'-'.$exp_month[0].'-01';

		$prev_month = date('F-Y', strtotime('first day of previous month', strtotime($selected_month_year)));

		$subject_prev_month = date('t-F-Y', strtotime('first day of previous month', strtotime($selected_month_year)));

		$vatclient_details = DB::table('vat_clients')->where('client_id',$client_id)->first();

		$title = 'VAT Notification '.$prev_month;

		$client_details = DB::table('cm_clients')->where('client_id',$vatclient_details->cm_client_id)->first();
		$to = '';
		if(!empty($client_details))
		{
			$to = $client_details->email;
			if($client_details->email2 != ""){
				$to = $to.','.$client_details->email2;
			}
		}
		else{
			$to = $vatclient_details->pemail;
			if($vatclient_details->semail != ""){
				$to = $to.','.$vatclient_details->semail;
			}
		}
		$from = Session::get('userid');
		$vat_review_settings = DB::table('vat_review_settings')->where('practice_code',Session::get('user_practice_code'))->first();

		$subject = $vat_review_settings->subject;
		if($vat_review_settings->period_end == 1) {
			$subject = $subject.' for '.$subject_prev_month;
		}
		if($vat_review_settings->include_client_name == 1) 
		{
			$subject = $subject. ' ('.$client_details->company.'-'.$client_details->client_id.' )';
		}
		$notes = $vat_review_settings->note;
		$notes.='<table style="border:none;border-collapse:collapse">';
		$vat_review_details = DB::table('vat_reviews')->where('client_id',$client_id)->where('month_year', $month)->where('type',7)->first();

		if(!empty($vat_review_details)){
			if($vat_review_settings->breakdown == 1) {
				if((is_numeric($vat_review_details->t1_value) == 1) && (is_numeric($vat_review_details->t2_value) == 1)) {
					$t3_value = (int)$vat_review_details->t1_value - (int)$vat_review_details->t2_value;

					if((int)$vat_review_details->t1_value >= (int)$vat_review_details->t2_value) {
						$t3_description = '<td style="width:150px;border:none;">VAT Due: </td><td style="border:none;text-align:right">'.number_format_invoice_empty($t3_value).'</td>';
					}
					else{
						$t3_description = '<td style="width:150px;border:none;">VAT Refund Due: </td><td style="border:none;text-align:right">'.number_format_invoice_empty($t3_value).'</td>';
					}
					$notes = $notes.'
						<tr style="border:none;">
							<td style="border:none;width:150px">VAT on Sales:</td>
							<td style="border:none;text-align:right">'.number_format_invoice_empty($vat_review_details->t1_value).'</td>
						</tr>
						<tr style="border:none;">
							<td style="border:none;width:150px">VAT on Purchases:</td>
							<td style="border:none;text-align:right">'.number_format_invoice_empty($vat_review_details->t2_value).'</td>
						</tr>
						<tr style="border:none;">
							'.$t3_description.'
						</tr>';
				}
				else{
					$t1value = $vat_review_details->t1_value;
					$t2value = $vat_review_details->t2_value;
					if($t1value == "") {
						$t1value = '0';
					}
					if($t2value == "") {
						$t2value = '0';
					}

					$t3_value = (int)$t1value - (int)$t2value;

					if((int)$t1value >= (int)$t2value) {
						$t3_description = '<td style="width:150px;border:none;">VAT Due: </td><td style="border:none;text-align:right">'.number_format_invoice_empty($t3_value).'</td>';
					}
					else{
						$t3_description = '<td style="width:150px;border:none;">VAT Refund Due: </td><td style="border:none;text-align:right">'.number_format_invoice_empty($t3_value).'</td>';
					}

					$notes = $notes.'
						<tr style="border:none;">
							<td style="width:150px;border:none;">VAT on Sales:</td>
							<td style="border:none;text-align:right">'.number_format_invoice_empty($t1value).'</td>
						</tr>
						<tr style="border:none;">
							<td style="width:150px;border:none;">VAT on Purchases:</td>
							<td style="border:none;text-align:right">'.number_format_invoice_empty($t2value).'</td>
						</tr>
						<tr style="border:none;">
							'.$t3_description.'
						</tr>';
				}
			}
		}
		else{
			if($vat_review_settings->breakdown == 1) {
				$notes =  $notes.'
						<tr style="border:none;">
							<td style="width:150px;border:none;">VAT on Sales:</td>
							<td style="border:none;text-align:right">0.00</td>
						</tr>
						<tr style="border:none;">
							<td style="width:150px;border:none;">VAT on Purchases:</td>
							<td style="border:none;text-align:right">0.00</td>
						</tr>
						<tr style="border:none;">
							<td style="width:150px;border:none;">T3:</td>
							<td style="border:none;text-align:right">0.00</td>
						</tr>';
			}
		}

		$notes.='</table>';
		$user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$from)->first();

		$dataarray['title'] = $title;
		$dataarray['client_name'] = $vatclient_details->name;
		$dataarray['from'] = $user_details->email;
		$dataarray['to'] = $to;
		$dataarray['subject'] = $subject;
		$dataarray['notes'] = $notes.$vat_review_settings->signature;

		$email_list = DB::table('vat_review_email_send')->where('client_id',$client_id)->where('month_year', $month)->orderBy('id','desc')->get();
		$email_output = '';
		if(is_countable($email_list) && count($email_list) > 0) {
			foreach($email_list as $list){
				$user_detailss = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$list->from_user)->first();
				$email_output.='<tr>
					<td>'.$user_detailss->lastname.' '.$user_detailss->firstname.'</td>
					<td>'.date('d-M-Y @ H:i', strtotime($list->email_send)).'</td>
				</tr>';
			}
		}
		else{
			$email_output.='<tr>
					<td colspan="2">No Emails sent</td>
				</tr>';
		}
		$dataarray['email_list'] = $email_output;

		echo json_encode($dataarray);
	}
	public function send_email_vat_review_notification(Request $request) {
		$client_id = $request->get('client_id');
		$month_year = $request->get('month_year');

		$from_input = $request->get('from_user');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('email',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = $request->get('to_user').','.$request->get('cc_unsent');
		$sentmails = $request->get('to_user').', '.$request->get('cc_unsent');
		$subject = $request->get('subject_unsent'); 
		$message = $request->get('email_content');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		if(is_countable($explode) && count($explode) > 0)
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = getEmailLogo('vat');
				$data['message'] = $message;
    			$data['signature'] = '';
				$contentmessage = view('user/email_share_paper', $data);
				$email = new PHPMailer();
				$email->CharSet = 'UTF-8';
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->set('MIME-Version', '1.0');
					$email->set('Content-Type', 'text/html; charset=UTF-8');
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->Send();
			}
			$email_date = date('Y-m-d H:i:s');
			
			$emaildata['client_id'] = $client_id;
			$emaildata['month_year'] = $month_year;
			$emaildata['from_user'] = $details->user_id;
			$emaildata['email_send'] = $email_date;

			DB::table('vat_review_email_send')->insert($emaildata);

			$vatclient_details = DB::table('vat_clients')->where('client_id',$client_id)->first();
			$client_details = DB::table('cm_clients')->where('client_id',$vatclient_details->cm_client_id)->first();
			$time = time();
	        $datamessage['message_id'] = $time;
	        $datamessage['message_from'] = $details->user_id;
	        $datamessage['subject'] = $subject;
	        $datamessage['message'] = $contentmessage;
	        $datamessage['client_ids'] = $client_details->client_id;
	        $datamessage['primary_emails'] = $client_details->email;
	        $datamessage['secondary_emails'] = $client_details->email2;
	        $datamessage['date_sent'] = date('Y-m-d H:i:s');
	        $datamessage['date_saved'] = date('Y-m-d H:i:s');
	        $datamessage['source'] = "VAT Review Notification";
	        $datamessage['attachments'] = '';
	        $datamessage['status'] = 1;
	        $datamessage['practice_code'] = Session::get('user_practice_code');
	        \App\Models\Messageus::insert($datamessage);

			$email_list = DB::table('vat_review_email_send')->where('client_id',$client_id)->where('month_year', $month_year)->orderBy('id','desc')->get();
			$email_output = '';
			if(is_countable($email_list) && count($email_list) > 0) {
				foreach($email_list as $list){
					$user_detailss = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$list->from_user)->first();
					$email_output.='<tr>
						<td>'.$user_detailss->lastname.' '.$user_detailss->firstname.'</td>
						<td>'.date('d-M-Y @ H:i', strtotime($list->email_send)).'</td>
					</tr>';
				}
			}
			else{
				$email_output.='<tr>
						<td colspan="2">No Emails sent</td>
					</tr>';
			}
			echo $email_output;
		}
		else{
			echo "0";
		}

	}

	public function deactivevatclientsajax(Request $request){
		$id = $request->get('client_id');
		$mode = 0;
		if ($request->get('mode') == "active") {
			$mode = 1;
		}
		\App\Models\vatClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $id)->update(['status' => $mode ]);
		echo json_encode(array("status" => "1"));
	}
	public function change_avathar_user_profile(Request $request) {
		$original_url = $request->get('original_upload_dir');
        $original_filename = $request->get('original_filename');

        $cropped_url = $request->get('cropped_upload_dir');
        $cropped_filename = $request->get('cropped_filename');

        $data['url'] = $original_url;
        $data['filename'] = $original_filename;
        $data['cropped_url'] = $cropped_url;
        $data['cropped_filename'] = $cropped_filename;

        DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($data);
	}
	public function upload_user_avatar_images(Request $request) {
        $upload_dir = 'uploads/user_avatar';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir.'/'.time();
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $targetPath = $upload_dir."/".$_FILES['file']['name'];
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                $uploadedImagePath = URL::to($targetPath);
                echo json_encode(array('image_path' => $uploadedImagePath,'upload_dir' => $upload_dir, 'filename' => $_FILES['file']['name']));
            }
        }
    }
    public function show_cropped_image(Request $request) {
        $upload_dir = $request->get('upload_dir');
    
        $imgUrl = $request->get('img');
        $x = $request->get('x');
        $y = $request->get('y');
        $w = $request->get('w');
        $h = $request->get('h');
    
        $imageWidth = $request->get('elewidth');
        $imageHeight = $request->get('eleheight');
    
        // Download the image from the URL and store it temporarily
        $tempImagePath = tempnam(sys_get_temp_dir(), 'img_');
            file_put_contents($tempImagePath, Http::get($imgUrl)->body());
        
        $imgInfo = @getimagesize($tempImagePath);
        if ($imgInfo === false) {
            // Failed to get image info, handle the error
            unlink($tempImagePath); // Delete the temporary image file
            throw new \Exception("Failed to get image information: $imgUrl");
        }
    
        $format = strtolower(substr($imgInfo['mime'], strpos($imgInfo['mime'], '/') + 1));
    
        if ($format === 'jpeg' || $format === 'jpg') {
            $img_r = imagecreatefromjpeg($tempImagePath);
        } elseif ($format === 'png') {
            $img_r = imagecreatefrompng($tempImagePath);
        } elseif ($format === 'webp') {
            $img_r = imagecreatefromwebp($tempImagePath);
        } elseif ($format === 'bmp') {
            $img_r = imagecreatefrombmp($tempImagePath);
        } else {
            // Handle other formats or throw an error
            unlink($tempImagePath); // Delete the temporary image file
            throw new \Exception("Unsupported image format: $format");
        }        
        
        $img_r = imagescale($img_r , $imageWidth, $imageHeight); // width=500 and height = 400

        $getimagewidth = imagesx($img_r);
        $getimageheight = imagesy($img_r);;
        $dst_r = ImageCreateTrueColor( $w, $h );
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w,$h);
        $exp = explode('/', $imgUrl);
        $upload_dir = $upload_dir . '/thumbnails';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $name = time() . '_' . end($exp);
        $filename = $upload_dir . '/' . $name;
    
        header('Content-type: image/jpeg');
        imagejpeg($dst_r, $filename);
    	
    	// After processing the image, don't forget to delete the temporary file
        unlink($tempImagePath);

        echo json_encode(array('image_path' => URL::to($filename), 'upload_dir' => $upload_dir, 'filename' => $name));
    }
    public function save_cropped_image(Request $request) {
        $original_url = $request->get('original_upload_dir');
        $original_filename = $request->get('original_filename');

        $cropped_url = $request->get('cropped_upload_dir');
        $cropped_filename = $request->get('cropped_filename');

        $data['url'] = $original_url;
        $data['filename'] = $original_filename;
        $data['cropped_url'] = $cropped_url;
        $data['cropped_filename'] = $cropped_filename;

        $customData['user_id'] = Session::get('userid');
        $customData['url'] = $original_url;
        $customData['filename'] = $original_filename;
        $customData['crop_url'] = $cropped_url;
        $customData['crop_filename'] = $cropped_filename;

        DB::table('custom_user_avatar')->insert($customData);

        DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($data);

        $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
		$img = URL::to('public/assets/user_avatar/ciaranguilfoyle-09.png');
		if($user_details->cropped_url != "" && $user_details->cropped_filename != ""){
		  $img = URL::to($user_details->cropped_url.'/'.$user_details->cropped_filename);
		}
        $avatarlist = DB::table('user_avatar')->where('status',0)->get();
        $customavatarlist = DB::table('custom_user_avatar')->where('user_id',$user_details->user_id)->where('status',0)->get();
        $output = '';
        $custom = 1;
		if(is_countable($avatarlist) && count($avatarlist) > 0) {
			foreach($avatarlist as $avatar) {
				if($img == URL::to($avatar->crop_url.'/'.$avatar->crop_filename)) {
                    $selected_container = 'selected_container';
                    $custom = 0;
                }
                else{
                    $selected_container = '';
                }
			  	$output.='<div class="col-md-2 image_container '.$selected_container.'">
			      <img src="'.URL::to($avatar->crop_url).'/'.$avatar->crop_filename.'" class="edit_avatar" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
			      <div class="middle">
			      	<a href="javascript:" class="common_avatar apply_avatar text" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'">Apply</a>
			      </div>
			  	</div>';
			}
		}
		if($custom == 1) {
			if(is_countable($customavatarlist) && count($customavatarlist) > 0) { 
				foreach($customavatarlist as $avatar) { 
					if($img == URL::to($avatar->crop_url.'/'.$avatar->crop_filename)) {
						$selected_container = 'selected_container';
						$custom = 0;
					}
					else{
						$selected_container = '';
					}
					$output.='<div class="col-md-2 image_container '.$selected_container.'">
				      <img src="'.URL::to($avatar->crop_url).'/'.$avatar->crop_filename.'" class="edit_avatar" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
				      <div class="middle">';
                        if($img == URL::to($avatar->crop_url.'/'.$avatar->crop_filename)) {
                          $output.='<a href="javascript:" class="common_avatar current_apply_avatar current_text" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'">Current</a>';
                        }
                        else {
                          $output.='<a href="javascript:" class="common_avatar apply_avatar text" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'">Apply</a>';
                        }
				      $output.='</div>
				  	</div>';
				}
			}
		}
		echo json_encode(array("output" => $output, "cropped_img" => URL::to($cropped_url.'/'.$cropped_filename)));
    }
    public function edit_pms_header_image(Request $request) {
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

                    DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	public function edit_paye_mrs_header_image(Request $request) {
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
                    $dataval['paye_mrs_email_header_url'] = $upload_dir;
                    $dataval['paye_mrs_email_header_filename'] = $fname;

                    DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	public function edit_vat_header_image(Request $request) {
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

                    DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	public function edit_dashboard_header_image(Request $request) {
		$module = $request->get('email_header_module');
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

                    if($module == 'pms') {
                    	DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'croard') {
                    	DB::table('croard_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'infile') {
                    	DB::table('infile_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'keydocs') {
                    	DB::table('key_docs_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'yearend') {
                    	DB::table('yearend_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'messageus') {
                    	DB::table('messageus_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'aml') {
                    	DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'taskmanager') {
                    	DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'request') {
                    	DB::table('request_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'car') {
                    	DB::table('clientaccountreview_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'statement') {
                    	DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'vat') {
                    	DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'invoice') {
                    	DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                    }
                    elseif($module == 'payemrs') {
                    	$payedataval['paye_mrs_email_header_url'] = $upload_dir;
                    	$payedataval['paye_mrs_email_header_filename'] = $fname;
                    	DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->update($payedataval); 
                    }
                    
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	
}
