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
<link rel="stylesheet" href="<?php echo URL::to('public/assets/css/easypayroll.css'); ?>">
<style>
  #selected_invoice_expand tbody tr td{
  padding:0px 8px !important;
  border-top: 0px;
}
.col-lg-1 {
  width: 10.333%;
}
.col-md-1 {
  width: 10.333%;
}
#edit_invoice_expand tbody tr td{
  padding:0px 8px !important;
  border-top: 0px;
}

#edit_invoice_expand thead tr th{
  border-bottom: 0px;
}

 #selected_invoice_expand tbody .empty_line_row{
  height:40px;
  border-top: 0px;
  border-bottom: 0px;
}
 #edit_invoice_expand tbody .empty_line_row{
  height:40px;
  border-top: 0px;
  border-bottom: 0px;
}
 .display_table_expand tbody .empty_line_row{
  height:25px;
}
.header_info_title{
  width:75px;
  margin-bottom: 0px !important;
}
.header_info_to_title{
  width:30px;
  margin-bottom: 0px !important;
}

.footer_info_title{
  margin-bottom: 0px !important;
  height:35px !important;
  font-weight: 700 !important;
}
.right{
  text-align: right;
}
.right_start{
  text-align: right;
}
.input-sm{
  font-size:14px;
}
#settings_table tbody tr td{
height: 44px;
}
#settings2_table tbody tr td {
  height: 44px;
}
#settings2_table thead tr th {
  height: 44px;
}
.prin_pdf{
  float:left;
  width:99%;
  margin-top:8px;
  text-align: left;
}
#colorbox{
  z-index:999999999999;
}
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}
</script>
<!-- <div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false" data-backdrop="static">
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
</div> -->
<div class="modal fade invoice_lines_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document" style="width: 42%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a href="javascript:" class="edit_invoice_lines common_black_button" style="float: right;margin-right: 19px;margin-top: 11px;">Edit Invoice Lines</a>
        <h4 class="modal-title" id="myModalLabel">Invoice Detail Lines for Invoice Number: <spam class="selected_invoice_number"></spam></h4>
      </div>
      <div class="modal-body" id="">
          <div class="col-md-12" id="invoice_lines_tbody">

          </div>
          <div class="col-md-12" id="edit_invoice_lines_tbody" style="display: none;max-height:600px;overflow-y: scroll;">

          </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          <input type="hidden" name="hidden_invoice_no" id="hidden_invoice_no" value="">
          <input type="button" class="common_black_button save_editted_invoice_lines" id="save_editted_invoice_lines" value="Update Details" style="float:right;display:none">
      </div>
    </div>
  </div>
</div>
<div class="modal fade print_invoice_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:20%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Print Invoice</h4>
      </div>
      <div class="modal-body" id="">
          <a href="javascript:" class="common_black_button prin_pdf print_invoice_with_no_background">Print Invoice with No Background</a>
          <a href="javascript:" class="common_black_button prin_pdf print_invoice_with_background">Print Invoice with Background</a>
          <a href="javascript:" class="common_black_button prin_pdf print_invoice_pdf_with_no_background">Print Invoice PDF with No Background</a>
          <a href="javascript:" class="common_black_button prin_pdf print_invoice_pdf_with_background">Print Invoice PDF with Background</a>
          <a href="javascript:" class="common_black_button prin_pdf email_invoice">Email Invoice</a>
      </div>
      <div class="modal-footer" style="clear: both;">
          
      </div>
    </div>
  </div>
