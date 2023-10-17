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

<link href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.12.1/features/scrollResize/dataTables.scrollResize.min.js"></script>
<style>
#aml_expand th:nth-child(1), #aml_expand th:nth-child(2), #aml_expand th:nth-child(3), #aml_expand th:nth-child(4) {
    position: sticky;
    left: 0;
    background: white !important;
    z-index: 50;
}
.fixedHeader-floating th:nth-child(1), .fixedHeader-floating th:nth-child(2), .fixedHeader-floating th:nth-child(3), .fixedHeader-floating th:nth-child(4){
    position: fixed !important;
    left: 0;
    background: white !important;
    z-index: 50;
}
.fixedHeader-floating th:nth-child(2){
left:70px
}
.fixedHeader-floating th:nth-child(3){
left:170px
}
.fixedHeader-floating th:nth-child(4){
left:300px
}
#aml_expand th:nth-child(2){
left:70px
}
#aml_expand th:nth-child(3){
left:170px
}
#aml_expand th:nth-child(4){
left:300px
}
#clients_tbody td:nth-child(1), #clients_tbody td:nth-child(2), #clients_tbody td:nth-child(3), #clients_tbody td:nth-child(4) {
    position: sticky;
    left: 0;
    background: white !important;
}

#clients_tbody td:nth-child(2){
left:70px
}
#clients_tbody td:nth-child(3){
left:170px
}
#clients_tbody td:nth-child(4){
left:300px
}
#colorbox{
  z-index: 9999999;
}
.DTFC_LeftBodyWrapper {
    color: black !important;
    background-color: #ffffff !important;
}

.DTFC_LeftBodyLiner {
    overflow: hidden;
}
body{
  background: #f5f5f5 !important;
}
.trade_details_form{
  text-align: left;
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
.download_aml_report_single{
    padding: 4px 6px 3px 6px;
}

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
    .DTFC_LeftBodyLiner {
top: -12px !important;
overflow-x: hidden;
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
$importing = 0;
$aml_settings = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
$admin_cc = $aml_settings->aml_cc_email;
if(!empty($_GET['import_type_new']))
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
        window.location.replace(base_url+'/user/import_aml_clients_details_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1');
      })
    </script>
    <?php
    $importing = 1;
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
    $importing = 1;
  }
}
if($importing == 0){
?>
<img id="coupon" />
<div class="modal fade aml_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-md" role="document" style="width:40%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">AML - <spam class="aml_client_code"></spam> - <spam class="aml_client_name"></spam></h4>
      </div>
      <div class="modal-body">
          <table class="table">
            <tbody id="aml_client_tbody">

            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/import_aml_clients_details'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import AML Client Details</h4>
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
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document" style="width:40%">
    <form action="" method="post" id="emailunsent_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">AML ID Request Email</h4>
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
              <input type="text" name="cc_unsent" class="form-control input-sm" id="cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
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
          $aml_details = DB::table('aml_settings')->where('practice_code',Session::get('user_practice_code'))->first();
          if($aml_details->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($aml_details->email_header_url.'/'.$aml_details->email_header_filename);
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
              <textarea name="message_editor" id="editor_2"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_email_task_id" id="hidden_email_task_id" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade alert_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          You are about to rename all the Attachment Files, Do you wish to continue?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_hit">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_hit">No</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade alert_modal_single" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          You are about to rename all the Attachment Files, Do you wish to continue?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_hit_single">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_hit_single">No</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade other_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Other Clients</h4>
            <br/>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
          <div class="panel-group">
            <div class="col-lg-9">
              <div class="form-title">Choose Client</div>
              <input type="text" class="form-control client_search_class" placeholder="Enter Client Name" name="" style="width:95%; display:inline;">
              <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
              <input type="hidden" id="client_search" name="" />
              <input type="hidden" id="select_type" name="" />
              <input type="hidden" id="current_client_id" name="" />
              
            </div>              
            <div class="col-lg-3" style="padding: 15px 0px 0px 0px; ">
              <input type="button" class="common_black_button other_submit"  value="Submit">
            </div>
              
            </div>
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                   <th width="5%" style="text-align: left;">S.No</th>                   
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                    }
                    $output.='
                    <tr>
                      <td '.$color.'>'.$i.'</td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.'</td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>

<div class="modal fade partner_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Personal Acquaintance of Partner</h4>           
            
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Select Partner</div>
              <select class="form-control" id="user_type">
                <option value="">Select Partner</option>
                <?php


                $resultuser='';
                if(($userlist)){
                  foreach ($userlist as $user) {
                    $resultuser.='<option value='.$user->user_id.'>'.$user->lastname.' '.$user->firstname.'</option>';
                  }
                }
                echo $resultuser;
                ?>
              </select>
              
              <input type="hidden" id="select_type2" name="" />
              <input type="hidden" id="partner_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button partner_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade reply_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reply to Advert / Walk in</h4>
                       
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Enter Notes</div>
              <textarea class="form-control" placeholder="Enter Notes" id="reply_note" style="height: 200px;"></textarea>
              
              <input type="hidden" id="select_type3" name="" />
              <input type="hidden" id="note_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button note_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Bank</h4>
                        
        </div>
        <div class="modal-body" >
          <form id="bank_form">
          <div class="form-group">
            BanK Name
              <input type="text" name="bank_name" class="form-control"  id="bank_name" placeholder="Bank Name" required>
            </div>
            <div class="form-group">Bank Account Name
              <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Bank Account Name" required>
            </div>
            <div class="form-group">Bank Account Number
              <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Bank Account Number" required>
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
<!-- <div class="modal fade trade_details_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Trade Details</h4>
                        
        </div>
        <div class="modal-body" >
          <form id="trade_form">
            <label> Client Name: <spam class="client_name"></spam></label><br/>
            <label> Client Code: <spam class="client_code"></spam></label><br/>
            <div class="form-group" style="margin-top:20px">
              Products & Services
              <input type="text" name="products_services" class="form-control"  id="products_services" placeholder="Products & Services" required>
            </div>
            <div class="form-group">Transaction Type
              <input type="text" name="transaction_type" class="form-control" id="transaction_type" placeholder="Transaction Type" required>
            </div>
            <div class="form-group">Risk Factors
              <input type="text" name="risk_factors" class="form-control" id="risk_factors" placeholder="Risk factors" required>
            </div>
            <div class="form-group">Geo Area of Operation
              <input type="text" name="geo_area" class="form-control" id="geo_area" placeholder="Geo Area of Operation" required>
            </div>
            <input type="hidden" id="trade_current_client_id" name="" />
          @csrf
</form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button trade_submit"  value="Add Trade details">
        </div>
      </div>
  </div>
</div> -->
<div class="modal fade bank_detail_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Add Bank</h4>           
            
        </div>
        <div class="modal-body">

          <table class="display nowrap fullviewtablelist"  id="bank_expand">
            <thead>
              <th>#</th>
              <th>Bank Name</th>
              <th>Account Name</th>
              <th>Account Number</th>
            </thead>
            <tbody id="bank_detail_body">
              
            </tbody>
          </table>
          
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>


<div class="modal fade review_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Review</h4>           
            
        </div>
        <div class="modal-body">
          <form id="review_form">
            <div class="form-group">
              Select Date
              <label class="input-group datepicker-only-init_date_received">
                  <input type="text" class="form-control review_date" id="review_date" placeholder="Select Date" name="review_date" style="font-weight: 500;" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
            </div>
            <div class="form-group">
              Review By
              <textarea class="form-control" id="reivew_filed" name="reivew_filed" required></textarea>
            </div>
            <input type="hidden" id="review_current_client_id" name="reviewed_by">
          @csrf
</form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button review_submit"  value="Review">
        </div>
      </div>
  </div>
</div>
<div class="modal fade notify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="float:left;width:35%">Request/Manage ID Files</h4>
        <input type="button" name="clear_selection" class="btn btn-primary common_black_button" id="clear_selection" value="Clear Selection" style="float:right;margin-right:30px;margin-top: 10px;">
        <input type="checkbox" name="notify_radio" id="identity_received"><label for="identity_received" style="float:right;margin-right:30px;margin-top: 16px;">Hide Accounts with ID</label>
        <input type="checkbox" name="notify_radio" id="inactive_clients"><label for="inactive_clients" style="float:right;margin-right:60px;margin-top: 16px;">Inactive Clients</label>
      </div>
      <div class="modal-body notify_place_div modal_max_height">
      </div>
      <div class="modal-footer">
        <input type="hidden" id="notify_type" value="">
        <input type="button" class="btn btn-primary common_black_button" id="email_notify" value="Email Notify Options">
      </div>
    </div>
  </div>
</div>
<div class="modal fade date_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Date Client Since</h4>           
            
        </div>
        <div class="modal-body">
          <div class="form-group">
            Select Date
            <label class="input-group datepicker-only-init_date_received">
                <input type="text" class="form-control client_date_since" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </label>
          </div>
          <input type="hidden" id="date_since_current_client_id" name="">
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button date_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade edit_review_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Review</h4>           
            
        </div>
        <div class="modal-body">
          <div class="form-group">
            Select Date
            <label class="input-group datepicker-only-init_date_received">
                <input type="text" class="form-control review_date_edit" id="review_date_edit" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </label>
          </div>
          <div class="form-group">
            Review By
            <textarea class="form-control" id="reivew_filed_edit"></textarea>
          </div>
          <input type="hidden" id="review_current_client_id_edit" name="">
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button review_edit_submit"  value="Review">
        </div>
      </div>
  </div>
</div>


<div class="modal fade report_modal_csv" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">AML Client Report</h4>
            <div class="col-lg-12 report_model_selectall">
              <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>
              <div class="col-lg-4" style="padding: 0px;"><input type="checkbox" class="hide_all_inactive_class_csv" id="hide_all_inactive_class_csv" value="1" style="padding-top: 20px;"><label for="hide_all_inactive_class_csv" style="font-size: 14px; font-weight: normal;">Hide Inactive Clients</label></div>

            </div>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">          
            <table class="table own_table_white">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>                   
                  <th></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                      $cls = '';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                      $cls = 'inactive_cls_csv';
                    }
                    else{
                      $cls = 'inactive_cls_csv';
                    }

                    $output.='
                    <tr class="'.$cls.'">
                      <td '.$color.'>'.$i.'</td>
                      <td><input type="checkbox" name="report_client" class="select_client_csv" data-element="'.$client->client_id.'" value="'.$client->client_id.'" /><label>&nbsp;</label></td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.'</td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">
        </div>
      </div>
  </div>
