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
class CroardController extends Controller {
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
	public function manage_croard(Request $request)
	{
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
		return view('user/croard/croardmanager', array('title' => 'Bubble - CRO ARD Manager', 'clientlist' => $client));
	}
	public function croard_monthly(Request $request)
	{
		return view('user/croard/croard_monthly', array('title' => 'Bubble - CRO ARD Manager'));
	}
	public function search_croard_month_year(Request $request){
		$month_year = date('m/Y', strtotime('01-'.$request->get('month_year')));
		$like = $month_year;
		$cro_ard_details = \App\Models\Croard::where('cro_ard','LIKE','%'.$like)->groupBy('client_id')->pluck('client_id')->toArray();
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->whereIn('client_id',$cro_ard_details)->orderBy('id','asc')->get();
		$ivall=1;
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$submission_days = $croard_settings->croard_submission_days;
		$output = '';
	    if(($clientlist)){              
		    foreach($clientlist as $key => $client){
	            $disabled='';
                $style="color:#000";
				if($client->active != "")
				{
					if($client->active == 2)
					{
					  	$disabled='disabled_tr';
						$style="color:#f00";
					}
					$check_color = \App\Models\CMClass::where('id',$client->active)->first();
				}
                $last_submission = '';
				$cmp = '<spam class="company_td" style="font-style:italic;"></spam>';
				$cr_ard_date = '';
				$ard_color = '';
                $timestampcroard = '';
	            $cro_ard_details = \App\Models\Croard::where('client_id',$client->client_id)->first();
                $notes = '';
                $color_status = '';
                $status_label = '';
                $attachment = '';
                $last_email_sent = '';
                $signature_checked = '';
                $yellow_status = '';
                $yellow_label = '';
                $signature_file_date = '';
                $rbo_submission = '';
	            if(($cro_ard_details))
	            {
                  if($cro_ard_details->filename != "")
                  {
                    $attachment = '<a class="attachment_link" href="'.URL::to($cro_ard_details->url.'/'.$cro_ard_details->filename).'" download>'.$cro_ard_details->filename.'</a>';
                  }
                  if($cro_ard_details->signature == 1)
                  {
                    $signature_checked = 'checked';
                    $yellow_status = 'yellow_status';
                    $yellow_label = '';
                  }
	              if($cro_ard_details->signature_date != "")
                  {
                    $signature_file_date = date('d/m/Y', strtotime($cro_ard_details->signature_date));
                  }
                  if($cro_ard_details->last_email_sent != "")
                  {
                    $last_email_sent = date('d F Y @ H : i', strtotime($cro_ard_details->last_email_sent));
                  }
                  $notes = $cro_ard_details->notes;
                  $clientname_company = preg_replace('/[[:^print:]]/', '', strtolower($client->company));
                  $croname_company = preg_replace('/[[:^print:]]/', '', strtolower($cro_ard_details->company_name));
	              if($cro_ard_details->cro_ard != "")
	              {
                    $exp_api_date_month = explode("/",$cro_ard_details->cro_ard);
                    $api_date_month = '';
                    if(($exp_api_date_month))
                    {
                      $api_date_month = $exp_api_date_month[0].'/'.$exp_api_date_month[1];
                    }
		            $ard = explode("/",$client->ard);
	              	if(count($ard) > 1)
	              	{
	              		$ard_date_month = $ard[0].'/'.$ard[1];
	              	}
					else{
						$ard_date_month = '';
					}
					if($ard_date_month == $api_date_month)
		            {
		              	$cr_ard_date = $cro_ard_details->cro_ard;
                      	if($cro_ard_details->cro_ard == "")
                      	{
                        	$timestampcroard = '';
                      	}
                      	else{
                        	$expandcroard = explode('/',$cro_ard_details->cro_ard);
                        	if(count($expandcroard) > 1)
                        	{
                          		$correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
                         	 	$timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
                          		$dd = date('d/m/Y', strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]. ' + '.$submission_days.' days'));
                          		$current_date = date('Y-m-d');
                          		$current_year = date('Y');
                          		$croard_year = $expandcroard[2];
                          		if($croard_year > $current_year)
                          		{
                            		$color_status = 'blue_status';
                            		$status_label = 'Current Year OK';
                            		$ard_color = 'color:blue';
                          		}
                          		else{
		                            $firstdate = strtotime($correctcroard);
		                            $seconddate = strtotime($current_date);
		                            $diff = ceil(($firstdate - $seconddate)/60/60/24);
		                            if($diff < 0 || $diff == 0)
		                            {
		                              $color_status = 'red_status';
		                              $status_label = 'Submission Late';
		                              $ard_color = 'color:red';
		                              $last_submission = '<strong>Last Submission Date: <spam>'.$dd.'</spam></strong>';
		                            }
		                            elseif($diff <= 30)
		                            {
		                              $color_status = 'orange_status';
		                              $status_label = 'Submission Pending';
		                              $ard_color = 'color:orange';
		                            }
		                            elseif($diff > 30)
		                            {
		                              $color_status = 'green_status';
		                              $status_label = 'Future Submission';
		                              $ard_color = 'color:green';
		                            }
                          		}
	                        }
	                        else{
	                          $timestampcroard = '';
	                        }
                      	}
		            }
		            else{
		              	$cr_ard_date = $cro_ard_details->cro_ard;
                      	if($cro_ard_details->cro_ard == "")
                      	{
                        	$timestampcroard = '';
                      	}
                      	else{
                        	$expandcroard = explode('/',$cro_ard_details->cro_ard);
	                        if(count($expandcroard) > 1)
	                        {
	                          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
	                          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
	                          $dd = date('d/m/Y', strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]. ' + '.$submission_days.' days'));
	                          $current_date = date('Y-m-d');
	                          $current_year = date('Y');
	                          $croard_year = $expandcroard[2];
	                          if($croard_year > $current_year)
	                          {
	                            $color_status = 'blue_status';
	                            $status_label = 'Current Year OK';
	                            $ard_color = 'color:blue';
	                          }
	                          else{
	                            $firstdate = strtotime($correctcroard);
	                            $seconddate = strtotime($current_date);
	                            $diff = ceil(($firstdate - $seconddate)/60/60/24);
	                            if($diff < 0 || $diff == 0)
	                            {
	                              $color_status = 'red_status';
	                              $status_label = 'Submission Late';
	                              $ard_color = 'color:red';
	                              $last_submission = '<strong>Last Submission Date: <spam>'.$dd.'</spam></strong>';
	                            }
	                            elseif($diff <= 30)
	                            {
	                              $color_status = 'orange_status';
	                              $status_label = 'Submission Pending';
	                              $ard_color = 'color:orange';
	                            }
	                            elseif($diff > 30)
	                            {
	                              $color_status = 'green_status';
	                              $status_label = 'Future Submission';
	                              $ard_color = 'color:green';
	                            }
	                          }
	                        }
	                        else{
	                          $timestampcroard = '';
	                        }
                      	}
		            }
	              }
                  if($clientname_company == $croname_company)
                  {
                    $cmp = '<spam class="company_td" style="color:green;font-style:italic">'.$cro_ard_details->company_name.'</spam>';
                  }
                  else{
                    $cmname = ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company;
                    $cmp = '<spam class="company_td company_blue" data-crono="'.$client->client_id.'" data-cmname="'.$cmname.'" data-croname="'.$cro_ard_details->company_name.'" data-croard="'.$cr_ard_date.'" data-cronumber="'.$client->cro.'" data-type="'.$client->tye.'" style="color:blue;font-style:italic;font-weight:800">'.$cro_ard_details->company_name.'</spam>';
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
	            }
                if($client->ard == "")
                {
                  $timestampard = '';
                }
                else{
                  $expand = explode('/',$client->ard);
                  if(count($expand) > 1)
                  {
                    $correctard = $expand[2].'-'.$expand[1].'-'.$expand[0];
                      $timestampard = strtotime($expand[2].'-'.$expand[1].'-'.$expand[0]);
                  }
                  else{
                    $timestampard = '';
                  }
                }
                $client_company = ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company;
                $client_type = ($client->tye == "")?"-":$client->tye;
                $cro_text = ($client->cro == "")?"-":'<a href="javascript:" class="check_cro" data-element="'.$client->cro.'">'.$client->cro.'</a>';
	            $output.='<tr class="edit_task '.$disabled.'" style="'.$style.'"  id="clientidtr_'.$client->client_id.'">
	                <td style="'.$style.'" class="sno_sort_vall">'.$ivall.'</td>
	                <td style="'.$style.'" class="clientid_sort_val" align="left">'.$client->client_id.'</td>
	                <td style="'.$style.'" align="left"><spam class="company_sort_val">'.$client_company.'</spam> <br/> '.$cmp.'</td>
	                <td style="'.$style.'" class="cro_sort_val" align="left">
                      '.$cro_text.'
                	</td>
	                <td style="'.$style.'" class="type_sort_val" align="left">'.$client_type.'</td>
	                <td class="cro_ard_td" style="'.$ard_color.'" align="left"><spam class="cro_ard_sort_val" style="display: none">'.$timestampcroard.'</spam><spam class="cro_ard_val">'.$cr_ard_date.'</spam></td>
                	<td class="future_submission_td" align="left" style="color:#000">
                		<label class="status_icon '.$color_status.' '.$yellow_status.'">
		                    <spam class="status_label">'.$status_label.' </spam>
		                    <spam class="yellow_label" style="display:none">Awaiting CRO Update</spam>
		                </label> &nbsp;&nbsp; '.$last_submission.'<br/>
                  		<div class="col-md-12 padding_00">
		                    <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a File"></a> 
		                    <div class="attachment_div">'.$attachment.'</div>
		                </div>
		                <div class="col-md-12 padding_00" style="margin-top:15px">
		                    <a href="javascript:" class="fa fa-envelope email_unsent" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Send Email"></a>
		                    <div class="email_unsent_label">'.$last_email_sent.'</div>
		                </div>
		                <div class="col-md-12 padding_00" style="margin-top:15px">
	                    	<input type="checkbox" name="signature_file_check" id="signature_file_check'.$client->client_id.'" class="signature_file_check" data-element="'.$client->client_id.'" data-crono="'.$client->cro.'" value="" '.$signature_checked.'><label for="signature_file_check'.$client->client_id.'" 
	                      style="width: fit-content;float: left;margin-right: 10px;">Signature file Submitted</label>
	                    	<input type="text" class="form-control signature_file_date" id="signature_file_date" name="signature_file_date" value="'.$signature_file_date.'" data-element="'.$client->client_id.'" readonly style="width: 30%;margin-top: -20px;background: #dfdfdf">
	                  	</div>';
	                  $tasks_det = \App\Models\taskmanagerCroard::where('client_id',$client->client_id)->get();
	                  if(($tasks_det))
	                  {
	                    $output.='<div class="col-md-12 padding_00 linked_tasks_div" style="margin-top:15px"><h5>Linked Tasks</h5>';
	                    $i = 1;
	                    foreach($tasks_det as $task_det)
	                    {
	                      $task_details = \App\Models\taskmanager::where('id',$task_det->task_id)->first();
	                      $output.='<p style="float: left;margin-top: 10px;font-weight: 600;width: 100%;">'.$i.'. Task : '.$task_details->taskid.' - '.$task_details->subject.'</p>';
	                      $i++;
	                    }
	                    $output.='</div>';
	                  }
	                  else{
	                  	echo '<div class="col-md-12 padding_00 linked_tasks_div" style="margin-top:15px;display:none"></div>';
	                  }
                	$output.='</td>
	            	<td align="left"><a href="javascript:" class="fa fa-refresh refresh_croard" data-element="'.$client->client_id.'" data-cro="'.trim($client->cro).'" data-type="'.trim($client->tye).'" style="'.$style.'"></a></td>
	          	</tr>';
              	$ivall++;
            }              
        }
        if($ivall == 1)
        {
          $output.='<tr><td colspan="11" align="center">Empty</td></tr>';
        }
        echo $output;
	}
	public function get_company_details_cro(Request $request)
	{
		$company_number = $request->get('company_number');
		$indicator = $request->get('indicator');
		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }
		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 
		$response = curl_exec($ch);
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);
		$address = '';
		if(isset($results_array->company_addr_1))
		{
			$address.= $results_array->company_addr_1;
		}
		if(isset($results_array->company_addr_2))
		{
			$address.= PHP_EOL.$results_array->company_addr_2;
		}
		if(isset($results_array->company_addr_3))
		{
			$address.= PHP_EOL.$results_array->company_addr_3;
		}
		if(isset($results_array->company_addr_3))
		{
			$address.= PHP_EOL.$results_array->company_addr_3;
		}
		$output = '
		<table class="table">
	        <tbody>
	          <tr>
	            <td>Company Number:</td>
	            <td><input type="text" name="company_number" class="form-control company_number" value="'.$results_array->company_num.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company / Business indicator:</td>
	            <td><input type="text" name="indicator_text" class="form-control indicator_text" value="'.$indi.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Name:</td>
	            <td><input type="text" name="company_name" class="form-control company_name" value="'.$results_array->company_name.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Address:</td>
	            <td><textarea name="company_address" class="form-control company_address" disabled style="height:110px">'.$address.'</textarea></td>
	          </tr>
	          <tr>
	            <td>Company Registration Date:</td>
	            <td><input type="text" name="company_reg_date" class="form-control company_reg_date" value="'.date('Y-m-d', strtotime($results_array->company_reg_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status:</td>
	            <td><input type="text" name="company_status_desc" class="form-control company_status_desc" value="'.$results_array->company_status_desc.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status Date:</td>
	            <td><input type="text" name="company_status_date" class="form-control company_status_date" value="'.date('Y-m-d', strtotime($results_array->company_status_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Next ARD:</td>
	            <td><input type="text" name="next_ar_date" class="form-control next_ar_date" value="'.date('Y-m-d', strtotime($results_array->next_ar_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Last ARD:</td>
	            <td><input type="text" name="last_ar_date" class="form-control last_ar_date" value="'.date('Y-m-d', strtotime($results_array->last_ar_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Accounts Upto:</td>
	            <td><input type="text" name="last_acc_date" class="form-control last_acc_date" value="'.date('Y-m-d', strtotime($results_array->last_acc_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Type:</td>
	            <td><input type="text" name="comp_type_desc" class="form-control comp_type_desc" value="'.$results_array->comp_type_desc.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Type Code:</td>
	            <td><input type="text" name="company_type_code" class="form-control company_type_code" value="'.$results_array->company_type_code.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status Code:</td>
	            <td><input type="text" name="company_status_code" class="form-control company_status_code" value="'.$results_array->company_status_code.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Place of Business:</td>
	            <td><input type="text" name="place_of_business" class="form-control place_of_business" value="'.$results_array->place_of_business.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Eircode:</td>
	            <td><input type="text" name="eircode" class="form-control eircode" value="'.$results_array->eircode.'" disabled></td>
	          </tr>
	        </tbody>
	      </table>';
		echo $output;
	}
	public function refresh_cro_ard(Request $request)
	{
		$client_id = $request->get('clientid');
		$cro = $request->get('cro');
		$company_number = $request->get('cro');
		$gccid = $request->get('gccid');
		$indicator = 'C';
		//$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		$userpwd = $croard_settings->username.":".$croard_settings->api_key; // new
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) ); // change
		curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
