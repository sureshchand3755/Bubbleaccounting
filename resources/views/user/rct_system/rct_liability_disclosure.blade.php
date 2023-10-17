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


<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                RCT Manager               
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

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
      <ul class="nav nav-tabs" style="margin-top: 20px;">
        <li class="nav-item ">
          <a class="nav-link" href="<?php echo URL::to('user/rct_system'); ?>">RCT Manager</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::to('user/rct_summary'); ?>">RCT Summary</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URL::to('user/rct_liability_disclosure'); ?>">Liability Disclosure</a>
        </li>
      </ul>
    </div>
</div>

<div class="col-lg-12 padding_00">
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px;">
  
  

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>

<table class="display nowrap fullviewtablelist own_table_white" id="ta_expand" width="100%" style="max-width: 100%; margin-top:0px !important; ">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: center;">ActiveClient</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Email Monthly Disclosure</th>
                            <th style="text-align: left;">Pay Liability</th>
                            <th style="text-align: left;">Pay from RCT</th>
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $ival=1;
                            if(($clientlist)){              
                              foreach($clientlist as $client){
                                if($client->active == 2)
                                {
                                  $color='color:#f00;';
                                }
                                else{
                                  $color="";
                                }
                                
                                $liability_detail = DB::table('rct_liability_disclosure')->where('client_id',$client->client_id)->first();
                                $email_monthly = 0;
                                $pay_liability = 0;
                                $pay_from_rct = 0;
                                if($liability_detail) {
                                    $email_monthly = $liability_detail->email_monthly_disclosure;
                                    $pay_liability = $liability_detail->pay_liability;
                                    $pay_from_rct = $liability_detail->pay_from_rct;
                                }
                          ?>
                            <tr class="edit_task tr_client_td_<?php echo $client->client_id; ?>">
                                <td style="<?php echo $color; ?>"><?php echo $ival; ?></td>
                                <td align="left" style="<?php echo $color; ?>"><?php echo $client->client_id; ?></td>
                                <td align="center"><img class="active_client_list_tm1" data-iden="<?php echo $client->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
                                <td align="left" style="<?php echo $color; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></td>
                                <td align="left" style="<?php echo $color; ?>"><?php echo $client->firstname; ?></td>
                                <td align="left" style="<?php echo $color; ?>"><?php echo $client->surname; ?></td>
                                <td align="center">
                                    <spam class="hidden_monthly_disclosure" style="display:none"><?php if($email_monthly == 1) { echo '1'; }  else { echo '0'; } ?></spam>
                                    <input type="checkbox" name="email_monthly_disclosure" class="email_monthly_disclosure" id="email_monthly_disclosure_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" <?php if($email_monthly == 1) { echo 'checked'; } ?>><label for="email_monthly_disclosure_<?php echo $client->client_id; ?>">&nbsp;</label>
                                </td>
                                <td align="center">
                                    <spam class="hidden_pay_liability" style="display:none"><?php if($pay_liability == 1) { echo '1'; }  else { echo '0'; } ?></spam>
                                    <input type="checkbox" name="pay_liability" class="pay_liability" id="pay_liability_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" <?php if($pay_liability == 1) { echo 'checked'; } ?>><label for="pay_liability_<?php echo $client->client_id; ?>">&nbsp;</label>
                                </td>
                                <td align="center">
                                    <spam class="hidden_pay_from_rct" style="display:none"><?php if($pay_from_rct == 1) { echo '1'; }  else { echo '0'; } ?></spam>
                                    <input type="checkbox" name="pay_from_rct" class="pay_from_rct" id="pay_from_rct_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" <?php if($pay_from_rct == 1) { echo 'checked'; } ?>><label for="pay_from_rct_<?php echo $client->client_id; ?>">&nbsp;</label>
                                </td>
                            </tr>
                            <?php
                              $ival++;
                              }              
                            }
                            if($ival == 1)
                            {
                              echo'<tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  </tr>';
                            }
                          ?> 
                        </tbody>
                    </table>
</div>
</div>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">



<!-- Page Scripts -->
<script>
$(".editclass_letter").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('user/edit_rctletterpad') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm_letter").modal("toggle");
         $(".editsp_letter").show();
         $(".name_class_letter").val(result['name']);
         $(".salution_class").val(result['salution']);
         $(".name_id_letter").val(result['id']);         
         $("#img_id").attr("src",result['image']);

    }
  })
});





