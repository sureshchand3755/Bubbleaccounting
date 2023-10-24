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
use App\Jobs\SendNotifyBankEmailJob;
class AllocationController extends Controller {
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
	public function allocation_system(Request $request)
	{
		return view('user/allocations/allocation_system', array('title' => 'Bubble - Allocations System'));
	}
	
	public function load_allocation_clients(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));

		$from_month = $request->get('from');
		$from_month = date('M-Y', strtotime($from_month));
        $from_monthh = date('Y-m-01', strtotime($from_month));
        $from_str_month = $from_month;

        $to_month = $request->get('to');
		$to_month = date('M-Y', strtotime($to_month));

		$to_month = date('M-Y', strtotime($to_month));
        $to_monthh = date('Y-m-01', strtotime($to_month));
        
        $to_str_month = $to_month;


        $edate = strtotime($to_str_month);
        $bdate = strtotime($from_str_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));

        $thval = '<th style="text-align:right;width:250px">Total</th>';
        $age = $age + 1;
        for($i= 1; $i<=$age; $i++)
        {
          $thval.= '<th style="text-align:right;width:250px">'.$to_month.'</th>';
          $to_month = date('M-Y', strtotime('first day of previous month', strtotime($to_month)));
        }
		$output = '
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th style="width: 45px;">S.No</th>
					<th style="width: 80px;">ClientID</th>
					<th><p style="width:500px;line-height: 0px;">Account/Client</p></th>
					'.$thval.'
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
			if(($clients))
			{
				$ival = 1;
				foreach($clients as $client)
				{
					$to_mon = date('Y-m', strtotime($to_str_month));
					if($client->active != "")
					{
						if($client->active==2){
							$check_color = \App\Models\CMClass::where('id',$client->active)->first();
							$style="font-weight:bold;color:#".$check_color->classcolor."";
						}	
						else{
							$style="color:#000";
						}					
					}
					else{
						$style="color:#000";
					}
					$total_receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('receipt_date','>=',$from_monthh)->where('receipt_date','<=',$to_monthh)->sum('amount');

					$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'" style="'.$style.'">
						<td>'.$ival.'</td>
						<td>'.$client->client_id.'</td>
						<td>'.$client->company.'</td>
						<td style="text-align:right">'.number_format_invoice($total_receipt_details).'</td>';
						for($i= 1; $i<=$age; $i++)
				        {
				        	$monthly_receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('receipt_date','like',$to_mon.'%')->sum('amount');
				        	$output.='<td style="text-align:right">'.number_format_invoice($monthly_receipt_details).'</td>';
				        	$to_mon_value = $to_mon.'-01';
				          	$to_mon = date('Y-m', strtotime('first day of previous month', strtotime($to_mon_value)));
				        }
					$output.='</tr>';
					$ival++;
				}
			}
			$output.='</tbody>
		</table>';
		echo $output;
	}
	public function export_allocation_clients(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));

		$from_month = $request->get('from');
		$from_month = date('M-Y', strtotime($from_month));
        $from_monthh = date('Y-m-01', strtotime($from_month));
        $from_str_month = $from_month;

        $to_month = $request->get('to');
		$to_month = date('M-Y', strtotime($to_month));

		$to_month = date('M-Y', strtotime($to_month));
        $to_monthh = date('Y-m-01', strtotime($to_month));
        
        $to_str_month = $to_month;

        $filename = time().'_Allocations Data From '.date('M-Y', strtotime($from_monthh)).' to '.date('M-Y', strtotime($to_monthh)).'.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');

		$columns_1 = array('S.No','ClientID','Account/Client','Total');
        $edate = strtotime($to_str_month);
        $bdate = strtotime($from_str_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));

        $age = $age + 1;
        for($i= 1; $i<=$age; $i++)
        {
          array_push($columns_1,$to_month);
          $to_month = date('M-Y', strtotime('first day of previous month', strtotime($to_month)));
        }

        fputcsv($file, $columns_1);

		if(($clients))
		{
			$ival = 1;
			foreach($clients as $client)
			{
				$to_mon = date('Y-m', strtotime($to_str_month));
				$total_receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('receipt_date','>=',$from_monthh)->where('receipt_date','<=',$to_monthh)->sum('amount');
				$columns_2 = array($ival,$client->client_id,$client->company,number_format_invoice($total_receipt_details));
				for($i= 1; $i<=$age; $i++)
		        {
		        	$monthly_receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('receipt_date','like',$to_mon.'%')->sum('amount');
		        	array_push($columns_2,number_format_invoice($monthly_receipt_details));
		        	$to_mon_value = $to_mon.'-01';
		          	$to_mon = date('Y-m', strtotime('first day of previous month', strtotime($to_mon_value)));
		        }
		        fputcsv($file, $columns_2);
				$ival++;
			}
		}
		fclose($file);
		echo $filename;
	}

	public function export_statement_clients(Request $request){
		$ival = 1;
		$current_month = $request->get('current_month');
		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('m-Y', strtotime($current_month));
        $curr_str_month = $current_month;
        $opening_month =\App\Models\userLogin::first();
		$filename = time().'_Statement List From '.date('M-Y', strtotime($opening_month->opening_balance_date)).' to '.$current_month.'.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		if(($clients))
		{
			foreach($clients as $key => $client)
			{
				$client_id = $client->client_id;
				$opening_month =\App\Models\userLogin::first();
		        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
		        $edate = strtotime($curr_str_month);
		        $bdate = strtotime($opening_bal_month);
		        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
				$opening_bal = '0.00';
				$client_opening_balance = '0.00';
				$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				if(($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = number_format_invoice($opening_bal_details->opening_balance);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$client_opening_balance = number_format_invoice($opening_bal_details->opening_balance);
					}
				}
				$thval_array = [];
				$thval_divide_array = [];
				$tdval_divide_array = [];
		        $invoice_details = \App\Models\InvoiceSystem::where('practice_code', Session::get('user_practice_code'))->where('client_id',$client_id)->where('invoice_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('gross');
		        $receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('amount');
		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        if($key == 0)
		        {
		        	$thval = date('M-Y', strtotime($opening_bal_month));
		        	array_push($thval_array, "");
		        	array_push($thval_array, "");
			        array_push($thval_array, $thval);
		        	array_push($thval_array, "");
		        	array_push($thval_array, "");
			        array_push($thval_divide_array, "Build");
			        array_push($thval_divide_array, "Statement Sent");
			        array_push($thval_divide_array, "Closing Balance");
			        array_push($thval_divide_array, "Received");
			        array_push($thval_divide_array, "Invoices");
		        }
		        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($opening_bal_month)))->first();
		    	if(($check_attachment))
		    	{
		    		$build_button = substr($check_attachment->filename,0,15);
		    	}
		    	else{
		    		$build_button = '';
		    	}
		        array_push($tdval_divide_array, $build_button);
		        array_push($tdval_divide_array, "");
		        array_push($tdval_divide_array, number_format_invoice($closing_bal));
		        array_push($tdval_divide_array, number_format_invoice($receipt_details));
		        array_push($tdval_divide_array, number_format_invoice($invoice_details));
		        $opening_bal = $closing_bal;
		        for($i= $age; $i>=1; $i--)
		        {
		          	$dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		          	$datevall = date('Y-m', strtotime('first day of next month', strtotime($opening_bal_month)));
		          	$datevalll = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		            $invoice_details = \App\Models\InvoiceSystem::where('practice_code', Session::get('user_practice_code'))->where('client_id',$client_id)->where('invoice_date','like',$datevall.'%')->sum('gross');
			        $receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$datevall.'%')->sum('amount');
			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        if($key == 0)
		        	{
			        	array_push($thval_array, "");
			        	array_push($thval_array, "");
				        array_push($thval_array, $datevalll);
			        	array_push($thval_array, "");
			        	array_push($thval_array, "");
				        array_push($thval_divide_array, "Build");
				        array_push($thval_divide_array, "Statement Sent");
				        array_push($thval_divide_array, "Closing Balance");
				        array_push($thval_divide_array, "Received");
				        array_push($thval_divide_array, "Invoices");
				    }
			        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',$dateval)->first();
					if(($check_attachment))
					{
						$build_button = substr($check_attachment->filename,0,15);
					}
					else{
						$build_button = '';
					}
			        array_push($tdval_divide_array, $build_button);
			        array_push($tdval_divide_array, "");
			        array_push($tdval_divide_array, number_format_invoice($closing_bal));
			        array_push($tdval_divide_array, number_format_invoice($receipt_details));
			        array_push($tdval_divide_array, number_format_invoice($invoice_details));
		          	$opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
		          	$opening_bal = $closing_bal;
		        }
		        $statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
				if(($statement_details))
				{
					$primary = $statement_details->email;
					$secondary = $statement_details->email2;
				}
				else{
					$primary = $client->email;
					$secondary = $client->email2;
				}
				if($client->send_statement == "1") { $send_statement = 'Yes'; } else { $send_statement = 'No'; }
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_array, "");
				array_push($thval_divide_array, "Opening Balance");
				array_push($thval_divide_array, "Send Statement");
				array_push($thval_divide_array, "Secondary Email");
				array_push($thval_divide_array, "Primary Email");
				array_push($thval_divide_array, "Company Name");
				array_push($thval_divide_array, "ClientID");
				array_push($thval_divide_array, "Sno");
				array_push($tdval_divide_array, $client_opening_balance);
				array_push($tdval_divide_array, $send_statement);
				array_push($tdval_divide_array, $secondary);
				array_push($tdval_divide_array, $primary);
				array_push($tdval_divide_array, $client->company);
				array_push($tdval_divide_array, $client_id);
				array_push($tdval_divide_array, $ival);
		        if($key == 0)
		        {
			        $thval_reverse = array_reverse($thval_array);
			        $thval_divide_reverse = array_reverse($thval_divide_array);
			    }
		        $tdval_divide_reverse = array_reverse($tdval_divide_array);
		        if($key == 0){
	    			fputcsv($file, $thval_reverse);
	    			fputcsv($file, $thval_divide_reverse);
		        }
		        fputcsv($file, $tdval_divide_reverse);
		        $ival++;
			}
		}
		fclose($file);
        echo $filename;
	}

	public function get_receipt_list_statement(Request $request)
	{
		$client = $request->get('client');
		$get_data_from = $request->get('get_data_from');
		if($get_data_from==''){
			$month = explode('-',$request->get('month'));
			$monthval = $month[1].'-'.$month[0];
			$get_title_month = date('M-Y', strtotime($month[1].'-'.$month[0].'-01'));
			$receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client)->where('credit_nominal','712')->where('receipt_date','like',$monthval.'%')->get();
		}
		else{
			$month = explode('_',$request->get('month'));
			$from_monthval='';$to_monthval='';$get_title_month='';
			if(($month)==2){
				$from=explode('-',$month[0]);
				$from_monthval = $from[1].'-'.$from[0].'-01';
				$get_title_month_from = date('M-Y', strtotime($from[1].'-'.$from[0].'-01'));
				$to=explode('-',$month[1]);
				$to_monthval = $to[1].'-'.$to[0].'-01';
				$to_monthval = date('Y-m-t', strtotime($to_monthval));
				$get_title_month_to = date('M-Y', strtotime($to[1].'-'.$to[0].'-01'));
				$get_title_month = $get_title_month_from.' - '.$get_title_month_to;
			}
			$receipt_details = \App\Models\receipts::where('practice_code', Session::get('user_practice_code'))->where('client_code',$client)->where('credit_nominal','712')
				->whereRaw("receipt_date BETWEEN '".$from_monthval."' AND '".$to_monthval."'")->get();
		}
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
		$output = '';
		$total_amount = 0;
		if(($receipt_details))
		{
			foreach($receipt_details as $receipt)
			{
				$output.='
				<tr>
				<td>'.date('d-M-Y', strtotime($receipt->receipt_date)).'</td>
				<td>'.$receipt->debit_nominal.'</td>
				<td>'.$receipt->credit_nominal.'</td>
				<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
				</tr>';
				$total_amount = $total_amount + $receipt->amount;
			}
		}
		else{
			$output.= '<tr>
				<td colspan="5">No Receipts Found</td>
			</tr>';
		}
		$data['output'] = $output;
		$data['total_amount'] = number_format_invoice($total_amount);
		$data['title'] = 'Receipt List - '.$client.' '.$client_details->company.' - '.$get_title_month;
		echo json_encode($data);
	}
}