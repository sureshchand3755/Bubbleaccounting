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
class CrmController extends Controller
{
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
    public function __construct() {
        $this->middleware("userauth");
        date_default_timezone_set("Europe/Dublin");
        require_once app_path("Http/helpers.php");
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function clientrequestsystem(Request $request)
    {
        $client = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->select(
                "client_id",
                "firstname",
                "surname",
                "company",
                "status",
                "active",
                "id"
            )
            ->orderBy("id", "asc")
            ->get();

        $get_list = \App\Models\requestClient::get();
        if (is_countable($get_list)) {
            foreach ($get_list as $list) {
                $check_purchase = \App\Models\requestPurchaseInvoice::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_purchase_attached = \App\Models\requestPurchaseAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_sales = \App\Models\requestSalesInvoice::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_sales_attached = \App\Models\requestSalesAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_bank = \App\Models\requestBankStatement::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_cheque = \App\Models\requestCheque::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_cheque_attached = \App\Models\requestChequeAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_others = \App\Models\requestOthers::where(
                    "request_id",
                    $list->request_id
                )->count();
                $countval =
                    $check_purchase +
                    $check_purchase_attached +
                    $check_sales +
                    $check_sales_attached +
                    $check_bank +
                    $check_cheque +
                    $check_cheque_attached +
                    $check_others;

                if ($countval == 0) {
                    \App\Models\requestClient::where(
                        "request_id",
                        $list->request_id
                    )->delete();
                }
            }
        }

        return view("user/crm/crm", [
            "title" => "Client Request System",
            "clientlist" => $client,
        ]);
    }

    public function clientrequestmanager(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $get_list = \App\Models\requestClient::where("client_id", $id)->get();
        if (is_countable($get_list)) {
            foreach ($get_list as $list) {
                $check_purchase = \App\Models\requestPurchaseInvoice::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_purchase_attached = \App\Models\requestPurchaseAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_sales = \App\Models\requestSalesInvoice::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_sales_attached = \App\Models\requestSalesAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_bank = \App\Models\requestBankStatement::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_cheque = \App\Models\requestCheque::where(
                    "request_id",
                    $list->request_id
                )->count();
                $check_cheque_attached = \App\Models\requestChequeAttached::where(
                    "request_id",
                    $list->request_id
                )->count();

                $check_others = \App\Models\requestOthers::where(
                    "request_id",
                    $list->request_id
                )->count();
                $countval =
                    $check_purchase +
                    $check_purchase_attached +
                    $check_sales +
                    $check_sales_attached +
                    $check_bank +
                    $check_cheque +
                    $check_cheque_attached +
                    $check_others;

                if ($countval == 0) {
                    \App\Models\requestClient::where(
                        "request_id",
                        $list->request_id
                    )->delete();
                }
            }
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $id)
            ->first();
        $crm_list = \App\Models\requestClient::where("client_id", $id)->get();

        return view("user/crm/crm_client", [
            "title" => "Client Request Manager",
            "client_details" => $client_details,
            "crmlist" => $crm_list,
            "clientiden" => $id,
        ]);
    }

    public function client_requestedit(Request $request, $id = "")
    {
        if (Session::has("file_attach_purchase")) {
            Session::forget("file_attach_purchase");
        }
        $dir = "uploads/crm_image/purchase/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }
        if (Session::has("file_attach_sales")) {
            Session::forget("file_attach_sales");
        }
        $dir = "uploads/crm_image/sales/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }
        if (Session::has("file_attach_cheque")) {
            Session::forget("file_attach_cheque");
        }
        $dir = "uploads/crm_image/cheque/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }

        $id = base64_decode($id);
        $request = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->get();
        $user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_status", 0)
            ->where("disabled", 0)
            ->orderBy("lastname", "asc")
            ->get();

        $bank_statement = \App\Models\requestBankStatement::where(
            "request_id",
            $request->request_id
        )->get();

        $other_information = \App\Models\requestOthers::where(
            "request_id",
            $request->request_id
        )->get();

        $cheque_book = \App\Models\requestCheque::where(
            "request_id",
            $request->request_id
        )->get();

        $purchase_invoice = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $request->request_id
        )->get();

        $sales_invoice = \App\Models\requestSalesInvoice::where(
            "request_id",
            $request->request_id
        )->get();

        return view("user/crm/crm_request", [
            "title" => "Make / View a Client Request",
            "request_details" => $request,
            "categorylist" => $category,
            "userlist" => $user,
            "bankstatementlist" => $bank_statement,
            "otherlist" => $other_information,
            "chequebooklist" => $cheque_book,
            "purchaseinvoicelist" => $purchase_invoice,
            "salesinvoicelist" => $sales_invoice,
        ]);
    }

