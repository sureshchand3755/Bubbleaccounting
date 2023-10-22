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
class ClientreviewController extends Controller
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
    public function client_account_review(Request $request)
    {
        return view("user/client_review/client_account_review", [
            "title" => "Client Account Review",
        ]);
    }
    public function client_account_review_summary(Request $request)
    {
        return view("user/client_review/client_account_review_summary", [
            "title" => "Client Account Review",
        ]);
    }
    public function client_review_commonclient_search(Request $request)
    {
        $value = $request->get("term");
        $details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("status", 0)
            ->where(function ($q) use ($value) {
                $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
            })->get();
        $data = [];
        foreach ($details as $single) {
            if ($single->company != "") {
                $company = $single->company;
            } else {
                $company = $single->firstname . " " . $single->surname;
            }
            $data[] = [
                "value" => $company . "-" . $single->client_id,
                "id" => $single->client_id,
            ];
        }
        if (count($data)) {
            return $data;
        } else {
            return ["value" => "No Result Found", "id" => ""];
        }
    }
    public function client_review_client_select(Request $request)
    {
        $client_id = $request->get("client_id");
        $cro_informations = \App\Models\Croard::where("client_id", $client_id)->first();
        $cm_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        if ($cm_details) {
            if ($cro_informations) {
                $croard_val = $cro_informations->cro_ard;
            } else {
                $croard_val = "";
            }
            $client_details =
                '
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600">Client Name:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600">' .
                $cm_details->company .
                '</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600">Client Code:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600">' .
                $client_id .
                '</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600">Address:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600">';
            if ($cm_details->address1 != "") {
                $client_details .= $cm_details->address1 . "<br/>";
            }
            if ($cm_details->address2 != "") {
                $client_details .= $cm_details->address2 . "<br/>";
            }
            if ($cm_details->address3 != "") {
                $client_details .= $cm_details->address3 . "<br/>";
            }
            if ($cm_details->address4 != "") {
                $client_details .= $cm_details->address4 . "<br/>";
            }
            if ($cm_details->address5 != "") {
                $client_details .= $cm_details->address5 . "<br/>";
            }
            $client_details .=
                '</h5>
			</div>
			<div class="col-md-3" style="padding:0px">
				<h5 style="font-weight: 600">Email:</h5>
			</div>
			<div class="col-md-9" style="padding:0px">
				<h5 style="font-weight: 600">' .
                $cm_details->email .
                '</h5>
			</div>';
            $data["client_details"] = $client_details;
            $data["client_email"] = $cm_details->email;
            $data["company"] = $cm_details->company;
            $data["cro"] = $cm_details->cro;
            $data["ard"] = $cm_details->ard;
            $data["type"] = $cm_details->tye;
            $data["cro_ard"] = $croard_val;
            $invoice_year = DB::select(
                'SELECT *,SUBSTR(`invoice_date`, 1, 4) as `invoice_year` from `invoice_system` WHERE client_id = "' .
                    $client_id .
                    '" GROUP BY SUBSTR(`invoice_date`, 1, 4) ORDER BY SUBSTR(`invoice_date`, 1, 4) ASC'
            );
            $output_year = '<option value="">Select Year</option>';
            if (count($invoice_year)) {
                foreach ($invoice_year as $year) {
                    $output_year .=
                        '<option value="' .
                        $year->invoice_year .
                        '">' .
                        $year->invoice_year .
                        "</option>";
                }
            }
            $data["invoice_year"] = $output_year;
            $receipt_year = DB::select(
                'SELECT *,SUBSTR(`receipt_date`, 1, 4) as `receipt_year` from `receipts` WHERE `client_code` LIKE "%' .
                    $client_id .
                    '%" AND `credit_nominal` = "712" AND `imported` = "0" AND `status` = "0" GROUP BY SUBSTR(`receipt_date`, 1, 4) ORDER BY SUBSTR(`receipt_date`, 1, 4) ASC'
            );
            $output_receipt_year = '<option value="">Select Year</option>';
            if (count($receipt_year)) {
                foreach ($receipt_year as $year) {
                    $output_receipt_year .=
                        '<option value="' .
                        $year->receipt_year .
                        '">' .
                        $year->receipt_year .
                        "</option>";
                }
            }
            $data["receipt_year"] = $output_receipt_year;
            $letter_output = "";
            $letters = \App\Models\KeyDocsLetters::where("client_id", $client_id)->get();
            if (count($letters)) {
                $i = 1;
                foreach ($letters as $letter) {
                    $letter_output .=
                        '<tr>
						<td><input type="checkbox" name="key_docs_letter" class="key_docs_letter" id="key_docs_letter_' .
                        $letter->id .
                        '" data-element="' .
                        $letter->id .
                        '"><label for="key_docs_letter_' .
                        $letter->id .
                        '">' .
                        $i .
                        '</label></td>
						<td><a href="' .
                        URL::to($letter->url . "/" . $letter->filename) .
                        '" download>' .
                        $letter->filename .
                        '</a></td>
						<td><input type="text" name="letter_notes" class="form-control letter_notes" data-element="' .
                        $letter->id .
                        '" value="' .
                        $letter->notes .
                        '" maxlength="20"></td>
						<td><a href="javascript:" class="fa fa-trash delete_letter" data-element="' .
                        $letter->id .
                        '"></a></td>
					</tr>';
                    $i++;
                }
            } else {
                $letter_output .= '<tr>
					<td colspan="4">No Datas Found.</td>
				</tr>';
            }
            $data["letter_output"] = $letter_output;
            $tax_output = "";
            $taxs = \App\Models\taxClearanceFiles::where("client_id", $client_id)->get();
            if (count($taxs)) {
                $i = 1;
                foreach ($taxs as $tax) {
                    $tax_output .=
                        '<tr>
						<td><input type="checkbox" name="key_docs_tax" class="key_docs_tax" id="key_docs_tax_' .
                        $tax->id .
                        '" data-element="' .
                        $tax->id .
                        '"><label for="key_docs_tax_' .
                        $tax->id .
                        '">' .
                        $i .
                        '</label></td>
						<td><a href="' .
                        URL::to($tax->url . "/" . $tax->filename) .
                        '" download>' .
                        $tax->filename .
                        '</a></td>
						<td>' .
                        date("d-M-Y", strtotime($tax->updatetime)) .
                        '</td>
						<td><a href="javascript:" class="fa fa-trash delete_tax" data-element="' .
                        $tax->id .
                        '"></a></td>
					</tr>';
                    $i++;
                }
            } else {
                $tax_output .= '<tr>
					<td colspan="4">No Datas Found.</td>
				</tr>';
            }
            $data["tax_output"] = $tax_output;
            $current_tax_output = "";
            $current_taxs = \App\Models\CurrentTaxClearanceFiles::where(
                "client_id",
                $client_id
            )->first();
            if ($current_taxs) {
                $current_tax_output .=
                    '<tr>
						<td><a href="' .
                    URL::to(
                        $current_taxs->url . "/" . $current_taxs->filename
                    ) .
                    '" download>' .
                    $current_taxs->filename .
                    '</a></td>
						<td>' .
                    date("d-M-Y", strtotime($current_taxs->updatetime)) .
                    '</td>
						<td><a href="javascript:" class="fa fa-trash delete_current_tax" data-element="' .
                    $current_taxs->id .
                    '"></a></td>
					</tr>';
            } else {
                $current_tax_output .= '<tr>
					<td colspan="3">No Datas Found.</td>
				</tr>';
            }
            $data["current_tax_output"] = $current_tax_output;
            echo json_encode($data);
        }
    }
    public function update_cro_ard_date(Request $request)
    {
        $client_id = $request->get("client_id");
        $cro_ard = $request->get("cro_ard");
        $data["ard"] = $cro_ard;
        \App\Models\CMClients::where("practice_code", Session::get("user_practice_code"))
            ->where("client_id", $client_id)
            ->update($data);
    }
    public function client_review_load_all_client_invoice(Request $request)
    {
        $client_id = $request->get("client_id");
        $type = $request->get("type");
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        if ($type == "1") {
            $year = $request->get("year");
            $invoicelist = DB::select(
                'SELECT * from `invoice_system` WHERE client_id = "' .
                    $client_id .
                    '" AND `invoice_date` LIKE "' .
                    $year .
                    '%"'
            );
            $margin_top = "margin-top:-3px !important;";
        } elseif ($type == "2") {
            $invoicelist = \App\Models\InvoiceSystem::where(
                "client_id",
                $client_details->client_id
            )->get();
            $margin_top = "margin-top:40px !important;";
        } elseif ($type == "3") {
            $exp_from = explode("/", $request->get("from"));
            $exp_to = explode("/", $request->get("to"));
            $from = $exp_from[2] . "-" . $exp_from[1] . "-" . $exp_from[0];
            $to = $exp_to[2] . "-" . $exp_to[1] . "-" . $exp_to[0];
            $invoicelist = \App\Models\InvoiceSystem::where(
                "client_id",
                $client_details->client_id
            )
                ->where("invoice_date", ">=", $from)
                ->where("invoice_date", "<=", $to)
                ->get();
            $margin_top = "margin-top:-3px !important;";
        }
        $outputinvoice =
            '<div class="invoice_expand_tbl" style="width:100%;position:absolute; ' .
            $margin_top .
            '">
			<p style="position: relative;bottom: 0px;"><input type="checkbox" name="select_all_invoice" class="select_all_invoice" id="select_all_invoice" value=""><label for="select_all_invoice" style="margin-left: 0px;">Select All</label></p>
			<table class="display nowrap fullviewtablelist own_table_white" id="invoice_expand" width="100%" style="margin-bottom:100px !important;">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;">S.No <i class="fa fa-sort sort_sno"></i></th>
                      <th style="text-align: left;">Invoice # <i class="fa fa-sort sort_invoice"></i></th>
                      <th style="text-align: left;">Date <i class="fa fa-sort sort_date"></i></th>
                      <th style="text-align: right;">Net <i class="fa fa-sort sort_net"></i></th>
                      <th style="text-align: right;">VAT <i class="fa fa-sort sort_vat"></i></th>
                      <th style="text-align: right;">Gross <i class="fa fa-sort sort_gross"></i></th>                      
                  </tr>
                </thead>                            
                <tbody id="invoice_tbody">';
        $i = 1;
        $total_net = 0;
        $total_vat = 0;
        $total_gross = 0;
        if (count($invoicelist)) {
            foreach ($invoicelist as $invoice) {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice->client_id)
                    ->first();
                if ($invoice->statement == "No") {
                    $textcolor = "color:#f00";
                } else {
                    $textcolor = "color:#00751a";
                }
                $outputinvoice .=
                    '
					<tr>
						<td><spam class="sno_sort_val">' .
                    $i .
                    '</spam> <input type="checkbox" name="invoice_check" class="invoice_check" data-element="' .
                    $invoice->invoice_number .
                    '" id="invoice_id_' .
                    $invoice->invoice_number .
                    '"> <label for="invoice_id_' .
                    $invoice->invoice_number .
                    '">&nbsp;</label></td>
						<td align="left" style="' .
                    $textcolor .
                    '"><a href="javascript:" class="invoice_inside_class invoice_sort_val" data-element="' .
                    $invoice->invoice_number .
                    '">' .
                    $invoice->invoice_number .
                    '</a></td>
						<td align="left" style="' .
                    $textcolor .
                    '"><spam class="date_sort_val" style="display:none">' .
                    strtotime($invoice->invoice_date) .
                    "</spam>" .
                    date("d-M-Y", strtotime($invoice->invoice_date)) .
                    '</td>
						<td align="right" style="' .
                    $textcolor .
                    '"><spam class="net_sort_val" style="display:none">' .
                    $invoice->inv_net .
                    "</spam>" .
                    number_format_invoice($invoice->inv_net) .
                    '</td>
						<td align="right" style="' .
                    $textcolor .
                    '"><spam class="vat_sort_val" style="display:none">' .
                    $invoice->vat_value .
                    "</spam>" .
                    number_format_invoice($invoice->vat_value) .
                    '</td>
						<td align="right" style="' .
                    $textcolor .
                    '"><spam class="gross_sort_val" style="display:none">' .
                    $invoice->gross .
                    "</spam>" .
                    number_format_invoice($invoice->gross) .
                    '</td>						
					</tr>
				';
                $total_net = $total_net + $invoice->inv_net;
                $total_vat = $total_vat + $invoice->vat_value;
                $total_gross = $total_gross + $invoice->gross;
                $i++;
            }
            $outputinvoice .=
                '
			</tbody>
			<tbody>
					<tr>
						<td colspan="3">Total</td>
						<td align="right" style="' .
                $textcolor .
                '">' .
                number_format_invoice($total_net) .
                '</td>
						<td align="right" style="' .
                $textcolor .
                '">' .
                number_format_invoice($total_vat) .
                '</td>
						<td align="right" style="' .
                $textcolor .
                '">' .
                number_format_invoice($total_gross) .
                '</td>						
					</tr>
				';
        }
        if ($i == 1) {
            $outputinvoice .= '<tr>
          	<td></td>
          	<td></td>
          	<td>Empty</td>
          	<td align="right"></td>
          	<td></td>
          	<td></td>
          </tr>';
        }
        $outputinvoice .= '                
                </tbody>
            </table>
            </div>';
        echo json_encode(["invoiceoutput" => $outputinvoice]);
    }
    public function client_review_load_all_client_receipt(Request $request)
    {
        $client_id = $request->get("client_id");
        $type = $request->get("type");
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        if ($type == "1") {
            $year = $request->get("year");
            $receiptlist = DB::select(
                'SELECT * from `receipts` WHERE client_code LIKE "%' .
                    $client_id .
                    '%" AND `credit_nominal` = "712" AND imported = "0" AND `receipt_date` LIKE "' .
                    $year .
                    '%"'
            );
            $margin_top = "margin-top:48px !important;";
        } elseif ($type == "2") {
            $receiptlist = \App\Models\receipts::where(
                "client_code",
                "like",
                "%" . $client_details->client_id . "%"
            )
                ->where("credit_nominal", "712")
                ->where("imported", 0)
                ->get();
            $margin_top = "margin-top:102px !important;";
        } elseif ($type == "3") {
            $exp_from = explode("/", $request->get("from"));
            $exp_to = explode("/", $request->get("to"));
            $from = $exp_from[2] . "-" . $exp_from[1] . "-" . $exp_from[0];
            $to = $exp_to[2] . "-" . $exp_to[1] . "-" . $exp_to[0];
            $receiptlist = \App\Models\receipts::where(
                "client_code",
                "like",
                "%" . $client_details->client_id . "%"
            )
                ->where("receipt_date", ">=", $from)
                ->where("receipt_date", "<=", $to)
                ->where("credit_nominal", "712")
                ->where("imported", 0)
                ->get();
            $margin_top = "margin-top:45px !important;";
        }
        $outputreceipt =
            '<table class="display nowrap fullviewtablelist own_table_white" id="receipt_expand" width="100%" style="position:absolute; ' .
            $margin_top .
            '">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;">Date <i class="fa fa-sort sort_receipt_date"></i></th>
                      <th style="text-align: right;">Amount <i class="fa fa-sort sort_amount"></i></th>                   
                  </tr>
                </thead>                            
                <tbody id="receipt_tbody">';
        $i = 1;
        $total_amount = 0;
        if (count($receiptlist)) {
            foreach ($receiptlist as $receipt) {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $receipt->client_code)
                    ->first();
                $outputreceipt .=
                    '
					<tr>
						<td align="left"><spam class="receipt_date_sort_val" style="display:none">' .
                    strtotime($receipt->receipt_date) .
                    "</spam>" .
                    date("d-M-Y", strtotime($receipt->receipt_date)) .
                    '</td>
						<td align="right" class="amount_sort_val">' .
                    number_format_invoice_empty($receipt->amount) .
                    '</td>
					</tr>
				';
                $total_amount = $total_amount + $receipt->amount;
                $i++;
            }
            $outputreceipt .=
                '
			</tbody>
			<tbody>
			<tr>
				<td>Total</td>
				<td align="right">' .
                number_format_invoice_empty($total_amount) .
                '</td>						
			</tr>';
        }
        if ($i == 1) {
            $outputreceipt .= '<tr>
          	<td>Empty</td>
          	<td align="right"></td>
          </tr>';
        }
        $outputreceipt .= '                
                </tbody>
            </table>
            <p style="float:left;clear:both;margin-bottom:100px;">&nbsp;</p>
            <p style="float:left;clear:both;margin-bottom:100px;">&nbsp;</p>
            <p style="float:left;clear:both;margin-bottom:100px;">&nbsp;</p>
            <p style="float:left;clear:both;margin-bottom:100px;">&nbsp;</p>
            <p style="float:left;clear:both;margin-bottom:100px;">&nbsp;</p>';
        echo json_encode(["receiptoutput" => $outputreceipt]);
    }
    public function invoice_email_selected_pdfs(Request $request)
    {
        $ids = explode(",", $request->get("ids"));
        $pdfsname = "";
        if (count($ids) == 1) {
            $html_main =
                '<style>
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
            foreach ($ids as $id) {
                $invoice_details = \App\Models\InvoiceSystem::where(
                    "invoice_number",
                    $id
                )->first();
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice_details->client_id)
                    ->first();
                if (!$client_details) {
                    $companyname =
                        '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
                    //$companyname = $company_firstname;
                    $taxdetails = "";
                    $row1 = "";
                    $row2 = "";
                    $row3 = "";
                    $row4 = "";
                    $row5 = "";
                    $row6 = "";
                    $row7 = "";
                    $row8 = "";
                    $row9 = "";
                    $row10 = "";
                    $row11 = "";
                    $row12 = "";
                    $row13 = "";
                    $row14 = "";
                    $row15 = "";
                    $row16 = "";
                    $row17 = "";
                    $row18 = "";
                    $row19 = "";
                    $row20 = "";
                } else {
                    $company_firstname =
                        '
                    <div class="company_details_div">
                      <div class="firstname_div">
                        <b>To:</b><br/>
                        ' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        '<br/>  
                        ' .
                        $client_details->company .
                        '<br/>  
                        ' .
                        $client_details->address1 .
                        '<br/>  
                        ' .
                        $client_details->address2 .
                        '<br/>
                        ' .
                        $client_details->address3 .
                        '
                      </div>
                    </div>
                    <div class="account_details_div">
                      <div class="account_details_main_address_div">
                            <div class="aib_account">
                                AIB Account: 48870061<br/>
                                Sort Code: 93-72-23<br/>
                                VAT Number: 9754009E<br/>
                                Company Number: 485123
                            </div>                   
                          <div class="account_details_invoice_div">
                            <div class="account_table">
                              <div class="account_row">
                                <div class="account_row_td left"><b>Account:</b></div>
                                <div class="account_row_td right">' .
                        $client_details->client_id .
                        '</div>
                              </div>
                              <div class="account_row">
                                <div class="account_row_td left"><b>Invoice:</b></div>
                                <div class="account_row_td right">' .
                        $invoice_details->invoice_number .
                        '</div>
                              </div>
                              <div class="account_row">
                                <div class="account_row_td left"><b>Date:</b></div>
                                <div class="account_row_td right">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        '</div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="invoice_label">
                      INVOICE
                    </div>';
                    if ($invoice_details->bn_row1 != "") {
                        $bn_row1_add_zero = number_format_invoice(
                            $invoice_details->bn_row1
                        );
                    } else {
                        $bn_row1_add_zero = "";
                    }
                    $row1 =
                        '
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->z_row1 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->at_row1 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bn_row1_add_zero .
                        "</div>";
                    if ($invoice_details->bo_row2 != "") {
                        $bo_row2_add_zero = number_format_invoice(
                            $invoice_details->bo_row2
                        );
                    } else {
                        $bo_row2_add_zero = "";
                    }
                    $row2 =
                        '<div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->aa_row2 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->au_row2 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bo_row2_add_zero .
                        "</div>";
                    if ($invoice_details->bp_row3 != "") {
                        $bp_row3_add_zero = number_format_invoice(
                            $invoice_details->bp_row3
                        );
                    } else {
                        $bp_row3_add_zero = "";
                    }
                    $row3 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ab_row3 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->av_row3 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bp_row3_add_zero .
                        "</div>";
                    if ($invoice_details->bq_row4 != "") {
                        $bq_row4_add_zero = number_format_invoice(
                            $invoice_details->bq_row4
                        );
                    } else {
                        $bq_row4_add_zero = "";
                    }
                    $row4 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ac_row4 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->aw_row4 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bq_row4_add_zero .
                        "</div>";
                    if ($invoice_details->br_row5 != "") {
                        $br_row5_add_zero = number_format_invoice(
                            $invoice_details->br_row5
                        );
                    } else {
                        $br_row5_add_zero = "";
                    }
                    $row5 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ad_row5 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->ax_row5 .
                        '</div>
                      <div class="class_row_td right">' .
                        $br_row5_add_zero .
                        "</div>";
                    if ($invoice_details->bs_row6 != "") {
                        $bs_row6_add_zero = number_format_invoice(
                            $invoice_details->bs_row6
                        );
                    } else {
                        $bs_row6_add_zero = "";
                    }
                    $row6 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ae_row6 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->ay_row6 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bs_row6_add_zero .
                        "</div>";
                    if ($invoice_details->bt_row7 != "") {
                        $bt_row7_add_zero = number_format_invoice(
                            $invoice_details->bt_row7
                        );
                    } else {
                        $bt_row7_add_zero = "";
                    }
                    $row7 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->af_row7 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->az_row7 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bt_row7_add_zero .
                        "</div>";
                    if ($invoice_details->bu_row8 != "") {
                        $bu_row8_add_zero = number_format_invoice(
                            $invoice_details->bu_row8
                        );
                    } else {
                        $bu_row8_add_zero = "";
                    }
                    $row8 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ag_row8 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->ba_row8 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bu_row8_add_zero .
                        "</div>";
                    if ($invoice_details->bv_row9 != "") {
                        $bv_row9_add_zero = number_format_invoice(
                            $invoice_details->bv_row9
                        );
                    } else {
                        $bv_row9_add_zero = "";
                    }
                    $row9 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ah_row9 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->bb_row9 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bv_row9_add_zero .
                        "</div>";
                    if ($invoice_details->bw_row10 != "") {
                        $bw_row10_add_zero = number_format_invoice(
                            $invoice_details->bw_row10
                        );
                    } else {
                        $bw_row10_add_zero = "";
                    }
                    $row10 =
                        '               
                      <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                        '</div>
                      <div class="class_row_td left_corner">' .
                        $invoice_details->ai_row10 .
                        '</div>
                      <div class="class_row_td right_start">' .
                        $invoice_details->bc_row10 .
                        '</div>
                      <div class="class_row_td right">' .
                        $bw_row10_add_zero .
                        "</div>";
                    if ($invoice_details->bx_row11 != "") {
                        $bx_row11_add_zero = number_format_invoice(
                            $invoice_details->bx_row11
                        );
                    } else {
                        $bx_row11_add_zero = "";
                    }
                    $row11 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->aj_row11 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bd_row11 .
                        '</div>
                          <div class="class_row_td right">' .
                        $bx_row11_add_zero .
                        "</div>";
                    if ($invoice_details->by_row12 != "") {
                        $by_row12_add_zero = number_format_invoice(
                            $invoice_details->by_row12
                        );
                    } else {
                        $by_row12_add_zero = "";
                    }
                    $row12 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->ak_row12 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->be_row12 .
                        '</div>
                          <div class="class_row_td right">' .
                        $by_row12_add_zero .
                        "</div>";
                    if ($invoice_details->bz_row13 != "") {
                        $bz_row13_add_zero = number_format_invoice(
                            $invoice_details->bz_row13
                        );
                    } else {
                        $bz_row13_add_zero = "";
                    }
                    $row13 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->al_row13 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bf_row13 .
                        '</div>
                          <div class="class_row_td right">' .
                        $bz_row13_add_zero .
                        "</div>";
                    if ($invoice_details->ca_row14 != "") {
                        $ca_row14_add_zero = number_format_invoice(
                            $invoice_details->ca_row14
                        );
                    } else {
                        $ca_row14_add_zero = "";
                    }
                    $row14 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->am_row14 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bg_row14 .
                        '</div>
                          <div class="class_row_td right">' .
                        $ca_row14_add_zero .
                        "</div>";
                    if ($invoice_details->cb_row15 != "") {
                        $cb_row15_add_zero = number_format_invoice(
                            $invoice_details->cb_row15
                        );
                    } else {
                        $cb_row15_add_zero = "";
                    }
                    $row15 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->an_row15 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bh_row15 .
                        '</div>
                          <div class="class_row_td right">' .
                        $cb_row15_add_zero .
                        "</div>";
                    if ($invoice_details->cc_row16 != "") {
                        $cc_row16_add_zero = number_format_invoice(
                            $invoice_details->cc_row16
                        );
                    } else {
                        $cc_row16_add_zero = "";
                    }
                    $row16 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->ao_row16 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bi_row16 .
                        '</div>
                          <div class="class_row_td right">' .
                        $cc_row16_add_zero .
                        "</div>";
                    if ($invoice_details->cd_row17 != "") {
                        $cd_row17_add_zero = number_format_invoice(
                            $invoice_details->cd_row17
                        );
                    } else {
                        $cd_row17_add_zero = "";
                    }
                    $row17 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->ap_row17 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bj_row17 .
                        '</div>
                          <div class="class_row_td right">' .
                        $cd_row17_add_zero .
                        "</div>";
                    if ($invoice_details->ce_row18 != "") {
                        $ce_row18_add_zero = number_format_invoice(
                            $invoice_details->ce_row18
                        );
                    } else {
                        $ce_row18_add_zero = "";
                    }
                    $row18 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->aq_row18 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bk_row18 .
                        '</div>
                          <div class="class_row_td right">' .
                        $ce_row18_add_zero .
                        "</div>";
                    if ($invoice_details->cf_row19 != "") {
                        $cf_row19_add_zero = number_format_invoice(
                            $invoice_details->cf_row19
                        );
                    } else {
                        $cf_row19_add_zero = "";
                    }
                    $row19 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->ar_row19 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bl_row19 .
                        '</div>
                          <div class="class_row_td right">' .
                        $cf_row19_add_zero .
                        "</div>";
                    if ($invoice_details->cg_row20 != "") {
                        $cg_row20_add_zero = number_format_invoice(
                            $invoice_details->cg_row20
                        );
                    } else {
                        $cg_row20_add_zero = "";
                    }
                    $row20 =
                        '               
                          <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                        '</div>
                          <div class="class_row_td left_corner">' .
                        $invoice_details->as_row20 .
                        '</div>
                          <div class="class_row_td right_start">' .
                        $invoice_details->bm_row20 .
                        '</div>
                          <div class="class_row_td right">' .
                        $cg_row20_add_zero .
                        "</div>";
                    $tax_details =
                        '
                     <div class="tax_table_div">
                        <div class="tax_table">
                          <div class="tax_row">
                            <div class="tax_row_td left">Total Fees (as agreed)</div>
                            <div class="tax_row_td right">' .
                        number_format_invoice($invoice_details->inv_net) .
                        '</div>
                          </div>
                          <div class="tax_row">
                            <div class="tax_row_td left">VAT @ 23%</div>
                            <div class="tax_row_td right" style="border-top:0px">' .
                        number_format_invoice($invoice_details->vat_value) .
                        '</div>
                          </div>
                          <div class="tax_row">
                            <div class="tax_row_td left" style="color:#fff">.</div>
                            <div class="tax_row_td right">' .
                        number_format_invoice($invoice_details->gross) .
                        '</div>
                          </div>
                          <div class="tax_row">
                            <div class="tax_row_td left">Outlay @ 0%</div>
                            <div class="tax_row_td right" style="border-top:0px;">..</div>
                          </div>
                          <div class="tax_row">
                            <div class="tax_row_td left">Total Fees Due</div>
                            <div class="tax_row_td right" style="border-bottom: 2px solid #000">' .
                        number_format_invoice($invoice_details->gross) .
                        '</div>
                          </div>
                        </div>
                      </div>
                     ';
                    $companyname = $company_firstname;
                    $taxdetails = $tax_details;
                    $row1 = $row1;
                    $row2 = $row2;
                    $row3 = $row3;
                    $row4 = $row4;
                    $row5 = $row5;
                    $row6 = $row6;
                    $row7 = $row7;
                    $row8 = $row8;
                    $row9 = $row9;
                    $row10 = $row10;
                    $row11 = $row11;
                    $row12 = $row12;
                    $row13 = $row13;
                    $row14 = $row14;
                    $row15 = $row15;
                    $row16 = $row16;
                    $row17 = $row17;
                    $row18 = $row18;
                    $row19 = $row19;
                    $row20 = $row20;
                }
                $output =
                    '<div class="company_details_class">' .
                    $companyname .
                    '</div>
                    <div class="tax_details_class_maindiv">
                      <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                        <div class="class_row class_row1">' .
                        $row1 .
                        '</div>
                        <div class="class_row class_row2">' .
                        $row2 .
                        '</div>
                        <div class="class_row class_row3">' .
                        $row3 .
                        '</div>
                        <div class="class_row class_row4">' .
                        $row4 .
                        '</div>
                        <div class="class_row class_row5">' .
                        $row5 .
                        '</div>
                        <div class="class_row class_row6">' .
                        $row6 .
                        '</div>
                        <div class="class_row class_row7">' .
                        $row7 .
                        '</div>
                        <div class="class_row class_row8">' .
                        $row8 .
                        '</div>
                        <div class="class_row class_row9">' .
                        $row9 .
                        '</div>
                        <div class="class_row class_row10">' .
                        $row10 .
                        '</div>
                        <div class="class_row class_row11">' .
                        $row11 .
                        '</div>
                        <div class="class_row class_row12">' .
                        $row12 .
                        '</div>
                        <div class="class_row class_row13">' .
                        $row13 .
                        '</div>
                        <div class="class_row class_row14">' .
                        $row14 .
                        '</div>
                        <div class="class_row class_row15">' .
                        $row15 .
                        '</div>
                        <div class="class_row class_row16">' .
                        $row16 .
                        '</div>
                        <div class="class_row class_row17">' .
                        $row17 .
                        '</div>
                        <div class="class_row class_row18">' .
                        $row18 .
                        '</div>
                        <div class="class_row class_row19">' .
                        $row19 .
                        '</div>
                        <div class="class_row class_row20">' .
                        $row20 .
                        '</div>
                      </div>
                    </div>
                    <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
                    <div class="tax_details_class">' .
                        $taxdetails .
                    "</div>";
                $html = $html_main . $output;


                $pdf = PDF::loadHTML($html);
                $zipFileName = time() . "_Invoice of " . $id . ".pdf";
                $pdf->save("public/papers/" . $zipFileName);
                $pdfsname = "<p>" . $zipFileName . "</p>";
            }
        } else {
            $html_main =
                '<style>
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
            $time = time();
            $zipFileName = $time . ".zip";
            $zip = new ZipArchive();
            if (
                $zip->open("public/papers/" . $zipFileName, ZipArchive::CREATE) ===
                true
            ) {
                if (count($ids)) {
                    foreach ($ids as $id) {
                        $invoice_details = \App\Models\InvoiceSystem::where(
                            "invoice_number",
                            $id
                        )->first();
                        $client_details = \App\Models\CMClients::where(
                            "practice_code",
                            Session::get("user_practice_code")
                        )
                            ->where("client_id", $invoice_details->client_id)
                            ->first();
                        if (!$client_details) {
                            $companyname =
                                '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
                            $companyname = $company_firstname;
                            $taxdetails = "";
                            $row1 = "";
                            $row2 = "";
                            $row3 = "";
                            $row4 = "";
                            $row5 = "";
                            $row6 = "";
                            $row7 = "";
                            $row8 = "";
                            $row9 = "";
                            $row10 = "";
                            $row11 = "";
                            $row12 = "";
                            $row13 = "";
                            $row14 = "";
                            $row15 = "";
                            $row16 = "";
                            $row17 = "";
                            $row18 = "";
                            $row19 = "";
                            $row20 = "";
                        } else {
                            $company_firstname =
                                '
                            <div class="company_details_div">
                              <div class="firstname_div">
                                <b>To:</b><br/>
                                ' .
                                $client_details->firstname .
                                " " .
                                $client_details->surname .
                                '<br/>  
                                ' .
                                $client_details->company .
                                '<br/>  
                                ' .
                                $client_details->address1 .
                                '<br/>  
                                ' .
                                $client_details->address2 .
                                '<br/>
                                ' .
                                $client_details->address3 .
                                '
                              </div>
                            </div>
                            <div class="account_details_div">
                              <div class="account_details_main_address_div">
                                    <div class="aib_account">
                                        AIB Account: 48870061<br/>
                                        Sort Code: 93-72-23<br/>
                                        VAT Number: 9754009E<br/>
                                        Company Number: 485123
                                    </div>                   
                                  <div class="account_details_invoice_div">
                                    <div class="account_table">
                                      <div class="account_row">
                                        <div class="account_row_td left"><b>Account:</b></div>
                                        <div class="account_row_td right">' .
                                $client_details->client_id .
                                '</div>
                                      </div>
                                      <div class="account_row">
                                        <div class="account_row_td left"><b>Invoice:</b></div>
                                        <div class="account_row_td right">' .
                                $invoice_details->invoice_number .
                                '</div>
                                      </div>
                                      <div class="account_row">
                                        <div class="account_row_td left"><b>Date:</b></div>
                                        <div class="account_row_td right">' .
                                date(
                                    "d-M-Y",
                                    strtotime($invoice_details->invoice_date)
                                ) .
                                '</div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="invoice_label">
                              INVOICE
                            </div>';
                            if ($invoice_details->bn_row1 != "") {
                                $bn_row1_add_zero = number_format_invoice(
                                    $invoice_details->bn_row1
                                );
                            } else {
                                $bn_row1_add_zero = "";
                            }
                            $row1 =
                                '
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->f_row1
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->z_row1 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->at_row1 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bn_row1_add_zero .
                                "</div>";
                            if ($invoice_details->bo_row2 != "") {
                                $bo_row2_add_zero = number_format_invoice(
                                    $invoice_details->bo_row2
                                );
                            } else {
                                $bo_row2_add_zero = "";
                            }
                            $row2 =
                                '<div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->g_row2
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->aa_row2 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->au_row2 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bo_row2_add_zero .
                                "</div>";
                            if ($invoice_details->bp_row3 != "") {
                                $bp_row3_add_zero = number_format_invoice(
                                    $invoice_details->bp_row3
                                );
                            } else {
                                $bp_row3_add_zero = "";
                            }
                            $row3 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->h_row3
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ab_row3 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->av_row3 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bp_row3_add_zero .
                                "</div>";
                            if ($invoice_details->bq_row4 != "") {
                                $bq_row4_add_zero = number_format_invoice(
                                    $invoice_details->bq_row4
                                );
                            } else {
                                $bq_row4_add_zero = "";
                            }
                            $row4 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->i_row4
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ac_row4 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->aw_row4 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bq_row4_add_zero .
                                "</div>";
                            if ($invoice_details->br_row5 != "") {
                                $br_row5_add_zero = number_format_invoice(
                                    $invoice_details->br_row5
                                );
                            } else {
                                $br_row5_add_zero = "";
                            }
                            $row5 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->j_row5
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ad_row5 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->ax_row5 .
                                '</div>
                              <div class="class_row_td right">' .
                                $br_row5_add_zero .
                                "</div>";
                            if ($invoice_details->bs_row6 != "") {
                                $bs_row6_add_zero = number_format_invoice(
                                    $invoice_details->bs_row6
                                );
                            } else {
                                $bs_row6_add_zero = "";
                            }
                            $row6 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->k_row6
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ae_row6 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->ay_row6 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bs_row6_add_zero .
                                "</div>";
                            if ($invoice_details->bt_row7 != "") {
                                $bt_row7_add_zero = number_format_invoice(
                                    $invoice_details->bt_row7
                                );
                            } else {
                                $bt_row7_add_zero = "";
                            }
                            $row7 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->l_row7
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->af_row7 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->az_row7 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bt_row7_add_zero .
                                "</div>";
                            if ($invoice_details->bu_row8 != "") {
                                $bu_row8_add_zero = number_format_invoice(
                                    $invoice_details->bu_row8
                                );
                            } else {
                                $bu_row8_add_zero = "";
                            }
                            $row8 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->m_row8
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ag_row8 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->ba_row8 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bu_row8_add_zero .
                                "</div>";
                            if ($invoice_details->bv_row9 != "") {
                                $bv_row9_add_zero = number_format_invoice(
                                    $invoice_details->bv_row9
                                );
                            } else {
                                $bv_row9_add_zero = "";
                            }
                            $row9 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->n_row9
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ah_row9 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->bb_row9 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bv_row9_add_zero .
                                "</div>";
                            if ($invoice_details->bw_row10 != "") {
                                $bw_row10_add_zero = number_format_invoice(
                                    $invoice_details->bw_row10
                                );
                            } else {
                                $bw_row10_add_zero = "";
                            }
                            $row10 =
                                '               
                              <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->o_row10
                                ) .
                                '</div>
                              <div class="class_row_td left_corner">' .
                                $invoice_details->ai_row10 .
                                '</div>
                              <div class="class_row_td right_start">' .
                                $invoice_details->bc_row10 .
                                '</div>
                              <div class="class_row_td right">' .
                                $bw_row10_add_zero .
                                "</div>";
                            if ($invoice_details->bx_row11 != "") {
                                $bx_row11_add_zero = number_format_invoice(
                                    $invoice_details->bx_row11
                                );
                            } else {
                                $bx_row11_add_zero = "";
                            }
                            $row11 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->p_row11
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->aj_row11 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bd_row11 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $bx_row11_add_zero .
                                "</div>";
                            if ($invoice_details->by_row12 != "") {
                                $by_row12_add_zero = number_format_invoice(
                                    $invoice_details->by_row12
                                );
                            } else {
                                $by_row12_add_zero = "";
                            }
                            $row12 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->q_row12
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->ak_row12 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->be_row12 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $by_row12_add_zero .
                                "</div>";
                            if ($invoice_details->bz_row13 != "") {
                                $bz_row13_add_zero = number_format_invoice(
                                    $invoice_details->bz_row13
                                );
                            } else {
                                $bz_row13_add_zero = "";
                            }
                            $row13 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->r_row13
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->al_row13 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bf_row13 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $bz_row13_add_zero .
                                "</div>";
                            if ($invoice_details->ca_row14 != "") {
                                $ca_row14_add_zero = number_format_invoice(
                                    $invoice_details->ca_row14
                                );
                            } else {
                                $ca_row14_add_zero = "";
                            }
                            $row14 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->s_row14
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->am_row14 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bg_row14 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $ca_row14_add_zero .
                                "</div>";
                            if ($invoice_details->cb_row15 != "") {
                                $cb_row15_add_zero = number_format_invoice(
                                    $invoice_details->cb_row15
                                );
                            } else {
                                $cb_row15_add_zero = "";
                            }
                            $row15 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->t_row15
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->an_row15 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bh_row15 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $cb_row15_add_zero .
                                "</div>";
                            if ($invoice_details->cc_row16 != "") {
                                $cc_row16_add_zero = number_format_invoice(
                                    $invoice_details->cc_row16
                                );
                            } else {
                                $cc_row16_add_zero = "";
                            }
                            $row16 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->u_row16
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->ao_row16 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bi_row16 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $cc_row16_add_zero .
                                "</div>";
                            if ($invoice_details->cd_row17 != "") {
                                $cd_row17_add_zero = number_format_invoice(
                                    $invoice_details->cd_row17
                                );
                            } else {
                                $cd_row17_add_zero = "";
                            }
                            $row17 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->v_row17
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->ap_row17 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bj_row17 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $cd_row17_add_zero .
                                "</div>";
                            if ($invoice_details->ce_row18 != "") {
                                $ce_row18_add_zero = number_format_invoice(
                                    $invoice_details->ce_row18
                                );
                            } else {
                                $ce_row18_add_zero = "";
                            }
                            $row18 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->w_row18
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->aq_row18 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bk_row18 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $ce_row18_add_zero .
                                "</div>";
                            if ($invoice_details->cf_row19 != "") {
                                $cf_row19_add_zero = number_format_invoice(
                                    $invoice_details->cf_row19
                                );
                            } else {
                                $cf_row19_add_zero = "";
                            }
                            $row19 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->x_row19
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->ar_row19 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bl_row19 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $cf_row19_add_zero .
                                "</div>";
                            if ($invoice_details->cg_row20 != "") {
                                $cg_row20_add_zero = number_format_invoice(
                                    $invoice_details->cg_row20
                                );
                            } else {
                                $cg_row20_add_zero = "";
                            }
                            $row20 =
                                '               
                                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->y_row20
                                ) .
                                '</div>
                                  <div class="class_row_td left_corner">' .
                                $invoice_details->as_row20 .
                                '</div>
                                  <div class="class_row_td right_start">' .
                                $invoice_details->bm_row20 .
                                '</div>
                                  <div class="class_row_td right">' .
                                $cg_row20_add_zero .
                                "</div>";
                            $tax_details =
                                '
                             <div class="tax_table_div">
                                <div class="tax_table">
                                  <div class="tax_row">
                                    <div class="tax_row_td left">Total Fees (as agreed)</div>
                                    <div class="tax_row_td right" width="13%">' .
                                number_format_invoice(
                                    $invoice_details->inv_net
                                ) .
                                '</div>
                                  </div>
                                  <div class="tax_row">
                                    <div class="tax_row_td left">VAT @ 23%</div>
                                    <div class="tax_row_td right" style="border-top:0px;">' .
                                number_format_invoice(
                                    $invoice_details->vat_value
                                ) .
                                '</div>
                                  </div>
                                  <div class="tax_row">
                                    <div class="tax_row_td left" style="color:#fff">.</div>
                                    <div class="tax_row_td right">' .
                                number_format_invoice($invoice_details->gross) .
                                '</div>
                                  </div>
                                  <div class="tax_row">
                                    <div class="tax_row_td left">Outlay @ 0%</div>
                                    <div class="tax_row_td right" style="border-top:0px;">..</div>
                                  </div>
                                  <div class="tax_row">
                                    <div class="tax_row_td left">Total Fees Due</div>
                                    <div class="tax_row_td right" style="border-bottom: 2px solid #000">' .
                                number_format_invoice($invoice_details->gross) .
                                '</div>
                                  </div>
                                </div>
                              </div>
                             ';
                            $companyname = $company_firstname;
                            $taxdetails = $tax_details;
                            $row1 = $row1;
                            $row2 = $row2;
                            $row3 = $row3;
                            $row4 = $row4;
                            $row5 = $row5;
                            $row6 = $row6;
                            $row7 = $row7;
                            $row8 = $row8;
                            $row9 = $row9;
                            $row10 = $row10;
                            $row11 = $row11;
                            $row12 = $row12;
                            $row13 = $row13;
                            $row14 = $row14;
                            $row15 = $row15;
                            $row16 = $row16;
                            $row17 = $row17;
                            $row18 = $row18;
                            $row19 = $row19;
                            $row20 = $row20;
                        }
                        $output =
                            '<div class="company_details_class">' .
                            $companyname .
                            '</div>
                        <div class="tax_details_class_maindiv">
                          <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                            <div class="class_row class_row1">' .
                            $row1 .
                            '</div>
                            <div class="class_row class_row2">' .
                            $row2 .
                            '</div>
                            <div class="class_row class_row3">' .
                            $row3 .
                            '</div>
                            <div class="class_row class_row4">' .
                            $row4 .
                            '</div>
                            <div class="class_row class_row5">' .
                            $row5 .
                            '</div>
                            <div class="class_row class_row6">' .
                            $row6 .
                            '</div>
                            <div class="class_row class_row7">' .
                            $row7 .
                            '</div>
                            <div class="class_row class_row8">' .
                            $row8 .
                            '</div>
                            <div class="class_row class_row9">' .
                            $row9 .
                            '</div>
                            <div class="class_row class_row10">' .
                            $row10 .
                            '</div>
                            <div class="class_row class_row11">' .
                            $row11 .
                            '</div>
                            <div class="class_row class_row12">' .
                            $row12 .
                            '</div>
                            <div class="class_row class_row13">' .
                            $row13 .
                            '</div>
                            <div class="class_row class_row14">' .
                            $row14 .
                            '</div>
                            <div class="class_row class_row15">' .
                            $row15 .
                            '</div>
                            <div class="class_row class_row16">' .
                            $row16 .
                            '</div>
                            <div class="class_row class_row17">' .
                            $row17 .
                            '</div>
                            <div class="class_row class_row18">' .
                            $row18 .
                            '</div>
                            <div class="class_row class_row19">' .
                            $row19 .
                            '</div>
                            <div class="class_row class_row20">' .
                            $row20 .
                            '</div>
                          </div>
                        </div>
                        <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
                        <div class="tax_details_class">' .
                            $taxdetails .
                            "</div>";
                        $html = $html_main . $output;
                        $pdf = PDF::loadHTML($html);
                        $pdf->save("public/papers/Invoice of " . $id . ".pdf");
                        $zip->addFile(
                            "public/papers/Invoice of " . $id . ".pdf",
                            "Invoice of " . $id . ".pdf"
                        );
                        if ($pdfsname == "") {
                            $pdfsname = "<p>Invoice of " . $id . ".pdf</p>";
                        } else {
                            $pdfsname =
                                $pdfsname . "<p>Invoice of " . $id . ".pdf</p>";
                        }
                    }
                }
            }
            $zip->close();
        }

        $clientaccountreview_settings = DB::table('clientaccountreview_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $signature = '';
        if($clientaccountreview_settings) {
            $signature = $clientaccountreview_settings->email_signature;
        }
        echo json_encode(["zipfile" => $zipFileName, "pdfs" => $pdfsname,'signature' => $signature]);
    }
    public function invoice_download_selected_pdfs(Request $request)
    {
        $ids = explode(",", $request->get("ids"));
        $pdfsname = "";
        if (count($ids) == 1) {
            $html_main =
                '<style>
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
            foreach ($ids as $id) {
                $invoice_details = \App\Models\InvoiceSystem::where(
                    "invoice_number",
                    $id
                )->first();
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice_details->client_id)
                    ->first();
                if (!$client_details) {
                    $companyname =
                        '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
                    //$companyname = $company_firstname;
                    $taxdetails = "";
                    $row1 = "";
                    $row2 = "";
                    $row3 = "";
                    $row4 = "";
                    $row5 = "";
                    $row6 = "";
                    $row7 = "";
                    $row8 = "";
                    $row9 = "";
                    $row10 = "";
                    $row11 = "";
                    $row12 = "";
                    $row13 = "";
                    $row14 = "";
                    $row15 = "";
                    $row16 = "";
                    $row17 = "";
                    $row18 = "";
                    $row19 = "";
                    $row20 = "";
                } else {
                    $company_firstname =
                        '
	          		<div class="company_details_div">
		              <div class="firstname_div">
		                <b>To:</b><br/>
		                ' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        '<br/>  
		                ' .
                        $client_details->company .
                        '<br/>  
		                ' .
                        $client_details->address1 .
                        '<br/>  
		                ' .
                        $client_details->address2 .
                        '<br/>
		                ' .
                        $client_details->address3 .
                        '
		              </div>
		            </div>
		            <div class="account_details_div">
		              <div class="account_details_main_address_div">
		              		<div class="aib_account">
				                AIB Account: 48870061<br/>
				                Sort Code: 93-72-23<br/>
				                VAT Number: 9754009E<br/>
				                Company Number: 485123
				            </div>	                 
		                  <div class="account_details_invoice_div">
		                    <div class="account_table">
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Account:</b></div>
		                        <div class="account_row_td right">' .
                        $client_details->client_id .
                        '</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Invoice:</b></div>
		                        <div class="account_row_td right">' .
                        $invoice_details->invoice_number .
                        '</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Date:</b></div>
		                        <div class="account_row_td right">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        '</div>
		                      </div>
		                    </div>
		                  </div>
		              </div>
		            </div>
		            <div class="invoice_label">
		              INVOICE
		            </div>';
                    if ($invoice_details->bn_row1 != "") {
                        $bn_row1_add_zero = number_format_invoice(
                            $invoice_details->bn_row1
                        );
                    } else {
                        $bn_row1_add_zero = "";
                    }
                    $row1 =
                        '
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->z_row1 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->at_row1 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bn_row1_add_zero .
                        "</div>";
                    if ($invoice_details->bo_row2 != "") {
                        $bo_row2_add_zero = number_format_invoice(
                            $invoice_details->bo_row2
                        );
                    } else {
                        $bo_row2_add_zero = "";
                    }
                    $row2 =
                        '<div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->aa_row2 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->au_row2 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bo_row2_add_zero .
                        "</div>";
                    if ($invoice_details->bp_row3 != "") {
                        $bp_row3_add_zero = number_format_invoice(
                            $invoice_details->bp_row3
                        );
                    } else {
                        $bp_row3_add_zero = "";
                    }
                    $row3 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ab_row3 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->av_row3 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bp_row3_add_zero .
                        "</div>";
                    if ($invoice_details->bq_row4 != "") {
                        $bq_row4_add_zero = number_format_invoice(
                            $invoice_details->bq_row4
                        );
                    } else {
                        $bq_row4_add_zero = "";
                    }
                    $row4 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ac_row4 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->aw_row4 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bq_row4_add_zero .
                        "</div>";
                    if ($invoice_details->br_row5 != "") {
                        $br_row5_add_zero = number_format_invoice(
                            $invoice_details->br_row5
                        );
                    } else {
                        $br_row5_add_zero = "";
                    }
                    $row5 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ad_row5 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->ax_row5 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $br_row5_add_zero .
                        "</div>";
                    if ($invoice_details->bs_row6 != "") {
                        $bs_row6_add_zero = number_format_invoice(
                            $invoice_details->bs_row6
                        );
                    } else {
                        $bs_row6_add_zero = "";
                    }
                    $row6 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ae_row6 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->ay_row6 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bs_row6_add_zero .
                        "</div>";
                    if ($invoice_details->bt_row7 != "") {
                        $bt_row7_add_zero = number_format_invoice(
                            $invoice_details->bt_row7
                        );
                    } else {
                        $bt_row7_add_zero = "";
                    }
                    $row7 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->af_row7 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->az_row7 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bt_row7_add_zero .
                        "</div>";
                    if ($invoice_details->bu_row8 != "") {
                        $bu_row8_add_zero = number_format_invoice(
                            $invoice_details->bu_row8
                        );
                    } else {
                        $bu_row8_add_zero = "";
                    }
                    $row8 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ag_row8 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->ba_row8 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bu_row8_add_zero .
                        "</div>";
                    if ($invoice_details->bv_row9 != "") {
                        $bv_row9_add_zero = number_format_invoice(
                            $invoice_details->bv_row9
                        );
                    } else {
                        $bv_row9_add_zero = "";
                    }
                    $row9 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ah_row9 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->bb_row9 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bv_row9_add_zero .
                        "</div>";
                    if ($invoice_details->bw_row10 != "") {
                        $bw_row10_add_zero = number_format_invoice(
                            $invoice_details->bw_row10
                        );
                    } else {
                        $bw_row10_add_zero = "";
                    }
                    $row10 =
                        '            	
	                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                        '</div>
	                  <div class="class_row_td left_corner">' .
                        $invoice_details->ai_row10 .
                        '</div>
	                  <div class="class_row_td right_start">' .
                        $invoice_details->bc_row10 .
                        '</div>
	                  <div class="class_row_td right">' .
                        $bw_row10_add_zero .
                        "</div>";
                    if ($invoice_details->bx_row11 != "") {
                        $bx_row11_add_zero = number_format_invoice(
                            $invoice_details->bx_row11
                        );
                    } else {
                        $bx_row11_add_zero = "";
                    }
                    $row11 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->aj_row11 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bd_row11 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $bx_row11_add_zero .
                        "</div>";
                    if ($invoice_details->by_row12 != "") {
                        $by_row12_add_zero = number_format_invoice(
                            $invoice_details->by_row12
                        );
                    } else {
                        $by_row12_add_zero = "";
                    }
                    $row12 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->ak_row12 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->be_row12 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $by_row12_add_zero .
                        "</div>";
                    if ($invoice_details->bz_row13 != "") {
                        $bz_row13_add_zero = number_format_invoice(
                            $invoice_details->bz_row13
                        );
                    } else {
                        $bz_row13_add_zero = "";
                    }
                    $row13 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->al_row13 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bf_row13 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $bz_row13_add_zero .
                        "</div>";
                    if ($invoice_details->ca_row14 != "") {
                        $ca_row14_add_zero = number_format_invoice(
                            $invoice_details->ca_row14
                        );
                    } else {
                        $ca_row14_add_zero = "";
                    }
                    $row14 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->am_row14 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bg_row14 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $ca_row14_add_zero .
                        "</div>";
                    if ($invoice_details->cb_row15 != "") {
                        $cb_row15_add_zero = number_format_invoice(
                            $invoice_details->cb_row15
                        );
                    } else {
                        $cb_row15_add_zero = "";
                    }
                    $row15 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->an_row15 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bh_row15 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $cb_row15_add_zero .
                        "</div>";
                    if ($invoice_details->cc_row16 != "") {
                        $cc_row16_add_zero = number_format_invoice(
                            $invoice_details->cc_row16
                        );
                    } else {
                        $cc_row16_add_zero = "";
                    }
                    $row16 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->ao_row16 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bi_row16 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $cc_row16_add_zero .
                        "</div>";
                    if ($invoice_details->cd_row17 != "") {
                        $cd_row17_add_zero = number_format_invoice(
                            $invoice_details->cd_row17
                        );
                    } else {
                        $cd_row17_add_zero = "";
                    }
                    $row17 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->ap_row17 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bj_row17 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $cd_row17_add_zero .
                        "</div>";
                    if ($invoice_details->ce_row18 != "") {
                        $ce_row18_add_zero = number_format_invoice(
                            $invoice_details->ce_row18
                        );
                    } else {
                        $ce_row18_add_zero = "";
                    }
                    $row18 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->aq_row18 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bk_row18 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $ce_row18_add_zero .
                        "</div>";
                    if ($invoice_details->cf_row19 != "") {
                        $cf_row19_add_zero = number_format_invoice(
                            $invoice_details->cf_row19
                        );
                    } else {
                        $cf_row19_add_zero = "";
                    }
                    $row19 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->ar_row19 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bl_row19 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $cf_row19_add_zero .
                        "</div>";
                    if ($invoice_details->cg_row20 != "") {
                        $cg_row20_add_zero = number_format_invoice(
                            $invoice_details->cg_row20
                        );
                    } else {
                        $cg_row20_add_zero = "";
                    }
                    $row20 =
                        '            	
		                  <div class="class_row_td left">' .
                        str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                        '</div>
		                  <div class="class_row_td left_corner">' .
                        $invoice_details->as_row20 .
                        '</div>
		                  <div class="class_row_td right_start">' .
                        $invoice_details->bm_row20 .
                        '</div>
		                  <div class="class_row_td right">' .
                        $cg_row20_add_zero .
                        "</div>";
                    $tax_details =
                        '
			         <div class="tax_table_div">
			            <div class="tax_table">
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees (as agreed)</div>
			                <div class="tax_row_td right">' .
                        number_format_invoice($invoice_details->inv_net) .
                        '</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">VAT @ 23%</div>
			                <div class="tax_row_td right" style="border-top:0px">' .
                        number_format_invoice($invoice_details->vat_value) .
                        '</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left" style="color:#fff">.</div>
			                <div class="tax_row_td right">' .
                        number_format_invoice($invoice_details->gross) .
                        '</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Outlay @ 0%</div>
			                <div class="tax_row_td right" style="border-top:0px;">..</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees Due</div>
			                <div class="tax_row_td right" style="border-bottom: 2px solid #000">' .
                        number_format_invoice($invoice_details->gross) .
                        '</div>
			              </div>
			            </div>
			          </div>
			         ';
                    $companyname = $company_firstname;
                    $taxdetails = $tax_details;
                    $row1 = $row1;
                    $row2 = $row2;
                    $row3 = $row3;
                    $row4 = $row4;
                    $row5 = $row5;
                    $row6 = $row6;
                    $row7 = $row7;
                    $row8 = $row8;
                    $row9 = $row9;
                    $row10 = $row10;
                    $row11 = $row11;
                    $row12 = $row12;
                    $row13 = $row13;
                    $row14 = $row14;
                    $row15 = $row15;
                    $row16 = $row16;
                    $row17 = $row17;
                    $row18 = $row18;
                    $row19 = $row19;
                    $row20 = $row20;
                }
                $output =
                    '<div class="company_details_class">' .
                    $companyname .
                    '</div>
    	            <div class="tax_details_class_maindiv">
    	              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
    	                <div class="class_row class_row1">' .
                        $row1 .
                        '</div>
    	                <div class="class_row class_row2">' .
                        $row2 .
                        '</div>
    	                <div class="class_row class_row3">' .
                        $row3 .
                        '</div>
    	                <div class="class_row class_row4">' .
                        $row4 .
                        '</div>
    	                <div class="class_row class_row5">' .
                        $row5 .
                        '</div>
    	                <div class="class_row class_row6">' .
                        $row6 .
                        '</div>
    	                <div class="class_row class_row7">' .
                        $row7 .
                        '</div>
    	                <div class="class_row class_row8">' .
                        $row8 .
                        '</div>
    	                <div class="class_row class_row9">' .
                        $row9 .
                        '</div>
    	                <div class="class_row class_row10">' .
                        $row10 .
                        '</div>
    	                <div class="class_row class_row11">' .
                        $row11 .
                        '</div>
    	                <div class="class_row class_row12">' .
                        $row12 .
                        '</div>
    	                <div class="class_row class_row13">' .
                        $row13 .
                        '</div>
    	                <div class="class_row class_row14">' .
                        $row14 .
                        '</div>
    	                <div class="class_row class_row15">' .
                        $row15 .
                        '</div>
    	                <div class="class_row class_row16">' .
                        $row16 .
                        '</div>
    	                <div class="class_row class_row17">' .
                        $row17 .
                        '</div>
    	                <div class="class_row class_row18">' .
                        $row18 .
                        '</div>
    	                <div class="class_row class_row19">' .
                        $row19 .
                        '</div>
    	                <div class="class_row class_row20">' .
                        $row20 .
                        '</div>
    	              </div>
    	            </div>
    	            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
    	            <div class="tax_details_class">' .
                        $taxdetails .
                    "</div>";
                $html = $html_main . $output;


                $pdf = PDF::loadHTML($html);
                $zipFileName = time() . "_Invoice of " . $id . ".pdf";
                $pdf->save("public/papers/" . $zipFileName);
                $pdfsname = "<p>" . $zipFileName . "</p>";
            }
        } else {
            $html_main =
                '<style>
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
            $time = time();
            $zipFileName = $time . ".zip";
            $zip = new ZipArchive();
            if (
                $zip->open("public/papers/" . $zipFileName, ZipArchive::CREATE) ===
                true
            ) {
                if (count($ids)) {
                    foreach ($ids as $id) {
                        $invoice_details = \App\Models\InvoiceSystem::where(
                            "invoice_number",
                            $id
                        )->first();
                        $client_details = \App\Models\CMClients::where(
                            "practice_code",
                            Session::get("user_practice_code")
                        )
                            ->where("client_id", $invoice_details->client_id)
                            ->first();
                        if (!$client_details) {
                            $companyname =
                                '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
                            $companyname = $company_firstname;
                            $taxdetails = "";
                            $row1 = "";
                            $row2 = "";
                            $row3 = "";
                            $row4 = "";
                            $row5 = "";
                            $row6 = "";
                            $row7 = "";
                            $row8 = "";
                            $row9 = "";
                            $row10 = "";
                            $row11 = "";
                            $row12 = "";
                            $row13 = "";
                            $row14 = "";
                            $row15 = "";
                            $row16 = "";
                            $row17 = "";
                            $row18 = "";
                            $row19 = "";
                            $row20 = "";
                        } else {
                            $company_firstname =
                                '
			          		<div class="company_details_div">
				              <div class="firstname_div">
				                <b>To:</b><br/>
				                ' .
                                $client_details->firstname .
                                " " .
                                $client_details->surname .
                                '<br/>  
				                ' .
                                $client_details->company .
                                '<br/>  
				                ' .
                                $client_details->address1 .
                                '<br/>  
				                ' .
                                $client_details->address2 .
                                '<br/>
				                ' .
                                $client_details->address3 .
                                '
				              </div>
				            </div>
				            <div class="account_details_div">
				              <div class="account_details_main_address_div">
				              		<div class="aib_account">
						                AIB Account: 48870061<br/>
						                Sort Code: 93-72-23<br/>
						                VAT Number: 9754009E<br/>
						                Company Number: 485123
						            </div>	                 
				                  <div class="account_details_invoice_div">
				                    <div class="account_table">
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Account:</b></div>
				                        <div class="account_row_td right">' .
                                $client_details->client_id .
                                '</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Invoice:</b></div>
				                        <div class="account_row_td right">' .
                                $invoice_details->invoice_number .
                                '</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Date:</b></div>
				                        <div class="account_row_td right">' .
                                date(
                                    "d-M-Y",
                                    strtotime($invoice_details->invoice_date)
                                ) .
                                '</div>
				                      </div>
				                    </div>
				                  </div>
				              </div>
				            </div>
				            <div class="invoice_label">
				              INVOICE
				            </div>';
                            if ($invoice_details->bn_row1 != "") {
                                $bn_row1_add_zero = number_format_invoice(
                                    $invoice_details->bn_row1
                                );
                            } else {
                                $bn_row1_add_zero = "";
                            }
                            $row1 =
                                '
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->f_row1
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->z_row1 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->at_row1 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bn_row1_add_zero .
                                "</div>";
                            if ($invoice_details->bo_row2 != "") {
                                $bo_row2_add_zero = number_format_invoice(
                                    $invoice_details->bo_row2
                                );
                            } else {
                                $bo_row2_add_zero = "";
                            }
                            $row2 =
                                '<div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->g_row2
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->aa_row2 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->au_row2 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bo_row2_add_zero .
                                "</div>";
                            if ($invoice_details->bp_row3 != "") {
                                $bp_row3_add_zero = number_format_invoice(
                                    $invoice_details->bp_row3
                                );
                            } else {
                                $bp_row3_add_zero = "";
                            }
                            $row3 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->h_row3
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ab_row3 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->av_row3 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bp_row3_add_zero .
                                "</div>";
                            if ($invoice_details->bq_row4 != "") {
                                $bq_row4_add_zero = number_format_invoice(
                                    $invoice_details->bq_row4
                                );
                            } else {
                                $bq_row4_add_zero = "";
                            }
                            $row4 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->i_row4
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ac_row4 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->aw_row4 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bq_row4_add_zero .
                                "</div>";
                            if ($invoice_details->br_row5 != "") {
                                $br_row5_add_zero = number_format_invoice(
                                    $invoice_details->br_row5
                                );
                            } else {
                                $br_row5_add_zero = "";
                            }
                            $row5 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->j_row5
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ad_row5 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->ax_row5 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $br_row5_add_zero .
                                "</div>";
                            if ($invoice_details->bs_row6 != "") {
                                $bs_row6_add_zero = number_format_invoice(
                                    $invoice_details->bs_row6
                                );
                            } else {
                                $bs_row6_add_zero = "";
                            }
                            $row6 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->k_row6
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ae_row6 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->ay_row6 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bs_row6_add_zero .
                                "</div>";
                            if ($invoice_details->bt_row7 != "") {
                                $bt_row7_add_zero = number_format_invoice(
                                    $invoice_details->bt_row7
                                );
                            } else {
                                $bt_row7_add_zero = "";
                            }
                            $row7 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->l_row7
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->af_row7 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->az_row7 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bt_row7_add_zero .
                                "</div>";
                            if ($invoice_details->bu_row8 != "") {
                                $bu_row8_add_zero = number_format_invoice(
                                    $invoice_details->bu_row8
                                );
                            } else {
                                $bu_row8_add_zero = "";
                            }
                            $row8 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->m_row8
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ag_row8 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->ba_row8 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bu_row8_add_zero .
                                "</div>";
                            if ($invoice_details->bv_row9 != "") {
                                $bv_row9_add_zero = number_format_invoice(
                                    $invoice_details->bv_row9
                                );
                            } else {
                                $bv_row9_add_zero = "";
                            }
                            $row9 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->n_row9
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ah_row9 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->bb_row9 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bv_row9_add_zero .
                                "</div>";
                            if ($invoice_details->bw_row10 != "") {
                                $bw_row10_add_zero = number_format_invoice(
                                    $invoice_details->bw_row10
                                );
                            } else {
                                $bw_row10_add_zero = "";
                            }
                            $row10 =
                                '            	
			                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->o_row10
                                ) .
                                '</div>
			                  <div class="class_row_td left_corner">' .
                                $invoice_details->ai_row10 .
                                '</div>
			                  <div class="class_row_td right_start">' .
                                $invoice_details->bc_row10 .
                                '</div>
			                  <div class="class_row_td right">' .
                                $bw_row10_add_zero .
                                "</div>";
                            if ($invoice_details->bx_row11 != "") {
                                $bx_row11_add_zero = number_format_invoice(
                                    $invoice_details->bx_row11
                                );
                            } else {
                                $bx_row11_add_zero = "";
                            }
                            $row11 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->p_row11
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->aj_row11 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bd_row11 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $bx_row11_add_zero .
                                "</div>";
                            if ($invoice_details->by_row12 != "") {
                                $by_row12_add_zero = number_format_invoice(
                                    $invoice_details->by_row12
                                );
                            } else {
                                $by_row12_add_zero = "";
                            }
                            $row12 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->q_row12
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->ak_row12 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->be_row12 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $by_row12_add_zero .
                                "</div>";
                            if ($invoice_details->bz_row13 != "") {
                                $bz_row13_add_zero = number_format_invoice(
                                    $invoice_details->bz_row13
                                );
                            } else {
                                $bz_row13_add_zero = "";
                            }
                            $row13 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->r_row13
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->al_row13 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bf_row13 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $bz_row13_add_zero .
                                "</div>";
                            if ($invoice_details->ca_row14 != "") {
                                $ca_row14_add_zero = number_format_invoice(
                                    $invoice_details->ca_row14
                                );
                            } else {
                                $ca_row14_add_zero = "";
                            }
                            $row14 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->s_row14
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->am_row14 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bg_row14 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $ca_row14_add_zero .
                                "</div>";
                            if ($invoice_details->cb_row15 != "") {
                                $cb_row15_add_zero = number_format_invoice(
                                    $invoice_details->cb_row15
                                );
                            } else {
                                $cb_row15_add_zero = "";
                            }
                            $row15 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->t_row15
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->an_row15 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bh_row15 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $cb_row15_add_zero .
                                "</div>";
                            if ($invoice_details->cc_row16 != "") {
                                $cc_row16_add_zero = number_format_invoice(
                                    $invoice_details->cc_row16
                                );
                            } else {
                                $cc_row16_add_zero = "";
                            }
                            $row16 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->u_row16
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->ao_row16 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bi_row16 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $cc_row16_add_zero .
                                "</div>";
                            if ($invoice_details->cd_row17 != "") {
                                $cd_row17_add_zero = number_format_invoice(
                                    $invoice_details->cd_row17
                                );
                            } else {
                                $cd_row17_add_zero = "";
                            }
                            $row17 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->v_row17
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->ap_row17 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bj_row17 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $cd_row17_add_zero .
                                "</div>";
                            if ($invoice_details->ce_row18 != "") {
                                $ce_row18_add_zero = number_format_invoice(
                                    $invoice_details->ce_row18
                                );
                            } else {
                                $ce_row18_add_zero = "";
                            }
                            $row18 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->w_row18
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->aq_row18 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bk_row18 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $ce_row18_add_zero .
                                "</div>";
                            if ($invoice_details->cf_row19 != "") {
                                $cf_row19_add_zero = number_format_invoice(
                                    $invoice_details->cf_row19
                                );
                            } else {
                                $cf_row19_add_zero = "";
                            }
                            $row19 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->x_row19
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->ar_row19 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bl_row19 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $cf_row19_add_zero .
                                "</div>";
                            if ($invoice_details->cg_row20 != "") {
                                $cg_row20_add_zero = number_format_invoice(
                                    $invoice_details->cg_row20
                                );
                            } else {
                                $cg_row20_add_zero = "";
                            }
                            $row20 =
                                '            	
				                  <div class="class_row_td left">' .
                                str_replace(
                                    " ",
                                    "&nbsp;",
                                    $invoice_details->y_row20
                                ) .
                                '</div>
				                  <div class="class_row_td left_corner">' .
                                $invoice_details->as_row20 .
                                '</div>
				                  <div class="class_row_td right_start">' .
                                $invoice_details->bm_row20 .
                                '</div>
				                  <div class="class_row_td right">' .
                                $cg_row20_add_zero .
                                "</div>";
                            $tax_details =
                                '
					         <div class="tax_table_div">
					            <div class="tax_table">
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees (as agreed)</div>
					                <div class="tax_row_td right" width="13%">' .
                                number_format_invoice(
                                    $invoice_details->inv_net
                                ) .
                                '</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">VAT @ 23%</div>
					                <div class="tax_row_td right" style="border-top:0px;">' .
                                number_format_invoice(
                                    $invoice_details->vat_value
                                ) .
                                '</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left" style="color:#fff">.</div>
					                <div class="tax_row_td right">' .
                                number_format_invoice($invoice_details->gross) .
                                '</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Outlay @ 0%</div>
					                <div class="tax_row_td right" style="border-top:0px;">..</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees Due</div>
					                <div class="tax_row_td right" style="border-bottom: 2px solid #000">' .
                                number_format_invoice($invoice_details->gross) .
                                '</div>
					              </div>
					            </div>
					          </div>
					         ';
                            $companyname = $company_firstname;
                            $taxdetails = $tax_details;
                            $row1 = $row1;
                            $row2 = $row2;
                            $row3 = $row3;
                            $row4 = $row4;
                            $row5 = $row5;
                            $row6 = $row6;
                            $row7 = $row7;
                            $row8 = $row8;
                            $row9 = $row9;
                            $row10 = $row10;
                            $row11 = $row11;
                            $row12 = $row12;
                            $row13 = $row13;
                            $row14 = $row14;
                            $row15 = $row15;
                            $row16 = $row16;
                            $row17 = $row17;
                            $row18 = $row18;
                            $row19 = $row19;
                            $row20 = $row20;
                        }
                        $output =
                            '<div class="company_details_class">' .
                            $companyname .
                            '</div>
			            <div class="tax_details_class_maindiv">
			              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
			                <div class="class_row class_row1">' .
                            $row1 .
                            '</div>
			                <div class="class_row class_row2">' .
                            $row2 .
                            '</div>
			                <div class="class_row class_row3">' .
                            $row3 .
                            '</div>
			                <div class="class_row class_row4">' .
                            $row4 .
                            '</div>
			                <div class="class_row class_row5">' .
                            $row5 .
                            '</div>
			                <div class="class_row class_row6">' .
                            $row6 .
                            '</div>
			                <div class="class_row class_row7">' .
                            $row7 .
                            '</div>
			                <div class="class_row class_row8">' .
                            $row8 .
                            '</div>
			                <div class="class_row class_row9">' .
                            $row9 .
                            '</div>
			                <div class="class_row class_row10">' .
                            $row10 .
                            '</div>
			                <div class="class_row class_row11">' .
                            $row11 .
                            '</div>
			                <div class="class_row class_row12">' .
                            $row12 .
                            '</div>
			                <div class="class_row class_row13">' .
                            $row13 .
                            '</div>
			                <div class="class_row class_row14">' .
                            $row14 .
                            '</div>
			                <div class="class_row class_row15">' .
                            $row15 .
                            '</div>
			                <div class="class_row class_row16">' .
                            $row16 .
                            '</div>
			                <div class="class_row class_row17">' .
                            $row17 .
                            '</div>
			                <div class="class_row class_row18">' .
                            $row18 .
                            '</div>
			                <div class="class_row class_row19">' .
                            $row19 .
                            '</div>
			                <div class="class_row class_row20">' .
                            $row20 .
                            '</div>
			              </div>
			            </div>
			            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
			            <div class="tax_details_class">' .
                            $taxdetails .
                            "</div>";
                        $html = $html_main . $output;
                        $pdf = PDF::loadHTML($html);
                        $pdf->save("public/papers/Invoice of " . $id . ".pdf");
                        $zip->addFile(
                            "public/papers/Invoice of " . $id . ".pdf",
                            "Invoice of " . $id . ".pdf"
                        );
                        if ($pdfsname == "") {
                            $pdfsname = "<p>Invoice of " . $id . ".pdf</p>";
                        } else {
                            $pdfsname =
                                $pdfsname . "<p>Invoice of " . $id . ".pdf</p>";
                        }
                    }
                }
            }
            $zip->close();
        }
        echo json_encode(["zipfile" => $zipFileName, "pdfs" => $pdfsname]);
    }
    public function client_review_email_selected_pdf(Request $request)
    {
        $from_input = $request->get("from_user_to_client");
        $attachment = $request->get("hidden_attachment");
        $client_id = $request->get("hidden_client_id");
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
        if (count($explode)) {
            foreach ($explode as $exp) {
                $to = trim($exp);
                $data["logo"] = getEmailLogo('car');
                $data["message"] = $message;
                $contentmessage = view("user/email_share_paper_crm", $data);
                $email = new PHPMailer();
                $email->SetFrom($from, $user_name); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                $email->AddAttachment("public/papers/" . $attachment, $attachment);
                $email->Send();
            }
            $client_details = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $client_id)
                ->first();
            $datamessage["message_id"] = time();
            $datamessage["message_from"] = $from_input;
            $datamessage["subject"] = $subject;
            $datamessage["message"] = $message;
            $datamessage["client_ids"] = $client_id;
            $datamessage["primary_emails"] = $client_details->email;
            $datamessage["secondary_emails"] = $client_details->email2;
            $datamessage["date_sent"] = date("Y-m-d H:i:s");
            $datamessage["date_saved"] = date("Y-m-d H:i:s");
            $datamessage["source"] = "Invoice System";
            $datamessage["attachments"] = "public/papers/" . $attachment;
            $datamessage["status"] = 1;
            $datamessage['practice_code'] = Session::get('user_practice_code');
            \App\Models\Messageus::insert($datamessage);
            //return Redirect::back()->with('message', 'Email Sent Successfully for Client.');
        } else {
            return Redirect::back()->with(
                "error",
                "Email Field is empty so email is not sent"
            );
        }
    }
    public function get_client_account_review_listing(Request $request)
    {
        $client_id = $request->get("client_id");
        $opening_details = \App\Models\FinanceClients::where(
            "client_id",
            $client_id
        )->first();
        $formatted_bal = "0.00";
        $opening_bal = "0.00";
        if ($opening_details) {
            if ($opening_details->balance != "") {
                $opening_bal = $opening_details->balance;
                $formatted_bal = number_format_invoice(
                    $opening_details->balance
                );
            }
        }
        $opening_bal_date_details =\App\Models\userLogin::first();
        $opening_bal_date = "";
        if ($opening_bal_date_details->opening_balance_date != "") {
            $opening_bal_date = date(
                "d-M-Y",
                strtotime($opening_bal_date_details->opening_balance_date)
            );
        }
        if ($opening_bal < 0) {
            $color = "#f00";
            $textval = "Client is Owed";
        } elseif ($opening_bal > 0) {
            $color = "green";
            $textval = "Client Owes Back";
        } else {
            $color = "green";
            $textval = "Opening Balance";
        }
        $output =
            '<tr>
			<td>' .
            $opening_bal_date .
            '</td>
			<td>Opening Balance</td>
			<td style="color:' .
            $color .
            '">' .
            $textval .
            '</td>
			<td style="color:' .
            $color .
            '">' .
            $formatted_bal .
            '</td>
			<td style="color:' .
            $color .
            '">' .
            $formatted_bal .
            '</td>
		</tr>';
        $get_receipts = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_payments = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_receipt_payments = array_merge($get_receipts, $get_payments);
        $dateval = [];
        foreach ($get_receipt_payments as $key => $row) {
            $dateval[$key] = $row->dateval;
        }
        array_multisort($dateval, SORT_ASC, $get_receipt_payments);
        $balance_val = $opening_bal;
        if (count($get_receipt_payments)) {
            foreach ($get_receipt_payments as $list) {
                if (isset($list->payments_id)) {
                    $source = "Payments";
                    $colorval = "color:blue";
                    $textvalue = "Payment Made Back to Client";
                    $amount = number_format_invoice($list->amount);
                    $amt = $list->amount;
                    $balance_val = $balance_val + $list->amount;
                    $class = "payment_viewer_class";
                    $id = $list->payments_id;
                } else {
                    $source = "Receipts";
                    if (
                        $list->amount != "0" &&
                        $list->amount != "0.00" &&
                        $list->amount != ""
                    ) {
                        $colorval = "";
                        $amount = number_format_invoice($list->amount * -1);
                        $amt = $list->amount * -1;
                        $textvalue = "Client Money Received";
                        $balance_val = $balance_val + $list->amount * -1;
                    } else {
                        $colorval = "";
                        $amount = number_format_invoice($list->amount);
                        $amt = $list->amount;
                        $textvalue = "";
                        $balance_val = $balance_val + $list->amount;
                    }
                    $id = $list->id;
                    $class = "receipt_viewer_class";
                }
                if ($balance_val < 0) {
                    $bal_color = "color:#f00";
                } else {
                    $bal_color = "color:blue";
                }
                if ($amt < 0) {
                    $amt_color = "color:#f00";
                    $colorval = "color:#f00";
                } else {
                    $amt_color = "color:blue";
                    $colorval = "color:blue";
                }
                $output .=
                    '<tr>
					<td>' .
                    date("d-M-Y", $list->dateval) .
                    '</td>
					<td><a href="javascript:" class="' .
                    $class .
                    '" data-element="' .
                    $id .
                    '">' .
                    $source .
                    '</a></td>
					<td style="' .
                    $colorval .
                    '">' .
                    $textvalue .
                    '</td>
					<td style="' .
                    $amt_color .
                    '">' .
                    $amount .
                    '</td>
					<td style="' .
                    $bal_color .
                    '">' .
                    number_format_invoice($balance_val) .
                    '</td>
				</tr>';
            }
        }
        echo json_encode([
            "output" => $output,
            "opening_balance" => $formatted_bal,
        ]);
    }
    public function export_client_account_review_listing(Request $request)
    {
        $client_id = $request->get("client_id");
        $columns = ["Date", "Source", "Description", "Amount", "Balance"];
        $filename = time() . "_" . $client_id . " Client Account listing.csv";
        $file = fopen("public/papers/" . $filename, "w");
        fputcsv($file, $columns);
        $opening_details = \App\Models\FinanceClients::where(
            "client_id",
            $client_id
        )->first();
        $formatted_bal = "0.00";
        $opening_bal = "0.00";
        if ($opening_details) {
            if ($opening_details->balance != "") {
                $opening_bal = $opening_details->balance;
                $formatted_bal = number_format_invoice(
                    $opening_details->balance
                );
            }
        }
        $opening_bal_date_details =\App\Models\userLogin::first();
        $opening_bal_date = "";
        if ($opening_bal_date_details->opening_balance_date != "") {
            $opening_bal_date = date(
                "d-M-Y",
                strtotime($opening_bal_date_details->opening_balance_date)
            );
        }
        if ($opening_bal < 0) {
            $textval = "Client is Owed";
        } elseif ($opening_bal > 0) {
            $textval = "Client Owes Back";
        } else {
            $textval = "Opening Balance";
        }
        $columns_2 = [
            $opening_bal_date,
            "Opening Balance",
            $textval,
            $formatted_bal,
            $formatted_bal,
        ];
        fputcsv($file, $columns_2);
        $get_receipts = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_payments = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_receipt_payments = array_merge($get_receipts, $get_payments);
        $dateval = [];
        foreach ($get_receipt_payments as $key => $row) {
            $dateval[$key] = $row->dateval;
        }
        array_multisort($dateval, SORT_ASC, $get_receipt_payments);
        $balance_val = $opening_bal;
        if (count($get_receipt_payments)) {
            foreach ($get_receipt_payments as $list) {
                if (isset($list->payments_id)) {
                    $source = "Payments";
                    $textvalue = "Payment Made Back to Client";
                    $amount = number_format_invoice($list->amount);
                    $balance_val = $balance_val - $list->amount;
                } else {
                    $source = "Receipts";
                    if (
                        $list->amount != "0" &&
                        $list->amount != "0.00" &&
                        $list->amount != ""
                    ) {
                        $amount = number_format_invoice($list->amount * -1);
                        $textvalue = "Client Money Received";
                        $balance_val = $balance_val + $list->amount * -1;
                    } else {
                        $amount = number_format_invoice($list->amount * -1);
                        $textvalue = "";
                        $balance_val = $balance_val + $list->amount * -1;
                    }
                }
                $columns_3 = [
                    date("d-M-Y", $list->dateval),
                    $source,
                    $textvalue,
                    $amount,
                    number_format_invoice($balance_val),
                ];
                fputcsv($file, $columns_3);
            }
        }
        fclose($file);
        echo $filename;
    }
    public function get_transaction_review_listing(Request $request)
    {
        $client_id = $request->get("client_id");
        $opening_details = \App\Models\OpeningBalance::where(
            "client_id",
            $client_id
        )->first();
        $formatted_bal = "0.00";
        $opening_bal = "0.00";
        if ($opening_details) {
            if ($opening_details->opening_balance != "") {
                $opening_bal = $opening_details->opening_balance;
                $formatted_bal = number_format_invoice(
                    $opening_details->opening_balance
                );
            }
        }
        $opening_bal_date_details =\App\Models\userLogin::first();
        $opening_bal_date = "";
        if ($opening_bal_date_details->opening_balance_date != "") {
            $opening_bal_date = date(
                "d-M-Y",
                strtotime($opening_bal_date_details->opening_balance_date)
            );
        }
        $output =
            '<tr>
			<td>' .
            $opening_bal_date .
            '</td>
			<td>Opening Balance</td>
			<td></td>
			<td>' .
            $formatted_bal .
            '</td>
			<td>' .
            $formatted_bal .
            '</td>
		</tr>';
        $get_receipts = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "712" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_invoice = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`invoice_date`) as dateval from `invoice_system` WHERE `invoice_date` > "' .
                $opening_bal_date_details->opening_balance_date .
                '" AND client_id = "' .
                $client_id .
                '"'
        );
        $get_invoice_receipts = array_merge($get_receipts, $get_invoice);
        $dateval = [];
        foreach ($get_invoice_receipts as $key => $row) {
            $dateval[$key] = $row->dateval;
        }
        array_multisort($dateval, SORT_ASC, $get_invoice_receipts);
        $balance_val = $opening_bal;
        if (count($get_invoice_receipts)) {
            foreach ($get_invoice_receipts as $list) {
                if (isset($list->invoice_number)) {
                    $source = "Invoice";
                    $textvalue = "Invoice - " . $list->invoice_number;
                    $amount = number_format_invoice($list->gross);
                    $balance_val = $balance_val + $list->gross;
                } else {
                    $source = "Receipts";
                    $textvalue =
                        'Payment Received <a href="javascript:" data-toggle="popover" title="Comment" data-content="' .
                        $list->comment .
                        '" data-trigger="focus">...</a>';
                    $amount = number_format_invoice($list->amount);
                    $balance_val = $balance_val - $list->amount;
                }
                $output .=
                    '<tr>
					<td>' .
                    date("d-M-Y", $list->dateval) .
                    '</td>
					<td>' .
                    $source .
                    '</td>
					<td>' .
                    $textvalue .
                    '</td>
					<td>' .
                    $amount .
                    '</td>
					<td>' .
                    number_format_invoice($balance_val) .
                    '</td>
				</tr>';
            }
        }
        echo json_encode([
            "output" => $output,
            "opening_balance" => $formatted_bal,
        ]);
    }
    public function export_transaction_review_listing(Request $request)
    {
        $client_id = $request->get("client_id");
        $columns = ["Date", "Source", "Description", "Amount", "Balance"];
        $filename = time() . "_" . $client_id . " Transaction listing.csv";
        $file = fopen("public/papers/" . $filename, "w");
        fputcsv($file, $columns);
        $opening_details = \App\Models\OpeningBalance::where(
            "client_id",
            $client_id
        )->first();
        $formatted_bal = "0.00";
        $opening_bal = "0.00";
        if ($opening_details) {
            if ($opening_details->opening_balance != "") {
                $opening_bal = $opening_details->opening_balance;
                $formatted_bal = number_format_invoice(
                    $opening_details->opening_balance
                );
            }
        }
        $opening_bal_date_details =\App\Models\userLogin::first();
        $opening_bal_date = "";
        if ($opening_bal_date_details->opening_balance_date != "") {
            $opening_bal_date = date(
                "d-M-Y",
                strtotime($opening_bal_date_details->opening_balance_date)
            );
        }
        $columns_2 = [
            $opening_bal_date,
            "Opening Balance",
            "",
            $formatted_bal,
            $formatted_bal,
        ];
        fputcsv($file, $columns_2);
        $get_receipts = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "712" AND client_code = "' .
                $client_id .
                '"'
        );
        $get_invoice = DB::select(
            'SELECT *,UNIX_TIMESTAMP(`invoice_date`) as dateval from `invoice_system` WHERE `invoice_date` > "' .
                $opening_bal_date_details->opening_balance_date .
                '" AND client_id = "' .
                $client_id .
                '"'
        );
        $get_invoice_receipts = array_merge($get_receipts, $get_invoice);
        $dateval = [];
        foreach ($get_invoice_receipts as $key => $row) {
            $dateval[$key] = $row->dateval;
        }
        array_multisort($dateval, SORT_ASC, $get_invoice_receipts);
        $balance_val = $opening_bal;
        if (count($get_invoice_receipts)) {
            foreach ($get_invoice_receipts as $list) {
                if (isset($list->invoice_number)) {
                    $source = "Invoice";
                    $textvalue = "Invoice - " . $list->invoice_number;
                    $amount = number_format_invoice($list->gross);
                    $balance_val = $balance_val + $list->gross;
                } else {
                    $source = "Receipts";
                    $textvalue =
                        'Payment Received <a href="javascript:" data-toggle="popover" title="Comment" data-content="' .
                        $list->comment .
                        '" data-trigger="focus">...</a>';
                    $amount = number_format_invoice($list->amount);
                    $balance_val = $balance_val - $list->amount;
                }
                $columns_3 = [
                    date("d-M-Y", $list->dateval),
                    $source,
                    $textvalue,
                    $amount,
                    number_format_invoice($balance_val),
                ];
                fputcsv($file, $columns_3);
            }
        }
        fclose($file);
        echo $filename;
    }
    public function load_single_client_receipt_payment(Request $request)
    {
        $id = $request->get("id");
        $type = $request->get("type");
        if ($type == 0) {
            $receipt = \App\Models\receipts::where("id", $id)->first();
            $output = '<thead>
				<tr>
					<th>Date</th>
					<th>Debit Nominal & Description</th>
					<th>Credit Nominal</th>
					<th>Client Code</th>
					<th>Credit Nominal Description </th>
					<th>Comment</th>
					<th style="text-align:right;">Amount</th>
					<th>Journal ID</th>
					<th>Status</th>
				</tr>
			</thead>';
            if ($receipt) {
                $get_details = receipt\App\Models\NominalCodes::where(
                    "code",
                    $receipt->debit_nominal
                )->first();
                $debit_nominal = "";
                if ($get_details) {
                    $debit_nominal =
                        $get_details->code . " - " . $get_details->description;
                }
                if ($receipt->hold_status == 0) {
                    $hold_status =
                        '<a href="javascript:" class="change_to_unhold" data-element="' .
                        $receipt->id .
                        '" data-nominal="' .
                        $get_details->code .
                        '">Outstanding</a>';
                } elseif ($receipt->hold_status == 2) {
                    $hold_status =
                        '<a href="javascript:" class="change_to_unhold" data-element="' .
                        $receipt->id .
                        '" data-nominal="' .
                        $get_details->code .
                        '">Reconciled</a>';
                } else {
                    $hold_status =
                        '<a href="javascript:" class="unhold_receipt" data-element="' .
                        $receipt->id .
                        '">Cleared</a>';
                }
                $output .=
                    '<tbody>
				<tr>
					<td><spam class="date_sort_val" style="display:none">' .
                    strtotime($receipt->receipt_date) .
                    "</spam>" .
                    date("d/m/Y", strtotime($receipt->receipt_date)) .
                    '</td>
					<td class="debit_sort_val">' .
                    $debit_nominal .
                    '</td>
					<td class="credit_sort_val">' .
                    $receipt->credit_nominal .
                    '</td>
					<td class="client_sort_val">' .
                    $receipt->client_code .
                    '</td>	
					<td class="des_sort_val">' .
                    $receipt->credit_description .
                    '</td>
					<td class="comment_sort_val">' .
                    $receipt->comment .
                    '</td>	
					<td style="text-align:right"><spam class="amount_sort_val" style="display:none">' .
                    $receipt->amount .
                    "</spam>" .
                    number_format_invoice_empty($receipt->amount) .
                    '</td>	
					<td>' .
                    $receipt->journal_id .
                    '</td>	
					<td>' .
                    $hold_status .
                    '</td>						
				</tr></tbody>
				';
            }
            $page_title = "Receipts";
        } else {
            $payment = \App\Models\payments::where("payments_id", $id)->first();
            $output = '<thead>
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
            if ($payment) {
                $get_details = payment\App\Models\NominalCodes::where(
                    "code",
                    $payment->credit_nominal
                )->first();
                $credit_nominal = "";
                if ($get_details) {
                    $credit_nominal =
                        $get_details->code . " - " . $get_details->description;
                }
                if ($payment->hold_status == 0) {
                    $hold_status =
                        '<a href="javascript:" class="change_to_unhold" data-element="' .
                        $payment->payments_id .
                        '" data-nominal="' .
                        $get_details->code .
                        '">Outstanding</a>';
                } elseif ($payment->hold_status == 2) {
                    $hold_status =
                        '<a href="javascript:" class="change_to_unhold" data-element="' .
                        $payment->payments_id .
                        '" data-nominal="' .
                        $get_details->code .
                        '">Reconciled</a>';
                } else {
                    $hold_status =
                        '<a href="javascript:" class="unhold_payment" data-element="' .
                        $payment->payments_id .
                        '">Cleared</a>';
                }
                $output .=
                    '<tr>
					<td><spam class="date_sort_val" style="display:none">' .
                    strtotime($payment->payment_date) .
                    "</spam>" .
                    date("d/m/Y", strtotime($payment->payment_date)) .
                    '</td>
					<td class="debit_sort_val">' .
                    $payment->debit_nominal .
                    '</td>
					<td class="credit_sort_val">' .
                    $credit_nominal .
                    "</td>";
                if ($payment->debit_nominal == "813") {
                    $supplier_details = \App\Models\suppliers::where(
                        "id",
                        $payment->supplier_code
                    )->first();
                    $output .=
                        '<td class="client_sort_val">' .
                        $supplier_details->supplier_code .
                        "</td>";
                } else {
                    $output .=
                        '<td class="client_sort_val">' .
                        $payment->client_code .
                        "</td>";
                }
                $output .=
                    '<td class="des_sort_val">' .
                    $payment->debit_description .
                    '</td>
					<td class="comment_sort_val">' .
                    $payment->comment .
                    '</td>	
					<td style="text-align:right"><spam class="amount_sort_val" style="display:none">' .
                    $payment->amount .
                    "</spam>" .
                    number_format_invoice_empty($payment->amount) .
                    '</td>	
					<td style=";text-align:right">' .
                    $payment->journal_id .
                    '</td>	
					<td>' .
                    $hold_status .
                    '</td>	
				</tr>';
            }
            $page_title = "Payments";
        }
        echo json_encode(["page_title" => $page_title, "output" => $output]);
    }
    public function invoice_export_selected_csvs(Request $request)
    {
        $ids = explode(",", $request->get("ids"));
        $columns = ["S.No", "Invoice", "Date", "Net", "Vat", "Gross"];
        $filename = "Client Account Review Invoice List.csv";
        $time = time();
        $upload_dir = "public/papers/" . $time;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $file = fopen($upload_dir . "/" . $filename, "w");
        fputcsv($file, $columns);
        $i = 1;
        if (count($ids)) {
            foreach ($ids as $id) {
                $invoice_details = \App\Models\InvoiceSystem::where(
                    "invoice_number",
                    $id
                )->first();
                $columns1 = [
                    $i,
                    $id,
                    date("d-M-Y", strtotime($invoice_details->invoice_date)),
                    number_format_invoice($invoice_details->inv_net),
                    number_format_invoice($invoice_details->vat_value),
                    number_format_invoice($invoice_details->gross),
                ];
                fputcsv($file, $columns1);
                $i++;
            }
        }
        fclose($file);
        echo json_encode(["timefolder" => $time, "filename" => $filename]);
    }
    public function edit_car_header_image(Request $request) {
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

                    DB::table('clientaccountreview_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
    }
    public function save_car_settings(Request $request) {
        $cc_email = $request->get('client_account_cc_email');
        $signature = $request->get('email_signature');
        $data['client_account_cc_email'] = $cc_email;
        $data['email_signature'] = $signature;

        $check_settings = DB::table('clientaccountreview_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        if($check_settings) {
              DB::table('clientaccountreview_settings')->where('id',$check_settings->id)->update($data);
        }
        else{
              $data['practice_code'] = Session::get('user_practice_code');
              DB::table('clientaccountreview_settings')->insert($data);
        }
        return redirect::back()->with('message', 'Client Account Settings Saved Sucessfully.');
    }
}
