<?php namespace App\Http\Controllers\facility;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use DB;
use Input;
use Redirect;
use Session;
use Hash;
use Illuminate\Http\Request;
use URL;
use Illuminate\Support\Facades\Crypt;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DateTime;
class FacilityController extends Controller
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
        $this->middleware("facilityauth");
        require_once(app_path('Http/helpers.php'));
    }
    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function profile(Request $request)
    {
        $facility_details = \App\Models\Facility::where("id", 1)->first();
        return view("facility/profile", [
            "facility_details" => $facility_details
        ]);
    }
    public function practice_screen(Request $request)
    {
        $practices = DB::table('user')->join('practices', 'practices.user_id','=','user.user_id')->where('user_type',1)->where('user_role','admin')->get();
        return view("facility/practice_screen", [
            "practices" => $practices
        ]);
    }
    
    public function logout()
    {
        Session::forget("facility_userid");
        return redirect("/facility");
    }

    public function update_facility_setting(Request $request)
    {
        $username = $request->get("admin_username");
        $practice_code = $request->get("admin_practice_code");

        if($request->get('newadmin_password') != "") {
            $password = Crypt::encrypt($request->get("newadmin_password"));
            $data['password'] = $password;
        }

        $data['email'] = strtolower($username);
        $data['practice_code'] = $practice_code;

        \App\Models\Facility::where("id", Session::get('facility_userid'))->update($data);
        return Redirect::back()->with(
            "message",
            "Settings Updated Successfully"
        );
        
    }
    public function show_practice_informations(Request $request) {
        $code = $request->get('code');
        $details = DB::table('user')->join('practices', 'practices.user_id','=','user.user_id')->where('user.practice',$code)->where('user_type',1)->first();
        $output = '';
        if($details) {
            $output.='<tr>
                <td style="width:200px;border-right:1px solid #c3c3c3"><strong>Practice Name:</strong></td>
                <td>'.$details->practice_name.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Practice Code:</strong></td>
                <td>'.$details->practice.'</td>
            </tr>';
            if($details->practice_logo_filename == "") {
                $output.='<tr>
                    <td style="border-right:1px solid #c3c3c3"><strong>Practice Logo:</strong></td>
                    <td></td>
                </tr>';
            }
            else{
                $output.='<tr>
                    <td style="border-right:1px solid #c3c3c3"><strong>Practice Logo:</strong></td>
                    <td><img src="'.URL::to($details->practice_logo_url.'/'.$details->practice_logo_filename).'" style="width:200px"></td>
                </tr>';
            }
            
            $output.='<tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Email:</strong></td>
                <td>'.$details->email.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>OneTimeCode:</strong></td>
                <td>'.$details->otp.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Admin Firstname:</strong></td>
                <td>'.$details->firstname.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Admin Surname:</strong></td>
                <td>'.$details->lastname.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Address:</strong></td>
                <td>'.$details->address1.',<br/> '.$details->address2.',<br/> '.$details->address3.'</td>
            </tr>
            <tr>
                <td style="border-right:1px solid #c3c3c3"><strong>Telephone Number:</strong></td>
                <td>'.$details->phoneno.'</td>
            </tr>';
        }
        echo $output;
    }

    public function table_viewer(Request $request)
    {
        $tables = DB::select("SHOW TABLES");
        $tables = array_map("current", $tables);
        return view("facility/table_viewer", ["tablelist" => $tables]);
    }
    public function get_table_notes(Request $request)
    {
        $table = $request->get("value");
        $get_notes = \App\Models\tableNotes::where("table_name", $table)->first();
        $notes = "";
        if ($get_notes) {
            $notes = $get_notes->notes;
        }
        echo $notes;
    }
    public function update_table_notes(Request $request)
    {
        $table = $request->get("table");
        $notes = $request->get("notes");
        $get_notes = \App\Models\tableNotes::where("table_name", $table)->first();
        if ($get_notes) {
            $data["notes"] = $notes;
            \App\Models\tableNotes::where("id", $get_notes->id)->update($data);
        } else {
            $data["notes"] = $notes;
            $data["table_name"] = $table;
            \App\Models\tableNotes::insert($data);
        }
    }
    public function show_table_viewer(Request $request)
    {
        $table = $request->get("table_name");
        $page = $request->get("page");
        $limit = 50;
        $offpage = $page - 1;
        $offset = $offpage * 50;
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        $rows = DB::table($table)
            ->skip($offset)
            ->take($limit)
            ->get();
        $total_array = DB::table($table)->get();
        if (count($total_array)) {
            $total_rows = ceil(count($total_array) / 50);
        } else {
            $total_rows = 0;
        }
        $output = '
        <table class="table own_table_white tablefixedheader" style="margin-top: 20px;min-width:100%;float:left">
            <thead>';
        if (count($fields)) {
            foreach ($fields as $field) {
                if($field != "table_content") {
                    $output .= '<th><div class="table_content_header_th">' . $field . '</th>';
                }
            }
        }
        $output .= '</thead>
            <tbody id="table_viewer_tbody">';
        if (count($rows)) {
            foreach ($rows as $row) {
                $output .= "<tr>";
                if (count($fields)) {
                    foreach ($fields as $field) {
                        if($field != "table_content") {
                            $output .= '<td><div class="table_content_body_td">' . $row->$field . '</div></td>';
                        }
                    }
                }
                $output .= "</tr>";
            }
        }
        $output .= '</tbody>
        </table>
        <div class="col-md-12" style="margin-bottom:50px">
        <input type="hidden" name="hidden_page" id="hidden_page" value="1">';
        if ($total_rows > 1) {
            $output .=
                '<input type="button" class="common_black_button common_btn load_more_content" id="load_more_content" value="Load More Content">';
        } else {
            $output .=
                '<input type="button" class="common_black_button common_btn" value="Load More Content" style="display:none">';
        }
        $output .= "</div>";
        echo json_encode(["output" => $output]);
    }
    public function show_table_viewer_append(Request $request)
    {
        $table = $request->get("table_name");
        $page = $request->get("page");
        $limit = 50;
        $offpage = $page - 1;
        $offset = $offpage * 50;
        $fields = DB::getSchemaBuilder()->getColumnListing($table);
        $rows = DB::table($table)
            ->skip($offset)
            ->take($limit)
            ->get();
        $total_array = DB::table($table)->get();
        if (count($total_array)) {
            $total_rows = ceil(count($total_array) / 50);
        } else {
            $total_rows = 0;
        }
        $output = "";
        if (count($rows)) {
            foreach ($rows as $row) {
                $output .= "<tr>";
                if (count($fields)) {
                    foreach ($fields as $field) {
                        $output .= "<td>" . $row->$field . "</td>";
                    }
                }
                $output .= "</tr>";
            }
        }
        $nextpage = $page + 1;
        if ($total_rows > $page) {
            $show_load_btn = 1;
        } else {
            $show_load_btn = 0;
        }
        echo json_encode([
            "show_load_btn" => $show_load_btn,
            "output" => $output,
        ]);
    }
    public function manage_avatar(Request $request) {
        $avatar_list = DB::table('user_avatar')->get();
        return view("facility/user_avatar", ["avatarlist" => $avatar_list]);
    }
    public function upload_user_avatar_images(Request $request) {
        $upload_dir = 'uploads/user_avatar';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $upload_dir = $upload_dir.'/'.time();
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $targetPath = $upload_dir."/".$_FILES['file']['name'];
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                $uploadedImagePath = URL::to($targetPath);
                echo json_encode(array('image_path' => $uploadedImagePath,'upload_dir' => $upload_dir, 'filename' => $_FILES['file']['name']));
            }
        }
    }
    public function show_cropped_image(Request $request) {
        $upload_dir = $request->get('upload_dir');
    
        $imgUrl = $request->get('img');
        $x = $request->get('x');
        $y = $request->get('y');
        $w = $request->get('w');
        $h = $request->get('h');
    
        $imageWidth = $request->get('elewidth');
        $imageHeight = $request->get('eleheight');
    
        // Download the image from the URL and store it temporarily
        $tempImagePath = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($tempImagePath, Http::get($imgUrl)->body());
    
        $imgInfo = @getimagesize($tempImagePath);
        if ($imgInfo === false) {
            // Failed to get image info, handle the error
            unlink($tempImagePath); // Delete the temporary image file
            throw new \Exception("Failed to get image information: $imgUrl");
        }
    
        $format = strtolower(substr($imgInfo['mime'], strpos($imgInfo['mime'], '/') + 1));
    
        if ($format === 'jpeg' || $format === 'jpg') {
            $img_r = imagecreatefromjpeg($tempImagePath);
        } elseif ($format === 'png') {
            $img_r = imagecreatefrompng($tempImagePath);
        } elseif ($format === 'webp') {
            $img_r = imagecreatefromwebp($tempImagePath);
        } elseif ($format === 'bmp') {
            $img_r = imagecreatefrombmp($tempImagePath);
        } else {
            // Handle other formats or throw an error
            unlink($tempImagePath); // Delete the temporary image file
            throw new \Exception("Unsupported image format: $format");
        }        
        
        $img_r = imagescale($img_r , $imageWidth, $imageHeight); // width=500 and height = 400

        $getimagewidth = imagesx($img_r);
        $getimageheight = imagesy($img_r);;
        $dst_r = ImageCreateTrueColor( $w, $h );
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w,$h);
        $exp = explode('/', $imgUrl);
        $upload_dir = $upload_dir . '/thumbnails';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir);
        }
        $name = time() . '_' . end($exp);
        $filename = $upload_dir . '/' . $name;
    
        header('Content-type: image/jpeg');
        imagejpeg($dst_r, $filename);

        // After processing the image, don't forget to delete the temporary file
        unlink($tempImagePath);
    
        echo json_encode(array('image_path' => URL::to($filename), 'upload_dir' => $upload_dir, 'filename' => $name));
    }
    public function save_cropped_image(Request $request) {
        $original_url = $request->get('original_upload_dir');
        $original_filename = $request->get('original_filename');

        $cropped_url = $request->get('cropped_upload_dir');
        $cropped_filename = $request->get('cropped_filename');

        $data['url'] = $original_url;
        $data['filename'] = $original_filename;

        $data['crop_url'] = $cropped_url;
        $data['crop_filename'] = $cropped_filename;

        DB::table('user_avatar')->insert($data);

        $output = '';
        $avatarlist = DB::table('user_avatar')->get();
        if(is_countable($avatarlist) && count($avatarlist) > 0) {
            foreach($avatarlist as $avatar) {
              $output.='<div class="col-md-2">
                  <img src="'.URL::to($avatar->crop_url).'/'.$avatar->crop_filename.'" class="edit_avatar" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
              </div>';
            }
        }

        echo $output;
    }
    public function update_cropped_image(Request $request) {
        $original_url = $request->get('original_upload_dir');
        $original_filename = $request->get('original_filename');
        $avatar_id = $request->get('avatar_id');

        $cropped_url = $request->get('cropped_upload_dir');
        $cropped_filename = $request->get('cropped_filename');

        $data['url'] = $original_url;
        $data['filename'] = $original_filename;

        $data['crop_url'] = $cropped_url;
        $data['crop_filename'] = $cropped_filename;

        DB::table('user_avatar')->where('id',$avatar_id)->update($data);

        $output = '';
        $avatarlist = DB::table('user_avatar')->get();
        if(is_countable($avatarlist) && count($avatarlist) > 0) {
            foreach($avatarlist as $avatar) {
              $output.='<div class="col-md-2">
                  <img src="'.URL::to($avatar->crop_url).'/'.$avatar->crop_filename.'" class="edit_avatar" data-element="'.$avatar->id.'" data-original="'.URL::to($avatar->url.'/'.$avatar->filename).'" data-cropped="'.URL::to($avatar->crop_url.'/'.$avatar->crop_filename).'" data-original_upload_dir="'.$avatar->url.'" data-original_filename="'.$avatar->filename.'" data-cropped_upload_dir="'.$avatar->crop_url.'" data-cropped_filename="'.$avatar->crop_filename.'" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
              </div>';
            }
        }

        echo $output;
    }
    public function edit_user_avatar(Request $request) {
        $avatar_id = $request->get('avatar_id');
        $original = $request->get('original');
        $cropped = $request->get('cropped');

        echo $original;
    }
    public function default_email_image(Request $request) {
        $emailHeaderImages = DB::table('email_header_images')->where('status',1)->get();
        return view("facility/default_email_image", [
            "email_header_images" => $emailHeaderImages
        ]);
    }
    public function edit_header_image(Request $request) {
        $image_id = $request->get('preview_image_id');

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
                    $dataval['url'] = $upload_dir;
                    $dataval['filename'] = $fname;

                    DB::table('email_header_images')->where('id',$image_id)->update($dataval); 
                }
                echo json_encode(array('image_id' => $image_id,'image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image_id' => $image_id,'image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
    }

    public function cmclass(Request $request)
    {
        $cmclass = \App\Models\CMClass::get();
        return view('facility/cm/class', array('title' => 'CM Class', 'cmclasslist' => $cmclass));
    }
    public function addclass(Request $request){
        $name = $request->get('name');
        $color = $request->get('color');
        \App\Models\CMClass::insert(['classname' => $name, 'classcolor' => $color]);
        return redirect::back()->with('message','Class Added Successfully');
    }
    public function editcmclass(Request $request, $id=""){
        $id = base64_decode($id);
        $result = \App\Models\CMClass::where('id', $id)->first();
        echo json_encode(array('name' => $result->classname, 'color' => $result->classcolor, 'id' => $result->id));
    }

    public function updatecmclass(Request $request){
        $name = $request->get('name');
        $color = $request->get('color');
        $id = $request->get('id');

        
        \App\Models\CMClass::where('id', $id)->update(['classname' => $name, 'classcolor' => $color]);
        return redirect::back()->with('message','Class Updated Successfully');
    }
    public function deactivecmclass(Request $request, $id=""){
        $id = base64_decode($id);
        $deactive =  1;
        \App\Models\CMClass::where('id', $id)->update(['status' => $deactive ]);
        return redirect::back()->with('message','Class Deactived Successfully');
    }
    public function activecmclass(Request $request, $id=""){
        $id = base64_decode($id);
        $active =  0;
        \App\Models\CMClass::where('id', $id)->update(['status' => $active ]);
        return redirect::back()->with('message','Class Activated Successfully');
    }
    public function cmpaper(Request $request)
    {
        $cmprint = \App\Models\CMPaper::get();
        return view('facility/cm/print', array('title' => 'CM Print', 'cmprintlist' => $cmprint));
    }
    public function addpaper(Request $request){
        $name = $request->get('name');
        $width = $request->get('width');
        $height = $request->get('height');

        $fields = $request->get('fields');
        $fld = '';
        if(($fields))
        {
            foreach($fields as $field)
            {
                if($fld == "")
                {
                    $fld = $field;
                }
                else{
                    $fld = $fld.','.$field;
                }
            }
        }
        $status = $request->get('set_as_default');
        if($status == 1)
        {
            \App\Models\CMPaper::where('status','!=', 0)->update(['status' => 0]);
        }
        else{
            $status = 0;
        }
        \App\Models\CMPaper::insert(['papername' => $name, 'width' => $width, 'height' => $height,'labels' => $fld,'status' => $status]);
        return redirect::back()->with('message','Cm Paper Added Successfully');
    }
    public function editcmpaper(Request $request, $id=""){
        $id = base64_decode($id);
        $result = \App\Models\CMPaper::where('id', $id)->first();
        echo json_encode(array('name' => $result->papername, 'width' => $result->width, 'height' => $result->height, 'fields' => $result->labels, 'status' => $result->status, 'id' => $result->id));
    }

    public function updatecmpaper(Request $request){
        $name = $request->get('name');
        $width = $request->get('width');
        $height = $request->get('height');
        $id = $request->get('id');
        $fields = $request->get('fields');
        $fld = '';
        if(($fields))
        {
            foreach($fields as $field)
            {
                if($fld == "")
                {
                    $fld = $field;
                }
                else{
                    $fld = $fld.','.$field;
                }
            }
        }

        $status = $request->get('set_as_default');
        if($status == 1)
        {
            \App\Models\CMPaper::where('id','>',0)->update(['status' => 0]);
        }
        
        \App\Models\CMPaper::where('id', $id)->update(['papername' => $name, 'width' => $width, 'height' => $height,'labels' => $fld,'status' => $status]);
        return redirect::back()->with('message','CM paper Updated Successfully');
    }

    public function deactivecmpaper(Request $request, $id=""){
        $id = base64_decode($id);
        $deactive =  1;
        \App\Models\CMPaper::where('status','!=', 0)->update(['status' => 0]);
        \App\Models\CMPaper::where('id', $id)->update(['status' => $deactive ]);
        return redirect::back()->with('message','CM paper Deactivated Successfully');
    }
    public function activecmpaper(Request $request, $id=""){
        $id = base64_decode($id);
        $active =  0;
        \App\Models\CMPaper::where('id', $id)->update(['status' => $active ]);
        return redirect::back()->with('message','CM paper Activated Successfully');
    }
    public function system_setting_review(Request $request) {
        $practices = DB::table('practices')->get();
        return view("facility/system_setting_modules", ["practices" => $practices]);
    }
    public function show_system_module_settings(Request $request) {
        $modules = DB::table('system_module_settings')->get();
        $output = '<table class="table own_table_white tablefixedheader" style="margin-top:20px;min-width:100%;float:left">
            <thead>
                <tr>
                <th><p style="width:100px"></p></th>';
                if(is_countable($modules) && count($modules) > 0) {
                    foreach($modules as $module){
                        $output.='<th><p style="width:200px;text-align:center;margin-bottom: 0px;">'.$module->module_name.'</p></th>';
                    }
                }

            $output.='</tr><tr>
                <th><p style="width:100px">Practice Code</p></th>';
                if(is_countable($modules) && count($modules) > 0) {
                    foreach($modules as $module){
                        if($module->settings_table_name != "") {
                            $table_name = $module->settings_table_name;
                        }
                        else{
                            $table_name = 'N/A';
                        }
                        $output.='<th><p style="width:200px;text-align:center;margin-bottom: 0px;">'.$table_name.'</p></th>';
                    }
                }
            $output.='</tr>
            </thead>
            <tbody>';
            $practices = DB::table('practices')->get();
            if(is_countable($practices) && count($practices) > 0) {
                foreach($practices as $practice) {
                    $output.='<tr>
                        <td>'.$practice->practice_code.'</td>';
                        if(is_countable($modules) && count($modules) > 0) {
                            foreach($modules as $module) {
                                if($module->settings_table_name == "") {
                                    $status = '<i class="fa fa-times" style="color:#f00"></i>';
                                }
                                else{
                                    $check_practice_code = DB::table($module->settings_table_name)->where('practice_code',$practice->practice_code)->first();
                                    if($check_practice_code) {
                                        $status = '<i class="fa fa-check" style="color:green"></i>';
                                    }
                                    else{
                                        $status = '<i class="fa fa-times" style="color:#f00"></i>';
                                    }
                                }
                                $output.='
                                    <td style="text-align:center">'.$status.'</td>';
                            }
                        }
                    $output.='</tr>';
                }
            }
            $output.='</tbody>
        </table>';

        echo $output;
    }
    public function fix_missing_records(Request $request) {
        $modules = DB::table('system_module_settings')->get();
        if(is_countable($modules) && count($modules) > 0) {
            foreach($modules as $module) {
                if($module->settings_table_name != "") {
                    $table_name = $module->settings_table_name;
                    $practices = DB::table('practices')->get();
                    if(is_countable($practices) && count($practices) > 0) {
                        foreach($practices as $practice) {
                            $practice_code = $practice->practice_code;
                            $check_code = DB::table($table_name)->where('practice_code',$practice_code)->first();
                            if(!$check_code) {
                                $datapractice['practice_code'] = $practice_code;
                                DB::table($table_name)->insert($datapractice);
                            }
                        }
                    }
                }
            }
        }
        $output = '<table class="table own_table_white tablefixedheader" style="margin-top:20px;min-width:100%;float:left">
            <thead>
                <tr>
                <th><p style="width:100px"></p></th>';
                if(is_countable($modules) && count($modules) > 0) {
                    foreach($modules as $module){
                        $output.='<th><p style="width:200px;text-align:center;margin-bottom: 0px;">'.$module->module_name.'</p></th>';
                    }
                }

            $output.='</tr><tr>
                <th><p style="width:100px">Practice Code</p></th>';
                if(is_countable($modules) && count($modules) > 0) {
                    foreach($modules as $module){
                        if($module->settings_table_name != "") {
                            $table_name = $module->settings_table_name;
                        }
                        else{
                            $table_name = 'N/A';
                        }
                        $output.='<th><p style="width:200px;text-align:center;margin-bottom: 0px;">'.$table_name.'</p></th>';
                    }
                }
            $output.='</tr>
            </thead>
            <tbody>';
            $practices = DB::table('practices')->get();
            if(is_countable($practices) && count($practices) > 0) {
                foreach($practices as $practice) {
                    $output.='<tr>
                        <td>'.$practice->practice_code.'</td>';
                        if(is_countable($modules) && count($modules) > 0) {
                            foreach($modules as $module) {
                                if($module->settings_table_name == "") {
                                    $status = '<i class="fa fa-times" style="color:#f00"></i>';
                                }
                                else{
                                    $check_practice_code = DB::table($module->settings_table_name)->where('practice_code',$practice->practice_code)->first();
                                    if($check_practice_code) {
                                        $status = '<i class="fa fa-check" style="color:green"></i>';
                                    }
                                    else{
                                        $status = '<i class="fa fa-times" style="color:#f00"></i>';
                                    }
                                }
                                $output.='
                                    <td style="text-align:center">'.$status.'</td>';
                            }
                        }
                    $output.='</tr>';
                }
            }
            $output.='</tbody>
        </table>';

        echo $output;
    }
}