</div>

<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">AML Client Report</h4>
            <div class="col-lg-12 report_model_selectall">

              <!-- <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div> -->
              <div class="col-lg-4" style="padding: 0px;"><strong>Maximum Clients selection is 20</strong></div>
              <div class="col-lg-4" style="padding: 0px;"><input type="checkbox" class="hide_all_inactive_class" id="hide_all_inactive_class" value="1" style="padding-top: 20px;"><label for="hide_all_inactive_class" style="font-size: 14px; font-weight: normal;">Hide Inactive Clients</label></div>

            </div>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">          
            <table class="table own_table_white">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>                   
                  <th></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                      $cls = '';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                      $cls = 'inactive_cls';
                    }
                    else{
                      $cls = 'inactive_cls';
                    }

                    $output.='
                    <tr class="'.$cls.'">
                      <td '.$color.'>'.$i.'</td>
                      <td><input type="checkbox" name="report_client" class="select_client" data-element="'.$client->client_id.'" value="'.$client->client_id.'" /><label>&nbsp;</label></td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.' &nbsp;&nbsp;&nbsp;<a href="javascript:" class="fa fa-download common_black_button download_aml_report_single" data-element="'.$client->client_id.'" title="Download '.$client->company.' AML Report"></a></td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
        </div>
      </div>
  </div>
</div>

<div class="modal fade client_identity_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Client Identity Manager - <spam id="client_identity_client_code_title"></spam> - <spam id="client_identity_client_name_title"></spam></h4>           
            
        </div>
        <div class="modal-body">
          <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
          <form action="<?php echo URL::to('user/aml_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
              <input name="client_identity_client_code" type="hidden" id="client_identity_client_code" value="">                  
          @csrf
</form> 
          <div class="col-md-12" style="margin-top: 20px;margin-bottom: 20px;">
            <a class="standard_file_name_cls_single common_black_button" href="javascript:" style="font-size: 13px; font-weight: 500;">Rename ID Attachment files</a>

            <a class="request_updated_id_files common_black_button" href="javascript:" style="font-size: 13px; font-weight: 500;">Request Updated ID files</a>

            <a class="request_id_files common_black_button" href="javascript:" style="font-size: 13px; font-weight: 500;">Request ID files</a>
          </div>
          <table class="table display nowrap fullviewtablelist"  id="client_identity_expand">
            <thead>
              <th>Filename</th>
              <th>Photo ID / Other ID</th>
              <th>Expiry Date</th>
              <th>Action</th>
            </thead>
            <tbody id="client_identity_body">
              
            </tbody>
          </table>
          
        </div>
        <div class="modal-footer">            
        </div>
      </div>
  </div>
