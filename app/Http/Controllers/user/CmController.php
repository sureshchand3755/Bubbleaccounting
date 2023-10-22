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
class CmController extends Controller
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
    public function clientmanagement(Request $request)
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
                "email",
                "status",
                "active",
                "id",
                "address1",
                "address2",
                "address3",
                "address4",
                "address5",
                "phone",
                "statement"
            )
            ->orderBy("id", "asc")
            ->get();
        $class = \App\Models\CMClass::where("status", 0)->get();
        return view("user/cm/clients", [
            "title" => "Client Management",
            "clientlist" => $client,
            "classlist" => $class,
        ]);
    }
    public function addcmclients(Request $request)
    {
        $check_clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->orderBy("id", "desc")
            ->first();
        $last_client_id = substr($check_clients->client_id, 3);
        $nextClientid = sprintf("%03d", $last_client_id + 1);
        $pcodeval = Session::get('user_practice_code');
        $checkclientid = $pcodeval.$nextClientid;

        $clientid = checkCliendIdCmSystem($checkclientid);

        $data["client_id"] = $clientid;
        $data["firstname"] = $request->get("name");
        $data["surname"] = $request->get("surname");
        $firstname = $request->get("name");
        $surname = $request->get("surname");
        $company = $request->get("cname");
        if ($company != "") {
            $data["company"] = $company;
        } else {
            if ($surname != "") {
                $surname = " " . $surname;
            }
            $data["company"] = $firstname . $surname;
        }
        $data["address1"] = $request->get("address1");
        $data["address2"] = $request->get("address2");
        $data["address3"] = $request->get("address3");
        $data["address4"] = $request->get("address4");
        $data["address5"] = $request->get("address5");
        $data["email"] = $request->get("email");
        $data["tye"] = $request->get("tye");
        $data["active"] = $request->get("class") == "" ? "1" : $request->get("class");
        $data["tax_reg1"] = $request->get("tax_reg1");
        $data["tax_reg2"] = $request->get("tax_reg2");
        $data["tax_reg3"] = $request->get("tax_reg3");
        $data["email2"] = $request->get("semail");
        $data["phone"] = $request->get("phone");
        $data["linkcode"] = $request->get("linkcode");
        $data["cro"] = $request->get("cro");
        $data["ard"] = ($request->has('ard')) ? $request->get("ard") : '';
        $data["trade_status"] = $request->get("trade_status");
        $data["directory"] = $request->get("directory");
        $data["send_statement"] = 1;
        $data["practice_code"] = $request->get("practice_code");
        $check_fields = \App\Models\CMFields::where("status", 0)->get();
        if (count($check_fields)) {
            foreach ($check_fields as $fields) {
                if ($fields->field == 4) {
                    $filename = $_FILES[$fields->name]["name"];
                    $tmp_name = $_FILES[$fields->name]["tmp_name"];
                    $uploads_dir = "uploads/cm_fields";
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    $uploads_dir = $uploads_dir . "/" . $clientid;
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    $uploads_dir = $uploads_dir . "/" . $fields->name;
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    move_uploaded_file(
                        $tmp_name,
                        $uploads_dir . "/" . $filename
                    );
                    $data[$fields->name] = $filename;
                } else {
                    $data[$fields->name] = $request->get($fields->name);
                }
            }
            \App\Models\CMClients::insert($data);
        } else {
            \App\Models\CMClients::insert($data);
        }
        time_task_review_all_helper();
        return redirect::back()->with("message", "Client Added Successfully");
    }
    public function editcmclient(Request $request,$id = "")
    {
        $id = base64_decode($id);
        $result = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $output = "";
        $getfields = \App\Models\CMFields::where("status", 0)->get();
        if (count($getfields)) {
            $i = 1;
            $output .= "<tr>";
            foreach ($getfields as $field) {
                if ($i % 4 == 0) {
                    $output .= "</tr><tr>";
                }
                $output .=
                    '<td>
              		<label>' .
                    $field->name .
                    ' : </label>
                    <div class="form-group">';
                if ($field->field == 1) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="text" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '" value="' .
                        $result->$fieldval .
                        '">';
                } elseif ($field->field == 2) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="radio" name="' .
                        $field->name .
                        '" class="' .
                        $field->name .
                        '_add" id="' .
                        $field->name .
                        '_yes" value="yes" ';
                    if ($result->$fieldval == "yes") {
                        $output .= "checked";
                    }
                    $output .=
                        '><label for="' .
                        $field->name .
                        '_yes"> YES </label>
                        <input type="radio" name="' .
                        $field->name .
                        '" class="' .
                        $field->name .
                        '_add" id="' .
                        $field->name .
                        '_no" value="no" ';
                    if ($result->$fieldval == "no") {
                        $output .= "checked";
                    }
                    $output .=
                        '><label for="' . $field->name . '_no"> NO </label>';
                } elseif ($field->field == 3) {
                    $fieldval = $field->name;
                    $output .=
                        '<textarea name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '">' .
                        $result->$fieldval .
                        "</textarea>";
                } elseif ($field->field == 4) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="file" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '">';
                    if ($result->$fieldval != "") {
                        $output .=
                            '<a href="javascript:" class="fileattachment" data-element="' .
                            URL::to(
                                "uploads/cm_fields/" .
                                    $result->client_id .
                                    "/" .
                                    $fieldval .
                                    "/" .
                                    $result->$fieldval .
                                    ""
                            ) .
                            '">' .
                            $result->$fieldval .
                            "</a>";
                    }
                } elseif ($field->field == 5) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="email" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '" value="' .
                        $result->$fieldval .
                        '">';
                } elseif ($field->field == 6) {
                    $fieldval = $field->name;
                    $unserialize = unserialize($field->options);
                    $output .=
                        '<select name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add">
                          <option value="">Select ' .
                        $field->name .
                        "</option>";
                    if (count($unserialize)) {
                        foreach ($unserialize as $key => $arrayval) {
                            $output .= '<option value="' . $arrayval . '" ';
                            if ($result->$fieldval == $arrayval) {
                                $output .= "selected";
                            } else {
                                $output .= "";
                            }
                            $output .= ">" . $key . "</option>";
                        }
                    }
                    $output .= "</select>";
                }
                $output .= "</div></td>";
                $i++;
            }
            $output .= "</tr>";
        }
        echo json_encode([
            "clientid" => $result->client_id,
            "firstname" => $result->firstname,
            "surname" => $result->surname,
            "company" => $result->company,
            "address1" => $result->address1,
            "address2" => $result->address2,
            "address3" => $result->address3,
            "address4" => $result->address4,
            "address5" => $result->address5,
            "email" => $result->email,
            "tye" => $result->tye,
            "active" => $result->active,
            "tax_reg1" => $result->tax_reg1,
            "tax_reg2" => $result->tax_reg2,
            "tax_reg3" => $result->tax_reg3,
            "email2" => $result->email2,
            "phone" => $result->phone,
            "linkcode" => $result->linkcode,
            "cro" => $result->cro,
            "ard" => $result->ard,
            "trade_status" => $result->trade_status,
            "directory" => $result->directory,
            "status" => $result->status,
            "id" => $result->id,
            "htmlcontent" => $output,
            "employer_no" => $result->employer_no,
            "salutation" => $result->salutation,
            "practice_code" => $result->practice_code,
        ]);
    }
    public function copycmclient(Request $request,$id = "")
    {
        $id = base64_decode($id);
        $result = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $check_clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->orderBy("id", "desc")
            ->first();
        $last_client_id = substr($check_clients->client_id, 3);
        $nextClientid = sprintf("%03d", $last_client_id + 1);
        $pcodeval = Session::get('user_practice_code');
        $clientid = $pcodeval.$nextClientid;
        $fields = \App\Models\CMFields::where("status", 0)->get();
        $data2 = [];
        if (count($fields)) {
            foreach ($fields as $fld) {
                $fieldname = $fld->name;
                $data2[$fieldname] = $result->$fieldname;
            }
        }
        $data1 = [
            "clientid" => $clientid,
            "firstname" => $result->firstname,
            "surname" => $result->surname,
            "company" => $result->company,
            "address1" => $result->address1,
            "address2" => $result->address2,
            "address3" => $result->address3,
            "address4" => $result->address4,
            "address5" => $result->address5,
            "email" => $result->email,
            "tye" => $result->tye,
            "active" => $result->active,
            "tax_reg1" => $result->tax_reg1,
            "tax_reg2" => $result->tax_reg2,
            "tax_reg3" => $result->tax_reg3,
            "email2" => $result->email2,
            "phone" => $result->phone,
            "linkcode" => $result->linkcode,
            "cro" => $result->cro,
            "ard" => $result->ard,
            "trade_status" => $result->trade_status,
            "directory" => $result->directory,
            "id" => $result->id,
        ];
        $data3 = array_merge($data1, $data2);
        echo json_encode($data3);
    }
    public function updatecmclients(Request $request)
    {
        $id = $request->get("id");
        $data["client_added"] = $request->get("client_added_class");
        $data["firstname"] = $request->get("name");
        $data["surname"] = $request->get("surname");
        $data["company"] = $request->get("cname");
        $data["address1"] = $request->get("address1");
        $data["address2"] = $request->get("address2");
        $data["address3"] = $request->get("address3");
        $data["address4"] = $request->get("address4");
        $data["address5"] = $request->get("address5");
        $data["email"] = $request->get("email");
        $data["tye"] = $request->get("tye");
        $data["active"] = $request->get("class") == "" ? "1" : $request->get("class");
        $data["tax_reg1"] = $request->get("tax_reg1");
        $data["tax_reg2"] = $request->get("tax_reg2");
        $data["tax_reg3"] = $request->get("tax_reg3");
        $data["salutation"] = $request->get("salutation");
        $data["email2"] = $request->get("semail");
        $data["phone"] = $request->get("phone");
        $data["linkcode"] = $request->get("linkcode");
        $data["cro"] = $request->get("cro");
        $data["ard"] = $request->get("ard");
        $data["trade_status"] = $request->get("trade_status");
        $data["directory"] = $request->get("directory");
        $data["practice_code"] = $request->get("practice_code");
        $check_client = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $check_fields = \App\Models\CMFields::where("status", 0)->get();
        if (count($check_fields)) {
            foreach ($check_fields as $fields) {
                if ($fields->field == 4) {
                    $filename = $_FILES[$fields->name]["name"];
                    $tmp_name = $_FILES[$fields->name]["tmp_name"];
                    $uploads_dir = "uploads/cm_fields";
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    $uploads_dir =
                        $uploads_dir . "/" . $check_client->client_id;
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    $uploads_dir = $uploads_dir . "/" . $fields->name;
                    if (!file_exists($uploads_dir)) {
                        mkdir($uploads_dir);
                    }
                    move_uploaded_file(
                        $tmp_name,
                        $uploads_dir . "/" . $filename
                    );
                    $data[$fields->name] = $filename;
                } else {
                    $data[$fields->name] = $request->get($fields->name);
                }
            }
            \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("id", $id)
                ->update($data);
        } else {
            \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("id", $id)
                ->update($data);
        }
        time_task_review_all_helper();
        return redirect("user/client_management?divid=clientidtr_" . $id)->with(
            "edit_message",
            "Client Updated Successfully."
        );
    }
    public function clientmanagement_paginate(Request $request)
    {
        $page = $request->get("page");
        $prev_page = $page - 1;
        $offset = $prev_page * 1000;
        $clientlist = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->select(
                "client_id",
                "firstname",
                "surname",
                "company",
                "email",
                "status",
                "active",
                "id",
                "address1",
                "address2",
                "address3",
                "address4",
                "address5",
                "phone",
                "statement"
            )
            ->orderBy("id", "asc")
            ->offset($offset)
            ->limit(1000)
            ->get();
        $output = "";
        $i = $offset + 1;
        if (count($clientlist)) {
            foreach ($clientlist as $key => $client) {
                $address =
                    $client->address1 .
                    " " .
                    $client->address2 .
                    " " .
                    $client->address3 .
                    " " .
                    $client->address4 .
                    " " .
                    $client->address5;
                if ($client->status == 1) {
                    $disabled = "disabled";
                    $style = "color:red";
                } else {
                    $disabled = "";
                    if ($client->active != "") {
                        $check_color = \App\Models\CMClass::where(
                            "id",
                            $client->active
                        )->first();
                        $style = "color:#" . $check_color->classcolor . "";
                    } else {
                        $style = "color:#000";
                    }
                }
                if ($key == 999) {
                    $class_load = 'class="load_more"';
                } else {
                    $class_load = "";
                }
                $output .=
                    '<tr class="edit_task ' .
                    $disabled .
                    '">
	                <td style="' .
                    $style .
                    '">' .
                    $i .
                    '</td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->client_id .
                    '</a></td>
	                <td align="left" ' .
                    $class_load .
                    '><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->firstname .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->surname .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">';
                $output .=
                    $client->company == ""
                        ? $client->firstname . " " . $client->surname
                        : $client->company;
                $output .=
                    '</a></td>
	                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $address .
                    '</a></td>
	                <td align="left"><a style="' .
                    $style .
                    '" href="mailto:' .
                    $client->email .
                    '">' .
                    $client->email .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->phone .
                    '</a></td>
	                <td style="' .
                    $style .
                    '" align="left">
	                	<input type="checkbox" class="client_statement" id="statement_' .
                    $client->client_id .
                    '" data-element="' .
                    $client->id .
                    '"';
                $output .= $client->statement == "yes" ? "checked" : "";
                $output .=
                    '><label for="statement_' .
                    $client->client_id .
                    '">Yes</label>
	                </td>
                </tr>';
                $i++;
            }
        }
        if ($i == 1) {
            $output .= '<tr><td colspan="9" align="center">Empty</td></tr>';
        }
        echo $output;
    }
    public function cm_search_clients(Request $request)
    {
        $input = $request->get("input");
        $select = $request->get("select");
        $incomplete_details =\App\Models\userLogin::first();
        $output = "";
        if ($select == "address1") {
            $clientlist = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("address1", "like", "%" . $input . "%")
                ->orWhere("address2", "like", "%" . $input . "%")
                ->orWhere("address3", "like", "%" . $input . "%")
                ->orWhere("address4", "like", "%" . $input . "%")
                ->orWhere("address5", "like", "%" . $input . "%")
                ->get();
        } else {
            $clientlist = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where($select, "like", "%" . $input . "%")
                ->get();
        }
        $i = 1;
        if (count($clientlist)) {
            foreach ($clientlist as $client) {
                $address =
                    $client->address1 .
                    " " .
                    $client->address2 .
                    " " .
                    $client->address3 .
                    " " .
                    $client->address4 .
                    " " .
                    $client->address5;
                if ($client->status == 1) {
                    $disabled = "disabled";
                    $style = "color:red";
                } else {
                    $disabled = "";
                    if ($client->active != "") {
                        $check_color = \App\Models\CMClass::where(
                            "id",
                            $client->active
                        )->first();
                        $style = "color:#" . $check_color->classcolor . "";
                    } else {
                        $style = "color:#000";
                    }
                }
                $output .=
                    '<tr class="edit_task ' .
                    $disabled .
                    '">
	                <td style="' .
                    $style .
                    '">' .
                    $i .
                    '</td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->client_id .
                    '</a></td>
                    <td align="center">
                        <img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
                    </td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->firstname .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->surname .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">';
                $output .=
                    $client->company == ""
                        ? $client->firstname . " " . $client->surname
                        : $client->company;
                $output .=
                    '</a></td>
	                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $address .
                    '</a></td>
	                <td align="left"><a style="' .
                    $style .
                    '" href="mailto:' .
                    $client->email .
                    '">' .
                    $client->email .
                    '</a></td>
	                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->phone .
                    '</a></td>
	                <td style="' .
                    $style .
                    '" align="left">
	                	' .
                    $client->practice_code .
                    '
	                </td>
                </tr>';
                $i++;
            }
        }
        if ($i == 1) {
            $output .= '<tr><td colspan="9" align="center">Empty</td></tr>';
        }
        echo $output;
    }
    public function update_cm_incomplete_status(Request $request)
    {
        $data["cm_incomplete"] = $request->get("value");
       \App\Models\userLogin::where("userid", 1)->update($data);
    }
    public function cm_report_pdf(Request $request)
    {
        $ids = explode(",", $request->get("value"));
        $type = $request->get("type");
        $clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->whereIn("id", $ids)
            ->get();
        $output = "";
        if ($type == 2) {
            $output .= '<style>
					.table_style {
					    width: 100%;
					    border-collapse:collapse;
					    border:1px solid #c5c5c5;
					}
					</style>
			<table class="table_style">
				<tr>
				<td style="text-align: left;border:1px solid #000;">Client ID</td>
				<td style="text-align: left;border:1px solid #000;">FIrstName</td>
				<td style="text-align: left;border:1px solid #000;">Surname</td>
				<td style="text-align: left;border:1px solid #000;">Company</td>
				<td style="text-align: left;border:1px solid #000;">Address</td>
				<td style="text-align: left;border:1px solid #000;">EMail ID</td>
				</tr>';
            if (count($clients)) {
                foreach ($clients as $key => $client) {
                    $output .=
                        '<tr>
							<td style="text-align: left;border:1px solid #000;">' .
                        $client->client_id .
                        '</td>
							<td style="text-align: left;border:1px solid #000;">' .
                        $client->firstname .
                        '</td>
							<td style="text-align: left;border:1px solid #000;">' .
                        $client->surname .
                        '</td>
							<td style="text-align: left;border:1px solid #000;">';
                    $output .=
                        $client->company == ""
                            ? $client->firstname . " " . $client->surname
                            : $client->company;
                    $output .=
                        '</td>
							<td style="text-align: left;border:1px solid #000;">' .
                        $client->address1 .
                        " " .
                        $client->address2 .
                        " " .
                        $client->address3 .
                        " " .
                        $client->address4 .
                        " " .
                        $client->address5 .
                        '</td>
							<td style="text-align: left;border:1px solid #000;">' .
                        $client->email .
                        '</td>
						</tr>';
                }
            }
            $output .= "</table>";
            $end_id = count($ids) - 1;
            $first_client = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("id", $ids[0])
                ->first();
            $end_client = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("id", $ids[$end_id])
                ->first();
            if ($end_client) {
                $name =
                    "CM Report of " .
                    $first_client->client_id .
                    " - " .
                    $end_client->client_id .
                    ".pdf";
            } else {
                $name = "CM Report of " . $first_client->client_id . ".pdf";
            }
        }
        if ($type == 1) {
            $output .= '<style>
					.table_style {
					    width: 100%;
					    border-collapse:collapse;
					    padding:6%;
					    border:1px solid #c5c5c5;
					}
					.table_style td{
						padding:8px;
					}
					</style>';
            if (count($clients)) {
                foreach ($clients as $key => $client) {
                    $class_details = \App\Models\CMClass::where(
                        "id",
                        $client->active
                    )->first();
                    if (count($clients) == $key + 1) {
                        $style = "";
                    } else {
                        $style = "page-break-after: always";
                    }
                    $output .=
                        '<table class="table_style" style="' .
                        $style .
                        '"><tr>
						<td style="text-align: left;border:1px solid #000;">Client ID</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->client_id .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">First Name</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->firstname .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Surname</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->surname .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Company</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->company .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 1</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address1 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 2</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address2 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 3</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address3 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 4</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address4 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 5</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address5 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Primary Email</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->email .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Type</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->tye .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Class Name</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $class_details->classname .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg1</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->tax_reg1 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg2</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->tax_reg2 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg3</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->tax_reg3 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Secondary Email</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->email2 .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Phone</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->phone .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Link code</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->linkcode .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">cro</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->cro .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Ard</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->ard .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Trade Status</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->trade_status .
                        '</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Directory</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->directory .
                        '</td>
					</tr>';
                    $getfields = \App\Models\CMFields::where("status", 0)->get();
                    if (count($getfields)) {
                        foreach ($getfields as $field) {
                            $fieldname = $field->name;
                            if ($field->field == 6) {
                                $unserialize = unserialize($field->options);
                                $key = array_search(
                                    $client->$fieldname,
                                    $unserialize
                                );
                                $output .=
                                    '<tr>
									<td style="text-align: left;border:1px solid #000;">' .
                                    $fieldname .
                                    '</td>
									<td style="text-align: left;border:1px solid #000;">' .
                                    $key .
                                    '</td>
								</tr>';
                            } else {
                                $output .=
                                    '<tr>
									<td style="text-align: left;border:1px solid #000;">' .
                                    $fieldname .
                                    '</td>
									<td style="text-align: left;border:1px solid #000;">' .
                                    $client->$fieldname .
                                    '</td>
								</tr>';
                            }
                        }
                    }
                    $output .= "</table>";
                }
            }
        }
        $pdf = PDF::loadHTML($output);
        $pdf->setPaper("A4", "landscape");
        if ($type == 1) {
            $file =
                $clients[0]->client_id .
                "_" .
                $clients[0]->firstname .
                "_" .
                $clients[0]->surname .
                ".pdf";
            $pdf->save("public/papers/" . $file . "");
            echo $file;
        } elseif ($type == 2) {
            $pdf->save("public/papers/" . $name . "");
            echo $name;
        }
    }
    public function cm_report_pdf_type_2(Request $request)
    {
        $ids = explode(",", $request->get("value"));
        $type = $request->get("type");
        $clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->whereIn("id", $ids)
            ->get();
        $output = "";
        if ($type == 2) {
            if (count($clients)) {
                foreach ($clients as $key => $client) {
                    $output .=
                        '<tr>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->client_id .
                        '</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->firstname .
                        '</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->surname .
                        '</td>
						<td style="text-align: left;border:1px solid #000;">';
                    $output .=
                        $client->company == ""
                            ? $client->firstname . " " . $client->surname
                            : $client->company;
                    $output .=
                        '</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->address1 .
                        " " .
                        $client->address2 .
                        " " .
                        $client->address3 .
                        " " .
                        $client->address4 .
                        " " .
                        $client->address5 .
                        '</td>
						<td style="text-align: left;border:1px solid #000;">' .
                        $client->email .
                        '</td>
					</tr>';
                }
            }
        }
        echo $output;
    }
    public function download_report_pdfs(Request $request)
    {
        $htmlval = $request->get("htmlval");
        $pdf = PDF::loadHTML($htmlval);
        $pdf->setPaper("A4", "landscape");
        $pdf->save("public/papers/CM Report.pdf");
        echo "CM Report.pdf";
    }
    public function cm_report_csv(Request $request,$id = "")
    {
        $ids = explode(",", $request->get("value"));
        $clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->whereIn("id", $ids)
            ->get();
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=CM_Report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        $getfields = \App\Models\CMFields::where("status", 0)->get();
        $fieldname = [];
        if (count($getfields)) {
            foreach ($getfields as $field) {
                array_push($fieldname, $field->name);
            }
        }
        $columns_1 = [
            "#",
            "Client ID",
            "First Name",
            "Surname",
            "Company",
            "Address 1",
            "Address 2",
            "Address 3",
            "Address 4",
            "Address 5",
            "Primary Email",
            "Type",
            "Class Name",
            "Tax Reg1",
            "Tax Reg2",
            "Tax Reg3",
            "Secondary Email",
            "Phone",
            "Link code",
            "cro",
            "ard",
            "Trade Status",
            "Directory",
        ];
        $columns = array_merge($columns_1, $fieldname);
        $callback = function () use ($clients, $columns) {
            $file = fopen("public/papers/CM_Report.csv", "w");
            fputcsv($file, $columns);
            $i = 1;
            foreach ($clients as $single) {
                $class_details = \App\Models\CMClass::where("id", $single->active)->first();
                $getfields = \App\Models\CMFields::where("status", 0)->get();
                $fieldval = [];
                if (count($getfields)) {
                    foreach ($getfields as $field) {
                        $val = $field->name;
                        if ($val == "") {
                            array_push($fieldval, "N/A");
                        } else {
                            array_push($fieldval, $single->$val);
                        }
                    }
                }
                $columns_2 = [
                    $i,
                    $single->client_id,
                    $single->firstname,
                    $single->surname,
                    $single->company,
                    $single->address1,
                    $single->address2,
                    $single->address3,
                    $single->address4,
                    $single->address5,
                    $single->email,
                    $single->tye,
                    $class_details->classname,
                    $single->tax_reg1,
                    $single->tax_reg2,
                    $single->tax_reg3,
                    $single->email2,
                    $single->phone,
                    $single->linkcode,
                    $single->cro,
                    $single->ard,
                    $single->trade_status,
                    $single->directory,
                ];
                $columns_3 = array_merge($columns_2, $fieldval);
                fputcsv($file, $columns_3);
                $i++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
    public function cm_status_clients(Request $request)
    {
        $status = $request->get("hidden_status");
        $client = base64_decode($request->get("client_id"));
        if ($status == 1) {
            $data["active"] = 2;
        } elseif ($status == 0) {
            $data["active"] = 1;
        }
        \App\Models\CMClients::where("practice_code", Session::get("user_practice_code"))
            ->where("id", $client)
            ->update($data);
        echo 1;
    }
    public function save_image(Request $request)
    {
        $img = $_POST["data"];
        $img = str_replace("data:image/png;base64,", "", $img);
        $img = str_replace(" ", "+", $img);
        $fileData = base64_decode($img);
        //saving
        $fileName = "uploads/print_image/photo.png";
        file_put_contents($fileName, $fileData);
    }
    public function cm_print_details(Request $request)
    {
        if (file_exists("uploads/print_image/photo.png")) {
            unlink("uploads/print_image/photo.png");
        }
        $paper_details = \App\Models\CMPaper::where("status", 1)->first();
        $editid = $request->get("editid");
        $details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", base64_decode($editid))
            ->first();
        $output = '<table style="width:100%">';
        if ($paper_details->labels != "") {
            $explode = explode(",", $paper_details->labels);
            if (count($explode)) {
                foreach ($explode as $exp) {
                    if ($exp == "client_id") {
                        $name = "Client Id";
                    }
                    if ($exp == "firstname") {
                        $name = "Firstname";
                    }
                    if ($exp == "surname") {
                        $name = "Surname";
                    }
                    if ($exp == "company") {
                        $name = "Company";
                    }
                    if ($exp == "address") {
                        $name = "Address";
                    }
                    if ($exp == "email") {
                        $name = "Primary Email";
                    }
                    if ($exp == "tye") {
                        $name = "Type";
                    }
                    if ($exp == "active") {
                        $name = "Class";
                    }
                    if ($exp == "tax_reg1") {
                        $name = "Tax Reg1";
                    }
                    if ($exp == "tax_reg2") {
                        $name = "Tax Reg2";
                    }
                    if ($exp == "tax_reg3") {
                        $name = "Tax Reg3";
                    }
                    if ($exp == "email2") {
                        $name = "Secondary Email";
                    }
                    if ($exp == "phone") {
                        $name = "Phone";
                    }
                    if ($exp == "linkcode") {
                        $name = "Link Code";
                    }
                    if ($exp == "cro") {
                        $name = "Cro";
                    }
                    if ($exp == "ard") {
                        $name = "Ard";
                    }
                    if ($exp == "trade_status") {
                        $name = "Trade Status";
                    }
                    if ($exp == "directory") {
                        $name = "Directory";
                    }
                    if ($exp != "address") {
                        $output .=
                            '<tr>
							<td style="text-align: center;font-weight: 600;vertical-align: text-top;padding:5px">' .
                            $details->$exp .
                            '</td>
						</tr>';
                    } else {
                        $output .= '<tr>
							<td style="text-align: center;font-weight: 600;vertical-align: text-top;padding:5px">';
                        if ($details->address1 != "") {
                            $output .=
                                '<p style="line-height: 0.7;">' .
                                $details->address1 .
                                "</p>";
                        }
                        if ($details->address2 != "") {
                            $output .=
                                '<p style="line-height: 0.7;">' .
                                $details->address2 .
                                "</p>";
                        }
                        if ($details->address3 != "") {
                            $output .=
                                '<p style="line-height: 0.7;">' .
                                $details->address3 .
                                "</p>";
                        }
                        if ($details->address4 != "") {
                            $output .=
                                '<p style="line-height: 0.7;">' .
                                $details->address4 .
                                "</p>";
                        }
                        if ($details->address5 != "") {
                            $output .=
                                '<p style="line-height: 0.7;">' .
                                $details->address5 .
                                "</p>";
                        }
                        $output .= '</td>
						</tr>';
                    }
                }
            }
        }
        $output .= "</table>";
        echo json_encode([
            "width" => $paper_details->width,
            "height" => $paper_details->height,
            "htmlcontent" => $output,
        ]);
    }
    public function cm_upload(Request $request)
    {
        $images_arr = [];
        $output = "";
        foreach ($_FILES["images"]["name"] as $key => $val) {
            $target_dir = "uploads/cm_attachments/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }
            $target_file = $target_dir . $_FILES["images"]["name"][$key];
            if (
                move_uploaded_file(
                    $_FILES["images"]["tmp_name"][$key],
                    $target_file
                )
            ) {
                $images_arr[] = $target_file;
                $attch["attachment"] = $_FILES["images"]["name"][$key];
                $attch["url"] = $target_dir;
                \App\Models\CMEmailAttachment::insert($attch);
                $output .=
                    '<p class="email_attachments">' .
                    $_FILES["images"]["name"][$key] .
                    "</p>";
            }
        }
        echo $output;
    }
    public function cm_bulk_email(Request $request)
    {
        $client_id = $request->get("client_id");
        $secondary = $request->get("secondary");
        $content = $request->get("content");
        $sub = $request->get("subject");
        $time = $request->get("timeval");
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $client_id)
            ->first();
        $user_details =\App\Models\User::where('user_id',Session::get('userid'))->first();
        $from = $user_details->email;
        $data["logo"] = URL::to("public/assets/images/logo.png");
        $admin_cc = $user_details->email;
        if ($secondary == 1) {
            if ($client_details->email2 != "") {
                $sec_email = $client_details->email2;
                $data["sentemails"] =
                    $client_details->email .
                    " , " .
                    $sec_email .
                    " , " .
                    $admin_cc;
            } else {
                if ($client_details->email != "") {
                    $data["sentemails"] =
                        $client_details->email . " , " . $admin_cc;
                } else {
                    $data["sentemails"] = $admin_cc;
                }
            }
        } else {
            if ($client_details->email != "") {
                $data["sentemails"] =
                    $client_details->email . " , " . $admin_cc;
            } else {
                $data["sentemails"] = $admin_cc;
            }
        }
        $attachments = \App\Models\CMEmailAttachment::get();
        if (count($attachments)) {
            $data["count_attachment"] = 1;
        } else {
            $data["count_attachment"] = 0;
        }
        $data["content"] = $content;
        $contentmessage = view("user/cm_bulk_email", $data);
        if ($client_details->company == "") {
            $subject =
                "GBS & CO. Note: " .
                $sub .
                " " .
                $client_details->firstname .
                $client_details->surname;
        } else {
            $subject =
                "GBS & CO. Note: " . $sub . " " . $client_details->company;
        }
        if ($client_details->email != "") {
            $to = $client_details->email;
            $email = new PHPMailer();
            $email->SetFrom($from); //Name is optional
            $email->Subject = $subject;
            $email->Body = $contentmessage;
            $email->IsHTML(true);
            $email->AddAddress($to);
            $attachments = \App\Models\CMEmailAttachment::get();
            $attach = "";
            if (count($attachments)) {
                foreach ($attachments as $attachment) {
                    $path = $attachment->url . "/" . $attachment->attachment;
                    if ($attach == "") {
                        $attach = $path;
                    } else {
                        $attach = $attach . "||" . $path;
                    }
                    $email->AddAttachment($path, $attachment->attachment);
                }
            }
            $email->Send();
            $datamessage["message_id"] = $time;
            $datamessage["message_from"] = 0;
            $datamessage["subject"] = $subject;
            $datamessage["message"] = $contentmessage;
            $datamessage["client_ids"] = $client_id;
            $datamessage["primary_emails"] = $client_details->email;
            $datamessage["secondary_emails"] = $client_details->email2;
            $datamessage["date_sent"] = date("Y-m-d H:i:s");
            $datamessage["date_saved"] = date("Y-m-d H:i:s");
            $datamessage["source"] = "CM SYSTEM";
            $datamessage["attachments"] = $attach;
            $datamessage["status"] = 1;
            $datamessage['practice_code'] = Session::get('user_practice_code');
            \App\Models\Messageus::insert($datamessage);
        }
        if ($secondary == 1) {
            if ($client_details->email2 != "") {
                $to = $client_details->email2;
                $email = new PHPMailer();
                $email->SetFrom($from); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                $attachments = \App\Models\CMEmailAttachment::get();
                if (count($attachments)) {
                    foreach ($attachments as $attachment) {
                        $path =
                            $attachment->url . "/" . $attachment->attachment;
                        $email->AddAttachment($path, $attachment->attachment);
                    }
                }
                $email->Send();
            }
        }
        if ($client_details->email != "") {
            $to = $admin_cc;
            $email = new PHPMailer();
            $email->SetFrom($from); //Name is optional
            $email->Subject = $subject;
            $email->Body = $contentmessage;
            $email->IsHTML(true);
            $email->AddAddress($to);
            $attachments = \App\Models\CMEmailAttachment::get();
            if (count($attachments)) {
                foreach ($attachments as $attachment) {
                    $path = $attachment->url . "/" . $attachment->attachment;
                    $email->AddAttachment($path, $attachment->attachment);
                }
            }
            $email->Send();
        }
    }
    public function get_cm_report_clients(Request $request)
    {
        $clientlist = \App\Models\CMClients::where(
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
            ->get();
        $i = 1;
        $output = "";
        if (count($clientlist)) {
            foreach ($clientlist as $client) {
                if ($client->status == 1) {
                    $disabled = "disabled";
                    $style = "color:red";
                } else {
                    $disabled = "";
                    if ($client->active != "") {
                        $check_color = \App\Models\CMClass::where(
                            "id",
                            $client->active
                        )->first();
                        $style = "color:#" . $check_color->classcolor . "";
                    } else {
                        $style = "color:#000";
                    }
                }
                $output .=
                    '<tr >
		          <td style="' .
                    $style .
                    '">' .
                    $i .
                    '</td>
		          <td><input type="checkbox" name="select_client" class="select_client class_' .
                    $client->active .
                    '" data-element="' .
                    $client->active .
                    '" value="' .
                    $client->id .
                    '"><label>&nbsp</label></td>
		          <td style="' .
                    $style .
                    '" align="left">' .
                    $client->client_id .
                    '</td>
		          <td style="' .
                    $style .
                    '" align="left">' .
                    $client->firstname .
                    '</td>
		          <td style="' .
                    $style .
                    '" align="left">';
                $output .=
                    $client->company == ""
                        ? $client->firstname . " " . $client->surname
                        : $client->company;
                $output .= '</td>
		      </tr>';
                $i++;
            }
        }
        if ($i == 1) {
            $output .= '<tr><td colspan="9" align="center">Empty</td></tr>';
        }
        echo $output;
    }
    public function get_cm_bulk_clients(Request $request)
    {
        $clientlist = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->select(
                "client_id",
                "firstname",
                "surname",
                "company",
                "email",
                "status",
                "active",
                "id"
            )
            ->get();
        $i = 1;
        $output = "";
        if (count($clientlist)) {
            foreach ($clientlist as $client) {
                if ($client->status == 1) {
                    $disabled = "disabled";
                    $style = "color:red";
                    $hide_style = "display:none";
                    $hide_class = "deactive_tr";
                } else {
                    $disabled = "";
                    $hide_style = "";
                    $hide_class = "";
                    if ($client->active != "") {
                        $check_color = \App\Models\CMClass::where(
                            "id",
                            $client->active
                        )->first();
                        $style = "color:#" . $check_color->classcolor . "";
                    } else {
                        $style = "color:#000";
                    }
                }
                $output .=
                    '<tr class="' .
                    $hide_class .
                    '" style="' .
                    $hide_style .
                    '">
		          <td><input type="checkbox" name="select_email_client" class="select_email_client email_class_' .
                    $client->active .
                    '" data-element="' .
                    $client->active .
                    '" value="' .
                    $client->id .
                    '"><label>&nbsp</label></td>
		          <td style="' .
                    $style .
                    '" align="left">' .
                    $client->client_id .
                    '</td>
		          <td style="' .
                    $style .
                    '" align="left">' .
                    $client->firstname .
                    '</td>
		          <td style="' .
                    $style .
                    '" align="left">';
                $output .=
                    $client->company == ""
                        ? $client->firstname . " " . $client->surname
                        : $client->company;
                $output .= '</td>
		      	</tr>';
                $i++;
            }
        }
        if ($i == 1) {
            $output .= '<tr><td colspan="9" align="center">Empty</td></tr>';
        }
        echo $output;
    }
    public function import_new_clients(Request $request)
    {
        if ($_FILES["new_file"]["name"] != "") {
            $uploads_dir =
                dirname($_SERVER["SCRIPT_FILENAME"]) . "/uploads/importfiles";
            $tmp_name = $_FILES["new_file"]["tmp_name"];
            $name = $_FILES["new_file"]["name"];
            $errorlist = [];
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                $filepath = $uploads_dir . "/" . $name;
                $objPHPExcel = IOFactory::load($filepath);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

                    $nrColumns = ord($highestColumn) - 64;
                    if ($highestRow > 50) {
                        $height = 50;
                    } else {
                        $height = $highestRow;
                    }
                    $id = $worksheet->getCellByColumnAndRow(1, 1);
                    $id = trim($id->getValue());
                    $firstname = $worksheet->getCellByColumnAndRow(2, 1);
                    $firstname = trim($firstname->getValue());
                    $surname = $worksheet->getCellByColumnAndRow(3, 1);
                    $surname = trim($surname->getValue());
                    $company = $worksheet->getCellByColumnAndRow(4, 1);
                    $company = trim($company->getValue());
                    if (
                        $id == "ID" &&
                        $firstname == "First Name" &&
                        $surname == "Surname" &&
                        $company == "Company"
                    ) {
                        $errorrow = [];
                        $mainarray = [];
                        for ($row = 2; $row <= $height; ++$row) {
                            $id = $worksheet->getCellByColumnAndRow(1, $row);
                            $id = trim($id->getValue());
                            $pemail = $worksheet->getCellByColumnAndRow(
                                10,
                                $row
                            );
                            $pemail = trim($pemail->getValue());
                            if ($id == "") {
                            } else {
                                $check_gbsid = \App\Models\CMClients::where(
                                    "client_id",
                                    $id
                                )->first();
                                if (!$check_gbsid) {
                                    $data["client_id"] = $id;
                                    $firstname = $worksheet->getCellByColumnAndRow(
                                        2,
                                        $row
                                    );
                                    $data["firstname"] = trim(
                                        $firstname->getValue()
                                    );
                                    $firstname = trim($firstname->getValue());
                                    $surname = $worksheet->getCellByColumnAndRow(
                                        3,
                                        $row
                                    );
                                    $data["surname"] = trim(
                                        $surname->getValue()
                                    );
                                    $surname = trim($surname->getValue());
                                    $company = $worksheet->getCellByColumnAndRow(
                                        4,
                                        $row
                                    );
                                    $company = trim($company->getValue());
                                    if ($company != "") {
                                        $data["company"] = $company;
                                    } else {
                                        if ($surname != "") {
                                            $surname = " " . $surname;
                                        }
                                        $data["company"] =
                                            $firstname . $surname;
                                    }
                                    $address1 = $worksheet->getCellByColumnAndRow(
                                        5,
                                        $row
                                    );
                                    $data["address1"] = trim(
                                        $address1->getValue()
                                    );
                                    $address2 = $worksheet->getCellByColumnAndRow(
                                        6,
                                        $row
                                    );
                                    $data["address2"] = trim(
                                        $address2->getValue()
                                    );
                                    $address3 = $worksheet->getCellByColumnAndRow(
                                        7,
                                        $row
                                    );
                                    $data["address3"] = trim(
                                        $address3->getValue()
                                    );
                                    $address4 = $worksheet->getCellByColumnAndRow(
                                        8,
                                        $row
                                    );
                                    $data["address4"] = trim(
                                        $address4->getValue()
                                    );
                                    $address5 = $worksheet->getCellByColumnAndRow(
                                        9,
                                        $row
                                    );
                                    $data["address5"] = trim(
                                        $address5->getValue()
                                    );
                                    $email = $worksheet->getCellByColumnAndRow(
                                        10,
                                        $row
                                    );
                                    $data["email"] = trim($email->getValue());
                                    $tye = $worksheet->getCellByColumnAndRow(
                                        11,
                                        $row
                                    );
                                    $data["tye"] = trim($tye->getValue());
                                    $active = $worksheet->getCellByColumnAndRow(
                                        12,
                                        $row
                                    );
                                    $active = strtolower(
                                        trim($active->getValue())
                                    );
                                    $client_added = $worksheet->getCellByColumnAndRow(
                                        13,
                                        $row
                                    );
                                    $client_added = trim(
                                        $client_added->getValue()
                                    );
                                    if ($client_added == "") {
                                        $data["client_added"] = "";
                                    } else {
                                        $data["client_added"] = $client_added;
                                        // $date = date_create($client_added);
                                        //    date_add($date, date_interval_create_from_date_string("0 days"));
                                        //    $data['client_added'] = date_format($date, 'd-M-Y');
                                    }
                                    if (
                                        $active == "N" ||
                                        $active == "n" ||
                                        $active == "no" ||
                                        $active == "No" ||
                                        $active == "NO"
                                    ) {
                                        $data["active"] = 2;
                                    } else {
                                        $data["active"] = 1;
                                    }
                                    $tax_reg1 = $worksheet->getCellByColumnAndRow(
                                        14,
                                        $row
                                    );
                                    $data["tax_reg1"] = trim(
                                        $tax_reg1->getValue()
                                    );
                                    $tax_reg2 = $worksheet->getCellByColumnAndRow(
                                        15,
                                        $row
                                    );
                                    $data["tax_reg2"] = trim(
                                        $tax_reg2->getValue()
                                    );
                                    $tax_reg3 = $worksheet->getCellByColumnAndRow(
                                        16,
                                        $row
                                    );
                                    $data["tax_reg3"] = trim(
                                        $tax_reg3->getValue()
                                    );
                                    $semail = $worksheet->getCellByColumnAndRow(
                                        17,
                                        $row
                                    );
                                    $data["email2"] = trim($semail->getValue());
                                    $phone = $worksheet->getCellByColumnAndRow(
                                        18,
                                        $row
                                    );
                                    $data["phone"] = trim($phone->getValue());
                                    $linkcode = $worksheet->getCellByColumnAndRow(
                                        19,
                                        $row
                                    );
                                    $data["linkcode"] = trim(
                                        $linkcode->getValue()
                                    );
                                    $cro = $worksheet->getCellByColumnAndRow(
                                        20,
                                        $row
                                    );
                                    $data["cro"] = trim($cro->getValue());
                                    $ard = $worksheet->getCellByColumnAndRow(
                                        24,
                                        $row
                                    );
                                    $data["ard"] = trim($ard->getValue());
                                    $trade_status = $worksheet->getCellByColumnAndRow(
                                        21,
                                        $row
                                    );
                                    $data["trade_status"] = trim(
                                        $trade_status->getValue()
                                    );
                                    $directory = $worksheet->getCellByColumnAndRow(
                                        23,
                                        $row
                                    );
                                    $data["directory"] = trim(
                                        $directory->getValue()
                                    );
                                    $data["practice_code"] = Session::get(
                                        "user_practice_code"
                                    );
                                    $data["send_statement"] = 1;
                                    \App\Models\CMClients::insert($data);
                                }
                            }
                        }
                    } else {
                        return redirect("user/client_management")->with(
                            "message",
                            "Import Failed! Invalid Import File"
                        );
                    }
                }
                $out = "";
                if (count($errorlist)) {
                    foreach ($errorlist as $error) {
                        $out .= '<p class="error_class">' . $error . "</p>";
                    }
                }
                if ($height >= $highestRow) {
                    time_task_review_all_helper();
                    if ($out != "") {
                        return redirect("user/client_management")->with(
                            "success_error",
                            $out
                        );
                    } else {
                        return redirect("user/client_management")->with(
                            "message",
                            "Clients Imported successfully."
                        );
                    }
                } else {
                    return redirect(
                        "user/client_management?filename=" .
                            $name .
                            "&height=" .
                            $height .
                            "&round=2&highestrow=" .
                            $highestRow .
                            "&import_type_new=1&out=" .
                            $out .
                            ""
                    );
                }
            }
        }
    }
    public function import_new_clients_one(Request $request)
    {
        $name = $request->get("filename");
        $uploads_dir =
            dirname($_SERVER["SCRIPT_FILENAME"]) . "/uploads/importfiles";
        $filepath = $uploads_dir . "/" . $name;
        $objPHPExcel = IOFactory::load($filepath);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

            $nrColumns = ord($highestColumn) - 64;
            $round = $request->get("round");
            $last_height = $request->get("height");
            $offset = $round - 1;
            $offsetcount = $last_height + 1;
            $roundcount = $round * 50;
            $nextround = $round + 1;
            if ($highestRow > $roundcount) {
                $height = $roundcount;
            } else {
                $height = $highestRow;
            }
            $errorrow = [];
            $mainarray = [];
            $errorlist = [];
            for ($row = $offsetcount; $row <= $height; ++$row) {
                $id = $worksheet->getCellByColumnAndRow(1, $row);
                $id = trim($id->getValue());
                $pemail = $worksheet->getCellByColumnAndRow(10, $row);
                $pemail = trim($pemail->getValue());
                if ($id == "") {
                } else {
                    $check_gbsid = \App\Models\CMClients::where("client_id", $id)->first();
                    if (!$check_gbsid) {
                        $data["client_id"] = $id;
                        $firstname = $worksheet->getCellByColumnAndRow(2, $row);
                        $data["firstname"] = trim($firstname->getValue());
                        $firstname = trim($firstname->getValue());
                        $surname = $worksheet->getCellByColumnAndRow(3, $row);
                        $data["surname"] = trim($surname->getValue());
                        $surname = trim($surname->getValue());
                        $company = $worksheet->getCellByColumnAndRow(4, $row);
                        $company = trim($company->getValue());
                        if ($company != "") {
                            $data["company"] = $company;
                        } else {
                            if ($surname != "") {
                                $surname = " " . $surname;
                            }
                            $data["company"] = $firstname . $surname;
                        }
                        $address1 = $worksheet->getCellByColumnAndRow(5, $row);
                        $data["address1"] = trim($address1->getValue());
                        $address2 = $worksheet->getCellByColumnAndRow(6, $row);
                        $data["address2"] = trim($address2->getValue());
                        $address3 = $worksheet->getCellByColumnAndRow(7, $row);
                        $data["address3"] = trim($address3->getValue());
                        $address4 = $worksheet->getCellByColumnAndRow(8, $row);
                        $data["address4"] = trim($address4->getValue());
                        $address5 = $worksheet->getCellByColumnAndRow(9, $row);
                        $data["address5"] = trim($address5->getValue());
                        $email = $worksheet->getCellByColumnAndRow(10, $row);
                        $data["email"] = trim($email->getValue());
                        $tye = $worksheet->getCellByColumnAndRow(11, $row);
                        $data["tye"] = trim($tye->getValue());
                        $active = $worksheet->getCellByColumnAndRow(12, $row);
                        $active = strtolower(trim($active->getValue()));
                        $client_added = $worksheet->getCellByColumnAndRow(
                            13,
                            $row
                        );
                        $client_added = trim($client_added->getValue());
                        if ($client_added == "") {
                            $data["client_added"] = "";
                        } else {
                            $data["client_added"] = $client_added;
                            // $date = date_create($client_added);
                            //    date_add($date, date_interval_create_from_date_string("0 days"));
                            //    $data['client_added'] = date_format($date, 'd-M-Y');
                        }
                        if (
                            $active == "N" ||
                            $active == "n" ||
                            $active == "no" ||
                            $active == "No" ||
                            $active == "NO"
                        ) {
                            $data["active"] = 2;
                        } else {
                            $data["active"] = 1;
                        }
                        $tax_reg1 = $worksheet->getCellByColumnAndRow(14, $row);
                        $data["tax_reg1"] = trim($tax_reg1->getValue());
                        $tax_reg2 = $worksheet->getCellByColumnAndRow(15, $row);
                        $data["tax_reg2"] = trim($tax_reg2->getValue());
                        $tax_reg3 = $worksheet->getCellByColumnAndRow(16, $row);
                        $data["tax_reg3"] = trim($tax_reg3->getValue());
                        $semail = $worksheet->getCellByColumnAndRow(17, $row);
                        $data["email2"] = trim($semail->getValue());
                        $phone = $worksheet->getCellByColumnAndRow(18, $row);
                        $data["phone"] = trim($phone->getValue());
                        $linkcode = $worksheet->getCellByColumnAndRow(19, $row);
                        $data["linkcode"] = trim($linkcode->getValue());
                        $cro = $worksheet->getCellByColumnAndRow(20, $row);
                        $data["cro"] = trim($cro->getValue());
                        $ard = $worksheet->getCellByColumnAndRow(24, $row);
                        $data["ard"] = trim($ard->getValue());
                        $trade_status = $worksheet->getCellByColumnAndRow(
                            21,
                            $row
                        );
                        $data["trade_status"] = trim($trade_status->getValue());
                        $directory = $worksheet->getCellByColumnAndRow(
                            23,
                            $row
                        );
                        $data["directory"] = trim($directory->getValue());
                        $data["practice_code"] = Session::get(
                            "user_practice_code"
                        );
                        $data["send_statement"] = 1;
                        \App\Models\CMClients::insert($data);
                    }
                }
            }
        }
        $out = $request->get("out");
        if (count($errorlist)) {
            foreach ($errorlist as $error) {
                $out .= '<p class="error_class">' . $error . "</p>";
            }
        }
        if ($height >= $highestRow) {
            time_task_review_all_helper();
            if ($out != "") {
                return redirect("user/client_management")->with(
                    "success_error",
                    $out
                );
            } else {
                return redirect("user/client_management")->with(
                    "message",
                    "Clients Imported successfully."
                );
            }
        } else {
            return redirect(
                "user/client_management?filename=" .
                    $name .
                    "&height=" .
                    $height .
                    "&round=" .
                    $nextround .
                    "&highestrow=" .
                    $highestRow .
                    "&out=" .
                    $out .
                    "&import_type_new=1"
            );
        }
    }
    public function import_existing_clients(Request $request)
    {
        $checkbox = $request->get("import_field");
        $check = implode(",", $checkbox);
        if ($_FILES["exists_file"]["name"] != "") {
            $uploads_dir =
                dirname($_SERVER["SCRIPT_FILENAME"]) . "/uploads/importfiles";
            $tmp_name = $_FILES["exists_file"]["tmp_name"];
            $name = $_FILES["exists_file"]["name"];
            $errorlist = [];
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                $filepath = $uploads_dir . "/" . $name;
                $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $objReader->setInputEncoding("ISO-8859-1");
                $objPHPExcel = $objReader->load($filepath);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

                    $nrColumns = ord($highestColumn) - 64;
                    if ($highestRow > 50) {
                        $height = 50;
                    } else {
                        $height = $highestRow;
                    }
                    $id = $worksheet->getCellByColumnAndRow(1, 1);
                    $id = trim($id->getValue());
                    $firstname = $worksheet->getCellByColumnAndRow(2, 1);
                    $firstname = trim($firstname->getValue());
                    $surname = $worksheet->getCellByColumnAndRow(3, 1);
                    $surname = trim($surname->getValue());
                    $company = $worksheet->getCellByColumnAndRow(4, 1);
                    $company = trim($company->getValue());
                    if (
                        $id == "ID" &&
                        $firstname == "First Name" &&
                        $surname == "Surname" &&
                        $company == "Company"
                    ) {
                        $errorrow = [];
                        $mainarray = [];
                        for ($row = 2; $row <= $height; ++$row) {
                            $id = $worksheet->getCellByColumnAndRow(1, $row);
                            $id = trim($id->getValue());
                            $pemail = $worksheet->getCellByColumnAndRow(
                                10,
                                $row
                            );
                            $pemail = trim($pemail->getValue());
                            if ($id == "") {
                            } else {
                                $check_gbsid = \App\Models\CMClients::where(
                                    "client_id",
                                    $id
                                )->first();
                                $firstname = $worksheet->getCellByColumnAndRow(
                                    2,
                                    $row
                                );
                                $data["firstname"] = trim(
                                    $firstname->getValue()
                                );
                                $firstname = trim($firstname->getValue());
                                $surname = $worksheet->getCellByColumnAndRow(
                                    3,
                                    $row
                                );
                                $data["surname"] = trim($surname->getValue());
                                $surname = trim($surname->getValue());
                                if (
                                    $worksheet
                                        ->getCellByColumnAndRow(4, $row)
                                        ->getValue() instanceof
                                    PHPExcel_RichText
                                ) {
                                    $company = $worksheet
                                        ->getCellByColumnAndRow(4, $row)
                                        ->getValue()
                                        ->getPlainText();
                                } else {
                                    $company = $worksheet
                                        ->getCellByColumnAndRow(4, $row)
                                        ->getValue();
                                }
                                //$company = $worksheet->getCellByColumnAndRow(4, $row); $company = trim($company->getValue()->getPlainText());
                                if ($company != "") {
                                    $data["company"] = $company;
                                } else {
                                    if ($surname != "") {
                                        $surname = " " . $surname;
                                    }
                                    $data["company"] = $firstname . $surname;
                                }
                                $address1 = $worksheet->getCellByColumnAndRow(
                                    5,
                                    $row
                                );
                                $data["address1"] = trim($address1->getValue());
                                $address2 = $worksheet->getCellByColumnAndRow(
                                    6,
                                    $row
                                );
                                $data["address2"] = trim($address2->getValue());
                                $address3 = $worksheet->getCellByColumnAndRow(
                                    7,
                                    $row
                                );
                                $data["address3"] = trim($address3->getValue());
                                $address4 = $worksheet->getCellByColumnAndRow(
                                    8,
                                    $row
                                );
                                $data["address4"] = trim($address4->getValue());
                                $address5 = $worksheet->getCellByColumnAndRow(
                                    9,
                                    $row
                                );
                                $data["address5"] = trim($address5->getValue());
                                $email = $worksheet->getCellByColumnAndRow(
                                    10,
                                    $row
                                );
                                $data["email"] = trim($email->getValue());
                                $tye = $worksheet->getCellByColumnAndRow(
                                    11,
                                    $row
                                );
                                $data["tye"] = trim($tye->getValue());
                                $active = $worksheet->getCellByColumnAndRow(
                                    12,
                                    $row
                                );
                                $active = strtolower(trim($active->getValue()));
                                $client_added = $worksheet->getCellByColumnAndRow(
                                    13,
                                    $row
                                );
                                $client_added = trim($client_added->getValue());
                                if ($client_added == "") {
                                    $data["client_added"] = "";
                                } else {
                                    $data["client_added"] = $client_added;
                                    // $date = date_create($client_added);
                                    //    date_add($date, date_interval_create_from_date_string("0 days"));
                                    //    $data['client_added'] = date_format($date, 'd-M-Y');
                                }
                                if (
                                    $active == "N" ||
                                    $active == "n" ||
                                    $active == "no" ||
                                    $active == "No" ||
                                    $active == "NO"
                                ) {
                                    $data["active"] = 2;
                                } else {
                                    $data["active"] = 1;
                                }
                                $tax_reg1 = $worksheet->getCellByColumnAndRow(
                                    14,
                                    $row
                                );
                                $data["tax_reg1"] = trim($tax_reg1->getValue());
                                $tax_reg2 = $worksheet->getCellByColumnAndRow(
                                    15,
                                    $row
                                );
                                $data["tax_reg2"] = trim($tax_reg2->getValue());
                                $tax_reg3 = $worksheet->getCellByColumnAndRow(
                                    16,
                                    $row
                                );
                                $data["tax_reg3"] = trim($tax_reg3->getValue());
                                $semail = $worksheet->getCellByColumnAndRow(
                                    17,
                                    $row
                                );
                                $data["email2"] = trim($semail->getValue());
                                $phone = $worksheet->getCellByColumnAndRow(
                                    18,
                                    $row
                                );
                                $data["phone"] = trim($phone->getValue());
                                $linkcode = $worksheet->getCellByColumnAndRow(
                                    19,
                                    $row
                                );
                                $data["linkcode"] = trim($linkcode->getValue());
                                $cro = $worksheet->getCellByColumnAndRow(
                                    20,
                                    $row
                                );
                                $data["cro"] = trim($cro->getValue());
                                $ard = $worksheet->getCellByColumnAndRow(
                                    24,
                                    $row
                                );
                                $data["ard"] = trim($ard->getValue());
                                $trade_status = $worksheet->getCellByColumnAndRow(
                                    21,
                                    $row
                                );
                                $data["trade_status"] = trim(
                                    $trade_status->getValue()
                                );
                                $directory = $worksheet->getCellByColumnAndRow(
                                    23,
                                    $row
                                );
                                $data["directory"] = trim(
                                    $directory->getValue()
                                );
                                $data["practice_code"] = Session::get(
                                    "user_practice_code"
                                );
                                if (!$check_gbsid) {
                                    $data["client_id"] = $id;
                                    $data["send_statement"] = 1;
                                    \App\Models\CMClients::insert($data);
                                } else {
                                    if (count($checkbox)) {
                                        foreach ($checkbox as $field) {
                                            $update[$field] = $data[$field];
                                        }
                                        \App\Models\CMClients::where(
                                            "practice_code",
                                            Session::get("user_practice_code")
                                        )
                                            ->where("id", $check_gbsid->id)
                                            ->update($update);
                                    }
                                }
                            }
                        }
                    } else {
                        return redirect("user/client_management")->with(
                            "message",
                            "Import Failed! Invalid Import File"
                        );
                    }
                }
                $out = "";
                if (count($errorlist)) {
                    foreach ($errorlist as $error) {
                        $out .= '<p class="error_class">' . $error . "</p>";
                    }
                }
                if ($height >= $highestRow) {
                    time_task_review_all_helper();
                    if ($out != "") {
                        return redirect("user/client_management")->with(
                            "success_error",
                            $out
                        );
                    } else {
                        return redirect("user/client_management")->with(
                            "message",
                            "Clients Imported successfully."
                        );
                    }
                } else {
                    return redirect(
                        "user/client_management?filename=" .
                            $name .
                            "&height=" .
                            $height .
                            "&round=2&highestrow=" .
                            $highestRow .
                            "&import_type_existing=1&out=" .
                            $out .
                            "&checkbox=" .
                            $check .
                            ""
                    );
                }
            }
        }
    }
    public function import_existing_clients_one(Request $request)
    {
        $check = $request->get("checkbox");
        $checkbox = explode(",", $check);
        $name = $request->get("filename");
        $uploads_dir =
            dirname($_SERVER["SCRIPT_FILENAME"]) . "/uploads/importfiles";
        $filepath = $uploads_dir . "/" . $name;
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $objReader->setInputEncoding("ISO-8859-1");
        $objPHPExcel = $objReader->load($filepath);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

            $nrColumns = ord($highestColumn) - 64;
            $round = $request->get("round");
            $last_height = $request->get("height");
            $offset = $round - 1;
            $offsetcount = $last_height + 1;
            $roundcount = $round * 50;
            $nextround = $round + 1;
            if ($highestRow > $roundcount) {
                $height = $roundcount;
            } else {
                $height = $highestRow;
            }
            $errorrow = [];
            $mainarray = [];
            $errorlist = [];
            for ($row = $offsetcount; $row <= $height; ++$row) {
                $id = $worksheet->getCellByColumnAndRow(1, $row);
                $id = trim($id->getValue());
                $pemail = $worksheet->getCellByColumnAndRow(10, $row);
                $pemail = trim($pemail->getValue());
                if ($id == "") {
                } else {
                    $check_gbsid = \App\Models\CMClients::where("client_id", $id)->first();
                    $firstname = $worksheet->getCellByColumnAndRow(2, $row);
                    $data["firstname"] = trim($firstname->getValue());
                    $firstname = trim($firstname->getValue());
                    $surname = $worksheet->getCellByColumnAndRow(3, $row);
                    $data["surname"] = trim($surname->getValue());
                    $surname = trim($surname->getValue());
                    if (
                        $worksheet
                            ->getCellByColumnAndRow(4, $row)
                            ->getValue() instanceof PHPExcel_RichText
                    ) {
                        $company = $worksheet
                            ->getCellByColumnAndRow(4, $row)
                            ->getValue()
                            ->getPlainText();
                    } else {
                        $company = $worksheet
                            ->getCellByColumnAndRow(4, $row)
                            ->getValue();
                    }
                    if ($company != "") {
                        $data["company"] = $company;
                    } else {
                        if ($surname != "") {
                            $surname = " " . $surname;
                        }
                        $data["company"] = $firstname . $surname;
                    }
                    $address1 = $worksheet->getCellByColumnAndRow(5, $row);
                    $data["address1"] = trim($address1->getValue());
                    $address2 = $worksheet->getCellByColumnAndRow(6, $row);
                    $data["address2"] = trim($address2->getValue());
                    $address3 = $worksheet->getCellByColumnAndRow(7, $row);
                    $data["address3"] = trim($address3->getValue());
                    $address4 = $worksheet->getCellByColumnAndRow(8, $row);
                    $data["address4"] = trim($address4->getValue());
                    $address5 = $worksheet->getCellByColumnAndRow(9, $row);
                    $data["address5"] = trim($address5->getValue());
                    $email = $worksheet->getCellByColumnAndRow(10, $row);
                    $data["email"] = trim($email->getValue());
                    $tye = $worksheet->getCellByColumnAndRow(11, $row);
                    $data["tye"] = trim($tye->getValue());
                    $active = $worksheet->getCellByColumnAndRow(12, $row);
                    $active = strtolower(trim($active->getValue()));
                    $client_added = $worksheet->getCellByColumnAndRow(13, $row);
                    $client_added = trim($client_added->getValue());
                    if ($client_added == "") {
                        $data["client_added"] = "";
                    } else {
                        $data["client_added"] = $client_added;
                        // $date = date_create($client_added);
                        //    date_add($date, date_interval_create_from_date_string("0 days"));
                        //    $data['client_added'] = date_format($date, 'd-M-Y');
                    }
                    if (
                        $active == "N" ||
                        $active == "n" ||
                        $active == "no" ||
                        $active == "No" ||
                        $active == "NO"
                    ) {
                        $data["active"] = 2;
                    } else {
                        $data["active"] = 1;
                    }
                    $tax_reg1 = $worksheet->getCellByColumnAndRow(14, $row);
                    $data["tax_reg1"] = trim($tax_reg1->getValue());
                    $tax_reg2 = $worksheet->getCellByColumnAndRow(15, $row);
                    $data["tax_reg2"] = trim($tax_reg2->getValue());
                    $tax_reg3 = $worksheet->getCellByColumnAndRow(16, $row);
                    $data["tax_reg3"] = trim($tax_reg3->getValue());
                    $semail = $worksheet->getCellByColumnAndRow(17, $row);
                    $data["email2"] = trim($semail->getValue());
                    $phone = $worksheet->getCellByColumnAndRow(18, $row);
                    $data["phone"] = trim($phone->getValue());
                    $linkcode = $worksheet->getCellByColumnAndRow(19, $row);
                    $data["linkcode"] = trim($linkcode->getValue());
                    $cro = $worksheet->getCellByColumnAndRow(20, $row);
                    $data["cro"] = trim($cro->getValue());
                    $ard = $worksheet->getCellByColumnAndRow(24, $row);
                    $data["ard"] = trim($ard->getValue());
                    $trade_status = $worksheet->getCellByColumnAndRow(21, $row);
                    $data["trade_status"] = trim($trade_status->getValue());
                    $directory = $worksheet->getCellByColumnAndRow(23, $row);
                    $data["directory"] = trim($directory->getValue());
                    $data["practice_code"] = Session::get("user_practice_code");
                    if (!$check_gbsid) {
                        $data["client_id"] = $id;
                        $data["send_statement"] = 1;
                        \App\Models\CMClients::insert($data);
                    } else {
                        if (count($checkbox)) {
                            foreach ($checkbox as $field) {
                                $update[$field] = $data[$field];
                            }
                            \App\Models\CMClients::where(
                                "practice_code",
                                Session::get("user_practice_code")
                            )
                                ->where("id", $check_gbsid->id)
                                ->update($update);
                        }
                    }
                }
            }
        }
        $out = $request->get("out");
        if (count($errorlist)) {
            foreach ($errorlist as $error) {
                $out .= '<p class="error_class">' . $error . "</p>";
            }
        }
        if ($height >= $highestRow) {
            time_task_review_all_helper();
            if ($out != "") {
                return redirect("user/client_management")->with(
                    "success_error",
                    $out
                );
            } else {
                return redirect("user/client_management")->with(
                    "message",
                    "Clients Imported successfully."
                );
            }
        } else {
            return redirect(
                "user/client_management?filename=" .
                    $name .
                    "&height=" .
                    $height .
                    "&round=" .
                    $nextround .
                    "&highestrow=" .
                    $highestRow .
                    "&out=" .
                    $out .
                    "&import_type_existing=1&checkbox=" .
                    $check .
                    ""
            );
        }
    }
    public function cm_statement_update(Request $request)
    {
        $value = $request->get("value");
        $id = $request->get("id");
        \App\Models\CMClients::where("practice_code", Session::get("user_practice_code"))
            ->where("id", $id)
            ->update(["statement" => $value]);
    }
    public function cm_client_invoice(Request $request)
    {
        $id = base64_decode($request->get("id"));
        $client_id = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $payrolllist = \App\Models\payrollTasks::where("client_id", $client_id->client_id)
            ->orderBy("update_time", "DESC")
            ->get();
        $result = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $output = "";
        $getfields = \App\Models\CMFields::where("status", 0)->get();
        if (count($getfields)) {
            $i = 1;
            $output .= "<tr>";
            foreach ($getfields as $field) {
                if ($i % 4 == 0) {
                    $output .= "</tr><tr>";
                }
                $output .=
                    '<td>
          		<label>' .
                    $field->name .
                    ' : </label>
                <div class="form-group">';
                if ($field->field == 1) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="text" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '" value="' .
                        $result->$fieldval .
                        '">';
                } elseif ($field->field == 2) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="radio" name="' .
                        $field->name .
                        '" class="' .
                        $field->name .
                        '_add" id="' .
                        $field->name .
                        '_yes" value="yes" ';
                    if ($result->$fieldval == "yes") {
                        $output .= "checked";
                    }
                    $output .=
                        '><label for="' .
                        $field->name .
                        '_yes"> YES </label>
                    <input type="radio" name="' .
                        $field->name .
                        '" class="' .
                        $field->name .
                        '_add" id="' .
                        $field->name .
                        '_no" value="no" ';
                    if ($result->$fieldval == "no") {
                        $output .= "checked";
                    }
                    $output .=
                        '><label for="' . $field->name . '_no"> NO </label>';
                } elseif ($field->field == 3) {
                    $fieldval = $field->name;
                    $output .=
                        '<textarea name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '">' .
                        $result->$fieldval .
                        "</textarea>";
                } elseif ($field->field == 4) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="file" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '">';
                    if ($result->$fieldval != "") {
                        $output .=
                            '<a href="javascript:" class="fileattachment" data-element="' .
                            URL::to(
                                "uploads/cm_fields/" .
                                    $result->client_id .
                                    "/" .
                                    $fieldval .
                                    "/" .
                                    $result->$fieldval .
                                    ""
                            ) .
                            '">' .
                            $result->$fieldval .
                            "</a>";
                    }
                } elseif ($field->field == 5) {
                    $fieldval = $field->name;
                    $output .=
                        '<input type="email" name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add" placeholder="Enter ' .
                        $field->name .
                        '" value="' .
                        $result->$fieldval .
                        '">';
                } elseif ($field->field == 6) {
                    $fieldval = $field->name;
                    $unserialize = unserialize($field->options);
                    $output .=
                        '<select name="' .
                        $field->name .
                        '" class="form-control ' .
                        $field->name .
                        '_add">
                      <option value="">Select ' .
                        $field->name .
                        "</option>";
                    if (count($unserialize)) {
                        foreach ($unserialize as $key => $arrayval) {
                            $output .= '<option value="' . $arrayval . '" ';
                            if ($result->$fieldval == $arrayval) {
                                $output .= "selected";
                            } else {
                                $output .= "";
                            }
                            $output .= ">" . $key . "</option>";
                        }
                    }
                    $output .= "</select>";
                }
                $output .= "</div></td>";
                $i++;
            }
            $output .= "</tr>";
        }
        $clientid = $client_id->client_id;
        $timetasklist = \App\Models\timeTask::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("clients", "like", "%" . $clientid . "%")
            ->get();
        $i = 1;
        $outputtimetask = '<table class="display nowrap fullviewtablelist" id="timetask_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;" width="10%">S.No</th>
                      <th style="text-align: left;">Task Name</th>
                  </tr>
                </thead>
                <tbody>
        ';
        if (count($timetasklist)) {
            foreach ($timetasklist as $key => $timetask) {
                $outputtimetask .=
                    '
        			<tr>
        				<td>' .
                    $i .
                    '</td>
        				<td>' .
                    $timetask->task_name .
                    '</td>
        			</tr>
        		';
                $i++;
            }
        }
        if ($i == 1) {
            $outputtimetask .= "<tr><td></td><td>Empty</td></tr>";
        }
        $outputtimetask .= "</tbody></table>";
        $output_payroll = '<table class="display nowrap fullviewtablelist" id="payroll_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th width="2%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Task Name</th>
                      <th style="text-align: left;">Year</th>
                      <th style="text-align: left;">Period</th>
                      <th style="text-align: left;">Email Sent</th>
                      <th style="text-align: left;">When the Task is Marked Complete</th>
                  </tr>
                </thead>
                <tbody>';
        $i = 1;
        if (count($payrolllist)) {
            foreach ($payrolllist as $payroll) {
                $task_details = \App\Models\task::where(
                    "task_id",
                    $payroll->task_id
                )->first();
                if ($task_details) {
                    $taskname = $task_details->task_name;
                } else {
                    $taskname = "";
                }
                $year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where("year_id", $payroll->year)->first();
                if ($payroll->month == 0) {
                    $week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where("week_id", $payroll->week)->first();
                    $period = $week->week;
                    $text = "Week";
                } else {
                    $month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where("month_id", $payroll->month)->first();
                    $period = $month->month;
                    $text = "Month";
                }
                if ($payroll->email_sent != "0000-00-00 00:00:00") {
                    $unsentfile = date(
                        "d F Y @ H : i",
                        strtotime($payroll->email_sent)
                    );
                } else {
                    $unsentfile = "N/A";
                }
                $output_payroll .=
                    '
					<tr>
						<td>' .
                    $i .
                    '</td>
						<td align="left">' .
                    $taskname .
                    '</td>
						<td align="left">' .
                    $year->year_name .
                    '</td>
						<td align="left">' .
                    $text .
                    " " .
                    $period .
                    '</td>
						<td align="left">' .
                    $unsentfile .
                    '</td>
						<td align="left">' .
                    date("d F Y @ H : i", strtotime($payroll->update_time)) .
                    '</td>
					</tr>
				';
                $i++;
            }
        }
        if ($i == 1) {
            $output_payroll .=
                "<tr><td></td><td></td><td>Empty</td><td></td><td></td><td></td></tr>";
        }
        $output_payroll .= '
                </tbody>
            </table>';
        $aml_bank_list = \App\Models\AmlBank::where("client_id", $result->client_id)->get();
        $outputbank = '<table class="display nowrap fullviewtablelist"  id="bank_expand_cms">
            <thead>
              <th>#</th>
              <th>Bank Name</th>
              <th>Account Name</th>
              <th>Account Number</th>
            </thead>
            <tbody>';
        $ibank = 1;
        if (count($aml_bank_list)) {
            foreach ($aml_bank_list as $bank) {
                $outputbank .=
                    '
				<tr>
					<td>' .
                    $ibank .
                    '</td>
					<td>' .
                    $bank->bank_name .
                    '</td>
					<td>' .
                    $bank->account_name .
                    '</td>
					<td>' .
                    $bank->account_number .
                    '</td>
				</tr>';
                $ibank++;
            }
        } else {
            $outputbank .= '<tr>
				<td></td>
				<td>Empty</td>
				<td></td>
				<td></td>
			</tr>';
        }
        $outputbank .= '</tbody>
            </table>';
        $current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->orderBy("week_id", "desc")->first();
        $current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->orderBy("month_id", "desc")->first();
        $outputmodule = '<table class="display nowrap fullviewtablelist" id="module_expand">
	            <thead>
					<th style="text-align:left">#</th>
					<th style="text-align:left">Module</th>
					<th style="text-align:left">Salute</th>
					<th style="text-align:left">Primary Email</th>
					<th style="text-align:left">Secondary Email</th>
					<th style="text-align:left">Action</th>
	            </thead>
            <tbody>';
        $week_tasks = \App\Models\task::where("client_id", $result->client_id)
            ->where("task_week", $current_week->week_id)
            ->get();
        $month_tasks = \App\Models\task::where("client_id", $result->client_id)
            ->where("task_month", $current_month->month_id)
            ->get();
        $vats =\App\Models\vatClients::where("cm_client_id", $result->client_id)->get();
        $statement = \App\Models\ClientStatement::where(
            "client_id",
            $result->client_id
        )->first();
        $i = 1;
        if (count($week_tasks)) {
            foreach ($week_tasks as $task) {
                $outputmodule .=
                    '<tr class="pms_module_' .
                    $task->task_id .
                    '">
					<td>' .
                    $i .
                    '</td>
					<td>PMS - Weekly</td>
					<td class="salutation_mod">' .
                    $task->salutation .
                    '</td>
					<td class="primary_mod">' .
                    $task->task_email .
                    '</td>
					<td class="secondary_mod">' .
                    $task->secondary_email .
                    '</td>
					<td><a href="javascript:" class="edit_task_module" data-element="' .
                    $task->task_id .
                    '" data-type="1" data-salutation="' .
                    $task->salutation .
                    '" data-primary="' .
                    $task->task_email .
                    '" data-secondary="' .
                    $task->secondary_email .
                    '">Edit</a></td>
				</tr>';
                $i++;
            }
        }
        if (count($month_tasks)) {
            foreach ($month_tasks as $task) {
                $outputmodule .=
                    '<tr class="pms_module_' .
                    $task->task_id .
                    '">
					<td>' .
                    $i .
                    '</td>
					<td>PMS - Monthly</td>
					<td class="salutation_mod">' .
                    $task->salutation .
                    '</td>
					<td class="primary_mod">' .
                    $task->task_email .
                    '</td>
					<td class="secondary_mod">' .
                    $task->secondary_email .
                    '</td>
					<td><a href="javascript:" class="edit_task_module" data-element="' .
                    $task->task_id .
                    '" data-type="1" data-salutation="' .
                    $task->salutation .
                    '" data-primary="' .
                    $task->task_email .
                    '" data-secondary="' .
                    $task->secondary_email .
                    '">Edit</a></td>
				</tr>';
                $i++;
            }
        }
        if (count($vats)) {
            foreach ($vats as $vat) {
                $outputmodule .=
                    '<tr class="vat_module_' .
                    $vat->client_id .
                    '">
					<td>' .
                    $i .
                    '</td>
					<td>Vat System</td>
					<td class="salutation_mod">' .
                    $vat->salutation .
                    '</td>
					<td class="primary_mod">' .
                    $vat->pemail .
                    '</td>
					<td class="secondary_mod">' .
                    $vat->semail .
                    '</td>
					<td><a href="javascript:" class="edit_task_module" data-element="' .
                    $vat->client_id .
                    '" data-type="2" data-salutation="' .
                    $vat->salutation .
                    '" data-primary="' .
                    $vat->pemail .
                    '" data-secondary="' .
                    $vat->semail .
                    '">Edit</a></td>
				</tr>';
                $i++;
            }
        }
        if ($statement) {
            $outputmodule .=
                '<tr class="statement_module_' .
                $statement->client_id .
                '">
				<td>' .
                $i .
                '</td>
				<td>Statement</td>
				<td class="salutation_mod">' .
                $statement->salutation .
                '</td>
				<td class="primary_mod">' .
                $statement->email .
                '</td>
				<td class="secondary_mod">' .
                $statement->email2 .
                '</td>
				<td><a href="javascript:" class="edit_task_module" data-element="' .
                $statement->client_id .
                '" data-type="3" data-salutation="' .
                $statement->salutation .
                '" data-primary="' .
                $statement->email .
                '" data-secondary="' .
                $statement->email2 .
                '">Edit</a></td>
			</tr>';
            $i++;
        }
        $outputmodule .=
            '<tr class="keydocs_module_' .
            $result->client_id .
            '">
				<td>' .
            $i .
            '</td>
				<td>Key Docs</td>
				<td class="keydocs_mod">' .
            $result->salutation .
            '</td>
				<td class="primary_mod">' .
            $result->email .
            '</td>
				<td class="secondary_mod">' .
            $result->email2 .
            '</td>
				<td><a href="javascript:" class="edit_task_module" data-element="' .
            $result->client_id .
            '" data-type="4" data-salutation="' .
            $result->salutation .
            '" data-primary="' .
            $result->email .
            '" data-secondary="' .
            $result->email2 .
            '">Edit</a></td>
			</tr>';
        if ($client_id->send_statement == 1) {
            $cecked = "checked";
            $statement_text =
                '<p class="statement_p" style="color:green;font-size: 16px;">Statements will be sent to this Client as part of the automated process</p>';
        } else {
            $cecked = "";
            $statement_text =
                '<p class="statement_p" style="color:#f00;font-size: 16px;">Statements will NOT be sent to this Client as part of the automated process</p>';
        }
        $outputstatement =
            '<input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement" data-client="' .
            $client_id->client_id .
            '" value="" ' .
            $cecked .
            '><label style="font-size: 16px;" for="ok_to_send_statement">Ok to send statements to this client</label><br/>' .
            $statement_text .
            "";
        $i++;
        $outputmodule .= '</tbody>
		</table>';
        echo json_encode([
            "clientid" => $result->client_id,
            "client_added" => $result->client_added,
            "firstname" => $result->firstname,
            "surname" => $result->surname,
            "company" => $result->company,
            "address1" => $result->address1,
            "address2" => $result->address2,
            "address3" => $result->address3,
            "address4" => $result->address4,
            "address5" => $result->address5,
            "email" => $result->email,
            "tye" => $result->tye,
            "active" => $result->active,
            "tax_reg1" => $result->tax_reg1,
            "tax_reg2" => $result->tax_reg2,
            "tax_reg3" => $result->tax_reg3,
            "email2" => $result->email2,
            "phone" => $result->phone,
            "linkcode" => $result->linkcode,
            "cro" => $result->cro,
            "ard" => $result->ard,
            "trade_status" => $result->trade_status,
            "directory" => $result->directory,
            "employer_no" => $result->employer_no,
            "salutation" => $result->salutation,
            "status" => $result->status,
            "practice_code" => $result->practice_code,
            "id" => $result->id,
            "htmlcontent" => $output,
            "payrolloutput" => $output_payroll,
            "timetaskoutput" => $outputtimetask,
            "client_note" => $result->notes,
            "outputbank" => $outputbank,
            "bank_client_id" => $result->client_id,
            "outputmodule" => $outputmodule,
            "outputstatement" => $outputstatement,
        ]);
    }
    public function cm_load_all_client_invoice(Request $request)
    {
        $id = base64_decode($request->get("id"));
        $client_id = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $invoicelist = \App\Models\InvoiceSystem::where(
            "client_id",
            $client_id->client_id
        )->get();
        $outputinvoice = '<table class="display nowrap fullviewtablelist" id="invoice_expand" style="float:left;width:60%">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;width: 9%;">S.No</th>
                      <th style="text-align: left;width: 11%;">Invoice #</th>
                      <th style="text-align: left;width: 15%;">Date</th>
                      <th style="text-align: right;width: 15%;">Net</th>
                      <th style="text-align: right;width: 15%;">VAT</th>
                      <th style="text-align: right;width: 15%;">Gross</th>
                      <th style="text-align: left;width: 11%;">Statement</th>
                  </tr>
                </thead>
                <tbody>';
        $i = 1;
        if (count($invoicelist)) {
            foreach ($invoicelist as $invoice) {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice->client_id)
                    ->first();
                if ($invoice->statement == "No" || $invoice->statement == "") {
                    $statement = "No";
                    $textcolor = "color:#f00;font-weight:700";
                } else {
                    $statement = "Yes";
                    $textcolor = "color:#26BD67;font-weight:700";
                }
                $outputinvoice .=
                    '
					<tr>
						<td>' .
                    $i .
                    ' <input type="checkbox" name="invoice_check" class="invoice_check" data-element="' .
                    $invoice->id .
                    '" id="invoice_id_' .
                    $invoice->id .
                    '"> <label for="invoice_id_' .
                    $invoice->id .
                    '">&nbsp;</label></td>
						<td align="left"><a href="javascript:" class="invoice_inside_class" data-element="' .
                    $invoice->invoice_number .
                    '">' .
                    $invoice->invoice_number .
                    '</a></td>
						<td align="left"><spam style="display:none">' .
                    strtotime($invoice->invoice_date) .
                    "</spam>" .
                    date("d-M-Y", strtotime($invoice->invoice_date)) .
                    '</td>
						<td align="right">' .
                    number_format_invoice($invoice->inv_net) .
                    '</td>
						<td align="right">' .
                    number_format_invoice($invoice->vat_value) .
                    '</td>
						<td align="right">' .
                    number_format_invoice($invoice->gross) .
                    '</td>
						<td style="' .
                    $textcolor .
                    '">
							<div class="dropdown">
							  <a href="javascript:" class="dropdown-toggle dropdown_ans" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="' .
                    $textcolor .
                    '">
							    ' .
                    $statement .
                    '
							  </a>
							  <div class="dropdown-menu dropdown_statement_div" aria-labelledby="dropdownMenuButton">
							    <a class="dropdown-item dropdown_statement" href="javascript:" data-element="Yes" data-invoice="' .
                    $invoice->id .
                    '">Yes</a>
							    <a class="dropdown-item dropdown_statement" href="javascript:" data-element="No" data-invoice="' .
                    $invoice->id .
                    '">No</a>
							  </div>
							</div>
						</td>
					</tr>
				';
                $i++;
            }
        }
        if ($i == 1) {
            $outputinvoice .= '<tr>
          	<td></td>
          	<td></td>
          	<td></td>
          	<td align="right">Empty</td>
          	<td></td>
          	<td></td>
          </tr>';
        }
        $outputinvoice .= '
                </tbody>
            </table>';
        echo json_encode(["invoiceoutput" => $outputinvoice]);
    }
    public function cm_load_all_client_message(Request $request)
    {
        $id = base64_decode($request->get("id"));
        $client_id = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("id", $id)
            ->first();
        $outputmessage = '<table class="display nowrap fullviewtablelist"  id="message_expand">
            <thead>
              <th>#</th>
              <th>Subject</th>
              <th>Message From</th>
              <th>Date Sent</th>
              <th>Source</th>
              <th>Body</th>
              <th>Attachments</th>
              <th>VIEW</th>
            </thead>
            <tbody>';
        $messageus = \App\Models\Messageus::where('practice_code',Session::get('user_practice_code'))->where("status", 1)
            ->where("client_ids", "LIKE", "%" . $client_id->client_id . "%")
            ->get();
        if (count($messageus)) {
            $i = 1;
            foreach ($messageus as $message) {
                $from = $message->message_from;
                if ($from == 0) {
                    $mess_from = "Admin";
                } else {
                    $userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $from)->first();
                    $mess_from =
                        $userdetails->lastname . " " . $userdetails->firstname;
                }
                $count_files = \App\Models\MessageusFiles::where(
                    "message_id",
                    $message->id
                )->count();
                if ($message->source == "") {
                    $source = "MessageUS";
                    $mess = substr($message->message, 0, 30) . "...";
                    $count_files = $count_files;
                    $link =
                        '<a href="javascript:" class="fa fa-eye view_message" title="View Message" data-element="' .
                        $message->id .
                        '" style="margin-left:20px"></a>';
                } else {
                    $source = $message->source;
                    $strip = strip_tags($message->message);
                    $mess = mb_substr(trim($strip), 0, 30) . "...";
                    if ($message->attachments == "") {
                        $count_files = 0;
                    } else {
                        $explodeattach = explode("||", $message->attachments);
                        $count_files = count($explodeattach);
                    }
                    $link =
                        '<a href="javascript:" class="fa fa-eye view_message" title="View Message" data-element="' .
                        $message->id .
                        '" style="margin-left:20px"></a>';
                }
                $outputmessage .=
                    '<tr>
                  <td>' .
                    $i .
                    '</td>
                  <td>' .
                    $message->subject .
                    '</td>
                  <td>' .
                    $mess_from .
                    '</td>
                  <td>' .
                    date("d-M-Y @ H:i", strtotime($message->date_sent)) .
                    '</td>
                  <td>' .
                    $source .
                    '</td>
                  <td>' .
                    $mess .
                    '</td>
                  <td>' .
                    $count_files .
                    '</td>
                  <td>
                    ' .
                    $link .
                    '
                  </td>
                </tr>';
                $i++;
            }
        } else {
            $outputmessage .= '<tr>
        			<td colspan="8">No Message Found</td>
        	</tr>';
        }
        echo json_encode(["outputmessage" => $outputmessage]);
    }
    public function cm_client_payroll(Request $request)
    {
        $id = base64_decode($request->get("id"));
        $payrolllist = \App\Models\payrollTasks::where("client_id", $id)
            ->orderBy("update_time", "DESC")
            ->get();
        $output = '<table class="display nowrap fullviewtablelist" id="payroll_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th width="2%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Year</th>
                      <th style="text-align: left;">Period</th>
                      <th style="text-align: left;">Email Sent</th>
                      <th style="text-align: left;">When the Task is Marked Complete</th>
                  </tr>
                </thead>
                <tbody>';
        $i = 1;
        if (count($payrolllist)) {
            foreach ($payrolllist as $payroll) {
                $year = \App\Models\Year::where('practice_code', Session::get('user_practice_code'))->where("year_id", $payroll->year)->first();
                if ($payroll->month == 0) {
                    $week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->where("week_id", $payroll->week)->first();
                    $period = $week->week;
                    $text = "Week";
                } else {
                    $month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->where("month_id", $payroll->month)->first();
                    $period = $month->month;
                    $text = "Month";
                }
                if ($payroll->email_sent != "0000-00-00") {
                    $unsentfile = date(
                        "d F Y @ H : i",
                        strtotime($payroll->email_sent)
                    );
                } else {
                    $unsentfile = "N/A";
                }
                $output .=
                    '
					<tr>
						<td>' .
                    $i .
                    '</td>
						<td align="left">' .
                    $year->year_name .
                    '</td>
						<td align="left">' .
                    $text .
                    " " .
                    $period .
                    '</td>
						<td align="left">' .
                    $unsentfile .
                    '</td>
						<td align="left">' .
                    date("d F Y @ H : i", strtotime($payroll->update_time)) .
                    '</td>
					</tr>
				';
                $i++;
            }
        }
        if ($i == 1) {
            $output .= '<tr><td colspan="5" align="center">Empty</td></tr>';
        }
        $output .= '
                </tbody>
            </table>';
        echo $output;
    }
    public function cm_invoice_report_csv(Request $request,$id = "")
    {
        $id = $request->get("value");
        $client_name = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $id)
            ->first();
        $filename = $client_name->company;
        $invoice = DB::select(
            'SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id = "' .
                $id .
                '" ORDER BY client_id,inc_date DESC'
        );
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=CM_Report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        $columns = [
            "#",
            "Invoice Number",
            "Invoice Date",
            "Client ID",
            "Company Name",
            "Net",
            "VAT",
            "Gross",
        ];
        $callback = function () use ($invoice, $columns) {
            $file = fopen("public/papers/CM_Report.csv", "w");
            fputcsv($file, $columns);
            $i = 1;
            foreach ($invoice as $single) {
                $company_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $single->client_id)
                    ->first();
                $columns_2 = [
                    $i,
                    $single->invoice_number,
                    date("d-M-Y", strtotime($single->invoice_date)),
                    $single->client_id,
                    $company_details->company,
                    number_format_invoice($single->inv_net),
                    number_format_invoice($single->vat_value),
                    number_format_invoice($single->gross),
                ];
                fputcsv($file, $columns_2);
                $i++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
        //return $filename.'_InvoiceReport.csv';
    }
    public function cm_get_csv_filename(Request $request,$id = "")
    {
        $id = $request->get("value");
        $client_name = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $id)
            ->first();
        $filename = $client_name->company;
        echo $filename . "_InvoiceReport.csv";
    }
    public function cm_invoice_report_pdf(Request $request)
    {
        $id = $request->get("value");
        $client_name = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $id)
            ->first();
        if ($client_name->company != "") {
            $filename = $client_name->company . "-" . $id;
            $companyname = $client_name->company;
        } else {
            $filename = $client_name->company . "-" . $id;
            $companyname =
                $client_name->firstname . " " . $client_name->surname;
        }
        $invoicelist = DB::select(
            'SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id = "' .
                $id .
                '" ORDER BY client_id,inc_date DESC'
        );
        $output = "";
        $i = 1;
        if (count($invoicelist)) {
            foreach ($invoicelist as $key => $invoice) {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice->client_id)
                    ->first();
                $output .=
                    '<tr>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">' .
                    $i .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">' .
                    $invoice->invoice_number .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">' .
                    date("d-M-Y", strtotime($invoice->invoice_date)) .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">' .
                    $invoice->client_id .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">' .
                    $client_details->company .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">' .
                    number_format_invoice($invoice->inv_net) .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">' .
                    number_format_invoice($invoice->vat_value) .
                    '</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">' .
                    number_format_invoice($invoice->gross) .
                    '</td>
								</tr>';
                $i++;
            }
        }
        echo json_encode([
            "filename" => $filename,
            "output" => $output,
            "companyname" => $companyname,
        ]);
    }
    public function cm_invoice_download_report_pdfs(Request $request)
    {
        $htmlval = $request->get("htmlval");
        $pdf = PDF::loadHTML($htmlval);
        $pdf->setPaper("A4", "landscape");
        $pdf->save("public/papers/Invoice Report.pdf");
        echo "Invoice Report.pdf";
    }
    public function cm_note_update(Request $request)
    {
        $id = $request->get("client_id");
        $notes = $request->get("notes");
        \App\Models\CMClients::where("practice_code", Session::get("user_practice_code"))
            ->where("client_id", $id)
            ->update(["notes" => $notes]);
        return redirect::back()->with("message", "Notes Update Success");
    }
    public function cm_client_add_bank(Request $request)
    {
        $current_client_id = $request->get("current_client_id");
        $bank_name = $request->get("bank_name");
        $account_name = $request->get("account_name");
        $account_number = $request->get("account_number");
        $data["client_id"] = $current_client_id;
        $data["bank_name"] = $bank_name;
        $data["account_name"] = $account_name;
        $data["account_number"] = $account_number;
        \App\Models\AmlBank::where("client_id", $current_client_id)->insert($data);
        $client = \App\Models\AmlSystem::where("client_id", $current_client_id)->first();
        $aml_bank_count = \App\Models\AmlBank::where(
            "client_id",
            $current_client_id
        )->get();
        $output = '<table class="display nowrap fullviewtablelist" id="bank_expand_cms" width="100%" style="max-width: 100%;">
		<thead><tr>
			<th>#</th>
			<th>Bank Name</th>
			<th>Account Name</th>
			<th>Account Number</th>
			</tr>
		</thead><tbody>';
        $ibank = 1;
        if (count($aml_bank_count)) {
            foreach ($aml_bank_count as $bank) {
                $output .=
                    '<tr>
				<td>' .
                    $ibank .
                    '</td>
				<td>' .
                    $bank->bank_name .
                    '</td>
				<td>' .
                    $bank->account_name .
                    '</td>
				<td>' .
                    $bank->account_number .
                    '</td>
				</tr>';
                $ibank++;
            }
        }
        $output .= "</tbody></table>";
        echo json_encode(["output" => $output, "id" => $current_client_id]);
    }
    public function print_selected_invoice(Request $request)
    {
        $ids = $request->get("ids");
        $explodids = explode(",", $ids);
        $time = time();
        if (count($ids)) {
            foreach ($explodids as $id) {
                $get_inv_no = \App\Models\InvoiceSystem::where("id", $id)->first();
                $inv_no = $get_inv_no->invoice_number;
                $invoice_details = \App\Models\InvoiceSystem::where(
                    "invoice_number",
                    $inv_no
                )->first();
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $invoice_details->client_id)
                    ->first();
                if ($client_details) {
                    $companyname =
                        '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
                } else {
                    $companyname =
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
                        '
		                  <div class="class_row_td left">' .
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
			                <div class="tax_row_td right" width="13%">' .
                        number_format_invoice($invoice_details->inv_net) .
                        '</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">VAT @ 23%</div>
			                <div class="tax_row_td right" style="border-top:0px;">' .
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
			                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
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
                }
                $html =
                    '<style>
					@page { margin: 0in; }
				    body {
				        background-image: url(' .
                    URL::to("assets/invoice_letterpad_1.jpg") .
                    ');
				        background-position: top left right bottom;
					    background-repeat: no-repeat;
					    font-family: Verdana,Geneva,sans-serif;
				    }
				    .tax_table_div{width: 100%; margin-top:-30px}
		            .tax_table{margin-left:73%;width: 20%;}
		            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
		            .details_table .class_row .class_row_td.left{position:absolute; width:70%; line-height:20px;  text-align: left;  font-size:14px; }
		            .details_table .class_row .class_row_td.left_corner{position:absolute; margin-left:71%; width:10%; line-height:20px;  text-align: right;}
		            .details_table .class_row .class_row_td.right_start{position:absolute; margin-left:81%; width:9%; line-height:20px;  text-align: right;}
		            .details_table .class_row .class_row_td.right{position:absolute;line-height:20px; margin-left:90%; text-align: right; font-size:14px; width:10%;}
		            .details_table .class_row{line-height: 30px; clear:both}
		            .details_table { height : 420px !important; }
		            .class_row{width: 100%; clear:both; height:20px:}
		            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
		            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
		            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
		            .tax_table .tax_row{line-height: 30px;}
		            .company_details_class{width:100%; height:auto; }
		            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
		            .account_details_invoice_div{width:200px; }
		            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
		            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
		            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
		            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
		            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
		            .account_table .account_row .account_row_td.left{margin-left:0px;}
		            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}
		            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
		            .tax_details_class_maindiv{width: 100%; float: left;}
				</style>
		            <div class="company_details_class">' .
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
                    $tax_details .
                    "</div>";
                $uploads_dir = "public/papers/" . $time;
                if (!file_exists($uploads_dir)) {
                    mkdir($uploads_dir);
                }
                $pdf = PDF::loadHTML($html);
                $pdf->save(
                    "public/papers/" . $time . "/Invoice of " . $inv_no . ".pdf"
                );
            }
            $public_dir = public_path();
            $zipFileName = $time . ".zip";
            $zip = new ZipArchive();
            if (
                $zip->open(
                    $public_dir . "/" . $zipFileName,
                    ZipArchive::CREATE
                ) === true
            ) {
                // Add File in ZipArchive
                $dir = "public/papers/" . $time;
                $files = scandir($dir);
                if (count($files)) {
                    foreach ($files as $file) {
                        if ($file != "." && $file != "..") {
                            $zip->addFile(
                                "public/papers/" . $time . "/" . $file,
                                $file
                            );
                        }
                    }
                }
                $zip->close();
            }
            echo json_encode([
                "time" => $time . ".zip",
                "company" => $client_details->company . ".zip",
            ]);
        }
    }
    public function update_pms_vat_module(Request $request)
    {
        $type = $request->get("type");
        $taskid = $request->get("taskid");
        $salutation = $request->get("salutation");
        $primary = $request->get("primary");
        $secondary = $request->get("secondary");
        if ($type == "2") {
            $data["salutation"] = $salutation;
            $data["pemail"] = $primary;
            $data["semail"] = $secondary;
           \App\Models\vatClients::where("client_id", $taskid)->update($data);
        } elseif ($type == "3") {
            $data["salutation"] = $salutation;
            $data["email"] = $primary;
            $data["email2"] = $secondary;
            $check_statement = \App\Models\ClientStatement::where(
                "client_id",
                $taskid
            )->first();
            if ($check_statement) {
                \App\Models\ClientStatement::where("client_id", $taskid)->update(
                    $data
                );
            } else {
                $data["client_id"] = $taskid;
                \App\Models\ClientStatement::insert($data);
            }
        } elseif ($type == "4") {
            $data["salutation"] = $salutation;
            $data["email"] = $primary;
            $data["email2"] = $secondary;
            \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("client_id", $taskid)
                ->update($data);
        } else {
            $data["salutation"] = $salutation;
            $data["task_email"] = $primary;
            $data["secondary_email"] = $secondary;
            \App\Models\task::where("task_id", $taskid)->update($data);
        }
    }
    public function change_send_statement_status(Request $request)
    {
        $client_id = $request->get("client_id");
        $data["send_statement"] = $request->get("status");
        \App\Models\CMClients::where("practice_code", Session::get("user_practice_code"))
            ->where("client_id", $client_id)
            ->update($data);
    }
    public function load_all_clients_cm_system(Request $request)
    {
        $clientlist = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->select(
                "client_id",
                "firstname",
                "surname",
                "company",
                "email",
                "status",
                "active",
                "id",
                "address1",
                "address2",
                "address3",
                "address4",
                "address5",
                "phone",
                "statement",
                "practice_code"
            )
            ->orderBy("id", "asc")
            ->get();
        $i = 1;
        $output_client = "";
        if (count($clientlist)) {
            foreach ($clientlist as $key => $client) {
                $address =
                    $client->address1 .
                    " " .
                    $client->address2 .
                    " " .
                    $client->address3 .
                    " " .
                    $client->address4 .
                    " " .
                    $client->address5;
                $disabled = "";
                if ($client->active != "") {
                    if ($client->active == 2) {
                        $disabled = "disabled";
                    }
                    $check_color = \App\Models\CMClass::where(
                        "id",
                        $client->active
                    )->first();
                    $style = "color:#" . $check_color->classcolor . "";
                } else {
                    $style = "color:#000";
                }
                if ($client->company == "") {
                    $client_company =
                        $client->firstname . " & " . $client->surname;
                } else {
                    $client_company = $client->company;
                }
                if ($client->statement == "yes") {
                    $yes_checked = "checked";
                } else {
                    $yes_checked = "";
                }
                $output_client .=
                    '
            <tr class="edit_task ' .
                    $disabled .
                    '" id="clientidtr_' .
                    $client->id .
                    '">
                <td style="<?php echo $style; ?>">' .
                    $i .
                    '</td>
                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->client_id .
                    '</a></td>
                <td align="center">
                    <img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
                </td>
                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->firstname .
                    '</a></td>
                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->surname .
                    '</a></td>
                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client_company .
                    '</a></td>
                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $address .
                    '</a></td>
                <td align="left"><a style="' .
                    $style .
                    '" href="mailto:' .
                    $client->email .
                    '">' .
                    $client->email .
                    '</a></td>
                <td align="left"><a href="javascript:" id="' .
                    base64_encode($client->id) .
                    '" class="invoice_class" style="' .
                    $style .
                    '">' .
                    $client->phone .
                    '</a></td>
                <td style="' .
                    $style .
                    '" align="left">
                  ' .
                    $client->practice_code .
                    '
                </td>
            </tr>
            ';
                $i++;
            }
        } elseif ($i == 1) {
            $output_client = '
      	<tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style="text-align:center; font-weight: normal !important;">No Data found</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      	';
        }
        echo $output_client;
    }
    public function load_single_cm_system(Request $request)
    {
        $client_id = $request->get("client_id");
        $client = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        $i = 1;
        if ($client) {
            $address =
                $client->address1 .
                " " .
                $client->address2 .
                " " .
                $client->address3 .
                " " .
                $client->address4 .
                " " .
                $client->address5;
            $disabled = "";
            if ($client->active != "") {
                if ($client->active == 2) {
                    $disabled = "disabled";
                }
                $check_color = \App\Models\CMClass::where("id", $client->active)->first();
                $style = "color:#" . $check_color->classcolor . "";
            } else {
                $style = "color:#000";
            }
            if ($client->company == "") {
                $client_company = $client->firstname . " & " . $client->surname;
            } else {
                $client_company = $client->company;
            }
            if ($client->statement == "yes") {
                $yes_checked = "checked";
            } else {
                $yes_checked = "";
            }
            $output_client =
                '
            <tr class="edit_task ' .
                $disabled .
                '" id="clientidtr_' .
                $client->id .
                '">
                <td style="<?php echo $style; ?>">' .
                $i .
                '</td>
                <td align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $client->client_id .
                '</a></td>
                <td align="center">
                    <img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
                </td>
                <td align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $client->firstname .
                '</a></td>
                <td align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $client->surname .
                '</a></td>
                <td align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $client_company .
                '</a></td>
                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $address .
                '</a></td>
                <td align="left"><a style="' .
                $style .
                '" href="mailto:' .
                $client->email .
                '">' .
                $client->email .
                '</a></td>
                <td align="left"><a href="javascript:" id="' .
                base64_encode($client->id) .
                '" class="invoice_class" style="' .
                $style .
                '">' .
                $client->phone .
                '</a></td>
                <td style="' .
                $style .
                '" align="left">
                  ' .
                $client->practice_code .
                '
                </td>
            </tr>
            ';
            $i++;
        } else {
            $output_client = '
      	<tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style="text-align:center; font-weight: normal !important;">No Data found</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      	';
        }
        echo $output_client;
    }
    public function update_statement_invoice(Request $request)
    {
        $value = $request->get("value");
        $inv_id = $request->get("inv_id");
        $data["statement"] = $value;
        \App\Models\InvoiceSystem::where("id", $inv_id)->update($data);
    }
    public function get_client_year_id(Request $request)
    {
        $client_id = $request->get("client_id");
        $year = \App\Models\YearEndYear::where("status", 0)
            ->orderBy("year", "desc")
            ->first();
        $year_clients =\App\Models\YearClient::where("year", $year->year)
            ->where("client_id", $client_id)
            ->first();
        if ($year_clients) {
            echo URL::to(
                "user/yearend_individualclient/" .
                    base64_encode($year_clients->id)
            );
        } else {
            echo 0;
        }
    }
    public function mui_icons_for_taskspecifics(Request $request)
    {
        $client_id = $request->get("client_id");
        $encode_client_id = base64_encode($client_id);
        $year = \App\Models\YearEndYear::where("status", 0)
            ->orderBy("year", "desc")
            ->first();
        $year_clients =\App\Models\YearClient::where("year", $year->year)
            ->where("client_id", $client_id)
            ->first();
        $prevdate = date("Y-m-05", strtotime("first day of -1 months"));
        $prev_date2 = date("Y-m", strtotime($prevdate));
        $icons =
            '<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to("user/ta_allocation?client_id=" . $client_id) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-clock-o" title="TA System" aria-hidden="true"></i>
		</a></div>
		<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to(
                "user/rct_client_manager/" .
                    $client_id .
                    "?active_month=" .
                    $prev_date2
            ) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-university" title="RCT Manager" aria-hidden="true"></i>
		</a></div>
		<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to("user/client_management?client_id=" . $client_id) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-users" title="Client Management System" aria-hidden="true"></i>
		</a></div>
		<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to("user/client_request_manager/" . $encode_client_id) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-envelope" title="Clinet Request System" aria-hidden="true"></i>
		</a></div>
		<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to("user/infile_search?client_id=" . $client_id) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-file-archive-o" title="Infile" aria-hidden="true"></i>
		</a></div>
		<div style="display:inline-flex;"><a class="quick_links" href="' .
            URL::to("user/key_docs?client_id=" . $client_id) .
            '" style="padding:10px; text-decoration:none;">
		<i class="fa fa-key" title="Keydocs" aria-hidden="true"></i>
		</a></div>';
        if ($year_clients) {
            $icons .=
                '<div style="display:inline-flex;"><a class="quick_links yearend_link" href="' .
                URL::to(
                    "user/yearend_individualclient/" .
                        base64_encode($year_clients->id)
                ) .
                '" style="padding:10px; text-decoration:none;">
			<i class="fa fa-calendar" title="Yearend Manager" aria-hidden="true"></i>
			</a></div>';
        }
        echo $icons;
    }
    public function client_checkemail(Request $request) {
        $id = $request->get('id');
        $email = $request->get('email');
        if($id != "")
        {
            $validate = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('email',$email)->where('client_id','!=', $id)->count();
            $secondary_validate = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('email2',$email)->where('client_id','!=', $id)->count();
        }
        else{
            $validate = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('email',$email)->count();
            $secondary_validate = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('email2',$email)->count();
        }
        if($validate != 0 || $secondary_validate != 0)
            $valid = false;
        else
            $valid = true;
        echo json_encode($valid);
        exit;
    }
    public function cms_client_portal(Request $request) {
        return view("user/cm/client_portal", ["title" => "Client Portal"]);
    }
    public function show_messageus_sample_screen_portal(Request $request)
    {
        $message_id = $request->get('message_id');
        $message_details = \App\Models\Messageus::where('id',$message_id)->first();
        if($message_details->source == "")
        {
            $output = '<div class="row" style="background: #c7c7c7;padding:20px">
                <div class="col-md-12">
                  <h5 style="font-weight:800">Message Summary:</h5>
                </div>
                <div class="col-md-12" style="margin-top:10px">
                  <h5 style="font-weight:800">Message Subject: </h5>
                  <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
                </div>
                <div class="col-md-12" style="margin-top:20px">
                  <h5 style="font-weight:800">Message Body: </h5>
                  <div style="width:100%;background: #fff;min-height:300px;padding:10px">
                    '.$message_details->message.'
                  </div>
                </div>
                <div class="col-md-12" style="margin-top:20px">
                  <h5 style="font-weight:800">Attached Files: </h5>';
                  $fileoutput = '';
                  $files = \App\Models\MessageusFiles::where('message_id',$message_id)->get();
                  if(($files))
                  {
                    foreach($files as $file)
                    {
                      $fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a></div>";
                    }
                  }
                  $output.=$fileoutput;
                $output.='</div>
            </div>';
        }
        else{
            $output = '<div class="row" style="background: #c7c7c7;padding:20px">
                <div class="col-md-12">
                  <h5 style="font-weight:800">Message Summary:</h5>
                </div>
                <div class="col-md-12" style="margin-top:10px">
                  <h5 style="font-weight:800">Message Subject: </h5>
                  <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
                </div>
                <div class="col-md-12" style="margin-top:20px">
                  <h5 style="font-weight:800">Message Body: </h5>
                  <div id="message_body" style="width:100%;background: #fff;min-height:300px;padding:10px;float: left;">
                    '.$message_details->message.'
                  </div>
                </div>
                <div class="col-md-12" style="margin-top:20px">';
                  $fileoutput = '';
                  if($message_details->attachments != "")
                  {
                    $fileoutput.='<h5 style="font-weight:800">Attached Files: </h5>';
                    $attachments = explode("||",$message_details->attachments);
                    if(($attachments))
                    {
                        foreach($attachments as $attach)
                        {
                            $exp_attach = explode("/",$attach);
                            $fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$attach."' class='messageus_attachment_a' download>".end($exp_attach)."</a></div>";
                        }
                    }
                  }
                  $output.=$fileoutput;
                $output.='</div>
              </div>';
        }
      echo $output;
    }
}
