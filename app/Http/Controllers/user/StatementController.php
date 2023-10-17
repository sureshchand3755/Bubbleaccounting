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
class StatementController extends Controller {
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
	public function statement_list(Request $request)
	{
		return view('user/statement/current_view_statement', array('title' => 'Bubble - Client Statements'));
	}
	public function full_view_statement(Request $request)
	{
		return view('user/statement/full_view_statement', array('title' => 'Bubble - Client Statements'));
	}
	public function monthly_statement(Request $request)
	{
		return view('user/statement/monthly_view_statement', array('title' => 'Bubble - Client Statements'));
	}
	public function client_specific_statement(Request $request)
	{
		return view('user/statement/client_specific_statement', array('title' => 'Bubble - Client Statements'));
	}
	public function load_statement_clients_current_view(Request $request)
	{
		$page = $request->get('page');
		$prevpage = $page - 1;
		$offset = $prevpage * 100;
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->offset($offset)->limit(100)->orderBy('id','asc')->get();
		$current_month = $request->get('current_month');
		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('Y-m', strtotime($current_month));
        $curr_str_month = $current_month;
        $opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));
        $from_date = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
        $to_date = date('Y-m-d', strtotime('last day of previous month', strtotime($curr_str_month)));
        $thval = '
        <th colspan="3" class="text-center" style="border-right:1px solid #d9d9d9">Post Month Activity</th>
        <th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($curr_str_month)).'</th>';
        $thval_divide = '
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Invoices</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Received</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:105px;text-align:right">Closing Balance</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Invoices</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Received</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:105px;text-align:right">Closing Balance</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
        if($page == 1) {
		$output = '
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="border-right:1px solid #d9d9d9"></th>
					'.$thval.'
				</tr>
				<tr>
					<th><p style="width: 45px;">S.No</p></th>
					<th><p style="width: 80px;">ClientID</p></th>
					<th><p style="width: 80px;">ActiveClient</p></th>
					<th><p style="width: 300px;">Company Name</p></th>
					<th><p style="width: 250px;">Primary Email</p></th>
					<th><p style="width: 175px;">Secondary Email</p></th>
					<th><p style="width: 105px;">Send Statement</p></th>
					<th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width: 115px;">Opening Balance</p></th>
					'.$thval_divide.'
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
		}else{
			$output = '';
		}
			if(($clients))
			{
				$i = $offset + 1;
				foreach($clients as $client)
				{
					$client_id = $client->client_id;
					$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
					$opening_bal = '0.00';
					if(($opening_bal_details))
					{
						if($opening_bal_details->opening_balance != "")
						{
							$opening_bal = $opening_bal_details->opening_balance;
						}
					}
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
					$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
					if(($statement_details))
					{
						$primary = $statement_details->email;
						$secondary = $statement_details->email2;
						$salutation = $statement_details->salutation;
					}
					else{
						$primary = $client->email;
						$secondary = $client->email2;
						$salutation = $client->salutation;
					}
					if($client->send_statement == 1) { $cecked = 'checked'; } else { $cecked = '';  }
					$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('gross');
			        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_date)->where('receipt_date','<=',$to_date)->sum('amount');
			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        $invoice_curr_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',$current_monthh.'%')->sum('gross');
			        $receipt_curr_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$current_monthh.'%')->sum('amount');
			        $closing_curr_bal = ($closing_bal + $invoice_curr_details) - $receipt_curr_details;
			        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($curr_str_month)))->first();
			    	if(($check_attachment))
			    	{
			    		$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.date('m-Y', strtotime($curr_str_month)).'" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($curr_str_month)).'" data-balance="'.$closing_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
			    	}
			    	else{
			    		$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($curr_str_month)).'" data-balance="'.$closing_bal.'">Build</a>';
			    	}
					$tdval_divide = '<td style="text-align:right;">'.number_format_invoice($invoice_details).'</td>
				        <td style="text-align:right;">'.number_format_invoice($receipt_details).'</td>
				        <td style="text-align:right;border-right:1px solid #d9d9d9">'.number_format_invoice($closing_bal).'</td>
				        <td style="text-align:right;" class="invoice invoice_td invoice_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">'.number_format_invoice($invoice_curr_details).'</td>
				        <td style="text-align:right;" class="received received_td received_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">'.number_format_invoice($receipt_curr_details).'</td>
				        <td style="text-align:right;" class="closing_bal closing_bal_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">'.number_format_invoice($closing_curr_bal).'</td>
				        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">
				        </td>
				        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'" style="border-right:1px solid #d9d9d9">
				        	'.$build_button.'
				        </td>';
					$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'" style="'.$style.'">
						<td>'.$i.'</td>
						<td>'.$client->client_id.'</td>
						<td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
						<td>'.$client->company.'</td>
						<td>'.$primary.'</td>
						<td>'.$secondary.'</td>
						<td><input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement'.$client->client_id.'" data-client="'.$client->client_id.'" value="" '.$cecked.'><label style="font-size: 16px;" for="ok_to_send_statement'.$client->client_id.'">&nbsp;</label></td>
						<td class="opening_bal_td" style="text-align:right;border-right:1px solid #d9d9d9">'.number_format_invoice_empty($opening_bal).'</td>
						'.$tdval_divide.'
					</tr>';
					$i++;
				}
			}
		if($page == 1) {
			$output.='</tbody>
		</table>';
		}
		$clientslist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$dataval['output'] = $output;
		$dataval['pagecount'] = ceil(count($clientslist) / 100);
		$dataval['total_clients'] = count($clientslist);
		$dataval['options'] = '<option value="">Select Month and Year</option><option value="'.date('Y-m-01', strtotime($curr_str_month)).'">'.date('M-Y', strtotime($curr_str_month)).'</option>';
		echo json_encode($dataval);
	}
	public function load_statement_clients_monthly_view(Request $request)
	{
		$page = $request->get('page');
		$prevpage = $page - 1;
		$offset = $prevpage * 100;
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->offset($offset)->limit(100)->orderBy('id','asc')->get();
		$from_month = $request->get('from_month');
		$to_month = $request->get('to_month');
		$ud['statement_monthly_from_period'] = $from_month;
		$ud['statement_monthly_to_period'] = $to_month;
		\App\Models\userLogin::where('id',1)->update($ud);
		$from_month = date('M-Y', strtotime($from_month));
        $from_monthh = date('Y-m', strtotime($from_month));
        $from_str_month = $from_month;
        $to_month = date('M-Y', strtotime($to_month));
        $to_monthh = date('Y-m', strtotime($to_month));
        $to_str_month = $to_month;
        $opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));
        $from_date = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
        $to_date = date('Y-m-d', strtotime('last day of previous month', strtotime($from_str_month)));
        $from_date_inv = $from_monthh.'-01';
        $to_date_inv = date('Y-m-t', strtotime($to_str_month));
        $month_year = date('m-Y', strtotime($from_str_month)).'_'.date('m-Y', strtotime($to_str_month));$month_year = date('m-Y', strtotime($from_str_month)).'_'.date('m-Y', strtotime($to_str_month));
        $thval = '
        <th colspan="3" class="text-center" style="border-right:1px solid #d9d9d9">Post Month Activity</th>
        <th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($from_str_month)).' - '.date('M-Y', strtotime($to_str_month)).'</th>';
        $thval_divide = '
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Invoices</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Received</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:105px;text-align:right">Closing Balance</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Invoices</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:90px;text-align:right">Received</p></th>
        <th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width:105px;text-align:right">Closing Balance</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
        if($page == 1) {
		$output = '
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="border-right:1px solid #d9d9d9"></th>
					'.$thval.'
				</tr>
				<tr>
					<th><p style="width: 45px;">S.No</p></th>
					<th><p style="width: 80px;">ClientID</p></th>
					<th><p style="width: 80px;">ActiveClient</p></th>
					<th><p style="width: 300px;">Company Name</p></th>
					<th><p style="width: 250px;">Primary Email</p></th>
					<th><p style="width: 175px;">Secondary Email</p></th>
					<th><p style="width: 105px;">Send Statement</p></th>
					<th style="text-align:right;border-right:1px solid #d9d9d9"><p style="width: 115px;">Opening Balance</p></th>
					'.$thval_divide.'
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
		}else{
			$output = '';
		}
			if(($clients))
			{
				$i = $offset + 1;
				foreach($clients as $client)
				{
					$client_id = $client->client_id;
					$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
					$opening_bal = '0.00';
					if(($opening_bal_details))
					{
						if($opening_bal_details->opening_balance != "")
						{
							$opening_bal = $opening_bal_details->opening_balance;
						}
					}
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
					$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
					if(($statement_details))
					{
						$primary = $statement_details->email;
						$secondary = $statement_details->email2;
						$salutation = $statement_details->salutation;
					}
					else{
						$primary = $client->email;
						$secondary = $client->email2;
						$salutation = $client->salutation;
					}
					if($client->send_statement == 1) { $cecked = 'checked'; } else { $cecked = '';  }
					$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('gross');
			        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_date)->where('receipt_date','<=',$to_date)->sum('amount');
			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        $invoice_curr_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_date_inv)->where('invoice_date','<=',$to_date_inv)->sum('gross');
			        $receipt_curr_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_date_inv)->where('receipt_date','<=',$to_date_inv)->sum('amount');
			        $closing_curr_bal = ($closing_bal + $invoice_curr_details) - $receipt_curr_details;
			        
			        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',$month_year)->first();
			    	if(($check_attachment))
			    	{
			    		$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.$month_year.'" data-client="'.$client_id.'" data-element="'.$month_year.'" data-balance="'.$closing_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
			    	}
			    	else{
			    		$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.$month_year.'" data-balance="'.$closing_bal.'">Build</a>';
			    	}
					$tdval_divide = '<td style="text-align:right;">'.number_format_invoice($invoice_details).'</td>
				        <td style="text-align:right;">'.number_format_invoice($receipt_details).'</td>
				        <td style="text-align:right;border-right:1px solid #d9d9d9">'.number_format_invoice($closing_bal).'</td>
				        <td style="text-align:right;" class="invoice invoice_td invoice_'.$month_year.'" data-month="'.$month_year.'">'.number_format_invoice($invoice_curr_details).'</td>
				        <td style="text-align:right;" class="received received_td received_'.$month_year.'" data-month="'.$month_year.'">'.number_format_invoice($receipt_curr_details).'</td>
				        <td style="text-align:right;" class="closing_bal closing_bal_'.$month_year.'" data-month="'.$month_year.'">'.number_format_invoice($closing_curr_bal).'</td>
				        <td class="statement_sent statement_sent_'.$month_year.'" data-month="'.$month_year.'">
				        </td>
				        <td class="build_statement_td build_statement_td_'.$month_year.'" data-month="'.$month_year.'" style="border-right:1px solid #d9d9d9">
				        	'.$build_button.'
				        </td>';
					$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'" style="'.$style.'">
						<td>'.$i.'</td>
						<td>'.$client->client_id.'</td>
						<td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
						<td>'.$client->company.'</td>
						<td>'.$primary.'</td>
						<td>'.$secondary.'</td>
						<td><input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement'.$client->client_id.'" data-client="'.$client->client_id.'" value="" '.$cecked.'><label style="font-size: 16px;" for="ok_to_send_statement'.$client->client_id.'">&nbsp;</label></td>
						<td class="opening_bal_td" style="text-align:right;border-right:1px solid #d9d9d9">'.number_format_invoice_empty($opening_bal).'</td>
						'.$tdval_divide.'
					</tr>';
					$i++;
				}
			}
		if($page == 1) {
			$output.='</tbody>
		</table>';
		}
		$clientslist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$dataval['output'] = $output;
		$dataval['pagecount'] = ceil(count($clientslist) / 100);
		$dataval['total_clients'] = count($clientslist);
		$dataval['options'] = 'Statement functions for the month '.$from_month.' to '.$to_month.'';
		$dataval['optionvalue'] = $month_year;
		echo json_encode($dataval);
	}
	public function load_statement_clients(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$current_month = $request->get('current_month');
		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('m-Y', strtotime($current_month));
        $curr_str_month = $current_month;
        $opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
        $edate = strtotime($curr_str_month);
        $bdate = strtotime($opening_bal_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($curr_str_month)).'</th>';
        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="received received_td received_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
        for($i= 1; $i<=$age; $i++)
        {
        	if($i == $age) { $displaynone = 'displaynone'; } else{ $displaynone = ''; }
          $dateval = date('m-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevall = date('M-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevalll = date('Y-m-01', strtotime('first day of previous month', strtotime($curr_str_month)));
          $thval.= '<th colspan="5" class="text-center '.$displaynone.'" style="border-right:1px solid #d9d9d9">'.$datevall.'</th>';
          $thval_divide.= '<th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
          $tdval_divide.= '<td class="'.$displaynone.' invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' received received_td received_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
          $curr_str_month = date('Y-m-d', strtotime('first day of previous month', strtotime($curr_str_month)));
        }
		$output = '
		<div id="demoItem3" style="float:left;width:1000px;position:fixed">
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="border-right:1px solid #d9d9d9"></th>
				</tr>
				<tr>
					<th><p style="width: 45px;">S.No</p></th>
					<th><p style="width: 80px;">ClientID</p></th>
					<th><p style="width: 80px;">ActiveClient</p></th>
					<th><p style="width: 300px;">Company Name</p></th>
					<th><p style="width: 250px;">Primary Email</p></th>
					<th><p style="width: 175px;">Secondary Email</p></th>
					<th><p style="width: 105px;">Send Statement</p></th>
					<th style="border-right:1px solid #d9d9d9"><p style="width: 115px;">Opening Balance</p></th>
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
			if(($clients))
			{
				$i = 1;
				foreach($clients as $client)
				{
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
					$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
					if(($statement_details))
					{
						$primary = $statement_details->email;
						$secondary = $statement_details->email2;
						$salutation = $statement_details->salutation;
					}
					else{
						$primary = $client->email;
						$secondary = $client->email2;
						$salutation = $client->salutation;
					}
					if($client->send_statement == 1) { $cecked = 'checked'; } else { $cecked = '';  }
					$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'" style="'.$style.'">
						<td>'.$i.'</td>
						<td>'.$client->client_id.'</td>
						<td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
						<td>'.$client->company.'</td>
						<td>'.$primary.'</td>
						<td>'.$secondary.'</td>
						<td><input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement'.$client->client_id.'" data-client="'.$client->client_id.'" value="" '.$cecked.'><label style="font-size: 16px;" for="ok_to_send_statement'.$client->client_id.'">&nbsp;</label></td>
						<td class="opening_bal_td" style="border-right:1px solid #d9d9d9"></td>
					</tr>';
					$i++;
				}
			}
			$output.='</tbody>
		</table>
		</div>
		<div style="float:left;max-width:2500px;margin-left:1279;">
			<table class="table table-fixed-header own_table_white" id="own_table_white2">
				<thead>
					<tr class="tr_header_1">
						'.$thval.'
					</tr>
					<tr class="tr_header_2">
						'.$thval_divide.'
					</tr>
				</thead>
				<tbody>';
				if(($clients))
				{
					$i = 1;
					foreach($clients as $client)
					{
						$output.='<tr class="client_value_tr client_value_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">'.$tdval_divide.'
						</tr>';
						$i++;
					}
				}
				$output.='</tbody>
			</table>
		</div>';
		echo $output;
	}
	public function load_statement_client_single(Request $request)
	{
		$client_id = $request->get('client_id');
		$client = \App\Models\CMClients::where('client_id',$client_id)->first();
		$current_month = $request->get('current_month');
		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('m-Y', strtotime($current_month));
        $curr_str_month = $current_month;
        $opening_month =\App\Models\userLogin::first();
        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
        $edate = strtotime($curr_str_month);
        $bdate = strtotime($opening_bal_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($curr_str_month)).'</th>';
        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="received received_td received_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
        for($i= 1; $i<=$age; $i++)
        {
        	if($i == $age) { $displaynone = 'displaynone'; } else{ $displaynone = ''; }
          $dateval = date('m-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevall = date('M-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevalll = date('Y-m-01', strtotime('first day of previous month', strtotime($curr_str_month)));
          $thval.= '<th colspan="5" class="text-center '.$displaynone.'" style="border-right:1px solid #d9d9d9">'.$datevall.'</th>';
          $thval_divide.= '<th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
          <th class="'.$displaynone.'" style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
          $tdval_divide.= '<td class="'.$displaynone.' invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' received received_td received_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="'.$displaynone.' build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
          $curr_str_month = date('Y-m-d', strtotime('first day of previous month', strtotime($curr_str_month)));
        }
		$output = '
		<div id="demoItem3" style="float:left;width:1000px;position:fixed">
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="border-right:1px solid #d9d9d9"></th>
				</tr>
				<tr>
					<th><p style="width: 45px;">S.No</p></th>
					<th><p style="width: 80px;">ClientID</p></th>
					<th><p style="width: 80px;">ActiveClient</p></th>
					<th><p style="width: 300px;">Company Name</p></th>
					<th><p style="width: 250px;">Primary Email</p></th>
					<th><p style="width: 175px;">Secondary Email</p></th>
					<th><p style="width: 105px;">Send Statement</p></th>
					<th style="border-right:1px solid #d9d9d9"><p style="width: 115px;">Opening Balance</p></th>
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
			if(($client))
			{
				$i = 1;
				$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
				if(($statement_details))
				{
					$primary = $statement_details->email;
					$secondary = $statement_details->email2;
					$salutation = $statement_details->salutation;
				}
				else{
					$primary = $client->email;
					$secondary = $client->email2;
					$salutation = $client->salutation;
				}
				if($client->send_statement == 1) { $cecked = 'checked'; } else { $cecked = '';  }
				$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = number_format_invoice_empty($opening_bal_details->opening_balance);
					}
				}
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
				$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'" style="'.$style.'">
					<td>'.$i.'</td>
					<td>'.$client->client_id.'</td>
					<td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
					<td>'.$client->company.'</td>
					<td>'.$primary.'</td>
					<td>'.$secondary.'</td>
					<td><input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement'.$client->client_id.'" data-client="'.$client->client_id.'" value="" '.$cecked.'><label style="font-size: 16px;" for="ok_to_send_statement'.$client->client_id.'">&nbsp;</label></td>
					<td class="opening_bal_td" style="border-right:1px solid #d9d9d9">'.$opening_bal.'</td>
				</tr>';
				$i++;
			}
			$output.='</tbody>
		</table>
		</div>
		<div style="float:left;max-width:2500px;margin-left:1279px;">
			<table class="table table-fixed-header own_table_white" id="own_table_white2">
				<thead>
					<tr class="tr_header_1">
						'.$thval.'
					</tr>
					<tr class="tr_header_2">
						'.$thval_divide.'
					</tr>
				</thead>
				<tbody>';
				if(($client))
				{
					$i = 1;
					$output.='<tr class="client_value_tr client_value_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">'.$tdval_divide.'
					</tr>';
					$i++;
				}
				$output.='</tbody>
			</table>
		</div>';
		echo $output;
	}
	public function get_client_opening_balance(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$opening_array = array();
		if(($clients))
		{
			$i = 1;
			foreach($clients as $client)
			{
				$client_id = $client->client_id;
				$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = number_format_invoice_empty($opening_bal_details->opening_balance);
					}
				}
				array_push($opening_array,$opening_bal);
			}
		}
		echo json_encode($opening_array);
	}
	public function get_client_statement_values(Request $request)
	{
		$count = $request->get('count');
		$limit = 100;
		$offset = $count * 100;
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->offset($offset)->limit($limit)->get();
		$data = [];
		if(($clients))
		{
			foreach($clients as $key => $client)
			{
				$client_id = $client->client_id;
				$current_month = $request->get('current_month');
				$current_month = date('M-Y', strtotime($current_month));
		        $current_monthh = date('m-Y', strtotime($current_month));
		        $curr_str_month = $current_month;
		        $opening_month =\App\Models\userLogin::first();
		        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
		        $edate = strtotime($curr_str_month);
		        $bdate = strtotime($opening_bal_month);
		        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
		        $opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
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
					}
				}
				$thval_array = array();
				$thval_divide_array = array();
				$tdval_divide_array = array();
		        $invoice_details = 0;
		        $receipt_details = 0;
		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        $options = [];
		        if($count == 0)
		        {
		        	$thval = '<th class="displaynone" colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($opening_bal_month)).'</th>';
			        array_push($thval_array, $thval);
			        $thval_divide = '<th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
			        array_push($thval_divide_array, $thval_divide);
		        }
		        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($opening_bal_month)))->first();
		    	if(($check_attachment))
		    	{
		    		$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.date('m-Y', strtotime($opening_bal_month)).'" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($opening_bal_month)).'" data-balance="'.$opening_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
		    	}
		    	else{
		    		$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($opening_bal_month)).'" data-balance="'.$opening_bal.'">Build</a>';
		    	}
		    	if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
		    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
		    	else { $inv_color= ''; }
		    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
		    	elseif($receipt_details < 0) { $inv_color = 'color:green;'; }
		    	else { $rec_color= ''; }
		        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$inv_color.'">
		        	'.number_format_invoice($invoice_details).'
		        </td>
		        <td class="received received_td received_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$rec_color.'">
		        	'.number_format_invoice($receipt_details).'
		        </td>
		        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        	'.number_format_invoice($closing_bal).'
		        </td>
		        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        </td>
		        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="border-right:1px solid #d9d9d9">
		        	'.$build_button.'
		        </td>';
		        array_push($tdval_divide_array, $tdval_divide);
		        $opening_bal = $closing_bal;
		        for($i= $age; $i>=1; $i--)
		        {
					$dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
					$datevall = date('Y-m', strtotime('first day of next month', strtotime($opening_bal_month)));
					$datevalll = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		            $invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',$datevall.'%')->sum('gross');
			        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$datevall.'%')->sum('amount');
			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        if($count == 0)
		        	{
		        		$optionval = '<option value="'.date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month))).'">'.date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month))).'</option>';
		        		array_push($options,$optionval);
				        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.$datevalll.'</th>';
				        array_push($thval_array, $thval);
				        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
				        array_push($thval_divide_array, $thval_divide);
				    }
			        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',$dateval)->first();
					if(($check_attachment))
					{
						$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.$dateval.'" data-client="'.$client_id.'" data-element="'.$dateval.'" data-balance="'.$opening_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
					}
					else{
						$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.$dateval.'" data-balance="'.$opening_bal.'">Build</a>';
					}
					if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
			    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
			    	else { $inv_color= ''; }
			    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
			    	else { $rec_color= ''; }
					$tdval_divide = '<td class="invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'" style="'.$inv_color.'">
						'.number_format_invoice($invoice_details).'
					</td>
			        <td class="received received_td received_'.$dateval.'" data-month="'.$dateval.'" style="'.$rec_color.'">
			        	'.number_format_invoice($receipt_details).'
			        </td>
			        <td class="closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">
			        	'.number_format_invoice($closing_bal).'
			        </td>
			        <td class="statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">
			        </td>
			        <td class="build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">
			        	'.$build_button.'
			        </td>';
			        array_push($tdval_divide_array, $tdval_divide);
		          $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $opening_bal = $closing_bal;
		        }
		        if($count == 0)
		        {
			        $thval_reverse = implode("",array_reverse($thval_array));
			        $thval_divide_reverse = implode("",array_reverse($thval_divide_array));
			        $optreverse = implode("",array_reverse($options));
			        $data[$key]['options'] = $optreverse;
			        $data[$key]['thval'] = $thval_reverse;
		        	$data[$key]['thval_divide'] = $thval_divide_reverse;
			    }
		        $tdval_divide_reverse = implode("",array_reverse($tdval_divide_array));
		        $data[$key]['tdval_divide'] = $tdval_divide_reverse;
		        $data[$key]['client_id'] = $client_id;
			}
		}
        echo json_encode($data);
	}
	public function get_client_statement_values_single(Request $request)
	{
		$client_id = $request->get('client_id');
		$client = \App\Models\CMClients::where('client_id',$client_id)->first();
		if(($client))
		{
				$data['client_email'] = $client->email;
				$client_id = $client->client_id;
				$current_month = $request->get('current_month');
				$current_month = date('M-Y', strtotime($current_month));
		        $current_monthh = date('m-Y', strtotime($current_month));
		        $curr_str_month = $current_month;
		        $opening_month =\App\Models\userLogin::first();
		        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
		        $edate = strtotime($curr_str_month);
		        $bdate = strtotime($opening_bal_month);
		        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
		        $opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
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
					}
				}
				$thval_array = array();
				$thval_divide_array = array();
				$tdval_divide_array = array();
		        $invoice_details = 0;
		        $receipt_details = 0;
		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        $thval = '<th class="displaynone" colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($opening_bal_month)).'</th>';
			        array_push($thval_array, $thval);
			        $thval_divide = '<th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
			        <th class="displaynone" style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
			        array_push($thval_divide_array, $thval_divide);
		        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($opening_bal_month)))->first();
		    	if(($check_attachment))
		    	{
		    		$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.date('m-Y', strtotime($opening_bal_month)).'" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($opening_bal_month)).'" data-balance="'.$opening_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
		    	}
		    	else{
		    		$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($opening_bal_month)).'" data-balance="'.$opening_bal.'">Build</a>';
		    	}
		    	if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
		    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
		    	else { $inv_color= ''; }
		    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
		    	elseif($receipt_details < 0) { $inv_color = 'color:green;'; }
		    	else { $rec_color= ''; }
		        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$inv_color.'">
		        	'.number_format_invoice($invoice_details).'
		        </td>
		        <td class="received received_td received_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$rec_color.'">
		        	'.number_format_invoice($receipt_details).'
		        </td>
		        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        	'.number_format_invoice($closing_bal).'
		        </td>
		        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        </td>
		        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($opening_bal_month)).' displaynone" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="border-right:1px solid #d9d9d9">
		        	'.$build_button.'
		        </td>';
		        array_push($tdval_divide_array, $tdval_divide);
		        $opening_bal = $closing_bal;
		        for($i= $age; $i>=1; $i--)
		        {
		          $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $datevall = date('Y-m', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $datevalll = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		            $invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',$datevall.'%')->sum('gross');
			        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$datevall.'%')->sum('amount');
			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.$datevalll.'</th>';
			        array_push($thval_array, $thval);
			        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoices</p></th>
			          <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
			          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
			          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
			          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
			        array_push($thval_divide_array, $thval_divide);
			        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',$dateval)->first();
					if(($check_attachment))
					{
						$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download class="build_attach_file build_attach_file_'.$dateval.'" data-client="'.$client_id.'" data-element="'.$dateval.'" data-balance="'.$opening_bal.'">'.substr($check_attachment->filename,0,15).'...</a>';
					}
					else{
						$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.$dateval.'" data-balance="'.$opening_bal.'">Build</a>';
					}
					if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
			    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
			    	else { $inv_color= ''; }
			    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
			    	else { $rec_color= ''; }
					$tdval_divide = '<td class="invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'" style="'.$inv_color.'">
						'.number_format_invoice($invoice_details).'
					</td>
			        <td class="received received_td received_'.$dateval.'" data-month="'.$dateval.'" style="'.$rec_color.'">
			        	'.number_format_invoice($receipt_details).'
			        </td>
			        <td class="closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">
			        	'.number_format_invoice($closing_bal).'
			        </td>
			        <td class="statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">
			        </td>
			        <td class="build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">
			        	'.$build_button.'
			        </td>';
			        array_push($tdval_divide_array, $tdval_divide);
		          $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $opening_bal = $closing_bal;
		        }
		        $thval_reverse = implode("",array_reverse($thval_array));
		        $thval_divide_reverse = implode("",array_reverse($thval_divide_array));
		        $data['thval'] = $thval_reverse;
	        	$data['thval_divide'] = $thval_divide_reverse;
		        $tdval_divide_reverse = implode("",array_reverse($tdval_divide_array));
		        $data['tdval_divide'] = $tdval_divide_reverse;
		        $data['client_id'] = $client_id;
				$invoice_year = DB::select('SELECT *,SUBSTR(`invoice_date`, 1, 4) as `invoice_year` from `invoice_system` WHERE client_id = "'.$client_id.'" GROUP BY SUBSTR(`invoice_date`, 1, 4) ORDER BY SUBSTR(`invoice_date`, 1, 4) ASC');
				$output_year = '<option value="">Select Year</option>';
				if(($invoice_year))
				{
					foreach($invoice_year as $year)
					{
						$output_year.='<option value="'.$year->invoice_year.'">'.$year->invoice_year.'</option>';
					}
				}
				$data['invoice_year'] = $output_year;
		}
        echo json_encode($data);
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
		        $invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('gross');
		        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('amount');
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
		            $invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',$datevall.'%')->sum('gross');
			        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$datevall.'%')->sum('amount');
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
	public function get_invoice_list_statement(Request $request)
	{
		$client = $request->get('client');
		$get_data_from = $request->get('get_data_from');
		if($get_data_from==''){
			$month = explode('-',$request->get('month'));
			$monthval = $month[1].'-'.$month[0];
			$get_title_month = date('M-Y', strtotime($month[1].'-'.$month[0].'-01'));
			$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client)->where('invoice_date','like',$monthval.'%')->get();
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
			$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client)
				->whereRaw("invoice_date BETWEEN '".$from_monthval."' AND '".$to_monthval."'")->get();
		}
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
		$output = '';
		$total_net = 0;
		$total_vat = 0;
		$total_gross = 0;
		if(($invoice_details))
		{
			foreach($invoice_details as $invoice)
			{
				$output.='
				<tr>
				<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
				<td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->inv_net).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->vat_value).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->gross).'</td>
				</tr>';
				$total_net = $total_net + $invoice->inv_net;
				$total_vat = $total_vat + $invoice->vat_value;
				$total_gross = $total_gross + $invoice->gross;
			}
		}
		else{
			$output.= '<tr>
				<td colspan="5">No Invoices Found</td>
			</tr>';
		}
		$data['output'] = $output;
		$data['total_net'] = number_format_invoice($total_net);
		$data['total_vat'] = number_format_invoice($total_vat);
		$data['total_gross'] = number_format_invoice($total_gross);
		$data['title'] = 'Invoice List - '.$client.' '.$client_details->company.' - '.$get_title_month;
		echo json_encode($data);
	}
	public function get_receipt_list_statement(Request $request)
	{
		$client = $request->get('client');
		$get_data_from = $request->get('get_data_from');
		if($get_data_from==''){
			$month = explode('-',$request->get('month'));
			$monthval = $month[1].'-'.$month[0];
			$get_title_month = date('M-Y', strtotime($month[1].'-'.$month[0].'-01'));
			$receipt_details = \App\Models\receipts::where('client_code',$client)->where('credit_nominal','712')->where('receipt_date','like',$monthval.'%')->get();
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
			$receipt_details = \App\Models\receipts::where('client_code',$client)->where('credit_nominal','712')
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
	public function save_statement_settings(Request $request)
	{
		$data['statement_cc_email'] = ($request->get('statement_secondary') != "")?$request->get('statement_secondary'):'';
		$data['email_body'] = ($request->get('email_body') != "")?$request->get('email_body'):'';
		$data['email_signature'] = ($request->get('email_signature') != "")?$request->get('email_signature'):'';
		$data['minimum_balance'] = ($request->get('minimum_bal') != "")?$request->get('minimum_bal'):'';
		$data['statement_invoice'] = ($request->get('statement_invoice') != "")?$request->get('statement_invoice'):'';
		$data['payments_to_iban'] = ($request->get('payments_to_iban') != "")?$request->get('payments_to_iban'):'';
		$data['payments_to_bic'] = ($request->get('payments_to_bic') != "")?$request->get('payments_to_bic'):'';
		$data['bg_filename'] = '';
		$data['bg_url'] = '';
		$data['bg_filename1'] = '';
		$data['bg_url1'] = '';
		if(isset($_FILES['bg_image']['name']))
		{
			if($_FILES['bg_image']['name'] != "")
			{
				$filename = str_replace(' ','_',$_FILES['bg_image']['name']);
				$tmp_name = $_FILES['bg_image']['tmp_name'];
				$fileinfo = @getimagesize($_FILES["bg_image"]["tmp_name"]);
				$width = $fileinfo[0];
    			$height = $fileinfo[1];
				if($width != "827" || $height != "1100")
				{
					return redirect('user/full_view_statement')->with('message','Uploaded image dimension is wrong');
				}
				$upload_dir = 'uploads/statements';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/bg_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
				$data['bg_filename'] = $filename;
				$data['bg_url'] = $upload_dir;
			}
		}
		if(isset($_FILES['bg_image2']['name']))
		{
			if($_FILES['bg_image2']['name'] != "")
			{
				$filename = str_replace(' ','_',$_FILES['bg_image2']['name']);
				$tmp_name = $_FILES['bg_image2']['tmp_name'];
				$fileinfo = @getimagesize($_FILES["bg_image2"]["tmp_name"]);
				$width = $fileinfo[0];
    			$height = $fileinfo[1];
				if($width != "827" || $height != "1100")
				{
					return redirect('user/full_view_statement')->with('message','Uploaded image dimension is wrong');
				}
				$upload_dir = 'uploads/statements';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/bg_image2';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
				$data['bg_filename1'] = $filename;
				$data['bg_url1'] = $upload_dir;
			}
		}
		$check_statement = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		$data['practice_code'] = Session::get('user_practice_code');
		if($check_statement)
		{
			DB::table('client_statement_settings')->where('id',$check_statement->id)->update($data);
		}
		else{
			DB::table('client_statement_settings')->insert($data);
		}
		return redirect('user/statement_list')->with('message','Settings Saved Successfully');
	}
	public function delete_bg(Request $request)
	{
		$img_type = $request->get('img_type');
		if($img_type=='bg1'){
			$data['bg_url'] = '';
			$data['bg_filename'] = '';
		}
		if($img_type=='bg2'){
			$data['bg_url1'] = '';
			$data['bg_filename1'] = '';
		}
		$settingsval = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->update($data);
	}
	public function build_statement(Request $request)
	{
		// return view('Report.index');
		// $pdf = PDF ::loadView ('Report.index')->setPaper('A4', 'portrait');
		// return $pdf->stream();
		// exit;
		$client_id = $request->get('client_id');
		$month = $request->get('month');
		$opening_bal_value = $request->get('opening_bal');
		$explode_month = explode('-',$month);
		$curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$currrr_month = $explode_month[1].'-'.$explode_month[0];
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$settings = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();            
		$iban = '';
		$bic = '';
		$final_bg1='';
		$base64='';
		if($settings)
		{
			$iban = $settings->payments_to_iban;
			$bic = $settings->payments_to_bic;
			$bg1=$settings->bg_url.'/'.$settings->bg_filename;
			if (file_exists($bg1)) {
				$final_bg1=URL::to('/').'/'.$bg1;
			}
			$bg2=$settings->bg_url1.'/'.$settings->bg_filename1;
			if (file_exists($bg2)) {
				$final_bg2=URL::to('/').'/'.$bg2;
			}
		}
		$opening_bal_date = date('d F Y', strtotime('last day of previous month', strtotime($curr_month)));
		$html='';
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
			background-image: url("'.$final_bg1.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		#content1{
			background-image: url("'.$final_bg2.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		.page-break {
			page-break-after: always;
		}
		</style>
		<body>';
			$invoice_count = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','LIKE', $currrr_month.'%')->count();
			$receipt_details_count = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')
				->where('receipt_date','like',$currrr_month.'%')->count();
			if($invoice_count>=$receipt_details_count){
				$final_count=$invoice_count;
			}	
			else{
				$final_count=$receipt_details_count;
			}
			$max_limit=19;
			$loop_count=ceil($final_count/$max_limit);
			$offset=0;
			$total_inv_issued = 0;
			$total_remittance = 0;
			if($loop_count>0){
				$html .='<div id="page-body">';
				for($i=0;$i<$loop_count;$i++){
					if($i==0){
						$bg_img='content';
					}
					else{
						$bg_img='content1';
					}
					$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
					if($i==0){
						$html .= '<h4 style="text-align:center;font-size:20px; padding-top:20px;">Accountancy Fee Statement</h4>
						<table style="width:100%">
							<tr>
								<td style="width:50%">
									<spam>To:</spam><br/>
									'.$client_details->firstname.' '.$client_details->surname.'<br/>
									'.$client_details->company.'<br/>
									'.$client_details->address1.'<br/>
									'.$client_details->address2.'<br/>
									'.$client_details->address3.'<br/>
									'.$client_details->address4.'<br/>
									'.$client_details->address5.'<br/>
								</td>
								<td style="width:50%;vertical-align:top">
									<table style="width:100%;margin-top:18px">
										<tr>
											<td>Date: </td>
											<td style="text-align:right">'.date('d-M-Y').'</td>
										</tr>
										<tr>
											<td>Client Code: </td>
											<td style="text-align:right">'.$client_id.'</td>
										</tr>
										<tr>
											<td colspan="2">Payments can be made to:</td>
										</tr>
										<tr>
											<td>IBAN: </td>
											<td style="text-align:right">'.$iban.'</td>
										</tr>
										<tr>
											<td>BIC: </td>
											<td style="text-align:right">'.$bic.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table style="width:100%;margin-top:40px">
							<tr>
								<td style="width:80%">
									Opening Balance @ '.$opening_bal_date.'
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
						</table>';	
					}			
					$html.= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
						<tr>
							<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
								Invoices Issued
							</td>
							<td style="width:40%;border-bottom:2px solid #000;text-align:center">
								Remittance
							</td>
						</tr>
						<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Date</td>
										<td style="text-align:left">Inv No</td>
										<td style="text-align:center">€</td>
									</tr>';
									$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','LIKE', $currrr_month.'%')
										->limit($max_limit)->offset($offset)->get();
									if(($invoices)){
										foreach($invoices as $inv){
											$html.= '<tr>
												<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
												<td>'.$inv->invoice_number.'</td>
												<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
											</tr>';
											$total_inv_issued = $total_inv_issued + $inv->gross;
										}					
									}
									else if($invoice_count != 0){
										$html.= '<tr>
												<td></td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
									else{
										$html.= '<tr>
												<td>No Invoice Issued</td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
								$html.= '</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Date</td>
										<td style="text-align:center">€</td>
									</tr>';
									$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')
										->where('receipt_date','like',$currrr_month.'%')
										->limit($max_limit)->offset($offset)->get();
									if(($receipt_details))
									{
										foreach($receipt_details as $receipt)
										{
											$html.= '<tr>
												<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
												<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
											</tr>';
											$total_remittance = $total_remittance + $receipt->amount;
										}
									}
									else if($receipt_details_count != 0){
										$html.= '<tr>
												<td></td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
									else{
										$html.= '<tr>
												<td>No Remittance Found</td>
												<td style="text-align:right"></td>
											</tr>';
									}
								$html.= '</table>
							</td>
						</tr>';
						if($i==$loop_count-1){
						$html.='<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
						}
					$html.='</table>';
					if($i!=$loop_count-1){
						$html .= '<div class="page-break"></div>';
					}
					if($i==$loop_count-1){
						$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					}				
					$html .= '</div>';
					$offset=$offset+$max_limit;
				}
				$html .= '</div></div>';
			}
			else{
				$bg_img='content';
				$html .='<div id="page-body">';
				$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
				$html .= '<h4 style="text-align:center;font-size:20px;">Accountancy Fee Statement</h4>
					<table style="width:100%">
						<tr>
							<td style="width:50%">
								<spam>To:</spam><br/>
								'.$client_details->firstname.' '.$client_details->surname.'<br/>
								'.$client_details->company.'<br/>
								'.$client_details->address1.'<br/>
								'.$client_details->address2.'<br/>
								'.$client_details->address3.'<br/>
								'.$client_details->address4.'<br/>
								'.$client_details->address5.'<br/>
							</td>
							<td style="width:50%;vertical-align:top">
								<table style="width:100%;margin-top:18px">
									<tr>
										<td>Date: </td>
										<td style="text-align:right">'.date('d-M-Y').'</td>
									</tr>
									<tr>
										<td>Client Code: </td>
										<td style="text-align:right">'.$client_id.'</td>
									</tr>
									<tr>
										<td colspan="2">Payments can be made to:</td>
									</tr>
									<tr>
										<td>IBAN: </td>
										<td style="text-align:right">'.$iban.'</td>
									</tr>
									<tr>
										<td>BIC: </td>
										<td style="text-align:right">'.$bic.'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="width:100%;margin-top:40px">
						<tr>
							<td style="width:80%">
								Opening Balance @ '.$opening_bal_date.'
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
					</table>';	
				$html.= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','LIKE', $currrr_month.'%')->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}					
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')
									->where('receipt_date','like',$currrr_month.'%')->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
				$html.='<tr>
					<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Total</td>
								<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
							</tr>
						</table>
					</td>
					<td style="border-bottom:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
							</tr>
						</table>
					</td>
				</tr>';					
				$html.='</table>';
				$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
					<table style="width:100%">
						<tr>
							<td style="width:80%">
								Opening Balance
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Invoices
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_inv_issued).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Remittances
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_remittance).'
							</td>
						</tr>
						<tr>';
						$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
							$html.='<td style="width:80%;padding-top:15px">
								Closing Balance
							</td>
							<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
								'.number_format_invoice($closing_bal).'
							</td>
						</tr>
					</table>';
				$html .= '</div>';
				$html .= '</div></div>';
			}
		// echo $html;
		// dd();
		$upload_dir = 'uploads/statements';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/build_statement/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time().'/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$filename = $client_id.'_'.date('M', strtotime($curr_month)).'_'.date('Y', strtotime($curr_month)).'.pdf';
		// $data=[];
		// return view('Report.index');
		// $pdf = PDF ::loadView ('Report.index', $data)->setPaper('a4', 'portrait');
		$pdf = PDF::loadHTML($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->save($upload_dir.$filename);
        // return $pdf->stream('file-pdf.pdf');
		$dataval['client_id'] = $client_id;
		$dataval['month_year'] = $month;
		$dataval['url'] = $upload_dir.$filename;
		$dataval['filename'] = $filename;
		\App\Models\BuildStatementAttachments::insert($dataval);
		echo '<a href="'.URL::to($upload_dir.$filename).'" download>'.$filename.'</a>';
	}
	public function build_statement_for_single_client(Request $request){
		$client_id = $request->get('client_id');
		$month = $request->get('current_month');
		$explode_month = explode('-',$month);
		$curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$currrr_month = $explode_month[1].'-'.$explode_month[0];
		$client_details = \App\Models\CMClients::where('client_id',$client_id)->first();
		$settings = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();            
		$iban = '';
		$bic = '';
		if($settings)
		{
			$iban = $settings->payments_to_iban;
			$bic = $settings->payments_to_bic;
			$bg1=$settings->bg_url.'/'.$settings->bg_filename;
			if (file_exists($bg1)) {
				$final_bg1=URL::to('/').'/'.$bg1;
			}
			$bg2=$settings->bg_url1.'/'.$settings->bg_filename1;
			if (file_exists($bg2)) {
				$final_bg2=URL::to('/').'/'.$bg2;
			}
		}
		
		$date =\App\Models\userLogin::where('id',1)->first();
		$opening_bal_date = date('d-M-Y', strtotime($date->opening_balance_date)); 
		$opening_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		$formatted_bal = '0.00';
		$opening_bal = '0.00';
		if(($opening_details))
		{
			if($opening_details->opening_balance != "")
			{
				$opening_bal = $opening_details->opening_balance;
				$formatted_bal = number_format_invoice($opening_details->opening_balance);
			}
		}
		$html='';
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
			background-image: url("'.$final_bg1.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		#content1{
			background-image: url("'.$final_bg2.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		.page-break {
			page-break-after: always;
		}
		</style>
		<body>';		
		$invoice_count = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>',$date->opening_balance_date)->count();
		$receipt_details_count = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')
		->where('receipt_date','>',$date->opening_balance_date)->count();
		if($invoice_count>=$receipt_details_count){
			$final_count=$invoice_count;
		}	
		else{
			$final_count=$receipt_details_count;
		}
		$max_limit=19;
		$loop_count=ceil($final_count/$max_limit);
		$offset=0;
		$total_inv_issued = 0;
		$total_remittance = 0;
		if($loop_count>0){
			$html .='<div id="page-body">';
			for($i=0;$i<$loop_count;$i++){
				if($i==0){
					$bg_img='content';
				}
				else{
					$bg_img='content1';
				}
				$html .= '<div id="'.$bg_img.'">
				<div style="margin:80px 20px 20px 20px;">';
				if($i==0){
					$html .= '<h4 style="text-align:center;font-size:20px; padding-top:20px;">Accountancy Fee Statement</h4>
					<table style="width:100%">
						<tr>
							<td style="width:50%">
								<spam>To:</spam><br/>
								'.$client_details->firstname.' '.$client_details->surname.'<br/>
								'.$client_details->company.'<br/>
								'.$client_details->address1.'<br/>
								'.$client_details->address2.'<br/>
								'.$client_details->address3.'<br/>
								'.$client_details->address4.'<br/>
								'.$client_details->address5.'<br/>
							</td>
							<td style="width:50%;vertical-align:top">
								<table style="width:100%;margin-top:18px">
									<tr>
										<td>Date: </td>
										<td style="text-align:right">'.date('d-M-Y').'</td>
									</tr>
									<tr>
										<td>Client Code: </td>
										<td style="text-align:right">'.$client_id.'</td>
									</tr>
									<tr>
										<td colspan="2">Payments can be made to:</td>
									</tr>
									<tr>
										<td>IBAN: </td>
										<td style="text-align:right">'.$iban.'</td>
									</tr>
									<tr>
										<td>BIC: </td>
										<td style="text-align:right">'.$bic.'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="width:100%;margin-top:40px">
						<tr>
							<td style="width:80%">
								Opening Balance @ '.$opening_bal_date.'
							</td>
							<td style="width:20%;text-align:right">
								'.$formatted_bal.'
							</td>
						</tr>
					</table>';
				}
				$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
				<tr>
					<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
						Invoices Issued
					</td>
					<td style="width:40%;border-bottom:2px solid #000;text-align:center">
						Remittance
					</td>
				</tr>
				<tr>
					<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Date</td>
								<td style="text-align:left">Inv No</td>
								<td style="text-align:center">€</td>
							</tr>';
							$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>',$date->opening_balance_date)
								->limit($max_limit)->offset($offset)->orderBy('invoice_date','desc')->get();
							if(($invoices)){
								foreach($invoices as $inv){
									$html.= '<tr>
										<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
										<td>'.$inv->invoice_number.'</td>
										<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
									</tr>';
									$total_inv_issued = $total_inv_issued + $inv->gross;
								}
							}
							else if($invoice_count != 0){
								$html.= '<tr>
										<td></td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
							else{
								$html.= '<tr>
										<td>No Invoice Issued</td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
						$html.= '</table>
					</td>
					<td style="border-bottom:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Date</td>
								<td style="text-align:center">€</td>
							</tr>';
							$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>',$date->opening_balance_date)
								->limit($max_limit)->offset($offset)->orderBy('receipt_date','desc')->get();
							if(($receipt_details))
							{
								foreach($receipt_details as $receipt)
								{
									$html.= '<tr>
										<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
										<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
									</tr>';
									$total_remittance = $total_remittance + $receipt->amount;
								}
							}
							else if($receipt_details_count != 0){
								$html.= '<tr>
										<td></td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
							else{
								$html.= '<tr>
										<td>No Remittance Found</td>
										<td style="text-align:right"></td>
									</tr>';
							}
						$html.= '</table>
					</td>
				</tr>';
				if($i==$loop_count-1){
					$html .= '<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Total</td>
									<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
								</tr>
							</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
								</tr>
							</table>
						</td>
					</tr>';
				}
				$html.='</table>';
				if($i!=$loop_count-1){
					$html .= '<div class="page-break"></div>';
				}
				if($i==$loop_count-1){
					$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
					<table style="width:100%">
						<tr>
							<td style="width:80%">
								Opening Balance
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Invoices
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_inv_issued).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Remittances
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_remittance).'
							</td>
						</tr>
						<tr>';
						$closing_bal = number_format_invoice_without_comma($opening_bal) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
							$html.='<td style="width:80%;padding-top:15px">
								Closing Balance
							</td>
							<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
								'.number_format_invoice($closing_bal).'
							</td>
						</tr>
					</table>';
				}
				$html .= '</div>';
				$offset=$offset+$max_limit;
			}
			$html .= '</div></div>';
		}
		else{
			$bg_img='content';
			$html .='<div id="page-body">';
			$html .= '<div id="'.$bg_img.'">
				<div style="margin:80px 20px 20px 20px;">';
				$html .= '<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
				<table style="width:100%">
					<tr>
						<td style="width:50%">
							<spam>To:</spam><br/>
							'.$client_details->firstname.' '.$client_details->surname.'<br/>
							'.$client_details->company.'<br/>
							'.$client_details->address1.'<br/>
							'.$client_details->address2.'<br/>
							'.$client_details->address3.'<br/>
							'.$client_details->address4.'<br/>
							'.$client_details->address5.'<br/>
						</td>
						<td style="width:50%;vertical-align:top">
							<table style="width:100%;margin-top:18px">
								<tr>
									<td>Date: </td>
									<td style="text-align:right">'.date('d-M-Y').'</td>
								</tr>
								<tr>
									<td>Client Code: </td>
									<td style="text-align:right">'.$client_id.'</td>
								</tr>
								<tr>
									<td colspan="2">Payments can be made to:</td>
								</tr>
								<tr>
									<td>IBAN: </td>
									<td style="text-align:right">'.$iban.'</td>
								</tr>
								<tr>
									<td>BIC: </td>
									<td style="text-align:right">'.$bic.'</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table style="width:100%;margin-top:40px">
					<tr>
						<td style="width:80%">
							Opening Balance @ '.$opening_bal_date.'
						</td>
						<td style="width:20%;text-align:right">
							'.$formatted_bal.'
						</td>
					</tr>
				</table>';
			$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
				<tr>
					<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
						Invoices Issued
					</td>
					<td style="width:40%;border-bottom:2px solid #000;text-align:center">
						Remittance
					</td>
				</tr>
				<tr>
					<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Date</td>
								<td style="text-align:left">Inv No</td>
								<td style="text-align:center">€</td>
							</tr>';
							$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>',$date->opening_balance_date)
								->orderBy('invoice_date','desc')->get();
							if(($invoices)){
								foreach($invoices as $inv){
									$html.= '<tr>
										<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
										<td>'.$inv->invoice_number.'</td>
										<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
									</tr>';
									$total_inv_issued = $total_inv_issued + $inv->gross;
								}
							}
							else if($invoice_count != 0){
								$html.= '<tr>
										<td></td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
							else{
								$html.= '<tr>
										<td>No Invoice Issued</td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
						$html.= '</table>
					</td>
					<td style="border-bottom:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Date</td>
								<td style="text-align:center">€</td>
							</tr>';
							$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>',$date->opening_balance_date)
								->orderBy('receipt_date','desc')->get();
							if(($receipt_details))
							{
								foreach($receipt_details as $receipt)
								{
									$html.= '<tr>
										<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
										<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
									</tr>';
									$total_remittance = $total_remittance + $receipt->amount;
								}
							}
							else if($receipt_details_count != 0){
								$html.= '<tr>
										<td></td>
										<td></td>
										<td style="text-align:right"></td>
									</tr>';
							}
							else{
								$html.= '<tr>
										<td>No Remittance Found</td>
										<td style="text-align:right"></td>
									</tr>';
							}
						$html.= '</table>
					</td>
				</tr>';
			$html .= '<tr>
				<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:left">Total</td>
							<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
						</tr>
					</table>
				</td>
				<td style="border-bottom:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
						</tr>
					</table>
				</td>
			</tr>';
			$html.='</table>';
			$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
					<table style="width:100%">
						<tr>
							<td style="width:80%">
								Opening Balance
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Invoices
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_inv_issued).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Remittances
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_remittance).'
							</td>
						</tr>
						<tr>';
						$closing_bal = number_format_invoice_without_comma($opening_bal) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
							$html.='<td style="width:80%;padding-top:15px">
								Closing Balance
							</td>
							<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
								'.number_format_invoice($closing_bal).'
							</td>
						</tr>
					</table>';
			$html .= '</div>';
			$html .= '</div></div>';
		}
		$upload_dir = 'uploads/statements';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time().'/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$filename = $client_id.'_Build Full Statement.pdf';
		$pdf = PDF::loadHTML($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->save($upload_dir.$filename);
		$dataval['url'] = '../'.$upload_dir.$filename;
		$dataval['filename'] = $filename;
		echo json_encode($dataval);
	}
	public function buildall_statement(Request $request)
	{
		$month = $request->get('month');
		$explode_month = explode('-',$month);
		$curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$currrr_month = $explode_month[1].'-'.$explode_month[0];
		$monSel = date('m-Y', strtotime($curr_month));
		$monSel1=date('Y-m', strtotime($curr_month));
		$c_data = $request->get('data');
		// dd($c_data);
		// $begin = $request->get('begin');
		// $limit = $request->get('limit');
		$responsearray = [];
		foreach($c_data as $data1){
			// print_r($data1);
			$c_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$data1['client_id'])->first();
			$settings = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
			$iban = '';
			$bic = '';
			if($settings)
			{
				$iban = $settings->payments_to_iban;
				$bic = $settings->payments_to_bic;
				$bg1=$settings->bg_url.'/'.$settings->bg_filename;
				if (file_exists($bg1)) {
					$final_bg1=URL::to('/').'/'.$bg1;
				}
				$bg2=$settings->bg_url1.'/'.$settings->bg_filename1;
				if (file_exists($bg2)) {
					$final_bg2=URL::to('/').'/'.$bg2;
				}
			}

			
			$opening_bal_date = date('d F Y', strtotime('last day of previous month', strtotime($curr_month)));
			$opening_bal_value = $data1['opening_bal'];
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
					background-image: url("'.$final_bg1.'");
					background-position: center top;
					background-repeat: no-repeat;
					width:100%;
					height:100%;
				}
				#content1{
					background-image: url("'.$final_bg2.'");
					background-position: center top;
					background-repeat: no-repeat;
					width:100%;
					height:100%;
				}
				.page-break {
					page-break-after: always;
				}
			</style>
			<body>';
			$invoice_count = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','LIKE', $monSel1.'%')->count();
			$receipt_details_count = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')
				->where('receipt_date','like',$currrr_month.'%')->count();
			if($invoice_count>=$receipt_details_count){
				$final_count=$invoice_count;
			}	
			else{
				$final_count=$receipt_details_count;
			}
			$max_limit=19;
			$loop_count=ceil($final_count/$max_limit);
			$offset=0;
			$total_inv_issued = 0;
			$total_remittance = 0;
			if($loop_count>0){
				$html .='<div id="page-body">';
				for($i=0;$i<$loop_count;$i++){
					if($i==0){
						$bg_img='content';
					}
					else{
						$bg_img='content1';
					}
					$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
					if($i==0){
						$html .= '<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
						<table style="width:100%">
							<tr>
								<td style="width:50%">
									<spam>To:</spam><br/>
									'.$c_details->firstname.' '.$c_details->surname.'<br/>
									'.$c_details->company.'<br/>
									'.$c_details->address1.'<br/>
									'.$c_details->address2.'<br/>
									'.$c_details->address3.'<br/>
									'.$c_details->address4.'<br/>
									'.$c_details->address5.'<br/>
								</td>
								<td style="width:50%;vertical-align:top">
									<table style="width:100%;margin-top:18px">
										<tr>
											<td>Date: </td>
											<td style="text-align:right">'.date('d-M-Y').'</td>
										</tr>
										<tr>
											<td>Client Code: </td>
											<td style="text-align:right">'.$c_details->client_id.'</td>
										</tr>
										<tr>
											<td colspan="2">Payments can be made to:</td>
										</tr>
										<tr>
											<td>IBAN: </td>
											<td style="text-align:right">'.$iban.'</td>
										</tr>
										<tr>
											<td>BIC: </td>
											<td style="text-align:right">'.$bic.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table style="width:100%;margin-top:40px">
							<tr>
								<td style="width:80%">
									Opening Balance @ '.$opening_bal_date.'
								</td>
								<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
						</table>';
					}
					$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','LIKE', $monSel1.'%')
									->limit($max_limit)->offset($offset)->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')->where('receipt_date','like',$currrr_month.'%')
								->limit($max_limit)->offset($offset)->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
					if($i==$loop_count-1){
						$html .= '<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
					}
					$html.='</table>';
					if($i!=$loop_count-1){
						$html .= '<div class="page-break"></div>';
					}
					if($i==$loop_count-1){
						$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							// dd(($opening_bal_value));
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					}
					$html .= '</div>';
					$offset=$offset+$max_limit;
				}
				$html .= '</div></div>';
			}
			else{
				$bg_img='content';
				$html .='<div id="page-body">';
				$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
				$html .= '<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
					<table style="width:100%">
						<tr>
							<td style="width:50%">
								<spam>To:</spam><br/>
								'.$c_details->firstname.' '.$c_details->surname.'<br/>
								'.$c_details->company.'<br/>
								'.$c_details->address1.'<br/>
								'.$c_details->address2.'<br/>
								'.$c_details->address3.'<br/>
								'.$c_details->address4.'<br/>
								'.$c_details->address5.'<br/>
							</td>
							<td style="width:50%;vertical-align:top">
								<table style="width:100%;margin-top:18px">
									<tr>
										<td>Date: </td>
										<td style="text-align:right">'.date('d-M-Y').'</td>
									</tr>
									<tr>
										<td>Client Code: </td>
										<td style="text-align:right">'.$c_details->client_id.'</td>
									</tr>
									<tr>
										<td colspan="2">Payments can be made to:</td>
									</tr>
									<tr>
										<td>IBAN: </td>
										<td style="text-align:right">'.$iban.'</td>
									</tr>
									<tr>
										<td>BIC: </td>
										<td style="text-align:right">'.$bic.'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="width:100%;margin-top:40px">
						<tr>
							<td style="width:80%">
								Opening Balance @ '.$opening_bal_date.'
							</td>
							<td style="width:20%;text-align:right">
							'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
					</table>';
					$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','LIKE', $monSel1.'%')->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')->where('receipt_date','like',$currrr_month.'%')->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
					$html .= '<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
					$html.='</table>';
					$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							// dd(($opening_bal_value));
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					$html .= '</div>';
					$html .= '</div></div>';
			}
			$upload_dir = 'uploads/statements';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/build_statement/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$filename = $c_details->client_id.'_'.date('M', strtotime($curr_month)).'_'.date('Y', strtotime($curr_month)).'.pdf';
			$pdf = PDF::loadHTML($html);
			$pdf->setPaper('A4', 'portrait');
			$where=[
				'client_id'=>$c_details->client_id,
				'month_year'=>$monSel
			];
			$db_stmt=\App\Models\BuildStatementAttachments::where($where)->first();
			if(!empty($db_stmt)){				
				if (file_exists($db_stmt->url)) {
					$fileurl = str_replace('\\', '/', $db_stmt->url);
					unlink($fileurl);
				}
				$upload_dir = $upload_dir.'/'.time().'/';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
			}
			else{
				$upload_dir = $upload_dir.'/'.time().'/';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
			}
			$pdf->save($upload_dir.$filename);
			$dataval['client_id'] = $c_details->client_id;
			$dataval['month_year'] = $monSel;
			$dataval['url'] = $upload_dir.$filename;
			$dataval['filename'] = $filename;
			$data=[
				'client_id'=> $c_details->client_id,
				'month_year'=> $monSel,
				'url'=> $upload_dir.$filename,
				'filename'=> $filename,
			];
			if($db_stmt){
				\App\Models\BuildStatementAttachments::insert($dataval);
			}
			else{
				\App\Models\BuildStatementAttachments::where($where)->update($data);
			}
			// dd($html);
			$dataarray['client_id'] = $c_details->client_id;
			$dataarray['month_year'] = $monSel;
			$dataarray['link'] = '<a href="'.URL::to($upload_dir.$filename).'" title="'.$filename.'" download="" data-client="'.$c_details->client_id.'" data-element="'.$monSel.'" data-balance="'.$opening_bal_value.'">'.$filename.'</a>';
			array_push($responsearray,$dataarray);
		}
		echo json_encode($responsearray);
	}
	public function deleteall_statement(Request $request)
	{
		$month = $request->get('month');
		$explode_month = explode('-',$month);
		$curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$currrr_month = $explode_month[1].'-'.$explode_month[0];
		$monSel = date('m-Y', strtotime($curr_month));
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		foreach($client_details as $c_details)
		{
			$where=[
				'client_id'=>$c_details->client_id,
				'month_year'=>$monSel
			];
			// dd($where);
			$db_stmt=\App\Models\BuildStatementAttachments::where($where)->get();
			foreach($db_stmt as $d1){
				if (file_exists($d1->url)) {
					$fileurl = str_replace('\\', '/', $d1->url);
					unlink($fileurl);
				} else {
					// File not found.
				}				
				\App\Models\BuildStatementAttachments::where('id',$d1->id)->delete();
			}
		}
		echo $monSel;
	}
	public function export_statement_clients_current_view(Request $request){
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$current_month = $request->get('current_month');
		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('Y-m', strtotime($current_month));
        $curr_str_month = $current_month;
        $opening_month =\App\Models\userLogin::first();
		$filename = time().'_Statement List From '.date('M-Y', strtotime($opening_month->opening_balance_date)).' to '.$current_month.'.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));
        $from_date = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
        $to_date = date('Y-m-d', strtotime('last day of previous month', strtotime($curr_str_month)));
        $columns1 = array("","","","","","","","","Post Month Activity","","","",date('M-Y', strtotime($curr_str_month)),"","");
        fputcsv($file, $columns1);
        $columns2 = array("S.No","ClientID","Company Name","Primary Email","Secondary Email","Send Statement","Opening Balance","Invoices","Received","Closing Balance","Invoices","Received","Closing Balance","Statement Sent","Build");
        fputcsv($file, $columns2);
		if(($clients))
		{
			$i = 1;
			foreach($clients as $client)
			{
				$client_id = $client->client_id;
				$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = $opening_bal_details->opening_balance;
					}
				}
				$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
				if(($statement_details))
				{
					$primary = $statement_details->email;
					$secondary = $statement_details->email2;
					$salutation = $statement_details->salutation;
				}
				else{
					$primary = $client->email;
					$secondary = $client->email2;
					$salutation = $client->salutation;
				}
				if($client->send_statement == 1) { $cecked = 'Yes'; } else { $cecked = 'No';  }
				$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('gross');
		        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_date)->where('receipt_date','<=',$to_date)->sum('amount');
		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        $invoice_curr_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','like',$current_monthh.'%')->sum('gross');
		        $receipt_curr_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$current_monthh.'%')->sum('amount');
		        $closing_curr_bal = ($closing_bal + $invoice_curr_details) - $receipt_curr_details;
		        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($curr_str_month)))->first();
		    	if(($check_attachment))
		    	{
		    		$build_button = $check_attachment->filename;
		    	}
		    	else{
		    		$build_button = '';
		    	}
			    $columns3 = array($i,$client->client_id,$client->company,$primary,$secondary,$cecked,number_format_invoice_empty($opening_bal),number_format_invoice($invoice_details),number_format_invoice($receipt_details),number_format_invoice($closing_bal),number_format_invoice($invoice_curr_details),number_format_invoice($receipt_curr_details),number_format_invoice($closing_curr_bal),"",$build_button);
			    fputcsv($file, $columns3);
				$i++;
			}
		}
		fclose($file);
        echo $filename;
	}
	public function build_all_statement_monthly_view(Request $request){
		$month = explode('_',$request->get('month'));
		$from_month_explode = $month[0];
		$to_month_explode = $month[1];
		$explode_month = explode('-',$from_month_explode);
		$from_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$frommm_month = $explode_month[1].'-'.$explode_month[0];
		$monSel_from = date('m-Y', strtotime($from_month));
		$monSel1_from =date('Y-m-01', strtotime($from_month));
		$to_explode_month = explode('-',$to_month_explode);
		$to_month = date('Y-m-t', strtotime($to_explode_month[1].'-'.$to_explode_month[0].'-01'));
		$tooo_month = $to_explode_month[1].'-'.$to_explode_month[0];
		$monSel_to = date('m-Y', strtotime($to_month));
		$monSel1_to =date('Y-m-t', strtotime($to_month));
		$c_data = $request->get('data');
		// dd($c_data);
		// $begin = $request->get('begin');
		// $limit = $request->get('limit');
		$responsearray = [];
		foreach($c_data as $data1){
			// print_r($data1);
			$c_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$data1['client_id'])->first();
			$settings = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
			$iban = '';
			$bic = '';
			$final_bg1 = '';
			$final_bg2 = '';
			if($settings)
			{
				$iban = $settings->payments_to_iban;
				$bic = $settings->payments_to_bic;
				$bg1=$settings->bg_url.'/'.$settings->bg_filename;
				if (file_exists($bg1)) {
					$final_bg1=URL::to('/').'/'.$bg1;
				}
				$bg2=$settings->bg_url1.'/'.$settings->bg_filename1;
				if (file_exists($bg2)) {
					$final_bg2=URL::to('/').'/'.$bg2;
				}
			}
			$opening_bal_date = date('d F Y', strtotime('last day of previous month', strtotime($from_month)));
			$opening_bal_value = $data1['opening_bal'];
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
					background-image: url("'.$final_bg1.'");
					background-position: center top;
					background-repeat: no-repeat;
					width:100%;
					height:100%;
				}
				#content1{
					background-image: url("'.$final_bg2.'");
					background-position: center top;
					background-repeat: no-repeat;
					width:100%;
					height:100%;
				}
				.page-break {
					page-break-after: always;
				}
			</style>
			<body>';
			$invoice_count = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','>=', $from_month)->where('invoice_date','<=', $to_month)->count();
			$receipt_details_count = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')
				->where('receipt_date','>=',$from_month)->where('receipt_date','<=', $to_month)->count();
			if($invoice_count>=$receipt_details_count){
				$final_count=$invoice_count;
			}	
			else{
				$final_count=$receipt_details_count;
			}
			$max_limit=19;
			$loop_count=ceil($final_count/$max_limit);
			$offset=0;
			$total_inv_issued = 0;
			$total_remittance = 0;
			if($loop_count>0){
				$html .='<div id="page-body">';
				for($i=0;$i<$loop_count;$i++){
					if($i==0){
						$bg_img='content';
					}
					else{
						$bg_img='content1';
					}
					$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
					if($i==0){
						$html .= '<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
						<table style="width:100%">
							<tr>
								<td style="width:50%">
									<spam>To:</spam><br/>
									'.$c_details->firstname.' '.$c_details->surname.'<br/>
									'.$c_details->company.'<br/>
									'.$c_details->address1.'<br/>
									'.$c_details->address2.'<br/>
									'.$c_details->address3.'<br/>
									'.$c_details->address4.'<br/>
									'.$c_details->address5.'<br/>
								</td>
								<td style="width:50%;vertical-align:top">
									<table style="width:100%;margin-top:18px">
										<tr>
											<td>Date: </td>
											<td style="text-align:right">'.date('d-M-Y').'</td>
										</tr>
										<tr>
											<td>Client Code: </td>
											<td style="text-align:right">'.$c_details->client_id.'</td>
										</tr>
										<tr>
											<td colspan="2">Payments can be made to:</td>
										</tr>
										<tr>
											<td>IBAN: </td>
											<td style="text-align:right">'.$iban.'</td>
										</tr>
										<tr>
											<td>BIC: </td>
											<td style="text-align:right">'.$bic.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table style="width:100%;margin-top:40px">
							<tr>
								<td style="width:80%">
									Opening Balance @ '.$opening_bal_date.'
								</td>
								<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
						</table>';
					}
					$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','>=', $from_month)->where('invoice_date','<=', $to_month)
									->limit($max_limit)->offset($offset)->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month)->where('receipt_date','<=', $to_month)->limit($max_limit)->offset($offset)->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
					if($i==$loop_count-1){
						$html .= '<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
					}
					$html.='</table>';
					if($i!=$loop_count-1){
						$html .= '<div class="page-break"></div>';
					}
					if($i==$loop_count-1){
						$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							// dd(($opening_bal_value));
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					}
					$html .= '</div>';
					$offset=$offset+$max_limit;
				}
				$html .= '</div></div>';
			}
			else{
				$bg_img='content';
				$html .='<div id="page-body">';
				$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
				$html .= '<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
					<table style="width:100%">
						<tr>
							<td style="width:50%">
								<spam>To:</spam><br/>
								'.$c_details->firstname.' '.$c_details->surname.'<br/>
								'.$c_details->company.'<br/>
								'.$c_details->address1.'<br/>
								'.$c_details->address2.'<br/>
								'.$c_details->address3.'<br/>
								'.$c_details->address4.'<br/>
								'.$c_details->address5.'<br/>
							</td>
							<td style="width:50%;vertical-align:top">
								<table style="width:100%;margin-top:18px">
									<tr>
										<td>Date: </td>
										<td style="text-align:right">'.date('d-M-Y').'</td>
									</tr>
									<tr>
										<td>Client Code: </td>
										<td style="text-align:right">'.$c_details->client_id.'</td>
									</tr>
									<tr>
										<td colspan="2">Payments can be made to:</td>
									</tr>
									<tr>
										<td>IBAN: </td>
										<td style="text-align:right">'.$iban.'</td>
									</tr>
									<tr>
										<td>BIC: </td>
										<td style="text-align:right">'.$bic.'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="width:100%;margin-top:40px">
						<tr>
							<td style="width:80%">
								Opening Balance @ '.$opening_bal_date.'
							</td>
							<td style="width:20%;text-align:right">
							'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
					</table>';
					$html .= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$c_details->client_id)->where('invoice_date','>=', $from_month)->where('invoice_date','<=', $to_month)->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$c_details->client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month)->where('receipt_date','<=', $to_month)->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
					$html .= '<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
					$html.='</table>';
					$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							// dd(($opening_bal_value));
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					$html .= '</div>';
					$html .= '</div></div>';
			}
			$upload_dir = 'uploads/statements';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/build_statement/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$filename = $c_details->client_id.'_'.date('M', strtotime($from_month)).'_'.date('Y', strtotime($from_month)).'-TO-'.date('M', strtotime($to_month)).'_'.date('Y', strtotime($to_month)).'.pdf';
			$pdf = PDF::loadHTML($html);
			$pdf->setPaper('A4', 'portrait');
			$where=[
				'client_id'=>$c_details->client_id,
				'month_year'=>$monSel_from.'_'.$monSel_to,
			];
			$db_stmt=\App\Models\BuildStatementAttachments::where($where)->first();
			if(!empty($db_stmt)){				
				if (file_exists($db_stmt->url)) {
					$fileurl = str_replace('\\', '/', $db_stmt->url);
					unlink($fileurl);
				}
				$upload_dir = $upload_dir.'/'.time().'/';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
			}
			else{
				$upload_dir = $upload_dir.'/'.time().'/';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
			}
			$pdf->save($upload_dir.$filename);
			$dataval['client_id'] = $c_details->client_id;
			$dataval['month_year'] = $monSel_from.'_'.$monSel_to;
			$dataval['url'] = $upload_dir.$filename;
			$dataval['filename'] = $filename;
			$data=[
				'client_id'=> $c_details->client_id,
				'month_year'=> $monSel_from.'_'.$monSel_to,
				'url'=> $upload_dir.$filename,
				'filename'=> $filename,
			];
			if(($db_stmt)) {
				\App\Models\BuildStatementAttachments::insert($dataval);
			}
			else{
				\App\Models\BuildStatementAttachments::where($where)->update($data);
			}
			// dd($html);
			$dataarray['client_id'] = $c_details->client_id;
			$dataarray['month_year'] = $monSel_from.'_'.$monSel_to;
			$dataarray['link'] = '<a href="'.URL::to($upload_dir.$filename).'" title="'.$filename.'" download="" data-client="'.$c_details->client_id.'" data-element="'.$monSel_from.'_'.$monSel_to.'" data-balance="'.$opening_bal_value.'">'.substr($filename,0,15).'...</a>';
			array_push($responsearray,$dataarray);
		}
		echo json_encode($responsearray);
	}
	public function build_statement_monthly_view(Request $request)
	{
		// return view('Report.index');
		// $pdf = PDF ::loadView ('Report.index')->setPaper('A4', 'portrait');
		// return $pdf->stream();
		// exit;
		$client_id = $request->get('client_id');
		$month = explode('_',$request->get('month'));
		$from_month_explode = $month[0];
		$to_month_explode = $month[1];
		$opening_bal_value = $request->get('opening_bal');
		$from_explode_month = explode('-',$from_month_explode);
		$from_month = $from_explode_month[1].'-'.$from_explode_month[0].'-'.'01';
		$frommm_month = $from_explode_month[1].'-'.$from_explode_month[0];
		$to_explode_month = explode('-',$to_month_explode);
		$to_month = date('Y-m-t', strtotime($to_explode_month[1].'-'.$to_explode_month[0].'-01'));
		$tooo_month = $to_explode_month[1].'-'.$to_explode_month[0];
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();

		$settings = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		$iban = '';
		$bic = '';
		$final_bg1 = '';
		$base64='';
		$final_bg2 = '';
		if($settings)
		{
			$iban = $settings->payments_to_iban;
			$bic = $settings->payments_to_bic;
			$bg1=$settings->bg_url.'/'.$settings->bg_filename;
			if (file_exists($bg1)) {
				$final_bg1=URL::to('/').'/'.$bg1;
			}
			$bg2=$settings->bg_url1.'/'.$settings->bg_filename1;
			if (file_exists($bg2)) {
				$final_bg2=URL::to('/').'/'.$bg2;
			}
		}

		
		$opening_bal_date = date('d F Y', strtotime('last day of previous month', strtotime($from_month)));
		$html='';
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
			background-image: url("'.$final_bg1.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		#content1{
			background-image: url("'.$final_bg2.'");
			background-position: center top;
			background-repeat: no-repeat;
			width:100%;
			height:100%;
		}
		.page-break {
			page-break-after: always;
		}
		</style>
		<body>';
			$invoice_count = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=', $from_month)->where('invoice_date','<=', $to_month)->count();
			$receipt_details_count = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month)->where('receipt_date','<=',$to_month)->count();
			if($invoice_count>=$receipt_details_count){
				$final_count=$invoice_count;
			}	
			else{
				$final_count=$receipt_details_count;
			}
			$max_limit=19;
			$loop_count=ceil($final_count/$max_limit);
			$offset=0;
			$total_inv_issued = 0;
			$total_remittance = 0;
			if($loop_count>0){
				$html .='<div id="page-body">';
				for($i=0;$i<$loop_count;$i++){
					if($i==0){
						$bg_img='content';
					}
					else{
						$bg_img='content1';
					}
					$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
					if($i==0){
						$html .= '<h4 style="text-align:center;font-size:20px; padding-top:20px;">Accountancy Fee Statement</h4>
						<table style="width:100%">
							<tr>
								<td style="width:50%">
									<spam>To:</spam><br/>
									'.$client_details->firstname.' '.$client_details->surname.'<br/>
									'.$client_details->company.'<br/>
									'.$client_details->address1.'<br/>
									'.$client_details->address2.'<br/>
									'.$client_details->address3.'<br/>
									'.$client_details->address4.'<br/>
									'.$client_details->address5.'<br/>
								</td>
								<td style="width:50%;vertical-align:top">
									<table style="width:100%;margin-top:18px">
										<tr>
											<td>Date: </td>
											<td style="text-align:right">'.date('d-M-Y').'</td>
										</tr>
										<tr>
											<td>Client Code: </td>
											<td style="text-align:right">'.$client_id.'</td>
										</tr>
										<tr>
											<td colspan="2">Payments can be made to:</td>
										</tr>
										<tr>
											<td>IBAN: </td>
											<td style="text-align:right">'.$iban.'</td>
										</tr>
										<tr>
											<td>BIC: </td>
											<td style="text-align:right">'.$bic.'</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table style="width:100%;margin-top:40px">
							<tr>
								<td style="width:80%">
									Opening Balance @ '.$opening_bal_date.'
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
						</table>';	
					}			
					$html.= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
						<tr>
							<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
								Invoices Issued
							</td>
							<td style="width:40%;border-bottom:2px solid #000;text-align:center">
								Remittance
							</td>
						</tr>
						<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Date</td>
										<td style="text-align:left">Inv No</td>
										<td style="text-align:center">€</td>
									</tr>';
									$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=', $from_month)->where('invoice_date','<=', $to_month)->limit($max_limit)->offset($offset)->get();
									if(($invoices)){
										foreach($invoices as $inv){
											$html.= '<tr>
												<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
												<td>'.$inv->invoice_number.'</td>
												<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
											</tr>';
											$total_inv_issued = $total_inv_issued + $inv->gross;
										}					
									}
									else if($invoice_count != 0){
										$html.= '<tr>
												<td></td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
									else{
										$html.= '<tr>
												<td>No Invoice Issued</td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
								$html.= '</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Date</td>
										<td style="text-align:center">€</td>
									</tr>';
									$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month)->where('receipt_date','<=',$to_month)->limit($max_limit)->offset($offset)->get();
									if(($receipt_details))
									{
										foreach($receipt_details as $receipt)
										{
											$html.= '<tr>
												<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
												<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
											</tr>';
											$total_remittance = $total_remittance + $receipt->amount;
										}
									}
									else if($receipt_details_count != 0){
										$html.= '<tr>
												<td></td>
												<td></td>
												<td style="text-align:right"></td>
											</tr>';
									}
									else{
										$html.= '<tr>
												<td>No Remittance Found</td>
												<td style="text-align:right"></td>
											</tr>';
									}
								$html.= '</table>
							</td>
						</tr>';
						if($i==$loop_count-1){
						$html.='<tr>
							<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:left">Total</td>
										<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
									</tr>
								</table>
							</td>
							<td style="border-bottom:2px solid #000;vertical-align:top">
								<table style="width:100%">
									<tr>
										<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
									</tr>
								</table>
							</td>
						</tr>';
						}
					$html.='</table>';
					if($i!=$loop_count-1){
						$html .= '<div class="page-break"></div>';
					}
					if($i==$loop_count-1){
						$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
						<table style="width:100%">
							<tr>
								<td style="width:80%">
									Opening Balance
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($opening_bal_value).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Invoices
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_inv_issued).'
								</td>
							</tr>
							<tr>
								<td style="width:80%">
									Remittances
								</td>
								<td style="width:20%;text-align:right">
									'.number_format_invoice($total_remittance).'
								</td>
							</tr>
							<tr>';
							$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
								$html.='<td style="width:80%;padding-top:15px">
									Closing Balance
								</td>
								<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
									'.number_format_invoice($closing_bal).'
								</td>
							</tr>
						</table>';
					}				
					$html .= '</div>';
					$offset=$offset+$max_limit;
				}
				$html .= '</div></div>';
			}
			else{
				$bg_img='content';
				$html .='<div id="page-body">';
				$html .= '<div id="'.$bg_img.'">
					<div style="margin:80px 20px 20px 20px;">';
				$html .= '<h4 style="text-align:center;font-size:20px;">Accountancy Fee Statement</h4>
					<table style="width:100%">
						<tr>
							<td style="width:50%">
								<spam>To:</spam><br/>
								'.$client_details->firstname.' '.$client_details->surname.'<br/>
								'.$client_details->company.'<br/>
								'.$client_details->address1.'<br/>
								'.$client_details->address2.'<br/>
								'.$client_details->address3.'<br/>
								'.$client_details->address4.'<br/>
								'.$client_details->address5.'<br/>
							</td>
							<td style="width:50%;vertical-align:top">
								<table style="width:100%;margin-top:18px">
									<tr>
										<td>Date: </td>
										<td style="text-align:right">'.date('d-M-Y').'</td>
									</tr>
									<tr>
										<td>Client Code: </td>
										<td style="text-align:right">'.$client_id.'</td>
									</tr>
									<tr>
										<td colspan="2">Payments can be made to:</td>
									</tr>
									<tr>
										<td>IBAN: </td>
										<td style="text-align:right">'.$iban.'</td>
									</tr>
									<tr>
										<td>BIC: </td>
										<td style="text-align:right">'.$bic.'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table style="width:100%;margin-top:40px">
						<tr>
							<td style="width:80%">
								Opening Balance @ '.$opening_bal_date.'
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
					</table>';	
				$html.= '<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
					<tr>
						<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
							Invoices Issued
						</td>
						<td style="width:40%;border-bottom:2px solid #000;text-align:center">
							Remittance
						</td>
					</tr>
					<tr>
						<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:left">Inv No</td>
									<td style="text-align:center">€</td>
								</tr>';
								$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_month)->where('invoice_date','<=',$to_month)->get();
								if(($invoices)){
									foreach($invoices as $inv){
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
											<td>'.$inv->invoice_number.'</td>
											<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
										</tr>';
										$total_inv_issued = $total_inv_issued + $inv->gross;
									}					
								}
								else if($invoice_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Invoice Issued</td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
						<td style="border-bottom:2px solid #000;vertical-align:top">
							<table style="width:100%">
								<tr>
									<td style="text-align:left">Date</td>
									<td style="text-align:center">€</td>
								</tr>';
								$receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month)->where('receipt_date','<=',$to_month)->get();
								if(($receipt_details))
								{
									foreach($receipt_details as $receipt)
									{
										$html.= '<tr>
											<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
											<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
										</tr>';
										$total_remittance = $total_remittance + $receipt->amount;
									}
								}
								else if($receipt_details_count != 0){
									$html.= '<tr>
											<td></td>
											<td></td>
											<td style="text-align:right"></td>
										</tr>';
								}
								else{
									$html.= '<tr>
											<td>No Remittance Found</td>
											<td style="text-align:right"></td>
										</tr>';
								}
							$html.= '</table>
						</td>
					</tr>';
				$html.='<tr>
					<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:left">Total</td>
								<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
							</tr>
						</table>
					</td>
					<td style="border-bottom:2px solid #000;vertical-align:top">
						<table style="width:100%">
							<tr>
								<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
							</tr>
						</table>
					</td>
				</tr>';					
				$html.='</table>';
				$html .= '<h5 style="text-align:center;font-size:14px;">Summary</h5>
					<table style="width:100%">
						<tr>
							<td style="width:80%">
								Opening Balance
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($opening_bal_value).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Invoices
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_inv_issued).'
							</td>
						</tr>
						<tr>
							<td style="width:80%">
								Remittances
							</td>
							<td style="width:20%;text-align:right">
								'.number_format_invoice($total_remittance).'
							</td>
						</tr>
						<tr>';
						$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
							$html.='<td style="width:80%;padding-top:15px">
								Closing Balance
							</td>
							<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
								'.number_format_invoice($closing_bal).'
							</td>
						</tr>
					</table>';
				$html .= '</div>';
				$html .= '</div></div>';
			}
		// echo $html;
		// dd();
		$upload_dir = 'uploads/statements';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/build_statement/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time().'/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$filename = $client_id.'_'.date('M', strtotime($from_month)).'_'.date('Y', strtotime($from_month)).'-TO-'.date('M', strtotime($to_month)).'_'.date('Y', strtotime($to_month)).'.pdf';
		// $data=[];
		// return view('Report.index');
		// $pdf = PDF ::loadView ('Report.index', $data)->setPaper('a4', 'portrait');
		$pdf = PDF::loadHTML($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->save($upload_dir.$filename);
        // return $pdf->stream('file-pdf.pdf');
		$dataval['client_id'] = $client_id;
		$dataval['month_year'] = $request->get('month');
		$dataval['url'] = $upload_dir.$filename;
		$dataval['filename'] = $filename;
		\App\Models\BuildStatementAttachments::insert($dataval);
		echo '<a href="'.URL::to($upload_dir.$filename).'" download>'.substr($filename,0,15).'...</a>';
	}
	public function deleteall_statement_monthly(Request $request)
	{
		$month = $request->get('month');
		// $explode_month = explode('-',$month);
		// $curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		// $currrr_month = $explode_month[1].'-'.$explode_month[0];
		// $monSel = date('m-Y', strtotime($curr_month));
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		foreach($client_details as $c_details)
		{
			$where=[
				'client_id'=>$c_details->client_id,
				'month_year'=>$month
			];
			// dd($where);
			$db_stmt=\App\Models\BuildStatementAttachments::where($where)->get();
			foreach($db_stmt as $d1){
				if (file_exists($d1->url)) {
					$fileurl = str_replace('\\', '/', $d1->url);
					unlink($fileurl);
				} else {
					// File not found.
				}				
				\App\Models\BuildStatementAttachments::where('id',$d1->id)->delete();
			}
		}
		echo $month;
	}
	public function export_statement_clients_monthly_view(Request $request){
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$from_month = $request->get('from_month');
		$to_month = $request->get('to_month');
		$from_month = date('M-Y', strtotime($from_month));
        $from_monthh = date('Y-m', strtotime($from_month));
        $from_str_month = $from_month;
        $from_month_date = date('Y-m-d', strtotime($from_month));
        $to_month = date('M-Y', strtotime($to_month));
        $to_monthh = date('Y-m', strtotime($to_month));
        $to_str_month = $to_month;
        $to_month_date = date('Y-m-t', strtotime($to_month));
        $opening_month =\App\Models\userLogin::first();
		$filename = time().'_Statement List From '.$from_month.' to '.$to_month.'.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');
        $opening_bal_month = date('Y-m-d', strtotime($opening_month->opening_balance_date));
        $from_date = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
        $to_date = date('Y-m-d', strtotime('last day of previous month', strtotime($from_str_month)));
        $columns1 = array("","","","","","","","","Post Month Activity","","","",date('M-Y', strtotime($from_str_month)).' - '.date('M-Y', strtotime($to_str_month)),"","");
        fputcsv($file, $columns1);
        $columns2 = array("S.No","ClientID","Company Name","Primary Email","Secondary Email","Send Statement","Opening Balance","Invoices","Received","Closing Balance","Invoices","Received","Closing Balance","Statement Sent","Build");
        fputcsv($file, $columns2);
		if(($clients))
		{
			$i = 1;
			foreach($clients as $client)
			{
				$client_id = $client->client_id;
				$opening_bal_details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = $opening_bal_details->opening_balance;
					}
				}
				$statement_details = \App\Models\ClientStatement::where('client_id',$client->client_id)->first();
				if(($statement_details))
				{
					$primary = $statement_details->email;
					$secondary = $statement_details->email2;
					$salutation = $statement_details->salutation;
				}
				else{
					$primary = $client->email;
					$secondary = $client->email2;
					$salutation = $client->salutation;
				}
				if($client->send_statement == 1) { $cecked = 'Yes'; } else { $cecked = 'No';  }
				$invoice_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('gross');
		        $receipt_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_date)->where('receipt_date','<=',$to_date)->sum('amount');
		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        $invoice_curr_details = \App\Models\InvoiceSystem::where('client_id',$client_id)->where('invoice_date','>=',$from_month_date)->where('invoice_date','<=',$to_month_date)->sum('gross');
		        $receipt_curr_details = \App\Models\receipts::where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','>=',$from_month_date)->where('receipt_date','<=',$to_month_date)->sum('amount');
		        $closing_curr_bal = ($closing_bal + $invoice_curr_details) - $receipt_curr_details;
		        $check_attachment = \App\Models\BuildStatementAttachments::where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($from_str_month)).'_'.date('m-Y', strtotime($to_str_month)))->first();
		    	if(($check_attachment))
		    	{
		    		$build_button = $check_attachment->filename;
		    	}
		    	else{
		    		$build_button = '';
		    	}
			    $columns3 = array($i,$client->client_id,$client->company,$primary,$secondary,$cecked,number_format_invoice_empty($opening_bal),number_format_invoice($invoice_details),number_format_invoice($receipt_details),number_format_invoice($closing_bal),number_format_invoice($invoice_curr_details),number_format_invoice($receipt_curr_details),number_format_invoice($closing_curr_bal),"",$build_button);
			    fputcsv($file, $columns3);
				$i++;
			}
		}
		fclose($file);
        echo $filename;
	}
	public function save_statement_from_month_value(Request $request){
		$value = $request->get('value');
		$current_month = date('M-Y');
        $current_monthh = date('m-Y');
        $curr_str_month = date('Y-m-01');
        $opening_month =\App\Models\userLogin::first();
        $opening_bal_month = $value;
        $edate = strtotime($curr_str_month);
        $bdate = strtotime($opening_bal_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $output = '';
        for($i= 1; $i<=$age; $i++)
        {
          $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
          $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
          $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
          if(date('m-Y') == $dateval) { $selected = 'selected="selected"'; } else { $selected = ''; }
          $output.='<option value="'.$datevalll.'" '.$selected.'>'.$datevall.'</option>';
          $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
        }
        // $data['statement_monthly_from_period'] = $value;
		//\App\Models\userLogin::where('id',1)->update($data);
        echo $output;
	}
	public function save_statement_to_month_value(Request $request){
		$from = $request->get('from');
		$to = $request->get('value');
		$from_str = strtotime($from);
		$to_str = strtotime($to);
		if($from_str > $to_str){
			echo 1;
		}
		else{
			$data['statement_monthly_to_period'] = $to;
			//\App\Models\userLogin::where('id',1)->update($data);
		}
	}
	public function clear_prev_loaded_data(Request $request)
	{
		$from_month = $request->get('from_month');
		$to_month = $request->get('to_month');
		$data['statement_monthly_from_period'] = '';
		$data['statement_monthly_to_period'] = '';
		\App\Models\userLogin::where('id',1)->update($data);
		$change_from_month = date('m-Y', strtotime($from_month));
        $change_to_month = date('m-Y', strtotime($to_month));
		$month = $change_from_month.'_'.$change_to_month;
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		foreach($client_details as $c_details)
		{
			$where=[
				'client_id'=>$c_details->client_id,
				'month_year'=>$month
			];
			// dd($where);
			$db_stmt=\App\Models\BuildStatementAttachments::where($where)->get();
			foreach($db_stmt as $d1){
				if (file_exists($d1->url)) {
					$fileurl = str_replace('\\', '/', $d1->url);
					unlink($fileurl);
				} else {
					// File not found.
				}				
				\App\Models\BuildStatementAttachments::where('id',$d1->id)->delete();
			}
		}
		echo $month;
	}
	public function notify_bank_account_clients(Request $request) {
		return view('user/statement/notify_bank_account_clients');
	}
	public function send_notify_bank_account_emails(Request $request) {
		$sender = $request->get('sender');
		$sender_details = DB::table("user")->where('user_id',$sender)->first();
		$sender_mail_address = $sender_details->email;
		$sender_name = $sender_details->firstname;
		$clientids = explode(',', $request->get('clientids'));
		$get_client_emails = \App\Models\CMClients::whereIn('client_id',$clientids)->get();

		$settingsval = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
		$practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();

		if(($get_client_emails)) {
			foreach($get_client_emails as $client) {
				$data['logo'] = getEmailLogo('statement');
				$data['firstname'] = $client->firstname;
				$data['practice_name'] = $practice_details->practice_name;
				$data['iban'] = (isset($settingsval->payments_to_iban))?$settingsval->payments_to_iban:'';
				$data['bic'] = (isset($settingsval->payments_to_bic))?$settingsval->payments_to_bic:'';

				$secondary_cc_email = (isset($settingsval->statement_cc_email))?$settingsval->statement_cc_email:'';
				$subject = $practice_details->practice_name.' - Bank Account Details';

				$contentmessage = view('emails/notify_bank_statement', $data);
				$email = new PHPMailer();
				$email->CharSet = 'UTF-8';
				$email->SetFrom($sender_mail_address,$sender_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				if($client->email2 != "") {
					$email->AddCC($client->email2);
				}
				if($secondary_cc_email != "") {
					$email->AddCC($secondary_cc_email);
				}
				$email->AddCC($settingsval->statement_cc_email);
				$email->IsHTML(true);
				$email->AddAddress($client->email);
				$email->Send();

				$datamessage['message_id'] = time();
				$datamessage['message_from'] = $sender;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $client->client_id;
				$datamessage['primary_emails'] = $client->email;
				$datamessage['secondary_emails'] = $client->email2;
				$datamessage['date_sent'] = date('Y-m-d H:i:s');
				$datamessage['date_saved'] = date('Y-m-d H:i:s');
				$datamessage['source'] = "Bank Details Notification";
				$datamessage['attachments'] = "";
				$datamessage['status'] = 1;
				$datamessage['practice_code'] = Session::get('user_practice_code');
				\App\Models\Messageus::insert($datamessage);
				//new SendNotifyBankEmailJob($details);
			}
			
		}
	}
	public function edit_statement_header_image(Request $request) {
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

                    DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
      }
}