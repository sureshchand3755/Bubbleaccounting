@extends('userheader')
@section('content')
<style>
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
body{background-color:#f5f5f5; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}
</style>
<style>
.page_title{color:#000;}
</style>
<div class="content_section">
  <div class="page_title">
    SELECT YEAR
    <?php
    $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
    $current_month_btn = '';
    $payroll_list_btn = '';
    if($year) {
      $current_month = DB::table('pms_month')->where('year',$year->year_id)->orderBy('month_id','desc')->first();
      if($current_month) {
          $current_month_btn = '<a href="'.URL::to('user/select_month/'.base64_encode($current_month->month_id)).'" class="common_black_button">Open Current Month</a>';
          $payroll_list_btn = '<a href="javascript:" class="common_black_button current_payroll_list" style="clear: both;font-size: 16px;font-weight: 600;color: #fff; position: absolute;bottom:8%;text-align: left;left: 15px;z-index: 9999999999999;">Current Payroll List</a>';
      }
    }
    ?>
    
  </div>
    <div class="select_button">
        <ul>
            <?php
            if(($yearlist)){
              foreach($yearlist as $year){
                if($year->year_status == 0){
            ?>
              <li><a href="<?php echo URL::to('user/month_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name?></a></li>
            <?php
                }
              }
            }
            ?>            
        </ul>
        <p style="float:left;clear: both;margin-top:15px"><?php echo $current_month_btn; ?></p>
        <?php echo $payroll_list_btn; ?>
        <p style="clear: both;font-size: 18px;font-weight: 800;color: #000; position: absolute;bottom:8%;text-align: center;
    width: 98%;">You are in Monthly Payroll Task Management</p>
    </div>
</div>
<div class="modal_load"></div>
<script>
  $(window).click(function(e) {
    if($(e.target).hasClass('current_payroll_list'))
    {
      $("body").addClass("loading");
        $.ajax({
          url:"<?php echo URL::to('user/current_payroll_list'); ?>",
          type:"post",
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/current_payroll_lists.csv",'current_payroll_lists.csv');
            $("body").removeClass("loading");
          }
        })
    }
  });
</script>
@stop