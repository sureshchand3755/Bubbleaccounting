@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
.client_inactive td,.selected_inactive td {color:#f00 !important;}
/*body{
  background: #2fd9ff !important;
}*/
.modal-header{
	cursor:move;
}
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
#total_time_minutes_format{
  color:#0f9600;
}
#reset_breaktime{

  background: #000;

    color: #fff;

        margin-left: -17px;

}
#edit_reset_breaktime{

  background: #000;

    color: #fff;

        margin-left: -17px;

}

.add_minutes_div{    

    border: 1px solid #dfdfdf;

}

.add_minutes_div:hover{    

    border: 1px solid #dfdfdf;

    background: #000;

    color:#fff;

}

.edit_add_minutes_div{    

    border: 1px solid #dfdfdf;

}

.edit_add_minutes_div:hover{    

    border: 1px solid #dfdfdf;

    background: #000;

    color:#fff;

}

.tasks_drop{border-radius: 0px; background:none;}





.form-control[readonly]{



      background-color: #fff !important



}

.formtable tr td{



  padding-left: 15px;



  padding-right: 15px;



}

.fullviewtablelist>tbody>tr>td{



  font-weight:800 !important;



  font-size:15px !important;



}



.fullviewtablelist>tbody>tr>td a{



  font-weight:800 !important;



  font-size:15px !important;



}



.modal { overflow: auto !important;z-index: 999999;}



.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover



{



  z-index: 0 !important;



}



.modal_load {

    display:    none;

    position:   fixed;

    z-index:    999999;

    top:        0;

    left:       0;

    height:     100%;

    width:      100%;

    background: rgba( 255, 255, 255, .8 ) 

                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 50% 50% no-repeat;



}







.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }

.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}

.ok_button:focus{background: #000; text-decoration: none; color: #fff}





body.loading {

    overflow: hidden;   

}



body.loading .modal_load {

    display: block;

}



.table thead th:focus{background: #ddd !important;}



.form-control{border-radius: 0px;}



.disabled{cursor :auto !important;pointer-events: auto !important}



.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}



body #coupon {

      display: none;



}

@media print {

 body * {

  display: none;

}

body #coupon {

   display: block;

   }

 }

.ui-widget{z-index: 999999999}
#colorbox{
  z-index: 99999999999;
}
.show_client_ids_import .col-md-4{
  padding: 10px;
  border:1px solid #eee;
}
</style>

<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document" style="width:35%">

    <form action="<?php echo URL::to('user/time_job_add')?>" method="post" class="add_new_form" id="create_job_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title menu-logo job_title"></h4>

          </div>

          <div class="modal-body">

            <div class="form-group">                

                <input  type="checkbox" class="mark_internal" name="internal" id="mark_internal"><label for="mark_internal" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Job</label>

                <input  type="checkbox" class="mark_activeclient" name="activeclient" id="mark_activeclient"><label for="mark_activeclient" id="label_activeclient" style="font-size: 14px; font-weight: normal; cursor: pointer;">Select from Active Client List</label>

                <input type="hidden" class="internal_type" value="1" name="internal_type">

                <input type="hidden" class="user_id" value="" name="user_id">

                <input type="hidden" id="hidden_job_id" value="" name="hidden_job_id">

                <input type="hidden" class="hidden_activejob_starttime" id="hidden_activejob_starttime" value="" name="hidden_activejob_starttime">

            </div>

            <div class="form-group client_group">

                <div class="form-title">Choose Client:</div>

                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required style="width:95%; display:inline;">
                <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />                      

                <input type="hidden" id="client_search" name="clientid" />

            </div>

            <div class="form-group active_client_group" style="display: none;">
              <div class="form-title">Select from active client list:</div>
              <div class="dropdown" style="width: 100%">
                <select class="form-control active_list_dropdown" name="active_list_option">
                  <option value=''>Select from active client list</option>
                  <?php
                    foreach($active_client_list as $list){
                      echo '<option value="'.$list->client_id.'" data-activestatus="'.$list->active.'">'.$list->company.' ('.$list->client_id.')'.'</option>';
                    }
                  ?>
                </select>               
              </div>
            </div>


            <div class="form-group internal_tasks_group" style="display: none;">

                <div class="form-title">Select Task:</div>

                <div class="dropdown" style="width: 100%">

                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">

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

                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>

                    <?php

                      }

                    }

                    ?>

                </ul>

              </div>

              

            </div>



<input type="hidden" id="idtask" value="" name="task_id">

            <div class="form-group tasks_group" style="display: none;">    

                <div class="form-title">Select Task:</div>



                <div class="dropdown" style="width: 100%">

                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">

                  <span class="task-choose">Select Task</span>  <span class="caret"></span>                          

                </a>

                

                <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  

                  

                </ul>

              </div>

             

            </div>



            <div class="form-group date_group" style="display: none;">

                <div class="form-title">Current Date:</div>

                <label class="input-group datepicker-only-init">

                    <input type="text" class="form-control create_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required readonly/>

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </label>

            </div>

            <div class="form-group start_group">

                <div class="form-title">Choose Start Time:</div>

                <div class='input-group date' id='start_time'>

                    <input type='text' class="form-control create_startclass" placeholder="Select Start Time" name="starttime" style="font-weight: 500;" required autocomplete="off" />

                    <span class="input-group-addon">

                        <span class="glyphicon glyphicon-time"></span>

                    </span>

                </div>

            </div>



            <div class="form-group stop_group">

                <div class="form-title">Choose Stop Time:</div>

                <div class='input-group date' id='stop_time'>

                    <input type='text' class="form-control stop_time" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <span class="glyphicon glyphicon-time"></span>

                    </span>

                </div>

            </div>







            <input type="hidden" id="quickjob" value="" name="quick_job">

            <input type="hidden" class="acive_id" value="" name="acive_id">
            <input type="hidden" class="taskjob_id" value="" name="taskjob_id">

            <input type="hidden" value="" class="currentdate" name="" >
            <input type="hidden" value="" class="add_edit_job" name="add_edit_job" >
            <input type="hidden" value="" id="hidden_job_type" class="hidden_job_type" name="hidden_job_type">

          </div>

          <div class="modal-footer">

            

            <input type="button" class="common_black_button start_button_quick" value="Start" style="display: none;">

            <input type="button" class="common_black_button start_button" value="Start" style="display: none;">

            <input type="submit" class="common_black_button job_button_name" value="" style="display: none;">

          </div>

        </div>

    @csrf
</form>

  </div>

</div>



<div class="modal fade stop_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form action="<?php echo URL::to('user/time_job_stop')?>" method="post" class="add_new_form" id="time_job_stop">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title menu-logo">Stop Active Job</h4>
          </div>
          <div class="modal-body"> 
            <div class="stop_left_body_class">          
              <div class="form-group start_group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Date:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>                    
                          <input class="form-control dateclass" name="date" value="" readonly autocomplete="off">
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Started Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>
                          <input type='text' class="form-control stop_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Choose Stop Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date' id='stop_time1'>
                          <input type='text' class="form-control stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date stop_jom_time'>
                          <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>
                  <input type="hidden" class="idclass" name="id">
              </div>
              <div class="form-group">
                <input type="button" class="common_black_button" id="stop_job" value="Stop Job" style="float:right">
              </div>
              <div class="form-group breaktime_div" style="display:none">
                  <div class="row">
                    <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">
                      <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time: </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class='input-group date'>
                          <input type='text' class="form-control" id="break_time" name="breaktime" style="font-weight: 500;" readonly/>
                          <input type="hidden" id="break_time_val" name="break_time_val" value="0">
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                      <a href="javascript:" class="btn btn-sm btn-primary" id="reset_breaktime"> Reset </a>
                    </div>
                  </div>
              </div>
              <div class="form-group breaktime_div" style="display:none">
                  <div class="row">
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>
                  </div>
                  <div class="form-group" style="margin-top:20px;">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date stop_jom_time'>
                          <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group start_group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Total Quick Jobs:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>                    
                          <input class="form-control" id="total_quick_jobs" value="" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group start_group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Total Breaks:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>                    
                          <input class="form-control" id="total_breaks" value="" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Actual Time on Job:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>
                          <input class="form-control" id="total_time_minutes_format" value="" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
              </div>
              </div>
              <div class="hide_group_div" style="margin-top:10px;background: #fff;padding:10px">
                <table class="table" style="margin-top:20px;width:90%; ">
                  <tr>
                    <td>Total job time in minutes:</td>
                    <td class="total_job_time"></td>
                  </tr>
                  <tr>
                    <td>Deducted for Quick Jobs:</td>
                    <td class="deducted_job_time"></td>
                  </tr>
                  <tr>
                    <td>Available for distribution:</td>
                    <td class="available_job_time"></td>
                  </tr>
                  <tr>
                    <td>Number of clients selected:</td>
                    <td class="clients_selected"></td>
                  </tr>
                  <tr>
                    <td>Minutes per client:</td>
                    <td class="minutes_per_client"></td>
                  </tr>
                </table>
                <input type="hidden" name="hidden_minutes_per_client" id="hidden_minutes_per_client" value="">
                <input type="hidden" name="hidden_round_type" id="hidden_round_type" value="">
                <input type="button" name="round_up" class="round_up common_black_button" value="Round Up">
                <input type="button" name="round_down" class="round_down common_black_button" value="Round Down">
              </div>
            </div>
            <div class="col-md-6 stop_right_body_class">
              <input type="radio" name="select_client_groups" id="bulk_all_clients" class="select_client_groups" value="1"><label for="bulk_all_clients">All Clients</label> 
              <input type="radio" name="select_client_groups" id="presets_clients" class="select_client_groups" value="2"><label for="presets_clients">Presets</label>
              <input type="radio" name="select_client_groups" id="groups_clients" class="select_client_groups" value="3"><label for="groups_clients">Groups</label> 
              <input type="radio" name="select_client_groups" id="active_clients_stop" class="select_client_groups" value="4"><label for="active_clients_stop">Your Active Clients</label> 

              <?php
              $groups = DB::table('messageus_groups')->get();
              $clients = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->get();
              ?>
              <div class="groups_combo">
                <label>Select Group:</label>
                <select name="select_group_clients" id="select_group_clients" class="form-control select_group_clients">
                  <option value="">Select Group</option>
                  <?php
                    if(($groups))
                    {
                      foreach($groups as $group)
                      {
                        echo '<option value="'.$group->id.'">'.$group->group_name.'</option>';
                      }
                    }
                  ?>
                </select>
              </div>
              <div class="presets_combo">
                <label>Select Presets:</label>
                <select name="select_presets_clients" id="select_presets_clients" class="form-control select_presets_clients">
                  <option value="">Select Presets</option>
                  <option value="1">Current Week Standard PMS Clients</option>
                  <option value="2">Current Week Enhanced PMS Clients</option>
                  <option value="3">Current Week  Complex PMS Clients</option>
                  <option value="4">Current Month Standard PMS Clients</option>
                  <option value="5">Current Month Enhanced PMS Clients</option>
                  <option value="6">Current Month Complex PMS Clients</option>
                  <option value="7">Paye MRS Clients</option>
                </select>
              </div>
              
              <div class="hide_group_div" style="margin-top:10px;background: #fff;padding:10px;min-height:700px;max-height: 700px;overflow-y: scroll;">
                <p><label style="font-weight:800;font-size:18px;text-decoration: underline" id="selected_grp_name"></label> <a href="javascript:" class="fa fa-plus common_black_button import_client_select_list" title="Import Bulk Client List" style="float:right"></a></p>
                 <input type="checkbox" name="select_all_clients" id="select_all_clients" class="select_all_clients" value=""><label for="select_all_clients">Select / Deselect All Clients</label> 
                 <?php
                  $default_tasks = DB::table('time_task')->orderBy('task_name','asc')->get();
                  $output_default = '<select name="default_task" class="form-control default_task" style="width: 30%;float: right;">
                    <option value="">Select Default Tasks</option>';
                  if(($default_tasks)){
                    foreach ($default_tasks as $single_task) {
                      $output_default.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
                    }
                  }
                  else{
                    $output_default.= '<option value="">No Tasks Found</option>';
                  }
                  $output_default.='</select><spam style="float:right;margin-top: 8px;">Select Default Task Type: </spam>';
                  echo $output_default;
                 ?>
                <table class="table own_table_white" id="client_table" style="border: 1px solid #000;">
                  <thead>
                    <th style="text-align: left"></th>
                    <th style="text-align: left">ClientID</th>
                    <th style="text-align: left">Company</th>
                    <th style="text-align: left">Task Type</th>
                  </thead>
                  <tbody id="client_tbody">
                      <?php
                      if(($clients))
                      {
                        foreach($clients as $client)
                        {
                          if($client->active == "2") { $cls= 'client_inactive'; } 
                          else { $cls = 'client_active'; }

                          echo '<tr class="client_tr '.$cls.'" id="client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">
                            <td class="client_td"><input type="checkbox" name="client_exclude[]" class="client_exclude" value="'.$client->client_id.'"><label>&nbsp;</label></td>
                            <td class="client_td">'.$client->client_id.'</td>
                            <td class="client_td">'.$client->company.'</td>
                            <td class="client_td">';
                              $tasks = DB::table('time_task')->where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client->client_id.'%')->orderBy('task_name','asc')->get();
                              $output = '<select name="client_task_'.$client->client_id.'" class="form-control client_task">
                                <option value="">Select Tasks</option>';
                              if(($tasks)){
                                foreach ($tasks as $single_task) {
                                  $output.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
                                }
                              }
                              else{
                                $output.= '<option value="">No Tasks Found</option>';
                              }
                              $output.='</select>';
                            echo $output.'</td>
                          </tr>';
                        }
                      }
                      ?>
                  </tbody>
                </table>
                <table class="table" id="payemrs_table" style="border: 1px solid #000;">
                  <thead>
                    <th style="text-align: left"></th>
                    <th style="text-align: left">ClientID</th>
                    <th style="text-align: left">Company</th>
                    <th style="text-align: left">Task Type</th>
                  </thead>
                  <tbody id="paye_tbody">
                      <?php
                      $year = DB::table('paye_p30_year')->orderBy('year_id','desc')->first();   
                      $payelist = DB::table('paye_p30_task')->where('paye_year',$year->year_id)->get();
                      $group_name = 'PAYE-MRS Clients';
                      $client_val = '';
                      if(($payelist)){
                          foreach ($payelist as $keytask => $task) {
                              $get_clients = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('employer_no',$task->task_enumber)->first();
                              if(($get_clients))
                              {
                                if($get_clients->active == 2) { 
                                  if($task->disabled == 1) {
                                    $label_color = 'color:blue'; $disbledtext = ' (DISABLED)'; 
                                    $label_cls = 'paye_blue_clients';
                                  }
                                  else{
                                    $label_color = 'color:#f00'; $disbledtext = ''; 
                                    $label_cls = 'paye_blue_clients';
                                  }
                                }
                                else{
                                  if($task->disabled == 1) {  $label_color = 'color:blue'; $disbledtext = ' (DISABLED)'; $label_cls = 'paye_blue_clients'; } else { $label_color = 'color:#000'; $disbledtext = ''; $label_cls = ''; }
                                }
                                echo '<tr class="client_tr '.$label_cls.'">
                                        <td class="client_td" style="'.$label_color.'"><input type="checkbox" name="client_exclude[]" class="client_exclude" value="'.$get_clients->client_id.'"><label>&nbsp;</label></td>
                                        <td class="client_td" style="'.$label_color.'">'.$get_clients->client_id.'</td>
                                        <td class="client_td" style="'.$label_color.'">'.$get_clients->company.''.$disbledtext.'</td>
                                        <td class="client_td">';
                                          $tasks = DB::table('time_task')->where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$get_clients->client_id.'%')->orderBy('task_name','asc')->get();
                                          $output = '<select name="paye_client_task_'.$get_clients->client_id.'" class="form-control client_task">
                                            <option value="">Select Tasks</option>';
                                          if(($tasks)){
                                            foreach ($tasks as $single_task) {
                                              $output.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
                                            }
                                          }
                                          else{
                                            $output.= '<option value="">No Tasks Found</option>';
                                          }
                                          $output.='</select>';
                                        echo $output.'</td>
                                      </tr>';
                              }
                          }
                      }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">
            <input type="submit" class="common_black_button" id="stop_active_job" value="Stop Active Job" style="display:none">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<!-- <div class="modal fade stop_bulk_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:70%">
    <form action="<?php echo URL::to('user/time_job_stop')?>" method="post" class="add_new_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Stop Even Bulk Job</h4>
          </div>
          <div class="modal-body">    
            <div class="col-md-6 col-lg-6">       
              <div class="form-group start_group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Date:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>                    
                          <input class="form-control dateclass" name="date" value="" readonly autocomplete="off">
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Started Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date'>
                          <input type='text' class="form-control stop_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Choose Stop Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date' id='stop_time1'>
                          <input type='text' class="form-control stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date stop_jom_time'>
                          <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                  <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>
                  <input type="hidden" class="idclass" name="id">
              </div>
              <div class="form-group breaktime_div" style="display:none">
                  <div class="row">
                    <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">
                      <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time: </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class='input-group date'>
                          <input type='text' class="form-control" id="break_time" name="breaktime" style="font-weight: 500;" readonly/>
                          <input type="hidden" id="break_time_val" name="break_time_val" value="0">
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                      <a href="javascript:" class="btn btn-sm btn-primary" id="reset_breaktime"> Reset </a>
                    </div>
                  </div>
              </div>
              <div class="form-group breaktime_div" style="display:none">
                  <div class="row">
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>
                    <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>
                  </div>
                  <div class="form-group" style="margin-top:20px;">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="form-title">Job Time:</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class='input-group date stop_jom_time'>
                          <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>

                    </div>

                  </div>

              </div>

              <div class="form-group start_group">

                  <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class="form-title">Total Quick Jobs:</div>

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class='input-group date'>                    

                          <input class="form-control" id="total_quick_jobs" value="" autocomplete="off" disabled>

                      </div>

                    </div>

                  </div>

              </div>

              <div class="form-group start_group">

                  <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class="form-title">Total Breaks:</div>

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class='input-group date'>                    

                          <input class="form-control" id="total_breaks" value="" autocomplete="off" disabled>

                      </div>

                    </div>

                  </div>

              </div>


              <div class="form-group">

                  <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class="form-title">Actual Time on Job:</div>

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                      <div class='input-group date'>

                          <input class="form-control" id="total_time_minutes_format" value="" autocomplete="off" disabled>

                      </div>

                    </div>

                  </div>

              </div>

              </div>

            </div>
            <div class="col-md-6 col-lg-6">
            </div>
          </div>

          <div class="modal-footer" style="clear:both">

            <input type="button" class="common_black_button" id="stop_job" value="Stop Job">

            <input type="submit" class="common_black_button" id="stop_active_job" value="Stop Active Job" style="display:none">

          </div>

        </div>

    @csrf
