@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.12.1/features/scrollResize/dataTables.scrollResize.min.js"></script>
<style>
.fixedHeader-floating th:nth-child(1),
.fixedHeader-floating th:nth-child(2),
.fixedHeader-floating th:nth-child(3),
.fixedHeader-floating th:nth-child(4),
.fixedHeader-floating th:nth-child(5),
.fixedHeader-floating th:nth-child(6){
    position: sticky !important;
    position: -webkit-sticky !important;
    left: 0;
    background: white !important;
    z-index: 50;
}
.fixedHeader-floating th:nth-child(2){
left:105px
}
.fixedHeader-floating th:nth-child(3){
left:400px
}
.fixedHeader-floating th:nth-child(4){
left:555px
}
.fixedHeader-floating th:nth-child(5){
left:737px
}
.fixedHeader-floating th:nth-child(6){
left:840px
}
.modal_load_clients {
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
body.loading_clients {
    overflow: hidden;   
}
body.loading_clients .modal_load_clients {
    display: block;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}
#aml_expand th:nth-child(1), #aml_expand th:nth-child(2), #aml_expand th:nth-child(3), #aml_expand th:nth-child(4), #aml_expand th:nth-child(5), #aml_expand th:nth-child(6) {
    position: sticky;
    left: 0;
    background: white !important;
    z-index: 50;
    top: 84px;
}
#aml_expand th:nth-child(2){
left:107px
}
#aml_expand th:nth-child(3){
left:403px;
}
#aml_expand th:nth-child(4){
left:561px;;
}
#aml_expand th:nth-child(5){
left:743px;
}
#aml_expand th:nth-child(6){
left:847px;
}


