@extends('userheader')
@section('content')
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<style>
.own_table_white tr td
{
  height:70px;
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
.nav-tabs > li { width:10%;text-align: center;font-weight: 600; }
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">

<div class="content_section" style="margin-bottom:200px">
  <div style="width: 98%; float: left; position: fixed;" id="headerfixed">
    <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Client Statement
            </h4>
    </div>
    <div class="row">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item active">
          <a class="nav-link active" id="journal-listing-tab" href="javascript:">Current View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="trial-balance-tab" href="<?php echo URL::to('user/full_view_statement'); ?>">Full View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="trial-balance-tab" href="<?php echo URL::to('user/client_specific_statement'); ?>">Client Specific</a>
        </li>
      </ul>
      <div class="col-md-12">
      </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
@stop