</form>

  </div>

</div>
 -->
<div class="modal fade stop_quick_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <form action="<?php echo URL::to('user/time_job_stop_quick')?>" method="post" class="add_new_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title menu-logo">Stop Quick Job</h4>

          </div>

          <div class="modal-body">

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Date:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control dateclass" name="date" value="" readonly autocomplete="off">

                    </div>

                  </div>

                </div>

            </div>



            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Started Time:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>

                        <input type='text' class="form-control stop_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Choose Stop Time:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date' id='stop_time2'>

                        <input type='text' class="form-control stop_time2" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control" id="calculate_job_time_quick" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>

                <input type="hidden" class="idclass" name="id">

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time: </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class='input-group date'>

                        <input type='text' class="form-control" id="break_time" name="breaktime" style="font-weight: 500;" readonly/>

                        <input type="hidden" id="break_time_val" name="break_time_val" value="0">

                    </div>

                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">

                    <a href="javascript:" class="btn btn-sm btn-primary" id="reset_breaktime"> Reset </a>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>

                </div>

            </div>

          </div>

          <div class="modal-footer">

            <input type="submit" class="common_black_button" id="stop_job_quick" value="Stop Quick Job">           

          </div>

        </div>

    @csrf
</form>

  </div>

</div>



<div class="modal fade edit_stop_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <form action="<?php echo URL::to('user/edit_time_job_update')?>" method="post">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title menu-logo stop_title">Edit Job</h4>

          </div>

          <div class="modal-body">           

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                  <input type="checkbox" class="edit_mark_internal" name="internal" id="edit_mark_internal" disabled>
                  <label for="edit_mark_internal" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Job</label>
                  <input  type="checkbox" class="edit_mark_activeclient" name="edit_activeclient" id="edit_mark_activeclient" disabled>
                  <label for="edit_mark_activeclient" id="edit_label_activeclient" style="font-size: 14px; font-weight: normal; cursor: pointer;">Select from Active Client List</label>
                  <input type="hidden" class="internal_type" value="">
                </div>
              </div>
            </div>
            <div class="form-group edit_client_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Client
                  </div>                  
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <input type="input" class="form-control edit_client_name" readonly required value="">
                  <input type="hidden" class="edit_client_class" name="clientid" value="">
                </div>
              </div>
            </div>
            <div class="form-group edit_active_client_group" style="display: none;">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                  Select from active client list
                  </div>                  
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <select class="form-control edit_active_list_dropdown" name="edit_active_list_dropdown">
                    <option value=''>Select from active client list</option>
                    <?php
                      foreach($active_client_list as $list){
                        echo '<option value="'.$list->client_id.'" data-activestatus="'.$list->active.'">'.$list->company.' ('.$list->client_id.')'.'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group edit_task_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Task
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="dropdown" style="width: 100%">
                    <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">
                      <span class="task-choose">Select Task</span>  <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  

                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group edit_internal_task_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Task
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="dropdown" style="width: 100%">
                    <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">
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
                          <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                        <?php
                          }
                        }
                        ?>
                    </ul>

                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" id="edit_idtask" value="" name="task_id">


            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Date :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control edit_dateclass" name="date" value="" readonly autocomplete="off">

                    </div>

                  </div>

                </div>

            </div>



            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Started Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group edit_date'>

                        <input type='text' class="form-control edit_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Choose Stop Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date' id='edit_stop_time1'>

                        <input type='text' class="form-control edit_stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control edit_calculate_job_time" name="edit_calculate_job_time" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <textarea class="form-control edit_comments" name="comments" placeholder="Enter Comments"></textarea>

                <input type="hidden" class="idclass" name="id">

            </div>

            <div class="form-group breaktime_div_edit_close" style="display:none">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Actual Job Time for Primary Active Job : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class='input-group date'>
                        <input type='text' class="form-control" id="primary_job_time" name="primary_job_time" style="font-weight: 500;" readonly/>
                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div_edit" style="display:none">

                <div class="row">

                  <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class='input-group date'>

                        <input type='text' class="form-control" id="edit_break_time" name="breaktime" style="font-weight: 500;" readonly/>

                        <input type="hidden" id="edit_break_time_val" name="edit_break_time_val" value="0">

                    </div>

                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">

                    <a href="javascript:" class="btn btn-sm btn-primary" id="edit_reset_breaktime"> Reset </a>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div_edit" style="display:none">

                <div class="row">

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>

                </div>

                <div class="form-group" style="margin-top:20px;">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control edit_calculate_job_time" name="edit_calculate_job_time" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Total Quick Jobs :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control" id="edit_total_quick_jobs" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Total Breaks :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control" id="edit_total_breaks" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>


            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Actual Time on Job:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>

                        <input class="form-control" id="edit_total_time_minutes_format" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>

            </div>

            
          </div>

          <div class="modal-footer">
            <input type="hidden" id="hidden_edit_job_id" value="" name="hidden_edit_job_id">
            <input type="hidden" id="edit_quickjob" value="" name="quick_job">
            <input type="submit" class="common_black_button" id="edit_stop_active_job" value="Update">        

          </div>

        </div>

    @csrf
</form>

  </div>

</div>


<div class="modal fade import_bulk_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
    <form action="<?php echo URL::to('user/import_client_list_timeme')?>" method="post" class="import_bulk_client_form" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title menu-logo">Import Bulk Client List</h4>
        </div>
        <div class="modal-body" style="clear:both">            
          <h4>Choose CSV File:</h4>
          <input type="file" name="import_file" class="form-control import_file" value="" accept=".csv" required>
          <p style="color:#f00">Note: Please import a  CSV file with the client codes you want to import, the title of the column must be "Client Code"</p>
          <div class="col-md-12 show_client_ids_import" style="display:none;margin-top:20px">
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="hidden" name="hidden_bulk_ids" id="hidden_bulk_ids" value="">
          <input type="button" name="import_file_submit" class="common_black_button import_file_submit" value="IMPORT FILE">
          <input type="button" name="bulk_assigned" class="common_black_button bulk_assigned" value="Bulk Assigned" style="display:none">
        </div>
      </div>
    @csrf
</form>
  </div>
</div>

<div class="modal fade load_time_sheet_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title menu-logo">Load Time Sheet</h4>
        </div>
        <div class="modal-body" style="clear:both">            
          <h4>Select Date:</h4>
          <input type="text" name="time_sheet_date" class="form-control time_sheet_date" value="" required>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="button" name="load_time_sheet_btn" class="common_black_button load_time_sheet_btn" value="Load Time Sheet">
        </div>
      </div>
  </div>
</div>


<div class="modal fade take_break_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document">

    <form action="<?php echo URL::to('user/job_add_break')?>" method="post" class="add_new_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title menu-logo job_title">Take Break</h4>

          </div>

          <div class="modal-body">            



            



            <div class="form-group">

                <select class="form-control select_break_class" name="breaktime" required>

                  <option value="">Select Time</option>

                  <option value="00:15:00">15 Minutes</option>

                  <option value="00:30:00">30 Minutes</option>

                  <option value="00:45:00">45 Minutes</option>

                  <option value="01:00:00">60 Minutes</option>

                </select>

            </div>



            <input type="hidden" class="id_take_break" name="id">            

            

          </div>

          <div class="modal-footer">

            <input type="submit" class="common_black_button" value="Take Break">

          </div>

        </div>

    @csrf
</form>

  </div>

</div>





<div class="modal fade break_time_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document">

    <form action="<?php echo URL::to('user/job_add_break')?>" method="post" class="add_new_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title menu-logo job_title">Break Time</h4>

          </div>

          <div class="modal-body">

              <table class="display nowrap fullviewtablelist" id="break_tbody" width="100%">

                <thead>

                  <tr style="background: #fff;">

                    <th width="2%" style="text-align: left;">S.No</th>

                    <th style="text-align: left;">Break Time</th>

                </tr>

                </thead>

                <tbody class="break_time_details">

                </tbody>

              </table>              

          </div>

          <div class="modal-footer">

            

          </div>

        </div>

    @csrf
</form>

  </div>

</div>











