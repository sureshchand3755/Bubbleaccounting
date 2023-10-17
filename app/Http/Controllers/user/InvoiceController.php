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
class InvoiceController extends Controller {
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
	public function invoicemanagement(Request $request)
	{
		$invoice = \App\Models\InvoiceSystem::select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate','vat_value', 'gross', 'status', 'statement')->orderBy('id','asc')->where('practice_code', Session::get('user_practice_code'))->get();	
		return view('user/invoice/invoice', array('title' => 'Invoice System Management', 'invoicelist' => $invoice));
	}
	public function invoice_search(Request $request)
	{
		$input = $request->get('input');
		$select = $request->get('select');
		if($select == 'company_name'){
			$companydetail = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('company','like','%'.$input.'%')->get();
			$output = '';
			$i=1;
			if(($companydetail)){
				foreach ($companydetail as $key => $company) {	
					$invoicelist = \App\Models\InvoiceSystem::where('client_id', $company->client_id)->where('practice_code', Session::get('user_practice_code'))->get();					
					if(($invoicelist)){ 
						foreach($invoicelist as $invoice){ 
							$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
							if($client_details)
							{
								if($client_details->company !== "")
	                          {
	                            $company = $client_details->company;
	                          }
	                          else{
	                            $company = $client_details->firstname.' & '.$client_details->surname;
	                          }
							}
							else{
								$company = '';
							}
							if($invoice->statement == "No"){
								$textcolor="color:#f00;font-weight:700";
							}
							else{
								$textcolor="color:#26BD67;font-weight:700";
							}
							$company = str_replace("Company Limited By Guarantee","",$company);
                            $company = str_replace("company Limited by Guarantee","",$company);
							$output.='
								<tr>
									<td>'.$i.'</td>
									<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
									<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
									<td align="left">'.$invoice->client_id.'</td>
									<td align="center">
										<img class="active_client_list_tm1" data-iden="'.$invoice->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
									</td>
									<td align="left">'.$company.'</td>';
									$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
		                            $jids = '';
		                            if($get_journal_ids)
		                            {
		                              $jids = $get_journal_ids->connecting_journal_reference;
		                            }
	                                $output.='
									<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
									<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
									<td align="right">'.number_format_invoice($invoice->gross).'</td>
									<td align="left" style="'.$textcolor.'">'. $invoice->statement.'</td>
									<td class="jids" align="left"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
								</tr>
							';
							$i++;
						}						
					}					
				}				
			}	
			if($i == 1)
		        {
		          $output.='<tr>
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
		else{
			if($select == "inv_net" || $select == "vat_value" || $select == "gross")
			{
				$invoicelist = DB::select('SELECT * from `invoice_system` WHERE ROUND(Replace('.$select.', ",", ""),2) LIKE "%'.$input.'%"')->where('practice_code', Session::get('user_practice_code'));
			}
			else{
				if($select == "invoice_date")
				{
					$input = date('Y-m-d',strtotime($input));
				}
				$invoicelist = DB::select('SELECT * from `invoice_system` WHERE `practice_code` = "'.Session::get('user_practice_code').'" AND '.$select.' LIKE "%'.$input.'%"'); //->where('practice_code', Session::get('user_practice_code'));
				// $filteredInvoicelist = array_filter($invoicelist, function($invoice) {
    //              return $invoice->practice_code === Session::get('user_practice_code');
    //             });
			}
			$output = '';
			$i=1;
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}
					if($invoice->statement == "No"){
						$textcolor="color:#f00;font-weight:700";
					}
					else{
						$textcolor="color:#26BD67;font-weight:700";
					}
					$company = str_replace("Company Limited By Guarantee","",$company);
                    $company = str_replace("company Limited by Guarantee","",$company);
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
							<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
							<td align="left">'.$invoice->client_id.'</td>
							<td align="center">
										<img class="active_client_list_tm1" data-iden="'.$invoice->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
							<td align="left">'.$company.'</td>';
							$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
		                            $jids = '';
                            if($get_journal_ids)
                            {
                              $jids = $get_journal_ids->connecting_journal_reference;
                            }
                            $output.='
							<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
							<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
							<td align="right">'.number_format_invoice($invoice->gross).'</td>
							<td align="left" style="'.$textcolor.'">'. $invoice->statement.'</td>
							<td class="jids" align="left"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
						</tr>
					';
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
	}
	public function invoicemanagement_paginate(Request $request)
	{
		$page = $request->get('page');
		$prev_page = $page - 1;
		$offset = $prev_page * 1000;
		$invoicelist = \App\Models\InvoiceSystem::select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate', 'vat_value', 'gross', 'status', 'statement')->where('practice_code', Session::get('user_practice_code'))->offset($offset)->limit(1000)->get();	
		$output ='';
		$i = $offset+1;
		if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					if($invoice->client_id != "")
					{
						$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
						if($client_details)
						{
							if($client_details->company !== "")
		                  {
		                    $company = $client_details->company;
		                  }
		                  else{
		                    $company = $client_details->firstname.' & '.$client_details->surname;
		                  }
						}
						else{
							$company = '';
						}
					}
					else{
						$company = '';
					}
					if($invoice->statement ==''){
	                    $statementtext = 'N/A';
	                  }
	                  else{
	                    $statementtext = $invoice->statement;
	                  };
	                  if($invoice->statement == "No"){
	                    $textcolor="color:#f00;font-weight:700";
	                  }
	                  else{
	                    $textcolor="color:#26BD67;font-weight:700";
	                  }
	                  if($key == 999) { $class_load = 'class="load_more"'; } else { $class_load = ''; }
	                  $company = str_replace("Company Limited By Guarantee","",$company);
                      $company = str_replace("company Limited by Guarantee","",$company);
					$output.='
						<tr>
                                <td>'.$i.'</td>
                                <td align="left"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>
                                <td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y',strtotime($invoice->invoice_date)).'</td>
                                <td align="left">'.$invoice->client_id.'</td>
                                <td align="left" '.$class_load.'>'.$company.'</td>
                                <td align="right">'.number_format_invoice($invoice->inv_net).'</td>
                                <td align="right">'.number_format_invoice($invoice->vat_value).'</td>
                                <td align="right">'.number_format_invoice($invoice->gross).'</td>
                                <td align="left" style="'.$textcolor.'">'.$statementtext.'</td>
                            </tr>
					';
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
	public function show_statement(Request $request)
	{
		$statement = $request->get('value');
		if($statement != 1){
			$invoicelist = \App\Models\InvoiceSystem::where('practice_code', Session::get('user_practice_code'))->where('statement', 'yes')->Orwhere('statement', '')->get();
			$output = '';
			$i=1;
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						if($client_details->company !== "")
                      {
                        $companyname = $client_details->company;
                      }
                      else{
                        $companyname = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$companyname = '';
					}
					if($invoice->statement ==''){
						$statementtext = 'N/A';
					}
					else{
						$statementtext = $invoice->statement;
					};
					if($invoice->statement == "No"){
						$textcolor="color:#f00;font-weight:700";
					}
					else{
						$textcolor="color:#26BD67;font-weight:700";
					}
					$companyname = str_replace("Company Limited By Guarantee","",$companyname);
                            $companyname = str_replace("company Limited by Guarantee","",$companyname);
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
							<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
							<td align="left">'.$invoice->client_id.'</td>
							<td align="left">'.$companyname.'</td>';
							$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
		                            $jids = '';
                            if($get_journal_ids)
                            {
                              $jids = $get_journal_ids->connecting_journal_reference;
                            }
                            $output.='
							<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
							<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
							<td align="right">'.number_format_invoice($invoice->gross).'</td>
							<td align="left" style="'.$textcolor.'">'.$statementtext.'</td>
							<td class="jids" align="left"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
						</tr>
					';
					$i++;
				}
			}
			else
	        {
	          $output.='<tr>
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
		else{
			$invoicelist = \App\Models\InvoiceSystem::get();
			$output = '';
			$i=1;
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						if($client_details->company !== "")
                      {
                        $companyname = $client_details->company;
                      }
                      else{
                        $companyname = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$companyname = '';
					}
					if($invoice->statement ==''){
						$statementtext = 'N/A';
					}
					else{
						$statementtext = $invoice->statement;
					};
					if($invoice->statement == "No"){
						$textcolor="color:#f00;font-weight:700";
					}
					else{
						$textcolor="color:#26BD67;font-weight:700";
					}
					$companyname = str_replace("Company Limited By Guarantee","",$companyname);
                            $companyname = str_replace("company Limited by Guarantee","",$companyname);
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
							<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
							<td align="left">'.$invoice->client_id.'</td>
							<td align="left">'.$companyname.'</td>';
							$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
		                            $jids = '';
                            if($get_journal_ids)
                            {
                              $jids = $get_journal_ids->connecting_journal_reference;
                            }
                            $output.='
							<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
							<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
							<td align="right">'.number_format_invoice($invoice->gross).'</td>
							<td align="left" style="'.$textcolor.'">'. $statementtext.'</td>
							<td class="jids" align="left"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
						</tr>
					';
					$i++;
				}
			}
			else
	        {
	          $output.='<tr>
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
	}
	public function report_client_invoice(Request $request){
		$id = $request->get('id');
		if($id != 1){
			$invoicelist = \App\Models\InvoiceSystem::select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate','vat_value', 'gross', 'status', 'statement')->where('practice_code', Session::get('user_practice_code'))->groupBy('client_id')->get();
			$output = '';
			$i=1;
			if(($invoicelist)){ 
				foreach($invoicelist as $invoice){ 
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						$count = \App\Models\InvoiceSystem::where('client_id', $client_details->client_id)->where('practice_code', Session::get('user_practice_code'))->get();
						if($client_details->company !== "")
	                      {
	                        $company = $client_details->company;
	                      }
	                      else{
	                        $company = $client_details->firstname.' & '.$client_details->surname;
	                      }
						$clientname = $client_details->firstname;
					}
					else{
						$count = array();
						$company = '';
						$clientname = '';
					}
					if($invoice->statement == "No"){
						$textcolor="color:#f00";
					}
					else{
						$textcolor="color:#26BD67";
					}
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td><input type="checkbox" name="select_client" class="select_client class_'.$invoice->client_id.'" data-element="'.$invoice->client_id.'" value="'.$invoice->client_id.'"><label>&nbsp</label></td>
							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>							
							<td align="left" style="'.$textcolor.'">'.$clientname.'</td>
							<td align="left" style="'.$textcolor.'">'.$company.'</td>
							<td align="center" style="'.$textcolor.'">'.count($count).'</td>
						</tr>
					';
					$i++;
				}						
			}
			if($i == 1)
	        {
	          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	        }
			echo $output;
		}
	}
	public function invoice_report_csv(Request $request, $id=""){	
		$ids = explode(",",$request->get('value'));
		$quoted_id = '';
		foreach($ids as $id)
		{
			if($quoted_id == "")
			{
				$quoted_id = '"'.$id.'"';
			}
			else{
				$quoted_id = $quoted_id.',"'.$id.'"';
			}
		}
		if(isset($_POST['fromdate']))
		{
			$from = explode("-",$request->get('fromdate'));
			$to = explode("-",$request->get('todate'));
			$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
			$todate = $to[2].'-'.$to[0].'-'.$to[1];
			$startdate = strtotime($fromdate);
			$enddate = strtotime($todate);
			$invoice = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');
		}
		else{
			$invoice = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');
		}
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns = array('#', 'Invoice Number', 'Invoice Date', 'Client ID', 'Company Name', 'Net', 'VAT', 'Gross','Journal Id');
		$callback = function() use ($invoice, $columns)
    	{
	       	$file = fopen('public/papers/CM_Report.csv', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($invoice as $single) {
				$company_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $single->client_id)->first();
				if($company_details->company !== "")
	              {
	                $company = $company_details->company;
	              }
	              else{
	                $company = $company_details->firstname.' & '.$company_details->surname;
	              }
	            $get_journal_ids = \App\Models\Journals::where('reference',$single->invoice_number)->first();
		                            $jids = '';
                if($get_journal_ids)
                {
                  $jids = $get_journal_ids->connecting_journal_reference;
                }
		      	$columns_2 = array($i, $single->invoice_number, date('d-M-Y', strtotime($single->invoice_date)), $single->client_id, $company, number_format_invoice($single->inv_net), $single->vat_value,  number_format_invoice($single->gross),$jids);
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}
	public function invoice_report_pdf(Request $request)
	{
		/*$ids = 'GBS058';	
		$invoicelist = \App\Models\InvoiceSystem::where('client_id', $ids)->first();
		$output='';
		$output.='<tr>
			<td align="left">'.$invoicelist->invoice_number.'</td>
			<td align="left">'.$invoicelist->invoice_date.'</td>
			<td align="left">'.$invoicelist->client_id.'</td>
			<td align="left">'.$invoicelist->inv_net.'</td>
			<td align="left">'.$invoicelist->vat_rate.'</td>
			<td align="left"></td>
			</tr>';
			echo $output;*/
		$ids = explode(",",$request->get('value'));
		$quoted_id = '';
		foreach($ids as $id)
		{
			if($quoted_id == "")
			{
				$quoted_id = '"'.$id.'"';
			}
			else{
				$quoted_id = $quoted_id.',"'.$id.'"';
			}
		}
		if(isset($_POST['fromdate']))
		{
			$from = explode("-",$request->get('fromdate'));
			$to = explode("-",$request->get('todate'));
			$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
			$todate = $to[2].'-'.$to[0].'-'.$to[1];
			$startdate = strtotime($fromdate);
			$enddate = strtotime($todate);
			$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');
		}
		else{
			$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');
		}
		$output = '';
		$i=1;
		if(($invoicelist)){
				foreach($invoicelist as $key => $invoice)
				{
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}
					$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
		                            $jids = '';
	                if($get_journal_ids)
	                {
	                  $jids = $get_journal_ids->connecting_journal_reference;
	                }
					$output.='<tr>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->invoice_number.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->client_id.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$company.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->inv_net).'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->vat_value).'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->gross).'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$jids.'</td>
								</tr>';
								$i++;
				}
		}
		echo $output;
	}
	public function invoice_download_report_pdfs(Request $request)
	{
		$htmlval = $request->get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('public/papers/Invoice Report.pdf');
		echo 'Invoice Report.pdf';
	}
	public function report_client_invoice_date_filter(Request $request){
		$id = $request->get('id');
		$from = explode("-",$request->get('fdate'));
		$to = explode("-",$request->get('tdate'));
		$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
		$todate = $to[2].'-'.$to[0].'-'.$to[1];
		$startdate = strtotime($fromdate);
		$enddate = strtotime($todate);
		$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement` from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' GROUP BY `client_id`');
		$output = '';
		$i=1;
		if(($invoicelist)){ 
			foreach($invoicelist as $invoice){ 
				$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
				if($client_details)
				{
					if($client_details->company !== "")
	              {
	                $company = $client_details->company;
	              }
	              else{
	                $company = $client_details->firstname.' & '.$client_details->surname;
	              }
					$count = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement` from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND client_id = "'.$client_details->client_id.'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.'');
					$clientname = $client_details->firstname;
				}
				else{
					$count = array();
					$company = '';
					$clientname = '';
				}
				if($invoice->statement == "No"){
					$textcolor="color:#f00";
				}
				else{
					$textcolor="color:#26BD67";
				}
				$output.='
					<tr>
						<td>'.$i.'</td>
						<td><input type="checkbox" name="select_client" class="select_client class_'.$invoice->client_id.'" data-element="'.$invoice->client_id.'" value="'.$invoice->client_id.'"><label>&nbsp</label></td>
						<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>							
						<td align="left" style="'.$textcolor.'">'.$clientname.'</td>
						<td align="left" style="'.$textcolor.'">'.$company.'</td>
						<td align="center" style="'.$textcolor.'">'.count($count).'</td>
					</tr>
				';
				$i++;
			}						
		}
		if($i == 1)
	    {
	      $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	    }
		echo $output;
	}
	public function import_new_invoice(Request $request)
	{
		if($_FILES['new_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['new_file']['tmp_name'];
			$name=$_FILES['new_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $keyval => $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 10)
					{
						$height = 10;
					}
					else{
						$height = $highestRow;
					}
					$Invno = $worksheet->getCellByColumnAndRow(1, 1); $Invno = trim($Invno->getValue());
					$incdate = $worksheet->getCellByColumnAndRow(2, 1); $incdate = trim($incdate->getValue());
					$client = $worksheet->getCellByColumnAndRow(3, 1); $client = trim($client->getValue());
					$net = $worksheet->getCellByColumnAndRow(4, 1); $net = trim($net->getValue());
					$vatrate = $worksheet->getCellByColumnAndRow(5, 1); $vatrate = trim($vatrate->getValue());
					if($Invno == "Inv No" && $incdate == "Inv Date" && $client == "Client" && $net == "Inv Net" && $vatrate == "VAT Rate")
					{
						$errorrow = array();
						$mainarray = array();
						for ($row = 2; $row <= $height; $row++) {
							$invoice_no = $worksheet->getCellByColumnAndRow(1, $row); $invoice_no = trim($invoice_no->getValue());
							if($invoice_no == "" )
							{
							}
							else{
								$check_invoice = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_number',$invoice_no)->first();
								if($check_invoice)
								{
									$data['invoice_number'] = $invoice_no;
									$invoice_date = $worksheet->getCellByColumnAndRow(2, $row); $invoice_date = trim($invoice_date->getValue());
									$explode_invoice_date = explode("/",$invoice_date);
									$explode_hyphen_invoice_date = explode("-",$invoice_date);
									if(($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));
									}
									elseif(($explode_hyphen_invoice_date) == 3){
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}
									else{
										$unix_date = ($invoice_date - 25569) * 86400;
										$excel_date = 25569 + ($unix_date / 86400);
										$unix_date = ($excel_date - 25569) * 86400;
										$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
									}
									$client = $worksheet->getCellByColumnAndRow(3, $row); $data['client_id'] = trim($client->getValue());
									$invoice_net = $worksheet->getCellByColumnAndRow(4, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));
									$invoice_vatrate = $worksheet->getCellByColumnAndRow(5, $row); 
									$invoice_vatrate = trim($invoice_vatrate->getValue());
									$f_row1 = $worksheet->getCellByColumnAndRow(6, $row); $data['f_row1'] = $f_row1->getValue();
									$g_row2 = $worksheet->getCellByColumnAndRow(7, $row); $data['g_row2'] = $g_row2->getValue();
									$h_row3 = $worksheet->getCellByColumnAndRow(8, $row); $data['h_row3'] = $h_row3->getValue();
									$i_row4 = $worksheet->getCellByColumnAndRow(9, $row); $data['i_row4'] = $i_row4->getValue();
									$j_row5 = $worksheet->getCellByColumnAndRow(10, $row); $data['j_row5'] = $j_row5->getValue();
									$k_row6 = $worksheet->getCellByColumnAndRow(11, $row); $data['k_row6'] = $k_row6->getValue();
									$l_row7 = $worksheet->getCellByColumnAndRow(12, $row); $data['l_row7'] = $l_row7->getValue();
									$m_row8 = $worksheet->getCellByColumnAndRow(13, $row); $data['m_row8'] = $m_row8->getValue();
									$n_row9 = $worksheet->getCellByColumnAndRow(14, $row); $data['n_row9'] = $n_row9->getValue();
									$o_row10 = $worksheet->getCellByColumnAndRow(15, $row); $data['o_row10'] = $o_row10->getValue();
									$p_row11 = $worksheet->getCellByColumnAndRow(16, $row); $data['p_row11'] = $p_row11->getValue();
									$q_row12 = $worksheet->getCellByColumnAndRow(17, $row); $data['q_row12'] = $q_row12->getValue();
									$r_row13 = $worksheet->getCellByColumnAndRow(18, $row); $data['r_row13'] = $r_row13->getValue();
									$s_row14 = $worksheet->getCellByColumnAndRow(19, $row); $data['s_row14'] = $s_row14->getValue();
									$t_row15 = $worksheet->getCellByColumnAndRow(20, $row); $data['t_row15'] = $t_row15->getValue();
									$u_row16 = $worksheet->getCellByColumnAndRow(21, $row); $data['u_row16'] = $u_row16->getValue();
									$v_row17 = $worksheet->getCellByColumnAndRow(22, $row); $data['v_row17'] = $v_row17->getValue();
									$w_row18 = $worksheet->getCellByColumnAndRow(23, $row); $data['w_row18'] = $w_row18->getValue();
									$x_row19 = $worksheet->getCellByColumnAndRow(24, $row); $data['x_row19'] = $x_row19->getValue();
									$y_row20 = $worksheet->getCellByColumnAndRow(25, $row); $data['y_row20'] = $y_row20->getValue();
									$z_row1 = $worksheet->getCellByColumnAndRow(26, $row); $data['z_row1'] = $z_row1->getValue();
									$aa_row2 = $worksheet->getCellByColumnAndRow(27, $row); $data['aa_row2'] = $aa_row2->getValue();
									$ab_row3 = $worksheet->getCellByColumnAndRow(28, $row); $data['ab_row3'] = $ab_row3->getValue();
									$ac_row4 = $worksheet->getCellByColumnAndRow(29, $row); $data['ac_row4'] = $ac_row4->getValue();
									$ad_row5 = $worksheet->getCellByColumnAndRow(30, $row); $data['ad_row5'] = $ad_row5->getValue();
									$ae_row6 = $worksheet->getCellByColumnAndRow(31, $row); $data['ae_row6'] = $ae_row6->getValue();
									$af_row7 = $worksheet->getCellByColumnAndRow(32, $row); $data['af_row7'] = $af_row7->getValue();
									$ag_row8 = $worksheet->getCellByColumnAndRow(33, $row); $data['ag_row8'] = $ag_row8->getValue();
									$ah_row9 = $worksheet->getCellByColumnAndRow(34, $row); $data['ah_row9'] = $ah_row9->getValue();
									$ai_row10 = $worksheet->getCellByColumnAndRow(35, $row); $data['ai_row10'] = $ai_row10->getValue();
									$aj_row11 = $worksheet->getCellByColumnAndRow(36, $row); $data['aj_row11'] = $aj_row11->getValue();
									$ak_row12 = $worksheet->getCellByColumnAndRow(37, $row); $data['ak_row12'] = $ak_row12->getValue();
									$al_row13 = $worksheet->getCellByColumnAndRow(38, $row); $data['al_row13'] = $al_row13->getValue();
									$am_row14 = $worksheet->getCellByColumnAndRow(39, $row); $data['am_row14'] = $am_row14->getValue();
									$an_row15 = $worksheet->getCellByColumnAndRow(40, $row); $data['an_row15'] = $an_row15->getValue();
									$ao_row16 = $worksheet->getCellByColumnAndRow(41, $row); $data['ao_row16'] = $ao_row16->getValue();
									$ap_row17 = $worksheet->getCellByColumnAndRow(42, $row); $data['ap_row17'] = $ap_row17->getValue();
									$aq_row18 = $worksheet->getCellByColumnAndRow(43, $row); $data['aq_row18'] = $aq_row18->getValue();
									$ar_row19 = $worksheet->getCellByColumnAndRow(44, $row); $data['ar_row19'] = $ar_row19->getValue();
									$as_row20 = $worksheet->getCellByColumnAndRow(45, $row); $data['as_row20'] = $as_row20->getValue();
									$at_row1 = $worksheet->getCellByColumnAndRow(46, $row); $data['at_row1'] = $at_row1->getValue();
									$au_row2 = $worksheet->getCellByColumnAndRow(47, $row); $data['au_row2'] = $au_row2->getValue();
									$av_row3 = $worksheet->getCellByColumnAndRow(48, $row); $data['av_row3'] = $av_row3->getValue();
									$aw_row4 = $worksheet->getCellByColumnAndRow(49, $row); $data['aw_row4'] = $aw_row4->getValue();
									$ax_row5 = $worksheet->getCellByColumnAndRow(50, $row); $data['ax_row5'] = $ax_row5->getValue();
									$ay_row6 = $worksheet->getCellByColumnAndRow(51, $row); $data['ay_row6'] = $ay_row6->getValue();
									$az_row7 = $worksheet->getCellByColumnAndRow(52, $row); $data['az_row7'] = $az_row7->getValue();
									$ba_row8 = $worksheet->getCellByColumnAndRow(53, $row); $data['ba_row8'] = $ba_row8->getValue();
									$bb_row9 = $worksheet->getCellByColumnAndRow(54, $row); $data['bb_row9'] = $bb_row9->getValue();
									$bc_row10 = $worksheet->getCellByColumnAndRow(55, $row); $data['bc_row10'] = $bc_row10->getValue();
									$bd_row11 = $worksheet->getCellByColumnAndRow(56, $row); $data['bd_row11'] = $bd_row11->getValue();
									$be_row12 = $worksheet->getCellByColumnAndRow(57, $row); $data['be_row12'] = $be_row12->getValue();
									$bf_row13 = $worksheet->getCellByColumnAndRow(58, $row); $data['bf_row13'] = $bf_row13->getValue();
									$bg_row14 = $worksheet->getCellByColumnAndRow(59, $row); $data['bg_row14'] = $bg_row14->getValue();
									$bh_row15 = $worksheet->getCellByColumnAndRow(60, $row); $data['bh_row15'] = $bh_row15->getValue();
									$bi_row16 = $worksheet->getCellByColumnAndRow(61, $row); $data['bi_row16'] = $bi_row16->getValue();
									$bj_row17 = $worksheet->getCellByColumnAndRow(62, $row); $data['bj_row17'] = $bj_row17->getValue();
									$bk_row18 = $worksheet->getCellByColumnAndRow(63, $row); $data['bk_row18'] = $bk_row18->getValue();
									$bl_row19 = $worksheet->getCellByColumnAndRow(64, $row); $data['bl_row19'] = $bl_row19->getValue();
									$bm_row20 = $worksheet->getCellByColumnAndRow(65, $row); $data['bm_row20'] = $bm_row20->getValue();
									$bn_row1 = $worksheet->getCellByColumnAndRow(66, $row); $data['bn_row1'] = $bn_row1->getValue();
									$bo_row2 = $worksheet->getCellByColumnAndRow(67, $row); $data['bo_row2'] = $bo_row2->getValue();
									$bp_row3 = $worksheet->getCellByColumnAndRow(68, $row); $data['bp_row3'] = $bp_row3->getValue();
									$bq_row4 = $worksheet->getCellByColumnAndRow(69, $row); $data['bq_row4'] = $bq_row4->getValue();
									$br_row5 = $worksheet->getCellByColumnAndRow(70, $row); $data['br_row5'] = $br_row5->getValue();
									$bs_row6 = $worksheet->getCellByColumnAndRow(71, $row); $data['bs_row6'] = $bs_row6->getValue();
									$bt_row7 = $worksheet->getCellByColumnAndRow(72, $row); $data['bt_row7'] = $bt_row7->getValue();
									$bu_row8 = $worksheet->getCellByColumnAndRow(73, $row); $data['bu_row8'] = $bu_row8->getValue();
									$bv_row9 = $worksheet->getCellByColumnAndRow(74, $row); $data['bv_row9'] = $bv_row9->getValue();
									$bw_row10 = $worksheet->getCellByColumnAndRow(75, $row); $data['bw_row10'] = $bw_row10->getValue();
									$bx_row11 = $worksheet->getCellByColumnAndRow(76, $row); $data['bx_row11'] = $bx_row11->getValue();
									$by_row12 = $worksheet->getCellByColumnAndRow(77, $row); $data['by_row12'] = $by_row12->getValue();
									$bz_row13 = $worksheet->getCellByColumnAndRow(78, $row); $data['bz_row13'] = $bz_row13->getValue();
									$ca_row14 = $worksheet->getCellByColumnAndRow(79, $row); $data['ca_row14'] = $ca_row14->getValue();
									$cb_row15 = $worksheet->getCellByColumnAndRow(80, $row); $data['cb_row15'] = $cb_row15->getValue();
									$cc_row16 = $worksheet->getCellByColumnAndRow(81, $row); $data['cc_row16'] = $cc_row16->getValue();
									$cd_row17 = $worksheet->getCellByColumnAndRow(82, $row); $data['cd_row17'] = $cd_row17->getValue();
									$ce_row18 = $worksheet->getCellByColumnAndRow(83, $row); $data['ce_row18'] = $ce_row18->getValue();
									$cf_row19 = $worksheet->getCellByColumnAndRow(84, $row); $data['cf_row19'] = $cf_row19->getValue();
									$cg_row20 = $worksheet->getCellByColumnAndRow(85, $row); $data['cg_row20'] = $cg_row20->getValue();
									$ch_invoice_number = $worksheet->getCellByColumnAndRow(86, $row); $data['ch_invoice_number'] = $ch_invoice_number->getValue();
									$ci_year_end = $worksheet->getCellByColumnAndRow(87, $row); $data['ci_year_end'] = $ci_year_end->getValue();
									$cj1 = $worksheet->getCellByColumnAndRow(88, $row); $data['cj1'] = $cj1->getValue();
									$ck2 = $worksheet->getCellByColumnAndRow(89, $row); $data['ck2'] = $ck2->getValue();
									$cl3 = $worksheet->getCellByColumnAndRow(90, $row); $data['cl3'] = $cl3->getValue();
									$cm4 = $worksheet->getCellByColumnAndRow(91, $row); $data['cm4'] = $cm4->getValue();
									$cn5 = $worksheet->getCellByColumnAndRow(92, $row); $data['cn5'] = $cn5->getValue();
									$co_abridgedinc = $worksheet->getCellByColumnAndRow(93, $row); $data['co_abridgedinc'] = $co_abridgedinc->getValue();
									$cp_sor = $worksheet->getCellByColumnAndRow(94, $row); $data['cp_sor'] = $cp_sor->getValue();
									$cq_adjnote = $worksheet->getCellByColumnAndRow(95, $row); $data['cq_adjnote'] = $cq_adjnote->getValue();
									$cr_position = $worksheet->getCellByColumnAndRow(96, $row); $data['cr_position'] = $cr_position->getValue();
									$cs_liability = $worksheet->getCellByColumnAndRow(97, $row); $data['cs_liability'] = $cs_liability->getValue();
									$ct_prelim = $worksheet->getCellByColumnAndRow(98, $row); $data['ct_prelim'] = $ct_prelim->getValue();
									$cu_paydate = $worksheet->getCellByColumnAndRow(99, $row); $data['cu_paydate'] = $cu_paydate->getValue();
									$cv_included = $worksheet->getCellByColumnAndRow(100, $row); $data['cv_included'] = $cv_included->getValue();
									$cw_liability = $worksheet->getCellByColumnAndRow(101, $row); $data['cw_liability'] = $cw_liability->getValue();
									$cx_prelim = $worksheet->getCellByColumnAndRow(102, $row); $data['cx_prelim'] = $cx_prelim->getValue();
									$cy_paydate = $worksheet->getCellByColumnAndRow(103, $row); $data['cy_paydate'] = $cy_paydate->getValue();
									$cz_invoice = $worksheet->getCellByColumnAndRow(104, $row); $data['cz_invoice'] = $cz_invoice->getValue();
									$da_blank1 = $worksheet->getCellByColumnAndRow(105, $row); $data['da_blank1'] = $da_blank1->getValue();
									$db_blank2 = $worksheet->getCellByColumnAndRow(106, $row); $data['db_blank2'] = $db_blank2->getValue();
									$dc_blank3 = $worksheet->getCellByColumnAndRow(107, $row); $data['dc_blank3'] = $dc_blank3->getValue();
									$statement = $worksheet->getCellByColumnAndRow(108, $row); $data['statement'] = $statement->getValue();
									if($data['statement'] != "Yes" && $data['statement'] != 'No'){
										$data['statement'] = 'Yes';
									}
									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$data['vat_rate'] = $inc_vat_rate * 100;
									$vat_percentage = $inc_vat_rate;
									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);
									$db_id = $check_invoice->id;
									$data['practice_code'] = Session::get('user_practice_code');
									\App\Models\InvoiceSystem::where('id',$db_id)->update($data);
								}
								else{
									$data['invoice_number'] = $invoice_no;
									$invoice_date = $worksheet->getCellByColumnAndRow(2, $row); $invoice_date = trim($invoice_date->getValue());
									$explode_invoice_date = explode("/",$invoice_date);
									$explode_hyphen_invoice_date = explode("-",$invoice_date);
									if(($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));
									}
									elseif(($explode_hyphen_invoice_date) == 3){
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}
									else{
										$unix_date = ($invoice_date - 25569) * 86400;
										$excel_date = 25569 + ($unix_date / 86400);
										$unix_date = ($excel_date - 25569) * 86400;
										$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
									}
									$client = $worksheet->getCellByColumnAndRow(3, $row); $data['client_id'] = trim($client->getValue());
									$invoice_net = $worksheet->getCellByColumnAndRow(4, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));
									$invoice_vatrate = $worksheet->getCellByColumnAndRow(5, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());
									$f_row1 = $worksheet->getCellByColumnAndRow(6, $row); $data['f_row1'] = $f_row1->getValue();
									$g_row2 = $worksheet->getCellByColumnAndRow(7, $row); $data['g_row2'] = $g_row2->getValue();
									$h_row3 = $worksheet->getCellByColumnAndRow(8, $row); $data['h_row3'] = $h_row3->getValue();
									$i_row4 = $worksheet->getCellByColumnAndRow(9, $row); $data['i_row4'] = $i_row4->getValue();
									$j_row5 = $worksheet->getCellByColumnAndRow(10, $row); $data['j_row5'] = $j_row5->getValue();
									$k_row6 = $worksheet->getCellByColumnAndRow(11, $row); $data['k_row6'] = $k_row6->getValue();
									$l_row7 = $worksheet->getCellByColumnAndRow(12, $row); $data['l_row7'] = $l_row7->getValue();
									$m_row8 = $worksheet->getCellByColumnAndRow(13, $row); $data['m_row8'] = $m_row8->getValue();
									$n_row9 = $worksheet->getCellByColumnAndRow(14, $row); $data['n_row9'] = $n_row9->getValue();
									$o_row10 = $worksheet->getCellByColumnAndRow(15, $row); $data['o_row10'] = $o_row10->getValue();
									$p_row11 = $worksheet->getCellByColumnAndRow(16, $row); $data['p_row11'] = $p_row11->getValue();
									$q_row12 = $worksheet->getCellByColumnAndRow(17, $row); $data['q_row12'] = $q_row12->getValue();
									$r_row13 = $worksheet->getCellByColumnAndRow(18, $row); $data['r_row13'] = $r_row13->getValue();
									$s_row14 = $worksheet->getCellByColumnAndRow(19, $row); $data['s_row14'] = $s_row14->getValue();
									$t_row15 = $worksheet->getCellByColumnAndRow(20, $row); $data['t_row15'] = $t_row15->getValue();
									$u_row16 = $worksheet->getCellByColumnAndRow(21, $row); $data['u_row16'] = $u_row16->getValue();
									$v_row17 = $worksheet->getCellByColumnAndRow(22, $row); $data['v_row17'] = $v_row17->getValue();
									$w_row18 = $worksheet->getCellByColumnAndRow(23, $row); $data['w_row18'] = $w_row18->getValue();
									$x_row19 = $worksheet->getCellByColumnAndRow(24, $row); $data['x_row19'] = $x_row19->getValue();
									$y_row20 = $worksheet->getCellByColumnAndRow(25, $row); $data['y_row20'] = $y_row20->getValue();
									$z_row1 = $worksheet->getCellByColumnAndRow(26, $row); $data['z_row1'] = $z_row1->getValue();
									$aa_row2 = $worksheet->getCellByColumnAndRow(27, $row); $data['aa_row2'] = $aa_row2->getValue();
									$ab_row3 = $worksheet->getCellByColumnAndRow(28, $row); $data['ab_row3'] = $ab_row3->getValue();
									$ac_row4 = $worksheet->getCellByColumnAndRow(29, $row); $data['ac_row4'] = $ac_row4->getValue();
									$ad_row5 = $worksheet->getCellByColumnAndRow(30, $row); $data['ad_row5'] = $ad_row5->getValue();
									$ae_row6 = $worksheet->getCellByColumnAndRow(31, $row); $data['ae_row6'] = $ae_row6->getValue();
									$af_row7 = $worksheet->getCellByColumnAndRow(32, $row); $data['af_row7'] = $af_row7->getValue();
									$ag_row8 = $worksheet->getCellByColumnAndRow(33, $row); $data['ag_row8'] = $ag_row8->getValue();
									$ah_row9 = $worksheet->getCellByColumnAndRow(34, $row); $data['ah_row9'] = $ah_row9->getValue();
									$ai_row10 = $worksheet->getCellByColumnAndRow(35, $row); $data['ai_row10'] = $ai_row10->getValue();
									$aj_row11 = $worksheet->getCellByColumnAndRow(36, $row); $data['aj_row11'] = $aj_row11->getValue();
									$ak_row12 = $worksheet->getCellByColumnAndRow(37, $row); $data['ak_row12'] = $ak_row12->getValue();
									$al_row13 = $worksheet->getCellByColumnAndRow(38, $row); $data['al_row13'] = $al_row13->getValue();
									$am_row14 = $worksheet->getCellByColumnAndRow(39, $row); $data['am_row14'] = $am_row14->getValue();
									$an_row15 = $worksheet->getCellByColumnAndRow(40, $row); $data['an_row15'] = $an_row15->getValue();
									$ao_row16 = $worksheet->getCellByColumnAndRow(41, $row); $data['ao_row16'] = $ao_row16->getValue();
									$ap_row17 = $worksheet->getCellByColumnAndRow(42, $row); $data['ap_row17'] = $ap_row17->getValue();
									$aq_row18 = $worksheet->getCellByColumnAndRow(43, $row); $data['aq_row18'] = $aq_row18->getValue();
									$ar_row19 = $worksheet->getCellByColumnAndRow(44, $row); $data['ar_row19'] = $ar_row19->getValue();
									$as_row20 = $worksheet->getCellByColumnAndRow(45, $row); $data['as_row20'] = $as_row20->getValue();
									$at_row1 = $worksheet->getCellByColumnAndRow(46, $row); $data['at_row1'] = $at_row1->getValue();
									$au_row2 = $worksheet->getCellByColumnAndRow(47, $row); $data['au_row2'] = $au_row2->getValue();
									$av_row3 = $worksheet->getCellByColumnAndRow(48, $row); $data['av_row3'] = $av_row3->getValue();
									$aw_row4 = $worksheet->getCellByColumnAndRow(49, $row); $data['aw_row4'] = $aw_row4->getValue();
									$ax_row5 = $worksheet->getCellByColumnAndRow(50, $row); $data['ax_row5'] = $ax_row5->getValue();
									$ay_row6 = $worksheet->getCellByColumnAndRow(51, $row); $data['ay_row6'] = $ay_row6->getValue();
									$az_row7 = $worksheet->getCellByColumnAndRow(52, $row); $data['az_row7'] = $az_row7->getValue();
									$ba_row8 = $worksheet->getCellByColumnAndRow(53, $row); $data['ba_row8'] = $ba_row8->getValue();
									$bb_row9 = $worksheet->getCellByColumnAndRow(54, $row); $data['bb_row9'] = $bb_row9->getValue();
									$bc_row10 = $worksheet->getCellByColumnAndRow(55, $row); $data['bc_row10'] = $bc_row10->getValue();
									$bd_row11 = $worksheet->getCellByColumnAndRow(56, $row); $data['bd_row11'] = $bd_row11->getValue();
									$be_row12 = $worksheet->getCellByColumnAndRow(57, $row); $data['be_row12'] = $be_row12->getValue();
									$bf_row13 = $worksheet->getCellByColumnAndRow(58, $row); $data['bf_row13'] = $bf_row13->getValue();
									$bg_row14 = $worksheet->getCellByColumnAndRow(59, $row); $data['bg_row14'] = $bg_row14->getValue();
									$bh_row15 = $worksheet->getCellByColumnAndRow(60, $row); $data['bh_row15'] = $bh_row15->getValue();
									$bi_row16 = $worksheet->getCellByColumnAndRow(61, $row); $data['bi_row16'] = $bi_row16->getValue();
									$bj_row17 = $worksheet->getCellByColumnAndRow(62, $row); $data['bj_row17'] = $bj_row17->getValue();
									$bk_row18 = $worksheet->getCellByColumnAndRow(63, $row); $data['bk_row18'] = $bk_row18->getValue();
									$bl_row19 = $worksheet->getCellByColumnAndRow(64, $row); $data['bl_row19'] = $bl_row19->getValue();
									$bm_row20 = $worksheet->getCellByColumnAndRow(65, $row); $data['bm_row20'] = $bm_row20->getValue();
									$bn_row1 = $worksheet->getCellByColumnAndRow(66, $row); $data['bn_row1'] = $bn_row1->getValue();
									$bo_row2 = $worksheet->getCellByColumnAndRow(67, $row); $data['bo_row2'] = $bo_row2->getValue();
									$bp_row3 = $worksheet->getCellByColumnAndRow(68, $row); $data['bp_row3'] = $bp_row3->getValue();
									$bq_row4 = $worksheet->getCellByColumnAndRow(69, $row); $data['bq_row4'] = $bq_row4->getValue();
									$br_row5 = $worksheet->getCellByColumnAndRow(70, $row); $data['br_row5'] = $br_row5->getValue();
									$bs_row6 = $worksheet->getCellByColumnAndRow(71, $row); $data['bs_row6'] = $bs_row6->getValue();
									$bt_row7 = $worksheet->getCellByColumnAndRow(72, $row); $data['bt_row7'] = $bt_row7->getValue();
									$bu_row8 = $worksheet->getCellByColumnAndRow(73, $row); $data['bu_row8'] = $bu_row8->getValue();
									$bv_row9 = $worksheet->getCellByColumnAndRow(74, $row); $data['bv_row9'] = $bv_row9->getValue();
									$bw_row10 = $worksheet->getCellByColumnAndRow(75, $row); $data['bw_row10'] = $bw_row10->getValue();
									$bx_row11 = $worksheet->getCellByColumnAndRow(76, $row); $data['bx_row11'] = $bx_row11->getValue();
									$by_row12 = $worksheet->getCellByColumnAndRow(77, $row); $data['by_row12'] = $by_row12->getValue();
									$bz_row13 = $worksheet->getCellByColumnAndRow(78, $row); $data['bz_row13'] = $bz_row13->getValue();
									$ca_row14 = $worksheet->getCellByColumnAndRow(79, $row); $data['ca_row14'] = $ca_row14->getValue();
									$cb_row15 = $worksheet->getCellByColumnAndRow(80, $row); $data['cb_row15'] = $cb_row15->getValue();
									$cc_row16 = $worksheet->getCellByColumnAndRow(81, $row); $data['cc_row16'] = $cc_row16->getValue();
									$cd_row17 = $worksheet->getCellByColumnAndRow(82, $row); $data['cd_row17'] = $cd_row17->getValue();
									$ce_row18 = $worksheet->getCellByColumnAndRow(83, $row); $data['ce_row18'] = $ce_row18->getValue();
									$cf_row19 = $worksheet->getCellByColumnAndRow(84, $row); $data['cf_row19'] = $cf_row19->getValue();
									$cg_row20 = $worksheet->getCellByColumnAndRow(85, $row); $data['cg_row20'] = $cg_row20->getValue();
									$ch_invoice_number = $worksheet->getCellByColumnAndRow(86, $row); $data['ch_invoice_number'] = $ch_invoice_number->getValue();
									$ci_year_end = $worksheet->getCellByColumnAndRow(87, $row); $data['ci_year_end'] = $ci_year_end->getValue();
									$cj1 = $worksheet->getCellByColumnAndRow(88, $row); $data['cj1'] = $cj1->getValue();
									$ck2 = $worksheet->getCellByColumnAndRow(89, $row); $data['ck2'] = $ck2->getValue();
									$cl3 = $worksheet->getCellByColumnAndRow(90, $row); $data['cl3'] = $cl3->getValue();
									$cm4 = $worksheet->getCellByColumnAndRow(91, $row); $data['cm4'] = $cm4->getValue();
									$cn5 = $worksheet->getCellByColumnAndRow(92, $row); $data['cn5'] = $cn5->getValue();
									$co_abridgedinc = $worksheet->getCellByColumnAndRow(93, $row); $data['co_abridgedinc'] = $co_abridgedinc->getValue();
									$cp_sor = $worksheet->getCellByColumnAndRow(94, $row); $data['cp_sor'] = $cp_sor->getValue();
									$cq_adjnote = $worksheet->getCellByColumnAndRow(95, $row); $data['cq_adjnote'] = $cq_adjnote->getValue();
									$cr_position = $worksheet->getCellByColumnAndRow(96, $row); $data['cr_position'] = $cr_position->getValue();
									$cs_liability = $worksheet->getCellByColumnAndRow(97, $row); $data['cs_liability'] = $cs_liability->getValue();
									$ct_prelim = $worksheet->getCellByColumnAndRow(98, $row); $data['ct_prelim'] = $ct_prelim->getValue();
									$cu_paydate = $worksheet->getCellByColumnAndRow(99, $row); $data['cu_paydate'] = $cu_paydate->getValue();
									$cv_included = $worksheet->getCellByColumnAndRow(100, $row); $data['cv_included'] = $cv_included->getValue();
									$cw_liability = $worksheet->getCellByColumnAndRow(101, $row); $data['cw_liability'] = $cw_liability->getValue();
									$cx_prelim = $worksheet->getCellByColumnAndRow(102, $row); $data['cx_prelim'] = $cx_prelim->getValue();
									$cy_paydate = $worksheet->getCellByColumnAndRow(103, $row); $data['cy_paydate'] = $cy_paydate->getValue();
									$cz_invoice = $worksheet->getCellByColumnAndRow(104, $row); $data['cz_invoice'] = $cz_invoice->getValue();
									$da_blank1 = $worksheet->getCellByColumnAndRow(105, $row); $data['da_blank1'] = $da_blank1->getValue();
									$db_blank2 = $worksheet->getCellByColumnAndRow(106, $row); $data['db_blank2'] = $db_blank2->getValue();
									$dc_blank3 = $worksheet->getCellByColumnAndRow(107, $row); $data['dc_blank3'] = $dc_blank3->getValue();
									$statement = $worksheet->getCellByColumnAndRow(108, $row); $data['statement'] = $statement->getValue();
									if($data['statement'] != "Yes" && $data['statement'] != 'No'){
										$data['statement'] = 'Yes';
									}
									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$data['vat_rate'] = $inc_vat_rate * 100;
									$vat_percentage = $inc_vat_rate;
									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);
									$data['practice_code'] = Session::get('user_practice_code');
									\App\Models\InvoiceSystem::insert($data);
								}
							}
						}
					}
					else{
						return redirect('user/invoice_management')->with('message', 'Import Failed! Invalid Import File');
					}
				}
				$out = '';
				if(($errorlist))
				{
					foreach($errorlist as $error) {
	                    $out.='<p class="error_class">'.$error.'</p>';
	                }
				}
				if($height >= $highestRow)
				{
					if($out != '')
					{
						return redirect('user/invoice_management')->with('success_error', $out);
					}
					else{
						return redirect('user/invoice_management')->with('message', 'Invoice Imported successfully.');
					}
				}
				else{
					return redirect('user/invoice_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&out='.$out.'');
				}
			}
		}
	}
	public function import_new_invoice_one(Request $request)
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
			$roundcount = $round * 10;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$errorrow = array();
			$mainarray = array();
			$errorlist = array();
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$invoice_no = $worksheet->getCellByColumnAndRow(1, $row); $invoice_no = trim($invoice_no->getValue());
				if($invoice_no == "" )
				{
				}
				else{
					$check_invoice = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_number',$invoice_no)->first();
					if($check_invoice)
					{
						$data['invoice_number'] = $invoice_no;
						$invoice_date = $worksheet->getCellByColumnAndRow(2, $row); $invoice_date = trim($invoice_date->getValue());
						$explode_invoice_date = explode("/",$invoice_date);
						$explode_hyphen_invoice_date = explode("-",$invoice_date);
						if(($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));
						}
						elseif(($explode_hyphen_invoice_date) == 3){
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}
						else{
							$unix_date = ($invoice_date - 25569) * 86400;
							$excel_date = 25569 + ($unix_date / 86400);
							$unix_date = ($excel_date - 25569) * 86400;
							$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
						}
						$client = $worksheet->getCellByColumnAndRow(3, $row); $data['client_id'] = trim($client->getValue());
						$invoice_net = $worksheet->getCellByColumnAndRow(4, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));
						$invoice_vatrate = $worksheet->getCellByColumnAndRow(5, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());
						$f_row1 = $worksheet->getCellByColumnAndRow(6, $row); $data['f_row1'] = $f_row1->getValue();
						$g_row2 = $worksheet->getCellByColumnAndRow(7, $row); $data['g_row2'] = $g_row2->getValue();
						$h_row3 = $worksheet->getCellByColumnAndRow(8, $row); $data['h_row3'] = $h_row3->getValue();
						$i_row4 = $worksheet->getCellByColumnAndRow(9, $row); $data['i_row4'] = $i_row4->getValue();
						$j_row5 = $worksheet->getCellByColumnAndRow(10, $row); $data['j_row5'] = $j_row5->getValue();
						$k_row6 = $worksheet->getCellByColumnAndRow(11, $row); $data['k_row6'] = $k_row6->getValue();
						$l_row7 = $worksheet->getCellByColumnAndRow(12, $row); $data['l_row7'] = $l_row7->getValue();
						$m_row8 = $worksheet->getCellByColumnAndRow(13, $row); $data['m_row8'] = $m_row8->getValue();
						$n_row9 = $worksheet->getCellByColumnAndRow(14, $row); $data['n_row9'] = $n_row9->getValue();
						$o_row10 = $worksheet->getCellByColumnAndRow(15, $row); $data['o_row10'] = $o_row10->getValue();
						$p_row11 = $worksheet->getCellByColumnAndRow(16, $row); $data['p_row11'] = $p_row11->getValue();
						$q_row12 = $worksheet->getCellByColumnAndRow(17, $row); $data['q_row12'] = $q_row12->getValue();
						$r_row13 = $worksheet->getCellByColumnAndRow(18, $row); $data['r_row13'] = $r_row13->getValue();
						$s_row14 = $worksheet->getCellByColumnAndRow(19, $row); $data['s_row14'] = $s_row14->getValue();
						$t_row15 = $worksheet->getCellByColumnAndRow(20, $row); $data['t_row15'] = $t_row15->getValue();
						$u_row16 = $worksheet->getCellByColumnAndRow(21, $row); $data['u_row16'] = $u_row16->getValue();
						$v_row17 = $worksheet->getCellByColumnAndRow(22, $row); $data['v_row17'] = $v_row17->getValue();
						$w_row18 = $worksheet->getCellByColumnAndRow(23, $row); $data['w_row18'] = $w_row18->getValue();
						$x_row19 = $worksheet->getCellByColumnAndRow(24, $row); $data['x_row19'] = $x_row19->getValue();
						$y_row20 = $worksheet->getCellByColumnAndRow(25, $row); $data['y_row20'] = $y_row20->getValue();
						$z_row1 = $worksheet->getCellByColumnAndRow(26, $row); $data['z_row1'] = $z_row1->getValue();
						$aa_row2 = $worksheet->getCellByColumnAndRow(27, $row); $data['aa_row2'] = $aa_row2->getValue();
						$ab_row3 = $worksheet->getCellByColumnAndRow(28, $row); $data['ab_row3'] = $ab_row3->getValue();
						$ac_row4 = $worksheet->getCellByColumnAndRow(29, $row); $data['ac_row4'] = $ac_row4->getValue();
						$ad_row5 = $worksheet->getCellByColumnAndRow(30, $row); $data['ad_row5'] = $ad_row5->getValue();
						$ae_row6 = $worksheet->getCellByColumnAndRow(31, $row); $data['ae_row6'] = $ae_row6->getValue();
						$af_row7 = $worksheet->getCellByColumnAndRow(32, $row); $data['af_row7'] = $af_row7->getValue();
						$ag_row8 = $worksheet->getCellByColumnAndRow(33, $row); $data['ag_row8'] = $ag_row8->getValue();
						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = $ah_row9->getValue();
						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = $ai_row10->getValue();
						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = $aj_row11->getValue();
						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = $ak_row12->getValue();
						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = $al_row13->getValue();
						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = $am_row14->getValue();
						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = $an_row15->getValue();
						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = $ao_row16->getValue();
						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = $ap_row17->getValue();
						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = $aq_row18->getValue();
						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = $ar_row19->getValue();
						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = $as_row20->getValue();
						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = $at_row1->getValue();
						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = $au_row2->getValue();
						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = $av_row3->getValue();
						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = $aw_row4->getValue();
						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = $ax_row5->getValue();
						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = $ay_row6->getValue();
						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = $az_row7->getValue();
						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = $ba_row8->getValue();
						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = $bb_row9->getValue();
						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = $bc_row10->getValue();
						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = $bd_row11->getValue();
						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = $be_row12->getValue();
						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = $bf_row13->getValue();
						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = $bg_row14->getValue();
						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = $bh_row15->getValue();
						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = $bi_row16->getValue();
						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = $bj_row17->getValue();
						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = $bk_row18->getValue();
						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = $bl_row19->getValue();
						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = $bm_row20->getValue();
						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = $bn_row1->getValue();
						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = $bo_row2->getValue();
						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = $bp_row3->getValue();
						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = $bq_row4->getValue();
						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = $br_row5->getValue();
						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = $bs_row6->getValue();
						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = $bt_row7->getValue();
						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = $bu_row8->getValue();
						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = $bv_row9->getValue();
						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = $bw_row10->getValue();
						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = $bx_row11->getValue();
						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = $by_row12->getValue();
						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = $bz_row13->getValue();
						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = $ca_row14->getValue();
						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = $cb_row15->getValue();
						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = $cc_row16->getValue();
						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = $cd_row17->getValue();
						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = $ce_row18->getValue();
						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = $cf_row19->getValue();
						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = $cg_row20->getValue();
						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = $ch_invoice_number->getValue();
						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = $ci_year_end->getValue();
						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = $cj1->getValue();
						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = $ck2->getValue();
						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = $cl3->getValue();
						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = $cm4->getValue();
						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = $cn5->getValue();
						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = $co_abridgedinc->getValue();
						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = $cp_sor->getValue();
						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = $cq_adjnote->getValue();
						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = $cr_position->getValue();
						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = $cs_liability->getValue();
						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = $ct_prelim->getValue();
						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = $cu_paydate->getValue();
						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = $cv_included->getValue();
						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = $cw_liability->getValue();
						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = $cx_prelim->getValue();
						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = $cy_paydate->getValue();
						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = $cz_invoice->getValue();
						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = $da_blank1->getValue();
						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = $db_blank2->getValue();
						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = $dc_blank3->getValue();
						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = $statement->getValue();
						if($data['statement'] != "Yes" && $data['statement'] != 'No'){
							$data['statement'] = 'Yes';
						}
						$f_row1 = $worksheet->getCellByColumnAndRow(6, $row); $data['f_row1'] = trim($f_row1->getValue());
									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$data['vat_rate'] = $inc_vat_rate * 100;
									$vat_percentage = $inc_vat_rate;
						$data['vat_value'] = $data['inv_net'] * $vat_percentage;
						$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);
						$db_id = $check_invoice->id;
						$data['practice_code'] = Session::get('user_practice_code');
						\App\Models\InvoiceSystem::where('id',$db_id)->update($data);
					}
					else{
						$data['invoice_number'] = $invoice_no;
						$invoice_date = $worksheet->getCellByColumnAndRow(2, $row); $invoice_date = trim($invoice_date->getValue());
						$explode_invoice_date = explode("/",$invoice_date);
						$explode_hyphen_invoice_date = explode("-",$invoice_date);
						if(($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));
						}
						elseif(($explode_hyphen_invoice_date) == 3){
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}
						else{
							$unix_date = ($invoice_date - 25569) * 86400;
							$excel_date = 25569 + ($unix_date / 86400);
							$unix_date = ($excel_date - 25569) * 86400;
							$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
						}
						$client = $worksheet->getCellByColumnAndRow(3, $row); $data['client_id'] = trim($client->getValue());
						$invoice_net = $worksheet->getCellByColumnAndRow(4, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));
						$invoice_vatrate = $worksheet->getCellByColumnAndRow(5, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());
						$f_row1 = $worksheet->getCellByColumnAndRow(6, $row); $data['f_row1'] = $f_row1->getValue();
						$g_row2 = $worksheet->getCellByColumnAndRow(7, $row); $data['g_row2'] = $g_row2->getValue();
						$h_row3 = $worksheet->getCellByColumnAndRow(8, $row); $data['h_row3'] = $h_row3->getValue();
						$i_row4 = $worksheet->getCellByColumnAndRow(9, $row); $data['i_row4'] = $i_row4->getValue();
						$j_row5 = $worksheet->getCellByColumnAndRow(10, $row); $data['j_row5'] = $j_row5->getValue();
						$k_row6 = $worksheet->getCellByColumnAndRow(11, $row); $data['k_row6'] = $k_row6->getValue();
						$l_row7 = $worksheet->getCellByColumnAndRow(12, $row); $data['l_row7'] = $l_row7->getValue();
						$m_row8 = $worksheet->getCellByColumnAndRow(13, $row); $data['m_row8'] = $m_row8->getValue();
						$n_row9 = $worksheet->getCellByColumnAndRow(14, $row); $data['n_row9'] = $n_row9->getValue();
						$o_row10 = $worksheet->getCellByColumnAndRow(15, $row); $data['o_row10'] = $o_row10->getValue();
						$p_row11 = $worksheet->getCellByColumnAndRow(16, $row); $data['p_row11'] = $p_row11->getValue();
						$q_row12 = $worksheet->getCellByColumnAndRow(17, $row); $data['q_row12'] = $q_row12->getValue();
						$r_row13 = $worksheet->getCellByColumnAndRow(18, $row); $data['r_row13'] = $r_row13->getValue();
						$s_row14 = $worksheet->getCellByColumnAndRow(19, $row); $data['s_row14'] = $s_row14->getValue();
						$t_row15 = $worksheet->getCellByColumnAndRow(20, $row); $data['t_row15'] = $t_row15->getValue();
						$u_row16 = $worksheet->getCellByColumnAndRow(21, $row); $data['u_row16'] = $u_row16->getValue();
						$v_row17 = $worksheet->getCellByColumnAndRow(22, $row); $data['v_row17'] = $v_row17->getValue();
						$w_row18 = $worksheet->getCellByColumnAndRow(23, $row); $data['w_row18'] = $w_row18->getValue();
						$x_row19 = $worksheet->getCellByColumnAndRow(24, $row); $data['x_row19'] = $x_row19->getValue();
						$y_row20 = $worksheet->getCellByColumnAndRow(25, $row); $data['y_row20'] = $y_row20->getValue();
						$z_row1 = $worksheet->getCellByColumnAndRow(26, $row); $data['z_row1'] = $z_row1->getValue();
						$aa_row2 = $worksheet->getCellByColumnAndRow(27, $row); $data['aa_row2'] = $aa_row2->getValue();
						$ab_row3 = $worksheet->getCellByColumnAndRow(28, $row); $data['ab_row3'] = $ab_row3->getValue();
						$ac_row4 = $worksheet->getCellByColumnAndRow(29, $row); $data['ac_row4'] = $ac_row4->getValue();
						$ad_row5 = $worksheet->getCellByColumnAndRow(30, $row); $data['ad_row5'] = $ad_row5->getValue();
						$ae_row6 = $worksheet->getCellByColumnAndRow(31, $row); $data['ae_row6'] = $ae_row6->getValue();
						$af_row7 = $worksheet->getCellByColumnAndRow(32, $row); $data['af_row7'] = $af_row7->getValue();
						$ag_row8 = $worksheet->getCellByColumnAndRow(33, $row); $data['ag_row8'] = $ag_row8->getValue();
						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = $ah_row9->getValue();
						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = $ai_row10->getValue();
						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = $aj_row11->getValue();
						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = $ak_row12->getValue();
						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = $al_row13->getValue();
						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = $am_row14->getValue();
						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = $an_row15->getValue();
						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = $ao_row16->getValue();
						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = $ap_row17->getValue();
						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = $aq_row18->getValue();
						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = $ar_row19->getValue();
						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = $as_row20->getValue();
						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = $at_row1->getValue();
						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = $au_row2->getValue();
						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = $av_row3->getValue();
						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = $aw_row4->getValue();
						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = $ax_row5->getValue();
						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = $ay_row6->getValue();
						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = $az_row7->getValue();
						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = $ba_row8->getValue();
						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = $bb_row9->getValue();
						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = $bc_row10->getValue();
						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = $bd_row11->getValue();
						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = $be_row12->getValue();
						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = $bf_row13->getValue();
						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = $bg_row14->getValue();
						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = $bh_row15->getValue();
						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = $bi_row16->getValue();
						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = $bj_row17->getValue();
						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = $bk_row18->getValue();
						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = $bl_row19->getValue();
						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = $bm_row20->getValue();
						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = $bn_row1->getValue();
						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = $bo_row2->getValue();
						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = $bp_row3->getValue();
						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = $bq_row4->getValue();
						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = $br_row5->getValue();
						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = $bs_row6->getValue();
						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = $bt_row7->getValue();
						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = $bu_row8->getValue();
						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = $bv_row9->getValue();
						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = $bw_row10->getValue();
						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = $bx_row11->getValue();
						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = $by_row12->getValue();
						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = $bz_row13->getValue();
						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = $ca_row14->getValue();
						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = $cb_row15->getValue();
						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = $cc_row16->getValue();
						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = $cd_row17->getValue();
						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = $ce_row18->getValue();
						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = $cf_row19->getValue();
						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = $cg_row20->getValue();
						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = $ch_invoice_number->getValue();
						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = $ci_year_end->getValue();
						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = $cj1->getValue();
						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = $ck2->getValue();
						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = $cl3->getValue();
						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = $cm4->getValue();
						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = $cn5->getValue();
						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = $co_abridgedinc->getValue();
						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = $cp_sor->getValue();
						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = $cq_adjnote->getValue();
						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = $cr_position->getValue();
						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = $cs_liability->getValue();
						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = $ct_prelim->getValue();
						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = $cu_paydate->getValue();
						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = $cv_included->getValue();
						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = $cw_liability->getValue();
						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = $cx_prelim->getValue();
						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = $cy_paydate->getValue();
						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = $cz_invoice->getValue();
						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = $da_blank1->getValue();
						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = $db_blank2->getValue();
						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = $dc_blank3->getValue();
						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = $statement->getValue();
						if($data['statement'] != "Yes" && $data['statement'] != 'No'){
							$data['statement'] = 'Yes';
						}
									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$data['vat_rate'] = $inc_vat_rate * 100;
									$vat_percentage = $inc_vat_rate;
									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);
									$data['practice_code'] = Session::get('user_practice_code');
						\App\Models\InvoiceSystem::insert($data);
					}
				}
			}
		}
		$out = $request->get('out');
		if(($errorlist))
		{
			foreach($errorlist as $error) {
                $out.='<p class="error_class">'.$error.'</p>';
            }
		}
		if($height >= $highestRow)
		{
			if($out != '')
			{
				return redirect('user/invoice_management')->with('success_error', $out);
			}
			else{
				return redirect('user/invoice_management')->with('message', 'Invoice Imported successfully.');
			}
		}
		else{
			return redirect('user/invoice_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type_new=1');
		}
	}
	public function invoicesprintview(Request $request){
		$id = $request->get('id');
		$invoice_details = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_number', $id)->first();
		$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice_details->client_id)->first();
		if(!$client_details){
			$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
			echo json_encode(array('companyname' => $companyname));
		}
		else{
			$company_firstname='
          <div class="company_details_div">
	              <div class="firstname_div">
	                <b>To:</b><br/>
	                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
	                '.$client_details->company.'<br/>  
	                '.$client_details->address1.'<br/>  
	                '.$client_details->address2.'<br/>
	                '.$client_details->address3.'
	              </div>
	            </div>
	            <div class="account_details_div">
	              <div class="account_details_main_address_div">
	              		<div class="aib_account">
			                AIB Account: 48870061<br/>
			                Sort Code: 93-72-23<br/>
			                VAT Number: 9754009E<br/>
			                Company Number: 485123
			            </div>	<br/>                 
	                  <div class="account_details_invoice_div">
	                    <div class="account_table">
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Account:</b></div>
	                        <div class="account_row_td right">'.$client_details->client_id.'</div>
	                      </div>
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Invoice:</b></div>
	                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
	                      </div>
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Date:</b></div>
	                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
	                      </div>
	                    </div>
	                  </div>
	              </div>
	            </div>
	            <div class="invoice_label">
              INVOICE
            </div>';
            if($invoice_details->bn_row1 != "")
            {
            	$bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
            }
            else{
            	$bn_row1_add_zero = '';
            }

            	$row1 = '
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';
            
           if($invoice_details->bo_row2 != "")
           {
           	$bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
           }
           else{
           	$bo_row2_add_zero = '';
           }
            	$row2 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
           
            if($invoice_details->bp_row3 != "")
            {
            	$bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
            }
            else{
            	$bp_row3_add_zero = '';
            }
            	$row3 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
            	
            if($invoice_details->bq_row4 != "")
            {
            	$bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
            }
            else{
            	$bq_row4_add_zero = '';
            }
            $row4 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
            	
           if($invoice_details->br_row5 != "")
           {
           	$br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
           }
           else{
           	$br_row5_add_zero = '';
           }
            $row5 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
            	
            if($invoice_details->bs_row6 != "")
            {
            	$bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
            }
            else{
            	$bs_row6_add_zero = '';
            }
           		 $row6 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
            	
            if($invoice_details->bt_row7 != "")
            {
            	$bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
            }
            else{
            	$bt_row7_add_zero = '';
            }
           		 $row7 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';

            	
            if($invoice_details->bu_row8 != "")
            {
            	$bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
            }
            else{
            	$bu_row8_add_zero = '';
            }
           		 $row8 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
            	
            if($invoice_details->bv_row9 != "")
            {
            	$bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
            }
            else{
            	$bv_row9_add_zero = '';
            }
           		 $row9 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
            	
           if($invoice_details->bw_row10 != "")
           {
           	$bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
           }
           else{
           	$bw_row10_add_zero = '';
           }
           		 $row10 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
            	
            if($invoice_details->bx_row11 != "")
            {
            	$bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
            }
            else{
            	$bx_row11_add_zero = '';
            }
           		 $row11 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
            	
           if($invoice_details->by_row12 != "")
           {
           	$by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
           }
           else{
           	$by_row12_add_zero = '';
           }
           		 $row12 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
            
            	
            if($invoice_details->bz_row13 != "")
            {
            	$bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
            }
            else{
            	$bz_row13_add_zero = '';
            }
           		 $row13 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
            
            	
            if($invoice_details->ca_row14 != "")
            {
            	$ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
            }
            else{
            	$ca_row14_add_zero = '';
            }
           		 $row14 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
            
            	
            if($invoice_details->cb_row15 != "")
            {
            	$cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
            }
            else{
            	$cb_row15_add_zero = '';
            }
           		 $row15 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
            
            	
           if($invoice_details->cc_row16 != "")
           {
           	$cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
           }
           else{
           	$cc_row16_add_zero = '';
           }
           		 $row16 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
            
            	
            if($invoice_details->cd_row17 != "")
            {
            	$cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
            }
            else{
            	$cd_row17_add_zero = '';
            }
           		 $row17 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
           
            	
           if($invoice_details->ce_row18 != "")
           {
           	$ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
           }
           else{
           	$ce_row18_add_zero = '';
           }
           		 $row18 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
            
            	
            if($invoice_details->cf_row19 != "")
            {
            	$cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
            }
            else{
            	$cf_row19_add_zero = '';
            }
           		 $row19 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
            
            	
            	
            if($invoice_details->cg_row20 != "")
            {
            	$cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
            }
            else{
            	$cg_row20_add_zero = '';
            }
           		 $row20 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
            
            	
	         $tax_details ='
	         <div class="tax_table_div">
	            <div class="tax_table">
	              <div class="tax_row">
	                <div class="tax_row_td left">Total Fees (as agreed)</div>
	                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">VAT @ '.$invoice_details->vat_rate.'%</div>
	                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left" style="color:#fff">.</div>
	                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">Outlay @ 0%</div>
	                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">Total Fees Due</div>
	                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
	              </div>
	            </div>
	          </div>
	         ';
	         //echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'detailsrow' => $roww_details ));
	         echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'row4' => $row4, 'row5' => $row5, 'row6' => $row6, 'row7' => $row7, 'row8' => $row8,'row9' => $row9, 'row10' => $row10, 'row11' => $row11, 'row12' => $row12, 'row13' => $row13, 'row14' => $row14, 'row15' => $row15, 'row16' => $row16, 'row17' => $row17, 'row18' => $row18, 'row19' => $row19, 'row20' => $row20  ));
	     }
	}
	public function invoice_saveas_pdf(Request $request)
	{
		$inv_no = $request->get('inv_no');
		$html = '<style>
                  @page { margin: 0in; }
                body {
                    background-image: url(' .
                URL::to("public/assets/invoice_letterpad_1.jpg") .
                ');
                    background-position: center;
                    background-size:110%;
                      background-repeat: no-repeat;
                      font-family:Verdana,Geneva,sans-serif; 
                }
                .tax_table_div{width: 100%; margin-left:10%;margin-top:-30px;}
                .tax_table{width: 90%;margin-right:400px;margin-top:40px;}
                .details_table .class_row .class_row_td { font-size: 14px;  }
                .details_table .class_row .class_row_td.left{ width:70%; line-height:20px;  text-align: left;  font-size:14px; }
                .details_table .class_row .class_row_td.left_corner{ margin-left:70%; width:10%; line-height:20px;margin-top:-30px; text-align: right;}
                .details_table .class_row .class_row_td.right_start{ margin-left:76%; width:9%; line-height:20px; margin-top:-230px; text-align: right;}
                .details_table .class_row .class_row_td.right{line-height:20px; margin-left:90%;margin-top:-230px; text-align: right; font-size:14px; width:10%;padding-top:-10px}
                .details_table .class_row{line-height: 30px; clear:both}
                .details_table { height : 420px !important; }
                .class_row{width: 100%;clear:both; height:20px !important;}
                .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;margin-right:10%;}
                .tax_table .tax_row .tax_row_td.left{ width:70%; text-align: left; font-family:Verdana,Geneva,sans-serif; font-size:14px;float:left;clear:both}
                .tax_table .tax_row .tax_row_td.right{margin-left:75%;text-align: right; padding-right: 10px;border-top: 2px solid #000;padding-top:-10px}
                .tax_table .tax_row{line-height: 30px;}
                .company_details_class{width:100%; height:auto; }
                .account_details_main_address_div{width:200px; margin-top:-100px;  margin-left:80%; margin-left:550px;}
                .account_details_invoice_div{width:200px; }
                .company_details_div{width: 400px; margin-top: 220px; height:75px; margin-left:80%; font-family:Verdana,Geneva,sans-serif; font-size:14px;}
                .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family:Verdana,Geneva,sans-serif; font-size:14px;}
                .aib_account{ color: #ccc; font-family:Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
                .account_details_div{width: 400px; font-family:Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
                .account_details_address_div{ left:80px; width:250px; margin-top:60px; }
                .account_table .account_row .account_row_td.left{margin-left:0px;}
                .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px; margin-top:-190px;}
                .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
                .tax_details_class_maindiv{width: 100%; margin-right:100%;}
                </style>';
		$html.= $request->get('htmlcontent');
		// print_r($html);die();
		$pdf = PDF::loadHTML($html);
		$pdf->save('public/papers/Invoice of '.$inv_no.'.pdf');
		echo 'Invoice of '.$inv_no.'.pdf';
	}
	public function invoice_print_pdf(Request $request)
	{
		$html = '<style>
                  @page { margin: 0in; }
                body {
                    background-image: url(' .
                URL::to("public/assets/invoice_letterpad_1.jpg") .
                ');
                    background-position: center;
                    background-size:110%;
                      background-repeat: no-repeat;
                      font-family:Verdana,Geneva,sans-serif; 
                }
                .tax_table_div{width: 100%; margin-left:10%;margin-top:-30px;}
                .tax_table{width: 90%;margin-right:400px;margin-top:40px;}
                .details_table .class_row .class_row_td { font-size: 14px;  }
                .details_table .class_row .class_row_td.left{ width:70%; line-height:20px;  text-align: left;  font-size:14px; }
                .details_table .class_row .class_row_td.left_corner{ margin-left:70%; width:10%; line-height:20px;margin-top:-30px; text-align: right;}
                .details_table .class_row .class_row_td.right_start{ margin-left:76%; width:9%; line-height:20px; margin-top:-230px; text-align: right;}
                .details_table .class_row .class_row_td.right{line-height:20px; margin-left:90%;margin-top:-230px; text-align: right; font-size:14px; width:10%;padding-top:-10px}
                .details_table .class_row{line-height: 30px; clear:both}
                .details_table { height : 420px !important; }
                .class_row{width: 100%;clear:both; height:20px !important;}
                .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;margin-right:10%;}
                .tax_table .tax_row .tax_row_td.left{ width:70%; text-align: left; font-family:Verdana,Geneva,sans-serif; font-size:14px;float:left;clear:both}
                .tax_table .tax_row .tax_row_td.right{margin-left:75%;text-align: right; padding-right: 10px;border-top: 2px solid #000;padding-top:-10px}
                .tax_table .tax_row{line-height: 30px;}
                .company_details_class{width:100%; height:auto; }
                .account_details_main_address_div{width:200px; margin-top:-100px;  margin-left:80%; margin-left:550px;}
                .account_details_invoice_div{width:200px; }
                .company_details_div{width: 400px; margin-top: 220px; height:75px; margin-left:80%; font-family:Verdana,Geneva,sans-serif; font-size:14px;}
                .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family:Verdana,Geneva,sans-serif; font-size:14px;}
                .aib_account{ color: #ccc; font-family:Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
                .account_details_div{width: 400px; font-family:Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
                .account_details_address_div{ left:80px; width:250px; margin-top:60px; }
                .account_table .account_row .account_row_td.left{margin-left:0px;}
                .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px; margin-top:-190px;}
                .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
                .tax_details_class_maindiv{width: 100%; margin-right:100%;}
                </style>';
		$html.= $request->get('htmlcontent');
		$pdf = PDF::loadHTML($html);
		$pdf->save('public/papers/Invoice Report.pdf');
		echo 'Invoice Report.pdf';
	}
	public function insert_update_invoice_nominals(Request $request)
	{
		$inv_id = $request->get('inv_id');
		$invoice_details = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('invoice_number',$inv_id)->first();
		$check_invoice = \App\Models\Journals::where('reference',$inv_id)->first();
		if($check_invoice)
		{
			$data['journal_date'] = $invoice_details->invoice_date;
			$data['description'] = 'Sales Invoice '.$inv_id;
			$data['journal_source'] = 'SI';
			$data['dr_value'] = '0.00';
			$data['cr_value'] = number_format_invoice_without_comma($invoice_details->inv_net);
			\App\Models\Journals::where('reference',$check_invoice->reference)->where('nominal_code','001')->update($data);
			$data['dr_value'] = '0.00';
			$data['cr_value'] = number_format_invoice_without_comma($invoice_details->vat_value);
			\App\Models\Journals::where('reference',$check_invoice->reference)->where('nominal_code','845')->update($data);
			$data['dr_value'] = number_format_invoice_without_comma($invoice_details->gross);
			$data['cr_value'] = '0.00';
			\App\Models\Journals::where('reference',$check_invoice->reference)->where('nominal_code','712')->update($data);
			echo '<a href="javascript:" class="journal_id_viewer" data-element="'.$check_invoice->connecting_journal_reference.'">'.$check_invoice->connecting_journal_reference.'</a>';
		}
		else{
			$count_total_journals = \App\Models\Journals::groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$data['journal_date'] = $invoice_details->invoice_date;
			$data['description'] = 'Sales Invoice '.$inv_id;
			$data['reference'] = $inv_id;
			$data['journal_source'] = 'SI';
			$data['practice_code'] = Session::get('user_practice_code');
			$data['nominal_code'] = '001';
			$data['connecting_journal_reference'] = $next_connecting_journal;
			$data['dr_value'] = '0.00';
			$data['cr_value'] = number_format_invoice_without_comma($invoice_details->inv_net);
			\App\Models\Journals::insert($data);
			$data['nominal_code'] = '845';
			$data['connecting_journal_reference'] = $next_connecting_journal.'.01';
			$data['dr_value'] = '0.00';
			$data['cr_value'] = number_format_invoice_without_comma($invoice_details->vat_value);
			\App\Models\Journals::insert($data);
			$data['nominal_code'] = '712';
			$data['connecting_journal_reference'] = $next_connecting_journal.'.02';
			$data['dr_value'] = number_format_invoice_without_comma($invoice_details->gross);
			$data['cr_value'] = '0.00';
			\App\Models\Journals::insert($data);
			echo '<a href="javascript:" class="journal_id_viewer" data-element="'.$next_connecting_journal.'">'.$next_connecting_journal.'</a>';
		}
	}
	public function get_loaded_client_inv_year(Request $request)
	{
		$client_id = $request->get('client_id');
		$invoice_year = DB::select('SELECT *,SUBSTR(`invoice_date`, 1, 4) as `invoice_year` from `invoice_system` WHERE practice_code = "'.Session::get('user_practice_code').'" AND client_id = "'.$client_id.'" GROUP BY SUBSTR(`invoice_date`, 1, 4) ORDER BY SUBSTR(`invoice_date`, 1, 4) ASC');
		$output_year = '<option value="">Select Year</option>';
		if(($invoice_year))
		{
			foreach($invoice_year as $year)
			{
				$output_year.='<option value="'.$year->invoice_year.'">'.$year->invoice_year.'</option>';
			}
		}
		echo $output_year;
	}
	public function load_all_client_invoice(Request $request)
	{
		$client_id = $request->get('client_id');
		$user_details =\App\Models\userLogin::where('userid',1)->first();
		$opening_date = '';
		if($user_details)
		{
			if($user_details->opening_balance_date != "")
			{
				$opening_date = strtotime($user_details->opening_balance_date);
			}
		}
		if($client_id == "all")
		{
			$type = $request->get('type');
			if($type == "1")
			{
				$year = $request->get('year');
				$invoicelist = DB::table('invoice_system')->select('invoice_system.*')->where('invoice_system.practice_code',Session::get('user_practice_code'))->where('invoice_date','like',$year.'%')->get();
			}
			elseif($type == "2")
			{
				$invoicelist = \App\Models\InvoiceSystem::select('invoice_system.*')->where('invoice_system.practice_code',Session::get('user_practice_code'))->get();
			}
			elseif($type == "3")
			{
				$from = date('Y-m-d', strtotime($request->get('from')));
				$to = date('Y-m-d', strtotime($request->get('to')));
				$invoicelist = \App\Models\InvoiceSystem::select('invoice_system.*')
                                                ->where('invoice_system.practice_code', Session::get('user_practice_code'))
                                                ->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			$i = 1;
			$output = '';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$inv_date_str = strtotime($invoice->invoice_date);
					if($opening_date == "")
					{
						$tr_cls = '';
					}
					elseif($inv_date_str == $opening_date)
					{
						$tr_cls = 'include_tr';
					}
					elseif($inv_date_str > $opening_date)
					{
						$tr_cls = 'include_tr after_tr';
					}
					else{
						$tr_cls = '';
					}
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
					  if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}
					if($invoice->statement == 'No' || $invoice->statement == ''){
						$statement = 'No';
						$textcolor="color:#f00;font-weight:700";
					}else{
						$statement = 'Yes';
						$textcolor="color:#26BD67;font-weight:700";
					}
					$company = str_replace("Company Limited By Guarantee","",$company);
                    $company = str_replace("company Limited by Guarantee","",$company);
					$output.='<tr class="'.$tr_cls.'">
							<td>'.$i.'</td>
							<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
							<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
							<td align="left">'.$invoice->client_id.'</td>
							<td align="center">
								<img class="active_client_list_tm1" data-iden="'.$invoice->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
							</td>
							<td align="left">'.$company.'</td>';
							$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
                            $jids = '<a href="javascript:" class="create_journal_for_invoice common_black_button" data-element="'.$invoice->invoice_number.'">Create Journal</a>';
                            if($get_journal_ids)
                            {
                              $jids = '<a href="javascript:" class="journal_id_viewer" data-element="'.$get_journal_ids->connecting_journal_reference.'">'.$get_journal_ids->connecting_journal_reference.'</a>';
                            }
                            $output.='
							<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
							<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
							<td align="right">'.number_format_invoice($invoice->gross).'</td>
							<td align="left" style="font-weight:700;'.$textcolor.'">'.$statement.'</td>
							<td class="jids" align="left">'.$jids.'</td>
						</tr>
					';
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
		else{
			$type = $request->get('type');
			$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
			if($type == "1")
			{
				$year = $request->get('year');
				$invoicelist = DB::select('SELECT * from `invoice_system` WHERE client_id = "'.$client_id.'" AND `invoice_date` LIKE "'.$year.'%"');
			}
			elseif($type == "2")
			{
				$invoicelist = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_details->client_id)->get();
			}
			elseif($type == "3")
			{
				$from = date('Y-m-d', strtotime($request->get('from')));
				$to = date('Y-m-d', strtotime($request->get('to')));
				$invoicelist = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_details->client_id)->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
			}
			$i = 1;
			$output = '';
			if(($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice->client_id)->first();
					if($client_details)
					{
						if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}
					if($invoice->statement == "No"){
						$textcolor="color:#f00;font-weight:700";
					}
					else{
						$textcolor="color:#26BD67;font-weight:700";
					}
					$company = str_replace("Company Limited By Guarantee","",$company);
                    $company = str_replace("company Limited by Guarantee","",$company);
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left"><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
							<td align="left"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
							<td align="left">'.$invoice->client_id.'</td>
							<td align="left">'.$company.'</td>';
							$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
                            $jids = '';
                            if($get_journal_ids)
                            {
                              $jids = $get_journal_ids->connecting_journal_reference;
                            }
                            $output.='
							<td align="right">'.number_format_invoice($invoice->inv_net).'</td>
							<td align="right">'.number_format_invoice($invoice->vat_value).'</td>
							<td align="right">'.number_format_invoice($invoice->gross).'</td>
							<td align="left" style="'.$textcolor.'">'. $invoice->statement.'</td>
							<td class="jids" align="left"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
						</tr>
					';
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
	}
	public function check_financial_opening_bal_date(Request $request)
	{
		$user_details =\App\Models\userLogin::where('userid',1)->first();
		$opening_date_formatted = '';
		$opening_date = '';
		if($user_details)
		{
			$opening_date = $user_details->opening_balance_date;
			$opening_date_formatted = date('d/M/Y', strtotime($user_details->opening_balance_date));
		}
		echo json_encode(array("opening_date" => $opening_date, "date_formatted" => $opening_date));
	}
	public function load_invoice_for_nominal(Request $request){
		$page = $request->get('page');
		$offset = ($page - 1)  * 1000;
		$total_invoice_system = \App\Models\InvoiceSystem::join('cm_clients','cm_clients.client_id','=','invoice_system.client_id')->select('invoice_system.*')
                                                ->where('invoice_system.practice_code', Session::get('user_practice_code'))
                                                ->get();
		$total_page = ceil(count($total_invoice_system) / 1000);
		$total_count = count($total_invoice_system);
		$output = '';
		$invoice_system = \App\Models\InvoiceSystem::join('cm_clients','cm_clients.client_id','=','invoice_system.client_id')->select('invoice_system.*')
                                                ->where('invoice_system.practice_code', Session::get('user_practice_code'))
                                                ->orderBy('invoice_system.id','asc')->offset($offset)->limit(1000)->get();
		if(($invoice_system))
		{
			foreach ($invoice_system as $invoice) {
				$journal_net = '0.00';
				$journal_vat = '0.00';
				$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
                $jids = '';
                if($get_journal_ids)
                {
                  $jids = $get_journal_ids->connecting_journal_reference;
                }
                $diff = '';
				if($jids != ''){
					$debits = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','001')->sum('dr_value');
					$credits = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','001')->sum('cr_value');
					$journal_net = $debits - $credits;
					$debits_vat = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','845')->sum('dr_value');
					$credits_vat = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','845')->sum('cr_value');
					$journal_vat = $debits_vat - $credits_vat;
					if(number_format_invoice_without_comma($journal_net) + number_format_invoice_without_comma($invoice->inv_net) != 0){
						$diff = 'Diff Identified';
					}
					elseif(number_format_invoice_without_comma($journal_vat) + number_format_invoice_without_comma($invoice->vat_value) != 0){
						$diff = 'Diff Identified';
					}
				}
				$output.='<tr>
					<td><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
					<td>'.$invoice->invoice_number.'</td>
					<td style="text-align:right">'.number_format_invoice($invoice->inv_net).'</td>
					<td style="text-align:right">'.number_format_invoice($invoice->vat_value).'</td>
					<td style="text-align:right"><a href="javascript:" class="journal_id_viewer" data-element="'.$jids.'">'.$jids.'</a></td>
					<td style="text-align: right;">'.number_format_invoice($journal_net).'</td>
					<td style="text-align: right;">'.number_format_invoice($journal_vat).'</td>
					<td>'.$diff.'</td>
				</tr>';
			}
		}
		$data['output'] = $output;
		$data['total_page'] = $total_page;
		$data['total_count'] = $total_count;
		echo json_encode($data);
	}
	public function export_invoice_for_nominal(Request $request){
		$output = '';
		$invoice_system = \App\Models\InvoiceSystem::where('practice_code',Session::get('user_practice_code'))->orderBy('id','asc')->get();
		$columns = array('Date', 'Invoice', 'Net Amount', 'VAT Amount', 'Journal ID', 'Journal Net', 'Journal VAT', 'Diff');
		$filename = time().'_Extract Invoice to Nominal Journal Check.csv';
       	$file = fopen('public/papers/'.$filename, 'w');
	    fputcsv($file, $columns);
		if(($invoice_system))
		{
			foreach ($invoice_system as $invoice) {
				$journal_net = '0.00';
				$journal_vat = '0.00';
				$get_journal_ids = \App\Models\Journals::where('reference',$invoice->invoice_number)->first();
                $jids = '';
                if($get_journal_ids)
                {
                  $jids = $get_journal_ids->connecting_journal_reference;
                }
                $diff = '';
				if($jids != ''){
					$debits = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','001')->sum('dr_value');
					$credits = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','001')->sum('cr_value');
					$journal_net = $debits - $credits;
					$debits_vat = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','845')->sum('dr_value');
					$credits_vat = \App\Models\Journals::where('reference',$invoice->invoice_number)->where('nominal_code','845')->sum('cr_value');
					$journal_vat = $debits_vat - $credits_vat;
					if(number_format_invoice_without_comma($journal_net) + number_format_invoice_without_comma($invoice->inv_net) != 0){
						$diff = 'Diff Identified';
					}
					elseif(number_format_invoice_without_comma($journal_vat) + number_format_invoice_without_comma($invoice->vat_value) != 0){
						$diff = 'Diff Identified';
					}
				}
				$columns1 = array(date('d-M-Y', strtotime($invoice->invoice_date)), $invoice->invoice_number, number_format_invoice($invoice->inv_net), number_format_invoice($invoice->vat_value), $jids, number_format_invoice($journal_net), number_format_invoice($journal_vat), $diff);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
}
