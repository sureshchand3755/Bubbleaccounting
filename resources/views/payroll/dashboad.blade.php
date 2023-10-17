@extends('payrollheader')
@section('content')
<style>
  .dashboard .dashboard_signle .crm_content{
    width:100%;
  }
.dashboard .crm {
    background: #b373a5;
}
.dashboard .morecrm {
    background: #b53098;
}
body{
  background: url('<?php echo URL::to('public/assets/images/gbs-and-co-bubbles.jpg')?>')!important;
}
.dashboard .yearend
{
  background: #2e6da4;
}
.dashboard .moreyearend
{
  background: #004b8c;
}
.lifirst:before
{
      content: none !important;
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
.fa-refresh{
  position: absolute;
  right: 28px;
  font-size: 25px;
  color: #fff;
  top: 7px;
}
.table_bg>tbody>tr>td, .table_bg>tbody>tr>th, .table_bg>tfoot>tr>td, .table_bg>tfoot>tr>th, .table_bg>thead>tr>td{
  color:#000 !important;
  }
</style>

<div class="content_section">
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
  
  <div class="row" id="dashboard_tbody">      
    <?php
    $userid = Session::get('payroll_userid');
    $user_details = DB::table('employer_users')->where('id',$userid)->first();
    $company_details = DB::table('employers')->where('id',$user_details->emp_id)->first();
    ?>
    <div class="col-md-9" style="border-right:1px solid #dfdfdf; min-height:100%">
      <div class="col-md-12">
        <h4>Company Name: <strong><?php echo $company_details->emp_name; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Employer Number: <strong><?php echo $company_details->emp_no; ?></strong></h4>
        <h4>Logged In as: <strong><?php echo $user_details->email; ?></strong></h4>
      </div>
      <div class="col-md-12" style="margin-top:20px">
        <h4>
          <spam style="float:left;margin-top:5px">Select The Year:</spam> 
          <select name="select_payroll_year" class="form-control select_payroll_year" style="float:left;width:20%;margin-left:15px">
            <option value="">Select Year</option>
            <?php
            $yearlist = DB::table('pms_year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->get();
            $default_year = '';
            if(($yearlist)){
              foreach($yearlist as $key => $year) {
                if($key == 0){
                    $default_year = $year->year_id;
                    $selected = 'selected';
                }
                else{
                  $selected = '';
                }
                if($year->year_status == 0) {
                ?>
                <option value="<?php echo $year->year_id; ?>" <?php echo $selected; ?>><?php echo $year->year_name; ?></option>
                <?php
                }
              }
            }
            ?>
          </select>
        </h4>
      </div>
      <div class="col-md-12" style="margin-top:20px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="week-tab" data-toggle="tab" href="#weeklytab" role="tab" aria-controls="weeklytab" aria-selected="true">Weekly Payroll</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="month-tab" data-toggle="tab" href="#monthlytab" role="tab" aria-controls="monthlytab" aria-selected="false">Monthly Payroll</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active in" id="weeklytab" role="tabpanel" aria-labelledby="week-tab">
              <?php
              $year = DB::table('pms_year')->where('year_id',$default_year)->first();
              $weeklist = DB::table('pms_week')->where('year', $default_year)->orderBy('week_id','desc')->get();
              ?>
              <table class="table">
                <thead>
                  <tr class="background_bg">
                      <th style="border-bottom: 0px !important;">Week</th>
                      <th style="border-bottom: 0px !important;">DATES (DD-MMMM-YYYY)</th>
                      <th style="border-bottom: 0px !important;">Action</th>
                  </tr>
                </thead>
                <tbody id="weekly_tbody">
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
                      ?>
                      <tr>
                          <td><a href="<?php echo URL::to('user/select_week/'.base64_encode($week->week_id))?>" class="btn">Week <?php echo $week->week?></a></td>
                          <td><label><?php echo date('d-F-Y', strtotime($arraydate[$count])); ?></label></td>
                          <td><a href="javascript:" data-element="<?php echo $week->week_id; ?>" class="load_week_tasks common_black_button" style="line-height:32px">Load</a></td>
                      </tr>

                      <tr class="hidden_week_tr hidden_week_tr_<?php echo $week->week_id; ?>" style="display:none">
                        <td colspan="3"></td>
                      </tr>
                      <?php 
                      $count = $count - 1;
                  } } ?>
                </tbody>            
              </table>
          </div>
          <div class="tab-pane fade" id="monthlytab" role="tabpanel" aria-labelledby="month-tab">
            <?php
              $year = DB::table('pms_year')->where('year_id',$default_year)->first();
              $monthlist = DB::table('pms_month')->where('year', $default_year)->orderBy('month_id','desc')->get();
              ?>
              <table class="table">
                <thead>
                  <tr class="background_bg">
                      <th style="border-bottom: 0px !important;">Month</th>
                      <th style="border-bottom: 0px !important;">DATES (DD-MMMM-YYYY)</th>
                      <th style="border-bottom: 0px !important;">Action</th>
                  </tr>
                </thead>
                <tbody id="monthly_tbody">
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
                      ?>
                      <tr>
                          <td><a href="<?php echo URL::to('user/select_month/'.base64_encode($month->month_id))?>" class="btn">Month <?php echo $month->month?></a></td>
                          <td><label><?php echo $arraydate[$count]; ?></label></td>
                          <td><a href="javascript:" data-element="<?php echo $month->month_id; ?>" class="load_month_tasks common_black_button" style="line-height:32px">Load</a></td>
                      </tr>
                      <tr class="hidden_month_tr hidden_month_tr_<?php echo $month->month_id; ?>" style="display:none">
                        <td colspan="3"></td>
                      </tr>
                      <?php 
                      $count = $count - 1;
                  } } ?>
                </tbody>            
              </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <h4><strong>Log Listing:</strong></h4>
      <table class="table own-white-table">
        <thead>
          <th>Activity</th>
          <th>Date</th>
          <th>Time</th>
        </thead>
        <tbody id="listing_tbody">
          <?php 
          $logs = DB::table('payroll_log')->where('payroll_user_id',Session::get('payroll_userid'))->orderBy('id','desc')->get();
          if(($logs)){
            foreach($logs as $log){
              echo '<tr>
                <td>'.$log->action.'</td>
                <td>'.date('d F Y', strtotime($log->updatetime)).'</td>
                <td>'.date('H:i:s', strtotime($log->updatetime)).'</td>
              </tr>';
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(window).click(function(e) {
  if($(e.target).hasClass('load_week_tasks')){
    $("body").addClass("loading");
    var weekid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('payroll/load_weekly_payroll_tasks'); ?>",
      type:"post",
      data:{weekid:weekid},
      dataType:"json",
      success:function(result){
        $(".hidden_week_tr").hide();
        $(".hidden_week_tr_"+weekid).find("td").html(result['output']);
        $(".hidden_week_tr_"+weekid).show();

        $("#listing_tbody").html(result['listing']);
        setTimeout(function() {
          $("body").removeClass("loading");
        },1000);
        
      }
    });
  }
  if($(e.target).hasClass('load_month_tasks')){
    $("body").addClass("loading");
    var monthid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('payroll/load_monthly_payroll_tasks'); ?>",
      type:"post",
      data:{monthid:monthid},
      dataType:"json",
      success:function(result){
        $(".hidden_month_tr").hide();
        $(".hidden_month_tr_"+monthid).find("td").html(result['output']);
        $(".hidden_month_tr_"+monthid).show();

        $("#listing_tbody").html(result['listing']);
        setTimeout(function() {
          $("body").removeClass("loading");
        },1000);
      }
    });
  }
})

$(window).change(function(e) {
  if($(e.target).hasClass('select_payroll_year')){
    $("body").addClass("loading");
    var year = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('payroll/load_year_tasks'); ?>",
      type:"post",
      data:{year:year},
      dataType:"json",
      success:function(result){
        $("#weekly_tbody").html(result['weeklist']);
        $("#monthly_tbody").html(result['monthlist']);
        setTimeout(function() {
          $("body").removeClass("loading");
        },1000);
      }
    });
  }
});
</script>
@stop