<div class="content_section">

  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                TimeMe Management System               
            </h4>
    </div>

  
    

  <div style="clear: both;">

   <?php

    if(Session::has('message')) { ?>

        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>



    

    <?php } ?>

    <?php

    if(Session::has('error-message')) { ?>

        <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>



    

    <?php } ?>

    </div>

  

    <div class="row">
      <div class="col-lg-12">
      <a href="<?php echo URL::to('user/time_me_overview'); ?>" class="common_black_button" style="float:right;font-size:14px;font-weight: bold;">TimeMe Overview</a>
    </div>      

      

      

      

      

      

      

      <div class="col-lg-12" style="background: #fff; padding:25px 15px !important; margin-top:23px;  ">        

        <div class="col-lg-12" style="margin: 0px 0px 0px 0px;">
          <spam style="font-size: 24px;font-weight: 500">Active Job</spam>
          <?php
          if(isset($_GET['timesheet_date']))
          {
            echo '<span style="font-size: 16px;font-weight: 600;margin-left: 30px;color: #f00;">You are Reviewing the Time Sheet for '.date('d-M-Y', strtotime($_GET['timesheet_date'])).'</span> <a href="'.URL::to('user/time_track').'" class="common_black_button">Revert to Current Date</a>';
          }
          ?>
          <?php
          if(Session::has('task_job_user') && Session::get('task_job_user') != "")
          {
            $check_date_available = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('quick_job',0)->where('status',0)->first();
            if(($check_date_available))
            {
              $job_available = 'not_create';
            }
            else{
              $job_available = '';
            }  
          }
          else{
            $job_available = '';
          }
          ?>
          <a href="javascript:" class="common_black_button load_time_sheet" style="line-height:20px; margin-right: 5px; font-weight: bold;float:right">Load Time Sheet</a>
          <a href="javascript:" class="common_black_button create_quick_time" style="line-height:20px; margin-right: 5px; font-weight: bold;float:right">Quick Time</a>          
          <a href="javascript:" class="common_black_button create_bulk_job <?php echo $job_available; ?>" style="line-height:20px; margin-right: 5px; font-weight: bold;float:right">Create an Even Bulk Job</a>   
          <a href="javascript:" class="common_black_button create_new <?php echo $job_available; ?>" style="line-height:20px; margin-right: 5px; font-weight: bold;float:right">Create an Active Job</a> 
          <select class="form-control" id="user_select" style="float:right;width:15%;">
            <option value="">Select User</option>        
            <?php
            $selected = '';
            if(($userlist)){
              foreach ($userlist as $user) {
                if(Session::has('task_job_user'))
                {
                  if($user->user_id == Session::get('task_job_user')) { $selected = 'selected'; }
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

        <div class="col-lg-5" style="padding: 7px 0px 0px 0px;text-align: right;">
          

          
        </div>
        <div class="clearfix" style="margin-bottom: 20px;"></div>



        <table class="display nowrap fullviewtablelist own_table_white" id="active_job" width="100%">

          <thead>

            <tr style="background: #fff;">

              <th width="2%" style="text-align: left;">S.No</th>

              <th style="text-align: left;">Client Name</th>

              <th style="text-align: left;">Task Name</th>

              <th style="text-align: left;">Task Type</th>

              <th style="text-align: left;">Quick Break</th>

              <th style="text-align: left;">Date</th>

              <th style="text-align: left;">Start Time</th>

              <th style="text-align: left;">Stop Time</th>
              <th style="text-align: left;">BEB</th>
              <th style="text-align: left;">BEP</th>

              <th style="text-align: left;">Job Time</th>

              <th style="text-align: center;">Action</th>

          </tr>

          </thead>

          <tbody id="tbody_active">

            <?php
            $output='';
            $i=1;            
            if(($joblist)){              
              foreach ($joblist as $jobs) {
                if($jobs->quick_job == 0 || $jobs->quick_job == 1){
                  if($jobs->status == 0){
                    $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $jobs->client_id)->first();
                    if(($client_details) != ''){
                      $client_name = $client_details->company.' ('.$jobs->client_id.')';
                    }
                    else{
                      $client_name = 'N/A';
                    }
                    $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();
                    if(($task_details) != ''){
                      $task_name = $task_details->task_name;
                      $task_type = $task_details->task_type;
                      if($task_type == 0){
                        $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                      }
                      elseif($task_type == 1){
                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                      }
                      else{
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }
                    // $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                    // $count_minues = 0;
                    // if(($break_time_count)){
                    //   foreach ($break_time_count as $break_time1) {
                    //     if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    //     elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    //     elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    //     elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    //     if($count_minues == 0)
                    //     {
                    //       $count_minues = $minval;
                    //     }
                    //     else{
                    //       $count_minues = $count_minues + $minval;
                    //     }
                    //   }
                    // }
                    // if($count_minues == 0)
                    // {
                    //   $break_hours = '';
                    // }
                    // elseif($count_minues < 60)
                    // {
                    //   $break_hours = $count_minues.' Minutes';
                    // }
                    // elseif($count_minues == 60)
                    // {
                    //   $break_hours = '1 Hour';
                    // }
                    // else{
                    //   if(floor($count_minues / 60) <= 9)
                    //   {
                    //     $h = floor($count_minues / 60);
                    //   }
                    //   else{
                    //     $h = floor($count_minues / 60);
                    //   }
                    //   if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
                    //   {
                    //     $m = ($count_minues -   floor($count_minues / 60) * 60);
                    //   }
                    //   else{
                    //     $m = ($count_minues -   floor($count_minues / 60) * 60);
                    //   }
                    //   if($m == "00")
                    //   {
                    //     $break_hours = $h.' Hours';
                    //   }
                    //   else{
                    //     $break_hours = $h.':'.$m.' Hours';
                    //   }
                    // }
                    //-----------Job Time Start----------------
                    $created_date = $jobs->job_created_date;
                    $jobstart = strtotime($created_date.' '.$jobs->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));

                    if($jobend < $jobstart)
                    {
                      if($created_date == date('Y-m-d'))
                      {
                          $negative = '-';
                          $jobdiff  = $jobstart - $jobend;
                      }
                      else{
                        $negative = '';
                        $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                        $jobend   = strtotime($todate.' '.date('H:i:s'));
                        $jobdiff  = $jobend - $jobstart;
                      }
                    }
                    else{
                      $negative = '';
                      $jobdiff  = $jobend - $jobstart;
                    }

                    //$todate = date('Y-m-d', strtotime("+1 day", strtotime($jobstart)));
                    $hours = floor($jobdiff / (60 * 60));
                    $minutes = $jobdiff - $hours * (60 * 60);
                    $minutes = floor( $minutes / 60 );
                    $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                    if($hours <= 9)
                    {
                      $hours = '0'.$hours;
                    }
                    else{
                      $hours = $hours;
                    }
                    if($minutes <= 9)
                    {
                      $minutes = '0'.$minutes;
                    }
                    else{
                      $minutes = $minutes;
                    }
                    if($second <= 9)
                    {
                      $second = '0'.$second;
                    }
                    else{
                      $second = $second;
                    }
                    $jobtime =   $hours.':'.$minutes.':'.$second;

                    $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                    //-----------Job Time End----------------
                    $current_date = date('Y-m-d');
                    if($current_date != $jobs->job_date)
                    {
                      $redcolor = 'color:#f00;';
                    }
                    elseif($jobs->color == 1){
                     $redcolor = 'color:#0f9600';
                    }
                    elseif($jobs->color == 0){
                      $redcolor = 'color:#666';
                    }
                    else{
                      $redcolor = '';
                    }
                    if($jobs->quick_job == 0){
                      $quick_job = 'No';                      
                      if($jobs->color == '1'){
                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$jobs->id.'" style="'.$redcolor.'" data-bulk="'.$jobs->bulk_job.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$jobs->id.'" data-job="'.date('d-M-Y',strtotime($jobs->job_date)).'">Quick Job</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="edit_active_job" data-element="'.$jobs->id.'" style="'.$redcolor.'">Edit Job</a>';
                      }
                      else{
                        $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'" style="'.$redcolor.'" data-bulk="'.$jobs->bulk_job.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'">Quick Job</a>';
                      }
                    }
                    elseif($jobs->stop_time == '00:00:00'){
                      $quick_job = 'Yes'; 
                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>|&nbsp;&nbsp;<a href="javascript:" class="edit_quick_job" data-element="'.$jobs->id.'" style="'.$redcolor.'">Edit Job</a>';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $buttons = '';
                    }

                    $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                    $rate_result = '0';
                    $cost = '0';

                    if(($ratelist)){
                      foreach ($ratelist as $rate) {
                        $job_date = strtotime($jobs->job_date);
                        $from_date = strtotime($rate->from_date);
                        $to_date = strtotime($rate->to_date);

                        if($rate->to_date != '0000-00-00'){                         
                          if($job_date >= $from_date  && $job_date <= $to_date){
                            
                            $rate_result = $rate->cost;                            
                            
                          }
                        }
                        else{
                          if($job_date >= $from_date){
                            $rate_result = $rate->cost;
                          }
                        }
                      }                      
                    }

                    $cost = ($rate_result/60)*$total_minutes;

                    $inv_no = '';
                    if($jobs->job_type !=  0)
                    {
                      $client_id = $jobs->client_id;
                      $get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
                      $get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
                      if(($get_invoice))
                      {
                        $unserialize = unserialize($get_invoice->auto_tasks);
                        if(($unserialize))
                        {
                          foreach($unserialize as $key => $arrayval)
                          {
                            if(in_array($jobs->task_id, $arrayval))
                            {
                              $inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
                            }
                          }
                        }
                      }
                    }


                    $output.='
                    <tr>
                      <td align="left" style="'.$redcolor.'">'.$i.'</td>
                      <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                      <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                      <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                      <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                      <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                      <td align="left" style="'.$redcolor.'">'.$data['start_time'] = date('H:i:s', strtotime($jobs->start_time)).'</td>
                      <td align="left" style="'.$redcolor.'">N/A</td>
                      <td align="left" style="'.$redcolor.'">'.number_format_invoice_without_decimal($rate_result).'</td>
                      <td align="left" style="'.$redcolor.'"><span id="job_bep_refresh_'.$jobs->id.'">'.number_format_invoice_without_decimal($cost).'</span></td>
                      <td align="left" style="'.$redcolor.'">
                      <span id="job_time_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.$negative.' '.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

                      </td>
                      <td align="center" style="'.$redcolor.'">'.$buttons.''.$inv_no.'</td>
                    </tr>';
                      
                      $userid = Session::get('task_job_user');
                      $joblist_child = DB::table('task_job')->where('user_id',$userid)->where('active_id',$jobs->id)->get();
                      $childi = 1;
                      if(($joblist_child)){              
                        foreach ($joblist_child as $child) {
                          if($child->quick_job == 0 || $child->quick_job == 1){
                            if($child->status == 0){
                              $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $child->client_id)->first();
                              if(($client_details) != ''){
                                $client_name = $client_details->company.' ('.$child->client_id.')';
                              }
                              else{
                                $client_name = 'N/A';
                              }
                              $task_details = DB::table('time_task')->where('id', $child->task_id)->first();
                              if(($task_details) != ''){
                                $task_name = $task_details->task_name;
                                $task_type = $task_details->task_type;
                                if($task_type == 0){
                                  $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                                }
                                elseif($task_type == 1){
                                  $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                                }
                                else{
                                  $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                                }
                              }
                              else{
                                $task_name = 'N/A';
                                $task_type = 'N/A';
                              }
                              
                              $created_date = $child->job_created_date;
                              $jobstart = strtotime($created_date.' '.$child->start_time);
                              $jobend   = strtotime($created_date.' '.date('H:i:s'));
                              if($jobend < $jobstart)
                              {
                                if($created_date == date('Y-m-d'))
                                {
                                    $childnegative = '-';
                                    $jobdiff  = $jobstart - $jobend;
                                }
                                else{
                                  $childnegative = '';
                                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                                  $jobdiff  = $jobend - $jobstart;
                                }
                              }
                              else{
                                $childnegative = '';
                                $jobdiff  = $jobend - $jobstart;
                              }

                             
                              
                              //$todate = date('Y-m-d', strtotime("+1 day", strtotime($jobstart)));
                              $hours = floor($jobdiff / (60 * 60));
                              $minutes = $jobdiff - $hours * (60 * 60);
                              $minutes = floor( $minutes / 60 );
                              $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                              if($hours <= 9)
                              {
                                $hours = '0'.$hours;
                              }
                              else{
                                $hours = $hours;
                              }
                              if($minutes <= 9)
                              {
                                $minutes = '0'.$minutes;
                              }
                              else{
                                $minutes = $minutes;
                              }
                              if($second <= 9)
                              {
                                $second = '0'.$second;
                              }
                              else{
                                $second = $second;
                              }
                              $jobtime =   $hours.':'.$minutes.':'.$second;
                              $explode_job_minutes = explode(":",$jobtime);
                              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                              if($child->stop_time != "00:00:00")
                              {
                                $explode_job_minutes = explode(":",$child->job_time);
                                $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                              }
                              //-----------Job Time End----------------
                              $current_date = date('Y-m-d');
                              if($current_date != $child->job_date)
                              {
                                $redcolor = 'color:#f00;';
                              }
                              elseif($child->color == 1){
                               $redcolor = 'color:#0f9600';
                              }
                              elseif($child->color == 0){
                                $redcolor = 'color:#666';
                              }
                              else{
                                $redcolor = '';
                              }
                              if($child->quick_job == 0){
                                $quick_job = 'No';                      
                                if($child->color == '1'){
                                  $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$child->id.'" style="'.$redcolor.'" data-bulk="'.$jobs->bulk_job.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$child->id.'" data-job="'.date('d-M-Y',strtotime($jobs->job_date)).'">Quick Job</a>';
                                }
                                else{
                                  $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'" style="'.$redcolor.'" data-bulk="'.$jobs->bulk_job.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'">Quick Job</a>';
                                }
                              }
                              elseif($child->stop_time == '00:00:00'){
                                $quick_job = 'Yes'; 
                                $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>|&nbsp;&nbsp;<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
                              }
                              else{
                                $quick_job = 'Yes'; 
                                $buttons = '<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
                              }

                              $ratelist_child = DB::table('user_cost')->where('user_id', $child->user_id)->get();

                              $rate_child_result = '0';
                              $child_cost = '0';

                              if(($ratelist_child)){
                                foreach ($ratelist_child as $rate_child) {
                                  $job_date = strtotime($child->job_date);
                                  $from_date = strtotime($rate_child->from_date);
                                  $to_date = strtotime($rate_child->to_date);

                                  if($rate_child->to_date != '0000-00-00'){                         
                                    if($job_date >= $from_date  && $job_date <= $to_date){
                                      $rate_child_result = $rate_child->cost;                            
                                    }
                                  }
                                  else{
                                    if($job_date >= $from_date){
                                      $rate_child_result = $rate_child->cost;
                                    }
                                  }
                                }                      
                              }
                              $child_cost = ($rate_child_result/60)*$total_minutes;

                              $inv_no = '';
                              if($child->job_type !=  0)
                              {
                                $client_id = $child->client_id;
                                $get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
                                $get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
                                if(($get_invoice))
                                {
                                  $unserialize = unserialize($get_invoice->auto_tasks);
                                  if(($unserialize))
                                  {
                                    foreach($unserialize as $key => $arrayval)
                                    {
                                      if(in_array($child->task_id, $arrayval))
                                      {
                                        $inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
                                      }
                                    }
                                  }
                                }
                              }

                          $output.='
                          <tr>
                            <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                            <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                            <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                            <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                            <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->start_time)).'</td>

                            ';
                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->stop_time)).'</td>';
                            }
                            else{
                              $output.='<td align="left" style="'.$redcolor.'">N/A</td>';
                            }

                            $output.='<td align="left" style="'.$redcolor.'">'.number_format_invoice_without_decimal($rate_child_result).'</td>
                            <td align="left" style="'.$redcolor.'"><span  id="job_bep_refresh_'.$child->id.'">'.number_format_invoice_without_decimal($child_cost).'</span></td>';

                            $output.='<td align="left" style="'.$redcolor.'">';
                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<span style="'.$redcolor.'">'.$child->job_time.' ('.$total_minutes.')</span>';
                            }
                            else{
                              $output.='<span id="job_time_refresh_'.$child->id.'" style="'.$redcolor.'">'.$childnegative.' '.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                            }
                            $output.='</td>
                            <td align="center" style="'.$redcolor.'">'.$buttons.' '.$inv_no.'</td>
                          </tr>';
                            $childi++;
                          }
                        }
                      }
                    }
                    $i++;
                  }
                }
              }              
            }
            if($i == 1){
              $output.= '<tr>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="center">Empty</td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        </tr>';
            }
            echo $output;           
            ?>

            

          </tbody>

        </table>

      </div>

      


      <div class="col-lg-12" style="margin-top: 50px; background: #fff; padding: 25px 15px;">

        

         <h3 class="col-lg-12" style="margin: 0px 0px 20px 0px;">Job of the Day / Closed Job</h3>



        <table class="display nowrap own_table_white fullviewtablelist" id="closed_job" width="100%">

          <thead>

            <tr style="background: #fff;">

              <th width="2%" style="text-align: left;">S.No</th>

              <th style="text-align: left;">Client Name</th>

              <th style="text-align: left;">Task Name</th>

              <th style="text-align: left;">Task Type</th>

              <th style="text-align: left;">Quick Break</th>

              <th style="text-align: left;">Date</th>

              <th style="text-align: left;">Start Time</th>   

              <th style="text-align: left;">Job Time</th>

              <th style="text-align: left;">Stop Time</th>

              <th style="text-align: left;">BEB</th>

              <th style="text-align: left;">BEP</th>

              <th style="text-align: center;">Action</th>

          </tr>

          </thead>

          <tbody id="tbody_jobclosed">

            <?php
            if(isset($_GET['timesheet_date']))
            {
              $current_date = $_GET['timesheet_date'];
            }
            else{
              $current_date = date('Y-m-d');
            }
            $output='';
            $i=1;            
            if(($joblist)){
              foreach ($joblist as $jobs) {
                
                if($current_date == $jobs->job_date)
                {
                    if($jobs->status == 1) {
                    $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $jobs->client_id)->first();
                    if(($client_details) != ''){
                      $client_name = $client_details->company.' ('.$jobs->client_id.')';
                    }
                    else{
                      $client_name = 'N/A';
                    }
                    $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();
                    if(($task_details) != ''){
                      $task_name = $task_details->task_name;
                      $task_type = $task_details->task_type;
                      if($task_type == 0){
                        $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                      }
                      else if($task_type == 1){
                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                      }
                      else{
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }
                    if($jobs->quick_job == 0){

                      $quick_job = 'No';

                      $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
                      $quick_minutes = 0;
                      if(($get_quick_jobs))
                      {
                        foreach($get_quick_jobs as $quickjobs_single)
                        {
                          $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
                          $minutes = ((int)$total_quick_jobs_1[0]*60) + ((int)$total_quick_jobs_1[1]) + ((int)$total_quick_jobs_1[2]/60);
                          if($quick_minutes == 0)
                          {
                            $quick_minutes = $minutes;
                          }
                          else{
                            $quick_minutes = $quick_minutes + $minutes;
                          }
                        }
                      }

                      $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                      $break_timee_minutes = 0;
                      if($break_time_min)
                      {
                        $break_timee = explode(':', $break_time_min->break_time);
                        $break_timee_minutes = ((int)$break_timee[0]*60) + ((int)$break_timee[1]) + ((int)$break_timee[2]/60);
                      }


                      $quick_minutes = $quick_minutes + $break_timee_minutes;

                      $job_timee = explode(':', $jobs->job_time);
                      $job_timee_minutes = ((int)$job_timee[0]*60) + ((int)$job_timee[1]) + ((int)$job_timee[2]/60);

                      $job_time_min = $job_timee_minutes - $quick_minutes;
                      $negative = '';
                      if($job_time_min < 0) {
                        $job_time_min = abs($job_time_min);
                        $negative = '-';
                      }
                      
                      if(floor($job_time_min / 60) <= 9)
                      {
                        $h = '0'.floor($job_time_min / 60);
                      }
                      else{
                        $h = floor($job_time_min / 60);
                      }
                      if(($job_time_min - floor($job_time_min / 60) * 60) <= 9)
                      {
                        $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      else{
                        $m = ($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      $job_time = $h.':'.$m.':00';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $job_time = $jobs->job_time;
                    }
                    $explode_job_minutes = explode(":",$job_time);
                    $total_minutes = ((int)$explode_job_minutes[0]*60) + ((int)$explode_job_minutes[1]);

                    if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }

                    $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                    $rate_result = '0';
                    $cost = '0';

                    if(($ratelist)){
                      foreach ($ratelist as $rate) {
                        $job_date = strtotime($jobs->job_date);
                        $from_date = strtotime($rate->from_date);
                        $to_date = strtotime($rate->to_date);

                        if($rate->to_date != '0000-00-00'){                         
                          if($job_date >= $from_date  && $job_date <= $to_date){
                            
                            $rate_result = $rate->cost;                            
                            $cost = ((int)$rate_result/60)*$total_minutes;
                          }
                        }
                        else{
                          if($job_date >= $from_date){
                            $rate_result = $rate->cost;
                            $cost = ((int)$rate_result/60)*$total_minutes;
                          }
                        }
                      }                      
                    }

                    $output.='

                    <tr>

                      <td align="left">'.$i.'</td>

                      <td align="left">'.$client_name.'</td>

                      <td align="left">'.$task_name.'</td>

                      <td align="left">'.$task_type.'</td>

                      <td align="left">'.$quick_job.'</td>

                      <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>

                      <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>

                      <td align="left">'.$negative.' '.$job_time.' ('.$total_minutes.')</td>

                      <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
                      <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
                      <td align="left">'.number_format_invoice_without_decimal($cost).'</td>

                      <td align="center">
                      <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
                      &nbsp;&nbsp;|&nbsp;&nbsp;
                      <a href="javascript:" class="edit_close_job" data-element="'.$jobs->id.'">Edit Job</a>

                        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">

                            <div class="modal-dialog" style="width:20%">

                              <div class="modal-content">

                                <div class="modal-header">

                                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                                  <h4 class="modal-title menu-logo">Comments</h4>

                                </div>

                                <div class="modal-body">
                                  <div style="width:100%;white-space: normal;text-align: left;">
                                  '.$comments.'
                                  </div>
                                </div>

                                <div class="modal-footer">

                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                </div>

                              </div>

                            </div>

                          </div>

                      </td>

                    </tr>';
                    $userid = Session::get('task_job_user');
                    $joblist_child = DB::table('task_job')->where('user_id',$userid)->where('active_id',$jobs->id)->get();
                    $childi=1;            
                      if(($joblist_child)){
                        foreach ($joblist_child as $child) {
                          if(isset($_GET['timesheet_date']))
                          {
                            $current_date = $_GET['timesheet_date'];
                          }
                          else{
                            $current_date = date('Y-m-d');
                          }

                          if($current_date == $child->job_date)
                          {
                              if($child->status == 1 ){
                              $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $child->client_id)->first();
                              if(($client_details) != ''){
                                $client_name = $client_details->company.' ('.$child->client_id.')';
                              }
                              else{
                                $client_name = 'N/A';
                              }
                              $task_details = DB::table('time_task')->where('id', $child->task_id)->first();
                              if(($task_details) != ''){
                                $task_name = $task_details->task_name;
                                $task_type = $task_details->task_type;
                                if($task_type == 0){
                                  $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                                }
                                else if($task_type == 1){
                                  $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                                }
                                else{
                                  $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                                }
                              }
                              else{
                                $task_name = 'N/A';
                                $task_type = 'N/A';
                              }
                              if($child->quick_job == 0){

                                $quick_job = 'No';

                                $job_time = $child->job_time;

                              }

                              else{

                                $quick_job = 'Yes'; 

                                $job_time = $child->job_time;

                              }

                              $explode_job_minutes = explode(":",$job_time);
                              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);



                              if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }

                              $ratechildlist = DB::table('user_cost')->where('user_id', $child->user_id)->get();

                              $rate_child_result = '0';
                              $child_cost = '0';

                              if(($ratechildlist)){
                                foreach ($ratechildlist as $rate_child) {
                                  $job_date = strtotime($child->job_date);
                                  $from_date = strtotime($rate_child->from_date);
                                  $to_date = strtotime($rate_child->to_date);

                                  if($rate_child->to_date != '0000-00-00'){                         
                                    if($job_date >= $from_date  && $job_date <= $to_date){
                                      
                                      $rate_child_result = $rate_child->cost;                            
                                      $child_cost = ($rate_child_result/60)*$total_minutes;
                                    }
                                  }
                                  else{
                                    if($job_date >= $from_date){
                                      $rate_child_result = $rate_child->cost;
                                      $child_cost = ($rate_child_result/60)*$total_minutes;
                                    }
                                  }
                                }                      
                              }

                              if($childi < 10)
                              {
                                $childi = '0'.$childi;
                              }
                              else{
                                $childi = $childi;
                              }

                              $output.='

                              <tr>

                                <td align="left">'.$i.'.'.$childi.'</td>

                                <td align="left">'.$client_name.'</td>

                                <td align="left">'.$task_name.'</td>

                                <td align="left">'.$task_type.'</td>

                                <td align="left">'.$quick_job.'</td>

                                <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>

                                <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>

                                <td align="left">'.$job_time.' ('.$total_minutes.')</td>

                                <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>

                                <td align="left">'.number_format_invoice_without_decimal($rate_child_result).'</td>

                                <td align="left">'.number_format_invoice_without_decimal($child_cost).'</td>

                                <td align="center">
                                <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>

                                &nbsp;&nbsp;|&nbsp;&nbsp;

                                <a href="javascript:" class="edit_close_job" data-element="'.$child->id.'">Edit Job</a>

                                  <div id="comments_'.$child->id.'" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">

                                      <div class="modal-dialog" style="width:20%">

                                        <div class="modal-content">

                                          <div class="modal-header">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title menu-logo">Comments</h4>

                                          </div>

                                          <div class="modal-body">
                                            <div style="width:100%;white-space: normal;text-align: left;">
                                            '.$comments.'
                                            </div>
                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                          </div>

                                        </div>

                                      </div>

                                    </div>

                                </td>

                              </tr>';
                              $childi++;
                              }
                          }
                        }
                      }
                      $i++;
                    }
                }

              }              

            }

            if($i == 1){

              $output.= '<tr>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="right">Empty</td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>

                        </tr>';

            }

            echo $output;

            ?>

          </tbody>

        </table>



      </div>

      <div class="col-lg-12 summary_div" style="margin-top: 50px; background: #fff; padding: 25px 15px;<?php if(Session::has('task_job_user') && Session::get('task_job_user') != "") { echo 'display:block'; } else{ echo 'display:none'; } ?>">
         <h3 class="col-lg-12" style="margin: 0px 0px 20px 0px;">Summary</h3>
         <?php

          if(Session::has('task_job_user') && Session::get('task_job_user') != "")
          {
            if(isset($_GET['timesheet_date']))
            {
              $currentdate = $_GET['timesheet_date'];
            }
            else{
              $currentdate = date('Y-m-d');
            }
            $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('task_job_user'))->first();
            $getdetails_active_jobs = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',0)->where('stop_time','!=','00:00:00')->get();
            $getdetails_active_jobs_num = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('stop_time','00:00:00')->count();


            $quick_jobs_count = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',1)->count();

            $getdetails_quick_jobs = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',1)->get();

            if(isset($_GET['timesheet_date']))
            {
              $his = date('H:i:s');
              $currentdatetime = $_GET['timesheet_date'].' '.$his;
            }
            else{
              $currentdatetime = date('Y-m-d H:i:s');
            }

            $spendminutes = 0;
            $spendquickminutes = 0;
            $primary_active_job_text = '';

            if($getdetails_active_jobs_num > 0)
            {
              $primary_active_job_text = 'Not Available as you have '.$getdetails_active_jobs_num.' active job(s)';
            }
            else{
              if(($getdetails_active_jobs))
              {
                foreach($getdetails_active_jobs as $activejobs)
                {
                  $todaystarttime = strtotime($currentdate.' '.$activejobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$activejobs->stop_time);
                  $diff = $currenttime - $todaystarttime;
                  if($spendminutes == 0) {
                    $spendminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendminutes = $spendminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            if(($getdetails_quick_jobs))
            {
              foreach($getdetails_quick_jobs as $quickjobs)
              {
                if($quickjobs->stop_time == "00:00:00")
                {
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdatetime);

                  if($currenttime < $todaystarttime)
                  {
                    $diff = 0;
                  }
                  else{
                    $diff = $currenttime - $todaystarttime;
                  }

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
                else{
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$quickjobs->stop_time);
                  $diff = $currenttime - $todaystarttime;

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            $actual_primary_job_time = $spendminutes - $spendquickminutes;

            if(floor($actual_primary_job_time / 60) <= 9)
            {
              $h = '0'.floor($actual_primary_job_time / 60);
            }
            else{
              $h = floor($actual_primary_job_time / 60);
            }
            if(($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60) <= 9)
            {
              $m = '0'.($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }
            else{
              $m = ($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }

            if($primary_active_job_text == "")
            {
              if($actual_primary_job_time < 60)
              {
                $summary_total_time = $m.' Minutes';
              }
              else{
                $summary_total_time = $h.':'.$m.' Hours';
              }
            }
            else{
              $summary_total_time = $primary_active_job_text;
            }

            if(floor($spendquickminutes / 60) <= 9)
            {
              $h = '0'.floor($spendquickminutes / 60);
            }
            else{
              $h = floor($spendquickminutes / 60);
            }
            if(($spendquickminutes -   floor($spendquickminutes / 60) * 60) <= 9)
            {
              $m = '0'.($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }
            else{
              $m = ($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }

            if($spendquickminutes < 60)
            {
              $summary_quick_jobs_time = floor($m).' Minutes';
            }
            else{
              $summary_quick_jobs_time = floor($h).':'.floor($m).' Hours';
            }

            echo '<div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on the Primary Active Job : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="active_job_hours">'.$summary_total_time.'</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Quick Jobs : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_quick_jobs_html">'.$quick_jobs_count.'</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on all Quick Jobs : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_time_quick_jobs">'.$summary_quick_jobs_time.'</span></h5>
              </div>
            </div>';

          }

          else{

            echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on the Primary Active Job : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="active_job_hours">0</span> Hours</h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Quick Jobs : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_quick_jobs_html">0</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on all Quick Jobs : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_time_quick_jobs">0</span> Hours</h5>
              </div>
            </div>';
          }

          ?>
      </div>

      <!-- <div class="col-lg-12 summary_div" style="margin-top: 50px; background: #fff; padding: 25px 15px;<?php if(Session::has('task_job_user') && Session::get('task_job_user') != "") { echo 'display:block'; } else{ echo 'display:none'; } ?>">

        

         <h4 class="col-lg-12" style="padding: 0px; font-weight: 600">Summary</h4>



        <?php

      if(Session::has('task_job_user') && Session::get('task_job_user') != "")
      {
        $currentdate = date('Y-m-d');
        $user_details = DB::table('user')->where('user_id',Session::get('task_job_user'))->first();
        $getdetails = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->get();
        $currentdatetime = date('Y-m-d H:i:s');
        $spendminutes = 0;
        $spendhours = 0;
        if(($getdetails))
        {
          foreach($getdetails as $details) {
            if($details->quick_job == 1 || $details->status == 1){

              $todaystarttime = strtotime($currentdate.' '.$details->start_time);
              $currenttime = strtotime($currentdate.' '.$details->stop_time);
                 $diff = $currenttime - $todaystarttime;
                if($spendminutes == 0)
                {
                  $spendminutes = round(abs($diff) / 60,2);
                }
                else
                {
                  $spendminutes = $spendminutes + round(abs($diff) / 60,2);
                }
                if($spendhours == 0)
                {
                  $spendhours = round(abs($diff)/3600, 1);
                }
                else
                {
                  $spendhours = $spendhours + round(abs($diff)/3600, 1);
                }
            }
          }
        }
        else{
          $spendminutes = 0;
          $spendhours = 0;
        }    
        echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-bottom:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of minutes worked for today by <span class="job_username">'.$user_details->firstname.'</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-bottom:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_minutes">'.$spendminutes.'</span> Minutes</h5>
          </div>
        </div>

        <div class="col-lg-12 user_details_div" style="margin-top: 0px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-top:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of hours worked for today by <span class="job_username">'.$user_details->firstname.'</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-top:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_hours">'.$spendhours.'</span> Hours</h5>
          </div>
        </div>';

      }

      else{

        echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-bottom:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of minutes worked for today by <span class="job_username">Ciaran</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-bottom:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_minutes">120</span> Minutes</h5>
          </div>
        </div>

        <div class="col-lg-12 user_details_div" style="margin-top: 0px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-top:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of hours worked for today by <span class="job_username">Ciaran</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-top:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_hours">2</span> Hours</h5>
          </div>
        </div>';
      }

      ?>



      </div> -->



      

    </div>

</div>
<div class="modal_load"></div>
<script>

$("#user_select").change(function(){
  var id = $(this).val();
  $.ajax({
    url:"<?php echo URL::to('user/job_user_filter')?>",
    data:{userid:id},
    dataType:'json',
    success:function(result){
      window.location.reload();
    }
  })

});

</script>





<script>

$(function(){
    $("#client_table").DataTable({
        columns:[
            {
                "sortable": false
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": false
            }
        ],
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
    $("#payemrs_table").DataTable({
        columns:[
            {
                "sortable": false
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": false
            }
        ],
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
    $('#active_job').DataTable({
        dom: 'Bfrtip',
        retrieve: true,
        fixedHeader: {

          headerOffset: 75

        },

        autoWidth: true,

        scrollX: false,

        fixedColumns: false,

        searching: false,

        paging: false,

        info: false

    });

    $('#closed_job').DataTable({
        dom: 'Bfrtip',
        retrieve: true,
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
        columnDefs: [ {
          "targets": 0,
          "orderable": false
        } ]

    });



});

</script>

<script>
$(window).change(function(e) {
  if($(e.target).hasClass('default_task'))
  {
    var value = $(e.target).val();
    $(".client_exclude:checked").parents(".client_tr").find(".client_task").val(value);

    var ival = 0;
    $(".client_exclude:checked").each(function() {
      var value = $(this).parents(".client_tr").find(".client_task").val();
      if(value == "" || value == null || typeof value == null || typeof value === "undefined")
      {
        ival++;
      }
    });
    if(ival > 0)
    {
      alert("The selected Task Type did not apply to one or more selected clients because this task type is not applicable to those clients(Either it is an internal task OR not assigned). Please review this before you stop this Active job");
      return false;
    }
  }
  if($(e.target).hasClass('select_group_clients'))
  {
    var group_id = $(e.target).val();
    if(group_id != "")
    {
      $("body").addClass("loading");
      $(".client_exclude").prop("checked",false);
      $(".client_tr").hide();
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/select_messageus_group'); ?>",
          type:"post",
          data:{group_id:group_id},
          dataType:"json",
          success:function(result)
          {

            $("#selected_grp_name").html(result['group_name']);
            var clientids = result['client_ids'].split(",");
            $.each(clientids, function(index,value) {
              $("#client_tr_"+value).show();
            });
            $("#client_table").show();
              $("#payemrs_table").hide();
            $(".hide_group_div").show();
            $(".client_active").find(".client_exclude:visible").prop("checked",true);
            $(".client_tr").removeClass("blue_clients");

            var checked_client = $(".client_exclude:checked").length;
            $(".clients_selected").html(checked_client);
            if(checked_client == 0)
            {
              $(".minutes_per_client").html("0");
              $("#hidden_minutes_per_client").val("0");
            }
            else
            {
              var available_for_distribution = $(".available_job_time").html();
              var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
              if(minutes_per_client < 1)
              {
                $(".minutes_per_client").html("1");
                $("#hidden_minutes_per_client").val("1");
              }
              else{
                $(".minutes_per_client").html(minutes_per_client.toFixed(2));
                $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
              }
            }
            $(".default_task").val("");
            $(".client_task").val("");
            $("body").removeClass("loading");
          }
        });
      },1000);
    }
  }
  if($(e.target).hasClass('select_presets_clients'))
  {
    var group_id = $(e.target).val();
    if(group_id != "")
    {
      if(group_id == 7)
      {
        $(".client_exclude").prop("checked",false);
        $("#selected_grp_name").html("PAYE MRS Clients");
        $(".hide_group_div").show();
        $("#client_table").hide();
        $("#payemrs_table").show();
        $("#payemrs_table").find(".client_tr").show();
        $("#payemrs_table").find(".client_exclude").prop("checked",true);
        $(".paye_blue_clients").find(".client_exclude").prop("checked",false);

        var checked_client = $(".client_exclude:checked").length;
        $(".clients_selected").html(checked_client);
        if(checked_client == 0)
        {
          $(".minutes_per_client").html("0");
          $("#hidden_minutes_per_client").val("0");
        }
        else
        {
          var available_for_distribution = $(".available_job_time").html();
          var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
          if(minutes_per_client < 1)
          {
            $(".minutes_per_client").html("1");
            $("#hidden_minutes_per_client").val("1");
          }
          else{
            $(".minutes_per_client").html(minutes_per_client.toFixed(2));
            $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
          }
        }
        $(".default_task").val("");
          $(".client_task").val("");
      }
      else{
        $("body").addClass("loading");
        $(".client_exclude").prop("checked",false);
        $(".client_tr").hide();
        setTimeout(function() {
          $.ajax({
            url:"<?php echo URL::to('user/select_presets_group'); ?>",
            type:"post",
            data:{group_id:group_id},
            dataType:"json",
            success:function(result)
            {
              $("#selected_grp_name").html(result['group_name']);
              var clientids = result['client_ids'].split(",");
              $.each(clientids, function(index,value) {
                var splitclient = value.split("||");
                $("#client_tr_"+splitclient[1]).show();
                $("#client_tr_"+splitclient[1]).find(".client_td").css("color",splitclient[0]);
                if(splitclient[0] == "blue")
                {
                  $("#client_tr_"+splitclient[1]).addClass("blue_clients");
                }
              });
              $("#client_table").show();
              $("#payemrs_table").hide();
              $(".hide_group_div").show();
              $(".client_active").find(".client_exclude:visible").prop("checked",true);
              $(".blue_clients").find(".client_exclude").prop("checked",false);
              $(".client_tr").removeClass("blue_clients");

              var checked_client = $(".client_exclude:checked").length;
              $(".clients_selected").html(checked_client);
              if(checked_client == 0)
              {
                $(".minutes_per_client").html("0");
                $("#hidden_minutes_per_client").val("0");
              }
              else
              {
                var available_for_distribution = $(".available_job_time").html();
                var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
                if(minutes_per_client < 1)
                {
                  $(".minutes_per_client").html("1");
                  $("#hidden_minutes_per_client").val("1");
                }
                else{
                  $(".minutes_per_client").html(minutes_per_client.toFixed(2));
                  $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
                }
              }
              $(".default_task").val("");
              $(".client_task").val("");
              $("body").removeClass("loading");
            }
          });
        },1000);
      } 
    }
  }
});
$(window).click(function(e) {
  if($(e.target).hasClass('active_client_list_pms'))
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
  var userval = $("#user_select").val();

  if(userval != "")

  {

    $(".user_details_div").show();

  }

  else{

    $(".user_details_div").hide(); 

  }
  if($(e.target).hasClass('load_time_sheet'))
  {
    var user = $("#user_select").val();
    if(user == "")
    {
      alert("Please select the Users");
    }
    else {
      $(".load_time_sheet_modal").modal("show");
    }
  }
  if($(e.target).hasClass('load_time_sheet_btn'))
  {
    var date = $(".time_sheet_date").val();
    if(date == "")
    {
      $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#f00">Please Select the Date to view the Time Sheet.</p>',fixed:true,width:"800px"});
    }
    else{
      var dateval = date.split('-');
      if(dateval[1] == "Jan") { var month = '01'; }
      else if(dateval[1] == "Feb") { var month = '02'; }
      else if(dateval[1] == "Mar") { var month = '03'; }
      else if(dateval[1] == "Apr") { var month = '04'; }
      else if(dateval[1] == "May") { var month = '05'; }
      else if(dateval[1] == "Jun") { var month = '06'; }
      else if(dateval[1] == "Jul") { var month = '07'; }
      else if(dateval[1] == "Aug") { var month = '08'; }
      else if(dateval[1] == "Sep") { var month = '09'; }
      else if(dateval[1] == "Oct") { var month = '10'; }
      else if(dateval[1] == "Nov") { var month = '11'; }
      else if(dateval[1] == "Dec") { var month = '12'; }
      var datevall = dateval[2]+'-'+month+'-'+dateval[0];
      window.location.replace('<?php echo URL::to('user/time_track?timesheet_date='); ?>'+datevall);
    }
  }
  if($(e.target).hasClass('import_client_select_list'))
  {
    $(".import_bulk_client_modal").modal("show");
    $(".import_file_submit").show();
    $(".bulk_assigned").hide();
    $(".show_client_ids_import").html("");
    $(".show_client_ids_import").hide();
    $("#hidden_bulk_ids").val("");
    $(".import_file").val("");
  }
  if($(e.target).hasClass('import_file_submit'))
  {

      $("body").addClass("loading");
      $('.import_bulk_client_form').ajaxForm({
          dataType:"json",
          success:function(result){
            if(result['error'] > 0)
            {
              if(result['client_errors'] == "")
              {
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#f00">'+result['message']+'</p>',fixed:true,width:"800px"});

                $(".import_file_submit").show();
                $(".bulk_assigned").hide();
                $(".show_client_ids_import").html("");
                $(".show_client_ids_import").hide();
                $("#hidden_bulk_ids").val("");
              }
              else{
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#f00">'+result['message']+'</p>',fixed:true,width:"800px"});
                $(".import_file_submit").show();
                $(".bulk_assigned").hide();
                var ival = "<h4>Client ID's that are Invalid From Imported CSV File:</h4>";
                $.each(result['client_errors'], function(index, value){
                  if(ival == "")
                  {
                    ival = '<div class="col-md-4" style="color:#f00">'+value+'</div>';
                  }
                  else{
                    ival = ival+' '+'<div class="col-md-4" style="color:#f00">'+value+'</div>';
                  }
                });
                $(".show_client_ids_import").html(ival);
                $(".show_client_ids_import").show();
                $("#hidden_bulk_ids").val("");
              }
            }
            else{
              $(".import_file_submit").hide();
              $(".bulk_assigned").show();
              var ival = '<h4>Client IDs From Imported CSV File:</h4><table class="table"> <thead><tr><th>Client Code</th><th>Firstname</th><th>Surname</th><th>Company</th></tr></thead><tbody>';
              var hidden_ival = '';
              var firstname = result['client_firstname'];
              var surname = result['client_surname'];
              var company = result['client_company'];

              $.each(result['client_codes'], function(index, value){
                
                  ival =ival+'<tr><td>'+value+'</td><td>'+firstname[index]+'</td><td>'+surname[index]+'</td><td>'+company[index]+'</td></tr>';
                
                if(hidden_ival == "")
                {
                  hidden_ival = value;
                }
                else{
                  hidden_ival = hidden_ival+','+value;
                }

              });
              ival = ival+'</tbody></table>';
              $(".show_client_ids_import").html(ival);
              $(".show_client_ids_import").show();
              $("#hidden_bulk_ids").val(hidden_ival);  
            }
            //$(".import_bulk_client_modal").modal("hide");
            $("body").removeClass("loading");
          }
      }).submit();
  }
  if($(e.target).hasClass('bulk_assigned'))
  {
    var ids = $("#hidden_bulk_ids").val();
    if(ids == ""){
      alert("There are No Client ID's from Imported CSV File to select");
    }
    else{
      $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to de-select clients not listed here?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
    }
  }
  if($(e.target).hasClass('yes_proceed'))
  {
    $(".client_exclude").prop("checked",false);
    var ids = $("#hidden_bulk_ids").val();
    var idsval = ids.split(",");
    $.each(idsval, function(index,value) {
      $("#client_tr_"+value).find(".client_exclude").prop("checked",true);
    });

    var checked_client = $(".client_exclude:checked").length;
    $(".clients_selected").html(checked_client);
    if(checked_client == 0)
    {
      $(".minutes_per_client").html("0");
      $("#hidden_minutes_per_client").val("0");
    }
    else
    {
      var available_for_distribution = $(".available_job_time").html();
      var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
      if(minutes_per_client < 1)
      {
        $(".minutes_per_client").html("1");
        $("#hidden_minutes_per_client").val("1");
      }
      else{
        $(".minutes_per_client").html(minutes_per_client.toFixed(2));
        $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
      }
    }

    
    $(".import_bulk_client_modal").modal("hide");
    $.colorbox.close();
  }
  if($(e.target).hasClass('no_proceed'))
  {
    $(".import_bulk_client_modal").modal("hide");
    $.colorbox.close();
  }
  if(e.target.id == "stop_active_job")
  {
    e.preventDefault();
    var visible = $(".stop_right_body_class:visible").length;
    if(visible > 0)
    {
      var lengthclient = $(".select_client_groups:checked").length;
      if(lengthclient > 0)
      {
        var checked_clients = $(".client_exclude:checked").length;
        if(checked_clients > 0)
        {
          var ival = 0;
          $(".client_exclude:checked").each(function() {
            var value = $(this).parents(".client_tr").find(".client_task").val();
            if(value == "" || value == null || typeof value == null || typeof value === "undefined")
            {
              ival++;
            }
          });
          if(ival > 0)
          {
            alert("Please select the 'Task Type' for all the selected clients");
            return false;
          }
          else{
            $("#time_job_stop").submit();
          }
        }
        else{
          alert("Please select atleast one client to stop the job");
        }
      }
      else{
        alert("Please select atleast one client to stop the job");
      }
    }
    else{
     $("#time_job_stop").submit();
    }
  }
  if($(e.target).hasClass('round_up'))
  {
    var value = $(".minutes_per_client").html();
    if(Number.isInteger(parseFloat(value)) !== true)
    {
      var minutes = parseInt(value) + 1;
      $(".minutes_per_client").html(minutes);
      $("#hidden_minutes_per_client").val(minutes);
      $("#hidden_round_type").val("1");
    }
    else{
      alert("Rounding has been set to Round Up to the nearest Minute per client selected");
    }
  }
  if($(e.target).hasClass('round_down'))
  {
    var value = $(".minutes_per_client").html();
    if(Number.isInteger(parseFloat(value)) !== true)
    {
      var minutes = parseInt(value);
      $(".minutes_per_client").html(minutes);
      $("#hidden_minutes_per_client").val(minutes);
      $("#hidden_round_type").val("2");
    }
    else{
      alert("Rounding has been set to Round Down to the nearest Minute per client selected");
    }
  }
  if($(e.target).hasClass('select_all_clients'))
  {
    if($(e.target).is(":checked"))
    {
      $(".client_exclude:visible").prop("checked",true);
    }
    else{
      $(".client_exclude").prop("checked",false);
    }

    var checked_client = $(".client_exclude:checked").length;
    $(".clients_selected").html(checked_client);
    if(checked_client == 0)
    {
      $(".minutes_per_client").html("0");
      $("#hidden_minutes_per_client").val("0");
    }
    else
    {
      var available_for_distribution = $(".available_job_time").html();
      var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
      if(minutes_per_client < 1)
      {
        $(".minutes_per_client").html("1");
        $("#hidden_minutes_per_client").val("1");
      }
      else{
        $(".minutes_per_client").html(minutes_per_client.toFixed(2));
        $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
      }
    }
  }
  if($(e.target).hasClass('client_exclude'))
  {
    var checked_client = $(".client_exclude:checked").length;
    $(".clients_selected").html(checked_client);
    if(checked_client == 0)
    {
      $(".minutes_per_client").html("0");
      $("#hidden_minutes_per_client").val("0");
    }
    else
    {
      var available_for_distribution = $(".available_job_time").html();
      var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
      if(minutes_per_client < 1)
      {
        $(".minutes_per_client").html("1");
        $("#hidden_minutes_per_client").val("1");
      }
      else{
        $(".minutes_per_client").html(minutes_per_client.toFixed(2));
        $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
      }
    }
  }
  if(e.target.id == "active_clients_stop")
  {
    var stoptime = $(".stop_time1").val();
    if(stoptime =="")
    {
      alert("Please Enter the StopTime");
      $(".select_client_groups").prop("checked",false);
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/getclientlist_for_timemetask'); ?>",
          type:"post",
          data:{'type':2},
          async:false,
          success:function(result)
          {
            $(".stop_model #client_table").dataTable().fnDestroy();
            $(".stop_model #client_tbody").html(result);
            $(".stop_model #client_table").DataTable({
              columns:[
                  {
                      "sortable": false
                  },
                  {
                      "sortable": true
                  },
                  {
                      "sortable": true
                  },
                  {
                      "sortable": false
                  }
              ],
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false
            });
            $("body").removeClass("loading");
          }
        })
        $(".import_client_select_list").hide();
        $("#selected_grp_name").html("Your Active Clients");
        $(".hide_group_div").show();
        $(".presets_combo").hide();
        $(".groups_combo").hide();
        $("#client_table").show();
        $("#payemrs_table").hide();
        $("#client_tbody").find(".client_tr").show();
        $(".client_exclude").prop("checked",false);
        $("#select_all_clients").prop("checked",false);

        $(".client_active").find(".client_exclude").prop("checked",true);

        var checked_client = $(".client_exclude:checked").length;
        $(".clients_selected").html(checked_client);
        if(checked_client == 0)
        {
          $(".minutes_per_client").html("0");
          $("#hidden_minutes_per_client").val("0");
        }
        else
        {
          var available_for_distribution = $(".available_job_time").html();
          var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
          if(minutes_per_client < 1)
          {
            $(".minutes_per_client").html("1");
            $("#hidden_minutes_per_client").val("1");
          }
          else{
            $(".minutes_per_client").html(minutes_per_client.toFixed(2));
            $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
          }
        }
        $(".default_task").val("");
        $(".client_task").val("");
      },3000);
    }
  }
  if(e.target.id == "bulk_all_clients")
  {
    var stoptime = $(".stop_time1").val();
    if(stoptime =="")
    {
      alert("Please Enter the StopTime");
      $(".select_client_groups").prop("checked",false);
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/getclientlist_for_timemetask'); ?>",
          type:"post",
          data:{'type':1},
          async:false,
          success:function(result)
          {
            $(".stop_model #client_table").dataTable().fnDestroy();
            $(".stop_model #client_tbody").html(result);
            $(".stop_model #client_table").DataTable({
              columns:[
                  {
                      "sortable": false
                  },
                  {
                      "sortable": true
                  },
                  {
                      "sortable": true
                  },
                  {
                      "sortable": false
                  }
              ],
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false
            });
            $("body").removeClass("loading");
          }
        })
        $(".import_client_select_list").show();
        $("#selected_grp_name").html("All Clients");
        $(".hide_group_div").show();
        $(".presets_combo").hide();
        $(".groups_combo").hide();
        $("#client_table").show();
        $("#payemrs_table").hide();
        $("#client_tbody").find(".client_tr").show();
        $(".client_exclude").prop("checked",false);
        $("#select_all_clients").prop("checked",false);

        $(".client_active").find(".client_exclude").prop("checked",true);

        var checked_client = $(".client_exclude:checked").length;
        $(".clients_selected").html(checked_client);
        if(checked_client == 0)
        {
          $(".minutes_per_client").html("0");
          $("#hidden_minutes_per_client").val("0");
        }
        else
        {
          var available_for_distribution = $(".available_job_time").html();
          var minutes_per_client = parseInt(available_for_distribution) / parseInt(checked_client);
          if(minutes_per_client < 1)
          {
            $(".minutes_per_client").html("1");
            $("#hidden_minutes_per_client").val("1");
          }
          else{
            $(".minutes_per_client").html(minutes_per_client.toFixed(2));
            $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
          }
        }

        $(".default_task").val("");
        $(".client_task").val("");        
      },3000);
    }
  }
  if(e.target.id == "presets_clients")
  {
    var stoptime = $(".stop_time1").val();
    if(stoptime =="")
    {
      alert("Please Enter the StopTime");
      $(".select_client_groups").prop("checked",false);
    }
    else{
      $(".import_client_select_list").hide();
      $(".hide_group_div").hide();
      $(".presets_combo").show();
      $(".groups_combo").hide();
      $(".client_exclude").prop("checked",false);
      $("#select_all_clients").prop("checked",false);

      $("select_group_clients").val("");
      $("select_presets_clients").val("");
    }
  }
  if(e.target.id == "groups_clients")
  {
    var stoptime = $(".stop_time1").val();
    if(stoptime =="")
    {
      alert("Please Enter the StopTime");
      $(".select_client_groups").prop("checked",false);
    }
    else{
      $(".import_client_select_list").hide();
      $(".hide_group_div").hide();
      $(".presets_combo").hide();
      $(".groups_combo").show();
      $(".client_exclude").prop("checked",false);
      $("#select_all_clients").prop("checked",false);

      $("select_group_clients").val("");
          $("select_presets_clients").val("");
    }
  }
  if($(e.target).hasClass('create_new')) {

    var userid = $("#user_select").val();

    if(userid == "" || typeof userid === "undefined")

    {

      alert("Please select the Users");

      return false;

    }
    $.ajax({
      url:"<?php echo URL::to('user/check_time_me_user_active_job'); ?>",
      type:"post",
      data:{userid:userid},
      success: function(result)
      {
        if(result > 0)
        {
          alert('You can only have 1 active job! You must stop the current active job before you create a New Active Job') ? "" : location.reload();
        }
        else{
          $(".mark_internal").prop("checked",false);
          $(".mark_internal").prop("disabled",false);
          $(".mark_activeclient").show();
          $("#label_activeclient").show();
          var mark_activelist=$(".mark_activeclient").is(":checked");
          if(mark_activelist){
            $(".mark_activeclient").click();
          }
          $("#hidden_job_type").val("0");
          $(".internal_type").val('1');
          $(".client_search_class").val("");

          $(".client_group").show();

          $(".internal_tasks_group").hide();

          $(".user_id").val(userid);

          $(".create_new_model").modal("show");

          $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });

          $("#quickjob").val('0');

          $(".job_title").html('Create an Active Job');

          $(".job_button_name").val('Create an Active Job');

          $(".stop_group").hide();

          $(".stop_time").prop("required",false);

          

          $(".stop_time").val('');

          $(".start_button").show();

          $(".start_button_quick").hide();

          $(".job_button_name").hide();

          $(".date_group").hide();

          $(".start_group").hide();

          $(".client_search_class").val('');

          $(".tasks_group").hide();

          $(".acive_id").val('');
          $(".taskjob_id").val('');
          $(".add_edit_job").val(0);
        }
      }
    });
  }
  if($(e.target).hasClass('create_bulk_job')) {

    var userid = $("#user_select").val();

    if(userid == "" || typeof userid === "undefined")

    {

      alert("Please select the Users");

      return false;

    }
    $.ajax({
      url:"<?php echo URL::to('user/check_time_me_user_active_job'); ?>",
      type:"post",
      data:{userid:userid},
      success: function(result)
      {
        if(result > 0)
        {
          alert('You can only have 1 active job! You must stop the current active job before you create a New Active Job') ? "" : location.reload();
        }
        else{
          $(".mark_internal").prop("checked",true);
          $(".mark_internal").prop("disabled",true);
          $(".mark_activeclient").hide();
          $("#label_activeclient").hide();
          $(".active_client_group").hide();
          $("#hidden_job_type").val("1");
          $(".internal_type").val('0');
          $(".client_group").hide();
          $(".internal_tasks_group").show();
          $(".tasks_group").hide();
          $(".client_search_class").val('');
          $(".client_search_class").prop("required",false);
          $("#client_search").val('');
          $(".task_details").html('');
          $("#idtask").val('');
          var child_value = $(".tasks_li_internal:first").text();
          $(".task-choose_internal:first-child").text(child_value);
          $(".client_search_class").val("");
          $(".user_id").val(userid);
          $(".create_new_model").modal("show");

          $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });
          $("#quickjob").val('0');
          $(".job_title").html('Create an Even Bulk Job');
          $(".job_button_name").val('Create an Even Bulk Job');
          $(".stop_group").hide();
          $(".stop_time").prop("required",false);
          $(".stop_time").val('');
          $(".start_button").show();
          $(".start_button_quick").hide();
          $(".job_button_name").hide();
          $(".date_group").hide();
          $(".start_group").hide();
          $(".acive_id").val('');
          $(".taskjob_id").val('');
          $(".add_edit_job").val(0);
        }
      }
    });
  }
  if($(e.target).hasClass('create_new_quick')) {

    $("body").addClass('loading');

    var element = $(e.target).attr("data-element");
    var jobdate = $(e.target).attr("data-job");

    $(".acive_id").val(element);
    $(".taskjob_id").val('');
    $(".add_edit_job").val(0);

    $.ajax({

      url:"<?php echo URL::to('user/get_quick_break_details'); ?>",

      type:"get",

      dataType:"json",

      data:{jobid:element},

      success: function(result)

      {
       
        $(".mark_internal").prop("checked",false);
        $(".mark_internal").prop("disabled",false);
        $(".internal_type").val(1);
        var mark_activelist=$(".mark_activeclient").is(":checked");
        $(".mark_activeclient").show();
        $("#label_activeclient").show();
        if(mark_activelist){
          $(".mark_activeclient").click();
        }
        $(".client_search_class").val("");
        $(".client_group").show();
        $(".internal_tasks_group").hide();
        $(".user_id").val(result['userid']);
        $(".hidden_activejob_starttime").val(result['start_time']);
        $(".create_new_model").modal("show");

        $('.modal-dialog').draggable({
  		    handle: ".modal-header"
  		  });

        $("#quickjob").val('1');
        $(".job_title").html('Create a Quick Job');
        $(".job_button_name").val('Create a Quick Job');
        $(".create_dateclass").prop("required",false);
        $(".start_button_quick").attr("data-job",jobdate);
        //$(".stop_time").prop("required",true);



        $(".stop_time").val('');
        $(".date_group").hide();
        $(".start_button_quick").show();
        $(".start_button").hide();
        $(".job_button_name").hide();
        $(".start_group").hide();
        $(".stop_group").hide();
        $(".client_search_class").val('');
        $(".tasks_group").hide();

        $("body").removeClass('loading');
      }

    });

  }

  if($(e.target).hasClass('mark_internal'))

  {

    if($(e.target).is(":checked"))

    {    

      var activelist_checked=$("#mark_activeclient").is(":checked");
      if(activelist_checked){
        $(".mark_activeclient").click();
      }
      $(".active_client_group").hide();
      $(".internal_type").val('0');

      $(".client_group").hide();

      $(".internal_tasks_group").show();

      $(".tasks_group").hide();

      $(".client_search_class").val('');

      $(".client_search_class").prop("required",false);

      $("#client_search").val('');

      $(".task_details").html('');

      $("#idtask").val('');

      var child_value = $(".tasks_li_internal:first").text();

      $(".task-choose_internal:first-child").text(child_value);

    }

    else{

      $(".internal_type").val('1');

      $(".client_group").show();

      $(".internal_tasks_group").hide();

      $(".tasks_group").hide();

      $(".client_search_class").prop("required",true);

      $("#idtask").val('');

    }

  }
  if($(e.target).hasClass('mark_activeclient')){
    if($(e.target).is(":checked")){
      $(".active_client_group").show();
      $(".active_list_dropdown").val('');
      var internal_checked=$("#mark_internal").is(":checked");
      if(internal_checked){
        $(".mark_internal").click();
      }
      $(".client_group").hide();
      $(".internal_type").val('1');
      $(".internal_tasks_group").hide();
      $(".tasks_group").hide();
      $(".client_search_class").val('');
      $(".client_search_class").prop("required",false);
      $("#idtask").val('');
    }
    else{
      $(".active_client_group").hide();
      $(".internal_type").val('1');
      $(".client_group").show();
      $(".internal_tasks_group").hide();
      $(".tasks_group").hide();
      $(".client_search_class").prop("required",true);
      $("#idtask").val('');
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

  if(e.target.id == "stop_job")

  {

    var job_time = $(".calculate_job_time").val();

    if(job_time == "" || typeof job_time === "undefined")

    {

      alert("Please Choose the Stop time to calculate the job time and then proceed with stop button.");

    }

    else{

      $(".breaktime_div").show();

      $(e.target).hide();

      $("#stop_active_job").show();

    }

  }


  if(e.target.id == "stop_job_quick")
  {

    var job_time = $("#calculate_job_time_quick").val();
    if(job_time == "" || typeof job_time === "undefined")
    {
      alert("Please Choose the Stop time to calculate the job time and then proceed with stop button.");
    }   

  }




  if($(e.target).hasClass('add_minutes'))

  {

    $("body").addClass("loading");

    var element = $(e.target).attr("data-element");

    var break_time = $("#break_time_val").val();

    var jobtime = $(".calculate_job_time").val();

    var total_quick_jobs = $("#total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#total_time_minutes_format").css({"color":"#f00"});
          $("#stop_active_job").prop("disabled",true);
        }
        else{
          $("#total_time_minutes_format").css({"color":"#0f9600"});
          $("#stop_active_job").prop("disabled",false);
        }

          $("#break_time").val(result['break_hours']);
          $("#total_breaks").val(result['break_hours_another']);
          $("#break_time_val").val(result['count']);
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);

          $(".total_job_time").html(result['total_job_time_in_minutes']);
          $(".deducted_job_time").html(result['deducted_for_quick']);
          $(".available_job_time").html(result['available_for_distribution']);

          var checked_client = $(".client_exclude:checked").length;
          $(".clients_selected").html(checked_client);
          if(checked_client == 0)
          {
            $(".minutes_per_client").html("0");
            $("#hidden_minutes_per_client").val("0");
          }
          else
          {
            var minutes_per_client = parseInt(result['available_for_distribution']) / parseInt(checked_client);
            if(minutes_per_client < 1)
            {
              $(".minutes_per_client").html("1");
              $("#hidden_minutes_per_client").val("1");
            }
            else{
              $(".minutes_per_client").html(minutes_per_client.toFixed(2));
              $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
            }
          }

          $("body").removeClass("loading");
      }

    })

  }

  if($(e.target).hasClass('edit_add_minutes'))

  {

    $("body").addClass("loading");

    var element = $(e.target).attr("data-element");

    var break_time = $("#edit_break_time_val").val();

    var jobtime = $(".edit_calculate_job_time").val();

    var total_quick_jobs = $("#edit_total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#edit_total_time_minutes_format").css({"color":"#f00"});
          $("#edit_stop_active_job").prop("disabled",true);
        }
        else{
          $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
          $("#edit_stop_active_job").prop("disabled",false);
        }

          $("#edit_break_time").val(result['break_hours']);
          $("#edit_total_breaks").val(result['break_hours_another']);
          $("#edit_break_time_val").val(result['count']);
          $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);

          $("body").removeClass("loading");
      }

    })

  }

  if(e.target.id == "reset_breaktime")

  {
    var element = 0;
    var break_time = 0;

    var jobtime = $(".calculate_job_time").val();

    var total_quick_jobs = $("#total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#total_time_minutes_format").css({"color":"#f00"});
          $("#stop_active_job").prop("disabled",true);
        }
        else{
          $("#total_time_minutes_format").css({"color":"#0f9600"});
          $("#stop_active_job").prop("disabled",false);
        }

          $("#break_time").val('');
          $("#total_breaks").val('');
          $("#break_time_val").val(0);
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);

          $(".total_job_time").html(result['total_job_time_in_minutes']);
          $(".deducted_job_time").html(result['deducted_for_quick']);
          $(".available_job_time").html(result['available_for_distribution']);

          var checked_client = $(".client_exclude:checked").length;
          $(".clients_selected").html(checked_client);
          if(checked_client == 0)
          {
            $(".minutes_per_client").html("0");
            $("#hidden_minutes_per_client").val("0");
          }
          else
          {
            var minutes_per_client = parseInt(result['available_for_distribution']) / parseInt(checked_client);
            if(minutes_per_client < 1)
            {
              $(".minutes_per_client").html("1");
              $("#hidden_minutes_per_client").val("1");
            }
            else{
              $(".minutes_per_client").html(minutes_per_client.toFixed(2));
              $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
            }
          }
          
      }

    })
  }

  if(e.target.id == "edit_reset_breaktime")

  {
    var element = 0;
    var break_time = 0;

    var jobtime = $(".edit_calculate_job_time").val();

    var total_quick_jobs = $("#edit_total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#edit_total_time_minutes_format").css({"color":"#f00"});
          $("#edit_stop_active_job").prop("disabled",true);
        }
        else{
          $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
          $("#edit_stop_active_job").prop("disabled",false);
        }

          $("#edit_break_time").val('');
          $("#edit_total_breaks").val('');
          $("#edit_break_time_val").val(0);
          $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);
      }

    })
  }
  if($(e.target).hasClass('stop_class')) {
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/stop_job_details')?>",
      dataType: "json",
      data:{jobid:id},
      success:function(result){
        var bulk_job = $(e.target).attr("data-bulk");
        if(bulk_job == "1")
        {
          $(".stop_title").html('Stop Even Bulk Job');
          $("#stop_job").val('Set Stop Time');
          $(".idclass").val(result['id']);
          $(".dateclass").val(result['date']);
          $("#total_quick_jobs").val(result['quick_job_times']);
          $(".stop_start_time").val(result['start_time']);
          $(".stop_model").find(".modal-dialog").css("width","70%");
          $(".stop_model").find(".modal-title").html("Stop Even Bulk Job");
          $(".stop_model").find(".stop_left_body_class").removeClass("col-md-12");
          $(".stop_model").find(".stop_left_body_class").addClass("col-md-6");
          $(".stop_model").find(".stop_right_body_class").show();
          $(".hide_group_div").hide();
          $(".groups_combo").hide();
          $(".presets_combo").hide();

          $(".select_client_groups").prop("checked",false);
          $("select_group_clients").val("");
          $("select_presets_clients").val("");

          $(".default_task").val("");
          $(".client_task").val("");

          $(".stop_model").modal("show");

          $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });

          $(".comments").val('');
          $('#stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));
          $('#stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
          $('.stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));
          $('.stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
          $(".stop_time1").val("");
          if(result['alert'] == 1)
          {
            $("#total_time_minutes_format").css({"color":"#f00"});
            $("#stop_active_job").prop("disabled",true);
          }
          else{
            $("#total_time_minutes_format").css({"color":"#0f9600"});
            $("#stop_active_job").prop("disabled",false);
          }
          $(".calculate_job_time").val('');
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);
          $("#break_time").val('');
          $("#total_breaks").val('');
          $("#break_time_val").val(0);
          $(".breaktime_div").hide();
          $("#stop_job").show();
          $("#stop_active_job").hide();
          $(".stop_class").val('Stop Active Job');
        }
        else{
          $(".stop_title").html('Stop Active Job');
          $("#stop_job").val('Stop Active Job');
          $(".idclass").val(result['id']);
          $(".dateclass").val(result['date']);
          $("#total_quick_jobs").val(result['quick_job_times']);
          $(".stop_start_time").val(result['start_time']);

          $(".stop_model").find(".modal-dialog").css("width","30%");
          $(".stop_model").find(".modal-title").html("Stop Active Job");
          $(".stop_model").find(".stop_left_body_class").removeClass("col-md-6");
          $(".stop_model").find(".stop_left_body_class").addClass("col-md-12");
          $(".stop_model").find(".stop_right_body_class").hide();

          $(".hide_group_div").hide();
          $(".groups_combo").hide();
          $(".presets_combo").hide();

          $(".stop_model").modal("show");

          $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });


          $(".comments").val('');
          $('#stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));
          $('#stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
          $('.stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));
          $('.stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
          $(".stop_time1").val("");
          if(result['alert'] == 1)
          {
            $("#total_time_minutes_format").css({"color":"#f00"});
            $("#stop_active_job").prop("disabled",true);
          }
          else{
            $("#total_time_minutes_format").css({"color":"#0f9600"});
            $("#stop_active_job").prop("disabled",false);
          }
          $(".calculate_job_time").val('');
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);
          $("#break_time").val('');
          $("#total_breaks").val('');
          $("#break_time_val").val(0);
          $(".breaktime_div").hide();
          $("#stop_job").show();
          $("#stop_active_job").hide();
          $(".stop_class").val('Stop Active Job');
        }
      }
    })

  }


  if($(e.target).hasClass('stop_class_quick')) {

    var id = $(e.target).attr("data-element");

    $.ajax({

      url:"<?php echo URL::to('user/stop_job_details')?>",

      dataType: "json",

      data:{jobid:id},

      success:function(result){

        $(".idclass").val(result['id']);

        $(".dateclass").val(result['date']);

        $(".stop_start_time").val(result['start_time']);

        $(".stop_quick_model").modal("show");

        $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });

        $(".comments").val('');



        $('#stop_time2').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('#stop_time2').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $('.stop_time2').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('.stop_time2').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $(".stop_time2").val("");

        $("#calculate_job_time_quick").val('');

        

        $("#stop_job").show();

        $("#stop_active_job").hide();

        $(".stop_class").val('Stop Quick Job')
        $(".stop_quick_title").html('Stop Quick Break');
        $("#stop_job_quick").val('Stop Quick Break');

      }

    })

  }





  if($(e.target).hasClass('take_break_class')) {



    var id = $(e.target).attr("data-element"); 

    $(".id_take_break").val(id);    

    $(".take_break_model").modal("show");

    $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });

    $(".select_break_class").val('');

  }



  if($(e.target).hasClass('break_time_class')) {

    $("#break_tbody").dataTable().fnDestroy();

    var id = $(e.target).attr("data-element");

    $.ajax({

      url:"<?php echo URL::to('user/break_time_details')?>",      

      data:{jobid:id},

      success:function(result){

        $(".break_time_details").html(result);

        $(".break_time_model").modal("show");

        $('.modal-dialog').draggable({
		    handle: ".modal-header"
		  });

        $('#break_tbody').DataTable({            

            fixedHeader: {

              headerOffset: 75

            },

            autoWidth: true,

            scrollX: false,

            fixedColumns: false,

            searching: false,

            paging: false,

            info: false,            

        });

      }

    })

  }

  if($(e.target).hasClass('job_button_name'))

  {

    var idtask = $("#idtask").val();

    if($('.tasks_group').is(":visible"))

    {

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the task from the dropdown.");

        return false;

      }

    }

    if($('.internal_tasks_group').is(":visible"))

    {

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the internal task from the dropdown.");

        return false;

      }

    }

  }

  if($(e.target).hasClass('start_button'))

  {

    var internal_job = $(".mark_internal:checked").length;

    var idtask = $("#idtask").val();

    var userid = $("#user_select").val();

    if(internal_job > 0)

    {

     

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the internal task from the dropdown.");

        return false;

      }

      else{
          var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
          $(".create_dateclass").datetimepicker({

             defaultDate: fullDate,

             format: 'L',

             format: 'DD-MMM-YYYY',

          })

          $(".create_startclass").datetimepicker({

             defaultDate: fullDate,

             format: 'HH:mm',

          });

          $("#start_time").datetimepicker({

             defaultDate: fullDate,

             format: 'HH:mm',

          }) 



          

          $(".date_group").show();

          $(".start_group").show();

          $(".start_button").hide();

          $(".job_button_name").show();

          
          $.ajax({
            url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
            type:"post",
            data:{userid:userid},
            success: function(result)
            {
              $(".create_startclass").val(result);
            }
          });
      }

    }

    else{

       $("#create_job_form").valid();

       var client_search_class = $(".client_search_class").val();

       if(client_search_class != "")

       {

          if(idtask == "" || typeof idtask === "undefined")

          {

            alert("Please Select any of the task from the dropdown.");

            return false;

          }

          else{



            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

            

            $(".create_dateclass").datetimepicker({

               defaultDate: fullDate,

               format: 'L',

               format: 'DD-MMM-YYYY',

            })

            $(".create_startclass").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

            });

            $("#start_time").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

            }) 





            

            $(".date_group").show();

            $(".start_group").show();

            $(".start_button").hide();

            $(".job_button_name").show();

            $.ajax({
              url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
              type:"post",
              data:{userid:userid},
              success: function(result)
              {
                $(".create_startclass").val(result);
              }
            });

          }

       } 

      else{
        var active_list = $(".active_list_dropdown").val();
        if(active_list != ''){
          if(idtask == "" || typeof idtask === "undefined")

          {

            alert("Please Select any of the task from the dropdown.");

            return false;

          }

          else{



            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

            

            $(".create_dateclass").datetimepicker({

               defaultDate: fullDate,

               format: 'L',

               format: 'DD-MMM-YYYY',

            })

            $(".create_startclass").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

            });

            $("#start_time").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

            }) 





            

            $(".date_group").show();

            $(".start_group").show();

            $(".start_button").hide();

            $(".job_button_name").show();

            $.ajax({
              url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
              type:"post",
              data:{userid:userid},
              success: function(result)
              {
                $(".create_startclass").val(result);
              }
            });

          }
        }
        else{
          alert('Please select client');
        }
      }
       

    }

  }

  if($(e.target).hasClass('start_button_quick'))

  {    

    if($('.create_startclass').data("DateTimePicker"))

    {

      $('.create_startclass').data("DateTimePicker").destroy();  

    }

    if($('#start_time').data("DateTimePicker"))

    {

      $('#start_time').data("DateTimePicker").destroy();

    }

    var datajob = $(e.target).attr("data-job");
    var userid = $("#user_select").val();
    var active_starttime = $(".hidden_activejob_starttime").val();

    var splittime = active_starttime.split(":");

   

    var internal_job = $(".mark_internal:checked").length;

    var idtask = $("#idtask").val();

    if(internal_job > 0)

    {

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the internal task from the dropdown.");

        return false;

      }

      else{

        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
        var getTime = new Date().toLocaleString("en-GB", {timeZone: "Europe/Dublin"}).split(" ");
        var time = getTime[1];

        time = time.split(':');

        $(".create_dateclass").datetimepicker({

           defaultDate: fullDate,

           format: 'L',

           format: 'DD-MMM-YYYY',

        })



        $(".create_startclass").datetimepicker({

           format: 'LT',

           format: 'HH:mm',

           minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

           maxDate: moment().startOf('day').hour(23).minute(59),

        })



        $('#start_time').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

            minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

            maxDate: moment().startOf('day').hour(23).minute(59),

        });

        $('#stop_time').datetimepicker({

            defaultDate: fullDate,

            format: 'LT',

            format: 'HH:mm',

        });

        if(time[0] < 9)
        {
          var timee = time[0];
        }
        else{
          var timee = time[0];
        }

        if(time[0] < splittime[0])
        {
          $(".create_startclass").val(splittime[0]+':'+splittime[1]);
        }
        else if(time[0] == splittime[0])
        {
          if(time[1] <= splittime[1])
          {
            $(".create_startclass").val(splittime[0]+':'+splittime[1]);
          }
          else{
            $(".create_startclass").val(timee+':'+time[1]);
          }
        }
        else{
          $(".create_startclass").val(timee+':'+time[1]);
        }

        $(".start_group").show();

        $(".stop_group").hide();    

        $(".start_button_quick").hide();

        $(".job_button_name").show(); 

        $(".date_group").show();

        $(".create_dateclass").val(datajob);

        $.ajax({
          url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
          type:"post",
          data:{userid:userid},
          success: function(result)
          {
            $(".create_startclass").val(result);
          }
        });

      }

    }

    else{

      $("#create_job_form").valid();

       var client_search_class = $(".client_search_class").val();

      if(client_search_class != "")

      {

          if(idtask == "" || typeof idtask === "undefined")

          {

            alert("Please Select any of the task from the dropdown.");

            return false;

          }

          else{

            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

            var getTime = new Date().toLocaleString("en-GB", {timeZone: "Europe/Dublin"}).split(" ");
            var time = getTime[1];
            time = time.split(':');

            $(".create_dateclass").datetimepicker({

               defaultDate: fullDate,

               format: 'L',

               format: 'DD-MMM-YYYY',

            })           

            $(".create_startclass").datetimepicker({

               format: 'LT',

               format: 'HH:mm',

               minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

               maxDate: moment().startOf('day').hour(23).minute(59),

            })



            $('#start_time').datetimepicker({

               format: 'LT',

               format: 'HH:mm',

               minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

               maxDate: moment().startOf('day').hour(23).minute(59),

            })

            $('#stop_time').datetimepicker({

                format: 'LT',

                format: 'HH:mm',

            }) 

            if(time[0] < 9)
            {
              var timee = time[0];
            }
            else{
              var timee = time[0];
            }

            $(".create_startclass").val(timee+':'+time[1]);

            $(".start_group").show();

            $(".stop_group").hide();    

            $(".start_button_quick").hide();

            $(".job_button_name").show(); 

            $(".date_group").show();

            $(".create_dateclass").val(datajob);

            $.ajax({
              url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
              type:"post",
              data:{userid:userid},
              success: function(result)
              {
                $(".create_startclass").val(result);
              }
            });

          }

      }
      else{
        var active_list = $(".active_list_dropdown").val();
        if(active_list != ''){
          if(idtask == "" || typeof idtask === "undefined")
          {
            alert("Please Select any of the task from the dropdown.");
            return false;
          }
          else{
            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

            var getTime = new Date().toLocaleString("en-GB", {timeZone: "Europe/Dublin"}).split(" ");
            var time = getTime[1];
            time = time.split(':');

            $(".create_dateclass").datetimepicker({
              defaultDate: fullDate,
              format: 'L',
              format: 'DD-MMM-YYYY',
            })           

            $(".create_startclass").datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
            })



            $('#start_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
            })

            $('#stop_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
            }) 

            if(time[0] < 9)            {
              var timee = time[0];
            }
            else{
              var timee = time[0];
            }

            $(".create_startclass").val(timee+':'+time[1]);
            $(".start_group").show();
            $(".stop_group").hide();    
            $(".start_button_quick").hide();
            $(".job_button_name").show(); 
            $(".date_group").show();
            $(".create_dateclass").val(datajob);

            $.ajax({
              url:"<?php echo URL::to('user/check_last_finished_job_time'); ?>",
              type:"post",
              data:{userid:userid},
              success: function(result)
              {
                $(".create_startclass").val(result);
              }
            });

          }
        }
        else{
          alert('Please select client');
        }
      }

    }

    

  }



  if($(e.target).hasClass('job_time_refresh')){

    

    var editid = $(e.target).attr("data-element");

    

    $.ajax({

      url: "<?php echo URL::to('user/job_time_count_refresh') ?>",

      data:{id:editid},

      type:"post",

      dataType:"json",

      success:function(result){

        $("#job_time_refresh_"+result['id']).html(result['refreshcount']);
        $("#job_bep_refresh_"+result['id']).html(result['bep']);

       }



    });

  }