$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('user/edit_rctsalution') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".desc_class").val(result['description']);         
         $(".name_id").val(result['id']);
    }
  })
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Are You Sure want to delete this Subcontractor?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  
  if($(e.target).hasClass('email_monthly_disclosure'))
  {
      var table = $('#ta_expand').DataTable();

      var order = table.order();

      $("body").addClass("loading");
      var client_id = $(e.target).attr("data-element");
      $("#ta_expand").dataTable().fnDestroy();
      if($(e.target).is(":checked"))
      {
          var status = 1;
          $(e.target).parents("td").find(".hidden_monthly_disclosure").html(1);
      }
      else{
          var status = 0;
          $(e.target).parents("td").find(".hidden_monthly_disclosure").html(0);
      }
      
      $.ajax({
          url:"<?php echo URL::to('user/save_email_monthly_disclosure'); ?>",
          type:"post",
          data:{client_id:client_id,status:status},
          success:function(result)
          {
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
                aaSorting: [[ order[0][0], order[0][1] ]]
            });
            $("body").removeClass("loading");
          }
      })
  }
  if($(e.target).hasClass('pay_liability'))
  {
      var table = $('#ta_expand').DataTable();

      var order = table.order();

      $("body").addClass("loading");
      var client_id = $(e.target).attr("data-element");
      $("#ta_expand").dataTable().fnDestroy();
      if($(e.target).is(":checked"))
      {
          var status = 1;
          $(e.target).parents("td").find(".hidden_pay_liability").html(1);

          $("#pay_from_rct_"+client_id).prop("checked",false);
          $(e.target).parents("tr").find(".hidden_pay_from_rct").html(0);
      }
      else{
          var status = 0;
          $(e.target).parents("td").find(".hidden_pay_liability").html(0);
      }
      
      $.ajax({
          url:"<?php echo URL::to('user/save_pay_liability'); ?>",
          type:"post",
          data:{client_id:client_id,status:status},
          success:function(result)
          {
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
                aaSorting: [[ order[0][0], order[0][1] ]]
              });
             $("body").removeClass("loading"); 
          }
      })
  }
  if($(e.target).hasClass('pay_from_rct'))
  {
      var table = $('#ta_expand').DataTable();

      var order = table.order();

      $("body").addClass("loading");
      var client_id = $(e.target).attr("data-element");
      $("#ta_expand").dataTable().fnDestroy();
      if($(e.target).is(":checked"))
      {
          var status = 1;
          $(e.target).parents("td").find(".hidden_pay_from_rct").html(1);

          $("#pay_liability_"+client_id).prop("checked",false);
          $(e.target).parents("tr").find(".hidden_pay_liability").html(0);
      }
      else{
          var status = 0;
          $(e.target).parents("td").find(".hidden_pay_from_rct").html(0);
      }
      
      $.ajax({
          url:"<?php echo URL::to('user/save_pay_from_rct'); ?>",
          type:"post",
          data:{client_id:client_id,status:status},
          success:function(result)
          {
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
                aaSorting: [[ order[0][0], order[0][1] ]]
              });
             $("body").removeClass("loading"); 
          }
      })
  }
});
$('#form-validation').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
$('#form-validation-edit').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
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
        info: false
    });
});
$(window).change(function(e){
  if($(e.target).hasClass('select_active_month_rct'))
  {
    $("body").addClass("loading");
    var value = $(e.target).val();
    $("#ta_expand").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/set_rct_active_month'); ?>",
      type:"post",
      data:{date:value},
      success: function(result)
      {
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
            info: false
        });
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('select_active_month'))
  {
    $("body").addClass("loading");
    var value = $(e.target).val();
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/set_rct_active_month_individual'); ?>",
      type:"post",
      data:{date:value,client_id:client_id},
      dataType:"json",
      success: function(result)
      {
        $(".tr_client_td_"+client_id).find(".deduction_clientid").html(result['deduction']);
        $(".tr_client_td_"+client_id).find(".gross_clientid").html(result['gross']);
        $(".tr_client_td_"+client_id).find(".net_clientid").html(result['net']);
        $(".tr_client_td_"+client_id).find(".count_clientid").html(result['count']);
        $(".tr_client_td_"+client_id).find(".emails_clientid").html(result['email_text']);

        $("body").removeClass("loading");
      }
    })
  }
})
$(window).click(function(e) {
  if($(e.target).hasClass('rct_settings'))
  {
    $(".rct_settings_modal").modal("show");
  }
  if($(e.target).hasClass('view_liability_assessment'))
  {
    e.preventDefault();
    var href= $(e.target).attr("href");
    var active_month = $(e.target).parents("tr").find(".select_active_month").val();
    window.location.replace(href+'?active_month='+active_month);
  }
  if($(e.target).hasClass('view_rct_manager'))
  {
    e.preventDefault();
    var href= $(e.target).attr("data-element");
    var active_month = $(e.target).parents("tr").find(".select_active_month").val();
    window.location.replace(href+'?active_month='+active_month);
  }
})
</script>


@stop