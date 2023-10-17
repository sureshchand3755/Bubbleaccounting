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
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class AmlController extends Controller
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
    public function aml_system(Request $request)
    {
        $client = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->select(
                "client_id",
                "firstname",
                "surname",
                "tye",
                "company",
                "status",
                "active",
                "id"
            )
            ->orderBy("id", "asc")
            ->get();
        $class = \App\Models\CMClass::where("status", 0)->get();
        $user =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_status", 0)
            ->where("disabled", 0)
            ->orderBy("lastname", "asc")
            ->get();
        $userlist =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_status", 0)
            ->where("disabled", 0)
            ->orderBy("lastname", "asc")
            ->get();
        $uname = '<option value="">Select Username</option>';
        if (is_countable($userlist)) {
            foreach ($userlist as $singleuser) {
                if ($uname == "") {
                    $uname =
                        '<option value="' .
                        $singleuser->user_id .
                        '">' .
                        $singleuser->lastname .
                        " " .
                        $singleuser->firstname .
                        "</option>";
                } else {
                    $uname =
                        $uname .
                        '<option value="' .
                        $singleuser->user_id .
                        '">' .
                        $singleuser->lastname .
                        " " .
                        $singleuser->firstname .
                        "</option>";
                }
            }
        }
        return view("user/aml/aml_system", [
            "title" => "Client Management",
            "clientlist" => $client,
            "classlist" => $class,
            "userlist" => $user,
            "userlistt" => $uname,
        ]);
    }
    public function update_aml_incomplete_status(Request $request)
    {
        $data["aml_incomplete"] = $request->get("value");
       \App\Models\userLogin::where("userid", 1)->update($data);
    }
    public function aml_system_client_source_refresh(Request $request)
    {
        $id = $request->get("id");
        $data["client_source"] = "0";
        $data["client_source_detail"] = "";
        \App\Models\AmlSystem::where("client_id", $id)->update($data);
        $client_source =
            '
		<input type="radio" name="client_source" class="other_client" value="1" id="other_client_' .
            $id .
            '" data-element="' .
            $id .
            '" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_' .
            $id .
            '">Other Client</label><br/>
		<input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="' .
            $id .
            '" id="personal_partner_' .
            $id .
            '"><label for="personal_partner_' .
            $id .
            '">Personal Acquaintance of Partner</label><br/>
		<input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="' .
            $id .
            '" id="reply_note_' .
            $id .
            '"><label for="reply_note_' .
            $id .
            '">Reply to Advert / Walk in</label>';
        echo json_encode(["output" => $client_source, "id" => $id]);
    }
    public function aml_system_risk_update(Request $request)
    {
        $id = $request->get("id");
        $value = $request->get("value");
        $aml_details = \App\Models\AmlSystem::where("client_id", $id)->first();
        if ($aml_details) {
            $data["risk_category"] = $value;
            \App\Models\AmlSystem::where("client_id", $id)->update($data);
        } else {
            $data["client_id"] = $id;
            $data["risk_category"] = $value;
            \App\Models\AmlSystem::where("client_id", $id)->insert($data);
        }
    }
    public function aml_client_search(Request $request)
    {
        $value = $request->get("term");
        $details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->Where("client_id", "like", "%" . $value . "%")
            ->orWhere("company", "like", "%" . $value . "%")
            ->Where("status", 0)
            ->get();
        $data = [];
        foreach ($details as $single) {
            if ($single->company != "") {
                $company = $single->company;
            } else {
                $company = $single->firstname . " & " . $single->surname;
            }
            $data[] = [
                "value" => $company . "-" . $single->client_id,
                "id" => $single->client_id,
                "active_status" => $single->active,
            ];
        }
        if (is_countable($data)) {
            return $data;
        } else {
            return ["value" => "No Result Found", "id" => ""];
        }
    }
    public function aml_clientsearchselect(Request $request)
    {
        $id = $request->get("value");
    }
    public function aml_system_other_client(Request $request)
    {
        $client_id = $request->get("client_id");
        $type = $request->get("type");
        $current_client_id = $request->get("current_client_id");
        $aml_details = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        if ($aml_details) {
            $data["client_source"] = $type;
            $data["client_source_detail"] = $client_id;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client_id;
            $data["client_source"] = $type;
            $data["client_source_detail"] = $client_id;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->insert(
                $data
            );
        }
        $client = \App\Models\AmlSystem::where("client_id", $current_client_id)->first();
        $client_details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client->client_source_detail)
            ->first();
        $client_source =
            '<a href="javascript:" data-text="Other Client - ' .
            $client_details->company .
            " - " .
            $client_details->firstname .
            " " .
            $client_details->surname .
            '" class="download_client_source"> Other Client - ' .
            $client_details->company .
            " - " .
            $client_details->firstname .
            " " .
            $client_details->surname .
            '.txt</a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
            $client->client_id .
            '></i></a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
            $client->client_id .
            "></i></a> ";
        echo json_encode([
            "output" => $client_source,
            "id" => $current_client_id,
        ]);
    }
    public function aml_system_partner(Request $request)
    {
        $user_type = $request->get("user_type");
        $type = $request->get("type");
        $current_client_id = $request->get("current_client_id");
        $aml_details = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        if ($aml_details) {
            $data["client_source"] = $type;
            $data["client_source_detail"] = $user_type;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client_id;
            $data["client_source"] = $type;
            $data["client_source_detail"] = $user_type;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->insert(
                $data
            );
        }
        $client = \App\Models\AmlSystem::where("client_id", $current_client_id)->first();
        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $user_type)->first();
        $client_source =
            '<a href="javascript:" data-text="Partner - ' .
            $user_details->firstname .
            " " .
            $user_details->lastname .
            '" class="download_client_source"> Partner - ' .
            $user_details->firstname .
            " " .
            $user_details->lastname .
            '.txt</a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
            $client->client_id .
            '></i></a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
            $client->client_id .
            "></i></a>";
        echo json_encode([
            "output" => $client_source,
            "id" => $current_client_id,
        ]);
    }
    public function aml_system_note(Request $request)
    {
        $reply_note = $request->get("reply_note");
        $type = $request->get("type");
        $current_client_id = $request->get("current_client_id");
        $aml_details = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        if ($aml_details) {
            $data["client_source"] = $type;
            $data["client_source_detail"] = $reply_note;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client_id;
            $data["client_source"] = $type;
            $data["client_source_detail"] = $reply_note;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->insert(
                $data
            );
        }
        $client = \App\Models\AmlSystem::where("client_id", $current_client_id)->first();
        $client_source =
            '<a href="javascript:" data-text="Note - ' .
            $client->client_source_detail .
            '" class="download_client_source"> Note - ' .
            $client->client_source_detail .
            '.txt</a> 
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
            $client->client_id .
            '></i></a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
            $client->client_id .
            "></i></a>";
        echo json_encode([
            "output" => $client_source,
            "id" => $current_client_id,
        ]);
    }
    public function aml_system_add_bank(Request $request)
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
        )->count();
        $output =
            '<a href="javascript:" class="bank_detail_class" data-element="' .
            $current_client_id .
            '">' .
            $aml_bank_count .
            '</a><a href="javascript:" class="bank_class" data-toggle="modal" data-toggle="modal" data-target=".bank_modal"><i class="fa fa-plus add_bank" title="Add Bank" data-element="' .
            $current_client_id .
            '"  style="margin-left:10px;"></i></a>';
        echo json_encode(["output" => $output, "id" => $current_client_id]);
    }
    public function aml_system_bank_details(Request $request)
    {
        $client_id = $request->get("client_id");
        $aml_bank_list = \App\Models\AmlBank::where("client_id", $client_id)->get();
        $output = "";
        $i = 1;
        if (is_countable($aml_bank_list)) {
            foreach ($aml_bank_list as $bank) {
                $output .=
                    '
				<tr>
					<td>' .
                    $i .
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
				</tr>
				';
                $i++;
            }
        }
        $company = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        echo json_encode([
            "output" => $output,
            "company_name" => $company->company,
        ]);
    }
    public function aml_system_review(Request $request)
    {
        $review_date = $request->get("review_date");
        $reivew_filed = $request->get("reivew_filed");
        $current_client_id = $request->get("current_client_id");
        $aml_details = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        if ($aml_details) {
            $data["review"] = "1";
            $data["review_date"] = date("Y-m-d", strtotime($review_date));
            $data["file_review"] = $reivew_filed;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client_id;
            $data["review"] = "1";
            $data["review_date"] = date("Y-m-d", strtotime($review_date));
            $data["file_review"] = $reivew_filed;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->insert(
                $data
            );
        }
        $aml_system = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        $output_reveiw =
            "Date:" .
            date("d-M-Y", strtotime($aml_system->review_date)) .
            "</br/>Review By: " .
            $aml_system->file_review .
            '<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="' .
            $aml_system->client_id .
            '"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="' .
            $aml_system->client_id .
            '"></i></a>';
        echo json_encode([
            "output" => $output_reveiw,
            "id" => $current_client_id,
        ]);
    }
    public function aml_system_review_edit(Request $request)
    {
        $current_client_id = $request->get("current_client");
        $aml_details = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        echo json_encode([
            "output" => $aml_details->file_review,
            "date" => date("d-M-Y", strtotime($aml_details->review_date)),
        ]);
    }
    public function aml_system_review_edit_update(Request $request)
    {
        $review_date = $request->get("review_date");
        $reivew_filed = $request->get("reivew_filed");
        $current_client_id = $request->get("current_client_id");
        $data["review_date"] = date("Y-m-d", strtotime($review_date));
        $data["file_review"] = $reivew_filed;
        \App\Models\AmlSystem::where("client_id", $current_client_id)->update($data);
        $aml_system = \App\Models\AmlSystem::where(
            "client_id",
            $current_client_id
        )->first();
        $output_reveiw =
            "Date:" .
            date("d-M-Y", strtotime($aml_system->review_date)) .
            "</br/>Review By: " .
            $aml_system->file_review .
            '<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="' .
            $aml_system->client_id .
            '"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="' .
            $aml_system->client_id .
            '"></i></a>';
        echo json_encode([
            "output" => $output_reveiw,
            "id" => $current_client_id,
        ]);
    }
    public function aml_system_review_delete(Request $request)
    {
        $current_client = $request->get("current_client");
        $data["review"] = "0";
        $data["file_review"] = "";
        \App\Models\AmlSystem::where("client_id", $current_client)->update($data);
        $output_reveiw =
            '
			<div class="select_button" style=" margin-left: 10px;">
            <ul>                                    
            <li><a href="javascript:" class="review_by" data-element="' .
            $current_client .
            '" style="font-size: 13px; font-weight: 500;">Review By</a></li>
          </ul>
        </div>';
        echo json_encode(["output" => $output_reveiw, "id" => $current_client]);
    }
    public function aml_upload_images_add(Request $request)
    {
        $current_client = $request->get("client_identity_client_code");
        if (!empty($_FILES)) {
            $tmpFile = $_FILES["file"]["tmp_name"];
            $fname = str_replace("#", "", $_FILES["file"]["name"]);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("#", "", $fname);
            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);
            $fname = str_replace("%", "", $fname);
            $upload_dir = "uploads/aml_image";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir . "/" . base64_encode($current_client);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $dataval["client_id"] = $current_client;
            $dataval["url"] = $upload_dir;
            $dataval["attachment"] = $fname;
            $id = \App\Models\AmlAttachment::insertDetails($dataval);
            $filename = $upload_dir . "/" . $fname;
            move_uploaded_file($tmpFile, $filename);
            echo json_encode([
                "id" => $id,
                "filename" => $fname,
                "fullurl" => URL::to($upload_dir . "/" . $fname),
                "client_id" => $current_client,
            ]);
        }
    }
    public function aml_system_image_upload(Request $request)
    {
        $current_client = $request->get("current_client");
        if (Session::has("aml_attach_add")) {
            $files = Session::get("aml_attach_add");
            $upload_dir = "uploads/aml_image";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $upload_dir = $upload_dir . "/" . base64_encode($current_client);
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }
            $dir = "uploads/aml_image/temp"; //"path/to/targetFiles";
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
                $dataval["client_id"] = $current_client;
                $dataval["url"] = $upload_dir;
                $dataval["attachment"] = $file["attachment"];
                \App\Models\AmlAttachment::insert($dataval);
            }
        }
        Session::forget("aml_attach_add");
        $images_list = \App\Models\AmlAttachment::where(
            "client_id",
            $current_client
        )->get();
        $output = "";
        if (is_countable($images_list)) {
            foreach ($images_list as $image) {
                if ($image->identity_type == 1) {
                    $iden = "P";
                } else {
                    $iden = "O";
                }
                $current_date = strtotime(date("Y-m-d"));
                $expiry_date = strtotime($image->expiry_date);
                $attach_color = "#000";
                if ($image->expiry_date != "0000-00-00") {
                    if ($current_date > $expiry_date) {
                        $attach_color = "#f00";
                    }
                }
                if ($image->standard_name == "") {
                    $output .=
                        '<a class="id_attach_' .
                        $image->id .
                        '" href="' .
                        URL::to("/" . $image->url . "/" . $image->attachment) .
                        '" style="color:' .
                        $attach_color .
                        '" download>' .
                        $image->attachment .
                        " (" .
                        $iden .
                        ")</a><br/>";
                } else {
                    $output .=
                        '<a class="id_attach_' .
                        $image->id .
                        '" href="' .
                        URL::to("/" . $image->url . "/" . $image->attachment) .
                        '" style="color:' .
                        $attach_color .
                        '" download="' .
                        $image->standard_name .
                        '">' .
                        $image->standard_name .
                        " (" .
                        $iden .
                        ")</a><br/>";
                }
            }
        }
        echo json_encode(["output" => $output, "id" => $current_client]);
    }
    public function aml_system_delete_attached(Request $request)
    {
        $id = $request->get("id");
        $row = \App\Models\AmlAttachment::where("id", $id)->first();
        $current_client = $row->client_id;
        \App\Models\AmlAttachment::where("id", $id)->delete();
        $images_list = \App\Models\AmlAttachment::where(
            "client_id",
            $current_client
        )->get();
        $output = "";
        if (is_countable($images_list)) {
            foreach ($images_list as $image) {
                if ($image->identity_type == 1) {
                    $iden = "P";
                } else {
                    $iden = "O";
                }
                $current_date = strtotime(date("Y-m-d"));
                $expiry_date = strtotime($image->expiry_date);
                $attach_color = "#000";
                if ($image->expiry_date != "0000-00-00") {
                    if ($current_date > $expiry_date) {
                        $attach_color = "#f00";
                    }
                }
                if ($image->standard_name == "") {
                    $output .=
                        '<a class="id_attach_' .
                        $image->id .
                        '" href="' .
                        URL::to("/" . $image->url . "/" . $image->attachment) .
                        '" style="color:' .
                        $attach_color .
                        '" download>' .
                        $image->attachment .
                        " (" .
                        $iden .
                        ")</a><br/>";
                } else {
                    $output .=
                        '<a class="id_attach_' .
                        $image->id .
                        '" href="' .
                        URL::to("/" . $image->url . "/" . $image->attachment) .
                        '" style="color:' .
                        $attach_color .
                        '" download="' .
                        $image->standard_name .
                        '">' .
                        $image->standard_name .
                        " (" .
                        $iden .
                        ")</a><br/>";
                }
            }
        } else {
            $output .= "";
        }
        echo json_encode(["output" => $output, "id" => $current_client]);
    }
    public function aml_system_client_since(Request $request)
    {
        $current_client = $request->get("current_client");
        $date = $request->get("date");
        $aml_details = \App\Models\AmlSystem::where("client_id", $current_client)->first();
        if ($aml_details) {
            $data["data_client"] = date("Y-m-d", strtotime($date));
            \App\Models\AmlSystem::where("client_id", $current_client)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client;
            $data["data_client"] = date("Y-m-d", strtotime($date));
            \App\Models\AmlSystem::where("client_id", $current_client)->insert(
                $data
            );
        }
        echo json_encode(["output" => $date, "id" => $current_client]);
    }
    public function aml_report_csv(Request $request)
    {
        $ids = explode(",", $request->get("value"));
        $clientlist = \App\Models\CMClients::whereIn("client_id", $ids)->get();
        $columns = [
            "S.No",
            "Client ID",
            "Company",
            "Client Source",
            "Date Client Since",
            "Client Identity",
            "Products & Services",
            "Transaction Type",
            "Risk Factors",
            "Geo Area of Operation",
            "Politically Exposed Person",
            "High Risk Country",
            "Legal Format of Company",
            "Email Unsent",
            "Bank Accounts",
            "File Review",
            "Risk Category",
        ];
        $filename = time() . "_ AML Client Report.csv";
        $file = fopen("public/papers/" . $filename, "w");
        fputcsv($file, $columns);
        $i = 1;
        if (is_countable($clientlist)) {
            foreach ($clientlist as $client) {
                $aml_system = \App\Models\AmlSystem::where(
                    "client_id",
                    $client->client_id
                )->first();
                if ($aml_system) {
                    if ($aml_system->client_source == 1) {
                        $client_details = \App\Models\CMClients::where(
                            "practice_code",
                            Session::get("user_practice_code")
                        )
                            ->where(
                                "client_id",
                                $aml_system->client_source_detail
                            )
                            ->first();
                        $client_source =
                            "Other - " . $client_details->firstname;
                    } elseif ($aml_system->client_source == 2) {
                        $client_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
                            "user_id",
                            $aml_system->client_source_detail
                        )->first();
                        $client_source =
                            "Partner - " .
                            $client_details->firstname .
                            " " .
                            $client_details->lastname;
                    } elseif ($aml_system->client_source == 3) {
                        $client_source =
                            "Notes - " . $aml_system->client_source_detail;
                    } else {
                        $client_source = "";
                    }
                } else {
                    $client_source = "";
                }
                if ($client) {
                    if ($client->client_added == "") {
                        $output_client_since = "No Date Set";
                    } else {
                        $explode_date = explode("/", $client->client_added);
                        $explode_hyphen_date = explode(
                            "-",
                            $client->client_added
                        );
                        if (is_countable($explode_date) > 1) {
                            $client_added = DateTime::createFromFormat(
                                "d/m/Y",
                                $client->client_added
                            );
                            $client_added_since = $client_added->format(
                                "d-M-Y"
                            );
                        } elseif (is_countable($explode_hyphen_date) > 1) {
                            $client_added = DateTime::createFromFormat(
                                "d-m-Y",
                                $client->client_added
                            );
                            $client_added_since = $client_added->format(
                                "d-M-Y"
                            );
                        } else {
                            $client_added_since = $client->client_added;
                        }
                        $output_client_since = $client_added_since;
                    }
                } else {
                    $output_client_since = "No Date Set";
                }
                $aml_attachement = \App\Models\AmlAttachment::where(
                    "client_id",
                    $client->client_id
                )->get();
                $output_attached = "";
                if (is_countable($aml_attachement)) {
                    foreach ($aml_attachement as $attached) {
                        $output_attached .= $attached->attachment . "\n";
                    }
                } else {
                    $output_attached .= "";
                }
                $aml_count = \App\Models\AmlBank::where(
                    "client_id",
                    $client->client_id
                )->count();
                if (is_countable($aml_count)) {
                    $bank_count = $aml_count;
                } else {
                    $bank_count = "";
                }
                if ($aml_system) {
                    if ($aml_system->review == 1) {
                        $review_output =
                            "Date: " .
                            date("d-M-Y", strtotime($aml_system->review_date)) .
                            "<br/>Review: " .
                            $aml_system->file_review;
                    } else {
                        $review_output = "";
                    }
                } else {
                    $review_output = "";
                }
                if ($aml_system) {
                    if ($aml_system->risk_category == 1) {
                        $risk = "Green";
                    } elseif ($aml_system->risk_category == 2) {
                        $risk = "Yellow";
                    } elseif ($aml_system->risk_category == 3) {
                        $risk = "Red";
                    } elseif ($aml_system->risk_category == 0) {
                        $risk = "Green";
                    }
                    $products_services = $aml_system->products_services;
                    $transaction_type = $aml_system->transaction_type;
                    $risk_factors = $aml_system->risk_factors;
                    $geo_area = $aml_system->geo_area;
                    $politically_exposed =
                        $aml_system->politically_exposed == 1 ? "Yes" : "No";
                    $high_risk = $aml_system->high_risk == 1 ? "Yes" : "No";
                } else {
                    $risk = "Green";
                    $products_services = "";
                    $transaction_type = "";
                    $risk_factors = "";
                    $geo_area = "";
                    $politically_exposed = "No";
                    $high_risk = "No";
                }
                if ($aml_system) {
                    if ($aml_system->last_email_sent == "0000-00-00 00:00:00") {
                        $email_sent = "";
                    } else {
                        $email_sent = date(
                            "d M Y @ H:i",
                            strtotime($aml_system->last_email_sent)
                        );
                    }
                } else {
                    $email_sent = "";
                }
                $columns_2 = [
                    $i,
                    $client->client_id,
                    $client->company,
                    $client_source,
                    $output_client_since,
                    $output_attached,
                    $products_services,
                    $transaction_type,
                    $risk_factors,
                    $geo_area,
                    $politically_exposed,
                    $high_risk,
                    $client->tye,
                    $email_sent,
                    $bank_count,
                    $review_output,
                    $risk,
                ];
                fputcsv($file, $columns_2);
                $i++;
            }
        }
        fclose($file);
        echo $filename;
    }
    public function aml_report_pdf(Request $request)
    {
        $ids = explode(",", $request->get("value"));
        $clientlist = \App\Models\CMClients::whereIn("client_id", $ids)->get();
        $output = "";
        $i = 1;
        if (is_countable($clientlist)) {
            foreach ($clientlist as $key => $client) {
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
                $aml_system = \App\Models\AmlSystem::where(
                    "client_id",
                    $client->client_id
                )->first();
                if ($aml_system) {
                    $risk_select = $aml_system->risk_category;
                } else {
                    $risk_select = "";
                }
                $aml_attachement = \App\Models\AmlAttachment::where(
                    "client_id",
                    $client->client_id
                )->get();
                $output_attached = "";
                if (is_countable($aml_attachement)) {
                    foreach ($aml_attachement as $attached) {
                        if ($attached->standard_name == "") {
                            if (strlen($attached->attachment) > 20) {
                                $att = substr($attached->attachment, 0, 20);
                            } else {
                                $att = $attached->attachment;
                            }
                            $output_attached .= $att . "<br/>";
                        } else {
                            if (strlen($attached->standard_name) > 20) {
                                $att = substr($attached->standard_name, 0, 20);
                            } else {
                                $att = $attached->standard_name;
                            }
                            $output_attached .= $att . "<br/>";
                        }
                    }
                } else {
                    $output_attached .= "";
                }
                if ($aml_system) {
                    if ($aml_system->review == 1) {
                        $output_reveiw =
                            "Date:" .
                            date("d-M-Y", strtotime($aml_system->review_date)) .
                            "</br/>Review By: " .
                            $aml_system->file_review .
                            "<br/>";
                    } else {
                        $output_reveiw = "";
                    }
                } else {
                    $output_reveiw = "";
                }
                if ($aml_system) {
                    if ($aml_system->client_source == 1) {
                        $client_details = \App\Models\CMClients::where(
                            "client_id",
                            $aml_system->client_source_detail
                        )->first();
                        $client_source_output =
                            "Other Client - " .
                            $client_details->company .
                            " - " .
                            $client_details->firstname .
                            " " .
                            $client_details->surname .
                            ".txt";
                        $client_source = $client_source_output;
                    } elseif ($aml_system->client_source == 2) {
                        $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
                            "user_id",
                            $aml_system->client_source_detail
                        )->first();
                        $client_source_output =
                            "Partner - " .
                            $user_details->lastname .
                            " " .
                            $user_details->firstname .
                            ".txt";
                        $client_source = $client_source_output;
                    } elseif ($aml_system->client_source == 3) {
                        $client_source_output =
                            "Note - " .
                            $aml_system->client_source_detail .
                            ".txt";
                        $client_source = $client_source_output;
                    } else {
                        $client_source = "";
                    }
                } else {
                    $client_source = "";
                }
                $aml_bank = \App\Models\AmlBank::where(
                    "client_id",
                    $client->client_id
                )->first();
                $aml_count = \App\Models\AmlBank::where(
                    "client_id",
                    $client->client_id
                )->count();
                if (is_countable($aml_bank)) {
                    $bank_output = $aml_count;
                } else {
                    $bank_output = "";
                }
                $cli_det = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $client->client_id)
                    ->first();
                if ($cli_det) {
                    if ($cli_det->client_added == "") {
                        $output_client_since = "No Date Set";
                    } else {
                        $explode_date = explode("/", $cli_det->client_added);
                        $explode_hyphen_date = explode(
                            "-",
                            $cli_det->client_added
                        );
                        if (is_countable($explode_date) > 1) {
                            $client_added = DateTime::createFromFormat(
                                "d/m/Y",
                                $cli_det->client_added
                            );
                            $client_added_since = $client_added->format(
                                "d-M-Y"
                            );
                        } elseif (is_countable($explode_hyphen_date) > 1) {
                            $client_added = DateTime::createFromFormat(
                                "d-m-Y",
                                $cli_det->client_added
                            );
                            $client_added_since = $client_added->format(
                                "d-M-Y"
                            );
                        } else {
                            $client_added_since = $cli_det->client_added;
                        }
                        $output_client_since = $client_added_since;
                    }
                } else {
                    $output_client_since = "No Date Set";
                }
                if ($aml_system) {
                    if ($aml_system->risk_category == 1) {
                        $risk = "Green";
                    } elseif ($aml_system->risk_category == 2) {
                        $risk = "Yellow";
                    } elseif ($aml_system->risk_category == 3) {
                        $risk = "Red";
                    } elseif ($aml_system->risk_category == 0) {
                        $risk = "Green";
                    }
                    $products_services = $aml_system->products_services;
                    $transaction_type = $aml_system->transaction_type;
                    $risk_factors = $aml_system->risk_factors;
                    $geo_area = $aml_system->geo_area;
                    $politically_exposed =
                        $aml_system->politically_exposed == 1 ? "Yes" : "No";
                    $high_risk = $aml_system->high_risk == 1 ? "Yes" : "No";
                } else {
                    $risk = "Green";
                    $products_services = "";
                    $transaction_type = "";
                    $risk_factors = "";
                    $geo_area = "";
                    $politically_exposed = "No";
                    $high_risk = "No";
                }
                $output .=
                    '<div class="client_div" style="page-break-after: always;">
					<h4 style="text-align:center">Anti Money Launding Client Review - ' .
                    $client->client_id .
                    " - " .
                    $client->company .
                    '</h4>
					<table class="table own_table_white" border="0px" style="font-family: Roboto, sans-serif; font-size: 13px; width: 100%;">
						<tr>
							<td style="width:70%;line-height:25px;vertical-align:top">
								<div><strong>Client Code:</strong> ' .
                    $client->client_id .
                    '</div>
								<div><strong>Client Name:</strong> ' .
                    $client->company .
                    '</div>
								<div><strong>Contact:</strong> ' .
                    $client->firstname .
                    " " .
                    $client->surname .
                    '</div>
							</td>
							<td style="width:15%;line-height:25px;vertical-align:top"><strong>Client Address:</strong></td>
							<td style="line-height:25px">
								<div>';
                if ($client->address1 != "") {
                    $output .= $client->address1 . "<br/>";
                }
                if ($client->address2 != "") {
                    $output .= $client->address2 . "<br/>";
                }
                if ($client->address3 != "") {
                    $output .= $client->address3 . "<br/>";
                }
                if ($client->address4 != "") {
                    $output .= $client->address4 . "<br/>";
                }
                if ($client->address5 != "") {
                    $output .= $client->address5 . "<br/>";
                }
                $output .=
                    '</div>
							</td>
						</tr>
					</table>
					<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; width: 100%;margin-top:20px">
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Client Source:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $client_source .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Date Client Since:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $output_client_since .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Client Identity:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $output_attached .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Products & Services:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $products_services .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Transaction Type:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $transaction_type .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Risk Factors:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $risk_factors .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Geo Area of Operation:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $geo_area .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Politically Exposed Person:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $politically_exposed .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">High Risk Country:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $high_risk .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Legal Format of Company:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $client->tye .
                    '</td>
						</tr>';
                if ($aml_system) {
                    if ($aml_system->last_email_sent == "0000-00-00 00:00:00") {
                        $email_sent = "";
                    } else {
                        $email_sent = date(
                            "d M Y @ H:i",
                            strtotime($aml_system->last_email_sent)
                        );
                    }
                } else {
                    $email_sent = "";
                }
                $output .=
                    '<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Bank Account:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $bank_output .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">File Review:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $output_reveiw .
                    '</td>
						</tr>
						<tr>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Risk Category:</td>
							<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
                    $risk .
                    '</td>
						</tr>
					</table>
				</div>
				';
            }
        }
        //$htmlval = $request->get('htmlval');
        $pdf = PDF::loadHTML($output);
        $pdf->setPaper("A4", "portrait	");
        $time = time();
        $pdf->save("public/papers/AML Report_" . $time . ".pdf");
        echo "AML Report_" . $time . ".pdf";
    }
    public function aml_report_pdf_single(Request $request)
    {
        $id = $request->get("value");
        $output = "";
        $client = \App\Models\CMClients::where("client_id", $id)->first();
        if ($client->active != "") {
            if ($client->active == 2) {
                $disabled = "disabled";
            }
            $check_color = \App\Models\CMClass::where("id", $client->active)->first();
            $style = "color:#" . $check_color->classcolor . "";
        } else {
            $style = "color:#000";
        }
        $aml_system = \App\Models\AmlSystem::where(
            "client_id",
            $client->client_id
        )->first();
        if ($aml_system) {
            $risk_select = $aml_system->risk_category;
        } else {
            $risk_select = "";
        }
        $aml_attachement = \App\Models\AmlAttachment::where(
            "client_id",
            $client->client_id
        )->get();
        $output_attached = "";
        if (is_countable($aml_attachement)) {
            foreach ($aml_attachement as $attached) {
                if ($attached->standard_name == "") {
                    if (strlen($attached->attachment) > 20) {
                        $att = substr($attached->attachment, 0, 20);
                    } else {
                        $att = $attached->attachment;
                    }
                    $output_attached .= $att . "<br/>";
                } else {
                    if (strlen($attached->standard_name) > 20) {
                        $att = substr($attached->standard_name, 0, 20);
                    } else {
                        $att = $attached->standard_name;
                    }
                    $output_attached .= $att . "<br/>";
                }
            }
        } else {
            $output_attached .= "";
        }
        if ($aml_system) {
            if ($aml_system->review == 1) {
                $output_reveiw =
                    "Date:" .
                    date("d-M-Y", strtotime($aml_system->review_date)) .
                    "</br/>Review By: " .
                    $aml_system->file_review .
                    "<br/>";
            } else {
                $output_reveiw = "";
            }
        } else {
            $output_reveiw = "";
        }
        if ($aml_system) {
            if ($aml_system->client_source == 1) {
                $client_details = \App\Models\CMClients::where(
                    "client_id",
                    $aml_system->client_source_detail
                )->first();
                $client_source_output =
                    "Other Client - " .
                    $client_details->company .
                    " - " .
                    $client_details->firstname .
                    " " .
                    $client_details->surname .
                    ".txt";
                $client_source = $client_source_output;
            } elseif ($aml_system->client_source == 2) {
                $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
                    "user_id",
                    $aml_system->client_source_detail
                )->first();
                $client_source_output =
                    "Partner - " .
                    $user_details->lastname .
                    " " .
                    $user_details->firstname .
                    ".txt";
                $client_source = $client_source_output;
            } elseif ($aml_system->client_source == 3) {
                $client_source_output =
                    "Note - " . $aml_system->client_source_detail . ".txt";
                $client_source = $client_source_output;
            } else {
                $client_source = "";
            }
        } else {
            $client_source = "";
        }
        $aml_bank = \App\Models\AmlBank::where("client_id", $client->client_id)->first();
        $aml_count = \App\Models\AmlBank::where("client_id", $client->client_id)->count();
        if (($aml_bank)) {
            $bank_output = $aml_count;
        } else {
            $bank_output = "";
        }
        $cli_det = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client->client_id)
            ->first();
        if (($cli_det)) {
            if ($cli_det->client_added == "") {
                $output_client_since = "No Date Set";
            } else {
                $explode_date = explode("/", $cli_det->client_added);
                $explode_hyphen_date = explode("-", $cli_det->client_added);
                if (is_countable($explode_date) > 1) {
                    $client_added = DateTime::createFromFormat(
                        "d/m/Y",
                        $cli_det->client_added
                    );
                    $client_added_since = $client_added->format("d-M-Y");
                } elseif (is_countable($explode_hyphen_date) > 1) {
                    $client_added = DateTime::createFromFormat(
                        "d-m-Y",
                        $cli_det->client_added
                    );
                    $client_added_since = $client_added->format("d-M-Y");
                } else {
                    $client_added_since = $cli_det->client_added;
                }
                $output_client_since = $client_added_since;
            }
        } else {
            $output_client_since = "No Date Set";
        }
        if (($aml_system)) {
            if ($aml_system->risk_category == 1) {
                $risk = "Green";
            } elseif ($aml_system->risk_category == 2) {
                $risk = "Yellow";
            } elseif ($aml_system->risk_category == 3) {
                $risk = "Red";
            } elseif ($aml_system->risk_category == 0) {
                $risk = "Green";
            }
            $products_services = $aml_system->products_services;
            $transaction_type = $aml_system->transaction_type;
            $risk_factors = $aml_system->risk_factors;
            $geo_area = $aml_system->geo_area;
            $politically_exposed =
                $aml_system->politically_exposed == 1 ? "Yes" : "No";
            $high_risk = $aml_system->high_risk == 1 ? "Yes" : "No";
        } else {
            $risk = "Green";
            $products_services = "";
            $transaction_type = "";
            $risk_factors = "";
            $geo_area = "";
            $politically_exposed = "No";
            $high_risk = "No";
        }
        $output .=
            '<div class="client_div" style="page-break-after: always;">
			<h4 style="text-align:center">Anti Money Launding Client Review - ' .
            $client->client_id .
            " - " .
            $client->company .
            '</h4>
			<table class="table own_table_white" border="0px" style="font-family: Roboto, sans-serif; font-size: 13px; width: 100%;">
				<tr>
					<td style="width:70%;line-height:25px;vertical-align:top">
						<div><strong>Client Code:</strong> ' .
            $client->client_id .
            '</div>
						<div><strong>Client Name:</strong> ' .
            $client->company .
            '</div>
						<div><strong>Contact:</strong> ' .
            $client->firstname .
            " " .
            $client->surname .
            '</div>
					</td>
					<td style="width:15%;line-height:25px;vertical-align:top"><strong>Client Address:</strong></td>
					<td style="line-height:25px">
						<div>';
        if ($client->address1 != "") {
            $output .= $client->address1 . "<br/>";
        }
        if ($client->address2 != "") {
            $output .= $client->address2 . "<br/>";
        }
        if ($client->address3 != "") {
            $output .= $client->address3 . "<br/>";
        }
        if ($client->address4 != "") {
            $output .= $client->address4 . "<br/>";
        }
        if ($client->address5 != "") {
            $output .= $client->address5 . "<br/>";
        }
        $output .=
            '</div>
					</td>
				</tr>
			</table>
			<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; width: 100%;margin-top:20px">
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Client Source:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $client_source .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Date Client Since:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $output_client_since .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Client Identity:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $output_attached .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Products & Services:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $products_services .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Transaction Type:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $transaction_type .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Risk Factors:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $risk_factors .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Geo Area of Operation:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $geo_area .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Politically Exposed Person:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $politically_exposed .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">High Risk Country:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $high_risk .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Legal Format of Company:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $client->tye .
            '</td>
				</tr>';
        if (($aml_system)) {
            if ($aml_system->last_email_sent == "0000-00-00 00:00:00") {
                $email_sent = "";
            } else {
                $email_sent = date(
                    "d M Y @ H:i",
                    strtotime($aml_system->last_email_sent)
                );
            }
        } else {
            $email_sent = "";
        }
        $output .=
            '<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Bank Account:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $bank_output .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">File Review:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $output_reveiw .
            '</td>
				</tr>
				<tr>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">Risk Category:</td>
					<td style="text-align: left;border:1px solid #000;padding:7px;line-height:20px">' .
            $risk .
            '</td>
				</tr>
			</table>
		</div>
		';
        //$htmlval = $request->get('htmlval');
        $pdf = PDF::loadHTML($output);
        $pdf->setPaper("A4", "portrait	");
        $time = time();
        $pdf->save(
            "public/papers/AML Report for " . $client->company . "_" . $time . ".pdf"
        );
        echo "AML Report for " . $client->company . "_" . $time . ".pdf";
    }
    public function aml_download_report_pdfs(Request $request)
    {
        $htmlval = $request->get("htmlval");
        $pdf = PDF::loadHTML($htmlval);
        $pdf->setPaper("A4", "landscape");
        $time = time();
        // $upload_dir = 'public/papers/'.$time;
        // if(!file_exists($upload_dir))
        // {
        // 	mkdir($upload_dir);
        // }
        //$pdf->save($upload_dir.'/AML Report.pdf');
        $pdf->save("public/papers/AML Report_" . $time . ".pdf");
        //echo $time.'||AML Report.pdf';
        echo "AML Report_" . $time . ".pdf";
    }
    public function aml_remove_dropzone_attachment(Request $request)
    {
        $attachment_id = $_POST["attachment_id"];
        $check_network = \App\Models\AmlAttachment::where("id", $attachment_id)->first();
        \App\Models\AmlAttachment::where("id", $attachment_id)->delete();
    }
    public function notify_tasks_aml(Request $request)
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
                "id",
                "email",
                "email2"
            )
            ->orderBy("id", "asc")
            ->get();
        $output =
            '<p><input type="checkbox" name="notify_all_clients" class="notify_all_clients" id="notify_all_clients"><label for="notify_all_clients">Select All Clients</label></p><table class="table own_table_white" id="request_manage_expand" style="width:100%">';
        if (is_countable($clientlist)) {
            $output .=
                "<thead><tr><th>Client ID</th><th>Client</th><th>Notify</th><th>ID Files</th><th>Primary Email</th><th>Secondary Email</th></tr></thead>";
            foreach ($clientlist as $client) {
                $disabled = "";
                if ($client->active != "") {
                    if ($client->active == 2) {
                        $disabled = "inactive";
                    }
                    $check_color = \App\Models\CMClass::where(
                        "id",
                        $client->active
                    )->first();
                    $style =
                        "color:#" .
                        $check_color->classcolor .
                        ";font-weight:600";
                    $class = "";
                } else {
                    $style = "color:#000 !important;font-weight:600";
                }
                $get_identity = \App\Models\AmlAttachment::where(
                    "client_id",
                    $client->client_id
                )->count();
                if ($get_identity > 0) {
                    $identity_received = "identity_received";
                } else {
                    $identity_received = "";
                }
                $aml_attachement = \App\Models\AmlAttachment::where(
                    "client_id",
                    $client->client_id
                )->get();
                if (is_countable($aml_attachement) != "") {
                    $image_plus_sapce = "margin-top:10px;";
                } else {
                    $image_plus_sapce = "margin-top:0px;";
                }
                $output_attached = "";
                if (is_countable($aml_attachement)) {
                    foreach ($aml_attachement as $attached) {
                        if ($attached->identity_type == 1) {
                            $iden = "P";
                        } else {
                            $iden = "O";
                        }
                        $current_date = strtotime(date("Y-m-d"));
                        $expiry_date = strtotime($attached->expiry_date);
                        $attach_color = "#000";
                        if ($attached->expiry_date != "0000-00-00") {
                            if ($current_date > $expiry_date) {
                                $attach_color = "#f00";
                            }
                        }
                        if ($attached->standard_name == "") {
                            $output_attached .=
                                '
                    <a class="id_attach_' .
                                $attached->id .
                                '" href="' .
                                URL::to(
                                    "/" .
                                        $attached->url .
                                        "/" .
                                        $attached->attachment
                                ) .
                                '" style="color:' .
                                $attach_color .
                                '" download>' .
                                $attached->attachment .
                                " (" .
                                $iden .
                                ")</a><br/>";
                        } else {
                            $output_attached .=
                                '
                    <a class="id_attach_' .
                                $attached->id .
                                '" href="' .
                                URL::to(
                                    "/" .
                                        $attached->url .
                                        "/" .
                                        $attached->attachment
                                ) .
                                '" style="color:' .
                                $attach_color .
                                '" download="' .
                                $attached->standard_name .
                                '">' .
                                $attached->standard_name .
                                " (" .
                                $iden .
                                ")</a><br/>";
                        }
                    }
                } else {
                    $output_attached .= "";
                }
                $url = URL::to(
                    "user/aml_upload_images_add?client_id=" . $client->client_id
                );
                $output .=
                    '<tr class="' .
                    $disabled .
                    " " .
                    $identity_received .
                    '">
					<td style="' .
                    $style .
                    '">' .
                    $client->client_id .
                    '</td>
					<td style="' .
                    $style .
                    '">' .
                    $client->company .
                    '</td>
					<td style="text-align:center">
						<input type="checkbox" name="notify_option" class="notify_option" data-element="' .
                    $client->client_id .
                    '" value="1"><label >&nbsp;</label>
					</td>
					<td style="color:#000 !important;">
						<div id="client_identity_' .
                    $client->client_id .
                    '">' .
                    $output_attached .
                    '</div>
                                    <i class="fa fa-plus fa-plus-add add_client_identity_files" style="cursor: pointer; color: #000; ' .
                    $image_plus_sapce .
                    '" aria-hidden="true" title="Add Attachment" data-element="' .
                    $client->client_id .
                    '"></i> 
                 <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>
                    <div id="add_attachments_div">
                    </div>
                <div class="img_div img_div_add" id="img_div_' .
                    $client->client_id .
                    '" style="z-index:9999999; margin-left: -120px; min-height: 275px">
                <form name="image_form" style="margin-bottom: 0px !important;" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">                 
                </form>                
                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files <br/>at a time. If you drop more than 300 <br/>files then the files uploading process<br/> will be crashed. </p>
                  <form action="' .
                    $url .
                    '" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload2" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input name="_token" type="hidden" value="' .
                    $client->client_id .
                    '">                  
                  </form>                
                </div>
                <div class="select_button" style=" margin-top: 10px;">
                    <ul>                                    
                    <li><a href="javascript:" class="image_submit" data-element="' .
                    $client->client_id .
                    '" style="font-size: 13px; font-weight: 500;">Submit</a></li>
                  </ul>
                </div>
               </div>
					</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="' .
                    $client->email .
                    '" data-element="' .
                    $client->client_id .
                    '" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="' .
                    $client->email2 .
                    '" data-element="' .
                    $client->client_id .
                    '" readonly></td>
				</tr>';
            }
        }
        $aml_settings = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $html = $aml_settings->email_body;
        $output .= '</table>';
        if($aml_settings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
        }
        else {
            $image_url = URL::to($aml_settings->email_header_url.'/'.$aml_settings->email_header_filename);
        }
        $output.='<div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top: 9px;">Header Image:</label>
            </div>
            <div class="col-md-10">
              <img src="'.$image_url.'" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
        </div>
		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">
            '.$html.'
		</textarea>';
        echo $output;
    }
    public function email_notify_aml(Request $request)
    {
        $email = $request->get("email");
        $time = $request->get("timeval");
        $message = $request->get("message");
        $user_details =\App\Models\User::where('user_id',Session::get('userid'))->first();
        $aml_settings = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $admin_cc = $aml_settings->aml_cc_email;
        $from = $user_details->email;
        $to = trim($email);
        $cc = $admin_cc;
        $data["sentmails"] = $to . " , " . $cc;
        $data["logo"] = getEmailLogo('aml');
        $data["message"] = $message;
        $data["signature"] = $aml_settings->email_signature;
        $contentmessage = view("user/email_notify", $data)->render();
        //$subject = "GBS & Co: Fraud and Anti Money Laundering ID Required";

        $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
        $subject = $practice_details->practice_name." - Anti Money Laundering ID Request";

        $email = new PHPMailer();
        if ($to != "") {
            $get_client = \App\Models\CMClients::where(
                "practice_code",
                Session::get("user_practice_code")
            )
                ->where("email", $to)
                ->orwhere("email2", $to)
                ->first();
            if (is_countable($get_client)) {
                $datamessage["message_id"] = $time;
                $datamessage["message_from"] = 0;
                $datamessage["subject"] = $subject;
                $datamessage["message"] = $contentmessage;
                $datamessage["client_ids"] = $get_client->client_id;
                if ($get_client->email == $to) {
                    $datamessage["primary_emails"] = $get_client->email;
                } else {
                    $datamessage["secondary_emails"] = $get_client->email2;
                }
                $datamessage["source"] = "AML SYSTEM";
                $datamessage["attachments"] = "";
                $datamessage["date_sent"] = date("Y-m-d H:i:s");
                $datamessage["date_saved"] = date("Y-m-d H:i:s");
                $datamessage["status"] = 1;
                $datamessage['practice_code'] = Session::get('user_practice_code');
                \App\Models\Messageus::insert($datamessage);
            }
            $email->SetFrom($from); //Name is optional
            $email->Subject = $subject;
            $email->Body = $contentmessage;
            $email->AddCC($admin_cc);
            $email->IsHTML(true);
            $email->AddAddress($to);
            $email->Send();
        }
    }
    public function aml_edit_email_unsent_files(Request $request)
    {
        $client_id = $request->get("client_id");
        $result = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        if ($result->email2 != "") {
            $to_email = $result->email . ", " . $result->email2;
        } else {
            $to_email = $result->email;
        }
        $aml_system = \App\Models\AmlSystem::where("client_id", $client_id)->first();
        if (is_countable($aml_system)) {
            $date = date("d F Y", strtotime($aml_system->last_email_sent));
            $time = date("H:i", strtotime($aml_system->last_email_sent));
            $last_date = $date . " @ " . $time;
        } else {
            $date = date("d F Y");
            $time = date("H:i");
            $last_date = $date . " @ " . $time;
        }
        $aml_settings = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $admin_cc = $aml_settings->aml_cc_email;
        $data["sentmails"] = $to_email . ", " . $admin_cc;
        $data["logo"] = getEmailLogo('aml');
        $data["salutation"] = $result->salutation;
        //$contentmessage = view("user/aml_email_content", $data)->render();

        $html = '<p>'.$result->salutation.'</p>'.$aml_settings->email_body.'<br/><br/><b>This Email has been sent to : </b> '.$data["sentmails"].'<br/><br/>'.$aml_settings->email_signature;

        $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
        $subject = $practice_details->practice_name." - Anti Money Laundering ID Request";


        echo json_encode([
            "html" => $html,
            "to" => $to_email,
            "subject" => $subject,
            "last_email_sent" => $last_date,
        ]);
    }
    public function aml_email_unsent_files(Request $request)
    {
        $client_id = $request->get("client_id");
        $det_task = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("client_id", $client_id)
            ->first();
        $from = $request->get("from");
        $toemails = $request->get("to") . "," . $request->get("cc");
        $sentmails = $request->get("to") . ", " . $request->get("cc");
        $subject = $request->get("subject");
        $message = $request->get("content");
        $explode = explode(",", $toemails);
        $data["sentmails"] = $sentmails;
        if (is_countable($explode)) {
            foreach ($explode as $exp) {
                $to = trim($exp);
                $data["logo"] = getEmailLogo('aml');
                $data["message"] = $message;
                $contentmessage = view("user/aml_email_share_paper", $data);
                $email = new PHPMailer();
                $email->SetFrom($from); //Name is optional
                $email->Subject = $subject;
                $email->Body = $contentmessage;
                $email->IsHTML(true);
                $email->AddAddress($to);
                $email->Send();
            }
            $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("email", $from)->first();
            if (is_countable($user_details)) {
                $user_from = $user_details->user_id;
            } else {
                $user_from = 0;
            }
            $datamessage["message_id"] = time();
            $datamessage["message_from"] = $user_from;
            $datamessage["subject"] = $subject;
            $datamessage["message"] = $contentmessage;
            $datamessage["source"] = "AML SYSTEM";
            $datamessage["client_ids"] = $client_id;
            $datamessage["primary_emails"] = $toemails;
            $datamessage["date_sent"] = date("Y-m-d H:i:s");
            $datamessage["date_saved"] = date("Y-m-d H:i:s");
            $datamessage["status"] = 1;
            $datamessage['practice_code'] = Session::get('user_practice_code');
            \App\Models\Messageus::insert($datamessage);
            $aml_system = \App\Models\AmlSystem::where("client_id", $client_id)->first();
            if (is_countable($aml_system)) {
                $date = date("Y-m-d H:i:s");
                \App\Models\AmlSystem::where("client_id", $client_id)->update([
                    "last_email_sent" => $date,
                ]);
            } else {
                $date = date("Y-m-d H:i:s");
                $dataval["last_email_sent"] = $date;
                $dataval["client_id"] = $client_id;
                $dataval["client_source"] = 0;
                $dataval["review"] = 0;
                $dataval["risk_category"] = 0;
                \App\Models\AmlSystem::insert($dataval);
            }
            $dateformat = date("d M Y @ H:i", strtotime($date));
            echo $dateformat;
            // return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('message', 'Email Sent Successfully');
        } else {
            echo "0";
            // return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('error', 'Email Field is empty so email is not sent');
        }
    }
    public function standard_file_name(Request $request)
    {
        $get_clients = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )->get();
        if (is_countable($get_clients)) {
            foreach ($get_clients as $client) {
                $get_attachments = \App\Models\AmlAttachment::where(
                    "client_id",
                    $client->client_id
                )->get();
                $i = 1;
                if (is_countable($get_attachments)) {
                    foreach ($get_attachments as $attach) {
                        $get_ext = explode(".", $attach->attachment);
                        $data["standard_name"] =
                            $client->client_id .
                            "_IDFile_" .
                            $i .
                            "." .
                            end($get_ext);
                        \App\Models\AmlAttachment::where("id", $attach->id)->update(
                            $data
                        );
                        $i++;
                    }
                }
            }
        }
        return Redirect::back()->with(
            "message",
            "Standard File Name has been set for all the clients' attachments."
        );
    }
    public function standard_file_name_single(Request $request)
    {
        $client_id = $request->get("client_id");
        $get_attachments = \App\Models\AmlAttachment::where("client_id", $client_id)->get();
        $i = 1;
        $output = "";
        if (is_countable($get_attachments)) {
            foreach ($get_attachments as $attachment) {
                $get_ext = explode(".", $attachment->attachment);
                $data["standard_name"] =
                    $client_id . "_IDFile_" . $i . "." . end($get_ext);
                \App\Models\AmlAttachment::where("id", $attachment->id)->update(
                    $data
                );
                $i++;
                $current_date = strtotime(date("Y-m-d"));
                $expiry_date = strtotime($attachment->expiry_date);
                $attach_color = "#000";
                if ($attachment->expiry_date != "0000-00-00") {
                    if ($current_date > $expiry_date) {
                        $attach_color = "#f00";
                    }
                }
                $filename =
                    '
                  <a class="id_attach_' .
                    $attachment->id .
                    '" href="' .
                    URL::to(
                        "/" . $attachment->url . "/" . $attachment->attachment
                    ) .
                    '" title="' .
                    $data["standard_name"] .
                    '" style="color:' .
                    $attach_color .
                    '" download>' .
                    $data["standard_name"] .
                    "</a>";
                $output .=
                    '<tr class="client_identity_tr">
                	<td>' .
                    $filename .
                    '</td>
                	<td>
                		<input type="radio" name="identity_type_' .
                    $attachment->id .
                    '" class="identity_type identity_type_' .
                    $client_id .
                    '" id="identity_type_photo_' .
                    $attachment->id .
                    '"';
                if ($attachment->identity_type == 1) {
                    $output .= "checked";
                }
                $output .=
                    ' data-element="' .
                    $client_id .
                    '" data-attach="' .
                    $attachment->id .
                    '" value="1"><label for="identity_type_photo_' .
                    $attachment->id .
                    '">Photo ID</label>
                		<input type="radio" name="identity_type_' .
                    $attachment->id .
                    '" class="identity_type identity_type_' .
                    $client_id .
                    '" id="identity_type_other_' .
                    $attachment->id .
                    '"';
                if ($attachment->identity_type == 0) {
                    $output .= "checked";
                }
                $output .=
                    ' data-element="' .
                    $client_id .
                    '" data-attach="' .
                    $attachment->id .
                    '" value="0"><label for="identity_type_other_' .
                    $attachment->id .
                    '">Other ID</label>
                	</td>
                	<td>';
                if (
                    $attachment->expiry_date == "0000-00-00" ||
                    $attachment->expiry_date == ""
                ) {
                    $output .=
                        '<input type="text" name="identity_expiry_date" class="identity_expiry_date" placeholder="dd-mmm-yyyy" value="" data-element="' .
                        $attachment->id .
                        '">';
                } else {
                    $output .=
                        '<input type="text" name="identity_expiry_date" class="identity_expiry_date" placeholder="dd-mmm-yyyy" value="' .
                        date("d-F-Y", strtotime($attachment->expiry_date)) .
                        '" data-element="' .
                        $attachment->id .
                        '">';
                }
                $output .=
                    '</td>
                	<td>
                		<a class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="' .
                    $attachment->id .
                    '"></a>
                	</td>
                </tr>';
            }
        }
        echo $output;
    }
    public function generate_aml_text_file(Request $request)
    {
        $text = $request->get("text");
        $upload_dir = "public/papers/aml_client_source";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $files = glob($upload_dir); // get all file names
        foreach ($files as $file) {
            // iterate files
            if (is_file($file)) {
                unlink($file);
            } // delete file
        }
        ($myfile = fopen($upload_dir . "/" . $text . ".txt", "w")) or
            die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
        echo $text . ".txt";
    }
    public function aml_system_add_trade(Request $request)
    {
        $current_client_id = $request->get("current_client_id");
        $products_services = $request->get("products_services");
        $transaction_type = $request->get("transaction_type");
        $risk_factors = $request->get("risk_factors");
        $geo_area = $request->get("geo_area");
        $politically_exposed = $request->get("politically_exposed");
        $high_risk = $request->get("high_risk");
        $upload_dir = "uploads/aml_trade_details";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir . "/" . $current_client_id;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $politically_exposed_text = "No";
        $high_risk_text = "No";
        if ($politically_exposed == "1") {
            $politically_exposed_text = "Yes";
        }
        if ($high_risk == "1") {
            $high_risk_text = "Yes";
        }
        $text =
            "Products & Services :" .
            $products_services .
            PHP_EOL .
            "Type of Transaction :" .
            $transaction_type .
            PHP_EOL .
            "Risk Factors :" .
            $risk_factors .
            PHP_EOL .
            "Geo Area of Operation :" .
            $geo_area .
            PHP_EOL .
            "Politically Exposed Person :" .
            $politically_exposed_text .
            PHP_EOL .
            "High Risk Country :" .
            $high_risk_text;
        ($myfile = fopen($upload_dir . "/Trade Details.txt", "w")) or
            die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
        $data["trade_details"] = "Trade Details.txt";
        $data["products_services"] = $products_services;
        $data["transaction_type"] = $transaction_type;
        $data["risk_factors"] = $risk_factors;
        $data["geo_area"] = $geo_area;
        $data["politically_exposed"] = $politically_exposed;
        $data["high_risk"] = $high_risk;
        $check_aml = \App\Models\AmlSystem::where("client_id", $current_client_id)->first();
        if (is_countable($check_aml)) {
            \App\Models\AmlSystem::where("client_id", $current_client_id)->update(
                $data
            );
        } else {
            $data["client_id"] = $current_client_id;
            \App\Models\AmlSystem::where("client_id", $current_client_id)->insert(
                $data
            );
        }
        $cm_client = \App\Models\CMClients::where("client_id", $current_client_id)->first();
        $output =
            ' <div class="trade_action_div trade_action_div' .
            $current_client_id .
            '"><a href="' .
            URL::to("uploads/aml_trade_details") .
            "/" .
            $current_client_id .
            "/" .
            $data["trade_details"] .
            '" download>' .
            $data["trade_details"] .
            '</a><br/>
		<a href="javascript:" class="fa fa-edit trade_details_edit trade_details_' .
            $current_client_id .
            '" data-element="' .
            $current_client_id .
            '"></a></div>
                                  <form class="trade_details_form" id="trade_details_form_' .
            $current_client_id .
            '" style="display:none">
                                    <table class="table" style="border:0px solid">
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Products & Services:</div></td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="products_services" class="form-control"  id="products_services' .
            $current_client_id .
            '" placeholder="Products & Services" required></td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Transaction Type:</div></td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="transaction_type" class="form-control" id="transaction_type' .
            $current_client_id .
            '" placeholder="Transaction Type" required></td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Risk Factors:</div></td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="risk_factors" class="form-control" id="risk_factors' .
            $current_client_id .
            '" placeholder="Risk factors" required></td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Geo Area of Operation:</div></td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="geo_area" class="form-control" id="geo_area' .
            $current_client_id .
            '" placeholder="Geo Area of Operation" required></td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">Politically Exposed Person:</td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">
                                          <input type="radio" name="politically_exposed" class="politically_exposed' .
            $current_client_id .
            '" id="politically_exposed_yes' .
            $current_client_id .
            '" value="1"><label for="politically_exposed_yes' .
            $current_client_id .
            '">Yes</label>
                                          <input type="radio" name="politically_exposed" class="politically_exposed' .
            $current_client_id .
            '" id="politically_exposed_no' .
            $current_client_id .
            '" value="0" checked><label for="politically_exposed_no' .
            $current_client_id .
            '">No</label>
                                        </td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">High Risk Country:</td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">
                                          <input type="radio" name="high_risk" class="high_risk' .
            $current_client_id .
            '" id="high_risk_yes' .
            $current_client_id .
            '" value="1"><label for="high_risk_yes' .
            $current_client_id .
            '">Yes</label>
                                          <input type="radio" name="high_risk" class="high_risk' .
            $current_client_id .
            '" id="high_risk_no' .
            $current_client_id .
            '" value="0" checked><label for="high_risk_no' .
            $current_client_id .
            '">No</label>
                                        </td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Legal Format of Company:</div></td>
                                        <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="legal_format" class="form-control" id="legal_format' .
            $current_client_id .
            '" placeholder="Legal Format of Company" value="' .
            $cm_client->tye .
            '" disabled>
                                          <input type="hidden" id="trade_current_client_id' .
            $current_client_id .
            '" name="' .
            $current_client_id .
            '" />
                                        </td>
                                      </tr>
                                      <tr style="border:0px solid">
                                        <td colspan="2" style="border:0px solid; padding-top:2px;padding-bottom: 2px;text-align: center;">
                                          <input type="button" class="common_black_button trade_submit" data-element="' .
            $current_client_id .
            '" value="Add / Update Trade details">
                                          <input type="button" class="common_black_button cancel_trade" data-element="' .
            $current_client_id .
            '" value="Close">
                                        </td>
                                      </tr>
                                    </table>
                                  </form>';
        echo json_encode(["output" => $output, "id" => $current_client_id]);
    }
    public function get_trade_details(Request $request)
    {
        $client_id = $request->get("current_client");
        $get_aml = \App\Models\AmlSystem::where("client_id", $client_id)->first();
        if (is_countable($get_aml)) {
            echo json_encode([
                "products_services" => $get_aml->products_services,
                "transaction_type" => $get_aml->transaction_type,
                "risk_factors" => $get_aml->risk_factors,
                "geo_area" => $get_aml->geo_area,
                "politically_exposed" => $get_aml->politically_exposed,
                "high_risk" => $get_aml->high_risk,
            ]);
        }
    }
    public function get_aml_client_content(Request $request)
    {
        $client_id = $request->get("client_id");
        $client = \App\Models\CMClients::select(
            "client_id",
            "firstname",
            "surname",
            "tye",
            "company",
            "status",
            "active",
            "id"
        )
            ->where("client_id", $client_id)
            ->first();
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
        $aml_system = \App\Models\AmlSystem::where(
            "client_id",
            $client->client_id
        )->first();
        if (is_countable($aml_system)) {
            $risk_select = $aml_system->risk_category;
        } else {
            $risk_select = "";
        }
        $aml_attachement = \App\Models\AmlAttachment::where(
            "client_id",
            $client->client_id
        )->get();
        $output_attached = "";
        if (is_countable($aml_attachement)) {
            foreach ($aml_attachement as $attached) {
                if ($attached->identity_type == 1) {
                    $iden = "P";
                } else {
                    $iden = "O";
                }
                $current_date = strtotime(date("Y-m-d"));
                $expiry_date = strtotime($attached->expiry_date);
                $attach_color = "#000";
                if ($attached->expiry_date != "0000-00-00") {
                    if ($current_date > $expiry_date) {
                        $attach_color = "#f00";
                    }
                }
                if ($attached->standard_name == "") {
                    $output_attached .=
                        '
              <a class="id_attach_' .
                        $attached->id .
                        '" href="' .
                        URL::to(
                            "/" . $attached->url . "/" . $attached->attachment
                        ) .
                        '" style="color:' .
                        $attach_color .
                        '" download>' .
                        $attached->attachment .
                        " (" .
                        $iden .
                        ")</a><br/>";
                } else {
                    $output_attached .=
                        '
              <a class="id_attach_' .
                        $attached->id .
                        '" href="' .
                        URL::to(
                            "/" . $attached->url . "/" . $attached->attachment
                        ) .
                        '" style="color:' .
                        $attach_color .
                        '" download="' .
                        $attached->standard_name .
                        '">' .
                        $attached->standard_name .
                        " (" .
                        $iden .
                        ")</a><br/>";
                }
            }
        } else {
            $output_attached .= "";
        }
        if (is_countable($aml_attachement) != "") {
            $image_plus_sapce = "margin-top:10px;";
        } else {
            $image_plus_sapce = "margin-top:0px;";
        }
        if (is_countable($aml_system)) {
            if ($aml_system->review == 1) {
                $output_reveiw =
                    "Date:" .
                    date("d-M-Y", strtotime($aml_system->review_date)) .
                    "</br/>Review By: " .
                    $aml_system->file_review .
                    '<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="' .
                    $client->client_id .
                    '"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="' .
                    $client->client_id .
                    '"></i></a>';
            } else {
                $output_reveiw =
                    '
            <div class="select_button" style=" margin-left: 10px;">
              <ul>                                    
              <li><a href="javascript:" class="review_by" data-element="' .
                    $client->client_id .
                    '" style="font-size: 13px; font-weight: 500;">Review By</a></li>
            </ul>
          </div>';
            }
        } else {
            $output_reveiw =
                '
          <div class="select_button" style=" margin-left: 10px;">
            <ul>                                    
              <li><a href="javascript:" class="review_by" data-element="' .
                $client->client_id .
                '" style="font-size: 13px; font-weight: 500;">Review By</a></li>
            </ul>
          </div>';
        }
        if (is_countable($aml_system)) {
            if ($aml_system->client_source == 1) {
                $client_details = \App\Models\CMClients::where(
                    "practice_code",
                    Session::get("user_practice_code")
                )
                    ->where("client_id", $aml_system->client_source_detail)
                    ->first();
                $client_source =
                    '<a href="javascript:" data-text="Other Client - ' .
                    $client_details->company .
                    " - " .
                    $client_details->firstname .
                    " " .
                    $client_details->surname .
                    '" class="download_client_source"> Other Client - ' .
                    $client_details->company .
                    " - " .
                    $client_details->firstname .
                    " " .
                    $client_details->surname .
                    '.txt</a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    '></i></a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    "></i></a>";
            } elseif ($aml_system->client_source == 2) {
                $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where(
                    "user_id",
                    $aml_system->client_source_detail
                )->first();
                $client_source =
                    '<a href="javascript:" data-text="Partner - ' .
                    $user_details->lastname .
                    " " .
                    $user_details->firstname .
                    '" class="download_client_source">Partner - ' .
                    $user_details->lastname .
                    " " .
                    $user_details->firstname .
                    '.txt</a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    '></i></a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    "></i></a>";
            } elseif ($aml_system->client_source == 3) {
                $client_source =
                    '<a href="javascript:" data-text="Note - ' .
                    $aml_system->client_source_detail .
                    '" class="download_client_source">Note - ' .
                    $aml_system->client_source_detail .
                    '.txt</a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    '></i></a>
            <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element=' .
                    $aml_system->client_id .
                    "></i></a>";
            } else {
                $client_source =
                    '<input type="radio" name="client_source" class="other_client" value="1" id="other_client_td_' .
                    $client->client_id .
                    '" data-element="' .
                    $client->client_id .
                    '" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_' .
                    $client->client_id .
                    '">Other Client</label><br/>
            <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="' .
                    $client->client_id .
                    '" id="personal_partner_td_' .
                    $client->client_id .
                    '"><label for="personal_partner_' .
                    $client->client_id .
                    '">Personal Acquaintance of Partner</label><br/>
            <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="' .
                    $client->client_id .
                    '" id="reply_note_td_' .
                    $client->client_id .
                    '"><label for="reply_note_' .
                    $client->client_id .
                    '">Reply to Advert / Walk in</label>';
            }
        } else {
            $client_source =
                '<input type="radio" name="client_source" class="other_client" value="1" id="other_client_td_' .
                $client->client_id .
                '" data-element="' .
                $client->client_id .
                '" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_' .
                $client->client_id .
                '">Other Client</label><br/>
            <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="' .
                $client->client_id .
                '" id="personal_partner_td_' .
                $client->client_id .
                '"><label for="personal_partner_' .
                $client->client_id .
                '">Personal Acquaintance of Partner</label><br/>
            <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="' .
                $client->client_id .
                '" id="reply_note_td_' .
                $client->client_id .
                '"><label for="reply_note_' .
                $client->client_id .
                '">Reply to Advert / Walk in</label>';
        }
        $aml_bank = \App\Models\AmlBank::where("client_id", $client->client_id)->first();
        $aml_count = \App\Models\AmlBank::where("client_id", $client->client_id)->count();
        if (is_countable($aml_bank)) {
            $bank_output =
                '<a href="javascript:" class="bank_detail_class" data-element="' .
                $client->client_id .
                '">' .
                $aml_count .
                '</a>
          <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="' .
                $client->client_id .
                '" style="margin-left:10px;"></i></a>
          ';
        } else {
            $bank_output =
                '
          <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="' .
                $client->client_id .
                '"></i></a>
          ';
        }
        $cli_det = \App\Models\CMClients::where("client_id", $client->client_id)->first();
        if (is_countable($cli_det)) {
            if ($cli_det->client_added == "") {
                $output_client_since = "No Date Set";
            } else {
                $explode_date = explode("/", $cli_det->client_added);
                $explode_hyphen_date = explode("-", $cli_det->client_added);
                if (is_countable($explode_date) > 1) {
                    $client_added = DateTime::createFromFormat(
                        "d/m/Y",
                        $cli_det->client_added
                    );
                    $client_added_since = $client_added->format("d-M-Y");
                } elseif (is_countable($explode_hyphen_date) > 1) {
                    $client_added = DateTime::createFromFormat(
                        "d-m-Y",
                        $cli_det->client_added
                    );
                    $client_added_since = $client_added->format("d-M-Y");
                } else {
                    $client_added_since = $cli_det->client_added;
                }
                $output_client_since = $client_added_since;
            }
        } else {
            $output_client_since = "No Date Set";
        }
        $output = "";
        $output .=
            '<tr>
          <td style="width: 30%;">Client Source</td>
          <td align="left" style="' .
            $style .
            '" id="client_source_td_' .
            $client->client_id .
            '">
            ' .
            $client_source .
            '
          </td>
        </tr>
        <tr>
          <td>Date Client Since</td>
          <td align="left" id="client_since_td_' .
            $client->client_id .
            '" style="' .
            $style .
            '">
            ' .
            $output_client_since .
            '
          </td>
        </tr>
        <tr>
          <td>Client Identity</td>
          <td align="left">
            <div id="client_identity_td_' .
            $client->client_id .
            '">' .
            $output_attached .
            '</div>
            <i class="fa fa-plus fa-plus-add add_client_identity_files" style="cursor: pointer; color: #000; ' .
            $image_plus_sapce .
            '" aria-hidden="true" title="Add Attachment" data-element="' .
            $client->client_id .
            '"></i> 
            <p id="attachments_text_td" style="display:none; font-weight: bold;">"Files Attached:</p>
            <div id="add_attachments_div_td">
            </div>
            <div class="img_div img_div_add" id="img_div_td_' .
            $client->client_id .
            '" style="z-index:9999999; margin-left: -120px; min-height: 275px">
              <form name="image_form" style="margin-bottom: 0px !important;" id="image_form_td" action="" method="post" enctype="multipart/form-data" style="text-align: left;"></form> 
              <div class="image_div_attachments">
                <p>You can only upload maximum 300 files <br/>at a time. If you drop more than 300 <br/>files then the files uploading process<br/> will be crashed. </p>
                <form action="' .
            URL::to(
                "user/aml_upload_images_add?client_id=" . $client->client_id
            ) .
            '" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadtd1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                    <input name="_token" type="hidden" value="' .
            $client->client_id .
            '">                  
                </form> 
              </div>
              <div class="select_button" style=" margin-top: 10px;">
                <ul>                                    
                  <li><a href="javascript:" class="image_submit" data-element="' .
            $client->client_id .
            '" style="font-size: 13px; font-weight: 500;">Submit</a></li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>Trade Details</td>
          <td id="client_trade_td_' .
            $client->client_id .
            '" style="text-align:center; width: 180px; ">
            <div class="trade_action_div trade_action_div' .
            $client->client_id .
            '" style="display:none">';
        if (is_countable($aml_system)) {
            if ($aml_system->trade_details != "") {
                $output .=
                    '<a href="' .
                    URL::to("uploads/aml_trade_details") .
                    "/" .
                    $client->client_id .
                    "/" .
                    $aml_system->trade_details .
                    '" download>' .
                    $aml_system->trade_details .
                    '</a><br/>
                <a href="javascript:" class="fa fa-edit trade_details_edit trade_details_' .
                    $client->client_id .
                    '" data-element="' .
                    $client->client_id .
                    '"></a>';
            } else {
                $output .=
                    '<a href="javascript:" class="fa fa-plus trade_details trade_details_' .
                    $client->client_id .
                    '" data-element="' .
                    $client->client_id .
                    '" data-client="' .
                    ($client->company == "")
                        ? $client->firstname . " & " . $client->surname
                        : $client->company . '"></a>';
            }
            $products_services = $aml_system->products_services;
            $transaction_type = $aml_system->transaction_type;
            $risk_factors = $aml_system->risk_factors;
            $geo_area = $aml_system->geo_area;
            $politically_exposed = $aml_system->politically_exposed;
            $high_risk = $aml_system->high_risk;
        } else {
            $output .=
                '<a href="javascript:" class="fa fa-plus trade_details trade_details_' .
                $client->client_id .
                '" data-element="' .
                $client->client_id .
                '" data-client="' .
                ($client->company == "")
                    ? $client->firstname . " & " . $client->surname
                    : $client->company . '"></a>';
            $products_services = "";
            $transaction_type = "";
            $risk_factors = "";
            $geo_area = "";
            $politically_exposed = 0;
            $high_risk = 0;
        }
        $output .=
            '</div>
            <form class="trade_details_form" id="trade_details_form_td_' .
            $client->client_id .
            '">
              <table class="table" style="border:0px solid">
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Products & Services:</div></td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="products_services" class="form-control products_services_td"  id="products_services_td' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '" placeholder="Products & Services" value="' .
            $products_services .
            '"></td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Transaction Type:</div></td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="transaction_type" class="form-control transaction_type_td" id="transaction_type_td' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '" placeholder="Transaction Type" value="' .
            $transaction_type .
            '"></td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Risk Factors:</div></td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="risk_factors" class="form-control risk_factors_td" id="risk_factors_td' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '" placeholder="Risk factors" value="' .
            $risk_factors .
            '"></td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Geo Area of Operation:</div></td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="geo_area" class="form-control geo_area_td" id="geo_area_td' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '" placeholder="Geo Area of Operation" value="' .
            $geo_area .
            '"></td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">Politically Exposed Person:</td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">
                    <input type="radio" name="politically_exposed" class="politically_exposed_td politically_exposed' .
            $client->client_id .
            '" id="politically_exposed_yes_td' .
            $client->client_id .
            '" value="1" data-element="' .
            $client->client_id .
            '"';
        if ($politically_exposed == 1) {
            $output .= "checked";
        }
        $output .=
            '><label for="politically_exposed_yes_td' .
            $client->client_id .
            '">Yes</label>
                    <input type="radio" name="politically_exposed" class="politically_exposed_td politically_exposed' .
            $client->client_id .
            '" id="politically_exposed_no_td' .
            $client->client_id .
            '" value="0" data-element="' .
            $client->client_id .
            '"';
        if ($politically_exposed == 0) {
            $output .= "checked";
        }
        $output .=
            '><label for="politically_exposed_no_td' .
            $client->client_id .
            '">No</label>
                  </td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">High Risk Country:</td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px">
                    <input type="radio" name="high_risk" class="high_risk_td high_risk' .
            $client->client_id .
            '" id="high_risk_yes_td' .
            $client->client_id .
            '" value="1" data-element="' .
            $client->client_id .
            '"';
        if ($high_risk == 1) {
            $output .= "checked";
        }
        $output .=
            '><label for="high_risk_yes_td' .
            $client->client_id .
            '">Yes</label>
                    <input type="radio" name="high_risk" class="high_risk_td high_risk' .
            $client->client_id .
            '" id="high_risk_no_td' .
            $client->client_id .
            '" value="0" data-element="' .
            $client->client_id .
            '"';
        if ($high_risk == 0) {
            $output .= "checked";
        }
        $output .=
            '><label for="high_risk_no_td' .
            $client->client_id .
            '">No</label>
                  </td>
                </tr>
                <tr style="border:0px solid">
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><div style="margin-top:10px">Legal Format of Company:</div></td>
                  <td style="border:0px solid;padding-top:2px;padding-bottom: 2px"><input type="text" name="legal_format" class="form-control" id="legal_format' .
            $client->client_id .
            '" placeholder="Legal Format of Company" value="' .
            $client->tye .
            '" disabled>
                    <input type="hidden" id="trade_current_client_id_td' .
            $client->client_id .
            '" name="' .
            $client->client_id .
            '" />
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
        <tr>
          <td>Email Unsent</td>';
        if (is_countable($aml_system)) {
            if ($aml_system->last_email_sent == "0000-00-00 00:00:00") {
                $email_sent = "";
            } else {
                $email_sent = date(
                    "d M Y @ H:i",
                    strtotime($aml_system->last_email_sent)
                );
            }
        } else {
            $email_sent = "";
        }
        $output .=
            '<td style="text-align:center; width: 180px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '"></a><br>' .
            $email_sent .
            '<br></td>
        </tr>
        <tr>
          <td>Bank Accounts</td>
          <td style="' .
            $style .
            '" align="left"  id="client_bank_td_' .
            $client->client_id .
            '">
            ' .
            $bank_output .
            '
          </td>
        </tr>
        <tr>
       	  <td>File Review</td>
          <td style="' .
            $style .
            '" align="left"   id="review_td_' .
            $client->client_id .
            '">
            ' .
            $output_reveiw .
            '
          </td>
        </tr>
        <tr>
          <td>Risk Category</td>
          <td style="' .
            $style .
            '" align="left">
            <select class="form-control risk_class_td" id="risk_class_td_' .
            $client->client_id .
            '" data-element="' .
            $client->client_id .
            '" >
              <option value="1"';
        if ($risk_select == 1) {
            $output .= "selected";
        } else {
            $output .= "";
        }
        $output .= '>Green</option>
              <option value="2"';
        if ($risk_select == 2) {
            $output .= "selected";
        } else {
            $output .= "";
        }
        $output .= '>Yellow</option>
              <option value="3"';
        if ($risk_select == 3) {
            $output .= "selected";
        } else {
            $output .= "";
        }
        $output .= '>Red</option>
            </select>
          </td>
        </tr>';
        echo json_encode([
            "output" => $output,
            "client_name" => $cli_det->company,
        ]);
    }
    public function import_aml_clients_details(Request $request)
    {
        if ($_FILES["new_file"]["name"] != "") {
            $uploads_dir =
                dirname($_SERVER["SCRIPT_FILENAME"]) . "/uploads/importfiles";
            $tmp_name = $_FILES["new_file"]["tmp_name"];
            $name = time() . "_" . $_FILES["new_file"]["name"];
            $errorlist = [];
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                $filepath = $uploads_dir . "/" . $name;
                $objPHPExcel = IOFactory::load($filepath);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    
                    $nrColumns = ord($highestColumn) - 64;
                    if ($highestRow > 100) {
                        $height = 100;
                    } else {
                        $height = $highestRow;
                    }
                    $id_label = $worksheet->getCellByColumnAndRow(1, 1);
                    $id_label = trim($id_label->getValue());
                    $prod_label = $worksheet->getCellByColumnAndRow(2, 1);
                    $prod_label = trim($prod_label->getValue());
                    $trans_label = $worksheet->getCellByColumnAndRow(3, 1);
                    $trans_label = trim($trans_label->getValue());
                    $risk_label = $worksheet->getCellByColumnAndRow(4, 1);
                    $risk_label = trim($risk_label->getValue());
                    if (
                        $id_label == "Client ID" &&
                        $prod_label == "Products & Services" &&
                        $trans_label == "Transaction Type" &&
                        $risk_label == "Risk Factors"
                    ) {
                        for ($row = 2; $row <= $height; ++$row) {
                            $id = $worksheet->getCellByColumnAndRow(1, $row);
                            $id = trim($id->getValue());
                            $products = $worksheet->getCellByColumnAndRow(
                                2,
                                $row
                            );
                            $products = trim($products->getValue());
                            $trans = $worksheet->getCellByColumnAndRow(3, $row);
                            $trans = trim($trans->getValue());
                            $risk = $worksheet->getCellByColumnAndRow(4, $row);
                            $risk = trim($risk->getValue());
                            $geo = $worksheet->getCellByColumnAndRow(5, $row);
                            $geo = trim($geo->getValue());
                            $politically = $worksheet->getCellByColumnAndRow(
                                6,
                                $row
                            );
                            $politically = trim($politically->getValue());
                            $high_risk = $worksheet->getCellByColumnAndRow(
                                7,
                                $row
                            );
                            $high_risk = trim($high_risk->getValue());
                            $review_date = $worksheet->getCellByColumnAndRow(
                                8,
                                $row
                            );
                            $review_date = trim($review_date->getValue());
                            $review_by = $worksheet->getCellByColumnAndRow(
                                9,
                                $row
                            );
                            $review_by = trim($review_by->getValue());
                            $data["products_services"] = $products;
                            $data["transaction_type"] = $trans;
                            $data["risk_factors"] = $risk;
                            $data["geo_area"] = $geo;
                            $politically_value = 0;
                            $high_risk_value = 0;
                            if ($politically == "Yes") {
                                $politically_value = 1;
                            }
                            if ($high_risk == "Yes") {
                                $high_risk_value = 1;
                            }
                            $data["politically_exposed"] = $politically_value;
                            $data["high_risk"] = $high_risk_value;
                            $review_date_val = "0000-00-00";
                            if ($review_date != "" || $review_by != "") {
                                $data["review"] = 1;
                                if ($review_date != "") {
                                    $date_exp = explode("/", $review_date);
                                    if (is_countable($date_exp) == 3) {
                                        $review_date_val =
                                            $date_exp[2] .
                                            "-" .
                                            $date_exp[1] .
                                            "-" .
                                            $date_exp[0];
                                    }
                                }
                            }
                            $data["review_date"] = $review_date_val;
                            $data["file_review"] = $review_by;
                            $aml_details = \App\Models\AmlSystem::where(
                                "client_id",
                                $id
                            )->first();
                            if (is_countable($aml_details)) {
                                \App\Models\AmlSystem::where(
                                    "client_id",
                                    $id
                                )->update($data);
                            }
                        }
                    } else {
                        return redirect("user/aml_system")->with(
                            "message",
                            "Import Failed! Invalid Import File"
                        );
                    }
                }
                if ($height >= $highestRow) {
                    return redirect("user/aml_system")->with(
                        "message",
                        "Clients Imported successfully."
                    );
                } else {
                    return redirect(
                        "user/aml_system?filename=" .
                            $name .
                            "&height=" .
                            $height .
                            "&round=2&highestrow=" .
                            $highestRow .
                            "&import_type_new=1"
                    );
                }
            }
        }
    }
    public function import_aml_clients_details_one(Request $request)
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
            $roundcount = $round * 100;
            $nextround = $round + 1;
            if ($highestRow > $roundcount) {
                $height = $roundcount;
            } else {
                $height = $highestRow;
            }
            for ($row = $offsetcount; $row <= $height; ++$row) {
                $id = $worksheet->getCellByColumnAndRow(1, $row);
                $id = trim($id->getValue());
                $products = $worksheet->getCellByColumnAndRow(2, $row);
                $products = trim($products->getValue());
                $trans = $worksheet->getCellByColumnAndRow(3, $row);
                $trans = trim($trans->getValue());
                $risk = $worksheet->getCellByColumnAndRow(4, $row);
                $risk = trim($risk->getValue());
                $geo = $worksheet->getCellByColumnAndRow(5, $row);
                $geo = trim($geo->getValue());
                $politically = $worksheet->getCellByColumnAndRow(6, $row);
                $politically = trim($politically->getValue());
                $high_risk = $worksheet->getCellByColumnAndRow(7, $row);
                $high_risk = trim($high_risk->getValue());
                $review_date = $worksheet->getCellByColumnAndRow(8, $row);
                $review_date = trim($review_date->getValue());
                $review_by = $worksheet->getCellByColumnAndRow(9, $row);
                $review_by = trim($review_by->getValue());
                $data["products_services"] = $products;
                $data["transaction_type"] = $trans;
                $data["risk_factors"] = $risk;
                $data["geo_area"] = $geo;
                $politically_value = 0;
                $high_risk_value = 0;
                if ($politically == "Yes") {
                    $politically_value = 1;
                }
                if ($high_risk == "Yes") {
                    $high_risk_value = 1;
                }
                $data["politically_exposed"] = $politically_value;
                $data["high_risk"] = $high_risk_value;
                $review_date_val = "0000-00-00";
                if ($review_date != "" || $review_by != "") {
                    $data["review"] = 1;
                    if ($review_date != "") {
                        $date_exp = explode("/", $review_date);
                        if (is_countable($date_exp) == 3) {
                            $review_date_val =
                                $date_exp[2] .
                                "-" .
                                $date_exp[1] .
                                "-" .
                                $date_exp[0];
                        }
                    }
                }
                $data["review_date"] = $review_date_val;
                $data["file_review"] = $review_by;
                $aml_details = \App\Models\AmlSystem::where("client_id", $id)->first();
                if (is_countable($aml_details)) {
                    \App\Models\AmlSystem::where("client_id", $id)->update($data);
                }
            }
        }
        if ($height >= $highestRow) {
            return redirect("user/aml_system")->with(
                "message",
                "Clients Imported successfully."
            );
        } else {
            return redirect(
                "user/aml_system?filename=" .
                    $name .
                    "&height=" .
                    $height .
                    "&round=" .
                    $nextround .
                    "&highestrow=" .
                    $highestRow .
                    "&import_type_new=1"
            );
        }
    }
    public function get_aml_client_identity_files(Request $request)
    {
        $client_id = $request->get("client_id");
        $cm_clients = \App\Models\CMClients::where("client_id", $client_id)->first();
        $get_attachments = \App\Models\AmlAttachment::where("client_id", $client_id)->get();
        $output = "";
        if (is_countable($get_attachments)) {
            foreach ($get_attachments as $attachment) {
                $current_date = strtotime(date("Y-m-d"));
                $expiry_date = strtotime($attachment->expiry_date);
                $attach_color = "#000";
                if ($attachment->expiry_date != "0000-00-00") {
                    if ($current_date > $expiry_date) {
                        $attach_color = "#f00";
                    }
                }
                if ($attachment->standard_name == "") {
                    if (strlen($attachment->attachment) > 20) {
                        $att = substr($attachment->attachment, 0, 20);
                    } else {
                        $att = $attachment->attachment;
                    }
                    $filename =
                        '
                  <a class="id_attach_' .
                        $attachment->id .
                        '" href="' .
                        URL::to(
                            "/" .
                                $attachment->url .
                                "/" .
                                $attachment->attachment
                        ) .
                        '" title="' .
                        $attachment->attachment .
                        '" style="color:' .
                        $attach_color .
                        '" download>' .
                        $att .
                        "</a>";
                } else {
                    if (strlen($attachment->standard_name) > 20) {
                        $att = substr($attachment->standard_name, 0, 20);
                    } else {
                        $att = $attachment->standard_name;
                    }
                    $filename =
                        '
                  <a class="id_attach_' .
                        $attachment->id .
                        '" class="id_attach_' .
                        $attachment->id .
                        '" href="' .
                        URL::to(
                            "/" .
                                $attachment->url .
                                "/" .
                                $attachment->attachment
                        ) .
                        '" title="' .
                        $attachment->standard_name .
                        '" style="color:' .
                        $attach_color .
                        '" download="' .
                        $attachment->standard_name .
                        '">' .
                        $att .
                        "</a>";
                }
                $output .=
                    '<tr class="client_identity_tr">
                	<td>' .
                    $filename .
                    '</td>
                	<td>
                		<input type="radio" name="identity_type_' .
                    $attachment->id .
                    '" class="identity_type identity_type_' .
                    $client_id .
                    '" id="identity_type_photo_' .
                    $attachment->id .
                    '"';
                if ($attachment->identity_type == 1) {
                    $output .= "checked";
                }
                $output .=
                    ' data-element="' .
                    $client_id .
                    '" data-attach="' .
                    $attachment->id .
                    '" value="1"><label for="identity_type_photo_' .
                    $attachment->id .
                    '">Photo ID</label>
                		<input type="radio" name="identity_type_' .
                    $attachment->id .
                    '" class="identity_type identity_type_' .
                    $client_id .
                    '" id="identity_type_other_' .
                    $attachment->id .
                    '"';
                if ($attachment->identity_type == 0) {
                    $output .= "checked";
                }
                $output .=
                    ' data-element="' .
                    $client_id .
                    '" data-attach="' .
                    $attachment->id .
                    '" value="0"><label for="identity_type_other_' .
                    $attachment->id .
                    '">Other ID</label>
                	</td>
                	<td>';
                if (
                    $attachment->expiry_date == "0000-00-00" ||
                    $attachment->expiry_date == ""
                ) {
                    $output .=
                        '<input type="text" name="identity_expiry_date" class="identity_expiry_date" placeholder="dd-mmm-yyyy" value="" data-element="' .
                        $attachment->id .
                        '">';
                } else {
                    $output .=
                        '<input type="text" name="identity_expiry_date" class="identity_expiry_date" placeholder="dd-mmm-yyyy" value="' .
                        date("d-F-Y", strtotime($attachment->expiry_date)) .
                        '" data-element="' .
                        $attachment->id .
                        '">';
                }
                $output .=
                    '</td>
                	<td>
                		<a class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="' .
                    $attachment->id .
                    '"></a>
                	</td>
                </tr>';
            }
        }
        echo json_encode([
            "output" => $output,
            "client_code" => $client_id,
            "client_name" => $cm_clients->company,
        ]);
    }
    public function set_identity_expiry_date(Request $request)
    {
        $attach_id = $request->get("attach_id");
        $value = $request->get("value");
        $expiry_date = date("Y-m-d", strtotime($value));
        $data["expiry_date"] = $expiry_date;
        \App\Models\AmlAttachment::where("id", $attach_id)->update($data);
        $current_date = strtotime(date("Y-m-d"));
        $expiry_str = strtotime($expiry_date);
        $attach_color = "#000";
        if ($current_date > $expiry_str) {
            $attach_color = "#f00";
        }
        echo $attach_color;
    }
    public function set_attachment_identity_type(Request $request)
    {
        $attach_id = $request->get("attach_id");
        $value = $request->get("value");
        $data["identity_type"] = $value;
        \App\Models\AmlAttachment::where("id", $attach_id)->update($data);
    }
    public function email_request_updated_id_files(Request $request)
    {
        $client_id = $request->get("client_id");
        $cm_clients = \App\Models\CMClients::where("client_id", $client_id)->first();
        $to = "";
        if (is_countable($cm_clients)) {
            $to = $cm_clients->email;
            if ($cm_clients->email2 != "") {
                $to = $to . "," . $cm_clients->email2;
            }
        }
        $message =
            "<p>Hi " .
            $cm_clients->company .
            ',<p>
		<p>Sample Request updated ID Files.</p>';
        echo json_encode(["to" => $to, "message" => $message]);
    }
    public function email_request_id_files(Request $request)
    {
        $client_id = $request->get("client_id");
        $cm_clients = \App\Models\CMClients::where("client_id", $client_id)->first();
        $to = "";
        if (is_countable($cm_clients)) {
            $to = $cm_clients->email;
            if ($cm_clients->email2 != "") {
                $to = $to . "," . $cm_clients->email2;
            }
        }
        $message =
            "<p>Hi " .
            $cm_clients->company .
            ',<p>
		<p>Sample Request updated ID Files.</p>';
        echo json_encode(["to" => $to, "message" => $message]);
    }
    public function update_aml_settings(Request $request) {
        $cc_email = $request->get('aml_cc_input');
        $data['aml_cc_email'] = $cc_email;
        $data['email_signature'] = $request->get('editoramlsignature');
        $data['email_body'] = $request->get('editoramlbody');

        $check_settings = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        if($check_settings) {
            DB::table('aml_settings')->where('id',$check_settings->id)->update($data);
        }
        else{
            $data['practice_code'] = Session::get('user_practice_code');
            DB::table('aml_settings')->insert($data);
        }
        return redirect::back()->with('message', 'AML Setings Saved Sucessfully.');
    }
    public function edit_aml_header_image(Request $request) {
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

                    DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
    }
}