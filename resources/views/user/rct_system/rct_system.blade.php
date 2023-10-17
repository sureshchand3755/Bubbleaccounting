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
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URL::to('user/rct_system'); ?>">RCT Manager</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::to('user/rct_summary'); ?>">RCT Summary</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::to('user/rct_liability_disclosure'); ?>">Liability Disclosure</a>
        </li>
      </ul>
    </div>

<div class="row" style="background: #fff; padding-bottom: 20px;">
            <div class="col-lg-1" style="padding-left: 40px; line-height: 35px; width: 160px; margin-top: 18px; ">
                Active Month
            </div>
            <div class="col-lg-2 padding_00" style="margin-top: 20px;">
              <select class="form-control select_active_month_rct">
                <?php
                  $current_month = date('Y-m');
                $prevdate = date("Y-m-05", strtotime("first day of -1 months"));
                $prev_date2 = date('Y-m', strtotime($prevdate));
                  $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
                  for($i=0;$i<=22;$i++)
                  {
                    $month = $i + 1;
                    $newdate = date("Y-m-05", strtotime("first day of -".$month." months"));
                    $formatted_date = date('M-Y', strtotime($newdate));
                    $formatted_date2 = date('Y-m', strtotime($newdate));

                    if($prev_date2 == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
                    $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
                  }
                  echo $active_drop;
                ?>
              </select>
              
            </div>
            <div class="col-lg-2 padding_00" style="margin-top: 20px;">
                <input type="checkbox" name="hide_inactive_clients" class="hide_inactive_clients" id="hide_inactive_clients"><label for="hide_inactive_clients" style="margin-left: 20px;margin-top: 7px;">Hide Inactive Clients</label>
                </div>
            <div class="col-lg-2" style="float:right;text-align: right;margin-right: 12px; margin-top: 30px; background: #fff">
               <a href="javascript:" class="fa fa-cog common_black_button rct_settings" title="RCT Settings" style="margin-top: -11px;float: right;"></a>
              <a href="javascript:" class="common_black_button extract_to_csv" style="margin-top: -11px;float: right;">Extract to CSV</a>
             
            </div>
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
<div class="modal fade rct_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-lg" role="document" style="width:60%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">RCT Settings</h4>
          </div>
          <div class="modal-body" style="clear:both">  
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active">
                <a class="nav-link active" id="rctemail-tab" data-toggle="tab" href="#rctemail" role="tab" aria-controls="rctemail" aria-selected="true">Email Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Email Salutation</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="rctprofile-tab" data-toggle="tab" href="#rctprofile" role="tab" aria-controls="rctprofile" aria-selected="false">Letter Pad Background</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="rctemail" role="tabpanel" aria-labelledby="rctemail-tab">
                  <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('user/update_rct_settings'); ?>">
                      <div class="col-lg-12 padding_00">
                        <div>
                          <?php
                          if(Session::has('emailmessage')) { ?>
                              <p class="alert alert-info" style="width:90%"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                          <?php }
                          ?>
                        </div>
                        <?php
                        $rct_settings = DB::table('rct_settings')->where('practice_code', Session::get('user_practice_code'))->first();
                        $rct_cc_email = '';
                        if($rct_settings){
                          $rct_cc_email = $rct_settings->rct_cc_email;
                        }
                        ?>
                          <div class="form-group">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                              <?php
                              if($rct_settings->email_header_url == '') {
                                $default_image = DB::table("email_header_images")->first();
                                if($default_image->url == "") {
                                  $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                                }
                                else{
                                  $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                                }
                              }
                              else {
                                $image_url = URL::to($rct_settings->email_header_url.'/'.$rct_settings->email_header_filename);
                              }
                              ?>
                              <img src="<?php echo $image_url; ?>" class="rct_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                              <input type="button" name="rct_email_header_img_btn" class="common_black_button rct_email_header_img_btn" value="Browse"> 
                              <h4>RCT CC Email ID</h4>
                              <input id="validation-cc-email"
                                     class="form-control"
                                     placeholder="Enter RCT CC Email ID"
                                     value="<?php echo $rct_cc_email; ?>"
                                     name="rct_cc_email"
                                     type="text"
                                     required>                                 
                          </div>
                      </div>
                      <div class="col-lg-12 padding_00">
                        <input type="submit" class="common_black_button" id="rct_submit" value="Submit">
                      </div>
                      @csrf
                    </form>
              </div>
              <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                
                <style>
                .cursor_sort{cursor: pointer;}
                </style>
                  <div class="col-lg-12 padding_00" style="margin-top: 20px;">
                      <div class="col-lg-12 padding_00">                    
                          <div class="margin-bottom-50">
                              <table class="table" >
                                  <thead class="table-inverse">
                                  <tr style="background: #fff;">
                                      <th width="5%" style="text-align: left;">S.No</th>
                                      <th>Name</th>
                                      <th style="text-align: center;">Description</th>
                                      <th style="text-align: center;" width="15%">Action</th>
                                  </tr>
                                  </thead>                            
                                  <tbody>
                                    <?php
                                      $i=1;
                                      if(($userlist)){              
                                        foreach($userlist as $user){
                                    ?>
                                    <tr>            
                                      <td><?php echo $i;?></td>            
                                      <td align="left"><?php echo $user->name; ?></td>
                                      <td align="left"><?php echo $user->description; ?></td>
                                      <td align="center">
                                          <a href="#" id="<?php echo base64_encode($user->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                    <?php
                                        $i++;
                                        }              
                                      }
                                      else{
                                        echo'<tr><td colspan="5" align="center">Empty</td></tr>';
                                      }
                                    ?>                                                       
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div> 

                <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
                <input type="hidden" name="first_sortoptions" id="first_sortoptions" value="asc">
                <input type="hidden" name="last_sortoptions" id="last_sortoptions" value="asc">
                <input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
                <input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
                <input type="hidden" name="seconday_sortoptions" id="seconday_sortoptions" value="asc">
              </div>
              <div class="tab-pane fade" id="rctprofile" role="tabpanel" aria-labelledby="rctprofile-tab">
                    <div class="col-lg-12 padding_00">
                        <div class="col-lg-12 padding_00">                    
                            <div class="margin-bottom-50">
                                <table class="table">
                                    <thead class="table-inverse">
                                    <tr style="background: #fff;">
                                        <th width="5%" style="text-align: left;">S.No</th>
                                        <th>Name</th>
                                        <th>Email Salutation</th>
                                        <th style="text-align: center;">image</th>
                                        <th style="text-align: center;" width="15%">Action</th>
                                    </tr>
                                    </thead>                            
                                    <tbody id="sort_maintable">
                                      <?php
                                        $i=1;
                                        if(($letterpad)){              
                                          foreach($letterpad as $user){
                                      ?>
                                      <tr>            
                                        <td><?php echo $i;?></td>            
                                        <td align="left"><?php echo $user->name; ?></td>
                                        <td align="left"><?php echo $user->salution; ?></td>
                                        <td align="left">
                                          <img width="150px;" src="<?php echo URL::to('uploads/letterpad/'.$user->image); ?>" >                                                                    
                                        </td>
                                        <td align="center">
                                            <a href="#" id="<?php echo base64_encode($user->id); ?>" class="editclass_letter"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                        </td>
                                      </tr>
                                      <?php
                                          $i++;
                                          }              
                                        }
                                        else{
                                          echo'<tr><td colspan="5" align="center">Empty</td></tr>';
                                        }
                                      ?>                                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
                    <input type="hidden" name="first_sortoptions" id="first_sortoptions" value="asc">
                    <input type="hidden" name="last_sortoptions" id="last_sortoptions" value="asc">
                    <input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
                    <input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
                    <input type="hidden" name="seconday_sortoptions" id="seconday_sortoptions" value="asc">
              </div>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">  
            
          </div>
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
          <form action="<?php echo URL::to('user/edit_rct_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;z-index:99999999999">
    <div class="modal-dialog modal-sm" role="document">
      <form id="form-validation-edit" action="<?php echo URL::to('user/update_rctsalution'); ?>" method="post" class="editsp">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Salutation</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <input type="text"
                    class="form-control name_class" readonly>
              </div>

              <div class="form-group">
                  <textarea class="form-control desc_class"
                         name="description"
                         placeholder="Enter Salutation"
                         type="text"
                         data-validation="[NOTEMPTY]"
                         data-validation-message="Enter Salutation" style="height: 150px;"></textarea>
                  <input type="hidden" class="name_id" name="id">
              </div>            
            </div>
            <div class="modal-footer">
              <input type="submit" value="Update" class="common_black_button">
            </div>
          </div>
      @csrf
</form>
    </div>
  </div>
<div class="modal fade bs-example-modal-sm_letter" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
    <form id="form-validation-edit" action="<?php echo URL::to('user/update_rctletterpad'); ?>" method="post" enctype="multipart/form-data" class="editsp_letter">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Letter Background</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <input type="text"
                  class="form-control name_class_letter" readonly>
            </div>

            <div class="form-group">
              <img src="<?php echo URL::to('uploads/letterpad/no-image.jpg'); ?>" id="img_id" width="100%">
            </div>
            <div class="form-group">
                <textarea class="form-control salution_class" style="height: 150px;" name="salution" data-validation="[NOTEMPTY]" data-validation-message="Enter Salutation">></textarea>
            </div>

            <div class="form-group">
                <input class="form-control image_class"
                       name="letterpadimage"                       
                       type="file"
                       data-validation="[NOTEMPTY]"
                       data-validation-message="Enter Image">
                <input type="hidden" class="name_id_letter" name="id">
            </div>            
          </div>
          <div class="modal-footer">
            <input type="submit" value="Update" class="common_black_button">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<table class="display nowrap fullviewtablelist own_table_white" id="ta_expand" width="100%" style="max-width: 100%; margin-top:0px !important; ">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: center;">ActiveClient</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Month</th>
                            <th style="text-align: left;">Deduction</th>
                            <th style="text-align: left;">Gross</th>
                            <th style="text-align: left;">Net</th>
                            <th style="text-align: left;">Count</th>
                            <!-- <th style="text-align: left;">Email</th> -->
                            <th style="text-align: center;">Action</th>
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
                                  $inactive_cls = 'inactive_cls';
                                }
                                else{
                                  $color="";
                                  $inactive_cls = '';
                                }
                          ?>
                            <tr class="edit_task tr_client_td_<?php echo $client->client_id; ?> <?php echo $inactive_cls; ?>">
                                <td style="<?php echo $color; ?>"><?php echo $ival; ?></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->client_id; ?></a></td>
                                <td align="center"><img class="active_client_list_tm1" data-iden="<?php echo $client->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->firstname; ?></a></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->surname; ?></a></td>
                                <td align="left">
                                    <select class="form-control select_active_month" data-element="<?php echo $client->client_id; ?>" style="<?php echo $color; ?>">
                                      <option value="">Select Month</option>
                                      <?php
                                        $current_month = date('Y-m');

                                        $prevdate = date("Y-m-05", strtotime("first day of -1 months"));
                                        $prev_date2 = date('Y-m', strtotime($prevdate));
                                        $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
                                        for($i=0;$i<=22;$i++)
                                        {
                                          $month = $i + 1;
                                          $newdate = date("Y-m-05", strtotime("first day of -".$month." months"));
                                          $formatted_date = date('M-Y', strtotime($newdate));
                                          $formatted_date2 = date('Y-m', strtotime($newdate));

                                          if($prev_date2 == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
                                          $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
                                        }
                                        echo $active_drop;
                                      ?>
                                    </select>
                                </td>
                                <td align="left" class="deduction_clientid" style="<?php echo $color; ?>">
                                  <?php
                                    $deductionsum = 0;
                                    $grosssum = 0;
                                    $netsum = 0;
                                    $icount = 0;

                                    $rct_output = '';
                                    $rctsubmission = DB::table('rct_submission')->where('client_id', $client->client_id)->first();
                                    if(($rctsubmission)){
                                      $start_date = unserialize($rctsubmission->start_date);
                                      $grossval = unserialize($rctsubmission->value_gross);
                                      $netval = unserialize($rctsubmission->value_net);
                                      $deductionval = unserialize($rctsubmission->deduction);

                                      $prevdate = date("Y-m-05", strtotime("first day of -1 months"));
                                      $prev_date2 = date('Y-m', strtotime($prevdate));
                                      $data = array();
                                      if(($start_date))
                                      {
                                        foreach($start_date as $key => $start)
                                        {
                                          $date = substr($start,0,7);
                                          if($date == $prev_date2)
                                          {
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
                                      }
                                      krsort($data);
                                      if(($data))
                                      {
                                        foreach($data as $key_date => $dataval)
                                        {
                                          $grosssum = 0;
                                          $netsum = 0;
                                          $deductionsum = 0;
                                          $icount = 0;
                                          if(($dataval))
                                          {
                                            foreach($dataval as $sumvalue)
                                            {
                                              if(isset($grossval[$sumvalue]))
                                              {
                                                $grosssum = $grosssum + $grossval[$sumvalue];
                                              }
                                              else{
                                                $grosssum = $grosssum + 0;
                                              }

                                              if(isset($netval[$sumvalue]))
                                              {
                                                $netsum = $netsum + $netval[$sumvalue];
                                              }
                                              else{
                                                $netsum = $netsum + 0;
                                              }

                                              if(isset($deductionval[$sumvalue]))
                                              {
                                                $deductionsum = $deductionsum + $deductionval[$sumvalue];
                                              }
                                              else{
                                                $deductionsum = $deductionsum + 0;
                                              }
                                              $icount++;
                                            }
                                          }
                                        }
                                      }
                                    }
                                    echo ($deductionsum)?number_format_invoice_without_decimal($deductionsum):'-';
                                    ?>
                                </td>
                                <td align="left" class="gross_clientid" style="<?php echo $color; ?>">
                                  <?php echo ($grosssum)?number_format_invoice_without_decimal($grosssum):'-'; ?>
                                </td>
                                <td align="left" class="net_clientid" style="<?php echo $color; ?>"><?php echo ($netsum)?number_format_invoice_without_decimal($netsum):'-'; ?></td>
                                <td align="left" class="count_clientid" style="<?php echo $color; ?>"><?php echo ($icount)?$icount:'-'; ?></td>
                                <!-- <td align="left" class="emails_clientid">
                                  <?php
                                  $emails = DB::table('rct_submission_email')->where('client_id',$client->client_id)->where('start_date',$prev_date2)->get();
                                  if(($emails))
                                  {
                                    foreach($emails as $email)
                                    {
                                      echo '<p style="'.$color.'">'.date('F d, Y', strtotime($email->email_sent)).'</p>';
                                    }
                                  }
                                  ?>
                                </td> -->
                                <td align="center"><a href="<?php echo URL::to('user/rct_liability_assessment/'.$client->client_id).'?active_month='.$prev_date2; ?>" class="fa fa-eye view_liability_assessment" style="<?php echo $color; ?>"></a></td>
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
                                  <td align="center">Empty</td>
                                  <td></td>
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
  if($(e.target).hasClass('rct_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the RCT Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('hide_inactive_clients'))
  {
      $(".edit_task").show();
      if($(e.target).is(":checked"))
      {
          $(".inactive_cls").hide();
      }
      else{
          $(".inactive_cls").show();
      }
  }
  if($(e.target).hasClass('extract_to_csv'))
  {
    var value = $(".select_active_month_rct").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/extract_rct_active_month_data'); ?>",
      type:"post",
      data:{date:value},
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
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
    setTimeout(function() {
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
    },500);
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
  if($(e.target).hasClass('view_rct_manager'))
  {
    e.preventDefault();
    var href= $(e.target).attr("data-element");
    var active_month = $(e.target).parents("tr").find(".select_active_month").val();
    window.location.replace(href+'?active_month='+active_month);
  }
})

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
              $(".rct_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">RCT Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
            $(".rct_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">RCT Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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