@extends('userheader')
@section('content')
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_pms.js'); ?>'></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<style>
.file_received_div_right{max-width: 350px; max-height: auto; height:auto; overflow: hidden;overflow-x: scroll;   margin-top:20px !important;}
  .margintop20{
  margin-top:20px !important;
  margin-bottom: 0px !important;
}
.tasks_drop{
  text-align: left !important;
}
  .start_group{clear:both;}
  body{ background: #fff !important; }
.header-copy{top: 200px !important;background: #fff;z-index:99;}
  .start_rating { cursor:pointer; font-size: 24px;margin-top: 20px;}
  .start_red { color:#f0ff00; }
  .start_lred { color:#ffce00; }
  .start_orange { color:#ff9a00; }
  .start_brown { color:#ff5a00; }
  .start_yellow { color:#ff0000; }
  .fa-star-o { color:#000 !important; }
  .error{ color:#f00; }
  .secret_button:focus { outline: none; }
  #colorbox { z-index:99999999999999999999 !important; }
  .modal_load_content {
      display:    none;
      position:   fixed;
      z-index:    999999999999999999;
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
  .fa-link { margin-left:-29px; }
  .fa-times-circle,.fa-check-circle-o { font-size: 23px; }
  .fa-sort { display:none !important; }
  .disable_user, .disabled_scheme{
  pointer-events: none;
  background: #c7c7c7;
}
.img_div{ z-index:9999; }
#task_body_std>tr>td,#task_body_cmp>tr>td,#task_body_enh>tr>td
{
  min-height: 700px !important;
  max-height: 700px !important;
  height: 340px !important;
}
.scroll_attachment_div { 
    width: 500px;
    padding-left: 10px;
    line-height: 25px;
    margin-top: 25px;    
}
.disclose_label{ width:300px; }
.option_label{width:100%;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
      margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
}
.dropzone .dz-preview p {
  font-size:12px !important;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}
.error_files_notepad
{
  color:#f00;
}
.notepad_div {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
    z-index:999999 !important;
}
.notepad_div textarea{
  height:212px;
}
.table_bg > tbody > tr > td, .table_bg > tbody > tr > th, .table_bg > tfoot > tr > td, .table_bg > tfoot > tr > th, .table_bg > thead > tr > td
{
  color:#000 !important;
}
@-moz-document url-prefix('') {
    .special_td{
        margin-top:-1px !important;
        width: 105px !important;
    }
    .special_div{
      width:630% !important;
    }
}
.fa-minus-square{
  margin-left:15px;
  cursor:pointer;
}
.text_checkbox{
      margin-top: 10px;
    color: #000;
    font-weight: 700;
}
.comments_input{
    margin-top: 10px;
    width: 213%;
    height: 200px !important;
    position: relative;
}
.uname_input{
  margin-top: 0px;
}
.task_email_input{
  margin-top: 0px;
}
.date_input{
  margin-top: 0px;
  margin-bottom: 10px
}
.time_input{
  margin-top: 0px;
}
.footer_row{
   display:none !important;
}
.modal{
  z-index:99999 !important;
}
#alert_modal{
  z-index:9999999 !important;
}
#alert_modal_edit{
  z-index:9999999 !important;
}
.attach_align{
  text-align: left !important;
}
.copy_label{
      font-size: 12px;
    color: #000;
    text-align: left;
    padding: 3px 14px;
}
.fileattachment{
  font-weight:800;
}
.fileattachment:hover{
  font-weight:800;
}
.fa-sort{
  cursor: pointer;
}
.table_bg tbody tr td{
  padding:8px;
}
.table_bg thead tr th{
  padding:8px;
  color:#000;
  background: #fff;
}
.email_sort_std,.email_sort_enh,.email_sort_cmp{
  width:10% !important;
}
.task_sort_std,.task_sort_enh,.task_sort_cmp{
  text-align: left !important;
}
.task_sort_std_val,.task_sort_enh_val,.task_sort_cmp_val{
  text-align: left !important;
}
.task_tr_std,.task_tr_enh,.task_tr_cmp
{
  vertical-align: top !important;
}
.page_title{
  background: #fff !important;
  margin-bottom: 0px !important;
}
.button_top_right ul{
      margin: 0px 0px 0px 0px !important;
}
.error_files{
  color:#f00;
  font-weight:800;
}
.email_unsent_label{
  width:100%;
}
.download_div
{
    position: absolute;
    top: 43px;
    left:-70px;
    width: 300px;
    background: #ff0;
    padding: 9px;
    line-height: 31px;
}
.close_xmark{opacity: 1 !important; padding: 4px 8px !important;}
.close_xmark:hover{opacity: 0.6;}
.notify_div
{
    position: absolute;
    top: 43px;
    left:-70px;
    width: 300px;
    background: #ff0;
    padding: 9px;
    line-height: 31px;
}
.close_xmark{
       position: absolute;
    right: 0px;
    top: 0;
    font-weight: 800;
    padding: 0px 5px;
    background: #000000;
    color: #ffcd44;
    font-size: 10px;
}
.close_xmark:focus, .close_xmark:hover {
    color: #641500;
    text-decoration: none !important;
}
.download_button
{
      background: #000;
    padding: 5px 10px;
    float: left;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
}
.download_radio{
        width: 85%;
    clear: both;
    float: left;
    padding: 5px;
    margin-left: 15px;
    border-bottom: 1px solid;
}
.download_radio:hover{
            width: 85%;
    clear: both;
    float: left;
    margin-left: 15px;
    border-bottom: 1px solid #000;
    background: #000;
}
.notify_radio{
        width: 85%;
    clear: both;
    float: left;
    padding: 5px;
    margin-left: 15px;
    border-bottom: 1px solid;
}
.notify_radio:hover{
            width: 85%;
    clear: both;
    float: left;
    margin-left: 15px;
    border-bottom: 1px solid #000;
    background: #000;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999999999;
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
.ui-widget{z-index: 999999999}
.form-control[readonly]{background: #eaeaea !important}
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<?php 
  $pms_admin_details = DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->first();
  $admin_cc = $pms_admin_details->payroll_cc_email;
?> 
<!--*************************************************************************-->
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999 !important">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  
          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<!-- <div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight: 700; font-size: 20px;">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row"> 
                <div class="col-md-2">
                  <label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-3">
                  <select name="select_user" class="form-control select_user_author" required>
                    <option value="">Select User</option>        
                      <?php
                      $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                      $selected = '';
                      if(($userlist)){
                        foreach ($userlist as $user) {
                      ?>
                        <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label style="margin-top:5px">Author Email:</label>
                </div>
                <div class="col-md-4">
                  <input  type="email" class="form-control author_email" name="author_email" placeholder="Enter Author's Email" required>
                </div>
            </div>
            <div class="row margintop20" style="margin-top:7px">
                <div class="col-md-2">
                  <label style="margin-top:5px">Allocate To:</label>
                </div>
                <div class="col-md-3">
                  <select name="allocate_user" class="form-control allocate_user_add">
                    <option value="">Select User</option>        
                      <?php
                      $selected = '';
                      if(($userlist)){
                        foreach ($userlist as $user) {
                          if(Session::has('task_manager_user'))
                          {
                            if($user->user_id == Session::get('task_manager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }
                      ?>
                        <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label style="margin-top:5px">Allocate To Email:</label>
                </div>
                <div class="col-md-3">
                  <input  type="email" class="form-control allocate_email" name="allocate_email" placeholder="Enter Allocate's Email" required>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                    <input type='checkbox' name="open_task" id="open_task" value="1"/>
                    <label for="open_task">OpenTask</label>
                  </div>
                </div>
            </div>
            <div class="row margintop20" style="margin-top:14px">
              <div class="col-md-2 client_group">
                  <label style="margin-top:5px">Client:</label>
                </div>
                <?php
                if(isset($_GET['client_id']))
                {
                  $client_id = $_GET['client_id'];
                  $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
                  $company = $client_details->company.'-'.$client_id;
                }
                else{
                  $client_id = '';
                  $company = '';
                }
                ?>
                <div class="col-md-8 client_group">
                  <input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="" required>
                  <input type="hidden" id="client_search_task" name="clientid" value=""/>
                </div>
                <div class="col-md-3 internal_tasks_group" style="display: none;">
                  <label style="margin-top:5px">Select Task:</label>
                </div>
                <div class="col-md-7 internal_tasks_group" style="display: none;">
                  <div class="dropdown" style="width: 100%">
                    <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                      <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
                    </a>
                    <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal">Select Task</a></li>
                        <?php
                        $taskslist = DB::table('time_task')->where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
                        if(($taskslist)){
                          foreach ($taskslist as $single_task) {
                            if($single_task->task_type == 0){
                              $icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
                            }
                            else if($single_task->task_type == 1){
                              $icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
                            }
                            else{
                              $icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
                            }
                        ?>
                          <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>" data-project="<?php echo $single_task->project_id; ?>"><?php echo $icon.$single_task->task_name?></a></li>
                        <?php
                          }
                        }
                        ?>
                    </ul>
                    <input type="hidden" name="idtask" id="idtask" value="">
                  </div>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                  </div>
                </div>
            </div>
            <div class="form-group start_group margintop20" style="margin-top:20px">
              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-title"><label style="margin-top:5px">Priority:</label></div>
                    </div>
                    <div class="col-md-7" style="padding-top: 5px;">
                      <?php echo user_rating(); ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="row" style="">
                    <div class="col-md-3">
                        <label style="margin-top:5px">Creation Date:</label>
                      </div>
                      <div class="col-md-9">
                        <label class="input-group datepicker-only-init_date_received">
                            <input type="text" class="form-control created_date" placeholder="Select Creation Date" name="created_date" style="font-weight: 500;" required />
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </label>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group start_group margintop20" style="margin-top:15px !important">
                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                  </div>
                  <div class="col-lg-10">
                    <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
                  </div>
                </div>
            </div>
            <div class="form-group start_group task_specifics_add margintop20">
                <div class="row">
                  <div class="col-lg-7">
                    <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                  </div>
                  <div class="col-lg-5">
                    <div class="form-group date_group" style="float: right;">
                      <div class="form-title" style="font-weight:600;margin-left:-10px">
                        <input type='checkbox' name="2_bill_task" class="2_bill_task" id="2_bill_task0" value="1"/> 
                        <label for="2_bill_task0" style="color:green">This task is a 2Bill Task!</label>
                        <img src="<?php echo URL::to('public/assets/images/2bill.png')?>" style="width:40px;margin-left:8px">
                      </div>
                  </div>
                  </div>
                  <div class="col-lg-12">
                    <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
                  </div>
                </div>
            </div>
            <div class="form-group date_group margintop20">
                <div class="col-md-1" style="padding:0px">
                  <label style="margin-top:5px">DueDate:</label>
                </div>
                <div class="col-md-3">
                  <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
                </div>
                <div class="col-md-1" style="padding:0px">
                  <label style="margin-top:5px">Project:</label>
                </div>
                <div class="col-md-3">
                    <select name="select_project" class="form-control select_project">
                      <option value="">Select Project</option>
                      <?php
                          $projects = DB::table('projects')->get();
                          if(($projects)){
                            foreach($projects as $project){
                              ?>
                              <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_name; ?></option>
                              <?php
                            }
                          }
                      ?>
                    </select>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <label style="margin-top:5px">Project Time:</label>
                </div>
                <div class="col-md-1" style="padding:0px">
                    <select name="project_hours" class="form-control project_hours">
                      <option value="">HH</option>
                      <?php
                      for($i = 0; $i <= 23; $i++)
                      {
                        if($i < 10) { $i = '0'.$i; }
                        ?>
                        <option value="{{$i}}">{{$i}}</option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                <div class="col-md-1" style="padding:0px">
                    <select name="project_mins" class="form-control project_mins">
                      <option value="">MM</option>
                      <?php
                      for($i = 0; $i <= 59; $i++)
                      {
                         if($i < 10) { $i = '0'.$i; }
                        ?>
                        <option value="{{$i}}">{{$i}}</option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
            </div>
            <div class="form-group start_group retreived_files_div margintop20">
            </div>
            <div class="row margintop20" style="padding-top: 10px; clear: both;">
              <div class="col-lg-8">
                <div class="form-group start_group">
                  <label>Task Files: </label>
                  <a href="javascript:" class="fa fa-plus fa-plus-task" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
                  <a href="javascript:" class="fa fa-pencil-square fanotepadtask" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
                  <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
                  <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
                  <div class="img_div img_div_task" style="z-index:9999999; min-height: 275px">
                    <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                    @csrf
</form>
                    <div class="image_div_attachments">
                      <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                      <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload5" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="">
                      @csrf
</form>              
                    </div>
                   </div>
                   <div class="notepad_div_notes_task" style="z-index:9999; position:absolute;display:none">
                      <textarea name="notepad_contents_task" class="form-control notepad_contents_task" placeholder="Enter Contents"></textarea>
                      <input type="button" name="notepad_submit_task" class="btn btn-sm btn-primary notepad_submit_task" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files_notepad_add"></spam>
                  </div>
                </div>
                <p id="attachments_text_task" style="display:none; font-weight: bold;">Files Attached:</p>
                <div id="add_attachments_div_task">
                </div>
                <div id="add_notepad_attachments_div_task">
                </div>
                <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
                <div id="add_infiles_attachments_div">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group date_group">
                    <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="auto_close_task" class="auto_close_task" id="auto_close_task0" value="1"/> <label for="auto_close_task0">This task is an Auto Close Task</label></div>
                </div>
                <div class="form-group date_group">
                    <div class="form-title" style="font-weight:600;margin-left:-10px;float:none"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                    <div class="accept_recurring_div">
                      <p>This Task is repeated:</p>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                        <label for="recurring_checkbox1">Monthly</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                        <label for="recurring_checkbox2">Weekly</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                        <label for="recurring_checkbox3">Daily</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                        <label for="recurring_checkbox4">Specific Number of Days</label>
                        <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">     
            <input type="hidden" name="action_type" id="action_type" value="">
            <input type="hidden" name="hidden_specific_type" id="hidden_specific_type" value="">
            <input type="hidden" name="hidden_attachment_type" id="hidden_attachment_type" value="">
            <input type="hidden" name="hidden_task_id_copy_task" class="hidden_task_id_copy_task" value="">
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    @csrf
</form>
  </div>
</div> -->
<div class="modal fade" id="show_email_sent_popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Email Sent Options</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_a" value="a"><label for="option_a" class="option_label">Fix an Error Created In House
</label>
            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_b" value="b"><label for="option_b" class="option_label">Fix an Error by Client or Implement a client Requested Change</label>
            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_c" value="c"><label for="option_c" class="option_label">Combined In House and Client Prompted adjustments</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="btn btn-primary common_black_button" id="hidden_task_id_val" value="">
        <input type="button" class="btn btn-primary common_black_button" id="email_option_submit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal_bi_payroll" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Alert</h4>
      </div>
      <div class="modal-body">
        <label>Bi-Period Payroll Status will be disabled</label>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="yes_bi_payroll" value="Yes">
        <input type="button" class="btn btn-primary common_black_button" id="no_bi_payroll" value="No">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Alert</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Company/Task Name?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="company_update" class="company_update" id="company_yes" value="1"><label for="company_yes">Yes</label>
            <input type="radio" name="company_update" class="company_update" id="company_no" value="0"><label for="company_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Employer Number?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="emp_update" class="emp_update" id="emp_yes" value="1"><label for="emp_yes">Yes</label>
            <input type="radio" name="emp_update" class="emp_update" id="emp_no" value="0"><label for="emp_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="email_update" class="email_update" id="email_yes" value="1"><label for="email_yes">Yes</label>
            <input type="radio" name="email_update" class="email_update" id="email_no" value="0"><label for="email_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_yes" value="1"><label for="salutation_yes">Yes</label>
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_no" value="0"><label for="salutation_no">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal_edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Alert</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Company/Task Name?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="company_update_edit" class="company_update_edit" id="company_yes_edit" value="1"><label for="company_yes_edit">Yes</label>
            <input type="radio" name="company_update_edit" class="company_update_edit" id="company_no_edit" value="0"><label for="company_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Employer Number?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="emp_update_edit" class="emp_update_edit" id="emp_yes_edit" value="1"><label for="emp_yes_edit">Yes</label>
            <input type="radio" name="emp_update_edit" class="emp_update_edit" id="emp_no_edit" value="0"><label for="emp_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="email_update_edit" class="email_update_edit" id="email_yes_edit" value="1"><label for="email_yes_edit">Yes</label>
            <input type="radio" name="email_update_edit" class="email_update_edit" id="email_no_edit" value="0"><label for="email_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_yes_edit" value="1"><label for="salutation_yes_edit">Yes</label>
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_no_edit" value="0"><label for="salutation_no_edit">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit_edit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade notify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Payroll Detail Request Manager</h4>
      </div>
      <div class="modal-body" style="clear: both;">
        <input type="checkbox" name="select_all_request" id="select_all_request" class="select_all_request" value=""><label for="select_all_request">Select All Clients</label>
        <a href="javascript:" class="common_black_button show_os_payroll" id="show_os_payroll" style="float:right">Show OS Payroll Only</a>
        <div class="col-md-12 notify_place_div" style="height:600px;max-height:600px;overflow-y: scroll;margin-top: 13px">
        </div>
      </div>
      <div class="modal-footer" style="clear: both;">
        <input type="hidden" name="hidden_request_taskids" id="hidden_request_taskids" value="">
        <input type="hidden" id="notify_type" value="">
        <input type="button" class="btn btn-primary common_black_button" id="email_notify" value="Send Payroll Request">
      </div>
    </div>
  </div>
</div>
<div class="modal fade createnewtask" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
    <form action="<?php echo URL::to('user/add_new_task'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Task</h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white">
            <tr>
              <td>
                <label>Choose Company Name</label>
                <input type="text" class="form-control common_input client_search_class" required placeholder="Choose Company Name" style="width:90%; display:inline;">
                <img class="active_client_list_pm1" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer; margin-top:5px;margin-left:6px;" title="Add to active client list" />
                <input type="hidden" id="client_search" name="clientid" />
              </td>
              <td>
                <input type="hidden" value="<?php echo $weekid->year ?>" name="year">
                <input type="hidden" value="<?php echo $weekid->week ?>" name="week">
                <input type="hidden" value="<?php echo $weekid->week_id ?>" name="weekid">
                <label>Enter Task Name</label>
                <input type="input" placeholder="Enter Task Name" class="form-control common_input" name="tastname" id="taskname" required>
              </td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>
                <label>Tax Reg1</label>
                <input type="text" class="form-control common_input tax_reg1class" value="" name="" placeholder="Tax Reg1" readonly>
              </td>
              <td>
                <label>Select Category</label>
                <select class="form-control common_input" name="classified" required>
                    <option value="">Select Category</option>
                    <?php
                    if(($classifiedlist)){
                      foreach ($classifiedlist as $classified) {
                    ?>
                        <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </td>
              <td>
                <label>Employer Number</label>
                <input type="text" name="task_enumber" class="common_input form-control" placeholder="Employer Number" id="task_enumber" required>
              </td>
              <td rowspan="3">
                <table style="margin-top:30px">
                  <tr>
                    <td><input type="radio" name="enterhours" value="0" required> <label>Enter Hours</label></td>
                    <td><input type="radio" name="enterhours" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="holiday" value="0" required> <label>Holiday Pay</label></td>
                    <td><input type="radio" name="holiday" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="process" value="0" required> <label>Process Payroll</label></td>
                    <td><input type="radio" name="process" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="payslips" value="0" required> <label>Upload Payslips</label></td>
                    <td><input type="radio" name="payslips" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="email" value="0" required> <label>Email Payslip</label></td>
                    <td><input type="radio" name="email" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="uploadd" value="0" required> <label>Upload Report</label></td>
                    <td><input type="radio" name="uploadd" value="2" required> <label>N/A </label></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <label>Primary Email</label>
                <input type="text" class="form-control common_input primaryemail_class" name="" value="" placeholder="Primary Email" readonly>
              </td>
              <td>
                <label>Enter Email</label>
                <input type="email" name="task_email" id="task_email_create" class="common_input form-control" placeholder="Enter Email" required>
              </td>
              <td>
                <label>Enter Secondary Email</label>
                <input type="email" name="secondary_email" class="common_input form-control" placeholder="Enter Secondary Email">
              </td>
            </tr>
            <tr>
              <td>
                <label>Firstname</label>
                <input type="text" class="form-control common_input firstname_class" name="" value="" placeholder="Firstname" readonly>
              </td>
              <td> <label>Enter Salutation</label><textarea name="salutation" id="salutation_create" class="common_input form-control" placeholder="Enter Salutation" required></textarea></td>
              <td>
                <label>Enter Network Location</label>
                <input type="text" name="location" class="common_input form-control" placeholder="Enter Network Location" required>
              </td>
            </tr>
            <tr>
              <td colspan="3"> <label>P30 Section : </label></td>
            </tr>
            <tr>
              <td>
                <label>Select Task Level</label>
                <?php $levels = DB::table('p30_tasklevel')->where('status',0)->orderBy('name','desc')->get(); ?>
                <select class="form-control tasklevel_input" name="tasklevel" required>
                    <option value="">Select Task Level</option>
                    <?php
                    if(($levels)){
                      foreach ($levels as $level) {
                    ?>
                        <option value="<?php echo $level->id ?>"><?php echo $level->name; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </td>
              <td>
                <div style="margin-top:28px">
                  <label>Email : </label>
                  <input type="radio" name="email_p30" value="1" required> <label>Yes</label>
                  <input type="radio" name="email_p30" value="0" required> <label>No</label>
                </div>
              </td>
              <td>
                <div style="margin-top:28px">
                  <label>Pay : </label>
                  <input type="radio" name="pay_p30" value="1" required> <label>Yes</label>
                  <input type="radio" name="pay_p30" value="0" required> <label>No</label>
                </div>
              </td>
            </tr>
          </table>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="">
        <input type="hidden" name="hidden_client_emp" id="hidden_client_emp" value="">
        <input type="hidden" name="hidden_client_salutation" id="hidden_client_salutation" value="">
        <input type="submit" class="btn btn-primary common_black_button" value="Create New">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade createschemes" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form action="<?php echo URL::to('user/add_new_task'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_schemes" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create Schemes</h4>
      </div>
      <div class="modal-body">
        <h4>Enter Scheme Name: </h4>
        <input type="text" name="scheme_name" class="form-control scheme_name" placeholder="Enter Scheme Name" value="" maxlength="15">
        <h4>Select Scheme Type: </h4>
        <input type="radio" name="scheme_type" id="scheme_type_open" class="scheme_type" value="0"><label for="scheme_type_open">Open</label>
        <input type="radio" name="scheme_type" id="scheme_type_close" class="scheme_type" value="1"><label for="scheme_type_close">Close</label>
        <p style="text-align: left;margin-top:10px"><input type="button" name="add_scheme_btn" class="common_black_button add_scheme_btn" value="Create Scheme"></p>
      </div>
      <div class="modal-footer">
        <div id="scheme_divbody" class="modal_max_height_400">
          <table class="table own_table_white">
            <thead>
              <th style="text-align:left">#</th>
              <th style="text-align:left">Scheme Name</th>
              <th style="text-align:left">Action</th>
            </thead>
            <tbody id="scheme_tbody">
              <?php
              $schemes = DB::table('pms_schemes')->where('practice_code',Session::get('user_practice_code'))->get();
              if(($schemes))
              {
                $i = 1;
                foreach($schemes as $scheme)
                {
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $scheme->scheme_name; ?></td>
                    <td>
                      <?php
                        if($scheme->status == "1")
                        {
                          echo '<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=0&id='.$scheme->id.'').'" class="fa fa-times-circle change_scheme_status" data-element="1" title="Closed" style="color:red"></a>';
                        }
                        else{
                          echo '<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=1&id='.$scheme->id.'').'" class="fa fa-check-circle-o change_scheme_status" data-element="0" title="Open" style="color:green"></a>';
                        }
                      ?>
                    </td>
                  </tr>
                  <?php
                  $i++;
                }
              }
              else{
                ?>
                <tr>
                  <td colspan="3">No Schemes Found</td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
        </div> 
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade copy_task" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('user/copy_task'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Copy task</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" name="hidden_task_id" id="hidden_task_id" value="">
          <input type="hidden" name="hidden_copy_year" id="hidden_copy_year" value="">
          <input type="hidden" name="hidden_copy_week" id="hidden_copy_week" value="">
          <input type="hidden" name="hidden_copy_month" id="hidden_copy_month" value="">
          <?php
            $years = DB::table('pms_year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->get();
          ?>
          <h5 style="font-weight:800">CHOOSE YEAR : </h5>
          <div class="select_button" style="min-height:48px;float:none">
            <ul>
                <?php
                $current_year = DB::table('pms_year')->orderBy('year_name', 'desc')->first();
                if($current_year) {
                ?>
                  <li><a href="javascript:" class="year_button" data-element="<?php echo $current_year->year_id; ?>"><?php echo $current_year->year_name; ?></a></li>
                <?php
                }
                ?>            
            </ul>
          </div>
          <h5 style="font-weight:800;float:left">CHOOSE TYPE : </h5>
          <select name="select_year_type" class="form-control" id="select_year_type">
            <option value="">Select Type</option>
            <option value="weekly">Weekly Task</option>
            <option value="monthly">Monthly Task</option>
          </select>
          <div class="select_button weekly_select" style="min-height:66px;float:none">
          </div>
          <div class="select_button category_select_copy" style="display:none;min-height:48px;float:none">
            <h5 style="font-weight:800">Choose Category : </h5>
            <select name="category_type_copy" class="form-control" id="category_type_copy" required>
              <option value="">Select Category</option>
              <?php
              if(($classifiedlist)){
                foreach ($classifiedlist as $classified) { ?>
                  <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>
                <?php
                }
              }
              ?>
            </select>
          </div>
      </div>
      <labe>
      <div class="modal-footer">
        <input type="submit" class="common_black_button" value="Submit" style="background: #000; color:#fff">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade edit_task_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
    <form action="<?php echo URL::to('user/edit_task_details'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Task</h4>
      </div>
      <div class="modal-body">
          <table class="table">
            <tr>
              <td>
                <label>Choose Company Name</label>
                <input type="text" class="form-control common_input client_search_class_edit companyclass" required placeholder="Choose Company Name" style="width:90%; display:inline;">
                <img class="active_client_list_pm" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer; margin-top:5px;margin-left:6px;" title="Add to active client list" />
                <input type="hidden" id="client_search_edit" name="clientid" />
              </td>
              <td>
                <input type="hidden" class="hidden_taskname_id" id="hidden_taskname_id" name="hidden_taskname_id" value="">
                <label>Enter Task Name</label>
                <input type="text" name="task_name" id="taskname_edit" class="task_name form-control input-sm" value="" required>
              </td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>
                <label>Tax Reg1</label>
                <input type="text" class="form-control common_input tax_reg1class_edit" value="" name="" placeholder="Tax Reg1" readonly>
              </td>
              <td>
                <label>Select Category</label>
                <select name="task_category" class="task_category form-control input-sm" required>
                  <option value="">Select Category</option>
                  <?php
                  if(($classifiedlist)){
                    foreach ($classifiedlist as $classified) { ?>
                      <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>
                    <?php
                    }
                  }
                  ?>
                </select>
              </td>
              <td>
                <label>Employer Number</label>
                <input type="text" name="enumber" id="enumber_edit" class="enumberclass form-control input-sm" value="" required>
              </td>
              <td rowspan="3">
                <table class="table">
                  <tr>
                    <td><input type="radio" name="enterhours_edit" id="hours_enter" value="0" required> <label>Enter Hours</label></td>
                    <td><input type="radio" name="enterhours_edit" id="hours_na" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="holiday_edit" id="holiday_enter" value="0" required> <label>Holiday Pay</label></td>
                    <td><input type="radio" name="holiday_edit" id="holiday_na" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="process_edit" id="process_enter" value="0" required> <label>Process Payroll</label></td>
                    <td><input type="radio" name="process_edit" id="process_na" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="payslips_edit" id="payslips_enter" value="0" required> <label>Upload Payslips</label></td>
                    <td><input type="radio" name="payslips_edit" id="payslips_na" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="email_edit" id="email_enter" value="0" required> <label>Email Payslip</label></td>
                    <td><input type="radio" name="email_edit" id="email_na" value="2" required> <label>N/A </label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="uploadd_edit" id="report_enter" value="0" required> <label>Upload Report</label></td>
                    <td><input type="radio" name="uploadd_edit" id="report_na" value="2" required> <label>N/A </label></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <label>Primary Email</label>
                <input type="text" class="form-control common_input primaryemail_class_edit" name="" value="" placeholder="Primary Email" readonly>
              </td>
              <td>
                <label>Enter Email</label>
                <input type="text" name="task_email_edit" id="task_email_edit" class="task_email_edit form-control input-sm" value="" required>
              </td>
              <td>
                <label>Enter Secondary Email</label>
                <input type="text" name="secondary_email_edit" class="secondary_email_edit form-control input-sm" value="">
              </td>
            </tr>
            <tr>
              <td>
                <label>Firstname</label>
                <input type="text" class="form-control common_input firstname_class_edit" name="" value="" placeholder="Firstname" readonly>
              </td>
              <td> <label>Enter Salutation</label><textarea name="salutation_edit" id="salutation_edit" class="salutation_edit common_input form-control" placeholder="Enter Salutation" required></textarea></td>
              <td>
                <label>Enter Network Location</label>
                <input type="text" name="task_network" class="task_network form-control input-sm" value="" required>
              </td>
            </tr>
            <tr>
              <td colspan="3"> <label>P30 Section : </label></td>
            </tr>
            <tr>
              <td>
                <label>Select Task Level</label>
                <?php $levels = DB::table('p30_tasklevel')->where('status',0)->orderBy('name','desc')->get(); ?>
                <select class="form-control tasklevel_edit" name="tasklevel_edit" required>
                    <option value="">Select Task Level</option>
                    <?php
                    if(($levels)){
                      foreach ($levels as $level) {
                    ?>
                        <option value="<?php echo $level->id ?>"><?php echo $level->name; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
              </td>
              <td>
                <div style="margin-top:28px">
                  <label>Email : </label>
                  <input type="radio" name="email_p30_edit" id="p30_email_yes" value="1" required> <label>Yes</label>
                  <input type="radio" name="email_p30_edit" id="p30_email_no" value="0" required> <label>No</label>
                </div>
              </td>
              <td>
                <div style="margin-top:28px">
                  <label>Pay : </label>
                  <input type="radio" name="pay_p30_edit" id="p30_pay_yes" value="1" required> <label>Yes</label>
                  <input type="radio" name="pay_p30_edit" id="p30_pay_no" value="0" required> <label>No</label>
                </div>
              </td>
            </tr>
          </table>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_client_id_edit" id="hidden_client_id_edit" value="">
        <input type="hidden" name="hidden_client_emp_edit" id="hidden_client_emp_edit" value="">
        <input type="hidden" name="hidden_client_salutation_edit" id="hidden_client_salutation_edit" value="">
        <input type="submit" class="common_black_button" value="Submit" style="background: #000; color:#fff">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="email_unsent_form" action="<?php echo URL::to('user/email_unsent_files'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Unsent Files</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="select_user" id="select_user" class="form-control" required>
                <?php echo $unamelist; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_unsent" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="" required>
            </div>
          </div>
          <?php
          $pms_admin_details = DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->first();
          if($pms_admin_details->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($pms_admin_details->email_header_url.'/'.$pms_admin_details->email_header_filename);
          }
          ?>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label style="margin-top:7px">Header Image:</label>
            </div>
            <div class="col-md-9">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachments:</label>
            </div>
            <div class="col-md-12" id="email_attachments">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="email_sent_option" id="email_sent_option" value="0">
        <input type="hidden" name="email_task_id_value" id="email_task_id_value" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_files_btn" value="Email Unsent Files">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade resendemailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="resend_email_unsent_form" action="<?php echo URL::to('user/email_unsent_files'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Resend Email</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="select_user" id="select_userresend" class="form-control" required>
                <?php echo $unamelist; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user" id="to_userresend" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_unsent" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_resend" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top:7px">Header Image:</label>
            </div>
            <div class="col-md-9">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor_9">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment:</label>
            </div>
            <div class="col-md-12" id="email_attachmentsresend">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="email_task_id_value" id="resend_email_task_id_value" value="">
        <input type="submit" class="btn btn-primary common_black_button resend_email_unsent_files_btn" value="Send">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade" id="email_report_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form action="<?php echo URL::to('user/email_report_send?year='.$yearname->year_name.'&week='.$weekid->week.'&type=week'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Task Report</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="select_user_report" class="form-control" required>
                <?php echo $unamelist; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user_report" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_report" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_report" class="form-control input-sm subject_report" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top:7px">Header Image:</label>
            </div>
            <div class="col-md-9">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_report" class="form-control" style="height:150px"></textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachments:</label>
            </div>
            <div class="col-md-12">
              <img src="<?php echo URL::to('public/assets/images/pdf.png'); ?>" width="30px">
                <label style="margin-top:10px" id="task_report_label">Task_Report_For_Year-<?php echo $yearname->year_name; ?>_Week-<?php echo $weekid->week; ?>.pdf</label>
                <label style="margin-top:10px" id="notify_report_label">Notify_Report_For_Year-<?php echo $yearname->year_name; ?>_Week-<?php echo $weekid->week; ?>.pdf</label>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="hidden_report_type" name="hidden_report_type" value="">
        <input type="submit" class="btn btn-primary common_black_button" value="Email Task Report">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade model_notify" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Notify</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <input type="checkbox" name="" id="notity_selectall"><label for="notity_selectall">Select All</label>
              <table id="dtBasicExample" class="table">
                <thead>
                  <tr>
                    <th scope="col" style="text-align: left">S.No</th>
                    <th scope="col" style="text-align: left">Name</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
                 $i = 1;
                if(($userlist)){
                  foreach ($userlist as $user) {
                    ?>
                    <tr>
                      <td scope="row"><?php echo $i; ?> <input type="checkbox" class="notify_id_class" name="username" id="user_<?php echo $user->user_id?>" data-element="<?php echo $user->email; ?>" data-value="<?php echo $user->user_id; ?>"><label>&nbsp;</label></td>
                      <td><label for="user_<?php echo $user->user_id?>"><?php echo $user->lastname.' '.$user->firstname; ?></label></td>
                    </tr>
                    <?php
                  $i++;
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="notify_task_id" value="" name="">
          <input type="button" class="btn btn-primary common_black_button notify_all_clients_tasks" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade model_don_not_complete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Disable:<span class="dont_task_name"></span></h4>
      </div>
      <div class="modal-body">
        <div style="font-size: 15px; font-weight: bold; width: 100%; height: auto; float: left; margin-bottom: 10px;">Do you want to Disable this task for:</div>
        <input type="radio" name="dontvale" value="1" class="dontvale_class" id="donot_period" ><label for="donot_period">This Period Only</label><br/>
        <input type="radio" name="dontvale" value="2" class="dontvale_class" id="donot_future_period" ><label for="donot_future_period">Until Future Notice</label>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="dontvale" value="">
        <input type="hidden" class="donot_id_class" name="task_id">
        <input type="submit" class="btn btn-primary common_black_button donot_submit_new" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
<div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 new_main_title">
                <?php
    if($weekid->week_closed == '0000-00-00 00:00:00')
    {
      $now = time();
      $your_date = strtotime($weekid->updatetime);
      $datediff = $now - $your_date;
    }
    else{
      $now = strtotime($weekid->week_closed);
      $your_date = strtotime($weekid->updatetime);
      $datediff = $now - $your_date;
    }
    $days = floor($datediff / (60 * 60 * 24));
    if($days < 0)
    {
      $days = 0;
    }
  ?>
    Task of <?php echo $yearname->year_name ?> &nbsp;&nbsp;&nbsp;&nbsp; Week : Week <?php echo $weekid->week ?> &nbsp;&nbsp;&nbsp;&nbsp; No of days : <?php echo $days; ?>       
            </h4>
    </div>
  <div class="row payroll_menu_section">
    <div class="col-lg-3">
      <?php $check_incomplete = DB::table('user_login')->where('userid',1)->first(); if($check_incomplete->week_incomplete == 1) { $inc_checked = 'checked'; } else { $inc_checked = ''; } ?>
      <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked; ?>><label for="show_incomplete">Show Incomplete Only</label> </div>
    <div class="col-lg-7 padding_00" style="float:left">
<div class="select_button">
      <ul style="margin-right: 1%;float:right">
        <?php
          $this_week = $weekid->week_id;
          $prev_week = DB::table('pms_week')->where('week_id','<',$this_week)->where('year',$weekid->year)->orderBy('week_id','desc')->first();
          $next_week = DB::table('pms_week')->where('week_id','>',$this_week)->where('year',$weekid->year)->orderBy('week_id','asc')->first();
          $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
          $current_week = DB::table('pms_week')->where('year',$year->year_id)->orderBy('week_id','desc')->first();
          if($prev_week)
          {
            $prev = '<li><a href="'.URL::to('user/select_week/'.base64_encode($prev_week->week_id).'').'">Previous</a></li>';
          }
          else{
            $prev = '';
          }
          if($current_week)
          {
            $curr = '<li><a href="'.URL::to('user/select_week/'.base64_encode($current_week->week_id).'').'">Current</a></li>';
          }
          else{
            $curr = '';
          }
          if($next_week)
          {
            $next = '<li><a href="'.URL::to('user/select_week/'.base64_encode($next_week->week_id).'').'">Next</a></li>';
          }
          else{
            $next = '';
          }
          ?>
         <?php echo $prev; ?>
         <?php echo $curr; ?>
         <?php echo $next; ?>
        <li><a href="<?php echo URL::to('user/close_create_new_week/'.$weekid->week_id); ?>" id="close_create_new_week">Close and Create New Week</a></li>
        <li><a href="javascript:" id="email_report_button">Email Task Report</a></li>
        <li class="dropdown_download" style="position: relative;"><a href="javascript:" id="download_reports" class="dropdown_download">DOWNLOAD <i class="fa fa-caret-down dropdown_download"></i></a>
          <div class="download_div" style="display:none">
          <a href="javascript:" class="close_xmark">X</a>
          <div class="row">
              <a href="javascript:" class="download_radio" id="all_tasks">All Tasks</a>
              <a href="javascript:" class="download_radio" id="task_completed">Tasks Completed</a>
              <a href="javascript:" class="download_radio" id="task_incomplete">Tasks InComplete</a>
          </div>
        </div>
        </li>
        <li class="dropdown_notify" style="position: relative;"><a href="javascript:" id="notify_reports" class="dropdown_notify">Request Payroll <i class="fa fa-caret-down dropdown_notify"></i></a>
          <div class="notify_div" style="display:none">
          <a href="javascript:" class="close_xmark">X</a>
          <div class="row">
              <a href="javascript:" class="notify_radio" id="task_standard">Standard</a>
              <a href="javascript:" class="notify_radio" id="task_enhanced">Enhanced</a>
              <a href="javascript:" class="notify_radio" id="task_complex">Complex</a>
          </div>
        </div>
        </li>
        <li><a href="javascript:"  data-toggle="modal" data-target=".createnewtask">Create a Task</a></li>
        <li><a href="javascript:" class="open_schemes">Schemes</a></li>
      </ul>
</div>
    </div>
  </div>
<div style="width:100%;float:left; margin-top: 120px; margin-bottom: -154px;">
<?php
if(Session::has('message')) { ?>
    <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
<?php }
if(Session::has('error')) { ?>
    <p class="alert alert-danger"><?php echo Session::get('error'); ?></p>
<?php }
?>
</div>
    <?php if(($resultlist_standard)){ ?>
    <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:130px">
      <h3> Standard Task</h3>
      <table class="table_bg table-fixed-header" style="width: 2000px; margin: 0px auto; background: #fff">
          <thead class="header">
            <tr>
                <th width="80px">S.No <i class="fa fa-sort sno_sort_std" aria-hidden="true"></th>
                <th width="80px"></th>
                <th width="250px">Task Name <i class="fa fa-sort task_sort_std" aria-hidden="true"></th>
                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>
                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>
                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>
                <th width="200px">Date <i class="fa fa-sort date_sort_std" aria-hidden="true"></th>
                <th width="250px">Username <i class="fa fa-sort user_sort_std" aria-hidden="true"></th>
                <th width="350px">Email <i class="fa fa-sort email_sort_std" aria-hidden="true"></th>                
                <th width="400px">Network Location</th>
                <th width="400px">Email Unsent Files</th>
                <th width="200px" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody id="task_body_std">
              <?php 
               if(($resultlist_standard)){
                  $i=1;
                  foreach ($resultlist_standard as $result) {
                    if($i < 10)
                  {
                    $i = '0'.$i;
                  }
              ?>
              <tr class="task_tr_std" id="taskidtr_<?php echo $result->task_id; ?>">
              <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>
              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; } elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="font-weight:600"'; } ?>
                  <td class="sno_sort_std_val"><?php echo $i;?></td>
                  <td>
                    <?php
                      if($result->task_started == 0){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->task_started == 1){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                    ?>
                    <input type="button" class="common_black_button secret_button" value="&nbsp;" style="width:60px;background: #fff !important;margin-top:63px" data-element="<?php echo $result->task_id; ?>">
                  </td>
                  <td class="task_sort_std_val" align="left">
                  <label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>
                  <br/> <input type="checkbox" name="bi_payroll" class="bi_payroll" id="bi_payroll_<?php echo $result->task_id; ?>" data-element="<?php echo $result->task_id; ?>" <?php if($result->bi_payroll == 1) { echo 'checked'; } ?> value=""><label for="bi_payroll_<?php echo $result->task_id; ?>">Bi-Period Payroll</label>
                  <br/><?php echo $result->client_id; ?> 
                  <img class="active_client_list_pms" data-iden="<?php echo $result->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /><br/><br/>
                  <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>
                  <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>
                  <br/>
                  <?php if($result->task_week > 96) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));
                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));
                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  <br/>
                  <i class="starfa starzero start_rating start_red fa <?php if($result->rating >= 0) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="0" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starone start_rating start_lred fa <?php if($result->rating >= 1) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="1" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa startwo start_rating start_orange fa <?php if($result->rating >= 2) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="2" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starthree start_rating start_brown fa <?php if($result->rating >= 3) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="3" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starfour start_rating start_yellow fa <?php if($result->rating == 4) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="4" data-task="<?php echo $result->task_id; ?>"></i>
                  </td>
                  <td align="center" class="special_td" style="width:90px;">
                    <?php
                      if($result->enterhours == 0){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->enterhours == 1){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <br/>
                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>
                      <a href="javascript:"  class="create_task_manager" style="font-weight: bold;" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>Create Task</a>&nbsp; &nbsp;
                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
                      <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>
                      <?php
                      if($result->same_as_last == 1) { $prev_checked = "checked"; $prev_text = 'color:blue;text-decoration:underline;font-weight:600'; } 
                      else { $prev_checked = ''; $prev_text = ''; }
                      ?>
                      <input type="checkbox" name="same_as_last" class="same_as_last" data-element="<?php echo $result->task_id; ?>" id="same_as_last_<?php echo $result->task_id; ?>" <?php echo $prev_checked; ?>><label class="same_as_last_label" for="same_as_last_<?php echo $result->task_id; ?>" style="<?php echo $prev_text; ?>">Same as last week</label>
                      <div class="files_received_div">
                        <?php
                        $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();
                        if(($attachments))
                        {
                          echo '<h5 style="color:#000; font-weight:600">Files Received : <i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px;" aria-hidden="true" title="Delete All Attachments"></i></h5>';
                          echo '<div class="scroll_attachment_div">';
                              foreach($attachments as $attachment)
                              {
                                  echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                              }
                          echo '</div>';
                        }
                        ?>
                      </div> 
                            <div class="img_div">
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                            </div>
                            <div class="notepad_div">
                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                                <input type="hidden" name="hidden_id" class="notepad_hidden_task_id" value="<?php echo $result->task_id ?>">
                                <input type="button" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                <spam class="error_files_notepad"></spam>
                              @csrf
</form>
                            </div>
                    </div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->holiday == 0){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->holiday == 1){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <br/>
                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->process == 0){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->process == 1){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->payslips == 0){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->payslips == 1){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->email == 0){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->email == 1){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->upload == 0){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->upload == 1){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>
                  </td>
                  <?php 
                    if($result->task_status == 1)
                    {
                      $date = date('d-M-Y', strtotime($result->updatetime));
                      $time = date('H:i', strtotime($result->updatetime));
                    }
                    else{
                      $date = 'DD-MMM-YYYY';
                      $time = 'HH:MM';
                    }
                  ?>
                  <td class="date_sort_cmp_val" align="center">
                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                  </td>
                  <td class="user_sort_std_val" align="center">
                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>
                        <?php
                            $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                            $uname = '<option value="">Select Username</option>';
                            if(($userlist)){
                              foreach ($userlist as $singleuser) {
                                if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                                if($uname == '')
                                {
    $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                                else{
    $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                              }
                            }
                            echo $uname;
                        ?>
                    </select>
                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>
                    <div class="row" style="width:250%;margin-top:15px">
                      <div class="col-md-3">
                        <label style="margin-top: 6px;">Scheme: </label>
                      </div>
                      <div class="col-md-9">
                        <select name="select_scheme" class="form-control select_scheme <?php if($weekid->week_closed == '0000-00-00 00:00:00') { echo ''; } else { echo 'disabled_scheme'; } ?>" data-element="<?php echo $result->task_id; ?>" id="select_scheme_<?php echo $result->task_id; ?>" style="width:91%">
                          <option value="0"></option>
                          <?php
                          if(($schemes))
                          {
                            foreach($schemes as $scheme)
                            {
                              if($scheme->status == 0)
                              {
                                if($result->scheme_id == $scheme->id) { $sch_selected = 'selected'; } else { $sch_selected = ''; }
                                echo '<option value="'.$scheme->id.'" '.$sch_selected.'>'.$scheme->scheme_name.'</option>';
                              }
                              else{
                                if($result->scheme_id == $scheme->id) {
                                  echo '<option value="'.$scheme->id.'" selected>'.$scheme->scheme_name.'</option>';
                                }
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </td>
                  <td class="email_sort_std_val" align="center">
                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Email" disabled>
                  </td>                  
                  <td align="center" class="file_received_div" style="text-align: left">
                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>
                      <div class="file_received_div_right">
                      <?php
                      $bi_payroll_next_status = 0;
                      $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();
                      if(($attachments))
                      {
                        if($result->bi_payroll == 1)
                        {
                          $bi_payroll_next_status = 1;
                        }
                          echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:-18px;margin-left: 21px;" aria-hidden="true" title="Delete All Attachments"></i>';
                          echo '<h5 style="color:#000; font-weight:600; position:absolute;">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)
                            {
                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                            }
                          echo '</div>';
                      }
                      ?>
                      </div>
                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" oninput="keypressonlynumber(this)" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" oninput="keypressonlynumber(this)" disabled>
                          <?php } ?>
                          <div class="img_div">
                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone_attach" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <div class="disclose_div">
                            <div class="disclose_sub_1">
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Liability on Client Email</label>
                        </div>
                         <div class="disclose_sub_2">
                          <?php
                          if($result->distribute_email == 1) { $check_email = 'checked'; } else { $check_email = ''; } ?>
                          <input type="checkbox" name="distribute_email" id="distribute_email<?php echo $result->task_id; ?>" class="distribute_email" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_email.' '.$disabled; ?>><label class="dist_email_label" for="distribute_email<?php echo $result->task_id; ?>">Distribute Email by Link</label>
                        </div>
                        </div>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="fa fa-envelope email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('public/assets/email_resend_icon.png')?>" class="resendemail_unsent" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">
                    </a>
                    <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('pms_task_email_sent')->where('task_id',$result->task_id)->where('options','!=','n')->get();
                      $last_date = '';
                      if(($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>
                    <label class="email_unsent_label"><?php echo $last_date; ?></label>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                     <div style="height: auto; float: right; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>
                    <?php $displaycls = ''; if($bi_payroll_next_status == 0){ $displaycls = 'displayhide'; } ?>
                    <br/>
                    <p class="bi_payroll_comlete_div <?php echo $displaycls; ?>">Bi Period Payroll Complete - No Payroll Next Period</p>
                  </td>   
              </tr>
              <?php
                  $i++;
                  }
               }
               else{
                  echo "<tr>
                        <td colspan='15' align='center'>Task not found</td></tr>";
               }
              ?>
          </tbody>
      </table>
      <p></p>
    </div>
    <?php } ?>
       <?php if(($resultlist_enhanced)){ ?>
      <div class="table-responsive" style="max-width: 100%; float: left; ">
       <h3> Enhanced Task</h3>
      <table class="table_bg table-fixed-header_1" style="width: 2000px; margin: 0px auto;  background: #fff">
          <thead class="header">
            <tr>
                <th width="80px">S.No <i class="fa fa-sort sno_sort_enh" aria-hidden="true"></th>
                <th width="80px"></th>
                <th width="250px">Task Name <i class="fa fa-sort task_sort_enh" aria-hidden="true"></th>
                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>
                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>
                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>
                <th width="200px">Date <i class="fa fa-sort date_sort_enh" aria-hidden="true"></th>
                <th width="250px">Username <i class="fa fa-sort user_sort_enh" aria-hidden="true"></th>
                <th width="350px">Email <i class="fa fa-sort email_sort_enh" aria-hidden="true"></th>
                <th width="400px">Network Location</th>
                <th width="400px">Email Unsent Files</th>
                <th width="200px" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody id="task_body_enh">
              <?php 
               if(($resultlist_enhanced)){
                  $i=1;
                  foreach ($resultlist_enhanced as $result) {
                    if($i < 10)
                  {
                    $i = '0'.$i;
                  }
              ?>
              <tr class="task_tr_enh" id="taskidtr_<?php echo $result->task_id; ?>">
                <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>
              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; }  elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="font-weight:600"'; }  ?>
                  <td class="sno_sort_enh_val"><?php echo $i;?></td>
                  <td>
                    <?php
                      if($result->task_started == 0){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->task_started == 1){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                    ?>
                    <input type="button" class="common_black_button secret_button" value="&nbsp;" style="width:60px;background: #fff !important;margin-top:63px" data-element="<?php echo $result->task_id; ?>">
                  </td>
                  <td class="task_sort_enh_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>
                  <br/> <input type="checkbox" name="bi_payroll" class="bi_payroll" id="bi_payroll_<?php echo $result->task_id; ?>" data-element="<?php echo $result->task_id; ?>" <?php if($result->bi_payroll == 1) { echo 'checked'; } ?> value=""><label for="bi_payroll_<?php echo $result->task_id; ?>">Bi-Period Payroll</label>
                  <br/><?php echo $result->client_id; ?>
                  <img class="active_client_list_pms" data-iden="<?php echo $result->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /><br/><br/>
                  <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>
                  <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>
                  <br/>
                  <?php if($result->task_week > 96) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));
                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));
                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  <br/>
                  <i class="starfa starzero start_rating start_red fa <?php if($result->rating >= 0) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="0" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starone start_rating start_lred fa <?php if($result->rating >= 1) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="1" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa startwo start_rating start_orange fa <?php if($result->rating >= 2) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="2" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starthree start_rating start_brown fa <?php if($result->rating >= 3) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="3" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starfour start_rating start_yellow fa <?php if($result->rating == 4) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="4" data-task="<?php echo $result->task_id; ?>"></i>
                  </td>
                  <td align="center" class="special_td" style="width:90px;">
                    <?php
                      if($result->enterhours == 0){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->enterhours == 1){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>
                      <a href="javascript:"  class="create_task_manager" style="font-weight: bold;" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>Create Task</a>&nbsp; &nbsp;
                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
                      <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>
                      <?php
                      if($result->same_as_last == 1) { $prev_checked = "checked"; $prev_text = 'color:blue;text-decoration:underline;font-weight:600'; } 
                      else { $prev_checked = ''; $prev_text = ''; }
                      ?>
                      <input type="checkbox" name="same_as_last" class="same_as_last" data-element="<?php echo $result->task_id; ?>" id="same_as_last_<?php echo $result->task_id; ?>" <?php echo $prev_checked; ?>><label class="same_as_last_label" for="same_as_last_<?php echo $result->task_id; ?>" style="<?php echo $prev_text; ?>">Same as last week</label>
                      <div class="files_received_div">
                        <?php
                        $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();
                        if(($attachments))
                        {
                          echo '';
                          echo '<h5 style="color:#000; font-weight:600">Files Received : <i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px;" aria-hidden="true" title="Delete All Attachments"></i></h5>';
                          echo '<div class="scroll_attachment_div">';
                              foreach($attachments as $attachment)
                              {
                                  echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                              }
                          echo '</div>';
                        }
                        ?>
                      </div> 
                          <div class="img_div">
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                            </div>
                            <div class="notepad_div">
                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                                <input type="hidden" name="hidden_id" class="notepad_hidden_task_id" value="<?php echo $result->task_id ?>">
                                <input type="button" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                <spam class="error_files_notepad"></spam>
                              @csrf
</form>
                            </div>
                    </div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->holiday == 0){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->holiday == 1){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->process == 0){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->process == 1){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->payslips == 0){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->payslips == 1){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->email == 0){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->email == 1){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->upload == 0){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->upload == 1){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>
                  </td>
                  <?php 
                    if($result->task_status == 1)
                    {
                      $date = date('d-M-Y', strtotime($result->updatetime));
                      $time = date('H:i', strtotime($result->updatetime));
                    }
                    else{
                      $date = 'DD-MMM-YYYY';
                      $time = 'HH:MM';
                    }
                  ?>
                  <td class="date_sort_cmp_val" align="center">
                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                  </td>
                  <td class="user_sort_enh_val" align="center">
                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>
                        <?php
                            $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                            $uname = '<option value="">Select Username</option>';
                            if(($userlist)){
                              foreach ($userlist as $singleuser) {
                                if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                                if($uname == '')
                                {
    $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                                else{
    $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                              }
                            }
                            echo $uname;
                        ?>
                    </select>
                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>
                    <div class="row" style="width:250%;margin-top:15px">
                      <div class="col-md-3">
                        <label style="margin-top: 6px;">Scheme: </label>
                      </div>
                      <div class="col-md-9">
                        <select name="select_scheme" class="form-control select_scheme <?php if($weekid->week_closed == '0000-00-00 00:00:00') { echo ''; } else { echo 'disabled_scheme'; } ?>" data-element="<?php echo $result->task_id; ?>" id="select_scheme_<?php echo $result->task_id; ?>" style="width:91%">
                          <option value="0"></option>
                          <?php
                          if(($schemes))
                          {
                            foreach($schemes as $scheme)
                            {
                              if($scheme->status == 0)
                              {
                                if($result->scheme_id == $scheme->id) { $sch_selected = 'selected'; } else { $sch_selected = ''; }
                                echo '<option value="'.$scheme->id.'" '.$sch_selected.'>'.$scheme->scheme_name.'</option>';
                              }
                              else{
                                if($result->scheme_id == $scheme->id) {
                                  echo '<option value="'.$scheme->id.'" selected>'.$scheme->scheme_name.'</option>';
                                }
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </td>
                  <td class="email_sort_enh_val" align="center">
                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>"  placeholder="Enter Email" disabled>
                  </td>
                  <td align="center" class="file_received_div" style="text-align: left">
                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>
                      <div class="file_received_div_right">
                      <?php
                      $bi_payroll_next_status = 0;
                      $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();
                      if(($attachments))
                      {
                        if($result->bi_payroll == 1)
                        {
                          $bi_payroll_next_status = 1;
                        }
                          echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:-18px;margin-left: 21px;" aria-hidden="true" title="Delete All Attachments"></i>';
                          echo '<h5 style="color:#000; font-weight:600; position:absolute;">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)
                            {
                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                            }
                        echo '</div>';
                      }
                      ?>
                    </div>
                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" oninput="keypressonlynumber(this)" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" oninput="keypressonlynumber(this)" disabled>
                          <?php } ?>
                          <div class="img_div">
                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone_attach" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <div class="disclose_div">
                             <div class="disclose_sub_1">
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Liability on Client Email</label>
                        </div>
                         <div class="disclose_sub_2">
                          <?php
                          if($result->distribute_email == 1) { $check_email = 'checked'; } else { $check_email = ''; } ?>
                          <input type="checkbox" name="distribute_email" id="distribute_email<?php echo $result->task_id; ?>" class="distribute_email" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_email.' '.$disabled; ?>><label class="dist_email_label" for="distribute_email<?php echo $result->task_id; ?>">Distribute Email by Link</label>
                        </div>
                        </div>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="fa fa-envelope email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('public/assets/email_resend_icon.png')?>" class="resendemail_unsent" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">  
                    </a>
                      <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('pms_task_email_sent')->where('task_id',$result->task_id)->where('options','!=','n')->get();
                      $last_date = '';
                      if(($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>
                    <label class="email_unsent_label"><?php echo $last_date; ?></label>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                      <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                     <div style="height: auto; float: right; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>
                    <?php $displaycls = ''; if($bi_payroll_next_status == 0){ $displaycls = 'displayhide'; } ?>
                    <br/>
                    <p class="bi_payroll_comlete_div <?php echo $displaycls; ?>">Bi Period Payroll Complete - No Payroll Next Period</p>
                  </td>            
              </tr>
              <?php
                  $i++;
                  }
               }
               else{
                  echo "<tr>
                        <td colspan='15' align='center'>Task not found</td></tr>";
               }
              ?>
          </tbody>
      </table>
      <p></p>
      </div>
    <?php } ?>
       <?php if(($resultlist_complex)){ ?>
       <div class="table-responsive" style="max-width: 100%; float: left;">
       <h3> Complex Task</label>
      <table class="table_bg table-fixed-header_2" style="width: 2000px; margin: 0px auto;  background: #fff">
          <thead class="header">
            <tr>
                <th width="80px">S.No <i class="fa fa-sort sno_sort_cmp" aria-hidden="true"></th>
                <th width="80px"></th>
                <th width="250px">Task Name <i class="fa fa-sort task_sort_cmp" aria-hidden="true"></th>
                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>
                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>
                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>
                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>
                <th width="200px">Date <i class="fa fa-sort date_sort_cmp" aria-hidden="true"></th>
                <th width="250px">Username <i class="fa fa-sort user_sort_cmp" aria-hidden="true"></th>
                <th width="350px">Email <i class="fa fa-sort email_sort_cmp" aria-hidden="true"></th>
                <th width="400px">Network Location</th>
                <th width="400px">Email Unsent Files</th>
                <th width="200px" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody id="task_body_cmp">
              <?php 
               if(($resultlist_complex)){
                  $i=1;
                  foreach ($resultlist_complex as $result) {
                  if($i < 10)
                  {
                    $i = '0'.$i;
                  }
              ?>
              <tr class="task_tr_cmp" id="taskidtr_<?php echo $result->task_id; ?>">
              <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>
              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; }  elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="font-weight:600"'; } ?>
                  <td class="sno_sort_cmp_val"><?php echo $i;?></td>
                  <td>
                    <?php
                      if($result->task_started == 0){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->task_started == 1){
                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                    ?>
                    <input type="button" class="common_black_button secret_button" value="&nbsp;" style="width:60px;background: #fff !important;margin-top:63px" data-element="<?php echo $result->task_id; ?>">
                  </td>
                  <td class="task_sort_cmp_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>
                  <br/> <input type="checkbox" name="bi_payroll" class="bi_payroll" id="bi_payroll_<?php echo $result->task_id; ?>" data-element="<?php echo $result->task_id; ?>" <?php if($result->bi_payroll == 1) { echo 'checked'; } ?> value=""><label for="bi_payroll_<?php echo $result->task_id; ?>">Bi-Period Payroll</label>
                    <br/><?php echo $result->client_id; ?>
                    <img class="active_client_list_pms" data-iden="<?php echo $result->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /><br/><br/>
                    <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>
                  <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>
                  <br/>
                  <?php if($result->task_week > 96) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));
                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));
                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  <br/>
                  <i class="starfa starzero start_rating start_red fa <?php if($result->rating >= 0) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="0" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starone start_rating start_lred fa <?php if($result->rating >= 1) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="1" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa startwo start_rating start_orange fa <?php if($result->rating >= 2) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="2" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starthree start_rating start_brown fa <?php if($result->rating >= 3) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="3" data-task="<?php echo $result->task_id; ?>"></i>
                  <i class="starfa starfour start_rating start_yellow fa <?php if($result->rating == 4) { echo 'fa-star'; } else { echo 'fa-star-o'; } ?>" data-element="4" data-task="<?php echo $result->task_id; ?>"></i>
                  </td>
                  <td align="center" class="special_td" style="width:90px;">
                    <?php
                      if($result->enterhours == 0){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->enterhours == 1){
                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>
                      <a href="javascript:"  class="create_task_manager" style="font-weight: bold;" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>Create Task</a>&nbsp; &nbsp;
                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
                      <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>
                      <?php
                      if($result->same_as_last == 1) { $prev_checked = "checked"; $prev_text = 'color:blue;text-decoration:underline;font-weight:600'; } 
                      else { $prev_checked = ''; $prev_text = ''; }
                      ?>
                      <input type="checkbox" name="same_as_last" class="same_as_last" data-element="<?php echo $result->task_id; ?>" id="same_as_last_<?php echo $result->task_id; ?>" <?php echo $prev_checked; ?>><label class="same_as_last_label" for="same_as_last_<?php echo $result->task_id; ?>" style="<?php echo $prev_text; ?>">Same as last week</label>
                      <div class="files_received_div">
                        <?php
                        $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();
                        if(($attachments))
                        {
                          echo '';
                          echo '<h5 style="color:#000; font-weight:600">Files Received : <i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px;" aria-hidden="true" title="Delete All Attachments"></i></h5>';
                          echo '<div class="scroll_attachment_div">';
                              foreach($attachments as $attachment)
                              {
                                  echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                              }
                          echo '</div>';
                        }
                        ?>
                      </div> 
                          <div class="img_div">
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                            </div>
                            <div class="notepad_div">
                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                                <input type="hidden" name="hidden_id" class="notepad_hidden_task_id" value="<?php echo $result->task_id ?>">
                                <input type="button" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                <spam class="error_files_notepad"></spam>
                              @csrf
</form>
                            </div>
                    </div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->holiday == 0){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->holiday == 1){
                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->process == 0){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->process == 1){
                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->payslips == 0){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->payslips == 1){
                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->email == 0){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->email == 1){
                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>
                  </td>
                  <td align="center">
                    <?php
                      if($result->upload == 0){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';
                      }
                      else if($result->upload == 1){
                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';
                      }
                      else{
                        echo 'N/A';
                      }
                    ?>
                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>
                  </td>
                  <?php 
                    if($result->task_status == 1)
                    {
                      $date = date('d-M-Y', strtotime($result->updatetime));
                      $time = date('H:i', strtotime($result->updatetime));
                    }
                    else{
                      $date = 'DD-MMM-YYYY';
                      $time = 'HH:MM';
                    }
                  ?>
                  <td class="date_sort_cmp_val" align="center">
                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>
                  </td>
                  <td class="user_sort_cmp_val" align="center">
                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>
                        <?php
                            $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                            $uname = '<option value="">Select Username</option>';
                            if(($userlist)){
                              foreach ($userlist as $singleuser) {
                                if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                                if($uname == '')
                                {
    $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                                else{
    $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                }
                              }
                            }
                            echo $uname;
                        ?>
                    </select>
                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>
                    <div class="row" style="width:250%;margin-top:15px">
                      <div class="col-md-3">
                        <label style="margin-top: 6px;">Scheme: </label>
                      </div>
                      <div class="col-md-9">
                        <select name="select_scheme" class="form-control select_scheme <?php if($weekid->week_closed == '0000-00-00 00:00:00') { echo ''; } else { echo 'disabled_scheme'; } ?>" data-element="<?php echo $result->task_id; ?>" id="select_scheme_<?php echo $result->task_id; ?>" style="width:91%">
                          <option value="0"></option>
                          <?php
                          if(($schemes))
                          {
                            foreach($schemes as $scheme)
                            {
                              if($scheme->status == 0)
                              {
                                if($result->scheme_id == $scheme->id) { $sch_selected = 'selected'; } else { $sch_selected = ''; }
                                echo '<option value="'.$scheme->id.'" '.$sch_selected.'>'.$scheme->scheme_name.'</option>';
                              }
                              else{
                                if($result->scheme_id == $scheme->id) {
                                  echo '<option value="'.$scheme->id.'" selected>'.$scheme->scheme_name.'</option>';
                                }
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </td>
                  <td class="email_sort_cmp_val" align="center">
                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>"  placeholder="Enter Email" disabled>
                  </td>
                  <td align="center" class="file_received_div" style="text-align: left">
                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>
                      <div class="file_received_div_right">
                      <?php
                      $bi_payroll_next_status = 0;
                      $attachments = DB::table('pms_task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();
                      if(($attachments))
                      {
                        if($result->bi_payroll == 1)
                        {
                          $bi_payroll_next_status = 1;
                        }
                          echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:-18px;margin-left: 21px;" aria-hidden="true" title="Delete All Attachments"></i>';
                          echo '<h5 style="color:#000; font-weight:600; position:absolute;">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)
                            {
                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                            }
                          echo '</div>';
                      }
                      ?>
                    </div>
                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" oninput="keypressonlynumber(this)" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" oninput="keypressonlynumber(this)" disabled>
                          <?php } ?>
                          <div class="img_div">
                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                @csrf
</form>
                                <a href="javascript:" class="btn btn-sm btn-primary submit_dropzone_attach" data-element="<?php echo $result->task_id; ?>" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <div class="disclose_div">
                             <div class="disclose_sub_1">
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Liability on Client Email</label>
                        </div>
                         <div class="disclose_sub_2">
                          <?php
                          if($result->distribute_email == 1) { $check_email = 'checked'; } else { $check_email = ''; } ?>
                          <input type="checkbox" name="distribute_email" id="distribute_email<?php echo $result->task_id; ?>" class="distribute_email" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_email.' '.$disabled; ?>><label class="dist_email_label" for="distribute_email<?php echo $result->task_id; ?>">Distribute Email by Link</label>
                        </div>
                        </div>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="fa fa-envelope email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('public/assets/email_resend_icon.png')?>" class="resendemail_unsent" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">  
                    </a>
                      <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('pms_task_email_sent')->where('task_id',$result->task_id)->where('options','!=','n')->get();
                      $last_date = '';
                      if(($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>
                    <label class="email_unsent_label"><?php echo $last_date; ?></label>
                  </td>
                  <td align="center">
                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;
                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                      <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                     <div style="height: auto; float: right; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>
                    <?php $displaycls = ''; if($bi_payroll_next_status == 0){ $displaycls = 'displayhide'; } ?>
                    <br/>
                    <p class="bi_payroll_comlete_div <?php echo $displaycls; ?>">Bi Period Payroll Complete - No Payroll Next Period</p>
                  </td>            
              </tr>
              <?php
                  $i++;
                  }
               }
               else{
                  echo "<tr>
                        <td colspan='15' align='center'>Task not found</td></tr>";
               }
              ?>
          </tbody>
      </table>
      <p></p>
      </div>
    <?php } ?>
</div>
<input type="hidden" name="sno_sortoptions_std" id="sno_sortoptions_std" value="asc">
<input type="hidden" name="task_sortoptions_std" id="task_sortoptions_std" value="asc">
<input type="hidden" name="date_sortoptions_std" id="date_sortoptions_std" value="asc">
<input type="hidden" name="user_sortoptions_std" id="user_sortoptions_std" value="asc">
<input type="hidden" name="email_sortoptions_std" id="email_sortoptions_std" value="asc">
<input type="hidden" name="initial_sortoptions_std" id="initial_sortoptions_std" value="asc">
<input type="hidden" name="sno_sortoptions_enh" id="sno_sortoptions_enh" value="asc">
<input type="hidden" name="task_sortoptions_enh" id="task_sortoptions_enh" value="asc">
<input type="hidden" name="date_sortoptions_enh" id="date_sortoptions_enh" value="asc">
<input type="hidden" name="user_sortoptions_enh" id="user_sortoptions_enh" value="asc">
<input type="hidden" name="email_sortoptions_enh" id="email_sortoptions_enh" value="asc">
<input type="hidden" name="initial_sortoptions_enh" id="initial_sortoptions_enh" value="asc">
<input type="hidden" name="sno_sortoptions_cmp" id="sno_sortoptions_cmp" value="asc">
<input type="hidden" name="task_sortoptions_cmp" id="task_sortoptions_cmp" value="asc">
<input type="hidden" name="date_sortoptions_cmp" id="date_sortoptions_cmp" value="asc">
<input type="hidden" name="user_sortoptions_cmp" id="user_sortoptions_cmp" value="asc">
<input type="hidden" name="email_sortoptions_cmp" id="email_sortoptions_cmp" value="asc">
<input type="hidden" name="initial_sortoptions_cmp" id="initial_sortoptions_cmp" value="asc">
<div class="modal_load"></div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we update Scheme status for all the tasks</p>
</div>
<script>
$(document).ready(function () {
  $('#dtBasicExample').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true
    });
});
$(document).ready(function() {
  $('.table-fixed-header').fixedHeader();
  $('.table-fixed-header_1').fixedHeader();
  $('.table-fixed-header_2').fixedHeader();
  if($("#show_incomplete").is(':checked'))
  {
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).parents('tr').hide();
        }
    });
  }
  else{
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).parents('tr').show();
        }
    });
  }
});
</script>
<?php
if(!empty($_GET['divid']))
{
  $divid = $_GET['divid'];
  ?>
  <script>
  $(function() {
    $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) );  
  });
  </script>
  <?php
}
?>
<script>
fileList = new Array();
Dropzone.options.imageUpload = {
    addRemoveLinks: true,
    maxFilesize:50,
    acceptedFiles: null,
    init: function() {
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
initSample();
  $( function() {
    $("#datepicker" ).datepicker({ dateFormat: 'dd-MM-yy' });
  } );
  $( function() {
    $(".datepicker" ).datepicker({ dateFormat: 'dd-MM-yy' });
  } );
  </script>
<script>
$(".commandclass").change( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('user/command_stire') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
    }
  })
});
function downloadFile(urlToSend) {
     var req = new XMLHttpRequest();
     req.open("GET", urlToSend, true);
     req.responseType = "blob";
     req.onload = function (event) {
         var blob = req.response;
         var fileName = req.getResponseHeader("fileName") //if you have the fileName header available
         var link=document.createElement('a');
         link.href=window.URL.createObjectURL(blob);
         link.download=fileName;
         link.click();
     };
     req.send();
 }
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}
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
  if($(e.target).hasClass('active_client_list_pm1'))
  {
    var client_id=$("#client_search").val();
    if(client_id!=''){
      $.ajax({
        url:"<?php echo URL::to('user/add_to_active_client_list'); ?>",
        type:"post",
        data:{'client_id':client_id},
        success:function(result)
        {
          if(result=="0"){
            alert("Details Already Existed");
          }
          else{
            $("#success_msg_active_list").html(result);
          }
        }
      })
    }
    else{
      alert('Please Select Client');
    }  
  }
  if($(e.target).hasClass('active_client_list_pm'))
  {
    var client_id=$("#hidden_client_id_edit").val();
    if(client_id!=''){
      $.ajax({
        url:"<?php echo URL::to('user/add_to_active_client_list'); ?>",
        type:"post",
        data:{'client_id':client_id},
        success:function(result)
        {
          if(result=="0"){
            alert("Details Already Existed");
          }
          else{
            $("#success_msg_active_list").html(result);
          }
        }
      })
    }
    else{
      alert('Please Select Client');
    }  
  }
  if($(e.target).hasClass('active_client_list_pms'))
  {
    var client_id=$(e.target).data('iden');
    if(client_id!=''){
      $.ajax({
        url:"<?php echo URL::to('user/add_to_active_client_list'); ?>",
        type:"post",
        data:{'client_id':client_id},
        success:function(result)
        {
          if(result=="0"){
            alert("Details Already Existed");
          }
          else{
            $("#success_msg_active_list").html(result);
          }
        }
      })
    }
    else{
      alert('Please Select Client');
    }  
  }
  if($(e.target).hasClass('bi_payroll')) {
    var taskid = $(e.target).attr("data-element");
    if($(e.target).is(":checked")) {
      var status = 1;
    }
    else {
      var status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/update_bi_payroll_status'); ?>",
      type:"post",
      data:{taskid:taskid,status:status},
      success:function(result)
      {
        if(result == 1)
        {
          $(e.target).parents("tr").find(".bi_payroll_comlete_div").show();
        }
        else{
          $(e.target).parents("tr").find(".bi_payroll_comlete_div").hide();
        }
      }
    })
  }
  if($(e.target).hasClass('select_all_request')){
    if($(e.target).is(":checked"))
    {
      $(".notify_option").prop("checked",false);
      var ids = $("#hidden_request_taskids").val();
      var taskids = ids.split(",");
      $.each(taskids, $.proxy(function(index, value) {
         $(".req_pay_"+value).find(".notify_option").prop("checked",true);
      }, this));
      //$(".notify_option").prop("checked",true);
    }
    else{
      $(".notify_option").prop("checked",false);
    }
  }
  if($(e.target).hasClass('show_os_payroll'))
  {
    if($(e.target).hasClass('show_all'))
    {
      $(".notify_option").parents("tr").show();
      $(e.target).removeClass("show_all");
      $(e.target).html("Show OS Payroll Only");
    }
    else{
      $(".notify_option").parents("tr").hide();
      $(".notify_option:checked").parents("tr").show();
      $(e.target).addClass("show_all");
      $(e.target).html("Show all");
    }
  }
  if($(e.target).hasClass('start_rating'))
  {
    var taskid = $(e.target).attr("data-task");
    var value = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/start_rating'); ?>",
      type:"post",
      data:{taskid:taskid,value:value},
      success:function(result)
      {
        $(e.target).parents("td").find(".start_rating").removeClass("disabled_star");
        if(value == "4")
        {
          $(e.target).parents("td").find(".starfour").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starthree").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".startwo").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starone").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starzero").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
        }
        else if(value == "3")
        {
          $(e.target).parents("td").find(".starfour").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starthree").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".startwo").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starone").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starzero").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
        }
        else if(value == "2")
        {
          $(e.target).parents("td").find(".starfour").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starthree").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".startwo").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starone").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starzero").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
        }
        else if(value == "1")
        {
          $(e.target).parents("td").find(".starfour").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starthree").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".startwo").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starone").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
          $(e.target).parents("td").find(".starzero").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
        }
        else if(value == "0")
        {
          $(e.target).parents("td").find(".starfour").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starthree").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".startwo").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starone").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star-o');
          $(e.target).parents("td").find(".starzero").removeClass('fa-star-o').removeClass('fa-star').addClass('fa-star');
        }
      }
    });
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
              if(result == "1")
              {
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Email Field is empty. So Email is not sent</p>',fixed:true,width:"800px"});
                $("body").removeClass("loading");
              }
              else if(result == "2")
              {
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Attachments are empty. Please attach the files before you sent an email.</p>',fixed:true,width:"800px"});
                $("body").removeClass("loading");
              }
              else{
                var date = result.split("||");
                $("#taskidtr_"+date[1]).find(".email_unsent_label").html(date[0]);
                $("body").removeClass("loading");
                $(".emailunsent").modal("hide");
              }
          }
      }).submit();
    }
  }
  if($(e.target).hasClass('resend_email_unsent_files_btn'))
  { 
    for (instance in CKEDITOR.instances) 
    {
        CKEDITOR.instances['editor_9'].updateElement();
    }
    if($("#resend_email_unsent_form").valid())
    {
      $("body").addClass("loading");
      $('#resend_email_unsent_form').ajaxForm({
          success:function(result){
              if(result == "1")
              {
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Email Field is empty. So Email is not sent</p>',fixed:true,width:"800px"});
                $("body").removeClass("loading");
              }
              else if(result == "2")
              {
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Attachments are empty. Please attach the files before you sent an email.</p>',fixed:true,width:"800px"});
                $("body").removeClass("loading");
              }
              else{
                var date = result.split("||");
                $("#taskidtr_"+date[1]).find(".email_unsent_label").html(date[0]);
                $("body").removeClass("loading");
                $(".resendemailunsent").modal("hide");
              }
          }
       });
    }
  }
  if($(e.target).hasClass('submit_dropzone'))
  {
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_pms_file_attachments'); ?>",
      type:"post",
      data:{task_id:task_id,type:"2"},
      dataType:"json",
      success:function(result)
      {
        $(e.target).parents("td:first").find(".files_received_div").html(result['files_received']);
        $(e.target).parents("tr:first").find(".task_label").css("color","#89ff00");
        $(e.target).parents("tr:first").find(".task_started_checkbox").prop("checked",true);
        $(".dz-preview").detach();
        $(".dz-message").parents(".dropzone").removeClass("dz-started");
        var dataval = $(e.target).attr("data-val");
        if(dataval == "1"){
            $(e.target).removeAttr("data-val");
        }
        else{
            if(result['current_day'] == "Thursday" || result['current_day'] == "Friday" || result['current_day'] == "Saturday" || result['current_day'] == "Sunday"){
              $("#taskidtr_"+task_id).find(".create_task_manager").trigger("click");
            }
        }
      }
    })
  }
  if($(e.target).hasClass('submit_dropzone_attach'))
  {
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_pms_file_attachments'); ?>",
      type:"post",
      data:{task_id:task_id,type:"1"},
      dataType:"json",
      success:function(result)
      {
        $(e.target).parents("td:first").find(".file_received_div_right").html(result['output']);
        $(".dz-preview").detach();
        $(".dz-message").parents(".dropzone").removeClass("dz-started");
        $(e.target).parents("td:first").find(".liability_input").prop("disabled",false);
        if(result['bi_payroll_next_status'] == 1)
        {
          $(e.target).parents("tr").find(".bi_payroll_comlete_div").show();
        }
        else{
          $(e.target).parents("tr").find(".bi_payroll_comlete_div").hide();
        }
      }
    })
  }
  if($(e.target).hasClass('secret_button'))
  {
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/secret_task_button'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $(e.target).parents("tr").find(".enterhours_checkbox").prop("checked",true);
        $(e.target).parents("tr").find(".holiday_checkbox").prop("checked",true);
        $(e.target).parents("tr").find(".process_checkbox").prop("checked",true);
        $(e.target).parents("tr").find(".payslips_checkbox").prop("checked",true);
        $(e.target).parents("tr").find(".email_checkbox").prop("checked",true);
        $(e.target).parents("tr").find(".upload_checkbox").prop("checked",true);
      }
    })
  }
  if($(e.target).hasClass('close_schemes'))
  {
    $("body").addClass("loading_content");
    window.location.reload();
  }
  if($(e.target).parents(".close_schemes").length > 0)
  {
    $("body").addClass("loading_content");
    window.location.reload();
  }
  if($(e.target).hasClass('change_scheme_status'))
  {
    var href = $(e.target).attr("data-src");
    var status = $(e.target).attr("data-element");
    $.ajax({
      url:href,
      type:"get",
      success:function(result)
      {
        if(status == "1")
        {
          $(e.target).addClass("fa-check-circle-o");
          $(e.target).removeClass("fa-times-circle");
          $(e.target).attr("title","Open");
          $(e.target).attr("data-element","0");
          $(e.target).css("color","green");
          $(e.target).attr("data-src",result);
        }
        else{
          $(e.target).addClass("fa-times-circle");
          $(e.target).removeClass("fa-check-circle-o");
          $(e.target).attr("title","Closed");
          $(e.target).attr("data-element","1");
          $(e.target).css("color","red");
          $(e.target).attr("data-src",result);
        }
      }
    })
  }
  if($(e.target).hasClass('same_as_last'))
  {
    if($(e.target).is(":checked"))
    {
      var task_id = $(e.target).attr("data-element");
      var r = confirm("Do you want to carry information forward from a previous period?");
      if(r)
      {
        $.ajax({
          url:"<?php echo URL::to('user/check_previous_week'); ?>",
          type:"post",
          data:{task_id:task_id,week:"<?php echo $weekid->week_id; ?>",status:"1"},
          success:function(result)
          {
            $(e.target).parents(".special_div").find(".files_received_div").html(result);
            $(e.target).parents(".special_div").find(".same_as_last_label").css({"color":"blue","text-decoration":"underline","font-weight":"600"});
            if(result == "")
            {
              $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">No files found in the previous period for this task</p>',fixed:true,width:"800px"});
            }
            else{
              $("#taskidtr_"+task_id).find(".task_started_checkbox").prop("checked",true);
              if($("#taskidtr_"+task_id).find(".task_label").not('disabled'))
              {
                $("#taskidtr_"+task_id).find(".task_label").css("color",'#89ff00');
              }
            }
          }
        })
      }
      else{
        $(e.target).prop("checked",false);
      }
    }
    else{
      var task_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/check_previous_week'); ?>",
        type:"post",
        data:{task_id:task_id,week:"<?php echo $weekid->week_id; ?>",status:"0"},
        success:function(result)
        {
          $(e.target).parents(".special_div").find(".files_received_div").html(result);
          $(e.target).parents(".special_div").find(".same_as_last_label").css({"text-decoration":"none","font-weight":"700"})
        }
      })
    }
  }
  if($(e.target).hasClass('open_schemes'))
  {
    $("#scheme_type_open").prop("checked",true);
    $(".createschemes").modal("show");
  }
  if($(e.target).hasClass('add_scheme_btn'))
  {
    var scheme_name = $(".scheme_name").val();
    var type = $(".scheme_type:checked").val();
    if(scheme_name == "")
    {
      alert("Please enter the Scheme name and then proceed");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/add_scheme'); ?>",
        type:"post",
        data:{scheme_name:scheme_name,status:type},
        dataType:"json",
        success: function(result)
        {
          $('#scheme_tbody').html(result['output']);
          $(".scheme_name").val('');
          $("#scheme_type_open").prop("checked",true);
          $(".select_scheme").append(result['option'])
        }
      })
    }
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
            $(".2_bill_task").prop("checked",true);
            var formData = $("#create_task_form").submit(function (e) {
              return;
            });
            var formData = new FormData(formData[0]);
            formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
            $("body").addClass("loading");
            $.ajax({
                url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
                type: 'POST',
                data: formData,
                dataType:"json",
                success: function (result) {
                  $(".create_new_task_model").modal("hide");
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">Task Created Successfully</p>',fixed:true,width:"800px"});
                  $("body").removeClass("loading");
                },
                cache: false,
                contentType: false,
                processData: false
            });
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px",height:"400px"});
          }
        }
      }
      else{
        if($(".2_bill_task").is(":checked"))
        {
          var formData = $("#create_task_form").submit(function (e) {
            return;
          });
          var formData = new FormData(formData[0]);
          formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
          $("body").addClass("loading");
          $.ajax({
              url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
              type: 'POST',
              data: formData,
              dataType:"json",
              success: function (result) {
                $(".create_new_task_model").modal("hide");
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">Task Created Successfully</p>',fixed:true,width:"800px"});
                $("body").removeClass("loading");
              },
              cache: false,
              contentType: false,
              processData: false
          });
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
    var formData = $("#create_task_form").submit(function (e) {
      return;
    });
    var formData = new FormData(formData[0]);
    formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
    $("body").addClass("loading");
    $.ajax({
        url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
        type: 'POST',
        data: formData,
        dataType:"json",
        success: function (result) {
          $(".create_new_task_model").modal("hide");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">Task Created Successfully</p>',fixed:true,width:"800px"});
          $("body").removeClass("loading");
        },
        cache: false,
        contentType: false,
        processData: false
    });
  }
  if($(e.target).hasClass('no_make_task_live'))
  {
    $(".2_bill_task").prop("checked",false);
    var formData = $("#create_task_form").submit(function (e) {
      return;
    });
    var formData = new FormData(formData[0]);
    formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
    $("body").addClass("loading");
    $.ajax({
        url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
        type: 'POST',
        data: formData,
        dataType:"json",
        success: function (result) {
          $(".create_new_task_model").modal("hide");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">Task Created Successfully</p>',fixed:true,width:"800px"});
          $("body").removeClass("loading");
        },
        cache: false,
        contentType: false,
        processData: false
    });
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
    var taskid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_clientname_from_pms'); ?>",
      type:"post",
      dataType:"json",
      data:{taskid:taskid},
      success: function(result)
      {
        $(".internal_checkbox_div").show();
        $(".client_search_class_task").val(result['company']);
        $("#client_search_task").val(result['client_id']);
        $(".create_new_task_model").find(".job_title").html("New Task Creator");
        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
        var user_id = $(".select_user_home").val();
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
          height: '300px',
          enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
       });
        $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
        $(".author_email").val("<?php echo Session::get('taskmanager_user_email'); ?>");
        $("#action_type").val("1");
        $(".allocate_user_add").val(result['staff']);
        $(".allocate_email").val(result['allocated_email']);
        $(".task-choose_internal").html("Select Task");
        $(".subject_class").val("Current Period (Weekly) Payroll to Be Created");
        $(".task_specifics_add").show();
        CKEDITOR.instances['editor_2'].setData("Do Payroll");
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
          data:{fileid:fileid},
          success: function(result)
          {
            $("#add_notepad_attachments_div").html('');
            $("#add_attachments_div").html('');
            $("body").removeClass("loading");
            $("#attachments_infiles").show();
            $("#add_infiles_attachments_div").html(result);
          }
        });
      }
    });
  }
  if(e.target.id == "internal_checkbox")
  {
    $("#client_search").val("");
    $("#idtask").val("");
    $(".task-choose_internal").html("Select Task");
    $(".client_search_class").val("");
    if($(e.target).is(":checked"))
    {
      $(".client_group").hide();
      $(".client_search_class").prop("required",false);
      $(".internal_tasks_group").show();
      $(".infiles_link").hide();
    }
    else{
      $(".client_group").show();
      $(".client_search_class").prop("required",true);
      $(".internal_tasks_group").hide();
      $(".infiles_link").show();
    }
  }
  if($(e.target).hasClass('tasks_li'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);
    $(".task-choose:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('tasks_li_internal'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);
    $(".task-choose_internal:first-child").text($(e.target).text());
    var project_id = $(e.target).attr("data-project");
    if(project_id == "0"){
      $(".select_project").val("");
    }
    else{
      $(".select_project").val(project_id);
    }
  }
  if($(e.target).hasClass('tasks_li_internal_change'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_change").val(taskid);
    $(".task-choose_internal_change:first-child").text($(e.target).text());
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
  if($(e.target).hasClass('disclose_liability'))
  {
    if($(e.target).is(":checked"))
    {
      var status = 1;
    }
    else{
      var status = 0;
    }
    var task_id = $(e.target).attr("data-element");
    console.log(task_id);
    $.ajax({
      url:"<?php echo URL::to('user/save_disclose_liability'); ?>",
      type:"post",
      data:{task_id:task_id,status:status},
      success: function(result)
      {
      }
    });
  }
  if($(e.target).hasClass('distribute_email'))
  {
    if($(e.target).is(":checked"))
    {
      var status = 1;
    }
    else{
      var status = 0;
    }
    var task_id = $(e.target).attr("data-element");
    console.log(task_id);
    $.ajax({
      url:"<?php echo URL::to('user/save_distribute_email'); ?>",
      type:"post",
      data:{task_id:task_id,status:status},
      success: function(result)
      {
      }
    });
  }
  if($(e.target).hasClass('report_email_unsent'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/email_report_generator'); ?>",
      type:"get",
      data:{task_id:task_id},
      success: function(result)
      {
         $("body").removeClass("loading");
         SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    })
  }
  if(e.target.id == "email_option_submit")
  {
      var value = $(".email_sent_options:checked").val();
      if(value == "" || typeof value === "undefined")
      {
          alert("Please select any one of the option to proceed");
      }
      else{
        $("#email_sent_option").val(value);
        $("#show_email_sent_popup").modal("hide");
        var task_id = $("#hidden_task_id_val").val();
        $.ajax({
          url:'<?php echo URL::to('user/edit_email_unsent_files'); ?>',
          type:'get',
          data:{task_id:task_id},
          dataType:"json",
          success: function(result)
          {
              CKEDITOR.instances['editor'].setData(result['html']);
              $("#email_attachments").html(result['files']);
              $(".subject_unsent").val(result['subject']);
              $("#select_user").val(result['from']);
              $("#to_user").val(result['to']);
              $("#email_task_id_value").val(task_id);
              $("#email_attachments").parent().show();
              $(".emailunsent").modal('show');
          }
        })
      }
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    var task_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id,task_id:task_id},
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
  if($(e.target).hasClass('image_submit'))
  {
    var files = $(e.target).parent().find('.image_file').val();
    if(files == '' || typeof files === 'undefined')
    {
      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
      return false;
    }
    else{
      $(e.target).parents('td').find('.img_div').toggle();
    }
  }
  else{
    $(".img_div").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).parents(".dropzone").length > 0)
  {
    $(e.target).parents('.img_div').show();
    $(e.target).parents(".dropzone").trigger("click");
  }
  if($(e.target).hasClass('notepad_submit'))
  {
    var contents = $(e.target).parent().find('.notepad_contents').val();
   if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      var contents = $(e.target).parents("td").find(".notepad_contents").val();
      var task_id = $(e.target).parents("td").find(".notepad_div").find(".notepad_hidden_task_id").val();
      $.ajax({
        url:"<?php echo URL::to('user/task_notepad_upload'); ?>",
        type:"post",
        data:{notepad_contents:contents,hidden_id:task_id},
        success:function(result)
        {
          $(e.target).parents("td:first").find(".submit_dropzone").trigger("click");
        }
      });
      $(e.target).parents('td').find('.notepad_div').toggle();
    }
  }
  else{
    $(".notepad_div").each(function() {
      $(this).hide();
    });
  }
  if(e.target.id == "alert_submit")
  {
    var company = $(".company_update:checked").val();
    var emp = $(".emp_update:checked").val();
    var email = $(".email_update:checked").val();
    var salutation = $(".salutation_update:checked").val();
    if(company == "" || typeof company === "undefined" || emp == "" || typeof emp === "undefined" || email == "" || typeof email === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id").val();
      if(company == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientcompanyname'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $("#taskname").val(result);
          }
        });
      }
      if(email == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $("#task_email_create").val(result);
          }
        });
      }
      if(emp == 1)
      {
        $("#hidden_client_emp").val(1);
      }
      else{
        $("#hidden_client_emp").val(0);
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation").val(1);
      }
      else{
        $("#hidden_client_salutation").val(0);
      }
      $("#alert_modal").modal("hide");
    }
  }
  if(e.target.id == "alert_submit_edit")
  {
    var company = $(".company_update_edit:checked").val();
    var emp = $(".emp_update_edit:checked").val();
    var email = $(".email_update_edit:checked").val();
    var salutation = $(".salutation_update_edit:checked").val();
    if(company == "" || typeof company === "undefined" || emp == "" || typeof emp === "undefined" || email == "" || typeof email === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id_edit").val();
      if(company == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientcompanyname'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $("#taskname_edit").val(result);
          }
        });
      }
      if(email == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $("#task_email_edit").val(result);
          }
        });
      }
      if(emp == 1)
      {
        $("#hidden_client_emp_edit").val(1);
      }
      else{
        $("#hidden_client_emp_edit").val(0);
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation_edit").val(1);
      }
      else{
        $("#hidden_client_salutation_edit").val(0);
      }
      $("#alert_modal_edit").modal("hide");
    }
  }
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(':checked'))
    {
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).parents('tr').hide();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_incomplete_status'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
        }
      });
    }
    else{
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).parents('tr').show();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }
  if($(e.target).hasClass('faplus'))
  {
    var temp = $(e.target).parents('tr').find(".network_input");
    copyToClipboard(temp);
  }
  if($(e.target).hasClass('dropdown_download'))
  {
    $(".download_div").each(function() {
      $(this).hide();
    });
    $(e.target).parents('ul').find('.download_div').toggle();
  }
  if($(e.target).hasClass('dropdown_notify'))
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    $(".notify_div").each(function() {
      $(this).hide();
    });
    $(e.target).parents('ul').find('.notify_div').toggle();
  }
  if($(e.target).hasClass('close_xmark'))
  {
    $(e.target).parent().hide();
  }
  var ascending = false;
  if($(e.target).hasClass('email_unsent'))
  {
    if($(e.target).parents("tr").find(".distribute_email").is(":checked"))
    {
      $("#email_sent_option").val("0");
      var task_id = $(e.target).attr('data-element');
      $.ajax({
        url:'<?php echo URL::to('user/edit_email_unsent_files_distribute_by_link'); ?>',
        type:'get',
        data:{task_id:task_id},
        dataType:"json",
        success: function(result)
        {
            CKEDITOR.instances['editor'].setData(result['html']);
            $(".subject_unsent").val(result['subject']);
            $("#select_user").val(result['from']);
            $("#to_user").val(result['to']);
            $("#email_task_id_value").val(task_id);
            $("#email_attachments").parent().hide();
            $(".emailunsent").modal('show');
        }
      })
    }
    else{
      if($(e.target).hasClass('show_popup'))
      {
        var task_id = $(e.target).attr('data-element');
        $("#hidden_task_id_val").val(task_id);
        $("#show_email_sent_popup").modal("show");
      }
      else{
        $("#email_sent_option").val("0");
        var task_id = $(e.target).attr('data-element');
        $.ajax({
          url:'<?php echo URL::to('user/edit_email_unsent_files'); ?>',
          type:'get',
          data:{task_id:task_id},
          dataType:"json",
          success: function(result)
          {
              CKEDITOR.instances['editor'].setData(result['html']);
              $("#email_attachments").html(result['files']);
              $(".subject_unsent").val(result['subject']);
              $("#select_user").val(result['from']);
              $("#to_user").val(result['to']);
              $("#email_task_id_value").val(task_id);
              $("#email_attachments").parent().show();
              $(".emailunsent").modal('show');
          }
        })
      }
    }
  }
  if($(e.target).hasClass('resendemail_unsent'))
  {
    if($(e.target).parents("tr").find(".distribute_email").is(":checked"))
    {
      $("#email_sent_option").val("0");
      var task_id = $(e.target).attr('data-element');
      $.ajax({
        url:'<?php echo URL::to('user/edit_email_unsent_files_distribute_by_link'); ?>',
        type:'get',
        data:{task_id:task_id},
        dataType:"json",
        success: function(result)
        {
            CKEDITOR.instances['editor_9'].setData(result['html']);
            $(".subject_resend").val(result['subject']);
            $("#select_userresend").val(result['from']);
            $("#to_userresend").val(result['to']);
            $("#resend_email_task_id_value").val(task_id);
            $("#email_attachmentsresend").parent().hide();
            $(".resendemailunsent").modal('show');
        }
      })
    }
    else{
      var task_id = $(e.target).attr('data-element');
      $.ajax({
        url:'<?php echo URL::to('user/resendedit_email_unsent_files'); ?>',
        type:'get',
        data:{task_id:task_id},
        dataType:"json",
        success: function(result)
        {
            CKEDITOR.instances['editor_9'].setData(result['html']);
            $("#email_attachmentsresend").html(result['files']);
            $(".subject_resend").val(result['subject']);
            $("#select_userresend").val(result['from']);
            $("#to_userresend").val(result['to']);
            $("#resend_email_task_id_value").val(task_id);
            $("#email_attachmentsresend").parent().show();
            $(".resendemailunsent").modal('show');
        }
      })
    }
  }
  if($(e.target).hasClass('sno_sort_std'))
  {
    var sort = $("#sno_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_std_val').html()) <
        convertToNumber($(b).find('.sno_sort_std_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_std_val').html()) <
        convertToNumber($(b).find('.sno_sort_std_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('sno_sort_enh'))
  {
    var sort = $("#sno_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_enh_val').html()) <
        convertToNumber($(b).find('.sno_sort_enh_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_enh_val').html()) <
        convertToNumber($(b).find('.sno_sort_enh_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('sno_sort_cmp'))
  {
    var sort = $("#sno_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_cmp_val').html()) <
        convertToNumber($(b).find('.sno_sort_cmp_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_cmp_val').html()) <
        convertToNumber($(b).find('.sno_sort_cmp_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('task_sort_std'))
  {
    var sort = $("#task_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#task_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_std_val').html()) <
        convertToNumber($(b).find('.task_sort_std_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#task_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_std_val').html()) <
        convertToNumber($(b).find('.task_sort_std_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('task_sort_enh'))
  {
    var sort = $("#task_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#task_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_enh_val').html()) <
        convertToNumber($(b).find('.task_sort_enh_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#task_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_enh_val').html()) <
        convertToNumber($(b).find('.task_sort_enh_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('task_sort_cmp'))
  {
    var sort = $("#task_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#task_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_cmp_val').html()) <
        convertToNumber($(b).find('.task_sort_cmp_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#task_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.task_sort_cmp_val').html()) <
        convertToNumber($(b).find('.task_sort_cmp_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('date_sort_std'))
  {
    var sort = $("#date_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_std_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_std_val').find('.date_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_std_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_std_val').find('.date_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('date_sort_enh'))
  {
    var sort = $("#date_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_enh_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_enh_val').find('.date_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_enh_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_enh_val').find('.date_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('date_sort_cmp'))
  {
    var sort = $("#date_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_cmp_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_cmp_val').find('.date_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_cmp_val').find('.date_input').val()) <
        convertToNumber($(b).find('.date_sort_cmp_val').find('.date_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('user_sort_std'))
  {
    var sort = $("#user_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#user_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_std_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_std_val').find('.uname_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#user_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_std_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_std_val').find('.uname_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('user_sort_enh'))
  {
    var sort = $("#user_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#user_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_enh_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_enh_val').find('.uname_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#user_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_enh_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_enh_val').find('.uname_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('user_sort_cmp'))
  {
    var sort = $("#user_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#user_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_cmp_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_cmp_val').find('.uname_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#user_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.user_sort_cmp_val').find('.uname_input').val()) <
        convertToNumber($(b).find('.user_sort_cmp_val').find('.uname_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('email_sort_std'))
  {
    var sort = $("#email_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#email_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_std_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_std_val').find('.task_email_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#email_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_std_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_std_val').find('.task_email_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('email_sort_enh'))
  {
    var sort = $("#email_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#email_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_enh_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_enh_val').find('.task_email_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#email_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_enh_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_enh_val').find('.task_email_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('email_sort_cmp'))
  {
    var sort = $("#email_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#email_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_cmp_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_cmp_val').find('.task_email_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#email_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.email_sort_cmp_val').find('.task_email_input').val()) <
        convertToNumber($(b).find('.email_sort_cmp_val').find('.task_email_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('initial_sort_std'))
  {
    var sort = $("#initial_sortoptions_std").val();
    if(sort == 'asc')
    {
      $("#initial_sortoptions_std").val('desc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_std_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_std_val').find('.initial_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#initial_sortoptions_std").val('asc');
      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_std_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_std_val').find('.initial_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_std').html(sorted);
  }
  if($(e.target).hasClass('initial_sort_enh'))
  {
    var sort = $("#initial_sortoptions_enh").val();
    if(sort == 'asc')
    {
      $("#initial_sortoptions_enh").val('desc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_enh_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_enh_val').find('.initial_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#initial_sortoptions_enh").val('asc');
      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_enh_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_enh_val').find('.initial_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_enh').html(sorted);
  }
  if($(e.target).hasClass('initial_sort_cmp'))
  {
    var sort = $("#initial_sortoptions_cmp").val();
    if(sort == 'asc')
    {
      $("#initial_sortoptions_cmp").val('desc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_cmp_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_cmp_val').find('.initial_input').val()))) ? 1 : -1;
      });
    }
    else{
      $("#initial_sortoptions_cmp").val('asc');
      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.initial_sort_cmp_val').find('.initial_input').val()) <
        convertToNumber($(b).find('.initial_sort_cmp_val').find('.initial_input').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body_cmp').html(sorted);
  }
  if($(e.target).hasClass('edit_task'))
  {
      var task_id = $(e.target).attr('data-element');
      $.ajax({
        url:'<?php echo URL::to('user/edit_task_name'); ?>',
        type:'get',
        data:{task_id:task_id},
        dataType:"json",
        success: function(result)
        {
            $("#hidden_client_id_edit").val(result['client_id']);
            $(".task_name").val(result['task_name']);
            $(".task_email_edit").val(result['task_email']);
            $(".secondary_email_edit").val(result['secondary_email']);
            $(".salutation_edit").val(result['salutation']);
            $(".task_network").val(result['network']);
            $(".task_category").val(result['category']);
            $(".hidden_taskname_id").val(result['task_id']);
            $(".enumberclass").val(result['enumber']);
            $(".companyclass").val(result['companyname']);
            $(".tax_reg1class_edit").val(result['taxreg']);
            $(".primaryemail_class_edit").val(result['primaryemail']);
            $(".firstname_class_edit").val(result['firstname']);
            if(result['enterhours'] == 2)
            {
              $("#hours_na").prop("checked",true);
            }
            else{
              $("#hours_enter").prop("checked",true);
            }
            if(result['holiday'] == 2)
            {
              $("#holiday_na").prop("checked",true);
            }
            else{
              $("#holiday_enter").prop("checked",true);
            }
            if(result['process'] == 2)
            {
              $("#process_na").prop("checked",true);
            }
            else{
              $("#process_enter").prop("checked",true);
            }
            if(result['payslips'] == 2)
            {
              $("#payslips_na").prop("checked",true);
            }
            else{
              $("#payslips_enter").prop("checked",true);
            }
            if(result['email'] == 2)
            {
              $("#email_na").prop("checked",true);
            }
            else{
              $("#email_enter").prop("checked",true);
            }
            if(result['upload'] == 2)
            {
              $("#report_na").prop("checked",true);
            }
            else{
              $("#report_enter").prop("checked",true);
            }
            $(".tasklevel_edit").val(result['tasklevel']);
            if(result['p30_pay'] == 1)
            {
              $("#p30_pay_yes").prop("checked",true);
            }
            else{
              $("#p30_pay_no").prop("checked",true);
            }
            if(result['p30_email'] == 1)
            {
              $("#p30_email_yes").prop("checked",true);
            }
            else{
              $("#p30_email_no").prop("checked",true);
            }
            $(".edit_task_modal").modal('show');
        }
      })
  }
  if($(e.target).hasClass('cancel_week'))
  {
      window.location.reload();
  }
  if(e.target.id == 'close_create_new_week')
  {
    var r = confirm("New Week will be created and all the task in this week will be copied in newly created week. Are you sure you want to continue?");
    if (r == true) {
    } else {
        return false;
    }
  }
  if(e.target.id == 'email_report_button')
  {
    var id = '<?php echo $weekid->week_id; ?>';
    $.ajax({
        url:"<?php echo URL::to('user/email_report_pdf'); ?>",
        type:"get",
        data:{id:id},
        success: function(result) {
          $(".subject_report").val(result);
          $("#email_report_model").modal('show');
          $("#task_report_label").show();
          $("#notify_report_label").hide();
          $("#hidden_report_type").val('task_report');
        }
    });
  }
  if(e.target.id == 'email_notify')
  {
    $(".notify_modal").modal('hide');
    var message = CKEDITOR.instances['editor_1'].getData();
    $("body").addClass("loading");
    var emails = [];
    var toemails = '';
    var timeval = "<?php echo time(); ?>";
    $(".notify_option").each(function(i, el) {
      var id = $(el).attr('data-element');
        if($(el).is(':checked'))
        {
          var user_email = $(el).parents('tr').find(".notify_primary_email").val();
          var secondary_email = $(el).parents('tr').find(".notify_secondary_email").val();
          if(user_email != '' && typeof user_email !== 'undefined')
          {
            if($.inArray(user_email, emails) == -1)
            {
              emails.push(user_email+'||'+id);
              if(toemails == '')
              {
                toemails= user_email;
              }
              else{
                toemails = toemails+', '+user_email;
              }
            }
          }
          if(secondary_email != '' && typeof secondary_email !== 'undefined')
          {
            if($.inArray(secondary_email, emails) == -1)
            {
              emails.push(secondary_email+'||'+id);
              if(toemails == '')
              {
                toemails= secondary_email;
              }
              else{
                toemails = toemails+', '+secondary_email;
              }
            }
          }
        }
    });
    toemails = toemails+', <?php echo $admin_cc; ?>';
    var option_length = emails.length;
    $.each( emails, function( i, value ) {
        setTimeout(function(){
          $.ajax({
            url:"<?php echo URL::to('user/email_notify_pdf'); ?>",
            type:"get",
            data:{email:value,message:message,toemails:toemails,week:"<?php echo $weekid->week_id; ?>",month:"0",timeval:timeval},
            success: function(result) {
              var keyi = parseInt(i) + parseInt(1);
              if(option_length == keyi)
              {
                $("body").removeClass("loading");
              }
            }
          });
        },2000 + ( i * 2000 ));
    });    
  }
  if(e.target.id == 'task_standard')
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    var id = '<?php echo $weekid->week_id; ?>';
    var value = 1;
    $("#notify_type").val(1);
    $.ajax({
        url:"<?php echo URL::to('user/notify_tasks'); ?>",
        type:"get",
        dataType:"json",
        data:{id:id,value:value},
        success: function(result) {
          $(".show_os_payroll").removeClass("show_all");
          $(".show_os_payroll").html("Show OS Payroll Only");
          $(".notify_modal").modal('show');
          $("#select_all_request").prop("checked",true);
          $(".notify_place_div").html(result['output']);
          $("#hidden_request_taskids").val(result['task_ids']);
          setTimeout(function(){  
             CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 
          },1000);
        }
    });
  }
  if(e.target.id == 'task_enhanced')
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    var id = '<?php echo $weekid->week_id; ?>';
    var value = 2;
    $("#notify_type").val(2);
    $.ajax({
        url:"<?php echo URL::to('user/notify_tasks'); ?>",
        type:"get",
        dataType:"json",
        data:{id:id,value:value},
        success: function(result) {
          $(".show_os_payroll").removeClass("show_all");
          $(".show_os_payroll").html("Show OS Payroll Only");
          $(".notify_modal").modal('show');
          $("#select_all_request").prop("checked",true);
          $(".notify_place_div").html(result['output']);
          $("#hidden_request_taskids").val(result['task_ids']);
          setTimeout(function(){  
             CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 
          },1000);
        }
    });
  }
  if(e.target.id == 'task_complex')
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    var id = '<?php echo $weekid->week_id; ?>';
    var value = 3;
    $("#notify_type").val(3);
    $.ajax({
        url:"<?php echo URL::to('user/notify_tasks'); ?>",
        type:"get",
        dataType:"json",
        data:{id:id,value:value},
        success: function(result) {
          $(".show_os_payroll").removeClass("show_all");
          $(".show_os_payroll").html("Show OS Payroll Only");
          $(".notify_modal").modal('show');
          $("#select_all_request").prop("checked",true);
          $(".notify_place_div").html(result['output']);
          $("#hidden_request_taskids").val(result['task_ids']);
          setTimeout(function(){  
             CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 
          },1000);
        }
    });
  }
  if($(e.target).hasClass('fileattachment'))
  {
    e.preventDefault();
    var element = $(e.target).attr('data-element');
    $('body').addClass('loading');
    setTimeout(function(){
      SaveToDisk(element,element.split('/').reverse()[0]);
      $('body').removeClass('loading');
      }, 3000);
    return false; 
  }
  if(e.target.id == 'all_tasks')
  {
    var id = '<?php echo $weekid->week_id; ?>';
    $("body").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('user/alltask_report_pdf'); ?>",
        type:"get",
        data:{id:id},
        success: function(result) {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            return false; //this is critical to stop the click event which will trigger a normal file download
        }
    });
  }
  if(e.target.id == 'task_completed')
  {
    var id = '<?php echo $weekid->week_id; ?>';
    $("body").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('user/task_complete_report_pdf'); ?>",
        type:"get",
        data:{id:id},
        success: function(result) {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            return false; //this is critical to stop the click event which will trigger a normal file download
        }
    });
  }
  if(e.target.id == 'task_incomplete')
  {
    var id = '<?php echo $weekid->week_id; ?>';
    $("body").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('user/task_incomplete_report_pdf'); ?>",
        type:"get",
        data:{id:id},
        success: function(result) {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            return false; //this is critical to stop the click event which will trigger a normal file download
        }
    });
  }
  if($(e.target).hasClass('year_button'))
  {
    $(".year_button").each(function() {
      $(this).removeClass('highlight_button');
    });
    $(e.target).addClass('highlight_button');
    $("#hidden_copy_year").val($(e.target).attr('data-element'));
    $("#select_year_type").val('');
    $(".weekly_select").html('');
  }
  if($(e.target).hasClass('week_button'))
  {
    $(".week_button").each(function() {
      $(this).removeClass('highlight_button');
    });
    $(e.target).addClass('highlight_button');
    $("#hidden_copy_week").val($(e.target).attr('data-element'));
    $(".category_select_copy").show();
  }
  if($(e.target).hasClass('month_button'))
  {
    $(".month_button").each(function() {
      $(this).removeClass('highlight_button');
    });
    $(e.target).addClass('highlight_button');
    $("#hidden_copy_month").val($(e.target).attr('data-element'));
    $(".category_select_copy").show();
  }
  if($(e.target).hasClass('fa-files-o'))
  {
    var element = $(e.target).attr('data-element');
    $("#hidden_task_id").val(element);
  }
  if($(e.target).hasClass('task_delete'))
  {
    var r = confirm("Are You Sure you want to delete this task");
    if (r == true) {
    }
    else{
      return false
    }
  }
  if($(e.target).hasClass('fa-check'))
  {
    var liability = $(e.target).parents('tr').find('.liability_input').prop('disabled');
    if(liability == 1)
    {
    }
    else{
        var liabilityval = $(e.target).parents('tr').find('.liability_input').val();
        if(liabilityval == "")
        {
          alert("You CAN NOT mark the wages as done until the the libility text box is filled in!");
          return false;
        }
    }
    var user = $(e.target).parents('tr').find(".uname_input").val();
    var enterhours = $(e.target).parents('tr').find(".enterhours_checkbox").length;
    var holiday = $(e.target).parents('tr').find(".holiday_checkbox").length;
    var process_check = $(e.target).parents('tr').find(".process_checkbox").length;
    var payslips = $(e.target).parents('tr').find(".payslips_checkbox").length;
    var email_check = $(e.target).parents('tr').find(".email_checkbox").length;
    var upload = $(e.target).parents('tr').find(".upload_checkbox").length;
    var enterhours_val = $(e.target).parents('tr').find(".enterhours_checkbox:checked").val();
    var holiday_val = $(e.target).parents('tr').find(".holiday_checkbox:checked").val();
    var process_check_val = $(e.target).parents('tr').find(".process_checkbox:checked").val();
    var payslips_val = $(e.target).parents('tr').find(".payslips_checkbox:checked").val();
    var email_check_val = $(e.target).parents('tr').find(".email_checkbox:checked").val();
    var upload_val = $(e.target).parents('tr').find(".upload_checkbox:checked").val();
    if(user == '' || typeof user === 'undefined')
    {
      alert('Please Select the username for this task to make Mark as Complete');
    }
    else{
      if((enterhours == 1 && typeof enterhours_val === 'undefined') || (holiday == 1 && typeof holiday_val === 'undefined') || (process_check == 1 && typeof process_check_val === 'undefined') || (payslips == 1 && typeof payslips_val === 'undefined') || (email_check == 1 && typeof email_check_val === 'undefined') || (upload == 1 && typeof upload_val === 'undefined'))
      {
        alert('You must CHECK all the boxes of this task to Mark this task as Complete');
      }
      else{
          var r = confirm("Please note that if you Mark this Task as Complete then all the fields will be disabled and you won't be able to change until you mark this task as incomplete again.");
          if (r == true) {
              var id = $(e.target).attr('data-element');
              $.ajax({
                  url:"<?php echo URL::to('user/task_status_update'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{status:1,id:id},
                  success: function(result) {
                    window.location.replace("<?php echo URL::to('user/select_week/'.base64_encode($weekid->week_id).'?divid='); ?>"+id);
              //       $(e.target).parents('tr').find(".date_input").val(result['date']);
              //       $(e.target).parents('tr').find(".time_input").val(result['time']);
              //       $(e.target).parents('tr').find("select").each(function(){
              //         $(this).prop('disabled',true);
              //       });
              //       $(e.target).parents('tr').find(".liability_input").prop("disabled",true);
              //       $(e.target).parents('tr').find("textarea").each(function(){
              //         $(this).prop('disabled',true);
              //       });
              //       $(e.target).parents('tr').find('.fa-trash').each(function() {
              //         $(this).addClass('disabled');
              //       });
              //       $(e.target).parents('tr').find(".task_started_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".enterhours_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".holiday_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".process_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".payslips_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".email_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find(".upload_checkbox").addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-trash').addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-trash').parent().addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-plus').addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-pencil-square').addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-minus-square').addClass('disabled');
              //       $(e.target).parents('tr').find('.task_label').addClass('disabled');
              //       $(e.target).parents('tr').find('.edit_task').addClass('disabled');
              //       $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');
              //       $(e.target).parents('tr').find('.task_delete').addClass('disabled');
              //         $(e.target).parents('tr').find('.single_notify').addClass('disabled');
              // $(e.target).parents('tr').find('.all_notify').addClass('disabled');
              // $(e.target).parents('tr').find('.donot_complete').addClass('disabled');
              // $(e.target).parents('tr').find('.do_complete').addClass('disabled');
              // $(e.target).parents('tr').find('.donot_complete').parent().addClass('disabled');
              // $(e.target).parents('tr').find('.do_complete').parent().addClass('disabled');
              //       $(e.target).parents('tr').find('.task_label').css({'color':'#f00','font-weight':'800'});
              //       $(e.target).removeClass('fa-check');
              //       $(e.target).addClass('fa-times');
              //       $(e.target).attr("data-original-title","Mark as Incomplete");
              //       if($("#show_incomplete").is(':checked'))
              //       {
              //         $(".edit_task").each(function() {
              //             if($(this).hasClass('disabled'))
              //             {
              //               $(this).parents('tr').hide();
              //             }
              //         });
              //       }
              //       else{
              //         $(".edit_task").each(function() {
              //             if($(this).hasClass('disabled'))
              //             {
              //               $(this).parents('tr').show();
              //             }
              //         });
              //       }
                  }
              });
          }
      }
    }
  }
  if($(e.target).hasClass('fa-times'))
  {
    var r = confirm("Unfreezing will enable all the input fields that you can change all the details");
    if (r == true) {
      var id = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/task_status_update'); ?>",
          type:"get",
          data:{status:0,id:id},
          success: function(result) {
            window.location.replace("<?php echo URL::to('user/select_week/'.base64_encode($weekid->week_id).'?divid='); ?>"+id);
   //          $(e.target).parents('tr').find(".date_input").val('MM-DD-YYYY');
   //          $(e.target).parents('tr').find(".time_input").val('HH:MM');
   //          $(e.target).parents('tr').find("select").each(function(){
   //            $(this).prop('disabled',false);
   //          });
   //          $(e.target).parents('tr').find(".liability_input").prop("disabled",false);
   //          $(e.target).parents('tr').find("textarea").each(function(){
   //            $(this).prop('disabled',false);
   //          });
   //          $(e.target).parents('tr').find('.fa-trash').each(function() {
   //            $(this).removeClass('disabled');
   //          });
   //          $(e.target).parents('tr').find('.fa-trash').removeClass('disabled');
   //          $(e.target).parents('tr').find('.fa-trash').parent().removeClass('disabled');
   //          $(e.target).parents('tr').find(".task_started_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".enterhours_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".holiday_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".process_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".payslips_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".email_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find(".upload_checkbox").removeClass('disabled');
   //          $(e.target).parents('tr').find('.fa-plus').removeClass('disabled');
   //          $(e.target).parents('tr').find('.fa-pencil-square').removeClass('disabled');
   //          $(e.target).parents('tr').find('.fa-minus-square').removeClass('disabled');
   //          $(e.target).parents('tr').find('.task_label').removeClass('disabled');
   //          $(e.target).parents('tr').find('.edit_task').removeClass('disabled');
   //          $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');
   //          $(e.target).parents('tr').find('.task_delete').removeClass('disabled');
   //          $(e.target).parents('tr').find('.single_notify').removeClass('disabled');
      // $(e.target).parents('tr').find('.all_notify').removeClass('disabled');
      // if($(e.target).parents('tr').find('.email_unsent_label').html() == "")
      // {
      // }
      // else{
      // }
      // $(e.target).parents('tr').find('.donot_complete').removeClass('disabled');
      // $(e.target).parents('tr').find('.do_complete').removeClass('disabled');
      // $(e.target).parents('tr').find('.donot_complete').parent().removeClass('disabled');
      // $(e.target).parents('tr').find('.do_complete').parent().removeClass('disabled');
   //          if($(e.target).parents('tr').find(".task_started_checkbox").is(":checked"))
   //          {
   //            $(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});
   //          }
   //          else{
   //            $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});
   //          }
   //          $(e.target).removeClass('fa-times');
   //          $(e.target).addClass('fa-check');
   //          $(e.target).attr("data-original-title","Mark as Completed");
   //          if($("#show_incomplete").is(':checked'))
   //          {
   //            $(".edit_task").each(function() {
   //                if($(this).hasClass('disabled'))
   //                {
   //                  $(this).parents('tr').hide();
   //                }
   //            });
   //          }
   //          else{
   //            $(".edit_task").each(function() {
   //                if($(this).hasClass('disabled'))
   //                {
   //                  $(this).parents('tr').show();
   //                }
   //            });
   //          }
          }
      });
    }
  }
  if($(e.target).hasClass('trash_image'))
  {
      if($(e.target).hasClass('sample_trash'))
      {
        var attach_count = $(e.target).parents('.file_received_div').find(".fileattachment").length;
        if(attach_count == 1)
        {
            var r = confirm("Are You sure you want to delete this image and Do you want to Remove the Liability Recorded and update it later?");
        }
        else{
              var r = confirm("Are You sure you want to delete this image");
        }
      }
      else{
        var r = confirm("Are You sure you want to delete this image");
      }
    if (r == true) {
      var imgid = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/task_delete_image'); ?>",
          type:"get",
          data:{imgid:imgid},
          success: function(result) {
            $(e.target).parents("td:first").find(".submit_dropzone").attr("data-val", "1");
            $(e.target).parents("td:first").find(".submit_dropzone").trigger("click");
            $(e.target).parents("td:first").find(".submit_dropzone_attach").trigger("click");
          }
      });
    }
  }
  if($(e.target).hasClass('fadeleteall'))
  {
    var r = confirm("Are You sure you want to delete all the attachments and Do you want to Remove the Liability Recorded and update it later?");
    if (r == true) {
      var taskid = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/task_delete_all_image'); ?>",
          type:"get",
          data:{taskid:taskid},
          success: function(result) {
            $(e.target).parents("td:first").find(".submit_dropzone").attr("data-val", "1");
            $(e.target).parents("td:first").find(".submit_dropzone").trigger("click");
            $(e.target).parents("td:first").find(".submit_dropzone_attach").trigger("click");
          }
      });
    }
  }
  if($(e.target).hasClass('fadeleteall_attachments'))
  {
    var r = confirm("Are You sure you want to delete all the attachments?");
    if (r == true) {
      var taskid = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/task_delete_all_image_attachments'); ?>",
          type:"get",
          data:{taskid:taskid},
          success: function(result) {
            $(e.target).parents("td:first").find(".submit_dropzone").attr("data-val", "1");
            $(e.target).parents("td:first").find(".submit_dropzone").trigger("click");
            $(e.target).parents("td:first").find(".submit_dropzone_attach").trigger("click");
          }
      });
    }
  }
  if($(e.target).hasClass('fa-plus'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    var length = $(e.target).parents(".special_div").find('.dz-preview').length;
    if(length > 0)
    {
      $(".dz-message").parents(".dropzone").addClass("dz-started");
    }
    else{
      $(".dz-preview").detach();
      $(".dz-message").parents(".dropzone").removeClass("dz-started");
    }
  }
  else if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  if($(e.target).hasClass('fa-pencil-square'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('image_file'))
  {
    $(e.target).parents('td').find('.img_div').toggle();
  }
  if($(e.target).hasClass("dropzone"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }
  if($(e.target).hasClass("remove_dropzone_attach"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }
  if($(e.target).parent().hasClass("dz-message"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }
  if($(e.target).hasClass('notepad_contents'))
  {
    $(e.target).parents('td').find('.notepad_div').toggle();
  }
  if($(e.target).hasClass('task_started_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_started_checkbox'); ?>",
        type:"get",
        data:{task_started:1,id:id},
        success: function(result) {
          $(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_started_checkbox'); ?>",
        type:"get",
        data:{task_started:0,id:id},
        success: function(result) {
          $(e.target).parents('tr').find('.task_label').css({'font-weight':'600'});
        }
      });
    }
  }
  if($(e.target).hasClass('enterhours_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_enterhours'); ?>",
        type:"get",
        data:{enterhouse:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_enterhours'); ?>",
        type:"get",
        data:{enterhouse:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('holiday_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_holiday'); ?>",
        type:"get",
        data:{holiday:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_holiday'); ?>",
        type:"get",
        data:{holiday:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('process_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_process'); ?>",
        type:"get",
        data:{process:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_process'); ?>",
        type:"get",
        data:{process:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('payslips_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_payslips'); ?>",
        type:"get",
        data:{payslips:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_payslips'); ?>",
        type:"get",
        data:{payslips:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('email_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_email'); ?>",
        type:"get",
        data:{email:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_email'); ?>",
        type:"get",
        data:{email:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('upload_checkbox'))
  {
    var id = $(e.target).attr('data-element');
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/task_upload'); ?>",
        type:"get",
        data:{upload:1,id:id},
        success: function(result) {
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/task_upload'); ?>",
        type:"get",
        data:{upload:0,id:id},
        success: function(result) {
        }
      });
    }
  }
  if($(e.target).hasClass('single_notify')){
    var taskid = $(e.target).attr("data-element");
    $(".model_notify").modal("show");
    $(".notify_title").html('Send Notification to Selected Staffs');
    $(".notify_task_id").val(taskid);
    $(".notify_id_class").prop("checked", false);
    $(".notify_id_class").prop("disabled", false);
    $("#notity_selectall").prop("checked", false);
    $("#notity_selectall").prop("disabled", false);
  }
  if($(e.target).hasClass('all_notify')){
    var taskid = $(e.target).attr("data-element");
    $(".model_notify").modal("show");
    $(".notify_title").html('Send Notification to All Staffs');
    $(".notify_task_id").val(taskid);
    $(".notify_id_class").prop("checked", true);
    $(".notify_id_class").attr("disabled", true);
    $("#notity_selectall").prop("checked", true);
    $("#notity_selectall").prop("disabled", true);
  }
  if(e.target.id == "notity_selectall"){
    if($(e.target).is(":checked"))
    {
      $(".notify_id_class").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".notify_id_class").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  /*
  if($(e.target).hasClass('donot_complete')) {
    $("body").addClass("loading");
    var taskid = $(e.target).attr("data-element");
    $.ajax({
          url:"<?php echo URL::to('user/task_complete_update'); ?>",
          data:{status:1,id:taskid},
          success: function(result) {
              $(e.target).parents('tr').find("select").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find("textarea").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find("input").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find('.fa-trash').each(function() {
                $(this).addClass('disabled');
              });             
              $(e.target).parents('tr').find('.fa-plus').addClass('disabled');
              $(e.target).parents('tr').find('.fa-pencil-square').addClass('disabled');
              $(e.target).parents('tr').find('.fa-minus-square').addClass('disabled');
              $(e.target).parents('tr').find('.task_label').addClass('disabled');
              $(e.target).parents('tr').find('.edit_task').addClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');
              $(e.target).parents('tr').find('.task_delete').addClass('disabled');
              $(e.target).parents('tr').find('.single_notify').addClass('disabled');
              $(e.target).parents('tr').find('.all_notify').addClass('disabled');            
              $(e.target).parents('tr').find('.email_unsent').addClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').parent().addClass('disabled');
              $(e.target).parents('tr').find('.fa-check').parent().addClass('disabled');
              $(e.target).parents('tr').find('.fa-times').parent().addClass('disabled');
              $(e.target).removeClass('fa-exclamation-triangle');
              $(e.target).removeClass('donot_complete');
              $(e.target).addClass('fa-ban');
              $(e.target).addClass('do_complete');
              $(e.target).parent().css({'color':'#f00'});
              $(e.target).parents('tr').find('.task_label').css({'color':'#1b0fd4','font-weight':'800'});
              $(e.target).parents('tr').find('.task_started_checkbox').prop("checked",false);
              if($("#show_incomplete").is(':checked'))
              {
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').hide();
                    }
                });
              }
              else{
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').show();
                    }
                });
              }
              $("body").removeClass("loading");
            }
    })
  }
  */
if($(e.target).hasClass('donot_complete')) {
  var taskid = $(e.target).attr("data-element");
  $.ajax({
        url:"<?php echo URL::to('user/donot_complete_task_details'); ?>",
        type:"get",
        data:{taskid:taskid},
        success: function(result) {
          $(".dont_task_name").html(result);
          $(".donot_id_class").val(taskid);
          $(".model_don_not_complete").modal("show");
        }
  });
}
if($(e.target).hasClass('dontvale_class')) {
  var value = $(e.target).attr("value");
  $(".dontvale").val(value);
}
if($(e.target).hasClass('donot_submit_new'))
  {
    var check_option = $(".dontvale_class:checked").val();
    if(check_option === "" || typeof check_option === "undefined")
    {
      alert("Please select any one type.");
    }
    else{
      $("body").addClass("loading");
      var taskid = $(".donot_id_class").val();
      var dontvale = $(".dontvale").val();  
      var proceedfurther = 0;
      if(dontvale == "2") {
        if($("#bi_payroll_"+taskid).is(":checked")) {
          var r = confirm("Bi-Period Payroll Status will be disabled?");
          if(r){
            var proceedfurther = 0;
          }
          else {
            var proceedfurther = 1;
            $("body").removeClass("loading");
            return false;
          }
        }
        else{
          var proceedfurther = 0;
        }
      }
      if(proceedfurther == 0) {
        $.ajax({
              url:"<?php echo URL::to('user/task_complete_update_new'); ?>",
              data:{status:1,id:taskid, dontvale:dontvale},
              success: function(result) {
                  $(".model_don_not_complete").modal("hide");
                  $('#taskidtr_'+taskid).find("select").each(function(){
                    $(this).prop('disabled',true);
                  });
                  $('#taskidtr_'+taskid).find("textarea").each(function(){
                    $(this).prop('disabled',true);
                  });
                  $('#taskidtr_'+taskid).find("input").each(function(){
                    $(this).prop('disabled',true);
                  });
                  $('#taskidtr_'+taskid).find('.fa-trash').each(function() {
                    $(this).addClass('disabled');
                  });             
                  $('#taskidtr_'+taskid).find('.fa-plus').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-pencil-square').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-minus-square').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.task_label').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.edit_task').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-files-o').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.task_delete').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.single_notify').addClass('disabled');
                  $('#taskidtr_'+taskid).find('.all_notify').addClass('disabled');      
                  $('#taskidtr_'+taskid).find('.fa-files-o').parent().addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-check').parent().addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-times').parent().addClass('disabled');
                  $('#taskidtr_'+taskid).find('.fa-exclamation-triangle').removeClass('fa-exclamation-triangle');
                   $('#taskidtr_'+taskid).find('.donot_complete').addClass('do_complete');
                  $('#taskidtr_'+taskid).find('.do_complete').removeClass('donot_complete');
                  $('#taskidtr_'+taskid).find('.do_complete').addClass('fa-ban');
                  $('#taskidtr_'+taskid).find('.do_complete').parent().css({'color':'#f00'});
                  $('#taskidtr_'+taskid).find('.task_label').css({'color':'#1b0fd4','font-weight':'800'});
                  $('#taskidtr_'+taskid).find('.task_started_checkbox').prop("checked",false);
                  if($("#show_incomplete").is(':checked'))
                  {
                    $(".edit_task").each(function() {
                        if($(this).hasClass('disabled'))
                        {
                          $(this).parents('tr').hide();
                        }
                    });
                  }
                  else{
                    $(".edit_task").each(function() {
                        if($(this).hasClass('disabled'))
                        {
                          $(this).parents('tr').show();
                        }
                    });
                  }
                  $("body").removeClass("loading");
              }
        })
      }
    }
}
if($(e.target).hasClass('do_complete')){
    $("body").addClass("loading");
    var taskid = $(e.target).attr("data-element");
    $.ajax({
          url:"<?php echo URL::to('user/task_complete_update'); ?>",
          data:{status:0,id:taskid},
          success: function(result) {
              $(e.target).parents('tr').find("select").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find("textarea").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find("input").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find('.fa-trash').each(function() {
                $(this).removeClass('disabled');
              });
              $(e.target).parents('tr').find('.fa-plus').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-pencil-square').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-minus-square').removeClass('disabled');
              $(e.target).parents('tr').find('.task_label').removeClass('disabled');
              $(e.target).parents('tr').find('.edit_task').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');
              $(e.target).parents('tr').find('.task_delete').removeClass('disabled');
              $(e.target).parents('tr').find('.single_notify').removeClass('disabled');
              $(e.target).parents('tr').find('.all_notify').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').parent().removeClass('disabled');
              $(e.target).parents('tr').find('.fa-check').parent().removeClass('disabled');
              $(e.target).parents('tr').find('.fa-times').parent().removeClass('disabled');
              $(e.target).removeClass('fa-ban');
              $(e.target).removeClass('do_complete');
              $(e.target).parent().css({'color':'#000'});
              $(e.target).parents('tr').find('.task_label').css({'font-weight':'800'});
              $(e.target).addClass('fa-exclamation-triangle');
              $(e.target).addClass('donot_complete');
              if($("#show_incomplete").is(':checked'))
              {
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').hide();
                    }
                });
              }
              else{
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').show();
                    }
                });
              }
              $("body").removeClass("loading");
            }
    })
  }
  if($(e.target).hasClass("notify_all_clients_tasks"))
  {
    $("body").addClass("loading");
    $(".model_notify").modal("hide");
    var task_id = $(".notify_task_id").val();
    var emails = [];
    var clientids = [];
    var toemails = '';
    var task_id = $(".notify_task_id").val();
    $(".notify_id_class").each(function(i, el) {
        if($(el).is(':checked'))
        {
          var user_email = $(el).attr('data-element');
          var user_id = $(el).attr('data-value');
          if(user_email != '' && typeof user_email !== 'undefined')
          {
            if($.inArray(user_email, emails) == -1)
            {
              emails.push(user_email);
              if(toemails == '')
              {
                toemails= user_email;
              }
              else{
                toemails = toemails+', '+user_email;
              }
            }
          }
          if(user_id != '' && typeof user_id !== 'undefined')
          {
            if($.inArray(user_id, clientids) == -1)
            {
              clientids.push(user_id);
            }
          }
        }
    });
    toemails = toemails+', <?php echo $admin_cc; ?>';
    var option_length = emails.length;
    $.each( emails, function( i, value ) {
        setTimeout(function(){
          $.ajax({
            url:"<?php echo URL::to('user/email_notify_tasks_pdf'); ?>",
            type:"get",
            data:{email:value,clientid:clientids[i],toemails:toemails,task_id:task_id},
            success: function(result) {
              var keyi = parseInt(i) + parseInt(1);
              if(option_length == keyi)
              {
                $("body").removeClass("loading");
                $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
              }
            }
          });
        },2000 + ( i * 2000 ));
    }); 
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
$(".image_file").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '<label class="attachments_label">Attachments : </label>';
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
  }
  $(this).parent().find(".image_div_attachments").html(htmlcontent);
});
$(window).change(function(e) {
  if($(e.target).hasClass('select_scheme'))
  {
    var task_id = $(e.target).attr("data-element");
    var scheme = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/set_scheme_for_task'); ?>",
      type:"post",
      data:{task_id:task_id,scheme:scheme},
      success:function(result)
      {
      }
    })
  }
  if(e.target.id == 'select_year_type')
  {
    var year = $("#hidden_copy_year").val();
    var id = $("#hidden_task_id").val();
    if($(e.target).val() == 'weekly')
    {
      $.ajax({
          url:"<?php echo URL::to('user/get_week_by_year'); ?>",
          type:"get",
          data:{year:year,id:id},
          success: function(result) {
            $(".weekly_select").html(result);
          }
      });
    }
    else{
      $.ajax({
          url:"<?php echo URL::to('user/get_month_by_year'); ?>",
          type:"get",
          data:{year:year,id:id},
          success: function(result) {
            $(".weekly_select").html(result);
          }
      });
    }
  }
  if($(e.target).hasClass('date_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_date_update'); ?>",
        type:"get",
        data:{date:input_val,id:id},
        success: function(result) {
        }
      });
  }
  // if($(e.target).hasClass('task_email_input'))
  // {
  //   var input_val = $(e.target).val();
  //   var id = $(e.target).attr('data-element');
  //   $.ajax({
  //       url:"<?php echo URL::to('user/task_email_update'); ?>",
  //       type:"get",
  //       data:{email:input_val,id:id},
  //       success: function(result) {
  //       }
  //     });
  // }
  if($(e.target).hasClass('uname_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_users_update'); ?>",
        type:"get",
        data:{users:input_val,id:id},
        success: function(result) {
          $(e.target).parents("tr").find('.initial_input').val(input_val);
        }
      });
  }
  if($(e.target).hasClass('initial_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_users_update'); ?>",
        type:"get",
        data:{users:input_val,id:id},
        success: function(result) {
          $(e.target).parents("tr").find('.uname_input').val(input_val);
        }
      });
  }
  if($(e.target).hasClass('default_staff'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_default_users_update'); ?>",
        type:"get",
        data:{users:input_val,id:id},
        success: function(result) {
          //$(e.target).parents("tr").find('.initial_input').val(input_val);
        }
      });
  }
  if($(e.target).hasClass('classified_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_classified_update'); ?>",
        type:"get",
        data:{classified:input_val,id:id},
        success: function(result) {
        }
      });
  }
});
//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.comments_input');
//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
});
//user is "finished typing," do something
function doneTyping (input,id) {
  $.ajax({
        url:"<?php echo URL::to('user/task_comments_update'); ?>",
        type:"get",
        data:{comments:input,id:id},
        success: function(result) {
        }
      });
}
var $input = $('.liability_input');
//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_liability, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_liability, doneTypingInterval,input_val,id);
});
//user is "finished typing," do something
function doneTyping_liability (input,id) {
  $.ajax({
        url:"<?php echo URL::to('user/task_liability_update'); ?>",
        type:"get",
        data:{liability:input,id:id},
        success: function(result) {
        }
      });
}
$(window).focusout(function(e) {
  if($(e.target).hasClass('liability_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_liability_update'); ?>",
        type:"get",
        data:{liability:input_val,id:id},
        success: function(result) {
        }
      });
  }
});
</script>
<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
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
          $("#client_search").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/task_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              /*$("#clients_tbody").html(result);
              $("#client_expand_paginate").hide();
              $(".dataTables_info").hide();*/
              $("#hidden_client_id").val(ui.item.id);
              $(".tax_reg1class").val(result['taxreg']);
              $(".primaryemail_class").val(result['primaryemail']);
              $(".firstname_class").val(result['firstname']);
              $('#alert_modal').modal({backdrop: 'static', keyboard: false});
            }
          })
        }
    });
     $(".client_search_class_edit").autocomplete({
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
          $("#client_search_edit").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/task_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              /*$("#clients_tbody").html(result);
              $("#client_expand_paginate").hide();
              $(".dataTables_info").hide();*/
              $("#hidden_client_id_edit").val(ui.item.id);
              $(".tax_reg1class_edit").val(result['taxreg']);
              $(".primaryemail_class_edit").val(result['primaryemail']);
              $(".firstname_class_edit").val(result['firstname']);
              $('#alert_modal_edit').modal({backdrop: 'static', keyboard: false});
            }
          })
        }
    });
});
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