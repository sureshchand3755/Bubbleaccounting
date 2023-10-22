﻿@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/html2canvas.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
  #colorbox{
    z-index:9999999;
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
.disabled_ard{
  background: #dfdfdf !important;
  pointer-events: none; 
}
body{
  background: #f5f5f5 !important;
}
.form-control[readonly]{
      background-color: #fff !important
}
.ard_class[readonly]{
      background-color: #dfdfdf !important
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
    top: 91%;
    left:54%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
}
.selectall_div{
  position: absolute;
    top: 13%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
    height:60px;
    z-index:999;
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
<?php
if(!empty($_GET['import_type_new']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];

    
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];

    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('public/assets/images/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_new_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&out=<?php echo $out; ?>');
      })
    </script>
    <?php

  }
}
if(!empty($_GET['import_type_existing']))
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];
    $checkbox = $_GET['checkbox'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('public/assets/images/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_existing_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_existing=1&out=<?php echo $out; ?>&checkbox=<?php echo $checkbox; ?>');
      })
    </script>
    <?php

  }
}
?>
<img id="coupon" />
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false" data-backdrop="static" style="z-index: 9999999999999999999999999999">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Invoices</h4>
        </div>
        <div class="modal-body" style="height: 700px; font-size: 14px; overflow-y: scroll;">
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
<div class="modal fade" id="import_newclient_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/import_new_clients'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Clients</h4>
          </div>
          <div class="modal-body">
              <label>Choose File : </label>
              <input type="file" name="new_file" id="new_file" class="form-control input-sm" accept=".csv" required> <br/>
              <p style="color:#f00">Note : Identify a csv file that has no blank row at the top of the screen that includes a header row followed by the records to be imported</p>
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" id="import_new_file" value="Import">
          </div>
        @csrf
</form>
      </div>
  </div>
</div>
<div class="modal fade" id="module_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Task Details</h4>
          </div>
          <div class="modal-body">
              <label>Enter Salutation : </label>
              <div class="form-group">            
                  <input class="form-control" name="salutation_module" id="salutation_module" placeholder="Enter Salutation" type="text">
              </div>
              <label>Enter Primary Email : </label>
              <div class="form-group">            
                  <input class="form-control" name="pemail_module" id="pemail_module" placeholder="Enter Primary Email" type="text">
              </div>
              <label>Enter Secondary Email : </label>
              <div class="form-group">            
                  <input class="form-control" name="semail_module" id="semail_module" placeholder="Enter Secondary Email" type="text">
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="task_type" id="task_type" value="">
              <input type="hidden" name="task_id_module" id="task_id_module" value="">
              <input type="submit" class="common_black_button" id="submit_module_update" value="Submit">
          </div>
      </div>
  </div>
