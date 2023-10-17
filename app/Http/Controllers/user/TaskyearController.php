<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use Illuminate\Http\Request;
class TaskyearController extends Controller {
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
		require_once app_path("Http/helpers.php");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function taskyear(Request $request)
	{
		$year = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->get();
		
		return view('user/taskyear', array('title' => 'User', 'tasklist' => $year));
	}
	public function addtaskyear(Request $request){
		$year = $request->get('year');
		$date = date('Y-m-d', strtotime($request->get('end_date')));
		$check_year = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('year_name',$year)->first();
		if($check_year)
		{
			 \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('year_id',$check_year->year_id)->update(['delete_status'=>0]);
			return redirect('user/manage_task')->with('message','Year Recovered Successfully');
		}
		else{
			$check_prev = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->orderBy('year_id','desc')->first();
			if($check_prev) {
				$count_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$check_prev->year_id)->count();
				$count_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$check_prev->year_id)->count();
				if($count_week < 52 && $count_month < 12)
				{
					return redirect('user/manage_task')->with('message','Year '.$year.' cannot be created unless all the 12 Months and 52 weeks of the previous year '.$check_prev->year_name.' have been created and closed.');
				}
				else{
					$now = date('Y-m-d H:i:s');
					 \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('week_closed','=','0000-00-00 00:00:00')->update(['week_closed' => $now]);
					\App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_closed','=','0000-00-00 00:00:00')->update(['month_closed' => $now]);
					$byear = $year - 1;
					$beforeyear = \App\Models\Year::where('practice_code',Session::get('user_practice_code'))->where('year_name',$byear)->first();
					if($beforeyear) {
						$getlastweek = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where('year',$beforeyear->year_id)->orderBy('week_id','desc')->first();
						$getlastmonth = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year',$beforeyear->year_id)->orderBy('month_id','desc')->first();
						$yid = \App\Models\Year::insertDetails(['year_name' => $year,'end_date' => $date,'practice_code' => Session::get('user_practice_code')]);

						$weekid = \App\Models\week::insertDetails(['year' => $yid,'week' => 1,'practice_code' => Session::get('user_practice_code')]);
						$gettasks = \App\Models\task::where('task_year',$getlastweek->year)->where('task_week',$getlastweek->week_id)->get();
						if(($gettasks))
						{
							foreach($gettasks as $tasks)
							{
								$data['task_year'] = $yid;
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
								$data['tasklevel'] = $tasks->tasklevel;
								$data['p30_pay'] = $tasks->p30_pay;
								$data['p30_email'] = $tasks->p30_email;
								$data['default_staff'] = $tasks->default_staff;
								$data['scheme_id'] = $tasks->scheme_id;
								$data['disclose_liability'] = $tasks->disclose_liability;
								$data['distribute_email'] = $tasks->distribute_email;
								if($tasks->task_complete_period_type == 2){							
									$data['task_complete_period'] = 1;
									$data['task_complete_period_type'] = 2;
								}
								else{
									$data['task_complete_period'] = 0;
									$data['task_complete_period_type'] = 0;							
								}
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
						$monthid = \App\Models\Month::insertDetails(['year' => $yid,'month' => 1,'practice_code' => Session::get('user_practice_code')]);
						$getmonthtasks = \App\Models\task::where('task_year',$getlastmonth->year)->where('task_month',$getlastmonth->month_id)->get();
						if(($getmonthtasks))
						{
							foreach($getmonthtasks as $monthtasks)
							{
								$datamonth['task_year'] = $yid;
								$datamonth['task_month'] = $monthid;
								$datamonth['task_name'] = $monthtasks->task_name;
								$datamonth['task_classified'] = $monthtasks->task_classified;
								$datamonth['enterhours'] = ($monthtasks->enterhours == 2)?2:0;
								$datamonth['holiday'] = ($monthtasks->holiday == 2)?2:0;
								$datamonth['process'] = ($monthtasks->process == 2)?2:0;
								$datamonth['payslips'] = ($monthtasks->payslips == 2)?2:0;
								$datamonth['email'] = ($monthtasks->email == 2)?2:0;
								$datamonth['upload'] = ($monthtasks->upload == 2)?2:0;
								$datamonth['date'] = $monthtasks->date;
								$datamonth['task_email'] = $monthtasks->task_email;
								$datamonth['comments'] = $monthtasks->comments;
								$datamonth['attached'] = $monthtasks->attached;
								$datamonth['network'] = $monthtasks->network;
								$datamonth['task_enumber'] = $monthtasks->task_enumber;
								$datamonth['secondary_email'] = $monthtasks->secondary_email;
								$datamonth['salutation'] = $monthtasks->salutation;
								$datamonth['task_status'] = 0;
								$datamonth['client_id'] = $monthtasks->client_id;
								$datamonth['tasklevel'] = $monthtasks->tasklevel;
								$datamonth['p30_pay'] = $monthtasks->p30_pay;
								$datamonth['p30_email'] = $monthtasks->p30_email;
								$datamonth['default_staff'] = $monthtasks->default_staff;	
								$datamonth['scheme_id'] = $monthtasks->scheme_id;
								$datamonth['disclose_liability'] = $monthtasks->disclose_liability;
								$datamonth['distribute_email'] = $monthtasks->distribute_email;
								if($monthtasks->task_complete_period_type == 2){							
									$datamonth['task_complete_period'] = 1;
									$datamonth['task_complete_period_type'] = 2;
								}
								else{
									$datamonth['task_complete_period'] = 0;
									$datamonth['task_complete_period_type'] = 0;							
								}					
								$tasknewmonthid = \App\Models\task::insert($datamonth);
								if($monthtasks->scheme_id > 0)
								{
									$scheme_det = \App\Models\schemes::where('practice_code',Session::get('user_practice_code'))->where('id',$monthtasks->scheme_id)->first();
									if(($scheme_det))
									{
										$upload_dir = 'uploads/task_image';
										if (!file_exists($upload_dir)) {
											mkdir($upload_dir);
										}
										$upload_dir = $upload_dir.'/'.base64_encode($tasknewmonthid);
										if (!file_exists($upload_dir)) {
											mkdir($upload_dir);
										}
										$myfile = fopen($upload_dir.'/'.$scheme_det->scheme_name.".txt", "w") or die("Unable to open file!");
										$txt = "This Payroll is to be run under the Scheme: ".$scheme_det->scheme_name."";
										fwrite($myfile, $txt);
										fclose($myfile);
										$datareceivedmonth['task_id'] = $tasknewmonthid;
										$datareceivedmonth['attachment'] = $scheme_det->scheme_name.".txt";
										$datareceivedmonth['url'] = $upload_dir;
										$datareceivedmonth['network_attach'] = 1;
										$datareceivedmonth['copied'] = 0;
										\App\Models\taskAttached::insert($datareceivedmonth);
									}
								}
							}
						}	
						$yearuseupdate = 1;
						 \App\Models\Year::where('year_id', $yid)->update(['year_used' => $yearuseupdate]);
					}
					return redirect('user/manage_task')->with('message','Year Added Successfully');
				}
			}
			else{
				$yid = \App\Models\Year::insertDetails(['year_name' => $year,'end_date' => $date,'practice_code' => Session::get('user_practice_code')]);
				$weekid = \App\Models\week::insertDetails(['year' => $yid,'week' => 1,'practice_code' => Session::get('user_practice_code')]);
				
				$monthid = \App\Models\Month::insertDetails(['year' => $yid,'month' => 1,'practice_code' => Session::get('user_practice_code')]);
				
				$yearuseupdate = 1;
				\App\Models\Year::where('year_id', $yid)->update(['year_used' => $yearuseupdate]);
				return redirect('user/manage_task')->with('message','Year Added Successfully');
			}
		}
	}
	public function updatetaskyear(Request $request){
		$id = $request->get('taskyear_id');
		return redirect('user/manage_task')->with('message','Update Success');
	}
	public function deactivetaskyear(Request $request, $id=""){
		$id = base64_decode($id);
		$deactive = 1;
		\App\Models\Year::where('year_id', $id)->update(['year_status' => $deactive]);
		return redirect('user/manage_task')->with('message','Deactive Success');
	}
	public function activetaskyear(Request $request, $id=""){
		$id = base64_decode($id);
		$active = 0;
		\App\Models\Year::where('year_id', $id)->update(['year_status' => $active]);
		return redirect('user/manage_task')->with('message','Active Success');
	}
	public function deletetaskyear($id=''){
		$id = base64_decode($id);
		$yearuseupdate = 0;
		 \App\Models\Year::where('year_id', $id)->update(['year_used' => $yearuseupdate,'delete_status' => 1]);
		return redirect('user/manage_task')->with('message','Delete Success');
	}
	public function edittaskyear(Request $request, $id=""){
		$id = base64_decode($id);		
		$year = \App\Models\Year::where('year_id', $id)->first();
		echo json_encode(array('year' => $year->year_name,'end_date'=> $year->end_date,'id' => $result->year_id));
	}
}