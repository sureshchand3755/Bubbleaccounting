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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class OpeningbalanceController extends Controller {
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
	public function opening_balance_manager(Request $request)
	{
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
		$class = \App\Models\CMClass::where('status', 0)->get();	
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/opening_balance/opening_balance_manager', array('title' => 'Opening Balance Manager', 'clientlist' => $client, 'classlist' => $class, 'userlist' => $user));
	}
	public function opening_balance_invoices_issued(Request $request){
		$user =\App\Models\userLogin::first();
        $financial_date = $user->opening_balance_date;
        $invoice_issued = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_date','<=',$financial_date)->get();
        return view('user/opening_balance/invoice_issued', array('title' => 'Opening Balance Manager', 'invoice_issued' => $invoice_issued));
	}
	public function create_opening_balance_journal(Request $request)
	{
		$client_id = $request->get('client_id');
		$client_details = \App\Models\CMClients::where('client_id',$client_id)->first();
		$details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(($details))
		{
			$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$date =\App\Models\userLogin::where('id',1)->first();
			$dataval['journal_date'] = $date->opening_balance_date;
			$dataval['description'] = 'SOB - '.$client_id.' - '.$client_details->company.'';
			$dataval['reference'] = 'SOB'.$client_id;
			$dataval['journal_source'] = 'SOB';
			$dataval['practice_code'] = Session::get('user_practice_code');
			if($details->opening_balance > 0){
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = '712';
				$dataval['dr_value'] = $details->opening_balance;
				$dataval['cr_value'] = '0.00';
				\App\Models\Journals::insert($dataval);
				$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
				$dataval['nominal_code'] = '991';
				$dataval['dr_value'] = '0.00';
				$dataval['cr_value'] = $details->opening_balance;
				\App\Models\Journals::insert($dataval);
			}
			if($details->opening_balance < 0){
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = '991';
				$dataval['dr_value'] = ((int)$details->opening_balance * -1);
				$dataval['cr_value'] = '0.00';
				\App\Models\Journals::insert($dataval);
				$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
				$dataval['nominal_code'] = '712';
				$dataval['dr_value'] = '0.00';
				$dataval['cr_value'] = ((int)$details->opening_balance * -1);
				\App\Models\Journals::insert($dataval);
			}
			$data['journal_id'] = $next_connecting_journal;
			\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
			echo '<a href="javascript:" class="journal_id_viewer journal_id_sortval" data-element="'.$next_connecting_journal.'">'.$next_connecting_journal.'</a>';
		}
	}
	public function create_journals_for_opening_balance(Request $request)
	{
		$clientlist = \App\Models\OpeningBalance::where('journal_id','0')->where('opening_balance','!=','')->where('opening_balance','!=','0')->where('opening_balance','!=','0.00')->offset(0)->limit(100)->get();
		$date =\App\Models\userLogin::where('id',1)->first();
		if(($clientlist)){
			foreach($clientlist as $client){
				$client_id = $client->client_id;
				$client_details = \App\Models\CMClients::where('client_id',$client_id)->first();
				$clientname = '';
				if(($client_details))
				{
					$clientname = $client_details->company;
				}
				$details = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
				if(($details))
				{
					$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
					$next_connecting_journal = count($count_total_journals) + 1;
					$dataval['journal_date'] = $date->opening_balance_date;
					$dataval['description'] = 'SOB - '.$client_id.' - '.$clientname.'';
					$dataval['reference'] = 'SOB'.$client_id;
					$dataval['journal_source'] = 'SOB';
					$dataval['practice_code'] = Session::get('user_practice_code');
					if($details->opening_balance > 0){
						$dataval['connecting_journal_reference'] = $next_connecting_journal;
						$dataval['nominal_code'] = '712';
						$dataval['dr_value'] = $details->opening_balance;
						$dataval['cr_value'] = '0.00';
						\App\Models\Journals::insert($dataval);
						$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
						$dataval['nominal_code'] = '991';
						$dataval['dr_value'] = '0.00';
						$dataval['cr_value'] = $details->opening_balance;
						\App\Models\Journals::insert($dataval);
					}
					if($details->opening_balance < 0){
						$dataval['connecting_journal_reference'] = $next_connecting_journal;
						$dataval['nominal_code'] = '991';
						$dataval['dr_value'] = ($details->opening_balance * -1);
						$dataval['cr_value'] = '0.00';
						\App\Models\Journals::insert($dataval);
						$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
						$dataval['nominal_code'] = '712';
						$dataval['dr_value'] = '0.00';
						$dataval['cr_value'] = ($details->opening_balance * -1);
						\App\Models\Journals::insert($dataval);
					}
					$data['journal_id'] = $next_connecting_journal;
					\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
				}
			}
		}
	}
	public function export_opening_balance_invoice_issued_csv(Request $request)
	{
		$user =\App\Models\userLogin::first();
        $financial_date = $user->opening_balance_date;
        $invoice_issued = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_date','<=',$financial_date)->get();
        $filename = 'Invoices Issued - '.time().'.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		$columns = array('Invoice', 'Client ID', 'Client', 'Invoice Date', 'Gross', 'Balance Outstanding');
		fputcsv($file, $columns);
		if(($invoice_issued))
        {
          foreach($invoice_issued as $invoice){
            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$invoice->client_id)->first();
            $clientname = '';
            if(($client_details)){
              $clientname =  ($client_details->company == "")?$client_details->firstname.' & '.$client_details->surname:$client_details->company;
            }
            $columns1 = array($invoice->invoice_number, $invoice->client_id, $clientname, date('d-M-Y', strtotime($invoice->invoice_date)), number_format_invoice($invoice->gross), number_format_invoice_empty($invoice->outstanding_balance));
			fputcsv($file, $columns1);
          }
        }
		fclose($file);
   	 	echo $filename;
	}
	public function update_outstanding_invoice(Request $request){
		$invoice = $request->get('invoice');
		$value = $request->get('value');
		$data['outstanding_balance'] = $value;
		\App\Models\InvoiceSystem::where('invoice_number',$invoice)->update($data);
	}
	public function client_opening_balance_manager(Request $request)
	{
		$client_id = $request->get('client_id');
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$check_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(!($check_client))
		{
			if($client->client_added != "")
			{
				$client_added_slash = explode('/',$client->client_added);
				$client_added_minus = explode('-',$client->client_added);
				if(count($client_added_slash) > 1)
				{
					$date_added = $client_added_slash[2].'-'.$client_added_slash[1].'-'.$client_added_slash[0];
				}
				elseif(count($client_added_minus) > 1)
				{
					$date_added = $client_added_minus[2].'-'.$client_added_minus[1].'-'.$client_added_minus[0];
				}
				else{
					$date_added = date('Y-m-d', strtotime($client->updatetime));
				}
			}
			else{
				$date_added = date('Y-m-d', strtotime($client->updatetime));
			}
			$data['client_id'] = $client_id;
			$data['opening_balance'] = "";
			$data['opening_date'] = $date_added;
			\App\Models\OpeningBalance::insert($data);
		}
		return view('user/opening_balance/client_opening_balance_manager', array('title' => 'Client Opening Balance Manager', 'client_details' => $client, 'client_id' => $client_id));
	}
	public function import_opening_balance_manager(Request $request)
	{
		if(Session::has('databal'))
		{
			Session::forget('databal');
		}
		return view('user/opening_balance/import_opening_balance_manager', array('title' => 'Import Opening Balance Manager'));
	}
	public function change_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$balance = $request->get('balance');
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$data['opening_balance'] = $balance;
		$check_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(($check_client))
		{
			\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			\App\Models\OpeningBalance::insert($data);
		}
		echo number_format_invoice($balance);
	}
	public function change_opening_balance_date(Request $request)
	{
		$client_id = $request->get('client_id');
		$date = explode('-',$request->get('dateval'));
		if($date[1] == "Jan") { $month = '01'; }
		if($date[1] == "Feb") { $month = '02'; }
		if($date[1] == "Mar") { $month = '03'; }
		if($date[1] == "Apr") { $month = '04'; }
		if($date[1] == "May") { $month = '05'; }
		if($date[1] == "Jun") { $month = '06'; }
		if($date[1] == "Jul") { $month = '07'; }
		if($date[1] == "Aug") { $month = '08'; }
		if($date[1] == "Sep") { $month = '09'; }
		if($date[1] == "Oct") { $month = '10'; }
		if($date[1] == "Nov") { $month = '11'; }
		if($date[1] == "Dec") { $month = '12'; }
		$data['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
		$check_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(($check_client))
		{
			\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			\App\Models\OpeningBalance::insert($data);
		}
	}
	public function auto_allocate_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$opening_balance = $request->get('opening_balance');
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$balance = $request->get('opening_balance');
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$dataval['balance_remaining'] = '';
		\App\Models\InvoiceSystem::where('client_id',$client_id)->update($dataval);
		$check_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
        $get_invoices_update = DB::select('SELECT * from `invoice_system` WHERE `client_id` = "'.$client_id.'" AND `invoice_date` <= "'.$check_client->opening_date.'" ORDER BY `invoice_date` DESC');
		if(($get_invoices_update))
		{
			foreach($get_invoices_update as $invoice)
			{
				$gross = $invoice->gross;
				if($opening_balance != 0)
				{
					if($gross > 0)
					{
						if($gross < $opening_balance)
						{
							$data['balance_remaining'] = $gross;
							$opening_balance = number_format_invoice_without_comma(number_format_invoice_without_comma($opening_balance) - number_format_invoice_without_comma($gross));
						}
						else{
							$data['balance_remaining'] = $opening_balance;
							$opening_balance = number_format_invoice_without_comma(number_format_invoice_without_comma($opening_balance) - number_format_invoice_without_comma($opening_balance));
						}
						\App\Models\InvoiceSystem::where('id',$invoice->id)->update($data);
					}
				}
			}
		}
		$output = '';
		$total_remaining = 0;
		$total_breakdown = 0;
          $get_invoices = DB::select('SELECT * from `invoice_system` WHERE `client_id` = "'.$client_id.'" AND `invoice_date` <= "'.$check_client->opening_date.'" ORDER BY `invoice_date` DESC');
          if(($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              if (strpos($invoice->gross, '-') !== false) { $breakdown = '-'; $balance_remaining = '-'; }
              else{
                if($invoice->balance_remaining != "") { 
                  $balance_remaining = number_format_invoice($invoice->balance_remaining); 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                } 
                else { 
                  $balance_remaining = '0.00'; 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                }
              }
              $output.='<tr>
                <td>'.$invoice->invoice_number.'</td>
                <td>'.date("d-M-Y", strtotime($invoice->invoice_date)).'</td>
                <td style="text-align: right">'.number_format_invoice($invoice->gross).'</td>
                <td style="text-align: right">'.number_format_invoice($invoice->import_balance).'</td>
                <td style="text-align: right">'.$balance_remaining.'</td>
              </tr>';
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $total_remaining = $total_remaining + $balance_remaining;
              $total_breakdown = $total_breakdown + $breakdown;
            }
          }
          else{
            $output.='<tr><td colspan="5">No Invoice are available on or before the given opening balance date</td></tr>';
          }
          $unallocated = $balance - $total_remaining;
          $output.='<tr>
            <td colspan="4" style="font-weight:700">Total Balance Remaining</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($total_remaining).'</td>
          </tr>
          <tr>
            <td colspan="4" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($unallocated).'</td>
          </tr>';
          echo $output;
	}
	public function import_opening_balance(Request $request)
	{
		$page = $request->get('page');
		if($page == "1")
		{
			$filename = $_FILES['balance_file']['name'];
			$tmp_name = $_FILES['balance_file']['tmp_name'];
			$import_type = $request->get('import_balance');
			$page = $request->get('page');
			$session_id = time();
			$upload_dir = 'uploads/opening_balance';
			if(!is_dir($upload_dir))
			{
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.time();
			if(!is_dir($upload_dir))
			{
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);
			$filepath = $upload_dir.'/'.$filename;
		}
		else{
			$filepath = $request->get('filename');
			$import_type = $request->get('import_type');
			$page = $request->get('page');
			$session_id = $request->get('session_id');
		}
		if($import_type == "1")
		{
			$output = '';
			$objPHPExcel = IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				
				$nrColumns = ord($highestColumn) - 64;
				$prevround = $page - 1;
				$last_height = $prevround * 100;
				$offsetcount = $last_height + 1;
				$roundcount = $page * 100;
				$nextpage = $page + 1;
				if($highestRow > $roundcount)
				{
					$height = $roundcount;
				}
				else{
					$height = $highestRow;
					$nextpage  = 0;
				}
				if($offsetcount == 1)
				{
					$offsetcount = 2;
				}
				$client_label = $worksheet->getCellByColumnAndRow(1, 1); $client_label = trim($client_label->getValue());
				$balance_label = $worksheet->getCellByColumnAndRow(2, 1); $balance_label = trim($balance_label->getValue());
				$date_label = $worksheet->getCellByColumnAndRow(3, 1); $date_label = trim($date_label->getValue());
				$client_label = strtolower($client_label);
				$balance_label = strtolower($balance_label);
				$date_label = strtolower($date_label);
				$client_label = str_replace(" ", "", $client_label);
				$client_label = str_replace(" ", "", $client_label);
				$client_label = str_replace(" ", "", $client_label);
				$balance_label = str_replace(" ", "", $balance_label);
				$balance_label = str_replace(" ", "", $balance_label);
				$balance_label = str_replace(" ", "", $balance_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				if($client_label != "code" || $balance_label != "balance" || $date_label != "date")
				{
					echo json_encode(array("error" => "1", "message" => 'The files you have imported is not in a Valid Format.', "upload_dir" => $filepath, "output" => "",'page' => "0", 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
					exit;
				}
				else{
					for ($row = $offsetcount; $row <= $height; ++ $row) {
						$client_id = $worksheet->getCellByColumnAndRow(1, $row); $client_id = trim($client_id->getValue());
						$balance = $worksheet->getCellByColumnAndRow(2, $row); $balance = trim($balance->getValue());
						$import_date = $worksheet->getCellByColumnAndRow(3, $row); $import_date = trim($import_date->getValue());
						$import_date = trim($import_date);
						if($import_date == "")
						{
							$imp_date = '';
						}
						else{
							$explode_import_date = explode("/",$import_date);
							$explode_hyphen_import_date = explode("-",$import_date);
							if(count($explode_import_date) == 3)
							{
								$inc_date = $explode_import_date[2].'-'.$explode_import_date[1].'-'.$explode_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							elseif(count($explode_hyphen_import_date) == 3){
								$inc_date = $explode_hyphen_import_date[2].'-'.$explode_hyphen_import_date[1].'-'.$explode_hyphen_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							else{
								$unix_date = ($import_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$imp_date = gmdate("Y-m-d", $unix_date);
							}
						}
						$bal = str_replace(',',"",$balance);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						if(is_numeric($bal) == 1) { $bal_status = '<td style="color:green">Pass</td>'; } else { $bal_status = '<td class="error_import" style="color:#f00">Value Fail</td>'; }
						$check_client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
						if(($check_client)) { $client_status = '<td style="color:green">Pass</td>'; } else { $client_status = '<td class="error_import" style="color:#f00">ID Fail</td>'; }
						$importdata['session_id'] = $session_id;
						$importdata['client_id'] = $client_id;
						$importdata['balance'] = $bal;
						$importdata['import_date'] = $imp_date;
						$importdata['import_type'] = $import_type;
						\App\Models\OpeningBalanceImport::insert($importdata);
						$output.='<tr>
						<td>'.$client_id.'</td>
						<td>-</td>
						<td>'.$balance.'</td>
						'.$client_status.'
						'.$bal_status.'
						<td>N/A</td>
						</tr>';
					}
				}
			}
			echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $filepath, "output" => $output,'page' => $nextpage, 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
			exit;
		}
		else
		{
			$output = '';
			$objPHPExcel = IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				
				$nrColumns = ord($highestColumn) - 64;
				$prevround = $page - 1;
				$last_height = $prevround * 100;
				$offsetcount = $last_height + 1;
				$roundcount = $page * 100;
				$nextpage = $page + 1;
				if($highestRow > $roundcount)
				{
					$height = $roundcount;
				}
				else{
					$height = $highestRow;
					$nextpage  = 0;
				}
				if($offsetcount == 1)
				{
					$offsetcount = 2;
				}
				$inv_label = $worksheet->getCellByColumnAndRow(1, 1); $inv_label = trim($inv_label->getValue());
				$gross_label = $worksheet->getCellByColumnAndRow(2, 1); $gross_label = trim($gross_label->getValue());
				$date_label = $worksheet->getCellByColumnAndRow(3, 1); $date_label = trim($date_label->getValue());
				$inv_label = strtolower($inv_label);
				$gross_label = strtolower($gross_label);
				$date_label = strtolower($date_label);
				$inv_label = str_replace(" ", "", $inv_label);
				$inv_label = str_replace(" ", "", $inv_label);
				$inv_label = str_replace(" ", "", $inv_label);
				$gross_label = str_replace(" ", "", $gross_label);
				$gross_label = str_replace(" ", "", $gross_label);
				$gross_label = str_replace(" ", "", $gross_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				if($inv_label != "invno" || $gross_label != "gross" || $date_label != "date")
				{
					echo json_encode(array("error" => "1", "message" => 'You have tried to upload a wrong csv file.', "upload_dir" => $filepath, "output" => "",'page' => "0", 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
					exit;
				}
				else{
					for ($row = $offsetcount; $row <= $height; ++ $row) {
						$inv_no = $worksheet->getCellByColumnAndRow(1, $row); $inv_no = trim($inv_no->getValue());
						$gross = $worksheet->getCellByColumnAndRow(2, $row); $gross = trim($gross->getValue());
						$import_date = $worksheet->getCellByColumnAndRow(3, $row); $import_date = trim($import_date->getValue());
						$import_date = trim($import_date);
						if($import_date == "")
						{
							$imp_date = '';
						}
						else{
							$explode_import_date = explode("/",$import_date);
							$explode_hyphen_import_date = explode("-",$import_date);
							if(count($explode_import_date) == 3)
							{
								$inc_date = $explode_import_date[2].'-'.$explode_import_date[1].'-'.$explode_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							elseif(count($explode_hyphen_import_date) == 3){
								$inc_date = $explode_hyphen_import_date[2].'-'.$explode_hyphen_import_date[1].'-'.$explode_hyphen_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							else{
								$unix_date = ($import_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$imp_date = gmdate("Y-m-d", $unix_date);
							}
						}
						$bal = str_replace(',',"",$gross);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						if(is_numeric($bal) == 1) { $bal_status = '<td style="color:green">Pass</td>'; } else { $bal_status = '<td class="error_import" style="color:#f00">Inv Val Fail</td>'; }
						$check_inv = \App\Models\InvoiceSystem::where('invoice_number',$inv_no)->first();
						if(($check_inv)) { $inv_status = '<td style="color:green">Pass</td>'; $client_id = $check_inv->client_id; } else { $inv_status = '<td class="error_import" style="color:#f00">Inv No Fail</td>'; $client_id= ''; }
						$importdata['session_id'] = $session_id;
						$importdata['client_id'] = $client_id;
						$importdata['invoice_id'] = $inv_no;
						$importdata['balance'] = $bal;
						$importdata['import_date'] = $imp_date;
						$importdata['import_type'] = $import_type;
						\App\Models\OpeningBalanceImport::insert($importdata);
						$output.='<tr>
						<td>'.$client_id.'</td>
						<td>'.$inv_no.'</td>
						<td>'.$gross.'</td>
						<td>N/A</td>
						'.$bal_status.'
						'.$inv_status.'
						</tr>';
					}
				}
			}
			echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $filepath, "output" => $output,'page' => $nextpage, 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
				exit;
		}
	}
	public function import_opening_balance_to_clients(Request $request)
	{
		$filepath = $request->get('filename');
		$bal_date = $request->get('bal_date');
		$import_type = $request->get('import_type');
		$session_id = $request->get('session_id');
		$page = $request->get('page');
		$nextpage = $page + 1;
		$date = explode('-',$request->get('bal_date'));
		if($date[1] == "Jan") { $month = '01'; }
		if($date[1] == "Feb") { $month = '02'; }
		if($date[1] == "Mar") { $month = '03'; }
		if($date[1] == "Apr") { $month = '04'; }
		if($date[1] == "May") { $month = '05'; }
		if($date[1] == "Jun") { $month = '06'; }
		if($date[1] == "Jul") { $month = '07'; }
		if($date[1] == "Aug") { $month = '08'; }
		if($date[1] == "Sep") { $month = '09'; }
		if($date[1] == "Oct") { $month = '10'; }
		if($date[1] == "Nov") { $month = '11'; }
		if($date[1] == "Dec") { $month = '12'; }
		$get_locked_client_ids = \App\Models\OpeningBalance::select('client_id')->where('locked',1)->get();
		$locked_clients = array();
		if(($get_locked_client_ids))
		{
			foreach($get_locked_client_ids as $clientid)
			{
				array_push($locked_clients,$clientid->client_id);
			}
		}
		if($import_type == "1")
		{
			$balance = \App\Models\OpeningBalanceImport::whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->where('status',0)->first();
			if(($balance))
			{
				$get_client_balance = \App\Models\OpeningBalanceImport::where('client_id',$balance->client_id)->where('session_id',$session_id)->sum('balance');
				$dataupdate['opening_balance'] = $get_client_balance;
				$dataupdate['client_id'] = $balance->client_id;
				$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
				$databal['status'] = 1;
				\App\Models\OpeningBalanceImport::where('client_id',$balance->client_id)->where('session_id',$session_id)->update($databal);
				$check_client_bal = \App\Models\OpeningBalance::where('client_id',$balance->client_id)->first();
				if(($check_client_bal))
				{
					\App\Models\OpeningBalance::where('client_id',$balance->client_id)->update($dataupdate);
				}
				else{
					\App\Models\OpeningBalance::insert($dataupdate);
				}
			}
			else{
				\App\Models\OpeningBalanceImport::where('session_id',$session_id)->delete();
				echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'finish'));
				exit;
			}
		}
		else
		{
			$balance = \App\Models\OpeningBalanceImport::whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->where('status',0)->first();
			if(($balance))
			{
				$get_client_balance = \App\Models\OpeningBalanceImport::where('client_id',$balance->client_id)->where('session_id',$session_id)->sum('balance');
				$dataupdate['opening_balance'] = number_format_invoice_without_comma($get_client_balance);
				$dataupdate['client_id'] = $balance->client_id;
				$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
				$databal['status'] = 1;
				\App\Models\OpeningBalanceImport::where('client_id',$balance->client_id)->where('session_id',$session_id)->update($databal);
				$check_client_bal = \App\Models\OpeningBalance::where('client_id',$balance->client_id)->first();
				if(($check_client_bal))
				{
					\App\Models\OpeningBalance::where('client_id',$balance->client_id)->update($dataupdate);
				}
				else{
					\App\Models\OpeningBalance::insert($dataupdate);
				}
				$client_id = $balance->client_id;
				$opening_balance = $get_client_balance;
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$dataval['balance_remaining'] = '';
				\App\Models\InvoiceSystem::where('client_id',$client_id)->update($dataval);
				$get_invoices_update = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->orderBy('invoice_date','desc')->get();
				if(($get_invoices_update))
				{
					foreach($get_invoices_update as $invoice)
					{
						$gross = $invoice->gross;
						if($opening_balance != 0)
						{
							if($gross > 0)
							{
								if($gross < $opening_balance)
								{
									$data['balance_remaining'] = $gross;
									$opening_balance = $opening_balance - $gross;
								}
								else{
									$data['balance_remaining'] = $opening_balance;
									$opening_balance = $opening_balance - $opening_balance;
								}
								\App\Models\InvoiceSystem::where('id',$invoice->id)->update($data);
							}
						}
					}
				}
			}
			else{
				$datainv_empty['import_balance'] = "";
				\App\Models\InvoiceSystem::update($datainv_empty);
				$get_client_csv_balance = \App\Models\OpeningBalanceImport::where('session_id',$session_id)->get();
				if(($get_client_csv_balance))
				{
					foreach($get_client_csv_balance as $csv_bal)
					{
						$datainv['import_balance'] = $csv_bal->balance;
						\App\Models\InvoiceSystem::where('invoice_number',$csv_bal->invoice_id)->update($datainv);
					}
				}
				\App\Models\OpeningBalanceImport::where('session_id',$session_id)->delete();
				echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'finish'));
				exit;
			}
		}
		echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'start'));
	}
	public function clear_import_opening_balance(Request $request)
	{
		$session_id = $request->get('session_id');
		\App\Models\OpeningBalanceImport::where('session_id',$session_id)->delete();
	}
	public function lock_client_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$locked = $request->get('locked');
		$client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		$check_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(!($check_client))
		{
			if($client->client_added != "")
			{
				$client_added_slash = explode('/',$client->client_added);
				$client_added_minus = explode('-',$client->client_added);
				if(count($client_added_slash) > 1)
				{
					$date_added = $client_added_slash[2].'-'.$client_added_slash[1].'-'.$client_added_slash[0];
				}
				elseif(count($client_added_minus) > 1)
				{
					$date_added = $client_added_minus[2].'-'.$client_added_minus[1].'-'.$client_added_minus[0];
				}
				else{
					$date_added = date('Y-m-d', strtotime($client->updatetime));
				}
			}
			else{
				$date_added = date('Y-m-d', strtotime($client->updatetime));
			}
			$data['client_id'] = $client_id;
			$data['opening_balance'] = "";
			$data['opening_date'] = $date_added;
			$data['locked'] = $locked;
			\App\Models\OpeningBalance::insert($data);
		}
		else{
			$data['locked'] = $locked;
			\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
		}
		return Redirect::back();
	}
	public function get_client_counts_opening_balance(Request $request)
	{
		$session_id = $request->get('session_id');
		$get_locked_client_ids = \App\Models\OpeningBalance::select('client_id')->where('locked',1)->get();
		$locked_clients = array();
		if(($get_locked_client_ids))
		{
			foreach($get_locked_client_ids as $clientid)
			{
				array_push($locked_clients,$clientid->client_id);
			}
		}
		$balance = \App\Models\OpeningBalanceImport::whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->groupBy('client_id')->get();
		echo ($balance);
	}
	public function set_global_opening_bal_date(Request $request)
	{
		$date = explode('-',$request->get('global_date'));
		if($date[1] == "Jan") { $month = '01'; }
		if($date[1] == "Feb") { $month = '02'; }
		if($date[1] == "Mar") { $month = '03'; }
		if($date[1] == "Apr") { $month = '04'; }
		if($date[1] == "May") { $month = '05'; }
		if($date[1] == "Jun") { $month = '06'; }
		if($date[1] == "Jul") { $month = '07'; }
		if($date[1] == "Aug") { $month = '08'; }
		if($date[1] == "Sep") { $month = '09'; }
		if($date[1] == "Oct") { $month = '10'; }
		if($date[1] == "Nov") { $month = '11'; }
		if($date[1] == "Dec") { $month = '12'; }
		$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
		\App\Models\OpeningBalance::where('locked',0)->update($dataupdate);
	}
	public function invoice_outstanding_upload_csv(Request $request)
	{
		$upload_dir = 'uploads/opening_balane_invoice_issued';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time();
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if (!empty($_FILES)) {
			$fname = $_FILES['file']['name'];
			$tmpFile = $_FILES['file']['tmp_name'];
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);
		 	echo json_encode(array('filename' => $filename));
		}
	}
	public function check_invoice_issued_csv_file(Request $request){
		$filepath = $request->get('filename');
		$output = '';
		$objPHPExcel = IOFactory::load($filepath);
		$error_invalid = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;
			$invoice_label = $worksheet->getCellByColumnAndRow(1, 1); $invoice_label = trim($invoice_label->getValue());
			$balance_label = $worksheet->getCellByColumnAndRow(2, 1); $balance_label = trim($balance_label->getValue());
			$invoice_label = strtolower($invoice_label);
			$balance_label = strtolower($balance_label);
			$invoice_label = str_replace(" ", "", $invoice_label);
			$invoice_label = str_replace(" ", "", $invoice_label);
			$invoice_label = str_replace(" ", "", $invoice_label);
			$balance_label = str_replace(" ", "", $balance_label);
			$balance_label = str_replace(" ", "", $balance_label);
			$balance_label = str_replace(" ", "", $balance_label);
			if($invoice_label != "invoiceno" || $balance_label != "balance")
			{
				echo json_encode(array("error" => "1","error_invalid" => $error_invalid, "message" => 'You have tried to upload a wrong csv file.', "output" => ""));
				exit;
			}
			else{
				for ($row = 2; $row <= $height; ++ $row) {
					$invoice_no = $worksheet->getCellByColumnAndRow(1, $row); $invoice_no = trim($invoice_no->getValue());
					$balance = $worksheet->getCellByColumnAndRow(2, $row); $balance = trim($balance->getValue());
					$bal = str_replace(',',"",$balance);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$bal = str_replace(',',"",$bal);
					$user =\App\Models\userLogin::first();
			        $financial_date = $user->opening_balance_date;
			        $noteval = 0;
			        $note = '';
			        if(is_numeric($bal) != 1) { 
			        	$note.= '<p>Balance Value Should be Numeric<p>'; 
			        	$noteval++;
			        } 
					$check_invoice = \App\Models\InvoiceSystem::where('invoice_number',$invoice_no)->first();
					if(!($check_invoice)) { 
						$note.= '<p>Invoice Number is Invalid</p>'; 
						$noteval++;
					}
					else{
						$invoice_date = strtotime($check_invoice->invoice_date);
						$financial_date = strtotime($financial_date);
						if($invoice_date > $financial_date){
							$note.= '<p>Invoice Date is Greater than the Openig Balance Financial Date</p>'; 
							$noteval++;
						}
					}
					$valid_td = 'Valid';
					$valid_cls = 'valid_tr';
					if($noteval > 0){
						$valid_td = 'InValid';
						$valid_cls = 'invalid_tr';
						$error_invalid++;
					}
					$output.='<tr class="'.$valid_cls.'" data-element="'.$invoice_no.'" data-balance="'.$balance.'">
					<td>'.$invoice_no.'</td>
					<td>'.number_format_invoice_empty($bal).'</td>
					<td>'.$valid_td.'</td>
					<td>'.$note.'</td>
					</tr>';
				}
			}
		}
		echo json_encode(array("error" => "0","error_invalid" => $error_invalid, "message" => '', "output" => $output));
		exit;
	}
	public function upload_invoice_issued_csv_file(Request $request){
		$filepath = $request->get('filename');
		$output = '';
		$objPHPExcel = IOFactory::load($filepath);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;
			for ($row = 2; $row <= $height; ++ $row) {
				$invoice_no = $worksheet->getCellByColumnAndRow(1, $row); $invoice_no = trim($invoice_no->getValue());
				$balance = $worksheet->getCellByColumnAndRow(2, $row); $balance = trim($balance->getValue());
				$bal = str_replace(',',"",$balance);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$bal = str_replace(',',"",$bal);
				$user =\App\Models\userLogin::first();
		        $financial_date = $user->opening_balance_date;
		        $noteval = 0;
		        $note = '';
		        if(is_numeric($bal) != 1) { 
		        	$note.= '<p>Balance Value Should be Numeric<p>'; 
		        	$noteval++;
		        } 
				$check_invoice = \App\Models\InvoiceSystem::where('invoice_number',$invoice_no)->first();
				if(!($check_invoice)) { 
					$note.= '<p>Invoice Number is Invalid</p>'; 
					$noteval++;
				}
				else{
					$invoice_date = strtotime($check_invoice->invoice_date);
					$financial_date = strtotime($financial_date);
					if($invoice_date > $financial_date){
						$note.= '<p>Invoice Date is Greater than the Opening Balance Financial Date</p>'; 
						$noteval++;
					}
				}
				if($noteval == 0){
					$data['outstanding_balance'] = $bal;
					\App\Models\InvoiceSystem::where('invoice_number',$invoice_no)->update($data);
				}
			}
		}
	}
	public function refresh_os_invoice(Request $request){
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$os_data = [];
		$user =\App\Models\userLogin::first();
        $financial_date = $user->opening_balance_date;
        $total_os = 0;
		if(($clients)){
			foreach($clients as $client)
			{
				$os_value = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->client_id)->where('invoice_date','<=',$financial_date)->sum('outstanding_balance');
				array_push($os_data, '<a href="javascript:" class="os_td_spam" data-element="'.$client->client_id.'">'.number_format_invoice($os_value).'</a>');
				$total_os = $total_os + $os_value;
			}
		}
		$data['os_data'] = $os_data;
		$data['os_data_total'] = number_format_invoice($total_os);
		echo json_encode($data);
	}
	public function set_balance_for_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$value = $request->get('value');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$check_op_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(($check_op_client))
		{
			if($check_op_client->locked == 0){
				$data['opening_balance'] = $value;
				\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
				echo 0;
			}
			else{
				echo 1;
			}
		}
		else{
			$data['client_id'] = $client_id;
			$data['opening_balance'] = $value;
			$data['locked'] = 0;
			\App\Models\OpeningBalance::insert($data);
			echo 0;
		}
	}
	public function remove_balance_for_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$check_op_client = \App\Models\OpeningBalance::where('client_id',$client_id)->first();
		if(($check_op_client))
		{
			if($check_op_client->locked == 0){
				$data['opening_balance'] = '';
				\App\Models\OpeningBalance::where('client_id',$client_id)->update($data);
				echo 0;
			}
			else{
				echo 1;
			}
		}
	}
	public function opening_balance_review(Request $request){
		$client_id = $request->get('client_id');
		$cm_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
		if(($cm_details)){
			$client_details = '
			<div class="col-md-3" style="padding:0px;">
				<h5 style="font-weight: 600; font-size:15px;">Client Name:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">'.$cm_details->company.'</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">Client Code:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">'.$client_id.'</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">Address:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">';
					if($cm_details->address1 != ''){
			          $client_details.=$cm_details->address1.'<br/>';
			        }
			        if($cm_details->address2 != ''){
			          $client_details.=$cm_details->address2.'<br/>';
			        }
			        if($cm_details->address3 != ''){
			          $client_details.=$cm_details->address3.'<br/>';
			        }
			        if($cm_details->address4 != ''){
			          $client_details.=$cm_details->address4.'<br/>';
			        }
			        if($cm_details->address5 != ''){
			          $client_details.=$cm_details->address5.'<br/>';
			        }
				$client_details.='</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">Email:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600; font-size:15px;">'.$cm_details->email.'</h5>
			</div>';
		}
		$user =\App\Models\userLogin::first();
        $financial_date = $user->opening_balance_date;
        $invoice_issued = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_id)->where('invoice_date','<=',$financial_date)->get();
        $output_invoice_issued='';
        $total_gross='';
        $total_outstanding_balance='';
        if(($invoice_issued))
        {
          foreach($invoice_issued as $invoice){
          	if($total_gross == ''){
          		$total_gross = $invoice->gross;
          	}
          	else{
          		$total_gross = $total_gross+$invoice->gross;
          	}
          	if($total_outstanding_balance == ''){
          		$total_outstanding_balance = $invoice->outstanding_balance;
          	}
          	else{
          		$total_outstanding_balance = $total_outstanding_balance+$invoice->outstanding_balance;
          	}
            $output_invoice_issued.='<tr>
              <td><a href="javascript:" class="invoice_inside_class invoice_sort_val" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>              
              <td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
              <td>'.number_format_invoice($invoice->gross).'</td>
              <td>'.number_format_invoice_empty($invoice->outstanding_balance).'</td>
            </tr>';
          }
        }
        if($total_outstanding_balance == ''){
        	$total_outstanding_balance = '0.00';
        }
        else{
        	$total_outstanding_balance = $total_outstanding_balance;
        }
		echo json_encode(array('client_details' => $client_details, 'output_invoice_issued' => $output_invoice_issued, 'total_gross' => number_format_invoice($total_gross), 'total_outstanding_balance' => number_format_invoice($total_outstanding_balance)));
	}
	public function opening_balance_export_all(Request $request){
		$columns_1 = array('S.No', 'Client ID', 'Client', 'Invoices Issued at O/B Date','OS invoices at OB Date', 'Opening Balance');
		$filename = 'Export Client Opening Balance Manager.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);
	    $clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
	    $i=1;
	    $total_invoice_issued='';
        $total_balance='';
	    if(($clientlist)){
	    	foreach($clientlist as $key => $client){
	    		$balance_check = \App\Models\OpeningBalance::where('client_id',$client->client_id)->first();
	    		if(($balance_check))
	              {
	                if($balance_check->opening_balance == "")
	                {
	                  $balance = '-';
	                }
	                elseif($balance_check->opening_balance == 0)
	                {
	                  $balance = '0.00';
	                }
	                elseif($balance_check->opening_balance < 0)
	                {
	                  $balance = number_format_invoice($balance_check->opening_balance);
	                }
	                else{
	                  $balance = number_format_invoice($balance_check->opening_balance);
	                }
	                if($total_balance == '' || $total_balance == 0.00){
	                  $total_balance = $balance_check->opening_balance;
	                }
	                else{
	                  $total_balance = (int)$total_balance+(int)$balance_check->opening_balance;
	                }
	                /*if($balance_check->locked == 0)
	                {
	                  $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=1').'" class="fa fa-unlock" style="color:green;font-size:18px !important"></a>';
	                }
	                else{
	                  $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=0').'" class="fa fa-lock" style="color:#f00;font-size:18px !important"></a>';
	                }*/
	            }
	            else{
	                $balance = '-';
	                /*$action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=1').'" class="fa fa-unlock" style="color:green;font-size:18px !important"></a>';*/
	            }
	            if($client->company == ''){
					$companyname = $client->surname.' '.$client->firstname;
				}
				else{
					$companyname = $client->company;
				}
				$user =\App\Models\userLogin::first();
				$financial_date = $user->opening_balance_date;
				$invoice_issued = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->client_id)->where('invoice_date','<=',$financial_date)->sum('gross');  
				if($total_invoice_issued == ''){
	                $total_invoice_issued = $invoice_issued;
	            }
	            else{
	                $total_invoice_issued = (int)$total_invoice_issued+(int)$invoice_issued;
	            }
	            $os_value = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client->client_id)->where('invoice_date','<=',$financial_date)->sum('outstanding_balance');
	            $columns_2 = array($i, $client->client_id, $companyname, number_format_invoice($invoice_issued), number_format_invoice($os_value), $balance);
	            fputcsv($fileopen, $columns_2);
	            $i++;
	    	}
	    }
	    $columns_3 = array("", "", "", number_format_invoice($total_invoice_issued), "", number_format_invoice($total_balance));
		fputcsv($fileopen, $columns_3);
	    fclose($fileopen);
	    echo $filename;
	}
}