</div>
<div class="modal fade show_messageus_last_screen_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index:9999999">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title menu-logo">MessageUs Summary</h4>
          </div>
          <div class="modal-body" id="show_messageus_body" style="max-height: 600px;height:600px; overflow-y: scroll;">
              
          </div>
          <div class="modal-footer">
              <button type="button" class="common_black_button" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="import_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="import_existing_clients_form" action="<?php echo URL::to('user/import_existing_clients'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Clients</h4>
            </div>
            <div class="modal-body">
                <label>Choose File To Import :</label>
                <input type="file" name="exists_file" id="exists_file" class="form-control input-sm" accept=".csv" required><br/>
                <p style="color:#f00">Note : Identify a csv file that has no blank row at the top of the screen that includes a header row followed by the records to be imported</p>
                <label style="width:100%; margin-top:15px; margin-bottom:15px">Choose Fields To Update For Existing Clients : </label>

                <input type="checkbox" id="select_all_fields" class="select_all_fields" value="1"><label for="select_all_fields" style="width:100%; margin-top:15px; margin-bottom:15px">Select All</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="firstname_update" value="firstname"> <label for="firstname_update" class="field_check"> Firstname </label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="surname_update" value="surname"> <label for="surname_update" class="field_check"> Surname </label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="company_update" value="company"> <label for="company_update" class="field_check">Company</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="address1_update" value="address1"> <label for="address1_update" class="field_check">Address 1</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="address2_update" value="address2"> <label for="address2_update" class="field_check">Address 2</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="address3_update" value="address3"> <label for="address3_update" class="field_check">Address 3</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="address4_update" value="address4"> <label for="address4_update" class="field_check">Address 4</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="address5_update" value="address5"> <label for="address5_update" class="field_check">Address 5</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="email_update" value="email"> <label for="email_update" class="field_check">Primary Email</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="tye_update" value="tye"> <label for="tye_update" class="field_check">Type</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="active_update" value="active"> <label for="active_update" class="field_check">Active</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="client_added_update" value="client_added"> <label for="client_added_update" class="field_check">Client Added</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="tax_reg1_update" value="tax_reg1"> <label for="tax_reg1_update" class="field_check">Tax Reg 1</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="tax_reg2_update" value="tax_reg2"> <label for="tax_reg2_update" class="field_check">Tax Reg 2</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="tax_reg3_update" value="tax_reg3"> <label for="tax_reg3_update" class="field_check">Tax Reg 3</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="email2_update" value="email2"> <label for="email2_update" class="field_check">Secondary Email</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="phone_update" value="phone"> <label for="phone_update" class="field_check">Phone</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="linkcode_update" value="linkcode"> <label for="linkcode_update" class="field_check">LinkCode</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="cro_update" value="cro"> <label for="cro_update" class="field_check">CRO</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="ard_update" value="ard"> <label for="ard_update" class="field_check">ARD</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="trade_status_update" value="trade_status"> <label for="trade_status_update" class="field_check">Trade Status</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="directory_update" value="directory"> <label for="directory_update" class="field_check">Directory</label>
                <input type="checkbox" class="select_import_client" name="import_field[]" id="practice_code_update" value="practice_code"> <label for="practice_code_update" class="field_check">Practice Code</label>
            </div>
            <div class="modal-footer">
                <input type="submit" class="common_black_button" id="import_existing_clients" value="Import Clients" required>
            </div>
        @csrf
</form>
      </div>
  </div>
</div>
<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Report Clients</h4>
            <br/>
            <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <input type="checkbox" class="select_all_class" id="select_all_class" value="1"><label style="font-weight:600; font-size:14px;">Select All</label>
                    <a data-toggle="collapse" href="#collapse1" style="margin-left:5%"><label style="font-weight:600; font-size:14px;cursor:pointer">Select By Classname <i class="fa fa-chevron-down" aria-hidden="true"></i></label></a>
                  </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse">
                  <div class="panel-body">
                  <h5>Select Clients By Using Class Name</h5>
                      <?php
                        if(($classlist)){
                          foreach($classlist as $class){
                        ?>
                          <div class="col-md-3 col-lg-3 col-sm-3">
                            <input type="checkbox" class="created_class" id="created_class_<?php echo $class->id; ?>" value="<?php echo $class->id; ?>"><label for="created_class_<?php echo $class->id; ?>"><?php echo $class->classname; ?></label>
                          </div>
                        <?php
                          }
                        }
                      ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                   <th width="5%" style="text-align: left;">S.No</th>
                   <th width="5%" style="text-align: left;">Select</th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody id="report_tbody">
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">
            <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
        </div>
      </div>
  </div>
</div>
<div class="modal fade bulk_email_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Email Clients</h4>
            <br/>
            <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <input type="checkbox" class="select_all_email_class" id="select_all_email_class" value="1"><label for="select_all_email_class" style="font-weight:600; font-size:14px;">Select All</label>
                    <div class="selectall_div" style="display:none">
                        <input type="checkbox" name="also_deactivated_clients" id="also_deactivated_clients" value="1"><label for="also_deactivated_clients">Also Select the Deactivated Clients</label>
                    </div>
                    <input type="checkbox" class="deactive_clients_dont" id="deactive_clients_dont" value="1" checked><label for="deactive_clients_dont" style="font-weight:600; font-size:14px;">Dont send to Deactivate Clients</label>
                    <input type="checkbox" class="secondary_email_sent" id="secondary_email_sent" value="1"><label for="secondary_email_sent" style="font-weight:600; font-size:14px;">Send To Secondary Email Address</label>
                    <a data-toggle="collapse" href="#collapse2" style="margin-left:5%"><label style="font-weight:600; font-size:14px;cursor:pointer">Select By Classname <i class="fa fa-chevron-down" aria-hidden="true"></i></label></a>
                  </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                  <div class="panel-body">
                  <h5>Select Clients By Using Class Name</h5>
                      <?php
                        if(($classlist)){
                          foreach($classlist as $class){
                        ?>
                          <div class="col-md-3 col-lg-3 col-sm-3">
                            <input type="checkbox" class="created_email_class" id="created_email_class_<?php echo $class->id; ?>" value="<?php echo $class->id; ?>"><label for="created_email_class_<?php echo $class->id; ?>"><?php echo $class->classname; ?></label>
                          </div>
                        <?php
                          }
                        }
                      ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-body" style="height: 380px; overflow-y: scroll;">
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                   <th width="5%" style="text-align: left;">Select</th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody id="bulk_email_tbody">
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <label style="float:left">Subject</label>
            <input type="text" name="email_subject" id="email_subject" class="form-control input-sm" value="">
            <div style="width:100%">
              <label style="float:left">Email Content</label>
            </div>
            <div style="width:100%;clear:both">
              <textarea name="email_content" id="email_content"></textarea>
            </div>
            <form method="post" name="multiple_upload_form" id="multiple_upload_form" enctype="multipart/form-data" autocomplete="off" action="<?php echo URL::to('user/cm_upload'); ?>">
               <input type="hidden" name="image_form_submit" value="1"/>
                <label style="float:left;margin-top:10px">Choose Attachments</label>
                <input type="file" name="images[]" id="images" multiple style="clear:both">
            @csrf
</form>
                <div class="attachments" id="images_preview" style="float:left;margin-top:10px">
                </div>
            <input type="button" class="common_black_button" id="send_bulk_email" value="Send Email">
        </div>
      </div>
  </div>
</div>
<!-- <div class="modal fade crypt_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Verify Crypt Pin</h4>
      </div>
      <div class="modal-body">
        
          <label>Enter Crypt Pin : </label>
          <div class="form-group">            
              <input class="form-control" name="crypt_pin" id="crypt_pin_input" placeholder="Enter Crypt Pin" type="password" required>
          </div>
          <div class="modal-footer">
            <input type="button" id="crypt_submit" value="Submit" class="common_black_button">
          </div>
        
      </div>
    </div>
  </div>
</div> -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="form-validation" action="<?php echo URL::to('user/add_cm_clients'); ?>" method="post" class="addsp" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Clients</h4>
          </div>
          <div class="modal-body" style="height: 600px; overflow-y: scroll;border-bottom:2px solid #bdbdbd">
            <table class="formtable" style="width:100%">
              <tr id="client_id_div" style="display:none">
                <td>
                    <label>Enter Client ID : </label>
                    <div class="form-group">                
                        <input class="form-control client_id_add" name="client_id" placeholder="Client Id" type="text" disabled>
                    </div>
                </td>
                <td>
                  <label>Client Added : </label>
                    <div class="form-group">                
                        <input class="form-control client_added" name="client_added" placeholder="Client Added" type="text" readonly>
                    </div>
                </td>
                <td></td>
              </tr>
              <tr>
                  <td>
                      <label>Enter First Name : </label>
                      <div class="form-group">                
                          <input class="form-control firstname_add" name="name" placeholder="Enter First Name" type="text" readonly onfocus="this.removeAttribute('readonly');" required>
                      </div>
                  </td>
                  <td>
                      <label>Enter Surname : </label>
                      <div class="form-group">                
                          <input class="form-control surname_add" name="surname" placeholder="Enter Surname" type="text" readonly onfocus="this.removeAttribute('readonly');" required>
                      </div>
                  </td>
                  <td rowspan="3">
                      <label>Enter Address : </label>
                      <div class="form-group">                
                          <input class="form-control address1_add" name="address1" placeholder="Enter Address" type="text" readonly onfocus="this.removeAttribute('readonly');" required>
                          <input class="form-control address2_add" name="address2" placeholder="Enter Address 2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address3_add" name="address3" placeholder="Enter Address 3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address4_add" name="address4" placeholder="Enter Address 4" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address5_add" name="address5" placeholder="Enter Address 5" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <label>Enter Company Name : </label>
                      <div class="form-group">                
                          <input class="form-control company_add" name="cname" placeholder="Enter Company Name" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Enter Primary Email : </label>
                      <div class="form-group">                
                          <input class="form-control pemail_add" name="email" placeholder="Enter Email Id" type="email" readonly onfocus="this.removeAttribute('readonly');" required>
                      </div>
                  </td>
                  <td>
                      <label>Secondary Email : </label>
                      <div class="form-group">                
                          <input class="form-control semail_add" name="semail" placeholder="Enter Secondary Email" type="email" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      <div class="row">
                        <div class="col-md-6">
                          <label>CRO : </label>
                          <div class="form-group">                
                              <input class="form-control cro_add" name="cro" placeholder="Enter CRO" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          </div>
                        </div>
                        <div class="col-md-6" style="margin-top: -7px;">
                          <spam style="margin-bottom: 5px;font-weight: 700;">ARD : <input type="checkbox" name="disable_ard" class="disable_ard_add" id="disable_ard_add" value="1"><label for="disable_ard_add">Disable<label></spam>
                          <div class="form-group">                
                              <input class="form-control ard_add" name="ard" placeholder="Enter ARD" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          </div>
                        </div>
                      </div>
                  </td>
                  <td>
                      <label>Type : </label>
                      <div class="form-group">                
                          <input class="form-control tye_add" name="tye" placeholder="Enter Type" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Link Code : </label>
                      <div class="form-group">                
                          <input class="form-control link_add" name="linkcode" placeholder="Enter Link Code" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Phone : </label>
                      <div class="form-group">                
                          <input class="form-control phone_add" name="phone" placeholder="Enter Phone Number" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Select Class : </label>
                      <div class="form-group">                
                          <select class="form-control class_add" name="class" required readonly onfocus="this.removeAttribute('readonly');">
                              <option value="">Select Class</option>
                              <?php
                              if(($classlist)){
                                foreach($classlist as $class){
                              ?>
                                <option value="<?php echo $class->id; ?>"><?php echo $class->classname?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>

                      </div>
                  </td>
                  <td>
                        <label>Trade Status : </label>
                        <div class="form-group">                
                            <input class="form-control status_add" name="trade_status" placeholder="Enter Trade Status" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
              </tr>
              <tr>
                  <td colspan="3">
                        <label>Directory : </label>
                        <div class="form-group">                
                            <input class="form-control directory_add" name="directory" placeholder="Enter Directory" type="text" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Tax Reg1 : </label>
                      <div class="form-group">                
                          <input class="form-control reg1_add" name="tax_reg1" placeholder="Enter Tax Reg1" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                       <label>Tax Reg2 : </label>
                        <div class="form-group">                
                            <input class="form-control reg2_add" name="tax_reg2" placeholder="Enter Tax Reg2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
                  <td>
                        <label>Tax Reg3 : </label>
                        <div class="form-group">                
                            <input class="form-control reg3_add" name="tax_reg3" placeholder="Enter Tax Reg3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
              </tr>
              
              <?php
                $getfields = DB::table('cm_fields')->where('status',0)->get();
                if(($getfields))
                {
                  $i = 1;
                  echo '<tr>';
                  foreach($getfields as $field)
                  {
                    if($i % 4 == 0) { echo '</tr><tr>'; }
                    echo '<td>';
                    if($field->field == 1)
                    {
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <input type="text" name="<?php echo $field->name; ?>" class="form-control <?php echo $field->name; ?>_add" placeholder="Enter <?php echo $field->name; ?>" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                      <?php
                    }
                    elseif($field->field == 2)
                    {
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <input type="radio" name="<?php echo $field->name ?>" class="<?php echo $field->name; ?>_add" id="<?php echo $field->name; ?>_yes" value="yes"><label for="<?php echo $field->name; ?>_yes"> YES </label>
                            <input type="radio" name="<?php echo $field->name ?>" class="<?php echo $field->name; ?>_add" id="<?php echo $field->name; ?>_no" value="no"><label for="<?php echo $field->name; ?>_no"> NO </label>
                        </div>
                      <?php
                    }
                    elseif($field->field == 3)
                    {
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <textarea name="<?php echo $field->name; ?>" class="form-control <?php echo $field->name; ?>_add" placeholder="Enter <?php echo $field->name; ?>"></textarea>
                        </div>
                      <?php
                    }
                    elseif($field->field == 4)
                    {
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <input type="file" name="<?php echo $field->name; ?>" class="form-control <?php echo $field->name; ?>_add" placeholder="Enter <?php echo $field->name; ?>">
                        </div>
                      <?php
                    }
                    elseif($field->field == 5)
                    {
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <input type="email" name="<?php echo $field->name; ?>" class="form-control <?php echo $field->name; ?>_add" placeholder="Enter <?php echo $field->name; ?>" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                      <?php
                    }
                    elseif($field->field == 6)
                    {
                      $unserialize = unserialize($field->options);
                      ?>
                        <label><?php echo $field->name; ?> : </label>
                        <div class="form-group">
                            <select name="<?php echo $field->name; ?>" class="form-control <?php echo $field->name; ?>_add">
                              <option value="">Select <?php echo $field->name; ?></option>
                              <?php
                                if(($unserialize))
                                {
                                  foreach($unserialize as $key => $arrayval)
                                  {
                                    ?>
                                    <option value="<?php echo $arrayval; ?>"><?php echo $key; ?></option>
                                    <?php
                                  }
                                }
                              ?>
                            </select>
                        </div>
                      <?php
                    }
                    echo '</td>';
                    $i++;
                  }
                  echo '</tr>';
                }
                ?>
            </table>
            
          </div>
          <div class="modal-footer">
            <div class="col-md-3">
              <label style="float: left;">Practice Code : </label>
              <div class="form-group">                
                  <input class="form-control" name="practice_code" placeholder="Enter Practice Code" type="text" required>
              </div>
            </div>
            <div class="col-md-3">
              <input type="submit" id="formvalid_id" style="float: left;margin-top:21px"  value="Submit" class="common_black_button">
            </div>
          </div>
        </div>
    @csrf
</form>
    <!-- <div class="editsp">
      <div class="modal-content">
        <form id="form-validation" action="<?php echo URL::to('user/update_cm_clients'); ?>" method="post" enctype="multipart/form-data" autocomplete="off"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Client</h4>
          </div>
          
            <div class="modal-body" style="height: 450px; overflow-y: scroll;border-bottom:2px solid #bdbdbd">
            <table class="formtable" style="width:100%">
              <tr>
                <td>
                    <label>Enter Client ID : </label>
                    <div class="form-group">                
                        <input class="form-control clientid_class" name="clientid" placeholder="Enter Client ID" type="text" disabled>
                    </div>
                </td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                  <td>
                      <label>Enter First Name : </label>
                      <div class="form-group">                
                          <input class="form-control first_class" name="name" placeholder="Enter First Name" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Enter Surname : </label>
                      <div class="form-group">                
                          <input class="form-control sur_classs" name="surname" placeholder="Enter Surname" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td rowspan="3">
                      <label>Enter Address : </label>
                      <div class="form-group">                
                          <input class="form-control address1_class" name="address1" placeholder="Enter Address" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address2_class" name="address2" placeholder="Enter Address 2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address3_class" name="address3" placeholder="Enter Address 3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address4_class" name="address4" placeholder="Enter Address 4" type="text" readonly onfocus="this.removeAttribute('readonly');">
                          <input class="form-control address5_class" name="address5" placeholder="Enter Address 5" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <label>Enter Company Name : </label>
                      <div class="form-group">                
                          <input class="form-control cname_class" name="cname" placeholder="Enter Company Name" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Enter Primary Email : </label>
                      <div class="form-group">                
                          <input class="form-control email_class" name="email" placeholder="Enter Email Id" type="email" required readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Secondary Email : </label>
                      <div class="form-group">                
                          <input class="form-control semail_class" name="semail" placeholder="Enter Secondary Email" type="email" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>CRO : </label>
                      <div class="form-group">                
                          <input class="form-control cro_class" name="cro" placeholder="Enter CRO" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Type : </label>
                      <div class="form-group">                
                          <input class="form-control tye_class" name="tye" placeholder="Enter Type" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Link Code : </label>
                      <div class="form-group">                
                          <input class="form-control linkcode_class" name="linkcode" placeholder="Enter Link Code" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Phone : </label>
                      <div class="form-group">                
                          <input class="form-control phone_class" name="phone" placeholder="Enter Phone Number" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                      <label>Select Class : </label>
                      <div class="form-group">                
                          <select class="form-control class_class" name="class">
                            <option value="">Select Class</option>
                            <?php
                            if(($classlist)){
                              foreach($classlist as $class){
                            ?>
                              <option value="<?php echo $class->id; ?>"><?php echo $class->classname?></option>
                            <?php
                              }
                            }
                            ?>
                          </select>

                      </div>
                  </td>
                  <td>
                        <label>Trade Status : </label>
                        <div class="form-group">                
                            <input class="form-control trade_status_class" name="trade_status" placeholder="Enter Trade Status" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
              </tr>
              <tr>
                  <td>
                        <label>Directory : </label>
                        <div class="form-group">                
                            <input class="form-control directory_class" name="directory" placeholder="Enter Directory" type="text" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
                  <td>
                        <label>Employer No : </label>
                        <div class="form-group">                
                            <input class="form-control employerno_edit" name="employer_no" placeholder="Enter Employer No" type="text" autocomplete="off" disabled>
                        </div>
                  </td>
                  <td>
                        <label>Salutation : </label>
                        <div class="form-group">                
                            <input class="form-control salutation_edit" name="salutation" placeholder="Enter Salutation" type="text" disabled>
                        </div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <label>Tax Reg1 : </label>
                      <div class="form-group">                
                          <input class="form-control tax_reg1_class" name="tax_reg1" placeholder="Enter Tax Reg1" type="text" readonly onfocus="this.removeAttribute('readonly');">
                      </div>
                  </td>
                  <td>
                       <label>Tax Reg2 : </label>
                        <div class="form-group">                
                            <input class="form-control tax_reg2_class" name="tax_reg2" placeholder="Enter Tax Reg2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
                  <td>
                        <label>Tax Reg3 : </label>
                        <div class="form-group">                
                            <input class="form-control tax_reg3_class" name="tax_reg3" placeholder="Enter Tax Reg3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                  </td>
              </tr>
              <tbody id="edit_clients_div">

              </tbody>
            </table>
            </div>
          
          <div class="modal-footer">
            
            <div class="col-md-3">
              <label style="float: left;">CRYPT-PIN : </label>
              <div class="form-group">                
                  <input class="form-control" name="crypt_pin" id="edit_crypt_pin" placeholder="Enter Pin" type="password" autocomplete="new-password" required readonly onfocus="this.removeAttribute('readonly');">
                  <input type="hidden" name="id" value="Submit" class="id_class">
              </div>
            </div>
            <div class="col-md-3">
              <input type="submit" style="float: left;margin-top:21px" id="formvalid_id" value="Submit" class="common_black_button">
            </div>
            <div class="col-md-6">
                <div style="font-size: 14px;margin-top: 13px;">
                  <b>Action</b><br/>
                  <a href="javascript:" class="copy_clients fa fa-files-o" data-toggle="tooltip" data-placement="top" title="Copy"></a> &nbsp; 
                  <a href="javascript:" class="deactivate_clients fa fa-check" data-toggle="tooltip" data-placement="top" title="Deactivate This Client"></a> &nbsp; 
                  <a href="javascript:" class="print_clients fa fa-print" data-toggle="tooltip" data-placement="top" title="Print"></a> &nbsp; 
                  <a href="javascript:" class="download_clients fa fa-file-pdf-o" data-toggle="tooltip" data-placement="top" title="PDF"></a>
                </div>
            </div>
          </div>
        @csrf
</form>
      </div>
    </div> -->
  </div>
</div>






<div class="modal fade invoice_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">    
    <div class="editsp">
      <div class="modal-content">
          <div class="modal-header" style="border-bottom: 0px;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title cm_title menu-logo"></h4>
            <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="cm_full_ul active"><a href="#edit_clients_tab" aria-controls="ediclient" role="tab" data-toggle="tab">Edit Client</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#invoice_tab"  aria-controls="invoicetab" role="tab" data-toggle="tab">Invoice</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#timetask_tab" aria-controls="timetask" role="tab" data-toggle="tab">Time Task</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#payroll_tab" aria-controls="payroll" role="tab" data-toggle="tab">Payroll Task</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#notes_tab" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#bank_tab" aria-controls="bank" role="tab" data-toggle="tab">Bank</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#message_tab" aria-controls="message" role="tab" data-toggle="tab">Messages Sent</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#coms_tab" aria-controls="coms" role="tab" data-toggle="tab">Module Coms</a></li>
                  <li role="presentation" class="cm_full_ul"><a href="#statement_tab" aria-controls="statement" role="tab" data-toggle="tab">Statement</a></li>
            </ul>
          </div>
          <div class="tab-content ">
            <div role="tabpanel" class="tab-pane cm_full_content active" id="edit_clients_tab">
              <form id="form-validation" action="<?php echo URL::to('user/update_cm_clients'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">                 
                  <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd">
                  <table class="formtable" style="width:100%">
                    <tr>
                      <td style="border-right: 3px solid;">
                          <div class="row">
                            <div class="col-md-6">
                              <label>Enter Client ID : </label>
                              <div class="row">
                                <div class="col-md-10"> 
                                  <div class="form-group">              
                                    <input class="form-control clientid_class" name="clientid" placeholder="Enter Client ID" type="text" disabled style="width:94%; display:inline;">
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;margin-top: 7px;" title="Add to active client list" />
                                  </div>
                              </div>
                            </div>
                            
                            <div class="col-md-4">
                               <label>Client Added : </label>
                                <div class="form-group">                
                                    <input class="form-control client_added_class" name="client_added_class" placeholder="Client Added" type="text" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                          </div>
                        </td>
                     
                      <td style="border-right: 3px solid ;">
                           <label>Tax Reg2 : </label>
                            <div class="form-group">                
                                <input class="form-control tax_reg2_class" name="tax_reg2" placeholder="Enter Tax Reg2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </td>
                        <td style="border-right: 3px solid ;">
                            <label>Type : </label>
                            <div class="form-group">                
                                <input class="form-control tye_class" name="tye" placeholder="Enter Type" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </td>
                        <td>
                          <label>CRO : </label>
                              <div class="form-group">                
                                  <input class="form-control cro_class" name="cro" placeholder="Enter CRO" type="text" readonly onfocus="this.removeAttribute('readonly');">
                              </div>
                        </td>
                    </tr>
                    <tr>
                      <td style="border-right: 3px solid;">
                          <div class="row">
                            <div class="col-md-6">
                              <label>Enter First Name : </label>
                              <div class="form-group">                
                                  <input class="form-control first_class" name="name" placeholder="Enter First Name" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                              </div>
                            </div>
                            <div class="col-md-6">
                               <label>Enter Surname : </label>
                                <div class="form-group">                
                                    <input class="form-control sur_classs" name="surname" placeholder="Enter Surname" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                          </div>
                        </td>
                        <td style="border-right: 3px solid ;">
                           <label>Tax Reg2 : </label>
                            <div class="form-group">                
                                <input class="form-control tax_reg2_class" name="tax_reg2" placeholder="Enter Tax Reg2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </td>


                        <td style="border-right: 3px solid ;">
                            <label>Select Class : </label>
                            <div class="form-group">                
                                <select class="form-control class_class" name="class">
                                  <option value="">Select Class</option>
                                  <?php
                                  if(($classlist)){
                                    foreach($classlist as $class){
                                  ?>
                                    <option value="<?php echo $class->id; ?>"><?php echo $class->classname?></option>
                                  <?php
                                    }
                                  }
                                  ?>
                                </select>

                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="border-right: 3px solid;">
                            <label>Enter Company Name : </label>
                            <div class="form-group">                
                                <input class="form-control cname_class" name="cname" placeholder="Enter Company Name" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </td>
                        <td style="border-right: 3px solid;">
                              <label>Tax Reg3 : </label>
                              <div class="form-group">                
                                  <input class="form-control tax_reg3_class" name="tax_reg3" placeholder="Enter Tax Reg3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                              </div>
                        </td>
                        <td style="border-right: 3px solid;"></td>
                        <td></td>
                    </tr>
                    <tr>
                      <td style="border-right: 3px solid;">
                            <label>Trade Status : </label>
                            <div class="form-group">                
                                <input class="form-control trade_status_class" name="trade_status" placeholder="Enter Trade Status" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                      </td>
                      <td style="border-right: 3px solid;">
                        <label>Employer No : </label>
                        <div class="form-group">                
                            <input class="form-control employerno_edit_class" name="employer_no" placeholder="Enter Employer No" type="text" autocomplete="off" disabled>
                        </div>
                      </td>
                      <td style="border-right: 3px solid;"></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td style="border-right: 3px solid;">
                          <div class="row">
                            <div class="col-md-6">
                              <label>Enter Primary Email : </label>
                                <div class="form-group">                
                                    <input class="form-control email_class" name="email" placeholder="Enter Email Id" type="email" required readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="col-md-6">
                               <label>Secondary Email : </label>
                                <div class="form-group">                
                                    <input class="form-control semail_class" name="semail" placeholder="Enter Secondary Email" type="email" readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                          </div>
                        </td>
                        <td style="border-right: 3px solid;">
                              
                        </td>
                        <td style="border-right: 3px solid;"></td>
                        <td></td>
                    </tr>

                    <tr>
                      <td style="border-right: 3px solid;">
                        <label>Enter Address : </label>
                        <div class="form-group">                
                            <input class="form-control address1_class" name="address1" placeholder="Enter Address" type="text" required readonly onfocus="this.removeAttribute('readonly');">
                            <input class="form-control address2_class" name="address2" placeholder="Enter Address 2" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            <input class="form-control address3_class" name="address3" placeholder="Enter Address 3" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            <input class="form-control address4_class" name="address4" placeholder="Enter Address 4" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            <input class="form-control address5_class" name="address5" placeholder="Enter Address 5" type="text" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                        </td>
                      <td style="border-right: 3px solid;"></td>
                      <td style="border-right: 3px solid;"></td>
                      <td></td>
                    </tr>

                    <tr>
                        <td style="border-right: 3px solid;">
                          <div class="row">
                          <div class="col-md-6">
                            <label>Phone : </label>
                            <div class="form-group">                
                                <input class="form-control phone_class" name="phone" placeholder="Enter Phone Number" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                          </div>
                          <div class="col-md-6">
                            
                          </div>
                        </div>
                        </td>
                        <td style="border-right: 3px solid;"></td>
                        <td style="border-right: 3px solid;">
                              <label>Directory : </label>
                              <div class="form-group">                
                                  <input class="form-control directory_class" name="directory" placeholder="Enter Directory" type="text" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                              </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                      <td style="border-right: 3px solid;">
                        <div class="row">
                        <div class="col-md-6">
                              <label>Salutation : </label>
                              <div class="form-group">                
                                  <input class="form-control salutation_edit_class" name="salutation" placeholder="Enter Salutation" type="text">
                              </div>
                            </div>
                            <div class="col-md-6">
                            
                          </div>
                        </div>
                        </td>
                        <td style="border-right: 3px solid;"></td>
                        <td style="border-right: 3px solid;">
                            <label>Link Code : </label>
                            <div class="form-group">                
                                <input class="form-control linkcode_class" name="linkcode" placeholder="Enter Link Code" type="text" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </td>
                        <td></td> 
                    </tr>
                                      

                    <tbody id="edit_clients_div">

                    </tbody>
                  </table>
                  </div>
                
                <div class="modal-footer">
                  
                  <div class="col-md-3">
                    <label style="float: left;">Practice Code : </label>
                    <div class="form-group">                
                        <input class="form-control" name="practice_code" id="edit_practice_code" placeholder="Enter Practice Code" type="text" required>
                        
                    </div>
                    <input type="hidden" name="id" value="Submit" class="id_class">
                  </div>
                  <div class="col-md-3">
                    <input type="submit" style="float: left;margin-top:26px" id="formvalid_id" value="Submit" class="common_black_button">
                  </div>
                  <div class="col-md-6">
                      <div style="font-size: 14px;margin-top: 13px;">
                        <b>Action</b><br/>
                        <a href="javascript:" class="copy_clients fa fa-files-o" data-toggle="tooltip" data-placement="top" title="Copy"></a> &nbsp; 
                        <a href="javascript:" class="deactivate_clients fa fa-check" data-toggle="tooltip" data-placement="top" title="Deactivate This Client"></a> &nbsp; 
                        <a href="javascript:" class="print_clients fa fa-print" data-toggle="tooltip" data-placement="top" title="Print"></a> &nbsp; 
                        <a href="javascript:" class="download_clients fa fa-file-pdf-o" data-toggle="tooltip" data-placement="top" title="PDF"></a>
                        <a href="javascript:" class="client_portal_link fa fa-user" data-toggle="tooltip" data-placement="top" title="Client Portal"></a>
                      </div>
                  </div>
                </div>
              @csrf
              </form>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="invoice_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                <a href="javascript:" class="load_all_cm_invoice common_black_button" style="float:left">Load Now</a>
                <h4 class="load_more_cm_invoice" style="margin-bottom: 15px;">List of Invoices sent to this client</h4>
                <div id="invoice_tbody" >
                  
                </div>
                <input type="hidden" class="invoice_client_id" name="">                                   
              </div>
              <div class="modal-footer">
                  <input type="button" class="common_black_button" id="print_selected_invoice" value="Download & Zip Selected Invoices">
                  <input type="button" class="common_black_button" id="save_as_csv_invoice" value="Save as CSV">
                  <input type="button" class="common_black_button" id="save_as_pdf_invoice" value="Save as PDF">
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="timetask_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                <h4 style="margin-bottom: 15px;">List of tasks assigned to this client</h4>
                <div id="timetask_tbody">
                </div>
              </div>
              <div class="modal-footer">
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="payroll_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                  <h4 style="margin-bottom: 15px;">List of Payroll tasks</h4>
                <div id="payroll_tbody">
                  
                </div>                                
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="notes_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                  <h4 style="margin-bottom: 15px;">Notes</h4>
                <div class="modal-body">
             
                  <form action="<?php echo URL::to('user/cm_note_update')?>" method="post">

                     <textarea name="notes" id="editor_2" class="notes_class">
                    </textarea>
                    <input type="hidden" value="" class="clientid_note" name="client_id">
                    <div class="select_button">
                      <ul>
                        <li><input type="submit" class="input_select" name=""></li>
                        <li><a href="javascript:" style="font-size: 15px;" id="print_notes">Print</a></li>
                      </ul>
                    </div> 
                  @csrf
</form>                 
                </div>                                
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="bank_tab">
              <div class="modal-body">
                <div id="bank_detail_body">
                  
                </div> 
              </div>
              <div class="modal-footer">
                <div class="select_button">
                  <ul>                    
                    <li><input type="hidden" class="bank_client_class" name="">
                      <a href="javascript:" class="add_bank_cms" style="font-size: 15px;">Add Bank</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="message_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                  <a href="javascript:" class="load_all_cm_message common_black_button" style="float:left">Load Now</a>
                  <h4 class="load_more_cm_message" style="margin-bottom: 15px;">Messages Sent</h4>
                <div id="message_tbody">
                  
                </div>                                
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="coms_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                <h4 class="load_more_cm_message" style="margin-bottom: 15px;">Module Coms</h4>
                <div id="coms_tbody">
                </div>                                
              </div>
            </div>
            <div role="tabpanel" class="tab-pane cm_full_content" id="statement_tab">
              <div class="modal-body" style="height: 700px; overflow-y: scroll;border-bottom:2px solid #bdbdbd; padding-top: 0px;">
                <h4 class="load_more_cm_message" style="margin-bottom: 15px;">Statement</h4>
                <div id="statement_tbody">

                </div>                                
              </div>
            </div>
          </div>
      </div>         
    </div>
  </div>
</div>

<div class="modal fade bank_modal_cms" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Bank</h4>
                        
        </div>
        <div class="modal-body" >
          <form id="cms_add_bank_form" method="post">
          <div class="form-group">
            Bank Name
              <input type="text" name="bank_name_cms" class="form-control"  id="bank_name_cms" placeholder="Bank Name" required>
            </div>
            <div class="form-group">Bank Account Name
              <input type="text" name="account_name_cms" class="form-control" id="account_name_cms" placeholder="Bank Account Name" required>
            </div>
            <div class="form-group">Bank Account Number
              <input type="number" name="account_number_cms" class="form-control" id="account_number_cms" placeholder="Bank Account Number" required>
            </div>
            
            <input type="hidden" id="bank_current_client_id" name="" />
          @csrf
</form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button bank_submit"  value="Add Bank">
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
  if($year_clients){
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
                Client Management System <span id="client_specific_name"></span>                                
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
              <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/client_request_manager/'.$encode_client_id)?>" style="padding:10px; text-decoration:none;display:none;">
              <i class="fa fa-envelope" title="Clinet Request System" aria-hidden="true"></i>
              </a></div>    
              <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/infile_search?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;display:none;">
              <i class="fa fa-file-archive-o" title="Infile" aria-hidden="true"></i>
              </a></div>    
              <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/key_docs?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;display:none;">
              <i class="fa fa-key" title="Keydocs" aria-hidden="true"></i>
              </a></div>      
              <div style="display:inline-flex;"><a class="quick_links yearend_link" href="<?php echo URL::to('user/yearend_individualclient/'.$year_client_id)?>" style="padding:10px; text-decoration:none;display:none;">
              <i class="fa fa-calendar" title="Yearend Manager" aria-hidden="true"></i>
              </a></div> 
            </span> 
            </h4>
            <div class="col-lg-6" style="padding: 0px; line-height: 35px;">

               <div style="float: left; margin-right: 10px;">
                <label style="float: left;">Search Client ID: </label>
              </div>
              <div style="float: left; margin-right: 6px;">          
                <input type="text" class="form-control client_common_search ui-autocomplete-input" placeholder="Enter Client Name" style="font-weight: 500;margin-bottom: 13px;margin-top:2px;width:250px;float:left" value="" autocomplete="off">
                <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
                <input type="button" class="common_black_button load_single_client" value="Load" style="width: 100px;margin-top: 3px;">
                <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="" name="client_id">
              </div>

              <div style="float: left;">
                <input type="button" class="common_black_button load_all_clients" value="Load All Clients" style="float: left;width:100%;margin-top: 3px;">
              </div>
              <div style="float: left; padding-left: 10px; padding-top: 10px;">
                <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete">Hide Inactive</label>
              </div>
            </div>
            
            <div class="col-lg-6 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                <li style="float:right"><a href="javascript:" class="addclientbutton" style="font-weight: 600;" data-toggle="modal" data-target=".bs-example-modal-sm" style="font-weight: 600;">Add Client</a></li>
                <li style="float:right"><a href="javascript:" class="importclientbutton" style="font-weight: 600;">Import Client</a></li>
                <div class="import_div" style="display:none">
                    <label>DO YOU WANT TO IMPORT NEW RECORDS ONLY</label><br/>
                    <input type="radio" name="import_client" id="import_yes" value="1"><label for="import_yes">Yes</label>
                    <input type="radio" name="import_client" id="import_no" value="1"><label for="import_no">No</label>
                </div>
              </ul>
            </div>
            <br/>
            
  </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:30px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    <?php }
    if(isset($_GET['email_sent'])) { DB::table('cm_email_attachment')->delete(); ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Email Sent successfully. </p>
        <script>
            window.history.replaceState(null, null, "<?php echo URL::to('user/client_management'); ?>");
        </script>
    <?php } ?>
    </div> 

<table class="display nowrap fullviewtablelist own_table_white" id="client_expand" width="100%">
                        <thead>
                        <tr style="background: #fff;">
                            <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Active Client</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Company Name</th>
                            <th style="text-align: left; width:300px; max-width: 300px;">Address</th>
                            <th style="text-align: left;">Primary Email</th> 
                            <th style="text-align: left;">Telephone</th>                            
                            <th style="text-align: left;">Practice Code</th>
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align:center; font-weight: normal !important;">No Data found</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                        </tbody>
                    </table>
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
      <td style="text-align: left;border:1px solid #000;">Client ID</td>
      <td style="text-align: left;border:1px solid #000;">FIrstName</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">Address</td>
      <td style="text-align: left;border:1px solid #000;">EMail ID</td>
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



<div class="modal_load modal_client_all"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Please wait until All clients are loaded... </h5></div>

<div class="modal_load modal_single_client"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Please wait until this client is loaded... </h5></div>

<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script type="text/javascript">
<?php

if(!empty($_GET['divid']))
{
  $divid = $_GET['divid'];
  ?>
  $(function() {
    $("body").addClass("loading");
    setTimeout(function(){ 
      if($("#<?php echo $divid; ?>").length > 0)
      {
        $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) ); 
        <?php if(Session::get('edit_message')){ ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green><?php echo Session::get('edit_message'); ?></p>",width:"30%",fixed:true});
        <?php } else if(Session::get('edit_error')) { ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00><?php echo Session::get('edit_error'); ?></p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['activate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Client Activated Successfully.</p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['deactivate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Client Deactivated Successfully.</p>",width:"30%",fixed:true});
        <?php } ?>
      }
      $("body").removeClass("loading"); 
       window.history.replaceState(null, null, "<?php echo URL::to('user/client_management'); ?>");
    }, 5000); 
  });
  <?php
} ?>
// $(window).scroll(function(e){
//   var len = $(".load_more").length;
//   if(len > 0)
//   {
//     var off = $(".load_more").offset();
//     var scroll = $(window).scrollTop();
//     var h = screen.height - parseInt(220);
//     var screen_height = $(document).height();

//     var final_scroll = parseInt(scroll) + parseInt(h);
//     if(off.top <= final_scroll)
//     {
//       $("body").addClass("loading");
//       doSomething();
//     }
//   }
// });
// function doSomething() { 
//     var paginate = $("#pagination").val();
//     var count = parseInt(paginate) + parseInt(1);
//     var base_url = "<?php echo URL::to('user/clientmanagement_paginate'); ?>";

//     $("#pagination").val(count);
//     $.ajax({
//       url: base_url,
//       data: {page:count},
//       type: "get",
//       success:function(result){
//         $("body").removeClass("loading");
//         $("body").find(".load_more").removeClass("load_more");
//         $("#clients_tbody").append(result);
//         var table = $('#client_expand').DataTable();
 
//         table.fixedHeader.adjust();
//       }
//     });
//   }
$( function() {
  $(".client_added" ).datepicker({ dateFormat: 'dd/MM/yy',monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                 "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], });
  $(".client_added_class" ).datepicker({ dateFormat: 'dd/MM/yy',monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                 "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], })
});
$(function () {       
    $('.ard_add').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'L',
        format: "DD/MM/YYYY",
    });
    $('.ard_class').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'L',
        format: "DD/MM/YYYY",
    });
});
$(document).ready(function(){   
    $('#images').on('change',function(){
      $("body").addClass("loading");
      setTimeout(function(){ 
          $('#multiple_upload_form').ajaxForm({
              //display the uploaded images
              target:'#images_preview',
              beforeSubmit:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              success:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              error:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              }
          }).submit();
      }, 2000); 
  });
});
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip(); 
  var hf_clientfrom = $("#hf_clientfrom").val();
  var hf_clientfrom_name = $("#hf_clientfrom_name").val();
  var hf_clientspecificname = $("#hf_clientspecificname").val();
  if(hf_clientfrom!='clientiden'){
    $(".quick_links").show();
    $("#client_expand").dataTable().fnDestroy();
    $(".client_common_search").val(hf_clientfrom_name);
    $("#client_search_hidden_infile").val(hf_clientfrom);
    $("#client_specific_name").text(' for '+hf_clientspecificname);
    $(".load_single_client").click();
  }  
  else{
    $(".quick_links").hide();
  }
  if($("#show_incomplete").is(':checked'))
  {
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).hide();
        }
    });
  }
  else{
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).show();
        }
    });
  }
});

