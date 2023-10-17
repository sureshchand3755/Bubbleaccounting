<html>
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap.min.css')?>">
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap-theme.min.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/font-awesome-4.2.0/css/font-awesome.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/style-responsive.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/stylesheet-image-based.css')?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL::to('public/assets/css/datepicker/jquery-ui.css')?>">
<link rel="stylesheet" href="<?php echo URL::to('public/assets/plugins/lightbox/colorbox.css')?>">
<script src="<?php echo URL::to('public/assets/js/datepicker/jquery-1.12.4.js')?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.validate.js')?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.number.min.js')?>"></script>
<script src="<?php echo URL::to('public/assets/plugins/pagination/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.css'); ?>" />
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/datepicker/jquery-ui.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/bootstrap.min.js')?>"></script>

<script src="<?php echo URL::to('public/assets/plugins/lightbox/jquery.colorbox.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/jscrollpane/style/jquery.jscrollpane.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/ladda/dist/ladda-themeless.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/summernote/dist/summernote.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">


<!-- Vendors Scripts -->
<!-- v1.0.0 -->

<script src="<?php echo URL::to('public/assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
<!-- <script src="<?php //echo URL::to('public/assets/vendors/html5-form-validation/dist/jquery.validation.min.js') ?>"></script> -->
<script src="<?php echo URL::to('public/assets/vendors/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/summernote/dist/summernote.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/editable-table/mindmup-editabletable.js') ?>"></script>
<link href="<?php echo URL::to('public/assets/common/css/jquery-ui.css')?>" rel="stylesheet">
<!-- <script src="<?php //echo URL::to('public/assets/common/js/jquery-ui.js')?>"></script> -->
<link href="<?php echo URL::to('public/assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/new_style.css')?>">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
</head>
<body class="body_bg">
<style>
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
<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $companydetails_val = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
  $companyname_val = $companydetails_val->company.'-'.$client_id;
  $hiddenval = $_GET['client_id'];
}
else{
  $companyname_val = '';
  $hiddenval = '';
}
?>
<div class="modal fade sent_to_client" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="send_request_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Selected PDF</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
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
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control client_email" placeholder="Enter Email Address" name="client_search" value="" autocomplete="off" required>
              <input type="hidden" class="hidden_client_id" id="hidden_client_id" value="<?php echo $hiddenval; ?>" name="hidden_client_id">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <?php 
          $clientaccount_settings = DB::table('clientaccountreview_settings')->where('practice_code',Session::get('user_practice_code'))->first();
          $admin_cc = $clientaccount_settings->client_account_cc_email;
        ?> 
              <input type="text" name="cc_approval_to_client" id="cc_approval_to_client" class="form-control" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_to_client" id="subject_to_client" class="form-control subject_to_client" value="Invoice(s) Attached" required>
            </div>
          </div>
          <?php
          if($clientaccount_settings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($clientaccount_settings->email_header_url.'/'.$clientaccount_settings->email_header_filename);
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
            <div id="client_attachments">
              <h4 style="margin-top: 20px;float: left;width: 100%;margin-left: 13px;">Attachment:</h4>
              <img class="zip_image" src="<?php echo URL::to('public/assets/images/zip.png'); ?>" style="width:30px">
              <img class="pdf_image" src="<?php echo URL::to('public/assets/images/pdf.png'); ?>" style="width:30px">
              <spam class="zip_name"></spam>
              <spam class="spam_attachment"></spam>
              <input type="hidden" name="hidden_attachment" class="hidden_attachment" value="">

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
<div class="content_section" style="margin-top:20px">
  	<div class="page_title">

        <div class="col-lg-4" style="padding-right: 0px;">
        	<div class="col-lg-4 padding_00">
    				<h4 style="font-weight: 600">Enter Client Name: </h4>
    			</div>
    			<div class="col-lg-7" style="padding: 0px;">
    				<div class="form-group">
    				    
    				    <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500;width:90%; display:inline;" value="<?php echo $companyname_val; ?>" required />                      
                <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />  
    				    <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $hiddenval; ?>" name="client_id">
    				</div>                  
    			</div>
    			<div class="col-md-1" style="padding: 0px">
    				<input type="button" name="load_client_review" class="common_black_button load_client_review" value="Load">
    			</div>
    			<div class="col-md-12 client_details_div">
    			</div>
        </div>
        <div class="col-lg-2">
        	&nbsp;
        </div>
        <div class="col-lg-6" style="padding:0px 40px">
        	<h3>CRO Information: </h3>
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
              </table>
        </div>
        <div class="col-md-4">
        	<h3>Invoice List</h3>
        	<input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_1" value="1"><label for="invoice_date_option_1">Year</label>
        	<input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_2" value="2"><label for="invoice_date_option_2">All Invoice</label>
        	<input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_3" value="3"><label for="invoice_date_option_3">Custom Date</label>
          <br/>
        	<input type="button" name="download_selected_pdf" class="common_black_button download_selected_pdf" value="Download Selected PDF" style="display:none">
        	<input type="button" name="email_selected_pdf" class="common_black_button email_selected_pdf" value="Email Selected PDF" style="display:none">
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
  	    	<div class="col-md-12 invoice_table_div padding_00" style="display: none; margin-top: 7px;">
      		</div> 
        </div>
        <div class="col-md-4">
          <h3>Receipt List</h3>
          <input type="radio" name="receipt_date_option" class="receipt_date_option" id="receipt_date_option_1" value="1"><label for="receipt_date_option_1">Year</label>
          <input type="radio" name="receipt_date_option" class="receipt_date_option" id="receipt_date_option_2" value="2"><label for="receipt_date_option_2">All Receipt</label>
          <input type="radio" name="receipt_date_option" class="receipt_date_option" id="receipt_date_option_3" value="3"><label for="receipt_date_option_3">Custom Date</label>
          <!-- <input type="button" name="download_selected_pdf" class="common_black_button download_selected_pdf" value="Download Selected PDF" style="display:none">
          <input type="button" name="email_selected_pdf" class="common_black_button email_selected_pdf" value="Email Selected PDF" style="display:none"> -->
          <div class="col-md-12 receipt_year_div padding_00" style="margin-top: 20px;display:none">
            <h5 class="col-md-1 padding_00" style="font-weight: 600; width: 80px;">Select Year:</h5>
            <div class="col-md-3">
              <select name="receipt_select_year" class="receipt_select_year form-control">
                <option value="">Select Year</option>
              </select>
            </div>
            <div class="col-md-2 padding_00">
              <input type="button" name="load_receipt_year" class="common_black_button load_all_cm_receipt" value="Load receipt">
            </div>
          </div>
          <div class="col-md-12 custom_date_div_receipt" style="margin-top: 20px;display:none">
            <h5 class="col-md-1">From:</h5>
            <div class="col-md-3">
              <input type="text" name="from_receipt" class="form-control from_receipt" value="">
            </div>
            <h5 class="col-md-1">To:</h5>
            <div class="col-md-3">
              <input type="text" name="to_receipt" class="form-control to_receipt" value="">
            </div>
            <div class="col-md-2">
              <input type="button" name="load_receipt_year" class="common_black_button load_all_cm_receipt" value="Load receipt">
            </div>
          </div>
          <div class="col-md-12 receipt_table_div padding_00" style="display: none; margin-top: 20px;">
          </div> 
        </div>
        <div class="col-md-4">
        	<h3>Transaction Listing</h3>
  	    	<div class="col-md-12 transaction_table_div padding_00">
  	    		<ul class="nav nav-tabs">
              <li class="nav-item active">
                <a class="nav-link transaction_tab active" href="javascript:" id="listing-tab" data-toggle="tab" data-target="#listing"  role="tab" aria-controls="home" aria-selected="true">Transaction Listing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link client_tab" href="javascript:" id="account_client_review-tab" data-toggle="tab" data-target="#account_client_review"  role="tab" aria-controls="home" aria-selected="true">Client Account</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade in active" id="listing" role="tabpanel" aria-labelledby="listing-tab">
                <a href="javascript:" class="load_transaction_listing common_black_button" style="margin-top: 10px;float:left">Load Transaction Listing</a>
                <a href="javascript:" class="export_transaction_details common_black_button" style="margin-top: 10px;float:right;display: none">EXPORT CSV</a>
                <h5 class="opening_bal_transaction_h5" style="display:none">Opening Balance: <spam id="opening_bal_transaction"></spam></h5>
                <table class="table own_table_white transaction_table" style="width: 100%;margin-top: 27px;float:left;display:none">
                  <thead>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Description</th>
                    <th>€</th>
                    <th>Balance</th>
                  </thead>
                  <tbody id="transaction_tbody">
                  </tbody>
                </table>
                <p style="margin-top:75%;clear:both;float:left">&nbsp;</p>
              </div>
              <div class="tab-pane fade" id="account_client_review" role="tabpanel" aria-labelledby="account_client_review-tab">
                <a href="javascript:" class="load_client_account_details common_black_button" style="margin-top: 10px;float:left">Load Client Account Details</a>
                <a href="javascript:" class="export_client_account_details common_black_button" style="margin-top: 10px;float:right">EXPORT CSV</a>
                <h5 class="opening_bal_h5" style="display:none">Opening Balance: <spam id="opening_bal_client"></spam></h5>
                <table class="table own_table_white client_account_table" style="width: 100%;margin-top: 27px;float:left;display:none">
                  <thead>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Description</th>
                    <th>€</th>
                    <th>Balance</th>
                  </thead>
                  <tbody id="client_account_tbody">
                  </tbody>
                </table>
                <p style="margin-top:75%;clear:both;float:left">&nbsp;</p>
              </div>
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
</div>
<script>
$.ajaxQueue = [];
var que = $.ajaxQueue;

$.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function(){
        if (this.queue) {
            que.push(this);
        }
        else {
            return true;
        }
        if (que.length > 1) {
            return false;
        }
    },
    complete: function(){
        que.shift();
        var newReq = que[0];
        if (newReq) {
            // setup object creation should be automated 
            // and include all properties in queued AJAX request
            // this version is just a demonstration. 
            var setup = {
                url: newReq.url,
                success: newReq.success
            };
            $.ajax(setup);
        }
    }
});
function detectPopupBlocker() {
  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");
  if (!myTest) {
    return 1;
  } else {
    myTest.close();
    return 0;
  }
}

