@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
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
  background: #f5f5f5 !important;
}*/
.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:14px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:14px !important;
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
.ui-widget{z-index: 999999999}

.dropzone .dz-preview.dz-image-preview {

    background: #949400 !important;

}

.dz-message span{text-transform: capitalize !important; font-weight: bold;}

.trash_imageadd{

  cursor:pointer;

}

.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{

      margin-top: 40px;

}

.dropzone .dz-preview {

  margin:0px !important;

  min-height:0px !important;

  width:100% !important;

  color:#000 !important;

    float: left;

  clear: both;

}

.dropzone .dz-preview p {

  font-size:12px !important;

}

.remove_dropzone_attach{

  color:#f00 !important;

  margin-left:10px;

}

.img_div_add{

        border: 1px solid #000;

    width: 300px;

    position: absolute !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}

.dropzone.dz-clickable{margin-bottom: 0px !important;}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }


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

.modal_load_one {
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
body.loading_one {
    overflow: hidden;   
}
body.loading_one .modal_load_one {
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

<img id="coupon" />

<div class="modal fade alert_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="exampleModalLabel">Alert</h4>
        </div>
        <div class="modal-body">
          <div class="sub_title3 alert_content" style="line-height: 25px;">
            
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="common_black_button yes_hit">Yes</button>
            <button type="button" class="common_black_button no_hit">No</button>
        </div>
      </div>
    </div>
</div>
<div class="content_section" style="margin-bottom:200px">

  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                TA System                 
            </h4>
    </div>
  <div class="row">
    <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div>
    <div class="col-lg-9">
      <div style="float: left; margin-right: 10px">
        <label style="float: left; padding-top: 10px;">Search Client ID: </label>
      </div>
      <div style="float: left; margin-right: 6px;">
          
        <input type="text" class="form-control client_common_search ui-autocomplete-input" placeholder="Enter Client Name" style="font-weight: 500;margin-bottom: 13px;margin-top:2px;width:350px;float:left" value="" autocomplete="off">
        <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
        <input type="button" class="common_black_button load_single_client" value="Load" style="width: 100px;margin-top: 3px;">
        <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="" name="client_id">
      </div>

      <div style="float: left;">
        <input type="button" class="common_black_button load_all_clients" value="Load All Clients" style="float: left;width:100%;margin-top: 3px;">
      </div>
      <div style="float: left; margin-right: 20px;margin-left:20px;margin-top:10px">
        <input type="checkbox" name="hide_inactive" class="hide_inactive" id="hide_inactive" value="1"><label for="hide_inactive" title="Hides the Inactive Clients">Hide Inactive</label>
      </div>


    </div>
    <div class="col-lg-3 text-right">
        <input type="button" class="common_black_button apply_time_allocations" value="Apply Time Allocations to all Clients">
    </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:30px">
  </div>
   

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments p{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>

<table class="display nowrap fullviewtablelist own_table_white" id="ta_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                            <th style="text-align: left;"></th>
                            <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Allocated time</th>
                            <th style="text-align: left;">Unallocated time</th>
                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td style="text-align:center; font-weight: normal !important;">No Data found</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          
                        </tr>
                        </tbody>
                    </table>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div id="report_pdf_type_two" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>
  <table class="table_style">
    <thead>
      <tr>
      <td style="text-align: left;border:1px solid #000;">#</td>
      <td style="text-align: left;border:1px solid #000;">Client Id</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">First Name</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Client Source</td>
      <td style="text-align: left;border:1px solid #000;">Date Client Since</td>
      <td style="text-align: left;border:1px solid #000;">Client Identity</td>      
      <td style="text-align: left;border:1px solid #000;">Bank Account</td>
      <td style="text-align: left;border:1px solid #000;">File Review</td>
      <td style="text-align: left;border:1px solid #000;">Risk Category</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>



<div id="report_pdf_type_two_invoice" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>

  <h3 id="pdf_title_inivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">List of Invoices issued to <span class="invoice_filename"></span></h3>  

  <table class="table_style">
    <thead>
      <tr>
      <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody_invoice">

    </tbody>
  </table>
</div>



<div class="modal_load modal_client_all"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Please wait until All clients are loaded... </h5></div>

<div class="modal_load modal_single_client"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Please wait until this client is loaded... </h5></div>

<div class="modal_load"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Applying Time Allocations to Clients.</h5></div>
<div class="modal_load_one"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Applying Time Allocations to this Client.</h5></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until we Allocate / Unallocate time for all the clients</p>
  <p style="font-size:18px;font-weight: 600;">Processing Clients: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">



<!-- Page Scripts -->
<script>
$(function(){
    $('#ta_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        columnDefs: [ {
        'targets': [0,6,7], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
        }]
    });
});
function ajax_response()
{
	setTimeout(function(){
		
		$.ajax({
			url:"<?php echo URL::to('user/ta_system_ajax_response'); ?>",
			type:"post",
			success: function(result){
				$("#clients_tbody").html(result);
        $('#ta_expand').DataTable({
            fixedHeader: {
              headerOffset: 75
            },
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            columnDefs: [ {
            'targets': [0,6,7], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
            }]
        });
        $(".apply_time_allocations").hide();
				$("body").removeClass("loading");
			}
		})
	},1000)
}

