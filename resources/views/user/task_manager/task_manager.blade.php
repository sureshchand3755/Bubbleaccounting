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
  
.modal_load_completion {
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
body.loading_completion_files {
    overflow: hidden;   
}
body.loading_completion_files .modal_load_completion {
    display: block;
}
.modal_load_bpso {
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
body.loading_bpso {
    overflow: hidden;   
}
body.loading_bpso .modal_load_bpso {
    display: block;
}
.menu-logo{
  margin-left: 14px;
}
.dmenu {
  position: absolute;
  top: -40px;
  left: 112px;
}
</style>
<?php
$date = date("Y-m-d");
$reactivate = 0;
if(Session::has('taskmanager_user'))
{
  $userid = Session::get('taskmanager_user');
  $check_db = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$userid)->first();

  if(($check_db))
  {
    if($check_db->park_date == $date)
    {
      if($check_db->park_status == 1)
      {
        $reactivate = 0;
      }
      else{
        $reactivate = 1;
      }
    }
    else{
      $dataval['park_date'] = $date;
      $dataval['park_status'] = 0;

      DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$userid)->update($dataval);
      $reactivate = 1;
    }
  }

  if($reactivate == 1)
  {
    ?>
    <script>
      $.ajaxSetup({
        headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
        var r = confirm("Do you want to review parked tasks now?");
        if(r)
        {
          $("body").addClass("loading");
          $.ajax({
            url:"<?php echo URL::to("user/reactivate_park_task"); ?>",
            type:"post",
            data:{userid:"<?php echo $userid; ?>",date:"<?php echo $date; ?>"},
            success:function(result)
            {
              window.location.reload();
            }
          })
        }
        else{
          $("body").addClass("loading");
          $.ajax({
            url:"<?php echo URL::to("user/change_taskmanager_park_status"); ?>",
            type:"post",
            data:{userid:"<?php echo $userid; ?>"},
            success:function(result)
            {
              $(".taskname_sort_val").find("img").detach();
            }
          })
        }
    </script>
    <?php
  }
}
?>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
.margintop20{
  margin-top:20px !important;
  margin-bottom: 0px !important;
}
.dropdown-item{
  float: left;
width: 100%;
padding: 10px;
}
.last_td_display .common_black_button { padding:8px 4px !important; }
.table_layout { border:0px; }
.table_layout #task_body_layout td{padding:0px !important;}
.tasks_drop {text-align: left !important; }
.existing_comments > p { margin-bottom: 0px !important; }
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.fa-sort{ cursor:pointer; }
.error{
  color:#f00;
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
  font-weight: 700;
}
.link_infile_p{
  color: #002bff;
  text-decoration: underline;
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
  background: #fff;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td
{
  border-top: 0px solid;
  color:#000 !important;
  text-align: left;
  font-weight:600;
  padding: 6px 10px;
  /*background: #f5f5f5;*/
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
.avoid_email{ color:green; font-size:18px;}
.retain_email { color: #f00 !important; }
.hidden_tasks_tr>td{
  cursor:pointer;
}
#colorbox, #cboxWrapper { z-index:99999999999; }
.start_group{clear:both;}
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">

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
            <input type="hidden" name="hidden_task_id_author" id="hidden_task_id_author" value="">
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
                          title="View Task" style="padding-left:15px;font-size:20px;font-weight: 500;float: right;margin-right: -3%;"></a>
                </h4>
              </div>
            </div>
            
            <h5 class="title_task_details" style="font-size:18px;font-weight:600; text-indent: 14px;"></h5>
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
              <div class="existing_comments" id="existing_comments" style="width:100%;background: #c7c7c7;padding:10px;min-height:300px;height:300px;overflow-y: scroll;font-size: 16px"></div>
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

                <div class="col-md-2 internal_tasks_group" style="display: none;">
                  <label style="margin-top:5px">Select Task:</label>
                </div>
                <div class="col-md-8 internal_tasks_group" style="display: none;">
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
                  <div class="row" >
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
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
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
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
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

<div class="modal fade project_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Projects</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-12">
                  <a href="javascript:" class="common_black_button add_new_project" style="float:right;">Add Project</a>
                </div>
                <div class="col-lg-12 modal_max_height">
                  <div class="table-responsive">
                    <table class="table own_table_white" >
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Project Name</th>
                          <th>Author</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="project_tbody">
            <?php 
            $projectlist = DB::table('projects')->get();
            $output_project='';
            $i=1;
            if(is_countable($projectlist) && count($projectlist) > 0){
              foreach ($projectlist as $project){
                $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', $project->author)->first();
                $usernamedtails = '';
                if($user_details) {
                  $usernamedtails = $user_details->lastname.' '.$user_details->firstname;
                }
                $output_project.='
                <tr>
                  <td>'.$i.'</td>
                  <td style="background: #fff;">'.$project->project_name.'</td>
                  <td style="background: #fff;">'.$usernamedtails.'</td>
                  <td style="width:50px; text-align:center; background: #fff;">
                  <a href="javascript:">
                    <i class="fa fa-pencil-square edit_project" data-element="'.base64_encode($project->project_id).'"></i>
                  </a>
                  </td>
                </tr>';
                $i++;
              }
            }
            else{
              $output_project='
                <tr>
                  <td></td>
                  <td></td>
                  <td>No Data found</td>
                  <td></td>
                </tr>
              ';
            }

            echo $output_project;
            ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>

<div class="modal fade add_edit_project_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index:99999999999999; background: rgba(0, 0, 0, 0.3) !important;">
  <div class="modal-dialog modal-sm" role="document" >
    <!-- <form method="post" action="<?php //echo URL::to('user/add_edit_project')?>" id="project_form"> -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title project_title" style="font-weight:700;font-size:20px"></h4>
          </div>
          <div class="modal-body">  
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label>Enter Project Name</label>
                    <input type="text" class="form-control project_name" placeholder="Enter Project Name" name="project_name" required>
                    <label id="project_name-error" style="display: none;" class="error" for="project_name">Please enter Project Name</label>
                  </div>
                  <div class="form-group">
                    <label>Select Author</label>
                    <select name="select_author" class="form-control select_author" required>
                    <option value="">Select Author</option>        
                      <?php
                      $selected = '';
                      if(($userlist)){
                        foreach ($userlist as $user) {
                          /*if(Session::has('taskmanager_user'))
                          {
                            if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }*/
                      ?>
                        <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                  <label id="select_author-error" style="display: none;" class="error" for="select_author">Please select Author</label>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="project_id" readonly value="0" name="project_id">
            <input type="submit" class="common_black_button project_add_button" value="" name="">
          </div>
        </div>
    <!-- @csrf
</form> -->
  </div>
</div>
<?php
$taskmanager_settings = DB::table("taskmanager_settings")->where('practice_code',Session::get('user_practice_code'))->first();
?>
<div class="modal fade taskmanager_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="taskmanager_settings_form" id="taskmanager_settings_form" method="post" action="<?php echo URL::to('user/save_taskmanager_settings'); ?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >Task Manager Settings</h4>
            </div>
            <div class="modal-body">  
                <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                <?php
                if($taskmanager_settings->email_header_url == '') {
                  $default_image = DB::table("email_header_images")->first();
                  if($default_image->url == "") {
                    $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                  }
                  else{
                    $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                  }
                }
                else {
                  $image_url = URL::to($taskmanager_settings->email_header_url.'/'.$taskmanager_settings->email_header_filename);
                }
                ?>
                <img src="<?php echo $image_url; ?>" class="email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                <input type="button" name="email_header_img_btn" class="common_black_button email_header_img_btn" value="Browse">

                <h4>Taskmanager CC Email ID</h4>
                <input id="validation-cc-email"
                       class="form-control"
                       placeholder="Enter Taskmanager CC Email ID"
                       value="<?php echo $taskmanager_settings->taskmanager_cc_email; ?>"
                       name="taskmanager_cc_email"
                       type="text"
                       required>  
            </div>
            <div class="modal-footer" style="clear:both">
              <input type="submit" class="common_black_button" id="taskmanager_submit" value="Submit">
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
          <form action="<?php echo URL::to('user/edit_taskmanager_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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

<div class="content_section">
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
        
        <div class="col-lg-7 padding_00">
            <label style="float:left;width: 6%; margin-top: 7px;margin-left: 22px;">User:</label>
            <select name="select_user" class="form-control select_user_home" style="float:left;width:18%;">
              <option value="">Select User</option>        
                <?php
                $selected = '';
                $all_user_list = DB::table('user')->where('practice',Session::get('user_practice_code'))->orderBy('lastname','asc')->where('user_status', 0)->get();
                if(($all_user_list)){
                  foreach ($all_user_list as $user) {
                    if(Session::has('taskmanager_user'))
                    {
                      if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                      else{ $selected = ''; }
                    }

                    if($user->disabled == 1){
                      $active_user = 'none';
                      $active_class = 'inactive_user';
                    }
                    else{
                      $active_user = 'block';
                      $active_class = '';
                    }

                ?>
                  <option class="<?php echo $active_class?>" style="display: <?php echo $active_user ?>" value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                <?php
                  }
                }
                //where('user_status', 0)->where('disabled',0)->

                /*$all_user_list = DB::table('user')->orderBy('lastname','asc')->where('user_status', 0)->get();

                $output_alluser='';
                if(($all_user_list)){
                  foreach ($all_user_list as $user) {
                    

                    if($user->disabled == 1){
                      $active_user = 'none';
                      $class_user = 'inactive_user';
                    }
                    elseif($user->user_id == Session::get('task_manager_user')){
                      $active_user = 'block';
                      $class_user = '';                      
                    }
                    else{
                      $active_user = 'block';
                      $class_user = '';
                    }

                    if(Session::has('task_manager_user'))
                    {
                      if($user->user_id == Session::get('task_manager_user')){ 
                        $selected = 'selected';
                      }
                      else{
                        $selected = '';
                      }
                    }

                    $output_alluser.='
                    <option class="'.$class_user.'" style="display:'.$active_user.'" value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.'&nbsp;'.$user->firstname.'</option>
                    ';
                  }
                }

                echo $output_alluser;*/
                ?>
            </select>
            <a href="javascript:" class="fa fa-refresh refresh_task" title="Refresh Tasks for this user" style="padding:12px;background: #dfdfdf;float:left"></a>

            <div style="float: left;margin-top:5px;margin-left: 15px;">
              <input type="checkbox" name="hide_inactive_users" class="hide_inactive_users" id="hide_inactive_users" value="1"><label for="hide_inactive_users" title="Show Inactive Users">Show Inactive users</label>
            </div>

            <label style="float:left;width: 5%; margin-top: 7px;margin-left: 35px;text-align: right;margin-right: 7px;">View:</label>
            <select name="select_view" class="form-control select_view" style="float:left;width:18%;">
              <option value="1">All Tasks Allocated</option>
              <option value="2">RedLine Tasks Allocated</option>
              <option value="3">Authored by All Tasks</option>
            </select>
            <label style="float:left;width: 11%; margin-top: 8px;margin-left: 15px;text-align: right;margin-right: 7px;">Quick Task View:</label>
            <input type="text" class="form-control quick_task_search" value="" placeholder="Enter TaskID" style="float:left;width:11%">
            <a href="javascript:" class="fa fa-eye quick_task_search_btn" title="Quick Task view Search" style="padding:10px;background: #dfdfdf;float:left;font-size: 18px;"></a>

        </div>
        
        <div class="col-lg-5" style="margin-left:-35px;margin-top: 3px;">
          <div class="dropdown" style="float: right;">
            <a href="javascript:" id="settings_taskamanager" class="fa fa-cog common_black_button" title="Taskmanager Settings" style="float:right;"></a>
            <button class="common_black_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              View Menu
            </button>
            <div class="dropdown-menu dmenu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item change_layout" href="javascript:" data-element="1">Full Layout</a>
              <a class="dropdown-item change_layout" href="javascript:" data-element="0">Compressed Layout</a>
              <a class="dropdown-item change_layout" href="javascript:" data-element="2">Modern View</a>
            </div>
          </div>
          <a href="javascript:" class="common_black_button project_button" style="float:right;">Projects</a>
          <input type="button" name="export_csv" class="export_csv common_black_button" value="Export Task List" style="float:right;">
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
          <div class="compressed_layout_div" style="float:right;width:33%;margin-right: 0px;<?php echo $compressed_div; ?>">
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
        <li class="nav-item waves-effect waves-light active" style="width:15%;text-align: center">
          <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
            <spam id="open_task_count">Your Open Tasks (<spam id="open_task_count_val"><?php if(is_countable($open_task_count) and count($open_task_count) > 0) { count($open_task_count); } else { echo 0; } ?></spam>)</spam>
            <spam id="redline_task_count" style="display:none">Redline Tasks (<spam id="redline_task_count_val">0</spam>)</spam>
            <spam id="authored_task_count" style="display:none">Your Authored Tasks (<spam id="authored_task_count_val"><?php if(is_countable($authored_task_count) and count($authored_task_count) > 0) { echo count($authored_task_count); } else { echo 0; } ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/park_task'); ?>" class="nav-link" id="home-tab">
            <spam id="park_task_count">Park Tasks (<spam id="park_task_count_val"><?php echo count($park_task_count); ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/taskmanager_search'); ?>" class="nav-link" id="profile-tab">Task Search</a>
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
      <div class="table-responsive" style="width: 100%; float: left;margin-top:250px;">
      <div class="tab-content" id="myTabContent">
        
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
          
<style type="text/css">
.open_layout_div{width:99%;margin: 0px auto; top:274px}
.open_layout_div_change{z-index: 999}
.open_layout_div_change::before{width: 100%; height: 0px; content: ""; position: fixed; background: #fff; margin-top: 300px; z-index: 99}
</style>
        <div class="open_layout_div" >
            <table class="table_bg table-fixed-header open_layout" style="width:100%;margin: 0px auto;">
              <tbody id="task_body_open">
                <?php
                $layout = '';
                if(is_countable($user_tasks) && count($user_tasks) > 0)
                {
                  foreach($user_tasks as $keytaskid => $task)
                  {
                    $aut_details = Db::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
                    if($aut_details) {
                    if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
                    else{ $disabled = ''; $disabled_icon = ''; }

                    $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
                    $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
                    $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
                    $taskyearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
                    $two_bill_icon = '';
                    if($task->two_bill == "1")
                    {
                      $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                    }
                    if($task->client_id == "")
                    {
                      $title_lable = 'Task Name:';
                      $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
                      if(($task_details))
                      {
                        $title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
                      }
                      else{
                        $title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
                      }
                    }
                    else{
                      $title_lable = 'Client:';
                      $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                      if(($client_details))
                      {
                        $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
                      }
                      else{
                        $title = ''.$two_bill_icon;
                      }
                    }
                    $author = DB::table('user')->where('user_id',$task->author)->first();
                    
                    $task_specifics_val = strip_tags($task->task_specifics);

                    if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
                    else{ $subject = $task->subject; }

                    if($task->allocated_to == 0) { $allocated_to = 'Open Task'; }
                    else{ $allocated = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }

                    if(Session::has('taskmanager_user'))
                    {
                      if(Session::get('taskmanager_user') == $task->author) {
                        if(Session::get('taskmanager_user') == $task->allocated_to)
                        {
                          $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
                        }
                        else{
                          $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
                        }
                      }
                      else{ 
                        $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
                      }
                    }
                    else{
                      $author_cls = '';
                      $hidden_author_cls = '';
                    }

                    if($task->auto_close == 1)
                    {
                      $close_task = 'auto_close_task_complete';
                    }
                    else{
                      $close_task = '';
                    }
                    ?>
                    <tr class="tasks_tr <?php echo $author_cls; ?>" id="task_tr_<?php echo $task->id; ?>">
                      <td style="vertical-align: baseline;background: #E1E1E1;width:35%;padding:0px">
                        <?php
                          $statusi = 0;
                          if(Session::has('taskmanager_user'))
                          {
                            if(Session::get('taskmanager_user') == $task->author) { 
                              if($task->author_spec_status == "1")
                              {
                                echo '<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                                $statusi++;
                              }
                            }
                            else{
                              if($task->allocated_spec_status == "1")
                              {
                                echo '<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                                $statusi++;
                              }
                            }
                          }
                          if($statusi == 0)
                          {
                            echo '<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                          }
                          ?>
                        <table class="table">
                          <tr>
                            <td style="width:25%;background: #E1E1E1;font-weight:700;text-decoration: underline;"><?php echo $title_lable; ?></td>
                            <td style="width:75%;background: #E1E1E1"><?php echo $title; ?> 
                            <?php if($task->client_id != ""){
                              echo '<img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />';
                            }
                            ?>                            
                            <?php
                            if($task->recurring_task > 0)
                            {
                              ?>
                              <img src="<?php echo URL::to('public/assets/images/recurring.png'); ?>" class="recure_image" style="width:30px;" title="This is a Recurring Task">
                              <?php
                            }
                            ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Subject:</td>
                            <td style="background: #E1E1E1"><?php echo $subject; ?></td>
                          </tr>
                          <tr>
                            <?php 
                              $date1=date_create(date('Y-m-d'));
                              $date2=date_create($task->due_date);
                              $diff=date_diff($date1,$date2);
                              $diffdays = $diff->format("%R%a");

                              if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
                              elseif($diffdays < 0) { $due_color = '#f00'; }
                              elseif($diffdays > 7) { $due_color = '#000'; }
                              elseif($diffdays <= 7) { $due_color = '#00a91d'; }
                              else{ $due_color = '#000'; }
                            ?>
                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Due Date:</td>
                            <td style="background: #E1E1E1" class="<?php echo $disabled_icon; ?>">
                              <spam style="color:<?php echo $due_color; ?> !important;font-weight:800" id="due_date_task_<?php echo $task->id; ?>"><?php echo date('d-M-Y', strtotime($task->due_date)); ?></spam>
                              <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-value="<?php echo date('d-M-Y', strtotime($task->due_date)); ?>" data-duedate="<?php echo $task->due_date; ?>" data-color="<?php echo $due_color; ?>" class="fa fa-edit edit_due_date edit_due_date_<?php echo $task->id; ?> <?php echo $disabled; ?>" style="font-weight:800"></a>
                            </td>
                          </tr>
                          <tr>
                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Created:</td>
                            <td style="background: #E1E1E1">
                              <spam><?php echo date('d-M-Y', strtotime($task->creation_date)); ?></spam>
                            </td>
                          </tr>
                          <tr>
                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task Specifics:</td>
                            <td style="background: #E1E1E1"><a href="javascript:" class="link_to_task_specifics" data-element="<?php echo $task->id; ?>" data-clientid="<?php echo $task->client_id; ?>"><?php echo substr($task_specifics_val,0,30); ?>...</a></td>
                          </tr>
                          <tr>
                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task files:</td>
                            <td style="background: #E1E1E1">
                              <?php
                              $fileoutput = '';
                              if(($taskfiles))
                              {
                                foreach($taskfiles as $file)
                                {
                                  if($file->status == 0)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              if(($tasknotepad))
                              {
                                foreach($tasknotepad as $note)
                                {
                                  if($note->status == 0)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              if(($taskinfiles))
                              {
                                $i=1;
                                foreach($taskinfiles as $infile)
                                {
                                  if($infile->status == 0)
                                  {
                                    if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                                    $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                                    $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                                    $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

                                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                                    </p>';
                                    $i++;
                                  }
                                }
                              }
                              echo $fileoutput;
                              ?>

                            </td>
                          </tr>
                        </table>
                      </td>
                      <td style="vertical-align: baseline;background: #E8E8E8;width:30%">
                        <table class="table">
                          <tr>
                            <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
                            <td style="width:75%"><?php echo $author->lastname.' '.$author->firstname; ?> 
                            <?php
                            if($task->avoid_email == 0) {
                              ?>
                              <a href="javascript:" class="fa fa-envelope avoid_email" data-element="<?php echo $task->id; ?>" title="Avoid Emails for this task"></a>
                              <?php
                            }
                            else{
                              ?>
                              <a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="<?php echo $task->id; ?>" title="Avoid Emails for this task"></a>
                              <?php
                            }
                            ?>
                            </td>
                          </tr>
                          <tr>
                            <td style=";font-weight:700;text-decoration: underline;">Allocated to:</td>
                            <td id="allocated_to_name_<?php echo $task->id; ?>"><?php echo $allocated_to; ?></td>
                          </tr>
                          <tr>
                            <td colspan="2">
                              <spam style="font-weight:700;text-decoration: underline;">Allocations: </spam> &nbsp;
                              <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_<?php echo $task->id; ?> <?php echo $disabled; ?> <?php echo $close_task; ?>" title="Allocate User" style="font-weight:800"></a>
                              &nbsp;
                              <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-history show_task_allocation_history show_task_allocation_history_<?php echo $task->id; ?>" title="Allocation History" style="font-weight:800"></a>
                              &nbsp;
                              <a href="javascript:" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" title="Request Update" style="font-weight:800">
                                <img src="<?php echo URL::to('public/assets/images/request.png'); ?>" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" style="width:16px;">
                              </a>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" id="allocation_history_div_<?php echo $task->id; ?>" class="<?php echo $disabled_icon; ?>">
                              <?php
                              $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
                              $output = '';
                              if(($allocations))
                              {
                                foreach($allocations as $allocate)
                                {
                                  $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.' '.$close_task.'" title="Allocate User">';
                                    $fromuser = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->from_user)->first();
                                    $touser = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$allocate->to_user)->first();
                                    $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
                                  $output.='</p>';
                                }
                              }
                              echo $output;
                              ?>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td style="vertical-align: baseline;background: #F0F0F0;width:20%">
                        <table class="table">
                          <tr>
                            <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
                            <td></td>
                          </tr>
                          <tr>
                            <td class="<?php echo $disabled_icon; ?>">
                              <a href="javascript:" class="fa fa-plus faplus_progress <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>" style="padding:5px;background: #dfdfdf;" title="Insert Files"></a>
                              <a href="javascript:" class="fa fa-edit fanotepad_progress <?php echo $disabled; ?>" style="padding:5px;background: #dfdfdf;" title="Create Notes"></a>
                              <a href="javascript:" class="fa fa-download faprogress_download_all <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>" style="padding:5px;background: #dfdfdf;" title="Download All Progress FIles"></a>
                              <?php
                              if($task->client_id != "")
                              {
                                ?>
                                <a href="javascript:" class="infiles_link_progress <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Infiles</a>
                                <?php
                              }
                              ?>
                              <input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_<?php echo $task->id; ?>" value="<?php echo $task->client_id; ?>">
                              <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_<?php echo $task->id; ?>" value="">
                              
                              <div class="notepad_div_progress_notes" style="z-index:99999999999999 !important; min-height: 250px; height: auto; position:absolute; padding: 10px;">

                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label style="font-weight: normal;">Select User</label>
                                      <select class="form-control notepad_user">
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
                                      <spam class="error_notepad_user" style="color:#f00;"></spam>
                                    </div>
                                    
                                  </div>
                                  <div class="col-lg-12">
                                    <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents" style="height: 110px !important"></textarea>
                                    <spam class="error_files_notepad" style="color:#f00;"></spam>
                                    <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="<?php echo $task->id; ?>">
                                    <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                    
                                  </div>
                                </div>


                                
                              </div>
                            </td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="<?php echo $disabled_icon; ?>">
                              <?php
                              $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
                              if(($taskfiles))
                              {
                                foreach($taskfiles as $file)
                                {
                                  if($file->status == 1)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
                              if(($tasknotepad))
                              {
                                foreach($tasknotepad as $note)
                                {
                                  if($note->status == 1)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
                              if(($taskinfiles))
                              {
                                $i=1;
                                  foreach($taskinfiles as $infile)
                                  {
                                    if($infile->status == 1)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                                      $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                                      $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                                      $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

                                      <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
                              echo $fileoutput;
                              ?>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td style="vertical-align: baseline;background: #F8F8F8;width:15%">
                        <table class="table" style="margin-bottom: 105px;">
                          <tr>
                            <td style="background:#F8F8F8" class="<?php echo $disabled_icon; ?>">
                              <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;"><?php echo $task->taskid; ?></spam>
                              <a href="javascript:" class="fa fa-files-o copy_task" data-element="<?php echo $task->id; ?>" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
                              <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="<?php echo $task->id; ?>" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
                                </a> 
                              <a href="<?php echo URL::to('user/view_taskmanager_task/'.$task->id); ?>" target="_blank" class="fa fa-expand view_full_task" data-element="<?php echo $task->id; ?>" 
                                title="View Task" style="padding:5px;font-size:20px;font-weight: 500;float: right">
                              </a> 
                              </td>
                          </tr>
                          <tr>
                            <td style="background:#F8F8F8">
                              <spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                              <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_<?php echo $task->id; ?>" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>

                              <!-- Content for Popover #1 -->
                              <div class="hidden" id="a1_<?php echo $task->id; ?>">
                                <div class="popover-heading">
                                  Set Progress Percentage
                                </div>
                                <div class="popover-body">
                                  <input type="number" class="form-control input-sm progress_value" id="progress_value_<?php echo $task->id; ?>" value="" style="width:60%;float:left">
                                  <a href="javascript:" class="common_black_button set_progress" data-element="<?php echo $task->id; ?>" style="font-size: 11px;line-height: 29px;">Set</a>
                                </div>
                              </div>
                              <div class="progress progress_<?php echo $task->id; ?>" style="width:60%;margin-bottom:5px">
                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $task->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $task->progress; ?>%">
                                  <?php echo $task->progress; ?>%
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="last_td_display" style="background:#F8F8F8">
                              <?php
                              if($task->status == 1)
                              {
                                ?>
                                <a href="javascript:" class="common_black_button mark_as_incomplete" data-element="<?php echo $task->id; ?>" style="font-size:12px">Completed</a>
                                <?php
                              }
                              elseif($task->status == 2)
                              {
                                if(Session::has('taskmanager_user'))
                                {
                                  $allocated_person = Session::get('taskmanager_user');
                                  if($task->author == $allocated_person)
                                  {
                                    $complete_button = 'mark_as_complete_author';
                                  }
                                  else{
                                    $complete_button = 'mark_as_complete';
                                  }
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                                ?>
                                <a href="javascript:" class="common_black_button <?php echo $complete_button; ?> <?php echo $close_task; ?>" data-element="<?php echo $task->id; ?>" style="font-size:12px">Mark Complete</a>
                                <a href="javascript:" class="common_black_button activate_task_button" data-element="<?php echo $task->id; ?>" style="font-size:12px">Activate</a>
                                <?php
                              }
                              else{
                                if(Session::has('taskmanager_user'))
                                {
                                  $allocated_person = Session::get('taskmanager_user');
                                  if($task->author == $allocated_person)
                                  {
                                    $complete_button = 'mark_as_complete_author';
                                  }
                                  else{
                                    $complete_button = 'mark_as_complete';
                                  }
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                                
                                ?>
                                <a href="javascript:" class="common_black_button <?php echo $complete_button; ?> <?php echo $close_task; ?>" data-element="<?php echo $task->id; ?>" style="font-size:12px">Mark Complete</a>
                                <a href="javascript:" class="common_black_button park_task_button" data-element="<?php echo $task->id; ?>" style="font-size:12px">Park Task</a>
                                
                                <?php
                              }
                              ?>
                              <a href="javascript:" class="fa fa-files-o integrity_check_for_task common_black_button" data-element="<?php echo $task->id; ?>" title="Integrity Check"></a>
                              
                            </td>
                          </tr>
                          <tr>
                            <td style="background:#F8F8F8">
                              <spam style="font-weight:700;text-decoration: underline;">Completion Files: <a href="javascript:" class="fa fa-download download_all_completion_files_taskmanager" data-element="<?php echo $task->id; ?>" data-client="<?php echo $task->client_id; ?>" title="Download all Completion Files"></a></spam><br/>
                              <a href="javascript:" class="fa fa-plus faplus_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>" style="padding:5px"></a>
                              <a href="javascript:" class="fa fa-edit fanotepad_completion <?php echo $disabled; ?>" style="padding:5px;"></a>
                              <?php
                              if($task->client_id != "")
                              {
                                ?>
                                <a href="javascript:" class="infiles_link_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Infiles</a>
                                <a href="javascript:" class="yearend_link_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Yearend</a>
                                <?php
                              }
                              $get_infiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
                              $get_yearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
                              $idsval = '';
                              $idsval_yearend = '';
                              if(($get_infiles))
                              {
                                foreach($get_infiles as $set_infile)
                                {
                                  if($idsval == "")
                                  {
                                    $idsval = $set_infile->infile_id;
                                  }
                                  else{
                                    $idsval = $idsval.','.$set_infile->infile_id;
                                  }
                                }
                              }
                              if(($get_yearend))
                              {
                                foreach($get_yearend as $set_yearend)
                                {
                                  if($idsval_yearend == "")
                                  {
                                    $idsval_yearend = $set_yearend->setting_id;
                                  }
                                  else{
                                    $idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
                                  }
                                }
                              }
                              ?>
                              
                              <input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_<?php echo $task->id; ?>" value="<?php echo $task->client_id; ?>">
                              <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_<?php echo $task->id; ?>" value="<?php echo $idsval; ?>">
                              <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_<?php echo $task->id; ?>" value="<?php echo $idsval_yearend; ?>">

                              
                              <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
                                <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
                                <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="<?php echo $task->id; ?>">
                                <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                <spam class="error_files_notepad"></spam>
                              </div>


                              <?php
                              $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
                              if(($taskfiles))
                              {
                                foreach($taskfiles as $file)
                                {
                                  if($file->status == 2)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
                              if(($tasknotepad))
                              {
                                foreach($tasknotepad as $note)
                                {
                                  if($note->status == 2)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
                              if(($taskinfiles))
                              {
                                $i=1;
                                  foreach($taskinfiles as $infile)
                                  {
                                    if($infile->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Infiles: <br/>'; }
                                      $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                                      $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                                      $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

                                      <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';

                              $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
                              if(($taskyearend))
                              {
                                $i=1;
                                  foreach($taskyearend as $yearend)
                                  {
                                    if($yearend->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
                                      $file = DB::table('year_setting')->where('id',$yearend->setting_id)->first();
                                      $get_client_id = DB::table('taskmanager')->where('practice_code', Session::get('user_practice_code'))->where('id',$task->id)->first();
                                      $year_client_id = $get_client_id->client_id;
                                      $yearend_id = DB::table('year_client')->where('client_id',$year_client_id)->orderBy('id','desc')->first();

                                      $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
                                      $fileoutput.='<p class="link_yearend_p"><a href="'.$ele.'" target="_blank">'.$i.'</a>
                                      <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                                      <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
                              echo $fileoutput;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="background:#F8F8F8">
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr class="empty_tr" style="background: #fff;height:10px">
                      <td style="padding:0px;background: #fff;">
                        
                      </td>
                      <td colspan="3" style="background: #fff;height:10px"></td>
                    </tr>
                    
                    <?php
                    $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
                      <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
                        <table style="width:100%">
                          <tr>';
                          $statusi = 0;
                          if(Session::has('taskmanager_user'))
                          {
                            if(Session::get('taskmanager_user') == $task->author) { 
                              if($task->author_spec_status == "1")
                              {
                                $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
                                $redlight_value = 1;
                                $statusi++;
                              }
                            }
                            else{
                              if($task->allocated_spec_status == "1")
                              {
                                $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
                                $redlight_value = 1;
                                $statusi++;
                              }
                            }
                          }
                          if($statusi == 0)
                          {
                            $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
                            $redlight_value = 0;
                          }
                            $layout.= '
                            <td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
                            <spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
                            '.$redlight_indication_layout.'
                            </td>
                            <td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
                            <td style="width:30%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val left">'.$title.'';
                              if($task->recurring_task > 0) {
                                  $layout.= '<img src="'.URL::to('public/assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
                                }
                            $layout.= '</td>';
                            if($task->client_id){
                              $layout.='<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"><img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>';
                            }else{
                              $layout .= '<td style="width:10%;padding:10px; font-size:14px; font-weight:800;"></td>';
                            }
                            $layout.='<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
                            '.$two_bill_icon.'
                            </td>
                            <td style="width:40%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
                          </tr>
                        </table>
                      </td>
                      <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
                        <table style="width:100%">
                          <tr>
                            <td style="width:60%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.' / <spam class="allocated_sort_val">'.$allocated_to.'</spam></td>
                            <td style="width:40%;padding:10px; font-size:14px; font-weight:800">'.user_rating($task->id).'</td>
                          </tr>
                        </table>
                      </td>
                      <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
                        <table style="width:100%">
                          <tr>
                            <td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
                              <spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
                              <spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
                            </td>
                            <td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
                            <spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
                            '.date('d-M-Y', strtotime($task->creation_date)).'
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
                        <table style="width:100%">
                          <tr>';
                            $project_time = '-';
                            if($task->project_hours != ''){
                              $project_time = $task->project_hours.':'.$task->project_mins;
                            }
                            $layout.='<td style="width:25%;padding:10px; font-size:14px; font-weight:800;">'.$project_time.'</td>
                            <td class="layout_progress_'.$task->id.'" style="width:30%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
                          </tr>
                        </table>
                      </td>
                    </tr>';
                    }
                  }
                }
                else{
                  ?>
                  <td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td>
                  <?php
                  $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
                }
                ?>
              </tbody>
            </table>
        </div>
          <?php 
          if($open_task_count == 0 && $authored_task_count == 0) {  } else { ?>

          <table class="table_bg table-fixed-header table_layout" style="width:100%;float:left;display:none">
            <thead>
              <tr>
                <td colspan="5" style="background: #fff;border: 0px">
                  
                </td>
              </tr>
              <tr class="hidden_tasks_th" id="menulist">
                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
                      <table style="width:100%">
                        <tr>
                          <td style="color:#000;width:5%;padding:10px; font-size:15px; font-weight:600;"><i class="fa fa-sort redlight_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#000;width:10%;padding:10px; font-size:15px; font-weight:600;">Task ID<i class="fa fa-sort taskid_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#000;width:30%;padding:10px; font-size:15px; font-weight:600;">Client/Task Name<i class="fa fa-sort taskname_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                            <td style="color:#000;width:10%;padding:10px; font-size:15px; font-weight:600;">Active Client<i class="fa fa-sort taskid_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#000;width:5%;padding:10px; font-size:15px; font-weight:600;"></td>
                          <td style="color:#000;width:40%;padding:10px; font-size:15px; font-weight:600">Subject<i class="fa fa-sort subject_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
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
                          <td style="color:#000;width:50%;padding:10px; font-size:15px; font-weight:600;">Due Date<i class="fa fa-sort duedate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#000;width:50%;padding:10px; font-size:15px; font-weight:600">Created Date<i class="fa fa-sort createddate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                        </tr>
                      </table>
                    </td>
                    <td style="background: #fff;padding:0px;border-bottom:3px solid #000;border-right: 0px solid">
                      <table style="width:100%">
                        <tr>
                          <td style="color:#000;width:25%;padding:10px; font-size:15px; font-weight:600;" title="Project Time">P.T </td>
                          <td style="color:#000;width:30%;padding:10px; font-size:15px; font-weight:600;">Progress<i class="fa fa-sort progress_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                        </tr>
                      </table>
                    </td>
                </tr>
            </thead>
            <tbody id="task_body_layout">
              <?php 
              echo $layout;
              ?>
            </tbody>
          </table>
          <?php } ?>
        
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
<div class="modal_load_bpso" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Infile Items are Processed in ZIP File.</p>
  <p style="font-size:18px;font-weight: 600;">Processing Infile Item - <span id="bpso_name"></span>: <span id="bpso_first"></span> of <span id="bpso_last"></span></p>
</div>
<div class="modal_load_completion" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Completion Files are Processed in ZIP File.</p>
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


$(function(){
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

    var layout = $("#hidden_compressed_layout").val();
    $(".tasks_tr").hide();
  $(".tasks_tr").next().hide();
  $(".hidden_tasks_tr").hide();
  var view = $(".select_view").val();
    if(view == "3")
    {
      if(layout == "1")
      {
        $(".author_tr:first").show();
        $(".author_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_author_tr").show();
        $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
      }
      else{
        $(".author_tr").show();
        $(".author_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_author_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "2"){
      $("#open_task_count").hide();
      $("#redline_task_count").show();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        var i = 1;
        $(".redline_indication").each(function() {
          if(i == 1)
          {
            if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
            {
              $(this).parents(".allocated_tr").show();
              $(this).parents(".allocated_tr").next().show();
              i++;
            }
          }
        });
        $(".table_layout").show();
        $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
        
        var j = 1;
        $(".redline_indication_layout").each(function() {
          if(j == 1)
          {
            if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
            {
              $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
              j++;
            }
          }
        });
      }
      else{
        $(".redline_indication").parents(".allocated_tr").show();
        $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_allocated_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "1"){
      if(layout == "1")
      {
        $(".allocated_tr:first").show();
        $(".allocated_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_allocated_tr").show();
        $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
      }
      else{
        $(".allocated_tr").show();
        $(".allocated_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_allocated_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }

    if(layout == "1")
    {
      $(".open_layout_div").addClass("open_layout_div_change");
      var open_tasks_height = $(".open_layout_div").height();
      var margintop = parseInt(open_tasks_height);
      $(".open_layout_div").css("position","fixed");
      $(".open_layout_div").css("height","312px");
      if(open_tasks_height > 312)
      {
        $(".open_layout_div").css("overflow-y","scroll");
      }
      if(open_tasks_height < 50)
      {
        $(".table_layout").css("margin-top","20px");
      }
        else{
          $(".table_layout").css("margin-top","233px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
      $(".open_layout_div").css("position","unset");
      $(".open_layout_div").css("height","auto");
      $(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
    $(".taskname_sort_val").find(".2bill_image").detach();
});
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
    //window.location.reload(true);
  });
}

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
          $("body").removeClass('loading_completion_files');
          $("body").addClass("loading_bpso");
          $("#bpso_first").html(offset);
          $("#bpso_name").html(result['infile_itemname']);
          $("#bpso_last").html(result['total_count']);
          setTimeout(function() {
            infile_download_bpso_all_image(ids,count,nextpage,task_id,result['filename']);
          },1000);
        }
        else{
          $("body").removeClass('loading_completion_files');
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
  var ascending = false;
  event_load();
  if(e.target.id == "settings_taskamanager")
  {
    $(".taskmanager_settings_modal").modal("show");
  }
  if($(e.target).hasClass('email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the TaskManager Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('hide_inactive_users'))
  {    
    if($(e.target).is(":checked"))
    {
      $(".inactive_user").show();
    }
    else{
      $(".inactive_user").hide();
    }

  }
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
  if($(e.target).hasClass('change_layout'))
  {
    var value=$(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/change_taskmanager_view_layout'); ?>",
      type:"post",
      data:{value:value},
      success:function(result){
          if(value == "0"){
            var layout = "1";
            $("#hidden_compressed_layout").val(layout);
            $(".compressed_layout").prop("checked",true);
            $(".compressed_layout_div").show();
            
          } else if(value == "1") {
            var layout = "0";
            $("#hidden_compressed_layout").val(layout);
            $(".compressed_layout").prop("checked",false);
            $(".compressed_layout_div").show();
          } else{
            var layout = "0";
            $("#hidden_compressed_layout").val(layout);
            $(".compressed_layout").prop("checked",false);
            $(".compressed_layout_div").hide();
          }

          $(".tasks_tr").hide();
          $(".tasks_tr").next().hide();
          $(".hidden_tasks_tr").hide();
          var view = $(".select_view").val();
            if(view == "3")
            {
              if(layout == "1")
              {
                $(".author_tr:first").show();
                $(".author_tr:first").next().show();
                $(".table_layout").show();
                $(".table_layout").find(".hidden_author_tr").show();
                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
              }
              else{
                $(".author_tr").show();
                $(".author_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_author_tr").hide();
              }

              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }
            else if(view == "2"){
              $("#open_task_count").hide();
              $("#redline_task_count").show();
              $("#authored_task_count").hide();
              if(layout == "1")
              {
                var i = 1;
                $(".redline_indication").each(function() {
                  if(i == 1)
                  {
                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                    {
                      $(this).parents(".allocated_tr").show();
                      $(this).parents(".allocated_tr").next().show();
                      i++;
                    }
                  }
                });
                $(".table_layout").show();
                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                
                var j = 1;
                $(".redline_indication_layout").each(function() {
                  if(j == 1)
                  {
                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                    {
                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                      j++;
                    }
                  }
                });
              }
              else{
                $(".redline_indication").parents(".allocated_tr").show();
                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_allocated_tr").hide();
              }

              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }
            else if(view == "1"){
              if(layout == "1")
              {
                $(".allocated_tr:first").show();
                $(".allocated_tr:first").next().show();
                $(".table_layout").show();
                $(".table_layout").find(".hidden_allocated_tr").show();
                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
              }
              else{
                $(".allocated_tr").show();
                $(".allocated_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_allocated_tr").hide();
              }

              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }

            if(layout == "1")
            {
              $(".open_layout_div").addClass("open_layout_div_change");
              var open_tasks_height = $(".open_layout_div").height();
              var margintop = parseInt(open_tasks_height);
              $(".open_layout_div").css("position","fixed");
              $(".open_layout_div").css("height","312px");
              if(open_tasks_height > 312)
              {
                $(".open_layout_div").css("overflow-y","scroll");
              }
              if(open_tasks_height < 50)
              {
                $(".table_layout").css("margin-top","20px");
              }
                else{
                  $(".table_layout").css("margin-top","233px");
                }
            }
            else{
              $(".open_layout_div").removeClass("open_layout_div_change");
              $(".open_layout_div").css("position","unset");
              $(".open_layout_div").css("height","auto");
              $(".open_layout_div").css("overflow-y","unset");
                $(".table_layout").css("margin-top","0px");
            }
      }
    })
  }
  if($(e.target).hasClass('project_add_button'))
  {
    var project_name = $(".project_name").val();
    var select_author = $(".select_author").val();
    var project_id = $(".project_id").val();

    if(project_name == ''){
      $("#project_name-error").show();
    }
    else if(select_author == ''){
      $("#project_name-error").hide();
      $("#select_author-error").show();
    }
    else{
      $("#project_name-error").hide();
      $("#select_author-error").hide();
      setTimeout(function() {        
        $.ajax({
            url:"<?php echo URL::to('user/add_edit_project')?>",
            dataType:'json',
            data:{project_name:project_name, select_author:select_author, project_id:project_id},
            type:"post",
            success: function(result)
            {
              $("#project_tbody").html(result['projects']);
              $(".add_edit_project_modal").modal('hide');
              alert(result['message']);
            }
          })
      },500);
    }

  }

  if($(e.target).hasClass('edit_project'))
  {
    var id = $(e.target).attr("data-element");
    setTimeout(function() {        
      $.ajax({
          url:"<?php echo URL::to('user/project_details')?>",
          dataType:'json',
          data:{id:id},
          type:"post",
          success: function(result)
          {
            $(".project_title").html('Edit Project');
            $(".project_add_button").val('Update Project');

            $(".project_name").val(result['name']);
            $(".select_author").val(result['author']);
            $(".project_id").val(result['id']);
            $(".add_edit_project_modal").modal('show');
            
          }
        })
    },500);
  }

  if($(e.target).hasClass('project_button'))
  {
    $(".project_modal").modal('show');
  }
  if($(e.target).hasClass('add_new_project'))
  {
    $(".project_title").html('Add Project');
    $(".project_add_button").val('Add Project');

    $(".project_name").val('');
    $(".select_author").val('');
    $(".project_id").val('0');

    $(".add_edit_project_modal").modal('show');
  }


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
    var view = $(".select_view").val();
    $.ajax({
      url:"<?php echo URL::to('user/download_export_csv_task_manager'); ?>",
      type:"post",
      data:{view:view},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
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
    var view = $(".select_view").val();
      if(view == "3")
      {
        if(layout == "1")
        {
          $(".author_tr:first").show();
          $(".author_tr:first").next().show();
          $(".table_layout").show();
          $(".table_layout").find(".hidden_author_tr").show();
          $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
        }
        else{
          $(".author_tr").show();
          $(".author_tr").next().show();
          $(".table_layout").hide();
          $(".table_layout").find(".hidden_author_tr").hide();
        }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;
        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
      }
      else if(view == "2"){
        $("#open_task_count").hide();
        $("#redline_task_count").show();
        $("#authored_task_count").hide();
        if(layout == "1")
        {
          var i = 1;
          $(".redline_indication").each(function() {
            if(i == 1)
            {
              if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
              {
                $(this).parents(".allocated_tr").show();
                $(this).parents(".allocated_tr").next().show();
                i++;
              }
            }
          });
          $(".table_layout").show();
          $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
          
          var j = 1;
          $(".redline_indication_layout").each(function() {
            if(j == 1)
            {
              if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
              {
                $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                j++;
              }
            }
          });
        }
        else{
          $(".redline_indication").parents(".allocated_tr").show();
          $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
          $(".table_layout").hide();
          $(".table_layout").find(".hidden_allocated_tr").hide();
        }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;
        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
      }
      else if(view == "1"){
        if(layout == "1")
        {
          $(".allocated_tr:first").show();
          $(".allocated_tr:first").next().show();
          $(".table_layout").show();
          $(".table_layout").find(".hidden_allocated_tr").show();
          $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
        }
        else{
          $(".allocated_tr").show();
          $(".allocated_tr").next().show();
          $(".table_layout").hide();
          $(".table_layout").find(".hidden_allocated_tr").hide();
        }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;
        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
      }

      if(layout == "1")
      {
        $(".open_layout_div").addClass("open_layout_div_change");
        var open_tasks_height = $(".open_layout_div").height();
        var margintop = parseInt(open_tasks_height);
        $(".open_layout_div").css("position","fixed");
        $(".open_layout_div").css("height","312px");
        if(open_tasks_height > 312)
        {
          $(".open_layout_div").css("overflow-y","scroll");
        }
        if(open_tasks_height < 50)
        {
          $(".table_layout").css("margin-top","20px");
        }
          else{
            $(".table_layout").css("margin-top","233px");
          }
      }
      else{
        $(".open_layout_div").removeClass("open_layout_div_change");
        $(".open_layout_div").css("position","unset");
        $(".open_layout_div").css("height","auto");
        $(".open_layout_div").css("overflow-y","unset");
          $(".table_layout").css("margin-top","0px");
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
                  $("#task_body_open").append(result['open_tasks_output']);
                  $("#task_body_layout").append(result['layout']);

                  var view = $(".select_view").val();
                  var layout = $("#hidden_compressed_layout").val();

                  $("#task_body_open").find(".tasks_tr").last().hide();
                  $("#task_body_open").find(".empty_tr").last().hide();
                  $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

                  if(view == "3") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".hidden_author_tr").show();
                    }
                    else{
                      $("#task_body_open").find(".author_tr").last().show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }
                  else if(view == "2") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    }
                    else {
                      $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }
                  else if(view == "1") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".hidden_allocated_tr").show();
                    }
                    else{
                      $("#task_body_open").find(".allocated_tr").last().show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }

                  $(".create_new_model").modal("hide");

                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');

                  $("body").removeClass("loading");
                },
                cache: false,
                contentType: false,
                processData: false
            });

            //$("#create_job_form").submit();
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px", height:"400px"});
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
                $("#task_body_open").append(result['open_tasks_output']);
                $("#task_body_layout").append(result['layout']);

                var view = $(".select_view").val();
                var layout = $("#hidden_compressed_layout").val();

                $("#task_body_open").find(".tasks_tr").last().hide();
                $("#task_body_open").find(".empty_tr").last().hide();
                $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

                if(view == "3") {
                  if(layout == "1"){
                    $("#task_body_layout").find(".hidden_author_tr").show();
                  }
                  else{
                    $("#task_body_open").find(".author_tr").last().show();
                    $("#task_body_open").find(".empty_tr").last().show();
                  }
                }
                else if(view == "2") {
                  if(layout == "1"){
                    $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                  }
                  else {
                    $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
                    $("#task_body_open").find(".empty_tr").last().show();
                  }
                }
                else if(view == "1") {
                  if(layout == "1"){
                    $("#task_body_layout").find(".hidden_allocated_tr").show();
                  }
                  else{
                    $("#task_body_open").find(".allocated_tr").last().show();
                    $("#task_body_open").find(".empty_tr").last().show();
                  }
                }

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
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px", height:"400px"});
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
          $("#task_body_open").append(result['open_tasks_output']);
          $("#task_body_layout").append(result['layout']);

          var view = $(".select_view").val();
          var layout = $("#hidden_compressed_layout").val();

          $("#task_body_open").find(".tasks_tr").last().hide();
          $("#task_body_open").find(".empty_tr").last().hide();
          $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

          if(view == "3") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_author_tr").show();
            }
            else{
              $("#task_body_open").find(".author_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "2") {
            if(layout == "1"){
              $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
            }
            else {
              $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "1") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_allocated_tr").show();
            }
            else{
              $("#task_body_open").find(".allocated_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }

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
          $("#task_body_open").append(result['open_tasks_output']);
          $("#task_body_layout").append(result['layout']);

          var view = $(".select_view").val();
          var layout = $("#hidden_compressed_layout").val();

          $("#task_body_open").find(".tasks_tr").last().hide();
          $("#task_body_open").find(".empty_tr").last().hide();
          $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

          if(view == "3") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_author_tr").show();
            }
            else{
              $("#task_body_open").find(".author_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "2") {
            if(layout == "1"){
              $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
            }
            else {
              $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "1") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_allocated_tr").show();
            }
            else{
              $("#task_body_open").find(".allocated_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }

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
  if($(e.target).hasClass('avoid_email'))
  {
    $("body").addClass("loading");
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
          $("body").removeClass("loading");
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
          $("body").removeClass("loading");
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
      $(".layout_progress_"+task_id).html("0%");
    }
    else{
      $(".progress_"+task_id).find(".progress-bar").attr("aria-valuenow",value);
      $(".progress_"+task_id).find(".progress-bar").css("width",value+"%");
      $(".progress_"+task_id).find(".progress-bar").html(value+"%");

      $(".layout_progress_"+task_id).html(value+"%");
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
      if(open_tasks_height > 312)
      {
        $(".open_layout_div").css("overflow-y","scroll");
      }
      if(open_tasks_height < 50)
      {
        $(".table_layout").css("margin-top","20px");
      }
        else{
          $(".table_layout").css("margin-top","233px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
      $(".open_layout_div").css("position","unset");
      $(".open_layout_div").css("height","auto");
      $(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
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
  if($(e.target).hasClass('mark_as_complete'))
  {
    var task_id = $(e.target).attr("data-element");
    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if($(e.target).hasClass('auto_close_task_complete'))
    {
      $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'" class="common_black_button yes_mark_complete">Yes</a><a href="javascript:" class="common_black_button no_mark_complete" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'">No</a></p>',fixed:true,width:"800px"});
    }
    else{
      $("body").addClass("loading");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
        type:"post",
        data:{task_id:task_id,type:"0"},
        success:function(resultval)
        {
          $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
          var layout = $("#hidden_compressed_layout").val();
          var view = $(".select_view").val();
          if(layout == "1")
          {
            var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
            var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
            if (typeof nexttask_id !== "undefined") {
              var taskidval = nexttask_id;
            }
            else if (typeof prevtask_id !== "undefined") {
              var taskidval = prevtask_id;
            }
            else{
              var taskidval = '';
            }

            $("#task_tr_"+task_id).next().detach();
            $("#task_tr_"+task_id).detach();
            $("#hidden_tasks_tr_"+task_id).detach();

            $("#task_tr_"+taskidval).show();
            $("#task_tr_"+taskidval).next().show();
            $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

            var opentask = $("#open_task_count_val").html();
            var countopen = parseInt(opentask) - 1;
            $("#open_task_count_val").html(countopen);
            $("body").removeClass("loading");
          }
          else{
            setTimeout(function() {
              var user_id = $(".select_user_home").val();
              $.ajax({
                url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                type:"post",
                data:{user_id:user_id},
                dataType:"json",
                success: function(result)
                {
                  
                  $("#task_body_open").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);
                  $(".taskname_sort_val").find(".2bill_image").detach();
                  var layout = $("#hidden_compressed_layout").val();
                  $(".tasks_tr").hide();
                  $(".tasks_tr").next().hide();
                  $(".hidden_tasks_tr").hide();
                  var view = $(".select_view").val();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);

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
                  if(layout == "0")
                  {
                    if(taskidval != "")
                    {
                      // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                    }
                  }
                  else{
                    $("#"+taskidval).show();
                    $("#"+taskidval).next().show();
                    var hidden_tr = taskidval.substr(8);
                    $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                  }
                  if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","233px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                  }
                  $("body").removeClass("loading");
                }
              })
            },2000);
          }
        }
      });
    }
  }
  if($(e.target).hasClass('yes_mark_complete'))
  {
    var task_id = $(e.target).attr("data-task");
    var nexttask_id = $(e.target).attr("data-next");
    var prevtask_id = $(e.target).attr("data-prev");

    $("body").addClass("loading");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
      type:"post",
      data:{task_id:task_id,type:"1"},
      success:function(resultval)
      {
        $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
        var layout = $("#hidden_compressed_layout").val();
        var view = $(".select_view").val();
        if(layout == "1")
        {
          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
          if (typeof nexttask_id !== "undefined") {
            var taskidval = nexttask_id;
          }
          else if (typeof prevtask_id !== "undefined") {
            var taskidval = prevtask_id;
          }
          else{
            var taskidval = '';
          }

          $("#task_tr_"+task_id).next().detach();
          $("#task_tr_"+task_id).detach();
          $("#hidden_tasks_tr_"+task_id).detach();

          $("#task_tr_"+taskidval).show();
          $("#task_tr_"+taskidval).next().show();
          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
          var opentask = $(".hidden_allocated_tr").length;
          var authored = $(".hidden_author_tr").length;
          $("#redline_task_count_val").html(redline);
          $("#open_task_count_val").html(opentask);
          $("#authored_task_count_val").html(authored);
          $("body").removeClass("loading");
        }
        else{
          setTimeout(function() {
            var user_id = $(".select_user_home").val();
            $.ajax({
              url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                
                $("#task_body_open").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);
                $(".taskname_sort_val").find(".2bill_image").detach();
                var layout = $("#hidden_compressed_layout").val();
                $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                var view = $(".select_view").val();
                if(view == "3")
                {
                  if(layout == "1")
                  {
                    $(".author_tr:first").show();
                    $(".author_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_author_tr").show();
                    $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".author_tr").show();
                    $(".author_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_author_tr").hide();
                  }
                }
                else if(view == "2"){
                  $("#open_task_count").hide();
                  $("#redline_task_count").show();
                  $("#authored_task_count").hide();
                  if(layout == "1")
                  {
                    var i = 1;
                    $(".redline_indication").each(function() {
                      if(i == 1)
                      {
                        if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                        {
                          $(this).parents(".allocated_tr").show();
                          $(this).parents(".allocated_tr").next().show();
                          i++;
                        }
                      }
                    });
                    $(".table_layout").show();
                    $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    
                    var j = 1;
                    $(".redline_indication_layout").each(function() {
                      if(j == 1)
                      {
                        if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                        {
                          $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                          j++;
                        }
                      }
                    });
                  }
                  else{
                    $(".redline_indication").parents(".allocated_tr").show();
                    $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }
                }
                else if(view == "1"){
                  if(layout == "1")
                  {
                    $(".allocated_tr:first").show();
                    $(".allocated_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_allocated_tr").show();
                    $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".allocated_tr").show();
                    $(".allocated_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }
                }

                var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                var opentask = $(".hidden_allocated_tr").length;
                var authored = $(".hidden_author_tr").length;
                $("#redline_task_count_val").html(redline);
                $("#open_task_count_val").html(opentask);
                $("#authored_task_count_val").html(authored);

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
                if(layout == "0")
                {
                  if(taskidval != "")
                  {
                    // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                  }
                }
                else{
                  $("#"+taskidval).show();
                  $("#"+taskidval).next().show();
                  var hidden_tr = taskidval.substr(8);
                  $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                }
                if(layout == "1")
                {
                  $(".open_layout_div").addClass("open_layout_div_change");
                  var open_tasks_height = $(".open_layout_div").height();
                  var margintop = parseInt(open_tasks_height);
                  $(".open_layout_div").css("position","fixed");
                  $(".open_layout_div").css("height","312px");
                  if(open_tasks_height > 312)
                  {
                    $(".open_layout_div").css("overflow-y","scroll");
                  }
                  if(open_tasks_height < 50)
                  {
                    $(".table_layout").css("margin-top","20px");
                  }
                    else{
                      $(".table_layout").css("margin-top","233px");
                    }
                }
                else{
                  $(".open_layout_div").removeClass("open_layout_div_change");
                  $(".open_layout_div").css("position","unset");
                  $(".open_layout_div").css("height","auto");
                  $(".open_layout_div").css("overflow-y","unset");
                    $(".table_layout").css("margin-top","0px");
                }
                $("body").removeClass("loading");
              }
            })
          },2000);
        }
        $.colorbox.close();
      }
    });
  }
  if($(e.target).hasClass('no_mark_complete'))
  {
    var task_id = $(e.target).attr("data-task");
    var nexttask_id = $(e.target).attr("data-next");
    var prevtask_id = $(e.target).attr("data-prev");
    
    $("body").addClass("loading");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
      type:"post",
      data:{task_id:task_id,type:"0"},
      success:function(resultval)
      {
        $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
        var layout = $("#hidden_compressed_layout").val();
        var view = $(".select_view").val();
        if(layout == "1")
        {
          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
          if (typeof nexttask_id !== "undefined") {
            var taskidval = nexttask_id;
          }
          else if (typeof prevtask_id !== "undefined") {
            var taskidval = prevtask_id;
          }
          else{
            var taskidval = '';
          }

          $("#task_tr_"+task_id).next().detach();
          $("#task_tr_"+task_id).detach();
          $("#hidden_tasks_tr_"+task_id).detach();

          $("#task_tr_"+taskidval).show();
          $("#task_tr_"+taskidval).next().show();
          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
          var opentask = $(".hidden_allocated_tr").length;
          var authored = $(".hidden_author_tr").length;
          $("#redline_task_count_val").html(redline);
          $("#open_task_count_val").html(opentask);
          $("#authored_task_count_val").html(authored);
          $("body").removeClass("loading");
        }
        else{
          setTimeout(function() {
            var user_id = $(".select_user_home").val();
            $.ajax({
              url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                
                $("#task_body_open").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);
                $(".taskname_sort_val").find(".2bill_image").detach();
                var layout = $("#hidden_compressed_layout").val();
                $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                var view = $(".select_view").val();
                if(view == "3")
                {
                  if(layout == "1")
                  {
                    $(".author_tr:first").show();
                    $(".author_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_author_tr").show();
                    $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".author_tr").show();
                    $(".author_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_author_tr").hide();
                  }

                  
                }
                else if(view == "2"){
                  $("#open_task_count").hide();
                  $("#redline_task_count").show();
                  $("#authored_task_count").hide();
                  if(layout == "1")
                  {
                    var i = 1;
                    $(".redline_indication").each(function() {
                      if(i == 1)
                      {
                        if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                        {
                          $(this).parents(".allocated_tr").show();
                          $(this).parents(".allocated_tr").next().show();
                          i++;
                        }
                      }
                    });
                    $(".table_layout").show();
                    $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    
                    var j = 1;
                    $(".redline_indication_layout").each(function() {
                      if(j == 1)
                      {
                        if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                        {
                          $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                          j++;
                        }
                      }
                    });
                  }
                  else{
                    $(".redline_indication").parents(".allocated_tr").show();
                    $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  
                }
                else if(view == "1"){
                  if(layout == "1")
                  {
                    $(".allocated_tr:first").show();
                    $(".allocated_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_allocated_tr").show();
                    $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".allocated_tr").show();
                    $(".allocated_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  
                }

                var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                var opentask = $(".hidden_allocated_tr").length;
                var authored = $(".hidden_author_tr").length;
                $("#redline_task_count_val").html(redline);
                $("#open_task_count_val").html(opentask);
                $("#authored_task_count_val").html(authored);

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
                if(layout == "0")
                {
                  if(taskidval != "")
                  {
                    // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                  }
                }
                else{
                  $("#"+taskidval).show();
                  $("#"+taskidval).next().show();
                  var hidden_tr = taskidval.substr(8);
                  $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                }
                if(layout == "1")
                {
                  $(".open_layout_div").addClass("open_layout_div_change");
                  var open_tasks_height = $(".open_layout_div").height();
                  var margintop = parseInt(open_tasks_height);
                  $(".open_layout_div").css("position","fixed");
                  $(".open_layout_div").css("height","312px");
                  if(open_tasks_height > 312)
                  {
                    $(".open_layout_div").css("overflow-y","scroll");
                  }
                  if(open_tasks_height < 50)
                  {
                    $(".table_layout").css("margin-top","20px");
                  }
                    else{
                      $(".table_layout").css("margin-top","233px");
                    }
                }
                else{
                  $(".open_layout_div").removeClass("open_layout_div_change");
                  $(".open_layout_div").css("position","unset");
                  $(".open_layout_div").css("height","auto");
                  $(".open_layout_div").css("overflow-y","unset");
                    $(".table_layout").css("margin-top","0px");
                }
                $("body").removeClass("loading");
              }
            })
          },2000);
        }
        $.colorbox.close();
      }
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
        
        var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
        var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
        if (typeof nexttask_id !== "undefined") {
          var taskidval = nexttask_id;
        }
        else if (typeof prevtask_id !== "undefined") {
          var taskidval = prevtask_id;
        }
        else{
          var taskidval = '';
        }
        $.ajax({
          url:"<?php echo URL::to('user/park_task_complete'); ?>",
          type:"post",
          data:{task_id:task_id,park_date:park_date},
          success:function(resultval)
          {
            $(".park_task_modal").modal("hide");
            var layout = $("#hidden_compressed_layout").val();
            var view = $(".select_view").val();
            if(layout == "1")
            {
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

              var opentask = $("#open_task_count_val").html();
              var parktask = $("#park_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              var countpark = parseInt(parktask) + 1;
              $("#open_task_count_val").html(countopen);
              $("#park_task_count_val").html(countpark);
              $("body").removeClass("loading");
            }
            else{
              setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                  dataType:"json",
                  success: function(result)
                  {
                    
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find(".2bill_image").detach();
                    var layout = $("#hidden_compressed_layout").val();
                    $(".tasks_tr").hide();
                    $(".tasks_tr").next().hide();
                    $(".hidden_tasks_tr").hide();
                    var view = $(".select_view").val();
                    if(view == "3")
                    {
                      if(layout == "1")
                      {
                        $(".author_tr:first").show();
                        $(".author_tr:first").next().show();
                        $(".table_layout").show();
                        $(".table_layout").find(".hidden_author_tr").show();
                        $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                      }
                      else{
                        $(".author_tr").show();
                        $(".author_tr").next().show();
                        $(".table_layout").hide();
                        $(".table_layout").find(".hidden_author_tr").hide();
                      }

                      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                      var opentask = $(".hidden_allocated_tr").length;
                      var authored = $(".hidden_author_tr").length;

                      var parktask = $("#park_task_count_val").html();
                      var countpark = parseInt(parktask) + 1;
                      $("#park_task_count_val").html(countpark);
                      $("#redline_task_count_val").html(redline);
                      $("#open_task_count_val").html(opentask);
                      $("#authored_task_count_val").html(authored);
                    }
                    else if(view == "2"){
                      $("#open_task_count").hide();
                      $("#redline_task_count").show();
                      $("#authored_task_count").hide();
                      if(layout == "1")
                      {
                        var i = 1;
                        $(".redline_indication").each(function() {
                          if(i == 1)
                          {
                            if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                            {
                              $(this).parents(".allocated_tr").show();
                              $(this).parents(".allocated_tr").next().show();
                              i++;
                            }
                          }
                        });
                        $(".table_layout").show();
                        $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                        
                        var j = 1;
                        $(".redline_indication_layout").each(function() {
                          if(j == 1)
                          {
                            if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                            {
                              $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                              j++;
                            }
                          }
                        });
                      }
                      else{
                        $(".redline_indication").parents(".allocated_tr").show();
                        $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                        $(".table_layout").hide();
                        $(".table_layout").find(".hidden_allocated_tr").hide();
                      }

                      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                      var opentask = $(".hidden_allocated_tr").length;
                      var authored = $(".hidden_author_tr").length;

                      var parktask = $("#park_task_count_val").html();
                      var countpark = parseInt(parktask) + 1;
                      $("#park_task_count_val").html(countpark);
                      $("#redline_task_count_val").html(redline);
                      $("#open_task_count_val").html(opentask);
                      $("#authored_task_count_val").html(authored);
                    }
                    else if(view == "1"){
                      if(layout == "1")
                      {
                        $(".allocated_tr:first").show();
                        $(".allocated_tr:first").next().show();
                        $(".table_layout").show();
                        $(".table_layout").find(".hidden_allocated_tr").show();
                        $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                      }
                      else{
                        $(".allocated_tr").show();
                        $(".allocated_tr").next().show();
                        $(".table_layout").hide();
                        $(".table_layout").find(".hidden_allocated_tr").hide();
                      }

                      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                      var opentask = $(".hidden_allocated_tr").length;
                      var authored = $(".hidden_author_tr").length;

                      var parktask = $("#park_task_count_val").html();
                      var countpark = parseInt(parktask) + 1;
                      $("#park_task_count_val").html(countpark);
                      $("#redline_task_count_val").html(redline);
                      $("#open_task_count_val").html(opentask);
                      $("#authored_task_count_val").html(authored);
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
                    if(layout == "0")
                    {
                      if(taskidval != "")
                      {
                        // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                      }
                    }
                    else{
                      $("#"+taskidval).show();
                      $("#"+taskidval).next().show();
                      var hidden_tr = taskidval.substr(8);
                      $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                    }
                    if(layout == "1")
                    {
                      $(".open_layout_div").addClass("open_layout_div_change");
                      var open_tasks_height = $(".open_layout_div").height();
                      var margintop = parseInt(open_tasks_height);
                      $(".open_layout_div").css("position","fixed");
                      $(".open_layout_div").css("height","312px");
                      if(open_tasks_height > 312)
                      {
                        $(".open_layout_div").css("overflow-y","scroll");
                      }
                      if(open_tasks_height < 50)
                      {
                        $(".table_layout").css("margin-top","20px");
                      }
                        else{
                          $(".table_layout").css("margin-top","233px");
                        }
                    }
                    else{
                      $(".open_layout_div").removeClass("open_layout_div_change");
                      $(".open_layout_div").css("position","unset");
                      $(".open_layout_div").css("height","auto");
                      $(".open_layout_div").css("overflow-y","unset");
                        $(".table_layout").css("margin-top","0px");
                    }
                    $("body").removeClass("loading");
                  }
                })
              },2000);
            }
          }
        })
      }
  }
  if($(e.target).hasClass('mark_as_complete_author'))
  {
    var r = confirm("You are about to mark this task as Complete are you sure you want to continue?");
    if(r)
    {
      $("body").addClass("loading");
      var task_id = $(e.target).attr("data-element");

      var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
      var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
        type:"post",
        data:{task_id:task_id,type:"0"},
        success:function(resultval)
        {
          $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
          var layout = $("#hidden_compressed_layout").val();
          $("#hidden_tasks_tr_"+task_id).detach();
          $("#task_tr_"+task_id).detach();
          $(".hidden_tasks_tr:visible").eq(0).find(".taskname_sort_val").trigger("click");
          $("body").removeClass("loading");

          
            // setTimeout(function() {
            //   var user_id = $(".select_user_home").val();
            //   $.ajax({
            //     url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
            //     type:"post",
            //     data:{user_id:user_id},
            //     dataType:"json",
            //     success: function(result)
            //     {
                  
            //       $("#task_body_open").html(result['open_tasks']);
            //       $("#task_body_layout").html(result['layout']);
            //       $(".taskname_sort_val").find(".2bill_image").detach();
            //       var layout = $("#hidden_compressed_layout").val();
            //       $(".tasks_tr").hide();
            //       $(".tasks_tr").next().hide();
            //       $(".hidden_tasks_tr").hide();
            //       var view = $(".select_view").val();
            //       if(view == "3")
            //       {
            //         if(layout == "1")
            //         {
            //           $(".author_tr:first").show();
            //           $(".author_tr:first").next().show();
            //           $(".table_layout").show();
            //           $(".table_layout").find(".hidden_author_tr").show();
            //           $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
            //         }
            //         else{
            //           $(".author_tr").show();
            //           $(".author_tr").next().show();
            //           $(".table_layout").hide();
            //           $(".table_layout").find(".hidden_author_tr").hide();
            //         }

            //       }
            //       else if(view == "2"){
            //         $("#open_task_count").hide();
            //         $("#redline_task_count").show();
            //         $("#authored_task_count").hide();
            //         if(layout == "1")
            //         {
            //           var i = 1;
            //           $(".redline_indication").each(function() {
            //             if(i == 1)
            //             {
            //               if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
            //               {
            //                 $(this).parents(".allocated_tr").show();
            //                 $(this).parents(".allocated_tr").next().show();
            //                 i++;
            //               }
            //             }
            //           });
            //           $(".table_layout").show();
            //           $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
            //           var j = 1;
            //           $(".redline_indication_layout").each(function() {
            //             if(j == 1)
            //             {
            //               if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
            //               {
            //                 $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
            //                 j++;
            //               }
            //             }
            //           });
            //         }
            //         else{
            //           $(".redline_indication").parents(".allocated_tr").show();
            //           $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
            //           $(".table_layout").hide();
            //           $(".table_layout").find(".hidden_allocated_tr").hide();
            //         }

            //       }
            //       else if(view == "1"){
            //         if(layout == "1")
            //         {
            //           $(".allocated_tr:first").show();
            //           $(".allocated_tr:first").next().show();
            //           $(".table_layout").show();
            //           $(".table_layout").find(".hidden_allocated_tr").show();
            //           $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
            //         }
            //         else{
            //           $(".allocated_tr").show();
            //           $(".allocated_tr").next().show();
            //           $(".table_layout").hide();
            //           $(".table_layout").find(".hidden_allocated_tr").hide();
            //         }
            //       }

            //       var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
            //       var opentask = $(".hidden_allocated_tr").length;
            //       var authored = $(".hidden_author_tr").length;
            //       $("#redline_task_count_val").html(redline);
            //       $("#open_task_count_val").html(opentask);
            //       $("#authored_task_count_val").html(authored);

            //       $("[data-toggle=popover]").popover({
            //           html : true,
            //           content: function() {
            //             var content = $(this).attr("data-popover-content");
            //             return $(content).children(".popover-body").html();
            //           },
            //           title: function() {
            //             var title = $(this).attr("data-popover-content");
            //             return $(title).children(".popover-heading").html();
            //           }
            //       });
            //       if(layout == "0")
            //       {
            //         if(taskidval != "")
            //         {
            //           // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
            //         }
            //       }
            //       else{
            //         $("#"+taskidval).show();
            //         $("#"+taskidval).next().show();
            //         var hidden_tr = taskidval.substr(8);
            //         $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
            //        }

            //       if(layout == "1")
            //       {
            //         $(".open_layout_div").addClass("open_layout_div_change");
            //         var open_tasks_height = $(".open_layout_div").height();
            //         var margintop = parseInt(open_tasks_height);
            //         $(".open_layout_div").css("position","fixed");
            //         $(".open_layout_div").css("height","312px");
            //         if(open_tasks_height > 312)
            //         {
            //           $(".open_layout_div").css("overflow-y","scroll");
            //         }
            //         if(open_tasks_height < 50)
            //         {
            //           $(".table_layout").css("margin-top","20px");
            //         }
            //           else{
            //             $(".table_layout").css("margin-top","233px");
            //           }
            //       }
            //       else{
            //         $(".open_layout_div").removeClass("open_layout_div_change");
            //         $(".open_layout_div").css("position","unset");
            //         $(".open_layout_div").css("height","auto");
            //         $(".open_layout_div").css("overflow-y","unset");
            //           $(".table_layout").css("margin-top","0px");
            //       }
            //        $("body").removeClass("loading");
            //     }
            //   })
            // },2000);
        }
      })
    }
  }
  if($(e.target).hasClass('mark_as_incomplete'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_incomplete'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        window.location.replace("<?php echo URL::to('user/task_manager?tr_task_id='); ?>"+task_id);
      }
    })
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
    var user_id = $(".select_user_home").val();
    if(user_id == "")
    {
      alert("Please Select the user and then click on the refresh button.");
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
          type:"post",
          data:{user_id:user_id},
          dataType:"json",
          success: function(result)
          {
            
            $("#task_body_open").html(result['open_tasks']);
            $("#task_body_layout").html(result['layout']);
            $(".taskname_sort_val").find(".2bill_image").detach();
            var layout = $("#hidden_compressed_layout").val();
            $(".tasks_tr").hide();
            $(".tasks_tr").next().hide();
            $(".hidden_tasks_tr").hide();
            var view = $(".select_view").val();
            if(view == "3")
            {
              if(layout == "1")
              {
                $(".author_tr:first").show();
                $(".author_tr:first").next().show();
                $(".table_layout").show();
                $(".table_layout").find(".hidden_author_tr").show();
                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
              }
              else{
                $(".author_tr").show();
                $(".author_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_author_tr").hide();
              }
              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }
            else if(view == "2"){
              $("#open_task_count").hide();
              $("#redline_task_count").show();
              $("#authored_task_count").hide();
              if(layout == "1")
              {
                var i = 1;
                $(".redline_indication").each(function() {
                  if(i == 1)
                  {
                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                    {
                      $(this).parents(".allocated_tr").show();
                      $(this).parents(".allocated_tr").next().show();
                      i++;
                    }
                  }
                });
                $(".table_layout").show();
                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                
                var j = 1;
                $(".redline_indication_layout").each(function() {
                  if(j == 1)
                  {
                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                    {
                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                      j++;
                    }
                  }
                });
              }
              else{
                $(".redline_indication").parents(".allocated_tr").show();
                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_allocated_tr").hide();
              }

              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }
            else if(view == "1"){
              if(layout == "1")
              {
                $(".allocated_tr:first").show();
                $(".allocated_tr:first").next().show();
                $(".table_layout").show();
                $(".table_layout").find(".hidden_allocated_tr").show();
                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
              }
              else{
                $(".allocated_tr").show();
                $(".allocated_tr").next().show();
                $(".table_layout").hide();
                $(".table_layout").find(".hidden_allocated_tr").hide();
              }
              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
              var opentask = $(".hidden_allocated_tr").length;
              var authored = $(".hidden_author_tr").length;
              $("#redline_task_count_val").html(redline);
              $("#open_task_count_val").html(opentask);
              $("#authored_task_count_val").html(authored);
            }

            if(layout == "1")
            {
              $(".open_layout_div").addClass("open_layout_div_change");
              var open_tasks_height = $(".open_layout_div").height();
              var margintop = parseInt(open_tasks_height);
              $(".open_layout_div").css("position","fixed");
              $(".open_layout_div").css("height","312px");
              if(open_tasks_height > 312)
              {
                $(".open_layout_div").css("overflow-y","scroll");
              }
              if(open_tasks_height < 50)
              {
                $(".table_layout").css("margin-top","20px");
              }
                else{
                  $(".table_layout").css("margin-top","233px");
                }
            }
            else{
              $(".open_layout_div").removeClass("open_layout_div_change");
              $(".open_layout_div").css("position","unset");
              $(".open_layout_div").css("height","auto");
              $(".open_layout_div").css("overflow-y","unset");
                $(".table_layout").css("margin-top","0px");
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
        $(".allocate_email").removeClass("disable_user");
        $(".allocate_email").val("");
        
        $.ajax({
          url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
          type:"post",
          data:{fileid:"0"},
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
          CKEDITOR.instances['editor_1'].setData("");
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
          $("#show_auto_close_msg").val(result);
          if($(e.target).is(":checked"))
          {
            $("#task_tr_"+task_id).find(".mark_as_complete").addClass("auto_close_task_complete");
            $("#task_tr_"+task_id).find(".edit_allocate_user").addClass("auto_close_task_complete");
          }
          else{
            $("#task_tr_"+task_id).find(".mark_as_complete").removeClass("auto_close_task_complete");
            $("#task_tr_"+task_id).find(".edit_allocate_user").removeClass("auto_close_task_complete");
          }
        }
    });
  }
  if($(e.target).hasClass('add_comment_and_allocate'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $("#hidden_task_id_task_specifics").val();
      var show_auto_close = $("#show_auto_close_msg").val();
      if(comments == "")
      {
        alert("Please enter new comments and then click on the Add New Comment Button");
      }
      else{
        if(show_auto_close == "1")
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" class="common_black_button yes_allocate_back">Yes</a><a href="javascript:" data-task="'+task_id+'" class="common_black_button no_allocate_back">No</a></p>',fixed:true,width:"800px"});
            $("body").removeClass("loading");
            return false;
        }
        else{
          $("body").addClass("loading");
          setTimeout(function() {
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
                  $("body").removeClass("loading");
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
                      var htmlval = $("#allocation_history_div_"+task_id).html();
                      $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                      var htmlval2 = $("#history_body").find("tbody").html();
                      $("#history_body").find("tbody").html(result['trval']+htmlval2);
                      $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                      $("#allocated_to_name_"+task_id).html(result['to']);
                      $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                      var count = 1;
                      $("#allocation_history_div_"+task_id).find("p").each(function() {
                        if(count > 5)
                        {
                          $(this).detach();
                        }
                        count++;
                      })
                      $(".allocation_modal").modal("hide");
                      var layout = $("#hidden_compressed_layout").val();
                      if(layout == "1")
                      {
                        if($("#task_tr_"+task_id).hasClass('author_tr'))
                        {
                        }
                        else{
                          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          if (typeof nexttask_id !== "undefined") {
                            var taskidval = nexttask_id;
                          }
                          else if (typeof prevtask_id !== "undefined") {
                            var taskidval = prevtask_id;
                          }
                          else{
                            var taskidval = '';
                          }

                          $("#task_tr_"+task_id).next().detach();
                          $("#task_tr_"+task_id).detach();
                          $("#hidden_tasks_tr_"+task_id).detach();

                          $("#task_tr_"+taskidval).show();
                          $("#task_tr_"+taskidval).next().show();
                          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

                          var opentask = $("#open_task_count_val").html();
                          var countopen = parseInt(opentask) - 1;
                          $("#open_task_count_val").html(countopen);
                        }
                        $("body").removeClass("loading");
                      }
                      else{
                        var user_id = $(".select_user_home").val();
                        $.ajax({
                          url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                          type:"post",
                          data:{user_id:user_id},
                              dataType:"json",
                          success: function(result)
                          {
                            
                            $("#task_body_open").html(result['open_tasks']);
                            $("#task_body_layout").html(result['layout']);
                            $(".taskname_sort_val").find(".2bill_image").detach();
                            var layout = $("#hidden_compressed_layout").val();
                            var view = $(".select_view").val();
                            $(".tasks_tr").hide();
                            $(".tasks_tr").next().hide();
                            $(".hidden_tasks_tr").hide();
                            if(view == "3")
                            {
                              if(layout == "1")
                              {
                                $(".author_tr:first").show();
                                $(".author_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_author_tr").show();
                                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                              }
                              else{
                                $(".author_tr").show();
                                $(".author_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_author_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "2"){
                              $("#open_task_count").hide();
                              $("#redline_task_count").show();
                              $("#authored_task_count").hide();
                              if(layout == "1")
                              {
                                var i = 1;
                                $(".redline_indication").each(function() {
                                  if(i == 1)
                                  {
                                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                    {
                                      $(this).parents(".allocated_tr").show();
                                      $(this).parents(".allocated_tr").next().show();
                                      i++;
                                    }
                                  }
                                });
                                $(".table_layout").show();
                                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                                
                                var j = 1;
                                $(".redline_indication_layout").each(function() {
                                  if(j == 1)
                                  {
                                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                    {
                                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                                      j++;
                                    }
                                  }
                                });
                              }
                              else{
                                $(".redline_indication").parents(".allocated_tr").show();
                                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }

                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "1"){
                              if(layout == "1")
                              {
                                $(".allocated_tr:first").show();
                                $(".allocated_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_allocated_tr").show();
                                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                              }
                              else{
                                $(".allocated_tr").show();
                                $(".allocated_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
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

                            if(layout == "0")
                            {
                                if(taskidval != "")
                                {
                                  //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                                }
                            }
                            else{
                              $("#"+taskidval).show();
                              $("#"+taskidval).next().show();
                            
                              var hidden_tr = taskidval.substr(8);
                              $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                            }

                            if(layout == "1")
                            {
                              $(".open_layout_div").addClass("open_layout_div_change");
                              var open_tasks_height = $(".open_layout_div").height();
                              var margintop = parseInt(open_tasks_height);
                              $(".open_layout_div").css("position","fixed");
                              $(".open_layout_div").css("height","312px");
                              if(open_tasks_height > 312)
                              {
                                $(".open_layout_div").css("overflow-y","scroll");
                              }
                              if(open_tasks_height < 50)
                              {
                                $(".table_layout").css("margin-top","20px");
                              }
                                else{
                                  $(".table_layout").css("margin-top","233px");
                                }
                            }
                            else{
                              $(".open_layout_div").removeClass("open_layout_div_change");
                              $(".open_layout_div").css("position","unset");
                              $(".open_layout_div").css("height","auto");
                              $(".open_layout_div").css("overflow-y","unset");
                                $(".table_layout").css("margin-top","0px");
                            }
                            $("body").removeClass("loading");
                          }
                        })
                      }
                    }
                  });
                }
              }
            })
          },1000);
        }
      }
  }
  if($(e.target).hasClass('yes_allocate_back'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $(e.target).attr("data-task");

      $("body").addClass("loading");
      setTimeout(function() {
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
              $("body").removeClass("loading");
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
                data:{task_id:task_id,new_allocation:new_allocation,type:"1"},
                dataType:"json",
                success:function(result)
                {
                  var htmlval = $("#allocation_history_div_"+task_id).html();
                  $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                  var htmlval2 = $("#history_body").find("tbody").html();
                  $("#history_body").find("tbody").html(result['trval']+htmlval2);
                  $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                  $("#allocated_to_name_"+task_id).html(result['to']);
                  $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                  var count = 1;
                  $("#allocation_history_div_"+task_id).find("p").each(function() {
                    if(count > 5)
                    {
                      $(this).detach();
                    }
                    count++;
                  })
                  $(".allocation_modal").modal("hide");
                  var layout = $("#hidden_compressed_layout").val();
                  if(layout == "1")
                  {
                    if($("#task_tr_"+task_id).hasClass('author_tr'))
                    {
                    }
                    else{
                      var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      if (typeof nexttask_id !== "undefined") {
                        var taskidval = nexttask_id;
                      }
                      else if (typeof prevtask_id !== "undefined") {
                        var taskidval = prevtask_id;
                      }
                      else{
                        var taskidval = '';
                      }

                      $("#task_tr_"+task_id).next().detach();
                      $("#task_tr_"+task_id).detach();
                      $("#hidden_tasks_tr_"+task_id).detach();

                      $("#task_tr_"+taskidval).show();
                      $("#task_tr_"+taskidval).next().show();
                      $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

                      var opentask = $("#open_task_count_val").html();
                      var countopen = parseInt(opentask) - 1;
                      $("#open_task_count_val").html(countopen);
                    }
                    $("body").removeClass("loading");
                  }
                  else{
                    var user_id = $(".select_user_home").val();
                    $.ajax({
                      url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                      type:"post",
                      data:{user_id:user_id},
                          dataType:"json",
                      success: function(result)
                      {
                        
                        $("#task_body_open").html(result['open_tasks']);
                        $("#task_body_layout").html(result['layout']);
                        $(".taskname_sort_val").find(".2bill_image").detach();
                        var layout = $("#hidden_compressed_layout").val();
                        var view = $(".select_view").val();
                        $(".tasks_tr").hide();
                        $(".tasks_tr").next().hide();
                        $(".hidden_tasks_tr").hide();
                        if(view == "3")
                        {
                          if(layout == "1")
                          {
                            $(".author_tr:first").show();
                            $(".author_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_author_tr").show();
                            $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                          }
                          else{
                            $(".author_tr").show();
                            $(".author_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_author_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "2"){
                          $("#open_task_count").hide();
                          $("#redline_task_count").show();
                          $("#authored_task_count").hide();
                          if(layout == "1")
                          {
                            var i = 1;
                            $(".redline_indication").each(function() {
                              if(i == 1)
                              {
                                if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                {
                                  $(this).parents(".allocated_tr").show();
                                  $(this).parents(".allocated_tr").next().show();
                                  i++;
                                }
                              }
                            });
                            $(".table_layout").show();
                            $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                            
                            var j = 1;
                            $(".redline_indication_layout").each(function() {
                              if(j == 1)
                              {
                                if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                {
                                  $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                                  j++;
                                }
                              }
                            });
                          }
                          else{
                            $(".redline_indication").parents(".allocated_tr").show();
                            $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }

                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "1"){
                          if(layout == "1")
                          {
                            $(".allocated_tr:first").show();
                            $(".allocated_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_allocated_tr").show();
                            $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                          }
                          else{
                            $(".allocated_tr").show();
                            $(".allocated_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
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

                        if(layout == "0")
                        {
                            if(taskidval != "")
                            {
                              //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                            }
                        }
                        else{
                          $("#"+taskidval).show();
                          $("#"+taskidval).next().show();
                        
                          var hidden_tr = taskidval.substr(8);
                          $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                        }

                        if(layout == "1")
                        {
                          $(".open_layout_div").addClass("open_layout_div_change");
                          var open_tasks_height = $(".open_layout_div").height();
                          var margintop = parseInt(open_tasks_height);
                          $(".open_layout_div").css("position","fixed");
                          $(".open_layout_div").css("height","312px");
                          if(open_tasks_height > 312)
                          {
                            $(".open_layout_div").css("overflow-y","scroll");
                          }
                          if(open_tasks_height < 50)
                          {
                            $(".table_layout").css("margin-top","20px");
                          }
                            else{
                              $(".table_layout").css("margin-top","233px");
                            }
                        }
                        else{
                          $(".open_layout_div").removeClass("open_layout_div_change");
                          $(".open_layout_div").css("position","unset");
                          $(".open_layout_div").css("height","auto");
                          $(".open_layout_div").css("overflow-y","unset");
                            $(".table_layout").css("margin-top","0px");
                        }
                        $("body").removeClass("loading");
                      }
                    })
                  }

                  $.colorbox.close();
                }
              });
            }
          }
        })
      },1000);
  }
  if($(e.target).hasClass('no_allocate_back'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $(e.target).attr("data-task");
      
      $("body").addClass("loading");
      setTimeout(function() {
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
              $("body").removeClass("loading");
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
                  var htmlval = $("#allocation_history_div_"+task_id).html();
                  $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                  var htmlval2 = $("#history_body").find("tbody").html();
                  $("#history_body").find("tbody").html(result['trval']+htmlval2);
                  $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                  $("#allocated_to_name_"+task_id).html(result['to']);
                  $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                  var count = 1;
                  $("#allocation_history_div_"+task_id).find("p").each(function() {
                    if(count > 5)
                    {
                      $(this).detach();
                    }
                    count++;
                  })
                  $(".allocation_modal").modal("hide");
                  var layout = $("#hidden_compressed_layout").val();
                  if(layout == "1")
                  {
                    if($("#task_tr_"+task_id).hasClass('author_tr'))
                    {
                    }
                    else{
                      var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      if (typeof nexttask_id !== "undefined") {
                        var taskidval = nexttask_id;
                      }
                      else if (typeof prevtask_id !== "undefined") {
                        var taskidval = prevtask_id;
                      }
                      else{
                        var taskidval = '';
                      }

                      $("#task_tr_"+task_id).next().detach();
                      $("#task_tr_"+task_id).detach();
                      $("#hidden_tasks_tr_"+task_id).detach();

                      $("#task_tr_"+taskidval).show();
                      $("#task_tr_"+taskidval).next().show();
                      $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

                      var opentask = $("#open_task_count_val").html();
                      var countopen = parseInt(opentask) - 1;
                      $("#open_task_count_val").html(countopen);
                    }
                    $("body").removeClass("loading");
                  }
                  else{
                    var user_id = $(".select_user_home").val();
                    $.ajax({
                      url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                      type:"post",
                      data:{user_id:user_id},
                          dataType:"json",
                      success: function(result)
                      {
                        
                        $("#task_body_open").html(result['open_tasks']);
                        $("#task_body_layout").html(result['layout']);
                        $(".taskname_sort_val").find(".2bill_image").detach();
                        var layout = $("#hidden_compressed_layout").val();
                        var view = $(".select_view").val();
                        $(".tasks_tr").hide();
                        $(".tasks_tr").next().hide();
                        $(".hidden_tasks_tr").hide();
                        if(view == "3")
                        {
                          if(layout == "1")
                          {
                            $(".author_tr:first").show();
                            $(".author_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_author_tr").show();
                            $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                          }
                          else{
                            $(".author_tr").show();
                            $(".author_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_author_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "2"){
                          $("#open_task_count").hide();
                          $("#redline_task_count").show();
                          $("#authored_task_count").hide();
                          if(layout == "1")
                          {
                            var i = 1;
                            $(".redline_indication").each(function() {
                              if(i == 1)
                              {
                                if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                {
                                  $(this).parents(".allocated_tr").show();
                                  $(this).parents(".allocated_tr").next().show();
                                  i++;
                                }
                              }
                            });
                            $(".table_layout").show();
                            $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                            
                            var j = 1;
                            $(".redline_indication_layout").each(function() {
                              if(j == 1)
                              {
                                if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                {
                                  $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                                  j++;
                                }
                              }
                            });
                          }
                          else{
                            $(".redline_indication").parents(".allocated_tr").show();
                            $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }

                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "1"){
                          if(layout == "1")
                          {
                            $(".allocated_tr:first").show();
                            $(".allocated_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_allocated_tr").show();
                            $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                          }
                          else{
                            $(".allocated_tr").show();
                            $(".allocated_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
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

                        if(layout == "0")
                        {
                            if(taskidval != "")
                            {
                              //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                            }
                        }
                        else{
                          $("#"+taskidval).show();
                          $("#"+taskidval).next().show();
                        
                          var hidden_tr = taskidval.substr(8);
                          $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                        }

                        if(layout == "1")
                        {
                          $(".open_layout_div").addClass("open_layout_div_change");
                          var open_tasks_height = $(".open_layout_div").height();
                          var margintop = parseInt(open_tasks_height);
                          $(".open_layout_div").css("position","fixed");
                          $(".open_layout_div").css("height","312px");
                          if(open_tasks_height > 312)
                          {
                            $(".open_layout_div").css("overflow-y","scroll");
                          }
                          if(open_tasks_height < 50)
                          {
                            $(".table_layout").css("margin-top","20px");
                          }
                            else{
                              $(".table_layout").css("margin-top","233px");
                            }
                        }
                        else{
                          $(".open_layout_div").removeClass("open_layout_div_change");
                          $(".open_layout_div").css("position","unset");
                          $(".open_layout_div").css("height","auto");
                          $(".open_layout_div").css("overflow-y","unset");
                            $(".table_layout").css("margin-top","0px");
                        }
                        $("body").removeClass("loading");
                      }
                    })
                  }
                  $.colorbox.close();
                }
              });
            }
          }
        })
      },1000);
  }
  if($(e.target).hasClass('add_comment_allocate_to_btn'))
  {
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
          setTimeout(function() {
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
                      var htmlval = $("#allocation_history_div_"+task_id).html();
                      $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                      var htmlval2 = $("#history_body").find("tbody").html();
                      $("#history_body").find("tbody").html(result['trval']+htmlval2);
                      $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                      $("#allocated_to_name_"+task_id).html(result['to']);
                      $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                      var count = 1;
                      $("#allocation_history_div_"+task_id).find("p").each(function() {
                        if(count > 5)
                        {
                          $(this).detach();
                        }
                        count++;
                      })
                      $(".allocation_modal").modal("hide");
                      var layout = $("#hidden_compressed_layout").val();
                      if(layout == "1")
                      {
                        if($("#task_tr_"+task_id).hasClass('author_tr'))
                        {
                        }
                        else{
                          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          if (typeof nexttask_id !== "undefined") {
                            var taskidval = nexttask_id;
                          }
                          else if (typeof prevtask_id !== "undefined") {
                            var taskidval = prevtask_id;
                          }
                          else{
                            var taskidval = '';
                          }

                          $("#task_tr_"+task_id).next().detach();
                          $("#task_tr_"+task_id).detach();
                          $("#hidden_tasks_tr_"+task_id).detach();

                          $("#task_tr_"+taskidval).show();
                          $("#task_tr_"+taskidval).next().show();
                          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

                          var opentask = $("#open_task_count_val").html();
                          var countopen = parseInt(opentask) - 1;
                          $("#open_task_count_val").html(countopen);
                        }
                        $("body").removeClass("loading");
                      }
                      else{
                        var user_id = $(".select_user_home").val();
                        $.ajax({
                          url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                          type:"post",
                          data:{user_id:user_id},
                              dataType:"json",
                          success: function(result)
                          {
                            $("#task_body_open").html(result['open_tasks']);
                            $("#task_body_layout").html(result['layout']);
                            $(".taskname_sort_val").find(".2bill_image").detach();
                            var layout = $("#hidden_compressed_layout").val();
                            var view = $(".select_view").val();
                            $(".tasks_tr").hide();
                            $(".tasks_tr").next().hide();
                            $(".hidden_tasks_tr").hide();
                            if(view == "3")
                            {
                              if(layout == "1")
                              {
                                $(".author_tr:first").show();
                                $(".author_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_author_tr").show();
                                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                              }
                              else{
                                $(".author_tr").show();
                                $(".author_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_author_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "2"){
                              $("#open_task_count").hide();
                              $("#redline_task_count").show();
                              $("#authored_task_count").hide();
                              if(layout == "1")
                              {
                                var i = 1;
                                $(".redline_indication").each(function() {
                                  if(i == 1)
                                  {
                                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                    {
                                      $(this).parents(".allocated_tr").show();
                                      $(this).parents(".allocated_tr").next().show();
                                      i++;
                                    }
                                  }
                                });
                                $(".table_layout").show();
                                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                                
                                var j = 1;
                                $(".redline_indication_layout").each(function() {
                                  if(j == 1)
                                  {
                                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                    {
                                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                                      j++;
                                    }
                                  }
                                });
                              }
                              else{
                                $(".redline_indication").parents(".allocated_tr").show();
                                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }

                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "1"){
                              if(layout == "1")
                              {
                                $(".allocated_tr:first").show();
                                $(".allocated_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_allocated_tr").show();
                                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                              }
                              else{
                                $(".allocated_tr").show();
                                $(".allocated_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
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

                            if(layout == "0")
                            {
                                if(taskidval != "")
                                {
                                  //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                                }
                            }
                            else{
                              $("#"+taskidval).show();
                              $("#"+taskidval).next().show();
                            
                              var hidden_tr = taskidval.substr(8);
                              $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                            }

                            if(layout == "1")
                            {
                              $(".open_layout_div").addClass("open_layout_div_change");
                              var open_tasks_height = $(".open_layout_div").height();
                              var margintop = parseInt(open_tasks_height);
                              $(".open_layout_div").css("position","fixed");
                              $(".open_layout_div").css("height","312px");
                              if(open_tasks_height > 312)
                              {
                                $(".open_layout_div").css("overflow-y","scroll");
                              }
                              if(open_tasks_height < 50)
                              {
                                $(".table_layout").css("margin-top","20px");
                              }
                                else{
                                  $(".table_layout").css("margin-top","233px");
                                }
                            }
                            else{
                              $(".open_layout_div").removeClass("open_layout_div_change");
                              $(".open_layout_div").css("position","unset");
                              $(".open_layout_div").css("height","auto");
                              $(".open_layout_div").css("overflow-y","unset");
                                $(".table_layout").css("margin-top","0px");
                            }
                            $("body").removeClass("loading");
                          }
                        })
                      }
                    }
                  });
                }
              }
            })
          },1000);
      }
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
          $(".redlight_indication_"+task_id).hide();
          $(".redlight_indication_layout_"+task_id).hide();
          $(".redlight_indication_layout_"+task_id).removeClass('redline_indication_layout');
          $(".redlight_indication_"+task_id).removeClass('redline_indication');
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
    $("#hidden_task_id_author").val(author);
    if($(e.target).hasClass('auto_close_task_complete'))
    {
      $("#hidden_task_id_auto_close").val("1");
    }
    else{
      $("#hidden_task_id_auto_close").val("0");
    }
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
    $("#hidden_task_id_author").val(author);
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
    //$("body").addClass("loading");

    var task_id = $("#hidden_task_id_allocation").val();
    var auto_close = $("#hidden_task_id_auto_close").val();
    var new_allocation = $(".new_allocation").val();
    var author = $("#hidden_task_id_author").val();
    var selected_user = $(".select_user_home").val();

    if(auto_close == "1")
    {
      if(selected_user != author)
      {
        if(author == new_allocation)
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'" class="common_black_button yes_allocate_now">Yes</a><a href="javascript:" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'" class="common_black_button no_allocate_now">No</a></p>',fixed:true,width:"800px"});
            $("body").removeClass("loading");
            return false;
        }
      }
    }

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }

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
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {
                    
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find(".2bill_image").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
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

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","233px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
        }
      })
    }
  }
  if($(e.target).hasClass('yes_allocate_now'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-task");
    var new_allocation = $(e.target).attr("data-new");
    var author = $(e.target).attr("data-author");

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }

    if(new_allocation == "")
    {
      alert("Please choose the user to allocate the task.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
        type:"post",
        data:{task_id:task_id,new_allocation:new_allocation,type:"1"},
        dataType:"json",
        success:function(result)
        {
          var htmlval = $("#allocation_history_div_"+task_id).html();
          $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
          var htmlval2 = $("#history_body").find("tbody").html();
          $("#history_body").find("tbody").html(result['trval']+htmlval2);
          $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {
                    
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find(".2bill_image").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
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

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","233px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
          $.colorbox.close();
        }
      })
    }
  }
  if($(e.target).hasClass('no_allocate_now'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-task");
    var new_allocation = $(e.target).attr("data-new");
    var author = $(e.target).attr("data-author");

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }

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
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {

                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find(".2bill_image").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
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

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","233px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
          $.colorbox.close();
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
    $("body").addClass("loading");
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

          $("#layout_due_date_task_"+task_id).html(result['new_date']);
          $("#layout_due_date_task_"+task_id).css("color",result['color']);


          $(".due_date_modal").modal("hide");
          $("body").removeClass("loading");
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
  //  var href = $(e.target).attr("data-element");
  // var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
  // if (printWin == null || typeof(printWin)=='undefined')
  // {
  //  alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
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
    $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
    $(".author_email").val("<?php echo Session::get('taskmanager_user_email'); ?>");
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
      data:{fileid:"0"},
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
      $(".active_client_list_tm").hide();
    }
    else{
      $(".client_group").show();
      $(".client_search_class").prop("required",true);
      $(".internal_tasks_group").hide();
      $(".infiles_link").show();
      $(".active_client_list_tm").show();
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
    var contents = $(e.target).parent().find('.notepad_contents_task').val();
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
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_add').toggle();
  }
  if($(e.target).hasClass('notepad_contents_progress'))
  {
    $(e.target).parents('.notepad_div_progress_notes').toggle();
  }
  if($(e.target).hasClass('notepad_user'))
  {
    $(e.target).parents('.notepad_div_progress_notes').show();
  }
  if($(e.target).hasClass('notepad_contents_completion'))
  {
    $(e.target).parents('.notepad_div_completion_notes').toggle();
  }
  if($(e.target).hasClass('notepad_submit_add'))
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
      data:{fileid:"0"},
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
})
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
  if($(e.target).hasClass('quick_task_search_btn')) {
    var taskid = $(".quick_task_search").val();
    if(taskid == "") {
      alert("Please enter the Task ID");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/quick_task_view'); ?>",
        type:"post",
        data:{taskid:taskid},
        success:function(result) {
          if(result == 0) {
            alert("Entered TaskID is invalid.")
          }
          else{
            window.open('<?php echo URL::to('user/view_taskmanager_task'); ?>/'+result, '_blank');
          }
        }
      })
    }
  }
  $.ajax({ 
      url:"<?php echo URL::to('user/get_task_redline_notification'); ?>",
      type:"post",
      success:function(result)
      {
        var ids = result.split(",");
        $.each(ids, function(index,value) {
          $(".redlight_indication_"+value).show();
          $(".redlight_indication_layout_"+value).show();

          $(".redlight_indication_"+value).addClass('redline_indication');
          $(".redlight_indication_layout_"+value).addClass('redline_indication_layout');
        })
      }
  })
});
$(window).change(function(e) {
  if($(e.target).hasClass('select_view'))
  {
    var view = $(e.target).val();
    var layout = $("#hidden_compressed_layout").val();
    $(".tasks_tr").hide();
    $(".tasks_tr").next().hide();
    $(".hidden_tasks_tr").hide();
    $(".table_layout").find(".hidden_tasks_tr").find("td").css("background","#fff");
    if(view == "3")
    {
      $("#open_task_count").hide();
      $("#redline_task_count").hide();
      $("#authored_task_count").show();
      if(layout == "1")
      {
        $(".author_tr:first").show();
        $(".author_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_author_tr").show();
        $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
      }
      else{
        $(".author_tr").show();
        $(".author_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_tasks_tr").hide();
      }
      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "2")
    {
      $("#open_task_count").hide();
      $("#redline_task_count").show();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        var i = 1;
        $(".redline_indication").each(function() {
          if(i == 1)
          {
            if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
            {
              $(this).parents(".allocated_tr").show();
              $(this).parents(".allocated_tr").next().show();
              i++;
            }
          }
        });
        $(".table_layout").show();
        $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
        
        var j = 1;
        $(".redline_indication_layout").each(function() {
          if(j == 1)
          {
            if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
            {
              $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
              j++;
            }
          }
        });
      }
      else{
        $(".redline_indication").parents(".allocated_tr").show();
        $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_allocated_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);

    }
    else if(view == "1")
    {
      $("#open_task_count").show();
      $("#redline_task_count").hide();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        $(".allocated_tr:first").show();
        $(".allocated_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_allocated_tr").show();
        $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
      }
      else{
        $(".allocated_tr").show();
        $(".allocated_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_tasks_tr").hide();
      }
      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }

    if(layout == "1")
    {
      $(".open_layout_div").addClass("open_layout_div_change");
      var open_tasks_height = $(".open_layout_div").height();
      var margintop = parseInt(open_tasks_height);
      $(".open_layout_div").css("position","fixed");
      $(".open_layout_div").css("height","312px");
      if(open_tasks_height > 312)
      {
        $(".open_layout_div").css("overflow-y","scroll");
      }
      if(open_tasks_height < 50)
      {
        $(".table_layout").css("margin-top","20px");
      }
        else{
          $(".table_layout").css("margin-top","233px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
      $(".open_layout_div").css("position","unset");
      $(".open_layout_div").css("height","auto");
      $(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
  }
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

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Taskmanager Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Taskmanager Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
          //$(".add_progress_attachments").html("");
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
          //$(".add_progress_attachments").html("");
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            //$.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
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

<script type="text/javascript">


/*$.ajaxSetup({async:false});
$('#project_form').validate({
    rules: {
        project_name : {required: true},   
        select_author : {required: true},        
    },
    messages: {
        project_name : {required : "Please enter Project Name"},
        select_author : {required : "Please select Author"},        
    },
});*/

/*$(".project_add_button").submit(function(){
  alert("Submitted");
});*/


</script>
@stop