function SaveToDisk(fileURL, fileName) {
  
  var idval = detectPopupBlocker();
  if(idval == 1)
  {
    alert("A popup blocker was detected. Please Allow the popups to download the file.");
  }
  else{
    // for non-IE
    if (!window.ActiveXObject) {
      var save = document.createElement('a');
      save.href = fileURL;
      save.target = '_blank';
      save.download = fileName || 'unknown';
      var evt = new MouseEvent('click', {
        'view': window,
        'bubbles': true,
        'cancelable': false
      });
      save.dispatchEvent(evt);
      (window.URL || window.webkitURL).revokeObjectURL(save.href);
    }
    // for IE < 11
    else if ( !! window.ActiveXObject && document.execCommand)     {
      var _window = window.open(fileURL, '_blank');
      _window.document.close();
      _window.document.execCommand('SaveAs', true, fileName || fileURL)
      _window.close();
    }
  }
  $("body").removeClass("loading");
}
$(document).ready(function() {
  $(".load_client_review").trigger("click");
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
});
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
  var ascending = false;
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
        url:"<?php echo URL::to('user/client_review_email_selected_pdf'); ?>",
        type:"post",
        data:{from_user_to_client:from_user_to_client,hidden_client_id:hidden_client_id,hidden_attachment:hidden_attachment,client_search:client_search,cc_approval_to_client:cc_approval_to_client,subject_to_client:subject_to_client,message_editor_to_client:message_editor_to_client},
        success:function(result)
        {
          $(".sent_to_client").modal("hide");
          $(".sent_to_client").removeClass("in");
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Email Sent Successfully</p>",width:"30%",fixed:true});
          $("body").removeClass("loading");
        }
      })

    }
  }
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
    $(".transaction_table_div").find(".nav-item").removeClass("active");
    $(".transaction_table_div").find(".nav-item").find("a").removeClass("active");
    $(e.target).addClass("active");
    $(e.target).parent().addClass("active");
    $("#account_client_review").addClass("in");
    $(".opening_bal_h5").hide();
    $(".client_account_table").hide();
    $(".export_client_account_details").hide();
    $('#opening_bal_client').html('');
    $("#client_account_tbody").html('');
  }
  if($(e.target).hasClass('transaction_tab'))
  {
    $(".transaction_table_div").find(".nav-item").removeClass("active");
    $(".transaction_table_div").find(".nav-item").find("a").removeClass("active");
    $(e.target).addClass("active");
    $(e.target).parent().addClass("active");
    $("#listing").addClass("in");

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
               $(".invoice_modal").addClass("in");
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
        url:"<?php echo URL::to('user/client_review_client_select'); ?>",
        type:"get",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".company_name").val(result['company']);
          $(".cro_number").val(result['cro']);
          $(".cm_ard_date").val(result['ard']);
          $(".cro_ard_date").val("");
          $(".cro_type").val(result['type']);
          $(".invoice_select_year").html(result['invoice_year']);
          $(".receipt_select_year").html(result['receipt_year']);
          $(".client_details_div").html(result['client_details']);
          $(".client_email").val(result['client_email']);
          $(".cro_ard_date").css("color","#000 !important");
          $(".company_name").css("color","#000 !important");
          $(".update_ard").hide();
          $(".invoice_year_div").hide();
          $(".custom_date_div").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".download_selected_pdf").hide();
          $(".email_selected_pdf").hide();
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
          $(".download_pdf_folder_modal").removeClass("in");
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
          if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
					CKEDITOR.replace('editor_1',
          {
            height: '300px',
          });
          CKEDITOR.instances['editor_1'].setData("<p>Hi,</p><p>We have attached a copy of the following Invoices</p>"+result['pdfs']);
					$(".sent_to_client").modal("show");
          $(".sent_to_client").addClass("in");
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
					$(".hidden_attachment").html(result['zipfile']);
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
</script>
</body>
</html>
