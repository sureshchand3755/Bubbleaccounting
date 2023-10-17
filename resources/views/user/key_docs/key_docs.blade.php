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
  .fa-unlock {
    color:green;
    font-weight: 800;
  }
  .fa-lock {
    color:#f00;
    font-weight: 800;
  }
  .quick_links img{
    width: 34px;
    transition: 0.5s all ease-in-out;
  }
  .quick_links:hover img{
    transform: scale(1.5);
  }
  .quick_links:hover{
    color:yellow;
    background-color:black;
  }
.client_details_table > tbody > tr > td{
  border-top: 0px solid;
}
.opening_bal_h5 {
  float: left;
  width: 100%;
  font-size: 18px;
}
#client_account_tbody > tr > td {
    padding: 12px !important;
}
.opening_bal_transaction_h5 {
  float: left;
  width: 100%;
  font-size: 18px;
}
#transaction_tbody > tr > td {
    padding: 12px !important;
}
#receipt_tbody > tr > td{
  padding:12px !important;
}
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
  margin-left: 8px;
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

* {box-sizing: border-box}
/* Style the tab */
.tab {
  float: left;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
  width: 17%;
}

/* Style the buttons inside the tab */
.tab button {
  display: block;
  background-color: inherit;
  color: black;
  padding: 22px 16px;
  width: 100%;
  border: none;
  outline: none;
  text-align: left;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #616161;
  color:#fff;
}

/* Create an active/current "tab button" class */
.tab button.active {
  background-color: #616161;
  color:#fff;
}

