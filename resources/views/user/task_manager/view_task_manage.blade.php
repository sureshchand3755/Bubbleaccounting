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
  .popover{
    top:-29.25px !important;
  }
  .arrow{
    top:50% !important;
  }
  .taskmanager_rate{
    padding:0px !important;
  }
  body{
    background: white !important;
  }

.dmenu {
  position: absolute;
  top: -40px;
  left: 112px;
}
</style>

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
    width: 250px;
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
<div class="content_section">
	<div id="fixed-header" style="width:100%;position: fixed; background: white;">
	  <div class="page_title">
        <div class="row padding_00 new_main_title" style="padding-bottom: 6px">
            <h4 class="col-lg-12" style="font-size: 30px;font-weight: 600;font-family: 'Poppins', sans-serif;text-transform: uppercase;">Task Manager - <?php echo $task->taskid; ?>
            <span style="margin-left:40px; font-size:30px;" id="place_mui_icons"><?php echo $mmi_icons; ?></span> 
            </h4>
        </div>
	  	<div class="row"> 
          <div class="card mb-3" style="padding:10px;">
            <div class="row no-gutters">
                <div class="col-md-3" style="padding:8px; background-color:white; height:980px; overflow-x:hidden; overflow-y:auto;">
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Task ID:</label>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label"><?php echo $task->taskid; ?></label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Priority:</label>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label"><?php echo $user_ratings; ?></label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Task Subject:</label>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label"><?php echo $task->subject; ?></label>
                        </div>
                    </div>
                    <?php
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
                      ?>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Task Name / Client:</label>
                        <div class="col-sm-8">
                            <?php
                            $type='';
                            if($task->task_type==15){
                                $type='(Internal)';
                            }
                            ?>                            
                            <label class="col-sm-12 control-label"><?php echo $title.$type; ?></label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Date Created:</label>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label"><?php echo date('d-M-Y',strtotime($task->creation_date)); ?></label>
                        </div>
                    </div>
                    <?php
                    if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
                    else{ $disabled = ''; $disabled_icon = ''; }
                    ?>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Due Date:</label>
                        <div class="col-sm-8 <?php echo $disabled_icon; ?>">
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

                            if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	                        else{ $subject = $task->subject; }
                            ?>
                            <label class="col-sm-12 control-label" style="color:<?php echo $due_color; ?> !important;font-weight:800;" id="due_date_task_<?php echo $task->id; ?>"><?php echo date('d-M-Y', strtotime($task->due_date)); ?>
                              <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-value="<?php echo date('d-M-Y', strtotime($task->due_date)); ?>" data-duedate="<?php echo $task->due_date; ?>" data-color="<?php echo $due_color; ?>" class="fa fa-edit edit_due_date edit_due_date_<?php echo $task->id; ?> <?php echo $disabled; ?>" style="font-weight:800"></a>
                            </label>
                            
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Task Files:</label>
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
                        ?>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label"><?php echo $fileoutput; ?></label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Linked InFilessss:</label>
                        <div class="col-sm-8">
                        <?php
                        $fileoutput='';
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
                                    $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

                                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                                    </p>';
                                    $i++;
                                }
                            }
                        }
                        ?>
                            <label class="col-sm-12 control-label"><?php echo $fileoutput; ?></label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <label class="col-sm-4 control-label">Task Author:</label>
                        <?php 
                        $author = $author->lastname." ".$author->firstname;
                        ?>
                        <div class="col-sm-8">                            
                            <label class="col-sm-12 control-label"><?php echo $author; ?>
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
                            </label>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <?php
                        if($task->auto_close == 1)
                        {
                          $close_task = 'auto_close_task_complete';
                        }
                        else{
                          $close_task = '';
                        }
                        ?>
                        <label class="col-sm-4 control-label">Allocations:<br>
                          <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_<?php echo $task->id; ?> <?php echo $disabled; ?> <?php echo $close_task; ?>" title="Allocate User" style="font-weight:800"></a>
                          &nbsp;
                          <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-history show_task_allocation_history show_task_allocation_history_<?php echo $task->id; ?>" title="Allocation History" style="font-weight:800"></a>
                          &nbsp;
                          <a href="javascript:" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" title="Request Update" style="font-weight:800">
                            <img src="<?php echo URL::to('public/assets/images/request.png'); ?>" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" style="width:16px;">
                          </a>
                        </label>
                        <div class="col-sm-8">
                            <label class="col-sm-12 control-label" style="padding:0px !important;">
                              <div class="col-md-12" id="allocation_history_div_<?php echo $task->id?>"><?php echo $output; ?></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="padding:8px; background-color:white; height:980px; overflow-x:hidden; overflow-y:auto;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Existing Task Specific Comments:</h5>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:" class="common_black_button download_pdf_spec" style="float: right;">Download as PDF</a> 
                                <img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" class="2bill_image 2bill_image_comments" style="width:32px;margin-left:10px;float:right;margin-top: 4px;display:none" title="this is a 2Bill Task">
                            </div>
                        </div><br>
                        <div class="col-md-12" style="padding: 0px;">
                            <div class="existing_comments" id="existing_comments" style="width:100%;background: #c7c7c7;padding:10px;min-height:450px;height:300px;overflow-y: scroll;font-size: 16px">
                            <?php echo $task_specific; ?>
                            </div>
                        </div>

                        <label class="col-md-12" style="margin-top:15px;padding: 0px">New Comment:</label>
                        <div class="col-md-12" style="padding: 0px">
                            <textarea name="new_comment" class="form-control new_comment" id="editor_1" style="height:150px"></textarea>
                        </div>
                        <input type="hidden" name="hidden_task_id_task_specifics" id="hidden_task_id_task_specifics" value="<?php echo $task->id; ?>">
                        <input type="hidden" name="show_auto_close_msg" id="show_auto_close_msg" value="<?php echo $show_auto_close_msg; ?>">
                        
                        <div class="col-md-12" style="padding:10px;margin-top:10px">
                            <input type="button" class="common_black_button add_comment_allocate_to_btn" value="Add Comment and Allocate To" style="float: left;font-size:12px; margin-right:10px;">
                            <select name="add_comment_allocate_to" class="form-control add_comment_allocate_to" style="float: left;width:20%;font-size:12px">
                                <option value="">Select User</option>
                                <?php
                                if(($user)){
                                    foreach ($user as $ulist) {
                                ?>
                                    <option value="<?php echo $ulist->user_id ?>"><?php echo $ulist->lastname.'&nbsp;'.$ulist->firstname; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12" style="padding:10px;">
                            <input type="button" class="common_black_button add_task_specifics" id="add_task_specifics" value="Add Comment Now" style="font-size:12px">
                            <input type="button" class="common_black_button add_comment_and_allocate" id="add_comment_and_allocate" value="Add Comment and Allocate Back" style="font-size:12px">
                            <input type="button" class="common_black_button add_progress_files_from_task_specifics" id="add_progress_files_from_task_specifics" value="Add Progress Files" data-element="<?php echo $task->id; ?>" style="float: left;font-size:12px">
                            <spam class="progress_spam" style="font-weight:600;color:green;margin-top:10px"></spam>
                        </div>
                        <div class="col-md-12" style="float:right;margin-top:10px;padding:0px">
                            <?php
                                if($auto_close == "1")
                                {
                                    $checked="checked";
                                }
                                else{
                                    $checked="";
                                }
                            ?>
                                <input type='checkbox' name="auto_close_task_comment" <?php echo $checked; ?> class="auto_close_task_comment" id="auto_close_task_comment" value="1"/>
                                 <label for="auto_close_task_comment" style="margin-top: 10px;">Make this task is an Auto Close Task</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding:8px; background-color:white; height:980px; overflow-x:hidden; overflow-y:auto;">
                    <div class="row" style="padding:10px;">
                        <div class="col-md-6">
                            <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="<?php echo $task->id; ?>" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;">
                            </a>
                        </div>                        
                    </div>
                    <div class="row" style="padding:10px;">
                        <div class="col-md-12">
                            <spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                            <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="right" data-popover-content="#a1_<?php echo $task->id; ?>" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>

                            <!-- Content for Popover #1 -->
                            <div class="hidden" id="a1_<?php echo $task->id; ?>">
                                <div class="popover-heading">
                                    Set Progress Percentage
                                </div>
                                <div class="popover-body">
                                    <input type="number" class="form-control input-sm progress_value" id="progress_value_<?php echo $task->id; ?>" value="" style="width:77%;float:left">
                                    <a href="javascript:" onclick="setProgress();" class="common_black_button set_progress" data-element="<?php echo $task->id; ?>" style="font-size: 11px;line-height: 29px;">Set</a>
                                </div>
                            </div>
                            <div class="progress progress_<?php echo $task->id; ?>" style="width:92%;margin-bottom:5px">
                            <?php
                            if($task->progress==0){
                              $color = 'color:black;';
                            }
                            else{
                              $color = 'color:white;';
                            }
                            ?>
                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $task->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo $color; ?> width:<?php echo $task->progress; ?>%">
                                    <?php echo $task->progress; ?>%
                                </div>
                            </div>
                        </div>
                        
                    </div>
                     <div class="row" style="padding:10px;">
                        <div class="col-md-12">
                            <?php
                            if($task->auto_close == 1)
                            {
                              $close_task = 'auto_close_task_complete';
                            }
                            else{
                              $close_task = '';
                            }
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
	                            
                        </div>
                     </div>
                     <div class="row" style="padding:10px;">
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
                                        if(($user)){
                                          foreach ($user as $user1) {
                                            if(Session::has('taskmanager_user'))
                                            {
                                              if($user1->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                                              else{ $selected = ''; }
                                            }

                                        ?>
                                          <option value="<?php echo $user1->user_id ?>" <?php echo $selected; ?>><?php echo $user1->lastname.'&nbsp;'.$user1->firstname; ?></option>
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
	                                    $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

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
                            <tr>
	                          <td style="background:#F8F8F8">
	                            <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
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
                                        if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                                        $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                                        $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                                        $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                                        <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                                        <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

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
	                      </table>
                     </div>
                </div>
            </div>
        </div>
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
                      if(($user)){
                        foreach ($user as $user1) {
                          if(Session::has('taskmanager_user'))
                          {
                            if($user1->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }

                      ?>
                        <option value="<?php echo $user1->user_id ?>" <?php echo $selected; ?>><?php echo $user1->lastname.'&nbsp;'.$user1->firstname; ?></option>
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
                  if(($user)){
                    foreach ($user as $user1) {
                  ?>
                    <option value="<?php echo $user1->user_id ?>"><?php echo $user1->lastname.'&nbsp;'.$user1->firstname; ?></option>
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
                  if(($user)){
                    foreach ($user as $user1) {
                  ?>
                    <option value="<?php echo $user1->user_id ?>"><?php echo $user1->lastname.'&nbsp;'.$user1->firstname; ?></option>
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
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
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
<script>
    $(window).click(function(e) {
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
      if($(e.target).hasClass('link_infile'))
      {
        var href = $(e.target).attr("data-element");
      var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
      if (printWin == null || typeof(printWin)=='undefined')
      {
        alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
      }
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
    });
    $(document).ready(function(){
        if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
        CKEDITOR.replace('editor_1',
        {
            height: '150px',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
        });
    });
    $(".download_pdf_spec").on("click", function(){
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
        });
    });
    $(".add_comment_allocate_to_btn").on("click", function(){
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
                                showComments(task_id);
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
                            showComments(task_id);
                            }
                        });
                    }
                }
                })
            },1000);
        }
    });
    $(".auto_close_task_comment").on("click", function(){
        var task_id = $("#hidden_task_id_task_specifics").val();
        if($(".auto_close_task_comment").is(":checked"))
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
            }
        });
    });
    $(".add_comment_and_allocate").on("click",function(){
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
                        showComments(task_id);
                    }
                    else{
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
                                showComments(task_id);
                            }
                        });
                                               
                        
                    }
                }
                })
            },1000);
            }
        }
    });
    function showComments(task_id){
        $.ajax({
            url:"<?php echo URL::to('user/show_existing_comments'); ?>",
            type:"post",
            dataType:"json",
            data:{task_id:task_id},
            success:function(result)
            {
                
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
            }
        })
    }
    $(".add_task_specifics").on("click", function(){
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
    });
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".add_progress_files_from_task_specifics").on("click", function(){
        var task_id = $(".add_progress_files_from_task_specifics").attr("data-element");
        $("#hidden_task_id_progress").val(task_id);
        $(".dropzone_progress_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    });
    $('.park_task_button').on('click', function(){
        var taskid = $('.park_task_button').attr("data-element");
        $(".hidden_task_id_park_task").val(taskid);
        $(".park_task_modal").modal("show");
        $("#park_task_date").val("");
        $("#park_task_date").datetimepicker({
            defaultDate: fullDate,       
            format: 'L',
            format: 'DD-MMM-YYYY',
            });
    });
    $("#park_submit").on("click", function(){
        var park_date = $("#park_task_date").val();
        if(park_date == "")
        {
            alert("Please choose the Date to Park the Task.");
        }
        else{
            $("body").addClass("loading");
            var task_id = $(".hidden_task_id_park_task").val();
            
            // var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
            // var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
            // if (typeof nexttask_id !== "undefined") {
            // var taskidval = nexttask_id;
            // }
            // else if (typeof prevtask_id !== "undefined") {
            // var taskidval = prevtask_id;
            // }
            // else{
            // var taskidval = '';
            // }
            $.ajax({
                url:"<?php echo URL::to('user/park_task_complete'); ?>",
                type:"post",
                data:{task_id:task_id,park_date:park_date},
                success:function(resultval)
                {
                    $(".park_task_modal").modal("hide");
                    $("body").removeClass("loading");
                    var layout = $("#hidden_compressed_layout").val();
                    var view = $(".select_view").val();
                    $('.park_task_button').text("Activate");
                    $('.park_task_button').addClass("activate_task_button");
                    $('.park_task_button').removeClass("park_task_button");
                    window.location.reload();
                }
            })
        }
    });
    $(".mark_as_complete").on("click",function(){
        var task_id = $(".mark_as_complete").attr("data-element");
        var nexttask_id = $(".mark_as_complete").parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
        var prevtask_id = $(".mark_as_complete").parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
        if($(".mark_as_complete").hasClass('auto_close_task_complete'))
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
                window.location.reload();
                // if(layout == "1")
                // {
                //     var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                //     var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                //     if (typeof nexttask_id !== "undefined") {
                //     var taskidval = nexttask_id;
                //     }
                //     else if (typeof prevtask_id !== "undefined") {
                //     var taskidval = prevtask_id;
                //     }
                //     else{
                //     var taskidval = '';
                //     }

                //     $("#task_tr_"+task_id).next().detach();
                //     $("#task_tr_"+task_id).detach();
                //     $("#hidden_tasks_tr_"+task_id).detach();

                //     $("#task_tr_"+taskidval).show();
                //     $("#task_tr_"+taskidval).next().show();
                //     $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

                //     var opentask = $("#open_task_count_val").html();
                //     var countopen = parseInt(opentask) - 1;
                //     $("#open_task_count_val").html(countopen);
                //     $("body").removeClass("loading");
                // }
                // else{
                //     setTimeout(function() {
                //     var user_id = $(".select_user_home").val();
                //     $.ajax({
                //         url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                //         type:"post",
                //         data:{user_id:user_id},
                //         dataType:"json",
                //         success: function(result)
                //         {
                        
                //         $("#task_body_open").html(result['open_tasks']);
                //         $("#task_body_layout").html(result['layout']);
                //         $(".taskname_sort_val").find(".2bill_image").detach();
                //         var layout = $("#hidden_compressed_layout").val();
                //         $(".tasks_tr").hide();
                //         $(".tasks_tr").next().hide();
                //         $(".hidden_tasks_tr").hide();
                //         var view = $(".select_view").val();
                //         if(view == "3")
                //         {
                //             if(layout == "1")
                //             {
                //             $(".author_tr:first").show();
                //             $(".author_tr:first").next().show();
                //             $(".table_layout").show();
                //             $(".table_layout").find(".hidden_author_tr").show();
                //             $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                //             }
                //             else{
                //             $(".author_tr").show();
                //             $(".author_tr").next().show();
                //             $(".table_layout").hide();
                //             $(".table_layout").find(".hidden_author_tr").hide();
                //             }
                //         }
                //         else if(view == "2"){
                //             $("#open_task_count").hide();
                //             $("#redline_task_count").show();
                //             $("#authored_task_count").hide();
                //             if(layout == "1")
                //             {
                //             var i = 1;
                //             $(".redline_indication").each(function() {
                //                 if(i == 1)
                //                 {
                //                 if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                //                 {
                //                     $(this).parents(".allocated_tr").show();
                //                     $(this).parents(".allocated_tr").next().show();
                //                     i++;
                //                 }
                //                 }
                //             });
                //             $(".table_layout").show();
                //             $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                            
                //             var j = 1;
                //             $(".redline_indication_layout").each(function() {
                //                 if(j == 1)
                //                 {
                //                 if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                //                 {
                //                     $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                //                     j++;
                //                 }
                //                 }
                //             });
                //             }
                //             else{
                //             $(".redline_indication").parents(".allocated_tr").show();
                //             $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                //             $(".table_layout").hide();
                //             $(".table_layout").find(".hidden_allocated_tr").hide();
                //             }
                //         }
                //         else if(view == "1"){
                //             if(layout == "1")
                //             {
                //             $(".allocated_tr:first").show();
                //             $(".allocated_tr:first").next().show();
                //             $(".table_layout").show();
                //             $(".table_layout").find(".hidden_allocated_tr").show();
                //             $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                //             }
                //             else{
                //             $(".allocated_tr").show();
                //             $(".allocated_tr").next().show();
                //             $(".table_layout").hide();
                //             $(".table_layout").find(".hidden_allocated_tr").hide();
                //             }
                //         }

                //         var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                //         var opentask = $(".hidden_allocated_tr").length;
                //         var authored = $(".hidden_author_tr").length;
                //         $("#redline_task_count_val").html(redline);
                //         $("#open_task_count_val").html(opentask);
                //         $("#authored_task_count_val").html(authored);

                //         $("[data-toggle=popover]").popover({
                //             html : true,
                //             content: function() {
                //                 var content = $(this).attr("data-popover-content");
                //                 return $(content).children(".popover-body").html();
                //             },
                //             title: function() {
                //                 var title = $(this).attr("data-popover-content");
                //                 return $(title).children(".popover-heading").html();
                //             }
                //         });
                //         if(layout == "0")
                //         {
                //             if(taskidval != "")
                //             {
                //             // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                //             }
                //         }
                //         else{
                //             $("#"+taskidval).show();
                //             $("#"+taskidval).next().show();
                //             var hidden_tr = taskidval.substr(8);
                //             $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                //         }
                //         if(layout == "1")
                //         {
                //             $(".open_layout_div").addClass("open_layout_div_change");
                //             var open_tasks_height = $(".open_layout_div").height();
                //             var margintop = parseInt(open_tasks_height);
                //             $(".open_layout_div").css("position","fixed");
                //             $(".open_layout_div").css("height","312px");
                //             if(open_tasks_height > 312)
                //             {
                //             $(".open_layout_div").css("overflow-y","scroll");
                //             }
                //             if(open_tasks_height < 50)
                //             {
                //             $(".table_layout").css("margin-top","20px");
                //             }
                //             else{
                //                 $(".table_layout").css("margin-top","233px");
                //             }
                //         }
                //         else{
                //             $(".open_layout_div").removeClass("open_layout_div_change");
                //             $(".open_layout_div").css("position","unset");
                //             $(".open_layout_div").css("height","auto");
                //             $(".open_layout_div").css("overflow-y","unset");
                //             $(".table_layout").css("margin-top","0px");
                //         }
                //         $("body").removeClass("loading");
                //         }
                //     })
                //     },2000);
                // }
            }
        });
        }
    });
    $(".mark_as_complete_author").on("click",function(){
      var r = confirm("You are about to mark this task as Complete are you sure you want to continue?");
      if(r)
      {
        $("body").addClass("loading");
        var task_id = $(".mark_as_complete_author").attr("data-element");

        var nexttask_id = $(".mark_as_complete_author").parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
        var prevtask_id = $(".mark_as_complete_author").parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
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
            window.location.reload();
          }
        })
      }
    });
    $(".integrity_check_for_task").on("click", function(){
        var task_id = $(".integrity_check_for_task").attr("data-element");
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
    });
    $(".download_pdf_task").on("click",function(){
        $("body").addClass("loading");
        var task_id = $(".download_pdf_task").attr("data-element");
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
    });
    $(".copy_task").on("click", function(){
        $("#hidden_copied_files").val("");
        $("#hidden_copied_notes").val("");
        $("#hidden_copied_infiles").val("");
        var task_id = $(".copy_task").attr("data-element");
        $(".hide_taskmanager_files").hide();
        $(".question_modal").modal("show");
        $(".hidden_task_id_copy_task").val(task_id);

        $("#copy_task_specifics_no").prop("checked",true);
        $("#copy_task_files_no").prop("checked",true);
    });
    $("#question_submit").on("click", function(){
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
                height: '150px',
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
    });
    $(".faprogress_download_all").on("click",function(){
        var lenval = $(".faprogress_download_all").parents("tbody:first").find(".file_attachments").length;
      if(lenval > 0)
      {
          $("body").addClass("loading");
          var id = $(".faprogress_download_all").attr('data-element');
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
    });
    $(".fanotepad_progress").on("click",function(){
        var pos = $(".fanotepad_progress").position();
        var leftposi = parseInt(pos.left) + 0;
        $(".fanotepad_progress").parent().find('.notepad_div_progress_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    });
    $(".faplus_progress").on("click",function(){
        var task_id = $(".faplus_progress").attr("data-element");
        $("#hidden_task_id_progress").val(task_id);
        $(".dropzone_progress_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    });
    $(".notepad_progress_submit").on("click",function(){
        var contents = $(".notepad_progress_submit").parent().find('.notepad_contents_progress').val();
        if(contents == '' || typeof contents === 'undefined')
        {
            $(".notepad_progress_submit").parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
            return false;
        }
        else{
            $(".notepad_progress_submit").parents('td').find('.notepad_div_progress_notes').toggle();
        }
    });
    $(".activate_task_button").on("click",function(){
        var r = confirm("Do you want to make this task live now?");
        if(r)
        {
            $("body").addClass("loading");
            var task_id = $(".activate_task_button").attr("data-element");
            
            $.ajax({
                url:"<?php echo URL::to('user/park_task_incomplete'); ?>",
                type:"post",
                data:{task_id:task_id},
                success:function(resultval)
                {
                    $('.activate_task_button').text("Park Task");
                    $('.activate_task_button').addClass("park_task_button");
                    $('.activate_task_button').removeClass("activate_task_button");
                    window.location.reload();
                }
            })
        }
    });
    $(".edit_due_date").on("click",function(){
        var subject = $(".edit_due_date").attr("data-subject");
        var due_date = $(".edit_due_date").attr("data-value");
        var task_id = $(".edit_due_date").attr("data-element");
        var color = $(".edit_due_date").attr("data-color");
        var correct_date = $(".edit_due_date").attr("data-duedate");
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
    });
    $("#due_date_change_button").on("click",function(){
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
    });
    $(".avoid_email").on("click",function(){
        $("body").addClass("loading");
        var task_id = $(".avoid_email").attr("data-element");
        if($(".avoid_email").hasClass('retain_email'))
        {
        $.ajax({
            url:"<?php echo URL::to('user/set_avoid_email_taskmanager'); ?>",
            type:"post",
            data:{task_id:task_id,status:0},
            success:function(result)
            {
            $(".avoid_email").removeClass("retain_email");
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
            $(".avoid_email").addClass("retain_email");
            $("body").removeClass("loading");
            }
        });
        }
    });
    $(".fanotepad_completion").on("click",function(){
        var pos = $(".fanotepad_completion").position();
        var leftposi = parseInt(pos.left);
        $(".fanotepad_completion").parent().find('.notepad_div_completion_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    });
    $(".notepad_completion_submit").on("click",function(){
        var contents = $(".notepad_completion_submit").parent().find('.notepad_contents_completion').val();
        if(contents == '' || typeof contents === 'undefined')
        {
            $(".notepad_completion_submit").parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
            return false;
        }
        else{
            $(".notepad_completion_submit").parents('td').find('.notepad_div_completion_notes').toggle();
        }
    });
    $(".faplus_completion").on("click",function(){
        var task_id = $(".faplus_completion").attr("data-element");
        $("#hidden_task_id_completion").val(task_id);
        $(".dropzone_completion_modal").modal("show");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    });
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
    });
    function setProgress(){
        var task_id = $(".set_progress").attr("data-element");
        var value = $(".set_progress").parents(".popover-content").find(".progress_value").val();
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
    $(".edit_allocate_user").on("click", function(){
      $("body").addClass("loading");
      var task_id = $(".edit_allocate_user").attr("data-element");
      var subject = $(".edit_allocate_user").attr("data-subject");
      var author = $(".edit_allocate_user").attr("data-author");
      var allocated = $(".edit_allocate_user").attr("data-allocated");
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
      if($(".edit_allocate_user").hasClass('auto_close_task_complete'))
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
    });
    $("#allocate_now").on("click",function(){
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

      // var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
      // var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
      // if (typeof nexttask_id !== "undefined") {
      //   var taskidval = nexttask_id;
      // }
      // else if (typeof prevtask_id !== "undefined") {
      //   var taskidval = prevtask_id;
      // }
      // else{
      //   var taskidval = '';
      // }

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
            
          }
        })
      }
    });
    $(".show_task_allocation_history").on("click",function(){
      $("body").addClass("loading");
      var task_id = $(".show_task_allocation_history").attr("data-element");
      var subject = $(".show_task_allocation_history").attr("data-subject");
      var author = $(".show_task_allocation_history").attr("data-author");
      var allocated = $(".show_task_allocation_history").attr("data-allocated");

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
    });
    $(".export_csv_history").on("click", function(){
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
    });
    $(".export_pdf_history").on("click", function(){
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
    });
    $(".request_update").on("click", function(){
      var r = confirm("An email is sent to the person who the task is currently allocated to. Are you sure you want to continue?");
      if(r)
      {
        $("body").addClass("loading");
        setTimeout(function() {
          var task_id = $(".request_update").attr("data-element");
          var author = $(".request_update").attr("data-author");
          var allocated_to = $(".request_update").attr("data-allocated");

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
    });
    $(".infiles_link_completion").on("click", function(){
      var task_id = $(".infiles_link_completion").attr("data-element");
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
    });
    $(".yearend_link_completion").on("click", function(){
      var task_id = $(".yearend_link_completion").attr("data-element");
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
    });
    $("#link_yearend_completion_button").on("click", function(){
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
    });
    $("#link_infile_completion_button").on("click", function(){
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
    });
    $(".infiles_link_progress").on("click", function(){
      var task_id = $(".infiles_link_progress").attr("data-element");
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
    });
    $("#link_infile_progress_button").on("click", function(){
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
    });

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

            $(".add_progress_attachments").append('<p class="pending_attachment" data-element="'+obj.task_id+'" data-user="'+obj.from_user+'">'+obj.message+'</p>');


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
                  $("#existing_comments").append(result);
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
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
</script>
@stop