#clients_tbody tr{
    /* height:47px; */
}
#clients_tbody td:nth-child(1), #clients_tbody td:nth-child(2), #clients_tbody td:nth-child(3), #clients_tbody td:nth-child(4), #clients_tbody td:nth-child(5), #clients_tbody td:nth-child(6) {
    position: sticky;
    left: 0;
    background: white !important;
}
#clients_tbody td:nth-child(2){
left:107px;
}
#clients_tbody td:nth-child(3){
left:403px;
}
#clients_tbody td:nth-child(4){
left:561px;;
}
#clients_tbody td:nth-child(5){
left:743px;
}
#clients_tbody td:nth-child(6){
left:847px;
}
</style>
<div class="content_section" style="margin-bottom:200px">    
    <div class="page_title" style="z-index:999;">
        <h4 class="col-lg-12 padding_00 new_main_title menu-logo">
            Task Manager - Client Task Analysis         
        </h4>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_manager'); ?>" class="nav-link">
            <spam id="open_task_count">Your Open Tasks (<?php echo count($open_task_count); ?>)</spam>
            <spam id="authored_task_count" style="display:none">Your Authored Tasks (<?php echo count($authored_task_count); ?>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/park_task'); ?>" class="nav-link" id="home-tab">
            <spam id="park_task_count">Park Tasks (<spam id="park_task_count_val"><?php echo count($park_task_count); ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/taskmanager_search'); ?>" class="nav-link">Task Search</a>
        </li>
        <li class="nav-item waves-effect waves-light active" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_analysis'); ?>" class="nav-link" id="profile-tab">Client Task Analysis</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_administration'); ?>" class="nav-link" id="profile-tab">Task Administration</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:15%;text-align: center">
          <a href="<?php echo URL::to('user/task_overview'); ?>" class="nav-link" id="profile-tab">Task Overview</a>
        </li>
    </ul>
    <div style="width: 100%; float: left; background: #fff">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row" style="margin-bottom: 10px;margin-top: 20px;">
                    <div class="col-md-12" style="margin-left:16px;">
                        <input  type="checkbox" class="mark_internal" name="internal" id="mark_internal"><label for="mark_internal" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Task</label>
                    </div>
                    <div class="col-md-6 client_group">
                        <div class="col-md-8">
                            <label>Select Client ID:</label>
                            <input  type="text" class="form-control client_common_search ui-autocomplete-input" name="client_name" placeholder="Enter Client Name / Client ID" style="width:95%; display:inline;" value="" required>
                            <img class="active_client_list_tm" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
                            <input type="hidden" id="client_search_hidden_infile" class="client_search_common" name="clientid" value="" />
                        </div>
                    </div>
                    <div class="col-md-6 internal_tasks_group" style="display: none;">
                        <div class="col-md-8">
                            <label>Select Task:</label>
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
                                    <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                            <input type="hidden" name="idtask" id="idtask" value="">
                            </div>
                        </div>   
                    </div> 
                    <div class="col-md-6">
                        <input type="button" class="common_black_button analyse_tasks" value="Analyse Tasks" disabled style="margin-top:25px; float:right;"> 
                        <input type="button" class="common_black_button load_all" value="Load All" style="margin-top:25px;float:right;"> 
                        <input type="button" class="common_black_button load_task" value="Load Task" style="margin-top:25px;float:right;"> 
                        <div class="col-md-2" style="float:right;">
                        <label>To Date:</label>
                        <input type="text" class="form-control to_date" placeholder="DD-MMM-YYYY" value="">
                        </div>
                        <div class="col-md-2" style="float:right;">
                        <label>From Date:</label>
                        <input type="text" class="form-control from_date" placeholder="DD-MMM-YYYY" value="">
                        </div>
                    </div>
                </div>
                <table class="tablefixed nowrap fullviewtablelist" id="aml_expand" width="100%" style="display:none;">
                    <thead>
                    <tr style="background: #fff;">
                        <th style="text-align: left;">Task ID</th>
                        <th style="text-align: left;">Subject</th>
                        <th style="text-align: left;">Creation Date</th>
                        <th style="text-align: left;width:10%">Completion Date</th>
                        <th style="text-align: left;">Status</th>
                        <th style="text-align: left;">Days</th>
                        <th style="text-align: left; display:none" class="th_date_column">
                            <table width="100%">
                                <!--<thead>-->
                                <!--    <tr style="text-align: left">-->
                                <!--        <td style="border:0px; font-weight:bold;">Date</td>-->
                                <!--    </tr>-->
                                <!--</thead>-->
                                <tbody id="td_date_col"></tbody>
                            </table>
                        </th>                            
                    </tr>
                    </thead>                            
                    <tbody id="clients_tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal_load_clients" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we load all the Tasks created for your selection as this might take 2-3 minutes depending on the amount of data.</p> </div>

<!-- Modal to show overall comments in this tickets -->
<div class="modal fade task_specifics_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:40%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close close_task_specifics" data-dismiss="modal" aria-label="Close"><span class="close_task_specifics" aria-hidden="true">&times;</span></button>
            <div class="row">
              <div class="col-md-11">
                <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Specifics: <spam class="task_title_spec"></spam>
                  <a href="" target="_blank" class="fa fa-expand view_full_task" data-element="" 
                          title="View Task" style="padding-left:15px;font-size:20px;font-weight: 800;float: right;margin-right: -5%;"></a>
                </h4>
              </div>
            </div>
            
            <h5 class="title_task_details" style="font-size:18px;font-weight:600; text-indent: 14px;"></h5>
            <div class="user_ratings_div"></div>
          </div>
          <div class="modal-body" style="min-height: 650px;padding: 5px;">
            <label class="col-md-12" style="padding: 0px;">
              <label style="margin-top:10px">Existing Task Specific Comments:</label>
              <a href="javascript:" class="common_black_button download_pdf_spec" style="float: right;">Download as PDF</a> 
              <img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" class="2bill_image 2bill_image_comments" style="width:32px;margin-left:10px;float:right;margin-top: 4px;display:none" title="this is a 2Bill Task">
            </label>
            <div class="col-md-12" style="padding: 0px;">
              <div class="existing_comments" id="existing_comments" style="width:100%;background: #c7c7c7;padding:10px;min-height:300px;height:620px;overflow-y: scroll;font-size: 16px"></div>
            </div>

          </div>
          <div class="modal-footer" style="padding: 18px 5px;">  
            <input type="hidden" name="hidden_task_id_task_specifics" id="hidden_task_id_task_specifics" value="">
            <input type="hidden" name="show_auto_close_msg" id="show_auto_close_msg" value="">
          </div>
        </div>
  </div>
</div>
<Script>
$(function(){
    
});
$(document).ready(function() {
    $(".from_date").datetimepicker({
        format: 'L',
        format: 'DD-MMM-YYYY',
        widgetPositioning: {
            horizontal: "left",
            vertical: "bottom"
          }
    });
    $(".to_date").datetimepicker({
        format: 'L',
        format: 'DD-MMM-YYYY',
        widgetPositioning: {
            horizontal: "left",
            vertical: "bottom"
          }
    });
});
$(".client_common_search").autocomplete({
    source: function(request, response) {        
      $.ajax({
        url:"<?php echo URL::to('user/client_review_client_common_search'); ?>",
        dataType: "json",
        data: {
            term : request.term
        },
        success: function(data) {
            response(data);
        }
      });
    },
    delay:1000,
    minLength: 1,
    select: function( event, ui ) {
      $("#client_search_hidden_infile").val(ui.item.id);
    }
});
$(window).click(function(e) {
    if($(e.target).hasClass('close_task_specifics')){
         $("body").removeClass("loading");
    }
    if($(e.target).hasClass('task_id'))
    {    
       $("body").addClass("loading");
        var task_id = $(e.target).attr("data-element");
        setTimeout(function() {
          $(".view_full_task").attr("href","<?php echo URL::to('user/view_taskmanager_task'); ?>/"+task_id);
          $(".view_full_task").attr('data-element',task_id);
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
              
	      $("body").removeClass("loading");
            }
          })
        },500);
  
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
    if($(e.target).hasClass('mark_internal'))
    {    
        if($(e.target).is(":checked"))
        {
            $(".client_group").hide();
            $(".internal_tasks_group").show();
            $("#client_search_hidden_infile").val('');
        }
        else{
            $(".client_group").show();
            $(".internal_tasks_group").hide();
            $("#idtask").val('');
        }
    }
    if($(e.target).hasClass('load_all')){
        var client_id = $("#client_search_hidden_infile").val();
        var task_id = $("#idtask").val();
        // var from_date = $(".from_date").val();
        // var to_date = $(".to_date").val();
        const diffInMs   = new Date(to_date) - new Date(from_date)
        const diffInDays = diffInMs / (1000 * 60 * 60 * 24);
        if($(".mark_internal").is(":checked")){
            if(task_id == ''){
                alert("Please select task");
                return false;
            }
        }
        else{
            if(client_id == ''){
                alert("Please select client");
                return false;
            }
        }
        $("body").addClass("loading_clients");
        setTimeout(function() {            
            $.ajax({
                url:"<?php echo URL::to('user/load_task_analysis'); ?>",
                type:"post",
                dataType:"json",
                data:{client_id:client_id,task_id:task_id},
                success:function(result)
                {
                    $("#aml_expand").dataTable().fnDestroy();
                    $("#clients_tbody").html(result['layout']);
                    $(".fullviewtablelist").show();
                    $(".analyse_tasks").prop("disabled",false);
                    $("#td_date_col").html(result['date_layout']);
                    $(".th_date_column").hide();
                    $(".td_date_val_column").hide();
                    $("body").removeClass("loading_clients");
                    $('#aml_expand').DataTable({
                        fixedHeader: {
                            headerOffset: 78
                        },
                        autoWidth: false,
                        scrollX: false,
                        fixedColumns: false,
                        searching: false,
                        paging: false,
                        info: false,
                        order: []
                    });
                }
            });
        },1000);
        
    }
    if($(e.target).hasClass('load_task')){
        var client_id = $("#client_search_hidden_infile").val();
        var task_id = $("#idtask").val();
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        const diffInMs   = new Date(to_date) - new Date(from_date)
        const diffInDays = diffInMs / (1000 * 60 * 60 * 24);
        if($(".mark_internal").is(":checked")){
            if(task_id == ''){
                alert("Please select task");
                return false;
            }
        }
        else{
            if(client_id == ''){
                alert("Please select client");
                return false;
            }
        }        
        if(from_date == ''){
            alert("Please select date range");
            return false;
        }
        else if(to_date == ''){
            alert("Please select date range");
            return false;
        }
        // else if(diffInDays > 120){
        //     alert("Sorry! Please select date range between 120 days");
        //     return false;
        // }
        var r=confirm("Tasks will be loaded based on your selection for the maximum of 120 days. Click OK to Accept or LOAD ALL to Load All the tasks for your selection for the maximum of 120 days");
        if(r){
            $("body").addClass("loading");
            setTimeout(function() {            
                $.ajax({
                    url:"<?php echo URL::to('user/load_task_analysis'); ?>",
                    type:"post",
                    dataType:"json",
                    data:{client_id:client_id,from_date:from_date,to_date:to_date,task_id:task_id},
                    success:function(result)
                    {
                        $("#aml_expand").dataTable().fnDestroy();
                        $("#clients_tbody").html(result['layout']);
                        $(".fullviewtablelist").show();
                        $(".analyse_tasks").prop("disabled",false);
                        $("#td_date_col").html(result['date_layout']);
                        $(".th_date_column").hide();
                        $(".td_date_val_column").hide();
                        $("body").removeClass("loading");
                        $('#aml_expand').DataTable({
                            fixedHeader: {
                                headerOffset: 78
                            },
                            autoWidth: false,
                            scrollX: false,
                            fixedColumns: false,
                            searching: false,
                            paging: false,
                            info: false,
                            order: []
                        });
                    }
                });
            },1000);
        }
        
    }
    if($(e.target).hasClass('analyse_tasks')){
        $("body").addClass("loading");
        setTimeout(function() {             
            $(".th_date_column").show();
            $(".td_date_val_column").show();
            $("body").removeClass("loading");
        },1000);
    }
    if($(e.target).hasClass('tasks_li_internal'))
      {
        var taskid = $(e.target).attr('data-element');
        $("#idtask").val(taskid);
        $(".task-choose_internal:first-child").text($(e.target).text());
      }
});
</script>
@stop
