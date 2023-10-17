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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class FinancialController extends Controller {
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
	public function financials(Request $request)
	{
		return view('user/financials/financials', array('title' => 'Financials'));
	}
	public function bank_account_manager(Request $request)
	{
		return view('user/bank_account/bank_account_manager', array('title' => 'Bank Account Manager'));
	}
	public function check_nominal_code(Request $request)
	{
		$nominal_code = $request->get('nominal_code_add');
		$check_nominal_code = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$nominal_code)->first();
		if(($check_nominal_code))
		{
			$valid = false;
		}
		else{
			$valid = true;
		}
		echo json_encode($valid);
		exit;
	}
	public function add_nominal_code_financial(Request $request)
	{
		$code = $request->get('code');
		$description = $request->get('description');
		$primary = $request->get('primary');
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$data['code'] = $code;
		$data['description'] = $description;
		$data['primary_group'] = $primary;
		if($primary == "Profit & Loss")
		{
			$data['debit_group'] = $debit;
			$data['credit_group'] = $debit;
		}
		else{
			$data['debit_group'] = $debit;
			$data['credit_group'] = $credit;
		}
		$data['type'] = 1;
		$already = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->first();
		if(($already))
		{
			\App\Models\NominalCodes::where('id',$already->id)->update($data);
			$table_type = 1;
		}
		else{
			$data['practice_code'] = Session::get('user_practice_code');
			\App\Models\NominalCodes::insert($data);
			$table_type = 0;
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('code','asc')->get();
		$output = '<option value="">Select Nominal Code</option>';
		if(($nominal_codes)) {
          foreach($nominal_codes as $code){
            $output.='<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
          }
        }
		echo json_encode(array("code" => $code,"description" => $description, "primary" => $primary,"debit" => $debit,"credit" => $credit,"table_type" => $table_type,'dropdown_output' => $output));
	}
	public function add_bank_financial(Request $request)
	{
		$bank_name = $request->get('bank_name');
		$account_name = $request->get('account_name');
		$account_no = $request->get('account_no');
		$description = $request->get('description');
		$code = $request->get('code');
		$data['bank_name'] = $bank_name;
		$data['account_name'] = $account_name;
		$data['account_number'] = $account_no;
		$data['description'] = $description;
		$data['nominal_code'] = $code;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\FinancialBanks::insertDetails($data);
		$dataval['description'] = $description;
		\App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->update($dataval);
		$bank_count = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->get();
		$banks = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->get();
	      $output_bank='<option value="">Select Bank</option>';
	      if(($banks)){
	        foreach($banks as $bank){
	          $output_bank.='<option value="'.base64_encode($bank->id).'">'.$bank->bank_name.' ('.$bank->account_name.') '.$bank->account_number.'</option>';
	        }
	      }
	      else{
	        $output_bank='<option value="">Select Bank</option>';                
	      }
		echo json_encode(array("id" => $id,"bank_name" => $bank_name, "account_name" => $account_name,"account_no" => $account_no,"description" => $description, "code" => $code,"bank_counts" => ($bank_count), 'output_bank' => $output_bank));
	}
	public function update_bank_financial(Request $request)
	{
		$bank_name = $request->get('bank_name');
		$account_name = $request->get('account_name');
		$account_no = $request->get('account_no');
		$description = $request->get('description');
		$id = $request->get('bank_id');
		$data['bank_name'] = $bank_name;
		$data['account_name'] = $account_name;
		$data['account_number'] = $account_no;
		$data['description'] = $description;
		\App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->update($data);
		$bank_code = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->first();
		$dataval['description'] = $description;
		\App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$bank_code->nominal_code)->update($dataval);
		$bank_count = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->get();
		echo json_encode(array("id" => $id,"bank_name" => $bank_name, "account_name" => $account_name,"account_no" => $account_no,"description" => $description, "code" => $bank_code->nominal_code));
	}
	public function edit_nominal_code_finance(Request $request)
	{
		$code = $request->get('code');
		$get_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->first();
		if(($get_codes))
		{
			$data['code'] = $get_codes->code;
			$data['description'] = $get_codes->description;
			$data['primary'] = $get_codes->primary_group;
			$data['debit'] = $get_codes->debit_group;
			$data['credit'] = $get_codes->credit_group;
			echo json_encode($data);
		}
	}
	public function get_nominal_codes_for_bank(Request $request)
	{
		$get_nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Balance Sheet')->where('debit_group','Current Assets')->orderBy('code','asc')->get();
		$option = '<option value="">Select Nominal Code</option>';
		if(($get_nominal_codes))
		{
			foreach($get_nominal_codes as $code)
			{
				$check_code = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('nominal_code',$code->code)->first();
				if(!($check_code))
				{
					$option.='<option value="'.$code->code.'">'.$code->code.'</option>';
				}
			}
		}
		echo $option;
	}
	public function financial_opening_balance_show(Request $request)
	{
		$id = $request->get('id');
		$date =\App\Models\userLogin::where('id',1)->first();
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->first();
		$data['description'] = $bank_details->description;
		$data['bank_name'] = $bank_details->bank_name;
		$data['account_name'] = $bank_details->account_name;
		$data['account_no'] = $bank_details->account_number;
		$data['debit_balance'] = ($bank_details->debit_balance != "") ? number_format_invoice($bank_details->debit_balance) : "";
		$data['credit_balance'] = ($bank_details->credit_balance != "") ? number_format_invoice($bank_details->credit_balance) : "";
		$data['opening_balance_date'] = date('d-M-Y', strtotime($date->opening_balance_date));
		echo json_encode($data);
	}
	public function save_opening_balance_values(Request $request)
	{
		$id = $request->get('id');
		$debit_balance = $request->get('debit_balance');
		$credit_balance = $request->get('credit_balance');
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->first();
		if(($bank_details))
		{
			$journal_id = $bank_details->journal_id;
			$code = $bank_details->nominal_code;
			if($journal_id != 0)
			{
				if($debit_balance != "" && $debit_balance != "0.00" && $debit_balance != "0")
				{
					$dataval['journal_source'] = 'BOB';
					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					\App\Models\Journals::where('connecting_journal_reference',$journal_id)->update($dataval);
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					\App\Models\Journals::where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}
				else{
					$dataval['journal_source'] = 'BOB';
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					\App\Models\Journals::where('connecting_journal_reference',$journal_id)->update($dataval);
					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					\App\Models\Journals::where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}
				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				\App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->update($data);
				echo $journal_id;
			}
			else{
				$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
				$next_connecting_journal = count($count_total_journals) + 1;
				$date =\App\Models\userLogin::where('id',1)->first();
				$dataval['journal_date'] = $date->opening_balance_date;
				$dataval['description'] = 'Bank Account Opening Balance';
				$dataval['reference'] = 'OB'.$id;
				$dataval['journal_source'] = 'BOB';
				$dataval['practice_code'] = Session::get('user_practice_code');
				if($debit_balance != "" && $debit_balance != "0.00" && $debit_balance != "0")
				{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					\App\Models\Journals::insert($dataval);
					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					\App\Models\Journals::insert($dataval);
				}
				else{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					\App\Models\Journals::insert($dataval);
					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					\App\Models\Journals::insert($dataval);
				}
				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				$data['journal_id'] = $next_connecting_journal;
				\App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->update($data);
				echo $next_connecting_journal;
			}
		}
	}
	public function save_opening_balance_date(Request $request)
	{
		$date = $request->get('opening_balance_date');
		$data['opening_balance_date'] = date('Y-m-d', strtotime($date));
		\App\Models\userLogin::where('id',1)->update($data);
	}
	public function load_journals_financials(Request $request)
	{
		$selection = $request->get('selection');
		$fromdate = $request->get('from');
		$todate = $request->get('to');
		if($selection == "4")
		{
			$explodefrom = explode('/',$fromdate);
			$explodeto = explode('/',$todate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = strtotime(date('Y-m-d',strtotime($from)));
			$to_date = strtotime(date('Y-m-d',strtotime($to)));
			$journals = DB::select('SELECT * from `journals` WHERE UNIX_TIMESTAMP(`journal_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`journal_date`) <= "'.$to_date.'" AND `practice_code` = "'.Session::get('user_practice_code').'" ORDER BY `id` ASC');
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$journals = \App\Models\Journals::where('practice_code',Session::get('user_practice_code'))->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$journals = \App\Models\Journals::where('practice_code',Session::get('user_practice_code'))->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$journals = \App\Models\Journals::where('practice_code',Session::get('user_practice_code'))->where('journal_date','like',$current_month.'-%')->orderBy('id','asc')->get();
		}
		$output = '<table class="table own_table_white" id="journal_table">
		<thead>
			<th style="width:120px;text-align:left">Journal ID <i class="fa fa-sort journal_id_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal Date <i class="fa fa-sort journal_date_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal Description <i class="fa fa-sort journal_des_sort" style="float: right"></i></th>
			<th style="text-align:left">Nominal Code <i class="fa fa-sort nominal_code_sort" style="float: right"></i></th>
			<th style="text-align:left">Nominal Code Description <i class="fa fa-sort nominal_des_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal Source <i class="fa fa-sort source_sort" style="float: right"></i></th>
			<th style="width:120px;text-align: right">Debit Value <i class="fa fa-sort debit_journal_sort" style="float: right"></i></th>
            <th style="width:120px;text-align: right">Credit Value <i class="fa fa-sort credit_journal_sort" style="float: right"></i></th>
		</thead>
		<tbody id="load_journals_tbody">';
		if(($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
				$output.='<tr>
					<td><a href="javascript:" class="journal_id_viewer journal_id_sortval" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
					<td><spam class="journal_date_sortval" style="display:none">'.strtotime($journal->journal_date).'</spam>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
					<td class="journal_des_sortval">'.$journal->description.'</td>
					<td class="nominal_code_sortval">'.$journal->nominal_code.'</td>
					<td class="nominal_des_sortval">'.$get_nominal->description.'</td>
					<td class="source_sortval"><a href="javascript:" class="journal_source_link">'.$journal->journal_source.'</a></td>
					<td style="text-align:right"><spam class="debit_journal_sortval" style="display:none">'.$journal->dr_value.'</spam>'.number_format_invoice($journal->dr_value).'</td>
					<td style="text-align:right"><spam class="credit_journal_sortval" style="display:none">'.$journal->cr_value.'</spam>'.number_format_invoice($journal->cr_value).'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="8" style="text-align:center">No Journals Found</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function save_debit_credit_finance_client(Request $request)
	{
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$debit = number_format_invoice_without_comma($debit);
		$credit = number_format_invoice_without_comma($credit);
		$client_id = $request->get('client_id');
		$bal_status = '0';
		$commit_status = '0';
		$owed_text = '';
		$data['debit'] = $debit;
		$data['credit'] = $credit;
		if($debit != "0.00" && $credit != "0.00")
		{
			$data['balance'] = 'ERROR';
			$bal_status = 1;
		}
		else{
			$sum = number_format_invoice_without_comma($debit - $credit);
			$data['balance'] = $sum;
			if($sum != "0.00")
			{
				$commit_status = '1';
				if($sum >= 0) { $owed_text = '<spam style="color:green;font-size:12px;font-weight:600">Client Owes Back</spam>'; }
				else { $owed_text = '<spam style="color:#f00;font-size:12px;font-weight:600">Client Is Owed</spam>'; }
			}
		}
		$check_client = \App\Models\FinanceClients::where('client_id',$client_id)->first();
		if(($check_client))
		{
			\App\Models\FinanceClients::where('id',$check_client->id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			\App\Models\FinanceClients::insert($data);
		}
		echo json_encode(array("bal_status" => $bal_status, 'commit_status' => $commit_status, 'owed_text' => $owed_text, "balance" => number_format_invoice($data['balance'])));
	}
	public function export_csv_client_opening(Request $request)
	{
		$filename = 'client_account_opening_balance_manager.csv';
		$columns = array('Client Code','Surname','Firstname','Company Name','Debit', 'Credit','Balance','Details');
		$file = fopen('public/papers/client_account_opening_balance_manager.csv', 'w');
		fputcsv($file, $columns);
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		if(($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
				$debit = '0.00';
				$credit = '0.00';
				$balance = '0.00';
				$bal_style = '';
				$owed_text = '';
				$commit_style="display:none";
				if(($finance_client))
				{
					$debit = ($finance_client->debit != "")?$finance_client->debit:"0.00";
					$credit = ($finance_client->credit != "")?$finance_client->credit:"0.00";
					if($debit != "" && $debit != "0.00" && $debit != "0" && $credit != "" && $credit != "0.00" && $credit != "0")
					{
						$balance = 'ERROR';
					}
					else{
						$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
						if($balance != "0.00" && $balance != "" && $balance != "0")
						{
							if($finance_client->balance >= 0) { $owed_text = 'Client Owes Back'; }
							else { $owed_text = 'Client Is Owed'; }
						}
					}
				}
				$columns1 = array($client->client_id,$client->surname,$client->firstname,$client->company,number_format_invoice_without_comma($debit),number_format_invoice_without_comma($credit),$balance,$owed_text);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function commit_client_account_opening_balance(Request $request)
	{
		$client_id = $request->get('client_id');
		$finance_client = \App\Models\FinanceClients::where('client_id',$client_id)->first();
		$get_sets = \App\Models\Journals::groupBy('reference')->get();
		$next_ref_id = count($get_sets) + 1;
		if(($finance_client))
		{
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
			$opening_dete_details =\App\Models\userLogin::where('id',1)->first();
			if($finance_client->debit > 0)
			{
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '813A';
				$data['dr_value'] = $finance_client->debit;
				$data['cr_value'] = '0.00';
				$data['connecting_journal_reference'] = $next_ref_id;
				$data['reference'] = 'CFA'.$finance_client->id;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '991';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $finance_client->debit;
				$data['connecting_journal_reference'] = $next_ref_id.'.01';
				$data['reference'] = 'CFA'.$finance_client->id;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				$dataval['journal_id'] = $next_ref_id;
				\App\Models\FinanceClients::where('id',$finance_client->id)->update($dataval);
				echo $next_ref_id;
			}
			elseif($finance_client->credit > 0)
			{
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '991';
				$data['dr_value'] = $finance_client->credit;
				$data['cr_value'] = '0.00';
				$data['connecting_journal_reference'] = $next_ref_id;
				$data['reference'] = 'CFA'.$finance_client->id;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '813A';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $finance_client->credit;
				$data['connecting_journal_reference'] = $next_ref_id.'.01';
				$data['reference'] = 'CFA'.$finance_client->id;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($data);
				$dataval['journal_id'] = $next_ref_id;
				\App\Models\FinanceClients::where('id',$finance_client->id)->update($dataval);
				echo $next_ref_id;
			}
		}
	}
	public function edit_bank_account_finance(Request $request)
	{
		$bank_id = $request->get('id');
		$banks = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$bank_id)->first();
		if(($banks))
		{
			$data['bank_name'] = $banks->bank_name;
			$data['account_name'] = $banks->account_name;
			$data['account_no'] = $banks->account_number;
			$data['description'] = $banks->description;
			echo json_encode($data);
		}
	}
	public function summary_clients_list(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		if(($clients))
		{
			foreach($clients as $client)
			{
				$output.='<tr class="summary_tr summary_tr_'.$client->client_id.'">
				  <td class="client_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->client_id.'</a></td>
				  <td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
				  <td class="surname_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->surname.'</a></td>
				  <td class="firstname_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->firstname.'</a></td>
				  <td class="company_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->company.'</a></td>
				  <td class="opening_bal_summary_sort_val" style="text-align:right"></td>
				  <td class="receipt_summary_sort_val" style="text-align:right"></td>
				  <td class="payment_summary_sort_val" style="text-align:right"></td>
				  <td class="balance_summary_sort_val" style="text-align:right"></td>
				</tr>';
			}
		}
	    echo $output;
	}
	public function summary_load_opening_balance(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output_array= array();
		$total = 0;
		if(($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$total = $total + $opening_bal;
				array_push($output_array, number_format_invoice($opening_bal));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_load_receipts(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output_array= array();
		$total = 0;
		if(($clients))
		{
			foreach($clients as $client)
			{
				$client_receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$total = $total + ($client_receipt * -1);
				array_push($output_array, number_format_invoice($client_receipt * -1));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_load_payments(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output_array= array();
		$total = 0;
		if(($clients))
		{
			foreach($clients as $client)
			{
				$client_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
				$total = $total + $client_payment;
				array_push($output_array, number_format_invoice($client_payment));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_calculations(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$output_array= array();
		$total = 0;
		if(($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$client_receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$client_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
				$sumval = $opening_bal + ($client_receipt * -1);
				$sumval = $sumval + $client_payment;
				$total = $total + $sumval;
				array_push($output_array, number_format_invoice($sumval));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_export_csv(Request $request)
	{
		$clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		$filename = 'client_account_summary.csv';
		$columns = array('Client Code','Surname','Firstname','Company Name','Opening Balance', 'Client money Received','Payments Made','Balance');
		$file = fopen('public/papers/client_account_summary.csv', 'w');
		fputcsv($file, $columns);
		if(($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$client_receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$client_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
				$sumval = $opening_bal + ($client_receipt * -1);
				$sumval = $sumval + $client_payment;
				$columns1 = array($client->client_id,$client->surname,$client->firstname,$client->company,$opening_bal, ($client_receipt * -1),$client_payment,$sumval);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function load_trial_balance_nominals(Request $request)
	{
		$selection = $request->get('selection');
		$fromdate = $request->get('from');
		$todate = $request->get('to');
		if($selection == "4")
		{
			$explodefrom = explode('/',$fromdate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$explodeto = explode('/',$todate);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('nominal_codes.code','asc')->get();
		$nom_codes = '';
		$total_nominal_debit = 0;
		$total_nominal_credit = 0;
		if(($nominal_codes))
		{
			foreach($nominal_codes as $codes)
			{
				$debits_open = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$credits_open = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$opening_bal = number_format_invoice_without_comma($debits_open[0]->debits - $credits_open[0]->credits);
				$debits = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$credits = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$closing_bal = number_format_invoice_without_comma(($opening_bal + $debits[0]->debits) - $credits[0]->credits);
				if($closing_bal == 0 || $closing_bal == 0.00 || $closing_bal == '' || $closing_bal == '0.00' || $closing_bal == '0')
				{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = '0.00';
					$nil_bal = 'nil_balance_tr';
					$opening = $opening_bal;
				}
				elseif($closing_bal > 0)
				{
					$nominal_debit_value = number_format_invoice_without_comma($closing_bal);
					$nominal_credit_value = '0.00';
					$nil_bal = '';
					$opening = $opening_bal;
				}
				else{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = number_format_invoice_without_comma($closing_bal);
					$nil_bal = '';
					$opening = $opening_bal;
				}
				$total_nominal_debit = $total_nominal_debit + $nominal_debit_value;
				$total_nominal_credit = $total_nominal_credit + $nominal_credit_value;
				if($codes->primary_group == "Profit & Loss") {
					$sub_group = $codes->debit_group;
				} elseif($codes->primary_group == "Balance Sheet") {
					$sub_group = $codes->debit_group.' / '.$codes->credit_group;
				} else {
					$sub_group = '';
				} 
				$des_code = $codes->description;
				$nom_codes.='<tr class="des_tr_'.$codes->code.' code_'.$codes->code.' '.$nil_bal.'">
					  <td><a href="javascript:" class="get_nominal_code_journals code_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$codes->code.'</a></td>
					  <td><a href="javascript:" class="get_nominal_code_journals des_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$des_code.'</a></td>
					  <td><a href="javascript:" class="get_nominal_code_journals primary_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$codes->primary_group.'</a></td>
					  <td><a href="javascript:" class="get_nominal_code_journals secondary_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$sub_group.'</a></td>
					  <td style="text-align:right"><a href="javascript:" class="get_nominal_code_journals" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.number_format_invoice($nominal_debit_value).'</a> <spam class="debit_trial_sort_val" style="display:none">'.$nominal_debit_value.'</spam></td> 
					  <td style="text-align:right"><a href="javascript:" class="get_nominal_code_journals" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.number_format_invoice($nominal_credit_value).'</a> <spam class="credit_trial_sort_val" style="display:none">'.$nominal_credit_value.'</spam></td>
					</tr>';
			}
		}
		$data['output'] = $nom_codes;
		$data['total_nominal_debit'] = number_format_invoice($total_nominal_debit);
		$data['total_nominal_credit'] = number_format_invoice($total_nominal_credit);
		echo json_encode($data);
	}
	public function extract_trial_balance_nominals_pdf(Request $request)
	{
		$selection = $request->get('selection');
		$fromdate = $request->get('from');
		$todate = $request->get('to');
		$nil_balance = $request->get('nil_balance');
		if($selection == "4") {
			$explodefrom = explode('/',$fromdate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$explodeto = explode('/',$todate);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1") {
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2") {
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3") {
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('nominal_codes.code','asc')->get();
		$nom_codes = '';
		$total_nominal_debit = 0;
		$total_nominal_credit = 0;
		/*********Need to change in future*******************/
		$settings = \App\Models\settings::where('source','practice')->first();
		$practice_name = '';
        if(($settings))
        {
          $settingsval = unserialize($settings->settings);
          $practice_name = $settingsval['practice_name'];
        }
        /*********Need to change in future*******************/
		$output = '<style>
		.table_style1 {
		width: 100%;
		border-collapse:collapse;
		border:1px solid #c5c5c5;
		margin-bottom:20px;
		margin-top:20px;
		}
		.table_style1 tr th,.table_style1 tr td {
		border:1px solid #c5c5c5;
		}
		body{
		font-size:14px;
		}
		.nil_balance_tr{
		display:none;
		}
		</style>
		<h3 style="text-align:center">Trial Balance - '.$practice_name.' - '.date('d-M-Y', strtotime($from_date)).' to '.date('d-M-Y', strtotime($to_date)).'</h3>
		<table class="table_style1">
		<thead>
			<tr>
			<th style="padding:5px">Nominal Code</th>
			<th style="padding:5px">Nominal Description</th>
			<th style="padding:5px">Primary Group</th>
			<th style="padding:5px">Sub Group</th>
			<th style="padding:5px">Debit Value</th>
			<th style="padding:5px">Credit Value</th>
			</tr>
		</thead>
		<tbody>';
		if(($nominal_codes))
		{
			foreach($nominal_codes as $codes)
			{
				$debits_open = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$credits_open = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$opening_bal = number_format_invoice_without_comma($debits_open[0]->debits - $credits_open[0]->credits);
				$debits = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$credits = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$closing_bal = number_format_invoice_without_comma(($opening_bal + $debits[0]->debits) - $credits[0]->credits);
				if($closing_bal == 0 || $closing_bal == 0.00 || $closing_bal == '' || $closing_bal == '0.00' || $closing_bal == '0')
				{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = '0.00';
					$ex_nominal_debit_value = '-';
					$ex_nominal_credit_value = '-';
					if($nil_balance == "1") {
						$nil_bal = 'nil_balance_tr';
					} else{
						$nil_bal = '';
					}
					$opening = $opening_bal;
				}
				elseif($closing_bal > 0)
				{
					$nominal_debit_value = number_format_invoice_without_comma($closing_bal);
					$nominal_credit_value = '0.00';
					$ex_nominal_debit_value = number_format_invoice($closing_bal);
					$ex_nominal_credit_value = '-';
					$nil_bal = '';
					$opening = $opening_bal;
				}
				else{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = number_format_invoice_without_comma($closing_bal);
					$ex_nominal_debit_value = '-';
					$ex_nominal_credit_value = number_format_invoice($closing_bal);
					$nil_bal = '';
					$opening = $opening_bal;
				}
				$total_nominal_debit = $total_nominal_debit + $nominal_debit_value;
				$total_nominal_credit = $total_nominal_credit + $nominal_credit_value;
				if($codes->primary_group == "Profit & Loss") {
					$sub_group = $codes->debit_group;
				} elseif($codes->primary_group == "Balance Sheet") {
					$sub_group = $codes->debit_group.' / '.$codes->credit_group;
				} else {
					$sub_group = '';
				} 
				$des_code = $codes->description;
				$output.='<tr class="'.$nil_bal.'">
					  <td style="border-bottom:0px;border-top:0px;padding:5px">'.$codes->code.'</td>
					  <td style="border-bottom:0px;border-top:0px;padding:5px">'.$des_code.'</td>
					  <td style="border-bottom:0px;border-top:0px;padding:5px">'.$codes->primary_group.'</td>
					  <td style="border-bottom:0px;border-top:0px;padding:5px">'.$sub_group.'</td>
					  <td style="border-bottom:0px;border-top:0px;text-align:right;padding:5px">'.$ex_nominal_debit_value.'</td> 
					  <td style="border-bottom:0px;border-top:0px;text-align:right;padding:5px">'.$ex_nominal_credit_value.'</td>
					</tr>';
			}
		}
		$output.='
		<tr>
			<td style="padding:5px"></td>
			<td style="padding:5px"></td>
			<td style="padding:5px"></td>
			<td style="padding:5px">Total:</td>
			<td style="text-align:right;padding:5px">'.number_format_invoice($total_nominal_debit).'</td>
			<td style="text-align:right;padding:5px">'.number_format_invoice($total_nominal_credit).'</td>
		</tr>
		</tbody>
		</table>';
		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4', 'portrait');
		$file = 'Trial Balance - '.$practice_name.' - '.date('d-M-Y', strtotime($from_date)).' to '.date('d-M-Y', strtotime($to_date)).' - '.time().'.pdf';
		$pdf->save('public/papers/'.$file.'');
		echo $file;
	}
	public function extract_trial_balance_nominals_csv(Request $request)
	{
		$selection = $request->get('selection');
		$fromdate = $request->get('from');
		$todate = $request->get('to');
		$nil_balance = $request->get('nil_balance');
		if($selection == "4") {
			$explodefrom = explode('/',$fromdate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$explodeto = explode('/',$todate);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1") {
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2") {
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3") {
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('nominal_codes.code','asc')->get();
		$nom_codes = '';
		$total_nominal_debit = 0;
		$total_nominal_credit = 0;
		$practice_code = Session::get('user_practice_code');
		/*********Need to change in future*******************/
		$settings = \App\Models\settings::where('source','practice')->first();
		$practice_name = '';
        if(($settings))
        {
          $settingsval = unserialize($settings->settings);
          $practice_name = $settingsval['practice_name'];
        }
        /*********Need to change in future*******************/
		$csvfilename = 'Trial Balance - '.$practice_name.' - '.date('d-M-Y', strtotime($from_date)).' to '.date('d-M-Y', strtotime($to_date)).' - '.time().'.csv';
		$file = fopen('public/papers/'.$csvfilename.'', 'w');
		$columns = array('Trial Balance - '.$practice_name.' - '.date('d-M-Y', strtotime($from_date)).' to '.date('d-M-Y', strtotime($to_date)),'','','','', '');
		fputcsv($file, $columns);
		$columns1 = array('','','','','', '');
		fputcsv($file, $columns1);
		$columns2 = array('Nominal Code','Nominal Description','Primary Group','Sub Group','Debit Value', 'Credit Value');
		fputcsv($file, $columns2);
		if(($nominal_codes))
		{
			foreach($nominal_codes as $codes)
			{
				$debits_open = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$credits_open = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$opening_bal = number_format_invoice_without_comma($debits_open[0]->debits - $credits_open[0]->credits);
				$debits = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$credits = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$closing_bal = number_format_invoice_without_comma(($opening_bal + $debits[0]->debits) - $credits[0]->credits);
				if($closing_bal == 0 || $closing_bal == 0.00 || $closing_bal == '' || $closing_bal == '0.00' || $closing_bal == '0')
				{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = '0.00';
					if($nil_balance == "1") {
						$nil_bal = '1';
					} else{
						$nil_bal = '';
					}
					$opening = $opening_bal;
				}
				elseif($closing_bal > 0)
				{
					$nominal_debit_value = number_format_invoice_without_comma($closing_bal);
					$nominal_credit_value = '0.00';
					$nil_bal = '';
					$opening = $opening_bal;
				}
				else{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = number_format_invoice_without_comma($closing_bal);
					$nil_bal = '';
					$opening = $opening_bal;
				}
				$total_nominal_debit = $total_nominal_debit + $nominal_debit_value;
				$total_nominal_credit = $total_nominal_credit + $nominal_credit_value;
				if($codes->primary_group == "Profit & Loss") {
					$sub_group = $codes->debit_group;
				} elseif($codes->primary_group == "Balance Sheet") {
					$sub_group = $codes->debit_group.' / '.$codes->credit_group;
				} else {
					$sub_group = '';
				} 
				$des_code = $codes->description;
				if($nil_bal == "")
				{
					$columns3 = array($codes->code,$des_code,$codes->primary_group,$sub_group,number_format_invoice($nominal_debit_value),number_format_invoice($nominal_credit_value));
					fputcsv($file, $columns3);
				}
			}
		}
		$columns4 = array('','','','Total:',number_format_invoice($total_nominal_debit),number_format_invoice($total_nominal_credit));
		fputcsv($file, $columns4);
		fclose($file);
		echo $csvfilename;
	}
	public function load_trial_balance_journals_for_nominal(Request $request)
	{
		$code = $request->get('code');
		$selection = $request->get('selection');
		$from = $request->get('from');
		$to = $request->get('to');
		$opening = $request->get('opening');
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		if($selection == "4")
		{
			$explodefrom = explode('/',$from);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$explodeto = explode('/',$to);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->first();
		$journals = DB::select('SELECT * from `journals` WHERE `nominal_code` = "'.$code.'" AND `journal_date` >= "'.$from_date.'" AND `journal_date` <= "'.$to_date.'"');
		$error = 0;
		$output = '
		<table class="table own_table_white" id="journal_viewer_extend" style="margin-top:20px">
			<thead>
				<th style="text-align:left">Journal <br/>ID</th>
				<th style="text-align:left">Journal <br/>Date</th>
				<th style="text-align:left">Journal <br/>Description</th>
				<th style="text-align:left">Nominal <br/>Code</th>
				<th style="text-align:left">Nominal Code <br/>Description</th>
				<th style="text-align:left">Journal <br/>Source</th>
				<th style="text-align:right">Debit <br/>Value</th>
				<th style="text-align:right">Credit <br/>Value</th>
			</thead>
			<tbody>
			<tr>
				<td></td>
				<td></td>
				<td>Opening Balance</td>
				<td>'.$code.'</td>
				<td>'.$nominal_code_details->description.'</td>
				<td></td>
				<td style="text-align:right">'.number_format_invoice($debit_bal).'</td>
				<td style="text-align:right">'.number_format_invoice($credit_bal).'</td>
			</tr>';
			$total_debit_value = $debit_bal;
			$total_credit_value = $credit_bal;
			if(($journals))
			{
				foreach($journals as $journal)
				{
					$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
					$output.='<tr>
						<td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
						<td><spam style="display:none">'.strtotime($journal->journal_date).'</spam>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
						<td>'.$journal->description.'</td>
						<td>'.$journal->nominal_code.'</td>
						<td>'.$get_nominal->description.'</td>
						<td><a href="javascript:" class="journal_source_link">'.$journal->journal_source.'</a></td>
						<td style="text-align:right">'.number_format_invoice($journal->dr_value).'</td>
						<td style="text-align:right">'.number_format_invoice($journal->cr_value).'</td>
					</tr>';
					$total_debit_value = $total_debit_value + $journal->dr_value;
					$total_credit_value = $total_credit_value + $journal->cr_value;
					$error++;
				}
			}
			$output.='</tbody>
			<tr>
				<td colspan="6">Total</td>
				<td style="text-align:right">'.number_format_invoice($total_debit_value).'</td>
				<td style="text-align:right">'.number_format_invoice($total_credit_value).'</td>
			</tr>
		</table>';
		echo json_encode(array("error" => $error, "output" => $output));
		//echo $output;
	}
	public function extract_trial_balance_journals_for_nominal_csv(Request $request)
	{
		$code = $request->get('code');
		$selection = $request->get('selection');
		$from = $request->get('from');
		$to = $request->get('to');
		$opening = $request->get('opening');
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		if($selection == "4")
		{
			$explodefrom = explode('/',$from);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$explodeto = explode('/',$to);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];
			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->first();
		$journals = DB::select('SELECT * from `journals` WHERE `nominal_code` = "'.$code.'" AND `journal_date` >= "'.$from_date.'" AND `journal_date` <= "'.$to_date.'"');
		$csvfilename = 'Journals for Nominal code - '.$code.'-'.time().'.csv';
		$file = fopen('public/papers/'.$csvfilename.'', 'w');
		$columns = array('Journal ID','Journal Date','Journal Description','Nominal Code','Nominal Code Description', 'Journal Source', 'Debit Value','Credit Value');
		fputcsv($file, $columns);
		$columns1 = array('','','Opening Balance',$code,$nominal_code_details->description, '', number_format_invoice($debit_bal),number_format_invoice($credit_bal));
		fputcsv($file, $columns1);
		$total_debit_value = $debit_bal;
		$total_credit_value = $credit_bal;
		if(($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
				$columns2 = array($journal->connecting_journal_reference,date('d-M-Y',strtotime($journal->journal_date)),$journal->description,$journal->nominal_code,$get_nominal->description, $journal->journal_source, number_format_invoice($journal->dr_value),number_format_invoice($journal->cr_value));
				fputcsv($file, $columns2);
				$total_debit_value = $total_debit_value + $journal->dr_value;
				$total_credit_value = $total_credit_value + $journal->cr_value;
			}
		}
		$columns3 = array('','','','','','Total', number_format_invoice($total_debit_value), number_format_invoice($total_credit_value));
		fputcsv($file, $columns3);
		fclose($file);
		echo $csvfilename;
	}
	public function finance_get_bank_details(Request $request){
		$id = base64_decode($request->get('id'));
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
		$data['bank_name'] = $bank_details->bank_name;
		$data['account_name'] = $bank_details->account_name;
		$data['account_number'] = $bank_details->account_number;
		$data['description'] = $bank_details->description;
		$data['nominal_code'] = $bank_details->nominal_code;
		echo json_encode($data);		
	}
	public function finance_reconcile_load(Request $request){
		$id = base64_decode($request->get('id'));
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
		/*$receipts_details = \App\Models\receipts::where('debit_nominal', $bank_details->nominal_code)->get();*/
		if(($bank_details)){
			$date =\App\Models\userLogin::where('id',1)->first();
			if($bank_details->debit_balance != "")
            {
              $baln = number_format_invoice($bank_details->debit_balance);
              $op_baln = $bank_details->debit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            elseif($bank_details->credit_balance != "")
            {
              $baln = '-'.number_format_invoice($bank_details->credit_balance);
              $op_baln = '-'.$bank_details->credit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            else{
              $baln = '0.00';
              $op_baln = '0.00';
              $opening_balace_text = 'Opening Balance Not Set';
            }
			$output_opending='<tr>
				<td style="display:none">1</td>
				<td><spam style="display:none">'.strtotime($date->opening_balance_date).'</spam>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
				<td>'.$opening_balace_text.'</td>
				<td>Open Balance</td>
				<td align="right">'.$baln.'</td>
				<td></td>
				<td align="right"><a href="javascript:" class="journal_id_viewer" data-element="'.$bank_details->journal_id.'">'.$bank_details->journal_id.'</td>
				<td></td>
			</tr>';
		}
		$unreconcied = 0;
		$receipts_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$receipt_ids='';
		$outstanding_receipt=0;
		$receipt_total='';
		$i=2;
		$output_receipt=$output_opending;
		if(($receipts_details)){
			foreach ($receipts_details as $receipts) {				
				$colortext = '#000';
				if(substr($receipts->credit_nominal,0,3) == "771")
				{
					$colortext = '#f00';
					$unreconcied = $unreconcied + 1;
				}
				if($receipts->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" style="width:100%; float:left; text-align:center" class="single_accept" type="1" data-element="'.$receipts->id.'">-</a>';					
					if($receipts->amount < 0){
						$color = 'red';
						$outstanding = $receipts->amount;
					}
					else{
						$color = 'green';
						$outstanding = $receipts->amount;
					}
					if($outstanding_receipt == ''){
						$outstanding_receipt = $receipts->amount;
					}
					else{
						$outstanding_receipt = $outstanding_receipt+$receipts->amount;
					}
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($receipts->clearance_date));
					$color = 'blue';
					$outstanding = 0;
				}
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipts->credit_nominal)->first();
				$description = $receipts->credit_nominal.'-'.$receipts->credit_description;
				if(($code_description)){
					$description = $receipts->credit_nominal.'-'.$code_description->description;
				}
				if($receipt_ids == ''){
					$receipt_ids = $receipts->id;
				}
				else{
					$receipt_ids = $receipt_ids.','.$receipts->id;
				}
				if($receipt_total == ''){
					$receipt_total = $receipts->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipts->amount;
				}
				$process_journal ='';
				if(($receipts->journal_id == 0) && ($receipts->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}
				$output_receipt.='<tr>
				<td style="display:none">'.$i.'</td>
				<td style="color:'.$colortext.'"><spam style="display:none">'.strtotime($receipts->receipt_date).'</spam>'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
				<td style="color:'.$colortext.'">'.$description.'</td>
				<td style="color:'.$colortext.'"><a href="javascript:" class="receipt_viewer_class" data-element="'.$receipts->id.'">Receipts</a></td>
				<td style="color:'.$colortext.'" align="right" id="receipt_amount_'.$receipts->id.'">'.number_format_invoice($receipts->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="receipt_out_'.$receipts->id.'">'.number_format_invoice($outstanding).'</td>
				<td style="color:'.$colortext.';text-align:right" class="journal_td">';
					if($receipts->journal_id != 0){
						$output_receipt.='<a href="javascript:" class="journal_id_viewer" data-element="'.$receipts->journal_id.'">'.$receipts->journal_id.'</a>';
					}
				$output_receipt.='</td>
				<td style="color:'.$colortext.'" class="receipt_clear '.$process_journal.'" id="receipt_clear_'.$receipts->id.'" data-element="'.$receipts->id.'">'.$clearance_date.'</td>
			</tr>';
			$i++;
			}			
		}
		$payment_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$payment_ids='';
		$payment_total='';
		$j=$i;
		$outstanding_payment=0;
		$output_payment=$output_receipt;
		if(($payment_details)){
			foreach ($payment_details as $payment) {
				$colortext = '#000';
				if(substr($payment->debit_nominal,0,3) == "771")
				{
					$colortext = '#f00';
					$unreconcied = $unreconcied + 1;
				}
				if($payment->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" class="single_accept" type="2" data-element="'.$payment->payments_id.'" style="width:100%; float:left; text-align:center">-</a>';					
					if($payment->amount < 0){
						$color = 'red';
						$outstanding = $payment->amount;
					}
					else{
						$color = 'green';
						$outstanding = $payment->amount;
					}
					if($outstanding_payment == ''){
						$outstanding_payment = $payment->amount;
					}
					else{
						$outstanding_payment = $outstanding_payment+$payment->amount;
					}
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($payment->clearance_date));
					$color = 'blue';
					$outstanding = 0;
				}
				if($payment_ids == ''){
					$payment_ids = $payment->payments_id;
				}
				else{
					$payment_ids = $payment_ids.','.$payment->payments_id;
				}
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->debit_nominal)->first();
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				if(($code_description)){
					$description = $payment->debit_nominal.'-'.$code_description->description;
				}
				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}
				$process_journal = '';
				if(($payment->journal_id == 0) && ($payment->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}
				$output_payment.='<tr>
				<td style="display:none">'.$j.'</td>
				<td style="color:'.$colortext.'"><spam style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td style="color:'.$colortext.'">'.$description.'</td>
				<td style="color:'.$colortext.'"><a href="javascript:" class="payment_viewer_class" data-element="'.$payment->payments_id.'">Payments</a></td>
				<td style="color:'.$colortext.'" align="right" id="payment_amount_'.$payment->payments_id.'">'.number_format_invoice($payment->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="payment_out_'.$payment->payments_id.'">'.number_format_invoice($outstanding).'</td>
				<td style="color:'.$colortext.';text-align:right" class="journal_td">';
					if($payment->journal_id != 0){
						$output_payment.='<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
					}
				$output_payment.='</td>
				<td style="color:'.$colortext.'" class="payment_clear '.$process_journal.'" id="payment_clear_'.$payment->payments_id.'" data-element="'.$payment->payments_id.'">'.$clearance_date.'</td>
			</tr>';
			$j++;
			}			
		}
		$outstanding = $outstanding_receipt - $outstanding_payment;
		$balance_transaction = ((int)$op_baln+(int)$receipt_total)-(int)$payment_total;
		$output_reconcilation='
			<tr>
		        <td>Opening Balance:</td>
		        <td class="op_reconcile" align="right" style="width: 150px;">'.number_format_invoice($op_baln).'</td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>Total Value of Receipts:</td>
		        <td class="tvr_reconcile" align="right">'.number_format_invoice($receipt_total).'</td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>Total Value of Payments:</td>
		        <td class="tvp_reconcile" align="right" style="border-bottom:1px solid #000;">'.number_format_invoice($payment_total).'</td>
		        <td></td>
		      </tr>
		      <tr>
		      	<td>Balance Per Processed Transactions:</td>		      	
		      	<td class="bpp_reconcile" align="right">'.number_format_invoice($balance_transaction).'		      			      	
		      	</td>
		      	<td></td>
		      </tr>
		      <tr>
		        <td>Balance Per Bank Statement:</td>
		        <td align="right"><input type="number" placeholder="Enter Value" class="form-control input_balance_bank" style="width:120px;" /></td>
		        <td><input type="text" placeholder="Select Date" class="form-control date_balance_bank" style="width:120px;"/></td>
		      </tr>
		      <tr>
		        <td>Total Value of Outstanding Items:</td>
		        <td align="right"><span class="class_total_outstanding_refresh">'.number_format_invoice($outstanding).'
		        </span>
		        <input type="hidden" class="refresh_input_outstanding" readonly value="'.$outstanding.'">
		        </td>
		        <td><a href="javascript:" class="common_black_button refresh_button fa fa-refresh" style="float: left; padding:5px 9px" title="Refresh"></a></td>
		      </tr>
		      <tr>
		        <td>Reconciled Bank Statement Closing Balance:</td>
		        <td align="right">
		        <span class="class_close_balance"></span>
		        <input type="hidden" value="" class="input_close_balance" readonly>
		        </td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>Difference:</td>
		        <td align="right" style="border-top:1px solid #000;">
		        <span class="class_difference"></span>
		        <input type="hidden" class="input_difference" value="" readonly >
		        </td>
		        <td><input type="button" class="common_black_button accept_reconciliation" value="Accept Reconciliation"></td>
		      </tr>';
		$reconcile_output = '';
		$reconciliations = \App\Models\reconciliations::where('bank_id',$id)->get();
		if(($reconciliations))
		{
			foreach($reconciliations as $reconsile){
				if($reconsile->stmt_date != '0000-00-00')
				{
					$stmt_date = date('d-M-Y', strtotime($reconsile->stmt_date));
				}
				else{
					$stmt_date = '-';
				}
				$reconcile_output.='<tr>
					<td>'.date('d-M-Y', strtotime($reconsile->rec_date)).'</td>
					<td style="text-align:right">'.number_format_invoice_empty($reconsile->stmt_bal).'</td>
					<td>'.$stmt_date.'</td>
					<td>'.$reconsile->total_os_items.'</td>
					<td><a href="'.URL::to($reconsile->rec_attached_dir.'/'.$reconsile->rec_attached_file).'" download>'.$reconsile->rec_attached_file.'</a></td>
				</tr>';
			}
		}
		echo json_encode(array('transactions' => $output_payment, 'receipt_ids' => $receipt_ids, 'payment_ids' => $payment_ids, 'outstanding_payment' => $outstanding_payment, 'reconcilation' => $output_reconcilation, 'balance_transaction' => $balance_transaction, 'outstanding_html' => number_format_invoice($outstanding),  'outstanding' => $outstanding,'unreconciled' => $unreconcied,'reconcile_output' => $reconcile_output));		
	}
	public function finance_bank_single_accept(Request $request){
		$type = $request->get('type');
		$id = $request->get('id');
		$receipt_id = $request->get('receipt_id');
		$payment_id = $request->get('payment_id');
		if($type == 1){
			$receipts_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
			$data['clearance_date'] = $receipts_details->receipt_date;
			$data['hold_status'] = '1';
			\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->update($data);
			$clearance_date = $receipts_details->receipt_date;
		}
		else{
			$payment_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->first();
			$data['clearance_date'] = $payment_details->payment_date;
			$data['hold_status'] = '1';
			\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->update($data);
			$clearance_date = $payment_details->payment_date;			
		}
		$outstanding = '0.00';
		$explode_receipt = explode(',', $receipt_id);
		$outstanding_receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->whereIn('id', $explode_receipt)->where('clearance_date', '0000-00-00')->sum('amount');
		$explode_payment = explode(',', $payment_id);
		$outstanding_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->whereIn('payments_id', $explode_payment)->where('clearance_date', '0000-00-00')->sum('amount');
		$total_outstanding = $outstanding_receipt+$outstanding_payment;
		echo json_encode(array('outstanding' => $outstanding, 'clearance_date' => date('d-M-Y', strtotime($clearance_date)), 'total_outstanding_html' => number_format_invoice($total_outstanding), 'total_outstanding' => $total_outstanding));
	}
	public function balance_per_bank(Request $request){
		$input_balance_bank = $request->get('input_balance_bank');
		$input_total_outstanding = $request->get('input_total_outstanding');
		$input_bala_transaction = $request->get('input_bala_transaction');
		$close_balance = $input_balance_bank+$input_total_outstanding;
		$diffence = $input_bala_transaction-$close_balance;
		echo json_encode(array('close_balance' => $close_balance, 'close_balance_span' => number_format_invoice($close_balance), 'diffence' => $diffence, 'diffence_span' => number_format_invoice($diffence)));
	}
	public function finance_bank_refresh(Request $request){
		$input_balance_bank = $request->get('input_balance_bank');
		$input_total_outstanding = $request->get('input_total_outstanding');
		$input_bala_transaction = $request->get('input_bala_transaction');
		$close_balance = (int)$input_balance_bank+(int)$input_total_outstanding;
		$diffence = (int)$input_bala_transaction-(int)$close_balance;
		echo json_encode(array('close_balance' => $close_balance, 'close_balance_span' => number_format_invoice($close_balance), 'diffence' => $diffence, 'diffence_span' => number_format_invoice($diffence), 'outstanding' => $input_total_outstanding, 'outstanding_span' => number_format_invoice($input_total_outstanding)));
	}
	public function finance_bank_all_accept(Request $request){
		$select_bank = base64_decode($request->get('select_bank'));	
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $select_bank)->first();
		if(($bank_details)){
			$date =\App\Models\userLogin::where('id',1)->first();
			if($bank_details->debit_balance != "")
            {
              $baln = number_format_invoice($bank_details->debit_balance);
              $op_baln = $bank_details->debit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            elseif($bank_details->credit_balance != "")
            {
              $baln = '-'.number_format_invoice($bank_details->credit_balance);
              $op_baln = $bank_details->credit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            else{
              $baln = '0.00';
              $op_baln = '0.00';
              $opening_balace_text = 'Opening Balance Not Set';
            }
			$output_opending='<tr>
				<td style="display:none">1</td>
				<td>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
				<td>'.$opening_balace_text.'</td>
				<td>Open Balance</td>
				<td align="right">'.number_format_invoice($baln).'</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		}
		$receipts_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$output_receipt=$output_opending;
		$i = 2;
		if(($receipts_details)){
			foreach ($receipts_details as $receipts){
				$color = 'blue';
				$outstanding = 0;
				$description = $receipts->credit_nominal.'-'.$receipts->credit_description;
				if($receipts->clearance_date == '0000-00-00'){
					$outstanding_color = 'orange';
				}
				else{
					$outstanding_color = '';
				}
				$process_journal = '';
				if(($receipts->journal_id == 0)) {
					$process_journal = 'process_journal';
				}
				$output_receipt.='
				<tr>
					<td style="display:none">'.$i.'</td>
					<td><spam style="display:none">'.strtotime($receipts->receipt_date).'</spam>'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
					<td>'.$description.'</td>
					<td>Receipts</td>
					<td align="right" id="receipt_amount_'.$receipts->id.'">'.number_format_invoice($receipts->amount).'</td>
					<td style="color:'.$color.'; text-align:right; font-weight:bold" id="receipt_out_'.$receipts->id.'">'.number_format_invoice($outstanding).'</td>
					<td class="journal_td" style="text-align:right">';
						if($receipts->journal_id != 0){
							$output_receipt.='<a href="javascript:" class="journal_id_viewer" data-element="'.$receipts->journal_id.'">'.$receipts->journal_id.'</a>';
						}
					$output_receipt.='</td>
					<td class="receipt_clear '.$process_journal.'" id="receipt_clear_'.$receipts->id.'" data-element="'.$receipts->id.'" style="color:'.$outstanding_color.'">'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
				</tr>';
				$data['clearance_date'] = $receipts->receipt_date;
				$data['hold_status'] = '1';
				\App\Models\receipts::where('id', $receipts->id)->update($data);
				$i++;
			}
		}
		else{
			$output_receipt=$output_opending;
		}
		$payment_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$output_payment=$output_receipt;
		if(($payment_details)){
			foreach ($payment_details as $payment) {				
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				$color = 'blue';				
				$outstanding = 0;
				if($payment->clearance_date == '0000-00-00'){
					$outstanding_color = 'orange';
				}
				else{
					$outstanding_color = '';
				}
				$process_journal = '';	
				if(($payment->journal_id == 0)) {
					$process_journal = 'process_journal';
				}
				$output_payment.='<tr>
				<td style="display:none">'.$i.'</td>
				<td><spam style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td>'.$description.'</td>
				<td>Payments</td>
				<td align="right" id="payment_amount_'.$payment->payments_id.'">'.number_format_invoice($payment->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="payment_out_'.$payment->payments_id.'">'.number_format_invoice($outstanding).'</td>
				<td class="journal_td" style="text-align:right">';
					if($payment->journal_id != 0){
						$output_payment.='<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
					}
				$output_payment.='</td>
				<td class="payment_clear '.$process_journal.'" id="payment_clear_'.$payment->payments_id.'" data-element="'.$payment->payments_id.'" style="color:'.$outstanding_color.'">'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
			</tr>';
			$data['clearance_date'] = $payment->payment_date;
			$data['hold_status'] = '1';
			\App\Models\payments::where('payments_id', $payment->payments_id)->update($data);
			$i++;
			}			
		}
		$total_outstanding = 0;
		echo json_encode(array('transactions' => $output_payment, 'total_outstanding' => number_format_invoice($total_outstanding)));
	}
	public function check_bank_nominal_code(Request $request)
	{
		$code = $request->get('nominal_code');
		$nominal = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('nominal_code',$code)->first();
		if(($nominal)) {
			echo $nominal->id;
		}
		else {
			echo 0;
		}
	}
	// public function create_journal_reconciliation()
	// {
	// 	$id = $request->get('id');
	// 	$type = $request->get('type');
	// 	$bank_id = base64_decode($request->get('bank_id'));
	// 	$bank_details = \App\Models\FinancialBanks::where('id',$bank_id)->first();
	// 	if($type == 1){
	// 		$details = \App\Models\receipts::where('id',$id)->first();
	// 		if($details->credit_nominal == "712") {
	// 			$exp_des = explode('-',$details->credit_description);
	// 			$journal_description = 'Client Payment from '.$details->client_code.' '.$exp_des[0];
	// 		} elseif($details->credit_nominal == "813A") {
	// 			$exp_des = explode('-',$details->credit_description);
	// 			$journal_description = 'Client Money Holding Account from '.$details->client_code.' '.$exp_des[0];
	// 		} else{
	// 			$journal_description = 'Received From '.$details->credit_nominal.' '.$details->credit_description.' '.$details->comment;
	// 		}
	// 		$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
	// 		$next_connecting_journal = ($count_total_journals) + 1;
	// 		$journal_id_val = $next_connecting_journal;
	// 		$dataval['journal_date'] = $details->receipt_date;
	// 		$dataval['description'] = $journal_description;
	// 		$dataval['reference'] = 'RCPT_'.$id;
	// 		$dataval['journal_source'] = 'RCPT';
	// 		$dataval['connecting_journal_reference'] = $next_connecting_journal;
	// 		$dataval['nominal_code'] = $details->credit_nominal;
	// 		$dataval['dr_value'] = '0.00';
	// 		$dataval['cr_value'] = $details->amount;
	// 		$dataval['practice_code'] = Session::get('user_practice_code');
	// 		\App\Models\Journals::insert($dataval);
	// 		$next_connecting_journal = $next_connecting_journal.'.01';
	// 		$dataval['journal_date'] = $details->receipt_date;
	// 		$dataval['description'] = $journal_description;
	// 		$dataval['reference'] = 'RCPT_'.$id;
	// 		$dataval['journal_source'] = 'RCPT';
	// 		$dataval['connecting_journal_reference'] = $next_connecting_journal;
	// 		$dataval['nominal_code'] = $bank_details->nominal_code;
	// 		$dataval['dr_value'] = $details->amount;
	// 		$dataval['cr_value'] = '0.00';
	// 		$dataval['practice_code'] = Session::get('user_practice_code');
	// 		\App\Models\Journals::insert($dataval);
	// 		$datarep['hold_status'] = 2;
	// 		$datarep['journal_id'] = $journal_id_val;
	// 		\App\Models\receipts::where('id',$id)->update($datarep);
	// 	}
	// 	else{
	// 		$details = \App\Models\payments::where('payments_id',$id)->first();
	// 		if($details->debit_nominal == "813") {
	// 			$exp_des = explode('-',$details->debit_description);
	// 			$supplier_details = \App\Models\suppliers::where('id',$details->supplier_code)->first();
	// 			$journal_description = 'Supplier Payment To '.$supplier_details->supplier_code.' '.$exp_des[0];
	// 		} elseif($details->debit_nominal == "813A") {
	// 			$exp_des = explode('-',$details->debit_description);
	// 			$journal_description = 'Client Money Holding Account to '.$details->client_code.' '.$exp_des[0];
	// 		} else{
	// 			$journal_description = 'Payment To '.$details->debit_nominal.' '.$details->debit_description;
	// 		}
	// 		$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
	// 		$next_connecting_journal = ($count_total_journals) + 1;
	// 		$journal_id_val = $next_connecting_journal;
	// 		$dataval['journal_date'] = $details->payment_date;
	// 		$dataval['description'] = $journal_description;
	// 		$dataval['reference'] = 'PAY_'.$id;
	// 		$dataval['journal_source'] = 'PAY';
	// 		$dataval['connecting_journal_reference'] = $next_connecting_journal;
	// 		$dataval['nominal_code'] = $details->debit_nominal;
	// 		$dataval['dr_value'] = $details->amount;
	// 		$dataval['cr_value'] = '0.00';
	// 		$dataval['practice_code'] = Session::get('user_practice_code');
	// 		\App\Models\Journals::insert($dataval);
	// 		$next_connecting_journal = $next_connecting_journal.'.01';
	// 		$dataval['journal_date'] = $details->payment_date;
	// 		$dataval['description'] = $journal_description;
	// 		$dataval['reference'] = 'PAY_'.$id;
	// 		$dataval['journal_source'] = 'PAY';
	// 		$dataval['connecting_journal_reference'] = $next_connecting_journal;
	// 		$dataval['nominal_code'] = $bank_details->nominal_code;
	// 		$dataval['dr_value'] = '0.00';
	// 		$dataval['cr_value'] = $details->amount;
	// 		$dataval['practice_code'] = Session::get('user_practice_code');
	// 		\App\Models\Journals::insert($dataval);
	// 		$datapay['hold_status'] = 2;
	// 		$datapay['journal_id'] = $journal_id_val;
	// 		\App\Models\payments::where('payments_id',$id)->update($datapay);
	// 	}
	// 	echo $journal_id_val;
	// }
	public function create_journal_reconciliation(Request $request)
	{
		$bank_id = base64_decode($request->get('bank_id'));
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id',$bank_id)->first();
		$receipts_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal',$bank_details->nominal_code)->where('imported',0)->where('journal_id',0)->where('clearance_date','!=','0000-00-00')->offset(0)->limit(1000)->get();
		$limitval = 0;
		if(($receipts_details)){
			foreach ($receipts_details as $receipts) {
				if($receipts->credit_nominal == "712") {
					$exp_des = explode('-',$receipts->credit_description);
					$journal_description = 'Client Payment from '.$receipts->client_code.' '.$exp_des[0];
				} elseif($receipts->credit_nominal == "813A") {
					$exp_des = explode('-',$receipts->credit_description);
					$journal_description = 'Client Money Holding Account from '.$receipts->client_code.' '.$exp_des[0];
				} else{
					$journal_description = 'Received From '.$receipts->credit_nominal.' '.$receipts->credit_description.' '.$receipts->comment;
				}
				$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
				$next_connecting_journal = count($count_total_journals) + 1;
				$journal_id_val = $next_connecting_journal;
				$dataval['journal_date'] = $receipts->receipt_date;
				$dataval['description'] = $journal_description;
				$dataval['reference'] = 'RCPT_'.$receipts->id;
				$dataval['journal_source'] = 'RCPT';
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = $receipts->credit_nominal;
				$dataval['dr_value'] = '0.00';
				$dataval['cr_value'] = $receipts->amount;
				$dataval['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($dataval);
				$next_connecting_journal = $next_connecting_journal.'.01';
				$dataval['journal_date'] = $receipts->receipt_date;
				$dataval['description'] = $journal_description;
				$dataval['reference'] = 'RCPT_'.$receipts->id;
				$dataval['journal_source'] = 'RCPT';
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = $bank_details->nominal_code;
				$dataval['dr_value'] = $receipts->amount;
				$dataval['cr_value'] = '0.00';
				$dataval['practice_code'] = Session::get('user_practice_code');
				\App\Models\Journals::insert($dataval);
				$datarep['hold_status'] = 2;
				$datarep['journal_id'] = $journal_id_val;
				\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipts->id)->update($datarep);
				$limitval++;
			}
		}
		if($limitval < 1000){
			$limitcount = 1000 - $limitval;
			$payment_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal',$bank_details->nominal_code)->where('imported',0)->where('journal_id',0)->where('clearance_date','!=','0000-00-00')->offset(0)->limit($limitcount)->get();
			if(($payment_details)){
				foreach ($payment_details as $payment) {
					if($payment->debit_nominal == "813") {
						$exp_des = explode('-',$payment->debit_description);
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$journal_description = 'Supplier Payment To '.$supplier_details->supplier_code.' '.$exp_des[0];
					} elseif($payment->debit_nominal == "813A") {
						$exp_des = explode('-',$payment->debit_description);
						$journal_description = 'Client Money Holding Account to '.$payment->client_code.' '.$exp_des[0];
					} else{
						$journal_description = 'Payment To '.$payment->debit_nominal.' '.$payment->debit_description;
					}
					$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
					$next_connecting_journal = count($count_total_journals) + 1;
					$journal_id_val = $next_connecting_journal;
					$dataval['journal_date'] = $payment->payment_date;
					$dataval['description'] = $journal_description;
					$dataval['reference'] = 'PAY_'.$payment->payments_id;
					$dataval['journal_source'] = 'PAY';
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = $payment->debit_nominal;
					$dataval['dr_value'] = $payment->amount;
					$dataval['cr_value'] = '0.00';
					$dataval['practice_code'] = Session::get('user_practice_code');
					\App\Models\Journals::insert($dataval);
					$next_connecting_journal = $next_connecting_journal.'.01';
					$dataval['journal_date'] = $payment->payment_date;
					$dataval['description'] = $journal_description;
					$dataval['reference'] = 'PAY_'.$payment->payments_id;
					$dataval['journal_source'] = 'PAY';
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $payment->amount;
					$dataval['practice_code'] = Session::get('user_practice_code');
					\App\Models\Journals::insert($dataval);
					$datapay['hold_status'] = 2;
					$datapay['journal_id'] = $journal_id_val;
					\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment->payments_id)->update($datapay);
				}
			}
		}
	}
	public function generate_reconcile_pdf(Request $request) {
		$bank_id = $request->get('bank_id');
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $bank_id)->first();
		$receipt_ids = explode(',',$request->get('receipt_id'));
		$payment_ids = explode(',',$request->get('payment_id'));
		$receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->whereIn('id',$receipt_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->whereIn('payments_id',$payment_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$receipts_total_amount = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		$payments_total_amount = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		// $receipts_total_amount = \App\Models\receipts::whereIn('id',$receipt_ids)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		// $payments_total_amount = \App\Models\payments::whereIn('payments_id',$payment_ids)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		$receipts_payment_html = '';
		$receipt_total = '0.00';
		$payment_total = '0.00';
		if(($receipts)) {
			foreach($receipts as $receipt){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->credit_nominal)->first();
				$description = $receipt->credit_nominal.'-'.$receipt->credit_description;
				if(($code_description)){
					$description = $receipt->credit_nominal.'-'.$code_description->description;
				}
				$outstanding = $receipt->amount;
				if($receipt_total == ''){
					$receipt_total = $receipt->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipt->amount;
				}
				$receipts_payment_html.='<tr>
				<td>'.date('d-M-Y', strtotime($receipt->receipt_date)).'</td>
				<td>'.$description.'</td>
				<td>Receipts</td>
				<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
				<td style="text-align:right">'.number_format_invoice($outstanding).'</td>
				</tr>';
			}
		}
		if(($payments)) {
			foreach($payments as $payment){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->debit_nominal)->first();
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				if(($code_description)){
					$description = $payment->debit_nominal.'-'.$code_description->description;
				}
				$outstanding = $payment->amount;
				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}
				$receipts_payment_html.='<tr>
				<td>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td>'.$description.'</td>
				<td>Payments</td>
				<td style="text-align:right">'.number_format_invoice($payment->amount).'</td>
				<td style="text-align:right">'.number_format_invoice($outstanding).'</td>
				</tr>';
			}
		}
		if($receipts_payment_html == ''){
			$receipts_payment_html.='<tr>
				<td>No Outstanding Items Found</td>
			<tr>';
		}
		if($bank_details->debit_balance != "")
        {
          $baln = number_format_invoice($bank_details->debit_balance);
          $op_baln = $bank_details->debit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        elseif($bank_details->credit_balance != "")
        {
          $baln = '-'.number_format_invoice($bank_details->credit_balance);
          $op_baln = '-'.$bank_details->credit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        else{
          $baln = '0.00';
          $op_baln = '0.00';
          $opening_balace_text = 'Opening Balance Not Set';
        }
		$balance_transaction = ($op_baln+$receipts_total_amount)-$payments_total_amount;
		$output_reconcilation='
		<style>
		.table_style1 {
		    width: 100%;
		    border-collapse:collapse;
		    border:1px solid #c5c5c5;
		    margin-bottom:20px;
		}
		.table_style1 tr th,.table_style1 tr td {
		    border:1px solid #c5c5c5;
		}
		.table_style2 {
		    width: 70%;
		    border-collapse:collapse;
		    margin-bottom:20px;
		}
		.table_style2 tr th,.table_style2 tr td {
		    border-bottom:1px solid #c5c5c5;
		    padding:10px;
		}
		.table_style3 {
		    width: 100%;
		    border-collapse:collapse;
		}
		.table_style3 tr th,.table_style3 tr td {
		    border-bottom:1px solid #c5c5c5;
		    padding:5px;
		}
		body{
			font-size:14px;
		}
		</style>
		<h3 style="text-align:center">'.$bank_details->description.'</h3>
		<table class="table_style1">
	        <thead>
	          <tr>
	            <th>Bank Name</th>
	            <th>Account Name</th>
	            <th>Account Number</th>
	            <th>Description</th>
	            <th>Nominal Code</th>
	          </tr>
	        </thead>
	        <tbody>
	          <tr>
	            <td>'.$bank_details->bank_name.'</td>
	            <td>'.$bank_details->account_name.'</td>
	            <td>'.$bank_details->account_number.'</td>
	            <td>'.$bank_details->description.'</td>
	            <td>'.$bank_details->nominal_code.'</td>
	          </tr>
	        </tbody>
	    </table>
	    <h3>RECONCILATION SECTION:</h3>
	    <table class="table_style2">
			<tr>
		        <td style="width:70%">Opening Balance:</td>
		        <td style="width:20%" class="op_reconcile" align="right" style="width: 150px;">'.$baln.'</td>
		        <td style="width:10%"></td>
		    </tr>
		    <tr>
		        <td>Total Value of Receipts:</td>
		        <td class="tvr_reconcile" align="right">'.number_format_invoice($receipts_total_amount).'</td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Total Value of Payments:</td>
		        <td class="tvp_reconcile" align="right" style="border-bottom:2px solid #000;">'.number_format_invoice($payments_total_amount).'</td>
		        <td></td>
			</tr>
			<tr>
		      	<td>Balance Per Processed Transactions:</td>		      	
		      	<td class="bpp_reconcile" align="right">'.number_format_invoice($balance_transaction).'
		      	</td>
		      	<td></td>
			</tr>
			<tr>
		        <td>Balance Per Bank Statement:</td>
		        <td align="right">'.number_format_invoice($request->get('input')).'</td>
		        <td align="right">'.$request->get('date').'</td>
		    </tr>
		    <tr>
		        <td>Total Value of Outstanding Items:</td>
		        <td align="right"><span class="class_total_outstanding_refresh">'.number_format_invoice($receipt_total + $payment_total).'
		        </span>
		        </td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Reconciled Bank Statement Closing Balance:</td>
		        <td align="right">
		        <span class="class_close_balance">'.$request->get('cb').'</span>
		        </td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Difference:</td>
		        <td align="right" style="border-top:2px solid #000;">
		        <span class="class_difference">'.$request->get('cd').'</span>
		        </td>
		        <td></td>
		    </tr>
		</table>
		<h3>Outstanding Transactions:</h3>
		<table class="table_style3">
			<tr>
				<th style="text-align:left">Date</th>
				<th style="text-align:left">Description</th>
				<th style="text-align:left">Source</th>
				<th style="text-align:right">Value</th>
				<th style="text-align:right">Outstanding</th>
			</tr>
			'.$receipts_payment_html.'
		</table>';
		$pdf = PDF::loadHTML($output_reconcilation);
		$pdf->setPaper('A4', 'portrait');
		$file = 'Reconciliation for - '.$bank_details->bank_name.'_'.time().'.pdf';
		$pdf->save('public/papers/'.$file.'');
		echo $file;
	}
	public function generate_reconcile_csv(Request $request) {
		$bank_id = $request->get('bank_id');
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $bank_id)->first();
		$receipt_ids = explode(',',$request->get('receipt_id'));
		$payment_ids = explode(',',$request->get('payment_id'));
		$filename = 'Reconciliation for - '.$bank_details->bank_name.'_'.time().'.csv';
		$file = fopen('public/papers/'.$filename.'', 'w');
		$receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->whereIn('id',$receipt_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->whereIn('payments_id',$payment_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$receipts_total_amount = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		$payments_total_amount = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		// $receipts_total_amount = \App\Models\receipts::whereIn('id',$receipt_ids)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		// $payments_total_amount = \App\Models\payments::whereIn('payments_id',$payment_ids)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		$receipts_payment_html = array();
		$receipt_total = '0.00';
		$payment_total = '0.00';
		if(($receipts)) {
			foreach($receipts as $receipt){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->credit_nominal)->first();
				$description = $receipt->credit_nominal.'-'.$receipt->credit_description;
				if(($code_description)){
					$description = $receipt->credit_nominal.'-'.$code_description->description;
				}
				$outstanding = $receipt->amount;
				if($receipt_total == ''){
					$receipt_total = $receipt->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipt->amount;
				}
				$column_arr = array(date('d-M-Y', strtotime($receipt->receipt_date)),$description,'Receipts',number_format_invoice($receipt->amount),number_format_invoice($outstanding));
				array_push($receipts_payment_html,$column_arr);
			}
		}
		if(($payments)) {
			foreach($payments as $payment){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->debit_nominal)->first();
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				if(($code_description)){
					$description = $payment->debit_nominal.'-'.$code_description->description;
				}
				$outstanding = $payment->amount;
				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}
				$column_arr = array(date('d-M-Y', strtotime($payment->payment_date)),$description,'Payments',number_format_invoice($payment->amount),number_format_invoice($outstanding));
				array_push($receipts_payment_html,$column_arr);
			}
		}
		if(!($receipts_payment_html)){
			$column_arr = array('No Outstanding Items Found','','','','');
			array_push($receipts_payment_html,$column_arr);
		}
		if($bank_details->debit_balance != "")
        {
          $baln = number_format_invoice($bank_details->debit_balance);
          $op_baln = $bank_details->debit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        elseif($bank_details->credit_balance != "")
        {
          $baln = '-'.number_format_invoice($bank_details->credit_balance);
          $op_baln = '-'.$bank_details->credit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        else{
          $baln = '0.00';
          $op_baln = '0.00';
          $opening_balace_text = 'Opening Balance Not Set';
        }
		$balance_transaction = ($op_baln+$receipts_total_amount)-$payments_total_amount;
		$columns = array('','',$bank_details->description,'','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Bank Name','Account Name','Account Number','Description','Nominal Code');
		fputcsv($file, $columns);
		$columns = array($bank_details->bank_name,$bank_details->account_name,$bank_details->account_number,$bank_details->description,$bank_details->nominal_code);
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('RECONCILATION SECTION:','','','','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Opening Balance:','',$baln,'','');
		fputcsv($file, $columns);
		$columns = array('Total Value of Receipts:','',number_format_invoice($receipts_total_amount),'','');
		fputcsv($file, $columns);
		$columns = array('Total Value of Payments:','',number_format_invoice($payments_total_amount),'','');
		fputcsv($file, $columns);
		$columns = array('Balance Per Processed Transactions:','',number_format_invoice($balance_transaction),'','');
		fputcsv($file, $columns);
		$columns = array('Balance Per Bank Statement:','',number_format_invoice($request->get('input')),'',$request->get('date'));
		fputcsv($file, $columns);
		$columns = array('Total Value of Outstanding Items:','',number_format_invoice($receipt_total + $payment_total),'','');
		fputcsv($file, $columns);
		$columns = array('Reconciled Bank Statement Closing Balance:','',$request->get('cb'),'','');
		fputcsv($file, $columns);
		$columns = array('Difference:','',$request->get('cd'),'','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Outstanding Transactions:','','','','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Date','Description','Source','Value','Outstanding');
		fputcsv($file, $columns);
		if(($receipts_payment_html))
		{
			foreach($receipts_payment_html as $receipt_payment)
			{
				fputcsv($file, $receipt_payment);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function generate_reconcile_csv_after_reconciliation(Request $request) {
		$bank_id = $request->get('bank_id');
		$bank_details = \App\Models\FinancialBanks::where('practice_code',Session::get('user_practice_code'))->where('id', $bank_id)->first();
		$receipt_ids = explode(',',$request->get('receipt_id'));
		$payment_ids = explode(',',$request->get('payment_id'));
		$time = time();
		$upload_dir = 'uploads/bank_reconcile';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$filename = 'Reconciliation for - '.$bank_details->bank_name.'_'.$time.'.csv';
		$file = fopen($upload_dir.'/'.$filename.'', 'w');
		$receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->whereIn('id',$receipt_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->whereIn('payments_id',$payment_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$receipts_total_amount = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		$payments_total_amount = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		// $receipts_total_amount = \App\Models\receipts::whereIn('id',$receipt_ids)->where('imported',0)->orderBy('receipt_date', 'asc')->sum('amount');
		// $payments_total_amount = \App\Models\payments::whereIn('payments_id',$payment_ids)->where('imported',0)->orderBy('payment_date', 'asc')->sum('amount');
		$receipts_payment_html = array();
		$receipt_total = '0.00';
		$payment_total = '0.00';
		if(($receipts)) {
			foreach($receipts as $receipt){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->credit_nominal)->first();
				$description = $receipt->credit_nominal.'-'.$receipt->credit_description;
				if(($code_description)){
					$description = $receipt->credit_nominal.'-'.$code_description->description;
				}
				$outstanding = $receipt->amount;
				if($receipt_total == ''){
					$receipt_total = $receipt->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipt->amount;
				}
				$column_arr = array(date('d-M-Y', strtotime($receipt->receipt_date)),$description,'Receipts',number_format_invoice($receipt->amount),number_format_invoice($outstanding));
				array_push($receipts_payment_html,$column_arr);
			}
		}
		if(($payments)) {
			foreach($payments as $payment){
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->debit_nominal)->first();
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				if(($code_description)){
					$description = $payment->debit_nominal.'-'.$code_description->description;
				}
				$outstanding = $payment->amount;
				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}
				$column_arr = array(date('d-M-Y', strtotime($payment->payment_date)),$description,'Payments',number_format_invoice($payment->amount),number_format_invoice($outstanding));
				array_push($receipts_payment_html,$column_arr);
			}
		}
		if(!($receipts_payment_html)){
			$column_arr = array('No Outstanding Items Found','','','','');
			array_push($receipts_payment_html,$column_arr);
		}
		//$balance_transaction = ($bank_details->debit_balance+$receipt_total)-$payment_total;
		if($bank_details->debit_balance != "")
        {
          $baln = number_format_invoice($bank_details->debit_balance);
          $op_baln = $bank_details->debit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        elseif($bank_details->credit_balance != "")
        {
          $baln = '-'.number_format_invoice($bank_details->credit_balance);
          $op_baln = '-'.$bank_details->credit_balance;
          $opening_balace_text = 'Opening Balance';
        }
        else{
          $baln = '0.00';
          $op_baln = '0.00';
          $opening_balace_text = 'Opening Balance Not Set';
        }
		$balance_transaction = ($op_baln+$receipts_total_amount)-$payments_total_amount;
		$columns = array('','',$bank_details->description,'','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Bank Name','Account Name','Account Number','Description','Nominal Code');
		fputcsv($file, $columns);
		$columns = array($bank_details->bank_name,$bank_details->account_name,$bank_details->account_number,$bank_details->description,$bank_details->nominal_code);
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('RECONCILATION SECTION:','','','','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Opening Balance:','',$baln,'','');
		fputcsv($file, $columns);
		$columns = array('Total Value of Receipts:','',number_format_invoice($receipts_total_amount),'','');
		fputcsv($file, $columns);
		$columns = array('Total Value of Payments:','',number_format_invoice($payments_total_amount),'','');
		fputcsv($file, $columns);
		$columns = array('Balance Per Processed Transactions:','',number_format_invoice($balance_transaction),'','');
		fputcsv($file, $columns);
		$columns = array('Balance Per Bank Statement:','',number_format_invoice($request->get('stmt_bal')),'',$request->get('stmt_date'));
		fputcsv($file, $columns);
		$columns = array('Total Value of Outstanding Items:','',number_format_invoice($receipt_total + $payment_total),'','');
		fputcsv($file, $columns);
		$columns = array('Reconciled Bank Statement Closing Balance:','',$request->get('cb'),'','');
		fputcsv($file, $columns);
		$columns = array('Difference:','',$request->get('cd'),'','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Outstanding Transactions:','','','','');
		fputcsv($file, $columns);
		$columns = array('','','','','');
		fputcsv($file, $columns);
		$columns = array('Date','Description','Source','Value','Outstanding');
		fputcsv($file, $columns);
		if(($receipts_payment_html))
		{
			foreach($receipts_payment_html as $receipt_payment)
			{
				fputcsv($file, $receipt_payment);
			}
		}
		fclose($file);
		$output_opending = '';
		if(($bank_details)){
			$date =\App\Models\userLogin::where('id',1)->first();
			if($bank_details->debit_balance != "")
            {
              $baln = number_format_invoice($bank_details->debit_balance);
              $op_baln = $bank_details->debit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            elseif($bank_details->credit_balance != "")
            {
              $baln = '-'.number_format_invoice($bank_details->credit_balance);
              $op_baln = '-'.$bank_details->credit_balance;
              $opening_balace_text = 'Opening Balance';
            }
            else{
              $baln = '0.00';
              $op_baln = '0.00';
              $opening_balace_text = 'Opening Balance Not Set';
            }
			$output_opending='<tr>
				<td style="display:none">1</td>
				<td><spam style="display:none">'.strtotime($date->opening_balance_date).'</spam>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
				<td>'.$opening_balace_text.'</td>
				<td>Open Balance</td>
				<td align="right">'.$baln.'</td>
				<td></td>
				<td align="right"><a href="javascript:" class="journal_id_viewer" data-element="'.$bank_details->journal_id.'">'.$bank_details->journal_id.'</td>
				<td></td>
			</tr>';
		}
		$receipts_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$i=2;
		$output_receipt = $output_opending;
		if(($receipts_details)){
			foreach ($receipts_details as $receipts) {				
				$colortext = '#000';
				if(substr($receipts->credit_nominal,0,3) == "771")
				{
					$colortext = '#f00';
				}
				if($receipts->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" style="width:100%; float:left; text-align:center" class="single_accept" type="1" data-element="'.$receipts->id.'">-</a>';					
					if($receipts->amount < 0){
						$color = 'red';
						$outstanding = $receipts->amount;
					}
					else{
						$color = 'green';
						$outstanding = $receipts->amount;
					}
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($receipts->clearance_date));
					$color = 'blue';
					$outstanding = 0;
				}
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipts->credit_nominal)->first();
				$description = $receipts->credit_nominal.'-'.$receipts->credit_description;
				if(($code_description)){
					$description = $receipts->credit_nominal.'-'.$code_description->description;
				}
				$process_journal ='';
				if(($receipts->journal_id == 0) && ($receipts->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}
				$output_receipt.='<tr>
				<td style="display:none">'.$i.'</td>
				<td style="color:'.$colortext.'"><spam style="display:none">'.strtotime($receipts->receipt_date).'</spam>'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
				<td style="color:'.$colortext.'">'.$description.'</td>
				<td style="color:'.$colortext.'"><a href="javascript:" class="receipt_viewer_class" data-element="'.$receipts->id.'">Receipts</a></td>
				<td style="color:'.$colortext.'" align="right" id="receipt_amount_'.$receipts->id.'">'.number_format_invoice($receipts->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="receipt_out_'.$receipts->id.'">'.number_format_invoice($outstanding).'</td>
				<td style="color:'.$colortext.';text-align:right" class="journal_td">';
					if($receipts->journal_id != 0){
						$output_receipt.='<a href="javascript:" class="journal_id_viewer" data-element="'.$receipts->journal_id.'">'.$receipts->journal_id.'</a>';
					}
				$output_receipt.='</td>
				<td style="color:'.$colortext.'" class="receipt_clear '.$process_journal.'" id="receipt_clear_'.$receipts->id.'" data-element="'.$receipts->id.'">'.$clearance_date.'</td>
			</tr>';
			$i++;
			}			
		}
		$payment_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$j=$i;
		$output_payment=$output_receipt;
		if(($payment_details)){
			foreach ($payment_details as $payment) {
				$colortext = '#000';
				if(substr($payment->debit_nominal,0,3) == "771")
				{
					$colortext = '#f00';
				}
				if($payment->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" class="single_accept" type="2" data-element="'.$payment->payments_id.'" style="width:100%; float:left; text-align:center">-</a>';					
					if($payment->amount < 0){
						$color = 'red';
						$outstanding = $payment->amount;
					}
					else{
						$color = 'green';
						$outstanding = $payment->amount;
					}
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($payment->clearance_date));
					$color = 'blue';
					$outstanding = 0;
				}
				$code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->debit_nominal)->first();
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				if(($code_description)){
					$description = $payment->debit_nominal.'-'.$code_description->description;
				}
				$process_journal = '';
				if(($payment->journal_id == 0) && ($payment->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}
				$output_payment.='<tr>
				<td style="display:none">'.$j.'</td>
				<td style="color:'.$colortext.'"><spam style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td style="color:'.$colortext.'">'.$description.'</td>
				<td style="color:'.$colortext.'"><a href="javascript:" class="payment_viewer_class" data-element="'.$payment->payments_id.'">Payments</a></td>
				<td style="color:'.$colortext.'" align="right" id="payment_amount_'.$payment->payments_id.'">'.number_format_invoice($payment->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="payment_out_'.$payment->payments_id.'">'.number_format_invoice($outstanding).'</td>
				<td style="color:'.$colortext.';text-align:right" class="journal_td">';
					if($payment->journal_id != 0){
						$output_payment.='<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
					}
				$output_payment.='</td>
				<td style="color:'.$colortext.'" class="payment_clear '.$process_journal.'" id="payment_clear_'.$payment->payments_id.'" data-element="'.$payment->payments_id.'">'.$clearance_date.'</td>
			</tr>';
			$j++;
			}			
		}
		if($request->get('stmt_date') != "")
		{
			$exp_date = explode('/',$request->get('stmt_date'));
			$stmt_date = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
			$out_stmt_date = date('d-M-Y', strtotime($stmt_date));
		}
		else{
			$stmt_date = '0000-00-00';
			$out_stmt_date = '-';
		}
		$insert_reconcile['bank_id'] = $bank_id;
		$insert_reconcile['rec_date'] = date('Y-m-d');
		$insert_reconcile['stmt_bal'] = $request->get('stmt_bal');
		$insert_reconcile['stmt_date'] = $stmt_date;
		$insert_reconcile['total_os_items'] = $receipt_total + $payment_total;
		$insert_reconcile['rec_attached_dir'] = $upload_dir;
		$insert_reconcile['rec_attached_file'] = $filename;
		\App\Models\reconciliations::insert($insert_reconcile);
		$reconcile_output= '<tr>
					<td>'.date('d-M-Y', strtotime($insert_reconcile['rec_date'])).'</td>
					<td style="text-align:right">'.number_format_invoice_empty($insert_reconcile['stmt_bal']).'</td>
					<td>'.$out_stmt_date.'</td>
					<td>'.$insert_reconcile['total_os_items'].'</td>
					<td><a href="'.URL::to($upload_dir.'/'.$filename).'" download>'.$filename.'</a></td>
				</tr>';
		echo json_encode(array("output_payment" => $output_payment, "output" => $reconcile_output, "rec_date" => date('d-M-Y', strtotime($insert_reconcile['rec_date'])),'stmt_date' => date('d-M-Y', strtotime($stmt_date)), 'stmt_bal' => number_format_invoice($insert_reconcile['stmt_bal'])));
	}
	public function save_general_journals(Request $request){
		$nominal_date_exp = explode('/',$request->get('nominal_date'));
		$nominal_date = $nominal_date_exp[2].'-'.$nominal_date_exp[1].'-'.$nominal_date_exp[0];
		//$nominal_codes = $request->get('nominal_codes');
		// $journal_desription = unserialize($request->get('journal_desription'));
		// $debitvalues = unserialize($request->get('debitvalues'));
		// $creditvalues = unserialize($request->get('creditvalues'));
		parse_str($_POST['nominal_codes'], $nominal_codes_serialize);
		parse_str($_POST['journal_desription'], $journal_desription_serialize);
		parse_str($_POST['debitvalues'], $debitvalues_serialize);
		parse_str($_POST['creditvalues'], $creditvalues_serialize);
		$nominal_codes = $nominal_codes_serialize['general_nominal'];
		$journal_desription = $journal_desription_serialize['general_journal_desription'];
		$debitvalues = $debitvalues_serialize['general_debit'];
		$creditvalues = $creditvalues_serialize['general_credit'];
		$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
		$next_connecting_journal = count($count_total_journals) + 1;
		$reference = 'General_Journal_'.time().'_'.$next_connecting_journal;
		$dataval['journal_date'] = $nominal_date;
		$dataval['reference'] = $reference;
		$dataval['journal_source'] = 'GJ';
		$dataval['practice_code'] = Session::get('user_practice_code');
		if(($nominal_codes))
		{
			foreach($nominal_codes as $key => $code){
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = $code;
				$dataval['description'] = $journal_desription[$key];
				$dataval['dr_value'] = $debitvalues[$key];
				$dataval['cr_value'] = $creditvalues[$key];
				\App\Models\Journals::insert($dataval);
				$next_connecting_journal = $next_connecting_journal + '.01';
			}
		}
	}
	public function finance_load_details_analysis(Request $request){
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();	
		$output='';
		$date =\App\Models\userLogin::where('id',1)->first();
		if(($clientlist)){
			foreach ($clientlist as $client) {
				$finance_client = \App\Models\FinanceClients::where('client_id','like',$client->client_id.'%')->first();
				$balance=0;
				if(($finance_client)){
					$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
					$owed_text='Opening Balance';
					if($balance != "0.00" && $balance != "" && $balance != "0")
					{
						if($finance_client->balance >= 0){
							$finance_client->owed_text = 'Client Owes Back';
						}
						else{
							$owed_text = 'Client Is Owed';
						}
					}
					if($finance_client->balance > 0){
						$openening_debit = '€ '.number_format_invoice($finance_client->balance);
						$openening_credit=0;							
					}
					elseif($finance_client->balance == '' || $finance_client->balance == 0){
						$openening_debit = 0;
						$openening_credit = '€ '.number_format_invoice($finance_client->balance);
					}
					else{							
						$openening_debit = 0;
						$openening_credit = '€ '.number_format_invoice($finance_client->balance * -1);
					}
					$client_opening = '<tr>
								<td>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
								<td>Opening Balance</td>
								<td>'.$owed_text.'</td>								
								<td align="right">'.$openening_debit.'</td>
								<td align="right">'.$openening_credit.'</td>
								<td align="right">€ '.number_format_invoice($finance_client->balance).'</td>
							</tr>';
				}
				else{
					$balance=0;
					$client_opening = '';
				}
				$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
				$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
				$get_receipt_payments=array_merge($get_receipts,$get_payments);
				$dateval = array();
				foreach ($get_receipt_payments as $key => $row)
				{
				    $dateval[$key] = $row->dateval;
				}
				array_multisort($dateval, SORT_ASC, $get_receipt_payments);
				$balance_val = $balance;
				if(($finance_client) && !count($get_receipt_payments)) {
					if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
					{
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $finance_client->client_id)->first();
						if($client_details->company == ''){
							$companyname = $client_details->surname.' '.$client_details->firstname;
						}
						else{
							$companyname = $client_details->company;
						} 
						$output.='<table class="table own_table_white">
						<thead><tr>
								<th>'.$client_details->client_id.'</th>
								<th colspan="2">'.$companyname.'</th>							
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr>
								<th style="width:120px;">Date</th>
								<th style="width:230px;">Source</th>
								<th>Description</th>
								<th style="width:120px; text-align:right">Debit €</th>
								<th style="width:120px; text-align:right">Creedit €</th>
								<th style="width:120px; text-align:right">Balance</th>
							</tr>
							</thead><tbody>'.$client_opening.'</tbody></table>';
					}
				}
				if(($get_receipt_payments))
				{
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $get_receipt_payments[0]->client_code)->first();
					if($client_details->company == ''){
						$companyname = $client_details->surname.' '.$client_details->firstname;
					}
					else{
						$companyname = $client_details->company;
					}
					$output.='<table class="table own_table_white">
						<thead><tr>
								<th>'.$get_receipt_payments[0]->client_code.'</th>
								<th>'.$companyname.'</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr>
								<th style="width:120px;">Date</th>
								<th style="width:230px;">Source</th>
								<th>Description</th>
								<th style="width:120px; text-align:right">Debit €</th>
								<th style="width:120px; text-align:right">Creedit €</th>
								<th style="width:120px; text-align:right">Balance</th>
							</tr>
							</thead><tbody>'.$client_opening;
					foreach($get_receipt_payments as $list)
					{
						if(isset($list->payments_id)) { 
							$source = 'Payments';
							$amount_credit = '';
							$amount_debit = '€ '.number_format_invoice($list->amount);
							$textvalue = 'Payment Made Back to Client';
							$amount = number_format_invoice($list->amount);
							$amt = $list->amount;
							$balance_val = ($balance_val + ($list->amount));
							$class = 'payment_viewer_class';
							$id = $list->payments_id;
						}
						else { 
							$source = 'Receipts'; 
							$amount_credit = '€ '.number_format_invoice($list->amount);
							$amount_debit = '';
							if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
							{								
								$amount = number_format_invoice($list->amount * -1);
								$amt = ($list->amount * -1);
								$textvalue = 'Client Money Received';
								$balance_val = $balance_val + ($list->amount * -1);
							}
							else{								
								$amount = number_format_invoice($list->amount);
								$amt = $list->amount;
								$textvalue = '';
								$balance_val = $balance_val + $list->amount;
							}
							$id = $list->id;
							$class = 'receipt_viewer_class';
						}						
						$output.='<tr>
							<td>'.date('d-M-Y', $list->dateval).'</td>
							<td><a href="javascript:" class="'.$class.'" data-element="'.$id.'">'.$source.'</a></td>
							<td>'.$textvalue.'</td>
							<td align="right">'.$amount_debit.'</td>
							<td align="right">'.$amount_credit.'</td>
							<td align="right">'.number_format_invoice($balance_val).'</td>
						</tr>';
					}
					$output.='</tbody></table>';
				}
			}
		}
		echo json_encode(array('output' => $output));
	}
	public function finance_analysis_report(Request $request){
		$type = $request->get('type');
		$format = $request->get('format');
		$clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		if($format == 3){
			// $font_family = "font-family: font-family: 'Roboto', sans-serif;";
			$font_family = '';
			if($type == 1){
				$output='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; width: 100%;">
						<tr>
							<td style="width:100px;padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;background:#000; color:#fff;'.$font_family.'">Client Code</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;background:#000; color:#fff;'.$font_family.'">Surname</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left; background:#000; color:#fff;'.$font_family.'">Firstname</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Company Name</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Opening Balance</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Client Money Received</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Payments Made</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</td>
						</tr>';
						if(($clientlist)){
							foreach ($clientlist as $client) {
								$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
								$opening_bal = '0.0';
								if(($finance_client))
								{
									if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
									{
										$opening_bal = $finance_client->debit;
									}
									if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
									{
										$opening_bal = '-'.$finance_client->credit;
									}
								}
								$client_receipt = \App\Models\receipts::where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
								$client_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
								$sumval = $opening_bal + ($client_receipt * -1);
								$sumval = $sumval + $client_payment;
								$output.='<tr>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->client_id.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->surname.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->firstname.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->company.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($opening_bal).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($client_receipt * -1).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($client_payment).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($sumval).'</td>
								</tr>';
							}					
						}
					$output.='</tbody>
				</table>';
				$time=time();
				$pdf = PDF::loadHTML($output);
			    $pdf->setPaper('A4', 'landscape');
			    $pdf->save('public/papers/Client Finance Account_'.$time.'.pdf');
			    $filename_download = 'Client Finance Account_'.$time.'.pdf';
			    echo $filename_download;
			}
			else{
				$output='';
				$date =\App\Models\userLogin::where('id',1)->first();
				if(($clientlist)){
					foreach ($clientlist as $client) {
						$finance_client = \App\Models\FinanceClients::where('client_id','like',$client->client_id.'%')->first();
						$balance='';
						if(($finance_client)){
							$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
							$owed_text='Opening Balance';
							if($balance != "0.00" && $balance != "" && $balance != "0")
							{
								if($finance_client->balance >= 0){
									$finance_client->owed_text = 'Client Owes Back';
								}
								else{
									$owed_text = 'Client Is Owed';
								}
							}
							if($finance_client->balance > 0){
								$openening_debit = '€ '.number_format_invoice($finance_client->balance);
								$openening_credit='';							
							}
							elseif($finance_client->balance == '' || $finance_client->balance == 0){
								$openening_debit = '';
								$openening_credit = '€ '.number_format_invoice($finance_client->balance);
							}
							else{							
								$openening_debit = '';
								$openening_credit = '€ '.number_format_invoice($finance_client->balance * -1);
							}
							$client_opening = '<tr>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">Opening Balance</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$owed_text.'</td>								
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$openening_debit.'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$openening_credit.'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">€ '.number_format_invoice($finance_client->balance).'</td>
									</tr>';
						}
						else{
							$balance='';
							$client_opening = '';
						}
						$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_receipt_payments=array_merge($get_receipts,$get_payments);
						$dateval = array();
						foreach ($get_receipt_payments as $key => $row)
						{
						    $dateval[$key] = $row->dateval;
						}
						array_multisort($dateval, SORT_ASC, $get_receipt_payments);
						$balance_val = $balance;
						if(($finance_client) && !count($get_receipt_payments)) {
							if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
							{
								$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $finance_client->client_id)->first();
								if($client_details->company == ''){
									$companyname = $client_details->surname.' '.$client_details->firstname;
								}
								else{
									$companyname = $client_details->company;
								}
								$output.='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; line-height: 20px; width: 100%; margin: 0px auto;page-break-after: always; ">
								<thead><tr>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client_details->client_id.'</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'" colspan="2">'.$companyname.'</th>							
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
									</tr>
									<tr>
										<th style="width:120px; padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left; background:#000; color:#fff;'.$font_family.'">Date</th>
										<th style="width:230px; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Source</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Description</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Debit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Creedit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</th>
									</tr>
									</thead><tbody>'.$client_opening.'</tbody></table>';
							}
						}
						if(($get_receipt_payments))
						{
							$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $get_receipt_payments[0]->client_code)->first();
							if($client_details->company == ''){
								$companyname = $client_details->surname.' '.$client_details->firstname;
							}
							else{
								$companyname = $client_details->company;
							}
							$output.='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; line-height: 20px; width: 100%; margin: 0px auto; page-break-after: always;">
								<thead><tr>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$get_receipt_payments[0]->client_code.'</th>
										<th colspan="2" style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left'.$font_family.'">'.$companyname.'</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
									</tr>
									<tr>
										<th style="width:120px; padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right; background:#000; color:#fff; text-align:left;'.$font_family.'">Date</th>
										<th style="width:230px; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Source</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Description</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Debit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Creedit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</th>
									</tr>
									</thead><tbody>'.$client_opening;
							foreach($get_receipt_payments as $list)
							{
								if(isset($list->payments_id)) { 
									$source = 'Payments';
									$amount_credit = '';
									$amount_debit = '€ '.number_format_invoice($list->amount);
									$textvalue = 'Payment Made Back to Client';
									$amount = number_format_invoice($list->amount);
									$amt = (int)$list->amount;
									$balance_val = ((int)$balance_val + ((int)$list->amount));
									$class = 'payment_viewer_class';
									$id = $list->payments_id;
								}
								else { 
									$source = 'Receipts'; 
									$amount_credit = '€ '.number_format_invoice($list->amount);
									$amount_debit = '';
									if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
									{								
										$amount = number_format_invoice($list->amount * -1);
										$amt = ((int)$list->amount * -1);
										$textvalue = 'Client Money Received';
										$balance_val = (int)$balance_val + ((int)$list->amount * -1);
									}
									else{								
										$amount = number_format_invoice($list->amount);
										$amt = $list->amount;
										$textvalue = '';
										$balance_val = (int)$balance_val + (int)$list->amount;
									}
									$id = $list->id;
									$class = 'receipt_viewer_class';
								}						
								$output.='<tr>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.date('d-M-Y', $list->dateval).'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$source.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$textvalue.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$amount_debit.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$amount_credit.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.number_format_invoice($balance_val).'</td>
								</tr>';
							}
							$output.='</tbody></table>';
						}
					}
				}
				$time = time();
				$pdf = PDF::loadHTML($output);
			    $pdf->setPaper('A4', 'landscape');
			    $pdf->save('public/papers/Client Finance Account_'.$time.'.pdf');
			    $filename_download = 'Client Finance Account_'.$time.'.pdf';
			    echo $filename_download;
				/*--------------Close type else here------------*/
			}
		}
		if($format == 4){
			$csvfilename = 'Client Finance Account.csv';
			$file = fopen('public/papers/Client Finance Account.csv', 'w');
			if($type == 1){
				$columns = array('Client Code','Surname','Firstname','Company Name','Opening Balance', 'Client Money Received', 'Payments Made','Balance');
				fputcsv($file, $columns);
				if(($clientlist)){
					foreach ($clientlist as $client) {
						$finance_client = \App\Models\FinanceClients::where('client_id',$client->client_id)->first();
						$opening_bal = '0.0';
						if(($finance_client))
						{
							if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
							{
								$opening_bal = $finance_client->debit;
							}
							if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
							{
								$opening_bal = '-'.$finance_client->credit;
							}
						}
						$client_receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
						$client_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
						$sumval = $opening_bal + ($client_receipt * -1);
						$sumval = $sumval + $client_payment;
						$columns = array($client->client_id,$client->surname,$client->firstname,$client->company,number_format_invoice($opening_bal), number_format_invoice($client_receipt * -1), number_format_invoice($client_payment),number_format_invoice($sumval));
							fputcsv($file, $columns);
					}					
				}
				fclose($file);
			    echo $csvfilename;
			}
			else{
				$date =\App\Models\userLogin::where('id',1)->first();
				if(($clientlist)){
					foreach ($clientlist as $client) {
						$finance_client = \App\Models\FinanceClients::where('client_id','like',$client->client_id.'%')->first();
						$balance='';
						if(($finance_client)){
							$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
							$owed_text='Opening Balance';
							if($balance != "0.00" && $balance != "" && $balance != "0")
							{
								if($finance_client->balance >= 0){
									$finance_client->owed_text = 'Client Owes Back';
								}
								else{
									$owed_text = 'Client Is Owed';
								}
							}
							if($finance_client->balance > 0){
								$openening_debit = number_format_invoice($finance_client->balance);
								$openening_credit='';							
							}
							elseif($finance_client->balance == '' || $finance_client->balance == 0){
								$openening_debit = '';
								$openening_credit = number_format_invoice($finance_client->balance);
							}
							else{							
								$openening_debit = '';
								$openening_credit = number_format_invoice($finance_client->balance * -1);
							}
							$client_opening = array(date('d-M-Y', strtotime($date->opening_balance_date)),'Opening Balance',$owed_text,$openening_debit,$openening_credit, number_format_invoice($finance_client->balance));
						}
						else{
							$balance='';
							$client_opening = [];
						}
						$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_receipt_payments=array_merge($get_receipts,$get_payments);
						$dateval = array();
						foreach ($get_receipt_payments as $key => $row)
						{
						    $dateval[$key] = $row->dateval;
						}
						array_multisort($dateval, SORT_ASC, $get_receipt_payments);
						$balance_val = $balance;
						if(($finance_client) && !count($get_receipt_payments)) {
							if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
							{
								$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $finance_client->client_id)->first();
								if($client_details->company == ''){
									$companyname = $client_details->surname.' '.$client_details->firstname;
								}
								else{
									$companyname = $client_details->company;
								}
								$columns = array('','','','','','');
							fputcsv($file, $columns);
								$columns = array($client_details->client_id,$companyname,'','','','');
								fputcsv($file, $columns);
								$columns = array('Date','Source','Description','Debit','Credit','Balance');
								fputcsv($file, $columns);
								if(($client_opening)){
									fputcsv($file, $client_opening);
								}
							}
						}
						if(($get_receipt_payments))
						{
							$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $get_receipt_payments[0]->client_code)->first();
							if($client_details->company == ''){
								$companyname = $client_details->surname.' '.$client_details->firstname;
							}
							else{
								$companyname = $client_details->company;
							}
							$columns = array('','','','','','');
							fputcsv($file, $columns);
							$columns = array($get_receipt_payments[0]->client_code,$companyname,'','','','');
							fputcsv($file, $columns);
							$columns = array('Date','Source','Description','Debit','Credit','Balance');
							fputcsv($file, $columns);
							if(($client_opening)){
									fputcsv($file, $client_opening);
								}
							foreach($get_receipt_payments as $list)
							{
								if(isset($list->payments_id)) { 
									$source = 'Payments';
									$amount_credit = '';
									$amount_debit = number_format_invoice($list->amount);
									$textvalue = 'Payment Made Back to Client';
									$amount = number_format_invoice($list->amount);
									$amt = (int)$list->amount;
									$balance_val = ((int)$balance_val + ((int)$list->amount));
									$class = 'payment_viewer_class';
									$id = $list->payments_id;
								}
								else { 
									$source = 'Receipts'; 
									$amount_credit = number_format_invoice($list->amount);
									$amount_debit = '';
									if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
									{								
										$amount = number_format_invoice($list->amount * -1);
										$amt = ((int)$list->amount * -1);
										$textvalue = 'Client Money Received';
										$balance_val = (int)$balance_val + ((int)$list->amount * -1);
									}
									else{								
										$amount = number_format_invoice($list->amount);
										$amt = (int)$list->amount;
										$textvalue = '';
										$balance_val = (int)$balance_val + (int)$list->amount;
									}
									$id = $list->id;
									$class = 'receipt_viewer_class';
								}		
								$columns = array(date('d-M-Y', $list->dateval),$source,$textvalue,$amount_debit,$amount_credit,number_format_invoice($balance_val));
								fputcsv($file, $columns);
							}
						}
					}
				}
				fclose($file);
			    echo $csvfilename;
			}
		}
	}
	public function practice_load_review(Request $request){
		$year = $request->get('year');
		$net_month='';
		$net='';
		$i=1;
		$total_net='';
        for($i = 1; $i <= 12; $i++){
        	if($i <= 9){
        		$i = '0'.$i;
        	}
        	else{
        		$i = $i;
        	}
          $from_date = date($year.'-'.$i.'-01');
          $to_date = date($year.'-'.$i.'-31');
          $net = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('inv_net');
          if($total_net == ''){
          	$total_net = $net;
          }
          else{
          	$total_net = $total_net+$net;
          }
          $net_month.='<td style="text-align:right">'.number_format_invoice($net).'</td>';
        }        
		$result = '<tr>
					<td>'.$year.'</td>
					'.$net_month.'
					<td style="text-align:right">'.number_format_invoice($total_net).'</td>
				</tr>';
		echo json_encode(array('year' => $year, 'output' => $result ));
	}
	public function practice_load_client_review(Request $request){
		$count = $request->get('count');
		$redact = $request->get('redact');
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('id', $count)->first();
		if($client_details){
			if($client_details->company == ""){$client_company = $client_details->firstname.' & '.$client_details->surname;}else{$client_company = $client_details->company;}
			$first_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'ASC')->first();
			$last_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'DESC')->first();
			$first_year = date('Y', strtotime($first_year->invoice_date));
	        $last_year = date('Y', strtotime($last_year->invoice_date));
	        $client_table_month='';
	        $total_net='';
	        for($i = $first_year; $i <= $last_year; $i++){
	          $year = $i;
	          $from_date = date($i.'-01-01');
	          $to_date = date($i.'-12-31');
	          $net = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_details->client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('inv_net');
	          if($total_net == ''){
	          	$total_net = $net;
	          }
	          else{
	          	$total_net = $total_net+$net;
	          }
	          $client_table_month.='<td style="text-align:right">'.number_format_invoice($net).'</td>';
	        }
	        $client_table_month.='<td style="text-align:right">'.number_format_invoice($total_net).'</td>';
			if($client_details->active == 2){
				$color = 'color:#f00';
			}
			else{
				$color = 'color:#000';
			}
			$result =  '<tr style="'.$color.'">
							<td>'.$count.'</td>
							<td>'.$client_details->client_id.'</td>';
							if($redact == 0){
								$result.='<td class="practice_client_name">'.$client_company.'</td>';
							}
							$result.=$client_table_month.'
						</tr>';
						$client_id = $client_details->client_id;
		}
		else{
			$result = '';
			$client_id = '';
		}
		echo json_encode(array('output' => $result, 'client_id' => $client_id, 'count' => $count ));
	}
	public function practice_load_staff_review(Request $request){
		$userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('disabled',0)->get();
		$result='';
		if(($userlist)){              
        	foreach($userlist as $user){
        		$check_date_from = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user->user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) DESC LIMIT 1');
                if(($check_date_from))
                {
                  $cost = $check_date_from[0]->cost;
                }
                else{
                  $cost = 0;
                }
        		$result.='
        		<tr>
        			<td>'.$user->lastname.' '.$user->firstname.'</td>
        			<td>&euro;  '.$cost.'</td>
        		</tr>
        		';
        	}
        }
        echo json_encode(array('output' => $result));
	}
	public function practice_review_export(Request $request){
		$type = $request->get('type');
		if($type == 1){
			$j=1;
            $turnover_table_month=['Year'];
            for($j = 1; $j <= 12; $j++){
              $month = date('Y-'.$j.'-01');
              array_push($turnover_table_month, date('M', strtotime($month)));
            }
            array_push($turnover_table_month, 'Total');
			$filename = 'Report Turnover Review.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $turnover_table_month);
			$first_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'ASC')->first();
            $last_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'DESC')->first();
            $first_year = date('Y', strtotime($first_year->invoice_date));
            $last_year = date('Y', strtotime($last_year->invoice_date));
            for($i = $first_year; $i <= $last_year; $i++){
              $year = $i;
              $client_table_month=[];
              array_push($client_table_month, $year);
              $net='';
              $k=1;
              $total_net='';
              for($k = 1; $k <= 12; $k++){
              	if($k <= 9){
	        		$k = '0'.$k;
	        	}
	        	else{
	        		$k = $k;
	        	}
	          $from_date = date($year.'-'.$k.'-01');
	          $to_date = date($year.'-'.$k.'-31');
	          $net = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('inv_net');	          
	          if($total_net == ''){
	          	$total_net = $net;
	          }
	          else{
	          	$total_net = $total_net+$net;
	          }
	          array_push($client_table_month, number_format_invoice($net));
	        }
	        array_push($client_table_month, number_format_invoice($total_net));
	        fputcsv($fileopen, $client_table_month);
            }
		    fclose($fileopen);
	        echo $filename;
		}
		elseif($type == 2){
			$j=1;
            $client_table_month=['Client Code', 'Client Name'];
            $first_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'ASC')->first();
            $last_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'DESC')->first();
            $first_year = date('Y', strtotime($first_year->invoice_date));
            $last_year = date('Y', strtotime($last_year->invoice_date));
            for($j = $first_year; $j <= $last_year; $j++){
              $year = $j;
              array_push($client_table_month, $j);
            }
            array_push($client_table_month, 'Total');            
            $filename = 'Report Client Review.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $client_table_month);
		    $client_list = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
		    if(($client_list)){
		    	foreach ($client_list as $client) {
		    		if($client->company == ""){$client_company = $client->firstname.' & '.$client->surname;}else{$client_company = $client->company;}
		    		$single_client = [$client->client_id];
		    		array_push($single_client, $client_company);
		    		$first_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'ASC')->first();
					$last_year = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'DESC')->first();
					$first_year = date('Y', strtotime($first_year->invoice_date));
			        $last_year = date('Y', strtotime($last_year->invoice_date));
			        $client_table_month='';
			        $total_net='';
			        for($i = $first_year; $i <= $last_year; $i++){
			          $year = $i;
			          $from_date = date($i.'-01-01');
			          $to_date = date($i.'-12-31');
			          $net = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client->client_id)->where('invoice_date','>=',$from_date)->where('invoice_date','<=',$to_date)->sum('inv_net');
			          if($total_net == ''){
			          	$total_net = $net;
			          }
			          else{
			          	$total_net = $total_net+$net;
			          }
			          array_push($single_client, number_format_invoice($net));			          
			        }
			        array_push($single_client, number_format_invoice($total_net));
			        fputcsv($fileopen, $single_client);		    		
		    	}
		    }
            fclose($fileopen);
	        echo $filename;
		}
		elseif($type == 3){
			$columns_1 = array('Staff Name', 'Break Even Point');
			$filename = 'Report Staff Review.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);
		    $userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('disabled',0)->get();
			$result='';
			if(($userlist)){              
	        	foreach($userlist as $user){
	        		$check_date_from = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user->user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) DESC LIMIT 1');
	                if(($check_date_from))
	                {
	                  $cost = $check_date_from[0]->cost;
	                }
	                else{
	                  $cost = 0;
	                }
	                $name = $user->lastname.' '.$user->firstname;
	                $cost_euro = $cost;
	                $columns_2 = array($name, $cost_euro);
					fputcsv($fileopen, $columns_2);        		
	        	}
	        }
		    fclose($fileopen);
	        echo $filename;
		}
	}
	public function accounting_period_save(Request $request){
		$start = $request->get('start');
		$end = $request->get('end');
		$desc = $request->get('desc');
		$data['ac_start_date'] = date('Y-m-d', strtotime($start));
		$data['ac_end_date'] = date('Y-m-d', strtotime($end));
		$data['ac_description'] = $desc;
		$data['practice_code'] = Session::get('user_practice_code');
		\App\Models\AccountingPeriod::insert($data);
		$accounting_period_list = \App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->get();
		$i=1;
		$output_ac='';
		if(($accounting_period_list)){
	        foreach ($accounting_period_list as $single_account) {
	          if($single_account->status == 1){
                $status = '<a href="javascript:"><i class="fa fa-check default_account_period" style="color: #33CC66" data-element="'.$single_account->accounting_id.'"></i></a>';
              }
              else{
                $status = '<a href="javascript:"><i class="fa fa-times account_period_active" data-element="'.$single_account->accounting_id.'" title="Active Default Period"  style="color: #E11B1C"></i></a>';
              }
              if($single_account->ac_lock == 0){
                $lock = '<a href="javascript:"><i class="fa fa-lock class_account_unlock" data-element="'.$single_account->accounting_id.'" type="0" style="color: #E11B1C" title="Unlock Accounting Period"></i></a>';
              }
              else{
                $lock = '<a href="javascript:"><i class="fa fa-unlock-alt class_account_lock" data-element="'.$single_account->accounting_id.'" type="1" style="color: #33CC66" title="Lock Accounting Period"></i>';
              }
	          $output_ac.='
	            <tr>
	              <td>'.$i.'</td>
	              <td>'.$single_account->accounting_id.'</td>
	              <td>'.date('d-M-Y', strtotime($single_account->ac_start_date)).'</td>
	              <td>'.date('d-M-Y', strtotime($single_account->ac_end_date)).'</td>
	              <td>'.$single_account->ac_description.'</td>
	              <td style="text-align:center;">
	                '.$status.'&nbsp; &nbsp; '.$lock.'
	              </td>
	            </tr>';
	          $i++;
	        }
	      }
	    else{
	        $output_ac='
	          <tr>
	            <td></td>
	            <td></td>
	            <td>No Records</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>
	        ';
	    }
	    $accounting_period = \App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->orderBy('accounting_id', 'desc')->first();
        $period_id = $accounting_period->accounting_id+1;
	    echo json_encode(array('output' => $output_ac, 'period_id' => $period_id));
	}
	public function accounting_period_set_default(Request $request){
		$period_id = $request->get('period_id');
		$accounting_period = \App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->get();
		if(($accounting_period)){
			foreach ($accounting_period as $single_account) {
				$data['status'] = 0;
				\App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->where('accounting_id', $single_account->accounting_id)->update($data);
			}
		}
		\App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->where('accounting_id', $period_id)->update(['status' => '1']);
		echo json_encode(array('result' => 'success'));
	}
	public function accounting_period_lock_unlock(Request $request){
		$period_id = $request->get('period_id');
		$type = $request->get('type');
		if($type == 0){
			$data['ac_lock'] = 1;
		}
		else{
			$data['ac_lock'] = 0;
		}
		\App\Models\AccountingPeriod::where('practice_code', Session::get('user_practice_code'))->where('accounting_id', $period_id)->update($data);
		echo json_encode(array('result' => 'success'));
	}
	public function get_profit_loss_values(Request $request)
	{
		$from_month = $request->get('from_month');
		$to_month = $request->get('to_month');
		$edate = strtotime($to_month);
        $bdate = strtotime($from_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $turnover_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Sales')->pluck('code')->toArray();
		$otherincome_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Other Income')->pluck('code')->toArray();
		$costofsales_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Cost of Sales')->pluck('code')->toArray();
		$adminexpenses_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Administrative Expenses')->pluck('code')->toArray();
        $output = '<table class="table table_pl">
        	<thead>
        		<th></th>
        		<th style="text-align:right">Total</th>
        		<th style="text-align:right">'.date('M-Y', strtotime($from_month)).'</th>';
        		for($i=1; $i<=$age; $i++){
        			$datevalll = date('M-Y', strtotime('first day of next month', strtotime($from_month)));
        			$output.='<th style="text-align:right">'.$datevalll.'</th>';
        			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
        		}
        	$output.='</thead>
        	<tbody>';
        		$from_month = $request->get('from_month');
        		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$turnover_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
        		$turnover ='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.date('Y-m',strtotime($from_month)).'" data-code="'.implode(',',$turnover_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
        		$turnoverarr[0] = $from_month_values;
        		$turnover_total = $from_month_values;
        		for($i=1; $i<=$age; $i++){
    				$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
    				$from_month_values = \App\Models\Journals::whereIn('nominal_code',$turnover_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
    				$turnover.='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.$datevalll.'" data-code="'.implode(',',$turnover_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
    				$turnoverarr[$i] = $from_month_values;
    				$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
    				$turnover_total = $turnover_total + $from_month_values;
    			}
    			$turnoverarr['total'] = $turnover_total;
        		$output.='<tr>
        			<td><strong>TURNOVER</strong></td>
        			<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="total" data-code="'.implode(',',$turnover_codes).'">'.number_format_invoice($turnover_total).'</a></td>
        			'.$turnover.'
        		</tr>';
        		$from_month = $request->get('from_month');
        		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$otherincome_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
        		$otherincome ='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.date('Y-m',strtotime($from_month)).'" data-code="'.implode(',',$otherincome_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
        		$otherincomearr[0] = $from_month_values;
        		$otherincome_total = $from_month_values;
        		for($i=1; $i<=$age; $i++){
    				$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
    				$from_month_values = \App\Models\Journals::whereIn('nominal_code',$otherincome_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
    				$otherincome.='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.$datevalll.'" data-code="'.implode(',',$otherincome_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
    				$otherincomearr[$i] = $from_month_values;
    				$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
    				$otherincome_total = $otherincome_total + $from_month_values;
    			}
    			$otherincomearr['total'] = $otherincome_total;
        		$output.='<tr>
        			<td><strong>OTHER INCOME</strong></td>
        			<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="total" data-code="'.implode(',',$otherincome_codes).'">'.number_format_invoice($otherincome_total).'</a></td>
        			'.$otherincome.'
        		</tr>';
        		$totalincome_total = $turnoverarr['total'] + $otherincomearr['total'];
        		$totalincomearr['total'] = $turnoverarr['total'] + $otherincomearr['total'];
        		$sum0 = $turnoverarr[0] + $otherincomearr[0];
        		$totalincomearr[0] = $sum0;
        		$totalincome = '<td style="text-align:right"><strong>'.number_format_invoice($sum0).'</strong></td>';
        		for($i=1; $i<=$age; $i++){
        			$sum = $turnoverarr[$i] + $otherincomearr[$i];
    				$totalincome.='<td style="text-align:right"><strong>'.number_format_invoice($sum).'</strong></td>';
    				$totalincomearr[$i] = $sum;
    			}
        		$output.='<tr>
        			<td><strong>TOTAL INCOME</strong></td>
        			<td style="text-align:right"><strong>'.number_format_invoice($totalincome_total).'</strong></td>
        			'.$totalincome.'
        		</tr>';
        		$from_month = $request->get('from_month');
        		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$costofsales_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
        		$costofsales ='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.date('Y-m',strtotime($from_month)).'" data-code="'.implode(',',$costofsales_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
        		$costofsalesarr[0] = $from_month_values;
        		$costofsales_total = $from_month_values;
        		for($i=1; $i<=$age; $i++){
    				$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
    				$from_month_values = \App\Models\Journals::whereIn('nominal_code',$costofsales_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
    				$costofsales.='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.$datevalll.'" data-code="'.implode(',',$costofsales_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
    				$costofsalesarr[$i] = $from_month_values;
    				$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
    				$costofsales_total = $costofsales_total + $from_month_values;
    			}
    			$costofsalesarr['total'] = $costofsales_total;
        		$output.='<tr>
        			<td><strong>COST OF SALES</strong></td>
        			<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="total" data-code="'.implode(',',$costofsales_codes).'">'.number_format_invoice($costofsales_total).'</a></td>
        			'.$costofsales.'
        		</tr>';
        		$grossprofit_total = $totalincomearr['total'] - $costofsalesarr['total'];
        		$grossprofitarr['total'] = $totalincomearr['total'] - $costofsalesarr['total'];
        		$sum0 = $totalincomearr[0] - $costofsalesarr[0];
        		$grossprofitarr[0] = $sum0;
        		$grossprofit = '<td style="text-align:right"><strong>'.number_format_invoice($sum0).'</strong></td>';
        		for($i=1; $i<=$age; $i++){
        			$sum = $totalincomearr[$i] - $costofsalesarr[$i];
    				$grossprofit.='<td style="text-align:right"><strong>'.number_format_invoice($sum).'</strong></td>';
    				$grossprofitarr[$i] = $sum;
    			}
        		$output.='<tr>
        			<td><strong>GROSS PROFIT</strong></td>
        			<td style="text-align:right"><strong>'.number_format_invoice($grossprofit_total).'</strong></td>
        			'.$grossprofit.'
        		</tr>';
        		$from_month = $request->get('from_month');
        		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$adminexpenses_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
        		$adminexpenses ='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.date('Y-m',strtotime($from_month)).'" data-code="'.implode(',',$adminexpenses_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
        		$adminexpensesarr[0] = $from_month_values;
        		$adminexpenses_total = $from_month_values;
        		for($i=1; $i<=$age; $i++){
    				$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
    				$from_month_values = \App\Models\Journals::whereIn('nominal_code',$adminexpenses_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
    				$adminexpenses.='<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="'.$datevalll.'" data-code="'.implode(',',$adminexpenses_codes).'">'.number_format_invoice($from_month_values).'</a></td>';
    				$adminexpensesarr[$i] = $from_month_values;
    				$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
    				$adminexpenses_total = $adminexpenses_total + $from_month_values;
    			}
    			$adminexpensesarr['total'] = $adminexpenses_total;
        		$output.='<tr>
        			<td><strong>ADMIN EXPENSES</strong></td>
        			<td style="text-align:right"><a href="javascript:" class="view_journals_pl" data-element="total" data-code="'.implode(',',$adminexpenses_codes).'">'.number_format_invoice($adminexpenses_total).'</a></td>
        			'.$adminexpenses.'
        		</tr>';
        		$netprofit_total = $grossprofitarr['total'] - $adminexpensesarr['total'];
        		$netprofitarr['total'] = $grossprofitarr['total'] - $adminexpensesarr['total'];
        		$sum0 = $grossprofitarr[0] - $adminexpensesarr[0];
        		$netprofitarr[0] = $sum0;
        		$netprofit = '<td style="text-align:right"><strong>'.number_format_invoice($sum0).'</strong></td>';
        		for($i=1; $i<=$age; $i++){
        			$sum = $grossprofitarr[$i] - $adminexpensesarr[$i];
    				$netprofit.='<td style="text-align:right"><strong>'.number_format_invoice($sum).'</strong></td>';
    				$netprofitarr[$i] = $sum;
    			}
        		$output.='<tr>
        			<td><strong>NET PROFIT</strong></td>
        			<td style="text-align:right"><strong>'.number_format_invoice($netprofit_total).'</strong></td>
        			'.$netprofit.'
        		</tr>
        	</tbody>
        </table>';
        $data['output'] = $output;
        $data['countmonth'] = $age;
        echo json_encode($data);
	}
	public function view_journal_for_profit_loss(Request $request)
	{
		$code = explode(',',$request->get('code'));
		$from = $request->get('from_month');
		$to = $request->get('to_month');
		$from_date = date('Y-m-d',strtotime($from));
		$to_date = date('Y-m-d',strtotime($to));
		$debits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('dr_value');
		$credits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('cr_value');
		$opening = number_format_invoice_without_comma($debits_open - $credits_open);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereIn('code',$code)->get();
		$journals = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','>=',$from_date)->where('journal_date','<=',$to_date)->orderBy('journal_date','asc')->get();
		$error = 0;
		$output = '
		<table class="table own_table_white" id="pl_viewer_extend" style="margin-top:20px">
			<thead>
				<th style="text-align:left">Journal <br/>ID</th>
				<th style="text-align:left">Journal <br/>Date</th>
				<th style="text-align:left">Journal <br/>Description</th>
				<th style="text-align:left">Nominal <br/>Code</th>
				<th style="text-align:left">Nominal Code <br/>Description</th>
				<th style="text-align:left">Journal <br/>Source</th>
				<th style="text-align:right">Debit <br/>Value</th>
				<th style="text-align:right">Credit <br/>Value</th>
			</thead>
			<tbody>
			<tr>
				<td></td>
				<td></td>
				<td>Opening Balance</td>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:right">'.number_format_invoice($debit_bal).'</td>
				<td style="text-align:right">'.number_format_invoice($credit_bal).'</td>
			</tr>';
			$total_debit_value = $debit_bal;
			$total_credit_value = $credit_bal;
			if(($journals))
			{
				foreach($journals as $journal)
				{
					$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
					$output.='<tr>
						<td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
						<td><spam style="display:none">'.strtotime($journal->journal_date).'</spam>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
						<td>'.$journal->description.'</td>
						<td>'.$journal->nominal_code.'</td>
						<td>'.$get_nominal->description.'</td>
						<td><a href="javascript:" class="journal_source_link">'.$journal->journal_source.'</a></td>
						<td style="text-align:right">'.number_format_invoice($journal->dr_value).'</td>
						<td style="text-align:right">'.number_format_invoice($journal->cr_value).'</td>
					</tr>';
					$total_debit_value = $total_debit_value + $journal->dr_value;
					$total_credit_value = $total_credit_value + $journal->cr_value;
					$error++;
				}
			}
			$output.='</tbody>
			<tr>
				<td colspan="6">Total</td>
				<td style="text-align:right">'.number_format_invoice($total_debit_value).'</td>
				<td style="text-align:right">'.number_format_invoice($total_credit_value).'</td>
			</tr>
		</table>';
		echo json_encode(array("error" => $error, "output" => $output));
		//echo $output;
	}
	public function view_journal_for_profit_loss_single_month(Request $request)
	{
		$code = explode(',',$request->get('code'));
		$from = $request->get('month').'-01';
		$from_date = date('Y-m-d',strtotime($from));
		$to_date = date("Y-m-t", strtotime($from_date));
		$debits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('dr_value');
		$credits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('cr_value');
		$opening = number_format_invoice_without_comma($debits_open - $credits_open);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereIn('code',$code)->get();
		$journals = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','>=',$from_date)->where('journal_date','<=',$to_date)->orderBy('journal_date','asc')->get();
		$error = 0;
		$output = '
		<table class="table own_table_white" id="pl_viewer_extend" style="margin-top:20px">
			<thead>
				<th style="text-align:left">Journal <br/>ID</th>
				<th style="text-align:left">Journal <br/>Date</th>
				<th style="text-align:left">Journal <br/>Description</th>
				<th style="text-align:left">Nominal <br/>Code</th>
				<th style="text-align:left">Nominal Code <br/>Description</th>
				<th style="text-align:left">Journal <br/>Source</th>
				<th style="text-align:right">Debit <br/>Value</th>
				<th style="text-align:right">Credit <br/>Value</th>
			</thead>
			<tbody>
			<tr>
				<td></td>
				<td></td>
				<td>Opening Balance</td>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:right">'.number_format_invoice($debit_bal).'</td>
				<td style="text-align:right">'.number_format_invoice($credit_bal).'</td>
			</tr>';
			$total_debit_value = $debit_bal;
			$total_credit_value = $credit_bal;
			if(($journals))
			{
				foreach($journals as $journal)
				{
					$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
					$output.='<tr>
						<td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
						<td><spam style="display:none">'.strtotime($journal->journal_date).'</spam>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
						<td>'.$journal->description.'</td>
						<td>'.$journal->nominal_code.'</td>
						<td>'.$get_nominal->description.'</td>
						<td><a href="javascript:" class="journal_source_link">'.$journal->journal_source.'</a></td>
						<td style="text-align:right">'.number_format_invoice($journal->dr_value).'</td>
						<td style="text-align:right">'.number_format_invoice($journal->cr_value).'</td>
					</tr>';
					$total_debit_value = $total_debit_value + $journal->dr_value;
					$total_credit_value = $total_credit_value + $journal->cr_value;
					$error++;
				}
			}
			$output.='</tbody>
			<tr>
				<td colspan="6">Total</td>
				<td style="text-align:right">'.number_format_invoice($total_debit_value).'</td>
				<td style="text-align:right">'.number_format_invoice($total_credit_value).'</td>
			</tr>
		</table>';
		echo json_encode(array("error" => $error, "output" => $output));
		//echo $output;
	}
	public function extract_journal_for_profit_loss(Request $request)
	{
		$code = explode(',',$request->get('code'));
		$from = $request->get('from_month');
		$to = $request->get('to_month');
		$from_date = date('Y-m-d',strtotime($from));
		$to_date = date('Y-m-d',strtotime($to));
		$debits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('dr_value');
		$credits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('cr_value');
		$opening = number_format_invoice_without_comma($debits_open - $credits_open);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereIn('code',$code)->get();
		$journals = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','>=',$from_date)->where('journal_date','<=',$to_date)->orderBy('journal_date','asc')->get();
		$filename = time().'_Profit & Loss For Nominal Code.csv';
		$columns = array('Journal ID','Journal Date','Journal Description','Nominal Code','Nominal Code Description', 'Journal Source','Debit Value','Credit Value');
		$file = fopen('public/papers/'.$filename.'', 'w');
		fputcsv($file, $columns);
		$columns1 = array('','','Opening Balance','','', '',number_format_invoice($debit_bal),number_format_invoice($credit_bal));
		fputcsv($file, $columns1);
		$total_debit_value = $debit_bal;
		$total_credit_value = $credit_bal;
		if(($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
				$columns2 = array($journal->connecting_journal_reference,date('d-M-Y',strtotime($journal->journal_date)),$journal->description,$journal->nominal_code,$get_nominal->description, $journal->journal_source,number_format_invoice($journal->dr_value),number_format_invoice($journal->cr_value));
				fputcsv($file, $columns2);
				$total_debit_value = $total_debit_value + $journal->dr_value;
				$total_credit_value = $total_credit_value + $journal->cr_value;
			}
		}
		$columns3 = array('','','','','', '',number_format_invoice($total_debit_value),number_format_invoice($total_credit_value));
		fputcsv($file, $columns3);
		echo $filename;
	}
	public function extract_journal_for_profit_loss_single_month(Request $request)
	{
		$code = explode(',',$request->get('code'));
		$from = $request->get('month').'-01';
		$from_date = date('Y-m-d',strtotime($from));
		$to_date = date("Y-m-t", strtotime($from_date));
		$debits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('dr_value');
		$credits_open = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','<',$from_date)->sum('cr_value');
		$opening = number_format_invoice_without_comma($debits_open - $credits_open);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}
		$nominal_code_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereIn('code',$code)->get();
		$journals = \App\Models\Journals::whereIn('nominal_code',$code)->where('journal_date','>=',$from_date)->where('journal_date','<=',$to_date)->orderBy('journal_date','asc')->get();
		$filename = time().'_Profit & Loss For Nominal Code.csv';
		$columns = array('Journal ID','Journal Date','Journal Description','Nominal Code','Nominal Code Description', 'Journal Source','Debit Value','Credit Value');
		$file = fopen('public/papers/'.$filename.'', 'w');
		fputcsv($file, $columns);
		$columns1 = array('','','Opening Balance','','', '',number_format_invoice($debit_bal),number_format_invoice($credit_bal));
		fputcsv($file, $columns1);
		$total_debit_value = $debit_bal;
		$total_credit_value = $credit_bal;
		if(($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$journal->nominal_code)->first();
				$columns2 = array($journal->connecting_journal_reference,date('d-M-Y',strtotime($journal->journal_date)),$journal->description,$journal->nominal_code,$get_nominal->description, $journal->journal_source,number_format_invoice($journal->dr_value),number_format_invoice($journal->cr_value));
				fputcsv($file, $columns2);
				$total_debit_value = $total_debit_value + $journal->dr_value;
				$total_credit_value = $total_credit_value + $journal->cr_value;
			}
		}
		$columns3 = array('','','','','', '',number_format_invoice($total_debit_value),number_format_invoice($total_credit_value));
		fputcsv($file, $columns3);
		echo $filename;
	}
	public function extract_profit_loss_values(Request $request)
	{
		$from_month = $request->get('from_month');
		$to_month = $request->get('to_month');
		$edate = strtotime($to_month);
        $bdate = strtotime($from_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $turnover_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Sales')->pluck('code')->toArray();
		$otherincome_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Other Income')->pluck('code')->toArray();
		$costofsales_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Cost of Sales')->pluck('code')->toArray();
		$adminexpenses_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('primary_group','Profit & Loss')->where('debit_group','Administrative Expenses')->pluck('code')->toArray();
		$filename = time().'_Extract Profit & Loss.csv';
		$columns = ['','Total',date('M-Y', strtotime($from_month))];
		for($i=1; $i<=$age; $i++){
			$datevalll = date('M-Y', strtotime('first day of next month', strtotime($from_month)));
			array_push($columns,$datevalll);
			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
		}
		$file = fopen('public/papers/'.$filename.'', 'w');
		fputcsv($file, $columns);
		$col_turnover = [];
		$from_month = $request->get('from_month');
		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$turnover_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
		$turnover =number_format_invoice($from_month_values);
		array_push($col_turnover,$turnover);
		$turnoverarr[0] = $from_month_values;
		$turnover_total = $from_month_values;
		for($i=1; $i<=$age; $i++){
			$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
			$from_month_values = \App\Models\Journals::whereIn('nominal_code',$turnover_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
			array_push($col_turnover,number_format_invoice($from_month_values));
			$turnoverarr[$i] = $from_month_values;
			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
			$turnover_total = $turnover_total + $from_month_values;
		}
    	$turnoverarr['total'] = $turnover_total;
    	$columns_turnover = ['TURNOVER',$turnover_total];
    	$columns1 = array_merge($columns_turnover,$col_turnover);
    	fputcsv($file, $columns1);
    	$col_otherincome = [];
		$from_month = $request->get('from_month');
		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$otherincome_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
		$otherincome =number_format_invoice($from_month_values);
		array_push($col_otherincome,$otherincome);
		$otherincomearr[0] = $from_month_values;
		$otherincome_total = $from_month_values;
		for($i=1; $i<=$age; $i++){
			$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
			$from_month_values = \App\Models\Journals::whereIn('nominal_code',$otherincome_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
			array_push($col_otherincome,number_format_invoice($from_month_values));
			$otherincomearr[$i] = $from_month_values;
			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
			$otherincome_total = $otherincome_total + $from_month_values;
		}
		$otherincomearr['total'] = $otherincome_total;
		$columns_otherincome = ['Other Income',$otherincome_total];
    	$columns2 = array_merge($columns_otherincome,$col_otherincome);
    	fputcsv($file, $columns2);
    	$col_totalincome = [];
		$totalincome_total = $turnoverarr['total'] + $otherincomearr['total'];
		$totalincomearr['total'] = $turnoverarr['total'] + $otherincomearr['total'];
		$sum0 = $turnoverarr[0] + $otherincomearr[0];
		$totalincomearr[0] = $sum0;
		$totalincome = number_format_invoice($sum0);
		array_push($col_totalincome,$totalincome);
		for($i=1; $i<=$age; $i++){
			$sum = $turnoverarr[$i] + $otherincomearr[$i];
			array_push($col_totalincome,number_format_invoice($sum));
			//$totalincome.='<td style="text-align:right">'.number_format_invoice($sum).'</td>';
			$totalincomearr[$i] = $sum;
		}
		$columns_totalincome = ['TOTAL INCOME',number_format_invoice($totalincome_total)];
    	$columns3 = array_merge($columns_totalincome,$col_totalincome);
    	fputcsv($file, $columns3);
    	$col_costofsales = [];
		$from_month = $request->get('from_month');
		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$costofsales_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
		$costofsales =number_format_invoice($from_month_values);
		array_push($col_costofsales,$costofsales);
		$costofsalesarr[0] = $from_month_values;
		$costofsales_total = $from_month_values;
		for($i=1; $i<=$age; $i++){
			$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
			$from_month_values = \App\Models\Journals::whereIn('nominal_code',$costofsales_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
			array_push($col_costofsales,number_format_invoice($from_month_values));
			$costofsalesarr[$i] = $from_month_values;
			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
			$costofsales_total = $costofsales_total + $from_month_values;
		}
		$costofsalesarr['total'] = $costofsales_total;
		$columns_costofsales = ['COST OF SALES',number_format_invoice($costofsales_total)];
    	$columns4 = array_merge($columns_costofsales,$col_costofsales);
    	fputcsv($file, $columns4);
    	$col_grossprofit = [];
		$grossprofit_total = $totalincomearr['total'] - $costofsalesarr['total'];
		$grossprofitarr['total'] = $totalincomearr['total'] - $costofsalesarr['total'];
		$sum0 = $totalincomearr[0] - $costofsalesarr[0];
		$grossprofitarr[0] = $sum0;
		array_push($col_grossprofit,number_format_invoice($sum0));
		//$grossprofit = '<td style="text-align:right">'.number_format_invoice($sum0).'</td>';
		for($i=1; $i<=$age; $i++){
			$sum = $totalincomearr[$i] - $costofsalesarr[$i];
			//$grossprofit.='<td style="text-align:right">'.number_format_invoice($sum).'</td>';
			array_push($col_grossprofit,number_format_invoice($sum));
			$grossprofitarr[$i] = $sum;
		}
		$columns_grossprofit = ['GROSS PROFIT',number_format_invoice($grossprofit_total)];
    	$columns5 = array_merge($columns_grossprofit,$col_grossprofit);
    	fputcsv($file, $columns5);
    	$col_adminexp = [];
		$from_month = $request->get('from_month');
		$from_month_values = \App\Models\Journals::whereIn('nominal_code',$adminexpenses_codes)->where('journal_date','LIKE',date('Y-m',strtotime($from_month)).'%')->sum(\DB::raw('dr_value - cr_value'));
		$adminexpenses =number_format_invoice($from_month_values);
		array_push($col_adminexp,$adminexpenses);
		$adminexpensesarr[0] = $from_month_values;
		$adminexpenses_total = $from_month_values;
		for($i=1; $i<=$age; $i++){
			$datevalll = date('Y-m', strtotime('first day of next month', strtotime($from_month)));
			$from_month_values = \App\Models\Journals::whereIn('nominal_code',$adminexpenses_codes)->where('journal_date','LIKE',$datevalll.'%')->sum(\DB::raw('dr_value - cr_value'));
			array_push($col_adminexp,number_format_invoice($from_month_values));
			$adminexpensesarr[$i] = $from_month_values;
			$from_month = date('Y-m-d', strtotime('first day of next month', strtotime($from_month)));
			$adminexpenses_total = $adminexpenses_total + $from_month_values;
		}
		$adminexpensesarr['total'] = $adminexpenses_total;
		$columns_adminexp = ['ADMIN EXPENSES',number_format_invoice($adminexpenses_total)];
    	$columns6 = array_merge($columns_adminexp,$col_adminexp);
    	fputcsv($file, $columns6);
    	$col_netprofit = [];
		$netprofit_total = $grossprofitarr['total'] - $adminexpensesarr['total'];
		$netprofitarr['total'] = $grossprofitarr['total'] - $adminexpensesarr['total'];
		$sum0 = $grossprofitarr[0] - $adminexpensesarr[0];
		$netprofitarr[0] = $sum0;
		$netprofit = number_format_invoice($sum0);
		array_push($col_netprofit,$netprofit);
		for($i=1; $i<=$age; $i++){
			$sum = $grossprofitarr[$i] - $adminexpensesarr[$i];
			//$netprofit.='<td style="text-align:right">'.number_format_invoice($sum).'</td>';
			array_push($col_netprofit,number_format_invoice($sum));
			$netprofitarr[$i] = $sum;
		}
		$columns_netprofit = ['NET PROFIT',number_format_invoice($netprofit_total)];
    	$columns7 = array_merge($columns_netprofit,$col_netprofit);
    	fputcsv($file, $columns7);
    	fclose($file);
    	echo $filename;
	}
}