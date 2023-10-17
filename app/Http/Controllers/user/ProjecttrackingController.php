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
class ProjecttrackingController extends Controller {
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
	public function tracking_project(Request $request)
	{
		return view('user/project_tracking/project_tracking', array('title' => 'Project Tracking'));
	}
  	public function tracking_project_common_search(Request $request)
  	{
	    $value = $request->get('value');
	    $single = \App\Models\trackingProjects::where('id',$value)->first();
	    if($single){
	      $tracking_type = \App\Models\trackingType::where('id',$single->tracking_type)->first();
	      $data = array('value'=>$single->project_name,'creation_date'=>date('d-M-Y', strtotime($single->creation_date)),'project_type'=>$single->tracking_type,'project_description'=>$tracking_type->description,'id'=>$single->id);
	      echo json_encode($data);
	    }
  	}
  	public function check_project_tracking_project_name(Request $request)
	{
		$project_name = $request->get('project_name');
		$check = \App\Models\trackingProjects::where('project_name', $project_name)->where('practice_code',Session::get('user_practice_code'))->first();
		if(($check))
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}
	public function create_project_tracking_project(Request $request)
	{
		$project_name 	= $request->get('project_name');
		$project_type 	= $request->get('project_type');
		$creation_date 	= $request->get('creation_date');
		$monthly_value 	= $request->get('monthly_value');
		$data['project_name'] = $project_name;
		$data['creation_date'] = date('Y-m-d', strtotime($creation_date));
		$data['tracking_type'] = $project_type;
		$data['practice_code'] = Session::get('user_practice_code');
		if($project_type == "5"){
			$data['monthly_value'] = $monthly_value;
		}
		\App\Models\trackingProjects::insert($data);
		$project_type_details = \App\Models\trackingType::where('id',$project_type)->first();
		echo '<tr class="project_list_tr">
	        <td>'.$project_name.'</td>
	        <td>'.$project_type_details->tracking_type.'</td>
	        <td>'.date('d-M-Y', strtotime($creation_date)).'</td>
	      </tr>';
	}
	public function load_project_tracking_clients(Request $request){
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $project_details->tracking_type;
		$project_clients = \App\Models\projectClients::where('project_id',$project_id)->get();
		$table_width = '60%';
		if($project_type == 1){
			$thead = '<th>Yes/No</th>
			<th>Comment</th>';
			$thead_count = '4';
		}
		elseif($project_type == 2){
			$thead = '<th>Yes/No</th>
			<th>Date</th>';
			$thead_count = '4';
		}
		elseif($project_type == 3){
			$thead = '<th>Yes/No</th>
			<th>Comment</th>
			<th>Date</th>';
			$thead_count = '5';
		}
		elseif($project_type == 4){
			$thead = '<th>Value</th>';
			$thead_count = '3';
		}
		elseif($project_type == 5){
			$selected_year = $project_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$thead = '<th>Dec - '.$previous_year.'</th>
			<th>Jan-'.$selected_year.'</th>
			<th>Feb-'.$selected_year.'</th>
			<th>Mar-'.$selected_year.'</th>
			<th>Apr-'.$selected_year.'</th>
			<th>May-'.$selected_year.'</th>
			<th>Jun-'.$selected_year.'</th>
			<th>Jul-'.$selected_year.'</th>
			<th>Aug-'.$selected_year.'</th>
			<th>Sep-'.$selected_year.'</th>
			<th>Oct-'.$selected_year.'</th>
			<th>Nov-'.$selected_year.'</th>
			<th>Dec-'.$selected_year.'</th>
			<th>Jan-'.$future_year.'</th>';
			$table_width = '100%';
			$thead_count = '16';
		}
		else{
			$thead = '';
		}
		$output = '<table class="table loadData" style="margin-top:20px;width:'.$table_width.'">
		<thead>
			<th style="width: 88px;">Client ID</th>
			<th style="width: 386px;">Client Name</th>
			'.$thead.'
		</thead>
		<tbody>';
		if(($project_clients))
		{
			foreach($project_clients as $client)
			{
				if($client->project_status == 1) { $yesno_checked = 'checked'; } else { $yesno_checked = ''; }
				if($client->project_date != "0000-00-00 00:00:00"){
					$project_date = date('d-M-Y',strtotime($client->project_date));
				}
				else{
					$project_date = '';
				}
				if($project_type == 1){
					$tbody = '<td><input type="checkbox" name="project_status_'.$client->id.'" class="project_status project_status_'.$client->id.'" id="project_status_'.$client->id.'" value="'.$client->id.'" data-element="'.$client->id.'" '.$yesno_checked.'><label for="project_status_'.$client->id.'">Yes</label></td>
					<td><input type="text" name="project_comment_'.$client->id.'" class="form-control project_comment project_comment_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->comment.'"></td>';
				}
				elseif($project_type == 2){
					$tbody = '<td><input type="checkbox" name="project_status_'.$client->id.'" class="project_status project_status_'.$client->id.'" id="project_status_'.$client->id.'" value="'.$client->id.'" data-element="'.$client->id.'" '.$yesno_checked.'><label for="project_status_'.$client->id.'">Yes</label></td>
					<td><input type="text" name="project_date_'.$client->id.'" class="form-control project_date project_date_'.$client->id.'" data-element="'.$client->id.'" value="'.$project_date.'"></td>';
				}
				elseif($project_type == 3){
					$tbody = '<td><input type="checkbox" name="project_status_'.$client->id.'" class="project_status project_status_'.$client->id.'" id="project_status_'.$client->id.'" value="'.$client->id.'" data-element="'.$client->id.'" '.$yesno_checked.'><label for="project_status_'.$client->id.'">Yes</label></td>
					<td><input type="text" name="project_comment_'.$client->id.'" class="form-control project_comment project_comment_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->comment.'"></td>
					<td><input type="text" name="project_date_'.$client->id.'" class="form-control project_date project_date_'.$client->id.'" data-element="'.$client->id.'" value="'.$project_date.'"></td>';
				}
				elseif($project_type == 4){
					$tbody = '<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.number_format_invoice($client->value).'" data-value="1"></td>';
				}
				elseif($project_type == 5){
					$selected_year = $project_details->monthly_value;
					$previous_year = $selected_year - 1;
					$future_year = $selected_year + 1;
					$tbody = '<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_1.'" data-value="1"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_2.'" data-value="2"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_3.'" data-value="3"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_4.'" data-value="4"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_5.'" data-value="5"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_6.'" data-value="6"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_7.'" data-value="7"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_8.'" data-value="8"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_9.'" data-value="9"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_10.'" data-value="10"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_11.'" data-value="11"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_12.'" data-value="12"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_13.'" data-value="13"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_14.'" data-value="14"></td>';
				}
				else{
					$tbody = '';
				}
				$cm_clients = \App\Models\CMClients::where('client_id',$client->client_id)->first();
				$output.='<tr>
					<td style="vertical-align:middle">'.$client->client_id.'</td>
					<td style="vertical-align:middle">'.$cm_clients->company.'</td>
					'.$tbody.'
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="'.$thead_count.'">No Clients Added to this Project</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function export_client_project_tracking(Request $request){
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $project_details->tracking_type;
		$tracking_type_details = \App\Models\trackingType::where('id',$project_type)->first();
		$project_clients = \App\Models\projectClients::where('project_id',$project_id)->get();
		$filename = time().'_Client Project Tracking Data.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		$proj_columns = array("Project Name:",$project_details->project_name);
		fputcsv($file, $proj_columns);
		$proj_columns_type = array("Project Type:",$tracking_type_details->tracking_type);
		fputcsv($file, $proj_columns_type);
		$empty_columns = array("","","");
		fputcsv($file, $empty_columns);
		$heading_columns = array("Project Data","","");
		fputcsv($file, $heading_columns);
		if($project_type == 1){
			$columns1 = array("Client ID","Client Name", "Confirmation Boolean", "Comment");
			fputcsv($file,$columns1);
		}
		elseif($project_type == 2){
			$columns1 = array("Client ID","Client Name", "Confirmation Boolean", "Date");
			fputcsv($file,$columns1);
		}
		elseif($project_type == 3){
			$columns1 = array("Client ID","Client Name", "Confirmation Boolean", "Comment", "Date");
			fputcsv($file,$columns1);
		}
		elseif($project_type == 4){
			$columns1 = array("Client ID","Client Name", "Value");
			fputcsv($file,$columns1);
		}
		elseif($project_type == 5){
			$selected_year = $project_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$columns1 = array("Client ID","Client Name", 'desc'.$previous_year, 'Jan'.$selected_year, 'Feb-'.$selected_year, 'Mar-'.$selected_year, 'Apr-'.$selected_year, 'May-'.$selected_year, 'Jun-'.$selected_year, 'Jul-'.$selected_year, 'Aug-'.$selected_year, 'Sep-'.$selected_year, 'Oct-'.$selected_year, 'Nov-'.$selected_year, 'Dec-'.$selected_year, 'Jan-'.$future_year);
			fputcsv($file,$columns1);
		}
		elseif($project_type == 6){
			$columns1 = array("Client ID","Client Name", "Value");
			fputcsv($file,$columns1);
		}
		elseif($project_type == 7){
			$selected_year = $project_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$columns1 = array("Client ID","Client Name", 'Dec-'.$previous_year, 'Jan'.$selected_year, 'Feb-'.$selected_year, 'Mar-'.$selected_year, 'Apr-'.$selected_year, 'May-'.$selected_year, 'Jun-'.$selected_year, 'Jul-'.$selected_year, 'Aug-'.$selected_year, 'Sep-'.$selected_year, 'Oct-'.$selected_year, 'Nov-'.$selected_year, 'Dec-'.$selected_year, 'Jan-'.$future_year);
			fputcsv($file,$columns1);
		}
		if(($project_clients))
		{
			foreach($project_clients as $client)
			{
				if($client->project_status == 1) { $yesno_checked = 'Yes'; } else { $yesno_checked = 'No'; }
				if($client->project_date != "0000-00-00 00:00:00"){
					$project_date = date('d-m-Y',strtotime($client->project_date));
				}
				else{
					$project_date = '';
				}
				$cm_clients = \App\Models\CMClients::where('client_id',$client->client_id)->first();
				if($project_type == 1){
					$columns2 = array($client->client_id,$cm_clients->company,$yesno_checked,$client->comment);
				}
				elseif($project_type == 2){
					$columns2 = array($client->client_id,$cm_clients->company,$yesno_checked,$project_date);
				}
				elseif($project_type == 3){
					$columns2 = array($client->client_id,$cm_clients->company,$yesno_checked,$client->comment,$project_date);
				}
				elseif($project_type == 4){
					$columns2 = array($client->client_id,$cm_clients->company,$client->value);
				}
				elseif($project_type == 5){
					$selected_year = $project_details->monthly_value;
					$previous_year = $selected_year - 1;
					$future_year = $selected_year + 1;
					$columns2 = array($client->client_id,$cm_clients->company,$client->month_1,$client->month_2,$client->month_3,$client->month_4,$client->month_5,$client->month_6,$client->month_7,$client->month_8,$client->month_9,$client->month_10,$client->month_11,$client->month_12,$client->month_13,$client->month_14);
				}
				elseif($project_type == 6){
					$columns2 = array($client->client_id,$cm_clients->company,$client->value);
				}
				elseif($project_type == 7){
					$selected_year = $project_details->monthly_value;
					$previous_year = $selected_year - 1;
					$future_year = $selected_year + 1;
					$columns2 = array($client->client_id,$cm_clients->company,$client->month_1,$client->month_2,$client->month_3,$client->month_4,$client->month_5,$client->month_6,$client->month_7,$client->month_8,$client->month_9,$client->month_10,$client->month_11,$client->month_12,$client->month_13,$client->month_14);
				}
				fputcsv($file, $columns2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function cliets_list_for_project_tracking(Request $request){
		$project_id = $request->get('project_id');
		$added_clients = \App\Models\projectClients::where('project_id',$project_id)->pluck('client_id')->toArray();
		$clients = \App\Models\CMClients::whereNotIn('client_id',$added_clients)->where('practice_code',Session::get('user_practice_code'))->get();
		$output = '<table class="table loadData1" id="project_tracking_expand">
		<thead>
			<th>Client ID</th>
			<th>Firstname</th>
			<th>Lastname</th>
			<th>Company</th>
		</thead>
		<tbody>';
		if(($clients))
		{
			foreach($clients as $client)
			{
				$disabled = '';
	            if($client->active != "")
	            {
	              if($client->active == 2)
	              {
	                $disabled='inactive_clients';
	                $check_color = \App\Models\CMClass::where('id',$client->active)->first();
	              	$style="color:#".$check_color->classcolor."";
	              }
	              else{
	              	$style="color:#000";
	              }
	            }
	            else{
	              $style="color:#000";
	            }
				$output.='<tr class="'.$disabled.'">
					<td><input type="checkbox" name="select_client_tracking" class="form-control select_client_tracking" id="select_client_tracking_'.$client->client_id.'" data-element="'.$client->client_id.'"> <label for="select_client_tracking_'.$client->client_id.'" style="'.$style.'">'.$client->client_id.'</label></td>
					<td><label for="select_client_tracking_'.$client->client_id.'" style="'.$style.'">'.$client->firstname.'</label></td>
					<td><label for="select_client_tracking_'.$client->client_id.'" style="'.$style.'">'.$client->surname.'</label></td>
					<td><label for="select_client_tracking_'.$client->client_id.'" style="'.$style.'">'.$client->company.'</label></td>
				</tr>';
			}
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function submit_clients_to_project_tracking(Request $request){
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $project_details->tracking_type;
		if($project_type < 6){
			$ids = explode(',',$request->get('ids'));
			if(($ids))
			{
				foreach($ids as $id){
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					$data['client_id'] = $id;
					\App\Models\projectClients::insert($data);
				}
			}
		}
		else{
			$project_computations = \App\Models\projectComputations::where('project_id',$project_id)->get();
			$ids = explode(',',$request->get('ids'));
			if($project_type == 6){
				if(($ids))
				{
					foreach($ids as $id){
						$symbol = 0;
						$last_value = 0;
						if(($project_computations)) {
							foreach($project_computations as $computation){
								if($last_value !== 'Error'){
									$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$id)->first();
									if(($prev_proj)) {
										if(trim($prev_proj->value) == "") { $comment = 0; }
										else { $comment = $prev_proj->value; }
										if($symbol == 0){
											$client_value = $comment;
										}else{
											if($symbol == 1){ $client_value = $last_value + $comment; }
											elseif($symbol == 2){ $client_value = $last_value - $comment; }
											elseif($symbol == 3){ $client_value = $last_value / $comment; }
											elseif($symbol == 4){ $client_value = $last_value * $comment; }
										}
									}
									else{
										$client_value = 'Error';
									}
									$symbol = $computation->computation;
									$last_value = $client_value;
								}
								else{
									$symbol = $computation->computation;
									$last_value = 'Error';
								}
							}
						}
						$datacomplex['project_id'] = $project_id;
						$datacomplex['project_type'] = $project_type;
						$datacomplex['client_id'] = $id;
						$datacomplex['value'] = $last_value;
						\App\Models\projectClients::insert($datacomplex);
					}
				}
			}
			if($project_type == 7){
				if(($ids))
				{
					foreach($ids as $id){
						$symbol = 0;
						$last_month1_value = 0;
						$last_month2_value = 0;
						$last_month3_value = 0;
						$last_month4_value = 0;
						$last_month5_value = 0;
						$last_month6_value = 0;
						$last_month7_value = 0;
						$last_month8_value = 0;
						$last_month9_value = 0;
						$last_month10_value = 0;
						$last_month11_value = 0;
						$last_month12_value = 0;
						$last_month13_value = 0;
						$last_month14_value = 0;
						if(($project_computations)) {
							foreach($project_computations as $computation){
								if($last_month1_value === "Error"){ $last_month1_value = 'Error'; }
								if($last_month2_value === "Error"){ $last_month2_value = 'Error'; }
								if($last_month3_value === "Error"){ $last_month3_value = 'Error'; }
								if($last_month4_value === "Error"){ $last_month4_value = 'Error'; }
								if($last_month5_value === "Error"){ $last_month5_value = 'Error'; }
								if($last_month6_value === "Error"){ $last_month6_value = 'Error'; }
								if($last_month7_value === "Error"){ $last_month7_value = 'Error'; }
								if($last_month8_value === "Error"){ $last_month8_value = 'Error'; }
								if($last_month9_value === "Error"){ $last_month9_value = 'Error'; }
								if($last_month10_value === "Error"){ $last_month10_value = 'Error'; }
								if($last_month11_value === "Error"){ $last_month11_value = 'Error'; }
								if($last_month12_value === "Error"){ $last_month12_value = 'Error'; }
								if($last_month13_value === "Error"){ $last_month13_value = 'Error'; }
								if($last_month14_value === "Error"){ $last_month14_value = 'Error'; }
								$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$id)->first();
								if(($prev_proj)) {
									$month_1 = $prev_proj->month_1;
									$month_2 = $prev_proj->month_2;
									$month_3 = $prev_proj->month_3;
									$month_4 = $prev_proj->month_4;
									$month_5 = $prev_proj->month_5;
									$month_6 = $prev_proj->month_6;
									$month_7 = $prev_proj->month_7;
									$month_8 = $prev_proj->month_8;
									$month_9 = $prev_proj->month_9;
									$month_10 = $prev_proj->month_10;
									$month_11 = $prev_proj->month_11;
									$month_12 = $prev_proj->month_12;
									$month_13 = $prev_proj->month_13;
									$month_14 = $prev_proj->month_14;
									if(trim($prev_proj->month_1) == "") { $month_1 = 0; }
									if(trim($prev_proj->month_2) == "") { $month_2 = 0; }
									if(trim($prev_proj->month_3) == "") { $month_3 = 0; }
									if(trim($prev_proj->month_4) == "") { $month_4 = 0; }
									if(trim($prev_proj->month_5) == "") { $month_5 = 0; }
									if(trim($prev_proj->month_6) == "") { $month_6 = 0; }
									if(trim($prev_proj->month_7) == "") { $month_7 = 0; }
									if(trim($prev_proj->month_8) == "") { $month_8 = 0; }
									if(trim($prev_proj->month_9) == "") { $month_9 = 0; }
									if(trim($prev_proj->month_10) == "") { $month_10 = 0; }
									if(trim($prev_proj->month_11) == "") { $month_11 = 0; }
									if(trim($prev_proj->month_12) == "") { $month_12 = 0; }
									if(trim($prev_proj->month_13) == "") { $month_13 = 0; }
									if(trim($prev_proj->month_14) == "") { $month_14 = 0; }
									if($symbol == 0){
										$client_value_month_1 = $month_1;
										$client_value_month_2 = $month_2;
										$client_value_month_3 = $month_3;
										$client_value_month_4 = $month_4;
										$client_value_month_5 = $month_5;
										$client_value_month_6 = $month_6;
										$client_value_month_7 = $month_7;
										$client_value_month_8 = $month_8;
										$client_value_month_9 = $month_9;
										$client_value_month_10 = $month_10;
										$client_value_month_11 = $month_11;
										$client_value_month_12 = $month_12;
										$client_value_month_13 = $month_13;
										$client_value_month_14 = $month_14;
									}else{
										if($symbol == 1){ 
											$client_value_month_1 = $last_month1_value + $month_1;
											$client_value_month_2 = $last_month2_value + $month_2;
											$client_value_month_3 = $last_month3_value + $month_3;
											$client_value_month_4 = $last_month4_value + $month_4;
											$client_value_month_5 = $last_month5_value + $month_5; 
											$client_value_month_6 = $last_month6_value + $month_6;
											$client_value_month_7 = $last_month7_value + $month_7;
											$client_value_month_8 = $last_month8_value + $month_8;
											$client_value_month_9 = $last_month9_value + $month_9;
											$client_value_month_10 = $last_month10_value + $month_10;
											$client_value_month_11 = $last_month11_value + $month_11;
											$client_value_month_12 = $last_month12_value + $month_12;
											$client_value_month_13 = $last_month13_value + $month_13;
											$client_value_month_14 = $last_month14_value + $month_14;
										}
										elseif($symbol == 2){ 
											$client_value_month_1 = $last_month1_value - $month_1;
											$client_value_month_2 = $last_month2_value - $month_2;
											$client_value_month_3 = $last_month3_value - $month_3;
											$client_value_month_4 = $last_month4_value - $month_4;
											$client_value_month_5 = $last_month5_value - $month_5; 
											$client_value_month_6 = $last_month6_value - $month_6;
											$client_value_month_7 = $last_month7_value - $month_7;
											$client_value_month_8 = $last_month8_value - $month_8;
											$client_value_month_9 = $last_month9_value - $month_9;
											$client_value_month_10 = $last_month10_value - $month_10;
											$client_value_month_11 = $last_month11_value - $month_11;
											$client_value_month_12 = $last_month12_value - $month_12;
											$client_value_month_13 = $last_month13_value - $month_13;
											$client_value_month_14 = $last_month14_value - $month_14;
										}
										elseif($symbol == 3){ 
											$client_value_month_1 = $last_month1_value / $month_1;
											$client_value_month_2 = $last_month2_value / $month_2;
											$client_value_month_3 = $last_month3_value / $month_3;
											$client_value_month_4 = $last_month4_value / $month_4;
											$client_value_month_5 = $last_month5_value / $month_5; 
											$client_value_month_6 = $last_month6_value / $month_6;
											$client_value_month_7 = $last_month7_value / $month_7;
											$client_value_month_8 = $last_month8_value / $month_8;
											$client_value_month_9 = $last_month9_value / $month_9;
											$client_value_month_10 = $last_month10_value / $month_10;
											$client_value_month_11 = $last_month11_value / $month_11;
											$client_value_month_12 = $last_month12_value / $month_12;
											$client_value_month_13 = $last_month13_value / $month_13;
											$client_value_month_14 = $last_month14_value / $month_14;
										}
										elseif($symbol == 4){ 
											$client_value_month_1 = $last_month1_value * $month_1;
											$client_value_month_2 = $last_month2_value * $month_2;
											$client_value_month_3 = $last_month3_value * $month_3;
											$client_value_month_4 = $last_month4_value * $month_4;
											$client_value_month_5 = $last_month5_value * $month_5; 
											$client_value_month_6 = $last_month6_value * $month_6;
											$client_value_month_7 = $last_month7_value * $month_7;
											$client_value_month_8 = $last_month8_value * $month_8;
											$client_value_month_9 = $last_month9_value * $month_9;
											$client_value_month_10 = $last_month10_value * $month_10;
											$client_value_month_11 = $last_month11_value * $month_11;
											$client_value_month_12 = $last_month12_value * $month_12;
											$client_value_month_13 = $last_month13_value * $month_13;
											$client_value_month_14 = $last_month14_value * $month_14;
										}
									}
								}
								else{
									$client_value_month_1 = 'Error';
									$client_value_month_2 = 'Error';
									$client_value_month_3 = 'Error';
									$client_value_month_4 = 'Error';
									$client_value_month_5 = 'Error';
									$client_value_month_6 = 'Error';
									$client_value_month_7 = 'Error';
									$client_value_month_8 = 'Error';
									$client_value_month_9 = 'Error';
									$client_value_month_10 = 'Error';
									$client_value_month_11 = 'Error';
									$client_value_month_12 = 'Error';
									$client_value_month_13 = 'Error';
									$client_value_month_14 = 'Error';
								}
								$symbol = $computation->computation;
								$last_month1_value = ($last_month1_value !== 'Error') ? $client_value_month_1 : 'Error';
								$last_month2_value = ($last_month2_value !== 'Error') ? $client_value_month_2 : 'Error';
								$last_month3_value = ($last_month3_value !== 'Error') ? $client_value_month_3 : 'Error';
								$last_month4_value = ($last_month4_value !== 'Error') ? $client_value_month_4 : 'Error';
								$last_month5_value = ($last_month5_value !== 'Error') ? $client_value_month_5 : 'Error';
								$last_month6_value = ($last_month6_value !== 'Error') ? $client_value_month_6 : 'Error';
								$last_month7_value = ($last_month7_value !== 'Error') ? $client_value_month_7 : 'Error';
								$last_month8_value = ($last_month8_value !== 'Error') ? $client_value_month_8 : 'Error';
								$last_month9_value = ($last_month9_value !== 'Error') ? $client_value_month_9 : 'Error';
								$last_month10_value = ($last_month10_value !== 'Error') ? $client_value_month_10 : 'Error';
								$last_month11_value = ($last_month11_value !== 'Error') ? $client_value_month_11 : 'Error';
								$last_month12_value = ($last_month12_value !== 'Error') ? $client_value_month_12 : 'Error';
								$last_month13_value = ($last_month13_value !== 'Error') ? $client_value_month_13 : 'Error';
									$last_month14_value = ($last_month14_value !== 'Error') ? $client_value_month_14 : 'Error';
							}
						}
						$datacomplex['project_id'] = $project_id;
						$datacomplex['project_type'] = $project_type;
						$datacomplex['client_id'] = $id;
						$datacomplex['month_1'] = $last_month1_value;
						$datacomplex['month_2'] = $last_month2_value;
						$datacomplex['month_3'] = $last_month3_value;
						$datacomplex['month_4'] = $last_month4_value;
						$datacomplex['month_5'] = $last_month5_value;
						$datacomplex['month_6'] = $last_month6_value;
						$datacomplex['month_7'] = $last_month7_value;
						$datacomplex['month_8'] = $last_month8_value;
						$datacomplex['month_9'] = $last_month9_value;
						$datacomplex['month_10'] = $last_month10_value;
						$datacomplex['month_11'] = $last_month11_value;
						$datacomplex['month_12'] = $last_month12_value;
						$datacomplex['month_13'] = $last_month13_value;
						$datacomplex['month_14'] = $last_month14_value;
						\App\Models\projectClients::insert($datacomplex);
					}
				}
			}
		}
		echo $project_type;
	}
	public function save_tracking_project_status(Request $request){
		$id = $request->get('id');
		$data['project_status'] = $request->get('status');
		\App\Models\projectClients::where('id',$id)->update($data);
	}
	public function save_tracking_project_date(Request $request){
		$id = $request->get('id');	
		$data['project_date'] = date('Y-m-d',strtotime($request->get('datevalue')));
		\App\Models\projectClients::where('id',$id)->update($data);
	}
	public function save_tracking_project_comment(Request $request){
		$id = $request->get('id');
		$data['comment'] = $request->get('comment');
		\App\Models\projectClients::where('id',$id)->update($data);
	}
	public function save_tracking_project_value(Request $request){
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $project_details->tracking_type;
		$key = $request->get('key');
		$id = $request->get('id');
		if($project_type == "4" || $project_type == "6"){
			$data['value'] = $request->get('value');
		}
		else{
			$data['month_'.$key] = $request->get('value');
		}
		\App\Models\projectClients::where('id',$id)->update($data);
	}
	public function get_computation_projects(Request $request) {
		$project_type = $request->get('project_type');
		$output = '<option value="">Select Project</option>';
		if($project_type == "6"){
			$types = ['4','6'];
		}
		else{
			$types = ['5','7'];
		}
		$get_projects = \App\Models\trackingProjects::whereIn('tracking_type',$types)->where('practice_code',Session::get('user_practice_code'))->get();
		if(($get_projects))
		{
			foreach($get_projects as $project){
				$output.='<option value="'.$project->id.'">'.$project->project_name.'</option>';
			}
		}
		echo $output;
	}
	public function create_project_tracking_project_computation(Request $request){
		$project_name 	= $request->get('project_name');
		$project_type 	= $request->get('project_type');
		$creation_date 	= $request->get('creation_date');
		$monthly_value 	= $request->get('monthly_value');
		$project_ids = explode(',',$request->get('project_ids'));
		$computations = explode(',',$request->get('computations'));
		$data['project_name'] = $project_name;
		$data['creation_date'] = date('Y-m-d', strtotime($creation_date));
		$data['tracking_type'] = $project_type;
		$data['practice_code'] = Session::get('user_practice_code');
		if($project_type == "7"){
			$data['monthly_value'] = $monthly_value;
		}
		$data['complex_value'] = $request->get('complex_value');
		$new_id = \App\Models\trackingProjects::insertDetails($data);
		if(($project_ids))
		{
			foreach($project_ids as $key => $project_id){
				$dataval['project_id'] = $new_id;
				$dataval['parent_project_id'] = $project_id;
				$dataval['computation'] = $computations[$key];
				\App\Models\projectComputations::insert($dataval);
			}
		}
		$project_type_details = \App\Models\trackingType::where('id',$project_type)->first();
		echo '<tr class="project_list_tr">
	        <td>'.$project_name.'</td>
	        <td>'.$project_type_details->tracking_type.'</td>
	        <td>'.date('d-M-Y', strtotime($creation_date)).'</td>
	      </tr>';
	}
	public function load_project_tracking_clients_complex(Request $request){
		$project_id = $request->get('project_id');
		$proj_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $proj_details->tracking_type;
		$complex_value = $proj_details->complex_value;
		$status = $request->get('status');
		if($status == "yes"){
			$project_clients = \App\Models\projectClients::where('project_id',$project_id)->get();
			$project_computations = \App\Models\projectComputations::where('project_id',$project_id)->get();
			if($project_type == "6"){
				if(($project_clients)){
					foreach($project_clients as $project_client) {
						$symbol = 0;
						$last_value = 0;
						if(($project_computations)) {
							foreach($project_computations as $computation){
								if($last_value !== 'Error'){
									$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$project_client->client_id)->first();
									if(($prev_proj)) {
										if(trim($prev_proj->value) == "") { $comment = 0; }
										else { $comment = $prev_proj->value; }
										if($symbol == 0){
											$client_value = $comment;
										}else{
											if($symbol == 1){ $client_value = $last_value + $comment; }
											elseif($symbol == 2){ $client_value = $last_value - $comment; }
											elseif($symbol == 3){ $client_value = $last_value / $comment; }
											elseif($symbol == 4){ $client_value = $last_value * $comment; }
										}
									}
									else{
										$client_value = 'Error';
									}
									$symbol = $computation->computation;
									$last_value = $client_value;
								}
								else{
									$symbol = $computation->computation;
									$last_value = 'Error';
								}
							}
						}
						$datacomplex['value'] = $last_value;
						\App\Models\projectClients::where('id',$project_client->id)->update($datacomplex);
					}
				}
			}
			elseif($project_type == "7"){
				if(($project_clients)){
					foreach($project_clients as $project_client) {
						$symbol = 0;
						$last_month1_value = 0;
						$last_month2_value = 0;
						$last_month3_value = 0;
						$last_month4_value = 0;
						$last_month5_value = 0;
						$last_month6_value = 0;
						$last_month7_value = 0;
						$last_month8_value = 0;
						$last_month9_value = 0;
						$last_month10_value = 0;
						$last_month11_value = 0;
						$last_month12_value = 0;
						$last_month13_value = 0;
						$last_month14_value = 0;
						if(($project_computations)) {
							foreach($project_computations as $computation){
								if($last_month1_value === "Error"){ $last_month1_value = 'Error'; }
								if($last_month2_value === "Error"){ $last_month2_value = 'Error'; }
								if($last_month3_value === "Error"){ $last_month3_value = 'Error'; }
								if($last_month4_value === "Error"){ $last_month4_value = 'Error'; }
								if($last_month5_value === "Error"){ $last_month5_value = 'Error'; }
								if($last_month6_value === "Error"){ $last_month6_value = 'Error'; }
								if($last_month7_value === "Error"){ $last_month7_value = 'Error'; }
								if($last_month8_value === "Error"){ $last_month8_value = 'Error'; }
								if($last_month9_value === "Error"){ $last_month9_value = 'Error'; }
								if($last_month10_value === "Error"){ $last_month10_value = 'Error'; }
								if($last_month11_value === "Error"){ $last_month11_value = 'Error'; }
								if($last_month12_value === "Error"){ $last_month12_value = 'Error'; }
								if($last_month13_value === "Error"){ $last_month13_value = 'Error'; }
								if($last_month14_value === "Error"){ $last_month14_value = 'Error'; }
								$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$project_client->client_id)->first();
								if(($prev_proj)) {
									$month_1 = $prev_proj->month_1;
									$month_2 = $prev_proj->month_2;
									$month_3 = $prev_proj->month_3;
									$month_4 = $prev_proj->month_4;
									$month_5 = $prev_proj->month_5;
									$month_6 = $prev_proj->month_6;
									$month_7 = $prev_proj->month_7;
									$month_8 = $prev_proj->month_8;
									$month_9 = $prev_proj->month_9;
									$month_10 = $prev_proj->month_10;
									$month_11 = $prev_proj->month_11;
									$month_12 = $prev_proj->month_12;
									$month_13 = $prev_proj->month_13;
									$month_14 = $prev_proj->month_14;
									if(trim($prev_proj->month_1) == "") { $month_1 = 0; }
									if(trim($prev_proj->month_2) == "") { $month_2 = 0; }
									if(trim($prev_proj->month_3) == "") { $month_3 = 0; }
									if(trim($prev_proj->month_4) == "") { $month_4 = 0; }
									if(trim($prev_proj->month_5) == "") { $month_5 = 0; }
									if(trim($prev_proj->month_6) == "") { $month_6 = 0; }
									if(trim($prev_proj->month_7) == "") { $month_7 = 0; }
									if(trim($prev_proj->month_8) == "") { $month_8 = 0; }
									if(trim($prev_proj->month_9) == "") { $month_9 = 0; }
									if(trim($prev_proj->month_10) == "") { $month_10 = 0; }
									if(trim($prev_proj->month_11) == "") { $month_11 = 0; }
									if(trim($prev_proj->month_12) == "") { $month_12 = 0; }
									if(trim($prev_proj->month_13) == "") { $month_13 = 0; }
									if(trim($prev_proj->month_14) == "") { $month_14 = 0; }
									if($symbol == 0){
										$client_value_month_1 = $month_1;
										$client_value_month_2 = $month_2;
										$client_value_month_3 = $month_3;
										$client_value_month_4 = $month_4;
										$client_value_month_5 = $month_5;
										$client_value_month_6 = $month_6;
										$client_value_month_7 = $month_7;
										$client_value_month_8 = $month_8;
										$client_value_month_9 = $month_9;
										$client_value_month_10 = $month_10;
										$client_value_month_11 = $month_11;
										$client_value_month_12 = $month_12;
										$client_value_month_13 = $month_13;
										$client_value_month_14 = $month_14;
									}else{
										if($symbol == 1){ 
											$client_value_month_1 = $last_month1_value + $month_1;
											$client_value_month_2 = $last_month2_value + $month_2;
											$client_value_month_3 = $last_month3_value + $month_3;
											$client_value_month_4 = $last_month4_value + $month_4;
											$client_value_month_5 = $last_month5_value + $month_5; 
											$client_value_month_6 = $last_month6_value + $month_6;
											$client_value_month_7 = $last_month7_value + $month_7;
											$client_value_month_8 = $last_month8_value + $month_8;
											$client_value_month_9 = $last_month9_value + $month_9;
											$client_value_month_10 = $last_month10_value + $month_10;
											$client_value_month_11 = $last_month11_value + $month_11;
											$client_value_month_12 = $last_month12_value + $month_12;
											$client_value_month_13 = $last_month13_value + $month_13;
											$client_value_month_14 = $last_month14_value + $month_14;
										}
										elseif($symbol == 2){ 
											$client_value_month_1 = $last_month1_value - $month_1;
											$client_value_month_2 = $last_month2_value - $month_2;
											$client_value_month_3 = $last_month3_value - $month_3;
											$client_value_month_4 = $last_month4_value - $month_4;
											$client_value_month_5 = $last_month5_value - $month_5; 
											$client_value_month_6 = $last_month6_value - $month_6;
											$client_value_month_7 = $last_month7_value - $month_7;
											$client_value_month_8 = $last_month8_value - $month_8;
											$client_value_month_9 = $last_month9_value - $month_9;
											$client_value_month_10 = $last_month10_value - $month_10;
											$client_value_month_11 = $last_month11_value - $month_11;
											$client_value_month_12 = $last_month12_value - $month_12;
											$client_value_month_13 = $last_month13_value - $month_13;
											$client_value_month_14 = $last_month14_value - $month_14;
										}
										elseif($symbol == 3){ 
											$client_value_month_1 = $last_month1_value / $month_1;
											$client_value_month_2 = $last_month2_value / $month_2;
											$client_value_month_3 = $last_month3_value / $month_3;
											$client_value_month_4 = $last_month4_value / $month_4;
											$client_value_month_5 = $last_month5_value / $month_5; 
											$client_value_month_6 = $last_month6_value / $month_6;
											$client_value_month_7 = $last_month7_value / $month_7;
											$client_value_month_8 = $last_month8_value / $month_8;
											$client_value_month_9 = $last_month9_value / $month_9;
											$client_value_month_10 = $last_month10_value / $month_10;
											$client_value_month_11 = $last_month11_value / $month_11;
											$client_value_month_12 = $last_month12_value / $month_12;
											$client_value_month_13 = $last_month13_value / $month_13;
											$client_value_month_14 = $last_month14_value / $month_14;
										}
										elseif($symbol == 4){ 
											$client_value_month_1 = $last_month1_value * $month_1;
											$client_value_month_2 = $last_month2_value * $month_2;
											$client_value_month_3 = $last_month3_value * $month_3;
											$client_value_month_4 = $last_month4_value * $month_4;
											$client_value_month_5 = $last_month5_value * $month_5; 
											$client_value_month_6 = $last_month6_value * $month_6;
											$client_value_month_7 = $last_month7_value * $month_7;
											$client_value_month_8 = $last_month8_value * $month_8;
											$client_value_month_9 = $last_month9_value * $month_9;
											$client_value_month_10 = $last_month10_value * $month_10;
											$client_value_month_11 = $last_month11_value * $month_11;
											$client_value_month_12 = $last_month12_value * $month_12;
											$client_value_month_13 = $last_month13_value * $month_13;
											$client_value_month_14 = $last_month14_value * $month_14;
										}
									}
								}
								else{
									$client_value_month_1 = 'Error';
									$client_value_month_2 = 'Error';
									$client_value_month_3 = 'Error';
									$client_value_month_4 = 'Error';
									$client_value_month_5 = 'Error';
									$client_value_month_6 = 'Error';
									$client_value_month_7 = 'Error';
									$client_value_month_8 = 'Error';
									$client_value_month_9 = 'Error';
									$client_value_month_10 = 'Error';
									$client_value_month_11 = 'Error';
									$client_value_month_12 = 'Error';
									$client_value_month_13 = 'Error';
									$client_value_month_14 = 'Error';
								}
								$symbol = $computation->computation;
								$last_month1_value = ($last_month1_value !== 'Error') ? $client_value_month_1 : 'Error';
								$last_month2_value = ($last_month2_value !== 'Error') ? $client_value_month_2 : 'Error';
								$last_month3_value = ($last_month3_value !== 'Error') ? $client_value_month_3 : 'Error';
								$last_month4_value = ($last_month4_value !== 'Error') ? $client_value_month_4 : 'Error';
								$last_month5_value = ($last_month5_value !== 'Error') ? $client_value_month_5 : 'Error';
								$last_month6_value = ($last_month6_value !== 'Error') ? $client_value_month_6 : 'Error';
								$last_month7_value = ($last_month7_value !== 'Error') ? $client_value_month_7 : 'Error';
								$last_month8_value = ($last_month8_value !== 'Error') ? $client_value_month_8 : 'Error';
								$last_month9_value = ($last_month9_value !== 'Error') ? $client_value_month_9 : 'Error';
								$last_month10_value = ($last_month10_value !== 'Error') ? $client_value_month_10 : 'Error';
								$last_month11_value = ($last_month11_value !== 'Error') ? $client_value_month_11 : 'Error';
								$last_month12_value = ($last_month12_value !== 'Error') ? $client_value_month_12 : 'Error';
								$last_month13_value = ($last_month13_value !== 'Error') ? $client_value_month_13 : 'Error';
									$last_month14_value = ($last_month14_value !== 'Error') ? $client_value_month_14 : 'Error';
							}
						}
						$datacomplex['month_1'] = $last_month1_value;
						$datacomplex['month_2'] = $last_month2_value;
						$datacomplex['month_3'] = $last_month3_value;
						$datacomplex['month_4'] = $last_month4_value;
						$datacomplex['month_5'] = $last_month5_value;
						$datacomplex['month_6'] = $last_month6_value;
						$datacomplex['month_7'] = $last_month7_value;
						$datacomplex['month_8'] = $last_month8_value;
						$datacomplex['month_9'] = $last_month9_value;
						$datacomplex['month_10'] = $last_month10_value;
						$datacomplex['month_11'] = $last_month11_value;
						$datacomplex['month_12'] = $last_month12_value;
						$datacomplex['month_13'] = $last_month13_value;
						$datacomplex['month_14'] = $last_month14_value;
						\App\Models\projectClients::where('id',$project_client->id)->update($datacomplex);
					}
				}
			}
		}
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $project_details->tracking_type;
		$project_clients = \App\Models\projectClients::where('project_id',$project_id)->get();
		if($project_type == 6){
			$thead = '<th>Value</th>';
			$thead_count = '3';
			$table_width = '30%';
		}
		elseif($project_type == 7){
			$selected_year = $project_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$thead = '<th>Dec - '.$previous_year.'</th>
			<th>Jan-'.$selected_year.'</th>
			<th>Feb-'.$selected_year.'</th>
			<th>Mar-'.$selected_year.'</th>
			<th>Apr-'.$selected_year.'</th>
			<th>May-'.$selected_year.'</th>
			<th>Jun-'.$selected_year.'</th>
			<th>Jul-'.$selected_year.'</th>
			<th>Aug-'.$selected_year.'</th>
			<th>Sep-'.$selected_year.'</th>
			<th>Oct-'.$selected_year.'</th>
			<th>Nov-'.$selected_year.'</th>
			<th>Dec-'.$selected_year.'</th>
			<th>Jan-'.$future_year.'</th>';
			$table_width = '100%';
			$thead_count = '16';
		}
		else{
			$thead = '';
		}
		$output = '<table class="table loadData2" style="margin-top:20px;width:'.$table_width.'">
		<thead>
			<th style="width: 88px;">Client ID</th>
			<th style="width: 386px;">Client Name</th>
			'.$thead.'
		</thead>
		<tbody>';
		if(($project_clients))
		{
			foreach($project_clients as $client)
			{
				if($client->project_status == 1) { $yesno_checked = 'checked'; } else { $yesno_checked = ''; }
				if($client->project_date != "0000-00-00 00:00:00"){
					$project_date = date('d-M-Y',strtotime($client->project_date));
				}
				else{
					$project_date = '';
				}
				if($project_type == 6){
					$tbody = '<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->value.'" data-value="1"></td>';
				}
				elseif($project_type == 7){
					$selected_year = $project_details->monthly_value;
					$previous_year = $selected_year - 1;
					$future_year = $selected_year + 1;
					$tbody = '
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_1.'" data-value="1"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_2.'" data-value="2"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_3.'" data-value="3"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_4.'" data-value="4"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_5.'" data-value="5"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_6.'" data-value="6"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_7.'" data-value="7"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_8.'" data-value="8"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_9.'" data-value="9"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_10.'" data-value="10"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_11.'" data-value="11"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_12.'" data-value="12"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_13.'" data-value="13"></td>
					<td><input type="text" name="project_value_'.$client->id.'" class="form-control project_value project_value_'.$client->id.'" data-element="'.$client->id.'" value="'.$client->month_14.'" data-value="14"></td>';
				}
				else{
					$tbody = '';
				}
				$cm_clients = \App\Models\CMClients::where('client_id',$client->client_id)->first();
				$output.='<tr>
					<td style="vertical-align:middle"><a href="javascript:" class="show_computation_icon_client" title="Complex Project Construction" data-element="'.$client->client_id.'">'.$client->client_id.'</a></td>
					<td style="vertical-align:middle"><a href="javascript:" class="show_computation_icon_client" title="Complex Project Construction" data-element="'.$client->client_id.'">'.$cm_clients->company.'</a></td>
					'.$tbody.'
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="'.$thead_count.'">No Clients Added to this Project</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function save_tracking_project_comment_monthly(Request $request){
		$id = $request->get('id');
		$comment = explode(',',$request->get('comment'));
		if(($comment)){
			$data['comment'] = serialize($comment);
		}
		\App\Models\projectClients::where('id',$id)->update($data);
	}
	public function show_complex_project_construction(Request $request){
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$tracking_type = \App\Models\trackingType::where('id',$project_details->tracking_type)->first();
		$computations = \App\Models\projectComputations::where('project_id',$project_id)->get();
		$output = '<div class="col-lg-12 padding_00">
	                <div>
	                  <div class="col-md-12 padding_00">
	                    <div class="col-md-3 padding_00">
	                        <label>Tracking Project:</label>
	                    </div>
	                    <div class="col-md-6 padding_00">
	                        '.$project_details->project_name.'
	                    </div>
	                  </div>
	                  <div class="col-md-12 padding_00" style="margin-top:20px">
	                    <div class="col-md-3 padding_00">
	                        <label>Project Type:</label>
	                    </div>
	                    <div class="col-md-6 padding_00">
	                        '.$tracking_type->tracking_type.'
	                    </div>
	                  </div>
	                </div>
	            </div>';
		if(($computations))
		{
			foreach($computations as $computation){
				$parent_project_details = \App\Models\trackingProjects::where('id',$computation->parent_project_id)->first();
				$output.='<div class="col-lg-12" style="margin-top:25px;background:#dfdfdf;padding:10px">
	                <div>
	                  <div class="col-md-12 padding_00">
	                    <div class="col-md-3 padding_00">
	                        <label>Project Name:</label>
	                    </div>
	                    <div class="col-md-6 padding_00">
	                        '.$parent_project_details->project_name.'
	                    </div>
	                  </div>
	                  <div class="col-md-12 padding_00" style="margin-top:20px">
	                    <div class="col-md-3 padding_00">
	                        <label>Computation:</label>
	                    </div>
	                    <div class="col-md-6 padding_00">';
	                        if($computation->computation == 1){ $output.='+ (Plus)'; }
	                        elseif($computation->computation == 2){ $output.='- (Minus)'; }
	                        elseif($computation->computation == 3){ $output.='/ (Division)'; }
	                        elseif($computation->computation == 4){ $output.='* (Multiplication)'; }
	                    $output.='</div>
	                  </div>
	                </div>
	            </div>';
			}
		} 
        $output.='<div class="col-lg-12" style="margin-top:25px;">
          <div class="col-md-3 padding_00">
            <label>Value:</label>
          </div>
          <div class="col-md-6 padding_00">
            <div class="form-group">
              '.$project_details->complex_value.'
            </div>
          </div>
        </div>';
        echo $output;
	}
	public function show_complex_project_construction_client(Request $request){
		$client_id = $request->get('client_id');
		$client_details = \App\Models\CMClients::where('client_id',$client_id)->first();
		$project_id = $request->get('project_id');
		$project_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$computations = \App\Models\projectComputations::where('project_id',$project_id)->get();
		$output = '<div class="col-md-12 padding_00">
			<div class="col-md-2">
				<label style="margin-top: 8px;">Client ID:</label>
			</div>
			<div class="col-md-2" style="border: 1px solid #d7d6d6;padding: 6px;background: #dfdfdf;">
				'.$client_id.'
			</div>
			<div class="col-md-2">
				<label style="margin-top: 8px;">Client Name:</label>
			</div>
			<div class="col-md-6" style="border: 1px solid #d7d6d6;padding: 6px;background: #dfdfdf;">
				'.$client_details->company.'
			</div>
		</div>
		<div class="col-md-12 padding_00" style="margin-top:20px">
			<div class="col-md-3">
				<label style="margin-top: 8px;">Tracking Project:</label>
			</div>
			<div class="col-md-9" style="border: 1px solid #d7d6d6;padding: 6px;background: #dfdfdf;">
				'.$project_details->project_name.'
			</div>
		</div>
		<div class="col-md-12 padding_00" style="margin-top:20px">
			<div class="col-md-3">
				<label style="margin-top: 8px;">Rule Analysis:</label>
			</div>
			<div class="col-md-9" style="border: 1px solid #d7d6d6;padding: 6px;background: #dfdfdf;">';
				$dependencies = '';
				if(($computations))
				{
					foreach($computations as $computation){
						$parent_project_details = \App\Models\trackingProjects::where('id',$computation->parent_project_id)->first();
						$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$client_id)->first();
						$output.=$parent_project_details->project_name;
                        if($computation->computation == 1){ $output.=' + '; }
                        elseif($computation->computation == 2){ $output.=' - '; }
                        elseif($computation->computation == 3){ $output.=' / '; }
                        elseif($computation->computation == 4){ $output.=' * '; }
                        if($project_details->tracking_type == 6){
                        	if(($prev_proj)){
                        		if($prev_proj->value != ""){
                        			$prev_proj_value = $prev_proj->value;
                        		}
                        		else{
                        			$prev_proj_value = '0';
                        		}
                        	}else{
                        		$prev_proj_value = 'Error';
                        	}
                        	$dependencies.='<p>----'.$parent_project_details->project_name.' ('.$prev_proj_value.')</p>';
                        }else{
                        	$dependencies.='<p>----'.$parent_project_details->project_name.'</p>';
                        }
					}
					$output.=$project_details->complex_value;
				} 
			$output.='</div>
		</div>
		<div class="col-lg-12" style="margin-top:25px;">
          <div class="col-md-12 padding_00">
            <label>Analysis of Project & Dependencies: &nbsp;&nbsp;&nbsp;<a href="javascript:" class="fa fa-refresh refresh_project_analysis" data-element="'.$project_id.'" data-client="'.$client_id.'" style="font-size:20px;" title="Update Complex Project Client Dependancy Analysis"></a></label>
          </div>
          <div class="col-md-12 padding_00">
          	<h4>Tracking Project Name</h4>
            <div class="form-group">
              '.$dependencies.'
            </div>
          </div>
        </div>';
        echo $output;
	}
	public function update_complex_project_client_dependency(Request $request){
		$project_id = $request->get('project_id');
		$client_id = $request->get('client_id');
		$proj_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $proj_details->tracking_type;
		$complex_value = $proj_details->complex_value;
		$project_computations = \App\Models\projectComputations::where('project_id',$project_id)->get();
		if($project_type == "6"){
			$symbol = 0;
			$last_value = 0;
			if(($project_computations)) {
				foreach($project_computations as $computation){
					if($last_value !== 'Error'){
						$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$client_id)->first();
						if(($prev_proj)) {
							if(trim($prev_proj->value) == "") { $comment = 0; }
							else { $comment = $prev_proj->value; }
							if($symbol == 0){
								$client_value = $comment;
							}else{
								if($symbol == 1){ $client_value = $last_value + $comment; }
								elseif($symbol == 2){ $client_value = $last_value - $comment; }
								elseif($symbol == 3){ $client_value = $last_value / $comment; }
								elseif($symbol == 4){ $client_value = $last_value * $comment; }
							}
						}
						else{
							$client_value = 'Error';
						}
						$symbol = $computation->computation;
						$last_value = $client_value;
					}
					else{
						$symbol = $computation->computation;
						$last_value = 'Error';
					}
				}
			}
			$datacomplex['value'] = $last_value;
			\App\Models\projectClients::where('client_id',$client_id)->where('project_id',$project_id)->update($datacomplex);
			$project_client_id = \App\Models\projectClients::where('client_id',$client_id)->where('project_id',$project_id)->first();
			$datacomplexvalue['project_client_id'] = $project_client_id->id;
			$datacomplexvalue['project_type'] = $project_type;
			$datacomplexvalue['value'] = $last_value;
			echo json_encode($datacomplexvalue);
		}
		elseif($project_type == "7"){
			$symbol = 0;
			$last_month1_value = 0;
			$last_month2_value = 0;
			$last_month3_value = 0;
			$last_month4_value = 0;
			$last_month5_value = 0;
			$last_month6_value = 0;
			$last_month7_value = 0;
			$last_month8_value = 0;
			$last_month9_value = 0;
			$last_month10_value = 0;
			$last_month11_value = 0;
			$last_month12_value = 0;
			$last_month13_value = 0;
			$last_month14_value = 0;
			if(($project_computations)) {
				foreach($project_computations as $keyv => $computation){
					if($last_month1_value === "Error"){ $last_month1_value = 'Error'; }
					if($last_month2_value === "Error"){ $last_month2_value = 'Error'; }
					if($last_month3_value === "Error"){ $last_month3_value = 'Error'; }
					if($last_month4_value === "Error"){ $last_month4_value = 'Error'; }
					if($last_month5_value === "Error"){ $last_month5_value = 'Error'; }
					if($last_month6_value === "Error"){ $last_month6_value = 'Error'; }
					if($last_month7_value === "Error"){ $last_month7_value = 'Error'; }
					if($last_month8_value === "Error"){ $last_month8_value = 'Error'; }
					if($last_month9_value === "Error"){ $last_month9_value = 'Error'; }
					if($last_month10_value === "Error"){ $last_month10_value = 'Error'; }
					if($last_month11_value === "Error"){ $last_month11_value = 'Error'; }
					if($last_month12_value === "Error"){ $last_month12_value = 'Error'; }
					if($last_month13_value === "Error"){ $last_month13_value = 'Error'; }
					if($last_month14_value === "Error"){ $last_month14_value = 'Error'; }
					$prev_proj = \App\Models\projectClients::where('project_id',$computation->parent_project_id)->where('client_id',$client_id)->first();
					if(($prev_proj)) {
						$month_1 = $prev_proj->month_1;
						$month_2 = $prev_proj->month_2;
						$month_3 = $prev_proj->month_3;
						$month_4 = $prev_proj->month_4;
						$month_5 = $prev_proj->month_5;
						$month_6 = $prev_proj->month_6;
						$month_7 = $prev_proj->month_7;
						$month_8 = $prev_proj->month_8;
						$month_9 = $prev_proj->month_9;
						$month_10 = $prev_proj->month_10;
						$month_11 = $prev_proj->month_11;
						$month_12 = $prev_proj->month_12;
						$month_13 = $prev_proj->month_13;
						$month_14 = $prev_proj->month_14;
						if(trim($prev_proj->month_1) == "") { $month_1 = 0; }
						if(trim($prev_proj->month_2) == "") { $month_2 = 0; }
						if(trim($prev_proj->month_3) == "") { $month_3 = 0; }
						if(trim($prev_proj->month_4) == "") { $month_4 = 0; }
						if(trim($prev_proj->month_5) == "") { $month_5 = 0; }
						if(trim($prev_proj->month_6) == "") { $month_6 = 0; }
						if(trim($prev_proj->month_7) == "") { $month_7 = 0; }
						if(trim($prev_proj->month_8) == "") { $month_8 = 0; }
						if(trim($prev_proj->month_9) == "") { $month_9 = 0; }
						if(trim($prev_proj->month_10) == "") { $month_10 = 0; }
						if(trim($prev_proj->month_11) == "") { $month_11 = 0; }
						if(trim($prev_proj->month_12) == "") { $month_12 = 0; }
						if(trim($prev_proj->month_13) == "") { $month_13 = 0; }
						if(trim($prev_proj->month_14) == "") { $month_14 = 0; }
						if($symbol == 0){
							$client_value_month_1 = $month_1;
							$client_value_month_2 = $month_2;
							$client_value_month_3 = $month_3;
							$client_value_month_4 = $month_4;
							$client_value_month_5 = $month_5;
							$client_value_month_6 = $month_6;
							$client_value_month_7 = $month_7;
							$client_value_month_8 = $month_8;
							$client_value_month_9 = $month_9;
							$client_value_month_10 = $month_10;
							$client_value_month_11 = $month_11;
							$client_value_month_12 = $month_12;
							$client_value_month_13 = $month_13;
							$client_value_month_14 = $month_14;
						}else{
							if($symbol == 1){ 
								$client_value_month_1 = $last_month1_value + $month_1;
								$client_value_month_2 = $last_month2_value + $month_2;
								$client_value_month_3 = $last_month3_value + $month_3;
								$client_value_month_4 = $last_month4_value + $month_4;
								$client_value_month_5 = $last_month5_value + $month_5; 
								$client_value_month_6 = $last_month6_value + $month_6;
								$client_value_month_7 = $last_month7_value + $month_7;
								$client_value_month_8 = $last_month8_value + $month_8;
								$client_value_month_9 = $last_month9_value + $month_9;
								$client_value_month_10 = $last_month10_value + $month_10;
								$client_value_month_11 = $last_month11_value + $month_11;
								$client_value_month_12 = $last_month12_value + $month_12;
								$client_value_month_13 = $last_month13_value + $month_13;
								$client_value_month_14 = $last_month14_value + $month_14;
							}
							elseif($symbol == 2){ 
								$client_value_month_1 = $last_month1_value - $month_1;
								$client_value_month_2 = $last_month2_value - $month_2;
								$client_value_month_3 = $last_month3_value - $month_3;
								$client_value_month_4 = $last_month4_value - $month_4;
								$client_value_month_5 = $last_month5_value - $month_5; 
								$client_value_month_6 = $last_month6_value - $month_6;
								$client_value_month_7 = $last_month7_value - $month_7;
								$client_value_month_8 = $last_month8_value - $month_8;
								$client_value_month_9 = $last_month9_value - $month_9;
								$client_value_month_10 = $last_month10_value - $month_10;
								$client_value_month_11 = $last_month11_value - $month_11;
								$client_value_month_12 = $last_month12_value - $month_12;
								$client_value_month_13 = $last_month13_value - $month_13;
								$client_value_month_14 = $last_month14_value - $month_14;
							}
							elseif($symbol == 3){ 
								$client_value_month_1 = $last_month1_value / $month_1;
								$client_value_month_2 = $last_month2_value / $month_2;
								$client_value_month_3 = $last_month3_value / $month_3;
								$client_value_month_4 = $last_month4_value / $month_4;
								$client_value_month_5 = $last_month5_value / $month_5; 
								$client_value_month_6 = $last_month6_value / $month_6;
								$client_value_month_7 = $last_month7_value / $month_7;
								$client_value_month_8 = $last_month8_value / $month_8;
								$client_value_month_9 = $last_month9_value / $month_9;
								$client_value_month_10 = $last_month10_value / $month_10;
								$client_value_month_11 = $last_month11_value / $month_11;
								$client_value_month_12 = $last_month12_value / $month_12;
								$client_value_month_13 = $last_month13_value / $month_13;
								$client_value_month_14 = $last_month14_value / $month_14;
							}
							elseif($symbol == 4){ 
								$client_value_month_1 = $last_month1_value * $month_1;
								$client_value_month_2 = $last_month2_value * $month_2;
								$client_value_month_3 = $last_month3_value * $month_3;
								$client_value_month_4 = $last_month4_value * $month_4;
								$client_value_month_5 = $last_month5_value * $month_5; 
								$client_value_month_6 = $last_month6_value * $month_6;
								$client_value_month_7 = $last_month7_value * $month_7;
								$client_value_month_8 = $last_month8_value * $month_8;
								$client_value_month_9 = $last_month9_value * $month_9;
								$client_value_month_10 = $last_month10_value * $month_10;
								$client_value_month_11 = $last_month11_value * $month_11;
								$client_value_month_12 = $last_month12_value * $month_12;
								$client_value_month_13 = $last_month13_value * $month_13;
								$client_value_month_14 = $last_month14_value * $month_14;
							}
						}
					}
					else{
						$client_value_month_1 = 'Error';
						$client_value_month_2 = 'Error';
						$client_value_month_3 = 'Error';
						$client_value_month_4 = 'Error';
						$client_value_month_5 = 'Error';
						$client_value_month_6 = 'Error';
						$client_value_month_7 = 'Error';
						$client_value_month_8 = 'Error';
						$client_value_month_9 = 'Error';
						$client_value_month_10 = 'Error';
						$client_value_month_11 = 'Error';
						$client_value_month_12 = 'Error';
						$client_value_month_13 = 'Error';
						$client_value_month_14 = 'Error';
					}
					$symbol = $computation->computation;
					$last_month1_value = ($last_month1_value !== 'Error') ? $client_value_month_1 : 'Error';
					$last_month2_value = ($last_month2_value !== 'Error') ? $client_value_month_2 : 'Error';
					$last_month3_value = ($last_month3_value !== 'Error') ? $client_value_month_3 : 'Error';
					$last_month4_value = ($last_month4_value !== 'Error') ? $client_value_month_4 : 'Error';
					$last_month5_value = ($last_month5_value !== 'Error') ? $client_value_month_5 : 'Error';
					$last_month6_value = ($last_month6_value !== 'Error') ? $client_value_month_6 : 'Error';
					$last_month7_value = ($last_month7_value !== 'Error') ? $client_value_month_7 : 'Error';
					$last_month8_value = ($last_month8_value !== 'Error') ? $client_value_month_8 : 'Error';
					$last_month9_value = ($last_month9_value !== 'Error') ? $client_value_month_9 : 'Error';
					$last_month10_value = ($last_month10_value !== 'Error') ? $client_value_month_10 : 'Error';
					$last_month11_value = ($last_month11_value !== 'Error') ? $client_value_month_11 : 'Error';
					$last_month12_value = ($last_month12_value !== 'Error') ? $client_value_month_12 : 'Error';
					$last_month13_value = ($last_month13_value !== 'Error') ? $client_value_month_13 : 'Error';
						$last_month14_value = ($last_month14_value !== 'Error') ? $client_value_month_14 : 'Error';
				}
			}
			$datacomplex['month_1'] = $last_month1_value;
			$datacomplex['month_2'] = $last_month2_value;
			$datacomplex['month_3'] = $last_month3_value;
			$datacomplex['month_4'] = $last_month4_value;
			$datacomplex['month_5'] = $last_month5_value;
			$datacomplex['month_6'] = $last_month6_value;
			$datacomplex['month_7'] = $last_month7_value;
			$datacomplex['month_8'] = $last_month8_value;
			$datacomplex['month_9'] = $last_month9_value;
			$datacomplex['month_10'] = $last_month10_value;
			$datacomplex['month_11'] = $last_month11_value;
			$datacomplex['month_12'] = $last_month12_value;
			$datacomplex['month_13'] = $last_month13_value;
			$datacomplex['month_14'] = $last_month14_value;
			\App\Models\projectClients::where('client_id',$client_id)->where('project_id',$project_id)->update($datacomplex);
			$project_client_id = \App\Models\projectClients::where('client_id',$client_id)->where('project_id',$project_id)->first();
			$datacomplexvalue['project_client_id'] = $project_client_id->id;
			$datacomplexvalue['project_type'] = $project_type;
			$datacomplexvalue['month_1'] = $last_month1_value;
			$datacomplexvalue['month_2'] = $last_month2_value;
			$datacomplexvalue['month_3'] = $last_month3_value;
			$datacomplexvalue['month_4'] = $last_month4_value;
			$datacomplexvalue['month_5'] = $last_month5_value;
			$datacomplexvalue['month_6'] = $last_month6_value;
			$datacomplexvalue['month_7'] = $last_month7_value;
			$datacomplexvalue['month_8'] = $last_month8_value;
			$datacomplexvalue['month_9'] = $last_month9_value;
			$datacomplexvalue['month_10'] = $last_month10_value;
			$datacomplexvalue['month_11'] = $last_month11_value;
			$datacomplexvalue['month_12'] = $last_month12_value;
			$datacomplexvalue['month_13'] = $last_month13_value;
			$datacomplexvalue['month_14'] = $last_month14_value;
			echo json_encode($datacomplexvalue);
		}
	}
	public function import_client_project_tracking(Request $request){
		$project_id = $request->get('project_id');
		$proj_details = \App\Models\trackingProjects::where('id',$project_id)->first();
		$project_type = $proj_details->tracking_type;
		$output = '<form id="import_project_form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="'.csrf_token().'" />
		<h4>Please Note that the csv file you choose to import should be with the following Headers.</h4>';
		if($project_type == "1"){
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li" style="width:32% !important"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Confirmation Boolean</label></li>
					<li class="import_li"><input type="checkbox" name="column_4" class="column_4 select_column_tracking" id="column_4" data-element="4"><label for="column_4">Comment</label></li>
			</li>';
		}
		elseif($project_type == "2"){
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li" style="width:32% !important"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Confirmation Boolean</label></li>
					<li class="import_li"><input type="checkbox" name="column_4" class="column_4 select_column_tracking" id="column_4" data-element="4"><label for="column_4">Date</label></li>
			</li>';
		}
		elseif($project_type == "3"){
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li" style="width:32% !important"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Confirmation Boolean</label></li>
					<li class="import_li"><input type="checkbox" name="column_4" class="column_4 select_column_tracking" id="column_4" data-element="4"><label for="column_4">Comment</label></li>
					<li class="import_li"><input type="checkbox" name="column_5" class="column_5 select_column_tracking" id="column_5" data-element="5"><label for="column_5">Date</label></li>
			</li>';
		}
		elseif($project_type == "4"){
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Value</label></li>
			</li>';
		}
		elseif($project_type == "5"){
			$selected_year = $proj_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Dec</label></li>
					<li class="import_li"><input type="checkbox" name="column_4" class="column_4 select_column_tracking" id="column_4" data-element="4"><label for="column_4">Jan</label></li>
					<li class="import_li"><input type="checkbox" name="column_5" class="column_5 select_column_tracking" id="column_5" data-element="5"><label for="column_5">Feb</label></li>
					<li class="import_li"><input type="checkbox" name="column_6" class="column_6 select_column_tracking" id="column_6" data-element="6"><label for="column_6">Mar</label></li>
					<li class="import_li"><input type="checkbox" name="column_7" class="column_7 select_column_tracking" id="column_7" data-element="7"><label for="column_7">Apr</label></li>
					<li class="import_li"><input type="checkbox" name="column_8" class="column_8 select_column_tracking" id="column_8" data-element="8"><label for="column_8">May</label></li>
					<li class="import_li"><input type="checkbox" name="column_9" class="column_9 select_column_tracking" id="column_9" data-element="9"><label for="column_9">Jun</label></li>
					<li class="import_li"><input type="checkbox" name="column_10" class="column_10 select_column_tracking" id="column_10" data-element="10"><label for="column_10">Jul</label></li>
					<li class="import_li"><input type="checkbox" name="column_11" class="column_11 select_column_tracking" id="column_11" data-element="11"><label for="column_11">Aug</label></li>
					<li class="import_li"><input type="checkbox" name="column_12" class="column_12 select_column_tracking" id="column_12" data-element="12"><label for="column_12">Sep</label></li>
					<li class="import_li"><input type="checkbox" name="column_13" class="column_13 select_column_tracking" id="column_13" data-element="13"><label for="column_13">Oct</label></li>
					<li class="import_li"><input type="checkbox" name="column_14" class="column_14 select_column_tracking" id="column_14" data-element="14"><label for="column_14">Nov</label></li>
					<li class="import_li"><input type="checkbox" name="column_15" class="column_15 select_column_tracking" id="column_15" data-element="15"><label for="column_15">Dec</label></li>
					<li class="import_li"><input type="checkbox" name="column_16" class="column_16 select_column_tracking" id="column_16" data-element="16"><label for="column_16">Jan</label></li>
			</li>';
		}
		elseif($project_type == "6"){
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Value</label></li>
			</li>';
		}
		elseif($project_type == "7"){
			$selected_year = $proj_details->monthly_value;
			$previous_year = $selected_year - 1;
			$future_year = $selected_year + 1;
			$output.='<ul class="import_ul">
					<li class="import_li"><input type="checkbox" name="column_1" class="column_1" id="column_1" data-element="1" checked><label for="column_1">Client ID</label></li>
					<li class="import_li"><input type="checkbox" name="column_2" class="column_2" id="column_2" data-element="2" checked><label for="column_2">Client Name</label></li>
					<li class="import_li"><input type="checkbox" name="column_3" class="column_3 select_column_tracking" id="column_3" data-element="3"><label for="column_3">Dec</label></li>
					<li class="import_li"><input type="checkbox" name="column_4" class="column_4 select_column_tracking" id="column_4" data-element="4"><label for="column_4">Jan</label></li>
					<li class="import_li"><input type="checkbox" name="column_5" class="column_5 select_column_tracking" id="column_5" data-element="5"><label for="column_5">Feb</label></li>
					<li class="import_li"><input type="checkbox" name="column_6" class="column_6 select_column_tracking" id="column_6" data-element="6"><label for="column_6">Mar</label></li>
					<li class="import_li"><input type="checkbox" name="column_7" class="column_7 select_column_tracking" id="column_7" data-element="7"><label for="column_7">Apr</label></li>
					<li class="import_li"><input type="checkbox" name="column_8" class="column_8 select_column_tracking" id="column_8" data-element="8"><label for="column_8">May</label></li>
					<li class="import_li"><input type="checkbox" name="column_9" class="column_9 select_column_tracking" id="column_9" data-element="9"><label for="column_9">Jun</label></li>
					<li class="import_li"><input type="checkbox" name="column_10" class="column_10 select_column_tracking" id="column_10" data-element="10"><label for="column_10">Jul</label></li>
					<li class="import_li"><input type="checkbox" name="column_11" class="column_11 select_column_tracking" id="column_11" data-element="11"><label for="column_11">Aug</label></li>
					<li class="import_li"><input type="checkbox" name="column_12" class="column_12 select_column_tracking" id="column_12" data-element="12"><label for="column_12">Sep</label></li>
					<li class="import_li"><input type="checkbox" name="column_13" class="column_13 select_column_tracking" id="column_13" data-element="13"><label for="column_13">Oct</label></li>
					<li class="import_li"><input type="checkbox" name="column_14" class="column_14 select_column_tracking" id="column_14" data-element="14"><label for="column_14">Nov</label></li>
					<li class="import_li"><input type="checkbox" name="column_15" class="column_15 select_column_tracking" id="column_15" data-element="15"><label for="column_15">Dec</label></li>
					<li class="import_li"><input type="checkbox" name="column_16" class="column_16 select_column_tracking" id="column_16" data-element="16"><label for="column_16">Jan</label></li>
			</li>';
		}
		$output.='<div class="col-md-12" style="margin-top:20px">
				<div class="col-md-3 padding_00">
					<label style="margin-top:11px">Browse File:</label>
				</div>
				<div class="col-md-9">
					<input type="file" name="import_client_project" class="form-control import_client_project" value="">
				</div>
				<h4 style="margin-top:15px;float:left">Note: The months displayed will be December from the prior year (month 1), January from the Future Year (month 14) , and the 12 months of the current year.</h4>
				<input type="button" class="common_black_button check_imported_file" id="check_imported_file" value="Check File" style="margin-top:20px">
				<div class="col-md-12 padding_00" id="checked_content_tbody" style="max-height:400px;overflow-y:scroll;max-width:100%;overflow-x:scroll">
				</div>
		</div>
		<input type="hidden" name="hidden_import_project_id" id="hidden_import_project_id" value="'.$project_id.'">
		<input type="hidden" name="hidden_import_project_type" id="hidden_import_project_type" value="'.$project_type.'">
		</form>';
		echo $output;
	}
	public function check_import_csv_project_tracking(Request $request){
		$project_id = $request->get('hidden_import_project_id');
		$project_type = $request->get('hidden_import_project_type');
		if($_FILES['import_client_project']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_client_project']['tmp_name'];
			$name=time().'_'.$_FILES['import_client_project']['name'];
			$errorlist = array();
			$output = '<table class="table loadData3">
			<thead>';
			$error_code = "";
			$k = 0;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$chunk_size = 100000;
				$csv_data = array_map('str_getcsv', file($filepath));
				$chunked_data = array_chunk($csv_data, $chunk_size);
				$total_count = count($chunked_data);
				$rowvalue = 2;
				foreach($chunked_data[0] as $key => $row)
				{
					if($key == 0) {
						if($project_type == 1){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							$column_4 = (isset($row[3])) ? $row[3] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Confirmation Boolean" && $column_4 == "Comment")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Confirmation Boolean</th>
									<th>Comment</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 2){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							$column_4 = (isset($row[3])) ? $row[3] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Confirmation Boolean" && $column_4 == "Date")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Confirmation Boolean</th>
									<th>Date</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 3){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							$column_4 = (isset($row[3])) ? $row[3] : '';
							$column_5 = (isset($row[4])) ? $row[4] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Confirmation Boolean" && $column_4 == "Comment" && $column_5 == "Date")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Confirmation Boolean</th>
									<th>Comment</th>
									<th>Date</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 4){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Value")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Value</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 5){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							$column_4 = (isset($row[3])) ? $row[3] : '';
							$column_5 = (isset($row[4])) ? $row[4] : '';
							$column_6 = (isset($row[5])) ? $row[5] : '';
							$column_7 = (isset($row[6])) ? $row[6] : '';
							$column_8 = (isset($row[7])) ? $row[7] : '';
							$column_9 = (isset($row[8])) ? $row[8] : '';
							$column_10 = (isset($row[9])) ? $row[9] : '';
							$column_11 = (isset($row[10])) ? $row[10] : '';
							$column_12 = (isset($row[11])) ? $row[11] : '';
							$column_13 = (isset($row[12])) ? $row[12] : '';
							$column_14 = (isset($row[13])) ? $row[13] : '';
							$column_15 = (isset($row[14])) ? $row[14] : '';
							$column_16 = (isset($row[15])) ? $row[15] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Dec" && $column_4 == "Jan" && $column_5 == "Feb" && $column_6 == "Mar" && $column_7 == "Apr" && $column_8 == "May" && $column_9 == "Jun" && $column_10 == "Jul" && $column_11 == "Aug" && $column_12 == "Sep" && $column_13 == "Oct" && $column_14 == "Nov" && $column_15 == "Dec" && $column_16 == "Jan")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Dec</th>
									<th>Jan</th>
									<th>Feb</th>
									<th>Mar</th>
									<th>Apr</th>
									<th>May</th>
									<th>Jun</th>
									<th>Jul</th>
									<th>Aug</th>
									<th>Sep</th>
									<th>Oct</th>
									<th>Nov</th>
									<th>Dec</th>
									<th>Jan</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 6){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Value")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Value</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
						elseif($project_type == 7){
							$column_1 = (isset($row[0])) ? $row[0] : '';
							$column_2 = (isset($row[1])) ? $row[1] : '';
							$column_3 = (isset($row[2])) ? $row[2] : '';
							$column_4 = (isset($row[3])) ? $row[3] : '';
							$column_5 = (isset($row[4])) ? $row[4] : '';
							$column_6 = (isset($row[5])) ? $row[5] : '';
							$column_7 = (isset($row[6])) ? $row[6] : '';
							$column_8 = (isset($row[7])) ? $row[7] : '';
							$column_9 = (isset($row[8])) ? $row[8] : '';
							$column_10 = (isset($row[9])) ? $row[9] : '';
							$column_11 = (isset($row[10])) ? $row[10] : '';
							$column_12 = (isset($row[11])) ? $row[11] : '';
							$column_13 = (isset($row[12])) ? $row[12] : '';
							$column_14 = (isset($row[13])) ? $row[13] : '';
							$column_15 = (isset($row[14])) ? $row[14] : '';
							$column_16 = (isset($row[15])) ? $row[15] : '';
							if($column_1 == "Client ID" && $column_2 == "Client Name" && $column_3 == "Dec" && $column_4 == "Jan" && $column_5 == "Feb" && $column_6 == "Mar" && $column_7 == "Apr" && $column_8 == "May" && $column_9 == "Jun" && $column_10 == "Jul" && $column_11 == "Aug" && $column_12 == "Sep" && $column_13 == "Oct" && $column_14 == "Nov" && $column_15 == "Dec" && $column_16 == "Jan")
							{
								$output.='<tr>
									<th>Client ID</th>
									<th>Client Name</th>
									<th>Dec</th>
									<th>Jan</th>
									<th>Feb</th>
									<th>Mar</th>
									<th>Apr</th>
									<th>May</th>
									<th>Jun</th>
									<th>Jul</th>
									<th>Aug</th>
									<th>Sep</th>
									<th>Oct</th>
									<th>Nov</th>
									<th>Dec</th>
									<th>Jan</th>
									<th>Error</th>
								</tr>
								</thead>
								<tbody>';
							}
							else{
								$error_code = "1";
								echo json_encode(array("error_code" => $error_code, "output" => ""));
								exit;
							}
						}
					}
					else{
						$row_0 = (isset($row[0])) ? $row[0] : '';
						$client_id = $row_0;
						$error_text = '';
						if($client_id != ""){
							$client_details = \App\Models\CMClients::where('client_id',$client_id)->where('practice_code',Session::get('user_practice_code'))->first();
							if(($client_details))
							{
								$color_code = '#000';
								$error_text = '';
							}
							else{
								$error_code = 3;
								$color_code = '#f00';
								$error_text = 'Client ID is Invalid.';
							}
						}
						else{
							$error_code = 3;
							$color_code = '#f00';
							$error_text = 'Client ID is empty.';
						}
						// $dataproj['project_id'] = $project_id;
						// $dataproj['project_type'] = $project_type;
						if($project_type == 1){
							// $data['client_id'] = (isset($row[0])) ? $row[0] : '';
							// $data['project_status'] = (isset($row[2])) ? $row[2] : '';
							// $data['comment'] = (isset($row[3])) ? $row[3] : '';
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							$row_3 = (isset($row[3])) ? $row[3] : '';
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$row_3.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 2){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							$row_3 = (isset($row[3])) ? $row[3] : '';
							if($row_3 == ""){
								$error_text = '';
								$color_code = '#000';
							}
							else{
								$explode = explode('-',$row_3);
								if(($explode) == 3){
									$error_text.= '';
									$color_code = '#000';
								}
								else{
									$error_code = 3;
									$color_code = '#f00';
									$error_text = 'Date Format is Invalid.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$row_3.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 3){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							$row_3 = (isset($row[3])) ? $row[3] : '';
							$row_4 = (isset($row[4])) ? $row[4] : '';
							if($row_4 == ""){
								$error_text = '';
								$color_code = '#000';
							}
							else{
								$explode = explode('-',$row_3);
								if(($explode) == 3){
									$error_text.= '';
									$color_code = '#000';
								}
								else{
									$error_code = 3;
									$color_code = '#f00';
									$error_text = 'Date Format is Invalid.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$row_3.'</td>
								<td style="color:'.$color_code.'">'.$row_4.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 4){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							if($row_2 == ""){
								$error_text = '';
								$color_code = '#000';
							}else{
								if(is_numeric($row_2)){
									$error_text = '';
									$color_code = '#000';
								}
								else{
									$error_code = 3;
									$color_code = '#f00';
									$error_text = 'Value should be in numeric value.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 5){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							$row_3 = (isset($row[3])) ? $row[3] : '';
							$row_4 = (isset($row[4])) ? $row[4] : '';
							$row_5 = (isset($row[5])) ? $row[5] : '';
							$row_6 = (isset($row[6])) ? $row[6] : '';
							$row_7 = (isset($row[7])) ? $row[7] : '';
							$row_8 = (isset($row[8])) ? $row[8] : '';
							$row_9 = (isset($row[9])) ? $row[9] : '';
							$row_10 = (isset($row[10])) ? $row[10] : '';
							$row_11 = (isset($row[11])) ? $row[11] : '';
							$row_12 = (isset($row[12])) ? $row[12] : '';
							$row_13 = (isset($row[13])) ? $row[13] : '';
							$row_14 = (isset($row[14])) ? $row[14] : '';
							$row_15 = (isset($row[15])) ? $row[15] : '';
							if($row_2 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_2)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_3 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_3)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_4 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_4)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_5 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_5)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_6 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_6)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_7 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_7)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_8 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_8)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_9 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_9)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_10 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_10)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_11 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_11)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_12 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_12)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_13 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_13)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_14 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_14)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_15 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_15)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$row_3.'</td>
								<td style="color:'.$color_code.'">'.$row_4.'</td>
								<td style="color:'.$color_code.'">'.$row_5.'</td>
								<td style="color:'.$color_code.'">'.$row_6.'</td>
								<td style="color:'.$color_code.'">'.$row_7.'</td>
								<td style="color:'.$color_code.'">'.$row_8.'</td>
								<td style="color:'.$color_code.'">'.$row_9.'</td>
								<td style="color:'.$color_code.'">'.$row_10.'</td>
								<td style="color:'.$color_code.'">'.$row_11.'</td>
								<td style="color:'.$color_code.'">'.$row_12.'</td>
								<td style="color:'.$color_code.'">'.$row_13.'</td>
								<td style="color:'.$color_code.'">'.$row_14.'</td>
								<td style="color:'.$color_code.'">'.$row_15.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 6){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							if($row_2 == ""){
								$error_text = '';
								$color_code = '#000';
							}else{
								if(is_numeric($row_2)){
									$error_text = '';
									$color_code = '#000';
								}
								else{
									$error_code = 3;
									$color_code = '#f00';
									$error_text = 'Value should be in numeric value.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
						elseif($project_type == 7){
							$row_0 = (isset($row[0])) ? $row[0] : '';
							$row_1 = (isset($row[1])) ? $row[1] : '';
							$row_2 = (isset($row[2])) ? $row[2] : '';
							$row_3 = (isset($row[3])) ? $row[3] : '';
							$row_4 = (isset($row[4])) ? $row[4] : '';
							$row_5 = (isset($row[5])) ? $row[5] : '';
							$row_6 = (isset($row[6])) ? $row[6] : '';
							$row_7 = (isset($row[7])) ? $row[7] : '';
							$row_8 = (isset($row[8])) ? $row[8] : '';
							$row_9 = (isset($row[9])) ? $row[9] : '';
							$row_10 = (isset($row[10])) ? $row[10] : '';
							$row_11 = (isset($row[11])) ? $row[11] : '';
							$row_12 = (isset($row[12])) ? $row[12] : '';
							$row_13 = (isset($row[13])) ? $row[13] : '';
							$row_14 = (isset($row[14])) ? $row[14] : '';
							$row_15 = (isset($row[15])) ? $row[15] : '';
							if($row_2 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_2)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_3 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_3)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_4 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_4)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_5 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_5)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_6 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_6)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_7 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_7)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_8 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_8)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_9 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_9)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_10 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_10)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_11 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_11)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_12 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_12)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_13 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_13)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_14 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_14)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							if($row_15 == ""){ $error_text = ''; $color_code = '#000'; }
							else{
								if(is_numeric($row_15)){ $error_text = ''; $color_code = '#000'; }
								else{ $error_code = 3; $color_code = '#f00'; $error_text = 'Value should be in numeric value.';
								}
							}
							$output.='<tr>
								<td style="color:'.$color_code.'">'.$row_0.'</td>
								<td style="color:'.$color_code.'">'.$row_1.'</td>
								<td style="color:'.$color_code.'">'.$row_2.'</td>
								<td style="color:'.$color_code.'">'.$row_3.'</td>
								<td style="color:'.$color_code.'">'.$row_4.'</td>
								<td style="color:'.$color_code.'">'.$row_5.'</td>
								<td style="color:'.$color_code.'">'.$row_6.'</td>
								<td style="color:'.$color_code.'">'.$row_7.'</td>
								<td style="color:'.$color_code.'">'.$row_8.'</td>
								<td style="color:'.$color_code.'">'.$row_9.'</td>
								<td style="color:'.$color_code.'">'.$row_10.'</td>
								<td style="color:'.$color_code.'">'.$row_11.'</td>
								<td style="color:'.$color_code.'">'.$row_12.'</td>
								<td style="color:'.$color_code.'">'.$row_13.'</td>
								<td style="color:'.$color_code.'">'.$row_14.'</td>
								<td style="color:'.$color_code.'">'.$row_15.'</td>
								<td style="color:'.$color_code.'">'.$error_text.'</td>
							</tr>';
						}
					}
				}
				$output.='</tbody>
				</table>';
				echo json_encode(array("error_code" => $error_code, "output" => $output));
				exit;
			}
			else{
				$error_code = "2";
				echo json_encode(array("error_code" => $error_code, "output" => ""));
				exit;
			}
		}
	}
	public function submit_import_csv_project_tracking(Request $request){
		$project_id = $request->get('hidden_import_project_id');
		$project_type = $request->get('hidden_import_project_type');
		if($_FILES['import_client_project']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_client_project']['tmp_name'];
			$name=time().'_'.$_FILES['import_client_project']['name'];
			$k = 0;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$chunk_size = 100000;
				$csv_data = array_map('str_getcsv', file($filepath));
				$chunked_data = array_chunk($csv_data, $chunk_size);
				$total_count = count($chunked_data);
				$rowvalue = 2;
				foreach($chunked_data[0] as $key => $row)
				{
					if($key == 0) {
					}
					else{
						$dataproj['project_id'] = $project_id;
						$dataproj['project_type'] = $project_type;
						$client_id = (isset($row[0])) ? $row[0] : '';
						if($project_type == 1){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$row_2 = (isset($row[2])) ? $row[2] : '';
								if($row_2 == 'Yes') { $row_2 = "1"; } else{ $row_2 = "0"; }
								$dataproj['project_status'] = $row_2;
							}
							if($request->get('column_4') == 'on'){
								$dataproj['comment'] = (isset($row[3])) ? $row[3] : '';
							}
						}
						elseif($project_type == 2){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$row_2 = (isset($row[2])) ? $row[2] : '';
								if($row_2 == 'Yes') { $row_2 = "1"; } else{ $row_2 = "0"; }
								$dataproj['project_status'] = $row_2;
							}
							if($request->get('column_4') == 'on') {
								$row_3 = (isset($row[3])) ? $row[3] : '';
								$explode_row = explode('-',$row_3);
								if(($explode_row) == 3){
									$datevalue = $explode_row[2].'-'.$explode_row[1].'-'.$explode_row[0];
								}
								else{
									$datevalue = '';
								}
								$dataproj['project_date'] = $datevalue;
							}
						}
						elseif($project_type == 3){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$row_2 = (isset($row[2])) ? $row[2] : '';
								if($row_2 == 'Yes') { $row_2 = "1"; } else{ $row_2 = "0"; }
								$dataproj['project_status'] = $row_2;
							}
							if($request->get('column_4') == 'on'){
								$dataproj['comment'] = (isset($row[3])) ? $row[3] : '';
							}
							if($request->get('column_5') == 'on'){
								$row_4 = (isset($row[4])) ? $row[4] : '';
								$explode_row = explode('-',$row_4);
								if(($explode_row) == 3){
									$datevalue = $explode_row[2].'-'.$explode_row[1].'-'.$explode_row[0];
								}
								else{
									$datevalue = '';
								}
								$dataproj['project_date'] = $datevalue;
							}
						}
						elseif($project_type == 4){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$dataproj['value'] = (isset($row[2])) ? $row[2] : '';
							}
						}
						elseif($project_type == 5){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$dataproj['month_1'] = (isset($row[2])) ? $row[2] : '';
							}
							if($request->get('column_4') == 'on'){
								$dataproj['month_2'] = (isset($row[3])) ? $row[3] : '';
							}
							if($request->get('column_5') == 'on'){
								$dataproj['month_3'] = (isset($row[4])) ? $row[4] : '';
							}
							if($request->get('column_6') == 'on'){
								$dataproj['month_4'] = (isset($row[5])) ? $row[5] : '';
							}
							if($request->get('column_7') == 'on'){
								$dataproj['month_5'] = (isset($row[6])) ? $row[6] : '';
							}
							if($request->get('column_8') == 'on'){
								$dataproj['month_6'] = (isset($row[7])) ? $row[7] : '';
							}
							if($request->get('column_9') == 'on'){
								$dataproj['month_7'] = (isset($row[8])) ? $row[8] : '';
							}
							if($request->get('column_10') == 'on'){
								$dataproj['month_8'] = (isset($row[9])) ? $row[9] : '';
							}
							if($request->get('column_11') == 'on'){
								$dataproj['month_9'] = (isset($row[10])) ? $row[10] : '';
							}
							if($request->get('column_12') == 'on'){
								$dataproj['month_10'] = (isset($row[11])) ? $row[11] : '';
							}
							if($request->get('column_13') == 'on'){
								$dataproj['month_11'] = (isset($row[12])) ? $row[12] : '';
							}
							if($request->get('column_14') == 'on'){
								$dataproj['month_12'] = (isset($row[13])) ? $row[13] : '';
							}
							if($request->get('column_15') == 'on'){
								$dataproj['month_13'] = (isset($row[14])) ? $row[14] : '';
							}
							if($request->get('column_16') == 'on'){
								$dataproj['month_14'] = (isset($row[15])) ? $row[15] : '';
							}
						}
						elseif($project_type == 6){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$dataproj['value'] = (isset($row[2])) ? $row[2] : '';
							}
						}
						elseif($project_type == 7){
							$dataproj['client_id'] = (isset($row[0])) ? $row[0] : '';
							if($request->get('column_3') == 'on'){
								$dataproj['month_1'] = (isset($row[2])) ? $row[2] : '';
							}
							if($request->get('column_4') == 'on'){
								$dataproj['month_2'] = (isset($row[3])) ? $row[3] : '';
							}
							if($request->get('column_5') == 'on'){
								$dataproj['month_3'] = (isset($row[4])) ? $row[4] : '';
							}
							if($request->get('column_6') == 'on'){
								$dataproj['month_4'] = (isset($row[5])) ? $row[5] : '';
							}
							if($request->get('column_7') == 'on'){
								$dataproj['month_5'] = (isset($row[6])) ? $row[6] : '';
							}
							if($request->get('column_8') == 'on'){
								$dataproj['month_6'] = (isset($row[7])) ? $row[7] : '';
							}
							if($request->get('column_9') == 'on'){
								$dataproj['month_7'] = (isset($row[8])) ? $row[8] : '';
							}
							if($request->get('column_10') == 'on'){
								$dataproj['month_8'] = (isset($row[9])) ? $row[9] : '';
							}
							if($request->get('column_11') == 'on'){
								$dataproj['month_9'] = (isset($row[10])) ? $row[10] : '';
							}
							if($request->get('column_12') == 'on'){
								$dataproj['month_10'] = (isset($row[11])) ? $row[11] : '';
							}
							if($request->get('column_13') == 'on'){
								$dataproj['month_11'] = (isset($row[12])) ? $row[12] : '';
							}
							if($request->get('column_14') == 'on'){
								$dataproj['month_12'] = (isset($row[13])) ? $row[13] : '';
							}
							if($request->get('column_15') == 'on'){
								$dataproj['month_13'] = (isset($row[14])) ? $row[14] : '';
							}
							if($request->get('column_16') == 'on'){
								$dataproj['month_14'] = (isset($row[15])) ? $row[15] : '';
							}
						}
						$check_client = \App\Models\projectClients::where('project_id',$project_id)->where('client_id',$client_id)->first();
						if(($check_client)){
							\App\Models\projectClients::where('id',$check_client->id)->update($dataproj);
						}
						else{
							\App\Models\projectClients::insert($dataproj);
						}
					}
				}
			}
		}
	}
	public function insertTrackingProjectCopyPastedData(Request $request){
		$projectTrackClientId = $request->get('id');
		$colsData = json_decode($request->get('key'));
		$colsValue = json_decode($request->get('value'));
		if(($colsData)>0){
			foreach($colsData as $key=>$data){
				if($data != ''){
					$update['month_'.$data]=trim($colsValue[$key]); 
				}
			}
			\App\Models\projectClients::where('id',$projectTrackClientId)->update($update);
		}
	}
}