if($(e.target).hasClass('create_new_quick_altert')){
    alert("Please Stop Quick Job.");
    return false;
}
if($(e.target).hasClass('stop_class_altert')){
    alert("Please Stop Quick Job.");
    return false;
}

if($(e.target).hasClass('edit_quick_job')){  
  $(".stop_group").hide();
  var id = $(e.target).attr("data-element");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
       dataType:"json",
      success:function(result){
        $("body").removeClass("loading");
        $(".breaktime_div_edit_close").hide();      
        if($('.create_startclass').data("DateTimePicker"))
        {
          $('.create_startclass').data("DateTimePicker").destroy();  
        }
        if($('#start_time').data("DateTimePicker"))
        {
          $('#start_time').data("DateTimePicker").destroy();
        }
        $("#hidden_job_id").val(result['id']);
        $(".create_new_model").modal("show");        

        $('.modal-dialog').draggable({
  		    handle: ".modal-header"
  		  });
        $("#hidden_job_id").val(result['id']);
        $(".mark_activeclient").show();
        $("#label_activeclient").show();
        if(result['bulk_job']==1){
          $(".mark_internal").prop("disabled",true);
        }
        else{
          $(".mark_internal").prop("disabled",false);
        }
        if(result['job_type'] == 0) { 
          $(".internal_type").val('0');
          $(".mark_internal").prop("checked",true);
          $(".client_search_class").val('');
          $(".client_search_class").prop("required",false);
          $("#client_search").val('');
          $(".client_group").hide();
          $(".internal_tasks_group").show();
          $(".tasks_group").hide();
          $(".task_details").html('');
          $(".mark_activeclient").prop("checked",false);
          $(".active_client_group").hide();

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose_internal:first-child").text(result['task_name']);
        }
        else{
          $(".internal_type").val('1');
          $(".mark_internal").prop("checked",false);
          if(result['client_from_activelist'] == 0){
            $(".client_search_class").val(result['client_name']);
            $(".client_search_class").prop("required",true);
            $("#client_search").val(result['client_id']);
            $(".client_group").show();
            $(".mark_activeclient").prop("checked",false);
            $(".active_client_group").hide();
          }
          else{      
            $(".client_search_class").val('');
            $(".client_search_class").prop("required",false);
            $("#client_search").val('');
            $(".mark_activeclient").prop("checked",true);
            $(".active_client_group").show();
            $(".client_group").hide();
            $(".active_list_dropdown").val(result['client_id']);
          }
                   
          $(".internal_tasks_group").hide();
          $(".tasks_group").show();
          $(".task_details").html(result['tasks_group']);

           var taskid = result['task_id'];
           $("#idtask").val(taskid);
           $(".task-choose:first-child").text(result['task_name']);
        }
        $("#idtask").val(result['task_id']);
        if(result['quick_job'] == 1) {
          $(".job_title").html('Edit Quick Break');
          $(".job_button_name").val('Edit Quick Break');   
        }
        else{
          $(".job_title").html('Edit Active Job');
          $(".job_button_name").val('Edit Active Job');
        }
        $(".user_id").val(result['user_id']);
        
        $("#quickjob").val(result['quick_job']);

        $(".create_dateclass").prop("required",true);      
        $(".start_group").show();
        var splittime = result['active_start_time'].split(":");
        if(result['stop_time'] == "00:00")
        {
          $('#start_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
                maxDate: moment().startOf('day').hour(23).minute(59),
            });
          $('.create_startclass').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        }
        else{
          var splitstoptime = result['stop_time'].split(":");
          $('#start_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
                maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
            });
          $('.stop_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
                maxDate: moment().startOf('day').hour(23).minute(59),
            });
          $('.create_startclass').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
          });
          $(".stop_group").show();
          $(".stop_time").val(result['stop_time']);
        }
        $(".start_time").prop("required",true);
        $(".create_startclass").val(result['start_time']);
        $(".start_button").hide();
        $(".create_dateclass").val(result['job_date']);

        $(".date_group").show();
        $(".start_button_quick").hide();
        
        $(".job_button_name").show();
        

        $(".add_edit_job").val(1);
        $(".acive_id").val(result['active_id']);
        $(".taskjob_id").val(result['id']);
      }
    })
}
if($(e.target).hasClass('edit_active_job')){  
  $(".stop_group").hide();
  var id = $(e.target).attr("data-element");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
      dataType:"json",
      success:function(result){    
        $("body").removeClass("loading");
        $(".breaktime_div_edit_close").hide();    
        if($('.create_startclass').data("DateTimePicker"))
        {
          $('.create_startclass').data("DateTimePicker").destroy();  
        }
        if($('#start_time').data("DateTimePicker"))
        {
          $('#start_time').data("DateTimePicker").destroy();
        }
        $("#hidden_job_id").val(result['id']);
        $(".create_new_model").modal("show");        

        $('.modal-dialog').draggable({
  		    handle: ".modal-header"
  		  });
        $("#hidden_job_id").val(result['id']);

        if(result['job_type'] == 0) { 
          $(".internal_type").val('0');
          $(".mark_internal").prop("checked",true);
          $(".client_search_class").val('');
          $(".client_search_class").prop("required",false);
          $("#client_search").val('');
          $(".client_group").hide();
          $(".internal_tasks_group").show();
          $(".tasks_group").hide();
          $(".task_details").html('');
          if(result['bulk_job'] == 1){
            $(".mark_internal").prop("disabled",true);
            $(".mark_activeclient").hide();
            $("#label_activeclient").hide();
          }
          else{
            $(".mark_internal").prop("disabled",false);
            $(".mark_activeclient").show();
            $("#label_activeclient").show();
          }
          $(".mark_activeclient").prop("checked",false);
          $(".active_client_group").hide();

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose_internal:first-child").text(result['task_name']);
        }
        else{
          $(".internal_type").val('1');
          $(".mark_internal").prop("checked",false);
          if(result['client_from_activelist'] == 0){
            $(".client_search_class").val(result['client_name']);
            $(".client_search_class").prop("required",true);
            $("#client_search").val(result['client_id']);
            $(".client_group").show();
            $(".mark_activeclient").prop("checked",false);
            $(".active_client_group").hide();
          }
          else{      
            $(".client_search_class").val('');
            $(".client_search_class").prop("required",false);
            $("#client_search").val('');
            $(".mark_activeclient").prop("checked",true);
            $(".active_client_group").show();
            $(".client_group").hide();
            $(".active_list_dropdown").val(result['client_id']);
          }
          $(".internal_tasks_group").hide();
          $(".tasks_group").show();
          $(".task_details").html(result['tasks_group']);

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose:first-child").text(result['task_name']);
        }
        $("#idtask").val(result['task_id']);
        if(result['quick_job'] == 1) {
          $(".job_title").html('Edit Quick Break');
          $(".job_button_name").val('Edit Quick Break');   
        }
        else if(result['bulk_job'] == 1) {
            $(".job_title").html('Edit Bulk Job');
            $(".job_button_name").val('Edit Bulk Job');   
        }
        else{
          $(".job_title").html('Edit Active Job');
          $(".job_button_name").val('Edit Active Job');
        }
        $(".user_id").val(result['user_id']);
      
        $("#quickjob").val(result['quick_job']);

        $(".create_dateclass").prop("required",true);      
        $(".start_group").show();
        $(".start_time").prop("required",true);

        var splitquicktime = result['quick_start_time'].split(":");
        if(splitquicktime == "")
        {
          $('#start_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                maxDate: moment().startOf('day').hour(23).minute(59),
            });
          $('.create_startclass').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        }
        else{
          $('#start_time').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                maxDate: moment().startOf('day').hour(splitquicktime[0]).minute(splitquicktime[1]),
            });
          $('.create_startclass').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              maxDate: moment().startOf('day').hour(splitquicktime[0]).minute(splitquicktime[1]),
          });
        }
        $(".create_startclass").val(result['start_time']);
        $(".start_button").hide();
        $(".create_dateclass").val(result['job_date']);

        $(".date_group").show();
        $(".start_button_quick").hide();
        
        $(".job_button_name").show();

        $('#start_time').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
        });
        $('.create_startclass').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
        });

        $(".add_edit_job").val(1);
        $(".acive_id").val(result['active_id']);
        $(".taskjob_id").val(result['id']);
      }
    })
}


