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
body{
  background: #f5f5f5 !important;
}
.input_readonly{
  text-align:left !important;
}
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

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{background: #eaeaea !important}
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
.input_readonly{border: 0px; box-shadow: none; width: 100%; float: right; text-align: right; background: transparent;}
.input_readonly:focus{border:none !important; outline: 0px;}
</style>

<img id="coupon" />

<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document" style="width:40%">
    <form action="<?php echo URL::to('user/rct_send_bulk_email'); ?>" method="post" id="emailunsent_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RCT SUBMISSION EMAIL</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="from_user" id="from_user" class="form-control " value="" required>
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
              <?php
              if($client_details->email2 == "")
              {
                ?>
                <input type="text" name="to_user" id="to_user" class="form-control " value="<?php echo $client_details->email; ?>" required>
                <?php
              }
              else{
                ?>
                <input type="text" name="to_user" id="to_user" class="form-control " value="<?php echo $client_details->email.','.$client_details->email2; ?>" required>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <?php 
              $rct_details = DB::table('rct_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                $admin_cc = $rct_details->rct_cc_email;
              ?>
              <input type="text" name="cc_unsent" class="form-control " id="cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent" class="form-control  subject_unsent" value="RCT LIABILITY ASSESSMENT" required>
            </div>
          </div>
          <?php
          
          if($rct_details->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($rct_details->email_header_url.'/'.$rct_details->email_header_filename);
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
              <textarea name="message_editor" id="editor_1"></textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment:</label>
            </div>
            <div class="col-md-12">
              <h5><img src="<?php echo URL::to('public/assets/images/pdf.png'); ?>" style="width:30px;"> RCT_Notification.pdf</h5>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_keys" id="hidden_keys" value="">
        <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="<?php echo $client_details->client_id; ?>">
        <input type="submit" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade batch_email_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document" style="width:40%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RCT SUBMISSION EMAIL</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="from_user_batch" id="from_user_batch" class="form-control " value="" required>
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
              <?php
              if($client_details->email2 == "")
              {
                ?>
                <input type="text" name="to_user_batch" id="to_user_batch" class="form-control " value="<?php echo $client_details->email; ?>" required>
                <?php
              }
              else{
                ?>
                <input type="text" name="to_user_batch" id="to_user_batch" class="form-control " value="<?php echo $client_details->email.','.$client_details->email2; ?>" required>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <?php 
                $admin_cc = $rct_details->rct_cc_email;
              ?>
              <input type="text" name="cc_unsent_batch" class="form-control " id="cc_unsent_batch" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button send_batch_email" value="Send Email">
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_submissions_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/rct_edit_submission_update')?>" id="edit_submission">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit RCT Submissions</h4>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label>Select Type</label>
                <select class="form-control type_class_edit" name="" required>
                  <option value="">Select Type</option>
                  <option value="1">Contract</option>
                  <option value="2">Payment</option>
                </select>
                
              </div>
            </div>
          <div class="col-lg-6">
              <div class="form-group rct_id_group_edit" style="display: none;">
                <label>RCT ID</label>
                <input type="number" class="form-control rct_class_edit" required placeholder="Enter RCT ID" name="rct_id">
              </div>
              <div class="form-group principal_group_edit" style="display: none;">
                <label>Principal Name</label>
                <select class="form-control principal_class_edit" required name="principal_name">
                  <option value="">Select Principal Name</option>
                  <?php
                  if(($taxlist)){
                    $tax_name = explode(',', $taxlist->tax_name);
                    $tax_number = explode(',', $taxlist->tax_number);
                    $outputtax='';
                    if(($tax_name)){
                      foreach ($tax_name as $key => $name) {              
                        $outputtax.='
                        <option value="'.$key.'">'.$name.' - '.$tax_number[$key].'</option>';
                      }
                    }
                  }
                  else{
                    $outputtax='
                          <option value="">Empty</option>';
                  }
                  echo $outputtax;
                  ?>
                </select>
                
              </div>
              <div class="form-group sub_contractor_group_edit" style="display: none;">
                <label>Sub Contractor Name</label>
                <input type="text" class="form-control sub_contractor_class_edit" required placeholder="Enter Sub Contractor Name" name="sub_contractor_name">
              </div>
              <div class="form-group sub_contractor_id_group_edit" style="display: none;">
                <label>Sub Contractor ID</label>
                <input type="text" class="form-control sub_contractor_id_class_edit" required placeholder="Enter Sub Contractor ID" name="sub_contractor_id">
              </div>
              <div class="form-group site_group_edit" style="display: none;">
                <label>Site</label>
                <input type="text" class="form-control site_class_edit" required placeholder="Enter Site" name="site">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group start_date_group_edit" style="display: none;">
                <label>Start/Pay Date</label>

                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control start_date_class_edit" placeholder="Enter Start/Pay Date" name="start_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
                
              </div>
              <div class="form-group finish_date_group_edit" style="display: none;">
                <label>Finish Date</label>
                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control finish_date_class_edit" placeholder="Enter Finish Date" name="finish_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>                
              </div>
              <div class="form-group gross_group_edit" style="display: none;">
                <label>Value Gross</label>
                <input type="number" class="form-control value_gross_class_edit" required placeholder="Enter Value Gross" name="value_gross">
              </div>
              <div class="form-group value_group_edit" style="display: none;">
                <label>Value Net</label>
                <input type="number" class="form-control value_net_class_edit" required placeholder="Enter Value Net" name="value_net">
              </div>
              <div class="form-group deduction_group_edit" style="display: none;">
                <label>Deduction</label>
                <input type="number" class="form-control deduction_class_edit" required placeholder="Enter Deduction" name="deduction">
              </div>
            </div>
          </div>
        </div>
          <div class="modal-footer">
              <input type="hidden" class="type_edit"  name="submission_type">
              <input type="hidden" class="key_edit" name="key">
              <input type="hidden" value="<?php echo $client_details->client_id?>" name="client_id">
              <a href="javascript:" class="common_black_button saveas_pdf" style="float: left;">Save as PDF</a>
              <input type="submit" class="common_black_button" value="Update">
          </div>
        @csrf
</form>
      </div>
  </div>
</div>







<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h5 class="col-lg-3" style="padding: 0px;font-size:22px;font-weight:600">
                RCT Liability Assessment      
                <input type="hidden" value="<?php echo $client_details->client_id?>" id="client_id" name="client_id">
            </h5>
        <a href="<?php echo URL::to('user/rct_system'); ?>" class="common_black_button" style="float:right">Back to RCT Manager</a>
        <a href="<?php echo URL::to('user/rct_client_manager/'.$client_details->client_id.'?active_month='.$_GET['active_month']); ?>" class="common_black_button" style="float:right">Move to Client RCT Manager</a>
</div>

<div class="col-lg-12 padding_00" style="font-size: 15px !important;">
  
  
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

    <div class="col-lg-2" style="line-height: 30px; font-weight: 700">
      Client Id:<br/>
      Company:<br/>
      Name:<br/>
      Primary Email:<br/>
      Secondary Email:
    </div>
    <div class="col-lg-3" style="line-height: 30px;">
      <?php echo $client_details->client_id;?><br/>
      <?php echo $client_details->company;?><br/>
      <?php echo $client_details->firstname;?><br/>
      <?php echo $client_details->email;?><br/>
      <?php echo $client_details->email2;?>
    </div>

    <div class="col-lg-1" style="line-height: 30px; padding-top: 120px; font-weight: 700; text-align: left;">Email Sent:</div>
    <div class="clearfix"></div>

    <div class="col-lg-2" style="line-height: 30px; margin-top: 30px; font-weight: 700;">Active Month:</div>
    <div class="col-lg-2" style="line-height: 30px; margin-top: 30px; font-weight: 700;">
      <select class="form-control active_month_class">
        <option value="">Select All</option>
        <?php
        $active_drop='';
        if(($rctsubmission)){
          $start_date = unserialize($rctsubmission->start_date);

          $data = array();
          if(($start_date))
          {
            foreach($start_date as $key => $start)
            {
              $date = substr($start,0,7);
              if(isset($data[$date]))
              {
                $implodeval = implode(",",$data[$date]);                  
                $combineval = $implodeval.','.$key;
                $data[$date] = explode(',',$combineval);

                

              }
              else{
                $data[$date] = array($key);
              }
            }
          }
          krsort($data);
          $has_active = 0;
          if(($data)){
            foreach($data as $key_date => $dataval)
            {   
              if(isset($_GET['active_month']))
              {
                if($_GET['active_month'] == $key_date) { $selected = 'selected'; $has_active = 1; } else { $selected = ''; }
              }
              else{
                $selected = '';
                $has_active = 0;
              }
              $active_drop.='<option value="'.$key_date.'" '.$selected.'>'.date('M-Y', strtotime($key_date)).'</option>';
            }
          }

        }
        else{
          $active_drop='Empty';
        }
        echo $active_drop;
        ?>        
      </select>
    </div>
    <div class="col-lg-1"></div>
    <?php
    $last_email_sent = DB::table('rct_liability_assessment_email')->where('client_id',$client_details->client_id)->orderBy('email_sent','DESC')->first(); ?>
    <div class="col-lg-2" style="line-height: 30px; margin-top: 10px; font-weight: 700; text-align: left;">
      <?php
      if(($last_email_sent)) { 
        echo date('d-M-Y',strtotime($last_email_sent->email_sent));
      }
      ?>
    </div>

</div>

<div class="row">
  
   <div class="col-lg-4" style="padding-top: 25px;">
     <div class="form-group" style="float: left; margin-right: 15px;">
        <input type="checkbox" class="select_all_class" name="" id="select_all_class">
       <label for="select_all_class">All</label>
     </div>
     <div class="form-group" style="float: left; margin-right: 15px;">
        <input type="checkbox" name="" id="contract_checkbox">
       <label for="contract_checkbox">CONTRACTS</label>
     </div>
     <div class="form-group" style="float: left; margin-right: 15px;">
        <input type="checkbox" name="" id="payment_checkbox">
       <label for="payment_checkbox">PAYMENTS</label>
     </div>
   </div>
   <div class="col-lg-4 text-center" style="font-size: 15px; font-weight: bold; margin-top: 25px;"></div>
   <div class="col-lg-4">
    <div class="select_button">
      <ul style="float: right; margin-top: 15px;">                                    
        <li><a href="javascript:" class="email_batch_send" style="font-size: 13px; font-weight: 500;">Send Batch Email</a></li>
        <li><a href="javascript:" class="email_class" style="font-size: 13px; font-weight: 500;">Email to Client</a></li>
        <li><a href="javascript:" class="extract_pdf_class" style="font-size: 13px; font-weight: 500;">Extract to PDF</a></li>
        <li><a href="javascript:" class="extract_csv_class" style="font-size: 13px; font-weight: 500;">Extract to CSV</a></li>
        
      </ul>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="table-responsive">
      
      <table class="own_table" width="100%" style="max-width: 100%; margin-bottom: 0px;">
        <thead>
          <tr style="background: #fff;">
            <th style="text-align:left"></th>
            <th style="text-align:left">Type</th>
            <th style="text-align:left">RCT ID</th>
            <th style="text-align:left">Principal Name</th>
            <th style="text-align:left">Sub Contractor Name</th>
            <th style="text-align:left">Sub Contractor ID</th>
            <th style="text-align:left">Site</th>
            <th style="text-align:left">Start/Pay Date</th>
            <th style="text-align:left">Finish Date</th>
            <th style="text-align:left;width: 130px;">Value Gross</th>
            <th style="text-align:left;width: 130px;">Value Net</th>
            <th style="text-align:left;width: 130px;">Deduction</th>
            <th style="text-align:left;width: 160px;" align="center">Action</th>
          </tr>
        </thead>
        <tbody class="view_liability_class">
          <?php
          $outputsubmission='';
          $site_view = '';
          $sub_cont_name = '';
          $sub_cont_id = '';
          $finish_date_view = '';
          $value_net_view = '';
          $deduction_view = '';
          $type_text='';
          $rct_id='';
          $key='';
          $principal_key='';
          $explode_tax_name='';
          $explode_tax_number='';

          if(($rctsubmission)){            
            $rct_type = unserialize($rctsubmission->type);
            $rct_id = unserialize($rctsubmission->rct_id);
            $principal_name = unserialize($rctsubmission->principal_name);
            
            
            $start_date = unserialize($rctsubmission->start_date);
            
            $value_gross = unserialize($rctsubmission->value_gross);

            
            $count_submitton = 0;
            $total_gross='';
            $total_net='';
            $total_deduction='';
            if(($rct_type)){
              $counti = 0;
              foreach ($rct_type as $key => $type) {
                $check_date = date('Y-m', strtotime($start_date[$key]));
                if($has_active == 1)
                {
                  if($check_date == $_GET['active_month'])
                  {
                    $count_submitton++;
                    if($type == 1){
                      $type_text = 'CONTRACT';    
                      
                      $site = unserialize($rctsubmission->site);  
                      $finish_date = unserialize($rctsubmission->finish_date); 
                      $value_net = unserialize($rctsubmission->value_net);
                      $deduction = unserialize($rctsubmission->deduction);           
                    }
                    elseif(($type == 2)){
                      $type_text = 'PAYMENT';

                      $sub_contractor = unserialize($rctsubmission->sub_contractor);
                      $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
                      $value_net = unserialize($rctsubmission->value_net);
                      $deduction = unserialize($rctsubmission->deduction);
                    }
                    else{
                      $type_text='';
                    }

                    $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
                    if(($tax_details))
                    {
                      $explode_tax_name = explode(',', $tax_details->tax_name);
                      $explode_tax_number = explode(',', $tax_details->tax_number);
                    }
                    else{
                      $explode_tax_name = array();
                      $explode_tax_number = array();
                    }

                    $principal_key = $principal_name[$key];

                    if(isset($sub_contractor[$key])){
                      $sub_cont_name = $sub_contractor[$key];
                    }
                    else{
                      $sub_cont_name = '';
                    }

                    if(isset($sub_contractor_id[$key])){
                      $sub_cont_id = $sub_contractor_id[$key];
                    }
                    else{
                      $sub_cont_id = '';
                    }

                    if(isset($site[$key])){                  
                        $site_view = $site[$key];                  
                    }
                    else{
                      $site_view = '';
                    }

                    if(isset($finish_date[$key])){
                      $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
                      $finish_date_spam = strtotime($finish_date[$key]);
                    }
                    else{
                      $finish_date_view = '';
                      $finish_date_spam = '';
                    }

                    if(isset($value_net[$key])){
                      $value_net_total = $value_net[$key];
                      $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
                    }
                    else{
                      $value_net_total = 0;
                      $value_net_view = '0.00';
                    }

                    if(isset($deduction[$key])){
                      $value_deduction_total = $deduction[$key];
                      $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
                    }
                    else{
                      $value_deduction_total = 0;
                      $deduction_view = '0.00';
                    }

                    $total_gross = (int)$total_gross+(int)$value_gross[$key];
                    $total_net = (int)$total_net+(int)$value_net_total;
                    $total_deduction = (int)$total_deduction+(int)$value_deduction_total;


                    if(isset($explode_tax_name[$principal_key])) { $exp_tax_name = $explode_tax_name[$principal_key]; } else { $exp_tax_name = ''; }
                    if(isset($explode_tax_number[$principal_key])) { $exp_tax_number = $explode_tax_number[$principal_key]; } else { $exp_tax_number = ''; }
                    $sub_id_val = $rct_id[$key].$type;
                    $emails_sent = DB::table('rct_submission_email')->where('submission_id',$sub_id_val)->where('client_id',$client_id)->get();
                    $ee = '';
                    if(($emails_sent))
                    {
                      foreach($emails_sent as $sent)
                      {
                        $ee.='<label>'.date('d-M-Y', strtotime($sent->email_sent)).'</label>';
                      }
                    }
                    $outputsubmission.='
                    <tr>
                      <td style="text-align:left"><input type="checkbox" class="select_class select_class_'.$type.'" value="'.$key.'"  /><label>&nbsp;</label></td>
                      <td style="text-align:left">'.$type_text.'</td>
                      <td style="text-align:left">'.$rct_id[$key].'<br/></td>
                      <td style="text-align:left">'.$exp_tax_name.' - '.$exp_tax_number.'</td>
                      <td style="text-align:left">'.$sub_cont_name.'</td>
                      <td style="text-align:left">'.$sub_cont_id.'</td>
                      <td style="text-align:left">'.$site_view.'</td>
                      <td style="text-align:left"><spam style="display:none">'.strtotime($start_date[$key]).'</spam>'.date('d-M-Y', strtotime($start_date[$key])).'</td>
                      <td style="text-align:left"><spam style="display:none">'.$finish_date_spam.'</spam>'.$finish_date_view.'</td>
                      <td style="text-align:left"><input type="hidden" class="gross_value_class" value="'.$value_gross[$key].'"> 
                      '.number_format_invoice_without_decimal($value_gross[$key]).'</td>
                      <td style="text-align:left"><input type="hidden" class="net_value_class" value="'.$value_net_total.'"> '.$value_net_view.'</td>
                      <td style="text-align:left"><input type="hidden" class="deduction_value_class" value="'.$value_deduction_total.'"> '.$deduction_view.'</td>
                      <td style="text-align:left">
                        <a href="javascript:" title="Download Pdf"><i class="fa fa-download download_submission" data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                        <a href="javascript:" title="View / Edit"><i class="fa fa-pencil edit_class"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                        <a href="javascript:" title="Email"><i class="fa fa-envelope email_class_single"  data-element="'.$type.'" id="'.$key.'"></i></a><br/>
                        '.$ee.'
                      </td>
                    </tr>
                    ';
                    $counti++;
                  }
                }
                else{
                    $count_submitton++;
                    if($type == 1){
                      $type_text = 'CONTRACT';    
                      
                      $site = unserialize($rctsubmission->site);  
                      $finish_date = unserialize($rctsubmission->finish_date); 
                      $value_net = unserialize($rctsubmission->value_net);
                      $deduction = unserialize($rctsubmission->deduction);           
                    }
                    elseif(($type == 2)){
                      $type_text = 'PAYMENT';

                      $sub_contractor = unserialize($rctsubmission->sub_contractor);
                      $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
                      $value_net = unserialize($rctsubmission->value_net);
                      $deduction = unserialize($rctsubmission->deduction);
                    }
                    else{
                      $type_text='';
                    }

                    $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
                    if(($tax_details))
                    {
                      $explode_tax_name = explode(',', $tax_details->tax_name);
                      $explode_tax_number = explode(',', $tax_details->tax_number);
                    }
                    else{
                      $explode_tax_name = array();
                      $explode_tax_number = array();
                    }

                    $principal_key = $principal_name[$key];

                    

                    if(isset($sub_contractor[$key])){
                      $sub_cont_name = $sub_contractor[$key];
                    }
                    else{
                      $sub_cont_name = '';
                    }

                    if(isset($sub_contractor_id[$key])){
                      $sub_cont_id = $sub_contractor_id[$key];
                    }
                    else{
                      $sub_cont_id = '';
                    }

                    if(isset($site[$key])){                  
                        $site_view = $site[$key];                  
                    }
                    else{
                      $site_view = '';
                    }

                    if(isset($finish_date[$key])){
                      $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
                    }
                    else{
                      $finish_date_view = '';
                    }

                    if(isset($value_net[$key])){
                      $value_net_total = $value_net[$key];
                      $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
                    }
                    else{
                      $value_net_total = 0;
                      $value_net_view = '0.00';
                    }

                    if(isset($deduction[$key])){
                      $value_deduction_total = $deduction[$key];
                      $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
                    }
                    else{
                      $value_deduction_total = 0;
                      $deduction_view = '0.00';
                    }

                    $total_gross = (float)$total_gross + (float)$value_gross[$key];
                    $total_net = (float)$total_net + (float)$value_net_total;
                    $total_deduction = (float)$total_deduction + (float)$value_deduction_total;

                    if(isset($explode_tax_name[$principal_key])) { $exp_tax_name = $explode_tax_name[$principal_key]; } else { $exp_tax_name = ''; }
                    if(isset($explode_tax_number[$principal_key])) { $exp_tax_number = $explode_tax_number[$principal_key]; } else { $exp_tax_number = ''; }

                    $sub_id_val = $rct_id[$key].$type;
                    $emails_sent = DB::table('rct_submission_email')->where('submission_id',$sub_id_val)->where('client_id',$client_id)->get();
                    $ee = '';
                    if(($emails_sent))
                    {
                      foreach($emails_sent as $sent)
                      {
                        $ee.='<label>'.date('d-M-Y', strtotime($sent->email_sent)).'</label>';
                      }
                    }
                    
                    $outputsubmission.='
                    <tr>
                      <td style="text-align:left"><input type="checkbox" class="select_class select_class_'.$type.'" value="'.$key.'"  /><label>&nbsp;</label></td>
                      <td style="text-align:left">'.$type_text.'</td>
                      <td style="text-align:left">'.$rct_id[$key].'<br/></td>
                      <td style="text-align:left">'.$exp_tax_name.' - '.$exp_tax_number.'</td>
                      <td style="text-align:left">'.$sub_cont_name.'</td>
                      <td style="text-align:left">'.$sub_cont_id.'</td>
                      <td style="text-align:left">'.$site_view.'</td>
                      <td style="text-align:left">'.date('d-M-Y', strtotime($start_date[$key])).'</td>
                      <td style="text-align:left">'.$finish_date_view.'</td>
                      <td style="text-align:left" ><input type="hidden" class="gross_value_class" value="'.$value_gross[$key].'"> 
                      '.number_format_invoice_without_decimal($value_gross[$key]).'</td>
                      <td style="text-align:left"><input type="hidden" class="net_value_class" value="'.$value_net_total.'"> '.$value_net_view.'</td>
                      <td style="text-align:left"><input type="hidden" class="deduction_value_class" value="'.$value_deduction_total.'"> '.$deduction_view.'</td>
                      <td style="text-align:left">
                        <a href="javascript:" title="Download Pdf"><i class="fa fa-download download_submission" data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                        <a href="javascript:" title="View / Edit"><i class="fa fa-pencil edit_class"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                        <a href="javascript:" title="Email"><i class="fa fa-envelope email_class_single"  data-element="'.$type.'" id="'.$key.'"></i></a><br/>
                        '.$ee.'
                      </td>
                    </tr>
                    ';
                    $counti++;
                }
              }
              if($counti == 0)
              {
                $outputsubmission='
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td align="left">Empty</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                ';
              }
            }  
            else{
              $outputsubmission='
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="left">Empty</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              ';
            }            
          }          
          else{
            $outputsubmission='
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td align="left">Empty</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            ';
            $total_gross='';
            $total_net='';
            $total_deduction='';
            $count_submitton=0;
          }
          echo $outputsubmission;
          ?>

          <tr style="border-top:1px solid #000;">
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td style="border-top:0px;"></td>
            <td align="left" style="width: 160px; border-top:0px;">Total</td>
            <td align="left" style="width: 130px; border-top:0px;">
              <input type="hidden" class="gross_total" value="<?php echo $total_gross?>" name="">
              <span class="gross_total_span"><?php echo number_format_invoice_without_decimal($total_gross)?></td></span>
              
            <td align="left" style="width: 130px; border-top:0px;">
              <input type="hidden" class="net_total" value="<?php echo $total_net?>" name="">
              <span class="net_total_span"><?php echo number_format_invoice_without_decimal($total_net)?></span>                
              </td>
            <td align="left" style="width: 130px; border-top:0px;">
              <input type="hidden" class="deduction_total" value="<?php echo $total_deduction?>" name="">
              <span class="deduction_total_span"><?php echo number_format_invoice_without_decimal($total_deduction)?></span>              
            </td>
            <td align="left" style="width: 120px; border-top:0px;"></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left" style="width: 160px;">Total of Selected</td>
            <td align="left" style="width: 130px;"><span class="input_readonly already_gross"></span></td>
            <td align="left" style="width: 130px;"><span class="input_readonly already_net"></span></td>
            <td align="left" style="width: 130px;"><span class="input_readonly already_deduction"></span></td>
            <td align="left"></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left" style="width: 160px;">Difference</td>
            <td align="left" style="width: 130px;"><span class="input_readonly difference_gross"></span></td>
            <td align="left" style="width: 130px;"><span class="input_readonly difference_net"></span></td>
            <td align="left" style="width: 130px;"><span class="input_readonly difference_deduction"></span></td>
            <td align="left"></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left" style="width: 160px;">Total Items</td>
            <td align="left" style="width: 130px;"><input type="text" class="input_readonly total_items" value="<?php echo $count_submitton?>" readonly name=""></td>
            <td align="left" style="width: 130px;"></td>
            <td align="left" style="width: 130px;"></td>
            <td align="left"></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left" style="width: 160px;">Items Selected</td>
            <td align="left" style="width: 130px;"><input type="text" value="" class="input_readonly items_selected" readonly name=""></td>
            <td align="left" style="width: 130px;"></td>
            <td align="left" style="width: 130px;"></td>
            <td align="left"></td>
          </tr>



          
        </tbody>
      </table>
      <table class="own_table" width="100%" style="max-width: 100%; margin:0px;">
        
        
      </table>
    </div>
  </div>
  
     
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
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Emails are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Sending Emails: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script>
function next_email_check(count)
{
  var from_user = $("#from_user_batch").val();
  var to_user = $("#to_user_batch").val();
  var cc_unsent = $("#cc_unsent_batch").val();

  var client_id = $("#client_id").val();
  var type = $(".select_class:checked:eq("+count+")").parents("tr").find(".email_class_single").attr("data-element");
  var key = $(".select_class:checked:eq("+count+")").parents("tr").find(".email_class_single").attr("id");

  if(type == "1")
  {
    var subject = "RCT Contract";
  }
  else{
    var subject = "RCT Payment";
  }

  $.ajax({
    url:"<?php echo URL::to('user/send_batch_email_single'); ?>",
    type:"post",
    data:{client_id:client_id,type:type,key:key,from_user:from_user,to_user:to_user,cc_unsent:cc_unsent,subject:subject},
    success:function(result)
    {
      setTimeout( function() {
        var countval = count + 1;
        if($(".select_class:checked:eq("+countval+")").length > 0)
        {
          $("#apply_first").html(countval);
          next_email_check(countval);
        }
        else{
          $("#from_user_batch").val("");
            $("body").removeClass("loading_apply");
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Email Sent Successfully</p>',fixed:true,width:"800px"});
        }
      },200);
    }
  });


  var inv_id = $(".include_tr:eq("+count+")").find(".invoice_class").html();
  $.ajax({
    url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
    type:"post",
    data:{inv_id:inv_id},
    success:function(result)
    {
      $(".include_tr:eq("+count+")").find(".jids").html(result);
      setTimeout( function() {
        var countval = count + 1;
        if($(".include_tr:eq("+countval+")").length > 0)
        {
          next_invoice_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("#hidden_financial_opening_date").val("");
          $("body").removeClass("loading_apply");
        }
      },200);
    }
  });
}
$(window).click(function(e) {
if($(e.target).hasClass('email_batch_send'))
{
  var length = $(".select_class:checked").length;
  if(length > 0)
  {
    $(".batch_email_modal").modal("show");
  }
  else{
     alert('Please Select atleast one RCT Submission');
  }
}
if($(e.target).hasClass('send_batch_email'))
{
  var from_user = $("#from_user_batch").val();
  var to_user = $("#to_user_batch").val();
  var cc_unsent = $("#cc_unsent_batch").val();
  if(from_user == "")
  {
    alert("Please select the From User to send a Email");
  }
  else if(to_user == "")
  {
    alert("Please Enter the To User to send a Email");
  }
  else if(cc_unsent == ""){
    alert("Please Enter the CC to send a Email");
  }
  else{
    $(".batch_email_modal").modal("hide");
    $("body").addClass("loading_apply");
    var length = $(".select_class:checked").length;
    $("#apply_last").html(length);

    var client_id = $("#client_id").val();
    var type = $(".select_class:checked:eq(0)").parents("tr").find(".email_class_single").attr("data-element");
    var key = $(".select_class:checked:eq(0)").parents("tr").find(".email_class_single").attr("id");

    if(type == "1")
    {
      var subject = "RCT Contract";
    }
    else{
      var subject = "RCT Payment";
    }

    $.ajax({
      url:"<?php echo URL::to('user/send_batch_email_single'); ?>",
      type:"post",
      data:{client_id:client_id,type:type,key:key,from_user:from_user,to_user:to_user,cc_unsent:cc_unsent,subject:subject},
      success:function(result)
      {
        setTimeout( function() {
          if($(".select_class:checked:eq(1)").length > 0)
          {
            $("#apply_first").html(1);
            next_email_check(1);
          }
          else{
            $("#from_user_batch").val("");
            $("body").removeClass("loading_apply");
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Email Sent Successfully</p>',fixed:true,width:"800px"});
          }
        },200);
      }
    });
  }
}
if($(e.target).hasClass('download_submission'))
{
  $("body").addClass("loading");
  var type = $(e.target).attr("data-element");
  var key = $(e.target).attr("id");
  var client_id = $("#client_id").val();

  $.ajax({
      url:"<?php echo URL::to('user/rct_saveaspdf'); ?>",
      type:"post",
      data:{type:type,key:key,client_id:client_id},
      success: function(result){
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
  })
}
if($(e.target).hasClass('extract_pdf_class'))
{
  var length = $(".select_class:checked").length;
  if(length > 0)
  {
    $("body").addClass("loading");
    var client_id = $("#client_id").val();
    var keys ='';
    $(".select_class:checked").each(function() {
      if(keys == "")
      {
        keys = $(this).val();
      }
      else{
        keys = keys+','+$(this).val();
      }
    });
    $.ajax({
        url:"<?php echo URL::to('user/rctsaveaspdf_multiple'); ?>",
        type:"post",
        data:{keys:keys,client_id:client_id},
        success: function(result){
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
    })
  }
  else{
    alert('Please Select atleast one RCT Submission to Extract to PDF');
  }
}
if($(e.target).hasClass('email_class'))
{
  var length = $(".select_class:checked").length;
  if(length > 0)
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      CKEDITOR.replace('editor_1',
      {
        height: '150px',
      });
      var selected_items = $(".total_items").val();
      var already_deduction = $(".already_deduction").html();
      var active_month = $(".active_month_class").val();
      var already_gross = $(".already_gross").html();

      if(active_month == "")
      {
        var active_14 = 'Nil';
      }
      else{
        var month = active_month.split("-");
        var monthval = parseInt(month[1]) + parseInt(1);
        console.log(monthval);
        if(monthval == 1) { var mon = 'January'; }
        else if(monthval == 2) { var mon = 'Febravary'; }
        else if(monthval == 3) { var mon = 'March'; }
        else if(monthval == 4) { var mon = 'April'; }
        else if(monthval == 5) { var mon = 'May'; }
        else if(monthval == 6) { var mon = 'June'; }
        else if(monthval == 7) { var mon = 'july'; }
        else if(monthval == 8) { var mon = 'August'; }
        else if(monthval == 9) { var mon = 'September'; }
        else if(monthval == 10) { var mon = 'October'; }
        else if(monthval == 11) { var mon = 'November'; }
        else if(monthval == 12) { var mon = 'December'; }
        else{ var mon = 'Empty'; }
        var active_14 = '14 '+mon+' '+month[0];

      }

      var keys ='';
      $(".select_class:checked").each(function() {
        if(keys == "")
        {
          keys = $(this).val();
        }
        else{
          keys = keys+','+$(this).val();
        }
      });
      $("#hidden_keys").val(keys);

        var html = '<p><?php echo $client_details->salutation; ?>,</p><p>We have created a total of '+selected_items+' RCT submissions for you and have listed them below and attached them for your reference.</p> <p>RCT deducted totals - '+already_deduction+'</p><p>THIS AMOUNT IS PAYABLE TO THE REVENUE BY '+active_14+'</p><p>Note: Gross sub contract payments totalled '+already_gross+'</p>';
        CKEDITOR.instances['editor_1'].setData(html);
        var active_month = $(".active_month_class").val();
        if(active_month == "")
        {
          var textval = '';
        }
        else{
          var splitval = active_month.split("-");
          if(splitval[1] == "01") { var month = 'Jan'; }
          else if(splitval[1] == "02") { var month = 'Feb'; }
          else if(splitval[1] == "03") { var month = 'Mar'; }
          else if(splitval[1] == "04") { var month = 'Apr'; }
          else if(splitval[1] == "05") { var month = 'May'; }
          else if(splitval[1] == "06") { var month = 'Jun'; }
          else if(splitval[1] == "07") { var month = 'Jul'; }
          else if(splitval[1] == "08") { var month = 'Aug'; }
          else if(splitval[1] == "09") { var month = 'Sep'; }
          else if(splitval[1] == "10") { var month = 'Oct'; }
          else if(splitval[1] == "11") { var month = 'Nov'; }
          else if(splitval[1] == "12") { var month = 'desc'; }

          var textval = month+'-'+splitval[0];
        }
        

      $(".subject_unsent").val("RCT Summary for Active Month: "+textval);
    $(".emailunsent").modal('show');
  }
  else{
    alert('Please Select atleast one RCT Submission to Extract to PDF');
  }
}
if($(e.target).hasClass('email_class_single'))
{
  var client_id = $("#client_id").val();
  var type = $(e.target).attr("data-element");
  var key = $(e.target).attr("id");
  $("#hidden_keys").val(key);

  if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
  CKEDITOR.replace('editor_1',
  {
    height: '150px',
  });
  $.ajax({
    url:"<?php echo URL::to('user/get_ckeditor_content_single'); ?>",
    type:"post",
    data:{client_id:client_id,type:type,key:key},
    success:function(result)
    {
      CKEDITOR.instances['editor_1'].setData(result);
      if(type == "1")
      {
        $(".subject_unsent").val("RCT Contract");
      }
      else{
        $(".subject_unsent").val("RCT Payment");
      }
      $(".emailunsent").modal('show');
    }
  })
}
if($(e.target).hasClass('extract_csv_class'))
{
  var length = $(".select_class:checked").length;
  if(length > 0)
  {
    $("body").addClass("loading");
    var client_id = $("#client_id").val();
    var keys ='';
    $(".select_class:checked").each(function() {
      if(keys == "")
      {
        keys = $(this).val();
      }
      else{
        keys = keys+','+$(this).val();
      }
    });
    $.ajax({
        url:"<?php echo URL::to('user/rctsaveascsv_multiple'); ?>",
        type:"post",
        data:{keys:keys,client_id:client_id},
        success: function(result){
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
    })
  }
  else{
    alert('Please Select atleast one RCT Submission to Extract to PDF');
  }
}
if(e.target.id == "select_all_class")
{
  if($(e.target).is(":checked"))
  {
    $(".select_class").prop("checked",true);
    $("#contract_checkbox").prop("checked",true);
    $("#payment_checkbox").prop("checked",true);
  }
  else{
    $(".select_class").prop("checked",false);
    $("#contract_checkbox").prop("checked",false);
    $("#payment_checkbox").prop("checked",false);
  }

  var totalgross = 0;
  var totalnet = 0;
  var totaldeduction = 0;
  if($(".select_class:checked").length > 0)
  {
    $(".select_class:checked").each(function() {
      var grossval = $(this).parents("tr").find(".gross_value_class").val();
      var netval = $(this).parents("tr").find(".net_value_class").val();
      var deductionval = $(this).parents("tr").find(".deduction_value_class").val();

      if(parseInt(grossval) > 0) { totalgross = parseInt(totalgross) + parseInt(grossval); }
      if(parseInt(netval) > 0) {totalnet = parseInt(totalnet) + parseInt(netval); }
      if(parseInt(deductionval) > 0) { totaldeduction = parseInt(totaldeduction) + parseInt(deductionval); }
    });

    var gross_total_span = $(".gross_total").val();
    var net_total_span = $(".net_total").val();
    var deduction_total_span = $(".deduction_total").val();

    $(".already_gross").html($.number(totalgross,2));
    $(".already_net").html($.number(totalnet,2));
    $(".already_deduction").html($.number(totaldeduction,2));

    var diff_gross = parseInt(gross_total_span) - parseInt(totalgross);
    var diff_net = parseInt(net_total_span) - parseInt(totalnet);
    var diff_deduction = parseInt(deduction_total_span) - parseInt(totaldeduction);

    $(".difference_gross").html($.number(diff_gross,2));
    $(".difference_net").html($.number(diff_net,2));
    $(".difference_deduction").html($.number(diff_deduction,2));

    $(".items_selected").val($(".select_class:checked").length);
  }
  else{
    $(".already_gross").html("");
    $(".already_net").html("");
    $(".already_deduction").html("");

    $(".difference_gross").html("");
    $(".difference_net").html("");
    $(".difference_deduction").html("");
    $(".items_selected").val("");
  }
}
if(e.target.id == "contract_checkbox"){
  $(".select_all_class").prop("checked",false);
  if($(e.target).is(":checked"))
  {
    $(".select_class_1").prop("checked",true);
  }
  else{
    $(".select_class_1").prop("checked",false);
  }

  var totalgross = 0;
  var totalnet = 0;
  var totaldeduction = 0;
  if($(".select_class:checked").length > 0)
  {
    $(".select_class:checked").each(function() {
      var grossval = $(this).parents("tr").find(".gross_value_class").val();
      var netval = $(this).parents("tr").find(".net_value_class").val();
      var deductionval = $(this).parents("tr").find(".deduction_value_class").val();

      if(parseInt(grossval) > 0) { totalgross = parseInt(totalgross) + parseInt(grossval); }
      if(parseInt(netval) > 0) {totalnet = parseInt(totalnet) + parseInt(netval); }
      if(parseInt(deductionval) > 0) { totaldeduction = parseInt(totaldeduction) + parseInt(deductionval); }
    });

    var gross_total_span = $(".gross_total").val();
    var net_total_span = $(".net_total").val();
    var deduction_total_span = $(".deduction_total").val();

    $(".already_gross").html($.number(totalgross,2));
    $(".already_net").html($.number(totalnet,2));
    $(".already_deduction").html($.number(totaldeduction,2));

    var diff_gross = parseInt(gross_total_span) - parseInt(totalgross);
    var diff_net = parseInt(net_total_span) - parseInt(totalnet);
    var diff_deduction = parseInt(deduction_total_span) - parseInt(totaldeduction);

    $(".difference_gross").html($.number(diff_gross,2));
    $(".difference_net").html($.number(diff_net,2));
    $(".difference_deduction").html($.number(diff_deduction,2));

    $(".items_selected").val($(".select_class:checked").length);
  }
  else{
    $(".already_gross").html("");
    $(".already_net").html("");
    $(".already_deduction").html("");

    $(".difference_gross").html("");
    $(".difference_net").html("");
    $(".difference_deduction").html("");
    $(".items_selected").val("");
  }
}
if(e.target.id == "payment_checkbox"){
  $(".select_all_class").prop("checked",false);
  if($(e.target).is(":checked"))
  {
    $(".select_class_2").prop("checked",true);
  }
  else{
    $(".select_class_2").prop("checked",false);
  }

  var totalgross = 0;
  var totalnet = 0;
  var totaldeduction = 0;
  if($(".select_class:checked").length > 0)
  {
    $(".select_class:checked").each(function() {
      var grossval = $(this).parents("tr").find(".gross_value_class").val();
      var netval = $(this).parents("tr").find(".net_value_class").val();
      var deductionval = $(this).parents("tr").find(".deduction_value_class").val();

      if(parseInt(grossval) > 0) { totalgross = parseInt(totalgross) + parseInt(grossval); }
      if(parseInt(netval) > 0) {totalnet = parseInt(totalnet) + parseInt(netval); }
      if(parseInt(deductionval) > 0) { totaldeduction = parseInt(totaldeduction) + parseInt(deductionval); }
    });

    var gross_total_span = $(".gross_total").val();
    var net_total_span = $(".net_total").val();
    var deduction_total_span = $(".deduction_total").val();

    $(".already_gross").html($.number(totalgross,2));
    $(".already_net").html($.number(totalnet,2));
    $(".already_deduction").html($.number(totaldeduction,2));

    var diff_gross = parseInt(gross_total_span) - parseInt(totalgross);
    var diff_net = parseInt(net_total_span) - parseInt(totalnet);
    var diff_deduction = parseInt(deduction_total_span) - parseInt(totaldeduction);

    $(".difference_gross").html($.number(diff_gross,2));
    $(".difference_net").html($.number(diff_net,2));
    $(".difference_deduction").html($.number(diff_deduction,2));

    $(".items_selected").val($(".select_class:checked").length);
  }
  else{
    $(".already_gross").html("");
    $(".already_net").html("");
    $(".already_deduction").html("");

    $(".difference_gross").html("");
    $(".difference_net").html("");
    $(".difference_deduction").html("");
    $(".items_selected").val("");
  }
}
if($(e.target).hasClass('allocate_button'))
{
  var select_invoice = $(".select_invoice").val();
  var select_invoice_click = $(".select_invoice_click").val();

  if(select_invoice == "")
  {
    alert("Please select invoice.")
  }  
  else if($(".select_task:checked").length)
    {
      var invoice = $(".select_invoice_click").val();
      var client_id = $("#client_search").val();
      var checkedvalue = '';
      var size = 100;
      $(".select_task:checked").each(function() {
        var value = $(this).val();
        if(checkedvalue == "")
        {
          checkedvalue = value;
        }
        else{
          checkedvalue = checkedvalue+","+value;
        }
      });
      var exp = checkedvalue.split(',');
      var arrayval = [];
      for (var i=0; i<exp.length; i+=size) {
          var smallarray = exp.slice(i,i+size);
          arrayval.push(smallarray);
      }
      $.each(arrayval, function( index, value ) {
          setTimeout(function(){ 
            var imp = value.join(',');
            $.ajax({
              url:"<?php echo URL::to('user/ta_tasks_update'); ?>",
              type:"post",
              dataType:"json",
              data:{value:imp,invoice:invoice,client_id:client_id},
              success: function(result)
              {
                                 
              }
            });
          }, 3000);
      });
  }
  else{
    $("body").removeClass("loading");
    alert("Please Choose atleast one Task to continue.");
  }   
}
if($(e.target).hasClass('edit_class')){
  //$("body").addClass("loading");
  var type = $(e.target).attr('data-element');
  var key = $(e.target).attr('id');
  var client_id = $("#client_id").val();

  console.log(client_id);

  $.ajax({
      url:"<?php echo URL::to('user/rct_edit_submission'); ?>",
      type:"post",
      data:{type:type,key:key,client_id:client_id},
      dataType:"json",
      success: function(result){
        $(".type_class_edit").val(result['type']);       
        $(".type_class_edit").attr("disabled", true);
        $(".rct_class_edit").attr("disabled", true);
        $(".rct_class_edit").val(result['rct_id']);
        $(".principal_class_edit").val(result['principal']);
        $(".start_date_class_edit").val(result['start_date']);
        $(".key_edit").val(result['key']);
        $(".type_edit").val(result['type']);

        $(".start_date_class_edit  ").datetimepicker({
           //defaultDate: fullDate,
           format: 'L',
           format: 'DD-MMM-YYYY',
        });

        $(".value_gross_class_edit").val(result['gross']);

        $(".rct_id_group_edit").show();
        $(".principal_group_edit").show();
        $(".start_date_group_edit").show();
        $(".gross_group_edit").show();
        

        if(result['type'] == 1){
          

          $(".site_group_edit").show();
          $(".finish_date_group_edit").show();

          $(".site_class_edit").val(result['site']);
          $(".finish_date_class_edit").val(result['finish']);

          $(".finish_date_class_edit  ").datetimepicker({
             //defaultDate: fullDate,
             format: 'L',
             format: 'DD-MMM-YYYY',
          });

          $(".sub_contractor_group_edit").hide();
          $(".sub_contractor_id_group_edit").hide();
          $(".value_group_edit").hide();
          $(".deduction_group_edit").hide();

        }
        else{
          $(".site_group_edit").hide();
          $(".finish_date_group_edit").hide();

          $(".sub_contractor_group_edit").show();
          $(".sub_contractor_id_group_edit").show();
          $(".value_group_edit").show();
          $(".deduction_group_edit").show();

          $(".sub_contractor_class_edit").val(result['sub_contractor']);
          $(".sub_contractor_id_class_edit").val(result['sub_contractor_id']);
          $(".value_net_class_edit").val(result['value_net']);
          $(".deduction_class_edit").val(result['deduction']);
          
        }


        $(".edit_submissions_modal").modal('show');
        
        //$("body").removeClass("loading");

      }
  })
}
if($(e.target).hasClass('select_class')){  
  if($(e.target).is(":checked"))
  {
    $(e.target).prop("checked",true);
  }
  else{
    $(e.target).prop("checked",false);
  }

  var totalgross = 0;
  var totalnet = 0;
  var totaldeduction = 0;
  if($(".select_class:checked").length > 0)
  {
    $(".select_class:checked").each(function() {
      var grossval = $(this).parents("tr").find(".gross_value_class").val();
      var netval = $(this).parents("tr").find(".net_value_class").val();
      var deductionval = $(this).parents("tr").find(".deduction_value_class").val();

      if(parseInt(grossval) > 0) { totalgross = parseInt(totalgross) + parseInt(grossval); }
      if(parseInt(netval) > 0) {totalnet = parseInt(totalnet) + parseInt(netval); }
      if(parseInt(deductionval) > 0) { totaldeduction = parseInt(totaldeduction) + parseInt(deductionval); }
    });

    var gross_total_span = $(".gross_total").val();
    var net_total_span = $(".net_total").val();
    var deduction_total_span = $(".deduction_total").val();

    $(".already_gross").html($.number(totalgross,2));
    $(".already_net").html($.number(totalnet,2));
    $(".already_deduction").html($.number(totaldeduction,2));

    var diff_gross = parseInt(gross_total_span) - parseInt(totalgross);
    var diff_net = parseInt(net_total_span) - parseInt(totalnet);
    var diff_deduction = parseInt(deduction_total_span) - parseInt(totaldeduction);

    $(".difference_gross").html($.number(diff_gross,2));
    $(".difference_net").html($.number(diff_net,2));
    $(".difference_deduction").html($.number(diff_deduction,2));

    $(".items_selected").val($(".select_class:checked").length);
  }
  else{
    $(".already_gross").html("");
    $(".already_net").html("");
    $(".already_deduction").html("");

    $(".difference_gross").html("");
    $(".difference_net").html("");
    $(".difference_deduction").html("");
    $(".items_selected").val("");
  }
}
})

$(window).change(function(e) {
if($(e.target).hasClass('active_month_class')){
  var date = $(e.target).val();
  var client_id = $("#client_id").val();
  $.ajax({
      url:"<?php echo URL::to('user/rct_liability_filter'); ?>",
      type:"post",
      data:{date:date,client_id:client_id},
      dataType:"json",
      success: function(result){
        window.history.replaceState(null, null, "<?php echo URL::to('user/rct_liability_assessment'); ?>/"+client_id+"?active_month="+date);

        $(".view_liability_class").html(result['result_liability_submission']);
        $(".already_gross").val('');
        $(".already_net").val('');
        $(".already_deduction").val('');
        $(".difference_gross").val('');
        $(".difference_net").val('');
        $(".difference_deduction").val('');
        $(".items_selected").val('');

        $("#select_all_class").attr("checked", false);
        $("#contract_checkbox").attr("checked", false);
        $("#payment_checkbox").attr("checked", false);
        
        

      }
  })
}
})
</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#rct_expand').DataTable({
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

$.ajaxSetup({async:false});
$('#edit_submission').validate({
    rules: {          
      submission_type:{required: true},      
      rct_id: {required: true, remote:{url:"<?php echo URL::to('user/rct_submission_check')?>",
                  data: {'client_id':function(){return $('#client_id').val()}, 'submission_type':function(){return $('.type_class').val()}},
                                async:false },},
      principal_name:{required: true},
      sub_contractor_name:{required: true},
      sub_contractor_id:{required: true},
      site:{required: true},
      start_date:{required: true},
      finish_date:{required: true},
      value_gross:{required: true},
      value_net:{required: true},
      deduction:{required: true},
    },
    messages: {              
      submission_type:{required:"Please select type"},
      rct_id : {
          required : "Enter RCT ID",
          remote : "RCT ID is already exists",
      },
      principal_name:{required:"Please select principal name "},
      sub_contractor_name:{required:"Please enter sub contractor name"},
      sub_contractor_id:{required:"Please enter sub contractor id"},
      site:{required:"Please enter site"},
      start_date:{required:"Please select start date"},
      finish_date:{required:"Please select finish date"},
      value_gross:{required:"Please enter value gross"},
      value_net:{required:"Please enter value net"},
      deduction:{required:"Please enter deduction"},      
    },
});


</script>


@stop