$(window).click(function(e) {
  if($(e.target).hasClass('client_portal_link')) {
    var clientid = $(".invoice_client_id").val();
    window.open('<?php echo URL::to('user/cms_client_portal?client_id='); ?>'+clientid, '_blank');
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
if($(e.target).hasClass('load_all_clients'))
{
  $(".modal_client_all").show();
  setTimeout(function() {
      $("#client_expand").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/load_all_clients_cm_system'); ?>",
        type:"post",
        success:function(result)
        {
          $(".quick_links").hide();          
          $("#client_specific_name").text('');
          $("#hidden_client_id").val("all");
          $("#clients_tbody").html(result);
          $("#client_search_hidden_infile").val("");
          $(".client_common_search").val("");
          $('#client_expand').DataTable({
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

          $(".modal_client_all").hide();
        }
      })
  },1000);
}

if($(e.target).hasClass('load_single_client'))
{
  var client_id = $("#client_search_hidden_infile").val();
  if(client_id == "")
  {
    alert("Please select the client and then click on the load button");
  }
  else{
    $(".modal_single_client").show();
    $("#client_expand").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/load_single_cm_system'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $("#hidden_client_id").val(client_id);
        $("#clients_tbody").html(result);
        $('#client_expand').DataTable({
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

        $(".modal_single_client").hide();
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
      }
    })
  }
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
          $(".statement_p").html("Statements will be sent to this Client as part of the automated process");
          $(".statement_p").css("color","green");
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
          $(".statement_p").html("Statements will NOT be sent to this client as part of the automated process");
          $(".statement_p").css("color","#f00");
        }
      })
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
  if($(e.target).hasClass('edit_task_module'))
  {
    var type = $(e.target).attr("data-type");
    var taskid = $(e.target).attr("data-element");
    var salutation = $(e.target).attr("data-salutation");
    var primary = $(e.target).attr("data-primary");
    var secondary = $(e.target).attr("data-secondary");

    $("#task_type").val(type);
    $("#task_id_module").val(taskid);
    $("#salutation_module").val(salutation);
    $("#pemail_module").val(primary);
    $("#semail_module").val(secondary);

    $("#module_modal").modal("show");
  }
  if(e.target.id == "submit_module_update")
  {
    var type = $("#task_type").val();
    var taskid = $("#task_id_module").val();
    var salutation = $("#salutation_module").val();
    var primary = $("#pemail_module").val();
    var secondary = $("#semail_module").val();

    $.ajax({
      url:"<?php echo URL::to('user/update_pms_vat_module'); ?>",
      type:"post",
      data:{type:type,taskid:taskid,salutation:salutation,primary:primary,secondary:secondary},
      success: function(result)
      {
        if(type == "2")
        {
          $(".vat_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".vat_module_"+taskid).find(".primary_mod").html(primary);
          $(".vat_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else if(type == "3")
        {
          $(".statement_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".statement_module_"+taskid).find(".primary_mod").html(primary);
          $(".statement_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else if(type == "4")
        {
          $(".keydocs_module_"+taskid).find(".keydocs_mod").html(salutation);
          $(".keydocs_module_"+taskid).find(".primary_mod").html(primary);
          $(".keydocs_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else{
          $(".pms_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".pms_module_"+taskid).find(".primary_mod").html(primary);
          $(".pms_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }

        $("#module_modal").modal("hide");
      }
    })
  }
  if($(e.target).hasClass('disable_ard_class'))
  {
    if($(e.target).is(":checked"))
    {
      $(".ard_class").addClass("disabled_ard");
    }
    else{
      $(".ard_class").removeClass("disabled_ard");
    }
  }
  if($(e.target).hasClass('view_message'))
  {
    var message_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_messageus_sample_screen'); ?>",
      type:"post",
      data:{message_id:message_id},
      success:function(result)
      {
        $(".show_messageus_last_screen_modal").modal("show");
        $("#show_messageus_body").html(result);
      }
    })
  }
  if(e.target.id == "print_selected_invoice")
  {
    var countval = $(".invoice_check:checked").length;
    if(countval == 0)
    {
      alert("Please select atleast one checkbox to download the invoice.");
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        var ids = '';
        $(".invoice_check:checked").each(function() {
          var invoiceid = $(this).attr("data-element");
          if(ids == "")
          {
            ids = invoiceid;
          }
          else{
            ids = ids+','+invoiceid;
          }
        });
        $.ajax({
          url:"<?php echo URL::to('user/print_selected_invoice'); ?>",
          type:"get",
          data:{ids:ids},
          dataType:"json",
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result['time'],result['company']);
            $("body").removeClass("loading");
          }
        });
      },1000);
    }
  }
  if(e.target.id == "print_notes")
  {
    var texteditor = CKEDITOR.instances['editor_2'].getData();
    var w = window.open("about:blank");
    w.document.write(texteditor);
    w.document.close();
    w.focus();
    w.print();
    w.close();
  }
  if(e.target.id == "select_all_fields")
  {
    if($(e.target).is(':checked'))
    {
      $(".select_import_client").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_import_client").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if($(e.target).hasClass('client_statement'))
  {
    if($(e.target).is(":checked"))
    {
      $('body').addClass('loading');
      var client_id = $(e.target).attr('data-element');
      var value = $(e.target).val();
      $.ajax({
        url:"<?php echo URL::to('user/cm_statement_update'); ?>",
        type:"post",
        data:{id:client_id,value:"yes"},
        success: function(result)
        {
          $('body').removeClass('loading');
        }
      });
    }
    else{
      $('body').addClass('loading');
      var client_id = $(e.target).attr('data-element');
      var value = $(e.target).val();
      $.ajax({
        url:"<?php echo URL::to('user/cm_statement_update'); ?>",
        type:"post",
        data:{id:client_id,value:"no"},
        success: function(result)
        {
          $('body').removeClass('loading');
        }
      });
    }
  }
  if(e.target.id == "select_all_email_class")
  {
    if($(e.target).is(":checked"))
    {
      $(".select_email_client").each(function() {
        $(this).prop("checked",true);
      });
      $(".created_email_class").each(function() {
        $(this).prop("checked",true);
      });
      $(".selectall_div").show();
    }
    else{
      $(".select_email_client").each(function() {
        $(this).prop("checked",false);
      });
      $(".created_email_class").each(function() {
        $(this).prop("checked",false);
      });
      $(".selectall_div").hide();
    }
  }
  else if(e.target.id == "also_deactivated_clients")
  {
    if($(e.target).is(":checked"))
    {
      $("#deactive_clients_dont").prop("checked",false);
      $(".deactive_tr").each(function() {
        $(this).show();
      });
      $(".selectall_div").hide();
    }
    else{
      $("#deactive_clients_dont").prop("checked",true); 
      $(".deactive_tr").each(function() {
        $(this).hide();
      });
      $(".selectall_div").hide();
    }
  }
  else{
    $(".selectall_div").hide();
  }

  if(e.target.id == "import_existing_clients")
  {
    var checkcount = $(".select_import_client:checked").length;
    if(checkcount == 0)
    {
      alert("Please Select atleast one field to update.");
      return false;
    }
    else{
      //$("#import_existing_clients_form").submit();
    }
  }
  if(e.target.id == "import_no")
  {
    $(".import_div").hide();
    $("#import_client_modal").modal("show");
  }
  if(e.target.id == "import_yes")
  {
    $(".import_div").hide();
    $("#import_newclient_modal").modal("show");
  }
  if($(e.target).hasClass('report_button'))
  {
    $("body").addClass("loading");
    setTimeout(function(){
      $.ajax({
        url:"<?php echo URL::to('user/get_cm_report_clients'); ?>",
        type:"get",
        success: function(result){
          $("#report_tbody").html(result);
          $(".report_modal").modal("show");
          $("body").removeClass("loading");
        }
      })
    }, 2000);
  }
  if($(e.target).hasClass('importclientbutton'))
  {
    $(".import_div").toggle();
  }

  if($(e.target).hasClass('fileattachment'))
  {
    e.preventDefault();
    var element = $(e.target).attr('data-element');
    $('body').addClass('loading');
    setTimeout(function(){
      SaveToDisk(element,element.split('/').reverse()[0]);
      $('body').removeClass('loading');
      }, 3000);
    return false; //this is critical to stop the click event which will trigger a normal file download
  }
  
  if(e.target.id == "deactive_clients_dont")
  {
    if($(e.target).is(":checked"))
    {
      $("#also_deactivated_clients").prop("checked",false); 
      $(".deactive_tr").each(function() {
        $(this).hide();
      });
    }
    else{
      $("#also_deactivated_clients").prop("checked",true); 
      $(".deactive_tr").each(function() {
        $(this).show();
      });
    }
  }
  if(e.target.id == "crypt_submit")
  {
    if (CKEDITOR.instances.email_content) CKEDITOR.instances.email_content.destroy();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/get_cm_bulk_clients'); ?>",
      type:"get",
      success: function(result){
        $("#bulk_email_tbody").html(result);
        $(".bulk_email_modal").modal("show");
        $("body").removeClass("loading");
        setTimeout(function(){  

           CKEDITOR.replace('email_content',

           {

            height: '150px',

           }); 

        },500);
      }
    });
  }
  if(e.target.id == "send_bulk_email")
  {
    
    var subject = $("#email_subject").val();
    var content = CKEDITOR.instances['email_content'].getData();
    var attachments = $(".email_attachments").length;
    var clients = $(".select_email_client:checked").length;
    var timeval = "<?php echo time(); ?>";
    if(subject != "" && content != "" && clients > 0)
    {
      $("body").addClass("loading");
      if($("#secondary_email_sent").is(":checked"))
      {
        var secondary = 1;
      }
      else{
        var secondary = 0;
      }
      var option_length = $(".select_email_client:checked").length;
      $(".select_email_client:checked").each(function(i, value) {
          var id = $(this).attr("data-element");
          var keyi = parseInt(i) + parseInt(1);
          if($(this).parents("tr").is(":visible"))
          {
            setTimeout(function(){
                $.ajax({
                  url:"<?php echo URL::to('user/cm_bulk_email'); ?>",
                  type:"post",
                  data:{client_id:id,secondary:secondary,content:content,subject:subject,timeval:timeval},
                  success: function(result) {
                    
                    if(option_length == keyi)
                    {
                      $("body").removeClass("loading");
                      $(".bulk_email_modal").modal("hide");
                      //window.location.replace("<?php echo URL::to('user/client_management?email_sent=1'); ?>");
                      $.colorbox({html:'<p style="text-align:center;font-size:18px;font-weight:600;color:green">Email Sent Successfully.</p>',width:"30%",fixed:true});
                    }
                  }
                });
            },2000 + ( i * 2000 ));
          }
      });
    }
    else{
      alert("Please Select the Clients and Fill the Email Content.");
    }
  }
  if(e.target.id == "save_as_pdf")
  {
    $("#report_pdf_type_two_tbody").html('');
    if($(".select_client:checked").length)
    {
      $("body").addClass("loading");
        var checkedvalue = '';
        var size = 100;
        $(".select_client:checked").each(function() {
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
                url:"<?php echo URL::to('user/cm_report_pdf_type_2'); ?>",
                type:"post",
                data:{value:imp,type:2},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody").append(result);
                  
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/download_report_pdfs'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
                      }
                    });
                  }
                }
              });
            }, 3000);
        });
        
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }

  if(e.target.id == "save_as_csv")
  {
    $("body").addClass("loading");
    if($(".select_client:checked").length)
    {
      var checkedvalue = '';
      $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/cm_report_csv'); ?>",
        type:"post",
        data:{value:checkedvalue},
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/CM_Report.csv",'CM_Report.csv');
        }
      });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }
  if(e.target.id == "select_all_class")
  {
    if($(e.target).is(":checked"))
    {
      $(".select_client").each(function() {
        $(this).prop("checked",true);
      });
      $(".created_class").each(function() {
        $(this).prop("checked",true);
      });

      var count = $(".select_client:checked").length;
      $("#hidden_client_count").val(count);
      if(count > 100)
      {
        var alertshown = $("#show_alert").val();
        if(alertshown == "")
        {
            alert("Please note that only 100 records can be exported into the PDF at a time hence the next set of records will be downloaded in another PDF and so on.");
            $("#show_alert").val("1");
        }
      }
    }
    else{
      $(".select_client").each(function() {
        $(this).prop("checked",false);
      });
      $(".created_class").each(function() {
        $(this).prop("checked",false);
      });
      $("#hidden_client_count").val('');
    }
  }
  if(e.target.id == "select_all_import_class")
  {
    if($(e.target).is(":checked"))
    {
      $(".select_import_client").each(function() {
        $(this).prop("checked",true);
      });
      $(".created_import_class").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_import_client").each(function() {
        $(this).prop("checked",false);
      });
      $(".created_import_class").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  
  if($(e.target).hasClass('select_client'))
  {
    if($(e.target).is(":checked"))
    {
      var classid = $(e.target).attr("data-element");
      var classfull = $(".class_"+classid).length;
      var classchecked = $(".class_"+classid+":checked").length;
      var fulldata = $(".select_client").length;
      var fulldatachecked = $(".select_client:checked").length;
      if(classfull == classchecked)
      {
        $("#created_class_"+classid).prop("checked",true);
      }
      if(fulldata == fulldatachecked)
      {
        $("#select_all_class").prop("checked",true);
      }

      var count = $(".select_client:checked").length;
      $("#hidden_client_count").val(count);
      if(count > 100)
      {
        var alertshown = $("#show_alert").val();
        if(alertshown == "")
        {
            alert("Please note that only 100 records can be exported into the PDF at a time hence the next set of records will be downloaded in another PDF and so on.");
            $("#show_alert").val("1");
        }
      }
    }
    else{
      $("#select_all_class").prop("checked",false);
      var classid = $(e.target).attr("data-element");
      $("#created_class_"+classid).prop("checked",false);
    }
  }
  if($(e.target).hasClass('select_email_client'))
  {
    if($(e.target).is(":checked"))
    {
      var classid = $(e.target).attr("data-element");
      var classfull = $(".email_class_"+classid).length;
      var classchecked = $(".email_class_"+classid+":checked").length;
      var fulldata = $(".select_email_client").length;
      var fulldatachecked = $(".select_email_client:checked").length;
      if(classfull == classchecked)
      {
        $("#created_email_class_"+classid).prop("checked",true);
      }
      if(fulldata == fulldatachecked)
      {
        $("#select_all_email_class").prop("checked",true);
      }
    }
    else{
      $("#select_all_email_class").prop("checked",false);
      var classid = $(e.target).attr("data-element");
      $("#created_email_class_"+classid).prop("checked",false);
    }
  }
  
  if($(e.target).hasClass('created_class'))
  {
    if($(e.target).is(":checked"))
    {
      var classid = $(e.target).val();
      $(".class_"+classid).each(function() {
        $(this).prop("checked",true);
      });
      var fulldata = $(".select_client").length;
      var fulldatachecked = $(".select_client:checked").length;
      if(fulldata == fulldatachecked)
      {
        $("#select_all_class").prop("checked",true);
      }

      var count = $(".select_client:checked").length;
      $("#hidden_client_count").val(count);
      if(count > 100)
      {
        var alertshown = $("#show_alert").val();
        if(alertshown == "")
        {
            alert("Please note that only 100 records can be exported into the PDF at a time hence the next set of records will be downloaded in another PDF and so on.");
            $("#show_alert").val("1");
        }
      }
    }
    else{
      $("#select_all_class").prop("checked",false);
      var classid = $(e.target).val();
      $(".class_"+classid).each(function() {
        $(this).prop("checked",false);
      });
    }

    
  }
  if($(e.target).hasClass('created_email_class'))
  {
    if($(e.target).is(":checked"))
    {
      var classid = $(e.target).val();
      $(".email_class_"+classid).each(function() {
        $(this).prop("checked",true);
      });
      var fulldata = $(".select_email_client").length;
      var fulldatachecked = $(".select_email_client:checked").length;
      if(fulldata == fulldatachecked)
      {
        $("#select_all_email_class").prop("checked",true);
      }
    }
    else{
      $("#select_all_email_class").prop("checked",false);
      var classid = $(e.target).val();
      $(".email_class_"+classid).each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if($(e.target).hasClass('created_import_class'))
  {
    if($(e.target).is(":checked"))
    {
      var classid = $(e.target).val();
      $(".import_class_"+classid).each(function() {
        $(this).prop("checked",true);
      });
      var fulldata = $(".select_import_client").length;
      var fulldatachecked = $(".select_import_client:checked").length;
      if(fulldata == fulldatachecked)
      {
        $("#select_all_import_class").prop("checked",true);
      }
    }
    else{
      $("#select_all_import_class").prop("checked",false);
      var classid = $(e.target).val();
      $(".import_class_"+classid).each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(':checked'))
    {
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).hide();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_cm_incomplete_status'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
        }
      });
    }
    else{
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).show();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_cm_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }
  if(e.target.id == "search_button")
  {
    var input = $(".search_input_class").val();
    var select = $(".search_select_class").val();
    if(input == "" || select == "")
    {
      alert("Please Type the Input and choose the search type to serch the clients.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/cm_search_clients'); ?>",
        type:"get",
        data:{input:input,select:select},
        success: function(result) {
          $("#clients_tbody").html(result);
          $("#client_expand_info").hide();
          $(".dataTables_paginate").hide();
          if($("#show_incomplete").is(':checked'))
          {
            $(".edit_task").each(function() {
                if($(this).hasClass('disabled'))
                {
                  $(this).hide();
                }
            });
          }
          else{
            $(".edit_task").each(function() {
                if($(this).hasClass('disabled'))
                {
                  $(this).show();
                }
            });
          }
        }
      });
    }
  }
  if($(e.target).hasClass('addclientbutton')) {
    $(".bs-example-modal-sm").find(".editsp").hide();
    $(".bs-example-modal-sm").find(".addsp").show();
    $(".bs-example-modal-sm").find(".modal-title").html("Add Clients");
    $(".ard_add").prop("disabled",true);
    $(".disable_ard_add").prop("checked",true);
  }
  if($(e.target).hasClass('deactivate_clients'))
  {
    var client_id = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('user/cm_status_clients'); ?>",
        type:"post",
        data:{hidden_status:1,client_id:client_id},
        success: function(result)
        {
          if(result == 1)
          {
            window.location.replace("<?php echo URL::to('user/client_management?deactivate=1&divid=clientidtr_'); ?>"+atob(client_id));
          }
          else{
            window.location.replace("<?php echo URL::to('user/client_management?status_pin_invalid=1&divid=clientidtr_'); ?>"+atob(client_id));
          }
        }
      })
  }
  if($(e.target).hasClass('activate_clients'))
  {
    var client_id = $(e.target).attr("data-element");

      $.ajax({
        url:"<?php echo URL::to('user/cm_status_clients'); ?>",
        type:"post",
        data:{hidden_status:0,client_id:client_id},
        success: function(result)
        {
          if(result == 1)
          {
            window.location.replace("<?php echo URL::to('user/client_management?activate=1&divid=clientidtr_'); ?>"+atob(client_id));
          }
          else{
            window.location.replace("<?php echo URL::to('user/client_management?status_pin_invalid=1&divid=clientidtr_'); ?>"+atob(client_id));
          }
        }
      })
  }
  if($(e.target).hasClass('download_clients'))
  {
    $("body").addClass("loading");
    var editid = $(e.target).attr("data-element");
      var checkedvalue = atob(editid);
      $.ajax({
        url:"<?php echo URL::to('user/cm_report_pdf'); ?>",
        type:"post",
        data:{value:checkedvalue,type:1},
        success: function(result)
        {
          $(".bs-example-modal-sm").modal("hide");
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      });
  }
  
  if($(e.target).hasClass('print_clients'))
  {
    $("body").addClass("loading");
     $("#print_image").show();
    var editid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/cm_print_details'); ?>",
      type:"post",
      dataType:"json",
      data:{editid: editid},
      success: function(result)
      {
        $("#print_image").css({"width":result['width'],"height":result['height']});
        $("#print_image").html(result['htmlcontent']);

        html2canvas(document.querySelector("#print_image")).then(canvas => {
            document.body.appendChild(canvas);
            var data = canvas.toDataURL('image/png');
            $.ajax({
              url:"<?php echo URL::to('user/save_image'); ?>",
              type:"post",
              data:{data:data},
              success: function(result)
              {
                $(".bs-example-modal-sm").modal("hide");
                $("#print_image").hide();
                $("canvas").detach();
                setTimeout(function(){ 
                  $("body").removeClass("loading");
                    $("#coupon").off("load").on("load", function() {
                      window.print();
                    }).attr("src", "<?php echo URL::to('uploads/print_image/photo.png'); ?>");
                }, 3000);
              }
            });
        });
      }
    })
  }
  if($(e.target).hasClass('copy_clients')) {
    var editid = $(e.target).attr("data-element");
    $.ajax({
        url: "<?php echo URL::to('user/copy_cm_client') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
          $(".invoice_model").modal("hide");
           $(".bs-example-modal-sm").modal("show");
           $(".bs-example-modal-sm").find(".modal-title").html("You are about to create a copy/clone of this client - are you sure you want to continue?");
           $(".bs-example-modal-sm").find(".editsp").hide();
           $(".bs-example-modal-sm").find(".addsp").show();   
           $(".client_id_add").val(result['clientid']);
           $(".firstname_add").val(result['firstname']);
           $(".surname_add").val(result['surname']);
           $(".company_add").val(result['company']);
           $(".address1_add").val(result['address1']);
           $(".address2_add").val(result['address2']);
           $(".address3_add").val(result['address3']);
           $(".address4_add").val(result['address4']);
           $(".address5_add").val(result['address5']);
           $(".pemail_add").val(result['email']);
           $(".tye_add").val(result['tye']);
           $(".class_add").val(result['active']);
           $(".reg1_add").val(result['tax_reg1']);
           $(".reg2_add").val(result['tax_reg2']);
           $(".reg3_add").val(result['tax_reg3']);

           $(".semail_add").val(result['email2']);
           $(".phone_add").val(result['phone']);
           $(".link_add").val(result['linkcode']);
           
           $(".cro_add").val(result['cro']);
           $(".ard_add").val(result['ard']);
           $(".status_add").val(result['trade_status']);
           $(".directory_add").val(result['directory']);
           $("#client_id_div").show();
           $(".client_id_add").val(result['clientid']);

           <?php
           $fields = DB::table('cm_fields')->where('status',0)->get();
           if(($fields))
           {
            foreach($fields as $fld)
            {
              $fieldval = $fld->name;
              
              if($fld->field == "2"){
                ?>
                if(result['<?php echo $fieldval; ?>'] == "yes")
                {
                  $("#<?php echo $fieldval; ?>_yes").prop("checked",true);
                }
                else if(result['<?php echo $fieldval; ?>'] == "no"){
                  $("#<?php echo $fieldval; ?>_no").prop("checked",true);
                }
                <?php
              }
              else if($fld->field == "4"){
                
              }
              else{
                ?>
                    $(".<?php echo $fieldval; ?>_add").val(result['<?php echo $fieldval; ?>']);
                <?php
              }
            }
           }
           ?>
      }
    });
  }
  if($(e.target).hasClass('edit_client')) {
    $("body").addClass("loading");
    setTimeout(function(){ 
      var editid = $(e.target).attr("id");
      $(".copy_clients").attr("data-element", editid);
      
      $(".print_clients").attr("data-element", editid);
      $(".download_clients").attr("data-element", editid);
      $.ajax({
          url: "<?php echo URL::to('user/edit_cm_client') ?>"+"/"+editid,
          dataType:"json",
          type:"post",
          success:function(result){
             $(".bs-example-modal-sm").modal("show");
             $(".bs-example-modal-sm").find(".modal-title").html("Edit Client");
             $(".bs-example-modal-sm").find(".editsp").show();
             $(".bs-example-modal-sm").find(".addsp").hide();   
             $(".clientid_class").val(result['clientid']);
             $(".first_class").val(result['firstname']);
             $(".sur_classs").val(result['surname']);
             $(".cname_class").val(result['company']);
             $(".address1_class").val(result['address1']);
             $(".address2_class").val(result['address2']);
             $(".address3_class").val(result['address3']);
             $(".address4_class").val(result['address4']);
             $(".address5_class").val(result['address5']);
             $(".email_class").val(result['email']);
             $(".tye_class").val(result['tye']);
             $(".class_class").val(result['active']);
             $(".tax_reg1_class").val(result['tax_reg1']);
             $(".tax_reg2_class").val(result['tax_reg2']);
             $(".tax_reg3_class").val(result['tax_reg3']);

             $(".semail_class").val(result['email2']);
             $(".phone_class").val(result['phone']);
             $(".linkcode_class").val(result['linkcode']);

             $(".employerno_edit").val(result['employer_no']);
             $(".salutation_edit").val(result['salutation']);
             
             $(".cro_class").val(result['cro']);
             $(".ard_class").val(result['ard']);
             $(".trade_status_class").val(result['trade_status']);
             $(".directory_class").val(result['directory']);

             $(".employerno_edit_class").val(result['employer_no']);
             $(".salutation_edit_class").val(result['salutation']);
             $("#edit_practice_code").val(result['practice_code']);

             $(".id_class").val(result['id']);
             if(result['status'] == 1)
             {
                $(".deactivate_clients").addClass("activate_clients");
                $(".deactivate_clients").addClass("fa-times");
                $(".activate_clients").removeClass("deactivate_clients");
                $(".activate_clients").removeClass("fa-check");
                $(".activate_clients").attr("data-element", editid);
                $(".activate_clients").attr("title", 'Activate This Client');
             }
             if(result['status'] == 0)
             {
                $(".activate_clients").addClass("deactivate_clients");
                $(".deactivate_clients").addClass("fa-check");
                $(".deactivate_clients").removeClass("activate_clients");
                $(".deactivate_clients").removeClass("fa-times");
                $(".deactivate_clients").attr("data-element", editid);
                $(".deactivate_clients").attr("title", 'Deactivate This Client');
             }
             $("#edit_clients_div").html(result['htmlcontent']);
             $("body").removeClass("loading");
        }
      });
    }, 2000);
  }


  if($(e.target).hasClass('invoice_class')) {
    $("body").addClass("loading");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
    setTimeout(function(){ 
        var editid = $(e.target).attr("id");
          $(".copy_clients").attr("data-element", editid);
          $(".print_clients").attr("data-element", editid);
          $(".download_clients").attr("data-element", editid);
          $.ajax({
              url: "<?php echo URL::to('user/cm_client_invoice') ?>",
              data:{id:editid},
              dataType:"json",
              type:"post",
              success:function(result){
                 $(".invoice_model").modal("show");
                 $(".cm_title").html(result['clientid']+' - '+result['company']);
                 $(".invoice_model").find(".editsp").show();

                 $(".load_more_cm").hide();
                 $(".cm_full_ul").removeClass("active");
                 $(".cm_full_content").removeClass("active");

                 $(".cm_full_ul:first").addClass("active");
                 $(".cm_full_content:first").addClass("active");

                 $(".load_all_cm_invoice").attr("data-element",editid);
                 $(".load_all_cm_invoice").show();

                 $(".load_all_cm_message").attr("data-element",editid);
                 $(".load_all_cm_message").show();

                 $(".load_more_cm_invoice").hide();
                 $(".load_more_cm_message").hide();

                 $("#invoice_tbody").html("");
                 $("#message_tbody").html("");

                 $("#payroll_tbody").html(result['payrolloutput']);
                 $("#timetask_tbody").html(result['timetaskoutput']);
                 $("#bank_detail_body").html(result['outputbank']);
                 $(".bank_client_class").val(result['bank_client_id']);
                 $(".invoice_client_id").val(result['clientid']);
                 $("#coms_tbody").html(result['outputmodule']);
                 $("#statement_tbody").html(result['outputstatement']);

                 CKEDITOR.replace('editor_2',
                 {
                  height: '150px',
                 });


               $(".clientid_class").val(result['clientid']);
               $(".client_added_class").val(result['client_added']);
               $(".first_class").val(result['firstname']);
               $(".sur_classs").val(result['surname']);
               $(".cname_class").val(result['company']);
               $(".address1_class").val(result['address1']);
               $(".address2_class").val(result['address2']);
               $(".address3_class").val(result['address3']);
               $(".address4_class").val(result['address4']);
               $(".address5_class").val(result['address5']);
               $(".email_class").val(result['email']);
               $(".tye_class").val(result['tye']);
               $(".class_class").val(result['active']);
               $(".tax_reg1_class").val(result['tax_reg1']);
               $(".tax_reg2_class").val(result['tax_reg2']);
               $(".tax_reg3_class").val(result['tax_reg3']);
               $("#edit_practice_code").val(result['practice_code']);

               CKEDITOR.instances['editor_2'].setData(result['client_note']);
               
               $(".clientid_note").val(result['clientid']);

               


               $(".semail_class").val(result['email2']);
               $(".phone_class").val(result['phone']);
               $(".linkcode_class").val(result['linkcode']);
               
               $(".cro_class").val(result['cro']);
               $(".ard_class").val(result['ard']);
               $(".trade_status_class").val(result['trade_status']);
               $(".directory_class").val(result['directory']);
               $(".employerno_edit_class").val(result['employer_no']);
                 $(".salutation_edit_class").val(result['salutation']);
               $(".id_class").val(result['id']);
               if(result['active'] == 2)
               {
                  $(".deactivate_clients").addClass("activate_clients");
                  $(".deactivate_clients").addClass("fa-times");
                  $(".activate_clients").removeClass("deactivate_clients");
                  $(".activate_clients").removeClass("fa-check");
                  $(".activate_clients").attr("data-element", editid);
                  $(".activate_clients").attr("title", 'Activate This Client');
               }
               else
               {
                  $(".activate_clients").addClass("deactivate_clients");
                  $(".deactivate_clients").addClass("fa-check");
                  $(".deactivate_clients").removeClass("activate_clients");
                  $(".deactivate_clients").removeClass("fa-times");
                  $(".deactivate_clients").attr("data-element", editid);
                  $(".deactivate_clients").attr("title", 'Deactivate This Client');
               }
               $("#edit_clients_div").html(result['htmlcontent']);
               $(".ard_class").addClass("disabled_ard");
                $(".disable_ard_class").prop("checked",true);
           
               $("body").removeClass("loading");
               

               $('#timetask_expand').DataTable({
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

               $('#payroll_expand').DataTable({
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
               $('#module_expand').DataTable({
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

            $('#bank_expand_cms').DataTable({
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
             




          }
        });
     }, 2000);
    
  }

  if($(e.target).hasClass('load_all_cm_invoice')) {
    $("body").addClass("loading");
    setTimeout(function(){ 
        var editid = $(e.target).attr("data-element");
          $(".copy_clients").attr("data-element", editid);
          $(".print_clients").attr("data-element", editid);
          $(".download_clients").attr("data-element", editid);
          $.ajax({
              url: "<?php echo URL::to('user/cm_load_all_client_invoice') ?>",
              data:{id:editid},
              dataType:"json",
              type:"post",
              success:function(result){
                $(".load_more_cm_invoice").show();
                $(".load_all_cm_invoice").hide();

                $("#invoice_tbody").html(result['invoiceoutput']);
           
                $("body").removeClass("loading");
                $('#invoice_expand').DataTable({
                    autoWidth: true,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                    order: false
                });
          }
        });
     }, 2000);
    
  }
  if($(e.target).hasClass('load_all_cm_message')) {
    $("body").addClass("loading");
    setTimeout(function(){ 
        var editid = $(e.target).attr("data-element");
          $(".copy_clients").attr("data-element", editid);
          $(".print_clients").attr("data-element", editid);
          $(".download_clients").attr("data-element", editid);
          $.ajax({
              url: "<?php echo URL::to('user/cm_load_all_client_message') ?>",
              data:{id:editid},
              dataType:"json",
              type:"post",
              success:function(result){
                $(".load_more_cm_message").show();
                $(".load_all_cm_message").hide();

                $("#message_tbody").html(result['outputmessage']);
           
                $("body").removeClass("loading");
                $('#message_expand').DataTable({
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
          }
        });
     }, 2000);
    
  }

  if($(e.target).hasClass('payroll_class')) {
    var clientid = $(e.target).attr("id");

    console.log(clientid);
    
    $.ajax({
        url: "<?php echo URL::to('user/cm_client_payroll') ?>",
        data:{id:clientid},
        type:"post",
        success:function(result){          
           $(".payroll_model").modal("show");
           
    }
  });
  }

  if(e.target.id == "save_as_csv_invoice"){
    $("body").addClass("loading");
    setTimeout(function(){ 
      var editid = $(".invoice_client_id").val();
      console.log(editid);
      $.ajax({
        url:"<?php echo URL::to('user/cm_invoice_report_csv'); ?>",
        type:"post",
        data:{value:editid},
        success: function(result)
        {
          $.ajax({
            url:"<?php echo URL::to('user/cm_get_csv_filename'); ?>",
            type:"post",
            data:{value:editid},
            success: function(result)
            {
              SaveToDisk("<?php echo URL::to('public/papers'); ?>/CM_Report.csv",result);
              $("body").removeClass("loading");
            }
          })
        }
      });
     }, 2000);

    
    
  }
  if(e.target.id == "save_as_pdf_invoice"){
     $("body").addClass("loading");
     setTimeout(function(){ 
        $("#report_pdf_type_two_tbody_invoice").html('');
        var editid = $(".invoice_client_id").val();
        $.ajax({
          url:"<?php echo URL::to('user/cm_invoice_report_pdf'); ?>",
          type:"post",
          dataType:"json",
          data:{value:editid},
          success: function(result)
          {
            $(".invoice_filename").text(result['companyname']);
            $("#report_pdf_type_two_tbody_invoice").append(result['output']);
            
              var pdf_html = $("#report_pdf_type_two_invoice").html();
              $.ajax({
                url:"<?php echo URL::to('user/cm_invoice_download_report_pdfs'); ?>",
                type:"post",
                data:{htmlval:pdf_html},
                success: function(result_pdf)
                {
                  SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result_pdf,'Invoice of '+result['filename']+'.pdf');
                  $("body").removeClass("loading");
                }
              });
            
          }
        });
    }, 2000);
  }


if($(e.target).hasClass('add_bank_cms')){

  var client_id = $(".bank_client_class").val();
  $(".bank_modal_cms").modal('show');
  $("#bank_current_client_id").val(client_id);
  $("#bank_name_cms").val('');
  $("#account_name_cms").val('');
  $("#account_number_cms").val('');

}

if($(e.target).hasClass('bank_submit')){
  if($("#cms_add_bank_form").valid()){
    $('body').addClass('loading');  
    var current_client_id = $("#bank_current_client_id").val();
    var bank_name = $("#bank_name_cms").val();
    var account_name = $("#account_name_cms").val();
    var account_number = $("#account_number_cms").val();
    $("#bank_expand_cms").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/cm_client_add_bank'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,bank_name:bank_name,account_name:account_name,account_number:account_number},
      dataType:"json",
      success: function(result)
      {
         
        $(".bank_modal_cms").modal('hide');
        $("#bank_detail_body").html(result['output']);
        $('#bank_expand_cms').DataTable({
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
       
        $('[data-toggle="tooltip"]').tooltip();
        $('body').removeClass('loading');
      }
    });
  }
}




});
$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        lname : {required: true,},
        taxnumber : {required: true,remote:"<?php echo URL::to('user/rctclient_checktax'); ?>"},
        email : {required: true,email:true,remote:"<?php echo URL::to('user/rctclient_checkemail'); ?>"},
    },
    messages: {
        name : "Client Name is Required",
        lname : "Salutation is Required",
        taxnumber : {
          required : "Tax Number is Required",
          remote : "Tax Number is already exists",
        },
        email : {
          required : "Email Id is Required",
          email : "Please Enter a valid Email Address",
          remote : "Email Id is already exists",
        },
    },
});

</script>

<!-- Page Scripts -->
<script>
$(function(){
  var hf_clientfrom = $("#hf_clientfrom").val();
  if(hf_clientfrom=='clientiden'){
    $('#client_expand').DataTable({
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
  }
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
  delay:1000,
  minLength: 1,
  select: function( event, ui ) {
    $("#client_search_hidden_infile").val(ui.item.id);
  }
  });

</script>





@stop
