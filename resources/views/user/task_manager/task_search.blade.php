@extends('userheader')
@section('content')
<style>
  .quick_links img{
    width: 34px;
    transition: 0.5s all ease-in-out;
  }
  .quick_links:hover img{
    transform: scale(1.5);
  }
  .quick_links:hover{
    color:yellow;
    background-color:black;
  }
  
.menu-logo{
  margin-left: 14px;
}
.tablefixedheader {
      text-align: left;
      position: relative;
      border-collapse: collapse; 
    }
    .tablefixedheader thead tr th {
      position: sticky;
        top: 0;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        background-color: #fff !important;
    }
</style>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
.margintop20{
  margin-top:20px !important;
  margin-bottom: 0px !important;
}
.table_layout { border:0px; }
.table_layout #task_body_layout td{padding:0px !important;}
  .start_group{clear:both;}
.last_td_display .common_black_button { padding:8px 4px !important; }
.tasks_drop {text-align: left !important; }
.existing_comments > p { margin-bottom: 0px !important; }
#consolidate_task_details > p { margin-bottom: 0px !important; }
/* Chrome, Safari, Edge, Opera */
#colorbox, #cboxWrapper { z-index:99999999999; }
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.fa-sort{ cursor:pointer; }
.hidden_tasks_tr>td{
	cursor:pointer;
}
.error{
  color:#f00;
}
#open_closed_spam{
  color:#f00;
  line-height: 17px
}
#recurring_spam{
  color:#f00;
  line-height: 17px
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.file_attachments{
  color: #002bff;
    text-decoration: underline;
    font-weight: 700;
}
.link_infile{
  color: #002bff;
    text-decoration: underline;
    font-weight: 700;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999;
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
    z-index:    999999999999;
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
.disclose_label{ width:300px; }
.option_label{width:100%;}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}
.table_bg>tbody>tr>td, .table_bg>tbody>tr>th, .table_bg>tfoot>tr>td, .table_bg>tfoot>tr>th, .table_bg>thead>tr>td
{
  border-top: 0px solid;
  color:#000 !important;
  text-align: left;
  font-weight:600;
  padding: 6px 10px;
  background: #f5f5f5;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td
{
  border-top: 0px solid;
  color:#000 !important;
  text-align: left;
  font-weight:600;
  padding: 6px 10px;
  background: #f5f5f5;
  font-size:15px;
}
.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}
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
.avoid_email{ color:green; font-size:18px;}
.retain_email { color: #f00 !important; }

.open_layout_div{width:99%;margin: 0px auto; top: 244px; z-index: 9}
.open_layout_div_change::before{width: 100%; height: 30px; content: ""; position: fixed; margin-top: 300px; z-index: 99}
.table_layout { border:0px; }
.bootstrap-datetimepicker-widget { z-index:9999999999999 !important; }
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">

<div class="modal fade park_task_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Choose Park Date</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">  
            <div class="col-md-12">
              <label>Until What Date Do you want to Park this task? </label>
            </div>
            <div class="col-md-12">
              <input type="text" name="park_task_date" class="form-control park_task_date" id="park_task_date" value="" placeholder="Choose Date to Park">
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="hidden_task_id_park_task" name="hidden_task_id_park_task" value="">
            <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
            <input type="button" class="common_black_button" id="park_submit" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade change_taskname_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Edit Task Name</h4>
          </div>
          <div class="modal-body" style="min-height: 95px;">  
            <div class="row">
              <div class="col-md-12 internal_tasks_group">
                <label style="margin-top:5px">Select Task:</label>
              </div>
              <div class="col-md-12 internal_tasks_group_change">
                <div class="dropdown" style="width: 100%">
                  <a class="btn btn-default dropdown-toggle tasks_drop_change" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                    <span class="task-choose_internal_change">Select Task</span>  <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu internal_task_details_change" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                    <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_change">Select Task</a></li>
                      <?php
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
                        <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_change" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                      <?php
                        }
                      }
                      ?>
                  </ul>
                  <input type="hidden" name="idtask_change" id="idtask_change" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="hidden_task_id_change_task" name="hidden_task_id_change_task" value="">
            <input type="button" class="common_black_button" id="change_taskname_button" value="Submit">
          </div>
        </div>
  </div>
</div> 
<div class="modal fade question_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Alert</h4>
          </div>
          <div class="modal-body" style="min-height: 200px;">  
            <div class="col-md-12">
              <label>Do you want to carry over ALL of the task specifics from the original task or just the Original Task Specifics. Select Yes for the all the task specifics or Select No for just the Original Task Specifics. </label>
            </div>
            <div class="col-md-12">
              <input type="radio" name="copy_task_specifics" class="copy_task_specifics" id="copy_task_specifics_yes" value="1"><label for="copy_task_specifics_yes">Yes</label>
              <input type="radio" name="copy_task_specifics" class="copy_task_specifics" id="copy_task_specifics_no" value="2" checked><label for="copy_task_specifics_no">No</label>
            </div>
            <div class="col-md-12">
              <label>Do you want attach some or all of the files to this Task? </label>
            </div>
            <div class="col-md-12">
              <input type="radio" name="copy_task_files" class="copy_task_files" id="copy_task_files_yes" value="1"><label for="copy_task_files_yes">Yes</label>
              <input type="radio" name="copy_task_files" class="copy_task_files" id="copy_task_files_no" value="2" checked><label for="copy_task_files_no">No</label>
            </div>

            <div class="hide_taskmanager_files">
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="hidden_task_id_copy_task" name="hidden_task_id_copy_task" value="">
            <input type="button" class="common_black_button" id="question_submit" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  

          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>

<div class="modal fade due_date_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Edit Due Date</h4>
          </div>
          <div class="modal-body" style="min-height: 193px;">  
            <p class="col-md-12">You are changing the due date of the task</p>
            <label class="col-md-4">Subject:</label>
            <label class="col-md-8 subject_due_date"></label>
            <br/>
            <label class="col-md-4">Current Due Date:</label>
            <div class="col-md-8">
              <input type="text" name="current_due_date" class="form-control current_due_date" value="" readonly style="font-weight:700">
            </div>
            <br/>
            <br/>
            <label class="col-md-4" style="margin-top:15px">New Due Date:</label>
            <div class="col-md-8" style="margin-top:15px">
              <input type="text" name="new_due_date" class="form-control new_due_date due_date_edit" value="20-Mar-20">
            </div>
            <p class="col-md-12" style="color:#f00">WARNING:  The Author of the task will be notified of the change.</p>
          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_task_id_due_date" id="hidden_task_id_due_date" value="">
            <input type="button" class="common_black_button" id="due_date_change_button" value="Apply New Due Date">
          </div>
        </div>
  </div>
</div>
<div class="modal fade allocation_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;">
        <div class="modal-content">
          <div class="modal-header allocation_body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Allocation</h4>
          </div>
          <div class="modal-body allocation_body" style="min-height: 195px;">
            <label class="col-md-3" style="padding:0px">Task Subject:</label>
            <label class="col-md-9 subject_allocation"></label>
            <br/>
            <label class="col-md-3" style="margin-top:15px;padding:0px">Current Allocation:</label>
            <div class="col-md-9" style="margin-top:15px">
              <select name="current_allocation" class="form-control current_allocation" disabled>
                <option value="">Select User</option>        
                  <?php
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
            <br/>
            <br/>
            <label class="col-md-3" style="margin-top:15px;padding:0px">Allocate this task to:</label>
            <div class="col-md-9" style="margin-top:15px">
              <select name="new_allocation" class="form-control new_allocation">
                <option value="">Select User</option>        
                  <?php
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
          </div>
          <div class="modal-footer allocation_body">  
            <input type="hidden" name="hidden_task_id_allocation" id="hidden_task_id_allocation" value="">
            <input type="hidden" name="hidden_task_id_auto_close" id="hidden_task_id_auto_close" value="">
            <input type="button" class="common_black_button" id="allocate_now" value="Allocate Now">
          </div>
          <div class="modal-header history_body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="text-align: center">Task Allocation History</h4>
          </div>
          <div class="modal-body history_body" id="history_body">
          </div>
          <div class="modal-footer history_body">  
            <input type="hidden" name="hidden_task_id_history" id="hidden_task_id_history" value="">
            <input type="button" class="common_black_button export_csv_history" id="export_csv_history" value="Export CSV">
            <input type="button" class="common_black_button export_pdf_history" id="export_pdf_history" value="Export PDF">
          </div>
        </div>
  </div>
</div>

<div class="modal fade task_specifics_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:40%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close close_task_specifics" data-dismiss="modal" aria-label="Close"><span class="close_task_specifics" aria-hidden="true">&times;</span></button>
            <div class="row">
              <div class="col-md-11">
                <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Specifics: <spam class="task_title_spec"></spam>
                <a href="" target="_blank" class="fa fa-expand view_full_task" data-element="" 
                          title="View Task" style="padding-left:15px;font-size:20px;font-weight: 500;float: right;margin-right: -3%;"></a></h4>
              </div>
            </div>
            <h5 class="title_task_details" style="font-size:18px;font-weight:600;text-indent: 14px;"></h5>
            <div class="user_ratings_div"></div>
          </div>
          <div class="modal-body" style="min-height: 193px;padding: 5px;">
            <label class="col-md-12" style="padding: 0px;">
              <label style="margin-top:10px">Existing Task Specific Comments:</label>
              <span style="margin-left:40px; font-size:30px;" id="place_mui_icons"> </span> 
              <a href="javascript:" class="common_black_button download_pdf_spec" style="float: right;">Download as PDF</a> 
              <img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" class="2bill_image 2bill_image_comments" style="width:32px;margin-left:10px;float:right;margin-top: 4px;display:none" title="this is a 2Bill Task">
            </label>
            <div class="col-md-12" style="padding: 0px;">
              <div class="existing_comments" id="existing_comments" style="width:100%;background: #c7c7c7;padding:10px;min-height:300px;height:300px;overflow-y: scroll;font-size:16px"></div>
            </div>

            <label class="col-md-12" style="margin-top:15px;padding: 0px">New Comment:</label>
            <div class="col-md-12" style="padding: 0px">
              <textarea name="new_comment" class="form-control new_comment" id="editor_1" style="height:150px"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="padding: 18px 5px;">  
            <input type="hidden" name="hidden_task_id_task_specifics" id="hidden_task_id_task_specifics" value="">
            <input type="hidden" name="show_auto_close_msg" id="show_auto_close_msg" value="">
            
            <div class="col-md-12" style="padding:0px;margin-top:10px">
              <input type="button" class="common_black_button add_comment_allocate_to_btn" value="Add Comment and Allocate To" style="float: left;font-size:12px">
              <select name="add_comment_allocate_to" class="form-control add_comment_allocate_to" style="float: left;width:20%;font-size:12px">
                <option value="">Select User</option>
                <?php
                  if(($userlist)){
                    foreach ($userlist as $user) {
                  ?>
                    <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                  <?php
                    }
                  }
                ?>
              </select>

              <input type="button" class="common_black_button add_task_specifics" id="add_task_specifics" value="Add Comment Now" style="float: right;font-size:12px">
              <input type="button" class="common_black_button add_comment_and_allocate" id="add_comment_and_allocate" value="Add Comment and Allocate Back" style="float: right;font-size:12px">
              
              <div class="col-md-6" style="float:left;margin-top:10px;padding:0px">
                <input type="button" class="common_black_button add_progress_files_from_task_specifics" id="add_progress_files_from_task_specifics" value="Add Progress Files" data-element="" style="float: left;font-size:12px">
                <spam class="progress_spam" style="font-weight:600;color:green;margin-top:10px"></spam>
              </div>
              <div class="col-md-6" style="float:right;margin-top:10px;padding:0px">
                  <input type='checkbox' name="auto_close_task_comment" class="auto_close_task_comment" id="auto_close_task_comment" value="1"/> <label for="auto_close_task_comment" style="margin-top: 10px;">Make this task is an Auto Close Task</label>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>

<div class="modal fade infiles_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_progress_body">  

          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_progress_infiles_task_id" id="hidden_progress_infiles_task_id" class="hidden_progress_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_infile_progress_button" value="Submit">
          </div>
        </div>
  </div>
</div>

<div class="modal fade infiles_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_completion_body">  

          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_completion_infiles_task_id" id="hidden_completion_infiles_task_id" class="hidden_completion_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_infile_completion_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade yearend_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link YearEnd Documents</h4>
          </div>
          <div class="modal-body" id="yearend_completion_body">  

          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_completion_yearend_task_id" id="hidden_completion_yearend_task_id" class="hidden_completion_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_yearend_completion_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<!-- <div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_job_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row margintop20"> 
                <div class="col-md-2">
                  <label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-3">
                  <select name="select_user" class="form-control select_user_author" required>
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
                <div class="col-md-8 client_group">
                  <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required>
                  <input type="hidden" id="client_search" name="clientid" />
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
                    <input type='checkbox' name="internal_checkbox" id="internal_checkbox" value="1"/>
                    <label for="internal_checkbox">Internal</label>
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
                    <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
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
            <div class="form-group start_group task_specifics_copy margintop20">
                <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
                <div class="task_specifics_copy_val" style="width:100%;height:400px;background: #e2e2e2;min-height: 400px;overflow-y: scroll;padding: 7px;"></div>
                
                <input type="hidden" name="hidden_task_specifics" id="hidden_task_specifics" value="">
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
            <div class="row margintop20" style="clear: both; padding-top: 10px;">
              <div class="col-lg-8">
                <div class="form-group start_group">
                  <label>Task Files: </label>
                  <a href="javascript:" class="fa fa-plus fa-plus-add" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
                  <a href="javascript:" class="fa fa-pencil-square fanotepadadd" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
                  <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
                  <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
                  <div class="img_div img_div_add" style="z-index:9999999; min-height: 275px">
                    <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                    @csrf
</form>
                    <div class="image_div_attachments">
                      <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                      <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="">
                      @csrf
