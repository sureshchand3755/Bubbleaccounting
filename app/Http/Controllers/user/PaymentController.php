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
class PaymentController extends Controller {
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
	public function payment_management(Request $request)
	{
		$updateval['hold_status'] = 2;
		\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('hold_status',1)->where('journal_id','!=',0)->where('clearance_date','!=','0000-00-00')->update($updateval);
		$payment_nominal_codes_arr = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->get();
		$code_array = array();
		if(($payment_nominal_codes_arr))
		{
			foreach($payment_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->whereNotin('code',$code_array)->orderBy('code','asc')->get();
		$payment_nominal_codes = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->orderBy('code','asc')->get();
		return view('user/payment/payment', array('title' => 'Payment Management System','nominal_codes' => $nominal_codes,'payment_nominal_codes' => $payment_nominal_codes));
	}
	public function payment_move_to_allowable_list(Request $request)
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
				\App\Models\paymentNominalCodes::insert($data);
				$output.='<tr>
					<td>'.$details->code.'</td>
					<td>'.$details->description.'</td>
				</tr>';
			}
		}
		echo $output;
	}
	public function payment_save_details(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$comment = $request->get('comment');
		$amount = $request->get('amount');
		$data['payment_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		if($debit == "813") {
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client_code)->first();
			if(($supplier_details)) {
				$data['supplier_code'] = $supplier_details->id;
			}
		}
		else{
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$data['client_code'] = $client_code;
		}
		$data['debit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\payments::insertDetails($data);
		echo $id;
	}
	public function payment_update_details(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$amount = $request->get('amount');
		$comment = $request->get('comment');
		$payment_id = $request->get('payment_id');
		if($date == "") { $data['status'] = 1; }
		elseif($debit == "") { $data['status'] = 1; }
		elseif($credit == "") { $data['status'] = 1; }
		elseif($des == "") { $data['status'] = 1; }
		elseif($amount == "") { $data['status'] = 1; }
		elseif($debit == "813A") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		elseif($debit == "813") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		else{
			$data['status'] =  0;
		}
		$data['payment_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		if($debit == "813"){
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client_code)->first();
			$data['supplier_code'] = $supplier_details->id;
		}
		else{
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$data['client_code'] = $client_code;
		}
		$data['debit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;
		\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->update($data);
	}
	public function payment_commonclient_search(Request $request)
	{
		$value = $request->get('term');
		$debit_nominal = $request->get('debit_nominal');
		if($debit_nominal == "813")
		{
			$details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->Where('status', 0)->Where('supplier_code','like','%'.$value.'%')->orWhere('supplier_name','like','%'.$value.'%')->get();
			$data=array();
			foreach ($details as $single) {
	            $data[]=array('value'=>$single->supplier_name.' - '.$single->supplier_code,'id'=>$single->supplier_code,'company' => $single->supplier_name.' - '.$single->supplier_code);
	        }
	         if(($data))
	             return $data;
	        else
	            return ['value'=>'No Result Found','id'=>'','company' => ''];
		}
		else{
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
	}
	public function load_payment(Request $request)
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
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','like',$current_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "2")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','>=',$from)->where('payment_date','<=',$to)->where('imported',0)->get();
		}
		elseif($filter == "3")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code','like','%'.$client.'%')->where('imported',0)->get();
		}
		elseif($filter == "4")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal',$debit)->where('imported',0)->get();
		}
		elseif($filter == "5")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal',$credit)->where('imported',0)->get();
		}
		elseif($filter == "6")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->orderBy('payments_id','desc')->limit(300)->get();
		}
		elseif($filter == "7")
		{
			$current_year = date('Y');
			$previous_year = $current_year-1;
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','like',$previous_year.'%')->where('imported',0)->get();
		}
		$output = '';
		if(($get_payments))
		{
			foreach($get_payments as $payment)
			{
				/*if($payment->status == 1)
				{
					$font_color = 'color:#f00;font-weight:600';
				}
				elseif($payment->hold_status == 0)
				{
					$font_color = 'color:blue;font-weight:600';
				}
				else{
					$font_color = '';
				}*/
				$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				$payment_code = '';
				if($get_details)
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
					$payment_code = $get_details->code;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$payment->payments_id.'" data-nominal="'.$payment_code.'" style="color:#f00">Outstanding</a>';
					$delete_icon = '<a href="javascript:" class="fa fa-trash delete_outstading" data-element="'.$payment->payments_id.'" title="Delete Receipt" type="2"></a>';
				}
				else
				{
					if($payment->journal_id != 0){
						$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'" data-nominal="'.$payment_code.'" style="color:blue">Reconciled</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
					else{
						$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'">Cleared</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>';
					}
				}
				if($payment->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				$debit_nom = substr($payment->debit_nominal, 0, 3);
				$credit_nom = substr($payment->credit_nominal, 0, 3);
				if($debit_nom == $credit_nom && $debit_nom == '771') {
				    $ticon = '<spam class="ticon_span" style="display:none">1</spam><a href="javascript:" class="bank_transfer_button" data-element="'.$payment->payments_id.'"><img src="'.url('public/assets/images/t-icon.png').'" class="bank_transfer_button" data-element="'.$payment->payments_id.'" style="width:20px" title="Bank Transfer"/></a>';
				}else {
				    $ticon = '<spam class="ticon_span" style="display:none">0</spam><a href="javascript:" class="ticon" data-element="'.$payment->payments_id.'"><img src="'.url('public/assets/images/t-icon-disable.png').'" style="width:20px" /></a>';
				}
				if($payment->batch_id != 0){
					$batch_id = $payment->batch_id;
				} else{
					$batch_id = '';
				}
				$output.='<tr class="receipt_payment_'.$payment->payments_id.'">
					<td style=""><spam class="date_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_sort_val" style="">'.$payment->debit_nominal.'</td>
					<td class="credit_sort_val" style="">'.$credit_nominal.'</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$output.='<td class="client_sort_val" style="">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_sort_val" style="">'.$payment->client_code.'</td>';
					}
					$output.='<td class="des_sort_val" style="">'.$payment->debit_description.'</td>
					<td class="comment_sort_val" style="">'.$payment->comment.'</td>
					<td style=";text-align:right"><spam class="amount_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>
					<td><a href="javascript:" class="show_batch_overlay batch_sort_val" data-element="'.$batch_id.'">'.$batch_id.'</a></td>
					<td style=";text-align:right">'.$journal_id_view.'</td>	
					<td>'.$hold_status.'</td>	
					<td>
					    '.$ticon.'&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-edit edit_payment" title="Edit Payment" data-element="'.$payment->payments_id.'"></a>&nbsp;&nbsp;
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
	public function load_all_payment(Request $request)
	{
		$page = $request->get('page');
		$offset = (($page - 1) * 500);
		$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->orderByRaw('UNIX_TIMESTAMP(`payment_date`) ASC')->offset($offset)->limit(500)->get();
		$output = '';
		if(($get_payments))
		{
			foreach($get_payments as $payment)
			{
				$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				$get_detail_code = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
					$get_detail_code = $get_details->code;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$payment->payments_id.'" data-nominal="'.$get_detail_code.'" style="color:#f00">Outstanding</a>';
					$delete_icon = '<a href="javascript:" class="fa fa-trash delete_outstading" data-element="'.$payment->payments_id.'" title="Delete Payment" type="2"></a>';
				}
				else
				{
					if($payment->journal_id != 0){
						$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'" data-nominal="'.$get_detail_code.'" style="color:blue">Reconciled</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Payment"></a>';
					}
					else{
						$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'">Cleared</a>';
						$delete_icon = '<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Payment"></a>';
					}
				}
				if($payment->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				$debit_nom = substr($payment->debit_nominal, 0, 3);
				$credit_nom = substr($payment->credit_nominal, 0, 3);
				if($debit_nom == $credit_nom && $debit_nom == '771') {
				    $ticon = '<spam class="ticon_span" style="display:none">1</spam><a href="javascript:" class="bank_transfer_button" data-element="'.$payment->payments_id.'"><img src="'.url('public/assets/images/t-icon.png').'" class="bank_transfer_button" data-element="'.$payment->payments_id.'" style="width:20px" title="Bank Transfer"/></a>';
				}else {
				    $ticon = '<spam class="ticon_span" style="display:none">0</spam><a href="javascript:" class="ticon" data-element="'.$payment->payments_id.'"><img src="'.url('public/assets/images/t-icon-disable.png').'" style="width:20px" /></a>';
				}
				if($payment->batch_id != 0){
					$batch_id = $payment->batch_id;
				} else{
					$batch_id = '';
				}
				$output.='<tr class="receipt_payment_'.$payment->payments_id.'">
					<td style=""><spam class="date_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_sort_val" style="">'.$payment->debit_nominal.'</td>
					<td class="credit_sort_val" style="">'.$credit_nominal.'</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$output.='<td class="client_sort_val" style="">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_sort_val" style="">'.$payment->client_code.'</td>';
					}
					$output.='<td class="des_sort_val" style="">'.$payment->debit_description.'</td>
					<td class="comment_sort_val" style="">'.$payment->comment.'</td>
					<td style=";text-align:right"><spam class="amount_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>
					<td><a href="javascript:" class="show_batch_overlay batch_sort_val" data-element="'.$batch_id.'">'.$batch_id.'</a></td>
					<td style=";text-align:right">'.$journal_id_view.'</td>	
					<td>'.$hold_status.'</td>	
					<td>
					    '.$ticon.'&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-edit edit_payment" title="Edit Payment" data-element="'.$payment->payments_id.'"></a>&nbsp;&nbsp;
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
		$data['total_counts'] = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->count();
		$data['total_pages'] = ceil($data['total_counts'] / 500);
		echo json_encode($data);
	}
	public function show_batch_payment_informations(Request $request)
	{
		$batch_id = $request->get('batch_id');
		$outstanding_trans 	= 0;
		$reconciled_trans 	= 0;
		$cleared_trans 	= 0;
		$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('batch_id',$batch_id)->where('imported',0)->get();
		$output = '';
		if(($get_payments))
		{
			foreach($get_payments as $payment)
			{
				/*if($payment->status == 1)
				{
					$font_color = 'color:#f00;font-weight:600';
				}
				elseif($payment->hold_status == 0)
				{
					$font_color = 'color:blue;font-weight:600';
				}
				else{
					$font_color = '';
				}*/
				$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = 'Outstanding';
					$outstanding_trans++;
				}
				elseif($payment->hold_status == 2)
				{
					$hold_status = 'Reconciled';
					$reconciled_trans++;
				}
				else{
					$hold_status = 'Cleared';
					$cleared_trans++;
				}
				if($payment->journal_id != 0){
					$journal_id_view = '<a href="javascript:" class="journal_id_viewer journal_id_batch_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
				}
				else{
					$journal_id_view = '';
				}
				if($payment->batch_id != 0){
					$batch_id = $payment->batch_id;
				} else{
					$batch_id = '';
				}
				$output.='<tr class="receipt_payment_tr_'.$payment->payments_id.'">
					<td style=""><spam class="date_batch_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_batch_sort_val" style="">'.$payment->debit_nominal.'</td>
					<td class="credit_batch_sort_val" style="">'.$credit_nominal.'</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
						$output.='<td class="client_batch_sort_val" style="">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_batch_sort_val" style="">'.$payment->client_code.'</td>';
					}
					$output.='<td class="des_batch_sort_val" style="">'.$payment->debit_description.'</td>
					<td class="comment_batch_sort_val" style="">'.$payment->comment.'</td>
					<td style=";text-align:right"><spam class="amount_batch_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>
					<td style=";text-align:right">'.$journal_id_view.'</td>	
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
		$batch_details = \App\Models\paymentBatches::where('practice_code',Session::get('user_practice_code'))->where('id',$batch_id)->first();
		$dataval['imported_date'] 		= date('d-M-Y @ H:i', strtotime($batch_details->created_at));
		$dataval['outstanding_trans'] 	= $outstanding_trans;
		$dataval['reconciled_trans'] 	= $reconciled_trans;
		$dataval['cleared_trans'] 		= $cleared_trans;
		$dataval['output'] 				= $output;
		echo json_encode($dataval);
	}
	public function delete_outstanding_payments_from_batch(Request $request)
	{
		$batch_id = $request->get('batch_id');
		\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('batch_id',$batch_id)->where('hold_status',0)->where('imported',0)->delete();
		$data['batch_delete'] = 1;
		$data['delete_datetime'] = date('Y-m-d H:i:s');
		\App\Models\paymentBatches::where('practice_code',Session::get('user_practice_code'))->where('id',$batch_id)->update($data);
	}
	public function show_payments_batch_list(Request $request)
	{
		$batches = \App\Models\paymentBatches::where('practice_code',Session::get('user_practice_code'))->get();
		$output = '';
		if(($batches)) {
			foreach($batches as $batch){
				//$trans_count = \App\Models\payments::where('batch_id',$batch->id)->where('imported',0)->count();
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
	public function export_load_payment(Request $request)
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
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','like',$current_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "2")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','>=',$from)->where('payment_date','<=',$to)->where('imported',0)->get();
		}
		elseif($filter == "3")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('client_code','like','%'.$client.'%')->where('imported',0)->get();
		}
		elseif($filter == "4")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('debit_nominal',$debit)->where('imported',0)->get();
		}
		elseif($filter == "5")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('credit_nominal',$credit)->where('imported',0)->get();
		}
		elseif($filter == "6")
		{
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->orderBy('payments_id','desc')->limit(300)->get();
		}
		elseif($filter == "7")
		{
			$current_year = date('Y');
			$previous_year = $current_year-1;			
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payment_date','like',$previous_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "8")
		{		
			$get_payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',0)->get();
		}
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client/Supplier Code', 'Debit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_payment_list.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		fputcsv($file, $columns);
		$output = '';
		if(is_countable($get_payments) && count($get_payments) > 0)
		{
			foreach($get_payments as $payment)
			{
				$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_details))
				{
					$credit_nominal = $get_details->code;
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
				$columns_2 = array(date('d/m/Y', strtotime($payment->payment_date)),$payment->debit_nominal,$credit_nominal,$payment->client_code,$payment->debit_description,$payment->comment,number_format_invoice($payment->amount),$payment->journal_id,$hold_status);
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function payment_change_to_unhold(Request $request)
	{
		$id = $request->get('id');
		$data['hold_status'] = 1;
		\App\Models\payments::where('payments_id',$id)->update($data);
	}
	public function import_new_payment(Request $request)
	{
		$get_imported_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->get();
		if(($get_imported_payment))
		{
			$dataBatch['created_at'] = date('Y-m-d H:i:s');
			$dataBatch['transaction_count'] = count($get_imported_payment);
			$dataBatch['practice_code'] = Session::get('user_practice_code');
			$batch_id = \App\Models\paymentBatches::insertDetails($dataBatch);
			foreach($get_imported_payment as $payment)
			{
				$daata['batch_id'] = $batch_id;
				$daata['imported'] = 0;
				\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment->payments_id)->update($daata);
			}
		  	return redirect('user/payment_management')->with('message_import', 'Payments Imported successfully.');
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
		// 			return redirect('user/payment_management')->with('message_import', 'Receipt Imported successfully.');
		// 		}
		// 		else{
		// 			return redirect('user/payment_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1');
		// 		}
		// 	}
		// }
	}
	public function import_new_payment_one(Request $request)
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
				$data['payment_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				if($debit == "813") {
					$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client)->first();
					$data['supplier_code'] = $supplier_details->id;
				}
				else{
					$data['client_code'] = $client;
				}
				$data['debit_description'] = $description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;
				$data['practice_code'] = Session::get('user_practice_code');
				\App\Models\payments::insert($data);
			}
		}
		if($height >= $highestRow)
		{
			return redirect('user/payment_management')->with('message_import', 'Payment Imported successfully.');
		}
		else{
			return redirect('user/payment_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1');
		}
	}
	public function add_payment_export_csv(Request $request)
	{
		$ids = $request->get('ids');		
		$expids = explode(',',$ids);
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client/Supplier Code', 'Debit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_add_payment_list.csv';
		$file = fopen('public/papers/'.$filename, 'w');
		fputcsv($file, $columns);
		if($ids != "")
		{
			foreach($expids as $id)
			{				
				$get_details = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$id)->first();				
				$get_debit_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$get_details->credit_nominal)->first();
				$credit_nominal = '';
				if(($get_debit_details))
				{
					$credit_nominal = $get_debit_details->code;
				}
				$columns_2 = array(date('d/m/Y', strtotime($get_details->payment_date)),$get_details->debit_nominal,$credit_nominal,$get_details->client_code,$get_details->debit_description,$get_details->comment,number_format_invoice($get_details->amount),'','Outstanding');
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function check_import_csv_payment(Request $request)
	{
		$get_imported_payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->get();
		if(($get_imported_payment))
		{
		  \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('imported',1)->delete();
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
					if($key == 0){
						$date_header = trim($row[0]);
						$debit_header = trim($row[1]);
						$credit_header = trim($row[2]);
						$client_header = trim($row[3]);
						$comment_header = trim($row[5]);
						$amount_header = trim($row[6]);
						if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client/Supplier Code" && $comment_header == "Comment" && $amount_header == "Amount")
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
						$debit_description = trim($row[4]);
						$comment = trim($row[5]);
						$amount = trim($row[6]);
						$supplier_id = '';
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
						if($debit_description != "")
						{
							$description = $debit_description;
						}
						else{
							if($debit == "813A")
							{
								$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
								if(($client_details))
								{
									$description = $client_details->company.' - '.$client_details->client_id;
								}
							}
							elseif($debit == "813")
							{
								$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client)->first();
								if(($supplier_details))
								{
									$description = $supplier_details->supplier_name.' - '.$supplier_details->supplier_code;
									$supplier_id = $supplier_details->id;
								}
							}
							else{
								$get_nominal_code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
								if(($get_nominal_code_description))
								{
									$description = $get_nominal_code_description->description;
								}
							}
						}
						$data['payment_date'] = $dateval;
						$data['debit_nominal'] = $debit;
						$data['credit_nominal'] = $credit;
						if($debit == "813")
						{
							$data['supplier_code'] = $supplier_id;
							$data['client_code'] = '';
						}
						else{
							$data['client_code'] = $client;
							$data['supplier_code'] = 0;
						}
						$data['debit_description'] = $description;
						$data['comment'] = $comment;
						$data['amount'] = $amount;
						$data['imported'] = 1;
						$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
						$credit_nominal = '';
						if(($get_details))
						{
							$credit_nominal = $get_details->code;
						}
						$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
						$debit_nominal = '';
						if(($get_details))
						{
							$debit_nominal = $get_details->code;
						}
						$get_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
						$client_name = '';
						if(($get_details))
						{
							$client_name = $get_details->client_id;
						}
						$get_supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client)->first();
						$supplier_name = '';
						if(($get_supplier_details))
						{
							$supplier_name = $get_supplier_details->supplier_code;
						}
						$i = 0;
						$j = 0;
						$error_text = '';
						if($date == "" || $debit == "" || $credit == "" || ($debit == "813A" && $client == "") || ($debit == "813" && $client == "") || $amount == "")
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
				        if($debit == "813A")
				        {
				          if($client_name == "")
				          {
				            $i = $i + 1;
				            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Client/Supplier Code is Invalid on Line '.$rowvalue.'<br/>';
				          }
				        }
				        if($debit == "813")
				        {
				          if($supplier_name == "")
				          {
				            $i = $i + 1;
				            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Supplier Code is Invalid on Line '.$rowvalue.'<br/>';
				          }
				        }
				        if($debit != "813A" && $debit != "813"){
				        	if($client_name != "")
				          	{
				          		$i = $i + 1;
				            	$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i>Client Code should need to be empty on Line '.$rowvalue.'<br/>';
				          	}
				          	if($supplier_name != "")
				          	{
				          		$i = $i + 1;
				            	$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i>Supplier Code should need to be empty on Line '.$rowvalue.'<br/>';
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
				        $data['practice_code'] = Session::get('user_practice_code');
						\App\Models\payments::insert($data);
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
							<td style="'.$font_color.'">'.$debit_description.'</td>
							<td style="'.$font_color.'">'.$comment.'</td>  
							<td style="text-align:right;'.$font_color.'">'.$amount_val.'</td>
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
	public function check_import_csv_one_payment(Request $request)
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
			$debit_description = trim($row[4]);
			$comment = trim($row[5]);
			$amount = trim($row[6]);
			$supplier_id = '';
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
			if($debit_description != "")
			{
				$description = $debit_description;
			}
			else{
				if($debit == "813A")
				{
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
					if(($client_details))
					{
						$description = $client_details->company.' - '.$client_details->client_id;
					}
				}
				elseif($debit == "813")
				{
					$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client)->first();
					if(($supplier_details))
					{
						$description = $supplier_details->supplier_name.' - '.$supplier_details->supplier_code;
						$supplier_id = $supplier_details->id;
					}
				}
				else{
					$get_nominal_code_description = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
					if(($get_nominal_code_description))
					{
						$description = $get_nominal_code_description->description;
					}
				}
			}
			$data['payment_date'] = $dateval;
			$data['debit_nominal'] = $debit;
			$data['credit_nominal'] = $credit;
			if($debit == "813")
			{
				$data['supplier_code'] = $supplier_id;
				$data['client_code'] = '';
			}
			else{
				$data['client_code'] = $client;
				$data['supplier_code'] = 0;
			}
			$data['debit_description'] = $description;
			$data['comment'] = $comment;
			$data['amount'] = $amount;
			$data['imported'] = 1;
			$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
			$credit_nominal = '';
			if(($get_details))
			{
				$credit_nominal = $get_details->code;
			}
			$get_details = \App\Models\NominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$debit)->first();
			$debit_nominal = '';
			if(($get_details))
			{
				$debit_nominal = $get_details->code;
			}
			$get_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client)->first();
			$client_name = '';
			if(($get_details))
			{
				$client_name = $get_details->client_id;
			}
			$get_supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client)->first();
			$supplier_name = '';
			if(($get_supplier_details))
			{
				$supplier_name = $get_supplier_details->supplier_code;
			}
			$i = 0;
			$j = 0;
			$error_text = '';
			if($date == "" || $debit == "" || $credit == "" || ($debit == "813A" && $client == "") || ($debit == "813" && $client == "") || $amount == "")
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
	        if($debit == "813A")
	        {
	          if($client_name == "")
	          {
	            $i = $i + 1;
	            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Client/Supplier Code is Invalid on Line '.$rowvalue.'<br/>';
	          }
	        }
	        if($debit == "813")
	        {
	          if($supplier_name == "")
	          {
	            $i = $i + 1;
	            $error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i> Supplier Code is Invalid on Line '.$rowvalue.'<br/>';
	          }
	        }
	        if($debit != "813A" && $debit != "813"){
	        	if($client_name != "")
	          	{
	          		$i = $i + 1;
	            	$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i>Client Code should need to be empty on Line '.$rowvalue.'<br/>';
	          	}
	          	if($supplier_name != "")
	          	{
	          		$i = $i + 1;
	            	$error_text.= '<i class="fa fa-exclamation-triangle" style="color:#f00"></i>Supplier Code should need to be empty on Line '.$rowvalue.'<br/>';
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
	        $data['practice_code'] = Session::get('user_practice_code');
			\App\Models\payments::insert($data);
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
				<td style="'.$font_color.'">'.$debit_description.'</td>
				<td style="'.$font_color.'">'.$comment.'</td>  
				<td style="text-align:right;'.$font_color.'">'.$amount_val.'</td>
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
	public function get_payment_bank_transfer_details(Request $request)
	{
	    $id = $request->get('payment_id');
	    $payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id', $id)->first();
	    $get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
		$credit_nominal = '';
		if(($get_details))
		{
			$credit_nominal = $get_details->code.' - '.$get_details->description;
		}
		if($payment->journal_id != 0){
			$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
		}
		else{
			$journal_id_view = 'N/A';
		}
		$output ='
		    <tr>
              <td style="width:40%;border:0px">Date:</td>
              <td style="border:0px" class="bank_transfer_date">'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
            </tr>
            <tr>
              <td style="border:0px">Debit Nominal:</td>
              <td style="border:0px" class="bank_transfer_debit">'.$payment->debit_nominal.'</td>
            </tr>
            <tr>
              <td style="border:0px">Credit Nominal - Credit Nominal Description:</td>
              <td style="border:0px" class="bank_transfer_credit">'.$credit_nominal.' <a href="javascript:" class="fa fa-lock" style="font-weight:800;color:green"></a></td>
            </tr>
            <tr>
              <td style="border:0px">Client / Supplier Code:</td>';
                if($payment->debit_nominal == "813") {
    				$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
    				$output.='<td class="client_sort_val bank_transfer_client" style="border:0px">'.$supplier_details->supplier_code.'</td>';
    			}
    			else {
    			    if($payment->client_code == ""){
    			        $na = 'N/A';
    			    }
    			    else{
    			        $na = $payment->client_code;
    			    }
    				$output.='<td class="client_sort_val bank_transfer_client" style="border:0px">'.$na.'</td>';
    			}
            $output.='</tr>
            <tr>
              <td style="border:0px">Debit Nominal Description:</td>
              <td style="border:0px" class="bank_transfer_debit_des">'.$payment->debit_description.'</td>
            </tr>
            <tr>
              <td style="border:0px">Comment:</td>
              <td style="border:0px" class="bank_transfer_comment">'.$payment->comment.'</td>
            </tr>
            <tr>
              <td style="border:0px">Amount:</td>
              <td style="border:0px" class="bank_transfer_amount">'.number_format_invoice_empty($payment->amount).'</td>
            </tr>
            <tr>
              <td style="border:0px">Journal ID:</td>
              <td style="border:0px" class="bank_transfer_journal">'.$journal_id_view.'</td>
            </tr>';
            echo $output;
	}
	public function prepare_payment_receipt_entry(Request $request)
	{
		$payment_id = $request->get('payment_id');
		$data['debit_nominal'] = '770';
		$payment_detail = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->first();
		\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->update($data);
		$datapayment['receipt_date'] = $payment_detail->payment_date;
		$datapayment['debit_nominal'] = $payment_detail->debit_nominal;
		$datapayment['credit_nominal'] = '770';
		$datapayment['client_code'] = '';
		$datapayment['credit_description'] = 'Bank Transfer Holding Account';
		$datapayment['comment'] = $payment_detail->comment;
		$datapayment['amount'] = $payment_detail->amount;
		\App\Models\receipts::insert($datapayment);
	}
	public function edit_payment_details(Request $request){
		$payment_id = $request->get('payment_id');
		$payment = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->first();
		$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$payment->credit_nominal)->first();
		$credit_nominal = $payment->credit_nominal;

		if($get_details) {
			$credit_nominal = $get_details->code.' - '.$get_details->description;
		}

		if($payment->hold_status == 0)
		{
			$hold_status = 'Outstanding';
			$locked = "0";
		}
		elseif($payment->hold_status == 2)
		{
			$hold_status = 'Reconciled';
			$locked = "1";
		}
		else{
			$hold_status = 'Cleared';
			$locked = "1";
		}
		if($payment->journal_id != 0){
			$journal_id_view = '<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
		}
		else{
			$journal_id_view = '';
		}

		if($payment->debit_nominal == "813") {
			$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('id',$payment->supplier_code)->first();
			$client_code = $supplier_details->supplier_code;
		}
		else {
		    if($payment->client_code == ""){
		        $na = '';
		    }
		    else{
		        $na = $payment->client_code;
		    }
		    $client_code = $na;
		}


		$data['status'] = $hold_status;
		$data['locked'] = $locked;
		$data['debit_nominal_code'] = $payment->debit_nominal;
		$data['credit_nominal_code'] = $payment->credit_nominal;
		$data['client_code'] = $client_code;
		$data['journal_id'] = $journal_id_view;
		$data['payment_date'] = date('d/m/Y', strtotime($payment->payment_date));
		$data['credit_nominal'] = $credit_nominal;
		$data['debit_nominal'] = $payment->debit_nominal;
		$data['debit_description'] = $payment->debit_description;
		$data['comment'] = $payment->comment;
		$data['amount'] = number_format_invoice_empty($payment->amount);
		echo json_encode($data);
	}
	public function update_payment_details_editted(Request $request)
	{
		$date = explode("/",$request->get('date'));
		$debit = $request->get('debit');
		$credit = $request->get('credit');
		$client_code = $request->get('client_code');
		$des = $request->get('des');
		$amount = $request->get('amount');
		$comment = $request->get('comment');
		$payment_id = $request->get('payment_id');
		if($date == "") { $data['status'] = 1; }
		elseif($debit == "") { $data['status'] = 1; }
		elseif($credit == "") { $data['status'] = 1; }
		elseif($des == "") { $data['status'] = 1; }
		elseif($amount == "") { $data['status'] = 1; }
		elseif($credit == "813" || $credit == "813A") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		else{
			$data['status'] =  0;
		}
		$data['payment_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;

		if($debit == "813") {
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$supplier_details = \App\Models\suppliers::where('practice_code',Session::get('user_practice_code'))->where('supplier_code',$client_code)->first();
			if($supplier_details) {
				$data['supplier_code'] = $supplier_details->id;
			}
		}
		else{
			$clientexp = explode('-',$client_code);
			$client_code = trim(end($clientexp));
			$data['client_code'] = $client_code;
		}
		$data['debit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;

		\App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->update($data);
		$payments = \App\Models\payments::where('practice_code',Session::get('user_practice_code'))->where('payments_id',$payment_id)->first();
		if($payments->journal_id != 0){
			if($payments->debit_nominal == "813") {
				$exp_des = explode('-',$payments->debit_description);
				$journal_description = 'Supplier Payment To '.$payments->supplier_code.' '.$exp_des[0];
			} elseif($payments->debit_nominal == "813A") {
				$exp_des = explode('-',$payments->debit_description);
				$journal_description = 'Client Money Holding Account to '.$payments->client_code.' '.$exp_des[0];
			} else{
				$journal_description = 'Payment To '.$payments->debit_nominal.' '.$payments->debit_description;
			}
			$dataval['journal_date'] = $payments->payment_date;
			$dataval['description'] = $journal_description;
			$dataval['nominal_code'] = $payments->debit_nominal;
			$dataval['dr_value'] = $payments->amount; 
			$dataval['cr_value'] = '0.00';
			$dataval['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::where('connecting_journal_reference',$payments->journal_id)->update($dataval);
			$next_connecting_journal = $payments->journal_id.'.01';
			$dataval1['journal_date'] = $payments->payment_date;
			$dataval1['description'] = $journal_description;
			$dataval1['dr_value'] = '0.00';
			$dataval1['cr_value'] = $payments->amount; 
			$dataval1['practice_code'] = Session::get('user_practice_code');
			\App\Models\Journals::where('connecting_journal_reference',$next_connecting_journal)->update($dataval1);
		}
		$get_details = \App\Models\paymentNominalCodes::where('practice_code',Session::get('user_practice_code'))->where('code',$credit)->first();
		$credit_nominal = '';
		if($get_details)
		{
			$credit_nominal = $get_details->code.' - '.$get_details->description;
		}
		$dataval['credit_nominal'] = $credit_nominal;
		$dataval['strtotime'] = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
		$dataval['amount'] = number_format_invoice($amount);
		echo json_encode($dataval);
	}
}