if($(e.target).hasClass('edit_mark_internal'))
  {
    if($(e.target).is(":checked"))
    {    
      $(".internal_type").val('0');
      $(".edit_client_group").hide();
      $(".edit_internal_task_group").show();
      $(".edit_task_group").hide();
      $(".edit_client_name").val('');
      $(".edit_client_name").prop("required",false);
      $(".edit_client_class").val('');
      $(".task_details").html('');
      $("#edit_idtask").val('');
      var child_value = $(".tasks_li_internal:first").text();
      $(".task-choose_internal:first-child").text(child_value);
      $(".edit_active_client_group").hide();
    }
    else{
      $(".internal_type").val('1');
      $(".edit_client_group").show();
      $(".edit_internal_task_group").hide();
      $(".edit_task_group").hide();
      $(".edit_client_name").prop("required",true);
      $("#edit_idtask").val('');
      $(".edit_active_client_group").hide();
    }

  }

  // if($(e.target).hasClass('edit_mark_activeclient'))
  // {
  //   if($(e.target).is(":checked"))
  //   {    
  //     $(".internal_type").val('1');
  //     $(".edit_client_group").hide();
  //     $(".edit_internal_task_group").hide();
  //     $(".edit_task_group").hide();
  //     $(".edit_client_name").prop("required",false);
  //     $("#edit_idtask").val('');
  //     $(".edit_active_client_group").show();
  //   }
  //   else{
  //     $(".internal_type").val('0');
  //     $(".edit_client_group").hide();
  //     $(".edit_internal_task_group").show();
  //     $(".edit_task_group").hide();
  //     $(".edit_client_name").val('');
  //     $(".edit_client_name").prop("required",false);
  //     $(".edit_client_class").val('');
  //     $(".task_details").html('');
  //     $("#edit_idtask").val('');
  //     var child_value = $(".tasks_li_internal:first").text();
  //     $(".task-choose_internal:first-child").text(child_value);
  //     $(".edit_active_client_group").hide();
  //   }

  // }


