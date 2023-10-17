@extends('userheader')
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

  /*background: #f5f5f5 !important;*/
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
.own_drop_down:hover, .own_drop_down:active, .own_drop_down:focus, .own_drop_down:visited,.own_drop_down:focus-visible,.own_drop_down:focus-within,.own_drop_down:target{
    color: #000 !important;
    background: #e7e7e7 !important;
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
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title"><a href="{{url('user/dashboard')}}" style="float:left;margin-right:15px">Dashboard</a>
      <div class="dropdown" style="float: left;">
          <button class="dropdown-toggle own_drop_down" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="border: 0px;margin-top: 3px;">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" style="width: 300px;left: 10%;" aria-labelledby="dropdownMenu1">
            <li><a href="<?php echo URL::to('user/dashboard'); ?>">Dashboard - User Profile</a></li>
            <li><a href="<?php echo URL::to('user/dashboard_analytics'); ?>">Dashboard - User Statistics </a></li>
            <li class="active"><a href="<?php echo URL::to('user/dashboard_system_summary'); ?>">Dashboard - System Summary </a></li>
            
          </ul>
      </div>
      <a href="javascript:" class="fa fa-cog dashboard_settings_email common_black_button" style="margin-top: -3px;float:right" title="Settings">
        </a>
    </h4>
  </div>
    <div class="row" id="dashboard_tbody">      
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle cmsystem">
            <div class="content" id="cm_tiles">
              <div class="title">Cm System</div>
              <div class="ul_list">
                <ul>
                
                  <li>Total  Clients : </li>
                  <li>Active  Clients : </li>
                  <li><a href="<?php echo URL::to('user/client_account_review'); ?>" style="color:#fff">Client Account Review</a></li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="cm" data-toggle="tooltip" data-placement="bottom" title="Load Data"></a> 
              <img src="<?php echo URL::to('public/assets/images/icon_cm_system.jpg')?>">
            </div>            
          </div>
          <div class="more morecmsystem">
                <a href="<?php echo URL::to('user/client_management'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle week">
            <div class="content" id="week_tiles">
              <div class="title">Weekly Payroll</div>
              <div class="ul_list">
              
                <div class="sub-title">Week #,</div>
                <ul>
                  <li>Completed Tasks : </li>
                  <li>Donot Complete tasks : </li>
                  <li>Incomplete Tasks : </li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="week"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a> 
              <img src="<?php echo URL::to('public/assets/images/icon_week_task.jpg')?>">
            </div>            
          </div>
          <div class="more moreweek">
                <a href="<?php echo URL::to('user/manage_week'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle month">
            <div class="content" id="month_tiles">
              <div class="title">Monthly Payroll</div>
              <div class="ul_list">
                <div class="sub-title">Month #,</div>
                <ul>
                  <li>Completed Tasks :</li>
                  <li>Donot Complete tasks :</li>
                  <li>Incomplete Tasks :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="month"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('public/assets/images/icon_month_task.jpg')?>">
            </div>            
          </div>
          <div class="more moremonth">
                <a href="<?php echo URL::to('user/manage_month'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle p30">
            <div class="content" id="p30_tiles">
              <div class="title">P30 system</div>
              <div class="ul_list">
              
                <div class="sub-title">Month #,</div>
                <ul>
                  <li>Completed Tasks :</li>
                  <li>Incomplete Tasks :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="p30"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('public/assets/images/icon_p30.jpg')?>">
            </div>            
          </div>
          <div class="more morep30">
                <a href="<?php echo URL::to('user/p30'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle vat">
            <div class="content" id="vat_tiles">
              <div class="title">VAT system</div>
              
              <div class="ul_list">                
                <ul>
                  <li>Disabled Clients:</li>
                  <li>Clients With Email:</li>
                  <li>Clients Without Email:</li>
                  <li>Self Managed:</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="vat"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('public/assets/images/icon_vat.jpg')?>">
            </div>            
          </div>
          <div class="more morevat">
                <a href="<?php echo URL::to('user/vat_clients'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle infile">
            <div class="content" id="infile_tiles">
              <div class="title">In Files System</div>
              
              <div class="ul_list">                
                <ul>
                  <li>No. of Clients with In Files :</li>
                  <li>No. of Complete In Files :</li>
                  <li>No. of InComplete In Files :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="infile"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('public/assets/images/infile_icon.jpg')?>">
            </div>            
          </div>
          <div class="more moreinfile">
                <a href="<?php echo URL::to('user/in_file_advance'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="dashboard">
          <div class="dashboard_signle yearend">
            <div class="content" id="yearend_tiles" style="width:100%">
              <div class="title">Yearend System</div>
                <div class="ul_list">      
                  <div class="col-md-4 col-lg-4">
                    <ul>
                    <li class="lifirst" style="text-decoration: underline;">Year : ####</li>
                    <li>Not Started :</li>
                    <li>Inprogress :</li>
                    <li>Completed :</li>
                    </ul>
                  </div>
                </div>
            </div> 
            <a href="javascript:" class="fa fa-refresh load_system" data-element="yearend"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>        
          </div>
          <div class="more moreyearend">
                <a href="<?php echo URL::to('user/year_end_manager'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle crm">
            <div class="content crm_content" id="crm_tiles">
              <div class="title">Client Request Manager</div>
              
              <div class="ul_list">                
                <ul>
                  <li>Total Requests :</li>
                  <li>Total Outstanding Requests :</li>
                  <li>Total Awaiting Approval :</li>
                </ul>
              </div>
            </div>
            <a href="javascript:" class="fa fa-refresh load_system" data-element="crm" data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
          </div>
          <div class="more morecrm">
                <a href="<?php echo URL::to('user/client_request_system'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
    </div>
    <input type="button" class="common_black_button load_all_dashboard_tiles" value="Load All System Data"  style="position: fixed;right:25px;bottom:74px;">
</div>
<div class="modal_load"></div>
@include('user/system_settings')
<script>

$(window).click(function(e) {
  if($(e.target).hasClass('load_system'))
  {
    $("body").addClass("loading");
    var system = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/load_dashboard_tiles'); ?>",
      type:"post",
      data:{system:system},
      success:function(result)
      {
        $("#"+system+"_tiles").html(result);
        $("body").removeClass("loading");
      }
    }); 
  }
  if($(e.target).hasClass('load_all_dashboard_tiles'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_all_dashboard_tiles'); ?>",
      type:"post",
      success:function(result)
      {
        $("#dashboard_tbody").html(result);
        $("body").removeClass("loading");
      }
    });
  }
}); 
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
@stop