</form>              
                    </div>
                   </div>
                   <div class="notepad_div_notes_add" style="z-index:9999; position:absolute;display:none">
                      <textarea name="notepad_contents_add" class="form-control notepad_contents_add" placeholder="Enter Contents"></textarea>
                      <input type="button" name="notepad_submit_add" class="btn btn-sm btn-primary notepad_submit_add" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files_notepad_add"></spam>
                  </div>
                </div>
                
                <p id="attachments_text" style="display:none; font-weight: bold;">Files Attached:</p>
                <div id="add_attachments_div">
                </div>
                <div id="add_notepad_attachments_div">
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
                    <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                    <div class="accept_recurring_div">
                      <p>This Task is repeated:</p>
                      <div class="form-title">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                        <label for="recurring_checkbox1">Monthly</label>
                      </div>
                      <div class="form-title">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                        <label for="recurring_checkbox2">Weekly</label>
                      </div>
                      <div class="form-title">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                        <label for="recurring_checkbox3">Daily</label>
                      </div>
                      <div class="form-title">
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
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Progress Files Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label>Select User</label>
                    <select class="form-control files_user_drop" required>
                      <option value="">Select User</option>
                      <?php
                      $selected = '';                      
                      if(($userlist)){
                        foreach ($userlist as $user) {
                          if(Session::has('taskmanager_user'))
                          {
                            if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }

                      ?>
                        <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                    <spam class="error_files_user_drop"></spam>
                  </div>
                  
                </div>
                <div class="col-lg-12">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">

                      <?php
                      if(Session::has('taskmanager_user'))
                          {
                            $session_user_id = Session::get('taskmanager_user');
                          }
                          else{
                            $session_user_id = '';
                          }
                      ?>
                      
                        <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_progress'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">                            
                            <input name="hidden_task_id_progress" id="hidden_task_id_progress" type="hidden" value="">
                            <input type="hidden" value="<?php echo $session_user_id?>" id="files_user_hidden" name="user_id">
                        @csrf
</form>

                        <div class="add_progress_attachments" style="display:none">

                        </div>
                     </div>
                  </div>
                  
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>

<div class="modal fade dropzone_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Completion Files Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_completion">
                 <div class="image_div_attachments_completion">
                    <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_completion'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload2" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_task_id_completion" id="hidden_task_id_completion" type="hidden" value="">
                    @csrf
</form>
                 </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade consolidate_select_check_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Consolidation Process</h4>
          </div>
          <div class="modal-body">  
              <div class="" style="margin-top: 20px;">
                 <div class="">
                    <input type="radio" name="selet_filter_type" class="select_filter_type" id="consolidate_by_taskid" value="1"><label for="consolidate_by_taskid">Consolidate by Task ID</label>
                    <p style="width: 95%;margin-left: 4%;"> This process takes each task based on the Task ID and consolidates them into one task in sequence by Task ID.</p>
                 </div>
                 <div>
                   <input type="radio" name="selet_filter_type" class="select_filter_type select_filter_type_date" id="consolidate_by_date" value="0"><label for="consolidate_by_date">Consolidate by Date</label>
                   <p style="width: 95%;margin-left: 4%;">This process takes each entry into the selected tasks and arranges them in order of the date they were entered, sorted by Date first, then by Time (if available), and finally by Task ID.</p>
                 </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-primary consolidation-process" align="left" style="margin-left:7px; float:right;background: #000;">Consolidate Now</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade consolidation_task" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-sm" role="document" style="width:70%;height:auto;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Consolidated Tasks</h4>
          </div>
          <div class="modal-body">  
              <div class="consolidate-table">
                <div class="consolidate-table-div" style="max-height: 300px;overflow-y: scroll;scrollbar-color: #3db0e6 #fff;scrollbar-width: thin;margin-bottom: 33px;">
                  <table class="table tablefixedheader own-white-table" style="width:100%;float:left;">
                    <thead class="header ">
                      <tr>
                           <th><input type="checkbox" name="select-all-consolidate" class="select_allConsolidatecheckbox" id="select_allConsolidatecheckbox"><label for="select_allConsolidatecheckbox" style="line-height: 22px;">Select All </label></th>
                           <th style="line-height: 32px;">Task ID</th>
                           <th style="line-height: 32px;">Client/Task Name</th>
                           <th style="line-height: 32px;">Subject</th>
                           <th style="line-height: 32px;">Status</th>
                      </tr>
                    </thead>
                    <tbody id="task_body_consolidate_layout">
                      
                    </tbody>
                  </table>
                </div>
            <b id="consolidate_output_tab" style="display:none;">Consolidated Output:</b>
            <div id="consolidate_task_details" style="width:100%;background: #c7c7c7;padding:10px;margin-top:20px;min-height:300px;height:300px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;font-size: 18px; display:none;"></div>
              </div>
              
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-primary consolidate_submit_data_btn common_black_button" style="float:right;">Consolidate</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade integrity_check_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Integrity Check</h4>
          </div>
          <div class="modal-body" id="integrity_check_tbody" style="clear:both;">  
              
          </div>
          <div class="modal-footer" style="clear:both;">  
            
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div id="fixed-header" style="width:100%;position: fixed; background: #f5f5f5;z-index:999">
    <div class="page_title">
      <div class="row padding_00 new_main_title" style="padding-bottom: 6px">
        <h4 class="col-lg-2 menu-logo" style="width: 15%;font-size: 30px;background-size: 39px 32px;">Task Manager</h4>
        <div class="col-lg-10 col-md-10 alert_message_div">
          <?php
          if(Session::has('message')) { ?>
              <p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 92%;"><?php echo Session::get('message'); ?> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 30px;">&times;</span>
                </button>
              </p>
          <?php Session::forget('message'); }
          if(Session::has('error')) { ?>
              <p class="alert alert-danger" style="font-size: 17px;margin: 0px;width: 92%;"><?php echo Session::get('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 30px;">&times;</span>
                </button>
              </p>
          <?php Session::forget('error'); }
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-2 padding_00">
          <label class="col-lg-2" style="text-align: right; line-height: 35px;">User:</label>
          <div class="col-lg-8">
            <select name="select_user" class="form-control select_user_home">
              <option value="">Select User</option>        
                <?php
                $selected = '';
                if(($userlist)){
                  foreach ($userlist as $user) {
                    if(Session::has('taskmanager_user'))
                    {
                      if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
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
          <label class="col-lg-1"><a href="javascript:" class="fa fa-refresh refresh_task" title="Refresh Tasks for this user" style="padding:10px;background: #dfdfdf"></a></label>
        </div>
        <div class="col-lg-2 padding_00" style="padding-top: 5px;">
          <input type="checkbox" name="select_check" class="show_authored_task_only" id="show_authored_task_only"><label for="show_authored_task_only" >Show authored tasks only</label>
        </div>
        
        <div class="col-md-7">
          <input type="button" name="consolidate" class="consolidate_task_manager common_black_button" disabled value="Consolidate Tasks"  style="float:right; ">
          <input type="button" name="export_csv" class="export_csv common_black_button" value="Export CSV" disabled style="float:right;">
          <a href="javascript:" class="common_black_button" id="create_new_task" style="float:right;">New task</a>
          <?php
          $settings = DB::table('user_login')->select('taskmanager_view')->where('id',1)->first();
          if($settings->taskmanager_view == "0"){
            $compressed_layout = 1;
            $compressed_div = 'display:block';
            $compressed_checked = 'checked';
          }elseif($settings->taskmanager_view == "1") {
            $compressed_layout = 0;
            $compressed_div = 'display:block';
            $compressed_checked = '';
          } else{
            $compressed_layout = 0;
            $compressed_checked = '';
            $compressed_div = 'display:none';
          }
          ?>
		    	<div class="compressed_layout_div" style="float:right;width:31%;<?php echo $compressed_div; ?>">
			      
	              <label class="switch" style="margin-right: 10px; float:right !important">
	                <input type="checkbox" class="compressed_layout" value="1" <?php echo $compressed_checked; ?>>
	                <span class="slider round"></span>
	              </label>
	              <input type="hidden" id="hidden_compressed_layout" value="<?php echo $compressed_layout; ?>">
                <label style="float: right; margin-right: 10px; line-height: 30px;" >Compressed Layout:</label>
          		</div>
		    </div>
      </div>
      
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_manager'); ?>" class="nav-link" id="home-tab">
            <spam>Your Open Tasks (<spam ><?php if(is_countable($open_task_count) && count($open_task_count) > 0) { echo count($open_task_count); } else { echo 0; } ?></spam>)</spam>
            <spam id="authored_task_count" style="display:none">Your Authored Tasks (<?php if(is_countable($authored_task_count) and count($authored_task_count) > 0) { echo count($authored_task_count); } else { echo 0; } ?>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/park_task'); ?>" class="nav-link" id="home-tab">
            <spam>Park Tasks (<spam><?php if(is_countable($park_task_count) && count($park_task_count) > 0) { echo count($park_task_count); } else { echo 0; } ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light active" style="width:15%;text-align: center">
          <a href="" class="nav-link" id="profile-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Task Search</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_analysis'); ?>" class="nav-link" id="profile-tab">Client Task Analysis</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_administration'); ?>" class="nav-link" id="profile-tab">Task Administration</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_overview'); ?>" class="nav-link" id="profile-tab">Task Overview</a>
        </li>
    </ul>
  </div>
    <div class="table-responsive" style="width: 100%; float: left;margin-top:250px;min-height:500px ">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">

          <div style="position:fixed;background: #fff;width: 100%;height: 103px;padding-top: 21px; z-index:999; top: 275px; ">
            <div class="col-md-2">
              <label>Search By Author:</label>
              <select name="select_user" class="form-control search_author">
                <option value="">Select User</option>        
                  <?php
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

            <div class="col-md-2 open_task_div" style="padding:0px">
              <label style="width:100%">Open/Closed Task:</label>
              <input type="radio" name="open_task_search" class="open_task_search" id="open_task_search_1" value="1" checked style="margin-top: 10px;"><label for="open_task_search_1">Open Tasks</label>
              <input type="radio" name="open_task_search" class="open_task_search" id="open_task_search_0" value="0"><label for="open_task_search_0">Closed Tasks</label>
              <input type="radio" name="open_task_search" class="open_task_search" id="open_task_search_2" value="2"><label for="open_task_search_2">All</label>
              <input type="hidden" id="hidden_open_task" value="1">
            </div>
            <div class="col-md-2" style="padding:0px">
              <label>Search By Client/Internal:</label> &nbsp; &nbsp; <input type="checkbox" name="make_internal" class="make_internal" id="make_internal" value="1"><label for="make_internal" style="float:right">Internal</label>
              <input type="text" class="form-control copy_client_search_class" placeholder="Search By Client" value="">
              <input type="hidden" id="hidden_make_internal" class="hidden_make_internal" value="0">
              <select class="form-control select_internal_tasks" id="select_internal_tasks" style="display:none;">
                <option value="">Select Tasks</option>
                <?php
                if(($taskslist)){
                  foreach ($taskslist as $single_task) {
                ?>
                  <option value="<?php echo $single_task->id; ?>"><?php echo $single_task->task_name; ?></option>
                <?php
                  }
                }
                ?>
              </select>
              <input type="hidden" id="copy_client_search" name="copy_client_search" value="">
            </div>
            <div class="col-md-2">
              <label>Search By Subject:</label>
              <input type="text" class="form-control subject_search_class" placeholder="Search By Subject" value="">
            </div>
            <div class="col-md-1 recurring_task_div">
              <label style="width:100%">Recurring Task:</label>
              <label class="switch" style="margin-right: 10px;">
                <input type="checkbox" class="recurring_task_search" value="1">
                <span class="slider round"></span>
              </label>
              <input type="hidden" id="hidden_recurring_task" value="0">
            </div>
            <div class="col-md-1">
              <label>Due Date:</label>
              <input type="text" class="form-control due_date_search_class" placeholder="DD-MMM-YYYY" value="">
            </div>
            <div class="col-md-1">
              <label>Creation Date:</label>
              <input type="text" class="form-control creation_date_search_class" placeholder="DD-MMM-YYYY" value="">
            </div>
            <div class="col-md-1">
                <input type="button" class="common_black_button search_tasks" value="Search" style="margin-top:25px"> 
            </div>
          </div>
          <div class="open_layout_div">
            <table class="table_bg table-fixed-header open_layout" style="width:100%;margin-top: 0px;">
              <tbody id="task_body_search">
                <td colspan="4" style="padding: 20px; text-align: center;"> Search For the Tasks </td>
              </tbody>
            </table>
           </div>

        	<table class="table_bg table-fixed-header table_layout" style="width:100%;float:left;display:none">
	          	<thead>
	          		<tr>
		                <td colspan="4" style="background: #fff;border: 0px">
		                  
		                </td>
		            </tr>
	          		<tr class="hidden_tasks_th" id="menulist">
	                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
	                    	<table style="width:100%">
		                    	<tr>
                            <td style="color:#000;width:10%;padding:10px; font-size:15px; font-weight:600;">Task ID<i class="fa fa-sort taskid_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
		                    		<td style="color:#000;width:35%;padding:10px; font-size:14px; font-weight:600;">Client/Task Name<i class="fa fa-sort taskname_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                              <td style="color:#000;width:10%;padding:10px; font-size:15px; font-weight:600;">Active Client<i class="fa fa-sort taskid_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                          		<td style="color:#000;width:5%;padding:10px; font-size:14px; font-weight:600;"></td>
		                    		<td style="color:#000;width:50%;padding:10px; font-size:14px; font-weight:600">Subject<i class="fa fa-sort subject_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
		                    	</tr>
		                    </table>
	                    </td>
	                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
	                    	<table style="width:100%">
		                    	<tr>
		                    		<td style="color:#000;width:60%;padding:10px; font-size:15px; font-weight:600;">Author / Allocation<i class="fa fa-sort author_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#000;width:40%;padding:10px; font-size:15px; font-weight:600">Priority<i class="fa fa-sort allocated_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
		                    	</tr>
	                    	</table>
	                    </td>
	                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
	                    	<table style="width:100%">
		                    	<tr>
		                    		<td style="color:#000;width:50%;padding:10px; font-size:14px; font-weight:600;">Due Date<i class="fa fa-sort duedate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
		                    		<td style="color:#000;width:50%;padding:10px; font-size:14px; font-weight:600">Created Date<i class="fa fa-sort createddate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
		                    	</tr>
		                    </table>
	                    </td>
	                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
	                    	<table style="width:100%">
		                    	<tr>
                            <td style="color:#000;width:25%;padding:10px; font-size:15px; font-weight:600;" title="Project Time">P.T </td>
                            <td style="color:#000;width:30%;padding:10px; font-size:15px; font-weight:600;">Progress<i class="fa fa-sort progress_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                            <td style="color:#000;width:20%;padding:10px; font-size:15px; font-weight:600">
                              Action
                            </td>
		                    	</tr>
		                    </table>
	                    </td>
	                </tr>
	          	</thead>
	          	<tbody id="task_body_layout">
	          		
	          	</tbody>
          	</table>
        </div>
      </div>
    </div>
</div>
<input type="hidden" name="taskname_sortoptions" id="taskname_sortoptions" value="asc">
<input type="hidden" name="subject_sortoptions" id="subject_sortoptions" value="asc">
<input type="hidden" name="author_sortoptions" id="author_sortoptions" value="asc">
<input type="hidden" name="allocated_sortoptions" id="allocated_sortoptions" value="asc">
<input type="hidden" name="duedate_sortoptions" id="duedate_sortoptions" value="asc">
<input type="hidden" name="createddate_sortoptions" id="createddate_sortoptions" value="asc">
<input type="hidden" name="taskid_sortoptions" id="taskid_sortoptions" value="asc">
<input type="hidden" name="progress_sortoptions" id="progress_sortoptions" value="asc">
<input type="hidden" name="redlight_sortoptions" id="redlight_sortoptions" value="asc">
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Resetting Stop Navigation.</p>
</div>
<script>
<?php
if(!empty($_GET['tr_task_id']))
{
  $divid = $_GET['tr_task_id'];
  ?>
  $(function() {
    $(document).scrollTop( $("#task_tr_<?php echo $divid; ?>").offset().top - parseInt(150) );
  });
  <?php
}
?>
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
            $("#client_search").val(ui.item.id);
          }
        })
      }
  });
 $(".copy_client_search_class").autocomplete({
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
            $("#copy_client_search").val(ui.item.id);
          }
        })
      }
  });
