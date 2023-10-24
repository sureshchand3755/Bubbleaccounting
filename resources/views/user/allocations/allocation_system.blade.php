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

.modal_load_clients {
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
body.loading_clients {
    overflow: hidden;   
}
body.loading_clients .modal_load_clients {
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


<div class="content_section" style="margin-bottom:200px">
  <div style="width: 98%; float: left; position: fixed;" id="headerfixed">
    <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Allocation System
            </h4>
    </div>
    <div class="row">
      <div class="col-md-12" style="margin-top:20px">
        <spam style="margin-top: 8px;float:left">From: </spam>
        <select name="from_month" class="from_month_values form-control" style="width:10%;margin-left:10px;float:left">
          <?php
          $current_month = date('M-Y');
          $current_monthh = date('m-Y');
          $curr_str_month = date('Y-m-01');
          $opening_month = DB::table('user_login')->first();
          $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
          $edate = strtotime($curr_str_month);
          $bdate = strtotime($opening_bal_month);
          $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
          echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'" data-timegrid="'.strtotime($opening_bal_month).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
          for($i= 1; $i<=$age; $i++)
          {
            $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
            $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
            $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
            echo '<option value="'.$datevalll.'" data-timegrid="'.strtotime($datevalll).'">'.$datevall.'</option>';
            $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
          }
          ?>
        </select>
        <spam style="margin-top: 8px;margin-left:10px;float:left">To: </spam>
        <select name="to_month" class="to_month_values form-control" style="width:10%;margin-left:10px;float:left">
          <?php
          $current_month = date('M-Y');
          $current_monthh = date('m-Y');
          $curr_str_month = date('Y-m-01');
          $opening_month = DB::table('user_login')->first();
          $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
          $edate = strtotime($curr_str_month);
          $bdate = strtotime($opening_bal_month);
          $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
          echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'" data-timegrid="'.strtotime($opening_bal_month).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
          for($i= 1; $i<=$age; $i++)
          {
            $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
            $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
            $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
            if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
            echo '<option value="'.$datevalll.'" '.$selected.' data-timegrid="'.strtotime($datevalll).'">'.$datevall.'</option>';
            $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
          }
          ?>
        </select>
        <input type="button" id="load_values" class="common_black_button load_values" value="Load Values" style="float:left">
        <input type="button" id="export_values" class="common_black_button export_values" value="Export as CSV" disabled style="float:right">
      </div>
    </div>
  </div>
    <div class="row" style="margin-top:25px;">
      <div class="col-md-12" id="load_table_clients" style="margin-top: 176px; padding: 0px;">

      </div>
      
    </div>
</div>
<div class="modal_load"></div>
  <div class="modal_load_clients" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Details and Receipts are Processed.<br/> This might take up to 2 minutes depending on the number of months to be processed. </p> </div>
  <div class="modal_load_export" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Details and Receipts are Processed in to a CSV File.</p>
    <p style="font-size:18px;font-weight: 600;">It may take upto 3 to 4 minutes to process the data in to a CSV file.</p>
  </div>
  <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<script>


$(window).click(function(e) {

  if($(e.target).hasClass('load_values'))
  {
    
    var from = $(".from_month_values").val();
    var to = $(".to_month_values").val();

    var from_timegrid = $('.from_month_values option:selected').attr("data-timegrid");
    var to_timegrid = $('.to_month_values option:selected').attr("data-timegrid");

    if(from_timegrid > to_timegrid) {
      alert("Please adjust the Date Range. To Date Should be Greater than the From Date");
    }
    else{
      $("body").addClass("loading_clients");
      $.ajax({
        url:"<?php echo URL::to('user/load_allocation_clients'); ?>",
        type:"post",
        data:{from:from,to:to},
        success:function(result)
        {
          $("#load_table_clients").html(result);
          $(".export_values").prop("disabled",false);
          $("body").removeClass("loading_clients");
        }
      })
    }
  }
  if($(e.target).hasClass('export_values'))
  {
    
    var from = $(".from_month_values").val();
    var to = $(".to_month_values").val();

    var from_timegrid = $('.from_month_values option:selected').attr("data-timegrid");
    var to_timegrid = $('.to_month_values option:selected').attr("data-timegrid");

    if(from_timegrid > to_timegrid) {
      alert("Please adjust the Date Range. To Date Should be Greater than the From Date");
    }
    else{
      $("body").addClass("loading_export");
      $.ajax({
        url:"<?php echo URL::to('user/export_allocation_clients'); ?>",
        type:"post",
        data:{from:from,to:to},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading_export");
        }
      })
    }
  }
})

</script>
@stop
