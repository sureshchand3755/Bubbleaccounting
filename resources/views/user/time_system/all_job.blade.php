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

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

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



<div class="content_section" style="margin-bottom:200px">

  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                All Job               
            </h4>
    </div>




<div class="row">
  <div style="clear: both;">
 <?php
  if(Session::has('message')) { ?>
      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

  
  <?php } ?>
  </div> 

  <div class="col-lg-12 text-right">
      <div class="select_button">
        <ul style="float: right;">
        <li><a href="javascript:" class="review_all report_csv">Report CSV</a></li>               
        <li><a href="javascript:" class="add_new report_pdf">Report PDF</a></li>  
        <li><a href="<?php echo URL::to('user/time_track'); ?>">TimeMe Manager</a></li>              
      </ul>
    </div>                        
  </div>

  <div class="col-lg-12">
     <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_overview')?>">Active Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_joboftheday')?>">Job of the day / Close Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_client_review')?>" >Client Review </a></li>
        <li role="presentation" class="active"><a href="javascript:" title="This Tab is not yet optimized">All Jobs </a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/staff_review')?>">Staff Review </a></li>
      </ul>

       <div class="tab-content" style="background: #fff; padding-top: 25px; padding-bottom:15px;">
        <!-- <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div> -->
        <table class="display nowrap fullviewtablelist own_table_white" id="active_job" width="100%">
          <thead>
            <tr style="background: #fff;">
              <th></th>
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">User Name</th>
              <th style="text-align: left;">BEB</th>
              <th style="text-align: left;">Client Name</th>
              <th style="text-align: left;">Task Name</th>
              <th style="text-align: left;">Task Type</th>
              <th style="text-align: left;">Date</th>
              <th style="text-align: left;">Start Time</th>
              <th style="text-align: left;">Stop Time</th>
              <th style="text-align: left;">BEP</th>
              <th style="text-align: left;">Job Time</th>     
              <th style="text-align: left;">Action</th>             
          </tr>
          </thead>
          <tbody id="tbody_active">
            <?php
            $output='';
            $i=1;            
            if(($joblist)){              
              foreach ($joblist as $jobs) {
                
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

                    

                    $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();

                    //-----------Job Time Start----------------

                    $created_date = $jobs->job_created_date;

                    $jobstart = strtotime($created_date.' '.$jobs->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));
                    

                    if($jobend < $jobstart)
                    {
                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                    }

                    $jobdiff  = $jobend - $jobstart;



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

                  if($jobs->status == 0){
                    $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.' ('.$total_minutes.')</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
                  }
                  else if($jobs->status == 1){
                      $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
                        $quick_minutes = 0;
                        if(($get_quick_jobs))
                        {
                          foreach($get_quick_jobs as $quickjobs_single)
                          {
                            $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
                            $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
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
                        if(($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }


                        $quick_minutes = $quick_minutes + $break_timee_minutes;

                        $job_timee = explode(':', $jobs->job_time);
                        $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

                        $job_time_min = $job_timee_minutes - $quick_minutes;

                        if(floor($job_time_min / 60) <= 9)
                        {
                          $h = '0'.floor($job_time_min / 60);
                        }
                        else{
                          $h = floor($job_time_min / 60);
                        }
                        if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
                        {
                          $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
                        }
                        else{
                          $m = ($job_time_min -   floor($job_time_min / 60) * 60);
                        }
                        $explode_job_minutes = explode(":",$h.':'.$m.':00');
                        $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                        $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
                  }
                  else{
                    $job_time_checked = 'N/A';
                  }
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
                          $cost = ($rate_result/60)*$total_minutes;
                        }
                      }
                      else{
                        if($job_date >= $from_date){
                          $rate_result = $rate->cost;
                          $cost = ($rate_result/60)*$total_minutes;
                        }
                      }
                    }                      
                  }


            $output.='
            <tr>
              <td>
              <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
              </td>
              <td align="left">'.$i.'</td>
              <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
              <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($jobs->start_time)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($jobs->stop_time)).'</td>
              <td align="left"><span id="job_bep_refresh_'.$jobs->id.'">'.number_format_invoice_without_decimal($cost).'</td></td>
              <td align="left">'.$job_time_checked.'</td>
              <td align="center">
                <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
                
                  <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
                      <div class="modal-dialog" style="width:20%">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Comments</h4>
                          </div>
                          <div class="modal-body">
                            '.$comments.'
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </td>
              
            </tr>';
            $joblist_child = DB::table('task_job')->where('active_id',$jobs->id)->get();
            $ichild=1;            
            if(($joblist_child)){              
              foreach ($joblist_child as $child) {
                
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

                    

                    $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', $child->user_id)->first();

                    //-----------Job Time Start----------------

                    $created_date = $child->job_created_date;

                    $jobstart = strtotime($created_date.' '.$child->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));
                    

                    if($jobend < $jobstart)
                    {
                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                    }

                    $jobdiff  = $jobend - $jobstart;



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

                    //-----------Job Time End----------------

                    if($child->quick_job == 0 && $child->status == 0){
                      $job_time_checked = '<span id="job_time_refresh_'.$child->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                    }
                    else if($child->quick_job == 1 && $child->status == 0){
                      $job_time_checked = $child->job_time;
                    }
                    else if($child->quick_job == 0 && $child->status == 1){
                      $job_time_checked = $child->job_time;
                    }
                    else{
                      $job_time_checked = $child->job_time;
                    }
                    if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }

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
            $output.='
            <tr>
              <td>
              <input type="checkbox" name="select_jobaaaaa" class="select_jobaaaaa classaaaaa_'.$child->id.'" data-element="'.$child->id.'" value="'.$child->id.'" style="display:none"><label style="display:none">&nbsp</label>
              </td>
              <td align="left">'.$i.'.'.$ichild.'</td>
              <td align="left">'.$user_details->firstname.'</td>
              <td align="left">'.number_format_invoice_without_decimal($rate_child_result).'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($child->start_time)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($child->stop_time)).'</td>
              <td align="left"><span id="job_bep_refresh_'.$child->id.'">'.number_format_invoice_without_decimal($child_cost).'</span></td>
              <td align="left">'.$job_time_checked.'</td>
              <td align="center">
                <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>
                
                  <div id="comments_'.$child->id.'" class="modal fade" role="dialog" >
                      <div class="modal-dialog" style="width:20%">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Comments</h4>
                          </div>
                          <div class="modal-body">
                            '.$comments.'
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </td>
              
            </tr>';
            $ichild++;
          }
        }
              $i++;
                  
              }              
            }
            if($i == 1){
              $output.= '<tr>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>                        
                        <td align="left"></td>
                        <td align="center">Empty</td>
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

<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">All Job Report</h3>

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
<script>
$(function(){
    $('#active_job').DataTable({
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
  /*if($(e.target).hasClass('export_csv')) {
    $("body").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('user/active_job_report_csv'); ?>",
        type:"post",        
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('public/job_file'); ?>/active_job_Report.csv",'active_job_Report.csv');
        }
      });
  }*/


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

        $.ajax({
          url:"<?php echo URL::to('user/all_job_report_csv'); ?>",
          type:"post",
          data:{value:checkedvalue},
          success: function(result)
          {
            SaveToDisk("<?php echo URL::to('public/job_file'); ?>/all_job_Report.csv",'all_job_Report.csv');
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
                    url:"<?php echo URL::to('user/all_job_report_pdf'); ?>",
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
                          url:"<?php echo URL::to('user/all_job_report_pdf_download'); ?>",
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


});
</script>

@stop