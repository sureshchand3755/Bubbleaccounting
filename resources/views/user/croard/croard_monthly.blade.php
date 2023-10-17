@extends('userheader')
@section('content')
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_croard.js'); ?>'></script>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<style>
  .margintop20{
  margin-top:20px !important;
  margin-bottom: 0px !important;
}
  .start_group{clear:both;}
.modal{
  z-index: 99999999;
}
#colorbox{
  z-index: 99999999999;
}
.attachment_div{
  margin-top: 10px;
  margin-left: 25px;
}
.add_attachment_month_year{
  float:left;
}
.email_unsent_label{
  margin-top: 10px;
margin-left: 25px;
}
.email_unsent{
  float:left;
}
.dz-remove{
    color: #000;
    font-weight: 800;
    text-transform: uppercase;
}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.status_icon{
padding: 10px;
width: 50%;
border-radius: 39px;
text-align: center;
}
.red_status{ background: #f00; color:#fff !important; }
.orange_status{ background: orange; color:#000 !important; }
.green_status{ background: green; color:#fff !important; }
.blue_status{ background: blue;color:#fff !important;  }
.yellow_status{ background: yellow !important; color:#000 !important;}

.table>thead>tr>th { background: #fff !important; }
.fa-sort{ cursor:pointer; }
.company_td { font-weight:800; }
.form-control[disabled] { background-color:#ececec !important; cursor: pointer; }
.fa-check { color:green; }
.fa-times { color:#f00; }
#table_administration_wrapper{ width:98%; }
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.modal_load_apply {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}
.modal_load_content {
    display:    none;
    position:   fixed;
    z-index:    999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
    display: block;
}

.disclose_label{ width:300px; }
.option_label{width:100%;}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}


.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}

.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
.notepad_div {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div textarea{
  height:212px;
}
.notepad_div_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes textarea{
  height:212px;
}

.notepad_div_progress_notes,.notepad_div_completion_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_progress_notes textarea, .notepad_div_completion_notes textarea{
  height:212px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.notepad_div_notes_add {
    border: 1px solid #000;
    width: 280px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes_add textarea{
  height:212px;
}
.edit_allocate_user{
  cursor: pointer;
  font-weight:600;
}
.disabled_icon{
  cursor:no-drop;
}
.disabled{
  pointer-events: none;
}
.disable_user{
  pointer-events: none;
  background: #c7c7c7;
}
.mark_as_incomplete{
  background: green;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
label{width:100%;}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.tr_closed{
  display:none;
}
.show_closed>.tr_closed{
  display:table-row !important;
}
/* Customize the label (the container) */
.form_checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.form_checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark_checkbox {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border:1px solid;
}

/* On mouse-over, add a grey background color */
.form_checkbox:hover input ~ .checkmark_checkbox {
  background-color: #fff;
  border:1px solid;
}

/* When the checkbox is checked, add a blue background */
.form_checkbox input:checked ~ .checkmark_checkbox {
  background-color: #fff;

}

/* Create the checkmark_checkbox/indicator (hidden when not checked) */
.checkmark_checkbox:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark_checkbox when checked */
.form_checkbox input:checked ~ .checkmark_checkbox:after {
  display: block;
}

/* Style the checkmark_checkbox/indicator */
.form_checkbox .checkmark_checkbox:after {
  left: 7px;
  top: 3px;
  width: 5px;
  height: 10px;
  border: solid #3a3a3a;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

.form_radio {
  display: block;
  position: relative;
  padding-right: 20px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.form_radio input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark_radio {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border-radius: 50%;
  border:1px solid #3a3a3a;
}

/* On mouse-over, add a grey background color */
.form_radio:hover input ~ .checkmark_radio {
  background-color: #fff;
}

/* When the radio button is checked, add a blue background */
.form_radio input:checked ~ .checkmark_radio {
  background-color: #fff;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark_radio:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.form_radio input:checked ~ .checkmark_radio:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.form_radio .checkmark_radio:after {
  top: 5px;
  left: 5px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background:green;
}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dz-message span{text-transform: capitalize !important; font-weight: bold;}
.trash_imageadd{
  cursor:pointer;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
      margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
  float: left;
  clear: both;
}
.dropzone .dz-preview p {
  font-size:12px !important;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.form_radio .text{}
.form_radio span{right: 0px; left: unset;}
input[type="checkbox"]:not(old), input[type="radio"]:not(old) {
  width:0px;
}
input[type="checkbox"]:not(old) + label, input[type="radio"]:not(old) + label { margin-left:0px; }
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> "> 
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" >Add Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_progress">
                 <div class="image_div_attachments_progress">
                    <form action="<?php echo URL::to('user/croard_upload_images'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_client_id_croard" id="hidden_client_id_croard" type="hidden" value="">
                    @csrf
</form>
                 </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade name_verify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Company name discrepancy</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <a href="javascript:" class="common_black_button refresh_blue_client" style="float:right;margin-bottom: 10px">Refresh The Client From CRO Details</a>
              <table class="table">
                <thead>
                  <th>Company Number</th>
                  <th>CM System Name</th>
                  <th>Type</th>
                  <th>CRO Name</th>
                  <th>CRO Number</th>
                  <th>CRO ARD</th>
                </thead>
                <tbody id="name_verify_tbody">
                </tbody>
              </table>
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>
<div class="modal fade automatic_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" >Updating - Awaiting CRO ARD</h4>
          </div>
          <div class="modal-body automatic_tbody" style="min-height:280px">  
              
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>

<div class="modal fade updated_croard_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:800; color:green">CRO ARD date is updated successfully for <spam class="title_client_code"></spam>. Updated date is shown under "Updated CRO ARD" Column.</h4>
          </div>
          <div class="modal-body " style="min-height:280px">  
              <table class="table own_table_white">
                <thead>
                  <tr><th>Client Code</th>
                  <th>Company Name</th>
                  <th>CRO Number</th>
                  <th>Current CRO ARD</th>
                  <th>Updated CRO ARD</th>
                </tr></thead>
                <tbody id="updated_tbody">
                  
                </tbody>
              </table>
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>
<?php
$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
?>
<div class="modal fade croard_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="croard_settings_form" id="croard_settings_form" method="post" action="<?php echo URL::to('user/save_croard_settings'); ?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >CRO-ARD Settings</h4>
            </div>
            <div class="modal-body">  
                <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                <?php
                if($croard_settings->email_header_url == '') {
                  $default_image = DB::table("email_header_images")->first();
                  if($default_image->url == "") {
                    $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                  }
                  else{
                    $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                  }
                }
                else {
                  $image_url = URL::to($croard_settings->email_header_url.'/'.$croard_settings->email_header_filename);
                }
                ?>
                <img src="<?php echo $image_url; ?>" class="email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                <input type="button" name="email_header_img_btn" class="common_black_button email_header_img_btn" value="Browse"> 
                <h4>Enter Email Signature:</h4>
                <textarea name="message_editor" id="editor1"><?php echo $croard_settings->croard_signature; ?></textarea>
                <h4>Enter CC Box:</h4>
                <input type="text" name="croard_cc_input" class="form-control croard_cc_input" value="<?php echo $croard_settings->croard_cc_email; ?>">
                <h4>Submission Days Allowed After ARD Date:</h4>
                <input type="text" name="croard_days_input" class="form-control croard_days_input" value="<?php echo $croard_settings->croard_submission_days; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                <h4>Username:</h4>
                <input id="validation-email" class="form-control" placeholder="Enter Username" value="<?php echo $croard_settings->username; ?>" name="username" type="text" required > 
                <h4>API Key:</h4>
                <input id="validation-cc-email" class="form-control" placeholder="Enter API Key" value="<?php echo $croard_settings->api_key; ?>" name="api_key" type="text" required> 

            </div>
            <div class="modal-footer">  
                <input type="submit" name="submit_croard_settings" class="common_black_button submit_croard_settings" value="Submit">
            </div>
          @csrf
</form>
        </div>
  </div>
</div>
<div class="modal fade change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_croard_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade rbo_review_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">RBO REVIEW</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <input type="button" name="show_ltd_rbo" id="show_ltd_rbo" class="common_black_button show_ltd_rbo" value="Show Active Ltd Clients Only" style="float:right">
              <input type="button" name="report_csv_rbo" id="report_csv_rbo" class="common_black_button report_csv_rbo" value="Report CSV" style="float:right">

              <div class="col-md-12" id="rbo_review_tbody">
              </div>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <form id="email_unsent_form" action="<?php echo URL::to('user/email_unsent_files_croard'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Email</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
            </div>
            <?php
              $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
              $uname = '<option value="">Select Username</option>';
              if(($userlist)){
                foreach ($userlist as $singleuser) {
                    if($uname == '')
                    {
                      $uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                    else{
                      $uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                  }
              }
            ?>
            <div class="col-md-9">
              <select name="select_user" id="select_user" class="form-control" title="Select the User" required>
                <?php echo $uname; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="to_user" id="to_user" class="form-control" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="cc_unsent" class="form-control" value="<?php echo $croard_settings->croard_cc_email; ?>" readonly required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_unsent" class="form-control subject_unsent" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment</label>
            </div>
            <div class="col-md-12" id="email_attachments">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_client_id_email_croard" id="hidden_client_id_email_croard" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_files_btn" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>


<div class="content_section" style="margin-bottom:10px">

<div class="page_title" style="z-index:999;">
  <h4 class="col-lg-5 padding_00 new_main_title">
            CRO - ARD Manager               
        </h4>
  <div class="row">
      <!-- Merged code snippet -->
      <div class="col-lg-12" style="display: flex; align-items: center;  border-bottom: 0px solid #c3c3c3;">
          <div class="col-lg-1">
              <label style="margin-top: 15px;">CRO Api Username:</label>
          </div>
          <div class="col-lg-1" style="padding: 0;">
              <input type="text" name="cro_username" class="form-control cro_username" id="cro_username" value="<?php echo $croard_settings->username; ?>" disabled style="margin-top: 10px;">
          </div>
          <div class="col-lg-1 text-right">
              <label style="margin-top: 15px;">CRO Api Key:</label>
          </div>
          <div class="col-lg-2" style="padding: 0;">
              <input type="text" name="cro_api" class="form-control cro_api" id="cro_api" value="<?php echo $croard_settings->api_key; ?>" disabled style="margin-top: 10px;">
          </div>
          <div class="col-lg-3" style="padding: 0;">
              <input type="button" name="check_company" class="common_black_button check_company" value="Check Company" data-toggle="modal" data-target=".search_company_modal" style="margin-top: 10px;">
              <input type="button" name="global_core_call" class="common_black_button global_core_call" value="Global Core Call" style="margin-top: 10px;"> 
              <a href="javascript:" id="settings_croard" class="fa fa-cog common_black_button" style="margin-top: 10px;" title="CRO ARD Settings"></a>
          </div>
          <div class="col-lg-4 text-right padding_00">
              <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete" style="width:auto;margin-top: 13px;">Hide Deactivated Accounts</label>
              <input type="button" name="show_ltd" id="show_ltd" class="common_black_button show_ltd" value="Show Active Ltd Clients Only">
              <input type="button" name="rbo_review_btn" id="rbo_review_btn" class="common_black_button rbo_review_btn" value="RBO Review">
          </div>
      </div>
  </div>
</div>
<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 15px;margin-bottom: 10px;">
  <li class="nav-item">
    <a href="<?php echo URL::to('user/manage_croard'); ?>" class="nav-link">Full CRO Listing</a>
  </li>
  <li class="nav-item active">
    <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">CRO Monthly listing</a>
  </li>
</ul>
<div class="row">
  <div class="col-lg-9">
      &nbsp;
  </div>
  <div class="col-md-1 text-right padding_00">
    <label style="margin-top:8px">Select Month & Year:</label>
  </div>
  <div class="col-md-2">
    <input type="text" name="select_month_year_text" id="select_month_year_text" class="select_month_year_text form-control" value="" style="width: 50%;float: left;">
    <!-- <select name="select_month" class="form-control select_month" style="width: 33%;float: left;">
      <option value="">Select Month</option>
      <option value="01">01 - January</option>
      <option value="02">02 - February</option>
      <option value="03">03 - March</option>
      <option value="04">04 - April</option>
      <option value="05">05 - May</option>
      <option value="06">06 - June</option>
      <option value="07">07 - July</option>
      <option value="08">08 - August</option>
      <option value="09">09 - September</option>
      <option value="10">10 - October</option>
      <option value="11">11 - November</option>
      <option value="12">12 - December</option>
    </select>
    <select name="select_year" class="form-control select_year" style="width: 33%;float: left">
      <option value="">Select Year</option>
      <?php
      $from = date('Y') - 10;
      $to = date('Y') + 10;
      for($i=$from; $i<=$to; $i++)
      {
        echo '<option value="'.$i.'">'.$i.'</option>';
      }
      ?>
    </select> -->
    <a href="javascript:" class="common_black_button search_month_year" style="width: 33%;float: left;">Submit</a>
  </div>
</div>
    <div class="table-responsive" style="width: 100%; float: left;margin-top: 10px">
      	<table class="table table-fixed-header own_table_white" style="width:100%;margin-top:0px; background: #fff">
	        <thead class="header">
	            <th style="width:3.5%;text-align: left;">S.No <i class="fa fa-sort sno_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:6%;text-align: left;">Client Code <i class="fa fa-sort clientid_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:25%;text-align: left;">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:7%;text-align: left;">CRO Number <i class="fa fa-sort cro_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">Type <i class="fa fa-sort type_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">CRO ARD <i class="fa fa-sort cro_ard_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:25%;text-align: left;">Submission </th>
	            <th style="width:5%;text-align: left;">Action</th>
	        </thead>                            
        	<tbody id="clients_tbody">
	           
        	</tbody>
        </table>
    </div>
</div>
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients CRO are Checked.</p>
  <p style="font-size:18px;font-weight: 600;">Checking CRO: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
</div>

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="cro_sortoptions" id="cro_sortoptions" value="asc">
<input type="hidden" name="ard_sortoptions" id="ard_sortoptions" value="asc">
<input type="hidden" name="type_sortoptions" id="type_sortoptions" value="asc">
<input type="hidden" name="cro_ard_sortoptions" id="cro_ard_sortoptions" value="asc">

<input type="hidden" name="sno_rbo_sortoptions" id="sno_rbo_sortoptions" value="asc">
<input type="hidden" name="clientid_rbo_sortoptions" id="clientid_rbo_sortoptions" value="asc">
<input type="hidden" name="company_rbo_sortoptions" id="company_rbo_sortoptions" value="asc">
<input type="hidden" name="cro_rbo_sortoptions" id="cro_rbo_sortoptions" value="asc">
<input type="hidden" name="type_rbo_sortoptions" id="type_rbo_sortoptions" value="asc">
<input type="hidden" name="rbo_ref_sortoptions" id="rbo_ref_sortoptions" value="asc">

<script>
var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
$(".select_month_year_text").datetimepicker({
    defaultDate: fullDate,
    format: 'L',
    format: 'MMM-YYYY',
});
$(".client_search_class_task").autocomplete({
  source: function(request, response) {
      $.ajax({
          url:"<?php echo URL::to('user/task_client_search'); ?>",
          dataType: "json",
          data: {
              term : request.term
          },
          success: function(data) {
              response(data);
          }
      });
  },
  minLength: 1,
  select: function( event, ui ) {
    $("#client_search_task").val(ui.item.id);
    $.ajax({
      dataType: "json",
      url:"<?php echo URL::to('user/task_client_search_select'); ?>",
      data:{value:ui.item.id},
      success: function(result){         
        $("#client_search_task").val(ui.item.id);
      }
    })
  }
});
$(document).ready(function() {
  <?php if(Session::has('message_client_id')) { ?>
    $(document).scrollTop( $("#clientidtr_<?php echo Session::get('message_client_id'); ?>").offset().top - parseInt(150) );  
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Task Created Successfully. </p>',fixed:true,width:"800px"});
  <?php } elseif(Session::has('message_settings')) { ?>
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">CROARD Settings saved successfully. </p>',fixed:true,width:"800px"});
  <?php } ?>
  $('.table-fixed-header').fixedHeader();
  $(".signature_file_check:checked").parents("td").find(".status_label").hide();
  $(".signature_file_check:checked").parents("td").find(".yellow_label").show();
  $(".signature_file_date").datetimepicker({
     defaultDate: "",
       format: 'L',
       format: 'DD/MM/YYYY',
  });

  $(".signature_file_date").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_croard_signature_date'); ?>",
      type:"post",
      data:{client:client,date:date},
      success:function(result)
      {

      }
    })
  }); 

    
});

function next_cro_check(count)
{
  var cro = $(".overlay_cro:eq("+count+")").html();
  var client = $(".overlay_cro:eq("+count+")").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/check_cro_in_api'); ?>",
    type:"post",
    dataType:"json",
    data:{cro:cro,client:client},
    success:function(result)
    {
      if(result['updated'] == 1)
      {
        $(".overlay_tr_"+client).find(".overlay_updated_croard").html(result['croard']);
        $("#clientidtr_"+client).find(".cro_ard_val").html(result['croard']);
        $("#clientidtr_"+client).find(".signature_file_date").val('');

        $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
        $("#clientidtr_"+client).find(".status_icon").addClass(result['color_status']);
        $("#clientidtr_"+client).find(".status_label").html(result['status_label']);
        $("#clientidtr_"+client).find(".signature_file_check").prop("checked",false);
        $("#clientidtr_"+client).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
        $("#clientidtr_"+client).find(".attachment_div").html("");
        $("#clientidtr_"+client).find(".email_unsent_label").html("");

        $("#clientidtr_"+client).find(".status_label").show();
        $("#clientidtr_"+client).find(".yellow_label").hide();
      }
      else{
        $("#clientidtr_"+client).find(".signature_file_date").val(result['signature_date']);
        $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
        $("#clientidtr_"+client).find(".signature_file_check").prop("checked",true);
        $("#clientidtr_"+client).find(".status_label").hide();
        $("#clientidtr_"+client).find(".yellow_label").show();
        $("#clientidtr_"+client).find(".status_icon").addClass("yellow_status");
        $("#clientidtr_"+client).find(".status_icon").html('<spam class="status_label" style="display:none">'+result['status_label']+'</spam><spam class="yellow_label">Awaiting CRO Update</spam>');
      }
      setTimeout( function() {
        var countval = count + 1;
        if($(".overlay_cro:eq("+countval+")").length > 0)
        {
          next_cro_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
          var atleastone = 0;
          $(".overlay_updated_croard").each(function() {
            var val = $(this).html();
            if(val != ""){
              atleastone = atleastone + 1;
            }
          })

          if(atleastone > 0){
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">CRO ARD date is successfully updated and shown under "Updated CRO ARD" column. </p>',fixed:true,width:"800px"});
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Updated CRO ARD date is not yet available for any of the shown records. </p>',fixed:true,width:"800px"});
          }
          
        }
      },200);
    }
  });
}
function refresh_all_function(ival)
{
	var ival = ival + 1;
	var countval = $(".refresh_croard").length;
	var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
	var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
	var type = $(".refresh_croard:eq("+ival+")").attr("data-type");

	$("#count_first").html(ival);
  if(cro == "")
  {
    $.ajax({
        url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500);
          }
        }
    });
  }
  else {
    if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
    {
        setTimeout(function() {
            $.ajax({
        url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
        dataType:"json",
        type:"get",
        data:{clientid:clientid,cro:cro},
        success:function(result)
        {
          if(result['companystatus'] == "0")
          {
            $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
            $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
          }
          else{
            $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
            $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
          }
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          if(result['ardstatus'] == "1")
          {
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
          }
          else if(result['ardstatus'] == "2")
          {
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
          }
          else if(result['ardstatus'] == "3")
          {
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'orange'});
          }
          else{
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'blue'});
          }

          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500); 
          }
        }
      });
        },2000);
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500);
          }
        }
      });
    }
  }
}
function refresh_blue_function(ival)
{
  var ival = ival + 1;
  var countval = $(".refresh_blue_croard").length;
  var clientid = $(".refresh_blue_croard:eq("+ival+")").attr("data-element");
  var cro = $(".refresh_blue_croard:eq("+ival+")").attr("data-cro");
  var type = $(".refresh_blue_croard:eq("+ival+")").attr("data-type");

  $("#count_first").html(ival);
  $("#count_last").html(countval);
  console.log(countval);
  if(cro == "")
  {
    $.ajax({
        url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');

          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500);
          }
        }
    });
  }
  else {
    if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
    {
      $.ajax({
        url:"<?php echo URL::to('user/refresh_blue_cro_ard'); ?>",
        dataType:"json",
        type:"get",
        data:{clientid:clientid,cro:cro},
        success:function(result)
        {
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html(result['company_name']);

          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500); 
          }
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
          var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500);
          }
        }
      });
    }
  }
}
$(window).change(function(e) {
  if($(e.target).hasClass('select_user_author'))
  {
    var value = $(e.target).val();
    if(value == "")
    {
      $(".author_email").val("");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
        type:"post",
        data:{value:value},
        success:function(result)
        {
          $(".author_email").val(result);
        }
      })
    }
  }
  if($(e.target).hasClass('allocate_user_add')){
    var value = $(e.target).val();
    if(value == "")
    {
      $(".allocate_email").val("");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
        type:"post",
        data:{value:value},
        success:function(result)
        {
          $(".allocate_email").val(result);
        }
      })
    }
  }
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_vall').html()) <
        convertToNumeric($(b).find('.sno_sort_vall').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_vall').html()) <
        convertToNumeric($(b).find('.sno_sort_vall').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('clientid_sort'))
  {
    var sort = $("#clientid_sortoptions").val();
    if(sort == 'asc')
    {
      $("#clientid_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#clientid_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('company_sort'))
  {
    var sort = $("#company_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_sort_val').html())))) ? 1 : -1;
      });
    }
    else{
      $("#company_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_sort_val').html())))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_sort'))
  {
    var sort = $("#cro_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('ard_sort'))
  {
    var sort = $("#ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('type_sort'))
  {
    var sort = $("#type_sortoptions").val();
    if(sort == 'asc')
    {
      $("#type_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#type_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_ard_sort'))
  {
    var sort = $("#cro_ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('sno_rbo_sort'))
  {
    var sort = $("#sno_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_rbo_sort_val').html()) <
        convertToNumeric($(b).find('.sno_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_rbo_sort_val').html()) <
        convertToNumeric($(b).find('.sno_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('clientid_rbo_sort'))
  {
    var sort = $("#clientid_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#clientid_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_rbo_sort_val').html()) <
        convertToNumber($(b).find('.clientid_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#clientid_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_rbo_sort_val').html()) <
        convertToNumber($(b).find('.clientid_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('company_rbo_sort'))
  {
    var sort = $("#company_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_rbo_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_rbo_sort_val').html())))) ? 1 : -1;
      });
    }
    else{
      $("#company_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_rbo_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_rbo_sort_val').html())))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_rbo_sort'))
  {
    var sort = $("#cro_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_rbo_sort_val').html()) <
        convertToNumber($(b).find('.cro_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_rbo_sort_val').html()) <
        convertToNumber($(b).find('.cro_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('type_rbo_sort'))
  {
    var sort = $("#type_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#type_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_rbo_sort_val').html()) <
        convertToNumber($(b).find('.type_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#type_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_rbo_sort_val').html()) <
        convertToNumber($(b).find('.type_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('rbo_ref_sort'))
  {
    var sort = $("#rbo_ref_sortoptions").val();
    if(sort == 'asc')
    {
      $("#rbo_ref_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.rbo_ref_sort_val').html()) <
        convertToNumber($(b).find('.rbo_ref_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#rbo_ref_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.rbo_ref_sort_val').html()) <
        convertToNumber($(b).find('.rbo_ref_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the CRO ARD Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('search_month_year'))
  {
    var month_year = $(".select_month_year_text").val();

    if(month_year == "")
    {
      alert("Please select the Month and Year to Search.")
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/search_croard_month_year'); ?>",
        type:"post",
        data:{month_year:month_year},
        success:function(result){
          $("#clients_tbody").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('rbo_review_btn'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/rbo_review_list'); ?>",
      type:"post",
      success:function(result)
      {
        $("#rbo_review_tbody").html(result);
        $(".show_ltd_rbo").removeClass("show_all_rbo");
        $(".show_ltd_rbo").val("Show Active Ltd Clients Only");
        $(".rbo_review_modal").modal("show");
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('report_csv_rbo'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/report_csv_rbo'); ?>",
      type:"post",
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    });
  }
  if(e.target.id == "settings_croard")
  {
    if (CKEDITOR.instances.editor1) CKEDITOR.instances.editor1.destroy();

    CKEDITOR.replace('editor1',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    $(".croard_settings_modal").modal("show");
  }
  if($(e.target).hasClass('show_ltd'))
  {
    if($(e.target).hasClass('show_all'))
    {
      $(".type_sort_val").parents("tr").show();
      $(e.target).removeClass("show_all");
      $(e.target).val("Show Active Ltd Clients Only");
    }
    else{
      $(".type_sort_val").parents("tr").hide();
      $(".type_sort_val:contains(Ltd):not(:contains('UK Ltd'))").parents("tr").show();
      $(".type_sort_val").parents(".disabled_tr").hide();
      $(e.target).addClass("show_all");
      $(e.target).val("Show all Clients");
    }
  }
  if($(e.target).hasClass('show_ltd_rbo'))
  {
    if($(e.target).hasClass('show_all_rbo'))
    {
      $(".type_rbo_sort_val").parents("tr").show();
      $(e.target).removeClass("show_all_rbo");
      $(e.target).val("Show Active Ltd Clients Only");
    }
    else{
      $(".type_rbo_sort_val").parents("tr").hide();
      $(".type_rbo_sort_val:contains(Ltd):not(:contains('UK Ltd'))").parents("tr").show();
      $(".type_rbo_sort_val").parents(".disabled_rbo_tr").hide();
      $(e.target).addClass("show_all_rbo");
      $(e.target).val("Show all Clients");
    }
  }
  if($(e.target).hasClass('signature_file_check'))
  {
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("td:first").find(".status_label").hide();
      $(e.target).parents("td:first").find(".yellow_label").show();
      $(e.target).parents("td:first").find(".status_icon").addClass("yellow_status");
      var status = 1;
    }
    else{
      $(e.target).parents("td:first").find(".status_label").show();
      $(e.target).parents("td:first").find(".yellow_label").hide();
      $(e.target).parents("td:first").find(".status_icon").removeClass("yellow_status");
      var status = 0;
    }
    var client_id = $(e.target).attr("data-element");
    var crono = $(e.target).attr("data-crono");
    $.ajax({
      url:"<?php echo URL::to('user/change_yellow_status_croard'); ?>",
      type:"post",
      dataType:"json",
      data:{client_id:client_id,status:status,crono:crono},
      success:function(result)
      {
        if(result['updated'] == 1)
        {
          $(".overlay_tr_"+client_id).find(".overlay_updated_croard").html(result['croard']);
          $("#clientidtr_"+client_id).find(".cro_ard_val").html(result['croard']);
          $("#clientidtr_"+client_id).find(".signature_file_date").val('');

          $("#clientidtr_"+client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
          $("#clientidtr_"+client_id).find(".status_icon").addClass(result['color_status']);
          $("#clientidtr_"+client_id).find(".status_label").html(result['status_label']);
          $("#clientidtr_"+client_id).find(".signature_file_check").prop("checked",false);
          $("#clientidtr_"+client_id).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
          $("#clientidtr_"+client_id).find(".attachment_div").html("");
          $("#clientidtr_"+client_id).find(".email_unsent_label").html("");

          $(e.target).parents("td:first").find(".status_label").show();
          $(e.target).parents("td:first").find(".yellow_label").hide();

          $(".updated_croard_modal").modal("show");
          $(".title_client_code").html(client_id);
          $("#updated_tbody").html(result['html_output']);
          //$.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">CROARD Date Updated Successfully.</p>',fixed:true,width:"800px"});
        }
        else if(result['updated'] == 0){
          $("#clientidtr_"+client_id).find(".signature_file_date").val(result['signature_date']);
          $("#clientidtr_"+client_id).find(".signature_file_check").prop("checked",true);
          $(e.target).parents("td:first").find(".status_label").hide();
          $(e.target).parents("td:first").find(".yellow_label").show();
          $(e.target).parents("td:first").find(".status_icon").addClass("yellow_status");
          $(e.target).parents("td:first").find(".status_icon").html('<spam class="status_label" style="display:none">'+result['status_label']+'</spam><spam class="yellow_label">Awaiting CRO Update</spam>')
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">No Updates Found.</p>',fixed:true,width:"800px"});
        }
        else if(result['updated'] == 2){
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">It Seems that you dont have the CRO Number for this client. Can you please refresh the Client once and then try to submit the signature file.</p>',fixed:true,width:"800px"});
        }
        else if(result['updated'] == 3){
          $(e.target).parents("td:first").find(".signature_file_date").val("");
          $("#clientidtr_"+client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
          $("#clientidtr_"+client_id).find(".status_icon").addClass(result['color_status']);
          $("#clientidtr_"+client_id).find(".status_label").html(result['status_label']);

          $(e.target).parents("td:first").find(".status_label").show();
          $(e.target).parents("td:first").find(".yellow_label").hide();
        }

        $("body").removeClass("loading");
      },
      error:function(result){
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('email_unsent'))
  {
    if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
    var attach_len = $(e.target).parents("td:first").find(".attachment_link").length;
    if(attach_len > 0)
    {
      CKEDITOR.replace('editor',
         {
          height: '150px',
         });
      var client_id = $(e.target).attr('data-client');
      $.ajax({
        url:'<?php echo URL::to('user/edit_email_unsent_files_croard'); ?>',
        type:'get',
        data:{client_id:client_id},
        dataType:"json",
        success: function(result)
        {
          CKEDITOR.instances['editor'].setData(result['html']);
          $("#email_attachments").html(result['files']);
          $(".subject_unsent").val(result['subject']);
          $("#to_user").val(result['to']);
          $("#hidden_client_id_email_croard").val(client_id);
          $(".emailunsent").modal('show');
        }
      });
    }
    else{
      $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000"> Email option will be enabled only after you add a signature file.</p>',fixed:true,width:"800px"});
    }
  }
  if($(e.target).hasClass('email_unsent_files_btn'))
  {
    for (instance in CKEDITOR.instances) 
    {
        CKEDITOR.instances['editor'].updateElement();
    }
    if($("#email_unsent_form").valid())
    {
      $("body").addClass("loading");
      $('#email_unsent_form').ajaxForm({
          success:function(result){
            var date = result.split("||");
            $("#clientidtr_"+date[1]).find(".email_unsent_label").html("<p>"+date[0]+"</p>");
            $("body").removeClass("loading");
            $(".emailunsent").modal("hide");
            $("body").removeClass("loading");
          }
      }).submit();
    }
  }
  

  

  if($(e.target).hasClass('global_core_call'))
  {
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
  }
  if($(e.target).hasClass('refresh_blue_client'))
  {
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_blue_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
  }
  if($(e.target).hasClass('add_attachment_month_year'))
  {
  	var client = $(e.target).attr("data-client");
  	var attach_len = $(e.target).parents("td:first").find(".attachment_link").length;
  	if(attach_len > 0)
  	{
  		$.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000"> A B1 file is already listed, do you want to over write it?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_attach" data-element="'+client+'">Yes</a><a href="javascript:" class="common_black_button no_attach">No</a></p>',fixed:true,width:"800px"});
  	}
    else{
    	$("#hidden_client_id_croard").val(client);
	    $(".dropzone_progress_modal").modal("show");
	    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
  }
  if($(e.target).hasClass('yes_attach'))
  {
  	$.colorbox.close();
  	var client = $(e.target).attr("data-element");
  	$("#hidden_client_id_croard").val(client);
    $(".dropzone_progress_modal").modal("show");
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('no_attach'))
  {
  	$.colorbox.close();
  }
  if($(e.target).hasClass('delete_attachments'))
  {
    e.preventDefault();
    var hrefval = $(e.target).attr("href");
    var client = $(e.target).attr("data-client");
    var month = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete this file?");
    if(r)
    {
      $.ajax({
        url:hrefval,
        type:"post",
        success:function(result)
        {
          $(e.target).parents("p:first").detach();
          $(".tasks_tr_"+client).find("#add_files_vat_client_"+month).find(".attachment_div").find('.delete_attachments[href="'+hrefval+'"]').parents("p:first").detach();
        }
      })
    }
  }
  if($(e.target).hasClass("dropzone"))
  {
    $(e.target).parents('td').find('.img_div').show();    
    $(e.target).parents('.modal-body').find('.img_div').show();    
  }
  if($(e.target).hasClass("remove_dropzone_attach"))
  {
    $(e.target).parents('td').find('.img_div').show();   
    $(e.target).parents('.modal-body').find('.img_div').show(); 
  }
  if($(e.target).parent().hasClass("dz-message"))
  {
    $(e.target).parents('td').find('.img_div').show();
    $(e.target).parents('.modal-body').find('.img_div').show(); 
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id,file_id:file_id},
      success: function(result)
      {
        var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;
        if(countval == 1)
        {
          $(e.target).parents(".dropzone").removeClass("dz-started");
        }
        $(e.target).parents(".dz-preview").detach();
      }
    })
  }  
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('download_pdf_task'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/download_taskmanager_task_pdf'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('make_task_live'))
  {
    e.preventDefault();
    if($("#create_task_form").valid())
    {
      if($("#internal_checkbox").is(":checked"))
      {
          var taskvalue = $("#idtask").val();
          if(taskvalue == "")
          {
            alert("Please select the Task Name and then make the task as live");
            return false;
          }
      }
      else{
        var clientid = $("#client_search_task").val();
        if(clientid == "")
        {
          alert("Please select the Client and then make the task as live");
          return false;
        }
      }
      if (CKEDITOR.instances.editor_2)
      {
        var comments = CKEDITOR.instances['editor_2'].getData();
        if(comments == "")
        {
          alert("Please Enter Task Specifics and then make the task as Live.");
          return false;
        }
        else{
          if($(".2_bill_task").is(":checked"))
          {
            $("#create_task_form").submit();
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px",height:"400px"});
          }
        }
      }
      else{
        if($(".2_bill_task").is(":checked"))
        {
          $("#create_task_form").submit();
        }
        else{
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px",height:"400px"});
        }
      }
    }
  }
  if($(e.target).hasClass('yes_make_task_live'))
  {
    $(".2_bill_task").prop("checked",true);
      $("#create_task_form").submit();
  }
  if($(e.target).hasClass('no_make_task_live'))
  {
    $(".2_bill_task").prop("checked",false);
      $("#create_task_form").submit();
  }
  if($(e.target).hasClass('accept_recurring'))
  {
    if($(e.target).is(":checked"))
    {
      $(".accept_recurring_div").show();
      $("#recurring_checkbox1").prop("checked",true);
    }
    else{
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }
  }
  if($(e.target).hasClass('remove_infile_link_add'))
  {
    var file_id = $(e.target).attr("data-element");
    var ids = $("#hidden_infiles_id").val();
    var idval = ids.split(",");
    var nextids = '';
    $.each(idval, function( index, value ) {
      if(value != file_id)
      {
        if(nextids == "")
        {
          nextids = value;
        }
        else{
          nextids = nextids+','+value;
        }
      }
    });
    $("#hidden_infiles_id").val(nextids);
    $(e.target).parents("tr").detach();
  }
  if(e.target.id == "link_infile_button")
  {
    var checkcount = $(".infile_check:checked").length;
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_id").val(ids);
      $(".infiles_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_infiles'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("#attachments_infiles").show();
          $("#add_infiles_attachments_div").show();
          $("#add_infiles_attachments_div").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('infiles_link'))
  {
    var client_id = $("#client_search_task").val();
    var ids = $("#hidden_infiles_id").val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $(".infiles_modal").modal("show");
          $("#infiles_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('notepad_submit_task'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_task').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_notes_task').toggle();
    }
  }
  else{
    $(".notepad_div_notes_task").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('notepad_submit_task'))
  {
    var contents = $(".notepad_contents_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div_task").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_task' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_task").hide();
      }
    });
  }
  if($(e.target).hasClass("create_task_manager"))
  {
    var client = $(e.target).attr("data-client");
    var clientname = $(e.target).attr("data-clientname");

    $(".client_search_class_task").val(clientname);
    $("#client_search_task").val(client);
    
    $(".create_new_task_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".create_new_task_model").modal("show");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
    $(".created_date").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
       maxDate: fullDate,
    });
    $(".due_date").datetimepicker({
       defaultDate: fullDate,
       format: 'L',
       format: 'DD-MMM-YYYY',
       minDate: fullDate,
    });
    CKEDITOR.replace('editor_2',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
   });

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("CRO ARD System Task");
    $(".task_specifics_add").show();
    CKEDITOR.instances['editor_2'].setData("Do the Annual Return");
    
    $(".retreived_files_div").hide();
    $(".retreived_files_div").html("");
    $(".recurring_checkbox").prop("checked", false);
    $(".specific_recurring").val("");
    $(".task_specifics_copy_val").html("");
    $("#hidden_task_specifics").val("");

    $("#hidden_specific_type").val("");
    $("#hidden_attachment_type").val("");

    $(".created_date").prop("readonly", true);
    $(".client_group").show();
    $(".client_search_class").prop("required",true);
    $(".internal_tasks_group").hide();
    $("#internal_checkbox").prop("checked",false);
    $(".infiles_link").show();
    $("#attachments_text").hide();
    
    $("#attachments_infiles").hide();
    $("#idtask").val("");

    $("#hidden_copied_files").val("");
    $("#hidden_copied_notes").val("");
    $("#hidden_copied_infiles").val("");

    $(".auto_close_task").prop("checked",false);
    $(".accept_recurring").prop("checked",false);
    $(".accept_recurring_div").hide();
    $("#recurring_checkbox1").prop("checked",false);

    $("#open_task").prop("checked",false);
    $(".allocate_user_add").removeClass("disable_user");
    $(".allocate_email").removeClass("disable_user");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
      }
    })
  }
  
  if($(e.target).hasClass('fanotepadtask')){
    var clientid = $("#client_search_task").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_task').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('remove_dropzone_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('remove_notepad_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_notepad_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  if(e.target.id == "open_task")
  {
    if($(e.target).is(":checked"))
    {
      $(".allocate_user_add").val("");
      $(".allocate_user_add").addClass("disable_user");
      $(".allocate_email").addClass("disable_user");
    }
    else{
      $(".allocate_user_add").val("");
      $(".allocate_user_add").removeClass("disable_user");
      $(".allocate_email").removeClass("disable_user");
    }
  }
  if($(e.target).hasClass('yes_proceed'))
  {
    $("body").addClass("loading_content");
    $.colorbox.close();
    var ival = 0;
    var countval = $(".refresh_croard").length;

    var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
    var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
    var type = $(".refresh_croard:eq("+ival+")").attr("data-type");

    setTimeout(function() {
      $("#count_last").html(countval);
      $("#count_first").html(ival);
      if(cro == "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
          type:"post",
          data:{clientid:clientid},
          success:function(result)
          {
            $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
            $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
            var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
            else { 
              setTimeout( function() {
                refresh_all_function(ival); 
              },500);
            }   
          }
        });
      }
      else{
        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited") {
          $.ajax({
            url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
            dataType:"json",
            type:"get",
            data:{clientid:clientid,cro:cro},
            success:function(result)
            {
              if(result['companystatus'] == "0")
              {
                $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
                $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
              }
              else{
                $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
                $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
              }
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
              if(result['ardstatus'] == "1")
              {
               $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
               $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
              }
              else if(result['ardstatus'] == "2")
              {
               $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
               $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
              }
              else if(result['ardstatus'] == "3")
              {
               $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
               $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'orange'});
              }
              else{
               $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
               $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'blue'});
              }

              var ivali = ival + 1;
              if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
            type:"post",
            data:{clientid:clientid},
            success:function(result)
            {
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
              $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
              var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
      }
    },1000);
  }
  if($(e.target).hasClass('yes_blue_proceed'))
  {
    $("body").addClass("loading_content");
    $.colorbox.close();
    var ival = 0;
    var countval = $(".refresh_blue_croard").length;
    var clientid = $(".refresh_blue_croard:eq("+ival+")").attr("data-element");
    var cro = $(".refresh_blue_croard:eq("+ival+")").attr("data-cro");
    var type = $(".refresh_blue_croard:eq("+ival+")").attr("data-type");

    setTimeout(function() {
      $("#count_last").html(countval);
      $("#count_first").html(ival);
      if(cro == "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
          type:"post",
          data:{clientid:clientid},
          success:function(result)
          {
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
            var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
            else { 
              setTimeout( function() {
                refresh_blue_function(ival); 
              },500);
            }
          }
        });
      }
      else{
        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited") {
          $.ajax({
            url:"<?php echo URL::to('user/refresh_blue_cro_ard'); ?>",
            dataType:"json",
            type:"get",
            data:{clientid:clientid,cro:cro},
            success:function(result)
            {
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html(result['company_name']);
            

              var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_blue_function(ival); 
                },500);
              }
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
            type:"post",
            data:{clientid:clientid},
            success:function(result)
            {
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
              var ivali = ival + 1;
            if(ivali == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_blue_function(ival); 
                },500);
              }
            }
          });
        }
      }
    },1000);
  }
  if($(e.target).hasClass('no_proceed'))
  {
    $.colorbox.close();
  }
  
  if(e.target.id == 'show_incomplete')
  {
      if($(e.target).is(':checked'))
      {
        $(".edit_task").each(function() {
            if($(this).hasClass('disabled_tr'))
            {
              $(this).hide();
            }
        });
      }
      else{
        $(".edit_task").each(function() {
            if($(this).hasClass('disabled_tr'))
            {
              $(this).show();
            }
        });
      }
  }
  
  if($(e.target).hasClass('refresh_croard'))
  {
    var clientid = $(e.target).attr("data-element");
    var cro = $(e.target).attr("data-cro");
    var type = $(e.target).attr("data-type");

    $.ajax({
      url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
      type:"post",
      data:{clientid:clientid},
      success:function(result)
      {
        $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
        $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
        if(cro == "")
        {
          alert("Sorry you cant fetch the details from api because the CRO Number for this client is empty.");
        }

        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed_single" data-element="'+clientid+'" data-cro="'+cro+'">Yes</a><a href="javascript:" class="common_black_button no_proceed_single">No</a></p>',fixed:true,width:"800px"});
        }
        else{
          alert("Sorry you cant fetch the details from api because the type should be 'Ltd' or 'Limited'.")
        }
      }
    })
  }
  if($(e.target).hasClass('yes_proceed_single'))
  {
    $("body").addClass("loading");
    var clientid = $(e.target).attr("data-element");
    var cro = $(e.target).attr("data-cro");
    $.ajax({
      url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
      dataType:"json",
      type:"get",
      data:{clientid:clientid,cro:cro},
      success:function(result)
      {
        if(result['companystatus'] == "0")
        {
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
        }
        else{
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
        }
        $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
        if(result['ardstatus'] == "1")
        {
         $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
         $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
        }
        else if(result['ardstatus'] == "2")
        {
         $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
         $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
        }
        else if(result['ardstatus'] == "3")
        {
         $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
         $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'orange'});
        }
        else{
         $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
         $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'blue'});
        }
        $.colorbox.close();
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('no_proceed_single'))
  {
    $.colorbox.close();
  }

  $(".cro_notes").blur(function() {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    
      $.ajax({
          url:"<?php echo URL::to('user/update_cro_notes'); ?>",
          type:"post",
          data:{input_val:input_val,clientid:clientid},
          success: function(result) {

          }
      });
  });
  $(".rbo_submission_text").blur(function() {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    
      $.ajax({
          url:"<?php echo URL::to('user/update_rbo_submission'); ?>",
          type:"post",
          data:{input_val:input_val,clientid:clientid},
          success: function(result) {

          }
      });
  });

  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example
  var $input1 = $('.cro_notes');
  var $input2 = $('.rbo_submission_text');
  $input1.on('keyup', function () {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_cro, doneTypingInterval,input_val,clientid,that);
  });
  $input2.on('keyup', function () {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_rbo, doneTypingInterval,input_val,clientid,that);
  });
  //on keydown, clear the countdown 
  $input1.on('keydown', function () {
    clearTimeout(typingTimer);
  });
  $input2.on('keydown', function () {
    clearTimeout(typingTimer);
  });
})
var convertToNumber = function(value){
       var lowercase = value.toLowerCase();
       return lowercase.trim();
}
var convertToNumeric = function(value){
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');

       return parseInt(value.toLowerCase());
}


function doneTyping_cro (input,clientid,that) {
  $.ajax({
      url:"<?php echo URL::to('user/update_cro_notes'); ?>",
      type:"post",
      data:{input_val:input,clientid:clientid},
      success: function(result) {

      }
  });
}

function doneTyping_rbo (input,clientid,that) {
  $.ajax({
      url:"<?php echo URL::to('user/update_rbo_submission'); ?>",
      type:"post",
      data:{input_val:input,clientid:clientid},
      success: function(result) {

      }
  });
}

$.ajaxSetup({async:false});
$('#email_unsent_form').validate({
    rules: {
        select_user : {required: true,},
        to_user : {required: true,},
        cc_unsent : {required: true,},
    },
    messages: {
        select_user : "Please select a From User",
        to_user : "Please Enter the Email ID to send a mail",
        cc_unsent : "Please create a CC Mail ID in CROARD Settings Overlay",
    },
});


fileList = new Array();
//$.ajaxSetup({async:false});
Dropzone.options.ImageUploadEmail = {
    maxFiles: 1,
    acceptedFiles: ".png",
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            if(obj.error == 1) {
              $("body").removeClass("loading");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRO ARD Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRO ARD Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
    },
};
Dropzone.options.imageUpload = {
    maxFiles: 1,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $(".dropzone_progress_modal").modal("hide");
            var croard_date = $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html();
            var cro_number =  $("#clientidtr_"+obj.client_id).find(".check_cro").html();
            $("#clientidtr_"+obj.client_id).find(".attachment_div").html('<a class="attachment_link" href="'+obj.download_url+'" download>'+obj.filename+'</a>');

            $("body").removeClass("loading");
            // $.ajax({
            //   url:"<?php echo URL::to('user/get_company_details_next_crd'); ?>",
            //   type:"post",
            //   dataType:"json",
            //   data:{company_number:cro_number,indicator:'C',client_id:obj.client_id},
            //   success:function(result)
            //   {
            //     $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html(result['croard']);
            //     $("#clientidtr_"+obj.client_id).find(".signature_file_date").val(result['croard']);
            //     if(result['updated'] == 1)
            //     {
            //       $("#clientidtr_"+obj.client_id).find(".signature_file_check").prop("checked",false);
            //       $("#clientidtr_"+obj.client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
            //     }
            //     else{
            //       $("#clientidtr_"+obj.client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status');
            //     }
                
            //     $("#clientidtr_"+obj.client_id).find(".status_icon").addClass(result['color_status']);
            //     $("#clientidtr_"+obj.client_id).find(".status_icon").html(result['status_label']);
            //     var cro_ard = $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html();
            //     var ard = $("#clientidtr_"+obj.client_id).find(".ard_val").html();
            //     if(cro_ard == ard)
            //     {
            //       $("#clientidtr_"+obj.client_id).find(".cro_ard_val").parents("td:first").css("color","green");
            //     }
            //     else{
            //       $("#clientidtr_"+obj.client_id).find(".cro_ard_val").parents("td:first").css("color","#f00");
            //     }
            //     $("body").removeClass("loading");
            //   }
            // });
        });
        this.on("complete", function (file, response) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            //$(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
        });
    },
};
Dropzone.options.imageUpload5 = {
    maxFiles: 2000,
    acceptedFiles: null,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div_task").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.file_id+"'>Remove</a></p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>
@stop