$(window).change(function(e) {
  if($(e.target).hasClass('select_allConsolidatecheckbox')) {
    if($(e.target).is(':checked')){
      $('.taskCheckbox').prop('checked',true);
    }else{
       $('.taskCheckbox').prop('checked',false);
    }
  }
});
$(window).click(function(e) {
  
  if($(e.target).hasClass('consolidate_submit_data_btn')) {
        var checked_count = $(".taskCheckbox:checked").length;
        if(checked_count >= 2) {
          $(".consolidate_select_check_modal").modal("show");
        }else{
          alert("You must select more than 1 task to consolidate tasks. Please select the tasks you want to consolidate.");
        }
    
  }
  if($(e.target).hasClass('consolidation-process')) {
    var checkedval = $(".select_filter_type:checked").length;

    if(checkedval > 0) {
      $(".consolidation_task").modal("hide");
      $("body").addClass("loading");
      setTimeout(function() {
        var consolidateType = $('.select_filter_type:checked').val();
        var consolidateTaskValue = '';
        $.each($(".taskCheckbox:checked"), function(){
            if(consolidateTaskValue == '') {
              consolidateTaskValue = $(this).val();
            }
            else{
              consolidateTaskValue = consolidateTaskValue+','+$(this).val();
            }
        });

        $.ajax({
            url:"<?php echo URL::to('user/consolidate_process'); ?>",
            type:"post",
            dataType:"json",
            data:{consolidateType:consolidateType,consolidateTaskValue:consolidateTaskValue},
            success:function(result)
            {
              $('#consolidate_task_details').show();
              $("#consolidate_output_tab").show();
                $("#consolidate_task_details").html(result['output']);
                $('.consolidate_select_check_modal').modal('hide');
                $(".consolidation_task").modal("show");
               $("body").removeClass("loading");
            }
        });
      },1000);
    }
    else{
      alert("Please select atleast ONE Consolidation process.");
    }
  }
  if($(e.target).hasClass('consolidate_task_manager')) {
    $("body").addClass("loading");
        setTimeout(function() {
          var author = $(".search_author").val();
          var open_task = $(".open_task_search:checked").val();
          var client_id = $("#copy_client_search").val();
          var client_id_search = $(".copy_client_search_class ").val();
          var subject = $(".subject_search_class").val();
          var recurring = $("#hidden_recurring_task").val();
          var due_date = $(".due_date_search_class").val();
          var creation_date = $(".creation_date_search_class").val();
          var make_internal = $("#hidden_make_internal").val();
          var select_tasks = $(".select_internal_tasks").val();
          var mode_consolidate = "consolidate";

          $.ajax({
            url:"<?php echo URL::to('user/search_taskmanager_consolidate'); ?>",
            type:"post",
            dataType:"json",
            data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
            success:function(result)
            
            {
              $('.consolidation_task').modal('show');
                $("#task_body_consolidate_layout").html(result['layout']);
               $("body").removeClass("loading");

                $("#consolidate_output_tab").hide();
                $("#consolidate_task_details").hide();
            }
          });
       });
    
  }
})
$(document).ready(function() {
  
  
  $(".creation_date_search_class").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
  $(".due_date_search_class").datetimepicker({
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
})
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
function event_load()
{
  $('.create_new_model').on('shown.bs.modal', function () {
      $(window).bind('beforeunload', function() {
        return 'You have unsaved changes which will not be saved.';
      });
  });
  $('.create_new_model').on('hidden.bs.modal', function () {   
    //$("body").addClass("loading_apply");
    $(window).unbind('beforeunload');
    // window.location.reload(true);
  });
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
function infile_download_bpso_all_image(ids,count,page,task_id,filename=''){
  $.ajax({
      url:"<?php echo URL::to('user/infile_download_bpso_all_image_taskmanager'); ?>",
      type:"get",
      data:{id:ids[count],page:page,filename:filename,task_id:task_id},
      dataType:"json",
      success: function(result) {
        var page_count = parseInt(page) * 500;
        var nextpage = parseInt(page) + 1;
        var prevpage = parseInt(page) - 1;
        var offset = parseInt(prevpage) * 500;

        if(result['total_count'] > page_count) {
          $("body").removeClass("loading");
          $("body").addClass("loading_bpso");
          $("#bpso_first").html(offset);
          $("#bpso_name").html(result['infile_itemname']);
          $("#bpso_last").html(result['total_count']);
          setTimeout(function() {
            infile_download_bpso_all_image(ids,count,nextpage,task_id,result['filename']);
          },1000);
        }
        else{
          $("body").removeClass("loading");
          $("body").addClass("loading_bpso");
          $("#bpso_first").html(offset);
          $("#bpso_name").html(result['infile_itemname']);
          $("#bpso_last").html(result['total_count']);
          
          var lengthids = ids.length;
          var nextcount = parseInt(count) + 1;
          if(nextcount == lengthids) {
            $("body").removeClass("loading_bpso");
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            // setTimeout(function() {
            //   $.ajax({
            //     url:"<?php echo URL::to('user/delete_file_link'); ?>",
            //     type:"post",
            //     data:{result:result['filename']},
            //     success: function(result)
            //     {

            //     }
            //   });
            // },3000);
          }
          else{
            setTimeout(function() {
              infile_download_bpso_all_image(ids,nextcount,1,task_id,result['filename']);
            },1000);
          }
        }
      }
  });
}
$(window).click(function(e) {
  event_load();
  if($(e.target).hasClass('download_all_completion_files_taskmanager')) {
    var task_id = $(e.target).attr("data-element");
    var client_id = $(e.target).attr("data-client");
    if(client_id != "") {
      $("body").addClass('loading_completion_files');
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/user_download_completion_files'); ?>",
          type:"post",
          data:{task_id:task_id,client_id:client_id},
          dataType:"json",
          success:function(result){
            var infilelength = result['infileids'].length;
            if(infilelength > 0) {
              setTimeout(function() {
                infile_download_bpso_all_image(result['infileids'],0,1,task_id,result['filename']);
              },1000);
            }
            else{
              $("body").removeClass('loading_completion_files');
              SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            }
          }
        })
      },1000);
    }
    else {
      $("body").addClass('loading_completion_files');
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/user_download_completion_files_only'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result){
            $("body").removeClass('loading_completion_files');
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
          }
        })
      },1000);
    }
  }
  if($(e.target).hasClass('download_all_infile_items_taskmanager')){
    var task_id = $(e.target).attr("data-element");
    $("body").addClass('loading');
    setTimeout(function() {
      $.ajax({
        url:"<?php echo URL::to('user/user_get_linked_infile_items'); ?>",
        type:"post",
        data:{task_id:task_id},
        dataType:"json",
        success:function(result){
          setTimeout(function() {
            infile_download_bpso_all_image(result,0,1,task_id);
          },1000);
        }
      })
    },1000);
  }
  if($(e.target).hasClass('cant_edit_task'))
  {
    alert("You are not authorized to edit this task. You should be either an author or the allocated user to edit this task");
  }
  else{
  		var ascending = false;
		if($(e.target).hasClass('taskname_sort'))
		{
			var sort = $("#taskname_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#taskname_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.taskname_sort_val').text()) <
			    convertToNumber($(b).find('.taskname_sort_val').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#taskname_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.taskname_sort_val').text()) <
			    convertToNumber($(b).find('.taskname_sort_val').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('redlight_sort'))
		{
			var sort = $("#redlight_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#redlight_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.hidden_redlight_value').text()) <
			    convertToNumber($(b).find('.hidden_redlight_value').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#redlight_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.hidden_redlight_value').text()) <
			    convertToNumber($(b).find('.hidden_redlight_value').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('subject_sort'))
		{
			var sort = $("#subject_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#subject_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.subject_sort_val').text()) <
			    convertToNumber($(b).find('.subject_sort_val').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#subject_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.subject_sort_val').text()) <
			    convertToNumber($(b).find('.subject_sort_val').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('author_sort'))
		{
			var sort = $("#author_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#author_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.author_sort_val').text()) <
			    convertToNumber($(b).find('.author_sort_val').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#author_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.author_sort_val').text()) <
			    convertToNumber($(b).find('.author_sort_val').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('allocated_sort'))
    {
      var sort = $("#allocated_sortoptions").val();
      if(sort == 'asc')
      {
        $("#allocated_sortoptions").val('desc');
        var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
          return (ascending ==
               (convertToNumber($(a).find('.hidden_star_rating_taskmanager').val()) <
          convertToNumber($(b).find('.hidden_star_rating_taskmanager').val()))) ? 1 : -1;
        });
      }
      else{
        $("#allocated_sortoptions").val('asc');
        var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
          return (ascending ==
               (convertToNumber($(a).find('.hidden_star_rating_taskmanager').val()) <
          convertToNumber($(b).find('.hidden_star_rating_taskmanager').val()))) ? -1 : 1;
        });
      }
      ascending = ascending ? false : true;
      $('#task_body_layout').html(sorted);
    }
		if($(e.target).hasClass('duedate_sort'))
		{
			var sort = $("#duedate_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#duedate_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.hidden_due_date_layout').text()) <
			    parseconvertToNumber($(b).find('.hidden_due_date_layout').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#duedate_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.hidden_due_date_layout').text()) <
			    parseconvertToNumber($(b).find('.hidden_due_date_layout').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('createddate_sort'))
		{
			var sort = $("#createddate_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#createddate_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.hidden_created_date_layout').text()) <
			    parseconvertToNumber($(b).find('.hidden_created_date_layout').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#createddate_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.hidden_created_date_layout').text()) <
			    parseconvertToNumber($(b).find('.hidden_created_date_layout').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('taskid_sort'))
		{
			var sort = $("#taskid_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#taskid_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.taskid_sort_val').text()) <
			    convertToNumber($(b).find('.taskid_sort_val').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#taskid_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (convertToNumber($(a).find('.taskid_sort_val').text()) <
			    convertToNumber($(b).find('.taskid_sort_val').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
		if($(e.target).hasClass('progress_sort'))
		{
			var sort = $("#progress_sortoptions").val();
			if(sort == 'asc')
			{
			  $("#progress_sortoptions").val('desc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.progress_sort_val').text()) <
			    parseconvertToNumber($(b).find('.progress_sort_val').text()))) ? 1 : -1;
			  });
			}
			else{
			  $("#progress_sortoptions").val('asc');
			  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
			    return (ascending ==
			         (parseconvertToNumber($(a).find('.progress_sort_val').text()) <
			    parseconvertToNumber($(b).find('.progress_sort_val').text()))) ? -1 : 1;
			  });
			}
			ascending = ascending ? false : true;
			$('#task_body_layout').html(sorted);
		}
    if($(e.target).hasClass('integrity_check_for_task'))
  {
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_for_task'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#integrity_check_tbody").html(result);
        $(".integrity_check_modal").modal("show");
      }
    })
  }
    if($(e.target).hasClass('faprogress_download_all')){
        var lenval = $(e.target).parents("tbody:first").find(".file_attachments").length;
        if(lenval > 0)
        {
            $("body").addClass("loading");
            var id = $(e.target).attr('data-element');
            $.ajax({

                url:"<?php echo URL::to('user/taskmanager_download_all_progress_files'); ?>",
                type:"get",
                data:{id:id},
                success: function(result) {
                    SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                    setTimeout(function() {
                      $.ajax({
                        url:"<?php echo URL::to('user/delete_file_link'); ?>",
                        type:"post",
                        data:{result:result},
                        success: function(result)
                        {
                          $("body").removeClass("loading");
                        }
                      });
                    },3000);
                }
            });
        }
        else{
          alert("There are no Progress Files attached to download.");
        }
    }
		if($(e.target).hasClass('export_csv'))
		{
		    $("body").addClass("loading");
        var author = $(".search_author").val();
        var open_task = $(".open_task_search:checked").val();
        var client_id = $("#copy_client_search").val();
        var client_id_search = $(".copy_client_search_class ").val();
        var subject = $(".subject_search_class").val();
        var recurring = $("#hidden_recurring_task").val();
        var due_date = $(".due_date_search_class").val();
        var creation_date = $(".creation_date_search_class").val();
        var make_internal = $("#hidden_make_internal").val();
        var select_tasks = $(".select_internal_tasks").val();

        $.ajax({
          url:"<?php echo URL::to('user/download_export_csv_task_search'); ?>",
          type:"post",
          data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
        })
		}
		if($(e.target).parents(".hidden_tasks_tr").length > 0)
		{
			$(".hidden_tasks_tr").find("td").css("background","#fff");
			$(e.target).parents(".hidden_tasks_tr").find("td").css("background","#E1E1E1");

			var taskid = $(e.target).parents(".hidden_tasks_tr").attr("data-element");

			$(".tasks_tr").hide();
			$(".tasks_tr").next().hide();

			$("#task_tr_"+taskid).show();
			$("#task_tr_"+taskid).next().show();
			var layout = $("#hidden_compressed_layout").val();
		    if(layout == "1")
			{
			  $(".open_layout_div").addClass("open_layout_div_change");
			  var open_tasks_height = $(".open_layout_div").height();
			  var margintop = parseInt(open_tasks_height);
			  $(".open_layout_div").css("position","fixed");
			  $(".open_layout_div").css("height","312px");
			  $(".open_layout_div").css("margin-top","28px");
			  if(open_tasks_height > 312)
			  {
			    $(".open_layout_div").css("overflow-y","scroll");
			  }
			  if(open_tasks_height < 50)
			  {
			    $(".table_layout").css("margin-top","20px");
			  }
			    else{
			      $(".table_layout").css("margin-top","363px");
			    }
          $(".empty_tr").hide();
			}
			else{
			  $(".open_layout_div").removeClass("open_layout_div_change");
			  $(".open_layout_div").css("position","unset");
			  $(".open_layout_div").css("height","auto");
			  $(".open_layout_div").css("overflow-y","unset");
			    $(".table_layout").css("margin-top","0px");
			    $(".open_layout_div").css("margin-top","53px");
          $(".empty_tr").show();
			}
		}
  	    if($(e.target).parents(".switch").length > 0)
		{
			if($(e.target).parents(".compressed_layout_div").find(".compressed_layout").is(":checked"))
			{
			  $(e.target).parents(".compressed_layout_div").find("#hidden_compressed_layout").val("1");
			}
			else{
			  $(e.target).parents(".compressed_layout_div").find("#hidden_compressed_layout").val("0");
			}

			var layout = $("#hidden_compressed_layout").val();
			$(".tasks_tr").hide();
			$(".tasks_tr").next().hide();
			$(".hidden_tasks_tr").hide();

            if(layout == "1")
			{
			  $(".tasks_tr:first").show();
			  $(".tasks_tr:first").next().show();
			  $(".table_layout").show();
			  $(".table_layout").find(".hidden_tasks_tr").show();
			  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
        $(".empty_tr").hide();
			}
			else{
			  $(".tasks_tr").show();
			  $(".tasks_tr").next().show();
			  $(".table_layout").hide();
			  $(".table_layout").find(".hidden_tasks_tr").hide();
        $(".empty_tr").show();
			}

			if(layout == "1")
	      {
	        $(".open_layout_div").addClass("open_layout_div_change");
	        var open_tasks_height = $(".open_layout_div").height();
	        var margintop = parseInt(open_tasks_height);
	        $(".open_layout_div").css("position","fixed");
	        $(".open_layout_div").css("height","312px");
	        $(".open_layout_div").css("margin-top","28px");
	        if(open_tasks_height > 312)
	        {
	          $(".open_layout_div").css("overflow-y","scroll");
	        }
	        if(open_tasks_height < 50)
	        {
	          $(".table_layout").css("margin-top","20px");
	        }
	          else{
	            $(".table_layout").css("margin-top","363px");
	          }
	      }
	      else{
	        $(".open_layout_div").removeClass("open_layout_div_change");
	        $(".open_layout_div").css("position","unset");
	        $(".open_layout_div").css("height","auto");
	        $(".open_layout_div").css("overflow-y","unset");
	          $(".table_layout").css("margin-top","0px");
	          $(".open_layout_div").css("margin-top","53px");
	      }
		}
      if($(e.target).hasClass('activate_task_button'))
      {
        var r = confirm("Do you want to make this task live now?");
        if(r)
        {
          $("body").addClass("loading");
          var task_id = $(e.target).attr("data-element");
          var author = $(".search_author").val();
          var open_task = $(".open_task_search:checked").val();
          var client_id = $("#copy_client_search").val();
          var client_id_search = $(".copy_client_search_class ").val();
          var subject = $(".subject_search_class").val();
          var recurring = $("#hidden_recurring_task").val();
          var due_date = $(".due_date_search_class").val();
          var creation_date = $(".creation_date_search_class").val();
          var make_internal = $("#hidden_make_internal").val();
          var select_tasks = $(".select_internal_tasks").val();

          $.ajax({
            url:"<?php echo URL::to('user/park_task_incomplete'); ?>",
            type:"post",
            data:{task_id:task_id},
            success:function(resultval)
            {
              $.ajax({
                url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
                type:"post",
                dataType:"json",
                data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
                success:function(result)
                {
                  if(result['count_rows'] >= 2) {
                    $(".consolidate_task_manager").prop("disabled", false);
                  }
                  else{
                    $(".consolidate_task_manager").prop("disabled", true);
                  }
					$("#task_body_search").html(result['open_tasks']);
					$("#task_body_layout").html(result['layout']);

					var layout = $("#hidden_compressed_layout").val();
					$(".tasks_tr").hide();
					$(".tasks_tr").next().hide();
					$(".hidden_tasks_tr").hide();

                    if(layout == "1")
					{
					  $(".tasks_tr:first").show();
					  $(".tasks_tr:first").next().show();
					  $(".table_layout").show();
					  $(".table_layout").find(".hidden_tasks_tr").show();
					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
            $(".empty_tr").hide();
					}
					else{
					  $(".tasks_tr").show();
					  $(".tasks_tr").next().show();
					  $(".table_layout").hide();
					  $(".table_layout").find(".hidden_tasks_tr").hide();
            $(".empty_tr").show();
					}

					if(layout == "1")
				      {
				        $(".open_layout_div").addClass("open_layout_div_change");
				        var open_tasks_height = $(".open_layout_div").height();
				        var margintop = parseInt(open_tasks_height);
				        $(".open_layout_div").css("position","fixed");
				        $(".open_layout_div").css("height","312px");
				        $(".open_layout_div").css("margin-top","28px");
				        if(open_tasks_height > 312)
				        {
				          $(".open_layout_div").css("overflow-y","scroll");
				        }
				        if(open_tasks_height < 50)
				        {
				          $(".table_layout").css("margin-top","20px");
				        }
				          else{
				            $(".table_layout").css("margin-top","363px");
				          }
				      }
				      else{
				        $(".open_layout_div").removeClass("open_layout_div_change");
				        $(".open_layout_div").css("position","unset");
				        $(".open_layout_div").css("height","auto");
				        $(".open_layout_div").css("overflow-y","unset");
				          $(".table_layout").css("margin-top","0px");
				          $(".open_layout_div").css("margin-top","53px");
				      }

					$("[data-toggle=popover]").popover({
						html : true,
						content: function() {
						  var content = $(this).attr("data-popover-content");
						  return $(content).children(".popover-body").html();
						},
						title: function() {
						  var title = $(this).attr("data-popover-content");
						  return $(title).children(".popover-heading").html();
						}
					});
                  	$("body").removeClass("loading");
                }
              })
            }
          })
        }
      }
      if($(e.target).hasClass('park_task_button'))
      {
        var taskid = $(e.target).attr("data-element");
        $(".hidden_task_id_park_task").val(taskid);
        $(".park_task_modal").modal("show");
        $("#park_task_date").val("");
        $("#park_task_date").datetimepicker({
               defaultDate: fullDate,       
               format: 'L',
               format: 'DD-MMM-YYYY',
            });
      }
      if(e.target.id == "park_submit")
      {
          var park_date = $("#park_task_date").val();
          if(park_date == "")
          {
            alert("Please choose the Date to Park the Task.");
          }
          else{
            $("body").addClass("loading");
            var task_id = $(".hidden_task_id_park_task").val();
            var author = $(".search_author").val();
            var open_task = $(".open_task_search:checked").val();
            var client_id = $("#copy_client_search").val();
            var client_id_search = $(".copy_client_search_class ").val();
            var subject = $(".subject_search_class").val();
            var recurring = $("#hidden_recurring_task").val();
            var due_date = $(".due_date_search_class").val();
            var creation_date = $(".creation_date_search_class").val();
            var make_internal = $("#hidden_make_internal").val();
            var select_tasks = $(".select_internal_tasks").val();

            $.ajax({
              url:"<?php echo URL::to('user/park_task_complete'); ?>",
              type:"post",
              data:{task_id:task_id,park_date:park_date},
              success:function(resultval)
              {
                $(".park_task_modal").modal("hide");
                $.ajax({
                  url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
                  type:"post",
                  dataType:"json",
                  data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
                  success:function(result)
                  {
                    if(result['count_rows'] >= 2) {
                      $(".consolidate_task_manager").prop("disabled", false);
                    }
                    else{
                      $(".consolidate_task_manager").prop("disabled", true);
                    }
                    $("#task_body_search").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);

                    var layout = $("#hidden_compressed_layout").val();
					$(".tasks_tr").hide();
					$(".tasks_tr").next().hide();
					$(".hidden_tasks_tr").hide();

                    if(layout == "1")
					{
					  $(".tasks_tr:first").show();
					  $(".tasks_tr:first").next().show();
					  $(".table_layout").show();
					  $(".table_layout").find(".hidden_tasks_tr").show();
					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
            $(".empty_tr").hide();
					}
					else{
					  $(".tasks_tr").show();
					  $(".tasks_tr").next().show();
					  $(".table_layout").hide();
					  $(".table_layout").find(".hidden_tasks_tr").hide();
            $(".empty_tr").show();
					}

					if(layout == "1")
			      {
			        $(".open_layout_div").addClass("open_layout_div_change");
			        var open_tasks_height = $(".open_layout_div").height();
			        var margintop = parseInt(open_tasks_height);
			        $(".open_layout_div").css("position","fixed");
			        $(".open_layout_div").css("height","312px");
			        $(".open_layout_div").css("margin-top","28px");
			        if(open_tasks_height > 312)
			        {
			          $(".open_layout_div").css("overflow-y","scroll");
			        }
			        if(open_tasks_height < 50)
			        {
			          $(".table_layout").css("margin-top","20px");
			        }
			          else{
			            $(".table_layout").css("margin-top","363px");
			          }
			      }
			      else{
			        $(".open_layout_div").removeClass("open_layout_div_change");
			        $(".open_layout_div").css("position","unset");
			        $(".open_layout_div").css("height","auto");
			        $(".open_layout_div").css("overflow-y","unset");
			          $(".table_layout").css("margin-top","0px");
			          $(".open_layout_div").css("margin-top","53px");
			      }

                    $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                    $("body").removeClass("loading");
                  }
                })
              }
            })
          }
      }
      if($(e.target).hasClass('edit_task_name'))
      {
        var taskid = $(e.target).attr("data-element");
        var taskname = $(e.target).attr("data-value");
        $(".task-choose_internal_change").html(taskname);
        $(".hidden_task_id_change_task").val(taskid);
        $(".change_taskname_modal").modal("show");
      }
      if($(e.target).hasClass('request_update'))
      {
        var r = confirm("An email is sent to the person who the task is currently allocated to. Are you sure you want to continue?");
        if(r)
        {
          $("body").addClass("loading");
          setTimeout(function() {
            var task_id = $(e.target).attr("data-element");
            var author = $(e.target).attr("data-author");
            var allocated_to = $(e.target).attr("data-allocated");

            $.ajax({
              url:"<?php echo URL::to('user/request_update'); ?>",
              type:"post",
              data:{task_id:task_id,author:author,allocated_to:allocated_to},
              success:function(result)
              {
                $("body").removeClass("loading");
                if(result == "1")
                {
                  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
                }
                else{
                  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>The Task you requested for update is an open task. Email will be sent only if any of the user is been allocated to this task.</p>", fixed:true});
                }
              }
            });
          },1000);
        }
      }
      if($(e.target).parents(".switch").length > 0)
      {
        if($(e.target).parents(".recurring_task_div").find(".recurring_task_search").is(":checked"))
        {
          $(e.target).parents(".recurring_task_div").find("#hidden_recurring_task").val("1");
        }
        else{
          $(e.target).parents(".recurring_task_div").find("#hidden_recurring_task").val("0");
        }
      }
      if($(e.target).hasClass('make_internal'))
      {
        if($(e.target).is(":checked"))
        {
          $("#hidden_make_internal").val("1");
          $(".select_internal_tasks").show();
          $(".copy_client_search_class").hide();
        }
        else{
          $("#hidden_make_internal").val("0");
          $(".select_internal_tasks").hide();
          $(".copy_client_search_class").show();
        }
      }
      if($(e.target).hasClass('make_task_live'))
      {
        e.preventDefault();
        if($( "#create_job_form" ).valid())
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
            var clientid = $("#client_search").val();
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
                $(window).unbind('beforeunload');
                
                var formData = $("#create_job_form").submit(function (e) {
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
                      $(".create_new_model").modal("hide");

                      $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
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
              $(window).unbind('beforeunload');
              var formData = $("#create_job_form").submit(function (e) {
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
                    $(".create_new_model").modal("hide");

                    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
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
        $(window).unbind('beforeunload');
        $(".2_bill_task").prop("checked",true);
        
        var formData = $("#create_job_form").submit(function (e) {
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
              $(".create_new_model").modal("hide");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
              $("body").removeClass("loading");
            },
            cache: false,
            contentType: false,
            processData: false
        });
      }
      if($(e.target).hasClass('no_make_task_live'))
      {
        $(window).unbind('beforeunload');
        $(".2_bill_task").prop("checked",false);
        
        var formData = $("#create_job_form").submit(function (e) {
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
              $(".create_new_model").modal("hide");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
              $("body").removeClass("loading");
            },
            cache: false,
            contentType: false,
            processData: false
        });
      }
      if($(e.target).hasClass("search_tasks"))
      {
        $("body").addClass("loading");
        setTimeout(function() {
          var author = $(".search_author").val();
          var open_task = $(".open_task_search:checked").val();
          var client_id = $("#copy_client_search").val();
          var client_id_search = $(".copy_client_search_class ").val();
          var subject = $(".subject_search_class").val();
          var recurring = $("#hidden_recurring_task").val();
          var due_date = $(".due_date_search_class").val();
          var creation_date = $(".creation_date_search_class").val();
          var make_internal = $("#hidden_make_internal").val();
          var select_tasks = $(".select_internal_tasks").val();
          
          if(client_id_search == "")
          {
            $("#copy_client_search").val("");
            client_id = "";
          }

          $.ajax({
            url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
            type:"post",
            dataType:"json",
            data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
            success:function(result)
            {
              if(result['count_rows'] >= 2) {
                $(".consolidate_task_manager").prop("disabled", false);
              }
              else{
                $(".consolidate_task_manager").prop("disabled", true);
              }
              $("#task_body_search").html(result['open_tasks']);
              $("#task_body_layout").html(result['layout']);
              $(".export_csv").prop("disabled", false);

              var layout = $("#hidden_compressed_layout").val();
              $(".tasks_tr").hide();
              $(".tasks_tr").next().hide();
              $(".hidden_tasks_tr").hide();

              if(layout == "1")
              {
                $(".tasks_tr:first").show();
                $(".tasks_tr:first").next().show();
                $(".table_layout").show();
                $(".table_layout").find(".hidden_tasks_tr").show();
                $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
                $(".empty_tr").hide();
              }
              else{
                $(".tasks_tr").show();
                $(".tasks_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_tasks_tr").hide();
                $(".empty_tr").show();
              }

              if(layout == "1")
              {
                $(".open_layout_div").addClass("open_layout_div_change");
                var open_tasks_height = $(".open_layout_div").height();
                var margintop = parseInt(open_tasks_height);
                $(".open_layout_div").css("position","fixed");
                $(".open_layout_div").css("height","312px");
                $(".open_layout_div").css("top", "350px");
                $(".open_layout_div").css("margin-top","28px");
                if(open_tasks_height > 312)
                {
                  $(".open_layout_div").css("overflow-y","scroll");
                }
                if(open_tasks_height < 50)
                {
                  $(".table_layout").css("margin-top","20px");
                }
                  else{
                    $(".table_layout").css("margin-top","363px");
                  }
              }
              else{
                $(".open_layout_div").removeClass("open_layout_div_change");
                $(".open_layout_div").css("position","unset");
                $(".open_layout_div").css("height","auto");
                $(".open_layout_div").css("overflow-y","unset");
                  $(".table_layout").css("margin-top","0px");
                  $(".open_layout_div").css("margin-top","53px");
              }
              $("[data-toggle=popover]").popover({
                  html : true,
                  content: function() {
                    var content = $(this).attr("data-popover-content");
                    return $(content).children(".popover-body").html();
                  },
                  title: function() {
                    var title = $(this).attr("data-popover-content");
                    return $(title).children(".popover-heading").html();
                  }
              });
              $("body").removeClass("loading");
            }
          })
        },1000);
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
      if($(e.target).hasClass('refresh_task'))
      {
        $("body").addClass("loading");
        setTimeout(function() {
          var user_id = $(".select_user_home").val();
          if(user_id == "")
          {
            alert("Please Select the user and then click on the refresh button.");
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                $("#task_body_open").html(result['open_tasks']);
                $("#open_task_count").html("Your Open Tasks ("+result['open_task_count']+")");
                $("#authored_task_count").html("Your Authored Tasks ("+result['authored_task_count']+")");

                $("[data-toggle=popover]").popover({
                    html : true,
                    content: function() {
                      var content = $(this).attr("data-popover-content");
                      return $(content).children(".popover-body").html();
                    },
                    title: function() {
                      var title = $(this).attr("data-popover-content");
                      return $(title).children(".popover-heading").html();
                    }
                });
                $("body").removeClass("loading");
              }
            })
          }
        },2000);
      }
      if($(e.target).hasClass('avoid_email'))
      {
        var task_id = $(e.target).attr("data-element");
        if($(e.target).hasClass('retain_email'))
        {
          $.ajax({
            url:"<?php echo URL::to('user/set_avoid_email_taskmanager'); ?>",
            type:"post",
            data:{task_id:task_id,status:0},
            success:function(result)
            {
              $(e.target).removeClass("retain_email");
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/set_avoid_email_taskmanager'); ?>",
            type:"post",
            data:{task_id:task_id,status:1},
            success:function(result)
            {
              $(e.target).addClass("retain_email");
            }
          });
        }
      }
      if($(e.target).hasClass('set_progress'))
      {
        var task_id = $(e.target).attr("data-element");
        var value = $(e.target).parents(".popover-content").find(".progress_value").val();
        if(value == "")
        {
          $(".progress_"+task_id).find(".progress-bar").attr("area-valuenow",0);
          $(".progress_"+task_id).find(".progress-bar").css("width","0px");
        }
        else{
          $(".progress_"+task_id).find(".progress-bar").attr("aria-valuenow",value);
          $(".progress_"+task_id).find(".progress-bar").css("width",value+"%");
          $(".progress_"+task_id).find(".progress-bar").html(value+"%");
        }
        $(".progress_value").val("");
        $('[data-toggle="popover"]').popover('hide')
        $.ajax({
          url:"<?php echo URL::to('user/set_progress_value'); ?>",
          type:"post",
          data:{task_id:task_id,value:value},
          success:function(result)
          {

          }
        })
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
      
      if($(e.target).hasClass('mark_as_complete'))
      {
        $("body").addClass("loading");
        var task_id = $(e.target).attr("data-element");

        var author = $(".search_author").val();
        var open_task = $(".open_task_search:checked").val();
        var client_id = $("#copy_client_search").val();
        var client_id_search = $(".copy_client_search_class ").val();
        var subject = $(".subject_search_class").val();
        var recurring = $("#hidden_recurring_task").val();
        var due_date = $(".due_date_search_class").val();
        var creation_date = $(".creation_date_search_class").val();
        var make_internal = $("#hidden_make_internal").val();
        var select_tasks = $(".select_internal_tasks").val();

        if(client_id_search == "")
    		{
    			$("#copy_client_search").val("");
    			client_id = "";
    		}

        $.ajax({
          url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
          type:"post",
          data:{task_id:task_id,type:"0"},
          success:function(result)
          {
            $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
            $.ajax({
              url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
              type:"post",
              dataType:"json",
              data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
              success:function(result)
              {
                if(result['count_rows'] >= 2) {
                  $(".consolidate_task_manager").prop("disabled", false);
                }
                else{
                  $(".consolidate_task_manager").prop("disabled", true);
                }

                $("#task_body_search").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);

                var layout = $("#hidden_compressed_layout").val();
      					$(".tasks_tr").hide();
      					$(".tasks_tr").next().hide();
      					$(".hidden_tasks_tr").hide();

                if(layout == "1")
      					{
      					  $(".tasks_tr:first").show();
      					  $(".tasks_tr:first").next().show();
      					  $(".table_layout").show();
      					  $(".table_layout").find(".hidden_tasks_tr").show();
      					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
                  $(".empty_tr").hide();
      					}
      					else{
      					  $(".tasks_tr").show();
      					  $(".tasks_tr").next().show();
      					  $(".table_layout").hide();
      					  $(".table_layout").find(".hidden_tasks_tr").hide();
                  $(".empty_tr").show();
      					}

					      if(layout == "1")
    			      {
    			        $(".open_layout_div").addClass("open_layout_div_change");
    			        var open_tasks_height = $(".open_layout_div").height();
    			        var margintop = parseInt(open_tasks_height);
    			        $(".open_layout_div").css("position","fixed");
    			        $(".open_layout_div").css("height","312px");
    			        $(".open_layout_div").css("margin-top","28px");
    			        if(open_tasks_height > 312)
    			        {
    			          $(".open_layout_div").css("overflow-y","scroll");
    			        }
    			        if(open_tasks_height < 50)
    			        {
    			          $(".table_layout").css("margin-top","20px");
    			        }
    			          else{
    			            $(".table_layout").css("margin-top","363px");
    			          }
    			      }
    			      else{
    			        $(".open_layout_div").removeClass("open_layout_div_change");
    			        $(".open_layout_div").css("position","unset");
    			        $(".open_layout_div").css("height","auto");
    			        $(".open_layout_div").css("overflow-y","unset");
    			          $(".table_layout").css("margin-top","0px");
    			          $(".open_layout_div").css("margin-top","53px");
    			      }

                $("[data-toggle=popover]").popover({
                    html : true,
                    content: function() {
                      var content = $(this).attr("data-popover-content");
                      return $(content).children(".popover-body").html();
                    },
                    title: function() {
                      var title = $(this).attr("data-popover-content");
                      return $(title).children(".popover-heading").html();
                    }
                });
                $("body").removeClass("loading");
              }
            })
          }
        })
      }
      if($(e.target).hasClass('mark_as_incomplete'))
      {
        $("body").addClass("loading");
        var task_id = $(e.target).attr("data-element");

        var author = $(".search_author").val();
        var open_task = $(".open_task_search:checked").val();
        var client_id = $("#copy_client_search").val();
        var client_id_search = $(".copy_client_search_class ").val();
        var subject = $(".subject_search_class").val();
        var recurring = $("#hidden_recurring_task").val();
        var due_date = $(".due_date_search_class").val();
        var creation_date = $(".creation_date_search_class").val();
        var make_internal = $("#hidden_make_internal").val();
        var select_tasks = $(".select_internal_tasks").val();

        if(client_id_search == "")
    		{
    			$("#copy_client_search").val("");
    			client_id = "";
    		}

        $.ajax({
          url:"<?php echo URL::to('user/taskmanager_mark_incomplete'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            $.ajax({
              url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
              type:"post",
              dataType:"json",
              data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
              success:function(result)
              {
                if(result['count_rows'] >= 2) {
                  $(".consolidate_task_manager").prop("disabled", false);
                }
                else{
                  $(".consolidate_task_manager").prop("disabled", true);
                }
                $("#task_body_search").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);

                var layout = $("#hidden_compressed_layout").val();
					$(".tasks_tr").hide();
					$(".tasks_tr").next().hide();
					$(".hidden_tasks_tr").hide();

                    if(layout == "1")
					{
					  $(".tasks_tr:first").show();
					  $(".tasks_tr:first").next().show();
					  $(".table_layout").show();
					  $(".table_layout").find(".hidden_tasks_tr").show();
					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
            $(".empty_tr").hide();
					}
					else{
					  $(".tasks_tr").show();
					  $(".tasks_tr").next().show();
					  $(".table_layout").hide();
					  $(".table_layout").find(".hidden_tasks_tr").hide();
            $(".empty_tr").show();
					}

					if(layout == "1")
			      {
			        $(".open_layout_div").addClass("open_layout_div_change");
			        var open_tasks_height = $(".open_layout_div").height();
			        var margintop = parseInt(open_tasks_height);
			        $(".open_layout_div").css("position","fixed");
			        $(".open_layout_div").css("height","312px");
			        $(".open_layout_div").css("margin-top","28px");
			        if(open_tasks_height > 312)
			        {
			          $(".open_layout_div").css("overflow-y","scroll");
			        }
			        if(open_tasks_height < 50)
			        {
			          $(".table_layout").css("margin-top","20px");
			        }
			          else{
			            $(".table_layout").css("margin-top","363px");
			          }
			      }
			      else{
			        $(".open_layout_div").removeClass("open_layout_div_change");
			        $(".open_layout_div").css("position","unset");
			        $(".open_layout_div").css("height","auto");
			        $(".open_layout_div").css("overflow-y","unset");
			          $(".table_layout").css("margin-top","0px");
			          $(".open_layout_div").css("margin-top","53px");
			      }

                $("[data-toggle=popover]").popover({
                  html : true,
                  content: function() {
                    var content = $(this).attr("data-popover-content");
                    return $(content).children(".popover-body").html();
                  },
                  title: function() {
                    var title = $(this).attr("data-popover-content");
                    return $(title).children(".popover-heading").html();
                  }
              });
                $("body").removeClass("loading");
              }
            })
          }
        })
      }
      if($(e.target).hasClass('mark_as_complete_author'))
      {
        var r = confirm("You are about to mark this task as Complete are you sure you want to continue?");
        if(r)
        {
          $("body").addClass("loading");
          var task_id = $(e.target).attr("data-element");

          var author = $(".search_author").val();
          var open_task = $(".open_task_search:checked").val();
          var client_id = $("#copy_client_search").val();
          var client_id_search = $(".copy_client_search_class ").val();
          var subject = $(".subject_search_class").val();
          var recurring = $("#hidden_recurring_task").val();
          var due_date = $(".due_date_search_class").val();
          var creation_date = $(".creation_date_search_class").val();
          var make_internal = $("#hidden_make_internal").val();
          var select_tasks = $(".select_internal_tasks").val();

          $.ajax({
            url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
            type:"post",
            data:{task_id:task_id,type:"0"},
            success:function(resultval)
            {
              $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
              $.ajax({
                url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
                type:"post",
                dataType:"json",
                data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
                success:function(result)
                {
                  if(result['count_rows'] >= 2) {
                    $(".consolidate_task_manager").prop("disabled", false);
                  }
                  else{
                    $(".consolidate_task_manager").prop("disabled", true);
                  }

                  $("#task_body_search").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);

                  var layout = $("#hidden_compressed_layout").val();
                  $(".tasks_tr").hide();
                  $(".tasks_tr").next().hide();
                  $(".hidden_tasks_tr").hide();

                  if(layout == "1")
                  {
                    $(".tasks_tr:first").show();
                    $(".tasks_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_tasks_tr").show();
                    $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
                    $(".empty_tr").hide();
                  }
                  else{
                    $(".tasks_tr").show();
                    $(".tasks_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_tasks_tr").hide();
                    $(".empty_tr").show();
                  }

                  if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    $(".open_layout_div").css("margin-top","28px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","363px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                      $(".open_layout_div").css("margin-top","53px");
                  }

                  $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                  $("body").removeClass("loading");
                }
              })
            }
          })
        }
      }
      if($(e.target).hasClass('copy_task'))
      {
        $("#hidden_copied_files").val("");
        $("#hidden_copied_notes").val("");
        $("#hidden_copied_infiles").val("");
        var task_id = $(e.target).attr("data-element");
        $(".hide_taskmanager_files").hide();
        $(".question_modal").modal("show");
        $(".hidden_task_id_copy_task").val(task_id);

        $("#copy_task_specifics_no").prop("checked",true);
        $("#copy_task_files_no").prop("checked",true);
      }
      if(e.target.id == "question_submit")
      {
        $(".create_new_model").find(".job_title").html("Copy Task");
        var task_specifics = $(".copy_task_specifics:checked").val();
        var task_files = $(".copy_task_files:checked").val();
        var task_id = $(".hidden_task_id_copy_task").val();
        $(".question_modal").modal("hide");
        $.ajax({
          url:"<?php echo URL::to('user/copy_task_details'); ?>",
          type:"post",
          data:{task_id:task_id,task_specifics:task_specifics,task_files:task_files},
          dataType:"json",
          success: function(result)
          {
            $(".create_new_model").modal("show");
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            
            $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
            $(".author_email").val("<?php echo Session::get('taskmanager_user_email'); ?>");
            $(".create_new_model").modal("show");
            $(".created_date").datetimepicker({
               defaultDate: fullDate,       
               format: 'L',
               format: 'DD-MMM-YYYY',
            });
            $(".due_date").datetimepicker({
               defaultDate: fullDate,
               format: 'L',
               format: 'DD-MMM-YYYY',
            });
            $(".created_date").val(result['creation_date']);
            $(".due_date").val("");
            $("#action_type").val("2");
            $(".allocate_user_add").val(result['allocated_to']);

            if(result['internal'] == "1")
            {
              $(".task-choose_internal").html(result['task_name']);
              $("#idtask").val(result['task_type']);
              $(".internal_tasks_group").show();
              $("#internal_checkbox").prop("checked",true);
              $(".client_group").hide();
              $(".client_search_class").prop("required",false);
              $(".client_search_class").val("");
              $("#client_search").val("");
            }
            else{
              $(".task-choose_internal").html("Select Task");
              $("#idtask").val("");
              $(".internal_tasks_group").hide();
              $("#internal_checkbox").prop("checked",false);

              $(".client_group").show();
              $(".client_search_class").prop("required",true);
              $(".client_search_class").val(result['client_name']);
              $("#client_search").val(result['client_id']);
            }

            $(".subject_class").val(result['subject']);

            $("#hidden_specific_type").val(result['task_specifics_type']);
            $("#hidden_attachment_type").val(result['task_attachment_type']);

            if(result['task_specifics_type'] == "2")
            {
              $(".task_specifics_add").show();
              
              CKEDITOR.replace('editor_2',
               {
                height: '300px',
                enterMode: CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P,
                autoParagraph: false,
                entities: false,
                contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
               });
              CKEDITOR.instances['editor_2'].setData(result['task_specifics']);
              $(".task_specifics_copy").hide();
              $(".task_specifics_copy_val").html("");
              $("#hidden_task_specifics").val(result['task_specifics']);
            }
            else{
              $(".task_specifics_add").hide();
              $(".task_specifics_copy").show();
              
              $(".task_specifics").val("");
              $(".task_specifics_copy_val").html(result['task_specifics']);
              $("#hidden_task_specifics").val(result['task_specifics']);
            }
            
            if(result['task_attachment_type'] == "2")
            {
              $(".retreived_files_div").hide();
              $(".retreived_files_div").html("");
            }
            else{
              $(".retreived_files_div").show();
              $(".retreived_files_div").html(result['attached_files']);
            }
            $(".specific_recurring").val("");        
            
            $(".infiles_link").show();
            $("#attachments_text").hide();
            $("#hidden_infiles_id").val("");
            $("#add_infiles_attachments_div").html("");
            $("#attachments_infiles").hide();

            $(".auto_close_task").prop("checked",false);
            $(".accept_recurring").prop("checked",true);
            $(".accept_recurring_div").show();
            $("#recurring_checkbox1").prop("checked",true);

            $("#open_task").prop("checked",false);
            $(".allocate_user_add").removeClass("disable_user");

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
        })
        
      }
      if($(e.target).hasClass('export_pdf_history'))
      {
        $("body").addClass("loading");
        var task_id = $("#hidden_task_id_history").val();
        $.ajax({
          url:"<?php echo URL::to('user/download_pdf_history'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
        })
      }
      if($(e.target).hasClass('export_csv_history'))
      {
        $("body").addClass("loading");
        var task_id = $("#hidden_task_id_history").val();
        $.ajax({
          url:"<?php echo URL::to('user/download_csv_history'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
        })
      }
      if($(e.target).hasClass('download_pdf_spec'))
      {
        $("body").addClass("loading");
        var task_id = $("#hidden_task_id_task_specifics").val();
        $.ajax({
          url:"<?php echo URL::to('user/download_pdf_specifics'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
        })
      }
      if($(e.target).hasClass('close_task_specifics')){
        if($(".add_progress_attachments").find("p").length > 0){
          var obj = {};
          obj.message = []; 
          obj.task_id = []; 
          obj.user_id = []; 
          $(".add_progress_attachments").find('p').each(function(index,value) {
            var message = $(this).html();
            var task_id = $(this).attr("data-element");
            var user_id = $(this).attr("data-user");

            obj.message.push([message]);
            obj.task_id.push([task_id]);
            obj.user_id.push([user_id]);
          });

          var messages = JSON.stringify(obj);

          $.ajax({
            url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
            type:"post",
            data:{messages:messages},
            success:function(result){
              $(".add_progress_attachments").html("");
              $("#existing_comments").append(result);
              $("body").removeClass("loading");
            }
          });
        }
      }
      if($(e.target).hasClass('add_task_specifics'))
      {
        var comments = CKEDITOR.instances['editor_1'].getData();
        var task_id = $("#hidden_task_id_task_specifics").val();
        if(comments == "")
        {
          alert("Please enter new comments and then click on the Add New Comment Button");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/add_comment_specifics'); ?>",
            type:"post",
            data:{task_id:task_id,comments:comments},
            success:function(result)
            {
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");
              if($(".add_progress_attachments").find("p").length > 0){
                var obj = {};
                obj.message = []; 
                obj.task_id = []; 
                obj.user_id = []; 
                $(".add_progress_attachments").find('p').each(function(index,value) {
                  var message = $(this).html();
                  var task_id = $(this).attr("data-element");
                  var user_id = $(this).attr("data-user");

                  obj.message.push([message]);
                  obj.task_id.push([task_id]);
                  obj.user_id.push([user_id]);
                });

                var messages = JSON.stringify(obj);

                $.ajax({
                  url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                  type:"post",
                  data:{messages:messages},
                  success:function(result){
                    $(".add_progress_attachments").html("");
                    $("#existing_comments").append(result);
                    $("body").removeClass("loading");
                  }
                });
              }
            }
          })
        }
      }
      if($(e.target).hasClass('auto_close_task_comment'))
      {
        var task_id = $("#hidden_task_id_task_specifics").val();
        if($(e.target).is(":checked"))
        {
          var status = 1;
        }
        else{
          var status = 0;
        }
        $.ajax({
            url:"<?php echo URL::to('user/change_auto_close_status'); ?>",
            type:"post",
            data:{task_id:task_id,status:status},
            success:function(result)
            {

            }
        });
      }
      if($(e.target).hasClass('add_comment_and_allocate'))
      {
        
        setTimeout(function() {
          var comments = CKEDITOR.instances['editor_1'].getData();
          var task_id = $("#hidden_task_id_task_specifics").val();
          if(comments == "")
          {
            alert("Please enter new comments and then click on the Add New Comment Button");
          }
          else{
            $("body").addClass("loading");
            $.ajax({
              url:"<?php echo URL::to('user/add_comment_and_allocate'); ?>",
              type:"post",
              data:{task_id:task_id,comments:comments},
              success:function(result)
              {
                var new_allocation = result;
                if(new_allocation == "0")
                {
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      var author = $(".search_author").val();
      			          var open_task = $(".open_task_search:checked").val();
      			          var client_id = $("#copy_client_search").val();
      			          var client_id_search = $(".copy_client_search_class ").val();
      			          var subject = $(".subject_search_class").val();
      			          var recurring = $("#hidden_recurring_task").val();
      			          var due_date = $(".due_date_search_class").val();
      			          var creation_date = $(".creation_date_search_class").val();
      			          var make_internal = $("#hidden_make_internal").val();
      			          var select_tasks = $(".select_internal_tasks").val();

      			          if(client_id_search == "")
      			          {
      			            $("#copy_client_search").val("");
      			            client_id = "";
      			          }
                      
                      $.ajax({
                        url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
                        type:"post",
                        dataType:"json",
                        data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
                        success:function(result)
                        {
                          if(result['count_rows'] >= 2) {
                            $(".consolidate_task_manager").prop("disabled", false);
                          }
                          else{
                            $(".consolidate_task_manager").prop("disabled", true);
                          }
                          $("#task_body_search").html(result['open_tasks']);
                          $("#task_body_layout").html(result['layout']);

                          var layout = $("#hidden_compressed_layout").val();
						$(".tasks_tr").hide();
						$(".tasks_tr").next().hide();
						$(".hidden_tasks_tr").hide();

	                    if(layout == "1")
						{
						  $(".tasks_tr:first").show();
						  $(".tasks_tr:first").next().show();
						  $(".table_layout").show();
						  $(".table_layout").find(".hidden_tasks_tr").show();
						  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
              $(".empty_tr").hide();
						}
						else{
						  $(".tasks_tr").show();
						  $(".tasks_tr").next().show();
						  $(".table_layout").hide();
						  $(".table_layout").find(".hidden_tasks_tr").hide();
              $(".empty_tr").show();
						}

						if(layout == "1")
				      {
				        $(".open_layout_div").addClass("open_layout_div_change");
				        var open_tasks_height = $(".open_layout_div").height();
				        var margintop = parseInt(open_tasks_height);
				        $(".open_layout_div").css("position","fixed");
				        $(".open_layout_div").css("height","312px");
				        $(".open_layout_div").css("margin-top","28px");
				        if(open_tasks_height > 312)
				        {
				          $(".open_layout_div").css("overflow-y","scroll");
				        }
				        if(open_tasks_height < 50)
				        {
				          $(".table_layout").css("margin-top","20px");
				        }
				          else{
				            $(".table_layout").css("margin-top","363px");
				          }
				      }
				      else{
				        $(".open_layout_div").removeClass("open_layout_div_change");
				        $(".open_layout_div").css("position","unset");
				        $(".open_layout_div").css("height","auto");
				        $(".open_layout_div").css("overflow-y","unset");
				          $(".table_layout").css("margin-top","0px");
				          $(".open_layout_div").css("margin-top","53px");
				      }

                          $("[data-toggle=popover]").popover({
			                  html : true,
			                  content: function() {
			                    var content = $(this).attr("data-popover-content");
			                    return $(content).children(".popover-body").html();
			                  },
			                  title: function() {
			                    var title = $(this).attr("data-popover-content");
			                    return $(title).children(".popover-heading").html();
			                  }
			              });
                          $("body").removeClass("loading");
                        }
                      })
                    }
                  })
                }
              }
            })
          }
        },1000);
      }
      if($(e.target).hasClass('add_comment_allocate_to_btn'))
      {
        
        setTimeout(function() {
          var comments = CKEDITOR.instances['editor_1'].getData();
          var task_id = $("#hidden_task_id_task_specifics").val();
          var allocate_to = $(".add_comment_allocate_to").val();
          if(comments == "")
          {
            alert("Please enter new comments and then click on the Add New Comment Button");
          }
          else if(allocate_to == "")
          {
            alert("Please select the user and then proceed with submit button.");
          }
          else{
            $("body").addClass("loading");
            $.ajax({
              url:"<?php echo URL::to('user/add_comment_and_allocate_to'); ?>",
              type:"post",
              data:{task_id:task_id,comments:comments,allocate_to:allocate_to},
              success:function(result)
              {
                var new_allocation = result;
                if(new_allocation == "0")
                {
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      var author = $(".search_author").val();
			          var open_task = $(".open_task_search:checked").val();
			          var client_id = $("#copy_client_search").val();
			          var client_id_search = $(".copy_client_search_class ").val();
			          var subject = $(".subject_search_class").val();
			          var recurring = $("#hidden_recurring_task").val();
			          var due_date = $(".due_date_search_class").val();
			          var creation_date = $(".creation_date_search_class").val();
			          var make_internal = $("#hidden_make_internal").val();
			          var select_tasks = $(".select_internal_tasks").val();

                      if(client_id_search == "")
                      {
                        $("#copy_client_search").val("");
                        client_id = "";
                      }
                      
                      $.ajax({
                        url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
                        type:"post",
                        dataType:"json",
                        data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
                        success:function(result)
                        {
                          if(result['count_rows'] >= 2) {
                            $(".consolidate_task_manager").prop("disabled", false);
                          }
                          else{
                            $(".consolidate_task_manager").prop("disabled", true);
                          }
                          $("#task_body_search").html(result['open_tasks']);
                          $("#task_body_layout").html(result['layout']);

                          var layout = $("#hidden_compressed_layout").val();
					$(".tasks_tr").hide();
					$(".tasks_tr").next().hide();
					$(".hidden_tasks_tr").hide();

                    if(layout == "1")
					{
					  $(".tasks_tr:first").show();
					  $(".tasks_tr:first").next().show();
					  $(".table_layout").show();
					  $(".table_layout").find(".hidden_tasks_tr").show();
					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
            $(".empty_tr").hide();
					}
					else{
					  $(".tasks_tr").show();
					  $(".tasks_tr").next().show();
					  $(".table_layout").hide();
					  $(".table_layout").find(".hidden_tasks_tr").hide();
            $(".empty_tr").show();
					}

					if(layout == "1")
			      {
			        $(".open_layout_div").addClass("open_layout_div_change");
			        var open_tasks_height = $(".open_layout_div").height();
			        var margintop = parseInt(open_tasks_height);
			        $(".open_layout_div").css("position","fixed");
			        $(".open_layout_div").css("height","312px");
			        $(".open_layout_div").css("margin-top","28px");
			        if(open_tasks_height > 312)
			        {
			          $(".open_layout_div").css("overflow-y","scroll");
			        }
			        if(open_tasks_height < 50)
			        {
			          $(".table_layout").css("margin-top","20px");
			        }
			          else{
			            $(".table_layout").css("margin-top","363px");
			          }
			      }
			      else{
			        $(".open_layout_div").removeClass("open_layout_div_change");
			        $(".open_layout_div").css("position","unset");
			        $(".open_layout_div").css("height","auto");
			        $(".open_layout_div").css("overflow-y","unset");
			          $(".table_layout").css("margin-top","0px");
			          $(".open_layout_div").css("margin-top","53px");
			      }


                          $("[data-toggle=popover]").popover({
			                  html : true,
			                  content: function() {
			                    var content = $(this).attr("data-popover-content");
			                    return $(content).children(".popover-body").html();
			                  },
			                  title: function() {
			                    var title = $(this).attr("data-popover-content");
			                    return $(title).children(".popover-heading").html();
			                  }
			              });
                          $("body").removeClass("loading");
                        }
                      })
                    }
                  })
                }
              }
            })
          }
        },1000);
      }

      if($(e.target).hasClass('link_to_task_specifics'))
      {
        if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
        $("#editor_1").val("");
        $("body").addClass("loading");
        setTimeout(function() {
          var task_id = $(e.target).attr("data-element");
          $(".view_full_task").attr("href","<?php echo URL::to('user/view_taskmanager_task'); ?>/"+task_id);
          $(".view_full_task").attr('data-element',task_id);
          $.ajax({
            url:"<?php echo URL::to('user/show_existing_comments'); ?>",
            type:"post",
            dataType:"json",
            data:{task_id:task_id},
            success:function(result)
            {
                CKEDITOR.replace('editor_1',
                 {
                  height: '150px',
                  enterMode: CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                  autoParagraph: false,
                  entities: false,
                  contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
                 });
              $("#hidden_task_id_task_specifics").val(task_id);
              $("#add_progress_files_from_task_specifics").attr("data-element",task_id);
              $(".progress_spam").html("");
              $("#existing_comments").html(result['output']);
              $(".title_task_details").html(result['title']);
              $(".user_ratings_div").html(result['user_ratings']);
              $(".task_specifics_modal").modal("show");
              if(result['two_bill'] == "1"){
                $(".2bill_image_comments").show();
              }
              else{
                $(".2bill_image_comments").hide();
              }
              $(".task_title_spec").html(result['task_specifics_name']);
              if(result['auto_close'] == "1")
              {
                $(".auto_close_task_comment").prop("checked",true);
              }
              else{
                $(".auto_close_task_comment").prop("checked",false);
              }
              $("#show_auto_close_msg").val(result['show_auto_close_msg']);
              var client_id = $(e.target).data("clientid");
              var icons='';
              if(client_id != ''){
                $.ajax({
                  url:"<?php echo URL::to('user/mui_icons_for_taskspecifics'); ?>",
                  type:"post",
                  data:{client_id:client_id},
                  success:function(icons)
                  {
                    $("#place_mui_icons").html(icons);                
                    $("body").removeClass("loading");
                  }
                });
              }
              else{
                $("#place_mui_icons").html(icons); 
                $("body").removeClass("loading");
              }
            }
          })
        },500);
      }
      if($(e.target).hasClass('edit_allocate_user'))
      {
        $("body").addClass("loading");
        var task_id = $(e.target).attr("data-element");
        var subject = $(e.target).attr("data-subject");
        var author = $(e.target).attr("data-author");
        var allocated = $(e.target).attr("data-allocated");
        $(".new_allocation").val("");
        $(".new_allocation").find("option").show();
        if(allocated == "0" || allocated == "")
        {
          $(".current_allocation").val(author);
          $(".new_allocation").find("option[value='"+author+"']").hide();
        }
        else{
          $(".current_allocation").val(allocated);
          $(".new_allocation").find("option[value='"+allocated+"']").hide();
        }
        $(".subject_allocation").html(subject);

        $("#hidden_task_id_allocation").val(task_id);
        $(".history_body").hide();
        $(".allocation_body").show();
        

        $.ajax({
          url:"<?php echo URL::to('user/show_all_allocations'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            $(".allocation_modal").modal("show");
            $("#hidden_task_id_history").val(task_id);
            $("#history_body").html(result);
            $("body").removeClass("loading");
          }
        })
      }
      if($(e.target).hasClass('show_task_allocation_history'))
      {
        $("body").addClass("loading");
        var task_id = $(e.target).attr("data-element");
        var subject = $(e.target).attr("data-subject");
        var author = $(e.target).attr("data-author");
        var allocated = $(e.target).attr("data-allocated");

        $(".new_allocation").val("");
        $(".new_allocation").find("option").show();
        if(allocated == "0" || allocated == "")
        {
          $(".current_allocation").val(author);
          $(".new_allocation").find("option[value='"+author+"']").hide();
        }
        else{
          $(".current_allocation").val(allocated);
          $(".new_allocation").find("option[value='"+allocated+"']").hide();
        }
        $(".subject_allocation").html(subject);

        $("#hidden_task_id_allocation").val(task_id);
        $(".history_body").show();
        $(".allocation_body").hide();

        $.ajax({
          url:"<?php echo URL::to('user/show_all_allocations'); ?>",
          type:"post",
          data:{task_id:task_id},
          success:function(result)
          {
            $("#hidden_task_id_history").val(task_id);
            $("#history_body").html(result);
            $(".allocation_modal").modal("show");
            $("body").removeClass("loading");
          }
        })
      }
      if(e.target.id == "allocate_now")
      {
        $("body").addClass("loading");
        var task_id = $("#hidden_task_id_allocation").val();
        var new_allocation = $(".new_allocation").val();
        if(new_allocation == "")
        {
          alert("Please choose the user to allocate the task.");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
            type:"post",
            data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
            dataType:"json",
            success:function(result)
            {
              var htmlval = $("#allocation_history_div_"+task_id).html();
              $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
              var htmlval2 = $("#history_body").find("tbody").html();
              $("#history_body").find("tbody").html(result['trval']+htmlval2);
              $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
              var count = 1;
              $("#allocation_history_div_"+task_id).find("p").each(function() {
                if(count > 5)
                {
                  $(this).detach();
                }
                count++;
              })
              $(".allocation_modal").modal("hide");
          setTimeout(function() {
            var author = $(".search_author").val();
			var open_task = $(".open_task_search:checked").val();
			var client_id = $("#copy_client_search").val();
			var client_id_search = $(".copy_client_search_class").val();
			var subject = $(".subject_search_class").val();
			var recurring = $("#hidden_recurring_task").val();
			var due_date = $(".due_date_search_class").val();
			var creation_date = $(".creation_date_search_class").val();
			var make_internal = $("#hidden_make_internal").val();
			var select_tasks = $(".select_internal_tasks").val();

            if(client_id_search == "")
            {
              $("#copy_client_search").val("");
              client_id = "";
            }

            $.ajax({
              url:"<?php echo URL::to('user/search_taskmanager_task'); ?>",
              type:"post",
              dataType:"json",
              data:{author:author,open_task:open_task,client_id:client_id,subject:subject,recurring:recurring,due_date:due_date,creation_date:creation_date,make_internal:make_internal,select_tasks:select_tasks},
              success:function(result)
              {
                if(result['count_rows'] >= 2) {
                  $(".consolidate_task_manager").prop("disabled", false);
                }
                else{
                  $(".consolidate_task_manager").prop("disabled", true);
                }
                $("#task_body_search").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);

                var layout = $("#hidden_compressed_layout").val();
					$(".tasks_tr").hide();
					$(".tasks_tr").next().hide();
					$(".hidden_tasks_tr").hide();

                    if(layout == "1")
					{
					  $(".tasks_tr:first").show();
					  $(".tasks_tr:first").next().show();
					  $(".table_layout").show();
					  $(".table_layout").find(".hidden_tasks_tr").show();
					  $(".table_layout").find(".hidden_tasks_tr:first").find("td").css("background","#E1E1E1");
            $(".empty_tr").hide();
					}
					else{
					  $(".tasks_tr").show();
					  $(".tasks_tr").next().show();
					  $(".table_layout").hide();
					  $(".table_layout").find(".hidden_tasks_tr").hide();
            $(".empty_tr").show();
					}

					if(layout == "1")
			      {
			        $(".open_layout_div").addClass("open_layout_div_change");
			        var open_tasks_height = $(".open_layout_div").height();
			        var margintop = parseInt(open_tasks_height);
			        $(".open_layout_div").css("position","fixed");
			        $(".open_layout_div").css("height","312px");
			        $(".open_layout_div").css("margin-top","28px");
			        if(open_tasks_height > 312)
			        {
			          $(".open_layout_div").css("overflow-y","scroll");
			        }
			        if(open_tasks_height < 50)
			        {
			          $(".table_layout").css("margin-top","20px");
			        }
			          else{
			            $(".table_layout").css("margin-top","363px");
			          }
			      }
			      else{
			        $(".open_layout_div").removeClass("open_layout_div_change");
			        $(".open_layout_div").css("position","unset");
			        $(".open_layout_div").css("height","auto");
			        $(".open_layout_div").css("overflow-y","unset");
			          $(".table_layout").css("margin-top","0px");
			          $(".open_layout_div").css("margin-top","53px");
			      }

                $("[data-toggle=popover]").popover({
	                  html : true,
	                  content: function() {
	                    var content = $(this).attr("data-popover-content");
	                    return $(content).children(".popover-body").html();
	                  },
	                  title: function() {
	                    var title = $(this).attr("data-popover-content");
	                    return $(title).children(".popover-heading").html();
	                  }
	              });
                $("body").removeClass("loading");
              }
            })
          },2000);
            }
          })
        }
      }
      if($(e.target).hasClass('edit_due_date'))
      {
        var subject = $(e.target).attr("data-subject");
        var due_date = $(e.target).attr("data-value");
        var task_id = $(e.target).attr("data-element");
        var color = $(e.target).attr("data-color");
        var correct_date = $(e.target).attr("data-duedate");
        $(".new_due_date").val("");

        $(".subject_due_date").html(subject);
        $(".current_due_date").val(due_date);
        $(".current_due_date").css("background",color);
        $("#hidden_task_id_due_date").val(task_id);

        $(".due_date_modal").modal("show");
        $(".due_date_edit").datetimepicker({
           defaultDate: fullDate,       
           format: 'L',
           format: 'DD-MMM-YYYY',
           minDate: correct_date,
        });
      }
      if(e.target.id == "due_date_change_button")
      {
        var task_id = $("#hidden_task_id_due_date").val();
        var new_date = $(".new_due_date").val();
        if(new_date == "")
        {
          alert("Please choose the Due Date to apply a new due date");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/taskmanager_change_due_date'); ?>",
            type:"post",
            data:{task_id:task_id,new_date:new_date},
            dataType:"json",
            success:function(result)
            {
              $(".edit_due_date_"+task_id).attr("data-duedate",result['new_change_date']);
              $(".edit_due_date_"+task_id).attr("data-value",result['new_date']);
              $(".edit_due_date_"+task_id).attr("data-color",result['color']);

              $("#due_date_task_"+task_id).html(result['new_date']);
              $("#due_date_task_"+task_id).css("color",result['color']);
              $(".due_date_modal").modal("hide");
            }
          })
        }
      }
      if($(e.target).hasClass('delete_attachments'))
      {
        e.preventDefault();
        var hrefval = $(e.target).attr("href");
        var r = confirm("Are you sure you want to delete this attachment?");
        if(r)
        {
          $.ajax({
            url:hrefval,
            type:"get",
            success:function(result)
            {
              $(e.target).parents("p").detach();
            }
          })
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
      if(e.target.id == "link_infile_progress_button")
      {
        var checkcount = $(".infile_progress_check:checked").length;
        var task_id = $("#hidden_progress_infiles_task_id").val();
        if(checkcount > 0)
        {
          var ids = '';
          $(".infile_progress_check:checked").each(function() {
            if(ids == "")
            {
              ids = $(this).val();
            }
            else{
              ids = ids+','+$(this).val();
            }
          });

          $("#hidden_infiles_progress_id_"+task_id).val(ids);
          $(".infiles_progress_modal").modal("hide");
          $.ajax({
            url:"<?php echo URL::to('user/show_linked_progress_infiles'); ?>",
            type:"post",
            data:{ids:ids,task_id:task_id},
            success:function(result)
            {
              $("#add_infiles_attachments_progress_div_"+task_id).html(result);
            }
          })
        }
      }
      if(e.target.id == "link_infile_completion_button")
      {
        var checkcount = $(".infile_completion_check:checked").length;
        var task_id = $("#hidden_completion_infiles_task_id").val();
        if(checkcount > 0)
        {
          var ids = '';
          $(".infile_completion_check:checked").each(function() {
            if(ids == "")
            {
              ids = $(this).val();
            }
            else{
              ids = ids+','+$(this).val();
            }
          });

          $("#hidden_infiles_completion_id_"+task_id).val(ids);
          $(".infiles_completion_modal").modal("hide");
          $.ajax({
            url:"<?php echo URL::to('user/show_linked_completion_infiles'); ?>",
            type:"post",
            data:{ids:ids,task_id:task_id},
            success:function(result)
            {
              $("#add_infiles_attachments_completion_div_"+task_id).html(result);
            }
          })
        }
      }
      if(e.target.id == "link_yearend_completion_button")
  {
    var checkcount = $(".yearend_completion_check:checked").length;
    var task_id = $("#hidden_completion_yearend_task_id").val();
    if(checkcount > 0)
    {
      var ids = '';
      $(".yearend_completion_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_yearend_completion_id_"+task_id).val(ids);
      $(".yearend_completion_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_completion_yearend'); ?>",
        type:"post",
        data:{ids:ids,task_id:task_id},
        success:function(result)
        {
          $("#add_yearend_attachments_completion_div_"+task_id).html(result);
        }
      })
    }
  }
      if(e.target.id == "show_incomplete_files")
      {
        if($(e.target).is(":checked"))
        {
          $(".tr_incomplete").hide();
        }
        else{
          $(".tr_incomplete").show();
        }
      }
      if($(e.target).hasClass('link_infile'))
      {
      //   var href = $(e.target).attr("data-element");
      // var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
      // if (printWin == null || typeof(printWin)=='undefined')
      // {
      //   alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
      // }
      }
      if($(e.target).hasClass('link_yearend'))
  {
    var href = $(e.target).attr("data-element");
  var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
  if (printWin == null || typeof(printWin)=='undefined')
  {
    alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
  }
  }
      if($(e.target).hasClass('infiles_link'))
      {
        var client_id = $("#client_search").val();
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
      if($(e.target).hasClass('infiles_link_progress'))
      {
        var task_id = $(e.target).attr("data-element");
        var client_id = $("#hidden_progress_client_id_"+task_id).val();
        var ids = $("#hidden_infiles_progress_id_"+task_id).val();

        if(client_id == "")
        {
          alert("Please select the client and then choose infiles");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/show_progress_infiles'); ?>",
            type:"post",
            data:{client_id:client_id,ids:ids},
            success: function(result)
            {
              $("#hidden_progress_infiles_task_id").val(task_id);
              $(".infiles_progress_modal").modal("show");
              $("#infiles_progress_body").html(result);
            }
          })
        }
      }
      if($(e.target).hasClass('infiles_link_completion'))
      {
        var task_id = $(e.target).attr("data-element");
        var client_id = $("#hidden_completion_client_id_"+task_id).val();
        var ids = $("#hidden_infiles_completion_id_"+task_id).val();

        if(client_id == "")
        {
          alert("Please select the client and then choose infiles");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/show_completion_infiles'); ?>",
            type:"post",
            data:{client_id:client_id,ids:ids},
            success: function(result)
            {
              $("#hidden_completion_infiles_task_id").val(task_id);
              $(".infiles_completion_modal").modal("show");
              $("#infiles_completion_body").html(result);
            }
          })
        }
      }
      if($(e.target).hasClass('yearend_link_completion'))
      {
        var task_id = $(e.target).attr("data-element");
        var client_id = $("#hidden_completion_client_id_"+task_id).val();
        var ids = $("#hidden_yearend_completion_id_"+task_id).val();

        if(client_id == "")
        {
          alert("Please select the client and then choose infiles");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/show_completion_yearend'); ?>",
            type:"post",
            data:{client_id:client_id,ids:ids},
            success: function(result)
            {
              $("#hidden_completion_yearend_task_id").val(task_id);
              $(".yearend_completion_modal").modal("show");
              $("#yearend_completion_body").html(result);
            }
          })
        }
      }
      if(e.target.id == "create_new_task")
      {
        $(".create_new_model").find(".job_title").html("New Task Creator");
        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
        var user_id = $(".select_user_home").val();
        $(".create_new_model").modal("show");
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
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
       });
        $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
        $(".author_email").val("<?php echo Session::get('taskmanager_user_email'); ?>");
        $("#action_type").val("1");
        $(".allocate_user_add").val("");
        $(".client_search_class").val("");
        $("#client_search").val("");
        $(".task-choose_internal").html("Select Task");
        $(".subject_class").val("");
        $(".task_specifics_add").show();
        $(".task_specifics_copy").hide();
        CKEDITOR.instances['editor_2'].setData("");
        
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
        $("#hidden_infiles_id").val("");
        $("#add_infiles_attachments_div").html("");
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
        $(".allocate_email").val("");
        
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
      if(e.target.id == "change_taskname_button")
      {
        var taskid = $(".hidden_task_id_change_task").val();
        var tasktype = $("#idtask_change").val();
        $.ajax({
          url:"<?php echo URL::to('user/change_task_name_taskmanager'); ?>",
          type:"post",
          data:{taskid:taskid,tasktype:tasktype},
          success:function(result)
          {
            $(".task_name_"+taskid).html(result);
            $(".change_taskname_modal").modal("hide");
          }
        })
      }
      if($(e.target).hasClass('tasks_li_internal_copy'))
      {
        var taskid = $(e.target).attr('data-element');
        $("#idtask_copy").val(taskid);
        $("#edit_idtask").val(taskid);
        $(".task-choose_internal_copy:first-child").text($(e.target).text());
      }
      if($(e.target).hasClass('fileattachment_checkbox'))
      {
        var value = $(e.target).val();
        $("body").addClass("loading");
        if($(e.target).is(":checked"))
        {
          $.ajax({
            url:"<?php echo URL::to('user/fileattachment_status'); ?>",
            type:"post",
            data:{id:value,status:1},
            success: function(result)
            {
              $(e.target).parent().find(".add_text").prop("disabled",true);
              $("body").removeClass("loading");
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/fileattachment_status'); ?>",
            type:"post",
            data:{id:value,status:0},
            success: function(result)
            {
              $(e.target).parent().find(".add_text").prop("disabled",false);
              $("body").removeClass("loading");
            }
          });
        }
      }
      if($(e.target).parents(".auto_save_date").length > 0)
      {
        var file_id = $(e.target).parents(".auto_save_date").find(".complete_date").attr("data-element");
        $("#hidden_file_id").val(file_id);
      }
      if($(e.target).hasClass('image_submit'))
      {
        var files = $(e.target).parent().find('.image_file').val();
        if(files == '' || typeof files === 'undefines')
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
      if($(e.target).hasClass('image_submit_add'))
      {
        var files = $(e.target).parent().find('.image_file_add').val();
        if(files == '' || typeof files === 'undefines')
        {
          $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
          return false;
        }
        else{
          $(e.target).parents('.modal-body').find('.img_div').toggle();
        }
      }
      else{
        $(".img_div").each(function() {
          $(this).hide();
        });
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
          $(e.target).parents('td').find('.notepad_div').toggle();
          $(e.target).parents('td').find('.notepad_div_notes').toggle();
        }
      }
      else{
        $(".notepad_div").each(function() {
          $(this).hide();
        });
        $(".notepad_div_notes").each(function() {
          $(this).hide();
        });
      }
      if($(e.target).hasClass('notepad_submit_add'))
      { 
        var contents = $(e.target).parent().find('.notepad_contents_add').val();
        if(contents == '' || typeof contents === 'undefined')
        {
          $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
          return false;
        }
        else{
          $(e.target).parents('td').find('.notepad_div_notes_add').toggle();
        }
      }
      else{
        $(".notepad_div_notes_add").each(function() {
          $(this).hide();
        });
      }

      if($(e.target).hasClass('notepad_progress_submit'))
      { 
        var contents = $(e.target).parent().find('.notepad_contents_progress').val();
        if(contents == '' || typeof contents === 'undefined')
        {
          $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
          return false;
        }
        else{
          $(e.target).parents('td').find('.notepad_div_progress_notes').toggle();
        }
      }
      else{
        $(".notepad_div_progress_notes").each(function() {
          $(this).hide();
        });
      }

      if($(e.target).hasClass('notepad_completion_submit'))
      { 
        var contents = $(e.target).parent().find('.notepad_contents_completion').val();
        if(contents == '' || typeof contents === 'undefined')
        {
          $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
          return false;
        }
        else{
          $(e.target).parents('td').find('.notepad_div_completion_notes').toggle();
        }
      }
      else{
        $(".notepad_div_completion_notes").each(function() {
          $(this).hide();
        });
      }
      if($(e.target).hasClass('image_file'))
      {
        $(e.target).parents('td').find('.img_div').toggle();
        $(e.target).parents('.modal-body').find('.img_div').toggle();
      }
      if($(e.target).hasClass('image_file_add'))
      {
        $(e.target).parents('.modal-body').find('.img_div').toggle();
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
      if($(e.target).hasClass('notepad_contents'))
      {
        $(e.target).parents('td').find('.notepad_div').toggle();
        $(e.target).parents('td').find('.notepad_div_notes').toggle();
      }
      if($(e.target).hasClass('notepad_contents_add'))
      {
        $(e.target).parents('.modal-body').find('.notepad_div_notes_add').toggle();
      }
      if($(e.target).hasClass('notepad_contents_progress'))
      {
        $(e.target).parents('.notepad_div_progress_notes').toggle();
      }
      if($(e.target).hasClass('notepad_contents_completion'))
      {
        $(e.target).parents('.notepad_div_completion_notes').toggle();
      }
      if($(e.target).hasClass('notepad_submit_add'))
      {
        var contents = $(".notepad_contents_add").val();
        $.ajax({
          url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
          type:"post",
          data:{contents:contents},
          dataType:"json",
          success: function(result)
          {
            $("#attachments_text").show();
            $("#add_notepad_attachments_div").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_add' data-task='"+result['file_id']+"'>Remove</a></p>");
            $(".notepad_div_notes_add").hide();
          }
        });
      }
      if($(e.target).hasClass('notepad_progress_submit'))
      {
        var contents = $(e.target).parents(".notepad_div_progress_notes").find(".notepad_contents_progress").val();
        var task_id = $(e.target).parents(".notepad_div_progress_notes").find("#hidden_task_id_progress_notepad").val();
        var user_id = $(e.target).parents(".notepad_div_progress_notes").find(".notepad_user").val();
        $.ajax({
          url:"<?php echo URL::to('user/taskmanager_notepad_contents_progress'); ?>",
          type:"post",
          data:{contents:contents,task_id:task_id,user_id:user_id},
          dataType:"json",
          success: function(result)
          {
            $("#add_notepad_attachments_progress_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".notepad_div_progress_notes").hide();
          }
        });
      }
      if($(e.target).hasClass('notepad_completion_submit'))
      {
        var contents = $(e.target).parents(".notepad_div_completion_notes").find(".notepad_contents_completion").val();
        var task_id = $(e.target).parents(".notepad_div_completion_notes").find("#hidden_task_id_completion_notepad").val();
        $.ajax({
          url:"<?php echo URL::to('user/taskmanager_notepad_contents_completion'); ?>",
          type:"post",
          data:{contents:contents,task_id:task_id},
          dataType:"json",
          success: function(result)
          {
            $("#add_notepad_attachments_completion_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".notepad_div_completion_notes").hide();
          }
        });
      }
      if($(e.target).hasClass('trash_imageadd'))
      {
        $("body").addClass("loading");
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
      if($(e.target).hasClass('fa-plus-add'))
      {
        var pos = $(e.target).position();
        var leftposi = parseInt(pos.left);
        $(e.target).parent().find('.img_div_add').toggle();
        Dropzone.forElement("#imageUpload1").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
      }
      if($(e.target).hasClass('faplus_progress'))
      {
        var task_id = $(e.target).attr("data-element");
        $("#hidden_task_id_progress").val(task_id);
        $(".dropzone_progress_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
      }
      if($(e.target).hasClass('add_progress_files_from_task_specifics'))
      {
        var task_id = $(e.target).attr("data-element");
        $("#hidden_task_id_progress").val(task_id);
        $(".dropzone_progress_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
      }
      if($(e.target).hasClass('faplus_completion'))
      {
        var task_id = $(e.target).attr("data-element");
        $("#hidden_task_id_completion").val(task_id);
        $(".dropzone_completion_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
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

      if($(e.target).hasClass('remove_dropzone_attach_add'))
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
      if($(e.target).hasClass('remove_notepad_attach_add'))
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
      
      if($(e.target).hasClass('trash_image'))

      {



        var r = confirm("Are You sure you want to delete");

        if (r == true) {

          var imgid = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_delete_image'); ?>",

              type:"get",

              data:{imgid:imgid},

              success: function(result) {

                window.location.reload();

              }

          });

        }

      }



      if($(e.target).hasClass('delete_all_image')){



        var r = confirm("Are You sure you want to delete all the attachments?");

        if (r == true) {

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_delete_all_image'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                window.location.reload();

              }

          });

        }

      }

      if($(e.target).hasClass('download_all_image')){

          $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_all_image'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                  SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });

      }

      if($(e.target).hasClass('download_rename_all_image')){

          $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_rename_all_image'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                  SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });

      }
      if($(e.target).hasClass('download_b_all_image')){

          var lenval = $(e.target).parents("table").find(".b_check:checked").length;
          if(lenval > 0)
          {
              $("body").addClass("loading");

              var id = $(e.target).attr('data-element');

              $.ajax({

                  url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

                  type:"get",

                  data:{type:"b",id:id},

                  success: function(result) {

                      SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                      setTimeout(function() {

                        $.ajax({

                          url:"<?php echo URL::to('user/delete_file_link'); ?>",

                          type:"post",

                          data:{result:result},

                          success: function(result)

                          {

                            $("body").removeClass("loading");

                          }

                        });

                      },3000);

                  }

              });
          }
          else{
            alert("None of the checkbox is checked to download the files");
          }
      }
      if($(e.target).hasClass('download_p_all_image')){
          var lenval = $(e.target).parents("table").find(".p_check:checked").length;
          if(lenval > 0)
          {
            $("body").addClass("loading");

            var id = $(e.target).attr('data-element');

            $.ajax({

                url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

                type:"get",

                data:{type:"p",id:id},

                success: function(result) {

                    SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                    setTimeout(function() {

                      $.ajax({

                        url:"<?php echo URL::to('user/delete_file_link'); ?>",

                        type:"post",

                        data:{result:result},

                        success: function(result)

                        {

                          $("body").removeClass("loading");

                        }

                      });

                    },3000);

                }

            });
          }
          else{
            alert("None of the checkbox is checked to download the files");
          }
      }
      if($(e.target).hasClass('download_s_all_image')){
          var lenval = $(e.target).parents("table").find(".s_check:checked").length;
          if(lenval > 0)
          {
            $("body").addClass("loading");

            var id = $(e.target).attr('data-element');

            $.ajax({

                url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

                type:"get",

                data:{type:"s",id:id},

                success: function(result) {

                    SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                    setTimeout(function() {

                      $.ajax({

                        url:"<?php echo URL::to('user/delete_file_link'); ?>",

                        type:"post",

                        data:{result:result},

                        success: function(result)

                        {

                          $("body").removeClass("loading");

                        }

                      });

                    },3000);

                }

            });
          }
          else{
            alert("None of the checkbox is checked to download the files");
          }
      }
      if($(e.target).hasClass('download_o_all_image')){
          var lenval = $(e.target).parents("table").find(".o_check:checked").length;
          if(lenval > 0)
          {
            $("body").addClass("loading");

            var id = $(e.target).attr('data-element');

            $.ajax({

                url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

                type:"get",

                data:{type:"o",id:id},

                success: function(result) {

                    SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                    setTimeout(function() {

                      $.ajax({

                        url:"<?php echo URL::to('user/delete_file_link'); ?>",

                        type:"post",

                        data:{result:result},

                        success: function(result)

                        {

                          $("body").removeClass("loading");

                        }

                      });

                    },3000);

                }

            });
          }
          else{
            alert("None of the checkbox is checked to download the files");
          }
      }

      if($(e.target).hasClass('delete_all_notes_only')){



        var r = confirm("Are You sure you want to delete all the attachments?");

        if (r == true) {

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_delete_all_notes_only'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                window.location.reload();

              }

          });

        }

      }



      if($(e.target).hasClass('download_all_notes_only')){

        $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_all_notes_only'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });

      }  
      if($(e.target).hasClass('bpso_all_check')){
        $("body").addClass("loading");
        var id = $(e.target).attr('id');
        var type = $(e.target).attr('data-element');
        $.ajax({
              url: "<?php echo URL::to('user/bpso_all_check') ?>",
              type:"post",        
              data:{id:id, type:type},
              dataType: "json",       
              success:function(result){
                $("#bspo_id_"+result['id']).html(result['table_content']);
                $("body").removeClass("loading");
                $('[data-toggle="tooltip"]').tooltip();
                               
          }
        });

      }




      if($(e.target).hasClass('delete_all_notes')){



        var r = confirm("Are You sure you want to delete all the attachments?");

        if (r == true) {

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_delete_all_notes'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                window.location.reload();

              }

          });

        }

      }

      if($(e.target).hasClass('download_all_notes')){

        $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_all_notes'); ?>",

              type:"get",

              data:{id:id},

              success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });

      }



      if($(e.target).hasClass('fa-pencil-square')){



        var pos = $(e.target).position();

        var leftposi = parseInt(pos.left) - 200;

        $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();



      }



      if($(e.target).hasClass('fanotepad_progress')){
        var pos = $(e.target).position();
        var leftposi = parseInt(pos.left) - 200;
        $(e.target).parent().find('.notepad_div_progress_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
      }
      if($(e.target).hasClass('fanotepad_completion')){
        var pos = $(e.target).position();
        var leftposi = parseInt(pos.left) - 200;
        $(e.target).parent().find('.notepad_div_completion_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
      }
       if($(e.target).hasClass('fanotepadadd')){
        var clientid = $("#client_search").val();
        var pos = $(e.target).position();
        var leftposi = parseInt(pos.left) - 20;
        $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
      }





      if($(e.target).hasClass('internal_checkbox')){

        var id = $(e.target).attr('data-element');

        if($(e.target).is(':checked')){

          $.ajax({

            url:"<?php echo URL::to('user/infile_internal'); ?>",

            type:"get",

            data:{internal:1,id:id},

            success: function(result) {

              //$(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});

            }

          });

        }

        else{

          $.ajax({

            url:"<?php echo URL::to('user/infile_internal'); ?>",

            type:"get",

            data:{internal:0,id:id},

            success: function(result) {

              //$(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

            }



          });

        }

      }

      if($(e.target).hasClass('reportclassdiv'))
      {
        $(".report_div").toggle();
      }

      if($(e.target).hasClass('ok_button'))
      {
        var check_option = $(".class_invoice:checked").val();
        $("#show_incomplete_report").prop("checked", true);
        $(".report_show_incomplete").val(1);

        if(check_option === "" || typeof check_option === "undefined")
        {
          alert("Please select atleast one report type to move forward.");
        }
        else{
          $(".report_type").val(1);
          var id = $('input[name="report_infile"]:checked').val();
          $(".class_invoice").prop("checked", false);
          if(id == 1){
              $("#report_tbody").html('');
              $("body").addClass("loading");
              $.ajax({
                  url: "<?php echo URL::to('user/report_infile') ?>",
                  data:{id:0},
                  type:"post",
                  success:function(result){
                     $(".report_infile_model").modal("show");                 
                     $(".report_div").hide();
                     $("body").removeClass("loading");
                     $("#report_tbody").html(result);
                     $(".select_all").hide();
                     $(".single_client_button").show();
                     $(".all_client_button").hide();                 
              }
            });
          }
          else{
            $(".report_type").val(2);
            $("#report_tbody").html('');
            $("body").addClass("loading");
              $.ajax({
                  url: "<?php echo URL::to('user/report_infile') ?>",
                  data:{id:1},
                  type:"post",
                  success:function(result){  
                    $(".report_infile_model").modal("show");
                    $(".report_div").hide();
                    $("body").removeClass("loading");      
                    $("#report_tbody").html(result);
                    $(".select_all").show(); 
                    $(".single_client_button").hide();
                    $(".all_client_button").show();
              }
            });
          }
        }
      }


      if(e.target.id == "select_all_class") {
        if($(e.target).is(":checked")){
          $(".select_client").each(function() {
            $(this).prop("checked",true);
          });
        }
        else{
          $(".select_client").each(function() {
            $(this).prop("checked",false);
          });
        }
      }


      if(e.target.id == "save_as_pdf")
      {
        $("#report_pdf_type_two_tbody").html('');
        var status = $(".report_show_incomplete").val();
        if($(".select_client:checked").length)
        {
          $("body").addClass("loading");
            var checkedvalue = '';
            var size = 100;
            $(".select_client:checked").each(function() {
              var value = $(this).val();
              if(checkedvalue == "")
              {
                checkedvalue = value;
              }
              else{
                checkedvalue = checkedvalue+","+value;
              }
            });
            var exp = checkedvalue.split(',');
            var arrayval = [];
            for (var i=0; i<exp.length; i+=size) {
                var smallarray = exp.slice(i,i+size);
                arrayval.push(smallarray);
            }
            $.each(arrayval, function( index, value ) {
                setTimeout(function(){ 
                  var imp = value.join(',');
                  $.ajax({
                    url:"<?php echo URL::to('user/infile_report_pdf'); ?>",
                    type:"post",
                    data:{value:imp, status:status},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody").append(result);
                      
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/download_infile_report_pdf'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('public/infile_report'); ?>/"+result,result);
                          }
                        });
                      }
                    }
                  });
                }, 3000);
            });
            
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one client to continue.");
        }
      }
      if(e.target.id == "save_as_csv")
      {
        $("body").addClass("loading");
        var status = $(".report_show_incomplete").val();
        if($(".select_client:checked").length)
        {
          var checkedvalue = '';
          $(".select_client:checked").each(function() {
              var value = $(this).val();
              if(checkedvalue == "")
              {
                checkedvalue = value;
              }
              else{
                checkedvalue = checkedvalue+","+value;
              }
          });
          $.ajax({
            url:"<?php echo URL::to('user/infile_report_csv'); ?>",
            type:"post",
            data:{value:checkedvalue, status:status},
            success: function(result)
            {
              SaveToDisk("<?php echo URL::to('public/infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
            }
          });
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one client to continue.");
        }
      }

      if(e.target.id == "single_save_as_csv")
      {
        $("body").addClass("loading");
        var status = $(".report_show_incomplete").val();
        if($(".select_client:checked").length)
        {
          var checkedvalue = '';
          $(".select_client:checked").each(function() {
              var value = $(this).val();
              if(checkedvalue == "")
              {
                checkedvalue = value;
              }
              else{
                checkedvalue = checkedvalue+","+value;
              }
          });
          $.ajax({
            url:"<?php echo URL::to('user/infile_report_csv_single'); ?>",
            type:"post",
            data:{value:checkedvalue, status:status},
            success: function(result)
            {
              SaveToDisk("<?php echo URL::to('public/infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
            }
          });
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one client to continue.");
        }
      }


      if(e.target.id == "single_save_as_pdf")
      {
        $("#report_pdf_type_two_tbody_single").html('');
        var status = $(".report_show_incomplete").val();
        console.log(status);
        if($(".select_client:checked").length)
        {
          $("body").addClass("loading");
            var checkedvalue = '';
            var size = 100;
            $(".select_client:checked").each(function() {
              var value = $(this).val();
              if(checkedvalue == "")
              {
                checkedvalue = value;
              }
              else{
                checkedvalue = checkedvalue+","+value;
              }
            });
            var exp = checkedvalue.split(',');
            var arrayval = [];
            for (var i=0; i<exp.length; i+=size) {
                var smallarray = exp.slice(i,i+size);
                arrayval.push(smallarray);
            }
            $.each(arrayval, function( index, value ) {
                setTimeout(function(){ 
                  var imp = value.join(',');
                  $.ajax({
                    url:"<?php echo URL::to('user/infile_report_pdf_single'); ?>",
                    type:"post",
                    data:{value:imp, status:status},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody_single").append(result);
                      
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two_single").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/download_infile_report_pdf_single'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('public/infile_report'); ?>/"+result,result);
                          }
                        });
                      }
                    }
                  });
                }, 3000);
            });
            
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one client to continue.");
        }
      }

      if(e.target.id == 'show_incomplete_report'){
        var type = $(".report_type").val();
        $("body").addClass("loading");
        if($(e.target).is(':checked'))
        {
          $.ajax({
            url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
            type:"post",
            data:{id:0, type:type},
            success: function(result)
            {
              $("#report_tbody").html(result);
              $(".report_show_incomplete").val(1);
              $("body").removeClass("loading");
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
            type:"post",
            data:{id:1, type:type},
            success: function(result)
            {
              $("#report_tbody").html(result);
              $(".report_show_incomplete").val(2);
              $("body").removeClass("loading");
            }
          });
        }

      }
  }
})
$(window).change(function(e) {
  if($(e.target).hasClass('select_user_home'))
  {
    var value = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/change_taskmanager_user'); ?>",
      type:"post",
      data:{user:value},
      success: function(result)
      {
        window.location.replace("<?php echo URL::to('user/task_manager'); ?>");
      }
    });
  }
})
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

