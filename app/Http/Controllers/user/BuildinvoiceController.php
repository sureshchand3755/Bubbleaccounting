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
class BuildinvoiceController extends Controller
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
    public function __construct()
    {
        $this->middleware("userauth");
        date_default_timezone_set("Europe/Dublin");
        require_once app_path("Http/helpers.php");
    }
    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function build_invoice(Request $request)
    {
        return view("user/build_invoice/build_invoice", [
            "title" => "Build Invoice",
        ]);
    }
    public function build_invoice_client_select(Request $request)
    {
        $client_id = $request->get("client_id");
        $cm_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        if (($cm_details)) {
            $client_details =
                '
      <div class="col-md-1" style="padding:0px">
        <h5 style="font-weight: 600">Client Name:</h5>
      </div>
      <div class="col-md-10" style="padding:0px">
        <h5 style="font-weight: 600">' .
                $cm_details->company .
                '</h5>
      </div>
      <div class="col-md-1" style="padding:0px">
        <h5 style="font-weight: 600">Client Code:</h5>
      </div>
      <div class="col-md-10" style="padding:0px">
        <h5 style="font-weight: 600">' .
                $client_id .
                '</h5>
      </div>
      <div class="col-md-1" style="padding:0px">
        <h5 style="font-weight: 600">Address:</h5>
      </div>
      <div class="col-md-10" style="padding:0px">
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
      <div class="col-md-1" style="padding:0px">
        <h5 style="font-weight: 600">Email:</h5>
      </div>
      <div class="col-md-10" style="padding:0px">
        <h5 style="font-weight: 600">' .
                $cm_details->email .
                '</h5>
      </div>';
            $data["client_details"] = $client_details;
            $data["client_email"] = $cm_details->email;
            $data["client_email2"] = $cm_details->email2;
            $data["company"] = $cm_details->company;
            $invoices = \App\Models\InvoiceSystem::where("client_id", $client_id)
                ->orderBy("invoice_number", "desc")
                ->get();
            $inv_output = '<table class="table" id="invoice_build_expand">
      <thead>
        <th>Inv No</th>
        <th>Inv Date</th>
        <th style="text-align:right">Net</th>
        <th style="text-align:right">VAT</th>
        <th style="text-align:right">Gross</th>
      </thead>
      <tbody id="issued_invoice_tbody">';
            if (count($invoices) > 0) {
                foreach ($invoices as $invoice) {
                    $inv_output .=
                        '<tr>
            <td class="invoice_td" data-element="' .
                        $invoice->invoice_number .
                        '">' .
                        $invoice->invoice_number .
                        '</td>
            <td class="invoice_td" data-element="' .
                        $invoice->invoice_number .
                        '"><spam style="display:none">' .
                        strtotime($invoice->invoice_date) .
                        "</spam>" .
                        date("d-M-Y", strtotime($invoice->invoice_date)) .
                        '</td>
            <td class="invoice_td" data-element="' .
                        $invoice->invoice_number .
                        '" style="text-align:right"><spam style="display:none">' .
                        $invoice->inv_net .
                        "</spam>" .
                        number_format_invoice($invoice->inv_net) .
                        '</td>
            <td class="invoice_td" data-element="' .
                        $invoice->invoice_number .
                        '" style="text-align:right"><spam style="display:none">' .
                        $invoice->vat_rate .
                        "</spam>" .
                        $invoice->vat_rate .
                        '%</td>
            <td class="invoice_td" data-element="' .
                        $invoice->invoice_number .
                        '" style="text-align:right"><spam style="display:none">' .
                        $invoice->gross .
                        "</spam>" .
                        number_format_invoice($invoice->gross) .
                        '</td>
          </tr>';
                }
            }
            $inv_output .= '</tbody>
      </table>';
            $data["invoices"] = $inv_output;
            echo json_encode($data);
        }
    }
    public function build_issued_invoice_lines(Request $request)
    {
        $inv_no = $request->get("inv_no");
        $invoice_details = \App\Models\InvoiceSystem::where(
            "invoice_number",
            $inv_no
        )->first();
        $client_details = \App\Models\CMClients::where(
            "client_id",
            $invoice_details->client_id
        )->first();
        $settings_details = \App\Models\settings::where("source", "build_invoice")
            ->where("practice_code", Session::get("user_practice_code"))
            ->first();
        if ($settings_details) {
            $settings = unserialize($settings_details->settings);
            $offset_lines =
                $settings["offset_lines"] != "" ? $settings["offset_lines"] : 6;
            $plus_invoice_heading =
                $settings["plus_invoice_heading"] != ""
                    ? $settings["plus_invoice_heading"]
                    : "Invoice";
            $minus_invoice_heading =
                $settings["minus_invoice_heading"] != ""
                    ? $settings["minus_invoice_heading"]
                    : "Credit Note";
            $suboffset_lines =
                $settings["suboffset_lines"] != ""
                    ? $settings["suboffset_lines"]
                    : 7;
            $iban_field =
                $settings["iban_field"] != ""
                    ? $settings["iban_field"]
                    : "5465623131";
            $bic_field =
                $settings["bic_field"] != ""
                    ? $settings["bic_field"]
                    : "545JHJDJ4544";
            $first_invoice_number =
                $settings["first_invoice_number"] != ""
                    ? $settings["first_invoice_number"]
                    : "";
            $left_margin =
                $settings["left_margin"] != ""
                    ? $settings["left_margin"]
                    : "95";
            $top_margin =
                $settings["top_margin"] != "" ? $settings["top_margin"] : "200";
            $inv_to_text =
                $settings["inv_to_text"] != ""
                    ? $settings["inv_to_text"]
                    : "To";
            $inv_date_location_text =
                $settings["inv_date_location_text"] != ""
                    ? $settings["inv_date_location_text"]
                    : "Date";
            $inv_iban_text =
                $settings["inv_iban_text"] != ""
                    ? $settings["inv_iban_text"]
                    : "IBAN";
            $inv_bic_text =
                $settings["inv_bic_text"] != ""
                    ? $settings["inv_bic_text"]
                    : "BIC";
            $inv_no_text =
                $settings["inv_no_text"] != ""
                    ? $settings["inv_no_text"]
                    : "Invoice";
            $client_id_text =
                $settings["client_id_text"] != ""
                    ? $settings["client_id_text"]
                    : "Client ID";
            $net_text =
                $settings["net_text"] != "" ? $settings["net_text"] : "Net";
            $vat_text =
                $settings["vat_text"] != "" ? $settings["vat_text"] : "VAT";
            $gross_text =
                $settings["gross_text"] != ""
                    ? $settings["gross_text"]
                    : "Gross";
            $inv_to_offset =
                $settings["inv_to_offset"] != ""
                    ? $settings["inv_to_offset"]
                    : 1;
            $client_id_offset =
                $settings["client_id_offset"] != ""
                    ? $settings["client_id_offset"]
                    : 1;
            $inv_no_offset =
                $settings["inv_no_offset"] != ""
                    ? $settings["inv_no_offset"]
                    : 2;
            $inv_date_location_offset =
                $settings["inv_date_location_offset"] != ""
                    ? $settings["inv_date_location_offset"]
                    : 3;
            $inv_iban_offset =
                $settings["inv_iban_offset"] != ""
                    ? $settings["inv_iban_offset"]
                    : 4;
            $inv_bic_offset =
                $settings["inv_bic_offset"] != ""
                    ? $settings["inv_bic_offset"]
                    : 5;
            $net_offset =
                $settings["net_offset"] != "" ? $settings["net_offset"] : 32;
            $vat_offset =
                $settings["vat_offset"] != "" ? $settings["vat_offset"] : 33;
            $gross_offset =
                $settings["gross_offset"] != ""
                    ? $settings["gross_offset"]
                    : 34;
            $inv_to_left_offset =
                $settings["inv_to_left_offset"] != ""
                    ? $settings["inv_to_left_offset"]
                    : 1;
            $client_id_left_offset =
                $settings["client_id_left_offset"] != ""
                    ? $settings["client_id_left_offset"]
                    : 4;
            $inv_no_left_offset =
                $settings["inv_no_left_offset"] != ""
                    ? $settings["inv_no_left_offset"]
                    : 4;
            $inv_date_location_left_offset =
                $settings["inv_date_location_left_offset"] != ""
                    ? $settings["inv_date_location_left_offset"]
                    : 4;
            $inv_iban_left_offset =
                $settings["inv_iban_left_offset"] != ""
                    ? $settings["inv_iban_left_offset"]
                    : 4;
            $inv_bic_left_offset =
                $settings["inv_bic_left_offset"] != ""
                    ? $settings["inv_bic_left_offset"]
                    : 4;
            $net_left_offset =
                $settings["net_left_offset"] != ""
                    ? $settings["net_left_offset"]
                    : 4;
            $vat_left_offset =
                $settings["vat_left_offset"] != ""
                    ? $settings["vat_left_offset"]
                    : 4;
            $gross_left_offset =
                $settings["gross_left_offset"] != ""
                    ? $settings["gross_left_offset"]
                    : 4;
        } else {
            $offset_lines = 6;
            $plus_invoice_heading = "Invoice";
            $minus_invoice_heading = "Credit Note";
            $suboffset_lines = 7;
            $iban_field = "5465623131";
            $bic_field = "545JHJDJ4544";
            $first_invoice_number = "";
            $left_margin = "95";
            $top_margin = "200";
            $inv_to_text = "To";
            $inv_date_location_text = "Date";
            $inv_iban_text = "IBAN";
            $inv_bic_text = "BIC";
            $inv_no_text = "Invoice";
            $client_id_text = "Client ID";
            $net_text = "Net";
            $vat_text = "VAT";
            $gross_text = "Gross";
            $inv_to_offset = 1;
            $client_id_offset = 1;
            $inv_no_offset = 2;
            $inv_date_location_offset = 3;
            $inv_iban_offset = 4;
            $inv_bic_offset = 5;
            $net_offset = 32;
            $vat_offset = 33;
            $gross_offset = 34;
            $inv_to_left_offset = 1;
            $client_id_left_offset = 4;
            $inv_no_left_offset = 4;
            $inv_date_location_left_offset = 4;
            $inv_iban_left_offset = 4;
            $inv_bic_left_offset = 4;
            $net_left_offset = 4;
            $vat_left_offset = 4;
            $gross_left_offset = 4;
        }
        $total_table_width = 900 - $left_margin * 2;
        $display_output =
            '<table class="display_table_expand" style="width:' .
            $total_table_width .
            "px; margin-left:" .
            $left_margin .
            "px; margin-top:" .
            $top_margin .
            'px; ">';
        for ($j = 1; $j <= $offset_lines - 1; $j++) {
            $display_output .= "<tr>";
            if ($inv_to_offset == $j) {
                if ($inv_to_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_to_title">' .
                        $inv_to_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        "</spam></td>";
                    $j1 = $j + 1;
                    $j2 = $j + 2;
                    $j3 = $j + 3;
                    $j4 = $j + 4;
                    ${"row" . $j1 . "_col1"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->company .
                        "</spam></td>";
                    ${"row" . $j2 . "_col1"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address1 .
                        "</spam></td>";
                    ${"row" . $j3 . "_col1"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address2 .
                        "</spam></td>";
                    ${"row" . $j4 . "_col1"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address3 .
                        "</spam></td>";
                } elseif ($inv_to_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:50%"><label class="header_info_to_title">' .
                        $inv_to_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        "</spam></td>";
                    $j1 = $j + 1;
                    $j2 = $j + 2;
                    $j3 = $j + 3;
                    $j4 = $j + 4;
                    ${"row" . $j1 . "_col2"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->company .
                        "</spam></td>";
                    ${"row" . $j2 . "_col2"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address1 .
                        "</spam></td>";
                    ${"row" . $j3 . "_col2"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address2 .
                        "</spam></td>";
                    ${"row" . $j4 . "_col2"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address3 .
                        "</spam></td>";
                } elseif ($inv_to_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:50%"><label class="header_info_to_title">' .
                        $inv_to_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        "</spam></td>";
                    $j1 = $j + 1;
                    $j2 = $j + 2;
                    $j3 = $j + 3;
                    $j4 = $j + 4;
                    ${"row" . $j1 . "_col3"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->company .
                        "</spam></td>";
                    ${"row" . $j2 . "_col3"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address1 .
                        "</spam></td>";
                    ${"row" . $j3 . "_col3"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address2 .
                        "</spam></td>";
                    ${"row" . $j4 . "_col3"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address3 .
                        "</spam></td>";
                } elseif ($inv_to_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:50%"><label class="header_info_to_title">' .
                        $inv_to_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->firstname .
                        " " .
                        $client_details->surname .
                        "</spam></td>";
                    $j1 = $j + 1;
                    $j2 = $j + 2;
                    $j3 = $j + 3;
                    $j4 = $j + 4;
                    ${"row" . $j1 . "_col4"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->company .
                        "</spam></td>";
                    ${"row" . $j2 . "_col4"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address1 .
                        "</spam></td>";
                    ${"row" . $j3 . "_col4"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address2 .
                        "</spam></td>";
                    ${"row" . $j4 . "_col4"} =
                        '<td style="width:50%"><spam class="header_info">' .
                        $client_details->address3 .
                        "</spam></td>";
                }
            }
            if ($client_id_offset == $j) {
                if ($client_id_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_title">' .
                        $client_id_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->client_id .
                        "</spam></td>";
                } elseif ($client_id_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $client_id_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->client_id .
                        "</spam></td>";
                } elseif ($client_id_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $client_id_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->client_id .
                        "</spam></td>";
                } elseif ($client_id_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%"><label class="header_info_title">' .
                        $client_id_text .
                        ':</label> <spam class="header_info">' .
                        $client_details->client_id .
                        "</spam></td>";
                }
            }
            if ($inv_no_offset == $j) {
                if ($inv_no_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_title">' .
                        $inv_no_text .
                        ':</label> <spam class="header_info">' .
                        $inv_no .
                        "</spam></td>";
                } elseif ($inv_no_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_no_text .
                        ':</label> <spam class="header_info">' .
                        $inv_no .
                        "</spam></td>";
                } elseif ($inv_no_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_no_text .
                        ':</label> <spam class="header_info">' .
                        $inv_no .
                        "</spam></td>";
                } elseif ($inv_no_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%"><label class="header_info_title">' .
                        $inv_no_text .
                        ':</label> <spam class="header_info">' .
                        $inv_no .
                        "</spam></td>";
                }
            }
            if ($inv_date_location_offset == $j) {
                if ($inv_date_location_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_title">' .
                        $inv_date_location_text .
                        ':</label> <spam class="header_info">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        "</spam></td>";
                } elseif ($inv_date_location_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_date_location_text .
                        ':</label> <spam class="header_info">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        "</spam></td>";
                } elseif ($inv_date_location_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_date_location_text .
                        ':</label> <spam class="header_info">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        "</spam></td>";
                } elseif ($inv_date_location_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%"><label class="header_info_title">' .
                        $inv_date_location_text .
                        ':</label> <spam class="header_info">' .
                        date(
                            "d-M-Y",
                            strtotime($invoice_details->invoice_date)
                        ) .
                        "</spam></td>";
                }
            }
            if ($inv_iban_offset == $j) {
                if ($inv_iban_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_title">' .
                        $inv_iban_text .
                        ':</label> <spam class="header_info">' .
                        $iban_field .
                        "</spam></td>";
                } elseif ($inv_iban_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_iban_text .
                        ':</label> <spam class="header_info">' .
                        $iban_field .
                        "</spam></td>";
                } elseif ($inv_iban_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_iban_text .
                        ':</label> <spam class="header_info">' .
                        $iban_field .
                        "</spam></td>";
                } elseif ($inv_iban_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%"><label class="header_info_title">' .
                        $inv_iban_text .
                        ':</label> <spam class="header_info">' .
                        $iban_field .
                        "</spam></td>";
                }
            }
            if ($inv_bic_offset == $j) {
                if ($inv_bic_left_offset == 1) {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%"><label class="header_info_title">' .
                        $inv_bic_text .
                        ':</label> <spam class="header_info">' .
                        $bic_field .
                        "</spam></td>";
                } elseif ($inv_bic_left_offset == 2) {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_bic_text .
                        ':</label> <spam class="header_info">' .
                        $bic_field .
                        "</spam></td>";
                } elseif ($inv_bic_left_offset == 3) {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%"><label class="header_info_title">' .
                        $inv_bic_text .
                        ':</label> <spam class="header_info">' .
                        $bic_field .
                        "</spam></td>";
                } elseif ($inv_bic_left_offset == 4) {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%"><label class="header_info_title">' .
                        $inv_bic_text .
                        ':</label> <spam class="header_info">' .
                        $bic_field .
                        "</spam></td>";
                }
            }
            if (isset(${"row" . $j . "_col1"})) {
                if (${"row" . $j . "_col1"} == "") {
                    ${"row" . $j . "_col1"} =
                        '<td style="width:50%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $j . "_col1"};
            } else {
                ${"row" . $j . "_col1"} = '<td style="width:50%">&nbsp;</td>';
                $display_output .= ${"row" . $j . "_col1"};
            }
            if (isset(${"row" . $j . "_col2"})) {
                if (${"row" . $j . "_col2"} == "") {
                    ${"row" . $j . "_col2"} =
                        '<td style="width:10%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $j . "_col2"};
            } else {
                ${"row" . $j . "_col2"} = '<td style="width:10%">&nbsp;</td>';
                $display_output .= ${"row" . $j . "_col2"};
            }
            if (isset(${"row" . $j . "_col3"})) {
                if (${"row" . $j . "_col3"} == "") {
                    ${"row" . $j . "_col3"} =
                        '<td style="width:10%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $j . "_col3"};
            } else {
                ${"row" . $j . "_col3"} = '<td style="width:10%">&nbsp;</td>';
                $display_output .= ${"row" . $j . "_col3"};
            }
            if (isset(${"row" . $j . "_col4"})) {
                if (${"row" . $j . "_col4"} == "") {
                    ${"row" . $j . "_col4"} =
                        '<td style="width:30%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $j . "_col4"};
            } else {
                ${"row" . $j . "_col4"} = '<td style="width:30%">&nbsp;</td>';
                $display_output .= ${"row" . $j . "_col4"};
            }
            $display_output .= "</tr>";
        }
        if ($invoice_details->inv_net >= 0) {
            $heading = $plus_invoice_heading;
        } else {
            $heading = $minus_invoice_heading;
        }
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        $heading = str_replace(" ","&nbsp;",$heading);
        // $display_output.='<tr>
        //       <td class="class_row_td right">'.$heading.'</td>
        //       <td class="class_row_td left_corner"></td>
        //       <td class="class_row_td right_start"></td>
        //       <td class="class_row_td right"></td>
        //     </tr>';
        $display_output .=
            '<tr>
      <td colspan="4" style="text-align:center;font-weight:700;font-size:18px">' .
            $heading .
            '</td>
    </tr>';
        for ($k = $offset_lines + 1; $k <= $suboffset_lines - 1; $k++) {
            $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
        }
        if ($invoice_details) {
            if ($invoice_details->bn_row1 != "") {
                $bn_row1_add_zero = number_format_invoice(
                    $invoice_details->bn_row1
                );
            } else {
                $bn_row1_add_zero = "";
            }
            if (
                $invoice_details->f_row1 != "" ||
                $invoice_details->z_row1 != "" ||
                $invoice_details->at_row1 != "" ||
                $bn_row1_add_zero != ""
            ) {
                $display_output .=
                    '<tr>
          <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                    '</td>
          <td class="class_row_td left_corner">' .
                    $invoice_details->z_row1 .
                    '</td>
          <td class="class_row_td right_start">' .
                    $invoice_details->at_row1 .
                    '</td>
          <td class="class_row_td right">' .
                    $bn_row1_add_zero .
                    '</td>
        </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bo_row2 != "") {
                $bo_row2_add_zero = number_format_invoice(
                    $invoice_details->bo_row2
                );
            } else {
                $bo_row2_add_zero = "";
            }
            if (
                $invoice_details->g_row2 != "" ||
                $invoice_details->aa_row2 != "" ||
                $invoice_details->au_row2 != "" ||
                $bo_row2_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aa_row2 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->au_row2 .
                    '</td>
            <td class="class_row_td right">' .
                    $bo_row2_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bp_row3 != "") {
                $bp_row3_add_zero = number_format_invoice(
                    $invoice_details->bp_row3
                );
            } else {
                $bp_row3_add_zero = "";
            }
            if (
                $invoice_details->h_row3 != "" ||
                $invoice_details->ab_row3 != "" ||
                $invoice_details->av_row3 != "" ||
                $bp_row3_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ab_row3 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->av_row3 .
                    '</td>
            <td class="class_row_td right">' .
                    $bp_row3_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bq_row4 != "") {
                $bq_row4_add_zero = number_format_invoice(
                    $invoice_details->bq_row4
                );
            } else {
                $bq_row4_add_zero = "";
            }
            if (
                $invoice_details->i_row4 != "" ||
                $invoice_details->ac_row4 != "" ||
                $invoice_details->aw_row4 != "" ||
                $bq_row4_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ac_row4 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->aw_row4 .
                    '</td>
            <td class="class_row_td right">' .
                    $bq_row4_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->br_row5 != "") {
                $br_row5_add_zero = number_format_invoice(
                    $invoice_details->br_row5
                );
            } else {
                $br_row5_add_zero = "";
            }
            if (
                $invoice_details->j_row5 != "" ||
                $invoice_details->ad_row5 != "" ||
                $invoice_details->ax_row5 != "" ||
                $br_row5_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ad_row5 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ax_row5 .
                    '</td>
            <td class="class_row_td right">' .
                    $br_row5_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bs_row6 != "") {
                $bs_row6_add_zero = number_format_invoice(
                    $invoice_details->bs_row6
                );
            } else {
                $bs_row6_add_zero = "";
            }
            if (
                $invoice_details->k_row6 != "" ||
                $invoice_details->ae_row6 != "" ||
                $invoice_details->ay_row6 != "" ||
                $bs_row6_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ae_row6 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ay_row6 .
                    '</td>
            <td class="class_row_td right">' .
                    $bs_row6_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bt_row7 != "") {
                $bt_row7_add_zero = number_format_invoice(
                    $invoice_details->bt_row7
                );
            } else {
                $bt_row7_add_zero = "";
            }
            if (
                $invoice_details->l_row7 != "" ||
                $invoice_details->af_row7 != "" ||
                $invoice_details->az_row7 != "" ||
                $bt_row7_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->af_row7 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->az_row7 .
                    '</td>
            <td class="class_row_td right">' .
                    $bt_row7_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bu_row8 != "") {
                $bu_row8_add_zero = number_format_invoice(
                    $invoice_details->bu_row8
                );
            } else {
                $bu_row8_add_zero = "";
            }
            if (
                $invoice_details->m_row8 != "" ||
                $invoice_details->ag_row8 != "" ||
                $invoice_details->ba_row8 != "" ||
                $bu_row8_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ag_row8 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ba_row8 .
                    '</td>
            <td class="class_row_td right">' .
                    $bu_row8_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bv_row9 != "") {
                $bv_row9_add_zero = number_format_invoice(
                    $invoice_details->bv_row9
                );
            } else {
                $bv_row9_add_zero = "";
            }
            if (
                $invoice_details->n_row9 != "" ||
                $invoice_details->ah_row9 != "" ||
                $invoice_details->bb_row9 != "" ||
                $bv_row9_add_zero != ""
            ) {
                $display_output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ah_row9 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bb_row9 .
                    '</td>
            <td class="class_row_td right">' .
                    $bv_row9_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bw_row10 != "") {
                $bw_row10_add_zero = number_format_invoice(
                    $invoice_details->bw_row10
                );
            } else {
                $bw_row10_add_zero = "";
            }
            if (
                $invoice_details->o_row10 != "" ||
                $invoice_details->ai_row10 != "" ||
                $invoice_details->bc_row10 != "" ||
                $bw_row10_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ai_row10 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bc_row10 .
                    '</td>
            <td class="class_row_td right">' .
                    $bw_row10_add_zero .
                    '</td>
          </tr>';
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bx_row11 != "") {
                $bx_row11_add_zero = number_format_invoice(
                    $invoice_details->bx_row11
                );
            } else {
                $bx_row11_add_zero = "";
            }
            if (
                $invoice_details->p_row11 != "" ||
                $invoice_details->aj_row11 != "" ||
                $invoice_details->bd_row11 != "" ||
                $bx_row11_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aj_row11 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bd_row11 .
                    '</td>
            <td class="class_row_td right">' .
                    $bx_row11_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->by_row12 != "") {
                $by_row12_add_zero = number_format_invoice(
                    $invoice_details->by_row12
                );
            } else {
                $by_row12_add_zero = "";
            }
            if (
                $invoice_details->q_row12 != "" ||
                $invoice_details->ak_row12 != "" ||
                $invoice_details->be_row12 != "" ||
                $by_row12_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ak_row12 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->be_row12 .
                    '</td>
            <td class="class_row_td right">' .
                    $by_row12_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->bz_row13 != "") {
                $bz_row13_add_zero = number_format_invoice(
                    $invoice_details->bz_row13
                );
            } else {
                $bz_row13_add_zero = "";
            }
            if (
                $invoice_details->r_row13 != "" ||
                $invoice_details->al_row13 != "" ||
                $invoice_details->bf_row13 != "" ||
                $bz_row13_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->al_row13 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bf_row13 .
                    '</td>
            <td class="class_row_td right">' .
                    $bz_row13_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->ca_row14 != "") {
                $ca_row14_add_zero = number_format_invoice(
                    $invoice_details->ca_row14
                );
            } else {
                $ca_row14_add_zero = "";
            }
            if (
                $invoice_details->s_row14 != "" ||
                $invoice_details->am_row14 != "" ||
                $invoice_details->bg_row14 != "" ||
                $ca_row14_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->am_row14 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bg_row14 .
                    '</td>
            <td class="class_row_td right">' .
                    $ca_row14_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->cb_row15 != "") {
                $cb_row15_add_zero = number_format_invoice(
                    $invoice_details->cb_row15
                );
            } else {
                $cb_row15_add_zero = "";
            }
            if (
                $invoice_details->t_row15 != "" ||
                $invoice_details->an_row15 != "" ||
                $invoice_details->bh_row15 != "" ||
                $cb_row15_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->an_row15 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bh_row15 .
                    '</td>
            <td class="class_row_td right">' .
                    $cb_row15_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->cc_row16 != "") {
                $cc_row16_add_zero = number_format_invoice(
                    $invoice_details->cc_row16
                );
            } else {
                $cc_row16_add_zero = "";
            }
            if (
                $invoice_details->u_row16 != "" ||
                $invoice_details->ao_row16 != "" ||
                $invoice_details->bi_row16 != "" ||
                $cc_row16_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ao_row16 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bi_row16 .
                    '</td>
            <td class="class_row_td right">' .
                    $cc_row16_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->cd_row17 != "") {
                $cd_row17_add_zero = number_format_invoice(
                    $invoice_details->cd_row17
                );
            } else {
                $cd_row17_add_zero = "";
            }
            if (
                $invoice_details->v_row17 != "" ||
                $invoice_details->ap_row17 != "" ||
                $invoice_details->bj_row17 != "" ||
                $cd_row17_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ap_row17 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bj_row17 .
                    '</td>
            <td class="class_row_td right">' .
                    $cd_row17_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->ce_row18 != "") {
                $ce_row18_add_zero = number_format_invoice(
                    $invoice_details->ce_row18
                );
            } else {
                $ce_row18_add_zero = "";
            }
            if (
                $invoice_details->w_row18 != "" ||
                $invoice_details->aq_row18 != "" ||
                $invoice_details->bk_row18 != "" ||
                $ce_row18_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aq_row18 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bk_row18 .
                    '</td>
            <td class="class_row_td right">' .
                    $ce_row18_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->cf_row19 != "") {
                $cf_row19_add_zero = number_format_invoice(
                    $invoice_details->cf_row19
                );
            } else {
                $cf_row19_add_zero = "";
            }
            if (
                $invoice_details->x_row19 != "" ||
                $invoice_details->ar_row19 != "" ||
                $invoice_details->bl_row19 != "" ||
                $cf_row19_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ar_row19 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bl_row19 .
                    '</td>
            <td class="class_row_td right">' .
                    $cf_row19_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
            if ($invoice_details->cg_row20 != "") {
                $cg_row20_add_zero = number_format_invoice(
                    $invoice_details->cg_row20
                );
            } else {
                $cg_row20_add_zero = "";
            }
            if (
                $invoice_details->y_row20 != "" ||
                $invoice_details->as_row20 != "" ||
                $invoice_details->bm_row20 != "" ||
                $cg_row20_add_zero != ""
            ) {
                $display_output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->as_row20 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bm_row20 .
                    '</td>
            <td class="class_row_td right">' .
                    $cg_row20_add_zero .
                    "</td></tr>";
            } else {
                $display_output .= '<tr>
          <td class="class_row_td left">&nbsp</td>
          <td class="class_row_td left_corner"></td>
          <td class="class_row_td right_start"></td>
          <td class="class_row_td right"></td>
        </tr>';
            }
        }
        for ($i = $k + 20; $i <= 34; $i++) {
            $display_output .= "<tr>";
            if ($net_offset == $i) {
                ${"row" . $i . "_col1"} =
                    '<td class="footer_info_title" style="width:50%;vertical-align:middle">' .
                    $net_text .
                    ":</td>";
                if ($net_left_offset == 2) {
                    ${"row" . $i . "_col2"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->inv_net) .
                        "</spam></td>";
                } elseif ($net_left_offset == 3) {
                    ${"row" . $i . "_col3"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->inv_net) .
                        "</spam></td>";
                } elseif ($net_left_offset == 4) {
                    ${"row" . $i . "_col4"} =
                        '<td class="right" style="width:30%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->inv_net) .
                        "</spam></td>";
                }
            }
            if ($vat_offset == $i) {
                ${"row" . $i . "_col1"} =
                    '<td class="footer_info_title" style="width:50%;vertical-align:middle">' .
                    $vat_text .
                    " @ " .
                    $invoice_details->vat_rate .
                    "%:</td>";
                if ($vat_left_offset == 2) {
                    ${"row" . $i . "_col2"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->vat_value) .
                        "</spam></td>";
                } elseif ($vat_left_offset == 3) {
                    ${"row" . $i . "_col3"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->vat_value) .
                        "</spam></td>";
                } elseif ($vat_left_offset == 4) {
                    ${"row" . $i . "_col4"} =
                        '<td class="right" style="width:30%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->vat_value) .
                        "</spam></td>";
                }
            }
            if ($gross_offset == $i) {
                ${"row" . $i . "_col1"} =
                    '<td class="footer_info_title" style="width:50%;vertical-align:middle">' .
                    $gross_text .
                    ":</td>";
                if ($gross_left_offset == 2) {
                    ${"row" . $i . "_col2"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->gross) .
                        "</spam></td>";
                } elseif ($gross_left_offset == 3) {
                    ${"row" . $i . "_col3"} =
                        '<td class="right" style="width:10%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->gross) .
                        "</spam></td>";
                } elseif ($gross_left_offset == 4) {
                    ${"row" . $i . "_col4"} =
                        '<td class="right" style="width:30%"><spam class="footer_info">' .
                        number_format_invoice($invoice_details->gross) .
                        "</spam></td>";
                }
            }
            if (isset(${"row" . $i . "_col1"})) {
                if (${"row" . $i . "_col1"} == "") {
                    ${"row" . $i . "_col1"} =
                        '<td style="width:50%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $i . "_col1"};
            } else {
                ${"row" . $i . "_col1"} = '<td style="width:50%">&nbsp;</td>';
                $display_output .= ${"row" . $i . "_col1"};
            }
            if (isset(${"row" . $i . "_col2"})) {
                if (${"row" . $i . "_col2"} == "") {
                    ${"row" . $i . "_col2"} =
                        '<td style="width:10%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $i . "_col2"};
            } else {
                ${"row" . $i . "_col2"} = '<td style="width:10%">&nbsp;</td>';
                $display_output .= ${"row" . $i . "_col2"};
            }
            if (isset(${"row" . $i . "_col3"})) {
                if (${"row" . $i . "_col3"} == "") {
                    ${"row" . $i . "_col3"} =
                        '<td style="width:10%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $i . "_col3"};
            } else {
                ${"row" . $i . "_col3"} = '<td style="width:10%">&nbsp;</td>';
                $display_output .= ${"row" . $i . "_col3"};
            }
            if (isset(${"row" . $i . "_col4"})) {
                if (${"row" . $i . "_col4"} == "") {
                    ${"row" . $i . "_col4"} =
                        '<td style="width:30%">&nbsp;</td>';
                }
                $display_output .= ${"row" . $i . "_col4"};
            } else {
                ${"row" . $i . "_col4"} = '<td style="width:30%">&nbsp;</td>';
                $display_output .= ${"row" . $i . "_col4"};
            }
            $display_output .= "</tr>";
        }
        $display_output .= "</table>";
        $output = '<table class="table" id="selected_invoice_expand">
    <thead>
      <th>Invoice Lines</th>
      <th></th>
      <th></th>
      <th></th>
    </thead>
    <tbody id="selected_invoice_tbody">';
        if ($invoice_details) {
            if ($invoice_details->bn_row1 != "") {
                $bn_row1_add_zero = number_format_invoice(
                    $invoice_details->bn_row1
                );
            } else {
                $bn_row1_add_zero = "";
            }
            if (
                $invoice_details->f_row1 != "" ||
                $invoice_details->z_row1 != "" ||
                $invoice_details->at_row1 != "" ||
                $bn_row1_add_zero != ""
            ) {
                $output .=
                    '<tr>
          <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                    '</td>
          <td class="class_row_td left_corner">' .
                    $invoice_details->z_row1 .
                    '</td>
          <td class="class_row_td right_start">' .
                    $invoice_details->at_row1 .
                    '</td>
          <td class="class_row_td right">' .
                    $bn_row1_add_zero .
                    '</td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bo_row2 != "") {
                $bo_row2_add_zero = number_format_invoice(
                    $invoice_details->bo_row2
                );
            } else {
                $bo_row2_add_zero = "";
            }
            if (
                $invoice_details->g_row2 != "" ||
                $invoice_details->aa_row2 != "" ||
                $invoice_details->au_row2 != "" ||
                $bo_row2_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aa_row2 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->au_row2 .
                    '</td>
            <td class="class_row_td right">' .
                    $bo_row2_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bp_row3 != "") {
                $bp_row3_add_zero = number_format_invoice(
                    $invoice_details->bp_row3
                );
            } else {
                $bp_row3_add_zero = "";
            }
            if (
                $invoice_details->h_row3 != "" ||
                $invoice_details->ab_row3 != "" ||
                $invoice_details->av_row3 != "" ||
                $bp_row3_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ab_row3 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->av_row3 .
                    '</td>
            <td class="class_row_td right">' .
                    $bp_row3_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bq_row4 != "") {
                $bq_row4_add_zero = number_format_invoice(
                    $invoice_details->bq_row4
                );
            } else {
                $bq_row4_add_zero = "";
            }
            if (
                $invoice_details->i_row4 != "" ||
                $invoice_details->ac_row4 != "" ||
                $invoice_details->aw_row4 != "" ||
                $bq_row4_add_zero != ""
            ) {
                $output .=
                    '<tr class="empty_line_tr">             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ac_row4 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->aw_row4 .
                    '</td>
            <td class="class_row_td right">' .
                    $bq_row4_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->br_row5 != "") {
                $br_row5_add_zero = number_format_invoice(
                    $invoice_details->br_row5
                );
            } else {
                $br_row5_add_zero = "";
            }
            if (
                $invoice_details->j_row5 != "" ||
                $invoice_details->ad_row5 != "" ||
                $invoice_details->ax_row5 != "" ||
                $br_row5_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ad_row5 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ax_row5 .
                    '</td>
            <td class="class_row_td right">' .
                    $br_row5_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bs_row6 != "") {
                $bs_row6_add_zero = number_format_invoice(
                    $invoice_details->bs_row6
                );
            } else {
                $bs_row6_add_zero = "";
            }
            if (
                $invoice_details->k_row6 != "" ||
                $invoice_details->ae_row6 != "" ||
                $invoice_details->ay_row6 != "" ||
                $bs_row6_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ae_row6 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ay_row6 .
                    '</td>
            <td class="class_row_td right">' .
                    $bs_row6_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bt_row7 != "") {
                $bt_row7_add_zero = number_format_invoice(
                    $invoice_details->bt_row7
                );
            } else {
                $bt_row7_add_zero = "";
            }
            if (
                $invoice_details->l_row7 != "" ||
                $invoice_details->af_row7 != "" ||
                $invoice_details->az_row7 != "" ||
                $bt_row7_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->af_row7 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->az_row7 .
                    '</td>
            <td class="class_row_td right">' .
                    $bt_row7_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bu_row8 != "") {
                $bu_row8_add_zero = number_format_invoice(
                    $invoice_details->bu_row8
                );
            } else {
                $bu_row8_add_zero = "";
            }
            if (
                $invoice_details->m_row8 != "" ||
                $invoice_details->ag_row8 != "" ||
                $invoice_details->ba_row8 != "" ||
                $bu_row8_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ag_row8 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ba_row8 .
                    '</td>
            <td class="class_row_td right">' .
                    $bu_row8_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bv_row9 != "") {
                $bv_row9_add_zero = number_format_invoice(
                    $invoice_details->bv_row9
                );
            } else {
                $bv_row9_add_zero = "";
            }
            if (
                $invoice_details->n_row9 != "" ||
                $invoice_details->ah_row9 != "" ||
                $invoice_details->bb_row9 != "" ||
                $bv_row9_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ah_row9 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bb_row9 .
                    '</td>
            <td class="class_row_td right">' .
                    $bv_row9_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bw_row10 != "") {
                $bw_row10_add_zero = number_format_invoice(
                    $invoice_details->bw_row10
                );
            } else {
                $bw_row10_add_zero = "";
            }
            if (
                $invoice_details->o_row10 != "" ||
                $invoice_details->ai_row10 != "" ||
                $invoice_details->bc_row10 != "" ||
                $bw_row10_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ai_row10 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bc_row10 .
                    '</td>
            <td class="class_row_td right">' .
                    $bw_row10_add_zero .
                    '</td>
          </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bx_row11 != "") {
                $bx_row11_add_zero = number_format_invoice(
                    $invoice_details->bx_row11
                );
            } else {
                $bx_row11_add_zero = "";
            }
            if (
                $invoice_details->p_row11 != "" ||
                $invoice_details->aj_row11 != "" ||
                $invoice_details->bd_row11 != "" ||
                $bx_row11_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aj_row11 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bd_row11 .
                    '</td>
            <td class="class_row_td right">' .
                    $bx_row11_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->by_row12 != "") {
                $by_row12_add_zero = number_format_invoice(
                    $invoice_details->by_row12
                );
            } else {
                $by_row12_add_zero = "";
            }
            if (
                $invoice_details->q_row12 != "" ||
                $invoice_details->ak_row12 != "" ||
                $invoice_details->be_row12 != "" ||
                $by_row12_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ak_row12 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->be_row12 .
                    '</td>
            <td class="class_row_td right">' .
                    $by_row12_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->bz_row13 != "") {
                $bz_row13_add_zero = number_format_invoice(
                    $invoice_details->bz_row13
                );
            } else {
                $bz_row13_add_zero = "";
            }
            if (
                $invoice_details->r_row13 != "" ||
                $invoice_details->al_row13 != "" ||
                $invoice_details->bf_row13 != "" ||
                $bz_row13_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->al_row13 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bf_row13 .
                    '</td>
            <td class="class_row_td right">' .
                    $bz_row13_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->ca_row14 != "") {
                $ca_row14_add_zero = number_format_invoice(
                    $invoice_details->ca_row14
                );
            } else {
                $ca_row14_add_zero = "";
            }
            if (
                $invoice_details->s_row14 != "" ||
                $invoice_details->am_row14 != "" ||
                $invoice_details->bg_row14 != "" ||
                $ca_row14_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->am_row14 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bg_row14 .
                    '</td>
            <td class="class_row_td right">' .
                    $ca_row14_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->cb_row15 != "") {
                $cb_row15_add_zero = number_format_invoice(
                    $invoice_details->cb_row15
                );
            } else {
                $cb_row15_add_zero = "";
            }
            if (
                $invoice_details->t_row15 != "" ||
                $invoice_details->an_row15 != "" ||
                $invoice_details->bh_row15 != "" ||
                $cb_row15_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->an_row15 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bh_row15 .
                    '</td>
            <td class="class_row_td right">' .
                    $cb_row15_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->cc_row16 != "") {
                $cc_row16_add_zero = number_format_invoice(
                    $invoice_details->cc_row16
                );
            } else {
                $cc_row16_add_zero = "";
            }
            if (
                $invoice_details->u_row16 != "" ||
                $invoice_details->ao_row16 != "" ||
                $invoice_details->bi_row16 != "" ||
                $cc_row16_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ao_row16 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bi_row16 .
                    '</td>
            <td class="class_row_td right">' .
                    $cc_row16_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->cd_row17 != "") {
                $cd_row17_add_zero = number_format_invoice(
                    $invoice_details->cd_row17
                );
            } else {
                $cd_row17_add_zero = "";
            }
            if (
                $invoice_details->v_row17 != "" ||
                $invoice_details->ap_row17 != "" ||
                $invoice_details->bj_row17 != "" ||
                $cd_row17_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ap_row17 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bj_row17 .
                    '</td>
            <td class="class_row_td right">' .
                    $cd_row17_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->ce_row18 != "") {
                $ce_row18_add_zero = number_format_invoice(
                    $invoice_details->ce_row18
                );
            } else {
                $ce_row18_add_zero = "";
            }
            if (
                $invoice_details->w_row18 != "" ||
                $invoice_details->aq_row18 != "" ||
                $invoice_details->bk_row18 != "" ||
                $ce_row18_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aq_row18 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bk_row18 .
                    '</td>
            <td class="class_row_td right">' .
                    $ce_row18_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->cf_row19 != "") {
                $cf_row19_add_zero = number_format_invoice(
                    $invoice_details->cf_row19
                );
            } else {
                $cf_row19_add_zero = "";
            }
            if (
                $invoice_details->x_row19 != "" ||
                $invoice_details->ar_row19 != "" ||
                $invoice_details->bl_row19 != "" ||
                $cf_row19_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ar_row19 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bl_row19 .
                    '</td>
            <td class="class_row_td right">' .
                    $cf_row19_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
            if ($invoice_details->cg_row20 != "") {
                $cg_row20_add_zero = number_format_invoice(
                    $invoice_details->cg_row20
                );
            } else {
                $cg_row20_add_zero = "";
            }
            if (
                $invoice_details->y_row20 != "" ||
                $invoice_details->as_row20 != "" ||
                $invoice_details->bm_row20 != "" ||
                $cg_row20_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->as_row20 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bm_row20 .
                    '</td>
            <td class="class_row_td right">' .
                    $cg_row20_add_zero .
                    "</td></tr>";
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
            }
        }
        $output .= '</tbody>
    </table>';
        $dataval["invoice_lines"] = $output;
        $dataval["display_output"] = $display_output;
        echo json_encode($dataval);
    }
    public function show_invoice_lines_for_invoice(Request $request)
    {
        $inv_no = $request->get("inv_no");
        $invoice_details = \App\Models\InvoiceSystem::where(
            "invoice_number",
            $inv_no
        )->first();
        $edit_output = '<table class="table" id="editted_invoice_table">
    <tbody id="editted_invoice_tbody">';
        $output = '<table class="table" id="edit_invoice_expand">
    <thead>
      <th>Invoice Lines</th>
      <th></th>
      <th></th>
      <th></th>
    </thead>
    <tbody id="edit_invoice_tbody">';
        if ($invoice_details) {
            if ($invoice_details->bn_row1 != "") {
                $bn_row1_add_zero = number_format_invoice(
                    $invoice_details->bn_row1
                );
            } else {
                $bn_row1_add_zero = "";
            }
            if (
                $invoice_details->f_row1 != "" ||
                $invoice_details->z_row1 != "" ||
                $invoice_details->at_row1 != "" ||
                $bn_row1_add_zero != ""
            ) {
                $output .=
                    '<tr>
          <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                    '</td>
          <td class="class_row_td left_corner">' .
                    $invoice_details->z_row1 .
                    '</td>
          <td class="class_row_td right_start">' .
                    $invoice_details->at_row1 .
                    '</td>
          <td class="class_row_td right">' .
                    $bn_row1_add_zero .
                    '</td>
        </tr>';
                $edit_output .=
                    '<tr>
          <td style="width:55%"><input type="text" name="f_row1" class="form-control input-sm f_row1" id="f_row1" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->f_row1) .
                    '"></td>
          <td style="width:15%"><input type="text" name="z_row1" class="form-control input-sm z_row1" id="z_row1" value="' .
                    $invoice_details->z_row1 .
                    '"></td></td>
          <td style="width:15%"><input type="text" name="at_row1" class="form-control input-sm at_row1" id="at_row1" value="' .
                    $invoice_details->at_row1 .
                    '"></td>
          <td style="width:15%"><input type="text" name="bn_row1" class="form-control input-sm bn_row1" id="bn_row1" value="' .
                    $bn_row1_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td style="width:55%"><input type="text" name="f_row1" class="form-control input-sm f_row1" id="f_row1" value=""></td>
          <td style="width:15%"><input type="text" name="z_row1" class="form-control input-sm z_row1" id="z_row1" value=""></td></td>
          <td style="width:15%"><input type="text" name="at_row1" class="form-control input-sm at_row1" id="at_row1" value=""></td>
          <td style="width:15%"><input type="text" name="bn_row1" class="form-control input-sm bn_row1" id="bn_row1" value=""></td>
        </tr>';
            }
            if ($invoice_details->bo_row2 != "") {
                $bo_row2_add_zero = number_format_invoice(
                    $invoice_details->bo_row2
                );
            } else {
                $bo_row2_add_zero = "";
            }
            if (
                $invoice_details->g_row2 != "" ||
                $invoice_details->aa_row2 != "" ||
                $invoice_details->au_row2 != "" ||
                $bo_row2_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aa_row2 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->au_row2 .
                    '</td>
            <td class="class_row_td right">' .
                    $bo_row2_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="g_row2" class="form-control input-sm g_row2" id="g_row2" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->g_row2) .
                    '"></td>
          <td><input type="text" name="aa_row2" class="form-control input-sm aa_row2" id="aa_row2" value="' .
                    $invoice_details->aa_row2 .
                    '"></td></td>
          <td><input type="text" name="au_row2" class="form-control input-sm au_row2" id="au_row2" value="' .
                    $invoice_details->au_row2 .
                    '"></td>
          <td><input type="text" name="bo_row2" class="form-control input-sm bo_row2" id="bo_row2" value="' .
                    $bo_row2_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="g_row2" class="form-control input-sm g_row2" id="g_row2" value=""></td>
          <td><input type="text" name="aa_row2" class="form-control input-sm aa_row2" id="aa_row2" value=""></td></td>
          <td><input type="text" name="au_row2" class="form-control input-sm au_row2" id="au_row2" value=""></td>
          <td><input type="text" name="bo_row2" class="form-control input-sm bo_row2" id="bo_row2" value=""></td>
        </tr>';
            }
            if ($invoice_details->bp_row3 != "") {
                $bp_row3_add_zero = number_format_invoice(
                    $invoice_details->bp_row3
                );
            } else {
                $bp_row3_add_zero = "";
            }
            if (
                $invoice_details->h_row3 != "" ||
                $invoice_details->ab_row3 != "" ||
                $invoice_details->av_row3 != "" ||
                $bp_row3_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ab_row3 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->av_row3 .
                    '</td>
            <td class="class_row_td right">' .
                    $bp_row3_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="h_row3" class="form-control input-sm h_row3" id="h_row3" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->h_row3) .
                    '"></td>
          <td><input type="text" name="ab_row3" class="form-control input-sm ab_row3" id="ab_row3" value="' .
                    $invoice_details->ab_row3 .
                    '"></td></td>
          <td><input type="text" name="av_row3" class="form-control input-sm av_row3" id="av_row3" value="' .
                    $invoice_details->av_row3 .
                    '"></td>
          <td><input type="text" name="bp_row3" class="form-control input-sm bp_row3" id="bp_row3" value="' .
                    $bp_row3_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="h_row3" class="form-control input-sm h_row3" id="h_row3" value=""></td>
          <td><input type="text" name="ab_row3" class="form-control input-sm ab_row3" id="ab_row3" value=""></td></td>
          <td><input type="text" name="av_row3" class="form-control input-sm av_row3" id="av_row3" value=""></td>
          <td><input type="text" name="bp_row3" class="form-control input-sm bp_row3" id="bp_row3" value=""></td>
        </tr>';
            }
            if ($invoice_details->bq_row4 != "") {
                $bq_row4_add_zero = number_format_invoice(
                    $invoice_details->bq_row4
                );
            } else {
                $bq_row4_add_zero = "";
            }
            if (
                $invoice_details->i_row4 != "" ||
                $invoice_details->ac_row4 != "" ||
                $invoice_details->aw_row4 != "" ||
                $bq_row4_add_zero != ""
            ) {
                $output .=
                    '<tr class="empty_line_tr">              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ac_row4 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->aw_row4 .
                    '</td>
            <td class="class_row_td right">' .
                    $bq_row4_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="i_row4" class="form-control input-sm i_row4" id="i_row4" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->i_row4) .
                    '"></td>
          <td><input type="text" name="ac_row4" class="form-control input-sm ac_row4" id="ac_row4" value="' .
                    $invoice_details->ac_row4 .
                    '"></td></td>
          <td><input type="text" name="aw_row4" class="form-control input-sm aw_row4" id="aw_row4" value="' .
                    $invoice_details->aw_row4 .
                    '"></td>
          <td><input type="text" name="bq_row4" class="form-control input-sm bq_row4" id="bq_row4" value="' .
                    $bq_row4_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="i_row4" class="form-control input-sm i_row4" id="i_row4" value=""></td>
          <td><input type="text" name="ac_row4" class="form-control input-sm ac_row4" id="ac_row4" value=""></td></td>
          <td><input type="text" name="aw_row4" class="form-control input-sm aw_row4" id="aw_row4" value=""></td>
          <td><input type="text" name="bq_row4" class="form-control input-sm bq_row4" id="bq_row4" value=""></td>
        </tr>';
            }
            if ($invoice_details->br_row5 != "") {
                $br_row5_add_zero = number_format_invoice(
                    $invoice_details->br_row5
                );
            } else {
                $br_row5_add_zero = "";
            }
            if (
                $invoice_details->j_row5 != "" ||
                $invoice_details->ad_row5 != "" ||
                $invoice_details->ax_row5 != "" ||
                $br_row5_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ad_row5 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ax_row5 .
                    '</td>
            <td class="class_row_td right">' .
                    $br_row5_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="j_row5" class="form-control input-sm j_row5" id="j_row5" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->j_row5) .
                    '"></td>
          <td><input type="text" name="ad_row5" class="form-control input-sm ad_row5" id="ad_row5" value="' .
                    $invoice_details->ad_row5 .
                    '"></td></td>
          <td><input type="text" name="ax_row5" class="form-control input-sm ax_row5" id="ax_row5" value="' .
                    $invoice_details->ax_row5 .
                    '"></td>
          <td><input type="text" name="br_row5" class="form-control input-sm br_row5" id="br_row5" value="' .
                    $br_row5_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="j_row5" class="form-control input-sm j_row5" id="j_row5" value=""></td>
          <td><input type="text" name="ad_row5" class="form-control input-sm ad_row5" id="ad_row5" value=""></td></td>
          <td><input type="text" name="ax_row5" class="form-control input-sm ax_row5" id="ax_row5" value=""></td>
          <td><input type="text" name="br_row5" class="form-control input-sm br_row5" id="br_row5" value=""></td>
        </tr>';
            }
            if ($invoice_details->bs_row6 != "") {
                $bs_row6_add_zero = number_format_invoice(
                    $invoice_details->bs_row6
                );
            } else {
                $bs_row6_add_zero = "";
            }
            if (
                $invoice_details->k_row6 != "" ||
                $invoice_details->ae_row6 != "" ||
                $invoice_details->ay_row6 != "" ||
                $bs_row6_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ae_row6 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ay_row6 .
                    '</td>
            <td class="class_row_td right">' .
                    $bs_row6_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="k_row6" class="form-control input-sm k_row6" id="k_row6" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->k_row6) .
                    '"></td>
          <td><input type="text" name="ae_row6" class="form-control input-sm ae_row6" id="ae_row6" value="' .
                    $invoice_details->ae_row6 .
                    '"></td></td>
          <td><input type="text" name="ay_row6" class="form-control input-sm ay_row6" id="ay_row6" value="' .
                    $invoice_details->ay_row6 .
                    '"></td>
          <td><input type="text" name="bs_row6" class="form-control input-sm bs_row6" id="bs_row6" value="' .
                    $bs_row6_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="k_row6" class="form-control input-sm k_row6" id="k_row6" value=""></td>
          <td><input type="text" name="ae_row6" class="form-control input-sm ae_row6" id="ae_row6" value=""></td></td>
          <td><input type="text" name="ay_row6" class="form-control input-sm ay_row6" id="ay_row6" value=""></td>
          <td><input type="text" name="bs_row6" class="form-control input-sm bs_row6" id="bs_row6" value=""></td>
        </tr>';
            }
            if ($invoice_details->bt_row7 != "") {
                $bt_row7_add_zero = number_format_invoice(
                    $invoice_details->bt_row7
                );
            } else {
                $bt_row7_add_zero = "";
            }
            if (
                $invoice_details->l_row7 != "" ||
                $invoice_details->af_row7 != "" ||
                $invoice_details->az_row7 != "" ||
                $bt_row7_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->af_row7 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->az_row7 .
                    '</td>
            <td class="class_row_td right">' .
                    $bt_row7_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="l_row7" class="form-control input-sm l_row7" id="l_row7" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->l_row7) .
                    '"></td>
          <td><input type="text" name="af_row7" class="form-control input-sm af_row7" id="af_row7" value="' .
                    $invoice_details->af_row7 .
                    '"></td></td>
          <td><input type="text" name="az_row7" class="form-control input-sm az_row7" id="az_row7" value="' .
                    $invoice_details->az_row7 .
                    '"></td>
          <td><input type="text" name="bt_row7" class="form-control input-sm bt_row7" id="bt_row7" value="' .
                    $bt_row7_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="l_row7" class="form-control input-sm l_row7" id="l_row7" value=""></td>
          <td><input type="text" name="af_row7" class="form-control input-sm af_row7" id="af_row7" value=""></td></td>
          <td><input type="text" name="az_row7" class="form-control input-sm az_row7" id="az_row7" value=""></td>
          <td><input type="text" name="bt_row7" class="form-control input-sm bt_row7" id="bt_row7" value=""></td>
        </tr>';
            }
            if ($invoice_details->bu_row8 != "") {
                $bu_row8_add_zero = number_format_invoice(
                    $invoice_details->bu_row8
                );
            } else {
                $bu_row8_add_zero = "";
            }
            if (
                $invoice_details->m_row8 != "" ||
                $invoice_details->ag_row8 != "" ||
                $invoice_details->ba_row8 != "" ||
                $bu_row8_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ag_row8 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->ba_row8 .
                    '</td>
            <td class="class_row_td right">' .
                    $bu_row8_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="m_row8" class="form-control input-sm m_row8" id="m_row8" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->m_row8) .
                    '"></td>
          <td><input type="text" name="ag_row8" class="form-control input-sm ag_row8" id="ag_row8" value="' .
                    $invoice_details->ag_row8 .
                    '"></td></td>
          <td><input type="text" name="ba_row8" class="form-control input-sm ba_row8" id="ba_row8" value="' .
                    $invoice_details->ba_row8 .
                    '"></td>
          <td><input type="text" name="bu_row8" class="form-control input-sm bu_row8" id="bu_row8" value="' .
                    $bu_row8_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="m_row8" class="form-control input-sm m_row8" id="m_row8" value=""></td>
          <td><input type="text" name="ag_row8" class="form-control input-sm ag_row8" id="ag_row8" value=""></td></td>
          <td><input type="text" name="ba_row8" class="form-control input-sm ba_row8" id="ba_row8" value=""></td>
          <td><input type="text" name="bu_row8" class="form-control input-sm bu_row8" id="bu_row8" value="">
        </tr>';
            }
            if ($invoice_details->bv_row9 != "") {
                $bv_row9_add_zero = number_format_invoice(
                    $invoice_details->bv_row9
                );
            } else {
                $bv_row9_add_zero = "";
            }
            if (
                $invoice_details->n_row9 != "" ||
                $invoice_details->ah_row9 != "" ||
                $invoice_details->bb_row9 != "" ||
                $bv_row9_add_zero != ""
            ) {
                $output .=
                    '<tr>              
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ah_row9 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bb_row9 .
                    '</td>
            <td class="class_row_td right">' .
                    $bv_row9_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="n_row9" class="form-control input-sm n_row9" id="n_row9" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->n_row9) .
                    '"></td>
          <td><input type="text" name="ah_row9" class="form-control input-sm ah_row9" id="ah_row9" value="' .
                    $invoice_details->ah_row9 .
                    '"></td></td>
          <td><input type="text" name="bb_row9" class="form-control input-sm bb_row9" id="bb_row9" value="' .
                    $invoice_details->bb_row9 .
                    '"></td>
          <td><input type="text" name="bv_row9" class="form-control input-sm bv_row9" id="bv_row9" value="' .
                    $bv_row9_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="n_row9" class="form-control input-sm n_row9" id="n_row9" value=""></td>
          <td><input type="text" name="ah_row9" class="form-control input-sm ah_row9" id="ah_row9" value=""></td></td>
          <td><input type="text" name="bb_row9" class="form-control input-sm bb_row9" id="bb_row9" value=""></td>
          <td><input type="text" name="bv_row9" class="form-control input-sm bv_row9" id="bv_row9" value=""></td>
        </tr>';
            }
            if ($invoice_details->bw_row10 != "") {
                $bw_row10_add_zero = number_format_invoice(
                    $invoice_details->bw_row10
                );
            } else {
                $bw_row10_add_zero = "";
            }
            if (
                $invoice_details->o_row10 != "" ||
                $invoice_details->ai_row10 != "" ||
                $invoice_details->bc_row10 != "" ||
                $bw_row10_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ai_row10 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bc_row10 .
                    '</td>
            <td class="class_row_td right">' .
                    $bw_row10_add_zero .
                    '</td>
          </tr>';
                $edit_output .=
                    '<tr>
          <td><input type="text" name="o_row10" class="form-control input-sm o_row10" id="o_row10" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->o_row10) .
                    '"></td>
          <td><input type="text" name="ai_row10" class="form-control input-sm ai_row10" id="ai_row10" value="' .
                    $invoice_details->ai_row10 .
                    '"></td></td>
          <td><input type="text" name="bc_row10" class="form-control input-sm bc_row10" id="bc_row10" value="' .
                    $invoice_details->bc_row10 .
                    '"></td>
          <td><input type="text" name="bw_row10" class="form-control input-sm bw_row10" id="bw_row10" value="' .
                    $bw_row10_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="o_row10" class="form-control input-sm o_row10" id="o_row10" value=""></td>
          <td><input type="text" name="ai_row10" class="form-control input-sm ai_row10" id="ai_row10" value=""></td></td>
          <td><input type="text" name="bc_row10" class="form-control input-sm bc_row10" id="bc_row10" value=""></td>
          <td><input type="text" name="bw_row10" class="form-control input-sm bw_row10" id="bw_row10" value=""></td>
        </tr>';
            }
            if ($invoice_details->bx_row11 != "") {
                $bx_row11_add_zero = number_format_invoice(
                    $invoice_details->bx_row11
                );
            } else {
                $bx_row11_add_zero = "";
            }
            if (
                $invoice_details->p_row11 != "" ||
                $invoice_details->aj_row11 != "" ||
                $invoice_details->bd_row11 != "" ||
                $bx_row11_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aj_row11 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bd_row11 .
                    '</td>
            <td class="class_row_td right">' .
                    $bx_row11_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="p_row11" class="form-control input-sm p_row11" id="p_row11" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->p_row11) .
                    '"></td>
          <td><input type="text" name="aj_row11" class="form-control input-sm aj_row11" id="aj_row11" value="' .
                    $invoice_details->aj_row11 .
                    '"></td></td>
          <td><input type="text" name="bd_row11" class="form-control input-sm bd_row11" id="bd_row11" value="' .
                    $invoice_details->bd_row11 .
                    '"></td>
          <td><input type="text" name="bx_row11" class="form-control input-sm bx_row11" id="bx_row11" value="' .
                    $bx_row11_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="p_row11" class="form-control input-sm p_row11" id="p_row11" value=""></td>
          <td><input type="text" name="aj_row11" class="form-control input-sm aj_row11" id="aj_row11" value=""></td></td>
          <td><input type="text" name="bd_row11" class="form-control input-sm bd_row11" id="bd_row11" value=""></td>
          <td><input type="text" name="bx_row11" class="form-control input-sm bx_row11" id="bx_row11" value=""></td>
        </tr>';
            }
            if ($invoice_details->by_row12 != "") {
                $by_row12_add_zero = number_format_invoice(
                    $invoice_details->by_row12
                );
            } else {
                $by_row12_add_zero = "";
            }
            if (
                $invoice_details->q_row12 != "" ||
                $invoice_details->ak_row12 != "" ||
                $invoice_details->be_row12 != "" ||
                $by_row12_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ak_row12 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->be_row12 .
                    '</td>
            <td class="class_row_td right">' .
                    $by_row12_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="q_row12" class="form-control input-sm q_row12" id="q_row12" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->q_row12) .
                    '"></td>
          <td><input type="text" name="ak_row12" class="form-control input-sm ak_row12" id="ak_row12" value="' .
                    $invoice_details->ak_row12 .
                    '"></td></td>
          <td><input type="text" name="be_row12" class="form-control input-sm be_row12" id="be_row12" value="' .
                    $invoice_details->be_row12 .
                    '"></td>
          <td><input type="text" name="by_row12" class="form-control input-sm by_row12" id="by_row12" value="' .
                    $by_row12_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="q_row12" class="form-control input-sm q_row12" id="q_row12" value=""></td>
          <td><input type="text" name="ak_row12" class="form-control input-sm ak_row12" id="ak_row12" value=""></td></td>
          <td><input type="text" name="be_row12" class="form-control input-sm be_row12" id="be_row12" value=""></td>
          <td><input type="text" name="by_row12" class="form-control input-sm by_row12" id="by_row12" value=""></td>
        </tr>';
            }
            if ($invoice_details->bz_row13 != "") {
                $bz_row13_add_zero = number_format_invoice(
                    $invoice_details->bz_row13
                );
            } else {
                $bz_row13_add_zero = "";
            }
            if (
                $invoice_details->r_row13 != "" ||
                $invoice_details->al_row13 != "" ||
                $invoice_details->bf_row13 != "" ||
                $bz_row13_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->al_row13 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bf_row13 .
                    '</td>
            <td class="class_row_td right">' .
                    $bz_row13_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="r_row13" class="form-control input-sm r_row13" id="r_row13" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->r_row13) .
                    '"></td>
          <td><input type="text" name="al_row13" class="form-control input-sm al_row13" id="al_row13" value="' .
                    $invoice_details->al_row13 .
                    '"></td></td>
          <td><input type="text" name="bf_row13" class="form-control input-sm bf_row13" id="bf_row13" value="' .
                    $invoice_details->bf_row13 .
                    '"></td>
          <td><input type="text" name="bz_row13" class="form-control input-sm bz_row13" id="bz_row13" value="' .
                    $bz_row13_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="r_row13" class="form-control input-sm r_row13" id="r_row13" value=""></td>
          <td><input type="text" name="al_row13" class="form-control input-sm al_row13" id="al_row13" value=""></td></td>
          <td><input type="text" name="bf_row13" class="form-control input-sm bf_row13" id="bf_row13" value=""></td>
          <td><input type="text" name="bz_row13" class="form-control input-sm bz_row13" id="bz_row13" value=""></td>
        </tr>';
            }
            if ($invoice_details->ca_row14 != "") {
                $ca_row14_add_zero = number_format_invoice(
                    $invoice_details->ca_row14
                );
            } else {
                $ca_row14_add_zero = "";
            }
            if (
                $invoice_details->s_row14 != "" ||
                $invoice_details->am_row14 != "" ||
                $invoice_details->bg_row14 != "" ||
                $ca_row14_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->am_row14 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bg_row14 .
                    '</td>
            <td class="class_row_td right">' .
                    $ca_row14_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="s_row14" class="form-control input-sm s_row14" id="s_row14" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->s_row14) .
                    '"></td>
          <td><input type="text" name="am_row14" class="form-control input-sm am_row14" id="am_row14" value="' .
                    $invoice_details->am_row14 .
                    '"></td></td>
          <td><input type="text" name="bg_row14" class="form-control input-sm bg_row14" id="bg_row14" value="' .
                    $invoice_details->bg_row14 .
                    '"></td>
          <td><input type="text" name="ca_row14" class="form-control input-sm ca_row14" id="ca_row14" value="' .
                    $ca_row14_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="s_row14" class="form-control input-sm s_row14" id="s_row14" value=""></td>
          <td><input type="text" name="am_row14" class="form-control input-sm am_row14" id="am_row14" value=""></td></td>
          <td><input type="text" name="bg_row14" class="form-control input-sm bg_row14" id="bg_row14" value=""></td>
          <td><input type="text" name="ca_row14" class="form-control input-sm ca_row14" id="ca_row14" value=""></td>
        </tr>';
            }
            if ($invoice_details->cb_row15 != "") {
                $cb_row15_add_zero = number_format_invoice(
                    $invoice_details->cb_row15
                );
            } else {
                $cb_row15_add_zero = "";
            }
            if (
                $invoice_details->t_row15 != "" ||
                $invoice_details->an_row15 != "" ||
                $invoice_details->bh_row15 != "" ||
                $cb_row15_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->an_row15 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bh_row15 .
                    '</td>
            <td class="class_row_td right">' .
                    $cb_row15_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="t_row15" class="form-control input-sm t_row15" id="t_row15" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->t_row15) .
                    '"></td>
          <td><input type="text" name="an_row15" class="form-control input-sm an_row15" id="an_row15" value="' .
                    $invoice_details->an_row15 .
                    '"></td></td>
          <td><input type="text" name="bh_row15" class="form-control input-sm bh_row15" id="bh_row15" value="' .
                    $invoice_details->bh_row15 .
                    '"></td>
          <td><input type="text" name="cb_row15" class="form-control input-sm cb_row15" id="cb_row15" value="' .
                    $cb_row15_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="t_row15" class="form-control input-sm t_row15" id="t_row15" value=""></td>
          <td><input type="text" name="an_row15" class="form-control input-sm an_row15" id="an_row15" value=""></td></td>
          <td><input type="text" name="bh_row15" class="form-control input-sm bh_row15" id="bh_row15" value=""></td>
          <td><input type="text" name="cb_row15" class="form-control input-sm cb_row15" id="cb_row15" value=""></td>
        </tr>';
            }
            if ($invoice_details->cc_row16 != "") {
                $cc_row16_add_zero = number_format_invoice(
                    $invoice_details->cc_row16
                );
            } else {
                $cc_row16_add_zero = "";
            }
            if (
                $invoice_details->u_row16 != "" ||
                $invoice_details->ao_row16 != "" ||
                $invoice_details->bi_row16 != "" ||
                $cc_row16_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ao_row16 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bi_row16 .
                    '</td>
            <td class="class_row_td right">' .
                    $cc_row16_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="u_row16" class="form-control input-sm u_row16" id="u_row16" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->u_row16) .
                    '"></td>
          <td><input type="text" name="ao_row16" class="form-control input-sm ao_row16" id="ao_row16" value="' .
                    $invoice_details->ao_row16 .
                    '"></td></td>
          <td><input type="text" name="bi_row16" class="form-control input-sm bi_row16" id="bi_row16" value="' .
                    $invoice_details->bi_row16 .
                    '"></td>
          <td><input type="text" name="cc_row16" class="form-control input-sm cc_row16" id="cc_row16" value="' .
                    $cc_row16_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="u_row16" class="form-control input-sm u_row16" id="u_row16" value=""></td>
          <td><input type="text" name="ao_row16" class="form-control input-sm ao_row16" id="ao_row16" value=""></td></td>
          <td><input type="text" name="bi_row16" class="form-control input-sm bi_row16" id="bi_row16" value=""></td>
          <td><input type="text" name="cc_row16" class="form-control input-sm cc_row16" id="cc_row16" value=""></td>
        </tr>';
            }
            if ($invoice_details->cd_row17 != "") {
                $cd_row17_add_zero = number_format_invoice(
                    $invoice_details->cd_row17
                );
            } else {
                $cd_row17_add_zero = "";
            }
            if (
                $invoice_details->v_row17 != "" ||
                $invoice_details->ap_row17 != "" ||
                $invoice_details->bj_row17 != "" ||
                $cd_row17_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ap_row17 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bj_row17 .
                    '</td>
            <td class="class_row_td right">' .
                    $cd_row17_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="v_row17" class="form-control input-sm v_row17" id="v_row17" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->v_row17) .
                    '"></td>
          <td><input type="text" name="ap_row17" class="form-control input-sm ap_row17" id="ap_row17" value="' .
                    $invoice_details->ap_row17 .
                    '"></td></td>
          <td><input type="text" name="bj_row17" class="form-control input-sm bj_row17" id="bj_row17" value="' .
                    $invoice_details->bj_row17 .
                    '"></td>
          <td><input type="text" name="cd_row17" class="form-control input-sm cd_row17" id="cd_row17" value="' .
                    $cd_row17_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="v_row17" class="form-control input-sm v_row17" id="v_row17" value=""></td>
          <td><input type="text" name="ap_row17" class="form-control input-sm ap_row17" id="ap_row17" value=""></td></td>
          <td><input type="text" name="bj_row17" class="form-control input-sm bj_row17" id="bj_row17" value=""></td>
          <td><input type="text" name="cd_row17" class="form-control input-sm cd_row17" id="cd_row17" value=""></td>
        </tr>';
            }
            if ($invoice_details->ce_row18 != "") {
                $ce_row18_add_zero = number_format_invoice(
                    $invoice_details->ce_row18
                );
            } else {
                $ce_row18_add_zero = "";
            }
            if (
                $invoice_details->w_row18 != "" ||
                $invoice_details->aq_row18 != "" ||
                $invoice_details->bk_row18 != "" ||
                $ce_row18_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->aq_row18 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bk_row18 .
                    '</td>
            <td class="class_row_td right">' .
                    $ce_row18_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="w_row18" class="form-control input-sm w_row18" id="w_row18" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->w_row18) .
                    '"></td>
          <td><input type="text" name="aq_row18" class="form-control input-sm aq_row18" id="aq_row18" value="' .
                    $invoice_details->aq_row18 .
                    '"></td></td>
          <td><input type="text" name="bk_row18" class="form-control input-sm bk_row18" id="bk_row18" value="' .
                    $invoice_details->bk_row18 .
                    '"></td>
          <td><input type="text" name="ce_row18" class="form-control input-sm ce_row18" id="ce_row18" value="' .
                    $ce_row18_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="w_row18" class="form-control input-sm w_row18" id="w_row18" value=""></td>
          <td><input type="text" name="aq_row18" class="form-control input-sm aq_row18" id="aq_row18" value=""></td></td>
          <td><input type="text" name="bk_row18" class="form-control input-sm bk_row18" id="bk_row18" value=""></td>
          <td><input type="text" name="ce_row18" class="form-control input-sm ce_row18" id="ce_row18" value=""></td>
        </tr>';
            }
            if ($invoice_details->cf_row19 != "") {
                $cf_row19_add_zero = number_format_invoice(
                    $invoice_details->cf_row19
                );
            } else {
                $cf_row19_add_zero = "";
            }
            if (
                $invoice_details->x_row19 != "" ||
                $invoice_details->ar_row19 != "" ||
                $invoice_details->bl_row19 != "" ||
                $cf_row19_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->ar_row19 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bl_row19 .
                    '</td>
            <td class="class_row_td right">' .
                    $cf_row19_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="x_row19" class="form-control input-sm x_row19" id="x_row19" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->x_row19) .
                    '"></td>
          <td><input type="text" name="ar_row19" class="form-control input-sm ar_row19" id="ar_row19" value="' .
                    $invoice_details->ar_row19 .
                    '"></td></td>
          <td><input type="text" name="bl_row19" class="form-control input-sm bl_row19" id="bl_row19" value="' .
                    $invoice_details->bl_row19 .
                    '"></td>
          <td><input type="text" name="cf_row19" class="form-control input-sm cf_row19" id="cf_row19" value="' .
                    $cf_row19_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="x_row19" class="form-control input-sm x_row19" id="x_row19" value=""></td>
          <td><input type="text" name="ar_row19" class="form-control input-sm ar_row19" id="ar_row19" value=""></td></td>
          <td><input type="text" name="bl_row19" class="form-control input-sm bl_row19" id="bl_row19" value=""></td>
          <td><input type="text" name="cf_row19" class="form-control input-sm cf_row19" id="cf_row19" value=""></td>
        </tr>';
            }
            if ($invoice_details->cg_row20 != "") {
                $cg_row20_add_zero = number_format_invoice(
                    $invoice_details->cg_row20
                );
            } else {
                $cg_row20_add_zero = "";
            }
            if (
                $invoice_details->y_row20 != "" ||
                $invoice_details->as_row20 != "" ||
                $invoice_details->bm_row20 != "" ||
                $cg_row20_add_zero != ""
            ) {
                $output .=
                    '<tr>             
            <td class="class_row_td left">' .
                    str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                    '</td>
            <td class="class_row_td left_corner">' .
                    $invoice_details->as_row20 .
                    '</td>
            <td class="class_row_td right_start">' .
                    $invoice_details->bm_row20 .
                    '</td>
            <td class="class_row_td right">' .
                    $cg_row20_add_zero .
                    "</td></tr>";
                $edit_output .=
                    '<tr>
          <td><input type="text" name="y_row20" class="form-control input-sm y_row20" id="y_row20" value="' .
                    str_replace(" ", "&nbsp;", $invoice_details->y_row20) .
                    '"></td>
          <td><input type="text" name="as_row20" class="form-control input-sm as_row20" id="as_row20" value="' .
                    $invoice_details->as_row20 .
                    '"></td></td>
          <td><input type="text" name="bm_row20" class="form-control input-sm bm_row20" id="bm_row20" value="' .
                    $invoice_details->bm_row20 .
                    '"></td>
          <td><input type="text" name="cg_row20" class="form-control input-sm cg_row20" id="cg_row20" value="' .
                    $cg_row20_add_zero .
                    '"></td>
        </tr>';
            } else {
                $output .= '<tr class="empty_line_tr">
          <td colspan="4" class="empty_line_row"></td>
        </tr>';
                $edit_output .= '<tr>
          <td><input type="text" name="y_row20" class="form-control input-sm y_row20" id="y_row20" value=""></td>
          <td><input type="text" name="as_row20" class="form-control input-sm as_row20" id="as_row20" value=""></td></td>
          <td><input type="text" name="bm_row20" class="form-control input-sm bm_row20" id="bm_row20" value=""></td>
          <td><input type="text" name="cg_row20" class="form-control input-sm cg_row20" id="cg_row20" value=""></td>
        </tr>';
            }
            $settings_details = \App\Models\settings::where("source", "build_invoice")
                ->where("practice_code", Session::get("user_practice_code"))
                ->first();
            if (($settings_details)) {
                $settings = unserialize($settings_details->settings);
                $net_text =
                    $settings["net_text"] != "" ? $settings["net_text"] : "Net";
                $vat_text =
                    $settings["vat_text"] != "" ? $settings["vat_text"] : "VAT";
                $gross_text =
                    $settings["gross_text"] != ""
                        ? $settings["gross_text"]
                        : "Gross";
            }
            $edit_output .=
                '<tr>
        <td><input type="text" name="" class="form-control input-sm" value="' .
                $net_text .
                '" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="inv_net" class="form-control input-sm inv_net" id="inv_net" value="' .
                $invoice_details->inv_net .
                '"></td>
      </tr>
      <tr>
        <td><input type="text" name="" class="form-control input-sm" value="' .
                $vat_text .
                '" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="vat_value" class="form-control input-sm vat_value" id="vat_value" value="' .
                $invoice_details->vat_value .
                '"></td>
      </tr>
      <tr>
        <td><input type="text" name="" class="form-control input-sm" value="' .
                $gross_text .
                '" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="" class="form-control input-sm" value="" disabled></td>
        <td><input type="text" name="gross" class="form-control input-sm gross" id="gross" value="' .
                $invoice_details->gross .
                '"></td>
      </tr>';
        }
        $output .= '</tbody>
    </table>';
        $edit_output .= '</tbody>
    </table>';
        $dataval["invoice_lines"] = $output;
        $dataval["edit_invoice_lines"] = $edit_output;
        echo json_encode($dataval);
    }
    public function update_invoice_lines_for_invoice(Request $request)
    {
        $datas = json_decode($request->get("datas"));
        $inv_no = $request->get("inv_no");
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                foreach ($data as $key => $value) {
                    $updateval[$key] = $value;
                }
            }
            \App\Models\InvoiceSystem::where("invoice_number", $inv_no)->update(
                $updateval
            );
        }
    }
    public function update_build_invoice_setings(Request $request)
    {
        $datas = json_decode($request->get("datas"));
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                foreach ($data as $key => $value) {
                    $updateval[$key] = $value;
                }
            }
            $serialized = serialize($updateval);
            $settings_details = \App\Models\settings::where("source", "build_invoice")
                ->where("practice_code", Session::get("user_practice_code"))
                ->first();
            if ($settings_details) {
                $updatevalue["settings"] = $serialized;
                \App\Models\settings::where("id", $settings_details->id)->update(
                    $updatevalue
                );
            } else {
                $updatevalue["source"] = "build_invoice";
                $updatevalue["practice_code"] = Session::get(
                    "user_practice_code"
                );
                $updatevalue["settings"] = $serialized;
                \App\Models\settings::insert($updatevalue);
            }
        }
    }
    public function build_invoice_saveas_pdf(Request $request)
    {
        $inv_no = $request->get("inv_no");
        $type = $request->get("type");
        $settings_details = \App\Models\settings::where("source", "build_invoice")
            ->where("practice_code", Session::get("user_practice_code"))
            ->first();
        if ($settings_details) {
            $settings = unserialize($settings_details->settings);
            $left_margin =
                $settings["left_margin"] != ""
                    ? $settings["left_margin"]
                    : "95";
            $top_margin =
                $settings["top_margin"] != "" ? $settings["top_margin"] : "200";
            $top_margin = $top_margin + 25;
        }
        if ($type == "1") {
            $html =
                '<html>
                <body style="width:700px">
                <style>
                  @page { margin: 0in; }
                  body {
                    font-family: Verdana,Geneva,sans-serif; 
                    font-size:12px;
                  }
                  .display_table_expand{margin-top:' .
                        $top_margin .
                        'px !important;}
                  .right{text-align: center;}
                  .right_start{text-align: center;}
                  .header_info_title{width:75px;margin-bottom: 0px !important;}
                  .header_info_to_title{width:30px;margin-bottom: 0px !important;}
                  .footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;}
              </style>';
                } else {
                    $html =
                        '<html>
                <body style="width:900px">
                <style>
                  @page { margin: 0in; }
                  body {
                      background-image: url(' .
                        URL::to("public/assets/invoice_letterpad_1.jpg") .
                        ');
                      background-position: top left right bottom;
                    background-repeat: no-repeat;
                    font-family: Verdana,Geneva,sans-serif; 
                    font-size:12px;
                  }
                  .display_table_expand{margin-top:' .
                        $top_margin .
                        'px !important;}
                  .right{text-align: center;}
                  .right_start{text-align: center;}
                  .header_info_title{width:75px;margin-bottom: 0px !important;}
                  .header_info_to_title{width:30px;margin-bottom: 0px !important;}
                  .footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;}
              </style>';
        }
        $html .= $request->get("htmlcontent") . "</body></html>";
        $pdf = PDF::loadHTML($html);
        $filename = time() . "_Invoice of " . $inv_no . ".pdf";
        $pdf->save("public/papers/" . $filename . "");

        $invoice_settings = DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
        $subject = 'Invoice ['.$inv_no.'] Attached';
        if($invoice_settings->include_practice == 1) {
            $subject = $practice_details->practice_name.' - Invoice ['.$inv_no.'] Attached';
        }
        echo json_encode(array("filename" => $filename, "subject" => $subject, "signature" => '<br/><br/><br/>'.$invoice_settings->email_signature));
    }
    public function email_invoice_submit(Request $request)
    {
        $from_input = $request->get("from");
        $toemails = $request->get("to") . "," . $request->get("cc");
        $sentmails = $request->get("to") . ", " . $request->get("cc");
        $subject = $request->get("subject");
        $message = $request->get("content");
        $client_id = $request->get("client_id");
        $attachments = "public/papers/" . $request->get("attachment");
        $explode = explode(",", $toemails);
        $data["sentmails"] = $sentmails;
        $i = 0;
        if (count($explode)) {
            foreach ($explode as $exp) {
                $to = trim($exp);
                $data["logo"] = getEmailLogo('invoice');
                $data["message"] = $message;
                $contentmessage = view("user/yearend_email_share_paper", $data);
                $email = new PHPMailer();
                $email->SetFrom($from_input); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                $email->AddAttachment($attachments, $request->get("attachment"));
                $email->Send();
                $i++;
            }
            $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("email", $from_input)->first();
            $client_details = \App\Models\CMClients::where(
                "client_id",
                $client_id
            )->first();
            $datamessage["message_id"] = time();
            $datamessage["message_from"] = $user_details->user_id;
            $datamessage["subject"] = $subject;
            $datamessage["message"] = $message;
            $datamessage["client_ids"] = $client_id;
            $datamessage["primary_emails"] = $client_details->email;
            $datamessage["secondary_emails"] = $client_details->email2;
            $datamessage["date_sent"] = date("Y-m-d H:i:s");
            $datamessage["date_saved"] = date("Y-m-d H:i:s");
            $datamessage["source"] = "Build Invoice";
            $datamessage["attachments"] = $attachments;
            $datamessage["status"] = 1;
            $datamessage['practice_code'] = Session::get('user_practice_code');
            \App\Models\Messageus::insert($datamessage);
            return Redirect::back()->with("message", "Email Sent Successfully");
        } else {
            return Redirect::back()->with(
                "error",
                "Email Field is empty so email is not sent"
            );
        }
    }
     public function edit_invoice_header_image(Request $request) {
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

                    DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
    }
    public function update_invoice_email_settings(Request $request) {
        $cc_email = $request->get('invoice_cc_email');
        $signature = $request->get('user_signature');
        $include_practice = $request->get('invoice_include_practice');
        $data['invoice_cc_email'] = $cc_email;
        $data['email_signature'] = $signature;
        $data['include_practice'] = $include_practice;

        $check_settings = DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        if($check_settings) {
              DB::table('invoice_system_settings')->where('id',$check_settings->id)->update($data);
        }
        else{
              $data['practice_code'] = Session::get('user_practice_code');
              DB::table('invoice_system_settings')->insert($data);
        }
        return redirect::back()->with('message', 'Build Invoice Setings Saved Sucessfully.');
    }
}