if($(e.target).hasClass('edit_close_job')){  
  var id = $(e.target).attr("data-element");
  $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
      dataType:"json",
      success:function(result){        
        $(".edit_stop_model").modal("show");
        $("body").removeClass("loading");
        $('.modal-dialog').draggable({
  		    handle: ".modal-header"
  		  });
        if($('.edit_stop_time1').data("DateTimePicker"))
        {
          $('.edit_stop_time1').data("DateTimePicker").destroy();  
        }
        if($('#edit_stop_time1').data("DateTimePicker"))
        {
          $('#edit_stop_time1').data("DateTimePicker").destroy();
        }
        if(result['alert'] == 1)
        {
          $("#edit_total_time_minutes_format").css({"color":"#f00"});
          $("#edit_stop_active_job").prop("disabled",true);
        }
        else{
          $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
          $("#edit_stop_active_job").prop("disabled",false);
        }

        if(result['quick_job'] == 0){
          $(".breaktime_div_edit_close").hide();
          $(".breaktime_div_edit").show();
          $("#edit_total_quick_jobs").val(result['quick_job_times']);
          $("#edit_break_time_val").val(result['total_breaks_minutes']);
          $("#edit_total_breaks").val(result['breaktime']);

          var splitstarttime = result['start_time'].split(":");  
          $('#edit_stop_time1').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
                maxDate: moment().startOf('day').hour(23).minute(59),
            });
          $('.edit_stop_time1').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        }
        else{
          $(".breaktime_div_edit_close").show();
          $("#primary_job_time").val(result['total_time_minutes_format']);
          $(".breaktime_div_edit").hide();

          var splitstarttime = result['start_time'].split(":");  
          var splitstoptime = result['stoptime_till_val'].split(":");  
              
          $('#edit_stop_time1').datetimepicker({
                format: 'LT',
                format: 'HH:mm',
                minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
                maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
            });
          $('.edit_stop_time1').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
          });
        }
        if(result['job_type'] == 0){
          $("#edit_mark_internal").prop("checked",true);
          $(".edit_client_group").hide();
          $(".edit_client_name").prop("required",false);

          var taskid = result['task_id'];
          $("#edit_idtask").val(taskid);
          $(".task-choose_internal:first-child").text(result['task_name']);
          $(".edit_internal_task_group").show();
          $(".edit_task_group").hide();
          $(".edit_active_client_group").hide();
          $(".edit_active_list_dropdown").val('');
            $(".edit_mark_activeclient").prop('checked',false);
        }
        else{
          if(result['client_from_activelist'] == 0){
            $("#edit_mark_internal").prop("checked",false);
            $(".edit_client_group").show();
            $(".edit_client_name").val(result['client_name']);
            $(".edit_client_class").val(result['client_id']);
            $(".edit_client_name").prop("required",true);

            var taskid = result['task_id'];
            $("#edit_idtask").val(taskid);
            $(".task-choose:first-child").text(result['task_name']);
            $(".edit_internal_task_group").hide();
            $(".edit_task_group").show();
            $(".task_details").html(result['tasks_group']);
            $(".edit_active_client_group").hide();
            $(".edit_active_list_dropdown").val('');
            $(".edit_mark_activeclient").prop('checked',false);
          }
          else{
            $("#edit_mark_internal").prop("checked",false);
            $(".edit_client_group").hide();
            $(".edit_client_name").val('');
            $(".edit_client_class").val('');
            $(".edit_client_name").prop("required",false);

            var taskid = result['task_id'];
            $("#edit_idtask").val(taskid);
            $(".task-choose:first-child").text(result['task_name']);
            $(".edit_internal_task_group").hide();
            $(".edit_task_group").show();
            $(".task_details").html(result['tasks_group']);
            
            $(".edit_active_client_group").show();
            $(".edit_active_list_dropdown").val(result['client_id']);
            $(".edit_mark_activeclient").prop('checked',true);
          }
        }

        $(".edit_dateclass").val(result['job_date']);
        $(".edit_start_time").val(result['start_time']);
        $(".edit_stop_time1").val(result['stop_time']);
        $(".edit_calculate_job_time").val(result['job_time']);
        $(".edit_comments").val(result['comments']);
        
        $("#edit_break_time").val(result['breaktime']);
        
        $("#edit_quickjob").val(result['quick_job']);
        
        $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);

        $("#hidden_edit_job_id").val(result['id']);
        
      }
  })
}









});

