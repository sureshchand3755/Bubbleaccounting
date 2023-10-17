<style>
  .statement_invoice { width:28px !important; }
</style>
<div class="modal fade settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
          <form id="statement_settings_form" method="post" action="<?php echo URL::to('user/save_statement_settings'); ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Statement Settings</h4>
            </div>
            <div class="modal-body modal_max_height" style="clear:both">  
              <?php
              $settingsval = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
              ?>
              <div class="row">
                <div class="col-md-4">
                  <spam>Email Header Image:</spam>
                </div>
                <div class="col-md-8">
                  <?php
                  if($settingsval->email_header_url == '') {
                    $default_image = DB::table("email_header_images")->first();
                    if($default_image->url == "") {
                      $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                    }
                    else{
                      $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                    }
                  }
                  else {
                    $image_url = URL::to($settingsval->email_header_url.'/'.$settingsval->email_header_filename);
                  }
                  ?>
                  <img src="<?php echo $image_url; ?>" class="statement_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                  <input type="button" name="statement_email_header_img_btn" class="common_black_button statement_email_header_img_btn" value="Browse"> 
                </div>
              </div>
              <div class="row" style="margin-top:20px">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Secondary Email CC Address:</label>
                </div>
                <div class="col-md-8">
                  <input type="email" name="statement_secondary" class="form-control statement_secondary" value="<?php echo (isset($settingsval->statement_cc_email))?$settingsval->statement_cc_email:''; ?>">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Minimum Balance Level:</label>
                </div>
                <div class="col-md-8">
                  <input type="text" name="minimum_bal" class="form-control minimum_bal" onkeypress="validate(event)" value="<?php echo (isset($settingsval->minimum_balance))?$settingsval->minimum_balance:''; ?>">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Statement to Include Invoices Set as Not for Statement:</label>
                </div>
                <div class="col-md-8">
                  <?php
                  $statement_invoice = (isset($settingsval->statement_invoice))?$settingsval->statement_invoice:1;
                  ?>
                  <input type="radio" name="statement_invoice" class="statement_invoice" id="statement_invoice_yes" value="1" <?php if($statement_invoice == 1) { echo 'checked'; } ?> style="width:0px"><label for="statement_invoice_yes" style="width:12%">Yes</label>
                  <input type="radio" name="statement_invoice" class="statement_invoice" id="statement_invoice_no" value="0" <?php if($statement_invoice == 0) { echo 'checked'; } ?> style="width:0px"><label for="statement_invoice_no" style="width:12%">No</label>
                </div>
              </div>
              <br>
              <div class="row" style="padding-bottom:8px; font-weight:bold;">
                <div class="col-md-12">Bank Details:</div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Payments to IBAN:</label>
                </div>
                <div class="col-md-8">
                  <input type="text" name="payments_to_iban" class="form-control payments_to_iban" value="<?php echo (isset($settingsval->payments_to_iban))?$settingsval->payments_to_iban:''; ?>">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Payments to BIC:</label>
                </div>
                <div class="col-md-8">
                  <input type="text" name="payments_to_bic" class="form-control payments_to_bic" value="<?php echo (isset($settingsval->payments_to_bic))?$settingsval->payments_to_bic:''; ?>">
                </div>
              </div>
              <br>
              <div class="row" style="padding-bottom:8px; font-weight:bold;">
                <div class="col-md-12">Background Image:</div>
              </div>
              <div class="row" style="padding-bottom:8px;">
                <div class="col-md-12"><b><u>Note:</u></b>
                  <p>1. Kindly attach png or jpg format only</p>
                  <p>2. Kindly attach image size as 827px * 1100px</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Statement Page 1 Background:</label>
                </div>
                <div class="col-md-8">
                  <input type="file" name="bg_image" class="form-control bg_image" accept="image/png, image/jpeg" value="">
                  <?php 
                  if(isset($settingsval->bg_filename)) {
                    if($settingsval->bg_filename != "")
                    {
                      echo '<h4 class="h_delete_bg1">Attachment:</h4><a class="a_delete_bg1" href="'.URL::to($settingsval->bg_url.'/'.$settingsval->bg_filename).'" download>'.$settingsval->bg_filename.'</a>
                      <a href="javascript:" class="delete_bg1" style="color:red;"><span><i class="fa fa-trash-o delete_bg1" aria-hidden="true"></i></span></a>
                      ';
                    } 
                  }
                  ?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label style="font-weight:normal;">Statement Subsequent Page Background:</label>
                </div>
                <div class="col-md-8">
                  <input type="file" name="bg_image2" class="form-control bg_image2" accept="image/png, image/jpeg" value="">
                  <?php 
                  if(isset($settingsval->bg_filename1)) {
                    if($settingsval->bg_filename1 != "")
                    {
                      echo '<h4 class="h_delete_bg2">Attachment:</h4><a class="a_delete_bg2" href="'.URL::to($settingsval->bg_url1.'/'.$settingsval->bg_filename1).'" download>'.$settingsval->bg_filename1.'</a>
                      <a href="javascript:" class="delete_bg2" style="color:red;"><span><i class="fa fa-trash-o delete_bg2" aria-hidden="true"></i></span></a>';
                    } 
                  }
                  ?>
                </div>
              </div>
              <br>
              <div class="row" style="padding-bottom:8px; font-weight:bold;">
                <div class="col-md-12">
                  <label>Email Body Message:</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <textarea name="email_body" class="form-control email_body" id="editor_1"><?php echo (isset($settingsval->email_body))?$settingsval->email_body:''; ?></textarea>
                </div>
              </div>
              <div class="row" style="padding-bottom:8px; font-weight:bold;margin-top:20px;">
                <div class="col-md-12">
                  <label>Email Signature:</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <textarea name="email_signature" class="form-control email_signature" id="editor_2"><?php echo (isset($settingsval->email_signature))?$settingsval->email_signature:''; ?></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="clear:both">  
                <input type="submit" class="common_black_button" value="Submit">
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
          <form action="<?php echo URL::to('user/edit_statement_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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