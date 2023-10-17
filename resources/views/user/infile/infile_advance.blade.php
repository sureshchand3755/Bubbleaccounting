@extends('userheader')
@section('content')
<style>
.table-fixed-header {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header thead tr th {
  background: white;
  position: sticky;
  top: 84; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}
.content_check{
  word-wrap:break-word; /*old browsers*/
  overflow-wrap:break-word;
}
.overflow-wrap-hack{
  max-width:1px;
}
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th { z-index:9; }
.modal_load_apply {
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
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}
.file_attachment_div{width:100%;}
.fa-sort{cursor:pointer;}
.user_td_class{
  word-wrap: break-word; white-space:normal; min-width:150px; max-width: 150px;
}
.datepicker-only-init table tr th{border-top: 0px !important;}
.datepicker-only-init table tr td{border-top: 0px !important;}
.auto_save_date table tr th{border-top: 0px !important;}
.auto_save_date table tr td{border-top: 0px !important;}
.form-control[disabled]{background-color: #ccc !important;}
.fa-plus, .fa-pencil-square{cursor: pointer;}
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
.img_div_add{
        border: 1px solid #000;
    width: 280px;
    position: inherit !important;
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
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.report_div{
    position: absolute;
    top: 91%; left: 25%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }
.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; line-height: 20px; font-weight: 500 }
.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.ok_button:focus{background: #000; text-decoration: none; color: #fff}
.report_csv, .report_pdf{opacity: 1 !important}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
    .disable_class{cursor :auto !important;pointer-events: none !important}
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
    .close_bpso{
      /* position: absolute;
      right: 15px; */
      font-size: 20px !important;
      color: #f00;
    }
    .img_div_bpso{
      width:100%;
      display:none;
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
.blinking{
    animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
    0%{     background: #f00;    }
    49%{    background: #000; }
    50%{    background: #f00; }
    99%{    background:#000;  }
    100%{   background: #f00;    }
}
</style>
<?php 
if(Session::has('countupdated')) 
{
  $countupdated = Session::get('countupdated');
  $total_count = Session::get('total_count');
  $message = Session::get('message');
  $client_session_id = Session::get('client_session_id');
  $file_id = Session::get('file_id');
  $search_type = Session::get('search_type');
  if($total_count != "0") {
  ?>
  <script>
    // $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?> Files Uploaded <?php echo $countupdated; ?> of <?php echo $total_count; ?> Files successfully</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Do you want to view this infiles item now?</p><p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-element="<?php echo URL::to('user/infile_search?client_id='.$client_session_id.''); ?>" class="common_black_button view_infiles_item">Yes</a><a href="javascript:" class="common_black_button close_colorbox">No</a></p><p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:"  class="common_black_button create_task_manager" data-element="<?php echo $file_id; ?>">Create Task</a></p>'});
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?> Files Uploaded <?php echo $countupdated; ?> of <?php echo $total_count; ?> Files successfully</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Do you want to view this infiles item now?</p><p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-element="<?php echo URL::to('user/infile_search?client_id='.$client_session_id.''); ?>" class="common_black_button view_infiles_item">Yes</a><a href="javascript:" class="common_black_button close_colorbox">No</a></p>'});
  </script>
  <?php
  }
}
elseif(Session::has('message'))
{
  $message = Session::get('message');
  $client_session_id = Session::get('client_session_id');
  $search_type = Session::get('search_type');
  ?>
  <script>
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?></p>'});
  </script>
  <?php
}
else{
  $client_session_id = '';
  $search_type = '';
}
if($search_type == "")
{
  $company_client = '';
  $client_search_val = '';
  $output = '';
}
elseif($search_type == "all")
{
  $company_client = '';
  $client_search_val = '';
  $output='';
  $i=1;
  $clientslist = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->get();
  if(($clientslist)){
  foreach ($clientslist as $client) {
    $clientid = $client->client_id;
    $firstname = $client->firstname;
    $lastname = $client->surname;
    $company = $client->company;
    if($i < 10)
    {
      $i = '0'.$i;
    }
    
    $received = DB::table('in_file')->where('client_id',$client->client_id)->count();
    $complete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',1)->count();
    $incomplete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',0)->count();
    if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
    if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
    if($client->active == 2) { $color = 'color:#F00 !important'; }
    else { $color = 'color:#000 !important'; }
    $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
    $output.='
    <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
      <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
      <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
      <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
      <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
      <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
      <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
      <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
      <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
      <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
        <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
        <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
        $infiles = DB::table('in_file')->where('client_id', $client->client_id)->get();
        if(($infiles))
        {
          foreach($infiles as $infile)
          {
            $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
          }
        }
      $output.='</td>
    </tr>';
    $i++;
  }
  }
  else{
  $output.='
    <tr>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">Empty</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
  ';
  }
}
else{
  $client_id = $search_type;
  $details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
  if($details->company != "")
  {
    $company = $details->company;
  }
  else{
    $company = $details->firstname.' '.$details->surname;
  }
  $company_client = $company.'-'.$client_id;
  $client_search_val = $client_id;
  $output='';
  $i=1;
  $client = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
  if(($client)){
    $clientid = $client->client_id;
    $firstname = $client->firstname;
    $lastname = $client->surname;
    $company = $client->company;
    if($i < 10)
    {
      $i = '0'.$i;
    }
    
    $received = DB::table('in_file')->where('client_id',$client->client_id)->count();
    $complete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',1)->count();
    $incomplete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',0)->count();
    if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
    if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
    if($client->active == 2) { $color = 'color:#F00 !important'; }
    else { $color = 'color:#000 !important'; }
    $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
    $output.='
    <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
      <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
      <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
      <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
      <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
      <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
      <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
      <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
      <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
      <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
        <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
        <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
        $infiles = DB::table('in_file')->where('client_id', $client->client_id)->get();
        if(($infiles))
        {
          foreach($infiles as $infile)
          {
            $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
          }
        }
      $output.='</td>
    </tr>';
    $i++;
  }
  else{
    $output.='
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center">Empty</td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
      </tr>
    ';
  }
}
?>
<div class="modal fade integrity_check_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:70%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Integrity Check</h4>
            <a href="javascript:" class="export_integrity_filename_a common_black_button" download style="margin-top:-47px;margin-right:57px;float:right">Export </a>
          </div>
          <div class="modal-body">  
              <table class="table">
                <thead>
                    <th style="text-align:left;width:20%">Client</th>
                    <th style="text-align:left;width:10%">Client ID</th>
                    <th style="text-align:left;width:30%">Filename</th>
                    <th style="text-align:left;width:5%">Status</th>
                    <th style="text-align:left;width:5%">Action</th>
                    <th style="text-align:left;width:20%">Description</th>
                    <th style="text-align:left;width:10%">Date Added</th>
                </thead>
                <tbody id="integrity_check_body">
                </tbody>
              </table>
          </div>
          <div class="modal-footer" style="text-align:left">
             <div class="export_integrity_filename" id="export_integrity_filename">
                <h5>Download</h5>
                <a href="<?php echo URL::to('public/papers/IntegrityCheckReport.csv'); ?>" download>IntegrityCheckReport.csv</a>
             </div>
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                InFiles: Client Supplied Soft File Management System         
            </h4>
    </div>
  
  <div class="row" style="margin-bottom: 10px;margin-top: 20px;">
      <div class="col-lg-12">
      </div>
      <div class="col-lg-12">
        <div style="float: left; margin-right: 10px">
          <label style="float: left; padding-top: 10px;">Search Client ID: </label>
        </div>
        <div style="float: left; margin-right: 6px;">
          
          <input type="text" class="form-control client_common_search ui-autocomplete-input" placeholder="Enter Client Name" style="font-weight: 500;margin-bottom: 13px;margin-top:2px;width:350px;float:left" value="<?php echo $company_client; ?>" autocomplete="off">
          <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
          <input type="button" class="common_black_button load_single_client" value="Load" style="width: 100px;margin-top: 3px;">
          <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $client_search_val; ?>" name="client_id">
        </div>
        <div style="float: left;">
          <input type="button" class="common_black_button load_all_clients" value="Load All Clients" style="float: left;width:100%;margin-top: 3px;">
        </div>
        <?php 
            $user = DB::table('user_login')->where('id',1)->first();
          ?>
          <div style="float: left; margin-right: 20px;margin-left:20px;margin-top:10px">
            <input type="checkbox" name="hide_inactive" class="hide_inactive" id="hide_inactive" value="1"><label for="hide_inactive" title="Hides the Inactive Clients">Hide Inactive</label>
          </div>
          <div style="float: left;;margin-top:10px"><input type="checkbox" name="show_incomplete" class="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete" title="Show client who have an Incomplete Batches">Show Incomplete Only</label></div>
          <!-- <?php
          if(Session::has('message')) { ?>
              <spam class="alert alert-info" style="float: left;margin-top: -14px;margin-left:10px"><?php echo Session::get('message'); ?> &nbsp; &nbsp;
                <?php echo Session::get('view_button'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close" style="float:none">×</a>
              </spam>
            
          <?php } ?> -->
           
           <a href="javascript:" class="fa fa-cogs common_black_button infile_settings_btn" style="margin-top:-1px; float: right;"></a>
           <a href="javascript:" class="integrity_check_for_all common_black_button" style="margin-top:-1px; float: right;">Integrity Check For All Clients</a>
      </div>
      
  <div style="clear: both;">
   
  </div>
</div>
<?php
$infile_settings = DB::table('infile_settings')->where('practice_code', Session::get('user_practice_code'))->first();
$cc_email = '';
if($infile_settings){
  $cc_email = $infile_settings->cc_email;
}
?>
<div class="modal fade infile_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <form action="<?php echo URL::to('user/update_infile_settings'); ?>" method="post" id="update_infile_settings_form">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title">Infile Settings</h4>
            </div>
            <div class="modal-body" id="infiles_body">  
                <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                <?php
                if($infile_settings->email_header_url == '') {
                  $default_image = DB::table("email_header_images")->first();
                  if($default_image->url == "") {
                    $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                  }
                  else{
                    $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                  }
                }
                else {
                  $image_url = URL::to($infile_settings->email_header_url.'/'.$infile_settings->email_header_filename);
                }
                ?>
                <img src="<?php echo $image_url; ?>" class="email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                <input type="button" name="email_header_img_btn" class="common_black_button email_header_img_btn" value="Browse"> 
                <h4>Infile CC Email:</h4>
                <input type="email" name="infile_cc_email" class="form-control infile_cc_email" id="infile_cc_email" value="<?php echo $cc_email; ?>" placeholder="Enter Infile CC Email Address" required>

                <h4>Infile Items Initial Load Count:</h4>
                <input type="number" name="loadcount" class="form-control loadcount" id="loadcount" value="<?php echo $infile_settings->loadcount; ?>" placeholder="Enter Load Count Values" required>
            </div>
            <div class="modal-footer">  
              <input type="submit" class="common_black_button" id="infile_settings_submit" value="Submit">
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
          <form action="<?php echo URL::to('user/edit_infile_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  
          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade model_notify" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:25%">
    <form action="" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Notify</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
            </div>
              <input type="checkbox" name="" id="notity_selectall"><label for="notity_selectall">Select All</label>
              <table id="dtBasicExample" class="table">
                <thead>
                  <tr>
                    <th scope="col" style="text-align: left">S.No</th>
                    <th scope="col" style="text-align: left">Name</th>
                  </tr>
                </thead>
                <tbody>
              <?php
              $userlist_notify = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
              $i = 1;
              if(($userlist_notify)){
                foreach ($userlist_notify as $user) {
                ?>
                  <tr>
                    <td scope="row"><?php echo $i; ?> <input type="checkbox" class="notify_id_class" name="username" id="user_<?php echo $user->user_id?>" data-element="<?php echo $user->email; ?>" data-value="<?php echo $user->user_id; ?>"><label>&nbsp;</label></td>
                    <td><label for="user_<?php echo $user->user_id?>"><?php echo $user->lastname.' '.$user->firstname; ?></label></td>
                  </tr>
                <?php
                $i++;
                }
              }
              ?>
              </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="notify_file_id" value="" name="">
          <input type="button" class="btn btn-primary common_black_button notify_all_clients_tasks" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
<!-- <div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row"> 
                <div class="col-md-3">
                  <label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-3">
                  <select name="select_user" class="form-control select_user_author" required>
                    <option value="">Select User</option>        
                      <?php
                      $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                      $selected = '';
                      if(($userlist)){
                        foreach ($userlist as $user) {
                      ?>
                        <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label style="margin-top:5px">Author Email:</label>
                </div>
                <div class="col-md-4">
                  <input  type="email" class="form-control author_email" name="author_email" placeholder="Enter Author's Email" required>
                </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                  <label style="margin-top:5px">Creation Date:</label>
                </div>
                <div class="col-md-9">
                  <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control created_date" placeholder="Select Creation Date" name="created_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
                </div>
            </div>
            <div class="row" style="margin-top:7px">
              <div class="col-md-3">
                  <label style="margin-top:5px">Allocate To:</label>
                </div>
                <div class="col-md-7">
                  <select name="allocate_user" class="form-control allocate_user_add">
                    <option value="">Select User</option>        
                      <?php
                      $selected = '';
                      if(($userlist)){
                        foreach ($userlist as $user) {
                      ?>
                        <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                    <input type='checkbox' name="open_task" id="open_task" value="1"/>
                    <label for="open_task">OpenTask</label>
                  </div>
                </div>
            </div>
            <div class="row" style="margin-top:14px">
              <div class="col-md-3 client_group">
                  <label style="margin-top:5px">Client:</label>
                </div>
                <?php
                if($client_session_id != "")
                {
                  $client_id = $client_session_id;
                  $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
                  $company = $client_details->company.'-'.$client_id;
                }
                else{
                  $client_id = '';
                  $company = '';
                }
                ?>
                <div class="col-md-7 client_group">
                  <input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="<?php echo $company; ?>" required disabled>
                  <input type="hidden" id="client_search_task" name="clientid" value="<?php echo $client_id; ?>"/>
                </div>
                <div class="col-md-3 internal_tasks_group" style="display: none;">
                  <label style="margin-top:5px">Select Task:</label>
                </div>
                <div class="col-md-7 internal_tasks_group" style="display: none;">
                  <div class="dropdown" style="width: 100%">
                    <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                      <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
                    </a>
                    <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal">Select Task</a></li>
                        <?php
                        $taskslist = DB::table('time_task')->where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
                        if(($taskslist)){
                          foreach ($taskslist as $single_task) {
                            if($single_task->task_type == 0){
                              $icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
                            }
                            else if($single_task->task_type == 1){
                              $icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
                            }
                            else{
                              $icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
                            }
                        ?>
                          <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                        <?php
                          }
                        }
                        ?>
                    </ul>
                    <input type="hidden" name="idtask" id="idtask" value="">
                  </div>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                  </div>
                </div>
            </div>
            <div class="form-group start_group" style="margin-top:10px">
                <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
            </div>
            <div class="form-group start_group task_specifics_add">
                <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
            </div>
            <div class="form-group date_group">
                <div class="col-md-2" style="padding:0px">
                  <label style="margin-top:5px">DueDate:</label>
                </div>
                <div class="col-md-10">
                  <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
                </div>
            </div>
            <div class="form-group start_group retreived_files_div">
            </div>
            <div class="form-group start_group">
              <label>Task Files: </label>
              <a href="javascript:" class="fa fa-plus fa-plus-task" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
              <a href="javascript:" class="fa fa-pencil-square fanotepadtask" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
              <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
              <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
              <div class="img_div img_div_task" style="z-index:9999999; min-height: 275px">
                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                @csrf
</form>
                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload5" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input name="_token" type="hidden" value="">
                  @csrf
</form>              
                </div>
               </div>
               <div class="notepad_div_notes_task" style="z-index:9999; position:absolute;display:none">
                  <textarea name="notepad_contents_task" class="form-control notepad_contents_task" placeholder="Enter Contents"></textarea>
                  <input type="button" name="notepad_submit_task" class="btn btn-sm btn-primary notepad_submit_task" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                  <spam class="error_files_notepad_add"></spam>
              </div>
            </div>
            
            <p id="attachments_text_task" style="display:none; font-weight: bold;">Files Attached:</p>
            <div id="add_attachments_div_task">
            </div>
            <div id="add_notepad_attachments_div_task">
            </div>
            <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
            <div id="add_infiles_attachments_div">
            </div>
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px;float:none"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                <div class="accept_recurring_div">
                  <p>This Task is repeated:</p>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                    <label for="recurring_checkbox1">Monthly</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                    <label for="recurring_checkbox2">Weekly</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                    <label for="recurring_checkbox3">Daily</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                    <label for="recurring_checkbox4">Specific Number of Days</label>
                    <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                  </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">     
            <input type="hidden" name="action_type" id="action_type" value="">
            <input type="hidden" name="hidden_specific_type" id="hidden_specific_type" value="">
            <input type="hidden" name="hidden_attachment_type" id="hidden_attachment_type" value="">
            <input type="hidden" name="hidden_task_id_copy_task" class="hidden_task_id_copy_task" value="">
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    @csrf
</form>
  </div>
</div> -->
<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?php echo URL::to('user/create_new_file')?>" method="post" class="add_new_form" id="create_job_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">File Received Manager</h4>
          </div>
          <div class="modal-body">  
            <div class="row">
              <div class="col-md-4">
                <div class="form-group client_group"> 
                  <div class="form-title">Select a Client <span><i class="fa fa-info-circle" style="font-size: 13px; cursor: pointer;" data-toggle="tooltip" title="Please make sure that you select a client from the auto-complete result shown below as you type, only then Create New button will be enabled."></i></span>
                  
                  </div>
                  <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required style="width:90%; display:inline;">
                  <img class="active_client_list_pms1" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:20px; cursor:pointer;" title="Add to active client list" /><br/><br/>
                  <input type="hidden" id="client_search" name="clientid" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group date_group">
                  <div class="form-title">Recevied Date</div>
                  <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control date_received" placeholder="Select Received Date" name="received_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group start_group">
                  <div class="form-title">Added Date</div>
                  <div class='input-group datepicker-only-init'>
                      <input type='text' class="form-control date_added" placeholder="Select Added Date" name="added_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </div>
                </div>
              </div>
            </div>       
            
            <div class="form-group start_group">
                <div class="form-title">Description</div>
                <textarea type='text' class="form-control" placeholder="Enter Description" name="description" style="font-weight: 500; height: 100px" required /></textarea>
            </div>
            <div class="form-group start_group">
                <div class='input-group'>
                    <input type='checkbox' name="hard_files_checkbox" id="hard_files_checkbox" value="1"/>
                    <label for="hard_files_checkbox">Hard Files</label>
                </div>
            </div>
            <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>
            <div id="add_attachments_div">
              
            </div>
            <div id="add_notepad_attachments_div">
            </div>
            
            <div class="form-group start_group">
              <!-- <i class="fa fa-plus fa-plus-add" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i>  -->
              <i class="fa fa-plus fa-plus-add-new" style="margin-top:10px;" aria-hidden="true" title="Add Attachment new"></i> 
              <i class="fa fa-pencil-square fanotepadadd" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></i>
              <i class="fa fa-trash trash_imageadd" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
              <div class="img_div img_div_add" style="z-index:9999999; min-height: 1px">
                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                  
                @csrf
</form>
                
                <!-- <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input name="_token" type="hidden" value="'.$file->id.'">
                   
                  @csrf
</form>                
                </div>                -->
               </div>
               <div class="img_div_bpso" style="z-index:9999999;">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td colspan="4" style="background:#ff0;font-weight:600;text-align:right;font-size:20px"><a href="javascript:" class="close_bpso">X</a></td>
                      </tr>
                      <tr>
                        <td style="background:#ff0;font-weight:600;text-align:center;font-size:20px">Upload Bank Documents</td>
                        <td style="background:#ff0;font-weight:600;text-align:center;font-size:20px">Upload Purchase Documents</td>
                        <td style="background:#ff0;font-weight:600;text-align:center;font-size:20px">Upload Sales Documents</td>
                        <td style="background:#ff0;font-weight:600;text-align:center;font-size:20px">Upload Other Documents
                          
                        </td>
                      </tr>
                      <tr>
                        <td style="background:#ff0">
                          <div class="image_div_attachments">
                            <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadb" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                              <input name="_token" type="hidden" value="'.$file->id.'">
                              <input type="hidden" name="hidden_infile_type" id="hidden_infile_type" value="1">
                            @csrf
</form>
                          </div>
                        </td>
                        <td style="background:#ff0">
                          <div class="image_div_attachments">
                            <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadp" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                              <input name="_token" type="hidden" value="'.$file->id.'">
                              <input type="hidden" name="hidden_infile_type" id="hidden_infile_type" value="2">
                            @csrf
</form>
                          </div>
                        </td>
                        <td style="background:#ff0">
                          <div class="image_div_attachments">
                            <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploads" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                              <input name="_token" type="hidden" value="'.$file->id.'">
                              <input type="hidden" name="hidden_infile_type" id="hidden_infile_type" value="3">
                            @csrf
</form>
                          </div>
                        </td>
                        <td style="background:#ff0">
                          <div class="image_div_attachments">
                            <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploado" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                              <input name="_token" type="hidden" value="'.$file->id.'">
                              <input type="hidden" name="hidden_infile_type" id="hidden_infile_type" value="4">
                            @csrf
</form>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td id="file_attachments_b" class="file_attachments_b" style="background:#ff0"></td>
                        <td id="file_attachments_p" class="file_attachments_p" style="background:#ff0"></td>
                        <td id="file_attachments_s" class="file_attachments_s" style="background:#ff0"></td>
                        <td id="file_attachments_o" class="file_attachments_o" style="background:#ff0"></td>
                      </tr>
                      <tr>
                        <!-- <td colspan="4" style="background:#ff0;text-align:center;padding:20px"><a href="javascript:" class="common_black_button submit_bpso" data-element="'.$file->id.'">Submit</a></td> -->
                      </tr>
                    </tbody>
                  </table>
                </div>
               <div class="notepad_div_notes_add" style="z-index:9999; position:absolute">
                  <textarea name="notepad_contents_add" class="form-control notepad_contents_add" placeholder="Enter Contents"></textarea>
                  <input type="button" name="notepad_submit_add" class="btn btn-sm btn-primary notepad_submit_add" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                  <spam class="error_files_notepad_add"></spam>
              </div>
            </div>
          </div>
          <div class="modal-footer">       
            <p class="accepted_files_main" style="display:none"><strong> </strong><span class="accepted_files">0</span> Files are Ready to Upload.</p>      
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="<?php echo $search_type; ?>">
            <input type="submit" class="common_black_button job_button_name create_new_class" value="Create New Infile">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<!--*************************************************************************-->
<div class="row">
<div class="col-lg-12">
  <table class="table table-fixed-header own_table_white" id="infile_expand">
    <thead>
      <tr>
        <th width="100px" style="text-align:left"> S.No <i class="fa fa-sort sort_sno"></i></th>
        <th width="150px" style="text-align:left">Client ID <i class="fa fa-sort sort_clientid"></i></th>
        <th style="text-align:left"> Company <i class="fa fa-sort sort_company"></i></th>
        <th style="text-align:left"> First Name <i class="fa fa-sort sort_firstname"></i></th>
        <th style="text-align:left"> Surname <i class="fa fa-sort sort_lastname"></i></th>
        <th style="text-align:left;width:11%"> Batches Received <i class="fa fa-sort sort_received"></i></th>
        <th style="text-align:left;width:11%"> Batches Complete <i class="fa fa-sort sort_complete"></i></th>
        <th style="text-align:left;width:11%"> Batches Incomplete <i class="fa fa-sort sort_incomplete"></i></th>
        <th style="text-align:left;width:5%"> Action </th>
      </tr>   
    </thead>
    <tbody id="task_body"> 
      <?php 
      if($output == "") { ?>
        <tr>
          <td colspan="9" style="text-align:center">No Data found</td>
        </tr>
      <?php } else { 
        echo $output;
      } ?>
    </tbody>
  </table>
</div>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Infile Items are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
<input type="hidden" name="lastname_sortoptions" id="lastname_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="received_sortoptions" id="received_sortoptions" value="asc">
<input type="hidden" name="complete_sortoptions" id="complete_sortoptions" value="asc">
<input type="hidden" name="incomplete_sortoptions" id="incomplete_sortoptions" value="asc">
<script>
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
  $("#attachments_text").show();
  $(".img_div").hide();
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumber_int = function(value){
       return parseInt(value);
}
function next_integrity_check(count)
{
  var fileid = $(".integrity_attachment:eq("+count+")").attr("data-element");
  var keyval = $(".integrity_attachment:eq("+count+")").attr("data-key");
  $.ajax({
    url:"<?php echo URL::to('user/check_files_in_files'); ?>",
    type:"post",
    dataType:"json",
    data:{fileid:fileid,filepath:"",filename:"",type:"1"},
    success:function(result)
    {
      $(".integrity_status_"+fileid).html(result['status']);
      var countval = count + 1;
      if($(".integrity_attachment:eq("+countval+")").length > 0)
      {
        next_integrity_check(countval);
      }
      else{
        $("body").removeClass("loading_apply");
        $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
        $(".export_integrity_filename_a").attr("href",result['url']);
        $("#export_integrity_filename").show();
      }
    }
  });
}
function next_integrity_check_div(count,filepath,filename)
{
    var client_id = $(".clientid_sort_val:eq("+count+")").html();
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_files_client_id'); ?>",
      type:"post",
      dataType:"json",
      data:{client_id:client_id,filepath:filepath,filename:filename,type:"1",},
      success:function(result)
      {
        setTimeout(function() {
          $("#integrity_check_body").append(result['output']);
          var countval = count + 1;
          if($(".clientid_sort_val:eq("+countval+")").length > 0)
          {
            next_integrity_check_div(countval,filepath,filename);
            $("#apply_first").html(countval);
          }
          else{
            $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
            $(".export_integrity_filename_a").attr("href",result['url']);
            $("#export_integrity_filename").show();
            $("body").removeClass("loading_apply");
          }
        },200);
      }
    });
}
$(window).change(function(e) {  
  if($(e.target).hasClass('select_user_author'))
  {
    var value = $(e.target).val();
    if(value == "")
    {
      $(".author_email").val("");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
        type:"post",
        data:{value:value},
        success:function(result)
        {
          $(".author_email").val(result);
        }
      })
    }
  }
});
$(window).click(function(e) {
var ascending = false;
  if($(e.target).hasClass('close_bpso')){    
    $(".img_div_bpso").hide();
  }
  if($(e.target).hasClass('infile_settings_btn')) {
    $(".infile_settings_modal").modal("show");
  }
  if($(e.target).hasClass('email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Infile Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
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
  if($(e.target).hasClass('active_client_list_pms1'))
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
if($(e.target).hasClass('sort_sno'))
{
  var sort = $("#sno_sortoptions").val();
  if(sort == 'asc')
  {
    $("#sno_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#sno_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_clientid'))
{
  var sort = $("#clientid_sortoptions").val();
  if(sort == 'asc')
  {
    $("#clientid_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').html()) <
      convertToNumber($(b).find('.clientid_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#clientid_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').html()) <
      convertToNumber($(b).find('.clientid_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_firstname'))
{
  var sort = $("#firstname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#firstname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').html()) <
      convertToNumber($(b).find('.firstname_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#firstname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').html()) <
      convertToNumber($(b).find('.firstname_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_lastname'))
{
  var sort = $("#lastname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#lastname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').html()) <
      convertToNumber($(b).find('.lastname_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#lastname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').html()) <
      convertToNumber($(b).find('.lastname_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_company'))
{
  var sort = $("#company_sortoptions").val();
  if(sort == 'asc')
  {
    $("#company_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').html()) <
      convertToNumber($(b).find('.company_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#company_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').html()) <
      convertToNumber($(b).find('.company_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_received'))
{
  var sort = $("#received_sortoptions").val();
  if(sort == 'asc')
  {
    $("#received_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.received_sort_val').html()) <
      convertToNumber_int($(b).find('.received_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#received_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.received_sort_val').html()) <
      convertToNumber_int($(b).find('.received_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_complete'))
{
  var sort = $("#complete_sortoptions").val();
  if(sort == 'asc')
  {
    $("#complete_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.complete_sort_val').html()) <
      convertToNumber_int($(b).find('.complete_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#complete_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.complete_sort_val').html()) <
      convertToNumber_int($(b).find('.complete_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_incomplete'))
{
  var sort = $("#incomplete_sortoptions").val();
  if(sort == 'asc')
  {
    $("#incomplete_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.incomplete_sort_val').html()) <
      convertToNumber_int($(b).find('.incomplete_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#incomplete_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.incomplete_sort_val').html()) <
      convertToNumber_int($(b).find('.incomplete_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_status'))
{
  var sort = $("#status_sortoptions").val();
  if(sort == 'asc')
  {
    $("#status_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#status_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('close_colorbox'))
{
  $.colorbox.close();
}
if($(e.target).hasClass('export_now'))
{
  
}
if($(e.target).hasClass('load_all_clients'))
{
  $("body").addClass("loading");
  setTimeout(function() {
      $.ajax({
        url:"<?php echo URL::to('user/load_all_clients_infile_advanced'); ?>",
        type:"post",
        success:function(result)
        {
          $("#hidden_client_id").val("all");
          $("#task_body").html(result);
          $("#client_search_hidden_infile").val("");
          $(".client_common_search").val("");
          $("body").removeClass("loading");
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
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_single_client_infile_advanced'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $("#hidden_client_id").val(client_id);
        $("#task_body").html(result);
        $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('integrity_check_for_all'))
{
  var tot_clients = $(".clientid_sort_val").length;
  if(tot_clients > 0)
  {
    $("body").addClass("loading_apply");
    $("#integrity_check_body").html();
    var countintegrity = $(".clientid_sort_val").length;
      $("#apply_last").html(countintegrity);
    var client_id = $(".clientid_sort_val:eq(0)").html();
    $(".integrity_check_modal").modal("show");
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_files_client_id'); ?>",
      type:"post",
      dataType:"json",
      data:{client_id:client_id,filepath:"",filename:"",type:"0"},
      success:function(result)
      {
        setTimeout(function() {
          $("#integrity_check_body").append(result['output']);
          if($(".clientid_sort_val:eq(1)").length > 0)
          {
            next_integrity_check_div(1,result['filepath'],result['filename']);
            $("#apply_first").html(1);
          }
          else{
            $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
            $(".export_integrity_filename_a").attr("href",result['url']);
            $("#export_integrity_filename").show();
            $("body").removeClass("loading_apply");
          }
        },200);
      }
    });
  }
  else{
    alert("Please Load the clients and then proceed with 'Integrity Check For All Clients' Button");
  }
}
if($(e.target).hasClass('view_infiles_item'))
{
  var hrefval = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/infile_incomplete_status'); ?>",
    type:"post",
    data:{status:1},
    success:function(result)
    {
      window.location.replace(hrefval);
    }
  })
}
if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('notepad_submit_task'))
  {
    var contents = $(".notepad_contents_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div_task").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_task' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_task").hide();
      }
    });
  }
if($(e.target).hasClass('notepad_submit_task'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_task').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_notes_task').toggle();
    }
  }
  else{
    $(".notepad_div_notes_task").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('infiles_link'))
  {
    var client_id = $("#client_search_task").val();
    var ids = $("#hidden_infiles_id").val();
    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $(".infiles_modal").modal("show");
          $("#infiles_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('make_task_live'))
  {
    e.preventDefault();
    if($("#internal_checkbox").is(":checked"))
    {
        var taskvalue = $("#idtask").val();
        if(taskvalue == "")
        {
          alert("Please select the Task Name and then make the task as live");
          return false;
        }
    }
    else{
      var clientid = $("#client_search_task").val();
      if(clientid == "")
      {
        alert("Please select the Client and then make the task as live");
        return false;
      }
    }
    if (CKEDITOR.instances.editor_2)
    {
      var comments = CKEDITOR.instances['editor_2'].getData();
      if(comments == "")
      {
        alert("Please Enter Task Specifics and then make the task as Live.");
        return false;
      }
      else{
        $( "#create_task_form" ).valid();
        $("#create_task_form").submit();
      }
    }
    else{
      $("#create_task_form").valid();
      $("#create_task_form").submit();
    }
  }
  if($(e.target).hasClass('accept_recurring'))
  {
    if($(e.target).is(":checked"))
    {
      $(".accept_recurring_div").show();
      $("#recurring_checkbox1").prop("checked",true);
    }
    else{
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }
  }
  if($(e.target).hasClass('remove_infile_link_add'))
  {
    var file_id = $(e.target).attr("data-element");
    var ids = $("#hidden_infiles_id").val();
    var idval = ids.split(",");
    var nextids = '';
    $.each(idval, function( index, value ) {
      if(value != file_id)
      {
        if(nextids == "")
        {
          nextids = value;
        }
        else{
          nextids = nextids+','+value;
        }
      }
    });
    $("#hidden_infiles_id").val(nextids);
    $(e.target).parents("tr").detach();
  }
  if(e.target.id == "link_infile_button")
  {
    var checkcount = $(".infile_check:checked").length;
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });
      $("#hidden_infiles_id").val(ids);
      $(".infiles_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_infiles'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("#attachments_infiles").show();
          $("#add_infiles_attachments_div").show();
          $("#add_infiles_attachments_div").html(result);
        }
      })
    }
  }
if($(e.target).hasClass("create_task_manager"))
{
  $.colorbox.close();
  var fileid = $(e.target).attr("data-element");
  $("#hidden_infiles_id").val(fileid);
  
  $(".create_new_task_model").find(".job_title").html("New Task Creator");
  var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  var user_id = $(".select_user_home").val();
  $(".select_user_author").val("<?php echo Session::get('taskmanager_user'); ?>");
  $(".create_new_task_model").modal("show");
  if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
  $(".created_date").datetimepicker({
     defaultDate: fullDate,       
     format: 'L',
     format: 'DD-MMM-YYYY',
     maxDate: fullDate,
  });
  $(".due_date").datetimepicker({
     defaultDate: fullDate,
     format: 'L',
     format: 'DD-MMM-YYYY',
     minDate: fullDate,
  });
  CKEDITOR.replace('editor_2',
  {
    height: '150px',
    enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
 });
  $("#action_type").val("1");
  $(".allocate_user_add").val("");
  $(".task-choose_internal").html("Select Task");
  $(".subject_class").val("");
  $(".task_specifics_add").show();
  CKEDITOR.instances['editor_2'].setData("");
  
  $(".retreived_files_div").hide();
  $(".retreived_files_div").html("");
  $(".recurring_checkbox").prop("checked", false);
  $(".specific_recurring").val("");
  $(".task_specifics_copy_val").html("");
  $("#hidden_task_specifics").val("");
  $("#hidden_specific_type").val("");
  $("#hidden_attachment_type").val("");
  $(".created_date").prop("readonly", true);
  $(".client_group").show();
  $(".client_search_class").prop("required",true);
  $(".internal_tasks_group").hide();
  $("#internal_checkbox").prop("checked",false);
  $(".infiles_link").show();
  $("#attachments_text").hide();
  
  $("#attachments_infiles").hide();
  $("#idtask").val("");
  $("#hidden_copied_files").val("");
  $("#hidden_copied_notes").val("");
  $("#hidden_copied_infiles").val("");
  $(".accept_recurring").prop("checked",false);
  $(".accept_recurring_div").hide();
  $("#recurring_checkbox1").prop("checked",false);
  $("#open_task").prop("checked",false);
  $(".allocate_user_add").removeClass("disable_user");
  $.ajax({
    url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
    type:"post",
    data:{fileid:fileid},
    success: function(result)
    {
      $("#add_notepad_attachments_div").html('');
      $("#add_attachments_div").html('');
      $("body").removeClass("loading");
      $("#attachments_infiles").show();
      $("#add_infiles_attachments_div").html(result);
    }
  })
}
if($(e.target).hasClass('remove_notepad_attach_task'))
{
  var file_id = $(e.target).attr("data-task");
  $.ajax({
    url:"<?php echo URL::to('user/tasks_remove_notepad_attachment'); ?>",
    type:"post",
    data:{file_id:file_id},
    success: function(result)
    {
      $(e.target).parents("p").detach();
    }
  })
}
if($(e.target).hasClass('remove_dropzone_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('fanotepadtask')){
    var clientid = $("#client_search_task").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_task').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
if($(e.target).hasClass('create_new')) {
    var clientid = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('user/infile_task_client_search'); ?>",
        dataType: "json",
        data: {
            client_id : clientid,
        },
        success: function(data) {
            console.log(data['value']);
            $("#client_search").val(clientid);
            $(".client_search_class").val(data['value']);
            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
            $(".create_new_model").modal("show");
            $(".date_received").datetimepicker({
               defaultDate: fullDate,       
               format: 'L',
               format: 'DD-MMM-YYYY',
               maxDate: fullDate,
            });
            $(".date_added").datetimepicker({
               defaultDate: fullDate,
               format: 'L',
               format: 'DD-MMM-YYYY',
            });
            $(".date_added").prop("readonly", true);
            $("#attachments_text").hide();
            
        }
    });
  }
  if(e.target.id == "notity_selectall"){
    if($(e.target).is(":checked"))
    {
      $(".notify_id_class").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".notify_id_class").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  
  if(e.target.id == "client_search_infile")
  {
    var clientid = $("#client_search_hidden_infile").val();
    if(clientid == "" || typeof clientid === "undefined")
    {
      alert("Please select a client from the autocomplete list and then click submit.");
      return false;
    }
  }
  if($(e.target).hasClass('fileattachment_checkbox'))
  {
    var value = $(e.target).val();
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:1},
        success: function(result)
        {
          $(e.target).parent().find(".add_text").prop("disabled",true);
          $("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:0},
        success: function(result)
        {
          $(e.target).parent().find(".add_text").prop("disabled",false);
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('image_submit'))
  {
    var files = $(e.target).parent().find('.image_file').val();
    if(files == '' || typeof files === 'undefines')
    {
      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
      return false;
    }
    else{
      $(e.target).parents('td').find('.img_div').toggle();
    }
  }
  else{
    $(".img_div").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('image_submit_add'))
  {
    var files = $(e.target).parent().find('.image_file_add').val();
    if(files == '' || typeof files === 'undefines')
    {
      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
      return false;
    }
    else{
      $(e.target).parents('.modal-body').find('.img_div').toggle();
    }
  }
  else{
    $(".img_div").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('notepad_submit_add'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_add').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_notes_add').toggle();
    }
  }
  else{
    $(".notepad_div_notes_add").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('image_file'))
  {
    $(e.target).parents('td').find('.img_div').toggle();
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
  if($(e.target).hasClass('image_file_add'))
  {
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
  if($(e.target).hasClass("dropzone"))
  {
    $(e.target).parents('td').find('.img_div').show();    
    $(e.target).parents('.modal-body').find('.img_div').show();    
  }
  if($(e.target).hasClass("remove_dropzone_attach"))
  {
    $(e.target).parents('td').find('.img_div').show();   
    $(e.target).parents('.modal-body').find('.img_div').show(); 
  }
  if($(e.target).parent().hasClass("dz-message"))
  {
    $(e.target).parents('td').find('.img_div').show();
    $(e.target).parents('.modal-body').find('.img_div').show();    
  }
  if($(e.target).hasClass('notepad_contents_add'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_add').toggle();
  }
  if($(e.target).hasClass('notepad_submit_add'))
  {
    var contents = $(".notepad_contents_add").val();
    var clientid = $("#client_search").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents,clientid:clientid},
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div").append('<p>'+result+'</p>');
        $(".notepad_div_notes_add").hide();
      }
    });
  }
  if($(e.target).hasClass('trash_imageadd'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_attachments'); ?>",
      type:"post",
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('fa-plus-add'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  if($(e.target).hasClass('fa-plus-add-new'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_bpso').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

    $(".file_attachments_b").html("");
    $(".file_attachments_p").html("");
    $(".file_attachments_s").html("");
    $(".file_attachments_o").html("");   
  }
  else if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
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
    return false; 
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id,file_id:file_id},
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
  if($(e.target).hasClass('trash_image'))
  {
    var r = confirm("Are You sure you want to delete");
    if (r == true) {
      var imgid = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/infile_delete_image'); ?>",
          type:"get",
          data:{imgid:imgid},
          success: function(result) {
            window.location.reload();
          }
      });
    }
  }
  if($(e.target).hasClass('fa-pencil-square')){
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('fanotepadadd')){
    var clientid = $("#client_search").val();
    if(clientid == "")
    {
      alert("Please Choose the client id to create the attachments");
    }
    else{
      var pos = $(e.target).position();
      var leftposi = parseInt(pos.left) - 20;
      $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    }
  }
  if($(e.target).hasClass('hide_inactive'))
  {
    $(".task_tr").show();
    if($(".show_incomplete").is(":checked"))
    {
      $(".complete_cls").hide();
    }
    if($(e.target).is(":checked"))
    {
      $(".inactive_cls").hide();
    }
  }
  if($(e.target).hasClass('show_incomplete'))
  {
    $(".task_tr").show();
    if($(".hide_inactive").is(":checked"))
    {
      $(".inactive_cls").hide();
    }
    if($(e.target).is(":checked"))
    {
      $(".complete_cls").hide();
      
    }
  }
});
fileList = new Array();
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
              $(".email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Infile Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
            $(".email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Infile Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
Dropzone.options.imageUploadb = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#file_attachments_b").show();
              $("#file_attachments_b").append("<p>"+obj.filename+" </p>");
              $(".dz-preview").detach();
              $(".dz-message").show();
              $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        
         });
        this.on("error", function (file) {
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

Dropzone.options.imageUploadp = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#file_attachments_p").show();
              $("#file_attachments_p").append("<p>"+obj.filename+" </p>");
              $(".dz-preview").detach();
              $(".dz-message").show();
              $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        
         });
        this.on("error", function (file) {
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

Dropzone.options.imageUploads = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#file_attachments_s").show();
              $("#file_attachments_s").append("<p>"+obj.filename+" </p>");
              $(".dz-preview").detach();
              $(".dz-message").show();
              $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        
         });
        this.on("error", function (file) {
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

Dropzone.options.imageUploado = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#file_attachments_o").show();
              $("#file_attachments_o").append("<p>"+obj.filename+" </p>");
              $(".dz-preview").detach();
              $(".dz-message").show();
              $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        
         });
        this.on("error", function (file) {
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

Dropzone.options.imageUpload = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();
            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        
         });
        this.on("error", function (file) {
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
Dropzone.options.imageUpload5 = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div_task").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.file_id+"'>Remove</a></p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        });
        this.on("error", function (file) {
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
$(".client_search_class_task").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
        $("#client_search_task").val(ui.item.id);
        $.ajax({
          dataType: "json",
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#client_search_task").val(ui.item.id);
          }
        })
      }
  });
$(".client_search_class").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#client_search").val(ui.item.id);     
            $(".create_new_class").show();
          }
        })
      }
  });
$.ajaxSetup({async:false});
$( "#create_task_form" ).validate({
    rules: {
        select_user : {required: true},
        created_date : { required: true},   
        client_name : { required: true},   
        due_date : { required: true},   
    },
    messages: {
        select_user : {
          required : "Please select the Author",
        },
        created_date : {
            required : "Creation Date is required",
        },
        client_name : {
            required : "Client Name is required",
        },
        due_date : {
            required : "Due Date is required",
        },
    },
});
</script>
@stop