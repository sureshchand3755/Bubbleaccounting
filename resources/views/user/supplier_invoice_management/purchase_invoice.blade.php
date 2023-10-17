@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>

  #colorbox {
    z-index:999999999999;
  }
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
  .disabled{
  pointer-events: none;
}
.disable_user, .created_date{
  pointer-events: none;
  background: #c7c7c7;
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
          <a href="javascript:" class="common_black_button create_new_task" style="float:right;margin-bottom: 20px;margin-top: 8px;">Create Task</a>
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
                      $get_global_id = DB::table('supplier_global_invoice')->where('practice_code', Session::get('user_practice_code'))->where('filename',$files->filename)->first();
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

<!-- modal for createnew task -->


<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  

          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
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
      $('#internal_checkbox').change(function(e){
        $("#client_search").val("");
        $("#idtask").val("");
        $(".task-choose_internal").html("Select Task");
        $(".client_search_class").val("");

        if($(e.target).is(":checked"))
        {
          $(".client_group").hide();
          $(".client_search_class").prop("required",false);
          $(".internal_tasks_group").show();
          $(".infiles_link").hide();
          $(".active_client_list_tm").hide();
        }
        else{
          $(".client_group").show();
          $(".client_search_class").prop("required",true);
          $(".internal_tasks_group").hide();
          $(".infiles_link").show();
          $(".active_client_list_tm").show();
        }
      });
      $(".client_search_class").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
          dataType: "json",
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#client_search").val(ui.item.id);
          }
        })
      }
  });
 $(".copy_client_search_class").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
          dataType: "json",
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#copy_client_search").val(ui.item.id);
          }
        })
      }
  });
  $("#open_task").change(function(e){
    console.log("inside check");
    if($(e.target).is(":checked"))
    {
      $(".allocate_user_add").val("");
      $(".allocate_user_add").addClass("disable_user");
      $(".allocate_email").addClass("disable_user");
      $(".allocate_email").prop("required",false);
    }
    else{
      $(".allocate_user_add").val("");
      $(".allocate_user_add").removeClass("disable_user");
      $(".allocate_email").removeClass("disable_user");
      $(".allocate_email").prop("required",true);
    }
  });
   $('#show_incomplete_files').change(function(e){
          if($(e.target).is(":checked"))
          {
            $(".tr_incomplete").hide();
          }
          else{
            $(".tr_incomplete").show();
          }
      });
        
  $('#link_infile_button').click(function(e){
    var checkcount = $(".infile_check:checked").length;
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_id").val(ids);
      $(".infiles_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_infiles'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("#attachments_infiles").show();
          $("#add_infiles_attachments_div").show();
          $("#add_infiles_attachments_div").html(result);
        }
      })
    }
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
$(window).click(function(e) {
  $.ajax({ 
      url:"<?php echo URL::to('user/get_task_redline_notification'); ?>",
      type:"post",
      success:function(result)
      {
        var ids = result.split(",");
        $.each(ids, function(index,value) {
          $(".redlight_indication_"+value).show();
          $(".redlight_indication_layout_"+value).show();

          $(".redlight_indication_"+value).addClass('redline_indication');
          $(".redlight_indication_layout_"+value).addClass('redline_indication_layout');
        })
      }
  })
});
  $(window).change(function(e) {
    if($(e.target).hasClass('select_user_author'))
    {
      var value = $(e.target).val();
      if(value == "")
      {
        $(".author_email").val("");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
          type:"post",
          data:{value:value},
          success:function(result)
          {
            $(".author_email").val(result);
          }
        })
      }
    }
    if($(e.target).hasClass('allocate_user_add')){
      var value = $(e.target).val();
      if(value == "")
      {
        $(".allocate_email").val("");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
          type:"post",
          data:{value:value},
          success:function(result)
          {
            $(".allocate_email").val(result);
          }
        })
      }
    }
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
    
    if($(e.target).hasClass('make_task_live'))
    {
      e.preventDefault();
      if($( "#create_job_form" ).valid())
      {
        if($("#internal_checkbox").is(":checked"))
        {
            var taskvalue = $("#idtask").val();
            if(taskvalue == "")
            {
              alert("Please select the Task Name and then make the task as live");
              return false;
            }
        }
        else{
          var clientid = $("#client_search").val();
          if(clientid == "")
          {
            alert("Please select the Client and then make the task as live");
            return false;
          }
        }
        if (CKEDITOR.instances.editor_2)
        {
          var comments = CKEDITOR.instances['editor_2'].getData();
          if(comments == "")
          {
            alert("Please Enter Task Specifics and then make the task as Live.");
            return false;
          }
          else{
            if($(".2_bill_task").is(":checked"))
            {
              $(window).unbind('beforeunload');

              var formData = $("#create_job_form").submit(function (e) {
                return;
              });

              var formData = new FormData(formData[0]);
              formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
              $("body").addClass("loading");

              $.ajax({
                  url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
                  type: 'POST',
                  data: formData,
                  dataType:"json",
                  success: function (result) {
                    $("#task_body_open").append(result['open_tasks_output']);
                    $("#task_body_layout").append(result['layout']);

                    var view = $(".select_view").val();
                    var layout = $("#hidden_compressed_layout").val();

                    $("#task_body_open").find(".tasks_tr").last().hide();
                    $("#task_body_open").find(".empty_tr").last().hide();
                    $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

                    if(view == "3") {
                      if(layout == "1"){
                        $("#task_body_layout").find(".hidden_author_tr").show();
                      }
                      else{
                        $("#task_body_open").find(".author_tr").last().show();
                        $("#task_body_open").find(".empty_tr").last().show();
                      }
                    }
                    else if(view == "2") {
                      if(layout == "1"){
                        $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      }
                      else {
                        $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
                        $("#task_body_open").find(".empty_tr").last().show();
                      }
                    }
                    else if(view == "1") {
                      if(layout == "1"){
                        $("#task_body_layout").find(".hidden_allocated_tr").show();
                      }
                      else{
                        $("#task_body_open").find(".allocated_tr").last().show();
                        $("#task_body_open").find(".empty_tr").last().show();
                      }
                    }

                    $(".create_new_model").modal("hide");

                    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                    $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');

                    $("body").removeClass("loading");
                  },
                  cache: false,
                  contentType: false,
                  processData: false
              });

              //$("#create_job_form").submit();
            }
            else{
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px"});
            }
          }
        }
        else{
          if($(".2_bill_task").is(":checked"))
          {
            $(window).unbind('beforeunload');
            
            var formData = $("#create_job_form").submit(function (e) {
                return;
            });

            var formData = new FormData(formData[0]);
            formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
            $("body").addClass("loading");

            $.ajax({
                url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
                type: 'POST',
                data: formData,
                dataType:"json",
                success: function (result) {
                  $("#task_body_open").append(result['open_tasks_output']);
                  $("#task_body_layout").append(result['layout']);

                  var view = $(".select_view").val();
                  var layout = $("#hidden_compressed_layout").val();

                  $("#task_body_open").find(".tasks_tr").last().hide();
                  $("#task_body_open").find(".empty_tr").last().hide();
                  $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

                  if(view == "3") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".hidden_author_tr").show();
                    }
                    else{
                      $("#task_body_open").find(".author_tr").last().show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }
                  else if(view == "2") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    }
                    else {
                      $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }
                  else if(view == "1") {
                    if(layout == "1"){
                      $("#task_body_layout").find(".hidden_allocated_tr").show();
                    }
                    else{
                      $("#task_body_open").find(".allocated_tr").last().show();
                      $("#task_body_open").find(".empty_tr").last().show();
                    }
                  }

                  $(".create_new_model").modal("hide");

                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
                  $("body").removeClass("loading");
                },
                cache: false,
                contentType: false,
                processData: false
            });
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('public/assets/images/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px"});
          }
        }
      }
    }
  if($(e.target).hasClass('yes_make_task_live'))
  {
    $(window).unbind('beforeunload');
    $(".2_bill_task").prop("checked",true);
    
    var formData = $("#create_job_form").submit(function (e) {
      return;
    });

    var formData = new FormData(formData[0]);
    formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
    $("body").addClass("loading");

    $.ajax({
        url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
        type: 'POST',
        data: formData,
        dataType:"json",
        success: function (result) {
          $("#task_body_open").append(result['open_tasks_output']);
          $("#task_body_layout").append(result['layout']);

          var view = $(".select_view").val();
          var layout = $("#hidden_compressed_layout").val();

          $("#task_body_open").find(".tasks_tr").last().hide();
          $("#task_body_open").find(".empty_tr").last().hide();
          $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

          if(view == "3") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_author_tr").show();
            }
            else{
              $("#task_body_open").find(".author_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "2") {
            if(layout == "1"){
              $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
            }
            else {
              $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "1") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_allocated_tr").show();
            }
            else{
              $("#task_body_open").find(".allocated_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }

          $(".create_new_model").modal("hide");

          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

           $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
          $("body").removeClass("loading");
        },
        cache: false,
        contentType: false,
        processData: false
    });
  }
  if($(e.target).hasClass('no_make_task_live'))
  {
    $(window).unbind('beforeunload');
    $(".2_bill_task").prop("checked",false);
    
    var formData = $("#create_job_form").submit(function (e) {
      return;
    });

    var formData = new FormData(formData[0]);
    formData.append('task_specifics_content', CKEDITOR.instances['editor_2'].getData());
    $("body").addClass("loading");

    $.ajax({
        url: "<?php echo URL::to('user/create_new_taskmanager_task'); ?>",
        type: 'POST',
        data: formData,
        dataType:"json",
        success: function (result) {
          $("#task_body_open").append(result['open_tasks_output']);
          $("#task_body_layout").append(result['layout']);

          var view = $(".select_view").val();
          var layout = $("#hidden_compressed_layout").val();

          $("#task_body_open").find(".tasks_tr").last().hide();
          $("#task_body_open").find(".empty_tr").last().hide();
          $("#task_body_layout").find(".hidden_tasks_tr").last().hide();

          if(view == "3") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_author_tr").show();
            }
            else{
              $("#task_body_open").find(".author_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "2") {
            if(layout == "1"){
              $("#task_body_layout").find(".redline_indication_layout").parents(".hidden_allocated_tr").show();
            }
            else {
              $("#task_body_open").find(".redline_indication").last().parents(".tasks_tr").show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }
          else if(view == "1") {
            if(layout == "1"){
              $("#task_body_layout").find(".hidden_allocated_tr").show();
            }
            else{
              $("#task_body_open").find(".allocated_tr").last().show();
              $("#task_body_open").find(".empty_tr").last().show();
            }
          }

          $(".create_new_model").modal("hide");

          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-weight:600;font-size:18px;color:green">'+result['message']+'</p>',fixed:true,width:"800px"});

                  $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+result['message']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
          $("body").removeClass("loading");
        },
        cache: false,
        contentType: false,
        processData: false
    });
  }
     if($(e.target).hasClass('fa-plus-add'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('faplus_progress'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_progress").val(task_id);
    $(".dropzone_progress_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('add_progress_files_from_task_specifics'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_progress").val(task_id);
    $(".dropzone_progress_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('faplus_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_completion").val(task_id);
    $(".dropzone_completion_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('fileattachment'))

  {

    e.preventDefault();

    var element = $(e.target).attr('data-element');

    $('body').addClass('loading');

    setTimeout(function(){

      SaveToDisk(element,element.split('/').reverse()[0]);

      $('body').removeClass('loading');

      }, 3000);

    return false; 

  }
  
  if($(e.target).hasClass('remove_infile_link_add'))
  {
    var file_id = $(e.target).attr("data-element");
    var ids = $("#hidden_infiles_id").val();
    var idval = ids.split(",");
    var nextids = '';
    $.each(idval, function( index, value ) {
      if(value != file_id)
      {
        if(nextids == "")
        {
          nextids = value;
        }
        else{
          nextids = nextids+','+value;
        }
      }
    });
    $("#hidden_infiles_id").val(nextids);
    $(e.target).parents("tr").detach();
  }
  if($(e.target).hasClass('notepad_submit_add'))
  {
    console.log("GFDGGFDfg");
    var contents = $(".notepad_contents_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_add' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_add").hide();
      }
    });
  }
  if($(e.target).hasClass('notepad_progress_submit'))
  {
    var contents = $(e.target).parents(".notepad_div_progress_notes").find(".notepad_contents_progress").val();
    var task_id = $(e.target).parents(".notepad_div_progress_notes").find("#hidden_task_id_progress_notepad").val();
    var user_id = $(e.target).parents(".notepad_div_progress_notes").find(".notepad_user").val();
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_notepad_contents_progress'); ?>",
      type:"post",
      data:{contents:contents,task_id:task_id,user_id:user_id},
      dataType:"json",
      success: function(result)
      {
        $("#add_notepad_attachments_progress_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
        $(".notepad_div_progress_notes").hide();
      }
    });
  }
  if($(e.target).hasClass('notepad_completion_submit'))
  {
    var contents = $(e.target).parents(".notepad_div_completion_notes").find(".notepad_contents_completion").val();
    var task_id = $(e.target).parents(".notepad_div_completion_notes").find("#hidden_task_id_completion_notepad").val();
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_notepad_contents_completion'); ?>",
      type:"post",
      data:{contents:contents,task_id:task_id},
      dataType:"json",
      success: function(result)
      {
        $("#add_notepad_attachments_completion_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
        $(".notepad_div_completion_notes").hide();
      }
    });
  }
    if($(e.target).hasClass('link_yearend'))
  {
    var href = $(e.target).attr("data-element");
    var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
    if (printWin == null || typeof(printWin)=='undefined')
    {
      alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
    }
  }
  if($(e.target).hasClass('infiles_link'))
  {
    var client_id = $("#client_search").val();
    var ids = $("#hidden_infiles_id").val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $(".infiles_modal").modal("show");
          $("#infiles_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('infiles_link_progress'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_progress_client_id_"+task_id).val();
    var ids = $("#hidden_infiles_progress_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_progress_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_progress_infiles_task_id").val(task_id);
          $(".infiles_progress_modal").modal("show");
          $("#infiles_progress_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('infiles_link_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_completion_client_id_"+task_id).val();
    var ids = $("#hidden_infiles_completion_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_completion_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_completion_infiles_task_id").val(task_id);
          $(".infiles_completion_modal").modal("show");
          $("#infiles_completion_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('yearend_link_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_completion_client_id_"+task_id).val();
    var ids = $("#hidden_yearend_completion_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_completion_yearend'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_completion_yearend_task_id").val(task_id);
          $(".yearend_completion_modal").modal("show");
          $("#yearend_completion_body").html(result);
        }
      })
    }
  }
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

    if($(e.target).hasClass('fanotepadadd')){
    var clientid = $("#client_search").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }

     if($(e.target).hasClass('create_new_task'))
  {
    $(".create_new_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
    $(".author_email").val("<?php echo Session::get('taskmanager_user_email'); ?>");
    $(".create_new_model").modal("show");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
    $(".created_date").datetimepicker({

       defaultDate: fullDate,       

       format: 'L',

       format: 'DD-MMM-YYYY',

       maxDate: fullDate,

    });

    $(".due_date").datetimepicker({

       defaultDate: fullDate,

       format: 'L',

       format: 'DD-MMM-YYYY',

       minDate: fullDate,

    });

    CKEDITOR.replace('editor_2',
   {
    height: '300px',
    enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
   });

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".client_search_class").val("");
    $("#client_search").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("Supplier Invoice Managemenet - Process Inovices");
    $(".task_specifics_add").show();
    $(".task_specifics_copy").hide();
    CKEDITOR.instances['editor_2'].setData("Please process the unprocessed Supplier Invoices");
    
    $(".retreived_files_div").hide();
    $(".retreived_files_div").html("");
    $(".recurring_checkbox").prop("checked", false);
    $(".specific_recurring").val("");
    $(".task_specifics_copy_val").html("");
    $("#hidden_task_specifics").val("");

    $("#hidden_specific_type").val("");
    $("#hidden_attachment_type").val("");

    $(".created_date").prop("readonly", true);
    $(".client_group").show();
    $(".client_search_class").prop("required",true);
    $(".internal_tasks_group").hide();
    $("#internal_checkbox").prop("checked",false);
    $(".infiles_link").show();
    $("#attachments_text").hide();
    $("#hidden_infiles_id").val("");
    $("#add_infiles_attachments_div").html("");
    $("#attachments_infiles").hide();
    $("#idtask").val("");

    $("#hidden_copied_files").val("");
    $("#hidden_copied_notes").val("");
    $("#hidden_copied_infiles").val("");

    $(".auto_close_task").prop("checked",false);
    $(".accept_recurring").prop("checked",false);
    $(".accept_recurring_div").hide();
    $("#recurring_checkbox1").prop("checked",false);

    $("#open_task").prop("checked",false);
    $(".allocate_user_add").removeClass("disable_user");
    $(".allocate_email").removeClass("disable_user");
    $(".allocate_email").val("");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      data:{fileid:"0"},
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
      }
    })
  }

  if($(e.target).hasClass('tasks_li_internal'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);
    $(".task-choose_internal:first-child").text($(e.target).text());
    var project_id = $(e.target).attr("data-project");
    if(project_id == "0"){
      $(".select_project").val("");
    }
    else{
      $(".select_project").val(project_id);
    }
  }
  if($(e.target).hasClass('mark_as_complete'))
  {
    var task_id = $(e.target).attr("data-element");
    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if($(e.target).hasClass('auto_close_task_complete'))
    {
      $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'" class="common_black_button yes_mark_complete">Yes</a><a href="javascript:" class="common_black_button no_mark_complete" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'">No</a></p>',fixed:true,width:"800px"});
    }
    else{
      $("body").addClass("loading");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
        type:"post",
        data:{task_id:task_id,type:"0"},
        success:function(resultval)
        {
          $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
          var layout = $("#hidden_compressed_layout").val();
          var view = $(".select_view").val();
          if(layout == "1")
          {
            var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
            var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
            if (typeof nexttask_id !== "undefined") {
              var taskidval = nexttask_id;
            }
            else if (typeof prevtask_id !== "undefined") {
              var taskidval = prevtask_id;
            }
            else{
              var taskidval = '';
            }

            $("#task_tr_"+task_id).next().detach();
            $("#task_tr_"+task_id).detach();
            $("#hidden_tasks_tr_"+task_id).detach();

            $("#task_tr_"+taskidval).show();
            $("#task_tr_"+taskidval).next().show();
            $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

            var opentask = $("#open_task_count_val").html();
            var countopen = parseInt(opentask) - 1;
            $("#open_task_count_val").html(countopen);
            $("body").removeClass("loading");
          }
          else{
            setTimeout(function() {
              var user_id = $(".select_user_home").val();
              $.ajax({
                url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
                type:"post",
                data:{user_id:user_id},
                dataType:"json",
                success: function(result)
                {
                  
                  $("#task_body_open").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);
                  $(".taskname_sort_val").find(".2bill_image").detach();
                  var layout = $("#hidden_compressed_layout").val();
                  $(".tasks_tr").hide();
                  $(".tasks_tr").next().hide();
                  $(".hidden_tasks_tr").hide();
                  var view = $(".select_view").val();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);

                  $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                  if(layout == "0")
                  {
                    if(taskidval != "")
                    {
                      // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                    }
                  }
                  else{
                    $("#"+taskidval).show();
                    $("#"+taskidval).next().show();
                    var hidden_tr = taskidval.substr(8);
                    $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                  }
                  if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","233px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                  }
                  $("body").removeClass("loading");
                }
              })
            },2000);
          }
        }
      });
    }
  }
  if($(e.target).hasClass('yes_mark_complete'))
  {
    var task_id = $(e.target).attr("data-task");
    var nexttask_id = $(e.target).attr("data-next");
    var prevtask_id = $(e.target).attr("data-prev");

    $("body").addClass("loading");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
      type:"post",
      data:{task_id:task_id,type:"1"},
      success:function(resultval)
      {
        $(".alert_message_div").html('<p class="alert alert-info" style="font-size: 17px;margin: 0px;width: 96%;">'+resultval+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 30px;">&times;</span></button></p>');
        var layout = $("#hidden_compressed_layout").val();
        var view = $(".select_view").val();
        if(layout == "1")
        {
          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
          if (typeof nexttask_id !== "undefined") {
            var taskidval = nexttask_id;
          }
          else if (typeof prevtask_id !== "undefined") {
            var taskidval = prevtask_id;
          }
          else{
            var taskidval = '';
          }

          $("#task_tr_"+task_id).next().detach();
          $("#task_tr_"+task_id).detach();
          $("#hidden_tasks_tr_"+task_id).detach();

          $("#task_tr_"+taskidval).show();
          $("#task_tr_"+taskidval).next().show();
          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#E1E1E1");

          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
          var opentask = $(".hidden_allocated_tr").length;
          var authored = $(".hidden_author_tr").length;
          $("#redline_task_count_val").html(redline);
          $("#open_task_count_val").html(opentask);
          $("#authored_task_count_val").html(authored);
          $("body").removeClass("loading");
        }
        else{
          setTimeout(function() {
            var user_id = $(".select_user_home").val();
            $.ajax({
              url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                
                $("#task_body_open").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);
                $(".taskname_sort_val").find(".2bill_image").detach();
                var layout = $("#hidden_compressed_layout").val();
                $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                var view = $(".select_view").val();
                if(view == "3")
                {
                  if(layout == "1")
                  {
                    $(".author_tr:first").show();
                    $(".author_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_author_tr").show();
                    $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".author_tr").show();
                    $(".author_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_author_tr").hide();
                  }
                }
                else if(view == "2"){
                  $("#open_task_count").hide();
                  $("#redline_task_count").show();
                  $("#authored_task_count").hide();
                  if(layout == "1")
                  {
                    var i = 1;
                    $(".redline_indication").each(function() {
                      if(i == 1)
                      {
                        if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                        {
                          $(this).parents(".allocated_tr").show();
                          $(this).parents(".allocated_tr").next().show();
                          i++;
                        }
                      }
                    });
                    $(".table_layout").show();
                    $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    
                    var j = 1;
                    $(".redline_indication_layout").each(function() {
                      if(j == 1)
                      {
                        if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                        {
                          $(this).parents(".hidden_allocated_tr").find("td").css("background","#E1E1E1");
                          j++;
                        }
                      }
                    });
                  }
                  else{
                    $(".redline_indication").parents(".allocated_tr").show();
                    $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }
                }
                else if(view == "1"){
                  if(layout == "1")
                  {
                    $(".allocated_tr:first").show();
                    $(".allocated_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_allocated_tr").show();
                    $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#E1E1E1");
                  }
                  else{
                    $(".allocated_tr").show();
                    $(".allocated_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }
                }

                var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                var opentask = $(".hidden_allocated_tr").length;
                var authored = $(".hidden_author_tr").length;
                $("#redline_task_count_val").html(redline);
                $("#open_task_count_val").html(opentask);
                $("#authored_task_count_val").html(authored);

                $("[data-toggle=popover]").popover({
                    html : true,
                    content: function() {
                      var content = $(this).attr("data-popover-content");
                      return $(content).children(".popover-body").html();
                    },
                    title: function() {
                      var title = $(this).attr("data-popover-content");
                      return $(title).children(".popover-heading").html();
                    }
                });
                if(layout == "0")
                {
                  if(taskidval != "")
                  {
                    // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                  }
                }
                else{
                  $("#"+taskidval).show();
                  $("#"+taskidval).next().show();
                  var hidden_tr = taskidval.substr(8);
                  $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#E1E1E1");
                }
                if(layout == "1")
                {
                  $(".open_layout_div").addClass("open_layout_div_change");
                  var open_tasks_height = $(".open_layout_div").height();
                  var margintop = parseInt(open_tasks_height);
                  $(".open_layout_div").css("position","fixed");
                  $(".open_layout_div").css("height","312px");
                  if(open_tasks_height > 312)
                  {
                    $(".open_layout_div").css("overflow-y","scroll");
                  }
                  if(open_tasks_height < 50)
                  {
                    $(".table_layout").css("margin-top","20px");
                  }
                    else{
                      $(".table_layout").css("margin-top","233px");
                    }
                }
                else{
                  $(".open_layout_div").removeClass("open_layout_div_change");
                  $(".open_layout_div").css("position","unset");
                  $(".open_layout_div").css("height","auto");
                  $(".open_layout_div").css("overflow-y","unset");
                    $(".table_layout").css("margin-top","0px");
                }
                $("body").removeClass("loading");
              }
            })
          },2000);
        }
        $.colorbox.close();
      }
    });
  }




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
