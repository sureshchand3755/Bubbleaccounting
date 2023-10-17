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
class TwobillController extends Controller {
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
	public function two_bill_manager(Request $request)
	{
		$tasks = \App\Models\taskmanager::where('two_bill', 1)->where('practice_code',Session::get('user_practice_code'))->get();
		$user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();


		return view('user/two_bill/two_bill_manager', array('title' => 'Bubble - Two Bill Manager', 'userlist' => $user, 'taskslist' => $tasks));
	}
	public function get_tasks_invoices(Request $request)
	{
		$taskid = $request->get('taskid');
		$client_id = $request->get('client_id');
		if($client_id == "")
		{
			$invoices = \App\Models\InvoiceSystem::orderBy('id','DESC')->get();
		}
		else{
			$invoices = \App\Models\InvoiceSystem::where('client_id',$client_id)->orderBy('id','DESC')->get();
		}
		$output = '<table class="table">
			<thead>
				<th>S.No</th>
				<th>Invoice No</th>
				<th>Date</th>
				<th>Net Amount</th>
			</thead>
			<tbody id="invoice_tbody_tr">';
			$i = 1;
			if(($invoices))
			{
				foreach($invoices as $invoice)
				{
					$output.='<tr>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$i.'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.date('d-m-Y', strtotime($invoice->invoice_date)).'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.number_format_invoice($invoice->inv_net).'</a></td>
					</tr>';
					$i++;
				}
			}
			else{
				$output.='<tr>
						<td colspan="4">No Invoice Found</td>
					</tr>';
			}
			$output.='</tbody>
		</table>';
		echo $output;
	}
	public function update_invoice_for_task(Request $request)
	{
		$taskid = $request->get('taskid');
		$invoiceno = $request->get('invoiceno');
		$data['invoice'] = $invoiceno;
		\App\Models\taskmanager::where('id',$taskid)->update($data);
	}
	public function remove_2bill_status(Request $request)
	{
		$taskid = $request->get('taskid');
		$data['two_bill'] = 0;
		\App\Models\taskmanager::where('id',$taskid)->update($data);
	}
	public function change_billing_status(Request $request)
	{
		$taskid = $request->get('taskid');
		$data['billing_status'] = $request->get('status');
		\App\Models\taskmanager::where('id',$taskid)->update($data);
	}
	public function twobill_manager_authenticate(Request $request)
	{
		$tasks = \App\Models\taskmanager::where('two_bill', 1)->where('practice_code',Session::get('user_practice_code'))->get();
		$user_id = Session::get('userid');

		$auditdata['user_id'] = $user_id;
		$auditdata['module'] = '2Bill Manager';
		$auditdata['event'] = 'Visited 2Bill Manager';

		if(count($tasks) > 0) {
			$auditdata['reference'] = '2Bill Tasks Loaded';
		}
		else{
			$auditdata['reference'] = 'No Tasks Found';
		}
		
		$auditdata['updatetime'] = date('Y-m-d H:i:s');
		\App\Models\AuditTrails::insert($auditdata);
	}
}