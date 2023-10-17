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
    SELECT MONTH
    <?php
    $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
    $current_month_btn = '';
    if($year) {
      $current_month = DB::table('pms_month')->where('year',$year->year_id)->orderBy('month_id','desc')->first();
      if($current_month) {
          $current_month_btn = '<a href="'.URL::to('user/select_month/'.base64_encode($current_month->month_id)).'" class="common_black_button">Open Current Month</a>';
      }
    }
    echo $current_month_btn;
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
                <th style="border-bottom: 0px !important;">DATES</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $end_date = $year->end_date;
            $arraydate = array();
            if(($monthlist)){
              foreach($monthlist as $month){
                $start_month_date = date('Y-F-01',strtotime($end_date));
                $end_month_date = date('Y-F-t',strtotime($end_date));

                $explode_start = explode('-',$start_month_date);
                $explode_end = explode('-',$end_month_date);
                $start_month_date = $explode_start[1].' '.$explode_start[2].' '.$explode_start[0];
                $end_month_date = $explode_end[1].' '.$explode_end[2].' '.$explode_end[0];

                array_push($arraydate,$start_month_date.' - '.$end_month_date);
                $end_date = date('Y-m-d', strtotime("+2 days", strtotime(date('Y-m-t',strtotime($end_date)))));
              }
            }
            if(($monthlist)){
                $count = count($arraydate) - 1;
              foreach($monthlist as $month){
                $task_count = DB::table('pms_task')->where('task_month',$month->month_id)->count();
                $task_completed = DB::table('pms_task')->where('task_month',$month->month_id)->where('task_status',1)->count();
                $task_donot_completed = DB::table('pms_task')->where('task_month',$month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
                $task_incomplete = DB::table('pms_task')->where('task_month',$month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
            ?>
            <tr style="text-align:center">
                <td><a href="<?php echo URL::to('user/select_month/'.base64_encode($month->month_id))?>" class="btn">Month <?php echo $month->month?></a></td>
                <td><label><?php echo $task_count; ?></label></td>
                <td><label><?php echo $task_completed; ?></label></td>
                <td><label><?php echo $task_donot_completed; ?></label></td>
                <td><label><?php echo $task_incomplete; ?></label></td>
                <td><label><?php echo $arraydate[$count]; ?></label></td>
            </tr>
            <?php 
            $count = $count - 1;
            } } ?>
          </tbody>            
        </table>
        
    </div>
</div>
@stop