function next_integrity_check(count)
{
  var client_id = $(".load_unallocated:eq("+count+")").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/load_unallocated_time_for_client'); ?>",
    type:"post",
    dataType:"json",
    data:{client_id:client_id},
    success:function(result)
    {
      setTimeout( function() {
        $(".allocated_time_client_"+client_id).html(result['allocated']);
        $(".unallocated_time_client_"+client_id).html(result['unallocated']);

        var countval = count + 1;
        if($(".load_unallocated:eq("+countval+")").length > 0)
        {
          next_integrity_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
        }
      },200);
    }
  });
}
$(window).click(function(e){
  if($(e.target).hasClass('active_client_list_pms'))
  {
    var client_id=$("#client_search_hidden_infile").val();
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
  if($(e.target).hasClass('apply_time_allocations'))
  {
    $("body").addClass("loading");
    var table = $('#ta_expand').DataTable();
    table.destroy();
    $(".alert_modal").modal("hide");
    ajax_response();
  }
  if($(e.target).hasClass('no_hit'))
  {
    $(".alert_modal").modal("hide");
  }
  if($(e.target).hasClass('load_unallocated'))
  {
    $("body").addClass("loading_one");
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/load_unallocated_time_for_client'); ?>",
      type:"post",
      dataType:"json",
      data:{client_id:client_id},
      success:function(result)
      {
        $(".allocated_time_client_"+client_id).html(result['allocated']);
        $(".unallocated_time_client_"+client_id).html(result['unallocated']);
        $("body").removeClass("loading_one");
      }
    })
  }


if($(e.target).hasClass('load_all_clients'))
{
  //$("body").addClass("loading");
  $(".modal_client_all").show();
  setTimeout(function() {
      $("#ta_expand").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/load_all_clients_ta_system'); ?>",
        type:"post",
        success:function(result)
        {
          $("#hidden_client_id").val("all");
          $("#clients_tbody").html(result);
          $("#client_search_hidden_infile").val("");
          $(".client_common_search").val("");
          $(".modal_client_all").hide();
          $('#ta_expand').DataTable({
              fixedHeader: {
                headerOffset: 75
              },
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              columnDefs: [ {
              'targets': [0,6,7], // column index (start from 0)
              'orderable': false, // set orderable false for selected columns
              }]
          });

          $("body").removeClass("loading");
        }
      })
  },1000);
}

if($(e.target).hasClass('load_single_client'))
{
  var client_id = $("#client_search_hidden_infile").val();
  if(client_id == "")
  {
    alert("Please select the client and then click on the load button");
  }
  else{
    //$("body").addClass("loading");
    $(".modal_single_client").show();
    $("#ta_expand").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/load_single_client_ta_system'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $("#hidden_client_id").val(client_id);
        $("#clients_tbody").html(result);
        $('#ta_expand').DataTable({
            fixedHeader: {
              headerOffset: 75
            },
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            columnDefs: [ {
            'targets': [0,6,7], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
            }]
        });
        $(".modal_single_client").hide();
        //$("body").removeClass("loading");
      }
    })
  }
}

if($(e.target).hasClass('hide_inactive'))
{
  $(".edit_task").show();
  if($(".show_incomplete").is(":checked"))
  {
    $(".complete_cls").hide();
  }
  if($(e.target).is(":checked"))
  {
    $(".inactive_cls").hide();
  }
}



  // if($(e.target).hasClass("apply_time_allocations"))
  // {
  //   $("body").addClass("loading_apply");
  //   var countintegrity = $(".load_unallocated").length;
  //   $("#apply_last").html(countintegrity);
  //   var client_id = $(".load_unallocated:eq(0)").attr("data-element");
  //   $.ajax({
  //     url:"<?php echo URL::to('user/load_unallocated_time_for_client'); ?>",
  //     type:"post",
  //     dataType:"json",
  //     data:{client_id:client_id},
  //     success:function(result)
  //     {
  //       setTimeout( function() {
  //         $(".allocated_time_client_"+client_id).html(result['allocated']);
  //         $(".unallocated_time_client_"+client_id).html(result['unallocated']);
  //         if($(".load_unallocated:eq(1)").length > 0)
  //         {
  //           next_integrity_check(1);
  //           $("#apply_first").html(1);
  //         }
  //         else{
  //           $("body").removeClass("loading_apply");
  //         }
  //     },200);
  //     }
  //   });
  // }
});


$(".client_common_search").autocomplete({
  source: function(request, response) {        
    $.ajax({
      url:"<?php echo URL::to('user/client_review_client_common_search'); ?>",
      dataType: "json",
      data: {
          term : request.term
      },
      success: function(data) {
          response(data);
      }
    });
  },
  delay:1000,
  minLength: 1,
  select: function( event, ui ) {
    $("#client_search_hidden_infile").val(ui.item.id);
  }
  });
</script>


@stop