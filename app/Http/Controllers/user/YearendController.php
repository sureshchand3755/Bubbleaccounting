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
use PHPWord; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class YearendController extends Controller {
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
      }
      /**
       * Show the application welcome screen to the user.
       *
       * @return Response
       */
      public function YearendController(Request $request){
            $year = \App\Models\YearEndYear::where('status', 0)->orderBy('year','desc')->get();       
            return view('user/yearend/yearend', array('title' => 'Year End Manger', 'yearlist' => $year));
      }
      public function yearend_crypt_validdation(Request $request){
            $type = $request->get('type');
            if($type == 0){
                  $result = true;
                  $drop = '
                  <div class="form-title">Choose Year</div>
                  <select class="form-control year_class" name="year" required>
                        <option value="">Select Year</option>
                        <option value="2000">2000</option>
                        <option value="2001">2001</option>
                        <option value="2002">2002</option>
                        <option value="2003">2003</option>
                        <option value="2004">2004</option>
                        <option value="2005">2005</option>
                        <option value="2006">2006</option>
                        <option value="2007">2007</option>
                        <option value="2008">2008</option>
                        <option value="2009">2009</option>
                        <option value="2010">2010</option>
                        <option value="2011">2011</option>
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                        <option value="2031">2031</option>
                        <option value="2032">2032</option>
                        <option value="2033">2033</option>
                        <option value="2034">2034</option>
                        <option value="2035">2035</option>
                        <option value="2036">2036</option>
                        <option value="2037">2037</option>
                        <option value="2038">2038</option>
                        <option value="2039">2039</option>
                        <option value="2040">2040</option>
                        <option value="2041">2041</option>
                        <option value="2042">2042</option>
                        <option value="2043">2043</option>
                        <option value="2044">2044</option>
                        <option value="2045">2045</option>
                        <option value="2046">2046</option>
                        <option value="2047">2047</option>
                        <option value="2048">2048</option>
                        <option value="2049">2049</option>
                        <option value="2050">2050</option>
                        <option value="2051">2051</option>
                        <option value="2052">2052</option>
                        <option value="2053">2053</option>
                        <option value="2054">2054</option>
                        <option value="2055">2055</option>
                        <option value="2056">2056</option>
                        <option value="2057">2057</option>
                        <option value="2058">2058</option>
                        <option value="2059">2059</option>
                        <option value="2060">2060</option>
                        <option value="2061">2061</option>
                        <option value="2062">2062</option>
                        <option value="2063">2063</option>
                        <option value="2064">2064</option>
                        <option value="2065">2065</option>
                        <option value="2066">2066</option>
                        <option value="2067">2067</option>
                        <option value="2068">2068</option>
                        <option value="2069">2069</option>
                        <option value="2070">2070</option>
                        <option value="2071">2071</option>
                        <option value="2072">2072</option>
                        <option value="2073">2073</option>
                        <option value="2074">2074</option>
                        <option value="2075">2075</option>
                        <option value="2076">2076</option>
                        <option value="2077">2077</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                        <option value="2081">2081</option>
                        <option value="2082">2082</option>
                        <option value="2083">2083</option>
                        <option value="2084">2084</option>
                        <option value="2085">2085</option>
                        <option value="2086">2086</option>
                        <option value="2087">2087</option>
                        <option value="2088">2088</option>
                        <option value="2089">2089</option>
                        <option value="2090">2090</option>
                        <option value="2091">2091</option>
                        <option value="2092">2092</option>
                        <option value="2093">2093</option>
                        <option value="2094">2094</option>
                        <option value="2095">2095</option>
                        <option value="2096">2096</option>
                        <option value="2097">2097</option>
                        <option value="2098">2098</option>
                        <option value="2099">2090</option>
                        <option value="2100">2100</option>
                        </select>';
                  $button = '<input type="submit" class="common_black_button year_button" value="Create a Year">';
            }
            else{
                  $result = true;
                  $drop = '
                        <select class="form-control setting_type">
                              <option value="">Select Type</option>
                              <option value="1">Supplementary notes</option>
                              <option value="2">Year End Documents</option>
                        </select>';
                  $button = '<a href="#" class="common_black_button setting_button">Submit</a>';
            }
            echo json_encode(array('security' => $result, 'drop' => $drop, 'create_button' => $button));
      }
      public function year_first_create(Request $request){
            $year = $request->get('year');          
            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('active', 1)->get();
            if(($client_details)){
                  foreach ($client_details as $client) {
                        $data['year'] = $year;
                        $data['client_id'] = $client->client_id;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                       \App\Models\YearClient::insert($data);
                  }
            }
            $client_update = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
            if(($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }
            $id = \App\Models\YearEndYear::insertDetails(['year' => $year, 'updatetime' => date('Y-m-d H:i:s')]);
            \App\Models\YearEndYear::where('id',$id)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Year Created successfully');
      }
      public function yearend_create_new_year(Request $request)
      {
            $get_last_created_year = \App\Models\YearEndYear::orderBy('id', 'desc')->first();
            $current_year = $get_last_created_year->year + 1;
            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('active', 1)->orderBy('id','ASC')->get();
            if(($client_details)){
                  foreach ($client_details as $client) {
                        $get_year_client =\App\Models\YearClient::where('client_id',$client->client_id)->where('year',$get_last_created_year->year)->first();
                        $status = 0;
                        if(($get_year_client)){
                              $sett_id = $get_year_client->setting_id;
                              $sett_active = $get_year_client->setting_active;
                        } else{
                              $sett_id = $get_last_created_year->setting_id;
                              $sett_active = $get_last_created_year->setting_active;
                        }
                        $exp_sett_active = explode(',',$sett_active);
                        if(in_array("1", $exp_sett_active)){
                              $status = 1;
                        }
                        $data['year'] = $current_year;
                        $data['client_id'] = $client->client_id;
                        $data['setting_id'] = $sett_id;
                        $data['setting_active'] = $sett_active;
                        $data['status'] = $status;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                       \App\Models\YearClient::insert($data);
                  }
            }
            $client_update = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
            if(($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }
            $id = \App\Models\YearEndYear::insertDetails(['year' => $current_year,'setting_id'=>$get_last_created_year->setting_id,'setting_active' => $get_last_created_year->setting_active, 'updatetime' => date('Y-m-d H:i:s')]);
            \App\Models\YearEndYear::where('id',$id)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Year Created successfully');
      }
      public function review_get_clients(Request $request)
      {
            $yearid = $request->get('yearid');
            /*$year_details = \App\Models\YearSetting::where('year',$yearid)->first();
            $time = strtotime($year_details->updatetime);
            $time = $time + (1 * 60);
            $date = date("Y-m-d H:i:s", $time);
            $time = strtotime($date);*/
            $output = '<input type="checkbox" class="hide_deactivate_clients" id="hide_deactivate_clients"><label for="hide_deactivate_clients" style="float: right;">Hide Deactivated Clients</label><br/>
            <table class="table table_bg">
                  <thead>
                        <tr class="background_bg">
                              <th style="width:133px"><input type="checkbox" name="select_all_clients" class="select_all_clients" id="select_all_clients" value="1"> <label for="select_all_clients">Select All</label></th>
                              <th style="width:128px">Client Id <i class="fa fa-sort review_sort_clientid"></i></th>
                              <th style="text-align:left">Company Name <i class="fa fa-sort review_sort_company"></i></th>
                              <th>Status</th>
                        <tr>
                  </thead>
                  <tbody id="review_task_body">';
                        $check_clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
                        if(($check_clients)){              
                              foreach ($check_clients as $clients) {
                                    $check_db =\App\Models\YearClient::where('client_id',$clients->client_id)->where('year',$yearid)->count();
                                    if($check_db == 0)
                                    {
                                          if($clients->active == 2) { $status = 'Deactive'; } else { $status = 'Active'; }
                                          if($clients->active == 2){ $hide = 'hidden_tr'; $color = 'color:#f00 !important'; } else{ $hide =''; $color = 'color:#000 !important'; }
                                          $output.='<tr class="review_task_tr '.$hide.'">
                                                <td><input type="checkbox" name="review_clients_checkbox[]" class="review_clients_checkbox" id="review_clients_checkbox_'.$clients->id.'" value="'.$clients->id.'"> <label for="review_clients_checkbox_'.$clients->id.'">&nbsp;</label></td>
                                                <td class="review_clientid_sort_val" style="'.$color.'">'.$clients->client_id.'</td>
                                                <td class="review_company_sort_val" style="text-align:left; '.$color.'">'.$clients->company.'</td>
                                                <td style="'.$color.'">'.$status.'</td>
                                          </tr>';
                                    }
                              }
                        }
                        else{
                              $output.='<tr>
                                    <td></td>
                                    <td style="color:#000 !important">No New Clients Found since this year was created</td>
                                    <td></td>
                                    <td></td>
                              </tr>';
                        }
                  $output.='</tbody>
            </table>
            <input type="hidden" name="hidden_yearid" id="hidden_yearid" value="'.$yearid.'">
            <input type="submit" class="common_black_button submit_review_clients" value="Add Clients to this year">';
            echo $output;
      }
      /*public function review_get_clients()
      {            
            $yearid = $request->get('yearid');            
            $year_details = \App\Models\YearSetting::where('year',$yearid)->first();
            $time = strtotime($year_details->updatetime);
            $time = $time + (1 * 60);
                  $date = date("Y-m-d H:i:s", $time);
                  $time = strtotime($date);
            $output = '
            <input type="checkbox" class="hide_deactivate_clients" id="hide_deactivate_clients"><label for="hide_deactivate_clients" style="float: right;">Hide Deactivated Clients</label><br/>
            <table class="table table_bg">
                  <thead>
                        <tr class="background_bg">
                              <th style="width:133px"><input type="checkbox" name="select_all_clients" class="select_all_clients" id="select_all_clients" value="1"> <label for="select_all_clients">Select All</label></th>
                              <th style="width:128px"><i class="fa fa-sort review_sort_clientid"></i>Client Id</th>
                              <th><i class="fa fa-sort review_sort_company"></i>Company Name</th>
                              <th>Status</th>
                        <tr>
                  </thead>
                  <tbody id="review_task_body">';
            $check_clients = DB::select('SELECT * from `cm_clients` WHERE UNIX_TIMESTAMP(`yearend_updatetime`) >= "'.$time.'"');
            if(($check_clients)){              
                  foreach ($check_clients as $clients) {
                        if($clients->active == 2) { $status = 'Deactive'; } else { $status = 'Active'; }
                        if($clients->active == 2){ $hide = 'hidden_tr'; $color = 'color:#f00 !important'; } else{ $hide =''; $color = 'color:#000 !important'; }
                        $output.='<tr class="review_task_tr '.$hide.'">
                              <td><input type="checkbox" name="review_clients_checkbox[]" class="review_clients_checkbox" id="review_clients_checkbox_'.$clients->id.'" value="'.$clients->id.'"> <label for="review_clients_checkbox_'.$clients->id.'">&nbsp;</label></td>
                              <td class="review_clientid_sort_val" style="'.$color.'">'.$clients->client_id.'</td>
                              <td class="review_company_sort_val" style="'.$color.'">'.$clients->company.'</td>
                              <td style="'.$color.'">'.$status.'</td>
                        </tr>';
                  }
            }
            else{
                  $output.='<tr>
                        <td></td>
                        <td style="color:#000 !important">No New Clients Found since this year was created</td>
                        <td></td>
                        <td></td>
                  </tr>';
            }
            $output.='</tbody>
            </table>
            <input type="hidden" name="hidden_yearid" id="hidden_yearid" value="'.$yearid.'">
            <input type="submit" class="common_black_button submit_review_clients" value="Add Clients to this year">';
            echo $output;
      }*/
      public function review_clients_update(Request $request)
      {
            $yearid = $request->get('hidden_yearid'); // 2022
            $ids = implode(",",$request->get('review_clients_checkbox')); //GBS445,GBS446,GBS447
            $get_last_created_year = \App\Models\YearEndYear::where('year',$yearid)->first();
            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->whereIn('id', $request->get('review_clients_checkbox'))->get();
            if(($client_details)){
                  foreach ($client_details as $client) {
                        $data['year'] = $yearid;
                        $data['client_id'] = $client->client_id;
                        $data['setting_id'] = $get_last_created_year->setting_id;
                        $data['setting_active'] = $get_last_created_year->setting_active;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                       \App\Models\YearClient::insert($data);
                  }
            }
            $client_update = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->get();
            if(($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }
            \App\Models\YearEndYear::where('year',$yearid)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Reviewed Clients successfully.');
      }
      public function yearend_setting(Request $request){
            if(Session::has('yearend_attach_add'))
            {
                  Session::forget("yearend_attach_add");
            }
            $setting = \App\Models\YearSetting::where('status', 0)->get();
            return view('user/yearend/yearend_setting', array('title' => 'Setting', 'setting_list' => $setting));
      }
      public function year_setting_create(Request $request){
            $data['document'] = $request->get('document');
            $data['introduction'] = $request->get('introduction');
            $data['active'] = 2;
            $id = \App\Models\YearSetting::insertDetails($data);
            if(Session::has('yearend_attach_add'))
            {
                  $files = Session::get('yearend_attach_add');
                  foreach($files as $file)
                  {
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        rename("uploads/yearend_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
                        $dataval['year_setting_id'] = $id;
                        $dataval['attachment'] = $file['attachment'];
                        $dataval['url'] = $upload_dir;
                        \App\Models\YearSettingAttachment::insert($dataval);
                  }
            }
            return Redirect::back()->with('message', 'Document created successfully');
      }
      public function active_checkbox(Request $request){
            $id = $request->get('id');
            $data['active'] = $request->get('active');
            \App\Models\YearSetting::where('id', $id)->update($data);
      }
      public function year_setting_edit(Request $request){
            $id= $request->get('id');
            $setting_details = \App\Models\YearSetting::where('id', $id)->first();
            echo json_encode(array('id' => $setting_details->id, 'document' => $setting_details->document, 'introduction' => $setting_details->introduction ));
      }
      public function yearend_crypt_setting_add(Request $request){
            if(Session::has('yearend_attach_add')) {
                  Session::forget("yearend_attach_add");
            }
            $type = $request->get('type');
            $id = $request->get('id');
            if($type == 0){
                  $form_details = '
                    <div class="form-group">
                        <div class="form-title">Document Name:</div>
                        <input type="text" class="form-control" value="" placeholder="Enter Document Name" name="document" required>
                    </div>
                    <div class="form-group">
                        <div class="form-title">Introduction Note:</div>
                        <textarea class="form-control" value="" placeholder="Enter Introduction Note" name="introduction" required></textarea>
                    </div>';        
                    $attachments = '';                
            }
            else{
                  $id = $request->get('id');
                  $setting_details = \App\Models\YearSetting::where('id', $id)->first();
                  $form_details = '
                    <div class="form-group">
                        <div class="form-title">Document Name:</div>
                        <input type="text" class="form-control" value="'.$setting_details->document.'" placeholder="Enter Document Name" name="document" required>
                    </div>
                    <div class="form-group">
                        <div class="form-title">Introduction Note:</div>
                        <textarea class="form-control" placeholder="Enter Introduction Note" name="introduction" required>'.$setting_details->introduction.'</textarea>
                    </div>';
                  $attachments = '';
                  $files = \App\Models\YearSettingAttachment::where('year_setting_id',$id)->get();
                  if(($files))
                  {
                        foreach($files as $file)
                        {
                              if($attachments == "")
                              {
                                    $attachments = '<p id="attach_'.$file->id.'">'.$file->attachment.' <a href="javascript:" class="delete_file fa fa-trash" data-element="'.$file->id.'"></a> </p>';
                              }
                              else{
                                    $attachments = $attachments.' <p id="attach_'.$file->id.'">'.$file->attachment.' <a href="javascript:" class="delete_file fa fa-trash" data-element="'.$file->id.'"></a></p>';
                              }
                        }
                  }
            }
            echo json_encode(array('form_details' => $form_details,'attachments' => $attachments));
      }
      public function year_setting_update(Request $request){
            $id = $request->get('id');
            $data['document'] = $request->get('document');
            $data['introduction'] = $request->get('introduction');
            \App\Models\YearSetting::where('id', $id)->update($data);
            if(Session::has('yearend_attach_add'))
            {
                  $files = Session::get('yearend_attach_add');
                  foreach($files as $file)
                  {
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        rename("uploads/yearend_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
                        $dataval['year_setting_id'] = $id;
                        $dataval['attachment'] = $file['attachment'];
                        $dataval['url'] = $upload_dir;
                        \App\Models\YearSettingAttachment::insert($dataval);
                  }
            }
            return Redirect::back()->with('message', 'Update Success');
      }
      public function yearend_upload_images_add(Request $request)
      {
            $upload_dir = 'uploads/yearend_image';
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/temp';
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            if (!empty($_FILES)) {
                   $tmpFile = $_FILES['file']['tmp_name'];
                   $filename = $upload_dir.'/'.$_FILES['file']['name'];
                   $fname = $_FILES['file']['name'];
                  move_uploaded_file($tmpFile,$filename);
                  if(Session::has('yearend_attach_add'))
                  {
                        $arrayval = array('attachment' => $fname,'url' => $upload_dir);
                        $getsession = Session::get('yearend_attach_add');
                        array_push($getsession,$arrayval);
                  }
                  else{
                        $arrayval = array('attachment' => $fname,'url' => $upload_dir);
                        $getsession = array();
                        array_push($getsession,$arrayval);
                  }
                  $sessn=array('yearend_attach_add' => $getsession);
                  Session::put($sessn);
            }
            echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0));
      }
      public function yearend_upload_images_edit(Request $request)
      {
            $id = $request->get('hidden_year_setting_id');
            $upload_dir = 'uploads/yearend_image';
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/'.base64_encode($id);
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            if (!empty($_FILES)) {
                   $tmpFile = $_FILES['file']['tmp_name'];
                   $filename = $upload_dir.'/'.$_FILES['file']['name'];
                   $fname = $_FILES['file']['name'];
                  move_uploaded_file($tmpFile,$filename);
                  $data['year_setting_id'] = $id;
                  $data['attachment'] = $fname;
                  $data['url'] = $upload_dir;
                  $id = \App\Models\YearSettingAttachment::insertDetails($data);
            }
            echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => $id));
      }
      public function yearend_clear_session_attachments(Request $request)
      {
            if(Session::has('yearend_attach_add'))
            {
                  Session::forget("yearend_attach_add");
            }
      }
      public function remove_all_attachments(Request $request)
      {
            $id = $request->get('year_setting_id'); 
            \App\Models\YearSettingAttachment::where('year_setting_id',$id)->delete();
      }
      public function remove_year_setting_attachment(Request $request)
      {
            $id = $request->get('id');
            \App\Models\YearSettingAttachment::where('id',$id)->delete();
      }
      public function yeadend_clients(Request $request, $id=""){
            $id = base64_decode($id);
            $clients =\App\Models\YearClient::join('cm_clients','cm_clients.client_id','=','year_client.client_id')->select('year_client.*')
                                                ->where('cm_clients.practice_code', Session::get('user_practice_code'))
                                                ->where('year', $id)->get();          
            return view('user/yearend/yearend_clients', array('title' => 'Clients', 'clientslist' => $clients, 'yearid' => $id));
      }
      public function yearend_individualclient(Request $request, $id=""){
            $id = base64_decode($id);            
            $year_details =\App\Models\YearClient::where('id', $id)->first();
            $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $year_details->client_id)->first();
            return view('user/yearend/individualclient', array('title' => 'Clients', 'year_details' => $year_details, 'client_details' => $client_details, 'year_end_id' => $id));
      }
      public function year_setting_copy_to_year(Request $request){
            $id = base64_decode($request->get('yearid'));
            $end_year = \App\Models\YearEndYear::where('year',$id)->first();
            $check_clients =\App\Models\YearClient::where('year', $id)->where('status','!=',2)->get();
            if(($check_clients))
            {
                  foreach($check_clients as $clients)
                  {
                        $get_setting = $clients->setting_id;
                        $get_setting_active = $clients->setting_active;
                        $explode_setting = explode(",",$get_setting);
                        $setting = \App\Models\YearSetting::get();
                        $commo = '';
                        if(($setting)){
                              foreach ($setting as $key => $single) {
                                    if(in_array($single->id, $explode_setting))
                                    {
                                    }
                                    else{
                                          if($commo == ''){
                                                $commo = $single->id;
                                          }
                                          else{
                                                $commo =  $commo.','.$single->id;
                                          }
                                    }
                              }
                        }
                        if($get_setting == "")
                        {
                              $year_setting['setting_id'] = $commo;
                        }
                        else{
                              if($commo == "")
                              {
                                    $year_setting['setting_id'] = $get_setting;
                              }
                              else{
                                    $year_setting['setting_id'] = $get_setting.','.$commo;
                              }
                        }
                        $commoactive = '';
                        if(($setting)){
                              foreach ($setting as $key => $single) {
                                    if(in_array($single->id, $explode_setting))
                                    {
                                    }
                                    else{
                                          if($commoactive == ''){
                                                $commoactive = $single->active;
                                          }
                                          else{
                                                $commoactive =  $commoactive.','. $single->active;
                                          }
                                    }
                              }
                        }
                        if($get_setting == "")
                        {
                              $year_setting['setting_active'] = $commoactive; 
                        }
                        else{
                              if($commoactive == "")
                              {
                                    $year_setting['setting_active'] = $get_setting_active;
                              }
                              else{
                                    $year_setting['setting_active'] = $get_setting_active.','.$commoactive;
                              }
                        }
                        if($clients->setting_default == "")
                        {
                           $array = array();
                        }
                        else{
                           $array = unserialize($clients->setting_default);   
                        }
                        if($commo != "")
                        {
                              $explodecomma = explode(",",$commo);
                              foreach($explodecomma as $comm)
                              {
                                    $getattachments = \App\Models\YearSettingAttachment::where('year_setting_id',$comm)->get();
                                    $attachids = '';
                                    $arrayval = array();
                                    if(($getattachments))
                                    {
                                          foreach($getattachments as $attach_id)
                                          {
                                                if($attachids == "")
                                                {
                                                      $attachids = $attach_id->id;
                                                }
                                                else{
                                                      $attachids = $attachids.','.$attach_id->id;
                                                }
                                                $datadist1['client_id'] = $clients->id;
                                                $datadist1['setting_id'] = $comm;
                                                $datadist1['attachments'] = $attach_id->attachment;
                                                $datadist1['url'] = $attach_id->url;
                                                $datadist1['distribution_type'] = 1;
                                                $datadist1['attach_type'] = 0;
                                                $datadist1['status'] = 0;
                                                \App\Models\YearendDistributionAttachments::insert($datadist1);
                                          }
                                          $arrayval = array($comm => $attachids);
                                    }
                                    array_push($array,$arrayval);
                              }
                        }      
                        $serialize = serialize($array);
                        $year_setting['setting_default'] = $serialize; 
                       \App\Models\YearClient::where('id', $clients->id)->where('status','!=',2)->update($year_setting);
                        $commoactive = '';
                        if(($setting)){
                              foreach ($setting as $key => $single) {
                                    if($commoactive == ''){
                                          $commoactive = $single->active;
                                    }
                                    else{
                                          $commoactive =  $commoactive.','. $single->active;
                                    }
                              }
                        }
                        $year_setting['setting_active'] = $commoactive;
                        \App\Models\YearEndYear::where('year', $id)->update($year_setting);                              
                  }
            }
            //return Redirect::back()->with('message', '');
      }
      public function dist_emailupdate(Request $request){
            $id = $request->get('id');            
            $number = $request->get('number');
            if($number == 1){
                  $data['distribution1_email']= $request->get('value');
                 \App\Models\YearClient::where('id', $id)->update($data);
            }
            elseif($number == 2){
                  $data['distribution2_email']= $request->get('value');
                 \App\Models\YearClient::where('id', $id)->update($data);
            }
            elseif($number == 3){
                  $data['distribution3_email']= $request->get('value');
                 \App\Models\YearClient::where('id', $id)->update($data);
            }
      }
      public function yearend_individual_attachment(Request $request)
      {
            $distribution_type = $request->get('distribution_type');
            if($distribution_type == "4")
            {
                  $total = count($_FILES['image_file']['name']);
                  $clientid = $request->get('hidden_client_id');
                  for($i=0; $i<$total; $i++) {
                        $filename = $_FILES['image_file']['name'][$i];
                        $tmp_name = $_FILES['image_file']['tmp_name'][$i];
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/clientid_'.$clientid;
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/aml';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);   
                        $data['client_id'] = $clientid;
                        $data['attachments'] = $filename;
                        $data['url'] = $upload_dir;
                        \App\Models\YearendAmlAttachments::insert($data);
                  }       
                  return redirect('user/yearend_individualclient/'.base64_encode($clientid))->with('message', 'Attachments Added successfully');
            }
            else{
                  $total = count($_FILES['image_file']['name']);
                  $clientid = $request->get('hidden_client_id');
                  $settingid = $request->get('hidden_setting_id');
                  $attach_type = $request->get('attach_type');
                  for($i=0; $i<$total; $i++) {
                        $filename = $_FILES['image_file']['name'][$i];
                        $tmp_name = $_FILES['image_file']['tmp_name'][$i];
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/clientid_'.$clientid;
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/distribution_'.$distribution_type;
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);   
                        $data['client_id'] = $clientid;
                        if($settingid == "")
                        {
                              $data['setting_id'] = 0;
                        }
                        else{
                              $data['setting_id'] = $settingid;
                        }
                        $data['attachments'] = $filename;
                        $data['url'] = $upload_dir;
                        $data['distribution_type'] = $distribution_type;
                        $data['attach_type'] = $attach_type;
                        \App\Models\YearendDistributionAttachments::insert($data);
                  }    
                 \App\Models\YearClient::where('id',$clientid)->update(['status' => 1]);        
                  return redirect('user/yearend_individualclient/'.base64_encode($clientid))->with('message', 'Attachments Added successfully');
            }
      }
      public function yearend_attachment_individual(Request $request)
      {
            $distribution_type = $request->get('distribution_type');
            if($distribution_type == "4")
            {
                  $clientid = $request->get('hidden_client_id');
                  $upload_dir = 'uploads/yearend_image';
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/clientid_'.$clientid;
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/aml';
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  if (!empty($_FILES)) {
                         $tmpFile = $_FILES['file']['tmp_name'];
                         $filename = $upload_dir.'/'.$_FILES['file']['name'];
                         $fname = $_FILES['file']['name'];
                        move_uploaded_file($tmpFile,$filename);
                        $data['client_id'] = $clientid;
                        $data['attachments'] = $fname;
                        $data['url'] = $upload_dir;
                        $id = \App\Models\YearendAmlAttachments::insertDetails($data);
                  }
                  echo json_encode(array('id' => $id,'filename' => $fname,'type' => 'aml'));
            }
            else{
                  $clientid = $request->get('hidden_client_id');
                  $settingid = $request->get('hidden_setting_id');
                  $attach_type = $request->get('attach_type');
                  $upload_dir = 'uploads/yearend_image';
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/clientid_'.$clientid;
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/distribution_'.$distribution_type;
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  if (!empty($_FILES)) {
                         $tmpFile = $_FILES['file']['tmp_name'];
                         $filename = $upload_dir.'/'.$_FILES['file']['name'];
                         $fname = $_FILES['file']['name'];
                        move_uploaded_file($tmpFile,$filename);
                        $data['client_id'] = $clientid;
                        if($settingid == "")
                        {
                              $data['setting_id'] = 0;
                        }
                        else{
                              $data['setting_id'] = $settingid;
                        }
                        $data['attachments'] = $fname;
                        $data['url'] = $upload_dir;
                        $data['distribution_type'] = $distribution_type;
                        $data['attach_type'] = $attach_type;
                        $id = \App\Models\YearendDistributionAttachments::insertDetails($data);
                       \App\Models\YearClient::where('id',$clientid)->update(['status' => 1]);
                  }
                  echo json_encode(array('id' => $id,'filename' => $fname,'type' => 'yearend'));
            }
      }
      public function yearend_delete_image(Request $request)
      {
            $imgid = $request->get('imgid');
            $type = $request->get('type');
            if($type == "2")
            {
                  \App\Models\YearendAmlAttachments::where('id',$imgid)->delete();
            }
            else{
                  \App\Models\YearendDistributionAttachments::where('id',$imgid)->delete();
            }            
      }
      public function yearend_delete_all_image(Request $request)
      {
            $clientid = $request->get('clientid');
            $settingid = $request->get('settingid');
            $distribution = $request->get('distribution');
            $type = $request->get('type');
            \App\Models\YearendDistributionAttachments::where('client_id',$clientid)->where('setting_id',$settingid)->where('distribution_type',$distribution)->where('attach_type',$type)->delete();
      }
      public function yearend_delete_all_image_aml(Request $request)
      {
            $clientid = $request->get('clientid');
            \App\Models\YearendAmlAttachments::where('client_id',$clientid)->delete();
      }
      public function yearend_delete_note(Request $request)
      {
            $imgid = $request->get('imgid');
            \App\Models\YearendNotesAttachments::where('id',$imgid)->delete();
      }
      public function yearend_delete_all_note(Request $request)
      {
            $clientid = $request->get('clientid');
            $settingid = $request->get('settingid');
            $type = $request->get('type');
            $distribution = $request->get('distribution');
            \App\Models\YearendNotesAttachments::where('client_id',$clientid)->where('setting_id',$settingid)->where('attach_type',$type)->where('distribution_type',$distribution)->delete();
      }
      public function remove_yearend_dropzone_attachment(Request $request)
      {
            $attachment_id = $_POST['attachment_id'];
            \App\Models\YearendDistributionAttachments::where('id',$attachment_id)->delete();
      }
      public function remove_yearend_dropzone_attachment_aml(Request $request)
      {
            $attachment_id = $_POST['attachment_id'];
            \App\Models\YearendAmlAttachments::where('id',$attachment_id)->delete();
      }
      public function distribution_future(Request $request)
      {
            $setting_active = $request->get('setting_active');
            $distribution1_future = $request->get('distribution1_future');
            $distribution2_future = $request->get('distribution2_future');
            $distribution3_future = $request->get('distribution3_future');
            $yearend_id = $request->get('yearend_id');
            $data['setting_active'] = $setting_active;
            $data['distribution1_future'] = $distribution1_future;
            $data['distribution2_future'] = $distribution2_future;
            $data['distribution3_future'] = $distribution3_future;
           \App\Models\YearClient::where('id',$yearend_id)->update($data);
      }
      public function distribution1_future(Request $request)
      {
            $distribution1_future = $request->get('distribution1_future');
            $yearend_id = $request->get('yearend_id');
            $data['distribution1_future'] = $distribution1_future;
           \App\Models\YearClient::where('id',$yearend_id)->update($data);
      }
      public function distribution2_future(Request $request)
      {
            $distribution2_future = $request->get('distribution2_future');
            $yearend_id = $request->get('yearend_id');
            $data['distribution2_future'] = $distribution2_future;
           \App\Models\YearClient::where('id',$yearend_id)->update($data);
      }
      public function distribution3_future(Request $request)
      {
            $distribution3_future = $request->get('distribution3_future');
            $yearend_id = $request->get('yearend_id');
            $data['distribution3_future'] = $distribution3_future;
           \App\Models\YearClient::where('id',$yearend_id)->update($data);
      }
      public function setting_active_update(Request $request)
      {
            $setting_active = $request->get('setting_active');
            $yearend_id = $request->get('yearend_id');
            $data['setting_active'] = $setting_active;
           \App\Models\YearClient::where('id',$yearend_id)->update($data);
      }
      public function check_already_attached(Request $request)
      {
            $client_id = $request->get('year_id');
            $setting_id = $request->get('setting_id');
            $type = $request->get('type');
            $distribution = $request->get('distribution');
            $check_db = \App\Models\YearendNotesAttachments::where('client_id',$client_id)->where('setting_id',$setting_id)->where('distribution_type',$distribution)->where('attach_type',$type)->get();
            $notesids = '';
            if(($check_db))
            {
                  foreach($check_db as $db)
                  {
                        if($notesids == "")
                        {
                              $notesids = $db->note_id;
                        }
                        else{
                              $notesids = $notesids.','.$db->note_id;
                        }
                  }
            }
            $explode_notesids = explode(",",$notesids);
            $get_notes = \App\Models\supplementaryFormula::where('name','!=','')->get();
            $output = '';
            if(($get_notes))
            {
                  foreach($get_notes as $notes)
                  {
                        $output.='<p class="main_note">'.$notes->name.'</p>';
                        $check_subs = \App\Models\supplementaryFormulaAttachments::where('formula_id',$notes->id)->get();
                        $ii = 0;
                        if(($check_subs))
                        {
                              foreach($check_subs as $sub)
                              {
                                    if(!in_array($sub->id, $explode_notesids))
                                    {
                                          $output.='<p><input type="checkbox" name="sub_note[]" class="sub_note" id="note_'.$sub->id.'" value="'.$sub->id.'"><label class="sub_note" for="note_'.$sub->id.'">'.$sub->name.'</label></p>';
                                          $ii++;
                                    }
                              }
                              if($ii == 0)
                              {
                                    $output.='<p><label>No Supplementary Notes Found</label></p>';
                              }
                        }
                        else{
                              $output.='<p><label>No Supplementary Notes Found</label></p>';
                        }
                  }
            }
            else{
                  $output.='<p class="main_note">No Supplementary Notes found</p>';
            }
            echo $output;
      }
      public function insert_notes_yearend(Request $request)
      {
            $noteid = explode(",",$request->get('noteid'));
            $textval = explode("||",$request->get('textval'));
            if(($noteid))
            {
                  $client_id = $request->get('year_id');
                  $setting_id = $request->get('setting_id');
                  $type = $request->get('type');
                  $distribution = $request->get('distribution');
                  foreach($noteid as $key=>$note)
                  {
                        $textvalue = $textval[$key];
                        $upload_dir = 'uploads/yearend_notes';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($client_id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($setting_id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($distribution);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($type);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $words = explode(" ", $textvalue);
                        $first = join(" ", array_slice($words, 0, 5));
                        $name = $first.'...';
                        $myfile = fopen($upload_dir."/".$name.".txt", "w") or die("Unable to open file!");
                        $txt = $textvalue;
                        fwrite($myfile, $txt);
                        fclose($myfile);
                        $data['client_id'] = $client_id;
                        $data['setting_id'] = $setting_id;
                        $data['attachments'] = $name.".txt";
                        $data['url'] = $upload_dir;
                        $data['attach_type'] = $type;
                        $data['distribution_type'] = $distribution;
                        \App\Models\YearendNotesAttachments::insert($data);
                  }
                 \App\Models\YearClient::where('id',$client_id)->update(['status' => 1]);
            }
      }
      public function download_email_format(Request $request)
      {
            $type = $request->get('type');
            $email = $request->get('email');
            $distribution = $request->get('distribution');
            $yearselected = $request->get('yearselected');
            $details =\App\Models\YearClient::where('id',$yearselected)->first();
            $clientdetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
            $setting_ids = explode(",",$details->setting_id);
            $setting_active = explode(",",$details->setting_active);
            if($distribution == "1") { $distribution_future = explode(",",$details->distribution1_future); }
            elseif($distribution == "2") { $distribution_future = explode(",",$details->distribution2_future); }
            elseif($distribution == "3") { $distribution_future = explode(",",$details->distribution3_future); }
            if($email == 1){ $attached = 'attached'; }
            else{ $attached = 'enclosed'; }
            if($type == 1)
            {
                  $output = '
                  <style>
                        p{ line-height:10px !important; }
                  </style>
                  <p>Subject: '.$details->year.' Year End</p>
                  <p>Dear '.$clientdetails->firstname.'</p>
                  <p>Please find '.$attached.' the following</p>
                  <p style="height:2px"></p>';
                  if(($setting_ids))
                  {
                        foreach($setting_ids as $key => $ids)
                        {
                              $setting_name = \App\Models\YearSetting::where('id',$ids)->first();
                              if($setting_active[$key] == 0)
                              {
                                    if($distribution_future[$key] == 1)
                                    {
                                          $output.='<p style="margin-left:20px">'.$setting_name->document.': will be sent to you under separate cover.</p>';
                                    }
                                    else{
                                          $attachments = \App\Models\YearendDistributionAttachments::where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearselected)->where('attach_type',0)->get();
                                          $outputattach = '';
                                          if(is_countable($attachments) && count($attachments) > 0)
                                          {
                                                foreach($attachments as $attach)
                                                {
                                                      if($outputattach == "")
                                                      {
                                                            $outputattach = $attach->attachments;
                                                      }
                                                      else{
                                                            $outputattach = $outputattach.'; '.$attach->attachments;
                                                      }
                                                }
                                                $output.='<p style="margin-left:20px">'.$setting_name->document.': '.$outputattach.'</p>';
                                          }
                                    }
                              }
                        }
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',1)->get();
                  $output_closingnote = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($note_details) {
                                   if($output_closingnote == '')
                                    {
                                          $output_closingnote =$note_details->name;
                                    }
                                    else{
                                          $output_closingnote = $output_closingnote.'; '.$note_details->name;
                                    } 
                              }
                        }
                        $output.='<p style="margin-left:20px">Closing Note: '.$output_closingnote.'</p>';
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',2)->get();
                  $output_feenote = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($output_feenote == '')
                              {
                                    $output_feenote =$note_details->name;
                              }
                              else{
                                    $output_feenote = $output_feenote.'; '.$note_details->name;
                              }
                        }
                        $output.='<p style="margin-left:20px">Fee Note: '.$output_feenote.'</p>';
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',3)->get();
                  $output_signature = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($output_signature == '')
                              {
                                    $output_signature =$note_details->name;
                              }
                              else{
                                    $output_signature = $output_signature.'; '.$note_details->name;
                              }
                        }
                        $output.='<p style="margin-left:20px">Signature: '.$output_signature.'</p>';
                  }
                  $pdf = PDF::loadHTML($output);
                  $pdf->setPaper('A4', 'landscape');
                  $pdf->save('public/job_file/Distribution_Email_Format.pdf');
                  echo 'Distribution_Email_Format.pdf';
            }
            else{
                  $PHPWord = new \PhpOffice\PhpWord\PhpWord();
                  $section = $PHPWord->addSection();
                  $section->addText('Subject: '.$details->year.' Year End');
                  $section->addText('Dear '.$clientdetails->firstname.'');
                  $section->addText('Please find '.$attached.' the following');
                  $section->addText('&nbsp;');
                  if(($setting_ids))
                  {
                        foreach($setting_ids as $key => $ids)
                        {
                              $setting_name = \App\Models\YearSetting::where('id',$ids)->first();
                              if($setting_active[$key] == 0)
                              {
                                    if($distribution_future[$key] == 1)
                                    {
                                          $section->addText($setting_name->document.': will be sent to you under separate cover.');
                                    }
                                    else{
                                          $attachments = \App\Models\YearendDistributionAttachments::where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearselected)->where('attach_type',0)->get();
                                          $outputattach = '';
                                          if(is_countable($attachments) && count($attachments) > 0)
                                          {
                                                foreach($attachments as $attach)
                                                {
                                                      if($outputattach == "")
                                                      {
                                                            $outputattach = $attach->attachments;
                                                      }
                                                      else{
                                                            $outputattach = $outputattach.'; '.$attach->attachments;
                                                      }
                                                }
                                                $section->addText($setting_name->document.': '.$outputattach);
                                          }
                                    }
                              }
                        }
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',1)->get();
                  $output_closingnote = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($note_details){
                                    if($output_closingnote == '')
                                    {
                                          $output_closingnote =$note_details->name;
                                    }
                                    else{
                                          $output_closingnote = $output_closingnote.'; '.$note_details->name;
                                    }
                              }
                        }
                        $section->addText('Closing Note: '.$output_closingnote);
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',2)->get();
                  $output_feenote = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($output_feenote == '')
                              {
                                    $output_feenote =$note_details->name;
                              }
                              else{
                                    $output_feenote = $output_feenote.'; '.$note_details->name;
                              }
                        }
                        $section->addText('Fee Note: '.$output_feenote);
                  }
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',3)->get();
                  $output_signature = '';
                  if(is_countable($notes) && count($notes) > 0)
                  {
                        foreach($notes as $note)
                        {
                              $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                              if($output_signature == '')
                              {
                                    $output_signature =$note_details->name;
                              }
                              else{
                                    $output_signature = $output_signature.'; '.$note_details->name;
                              }
                        }
                        $section->addText('Signature: '.$output_signature);
                  }
                  $section->addText('&nbsp;');
                  $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'HTML');
                  $objWriter->save("public/job_file/Distribution_Email_Format.doc");
                  echo 'Distribution_Email_Format.doc';
            }
      }
      public function edit_yearend_email_unsent_files(Request $request)
      {
            $yearid = $request->get('yearid');
            $distribution = $request->get('distribution');
            $email = 1;
            $attached = 'attached';
            $files = '';
            $attachment_count = 0;
            $type = $request->get('type');
            $details =\App\Models\YearClient::where('id',$yearid)->first();
            $clientdetails = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$details->client_id)->first();
            $setting_ids = explode(",",$details->setting_id);
            $setting_active = explode(",",$details->setting_active);
            if($distribution == "1") { $distribution_future = explode(",",$details->distribution1_future); }
            elseif($distribution == "2") { $distribution_future = explode(",",$details->distribution2_future); }
            elseif($distribution == "3") { $distribution_future = explode(",",$details->distribution3_future); }
            $subject = $details->year.' Year End';
            $output = '
            <style>
                  p{ line-height:10px !important; }
            </style>
            <p>Dear '.$clientdetails->firstname.'</p>
            <p>Please find '.$attached.' the following</p>';
            if(($setting_ids))
            {
                  foreach($setting_ids as $key => $ids)
                  {
                        $setting_name = \App\Models\YearSetting::where('id',$ids)->first();
                        if($setting_active[$key] == 0)
                        {
                              if($distribution_future[$key] == 1)
                              {
                                    $output.='<p style="margin-left:40px">'.$setting_name->document.' will be sent to you under separate cover.</p>';
                              }
                              else{
                                    if($type == "unsent")
                                    {
                                          $attachments = \App\Models\YearendDistributionAttachments::where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearid)->where('attach_type',0)->where('status',0)->get();
                                    }
                                    else{
                                          $attachments = \App\Models\YearendDistributionAttachments::where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearid)->where('attach_type',0)->where('status',1)->get();
                                    }

                                    $outputattach = '';
                                    if(is_countable($attachments) && count($attachments) > 0)
                                    {
                                          $files.='<p><input type="checkbox" class="check_all_setting" value="'.$setting_name->id.'" id="setting_'.$setting_name->id.'" checked><label for="setting_'.$setting_name->id.'">'.$setting_name->document.'</label></p>';
                                          foreach($attachments as $attach)
                                          {
                                                if($outputattach == "")
                                                {
                                                      $outputattach = $attach->attachments;
                                                }
                                                else{
                                                      $outputattach = $outputattach.'; '.$attach->attachments;
                                                }
                                                $files.='<p style="margin-left:30px"><input type="checkbox" name="check_attachment[]" value="'.$attach->id.'" id="label_'.$attach->id.'" class="attachments_setting_'.$setting_name->id.'" checked><label for="label_'.$attach->id.'">'.$attach->attachments.'</label></p>';
                                                $attachment_count = $attachment_count + 1;
                                          }
                                          $output.='<p style="margin-left:40px">'.$setting_name->document.': '.$outputattach.'</p>';
                                    }
                              }
                        }
                  }
            }
            if($type == "unsent")
            {
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',1)->where('status',0)->get();
            }
            else{
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',1)->where('status',1)->get();
            }
            $output_closing = '';
            if(is_countable($notes) && count($notes) > 0)
            {
                  $files.='<p><input type="checkbox" id="check_all_closing_note" value="closing_note" checked><label for="check_all_closing_note">Closing Note</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                        if($output_closing == "")
                        {
                              $output_closing = $note_details->name;
                        }
                        else{
                              $output_closing = $output_closing.'; '.$note_details->name;
                        }
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_closing_note" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                        $attachment_count = $attachment_count + 1;
                  }
                   $output.='<p style="margin-left:40px">Closing Note: '.$output_closing.'</p>';
            }
            if($type == "unsent")
            {
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',2)->where('status',0)->get();
            }
            else{
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',2)->where('status',1)->get();
            }
            $output_fee = '';
            if(is_countable($notes) && count($notes) > 0)
            {
                  $files.='<p><input type="checkbox" id="check_all_fee_note" value="closing_note" checked><label for="check_all_fee_note">Fee Note</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                        if($output_fee == "")
                        {
                              $output_fee = $note_details->name;
                        }
                        else{
                              $output_fee = $output_fee.'; '.$note_details->name;
                        }
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_fee_note" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                        $attachment_count = $attachment_count + 1;
                  }
                  $output.='<p style="margin-left:40px">Fee Note: '.$output_fee.'</p>';
            }
            if($type == "unsent")
            {
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',3)->where('status',0)->get();
            }
            else{
                  $notes = \App\Models\YearendNotesAttachments::where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',3)->where('status',1)->get();
            }
            $output_signature = '';
            if(is_countable($notes) && count($notes) > 0)
            {
                  $files.='<p><input type="checkbox" id="check_all_signature" value="closing_note" checked><label for="check_all_signature">Signature</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note->note_id)->first();
                        if($output_signature == "")
                        {
                              $output_signature = $note_details->name;
                        }
                        else{
                              $output_signature = $output_signature.'; '.$note_details->name;
                        }
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_signature" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                        $attachment_count = $attachment_count + 1;
                  }
                  $output.='<p style="margin-left:40px">Signature: '.$output_signature.'</p>';
            }
            echo json_encode(["files" => $files,"html" => $output,'subject' => $subject,'to' => $clientdetails->email,"attachment_count" => $attachment_count]);
      }
      public function yearend_email_unsent_files(Request $request)
      {
            $from_input = $request->get('from_user');
            $toemails = $request->get('to_user').','.$request->get('cc_unsent');
            $sentmails = $request->get('to_user').', '.$request->get('cc_unsent');
            $subject = $request->get('subject_unsent'); 
            $message = $request->get('message_editor');
            $attachments = $request->get('check_attachment');
            $notes = $request->get('check_notes');
            $distribution = $request->get('email_sent_option');
            $explode = explode(',',$toemails);
            $data['sentmails'] = $sentmails;
            $i = 0;
            $attach = '';
            if(($attachments) || ($notes))
            {
                  if(($explode))
                  {
                        foreach($explode as $exp)
                        {
                              $to = trim($exp);
                              $data['logo'] = getEmailLogo('yearend');
                              $data['message'] = $message;
                              $contentmessage = view('user/yearend_email_share_paper', $data);
                              $email = new PHPMailer();
                              $email->SetFrom($from_input); //Name is optional
                              $email->Subject   = $subject;
                              $email->Body      = $contentmessage;
                              $email->IsHTML(true);
                              $email->AddAddress( $to );
                              if(($attachments))
                              {
                                    foreach($attachments as $attachment)
                                    {
                                          $attachment_details = \App\Models\YearendDistributionAttachments::where('id',$attachment)->first();
                                          $path = $attachment_details->url.'/'.$attachment_details->attachments;
                                          $email->AddAttachment( $path , $attachment_details->attachments );
                                          \App\Models\YearendDistributionAttachments::where('id',$attachment)->update(['status' => 1]);
                                          $year_id = $attachment_details->client_id;
                                          if($attach == "")
                                          {
                                                $attach = $path;
                                          }
                                          else{
                                                $attach = $attach.'||'.$path;
                                          }
                                    }
                              }
                              if(($notes))
                              {
                                    foreach($notes as $note)
                                    {
                                          $note_content = \App\Models\YearendNotesAttachments::where('id',$note)->first();
                                          $note_details = \App\Models\supplementaryFormulaAttachments::where('id',$note_content->note_id)->first();
                                          $upload_dir = 'uploads/yearend_notes';
                                          if (!file_exists($upload_dir)) {
                                                mkdir($upload_dir);
                                          }
                                          $upload_dir = $upload_dir.'/'.base64_encode($note_content->note_id);
                                          if (!file_exists($upload_dir)) {
                                                mkdir($upload_dir);
                                          }
                                          $myfile = fopen($upload_dir."/".$note_details->name.".txt", "w") or die("Unable to open file!");
                                          $txt = $note_details->supplementary_text;
                                          fwrite($myfile, $txt);
                                          fclose($myfile);
                                          $path = $upload_dir.'/'.$note_details->name.'.txt';
                                          $email->AddAttachment( $path , $note_details->name.'.txt' );
                                          \App\Models\YearendNotesAttachments::where('id',$note)->update(['status' => 1]);
                                          $year_id = $note_content->client_id;
                                          if($attach == "")
                                          {
                                                $attach = $path;
                                          }
                                          else{
                                                $attach = $attach.'||'.$path;
                                          }
                                    }
                              }
                              $email->Send();
                              $i++;
                        }
                        $year_client =\App\Models\YearClient::where('id',$year_id)->first();
                        if(($year_client))
                        {
                              if($year_client->client_id != "")
                              {
                                    $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('email',$from_input)->first();
                                    $client_details = \App\Models\CMClients::where('client_id',$year_client->client_id)->first();
                                    $datamessage['message_id'] = time();
                                    $datamessage['message_from'] = $user_details->user_id;
                                    $datamessage['subject'] = $subject;
                                    $datamessage['message'] = $message;
                                    $datamessage['client_ids'] = $year_client->client_id;
                                    $datamessage['primary_emails'] = $client_details->email;
                                    $datamessage['secondary_emails'] = $client_details->email2;
                                    $datamessage['date_sent'] = date('Y-m-d H:i:s');
                                    $datamessage['date_saved'] = date('Y-m-d H:i:s');
                                    $datamessage['source'] = "Year End System";
                                    $datamessage['attachments'] = $attach;
                                    $datamessage['status'] = 1;
                                    $datamessage['practice_code'] = Session::get('user_practice_code');
                                    \App\Models\Messageus::insert($datamessage);
                              }
                        }
                        $date = date('Y-m-d H:i:s');
                        if($distribution == 1) { $dataval['dist1_email_sent_date'] = $date; }
                        elseif($distribution == 2) { $dataval['dist2_email_sent_date'] = $date; }
                        elseif($distribution == 3) { $dataval['dist3_email_sent_date'] = $date; }
                       \App\Models\YearClient::where('id',$year_id)->update($dataval);
                        return Redirect::back()->with('message', 'Email Sent Successfully');
                  }
                  else{
                        return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
                  }
            }
            else{
                  return Redirect::back()->with('error', 'Attachments are empty so Email is not sent');
            }
      }
      public function make_client_disable(Request $request)
      {
            $status = $_POST['status'];
            $id = $_POST['year'];
            if($status == 1)
            {
                 \App\Models\YearClient::where('id',$id)->update(['disabled' => $status,'status' => 2]);
            }
            else{
                 \App\Models\YearClient::where('id',$id)->update(['disabled' => $status,'status' => 1]);
            }
      }
      public function select_template(Request $request)
      {
            $templateid = $_POST['templateid'];
            $selectval = \App\Models\supplementaryFormula::where('id',$templateid)->first();
            $value_1 = $selectval->value_1;
            $value_2 = $selectval->value_2;
            $value_3 = $selectval->value_3_output;
            $value_4 = $selectval->value_4_output;
            $value_5 = $selectval->value_5_output;
            $value_6 = $selectval->value_6_output;
            $invoice = $selectval->invoice_number;
            $placeholder1 = 'Enter Input value';
            $placeholder2 = 'Enter Input value';
            if($selectval->value_3 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_3_combo1 == 1) { $value3_placeholder1 = 'Value1'; }
                  elseif($selectval->value_3_combo1 == 2) { $value3_placeholder1 = 'Value2'; }
                  else{ $value3_placeholder1 = ''; }
                  if($selectval->value_3_combo2 == 1) { $value3_placeholder2 = 'Value1'; }
                  elseif($selectval->value_3_combo2 == 2) { $value3_placeholder2 = 'Value2'; }
                  else{ $value3_placeholder2 = ''; }
                  if($selectval->value_3_formula == 1) { $value_3_formula = "+"; }
                  elseif($selectval->value_3_formula == 2) { $value_3_formula = "-"; }
                  elseif($selectval->value_3_formula == 3) { $value_3_formula = "*"; }
                  elseif($selectval->value_3_formula == 4) { $value_3_formula = "/"; }
                  else { $value_3_formula = ''; }
                  $placeholder3 = $placeholder.' '.$value3_placeholder1.' '.$value_3_formula.' '.$value3_placeholder2;
            }
            else{
                  $placeholder3 = 'Enter Input value';
            }
            if($selectval->value_4 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_4_combo1 == 1) { $value4_placeholder1 = 'Value1'; }
                  elseif($selectval->value_4_combo1 == 2) { $value4_placeholder1 = 'Value2'; }
                  elseif($selectval->value_4_combo1 == 3) { $value4_placeholder1 = 'Value3'; }
                  else{ $value4_placeholder1 = ''; }
                  if($selectval->value_4_combo2 == 1) { $value4_placeholder2 = 'Value1'; }
                  elseif($selectval->value_4_combo2 == 2) { $value4_placeholder2 = 'Value2'; }
                  elseif($selectval->value_4_combo2 == 3) { $value4_placeholder2 = 'Value3'; }
                  else{ $value4_placeholder2 = ''; }
                  if($selectval->value_4_formula == 1) { $value_4_formula = "+"; }
                  elseif($selectval->value_4_formula == 2) { $value_4_formula = "-"; }
                  elseif($selectval->value_4_formula == 3) { $value_4_formula = "*"; }
                  elseif($selectval->value_4_formula == 4) { $value_4_formula = "/"; }
                  else { $value_4_formula = ''; }
                  $placeholder4 = $placeholder.' '.$value4_placeholder1.' '.$value_4_formula.' '.$value4_placeholder2;
            }
            else{
                  $placeholder4 = 'Enter Input value';
            }
            if($selectval->value_5 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_5_combo1 == 1) { $value5_placeholder1 = 'Value1'; }
                  elseif($selectval->value_5_combo1 == 2) { $value5_placeholder1 = 'Value2'; }
                  elseif($selectval->value_5_combo1 == 3) { $value5_placeholder1 = 'Value3'; }
                  elseif($selectval->value_5_combo1 == 4) { $value5_placeholder1 = 'Value4'; }
                  else{ $value5_placeholder1 = ''; }
                  if($selectval->value_5_combo2 == 1) { $value5_placeholder2 = 'Value1'; }
                  elseif($selectval->value_5_combo2 == 2) { $value5_placeholder2 = 'Value2'; }
                  elseif($selectval->value_5_combo2 == 3) { $value5_placeholder2 = 'Value3'; }
                  elseif($selectval->value_5_combo2 == 4) { $value5_placeholder2 = 'Value4'; }
                  else{ $value5_placeholder2 = ''; }
                  if($selectval->value_5_formula == 1) { $value_5_formula = "+"; }
                  elseif($selectval->value_5_formula == 2) { $value_5_formula = "-"; }
                  elseif($selectval->value_5_formula == 3) { $value_5_formula = "*"; }
                  elseif($selectval->value_5_formula == 4) { $value_5_formula = "/"; }
                  else { $value_5_formula = ''; }
                  $placeholder5 = $placeholder.' '.$value5_placeholder1.' '.$value_5_formula.' '.$value5_placeholder2;
            }
            else{
                  $placeholder5 = 'Enter Input value';
            }
            if($selectval->value_6 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_6_combo1 == 1) { $value6_placeholder1 = 'Value1'; }
                  elseif($selectval->value_6_combo1 == 2) { $value6_placeholder1 = 'Value2'; }
                  elseif($selectval->value_6_combo1 == 3) { $value6_placeholder1 = 'Value3'; }
                  elseif($selectval->value_6_combo1 == 4) { $value6_placeholder1 = 'Value4'; }
                  elseif($selectval->value_6_combo1 == 5) { $value6_placeholder1 = 'Value5'; }
                  else{ $value6_placeholder1 = ''; }
                  if($selectval->value_6_combo2 == 1) { $value6_placeholder2 = 'Value1'; }
                  elseif($selectval->value_6_combo2 == 2) { $value6_placeholder2 = 'Value2'; }
                  elseif($selectval->value_6_combo2 == 3) { $value6_placeholder2 = 'Value3'; }
                  elseif($selectval->value_6_combo2 == 4) { $value6_placeholder2 = 'Value4'; }
                  elseif($selectval->value_6_combo2 == 5) { $value6_placeholder2 = 'Value5'; }
                  else{ $value6_placeholder2 = ''; }
                  if($selectval->value_6_formula == 1) { $value_6_formula = "+"; }
                  elseif($selectval->value_6_formula == 2) { $value_6_formula = "-"; }
                  elseif($selectval->value_6_formula == 3) { $value_6_formula = "*"; }
                  elseif($selectval->value_6_formula == 4) { $value_6_formula = "/"; }
                  else { $value_6_formula = ''; }
                  $placeholder6 = $placeholder.' '.$value6_placeholder1.' '.$value_6_formula.' '.$value6_placeholder2;
            }
            else{
                  $placeholder6 = 'Enter Input value';
            }
            $placeholder7 = 'Enter Input value';
            $output = '<p>Supplementary Note :</p>';
            $attachments = \App\Models\supplementaryFormulaAttachments::where('formula_id',$selectval->id)->get();
            if(($attachments))
            {
                  foreach($attachments as $attach)
                  {
                        $textval = str_replace("<<value1>>","<span class='classval' id='value1'>".$attach->value_1."</span>",$attach->magic_text);
                        $textval = str_replace("<<value2>>","<span class='classval' id='value2'>".$attach->value_2."</span>",$textval);
                        $textval = str_replace("<<value3>>","<span class='classval' id='value3'>".$attach->value_3_output."</span>",$textval);
                        $textval = str_replace("<<value4>>","<span class='classval' id='value4'>".$attach->value_4_output."</span>",$textval);
                        $textval = str_replace("<<value5>>","<span class='classval' id='value5'>".$attach->value_5_output."</span>",$textval);
                        $textval = str_replace("<<value6>>","<span class='classval' id='value6'>".$attach->value_6_output."</span>",$textval);
                        $textval = str_replace("<<invoice>>","<span class='classval' id='invoice'>".$attach->invoice_number."</span>",$textval);
                        $output.='<p><input type="checkbox" name="check_note" class="check_note" id="note_'.$attach->id.'" value="'.$attach->id.'"><label for="note_'.$attach->id.'">&nbsp;</label> <span class="notetxt_'.$attach->id.'">'.$textval.'</span></p>';
                  }
            }
            echo json_encode(array('value_1' => $value_1,'value_2' => $value_2,'value_3' => $value_3,'value_4' => $value_4,'value_5' => $value_5,'value_6' => $value_6,'invoice' => $invoice,'attachments' => $output,'placeholder1' => $placeholder1,'placeholder2' => $placeholder2,'placeholder3' => $placeholder3,'placeholder4' => $placeholder4,'placeholder5' => $placeholder5,'placeholder6' => $placeholder6,'placeholder7' => $placeholder7));
      }
      public function set_client_year_end_date(Request $request)
      {
            $clientid = $_POST['yearid'];
            $date = $_POST['date'];
           \App\Models\YearClient::where('id',$clientid)->update(['year_end_date' => $date]);
      }
      public function update_na_status(Request $request)
      {
            $status = $_POST['status'];
            $yearend_id = $_POST['yearend_id'];
           \App\Models\YearClient::where('id',$yearend_id)->update(['hide_na' => $status]);
      }
      public function yearendliabilityupdate(Request $request){
            $setting_id = base64_decode($request->get('setting_id'));
            $value = $request->get('value');
            $year_id = $request->get('year_id');
            $row_id = $request->get('row_id');
            $client_id = $request->get('client_id');
            $type = $request->get('type');
            $current_time = date('Y-m-d H:i:s');
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $check_row_setting = \App\Models\YearClientLiability::where('row_id', $row_id)->where('setting_id', $setting_id)->first();
            if($check_row_setting == ''){
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;
                  if($type == 1){
                        $data['liability1'] = $value;
                        $data['liability1_updatetime'] = $current_time;
                  }
                  elseif($type == 2){
                        $data['liability2'] = $value;
                        $data['liability2_updatetime'] = $current_time;
                  }
                  elseif($type == 3){
                        $data['liability3'] = $value;
                        $data['liability3_updatetime'] = $current_time;
                  }
                  \App\Models\YearClientLiability::insert($data);
            }
            else{
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;
                  $payment = $check_row_setting->payments;
                  if($type == 1){
                        $data['liability1'] = $value;
                        $data['liability1_updatetime'] = $current_time;
                        $data['balance'] = number_format_invoice_without_comma((int)$data['liability1'] - (int)$payment);
                  }
                  elseif($type == 2){
                        $data['liability2'] = $value;
                        $data['liability2_updatetime'] = $current_time;
                        $data['balance'] = number_format_invoice_without_comma((int)$data['liability2'] - (int)$payment);
                  }
                  elseif($type == 3){
                        $data['liability3'] = $value;
                        $data['liability3_updatetime'] = $current_time;
                        $data['balance'] = number_format_invoice_without_comma((int)$data['liability3'] - (int)$payment);
                  }
                 \App\Models\YearClientLiability::where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }       
            //echo number_format_invoice_without_decimal($value);    
      }
      public function yeadendliability(Request $request, $id=""){
            $id = base64_decode($id);
            $year_setting = \App\Models\YearEndYear::where('year', $id)->first();
            $explode_setting = explode(',', $year_setting->setting_id);
            $output_setting='';
            if(($explode_setting)){
                  foreach ($explode_setting as $setting) {
                        $setting_details = \App\Models\YearSetting::where('id', $setting)->first();
                        $output_setting.='<option value="'.$setting.'">'.$setting_details->document.'</option>';
                  }
            }
            return view('user/yearend/yearliability', array('title' => 'Clients', 'yearid' => $id, 'setting_list' => $output_setting));
      }
      public function yearendliabilitysettingresult(Request $request){
            $setting_id = $request->get('id');
            $year_id = $request->get('yearid');
            $year_client =\App\Models\YearClient::join('cm_clients','cm_clients.client_id','=','year_client.client_id')->select('year_client.*')
                                                ->where('cm_clients.practice_code', Session::get('user_practice_code'))
                                                ->where('year', $year_id)->get();
            $prelim_year = $year_id+1;
            $output_result='<table class="display fullviewtablelist own_table_white" id="liability_expand" width="100%" style="max-width: 100%;">
                <thead>
                  <tr class="background_bg">        
                    <th width="90px" style="text-align:left">Client Id <i class="fa fa-sort sort_clientid"></i></th>
                    <th style="text-align:left">First Name <i class="fa fa-sort sort_firstname"></i></th>
                    <th style="text-align:left">Last Name <i class="fa fa-sort sort_lastname"></i></th>
                    <th style="text-align:left">Company <i class="fa fa-sort sort_company"></i></th>
                    <th style="text-align: center;">Active Client</th>
                    <th style="text-align:left">Balance <i class="fa fa-sort sort_balance"></i></th>
                    <th style="text-align:left">Liability <i class="fa fa-sort sort_liability"></i></th>
                    <th style="text-align:left;width:100px">Payments <i class="fa fa-sort sort_payment"></i></th>
                    <th style="text-align:left;width:45px">'.$prelim_year.' Prelim <i class="fa fa-sort sort_prelim"></i></th>
                    <th style="text-align:left;width:100px">Progress <i class="fa fa-sort sort_progress"></i></th>
                    <th style="text-align:left;width:200px">Date Filled <i class="fa fa-sort sort_date"></i></th>
                    <th style="text-align:left">File Uploaded <i class="fa fa-sort sort_file"></i></th>
                  </tr>   
                </thead>
                <tbody id="task_body">';
            if(($year_client)){
                  foreach ($year_client as $single_client) {
                        $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $single_client->client_id)->first();                    
                        $attchement_client_id = $single_client->id;
                        $year_client_attachement_latest = \App\Models\YearendDistributionAttachments::where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->orderBy('updatetime', 'DESC')->first();
                        if(($year_client_attachement_latest)){
                              $final_attachement = \App\Models\YearendDistributionAttachments::where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->where('distribution_type', $year_client_attachement_latest->distribution_type)->get();
                              $output_attachement='';
                              $out_attachment = '';
                              if(($final_attachement)){
                                    foreach ($final_attachement as $single_attachement) {
                                          $output_attachement.='
                                          <a href="javascript:" class="fileattachment" data-element="'.URL::to("".$single_attachement->url.'/'.$single_attachement->attachments).'">'.$single_attachement->attachments.'</a><br/>';
                                          $out_attachment.=$single_attachement->attachments;
                                    }
                              }
                              else{
                                    $output_attachement='';
                                    $out_attachment = '';
                              }
                        }
                        else{
                              $final_attachement = '';
                              $output_attachement='';
                              $out_attachment = '';
                        }
                        $liability_details = \App\Models\YearClientLiability::where('year_id', $year_id)->where('row_id', $single_client->id)->where('client_id', $single_client->client_id)->where('setting_id',$setting_id)->first();
                        if(($liability_details)){
                              //$liability = $liability_details->liability1;
                              $update_time1 = strtotime($liability_details->liability1_updatetime);
                              $update_time2 = strtotime($liability_details->liability2_updatetime);
                              $update_time3 = strtotime($liability_details->liability3_updatetime);
                              if($update_time1>$update_time2 && $update_time1>$update_time3){
                                $liability = $liability_details->liability1;
                              }
                              else{
                                if($update_time2>$update_time1 && $update_time2>$update_time3){
                                  $liability = $liability_details->liability2;
                                }
                                else
                                  $liability = $liability_details->liability3;
                              }
                              $payments = $liability_details->payments;
                              $balance = $liability_details->balance;
                              $prelim = $liability_details->prelim;
                              if($liability == ''){
                                    $liability = '0.00';
                              }
                              if($balance == ''){
                                    $balance = '0.00';
                              }
                              elseif($balance == 0){
                                    $balance = '0.00';
                              }
                              if($liability_details->date_filled == 1)
                              {
                                    $yearend_date = $liability_details->yearend_date;
                                    $exp_yearend_date = explode("-",$yearend_date);
                                    if($yearend_date != "")
                                    {
                                          if($exp_yearend_date[1] == "Jan") { $year_month = "01"; }
                                          elseif($exp_yearend_date[1] == "Feb") { $year_month = "02"; }
                                          elseif($exp_yearend_date[1] == "Mar") { $year_month = "03"; }
                                          elseif($exp_yearend_date[1] == "Apr") { $year_month = "04"; }
                                          elseif($exp_yearend_date[1] == "May") { $year_month = "05"; }
                                          elseif($exp_yearend_date[1] == "Jun") { $year_month = "06"; }
                                          elseif($exp_yearend_date[1] == "Jul") { $year_month = "07"; }
                                          elseif($exp_yearend_date[1] == "Aug") { $year_month = "08"; }
                                          elseif($exp_yearend_date[1] == "Sep") { $year_month = "09"; }
                                          elseif($exp_yearend_date[1] == "Oct") { $year_month = "10"; }
                                          elseif($exp_yearend_date[1] == "Nov") { $year_month = "11"; }
                                          else { $year_month = "12"; }
                                          $formatted_date = strtotime($exp_yearend_date[2].'-'.$year_month.'-'.$exp_yearend_date[0]);
                                    }
                                    else{
                                          $formatted_date = 0;
                                    }
                                    $date_checked = 'checked';
                                    $date_disabled = '';
                              }
                              else{
                                    $yearend_date = $liability_details->yearend_date;
                                    $exp_yearend_date = explode("-",$yearend_date);
                                    if($yearend_date != "")
                                    {
                                          if($exp_yearend_date[1] == "Jan") { $year_month = "01"; }
                                          elseif($exp_yearend_date[1] == "Feb") { $year_month = "02"; }
                                          elseif($exp_yearend_date[1] == "Mar") { $year_month = "03"; }
                                          elseif($exp_yearend_date[1] == "Apr") { $year_month = "04"; }
                                          elseif($exp_yearend_date[1] == "May") { $year_month = "05"; }
                                          elseif($exp_yearend_date[1] == "Jun") { $year_month = "06"; }
                                          elseif($exp_yearend_date[1] == "Jul") { $year_month = "07"; }
                                          elseif($exp_yearend_date[1] == "Aug") { $year_month = "08"; }
                                          elseif($exp_yearend_date[1] == "Sep") { $year_month = "09"; }
                                          elseif($exp_yearend_date[1] == "Oct") { $year_month = "10"; }
                                          elseif($exp_yearend_date[1] == "Nov") { $year_month = "11"; }
                                          else { $year_month = "12"; }
                                          $formatted_date = strtotime($exp_yearend_date[2].'-'.$year_month.'-'.$exp_yearend_date[0]);
                                    }
                                    else{
                                          $formatted_date = 0;
                                    }
                                    $date_checked = '';
                                    $date_disabled = 'disabled';
                              }
                        }
                        else{
                              $liability ='0.00';
                              $payments = '';
                              $balance = '0.00';
                              $prelim = '';
                              $yearend_date = '';
                              $date_checked = '';
                              $date_disabled = 'disabled';
                              $formatted_date = 0;
                        }
                        $setting_client = $single_client->setting_id;
                        $setting_checkbox = $single_client->setting_active;
                        $explode_client = explode(',', $setting_client);
                        $explode_checkbox = explode(',', $setting_checkbox);
                        $get_setting_key = array_search($setting_id, $explode_client);
                        $get_checkbox = $explode_checkbox[$get_setting_key];
                        if($single_client->status == 0)
                          {
                            $color = 'color:#f00 !important;';
                          }
                          elseif($single_client->status == 1)
                          {
                            $color = 'color:#f7a001 !important;';
                          }
                          elseif($single_client->status == 2)
                          {
                            $color = 'color:#0000fb !important;';
                          }
                          else{
                            $color = 'color:#f00 !important;';
                          }
                          if($liability == '0.00'){
                              $color_liability = 'color:#0000fb';
                          }
                          elseif($liability < 0){
                              $color_liability = 'color:#0dce00';
                          }
                          else{
                              $color_liability = 'color:#f00';
                          }
                          if($balance == '0.00'){
                              $color_balance = 'color:#0000fb';
                          }
                          elseif(($balance < 0 && $balance >= -5) || ($balance > 0 && $balance <= 5)){
                              $color_balance = 'color:#0000fb';
                          }
                          elseif($balance < 0){
                              $color_balance = 'color:#0dce00';
                          }
                          else{
                              $color_balance = 'color:#f00';
                          }
                          $balance = str_replace(",","",$balance);
                          $balance = str_replace(",","",$balance);
                          $balance = str_replace(",","",$balance);
                          $balance = str_replace(",","",$balance);
                          $liability = str_replace(",","",$liability);
                          $liability = str_replace(",","",$liability);
                          $liability = str_replace(",","",$liability);
                          $liability = str_replace(",","",$liability);
                          $payments = str_replace(",","",$payments);
                          $payments = str_replace(",","",$payments);
                          $payments = str_replace(",","",$payments);
                          $payments = str_replace(",","",$payments);
                          if(is_numeric($payments) == 1)
                          {
                                $payments_without_comma = number_format_invoice_without_comma($payments);
                                $payments_with_comma = number_format_invoice($payments);
                          }
                          else{
                                $payments_without_comma = $payments;
                                $payments_with_comma = $payments;
                          }
                          $prelim = str_replace(",","",$prelim);
                          $prelim = str_replace(",","",$prelim);
                          $prelim = str_replace(",","",$prelim);
                          $prelim = str_replace(",","",$prelim);
                          if(is_numeric($prelim) == 1)
                          {
                                $prelim_without_comma = number_format_invoice_without_comma($prelim);
                                $prelim_with_comma = number_format_invoice($prelim);
                          }
                          else{
                                $prelim_without_comma = $prelim;
                                $prelim_with_comma = $prelim;
                          }
                          if($single_client->status == 0)
                          {
                            $fontcolor = 'color:#f00 !important;';
                          }
                          elseif($single_client->status == 1)
                          {
                            $fontcolor = 'color:#f7a001 !important;';
                          }
                          elseif($single_client->status == 2)
                          {
                            $fontcolor = 'color:#0000fb !important;';
                          }
                          else{
                            $fontcolor = 'color:#f00 !important;';
                          }
                          if($single_client->status == 0) { $stausval = 'Not Started'; }
                          elseif($single_client->status == 1) { $stausval = 'Inprogress'; }
                          elseif($single_client->status == 2) { $stausval = 'Completed'; }
                        if($get_checkbox == 0){
                              $output_result.='<tr id="client_'.$client_details->client_id.'" class="client_'.$client_details->active.'"">
                              <td style="'.$color.'"><a href="'.URL::to('user/yearend_individualclient/'.base64_encode($single_client->id).'').'" target="_blank" class="client_sort_val">'.$client_details->client_id.'</a></td>
                              <td style="'.$color.'"><a href="'.URL::to('user/yearend_individualclient/'.base64_encode($single_client->id).'').'" target="_blank" class="firstname_sort_val">'.$client_details->firstname.'</a></td>
                              <td style="'.$color.'"><a href="'.URL::to('user/yearend_individualclient/'.base64_encode($single_client->id).'').'" target="_blank" class="lastname_sort_val">'.$client_details->surname.'</a></td>
                              <td style="'.$color.'"><a href="'.URL::to('user/yearend_individualclient/'.base64_encode($single_client->id).'').'" target="_blank" class="company_sort_val">'.$client_details->company.'</a></td>
                              <td style="'.$color.';text-align: center;">
                                    <img class="active_client_list_tm1" data-iden="'.$single_client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
                              </td>
                              <td style="'.$color_balance.'"><span class="balance_sort_val" style="display:none">'.number_format_invoice_without_comma($balance).'</span><span class="balance_class">'.number_format_invoice($balance).'</span></td>
                              <td style="'.$color_liability.'"><span class="liability_sort_val" style="display:none">'.number_format_invoice_without_comma($liability).'</span>'.number_format_invoice($liability).'</td>
                              <td>
                                    <span class="payment_sort_val" style="display:none">'.$payments_without_comma.'</span>
                                    <spam class="payment_spam_class" style="display:none">'.$payments_with_comma.'</spam>
                                    <input type="text" class="form-control payment_class" data-element="'.$client_details->client_id.'" placeholder="Payments" value="'.$payments_with_comma.'" pattern="[0-9.,-]*" onkeypress="preventNonNumericalInput(event)"/>
                              </td>
                              <td>
                                    <span class="prelim_sort_val" style="display:none">'.$prelim_without_comma.'</span>
                                    <spam class="prelim_spam_class" style="display:none">'.$prelim_with_comma.'</spam>
                                    <input type="text" class="form-control prelim_class" data-element="'.$client_details->client_id.'"  placeholder="Prelim" value="'.$prelim_with_comma.'" pattern="[0-9.,-]*" onkeypress="preventNonNumericalInput(event)"/>
                              </td>
                              <td class="progress_sort_val" style="'.$fontcolor.'">
                                    '.$stausval.'
                              </td>
                              <td>
                                    <spam class="date_spam_class date_sort_val" style="display:none">'.$formatted_date.'</spam>
                                    <input type="checkbox" class="date_selection" id="date_selection_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" style="float: left;" value="1" '.$date_checked.'><label for="date_selection_'.$client_details->client_id.'" style="float:left;margin-top:5px">&nbsp;</label>
                                    <input type="text" class="form-control date_class" data-element="'.$client_details->client_id.'"  placeholder="Date" style="width:70%" value="'.$yearend_date.'" '.$date_disabled.'/>
                              </td>
                              <td><spam class="attachment_sort_val" style="display:none">'.$out_attachment.'</spam>'.$output_attachement.'</td>
                              </tr>';
                        }
                  }
            }
            else{
                  $output_result.='<tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td align="center">Empty</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>                  
                  </tr>';
            }
            $output_result.=' </tbody></table>';
            echo json_encode(array('output_result' => $output_result));
      }
      public function yearendliabilitypayment(Request $request){
            $value = $request->get('value');
            $setting_id = $request->get('setting_id');
            $year_id = $request->get('year_id');
            $client_id = $request->get('client_id');
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $yearend_client =\App\Models\YearClient::where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            $liability_details = \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->first();
            if(($liability_details)){
                  //$liability = $liability_details->liability1;
                  $update_time1 = strtotime($liability_details->liability1_updatetime);
                  $update_time2 = strtotime($liability_details->liability2_updatetime);
                  $update_time3 = strtotime($liability_details->liability3_updatetime);
                  if($update_time1>$update_time2 && $update_time1>$update_time3){
                    $balance = $liability_details->liability1-$value;
                  }
                  else{
                    if($update_time2>$update_time1 && $update_time2>$update_time3){
                      $balance = $liability_details->liability2-$value;
                    }
                    else
                      $balance = $liability_details->liability3-$value;
                  }                        
            }
            else{
                  $balance = '';
            }
            if($liability_details == ''){
                  $data['payments'] = $value;
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;
                  $data['balance'] = $balance;
                  \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->insert($data);
            }
            else{
                  $data['payments'] = $value;
                  $data['balance'] = $balance;
                 \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }
            if($balance == ''){
                  $balance_result = '<span style="color:#0000fb">0.00</span>';
                  $balance_spam = '0.00';
            }
            elseif($balance == 0){
                  $balance_result = '<span style="color:#0000fb">0.00</span>';
                  $balance_spam = '0.00';
            }
            elseif(($balance < 0 && $balance >= -5) || ($balance > 0 && $balance <= 5)){
                  $balance_result = '<span style="color:#0000fb">'.number_format_invoice_without_decimal($balance).'</span>';
                  $balance_spam = number_format_invoice_without_decimal($balance);
            }
            elseif($balance < 0){
                  $balance_result = '<span style="color:#0dce00">'.number_format_invoice_without_decimal($balance).'</span>';
                  $balance_spam = number_format_invoice_without_decimal($balance);
            }
            else{
                $balance_result = '<span style="color:#f00">'.number_format_invoice_without_decimal($balance).'</span>';
                $balance_spam = number_format_invoice_without_decimal($balance);
            }
            echo json_encode(array('balance' => $balance_result,'balance_spam' => $balance_spam, 'client_id' => $client_id));
      }
      public function yearendliabilityprelim(Request $request){
            $value = $request->get('value');
            $setting_id = $request->get('setting_id');
            $year_id = $request->get('year_id');
            $client_id = $request->get('client_id');
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $yearend_client =\App\Models\YearClient::where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            $liability_details = \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->first();
            if($liability_details == ''){
                  $data['prelim'] = $value;
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;                  
                  \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->insert($data);
            }
            else{
                  $data['prelim'] = $value;
                 \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }
      }
      public function yearendliabilityexport(Request $request){
            $setting_id = $request->get('setting_id');            
            $year_id = $request->get('year_id');
            $year_client =\App\Models\YearClient::where('year', $year_id)->get();
            $prelim_year = $year_id+1;
            $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=CM_Report.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
            );
            $columns = array('Client Id', 'First Name', 'Last Name', 'Company Name', 'Balance', 'Liability', 'Payments', ''.$prelim_year.' Prelim','Progress', 'Date Filled', 'File Uploaded');
            $callback = function() use ($year_client, $columns, $year_id, $setting_id){
                  $file = fopen('public/papers/Export_Liability.csv', 'w');
            fputcsv($file, $columns);
            if(($year_client)){
                  foreach ($year_client as $single_client) {
                        $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $single_client->client_id)->first();
                        if($client_details) {
                              $attchement_client_id = $single_client->id;

                              $stausval = '';
                              if($single_client->status == 0) { $stausval = 'Not Started'; }
                              elseif($single_client->status == 1) { $stausval = 'Inprogress'; }
                              elseif($single_client->status == 2) { $stausval = 'Completed'; }

                              $setting_client = $single_client->setting_id;                        
                              $setting_checkbox = $single_client->setting_active;                        
                              $explode_client = explode(',', $setting_client);
                              $explode_checkbox = explode(',', $setting_checkbox);
                              $get_setting_key = array_search($setting_id, $explode_client);                       
                              $get_checkbox = $explode_checkbox[$get_setting_key];
                              if($get_checkbox == 0){
                                    $liability_details = \App\Models\YearClientLiability::where('year_id', $year_id)->where('row_id', $single_client->id)->where('client_id', $single_client->client_id)->where('setting_id',$setting_id)->first();                              
                                    if($liability_details){
                                          //$liability = $liability_details->liability1;
                                          $update_time1 = strtotime($liability_details->liability1_updatetime);
                                          $update_time2 = strtotime($liability_details->liability2_updatetime);
                                          $update_time3 = strtotime($liability_details->liability3_updatetime);
                                          if($update_time1>$update_time2 && $update_time1>$update_time3){
                                            $liability = $liability_details->liability1;
                                          }
                                          else{
                                            if($update_time2>$update_time1 && $update_time2>$update_time3){
                                              $liability = $liability_details->liability2;
                                            }
                                            else
                                              $liability = $liability_details->liability3;
                                          }
                                          $payments = $liability_details->payments;
                                          $balance = $liability_details->balance;
                                          $prelim = $liability_details->prelim;
                                          $date_filled = $liability_details->yearend_date;
                                          if($liability == ''){
                                                $liability = '0.00';
                                          }
                                          if($balance == ''){
                                                $balance = '0.00';
                                          }
                                          elseif($balance == 0){
                                                $balance = '0.00';
                                          }
                                    }
                                    else{
                                          $liability ='0.00';
                                          $payments = '';
                                          $balance = '0.00';
                                          $prelim = '';
                                          $date_filled = '';
                                    }
                                    /*$columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, $balance, $liability, $payments, $prelim,$output_attachement);
                                    fputcsv($file, $columns_2);*/
                                    $year_client_attachement_latest = \App\Models\YearendDistributionAttachments::where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->orderBy('updatetime', 'DESC')->first();
                                    if(($year_client_attachement_latest)){
                                          $final_attachement = \App\Models\YearendDistributionAttachments::where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->where('distribution_type', $year_client_attachement_latest->distribution_type)->get();
                                          $output_attachement='';
                                          if(($final_attachement)){
                                                foreach ($final_attachement as $key => $single_attachement) {
                                                      if($key == 0){
                                                            $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),$stausval, $date_filled,$single_attachement->attachments);
                                                            fputcsv($file, $columns_2);
                                                      }
                                                      else{
                                                            $columns_2 = array('', '', '', '', '', '', '', '','','',$single_attachement->attachments);
                                                            fputcsv($file, $columns_2);
                                                      }
                                                }
                                          }
                                          else{
                                                $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),$stausval,$date_filled,'');
                                                fputcsv($file, $columns_2);
                                          }
                                    }
                                    else{
                                          $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),$stausval,$date_filled,'');
                                                fputcsv($file, $columns_2);
                                    }
                              } 
                        }                       
                  }
            }
            fclose($file);
            };
            return Response::stream($callback, 200, $headers);
      }
      public function yearend_export_to_csv(Request $request)
      {
            $year = $request->get('year');
            $file = fopen('public/papers/yearend_clients.csv', 'w');
            $columns = array('S.No', 'Client Id', 'First Name', 'Last Name', 'Company', 'Status');
            fputcsv($file, $columns);
            $i=1;
            $clientslist =\App\Models\YearClient::where('year', $year)->get();
            if(($clientslist)){
                  foreach ($clientslist as $client) {
                        $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client->client_id)->first();
                        if($client_details){
                              $clientid = $client_details->client_id;
                              $firstname = $client_details->firstname;
                              $lastname = $client_details->surname;
                              $company = $client_details->company;

                              if($client->status == 0)
                              {
                                    $color = 'color:#f00 !important;';
                              }
                              elseif($client->status == 1)
                              {
                                    $color = 'color:#f7a001 !important;';
                              }
                              elseif($client->status == 2)
                              {
                                    $color = 'color:#0000fb !important;';
                              }
                              else{
                                    $color = 'color:#f00 !important;';
                              }
                              if($i < 10)
                              {
                                    $i = '0'.$i;
                              }
                              if($client->status == 2) { 
                                  $stausval = 'Completed'; 
                                } else{
                                  $year_end_attachments = \App\Models\YearendDistributionAttachments::where('client_id',$client->id)->get();
                                  if(($year_end_attachments))
                                  {
                                    $stausval = 'Inprogress';
                                  }
                                  else{
                                    if($client_details->active == "2") { 
                                      $stausval = 'Inactive & Not Started'; 
                                    } 
                                    else { $stausval = 'Not Started'; } 
                                  }
                                }
                              $columns1 = array($i, $clientid,$firstname,$lastname,$company,$stausval);
                              fputcsv($file, $columns1);
                              $i++;
                        }
                  }
            }
            fclose($file);
            echo 'yearend_clients.csv';
      }
      public function save_yearend_date_status(Request $request)
      {
            $status = $request->get('status');
            $client_id = $request->get('client_id');
            $setting_id = $request->get('setting_id');
            $year_id = $request->get('year_id');
            $yearend_client =\App\Models\YearClient::where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            $liability_details = \App\Models\YearClientLiability::where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->first();
            $data['date_filled'] = $status;
            if(($liability_details))
            {
                  if($status != "0")
                  {
                        if($liability_details->yearend_date == "")
                        {
                              $date_filled = date('d-M-Y');
                              $data['yearend_date'] = $date_filled;
                        }
                        else{
                              $date_filled = $liability_details->yearend_date;
                        }
                  }
                  else{
                       $date_filled = ''; 
                       $data['yearend_date'] = $date_filled;
                  }
                  \App\Models\YearClientLiability::where('year_id',$year_id)->where('client_id',$client_id)->where('row_id', $row_id)->where('setting_id',$setting_id)->update($data);
            }
            else{
                  if($status != "0")
                  {
                        $date_filled = date('d-M-Y');
                        $data['yearend_date'] = $date_filled;
                  }
                  else{
                        $date_filled = '';
                        $data['yearend_date'] = $date_filled;
                  }
                  $data['payments'] = '';
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;
                  $data['balance'] = '';
                  \App\Models\YearClientLiability::insert($data);
            }
            echo $date_filled;
      }
      public function save_yearend_liability_date(Request $request)
      {
            $dateval = $request->get('dateval');
            $client_id = $request->get('client_id');
            $setting_id = $request->get('setting_id');
            $year_id = $request->get('year_id');
            $yearend_client =\App\Models\YearClient::where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            $data['yearend_date'] = $dateval;
            \App\Models\YearClientLiability::where('year_id',$year_id)->where('client_id',$client_id)->where('row_id', $row_id)->where('setting_id',$setting_id)->update($data);
            $exp_yearend_date = explode("-",$dateval);
            if($dateval != "")
            {
                  if($exp_yearend_date[1] == "Jan") { $year_month = "01"; }
                  elseif($exp_yearend_date[1] == "Feb") { $year_month = "02"; }
                  elseif($exp_yearend_date[1] == "Mar") { $year_month = "03"; }
                  elseif($exp_yearend_date[1] == "Apr") { $year_month = "04"; }
                  elseif($exp_yearend_date[1] == "May") { $year_month = "05"; }
                  elseif($exp_yearend_date[1] == "Jun") { $year_month = "06"; }
                  elseif($exp_yearend_date[1] == "Jul") { $year_month = "07"; }
                  elseif($exp_yearend_date[1] == "Aug") { $year_month = "08"; }
                  elseif($exp_yearend_date[1] == "Sep") { $year_month = "09"; }
                  elseif($exp_yearend_date[1] == "Oct") { $year_month = "10"; }
                  elseif($exp_yearend_date[1] == "Nov") { $year_month = "11"; }
                  else { $year_month = "12"; }
                  $formatted_date = strtotime($exp_yearend_date[2].'-'.$year_month.'-'.$exp_yearend_date[0]);
            }
            else{
                  $formatted_date = 0;
            }
            echo $formatted_date;
      }
      public function yearend_notes_update(Request $request)
      {
            $value = $request->get('value');
            $year_id = $request->get('year_id');
            $client_id = $request->get('client_id');
            $data['notes'] = $value;
           \App\Models\YearClient::where('year',$year_id)->where('client_id',$client_id)->update($data);
      }
      public function yearend_status_notes_update(Request $request)
      {
            $status = $request->get('status');
            $year_id = $request->get('year_id');
            $client_id = $request->get('client_id');
            $data['notes_status'] = $status;
           \App\Models\YearClient::where('year',$year_id)->where('client_id',$client_id)->update($data);
      }
      public function remove_client_from_year(Request $request)
      {
            $id = $request->get('client_id');
            $details =\App\Models\YearClient::where('id',$id)->first();
            if(($details))
            {
                  $client_id = $details->client_id;
                  $year = $details->year;
                  \App\Models\YearClientLiability::where('client_id',$client_id)->where('year_id',$year)->delete();
                  \App\Models\YearendAmlAttachments::where('client_id',$id)->delete();
                  \App\Models\YearendDistributionAttachments::where('client_id',$id)->delete();
                  \App\Models\YearendNotesAttachments::where('client_id',$id)->delete();
                 \App\Models\YearClient::where('id',$id)->delete();
                  //return Redirect::back()->with('message', 'Client Removed from this year successfully');
            }
      }
      public function yearend_aml_risk_attachment(Request $request){
          if (!empty($_FILES)) {
                $year = $request->get('hidden_year_end_year');
                $fname = $_FILES['file']['name'];
                $fname_exp = explode('.',$fname);
                $filename_without_ext = trim($fname_exp[0]);
                $get_year_clients =\App\Models\YearClient::where('year',$year)->pluck('client_id')->toArray();
                if (in_array($filename_without_ext, $get_year_clients))
                {
                    $tmp_name = $_FILES['file']['tmp_name'];
                    $filename = $_FILES['file']['name'];
                    $clientid = $filename_without_ext;
                    $upload_dir = 'uploads/yearend_image';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    $upload_dir = $upload_dir.'/clientid_'.$clientid;
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    $upload_dir = $upload_dir.'/aml';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    $upload_dir = $upload_dir.'/'.time();
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    move_uploaded_file($tmp_name, $upload_dir.'/'.$filename); 
                    $get_client_year_id =\App\Models\YearClient::where('year',$year)->where('client_id',$clientid)->first();
                    $data['client_id'] = $get_client_year_id->id;
                    $data['attachments'] = $filename;
                    $data['url'] = $upload_dir;
                    \App\Models\YearendAmlAttachments::insert($data);
                    $fname = $fname.' - <spam class="aml_attach_success">Success</spam>';
                }
                else{
                    $fname = $fname.' - <spam class="aml_attach_error">File Error</spam>';
                }
                echo json_encode(array('filename' => $fname,'type' => 'aml'));
          }
      }
      public function edit_yearend_header_image(Request $request) {
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

                    DB::table('yearend_settings')->where('practice_code',Session::get('user_practice_code'))->update($dataval); 
                }
                echo json_encode(array('image' => URL::to($filename), 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 0));
            }
        }
        else {
            echo json_encode(array('image' => '', 'image_height' => $image_height, 'image_width' => $image_width, 'error' => 1));
        }
      }
      public function update_yearend_settings(Request $request) {
            $cc_email = $request->get('yearend_cc_email');
            $data['yearend_cc_email'] = $cc_email;

            $check_settings = DB::table('yearend_settings')->where('practice_code',Session::get('user_practice_code'))->first();
            if($check_settings) {
                  DB::table('yearend_settings')->where('id',$check_settings->id)->update($data);
            }
            else{
                  $data['practice_code'] = Session::get('user_practice_code');
                  DB::table('yearend_settings')->insert($data);
            }
            return redirect::back()->with('message', 'Yearend Setings Saved Sucessfully.');
      }
}