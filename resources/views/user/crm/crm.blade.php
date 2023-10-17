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
<?php
DB::table('request_others')->where('other_content','')->where('status',0)->delete();
DB::table('request_purchase_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_purchase_invoice')->where('specific_invoice','')->where('status',0)->delete();
DB::table('request_sales_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_sales_invoice')->where('specific_invoice','')->where('sales_invoices','')->where('status',0)->delete();
DB::table('request_bank_statement')->where('bank_id','')->where('statment_number','')->where('status',0)->delete();
DB::table('request_cheque')->where('bank_id','')->where('specific_number','')->where('status',0)->delete();
DB::table('request_cheque_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
?>
<style>
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
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
    #colorbox, #cboxWrapper { z-index:99999999999; }
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
<!-- Modal -->
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 999999999999999;">
  <div class="modal-dialog modal-md" role="document" style="z-index: 999999">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/admin_request_add')?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Add Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control" name="category" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="add_items_div">
            <div class="row single_item_div">
              <div class="col-lg-10">
                <div class="form-group">
                  <label>Enter Item Name</label>
                  <input type="text" required class="form-control" name="request_item[]" placeholder="Enter Item Name">
                </div>      
              </div>
              <div class="col-lg-2">
                <a href="javascript:" class="plus_add">+</a>
              </div>
          </div>
          </div>
          
          

          
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary common_black_button" value="Add New Category">
        </div>
      @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade edit_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 999999999999999;">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/admin_request_edit_form')?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Edit Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control category_edit" name="category_edit" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="edit_items_div">
            
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="category_id_edit" id="category_id_edit" value="">
          <input type="submit" class="btn btn-primary common_black_button" value="Update Category">
        </div>
      @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade" id="setup_request_modal" tabindex="-1" role="dialog" aria-labelledby="setup_request_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".add_model" style="margin-right: 20px;margin-top: 10px;">Add Setup Category</a>
         <h4 class="modal-title" id="exampleModalLabel">Setup Request Categories</h4>
      </div>
      <div class="modal-body modal_max_height">
        <?php $requestlist = DB::table('request_category')->where('practice_code',Session::get('user_practice_code'))->get(); ?>
        <table class="table own_table_white">
          <thead>
            <tr>
                <th width="5%" style="text-align: left;">S.No</th>
                <th style="text-align: left;">Category Name</th>
                <th>Signature</th>
                <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $output='';
            $i=1;
            if(($requestlist)){
              foreach ($requestlist as $request) {
                $sub_category=DB::table('request_sub_category')->where('category_id', $request->category_id)->get();
                if(($sub_category)){
                  $outputsub='';
                  foreach ($sub_category as $sub) {
                    $outputsub.=$sub->sub_category_name.'<br/>';
                  }              
                }
                else{
                  $outputsub='';
                }
                if($request->status == 0){
                  $status = '<a href="'.URL::to('user/admin_deactive_request/'.base64_encode($request->category_id)).'"><i class="fa fa-check deactive_class" style="color:#00b348;"></i></a>';
                }
                else{
                  $status = '<a href="'.URL::to('user/admin_active_request/'.base64_encode($request->category_id)).'"><i class="fa fa-times active_class" style="color:#f00;"></i></a>';
                }
                $output.='<tr>
                            <td>'.$i.'</td>
                            <td><b>'.$request->category_name.'</b><br/>'.$outputsub.'</td>
                            <td width="300px;">
                            <textarea placeholder="Enter Signature" data-element="'.base64_encode($request->category_id).'" class="form-control class_signature" style="height:100px;">'.$request->signature.'</textarea>
                            </td>
                            <td style="font-size:15px; text-align:center">
                            '.$status.'&nbsp;&nbsp;
                            <a href="javascript:"><i class="fa fa-pencil-square edit_icon" data-element="'.base64_encode($request->category_id).'"></i></a>&nbsp;&nbsp;
                            <a href="'.URL::to('user/admin_delete_request/'.base64_encode($request->category_id)).'"><i class="fa fa-trash"></i></a>
                            
                            </td>
                          </tr>';
                          $i++;
              }
            }
            else{
              $output='<tr><td colspan="4" align="center">Empty</td></tr>';
            }
            echo $output;
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">        
        <button type="button" class="common_black_button">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php
$crm_settings = DB::table("request_settings")->where('practice_code',Session::get('user_practice_code'))->first();
?>
<div class="modal fade crm_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="crm_settings_form" id="crm_settings_form" method="post" action="<?php echo URL::to('user/save_crm_settings'); ?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >Client Request Settings</h4>
            </div>
            <div class="modal-body">  
                <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                <?php
                if($crm_settings->email_header_url == '') {
                  $default_image = DB::table("email_header_images")->first();
                  if($default_image->url == "") {
                    $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                  }
                  else{
                    $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                  }
                }
                else {
                  $image_url = URL::to($crm_settings->email_header_url.'/'.$crm_settings->email_header_filename);
                }
                ?>
                <img src="<?php echo $image_url; ?>" class="email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                <input type="button" name="email_header_img_btn" class="common_black_button email_header_img_btn" value="Browse">
                <h4>Client Request CC Email ID</h4>
                <input id="validation-cc-email"
                       class="form-control"
                       placeholder="Enter Client Request CC Email ID"
                       value="<?php echo $crm_settings->crs_cc_email; ?>"
                       name="crs_cc_email"
                       type="text"
                       required>  
            </div>
            <div class="modal-footer" style="clear:both">
              <input type="submit" class="common_black_button" id="crs_submit" value="Submit">
            </div>
          @csrf
</form>
        </div>
  </div>
</div>
<div class="modal fade change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_crm_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Client Request System                 
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
        
            <div class="col-lg-12 text-right" style="line-height: 35px;text-align: right">
                <!-- <a href="javascript:" class="fa fa-cog setup_request" data-toggle="modal" data-target="#setup_request_modal" title="Setup Request Categories" style="font-size: 50px;"></a> --> 
                <a href="javascript:" class="common_black_button setup_request" data-toggle="modal" data-target="#setup_request_modal" title="Setup Request Categories">Setup Request Categories</a>
                <a href="javascript:" id="settings_crm" class="fa fa-cog common_black_button" title="Client Request Settings"></a>
            </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:40px">
  </div>
  

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>
<div class="col-lg-12" style="margin-top: 20px;">
<table class="display nowrap fullviewtablelist own_table_white" id="crm_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: center;">Requests</th>
                            <th style="text-align: center;">Outstanding</th>
                            <th style="text-align: center;">Awaiting Approval</th> 
                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(($clientlist)){              
                              foreach($clientlist as $key => $client){
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
                          ?>
                            <tr class="edit_task " id="clientidtr_<?php echo $client->id; ?>">
                                <td style=""><?php echo $i; ?></td>
                                <td align="left"><a href="<?php echo URL::to('user/client_request_manager/'.base64_encode($client->client_id))?>" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style=""><?php echo $client->client_id; ?></a></td>
                                <td align="left"><a href="<?php echo URL::to('user/client_request_manager/'.base64_encode($client->client_id))?>" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style=""><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><?php echo $client->firstname; ?></td>
                                <td align="left"><?php echo $client->surname; ?></td>

                                <td align="center">                                  
                                  <?php echo $request_count?>
                                </td>
                                <td align="center">                                  
                                  <?php echo $countoutstanding; ?>
                                </td>
                                <td align="center">                                  
                                  <?php echo $awaiting_request?>
                                </td>
                            </tr>
                            <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="8" align="center">Empty</td></tr>';
                            }
                          ?> 
                        </tbody>
                    </table>
</div>
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





<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">


<script>
$(function(){
    $('#crm_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});

$(window).click(function(e) { 
if(e.target.id == "settings_crm")
{
  $(".crm_settings_modal").modal("show");
}
if($(e.target).hasClass('email_header_img_btn')) {
  var r = confirm("Are you sure you want to change the Client Request Email Header Image?");
  if(r) {
    $(".change_header_image").modal("show");

    Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
  }
}
if($(e.target).hasClass('plus_add'))
{
  $("#add_items_div").append('<div class="row single_item_div"><div class="col-lg-10"><div class="form-group"><label>Enter Item Name</label><input type="text" required class="form-control" placeholder="Enter Item Name" name="request_item[]"></div> </div><div class="col-lg-2"><a href="javascript:" class="plus_add">+</a></div></div>');

  $(".plus_add").each(function(){
    $(this).parent().html('<a href="javascript:" class="minus_remove">-</a>');
  });
  $(".minus_remove:last").parent().html('<a href="javascript:" class="minus_remove">-</a><a href="javascript:" class="plus_add">+</a>');
}
if($(e.target).hasClass('minus_remove'))
{
  $(e.target).parents(".single_item_div").detach();
  $(".plus_add").each(function(){
    $(this).parent().html('<a href="javascript:" class="minus_remove">-</a>');
  });
  $(".minus_remove:last").parent().html('<a href="javascript:" class="minus_remove">-</a><a href="javascript:" class="plus_add">+</a>');
}
if($(e.target).hasClass('edit_icon'))
{
  var id = $(e.target).attr("data-element");
  $("#category_id_edit").val(id);
  $.ajax({
    url:"<?php echo URL::to('user/admin_request_edit_category'); ?>",
    type:"get",
    data:{id:id},
    dataType:"json",
    success: function(result) {
      $(".edit_model").modal("show");
      $("#edit_items_div").html("");
      $(".category_edit").val(result['category_name']);
      var items = result['sub_category_name'];
      var items_sep = items.split("||");
      $.each(items_sep, function(index,value)
      {
        $("#edit_items_div").append('<div class="row single_item_div_edit"><div class="col-lg-10"><div class="form-group"><label>Enter Item Name</label><input type="text" required class="form-control" placeholder="Enter Item Name" name="request_item_edit[]" value="'+value+'"></div> </div><div class="col-lg-2"></div></div>')
      });
    }
  })
}
})

$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $signature_value = $('.class_signature');    
    if($(e.target).hasClass('class_signature'))
    {        
        var input_val = $(e.target).val();  
        var id = $(e.target).attr("data-element");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, id);   
    }    
});
function doneTyping (signature_value,id) {
  $.ajax({
        url:"<?php echo URL::to('user/admin_request_signature')?>",
        type:"post",
        dataType:"json",
        data:{value:signature_value, id:id},
        success: function(result) {            
            
        }
      });
}
Dropzone.options.ImageUploadEmail = {
    maxFiles: 1,
    acceptedFiles: ".png",
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
            if(obj.error == 1) {
              $("body").removeClass("loading");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Request Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Request Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
    },
};
</script>




@stop