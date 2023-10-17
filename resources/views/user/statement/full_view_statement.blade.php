@extends('userheader')
@section('content')
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<style>
.own_table_white tr td
{
  height:70px;
}
.displaynone{
  display: none !important;
}
.own_table_white tr td{background: #fff !important}
.own_table_white tr:hover td{background: #fff !important}
.own_table_white tr:hover td:first-child {background: #fff !important}
.invoice_td{
  cursor: pointer;
}
.received_td{
  cursor: pointer;
}
.modal{
  z-index: 99999999;
}
#colorbox{
  z-index: 99999999999;
}
.attachment_div{
  margin-top: 10px;
  margin-left: 25px;
}
.add_attachment_month_year{
  float:left;
}
.email_unsent_label{
  margin-top: 10px;
margin-left: 25px;
}
.email_unsent{
  float:left;
}
.dz-remove{
    color: #000;
    font-weight: 800;
    text-transform: uppercase;
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
.status_icon{
padding: 10px;
width: 50%;
border-radius: 39px;
text-align: center;
}
.red_status{ background: #f00; color:#fff !important; }
.orange_status{ background: orange; color:#000 !important; }
.green_status{ background: green; color:#fff !important; }
.blue_status{ background: blue;color:#fff !important;  }
.yellow_status{ background: yellow !important; color:#000 !important;}
.table>thead>tr>th { background: #fff !important; }
.fa-sort{ cursor:pointer; }
.company_td { font-weight:800; }
.form-control[disabled] { background-color:#ececec !important; cursor: pointer; }
.fa-check { color:green; }
.fa-times { color:#f00; }
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999999;
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
.modal_load_balance {
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
body.loading_balance {
    overflow: hidden;   
}
body.loading_balance .modal_load_balance {
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
.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
    display: block;
}

.modal_load_build {
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
body.loading_build {
    overflow: hidden;   
}
body.loading_build .modal_load_build {
    display: block;
}
.modal_load_export {
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
body.loading_export {
    overflow: hidden;   
}
body.loading_export .modal_load_export {
    display: block;
}
.disclose_label{ width:300px; }
.option_label{width:100%;}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}
.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
.notepad_div {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div textarea{
  height:212px;
}
.notepad_div_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes textarea{
  height:212px;
}
.notepad_div_progress_notes,.notepad_div_completion_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_progress_notes textarea, .notepad_div_completion_notes textarea{
  height:212px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.notepad_div_notes_add {
    border: 1px solid #000;
    width: 280px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes_add textarea{
  height:212px;
}
.edit_allocate_user{
  cursor: pointer;
  font-weight:600;
}
.disabled_icon{
  cursor:no-drop;
}
.disabled{
  pointer-events: none;
}
.disable_user{
  pointer-events: none;
  background: #c7c7c7;
}
.mark_as_incomplete{
  background: green;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
label{width:100%;}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked + .slider {
  background-color: #2196F3;
}
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}
.slider.round:before {
  border-radius: 50%;
}
.tr_closed{
  display:none;
}
.show_closed>.tr_closed{
  display:table-row !important;
}
/* Customize the label (the container) */
.form_checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
/* Hide the browser's default checkbox */
.form_checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}
/* Create a custom checkbox */
.checkmark_checkbox {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border:1px solid;
}
/* On mouse-over, add a grey background color */
.form_checkbox:hover input ~ .checkmark_checkbox {
  background-color: #fff;
  border:1px solid;
}
/* When the checkbox is checked, add a blue background */
.form_checkbox input:checked ~ .checkmark_checkbox {
  background-color: #fff;
}
/* Create the checkmark_checkbox/indicator (hidden when not checked) */
.checkmark_checkbox:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the checkmark_checkbox when checked */
.form_checkbox input:checked ~ .checkmark_checkbox:after {
  display: block;
}
/* Style the checkmark_checkbox/indicator */
.form_checkbox .checkmark_checkbox:after {
  left: 7px;
  top: 3px;
  width: 5px;
  height: 10px;
  border: solid #3a3a3a;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.form_radio {
  display: block;
  position: relative;
  padding-right: 20px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
/* Hide the browser's default radio button */
.form_radio input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}
/* Create a custom radio button */
.checkmark_radio {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border-radius: 50%;
  border:1px solid #3a3a3a;
}
/* On mouse-over, add a grey background color */
.form_radio:hover input ~ .checkmark_radio {
  background-color: #fff;
}
/* When the radio button is checked, add a blue background */
.form_radio input:checked ~ .checkmark_radio {
  background-color: #fff;
}
/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark_radio:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the indicator (dot/circle) when checked */
.form_radio input:checked ~ .checkmark_radio:after {
  display: block;
}
/* Style the indicator (dot/circle) */
.form_radio .checkmark_radio:after {
  top: 5px;
  left: 5px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background:green;
}
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
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.form_radio .text{}
.form_radio span{right: 0px; left: unset;}
.build_statement{opacity:1;}
/*.table-fixed-header {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header thead tr th {
  background: white;
  position: sticky;
  top: 84; 
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}*/
.nav-tabs > li { width:10%;text-align: center;font-weight: 600; }
.invoice_td{ text-align: right; }
.received_td{ text-align: right; }
.closing_bal{ text-align: right; }
.opening_bal_td{ text-align: right; }
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
@include('user/statement/settings');
<div class="modal fade invoice_list_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title invoice_title">Invoice List</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <table class="table">
                <thead>
                  <th>Invoice Number</th>
                  <th>Invoice Date</th>
                  <th style="text-align: right">Net</th>
                  <th style="text-align: right">Vat</th>
                  <th style="text-align: right">Gross</th>
                </thead>
                <tbody id="invoice_list_tbody">
                </tbody>
                <tr>
                  <td colspan="2">Total:</td>
                  <td class="total_net" style="text-align:right"></td>
                  <td class="total_vat" style="text-align:right"></td>
                  <td class="total_gross" style="text-align:right"></td>
                </tr>
              </table>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade receipt_list_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title receipt_title">Receipt List</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <table class="table">
                <thead>
                  <th>Receipt Date</th>
                  <th>Debit Nominal</th>
                  <th>Credit Nominal</th>
                  <th>Amount</th>
                </thead>
                <tbody id="receipt_list_tbody">
                </tbody>
                <tr>
                  <td colspan="3">Total:</td>
                  <td class="total_amount" style="text-align:right"></td>
                </tr>
              </table>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade statement_functions_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title receipt_title">Statement Functions</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <div class="row">
                <div class="col-md-12" style="margin-bottom:10px; margin-left:2px;">
                  <spam style="">Current Month: </spam>
                  <select name="select_months_in_stmt_funcs" class="select_months_in_stmt_funcs form-control" style="">
                    <?php
                    $current_month = date('M-Y');
                    $current_monthh = date('m-Y');
                    $curr_str_month = date('Y-m-01');
                    $opening_month = DB::table('user_login')->first();
                    $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
                    $edate = strtotime($curr_str_month);
                    $bdate = strtotime($opening_bal_month);
                    $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
                    echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
                    for($i= 1; $i<=$age; $i++)
                    {
                      $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
                      if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
                      echo '<option value="'.$datevalll.'" '.$selected.'>'.$datevall.'</option>';
                      $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-12">
                  <a href="javascript:" id="build_all" class="common_black_button build_all" style="float:left">Build All</a>
                  <a href="javascript:" id="delete_all" class="common_black_button delete_all" style="float:left">Delete All</a>
                  <a href="javascript:" id="distribute_all" class="common_black_button distribute_all" style="float:left">Distribute All</a>
                </div>
                
              </div>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
<div class="content_section" style="margin-bottom:200px">
  <div style="width: 98%; float: left; position: fixed;" id="headerfixed">
    <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Client Statement
            </h4>
    </div>
    <div class="row">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link" id="journal-listing-tab" href="<?php echo URL::to('user/statement_list'); ?>">Current View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="journal-listing-tab" href="<?php echo URL::to('user/monthly_statement'); ?>">Multi Period</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link active" id="trial-balance-tab" href="javascript:">Full View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="trial-balance-tab" href="<?php echo URL::to('user/client_specific_statement'); ?>">Client Specific</a>
        </li>
        <a href="javascript:" class="fa fa-cog common_black_button statement_settings" style="float:right"></a>
      </ul>
      <div class="col-md-12" style="margin-top:20px">
          <a href="javascript:" id="load_clients" class="common_black_button load_clients" style="float:left">Load Clients</a>
          <input type="button" id="export_values" class="common_black_button export_values" value="Export as CSV" style="float:right;">
          <input type="button" id="stmt_funcs" class="common_black_button stmt_funcs" value="Statement functions" disabled style="float:right;">
          <input type="button" id="load_values" class="common_black_button load_values" value="Load Values" disabled style="float:right;">
          <select name="select_month" class="select_month_values form-control" style="width:10%;margin-left:10px;float:right">
            <?php
            $current_month = date('M-Y');
            $current_monthh = date('m-Y');
            $curr_str_month = date('Y-m-01');
            $opening_month = DB::table('user_login')->first();
            $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
            $edate = strtotime($curr_str_month);
            $bdate = strtotime($opening_bal_month);
            $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
            echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
            for($i= 1; $i<=$age; $i++)
            {
              $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
              $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
              $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
              if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
              echo '<option value="'.$datevalll.'" '.$selected.'>'.$datevall.'</option>';
              $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
            }
            ?>
          </select>
          <spam style="float:right;margin-top: 8px;">Current Month: </spam>
      </div>
    </div>
  </div>
    <div class="row" style="margin-top:25px;">
      <div class="col-md-12" id="load_table_clients" style="margin-top: 176px; padding: 0px;">
      </div>
      
    </div>
</div>
<div class="modal_load"></div>
  <div class="modal_load_balance" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Opening Balance</p> </div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Opening Balance are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Loading Opening Balances: <span id="apply_first"></span> of <span id="apply_last"></span> Clients</p>
</div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Invoices, Receipts and Closing Balance are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Loading Values: <span id="count_first"></span> of <span id="count_last"></span> Clients</p>
</div>

<div class="modal_load_build" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Statements are built for the selected month for all the clients.</p>
  <p style="font-size:18px;font-weight: 600;">Processing Statements for: <span id="build_first"></span> of <span id="build_last"></span> Clients</p>
</div>

<div class="modal_load_export" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Invoices, Receipts and Closing Balance are Processed in to a CSV File.</p>
  <p style="font-size:18px;font-weight: 600;">It may take upto 3 to 4 minutes to process the data in to a CSV file.</p>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<script>
  var query_begin=0;  
  var temp=0;
  var _list=[];
/* @Note: not sure e.pageX will work in IE8 */
function scrolldiv()
{ 
  /* A full compatability script from MDN: */
  var supportPageOffset = window.pageXOffset !== undefined;
  var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
 
  /* Set up some variables  */
  
  var demoItem3 = document.getElementById("demoItem3"); 
  /* Add an event to the window.onscroll event */
  window.addEventListener("scroll", function(e) {  
    
    /* A full compatability script from MDN for gathering the x and y values of scroll: */
    var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
 
    
    demoItem3.style.top = -y + 305 + "px";
  });
  
};

function validate(evt) {
  var theEvent = evt || window.event;
  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9,]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
$(document).ready(function(){
  CKEDITOR.replace('editor_1',
 {
  height: '150px',
 }); 
  CKEDITOR.replace('editor_2',
 {
  height: '150px',
 }); 
});
function next_client_bal_check()
{
  $("body").addClass("loading_balance");
  $.ajax({
    url:"<?php echo URL::to('user/get_client_opening_balance'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#statement_client_tbody').find('tr').each(function(){
        $(this).find('td').eq(6).html(result[i]);
        i++;
      });
      setTimeout(function() {
          $("body").removeClass("loading_balance");
          next_client_values_check(0);
      },1000);
    }
  });
}
function next_client_values_check(count)
{
  if(count == 0)
  {
    $("body").addClass("loading_content");
  }
  var current_month = $(".select_month_values").val();
  var countclient = $(".client_tr").length;
  $("#count_last").html(countclient);
  $("#count_first").html('1');
  
  $.ajax({
    url:"<?php echo URL::to('user/get_client_statement_values'); ?>",
    type:"post",
    dataType:"json",
    data:{current_month:current_month,count:count},
    success:function(result)
    {
      if(result[0]) {
        if(count == 0){
          $(".tr_header_1").find("th").detach();
          $(".tr_header_2").find("th").detach();
          $(".tr_header_1").append(result[0]['thval']);
          $(".tr_header_2").append(result[0]['thval_divide']);
          $(".select_months_in_stmt_funcs").html(result[0]['options']);
        }
        $.each(result, function(index,value) {
          $(".client_value_tr_"+result[index]['client_id']).find("td").detach();
          $(".client_value_tr_"+result[index]['client_id']).append(result[index]['tdval_divide']);
        });

        setTimeout( function() {
            var countval = count + 1;
            var offset = countval * 100;
            offset = offset + 1;
            if($(".client_tr:eq("+offset+")").length > 0)
            {
              next_client_values_check(countval);
              $("#count_first").html(offset);
            }
            else{
              scrolldiv();
              $("body").removeClass("loading_content");
              $(".stmt_funcs").prop("disabled",false);
            }
        },200);
      }
    }
  });
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
   if($(e.target).hasClass('statement_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Client Statement Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('build_statement'))
  {
    var client_id = $(e.target).attr("data-client");
    var month = $(e.target).attr("data-element");
    var opening_bal = $(e.target).attr("data-balance");
    $.ajax({
      url:"<?php echo URL::to('user/build_statement'); ?>",
      type:"post",
      data:{client_id:client_id,month:month,opening_bal:opening_bal},
      success:function(result)
      {
        $(e.target).parents("td").html(result);
      }
    })
  }
  if($(e.target).hasClass('ok_to_send_statement'))
  {
    var client_id = $(e.target).attr("data-client");
    if($(e.target).is(":checked"))
    {
      var status = 1;
      $.ajax({
        url:"<?php echo URL::to('user/change_send_statement_status'); ?>",
        type:"post",
        data:{client_id:client_id,status:status},
        success:function(result)
        {

        }
      })
    }
    else{
      var status = 0;
      $.ajax({
        url:"<?php echo URL::to('user/change_send_statement_status'); ?>",
        type:"post",
        data:{client_id:client_id,status:status},
        success:function(result)
        {

        }
      })
    }
  }
  if($(e.target).hasClass('statement_settings'))
  {
    $(".settings_modal").modal("show");
  }
  if($(e.target).hasClass('load_clients'))
  {
    $("body").addClass("loading");
    var current_month = $(".select_month_values").val();
    $.ajax({
      url:"<?php echo URL::to('user/load_statement_clients'); ?>",
      type:"post",
      data:{current_month:current_month},
      success:function(result)
      {
        $("#load_table_clients").html(result);
        $(".load_values").prop("disabled",false);
        
        $(".statement_settings").show();
        var countclient = $(".client_tr").length;
        $("#apply_last").html(countclient);
        scrolldiv();
        $("body").removeClass("loading");
        //next_client_bal_check(0);
      }
    })
  }
  if($(e.target).hasClass('export_values')){
    $("body").addClass("loading_export");
    var current_month = $(".select_month_values").val();
    $.ajax({
      url:"<?php echo URL::to('user/export_statement_clients'); ?>",
      type:"post",
      data:{current_month:current_month},
      success:function(result)
      {
        $("body").removeClass("loading_export");  
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    })
  }
  if($(e.target).hasClass('load_values'))
  {
    var current_month = $(".select_month_values").val();
    if(current_month != "")
    {
      next_client_bal_check();
    }
  }
  if($(e.target).hasClass('invoice_td'))
  {
    $("body").addClass("loading");
    $(".invoice_list_modal").modal("show");
    var month = $(e.target).attr("data-month");
    var client = $(e.target).parents(".client_value_tr").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_invoice_list_statement'); ?>",
      type:"post",
      dataType:"json",
      data:{client:client,month:month},
      success:function(result)
      {
        $("#invoice_list_tbody").html(result['output']);
        $(".total_net").html(result['total_net']);
        $(".total_vat").html(result['total_vat']);
        $(".total_gross").html(result['total_gross']);
        $(".invoice_title").html(result['title']);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('received_td'))
  {
    $("body").addClass("loading");
    $(".receipt_list_modal").modal("show");
    var month = $(e.target).attr("data-month");
    var client = $(e.target).parents(".client_value_tr").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_receipt_list_statement'); ?>",
      type:"post",
      dataType:"json",
      data:{client:client,month:month},
      success:function(result)
      {
        $("#receipt_list_tbody").html(result['output']);
        $("body").removeClass("loading");

        $("#receipt_list_tbody").html(result['output']);
        $(".total_amount").html(result['total_amount']);
        $(".receipt_title").html(result['title']);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('invoice_class')){
    $("body").addClass("loading");  
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
    $("body").addClass("loading");  
    var htmlcontent = $("#letterpad_modal").html();
    var inv_no = $("#invoice_number_pdf").val();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no},
      type:"post",
      success: function(result)
      {
        $("body").removeClass("loading");  
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass('print_pdf'))
  {
    $("body").addClass("loading");  
    var htmlcontent = $("#letterpad_modal").html();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
      data:{htmlcontent:htmlcontent},
      type:"post",
      success: function(result)
      {
        $("body").removeClass("loading");  
        $("#pdfDocument").attr("src","<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
        printPdf("<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
      }
    });
  }
  if($(e.target).hasClass('stmt_funcs'))
  {
    $(".statement_functions_modal").modal('show');
  }
  if($(e.target).hasClass('build_all'))
  {
    var select_month=$(".select_months_in_stmt_funcs option:selected").text();
    var r = confirm("You are about to build all the statements for all clients, existing statements will be over written. Do you wish to continue?");
		if(r)
		{
      var month = select_month;
      $("body").addClass("loading_build");
      var async_request=[];
      var responses=[];
      setupLoop();      
    }
  }
  if($(e.target).hasClass('delete_all'))
  {
    var select_month=$(".select_months_in_stmt_funcs option:selected").text();
    var r = confirm("You are about to Delete All the Statements for "+select_month+", Do you wish to continue?");
		if(r)
		{      
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/deleteall_statement'); ?>",
        type:"post",
        data:{month:select_month},
        success:function(result)
        {
          alert("Deleted Successfully!");
          setTimeout(function() {
            $(".statement_functions_modal").modal('hide');
            $(".load_values").click();
            // $(".build_attach_file_"+result).each(function(index, value)
            // {
            //   var client_id = $(this).attr("data-client");
            //   var month_year = $(this).attr("data-element");
            //   var opening_bal = $(this).attr("data-balance");

            //   $(this).parents(".client_value_tr").find(".build_statement_td").html('<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'+client_id+'" data-element="'+month_year+'" data-balance="'+opening_bal+'">Build</a>');
            // });

            $("body").removeClass("loading");
            //$("#load_values").click();
          },1000);
        }
      })
    }
  }
  if($(e.target).hasClass('delete_bg1'))
  {
    var r = confirm("Are you sure to delete page 1 background image?");
    if(r){
      $("body").addClass("loading");  
      $.ajax({
        url:"<?php echo URL::to('user/delete_bg'); ?>",
        data:{'img_type':'bg1'},
        type:"post",
        success: function(result)
        {
          $("body").removeClass("loading");  
          $(".delete_bg1").remove();
          $(".a_delete_bg1").remove();
          $(".h_delete_bg1").remove();
        }
      });
    }
  }
  if($(e.target).hasClass('delete_bg2'))
  {
    var r = confirm("Are you sure to delete subsequent page background image?");
    if(r){
    $("body").addClass("loading");  
      $.ajax({
        url:"<?php echo URL::to('user/delete_bg'); ?>",
        data:{'img_type':'bg2'},
        type:"post",
        success: function(result)
        {
          $("body").removeClass("loading");  
          $(".delete_bg2").remove();
          $(".a_delete_bg2").remove();
          $(".h_delete_bg2").remove();
        }
      });
    }
  }
})
function nextStmtAllBuild(month,chunk){  
  $.ajax({
    url:"<?php echo URL::to('user/buildall_statement'); ?>",
    type:"post",
    data:{month:month,data:chunk,limit:100},
    dataType:"json",
    success:function(result)
    {
      $.each(result, function( index, value ) {
        $(".client_value_tr_"+result[index]['client_id']).find(".build_statement_td_"+result[index]['month_year']).html(result[index]['link']);
      });
      setupLoop();
    }
  });
}
 function setupLoop()
 {
  // var total_clients=$(".client_tr").length;;
  // var loop=Math.ceil(total_clients/100);
  // var select_month=$(".select_months_in_stmt_funcs option:selected").text();
  // var month = select_month;
  // $("#count_first").html(query_begin);
  // $("#count_last").html(total_clients);
  // if(temp<=loop){
  //   nextStmtAllBuild(month,query_begin);
  //   temp++;
  // }
  // else{
  //   $("body").removeClass("loading_content");
  //   alert("Statement Built Successfully!");
  //   setTimeout(function() {
  //     $(".statement_functions_modal").modal('hide');
  //     query_begin=0;
  //     temp=1;
  //     $("#load_values").click();
  //   },1000);
  // }
    var total_clients=$("#statement_client_tbody tr").length;
    var loop=Math.ceil(total_clients/100);
    var select_month=$(".select_months_in_stmt_funcs").val();
    var format_into_date=new Date(select_month);
    var mon=format_into_date.getMonth()+1;
    var month = ((mon<10)?'0'+mon:mon)+'-'+format_into_date.getFullYear();
    // var _list=[];
    $.each($("#statement_client_tbody tr"), function(){
      var client_id = $(this).data('element');
      var opening_bal = $(".client_value_tr_"+client_id).find(".build_statement_td_"+month).find("a").data('balance');
      if(opening_bal==undefined){
        opening_bal=0.00;
      }
      var tempval={'client_id':client_id,"opening_bal":opening_bal};
      _list.push(tempval);
    });
    // console.log(_list);

    if(query_begin > total_clients)
    {
      query_begin = query_begin - 100;
    }
    
    $("#build_first").html(query_begin);
    $("#build_last").html(total_clients);


    const chunkSize = 100; var arrayOfArrays = [];
    var chunk = _list.slice(query_begin, chunkSize);
    for (var i=0; i<_list.length; i+=chunkSize) {
        arrayOfArrays.push(_list.slice(i,i+chunkSize));
    }

    query_begin=query_begin+100;
    // console.log(arrayOfArrays);
    if(temp<=loop){
      nextStmtAllBuild(month, arrayOfArrays[temp]);
      temp++;
    }
    else{
      $("body").removeClass("loading_build");
      alert("Statement Built Successfully!");
      setTimeout(function() {
        $(".statement_functions_modal").modal('hide');
        query_begin=0;
        temp=0;
        _list=[];
        //$("#load_values").click();
      },1000);
    }
 }
</script>

<script type="text/javascript">

/* @Note: not sure e.pageX will work in IE8 */
(function(window){
  
  /* A full compatability script from MDN: */
  var supportPageOffset = window.pageXOffset !== undefined;
  var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
 
  /* Set up some variables  */
  
  var headerfixed = document.getElementById("headerfixed"); 
  /* Add an event to the window.onscroll event */
  window.addEventListener("scroll", function(e) {  
    
    /* A full compatability script from MDN for gathering the x and y values of scroll: */
    var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
 
    
    headerfixed.style.top = -y + 84 + "px";
  });
  
})(window);
<?php 
if(Session::get('message')){
?>
  alert("Uploaded image dimension is wrong");
<?php
}
?>
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
              $(".statement_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Statement Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
            $(".statement_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Statement Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
