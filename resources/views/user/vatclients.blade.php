@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<style>
.disabled{
  pointer-events: none;
  cursor: not-allowed;
}
.fa-sort { margin-top:3px; }
/*.nav>li>a:focus, .nav>li>a:hover { background: #d6d6d6;
    color: #000;
    font-weight: 700; }
.nav-item .active { background: #d6d6d6;
    color: #000;
    font-weight: 700; }*/
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
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.error_ref{
  color: #f00;
    font-size: 9px;
    position: absolute;
    left: 55.5%;
}
body.loading {
    overflow: hidden;
}
body.loading .modal_load {
    display: block;
}
.select_button table tbody tr td a{
    text-align: center;
    padding: 8px 12px;
    color: #000;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #dcdcdc;
    text-align: center;
    padding: 8px 12px;
    color: #000;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#000 !important;
    font-weight:800;
    margin-top:6px;
}

.btn{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.btn:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}

.btn_add
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.btn_add:hover
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.drop_down{
  width: 100%;
margin-top: 2px;
background: none !important;
color: #000 !important;
border-bottom: 1px solid #dedada;
}
.dropdown-menu{
  right: 0px;
left: 79%;
top: 85%;
}
.color_pallete_red{
  padding:18px 17px;
  background: #f00;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_green{
  padding:18px 17px;
  background: green;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_yellow{
  padding:18px 17px;
  background: yellow;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_purple{
  padding:18px 17px;
  background: purple;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.popover-title
{
  font-weight:800;
}
.popover-content{
  display:none !important;
}
#alert_modal{
  z-index:9999999 !important;
}
#alert_modal_edit{
  z-index:9999999 !important;
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
</style>

<style>
.body_bg{
    background: #f5f5f5;
}

.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}

.ui-widget{z-index: 999999999}
.form-control[readonly]{background: #eaeaea !important}
.finishmsg{
  color: green;
font-size: 18px;
font-weight: 800;
}
.disablednav{
  cursor: not-allowed !important;
}
</style>
<?php

if(!empty($_GET['import_type']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('public/assets/images/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_form_one?mode=modal&filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type=1');
      })
    </script>
    <?php
    exit;
  }
}
if(Session::has('message_import')) { ?>
  <div class="modal fade import_excel_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Clients yet to update from Excel sheet</h4>
            <a href="<?php echo URL::to('user/import_sessions?round=1'); ?>" class="btn btn-primary" style="float:right;width: 15%;margin-top: -32px;margin-right: 36px;"> UPLOAD </a>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <th style="text-align: left">S.No</th>
                <th style="text-align: left">Client Name</th>
                <th style="text-align: left">Tax No</th>
              </thead>
              <tbody>
                <?php
                  if((Session::get('insertrows')))
                  {
                    $i = 1;
                    foreach(Session::get('insertrows') as $rows)
                    {
                      ?>
                        <tr>
                          <td><?php echo $i; ?> </td>
                          <td><?php echo $rows['name']; ?></td>
                          <td><?php echo $rows['taxnumber']; ?></td>
                        </tr>
                      <?php
                      $i++;
                    }
                  }
                  else{
                    ?>
                      <tr>
                        <td colspan="8">No Data to Upload</td>
                      </tr>
                    <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="modal fade" id="alert_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Link Client Options</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Primary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="pemail_update" class="pemail_update" id="pemail_yes" value="1" checked><label for="pemail_yes">Yes</label>
            <input type="radio" name="pemail_update" class="pemail_update" id="pemail_no" value="0"><label for="pemail_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Secondary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="semail_update" class="semail_update" id="semail_yes" value="1" checked><label for="semail_yes">Yes</label>
            <input type="radio" name="semail_update" class="semail_update" id="semail_no" value="0"><label for="semail_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_yes" value="1" checked><label for="salutation_yes">Yes</label>
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_no" value="0"><label for="salutation_no">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal_edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Link Client Options</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Primary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="pemail_update_edit" class="pemail_update_edit" id="pemail_yes_edit" value="1" checked><label for="pemail_yes_edit">Yes</label>
            <input type="radio" name="pemail_update_edit" class="pemail_update_edit" id="pemail_no_edit" value="0"><label for="pemail_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Secondary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="semail_update_edit" class="semail_update_edit" id="semail_yes_edit" value="1" checked><label for="semail_yes_edit">Yes</label>
            <input type="radio" name="semail_update_edit" class="semail_update_edit" id="semail_no_edit" value="0"><label for="semail_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_yes_edit" value="1" checked><label for="salutation_yes_edit">Yes</label>
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_no_edit" value="0"><label for="salutation_no_edit">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit_edit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="vat_notifications_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="margin-top:6%;width:82%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">VAT Notifications Utility</h4>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active">
                <a class="nav-link active" id="sourcefile-tab" data-toggle="tab" href="#sourcefiletab" role="tab" aria-controls="sourcefiletab" aria-selected="false">Source File</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="activeclients-tab" data-toggle="tab" href="#activeclientstab" role="tab" aria-controls="activeclientstab" aria-selected="false">Active Clients</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="withoutemail-tab" data-toggle="tab" href="#withoutemailtab" role="tab" aria-controls="withoutemailtab" aria-selected="true">Clients without Email Address</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="deactivatedclients-tab" data-toggle="tab" href="#deactivatedclientstab" role="tab" aria-controls="deactivatedclientstab" aria-selected="true">Deactivated Clients</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="sourcefiletab" role="tabpanel" aria-labelledby="sourcefile-tab">
                <form id="form-validation-compare" action="<?php echo URL::to('user/compare_form'); ?>" method="post" enctype="multipart/form-data">
                  <div class="compare_form_modal" style="width: 50%;padding: 10px 0px;margin-top: 20px;">
                      <div class="form-group">    
                        <label style="margin-bottom: 10px;">Notify Client of VAT returns due From This CSV File:</label>            
                          <input class="form-control compare_file" name="compare_file" type="file" required>
                      </div>
                      <input type="button" value="Review VAT returns and Proceed to Notify" class="btn review_vat_returns_notify">
                      <p style="text-align: justify; margin-top:55px">Select a Revenue Extract file and import it, to proceed to notify clients that their VAT returns are due</p>
                  </div>
                @csrf
</form>
              </div>
              <div class="tab-pane fade" id="activeclientstab" role="tabpanel" aria-labelledby="activeclients-tab">
                
              </div>
              <div class="tab-pane fade" id="withoutemailtab" role="tabpanel" aria-labelledby="withoutemail-tab">
                
              </div>
              <div class="tab-pane fade" id="deactivatedclientstab" role="tabpanel" aria-labelledby="deactivatedclients-tab">
                
              </div>
            </div>
            <p class="finishmsg"></p>
          </div>
          <div class="modal-footer" style="clear:both">

          </div>
      </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="form-validation-edit" action="<?php echo URL::to('user/update_vat_clients'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title edit_title">Edit Clients</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">      
                    <label>Company Name : </label>          
                      <input class="form-control client_search_class_edit"
                             name=""
                             placeholder="Choose Company Name"
                             type="text" required style="width:90%; display:inline;">
                      <img class="active_client_list_vat" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
                      <input type="hidden" id="client_search_edit" name="cmclientid" />
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">      
                    <label>Client Name : </label>          
                      <input type="hidden" name="client_id" id="client_id" value="">
                      <input class="form-control name_class firstname_class_edit"
                             name="name"
                             placeholder="Enter Client Name"
                             type="text" required>
                  </div>
                  <label class="error_client_name"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Enter Taxnumber : </label>           
                      <input class="form-control taxnumber_class"
                             name="taxnumber"
                             placeholder="Enter Taxnumber"
                             type="text" readonly required>
                  </div>  
              </div>
              <div class="col-md-6">
                  <div class="form-group">  
                  <label>Enter Primary Email ID : </label>               
                      <input class="form-control pemail_class primaryemail_class_edit"
                             name="pemail"
                             placeholder="Enter Primary Email ID"
                             type="email" required>
                  </div>
                  <label class="error_pemail"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">       
                  <label>Enter Secondary Email ID : </label>         
                      <input class="form-control semail_class_edit"
                             name="semail"
                             placeholder="Enter Secondary Email Id"
                             type="email">
                      
                  </div>
                  <label class="error_semail"></label>
              </div>
              <div class="col-md-6">
                  <div class="form-group">    
                  <label>Enter Salutation : </label>                 
                      <textarea class="form-control salutation_class_edit"
                             name="salutation"
                             placeholder="Enter Salutation" required></textarea>                
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Self Manage : </label>                 
                            <input type="radio" name="self" value="yes" class="self_manage_class" id="self_manage_class_yes" required >
                            <label>Yes</label>
                        
                            <input type="radio" name="self" value="no" class="self_manage_class" id="self_manage_class_no" required>
                            <label>No</label>
                    
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Always Nil : </label>                 
                            <input type="radio" name="always_nil" value="yes" class="always_nil_class" id="always_nil_class_yes" required>
                            <label>Yes</label>
                        
                            <input type="radio" name="always_nil" value="no" class="always_nil_class" id="always_nil_class_no" required>
                            <label>No</label>
                    
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_client_id_edit" id="hidden_client_id_edit" value="">
            <input type="hidden" value="" name="id" class="name_id">
            <input type="button" value="Update Clients" class="btn_add" id="edit_client_details">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade addclass_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="form-validation-add" action="<?php echo URL::to('user/add_vat_clients'); ?>" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Clients</h4>
          </div>
          <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">      
                  <label>Company Name : </label>          
                    <input class="form-control client_search_class"
                           name=""
                           placeholder="Choose Company Name"
                           type="text" required>
                    <input type="hidden" id="client_search" name="cmclientid" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">      
                  <label>Client Name : </label>          
                    <input class="form-control name_class_add firstname_class"
                           name="name"
                           placeholder="Enter Client Name"
                           type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                  <label>Enter Taxnumber : </label>           
                  <input class="form-control taxnumber_class_add tax_reg1class"
                       name="taxnumber"
                       placeholder="Enter Taxnumber"
                       type="text" required>
                </div>     
                <label class="error_taxnumber_add"></label>       
            </div>
            <div class="col-md-6">
                <div class="form-group">  
                <label>Enter Primary Email ID : </label>               
                    <input class="form-control pemail_class_add primaryemail_class"
                           name="pemail"
                           placeholder="Enter Primary Email ID"
                           type="email" required>
                </div>
                <label class="error_pemail_add"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">       
                <label>Enter Secondary Email ID : </label>         
                    <input class="form-control semail_class_add"
                           name="semail"
                           placeholder="Enter Secondary Email Id"
                           type="email">
                    
                </div>
                <label class="error_semail_add"></label>     
            </div>
            <div class="col-md-6">
                <div class="form-group">    
                <label>Enter Salutation : </label>                 
                    <textarea class="form-control salutation_class_add"
                           name="salutation"
                           placeholder="Enter Salutation" required></textarea>                
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                <label>Tax Type : </label>                 
                          <input class="form-control tax_class_add"
                           name="tax_type"
                           placeholder="Enter Tax Type"
                           type="text" required>
                </div>  
            </div>
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Document Type : </label>                 
                    <input class="form-control document_class_add" name="document_type" placeholder="Enter Document Type" type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Period : </label>                 
                    <input class="form-control period_class_add" name="period_add" placeholder="Enter Period" type="text" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Due Date : </label>                 
                    <input class="form-control due_class_add datepicker" name="due_add" placeholder="Enter Due Date" type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">     
                <label>Self Manage : </label>                 
                          <input type="radio" name="self" value="yes" id="self_manage_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="self" value="no" id="self_manage_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">     
                <label>Always Nil : </label>                 
                          <input type="radio" name="always_nil" value="yes" id="always_nil_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="always_nil" value="no" id="always_nil_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">     
                <label>ROS filer : </label>                 
                          <input type="radio" name="ros_filer" value="yes" id="ros_filer_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="ros_filer" value="no" id="ros_filer_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="">
            <input type="hidden" name="hidden_client_salutation" id="hidden_client_salutation" value="">
            <input type="submit" value="Add Clients" class="btn_add" id="add_client_details">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade import_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form id="form-validation-import" action="<?php echo URL::to('user/import_form'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Import Clients</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input class="form-control import_file" name="import_file" type="file" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Import" class="btn">

            <p style="text-align: justify;margin-top:55px">Select a revenue extract file for VAT returns due, and import into the system.  This will update the list of client in the VAT system that have VAT returns due.  Once imported you need to link these clients, who will be highlighted with the broken red chain link to a client in the CM System</p>
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="client_email_sents_modal" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Email Sent Date and Time</h4>
      </div>
      <div class="modal-body modal_max_height">
          <table class="table">
            <thead>
              <th>S.No</th>
              <th>Date</th>
              <th>Time</th>
            </thead>
            <tbody id="client_email_sents">

            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <a href="javascript:" class="btn btn-primary saveaspdf" data-element="">Save as Pdf</a>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999"  id="vatClientsOnlyModal">
  <div class="modal-dialog modal-sm" role="document" style="width:1550px !important">
    <div class="modal-content" style="width:1600px !important">
      <div class="modal-header">
        <button type="button" class="close" id="dataModalClose"aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Active VAT Clients who are no longer Active Client</h4>
      </div>
      <div class="modal-body modal_max_height">
           <table class="table display nowrap fullviewtablelist own_table_white vat_expand">
            <thead>
              <tr>
                  <th >Client ID <i class="fa fa-sort sno_sort" style="float:right" aria-hidden="true"></th>
                  <th style="text-align: left;">Client Name <i class="fa fa-sort client_sort" style="float:right" aria-hidden="true"></th>
                 
                    <th style="text-align: left; ">VAT Status <i class="fa fa-sort pemail_sort" style="float:right" aria-hidden="true"></th>
                  <th style="text-align: left; ">Client Status <i class="fa fa-sort pemail_sort" style="float:right" aria-hidden="true"></th>
              </tr>
            </thead>
            <tbody id="task_bodq" style="widht:300px !important">
              <?php
                  $i=1;
                  if(($vatOnlyClient)){              
                    foreach($vatOnlyClient as $client){                
                    
                ?>
             <tr class="task_tr task_<?php echo $client->client_id; ?>" style="text-align:center">
               
                  <td class="sno_sort_val"style="text-align: left;"><label class="lableid_<?php echo $client->client_id; ?>" style="color: green !important;"><?php echo $client->cm_client_id; ?></label></th>
                  <td class="client_sort_val" style="text-align: left; "><label class="lablename_<?php echo $client->client_id; ?>" style="color:green !important;"><?php echo $client->name; ?></label></td>
                   <td class="pemail_sort_val" style="text-align: left; "><label class="lablevatstatus_<?php echo $client->client_id; ?>"><a href="javascript:" style="color:green !important;" class="vatClientId" id="lablevatstatus_<?php echo $client->client_id; ?>" data-mode="active" data-id="<?php echo $client->client_id;?>" style="width:auto; float: none; padding: 5px;" title="Disable Client">Active</a> </label></td>
                  <td class="pemail_sort_val" style="text-align: left; color:red; "><label style="color: red !important;"><?php if($client->cm_status == 0){echo "Inactive";}; ?></label></td>
              </tr>
              <?php
                  $i++;                              
                  }              
                }
                if($i == 1)
                {
                  echo'<tr><td colspan="9" align="center">Empty</td></tr>';
                }
              ?>
              
            </tbody>            
          </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<!-- <div class="modal fade compare_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form id="form-validation-compare" action="<?php echo URL::to('user/compare_form'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Notify Client of VAT returns due From This CSV File</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input class="form-control compare_file" name="compare_file" type="file" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Review VAT returns and Proceed to Notify" class="btn">
            <p style="text-align: justify; margin-top:55px">Select a Revenue Extract file and import it, to proceed to notify clients that their VAT returns are due</p>
          </div>
        </div>
    @csrf
</form>
  </div>
</div> -->
<div class="content_section">

<div class="page_title" style="z-index:999;">
    <h4 class="col-lg-12 padding_00 new_main_title">
              VAT Management System   
              <a href="javascript:" class="common_black_button fa fa-cog vat_settings_btn" title="VAT Settings" style="float:right"></a>          
          </h4>
  </div>

<div class="row">
<?php
  if(Session::has('message')) { ?>
         <p class="alert alert-info" style="clear: both;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
<?php } ?>
  <div class="message_edit">
  </div>
  
  
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
      <div style="position: absolute; right: 15px; top: 45px; z-index: 1">
        <div style="float: left;margin-left: 20px;">
          <?php
            $red = DB::table('vat_clients')->where('practice_code', Session::get('user_practice_code'))->where('status',1)->count();
            $green = DB::table('vat_clients')->where('practice_code', Session::get('user_practice_code'))->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
            $yellow = DB::table('vat_clients')->where('practice_code', Session::get('user_practice_code'))->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
            $purple = DB::table('vat_clients')->where('practice_code', Session::get('user_practice_code'))->where('status',0)->where('self_manage','yes')->count();
          ?>
          <spam style="color:#f00;"><?php echo $red; ?> Clients Disabled</spam>
          
        </div>
        <div style="float: left;margin-left: 30px;">
          <spam style="color:green"><?php echo $green; ?> Clients With Primary Email Address</spam>
        </div>
        <div style="float: left;margin-left: 30px;">
          <spam style="color:#bd510a"><?php echo $yellow; ?> Clients Without Primary Email Address</spam>        
        </div>
        <div style="float: left;margin-left: 30px;">
          <spam style="color:purple"><?php echo $purple; ?> Clients With Self Manage</spam>
        </div>
      </div>
      <div style="float:right;">
        <a href="javascript:" class="import_button common_black_button">Import VAT Clients from ROS Extract</a>
        <a href="javascript:" class="compare_button common_black_button" type="button" >Notify Clients of VAT Returns Due</button></a>
        
      </div>


    
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_00" >
  <ul class="nav nav-tabs" style="margin-top: 20px;">
    <li class="nav-item">
      <a class="nav-link" href="<?php echo URL::to('user/vat_review'); ?>">VAT Management System VAT Review</a>
    </li>
    <li class="nav-item active">
      <a class="nav-link" href="<?php echo URL::to('user/vat_clients'); ?>">VAT Clients Imported in to VAT Management System</a>
    </li>
  </ul>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_00 vattaxtable" style="display:none;" >
  <div class="table-responsive">
      <table class="display nowrap fullviewtablelist own_table_white vat_expand " id="vat_expand" style="margin-top: 0px !important;">
        <thead>
          <tr>
              <th style="text-align: left;width:8%">Client Code <i class="fa fa-sort sno_sort" style="float:right" aria-hidden="true"></th>
              <th style="text-align: left; ;">Client Name <i class="fa fa-sort client_sort" style="float:right" aria-hidden="true"></th>
              <th style="text-align: left; ">Tax Regn./Trader No <i class="fa fa-sort tax_sort" style="float:right" aria-hidden="true"></th>
              <th style="text-align: left; ">Email <i class="fa fa-sort pemail_sort" style="float:right" aria-hidden="true"></th>
              <th style="text-align: left; ">Secondary Email <i class="fa fa-sort semail_sort" style="float:right" aria-hidden="true"></th>  
              <th style="text-align: left; ">Salutation </th>                               
              <th style="text-align: left; width:10%">Action</th>

          </tr>
        </thead>
        <tbody id="task_body">
          <?php
              $i=1;
              if(($clientlist)){              
                foreach($clientlist as $client){                
                if($client->status == 1) { $fontcolor = 'red'; $disabled = 'disabled'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; $disabled = ''; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; $disabled = ''; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff'; $disabled = ''; }
            ?>
          <tr class="task_tr task_<?php echo $client->client_id; ?>" style="text-align:center">
              <td class="sno_sort_val" width="5%" style="text-align: left;"><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->cm_client_id; ?></label></th>
              <td class="client_sort_val" style="text-align: left; "><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->name; ?></label></td>
              <td class="tax_sort_val" style="text-align: left; "><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->taxnumber; ?></label></td>
              <td class="pemail_sort_val" style="text-align: left; "><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->pemail; ?></label></td>
              <td class="semail_sort_val" style="text-align: left; "><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->semail; ?></label></td>
              <td class="salutation_sort_val" style="text-align: left; "><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->salutation; ?></label></td>                      
              <td style="text-align: left; ">
                  <a href="javascript:" style="width:auto; float: none; padding: 5px;" id="<?php echo base64_encode($client->client_id); ?>" class="editclass <?php echo $disabled; ?>" title="Edit Client"><i class="fa fa-pencil-square editclass" id="<?php echo base64_encode($client->client_id); ?>" aria-hidden="true"></i></a>

                            &nbsp; 
                  <a href="javascript:" style="width:auto; float: none; padding: 5px;" id="<?php echo base64_encode($client->client_id); ?>" class="email_sent" title="Email Sent Date & Time"><i class="fa fa-envelope email_sent" id="<?php echo base64_encode($client->client_id); ?>" aria-hidden="true"></i></a>

                            &nbsp; 
                  <?php
                  if($client->status ==0){
                    echo'<a href="'.URL::to('user/deactive_vat_clients',base64_encode($client->client_id)).'" style="width:auto; float: none; padding: 5px; " title="Disable Client"><i class="fa fa-check" aria-hidden="true"></i></a>';
                  }
                  else{
                    echo'<a href="'.URL::to('user/active_vat_clients',base64_encode($client->client_id)).'" style="width:auto; float: none; padding: 5px; " title="Enable Client"><i class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                ?>
                  <spam class="icon_div" style="font-size: 20px; color: <?php if($client->cm_client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                    <i class="fa <?php if($client->cm_client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($client->cm_client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                  </spam>


              </td>
          </tr>
          <?php
              $i++;                              
              }              
            }
            if($i == 1)
            {
              echo'<tr><td colspan="9" align="center">Empty</td></tr>';
            }
          ?>
          
        </tbody>            
      </table>
      </div>
  </div>
</div>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
<input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
<input type="hidden" name="pemail_sortoptions" id="pemail_sortoptions" value="asc">
<input type="hidden" name="semail_sortoptions" id="semail_sortoptions" value="asc">
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Uploading Please wait...</p>
  <p style="font-size:18px;font-weight: 600;">Finished Comparing <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<?php
if(Session::get('comparerows'))
{
    Session::forget('comparerows');
}
if(Session::has('comparerows')){
    Session::forget('comparerows');
}
if(Session::has('message_import')) { ?>
<script>
$(document).ready(function(){
  $(".import_excel_modal").modal("show");
});
</script>
<?php } 
elseif(Session::has('message_import_not_valid')) { ?>
<script>
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00><?php echo Session::get('message_import_not_valid'); ?></p>",width:"40%",height:"22%"});
    </script>
<?php }
else {
  ?>
  <script>
  $( function() {
    var url = window.location.href;
   var urlVal = url.split("?");
  if (urlVal.length <= 1) {
   $('#vatClientsOnlyModal').modal('show');
   $('.vattaxtable').hide();
  } else{
    $('.vattaxtable').show();
  }

  $("#dataModalClose").click(function(){
      window.location.href += "?mode=modal";
     $("#vatClientsOnlyModal").modal("hide");
     // $(".vattaxtable").show();
  });
  $(".vatClientId").click(function(){
    
    var clientIdvat = $(this).attr('data-id');
    var modeVal = $(this).attr('data-mode');
        
    if(modeVal == 'active') {
     
      $(this).attr('data-mode', 'deactivate');
      $('.lableid_'+clientIdvat).css("color", "red");
      $('.lablename_'+clientIdvat).css("color", "red");
      $('#lablevatstatus_'+clientIdvat).css("color", "red");
       $(this).text("InActive");

    } else{
      
      $(this).attr('data-mode', 'active');
      $('.lableid_'+clientIdvat).css("color", "green");
      $('.lablename_'+clientIdvat).css("color", "green");
      $('#lablevatstatus_'+clientIdvat).css("color", "green");
      $(this).text("Active");

    }
   $.ajax({
        url:"<?php echo URL::to('/user/deactive_vat_clients_ajax'); ?>",
        type:"get",
        data:{'client_id':clientIdvat, 'mode':modeVal},
        success:function(result)
        {
        }
      })
  });
    $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  } );
  </script>
  <?php
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
function compare_next_one(status,filename,height,round,highestrow,compare_type){
  $.ajax({
      url: "<?php echo URL::to('user/compare_form_one'); ?>",
      type: 'POST',
      data: {filename:filename,height:height,round:round,highestrow:highestrow,compare_type:compare_type},
      dataType:"json",
      success: function (result) {
        if(result['status'] == "2"){
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">'+result['message']+'</p>',fixed:true,width:"800px"});
          $("body").removeClass("loading_apply");
        }
        else if(result['status'] == "0"){

          $("#activeclients-tab").removeClass("disabled");
          $("#withoutemail-tab").removeClass("disabled");
          $("#deactivatedclients-tab").removeClass("disabled");

          $("#activeclients-tab").parents(".nav-item").removeClass("disablednav");
          $("#withoutemail-tab").parents(".nav-item").removeClass("disablednav");
          $("#deactivatedclients-tab").parents(".nav-item").removeClass("disablednav");


          $("#activeclientstab").html(result['active_clients']);
          $("#withoutemailtab").html(result['without_email']);
          $("#deactivatedclientstab").html(result['deactivated_clients']);

          $(".nav-item").removeClass("active");
          $(".nav-link").removeClass("active");

          $("#sourcefiletab").removeClass("active");
          $("#sourcefiletab").removeClass("in");

          $("#activeclients-tab").parents(".nav-item").addClass("active");

          $("#activeclientstab").addClass("active");
          $("#activeclientstab").addClass("in");

          $('#vat_active_clients_expand').DataTable({        
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [[0, 'asc']]
          });
          $('#vat_without_email_expand').DataTable({        
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [[0, 'asc']]
          });
          $('#vat_deactivated_clients_expand').DataTable({        
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [[0, 'asc']]
          });


          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">'+result['message']+'</p>',fixed:true,width:"800px"});
          $("body").removeClass("loading_apply");
        }
        else{
          var status = result['status'];
          var filename = result['filename'];
          var height = result['height'];
          var round = result['round'];
          var highestrow = result['highestrow'];
          var compare_type = result['compare_type'];

          var finished = parseInt(round) - 1;
          var finished_count = parseInt(finished) * 500;
          $("#apply_first").html(finished_count);

          compare_next_one(status,filename,height,round,highestrow,compare_type);
        }
      }
  });
}
$(window).click(function(e) {  
  var ascending = false;
  if($(e.target).hasClass('review_vat_returns_notify'))
  {
    var file = $(".compare_file").val();
    if(file == "")
    {
      alert("Please select the CSV file to import");
    }
    else{
      $("body").addClass("loading");
      var formData = $("#form-validation-compare").submit(function (e) {
        return;
      });
      var formData = new FormData(formData[0]);

      $("#vat_active_clients_expand").dataTable().fnDestroy();
      $("#vat_without_email_expand").dataTable().fnDestroy();
      $("#vat_deactivated_clients_expand").dataTable().fnDestroy();

      $.ajax({
          url: "<?php echo URL::to('user/compare_form'); ?>",
          type: 'POST',
          data: formData,
          dataType:"json",
          success: function (result) {
            if(result['status'] == "2"){
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">'+result['message']+'</p>',fixed:true,width:"800px"});
              $("body").removeClass("loading");
            }
            else if(result['status'] == "0"){
              $("#activeclients-tab").removeClass("disabled");
              $("#withoutemail-tab").removeClass("disabled");
              $("#deactivatedclients-tab").removeClass("disabled");

              $("#activeclients-tab").parents(".nav-item").removeClass("disablednav");
              $("#withoutemail-tab").parents(".nav-item").removeClass("disablednav");
              $("#deactivatedclients-tab").parents(".nav-item").removeClass("disablednav");

              $("#activeclientstab").html(result['active_clients']);
              $("#withoutemailtab").html(result['without_email']);
              $("#deactivatedclientstab").html(result['deactivated_clients']);

              $(".nav-item").removeClass("active");
              $(".nav-link").removeClass("active");

              $("#sourcefiletab").removeClass("active");
              $("#sourcefiletab").removeClass("in");

              $("#activeclients-tab").parents(".nav-item").addClass("active");

              $("#activeclientstab").addClass("active");
              $("#activeclientstab").addClass("in");

              $('#vat_active_clients_expand').DataTable({        
                  autoWidth: false,
                  scrollX: false,
                  fixedColumns: false,
                  searching: false,
                  paging: false,
                  info: false,
                  order: [[0, 'asc']]
              });
              $('#vat_without_email_expand').DataTable({        
                  autoWidth: false,
                  scrollX: false,
                  fixedColumns: false,
                  searching: false,
                  paging: false,
                  info: false,
                  order: [[0, 'asc']]
              });
              $('#vat_deactivated_clients_expand').DataTable({        
                  autoWidth: false,
                  scrollX: false,
                  fixedColumns: false,
                  searching: false,
                  paging: false,
                  info: false,
                  order: [[0, 'asc']]
              });

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">'+result['message']+'</p>',fixed:true,width:"800px"});
              $("body").removeClass("loading");
            }
            else{
              $("body").removeClass("loading");
              $("body").addClass("loading_apply");

              var status = result['status'];
              var filename = result['filename'];
              var height = result['height'];
              var round = result['round'];
              var highestrow = result['highestrow'];
              var compare_type = result['compare_type'];
              var finished = parseInt(round) - 1;
              var finished_count = parseInt(finished) * 500;
              $("#apply_first").html(finished_count);
              $("#apply_last").html(highestrow);
              compare_next_one(status,filename,height,round,highestrow,compare_type);
            }
          },
          cache: false,
          contentType: false,
          processData: false
      });
    }
  }
  if($(e.target).hasClass('active_client_list_vat'))
  {
    var client_id=$("#hidden_client_id_edit").val();
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
  if($(e.target).hasClass('vat_settings_btn'))
  {
    if (CKEDITOR.instances.editor_vat_review) CKEDITOR.instances.editor_vat_review.destroy();
    if (CKEDITOR.instances.editor_vat_review_notes) CKEDITOR.instances.editor_vat_review_notes.destroy();
    if (CKEDITOR.instances.editor_vat_review_signature) CKEDITOR.instances.editor_vat_review_signature.destroy();

    CKEDITOR.replace('editor_vat_review',
     {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
     });
    CKEDITOR.replace('editor_vat_review_notes',
     {
      height: '80px',
      enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
     });
    CKEDITOR.replace('editor_vat_review_signature',
     {
      height: '80px',
      enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
     });
    $(".vat_settings_modal").modal('show');
  }
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_val').text()) <
        convertToNumber($(b).find('.sno_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_val').text()) <
        convertToNumber($(b).find('.sno_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').find("label").html()) <
        convertToNumber($(b).find('.client_sort_val').find("label").html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').find("label").html()) <
        convertToNumber($(b).find('.client_sort_val').find("label").html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('tax_sort'))
  {
    var sort = $("#tax_sortoptions").val();
    if(sort == 'asc')
    {
      $("#tax_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#tax_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('pemail_sort'))
  {
    var sort = $("#pemail_sortoptions").val();
    if(sort == 'asc')
    {
      $("#pemail_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.pemail_sort_val').html()) <
        convertToNumber($(b).find('.pemail_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#pemail_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.pemail_sort_val').html()) <
        convertToNumber($(b).find('.pemail_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('semail_sort'))
  {
    var sort = $("#semail_sortoptions").val();
    if(sort == 'asc')
    {
      $("#semail_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.semail_sort_val').html()) <
        convertToNumber($(b).find('.semail_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#semail_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.semail_sort_val').html()) <
        convertToNumber($(b).find('.semail_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if(e.target.id == "edit_client_details")
  {
      var name = $(".name_class").val();           
      var pemail = $(".pemail_class").val();
      var semail = $(".semail_class_edit").val();
      var salutation = $(".salutation_class_edit").val();
      var id = $(".name_id").val();
      var self_manage = $(".self_manage_class:checked").val();
      var always_nil = $(".always_nil_class:checked").val();
      var client_id = $("#hidden_client_id_edit").val();
      if(pemail == "")
      {
        $(".error_pemail").text("Please Enter your Primary Email Address");
        return false;
      }
      if(name  == "")
      {
        $(".error_client_name").text("Please Enter Client Name");
        return false; 
      }
      $.ajax({
        url:"<?php echo URL::to('user/update_vat_clients'); ?>",
        type:"post",
        dataType:"json",
        data:{name:name,pemail:pemail,semail:semail,salutation:salutation,self:self_manage,always_nil:always_nil,id:id,client_id:client_id},
        success: function(result)
        {
          $(".bs-example-modal-sm").modal("hide");
          $(".task_"+id).find(".semail_sort_val").find("label").text(semail);
          $(".task_"+id).find(".salutation_sort_val").find("label").text(salutation);
          $(".task_"+id).find(".pemail_sort_val").find("label").text(pemail);
          $(".task_"+id).find(".client_sort_val").find("label").text(name);
          if(result['cm_client_id'] != "")
          {
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-chain-broken").addClass("fa-link");            
            $(".task_"+id).find(".icon_div").css({"color":"blue"});

            $(".task_"+id).find(".sno_sort_val").html('<label style="color:green !important;">'+result['cm_client_id']+'</label>');
          }
          else{
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-link").addClass("fa-chain-broken");
            $(".task_"+id).find(".icon_div").css({"color":"red"});
          }
          
          $(".color_pallete_purple").attr("data-content",result['purple']+" Clients");
          $(".color_pallete_yellow").attr("data-content",result['yellow']+" Clients");
          $(".color_pallete_green").attr("data-content",result['green']+" Clients");
          $(".color_pallete_red").attr("data-content",result['red']+" Clients");

          if(result['status'] == "0")
          {
            if(pemail != "" && self_manage == "no")
            {
              $(".task_"+id).find(".sno_sort_val").find("label").attr("style","color:green !important");
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:green !important");
              $(".task_"+id).find(".tax_sort_val").find("label").attr("style","color:green !important");
              $(".task_"+id).find(".pemail_sort_val").find("label").attr("style","color:green !important");
              $(".task_"+id).find(".semail_sort_val").find("label").attr("style","color:green !important");
              $(".task_"+id).find(".salutation_sort_val").find("label").attr("style","color:green !important");
            }
            else if(pemail == "" && self_manage == "no")
            {
              $(".task_"+id).find(".sno_sort_val").find("label").attr("style","color:#bd510a !important");
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:#bd510a !important");
              $(".task_"+id).find(".tax_sort_val").find("label").attr("style","color:#bd510a !important");
              $(".task_"+id).find(".pemail_sort_val").find("label").attr("style","color:#bd510a !important");
              $(".task_"+id).find(".semail_sort_val").find("label").attr("style","color:#bd510a !important");
              $(".task_"+id).find(".salutation_sort_val").find("label").attr("style","color:#bd510a !important");
            }
            else if(self_manage == "yes")
            {
              $(".task_"+id).find(".sno_sort_val").find("label").attr("style","color:purple !important");
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:purple !important");
              $(".task_"+id).find(".tax_sort_val").find("label").attr("style","color:purple !important");
              $(".task_"+id).find(".pemail_sort_val").find("label").attr("style","color:purple !important");
              $(".task_"+id).find(".semail_sort_val").find("label").attr("style","color:purple !important");
              $(".task_"+id).find(".salutation_sort_val").find("label").attr("style","color:purple !important");
            }
            else{
              $(".task_"+id).find(".sno_sort_val").find("label").attr("style","color:#fff !important");
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:#fff !important");
              $(".task_"+id).find(".tax_sort_val").find("label").attr("style","color:#fff !important");
              $(".task_"+id).find(".pemail_sort_val").find("label").attr("style","color:#fff !important");
              $(".task_"+id).find(".semail_sort_val").find("label").attr("style","color:#fff !important");
              $(".task_"+id).find(".salutation_sort_val").find("label").attr("style","color:#fff !important");
            }
          }
          $(".message_edit").html('<p class="alert alert-info" style="clear:both"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> Clients Updated successfully</p>');
        }
      });
  }
  if(e.target.id == "add_client_details")
  {
    var taxnumber = $(".error_taxnumber_add").text();
    if(taxnumber != "")
    {
      return false;
    }
    else{
      
    }
  }
  if($(e.target).hasClass("email_sent"))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("id");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("show");
        $("#client_email_sents").html(result);
        $(".saveaspdf").attr("data-element",id);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('saveaspdf'))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents_save_pdf'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("hide");
        $("body").removeClass("loading");
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          return false; //this is critical to stop the click event which will trigger a normal file download
      }
    })
  }
  if($(e.target).hasClass('import_button')) {
    $(".import_modal").modal("show");
  }
  if($(e.target).hasClass('compare_button')) {
    $("#vat_notifications_modal").modal("show");

    $("#activeclients-tab").addClass("disabled");
    $("#withoutemail-tab").addClass("disabled");
    $("#deactivatedclients-tab").addClass("disabled");

    $("#activeclients-tab").parents(".nav-item").addClass("disablednav");
    $("#withoutemail-tab").parents(".nav-item").addClass("disablednav");
    $("#deactivatedclients-tab").parents(".nav-item").addClass("disablednav");

    $(".nav-item").removeClass("active");
    $(".nav-link").removeClass("active");

    $("#sourcefiletab").removeClass("active");
    $("#sourcefiletab").removeClass("in");

    $("#activeclientstab").removeClass("active");
    $("#activeclientstab").removeClass("in");

    $("#withoutemailtab").removeClass("active");
    $("#withoutemailtab").removeClass("in");

    $("#deactivatedclientstab").removeClass("active");
    $("#deactivatedclientstab").removeClass("in");

    $("#sourcefile-tab").parents(".nav-item").addClass("active");

    $("#sourcefiletab").addClass("active");
    $("#sourcefiletab").addClass("in");
  }
  
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    console.log(editid);
    $.ajax({
        url: "<?php echo URL::to('user/edit_vat_clients') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".bs-example-modal-sm").modal("toggle");
           $(".modal-content").css({"top":"90px"});
           if(result['cm_client_id'] == "")
           {
            $(".edit_title").html('Edit Client - '+result['name']);
           }
           else{
            $(".edit_title").html('Edit Client - '+result['name']+' '+result['cm_client_id']);
           }
           
           $(".editsp").show();           
           $("#hidden_client_id_edit").val(result['cm_client_id']);
           $(".name_class").val(result['name']);           
           $(".taxnumber_class").val(result['taxnumber']);
           $(".pemail_class").val(result['pemail']);
           $(".semail_class_edit").val(result['semail']);
           $(".salutation_class_edit").val(result['salutation']);
           $(".client_search_class_edit").val(result['companyname']);
           $(".firstname_class_edit").val(result['firstname']);

           $(".name_id").val(result['id']);

            if(result['self_manage'] == 'yes')
            {
              $("#self_manage_class_yes").prop("checked",true);
            }
            else if(result['self_manage'] == 'no')
            {
              $("#self_manage_class_no").prop("checked",true);
            }
            else
            {
              $("#self_manage_class_yes").prop("checked",false);
              $("#self_manage_class_no").prop("checked",false);
            }

            if(result['always_nil'] == 'yes')
            {
              $("#always_nil_class_yes").prop("checked",true);
            }
            else if(result['always_nil'] == 'no')
            {
              $("#always_nil_class_no").prop("checked",true);
            }
            else
            {
              $("#always_nil_class_yes").prop("checked",false);
              $("#always_nil_class_no").prop("checked",false);
            }
      }
    });
  }
  if($(e.target).hasClass('addclass')) {
           $(".addclass_modal").modal("toggle");
           $(".modal-content").css({"top":"90px"});
  }
  if(e.target.id == "alert_submit")
  {
    var pemail = $(".pemail_update:checked").val();
    var semail = $(".semail_update:checked").val();
    var salutation = $(".salutation_update:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id").val();
      
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_add").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation").val(1);
      }
      else{
        $("#hidden_client_salutation").val(0);
      }
      $("#alert_modal").modal("hide");
    }
  }
  if(e.target.id == "alert_submit_edit")
  {
    var pemail = $(".pemail_update_edit:checked").val();
    var semail = $(".semail_update_edit:checked").val();
    var salutation = $(".salutation_update_edit:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id_edit").val();
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class_edit").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_edit").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclient_salutation'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".salutation_class_edit").val(result);
          }
        });
      }
      $("#alert_modal_edit").modal("hide");
    }
  }
});
function SaveToDisk_vat(fileURL, fileName) {
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
$(window).click(function(e) {  
  if(e.target.id == "select_all")
  {
    if($(e.target).is(":checked"))
    {
      $("#always_nill").prop("checked",false);
      $(".select_functioning").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if(e.target.id == "always_nill")
  {
    if($(e.target).is(":checked"))
    {
      $("#select_all").prop("checked",false);

      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
      $(".always_nil_check").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
      $(".always_nil_check").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if(e.target.id == "pdf_without_email")
  {
    $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/pdf_without_email'); ?>",
          type:"get",
          success: function(result)
          {
              $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('public/papers'); ?>/"+result,result);
          }
      });
  }
  if(e.target.id == "pdf_with_email")
  {
    $("body").addClass("loading");
    $.ajax({
          url:"<?php echo URL::to('user/pdf_with_email'); ?>",
          type:"get",
          success: function(result)
          {
                $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('public/papers'); ?>/"+result,result);
          }
      });
  }
  if(e.target.id == "pdf_disabled")
  {
    $("body").addClass("loading");
    $.ajax({
          url:"<?php echo URL::to('user/pdf_disabled'); ?>",
          type:"get",
          success: function(result)
          {
                $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('public/papers'); ?>/"+result,result);
          }
      });
  }
  if($(e.target).hasClass('functioningall'))
  {
    var id = $(e.target).attr("id");
    if($(e.target).is(":checked"))
    {
      $(".functioning_"+id).each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".functioning_"+id).each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if($(e.target).hasClass('select_functioning'))
  {
    if($(e.target).is(":checked"))
    {
      
    }
    else{
      var lnth = $(".select_functioning:checked").length;
      if(lnth == 0)
      {
        $("#select_all").prop("checked",false);
      }
    }
  }
  if(e.target.id == "with_email_submit")
  {
    $("body").addClass("loading");
    var option_length = $(".select_functioning:checked").length;
    var emails = '';
    var timeval = "<?php echo time(); ?>";
    $(".select_functioning").each(function() {
      if($(this).is(":checked"))
      {
        var pemail = $(this).parent().find(".hidden_pemail").val();
        var semail = $(this).parent().find(".hidden_semail").val();
        if(emails == "")
        {
          emails = pemail+','+semail;
        }
        else{
          emails = emails+','+pemail+','+semail;
        }
      }
    });
    if(emails == "")
    {
      $("body").removeClass("loading");
      alert('Please select atleast one client to send the VAT Notification.');
    }
    $(".select_functioning").each(function(i, value) {
      if($(this).is(":checked"))
      {
          var pemail = $(this).parent().find(".hidden_pemail").val();
          var semail = $(this).parent().find(".hidden_semail").val();
          var salutation = $(this).parent().find(".hidden_salutation").val();
          var self_manage = $(this).parent().find(".hidden_self").val();
          var period = $(this).parent().find(".hidden_period").val();
          var due_date = $(this).parent().find(".hidden_duedate").val();
          var client_id = $(this).attr("id");
          setTimeout(function(){
              $.ajax({
                url:"<?php echo URL::to('user/email_vatnotifications'); ?>",
                type:"get",
                data:{pemail:pemail,semail:semail,salutation:salutation,self_manage:self_manage,period:period,due_date:due_date,emails:emails,client_id:client_id,timeval:timeval},
                success: function(result) {
                  var keyi = parseInt(i) + parseInt(1);
                  if(option_length <= keyi)
                  {
                    $("body").removeClass("loading");
                    $(document).scrollTop(0);  
                    $(".finishmsg").text("Email Sent Successfully!");
                  }
                }
              });
            },2000 + ( i * 2000 ));
      }
    });
  }
});

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.pemail_class');

//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();

  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping (input) {
  var client_id = $(".name_id").val();
  var semail = $(".semail_class").val();
  $.ajax({
        url:"<?php echo URL::to('user/check_client_email'); ?>",
        type:"get",
        data:{email:input,client_id:client_id,type:"primary"},
        success: function(result) {
          if(result == 1)
          {
            $(".error_pemail").text('Email Already Exists');
          }
          else{
            $(".error_pemail").text('');
          }
          if(semail == input)
          {
            $(".error_pemail").text('Primary and Secondary Email Should Differ');
          }
        }
      });
}

//setup before functions
var stypingTimer;                //timer identifier
var sdoneTypingInterval = 1000;  //time in ms, 5 second for example
var $sinput = $('.semail_class');

//on keyup, start the countdown
$sinput.on('keyup', function () {
  var sinput_val = $(this).val();

  clearTimeout(stypingTimer);
  stypingTimer = setTimeout(sdoneTyping, sdoneTypingInterval,sinput_val);
});

//on keydown, clear the countdown 
$sinput.on('keydown', function () {
  clearTimeout(stypingTimer);
});

//user is "finished typing," do something
function sdoneTyping (input) {
  var client_id = $(".name_id").val();
  var pemail = $(".pemail_class").val();
  $.ajax({
        url:"<?php echo URL::to('user/check_client_email'); ?>",
        type:"get",
        data:{email:input,client_id:client_id,type:"secondary"},
        success: function(result) {
          if(result == 1)
          {
            $(".error_semail").text('Email Already Exists');
          }
          else{
            $(".error_semail").text('');
          }
          if(pemail == input)
          {
            $(".error_semail").text('Primary and Secondary Email Should Differ');
          }
        }
      });
}

//setup before functions
var taxtypingTimer_add;                //timer identifier
var taxdoneTypingInterval_add = 1000;  //time in ms, 5 second for example
var $taxinput_add = $('.taxnumber_class_add');

//on keyup, start the countdown
$taxinput_add.on('keyup', function () {
  var taxinput_val_add = $(this).val();

  clearTimeout(taxtypingTimer_add);
  taxtypingTimer_add = setTimeout(taxdoneTyping_add, taxdoneTypingInterval_add,taxinput_val_add);
});

//on keydown, clear the countdown 
$taxinput_add.on('keydown', function () {
  clearTimeout(taxtypingTimer_add);
});

//user is "finished typing," do something
function taxdoneTyping_add (input) {
  $.ajax({
        url:"<?php echo URL::to('user/check_client_taxnumber'); ?>",
        type:"get",
        data:{taxnumber:input},
        success: function(result) {
          if(result == 1)
          {
            $(".error_taxnumber_add").text('Taxnumber Already Exists');
          }
          else{
            $(".error_taxnumber_add").text('');
          }
        }
      });
}
</script>

<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
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
          $("#client_search").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              $(".tax_reg1class").val(result['taxreg']);
              $(".firstname_class").val(result['firstname']);
              $("#hidden_client_id").val(ui.item.id);
              $('#alert_modal').modal({backdrop: 'static', keyboard: false});
            }
          })
        }
    });
     $(".client_search_class_edit").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
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
          $("#client_search_edit").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              $(".firstname_class_edit").val(result['firstname']);
              $("#hidden_client_id_edit").val(ui.item.id);
              $('#alert_modal_edit').modal({backdrop: 'static', keyboard: false});
            }
          })
        }
    });
});
</script>

<script>
$(function(){
    $('#vat_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: false,
    });
});

</script>

@stop
