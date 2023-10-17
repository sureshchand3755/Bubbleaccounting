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
/*body{
  background: #2fd9ff !important;
}*/
.ui-autocomplete{
  z-index:9999999;
}
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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

.report_div{
    position: absolute;
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; line-height: 20px; font-weight: 500 }
.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.ok_button:focus{background: #000; text-decoration: none; color: #fff}
/*.report_csv, .report_pdf{opacity: 1 !important}*/

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
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
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>
<!--*************************************************************************-->
<div class="modal fade edit_job_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL::to('user/time_job_edit')?>" method="post" class="add_new_form" id="create_job_form">
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
                <input type="hidden" id="hidden_search_date" value="" name="hidden_search_date">
            </div>
            <div class="form-group client_group">                
                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" style="width:95%; display:inline;">
                <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
                <input type="hidden" id="client_search" name="clientid" />
            </div>

            <div class="form-group active_client_group" style="display: none;">
                <select class="form-control active_list_dropdown" name="active_list_option">
                  <option value=''>Select from active client list</option>
                  <?php
                    foreach($active_client_list as $list){
                      echo '<option value="'.$list->client_id.'" data-activestatus="'.$list->active.'">'.$list->company.' ('.$list->client_id.')'.'</option>';
                    }
                  ?>
                </select> 
            </div>

            <div class="form-group internal_tasks_group" style="display: none;">                
                <div class="dropdown" style="width: 100%">
                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                  <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
                </a>
                
                <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  
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
                <div class="dropdown" style="width: 100%">
                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                  <span class="task-choose">Select Task</span>  <span class="caret"></span>                          
                </a>
                
                <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  
                  
                </ul>
              </div>
             
            </div>

            <div class="form-group date_group" style="display: none;">
                <label class="input-group datepicker-only-init">
                    <input type="text" class="form-control create_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-calendar"></i>
                    </span>
                </label>
            </div>

            <div class="form-group start_group" style="display: none;">
                <div class='input-group date' id='start_time'>
                    <input type='text' class="form-control create_startclass start_time" placeholder="Select Start Time" name="starttime" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
            </div>

            <div class="form-group stop_group" style="display: none;">
                <div class='input-group date' id='stop_time'>
                    <input type='text' class="form-control stop_time" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
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



            <input type="hidden" id="quickjob" value="" name="quick_job">
            <input type="hidden" value="" class="currentdate" name="" name="">
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
<!--*************************************************************************-->
<div class="content_section" style="margin-bottom:200px">

  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Job of the day / Close Job                
            </h4>
    </div>

<div class="row">
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div>

    <div class="col-lg-3 text-right" style="padding-right: 0px; line-height: 35px;">
      <form action="post">
        <div class="col-lg-8" style="padding: 0px;">
          <div class="form-group date_group">
              <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control" id="search_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
          </div>                  
        </div>
        <div class="col-lg-4">
            <input type="button" class="common_black_button search_button" value="Search" style="float: left;"  name="">
            
        </div>
        @csrf
</form>
    </div>
    <div class="col-lg-9 text-right">
      <div class="select_button">
        <ul style="float: right;">
        <li><a href="javascript:" class="class_report_csv">Report CSV</a></li>               
        <li><a href="javascript:" class="class_report_pdf">Report PDF</a></li> 
        <li><a href="<?php echo URL::to('user/time_track'); ?>">TimeMe Manager</a></li>  
      </ul>
    </div>                        
  </div>


  <div class="col-lg-12">
     <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_overview')?>">Active Job</a></li>
        <li role="presentation" class="active"><a href="<?php echo URL::to('user/time_me_joboftheday')?>" >Job of the day / Close Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_client_review')?>">Client Review </a></li>
        <li role="presentation"><a href="javascript:" title="This Tab is not yet optimized">All Jobs </a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/staff_review')?>">Staff Review </a></li>
      </ul>

       <div class="tab-content" style="background: #fff; padding-top: 25px; padding-bottom: 15px;">
          <div class="filter_text" style="width: 100%; text-align: center; font-size: 15px; font-weight: bold;">Please wait while the Jobs of the day are loaded.</div>
          <div class="table_selectall" style="display: none;">
            <div class="col-lg-2" style="padding: 0px 0px 0px 10px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class">Select all</label></div>
            <table class="display nowrap fullviewtablelist" id="job_oftheday" width="100%">
              <thead>
                <tr style="background: #fff; font-size: 13px;">
                  <th></th>
                  <th width="2%" style="text-align: left;">S.No</th>
                  <th style="text-align: left;">User Name</th>
                  <th style="text-align: left;">BEB</th>
                  <th style="text-align: left;">Client Name</th>
                  <th style="text-align: left;">Task Name</th>
                  <th style="text-align: left;">Task Type</th>
                  <th style="text-align: left;">Quick Job</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Start Time</th>
                  <th style="text-align: left;">Stop Time</th>                                                 
                  <th style="text-align: left;">BEP</th>
                  <th style="text-align: left;">Job Time</th>
                  <th style="text-align: left;">Action</th> 
              </tr>
              </thead>
              <tbody id="tbody_job_oftheday">
                
                
              </tbody>
            </table>
          </div>


      </div>

  </div>
</div>
</div>

    <!-- End  -->
<div class="main-backdrop"><!-- --></div>

<div id="report_pdf_type_two" style="display:none">

<style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
</style>

<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px;">Jobs of the Day &#8211; <spam id="report_timejob_date"><?php echo date('m/d/y')?></spam></h3>

  <table class="table_style">
    <thead>
      <tr style="background: #fff;">        
        <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Stop Time</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</th>             
    </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">
    </tbody>
</table>
</div>


<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script type="text/javascript">
    $(function () {       
        $('.datepicker-only-init').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'L',
            format: 'DD-MMM-YYYY',
        });
    });
