@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>

<script src='<?php echo URL::to('public/assets/js/table-fixed-header_cm.js')?>'></script>
<style>

body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
}
.show_incomplete_label{
  cursor:pointer;
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

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}
.table tr td, tr th{font-size: 15px;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
      margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
}
.dropzone .dz-preview p {
  font-size:12px;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
.remove_dropzone_attach_aml{
  color:#f00 !important;
  margin-left:10px;
}
.dropzone_aml_attachments{
    font-size:20px !important;
    color:#fff;
    font-weight:800;
}
.aml_attach_success{
    color:#087d08;
    font-weight:800;
}
.aml_attach_error{
    color:#f00;
    font-weight:800;
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
    z-index:    9999999999999;
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
#task_body .task_tr td {
  vertical-align: middle !important;
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

.blinking{
    animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
    0%{     background: #f00;    }
    49%{    background: #000; }
    50%{    background: #f00; }
    99%{    background:#000;  }
    100%{   background: #f00;    }
}
</style>

<div class="modal fade" id="review_clients_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/review_clients_update')?>"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Review Clients</h4>
          </div>
          <div class="modal-body review_clients_tbody modal_max_height" id="review_clients_tbody">

          </div>
        @csrf
</form>
      </div>
  </div>
</div>
<div class="modal fade" id="import_aml_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import AML Risk Assessment</h4>
          </div>
          <div class="modal-body" style="max-height:400px;overflow-y:scroll">
              <form action="<?php echo URL::to('user/yearend_aml_risk_attachment'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                  <input type="hidden" name="hidden_year_end_year" value="<?php echo $yearid; ?>">
                  <input name="_token" type="hidden" value="">
              @csrf
</form>
          </div>
          <div class="modal-footer">
                <a href="" class="btn btn-primary">Close</a>
          </div>
      </div>
  </div>
</div>







<div class="content_section" style="margin-bottom:200px">

    <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
               <?php echo $yearid; ?> YEAR END MANAGER
        </h4>
    </div>


  <div class="row" style="margin-bottom: 2px;">
<div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 
          <div class="col-lg-5">
              <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" class="show_incomplete_label"><label for="show_incomplete" class="show_incomplete_label">Hide / Unhide Completed Clients</label> 
              <input type="checkbox" name="show_active_clients" id="show_active_clients" value="1" class="show_active_label"><label for="show_active_clients" class="show_active_label">Show Active Client for this Year</label> 
            </div>
            <div class="col-lg-7 text-right"  >
              <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right;">            
                         
                    
                  <li><a href="<?php echo URL::to('user/year_end_manager'); ?>" style="font-size: 13px; font-weight: 500;">Back to Select Year Screen</a></li>
                  <li><a href="javascript:" class="import_aml_risk" style="font-size: 13px; font-weight: 500;">Import AML Risk Assessment</a></li>
                  <li><a href="javascript:" class="export_to_csv common_black_button" style="font-size: 13px; font-weight: 500;">Export to CSV</a></li>
                  <?php
                  $count_setting = DB::table('year_setting')->count();
                  $year = DB::table('year_end_year')->where('year', $yearid)->first();
                  if($year)
                  {
                    $count_year_setting = count(explode(',',$year->setting_id));
                    if($count_setting != $count_year_setting){
                      $reviewbutton = '<li><a href="javascript:" class="update_documents" style="font-size: 13px; font-weight: 500;">Review Clients</a></li>';
                    }
                    elseif($year->setting_id == ''){
                      $reviewbutton = '<li><a href="javascript:" class="update_documents" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                    }
                    else{
                      $reviewbutton = '<li><a href="javascript:" class="review_clients" style="font-size: 13px; font-weight: 500;">Review Clients</a></li>';
                    }
                    echo $reviewbutton;
                    ?>
                    
                    <li><a href="javascript:" class="create_new_year" style="font-size: 13px; font-weight: 500;">Create New Year</a></li>

                    <?php
                    if($count_setting == 0){
                      $button = '<li><a class="no_setting" href="javascript:" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                    }
                    elseif($count_setting != $count_year_setting){
                      $button = '<li><a href="javascript:" class="blinking new_year_end_class" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                    }
                    elseif($year->setting_id == ''){
                      $button = '<li><a href="javascript:" class="blinking new_year_end_class" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                    }
                    else{
                      $button = '<li><a class="setting_same" href="javascript:" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                    }
                    echo $button;
                  }
                  ?>


                  
              </ul>
            </div>                        
  </div>

  


</div>

<div class="table-responsive" style="margin-top: 20px;">
  <table class="table table-fixed-header own_table_white" style="background: #fff">
    <thead>
      <tr>
        <th width="100px" style="text-align:left"><i class="fa fa-sort sort_sno"></i> S.No</th>
        <th width="150px" style="text-align:left"><i class="fa fa-sort sort_clientid"></i>Client Id</th>
        <th width="150px" style="text-align:center">ActiveClient</th>
        <th style="text-align:left"><i class="fa fa-sort sort_firstname"></i> First Name</th>
        <th style="text-align:left"><i class="fa fa-sort sort_lastname"></i> Last Name</th>
        <th style="text-align:left"><i class="fa fa-sort sort_company"></i> Company</th>

        <th style="text-align:left">Type</th>
        <th style="text-align:left">Notes</th>
        <th style="text-align:left">Requests</th>

        <th style="text-align:left"><i class="fa fa-sort sort_status"></i> Status </th>
        <th style="text-align:left;width:170px"> Action </th>
      </tr>   
    </thead>
    <tbody id="task_body">    
          <?php
          $output='';
          $i=1;
          if(($clientslist)){
            foreach ($clientslist as $client) {
              $client_details = DB::table('cm_clients')->where('client_id', $client->client_id)->first();

              $countoutstanding = 0;
              /* $outstanding_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();*/
              $awaiting_request = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();
              $request_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->count();

              $get_req = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->get();
              if(($get_req))
              {
                foreach($get_req as $req)
                {
                    $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                    $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->where('status',0)->count(); 

                    $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                    $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->where('status',0)->count();

                    $check_received_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->where('status',0)->count();

                    $check_received_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->where('status',0)->count();
                    $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->where('status',0)->count();

                    $check_received_others = DB::table('request_others')->where('request_id',$req->request_id)->where('status',0)->count();

                    $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->count();
                    $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->count(); 

                    $check_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->count();
                    $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->count();

                    $check_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->count();

                    $check_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->count();
                    $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->count();

                    $check_others = DB::table('request_others')->where('request_id',$req->request_id)->count();

                    $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;

                    $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;

                    if($countval_not_received != 0)
                    {
                      $countoutstanding++;
                    }
                }
              }
              if(($client_details)){
                $clientid = $client_details->client_id;
                $firstname = $client_details->firstname;
                $lastname = $client_details->surname;
                $company = $client_details->company;
              }
              else{
                $clientid = '';
                $firstname = '';
                $lastname = '';
                $company = '';
              }
              // if($client->status == 0)
              // {
              //   $color = 'color:#f00 !important;';
              // }
              // elseif($client->status == 1)
              // {
              //   $color = 'color:#f7a001 !important;';
              // }
              // elseif($client->status == 2)
              // {
              //   $color = 'color:#0000fb !important;';
              // }
              // else{
              //   $color = 'color:#f00 !important;';
              // }
              if($i < 10)
              {
                $i = '0'.$i;
              }
              $remove_link = '';
              $deactive_client = '';

              // if($client->status == 0) { 
              // 	if($client_details->active == "2") { 
              //     $stausval = 'Inactive & Not Started'; 
              //     $deactive_client = 'deactivate_tr'; 
              //   } 
              //   else { $stausval = 'Not Started'; } 

              // 	$remove_link = '<a href="javascript:" class="common_black_button remove_from_year" data-client="'.$client->id.'">Remove From Year</a>';
              // }
              // elseif($client->status == 1) { $stausval = 'Inprogress'; }

              if($client->status == 2) { 
                $stausval = 'Completed'; 
                $color = 'color:#0000fb !important;';
              } else{
                $year_end_attachments = DB::table('yearend_distribution_attachments')->where('client_id',$client->id)->get();
                if(is_countable($year_end_attachments) && count($year_end_attachments) > 0)
                {
                  $stausval = 'Inprogress';
                  $color = 'color:#f7a001 !important;';
                }
                else{
                  if($client_details->active == "2") { 
                    $stausval = 'Inactive & Not Started'; 
                    $deactive_client = 'deactivate_tr'; 
                  } 
                  else { $stausval = 'Not Started'; } 
                  $color = 'color:#f00 !important;';
                  $remove_link = '<a href="javascript:" class="common_black_button remove_from_year" data-client="'.$client->id.'">Remove From Year</a>';
                }
              }


              $output.='
              <tr class="task_tr client_'.$client->status.' '.$deactive_client.'">
                <td class="sno_sort_val" style="'.$color.'text-align:left;font-weight:600">'.$i.'</td>
                <td class="clientid_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'" target="_blank">'.$clientid.'</a></td>
                <td align="center">
                  <img class="active_client_list_tm1" data-iden="'.$clientid.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />
                </td>
                <td class="firstname_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'" target="_blank">'.$firstname.'</a></td>
                <td class="lastname_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'" target="_blank">'.$lastname.'</a></td>
                <td class="company_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'" target="_blank">'.$company.'</a></td>
               
                <td class="type_sort_val" style="'.$color.'text-align:left;font-weight:600">'.$client_details->tye.'</td>
                <td class="notes_sort_val" style="'.$color.'text-align:left;font-weight:600">
                  <textarea name="yearend_notes" class="form-control yearend_notes" data-element="'.$client->client_id.'" data-year="'.$client->year.'" style="height:50px">'.$client->notes.'</textarea>
                </td>
                <td class="request_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'font-weight:600" href="'.URL::to('user/client_request_manager/'.base64_encode($client->client_id)).'">'.$request_count.'/'.$countoutstanding.'/'.$awaiting_request.'</a></td>


                <td class="status_sort_val" style="'.$color.'text-align:left;font-weight:600">'.$stausval.'</td>
                <td style="'.$color.'text-align:left;font-weight:600">'.$remove_link.'</td>
              </tr>';
              $i++;
            }
          }
          else{
            $output.='
              <tr>
                <td colspan="3" align="center">Empty</td>
              </tr>
            ';
          }
          echo $output;
          ?>                        
    </tbody>
  </table>
</div>
    <!-- End  -->

<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Files are Imported.</p>
  <p style="font-size:18px;font-weight: 600;">Importing File: <span id="apply_first">1</span> of <span id="apply_last"></span></p>
</div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
<input type="hidden" name="lastname_sortoptions" id="lastname_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="status_sortoptions" id="status_sortoptions" value="asc">

<input type="hidden" name="review_clientid_sortoptions" id="review_clientid_sortoptions" value="asc">
<input type="hidden" name="review_company_sortoptions" id="review_company_sortoptions" value="asc">


<script>
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumber_int = function(value){
       return parseInt(value);
}

$(window).click(function(e) {
$(".yearend_notes").blur(function() {
    var that = $(this);
    var input_val = $(this).val();
    var client_id  = $(this).attr("data-element");
    var year_id  = $(this).attr("data-year");

    doneTyping_notes(input_val,client_id,year_id,that);
});
var ascending = false;
if($(e.target).hasClass('sort_sno'))
{
  var sort = $("#sno_sortoptions").val();
  if(sort == 'asc')
  {
    $("#sno_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#sno_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_clientid'))
{
  var sort = $("#clientid_sortoptions").val();
  if(sort == 'asc')
  {
    $("#clientid_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.clientid_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#clientid_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.clientid_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}

if($(e.target).hasClass('review_sort_clientid'))
{
  var sort = $("#review_clientid_sortoptions").val();
  if(sort == 'asc')
  {
    $("#review_clientid_sortoptions").val('desc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_clientid_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#review_clientid_sortoptions").val('asc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_clientid_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#review_task_body').html(sorted);
}

if($(e.target).hasClass('sort_firstname'))
{
  var sort = $("#firstname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#firstname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.firstname_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#firstname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.firstname_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_lastname'))
{
  var sort = $("#lastname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#lastname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.lastname_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#lastname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.lastname_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_company'))
{
  var sort = $("#company_sortoptions").val();
  if(sort == 'asc')
  {
    $("#company_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.company_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#company_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.company_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}

if($(e.target).hasClass('review_sort_company'))
{
  var sort = $("#review_company_sortoptions").val();
  if(sort == 'asc')
  {
    $("#review_company_sortoptions").val('desc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_company_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#review_company_sortoptions").val('asc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_company_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#review_task_body').html(sorted);
}

if($(e.target).hasClass('sort_status'))
{
  var sort = $("#status_sortoptions").val();
  if(sort == 'asc')
  {
    $("#status_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#status_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('remove_from_year'))
{
	var client_id = $(e.target).attr("data-client");
	var r = confirm("Are you sure you want to remove this client from this year?");
	if(r)
	{
    $.ajax({
      url:"<?php echo URL::to('user/remove_client_from_year'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $(e.target).parents("tr").detach();
        $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Client Removed from this Year</p>',fixed:true,width:"800px"});
      }
    })
	}
}
if($(e.target).hasClass('export_to_csv'))
{
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
      url:"<?php echo URL::to('user/yearend_export_to_csv'); ?>",
      type:"post",
      data:{year:"<?php echo $yearid; ?>"},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  },1000);
}
if($(e.target).hasClass('update_documents'))
{
  alert('New Clients cannot be added when "New Year End Docs" is blinking. Apply any new docs to the existing clients first and then add new clients');
}
if(e.target.id == 'show_incomplete')
{
	$(".task_tr").show();
  if($(e.target).is(":checked"))
  {
    $(".client_2").hide();
    $("#show_active_clients").prop("checked",false);
  }
  else{
    $(".client_2").show();
  }
}
if(e.target.id == "show_active_clients")
{
  $(".task_tr").show();
  if($(e.target).is(":checked"))
  {
    $(".deactivate_tr").hide();
    $("#show_incomplete").prop("checked",false);
  }
  else{
    $(".deactivate_tr").show();
  }
}
if($(e.target).hasClass("submit_review_clients"))
{
  var count = $(".review_clients_checkbox:checked").length;
  if(count < 1)
  {
    alert("You Should select atleast one client to proceed with Review Clients.");
    return false;
  }
}
if(e.target.id =="hide_deactivate_clients")
{
  if($(e.target).is(":checked"))
  {
    $(".hidden_tr").hide();
  }
  else{
    $(".hidden_tr").show();
  }
}
if($(e.target).hasClass("select_all_clients"))
{
  if($(e.target).is(":checked"))
  {
    $(".review_clients_checkbox").prop("checked",true);
  }
  else{
    $(".review_clients_checkbox").prop("checked",false);
  }
}
if($(e.target).hasClass("hide_clients"))
{
  if($(e.target).is(":checked"))
  {
    $(".review_clients_checkbox").prop("checked",true);
  }
  else{
    $(".review_clients_checkbox").prop("checked",false);
  }
}

if($(e.target).hasClass('create_new_year'))
{
  var r=confirm("Are you sure you want to create a new year?");
  if(r)
  {
    window.location.replace("<?php echo URL::to('user/yearend_create_new_year'); ?>");
  }
}
if($(e.target).hasClass('review_clients'))
{
  var yearid = "<?php echo $yearid; ?>";
  $.ajax({
    url:"<?php echo URL::to('user/review_get_clients'); ?>",
    type:"get",
    data:{yearid:yearid},
    success:function(result)
    {
      $("#review_clients_modal").modal("show");
      $("#review_clients_tbody").html(result);
    }
  })
}

if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".year_input_group").hide();
    $(".year_drop").prop('required', false);
}
if($(e.target).hasClass('setting_button')){
  var setting_type = $(".setting_type").val();
  if(setting_type == "" || typeof setting_type === "undefined")
  {
    alert("Please select type");
    return false;
  }
}



if($(e.target).hasClass('year_button')){
  var year_class = $(".year_class").val();

  if(year_class == "" || typeof year_class === "undefined")
  {
    alert("Please select year");
    return false;
  }
  else{
    var r = confirm("Warning, once you create this year no year prior to this can be created.  Do you wish to Proceed with Creating the year?");
    if (r == true) {      

    } else {
        return false;
    }
  }
}

if($(e.target).hasClass("dropzone"))
{
	$(e.target).parents('td').find('.img_div').show();    
	$(e.target).parents('.modal-body').find('.img_div').show();    
}
if($(e.target).hasClass("remove_dropzone_attach"))
{
	$(e.target).parents('td').find('.img_div').show();   
	$(e.target).parents('.modal-body').find('.img_div').show(); 
}
if($(e.target).hasClass("remove_dropzone_attach_aml"))
{
	$(e.target).parents('td').find('.img_div').show();   
	$(e.target).parents('.modal-body').find('.img_div').show(); 
}
if($(e.target).parent().hasClass("dz-message"))
{
	$(e.target).parents('td').find('.img_div').show();
	$(e.target).parents('.modal-body').find('.img_div').show();    
}
if($(e.target).hasClass('import_aml_risk'))
{
    Dropzone.forElement("#imageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    $("#import_aml_modal").modal("show");
}
if($(e.target).hasClass('setting_type')){
  var level = $(e.target).val();  
  if (level == 1) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/supplementary_manager')?>");
  }

  else if (level == 2) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/yearend_setting')?>");
  }
}

if($(e.target).hasClass('no_setting')){
  alert("It looks like there are NO Documents created yet in the settings screen. Please create atleast one document type and then run this New Year End doc.");
  return false;
}

if($(e.target).hasClass('setting_same')){
  alert("It looks like NO new Documents were created in the settings screen since this Year has been created. Please create a new document type and then run this New Year End doc.");
  return false;
}
if($(e.target).hasClass('new_year_end_class')){
    //$(".crypt_modal").modal("show");    
    //$(".crypt_pin_setting").val('');
    $.ajax({
      url:"<?php echo URL::to('user/year_setting_copy_to_year'); ?>",
      type:"post",
      data:{yearid:"<?php echo base64_encode($yearid)?>"},
      success:function(result){
        $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Successfully updated all existing clients with New Year end docs. Please click on the Okay button to Refresh the page.</p><p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" class="common_black_button ok_proceed">Okay</a></p>',fixed:true,width:"800px"});
        //window.location.reload();
      }
    })
}
if($(e.target).hasClass('ok_proceed'))
{
  window.location.reload();
}
});

$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    if($(e.target).hasClass('yearend_notes'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var client_id  = $(".client_id").val();
        var year_id  = $(".year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_notes, valueInterval,input_val, client_id, year_id, that);   
    }
});

function doneTyping_notes (notes_value, client_id, year_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_notes_update')?>",
        type:"post",
        data:{value:notes_value, year_id:year_id, client_id:client_id},
        success: function(result) { 
          //that.val(result);
        } 
  });            
}

$(".add_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});

fileList = new Array();
Dropzone.options.imageUpload = {
	acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading_apply");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading_apply");        
        });
        this.on("success", function(file, response) {
            var total_count = this.files.length;
            var uploaded_count = total_count - this.getQueuedFiles().length;
            
            $("#apply_first").html(uploaded_count + 1);
            $("#apply_last").html(total_count);
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            file.previewElement.innerHTML = "<p class='dropzone_aml_attachments'>"+obj.filename+"</p>";
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("body").removeClass("loading_apply");
            $(".aml_attach_spam").html("files added Successfully");
          }
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>

<script>
$(document).ready(function() {
  $('.table-fixed-header').fixedHeader();  
});
</script>
@stop