</script>



<script type="text/javascript">

    $(function () {

        $('#stop_time').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('#stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('#edit_stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.edit_stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('#stop_time2').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.stop_time2').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $("#start_time").on("dp.change", function (e) {

            $('#stop_time').data("DateTimePicker").minDate(e.date);

            $('#stop_time').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        });



        $("#stop_time1").on("dp.hide", function (e) {

            var start_time = $(".stop_start_time").val();

            var stop_time = $(".stop_time1").val();
            if(stop_time == "")
            {

            }
            else{
              var total_quick_jobs = $("#total_quick_jobs").val();
              var total_breaks = $("#total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    if(result['alert'] == 1)
                    {
                      $("#total_time_minutes_format").css({"color":"#f00"});
                      $("#stop_active_job").prop("disabled",true);
                    }
                    else{
                      $("#total_time_minutes_format").css({"color":"#0f9600"});
                      $("#stop_active_job").prop("disabled",false);
                    }
                    $(".calculate_job_time").val(result['jobtime']);
                    $("#total_time_minutes_format").val(result['total_time_minutes_format']);
                    $(".total_job_time").html(result['total_job_time_in_minutes']);
                    $(".deducted_job_time").html(result['deducted_for_quick']);
                    $(".available_job_time").html(result['available_for_distribution']);

                    var checked_client = $(".client_exclude:checked").length;
                    $(".clients_selected").html(checked_client);
                    if(checked_client == 0)
                    {
                      $(".minutes_per_client").html("0");
                      $("#hidden_minutes_per_client").val("0");
                    }
                    else
                    {
                      var minutes_per_client = parseInt(result['available_for_distribution']) / parseInt(checked_client);
                      if(minutes_per_client < 1)
                      {
                        $(".minutes_per_client").html("1");
                        $("#hidden_minutes_per_client").val("1");
                      }
                      else{
                        $(".minutes_per_client").html(minutes_per_client.toFixed(2));
                        $("#hidden_minutes_per_client").val(minutes_per_client.toFixed(2));
                      }
                    }
                  }
                });
            }
        });

        $("#edit_stop_time1").on("dp.hide", function (e) {

            var start_time = $(".edit_start_time").val();

            var stop_time = $(".edit_stop_time1").val();
            if(stop_time == "")
            {

            }
            else{
              var total_quick_jobs = $("#edit_total_quick_jobs").val();
              var total_breaks = $("#edit_total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    if(result['alert'] == 1)
                    {
                      $("#edit_total_time_minutes_format").css({"color":"#f00"});
                      $("#edit_stop_active_job").prop("disabled",true);
                    }
                    else{
                      $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
                      $("#edit_stop_active_job").prop("disabled",false);
                    }
                    $(".edit_calculate_job_time").val(result['jobtime']);
                    $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);
                    $("#total_time_minutes_format").val(result['total_time_minutes_format']);
                    $(".total_job_time").html(result['total_job_time_in_minutes']);
                    $(".deducted_job_time").html(result['deducted_for_quick']);
                    $(".available_job_time").html(result['available_for_distribution']);
                  }
                });
            }
        });


        $("#stop_time2").on("dp.hide", function (e) {

            var start_time = $(".stop_start_time").val();

            var stop_time = $(".stop_time2").val();
            if(stop_time == "")
            {

            }
            else{
                var total_quick_jobs = $("#total_quick_jobs").val();
              var total_breaks = $("#total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    $("#calculate_job_time_quick").val(result['jobtime']);
                  }
                });
            }
        });



        

        // $('.datepicker-only-init').datetimepicker({

        //     widgetPositioning: {

        //         horizontal: 'left'

        //     },

        //     icons: {

        //         time: "fa fa-clock-o",

        //         date: "fa fa-calendar",

        //         up: "fa fa-arrow-up",

        //         down: "fa fa-arrow-down"

        //     },

        //     format: 'L',

        //     format: 'DD-MMM-YYYY',

        // });

    });