</div>
<div class="modal fade request_updated_id_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="email_request_updated_id_form" action="<?php echo URL::to('user/email_unsent_files'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Request Updated ID Files</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="from_user_req" id="from_user_req" class="form-control" required>
                <?php echo $userlistt; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user_req" id="to_user_req" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_unsent_req" id="cc_unsent_req" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent_req" id="subject_unsent_req" class="form-control input-sm subject_unsent_req" value="" required>
            </div>
          </div>
          <?php
          if($aml_details->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($aml_details->email_header_url.'/'.$aml_details->email_header_filename);
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
              <textarea name="message_editor1" id="editor1">
              </textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button email_request_updated_id_btn" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<div class="modal fade request_id_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="email_request_id_form" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Request ID Files</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <div class="col-md-5">
              <select name="from_user_id" id="from_user_id" class="form-control" required>
                <?php echo $userlistt; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user_id" id="to_user_id" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_unsent_id" id="cc_unsent_id" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">Subject:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="subject_unsent_id" id="subject_unsent_id" class="form-control input-sm subject_unsent_id" value="" required>
            </div>
          </div>
          <?php
          if($aml_details->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($aml_details->email_header_url.'/'.$aml_details->email_header_filename);
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
              <textarea name="message_editor_5" id="editor_5">
              </textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button email_request_id_btn" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<?php
$aml_settings = DB::table('aml_settings')->where('practice_code', Session::get('user_practice_code'))->first();
$aml_cc_email = '';
if($aml_settings){
  $aml_cc_email = $aml_settings->aml_cc_email;
}
?>
<div class="modal fade aml_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title">AML Settings</h4>
            </div>
            <div class="modal-body" id="aml_body">  
              <div class="col-lg-12 text-left padding_00">
                  <form name="aml_settings_form" id="aml_settings_form" method="post" action="<?php echo URL::to('user/update_aml_settings'); ?>">
                    <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 28%;margin-top: 13px;">Email Header Image:</spam>
                    <?php
                    if($aml_settings->email_header_url == '') {
                      $default_image = DB::table("email_header_images")->first();
                      if($default_image->url == "") {
                        $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                      }
                      else{
                        $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                      }
                    }
                    else {
                      $image_url = URL::to($aml_settings->email_header_url.'/'.$aml_settings->email_header_filename);
                    }
                    ?>
                    <img src="<?php echo $image_url; ?>" class="aml_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                    <input type="button" name="aml_email_header_img_btn" class="common_black_button aml_email_header_img_btn" value="Browse">
                    <h4 style="margin-top:20px">Enter AML CC Email Box:</h4>
                    <input type="text" name="aml_cc_input" class="form-control aml_cc_input" value="<?php echo $aml_settings->aml_cc_email; ?>">

                    <h4 style="margin-top:20px">Email Body for ID Request:</h4>
                    <textarea name="editoramlbody" id="editoramlbody"><?php echo $aml_settings->email_body; ?></textarea>

                    <h4 style="margin-top:20px">Enter Email Signature:</h4>
                    <textarea name="editoramlsignature" id="editoramlsignature"><?php echo $aml_settings->email_signature; ?></textarea>
                    
                    <div class="modal-footer">  
                        <input type="submit" name="submit_aml_settings" class="common_black_button submit_aml_settings" value="Submit">
                    </div>
                  @csrf
                  </form>
              </div>
            </div>
            <div class="modal-footer" style="clear:both">  
            </div>
        </div>
  </div>
</div>
<div class="modal fade aml_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_aml_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
                AML System                  
            </h4>        
            <div class="col-lg-12 padding_00">
              <?php $check_incomplete = DB::table('user_login')->where('userid',1)->first(); if($check_incomplete->aml_incomplete == 1) { $inc_checked = 'checked'; } else { $inc_checked = ''; } ?>
                <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked?> ><label for="show_incomplete" style="float: left;margin-left: 10px;margin-top: 5px;margin-right:10px">Hide/Show Inactive Clients</label>

                  <a class="standard_file_name_cls common_black_button" href="javascript:" style="font-size: 13px; font-weight: 500;float: left;">Rename ID Attachment files</a>
                  <div class="dropdown" style="float: left;">
                    <button class="common_black_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      AML Client Report &nbsp;&nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target=".report_modal" style="background: #fff;color: #000;width: 100% !important;text-align: left;float: left;padding: 10px;">Report as PDF</a>
                      <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target=".report_modal_csv" style="background: #fff;color: #000;width: 100% !important;text-align: left;float: left;padding: 10px;">Report as CSV</a>
                    </div>
                  </div>
                  <a href="javascript:" class="notify_aml common_black_button" id="notify_aml" style="font-size: 13px; font-weight: 500;float: left;">Request/Manage ID Files</a>
                  <a href="javascript:" class="import_aml common_black_button" id="import_aml" style="font-size: 13px; font-weight: 500;float: left;">Import AML Details</a>
                  <a href="javascript:" class="fa fa-cog aml_settings_btn common_black_button" id="aml_settings_btn" title="AML Settings" style="font-size: 13px; font-weight: 500;padding: 8px;float: left;"></a>

              </div>
          
  <div class="table-responsive" style="max-width: 100%; float: left;margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}

</style>
<table class="tablefixed nowrap fullviewtablelist" id="aml_expand" width="100%">
                        <thead>
                        <tr style="background: #fff;">
                            <th style="text-align: left;"><p style="width:55px;margin-top: 9px;">S.No <i class="fa fa-sort" style="float:right;margin-top:5px"></i></p></th>
                            <th style="text-align: left;"><p style="width:90px;margin-top: 9px;">Client ID <i class="fa fa-sort" style="float:right;margin-top:5px"></i></p></th>
                            <th style="text-align: center;">ActiveClient</th>
                            <th style="text-align: left;">Company <i class="fa fa-sort" style="float:right;margin-top:5px"></i></th>
                            <th style="text-align: left;width:10%">Client Source</th>
                            <th style="text-align: left;">Date Client Since</th>
                            <th style="text-align: left">Client Identity</th> 
                            <th style="text-align: left;">Products & Services</th> 
                            <th style="text-align: left;">Transaction Type</th>
                            <th style="text-align: left;">Risk Factors</th>
                            <th style="text-align: left;">Geo Area of Operation</th>
                            <th style="text-align: left;">Politically Exposed Person</th>
                            <th style="text-align: left;">High Risk Country</th>
                            <th style="text-align: left;">Legal Format of Company</th>
                            <th style="text-align: left;">Email ID request</th> 
                            <th style="text-align: left;">Bank Accounts</th>                             
                            <th style="text-align: left;">File review</th>
                            <th style="text-align: left;">Risk Category</th>                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(($clientlist)){              
                              foreach($clientlist as $key => $client){
                                $disabled='';
                                if($client->active != "")
                                {
                                  if($client->active == 2)
                                  {
                                    $disabled='disabled';
                                  }
                                  $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                                  $style="color:#".$check_color->classcolor."";
                                }
                                else{
                                  $style="color:#000";
                                }

                                $aml_system = DB::table('aml_system')->where('client_id', $client->client_id)->first();

                                if(($aml_system)){
                                  $risk_select = $aml_system->risk_category;                                    
                                }
                                else{
                                  $risk_select = '';
                                }   
                                $aml_attachement = DB::table('aml_attachment')->where('client_id', $client->client_id)->get();
                                $output_attached='';
                                if(($aml_attachement)){
                                  foreach ($aml_attachement as $attached) {

                                    $current_date = strtotime(date('Y-m-d'));
                                    $expiry_date = strtotime($attached->expiry_date);

                                    $attach_color = '#000';
                                    if($attached->expiry_date != '0000-00-00') { if($current_date > $expiry_date) { $attach_color = '#f00'; } }

                                    if($attached->standard_name == "")
                                    {
                                        if(strlen($attached->attachment) > 20){
                                            $att = substr($attached->attachment,0,20);
                                        } else {
                                            $att = $attached->attachment;
                                        }

                                        if($attached->identity_type == 1) { $iden = 'P'; } else { $iden = 'O'; }
                                      $output_attached.='
                                      <a class="id_attach_'.$attached->id.'" href="'.URL::to('/'.$attached->url.'/'.$attached->attachment).'" title="'.$attached->attachment.'" style="color:'.$attach_color.'" download>'.$att.' ('.$iden.')</a><br/>';
                                    }
                                    else{
                                        if(strlen($attached->standard_name) > 20){
                                            $att = substr($attached->standard_name,0,20);
                                        } else {
                                            $att = $attached->standard_name;
                                        }

                                        if($attached->identity_type == 1) { $iden = 'P'; } else { $iden = 'O'; }
                                      $output_attached.='
                                      <a class="id_attach_'.$attached->id.'" href="'.URL::to('/'.$attached->url.'/'.$attached->attachment).'" title="'.$attached->standard_name.'" style="color:'.$attach_color.'" download="'.$attached->standard_name.'">'.$att.' ('.$iden.')</a><br/>';
                                    }
                                  }
                                }
                                else{
                                  $output_attached.='';
                                }
                                if(($aml_attachement) != '' ){
                                  $image_plus_sapce='margin-top:10px;';
                                }
                                else{
                                  $image_plus_sapce='margin-top:0px;';
                                }
                                if(($aml_system)){
                                  if($aml_system->review == 1){
                                    $output_reveiw = 'Date:'.date('d-M-Y', strtotime($aml_system->review_date)).'</br/>Review By: '.$aml_system->file_review.'<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="'.$client->client_id.'"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="'.$client->client_id.'"></i></a>';
                                  }
                                  else{
                                    $output_reveiw = '
                                    <div class="select_button" style=" margin-left: 10px;">
                                      <ul>                                    
                                      <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                    </ul>
                                  </div>';
                                  }  
                                }  
                                else{
                                  $output_reveiw = '
                                  <div class="select_button" style=" margin-left: 10px;">
                                    <ul>                                    
                                      <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                    </ul>
                                  </div>';
                                }
                                if(($aml_system)){
                                  if($aml_system->client_source == 1){
                                    $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $aml_system->client_source_detail)->first();
                                    
                                    if(strlen('Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt') > 20)
                                    {
                                        $client_source_output = substr('Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt',0,20).'...';
                                    }
                                    else{
                                        $client_source_output = 'Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt';
                                    }
                                    
                                    $client_source ='<a href="javascript:" data-text="Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'" class="download_client_source" title="Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt">'.$client_source_output.'</a>

                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                  }
                                  elseif($aml_system->client_source == 2){
                                    $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', $aml_system->client_source_detail)->first();   
                                    
                                    if(strlen('Partner - '.$user_details->lastname.' '.$user_details->firstname.'.txt') > 20)
                                    {
                                        $client_source_output = substr('Partner - '.$user_details->lastname.' '.$user_details->firstname.'.txt',0,20).'...';
                                    }
                                    else{
                                        $client_source_output = 'Partner - '.$user_details->lastname.' '.$user_details->firstname.'.txt';
                                    }
                                    
                                    $client_source ='<a href="javascript:" data-text="Partner - '.$user_details->lastname.' '.$user_details->firstname.'" class="download_client_source" title="Partner - '.$user_details->lastname.' '.$user_details->firstname.'.txt">'.$client_source_output.'</a>


                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                  }
                                  elseif($aml_system->client_source == 3){
                                    if(strlen('Note - '.$aml_system->client_source_detail.'.txt') > 20)
                                    {
                                        $client_source_output = substr('Note - '.$aml_system->client_source_detail.'.txt',0,20).'...';
                                    }
                                    else{
                                        $client_source_output = 'Note - '.$aml_system->client_source_detail.'.txt';
                                    }
                                    $client_source = '<a href="javascript:" data-text="Note - '.$aml_system->client_source_detail.'" class="download_client_source" title="Note - '.$aml_system->client_source_detail.'.txt">'.$client_source_output.'</a>

                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                    <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                  }
                                  else{
                                    $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                    <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                    <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';
                                  }
                                }
                                else{
                                  $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                    <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                    <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';
                                }
                                $aml_bank = DB::table('aml_bank')->where('client_id', $client->client_id)->first();
                                $aml_count = DB::table('aml_bank')->where('client_id', $client->client_id)->count();
                                if(($aml_bank)){
                                  $bank_output='<a href="javascript:" class="bank_detail_class" data-element="'.$client->client_id.'">'.$aml_count.'</a>
                                  <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="'.$client->client_id.'" style="margin-left:10px;"></i></a>
                                  ';
                                }
                                else{
                                  $bank_output='
                                  <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="'.$client->client_id.'"></i></a>
                                  ';
                                }
                                $cli_det = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $client->client_id)->first();
                                if(($cli_det))
                                {
                                  if($cli_det->client_added == "")
                                  {
                                    $output_client_since = 'No Date Set';
                                  }
                                  else{
                                    $explode_date = explode("/",$cli_det->client_added);
                                    $explode_hyphen_date = explode("-",$cli_det->client_added);
                                    if(count($explode_date) > 1)
                                    {
                                      //$client_added  = DateTime::createFromFormat('d/m/Y', $cli_det->client_added);
                                      $client_added_since = $cli_det->client_added; //$client_added->format('d-M-Y');
                                    }
                                    elseif(count($explode_hyphen_date) > 1)
                                    {
                                      //$client_added  = DateTime::createFromFormat('d-m-Y', $cli_det->client_added);
                                      $client_added_since = $cli_det->client_added; //$client_added->format('d-M-Y');
                                    }
                                    else{
                                      $client_added_since  = $cli_det->client_added;
                                    }

                                    $output_client_since = $client_added_since;
                                  }
                                }
                                else{
                                  $output_client_since = 'No Date Set';
                                }
                                ?>
                                <tr class="edit_task <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->id; ?>">
                                  <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                                  <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" data-element="<?php echo $client->client_id; ?>" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></a></td>
                                  <td align="center"><img class="active_client_list_tm1" data-iden="<?php echo $client->client_id; ?>" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
                                  <td align="left"><a href="javascript:" class="fa fa-download common_black_button download_aml_report_single" data-element="<?php echo $client->client_id; ?>" title="Download <?php echo $client->company; ?> AML Report"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class"  data-element="<?php echo $client->client_id; ?>" style="<?php echo $style; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                  <td align="left" style="<?php echo $style; ?>" id="client_source_<?php echo $client->client_id?>">
                                    <?php echo $client_source?>
                                  </td>
                                  <td align="left" id="client_since_<?php echo $client->client_id?>" style="<?php echo $style; ?>">
                                    <?php echo $output_client_since?>
                                  </td>
                                  <td align="left">
                                    <div id="client_identity_<?php echo $client->client_id?>"><?php echo $output_attached?></div>
                                    <i class="fa fa-plus fa-plus-add add_client_identity_files" style="cursor: pointer; color: #000; <?php echo $image_plus_sapce?>" aria-hidden="true" title="Add Attachment" data-element="<?php echo $client->client_id?>"></i> 
                                    <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>
                                    <div id="add_attachments_div">
                                    </div>
                                    <div class="img_div img_div_add" id="img_div_<?php echo $client->client_id?>" style="z-index:9999999; margin-left: -120px; min-height: 275px">
                                      <form name="image_form" style="margin-bottom: 0px !important;" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">@csrf
</form> 
                                      <div class="image_div_attachments">
                                        
                                      </div>
                                      <div class="select_button" style=" margin-top: 10px;">
                                        <ul>                                    
                                          <li><a href="javascript:" class="image_submit" data-element="<?php echo $client->client_id?>" style="font-size: 13px; font-weight: 500;">Submit</a></li>
                                        </ul>
                                      </div>
                                    </div>
                                  </td>
                                  <td id="client_trade_<?php echo $client->client_id?>" style="text-align:center; width: 180px; ">
                                    <?php 
                                    if(($aml_system)){
                                      $products_services = $aml_system->products_services;
                                      $transaction_type = $aml_system->transaction_type;
                                      $risk_factors = $aml_system->risk_factors;
                                      $geo_area = $aml_system->geo_area;
                                      $politically_exposed = $aml_system->politically_exposed;
                                      $high_risk = $aml_system->high_risk;
                                    }
                                    else{
                                      $products_services = "";
                                      $transaction_type = "";
                                      $risk_factors = "";
                                      $geo_area = "";
                                      $politically_exposed = 0;
                                      $high_risk = 0;
                                    }
                                    ?>
                                    <input type="text" name="products_services" class="form-control products_services"  id="products_services<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" placeholder="Products & Services" value="<?php echo $products_services; ?>">
                                    </td>
                                    <td align="left">
                                        <input type="text" name="transaction_type" class="form-control transaction_type" id="transaction_type<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" placeholder="Transaction Type" value="<?php echo $transaction_type; ?>">
                                    </td>
                                    <td align="left">
                                        <input type="text" name="risk_factors" class="form-control risk_factors" id="risk_factors<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" placeholder="Risk factors" value="<?php echo $risk_factors; ?>" />
                                    </td>
                                    <td align="left">
                                        <input type="text" name="geo_area" class="form-control geo_area" id="geo_area<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>" placeholder="Geo Area of Operation" value="<?php echo $geo_area; ?>" />
                                    </td>
                                    <td align="left">
                                        <input type="radio" name="politically_exposed<?php echo $client->client_id; ?>" class="politically_exposed politically_exposed<?php echo $client->client_id; ?>" id="politically_exposed_yes<?php echo $client->client_id; ?>" value="1" data-element="<?php echo $client->client_id; ?>" <?php if($politically_exposed == 1) { echo 'checked'; } ?> /><label for="politically_exposed_yes<?php echo $client->client_id; ?>">Yes</label>
                                            <input type="radio" name="politically_exposed<?php echo $client->client_id; ?>" class="politically_exposed politically_exposed<?php echo $client->client_id; ?>" id="politically_exposed_no<?php echo $client->client_id; ?>" value="0" data-element="<?php echo $client->client_id; ?>" <?php if($politically_exposed == 0) { echo 'checked'; } ?> /><label for="politically_exposed_no<?php echo $client->client_id; ?>">No</label>
                                    </td>
                                    <td align="left">
                                        <input type="radio" name="high_risk<?php echo $client->client_id; ?>" class="high_risk high_risk<?php echo $client->client_id; ?>" id="high_risk_yes<?php echo $client->client_id; ?>" value="1" data-element="<?php echo $client->client_id; ?>" <?php if($high_risk == 1) { echo 'checked'; } ?> /><label for="high_risk_yes<?php echo $client->client_id; ?>">Yes</label>
                                            <input type="radio" name="high_risk<?php echo $client->client_id; ?>" class="high_risk high_risk<?php echo $client->client_id; ?>" id="high_risk_no<?php echo $client->client_id; ?>" value="0" data-element="<?php echo $client->client_id; ?>" <?php if($high_risk == 0) { echo 'checked'; } ?> /><label for="high_risk_no<?php echo $client->client_id; ?>">No</label>
                                    </td>
                                    <td align="left">
                                        <input type="text" name="legal_format" class="form-control" id="legal_format<?php echo $client->client_id; ?>" placeholder="Legal Format of Company" value="<?php echo $client->tye; ?>" disabled />
                                            <input type="hidden" id="trade_current_client_id<?php echo $client->client_id; ?>" name="<?php echo $client->client_id; ?>" />
                                    </td>
                                  <?php
                                  if(($aml_system))
                                  {
                                    if($aml_system->last_email_sent == "0000-00-00 00:00:00") { $email_sent = ''; }
                                    else{ $email_sent = date('d M Y @ H:i', strtotime($aml_system->last_email_sent)); }
                                  }
                                  else{
                                    $email_sent = '';
                                  }
                                  ?>
                                  <td style="text-align:center; width: 180px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>"></a><br><?php echo $email_sent; ?><br></td>
                                  <td style="<?php echo $style; ?>" align="left"  id="client_bank_<?php echo $client->client_id?>">
                                    <?php echo $bank_output ?>
                                  </td>
                                  <td style="<?php echo $style; ?>" align="left"   id="review_<?php echo $client->client_id?>">
                                    <?php echo $output_reveiw?>
                                  </td>
                                  <td style="<?php echo $style; ?>" align="left">
                                    <select class="form-control risk_class" id="risk_class_<?php echo $client->client_id?>" data-element="<?php echo $client->client_id?>" >
                                      <option value="1" <?php if($risk_select == 1){echo 'selected';}else{echo'';}?>>Green</option>
                                      <option value="2" <?php if($risk_select == 2){echo 'selected';}else{echo'';}?>>Yellow</option>
                                      <option value="3" <?php if($risk_select == 3){echo 'selected';}else{echo'';}?>>Red</option>
                                    </select>
                                    
                                  </td>
                                </tr>
                            <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="11" align="center">Empty</td></tr>';
                            }
                          ?> 
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
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Reports are loaded.</p>
  <!-- <p class="apply_p" style="font-size:18px;font-weight: 600;">Producing Report: <span id="apply_first"></span> of <span id="apply_last"></span></p>
  <p class="apply_msg_p" style="font-size:18px;font-weight: 600;"> Please wait while the Report is being Downloaded.</p> -->
</div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script type="text/javascript">
$(".products_services").blur(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});
$(".transaction_type").blur(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});
$(".risk_factors").blur(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});
$(".geo_area").blur(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});
$(".politically_exposed").change(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});
$(".high_risk").change(function() {
  var client_id = $(this).attr("data-element");
  save_trade_details(client_id);
});

