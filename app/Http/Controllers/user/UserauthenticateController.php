<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Hash;
use Auth;
use Redirect;
use URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
class UserauthenticateController extends Controller {
	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('userredirect', ['except' => 'getLogout']);
		date_default_timezone_set("Europe/Dublin");
		require_once app_path("Http/helpers.php");
	}
	public function home(Request $request)
	{	
		return view('pages/index', array('title' => 'Welcome'));
	}
	public function about(Request $request)
	{	
		return view('pages/about', array('title' => 'Welcome'));
	}
	public function modules(Request $request)
	{	
		return view('pages/modules', array('title' => 'Welcome'));
	}
	public function access_payroll(Request $request)
	{	
		return view('payroll/index', array('title' => 'Welcome'));
	}
	public function bubble_books(Request $request)
	{	
		return view('pages/bubble-books', array('title' => 'Welcome'));
	}
	public function contact(Request $request)
	{	
		return view('pages/contact', array('title' => 'Welcome'));
	}
	public function schedule(Request $request)
	{	
		return view('pages/schedule', array('title' => 'Welcome'));
	}
	public function demo(Request $request)
	{	
		return view('pages/demo', array('title' => 'Welcome'));
	}
	public function login(Request $request)
	{	
		return view('pages/login', array('title' => 'Welcome'));
	}
	
	public function register(Request $request)
	{	
		return view('pages/register', array('title' => 'Welcome'));
	}
	public function postLogin(Request $request)
	{
		  $validator = Validator::make($request->all(),
		                            ['userid' => 'required',
									 'password' => 'required',
									  'practice_code' => 'required']
									);
		if($validator->fails())
		{
			return redirect('login')->withInput($request->all())->with('login_error',$validator->errors());
		}
		else
		{
			$email = $request->get('userid');
			$password = $request->get('password');
			$practice_code = $request->get('practice_code');
			$user = \App\Models\User::where('email', $email)->where('practice', $practice_code)->first();
			if(!empty($user))
			{
				if($user->password == ""){
					return redirect('login')->withInput()->with('message','User need to set password for their account. Click on "LOGGING IN FOR FIRST TIME?" to set Password.');
					exit;
				}
				if($user->disabled == 1){
					return redirect('login')->withInput()->with('message','User Account has been disabled. Please contact admin to enable the user.');
					exit;
				}
				$pass = Crypt::decrypt($user->password);
				if($password === $pass){
					$details = $user;
				}
			}
			if(!empty($details))
			{	
				$dataval['logins'] = $user->logins + 1;
				\App\Models\User::where('user_id', $details->user_id)->update($dataval);
				$auditdata['user_id'] = $details->user_id;
				$auditdata['module'] = 'Login';
				$auditdata['event'] = 'Logged In';
				$auditdata['reference'] = $details->user_id;
				$auditdata['updatetime'] = date('Y-m-d H:i:s');
				\App\Models\AuditTrails::insert($auditdata);

				$sessn=array('userid' => $details->user_id);
				Session::put($sessn); 
				$sessn=array('user_practice_code' => $details->practice);
				Session::put($sessn); 
				$sessn=array('user_practice_name' => $details->practice_name);
				Session::put($sessn); 
				$sessn=array('taskmanager_user' => $details->user_id);
				Session::put($sessn);
				$sessn=array('taskmanager_user_email' => $details->email);
  				Session::put($sessn);
				$sessn=array('task_job_user' => $details->user_id);
				Session::put($sessn); 
				return redirect('/user/dashboard');
			}
			else
			{
				return redirect('login')->withInput()->with('message','Invalid Username or Password');
			}
		}
	}
	public function user_registration(Request $request)
	{
		$name = $request->get('practice_name');
		$code = $request->get('practice_code');
		$address1 = $request->get('address1');
		$address2 = $request->get('address2');
		$address3 = $request->get('address3');
		$telephone = $request->get('telephone');

		$admin_firstname = $request->get('admin_firstname');
		$admin_surname = $request->get('admin_surname');
		$admin_email = $request->get('admin_email');
		$admin_password = $request->get('admin_password');

		$practice_data['practice_name'] = $name;
		$practice_data['practice_code'] = $code;
		$practice_data['address1'] = $address1;
		$practice_data['address2'] = $address2;
		$practice_data['address3'] = $address3;
		$practice_data['phoneno'] = $telephone;

		$data['practice'] = $code;
		$data['firstname'] = $admin_firstname;
		$data['lastname'] = $admin_surname;
		$data['email'] = $admin_email;
		$data['password'] = Crypt::encrypt($admin_password);

		if($_FILES['practice_logo']['name'] != "") {
			$logo = $_FILES['practice_logo']['name'];
			$tmp_name = $_FILES['practice_logo']['tmp_name'];

			$upload_dir = 'uploads/profile_logo';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.time();
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$logo);
			$practice_data['practice_logo_filename'] = $logo;
			$practice_data['practice_logo_url'] = $upload_dir;
		}

		$data['user_type'] = 1;
		$data['user_role'] = 'Admin';
		$data['otp'] = $request->get('verified_otp');
		$userid = \App\Models\User::insertDetails($data);

		$practice_data['user_id'] = $userid;
		DB::table('practices')->insert($practice_data);

		$check_year = DB::table('pms_year')->where('practice_code',$code)->first();
		if(!$check_year) {
			$year = date('Y') - 1;
			$firstSunday = date('Y-m-d', strtotime('first sunday of January ' . $year));
			$yid = \App\Models\Year::insertDetails(['year_name' => $year,'end_date' => $firstSunday,'practice_code' => $code]);
			$weekid = \App\Models\week::insertDetails(['year' => $yid,'week' => 1,'practice_code' => $code]);
			
			$monthid = \App\Models\Month::insertDetails(['year' => $yid,'month' => 1,'practice_code' => $code]);
			
			$yearuseupdate = 1;
			\App\Models\Year::where('year_id', $yid)->update(['year_used' => $yearuseupdate]);
		}

		$pmsadmin_data['payroll_signature'] = '';
		$pmsadmin_data['payroll_cc_email'] = '';
		$pmsadmin_data['notify_message'] = '';
		$pmsadmin_data['distribute_link'] = '';
		$pmsadmin_data['email_header_url'] = '';
		$pmsadmin_data['email_header_filename'] = '';
		$pmsadmin_data['paye_mrs_email_header_url'] = '';
		$pmsadmin_data['paye_mrs_email_header_filename'] = '';
		$pmsadmin_data['practice_code'] = $code;

		DB::table('pms_admin')->insert($pmsadmin_data);
		
		$croard_data['croard_signature'] = '';
		$croard_data['croard_cc_email'] = '';
		$croard_data['croard_submission_days'] = '';
		$croard_data['username'] = '';
		$croard_data['api_key'] = '';
		$croard_data['email_header_url'] = '';
		$croard_data['email_header_filename'] = '';
		$croard_data['practice_code'] = $code;
		DB::table('croard_settings')->insert($croard_data);

		$taskmanager_data['email_header_url'] = '';
		$taskmanager_data['email_header_filename'] = '';
		$taskmanager_data['taskmanager_cc_email'] = '';
		$taskmanager_data['practice_code'] = $code;
		DB::table('taskmanager_settings')->insert($taskmanager_data);

		$request_data['crs_cc_email'] = '';
		$request_data['email_header_url'] = '';
		$request_data['email_header_filename'] = '';
		$request_data['practice_code'] = $code;
		DB::table('request_settings')->insert($request_data);

		$infile_data['cc_email'] = '';
		$infile_data['email_header_url'] = '';
		$infile_data['email_header_filename'] = '';
		$infile_data['loadcount'] = 20;
		$infile_data['practice_code'] = $code;
		DB::table('infile_settings')->insert($infile_data);

		$key_docs_data['keydocs_cc_email'] = '';
		$key_docs_data['keydocs_signature'] = '';
		$key_docs_data['email_header_url'] = '';
		$key_docs_data['email_header_filename'] = '';
		$key_docs_data['practice_code'] = $code;
		DB::table('key_docs_settings')->insert($key_docs_data);

		$yearend_data['yearend_cc_email'] = '';
		$yearend_data['email_header_url'] = '';
		$yearend_data['email_header_filename'] = '';
		$yearend_data['practice_code'] = $code;
		DB::table('yearend_settings')->insert($yearend_data);

		$messageus_data['messageus_cc_email'] = '';
		$messageus_data['email_header_url'] = '';
		$messageus_data['email_header_filename'] = '';
		$messageus_data['practice_code'] = $code;
		DB::table('messageus_settings')->insert($messageus_data);

		$aml_data['aml_cc_email'] = '';
		$aml_data['email_header_url'] = '';
		$aml_data['email_header_filename'] = '';
		$aml_data['email_signature'] = '';
		$aml_data['email_body'] = '';
		$aml_data['practice_code'] = $code;
		DB::table('aml_settings')->insert($aml_data);

		$rct_data['rct_cc_email'] = '';
		$rct_data['email_header_url'] = '';
		$rct_data['email_header_filename'] = '';
		$rct_data['practice_code'] = $code;
		DB::table('rct_settings')->insert($rct_data);

		$car_data['client_account_cc_email'] = '';
		$car_data['email_header_url'] = '';
		$car_data['email_header_filename'] = '';
		$car_data['email_signature'] = '';
		$car_data['practice_code'] = $code;
		DB::table('clientaccountreview_settings')->insert($car_data);

		$statement_data['statement_cc_email'] = '';
		$statement_data['email_body'] = '';
		$statement_data['email_signature'] = '';
		$statement_data['minimum_balance'] = '';
		$statement_data['statement_invoice'] = '';
		$statement_data['payments_to_iban'] = '';
		$statement_data['payments_to_bic'] = '';
		$statement_data['bg_filename'] = '';
		$statement_data['bg_url'] = '';
		$statement_data['bg_filename1'] = '';
		$statement_data['bg_url1'] = '';
		$statement_data['email_header_url'] = '';
		$statement_data['email_header_filename'] = '';
		$statement_data['practice_code'] = $code;
		DB::table('client_statement_settings')->insert($statement_data);

		$vat_data['vat_cc_email'] = '';
		$vat_data['email_header_url'] = '';
		$vat_data['email_header_filename'] = '';
		$vat_data['email_signature'] = '';
		$vat_data['practice_code'] = $code;
		DB::table('vat_settings')->insert($vat_data);

		$inv_data['invoice_cc_email'] = '';
		$inv_data['email_header_url'] = '';
		$inv_data['email_header_filename'] = '';
		$inv_data['email_signature'] = '';
		$inv_data['include_practice'] = 0;
		$inv_data['practice_code'] = $code;
		DB::table('invoice_system_settings')->insert($inv_data);

		$data['practice_name'] = $name;
		$data['logo'] = URL::to('public/assets/images/easy_payroll_logo.png');
		$data['decrypted_password'] = $admin_password;
		$subject = 'Bubble: Your Practice is Registered Successfully';
		$contentmessage = view('user/practice_user_registration', $data);

		$email = new PHPMailer();
		$email->SetFrom('sandra@bubble.ie'); //Name is optional
		$email->Subject   = $subject;
		$email->Body      = $contentmessage;
		$email->IsHTML(true);
		$email->AddAddress( $admin_email );
		$email->Send();
		return redirect('/login')->with('message', 'Your Practice has been Successfully Registered. You may Login now.');
	}
	public function payroll_login(Request $request){
		$emp_no = $request->get('payroll_emp_no');
		$email = $request->get('payroll_email');
		$password = $request->get('payroll_password');
		$check_details = \App\Models\EmployerUsers::where('emp_no',$emp_no)->where('email',$email)->first();
		if(($check_details))
		{
			if($check_details->status == 0){
				$db_pass = Crypt::decrypt($check_details->password);
				if($db_pass == $password){
					$data['error'] = '0';
					$data['message'] = 'Logged In Successfully';
					$data['payroll_user_id'] = $check_details->id;
					$data['payroll_user_email'] = $email;
					$activity['payroll_user_id'] = $check_details->id;
					$activity['payroll_user_email'] = $email;
					$activity['payroll_emp_no'] = $emp_no;
					$activity['action'] = 'Logged In';
					$activity['updatetime'] = date('Y-m-d H:i:s');
					\App\Models\payrollLog::insert($activity);
					$sessn=array('payroll_userid' => $check_details->id, 'payroll_user_email' => $email, 'payroll_emp_no' => $emp_no, "payroll_practice_code" => $check_details->practice_code);
					Session::put($sessn); 
					//return redirect('/payroll/dashboard');
				}
				else{
					$data['error'] = '1';
					$data['message'] = 'Invalid Credentials';
				}
			}
			else{
				$data['error'] = '1';
				$data['message'] = 'User is not Approved.';
			}
		}
		else{
			$data['error'] = '1';
			$data['message'] = 'Invalid Credentials';
		}
		echo json_encode($data);
	}
	public function verify_emp_no(Request $request){
		$empno = $request->get('empno');
		$tasks = \App\Models\task::where('task_enumber',$empno)->first();
		if(($tasks)){
			echo $tasks->task_name;
		}
		else{
			echo '';
		}
	}
	public function payroll_register(Request $request){
		$emp_no = $request->get('payroll_emp_no');
		$email = $request->get('payroll_email');
		$password = $request->get('payroll_password');
		$check_details = \App\Models\EmployerUsers::where('email',$email)->first();
		if(($check_details))
		{
			$data['error'] = '1';
			$data['message'] = 'The User is already Registered.';
		}
		else{
			$check_emp = \App\Models\Employers::where('emp_no',$emp_no)->first();
			if(($check_emp)){
				$datauser['emp_id'] = $check_emp->id;
				$datauser['emp_no'] = $emp_no;
				$datauser['email'] = $email;
				$datauser['password'] = Crypt::encrypt($password);
				$datauser['status'] = 1;
				$datauser["practice_code"] = $check_emp->practice_code;
				\App\Models\EmployerUsers::insert($datauser);
			}
			else{
				$tasks = \App\Models\task::where('task_enumber',$emp_no)->first();
				$emp_name = '';
				if(($tasks)) {
					$emp_name = $tasks->task_name;
					$dataemp['emp_no'] = $emp_no;
					$dataemp['emp_name'] = $emp_name;
					$dataemp["practice_code"] = $tasks->practice_code;
					$emp_id = \App\Models\Employers::insertDetails($dataemp);
					$datauser['emp_id'] = $emp_id;
					$datauser['emp_no'] = $emp_no;
					$datauser['email'] = $email;
					$datauser['password'] = Crypt::encrypt($password);
					$datauser['status'] = 1;
					$datauser["practice_code"] = $tasks->practice_code;
					\App\Models\EmployerUsers::insert($datauser);
				}
			}
			$data['error'] = '0';
			$data['message'] = 'Registered Successfully. You will redirected to login page. so that you can login using your credentials.';
		}
		echo json_encode($data);
	}
	public function check_user_login_count(Request $request){
		$email = $request->get('email');
		$practice_code = $request->get('practice_code');

		$user =\App\Models\User::where('email',$email)->where('practice',$practice_code)->first();
		if($user){
			if($user->disabled == 1){
				$data['msg'] = 'User Account has been disabled.';
				$data['status'] = 2;
			}
			else{
				if($user->logins == 0 && $user->password == ""){
					$data['msg'] = 'User needs to set a password for their account.';
					$data['status'] = 1;
					$data['firstname'] = $user->firstname;
					$data['lastname'] = $user->lastname;
					$data['email'] = $user->email;
					$data['user_id'] = $user->user_id;
				}
				else{
					$data['msg'] = 'User has already Logged in.';
					$data['status'] = 0;
				}
			}
		}
		else{
			$data['msg'] = 'Invalid User Email Address.';
			$data['status'] = 2;
		}
		echo json_encode($data);
	}
	public function user_logging_password(Request $request){
		$user_id = $request->get('hidden_user_id');
		$user =\App\Models\User::where('user_id',$user_id)->first();
		if(($user)){
			$data['password'] = Crypt::encrypt($request->get('password_logging'));
			$data['logins'] = $user->logins + 1;
			if($_FILES['profile_img']['name'] != ""){
				$name = $_FILES['profile_img']['name'];
				$tmp_name = $_FILES['profile_img']['name'];
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
			\App\Models\User::where('user_id',$user_id)->update($data);
			$auditdata['user_id'] = $user_id;
			$auditdata['module'] = 'Login';
			$auditdata['event'] = 'Logged In';
			$auditdata['reference'] = $user_id;
			$auditdata['updatetime'] = date('Y-m-d H:i:s');
			\App\Models\AuditTrails::insert($auditdata);
			$sessn=array('userid' => $user_id);
			Session::put($sessn);
			$sessn=array('user_practice_code' => $user->practice);
			Session::put($sessn); 
			$sessn=array('taskmanager_user' => $user_id);
			Session::put($sessn);
			$sessn=array('taskmanager_user_email' => $user->email);
  			Session::put($sessn);
			$sessn=array('task_job_user' => $user_id);
			Session::put($sessn); 
			return redirect('/user/dashboard')->with('message','Password Updated Successfuly');
		}
	}
	public function check_email_address(Request $request) {
		$email_address = $request->get('email_address');
		$check_db = DB::table('user')->where('email',$email_address)->first();
		if($check_db) {
			echo 1;
		}
		else{
			$check_temp = DB::table('temp_details')->where('email',$email_address)->first();
			if($check_temp) {
				$FourDigitRandomNumber = mt_rand(1000,9999);
				$data['otp'] = $FourDigitRandomNumber;
				$data['email'] = $email_address;
				$data['resend_count'] = $check_temp->resend_count + 1;

				DB::table('temp_details')->where('id',$check_temp->id)->update($data);

				$data['logo'] = URL::to('public/assets/images/easy_payroll_logo.png');

				$subject = 'OTP to Verify your Bubble Account';
				$contentmessage = view('user/otp_email', $data);

				$email = new PHPMailer();
				$email->SetFrom('sandra@bubble.ie'); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $email_address );
				$email->Send();
			}
			else{
				$FourDigitRandomNumber = mt_rand(1000,9999);
				$data['otp'] = $FourDigitRandomNumber;
				$data['email'] = $email_address;
				$data['resend_count'] = 0;

				DB::table('temp_details')->insert($data);

				$data['logo'] = URL::to('public/assets/images/easy_payroll_logo.png');

				$subject = 'OTP to Verify your Bubble Account';
				$contentmessage = view('user/otp_email', $data);

				$email = new PHPMailer();
				$email->SetFrom('sandra@bubble.ie'); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $email_address );
				$email->Send();
			}
		}
	}
	public function verify_otp_register(Request $request) {
		$user_email = $request->get('user_email');
		$otp = $request->get('otp');

		$check_otp = DB::table('temp_details')->where('email',$user_email)->where('otp',$otp)->first();
		if($check_otp) {
			DB::table('temp_details')->where('email',$user_email)->where('otp',$otp)->delete();
			echo $user_email;
		}
		else {
			echo 1;
		}
	}
	public function check_practice_name(Request $request) {
		$value = strtoupper($request->get('value'));
		$getcleanvalue = clean($value,'nospace');
		$practice_code = substr($getcleanvalue,0,3);

		$check_practice_code = DB::table('practices')->pluck('practice_code')->toArray();
		if(!in_array($practice_code, $check_practice_code)) {
			echo $practice_code;
			exit;
		}
		else{
			$getcleanvalue = clean($value,'hyphen');
			$explodevalue = explode('-',$getcleanvalue);
			$practice_code = substr($explodevalue[0],0,1).substr($explodevalue[1],0,1).substr($explodevalue[2],0,1);
			if(!in_array($practice_code, $check_practice_code)) {
				echo $practice_code;
				exit;
			}
			else{
				$check_db = DB::table('practices')->where('practice_code','like',substr($explodevalue[0],0,1).'%')->orderBy('practice_code','desc')->get();
				if(is_countable($check_db) && count($check_db) > 0) {
					$ival = 1;
					foreach($check_db as $db){
						$getCode = substr($db->practice_code,1);
						if(is_numeric($getCode)) {
							$codeval = $getCode + 1;
							if($codeval > 9) {
								$codeval = $codeval;
							}
							else{
								$codeval = '0'.$codeval;
							}
							$practice_code = substr($explodevalue[0],0,1).$codeval;
							$ival++;
							echo $practice_code;
							exit;
						}
					}

					if($ival == 1) {
						$practice_code = substr($explodevalue[0],0,1).'01';
						echo $practice_code;
						exit;
					}
				}
				else{
					$practice_code = substr($explodevalue[0],0,1).'01';
					echo $practice_code;
					exit;
				}
			}
		}
	}
	public function get_user_practice_code(Request $request) {
		$email = $request->get('value');
		$get_codes = DB::table('user')->where('email', $email)->get();
		$output = '';
		if(is_countable($get_codes) && count($get_codes) > 0) {
			foreach($get_codes as $code) {
				$output.='<option value="'.$code->practice.'">'.$code->practice.'</option>';
			}
		}
		else{
			$output = '<option value="">No Matching Practice code Found</option>';
		}
		echo $output;
	}
}