    public function clientrequestmodal(Request $request)
    {
        $id = base64_decode($request->get("id"));
        $clientid = $request->get("clientid");
        $requestid = $request->get("requestid");

        $bank_details = \App\Models\AmlBank::where("client_id", $clientid)->get();

        $output_bank = "";
        if (is_countable($bank_details)) {
            foreach ($bank_details as $bank) {
                $output_bank .=
                    '<option value="' .
                    $bank->id .
                    '">' .
                    $bank->bank_name .
                    " &#8212; " .
                    $bank->account_number .
                    " (" .
                    $bank->account_name .
                    ")</option>";
            }
        }

        if ($id == 1) {
            $modal =
                '
			<form action="' .
                URL::to("user/request_purchase_invoice_add") .
                '" method="post" id="purchase_invoice_form">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">PURCHASE INVOICE REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Purchase Invoice: </label>
	          			<textarea class="form-control request_textarea req_purchase" placeholder="Enter Specific Purchase Invoice" name="specific_invoice"></textarea>
	          		</div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - <strong>Attached List of Purchase Invoices: </strong></div>
		            <div id="add_attachments_purchase_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-purchase" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_purchase" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="' .
                $requestid .
                '" name="requestid" />
            <input type="submit" class="common_black_button client_request_button client_purchase_button"  value="Add to Request">
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        } elseif ($id == 2) {
            $modal =
                '
			<form action="' .
                URL::to("user/request_add_sales") .
                '" method="post" id="sales_invoice_form">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">SALES INVOICE REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Sales Invoice:</label>
	          			<textarea class="form-control request_textarea req_sales" name="specific_invoice" placeholder="Enter Specific Sales Invoice"></textarea>
	          		</div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - <strong>Attached List of Sales Invoices: </strong></div>
	          		<div id="add_attachments_sales_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-sales" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_sales" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Sales Invoices to Specific Customer:</label>
	          			<textarea class="form-control request_textarea req_specific_sales" name="sales_invoices" placeholder=" - Sales Invoices to Specific Customer"></textarea>
	          		</div>
	          	</div>	          	
	          	
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="' .
                $requestid .
                '" name="requestid" />
            <input type="submit" class="common_black_button client_request_button client_sales_button"  value="Add to Request">
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        } elseif ($id == 3) {
            $modal =
                '
			<form id="bank_form" action="' .
                URL::to("user/request_add_bank_statement") .
                '" method="post" id="bank_statement_form">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">BANK STATEMENT REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
	          	<div class="col-lg-4" style="font-weight: 700;margin-top: 6px;padding-left: 20px;">Bank Account:</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
		          		<select class="form-control" required="required" name="bank_id" id="statement_bank_id">
		          			<option value="">Select Bank</option>
		          			' .
                $output_bank .
                '
		          		</select>
	          		</div>         		
	          	</div>
	          	<div class="col-lg-4" style="font-weight: 700;margin-top: 6px;padding-left: 20px;">Statement Numbers:</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          		<input type="number" name="statement_number" class="form-control statement_number" placeholder="Enter Statement Numbers" />
	          		</div>
	          			
	          	</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          			<label>From Date:</label>
	          			<label class="input-group datepicker-only-init">
	          				<input type="text" class="form-control from_date" placeholder="Select From Date" name="from_date" style="font-weight: 500;"/>
	          				<span class="input-group-addon">
		                        <i class="glyphicon glyphicon-calendar"></i>
		                    </span>
		                </label>
	          		</div>
	          	</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          			<label>To Date:</label>
	          			<label class="input-group datepicker-only-init">
	          				<input type="text" class="form-control to_date" placeholder="Select To Date" name="to_date" style="font-weight: 500;" autocomplete="off"/>
	          				<span class="input-group-addon">
		                        <i class="glyphicon glyphicon-calendar"></i>
		                    </span>
		                </label>
	          		</div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="' .
                $requestid .
                '" name="requestid" />
            <input type="submit" class="common_black_button client_request_button bank_statements_button"  value="Add to Request">
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        } elseif ($id == 4) {
            $modal =
                '
			<form action="' .
                URL::to("user/request_add_cheque") .
                '" method="post" id="add_cheque_form">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">CHEQUE BOOK REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
	          	<div class="col-lg-3" style="font-weight: 700;margin-top: 6px;padding-left: 20px;">Bank Account:</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
		          		<select class="form-control" required="required" name="bank_id" id="cheque_bank_id">
		          			<option value="">Select Bank</option>
		          			' .
                $output_bank .
                '
		          		</select>
	          		</div>         		
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - <strong>Attach List of Cheques: </strong></div>
	          		<div id="add_attachments_cheque_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-cheque" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_cheque" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>	          	
	          	<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Cheque Numbers: </label>
	          			<textarea class="form-control request_textarea req_cheque" placeholder="Enter Specific Cheque Numbers" name="specific_number"></textarea>
	          		</div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="' .
                $requestid .
                '" name="requestid" />
            <input type="submit" class="common_black_button client_request_button client_cheque_button"  value="Add to Request">
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        } elseif ($id == 5) {
            $modal =
                '
			<form action="' .
                URL::to("user/request_add_others") .
                '" method="post" id="add_others_form">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Additional/Other Information</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">	          			
	          			<textarea class="form-control" id="editor_2"  name="content"></textarea>
	          		</div>
	          	</div>	          		          	
	          	
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="' .
                $requestid .
                '" name="requestid" />
            <input type="submit" class="common_black_button client_request_button client_others_button"  value="Add to Request">
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        } else {
            $modal = '
			<form>
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">SORRY</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<label>No Functionality built yet for this Request Item.</label>
	          	</div>	          	
          	</div>
        </div>
        <div class="modal-footer">            
            
        </div>
        </form>
        ';
            echo json_encode(["modal" => $modal]);
        }
    }
    public function requestaddbankstatement(Request $request)
    {
        $statement_number = $request->get("statement_number");
        $from_date = $request->get("from_date");

        if ($statement_number == "") {
            $data["request_id"] = $request->get("requestid");
            $data["bank_id"] = $request->get("bank_id");
            $data["from_date"] = date(
                "Y-m-d",
                strtotime($request->get("from_date"))
            );
            $data["to_date"] = date("Y-m-d", strtotime($request->get("to_date")));

            \App\Models\requestBankStatement::insert($data);
            return redirect::back()->with(
                "message",
                "Bank Statements was add successfully"
            );
        } elseif ($from_date == "") {
            $data["request_id"] = $request->get("requestid");
            $data["bank_id"] = $request->get("bank_id");
            $data["statment_number"] = $request->get("statement_number");

            \App\Models\requestBankStatement::insert($data);
            return redirect::back()->with(
                "message",
                "Bank Statements was add successfully"
            );
        } else {
            $data["request_id"] = $request->get("requestid");
            $data["bank_id"] = $request->get("bank_id");
            $data["from_date"] = date(
                "Y-m-d",
                strtotime($request->get("from_date"))
            );
            $data["to_date"] = date("Y-m-d", strtotime($request->get("to_date")));
            $data["statment_number"] = $request->get("statement_number");

            \App\Models\requestBankStatement::insert($data);
            return redirect::back()->with(
                "message",
                "Bank Statements was add successfully"
            );
        }
    }
    public function requestdeletestatement(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestBankStatement::where("statement_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Bank Statements was delete successfully"
        );
    }

    public function requestaddothers(Request $request)
    {
        $data["request_id"] = $request->get("requestid");
        $data["other_content"] = $request->get("content");

        \App\Models\requestOthers::insert($data);
        return redirect::back()->with(
            "message",
            "Other Information was add successfully"
        );
    }

