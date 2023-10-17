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
use Illuminate\Http\Request;
class ReceiptController extends Controller {
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
	public function receipt_management(Request $request)
	{
		$receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->get();
		foreach($receipts as $receipt)
		{
			$code = explode('-',$receipt->client_code);
			$client_id = trim(end($code));
			$dataa['client_code'] = $client_id;
			if($receipt->hold_status == 1 && $receipt->journal_id != 0 && $receipt->clearance_date != '0000-00-00')
			{
				$dataa['hold_status'] = 2;
			}
			\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt->id)->update($dataa);
		}
		$receipt_nominal_codes_arr = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->get();
		$code_array = array();
		if(($receipt_nominal_codes_arr))
		{
			foreach($receipt_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereNotin('code',$code_array)->orderBy('code','asc')->get();
		$receipt_nominal_codes = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('code','asc')->get();
		return view('user/receipts/receipt', array('title' => 'Receipt Management System','nominal_codes' => $nominal_codes,'receipt_nominal_codes' => $receipt_nominal_codes));
	}
	public function receipt_settings(Request $request)
	{
		$receipt_nominal_codes_arr = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->get();
		$code_array = array();
		if(($receipt_nominal_codes_arr))
		{
			foreach($receipt_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereNotin('code',$code_array)->get();
		$receipt_nominal_codes = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->get();
		return view('user/receipts/receipt_settings', array('title' => 'Receipt Management System','nominal_codes' => $nominal_codes,'receipt_nominal_codes' => $receipt_nominal_codes));
	}
	public function move_to_allowable_list(Request $request)
	{
		$code = explode(',',$request->get('code'));
		$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereIn('id',$code)->get();
		$output = '';
		if(($get_details))
		{
			foreach($get_details as $details)
			{
				$data['code'] = $details->code;
				$data['description'] = $details->description;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\receiptNominalCodes::insert($data);
				$output.='<tr>
					<td>'.$details->code.'</td>
					<td>'.$details->description.'</td>
				</tr>';
			}
		}	
		echo $output;
	}
	public function get_nominal_code_description(Request $request)
	{
		$code = $request->get('code');
		$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$code)->first();
		if(($get_details))
		{
			echo $get_details->description;
		}
	}
	public function receipt_commonclient_search(Request $request)
	{
		$value = $request->get('term');
		$details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("status", 0)
            ->where(function ($q) use ($value) {
                $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
            })->get();
		$data=array();
		foreach ($details as $single) {
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
            $data[]=array('value'=>$company.' - '.$single->client_id,'id'=>$single->client_id,'company' => $company.' - '.$single->client_id);
        }
         if(($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>'','company' => ''];
	}
	public function save_receipt_details(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$comment = $request->get('comment');
		$amount = $request->get('amount');
		$data['receipt_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;
		$data['statement'] = 1;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\receipts::insertDetails($data);
		echo $id;
	}
	public function update_receipt_details(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$amount = $request->get('amount');
		$comment = $request->get('comment');
		$receipt_id = $request->get('receipt_id');
		if($date == "") { $data['status'] = 1; }
		elseif($debit == "") { $data['status'] = 1; }
		elseif($credit == "") { $data['status'] = 1; }
		elseif($des == "") { $data['status'] = 1; }
		elseif($amount == "") { $data['status'] = 1; }
		elseif($credit == "712" || $credit == "813A") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		else{
			$data['status'] =  0;
		}
		$data['receipt_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->update($data);
	}
	public function update_receipt_details_editted(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$amount = $request->get('amount');
		$comment = $request->get('comment');
		$receipt_id = $request->get('receipt_id');
		if($date == "") { $data['status'] = 1; }
		elseif($debit == "") { $data['status'] = 1; }
		elseif($credit == "") { $data['status'] = 1; }
		elseif($des == "") { $data['status'] = 1; }
		elseif($amount == "") { $data['status'] = 1; }
		elseif($credit == "712" || $credit == "813A") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		else{
			$data['status'] =  0;
		}
		$data['receipt_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->update($data);
		$receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->first();
		if($receipts->journal_id != 0){
			if($receipts->credit_nominal == "712") {
				$exp_des = explode('-',$receipts->credit_description);
				$journal_description = 'Client Payment from '.$receipts->client_code.' '.$exp_des[0];
			} elseif($receipts->credit_nominal == "813A") {
				$exp_des = explode('-',$receipts->credit_description);
				$journal_description = 'Client Money Holding Account from '.$receipts->client_code.' '.$exp_des[0];
			} else{
				$journal_description = 'Received From '.$receipts->credit_nominal.' '.$receipts->credit_description.' '.$receipts->comment;
			}
			$dataval['journal_date'] = $receipts->receipt_date;
			$dataval['description'] = $journal_description;
			$dataval['nominal_code'] = $receipts->credit_nominal;
			$dataval['dr_value'] = '0.00';
			$dataval['cr_value'] = $receipts->amount;
			$dataval['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::where('connecting_journal_reference',$receipts->journal_id)->update($dataval);
			$next_connecting_journal = $receipts->journal_id.'.01';
			$dataval1['journal_date'] = $receipts->receipt_date;
			$dataval1['description'] = $journal_description;
			$dataval1['dr_value'] = $receipts->amount;
			$dataval1['cr_value'] = '0.00';
			$dataval1['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::where('connecting_journal_reference',$next_connecting_journal)->update($dataval1);
		}
		$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
		$debit_nominal = '';
		if(($get_details))
		{
			$debit_nominal = $get_details->code.' - '.$get_details->description;
		}
		$dataval['debit_nominal'] = $debit_nominal;
		$dataval['strtotime'] = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
		$dataval['amount'] = number_format_invoice($amount);
		echo json_encode($dataval);
	}
	public function import_new_receipts(Request $request)
	{
		$get_imported_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->get();
		if(($get_imported_receipts))
		{
			$dataBatch['created_at'] = date('Y-m-d H:i:s');
			$dataBatch['transaction_count'] = count($get_imported_receipts);
			$dataBatch['practice_code'] = Session::get('user_practice_code');
			$batch_id = \App\Models\receiptBatches::insertDetails($dataBatch);
			foreach($get_imported_receipts as $receipt)
			{
				$daata['batch_id'] = $batch_id;
				$daata['imported'] = 0;
				\App\Models\receipts::where('id',$receipt->id)->update($daata);
			}
		  	return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
		}
		// if($_FILES['new_file']['name']!='')
		// {
		// 	$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		// 	$tmp_name = $_FILES['new_file']['tmp_name'];
		// 	$name=time().'_'.$_FILES['new_file']['name'];
		// 	$errorlist = array();
		// 	if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
		// 		$filepath = $uploads_dir.'/'.$name;
		// 		$objPHPExcel = IOFactory::load($filepath);
		// 		foreach ($objPHPExcel->getWorksheetIterator() as $keyval => $worksheet) {
		// 			$worksheetTitle     = $worksheet->getTitle();
		// 			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		// 			
		// 			$nrColumns = ord($highestColumn) - 64;
		// 			if($highestRow > 500)
		// 			{
		// 				$height = 500;
		// 			}
		// 			else{
		// 				$height = $highestRow;
		// 			}
		// 			$date_header = $worksheet->getCellByColumnAndRow(1, 1); $date_header = trim($date_header->getValue());
		// 			$debit_header = $worksheet->getCellByColumnAndRow(2, 1); $debit_header = trim($debit_header->getValue());
		// 			$credit_header = $worksheet->getCellByColumnAndRow(3, 1); $credit_header = trim($credit_header->getValue());
		// 			$client_header = $worksheet->getCellByColumnAndRow(4, 1); $client_header = trim($client_header->getValue());
		// 			$description_header = $worksheet->getCellByColumnAndRow(5, 1); $description_header = trim($description_header->getValue());
		// 			$comment_header = $worksheet->getCellByColumnAndRow(6, 1); $comment_header = trim($comment_header->getValue());
		// 			$amount_header = $worksheet->getCellByColumnAndRow(7, 1); $amount_header = trim($amount_header->getValue());
		// 			if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client Code" && $comment_header == "Comment" && $amount_header == "Amount")
		// 			{
		// 				for ($row = 2; $row <= $height; $row++) {
		// 					$date = $worksheet->getCellByColumnAndRow(1, $row); $date = trim($date->getValue());
		// 					$debit = $worksheet->getCellByColumnAndRow(2, $row); $debit = trim($debit->getValue());
		// 					$credit = $worksheet->getCellByColumnAndRow(3, $row); $credit = trim($credit->getValue());
		// 					$client = $worksheet->getCellByColumnAndRow(4, $row); $client = trim($client->getValue());
		// 					$description = $worksheet->getCellByColumnAndRow(5, $row); $description = trim($description->getValue());
		// 					$comment = $worksheet->getCellByColumnAndRow(6, $row); $comment = trim($comment->getValue());
		// 					$amount = $worksheet->getCellByColumnAndRow(7, $row); $amount = trim($amount->getValue());
		// 					$exp_date = explode('/',$date);
		// 					$data['receipt_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
		// 					$data['debit_nominal'] = $debit;
		// 					$data['credit_nominal'] = $credit;
		// 					$data['client_code'] = $client;
		// 					$data['credit_description'] = $description;
		// 					$data['comment'] = $comment;
		// 					$data['amount'] = $amount;
		// 					$data['imported'] = 1;
		// 					\App\Models\receipts::insert($data);
		// 				}
		// 			}
		// 			else{
		// 				return redirect('user/invoice_management')->with('message_import', 'Import Failed! Invalid Import File');
		// 			}
		// 		}
		// 		if($height >= $highestRow)
		// 		{
		// 			return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
		// 		}
		// 		else{
		// 			return redirect('user/receipt_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1');
		// 		}
		// 	}
		// }
	}
	public function import_new_receipts_one(Request $request)
	{
		$name = $request->get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = IOFactory::load($filepath);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			
			$nrColumns = ord($highestColumn) - 64;
			$round = $request->get('round');
			$last_height = $request->get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$date = $worksheet->getCellByColumnAndRow(1, $row); $date = trim($date->getValue());
				$debit = $worksheet->getCellByColumnAndRow(2, $row); $debit = trim($debit->getValue());
				$credit = $worksheet->getCellByColumnAndRow(3, $row); $credit = trim($credit->getValue());
				$client = $worksheet->getCellByColumnAndRow(4, $row); $client = trim($client->getValue());
				$description = $worksheet->getCellByColumnAndRow(5, $row); $description = trim($description->getValue());
				$comment = $worksheet->getCellByColumnAndRow(6, $row); $comment = trim($comment->getValue());
				$amount = $worksheet->getCellByColumnAndRow(7, $row); $amount = trim($amount->getValue());
				$exp_date = explode('/',$date);
				$data['receipt_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				$data['client_code'] = $client;
				$data['credit_description'] = $description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;
				$data['statement'] = 1;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\receipts::insert($data);
			}
		}
		if($height >= $highestRow)
		{
			return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
		}
		else{
			return redirect('user/receipt_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1');
		}
	}
	public function change_receipt_status(Request $request)
	{
		$receipt_id = $request->get('receipt_id');
		$data['status'] = 1;
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->update($data);
	}
	public function load_receipt(Request $request)
	{
		$filter = $request->get('filter');
		$expfrom = explode('/',$request->get('from'));
		$expto = explode('/',$request->get('to'));
		$client = $request->get('client');
		$debit = $request->get('debit');
		$credit = $request->get('credit');		
		if(count($expfrom) > 2)
		{
			$from = $expfrom[2].'-'.$expfrom[1].'-'.$expfrom[0];
		}
		if(count($expto) > 2)
		{
			$to = $expto[2].'-'.$expto[1].'-'.$expto[0];
		}
		if($filter == "1")
		{
			$current_year = date('Y');
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','like',$current_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "2")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','>=',$from)->where('receipt_date','<=',$to)->where('imported',0)->get();
		}
		elseif($filter == "3")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code','like','%'.$client.'%')->where('imported',0)->get();
		}
		elseif($filter == "4")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal',$debit)->where('imported',0)->get();
		}
		elseif($filter == "5")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal',$credit)->where('imported',0)->get();
		}
		elseif($filter == "6")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->orderBy('id','desc')->limit(300)->get();
		}
		elseif($filter == "7")
		{
			$current_year = date('Y');
			$previous_year = $current_year-1;			
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','like',$previous_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "8")
		{		
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->get();
		}
		$output = '';
		if(is_countable($get_receipts) && count($get_receipts) > 0)
		{
			foreach($get_receipts as $receipt)
			{
				/*if($receipt->status == 1)
				{
					$font_color = 'color:#f00;font-weight:600';
				}
				elseif($receipt->hold_status == 0)
				{
					$font_color = 'color:blue;font-weight:600';
				}
				else{
					$font_color = '';
				}*/
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				$nominal_code = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
					$nominal_code = $get_details->code;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$receipt->id.'" data-nominal="'.$nominal_code.'" style="color:#f00">Outstanding</a>';
					$delete_icon = '<a href="javascript:" class="fa fa-trash delete_outstading" data-element="'.$receipt->id.'" title="Delete Receipt" type="1"></a>';
				}
				else
				{
					if($receipt->journal_id != 0){
						$hold_status = '<a href="javascript:" class="unhold_receipt" data-element="'.$receipt->id.'" data-nominal="'.$nominal_code.'" style="color:blue">Reconciled</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
					else{
						$hold_status = '<a href="javascript:" class="unhold_receipt" data-element="'.$receipt->id.'">Cleared</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
				}
				if($receipt->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				$debit_nom = substr($receipt->debit_nominal, 0, 3);
				$credit_nom = substr($receipt->credit_nominal, 0, 3);
				if($debit_nom == $credit_nom && $debit_nom == '771') {
				    $ticon = '<spam class="ticon_span" style="display:none">1</spam><a href="javascript:" class="bank_transfer_button" data-element="'.$receipt->id.'"><img src="'.url('public/assets/images/t-icon.png').'" class="bank_transfer_button" data-element="'.$receipt->id.'" style="width:20px" title="Bank Transfer"/></a>';
				}else {
				    $ticon = '<spam class="ticon_span" style="display:none">0</spam><a href="javascript:" class="ticon" data-element="'.$receipt->id.'"><img src="'.url('public/assets/images/t-icon-disable.png').'" style="width:20px" /></a>';
				}
				if($receipt->batch_id != 0){
					$batch_id = $receipt->batch_id;
				} else{
					$batch_id = '';
				}
				if($receipt->statement == 1){
					$statement = 'Yes';
					$statement_color = 'font-weight:700;color:#26BD67';
				}else{
					$statement = 'No';
					$statement_color = 'font-weight:700;color:#f00';
				}
				$output.='<tr class="receipt_payment_'.$receipt->id.'">
					<td style=""><spam class="date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					<td class="debit_sort_val" style="">'.$debit_nominal.'</td>
					<td class="credit_sort_val" style="">'.$receipt->credit_nominal.'</td>
					<td class="client_sort_val" style="">'.$receipt->client_code.'</td>	
					<td class="des_sort_val" style="">'.$receipt->credit_description.'</td>
					<td class="comment_sort_val" style="">'.$receipt->comment.'</td>
					<td style=";text-align:right"><spam class="amount_sort_val" style="display:none">'.$receipt->amount.'</spam>'.number_format_invoice_empty($receipt->amount).'</td>	
					<td><a href="javascript:" class="show_batch_overlay batch_sort_val" data-element="'.$batch_id.'">'.$batch_id.'</a></td>	
					<td style=";text-align:right">'.$journal_id_view.'</td>	
					<td class="statement_sort_val" style="'.$statement_color.'">'.$statement.'</td>	
					<td>'.$hold_status.'</td>	
					<td>
					    '.$ticon.'&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-edit edit_receipt" title="Edit Receipt" data-element="'.$receipt->id.'"></a>&nbsp;&nbsp;
						'.$delete_icon.'
					</td>	
				</tr>';
			}
		}
		else
		{
			$output.='<tr>
                  <td colspan="10" style="text-align: center">No Records Found</td>
                </tr>';
		}
		echo $output;
	}
	public function load_all_receipt(Request $request)
	{
		$page = $request->get('page');
		$offset = (($page - 1) * 500);
		//$get_receipts = DB::select('SELECT * from `receipts` WHERE imported = "0" ORDER BY UNIX_TIMESTAMP(`receipt_date`) ASC OFFSET '.$offset.' LIMIT 500');
		$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->orderByRaw('UNIX_TIMESTAMP(`receipt_date`) ASC')->offset($offset)->limit(500)->get();
		$output = '';
		if(($get_receipts))
		{
			foreach($get_receipts as $receipt)
			{
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$receipt->id.'" data-nominal="'.$get_details->code.'" style="color:#f00">Outstanding</a>';
					$delete_icon = '<a href="javascript:" class="fa fa-trash delete_outstading" data-element="'.$receipt->id.'" title="Delete Receipt" type="1"></a>';
				}
				else
				{
					if($receipt->journal_id != 0){
						$hold_status = '<a href="javascript:" class="unhold_receipt" data-element="'.$receipt->id.'" data-nominal="'.$get_details->code.'" style="color:blue">Reconciled</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
					else{
						$hold_status = '<a href="javascript:" class="unhold_receipt" data-element="'.$receipt->id.'">Cleared</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
				}
				if($receipt->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				$debit_nom = substr($receipt->debit_nominal, 0, 3);
				$credit_nom = substr($receipt->credit_nominal, 0, 3);
				if($debit_nom == $credit_nom && $debit_nom == '771') {
				    $ticon = '<spam class="ticon_span" style="display:none">1</spam><a href="javascript:" class="bank_transfer_button" data-element="'.$receipt->id.'"><img src="'.url('public/assets/images/t-icon.png').'" class="bank_transfer_button" data-element="'.$receipt->id.'" style="width:20px" title="Bank Transfer"/></a>';
				}else {
				    $ticon = '<spam class="ticon_span" style="display:none">0</spam><a href="javascript:" class="ticon" data-element="'.$receipt->id.'"><img src="'.url('public/assets/images/t-icon-disable.png').'" style="width:20px" /></a>';
				}
				if($receipt->batch_id != 0){
					$batch_id = $receipt->batch_id;
				} else{
					$batch_id = '';
				}
				if($receipt->statement == 1){
					$statement = 'Yes';
					$statement_color = 'font-weight:700;color:#26BD67';
				}else{
					$statement = 'No';
					$statement_color = 'font-weight:700;color:#f00';
				}
				$output.='<tr class="receipt_payment_'.$receipt->id.'">
					<td style=""><spam class="date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					<td class="debit_sort_val" style="">'.$debit_nominal.'</td>
					<td class="credit_sort_val" style="">'.$receipt->credit_nominal.'</td>
					<td class="client_sort_val" style="">'.$receipt->client_code.'</td>	
					<td class="des_sort_val" style="">'.$receipt->credit_description.'</td>
					<td class="comment_sort_val" style="">'.$receipt->comment.'</td>
					<td style=";text-align:right"><spam class="amount_sort_val" style="display:none">'.$receipt->amount.'</spam>'.number_format_invoice_empty($receipt->amount).'</td>	
					<td><a href="javascript:" class="show_batch_overlay batch_sort_val" data-element="'.$batch_id.'">'.$batch_id.'</a></td>	
					<td style=";text-align:right">'.$journal_id_view.'</td>	
					<td class="statement_sort_val" style="'.$statement_color.'">'.$statement.'</td>	
					<td>'.$hold_status.'</td>	
					<td>
					    '.$ticon.'&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-edit edit_receipt" title="Edit Receipt" data-element="'.$receipt->id.'"></a>&nbsp;&nbsp;
						'.$delete_icon.'
					</td>	
				</tr>';
			}
		}
		else
		{
			$output.='<tr>
                  <td colspan="10" style="text-align: center">No Records Found</td>
                </tr>';
		}
		$data['output'] = $output;
		$data['total_counts'] = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->count();
		$data['total_pages'] = ceil($data['total_counts'] / 500);
		echo json_encode($data);
	}
	public function show_batch_receipt_informations(Request $request)
	{
		$batch_id = $request->get('batch_id');
		$outstanding_trans 	= 0;
		$reconciled_trans 	= 0;
		$cleared_trans 	= 0;
		$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('batch_id',$batch_id)->where('imported',0)->get();
		$output = '';
		if(($get_receipts))
		{
			foreach($get_receipts as $receipt)
			{
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = 'Outstanding';
					$outstanding_trans++;
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = 'Reconciled';
					$reconciled_trans++;
				}
				else{
					$hold_status = 'Cleared';
					$cleared_trans++;
				}
				if($receipt->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer journal_id_batch_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				if($receipt->statement == 1){
					$statement = 'Yes';
					$statement_color = 'font-weight:700;color:#26BD67';
				}else{
					$statement = 'No';
					$statement_color = 'font-weight:700;color:#f00';
				}
				$output.='<tr class="receipt_payment_tr_'.$receipt->id.'">
					<td style=""><spam class="date_batch_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					<td class="debit_batch_sort_val" style="">'.$debit_nominal.'</td>
					<td class="credit_batch_sort_val" style="">'.$receipt->credit_nominal.'</td>
					<td class="client_batch_sort_val" style="">'.$receipt->client_code.'</td>	
					<td class="des_batch_sort_val" style="">'.$receipt->credit_description.'</td>
					<td class="comment_batch_sort_val" style="">'.$receipt->comment.'</td>
					<td style="text-align:right"><spam class="amount_batch_sort_val" style="display:none">'.$receipt->amount.'</spam>'.number_format_invoice_empty($receipt->amount).'</td>
					<td style=";text-align:right">'.$journal_id_view.'</td>	
					<td class="statement_batch_sort_val" style="'.$statement_color.'">'.$statement.'</td>
					<td class="status_batch_sort_val">'.$hold_status.'</td>		
				</tr>';
			}
		}
		else
		{
			$output.='<tr>
                  <td colspan="8" style="text-align: center">No Records Found</td>
                </tr>';
		}
		$batch_details = \App\Models\receiptBatches::where('practice_code',Session::get('user_practice_code'))->where('id',$batch_id)->first();
		$dataval['imported_date'] 		= date('d-M-Y @ H:i', strtotime($batch_details->created_at));
		$dataval['outstanding_trans'] 	= $outstanding_trans;
		$dataval['reconciled_trans'] 	= $reconciled_trans;
		$dataval['cleared_trans'] 		= $cleared_trans;
		$dataval['output'] 				= $output;
		echo json_encode($dataval);
	}
	public function delete_outstanding_receipts_from_batch(Request $request)
	{
		$batch_id = $request->get('batch_id');
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('batch_id',$batch_id)->where('hold_status',0)->where('imported',0)->delete();
		$data['batch_delete'] = 1;
		$data['delete_datetime'] = date('Y-m-d H:i:s');
		\App\Models\receiptBatches::where('id',$batch_id)->update($data);
	}
	public function show_receipts_batch_list(Request $request)
	{
		$batches = \App\Models\receiptBatches::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		if(($batches)) {
			foreach($batches as $batch){
				//$trans_count = \App\Models\receipts::where('batch_id',$batch->id)->where('imported',0)->count();
				$batch_delete = 'N';
				$delete_date = '-';
				$delete_time = '-';
				if($batch->batch_delete == 1) { 
					$batch_delete = 'Y';
					$delete_date = date('d-M-Y', strtotime($batch->delete_datetime));
					$delete_time = date('H:i', strtotime($batch->delete_datetime)); 
				}
				$output.='<tr>
					<td><a href="javascript:" class="show_batch_overlay batch_sort_val" data-element="'.$batch->id.'">'.$batch->id.'</a></td>
					<td>'.$batch->transaction_count.'</td>
					<td>'.date('d-M-Y', strtotime($batch->created_at)).'</td>
					<td>'.date('H:i', strtotime($batch->created_at)).'</td>
					<td style="text-align:center">'.$batch_delete.'</td>
					<td>'.$delete_date.'</td>
					<td>'.$delete_time.'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td>No Records Found</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		}
		echo $output;
	}
	public function change_to_unhold(Request $request)
	{
		$id = $request->get('id');
		$data['hold_status'] = 1;
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->update($data);
	}
	public function add_receipt_export_csv(Request $request)
	{
		$ids = $request->get('ids');
		$expids = explode(',',$ids);
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_add_receipt_list.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		fputcsv($file, $columns);
		if($ids != "")
		{
			foreach($expids as $id)
			{
				$get_details = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$id)->first();
				$get_debit_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$get_details->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_debit_details))
				{
					$debit_nominal = $get_debit_details->code;
				}
				$columns_2 = array(date('d/m/Y', strtotime($get_details->receipt_date)),$debit_nominal,$get_details->credit_nominal,$get_details->client_code,$get_details->credit_description,$get_details->comment,number_format_invoice($get_details->amount),'','Outstanding');
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function export_load_receipt(Request $request)
	{
		$filter = $request->get('filter');
		$expfrom = explode('/',$request->get('from'));
		$expto = explode('/',$request->get('to'));
		$client = $request->get('client');
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		if(count($expfrom) > 2)
		{
			$from = $expfrom[2].'-'.$expfrom[1].'-'.$expfrom[0];
		}
		if(count($expto) > 2)
		{
			$to = $expto[2].'-'.$expto[1].'-'.$expto[0];
		}
		if($filter == "1")
		{
			$current_year = date('Y');
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','like',$current_year.'%')->get();
		}
		elseif($filter == "2")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','>=',$from)->where('receipt_date','<=',$to)->get();
		}
		elseif($filter == "3")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('client_code','like','%'.$client.'%')->get();
		}
		elseif($filter == "4")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal',$debit)->get();
		}
		elseif($filter == "5")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal',$credit)->get();
		}
		elseif($filter == "6")
		{
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->orderBy('id','desc')->limit(300)->get();
		}
		elseif($filter == "7")
		{
			$current_year = date('Y');
			$previous_year = $current_year-1;			
			$get_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('receipt_date','like',$previous_year.'%')->where('imported',0)->get();
		}
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_receipt_list.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		fputcsv($file, $columns);
		$output = '';
		if(($get_receipts))
		{
			foreach($get_receipts as $receipt)
			{
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}
				$columns_2 = array(date('d/m/Y', strtotime($receipt->receipt_date)),$debit_nominal,$receipt->credit_nominal,$receipt->client_code,$receipt->credit_description,$receipt->comment,number_format_invoice($receipt->amount),$receipt->journal_id,$hold_status);
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function check_import_csv(Request $request)
	{
		$get_imported_receipts = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->get();
		if(($get_imported_receipts))
		{
		  \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->delete();
		}
		if($_FILES['new_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['new_file']['tmp_name'];
			$name=time().'_'.$_FILES['new_file']['name'];
			$errorlist = array();
			$output = '';
			$error_code = "";
			$k = 0;
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$chunk_size = 100;
				$csv_data = array_map('str_getcsv', file($filepath));
				$chunked_data = array_chunk($csv_data, $chunk_size);
				$total_count = count($chunked_data);
				$rowvalue = 2;
				foreach($chunked_data[0] as $key => $row)
				{
					if($key == 0) {
						$date_header = trim($row[0]);
						$debit_header = trim($row[1]);
						$credit_header = trim($row[2]);
						$client_header = trim($row[3]);
						$comment_header = trim($row[5]);
						$amount_header = trim($row[6]);	
						if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client Code" && $comment_header == "Comment" && $amount_header == "Amount")
						{
						}
						else{
							$error_code = "1";
							echo json_encode(array("error_code" => $error_code, "output" =>$output, "import_type_new" => "0"));
							exit;
						}
					}
					else{
						$date = trim($row[0]);
						$debit = trim($row[1]);
						$credit = trim($row[2]);
						$client = trim($row[3]);
						$credit_description = trim($row[4]);
						$comment = trim($row[5]);
						$amount = trim($row[6]);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$amount = str_replace(",","",$amount);
						$exp_date = explode('/',$date);
						$dateval = '';
						if(count($exp_date) == 3)
				        {
							$dateval = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
						}
						$description = '';
						if($credit_description != "")
						{
							$description = $credit_description;
						}
						else{
							if($credit == "712" || $credit == "813A")
							{
								$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
								if(($client_details))
								{
									$description = $client_details->company.' - '.$client_details->client_id;
								}
							}
							else{
								$get_nominal_code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
								if(($get_nominal_code_description))
								{
									$description = $get_nominal_code_description->description;
								}
							}
						}
						$data['receipt_date'] = $dateval;
						$data['debit_nominal'] = $debit;
						$data['credit_nominal'] = $credit;
						if($credit == "712" || $credit == "813A")
				        {
							$data['client_code'] = $client;
						}else{
							$data['client_code'] = '';
						}
						$data['credit_description'] = $description;
						$data['comment'] = $comment;
						$data['amount'] = $amount;
						$data['imported'] = 1;
						$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
						$debit_nominal = '';
						if(($get_details))
						{
							$debit_nominal = $get_details->code;
						}
						$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
						$credit_nominal = '';
						if(($get_details))
						{
							$credit_nominal = $get_details->code;
						}
						$get_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
						$client_name = '';
						if(($get_details))
						{
							$client_name = $get_details->client_id;
						}
						$i = 0;
						$j = 0;
						$k = 0;
						$error_text = '';
						if($date == "" || $debit == "" || $credit == "" || ($credit == "813A" && $client == "") || ($credit == "813" && $client == "") || $amount == "")
						{
							$j = 1;
						}
						if($date != "")
				        {
				        	$explodedate = explode('/',$date);
				        	if(count($explodedate) != 3)
				        	{
				        		$i = $i + 1;
				        		$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Date Format is wrong on Line '.$rowvalue.'<br/>';
				        	}
				        }
				        if($debit_nominal == "")
				        {
				          $i = $i + 1;
				          $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Debit Nominal is Invalid on Line '.$rowvalue.'<br/>';
				        }
				        if($credit_nominal == "")
				        {
				          $i = $i + 1;
				          $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Credit Nominal is Invalid on Line '.$rowvalue.'<br/>';
				        }
				        if($credit == "712" || $credit == "813A")
				        {
				          if($client_name == "")
				          {
				            $i = $i + 1;
				            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Client Code is Invalid on Line '.$rowvalue.'<br/>';
				          }
				        }
				        $amount_val = $amount;
				        if($amount != "")
				        {
				        	if(is_numeric($amount) != 1)
				        	{
				        		$i = $i + 1;
				        		$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Invalid Numeric value for Amount in Line '.$rowvalue.'<br/>';
				        	}
				        	else{
				        		//$data['amount'] = number_format_invoice_empty($amount);
				        		$amount_val = number_format_invoice_empty($amount);
				        	}
				        }
				        $data['statement'] = 1;
				        $data['practice_code'] = Session::get('user_practice_code');
						\App\Models\receipts::insert($data);
						if($j > 0)
						{
							$data['status'] = 1;
							$font_color = 'color:#f00;font-weight:600';
							$error_code = "3";
							$error_cls = "error_tr";
							$error_text = '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Line '.$rowvalue.' Does not have sufficient fields<br/>';
							$k++;
						}
						else{
							if($i > 0 && $k == 0)
							{
								$data['status'] = 1;
								$font_color = 'color:#f00;font-weight:600';
								$error_code = "3";
								$error_cls = "error_tr";
							}
							else
							{
								$data['status'] = 0;
								$font_color = 'color:green;font-weight:600';
								$error_code = "0";
								$error_cls = "";
								$error_text = '';
							}
						}
						if($error_text == "")
						{
							$error_html = '';
						}
						else{
							$error_html = $error_text;
						}
						$output.='<tr class="'.$error_cls.'">
							<td style="'.$font_color.'">'.$date.'</td>
							<td style="'.$font_color.'">'.$debit.'</td>
							<td style="'.$font_color.'">'.$credit.'</td>
							<td style="'.$font_color.'">'.$client.'</td>  
							<td style="'.$font_color.'">'.$credit_description.'</td>
							<td style="'.$font_color.'">'.$comment.'</td>  
							<td style="text-align:right;'.$font_color.'">'.$data['amount'].'</td>
							<td style="vertical-align:middle;color:#f00">'.$error_html.'</td>
						</tr>';
						$rowvalue++;
					}
				}
				if($total_count > 1){
					echo json_encode(array("error_code" => $error_code,"output" => $output,"filename" => $name, "round" => "1","total_count" => $total_count, "import_type_new" => "1", "kval" => $k));
				}
				else{
					echo json_encode(array("error_code" => $error_code,"output" => $output, "import_type_new" => "0"));
				}
			}
			else{
				$error_code = "2";
				echo json_encode(array("error_code" => $error_code, "output" =>$output, "import_type_new" => "0"));
			}
		}
	}
	public function check_import_csv_one(Request $request)
	{
		$name = $request->get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$output = '';
		$error_code = "";
		$k = $request->get('kval');
		$round = $request->get('round');
		$total_count = $request->get('total_count');
		$chunk_size = 100;
		$csv_data = array_map('str_getcsv', file($filepath));
		$chunked_data = array_chunk($csv_data, $chunk_size);
		$total_count = count($chunked_data);
		$rowvalue = ($round * 100) + 1;
		foreach($chunked_data[$round] as $key => $row)
		{
			$date = trim($row[0]);
			$debit = trim($row[1]);
			$credit = trim($row[2]);
			$client = trim($row[3]);
			$credit_description = trim($row[4]);
			$comment = trim($row[5]);
			$amount = trim($row[6]);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$amount = str_replace(",","",$amount);
			$exp_date = explode('/',$date);
			$dateval = '';
			if(count($exp_date) == 3)
	        {
				$dateval = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
			}
			$description = '';
			if($credit_description != "")
			{
				$description = $credit_description;
			}
			else{
				if($credit == "712" || $credit == "813A")
				{
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
					if(($client_details))
					{
						$description = $client_details->company.' - '.$client_details->client_id;
					}
				}
				else{
					$get_nominal_code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
					if(($get_nominal_code_description))
					{
						$description = $get_nominal_code_description->description;
					}
				}
			}
			$data['receipt_date'] = $dateval;
			$data['debit_nominal'] = $debit;
			$data['credit_nominal'] = $credit;
			if($credit == "712" || $credit == "813A")
	        {
				$data['client_code'] = $client;
			}else{
				$data['client_code'] = '';
			}
			$data['credit_description'] = $description;
			$data['comment'] = $comment;
			$data['amount'] = $amount;
			$data['imported'] = 1;
			$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
			$debit_nominal = '';
			if(($get_details))
			{
				$debit_nominal = $get_details->code;
			}
			$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
			$credit_nominal = '';
			if(($get_details))
			{
				$credit_nominal = $get_details->code;
			}
			$get_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
			$client_name = '';
			if(($get_details))
			{
				$client_name = $get_details->client_id;
			}
			$i = 0;
			$j = 0;
			$k = 0;
			$error_text = '';
			if($date == "" || $debit == "" || $credit == "" || ($credit == "813A" && $client == "") || ($credit == "813" && $client == "") || $amount == "")
			{
				$j = 1;
			}
			if($date != "")
	        {
	        	$explodedate = explode('/',$date);
	        	if(count($explodedate) != 3)
	        	{
	        		$i = $i + 1;
	        		$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Date Format is wrong on Line '.$rowvalue.'<br/>';
	        	}
	        }
	        if($debit_nominal == "")
	        {
	          $i = $i + 1;
	          $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Debit Nominal is Invalid on Line '.$rowvalue.'<br/>';
	        }
	        if($credit_nominal == "")
	        {
	          $i = $i + 1;
	          $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Credit Nominal is Invalid on Line '.$rowvalue.'<br/>';
	        }
	        if($credit == "712" || $credit == "813A")
	        {
	          if($client_name == "")
	          {
	            $i = $i + 1;
	            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Client Code is Invalid on Line '.$rowvalue.'<br/>';
	          }
	        }
	        $amount_val = $amount;
	        if($amount != "")
	        {
	        	if(is_numeric($amount) != 1)
	        	{
	        		$i = $i + 1;
	        		$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Invalid Numeric value for Amount in Line '.$rowvalue.'<br/>';
	        	}
	        	else{
	        		//$data['amount'] = number_format_invoice_empty($amount);
	        		$amount_val = number_format_invoice_empty($amount);
	        	}
	        }
	        $data['statement'] = 1;
	        $data['practice_code'] = Session::get('user_practice_code');
			\App\Models\receipts::insert($data);
			if($j > 0)
			{
				$data['status'] = 1;
				$font_color = 'color:#f00;font-weight:600';
				$error_code = "3";
				$error_cls = "error_tr";
				$error_text = '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Line '.$rowvalue.' Does not have sufficient fields<br/>';
				$k++;
			}
			else{
				if($i > 0 && $k == 0)
				{
					$data['status'] = 1;
					$font_color = 'color:#f00;font-weight:600';
					$error_code = "3";
					$error_cls = "error_tr";
				}
				else
				{
					$data['status'] = 0;
					$font_color = 'color:green;font-weight:600';
					$error_code = "0";
					$error_cls = "";
					$error_text = '';
				}
			}
			if($error_text == "")
			{
				$error_html = '';
			}
			else{
				$error_html = $error_text;
			}
			$output.='<tr class="'.$error_cls.'">
				<td style="'.$font_color.'">'.$date.'</td>
				<td style="'.$font_color.'">'.$debit.'</td>
				<td style="'.$font_color.'">'.$credit.'</td>
				<td style="'.$font_color.'">'.$client.'</td>  
				<td style="'.$font_color.'">'.$credit_description.'</td>
				<td style="'.$font_color.'">'.$comment.'</td>  
				<td style="text-align:right;'.$font_color.'">'.$data['amount'].'</td>
				<td style="vertical-align:middle;color:#f00">'.$error_html.'</td>
			</tr>';
			$rowvalue++;
		}
		$next_round = $round + 1;
		if($total_count > $next_round){
			echo json_encode(array("error_code" => $error_code,"output" => $output,"filename" => $name, "round" => $next_round,"total_count" => $total_count, "import_type_new" => "1", "kval" => $k));
		}
		else{
			echo json_encode(array("error_code" => $error_code,"output" => $output, "import_type_new" => "0"));
		}
	}
	public function payment_receipdt_delete_outstading(Request $request){
		$id = $request->get('id');
		$type = $request->get('type');
		if($type == '1'){
			$receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
			if(($receipt)){
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}
				if($receipt->journal_id != 0){
					$journal_id_view = $receipt->journal_id;
				}
				else{
					$journal_id_view = '';
				}
				$output ='
					<tr>
						<td style="border:0px">Date</td>
						<td style="border:0px"><spam class="date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					</tr>
					<tr>
						<td>Debit Nominal & Description</td>
						<td>'.$debit_nominal.'</td>
					</tr>
					<tr>
						<td>Credit Nominal</td>
						<td>'.$receipt->credit_nominal.'</td>
					</tr>
					<tr>
						<td>Client Code</td>
						<td>'.$receipt->client_code.'</td>	
					</tr>
					<tr>
						<td>Credit Nominal Description</td>
						<td>'.$receipt->credit_description.'</td>
					</tr>
					<tr>
						<td>Comment</td>
						<td>'.$receipt->comment.'</td>
					</tr>
					<tr>
						<td>Amount</td>
						<td>'.number_format_invoice_empty($receipt->amount).'</td>
					</tr>
					<tr>
						<td>Journal ID</td>
						<td>'.$journal_id_view.'</td>	
					</tr>
					<tr>
						<td>Status</td>
						<td>'.$hold_status.'</td>							
					</tr>';
			}
			$title = 'Delete Receipt Detail Summary';
			$message = 'ARE YOU SURE YOU WANT TO DELETE THIS RECEIPT?';
		}
		else{
			$payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->first();
			if(($payment)){
				$get_details = payment\App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($payment->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}
				if($payment->journal_id != 0){
					$journal_id_view = $payment->journal_id;
				}
				else{
					$journal_id_view = '';
				}
				$output='
				<tr>
					<td style="border:0px">Date</td>
					<td style="border:0px">'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
				</tr>
				<tr>
					<td>Debit Nominal</td>
					<td>'.$payment->debit_nominal.'</td>
				</tr>
				<tr>
					<td>Credit Nominal & Description</td>
					<td>'.$credit_nominal.'</td>
				</tr>
				<tr><td>Client/Supplier Code</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$output.='<td class="client_sort_val" style="">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_sort_val" style="">'.$payment->client_code.'</td>';
					}
					$output.='</tr>
					<tr>
						<td>Debit Nominal Description</td>
						<td>'.$payment->debit_description.'</td>
					</tr>
					<tr>
						<td>Comment</td>
						<td>'.$payment->comment.'</td>	
					</tr>
					<tr>
						<td>Amount</td>
						<td>'.number_format_invoice_empty($payment->amount).'</td>	
					</tr>
						<td>Journal ID</td>
						<td>'.$journal_id_view.'</td>	
					<tr>
						<td>Status</td>
						<td>'.$hold_status.'</td>	
					</tr>
				</tr>
				';
			}
			$title = 'Delete Payment Detail Summary';
			$message = 'ARE YOU SURE YOU WANT TO DELETE THIS PAYMENT?';
		}
		echo json_encode(array('id' => $id, 'type' => $type, 'title' => $title, 'output' => $output, 'message' => $message ));	
	}
	public function outstanding_payment_receipt_download(Request $request){
		$id = $request->get('id');
		$type = $request->get('type');
		$style = 'style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left; border-right: 1px solid #c3c3c3; border-bottom: 1px solid #c3c3c3;"';
		if($type == '1'){
			$receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
			if(($receipt)){
				$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}
				if($receipt->journal_id != 0){
					$journal_id_view = $receipt->journal_id;
				}
				else{
					$journal_id_view = '';
				}
				$output ='
					<div style="float:left; width:100%; padding: 8px; text-align:center; font-family: Roboto, sans-serif; ">
						<h4>Deleted Receipt Detailed Summary</h4>
					</div>
					<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; width: 100%; border-top: 1px solid #c3c3c3; border-left: 1px solid #c3c3c3;">
					<tr>
						<td '.$style.'>Date</td>
						<td '.$style.'><spam class="date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					</tr>
					<tr>
						<td '.$style.'>Debit Nominal & Description</td>
						<td '.$style.'>'.$debit_nominal.'</td>
					</tr>
					<tr>
						<td '.$style.'>Credit Nominal</td>
						<td '.$style.'>'.$receipt->credit_nominal.'</td>
					</tr>
					<tr>
						<td '.$style.'>Client Code</td>
						<td '.$style.'>'.$receipt->client_code.'</td>	
					</tr>
					<tr>
						<td '.$style.'>Credit Nominal Description</td>
						<td '.$style.'>'.$receipt->credit_description.'</td>
					</tr>
					<tr>
						<td '.$style.'>Comment</td>
						<td '.$style.'>'.$receipt->comment.'</td>
					</tr>
					<tr>
						<td '.$style.'>Amount</td>
						<td '.$style.'><spam class="amount_sort_val" style="display:none">'.$receipt->amount.'</spam>'.number_format_invoice_empty($receipt->amount).'</td>
					</tr>
					<tr>
						<td '.$style.'>Journal ID</td>
						<td '.$style.'>'.$journal_id_view.'</td>	
					</tr>
					<tr>
						<td '.$style.'>Status</td>
						<td '.$style.'>'.$hold_status.'</td>							
					</tr></table>';
			}
			$filename = 'Deleted Receipt Detail Summary';
		}
		else{
			$payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->first();
			if(($payment)){
				$get_details = payment\App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($payment->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}
				if($payment->journal_id != 0){
					$journal_id_view = $payment->journal_id;
				}
				else{
					$journal_id_view = '';
				}
				$output='
				<div style="float:left; width:100%; padding: 8px; text-align:center; font-family: Roboto, sans-serif; ">
						<h4>Deleted Payment Detailed Summary</h4>
					</div>
				<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; width: 100%; border-top: 1px solid #c3c3c3; border-left: 1px solid #c3c3c3;">
				<tr>
					<td '.$style.'>Date</td>
					<td '.$style.'>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
				</tr>
				<tr>
					<td '.$style.'>Debit Nominal</td>
					<td '.$style.'>'.$payment->debit_nominal.'</td>
				</tr>
				<tr>
					<td '.$style.'>Credit Nominal & Description</td>
					<td '.$style.'>'.$credit_nominal.'</td>
				</tr>
				<tr><td '.$style.'>Client/Supplier Code</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$output.='<td '.$style.'>'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td '.$style.'>'.$payment->client_code.'</td>';
					}
					$output.='</tr>
					<tr>
						<td '.$style.'>Debit Nominal Description</td>
						<td '.$style.'>'.$payment->debit_description.'</td>
					</tr>
					<tr>
						<td '.$style.'>Comment</td>
						<td '.$style.'>'.$payment->comment.'</td>	
					</tr>
					<tr>
						<td '.$style.'>Amount</td>
						<td '.$style.'>'.number_format_invoice_empty($payment->amount).'</td>	
					</tr>
					<tr>
						<td '.$style.'>Journal ID</td>
						<td '.$style.'>'.$journal_id_view.'</td>
					</tr>
					<tr>
						<td '.$style.'>Status</td>
						<td '.$style.'>'.$hold_status.'</td>	
					</tr>
				</table>
				';
			}	
			$filename = 'Deleted Payment Detail Summary';
		}
		$time=time();
		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('public/papers/'.$filename.'_'.$time.'.pdf');
	    $filename_download = $filename.'_'.$time.'.pdf';
	    echo $filename_download;
	}
	public function outstanding_payment_receipt_delete(Request $request){
		$id = $request->get('id');
		$type = $request->get('type');
		if($type == 1){
			$receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->delete();
			$message = 'Receipt Detail Summary was successfully deleted';
		}
		else{
			$payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->delete();
			$message = 'Payment Detail Summary was successfully deleted';
		}
		echo json_encode(array('message' => $message));
	}
	public function get_receipt_bank_transfer_details(Request $request)
	{
	    $id = $request->get('receipt_id');
	    $receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id', $id)->first();
	    $get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
		$debit_nominal = '';
		if(($get_details))
		{
			$debit_nominal = $get_details->code.' - '.$get_details->description;
		}
		if($receipt->journal_id != 0){
			$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a>';
		}
		else{
			$journal_id_view = 'N/A';
		}
        if($receipt->client_code == ""){
	        $na = 'N/A';
	    }
	    else{
	        $na = $receipt->client_code;
	    }
		$output ='
		    <tr>
              <td style="width:30%;border:0px">Date:</td>
              <td style="border:0px" class="bank_transfer_date">'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
            </tr>
            <tr>
              <td style="border:0px">Debit Nominal:</td>
              <td style="border:0px" class="bank_transfer_debit">'.$debit_nominal.' <a href="javascript:" class="fa fa-lock" style="font-weight:800;color:green"></a></td>
            </tr>
            <tr>
              <td style="border:0px">Credit Nominal:</td>
              <td style="border:0px" class="bank_transfer_credit">'.$receipt->credit_nominal.'</td>
            </tr>
            <tr>
              <td style="border:0px">Client / Supplier Code:</td>
              <td style="border:0px" class="bank_transfer_client">'.$na.'</td>
            </tr>
            <tr>
              <td style="border:0px">Credit Nominal Description:</td>
              <td style="border:0px" class="bank_transfer_credit_des">'.$receipt->credit_description.'</td>
            </tr>
            <tr>
              <td style="border:0px">Comment:</td>
              <td style="border:0px" class="bank_transfer_comment">'.$receipt->comment.'</td>
            </tr>
            <tr>
              <td style="border:0px">Amount:</td>
              <td style="border:0px" class="bank_transfer_amount">'.number_format_invoice_empty($receipt->amount).'</td>
            </tr>
            <tr>
              <td style="border:0px">Journal ID:</td>
              <td style="border:0px" class="bank_transfer_journal">'.$journal_id_view.'</td>
            </tr>';
            echo $output;
	}
	public function prepare_receipt_payment_entry(Request $request)
	{
		$receipt_id = $request->get('receipt_id');
		$data['credit_nominal'] = '770';
		$receipt_detail = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->first();
		\App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->update($data);
		$datapayment['payment_date'] = $receipt_detail->receipt_date;
		$datapayment['debit_nominal'] = '770';
		$datapayment['credit_nominal'] = $receipt_detail->credit_nominal;
		$datapayment['client_code'] = '';
		$datapayment['supplier_code'] = 0;
		$datapayment['debit_description'] = 'Bank Transfer Holding Account';
		$datapayment['comment'] = $receipt_detail->comment;
		$datapayment['amount'] = $receipt_detail->amount;
		$datapayment['practice_code'] = Session::get('user_practice_code');
		\App\Models\payments::insert($datapayment);
	}
	public function edit_receipt_details(Request $request){
		$receipt_id = $request->get('receipt_id');
		$receipt = \App\Models\receipts::where('practice_code',Session::get('user_practice_code'))->where('id',$receipt_id)->first();
		$get_details = \App\Models\receiptNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$receipt->debit_nominal)->first();
		$debit_nominal = '';
		if(($get_details))
		{
			$debit_nominal = $get_details->code.' - '.$get_details->description;
		}
		if($receipt->hold_status == 0)
		{
			$hold_status = 'Outstanding';
			$locked = "0";
		}
		elseif($receipt->hold_status == 2)
		{
			$hold_status = 'Reconciled';
			$locked = "1";
		}
		else{
			$hold_status = 'Cleared';
			$locked = "1";
		}
		if($receipt->journal_id != 0){
			$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a>';
		}
		else{
			$journal_id_view = '';
		}
		$data['status'] = $hold_status;
		$data['locked'] = $locked;
		$data['debit_nominal_code'] = $receipt->debit_nominal;
		$data['credit_nominal_code'] = $receipt->credit_nominal;
		$data['client_code'] = $receipt->client_code;
		$data['journal_id'] = $journal_id_view;
		$data['receipt_date'] = date('d/m/Y', strtotime($receipt->receipt_date));
		$data['debit_nominal'] = $debit_nominal;
		$data['credit_nominal'] = $receipt->credit_nominal;
		$data['credit_description'] = $receipt->credit_description;
		$data['comment'] = $receipt->comment;
		$data['amount'] = number_format_invoice_empty($receipt->amount);
		echo json_encode($data);
	}
}