</script>

<script>
$(function(){
    $('#job_oftheday').DataTable({
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

});
</script>

<script>
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
  if($(e.target).hasClass('job_button_name'))
  {
    
    var taskid = $("#idtask").val();
    if(taskid == "")
    {
      alert("Please Choose any one of the task from the dropdown.");
      return false;
    }
    
  }
  if($(e.target).hasClass('edit_task'))
  {
    var jobid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_job_details'); ?>",
      dataType:"json",
      data:{ jobid:jobid},
      success: function(result)
      {
        if($('.stop_time').data("DateTimePicker"))
        {
          $('.stop_time').data("DateTimePicker").destroy();  
        }
        if($('#stop_time').data("DateTimePicker"))
        {
          $('#stop_time').data("DateTimePicker").destroy();
        }
      	$("#hidden_job_id").val(result['id']);
          
      	if(result['job_type'] == 0) { 
      		$(".internal_type").val('0');
      		$(".mark_internal").prop("checked",true);
      		$(".client_search_class").val('');
      		$("#client_search").val('');
      		$(".client_group").hide();
      		$(".internal_tasks_group").show();
      		$(".tasks_group").hide();
      		$(".task_details").html('');

      		var taskid = result['task_id'];
          $("#idtask").val(taskid);
          $(".task-choose_internal:first-child").text(result['task_name']);

          $(".mark_activeclient").prop("checked",false);
          $(".active_client_group").hide();
      	}
      	else{
      		$(".internal_type").val('1');
          if(result['client_from_activelist'] == 0) { 
            $(".mark_internal").prop("checked",false);
            $(".client_search_class").val(result['client_name']);
            $("#client_search").val(result['client_id']);
            $(".client_group").show();
            $(".internal_tasks_group").hide();
            $(".tasks_group").show();
            $(".task_details").html(result['tasks_group']);

            var taskid = result['task_id'];
            $("#idtask").val(taskid);
            $(".task-choose:first-child").text(result['task_name']);

            $(".mark_activeclient").prop("checked",false);
            $(".active_client_group").hide();
          }
          else{
            $(".mark_internal").prop("checked",false);
            $(".client_search_class").val('');
            $("#client_search").val('');
            $(".client_group").hide();
            $(".internal_tasks_group").hide();
            $(".tasks_group").show();
            $(".task_details").html(result['tasks_group']);

            var taskid = result['task_id'];
            $("#idtask").val(taskid);
            $(".task-choose:first-child").text(result['task_name']);

            $(".mark_activeclient").prop("checked",true);
            $(".active_client_group").show();
            $(".active_list_dropdown").val(result['client_id']);
          }
      		
      	}
      	$("#idtask").val(result['task_id']);
      	if(result['quick_job'] == 1) {
  			$(".job_title").html('Update Quick Break');
    		$(".job_button_name").val('Update Quick Break');   
  		}
  		else{
  			$(".job_title").html('Update Active Job');
			$(".job_button_name").val('Update Active Job');
  		}
  		$(".user_id").val(result['user_id']);
  		$(".edit_job_model").modal("show");
  		$("#quickjob").val(result['quick_job']);


      if(result['quick_job'] == 0){
         $(".breaktime_div_edit_close").hide();
        var splitstarttime = result['start_time'].split(":");  
        $('#stop_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        $('.stop_time').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
            maxDate: moment().startOf('day').hour(23).minute(59),
        });
      }
      else{
        $(".breaktime_div_edit_close").show();
        $("#primary_job_time").val(result['total_time_minutes_format']);

        var splitstarttime = result['start_time'].split(":");  
        var splitstoptime = result['stoptime_till_val'].split(":");  
            
        $('#stop_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
          });
        $('.stop_time').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
            maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
        });
      }

      $(".create_dateclass").prop("required",true);
      $(".stop_group").show();

  		$(".start_group").show();
  		$(".start_time").prop("required",true);
  		$(".start_time").val(result['start_time']);
  		$(".start_button").hide();
  		$(".create_dateclass").val(result['job_date']);

      $(".stop_time").prop("required",true);
      $(".stop_time").val(result['stop_time']);
      
	    $(".date_group").show();
	    $(".start_button_quick").hide();
	    
	    $(".job_button_name").show();
      }
    });
  }
  if($(e.target).hasClass('search_button')) {
    $("#job_oftheday").dataTable().fnDestroy();
    var date = $("#search_dateclass").val();
    if(date != ''){
        //$("body").addClass("loading");
        $.ajax({
        url:"<?php echo URL::to('user/search_job_of_day')?>",
        data:{value:date},
        //dataType:"json",
        success:function (result){
          $("#report_timejob_date").html(date);
          $("#hidden_search_date").val(date);
          $("#tbody_job_oftheday").html(result);
          $(".table_selectall").show();
          $(".filter_text").hide();
          $("#job_oftheday").removeClass("no-footer");
          $(".class_report_csv").addClass("report_csv");
          $(".class_report_pdf").addClass("report_pdf");
          $()
          $('#job_oftheday').DataTable({
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

        }
      })
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose any date");

    }
  }


  if($(e.target).hasClass('report_csv')) {

    if($(".select_job:checked").length)
      {
        var checkedvalue = '';
        $(".select_job:checked").each(function() {
            var value = $(this).val();
            if(checkedvalue == "")
            {
              checkedvalue = value;
            }

            else{
              checkedvalue = checkedvalue+","+value;
            }
        });
        var date = $("#report_timejob_date").html();
        $.ajax({
          url:"<?php echo URL::to('user/joboftheday_report_csv'); ?>",
          type:"post",
          data:{value:checkedvalue,date:date},
          success: function(result)
          {
            SaveToDisk("<?php echo URL::to('public/job_file'); ?>/joboftheday_Report.csv",'joboftheday_Report.csv');
          }
        });
      }

      else{
        $("body").removeClass("loading");
        alert("Please Choose atleast one Job.");
      }
  }

  if($(e.target).hasClass('report_pdf')) {
     $("#report_pdf_type_two_tbody").html('');
     if($(".select_job:checked").length){
          $("body").addClass("loading");
            var checkedvalue = '';
            var size = 100;
            $(".select_job:checked").each(function() {
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
                    url:"<?php echo URL::to('user/joboftheday_report_pdf'); ?>",
                    type:"post",
                    data:{value:imp},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody").append(result);
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/joboftheday_report_pdf_download'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('public/job_file'); ?>/"+result,result);
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
        alert("Please Choose atleast one Job.");
      }
  }


  if(e.target.id == "select_all_class"){
    if($(e.target).is(":checked"))
    {
      $(".select_job").each(function() {
        $(this).prop("checked",true);
      });
    }

    else{
      $(".select_job").each(function() {
        $(this).prop("checked",false);
      });
    }
  }

   if($(e.target).hasClass('mark_internal'))
  {
    if($(e.target).is(":checked"))
    {      
      $(".internal_type").val('0');
      $(".client_group").hide();
      $(".internal_tasks_group").show();
      $(".tasks_group").hide();
      $(".client_search_class").val('');
      $(".client_search_class").prop("required",false);
      $("#client_search").val('');
      $(".task_details").html('');
      $("#idtask").val('');

      $(".mark_activeclient").prop("checked", false);
      $(".active_client_group").hide();
      $(".mark_internal").prop("checked", true);
    }
    else{
      $(".internal_type").val('1');
      $(".client_group").show();
      $(".internal_tasks_group").hide();
      $(".tasks_group").show();
      $(".client_search_class").prop("required",true);

      $(".mark_activeclient").prop("checked", false);
      $(".active_client_group").hide();
      $(".mark_internal").prop("checked", false);
    }
  }
  if($(e.target).hasClass('mark_activeclient'))
  {
    if($(e.target).is(":checked"))
    {      
      $(".internal_type").val('1');
      $(".client_group").hide();
      $(".internal_tasks_group").hide();
      $(".tasks_group").show();
      $(".client_search_class").prop("required",false);

      $(".mark_activeclient").prop("checked", true);
      $(".active_client_group").show();
      $(".mark_internal").prop("checked", false);      
    }
    else{
      $(".internal_type").val('1');
      $(".client_group").show();
      $(".internal_tasks_group").hide();
      $(".tasks_group").show();
      $(".client_search_class").prop("required",true);      

      $(".mark_activeclient").prop("checked", false);
      $(".active_client_group").hide();
      $(".mark_internal").prop("checked", false); 
    }
  }
  if($(e.target).hasClass('tasks_li'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $(".task-choose:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('tasks_li_internal'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $(".task-choose_internal:first-child").text($(e.target).text());
  }
});
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
	<?php
	if(isset($_GET['date_search']))
	{
		?>
			var dateval = "<?php echo $_GET['date_search']; ?>";
			$.ajax({
		        url:"<?php echo URL::to('user/search_job_of_day')?>",
		        data:{value:dateval},
		        //dataType:"json",
		        success:function (result){
		          $("#hidden_search_date").val(dateval);
		          $("#tbody_job_oftheday").html(result);
		          $(".table_selectall").show();
		          $(".filter_text").hide();
		          $("#job_oftheday").removeClass("no-footer");
		          $(".class_report_csv").addClass("report_csv");
		          $(".class_report_pdf").addClass("report_pdf");

		          window.history.replaceState(null, null, "<?php echo URL::to('user/time_me_joboftheday'); ?>");
		        }
		      })
		<?php
	}
  else{
    ?>

      var dateval = "<?php echo date('d-M-Y'); ?>";
      $("#search_dateclass").val(dateval);
      $.ajax({
            url:"<?php echo URL::to('user/search_job_of_day')?>",
            data:{value:dateval},
            //dataType:"json",
            success:function (result){
              $("#hidden_search_date").val(dateval);
              $("#tbody_job_oftheday").html(result);
              $(".table_selectall").show();
              $(".filter_text").hide();
              $("#job_oftheday").removeClass("no-footer");
              $(".class_report_csv").addClass("report_csv");
              $(".class_report_pdf").addClass("report_pdf");

              window.history.replaceState(null, null, "<?php echo URL::to('user/time_me_joboftheday'); ?>");
            }
          })
    <?php
  }
	?> 
});
  
$(function () {
        $('#start_time').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
        });
        $('#stop_time').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
        });
        $('#stop_time1').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
        });
        $("#start_time").on("dp.change", function (e) {
            $('#stop_time').data("DateTimePicker").minDate(e.date);
            $('#stop_time').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
            
        });
        
        $('.datepicker-only-init').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'L',
            format: 'DD-MMM-YYYY',
        });
    });
</script>
@stop