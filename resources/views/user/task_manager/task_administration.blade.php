@extends('userheader')
@section('content')

<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
#table_administration_wrapper{ width:98%; margin-top: 25px; }
.modal_load {
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

.modal_load_export {
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
body.loading_export {
    overflow: hidden;   
}
body.loading_export .modal_load_export {
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
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> "> 
<div class="modal fade edit_task_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:20%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Edit/Modify Task</h4>
          </div>
          <div class="modal-body">  
            <div class="form-group client_group"> 
                <div class="form-title">Author</div>
                <select name="select_user" class="form-control select_user_author" required>
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
            </div>
            <div class="form-group start_group">
                <div class="form-title">Allocate To</div>
                <select name="allocate_user" class="form-control allocate_user_add">
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
            </div>
            <div class="form-group start_group">
                <div class="form-title">Task Subject</div>
                <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
            </div>
            <div class="form-group start_group">
                <div class="form-title">Project</div>
                <select name="project_id" class="form-control project_id_add">
                  <option value="">Select Project</option>        
                    <?php
                    $projects = DB::table('projects')->get();
                    if(($projects)){
                      foreach ($projects as $project) {
                    ?>
                      <option value="<?php echo $project->project_id ?>"><?php echo $project->project_name; ?></option>
                    <?php
                      }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12 internal_tasks_grp internal_tasks_group" style="padding:0px">
              <label style="margin-top:5px">Select Task:</label>
            </div>
            <div class="col-md-12 internal_tasks_grp internal_tasks_group_change" style="padding:0px;margin-bottom: 20px">
              <div class="dropdown" style="width: 100%">
                <a class="btn btn-default dropdown-toggle tasks_drop_change" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                  <span class="task-choose_internal_change">Select Task</span>  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu internal_task_details_change" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                    <?php
                    $tasksval = DB::table('time_task')->where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
                    if(($tasksval)){
                      foreach ($tasksval as $single_task) {
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
            <div class="col-md-6 form-group start_group retain_specifics_div" style="padding:0px">
                <label>Retain Specifics:</label>
                <div class="form-title">
                  <label class="switch">
                    <input type="checkbox" name="retain_specifics" class="retain_specifics" id="retain_specifics" value="1">
                    <span class="slider round"></span>
                  </label>
                  <input type="hidden" id="hidden_retain_specifics" value="0">
                </div>
            </div>
            <div class="col-md-6 form-group start_group retain_files_div" style="padding:0px">
                <label>Retain Files:</label>
                <div class="form-title">
                  <label class="switch">
                    <input type="checkbox" name="retain_files" class="retain_files" id="retain_files" value="1">
                    <span class="slider round"></span>
                  </label>
                  <input type="hidden" id="hidden_retain_files" value="0">
                </div>
            </div>
            <div class="form-group start_group recurring_task_div">
                <label>Recurring Task:</label>
                <div class="form-title">
                  <label class="switch">
                    <input type="checkbox" name="recurring_task" class="recurring_task" id="recurring_task" value="1">
                    <span class="slider round"></span>
                  </label>
                  <input type="hidden" id="hidden_recurring_task" value="0">
                </div>
                <br/><br/>
                <div class="accept_recurring_div">
                  <p>This Task is repeated:</p>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                    <label for="recurring_checkbox1" style="margin-left: 0px;">Monthly</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                    <label for="recurring_checkbox2" style="margin-left: 0px;">Weekly</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                    <label for="recurring_checkbox3" style="margin-left: 0px;">Daily</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                    <label for="recurring_checkbox4" style="margin-left: 0px;">Specific Number of Days</label>
                    <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                  </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" id="hidden_task_id_copy_task" name="hidden_task_id_copy_task" value="">
            <input type="button" class="common_black_button" id="update_task_details" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade integrity_check_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:85%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Taskmanager Integrity Check</h4>
          </div>
          <div class="modal-body" style="clear:both">  
            <input type="button" class="common_black_button export_integrity" value="Export CSV" style="float:right">
            <input type="hidden" name="hidden_page_no_integrity" id="hidden_page_no_integrity" value="0">
            <table class="table own_table_white" style="float:left;margin-top:20px">
              <thead>
                  <th>Task ID</th>
                  <th >Task Name</th>
                  <th>Subject</th>
                  <th>Task FIles</th>
                  <th>Status</th>
                  <th>Progress FIles</th>
                  <th>Status</th>
                  <th>Completion Files</th>
                  <th>Status</th>
              </thead>
              <tbody id="integrity_check_tbody">
              </tbody>
            </table>
          </div>
          <div class="modal-footer" style="clear:both">  
           
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div style="width:100%;position: fixed; background: #f5f5f5; z-index: 99; top: 83px;">
  <div class="page_title" style="z-index:999">
    <h4 class="col-lg-12 padding_00 new_main_title">Task Administration</h4>
    <div class="col-lg-3 padding_00">
      <label class="col-md-3 padding_00" style="line-height: 35px; width: 116px">Search By Task:</label>
            <div class="col-md-9">
                <input type="text" name="search_by_task" class="form-control search_by_task" id="search_by_task" value="" placeholder="Search by Task ID or Task Subject"> 
            </div>
    </div>
    <div class="col-lg-7 padding_00" style="text-align:center;font-size:20px; width: 1250px">
      

      <a href="javascript:" class="load_closed_jobs common_black_button" style="float:left;">Load Closed Tasks</a>
      <a href="javascript:" class="integrity_check common_black_button" style="float:left;">Integrity Check</a>
      <input type="hidden" name="hidden_load_closed" id="hidden_load_closed" value="0">
    </div>
  </div>
  <div id="fixed-header" style="width:100%;float:left; margin-top: 28px;">
  <?php
  if(Session::has('message')) { ?>
      <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
  <?php }
  if(Session::has('error')) { ?>
      <p class="alert alert-danger"><?php echo Session::get('error'); ?></p>
  <?php }
  ?>
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
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/taskmanager_search'); ?>" class="nav-link">Task Search</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_analysis'); ?>" class="nav-link" id="profile-tab">Client Task Analysis</a>
        </li>
        <li class="nav-item waves-effect waves-light active" style="width:15%;text-align: center">
          <a href="#home" class="nav-link" id="profile-tab" data-toggle="tab" role="tab" aria-controls="profile" aria-selected="false" style="border-bottom: 2px solid #fff">
            Task Administration</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_overview'); ?>" class="nav-link" id="profile-tab">Task Overview</a>
        </li>
      </ul>
  </div>
    <div style="width: 100%; float: left;margin-top:170px; background: #fff">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
            <table class="table own_table_white" id="table_administration" style="width:100%; background: #fff">
                <thead>
                  <tr>
                    <th>Task ID</th>
                    <th>Client/Task Name</th>
                    <th>Author</th>
                    <th>Roll Forward To</th>
                    <th style="width:7%">Parked</th>
                    <th>Retain Orignal Specifics</th>
                    <th>Retain Original Files</th>
                    <th>Subject</th>
                    <th>Project</th>
                    <th>Repeat</th>
                    <th>Repeat</th>
                    <th>Due Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tbody_show_tasks">
                  <?php
                  $outputtask = '';
                  if(($taskslist))
                  {
                    foreach($taskslist as $task)
                    {
                      $two_bill_icon = '';
                      if($task->two_bill == "1")
                      {
                        $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                      }
                      if($task->client_id == "")
                      {
                        $title_lable = 'Task Name:';
                        $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
                        if(($task_details))
                        {
                          $title = $task_details->task_name;
                          $tasktitle = $task_details->task_name;
                          $internaltask = 'yes';
                        }
                        else{
                          $title = '';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                      }
                      else{
                        $title_lable = 'Client:';
                        $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                        if(($client_details))
                        {
                          $title = $client_details->company.' ('.$task->client_id.')';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                        else{
                          $title = '';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                      }

                      if($task->allocated_to != 0)
                      {
                        $allocated_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$task->allocated_to)->first();
                        $allocated_to = $allocated_details->lastname.' '.$allocated_details->firstname;
                      }
                      else{
                        $allocated_to = '-';
                      }

                      if($task->author != 0)
                      {
                        $author_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
                        $author_to = $author_details->lastname.' '.$author_details->firstname;
                      }
                      else{
                        $author_to = '-';
                      }

                      if($task->status == 1) { $color = 'color:#f00'; $tr_status= 'tr_closed'; } 
                      else { $color = ''; $tr_status= ''; }

                      if($task->recurring_days == "30"){ $recurring_days = 'Monthly'; }
                      elseif($task->recurring_days == "7"){ $recurring_days = 'Weekly'; }
                      elseif($task->recurring_days == "1"){ $recurring_days = 'Daily'; }
                      else{ $recurring_days = 'Specific'; }

                      if($task->recurring_task == "0"){ $recurring_task = 'NO'; }
                      else{ $recurring_task = 'YES'; }

                      if($task->retain_specifics == "0"){ $retain_specifics = '-'; }
                      else{ $retain_specifics = '<i class="fa fa-check"></i>'; }

                      if($task->retain_files == "0"){ $retain_files = '-'; }
                      else{ $retain_files = '<i class="fa fa-check"></i>'; }

                      $project_name = '';
                      if($task->project_id != 0)
                      {
                        $details = DB::table('projects')->where('project_id',$task->project_id)->first();
                        $project_name = $details->project_name;
                      }

                      $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$tr_status.'">
                        <td class="taskid_td" style="'.$color.'">'.$task->taskid.'</td>
                        <td class="taskid_td task_name_val" style="'.$color.'">'.$title.' '.$two_bill_icon.'</td>
                        <td class="author_td" style="'.$color.'">'.$author_to.'</td>
                        <td class="allocated_td" style="'.$color.'">'.$allocated_to.'</td>
                        <td class="" style="'.$color.';text-align:center">';
                          if($task->status == 2)
                          {
                            $outputtask.='<i class="fa fa-pause" aria-hidden="true"></i><br/>'.date('d-M-Y',strtotime($task->park_date)).'';
                          }
                          else{
                            $outputtask.='-';
                          }
                        $outputtask.='</td>
                        <td class="retain_spec_td" style="'.$color.'">'.$retain_specifics.'</td>
                        <td class="retain_files_td" style="'.$color.'">'.$retain_files.'</td>
                        <td class="subject_td" style="'.$color.'">'.$task->subject.'</td>

                        <td class="project_td" style="'.$color.'">'.$project_name.'</td>

                        <td class="recurring_days_td" style="'.$color.'">'.$recurring_days.'</td>
                        <td class="recurring_task_td" style="'.$color.'">'.$recurring_task.'</td>
                        <td class="due_date_td" style="'.$color.'"><spam style="display:none">'.strtotime($task->due_date).'</spam>'.date('d-M-Y', strtotime($task->due_date)).'</td>
                        <td style="'.$color.'">
                          <a href="javascript:" class="fa fa-download download_pdf_task" data-element="'.$task->id.'" title="Download PDF"></a>
                          <a href="javascript:" class="fa fa-edit edit_task edit_task_'.$task->id.'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'" data-specifics="'.$task->retain_specifics.'" data-files="'.$task->retain_files.'" data-recurring="'.$task->recurring_task.'" data-taskname="'.$tasktitle.'" data-tasktype="'.$task->task_type.'" data-internal="'.$internaltask.'" data-projectid="'.$task->project_id.'" title="EDIT TASK"></a>
                        </td>
                      </tr>';
                    }
                  }
                  else{
                    $outputtask.='<td colspan="10" style="text-align:center">No Tasks Found</td>';
                  }
                  echo $outputtask;
                  ?>
                </tbody>
            </table>
            <?php
            $total_tasks = DB::table('taskmanager')->where('practice_code', Session::get('user_practice_code'))->get();
            if(count($total_tasks) > 500)
            {
              echo '<a href="javascript:" class="show_more_tasks common_black_button">Show More</a> &nbsp;&nbsp;<a href="javascript:" class="load_all_tasks common_black_button">Load All Tasks</a>';
            }
            ?>
            <input type="hidden" id="hidden_tasks_count" name="hidden_tasks_count" value="<?php echo count($total_tasks); ?>">
        </div>
      </div>
    </div>
</div>
<input type="hidden" id="hidden_page_no" value="1">
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the tasks are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Tasks Loaded: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<div class="modal_load_export" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the tasks are Exported.</p>
  <p style="font-size:18px;font-weight: 600;">Exporting Tasks: <span id="apply_first_export"></span> of <span id="apply_last_export"></span></p>
</div>
<script>
$(function(){
    $('#table_administration').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        aaSorting: [],
    });
});
$(document).ready(function() {
  $("#hidden_page_no").val("1");
  $(".creation_date_search_class").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
  $(".due_date_search_class").datetimepicker({
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
})
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    if($(e.target).hasClass('search_by_task'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val,that);   
    }
});
function doneTyping(value,targetval)
{
  var vv = value.toLowerCase();

  $(".tr_task").hide();
  $(".taskid_td").each(function() {
    var task_id_td = $(this).html();
    task_id_td = task_id_td.toLowerCase();
    var n = task_id_td.indexOf(vv);
    if(n >= 0)
    {
      $(this).parents(".tr_task").show();
    }
  });

  $(".subject_td").each(function() {
    var subject_td = $(this).html();
    subject_td = subject_td.toLowerCase();
    var n_subject = subject_td.indexOf(vv);
    if(n_subject >= 0)
    {
      $(this).parents(".tr_task").show();
    }
  });
}
function load_all_tasks(count)
{
  var page_no = $("#hidden_page_no").val();
  var tasks_count = $("#hidden_tasks_count").val();
  var next_count = count + 1;
  var total_round = parseInt(tasks_count) / 500;
  total_round = Math.round(total_round);
  var prev= parseInt(page_no) - 1;
  var offset = parseInt(prev) * 500;
  $("#apply_first").html(offset);
  if(next_count <= total_round)
  {
    $.ajax({
      url:"<?php echo URL::to('user/show_more_tasks'); ?>",
      type:"post",
      data:{page_no:page_no},
      success: function(result)
      {
        $("#tbody_show_tasks").append(result);
        var pageno = parseInt(page_no) + 1;
        $("#hidden_page_no").val(pageno);
        load_all_tasks(next_count);
      }
    });
  }
  else{
    $('#table_administration').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        aaSorting: [],
    });
    $(".show_more_tasks").hide();
    $(".load_all_tasks").hide();
    $("body").removeClass("loading_apply");
  }
}
function load_all_tasks_integrity(count)
{
  var page_no = $("#hidden_page_no_integrity").val();
  var tasks_count = $("#hidden_tasks_count").val();
  var next_count = count + 1;
  var total_round = parseInt(tasks_count) / 100;
  total_round = Math.round(total_round);
  var prev= parseInt(page_no);
  var offset = parseInt(prev) * 100;
  $("#apply_first").html(offset);
  if(next_count <= total_round)
  {
    $.ajax({
      url:"<?php echo URL::to('user/integrity_check_all_tasks'); ?>",
      type:"post",
      data:{page_no:page_no},
      success: function(result)
      {
        $("#integrity_check_tbody").append(result);
        var pageno = parseInt(page_no) + 1;
        $("#hidden_page_no_integrity").val(pageno);
        load_all_tasks_integrity(next_count);
      }
    });
  }
  else{
    $('[data-toggle="tooltip"]').tooltip();
    $("#hidden_page_no_integrity").val("0");
    $("#apply_first").html("0");
    $("body").removeClass("loading_apply");
  }
}
function export_all_tasks_integrity(count,filename)
{
  var page_no = $("#hidden_page_no_integrity").val();
  var tasks_count = $("#hidden_tasks_count").val();
  var next_count = count + 1;
  var total_round = parseInt(tasks_count) / 100;
  total_round = Math.round(total_round);
  var prev= parseInt(page_no);
  var offset = parseInt(prev) * 100;
  $("#apply_first_export").html(offset);
  if(next_count <= total_round)
  {
    $.ajax({
      url:"<?php echo URL::to('user/export_integrity_tasks'); ?>",
      type:"post",
      data:{page_no:page_no,filename:filename},
      success: function(result)
      {
        var pageno = parseInt(page_no) + 1;
        $("#hidden_page_no_integrity").val(pageno);
        export_all_tasks_integrity(next_count,filename);
      }
    });
  }
  else{
    $("#hidden_page_no_integrity").val("0");
    $("#apply_first_export").html("0");

    SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+filename,filename);
    $("body").removeClass("loading_export");
  }
}
$(window).click(function(e) {
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
  if($(e.target).hasClass("show_more_tasks"))
  {
    $("body").addClass("loading");
    $('#table_administration').DataTable().destroy();
    var page_no = $("#hidden_page_no").val();
    var tasks_count = $("#hidden_tasks_count").val();

    $.ajax({
      url:"<?php echo URL::to('user/show_more_tasks'); ?>",
      type:"post",
      data:{page_no:page_no},
      success: function(result)
      {
        if(result == "")
        {
          $(".show_more_tasks").hide();
          $(".load_all_tasks").hide();
        }
        $("#tbody_show_tasks").append(result);
        var pageno = parseInt(page_no) + 1;
        $("#hidden_page_no").val(pageno);

        $('#table_administration').DataTable({
            fixedHeader: {
              headerOffset: 75
            },
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            aaSorting: [],
        });

        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('load_all_tasks'))
  {
    $("body").addClass("loading_apply");
    $('#table_administration').DataTable().destroy();
    var page_no = $("#hidden_page_no").val();
    var tasks_count = $("#hidden_tasks_count").val();
    $("#apply_last").html(tasks_count);

    var total_round = parseInt(tasks_count) / 500;
    total_round = Math.round(total_round);

    var count = 1;
    var prev= parseInt(page_no) - 1;
    var offset = parseInt(prev) * 500;
    $("apply_first").html(offset);
    if(count <= total_round)
    {
      $.ajax({
        url:"<?php echo URL::to('user/show_more_tasks'); ?>",
        type:"post",
        data:{page_no:page_no},
        success: function(result)
        {
          $("#tbody_show_tasks").append(result);
          var pageno = parseInt(page_no) + 1;
          $("#hidden_page_no").val(pageno);
          load_all_tasks(count);
        }
      });
    }
    else{
      $('#table_administration').DataTable({
          fixedHeader: {
            headerOffset: 75
          },
          autoWidth: true,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          aaSorting: [],
      });
      $(".show_more_tasks").hide();
      $(".load_all_tasks").hide();
      $("body").removeClass("loading_apply");
    }
  }
  if($(e.target).hasClass('integrity_check'))
  {
    $("body").addClass("loading_apply");
    var page_no = $("#hidden_page_no_integrity").val();
    var tasks_count = $("#hidden_tasks_count").val();
    $("#apply_last").html(tasks_count);

    var total_round = parseInt(tasks_count) / 100;
    total_round = Math.round(total_round);

    var count = 1;
    var prev= parseInt(page_no);

    var offset = parseInt(prev) * 100;
    $("apply_first").html(offset);
    $("#integrity_check_tbody").html("");
    $(".integrity_check_modal").modal("show");
    if(count <= total_round)
    {
      $.ajax({
        url:"<?php echo URL::to('user/integrity_check_all_tasks'); ?>",
        type:"post",
        data:{page_no:page_no},
        success: function(result)
        {
          $("#integrity_check_tbody").html(result);
          var pageno = parseInt(page_no) + 1;
          $("#hidden_page_no_integrity").val(pageno);
          load_all_tasks_integrity(count);
        }
      });
    }
    else{
      $("#hidden_page_no_integrity").val("0");
      $("#apply_first").html("0");
      $('[data-toggle="tooltip"]').tooltip()
      $("body").removeClass("loading_apply");
    }
  }
  if($(e.target).hasClass('export_integrity'))
  {
    $("body").addClass("loading_export");
    var page_no = $("#hidden_page_no_integrity").val();
    var tasks_count = $("#hidden_tasks_count").val();
    $("#apply_last_export").html(tasks_count);

    var total_round = parseInt(tasks_count) / 100;
    total_round = Math.round(total_round);

    var count = 1;
    var prev= parseInt(page_no);

    var offset = parseInt(prev) * 100;
    $("apply_first_export").html(offset);
    if(count <= total_round)
    {
      $.ajax({
        url:"<?php echo URL::to('user/export_integrity_tasks'); ?>",
        type:"post",
        data:{page_no:page_no},
        success: function(result)
        {
          var pageno = parseInt(page_no) + 1;
          $("#hidden_page_no_integrity").val(pageno);
          export_all_tasks_integrity(count,result);
        }
      });
    }
    else{
      $("#hidden_page_no_integrity").val("0");
      $("#apply_first_export").html("0");
      $("body").removeClass("loading_export");
    }
  }
  
  if($(e.target).hasClass('load_closed_jobs'))
  {
    var value = $("#hidden_load_closed").val();
    if(value == "0")
    {
      $("#tbody_show_tasks").addClass("show_closed");
      $(e.target).text("Hide Closed Tasks");
      $("#hidden_load_closed").val("1");
    }
    else{
      $("#tbody_show_tasks").removeClass("show_closed");
      $(e.target).text("Load Closed Tasks");
      $("#hidden_load_closed").val("0");
    }
  }  
  if($(e.target).parents(".switch").length > 0)
  {
    if($(e.target).parents(".recurring_task_div").length > 0)
    {
      if($(e.target).parents(".recurring_task_div").find(".recurring_task").is(":checked"))
      {
        $(e.target).parents(".recurring_task_div").find("#hidden_recurring_task").val("1");
        $(".accept_recurring_div").show();
        $("#recurring_checkbox1").prop("checked",true);
      }
      else{
        $(e.target).parents(".recurring_task_div").find("#hidden_recurring_task").val("0");
        $(".accept_recurring_div").hide();
        $(".recurring_checkbox").prop("checked",false);
      }
    }
    if($(e.target).parents(".retain_specifics_div").length > 0)
    {
      if($(e.target).parents(".retain_specifics_div").find(".retain_specifics").is(":checked"))
      {
        $(e.target).parents(".retain_specifics_div").find("#hidden_retain_specifics").val("1");
        
      }
      else{
        $(e.target).parents(".retain_specifics_div").find("#hidden_retain_specifics").val("0");
        
      }
    }
    if($(e.target).parents(".retain_files_div").length > 0)
    {
      if($(e.target).parents(".retain_files_div").find(".retain_files").is(":checked"))
      {
        $(e.target).parents(".retain_files_div").find("#hidden_retain_files").val("1");
      }
      else{
        $(e.target).parents(".retain_files_div").find("#hidden_retain_files").val("0");
      }
    }
  }
  if($(e.target).hasClass('edit_task'))
  {
    var task_id = $(e.target).attr("data-element");
    var author = $(e.target).attr("data-author");
    var allocated = $(e.target).attr("data-allocated");
    var specifics = $(e.target).attr("data-specifics");
    var files = $(e.target).attr("data-files");
    var recurring = $(e.target).attr("data-recurring");

    var internal = $(e.target).attr("data-internal");
    var taskname = $(e.target).attr("data-taskname");
    var tasktype = $(e.target).attr("data-tasktype");

    var projectid = $(e.target).attr("data-projectid");

    $("#hidden_task_id_copy_task").val(task_id);
    $(".select_user_author").val(author);
    $(".allocate_user_add").val(allocated);

    $(".project_id_add").val(projectid);

    if(specifics == "1")
    {
      $("#retain_specifics").prop("checked",true);
    }
    else{
      $("#retain_specifics").prop("checked",false);
    }

    if(files == "1")
    {
      $("#retain_files").prop("checked",true);
    }
    else{
      $("#retain_files").prop("checked",false);
    }

    if(recurring > "0")
    {
      $("#recurring_task").prop("checked",true);
      $("#recurring_checkbox"+recurring).prop("checked",true);
      $(".accept_recurring_div").show();
    }
    else{
      $("#recurring_task").prop("checked",false);
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }

    if(internal == "yes")
    {
      $(".internal_tasks_grp").show();
      $(".task-choose_internal_change").html(taskname);
      $("#idtask_change").val(tasktype);
    }
    else{
      $(".internal_tasks_grp").hide();
      $(".task-choose_internal_change").html("Select Task");
      $("#idtask_change").val("0");
    }

    $.ajax({
      url:"<?php echo URL::to('user/edit_task_details_admin_screen'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $(".subject_class").val(result);
        $(".edit_task_modal").modal("show");
      }
    });
  }
  if($(e.target).hasClass('tasks_li_internal_change'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_change").val(taskid);
    $(".task-choose_internal_change:first-child").text($(e.target).text());
  }
  if(e.target.id == "update_task_details")
  {
    var task_id = $("#hidden_task_id_copy_task").val();
    var author = $(".select_user_author").val();
    var allocated = $(".allocate_user_add").val();
    var specifics = $("#hidden_retain_specifics").val();
    var files = $("#hidden_retain_files").val();
    var recurring = $("#hidden_recurring_task").val();
    var days = $(".recurring_checkbox:checked").val();
    var specific_recurring = $(".specific_recurring").val();
    var task_type = $("#idtask_change").val();
    var subject = $(".subject_class").val();
    var project_id = $(".project_id_add").val();

    if(author == "")
    {
      alert("Please Select the Author");
    }
    else if(days == "4" && specific_recurring == "")
    {
      alert("Please set number of days for recurring");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/update_taskmanager_details'); ?>",
        type:"post",
        data:{task_id:task_id,author:author,allocated:allocated,specifics:specifics,files:files,recurring:recurring,days:days,specific_recurring:specific_recurring,task_type:task_type,subject:subject,project_id:project_id},
        dataType:"json",
        success:function(result)
        {
          $("#tr_task_"+task_id).find(".author_td").html(result['author']);
          $("#tr_task_"+task_id).find(".allocated_td").html(result['allocated']);
          $("#tr_task_"+task_id).find(".retain_spec_td").html(result['retain_spec']);
          $("#tr_task_"+task_id).find(".retain_files_td").html(result['retain_files']);
          $("#tr_task_"+task_id).find(".recurring_days_td").html(result['recurring_days']);
          $("#tr_task_"+task_id).find(".recurring_task_td").html(result['recurring_task']);
          $("#tr_task_"+task_id).find(".subject_td").html(result['subject']);
          $("#tr_task_"+task_id).find(".project_td").html(result['project']);
          if(result['task_type'] == "0")
          {

          }
          else if(result['task_type'] == "")
          {

          }
          else{
            $("#tr_task_"+task_id).find(".task_name_val").html(result['task_name_val']);
            $(".edit_task_"+task_id).attr("data-taskname",result['task_name_val']);
            $(".edit_task_"+task_id).attr("data-tasktype",result['task_type']);
          }

          $(".edit_task_"+task_id).attr("data-projectid",project_id);
          $(".edit_task_modal").modal("hide");
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Task Updated successfully.</p>',fixed:true,width:"800px"});
        }
      })
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
          success: function(result)
          {
            $("#task_body_open").html(result);
            $("body").removeClass("loading");
          }
        })
      }
    },2000);
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
        $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
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
          $(".task_specifics").prop("required",true);
          $(".task_specifics_copy").hide();
          $(".task_specifics").val(result['task_specifics']);
          $(".task_specifics_copy_val").html("");
          $("#hidden_task_specifics").val(result['task_specifics']);
        }
        else{
          $(".task_specifics_add").hide();
          $(".task_specifics").prop("required",false);
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
  if($(e.target).hasClass('add_task_specifics'))
  {
    var comments = $("#new_comment").val();
    var task_id = $("#hidden_task_id_task_specifics").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_comment_specifics'); ?>",
      type:"post",
      data:{task_id:task_id,comments:comments},
      success:function(result)
      {
        $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify">'+result+'</strong>');
        $("#new_comment").val("");
      }
    })
  }
  if($(e.target).hasClass('link_to_task_specifics'))
  {
    var task_id = $(e.target).attr("data-element");
    $(".task_specifics_modal").modal("show");
    $.ajax({
      url:"<?php echo URL::to('user/show_existing_comments'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#hidden_task_id_task_specifics").val(task_id);
        $("#existing_comments").html(result);
      }
    })
  }
  if($(e.target).hasClass('edit_allocate_user'))
  {
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
    $(".allocation_modal").modal("show");

    $.ajax({
      url:"<?php echo URL::to('user/show_all_allocations'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#hidden_task_id_history").val(task_id);
        $("#history_body").html(result);
      }
    })
  }
  if(e.target.id == "allocate_now")
  {
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
        data:{task_id:task_id,new_allocation:new_allocation},
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
  if(e.target.id == "create_new_task")
  {
    $(".create_new_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
    $(".create_new_model").modal("show");
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

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".client_search_class").val("");
    $("#client_search").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("");
    $(".task_specifics_add").show();
    $(".task_specifics_copy").hide();
    $(".task_specifics").val("");
    $(".task_specifics").prop("required",true);
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
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_notepad_contents_progress'); ?>",
      type:"post",
      data:{contents:contents,task_id:task_id},
      dataType:"json",
      success: function(result)
      {
        $("#add_notepad_attachments_progress_div_"+task_id).append("<p><a href='"+result['download_url']+"' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
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
        $("#add_notepad_attachments_completion_div_"+task_id).append("<p><a href='"+result['download_url']+"' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
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
  else if($(e.target).hasClass('fa-plus'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
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
        window.location.reload();
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $("#add_files_attachments_progress_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".img_div").each(function() {
              $(this).hide();
            });
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $("#add_files_attachments_completion_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".img_div").each(function() {
              $(this).hide();
            });
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
</script>
@stop