/* Style the tab content */
.tabcontent {
  float: left;
  padding: 0px 12px;
  width: 80%;
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


<!-- <div class="modal fade receipt_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title receipt_payment_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white" id="result_payment_receipt" style="background: #fff;"></table>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div> -->



<div class="modal fade letters_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Files</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-12">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">
                        <form action="<?php echo URL::to('user/upload_key_docs_letter'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadprogress" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">                            
                            <input name="hidden_client_id_letters" id="hidden_client_id_letters" type="hidden" value="">
                            <input type="hidden" name="hidden_type_key_docs" id="hidden_type_key_docs" value="">
                        @csrf
</form>
                     </div>
                  </div>
                  
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>



<div class="modal fade download_pdf_folder_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Download Folder Path:</h4>
      </div>
      <div class="modal-body">
          <h4>Folder Path:</h4>
          <input type="text" name="download_folder_path" class="form-control download_folder_path" placeholder="Copy & Paste Folder Path" value="">
          <h4>Create Folder(Optional):</h4>
          <input type="text" name="create_folder" class="form-control create_folder" placeholder="Folder Name" value="">
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button save_pdfs_in_folder" value="Download Selected Pdf">
      </div>
    </div>
  </div>
</div>
<div class="modal fade sent_to_client" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:30%">
    <form id="send_request_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Selected PDF</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:9px">From:</label>
            </div>
            <div class="col-md-5">
               <select name="from_user_to_client" id="from_user_to_client" class="form-control" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
                    if(($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control client_email" placeholder="Enter Email Address" name="client_search" value="" autocomplete="off" required>
              <input type="hidden" class="hidden_client_id" id="hidden_client_id" value="" name="hidden_client_id">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">CC:</label>
            </div>
            <div class="col-md-5">
            	<?php 
          $keydocs_setttings = DB::table('key_docs_settings')->where('practice_code',Session::get('user_practice_code'))->first();
				  $admin_cc = $keydocs_setttings->keydocs_cc_email;
				?> 
              <input type="text" name="cc_approval_to_client" id="cc_approval_to_client" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_to_client" id="subject_to_client" class="form-control input-sm subject_to_client" value="Invoice(s) Attached" required>
            </div>
          </div>
          <?php
          if($keydocs_setttings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($keydocs_setttings->email_header_url.'/'.$keydocs_setttings->email_header_filename);
          }
          ?>
          <div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top: 9px;">Header Image:</label>
            </div>
            <div class="col-md-10">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor_to_client" id="editor_1">
              </textarea>
            </div>
            <div class="col-md-12">
              <div id="client_attachments">
              	<h4>Attachment:</h4>
              	<img class="zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:50px">
                <img class="pdf_image" src="<?php echo URL::to('public/assets/images/pdf.png'); ?>" style="width:24px">
              	<spam class="zip_name"></spam>
                <spam class="spam_attachment"></spam>
              	<input type="hidden" name="hidden_attachment" class="hidden_attachment" value="">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
        <input type="button" class="btn btn-primary common_black_button send_request_to_client" value="Send Request to Client">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade email_selected_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:40%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Selected Attachments</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:9px">From:</label>
            </div>
            <div class="col-md-5">
               <select name="from_user_company" id="from_user_company" class="form-control" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
                    if(($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control client_email_selected" placeholder="Enter Email Address" name="client_email_selected" value="" autocomplete="off" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">CC:</label>
            </div>
            <div class="col-md-5">
              <?php 
          $admin_cc = $keydocs_setttings->keydocs_cc_email;
        ?> 
              <input type="text" id="cc_approval_selected" name="cc_approval_selected" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:9px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_to_client" class="form-control input-sm subject_selected" value="" required>
            </div>
          </div>
          <?php
          if($keydocs_setttings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($keydocs_setttings->email_header_url.'/'.$keydocs_setttings->email_header_filename);
          }
          ?>
          <div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top: 9px;">Header Image:</label>
            </div>
            <div class="col-md-10">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor_to_client" id="editor_2">
              </textarea>
            </div>
            <div class="col-md-12">
              <div id="client_attachments_selected">
                <h4>Attachment:</h4>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_selected" id="request_id_selected" value="">
        <input type="button" class="btn btn-primary common_black_button send_email_company_formation" value="Send Email">
      </div>
    </div>
  </div>
</div>
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false" data-backdrop="static" style="z-index: 9999999999999999999999999999">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Invoices</h4>
        </div>
        <div class="modal-body" style="height: 600px; font-size: 14px; overflow-y: scroll;">
          <style type="text/css">
            .account_table .account_row .account_row_td{font-size: 14px; line-height: 20px; float:left;}
            .account_table .account_row .account_row_td.left{width:40%;}
            .account_table .account_row .account_row_td.right{width:60%;}
            .tax_table_div{width: 100%; height: auto; float: left;}
            .tax_table{width: 80%; height: auto; float: left;margin-left: 10%;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; border-top:2px solid #000; }
            .class_row{width: 100%; height: 20px;}
            .details_table .class_row .class_row_td, .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .details_table .class_row .class_row_td.left{width:70%;min-height:10px; text-align: left; float: left; height:20px;}
            .details_table .class_row .class_row_td.left_corner{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right_start{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right{width:10%;text-align: right; padding-right: 10px; float: right;height:20px;}
            .tax_table .tax_row .tax_row_td.left{width:70%;text-align: left; float: left;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; float: right;}
            .details_table .class_row, .tax_table .tax_row{line-height: 30px; clear: both;}
            .company_details_class{width: 100%; margin: 0px auto; height: auto;}
            .company_details_div{width: 40%; height: auto; float: left; margin-top: 220px; margin-left: 10%}
            .firstname_div{width: 100%; float: left; margin-top: 55px;}
            .aib_account{ width: 200px; height: auto; float: right; line-height: 20px; color: #ccc; font-size: 12px; }
            .account_details_div{width: 50%; height:auto; float: left; margin-top: 220px;}
            .account_details_main_address_div{width: 100%; height: auto; float: right;}
            .account_details_address_div{width: 100%; height: auto; float: left; }
            .account_details_invoice_div{width: 200px; height: auto; float: right; clear: both; margin-top: 20px;}
            .invoice_label{width: 100%; height: auto; float: left; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px;}
            .tax_details_class_maindiv{width: 100%; min-height: 539px; float: left;}
          </style>
          <div id="letterpad_modal" style="width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('public/assets/invoice_letterpad.jpg');?>') no-repeat">
            <div class="company_details_class"></div>
            <div class="tax_details_class_maindiv">
              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                <div class="class_row class_row1"></div>
                <div class="class_row class_row2"></div>
                <div class="class_row class_row3"></div>
                <div class="class_row class_row4"></div>
                <div class="class_row class_row5"></div>
                <div class="class_row class_row6"></div>
                <div class="class_row class_row7"></div>
                <div class="class_row class_row8"></div>
                <div class="class_row class_row9"></div>
                <div class="class_row class_row10"></div>
                <div class="class_row class_row11"></div>
                <div class="class_row class_row12"></div>
                <div class="class_row class_row13"></div>
                <div class="class_row class_row14"></div>
                <div class="class_row class_row15"></div>
                <div class="class_row class_row16"></div>
                <div class="class_row class_row17"></div>
                <div class="class_row class_row18"></div>
                <div class="class_row class_row19"></div>
                <div class="class_row class_row20"></div>
              </div>
            </div>
            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
            <div class="tax_details_class"></div> 
          </div>
        </div>
        <div class="modal-footer">
            <input type="button" class="common_black_button saveas_pdf" value="Save as PDF">
            <input type="button" class="common_black_button print_pdf" value="Print">
        </div>
      </div>
  </div>
</div>
<div class="modal fade keydocs_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="keydocs_settings_form" id="keydocs_settings_form" method="post" action="<?php echo URL::to('user/save_keydocs_settings'); ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >Key Docs Settings</h4>
            </div>
            <div class="modal-body">  
                <?php
                $keydocs_setttings = DB::table('key_docs_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                ?>
                <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                <?php
                if($keydocs_setttings->email_header_url == '') {
                  $default_image = DB::table("email_header_images")->first();
                  if($default_image->url == "") {
                    $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                  }
                  else{
                    $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                  }
                }
                else {
                  $image_url = URL::to($keydocs_setttings->email_header_url.'/'.$keydocs_setttings->email_header_filename);
                }
                ?>
                <img src="<?php echo $image_url; ?>" class="keydocs_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                <input type="button" name="keydocs_email_header_img_btn" class="common_black_button keydocs_email_header_img_btn" value="Browse">

                <h4>Enter Email Signature:</h4>
                <textarea name="message_editor" id="editor1"><?php echo $keydocs_setttings->keydocs_signature; ?></textarea>
                <h4><h4>Enter CC Box:</h4>
                <input type="text" name="keydocs_cc_input" class="form-control keydocs_cc_input" value="<?php echo $keydocs_setttings->keydocs_cc_email; ?>">
            </div>
            <div class="modal-footer">  
                <input type="submit" name="submit_keydocs_settings" class="common_black_button submit_keydocs_settings" value="Submit">
            </div>
          @csrf
          </form>
        </div>
  </div>
</div>
<div class="modal fade keydocs_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 9999999">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
        
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_keydocs_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailKeydocs" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal fade document_batches_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close close_document_batch_list"><span class="close_document_batch_list" aria-hidden="true">&times;</span></button>
              <h4 class="modal-title menu-logo" >Manage Document Batches</h4>
            </div>
            <div class="modal-body">  
                <h4>Document Batches for: <spam class="doc_batch_title"></spam></h4>
                <label style="margin-top: 15px;width:100%">Name of a Batch: </label>
                <input type="text" name="name_of_batch" class="form-control name_of_batch" id="name_of_batch" value="" style="margin-top: 10px;width:65%;float:left">
                <input type="button" name="add_document_batch_client" class="add_document_batch_client common_black_button" id="add_document_batch_client" value="Add Document Batch to Client" style="margin-top: 10px">

                <h4 style="margin-top:20px">Document Batches List:</h4>
                <div style="width:100%;max-height: 500px; overflow-y: scroll">
                  <table class="table" id="document_batches_expand">
                    <thead>
                      <th>Batch Name</th>
                      <th>Email Sent</th>
                      <th>Docs Count</th>
                      <th>Status</th>
                      <th>Action</th>
                    </thead>
                    <tbody id="batch_list_tbody">

                    </tbody>
                  </table>
                </div>
            </div>
            <div class="modal-footer" style="clear:both">  
               <input type="button" name="close_document_batch_list" class="close_document_batch_list common_black_button" id="close_document_batch_list" value="Close">
            </div>
        </div>
  </div>
</div>

<div class="modal fade send_batch_to_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Batch to Client</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4" style="margin-top: 10px;">
            <div class="row">
              <div class="col-md-3">
                <label style="margin-top:9px">From:</label>
              </div>
              <div class="col-md-9">
                <select name="batch_from_user" id="batch_from_user" class="form-control input-md batch_from_user" value="" required>
                    <option value="">Select User</option>
                    <?php
                      $users = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
                      if(($users))
                      {
                        foreach($users as $user)
                        {
                            ?>
                              <option value="<?php echo trim($user->email); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                            <?php
                        }
                      }
                    ?>
                </select>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label style="margin-top:9px">To:</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="batch_to_user" id="batch_to_user" class="form-control input-sm batch_to_user" value="" required>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label style="margin-top:9px">CC:</label>
              </div>
              <div class="col-md-9">
                <?php 
                  $admin_cc = $keydocs_setttings->keydocs_cc_email;
                ?>
                <input type="text" name="batch_cc_unsent" id="batch_cc_unsent" class="form-control input-sm batch_cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label style="margin-top:9px">Subject:</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="batch_subject_unsent" class="form-control input-sm batch_subject_unsent" value="" required>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="row" style="margin-top:10px">
              <div class="col-md-2">
                <label>Attachment:</label>
              </div>
              <div class="col-md-9" id="batch_email_attachments" style="border: 1px solid silver; padding: 8px; height: 200px;overflow-y: auto;">
              </div>
            </div>
          </div>
        </div>
        <?php
        if($keydocs_setttings->email_header_url == '') {
          $default_image = DB::table("email_header_images")->first();
          if($default_image->url == "") {
            $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
          }
          else{
            $image_url = URL::to($default_image->url.'/'.$default_image->filename);
          }
        }
        else {
          $image_url = URL::to($keydocs_setttings->email_header_url.'/'.$keydocs_setttings->email_header_filename);
        }
        ?>
        <div class="row" style="margin-top:10px">
          <div class="col-md-2">
            <label style="margin-top: 9px;">Header Image:</label>
          </div>
          <div class="col-md-9 padding_00">
            <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
          </div>
        </div>
        <div class="row" style="margin-top:10px">
          <div class="col-md-12">
            <label>Message:</label>
          </div>
          <div class="col-md-12">
            <textarea name="batch_message_editor" id="batch_editor" style="height:500px;">
            </textarea>
          </div>
        </div>          
      </div>
      <div class="modal-footer">
        <input type="hidden" name="batch_email_sent_option" id="batch_email_sent_option" value="0">
        <input type="button" class="btn btn-primary common_black_button send_batch_to_client_btn" value="Send Batch to Client">
      </div>
    </div>
  </div>
</div>

<div class="modal fade document_batch_email_history" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document" style="width:60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Sent History</h4>
      </div>
      <div class="modal-body" id="email_history_tbody">
          
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" data-dismiss="modal" aria-label="Close" value="Close">
      </div>
    </div>
  </div>
</div>

<div class="modal fade show_messageus_last_screen_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index:9999999">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title menu-logo">Email Summary</h4>
          </div>
          <div class="modal-body" id="show_messageus_body" style="max-height: 600px;height:600px; overflow-y: scroll;">
              
          </div>
          <div class="modal-footer">
              <button type="button" class="common_black_button" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
      </div>
  </div>
</div>
<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $companydetails_val = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
  $companyname_val = $companydetails_val->company.'-'.$client_id;
  $hiddenval = $_GET['client_id'];
  $year = DB::table('year_end_year')->where('status', 0)->orderBy('year','desc')->first();
  $year_clients = DB::table('year_client')->where('year', $year->year)->where('client_id',$client_id)->first(); 
  if(($year_clients)){
    $year_client_id=base64_encode($year_clients->id);
  }
  else{
    $year_client_id='yearclientid';
  }
  $encode_client_id=base64_encode($client_id);
  $client_specific_name=$companydetails_val->company.' ('.$client_id.')';
}
else{
  $companyname_val = '';
  $hiddenval = '';
  $client_id = 'clientiden';
  $year_client_id='yearclientid';
  $encode_client_id='encodeclientid';
  $client_specific_name='';
}
$prevdate = date("Y-m-05", strtotime("first day of -1 months"));
$prev_date2 = date('Y-m', strtotime($prevdate));
?>
<div class="content_section" style="margin-bottom:200px">
  	<div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title" style="display:flex;">
            Key Docs&nbsp;<span id="client_specific_name"></span>
            <input type="hidden" id="hf_clientfrom" value="<?php echo $client_id; ?>" />  
            <input type="hidden" id="hf_clientspecificname" value="<?php echo $client_specific_name; ?>" /> 
            <input type="hidden" id="hf_clientfrom_name" value="<?php echo $companyname_val; ?>" /> 
          <span style="margin-left:40px; margin-top:-8px;">   
            <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/ta_allocation?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-clock-o" title="TA System" aria-hidden="true"></i>
            </a></div>  
            <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/rct_client_manager/'.$client_id.'?active_month='.$prev_date2)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-university" title="RCT Manager" aria-hidden="true"></i>
            </a></div>    
            <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/client_management?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-users" title="Client Management System" aria-hidden="true"></i>
            </a></div>     
            <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/client_request_manager/'.$encode_client_id)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-envelope" title="Clinet Request System" aria-hidden="true"></i>
            </a></div>    
            <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/infile_search?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-file-archive-o" title="Infile" aria-hidden="true"></i>
            </a></div>      
            <div style="display:inline-flex;"><a class="quick_links yearend_link" href="<?php echo URL::to('user/yearend_individualclient/'.$year_client_id)?>" style="padding:10px; text-decoration:none;display:none;">
            <i class="fa fa-calendar" title="Yearend Manager" aria-hidden="true"></i>
            </a></div>  
          </span>
        </h4>     
          <a href="javascript:" id="settings_keydocs" class="fa fa-cog common_black_button" title="Key Docs Settings" style="float:right; margin-top: -77px;"></a>
          
        <div class="col-lg-6" style="padding-right: 0px;height: 95px;">
        	<div class="col-lg-3 padding_00">
    				<h5 style="font-weight: 600">Enter Client Name: </h5>
    			</div>
    			<div class="col-lg-7" style="padding: 0px;">
    				<div class="form-group">
    				    
    				    <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500; width:93%; display:inline;" value="<?php echo $companyname_val; ?>" required />                      
    				    <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />                      
                <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $hiddenval; ?>" name="client_id">
    				</div>                  
    			</div>
    			<div class="col-md-1" style="padding: 0px">
    				<input type="button" name="load_client_review" class="common_black_button load_client_review" value="Load">
    			</div>
        </div>
        <div class="col-lg-6" style="padding:0px 20px">
          <div class="col-md-12 client_details_div" style="padding:0px">
            
          </div>
        	<!-- <h3>CRO Information: </h3>
            <table class="table">
                <tbody>
                  <tr>
                    <td style="width:15%;margin-top:6px"><label style="margin-top:6px">Type:</label></td>
                    <td><input type="text" name="cro_type" class="form-control cro_info cro_type" value="" readonly></td>
                    <td colspan="2"><input type="button" class="common_black_button refresh_cro" value="Refresh"></td>
                  </tr>
                  <tr>
                    <td style=""><label style="margin-top:6px">ARD:</label><input type="button" class="common_black_button update_ard" value="Update" style="display:none"></td>
                    <td><input type="text" name="cm_ard_date" class="form-control cro_info cm_ard_date" value="" readonly></td>
                    <td style=""><label style="margin-top:6px">CRO ARD:</label></td>
                    <td><input type="text" name="cro_ard_date" class="form-control cro_info cro_ard_date" value="" readonly></td>
                  </tr>
                  <tr>
                    <td style=""><label style="margin-top:6px">Company Name:</label></td>
                    <td><input type="text" name="company_name" class="form-control cro_info company_name" value="" readonly></td>
                    <td colspan="2"><input type="text" name="cro_number" class="form-control cro_info cro_number" value="" readonly style="display:none"></td>
                  </tr>
                  
                </tbody>
              </table> -->
        </div>
        <div class="col-md-12 padding_00">
          <div class="col-md-7 padding_00">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active" style="width:15%">
                <a class="nav-link active" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="true" aria-expanded="true">Invoice List</a>
              </li>
              <li class="nav-item" style="width:17%">
                <a class="nav-link" id="year-tab" data-toggle="tab" href="#year" role="tab" aria-controls="year" aria-selected="false" aria-expanded="false">Year End Docs</a>
              </li>
              <li class="nav-item" style="width:13%">
                <a class="nav-link" id="letters-tab" data-toggle="tab" href="#letters" role="tab" aria-controls="letters" aria-selected="false" aria-expanded="false">Letters</a>
              </li>
              <li class="nav-item" style="width:18%">
                <a class="nav-link" id="tax-tab" data-toggle="tab" href="#tax" role="tab" aria-controls="tax" aria-selected="false" aria-expanded="false">Tax Clearance</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="otherdocs-tab" data-toggle="tab" href="#otherdocs" role="tab" aria-controls="otherdocs" aria-selected="false" aria-expanded="false">Other Docs</a>
              </li>
              <li class="nav-item" style="width:21%">
                <a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="tax" aria-selected="false" aria-expanded="false">Company Formation</a>
              </li>
              
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                <div class="col-md-10" style="margin-top:20px">
                  <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_1" value="1"><label for="invoice_date_option_1">Year</label>
                  <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_2" value="2"><label for="invoice_date_option_2">All Invoice</label>
                  <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_3" value="3"><label for="invoice_date_option_3">Custom Date</label>
                  <br/>
                  <input type="button" name="download_selected_pdf" class="common_black_button download_selected_pdf" value="Download Selected PDF" style="display:none">
                  <input type="button" name="email_selected_pdf" class="common_black_button email_selected_pdf" value="Email Selected PDF" style="display:none">
                  <input type="button" name="selected_export_csv" class="common_black_button selected_export_csv" value="Export CSV" style="display:none">
                  <input type="button" name="selected_document_batch" class="common_black_button selected_document_batch" value="Add Selected to Document Batch" data-source="invoice" style="display:none">
                  

                  <div class="col-md-12 invoice_year_div padding_00" style="margin-top: 7px;display:none">
                    <h5 class="col-md-1 padding_00" style="font-weight: 600; width: 80px;">Select Year:</h5>
                    <div class="col-md-3">
                      <select name="invoice_select_year" class="invoice_select_year form-control">
                        <option value="">Select Year</option>
                      </select>
                    </div>
                    <div class="col-md-2 padding_00">
                      <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
                    </div>
                  </div>
                  <div class="col-md-12 custom_date_div" style="margin-top: 7px;display:none">
                    <h5 class="col-md-1">From:</h5>
                    <div class="col-md-3">
                      <input type="text" name="from_invoice" class="form-control from_invoice" value="">
                    </div>
                    <h5 class="col-md-1">To:</h5>
                    <div class="col-md-3">
                      <input type="text" name="to_invoice" class="form-control to_invoice" value="">
                    </div>
                    <div class="col-md-2">
                      <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
                    </div>
                  </div>
                  <div class="col-md-12 invoice_table_div padding_00" style="display:none; margin-top: 7px; max-height: 550px; overflow-y: scroll;height: 550px;">
                  </div> 
                </div>
              </div>
              <div class="tab-pane fade" id="year" role="tabpanel" aria-labelledby="year-tab">
                <div class="col-md-10" style="margin-top:20px">
                  <div style="margin-top: 10px;clear: both;float: left;">
                    <a href="javascript:" class="common_black_button load_year_end_docs" id="load_year_end_docs">Load Year End Docs</a>
                    <a href="javascript:" class="common_black_button download_selected_documents" id="download_selected_documents">Download Documents</a>
                    <a href="javascript:" class="common_black_button dummy_button" style="display:none">Create Loan Pack</a>
                    <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="yearend">
                  </div>
                  
                  <div class="col-md-12 receipt_table_div padding_00" style="margin-top: 20px;">
                    
                  </div> 
                </div>
              </div>
              <div class="tab-pane fade" id="letters" role="tabpanel" aria-labelledby="letters-tab">
                <div class="col-md-10" style="margin-top:20px">
                  <div style="margin-top: 10px;clear: both;float: left;">
                    <a href="javascript:" class="common_black_button add_letter_files" id="add_letter_files">Add Files</a>
                    <a href="javascript:" class="common_black_button download_selected_letters" id="download_selected_letters">Download Letters</a>
                    <a href="javascript:" class="common_black_button dummy_button" style="display:none">Create Standard Letters</a>
                    <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="letters">
                  </div>
                  <div class="col-md-12 transaction_table_div padding_00" style="display:none">
                    <table class="table" style="margin-top: 20px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Notes</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="letters_tbody">
                          
                      </tbody>
                    </table>
                  </div> 
                </div>
              </div>
              <div class="tab-pane fade" id="otherdocs" role="tabpanel" aria-labelledby="otherdocs-tab">
                <div class="col-md-10" style="margin-top:20px">
                  <div style="margin-top: 10px;clear: both;float: left;">
                    <a href="javascript:" class="common_black_button add_otherdocs_files" id="add_otherdocs_files">Add Files</a>
                    <a href="javascript:" class="common_black_button download_selected_otherdocs" id="download_selected_otherdocs">Download Other Documents</a>
                    <!-- <a href="javascript:" class="common_black_button dummy_button" style="display:none">Create Standard Letters</a> -->
                    <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="otherdocs">
                  </div>
                  <div class="col-md-12 transaction_table_div padding_00" style="display:none">
                    <table class="table" style="margin-top: 20px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Notes</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="otherdocs_tbody">
                          
                      </tbody>
                    </table>
                  </div> 
                </div>
              </div>
              <div class="tab-pane fade" id="tax" role="tabpanel" aria-labelledby="tax-tab">
                <div class="col-md-10" style="margin-top:20px">

                  <div style="margin-top: 10px;clear: both;float: left;">
                    <a href="javascript:" class="common_black_button add_tax_files" id="add_tax_files">Add Files</a>
                    <a href="javascript:" class="common_black_button download_selected_tax" id="download_selected_tax">Download Selected Files</a>
                    <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="tax_clearance">
                    <!-- <a href="javascript:" class="common_black_button add_current_tax_files" id="add_current_tax_files" style="position: absolute;right: 0px;top: 4%;">Current Tax Clearance file</a> -->
                  </div>
                  <div class="col-md-12 transaction_table_div padding_00" style="display:none">
                    <h4 style="margin-top:20px;font-weight:600;text-decoration: underline;">Current Tax Clearance File:</h4>
                    <table class="table" style="margin-top: 20px;">
                      <thead>
                        <th>Files</th>
                        <th>Date Stored</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="current_tax_tbody">
                          
                      </tbody>
                    </table>
                    <h4 style="margin-top:20px;font-weight:600;text-decoration: underline;">Files Stored:</h4>
                    <table class="table" style="margin-top: 20px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Date Stored</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="tax_tbody">
                          
                      </tbody>
                    </table>

                    <a href="javascript:" class="common_black_button dummy_button" style="display:none">Email Tax Clearance</a>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab" style="margin-top: 1%;">
                <div class="tab">
                  <button class="tablinks" onclick="openCity(event, 'Certificate')" id="defaultOpen">Certificate of Incorporation</button>
                  <button class="tablinks" onclick="openCity(event, 'Constitution')">Constitution</button>
                  <button class="tablinks" onclick="openCity(event, 'Memorandum')">Memorandum</button>
                  <button class="tablinks" onclick="openCity(event, 'Articles')">Articles</button>
                  <button class="tablinks" onclick="openCity(event, 'Application')">A1 Application to Incorporate</button>
                  <button class="tablinks" onclick="openCity(event, 'Other')">Other</button>
                </div>
                <div id="Certificate" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_cert_files" id="add_cert_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_cert" id="download_selected_cert">Download Selected</a>
                      <a href="javascript:" class="common_black_button email_selected_cert" id="email_selected_cert">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="certificate_of_incorporation">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="cert_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="Constitution" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_cons_files" id="add_cons_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_cons" id="download_selected_cons">Download Selected Files</a>
                      <a href="javascript:" class="common_black_button email_selected_cons" id="email_selected_cons">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="constitution">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="cons_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="Memorandum" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_memo_files" id="add_memo_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_memo" id="download_selected_memo">Download Selected Files</a>
                      <a href="javascript:" class="common_black_button email_selected_memo" id="email_selected_memo">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="memorandum">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="memo_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="Articles" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_art_files" id="add_art_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_art" id="download_selected_art">Download Selected Files</a>
                      <a href="javascript:" class="common_black_button email_selected_art" id="email_selected_art">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="articles">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="art_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="Application" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_app_files" id="add_app_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_app" id="download_selected_app">Download Selected Files</a>
                      <a href="javascript:" class="common_black_button email_selected_app" id="email_selected_app">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="application_to_incorporate">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="app_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="Other" class="tabcontent">
                  <div class="col-md-12">
                    <div style="margin-top: 10px;clear: both;float: left;">
                      <a href="javascript:" class="common_black_button add_other_files" id="add_other_files">Add Files</a>
                      <a href="javascript:" class="common_black_button download_selected_other" id="download_selected_other">Download Selected Files</a>
                      <a href="javascript:" class="common_black_button email_selected_other" id="email_selected_other">Email Selected</a>
                      <input type="button" name="selected_document_batch_keydocs" class="common_black_button selected_document_batch_keydocs" value="Add Selected to Document Batch" data-source="other">
                    </div>

                    <table class="table" style="margin-top: 65px;">
                      <thead>
                        <th>S.no</th>
                        <th>Files</th>
                        <th>Action</th>
                      </thead>
                      <tbody id="other_tbody">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5 padding_00" style="margin-top:20px">
            <label for="select_document_batch" style="float: left;margin-top: 7px;margin-left: 20px;margin-right: 10px;">Select Document Batch: </label>
            <select name="select_document_batch" id="select_document_batch" class="form-control select_document_batch" style="width: 30%;float: left;">
                <option value="">Select</option>
            </select>
            <input type="button" name="load_selected_document_batch" class="load_selected_document_batch common_black_button" value="Load" style="float:left;display:none">
            <input type="button" name="manage_document_batches" id="manage_document_batches" class="common_black_button manage_document_batches" title="Manage Document Batches" style="float:right;" value="Manage Document Batches" disabled="disabled">

            <div class="col-md-12" id="document_list_tbody" style="margin-top: 20px">

            </div>
          </div>
        </div>
	</div>
    <!-- End  -->
	<div class="main-backdrop"><!-- --></div>
	<div id="print_image">
	    
	</div>
	<div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Invoices are get to be download to the selected folder.</p>
  </div>
	<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
	<input type="hidden" name="show_alert" id="show_alert" value="">
	<input type="hidden" name="pagination" id="pagination" value="1">
  <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
  <input type="hidden" name="invoice_sortoptions" id="invoice_sortoptions" value="asc">
  <input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
  <input type="hidden" name="net_sortoptions" id="net_sortoptions" value="asc">
  <input type="hidden" name="vat_sortoptions" id="vat_sortoptions" value="asc">
  <input type="hidden" name="gross_sortoptions" id="gross_sortoptions" value="asc">
  <input type="hidden" name="receipt_sno_sortoptions" id="receipt_sno_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">
  <input type="hidden" name="receipt_date_sortoptions" id="receipt_date_sortoptions" value="asc">
  <input type="hidden" name="amount_sortoptions" id="amount_sortoptions" value="asc">
  <input type="hidden" name="keydocs_signature" id="keydocs_signature" value="<?php echo $keydocs_setttings->keydocs_signature; ?>">
</div>
<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
$(function () {     
  $('a[data-toggle="collapse"]').on('click',function(){
    var objectID=$(this).attr('href');
    if($(objectID).hasClass('in'))
    {
      $(objectID).collapse('hide');
    }
    else{
      $(objectID).collapse('show');
    }
  });
});
function next_company_formation_attachment_cert(countval,email){
  var url = $(".key_docs_cert:checked").eq(countval).parents("tr").find(".download_cert").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_cert:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_cert(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("1");
    }
  }
}
function next_company_formation_attachment_cons(countval,email){
  var url = $(".key_docs_cons:checked").eq(countval).parents("tr").find(".download_cons").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_cons:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_cons(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("2");
    }
  }
}
function next_company_formation_attachment_memo(countval,email){
  var url = $(".key_docs_memo:checked").eq(countval).parents("tr").find(".download_memo").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_memo:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_memo(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("3");
    }
  }
}
function next_company_formation_attachment_art(countval,email){
  var url = $(".key_docs_art:checked").eq(countval).parents("tr").find(".download_art").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_art:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_art(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("4");
    }
  }
}
function next_company_formation_attachment_app(countval,email){
  var url = $(".key_docs_app:checked").eq(countval).parents("tr").find(".download_app").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_app:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_app(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("5");
    }
  }
}
function next_company_formation_attachment_other(countval,email){
  var url = $(".key_docs_other:checked").eq(countval).parents("tr").find(".download_other").attr("href");
  var fnamesplit = url.split("/");
  var fname = fnamesplit[fnamesplit.length-1];
  SaveToDisk(url,fname);
  var nextcountval = parseInt(countval) + 1;

  if($(".key_docs_other:checked").length > nextcountval){
    setTimeout(function(){
      next_company_formation_attachment_other(nextcountval,email);
    },1000);
  }
  else{
    if(email == ""){
      $("body").removeClass("loading");
      $.colorbox.close();
    }
    else{
      email_multiple_attachments("6");
    }
  }
}

function email_multiple_attachments(type){

  var ids = '';
  if(type == "1"){
    $(".key_docs_cert:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  } else if(type == "2"){
    $(".key_docs_cons:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  } else if(type == "3"){
    $(".key_docs_memo:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  } else if(type == "4"){
    $(".key_docs_art:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  } else if(type == "5"){
    $(".key_docs_app:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  } else if(type == "6"){
    $(".key_docs_other:checked").each(function() {
      var id = $(this).attr("data-element");
      if(ids == "")
      {
        ids = id;
      }
      else{
        ids = ids+','+id;
      }
    });
  }
  

  $.ajax({
    url:"<?php echo URL::to('user/email_key_docs_company_formation_multiple'); ?>",
    type:"post",
    data:{ids:ids,type:type},
    dataType:"json",
    success:function(result){
      //SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
      $(".subject_selected").val(result['subject']);
      $(".client_email_selected").val(result['to']);
      if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      CKEDITOR.replace('editor_2',
      {
        height: '300px',
      });
      CKEDITOR.instances['editor_2'].setData(result['email_output']);

      $("#client_attachments_selected").html(result['filename']);
      $(".email_selected_modal").modal("show");
      $.colorbox.close();
    }
  })
}
$(window).change(function(e) {
  if($(e.target).hasClass('select_document_batch')){
    $("body").addClass("loading");
    var client_id = $("#client_search_hidden_infile").val();
    var batch_id = $(e.target).val();
    
    $.ajax({
      url:"<?php echo URL::to('user/get_document_list_for_batch'); ?>",
      type:"post",
      data:{client_id:client_id,batch_id:batch_id},
      success:function(result){
        $("#document_list_tbody").html(result);
        $("#document_list_expand").dataTable().fnDestroy();
        $('#document_list_expand').DataTable({
            autoWidth: false,
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
});
function printPdf(url) {
  var iframe = this._printIframe;
  if (!this._printIframe) {
    iframe = this._printIframe = document.createElement('iframe');
    document.body.appendChild(iframe);
    iframe.style.display = 'none';
    iframe.onload = function() {
      setTimeout(function() {
        iframe.focus();
        iframe.contentWindow.print();
      }, 1);
    };
  }
  iframe.src = url;
}
$(window).click(function(e) { 
  if($(e.target).hasClass('dummy_button')) {
    alert("This Feature is Coming Soon!");
  }
  if($(e.target).hasClass('keydocs_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Keydocs Email Header Image?");
    if(r) {
      $(".keydocs_change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmailKeydocs").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('view_document_batch_email'))
  {
    $("body").addClass("loading");
    var email_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_messageus_sample_screen_keydocs'); ?>",
      type:"post",
      data:{email_id:email_id},
      success:function(result)
      {
        $(".show_messageus_last_screen_modal").modal("show");
        $("#show_messageus_body").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('email_document_batch_history')) {
    $("body").addClass("loading");
    var batch_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_document_batch_email_history'); ?>",
      type:"post",
      data:{batch_id:batch_id},
      success:function(result){
        $(".document_batch_email_history").modal("show");
        $("#email_history_tbody").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('send_document_to_client')){
    var client_id = $("#client_search_hidden_infile").val();
    var batch_id = $(".select_document_batch").val();

    if (CKEDITOR.instances.batch_editor) CKEDITOR.instances.batch_editor.destroy();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/email_setup_document_batch_keydocs'); ?>",
      type:"post",
      data:{client_id:client_id,batch_id:batch_id},
      dataType:"json",
      success:function(result) {
        $(".send_batch_to_client_modal").modal("show");
        $(".batch_from_user").val(result['from_user']);
        $(".batch_to_user").val(result['to_user']);
        $(".batch_subject_unsent").val(result['subject']);

        $("#batch_email_attachments").html(result['attachment']);

        CKEDITOR.replace('batch_editor',
        {
          height: '250px',
          enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
        });

        CKEDITOR.instances['batch_editor'].setData("");

        $("body").removeClass("loading");
      }
    })
    

  }
  if($(e.target).hasClass('send_batch_to_client_btn')) {
    var client_id = $("#client_search_hidden_infile").val();
    var batch_id = $(".select_document_batch").val();

    var from_user = $(".batch_from_user").val();
    var to_user = $(".batch_to_user").val();
    var cc = $(".batch_cc_unsent").val();
    var subject = $(".batch_subject_unsent").val();
    var message =  CKEDITOR.instances['batch_editor'].getData();

    var attachment_ids = '';
    $(".send_batch_attachment:checked").each(function() {
      if(attachment_ids == ""){
        attachment_ids = $(this).attr("data-element");
      }
      else{
        attachment_ids = attachment_ids+','+$(this).attr("data-element");
      }
    });

    if(from_user == ""){
      alert("Please Select the From User to send a Document batch email");
      return false;
    }
    else if(to_user == ""){
      alert("Please enter the Client Email Address to send a Document batch email");
      return false;
    }
    else if(subject == ""){
      alert("Please enter the Subject");
      return false;
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/send_document_batches_email_to_client'); ?>",
        type:"post",
        data:{client_id:client_id,batch_id:batch_id,from_user:from_user,to_user:to_user,cc:cc,subject:subject,message:message,attachment_ids:attachment_ids},
        success:function(result){
          $("#last_email_sent_tag").html(result);
          $(".send_batch_to_client_modal").modal("hide");
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Email Sent Successfully</p>",width:"30%",fixed:true});
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('delete_document_attachment')){
    var file_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete this document attached?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_document_batch_attachment'); ?>",
        type:"post",
        data:{file_id:file_id},
        success:function(result){
          $(e.target).parents("tr").detach();
          var lengthval = $("#attachments_list_tbody").find("tr").length;
          if(lengthval == 0){
            $(".send_document_to_client").prop("disabled",true);
          }
        }
      })
    }
  }
  if($(e.target).hasClass('selected_document_batch')){
    var client_id = $("#client_search_hidden_infile").val();
    var batch_id = $(".select_document_batch").val();

    if(batch_id == ""){
      alert("There is no Document Batch Selected, please load a document batch or create a new one in order to add the selected documents to a Document Batch.");
    }
    else{
      var checked = $(".invoice_check:checked").length;
      if(checked > 0)
      {
        $("body").addClass("loading");
        var ids = '';
        $(".invoice_check:checked").each(function() {
            if(ids == "")
            {
              ids = $(this).attr("data-element");
            }
            else{
              ids = ids+','+$(this).attr("data-element");
            }
        });
        $.ajax({
          url:"<?php echo URL::to('user/invoice_save_selected_pdfs'); ?>",
          type:"post",
          dataType:"json",
          data:{ids:ids, client_id:client_id, batch_id:batch_id},
          success:function(result)
          {
            if(result['file_count'] == 0){
              $("#attachments_list_tbody").html(result['outputpdf']);
            }
            else{
              $("#attachments_list_tbody").append(result['outputpdf']);
            }
            $(".send_document_to_client").prop("disabled",false);
            $("body").removeClass("loading");
          }
        })
      }
      else{
        alert("Please select atleast one invoice to save on Selected Batch");
      }
    }
  }
  if($(e.target).hasClass('selected_document_batch_keydocs')){
    var client_id = $("#client_search_hidden_infile").val();
    var batch_id = $(".select_document_batch").val();
    var source = $(e.target).attr("data-source");
    console.log(source);
    if(batch_id == ""){
      alert("There is no Document Batch Selected, please load a document batch or create a new one in order to add the selected documents to a Document Batch.");
    }
    else{
      if(source == "yearend"){
        var attachment = $(".year_end_documents:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".year_end_documents:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "letters"){
        var attachment = $(".key_docs_letter:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_letter:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "otherdocs"){
        console.log("etehsnsn");
        var attachment = $(".key_docs_otherdocs:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_otherdocs:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "tax_clearance"){
        var attachment = $(".key_docs_tax:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_tax:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "certificate_of_incorporation"){
        var attachment = $(".key_docs_cert:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_cert:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "constitution"){
        var attachment = $(".key_docs_cons:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_cons:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "memorandum"){
        var attachment = $(".key_docs_memo:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_memo:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "articles"){
        var attachment = $(".key_docs_art:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_art:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "application_to_incorporate"){
        var attachment = $(".key_docs_app:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_app:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
      else if(source == "other"){
        var attachment = $(".key_docs_other:checked").length;
        if(attachment > 0){
          $("body").addClass("loading");
          var ids = '';
          $(".key_docs_other:checked").each(function() {
            var id = $(this).attr("data-element");
            if(ids == "")
            {
              ids = id;
            }
            else{
              ids = ids+','+id;
            }
          });

          $.ajax({
            url:"<?php echo URL::to('user/save_year_end_documents'); ?>",
            type:"post",
            data:{ids:ids,client_id:client_id,batch_id:batch_id,source:source},
            dataType:"json",
            success:function(result){
              if(result['file_count'] == 0){
                $("#attachments_list_tbody").html(result['outputpdf']);
              }
              else{
                $("#attachments_list_tbody").append(result['outputpdf']);
              }
              $(".send_document_to_client").prop("disabled",false);
              $("body").removeClass("loading");
            }
          })
        }
        else{
          alert("Please select atlease one file to save on Selected Batch");
        }
      }
    }
  }
  // if($(e.target).hasClass('load_selected_document_batch')){
  //   var client_id = $("#client_search_hidden_infile").val();
  //   var batch_id = $(".select_document_batch").val();
    
  //   $.ajax({
  //     url:"<?php echo URL::to('user/get_document_list_for_batch'); ?>",
  //     type:"post",
  //     data:{client_id:client_id,batch_id:batch_id},
  //     success:function(result){
  //       $("#document_list_tbody").html(result);
  //       $("#document_list_expand").dataTable().fnDestroy();
  //       $('#document_list_expand').DataTable({
  //           autoWidth: false,
  //           scrollX: false,
  //           fixedColumns: false,
  //           searching: false,
  //           paging: false,
  //           info: false
  //       });
  //     }
  //   })
  // }
  if($(e.target).hasClass('edit_document_batch')) {
    var batch_id = $(e.target).attr("data-element");

    $(e.target).parents("tbody").find("td").show();
    $(e.target).parents("tbody").find(".edit_doc_td").hide();

    $(e.target).parents("tr").find("td").hide();
    $(e.target).parents("tr").find(".edit_doc_td").show();
  }
  if($(e.target).hasClass('submit_edit_doc_batch')) {
    var batch_id = $(e.target).attr("data-element");
    var batch_name = $(e.target).parents("tr").find(".edit_doc_batch_name").val();
    var client_id = $("#client_search_hidden_infile").val();

    $.ajax({
      url:"<?php echo URL::to('user/update_document_batches_key_docs'); ?>",
      type:"post",
      data:{batch_id:batch_id,batch_name:batch_name,client_id:client_id},
      success:function(result){
        if(result == "1"){
          alert("Document Batch Name Already Exists.");
        }
        else {
          $(e.target).parents("tr").find("td").eq(0).html(batch_name);
          $(e.target).parents("tr").find("td").show();
          $(e.target).parents("tr").find(".edit_doc_td").hide();
        }
      }
    });
  }
  if($(e.target).hasClass('add_document_batch_client')) {
    var batch_name = $(".name_of_batch").val();
    var client_id = $("#client_search_hidden_infile").val();
    if(batch_name == "") {
      alert("Please enter the Batch Name");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/add_document_batches_key_docs'); ?>",
        type:"post",
        data:{batch_name:batch_name,client_id:client_id},
        dataType:"json",
        success:function(result){
          if(result['error'] == "1"){
            alert("Document Batch Name Already Exists.");
          }
          else{
            $(".name_of_batch").val("");
            if(result['batch_count'] > 1){
              $("#batch_list_tbody").append(result['inserted_batch']);
            }else{
              $("#batch_list_tbody").html(result['inserted_batch']);
            }
          }
        }
      });
    }
  }
  if($(e.target).hasClass('status_document_batch')){
    var batch_id = $(e.target).attr("data-element");
    var status = $(e.target).attr("data-value");

    $.ajax({
      url:"<?php echo URL::to('user/change_document_batch_status'); ?>",
      type:"post",
      data:{batch_id:batch_id,status:status},
      success:function(result){
        if(status == "1"){
          $(e.target).parents("tr").find("td").eq(3).html("Locked");
          $(e.target).removeClass("fa-unlock");
          $(e.target).addClass("fa-lock");
          $(e.target).attr("title","Unlock this Document Batch");
          $(e.target).attr("data-value","0");
        }else{
          $(e.target).parents("tr").find("td").eq(3).html("Unlocked");
          $(e.target).removeClass("fa-lock");
          $(e.target).addClass("fa-unlock");
          $(e.target).attr("title","Lock this Document Batch");
          $(e.target).attr("data-value","1");
        }
      }
    })
  }
  if($(e.target).hasClass('delete_document_batch')){
    var r = confirm("Are you sure you want to Delete this Document Batch?");
    if(r){
      var batch_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/delete_document_batch_keydocs'); ?>",
        type:"post",
        data:{batch_id:batch_id},
        success:function(result){
          $(e.target).parents("tr").detach();
        }
      })
    }
  }
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
            alert("Client Already Exist in your Active Client list");
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
  if($(e.target).hasClass('send_request_to_client'))
  {
    if($("#send_request_form").valid())
    {
      $("body").addClass("loading");
      var from_user_to_client = $("#from_user_to_client").val();
      var hidden_client_id = $("#hidden_client_id").val();
      var hidden_attachment = $(".hidden_attachment").val();

      var client_search = $(".client_email").val();
      var cc_approval_to_client = $("#cc_approval_to_client").val();
      var subject_to_client = $("#subject_to_client").val();

      var message_editor_to_client = CKEDITOR.instances['editor_1'].getData();


      $.ajax({
        url:"<?php echo URL::to('user/keydocs_email_selected_pdf'); ?>",
        type:"post",
        data:{from_user_to_client:from_user_to_client,hidden_client_id:hidden_client_id,hidden_attachment:hidden_attachment,client_search:client_search,cc_approval_to_client:cc_approval_to_client,subject_to_client:subject_to_client,message_editor_to_client:message_editor_to_client},
        success:function(result)
        {
          $(".sent_to_client").modal("hide");
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Email Sent Successfully</p>",width:"30%",fixed:true});
          $("body").removeClass("loading");
        }
      })

    }
  }
  if(e.target.id == "settings_keydocs")
  {
    if (CKEDITOR.instances.editor1) CKEDITOR.instances.editor1.destroy();

    CKEDITOR.replace('editor1',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    $(".keydocs_settings_modal").modal("show");
  }
  if($(e.target).hasClass('load_year_end_docs')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      $.ajax({
        url:"<?php echo URL::to('user/load_year_end_docs'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result){
          $(".receipt_table_div").html(result);
          $(".receipt_table_div").show();
        }
      });
    }else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_documents')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".year_end_documents:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".year_end_documents:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_year_end_documents'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_letters')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_letter:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_letter:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_letters'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_otherdocs')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_otherdocs:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_otherdocs:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_otherdocs'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_tax')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_tax:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_tax:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_tax'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_cert">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_cert">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_cons">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_cons">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_memo">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_memo">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_art">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_art">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_app">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_app">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Download as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button download_multiple_other">Download as Files.</a><a href="javascript:" class="common_black_button download_zip_other">Download as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }

  if($(e.target).hasClass('email_selected_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_cert">Email as Files</a><a href="javascript:" class="common_black_button email_zip_cert">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_selected_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_cons">Email as Files.</a><a href="javascript:" class="common_black_button email_zip_cons">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_selected_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_memo">Email as Files.</a><a href="javascript:" class="common_black_button email_zip_memo">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_selected_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_art">Email as Files.</a><a href="javascript:" class="common_black_button email_zip_art">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_selected_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_app">Email as Files.</a><a href="javascript:" class="common_black_button email_zip_app">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_selected_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Do you want to Email as a Zip file or as Individual Files?</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button email_multiple_other">Email as Files.</a><a href="javascript:" class="common_black_button email_zip_other">Email as Zip</a></p>'});
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }


  if($(e.target).hasClass('download_zip_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_cert:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"1"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_zip_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_cons:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"2"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_zip_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_memo:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"3"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_zip_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_art:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"4"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_zip_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_app:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"5"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_zip_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_other:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"6"},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $.colorbox.close();
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }

  if($(e.target).hasClass('email_zip_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_cert:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"1"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);


            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');


            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_zip_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_cons:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"2"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);

            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');

            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_zip_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_memo:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"3"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);

            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');

            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_zip_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_art:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"4"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);

            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');

            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_zip_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_app:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"5"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);

            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');
            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_zip_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_other:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/email_key_docs_company_formation_zip'); ?>",
          type:"post",
          data:{ids:ids,type:"6"},
          dataType:"json",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['filename'],result['filename']);
            $(".subject_selected").val(result['subject']);
            $(".client_email_selected").val(result['to']);
            if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
            CKEDITOR.replace('editor_2',
            {
              height: '300px',
            });
            CKEDITOR.instances['editor_2'].setData(result['email_output']);

            $("#client_attachments_selected").html('<div class="col-md-12"><p>Attachments:</p><input type="hidden" name="attach_selected_url" class="attach_selected_url" value="public"><input type="hidden" name="attach_selected_filename" class="attach_selected_filename" value="'+result['filename']+'"><img class="email_selected_zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px"><spam class="email_selected_zip_name" style="margin-left: 7px;">'+result['filename']+'</spam></div>');
            $(".email_selected_modal").modal("show");
            $.colorbox.close();
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }

  if($(e.target).hasClass('download_multiple_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_cert:checked").eq(0).parents("tr").find(".download_cert").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_cert:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_cert(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_multiple_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_cons:checked").eq(0).parents("tr").find(".download_cons").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_cons:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_cons(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_multiple_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_memo:checked").eq(0).parents("tr").find(".download_memo").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_memo:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_memo(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_multiple_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_art:checked").eq(0).parents("tr").find(".download_art").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_art:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_art(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_multiple_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_app:checked").eq(0).parents("tr").find(".download_app").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_app:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_app(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_multiple_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_other:checked").eq(0).parents("tr").find(".download_other").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_other:checked").length > 1){
          setTimeout(function(){
            var email = '';
            next_company_formation_attachment_other(1,email);
          },1000);
        }
        else{
          $.colorbox.close();
          $("body").removeClass("loading");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }

  if($(e.target).hasClass('email_multiple_cert')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cert:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_cert:checked").eq(0).parents("tr").find(".download_cert").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_cert:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_cert(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("1");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_multiple_cons')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_cons:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_cons:checked").eq(0).parents("tr").find(".download_cons").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_cons:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_cons(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("2");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_multiple_memo')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_memo:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_memo:checked").eq(0).parents("tr").find(".download_memo").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_memo:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_memo(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("3");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_multiple_art')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_art:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_art:checked").eq(0).parents("tr").find(".download_art").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_art:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_art(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("4");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_multiple_app')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_app:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_app:checked").eq(0).parents("tr").find(".download_app").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_app:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_app(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("5");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('email_multiple_other')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_other:checked").length;
      if(attachment > 0){
        $("body").addClass("loading");
        var url = $(".key_docs_other:checked").eq(0).parents("tr").find(".download_other").attr("href");
        var fnamesplit = url.split("/");
        var fname = fnamesplit[fnamesplit.length-1];
        SaveToDisk(url,fname);
        if($(".key_docs_other:checked").length > 1){
          setTimeout(function(){
            next_company_formation_attachment_other(1,'email');
          },1000);
        }
        else{
          email_multiple_attachments("6");
        }
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('send_email_company_formation')){
    var client_id = $("#client_search_hidden_infile").val();
    var from = $("#from_user_company").val();
    var to = $(".client_email_selected").val();
    var cc = $("#cc_approval_selected").val();
    var subject = $(".subject_selected").val();
    var content = CKEDITOR.instances['editor_2'].getData();

    var email_type = $("#hidden_email_send_type").val();
    var selected_ids = $("#hidden_attached_selected_ids").val();
    var zip_url = $(".attach_selected_url").val();
    var zip_filename = $(".attach_selected_filename").val();

    if(from == ""){ alert("Please select the user to send an email"); }
    else if(to == "") { alert("Please enter the client primary email address"); }
    else if(subject == "") { alert("Please enter the subject"); }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/company_formation_email'); ?>",
        type:"post",
        data:{from:from, to:to, cc:cc, subject:subject, content:content, email_type:email_type, selected_ids:selected_ids, zip_url:zip_url, zip_filename:zip_filename,client_id:client_id},
        success:function(result){
          if(result == "1"){
            $(".email_selected_modal").modal("hide");
            $.colorbox.close();
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Email Sent Successfully.</p>'});
          }
          else{
            $.colorbox.close();
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Email Not sent. Something went wrong please try again.</p>'});
          }

          $("body").removeClass("loading");
        }
      })
    }
  }
  if($(e.target).hasClass('add_letter_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("1");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_otherdocs_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("10");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_tax_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("2");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_cert_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("4");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_cons_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("5");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_memo_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("6");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_art_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("7");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_app_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("8");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_other_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("9");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_current_tax_files'))
  {
    var currlength = $(".delete_current_tax").length;
    if(currlength > 0){
      alert("Already you have an current tax clearence file. If you want to change the current file please delete it and add a new current file.")
    }
    else{
      var client_id = $("#client_search_hidden_infile").val();
      if(client_id != ""){
        var task_id = $(e.target).attr("data-element");
        $(".letters_modal").modal("show");
        $("#hidden_type_key_docs").val("3");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
      }
      else{
        alert("Please load the Client Details");
      }
    }
  }
  if($(e.target).hasClass('delete_letter')){
    var letter_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_letter'); ?>",
        type:"post",
        data:{letter_id:letter_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('delete_otherdocs')){
    var doc_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_otherdocs'); ?>",
        type:"post",
        data:{doc_id:doc_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('delete_tax')){
    var tax_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_tax'); ?>",
        type:"post",
        data:{tax_id:tax_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_cert')){
    var cert_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:cert_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_cons')){
    var cons_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:cons_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_memo')){
    var memo_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:memo_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_art')){
    var art_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:art_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_app')){
    var app_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:app_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_other')){
    var other_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_company_formation'); ?>",
        type:"post",
        data:{id:other_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }

  if($(e.target).hasClass('delete_current_tax')){
    var tax_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_current_tax'); ?>",
        type:"post",
        data:{tax_id:tax_id},
        success:function(result)
        {
          $(e.target).parents("tr").html("<td colspan='3'>No Current File Found</td>");
        }
      })
    }
  }
})
function ajaxcomplete() {
  $(".letter_notes").blur(function() {
    var letter_id = $(this).attr("data-element");
    var value = $(this).val();

    if(value != ""){
      $.ajax({
        url:"<?php echo URL::to('user/save_letter_notes'); ?>",
        type:"post",
        data:{letter_id:letter_id,value:value},
        success:function(result){

        }
      })
    }
  });
  $(".otherdocs_notes").blur(function() {
    var doc_id = $(this).attr("data-element");
    var value = $(this).val();

    if(value != ""){
      $.ajax({
        url:"<?php echo URL::to('user/save_otherdocs_notes'); ?>",
        type:"post",
        data:{doc_id:doc_id,value:value},
        success:function(result){

        }
      })
    }
  });
}

$(document).ready(function() {
  <?php
  if(Session::has('message_settings')) { ?>
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Keydocs Settings saved successfully. </p>',fixed:true,width:"800px"});

  <?php } ?>
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
		minLength: 1,
		select: function( event, ui ) {
			$("#client_search_hidden_infile").val(ui.item.id);
      $("#hidden_client_id").val(ui.item.id);
		}
  	});
  	var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  	$(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".from_receipt").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".to_receipt").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    var hf_clientfrom = $("#hf_clientfrom").val();
    var hf_clientfrom_name = $("#hf_clientfrom_name").val();
    var hf_clientspecificname = $("#hf_clientspecificname").val();
    if(hf_clientfrom!='clientiden'){
      $(".quick_links").show();
      $("#crm_expand").dataTable().fnDestroy();
      $(".client_common_search").val(hf_clientfrom_name);
      $("#client_search_hidden_infile").val(hf_clientfrom);
      $("#client_specific_name").text(' for '+hf_clientspecificname);
      $(".load_client_review").click();
    }  
    else{
      $(".quick_links").hide();
    }
});
$(function(){
  var hf_clientfrom = $("#hf_clientfrom").val();
  if(hf_clientfrom=='clientiden'){
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
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumeric = function(value){
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
       return parseInt(value.toLowerCase());
}
$(window).click(function(e) { 
  var ascending = false;
  if($(e.target).hasClass('sort_sno'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }






  if($(e.target).hasClass('sort_sno_receipt'))
  {
    var sort = $("#receipt_sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_sno_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_receipt_val').html()) <
        convertToNumeric($(b).find('.sno_sort_receipt_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_sno_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_receipt_val').html()) <
        convertToNumeric($(b).find('.sno_sort_receipt_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_debit'))
  {
    var sort = $("#debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.debit_sort_val').html()) <
        convertToNumeric($(b).find('.debit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.debit_sort_val').html()) <
        convertToNumeric($(b).find('.debit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_credit'))
  {
    var sort = $("#credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.credit_sort_val').html()) <
        convertToNumeric($(b).find('.credit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.credit_sort_val').html()) <
        convertToNumeric($(b).find('.credit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_receipt_date'))
  {
    var sort = $("#receipt_date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_date_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.receipt_date_sort_val').html()) <
        convertToNumeric($(b).find('.receipt_date_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_date_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.receipt_date_sort_val').html()) <
        convertToNumeric($(b).find('.receipt_date_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_amount'))
  {
    var sort = $("#amount_sortoptions").val();
    if(sort == 'asc')
    {
      $("#amount_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.amount_sort_val').html()) <
        convertToNumeric($(b).find('.amount_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#amount_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.amount_sort_val').html()) <
        convertToNumeric($(b).find('.amount_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_invoice'))
  {
    var sort = $("#invoice_sortoptions").val();
    if(sort == 'asc')
    {
      $("#invoice_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.invoice_sort_val').html()) <
        convertToNumeric($(b).find('.invoice_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#invoice_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.invoice_sort_val').html()) <
        convertToNumeric($(b).find('.invoice_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_date'))
  {
    var sort = $("#date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.date_sort_val').html()) <
        convertToNumeric($(b).find('.date_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.date_sort_val').html()) <
        convertToNumeric($(b).find('.date_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_net'))
  {
    var sort = $("#net_sortoptions").val();
    if(sort == 'asc')
    {
      $("#net_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.net_sort_val').html()) <
        convertToNumeric($(b).find('.net_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#net_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.net_sort_val').html()) <
        convertToNumeric($(b).find('.net_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_vat'))
  {
    var sort = $("#vat_sortoptions").val();
    if(sort == 'asc')
    {
      $("#vat_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.vat_sort_val').html()) <
        convertToNumeric($(b).find('.vat_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#vat_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.vat_sort_val').html()) <
        convertToNumeric($(b).find('.vat_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_gross'))
  {
    var sort = $("#gross_sortoptions").val();
    if(sort == 'asc')
    {
      $("#gross_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.gross_sort_val').html()) <
        convertToNumeric($(b).find('.gross_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#gross_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.gross_sort_val').html()) <
        convertToNumeric($(b).find('.gross_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('load_client_account_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_client_account_review_listing'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".opening_bal_h5").show();
          $(".client_account_table").show();
          $(".export_client_account_details").show();
          $('#opening_bal_client').html(result['opening_balance']);
          $("#client_account_tbody").html(result['output']);
        }
      });
    }
  }
  if($(e.target).hasClass('load_transaction_listing'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_transaction_review_listing'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {

          $(".opening_bal_transaction_h5").show();
          $(".transaction_table").show();
          $(".export_transaction_details").show();
          $('#opening_bal_transaction').html(result['opening_balance']);
          $("#transaction_tbody").html(result['output']);
          $('[data-toggle="popover"]').popover();
        }
      });
    }
  }
  if($(e.target).hasClass('export_client_account_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/export_client_account_review_listing'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result)
        {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        }
      });
    }
  }
  if($(e.target).hasClass('export_transaction_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/export_transaction_review_listing'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result)
        {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        }
      });
    }
  }
  if($(e.target).hasClass('client_tab'))
  {
    $(".opening_bal_h5").hide();
    $(".client_account_table").hide();
    $(".export_client_account_details").hide();
    $('#opening_bal_client').html('');
    $("#client_account_tbody").html('');
  }
  if($(e.target).hasClass('transaction_tab'))
  {
    $(".opening_bal_transaction_h5").hide();
    $(".transaction_table").hide();
    $(".export_transaction_details").hide();
    $('#opening_bal_transaction').html('');
    $("#transaction_tbody").html('');
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
				$("body").addClass("loading");
			    setTimeout(function(){ 
			        var client_id = $("#client_search_hidden_infile").val();
			          $(".copy_clients").attr("data-element", client_id);
			          $(".print_clients").attr("data-element", client_id);
			          $(".download_clients").attr("data-element", client_id);
			          $.ajax({
			              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
			              data:{client_id:client_id,year:year,type:"1"},
			              dataType:"json",
			              type:"post",
			              success:function(result){
			                $(".invoice_table_div").html(result['invoiceoutput']);
			                $(".invoice_table_div").show();
			                 $(".download_selected_pdf").show();
			                $(".email_selected_pdf").show();
                      $(".selected_export_csv").show();
                      $(".selected_document_batch").show();
			                $("body").removeClass("loading");
			                $('#invoice_expand').DataTable({
			                    autoWidth: true,
			                    scrollX: false,
			                    fixedColumns: false,
			                    searching: false,
			                    paging: false,
			                    info: false,
			                    ordering: false
			                });
			               
			          }
			        });
			    }, 2000);
			}
			
		}
		else if(type == "3")
		{
			$("body").addClass("loading");
		    setTimeout(function(){ 
		        var client_id = $("#client_search_hidden_infile").val();
		        var from = $(".from_invoice").val();
		        var to = $(".to_invoice").val();
		          $(".copy_clients").attr("data-element", client_id);
		          $(".print_clients").attr("data-element", client_id);
		          $(".download_clients").attr("data-element", client_id);
		          $.ajax({
		              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
		              data:{client_id:client_id,from:from,to:to,type:"3"},
		              dataType:"json",
		              type:"post",
		              success:function(result){
		                $(".invoice_table_div").html(result['invoiceoutput']);
		                $(".invoice_table_div").show();
		                 $(".download_selected_pdf").show();
			                $(".email_selected_pdf").show();
                      $(".selected_export_csv").show();
                      $(".selected_document_batch").show();
		                $("body").removeClass("loading");
		                $('#invoice_expand').DataTable({
		                    autoWidth: true,
		                    scrollX: false,
		                    fixedColumns: false,
		                    searching: false,
		                    paging: false,
		                    info: false,
		                    ordering: false
		                });
		          }
		        });
		    }, 2000);
			
		}
	}
  if($(e.target).hasClass('load_all_cm_receipt')) {
    var type = $(".receipt_date_option:checked").val();
    if(type == "1")
    {
      var year = $(".receipt_select_year").val();
      if(year == "")
      {
        alert("Please select the year to review the Receipt");
      }
      else{
        $("body").addClass("loading");
          setTimeout(function(){ 
              var client_id = $("#client_search_hidden_infile").val();
                $.ajax({
                    url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                    data:{client_id:client_id,year:year,type:"1"},
                    dataType:"json",
                    type:"post",
                    success:function(result){
                      $(".receipt_table_div").html(result['receiptoutput']);
                      $(".receipt_table_div").show();
                      $("body").removeClass("loading");
                      $('#receipt_expand').DataTable({
                          autoWidth: true,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          ordering: false
                      });
                     
                }
              });
          }, 2000);
      }
      
    }
    else if(type == "3")
    {
      $("body").addClass("loading");
        setTimeout(function(){ 
            var client_id = $("#client_search_hidden_infile").val();
            var from = $(".from_receipt").val();
            var to = $(".to_receipt").val();
              $.ajax({
                  url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                  data:{client_id:client_id,from:from,to:to,type:"3"},
                  dataType:"json",
                  type:"post",
                  success:function(result){
                    $(".receipt_table_div").html(result['receiptoutput']);
                    $(".receipt_table_div").show();
                    $("body").removeClass("loading");
                    $('#receipt_expand').DataTable({
                        autoWidth: true,
                        scrollX: false,
                        fixedColumns: false,
                        searching: false,
                        paging: false,
                        info: false,
                        ordering: false
                    });
              }
            });
        }, 2000);
      
    }
  }
  if($(e.target).hasClass('select_all_invoice'))
  {
    if($(e.target).is(":checked"))
    {
      $(".invoice_check:visible").prop("checked",true);
    }
    else{
      $(".invoice_check").prop("checked",false);
    }
  }
  if($(e.target).hasClass('select_all_receipt'))
  {
    if($(e.target).is(":checked"))
    {
      $(".receipt_check:visible").prop("checked",true);
    }
    else{
      $(".receipt_check").prop("checked",false);
    }
  }
	if($(e.target).hasClass('invoice_inside_class')){
	    var editid = $(e.target).attr("data-element");
	    $.ajax({
	          url: "<?php echo URL::to('user/invoices_print_view') ?>",
	          data:{id:editid},
	          dataType:'json',
	          type:"post",
	          success:function(result){      
	             $(".invoice_modal").modal("show");
	             $("body").removeClass("loading");  
	             $("#invoice_number_pdf").val(editid);
	             $(".company_details_class").html(result['companyname']);
	             $(".tax_details_class").html(result['taxdetails']);
	             $(".class_row1").html(result['row1']);
	             $(".class_row2").html(result['row2']);
	             $(".class_row3").html(result['row3']);
	             $(".class_row4").html(result['row4']);
	             $(".class_row5").html(result['row5']);
	             $(".class_row6").html(result['row6']);
	             $(".class_row7").html(result['row7']);
	             $(".class_row8").html(result['row8']);
	             $(".class_row9").html(result['row9']);
	             $(".class_row10").html(result['row10']);
	             $(".class_row11").html(result['row11']);
	             $(".class_row12").html(result['row12']);
	             $(".class_row13").html(result['row13']);
	             $(".class_row14").html(result['row14']);
	             $(".class_row15").html(result['row15']);
	             $(".class_row16").html(result['row16']);
	             $(".class_row17").html(result['row17']);
	             $(".class_row18").html(result['row18']);
	             $(".class_row19").html(result['row19']);
	             $(".class_row20").html(result['row20']);
	      }
	    });
	}
	if($(e.target).hasClass('saveas_pdf'))
	{
	    var htmlcontent = $("#letterpad_modal").html();
	    var inv_no = $("#invoice_number_pdf").val();
	    $.ajax({
	      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
	      data:{htmlcontent:htmlcontent,inv_no:inv_no},
	      type:"post",
	      success: function(result)
	      {
	        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
	      }
	    });
	}
	if($(e.target).hasClass('print_pdf'))
	{
	    var htmlcontent = $("#letterpad_modal").html();
	    $.ajax({
	      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
	      data:{htmlcontent:htmlcontent},
	      type:"post",
	      success: function(result)
	      {
	        $("#pdfDocument").attr("src","<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
	        printPdf("<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
	      }
	    });
	}
	if($(e.target).hasClass('invoice_date_option'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		if(client_id == "")
		{
			alert("Please select the Client and click on the Load Button");
			$(".invoice_date_option").prop("checked",false);
		}
		else{
			var value = $(e.target).val();
			if(value == "1")
			{
				$(".invoice_year_div").show();
				$(".custom_date_div").hide();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
        $(".selected_document_batch").hide();
				$(".email_selected_pdf").hide();
			}
			else if(value == "2")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").hide();
				$("body").addClass("loading");
			    setTimeout(function(){ 
			        var client_id = $("#client_search_hidden_infile").val();
			          $(".copy_clients").attr("data-element", client_id);
			          $(".print_clients").attr("data-element", client_id);
			          $(".download_clients").attr("data-element", client_id);
			          $.ajax({
			              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
			              data:{client_id:client_id,type:"2"},
			              dataType:"json",
			              type:"post",
			              success:function(result){
			                $(".invoice_table_div").html(result['invoiceoutput']);
			                $(".invoice_table_div").show();
			                $(".download_selected_pdf").show();
							$(".email_selected_pdf").show();
              $(".selected_export_csv").show();
              $(".selected_document_batch").show();
			                $("body").removeClass("loading");
			                $('#invoice_expand').DataTable({
			                    autoWidth: true,
			                    scrollX: false,
			                    fixedColumns: false,
			                    searching: false,
			                    paging: false,
			                    info: false,
			                    ordering: false
			                });
			                
			          }
			        });
			    }, 2000);
			}
			else if(value == "3")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").show();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
        $(".selected_document_batch").hide();
				$(".email_selected_pdf").hide();
			}
		}
	}
  if($(e.target).hasClass('receipt_date_option'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
      $(".receipt_date_option").prop("checked",false);
    }
    else{
      var value = $(e.target).val();
      if(value == "1")
      {
        $(".receipt_year_div").show();
        $(".custom_date_div_receipt").hide();
        $(".receipt_table_div").html("");
      }
      else if(value == "2")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").hide();
        $("body").addClass("loading");
          setTimeout(function(){ 
              var client_id = $("#client_search_hidden_infile").val();
                $.ajax({
                    url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                    data:{client_id:client_id,type:"2"},
                    dataType:"json",
                    type:"post",
                    success:function(result){
                      $(".receipt_table_div").html(result['receiptoutput']);
                      $(".receipt_table_div").show();
                      $("body").removeClass("loading");
                      $('#receipt_expand').DataTable({
                          autoWidth: true,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          ordering: false
                      });
                }
              });
          }, 2000);
      }
      else if(value == "3")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").show();
        $(".receipt_table_div").html("");
      }
    }
  }
  if($(e.target).hasClass('manage_document_batches')) {
    var client_id = $("#client_search_hidden_infile").val();
    $.ajax({
      url:"<?php echo URL::to('user/manage_document_batches_key_docs'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result){
        $(".document_batches_modal").modal("show");
        $("#batch_list_tbody").html(result);
      }
    })
  }
  if($(e.target).hasClass('close_document_batch_list')){
    var client_id = $("#client_search_hidden_infile").val();
    $.ajax({
      url:"<?php echo URL::to('user/get_document_batch_keydocs_downdown_list'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result){
        $(".document_batches_modal").modal("hide");
        $(".select_document_batch").html(result);
        $(".select_document_batch").val("");
        $("#document_list_tbody").html("");
      }
    })
  }
	if($(e.target).hasClass('load_client_review'))
	{
		var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
    }
    else{
      $("body").addClass("loading");

      $.ajax({
        url:"<?php echo URL::to('user/key_docs_client_select'); ?>",
        type:"get",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $("#hidden_client_id_letters").val(client_id);
          $(".company_name").val(result['company']);
          $(".cro_number").val(result['cro']);
          $(".cm_ard_date").val(result['ard']);
          $(".cro_ard_date").val("");
          $(".cro_type").val(result['type']);
          $(".invoice_select_year").html(result['invoice_year']);
          $(".receipt_select_year").html(result['receipt_year']);
          $(".client_details_div").html(result['client_details']);
          $(".select_document_batch").html(result['batch_output']);
          $("#document_list_tbody").html("");

          $(".manage_document_batches").prop("disabled",false);
          $(".doc_batch_title").html(result['company']+' ('+client_id+')');
          $("#letters_tbody").html(result['letter_output']);
          $("#otherdocs_tbody").html(result['otherdocs_output']);
          $("#tax_tbody").html(result['tax_output']);

          $("#cert_tbody").html(result['cert_output']);
          $("#cons_tbody").html(result['cons_output']);
          $("#memo_tbody").html(result['memo_output']);
          $("#art_tbody").html(result['art_output']);
          $("#app_tbody").html(result['app_output']);
          $("#other_tbody").html(result['other_output']);

          $("#current_tax_tbody").html(result['current_tax_output']);
          $(".transaction_table_div").show();
          $(".client_email").val(result['client_email']);
          $(".cro_ard_date").css("color","#000 !important");
          $(".company_name").css("color","#000 !important");
          $(".update_ard").hide();
          $(".dummy_button").show();
          $(".invoice_year_div").hide();
          $(".custom_date_div").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".download_selected_pdf").hide();
          $(".email_selected_pdf").hide();
          $(".selected_export_csv").hide();
          $(".selected_document_batch").hide();
          $(".invoice_table_div").hide();
          $(".receipt_table_div").hide();
          /*$(".receipt_table_div").hide();*/
          $(".from_invoice").val("");
          $(".to_invoice").val("");
          $(".from_receipt").val("");
          $(".to_receipt").val("");
          $(".invoice_date_option").prop("checked",false);
          $(".receipt_date_option").prop("checked",false);
          $(".opening_bal_h5").hide();
          $(".client_account_table").hide();
          $(".export_client_account_details").hide();
          $('#opening_bal_client').html('');
          $("#client_account_tbody").html('');

          $(".opening_bal_transaction_h5").hide();
          $(".transaction_table").hide();
          $(".export_transaction_details").hide();
          $('#opening_bal_transaction').html('');
          $("#transaction_tbody").html('');
          $.ajax({
            url:"<?php echo URL::to('user/get_client_year_id'); ?>",
            type:"post",
            data:{client_id:client_id},
            success:function(yearid)
            {
              $(".quick_links").each(function(){
                var url = $(this).attr('href')
                url = url.replace('clientiden', client_id)
                url = url.replace('encodeclientid', btoa(client_id))
                $(this).attr('href', url);
              });   
                    
              var text_val=$(".client_common_search").val();
              var arr = text_val.split('-');
              if(arr.length==2){
                var hf_clientspecificname = arr[0]+' ('+arr[1]+')';
                $("#client_specific_name").text(' for '+hf_clientspecificname);
              }          
              $(".quick_links").show();
              if(yearid=="0"){
                $('.yearend_link').hide();
                $('.yearend_link').attr("href","");
              } 
              else{
                $('.yearend_link').show();
                $('.yearend_link').attr("href",yearid);
              }
            }
          });
          ajaxcomplete();
          $("body").removeClass("loading");
        }
      })
    }
	}
	if($(e.target).hasClass('refresh_cro'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		if(client_id == "")
		{
			alert("Please select the Client and click on the Load Button");
			$(".invoice_date_option").prop("checked",false);
		}
		else{
			$("body").addClass("loading");
			var cro = $(".cro_number").val();
			var client_id = $("#client_search_hidden_infile").val();
			$.ajax({
				url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
				type:"get",
				dataType:"json",
				data:{cro:cro,clientid:client_id},
				success:function(result)
				{
					if(result['ardstatus'] == "1")
					{
						$(".cro_ard_date").val(result['next_ard']);
						$(".cro_ard_date").css("color","#f00 !important");
						$(".update_ard").show();
					}
					else{
						$(".cro_ard_date").val(result['next_ard']);
						$(".update_ard").hide();
					}
					if(result['companystatus'] == "1")
					{
						$(".company_name").val(result['company_name']);
						$(".company_name").css("color","#f00 !important");
					}
					else{
						$(".company_name").val(result['company_name']);
					}
					$("body").removeClass("loading");
				}
			})
		}
	}
	if($(e.target).hasClass('update_ard'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		var cro_ard = $(".cro_ard_date").val();
		$.ajax({
			url:"<?php echo URL::to('user/update_cro_ard_date'); ?>",
			type:"post",
			data:{client_id:client_id,cro_ard:cro_ard},
			success:function(result)
			{
				$(".cm_ard_date").val(cro_ard);
				$(".cro_ard_date").css("color","#000 !important");
				$(".update_ard").hide();
			}
		})
	}
	if($(e.target).hasClass('download_selected_pdf'))
	{
		var checked = $(".invoice_check:checked").length;
		if(checked > 0)
		{
      // $(".download_pdf_folder_modal").modal("show");
      $("body").addClass("loading");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_download_selected_pdfs'); ?>",
        type:"post",
        dataType:"json",
        data:{ids:ids},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result['zipfile'],result['zipfile']);
        }
      })
		}
		else{
			alert("Please select atleast one invoice to download");
		}
	}
  if($(e.target).hasClass('selected_export_csv'))
  {
    var checked = $(".invoice_check:checked").length;
    if(checked > 0)
    {
      // $(".download_pdf_folder_modal").modal("show");
      $("body").addClass("loading");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_export_selected_csvs'); ?>",
        type:"post",
        dataType:"json",
        data:{ids:ids},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result['timefolder']+'/'+result['filename'],result['filename']);
        }
      })
    }
    else{
      alert("Please select atleast one invoice to export as csv");
    }
  }
  if($(e.target).hasClass('save_pdfs_in_folder'))
  {
    var folder_path = $(".download_folder_path").val();
    var folder_name = $(".create_folder").val();
    if(folder_path == "")
    {
      alert("Please copy and paste the folder path to download the files");
    }
    else{
      $("body").addClass("loading_apply");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_download_selected_pdfs'); ?>",
        type:"post",
        data:{ids:ids,folder_path:folder_path,folder_name:folder_name},
        success:function(result)
        {
          $("body").removeClass("loading_apply");
          $(".download_pdf_folder_modal").modal("hide");
          $(".download_folder_path").val("");
          $(".create_folder").val("");
          //SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result['zipfile'],result['zipfile']);
        }
      })
    }
  }
	if($(e.target).hasClass('email_selected_pdf'))
	{
		var checked = $(".invoice_check:checked").length;
		if(checked > 0)
		{
			$("body").addClass("loading");
			var ids = '';
			$(".invoice_check:checked").each(function() {
				if(ids == "")
				{
					ids = $(this).attr("data-element");
				}
				else{
					ids = ids+','+$(this).attr("data-element");
				}
			});
			$.ajax({
				url:"<?php echo URL::to('user/invoice_email_selected_pdfs'); ?>",
				type:"post",
        dataType:"json",
				data:{ids:ids},
				success:function(result)
				{
          var signature = $("#keydocs_signature").val();
          if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
					CKEDITOR.replace('editor_1',
          {
            height: '300px',
          });
          CKEDITOR.instances['editor_1'].setData("<p>Hi,</p><p>We have attached a copy of the following Invoices</p>"+result['pdfs']+"<p>"+signature+"</p>");
					$(".sent_to_client").modal("show");
					$(".zip_name").html(result);
          if(checked == 1)
          {
            $(".zip_image").hide();
            $(".pdf_image").show();
          }
          else{
            $(".zip_image").show();
            $(".pdf_image").hide();
          }
					$(".hidden_attachment").val(result['zipfile']);
          $(".spam_attachment").html(result['zipfile']);
					$("body").removeClass("loading");
				}
			})
		}
		else{
			alert("Please select atleast one invoice to download");
		}
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
Dropzone.options.ImageUploadEmailKeydocs = {
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
              Dropzone.forElement("#ImageUploadEmailKeydocs").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".keydocs_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".keydocs_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailKeydocs").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".keydocs_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailKeydocs").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".keydocs_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".keydocs_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailKeydocs").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Keydocs Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
Dropzone.options.imageUploadprogress = {
    maxFiles: 5000,
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
            $(".letters_modal").modal("hide");
            if(obj.type == "1"){
              var countlen = $("#letters_tbody").find(".fa-trash").length;
              if(countlen > 0) {
                $("#letters_tbody").append(obj.output);
              }
              else {
                $("#letters_tbody").html(obj.output);
              }
            }
            else if(obj.type == "2"){
              $("#tax_tbody").append(obj.output);
              $("#current_tax_tbody").html(obj.current_output);
            }

            else if(obj.type == "4"){
              var countlen = $("#cert_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#cert_tbody").append(obj.output);
              } else {
                $("#cert_tbody").html(obj.output);
              }
              
            }
            else if(obj.type == "5"){
              var countlen = $("#cons_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#cons_tbody").append(obj.output);
              } else {
                $("#cons_tbody").html(obj.output);
              }
            }
            else if(obj.type == "6"){
              var countlen = $("#memo_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#memo_tbody").append(obj.output);
              } else {
                $("#memo_tbody").html(obj.output);
              }
            }
            else if(obj.type == "7"){
              var countlen = $("#art_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#art_tbody").append(obj.output);
              } else {
                $("#art_tbody").html(obj.output);
              }
            }

            else if(obj.type == "8"){
              var countlen = $("#app_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#app_tbody").append(obj.output);
              } else {
                $("#app_tbody").html(obj.output);
              }
            }

            else if(obj.type == "9"){
              var countlen = $("#other_tbody").find(".fa-trash").length;
              if(countlen > 0){
                $("#other_tbody").append(obj.output);
              } else {
                $("#other_tbody").html(obj.output);
              }
            }

            else if(obj.type == "10"){
              var countlen = $("#otherdocs_tbody").find(".fa-trash").length;
              if(countlen > 0) {
                $("#otherdocs_tbody").append(obj.output);
              }
              else {
                $("#otherdocs_tbody").html(obj.output);
              }
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

            ajaxcomplete();
          }
        });
        this.on("error", function (file) {
          //$(".add_progress_attachments").html("");
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
          //$(".add_progress_attachments").html("");
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            //$.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>
@stop