</script>

<script>

$(document).ready(function() {    

  $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/timesystem_client_search'); ?>",
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
            url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              if(ui.item.active_status == 2)
              {
                var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
                if(r)
                {
                  $(".task_details").html(result);
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#idtask").val('');
                  $(".tasks_group").show();
                }
                else{
                  $(".client_search_class").val('');
                  $(".task_details").html('');
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#idtask").val('');
                  $(".tasks_group").hide();
                }
              }
              else{
                $(".task_details").html(result);
                $(".task-choose:first-child").text("Select Tasks")
                $("#idtask").val('');
                $(".tasks_group").show();
              }
            }
          })
        }
  });

  $(".edit_client_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/timesystem_client_search'); ?>",
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
          $(".edit_client_class").val(ui.item.id);          
          $.ajax({
            url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              if(ui.item.active_status == 2)
              {
                var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
                if(r)
                {
                  $(".task_details").html(result);
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#edit_idtask").val('');
                  $(".edit_task_group").show();
                }
                else{
                  $(".edit_client_name").val('');
                  $(".task_details").html('');
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#edit_idtask").val('');
                  $(".edit_task_group").hide();
                }
              }
              else{
                $(".task_details").html(result);
                $(".task-choose:first-child").text("Select Tasks")
                $("#edit_idtask").val('');
                $(".edit_task_group").show();
              }
            }
          })
        }
  });

  $(".time_sheet_date").datetimepicker({
     defaultDate: '',
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
});
$(".active_list_dropdown").on("change",function(){
  var client_id=$(this).val();
  var active_status = $(".active_list_dropdown option:selected").data('activestatus');
  $.ajax({
    url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
    data:{value:client_id},
    success: function(result){
      if(active_status == 2)
      {
        var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
        if(r)
        {
          $(".task_details").html(result);
          $(".task-choose:first-child").text("Select Tasks");
          $("#idtask").val('');
          $(".tasks_group").show();
        }
        else{
          $(".client_search_class").val('');
          $(".task_details").html('');
          $(".task-choose:first-child").text("Select Tasks");
          $("#idtask").val('');
          $(".tasks_group").hide();
        }
      }
      else{
        $(".task_details").html(result);
        $(".task-choose:first-child").text("Select Tasks")
        $("#idtask").val('');
        $(".tasks_group").show();
      }
    }
  })
});
$("#bulk_all_clients").on("change", function(){
  
});
</script>



<script type="text/javascript">

/*var fullDate = new Date()

var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);

var currentDate = fullDate.getDate() + "-" + twoDigitMonth + "-" + fullDate.getFullYear();

console.log(currentDate);

$(".currentdate").val(currentDate);*/

</script>



<!-- 





  job_type = Internal Job =0

  job_type = client Job = 1



  quick_job = Big Job =0

  quick_job = Quick Job =1





 -->



@stop