function runAfterAjax(){
  $(".products_services_td").blur(function() {
  var client_id = $(this).attr("data-element");
  var value = $(this).val();
  $("#products_services"+client_id).val(value);
  save_trade_details_td(client_id);
});
$(".transaction_type_td").blur(function() {
  var client_id = $(this).attr("data-element");
  var value = $(this).val();
  $("#transaction_type"+client_id).val(value);
  save_trade_details_td(client_id);
});
$(".risk_factors_td").blur(function() {
  var client_id = $(this).attr("data-element");
  var value = $(this).val();
  $("#risk_factors"+client_id).val(value);
  save_trade_details_td(client_id);
});
$(".geo_area_td").blur(function() {
  var client_id = $(this).attr("data-element");
  var value = $(this).val();
  $("#geo_area"+client_id).val(value);
  save_trade_details_td(client_id);
});
$(".politically_exposed_td").change(function() {
  var client_id = $(this).attr("data-element");
  var value = $(".politically_exposed_td:checked").val();
  if(value == "1"){
    $("#politically_exposed_yes"+client_id).prop("checked",true);
    $("#politically_exposed_no"+client_id).prop("checked",false);
  }
  else{
    $("#politically_exposed_yes"+client_id).prop("checked",false);
    $("#politically_exposed_no"+client_id).prop("checked",true);
  }
  save_trade_details_td(client_id);
});
$(".high_risk_td").change(function() {
  var client_id = $(this).attr("data-element");
  var value = $(".high_risk_td:checked").val();
  if(value == "1"){
    $("#high_risk_yes"+client_id).prop("checked",true);
    $("#high_risk_no"+client_id).prop("checked",false);
  }
  else{
    $("#high_risk_yes"+client_id).prop("checked",false);
    $("#high_risk_no"+client_id).prop("checked",true);
  }
  save_trade_details_td(client_id);
});
}

function save_trade_details(client_id){
  var products_services = $("#products_services"+client_id).val();
  var transaction_type = $("#transaction_type"+client_id).val();
  var risk_factors = $("#risk_factors"+client_id).val();
  var geo_area = $("#geo_area"+client_id).val();

  var politically_exposed = $(".politically_exposed"+client_id+":checked").val();
  var high_risk = $(".high_risk"+client_id+":checked").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_add_trade'); ?>",
    type:"post",
    data:{current_client_id:client_id,products_services:products_services,transaction_type:transaction_type,risk_factors:risk_factors,geo_area:geo_area,politically_exposed:politically_exposed,high_risk:high_risk},
    dataType:"json",
    success: function(result)
    {
      //$(e.target).attr("disabled",true);
      //$('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });
}

function save_trade_details_td(client_id){
  var products_services = $("#products_services_td"+client_id).val();
  var transaction_type = $("#transaction_type_td"+client_id).val();
  var risk_factors = $("#risk_factors_td"+client_id).val();
  var geo_area = $("#geo_area_td"+client_id).val();

  var politically_exposed = $(".politically_exposed_td:checked").val();
  var high_risk = $(".high_risk_td:checked").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_add_trade'); ?>",
    type:"post",
    data:{current_client_id:client_id,products_services:products_services,transaction_type:transaction_type,risk_factors:risk_factors,geo_area:geo_area,politically_exposed:politically_exposed,high_risk:high_risk},
    dataType:"json",
    success: function(result)
    {
      //$(e.target).attr("disabled",true);
      //$('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });
}

$(".image_file").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '<label class="attachments_label">Attachments : </label>';
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
  }
  $(this).parent().find(".image_div_attachments").html(htmlcontent);
});
$(".image_file_add").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '';
  var attachments = $('#add_attachments_div').html();
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';
    }
  }
  $('#add_attachments_div').html(attachments+' '+htmlcontent);
  $('#add_attachments_div_td').html(attachments+' '+htmlcontent);
  $("#attachments_text").show();
  $("#attachments_text_td").show();
  $(".img_div").hide();
});


