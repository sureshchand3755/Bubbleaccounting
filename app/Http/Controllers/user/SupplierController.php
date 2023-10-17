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
class SupplierController extends Controller {
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
	public function supplier_management(Request $request)
	{
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$nominal_codes = \App\Models\NominalCodes::get();
		$vat_rates = \App\Models\supplierVatRates::get();
		return view('user/supplier_management/home', array('title' => 'Supplier Management','suppliers' => $suppliers,'nominal_codes' => $nominal_codes,'vat_rates' => $vat_rates));
	}
	public function store_supplier(Request $request)
	{
		$data['supplier_name'] = $request->get('supplier_name');
		$data['web_url'] = $request->get('supplier_address');
		$data['supplier_address'] = $request->get('supp_address');
		$data['phone_no'] = $request->get('phone_no');
		$data['email'] = $request->get('supplier_email');
		$data['iban'] = $request->get('supplier_iban');
		$data['bic'] = $request->get('supplier_bic');
		$data['vat_no'] = $request->get('vat_number');
		$data['currency'] = $request->get('currency');
		$data['opening_balance'] = $request->get('opening_balance');
		$data['default_nominal'] = $request->get('default_nominal');
		$data['username'] = $request->get('ac_username');
		$data['password'] = base64_encode($request->get('ac_password'));
		$data['practice_code'] = Session::get('user_practice_code');
		$id = $request->get('supplier_id');
		if($id == "")
		{
			$code = \App\Models\suppliers::insertDetails($data);
			$count = sprintf("%04d",$code);
			$pcodeval = Session::get('user_practice_code');
			$dataid['supplier_code'] = $pcodeval.$count;
			\App\Models\suppliers::where('id',$code)->update($dataid);
		}
		else{
			\App\Models\suppliers::where('id',$id)->update($data);
		}
		return redirect::back()->with('message','Supplier Added Successfully');
	}
	public function edit_supplier(Request $request)
	{
		$id = $request->get('id');
		$get_details = \App\Models\suppliers::where('id',$id)->first();
		if(($get_details))
		{
			$journals_details = \App\Models\Journals::where('connecting_journal_reference', $get_details->journal_id)->first();
			if(($journals_details)){
				$data['journal_date'] = date('d-M-Y', strtotime($journals_details->journal_date));
			}
			else{
				$data['journal_date'] = '';
			}
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['supplier_address'] = $get_details->supplier_address;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;
			$data['journal_id'] = $get_details->journal_id;
			$data['default_nominal'] = $get_details->default_nominal;
			$data['username'] = $get_details->username;
			$data['password'] = base64_decode($get_details->password);
			echo json_encode($data);
		}
	}
	public function get_supplier_transaction_list(Request $request)
	{
		$id = $request->get('id');
		$type = $request->get('type');
		if($type == 0){
			$class = "col-md-9";
			$display = "block";
		}
		else{
			$class = "col-md-12";
			$display = "none";
		}
		$details = \App\Models\suppliers::where('id',$id)->first();
		$output = '';
		if(($details))
		{
			$modal_title = $details->supplier_code.' '.$details->supplier_name;
			$output.='
			<div class="col-md-3 supplier_info_div">
				<table class="own_table_white table" style="width:100%">
					<tr>
						<td style="width:11%">Web Url: </td> 
						<td style="width:22%">'.$details->web_url.' </td>						
					</tr>
					<tr>
						<td>Email: </td>
						<td>'.$details->email.' </td>						
					</tr>
					<tr>
						<td>BIC: </td> 
						<td>'.$details->bic.' </td>						
					</tr>
					<tr>
						<td>Phone No: </td> 
						<td>'.$details->phone_no.' </td>
					</tr>
					<tr>
						<td>IBAN: </td> 
						<td>'.$details->iban.' </td>
					</tr>
					<tr>
						<td>VAT No: </td> 
						<td>'.$details->vat_no.' </td>
					</tr>
					<tr>
						<td colspan="2" style="padding:0px;">
							<a href="javascript:" class="common_black_button add_purchase_invoice" data-element="'.$id.'" data-supplier="'.$details->supplier_name.'" style="float:left; width:100%; margin-top:10px; margin-left:0px;">Add Purchase Invoice</a>
							<a href="javascript:" name="export_transaction_list" class="common_black_button export_transaction_list" id="export_transaction_list" style="float: left;width:100%; margin-top:10px; margin-left:0px;">Export as CSV</a>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						</td>
					</tr>
				</table>
			</div>';
			$formatted_bal = '0.00';
			$opening_bal = '0.00';
			if($details->opening_balance != "")
			{
				$opening_bal = $details->opening_balance;
				$formatted_bal = number_format_invoice($details->opening_balance);
			}
			if($opening_bal < 0)
			{
				$color = 'green';
				$textval = 'Client is Owed';
				$debit_open = $formatted_bal;
				$credit_open = '';
			}
			elseif($opening_bal > 0)
			{
				$color = 'red';
				$textval = 'Client Owes Back';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}
			else{
				$color = 'red';
				$textval = 'Opening Balance';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}
			$i=1;
			$output.='
			<div class="'.$class.'" style="max-height:700px; overflow:hidden; overflow-y:scroll;">
				<h4 style="display:'.$display.'">Transaction List</h4>
				<input type="hidden" name="hidden_trans_supplier_id" id="hidden_trans_supplier_id" value="'.$id.'">
				<table class="table own_table_white client_account_table" id="transaction_table" style="width: 100%;">
				<thead>
				<tr>
					<th style="display:none">#</th>
					<th>Date</th>
				    <th>Source</th>				    
				    <th>Description</th>				    				    
				    <th style="text-align:center">Debit</th>
				    <th style="text-align:center">Credit</th>
				    <th style="text-align:center">Balance</th>
				    <th style="text-align:center">Action</th>
					</tr>
				</thead>
				<tbody id="client_account_tbody">
					<tr>
						<td style="display:none">'.$i.'</td>
						<td></td>						
						<td style="color:'.$color.'">Opening Balance</td>
						<td style="color:'.$color.'">'.$textval.'</td>						
						<td style="color:'.$color.'; text-align:right">'.$debit_open.'</td>
						<td style="color:'.$color.'; text-align:right">'.$credit_open.'</td>
						<td style="color:'.$color.'; text-align:right">'.$formatted_bal.'</td>
						<td></td>
					</tr>';
					$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval,`payment_date` as transaction_date from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813" AND supplier_code = "'.$details->id.'"');
					$get_invoice = DB::select('SELECT *,UNIX_TIMESTAMP(`invoice_date`) as dateval,`invoice_date` as transaction_date from `supplier_global_invoice` WHERE supplier_id = "'.$details->id.'"');
					$get_invoice_payments=array_merge($get_payments,$get_invoice);					
					$dateval = array();
					foreach ($get_invoice_payments as $key => $row)
					{
					    $dateval[$key] = $row->dateval;
					}
					array_multisort($dateval, SORT_ASC, $get_invoice_payments);
					$balance_val = $details->opening_balance;
					if(($get_invoice_payments))
					{
						foreach($get_invoice_payments as $list)
						{
							if(isset($list->invoice_no)) { 
								$colorval = 'color:red';
								$source = '<a href="javascript:" class="purchase_class" style="'.$colorval.'" data-element="'.$list->id.'">Purchase Invoice</a>';
								$textvalue = 'Purchase Invoice - '.$list->invoice_no;
								$amount_invoice = number_format_invoice($list->gross);
								$amount_payment='';
								$balance_val = (int)$balance_val + (int)$list->gross;
								$invocie_text = '';
								$payment_text = '';
								/*$download = '<a href="'.URL::to($list->url.'/'.$list->filename).'" class="fa fa-download"  title="Download Invoice" download></a>';*/
								if($list->filename != ""){
									$download = '<a href="'.URL::to($list->url.'/'.$list->filename).'" class="fa fa-download" title="Download Invoice" download></a>';
									}
								else{
									$download = '';
								}
							}
							else{
								$colorval = 'color:green';
								$source = '<a href="javascript:" class="payment_viewer_class" style="'.$colorval.'" data-element="'.$list->payments_id.'">Payments</a>'; 
								$textvalue = 'Payment Made';
								$amount_payment = number_format_invoice($list->amount);
								$amount_invoice='';
								$balance_val = $balance_val - $list->amount;
								$download='';
							}
							if($balance_val <= 0) { $bal_color = 'color:green'; }
							else{ $bal_color = 'color:red'; }
							$output.='<tr>
								<td style="display:none">'.$i.'</td>
								<td style="'.$colorval.'">'.date('d-M-Y', strtotime($list->transaction_date)).'</td>
								<td style="'.$colorval.'">'.$source.'</td>								
								<td style="'.$colorval.'">'.$textvalue.'</td>								
								<td style="'.$colorval.'" align="right">'.$amount_payment.'</td>
								<td style="'.$colorval.'" align="right">'.$amount_invoice.'</td>
								<td align="right" style="'.$bal_color.';">'.number_format_invoice($balance_val).'</td>
								<td style="'.$colorval.'" align="center">'.$download.'</td>
							</tr>';
							$i++;
						}
					}
				$output.='</tbody>
				</table>
			</div>';
		}
		echo json_encode(array('output' => $output, 'modal_title' => $modal_title ));	
	}
	public function refresh_supplier_counts(Request $request)
	{
		$supplier_id = $request->get('id');
		$supplier_details = \App\Models\suppliers::where('id',$supplier_id)->first();
		$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier_details->id)->first();
        $payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier_details->id)->first();
        $balance = ((int)$supplier_details->opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
        $data['invoice_count'] = $invoice_count->invoice_count;
        $data['balance'] = number_format_invoice($balance);
        $data['opening_balance'] = number_format_invoice($supplier_details->opening_balance);
        $data['invoice_gross'] = number_format_invoice($invoice_count->gross_sum);
        $data['payment'] = number_format_invoice($payment_sum->payment_sum);
        echo json_encode($data);
	}
	public function refresh_all_supplier_counts(Request $request){
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$dataval = array();
		if(($suppliers)) {
			foreach($suppliers as $supplier){
				$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier->id)->first();
		        $payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->first();
		        $balance = ((int)$supplier->opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
		        $data['invoice_count'] = $invoice_count->invoice_count;
		        $data['balance'] = number_format_invoice($balance);
		        $data['opening_balance'] = number_format_invoice($supplier->opening_balance);
		        $data['invoice_gross'] = number_format_invoice($invoice_count->gross_sum);
		        $data['payment'] = number_format_invoice($payment_sum->payment_sum);
		        $dataval[$supplier->id] = $data;
			}
		}
		echo json_encode($dataval);
	}
	public function export_suppliers_list(Request $request){
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$columns_1 = array('Supplier Code', 'Supplier Name', 'Email Address', 'Web URL','Opening Balance','Invoice','Payments','Invoice Count', 'Balance Count');
		$filename = time().'_Supplier Management Report.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);
		if(($suppliers)){
			foreach($suppliers as $supplier){
				$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier->id)->first();
            	$payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('supplier_code',$supplier->id)->where('debit_nominal','813')->where('imported',0)->first();
            	$balance = ((int)$supplier->opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
            	$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier->id)->first();
		        $payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->first();
		        $balance = ((int)$supplier->opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
		        $data['invoice_count'] = $invoice_count->invoice_count;
		        $data['balance'] = number_format_invoice($balance);
		        $data['opening_balance'] = number_format_invoice($supplier->opening_balance);
		        $data['invoice_gross'] = number_format_invoice($invoice_count->gross_sum);
		        $data['payment'] = number_format_invoice($payment_sum->payment_sum);
            	$columns_2 = array($supplier->supplier_code, $supplier->supplier_name, $supplier->email, $supplier->web_url,number_format_invoice($supplier->opening_balance),number_format_invoice($invoice_count->gross_sum),number_format_invoice($payment_sum->payment_sum),$invoice_count->invoice_count, number_format_invoice($balance));
            	fputcsv($fileopen, $columns_2);
			}
		}
		fclose($fileopen);
        echo $filename;
	}
	public function load_single_invoice_payment(Request $request){
		$id = $request->get('id');
		$type = $request->get('type');
		if($type == 0){
			$global = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
			$output='<thead>
				<tr>
					<th>S.No</th>
					<th>Description</th>
					<th>Nominal Code</th>
					<th>Net Value</th>
					<th>VAT Rate</th>
					<th>VAT</th>					
					<th>Gross</th>
				</tr>
			</thead>';
			if(($global)){
	            $detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$id)->get();
	            $nominal_codes = \App\Models\NominalCodes::get();
				$vat_rates = \App\Models\supplierVatRates::get();
				$detail_invoice_count = count($detail_invoice);
	            $i = 1;
	            $total_net = 0;
	            $total_vat = 0;
	            $total_gross  = 0;
	            if(($detail_invoice)) {
	            	foreach($detail_invoice as $key => $detail) {
	            		$nominal_details = \App\Models\NominalCodes::where('id',$detail->nominal_code)->first();
	            		$code_name = '';
	            		if(($nominal_details)){
	            			$code_name = $nominal_details->code;
	            		}
	            		$output.= '<tr>
						  <td>'.$i.'</td>
						  <td>'.$detail->description.'</td>
						  <td>'.$code_name.'</td>
						  <td>'.number_format_invoice($detail->net).'</td>
						  <td>'.number_format_invoice($detail->vat_rate).'</td>
						  <td>'.number_format_invoice($detail->vat_value).'</td>
						  <td>'.number_format_invoice($detail->gross).'</td>
						</tr>';
						$i++;
						$total_net = number_format_invoice_without_comma(number_format_invoice_without_comma($total_net) + number_format_invoice_without_comma($detail->net));
						$total_vat = number_format_invoice_without_comma(number_format_invoice_without_comma($total_vat) + number_format_invoice_without_comma($detail->vat_value));
						$total_gross = number_format_invoice_without_comma(number_format_invoice_without_comma($total_gross) + number_format_invoice_without_comma($detail->gross));
	            	}
	            	$output.='<tr>
	            		<td colspan="3" style="text-align:right">Total:</td>
	            		<td>'.number_format_invoice($total_net).'</td>
	            		<td></td>
	            		<td>'.number_format_invoice($total_vat).'</td>
	            		<td>'.number_format_invoice($total_gross).'</td>
	            	</tr>';
	            }
			$page_title = 'Invoice Line Details';
		}
		}
		else{
			$payment = \App\Models\payments::where('payments_id',$id)->first();
			$output='<thead>
				<tr>
					<th>Date</th>
					<th>Debit Nominal </th>
					<th>Credit Nominal & Description</th>
					<th>Client/Supplier Code</th>
					<th>Debit Nominal Description </th>
					<th>Comment</th>
					<th style="text-align:right;">Amount</th>
					<th>Journal ID</th>
					<th>Status</th>
				</tr>
			</thead>';
			if(($payment)){
				$get_details = payment\App\Models\NominalCodes::where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$payment->payments_id.'">On Hold</a>';
				}
				else{
					$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'">Unhold</a>';
				}
				$output.='<tr>
					<td><spam class="date_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_sort_val">'.$payment->debit_nominal.'</td>
					<td class="credit_sort_val">'.$credit_nominal.'</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('id',$payment->supplier_code)->first();
						$output.='<td class="client_sort_val">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_sort_val">'.$payment->client_code.'</td>';
					}
					$output.='<td class="des_sort_val">'.$payment->debit_description.'</td>
					<td class="comment_sort_val">'.$payment->comment.'</td>	
					<td style="text-align:right"><spam class="amount_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>	
					<td style=";text-align:right"></td>	
					<td>'.$hold_status.'</td>	
				</tr>';
			}
			$page_title = 'Payments';
		}
		echo json_encode(array('page_title' => $page_title, 'output' => $output ));
	}
	public function supplier_opening_balance(Request $request)
	{
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$nominal_codes = \App\Models\NominalCodes::get();
		$vat_rates = \App\Models\supplierVatRates::get();
		return view('user/supplier_management/supplier_opening_balance', array('title' => 'Supplier Management','suppliers' => $suppliers,'nominal_codes' => $nominal_codes,'vat_rates' => $vat_rates));
	}
	public function supplier_journal_create(Request $request){
		$id = base64_decode($request->get('id'));
		$suppliers_details = \App\Models\suppliers::where('id', $id)->first();
		$finance_date =\App\Models\userLogin::where('id',1)->first();
		$journals = \App\Models\Journals::groupBy('reference')->get();		
		$journal_id = count($journals)+1;
		if(($suppliers_details)){
			$reference = 'POB'.$suppliers_details->id;
			if($suppliers_details->opening_balance > 0){
				$data['journal_date'] = $finance_date->opening_balance_date;
				$data['connecting_journal_reference'] = $journal_id;
				$data['reference'] = $reference;
				$data['description'] = 'POB - '.$suppliers_details->supplier_name;
				$data['nominal_code'] = '813';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $suppliers_details->opening_balance;
				$data['journal_source'] = 'POB';
				$data['practice_code'] = Session::get('user_practice_code');
				$data_secondary['journal_date']  = $finance_date->opening_balance_date;
				$data_secondary['connecting_journal_reference'] = $journal_id.'.01';
				$data_secondary['reference'] = $reference;
				$data_secondary['description'] = 'POB - '.$suppliers_details->supplier_name;
				$data_secondary['nominal_code'] = '991';
				$data_secondary['dr_value'] = $suppliers_details->opening_balance;
				$data_secondary['cr_value'] = '0.00';
				$data_secondary['journal_source'] = 'POB';
				$data_secondary['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				\App\Models\Journals::insert($data_secondary);
			}
			else{
				$balance = $suppliers_details->opening_balance*-1; 
				$data['journal_date'] = $finance_date->opening_balance_date;
				$data['connecting_journal_reference'] = $journal_id;
				$data['reference'] = $reference;
				$data['description'] = 'POB - '.$suppliers_details->supplier_name;
				$data['nominal_code'] = '991';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $balance;
				$data['journal_source'] = 'POB';
				$data['practice_code'] = Session::get('user_practice_code');
				$data_secondary['journal_date']  = $finance_date->opening_balance_date;
				$data_secondary['connecting_journal_reference'] = $journal_id.'.01';
				$data_secondary['reference'] = $reference;
				$data_secondary['description'] = 'POB - '.$suppliers_details->supplier_name;
				$data_secondary['nominal_code'] = '813';
				$data_secondary['dr_value'] = $balance;
				$data_secondary['cr_value'] = '0.00';
				$data_secondary['journal_source'] = 'POB';
				$data_secondary['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				\App\Models\Journals::insert($data_secondary);
			}
		}
		\App\Models\suppliers::where('id', $id)->update(['journal_id' =>  $journal_id]);
		$href='<a href="javascript:" class="journal_id_viewer" data-element="'.$journal_id.'">'.$journal_id.'</a>';
		echo json_encode(array('id' => $href));
	}
	public function run_creditors_listing(Request $request){
		$nil = $request->get('removed');
		$dateval = explode('/',$request->get('dateval'));
		$datevall = $dateval[2].'-'.$dateval[1].'-'.$dateval[0];
		$strtotime_selected = strtotime($datevall);
		$date =\App\Models\userLogin::where('id',1)->first();
		$financial_date = $date->opening_balance_date;
		$strtotime_financial = strtotime($financial_date);
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		if(($suppliers))
		{
			foreach($suppliers as $supplier){
				if($strtotime_selected >= $strtotime_financial){
					$opening_balance = $supplier->opening_balance;
					$opening_balance_listed = number_format_invoice($supplier->opening_balance);
				}
				else{
					$opening_balance = '0.00';
					$opening_balance_listed = '-';
				}
				$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier->id)->where('invoice_date','<=',$datevall)->first();
	            $payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->where('payment_date','<=',$datevall)->first();
	            $balance = ((int)$opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
	            $invoice_gross = number_format_invoice($invoice_count->gross_sum);
	            $payment = number_format_invoice($payment_sum->payment_sum);
	            $show_tr = '0';
	            if($nil == "1"){
	            	if(number_format_invoice($balance) == "" || number_format_invoice($balance) == "0.00"){
	            		$show_tr = '1';
	            	}
	            }
	            if($show_tr != "1"){
	            	$output.='<tr class="supp_report_tr_'.$supplier->id.' '.$show_tr.'">
		              <td>'.$supplier->supplier_code.'</td>
		              <td>'.$supplier->supplier_name.'</td>
		              <td>'.$supplier->email.'</td>
		              <td class="class_opening_td" align="right">
		                '.$opening_balance_listed.'
		              </td>
		              <td class="class_invoice_td" align="right">
		                '.$invoice_gross.'
		              </td>
		              <td class="class_payment_td" align="right">
		                '.$payment.'
		              </td>
		              <td class="balance_count_td" style="text-align: right">'.number_format_invoice($balance).'</td>
		            </tr>';
	            }
			}
		}
		echo $output;
	}
	public function export_creditors_listing(Request $request){
		$nil = $request->get('removed');
		$dateval = explode('/',$request->get('dateval'));
		$datevall = $dateval[2].'-'.$dateval[1].'-'.$dateval[0];
		$dateformat = date('d-m-Y H:i:s', strtotime($datevall));
		$strtotime_selected = strtotime($datevall);
		$date =\App\Models\userLogin::where('id',1)->first();
		$financial_date = $date->opening_balance_date;
		$strtotime_financial = strtotime($financial_date);
		$columns_1 = array('Supplier Code', 'Supplier Name', 'Email Address','Opening Balance','Invoice','Payments','Balance');
		$filename = 'Supplier Listing at '.$dateformat.'.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);
		$suppliers = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		if(($suppliers))
		{
			foreach($suppliers as $supplier){
				if($strtotime_selected >= $strtotime_financial){
					$opening_balance = $supplier->opening_balance;
					$opening_balance_listed = number_format_invoice($supplier->opening_balance);
				}
				else{
					$opening_balance = '0.00';
					$opening_balance_listed = '-';
				}
				$invoice_count = \App\Models\supplier_global_invoice::select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('practice_code', Session::get('user_practice_code'))->where('supplier_id',$supplier->id)->where('invoice_date','<=',$datevall)->first();
	            $payment_sum = \App\Models\payments::select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->where('payment_date','<=',$datevall)->first();
	            $balance = ((int)$opening_balance + (int)$invoice_count->gross_sum) - (int)$payment_sum->payment_sum;
	            $invoice_gross = number_format_invoice($invoice_count->gross_sum);
	            $payment = number_format_invoice($payment_sum->payment_sum);
	            $show_tr = '0';
	            if($nil == "1"){
	            	if($balance == "" || $balance == "0" || $balance == "0.00"){
	            		$show_tr = '1';
	            	}
	            }
	            if($show_tr != "1"){
	            	$columns_2 = array($supplier->supplier_code, $supplier->supplier_name, $supplier->email,$opening_balance_listed,$invoice_gross,$payment,number_format_invoice($balance));
				    fputcsv($fileopen, $columns_2);
	            }
			}
		}
		fclose($fileopen);
        echo $filename;
	}
}