</div>
<div class="modal fade build_invoice_settings_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document" style="width: 50%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Build Invoice Settings</h4>
      </div>
      <div class="modal-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="invoiceemailsettings-tab" data-toggle="tab" href="#invoiceemailsettingstab" role="tab" aria-controls="invoiceemailsettingstab" aria-selected="false">Email Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="placeholdsettings-tab" data-toggle="tab" href="#placeholdsettingstab" role="tab" aria-controls="placeholdsettingstab" aria-selected="false">Placehold Settings</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="invoiceemailsettingstab" role="tabpanel" aria-labelledby="invoiceemailsettings-tab">
                <div class="admin_content_section" style="margin-top:20px">  
                  <form action="<?php echo URL::to('user/update_invoice_email_settings'); ?>" method="post" id="update_invoice_email_settings">
                      <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                      <?php
                      $invoice_settings = DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->first();

                      if($invoice_settings->email_header_url == '') {
                      $default_image = DB::table("email_header_images")->first();
                      if($default_image->url == "") {
                        $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                      }
                      else{
                        $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                      }
                      }
                      else {
                      $image_url = URL::to($invoice_settings->email_header_url.'/'.$invoice_settings->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="invoice_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="invoice_email_header_img_btn" class="common_black_button invoice_email_header_img_btn" value="Browse">

                      <div class="col-md-12 padding_00" style="margin-top:20px">
                      <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Subject:</spam>
                      <?php
                      $checked_subject = '';
                      if($invoice_settings->include_practice == 1) {
                        $checked_subject = 'checked';
                      }
                      ?>
                      <input type="checkbox" name="invoice_include_practice" class="invoice_include_practice" id="invoice_include_practice" value="1" <?php echo $checked_subject; ?>> <label for="invoice_include_practice" style="width:400px;margin-left:20px;margin-right:20px">Include Practice Name in Subject</label>
                      </div>

                      <h4 style="margin-top:70px">Invoice CC Email ID</h4>
                      <input id="validation-cc-email"
                           class="form-control"
                           placeholder="Enter Invoice CC Email ID"
                           value="<?php echo $invoice_settings->invoice_cc_email; ?>"
                           name="invoice_cc_email"
                           type="text"
                           required> 
                      <h4 style="margin-top:25px">Email Signature :</h4>
                      <textarea class="form-control input-sm" id="editor_inv_review"  name="user_signature" style="height:100px"><?php echo $invoice_settings->email_signature; ?></textarea>
                      
                      <div class="col-md-12" style="text-align:right; margin-top:20px">
                          <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
                      </div>
                      @csrf
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="placeholdsettingstab" role="tabpanel" aria-labelledby="placeholdsettings-tab">
                  <?php
                  $settings_details = DB::table('settings')->where('source','build_invoice')->where('practice_code',Session::get('user_practice_code'))->first();
                  if($settings_details)
                  {
                    $settings = unserialize($settings_details->settings);

                    $offset_lines = ($settings['offset_lines'] != "") ? $settings['offset_lines'] : 6;
                    $plus_invoice_heading = ($settings['plus_invoice_heading'] != "") ? $settings['plus_invoice_heading'] : 'Invoice';
                    $minus_invoice_heading = ($settings['minus_invoice_heading'] != "") ? $settings['minus_invoice_heading'] : 'Credit Note';
                    $suboffset_lines = ($settings['suboffset_lines'] != "") ? $settings['suboffset_lines'] : 7;
                    $iban_field = ($settings['iban_field'] != "") ? $settings['iban_field'] : '5465623131';
                    $bic_field = ($settings['bic_field'] != "") ? $settings['bic_field'] : '545JHJDJ4544';
                    $first_invoice_number = ($settings['first_invoice_number'] != "") ? $settings['first_invoice_number'] : '';
                    $left_margin = ($settings['left_margin'] != "") ? $settings['left_margin'] : '95';
                    $top_margin = ($settings['top_margin'] != "") ? $settings['top_margin'] : '200';

                    $inv_to_text = ($settings['inv_to_text'] != "") ? $settings['inv_to_text'] : 'To';
                    $inv_date_location_text = ($settings['inv_date_location_text'] != "") ? $settings['inv_date_location_text'] : 'Date';
                    $inv_iban_text = ($settings['inv_iban_text'] != "") ? $settings['inv_iban_text'] : 'IBAN';
                    $inv_bic_text = ($settings['inv_bic_text'] != "") ? $settings['inv_bic_text'] : 'BIC';
                    $inv_no_text = ($settings['inv_no_text'] != "") ? $settings['inv_no_text'] : 'Invoice';
                    $client_id_text = ($settings['client_id_text'] != "") ? $settings['client_id_text'] : 'Client ID';
                    $net_text = ($settings['net_text'] != "") ? $settings['net_text'] : 'Net';
                    $vat_text = ($settings['vat_text'] != "") ? $settings['vat_text'] : 'VAT';
                    $gross_text = ($settings['gross_text'] != "") ? $settings['gross_text'] : 'Gross';

                    $inv_to_offset = ($settings['inv_to_offset'] != "") ? $settings['inv_to_offset'] : 1;
                    $client_id_offset = ($settings['client_id_offset'] != "") ? $settings['client_id_offset'] : 1;
                    $inv_no_offset = ($settings['inv_no_offset'] != "") ? $settings['inv_no_offset'] : 2;
                    $inv_date_location_offset = ($settings['inv_date_location_offset'] != "") ? $settings['inv_date_location_offset'] : 3;
                    $inv_iban_offset = ($settings['inv_iban_offset'] != "") ? $settings['inv_iban_offset'] : 4;
                    $inv_bic_offset = ($settings['inv_bic_offset'] != "") ? $settings['inv_bic_offset'] : 5;
                    $net_offset = ($settings['net_offset'] != "") ? $settings['net_offset'] : 32;
                    $vat_offset = ($settings['vat_offset'] != "") ? $settings['vat_offset'] : 33;
                    $gross_offset = ($settings['gross_offset'] != "") ? $settings['gross_offset'] : 34;

                    $inv_to_left_offset = ($settings['inv_to_left_offset'] != "") ? $settings['inv_to_left_offset'] : 1;
                    $client_id_left_offset = ($settings['client_id_left_offset'] != "") ? $settings['client_id_left_offset'] : 4;
                    $inv_no_left_offset = ($settings['inv_no_left_offset'] != "") ? $settings['inv_no_left_offset'] : 4;
                    $inv_date_location_left_offset = ($settings['inv_date_location_left_offset'] != "") ? $settings['inv_date_location_left_offset'] : 4;
                    $inv_iban_left_offset = ($settings['inv_iban_left_offset'] != "") ? $settings['inv_iban_left_offset'] : 4;
                    $inv_bic_left_offset = ($settings['inv_bic_left_offset'] != "") ? $settings['inv_bic_left_offset'] : 4;
                    $net_left_offset = ($settings['net_left_offset'] != "") ? $settings['net_left_offset'] : 4;
                    $vat_left_offset = ($settings['vat_left_offset'] != "") ? $settings['vat_left_offset'] : 4;
                    $gross_left_offset = ($settings['gross_left_offset'] != "") ? $settings['gross_left_offset'] : 4;
                  }
                  else{
                    $offset_lines = 6;
                    $plus_invoice_heading = 'Invoice';
                    $minus_invoice_heading = 'Credit Note';
                    $suboffset_lines = 7;
                    $iban_field = '5465623131';
                    $bic_field = '545JHJDJ4544';
                    $first_invoice_number = '';
                    $left_margin = '95';
                    $top_margin = '200';

                    $inv_to_text = 'To';
                    $inv_date_location_text = 'Date';
                    $inv_iban_text = 'IBAN';
                    $inv_bic_text = 'BIC';
                    $inv_no_text = 'Invoice';
                    $client_id_text = 'Client ID';
                    $net_text = 'Net';
                    $vat_text = 'VAT';
                    $gross_text = 'Gross';

                    $inv_to_offset = 1;
                    $client_id_offset = 1;
                    $inv_no_offset = 2;
                    $inv_date_location_offset = 3;
                    $inv_iban_offset = 4;
                    $inv_bic_offset = 5;
                    $net_offset = 32;
                    $vat_offset = 33;
                    $gross_offset = 34;

                    $inv_to_left_offset = 1;
                    $client_id_left_offset = 4;
                    $inv_no_left_offset = 4;
                    $inv_date_location_left_offset = 4;
                    $inv_iban_left_offset = 4;
                    $inv_bic_left_offset = 4;
                    $net_left_offset = 4;
                    $vat_left_offset = 4;
                    $gross_left_offset = 4;
                  }
                  ?>
                  <div class="col-md-5">
                    <table style="width:100%" id="settings_table">
                        <tr>
                            <td style="width: 52%;"><strong>Top Margin (In Pixels):</strong></td>
                            <td style="width:35%"><input type="text" name="top_margin" class="form-control top_margin" value="<?php echo $top_margin; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                            <td><strong>Left Margin (In Pixels):</strong></td>
                            <td><input type="text" name="left_margin" class="form-control left_margin" value="<?php echo $left_margin; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                            <td><strong>Number of offset lines:</strong></td>
                            <td><input type="text" name="offset_lines" class="form-control offset_lines" value="<?php echo $offset_lines; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                            <td><strong>Number of Sub offset lines:</strong></td>
                            <td><input type="text" name="suboffset_lines" class="form-control suboffset_lines" value="<?php echo $suboffset_lines; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                            <td><strong>+Invoice Heading:</strong></td>
                            <td><input type="text" name="plus_invoice_heading" class="form-control plus_invoice_heading" value="<?php echo $plus_invoice_heading; ?>"></td>
                        </tr>
                        <tr>
                            <td><strong>-Invoice Heading:</strong></td>
                            <td><input type="text" name="minus_invoice_heading" class="form-control minus_invoice_heading" value="<?php echo $minus_invoice_heading; ?>"></td>
                        </tr>
                        <tr>
                            <td><strong>IBAN:</strong></td>
                            <td><input type="text" name="iban_field" class="form-control iban_field" value="<?php echo $iban_field; ?>"></td>
                        </tr>
                        <tr>
                            <td><strong>BIC:</strong></td>
                            <td><input type="text" name="bic_field" class="form-control bic_field" value="<?php echo $bic_field; ?>"></td>
                        </tr>
                        <tr>
                            <td><strong>First Invoice Number:</strong></td>
                            <td><input type="text" name="first_invoice_number" class="form-control first_invoice_number" value="<?php echo $first_invoice_number; ?>"></td>
                        </tr>
                    </table>
                  </div>
                  <div class="col-md-7">
                    <table style="width:100%" id="settings2_table">
                      <thead>
                        <th style="width: 36%;">Items</th>
                        <th style="width:15%">Item <br/>Text</th>
                        <th style="width:16%">Offset <br/>Lines</th>
                        <th style="width:18%">Left Margin <br/>Offset</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td><strong>Invoice To Location:</strong></td>
                          <td><input type="text" name="inv_to_text" class="form-control inv_to_text" value="<?php echo $inv_to_text; ?>"></td>
                          <td><input type="text" name="inv_to_offset" class="form-control inv_to_offset" value="<?php echo $inv_to_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="inv_to_left_offset" class="form-control inv_to_left_offset" value="<?php echo $inv_to_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>Invoice Date Location:</strong></td>
                          <td><input type="text" name="inv_date_location_text" class="form-control inv_date_location_text" value="<?php echo $inv_date_location_text; ?>"></td>
                          <td><input type="text" name="inv_date_location_offset" class="form-control inv_date_location_offset" value="<?php echo $inv_date_location_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="inv_date_location_left_offset" class="form-control inv_date_location_left_offset" value="<?php echo $inv_date_location_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>IBAN Location:</strong></td>
                          <td><input type="text" name="inv_iban_text" class="form-control inv_iban_text" value="<?php echo $inv_iban_text; ?>"></td>
                          <td><input type="text" name="inv_iban_offset" class="form-control inv_iban_offset" value="<?php echo $inv_iban_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="inv_iban_left_offset" class="form-control inv_iban_left_offset" value="<?php echo $inv_iban_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>BIC Location:</strong></td>
                          <td><input type="text" name="inv_bic_text" class="form-control inv_bic_text" value="<?php echo $inv_bic_text; ?>"></td>
                          <td><input type="text" name="inv_bic_offset" class="form-control inv_bic_offset" value="<?php echo $inv_bic_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="inv_bic_left_offset" class="form-control inv_bic_left_offset" value="<?php echo $inv_bic_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>Invoice Number Location:</strong></td>
                          <td><input type="text" name="inv_no_text" class="form-control inv_no_text" value="<?php echo $inv_no_text; ?>"></td>
                          <td><input type="text" name="inv_no_offset" class="form-control inv_no_offset" value="<?php echo $inv_no_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="inv_no_left_offset" class="form-control inv_no_left_offset" value="<?php echo $inv_no_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>Client ID Location:</strong></td>
                          <td><input type="text" name="client_id_text" class="form-control client_id_text" value="<?php echo $client_id_text; ?>"></td>
                          <td><input type="text" name="client_id_offset" class="form-control client_id_offset" value="<?php echo $client_id_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="client_id_left_offset" class="form-control client_id_left_offset" value="<?php echo $client_id_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>Net Location:</strong></td>
                          <td><input type="text" name="net_text" class="form-control net_text" value="<?php echo $net_text; ?>"></td>
                          <td><input type="text" name="net_offset" class="form-control net_offset" value="<?php echo $net_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="net_left_offset" class="form-control net_left_offset" value="<?php echo $net_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>VAT Location:</strong></td>
                          <td><input type="text" name="vat_text" class="form-control vat_text" value="<?php echo $vat_text; ?>"></td>
                          <td><input type="text" name="vat_offset" class="form-control vat_offset" value="<?php echo $vat_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="vat_left_offset" class="form-control vat_left_offset" value="<?php echo $vat_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                        <tr>
                          <td><strong>Gross Location:</strong></td>
                          <td><input type="text" name="gross_text" class="form-control gross_text" value="<?php echo $gross_text; ?>"></td>
                          <td><input type="text" name="gross_offset" class="form-control gross_offset" value="<?php echo $gross_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                          <td><input type="text" name="gross_left_offset" class="form-control gross_left_offset" value="<?php echo $gross_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <br/>
                  <input type="button" class="common_black_button save_build_invoice_settings" id="save_build_invoice_settings" value="Save Settings" >
              </div>
          </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          
      </div>
    </div>
  </div>
</div>
<div class="modal fade invoice_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_invoice_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailInvoice" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<?php 
  $invoice_settings = DB::table('invoice_system_settings')->where('practice_code', Session::get('user_practice_code'))->first();
  $admin_cc = $invoice_settings->invoice_cc_email;
?>
<div class="modal fade email_invoice_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Invoice</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="from_user" id="from_user" class="form-control" value="" required>
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
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_unsent" class="form-control input-sm cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="" required>
            </div>
          </div>
          <?php
          if($invoice_settings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($invoice_settings->email_header_url.'/'.$invoice_settings->email_header_filename);
          }
          ?>
          <div class="row" style="margin-top:10px">
            <div class="col-md-2">
              <label style="margin-top: 9px;">Header Image:</label>
            </div>
            <div class="col-md-10 padding_00">
              <img src="<?php echo $image_url; ?>" style="width:200px;margin-left:20px;margin-right:20px">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment:</label>
            </div>
            <div class="col-md-12">
              <img class="pdf_image" src="<?php echo URL::to('public/assets/images/pdf.png'); ?>" style="width:30px">
              <spam class="pdf_name"></spam>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button email_printed_invoice" value="Email Invoice">
      </div>
    </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  	<div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title">Build Invoice <a href="javascript:" class="fa fa-cog build_invoice_settings common_black_button" title="Build Invoice Settings" style="float:right;margin-right:20px"></a></h4>
        <div class="col-lg-6" style="padding-right: 0px;width:57%">
        	<div class="col-lg-1 padding_00">
    				<h5 style="font-weight: 600">Search Client: </h5>
    			</div>
    			<div class="col-lg-4" style="padding: 0px;">
    				<div class="form-group">
    				    <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500; width:90%; display:inline;" value="" required />     
                <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />                      
    				    <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="" name="client_id">
    				</div>                  
    			</div>
    			<div class="col-md-1" style="padding: 0px">
    				<input type="button" name="load_client_review" class="common_black_button load_client_review" value="Load">
    			</div>
    			<div class="col-md-12 client_details_div" style="padding:0px">
    			</div>
          <div class="col-lg-5 issued_invoice_main_div padding_00" style="display:none">
            <h3>Issued Invoice List: </h3>
            <div class="col-md-12 issued_invoice_div padding_00" style="max-height: 950px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
            </div>
          </div>
          <div class="col-md-7 selected_invoice_main_div padding_00" style="display:none">
            <h3>Invoice Detail Lines for Invoice Number: <spam class="selected_invoice_number"></spam> <a href="javascript:" class="fa fa-expand expand_invoice_lines" title="Edit Invoice Detail Lines" style="float:right"></a></h3>
            <div class="col-md-12 selected_invoice_div padding_00" style="max-height: 400px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
              <table class="table" id="selected_invoice_expand">
                <thead>
                  <tr>
                  <th>Invoice Lines</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  </tr>
                </thead>
                <tbody id="selected_invoice_tbody">
                  <tr>             
                    <td class="class_row_td left">No Data Found</td>
                    <td class="class_row_td left_corner"></td>
                    <td class="class_row_td right_start"></td>
                    <td class="class_row_td right"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-6" style="margin-top:-16px;width:43%">
          <div class="col-md-12 invoice_display_main_div padding_00" id="invoice_display_main_div" style="display:none">
            <h3 style="width:845px" class="invoice_display_h3">Invoice Display: <a href="javascript:" class="common_black_button print_invoice_btn" style="float: right;margin-left: 22px;">Print Invoice</a> <input type="checkbox" name="show_grid_lines" class="show_grid_lines" id="show_grid_lines"><label for="show_grid_lines" style="font-size:16px;font-weight:500;float:right;margin-top: 7px;">Show Grid Lines</label> </h3>
              <div class="invoice_display_sub_div" style="height:1235px;">
                <div class="display_letterpad_modal" style="width:900px;height:100%;float: left; background:url('<?php echo URL::to('public/assets/invoice_letterpad.jpg');?>') no-repeat;background-size: cover;overflow: hidden;">
                
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
$(document).ready(function() {
  CKEDITOR.replace('editor_inv_review',
     {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
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
		minLength: 1,
		select: function( event, ui ) {
			$("#client_search_hidden_infile").val(ui.item.id);
      $("#hidden_client_id").val(ui.item.id);
		}
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
$(window).click(function(e) {
  if($(e.target).hasClass('invoice_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Invoice Email Header Image?");
    if(r) {
      $(".invoice_change_header_image").modal("show");
      Dropzone.forElement("#ImageUploadEmailInvoice").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
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
  if($(e.target).hasClass('print_invoice_btn'))
  {
    $(".print_invoice_overlay").modal("show");
  }
  if($(e.target).hasClass("print_invoice_with_background"))
  {
    var style = '<style>@media print {* {-webkit-print-color-adjust: exact !important;color-adjust: exact !important;}.right{text-align: right;}.right_start{text-align: right;}.header_info_title{width:75px;margin-bottom: 0px !important;}.header_info_to_title{width:30px;margin-bottom: 0px !important;}.footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;}}</style>';
    $(".invoice_display_h3").hide();
    var divToPrint=document.getElementById('invoice_display_main_div');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+' '+style+'</body></html>');
    newWin.document.close();
    //newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }
    setTimeout(function(){newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }},1000);
  }
  if($(e.target).hasClass("print_invoice_with_no_background"))
  {
    var style = '<style>@media print {* {-webkit-print-color-adjust: exact !important;color-adjust: exact !important;}.right{text-align: right;}.right_start{text-align: right;}.header_info_title{width:75px;margin-bottom: 0px !important;}.header_info_to_title{width:30px;margin-bottom: 0px !important;}.footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;} .display_letterpad_modal{background:#fff !important}}</style>';
    $(".invoice_display_h3").hide();
    var divToPrint=document.getElementById('invoice_display_main_div');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+' '+style+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }},1000);
  }
  if($(e.target).hasClass("print_invoice_pdf_with_no_background"))
  {
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"1"},
      dataType:"json",
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result['filename'],result['filename']);
      }
    });
  }
  if($(e.target).hasClass("print_invoice_pdf_with_background"))
  {
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"2"},
      dataType:"json",
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result['filename'],result['filename']);
      }
    });
  }
  if($(e.target).hasClass("email_invoice"))
  {
    if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"2"},
      dataType:"json",
      type:"post",
      success: function(result)
      {
         CKEDITOR.replace('editor',
         {
          height: '150px',
         });
         CKEDITOR.instances['editor'].setData(result['signature']);
          $(".email_invoice_overlay").modal("show");
          $(".subject_unsent").val(result['subject']);
          $(".pdf_name").html(result['filename']);
      }
    });
  }
  if($(e.target).hasClass('email_printed_invoice'))
  {
    var from = $("#from_user").val();
    var to = $("#to_user").val();
    var cc = $(".cc_unsent").val();
    var subject = $(".subject_unsent").val();
    var content = CKEDITOR.instances['editor'].getData();
    var attachment = $(".pdf_name").html();
    var client_id = $("#client_search_hidden_infile").val();

    if(from == "") { alert("Please select the From User to send the email."); return false; }
    else if(to == "") { alert("Please enter the To User to send the email."); return false; }
    else if(cc == "") { alert("Please enter the CC to send the email."); return false; }
    else if(subject == "") { alert("Please enter the Subject to send the email."); return false; }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/email_invoice_submit'); ?>",
        type:"post",
        data:{from:from,to:to,subject:subject,cc:cc,content:content,attachment:attachment,client_id:client_id},
        success:function(result)
        {
          $(".email_invoice_overlay").modal("hide");
          $(".print_invoice_overlay").modal("hide");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Mail Sent Successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
        }
      })
    }
  }
  if($(e.target).hasClass('save_build_invoice_settings'))
  {
    var offset_lines = $(".offset_lines").val();
    var plus_invoice_heading = $(".plus_invoice_heading").val();
    var minus_invoice_heading = $(".minus_invoice_heading").val();
    var suboffset_lines = $(".suboffset_lines").val();
    var iban_field = $(".iban_field").val();
    var bic_field = $(".bic_field").val();
    var first_invoice_number = $(".first_invoice_number").val();
    var left_margin = $(".left_margin").val();
    var top_margin = $(".top_margin").val();

    var inv_to_text = $(".inv_to_text").val();
    var inv_date_location_text = $(".inv_date_location_text").val();
    var inv_iban_text = $(".inv_iban_text").val();
    var inv_bic_text = $(".inv_bic_text").val();
    var inv_no_text = $(".inv_no_text").val();
    var client_id_text = $(".client_id_text").val();
    var net_text = $(".net_text").val();
    var vat_text = $(".vat_text").val();
    var gross_text = $(".gross_text").val();

    var inv_to_offset = $(".inv_to_offset").val();
    var client_id_offset = $(".client_id_offset").val();
    var inv_no_offset = $(".inv_no_offset").val();
    var inv_date_location_offset = $(".inv_date_location_offset").val();
    var inv_iban_offset = $(".inv_iban_offset").val();
    var inv_bic_offset = $(".inv_bic_offset").val();
    var net_offset = $(".net_offset").val();
    var vat_offset = $(".vat_offset").val();
    var gross_offset = $(".gross_offset").val();

    var inv_to_left_offset = $(".inv_to_left_offset").val();
    var client_id_left_offset = $(".client_id_left_offset").val();
    var inv_no_left_offset = $(".inv_no_left_offset").val();
    var inv_date_location_left_offset = $(".inv_date_location_left_offset").val();
    var inv_iban_left_offset = $(".inv_iban_left_offset").val();
    var inv_bic_left_offset = $(".inv_bic_left_offset").val();
    var net_left_offset = $(".net_left_offset").val();
    var vat_left_offset = $(".vat_left_offset").val();
    var gross_left_offset = $(".gross_left_offset").val();

    if(offset_lines == "") { alert("Please enter the offset_lines"); return false; }
    if(plus_invoice_heading == "") { alert("Please enter the plus_invoice_heading"); return false; }
    if(minus_invoice_heading == "") { alert("Please enter the minus_invoice_heading"); return false; }
    if(suboffset_lines == "") { alert("Please enter the suboffset_lines"); return false; }
    if(iban_field == "") { alert("Please enter the iban_field"); return false; }
    if(bic_field == "") { alert("Please enter the bic_field"); return false; }
    if(first_invoice_number == "") { alert("Please enter the first_invoice_number"); return false; }
    if(left_margin == "") { alert("Please enter the left_margin"); return false; }
    if(top_margin == "") { alert("Please enter the top_margin"); return false; }
    if(inv_to_text == "") { alert("Please enter the inv_to_text"); return false; }
    if(inv_date_location_text == "") { alert("Please enter the inv_date_location_text"); return false; }
    if(inv_iban_text == "") { alert("Please enter the inv_iban_text"); return false; }
    if(inv_bic_text == "") { alert("Please enter the inv_bic_text"); return false; }
    if(inv_no_text == "") { alert("Please enter the inv_no_text"); return false; }
    if(client_id_text == "") { alert("Please enter the client_id_text"); return false; }
    if(net_text == "") { alert("Please enter the net_text"); return false; }
    if(vat_text == "") { alert("Please enter the vat_text"); return false; }
    if(gross_text == "") { alert("Please enter the gross_text"); return false; }
    if(inv_to_offset == "") { alert("Please enter the inv_to_offset"); return false; }
    if(client_id_offset == "") { alert("Please enter the client_id_offset"); return false; }
    if(inv_no_offset == "") { alert("Please enter the inv_no_offset"); return false; }
    if(inv_date_location_offset == "") { alert("Please enter the inv_date_location_offset"); return false; }
    if(inv_iban_offset == "") { alert("Please enter the inv_iban_offset"); return false; }
    if(inv_bic_offset == "") { alert("Please enter the inv_bic_offset"); return false; }
    if(net_offset == "") { alert("Please enter the net_offset"); return false; }
    if(vat_offset == "") { alert("Please enter the vat_offset"); return false; }
    if(gross_offset == "") { alert("Please enter the gross_offset"); return false; }
    if(inv_to_left_offset == "") { alert("Please enter the inv_to_left_offset"); return false; }
    if(client_id_left_offset == "") { alert("Please enter the client_id_left_offset"); return false; }
    if(inv_no_left_offset == "") { alert("Please enter the inv_no_left_offset"); return false; }
    if(inv_date_location_left_offset == "") { alert("Please enter the inv_date_location_left_offset"); return false; }
    if(inv_iban_left_offset == "") { alert("Please enter the inv_iban_left_offset"); return false; }
    if(inv_bic_left_offset == "") { alert("Please enter the inv_bic_left_offset"); return false; }
    if(net_left_offset == "") { alert("Please enter the net_left_offset"); return false; }
    if(vat_left_offset == "") { alert("Please enter the vat_left_offset"); return false; }
    if(gross_left_offset == "") { alert("Please enter the gross_left_offset"); return false; }

    if(parseInt(suboffset_lines) <= parseInt(offset_lines)) { alert("The Number of Sub Offset Lines should be Greater than the Offset Lines"); return false; }
    if(parseInt(inv_to_offset) >= parseInt(offset_lines)) { alert("The Invoice To Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(client_id_offset) >= parseInt(offset_lines)) { alert("The Client ID Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_no_offset) >= parseInt(offset_lines)) { alert("The Invoice Number Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_date_location_offset) >= parseInt(offset_lines)) { alert("The Date Location Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_iban_offset) >= parseInt(offset_lines)) { alert("The IBAN Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_bic_offset) >= parseInt(offset_lines)) { alert("The BIC Offset should be Lesser than the Offset Lines"); return false; }

    var datavalues = [];

    datavalues.push({offset_lines: $(".offset_lines").val() });
    datavalues.push({plus_invoice_heading: $(".plus_invoice_heading").val() });
    datavalues.push({minus_invoice_heading: $(".minus_invoice_heading").val() });
    datavalues.push({suboffset_lines: $(".suboffset_lines").val() });
    datavalues.push({iban_field: $(".iban_field").val() });
    datavalues.push({bic_field: $(".bic_field").val() });
    datavalues.push({first_invoice_number: $(".first_invoice_number").val() });
    datavalues.push({left_margin: $(".left_margin").val() });
    datavalues.push({top_margin: $(".top_margin").val() });
    datavalues.push({inv_to_text: $(".inv_to_text").val() });
    datavalues.push({inv_date_location_text: $(".inv_date_location_text").val() });
    datavalues.push({inv_iban_text: $(".inv_iban_text").val() });
    datavalues.push({inv_bic_text: $(".inv_bic_text").val() });
    datavalues.push({inv_no_text: $(".inv_no_text").val() });
    datavalues.push({client_id_text: $(".client_id_text").val() });
    datavalues.push({net_text: $(".net_text").val() });
    datavalues.push({vat_text: $(".vat_text").val() });
    datavalues.push({gross_text: $(".gross_text").val() });
    datavalues.push({inv_to_offset: $(".inv_to_offset").val() });
    datavalues.push({client_id_offset: $(".client_id_offset").val() });
    datavalues.push({inv_no_offset: $(".inv_no_offset").val() });
    datavalues.push({inv_date_location_offset: $(".inv_date_location_offset").val() });
    datavalues.push({inv_iban_offset: $(".inv_iban_offset").val() });
    datavalues.push({inv_bic_offset: $(".inv_bic_offset").val() });
    datavalues.push({net_offset: $(".net_offset").val() });
    datavalues.push({vat_offset: $(".vat_offset").val() });
    datavalues.push({gross_offset: $(".gross_offset").val() });
    datavalues.push({inv_to_left_offset: $(".inv_to_left_offset").val() });
    datavalues.push({client_id_left_offset: $(".client_id_left_offset").val() });
    datavalues.push({inv_no_left_offset: $(".inv_no_left_offset").val() });
    datavalues.push({inv_date_location_left_offset: $(".inv_date_location_left_offset").val() });
    datavalues.push({inv_iban_left_offset: $(".inv_iban_left_offset").val() });
    datavalues.push({inv_bic_left_offset: $(".inv_bic_left_offset").val() });
    datavalues.push({net_left_offset: $(".net_left_offset").val() });
    datavalues.push({vat_left_offset: $(".vat_left_offset").val() });
    datavalues.push({gross_left_offset: $(".gross_left_offset").val() });

    $.ajax({
        type: "POST",
        url: "<?php echo URL::to('user/update_build_invoice_setings'); ?>",
        data: {datas:JSON.stringify(datavalues)},
        success: function(result){
          $(".build_invoice_settings_overlay").modal("hide");
          var countval = $(".highlight_inv").length;
          if(countval > 0){
            $(".highlight_inv").eq(0).trigger("click");
          }
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Settings have been updated successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>',fixed:true,width:"500px"});
        }
    });
  }
  if($(e.target).hasClass('build_invoice_settings'))
  {
    $(".build_invoice_settings_overlay").modal("show");
  }
  if($(e.target).hasClass('edit_invoice_lines'))
  {
    $("#invoice_lines_tbody").hide();
    $("#edit_invoice_lines_tbody").show();
    $(e.target).hide();
    $("#save_editted_invoice_lines").show();
  }
  if($(e.target).hasClass('expand_invoice_lines'))
  {
    var inv_no = $("#hidden_invoice_no").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/show_invoice_lines_for_invoice') ?>",
      type:"post",
      data:{inv_no:inv_no},
      dataType:"json",
      success:function(result){
        $("#invoice_lines_tbody").html(result['invoice_lines']);
        $("#edit_invoice_lines_tbody").html(result['edit_invoice_lines']);

        $("#edit_invoice_tbody").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($("#edit_invoice_tbody").find("tr").eq(0).hasClass('empty_line_tr')){
          $("#edit_invoice_tbody").find("tr").eq(0).detach();
        }
        if($("#edit_invoice_tbody").find("tr").last().hasClass('empty_line_tr')){
          $("#edit_invoice_tbody").find("tr").last().detach();
        }

        $("#invoice_lines_tbody").show();
        $("#edit_invoice_lines_tbody").hide();
        $("#save_editted_invoice_lines").hide();

        if(inv_no != ""){
          $(".edit_invoice_lines").show();
        }
        else{
          $(".edit_invoice_lines").hide();
        }

        $(".invoice_lines_overlay").modal("show");
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('save_editted_invoice_lines'))
  {
    var inv_no = $("#hidden_invoice_no").val();
    var datavalues = [];
    datavalues.push({f_row1: $("#f_row1").val() });
    datavalues.push({g_row2: $("#g_row2").val() });
    datavalues.push({h_row3: $("#h_row3").val() });
    datavalues.push({i_row4: $("#i_row4").val() });
    datavalues.push({j_row5: $("#j_row5").val() });
    datavalues.push({k_row6: $("#k_row6").val() });
    datavalues.push({l_row7: $("#l_row7").val() });
    datavalues.push({m_row8: $("#m_row8").val() });
    datavalues.push({n_row9: $("#n_row9").val() });
    datavalues.push({o_row10: $("#o_row10").val() });
    datavalues.push({p_row11: $("#p_row11").val() });
    datavalues.push({q_row12: $("#q_row12").val() });
    datavalues.push({r_row13: $("#r_row13").val() });
    datavalues.push({s_row14: $("#s_row14").val() });
    datavalues.push({t_row15: $("#t_row15").val() });
    datavalues.push({u_row16: $("#u_row16").val() });
    datavalues.push({v_row17: $("#v_row17").val() });
    datavalues.push({w_row18: $("#w_row18").val() });
    datavalues.push({x_row19: $("#x_row19").val() });
    datavalues.push({y_row20: $("#y_row20").val() });

    datavalues.push({z_row1: $("#z_row1").val() });
    datavalues.push({aa_row2: $("#aa_row2").val() });
    datavalues.push({ab_row3: $("#ab_row3").val() });
    datavalues.push({ac_row4: $("#ac_row4").val() });
    datavalues.push({ad_row5: $("#ad_row5").val() });
    datavalues.push({ae_row6: $("#ae_row6").val() });
    datavalues.push({af_row7: $("#af_row7").val() });
    datavalues.push({ag_row8: $("#ag_row8").val() });
    datavalues.push({ah_row9: $("#ah_row9").val() });
    datavalues.push({ai_row10: $("#ai_row10").val() });
    datavalues.push({aj_row11: $("#aj_row11").val() });
    datavalues.push({ak_row12: $("#ak_row12").val() });
    datavalues.push({al_row13: $("#al_row13").val() });
    datavalues.push({am_row14: $("#am_row14").val() });
    datavalues.push({an_row15: $("#an_row15").val() });
    datavalues.push({ao_row16: $("#ao_row16").val() });
    datavalues.push({ap_row17: $("#ap_row17").val() });
    datavalues.push({aq_row18: $("#aq_row18").val() });
    datavalues.push({ar_row19: $("#ar_row19").val() });
    datavalues.push({as_row20: $("#as_row20").val() });

    datavalues.push({at_row1: $("#at_row1").val() });
    datavalues.push({au_row2: $("#au_row2").val() });
    datavalues.push({av_row3: $("#av_row3").val() });
    datavalues.push({aw_row4: $("#aw_row4").val() });
    datavalues.push({ax_row5: $("#ax_row5").val() });
    datavalues.push({ay_row6: $("#ay_row6").val() });
    datavalues.push({az_row7: $("#az_row7").val() });
    datavalues.push({ba_row8: $("#ba_row8").val() });
    datavalues.push({bb_row9: $("#bb_row9").val() });
    datavalues.push({bc_row10: $("#bc_row10").val() });
    datavalues.push({bd_row11: $("#bd_row11").val() });
    datavalues.push({be_row12: $("#be_row12").val() });
    datavalues.push({bf_row13: $("#bf_row13").val() });
    datavalues.push({bg_row14: $("#bg_row14").val() });
    datavalues.push({bh_row15: $("#bh_row15").val() });
    datavalues.push({bi_row16: $("#bi_row16").val() });
    datavalues.push({bj_row17: $("#bj_row17").val() });
    datavalues.push({bk_row18: $("#bk_row18").val() });
    datavalues.push({bl_row19: $("#bl_row19").val() });
    datavalues.push({bm_row20: $("#bm_row20").val() });

    datavalues.push({bn_row1: $("#bn_row1").val() });
    datavalues.push({bo_row2: $("#bo_row2").val() });
    datavalues.push({bp_row3: $("#bp_row3").val() });
    datavalues.push({bq_row4: $("#bq_row4").val() });
    datavalues.push({br_row5: $("#br_row5").val() });
    datavalues.push({bs_row6: $("#bs_row6").val() });
    datavalues.push({bt_row7: $("#bt_row7").val() });
    datavalues.push({bu_row8: $("#bu_row8").val() });
    datavalues.push({bv_row9: $("#bv_row9").val() });
    datavalues.push({bw_row10: $("#bw_row10").val() });
    datavalues.push({bx_row11: $("#bx_row11").val() });
    datavalues.push({by_row12: $("#by_row12").val() });
    datavalues.push({bz_row13: $("#bz_row13").val() });
    datavalues.push({ca_row14: $("#ca_row14").val() });
    datavalues.push({cb_row15: $("#cb_row15").val() });
    datavalues.push({cc_row16: $("#cc_row16").val() });
    datavalues.push({cd_row17: $("#cd_row17").val() });
    datavalues.push({ce_row18: $("#ce_row18").val() });
    datavalues.push({cf_row19: $("#cf_row19").val() });
    datavalues.push({cg_row20: $("#cg_row20").val() });

    datavalues.push({inv_net: $("#inv_net").val() });
    datavalues.push({vat_value: $("#vat_value").val() });
    datavalues.push({gross: $("#gross").val() });

    // console.log(JSON.parse(JSON.stringify(datavalues)));
    // return false;
    $.ajax({
        type: "POST",
        url: "<?php echo URL::to('user/update_invoice_lines_for_invoice'); ?>",
        data: {datas:JSON.stringify(datavalues),inv_no:inv_no},
        success: function(result){
          $(".invoice_lines_overlay").modal("hide");
          $(".highlight_inv").eq(0).trigger("click");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Invoice Lines for the Invoice '+inv_no+' has been updated successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
        }
    });

  }
  if($(e.target).hasClass('ok_proceed'))
  {
    $.colorbox.close();
  }
  if($(e.target).hasClass('show_grid_lines'))
  {
    if($(e.target).is(":checked"))
    {
      $(".display_table_expand").find("td").css("border","1px solid #d0d0d0");
    }
    else{
      $(".display_table_expand").find("td").css("border","0px");
    }
  }
  if($(e.target).hasClass('invoice_td'))
  {
    $("#issued_invoice_tbody").find(".invoice_td").removeClass("highlight_inv");
    var inv_no = $(e.target).attr("data-element");
    $(e.target).parents("tr").find(".invoice_td").addClass("highlight_inv");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/build_issued_invoice_lines'); ?>",
      type:"post",
      data:{inv_no:inv_no},
      dataType:"json",
      success:function(result){
        $(".selected_invoice_number").html(inv_no);
        $(".selected_invoice_div").html(result['invoice_lines']);
        $("#hidden_invoice_no").val(inv_no);
        $("#selected_invoice_tbody").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($("#selected_invoice_tbody").find("tr").eq(0).hasClass('empty_line_tr')){
          $("#selected_invoice_tbody").find("tr").eq(0).detach();
        }
        if($("#selected_invoice_tbody").find("tr").last().hasClass('empty_line_tr')){
          $("#selected_invoice_tbody").find("tr").last().detach();
        }

        $(".display_letterpad_modal").html(result['display_output']);

        $(".display_table_expand").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($(".display_table_expand").find("tr").eq(0).hasClass('empty_line_tr')){
          $(".display_table_expand").find("tr").eq(0).detach();
        }
        if($(".display_table_expand").find("tr").last().hasClass('empty_line_tr')){
          $(".display_table_expand").find("tr").last().detach();
        }

        $(".selected_invoice_main_div").show();
        $(".invoice_display_main_div").show();

        $(".expand_invoice_lines").show();
          $(".print_invoice_btn").show();

        $("#show_grid_lines").prop("checked",false);
        $("body").removeClass("loading");
      }
    })
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
      $("#invoice_build_expand").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/build_invoice_client_select'); ?>",
        type:"get",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".company_name").val(result['company']);
          $(".client_details_div").html(result['client_details']);
          $(".client_email").val(result['client_email']);
          $(".company_name").css("color","#000 !important");
          $(".issued_invoice_div").html(result['invoices']);

          var emails = '';
          if(result['client_email'] != ""){
            emails = result['client_email'];
          }
          if(result['client_email2'] != "")
          {
            if(emails == ''){
              emails = result['client_email2'];
            }
            else{
              emails = emails+', '+result['client_email2'];
            }
          }
          $("#to_user").val(emails)

          $('#invoice_build_expand').DataTable({
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order:[]
          });

          $(".issued_invoice_main_div").show();

          $(".selected_invoice_main_div").show();
          $(".invoice_display_main_div").show();

          $("#selected_invoice_tbody").html('<tr><td class="class_row_td left">No Data Found</td><td class="class_row_td left_corner"></td><td class="class_row_td right_start"></td><td class="class_row_td right"></td></tr>');

          $(".expand_invoice_lines").hide();
          $(".print_invoice_btn").hide();
          $("body").removeClass("loading");
          $(".display_letterpad_modal").html('');
        }
      })
    }
	}
})

Dropzone.options.ImageUploadEmailInvoice = {
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
              Dropzone.forElement("#ImageUploadEmailInvoice").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".invoice_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".invoice_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailInvoice").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Build Invoice Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".invoice_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".invoice_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".invoice_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailInvoice").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Build Invoice Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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