    public function requestdeleteother(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestOthers::where("other_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Other Information was delete successfully"
        );
    }
    public function requestaddcheque(Request $request)
    {
        $data["request_id"] = $request->get("requestid");
        $data["bank_id"] = $request->get("bank_id");
        $data["specific_number"] = $request->get("specific_number");
        $id = \App\Models\requestCheque::insertDetails($data);
        if (Session::has("file_attach_cheque")) {
            $files = Session::get("file_attach_cheque");

            $upload_dir = "uploads/crm_image/cheque";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir . "/" . base64_encode($id);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }

            $dir = "uploads/crm_image/cheque/temp"; //"path/to/targetFiles";
            $dirNew = $upload_dir; //path/to/destination/files
            // Open a known directory, and proceed to read its contents
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file == ".") {
                            continue;
                        }
                        if ($file == "..") {
                            continue;
                        }
                        rename($dir . "/" . $file, $dirNew . "/" . $file);
                    }
                    closedir($dh);
                }
            }

            foreach ($files as $file) {
                //rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
                $dataval["request_id"] = $request->get("requestid");
                $dataval["cheque_id"] = $id;
                $dataval["url"] = $upload_dir;
                $dataval["attachment"] = $file["attachment"];
                \App\Models\requestChequeAttached::insert($dataval);
            }
        }
        return redirect::back()->with(
            "message",
            "Cheque Books was add successfully"
        );
    }
    public function requestdeletecheque(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestCheque::where("cheque_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Cheque Books was delete successfully"
        );
    }
    public function requestbankreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestBankStatement::where("statement_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Bank Statements was received successfully"
        );
    }
    public function requestchequereceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestCheque::where("cheque_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Cheque Books was received successfully"
        );
    }
    public function requestchequenotreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestCheque::where("cheque_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Cheque Books was not received successfully"
        );
    }
    public function requestotherreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestOthers::where("other_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Other Information was received successfully"
        );
    }
    public function requestothernotreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestOthers::where("other_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Other Information was not received successfully"
        );
    }
    public function requestpurchaseinvoiceadd(Request $request)
    {
        $data["request_id"] = $request->get("requestid");
        $data["specific_invoice"] = $request->get("specific_invoice");

        $id = \App\Models\requestPurchaseInvoice::insertDetails($data);

        if (Session::has("file_attach_purchase")) {
            $files = Session::get("file_attach_purchase");

            $upload_dir = "uploads/crm_image/purchase";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir . "/" . base64_encode($id);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }

            $dir = "uploads/crm_image/purchase/temp"; //"path/to/targetFiles";
            $dirNew = $upload_dir; //path/to/destination/files
            // Open a known directory, and proceed to read its contents
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file == ".") {
                            continue;
                        }
                        if ($file == "..") {
                            continue;
                        }
                        rename($dir . "/" . $file, $dirNew . "/" . $file);
                    }
                    closedir($dh);
                }
            }

            foreach ($files as $file) {
                //rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
                $dataval["request_id"] = $request->get("requestid");
                $dataval["purchase_id"] = $id;
                $dataval["url"] = $upload_dir;
                $dataval["attachment"] = $file["attachment"];
                \App\Models\requestPurchaseAttached::insert($dataval);
            }
        }
        return redirect::back()->with(
            "message",
            "Purchase Invoice was added successfully"
        );
    }

    public function requestpurchasereceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestPurchaseInvoice::where("invoice_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Purchase Invoice was received successfully"
        );
    }
    public function requestpurchasenotreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestPurchaseInvoice::where("invoice_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Purchase Invoice was not received successfully"
        );
    }
    public function requestdeletepurchase(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestPurchaseInvoice::where("invoice_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Purchase Invoice was delete successfully"
        );
    }

    public function requestdeletepurchaseattach(Request $request, $id = "")
    {
        $id = base64_decode($id);

        \App\Models\requestPurchaseAttached::where("attached_id", $id)->delete();
        return redirect::back()->with(
            "message",
            "Purchase Invoice attachement was delete successfully"
        );
    }

    public function requestdeletechequeattach(Request $request, $id = "")
    {
        $id = base64_decode($id);

        \App\Models\requestChequeAttached::where("attached_id", $id)->delete();
        return redirect::back()->with(
            "message",
            "Cheque attachement was delete successfully"
        );
    }
    public function requestchequereceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestChequeAttached::where("attached_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Cheque attachement was received successfully"
        );
    }
    public function requestchequenotreceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestChequeAttached::where("attached_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Cheque attachement was not received successfully"
        );
    }

    public function requestsalesreceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestSalesAttached::where("attached_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Sales Invoice attachement was received successfully"
        );
    }
    public function requestsalesnotreceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestSalesAttached::where("attached_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Sales Invoice attachement was not received successfully"
        );
    }

    public function requestpurchasereceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestPurchaseAttached::where("attached_id", $id)->update(
            $data
        );
        return redirect::back()->with(
            "message",
            "Purchase Invoice attachement was received successfully"
        );
    }
    public function requestpurchasenotreceivedattach(Request $request, $id = "")
    {
        $id = base64_decode($id);

        $data["status"] = 0;
        \App\Models\requestPurchaseAttached::where("attached_id", $id)->update(
            $data
        );
        return redirect::back()->with(
            "message",
            "Purchase Invoice attachement was not received successfully"
        );
    }

    public function requestaddsales(Request $request)
    {
        $data["request_id"] = $request->get("requestid");
        $data["specific_invoice"] = $request->get("specific_invoice");
        $id = \App\Models\requestSalesInvoice::insertDetails($data);
        if (Session::has("file_attach_sales")) {
            $files = Session::get("file_attach_sales");

            $upload_dir = "uploads/crm_image/sales";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir . "/" . base64_encode($id);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }

            $dir = "uploads/crm_image/sales/temp"; //"path/to/targetFiles";
            $dirNew = $upload_dir; //path/to/destination/files
            // Open a known directory, and proceed to read its contents
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file == ".") {
                            continue;
                        }
                        if ($file == "..") {
                            continue;
                        }
                        rename($dir . "/" . $file, $dirNew . "/" . $file);
                    }
                    closedir($dh);
                }
            }

            foreach ($files as $file) {
                //rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
                $dataval["request_id"] = $request->get("requestid");
                $dataval["sales_id"] = $id;
                $dataval["url"] = $upload_dir;
                $dataval["attachment"] = $file["attachment"];
                \App\Models\requestSalesAttached::insert($dataval);
            }
        }
        $dataval1["request_id"] = $request->get("requestid");
        $dataval1["sales_invoices"] = $request->get("sales_invoices");
        \App\Models\requestSalesInvoice::insert($dataval1);
        return redirect::back()->with(
            "message",
            "Sales Invoice was add successfully"
        );
    }

    public function requestsalesreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestSalesInvoice::where("invoice_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Sales Invoice was received successfully"
        );
    }
    public function requestsalesnotreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestSalesInvoice::where("invoice_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Sales Invoice was not received successfully"
        );
    }

    public function requestbankstatement(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 1;
        \App\Models\requestBankStatement::where("statement_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Bank Statements attachement was received successfully"
        );
    }

    public function requestbankstatementnotreceived(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["status"] = 0;
        \App\Models\requestBankStatement::where("statement_id", $id)->update($data);
        return redirect::back()->with(
            "message",
            "Bank Statements attachement was not received successfully"
        );
    }

    public function requestdeletesales(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestSalesInvoice::where("invoice_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Sales Invoice was delete successfully"
        );
    }

    public function requestdeletesalesattach(Request $request, $id = "")
    {
        $id = base64_decode($id);

        \App\Models\requestSalesAttached::where("attached_id", $id)->delete();
        return redirect::back()->with(
            "message",
            "Sales Invoice attachement was delete successfully"
        );
    }

    public function clientrequestyearcategoryuser(Request $request)
    {
        $requestid = $request->get("requestid");
        $type = $request->get("type");

        if ($type == 1) {
            $data["year"] = $request->get("value");
            \App\Models\requestClient::where("request_id", $requestid)->update(
                $data
            );

            $request = \App\Models\requestClient::where("request_id", $requestid)->first();
            $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
                "category_id",
                $request->category_id
            )->first();
            if ($request->category_id != "") {
                $category = $category->category_name;
            } else {
                $category = "";
            }
            $client_details = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $request->client_id)
                ->first();

            $result =
                "Information Request: " .
                $request->year .
                " " .
                $category .
                " (" .
                $client_details->company .
                ")";
            echo json_encode(["content" => $result]);
        } elseif ($type == 2) {
            $data["category_id"] = $request->get("value");
            \App\Models\requestClient::where("request_id", $requestid)->update(
                $data
            );

            $request = \App\Models\requestClient::where("request_id", $requestid)->first();
            $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
                "category_id",
                $request->category_id
            )->first();

            if ($request->category_id != "") {
                $category = $category->category_name;
                $request_item = \App\Models\requestSubCategory::where(
                    "category_id",
                    $request->category_id
                )->get();

                $outputitem = "";
                if (is_countable($request_item)) {
                    foreach ($request_item as $item) {
                        $outputitem .=
                            '<a href="javascript:" style="font-weight:normal;" class="item_class" data-element="' .
                            base64_encode($item->sub_category_id) .
                            '">' .
                            $item->sub_category_name .
                            "</a><br/>";
                    }
                } else {
                    $outputitem = "Item not found";
                }
            } else {
                $category = "";
                $outputitem = "Item not found";
            }

            $client_details = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $request->client_id)
                ->first();

            $result =
                "Information Request: " .
                $request->year .
                " " .
                $category .
                " (" .
                $client_details->company .
                ")";

            echo json_encode([
                "content" => $result,
                "outputitem" => $outputitem,
            ]);
        } elseif ($type == 3) {
            $data["request_from"] = $request->get("value");
            \App\Models\requestClient::where("request_id", $requestid)->update(
                $data
            );
        }
    }

    public function requestreceivedall(Request $request, $id = "")
    {
        $id = base64_decode($id);

        $data["status"] = 1;
        \App\Models\requestClient::where("request_id", $id)->update($data);
        \App\Models\requestPurchaseInvoice::where("request_id", $id)->update($data);
        \App\Models\requestSalesInvoice::where("request_id", $id)->update($data);
        \App\Models\requestBankStatement::where("request_id", $id)->update($data);
        \App\Models\requestCheque::where("request_id", $id)->update($data);
        \App\Models\requestOthers::where("request_id", $id)->update($data);
        \App\Models\requestChequeAttached::where("request_id", $id)->update($data);
        \App\Models\requestPurchaseAttached::where("request_id", $id)->update($data);
        \App\Models\requestSalesAttached::where("request_id", $id)->update($data);

        return redirect::back()->with(
            "message",
            "All files was received successfully"
        );
    }

    public function requestnewadd(Request $request, $id = "")
    {
        $id = base64_decode($id);
        $data["client_id"] = $id;
        $data["request_date"] = date("Y-m-d");
        $data["year"] = date("Y");
        $request = \App\Models\requestClient::insertDetails($data);

        return redirect("user/client_request_edit/" . base64_encode($request));
    }

    public function requestdelete(Request $request, $id = "")
    {
        $id = base64_decode($id);
        \App\Models\requestClient::where("request_id", $id)->delete();

        \App\Models\requestPurchaseInvoice::where("request_id", $id)->delete();
        \App\Models\requestSalesInvoice::where("request_id", $id)->delete();
        \App\Models\requestBankStatement::where("request_id", $id)->delete();
        \App\Models\requestCheque::where("request_id", $id)->delete();
        \App\Models\requestOthers::where("request_id", $id)->delete();
        \App\Models\requestChequeAttached::where("request_id", $id)->delete();
        \App\Models\requestPurchaseAttached::where("request_id", $id)->delete();
        \App\Models\requestSalesAttached::where("request_id", $id)->delete();

        return redirect::back()->with(
            "message",
            "Request was delete successfully"
        );
    }

    public function client_requestview(Request $request)
    {
        $id = $request->get("requestid");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%"><a href="' .
                            URL::to("/") .
                            "/" .
                            $purchase_attach->url .
                            "/" .
                            $purchase_attach->attachment .
                            '" download>' .
                            $purchase_attach->attachment .
                            '</a></td>
		        </tr>';
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%"><a href="' .
                            URL::to("/") .
                            "/" .
                            $sales_attach->url .
                            "/" .
                            $sales_attach->attachment .
                            '" download>' .
                            $sales_attach->attachment .
                            '</a></td>
		        </tr>';
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%">Bank Statements</td>
	          <td style="width:30%">Statements for:</td>
	          <td style="width:50%">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();

                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";
                if ($bank_details) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%"><a href="' .
                            URL::to("/") .
                            "/" .
                            $cheque_attach->url .
                            "/" .
                            $cheque_attach->attachment .
                            '" download>' .
                            $cheque_attach->attachment .
                            '</a></td>
	            </tr>
	            ';
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if ($bank_details) {
                        $bank_name =
                            $bank_details->bank_name .
                            " " .
                            $bank_details->account_number .
                            " (" .
                            $bank_details->account_name .
                            ")";
                    } else {
                        $bank_name = "";
                    }
                    $output_cheque .=
                        '
	          <tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Specific Cheques</td>
	            <td style="width:50%">' .
                        $bank_name .
                        " Specific Cheque Numbers: " .
                        $cheque->specific_number .
                        '</td>
	          </tr>';
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%">Other Information</td>
	          <td style="width:30%"></td>
	          <td style="width:50%">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output_information =
            '
		<tr>
			<td>Year:</td>
			<td>' .
            $request_details->year .
            '</td>
		</tr>
		<tr>
			<td>Category:</td>
			<td>' .
            $category->category_name .
            '</td>
		</tr>
		<tr>
			<td>User:</td>
			<td>' .
            $employee->lastname .
            " " .
            $employee->firstname .
            '</td>
		</tr>
		<tr>
			<td>Subject:</td>
			<td>Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</td>
		</tr>
		';

        $output =
            '
		<div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <input type="button" name="download_view_pdf" id="download_view_pdf" class="common_black_button" value="Download as PDF" style="float:right;margin-right:0px; margin-top:10px">
            <h4 class="modal-title">View Request</h4>           
            
        </div>
        <div class="modal-body">
        	<table class="table" style="width:500px">
        		' .
            $output_information .
            '
        	</table>
        	<table class="table">
        		<thead>
        			<tr>
        				<th colspan="3">ITEMS ON THIS REQUEST</th>
        			</tr>
        			' .
            $output_purchase_invoice .
            '
        			' .
            $output_sales_invoice .
            '
        			' .
            $output_statement .
            '
        			' .
            $output_cheque .
            '
        			' .
            $output_other .
            '
        		</thead>
        		<tbody>
        		</tbody>
        	</table>
        </div>
        <div class="modal-footer">
        	            
        </div>
		';

        echo json_encode(["content" => $output]);
    }
    public function download_request_view(Request $request)
    {
        $id = $request->get("requestid");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Sales Invoice</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices to Specific Customer</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Bank Statements</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Statements for:</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();

                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";
                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if (is_countable($bank_details)) {
                        $bank_name =
                            $bank_details->bank_name .
                            " " .
                            $bank_details->account_number .
                            " (" .
                            $bank_details->account_name .
                            ")";
                    } else {
                        $bank_name = "";
                    }
                    $output_cheque .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Cheques</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $bank_name .
                        " Specific Cheque Numbers: " .
                        $cheque->specific_number .
                        '</td>
	          </tr>';
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Other Information</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px"></td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output_information =
            '
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Year</td>
			<td style="height:40px">' .
            $request_details->year .
            '</td>
		</tr>
		<tr>
			<td style="height:40px;font-weight:700">Category</td>
			<td style="height:40px">' .
            $category->category_name .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Employee</td>
			<td style="height:40px">' .
            $employee->lastname .
            " " .
            $employee->firstname .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Subject:</td>
			<td style="height:40px">Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</td>
		</tr>
		';

        $output =
            '<style>
		.table_style {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		.table_style_no_border {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		</style>
    	<table class="table_style_no_border">
    		' .
            $output_information .
            '
    	</table>
    	<table class="table_style">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:45px;font-weight:700">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>';

        $pdf = PDF::loadHTML($output);
        $pdf->save(
            "public/papers/Information Request- " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ").pdf"
        );
        echo "Information Request- " .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ").pdf";
    }
    public function crm_upload_images_purchase(Request $request)
    {
        $upload_dir = "uploads/crm_image";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/purchase";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/temp";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        if (!empty($_FILES)) {
            $tmpFile = $_FILES["file"]["tmp_name"];
            $fname = str_replace("#", "", $_FILES["file"]["name"]);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);

            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);

            $filename = $upload_dir . "/" . $fname;

            $arrayval = ["attachment" => $fname, "url" => $upload_dir];

            if (Session::has("file_attach_purchase")) {
                $getsession = Session::get("file_attach_purchase");
            } else {
                $getsession = [];
            }

            array_push($getsession, $arrayval);

            $sessn = ["file_attach_purchase" => $getsession];
            Session::put($sessn);

            move_uploaded_file($tmpFile, $filename);

            echo json_encode([
                "id" => 0,
                "filename" => $fname,
                "file_id" => 0,
                "count" => ($getsession),
            ]);
        }
    }
    public function crm_upload_images_sales(Request $request)
    {
        $upload_dir = "uploads/crm_image";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/sales";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/temp";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        if (!empty($_FILES)) {
            $tmpFile = $_FILES["file"]["tmp_name"];
            $fname = str_replace("#", "", $_FILES["file"]["name"]);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);

            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);

            $filename = $upload_dir . "/" . $fname;

            $arrayval = ["attachment" => $fname, "url" => $upload_dir];

            if (Session::has("file_attach_sales")) {
                $getsession = Session::get("file_attach_sales");
            } else {
                $getsession = [];
            }

            array_push($getsession, $arrayval);

            $sessn = ["file_attach_sales" => $getsession];
            Session::put($sessn);

            move_uploaded_file($tmpFile, $filename);

            echo json_encode([
                "id" => 0,
                "filename" => $fname,
                "file_id" => 0,
                "count" => ($getsession),
            ]);
        }
    }
    public function crm_upload_images_cheque(Request $request)
    {
        $upload_dir = "uploads/crm_image";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/cheque";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/temp";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        if (!empty($_FILES)) {
            $tmpFile = $_FILES["file"]["tmp_name"];
            $fname = str_replace("#", "", $_FILES["file"]["name"]);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);

            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);

            $filename = $upload_dir . "/" . $fname;

            $arrayval = ["attachment" => $fname, "url" => $upload_dir];

            if (Session::has("file_attach_cheque")) {
                $getsession = Session::get("file_attach_cheque");
            } else {
                $getsession = [];
            }

            array_push($getsession, $arrayval);

            $sessn = ["file_attach_cheque" => $getsession];
            Session::put($sessn);

            move_uploaded_file($tmpFile, $filename);

            echo json_encode([
                "id" => 0,
                "filename" => $fname,
                "file_id" => 0,
                "count" => ($getsession),
            ]);
        }
    }
    public function clear_session_attachments_purchase(Request $request)
    {
        if (Session::has("file_attach_purchase")) {
            Session::forget("file_attach_purchase");
        }
        $dir = "uploads/crm_image/purchase/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }
    }
    public function clear_session_attachments_sales(Request $request)
    {
        if (Session::has("file_attach_sales")) {
            Session::forget("file_attach_sales");
        }
        $dir = "uploads/crm_image/sales/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }
    }
    public function clear_session_attachments_cheque(Request $request)
    {
        if (Session::has("file_attach_cheque")) {
            Session::forget("file_attach_cheque");
        }
        $dir = "uploads/crm_image/cheque/temp"; //"path/to/targetFiles";

        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == ".") {
                        continue;
                    }
                    if ($file == "..") {
                        continue;
                    }
                    unlink($dir . "/" . $file);
                }
                closedir($dh);
            }
        }
    }
    public function send_request_for_approval_edit(Request $request)
    {
        $id = $request->get("requestid");
        $to_user = $request->get("to_user");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();
        if ($to_user != "") {
            $to_employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $to_user)->first();
            $to_user_name =
                $to_employee->lastname . " " . $to_employee->firstname;
        } else {
            $to_user_name = "";
        }

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $attachments = '<p class="attach_p_main">Attachments: </p>';
        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="purchase_attachments[]" value="' .
                            $purchase_attach->url .
                            "/" .
                            $purchase_attach->attachment .
                            "||" .
                            $purchase_attach->attachment .
                            '" class="attach_p" checked><label></label>'.$purchase_attach->attachment;
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';

                        $attachments .=
                            '<input type="checkbox" name="sales_attachments[]" value="' .
                            $sales_attach->url .
                            "/" .
                            $sales_attach->attachment .
                            "||" .
                            $sales_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px"></label>'.$sales_attach->attachment;
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%">Bank Statements</td>
	          <td style="width:30%">Statements for:</td>
	          <td style="width:50%">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();
                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";
                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                        $attachments .=
                            '<input type="checkbox" name="cheque_attachments[]" value="' .
                            $cheque_attach->url .
                            "/" .
                            $cheque_attach->attachment .
                            "||" .
                            $cheque_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px"></label>'.$cheque_attach->attachment;
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if ($cheque->specific_number != "") {
                        if (is_countable($bank_details)) {
                            $bank_name =
                                $bank_details->bank_name .
                                " " .
                                $bank_details->account_number .
                                " (" .
                                $bank_details->account_name .
                                ")";
                        } else {
                            $bank_name = "";
                        }
                        $output_cheque .=
                            '
		          <tr>
		            <td style="width:20%">Cheque Books</td>
		            <td style="width:30%">Specific Cheques</td>
		            <td style="width:50%">' .
                            $bank_name .
                            " Specific Cheque Numbers: " .
                            $cheque->specific_number .
                            '</td>
		          </tr>';
                    }
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%">Other Information</td>
	          <td style="width:30%"></td>
	          <td style="width:50%">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();
        $output =
            '
		<p>Hi ' .
            $to_user_name .
            ', </p>
		<p>FOR APPROVAL</p>
		<p>We have complied the following list or items / information related to <b>Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</b> that we require from you.  Please can you get for us:</p>
		<p><b>Subject:</b> Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</p>
		<table class="table" align="center" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left; height:35px">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>

    	<p>' .
            $category->signature .
            "</p>";

        echo json_encode([
            "subject" =>
                "Information Request: " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ")",
            "content" => $output,
            "user_id" => $employee->user_id,
            "attachments" => $attachments,
        ]);
    }
    public function send_request_to_client_edit(Request $request)
    {
        $id = $request->get("requestid");

        $to_user = $request->get("to_user");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();
        if ($to_user != "") {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $to_user)
                ->first();
            $to_user_name = $to_employee->firstname;
        } else {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $request_details->client_id)
                ->first();
            $to_user_name = $to_employee->firstname;
        }

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $attachments = '<p class="attach_p_main">Attachments: </p>';
        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="purchase_attachments[]" value="' .
                            $purchase_attach->url .
                            "/" .
                            $purchase_attach->attachment .
                            "||" .
                            $purchase_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px"></label>'.$purchase_attach->attachment;
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="sales_attachments[]" value="' .
                            $sales_attach->url .
                            "/" .
                            $sales_attach->attachment .
                            "||" .
                            $sales_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$sales_attach->attachment;
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%">Bank Statements</td>
	          <td style="width:30%">Statements for:</td>
	          <td style="width:50%">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();
                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";

                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                        $attachments .=
                            '<input type="checkbox" name="cheque_attachments[]" value="' .
                            $cheque_attach->url .
                            "/" .
                            $cheque_attach->attachment .
                            "||" .
                            $cheque_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$cheque_attach->attachment;
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if ($cheque->specific_number != "") {
                        if (is_countable($bank_details)) {
                            $bank_name =
                                $bank_details->bank_name .
                                " " .
                                $bank_details->account_number .
                                " (" .
                                $bank_details->account_name .
                                ")";
                        } else {
                            $bank_name = "";
                        }
                        $output_cheque .=
                            '
		          <tr>
		            <td style="width:20%">Cheque Books</td>
		            <td style="width:30%">Specific Cheques</td>
		            <td style="width:50%">' .
                            $bank_name .
                            " Specific Cheque Numbers: " .
                            $cheque->specific_number .
                            '</td>
		          </tr>';
                    }
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%">Other Information</td>
	          <td style="width:30%"></td>
	          <td style="width:50%">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }

        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output =
            '
		<p>Hi ' .
            $to_user_name .
            ', </p>
		<p>We have complied the following list or items / information related to <b>Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</b> that we require from you.  Please can you get for us:</p>
		<p><b>Subject:</b> Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</p>
		<table style="border:0px solid;border-collapse:collapse">
    			<tr>
    				<th style="text-align:left;height:35px">ITEMS ON THIS REQUEST</th>
    				<th></th>
    				<th></th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    	</table>
    	<p>' .
            $category->signature .
            "</p>";

        $pdf = PDF::loadHTML($output);
        $pdf->stream(
            "public/papers/Information Request- " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ").pdf"
        );
        $pdf_attachment =
            "Information Request- " .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ").pdf";

        $attachments .=
            '<img src="' .
            URL::to("public/assets/images/pdf.png") .
            '" style="width:30px;float:left;margin-left: 7px;"><input type="checkbox" name="pdf_attachments" value="public/papers/' .
            $pdf_attachment .
            "||" .
            $pdf_attachment .
            '" class="attach_p" checked style="display:none"><label style="margin-left:5px;margin-top:2px">&nbsp;</label>'.$pdf_attachment;

        echo json_encode([
            "subject" =>
                "Information Request: " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ")",
            "content" => $output,
            "user_id" => $employee->user_id,
            "client_id" => $client_details->client_id,
            "client_name" => $client_details->company,
            "attachments" => $attachments,
        ]);
    }
    public function send_request_to_client_edit_none_received(Request $request)
    {
        $id = $request->get("requestid");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Sales Invoice</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices to Specific Customer</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Bank Statements</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Statements for:</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();

                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";
                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if (is_countable($bank_details)) {
                        $bank_name =
                            $bank_details->bank_name .
                            " " .
                            $bank_details->account_number .
                            " (" .
                            $bank_details->account_name .
                            ")";
                    } else {
                        $bank_name = "";
                    }
                    $output_cheque .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Cheques</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $bank_name .
                        " Specific Cheque Numbers: " .
                        $cheque->specific_number .
                        '</td>
	          </tr>';
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Other Information</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px"></td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output_information =
            '
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Year</td>
			<td style="height:40px">' .
            $request_details->year .
            '</td>
		</tr>
		<tr>
			<td style="height:40px;font-weight:700">Category</td>
			<td style="height:40px">' .
            $category->category_name .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Employee</td>
			<td style="height:40px">' .
            $employee->lastname .
            " " .
            $employee->firstname .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Subject:</td>
			<td style="height:40px">Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</td>
		</tr>
		';

        $output =
            '<style>
		.table_style {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		.table_style_no_border {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		</style>
    	<table class="table_style_no_border">
    		' .
            $output_information .
            '
    	</table>
    	<table class="table_style">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:45px;font-weight:700">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>';

        $pdf = PDF::loadHTML($output);
        $pdf->save(
            "public/papers/Information Request- " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ").pdf"
        );
        $pdf_attachment =
            "Information Request- " .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ").pdf";

        $to_user = $request->get("to_user");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();
        if ($to_user != "") {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $to_user)
                ->first();
            $to_user_name = $to_employee->firstname;
        } else {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $request_details->client_id)
                ->first();
            $to_user_name = $to_employee->firstname;
        }
        $last_sent = \App\Models\requestClientEmailSent::where("request_id", $id)
            ->orderBy("id", "DESC")
            ->first();
        if (is_countable($last_sent)) {
            $last_email_sent = date(
                "d-M-Y H:i",
                strtotime($last_sent->email_sent)
            );
        } else {
            $last_email_sent = "";
        }
        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $attachments = '<p class="attach_p_main">Attachments: </p>';
        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="purchase_attachments[]" value="' .
                            $purchase_attach->url .
                            "/" .
                            $purchase_attach->attachment .
                            "||" .
                            $purchase_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$purchase_attach->attachment;
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="sales_attachments[]" value="' .
                            $sales_attach->url .
                            "/" .
                            $sales_attach->attachment .
                            "||" .
                            $sales_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$sales_attach->attachment;
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%">Bank Statements</td>
	          <td style="width:30%">Statements for:</td>
	          <td style="width:50%">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();
                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";

                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                        $attachments .=
                            '<input type="checkbox" name="cheque_attachments[]" value="' .
                            $cheque_attach->url .
                            "/" .
                            $cheque_attach->attachment .
                            "||" .
                            $cheque_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$cheque_attach->attachment;
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if ($cheque->specific_number != "") {
                        if (is_countable($bank_details)) {
                            $bank_name =
                                $bank_details->bank_name .
                                " " .
                                $bank_details->account_number .
                                " (" .
                                $bank_details->account_name .
                                ")";
                        } else {
                            $bank_name = "";
                        }
                        $output_cheque .=
                            '
		          <tr>
		            <td style="width:20%">Cheque Books</td>
		            <td style="width:30%">Specific Cheques</td>
		            <td style="width:50%">' .
                            $bank_name .
                            " Specific Cheque Numbers: " .
                            $cheque->specific_number .
                            '</td>
		          </tr>';
                    }
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%">Other Information</td>
	          <td style="width:30%"></td>
	          <td style="width:50%">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }

        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output =
            '
		<p>Hi ' .
            $to_user_name .
            ', </p>
		<p>On ' .
            $last_email_sent .
            ' we sent you a request for some information, we have not had reply to that email yet – here is a list of what we require form you</p>
		<p><b>Subject:</b> Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</p>
		<table class="table" align="center" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:35px">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>

    	<p>' .
            $category->signature .
            "</p>";

        $attachments .=
            '<img src="' .
            URL::to("public/assets/images/pdf.png") .
            '" style="width:30px;float:left;margin-left: 7px;"><input type="checkbox" name="pdf_attachments" value="public/papers/' .
            $pdf_attachment .
            "||" .
            $pdf_attachment .
            '" class="attach_p" checked style="display:none"><label style="margin-left:5px;margin-top:2px">&nbsp;</label>'.$pdf_attachment;

        echo json_encode([
            "subject" =>
                "Information Request: " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ")",
            "content" => $output,
            "user_id" => $employee->user_id,
            "client_id" => $client_details->client_id,
            "client_name" => $client_details->company,
            "attachments" => $attachments,
        ]);
    }
    public function send_request_to_client_some_not_edit(Request $request)
    {
        $id = $request->get("requestid");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Purchase Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Purchase Invoices:</td>
		        <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Sales Invoice</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Sales Invoices to Specific Customer</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Bank Statements</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Statements for:</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();

                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";
                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Attached List of Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if (is_countable($bank_details)) {
                        $bank_name =
                            $bank_details->bank_name .
                            " " .
                            $bank_details->account_number .
                            " (" .
                            $bank_details->account_name .
                            ")";
                    } else {
                        $bank_name = "";
                    }
                    $output_cheque .=
                        '
	          <tr>
	            <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Cheque Books</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">Specific Cheques</td>
	            <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                        $bank_name .
                        " Specific Cheque Numbers: " .
                        $cheque->specific_number .
                        '</td>
	          </tr>';
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%;border-bottom:1px solid #dfdfdf;height:35px">Other Information</td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px"></td>
	          <td style="width:40%;border-bottom:1px solid #dfdfdf;height:35px">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output_information =
            '
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Year</td>
			<td style="height:40px">' .
            $request_details->year .
            '</td>
		</tr>
		<tr>
			<td style="height:40px;font-weight:700">Category</td>
			<td style="height:40px">' .
            $category->category_name .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Employee</td>
			<td style="height:40px">' .
            $employee->lastname .
            " " .
            $employee->firstname .
            '</td>
		</tr>
		<tr style="border-bottom:1px solid #dfdfdf">
			<td style="height:40px;font-weight:700">Subject:</td>
			<td style="height:40px">Information Request: ' .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ')</td>
		</tr>
		';

        $output =
            '<style>
		.table_style {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		.table_style_no_border {
		    width: 100%;
		    border-collapse:collapse;
		    border:0px solid #c5c5c5;
		}
		</style>
    	<table class="table_style_no_border">
    		' .
            $output_information .
            '
    	</table>
    	<table class="table_style">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:45px;font-weight:700">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>';

        $pdf = PDF::loadHTML($output);
        $pdf->save(
            "public/papers/Information Request- " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ").pdf"
        );
        $pdf_attachment =
            "Information Request- " .
            $request_details->year .
            " " .
            $category->category_name .
            " (" .
            $client_details->company .
            ").pdf";

        $to_user = $request->get("to_user");
        $request_details = \App\Models\requestClient::where("request_id", $id)->first();
        $category = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where(
            "category_id",
            $request_details->category_id
        )->first();
        $employee =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
            "user_id",
            $request_details->request_from
        )->first();
        if ($to_user != "") {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $to_user)
                ->first();
            $to_user_name = $to_employee->firstname;
        } else {
            $to_employee = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $request_details->client_id)
                ->first();
            $to_user_name = $to_employee->firstname;
        }
        $last_sent = \App\Models\requestClientEmailSent::where("request_id", $id)
            ->orderBy("id", "DESC")
            ->first();
        $last_email_sent = date("d-M-Y H:i", strtotime($last_sent->email_sent));
        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where("request_id", $id)
            ->where("status", 0)
            ->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)
            ->where("status", 0)
            ->get();

        $attachments = '<p class="attach_p_main">Attachments: </p>';
        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )
                    ->where("status", 0)
                    ->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="purchase_attachments[]" value="' .
                            $purchase_attach->url .
                            "/" .
                            $purchase_attach->attachment .
                            "||" .
                            $purchase_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$purchase_attach->attachment;
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "" && $invoice->status == 0) {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )
                    ->where("status", 0)
                    ->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                        $attachments .=
                            '<input type="checkbox" name="sales_attachments[]" value="' .
                            $sales_attach->url .
                            "/" .
                            $sales_attach->attachment .
                            "||" .
                            $sales_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$sales_attach->attachment;
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "" && $invoice->status == 0) {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "" && $invoice->status == 0) {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                if ($statement->status == 0) {
                    $output_statement .=
                        '
		        <tr>
		          <td style="width:20%">Bank Statements</td>
		          <td style="width:30%">Statements for:</td>
		          <td style="width:50%">' .
                        $result_bank .
                        '</td>
		          
		        </tr>
		        ';
                }
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();
                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )
                    ->where("status", 0)
                    ->get();

                $output_cheque_attach = "";

                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                        $attachments .=
                            '<input type="checkbox" name="cheque_attachments[]" value="' .
                            $cheque_attach->url .
                            "/" .
                            $cheque_attach->attachment .
                            "||" .
                            $cheque_attach->attachment .
                            '" class="attach_p" checked><label style="margin-left:5px">&nbsp;</label>'.$cheque_attach->attachment;
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "" && $cheque->status == 0) {
                    if ($cheque->specific_number != "") {
                        if (is_countable($bank_details)) {
                            $bank_name =
                                $bank_details->bank_name .
                                " " .
                                $bank_details->account_number .
                                " (" .
                                $bank_details->account_name .
                                ")";
                        } else {
                            $bank_name = "";
                        }
                        $output_cheque .=
                            '
		          <tr>
		            <td style="width:20%">Cheque Books</td>
		            <td style="width:30%">Specific Cheques</td>
		            <td style="width:50%">' .
                            $bank_name .
                            " Specific Cheque Numbers: " .
                            $cheque->specific_number .
                            '</td>
		          </tr>';
                    }
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                if ($other->status == 0) {
                    $output_other .=
                        '
		        <tr>
		          <td style="width:20%">Other Information</td>
		          <td style="width:30%"></td>
		          <td style="width:50%">' .
                        $other->other_content .
                        '</td>
		          
		        </tr>
		        ';
                }
            }
        } else {
            $output_other = "";
        }

        $output =
            '
		<p>Hi ' .
            $to_user_name .
            ', </p>
		<p>On "' .
            $last_email_sent .
            '" we sent you a request for some information, we have not had a reply to that email yet – here is a list of what we require form you </p>

		<p>WE DID NOT GET THE FOLLOWING ITEMS</p>
		<table class="table" align="center" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:35px">ITEMS NOT RECEIVED</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>';

        $purchaseinvoicelist = \App\Models\requestPurchaseInvoice::where(
            "request_id",
            $id
        )->get();
        $salesinvoicelist = \App\Models\requestSalesInvoice::where(
            "request_id",
            $id
        )->get();
        $bankstatementlist = \App\Models\requestBankStatement::where(
            "request_id",
            $id
        )->get();
        $chequebooklist = \App\Models\requestCheque::where("request_id", $id)->get();
        $otherlist = \App\Models\requestOthers::where("request_id", $id)->get();

        $output_purchase_invoice = "";
        if (is_countable($purchaseinvoicelist)) {
            foreach ($purchaseinvoicelist as $invoice) {
                $purchase_attached_list = \App\Models\requestPurchaseAttached::where(
                    "purchase_id",
                    $invoice->invoice_id
                )->get();

                $output_purchase_attach = "";

                if (is_countable($purchase_attached_list)) {
                    foreach ($purchase_attached_list as $purchase_attach) {
                        $output_purchase_attach .=
                            '<tr>
		        <td style="width:20%">Purchase Invoices</td>
		        <td style="width:30%">Attached List of Purchase Invoices:</td>
		        <td style="width:50%">' .
                            $purchase_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_purchase_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_purchase_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Purchase Invoices</td>
	            <td style="width:30%">Specific Purchase Invoices</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>         
	          </tr>';
                }
                $output_purchase_invoice .= $output_purchase_attach;
            }
        } else {
            $output_purchase_invoice = "";
        }

        $output_sales_invoice = "";
        if (is_countable($salesinvoicelist)) {
            foreach ($salesinvoicelist as $invoice) {
                $sales_attached_list = \App\Models\requestSalesAttached::where(
                    "sales_id",
                    $invoice->invoice_id
                )->get();

                $output_sales_attach = "";

                if (is_countable($sales_attached_list)) {
                    foreach ($sales_attached_list as $sales_attach) {
                        $output_sales_attach .=
                            '<tr>
		        <td style="width:20%">Sales Invoices</td>
		        <td style="width:30%">Attached List of Sales Invoices:</td>
		        <td style="width:50%">' .
                            $sales_attach->attachment .
                            '</td>
		        </tr>';
                    }
                } else {
                    $output_sales_attach = "";
                }

                if ($invoice->specific_invoice != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Specific Sales Invoice</td>
	            <td style="width:50%">' .
                        $invoice->specific_invoice .
                        '</td>        
	          </tr>';
                }
                if ($invoice->sales_invoices != "") {
                    $output_sales_invoice .=
                        '
	          <tr>
	            <td style="width:20%">Sales Invoices</td>
	            <td style="width:30%">Sales Invoices to Specific Customer</td>
	            <td style="width:50%">' .
                        $invoice->sales_invoices .
                        '</td>         
	          </tr>';
                }
                $output_sales_invoice .= $output_sales_attach;
            }
        } else {
            $output_sales_invoice = "";
        }

        $output_statement = "";
        if (is_countable($bankstatementlist)) {
            foreach ($bankstatementlist as $statement) {
                $bank_details = \App\Models\AmlBank::where(
                    "id",
                    $statement->bank_id
                )->first();
                if (is_countable($bank_details)) {
                    $bank_name =
                        $bank_details->bank_name .
                        " " .
                        $bank_details->account_number .
                        " (" .
                        $bank_details->account_name .
                        ")";
                } else {
                    $bank_name = "";
                }

                if ($statement->statment_number == "") {
                    $result_bank =
                        $bank_name .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                } elseif ($statement->from_date == "0000-00-00") {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number;
                } else {
                    $result_bank =
                        $bank_name .
                        " Statement Numbers " .
                        $statement->statment_number .
                        " From " .
                        date("d-M-Y", strtotime($statement->from_date)) .
                        " to " .
                        date("d-M-Y", strtotime($statement->to_date));
                }

                $output_statement .=
                    '
	        <tr>
	          <td style="width:20%">Bank Statements</td>
	          <td style="width:30%">Statements for:</td>
	          <td style="width:50%">' .
                    $result_bank .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_statement = "";
        }

        $output_cheque = "";
        if (is_countable($chequebooklist)) {
            foreach ($chequebooklist as $cheque) {
                $bank_details = \App\Models\AmlBank::where("id", $cheque->bank_id)->first();
                $cheque_attached_list = \App\Models\requestChequeAttached::where(
                    "cheque_id",
                    $cheque->cheque_id
                )->get();

                $output_cheque_attach = "";

                if (is_countable($bank_details)) {
                    $bank_name_cheque = $bank_details->bank_name;
                } else {
                    $bank_name_cheque = "";
                }
                if (is_countable($cheque_attached_list)) {
                    foreach ($cheque_attached_list as $cheque_attach) {
                        $output_cheque_attach .=
                            '<tr>
	            <td style="width:20%">Cheque Books</td>
	            <td style="width:30%">Attached List of Cheque Books</td>
	            <td style="width:50%">' .
                            $cheque_attach->attachment .
                            '</td>
	            </tr>
	            ';
                    }
                } else {
                    $output_cheque_attach = "";
                }

                if ($cheque->specific_number != "") {
                    if ($cheque->specific_number != "") {
                        if (is_countable($bank_details)) {
                            $bank_name =
                                $bank_details->bank_name .
                                " " .
                                $bank_details->account_number .
                                " (" .
                                $bank_details->account_name .
                                ")";
                        } else {
                            $bank_name = "";
                        }
                        $output_cheque .=
                            '
		          <tr>
		            <td style="width:20%">Cheque Books</td>
		            <td style="width:30%">Specific Cheques</td>
		            <td style="width:50%">' .
                            $bank_name .
                            " Specific Cheque Numbers: " .
                            $cheque->specific_number .
                            '</td>
		          </tr>';
                    }
                }
                $output_cheque .= $output_cheque_attach;
            }
        } else {
            $output_cheque = "";
        }

        $output_other = "";
        if (is_countable($otherlist)) {
            foreach ($otherlist as $other) {
                $output_other .=
                    '
	        <tr>
	          <td style="width:20%">Other Information</td>
	          <td style="width:30%"></td>
	          <td style="width:50%">' .
                    $other->other_content .
                    '</td>
	          
	        </tr>
	        ';
            }
        } else {
            $output_other = "";
        }

        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $request_details->client_id)
            ->first();

        $output .=
            '<p>A FULL LIST OF WHAT WAS SENT TO YOU ON "' .
            $last_email_sent .
            '" </p>
		<table class="table" align="center" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th colspan="3" style="text-align:left;height:35px">ITEMS ON THIS REQUEST</th>
    			</tr>
    			' .
            $output_purchase_invoice .
            '
    			' .
            $output_sales_invoice .
            '
    			' .
            $output_statement .
            '
    			' .
            $output_cheque .
            '
    			' .
            $output_other .
            '
    		</thead>
    		<tbody>
    		</tbody>
    	</table>
    	<p>' .
            $category->signature .
            "</p>";

        $attachments .=
            '<img src="' .
            URL::to("public/assets/images/pdf.png") .
            '" style="width:30px;float:left;margin-left: 7px;"><input type="checkbox" name="pdf_attachments" value="public/papers/' .
            $pdf_attachment .
            "||" .
            $pdf_attachment .
            '" class="attach_p" checked style="display:none"><label style="margin-left:5px;margin-top:2px">&nbsp;</label>'.$pdf_attachment;

        echo json_encode([
            "subject" =>
                "Information Request: " .
                $request_details->year .
                " " .
                $category->category_name .
                " (" .
                $client_details->company .
                ")",
            "content" => $output,
            "user_id" => $employee->user_id,
            "client_id" => $client_details->client_id,
            "client_name" => $client_details->company,
            "attachments" => $attachments,
        ]);
    }
    public function email_to_client(Request $request)
    {
        $request_id = $request->get("request_id_email_client");
        $from_input = $request->get("from_user_to_client");
        $details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $from_input)->first();
        $from = $details->email;
        $user_name = $details->lastname . " " . $details->firstname;

        $to_user = $request->get("client_search");

        $toemails = $to_user . "," . $request->get("cc_approval_to_client");
        $sentmails = $to_user . ", " . $request->get("cc_approval_to_client");
        $subject = $request->get("subject_to_client");
        $message = $request->get("message_editor_to_client");
        $explode = explode(",", $toemails);
        $data["sentmails"] = $sentmails;

        $purchase_attachments = $request->get("purchase_attachments");
        $sales_attachments = $request->get("sales_attachments");
        $cheque_attachments = $request->get("cheque_attachments");
        $pdf_attachments = $request->get("pdf_attachments");

        if (is_countable($explode)) {
            foreach ($explode as $exp) {
                $to = trim($exp);
                $data["logo"] = getEmailLogo('crm');
                $data["message"] = $message;
                $contentmessage = view("user/email_share_paper_crm", $data);
                $email = new PHPMailer();
                $email->SetFrom($from, $user_name); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                $attach = "";
                if (is_countable($purchase_attachments)) {
                    foreach ($purchase_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path1 = $explode[0];
                        if ($attach == "") {
                            $attach = $path1;
                        } else {
                            $attach = $attach . "||" . $path1;
                        }
                        $email->AddAttachment($path1, $explode[1]);
                    }
                }

                if (is_countable($sales_attachments)) {
                    foreach ($sales_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path2 = $explode[0];
                        if ($attach == "") {
                            $attach = $path2;
                        } else {
                            $attach = $attach . "||" . $path2;
                        }
                        $email->AddAttachment($path2, $explode[1]);
                    }
                }

                if (is_countable($cheque_attachments)) {
                    foreach ($cheque_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path3 = $explode[0];
                        if ($attach == "") {
                            $attach = $path3;
                        } else {
                            $attach = $attach . "||" . $path3;
                        }
                        $email->AddAttachment($path3, $explode[1]);
                    }
                }

                $explode_pdf = explode("||", $pdf_attachments);
                $path4 = $explode_pdf[0];
                $email->AddAttachment($path4, $explode_pdf[1]);
                $email->Send();
            }
            $too = $explode[0];
            $get_client = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("email", $too)
                ->orwhere("email2", $too)
                ->first();
            if (is_countable($get_client)) {
                $client_id = $get_client->client_id;
            } else {
                $client_id = "";
            }
            if ($client_id != "") {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $client_id)
                    ->first();
                $datamessage["message_id"] = time();
                $datamessage["message_from"] = $from_input;
                $datamessage["subject"] = $subject;
                $datamessage["message"] = $contentmessage;
                $datamessage["client_ids"] = $client_id;
                $datamessage["primary_emails"] = $client_details->email;
                $datamessage["secondary_emails"] = $client_details->email2;
                $datamessage["date_sent"] = date("Y-m-d H:i:s");
                $datamessage["date_saved"] = date("Y-m-d H:i:s");
                $datamessage["source"] = "CRM SYSTEM";
                $datamessage["attachments"] = $attach;
                $datamessage["status"] = 1;
                $datamessage['practice_code'] = Session::get('user_practice_code');
                \App\Models\Messageus::insert($datamessage);
            }

            $date = date("Y-m-d H:i:s");
            $dataval["status"] = 1;
            $dataval["request_sent"] = $date;
            \App\Models\requestClient::where("request_id", $request_id)->update(
                $dataval
            );

            $dataemail["request_id"] = $request_id;
            $dataemail["email_sent"] = $date;
            \App\Models\requestClientEmailSent::insert($dataemail);

            return Redirect::back()->with(
                "message",
                "Email Sent Successfully for Client."
            );
        } else {
            return Redirect::back()->with(
                "error",
                "Email Field is empty so email is not sent"
            );
        }
    }

    public function email_for_approval(Request $request)
    {
        $request_id = $request->get("request_id_email_approval");
        $from_input = $request->get("from_user");
        $details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $from_input)->first();
        $from = $details->email;
        $user_name = $details->lastname . " " . $details->firstname;

        $to_input = $request->get("to_user");
        $details_to =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $to_input)->first();
        $to_user = $details_to->email;

        $toemails = $to_user . "," . $request->get("cc_approval");
        $sentmails = $to_user . ", " . $request->get("cc_approval");
        $subject = $request->get("subject_approval");
        $message = $request->get("message_editor");
        $explode = explode(",", $toemails);
        $data["sentmails"] = $sentmails;

        $purchase_attachments = $request->get("purchase_attachments");
        $sales_attachments = $request->get("sales_attachments");
        $cheque_attachments = $request->get("cheque_attachments");

        $attach = "";
        if (is_countable($explode)) {
            foreach ($explode as $exp) {
                $to = trim($exp);
                $data["logo"] = getEmailLogo('crm');
                $data["message"] = $message;
                $contentmessage = view("user/email_share_paper_crm", $data);
                $email = new PHPMailer();
                $email->SetFrom($from, $user_name); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                if (is_countable($purchase_attachments)) {
                    foreach ($purchase_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path = $explode[0];
                        if ($attach == "") {
                            $attach = $path;
                        } else {
                            $attach = $attach . "||" . $path;
                        }
                        $email->AddAttachment($path, $explode[1]);
                    }
                }

                if (is_countable($sales_attachments)) {
                    foreach ($sales_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path = $explode[0];
                        if ($attach == "") {
                            $attach = $path;
                        } else {
                            $attach = $attach . "||" . $path;
                        }
                        $email->AddAttachment($path, $explode[1]);
                    }
                }

                if (is_countable($cheque_attachments)) {
                    foreach ($cheque_attachments as $attachment) {
                        $explode = explode("||", $attachment);
                        $path = $explode[0];
                        if ($attach == "") {
                            $attach = $path;
                        } else {
                            $attach = $attach . "||" . $path;
                        }
                        $email->AddAttachment($path, $explode[1]);
                    }
                }
                $email->Send();
            }
            $too = $explode[0];
            $get_client = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("email", $too)
                ->orwhere("email2", $too)
                ->first();
            if (is_countable($get_client)) {
                $client_id = $get_client->client_id;
            } else {
                $client_id = "";
            }
            if ($client_id != "") {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $client_id)
                    ->first();
                $datamessage["message_id"] = time();
                $datamessage["message_from"] = $from_input;
                $datamessage["subject"] = $subject;
                $datamessage["message"] = $contentmessage;
                $datamessage["client_ids"] = $client_id;
                $datamessage["primary_emails"] = $client_details->email;
                $datamessage["secondary_emails"] = $client_details->email2;
                $datamessage["date_sent"] = date("Y-m-d H:i:s");
                $datamessage["date_saved"] = date("Y-m-d H:i:s");
                $datamessage["source"] = "CRM SYSTEM";
                $datamessage["attachments"] = $attach;
                $datamessage["status"] = 1;
                $datamessage['practice_code'] = Session::get('user_practice_code');
                \App\Models\Messageus::insert($datamessage);
            }

            $date = date("Y-m-d H:i:s");
            $dataemail["request_id"] = $request_id;
            $dataemail["email_sent"] = $date;
            $dataemail["type"] = 1;
            \App\Models\requestClientEmailSent::insert($dataemail);

            return Redirect::back()->with(
                "message",
                "Email Sent Successfully for Approval"
            );
        } else {
            return Redirect::back()->with(
                "error",
                "Email Field is empty so email is not sent"
            );
        }
    }
    public function edit_crm_header_image(Request $request) {
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

                    DB::table('request_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
    }
    public function save_crm_settings(Request $request) {
        $cc_email = $request->get('crs_cc_email');
        $data['crs_cc_email'] = $cc_email;

        $check_settings = DB::table('request_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        if($check_settings) {
              DB::table('request_settings')->where('id',$check_settings->id)->update($data);
        }
        else{
              $data['practice_code'] = Session::get('user_practice_code');
              DB::table('request_settings')->insert($data);
        }
        return redirect::back()->with('message', 'Request Settings Saved Sucessfully.');
    }
    
}
