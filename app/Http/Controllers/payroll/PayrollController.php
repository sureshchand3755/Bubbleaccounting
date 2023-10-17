<?php namespace App\Http\Controllers\payroll;
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
use Illuminate\Http\Request;
class PayrollController extends Controller {
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
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function dashboard(Request $request)
	{
		return view('payroll/dashboad', array('title' => 'Payroll - Dashboard'));
	}
	public function logout(Request $request){

		$payroll_user_id = Session::get('payroll_userid');
		$email = Session::get('payroll_user_email');
		$emp_no = Session::get('payroll_emp_no');

		$activity['payroll_user_id'] = $payroll_user_id;
		$activity['payroll_user_email'] = $email;
		$activity['payroll_emp_no'] = $emp_no;
		$activity['action'] = 'Logged Out';
		$activity['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\payrollLog::insert($activity);

		Session::forget("payroll_userid");
		return redirect(URL::to('access-payroll'));
	}
	public function load_weekly_payroll_tasks(Request $request) {
		$id = $request->get('weekid');
		$week_id = \App\Models\week::where('week_id', $id)->first();
		$week2 = $week_id->week_id;
		$year2 = $week_id->year;

		$userid = Session::get('payroll_userid');
	    $user_details = \App\Models\EmployerUsers::where('practice_code',Session::get('payroll_practice_code'))->where('id', $userid)->first();

		$tasks = \App\Models\task::where('task_year', $year2)->where('task_week', $week2)->where('task_enumber', $user_details->emp_no)->get();
		$output = '<table class="table">
		<thead>
			<th>Files/Instructions Received</th>
			<th>Processed By</th>
			<th>Payroll Files</th>
			<th>PAYE/PRSI/USC Liability</th>
			<th>Emails</th>
		</thead>
		<tbody>';
		if(($tasks)){
			foreach($tasks as $task){
				$attachments = \App\Models\taskAttached::where('task_id', $task->task_id)->where('network_attach',1)->get();
				$files_received = 'No Attachments';
                if(($attachments))
                {
                  	$files_received ='<div class="scroll_attachment_div">';
                      foreach($attachments as $attachment)
                      {
                          $files_received.='<a class="fileattachment" href="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" download>'.$attachment->attachment.'</a><br/>';
                      }
                  	$files_received.='</div>';
                }
				$attachments = \App\Models\taskAttached::where('task_id', $task->task_id)->where('network_attach',0)->get();
				$payroll_files = 'No Attachments';
				if(($attachments))
				{
				  $payroll_files ='<div class="scroll_attachment_div">';
				    foreach($attachments as $attachment)
				    {
				        $payroll_files.='<a class="fileattachment" href="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" download>'.$attachment->attachment.'</a><br/>';
				    }
				  $payroll_files.='</div>';
				}
				$processedBy = '';
				if($task->users != ""){
					$userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $task->users)->where('user_status', 0)->where('disabled',0)->first();
					if($userdetails){
					    $processedBy = $userdetails->lastname.' '.$userdetails->firstname;
					}
				}
				$liability = $task->liability;
				$last_date = ' - ';
				if($task->last_email_sent != '0000-00-00 00:00:00')
                {
                  $get_dates = \App\Models\taskEmailSent::where('task_id', $task->task_id)->where('options','!=','n')->get();
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
                    $date = date('d F Y', strtotime($task->last_email_sent));
                    $time = date('H : i', strtotime($task->last_email_sent));
                    $last_date = '<p>'.$date.' @ '.$time.'</p>';
                  }
                }
				$output.= '<tr>
					<td style="vertical-align: middle;">'.$files_received.'</td>
					<td style="vertical-align: middle;">'.$processedBy.'</td>
					<td style="vertical-align: middle;">'.$payroll_files.'</td>
					<td style="vertical-align: middle;">'.$liability.'</td>
					<td style="vertical-align: middle;">'.$last_date.'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="4" style="text-align:center">No Task Found</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';

		$payroll_user_id = Session::get('payroll_userid');
		$email = Session::get('payroll_user_email');
		$emp_no = Session::get('payroll_emp_no');

		$activity['payroll_user_id'] = $payroll_user_id;
		$activity['payroll_user_email'] = $email;
		$activity['payroll_emp_no'] = $emp_no;
		$activity['action'] = 'Viewed Week '.$week_id->week;
		\App\Models\payrollLog::insert($activity);

		$listing = '';
		$logs = \App\Models\payrollLog::where('payroll_user_id',Session::get('payroll_userid'))->orderBy('id','desc')->get();
		if(($logs)){
			foreach($logs as $log){
			  $listing.='<tr>
			    <td>'.$log->action.'</td>
			    <td>'.date('d F Y', strtotime($log->updatetime)).'</td>
			    <td>'.date('H:i:s', strtotime($log->updatetime)).'</td>
			  </tr>';
			}
		}

		$dataa['output'] = $output;
		$dataa['listing'] = $listing;
		echo json_encode($dataa);
	}
	public function load_monthly_payroll_tasks(Request $request) {
		$id = $request->get('monthid');
		$month_id = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('month_id', $id)->first();
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;

		$userid = Session::get('payroll_userid');
	    $user_details = \App\Models\EmployerUsers::where('practice_code',Session::get('payroll_practice_code'))->where('id', $userid)->first();

		$tasks = \App\Models\task::where('task_year', $year2)->where('task_month', $month2)->where('task_enumber', $user_details->emp_no)->get();
		$output = '<table class="table">
		<thead>
			<th>Files/Instructions Received</th>
			<th>Processed By</th>
			<th>Payroll Files</th>
			<th>PAYE/PRSI/USC Liability</th>
			<th>Emails</th>
		</thead>
		<tbody>';
		if(($tasks)){
			foreach($tasks as $task){
				$attachments = \App\Models\taskAttached::where('task_id', $task->task_id)->where('network_attach',1)->get();
				$files_received = 'No Attachments';
                if(($attachments))
                {
                  	$files_received ='<div class="scroll_attachment_div">';
                      foreach($attachments as $attachment)
                      {
                          $files_received.='<a class="fileattachment" href="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" download>'.$attachment->attachment.'</a><br/>';
                      }
                  	$files_received.='</div>';
                }
				$attachments = \App\Models\taskAttached::where('task_id', $task->task_id)->where('network_attach',0)->get();
				$payroll_files = 'No Attachments';
				if(($attachments))
				{
				  $payroll_files ='<div class="scroll_attachment_div">';
				    foreach($attachments as $attachment)
				    {
				        $payroll_files.='<a class="fileattachment" href="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" download>'.$attachment->attachment.'</a><br/>';
				    }
				  $payroll_files.='</div>';
				}
				$processedBy = '';
				if($task->users != ""){
					$userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $task->users)->where('user_status', 0)->where('disabled',0)->first();
					$processedBy = $userdetails->lastname.' '.$userdetails->firstname;
				}
				$liability = $task->liability;

				$last_date = ' - ';
				if($task->last_email_sent != '0000-00-00 00:00:00')
                {
                  $get_dates = \App\Models\taskEmailSent::where('task_id', $task->task_id)->where('options','!=','n')->get();
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
                    $date = date('d F Y', strtotime($task->last_email_sent));
                    $time = date('H : i', strtotime($task->last_email_sent));
                    $last_date = '<p>'.$date.' @ '.$time.'</p>';
                  }
                }
				$output.= '<tr>
					<td style="vertical-align: middle;">'.$files_received.'</td>
					<td style="vertical-align: middle;">'.$processedBy.'</td>
					<td style="vertical-align: middle;">'.$payroll_files.'</td>
					<td style="vertical-align: middle;">'.$liability.'</td>
					<td style="vertical-align: middle;">'.$last_date.'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="4" style="text-align:center">No Task Found</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';

		$payroll_user_id = Session::get('payroll_userid');
		$email = Session::get('payroll_user_email');
		$emp_no = Session::get('payroll_emp_no');

		$activity['payroll_user_id'] = $payroll_user_id;
		$activity['payroll_user_email'] = $email;
		$activity['payroll_emp_no'] = $emp_no;
		$activity['action'] = 'Viewed Month '.$month_id->month;
		\App\Models\payrollLog::insert($activity);

		$listing = '';
		$logs = \App\Models\payrollLog::where('payroll_user_id',Session::get('payroll_userid'))->orderBy('id','desc')->get();
		if(($logs)){
			foreach($logs as $log){
			  $listing.='<tr>
			    <td>'.$log->action.'</td>
			    <td>'.date('d F Y', strtotime($log->updatetime)).'</td>
			    <td>'.date('H:i:s', strtotime($log->updatetime)).'</td>
			  </tr>';
			}
		}

		$dataa['output'] = $output;
		$dataa['listing'] = $listing;
		echo json_encode($dataa);
	}
	public function load_year_tasks(Request $request){
		$default_year = $request->get('year');
		$year = \App\Models\Year::where('year_id', $default_year)->first();
        $weeklist = \App\Models\week::where('year', $default_year)->orderBy('week_id','desc')->get();
        $monthlist = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where('year', $default_year)->orderBy('month_id','desc')->get();

        $end_date = $year->end_date;
		$arraydate = array();
		if(($weeklist)){
			foreach($weeklist as $week){
			  $explode = explode('-', $end_date);
			  $start_date = $explode[1].'-'.$explode[2].'-'.$explode[0];
			  array_push($arraydate,$end_date);
			  $end_date = date('Y-m-d', strtotime("+7 days", strtotime($end_date)));
			}
		}
		$weeklylist = '';
		if(($weeklist)){
		  $count = count($arraydate) - 1;
		  foreach($weeklist as $key => $week){
		  	$weeklylist.='<tr>
	              <td><a href="'.URL::to('user/select_week/'.base64_encode($week->week_id)).'" class="btn">Week '.$week->week.'</a></td>
	              <td><label>'.date('d-F-Y', strtotime($arraydate[$count])).'</label></td>
	              <td><a href="javascript:" data-element="'.$week->week_id.'" class="load_week_tasks common_black_button" style="line-height:32px">Load</a></td>
	          </tr>

	          <tr class="hidden_week_tr hidden_week_tr_'.$week->week_id.'" style="display:none">
	            <td colspan="3"></td>
	          </tr>';
	          $count = $count - 1;
		  }
		}

		$end_date = $year->end_date;
		$arraydate = array();
		if(($monthlist)){
			foreach($monthlist as $month){
			  $start_month_date = date('Y-F-01',strtotime($end_date));
			  $end_month_date = date('Y-F-t',strtotime($end_date));

			  $explode_start = explode('-', $start_month_date);
			  $explode_end = explode('-', $end_month_date);
			  $start_month_date = $explode_start[1].' '.$explode_start[2].' '.$explode_start[0];
			  $end_month_date = $explode_end[1].' '.$explode_end[2].' '.$explode_end[0];

			  array_push($arraydate,$start_month_date.' - '.$end_month_date);
			  $end_date = date('Y-m-d', strtotime("+2 days", strtotime(date('Y-m-t',strtotime($end_date)))));
			}
		}
		$monthlylist = '';
		if(($monthlist)){
          $count = count($arraydate) - 1;
          foreach($monthlist as $month){
          	$monthlylist.='<tr>
              <td><a href="'.URL::to('user/select_month/'.base64_encode($month->month_id)).'" class="btn">Month '.$month->month.'</a></td>
              <td><label>'.$arraydate[$count].'</label></td>
              <td><a href="javascript:" data-element="'.$month->month_id.'" class="load_month_tasks common_black_button" style="line-height:32px">Load</a></td>
          </tr>
          <tr class="hidden_month_tr hidden_month_tr_'.$month->month_id.'" style="display:none">
            <td colspan="3"></td>
          </tr>';
          $count = $count - 1;
      } }

      $dataa['weeklist'] = $weeklylist;
      $dataa['monthlist'] = $monthlylist;
      echo json_encode($dataa);
	}
}