$(".image_file_add").change(function(){

  var lengthval = $(this.files).length;

  var htmlcontent = '';

  var attachments = $('#add_attachments_div').html();

  for(var i=0; i<= lengthval - 1; i++)

  {

    var sno = i + 1;

    if(htmlcontent == "")

    {

      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';

    }

    else{

      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';

    }

  }

  $('#add_attachments_div').html(attachments+' '+htmlcontent);

  $("#attachments_text").show();

  $(".img_div").hide();

});
fileList = new Array();

Dropzone.options.imageUpload = {
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
            file.serverId = obj.id;
            $("#add_files_attachments_progress_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' class='file_attachments' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".dropzone_progress_modal").modal("hide");
            $(".dropzone_completion_modal").modal("hide");

            $(".add_progress_attachments").append('<p class="pending_attachment" data-element="'+obj.task_id+'" data-user="'+obj.from_user+'">'+obj.message+'</p>')
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            $(".progress_spam").html("Progress files added Successfully");
            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            Dropzone.forElement("#imageUpload2").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

            if($(".task_specifics_modal").hasClass('in')){

            }
            else{
              var obj = {};
              obj.message = []; 
              obj.task_id = []; 
              obj.user_id = []; 
              $(".add_progress_attachments").find('p').each(function(index,value) {
                var message = $(this).html();
                var task_id = $(this).attr("data-element");
                var user_id = $(this).attr("data-user");

                obj.message.push([message]);
                obj.task_id.push([task_id]);
                obj.user_id.push([user_id]);
              });

              var messages = JSON.stringify(obj);

              $.ajax({
                url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                type:"post",
                data:{messages:messages},
                success:function(result){
                  $(".add_progress_attachments").html("");
                }
              });
            }
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

Dropzone.options.imageUpload2 = {
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
            file.serverId = obj.id;
            $("#add_files_attachments_completion_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' class='file_attachments' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".dropzone_progress_modal").modal("hide");
            $(".dropzone_completion_modal").modal("hide");
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");

            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            Dropzone.forElement("#imageUpload2").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
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

Dropzone.options.imageUpload1 = {
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_add' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_add' data-task='"+obj.file_id+"'>Remove</a></p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
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
$.ajaxSetup({async:false});
$( "#create_job_form" ).validate({

    rules: {
        select_user : {required: true},
        created_date : { required: true},   
        client_name : { required: true},   
        due_date : { required: true},   
    },
    messages: {
        select_user : {
          required : "Please select the Author",
        },
        created_date : {
            required : "Creation Date is required",
        },
        client_name : {
            required : "Client Name is required",
        },
        due_date : {
            required : "Due Date is required",
        },
    },
});
</script>
@stop