// 		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
// 		curl_setopt($ch, CURLOPT_FAILONERROR,1);
// 		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
// 		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
// 		curl_setopt($ch, CURLOPT_URL, $url );
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 		curl_setopt($ch, CURLOPT_POST, 0); 
		$response = curl_exec($ch);
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);
		if($results_array)
		{
			$nextard = $results_array->next_ar_date;
			$company = $results_array->company_name;
		}
		else{
			$nextard = '';
			$company = '';
		}
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$ard = explode("/",$client->ard);
		if(count($ard) > 1)
		{
			$ard_date_month = $ard[0].'/'.$ard[1];
		}
		else{
			$ard_date_month = '';
		}
		if($nextard != "")
		{
			$api_date_month = date('d/m',strtotime($nextard));
			if($ard_date_month == $api_date_month)
			{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "0";
			}
			else{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "1";
			}
		}
		else{
			$coreard = '';
			$corard_timestamp = '';
			$ardstatus = "0";
		}
		if(strtolower($company) == strtolower($client->company))
		{
			$companyname = $company;
			$companystatus = "0";
		}
		else{
			$companyname = $company;
			$companystatus = "1";
		}
		$data['company_name'] = $companyname;
		$data['cro_ard'] = $coreard;
		$detail = \App\Models\Croard::where('client_id',$client_id)->first();
		if($detail)
		{
			\App\Models\Croard::where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			\App\Models\Croard::insert($data);
		}

		

		echo json_encode(array('company_name' => $companyname, 'next_ard' => $coreard, 'corard_timestamp' => $corard_timestamp, 'companystatus' => $companystatus, 'ardstatus' => $ardstatus));
	}
	public function refresh_blue_cro_ard(Request $request)
	{
		$client_id = $request->get('clientid');
		$cro = $request->get('cro');
		$company_number = $request->get('cro');
		$indicator = 'C';
		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 
		$response = curl_exec($ch);
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);
		if(($results_array))
		{
			$nextard = $results_array->next_ar_date;
			$company = $results_array->company_name;
		}
		else{
			$nextard = '';
			$company = '';
		}
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$ard = explode("/",$client->ard);
		if(count($ard) > 1)
		{
			$ard_date_month = $ard[0].'/'.$ard[1];
		}
		else{
			$ard_date_month = '';
		}
		if($nextard != "")
		{
			$api_date_month = date('d/m',strtotime($nextard));
			if($ard_date_month == $api_date_month)
			{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "0";
			}
			else{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "1";
			}
		}
		else{
			$coreard = '';
			$corard_timestamp = '';
			$ardstatus = "0";
		}
		if(strtolower($company) == strtolower($client->company))
		{
			$companyname = $company;
			$companystatus = "0";
		}
		else{
			$companyname = $company;
			$companystatus = "1";
		}
		$data['company_name'] = $companyname;
		$data['cro_ard'] = $coreard;
		$detail = \App\Models\Croard::where('client_id',$client_id)->first();
		if(($detail))
		{
			\App\Models\Croard::where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			\App\Models\Croard::insert($data);
		}
		echo json_encode(array('company_name' => $companyname, 'next_ard' => $coreard, 'corard_timestamp' => $corard_timestamp, 'companystatus' => $companystatus, 'ardstatus' => $ardstatus));
	}
	public function update_cro_notes(Request $request)
	{
		$value = $request->get('input_val');
		$clientid = $request->get('clientid');
		$details = \App\Models\Croard::where('client_id',$clientid)->first();
		if(($details))
		{
			$data['notes'] = $value;
			\App\Models\Croard::where('id',$details->id)->update($data);
		}
		else{
			$data['notes'] = $value;
			\App\Models\Croard::insert($data);
		}
	}
	public function update_rbo_submission(Request $request)
	{
		$value = $request->get('input_val');
		$clientid = $request->get('clientid');
		$details = \App\Models\Croard::where('client_id',$clientid)->first();
		if(($details))
		{
			$data['rbo_submission'] = $value;
			\App\Models\Croard::where('id',$details->id)->update($data);
		}
		else{
			$data['rbo_submission'] = $value;
			\App\Models\Croard::insert($data);
		}
	}
	public function croard_upload_images(Request $request)
	{
		$client_id = $request->get('hidden_client_id_croard');
		$upload_dir = 'uploads/croard_uploads';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$client_id;
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
			$data['client_id'] = $client_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir;
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
			if(($client_details))
			{
				$data['company_name'] = $client_details->company;
			}
			$details = \App\Models\Croard::where('client_id',$client_id)->first();
			if(!($details))
			{
				$insertedid = \App\Models\Croard::insertDetails($data);
			}
			else{
				\App\Models\Croard::where('client_id',$client_id)->update($data);
				$insertedid = $details->id;
			}
			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_croard_files?file_id='.$insertedid.'');
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'client_id' => $client_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
		}
	}
	public function get_company_details_next_crd(Request $request)
	{
		$company_number = $request->get('company_number');
		$indicator = $request->get('indicator');
		$client_id = $request->get('client_id');
		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }
		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/C";
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 
		$response = curl_exec($ch);
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);
		$color_status = '';
		$status_label = '';
		$cro_ard_val = date('d/m/Y', strtotime($results_array->next_ar_date));
		$dataval['cro_ard'] = $cro_ard_val;
		$dataval['signature_date'] = $results_array->next_ar_date;
		\App\Models\Croard::where('client_id',$client_id)->update($dataval);
		$updated = 0;
		$cro_ard_details = \App\Models\Croard::where('client_id',$client_id)->first();
		if(($cro_ard_details))
		{
			$expandcroard = explode('/',$cro_ard_details->cro_ard);
	        if(($expandcroard) > 1)
	        {
	          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
	          if($correctcroard != $results_array->next_ar_date)
	          {
	          	$updated = 1;
	          	$datavall['signature'] = 0;
				\App\Models\Croard::where('client_id',$client_id)->update($datavall);
	          }
	          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
	          $current_date = date('Y-m-d');
	          $current_year = date('Y');
	          $croard_year = $expandcroard[2];
	          if($croard_year > $current_year)
	          {
	            $color_status = 'blue_status';
	            $status_label = 'Current Year OK';
	          }
	          else{
	            $firstdate = strtotime($correctcroard);
	            $seconddate = strtotime($current_date);
	            $diff = ceil(($firstdate - $seconddate)/60/60/24);
	            if($diff < 0 || $diff == 0)
	            {
	              $color_status = 'red_status';
	              $status_label = 'Submission Late';
	            }
	            elseif($diff <= 30)
	            {
	              $color_status = 'orange_status';
	              $status_label = 'Submission Pending';
	            }
	            elseif($diff > 30)
	            {
	              $color_status = 'green_status';
	              $status_label = 'Future Submission';
	            }
	          }
	        }
		}
		echo json_encode(array("croard" => date('d/m/Y', strtotime($results_array->next_ar_date)), "color_status" => $color_status, "status_label" => $status_label,'updated' => $updated));
	}
	public function edit_email_unsent_files_croard(Request $request)
	{
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$client_id = $request->get('client_id');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$result = \App\Models\Croard::where('client_id',$client_id)->first();
		$files = '';
		$html = '<p>Hi '.$client_details->firstname.'</p>
		<p>Please find attached the B1 for '.$result->company_name.' which needs to be signed and sent back to my office at your very earliest convenience.</p>
		<p>I note this B1 can be scanned-in and emailed back to me, it must be signed and dated before you send it back, and the quality of the scan must be very good Quality.</p>
		'.$croard_settings->croard_signature.'';

		$getExt = explode(".",$result->filename);
		$ext = strtolower(end($getExt));
		
		if($ext == "pdf") {
			$img = URL::to('public/assets/images/pdf.png');
		}
		elseif($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "svg") {
			$img = URL::to('public/assets/images/jpg.png');
		}
		elseif($ext == "rar" || $ext == "zip") {
			$img = URL::to('public/assets/images/zip.png');
		}
		elseif($ext == "doc" || $ext == "docx") {
			$img = URL::to('public/assets/images/docx.png');
		}
		elseif($ext == "xls" || $ext == "xlsx" || $ext == "csv") {
			$img = URL::to('public/assets/images/xlsx.png');
		}
		else{
			$img = URL::to('public/assets/images/no-image.png');
		}

		$files ='<p><img class="email_selected_zip_image" src="'.$img.'" style="width:30px;float:left">'.$result->filename.'</p>';
	    $subject = 'CROARD: '.$result->company_name.'';
	    if(($client_details))
	    {
	    	if($client_details->email2 != '')
			{
				$to_email = $client_details->email.','.$client_details->email2;
			}
			else{
				$to_email = $client_details->email;
			}
	    }
	    echo json_encode(["files" => $files,"html" => $html,"to" => $to_email,'subject' => $subject]);
	}
	public function email_unsent_files_croard(Request $request)
	{
		$from_input = $request->get('select_user');
		$client_id = $request->get('hidden_client_id_email_croard');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = $request->get('to_user').','.$request->get('cc_unsent');
		$sentmails = $request->get('to_user').', '.$request->get('cc_unsent');
		$subject = $request->get('subject_unsent'); 
		$message = $request->get('message_editor');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		$croard_details = \App\Models\Croard::where('client_id',$client_id)->first();
		$attach = $croard_details->url.'/'.$croard_details->filename;
		$filename = $croard_details->filename;

		if(($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = getEmailLogo('croard');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_croard', $data);
				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->AddAttachment( $attach , $filename );
				$email->Send();
			}
			$date = date('Y-m-d H:i:s');
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
			$datamessage['message_id'] = time();
			$datamessage['message_from'] = $from_input;
			$datamessage['subject'] = $subject;
			$datamessage['message'] = $message;
			$datamessage['client_ids'] = $client_id;
			$datamessage['primary_emails'] = $client_details->email;
			$datamessage['secondary_emails'] = $client_details->email2;
			$datamessage['date_sent'] = $date;
			$datamessage['date_saved'] = $date;
			$datamessage['source'] = "CROARD";
			$datamessage['attachments'] = $attach;
			$datamessage['status'] = 1;
			$datamessage['practice_code'] = Session::get('user_practice_code');
			\App\Models\Messageus::insert($datamessage);
			\App\Models\Croard::where('client_id',$client_id)->update(['last_email_sent' => $date]);
			$last_date = date('d F Y @ H : i', strtotime($date));
			echo $last_date.'||'.$client_id;
		}
		else{
			echo "1";
		}
	}
	public function change_yellow_status_croard(Request $request)
	{
		$company_number = $request->get('crono');
		$indicator = 'C';
		$client_id = $request->get('client_id');
		$status = $request->get('status');
		if($company_number != ""){
			if($status == 1)
			{
				if($indicator == "C") { $indi = 'Limited Company'; }
				else { $indi = 'Registered Business'; }
				$ch = curl_init();
				$url = "https://services.cro.ie/cws/company/".$company_number."/C";
				$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
				$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
				$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
				    "Content-Type: application/json", 
				    "charset: utf-8");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
				curl_setopt($ch, CURLOPT_FAILONERROR,1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 15);
				curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				curl_setopt($ch, CURLOPT_URL, $url );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_POST, 0); 
				$response = curl_exec($ch);
				// Some values from the header if want to take a look... 
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
				//echo $code.'<p>'.$headerOut.'</p>';
				// Don't forget to close handle.
				curl_close($ch);
				$results_array = json_decode($response);
				$color_status = '';
				$status_label = '';
				$cro_ard_val = date('d/m/Y', strtotime($results_array->next_ar_date));
				$updated = 0;
				$ard_color = 'color:#000';
				$cro_ard_details = \App\Models\Croard::where('client_id',$client_id)->first();
				if(($cro_ard_details))
				{
					$signature_date = '';
					$html_output = '';
					if($cro_ard_val != $cro_ard_details->cro_ard)
					{
						$dataval['cro_ard'] = $cro_ard_val;
						$dataval['signature'] = 0;
						$dataval['signature_date'] = $signature_date;
						$dataval['filename'] = '';
						$dataval['url'] = '';
						$dataval['last_email_sent'] = '';
						\App\Models\Croard::where('client_id',$client_id)->update($dataval);
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
						$html_output = '<tr>
		                    <td>'.$client_id.'</td>
		                    <td>'.$cro_ard_details->company_name.'</td>
		                    <td>'.$client_details->cro.'</td>
		                    <td>'.$cro_ard_details->cro_ard.'</td>
		                    <td>'.$cro_ard_val.'</td>
		                  </tr>';
						$updated = 1;
					}
					else{
						$signature_date = date('Y-m-d');
						$dataval['cro_ard'] = $cro_ard_details->cro_ard;
						$dataval['signature'] = 1;
						$dataval['signature_date'] = $signature_date;
						\App\Models\Croard::where('client_id',$client_id)->update($dataval);
						$updated = 0;
						$signature_date = date('d/m/Y', strtotime($signature_date));
					}
					$expandcroard = explode('/',$cro_ard_val);
			        if(count($expandcroard) > 1)
			        {
			          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
			          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
			          $current_date = date('Y-m-d');
			          $current_year = date('Y');
			          $croard_year = $expandcroard[2];
			          if($croard_year > $current_year)
			          {
			            $color_status = 'blue_status';
			            $status_label = 'Current Year OK';
			            $ard_color = 'blue';
			          }
			          else{
			            $firstdate = strtotime($correctcroard);
			            $seconddate = strtotime($current_date);
			            $diff = ceil(($firstdate - $seconddate)/60/60/24);
			            if($diff < 0 || $diff == 0)
			            {
			              $color_status = 'red_status';
			              $status_label = 'Submission Late';
			              $ard_color = 'red';
			            }
			            elseif($diff <= 30)
			            {
			              $color_status = 'orange_status';
			              $status_label = 'Submission Pending';
			              $ard_color = 'orange';
			            }
			            elseif($diff > 30)
			            {
			              $color_status = 'green_status';
			              $status_label = 'Future Submission';
			              $ard_color = 'green';
			            }
			          }
			        }
			        echo json_encode(array("croard" => $cro_ard_val, "color_status" => $color_status, "status_label" => $status_label,'updated' => $updated,'ard_color' => $ard_color,'signature_date' => $signature_date, 'html_output' => $html_output));
				}
				else{
					$updated = 2; // Client Id not inserted in CROARD Table. Need to click on the refresh icon 
					echo json_encode(array('signature_date' => date('Y-m-d'),'updated' => $updated));
				}
			}
			else{
				$checkdbb = \App\Models\Croard::where('client_id',$client_id)->first();
				if(($checkdbb)){
					$data['signature'] = 0;
					$data['signature_date'] = "";
					$updated = 3;
					\App\Models\Croard::where('client_id',$client_id)->update($data);
					$expandcroard = explode('/',$checkdbb->cro_ard);
					$color_status = '';
					$status_label = '';
					$ard_color = '';
			        if(count($expandcroard) > 1)
			        {
			          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
			          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
			          $current_date = date('Y-m-d');
			          $current_year = date('Y');
			          $croard_year = $expandcroard[2];
			          if($croard_year > $current_year)
			          {
			            $color_status = 'blue_status';
			            $status_label = 'Current Year OK';
			            $ard_color = 'blue';
			          }
			          else{
			            $firstdate = strtotime($correctcroard);
			            $seconddate = strtotime($current_date);
			            $diff = ceil(($firstdate - $seconddate)/60/60/24);
			            if($diff < 0 || $diff == 0)
			            {
			              $color_status = 'red_status';
			              $status_label = 'Submission Late';
			              $ard_color = 'red';
			            }
			            elseif($diff <= 30)
			            {
			              $color_status = 'orange_status';
			              $status_label = 'Submission Pending';
			              $ard_color = 'orange';
			            }
			            elseif($diff > 30)
			            {
			              $color_status = 'green_status';
			              $status_label = 'Future Submission';
			              $ard_color = 'green';
			            }
			          }
			        }
					echo json_encode(array("color_status" => $color_status, "status_label" => $status_label,'updated' => $updated,'ard_color' => $ard_color,'signature_date' => ''));
				}
				else{
					$updated = 2; // Client Id not inserted in CROARD Table. Need to click on the refresh icon 
					echo json_encode(array('signature_date' => '','updated' => $updated));
				}
			}
		}
	}
	public function save_croard_signature_date(Request $request)
	{
		$client_id = $request->get('client');
		$date = explode('/', $request->get('date'));
		$data['signature_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		\App\Models\Croard::where('client_id',$client_id)->update($data);
	}
	public function croard_get_yellow_status_clients(Request $request)
	{
		$clients = \App\Models\Croard::where('signature',1)->orderBy('client_id','asc')->get();
		$output = '<table class="table own_table_white">
		<thead>
			<th>Client Code</th>
			<th>Company Name</th>
			<th>CRO Number</th>
			<th>Current CRO ARD</th>
			<th>Updated CRO ARD</th>
		</thead>
		<tbody>';
		if(($clients))
		{
			foreach($clients as $client)
			{
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->client_id)->first();
				$output.='<tr class="overlay_tr_'.$client->client_id.'">
					<td>'.$client->client_id.'</td>
					<td>'.$client->company_name.'</td>
					<td class="overlay_cro" data-element="'.$client->client_id.'">'.$client_details->cro.'</td>
					<td class="overlay_current_croard">'.$client->cro_ard.'</td>
					<td class="overlay_updated_croard"></td>
				</tr>';
			}
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function check_cro_in_api(Request $request)
	{
		$company_number = $request->get('cro');
		$indicator = 'C';
		$client_id = $request->get('client');
		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }
		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/C";
		$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
		$authenticate = $croard_settings->username.':'.$croard_settings->api_key;
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 
		$response = curl_exec($ch);
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);
		$color_status = '';
		$status_label = '';
		$cro_ard_val = date('d/m/Y', strtotime($results_array->next_ar_date));
		$updated = 0;
		$ard_color = 'color:#000';
		$cro_ard_details = \App\Models\Croard::where('client_id',$client_id)->first();
		$signature_date = '';
		if(($cro_ard_details))
		{
			if($cro_ard_val != $cro_ard_details->cro_ard)
			{
				$dataval['cro_ard'] = $cro_ard_val;
				$dataval['signature'] = 0;
				$dataval['signature_date'] = $signature_date;
				$dataval['filename'] = '';
				$dataval['url'] = '';
				$dataval['last_email_sent'] = '';
				\App\Models\Croard::where('client_id',$client_id)->update($dataval);
				$updated = 1;
			}
			else{
				$signature_date = date('Y-m-d');
				$dataval['cro_ard'] = $cro_ard_details->cro_ard;
				$dataval['signature'] = 1;
				$dataval['signature_date'] = $signature_date;
				$signature_date = date('d/m/Y', strtotime($signature_date));
				\App\Models\Croard::where('client_id',$client_id)->update($dataval);
				$updated = 0;
			}
			$expandcroard = explode('/',$cro_ard_val);
	        if(count($expandcroard) > 1)
	        {
	          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
	          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
	          $current_date = date('Y-m-d');
	          $current_year = date('Y');
	          $croard_year = $expandcroard[2];
	          if($croard_year > $current_year)
	          {
	            $color_status = 'blue_status';
	            $status_label = 'Current Year OK';
	            $ard_color = 'blue';
	          }
	          else{
	            $firstdate = strtotime($correctcroard);
	            $seconddate = strtotime($current_date);
	            $diff = ceil(($firstdate - $seconddate)/60/60/24);
	            if($diff < 0 || $diff == 0)
	            {
	              $color_status = 'red_status';
	              $status_label = 'Submission Late';
	              $ard_color = 'red';
	            }
	            elseif($diff <= 30)
	            {
	              $color_status = 'orange_status';
	              $status_label = 'Submission Pending';
	              $ard_color = 'orange';
	            }
	            elseif($diff > 30)
	            {
	              $color_status = 'green_status';
	              $status_label = 'Future Submission';
	              $ard_color = 'green';
	            }
	          }
	        }
		}
		echo json_encode(array("croard" => $cro_ard_val, "color_status" => $color_status, "status_label" => $status_label,'updated' => $updated,'ard_color' => $ard_color,'signature_date' => $signature_date));
	}
	public function save_croard_settings(Request $request)
	{
		$signature = $request->get('message_editor');
		$cc = $request->get('croard_cc_input');
		$croard_days_input = $request->get('croard_days_input');
		$data['croard_signature'] = $signature;
		$data['croard_cc_email'] = $cc;
		$data['croard_submission_days'] = $croard_days_input;
		$data['username'] = $request->get('username');
		$data['api_key'] = $request->get('api_key');

		$check_settings = DB::table('croard_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		if($check_settings) {
			DB::table('croard_settings')->where('practice_code',Session::get('user_practice_code'))->update($data); 
		}
		else{
			$data['practice_code'] = Session::get('user_practice_code');
			DB::table('croard_settings')->insert($data); 
		}


		

		return Redirect::back()->with('message_settings',"CRO ARD Settings Saved successfully.");
	}
	public function rbo_review_list(Request $request)
	{
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
		$output = '<table class="table table-fixed-header1 own_table_white" style="width:100%;margin-top:0px; background: #fff">
          <thead class="header">
              <th style="width:3.5%;text-align: left;">S.No <i class="fa fa-sort sno_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:6%;text-align: left;">Client Code <i class="fa fa-sort clientid_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:25%;text-align: left;">Company Name <i class="fa fa-sort company_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:10%;text-align: left;">Type <i class="fa fa-sort type_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:7%;text-align: left;">CRO Number <i class="fa fa-sort cro_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:10%;text-align: left;">RBO Reference <i class="fa fa-sort rbo_ref_sort" aria-hidden="true" style="float: right;"></i></th>
          </thead>                            
          <tbody id="clients_rbo_tbody">';
          $i=1;
          if(($clientlist)){              
            foreach($clientlist as $key => $client){
                $disabled='';
                $style="color:#000";
                if($client->active != "")
                {
                  if($client->active == 2)
                  {
                    $disabled='disabled_rbo_tr';
                    $style="color:#f00";
                  }
                }
                $cmp = '<spam class="company_rbo_td" style="font-style:italic;"></spam>';
                $timestampcroard = '';
                $cro_ard_details = \App\Models\Croard::where('client_id',$client->client_id)->first();
                $notes = '';
                $rbo_submission = '';
                if(($cro_ard_details))
                {
                  $notes = $cro_ard_details->notes;
                  if(strtolower($client->company) == strtolower($cro_ard_details->company_name))
                  {
                    $cmp = '<spam class="company_td" style="color:green;font-style:italic">'.$cro_ard_details->company_name.'</spam>';
                  }
                  else{
                    $cmp = '<spam class="company_td" style="color:blue;font-style:italic;font-weight:800">'.$cro_ard_details->company_name.'</spam>';
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
                }
                if($client->tye == "") { $tye = '-'; } else { $tye = $client->tye; }
                if($client->cro == "") { $croval = '-'; } else { $croval = $client->cro; }
                if($client->company == "") { $cmpval = $client->firstname.' & '.$client->surname; }
                else{ $cmpval = $client->company; }
                $output.='<tr class="edit_rbo_task '.$disabled.'" style="'.$style.'"  id="clientidtr_rbo_'.$client->client_id.'">
                    <td style="'.$style.'" class="sno_rbo_sort_val">'.$i.'</td>
                    <td style="'.$style.'" class="clientid_rbo_sort_val" align="left">'.$client->client_id.'</td>
                    <td style="'.$style.'" align="left"><spam class="company_rbo_sort_val">'.$cmpval.'</spam> <br/> '.$cmp.'</td>
                    <td style="'.$style.'" class="type_rbo_sort_val" align="left">'.$tye.'</td>
                    <td style="'.$style.'" class="cro_rbo_sort_val" align="left">'.$croval.'</td>
                    <td style="'.$style.'" class="rbo_ref_sort_val" align="left">'.$rbo_submission.'</td>
                </tr>';
                $i++;
              }              
            }
            if($i == 1)
            {
              $output.='<tr><td colspan="6" align="center">Empty</td></tr>';
            } 
          $output.='</tbody>
        </table>';
        echo $output;
	}
	public function report_csv_rbo(Request $request)
	{
		$filename = 'rbo_submission_review.csv';
		$columns = array('S.No', 'Client Code','Company Name','Type','CRO Number','RBO Reference','Activate');
		$file = fopen('public/papers/rbo_submission_review.csv', 'w');
		fputcsv($file, $columns);
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
          $i=1;
          if(($clientlist)){              
            foreach($clientlist as $key => $client){
                $act_text = 'Active';
                if($client->active != "")
                {
                  if($client->active == 2)
                  {
                    $act_text = 'Deactive';
                  }
                }
                $cmp = '';
                $timestampcroard = '';
                $cro_ard_details = \App\Models\Croard::where('client_id',$client->client_id)->first();
                $notes = '';
                $rbo_submission = '';
                if(($cro_ard_details))
                {
                  $notes = $cro_ard_details->notes;
                  if(strtolower($client->company) == strtolower($cro_ard_details->company_name))
                  {
                    $cmp = $cro_ard_details->company_name;
                  }
                  else{
                    $cmp = $cro_ard_details->company_name;
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
                }
                if($client->tye == "") { $tye = '-'; } else { $tye = $client->tye; }
                if($client->cro == "") { $croval = '-'; } else { $croval = $client->cro; }
                if($client->company == "") { $cmpval = $client->firstname.' & '.$client->surname; }
                else{ $cmpval = $client->company; }
                $columns1 = array($i,$client->client_id,"$cmpval\n$cmp",$tye,$croval,$rbo_submission,$act_text);
				fputcsv($file, $columns1);
                $i++;
            }              
          }
        fclose($file);
		echo $filename;
	}
	public function remove_croard_refresh(Request $request)
	{
		$clientid = $request->get('clientid');
		$gccid = $request->get('gccid');
		$data['cro_ard'] = '';
		$data['company_name'] = '';
		$detail = \App\Models\Croard::where('client_id',$clientid)->first();
		if(($detail))
		{
			\App\Models\Croard::where('client_id',$clientid)->update($data);
		}
	}
	public function remove_blue_croard_refresh(Request $request)
	{
		$clientid = $request->get('clientid');
		$data['cro_ard'] = '';
		$data['company_name'] = '';
		$detail = \App\Models\Croard::where('client_id',$clientid)->first();
		if(($detail))
		{
			\App\Models\Croard::where('client_id',$clientid)->update($data);
		}
	}
	public function get_client_from_cronumber(Request $request){
		$company_number = $request->get('company_number');
		$indicator_text = $request->get('indicator_text');
		$company_name = $request->get('company_name');
		$company_address = $request->get('company_address');
		$company_reg_date = $request->get('company_reg_date');
		$company_status_desc = $request->get('company_status_desc');
		$company_status_date = $request->get('company_status_date');
		$next_ar_date = $request->get('next_ar_date');
		$last_ar_date = $request->get('last_ar_date');
		$last_acc_date = $request->get('last_acc_date');
		$comp_type_desc = $request->get('comp_type_desc');
		$company_type_code = $request->get('company_type_code');
		$company_status_code = $request->get('company_status_code');
		$place_of_business = $request->get('place_of_business');
		$eircode = $request->get('eircode');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('cro',$company_number)->first();
		if(($client_details)){
			$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
			$client_id = $client_details->client_id;
			$result = \App\Models\Croard::where('client_id',$client_id)->first();
			$html = '<p>Hi '.$client_details->firstname.'</p>
			<p>Here is your company information as per the companies office</p>
			<div>
			<b>Company Number: </b><spam>'.$company_number.'</spam><br/>
			<b>Company / Business indicator: </b><spam>'.$indicator_text.'</spam><br/>
			<b>Company Name: </b><spam>'.$company_name.'</spam><br/>
			<b>Company Address: </b><spam>'.$company_address.'</spam><br/>
			<b>Company Registration Date: </b><spam>'.$company_reg_date.'</spam><br/>
			<b>Company Status: </b><spam>'.$company_status_desc.'</spam><br/>
			<b>Company Status Date: </b><spam>'.$company_status_date.'</spam><br/>
			<b>Next ARD: </b><spam>'.$next_ar_date.'</spam><br/>
			<b>Last ARD: </b><spam>'.$last_ar_date.'</spam><br/>
			<b>Accounts Upto: </b><spam>'.$last_acc_date.'</spam><br/>
			<b>Company Type: </b><spam>'.$comp_type_desc.'</spam><br/>
			<b>Company Type Code: </b><spam>'.$company_type_code.'</spam><br/>
			<b>Company Status Code: </b><spam>'.$company_status_code.'</spam><br/>
			<b>Place of Business: </b><spam>'.$place_of_business.'</spam><br/>
			<b>Eircode: </b><spam>'.$eircode.'</spam><br/>
			</div><br/>
			<p>'.$croard_settings->croard_signature.'</p>';
		    $subject = 'CROARD Company Details - '.$result->company_name.'';
	    	if($client_details->email2 != '')
			{
				$to_email = $client_details->email.','.$client_details->email2;
			}
			else{
				$to_email = $client_details->email;
			}
		    echo json_encode(["html" => $html,"to" => $to_email,'subject' => $subject,'client_id' => $client_id]);
		}
		else {
			echo json_encode(["html" => '',"to" => '','subject' => '', 'client_id' => '']);
		}
	}
	public function pdf_company_info(Request $request){
		$company_number = $request->get('company_number');
		$indicator_text = $request->get('indicator_text');
		$company_name = $request->get('company_name');
		$company_address = $request->get('company_address');
		$company_reg_date = $request->get('company_reg_date');
		$company_status_desc = $request->get('company_status_desc');
		$company_status_date = $request->get('company_status_date');
		$next_ar_date = $request->get('next_ar_date');
		$last_ar_date = $request->get('last_ar_date');
		$last_acc_date = $request->get('last_acc_date');
		$comp_type_desc = $request->get('comp_type_desc');
		$company_type_code = $request->get('company_type_code');
		$company_status_code = $request->get('company_status_code');
		$place_of_business = $request->get('place_of_business');
		$eircode = $request->get('eircode');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('cro',$company_number)->first();
		if(($client_details)){
			$client_id = $client_details->client_id;
			$result = \App\Models\Croard::where('client_id',$client_id)->first();
			$html = '
			<style> 
				@page{
					size: A4;
					margin-top: 0;
					margin-left: 0;
					margin-right: 0;
					margin-bottom: 0;
					padding: 0;
				}
				@font-face { font-family: "Roboto Regular"; font-weight: normal; src: url(\"fonts/Roboto-Regular.ttf\") format(\"truetype\"); } 
				@font-face { font-family: "Roboto Bold"; font-weight: bold; src: url(\"fonts/Roboto-Bold.ttf\") format(\"truetype\"); } 
				body{
					font-family: "Roboto Regular", sans-serif; font-weight: normal; font-size:14px;line-height:20px;
				}
				#content{
					width:100%;
					height:100%;
				}
				table td{
					padding:8px;
				}
			</style>
			<body>
				<div id="content">
					<div style="margin:40px 20px 20px 20px;">
						<h4 style="text-align:center;font-size:20px;">CRO Details</h4>
						<h4 style="text-align:center;font-size:20px;">'.$client_details->company.'</h4>
						<table style="width:80%; margin:auto; padding-top:20px;">
							<tr>
								<td style="width:40%"><b>Company Number: </b></td>
								<td style="width:60%">'.$company_number.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company / Business indicator: </b></td>
								<td style="width:60%">'.$indicator_text.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Name: </b></td>
								<td style="width:60%">'.$company_name.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Address: </b></td>
								<td style="width:60%">'.$company_address.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Registration Date: </b></td>
								<td style="width:60%">'.$company_reg_date.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Status: </b></td>
								<td style="width:60%">'.$company_status_desc.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Status Date: </b></td>
								<td style="width:60%">'.$company_status_date.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Next ARD: </b></td>
								<td style="width:60%">'.$next_ar_date.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Last ARD: </b></td>
								<td style="width:60%">'.$last_ar_date.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Accounts Upto: </b></td>
								<td style="width:60%">'.$last_acc_date.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Type: </b></td>
								<td style="width:60%">'.$comp_type_desc.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Type Code: </b></td>
								<td style="width:60%">'.$company_type_code.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Company Status Code: </b></td>
								<td style="width:60%">'.$company_status_code.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Place of Business: </b></td>
								<td style="width:60%">'.$place_of_business.'</td>
							</tr>
							<tr>
								<td style="width:40%"><b>Eircode: </b></td>
								<td style="width:60%">'.$eircode.'</td>
							</tr>
						</table>
					</div>
				</div>';
			$upload_dir = 'uploads/company_info';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.$client_id.'/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$filename = $client_id.'_company_info.pdf';
			$pdf = PDF::loadHTML($html);
			$pdf->setPaper('A4', 'portrait');
			$pdf->save($upload_dir.$filename);
			echo '<a href="'.URL::to($upload_dir.$filename).'" download style="display:none;" id="download_pdf_file">'.$filename.'</a>';
		}
		else {
		}
	}
	public function email_company_files_croard(Request $request)
	{
		$from_input = $request->get('select_user_company');
		$details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = $request->get('to_user_company').','.$request->get('cc_company');
		$sentmails = $request->get('to_user_company').', '.$request->get('cc_company');
		$subject = $request->get('subject_company'); 
		$message = $request->get('message_editor');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		if(($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = getEmailLogo('croard');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_croard', $data);
				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->Send();
			}
			$date = date('Y-m-d H:i:s');
			$client_id = $request->get('hidden_client_id_search_company');
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
			if(($client_details)){
				$datamessage['message_id'] = time();
				$datamessage['message_from'] = $from_input;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $message;
				$datamessage['client_ids'] = $client_id;
				$datamessage['primary_emails'] = $client_details->email;
				$datamessage['secondary_emails'] = $client_details->email2;
				$datamessage['date_sent'] = $date;
				$datamessage['date_saved'] = $date;
				$datamessage['source'] = "CROARD";
				$datamessage['attachments'] = '';
				$datamessage['status'] = 1;
				$datamessage['practice_code'] = Session::get('user_practice_code');
				\App\Models\Messageus::insert($datamessage);
			}
		}
	}
	public function edit_croard_header_image(Request $request) {
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

                    DB::table('croard_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
	}
	public function global_core_call_entry(Request $request) {
		$data['gcc_userid'] = Session::get('userid');
		$data['gcc_date'] = date('Y-m-d');
		$data['gcc_starttime'] = date('H:i:s');
		$data['practice_code'] = Session::get('user_practice_code');

		$id = DB::table('croard_globalcorecall')->insertGetId($data);
		echo $id;
	}
	public function update_gcc_finishtime(Request $request) {
		$gccid = $request->get('gccid');
		if($gccid != '0'){
			$datagcc['gcc_finishtime'] = date('H:i:s');
			DB::table('croard_globalcorecall')->where('id',$gccid)->update($datagcc);

			$auditdata['user_id'] =  Session::get('userid');
			$auditdata['module'] =  'CRO ARD';
			$auditdata['event'] =  'Global Core Call';
			$auditdata['reference'] = 'Clients updated with ARD Date from CRO';

			DB::table('audit_trails')->insert($auditdata);

		}
	}
	public function show_global_corecall_details(Request $request) {
		$corecalls = DB::table('croard_globalcorecall')->where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
	      if(is_countable($corecalls) && count($corecalls) > 0) {
	        foreach($corecalls as $corecall) {
	          $userDetails = DB::table("user")->where('user_id',$corecall->gcc_userid)->first();
	          $output.= '<tr>
	            <td>'.$userDetails->lastname.' '.$userDetails->firstname.'</td>
	            <td>'.date('d-M-Y', strtotime($corecall->gcc_date)).'</td>
	            <td>'.date('H:i', strtotime($corecall->gcc_starttime)).'</td>
	            <td>'.date('H:i', strtotime($corecall->gcc_finishtime)).'</td>
	          </tr>';
	        }
	      }
	    echo $output;
	}
}