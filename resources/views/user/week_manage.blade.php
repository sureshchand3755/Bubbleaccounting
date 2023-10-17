@extends('userheader')
@section('content')
<style>
.select_button table tbody tr td a{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#000 !important;
    font-weight:800;
    margin-top:6px;
}
</style>
<style>
body{background-color:#f5f5f5;  
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}

.table_style tr td{border:0px solid #fff; text-align: center; color:#fff !important; border-top: 0px !important; border-bottom: 1px solid #d5d5d5; padding: 8px 0px !important}

.table_style tr td .btn{background: #000; color:#fff; text-shadow: none; font-weight: bold; border-radius: 0px;}
.table_style{border: 0px solid #fff;}
.page_title{color:#000;}
.background_bg{background: #000; color: #fff;}

</style>
<div class="content_section">
  <div class="page_title">
    SELECT WEEK
    <?php
    $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
    $current_week_btn = '';
    if($year) {
      $current_week = DB::table('pms_week')->where('year',$year->year_id)->orderBy('week_id','desc')->first();
      if($current_week) {
          $current_week_btn = '<a href="'.URL::to('user/select_week/'.base64_encode($current_week->week_id)).'" class="common_black_button">Open Current Week</a>';
      }
    }
    echo $current_week_btn;
    ?>
  </div>
    <div class="select_button">
        <table class="table table_bg table_style">
          <thead>
            <tr class="background_bg">
                <th style="border-bottom: 0px !important;">YEAR</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS COMPLETED</th>
                <th style="border-bottom: 0px !important;">NO OF DONOT COMPLETE TASKS</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS INCOMPLETE</th>
                <th style="border-bottom: 0px !important;">DATES (DD-MMMM-YYYY)</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $end_date = $year->end_date;
            $arraydate = array();
            if(($weeklist)){
              foreach($weeklist as $week){
                $explode = explode('-',$end_date);
                $start_date = $explode[1].'-'.$explode[2].'-'.$explode[0];
                array_push($arraydate,$end_date);
                $end_date = date('Y-m-d', strtotime("+7 days", strtotime($end_date)));
              }
            }
            if(($weeklist)){
                $count = count($arraydate) - 1;
              foreach($weeklist as $key => $week){
                $task_count = DB::table('pms_task')->where('task_week',$week->week_id)->count();
                $task_completed = DB::table('pms_task')->where('task_week',$week->week_id)->where('task_status',1)->count();
                $task_donot_completed = DB::table('pms_task')->where('task_week',$week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
                $task_incomplete = DB::table('pms_task')->where('task_week',$week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
            ?>
            <tr>
                <td><a href="<?php echo URL::to('user/select_week/'.base64_encode($week->week_id))?>" class="btn">Week <?php echo $week->week?></a></td>
                <td><label><?php echo $task_count; ?></label></td>
                <td><label><?php echo $task_completed; ?></label></td>
                <td><label><?php echo $task_donot_completed; ?></label></td>
                <td><label><?php echo $task_incomplete; ?></label></td>
                <td><label><?php echo date('d-F-Y', strtotime($arraydate[$count])); ?></label></td>
            </tr>
            <?php 
              $count = $count - 1;
            } } ?>
          </tbody>            
        </table>
        
    </div>
</div>
@stop