fileList = new Array();
Dropzone.options.imageUpload1 = {
    acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            
            var output = '<tr><td><a href="'+obj.fullurl+'" title="'+obj.filename+'" download>'+obj.filename+'</a></td><td><input type="radio" name="identity_type_'+obj.id+'" class="identity_type identity_type_'+obj.client_id+'" id="identity_type_photo_'+obj.id+'" data-element="'+obj.client_id+'" data-attach="'+obj.id+'" value="1"><label for="identity_type_photo_'+obj.id+'">Photo ID</label><input type="radio" name="identity_type_'+obj.id+'" class="identity_type identity_type_'+obj.client_id+'" id="identity_type_other_'+obj.id+'" checked data-element="'+obj.client_id+'" data-attach="'+obj.id+'" value="0"><label for="identity_type_other_'+obj.id+'">Other ID</label></td><td><input type="text" name="identity_expiry_date" class="identity_expiry_date" value="" placeholder="dd-mmm-yyyy" data-element="'+obj.id+'"></td><td><a class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'+obj.id+'"></a></td></tr>';

            $("#client_identity_body").append(output);

            var out = '<a href="'+obj.fullurl+'" title="'+obj.filename+'" download>'+obj.filename+' (O)</a><br/>';

            $("#client_identity_"+obj.client_id).append(out);
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            setDatetimepickerfunction();
            Dropzone.forElement("#imageUpload1").removeAllFiles(true);
            $(".dz-message").find("span").html("Drop files here to upload");
          }
         });
        this.on("error", function (file) {
            console.log(file);
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
Dropzone.options.imageUploadtd1 = {
    acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $("#attachments_text_td").show();
              $("#add_attachments_div_td").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
          }
         });
        this.on("error", function (file) {
            console.log(file);
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
Dropzone.options.imageUpload2 = {
    acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#attachments_text_td").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $("#add_attachments_div_td").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
          }
         });
        this.on("error", function (file) {
            console.log(file);
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

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
        <?php } else if(isset($_GET['status_pin_invalid'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Crypt Pin You have entered is Incorrect.</p>",width:"30%",fixed:true});
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
$(".image_file").change(function(){

  var lengthval = $(this.files).length;

  var htmlcontent = '<label class="attachments_label">Attachments : </label>';

  for(var i=0; i<= lengthval - 1; i++)

  {

    var sno = i + 1;

    if(htmlcontent == "")

    {

      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

    else{

      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

  }

  $(this).parent().find(".image_div_attachments").html(htmlcontent);

});
$(window).change(function(e){
if($(e.target).hasClass('risk_class_td')){
  var client_id = $(e.target).attr('data-element');
  var value = $(e.target).val();
  $("#risk_class_"+client_id).val(value);
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_risk_update'); ?>",
    type:"post",
    data:{id:client_id,value:value},
    dataType:"json",
    success: function(result)
    {
      
    }
  });
}

if($(e.target).hasClass('risk_class')){
  var client_id = $(e.target).attr('data-element');
  var value = $(e.target).val();
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_risk_update'); ?>",
    type:"post",
    data:{id:client_id,value:value},
    dataType:"json",
    success: function(result)
    {
      
    }
  });
}

if($(e.target).hasClass('identity_expiry_date')) {
  var attach_id = $(e.target).attr("data-element");
  var value = $(e.target).val();

  $.ajax({
    url:"<?php echo URL::to('user/set_identity_expiry_date'); ?>",
    type:"post",
    data:{attach_id:attach_id,value:value},
    success:function(result){

    }
  })
}
})

function countapply(count,len)
{
  $("#apply_first").html(count);
  if(count != 0 && count < len){
    setTimeout(function(){
      countapply(count + 1,len);
    },500);
  }
}

function setDatetimepickerfunction()
{
  $(".identity_expiry_date").datetimepicker({   
           format: 'L',
           format: 'DD-MMM-YYYY'
        });

  $(".identity_expiry_date").on("dp.hide", function (e) {
    var attach_id = $(e.target).attr("data-element");
    var value = $(e.target).val();

    $.ajax({
      url:"<?php echo URL::to('user/set_identity_expiry_date'); ?>",
      type:"post",
      data:{attach_id:attach_id,value:value},
      success:function(result){
        $(".id_attach_"+attach_id).css("color",result);
      }
    })
  });
}
$(window).click(function(e) {
  if($(e.target).hasClass('aml_settings_btn')) {
    $(".aml_settings_modal").modal("show");
  }
  if($(e.target).hasClass('aml_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the AML Email Header Image?");
    if(r) {
      $(".aml_change_header_image").modal("show");
      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('active_client_list_pms'))
  {
    var client_id=$("#client_search").val();
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
  if($(e.target).hasClass('email_request_id_btn'))
  {
    if($("#email_request_id_form").valid())
    {

      $("body").addClass("loading");
      //var content = CKEDITOR.instances['editor1'].getData();
      var to = $("#to_user_id").val();
      var from = $("#from_user_id").val();
      var subject = $(".subject_unsent_id").val();
      var client_id = $("#client_identity_client_code").val();

      var cc = $("#cc_unsent_id").val();

      $(".request_id_modal").modal("hide");
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
      $("body").removeClass("loading");
      // $.ajax({
      //   url:"<?php echo URL::to('user/aml_email_request_updated_id_files'); ?>",
      //   type:"post",
      //   data:{client_id:client_id,from:from,to:to,subject:subject,content:content,cc:cc},
      //   success: function(result)
      //   {
      //     $(".emailunsent").modal('hide');
      //     if(result == "0")
      //     {
      //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email Field is empty so email is not sent</p>", fixed:true});
      //     }
      //     else{
      //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
      //     }
      //     $("body").removeClass("loading");
      //   }
      // });
    }
  }
  if($(e.target).hasClass('email_request_updated_id_btn'))
  {
    if($("#email_request_updated_id_form").valid())
    {
      $("body").addClass("loading");
      //var content = CKEDITOR.instances['editor1'].getData();
      var to = $("#to_user_req").val();
      var from = $("#from_user_req").val();
      var subject = $(".subject_unsent_req").val();
      var client_id = $("#client_identity_client_code").val();

      var cc = $("#cc_unsent_req").val();

      $(".request_updated_id_modal").modal("hide");
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
      $("body").removeClass("loading");
      // $.ajax({
      //   url:"<?php echo URL::to('user/aml_email_request_updated_id_files'); ?>",
      //   type:"post",
      //   data:{client_id:client_id,from:from,to:to,subject:subject,content:content,cc:cc},
      //   success: function(result)
      //   {
      //     $(".emailunsent").modal('hide');
      //     if(result == "0")
      //     {
      //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email Field is empty so email is not sent</p>", fixed:true});
      //     }
      //     else{
      //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
      //     }
      //     $("body").removeClass("loading");
      //   }
      // });
    }
  }
  if($(e.target).hasClass('request_updated_id_files'))
  {
    var client_id = $("#client_identity_client_code").val();
    if (CKEDITOR.instances.editor1) CKEDITOR.instances.editor1.destroy();
    $.ajax({
      url:"<?php echo URL::to('user/email_request_updated_id_files'); ?>",
      type:"post",
      data:{client_id:client_id},
      dataType:"json",
      success:function(result)
      {
        $("#to_user_req").val(result['to']);
        $(".request_updated_id_modal").modal("show");

        CKEDITOR.replace('editor1',
        {
          height: '150px',
          enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
       });

        CKEDITOR.instances['editor1'].setData(result['message']);
      }
    })
  }
  if($(e.target).hasClass('request_id_files'))
  {
    var client_id = $("#client_identity_client_code").val();
    if (CKEDITOR.instances.editor_5) CKEDITOR.instances.editor_5.destroy();
    $.ajax({
      url:"<?php echo URL::to('user/email_request_id_files'); ?>",
      type:"post",
      data:{client_id:client_id},
      dataType:"json",
      success:function(result)
      {
        $("#to_user_id").val(result['to']);
        $(".request_id_modal").modal("show");

        CKEDITOR.replace('editor_5',
        {
          height: '150px',
          enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
       });

        CKEDITOR.instances['editor_5'].setData(result['message']);
      }
    })
  }
  if($(e.target).hasClass('identity_type'))
  {
    var client_id = $(e.target).attr("data-element");
    var attach_id = $(e.target).attr("data-attach");
    var value = $(".identity_type_"+client_id+":checked").val();

    $.ajax({
      url:"<?php echo URL::to('user/set_attachment_identity_type'); ?>",
      type:"post",
      data:{attach_id:attach_id,value:value},
      success:function(result){
        
      }
    })
  }
  if($(e.target).hasClass('import_aml'))
  {
      $("#import_modal").modal("show");
  }
  if($(e.target).hasClass('select_client'))
  {
    var checkedcount = $(".select_client:checked").length;
    if(checkedcount >= 20){
      $(".select_client:unchecked").prop("disabled",true);
    }
    else{
      $(".select_client:unchecked").prop("disabled",false);
    }
  }
  if($(e.target).hasClass('hide_all_inactive_class'))
  {
    if($(e.target).is(":checked"))
    {
      $(".inactive_cls").find(".select_client").prop("checked",false);
      $(".inactive_cls").hide();
    }
    else{
      $(".inactive_cls").find(".select_client").prop("checked",false);
      $(".inactive_cls").show();
    }

    var checkedcount = $(".select_client:checked").length;
    if(checkedcount >= 20){
      $(".select_client:unchecked").prop("disabled",true);
    }
    else{
      $(".select_client:unchecked").prop("disabled",false);
    }
  }
  if($(e.target).hasClass('hide_all_inactive_class_csv'))
  {
    if($(e.target).is(":checked"))
    {
      $(".inactive_cls_csv").find(".select_client_csv").prop("checked",false);
      $(".inactive_cls_csv").hide();
    }
    else{
      $(".inactive_cls_csv").find(".select_client_csv").prop("checked",false);
      $(".inactive_cls_csv").show();
    }
  }
  if($(e.target).hasClass('invoice_class'))
  {
    if (Dropzone.instances.imageUploadtd1) Dropzone.forElement("#imageUploadtd1").destroy();
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_aml_client_content'); ?>",
      type:"post",
      data:{client_id:client_id},
      dataType:"json",
      success:function(result){
        $("#aml_client_tbody").html(result['output']);
        $(".aml_client_code").html(client_id);
        $(".aml_client_name").html(result['client_name']);
        $(".aml_client_modal").modal("show");
        if (!Dropzone.instances.imageUploadtd1)
        {
          $("#imageUploadtd1").dropzone();
        }
        runAfterAjax();
      }
    })
  }
  if($(e.target).hasClass('notify_all_clients')){
    if($(e.target).is(":checked"))
    {
      $(".notify_option:visible").prop("checked",true);
    }
    else{
      $(".notify_option:visible").prop("checked",false);
    }
  }
  if($(e.target).hasClass('standard_file_name_cls'))
  {
    var countvalue = $("#clients_tbody").find(".edit_task").length;
    if(countvalue > 0) {
      $(".alert_modal").modal("show");
    }
    else{
      alert("No Clients Found");
    }
  }
  if($(e.target).hasClass('standard_file_name_cls_single'))
  {
    $(".alert_modal_single").modal("show");
  }
  if($(e.target).hasClass('yes_hit'))
  {
    $("body").addClass("loading");
    window.location.replace("<?php echo URL::to('user/standard_file_name'); ?>");
  }
  if($(e.target).hasClass('no_hit'))
  {
    $(".alert_modal").modal("hide");
  }
  if($(e.target).hasClass('yes_hit_single'))
  {
    $("body").addClass("loading");
    var client_id = $("#client_identity_client_code").val();

    $.ajax({
      url:"<?php echo URL::to('user/standard_file_name_single?client_id='); ?>"+client_id,
      type:"get",
      success: function(result) {
        $("#client_identity_body").html(result);
        $("body").removeClass("loading");
        $(".alert_modal_single").modal("hide");
        setDatetimepickerfunction();
      }
    })
    //window.location.replace("<?php echo URL::to('user/standard_file_name_single?client_id='); ?>"+client_id);
  }
  if($(e.target).hasClass('no_hit_single'))
  {
    $(".alert_modal").modal("hide");
  }
  if($(e.target).hasClass('download_client_source'))
  {
    var text = $(e.target).attr("data-text");
    $.ajax({
      url:"<?php echo URL::to('user/generate_aml_text_file'); ?>",
      type:"get",
      data:{text:text},
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers/aml_client_source'); ?>/"+result,result);
      }
    });
  }
  if(e.target.id == "inactive_clients")
  {
    $(".notify_place_div").find("tr").show();

    if($("#identity_received").is(":checked")){
      $(".notify_place_div").find(".identity_received").hide();
      $(".notify_place_div").find(".identity_received").find(".notify_option").prop("checked",false);
      $("#notify_all_clients").prop("checked",false);
    }
    else{
      $(".notify_place_div").find(".identity_received").show();
    }

    if($(e.target).is(":checked")){
      $(".notify_place_div").find(".inactive").hide();
      $(".notify_place_div").find(".inactive").find(".notify_option").prop("checked",false);
      $("#notify_all_clients").prop("checked",false);
    }
    else{
      $(".notify_place_div").find(".inactive").show();
    }
  }
  if(e.target.id == "identity_received")
  {
    $(".notify_place_div").find("tr").show();
    
    if($("#inactive_clients").is(":checked")){
      $(".notify_place_div").find(".inactive").hide();
      $(".notify_place_div").find(".inactive").find(".notify_option").prop("checked",false);
      $("#notify_all_clients").prop("checked",false);
    }
    else{
      $(".notify_place_div").find(".inactive").show();
    }
    
    if($(e.target).is(":checked")){
      $(".notify_place_div").find(".identity_received").hide();
      $(".notify_place_div").find(".identity_received").find(".notify_option").prop("checked",false);
      $("#notify_all_clients").prop("checked",false);
    }
    else{
      $(".notify_place_div").find(".identity_received").show();
    }
  }
  if(e.target.id == "clear_selection")
  {
    $(e.target).hide();
    $("#inactive_clients").prop("checked",false);
    $("#identity_received").prop("checked",false);
    $(".notify_place_div").find("tr").show();
  }
  // if($(e.target).hasClass('aml_notify'))
  // {
  //   if($('#inactive_clients').is(':checked') && $('#identity_received').is(':checked'))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#f00 !important", "font-weight": "600"});
  //     $(".identity_received").find("td:first").css({"color": "#1000ff !important", "font-weight": "600"});
  //   }
  //   else if($('#inactive_clients').is(':checked') && !($('#identity_received').is(':checked')))
  //   {
  //     $(".identity_received").find("td:first").css({"color": "#000 !important"});
  //     $(".inactive").find("td:first").css({"color": "#f00 !important", "font-weight": "600"});
  //   }
  //   else if($('#identity_received').is(':checked') && !($('#inactive_clients').is(':checked')))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#000 !important"});
  //     $(".identity_received").find("td:first").css({"color": "#1000ff !important", "font-weight": "600"});
  //   }
  //   else if(!($('#identity_received').is(':checked')) && !($('#inactive_clients').is(':checked')))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#000 !important"});
  //     $(".identity_received").find("td:first").css({"color": "#000 !important"});
  //   }
  // }
  if(e.target.id == 'notify_aml')
  {
    if (Dropzone.instances.imageUpload2) Dropzone.forElement("#imageUpload2").destroy();
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    $.ajax({
        url:"<?php echo URL::to('user/notify_tasks_aml'); ?>",
        type:"get",
        success: function(result) {
          $(".notify_modal").modal('show');
          $(".notify_place_div").html(result);
          setTimeout(function(){  
             CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 

             $(".dropzone").dropzone();
          },1000);


          $("#request_manage_expand").DataTable({
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              columnDefs: [
                 { orderable: false, targets: [2,3,4,5] }
              ]
          });
        }
    });
  }
  if(e.target.id == 'email_notify')
  {
    var countval = $(".notify_option:checked").length;
    if(countval > 0)
    {
      $(".notify_modal").modal('hide');
      var message = CKEDITOR.instances['editor_1'].getData();
      $("body").addClass("loading");
      var emails = [];
      var toemails = '';
      var timeval = "<?php echo time(); ?>";
      $(".notify_option").each(function(i, el) {
        var id = $(el).attr('data-element');
          if($(el).is(':checked'))
          {
            var user_email = $(el).parents('tr').find(".notify_primary_email").val();
            var secondary_email = $(el).parents('tr').find(".notify_secondary_email").val();
            if(user_email != '' && typeof user_email !== 'undefined')
            {
              if($.inArray(user_email, emails) == -1)
              {
                emails.push(user_email);
                if(toemails == '')
                {
                  toemails= user_email;
                }
                else{
                  toemails = toemails+', '+user_email;
                }
              }
            }
            if(secondary_email != '' && typeof secondary_email !== 'undefined')
            {
              if($.inArray(secondary_email, emails) == -1)
              {
                emails.push(secondary_email);
                if(toemails == '')
                {
                  toemails= secondary_email;
                }
                else{
                  toemails = toemails+', '+secondary_email;
                }
              }
            }
          }
      });
      toemails = toemails+', <?php echo $admin_cc; ?>';
      var option_length = emails.length;
      $.each( emails, function( i, value ) {
          setTimeout(function(){
            $.ajax({
              url:"<?php echo URL::to('user/email_notify_aml'); ?>",
              type:"get",
              data:{email:value,message:message,toemails:toemails,timeval:timeval},
              success: function(result) {
                var keyi = parseInt(i) + parseInt(1);
                if(option_length == keyi)
                {
                  $("body").removeClass("loading");
                }
              }
            });
          },2000 + ( i * 2000 ));
      });
    }
    else{
      alert("Please choose the clients to send the email.");
    }
  }
  if($(e.target).hasClass('email_unsent'))
  {
    $("body").addClass("loading");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      CKEDITOR.replace('editor_2',
               {
                height: '150px',
               }); 
    setTimeout(function() {
            
            var client_id = $(e.target).attr('data-element');
            $.ajax({
              url:'<?php echo URL::to('user/aml_edit_email_unsent_files'); ?>',
              type:'get',
              data:{client_id:client_id},
              dataType:"json",
              success: function(result)
              {
                  $(".subject_unsent").val(result['subject']);
                  $("#to_user").val(result['to']);
                  $(".emailunsent").modal('show');
                  $("#hidden_email_task_id").val(client_id);
                  CKEDITOR.instances['editor_2'].setData(result['html']);
              }
            })
        $("body").removeClass("loading");
    },7000);
  }
  if($(e.target).hasClass('email_unsent_button'))
  {
    if($("#emailunsent_form").valid())
    {
      $("body").addClass("loading");
      var content = CKEDITOR.instances['editor_2'].getData();
      var to = $("#to_user").val();
      var from = $("#from_user").val();
      var subject = $(".subject_unsent").val();
      var client_id = $("#hidden_email_task_id").val();
      var cc = $("#cc_unsent").val();

      $.ajax({
        url:"<?php echo URL::to('user/aml_email_unsent_files'); ?>",
        type:"post",
        data:{client_id:client_id,from:from,to:to,subject:subject,content:content,cc:cc},
        success: function(result)
        {
          $(".emailunsent").modal('hide');
          if(result == "0")
          {
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email Field is empty so email is not sent</p>", fixed:true});
          }
          else{
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
            $(".email_unsent_"+client_id).parents("td").html("<a href='javascript:' class='fa fa-envelope email_unsent email_unsent_"+client_id+"' data-element='"+client_id+"'></a><br>"+result);
          }
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/aml_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id},
      success: function(result)
      {
        var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;
        if(countval == 1)
        {
          $(e.target).parents(".dropzone").removeClass("dz-started");
        }
        $(e.target).parents(".dz-preview").detach();
      }
    })
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
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
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
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }

if($(e.target).hasClass('refresh_client_source')){
  $('body').addClass('loading');
  var client_id = $(e.target).attr('data-element');
  
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_client_source_refresh'); ?>",
    type:"post",
    data:{id:client_id},
    dataType:"json",
    success: function(result)
    {
      $("#client_source_"+result['id']).html(result['output']);
      $("#client_source_td_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
    }
  });

}

if($(e.target).hasClass('other_client')){
  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');

  $("#select_type").val(type);
  $("#current_client_id").val(current_client);  
  $("#client_search").val('');
  $(".client_search_class").val('');
}

if($(e.target).hasClass('other_submit')){
  var client_search = $("#client_search").val();
  if(client_search == "")
  {
    alert('Please choose the client to proceed.');
  }
  else{
    $('body').addClass('loading');
    var client_id = $("#client_search").val();  
    var type = $("#select_type").val();
    var current_client_id = $("#current_client_id").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_other_client'); ?>",
      type:"post",
      data:{client_id:client_id,type:type,current_client_id:current_client_id},
      dataType:"json",
      success: function(result)
      {
        $(".other_client_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $("#client_source_td_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}

if($(e.target).hasClass('partner_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#user_type").val('');

  $("#select_type2").val(type);
  $("#partner_current_client_id").val(current_client);  

}


if($(e.target).hasClass('partner_submit')){
  var user_type = $("#user_type").val();
  if(user_type == "")
  {
    alert("Please Choose the user from the dropdown to proceed.")
  }
  else{
    $('body').addClass('loading');  
    var type = $("#select_type2").val();
    var current_client_id = $("#partner_current_client_id").val();
    

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_partner'); ?>",
      type:"post",
      data:{type:type,current_client_id:current_client_id,user_type:user_type},
      dataType:"json",
      success: function(result)
      {
        $(".partner_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $("#client_source_td_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}

if($(e.target).hasClass('reply_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#reply_note").val('');

  $("#select_type3").val(type);
  $("#note_current_client_id").val(current_client);  

}

if($(e.target).hasClass('note_submit')){
  var reply_note = $("#reply_note").val();
  if(reply_note == "")
  {
    alert("Notes textarea is empty. Please Fill the Notes to proceed")
  }
  else{
    $('body').addClass('loading');  
    var type = $("#select_type3").val();
    var current_client_id = $("#note_current_client_id").val();
    

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_note'); ?>",
      type:"post",
      data:{type:type,current_client_id:current_client_id,reply_note:reply_note},
      dataType:"json",
      success: function(result)
      {
        $(".reply_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $("#client_source_td_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}


if($(e.target).hasClass('add_bank')){

  var current_client = $(e.target).attr('data-element');
  $("#bank_name").val('');
  $("#account_name").val('');
  $("#account_number").val('');
  
  $("#bank_current_client_id").val(current_client);  

}
// if($(e.target).hasClass('trade_details'))
// {
//   var current_client = $(e.target).attr('data-element');
//   var current_name = $(e.target).attr('data-client');
//   $("#products_services").val('');
//   $("#transaction_type").val('');
//   $("#risk_factors").val('');
//   $("#geo_area").val('');
//   $("#politically_exposed_no").prop("checked",true);
//   $("#high_risk_no").prop("checked",true);

//   // $(".client_name").html(current_name);
//   // $(".client_code").html(current_client);
//   $(e.target).parents("td:first").find("#trade_details_form_"+current_client).show();
//   $(e.target).parents("td:first").find(".trade_action_div").hide();
// }

// if($(e.target).hasClass('trade_details_edit'))
// {
//   var current_client = $(e.target).attr('data-element');
//   $.ajax({
//     url:"<?php echo URL::to('user/get_trade_details'); ?>",
//     type:"get",
//     data:{current_client:current_client},
//     dataType:"json",
//     success:function(result)
//     {
//       $(e.target).parents("td:first").find("#products_services"+current_client).val(result['products_services']);
//       $(e.target).parents("td:first").find("#transaction_type"+current_client).val(result['transaction_type']);
//       $(e.target).parents("td:first").find("#risk_factors"+current_client).val(result['risk_factors']);
//       $(e.target).parents("td:first").find("#geo_area"+current_client).val(result['geo_area']);

//       if(result['high_risk'] == 1){
//         $(e.target).parents("td:first").find("#high_risk_no"+current_client).prop("checked",false);
//         $(e.target).parents("td:first").find("#high_risk_yes"+current_client).prop("checked",true);
//       }
//       else{
//         $(e.target).parents("td:first").find("#high_risk_no"+current_client).prop("checked",true);
//         $(e.target).parents("td:first").find("#high_risk_yes"+current_client).prop("checked",false);
//       }

//       if(result['politically_exposed'] == 1){
//         $(e.target).parents("td:first").find("#politically_exposed_no"+current_client).prop("checked",false);
//         $(e.target).parents("td:first").find("#politically_exposed_yes"+current_client).prop("checked",true);
//       }
//       else{
//         $(e.target).parents("td:first").find("#politically_exposed_no"+current_client).prop("checked",true);
//         $(e.target).parents("td:first").find("#politically_exposed_yes"+current_client).prop("checked",false);
//       }

//       $(e.target).parents("td:first").find("#trade_details_form_"+current_client).show();
//       $(e.target).parents("td:first").find(".trade_action_div").hide();
//     }
//   });
// }

if($(e.target).hasClass('bank_submit')){
  if($("#bank_form").valid())
  {
    //$('body').addClass('loading');    
    var current_client_id = $("#bank_current_client_id").val();
    var bank_name = $("#bank_name").val();
    var account_name = $("#account_name").val();
    var account_number = $("#account_number").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_add_bank'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,bank_name:bank_name,account_name:account_name,account_number:account_number},
      dataType:"json",
      success: function(result)
      {
        $(".bank_modal").modal('hide');
        $("#client_bank_"+result['id']).html(result['output']);
        $("#client_bank_td_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}
// if($(e.target).hasClass('cancel_trade')){
//   var client_id = $(e.target).attr("data-element");

//   $("#trade_details_form_"+client_id).hide();
//   $(".trade_action_div"+client_id).show();
// }
// if($(e.target).hasClass('trade_submit')){
//   var client_id = $(e.target).attr("data-element");

//   if($("#trade_details_form_"+client_id).valid())
//   {
//     var products_services = $("#products_services"+client_id).val();
//     var transaction_type = $("#transaction_type"+client_id).val();
//     var risk_factors = $("#risk_factors"+client_id).val();
//     var geo_area = $("#geo_area"+client_id).val();

//     var politically_exposed = $(".politically_exposed"+client_id+":checked").val();
//     var high_risk = $(".high_risk"+client_id+":checked").val();

//     $.ajax({
//       url:"<?php echo URL::to('user/aml_system_add_trade'); ?>",
//       type:"post",
//       data:{current_client_id:client_id,products_services:products_services,transaction_type:transaction_type,risk_factors:risk_factors,geo_area:geo_area,politically_exposed:politically_exposed,high_risk:high_risk},
//       dataType:"json",
//       success: function(result)
//       {
//         //$(".trade_details_modal").modal('hide');
//         // $("#trade_details_form_"+client_id).hide();
//         // $(".trade_action_div"+client_id).show();
//         $(e.target).attr("disabled",true);
//         //$("#client_trade_"+result['id']).html(result['output']);
//         $('body').removeClass('loading');
//         $('[data-toggle="tooltip"]').tooltip();
        
//       }
//     });
//   }
// }

if($(e.target).hasClass('bank_detail_class')){
  
  $("#bank_expand").dataTable().fnDestroy();
  $('body').addClass('loading');  
   var client_id = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_bank_details'); ?>",
    type:"post",
    data:{client_id:client_id},
    dataType:"json",
    success: function(result)
    {
      $(".bank_detail_model").modal('show');
      $("#bank_detail_body").html(result['output']);
      $(".bank_company_name").html(result['company_name']);
      $('#bank_expand').DataTable({
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
      $('body').removeClass('loading'); 
    }
  });
}

if($(e.target).hasClass('review_by')){

  var current_client = $(e.target).attr('data-element');
  $("#review_current_client_id").val(current_client);
  $("#review_date").val('');
  $("#reivew_filed").val('');
  $(".review_model").modal('show');


}

if($(e.target).hasClass('review_submit')){
  if($("#review_form").valid())
  {
    $('body').addClass('loading');    
    var current_client_id = $("#review_current_client_id").val();
    var review_date = $("#review_date").val();
    var reivew_filed = $("#reivew_filed").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_review'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,review_date:review_date,reivew_filed:reivew_filed},
      dataType:"json",
      success: function(result)
      {
        $(".review_model").modal('hide');
        $("#review_"+result['id']).html(result['output']);
        $("#review_td_"+result['id']).html(result['output']);
        $('body').removeClass('loading'); 
        
      }
    });
  }
}
if($(e.target).hasClass('edit_review')){
  var current_client = $(e.target).attr('data-element');
  $("#review_current_client_id_edit").val(current_client);
  

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_edit'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {
      $(".edit_review_model").modal('show');
      $("#review_date_edit").val(result['date']);
      $("#reivew_filed_edit").val(result['output']);
      $(".review_date_edit").datetimepicker({   
         format: 'L',
         format: 'DD-MMM-YYYY'
      });
      
    }
  });
}
if($(e.target).hasClass('review_edit_submit')){
  $('body').addClass('loading');    
  var current_client_id = $("#review_current_client_id_edit").val();
  var review_date = $("#review_date_edit").val();
  var reivew_filed = $("#reivew_filed_edit").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_edit_update'); ?>",
    type:"post",
    data:{current_client_id:current_client_id,review_date:review_date,reivew_filed:reivew_filed},
    dataType:"json",
    success: function(result)
    {
      $(".edit_review_model").modal('hide');
      $("#review_"+result['id']).html(result['output']);
      $("#review_td_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}


if($(e.target).hasClass('delete_review')){
  $('body').addClass('loading');    
  var current_client = $(e.target).attr('data-element');;

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_delete'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {      
      $("#review_"+result['id']).html(result['output']);
      $("#review_td_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}




if($(e.target).hasClass('image_submit')){
  $('body').addClass('loading');    
  var current_client = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_image_upload'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {
      $(".img_div_add").hide();
      $("#client_identity_"+result['id']).html(result['output']);
      $("#client_identity_td_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}

if($(e.target).hasClass('delete_attached')){
  $('body').addClass('loading');    
  var id = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_delete_attached'); ?>",
    type:"post",
    data:{id:id},
    dataType:"json",
    success: function(result)
    {
      $(".img_div_add").hide();
      $("#client_identity_"+result['id']).html(result['output']);
      $("#client_identity_td_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      $(e.target).parents(".client_identity_tr").detach();
    }
  });
}

if($(e.target).hasClass('client_since_class')){  
  var current_client = $(e.target).attr('data-element');
  $("#date_since_current_client_id").val(current_client);  
  $(".date_model").modal('show');
  $(".client_date_since").val('');
}

if($(e.target).hasClass('date_submit')){
  var date = $(".client_date_since").val();
  if(date == "")
  {
    alert("Please fill the date.");
  }
  else{
    $('body').addClass('loading');    
    var current_client = $("#date_since_current_client_id").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_client_since'); ?>",
      type:"post",
      data:{date:date,current_client:current_client},
      dataType:"json",
      success: function(result)
      {      
        $("#client_since_"+result['id']).html(result['output']);
        $("#client_since_td_"+result['id']).html(result['output']);
        $(".date_model").modal('hide');
        $('body').removeClass('loading');
      }
    });
  }
}

if($(e.target).hasClass('download_aml_report_single'))
{
  var checkedvalue = $(e.target).attr("data-element");
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/aml_report_pdf_single'); ?>",
    type:"post",
    data:{value:checkedvalue},
    success: function(result)
    {
      //$("#apply_first").html(len.length);
      //countapply(0,len.length);
      $("body").removeClass("loading");
      SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      //window.location.reload();
    }
  });
}
if(e.target.id == "save_as_pdf")
{
  if($(".select_client:checked").length)
  {
    if($(".select_client:checked").length <= 20)
    {
      $("body").addClass("loading_apply");
      // $(".apply_p").show();
      // $(".apply_msg_p").hide();
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
      var len = checkedvalue.split(",");
      // $("#apply_first").html(1);
      // $("#apply_last").html(len.length);
      // setTimeout(function() {
      //   countapply(2,len.length);
      // },500);
      setTimeout(function() {

        $.ajax({
          url:"<?php echo URL::to('user/aml_report_pdf'); ?>",
          type:"post",
          data:{value:checkedvalue},
          success: function(result)
          {
            //$("#apply_first").html(len.length);
            //countapply(0,len.length);
            $("body").removeClass("loading_apply");
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            window.location.reload();
          }
        });
      },1000);   
    }
    else{
      $("body").removeClass("loading");
      alert("Maximum Clients selection is 20. You cant able to generate a PDF Report more than 20 clients.");
    }
  }
  else{
    $("body").removeClass("loading");
    alert("Please Choose atleast one invoice to continue.");
  }
}
if(e.target.id == "save_as_csv")
{
  if($(".select_client_csv:checked").length)
  {
      $("body").addClass("loading");
      var checkedvalue = '';
      $(".select_client_csv:checked").each(function() {
        var value = $(this).val();
        if(checkedvalue == "")
        {
          checkedvalue = value;
        }
        else{
          checkedvalue = checkedvalue+","+value;
        }
      });
      var len = checkedvalue.split(",");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/aml_report_csv'); ?>",
          type:"post",
          data:{value:checkedvalue},
          success: function(result)
          {
            $("body").removeClass("loading");
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            window.location.reload();
          }
        });
      },1000);
  }
  else{
    $("body").removeClass("loading");
    alert("Please Choose atleast one invoice to continue.");
  }
}
if(e.target.id == "select_all_class")
{
  if($(e.target).is(":checked"))
  {
    $(".select_client_csv:visible").each(function() {
      $(this).prop("checked",true);
    });
  }
  else{
    $(".select_client_csv:visible").each(function() {
      $(this).prop("checked",false);
    });
  }
}
if($(e.target).hasClass('add_client_identity_files'))
  {
    $(".img_div").hide();
    var current_client = $(e.target).attr('data-element');
    $("#client_identity_client_code").val(current_client);
    $(".client_identity_modal").modal("show");

    // var pos = $(e.target).position();
    // var leftposi = parseInt(pos.left);
    // $(e.target).parent().find('.img_div_add').toggle();

    if(Dropzone.instances.imageUpload1) {
      Dropzone.forElement("#imageUpload1").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE<br/>the files OR just drop<br/>files here to upload");
    }

    $.ajax({
      url:"<?php echo URL::to('user/get_aml_client_identity_files'); ?>",
      type:"post",
      data:{client_id:current_client},
      dataType:"json",
      success:function(result)
      {
        $("#client_identity_body").html(result['output']);
        $("#client_identity_client_code_title").html(result['client_code']);
        $("#client_identity_client_name_title").html(result['client_name']);

        setDatetimepickerfunction();
      }
    })

    
    //$("#img_div_"+current_client).css({"position":"absolute","top":toppos,"left":leftposi, "z-index":"9999"}).toggle();
  }
  else if($(e.target).hasClass('fa-plus'))
  {
    $(".img_div").hide();
    var current_client = $(e.target).attr('data-element');

    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    
    //$("#img_div_"+current_client).css({"position":"absolute","top":toppos,"left":leftposi, "z-index":"9999"}).toggle();
  }
  
  
  if($(e.target).hasClass('image_file_add'))
  {
    $(e.target).parents('.modal-body').find('.img_div').toggle();
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
$('#bank_form').validate({
    rules: {
        bank_name : {required: true,},
        account_name : {required: true,},
        account_number : {required: true,},
    },
    messages: {
        bank_name : "Bank Name is Required",
        account_name : "Account Name is Required",
        account_number : "Account Number is Required",
    },
});
$('#review_form').validate({
    rules: {
        review_date : {required: true,},
        reivew_filed : {required: true,},
    },
    messages: {
        review_date : "Review Date is Required",
        reivew_filed : "Reviewed By is Required",
    },
});


</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#aml_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
           { orderable: false, targets: [4,5,6,7,8,9,10,11,12,13,14,15,16,17] }
        ]
    });
});

$(".client_date_since").datetimepicker({
   //defaultDate: fullDate,       
   format: 'L',
   format: 'DD-MMM-YYYY',
   //maxDate: fullDate,
});
$(".review_date").datetimepicker({   
   format: 'L',
   format: 'DD-MMM-YYYY'
});
CKEDITOR.replace('editoramlsignature',
{
  height: '150px',
  enterMode: CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    autoParagraph: false,
    entities: false,
});
CKEDITOR.replace('editoramlbody',
{
  height: '150px',
  enterMode: CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    autoParagraph: false,
    entities: false,
});
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
              $(".aml_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".aml_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">AML Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".aml_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".aml_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".aml_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">AML Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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

<script>
$(document).ready(function() {    
  $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/aml_client_search'); ?>",
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
            url:"<?php echo URL::to('user/aml_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              
            }
          })
        }
  });
});
</script>

<?php } ?>



@stop