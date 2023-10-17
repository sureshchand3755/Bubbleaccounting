﻿@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
  .invoice_year_div{
    position: absolute;
    top: 93%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
    width: 250px;
}
.invoice_custom_div{
    position: absolute;
    top: 93%;
    left:266px;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.all_clients, .invoice_date_option{margin-top: 12px !important;}
  .disabled_ard{
    background: #dfdfdf !important;
    pointer-events: none; 
  }
  body{
    background: #f5f5f5 !important;
  }
  .form-control[readonly]{
    background-color: #fff !important
  }
  .ard_class[readonly]{
    background-color: #dfdfdf !important
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
  .ui-tooltip{
    margin-top:-50px !important;
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
  .field_check
  {
    width:24%;
  }
  .import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
  }
  .selectall_div{
    position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
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
  .error{color: #f00; font-size: 12px;}
  a:hover{text-decoration: underline;}
  #unprocessed_tbody tr td{ vertical-align: middle !important; }
</style>
<div class="modal fade dropzone_purchase_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Attachments</h4>
        </div>
        <div class="modal-body" style="min-height:280px">  
            <div class="img_div_supplier">
               <div class="image_div_attachments_supplier">
                  <form action="<?php echo URL::to('user/purchase_invoice_files'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                    <input type="hidden" name="hidden_global_inv_id" id="hidden_global_inv_id" value="">
                  @csrf
</form>
               </div>
            </div>
        </div>
        <div class="modal-footer">  
          <a href="" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
        </div>
      </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
    <h4 class="col-lg-12 padding_00 new_main_title">
      Supplier Invoice Management System             
    </h4>
  </div>
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a href="<?php echo URL::to('user/supplier_invoice_management'); ?>" class="nav-link">Supplier Invoice Management</a>
    </li>
    <li class="nav-item active">
      <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Purchase Invoices to Process</a>
    </li>
  </ul>
  <div style="clear: both;">
    <?php
    if(Session::has('message')) { ?>
      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php }
    if(Session::has('error-message')) { ?>
      <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>
    <?php } ?>
  </div>
  <!-- <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 20px;">
    <li class="nav-item active">
      <a class="nav-link active" id="unprocessed-tab" data-toggle="tab" href="#unprocessed" role="tab" aria-controls="unprocessed" aria-selected="true">Unprocessed Files</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="processed-tab" data-toggle="tab" href="#processed" role="tab" aria-controls="processed" aria-selected="true">Processed Files</a>
    </li>
  </ul> -->
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active in" id="unprocessed" role="tabpanel" aria-labelledby="unprocessed-tab">
      <div class="col-lg-12">
        <style>
          #client_expand {
            text-align: left;
            position: relative;
            border-collapse: collapse; 
          }
          #client_expand thead tr th {
            background: white;
            position: sticky;
            top: 84; /* Don't forget this, required for the stickiness */
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
          }
          </style>
          <a href="javascript:" class="common_black_button faplus_progress" style="float:right;margin-bottom: 20px;margin-top: 8px;">Add Purchase Invoice Files</a>
          <a href="javascript:" class="common_black_button review_unprocessed" style="float:right;margin-bottom: 20px;margin-top: 8px;">Review Unprocessed Purchase Invoice</a>
            <table class="table own_white_table" width="100%">
              <thead>
                <tr>
                  <th style="width:5%">S.No</th>
                  <th style="width:30%">Attachment</th>
                  <th style="width:10%;text-align: center;">Status</th>
                  <th style="width:15%">Supplier</th>
                  <th style="width:10%">Date</th>
                  <th style="width:10%">Ignore</th>
                  <th style="width:10%">Action</th>
                </tr>
              </thead>
              <tbody id="unprocessed_tbody">
                  <?php
                  if(($unprocessed_purchase_files))
                  {
                    foreach($unprocessed_purchase_files as $files)
                    {
                      $get_global_id = DB::table('supplier_global_invoice')->where('filename',$files->filename)->first();
                      if(($get_global_id)){
                        $sno = $get_global_id->id;
                      }else{
                        $sno = '';
                      }
                      ?>
                      <tr>
                        <td><?php echo $sno; ?></td>
                        <td><a href="<?php echo URL::to($files->url.'/'.$files->filename); ?>" download><?php echo $files->filename; ?></a></td>
                        <td style="text-align: center">
                          <?php
                          if($files->status == 1){ ?>
                            <a href="javascript:" class="common_black_button process_now_btn" data-element="<?php echo $files->id; ?>">Process Now</a>
                          <?php } else { echo '<b class="processed_text">Processed</b>'; }?>
                        </td>
                        <td>
                          <select name="select_supplier_files" class="form-control select_supplier_files" data-element="<?php echo $files->id; ?>" <?php if($files->status == 0){ echo 'disabled'; } ?>>
                            <option value="">Select Supplier</option>
                            <?php
                            if(($suppliers)) {
                              foreach($suppliers as $supplier) {
                                if($files->supplier_id == $supplier->id) { $selected = 'selected'; }
                                else { $selected = ''; }
                                ?>
                                <option value="<?php echo $supplier->id; ?>" <?php echo $selected; ?>><?php echo $supplier->supplier_name.' - '.$supplier->supplier_code; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="file_inv_date" class="form-control file_inv_date" value="<?php if($files->inv_date != ""){ echo date('d-m-Y', strtotime($files->inv_date)); } ?>" placeholder="DD-MM-YYYY" data-element="<?php echo $files->id; ?>" <?php if($files->status == 0){ echo 'disabled'; } ?>>
                        </td>
                        <td>
                          <?php
                          if($files->ignore_file == 1) { $checked = 'checked'; }
                          else{ $checked = ''; }
                          ?>
                          <input type="checkbox" name="ignore_files" class="ignore_files" id="ignore_files_<?php echo $files->id; ?>" data-element="<?php echo $files->id; ?>" <?php echo $checked; ?> <?php if($files->status == 0){ echo 'disabled'; } ?>><label for="ignore_files_<?php echo $files->id; ?>">Ignore</label> 
                        </td>
                        
                        <td>
                          
                          <a href="javascript:" class="common_black_button delete_purchase_files" data-element="<?php echo $files->id; ?>">Delete</a>
                        </td>
                      </tr>
                      <?php
                    }
                  }
                  ?>
              </tbody>
            </table>
      </div>
    </div>
    
  </div>

  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Supplier Invoices Journals are Reposted.</p>
  <p style="font-size:18px;font-weight: 600;">Processing Invoice of <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
  
</div>
<script>
  $(function(){
      $('#client_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false
      });
      blurfunction();
  });
  function blurfunction() {
    $(".file_inv_date").on("dp.hide", function (e) {
      var id = $(this).attr("data-element");
      var value = $(this).val();
      $.ajax({
        url:"<?php echo URL::to('user/change_supplier_files_inv_date'); ?>",
        type:"post",
        data:{value:value,id:id},
        success:function(result)
        {

        }
      })
    });
    $(".net_detail").on('blur', function() {
      var net_value = $(this).val();
      var value = $(this).parents("tr").find(".select_vat_rates").val();
      if(net_value != "" && value != "")
      {
        var vat_value = (parseFloat(net_value) * (parseFloat(value) / 100)).toFixed(2);
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".vat_detail").val(vat_value);
        $(this).parents("tr").find(".gross_detail").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = $(".net_global").val();
        var vat_global = $(".vat_global").val();
        var gross_global = $(".gross_global").val();

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });


    $(".net_global").on('blur', function() {
      var net_value = $(this).val();
      var vat_value = $(this).parents("tr").find(".vat_global").val();
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = $(".net_global").val();
        var vat_global = $(".vat_global").val();
        var gross_global = $(".gross_global").val();

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });

    $(".vat_global").on('blur', function() {
      var net_value = $(this).parents("tr").find(".net_global").val();
      var vat_value = $(this).val(); 
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = $(".net_global").val();
        var vat_global = $(".vat_global").val();
        var gross_global = $(".gross_global").val();

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });


    var total_net = '0.00';
    $(".net_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
      }
    });


    var total_vat = '0.00';
    $(".vat_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
      }
    });

    var total_gross = '0.00';
    $(".gross_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
      }
    });
    $(".total_detail_net").val(total_net);
    $(".total_detail_vat").val(total_vat);
    $(".total_detail_gross").val(total_gross);

    var net_global = $(".net_global").val();
    var vat_global = $(".vat_global").val();
    var gross_global = $(".gross_global").val();

    if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
    else { $(".total_detail_net").css("color","red"); }

    if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
    else { $(".total_detail_vat").css("color","red"); }

    if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
    else { $(".total_detail_gross").css("color","red"); }

    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".inv_date_global").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });
    $(".file_inv_date").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });
  }
  $(document).ready(function() {
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".inv_date_global").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });
  });

  $(window).change(function(e) {
    if($(e.target).hasClass('select_supplier_files'))
    {
      var id = $(e.target).attr("data-element");
      var value = $(e.target).val();
      $.ajax({
        url:"<?php echo URL::to('user/change_supplier_files_supplier_id'); ?>",
        type:"post",
        data:{value:value,id:id},
        success:function(result)
        {

        }
      })
    }
    if($(e.target).hasClass('select_vat_rates'))
    {
      var value = $(e.target).val();
      var net_value = $(e.target).parents("tr").find(".net_detail").val();
      if(net_value != "" && value != "")
      {
        var vat_value = (parseFloat(net_value) * (parseFloat(value) / 100)).toFixed(2);
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(e.target).parents("tr").find(".vat_detail").val(vat_value);
        $(e.target).parents("tr").find(".gross_detail").val(gross_value);


        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = $(".net_global").val();
        var vat_global = $(".vat_global").val();
        var gross_global = $(".gross_global").val();

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    }
    if($(e.target).hasClass('select_supplier'))
    {
      var id = $(e.target).val();
      if(id != "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/get_supplier_info_details'); ?>",
          type:"post",
          dataType:"json",
          data:{id:id},
          success:function(result)
          {
            $(".supplier_code_td").html(result['supplier_code']);
            $(".supplier_name_td").html(result['supplier_name']);
            $(".web_url_td").html(result['web_url']);
            $(".phone_no_td").html(result['phone_no']);
            $(".email_td").html(result['email']);
            $(".iban_td").html(result['iban']);
            $(".bic_td").html(result['bic']);
            $(".vat_no_td").html(result['vat_no']);
          }
        })
      }
    }
  });
