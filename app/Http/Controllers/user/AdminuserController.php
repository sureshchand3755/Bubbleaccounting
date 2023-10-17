<?php 
namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
class AdminuserController extends Controller {

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
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manageuser(Request $request)
	{
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->get();
		return view('user/user', array('title' => 'User', 'userlist' => $user));
	}
	public function deactiveuser(Request $request, $id=""){
		$id = base64_decode($id);
		$deactive =  1;
		\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $id)->update(['user_status' => $deactive ]);
		return redirect('user/manage_user')->with('message','Deactive Success');
	}
	public function activeuser(Request $request, $id=""){
		$id = base64_decode($id);
		$active =  0;
		\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $id)->update(['user_status' => $active ]);
		return redirect('user/manage_user')->with('message','Active Success');
	}
	public function adduser(Request $request){
		$name = $request->get('name');
		$lname = $request->get('lname');
		$email = $request->get('email');
		$initial = $request->get('initial');
		$practice = Session::get('user_practice_code');
		$id =\App\Models\User::insertDetails(['firstname' => $name, 'lastname' => $lname, 'email' => $email,'initial' => $initial,'practice' => $practice]);

		// $years =  \App\Models\Year::get();
		// if(($years))
		// {
		// 	foreach($years as $year)
		// 	{
		// 		$userid = $year->taskyear_user;
		// 		if($userid == "")
		// 		{
		// 			$dataval['taskyear_user'] = $id;
		// 		}
		// 		else{
		// 			$dataval['taskyear_user'] = $userid.','.$id;
		// 		}
		// 		 \App\Models\taskYear::where('taskyear_id', $year->taskyear_id)->update($dataval);
		// 	}
		// }
		return redirect('user/manage_user')->with('message','Add Success');
	}
	public function deleteuser(Request $request, $id=""){
		$id = base64_decode($id);
		$data['disabled'] = $_GET['status'];
		\App\Models\User::where('user_id', $id)->update($data);
		if($_GET['status'] == 1)
		{
			return redirect('user/manage_user')->with('message','User Disabled Successfully');
		}
		else{
			return redirect('user/manage_user')->with('message','User Enabled Successfully');
		}
	}
	public function edituser(Request $request, $id=""){
		$id = base64_decode($id);
		$result =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $id)->first();
		echo json_encode(array('name' => $result->firstname, 'lname' => $result->lastname, 'email' =>  $result->email,'initial' =>  $result->initial,'practice' =>  $result->practice, 'id' => $result->user_id));
	}
	public function updateuser(Request $request){
		$name = $request->get('name');
		$id = $request->get('id');
		$lname = $request->get('lname');
		$email = $request->get('email');
		$initial = $request->get('initial');
		$practice = $request->get('practice_code');

		$data['firstname'] = $name;
		$data['lastname'] = $lname;
		$data['email'] = $email;
		$data['initial'] = $initial;
		$data['practice'] = $practice;

		if($request->has('new_password')) {
			if($request->get('new_password') != "") {
				$data['password'] = Crypt::encrypt($request->get('new_password'));
			}
		}

		\App\Models\User::where('user_id', $id)->update($data);

		return redirect('user/manage_user')->with('message','Update Success');
	}
	public function check_user_email(Request $request){
		$user_id = ($request->get('id')) ? $request->get('id') : 0;

		$email = $request->get('email');
		if($user_id != 0)
		{
			$check_user =\App\Models\User::where('email',$email)->where('user_id','!=',$user_id)->where('practice',Session::get('user_practice_code'))->first();
		}
		else{
			$check_user =\App\Models\User::where('email',$email)->where('practice',Session::get('user_practice_code'))->first();
		}

		if(($check_user))
		{
			$valid = false;
		}
		else{
			$valid = true;
		}
		echo json_encode($valid);
		exit;
	}
	public function manageusercosting(Request $request){
		$user_id = base64_decode($request->get('id'));

		$calculate_count =\App\Models\userCalculateCost::where('user_id', $user_id)->first();
		$user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $user_id)->first();
		$staff_name = $user_details->firstname.' '.$user_details->lastname;

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		if(($calculate_count) == ''){
			$data['user_id'] = $user_id;
			\App\Models\userCalculateCost::insert($data);
			$user_calculate =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			echo json_encode(array('base_salary' => $user_calculate->base_salary, 'annual_bonus' => $user_calculate->annual_bonus, 'other_annual' => $user_calculate->other_annual, 'total_salary' => $user_calculate->total_salary, 'standard_hour' => $user_calculate->standard_hour, 'holiday_day' => $user_calculate->holiday_day, 'rate_social_insurance' => $user_calculate->rate_social_insurance, 'social_insurance' => $user_calculate->social_insurance, 'cost_per_hour' => $user_calculate->cost_per_hour, 'holiday_cost_per_hour' => $user_calculate->holiday_cost_per_hour, 'final_cost_per_hour' => $user_calculate->final_cost_per_hour, 'user_id' => base64_encode($user_id), 'staff_name' => $staff_name, 'output_cost' => $output_cost));
		}
		else{
			$user_calculate =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			echo json_encode(array('base_salary' => $user_calculate->base_salary, 'annual_bonus' => $user_calculate->annual_bonus, 'other_annual' => $user_calculate->other_annual, 'total_salary' => $user_calculate->total_salary, 'standard_hour' => $user_calculate->standard_hour, 'holiday_day' => $user_calculate->holiday_day, 'rate_social_insurance' => $user_calculate->rate_social_insurance, 'social_insurance' => $user_calculate->social_insurance, 'cost_per_hour' => $user_calculate->cost_per_hour, 'holiday_cost_per_hour' => $user_calculate->holiday_cost_per_hour, 'final_cost_per_hour' => $user_calculate->final_cost_per_hour, 'user_id' => base64_encode($user_id), 'staff_name' => $staff_name, 'output_cost' => $output_cost));
		}
	}

	public function usercostingupdate(Request $request){
		$value = $request->get('value');
		$user_id = base64_decode($request->get('user_id'));
		$type = $request->get('type');
		$calculate_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

		

		if($type == 1){
			$data['base_salary'] = $value;
			$data['total_salary'] = $value+$calculate_details->annual_bonus+$calculate_details->other_annual;
			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;	

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['social_insurance' => $social]);	
			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();	

			if($row_details->standard_hour == ''){				
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);
				$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}
		elseif($type==2){
			$data['annual_bonus'] = $value;
			$data['total_salary'] = $calculate_details->base_salary+$value+$calculate_details->other_annual;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['social_insurance' => $social]);
			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);				
			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;		


			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result, 'holiday_cost_per_hour' => $holiday_result, 'final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour ));
		}
		elseif($type==3){
			$data['other_annual'] = $value;
			$data['total_salary'] = $calculate_details->base_salary+$calculate_details->annual_bonus+$value;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['social_insurance' => $social]);
			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}

		elseif($type==4){
			$data['standard_hour'] =$value;
			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

 			

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);
			echo json_encode(array('per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));

		}

		elseif($type==5){
			$data['holiday_day'] =$value;
			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){				
				$holiday_result = '0';
			}
			else{
				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();


			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}
		elseif($type==6){
			$data['rate_social_insurance'] =$value;
			\App\Models\userCalculateCost::where('user_id', $user_id)->update($data);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['social_insurance' => $social]);

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();


			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				\App\Models\userCalculateCost::where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);
			}

			$row_details =\App\Models\userCalculateCost::where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			\App\Models\userCalculateCost::where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}	

	}

	public function manageusercostadd(Request $request){
		$user_id = base64_decode($request->get('user_id'));
		$count_row =\App\Models\userCost::where('user_id', $user_id)->count();

		if($count_row == 0){
			$data['user_id'] = base64_decode($request->get('user_id'));
			$data['from_date'] = date('Y-m-d', strtotime($request->get('from_date')));
			$data['cost'] = $request->get('new_cost');
		}
		else{
			$from_date = strtotime(date('Y-m-d', strtotime($request->get('from_date'))));
			$date = date('Y-m-d', strtotime($request->get('from_date')));

			$check_date = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`from_date`) <= "'.$from_date.'" AND UNIX_TIMESTAMP(`to_date`) >= "'.$from_date.'"');
			$check_fromdate_equals =\App\Models\userCost::where('user_id',$user_id)->where('from_date',$date)->get();
			$check_todate_equals =\App\Models\userCost::where('user_id',$user_id)->where('to_date',$date)->get();

			if(($check_date))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			elseif(($check_fromdate_equals))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			elseif(($check_todate_equals))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			else{
				$check_date_from = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`from_date`) > "'.$from_date.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC LIMIT 1');

				$check_date_to = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`to_date`) < "'.$from_date.'" ORDER BY UNIX_TIMESTAMP(`to_date`) DESC LIMIT 1');

				$data['user_id'] = base64_decode($request->get('user_id'));
				$data['from_date'] = date('Y-m-d', strtotime($request->get('from_date')));
				$data['cost'] = $request->get('new_cost');

				if(($check_date_from))
				{
					$next_from_date = date('Y-m-d', strtotime('-1 day', strtotime($check_date_from[0]->from_date)));
					$data['to_date'] = $next_from_date;
				}
				else{
					$data['to_date'] = '0000-00-00';
					$last_row = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) DESC LIMIT 1');

					\App\Models\userCost::where('user_id', $user_id)->latest('updatetime')->first();
					$last_to_date = date('Y-m-d', strtotime('-1 day', strtotime($request->get('from_date'))));
					\App\Models\userCost::where('cost_id', $last_row[0]->cost_id)->update(['to_date' => $last_to_date]);
				}
			}
		}
		\App\Models\userCost::insert($data);

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table" style="margin-top:20px"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		echo json_encode(array('output_cost' => $output_cost,"alert" => ""));
	}

	public function manageusercostingdelete(Request $request){
		$cost_id = base64_decode($request->get('cost_id'));
		$user_id = base64_decode($request->get('user_id'));

		\App\Models\userCost::where('cost_id', $cost_id)->delete();

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table" style="margin-top:20px"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		echo json_encode(array('output_cost' => $output_cost));

	}


}
