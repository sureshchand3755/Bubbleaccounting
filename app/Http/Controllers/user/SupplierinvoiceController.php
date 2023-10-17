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
class SupplierinvoiceController extends Controller {
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
	public function supplier_invoice_management(Request $request)
	{
		$global_invoices = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->get();
		return view('user/supplier_invoice_management/home', array('title' => 'Supplier Invoice Management','global_invoices' => $global_invoices));
	}
	public function get_supplier_info_details(Request $request)
	{
		$id = $request->get('id');
		$get_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		if(($get_details))
		{
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;
			$data['default_nominal'] = $get_details->default_nominal;
			echo json_encode($data);
		}
	}
	public function store_purchase_invoice(Request $request)
	{
		$supplier = $request->get('select_supplier');
		$inv_no_global = $request->get('inv_no_global');
		$inv_date_global = $request->get('inv_date_global');
		$ref_global = $request->get('ref_global');
		$net_global = $request->get('net_global');
		$vat_global = $request->get('vat_global');
		$gross_global = $request->get('gross_global');
		$global_url = $request->get('global_file_url');
		$global_filename = $request->get('global_file_name');
		$globalid = $request->get('hidden_global_id');
		$sno = $request->get('hidden_sno');
		$ac_accounting_id = $request->get('ac_accounting_id');
		$inv_date = explode('-',$inv_date_global);
		$invoice_date = '';
		if(($inv_date)) {
			$invoice_date = $inv_date[2].'-'.$inv_date[1].'-'.$inv_date[0];
		}
		$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$supplier)->first();
		$data['supplier_id'] = $supplier;
		$data['invoice_no'] = $inv_no_global;
		$data['invoice_date'] = $invoice_date;
		$data['reference'] = $ref_global;
		$data['net'] = $net_global;
		$data['vat'] = $vat_global;
		$data['gross'] = $gross_global;
		$data['ac_period'] = $ac_accounting_id;
		if($globalid != "") {
			$filename = $request->get('global_file_name');
			if($filename != "")
			{
				$datafile['status'] = 0;
				$datafile['supplier_id'] = $supplier;
				$datafile['inv_date'] = $invoice_date;
				\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('filename',$filename)->update($datafile);
			}
			$global_details = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$globalid)->first();
			\App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$globalid)->update($data);
			$next_connecting_journal = $global_details->journal_id;
			\App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$globalid)->delete();
			\App\Models\Journals::where('connecting_journal_reference',$next_connecting_journal)->delete();
			\App\Models\Journals::where('connecting_journal_reference','like',$next_connecting_journal.'.%')->delete();
			$global_invoices_sno = $sno;
		}
		else{
			$data['filename'] = $global_filename;
			$data['url'] = $global_url;
			\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('filename',$data['filename'])->update(['status' => 0,'inv_date' => $invoice_date, 'supplier_id' => $supplier]);
			$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$data['journal_id'] = $next_connecting_journal;
			$data['practice_code'] = Session::get('user_practice_code');
			$globalid = \App\Models\supplier_global_invoice::insertDetails($data);
			$global_invoices_sno = 1;
		}
		$journal_ref = time().'_SPI_'.$globalid;
		$datajournal['journal_date'] = $invoice_date;
		$datajournal['connecting_journal_reference'] = $next_connecting_journal;
		$datajournal['reference'] = $journal_ref;
		$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
		$datajournal['nominal_code'] = '813'; 	
		$datajournal['dr_value'] = '0.00';
		$datajournal['cr_value'] = $gross_global; 	
		$datajournal['journal_source'] = 'PI';
		$datajournal['practice_code'] = Session::get('user_practice_code');
		\App\Models\Journals::insert($datajournal);
		if($vat_global == "0" || $vat_global == "0.00" || $vat_global == "0.0") {
		}
		else{
			$next_connecting_journal = $next_connecting_journal + 0.01;
			$datajournal['journal_date'] = $invoice_date;
			$datajournal['connecting_journal_reference'] = $next_connecting_journal;
			$datajournal['reference'] = $journal_ref;
			$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
			$datajournal['nominal_code'] = '845'; 	
			$datajournal['dr_value'] = $vat_global;
			$datajournal['cr_value'] = '0.00'; 	
			$datajournal['journal_source'] = 'PI';
			$datajournal['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::insert($datajournal);
		}
		$next_connecting_journal = $next_connecting_journal + 0.01;
		$des_detail = $request->get('description_detail');
		$code_detail = $request->get('select_nominal_codes');
		$net_detail = $request->get('net_detail');
		$vat_rate_detail = $request->get('select_vat_rates');
		$vat_detail = $request->get('vat_detail');
		$gross_detail = $request->get('gross_detail');
		if(($code_detail)) {
			foreach($code_detail as $key => $code) {
				$datadetail['global_id'] = $globalid;
				$datadetail['invoice_no'] = $inv_no_global;
				$datadetail['description'] = $des_detail[$key];
				$datadetail['nominal_code'] = $code;
				$datadetail['net'] = $net_detail[$key];
				$datadetail['vat_rate'] = $vat_rate_detail[$key];
				$datadetail['vat_value'] = $vat_detail[$key];
				$datadetail['gross'] = $gross_detail[$key];
				$datadetail['practice_code'] = Session::get('user_practice_code');
				//$datadetail['journal_id'] = $next_connecting_journal;
				$detailid = \App\Models\supplier_detail_invoice::insertDetails($datadetail);
				$code_details = \App\Models\NominalCodes::where('id',$code)->first();
				$datajournal['journal_date'] = $invoice_date;
				$datajournal['connecting_journal_reference'] = $next_connecting_journal;
				$datajournal['reference'] = $journal_ref;
				$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
				$datajournal['nominal_code'] = $code_details->code; 	
				$datajournal['dr_value'] = $net_detail[$key];
				$datajournal['cr_value'] = '0.00'; 	
				$datajournal['journal_source'] = 'PI';
				$datajournal['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($datajournal);
				$next_connecting_journal = $next_connecting_journal + 0.01;
			}
		}
		$exp_journal_id = explode(".",$next_connecting_journal);
		$journal_id = $exp_journal_id[0];
		return redirect::back()->with('message','Invoice has been saved with Serial Number - '.$global_invoices_sno.', Journal ID Number '.$journal_id.' Do you want to review the journal now? <a href="javascript:" class="common_black_button journal_id_viewer" data-element="'.$journal_id.'">View Journal</a>');
	}
	public function supplier_upload_global_files(Request $request)
	{
		$global_id = $request->get('hidden_global_inv_id');
		$upload_dir = 'uploads/supplier_global_files';
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
			$fname = time().'_'.$fname;
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);
			$download_url = URL::to($filename);
			if($global_id != ""){
				$data['url'] = $upload_dir;
				$data['filename'] = $fname; 
				\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('filename',$data['filename'])->update(['status' => 0]);
				\App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$global_id)->update($data);
			}
		 	echo json_encode(array('filename' => $fname, 'url' => $upload_dir, 'download_url' => $download_url));
		}
	}
	public function purchase_invoice_files(Request $request)
	{
		$upload_dir = 'uploads/supplier_global_files';
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
			$fname = time().'_'.$fname;
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);
			$download_url = URL::to($filename);
			$data['url'] = $upload_dir;
			$data['filename'] = $fname; 
			$data['status'] = 1; 
			$data['practice_code'] = Session::get('user_practice_code');

			\App\Models\supplierPurchaseInvoiceFiles::insert($data);
		 	echo json_encode(array('filename' => $fname, 'url' => $upload_dir, 'download_url' => $download_url));
		}
	}
	public function edit_purchase_invoice_supplier(Request $request)
	{
		$id = $request->get('id');
		$global = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		$supplier_output = '';
		$global_output = '';
		if(($global))
		{
			$supplier_id = $global->supplier_id;
			$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$supplier_id)->first();
			$ac_period = $global->ac_period;
			$supplier_output = '
			<tr>
              <td style="font-weight:600">Supplier Code: </td> 
              <td class="supplier_code_td">'.$supplier_details->supplier_code.'</td>
              <td style="font-weight:600">Supplier Name: </td> 
              <td class="supplier_name_td">'.$supplier_details->supplier_name.'</td>
              <td style="font-weight:600">Web Url: </td> 
              <td class="web_url_td">'.$supplier_details->web_url.'</td>
            </tr>
            <tr>
              <td style="font-weight:600">Phone No: </td> 
              <td class="phone_no_td">'.$supplier_details->phone_no.'</td>
              <td style="font-weight:600">Email: </td>
              <td class="email_td">'.$supplier_details->email.'</td>
              <td style="font-weight:600">IBAN: </td> 
              <td class="iban_td">'.$supplier_details->iban.'</td>
            </tr>
            <tr>
              <td style="font-weight:600">BIC: </td> 
              <td class="bic_td">'.$supplier_details->bic.'</td>
              <td style="font-weight:600">VAT No: </td> 
              <td class="vat_no_td">'.$supplier_details->vat_no.'</td>
              <td></td>
              <td></td>
            </tr>';
            $thisinput = "this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');";
            $global_output = '<tr>
                  <td><input type="text" name="inv_no_global" class="form-control inv_no_global" value="'.$global->invoice_no.'" placeholder="Enter Invoice No" required></td>
                  <td><input type="text" name="inv_date_global" class="form-control inv_date_global" value="'.date('d-m-Y', strtotime($global->invoice_date)).'" placeholder="Enter Invoice Date" required></td>
                  <td><input type="text" name="ref_global" class="form-control ref_global" value="'.$global->reference.'" placeholder="Enter Reference" required></td>
                  <td><input type="text" name="net_global" class="form-control net_global" value="'.$global->net.'" placeholder="Enter Net Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'" required></td>
                  <td></td>
                  <td><input type="text" name="vat_global" class="form-control vat_global" value="'.$global->vat.'" placeholder="Enter VAT Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'" required></td>
                  <td><input type="text" name="gross_global" class="form-control gross_global" value="'.$global->gross.'" placeholder="Enter Gross" readonly required></td>
                </tr>';
                $attachment_output = '<spam class="global_file_upload">';
                    if($global->filename != ""){
                    	$attachment_output.='<a href="'.URL::To($global->url.'/'.$global->filename).'" class="file_attachments" download>'.$global->filename.'</a> <a href="javascript:" class="fa fa-trash delete_global_attachments"></a>';
                    }
                    $attachment_output.='</spam>
                    <input type="hidden" name="global_file_url" id="global_file_url" value="'.$global->url.'">
                    <input type="hidden" name="global_file_name" id="global_file_name" value="'.$global->filename.'">
                    <a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Insert Files" data-element="'.$global->id.'"></a>';
            $detail_output = '';
            $detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$id)->get();
            $nominal_codes = \App\Models\NominalCodes::orderBy('code','asc')->get();
			$vat_rates = \App\Models\supplierVatRates::where('practice_code', Session::get('user_practice_code'))->get();
			$detail_invoice_count = count($detail_invoice);
            $i = 1;
            if(($detail_invoice)) {
            	foreach($detail_invoice as $key => $detail) {
            		$detail_output.= '<tr>
					  <td>'.$i.'</td>
					  <td><input type="text" name="description_detail[]" class="form-control description_detail" value="'.$detail->description.'" placeholder="Enter Description"></td>
					  <td>
					    <select name="select_nominal_codes[]" class="form-control select_nominal_codes">
					      <option value="">Select Nominal Code</option>';
					      if(($nominal_codes)) {
					        foreach($nominal_codes as $code){
					        	if($code->id == $detail->nominal_code) { $selected = 'selected'; }
					        	else { $selected = ''; }
					          	$detail_output.='<option value="'.$code->id.'" '.$selected.'>'.$code->code.' - '.$code->description.'</option>';
					        }
					      }
					    $detail_output.='</select>
					  </td>
					  <td><input type="text" name="net_detail[]" class="form-control net_detail" value="'.$detail->net.'" placeholder="Enter Net Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'"></td>
					  <td>
					    <select name="select_vat_rates[]" class="form-control select_vat_rates">
					      <option value="">Select VAT Rate</option>';
					      if(($vat_rates)) {
					        foreach($vat_rates as $rate){
					        	if($rate->vat_rate == $detail->vat_rate) { $selected = 'selected'; }
					        	else { $selected = ''; }
					          	$detail_output.='<option value="'.$rate->vat_rate.'" '.$selected.'>'.$rate->vat_rate.' %</option>';
					        }
					      }
					    $detail_output.='</select>
					  </td>
					  <td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="'.$detail->vat_value.'" placeholder="Enter VAT Value" readonly></td>
					  <td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="'.$detail->gross.'" placeholder="Enter Gross" readonly></td>
					  <td class="detail_last_td" style="vertical-align: middle;text-align: center">
					    <a href="javascript:" class="fa fa-trash remove_detail_section"></a>';
					    $keyval = $key + 1;
					    if($detail_invoice_count == $keyval) {
					    	$detail_output.='&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>';
					    }
					    else{
					    	$detail_output.='';
					    }
					  $detail_output.='</td>
					</tr>';
					$i++;
            	}
            }
            $datajson['supplier_output'] = $supplier_output;
            $datajson['global_output'] = $global_output;
            $datajson['detail_output'] = $detail_output;
            $datajson['attachment_output'] = $attachment_output;
            $datajson['supplier_id'] = $supplier_id;
            $datajson['ac_period'] = $ac_period;
            echo json_encode($datajson);	
        }
	}
	public function view_purchase_invoice_supplier(Request $request)
	{
		$id = $request->get('id');
		$global = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		if(($global))
		{
            $detail_output = '';
            $detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$id)->get();
            $nominal_codes = \App\Models\NominalCodes::get();
			$vat_rates = \App\Models\supplierVatRates::where('practice_code', Session::get('user_practice_code'))->get();
			$detail_invoice_count = count($detail_invoice);
            $i = 1;
            $detail_output = '';
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
            		$detail_output.= '<tr>
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
            	$detail_output.='<tr>
            		<td colspan="3" style="text-align:right">Total:</td>
            		<td>'.number_format_invoice($total_net).'</td>
            		<td></td>
            		<td>'.number_format_invoice($total_vat).'</td>
            		<td>'.number_format_invoice($total_gross).'</td>
            	</tr>';
            }
            $datajson['detail_output'] = $detail_output;
            echo json_encode($datajson);	
        }
	}
	public function load_all_global_invoice(Request $request)
	{
		$type = $request->get('type');
		if($type == "1")
		{
			$year = $request->get('year');
			$invoicelist = DB::select('SELECT * from `supplier_global_invoice` WHERE `practice_code` = "'.Session::get('user_practice_code').'" AND `invoice_date` LIKE "'.$year.'%" ORDER BY `id` DESC');
		}
		elseif($type == "2")
		{
			$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->get();
		}
		elseif($type == "3")
		{
			$from = date('Y-m-d', strtotime($request->get('from')));
			$to = date('Y-m-d', strtotime($request->get('to')));
			$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->orderBy('id','desc')->get();
		}
		elseif($type == "4")
		{
			$invoice_ac_period = $request->get('invoice_ac_period');
			$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period', $invoice_ac_period)->orderBy('id','desc')->get();
		}
		$i = 1;
		$output = '';
		if(($invoicelist)){ 
			foreach($invoicelist as $key => $global){ 
				$inv_date_str = strtotime($global->invoice_date);
				$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
	            $supplier_code = '';
	            $supplier_name = '';
	            $balance = 0.00;
	            if(($supplier_details)) {
	              $supplier_code = $supplier_details->supplier_code;
	              $supplier_name = $supplier_details->supplier_name;
	              $balance = $supplier_details->opening_balance;
	            }
	            $ac_period = \App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->where('accounting_id', $global->ac_period)->first();
	            $start_date = date('d M Y', strtotime($ac_period->ac_start_date));
	            $end_date = date('d M Y', strtotime($ac_period->ac_end_date));
	            if($ac_period->ac_lock == 0){              
	              $period_color = '#E11B1C';              
	            }
	            else{              
	              $period_color='#33CC66';              
	            }
				$output.='<tr>
	              <td>'.$i.'</td>
	              <td>'.$supplier_code.'</td>
	              <td>'.$supplier_name.'</td>	              
	              <td>'.date('d-M-Y', strtotime($global->invoice_date)).'</td>
	              <td style="color:'.$period_color.'; font-weight:bold">'.$start_date.' - '.$end_date.'</td>
	              <td>'.$global->invoice_no.'</td>
	              <td style="text-align: right">'.number_format_invoice($balance).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->net).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->vat).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->gross).'</td>
	              <td><a href="javascript:" class="journal_id_viewer" data-element="'.$global->journal_id.'">'.$global->journal_id.'</a></td>
	              <td><a href="javascript:" class="fa fa-edit edit_purchase_invoice" data-element="'.$global->id.'" title="Edit Purchase Invoice"></a></td>
	            </tr>';
				$i++;
			}
		}
		if($i == 1)
        {
          $output.='<tr>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center">Empty</td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          </tr>';
        }
        echo $output;
	}
	public function export_all_global_invoice(Request $request)
	{
		$type = $request->get('type');
		if($type == "1")
		{
			$year = $request->get('year');
			$invoicelist = DB::select('SELECT * from `supplier_global_invoice` WHERE `practice_code` = "'.Session::get('user_practice_code').'" AND `invoice_date` LIKE "'.$year.'%"');
		}
		elseif($type == "2")
		{
			$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->get();
		}
		elseif($type == "3")
		{
			$from = date('Y-m-d', strtotime($request->get('from')));
			$to = date('Y-m-d', strtotime($request->get('to')));
			$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
		}
		$columns_1 = array('S.No', 'Invoice No', 'Date', 'Supplier Code','Supplier Name', 'Net', 'VAT', 'Gross', 'Journal ID');
		$filename = time().'_Supplier Invoice Management Report.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);
		$i = 1;
		$output = '';
		if(($invoicelist)){ 
			foreach($invoicelist as $key => $global){ 
				$inv_date_str = strtotime($global->invoice_date);
				$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
	            $supplier_code = '';
	            $supplier_name = '';
	            $balance = 0.00;
	            if(($supplier_details)) {
	              $supplier_code = $supplier_details->supplier_code;
	              $supplier_name = $supplier_details->supplier_name;
	              $balance = $supplier_details->opening_balance;
	            }
	            $columns_2 = array($i, $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
				fputcsv($fileopen, $columns_2);
				$i++;
			}
		}
		fclose($fileopen);
        echo $filename;
	}
	public function export_supplier_transaction_list(Request $request)
	{
		$id = $request->get('supplier_id');
		$details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		if(($details))
		{
			$formatted_bal = '0.00';
			$opening_bal = '0.00';
			if($details->opening_balance != "")
			{
				$opening_bal = $details->opening_balance;
				$formatted_bal = number_format_invoice($details->opening_balance);
			}
			if($opening_bal < 0)
			{
				$textval = 'Client is Owed';
				$debit_open = $formatted_bal;
				$credit_open = '';
			}
			elseif($opening_bal > 0)
			{
				$textval = 'Client Owes Back';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}
			else{
				$textval = 'Opening Balance';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}
			$columns_1 = array('Date', 'Source', 'Description', 'Debit', 'Credit','Balance');
			$filename = time().'_Supplier Transaction List Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);
		    $columns_2 = array('', 'Opening Balance', 'Opening Balance', $debit_open, $credit_open,$formatted_bal);
		    fputcsv($fileopen, $columns_2);
		    $get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval,`payment_date` as transaction_date from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813" AND supplier_code = "'.$details->id.'"');
			$get_invoice = DB::select('SELECT *,UNIX_TIMESTAMP(`invoice_date`) as dateval,`invoice_date` as transaction_date from `supplier_global_invoice` WHERE `practice_code` = "'.Session::get('user_practice_code').'" AND `supplier_id` = "'.$details->id.'"');
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
						$source = 'Purchase Invoice';
						$textvalue = 'Purchase Invoice - '.$list->invoice_no;
						$amount_invoice = number_format_invoice($list->gross);
						$amount_payment='';
						$balance_val = $balance_val + $list->gross;
					}
					else{
						$source = 'Payments'; 
						$textvalue = 'Payment Made';
						$amount_payment = number_format_invoice($list->amount);
						$amount_invoice='';
						$balance_val = $balance_val - $list->amount;
					}
					$columns_3 = array(date('d-M-Y', strtotime($list->transaction_date)), $source, $textvalue, $amount_payment,$amount_invoice,number_format_invoice($balance_val));
		    		fputcsv($fileopen, $columns_3);
				}
			}
			fclose($fileopen);
        	echo $filename;
		}
	}
	public function store_supplier_vat_rate(Request $request)
	{
		$value = $request->get('value');
		$tr_length = $request->get('tr_length');
		$ival = $tr_length + 1;
		$data['vat_rate'] = $value;
		$data['status'] = 0;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\supplierVatRates::insertDetails($data);
		echo '<tr class="vat_tr">
			<td>'.$ival.'</td>
			<td>'.$value.' %</td>
			<td>
				<a href="javascript:" class="fa fa-check" data-element="'.$id.'" data-status="1" title="Disable Rate" style="color:green"></a>
			</td>
		</tr>';
	}
	public function change_supplier_vat_status(Request $request)
	{
		$id = $request->get('id');
		$status = $request->get('status');
		$data['status'] = $status;
		\App\Models\supplierVatRates::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->update($data);
	}
	public function delete_supplier_global_attachment(Request $request){
		$global_id = $request->get('global_id');
		$data['url'] = '';
		$data['filename'] = '';
		\App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$global_id)->update($data);
	}
	public function check_supplier_journal_repost(Request $request){
		$globalid = $request->get('invid');
		$global_details = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id',$globalid)->first();
		if($global_details) {
			$next_connecting_journal = $global_details->journal_id;
			$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global_details->supplier_id)->first();
			\App\Models\Journals::where('connecting_journal_reference',$next_connecting_journal)->delete();
			\App\Models\Journals::where('connecting_journal_reference','like',$next_connecting_journal.'.%')->delete();
			$journal_ref = time().'_'.$global_details->reference;
			$datajournal['journal_date'] = $global_details->invoice_date;
			$datajournal['connecting_journal_reference'] = $next_connecting_journal;
			$datajournal['reference'] = $journal_ref;
			$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
			$datajournal['nominal_code'] = '813'; 	
			$datajournal['dr_value'] = '0.00';
			$datajournal['cr_value'] = $global_details->gross; 	
			$datajournal['journal_source'] = 'PI';
			$datajournal['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::insert($datajournal);
			if($global_details->vat == "0" || $global_details->vat == "0.00" || $global_details->vat == "0.0") {
			}
			else{
				$next_connecting_journal = $next_connecting_journal + 0.01;
				$datajournal['journal_date'] = $global_details->invoice_date;
				$datajournal['connecting_journal_reference'] = $next_connecting_journal;
				$datajournal['reference'] = $journal_ref;
				$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
				$datajournal['nominal_code'] = '845'; 	
				$datajournal['dr_value'] = $global_details->vat;
				$datajournal['cr_value'] = '0.00'; 	
				$datajournal['journal_source'] = 'PI';
				$datajournal['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($datajournal);
			}
			$next_connecting_journal = $next_connecting_journal + 0.01;
			$detail_invs = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$globalid)->get();
			if(($detail_invs))
			{
				foreach($detail_invs as $inv){
					$code_details = \App\Models\NominalCodes::where('id',$inv->nominal_code)->first();
					$codes = '';
					if(($code_details)){
						$codes = $code_details->code; 
					}
					$datajournal['journal_date'] = $global_details->invoice_date;
					$datajournal['connecting_journal_reference'] = $next_connecting_journal;
					$datajournal['reference'] = $journal_ref;
					$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
					$datajournal['nominal_code'] = $codes;
					$datajournal['dr_value'] = $inv->net;
					$datajournal['cr_value'] = '0.00'; 	
					$datajournal['journal_source'] = 'PI';
					$datajournal['practice_code'] = Session::get('user_practice_code');
					\App\Models\Journals::insert($datajournal);
					$next_connecting_journal = $next_connecting_journal + 0.01;
				}
			}
		}
	}
	public function store_supplier_invoice(Request $request)
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
			$id = $code;
			$count = sprintf("%04d",$code);
			$pcodeval = Session::get('user_practice_code');
			$dataid['supplier_code'] = $pcodeval.$count;
			\App\Models\suppliers::where('id',$code)->update($dataid);
		}
		else{
			\App\Models\suppliers::where('id',$id)->update($data);
		}
		$supplier_count_invoice = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->first(); 
        if(($supplier_count_invoice))
        {
          $count_invoice = substr($supplier_count_invoice->supplier_code,3,7);
          $count_invoice = sprintf("%04d",$count_invoice + 1);
        }
        else{
          $count_invoice = sprintf("%04d",1);
        }
        $message = '<p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Supplier Added Successfully</p>';
        $suppliers = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->orderBy('supplier_name','asc')->get();
        $drop_supplier='<option value="">Select Supplier</option>';
        if(($suppliers)){
		    foreach($suppliers as $supplier){
		    	if($id == $supplier->id){
		    		$selected = 'selected';
		    	}
		    	else{
		    		$selected = '';
		    	}
		    	$drop_supplier.='<option value="'.$supplier->id.'" '.$selected.'>'.$supplier->supplier_name.'</option>';
		    }
		}
		$get_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		if(($get_details))
		{
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;
			$pcodeval = Session::get('user_practice_code');
			$data['code'] = $pcodeval.$count_invoice;
			$data['message'] = $message;
			$data['drop_supplier'] = $drop_supplier;
			echo json_encode($data);
		}
		//echo json_encode(array('code' =>  'message' => $message, 'drop_supplier' => $drop_supplier));
		//return redirect::back()->with('message','Supplier Added Successfully');
	}
	public function purchase_invoice_to_process(Request $request){
		$global_invoices = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->get();
		$unprocessed_purchase_files = \App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->get();
		// $processed_purchase_files = \App\Models\supplierPurchaseInvoiceFiles::where('status',0)->orderBy('id','desc')->get();
		$suppliers = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->orderBy('supplier_name','ASC')->get();
		return view('user/supplier_invoice_management/purchase_invoice', array('title' => 'Supplier Invoice Management','global_invoices' => $global_invoices,'unprocessed_purchase_files' => $unprocessed_purchase_files, 'suppliers' => $suppliers));
	}
	public function change_supplier_files_supplier_id(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$data['supplier_id'] = $value;
		\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->update($data);
	}
	public function change_supplier_files_inv_date(Request $request){
		$id = $request->get('id');
		$value = explode('-',$request->get('value'));
		$data['inv_date'] = $value[2].'-'.$value[1].'-'.$value[0];
		\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->update($data);
	}
	public function change_supplier_files_ignore_file(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$data['ignore_file'] = $value;
		\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->update($data);
	}
	public function delete_purchase_files(Request $request){
		$id = $request->get('id');
		\App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->delete();
	}
	public function get_purchase_invoice_files_details(Request $request){
		$id = $request->get('id');
		$details = \App\Models\supplierPurchaseInvoiceFiles::where('practice_code', Session::get('user_practice_code'))->where('id',$id)->first();
		if(($details))
		{
			$data['filename'] = $details->filename;
			$data['url'] = $details->url;
			$data['supplier_id'] = $details->supplier_id;
			if($details->inv_date != ""){
				$data['inv_date'] = date('d-m-Y', strtotime($details->inv_date));
			} else {
				$data['inv_date'] = '';
			}
			echo json_encode($data);
		}
	}
	public function supplier_invoice_report_download(Request $request){
		$type = $request->get('type');
		$from = date('Y-m-d', strtotime($request->get('from')));
		$to = date('Y-m-d', strtotime($request->get('to')));
		$type_date_period = $request->get('type_date_period');
		$select_account = $request->get('select_account');
		if($type == 1){
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->get();
			}			
			$columns_1 = array('S.No', 'Invoice No', 'Date', 'Supplier Code','Supplier Name', 'Net', 'VAT', 'Gross', 'Journal ID');
			$filename = 'Purchase Summary Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);
			$i = 1;
			$output = '';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
		            $columns_2 = array($i, $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
					fputcsv($fileopen, $columns_2);
					$i++;
				}
			}
			fclose($fileopen);
	        echo $filename;
		}
		elseif($type == 2){
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->orderBy('invoice_date', 'desc')->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->orderBy('invoice_date', 'desc')->get();
			}		
			$columns_1 = array('Date', 'Invoice No', 'Supplier Code','Supplier Name', 'Debit Nominal', 'Description', 'Net', 'VAT', 'Gross');
			$filename = 'Details Purchase Analysis.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);
			$i = 1;
			$output = '';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 
					$detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$global->id)->get();	
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
		            /*$columns_2 = array( $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($balance), number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
					fputcsv($fileopen, $columns_2);*/
					$j='';
					if(($detail_invoice)){
						foreach ($detail_invoice as $details) {
							$nominal = \App\Models\NominalCodes::where('id', $details->nominal_code)->first();
							$nominal_codes = '';
							if($nominal) {
								$nominal_codes = $nominal->code.'-'.$nominal->description;
							}
							$columns_2 = array(date('d-M-Y', strtotime($global->invoice_date)), $global->invoice_no, $supplier_code, $supplier_name, $nominal_codes, $details->description, number_format_invoice($details->net), number_format_invoice($details->vat_value), number_format_invoice($details->gross));
						fputcsv($fileopen, $columns_2);
							$j++;	
						}
					}
					$i++;
				}
			}
			fclose($fileopen);
	        echo $filename;
		}
		else{
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->get();
			}
			/*$columns_1 = array('Debit Nominal', 'Net', 'VAT', 'Gross');*/
			$filename = 'Purchase Posting Summary Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    /*fputcsv($fileopen, $columns_1);*/
			$i = 1;
			$output = '';
			$total_net='';
			$total_vat_value='';
			$total_gross='';
			$invoice_id='';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){
					if($invoice_id == ''){
						$invoice_id = $global->id;
					}
					else{
						$invoice_id = $invoice_id.','.$global->id;
					}
					$detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$global->id)->get();	
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }	            
					if(($detail_invoice)){
						foreach ($detail_invoice as $details) {
							if($total_net==''){
								$total_net= $details->net;
							}
							else{
								$total_net = $total_net+$details->net;
							}
							if($total_vat_value == ''){
								$total_vat_value = $details->vat_value;
							}
							else{
								$total_vat_value = $total_vat_value+$details->vat_value;
							}
							if($total_gross == ''){
								$total_gross = $details->gross;
							}
							else{
								$total_gross = $total_gross+$details->gross;
							}
							$nominal = \App\Models\NominalCodes::where('id', $details->nominal_code)->first();
							$nominal_codes = '';
							if($nominal) {
								$nominal_codes = $nominal->code.'-'.$nominal->description;
							}
							/*$columns_2 = array($nominal_codes, number_format_invoice($details->net), number_format_invoice($details->vat_value), number_format_invoice($details->gross));
						fputcsv($fileopen, $columns_2);*/
						}
					}			
					$i++;
				}
			}	
			/*$columns_3 = array('', '', '', '');
			fputcsv($fileopen, $columns_3);
			$columns_4 = array('', number_format_invoice($total_net), number_format_invoice($total_vat_value), number_format_invoice($total_gross));
			fputcsv($fileopen, $columns_4);*/
			$columns_5 = array('', '', '', '');
			fputcsv($fileopen, $columns_5);
			$columns_6 = array('Total Net', number_format_invoice($total_net));
			fputcsv($fileopen, $columns_6);
			$columns_7 = array('Total VAT', number_format_invoice($total_vat_value));
			fputcsv($fileopen, $columns_7);
			$columns_7 = array('Total Gross Purchases', number_format_invoice($total_gross));
			fputcsv($fileopen, $columns_7);
			$columns_8 = array('', '', '', '');
			fputcsv($fileopen, $columns_8);
			$columns_9 = array('NET SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_9);
			$explode_invoice_id = explode(',', $invoice_id);
			$nominal_code_list = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->where('net', '!=', 0)->groupBy('nominal_code')->get();
			$nominal_grand='';
			if(($nominal_code_list)){
				foreach ($nominal_code_list as $single_nominal) {
					$single_normail_total = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->where('nominal_code', $single_nominal->nominal_code)->sum('net');
					$nominal = \App\Models\NominalCodes::where('id', $single_nominal->nominal_code)->first();
					$nominal_codes = '';
					if($nominal) {
						$nominal_codes = $nominal->code.'-'.$nominal->description;
					}
					if($nominal_grand == ''){
						$nominal_grand = $single_normail_total;
					}
					else{
						$nominal_grand = $nominal_grand+$single_normail_total;
					}
					$columns_10 = array($nominal_codes, number_format_invoice($single_normail_total));
					fputcsv($fileopen, $columns_10);
				}
			}
			$columns_11 = array('GRAND TOTAL', number_format_invoice($nominal_grand));
			fputcsv($fileopen, $columns_11);
			$columns_12 = array('', '', '', '');
			fputcsv($fileopen, $columns_12);
			$columns_13 = array('VAT SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_13);
			$vat_percentage_list = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->groupBy('vat_rate')->get();
			$vat_grand = '';
			if(($vat_percentage_list)){
				foreach ($vat_percentage_list as $key => $vat_percentage) {
					$single_vat_percentage =  $single_normail_total = \App\Models\supplier_detail_invoice::whereIn('global_id',$explode_invoice_id)->where('vat_rate', $vat_percentage->vat_rate)->sum('net');
					$percentage = $vat_percentage->vat_rate.' %';
					if($vat_grand == ''){
						$vat_grand = $single_vat_percentage;
					}
					else{
						$vat_grand = $vat_grand+$single_vat_percentage;
					}
					$columns_14 = array($percentage, number_format_invoice($single_vat_percentage));
					fputcsv($fileopen, $columns_14);
				}
			}
			$columns_15 = array('GRAND TOTAL', number_format_invoice($vat_grand));
			fputcsv($fileopen, $columns_15);
			fclose($fileopen);
	        echo $filename;
		}
	}
	public function supplier_invoice_report_preview(Request $request){
		$type = $request->get('type');
		$from = date('Y-m-d', strtotime($request->get('from')));
		$to = date('Y-m-d', strtotime($request->get('to')));
		$type_date_period = $request->get('type_date_period');
		$select_account = $request->get('select_account');
		if($type == 1){
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->get();
			}
			$columns_1 = array('S.No', 'Invoice No', 'Date', 'Supplier Code','Supplier Name', 'Net', 'VAT', 'Gross', 'Journal ID');
			$i = 1;
			$output = '<table class="table own_white_table" id="report_preview">
			<thead>
				<tr>
					<th>S.No</th>
					<th>Invoice No</th>
					<th>Date</th>
					<th>Supplier Code</th>
					<th>Supplier Name</th>
					<th>Net</th>
					<th>VAT</th>
					<th>Gross</th>
					<th>Journal ID</th>
				</tr>
			</thead><tbody>';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
		            $output.='
		            <tr>
		            	<td>'.$i.'</td>
		            	<td>'.$global->invoice_no.'</td>
		            	<td>'.date('d-M-Y', strtotime($global->invoice_date)).'</td>
		            	<td>'.$supplier_code.'</td>
		            	<td>'.$supplier_name.'</td>
		            	<td>'.number_format_invoice($global->net).'</td>
		            	<td>'.number_format_invoice($global->vat).'</td>
		            	<td>'.number_format_invoice($global->gross).'</td>
		            	<td>'.$global->journal_id.'</td>
		            </tr>';		            
					$i++;
				}
			}
			$output.='</tbody></table>';
			echo json_encode(array('output' => $output));
		}
		elseif($type == 2){
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->orderBy('invoice_date', 'desc')->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->orderBy('invoice_date', 'desc')->get();
			}
			$i = 1;
			$output = '<table class="table own_white_table" id="report_preview">
			<thead>
				<tr>
					<th>S.No</th>
					<th>Date</th>
					<th>Invoice No</th>
					<th>Supplier Code</th>
					<th>Supplier Name</th>
					<th>Debit Nominal</th>
					<th>Description</th>
					<th>Net</th>
					<th>VAT</th>
					<th>Gross</th>
				</tr>
			</thead><tbody>';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 
					$detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$global->id)->get();	
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
					if(($detail_invoice)){
						foreach ($detail_invoice as $details) {
							$nominal = \App\Models\NominalCodes::where('id', $details->nominal_code)->first();
							if(($nominal)){
								$nominal_codes = $nominal->code.'-'.$nominal->description;
							}
							else{
								$nominal_codes = '';
							}
							//$nominal_codes = $nominal->code.'-'.$nominal->description;
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td>'.date('d-M-Y', strtotime($global->invoice_date)).'</td>
								<td>'.$global->invoice_no.'</td>
								<td>'.$supplier_code.'</td>
								<td>'.$supplier_name.'</td>
								<td>'.$nominal_codes.'</td>
								<td>'.$details->description.'</td>
								<td>'.number_format_invoice($details->net).'</td>
								<td>'.number_format_invoice($details->vat_value).'</td>
								<td>'.number_format_invoice($details->gross).'</td>
							</tr>';
						}
					}
					$i++;
				}
			}
			$output.='</tbody></table>';
			echo json_encode(array('output' => $output));
		}
		else{
			if($type_date_period == 1){
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			else{
				$invoicelist = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('ac_period',$select_account)->get();
			}
			/*$filename = 'Purchase Posting Summary Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');*/
			$i = 1;
			$total_net='';
			$total_vat_value='';
			$total_gross='';
			$invoice_id='';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $global){
					if($invoice_id == ''){
						$invoice_id = $global->id;
					}
					else{
						$invoice_id = $invoice_id.','.$global->id;
					}
					$detail_invoice = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->where('global_id',$global->id)->get();	
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = \App\Models\suppliers::where('practice_code', Session::get('user_practice_code'))->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }	            
					if(($detail_invoice)){
						foreach ($detail_invoice as $details) {
							if($total_net==''){
								$total_net= $details->net;
							}
							else{
								$total_net = $total_net+$details->net;
							}
							if($total_vat_value == ''){
								$total_vat_value = $details->vat_value;
							}
							else{
								$total_vat_value = $total_vat_value+$details->vat_value;
							}
							if($total_gross == ''){
								$total_gross = $details->gross;
							}
							else{
								$total_gross = $total_gross+$details->gross;
							}
							$nominal = \App\Models\NominalCodes::where('id', $details->nominal_code)->first();
							if(($nominal)){
								$nominal_codes = $nominal->code.'-'.$nominal->description;
							}
							else{
								$nominal_codes = '';
							}
						}
					}			
					$i++;
				}
			}	
			/*$columns_5 = array('', '', '', '');
			fputcsv($fileopen, $columns_5);*/
			$output_total = '<table class="table own_white_table">
				<tr>
					<td style="width:50% !important;">Total Net</td>
					<td>'.number_format_invoice($total_net).'</td>					
				</tr>
				<tr>
					<td>Total VAT</td>
					<td>'.number_format_invoice($total_vat_value).'</td>
				</tr>
				<tr>
					<td>Total Gross Purchases</td>
					<td>'.number_format_invoice($total_gross).'</td>
				</tr>
			</table>
			';
			/*$columns_9 = array('NET SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_9);*/
			$explode_invoice_id = explode(',', $invoice_id);
			$nominal_code_list = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->where('net', '!=', 0)->groupBy('nominal_code')->get();
			$nominal_grand='';
			$output_summart_net='<table class="table own_white_table" id="report_preview">
			<thead>
				<tr>
					<th style="width:50% !important">NET SUMMARY</th>
					<th>SUM OF NET</th>
				</tr>
			</thead><tbody>';
			if(($nominal_code_list)){
				foreach ($nominal_code_list as $single_nominal) {
					$single_normail_total = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->where('nominal_code', $single_nominal->nominal_code)->sum('net');
					$nominal = \App\Models\NominalCodes::where('id', $single_nominal->nominal_code)->first();
					if(($nominal)){
						$nominal_codes = $nominal->code.'-'.$nominal->description;
					}
					else{
						$nominal_codes = '';
					}
					if($nominal_grand == ''){
						$nominal_grand = $single_normail_total;
					}
					else{
						$nominal_grand = $nominal_grand+$single_normail_total;
					}
					$output_summart_net.='
					<tr>
						<td>'.$nominal_codes.'</td>
						<td>'.number_format_invoice($single_normail_total).'</td>
					</tr>';					
				}
				$output_summart_net.='
				<tr>
					<td>GRAND TOTAL</td>
					<td>'.number_format_invoice($nominal_grand).'</td>
				</tr></tbody></table>';
			}
			/*
			$columns_13 = array('VAT SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_13);*/
			$vat_percentage_list = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->groupBy('vat_rate')->get();
			$vat_grand = '';
			$total_vat_grand='
			<table class="own_white_table table" id="report_preview2"><thead>
				<tr>
					<th style="width:50% !important">VAT SUMMARY</th>
					<th>SUM OF NET</th>
				</tr>
			</thead><tbody>
			';
			if(($vat_percentage_list)){
				foreach ($vat_percentage_list as $key => $vat_percentage) {
					$single_vat_percentage =  $single_normail_total = \App\Models\supplier_detail_invoice::where('practice_code', Session::get('user_practice_code'))->whereIn('global_id',$explode_invoice_id)->where('vat_rate', $vat_percentage->vat_rate)->sum('net');
					$percentage = $vat_percentage->vat_rate.' %';
					if($vat_grand == ''){
						$vat_grand = $single_vat_percentage;
					}
					else{
						$vat_grand = $vat_grand+$single_vat_percentage;
					}
					$total_vat_grand.='
					<tr>
						<td>'.$percentage.'</td>
						<td>'.number_format_invoice($single_vat_percentage).'</td>
					</tr>';
				}
				$total_vat_grand.='
				<tr>
					<td>GRAND TOTAL</td>
					<td>'.number_format_invoice($vat_grand).'</td>
				</tr></tbody></table>';
			}
			$output='
			<div class="col-lg-12" style="margin-top:30px;">
			'.$output_total.'
			</div>
			<div class="col-lg-12">
			'.$output_summart_net.'
			</div>
			<div class="col-lg-12">
			'.$total_vat_grand.'
			</div>';
			echo json_encode(array('output' => $output));
		}		
	}
	public function invoice_setacperiod(Request $request){
		$invoice_list = \App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->get();
		if(($invoice_list)){
			foreach ($invoice_list as $single_invoice) {
				$data['ac_period'] = '1001';
				\App\Models\supplier_global_invoice::where('practice_code', Session::get('user_practice_code'))->where('id', $single_invoice->id)->update($data);
			}
		}
		echo 'Success';
		exit;
	}
}