function next_journal_check(count)
{
  var invid = $(".edit_purchase_invoice:eq("+count+")").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/check_supplier_journal_repost'); ?>",
    type:"post",
    data:{invid:invid},
    success:function(result)
    {
      setTimeout( function() {
        var countval = count + 1;
        if($(".edit_purchase_invoice:eq("+countval+")").length > 0)
        {
          next_journal_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Journals Reposted Successfully.</p>',fixed:true,width:"800px"});
        }
      },200);
    }
  });
}
  $(window).click(function(e) {
    if($(e.target).hasClass('review_unprocessed'))
    {
      if($(e.target).hasClass('show_unprocessed'))
      {
        $("#unprocessed_tbody").find(".ignore_files:checked").parents("tr").show();
        $("#unprocessed_tbody").find(".processed_text").parents("tr").show();
        $(e.target).removeClass('show_unprocessed');
        $(e.target).html('Review Unprocessed Purchase Invoice');
      }
      else{
        $("#unprocessed_tbody").find(".ignore_files:checked").parents("tr").hide();
        $("#unprocessed_tbody").find(".processed_text").parents("tr").hide();
        $(e.target).addClass('show_unprocessed');
        $(e.target).html('Show All Purchase Invoice');
      }
    }
    if($(e.target).hasClass('process_now_btn')){
      var id = $(e.target).attr("data-element");
      var base_url = "<?php echo URL::to('/'); ?>"
      $.ajax({
          url:"<?php echo URL::to('user/get_purchase_invoice_files_details'); ?>",
          type:"post",
          data:{id:id},
          dataType:"json",
          success:function(result) {
            $(".add_purchase_invoice_modal").modal("show");
            $(".add_purchase_invoice_modal").find(".modal-title").html("Add Purchase Invoice");
            $("#global_invoice_tbody").find(".form-control").val("");
            $("#detail_tbody").find(".form-control").val("");
            $("#hidden_global_id").val("");
            $("#hidden_sno").val("");
            if(result['supplier_id'] != '0'){
              $(".select_supplier").val(result['supplier_id']);
              $(".select_supplier").trigger("change");
            }
            if(result['inv_date'] != ""){
              $(".inv_date_global").val(result['inv_date']);
            }
            $("#attachment_global_supplier_tbody").html('<spam class="global_file_upload"><a href="'+base_url+'/'+result['url']+'/'+result['filename']+'" class="file_attachments" download>'+result['filename']+'</a> <a href="javascript:" class="fa fa-trash delete_global_attachments"></a></spam><input type="hidden" name="global_file_url" id="global_file_url" value="'+result['url']+'"><input type="hidden" name="global_file_name" id="global_file_name" value="'+result['filename']+'"><a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment"></a>');
          }
        })
    }
    if($(e.target).hasClass('delete_purchase_files'))
    {
      var id = $(e.target).attr("data-element");
      var r = confirm("Are you sure you want to delete this file?");
      if(r){
        $.ajax({
          url:"<?php echo URL::to('user/delete_purchase_files'); ?>",
          type:"post",
          data:{id:id},
          success:function(result){
            window.location.reload();
          }
        })
      }
    }
    if($(e.target).hasClass('ignore_files'))
    {
      if($(e.target).is(":checked"))
      {
        var id = $(e.target).attr("data-element");
        var value = 1;
        $.ajax({
          url:"<?php echo URL::to('user/change_supplier_files_ignore_file'); ?>",
          type:"post",
          data:{value:value,id:id},
          success:function(result)
          {

          }
        })
      }
      else{
        var id = $(e.target).attr("data-element");
        var value = 0;
        $.ajax({
          url:"<?php echo URL::to('user/change_supplier_files_ignore_file'); ?>",
          type:"post",
          data:{value:value,id:id},
          success:function(result)
          {

          }
        })
      }
    }
    if($(e.target).hasClass('add_supplier_invoice')){

  $(".supplier_name_class").val('');
  $(".supp_address_class").val('');
  $(".supplier_address_class").val('');
  $(".phone_no_class").val('');
  $(".supplier_email_class").val('');
  $(".supplier_iban_class").val('');
  $(".supplier_bic_class").val('');
  $(".supplier_count").val('');
  $(".vat_number_class").val('');
  $(".currency_class").val('');
  $(".opening_balance_class").val('');


  $("#add_supplier_invoice_modal").modal('show');
  $("#add_supplier_invoice_modal").css({"z-index":"999999999999999998 !important"})
}

if($(e.target).hasClass('submit_module_update_invoice')){
  var supplier_code = $(".supplier_code_class").val();
  var supplier_name = $(".supplier_name_class").val();
  var supp_address = $(".supp_address_class").val();
  var supplier_address = $(".supplier_address_class").val();
  var phone_no = $(".phone_no_class").val();
  var supplier_email = $(".supplier_email_class").val();
  var supplier_iban = $(".supplier_iban_class").val();
  var supplier_bic = $(".supplier_bic_class").val();
  var supplier_count = $(".supplier_count_class").val();
  var vat_number = $(".vat_number_class").val();
  var currency = $(".currency_class").val();
  var opening_balance = $(".opening_balance_class").val();
  var supplier_id = $("#supplier_id_invoice").val();

  if(supplier_name == ''){
    $(".error_supplier_name_class").show();
  }
  else{
    $(".error_supplier_name_class").hide();
    setTimeout(function() {      
      $.ajax({
          url:"<?php echo URL::to('user/store_supplier_invoice'); ?>",
          type:"post",
          dataType:"json",
          data:{supplier_id:supplier_id, supplier_code:supplier_code, supplier_name:supplier_name, supp_address:supp_address, supplier_address:supplier_address, phone_no:phone_no, supplier_email:supplier_email, supplier_iban:supplier_iban, supplier_bic:supplier_bic, vat_number:vat_number, currency:currency, opening_balance:opening_balance, supplier_count:supplier_count},
          success: function(result)
          {
            $(".supplier_code_class").val(result['code']);
            $(".select_supplier_invoice").html(result['drop_supplier']);
            $(".supplier_invoice_message").html(result['message']);

            $(".supplier_code_td").html(result['supplier_code']);
            $(".supplier_name_td").html(result['supplier_name']);
            $(".web_url_td").html(result['web_url']);
            $(".phone_no_td").html(result['phone_no']);
            $(".email_td").html(result['email']);
            $(".iban_td").html(result['iban']);
            $(".bic_td").html(result['bic']);
            $(".vat_no_td").html(result['vat_no']);


            $("#add_supplier_invoice_modal").modal('hide');
            
          }
        })
      },500);
  }

}
    if($(e.target).hasClass('repost_journals')){
      $("body").addClass("loading_apply");
      var lengthval = $(".edit_purchase_invoice").length;
      $("#apply_first").html(0);
      $("#apply_last").html(lengthval);
      next_journal_check(0);
    }
    if($(e.target).hasClass('delete_global_attachments'))
    {
      var global_id = $("#hidden_global_id").val();
      var r= confirm("Do you want to delete the file?");
      if(r){
        $("#global_file_url").val("");
        $("#global_file_name").val("");
        $(".global_file_upload").html("");
      }

      if(global_id != ""){
        $.ajax({
          url:"<?php echo URL::to('user/delete_supplier_global_attachment'); ?>",
          type:"post",
          data:{global_id:global_id},
          success:function(result){
            
          }
        })
      }
    }
    if($(e.target).hasClass('export_transaction_list'))
    {
      $("body").addClass("loading");
      var supplier_id = $("#hidden_trans_supplier_id").val();
      $.ajax({
        url:"<?php echo URL::to('user/export_supplier_transaction_list'); ?>",
        type:"post",
        data:{supplier_id:supplier_id},
        success:function(result){
          SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      })
    }
    if($(e.target).hasClass('transaction_list'))
    {
      var id = $(".select_supplier").val();
      if(id == "")
      {
        alert("Please select the Supplier and then load the Transaction lists");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/get_supplier_transaction_list'); ?>",
          type:"post",
          data:{id:id, type:1},
          dataType:"json",
          success:function(result)
          {
            $("#transaction_list_modal_invoice").modal("show");
            $("#supplier_info_tbody_invoice").html(result['output']);
            $("#supplier_info_tbody_invoice").find(".supplier_info_div").hide();
          }
        });
      }
    }
    if($(e.target).hasClass('close_purchase_invoice'))
    {
      var r = confirm('Are you sure you want to close the Purchase Invoice Overlay? If you have any unsaved data then please click on "Submit Purchase Invoice" button, so you do not loose any data.');
      if(r){
        $(".add_purchase_invoice_modal").modal("hide");
        $(".total_detail_net").val("");
        $(".total_detail_vat").val("");
        $(".total_detail_gross").val("");
      }
    }
    if($(e.target).hasClass('submit_purchase_invoice')) {
      if($("#add_purchase_invoice_form").valid())
      {
        var des = $("#detail_tbody").find("tr").last().find(".description_detail").val();
        var code = $("#detail_tbody").find("tr").last().find(".select_nominal_codes").val();
        var net = $("#detail_tbody").find("tr").last().find(".net_detail").val(); 
        var vat_rate = $("#detail_tbody").find("tr").last().find(".select_vat_rates").val(); 
        var vat_value = $("#detail_tbody").find("tr").last().find(".vat_detail").val(); 
        var gross = $("#detail_tbody").find("tr").last().find(".gross_detail").val(); 

        if(des == "" || code == "" || net == "" || vat_rate == "" || vat_value == "" || gross == "")
        {
          alert("Please fill all the fields and then proceed with next invoice");
        }
        else{
          var supplier = $(".select_supplier").val();
          var net_global = $(".net_global").val();
          var vat_global = $(".vat_global").val();
          var gross_global = $(".gross_global").val();

          net_global = parseFloat(net_global).toFixed(2);
          vat_global = parseFloat(vat_global).toFixed(2);
          gross_global = parseFloat(gross_global).toFixed(2);

          var total_net = 0.00;
          $(".net_detail").each(function() {
            var value = $(this).val();
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          })

          var total_vat = 0.00;
          $(".vat_detail").each(function() {
            var value = $(this).val();
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          })

          var total_gross = 0.00;
          $(".gross_detail").each(function() {
            var value = $(this).val();
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          })

          //console.log(net_global+'----'+total_net+'----'+gross_global+'----'+total_gross)

          if((net_global == total_net) && (vat_global == total_vat) && (gross_global == total_gross)) {
            $("#add_purchase_invoice_form").submit();
          }
          else{
            alert("The Global value and the Total of Detail value are different. Please check the Net and Gross Value are same in both Global and Detail Section")
          }
        }
      }
    }
    if($(e.target).hasClass('add_detail_section'))
    {
      if($(e.target).hasClass('remove_detail_section'))
      {
        var r = confirm("Are you sure you want to delete this invoice?");
        if(r)
        {
          $(e.target).parents("tr").detach();
          var ival = 1;
          $("#detail_tbody").find("tr").each(function() {
            $(this).find("td").eq(0).html(ival);
            $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
            ival++;
          });

          $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        }
      }
      else{
        var des = $(e.target).parents("tr").find(".description_detail").val();
        var code = $(e.target).parents("tr").find(".select_nominal_codes").val();
        var net = $(e.target).parents("tr").find(".net_detail").val(); 
        var vat_rate = $(e.target).parents("tr").find(".select_vat_rates").val(); 
        var vat_value = $(e.target).parents("tr").find(".vat_detail").val(); 
        var gross = $(e.target).parents("tr").find(".gross_detail").val(); 

        if(des == "" || code == "" || net == "" || vat_rate == "" || vat_value == "" || gross == "")
        {
          alert("Please fill all the fields and then proceed with next invoice");
        }
        else{
          var html = $(e.target).parents("tr").html();
          $("#detail_tbody").append('<tr>'+html+'</tr>');
          $("#detail_tbody").find("tr").last().find("input").val("");
          $("#detail_tbody").find("tr").last().find("select").val("");
          $("#detail_tbody").find("tr").last().find(".select_vat_rates").val(vat_rate);

          var ival = 1;
          $("#detail_tbody").find("tr").each(function() {
            $(this).find("td").eq(0).html(ival);
            $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
            ival++;
          });

          $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        }
      }
      blurfunction();
    }
    else if($(e.target).hasClass('remove_detail_section'))
    {
      var r = confirm("Are you sure you want to delete this invoice?");
      if(r)
      {
        $(e.target).parents("tr").detach();
        var ival = 1;
        $("#detail_tbody").find("tr").each(function() {
          $(this).find("td").eq(0).html(ival);
          $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
          ival++;
        });

        $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        blurfunction();
      }
    }

    if($(e.target).hasClass('change_vat_status'))
    {
      var id = $(e.target).attr("data-element");
      var status = $(e.target).attr("data-status");
      $.ajax({
        url:"<?php echo URL::to('user/change_supplier_vat_status'); ?>",
        type:"post",
        data:{id:id,status:status},
        success:function(result)
        {
          if(status == 1)
          {
            $(e.target).attr("class","fa fa-times change_vat_status");
            $(e.target).css("color","#f00");
            $(e.target).attr("data-status",0);
          }
          else{
            $(e.target).attr("class","fa fa-check change_vat_status");
            $(e.target).css("color","green");
            $(e.target).attr("data-status",1);
          }
        }
      });
    }
    if($(e.target).hasClass('submit_vat_rate'))
    {
      var value = $(".vat_rate_input").val();
      if(value == ""){
        alert("Please enter the vat rate");
      }
      else{
        var tr_length = $(".vat_tr").length;
        $.ajax({
          url:"<?php echo URL::to('user/store_supplier_vat_rate'); ?>",
          type:"post",
          data:{value:value,tr_length:tr_length},
          success:function(result)
          {
            $("#vat_rate_tbody").append(result);
            $(".vat_rate_input").val("");
          }
        })
      }
    }
    if($(e.target).hasClass('invoice_date_option'))
    {
      var value = $(e.target).val();
      if(value == "1")
      {
        $(".invoice_year_div").show();
        $(".invoice_custom_div").hide();
        $(".invoice_select_year").val("");
        var myVal = $('.invoice_select_year option:last').val();
        $('.invoice_select_year').val(myVal);
      }
      else if(value == "2")
      {
        $(".invoice_year_div").hide();
        $(".custom_date_div").hide();
        $('#client_expand').DataTable().destroy();
        $("body").addClass("loading");
          setTimeout(function(){ 
            $.ajax({
              url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
              data:{type:"2"},
              type:"post",
              success:function(result){
                $("#invoice_tbody").html(result);
                $('#client_expand').DataTable({
                    autoWidth: false,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false
                });
                $("body").removeClass("loading");
              }
            });
          }, 2000);
      }
      else if(value == "3")
      {
        $(".invoice_year_div").hide();
        $(".invoice_custom_div").show();
        $(".from_invoice").val("");
        $(".to_invoice").val("");
      }
    }
    else if($(e.target).parents(".invoice_custom_div").length > 0)
    {
      $(".invoice_year_div").hide();
      $(".invoice_custom_div").show();
    }
    else if($(e.target).parents(".invoice_year_div").length > 0)
    {
      $(".invoice_year_div").show();
      $(".invoice_custom_div").hide();
    }
    else{
        $(".invoice_year_div").hide();
        $(".invoice_custom_div").hide();
    }

    if($(e.target).hasClass('load_all_cm_invoice')) {
      var type = $(".invoice_date_option:checked").val();
      if(type == "1")
      {
        var year = $(".invoice_select_year").val();
        if(year == "")
        {
          alert("Please select the year to review the invoice");
        }
        else{
          $('#client_expand').DataTable().destroy();
          $("body").addClass("loading");
            setTimeout(function(){ 
                  $.ajax({
                    url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
                    data:{year:year,type:"1"},
                    type:"post",
                    success:function(result){
                      $("#invoice_tbody").html(result);       
                      $('#client_expand').DataTable({
                          autoWidth: false,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false
                      });
                      $(".invoice_year_div").hide();
                      $(".invoice_custom_div").hide();
                      $("body").removeClass("loading");
                      }
                  });
            }, 2000);
        }
        
      }
      else if(type == "3")
      {
        $('#client_expand').DataTable().destroy();
        $("body").addClass("loading");
          setTimeout(function(){ 
              var from = $(".from_invoice").val();
              var to = $(".to_invoice").val();
                $.ajax({
                    url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
                    data:{from:from,to:to,type:"3"},
                    type:"post",
                    success:function(result){
                      $("#invoice_tbody").html(result);  
                      $('#client_expand').DataTable({
                          autoWidth: false,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false
                      });     
                      $(".invoice_year_div").hide();
                      $(".invoice_custom_div").hide();
                      $("body").removeClass("loading");
                    }
                });
          }, 2000);
        
      }
    }
    if($(e.target).hasClass('export_purchase_invoice')) {
      var type = $(".invoice_date_option:checked").val();
      if(type == "1")
      {
        var year = $(".invoice_select_year").val();
        if(year == "")
        {
          alert("Please select the year to review the invoice");
        }
        else{
          $("body").addClass("loading");
          setTimeout(function(){ 
            $.ajax({
              url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
              data:{year:year,type:"1"},
              type:"post",
              success:function(result){
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                $("body").removeClass("loading");
              }
            });
          }, 2000);
        }
      }
      else if(type == "2")
      {
        $("body").addClass("loading");
        setTimeout(function(){ 
          $.ajax({
            url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
            data:{type:"1"},
            type:"post",
            success:function(result){
              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
              $("body").removeClass("loading");
            }
          });
        }, 2000);
      }
      else if(type == "3")
      {
        $("body").addClass("loading");
        setTimeout(function(){ 
          var from = $(".from_invoice").val();
          var to = $(".to_invoice").val();
          $.ajax({
              url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
              data:{from:from,to:to,type:"3"},
              type:"post",
              success:function(result){
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                $("body").removeClass("loading");
              }
          });
        }, 2000);
      }
    }

    if($(e.target).hasClass('settings_btn'))
    {
      $(".vat_rate_settings_modal").modal("show");
    }
    ////////////////////////Add Purchase Invoice///////////////////////////
    if($(e.target).hasClass('add_purchase_invoice'))
    {
      $(".add_purchase_invoice_modal").modal("show");
      $(".add_purchase_invoice_modal").find(".modal-title").html("Add Purchase Invoice");
      $("#global_invoice_tbody").find(".form-control").val("");
      $("#detail_tbody").find(".form-control").val("");
      $("#hidden_global_id").val("");
      $("#hidden_sno").val("");
      $(".select_supplier").val("");
      $("#attachment_global_supplier_tbody").html('<spam class="global_file_upload"></spam><input type="hidden" name="global_file_url" id="global_file_url" value=""><input type="hidden" name="global_file_name" id="global_file_name" value=""><a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment"></a>');

      $(".total_detail_net").val("");
      $(".total_detail_vat").val("");
      $(".total_detail_gross").val("");

      $(".supplier_code_td").html("");
      $(".supplier_name_td").html("");
      $(".web_url_td").html("");
      $(".phone_no_td").html("");
      $(".email_td").html("");
      $(".iban_td").html("");
      $(".bic_td").html("");
      $(".vat_no_td").html("");

      var nominal_codes = $(".select_nominal_codes_dummy").html();
      var vat_rates = $(".select_vat_rates_dummy").html();
      $("#detail_tbody").html('<tr><td>1</td><td><input type="text" name="description_detail[]" class="form-control description_detail" value="" placeholder="Enter Description"></td><td><select name="select_nominal_codes[]" class="form-control select_nominal_codes">'+nominal_codes+'</select></td><td><input type="text" name="net_detail[]" class="form-control net_detail" value="" placeholder="Enter Net Value" oninput="keypressonlynumber(this)" onpaste="keypressonlynumber(this)"></td><td><select name="select_vat_rates[]" class="form-control select_vat_rates">'+vat_rates+'</select></td><td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="" placeholder="Enter VAT Value" readonly=""></td><td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="" placeholder="Enter Gross" readonly=""></td><td class="detail_last_td" style="vertical-align: middle;text-align: center"><a href="javascript:" class="fa fa-plus add_detail_section" title="Add Row"></a></td></tr>');
    }
    if($(e.target).hasClass('edit_purchase_invoice'))
    {
      var id = $(e.target).attr("data-element");
      var sno = $(e.target).attr("data-sno");
      $.ajax({
        url:"<?php echo URL::to('user/edit_purchase_invoice_supplier'); ?>",
        type:"post",
        data:{id:id},
        dataType:"json",
        success:function(result)
        {
          $("#supplier_detail_tbody").html(result['supplier_output']);
          $("#global_invoice_tbody").html(result['global_output']);
          $("#detail_tbody").html(result['detail_output']);
          $("#attachment_global_supplier_tbody").html(result['attachment_output']);
          $(".select_supplier").val(result['supplier_id']);
          $("#hidden_global_id").val(id);
          $("#hidden_sno").val(sno);
          $(".add_purchase_invoice_modal").modal("show");
          $(".add_purchase_invoice_modal").find(".modal-title").html("Edit Purchase Invoice");

          blurfunction();
        }
      })
    }
    if($(e.target).hasClass('view_purchase_invoice'))
    {
      var id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/view_purchase_invoice_supplier'); ?>",
        type:"post",
        data:{id:id},
        dataType:"json",
        success:function(result)
        {
          $("#detail_tbody_view").html(result['detail_output']);

          $(".view_purchase_invoice_modal").modal("show");
          blurfunction();
        }
      })
    }

    //////////////////////////////////////////////////////////////////////

    if($(e.target).hasClass('faplus_progress'))
    {
      var global_id = $(e.target).attr("data-element");
      if(global_id != ""){
        $("#hidden_global_inv_id").val(global_id);
      } else {
        $("#hidden_global_inv_id").val("");
      }
      
      $(".dropzone_purchase_invoice_modal").modal("show");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    if($(e.target).hasClass('remove_dropzone_attach'))
    {
      var attachment_id = $(e.target).attr("data-element");
      var file_id = $(e.target).attr("data-task");
      $.ajax({
        url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",
        type:"post",
        data:{attachment_id:attachment_id,file_id:file_id},
        success: function(result)
        {
          var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;
          if(countval == 1)
          {
            $(e.target).parents(".dropzone").removeClass("dz-started");
          }
          $(e.target).parents(".dz-preview").detach();
        }
      })
    }
  });
Dropzone.options.imageUpload = {
    maxFiles: 100,
    acceptedFiles: null,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            //$(".dropzone_purchase_invoice_modal").modal("hide");
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            //Dropzone.forElement("#imageUpload").removeAllFiles(true);
            //$(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>
@stop