<html>
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php
$menu_logo_img = '';
$page_segment =  Request::segment(2);
if($page_segment == "dashboard") { $title = 'Bubble - Dashboard User Profile'; }
elseif($page_segment == "dashboard_analytics") { $title = 'Bubble - Dashboard User Stats'; }
elseif($page_segment == "dashboard_system_summary") { $title = 'Bubble - Dashboard System Summary'; }
elseif($page_segment == "dashboard_new") { $title = 'Bubble - Dashboard'; }
elseif($page_segment == "client_management") { $title = 'Bubble - Client Management System'; }
elseif($page_segment == "invoice_management") { $title = 'Bubble - Invoice Management System'; }
elseif($page_segment == "build_invoice") { $title = 'Bubble - Build Invoice System'; }
elseif($page_segment == "statement_list") { $title = 'Bubble - Statements (Current)'; }
elseif($page_segment == "full_view_statement") { $title = 'Bubble - Statements (Full)'; }
elseif($page_segment == "monthly_statement") { $title = 'Bubble - Statements (Multi Period)'; }
elseif($page_segment == "client_specific_statement") { $title = 'Bubble - Statements (Client)'; }
elseif($page_segment == "receipt_management") { $title = 'Bubble - Receipt Management'; }
elseif($page_segment == "receipt_settings") { $title = 'Bubble - Receipt Management'; }
elseif($page_segment == "payment_management") { $title = 'Bubble - Payment Management'; }
elseif($page_segment == "time_management") { $title = 'Bubble - Time Management System'; }
elseif($page_segment == "manage_week") { $title = 'Bubble - Weekly Payroll Management'; }
elseif($page_segment == "week_manage") { $title = 'Bubble - Weekly Payroll Management'; }
elseif($page_segment == "select_week") { $title = 'Bubble - Weekly Payroll Management'; }
elseif($page_segment == "manage_month") { $title = 'Bubble - Monthly Payroll Management'; }
elseif($page_segment == "month_manage") { $title = 'Bubble - Monthly Payroll Management'; }
elseif($page_segment == "select_month") { $title = 'Bubble - Monthly Payroll Management'; }
elseif($page_segment == "payroll_settings") { $title = 'Bubble - Payroll Settings'; }
elseif($page_segment == "p30") { $title = 'Bubble - PAYE P30'; }
elseif($page_segment == "paye_p30_manage") { $title = 'Bubble - PAYE M.R.S Manage'; }
elseif($page_segment == "paye_p30_ros_liabilities") { $title = 'Bubble - PAYE M.R.S ROS Liability'; }
elseif($page_segment == "paye_p30_email_distribution") { $title = 'PAYE M.R.S. Quick Email'; }
elseif($page_segment == "vat_clients") { $title = 'Bubble - VAT Management System'; }
elseif($page_segment == "vat_review") { $title = 'Bubble - VAT Management Review'; }
elseif($page_segment == "vat_notifications") { $title = 'Bubble - VAT Notification'; }
elseif($page_segment == "rct_system") { $title = 'Bubble - RCT Manager'; }
elseif($page_segment == "rct_summary") { $title = 'Bubble - RCT Summary'; }
elseif($page_segment == "rct_client_manager") { $title = 'Bubble - RCT Manager'; }
elseif($page_segment == "rct_liability_assessment") { $title = 'Bubble - RCT Manager'; }
elseif($page_segment == "rct_liability_disclosure") { $title = 'Bubble - RCT Liability Disclosure'; }
elseif($page_segment == "staff_review") { $title = 'Bubble - TimeMe (Staff Review)'; }
elseif($page_segment == "year_end_manager") { $title = 'Bubble - Year End Manager'; }
elseif($page_segment == "yearend_setting") { $title = 'Bubble - Year End Manager'; }
elseif($page_segment == "yearend_individualclient") { $title = 'Bubble - Year End Manager'; }
elseif($page_segment == "yeadend_clients") { $title = 'Bubble - Year End Manager'; }
elseif($page_segment == "time_track") { $title = 'Bubble - TimeMe Manager'; }
elseif($page_segment == "time_me_overview") { $title = 'Bubble - TimeMe (Active Jobs)'; }
elseif($page_segment == "time_me_joboftheday") { $title = 'Bubble - TimeMe (Jobs of the day)'; }
elseif($page_segment == "time_me_client_review") { $title = 'Bubble - TimeMe (Client Review)'; }
elseif($page_segment == "time_me_all_job") { $title = 'Bubble - TimeMe (All Jobs)'; }
elseif($page_segment == "time_task") { $title = 'Bubble - TimeMe Tasks'; }
elseif($page_segment == "in_file") { $title = 'Bubble - Infiles Module'; }
elseif($page_segment == "in_file_advance") { $title = 'Bubble - Infiles Module'; }
elseif($page_segment == "infile_search") { $title = 'Bubble - Infiles Module'; }
elseif($page_segment == "aml_system") { $title = 'Bubble - AML System'; }
elseif($page_segment == "client_request_system") { $title = 'Bubble - Client Request System'; }
elseif($page_segment == "client_request_manager") { $title = 'Bubble - Client Request System'; }
elseif($page_segment == "client_request_edit") { $title = 'Bubble - Client Request System'; }
elseif($page_segment == "yeadend_liability") { $title = 'Bubble - Year End Manager Liability Assessment'; }
elseif($page_segment == "ta_system") { $title = 'Bubble - T.A. System'; }
elseif($page_segment == "ta_allocation") { $title = 'Bubble - T.A. System'; }
elseif($page_segment == "ta_auto_allocation") { $title = 'Bubble - T.A. System'; }
elseif($page_segment == "ta_overview") { $title = 'Bubble - T.A. System'; }
elseif($page_segment == "task_manager") { $title = 'Bubble - Task Manager'; }
elseif($page_segment == "park_task") { $title = 'Bubble - Task Manager'; }
elseif($page_segment == "taskmanager_search") { $title = 'Bubble - Task Manager'; }
elseif($page_segment == "view_taskmanager_task") { $title = 'Bubble - Task (Extended)'; }
elseif($page_segment == "task_administration") { $title = 'Bubble - Task Manager'; }
elseif($page_segment == "task_overview") { $title = 'Bubble - Task Manager'; }
elseif($page_segment == "task_analysis") { $title = 'Bubble - Client Task Analysis'; }
elseif($page_segment == "directmessaging") { $title = 'Bubble - Message Us'; }
elseif($page_segment == "directmessaging_page_two") { $title = 'Bubble - Message Us'; }
elseif($page_segment == "directmessaging_page_three") { $title = 'Bubble - Message Us'; }
elseif($page_segment == "messageus_groups") { $title = 'Bubble - Message Us'; }
elseif($page_segment == "messageus_saved_messages") { $title = 'Bubble - Message Us'; }
elseif($page_segment == "opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }
elseif($page_segment == "opening_balance_invoices_issued") { $title = 'Bubble - Opening Balance Manager'; }
elseif($page_segment == "client_opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }
elseif($page_segment == "import_opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }
elseif($page_segment == "client_account_review") { $title = 'Bubble - Client Account Review'; }
elseif($page_segment == "financials") { $title = 'Bubble - Financials'; }
elseif($page_segment == "two_bill_manager") { $title = 'Bubble - 2Bill'; }
elseif($page_segment == "manage_croard") { $title = 'Bubble - CRO ARD'; }
elseif($page_segment == "croard_monthly") { $title = 'Bubble - CRO ARD'; }
elseif($page_segment == "key_docs") { $title = 'Bubble - Key Docs'; }
elseif($page_segment == "supplier_management") { $title = 'Bubble - Supplier Management'; }
elseif($page_segment == "supplier_invoice_management") { $title = 'Bubble - Supplier Invoice Management'; }
elseif($page_segment == "purchase_invoice_to_process") { $title = 'Bubble - Purchase Invoices to Process'; }
elseif($page_segment == "supplier_opening_balance") { $title = 'Bubble - Supplier - Opening Balance'; }
elseif($page_segment == "audit_trail") { $title = 'Bubble - Audit Trail'; }
elseif($page_segment == "bubble_mail") { $title = 'Bubble - BubbleMail'; }
elseif($page_segment == "tracking_project") { $title = 'Bubble - Tracking Project'; }
elseif($page_segment == "qba") { $title = 'Bubble - Quick Bank Analysis'; }
elseif($page_segment == "bank_account_manager") { $title = 'Bubble - Bank Account Manager'; }
elseif($page_segment == "staff_calendar") { $title = 'Bubble - Staff Calendar'; }
else{ $title = 'Bubble'; }
?>
<title><?php echo $title; ?></title>
<link rel="icon" href="<?php echo URL::to('public/assets/images/fav_icon.png'); ?>" sizes="16x16 32x32 64x64" type="image/png"/>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap-theme.min.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/font-awesome-4.2.0/css/font-awesome.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/style.css?v='.time().'')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/style-responsive.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/stylesheet-image-based.css')?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL::to('public/assets/css/datepicker/jquery-ui.css')?>">
<link rel="stylesheet" href="<?php echo URL::to('public/assets/plugins/lightbox/colorbox.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>"> -->
<link href="<?php echo URL::to('public/assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/new_style.css')?>">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery-2.1.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery.validate.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery.number.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.js'); ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/datepicker/jquery-ui.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/popper.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/bootstrap_3.3.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/lightbox/jquery.colorbox.js'); ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/moment/min/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<!-- <script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script> -->
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main.js'); ?>"></script>
<!-- Fullcalendar  -->
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins') ?>/fullcalendar/dist/index.global.min.js"></script>
<!-- Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  .modal-header button.close{
      padding: 15px !important;
  }
  .alert{
    padding: 12px 15px !important;
    clear:both;
  }
  .modal-header{
      padding:0px !important;
  }
  .menu-logo{
  }
  .menu-logo{
    /*background-size: 63px 33px;    */
    background-size: 45px 40px;
    margin-left: 1px;
    padding-left: 48px;         
    background-repeat: no-repeat;
    background-image:url(<?php echo URL::to('public/assets/images/bubble-icon-color.png'); ?>);
    width:100%;
  }
  .modal-title.menu-logo{
    background-size: 29px 22px;
    padding-left: 40px;       
    background-repeat: no-repeat;
    background-image:url(<?php echo URL::to('public/assets/images/bubble-icon-color.png'); ?>);
    margin:15px;
  }
  .modal-title{
    background-size: 30px 25px;
    padding-left: 40px;        
    background-repeat: no-repeat;
    background-image:url(<?php echo URL::to('public/assets/images/bubble-icon-color.png'); ?>);
    margin:15px;
    line-height: 1.5 !important;
    padding-top: 0px;
  }
  .modal-header .modal-title{
        padding-top: 1px;
        background-position: left;
  }
  .taskcreated_message{
    color: blue;
    font-size: 25px;
    text-align: center;
    font-weight: 600;
  }
body.loading {
  overflow: hidden;   
}
body.loading .modal_load {
    display: block;
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
.modal_load_trial {
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
body.loading_trial {
    overflow: hidden;   
}
body.loading_trial .modal_load_trial {
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
.modal_load_reconcilation {
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
body.loading_reconcilation {
    overflow: hidden;   
}
body.loading_reconcilation .modal_load_reconcilation {
    display: block;
}
.modal_load_receipt {
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
body.loading_receipts {
    overflow: hidden;   
}
body.loading_receipts .modal_load_receipt {
    display: block;
}
.modal_load_pratice {
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
body.loading_pratice {
    overflow: hidden;   
}
body.loading_pratice .modal_load_pratice {
    display: block;
}
.modal_load_notify {
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
body.loading_notify {
    overflow: hidden;   
}
body.loading_notify .modal_load_notify {
    display: block;
}
.modal_load_payment {
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
body.loading_payments {
    overflow: hidden;   
}
body.loading_payments .modal_load_payment {
    display: block;
}
.modal_load_delay {
    display:    none;
    position:   fixed;
    z-index:    9999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_delay {
    overflow: hidden;   
}
body.loading_delay .modal_load_delay {
    display: block;
}
.modal_load_calculation {
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
body.loading_calculations {
    overflow: hidden;   
}
body.loading_calculations .modal_load_calculation {
    display: block;
}
.vat_settings_table tbody tr td{
  border:0px;
}
.client_finance_modal label {
  width:auto !important;
}
#day-view-iframe-container {
    display: none; /* Hide the iframe container by default */
    width: 700px;
    padding: 12px;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    z-index: 1;
}
.tablefixedheader {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.tablefixedheader thead tr th {
  position: sticky;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    background-color: #fff !important;
}
<?php
 $segment1 =  Request::segment(2);  
  if($segment1 == 'manage_week' || $segment1 == 'week_manage' || $segment1 == 'select_week') { 
    if($segment1 == 'select_week') { ?>
      .body_bg{
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
          font-weight: bold;
          text-align: left;
          padding: 5px 0px;
          margin-bottom: 20px;
          color: #000;
          font-size: 15px;
          text-transform: uppercase;
          left: 0px;
      }
      <?php } 
    else{ ?>
      .body_bg{
      }
      <?php
      } 
  } 
  elseif($segment1 == 'manage_month' || $segment1 == 'month_manage' || $segment1 == 'select_month') { 
    if($segment1 == 'select_month') { ?>
      .body_bg{
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
      }
      <?php
    }
  } 
  elseif($segment1 == 'p30' || $segment1 == 'p30month_manage' || $segment1 == 'p30_select_month') { 
    if($segment1 == 'p30_select_month') { ?>
    .body_bg{
        background: #03d4b7;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #03d4b7;
      }
      <?php
    }
  }
  ?>
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover
{
      background-color: #000 !important;
      background-image:none !important;
      color:#fff !important;
}
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > a.dropdown-item:after {
    content: "\f054";
    float: right;
}
.dropdown-submenu > a.dropdown-item:after {
    content: ">";
    float: right;
    font-weight: 800;
    margin-right: -10px;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: 0px;
    margin-left: 0px;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
.dropdown-menu
{
  width:200px;
}
.dropdown-header{
  font-size: 16px;
}
.dropdown-menu .divider{
    height:1.5px;
}
.modal_load_apply, .modal_load, .modal_load_content, .modal_load_available, .modal_load_browse, .modal_load_review, .modal_load_import {z-index: 999999999999999999999999999999999999 !important;}
.modal{ margin-top:4%; }
.dropdown-toggle:hover, .dropdown-toggle:active, .dropdown-toggle:focus { text-decoration: underline !important; }
.navbar-default{background: #e7e7e7}
.ui-autocomplete{z-index:999999999999999999999999999999999999 !important; }
.modal_max_height{max-height: 700px; overflow:hidden; overflow-y: scroll; }
.modal_max_height_400{max-height: 400px; overflow:hidden; overflow-y: scroll; }
.modal_max_height_350{max-height: 350px; overflow:hidden; overflow-y: scroll; }
  .disabled_input{
    background: #dfdfdf !important;
  }
  .error{
    color:#f00;
  }
  .delete_emp_users{
    margin-left:20px;
  }
  body.loading_pratice {
    overflow: hidden;   
  }
  body.loading_pratice .modal_load_pratice {
      display: block;
  }
  .modal_load_pratice {
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
.nav-tabs li a:after {
    background-image: url(<?php echo URL::to('public/assets/images/bubble-icon-black.png'); ?>);
    background-size: 29px 22px;
    display: inline-block;
    width: 43px;
    height: 22px;
    content: "   ";
    background-repeat: no-repeat;
    margin: 0 0 -5px 10px;
}
.nav-tabs .active a:after {
    background-image: url(<?php echo URL::to('public/assets/images/bubble-icon-color.png'); ?>);
    background-size: 29px 22px;
    display: inline-block;
    width: 50px;
    height: 22px;
    content: "";
    background-repeat: no-repeat;
    margin: 0 0 -5px 10px;
}
.new_task_priority_div .taskmanager_rate{
  padding:0px;
}
.include_vat_breakdown{
  margin-left:0px !important;
}
.start_load {
  display:    block;
  position:   fixed;
  z-index:    999999999999999999999999;
  top:        0;
  left:       0;
  height:     100%;
  width:      100%;
  background: rgba( 255, 255, 255, 1 ) 
              url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
              50% 50% 
              no-repeat;
}
#colorbox{
  z-index: 99999999999;
}
div:where(.swalerrorcontainer) .swal2-html-container {
  font-size:16px !important;
}
div:where(.swalerrorcontainer).swal2-center > .swal2-popup {
  width: 20% !important;
}
</style>
</head>
<body class="body_bg">
<?php
if(!Session::has('taskmanager_user_email')){
  $sessn=array('taskmanager_user_email' => '');
  Session::put($sessn);
}
?>
<div class="start_load"></div>
<div class="loading_pratice modal_load_pratice text-center">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%; padding-top: 35px;">
    <span class="modal_pratice_span1"></span> <span class="modal_pratice_span2"></span>
  </p>
</div>
<div class="modal_load"></div>
<div class="modal_load_balance" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Opening Balance</p> </div>
<div class="modal_load_trial" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Nominal Details</p> </div>
<div class="modal_load_receipt" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Client Money Received</p> </div>
<div class="modal_load_payment" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Payments Made</p> </div>
<div class="modal_load_delay" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait as this may take upto 5 minutes to generate a PDF Report.</p> </div>
<div class="modal_load_reconcilation" style="text-align: center;">
  <p class="first_rec_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Posting Reconciling Journals, Please do not close your browser window, or restart your Computer until this process has finished.</p>
  <p class="first_rec_div" style="font-size:18px;font-weight: 600;">Reconciliation will be processed in batches of 1000. Processing the batches <span id="apply_rec_first"></span> of <span id="apply_rec_last"></span></p>
  <p class="second_rec_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Now, generating the Reconciliation Report in CSV format to be added in the Rec file and resorting the Transaction grid. Please wait.</p>
</div>
<div class="modal_load_notify" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">We are currently in the process of sending emails in batches of 100 to ensure efficient and fast delivery.</p>
  <p style="font-size:18px;font-weight: 600;">Sending Emails to Clients <span id="notify_first"></span> of <span id="notify_last"></span></p>
</div>
<div class="modal fade vat_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="margin-top:6%;width:50%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">VAT Settings</h4>
          </div>
          <div class="modal-body" style="clear:both">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active">
                <a class="nav-link active" id="vatemailsettings-tab" data-toggle="tab" href="#vatemailsettingstab" role="tab" aria-controls="vatemailsettingstab" aria-selected="false">Email Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="vatreviewsettings-tab" data-toggle="tab" href="#vatreviewsettingstab" role="tab" aria-controls="vatreviewsettingstab" aria-selected="false">VAT System VAT Review Settings</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="vatemailsettingstab" role="tabpanel" aria-labelledby="vatemailsettings-tab">
                <div class="admin_content_section" style="margin-top:20px">  
                <form action="<?php echo URL::to('user/update_user_signature'); ?>" method="post" id="update_user_signature_form">
                    <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                    <?php
                    $vat_settings = DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                    if($vat_settings->email_header_url == '') {
                    $default_image = DB::table("email_header_images")->first();
                    if($default_image->url == "") {
                      $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                    }
                    else{
                      $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                    }
                    }
                    else {
                    $image_url = URL::to($vat_settings->email_header_url.'/'.$vat_settings->email_header_filename);
                    }
                    ?>
                    <img src="<?php echo $image_url; ?>" class="vat_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                    <input type="button" name="vat_email_header_img_btn" class="common_black_button vat_email_header_img_btn" value="Browse">
                    <h4>VAT CC Email ID</h4>
                    <input id="validation-cc-email"
                         class="form-control"
                         placeholder="Enter VAT CC Email ID"
                         value="<?php echo $vat_settings->vat_cc_email; ?>"
                         name="vat_cc_email"
                         type="text"
                         required> 
                    <h4>VAT Email Signature :</h4>
                    <textarea class="form-control input-sm" id="editor_vat_review"  name="user_signature" style="height:100px"><?php echo $vat_settings->email_signature; ?></textarea>

                    <div class="col-md-12" style="text-align:right; margin-top:20px">
                        <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
                    </div>
                    @csrf
                </form>
                </div>
              </div>
              <div class="tab-pane fade" id="vatreviewsettingstab" role="tabpanel" aria-labelledby="vatreviewsettings-tab">
                <div class="row">
                    <div class="col-md-12">
                      <form action="<?php echo URL::to('user/update_vat_review_settings'); ?>" method="post" id="update_vat_review_settings_form">
                        <table class="table vat_settings_table" style="width:95%;margin-top:20px">
                          <tbody>
                            <?php
                            $setting_details = DB::table('vat_review_settings')->where('practice_code', Session::get('user_practice_code'))->first();
                            $period_checked = '';
                            $breakdown_checked = '';
                            $include_client_name_radio = '';
                            $subject = '';
                            $note = '';
                            $signature = '';
                            if($setting_details) {
                              if($setting_details->period_end == 1){
                                $period_checked = 'checked';
                              }
                              if($setting_details->breakdown == 1){
                                $breakdown_checked = 'checked';
                              }
                              if($setting_details->include_client_name == 1){
                                $include_client_name_radio = 'checked';
                              }
                              $subject = $setting_details->subject;
                              $note = $setting_details->note;
                              $signature = $setting_details->signature;
                            }
                            ?>
                              <tr>
                                <td style="width:13%;vertical-align: middle"><strong>Subject: </strong></td>
                                <td><input type="text" class="form-control vat_review_subject" style="margin-top: 8px;" name="vat_review_subject" placeholder="Enter Subject" value="<?php echo $subject; ?>"></td>
                                <td style="width:25%"><div class="approve_t2_div" style="float:none;text-align:left;margin-top:-11px"><input type="checkbox" class="form-control vat_review_period_end" id="vat_review_period_end" name="vat_review_period_end" value="1" <?php echo $period_checked; ?>> <label for="vat_review_period_end" class="include_vat_breakdown">Include Period End</label></div>
                                  <div class="approve_t2_div" style="float:none;text-align:left;margin-top:-17px">
                                    <input type="checkbox" class="form-control vat_review_client_name_subject" style="margin-top:-8px" id="vat_review_client_name_subject" name="vat_review_client_name_subject" value="1" <?php echo $include_client_name_radio; ?>> <label for="vat_review_client_name_subject" class="include_vat_breakdown">Include Client Name</label>
                                  </div>
                                </td>
                              </tr>
                            <tr>
                              <td style="vertical-align: middle"><strong>Notes: </strong></td>
                              <td colspan="2">
                                <textarea class="form-control input-sm" id="editor_vat_review_notes"  name="vat_review_notes" style="height:100px"><?php echo $note; ?></textarea>
                              </td>
                            </tr>
                            <tr>
                              <td style="vertical-align: middle"><strong>Breakdown: </strong></td>
                              <td colspan="2"><input type="checkbox" class="form-control vat_review_breakdown" id="vat_review_breakdown" name="vat_review_breakdown" value="1" <?php echo $breakdown_checked; ?>> <label for="vat_review_breakdown" class="include_vat_breakdown">Include VAT Breakdown</label></td>
                            </tr>
                            <tr>
                              <td style="vertical-align: middle"><strong>Signature: </strong></td>
                              <td colspan="2">
                                  <textarea class="form-control input-sm" id="editor_vat_review_signature"  name="vat_review_signature" style="height:100px"><?php echo $signature; ?></textarea>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" style="text-align: right">
                                <input type="submit" name="review_setting_submit" id="review_setting_submit" class="btn common_black_button" value="Update">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        @csrf
                      </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">
          </div>
      </div>
  </div>
</div>
<div class="modal fade vat_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_vat_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailVat" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal_load_calculation" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating the Sum of Balance.</p> </div>
<div class="modal fade detail_analysis_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="background: rgb(0,0,0,0.5); z-index: 999999">
  <div class="modal-dialog modal-md" role="document"  >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Detailed Analysis Report</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <div class="row">
            <div class="col-lg-12">
              <input type="radio" class="report_type_class" id="summary_report" name="analysis_report" value="1">
              <label for="summary_report">Summary Report</label>
              <input type="radio" class="report_type_class" id="details_report" name="analysis_report" value="2">
              <label for="details_report">Details Report</label>
              <input type="hidden" value="" class="analysis_report_type_input" name="">
              <label class="error error_report_type" style="padding-left: 8px; clear: both;  width: 100%"></label>
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <div class="col-lg-12">
              <input type="radio" class="report_format_class" id="report_format_pdf" name="analysis_report_frmat" value="3">
              <label for="report_format_pdf">PDF</label>
              <input type="radio" class="report_format_class" id="report_format_csv" name="analysis_report_frmat" value="4">
              <label for="report_format_csv">CSV</label>
              <input type="hidden" value="" class="analysis_report_format_input" name="">              
              <label class="error error_report_format" style="padding-left: 8px; clear: both; width: 100%"></label>
            </div>
          </div>
      </div>
      <div class="modal-footer" style="clear:both">
        <input type="button" class="analysis_report_button common_black_button" value="Export" name="">
      </div>
    </div>
  </div>
</div>
<div class="modal fade client_finance_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999 !important;">
  <div class="modal-dialog" role="document" style="width:90%">
    <div class="modal-content">
      <div class="modal-header" style="padding-bottom: 0px;border-bottom:0px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">
          Client Account Balance Manager
          <div style="float:right;margin-right: 24px;font-size: 16px;margin-top: 5px;">
            <input type="button" name="export_csv_client_opening" class="common_black_button export_csv_client_opening" value="Export CSV">
            <input type="button" name="export_detail_analysis" class="common_black_button export_detail_analysis" value="Report" style="display: none;">
            <input type="button" name="export_csv_summary" class="common_black_button export_csv_summary" value="Export CSV" style="display:none">
            <label>Client Account Opening Balance Date: </label> <spam class="opening_balance_date_spam"></spam>
          </div>
        </h4>
      </div>
      <div class="modal-body" style="min-height:700px;max-height: 700px;overflow-y:scroll;padding-top: 0px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="analysis-tab" data-toggle="tab" href="#analysistab" role="tab" aria-controls="analysistab" aria-selected="false">Analysis</a>
          </li>
          <li class="nav-item" style="width:15%">
            <a class="nav-link" id="detail-analysis-tab" data-toggle="tab" href="#detailanalysistab" role="tab" aria-controls="analysistab" aria-selected="false">Detailed Analysis</a>
          </li>
          <li class="nav-item" style="width:15%">
            <a class="nav-link" id="opening-balance-tab" data-toggle="tab" href="#openingtab" role="tab" aria-controls="openingtab" aria-selected="true">Opening Balance</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active in" id="analysistab" role="tabpanel" aria-labelledby="analysis-tab">
            <a href="javascript:" class="common_black_button load_summary_clients" style="clear: both;float: left;margin-top: 10px;" title="Load Clients">Load Clients</a>
            <table class="table table-fixed" id="summary_financial" style="display:none">
              <thead>
                  <tr>
                    <th style="text-align: left;width:8%">Client Code <i class="fa fa-sort client_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:8%">ActiveClient</th>
                    <th style="text-align: left;width:10%">Surname <i class="fa fa-sort surname_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:10%">Firstname <i class="fa fa-sort firstname_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:26%">Company Name <i class="fa fa-sort company_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Opening Balance <i class="fa fa-sort opening_bal_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Client Money <br/>Received <i class="fa fa-sort receipt_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Payments Made <i class="fa fa-sort payment_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Balance <i class="fa fa-sort balance_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                  </tr>
                </thead>
                <tbody id="summary_tbody">
                </tbody>
                <tr>
                  <td colspan="4"></td>
                  <td class="total_opening_balance_summary" style="text-align: right"></td>
                  <td class="total_receipt_summary" style="text-align: right"></td>
                  <td class="total_payment_summary" style="text-align: right"></td>
                  <td class="total_balance_summary" style="text-align: right"></td>
                </tr>
              </table>
          </div>
          <div class="tab-pane fade" id="detailanalysistab" role="tabpanel" aria-labelledby="detail-analysis-tab">
            <a href="javascript:" class="common_black_button load_details_analysis" style="clear: both;float: left;margin-top: 10px;" title="Load Detailed Analysis">Load Detailed Analysis</a>
            <div class="div_details_analysis">
            </div>
          </div>
          <div class="tab-pane fade" id="openingtab" role="tabpanel" aria-labelledby="opening-balance-tab">
            <table class="table table-fixed table-fixed-header" id="client_financial">
              <thead>
                  <tr>
                    <th style="text-align: left;width:10%">Client Code <i class="fa fa-sort client_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: center;width:10%">ActiveClient</th>
                    <th style="text-align: left;width:12%">Surname <i class="fa fa-sort surname_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:12%">Firstname <i class="fa fa-sort firstname_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:32%">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Debit <i class="fa fa-sort debit_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Credit <i class="fa fa-sort credit_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Balance <i class="fa fa-sort balance_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Commit</th>
                  </tr>
                </thead>
                <tbody id="client_tbody">
                <?php 
                  $clients = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->get();
                  if(($clients))
                  {
                    foreach($clients as $client)
                    {
                      $finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
                      $debit = '0.00';
                      $credit = '0.00';
                      $balance = '0.00';
                      $bal_style = '';
                      $owed_text = '';
                      $commit_style="display:none";
                      $commit_btn = '<input type="button" class="common_black_button commit_btn commit_btn_'.$client->client_id.'" value="Commit" data-element="'.$client->client_id.'" style="'.$commit_style.'">';
                      if(($finance_client))
                      {
                        $debit = ($finance_client->debit != "")?$finance_client->debit:"0.00";
                        $credit = ($finance_client->credit != "")?$finance_client->credit:"0.00";
                        if($debit != "" && $debit != "0.00" && $debit != "0" && $credit != "" && $credit != "0.00" && $credit != "0")
                        {
                          $balance = 'ERROR';
                          $bal_style = 'color:#f00;font-weight:600';
                        }
                        else{
                          $balance = ($finance_client->balance != "")?number_format_invoice_empty($finance_client->balance):"0.00";
                          $bal_style = '';
                          if($balance != "0.00" && $balance != "" && $balance != "0")
                          {
                            if($finance_client->balance >= 0) { $owed_text = '<spam style="color:green;font-size:12px;font-weight:600">Client Owes Back</spam>'; }
                            else { $owed_text = '<spam style="color:#f00;font-size:12px;font-weight:600">Client Is Owed</spam>'; }
                            $commit_style = 'display:block'; 
                          }
                        }
                        if($finance_client->journal_id == "")
                        {
                          $commit_btn = '<input type="button" class="common_black_button commit_btn commit_btn_'.$client->client_id.'" value="Commit" data-element="'.$client->client_id.'" style="'.$commit_style.'">';
                        }
                        else{
                          $commit_btn = '<a href="javascript:" class="journal_id_viewer" data-element="'.$finance_client->journal_id.'">'.$finance_client->journal_id.'</a>';
                        }
                      }
                      echo '<tr class="client_tr_'.$client->client_id.'">
                          <td class="client_sort_val" style="width:8%">'.$client->client_id.'</td>
                          <td align="center"><img class="active_client_list_tm1" data-iden="'.$client->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" /></td>
                          <td class="surname_sort_val" style="width:12%">'.$client->surname.'</td>
                          <td class="firstname_sort_val" style="width:12%">'.$client->firstname.'</td>
                          <td class="company_sort_val" style="width:32%">'.$client->company.'</td>
                          <td style="width:9%"><input type="text" class="form-control debit_fin_sort_val debit_fin_sort_val_'.$client->client_id.'" id="debit_fin_sort_val" value="'.number_format_invoice($debit).'" data-element="'.$client->client_id.'"></td>
                          <td style="width:9%"><input type="text" class="form-control credit_fin_sort_val credit_fin_sort_val_'.$client->client_id.'" id="credit_fin_sort_val" value="'.number_format_invoice($credit).'" data-element="'.$client->client_id.'"></td>
                          <td style="width:9%">
                            <input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'.$client->client_id.'" id="balance_fin_sort_val" value="'.$balance.'" style="'.$bal_style.'" disabled>
                            '.$owed_text.'
                          </td>
                          <td style="width:9%">
                              '.$commit_btn.'
                          </td>
                        </tr>';
                    }
                  }
                ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade open_client_review_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 999999 !important;">
  <div class="modal-dialog" role="document" style="width:95%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Client Account Review</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <iframe src="" id="client_revew_iframe" class="client_revew_iframe" style="width:100%;height:900px;border:0px"></iframe>
      </div>
      <div class="modal-footer" style="clear:both">
      </div>
    </div>
  </div>
</div>
<div class="modal fade practice_overview_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999 !important;">
  <div class="modal-dialog modal-sm" role="document" style="width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Practice Overview</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <?php
            $first_year = DB::table('invoice_system')->where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'ASC')->first();
            $last_year = DB::table('invoice_system')->where('practice_code',Session::get('user_practice_code'))->groupBy('invoice_date')->orderBy('invoice_date', 'DESC')->first();
            $first_year_invoice = '';
            $last_year_invoice = '';
            if($first_year)  {
                $first_year_invoice = date('Y', strtotime($first_year->invoice_date));
            }
            if($last_year)  {
                $last_year_invoice = date('Y', strtotime($last_year->invoice_date));
            }


            ?>
            <input type="hidden" value="<?php echo $first_year_invoice; ?>" class="practice_first_year" readonly>
            <input type="hidden" value="<?php echo $last_year_invoice; ?>" class="practice_last_year" readonly>
            <div class="col-lg-5">
              <h4>TURNOVER REVIEW</h4>
            </div>
            <div class="col-lg-1 text-center">
            </div>
            <div class="col-lg-6">
              <div class="row">
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button export_turnover" style="margin-left: 0px; float: left; width: 100%">EXPORT TURNOVER REVIEW</a>
                </div>
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button load_turnover_review" style="margin-left: 0px; float: left; width: 100%">LOAD TURNOVER REVIEW</a>
                </div>
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button practice_load_icon" style="margin-left: 0px; float: left; width: 100%">LOAD ALL SECTIONS</a>
                </div>
              </div>              
            </div>
            <div class="col-lg-12">              
              <div style="max-height: 300px; overflow: scroll; overflow-x: hidden;">
              <table class="own_table_white table" id="practice_turnover_table">
                <thead>
                  <tr>
                    <th style="text-align: left">YEAR</th>
                    <?php
                    $i=1;
                    $turnover_table_month='';
                    for($i = 1; $i <= 12; $i++){
                      $month = date('Y-'.$i.'-01');
                      $turnover_table_month.='<th style="text-align: right">'.date('M', strtotime($month)).'</th>';
                    }
                    $turnover_table_month.='<th style="text-align: right">Total</th>';
                    echo $turnover_table_month;
                    ?>                    
                  </tr>
                </thead>
                <tbody class="tbody_turnover_load">
                </tbody>
              </table>
              </div>
            </div>
          </div>
          <div class="row" id="client_review_div" style="margin-top: 30px;display:none">
            <?php
            $client_review = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->get();
            ?>
            <input type="hidden" class="client_review_total" readonly value="<?php echo count($client_review)?>" name="">
            <div class="col-lg-5">
              <h4>CLIENT REVIEW</h4>
            </div>
            <div class="col-lg-1 text-center">
            </div>
            <div class="col-lg-6">
              <div class="row">
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button export_client_review" style="margin-left: 0px; float: left; width: 100%">EXPORT CLIENT REVIEW</a>
                </div>
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button load_client_review_financial" style="margin-left: 0px; float: left; width: 100%">LOAD CLIENT REVIEW</a>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <input type="checkbox" id="redact_name" class="redact_name" name="">
                    <label for="redact_name">Redact Name</label>
                  </div>
                </div>
              </div>              
            </div>
            <div class="col-lg-12">
              ACTIVE CLIENTS: 
              <?php
              $active_client = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('active', '!=', '2')->get();
              echo count($active_client);
              ?>
            </div>
            <div class="col-lg-12">              
              <div style="max-height: 300px; overflow: scroll; overflow-x: hidden;">
              <table class="own_table_white table" id="practice_client_table">
                <thead>
                  <tr>
                    <th style="text-align: left">#</th>
                    <th style="text-align: left">CLIENT CODE</th>
                    <th style="text-align: left" class="practice_client_name">CLIENT NAME</th>
                    <?php
                    if($first_year && $last_year) {
                        $first_year = date('Y', strtotime($first_year->invoice_date));
                        $last_year = date('Y', strtotime($last_year->invoice_date));
                        $client_table_month='';
                        for($i = $first_year; $i <= $last_year; $i++){
                          $year = $i;
                          $client_table_month.='<th style="text-align: right">'.$year.'</th>';
                        }
                        /*$client_table_month.='<th>Total</th>'*/;
                        echo $client_table_month;
                    }
                    ?>
                    <th style="text-align: right">Total</th>
                  </tr>
                </thead>
                <tbody class="tbody_client_load">
                </tbody>
              </table>
              </div>
            </div>
          </div>
          <div class="row" id="staff_review_div" style="margin-top: 30px;display:none">
            <div class="col-lg-5">
              <h4>STAFF REVIEW</h4>
            </div>
            <div class="col-lg-1 text-center">
            </div>
            <div class="col-lg-6">
              <div class="row">
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button export_staff_review" style="margin-left: 0px; float: left; width: 100%">EXPORT STAFF REVIEW</a>
                </div>
                <div class="col-lg-4">
                  <a href="javascript:" class="common_black_button load_staff_review" style="margin-left: 0px; float: left; width: 100%">LOAD STAFF REVIEW</a>
                </div>
                <div class="col-lg-4">
                </div>
              </div>              
            </div>
            <div class="col-lg-12">              
              <div style="max-height: 300px; overflow: scroll; overflow-x: hidden;">
              <table class="own_table_white table" id="practice_staff_table">
                <thead>
                  <tr>
                    <th style="text-align: left">STAFF NAME</th>
                    <th style="text-align: left">BREAK EVEN POINT</th>
                  </tr>
                </thead>
                <tbody class="tbody_staff_load">
                </tbody>
              </table>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade active_client_manager_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <div class="row" style="display:flex; align-items:center;">
          <div class="col-md-4"><h4 class="modal-title" id="myModalLabel">Active Client Manager </h4></div>
          <div class="col-md-7" style="padding: 8px 0px;">
            <div class="row" style="float:right;">
              <div class="col-md-6">
                <label class="col-md-3" style="text-align: right; line-height: 35px;">User:</label>
                <div class="col-lg-9">
                  <select name="select_user" class="form-control select_user_AM">    
                      <?php
                      $selected = '';
                      $all_user_list = DB::table('user')->where('practice',Session::get('user_practice_code'))->orderBy('lastname','asc')->where('user_status', 0)->get();
                      if(($all_user_list)){
                        foreach ($all_user_list as $user) {
                          if(Session::has('userid'))
                          {
                            if($user->user_id == Session::get('userid')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }
                          if($user->disabled == 1){
                            $active_user = 'none';
                            $active_class = 'inactive_user';
                          }
                          else{
                            $active_user = 'block';
                            $active_class = '';
                          }
                      ?>
                        <option class="<?php echo $active_class?>" style="display: <?php echo $active_user ?>" value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.' '.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6" style="margin-top:8px;">
                <a href="javascript:" class="common_black_button delete_all_active_list">Clear All Active Clients</a>
                <a href="javascript:" class="common_black_button export_active_clients_list">Export</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-body" style="min-height:500px;max-height: 600px;overflow-y:scroll">
        <table class="table own_table_white" id="tbl_active_manager_list">
            <thead>
              <th style="text-align: left"># <i class="fa fa-sort code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Client Code <i class="fa fa-sort code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Client Name <i class="fa fa-sort des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Created Date <i class="fa fa-sort primary_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Delete <i class="fa fa-sort primary_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            </thead>
            <tbody id="active_manager_tbody">
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade nominal_codes_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Financial Setup <input type="button" class="common_black_button add_nominal" value="Add a Nominal" style="float:right;margin-right:15px"></h4>
      </div>
      <div class="modal-body" style="min-height:500px;max-height: 600px;overflow-y:scroll">
        <table class="table own_table_white">
            <thead>
              <th style="text-align: left">Code <i class="fa fa-sort code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Description <i class="fa fa-sort des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Primary Group <i class="fa fa-sort primary_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Debit Group <i class="fa fa-sort debit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Credit Group <i class="fa fa-sort credit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            </thead>
            <tbody id="nominal_tbody">
            <?php 
              $nominal_codes = DB::table('nominal_codes')->get();
              if(($nominal_codes))
              {
                foreach($nominal_codes as $codes)
                {
                  $des_code = $codes->description;
                  if($codes->type == 0)
                  {
                    echo '<tr class="des_tr_'.$codes->code.'">
                      <td class="code_sort_val">'.$codes->code.' <i class="fa fa-lock" title="Core Nominal"></i></td>
                      <td class="des_sort_val">'.$des_code.'</td>
                      <td class="primary_sort_val">'.$codes->primary_group.'</td>
                      <td class="debit_sort_val">'.$codes->debit_group.'</td>
                      <td class="credit_sort_val">'.$codes->credit_group.'</td>
                    </tr>';
                  }
                  else{
                    echo '<tr class="des_tr_'.$codes->code.' code_'.$codes->code.'">
                      <td><a href="javascript:" class="edit_nominal_code code_sort_val" data-element="'.$codes->code.'">'.$codes->code.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code des_sort_val" data-element="'.$codes->code.'">'.$des_code.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code primary_sort_val" data-element="'.$codes->code.'">'.$codes->primary_group.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code debit_sort_val" data-element="'.$codes->code.'">'.$codes->debit_group.'</a></td> 
                      <td><a href="javascript:" class="edit_nominal_code credit_sort_val" data-element="'.$codes->code.'">'.$codes->credit_group.'</a></td>
                    </tr>';
                  }
                }
              }
            ?>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <label style="margin-top:5px;float:left">Opening Financial Date:</label>  
        <?php 
        $date = DB::table('user_login')->where('id',1)->first();
        ?>
        <spam class="opening_date_spam" style="text-align: left;line-height: 32px;float:left"><?php echo date('d-M-Y', strtotime($date->opening_balance_date)); ?></spam><a href="javascript:" class="common_black_button edit_opening_balance_btn" style="float:left">...</a>
          <input type="text" name="opening_financial_date" class="opening_financial_date" value="<?php echo date('d-M-Y', strtotime($date->opening_balance_date)); ?>" style="display:none;width: 12%;padding: 7px;outline: none;float:left">
          <a href="javascript:" class="common_black_button save_opening_balance_btn" style="display:none;line-height: 32px;float:left">Save</a>
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
        <a href="<?php echo URL::to('user/bank_account_manager'); ?>" class="common_black_button bank_account_manager" target="_blank" style="float:right">Bank Account Manager</a>
        <a class="common_black_button" href="<?php echo URL::to('user/opening_balance_manager'); ?>" style="float:right">Opening Balances</a>
        <a href="javascript:" class="common_black_button accounting_period" style="float:right">Accounting Period</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade add_nominal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Add a Nominal</h4>
      </div>
      <div class="modal-body">
        <form name="add_nominal_form" id="add_nominal_form" method="post">
          <h4>Enter Nominal Code:</h4>
          <input type="text" name="nominal_code_add" class="form-control nominal_code_add" id="nominal_code_add" value="">
          <h4>Enter Description:</h4>
          <input type="text" name="description_add" class="form-control description_add" id="description_add" value="">
          <h4>Select Primary Group:</h4>
          <select name="primary_grp_add" class="form-control primary_grp_add" id="primary_grp_add">
              <option value="">Select Value</option>
              <option value="Profit & Loss">Profit & Loss</option>
              <option value="Balance Sheet">Balance Sheet</option>
          </select>
          <div class="debit_group_div" style="display:none">
            <h4>Select Debit Group:</h4>
            <select name="debit_grp_add" class="form-control debit_grp_add" id="debit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
          <div class="credit_group_div" style="display:none">
            <h4>Select Credit Group:</h4>
            <select name="credit_grp_add" class="form-control credit_grp_add" id="credit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
        @csrf
</form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary add_nominal_btn" value="Add Nominal">
      </div>
    </div>
  </div>
</div>
<div class="modal fade accounting_period_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999999999 !important; background: rgb(0,0,0,0.5);">
  <div class="modal-dialog" role="document" style="width:50%;">
    <div class="modal-content" style="z-index: 99999 !important">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Accounting Period</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12"><h4>Create Accounting Period</h4></div>
            <form name="accounting_period_form" id="accounting_period_form" method="post">
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Period Id:</label>
                  <?php
                  $accounting_period = DB::table('accounting_period')->where('practice_code', Session::get('user_practice_code'))->orderBy('accounting_id', 'desc')->first();
                  $count_accounting_period = 0;
                  if($accounting_period) {
                    $count_accounting_period = $accounting_period->accounting_id+1;
                  }
                  ?>
                  <input type="text" class="form-control ac_accounting_id" placeholder="Period ID" readonly value="<?php echo $count_accounting_period?>" name="ac_accounting_id">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>Start Date:</label>
                  <input type="text" class="form-control ac_start_date" placeholder="Select Start Date" name="ac_start_date">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="form-group">
                  <label>End Date:</label>
                  <input type="text" class="form-control ac_end_date" placeholder="Select End Date" name="ac_end_date">
                </div>
              </div>
              <div class="col-lg-5">
                <div class="form-group">
                  <label>Description:</label>
                  <textarea class="form-control ac_description" name="ac_description" placeholder="Enter Description"></textarea>
                </div>
              </div>
            @csrf
</form>
            <div class="col-lg-1" style="padding-left: 0px;">
              <div class="form-group">
                <label> </label>
                <input type="submit" class="common_black_button ac_save" style="float: right; width: 100%" value="Save" name="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12"><h4>List of Accounting Periods</h4></div>
            <div class="col-lg-12" style="max-height: 500px; overflow: scroll; overflow-x:hidden;">
              <table class="table own_table_white" id="ac_period_table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Period Id</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th style="width: 150px;">Description</th>
                    <th style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody id="tbody_account_period">
                  <?php
                  $accounting_period_list = DB::table('accounting_period')->where('practice_code', Session::get('user_practice_code'))->get();
                  $i=1;
                  $output_ac='';
                  if(($accounting_period_list)){
                    foreach ($accounting_period_list as $single_account) {
                      if($single_account->status == 1){
                        $status = '<a href="javascript:"><i class="fa fa-check default_account_period" style="color: #33CC66" data-element="'.$single_account->accounting_id.'"></i></a>';
                      }
                      else{
                        $status = '<a href="javascript:"><i class="fa fa-times account_period_active" data-element="'.$single_account->accounting_id.'" title="Active Default Period"  style="color: #E11B1C"></i></a>';
                      }
                      if($single_account->ac_lock == 0){
                        $lock = '<a href="javascript:"><i class="fa fa-lock class_account_unlock" data-element="'.$single_account->accounting_id.'" type="0" style="color: #E11B1C" title="Unlock Accounting Period"></i></a>';
                      }
                      else{
                        $lock = '<a href="javascript:"><i class="fa fa-unlock-alt class_account_lock" data-element="'.$single_account->accounting_id.'" type="1" style="color: #33CC66" title="Lock Accounting Period"></i>';
                      }
                      $output_ac.='
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.$single_account->accounting_id.'</td>
                          <td>'.date('d-M-Y', strtotime($single_account->ac_start_date)).'</td>
                          <td>'.date('d-M-Y', strtotime($single_account->ac_end_date)).'</td>
                          <td>'.$single_account->ac_description.'</td>
                          <td style="text-align:center;">
                            '.$status.'    '.$lock.'
                          </td>
                        </tr>';
                      $i++;
                    }
                  }
                  else{
                    $output_ac='
                      <tr>
                        <td></td>
                        <td></td>
                        <td>No Records found</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    ';
                  }
                  echo $output_ac;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade add_bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Add a Bank Account</h4>
      </div>
      <div class="modal-body">
        <form name="add_bank_form" id="add_bank_form" method="post">
          <h4>Enter Bank Name:</h4>
          <input type="text" name="bank_name_add" class="form-control bank_name_add" id="bank_name_add" value="">
          <h4>Enter Account Name:</h4>
          <input type="text" name="account_name_add" class="form-control account_name_add" id="account_name_add" value="">
          <h4>Enter Account No:</h4>
          <input type="text" name="account_no_add" class="form-control account_no_add" id="account_no_add" value="">
          <h4>Enter Nominal Description:</h4>
          <textarea name="nominal_description_add" class="form-control nominal_description_add" id="nominal_description_add"></textarea>
          <h4>Select Nominal Code:</h4>
          <select name="bank_code_add" class="form-control bank_code_add" id="bank_code_add">
              <option value="">Select Nominal Code</option>
          </select>
        @csrf
</form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="common_black_button add_bank_btn" value="Add Bank">
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Bank Account</h4>
      </div>
      <div class="modal-body">
        <form name="edit_bank_form" id="edit_bank_form" method="post">
          <h4>Enter Bank Name:</h4>
          <input type="text" name="bank_name_edit" class="form-control bank_name_edit" id="bank_name_edit" value="">
          <h4>Enter Account Name:</h4>
          <input type="text" name="account_name_edit" class="form-control account_name_edit" id="account_name_edit" value="">
          <h4>Enter Account No:</h4>
          <input type="text" name="account_no_edit" class="form-control account_no_edit" id="account_no_edit" value="">
          <h4>Enter Nominal Description:</h4>
          <textarea name="nominal_description_edit" class="form-control nominal_description_edit" id="nominal_description_edit"></textarea>
          <input type="hidden" name="hidden_bank_id_update" id="hidden_bank_id_update" value="">
        @csrf
</form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="common_black_button update_bank_btn" value="Update Bank">
      </div>
    </div>
  </div>
</div>
<div class="modal fade opening_balance_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Opening Balance</h4>
      </div>
      <div class="modal-body">
          <h4 style="font-weight:600">Bank Name: </h4><p id="bank_name_des" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Description: </h4><p id="bank_acc_des" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Name: </h4><p id="acc_name" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Number: </h4><p id="acc_no" style="font-weight:500"></p>
          <h4 style="font-weight:600">Enter Debit Balance:</h4>
          <input type="text" name="debit_balance_add" class="form-control debit_balance_add" id="debit_balance_add" value="">
          <h4 style="font-weight:600">Enter Credit Balance:</h4>
          <input type="text" name="credit_balance_add" class="form-control credit_balance_add" id="credit_balance_add" value="">
          <h4 style="font-weight:600">Opening Financial Date:</h4>
          <input type="text" name="opening_financial_date_val" class="form-control opening_financial_date_val" id="opening_financial_date_val" value="" disabled>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_bank_id" id="hidden_bank_id" value="">
        <input type="submit" class="common_black_button save_opening_balance" value="SAVE">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal_outstanding_payment_receipt" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title modal_outstanding_payment_receipt_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" style="clear:both">
          <table class="table own_table_white">
            <tbody id="tbody_outstanding_payment_receipt">
            </tbody>
          </table>
          <div id="outstanding_payment_receipt_message"></div>
          <input type="hidden" id="tbody_outstanding_payment_receipt_type" readonly name="">
          <input type="hidden" id="tbody_outstanding_payment_receipt_id" readonly name="">
      </div>
      <div class="modal-footer" style="clear:both">
        <div class="row">
          <div class="col-lg-6">
            <a href="javascript:" class="common_black_button outstanding_payment_receipt_delete" style="float: left;">Yes</a>
            <a href="javascript:" class="common_black_button" data-dismiss="modal" aria-label="Close" style="float: left;">No</a>
          </div>
          <div class="col-lg-6">
            <a href="javascript:" class="common_black_button outstanding_payment_receipt_download" style="float: right;">Download PDF</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade question_mark_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top:13%">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Process of Posting Initial Journals</h4>
      </div>
      <div class="modal-body">
          <p>A) Set Bank Opening Balance and Save</p>
          <p>B) Set Client Opening Balance and post</p>
          <p>C) Set Supplier Opening Balances and Post</p>
          <p>D) Set the Client Account Opening balances and Post</p>
          <p>E) Post Nominals for Invoices Imported into the System</p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade journal_viewer_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 9999999 !important;">
  <div class="modal-dialog modal-sm" role="document" style="width:70%;">
    <div class="modal-content">          
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" style="font-weight:700;font-size:20px">Journal Viewer</h4>
      </div>
      <div class="modal-body modal_max_height" style="min-height: 100px;">  
        <div class="col-md-12">
          <label style="float:left;margin-top:10px;margin-right:10px">Enter Journal ID:</label>
          <input type="text" name="journal_viewer_text_id" class="form-control journal_viewer_text_id" id="journal_viewer_text_id" placeholder="Enter Journal ID" value="" style="width:15%;float:left;">
          <input type="button" class="common_black_button journal_viewer_btn" value="Load">
          <input type="button" class="common_black_button load_journal_viewer_pdf" value="Download PDF" style="float:right">
          <input type="button" class="common_black_button class_general_journal" value="General Journal" style="float:right">
        </div>
        <table class="table own_table_white" style="margin-top:55px">
          <thead>
            <th>Journal ID</th>
            <th>Date</th>
            <th>Journal Description</th>
            <th>Journal Source</th>
            <th>Nominal Code</th>
            <th>Nominal Description</th>
            <th style="text-align: right;">Debit Value</th>
            <th style="text-align: right;">Credit Value</th>
          </thead>
          <tbody id="journal_viewer_tbody">
          </tbody>
          <tr>
            <td colspan="6" style="text-align: right;font-weight:800">Total:</td>
            <td class="journal_viewer_debit_total" style="text-align: right;font-weight:800"></td>
            <td class="journal_viewer_credit_total" style="text-align: right;font-weight:800"></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
      </div>
    </div>
  </div>
</div>
<div class="modal fade journal_source_viewer_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top:13%;z-index: 9999999 !important;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Journal Source</h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white">
            <thead class="header">
                <th>Code</th>
                <th>Journal Source</th>
            </thead>
            <tbody>
              <tr>
                <td>BOB</td>
                <td>BANK OPENING BALANCE</td>
              </tr>
              <tr>
                <td>CFA</td>
                <td>CLIENT FINANCE ACCOUNT</td>
              </tr>
              <tr>
                <td>GJ</td>
                <td>GENERAL JOURNAL</td>
              </tr>
              <tr>
                <td>SI</td>
                <td>SALES INVOICE</td>
              </tr>
              <tr>
                <td>PI</td>
                <td>PURCHASE INVOICE</td>
              </tr>
              <tr>
                <td>PAY</td>
                <td>PAYMENT SYSTEM</td>
              </tr>
              <tr>
                <td>RCPT</td>
                <td>RECEIPTS SYSTEM</td>
              </tr>
              <tr>
                <td>POB</td>
                <td style="text-transform: uppercase;">Purchase / Supplier Opening Balance</td>
              </tr>
              <tr>
                <td>SOB</td>
                <td style="text-transform: uppercase;">Sales / Customer Opening Balance</td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade payroll_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-lg" role="document" style="width:70%;">
    <div class="modal-content">          
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" style="font-weight:700;font-size:20px">Payroll Settings</h4>
      </div>
      <div class="modal-body" style="clear:both"> 
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="setemail-tab" data-toggle="tab" href="#setemail" role="tab" aria-controls="setemail" aria-selected="true">Email Settings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="setnotify-tab" data-toggle="tab" href="#setnotify" role="tab" aria-controls="setnotify" aria-selected="false">Notification Message</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="setdistribute-tab" data-toggle="tab" href="#setdistribute" role="tab" aria-controls="setdistribute" aria-selected="false">Distribute by Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Manage Year</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active in" id="setemail" role="tabpanel" aria-labelledby="setemail-tab">
            <div class="admin_content_section" style="margin-top:10px">  
              <div>
                <div class="table-responsive">
                  <div>
                    <?php
                      $pms_admin_details = DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->first();
                    if(Session::has('message')) { ?>
                        <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
                    <?php }
                    ?>
                  </div>
                  <div class="col-lg-12 text-left padding_00">
                    <form name="payroll_settings_form" id="payroll_settings_form" method="post" action="<?php echo URL::to('user/save_payroll_settings'); ?>">
                      <div class="col-md-12 padding_00">
                      <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 22%;margin-top: 13px;">PMS Email Header Image:</spam>
                      <?php
                      if($pms_admin_details->email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($pms_admin_details->email_header_url.'/'.$pms_admin_details->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="pms_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="pms_email_header_img_btn" class="common_black_button pms_email_header_img_btn" value="Browse">
                      </div>
                      <div class="col-md-12 padding_00" style="margin: 16px 0px;">
                      <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 22%;margin-top: 13px;">Paye M.R.S Email Header Image:</spam>
                      <?php
                      if($pms_admin_details->paye_mrs_email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($pms_admin_details->paye_mrs_email_header_url.'/'.$pms_admin_details->paye_mrs_email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="paye_mrs_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="paye_mrs_email_header_img_btn" class="common_black_button paye_mrs_email_header_img_btn" value="Browse">
                      </div>
                      <h4>Enter Email Signature:</h4>
                      <textarea name="message_editor" id="editor999"><?php echo $pms_admin_details->payroll_signature; ?></textarea>
                      <h4>Enter CC Box:</h4>
                      <input type="text" name="payroll_cc_input" class="form-control payroll_cc_input" value="<?php echo $pms_admin_details->payroll_cc_email; ?>">
                      <div class="modal-footer">  
                          <input type="submit" name="submit_payroll_settings" class="common_black_button submit_payroll_settings" value="Submit">
                      </div>
                    @csrf
</form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="setnotify" role="tabpanel" aria-labelledby="setnotify-tab">
            <form action="<?php echo URL::to('user/update_user_notification'); ?>" method="post" id="update_user_form">
              <h4>Enter Notification Message:</h4>
            <textarea class="form-control input-sm" id="editor_9999"  name="user_notification" style="height:100px"><?php echo $pms_admin_details->notify_message; ?></textarea>
            <div class="row">
              <div class="col-md-12" style="text-align:center; margin-top:20px">
                  <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
              </div>
            </div>
          @csrf
</form>
          </div>
          <div class="tab-pane fade" id="setdistribute" role="tabpanel" aria-labelledby="setdistribute-tab">
            <form action="<?php echo URL::to('user/update_distribute_link'); ?>" method="post" id="update_distribute_link">
              <h4>Enter Distribution by Link Message:</h4>
            <textarea class="form-control input-sm" id="editor_distribute"  name="distribute_link" style="height:100px"><?php echo $pms_admin_details->distribute_link; ?></textarea>
            <div class="row">
              <div class="col-md-12" style="text-align:center; margin-top:20px">
                  <input type="submit" name="distribute_submit" id="distribute_submit" class="btn common_black_button" value="Update">
              </div>
            </div>
          @csrf
</form>
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <iframe src="{{URL::to('user/manage_task')}}" style="width:100%;height:800px"></iframe>
          </div>
        </div>
          <!-- Content Header (Page header) -->
      </div>
      <div class="modal-footer" style="clear:both">
        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
      </div>
    </div>
  </div>
</div>
<div class="modal fade pms_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_pms_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailPms" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal fade paye_mrs_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_paye_mrs_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailPayemrs" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
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
<div class="modal fade payroll_access_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 9999999 !important;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%;">
    <div class="modal-content">          
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" style="font-weight:700;font-size:20px">Payroll Access</h4>
      </div>
      <div class="modal-body" style="clear:both"> 
        <label class="col-md-3" style="text-align: right;margin-top: 7px;">Select Employer: </label>
        <div class="col-md-6">
          <select name="select_payroll_employer" class="form-control select_payroll_employer">
            <option value="">Select Employer</option>
            <?php
              $tasks = DB::table('pms_task')->where('practice_code',Session::get('user_practice_code'))->where('task_enumber','!=','')->groupBy('task_enumber')->orderBy('task_name','asc')->get();
              if(($tasks))
              {
                foreach($tasks as $task){
                  $check_user = DB::table('employers')->where('practice_code',Session::get('user_practice_code'))->where('emp_no',$task->task_enumber)->first();
                  if(!($check_user)) {
                    echo '<option value="'.$task->task_enumber.'" data-element="'.$task->task_name.'">'.$task->task_name.' - '.$task->task_enumber.'</option>';
                  }
                }
              }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <input type="button" class="common_black_button add_to_employer" value="Add to List">
        </div>
        <div class="col-md-12" style="margin-top:20px;max-height: 600px;overflow-y: scroll">
            <table class="table own_table_white" id="payroll_access_expand">
              <thead>
                  <th>Employer no</th>
                  <th>Employer Name</th>
                  <th>Users Count</th>
                  <th>Action</th>
              </thead>
              <tbody id="employers_tbody">
                <?php
                $get_emps = DB::table('employers')->where('practice_code',Session::get('user_practice_code'))->get();
                if(($get_emps))
                {
                  foreach($get_emps as $emps)
                  {
                    $users_count = DB::table('employer_users')->where('practice_code',Session::get('user_practice_code'))->where('emp_id',$emps->id)->get();
                    echo '<tr>
                      <td>'.$emps->emp_no.'</td>
                      <td>'.$emps->emp_name.'</td>
                      <td class="emp_users_count emp_users_count_'.$emps->id.'">'.count($users_count).'</td>
                      <td>
                        <a href="javascript:" class="fa fa-user manage_employer_users" data-element="'.$emps->id.'" title="Manage Users" style="font-size: 20px;"></a>
                      </td>
                    </tr>';
                  }
                } else {
                  echo '<tr>
                    <td colspan="4" style="text-align:center">No records found</td>
                  </tr>';
                }
                ?>
              </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer" style="clear:both">
        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
      </div>
    </div>
  </div>
</div>
<div class="modal fade employer_users_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 9999999 !important;">
  <div class="modal-dialog modal-sm" role="document" style="width:50%;">
    <div class="modal-content">          
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" style="font-weight:700;font-size:20px">Add User</h4>
      </div>
      <div class="modal-body" style="clear:both"> 
        <form id="emp_users_form" method="post">
          <div class="col-md-6">
            <label style="margin-top:8px;">Employer No: </label>
            <input type="text" name="emp_no_add" class="form-control emp_no_add" value="" style="width: 70%;float: right;" disabled>
          </div>
          <div class="col-md-6">
            <label style="margin-top:8px;">Employer Name: </label>
            <input type="text" name="emp_name_add" class="form-control emp_name_add" value="" style="width: 70%;float: right;" disabled>
          </div>
          <div class="col-md-6" style="margin-top:20px">
            <label style="margin-top:8px;">Email: </label>
            <input type="email" name="email_user_add" id="email_user_add" class="form-control email_user_add" value="" required style="width: 70%;float: right;">
          </div>
          <div class="col-md-6" style="margin-top:20px">
            <label style="margin-top:8px;">Password: </label>
            <input type="password" name="password_user_add" id="password_user_add" class="form-control password_user_add" value="" required style="width: 70%;float: right;" autocomplete="new-password">
          </div>
          <hr>
          <input type="hidden" name="hidden_employer_payroll_id" id="hidden_employer_payroll_id" value="">
          <input type="button" name="add_users_to_emp" class="common_black_button add_users_to_emp" value="SUBMIT" style="text-align: center;margin-top: 20px;width: 95%;margin-left: 2.5%;">
        @csrf
</form>
        <h4 class="modal-title" style="font-weight:700;font-size:20px;margin-top: 20px">Manage Users</h4>
        <div class="col-md-12" style="margin-top:20px;max-height: 300px;overflow-y: scroll;">
          <table class="table own_table_white">
            <thead>
                <th>Employer no</th>
                <th>Email</th>
                <th>Password</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody id="employers_users_tbody">
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer" style="clear:both">
        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
      </div>
    </div>
  </div>
</div>
<div class="modal fade receipt_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999;margin-top:15%">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title receipt_payment_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white" id="result_payment_receipt" style="background: #fff;"></table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<?php
$userid = Session::get('userid');
$active_client_list =  \App\Models\ActiveClientList::join('cm_clients','cm_clients.client_id','=','active_client_list.client_id')
      ->select('active_client_list.*','cm_clients.company','cm_clients.active')
      ->where('active_client_list.practice_code',Session::get('user_practice_code'))
      ->where('active_client_list.user_id',$userid)->get();
$taskslist = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 0)->orderBy('task_name', 'asc')->get();
?>
<div class="modal fade create_quick_time_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
    <form action="<?php echo URL::to('user/quick_time_add')?>" method="post" class="add_new_quick_time" id="create_quick_time_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title menu-logo job_title">Create Quick Time</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input  type="checkbox" class="mark_internal_quick_time" name="internal_quick_time" id="mark_internal_quick_time"><label for="mark_internal_quick_time" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Job</label>
                <input  type="checkbox" class="mark_activeclient_quick_time" name="activeclient_quick_time" id="mark_activeclient_quick_time"><label for="mark_activeclient_quick_time" id="label_activeclient_quick_time" style="font-size: 14px; font-weight: normal; cursor: pointer;">Select from Active Client List</label>
                <input type="hidden" class="internal_type_quick_time" value="1" name="internal_type_quick_time">
                <input type="hidden" class="user_id_quick_time" value="" name="user_id_quick_time">
                <input type="hidden" id="hidden_job_id_quick_time" value="" name="hidden_job_id_quick_time">
                <input type="hidden" class="hidden_activejob_starttime_quick_time" id="hidden_activejob_starttime_quick_time" value="" name="hidden_activejob_starttime_quick_time">
            </div>
            <div class="form-group client_group_quick_time">
                <div class="form-title">Choose Client:</div>
                <input  type="text" class="form-control client_search_class_quick_time" name="client_name_quick_time" placeholder="Enter Client Name / Client ID" required style="width:95%; display:inline;">
                <img class="active_client_list_pms_quick_time" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />                      
                <input type="hidden" id="client_search_quick_time" name="clientid_quick_time" />
            </div>
            <div class="form-group active_client_group_quick_time" style="display: none;max-height: 500px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
              <div class="form-title">Select from active client list:</div>
              <!-- <div class="dropdown" style="width: 100%">
                <select class="form-control active_list_dropdown_quick_time" name="active_list_option_quick_time">
                  <option value=''>Select from active client list</option>
                  <?php
                    foreach($active_client_list as $list){
                      echo '<option value="'.$list->client_id.'" data-activestatus="'.$list->active.'">'.$list->company.' ('.$list->client_id.')'.'</option>';
                    }
                  ?>
                </select>               
              </div> -->
              <table class="table tablefixedheader own_table_white" id="active_client_quick_time_expand">
                <thead>
                    <th>ClientID</th>
                    <th>ClientName</th>
                    <th style="width: 85px;"><a href="javascript:" class="standard_minutes_link">Minutes</a></th>
                    <th><a href="javascript:" class="standard_task_link">Task</a></th>
                    <th><a href="javascript:" class="standard_comments_link">Comments</a></th>
                </thead>
                <tbody id="active_client_quick_tbody">
                    <?php
                    if(is_countable($active_client_list) && count($active_client_list) > 0) {
                      foreach($active_client_list as $list) {
                        ?>
                        <tr>
                          <td><?php echo $list->client_id; ?></td>
                          <td><?php echo $list->company; ?></td>
                          <td><input type="number" class="form-control quick_time_active_minutes" name="quick_time_active_minutes[]" value="" placeholder="Enter Minutes" oninput="validateNumericInput(this)" required></td>
                          <td>
                            <select name="idtask_quick_time_active[]" class="form-control idtask_quick_time_active">
                              <option value="">Select Task</option>
                              <?php
                              $clienttasks = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$list->client_id.'%')->orderBy('task_name', 'asc')->get();
                              if(($clienttasks)){
                                foreach ($clienttasks as $single_task) {
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
                                <option value="<?php echo $single_task->id; ?>"><?php echo $icon.$single_task->task_name?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>
                          </td>
                          <td>
                            <textarea class="form-control quick_time_active_comments" name="quick_time_active_comments[]" placeholder="Enter Comments"></textarea>
                          </td>
                        </tr>
                        <?php
                      }
                    }
                    else{
                      ?>
                      <tr>
                        <td>No Active Clients Found.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <?php
                    }
                    ?>
                </tbody>
              </table>
            </div>
            <div class="form-group internal_tasks_group_quick_time" style="display: none;">
                <div class="form-title">Select Task:</div>
                <div class="dropdown" style="width: 100%">
                  <a class="btn btn-default dropdown-toggle tasks_drop_quick_time" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">
                    <span class="task-choose_internal_quick_time">Select Task</span>  <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu internal_task_details_quick_time" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                    <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_quick_time">Select Task</a></li>
                      <?php
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
                        <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_quick_time" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                      <?php
                        }
                      }
                      ?>
                  </ul>
                </div>
            </div>
            <input type="hidden" id="idtask_quick_time" value="" name="task_id_quick_time">
            <div class="form-group tasks_group_quick_time" style="display: none;">    
                <div class="form-title">Select Task:</div>
                <div class="dropdown" style="width: 100%">
                  <a class="btn btn-default dropdown-toggle tasks_drop_quick_time" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%;text-align: left">
                    <span class="task-choose_quick_time">Select Task</span>  <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu task_details_quick_time" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                  </ul>
                </div>
            </div>
            <div class="form-group quick_time_minutes_div">
                <div class="form-title">Enter Minutes:</div>
                <div class="dropdown" style="width: 100%">
                  <input type="number" class="form-control quick_time_minutes" name="quick_time_minutes" id="quick_time_minutes" value="" required>
                </div>
            </div>  
            <div class="form-group quick_time_comments_div">
                <div class="form-title">Enter Comments:</div>
                <div class="dropdown" style="width: 100%">
                  <textarea class="form-control quick_time_comments" name="quick_time_comments" id="quick_time_comments"></textarea>
                </div>
            </div>  
            <input type="hidden" id="quickjob_quick_time" value="" name="quick_job_quick_time">
            <input type="hidden" class="acive_id_quick_time" value="" name="acive_id_quick_time">
            <input type="hidden" class="taskjob_id_quick_time" value="" name="taskjob_id_quick_time">
            <input type="hidden" value="" class="currentdate_quick_time" name="" >
            <input type="hidden" value="" class="add_edit_job_quick_time" name="add_edit_job_quick_time" >
            <input type="hidden" value="" id="hidden_job_type_quick_time" class="hidden_job_type_quick_time" name="hidden_job_type_quick_time">
          </div>
          <div class="modal-footer">
            <input type="hidden" name="create_quick_time_type" id="create_quick_time_type" value="">
            <input type="button" class="common_black_button quick_time_button_name" value="Create a Quick Time">
          </div>
        </div>
        @csrf
    </form>
  </div>
</div>
<div class="modal fade standard_minutes_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999;margin-top:15%">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Standard Quick Time Minutes</h4>
      </div>
      <div class="modal-body">
          <h4>Please enter the minutes in the Text box in Numeric only.</h4>
          <h4 style="margin-top: 29px;">Standard Minutes:</h4>
          <input type="number" name="standard_minutes_text" class="form-control standard_minutes_text" id="standard_minutes_text" value="" placeholder="Enter Standard Minutes" oninput="validateNumericInput(this)" style="width:50%">
      </div>
      <div class="modal-footer">
        <input type="button" class="common_black_button standard_minutes_btn" id="standard_minutes_btn" value="Apply"> 
      </div>
    </div>
  </div>
</div>
<div class="modal fade standard_comment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999;margin-top:15%">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Standard Quick Time Comment</h4>
      </div>
      <div class="modal-body">
          <h4>Please enter the comments in the Text box</h4>
          <h4 style="margin-top: 29px;">Standard Comment:</h4>
          <textarea name="standard_comment_text" class="form-control standard_comment_text" id="standard_comment_text" placeholder="Enter Standard Comments"></textarea>
      </div>
      <div class="modal-footer">
        <input type="button" class="common_black_button standard_comment_btn" id="standard_comment_btn" value="Apply"> 
      </div>
    </div>
  </div>
</div>
<div class="modal fade standard_task_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999;margin-top:15%">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Standard Quick Time Task</h4>
      </div>
      <div class="modal-body">
          <h4>Please select the Task from the dropdown list</h4>
          <h4 style="margin-top: 29px;">Standard Task:</h4>
          <select name="standard_task_select" class="form-control standard_task_select" id="standard_task_select">
            <option value="">Select Task</option>
            <?php
            $globaltaskslist = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', '!=', 0)->orderBy('task_name', 'asc')->get();
            if(($globaltaskslist)) {
                foreach($globaltaskslist as $tasktype) {
                    echo '<option value="'.$tasktype->id.'">'.$tasktype->task_name.'</option>';
                }
            }
            ?>
          </select>
      </div>
      <div class="modal-footer">
        <input type="button" class="common_black_button standard_task_btn" id="standard_task_btn" value="Apply"> 
      </div>
    </div>
  </div>
</div>
<?php

$suppliers = DB::table('suppliers')->where('practice_code', Session::get('user_practice_code'))->orderBy('supplier_name','asc')->get();
$nominal_codes = DB::table('nominal_codes')->where('practice_code', Session::get('user_practice_code'))->orderBy('code','asc')->get();
$vat_rates = DB::table('supplier_vat_rates')->where('practice_code', Session::get('user_practice_code'))->get();
?>
<div class="top_row" style="z-index: 999999999">
  <div class="col-lg-12 padding_00" style="height:84px">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL::to('user/dashboard')?>"><img style=" height:55px;" src="<?php echo URL::to('public/assets/images/bubble-color-logo-horizontal.png')?>" class="img-responsive" /></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <div style="float: left;margin-top: 12px;width:16%;font-size:13px">
            <?php
            $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
            $practice_code = Session::get('user_practice_code');
            $practice_name = '';
            if($practice_details)
            {
              $practice_code = $practice_details->practice_code;
              $practice_name = $practice_details->practice_name;
            }
            $user = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', Session::get('userid'))->first();
            ?>
            <spam style="width: 30%;float: left;">Practice Code </spam>:<spam style="font-weight:600;color:blue"> <?php echo $practice_code; ?></spam><br/>
            <spam style="width: 30%;float: left;">Practice Name </spam>:<spam style="font-weight:600;color:blue"> <?php echo $practice_name; ?></spam><br/>
            <spam style="width: 30%;float: left;">Logged in as </spam>:<spam style="font-weight:600;"> <a href="javascript:" class="open_user_logging_modal" style="color:blue" data-element="<?php echo Session::get('userid'); ?>" data-firstname="<?php echo $user->firstname; ?>" data-lastname="<?php echo $user->lastname; ?>" data-email="<?php echo $user->email; ?>" data-image="<?php echo($user->url != "") ? URL::to($user->url.'/'.$user->filename) : URL::to('public/assets/images/avatar.png'); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></a></spam>
          </div>
          <ul class="nav navbar-nav navbar-right menu">
            <li>
              <div style="float: left; margin-right: 10px; padding-top: 8px; margin-top: 5px; color:#777">Client Name:</div>
              <div style="float:left; width: 250px; margin-top: 5px;">
                <input type="text" class="form-control top_client_common_search ui-autocomplete-input" placeholder="Enter Client Name" name="">
                <input type="hidden" id="client_search_hidden_top_menu" name="">
              </div><br/>
              <spam style="color:red;position: absolute;left: 0;top: 45;width: 121%;" id="success_msg_active_list"></spam>
            </li>
            <li class="dropdown" style="margin-right: 10px;">
              <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="padding: 8px 13px; margin-top: 5px "><i style="font-size: 20px;" class="fa fa-caret-down" aria-hidden="true"></i></a>
              <ul class="dropdown-menu">
                <li><a href="javascript:" class="active_client_manager">Active Client Manager <i class="fa fa-external-link active_client_manager"></i></a></li>
                <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>
                <li class="<?php if(($segment1 == "client_account_review")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_account_review'); ?>">Client Account Review</a></li>
                <li class="<?php if(($segment1 == "in_file" || $segment1 == "in_file_advance" || $segment1 == "infile_search")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/in_file_advance')?>">InFiles</a></li>
                <li class="<?php if(($segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_view" || $segment1 == "client_request_edit")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_request_system'); ?>">Client Request System</a></li>
                <li class="<?php if(($segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/ta_system')?>">TA System</a></li>
                <li><a href="javascript:" class="notify_bank_account_btn">Notify Of Bank Account</a></li>
              </ul>
            </li>
            <li>
              <img class="active_client_list" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" 
                style="width:24px; cursor:pointer; margin-top:11px; margin-right:8px;" title="Add to active client list" />
            </li>
            <!---------------------Menu Items---------------------------->
            <li class="<?php if($segment1 == "task_manager" || $segment1 == "taskmanager_search" || $segment1 == "task_administration" || $segment1 == "park_task") { echo "active"; } else { echo ""; } ?>">
              <a href="<?php echo URL::to('user/task_manager')?>">Task Manager
              </a>
            </li>
            <li class="myday_calendar_link">
              <a href="javascript:" class="myday_calendar_link">My Day <i class="fa fa-caret-down" aria-hidden="true"></i></a>
              <div id="day-view-iframe-container">
                  <div id='calendar-container' style="width:100%;float:left">
                    <div id='calendar_myday'></div>
                  </div>
              </div>
            </li>
            <li class="<?php if(($segment1 == "in_file" || $segment1 == "in_file_advance" || $segment1 == "infile_search")) { echo "active"; } else { echo ""; } ?>">
              <a href="<?php echo URL::to('user/in_file_advance')?>">InFiles</a>
            </li>
            <li class="<?php if(($segment1 == "time_me" || $segment1 == "time_task" || $segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job" || $segment1 == "time_track" || $segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Time Me <i class="fa fa-caret-down" aria-hidden="true"></i></a>
              <ul class="dropdown-menu">
                <li class="<?php if(($segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_track')?>">TimeMe Manager</a></li>
                <li class="<?php if(($segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_me_overview')?>">TimeMe Overview</a></li>
                <li class="<?php if(($segment1 == "time_task")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_task')?>">Tasks</a></li>
                <li class="<?php if(($segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/ta_system')?>">TA System</a></li>
                <li><a href="javascript:" class="create_quick_time_menu">Quick Time <i class="fa fa-external-link create_quick_time_menu"></i></a></li>
              </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "manage_week" || $segment1 == "week_manage" || $segment1 == "select_week" || $segment1 == "manage_month" || $segment1 == "month_manage" || $segment1 == "select_month" || $segment1 == "p30" || $segment1 == "p30month_manage" || $segment1 == "p30_select_month" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month" || $segment1 == "paye_p30_manage" || $segment1 == "paye_p30_ros_liabilities" || $segment1 == "paye_p30_email_distribution") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Payroll Functions <i class="fa fa-caret-down" aria-hidden="true"></i></a>
              <?php 
                $year = DB::table('pms_year')->where('practice_code',Session::get('user_practice_code'))->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','desc')->first();
                $current_year_week = '#';
                $current_year_month = '#';
                $current_week_link = '#';
                $current_month_link = '#';
                if($year) {
                  $current_year_week = URL::to('user/week_manage/'.base64_encode($year->year_id));
                  $current_year_month = URL::to('user/month_manage/'.base64_encode($year->year_id));
                  $current_week = DB::table('pms_week')->where('practice_code',Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('week_id','desc')->first();
                  $current_month = DB::table('pms_month')->where('practice_code',Session::get('user_practice_code'))->where('year',$year->year_id)->orderBy('month_id','desc')->first();
                  if($current_week) {
                    $current_week_link = URL::to('user/select_week/'.base64_encode($current_week->week_id));
                  }
                  if($current_month) {
                    $current_month_link = URL::to('user/select_month/'.base64_encode($current_month->month_id));
                  }
                }
              ?>
                <ul class="dropdown-menu" style="width:280px">
                   <li class="dropdown-header">
                      Weekly Payroll Section
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "manage_week") || ($segment1 == "week_manage") || ($segment1 == "select_week")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/manage_week')?>">Weekly Payroll Manager</a></li>
                    <li style="padding-left: 14px;"><a href="<?php echo $current_year_week; ?>">Current Year</a></li>
                    <li style="padding-left: 14px;"><a href="<?php echo $current_week_link; ?>">Current Period</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Monthly Payroll Section
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "manage_month") || ($segment1 == "month_manage") || ($segment1 == "select_month")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/manage_month')?>">Monthly Payroll Manager</a></li>
                    <li style="padding-left: 14px;"><a href="<?php echo $current_year_month; ?>">Current Year</a></li>
                    <li style="padding-left: 14px;"><a href="<?php echo $current_month_link; ?>">Current Period</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      PMS System
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "p30") || ($segment1 == "p30month_manage" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month" || $segment1 == "paye_p30_manage" || $segment1 == "paye_p30_ros_liabilities" || $segment1 == "paye_p30_email_distribution") || ($segment1 == "p30_select_month")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/p30'); ?>">PAYE Modern Reporting System</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Payroll Client Remote Access
                    </li>
                    <li role="separator" class="divider"></li>
                    <li style="padding-left: 14px;"><a href="javascript:" class="payroll_access_btn">Payroll Access <i class="fa fa-external-link"></i></a></li>
                    <li style="padding-left: 14px;"><a href="javascript:" class="payroll_settings_btn">Payroll Settings <i class="fa fa-external-link"></i></a></li>
                </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "vat_clients" || $segment1 == "vat_review" || $segment1 == "rct_system" || $segment1 == "rct_liability_assessment" || $segment1 == "rct_client_manager" || $segment1 == "gbs_p30" || $segment1 == "gbs_p30month_manage" || $segment1 == "gbs_p30_select_month" || $segment1 == "year_end_manager" || $segment1 == "yearend_setting" || $segment1 == "supplementary_manager" || $segment1 == "yeadend_clients" || $segment1 == "yearend_individualclient" || $segment1 == "supplementary_note_create" || $segment1 == "gbs_paye_p30month_manage" || $segment1 == "gbs_paye_p30_select_month" || $segment1 == "yeadend_liability" || $segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_edit" || $segment1 == "client_request_view" || $segment1 == "manage_croard" || $segment1 == "directmessaging" || $segment1 == "directmessaging_page_two" || $segment1 == "directmessaging_page_three" || $segment1 == "messageus_groups" || $segment1 == "messageus_saved_messages" || $segment1 == "rct_summary" || $segment1 == "key_docs" || $segment1 == "qba") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Client Functions <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                <ul class="dropdown-menu" style="width: 280px;">
                  <?php
                  $year = DB::table('year_end_year')->where('status', 0)->orderBy('year','desc')->first();
                  ?>
                    <li class="dropdown-header">
                      Year End Manager
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "yeadend_clients")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/yeadend_clients/'.base64_encode($year->year).''); ?>">Current Year End Manager</a></li>
                    <li class="<?php if(($segment1 == "yeadend_liability")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/yeadend_liability/'.base64_encode($year->year).''); ?>">Current Year Liability Assessment</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Client Functions
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if($segment1 == "directmessaging" || $segment1 == "directmessaging_page_two" || $segment1 == "directmessaging_page_three" || $segment1 == "messageus_groups" || $segment1 == "messageus_saved_messages") { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/directmessaging')?>">MessageUs System</a></li>
                    <li class="<?php if(($segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_view" || $segment1 == "client_request_edit")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/client_request_system'); ?>">Client Request System</a></li>
                    <li class="<?php if(($segment1 == "key_docs")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a 
                      href="<?php echo URL::to('user/key_docs'); ?>">Key Docs</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Regulatory Functions
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "manage_croard")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/manage_croard'); ?>">CRO ARD</a></li>
                    <li class="<?php if(($segment1 == "rct_system") || ($segment1 == "rct_liability_assessment") || ($segment1 == "rct_client_manager")  || ($segment1 == "rct_summary")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/rct_system')?>">RCT System</a></li>
                    <li class="<?php if(($segment1 == "vat_clients") || ($segment1 == "vat_review")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/vat_review')?>">VAT Management</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Tools
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "tracking_project")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/tracking_project')?>">Project Tracking</a></li>
                    <li class="<?php if(($segment1 == "qba")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/qba')?>">QBA</a></li>
                </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "client_management" || $segment1 == "invoice_management" || $segment1 == "build_invoice" || $segment1 == "statement_list" || $segment1 == "full_view_statement" || $segment1 == "client_specific_statement" || $segment1 == "receipt_management" || $segment1 == "receipt_settings" || $segment1 == "time_management" || $segment1 == "aml_system" || $segment1 == "opening_balance_manager" || $segment1 == "client_opening_balance_manager" || $segment1 == "import_opening_balance_manager" || $segment1 == "two_bill_manager" || $segment1 == "client_account_review" || $segment1 == "financials" || $segment1 == "supplier_management" || $segment1 == "supplier_invoice_management" || $segment1 == "payment_management" || $segment1 == "audit_trail" || $segment1 == "bubble_mail" || $segment1 == "bank_account_manager" || $segment1 == "staff_calenders") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Practice Functions <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                <ul class="dropdown-menu dropdown-menu-right text-right" style="width: 180%;max-height: 800px;overflow-y: scroll;scrollbar-color: #f2f2f2 #fff;scrollbar-width: thin;">
                    <li class="dropdown-header">
                      Clients
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>
                    <li class="<?php if(($segment1 == "client_account_review")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/client_account_review'); ?>">Client Account Review</a></li>
                    <li class="<?php if(($segment1 == "aml_system")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/aml_system'); ?>">AML System</a></li>
                    <li class="<?php if(($segment1 == "bubble_mail")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/bubble_mail'); ?>">Bubble Mail</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Accounts
                    </li>
                    <li role="separator" class="divider"></li>
                    <li class="<?php if(($segment1 == "invoice_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/invoice_management'); ?>">Invoice Management</a></li>
                     <li class="<?php if(($segment1 == "build_invoice")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/build_invoice'); ?>">Build Invoice</a></li> 
                    <li class="<?php if(($segment1 == "receipt_management" || $segment1 == "receipt_settings")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/receipt_management'); ?>">Receipt Management</a></li>
                    <li class="<?php if(($segment1 == "payment_management" || $segment1 == "payment_settings")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/payment_management'); ?>">Payment Management</a></li>
                    <li class="twobillmenu <?php if(($segment1 == "two_bill_manager")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="javascript:" class="twobillmenu">2Bill Manager</a></li>
                    <li class="<?php if(($segment1 == "statement_list")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/statement_list'); ?>">Client Statements</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Practice Financials
                    </li>
                    <li role="separator" class="divider"></li>
                    <li style="padding-left: 14px;"><a href="javascript:" class="accounting_period">Accounting Period <i class="fa fa-external-link accounting_period"></i></a></li>
                    <li class="<?php if(($segment1 == "bank_account_manager")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/bank_account_manager'); ?>">Bank Account Manager</a></li>
                    <!-- <li style="padding-left: 14px;"><a href="javascript:" class="bank_account_manager">Bank Account Manager <i class="fa fa-external-link bank_account_manager"></i></a></li> -->
                    <li style="padding-left: 14px;"><a href="javascript:" class="client_finance_account_btn" >Client Finance Account <i class="fa fa-external-link client_finance_account_btn"></i></a></li>
                    <li style="padding-left: 14px;"><a href="<?php echo URL::to('user/opening_balance_manager'); ?>">Debtor Account Opening Balances</a></li>
                    <li style="padding-left: 14px;"><a href="javascript:" class="financial_setup">Financials Setup <i class="fa fa-external-link financial_setup"></i></a></li>
                    <li class="<?php if(($segment1 == "financials")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/financials'); ?>">Financials Overview</a></li>
                    <li style="padding-left: 14px;"><a href="javascript:" class="practice_overview">Practice Overview <i class="fa fa-external-link practice_overview"></i></a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Suppliers & Payables
                    </li>
                    <li role="separator" class="divider" style="height:2px"></li>
                    <li class="<?php if(($segment1 == "supplier_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/supplier_management'); ?>">Supplier Management</a></li>
                    <li class="<?php if($segment1 == "supplier_invoice_management") { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/supplier_invoice_management'); ?>">Supplier Invoice Management</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      System
                    </li>
                    <li role="separator" class="divider" style="height:2px"></li>
                    <li class="<?php if(($segment1 == "audit_trail")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/audit_trail'); ?>">Audit Trail</a></li>
                    <li role="separator" class="divider" style="background: #fff;"></li>
                    <li class="dropdown-header">
                      Staff Functions
                    </li>
                    <li role="separator" class="divider" style="height:2px"></li>
                    <li class="<?php if(($segment1 == "staff_calendar")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/staff_calendar'); ?>">Staff Calendar</a></li>
                </ul>
            </li>
            <li><a href="<?php echo URL::to('user/logout')?>" title="Logout" style="margin-top: -3px;"><i class="fa fa-sign-out fa-2x" style="font-size:2em !important;" aria-hidden="true"></i></a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
  </div>
</div>
<?php
if($page_segment == "task_manager" || $page_segment == "park_task" || $page_segment == "taskmanager_search" || $page_segment == "select_week" || $page_segment == "select_month" || $page_segment == "vat_review" || $page_segment == "manage_croard" || $page_segment == "in_file" || $page_segment == "in_file_advance" || $page_segment == "infile_search" || $page_segment == "purchase_invoice_to_process") {
  $clstaskname = 'create_new_task_model';
  $taskmanager_page = 0;
  $client_search_class = 'client_search_class_task';
  $client_search_task = 'client_search_task';
  $imageUpload_cls = 'imageUpload5';
  $add_attachments_div = 'add_attachments_div_task';
  $add_notepad_attachments_div = 'add_notepad_attachments_div_task';
  $create_new_taskmanager_task = 'user/create_new_taskmanager_task';
  $create_job_form = 'create_task_form';
  $company = '';
  $faplustask = 'fa-plus-task';
  $fanotepadtask = 'fanotepadtask';
  $img_div_task = 'img_div_task';
  $notepad_div_notes_add = 'notepad_div_notes_task';
  $notepad_contents_add = 'notepad_contents_task';
  $notepad_submit_add = 'notepad_submit_task';
  $create_taskmanager_type = 'taskmanager';
  if($page_segment == "task_manager" || $page_segment == "park_task" || $page_segment == "taskmanager_search" || $page_segment == "purchase_invoice_to_process") 
  { 
    $clstaskname = 'create_new_model';
    $taskmanager_page = 1;
    $client_search_class = 'client_search_class';
    $client_search_task = 'client_search';
    $imageUpload_cls = 'imageUpload1';
    $add_attachments_div = 'add_attachments_div';
    $add_notepad_attachments_div = 'add_notepad_attachments_div';
    $create_new_taskmanager_task = 'user/create_new_taskmanager_task';
    $create_job_form = 'create_job_form';
    $faplustask = 'fa-plus-add';
    $fanotepadtask = 'fanotepadadd';
    $img_div_task = 'img_div_add';
    $notepad_div_notes_add = 'notepad_div_notes_add';
    $notepad_submit_add = 'notepad_submit_add';
    $create_taskmanager_type = 'taskmanager';
  }
  else if($page_segment == "select_week" || $page_segment == "select_month"){
    $create_new_taskmanager_task = 'user/create_new_taskmanager_task';
    $faplustask = 'fa-plus-add';
    $fanotepadtask = 'fanotepadadd';
    $img_div_task = 'img_div_add';
    $notepad_div_notes_add = 'notepad_div_notes_add';
    $notepad_contents_add = 'notepad_contents_add';
    $notepad_submit_add = 'notepad_submit_add';
    $create_taskmanager_type = 'pms';
  }
  else if($page_segment == "vat_review"){
    $create_new_taskmanager_task = 'user/create_new_taskmanager_task_vat';
    $create_taskmanager_type = 'vat';
  }
  else if($page_segment == "manage_croard"){
    $create_new_taskmanager_task = 'user/create_new_taskmanager_task_croard';
    $create_taskmanager_type = 'croard';
  }
  else if($page_segment == "in_file" || $page_segment == "in_file_advance" || $page_segment == "infile_search"){
    $create_new_taskmanager_task = 'user/create_new_taskmanager_task';
    $create_taskmanager_type = 'infile';
  }
?>
<div class="modal fade <?php echo $clstaskname; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll;z-index:9999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:65%">
    <form action="<?php echo URL::to($create_new_taskmanager_task)?>" method="post" class="add_new_form" id="<?php echo $create_job_form; ?>">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="col-md-3">
              <label style="margin-top:5px">Author:</label>
              <input type="hidden" name="hidden_create_taskmanager_type" id="hidden_create_taskmanager_type" value="<?php echo $create_taskmanager_type; ?>">
              <select name="select_user" class="form-control select_user_author" required>
                <option value="">Select User</option>        
                  <?php
                  $selected = '';
                  $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                  if(($userlist)){
                    foreach ($userlist as $user) {
                      $selected = '';
                      if(Session::has('taskmanager_user'))
                      {
                        if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                      }
                  ?>
                    <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.' '.$user->firstname; ?></option>
                  <?php
                    }
                  }
                  ?>
              </select>
              <?php
              $session_email = '';
              if(Session::has('userid')) {
                $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', Session::get('userid'))->first();
                $session_email = $user_details->email;
              } ?>
              <input  type="email" class="form-control author_email" name="author_email" placeholder="Enter Author's Email" style="margin-top: 10px;" value="<?php echo $session_email; ?>" required>
              <div class="col-md-12 padding_00">
                <div style="margin-top:15px;margin-bottom:11px;">
                  <input type='checkbox' name="open_task" id="open_task" value="1"/>
                  <label for="open_task">OpenTask</label>
                </div>
              </div>
              <label>Allocate To:</label>
              <select name="allocate_user" class="form-control allocate_user_add">
                <option value="">Select User</option>        
                  <?php
                  $selected = '';
                  if(($userlist)){
                    foreach ($userlist as $user) {
                      $selected = '';
                      if($taskmanager_page == 1) {
                        if(Session::has('task_manager_user'))
                        {
                          if($user->user_id == Session::get('task_manager_user')) { $selected = 'selected'; }
                        }
                      }
                  ?>
                    <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.' '.$user->firstname; ?></option>
                  <?php
                    }
                  }
                  ?>
              </select>
              <input  type="email" class="form-control allocate_email" name="allocate_email" placeholder="Enter Allocate's Email" style="margin-top: 10px;" required>
              <div class="col-md-12 padding_00">
                <div class="internal_checkbox_div" style="margin-top:18px;margin-bottom:2px;<?php if($taskmanager_page == 1){ echo 'display: block'; } else { echo 'display: none'; }?>">
                  <input type='checkbox' name="internal_checkbox" id="internal_checkbox" value="1"/>
                  <label for="internal_checkbox">Internal</label>
                </div>
              </div>
              <div class="col-md-12 client_group padding_00">
                <label style="margin-top:10px">Client:</label>
              </div>
              <?php
              if(isset($_GET['client_id']))
              {
                $client_id = $_GET['client_id'];
                $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
                $company = $client_details->company.'-'.$client_id;
                $disabled = 'disabled';
              }
              else{
                $client_id = '';
                $company = '';
                $disabled = '';
              }
              ?>
              <div class="col-md-12 client_group padding_00">
                <input  type="text" class="form-control <?php echo $client_search_class; ?>" name="client_name" placeholder="Enter Client Name / Client ID" style="width:90%; display:inline;" value="<?php echo $company; ?>" required <?php echo $disabled; ?>>
                <img class="active_client_list_tm" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
                <input type="hidden" id="<?php echo $client_search_task; ?>" class="entered_client_id" name="clientid" value="<?php echo $client_id; ?>" />
              </div>
              <div class="col-md-12 internal_tasks_group padding_00" style="display: none;">
                <label style="margin-top:5px">Select Task:</label>
              </div>
              <div class="col-md-12 internal_tasks_group padding_00" style="display: none;">
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
                        <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>" data-project="<?php echo $single_task->project_id; ?>"><?php echo $icon.$single_task->task_name?></a></li>
                      <?php
                        }
                      }
                      ?>
                  </ul>
                  <input type="hidden" name="idtask" id="idtask" value="">
                </div>
              </div>
              <div class="col-lg-12 padding_00" style="margin-top:10px">
                  <label style="margin-top:5px">Priority:</label>
                  <div class="col-md-12 new_task_priority_div padding_00">
                    <?php echo user_rating(); ?>
                  </div>
              </div>
              <div class="col-lg-12 padding_00" style="margin-top: -10px;">
                  <label style="margin-top:5px">Creation Date:</label>
                  <div class="col-md-12 padding_00">
                    <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control created_date" placeholder="Select Creation Date" name="created_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                    </label>
                  </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="col-lg-12 padding_00">
                  <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                </div>
                <div class="col-lg-12 padding_00">
                  <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group start_group task_specifics_add margintop20" style="margin-top:10px !important">
                  <div class="col-lg-12 padding_00">
                    <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
                  </div>
                  <div class="col-lg-12 padding_00">
                    <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:600px"></textarea>
                  </div>
                </div>
                <?php
                if($taskmanager_page == 1) { ?>
                <div class="form-group start_group task_specifics_copy margintop20" style="margin-top:10px !important">
                    <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
                    <div class="task_specifics_copy_val" style="width:100%;height:400px;background: #e2e2e2;min-height: 400px;overflow-y: scroll;padding: 7px;"></div>
                    <input type="hidden" name="hidden_task_specifics" id="hidden_task_specifics" value="">
                </div>
                <?php } ?>
              </div>
              <div class="col-md-12" style="margin-top: 14px;">
                <div class="form-group date_group margintop20" style="margin-top:10px !important">
                    <div class="col-md-1" style="padding:0px">
                      <label style="margin-top:5px">DueDate:</label>
                    </div>
                    <div class="col-md-3">
                      <label class="input-group datepicker-only-init_date_received">
                          <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
                          <span class="input-group-addon">
                              <i class="glyphicon glyphicon-calendar"></i>
                          </span>
                      </label>
                    </div>
                    <div class="col-md-1" style="padding:0px;text-align: right">
                      <label style="margin-top:5px">Project:</label>
                    </div>
                    <div class="col-md-3">
                        <select name="select_project" class="form-control select_project">
                          <option value="">Select Project</option>
                          <?php
                              $projects = DB::table('projects')->get();
                              if(($projects)){
                                foreach($projects as $project){
                                  ?>
                                  <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_name; ?></option>
                                  <?php
                                }
                              }
                          ?>
                        </select>
                    </div>
                    <div class="col-md-2" style="padding:0px;text-align: right;right: 44px;">
                      <label style="margin-top:5px">Project Time:</label>
                    </div>
                    <div class="col-md-1" style="padding:0px">
                        <select name="project_hours" class="form-control project_hours">
                          <option value="">HH</option>
                          <?php
                          for($i = 0; $i <= 23; $i++)
                          {
                            if($i < 10) { $i = '0'.$i; }
                            ?>
                            <option value="{{$i}}">{{$i}}</option>
                            <?php
                          }
                          ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding:0px">
                        <select name="project_mins" class="form-control project_mins">
                          <option value="">MM</option>
                          <?php
                          for($i = 0; $i <= 59; $i++)
                          {
                             if($i < 10) { $i = '0'.$i; }
                            ?>
                            <option value="{{$i}}">{{$i}}</option>
                            <?php
                          }
                          ?>
                        </select>
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group start_group retreived_files_div margintop20" style="margin-top:10px !important">
                </div>
              </div>
              <div class="col-md-12 margintop20" style="margin-top:10px !important;clear: both; padding-top: 10px;">
                <div class="col-lg-4 padding_00">
                  <div class="form-group start_group">
                    <label>Task Files: </label>
                    <a href="javascript:" class="fa fa-plus <?php echo $faplustask; ?>" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
                    <a href="javascript:" class="fa fa-pencil-square <?php echo $fanotepadtask; ?>" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
                    <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
                    <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
                    <div class="img_div <?php echo $img_div_task; ?>" style="z-index:9999999; min-height: 275px">
                      <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                      @csrf
</form>
                      <div class="image_div_attachments">
                        <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                        <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="<?php echo $imageUpload_cls; ?>" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                            <input name="_token" type="hidden" value="">
                        @csrf
</form>              
                      </div>
                    </div>
                    <div class="<?php echo $notepad_div_notes_add; ?>" style="z-index:9999; position:absolute;display:none">
                      <textarea name="<?php echo $notepad_contents_add; ?>" class="form-control <?php echo $notepad_contents_add; ?>" placeholder="Enter Contents"></textarea>
                      <input type="button" name="<?php echo $notepad_submit_add; ?>" class="btn btn-sm btn-primary <?php echo $notepad_submit_add; ?>" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files_notepad_add"></spam>
                    </div>
                  </div>
                  <p id="attachments_text" style="display:none; font-weight: bold;">Files Attached:</p>
                  <div id="<?php echo $add_attachments_div; ?>">
                  </div>
                  <div id="<?php echo $add_notepad_attachments_div; ?>">
                  </div>
                  <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
                  <div id="add_infiles_attachments_div">
                  </div>
                </div>
                <div class="col-lg-4 padding_00">
                  <div class="form-group date_group" style="width: 100%">
                    <div class="form-title" style="font-weight:600;margin-left:21px">
                      <input type='checkbox' name="2_bill_task" class="2_bill_task" id="2_bill_task0" value="1"/> 
                      <label for="2_bill_task0" style="color:green;width: auto !important;">This task is a 2Bill Task!</label>
                      <img src="<?php echo URL::to('public/assets/images/2bill.png')?>" style="width:40px;margin-left:8px">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 padding_00">
                    <div class="form-group date_group">
                      <div class="form-title" style="font-weight:600;margin-left:26px"><input type='checkbox' name="auto_close_task" class="auto_close_task" id="auto_close_task0" value="1"/> <label for="auto_close_task0">This task is an Auto Close Task</label></div>
                    </div>
                    <div class="form-group date_group">
                      <div class="form-title" style="font-weight:600;margin-left:26px"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                      <div class="accept_recurring_div">
                        <p>This Task is repeated:</p>
                        <div class="form-title">
                          <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                          <label for="recurring_checkbox1">Monthly</label>
                        </div>
                        <div class="form-title">
                          <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                          <label for="recurring_checkbox2">Weekly</label>
                        </div>
                        <div class="form-title">
                          <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                          <label for="recurring_checkbox3">Daily</label>
                        </div>
                        <div class="form-title">
                          <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                          <label for="recurring_checkbox4">Specific Number of Days</label>
                          <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">     
            <input type="hidden" name="action_type" id="action_type" value="">
            <input type="hidden" name="hidden_specific_type" id="hidden_specific_type" value="">
            <input type="hidden" name="hidden_attachment_type" id="hidden_attachment_type" value="">
            <input type="hidden" name="hidden_task_id_copy_task" class="hidden_task_id_copy_task" value="">
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <?php 
            if($page_segment == "vat_review") { ?>
            <input type="hidden" name="hidden_vat_client_id" id="hidden_vat_client_id" value="">
            <input type="hidden" name="hidden_vat_month" id="hidden_vat_month" value="">
            <?php } ?>
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>
<?php } ?>
<div class="modal fade user_logging_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form id="logging_form" action="<?php echo URL::to('user/user_logging_password_after_login'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">User Account Manager</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group" style="text-align: center">
                <input type="file" name="profile_img" id="imgupload" onchange="readURL(this);" style="display:none"/> 
                <img src="<?php echo URL::to('public/assets/images/avatar.png'); ?>" class="img-rounded system_img" style="width:100px;height:100px;border: 1px solid #ccc;">
                <label for="message-text" class="col-form-label" style="width:100%">Choose Image</label>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Firstname:</label>
                <input type="text" name="firstname_logging" id="firstname_logging" class="form-control" placeholder="Enter Firstname" disabled>
              </div>
              <div class="form-group">            
                <label for="message-text" class="col-form-label">Enter Lastname:</label>
                <input type="text" name="lastname_logging" id="lastname_logging" class="form-control" placeholder="Enter Lastname" disabled>
              </div>
              <div class="form-group">    
                <label for="message-text" class="col-form-label">Enter Email:</label>
                <input type="email" name="email_logging" id="email_logging" class="form-control" placeholder="Enter Email" disabled>
              </div>
              <div class="form-group">    
                <label for="message-text" class="col-form-label">Enter Password:</label>
                <input type="password" name="password_logging" id="password_logging" class="form-control" placeholder="Enter Password" required>
              </div>
              <div class="form-group">    
                <label for="message-text" class="col-form-label">Enter Confirm Password:</label>
                <input type="password" name="cpassword_logging" id="cpassword_logging" class="form-control" placeholder="Enter Confirm Password" required>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_user_id" id="hidden_user_id" value="">
        <button type="button" class="btn btn-secondary common_button_gray" data-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary common_button submit_logging">SUBMIT</button>
      </div>
       @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade search_company_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:50%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <a href="javascript:" class="common_black_button send_company_info" style="float:right;margin-right: 25px;margin-top: 10px;">Send Email</a>
            <a href="javascript:" class="common_black_button pdf_company_info" style="float:right;margin-right: 25px;margin-top: 10px;" title="Download CRO Details">
              <i class="fa fa-file-pdf-o pdf_company_info" aria-hidden="true"></i>
            </a>
            <div id="pdf_placeholder"></div>
            <h4 class="modal-title job_title">Search Company</h4>
          </div>
          <div class="modal-body modal_max_height">  
            <div class="row">
              <div class="col-md-3">
                <h5>Company number:</h5>
                <input type="text" name="company_number" class="form-control company_number" value="">
              </div>
              <div class="col-md-3">
                <h5>Company / Business indicator:</h5>
                <input type="radio" name="indicator" class="indicator" id="indicator_1" value="C"><label for="indicator_1" style="width:auto;  margin-top: 6px; margin-left: -33px;">Limited Company</label>
              </div>
              <div class="col-md-3">
                <h5> </h5>
                <input type="radio" name="indicator" class="indicator" id="indicator_2" value="B"><label for="indicator_2" style="width:auto;  margin-top: 6px;">Registered Business</label>
              </div>
              <div class="col-md-3">
                <h5> </h5>
                <input type="button" class="common_black_button search_company_btn" id="search_company_btn" value="Call From CRO" style="margin-top: 1px;">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 table_api" style="margin-top:20px;">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>Company Number:</td>
                        <td><input type="text" name="company_number" class="form-control company_number" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company / Business indicator:</td>
                        <td><input type="text" name="indicator_text" class="form-control indicator_text" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Name:</td>
                        <td><input type="text" name="company_name" class="form-control company_name" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Address:</td>
                        <td><textarea name="company_address" class="form-control company_address" disabled style="height:110px"></textarea></td>
                      </tr>
                      <tr>
                        <td>Company Registration Date:</td>
                        <td><input type="text" name="company_reg_date" class="form-control company_reg_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status:</td>
                        <td><input type="text" name="company_status_desc" class="form-control company_status_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Date:</td>
                        <td><input type="text" name="company_status_date" class="form-control company_status_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Next ARD:</td>
                        <td><input type="text" name="next_ar_date" class="form-control next_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Last ARD:</td>
                        <td><input type="text" name="last_ar_date" class="form-control last_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Accounts Upto:</td>
                        <td><input type="text" name="last_acc_date" class="form-control last_acc_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type:</td>
                        <td><input type="text" name="comp_type_desc" class="form-control comp_type_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type Code:</td>
                        <td><input type="text" name="company_type_code" class="form-control company_type_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Code:</td>
                        <td><input type="text" name="company_status_code" class="form-control company_status_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Place of Business:</td>
                        <td><input type="text" name="place_of_business" class="form-control place_of_business" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Eircode:</td>
                        <td><input type="text" name="eircode" class="form-control eircode" value="" disabled></td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
<?php
$croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
?>
<div class="modal fade emailcompany" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:999999999999">
  <div class="modal-dialog" role="document" style="width:40%">
    <form id="email_company_form" action="<?php echo URL::to('user/email_company_files_croard'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Email</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <label style="margin-top:7px">From:</label>
            </div>
            <?php
              $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
              $uname = '<option value="">Select Username</option>';
              if(($userlist)){
                foreach ($userlist as $singleuser) {
                    if($uname == '')
                    {
                      $uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                    else{
                      $uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                  }
              }
            ?>
            <div class="col-md-5">
              <select name="select_user_company" id="select_user_company" class="form-control" title="Select the User" required>
                <?php echo $uname; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">To:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="to_user_company" id="to_user_company" class="form-control" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-1">
              <label style="margin-top:7px">CC:</label>
            </div>
            <div class="col-md-5">
              <input type="text" name="cc_company" class="form-control" value="<?php echo $croard_settings->croard_cc_email; ?>" readonly required>
            </div>
          </div>
          <?php
          if($croard_settings->email_header_url == '') {
            $default_image = DB::table("email_header_images")->first();
            if($default_image->url == "") {
              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
          }
          else {
            $image_url = URL::to($croard_settings->email_header_url.'/'.$croard_settings->email_header_filename);
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
            <div class="col-md-3">
              <label>Subject:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_company" class="form-control subject_company" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor2">
              </textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_client_id_search_company" id="hidden_client_id_search_company" value="">
        <input type="button" class="btn btn-primary common_black_button email_company_files_btn" value="Send Email">
      </div>
    </div>
    @csrf
</form>
  </div>
</div>
@yield('content')
<select name="select_nominal_codes_dummy" class="form-control select_nominal_codes_dummy" style="display:none">
  <option value="">Select Nominal Code</option>
  <?php
  if(($nominal_codes)) {
    foreach($nominal_codes as $code){
      echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
    }
  }
  ?>
</select>
<select name="select_vat_rates_dummy" class="form-control select_vat_rates_dummy" style="display:none">
  <option value="">Select VAT Rate</option>
  <?php
  if(($vat_rates)) {
    foreach($vat_rates as $rate){
      echo '<option value="'.$rate->vat_rate.'">'.$rate->vat_rate.' %</option>';
    }
  }
  ?>
</select>
<div class="modal fade add_purchase_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false"  style="z-index: 9999999999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
    <div class="modal-content">
      <form name="add_purchase_invoice_form" id="add_purchase_invoice_form" method="post" action="<?php echo URL::to('user/store_purchase_invoice'); ?>">
        <div class="modal-header">
          <button type="button" class="close close_purchase_invoice"><span class="close_purchase_invoice" aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add Purchase Invoice</h4>
        </div>
        <div class="modal-body modal_max_height" style="clear:both">
          <div class="col-md-10">
            <table class="table" style="width:100%">
              <thead>
                <tr>
                  <td style="width:11%;font-weight:600;vertical-align: middle;">Select Supplier: </td> 
                  <td style="width:22%">
                    <select name="select_supplier" class="form-control select_supplier select_supplier_invoice" required>
                      <option value="">Select Supplier</option>
                      <?php
                      if(($suppliers)){
                        foreach($suppliers as $supplier){
                          echo '<option value="'.$supplier->id.'">'.$supplier->supplier_name.'</option>';
                        }
                      }
                      ?>
                    </select>
                  </td>
                  <td style="width:11%">
                    <a href="javascript:" class="common_black_button add_supplier_invoice" style="font-size:14px;font-weight: bold; float: left; margin-top:-1px ">Add Supplier</a>
                  </td>
                  <td style="width:20%; font-weight:600;vertical-align: middle;">
                    Select Accounting Period:
                  </td>
                  <td style="width:33%" colspan="2">
                    <select class="form-control invoice_ac_accounting_id" style="width: 95%; font-weight:bold;" name="ac_accounting_id" required>
                      <?php
                      $output_account='';
                      $accounting_period_list = DB::table('accounting_period')->where('practice_code', Session::get('user_practice_code'))->orderBy('status', 'desc')->get();
                      if(($accounting_period_list)){
                        foreach ($accounting_period_list as $single_account){
                          if($single_account->ac_lock == 0){
                            $lock_text = 'Locked';
                            $color = '#E11B1C';
                            $value = '';
                          }
                          else{
                            $lock_text = 'Unlocked';
                            $color='#33CC66';
                            $value = $single_account->accounting_id;
                          }
                          $start_date = date('d-M-Y', strtotime($single_account->ac_start_date));
                          $end_date = date('d-M-Y', strtotime($single_account->ac_end_date));
                          $output_account.='<option value="'.$value.'" style="color:'.$color.'; font-weight:bold;">'.$start_date.' '.$end_date.' '.$lock_text.'</option>';
                        }
                      }
                      else{
                        $output_account='<option value="">No Records found</option>';
                      }
                      echo $output_account;
                      ?>
                    </select>
                  </td>
                </tr>
              </thead>
              <tbody id="supplier_detail_tbody">
                <tr>
                  <td style="font-weight:600">Supplier Code: </td> 
                  <td class="supplier_code_td"> </td>
                  <td style="font-weight:600">Supplier Name: </td> 
                  <td class="supplier_name_td"></td>
                  <td style="font-weight:600">Web Url: </td> 
                  <td class="web_url_td"> </td>
                </tr>
                <tr>
                  <td style="font-weight:600">Phone No: </td> 
                  <td class="phone_no_td"> </td>
                  <td style="font-weight:600">Email: </td>
                  <td class="email_td"></td>
                  <td style="font-weight:600">IBAN: </td> 
                  <td class="iban_td"> </td>
                </tr>
                <tr>
                  <td style="font-weight:600">BIC: </td> 
                  <td class="bic_td"> </td>
                  <td style="font-weight:600">VAT No: </td> 
                  <td class="vat_no_td"> </td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-2 text-right" style="margin-top:17px">
            <a href="javascript:" class="common_black_button transaction_list">Load Transaction List</a>
          </div>
          <div class="col-md-12">
            <div class="col-md-12" style="background: #E0BBE4">
              <h4 style="font-weight:600">Purchase Invoice Header Information</h4>
              <input type="hidden" name="hidden_global_id" id="hidden_global_id" value="">
              <input type="hidden" name="hidden_sno" id="hidden_sno" value="">
              <table class="table">
                <thead>
                  <th style="width:10%">Invoice No</th>
                  <th style="width:15%">Invoice Date</th>
                  <th style="width:15%">Reference</th>
                  <th style="width:15%">Net Value</th>
                  <th style="width:15%"></th>
                  <th style="width:15%">VAT</th>
                  <th style="width:15%">Gross</th>
                </thead>
                <tbody id="global_invoice_tbody">
                  <tr>
                    <td><input type="text" name="inv_no_global" class="form-control inv_no_global" value="" placeholder="Enter Invoice No" required></td>
                    <td><input type="text" name="inv_date_global" class="form-control inv_date_global" value="" placeholder="Enter Invoice Date" required></td>
                    <td><input type="text" name="ref_global" class="form-control ref_global" value="" placeholder="Enter Reference" required></td>
                    <td><input type="text" name="net_global" class="form-control net_global" value="" placeholder="Enter Net Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required></td>
                    <td></td>
                    <td><input type="text" name="vat_global" class="form-control vat_global" value="" placeholder="Enter VAT Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required></td>
                    <td><input type="text" name="gross_global" class="form-control gross_global" value="" placeholder="Enter Gross" readonly required></td>
                  </tr>
                </tbody>
              </table>
              <p id="attachment_global_supplier_tbody">
                <spam class="global_file_upload"></spam>
                <input type="hidden" name="global_file_url" id="global_file_url" value="">
                <input type="hidden" name="global_file_name" id="global_file_name" value="">
                <a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment" data-element=""></a>
              </p>
            </div>
            <div class="col-md-12">
              <h4 style="font-weight:600">Invoice Line Details</h4>
              <table class="table">
                <thead>
                  <th style="width:10%">S.No</th>
                  <th style="width:15%">Description</th>
                  <th style="width:15%">Nominal Code</th>
                  <th style="width:15%">Net Value</th>
                  <th style="width:15%">VAT Rate</th>
                  <th style="width:15%">VAT</th>
                  <th style="width:10%">Gross</th>
                  <th style="width: 5%;"> </th>
                </thead>
                <tbody id="detail_tbody">
                  <tr>
                    <td>1</td>
                    <td><input type="text" name="description_detail[]" class="form-control description_detail" value="" placeholder="Enter Description"></td>
                    <td>
                      <select name="select_nominal_codes[]" class="form-control select_nominal_codes">
                        <option value="">Select Nominal Code</option>
                        <?php
                        if(($nominal_codes)) {
                          foreach($nominal_codes as $code){
                            echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                          }
                        }
                        ?>
                      </select>
                    </td>
                    <td><input type="text" name="net_detail[]" class="form-control net_detail" value="" placeholder="Enter Net Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');"></td>
                    <td>
                      <select name="select_vat_rates[]" class="form-control select_vat_rates">
                        <option value="">Select VAT Rate</option>
                        <?php
                        if(($vat_rates)) {
                          foreach($vat_rates as $rate){
                            echo '<option value="'.$rate->vat_rate.'">'.$rate->vat_rate.' %</option>';
                          }
                        }
                        ?>
                      </select>
                    </td>
                    <td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="" placeholder="Enter VAT Value" readonly></td>
                    <td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="" placeholder="Enter Gross" readonly></td>
                    <td class="detail_last_td" style="vertical-align: middle;text-align: center">
                      <a href="javascript:" class="fa fa-plus add_detail_section" title="Add Row"></a>
                    </td>
                  </tr>
                </tbody>
                <tr>
                  <td colspan="3" style="text-align: right;font-weight:600;vertical-align: middle">Total:</td>
                  <td><input type="text" name="total_detail_net" class="form-control total_detail_net" value="" placeholder="Total Net Value" readonly></td>
                  <td></td>
                  <td><input type="text" name="total_detail_vat" class="form-control total_detail_vat" value="" placeholder="Total VAT Value" readonly></td>
                  <td><input type="text" name="total_detail_gross" class="form-control total_detail_gross" value="" placeholder="Total Gross Value" readonly></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="button" name="submit_purchase_invoice" id="submit_purchase_invoice" class="common_black_button submit_purchase_invoice" value="Submit Purchase Invoice">
        </div>
      @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade" id="add_supplier_invoice_modal" tabindex="-1" data-backdrop="static"  role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999999999999; background: rgb(0,0,0,0.8);">
  <div class="modal-dialog modal-lg" role="document" style="width:50%; z-index: 99999999999999999999999; ">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add Supplier</h4>
        </div>
        <div class="modal-body" style="clear:both;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="first-tab__supplier_invoice" data-toggle="tab" href="#first_supplier_invoice" role="tab" aria-controls="first_supplier_invoice" aria-selected="true">Add Supplier</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab_supplier_invoice" data-toggle="tab" href="#home_supplier_invoice" role="tab" aria-controls="home_supplier_invoice" aria-selected="true">Supplier Opening Balance</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent_supplier_invoice" style="margin-top:20px;">
              <div class="tab-pane fade active in" id="first_supplier_invoice" role="tabpanel" aria-labelledby="first-tab__supplier_invoice">
                <?php
                $supplier_count_invoice = DB::table('suppliers')->where('practice_code', Session::get('user_practice_code'))->orderBy('id','desc')->first(); 
                if(($supplier_count_invoice))
                {
                  $count_invoice = substr($supplier_count_invoice->supplier_code,3,7);
                  $count_invoice = sprintf("%04d",$count_invoice + 1);
                }
                else{
                  $count_invoice = sprintf("%04d",1);
                }
                $pcodeval = Session::get('user_practice_code');
                ?>
                  <div class="row" style="margin: 0px;">
                    <div class="col-md-4 col-lg-4">
                      <label>Supplier Code : </label>
                      <div class="form-group">            
                        <input class="form-control supplier_code_class" name="supplier_code" placeholder="Enter Supplier Code" type="text" value="<?php echo $pcodeval.$count_invoice; ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                      <label>Supplier Name : </label>
                      <div class="form-group">            
                        <input class="form-control supplier_name_class" name="supplier_name" placeholder="Enter Supplier Name" type="text" required>
                        <label class="error error_supplier_name_class" style="display: none;">Enter Supplier Name</label>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="margin: 0px;">
                    <div class="col-lg-9">
                      <div class="paragraph_title">
                        <div class="para_text">Contact Details</div>
                        <div class="row">
                          <div class="col-md-12 col-lg-12">
                            <label>Supplier Address : </label>
                            <div class="form-group">            
                              <input class="form-control supp_address_class" name="supp_address" placeholder="Enter Supplier Address" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Web URL : </label>
                            <div class="form-group">            
                              <input class="form-control supplier_address_class" name="supplier_address" placeholder="Enter Web URL" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Email Address : </label>
                            <div class="form-group">            
                              <input class="form-control supplier_email_class" name="supplier_email" placeholder="Enter Email Address" type="email">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Phone Number : </label>
                            <div class="form-group">            
                              <input class="form-control phone_no_class" name="phone_no" placeholder="Enter Phone Number" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="paragraph_title">
                          <div class="para_text">Banking Details</div>
                          <div class="row">
                            <div class="col-md-12 col-lg-12">
                              <label>Bank Account IBAN : </label>
                              <div class="form-group">            
                                <input class="form-control supplier_iban_class" name="supplier_iban" placeholder="Enter Bank Account IBAN" type="text">
                              </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                              <label>Bank Account BIC : </label>
                              <div class="form-group">            
                                <input class="form-control supplier_bic_class" name="supplier_bic" placeholder="Enter Bank Account BIC" type="text">
                                <input type="hidden" name="supplier_count" class="supplier_count_class" value="<?php echo $pcodeval.$count_invoice; ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="row" style="margin: 0px;">
                    <div class="col-lg-9">
                      <div class="paragraph_title">
                        <div class="para_text">General</div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>VAT Number : </label>
                            <div class="form-group">            
                              <input class="form-control vat_number_class" name="vat_number" placeholder="Enter VAT Number" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Currency : </label>
                            <div class="form-group">            
                              <input class="form-control currency_class" name="currency" placeholder="Enter Currency" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Default Nominal :</label>
                            <div class="form-group">
                              <select class="form-control default_nominal_supplier_class" name="default_nominal">
                                <option value="">Default Nominal</option>
                                <?php
                                $nominal_codes = DB::table('nominal_codes')->where('practice_code', Session::get('user_practice_code'))->orderBy('code','asc')->get();
                                if(($nominal_codes)) {
                                  foreach($nominal_codes as $code){
                                    echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                                  }
                                }
                                ?>
                              </select> 
                            </div>
                          </div>                        
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Account Username :</label>
                            <div class="form-group">
                              <input type="text" class="form-control ac_username_class" placeholder="Enter Username" name="ac_username">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Account Password :</label>
                            <div class="form-group">
                              <input type="text" class="form-control ac_password_class"  placeholder="Enter Password" name="ac_password">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="tab-pane fade" id="home_supplier_invoice" role="tabpanel" aria-labelledby="home-tab_supplier_invoice">
                  <div class="col-md-12 col-lg-12">
                    <label>Opening Balance : </label>
                    <div class="form-group">            
                      <input class="form-control opening_balance_class" name="opening_balance" placeholder="Enter Opening Balance" type="text" value="" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="hidden" name="supplier_id" id="supplier_id_invoice" value="">
          <input type="submit" class="common_black_button submit_module_update_invoice" value="Submit">
        </div>
    </div>
  </div>
</div>
<div class="modal fade view_purchase_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="z-index: 9999999999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
    <div class="modal-content">
        <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">View Invoice</h4>
        </div>
        <div class="modal-body modal_max_height" style="clear:both">
          <div class="col-md-12">
            <div class="col-md-12">
              <h4 style="font-weight:600">Invoice Line Details</h4>
              <table class="table">
                <thead>
                  <th style="width:10%">S.No</th>
                  <th style="width:15%">Description</th>
                  <th style="width:15%">Nominal Code</th>
                  <th style="width:15%">Net Value</th>
                  <th style="width:15%">VAT Rate</th>
                  <th style="width:15%">VAT</th>
                  <th style="width:10%">Gross</th>
                  <th style="width: 5%;"> </th>
                </thead>
                <tbody id="detail_tbody_view">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
        </div>
      @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade dropzone_global_supplier_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_supplier">
                 <div class="image_div_attachments_supplier">
                    <form action="<?php echo URL::to('user/supplier_upload_global_files'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload200" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input type="hidden" name="hidden_global_inv_id" id="hidden_global_inv_id" value="">
                    @csrf
</form>
                 </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade" id="transaction_list_modal_invoice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <a href="javascript:" name="export_transaction_list" class="common_black_button export_transaction_list" id="export_transaction_list" style="float: right;margin-right: 10px;margin-top: 10px;">Export as CSV</a>
        <h4 class="modal-title">Transaction List</h4>
      </div>
      <div class="modal-body" id="supplier_info_tbody_invoice" style="clear:both; overflow: unset;">
      </div>
      <div class="modal-footer" style="clear:both">
      </div>
    </div>
  </div>
</div>
<div class="modal fade reconcile_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Bank Reconciliation</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div style="float: left; margin-right: 10px; margin-left: 15px; padding-top: 9px;">Select Account:</div>
          <div class="col-lg-5" style="margin-bottom: 20px;">
            <select class="form-control select_reconcile_bank">
              <?php
              $banks = DB::table('financial_banks')->where('practice_code',Session::get('user_practice_code'))->get();
              $output_bank='<option value="">Select Bank</option>';
              if(($banks)){
                foreach($banks as $bank){
                  $output_bank.='<option value="'.base64_encode($bank->id).'">'.$bank->bank_name.' ('.$bank->account_name.') '.$bank->account_number.'</option>';
                }
              }
              else{
                $output_bank='<option value="">Select Bank</option>';                
              }
              echo $output_bank;
              ?>
            </select>
            <label class="error error_select_bank" style="display: none;">Please Select Bank</label>
          </div>    
          <div style="float: left; ">
            <a href="javascript:" class="common_black_button reconcile_load" style="float: right;width: 100%">Load</a>
          </div>
          <div style="float: left;width:40% ">
            <label style="margin-left:20px;font-size:18px">Unreconciled Bank Tranfers: <spam id="unreconciled_count">0</spam></label><br/>
            <label style="margin-left:20px;font-size:18px;color:#f00" id="unreconciled_alert_text">There are <spam id="unreconciled_count_text"></spam> Unreconciled Bank Transfers that must be reconciled before you Clear Transactions or Reconcile the Account.</label>
          </div>
        </div>
        <div class="row table_bank_details">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table own_table_white ">
                <thead>
                  <tr>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Description</th>
                    <th>Nominal Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td_bank_name"></td>
                    <td class="tb_ac_name"></td>
                    <td class="td_ac_number"></td>
                    <td class="td_ac_description"></td>
                    <td class="td_nominal_code"></td>
                  </tr>
                </tbody>
              </table>
            </div>            
          </div>
        </div>
        <div class="row transactions_section" style="display: none;">
          <div class="col-lg-12">
            <h4>TRANSACTIONS GRID:</h4>
            <input type="hidden" class="receipt_id" value="" readonly name="">
              <input type="hidden" class="payment_id" value="" readonly name="">
              <input type="hidden" value="" class="balance_tran_class" >
          </div>
        </div>
        <div class="row transactions_section modal_max_height_350" style="display: none;">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table own_table_white" id="transaction_table" style="width:100%">
                <thead>
                  <tr>
                    <th style="display:none"></th>
                    <th style="width: 10% !important;">Date</th>
                    <th style="width: 20% !important;">Description</th>
                    <th style="width: 10% !important; ">Source</th>
                    <th style="text-align: right; width: 10% !important;">Value</th>
                    <th style="width: 10% !important; text-align: right;">Outstanding Value</th>
                    <th style="width: 20% !important; text-align: right;">Journal ID</th>
                    <th style="width: 20% !important;">Clearance Date</th>
                  </tr>
                </thead>
                <tbody class="tbody_transaction">
                </tbody>
              </table>
            </div>
          </div>          
        </div>
        <div class="row transactions_section" style="display: none;">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table own_table_white" id="transaction_table" style="width:100%">
                  <tr>
                    <td colspan="5" style="width:70%;text-align: right">
                      Total: <span class="class_total_outstanding"></span>
                        <input readonly type="hidden" value="" class="input_total_outstanding" >
                    </td>
                    <td colspan="2">
                       <input type="button" class="common_black_button accept_all_button" style="float: right; width: 200px;" value="Accept All as Cleared">
                    </td>
                  </tr>
              </table>
            </div>
          </div>          
        </div>
        <div class="row reconcilation_section" style="display: none;"> 
          <div class="col-lg-6" style="margin-top: -25px;">
            <h4>RECONCILATION SECTION:</h4>
            <div class="table-responsive">
              <table class="table own_table_white">                
                <tbody class="tbody_reconcilation">
                </tbody>
              </table>
            </div>
            <div class="row">              
              <div class="col-lg-2">
                <h4 style="margin-top: 7px;">Get Report: </h4>
              </div>
              <div class="col-lg-4">
                <a href="javascript:" style="float: right; width: 100%" class="common_black_button reconciliation_pdf">PDF</a>
              </div>
              <div class="col-lg-4">
                <a href="javascript:" style="float: right; width: 100%" class="common_black_button reconciliation_csv">CSV</a>
              </div>
              <div class="col-md-12" style="margin-top: 22px;">
                Note: CSV Rendering will complete in few seconds whereas, PDF Rendering may take upto 5 mins based on the data volume.
              </div>
            </div>
          </div>
          <div class="col-lg-6 reconcilation_report">
            <table class="table" id="reconcile_report">
              <thead>
                <th>Rec Date</th>
                <th>Stmnt Bal</th>
                <th>Stmnt Date</th>
                <th>OS Items</th>
                <th>Rec File</th>
              </thead>
              <tbody id="reconcile_tbody">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
      </div>
    </div>
  </div>
</div>
<select class="general_nominal_hidden_for_add_ajax" style="display: none;" required name="general_nominal[]">
  <option value="">Select Nominal Code</option>
  <?php
  if(($nominal_codes)) {
    foreach($nominal_codes as $code){
      echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
    }
  }
  ?>
</select>
<div class="modal fade general_journal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999999 !important; background: rgba(0,0,0,0.3) ">
  <div class="modal-dialog" role="document" style="width:70%;">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/financials_general_journal_save')?>" id="general_journal_id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">General Journal</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <div class="row">
            <div class="col-md-2">
              <input type="text" name="general_journal_date" required class="form-control general_journal_date" placeholder="Select Date" value="">
              <label class="error error-general_journal_date"></label>
            </div>
            <div class="col-lg-12">
              <table class="table excel_sheet_table" style="margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th style="width: 300px;">Nominal Code</th>
                    <th>Journal Desription</th>
                    <th style="width: 200px;">Debit Value</th>
                    <th style="width: 200px;">Credit Value</th>
                    <th style="width: 100px;">Action</th>
                  </tr>
                </thead>
                <tbody id="general_journal_tboday">
                  <tr>
                    <td>
                      <select class="general_nominal" required name="general_nominal[]">
                        <option value="">Select Nominal Code</option>
                        <?php
                        if(($nominal_codes)) {
                          foreach($nominal_codes as $code){
                            echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <label class="error error-general-nominal" ></label>
                    </td>
                    <td>
                      <input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]">
                      <label class="error error-general_journal_desription" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_debit" required value="0.00" placeholder="Enter Debit Value" name="general_debit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_debit" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_credit" required value="0.00" placeholder="Enter Credit Value" name="general_credit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_credit" ></label>
                    </td>
                    <td>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <select class="general_nominal" name="general_nominal[]">
                        <option value="">Select Nominal Code</option>
                        <?php
                        if(($nominal_codes)) {
                          foreach($nominal_codes as $code){
                            echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <label class="error error-general-nominal" ></label>
                    </td>
                    <td>
                      <input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]">
                      <label class="error error-general_journal_desription" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_debit" value="0.00" required placeholder="Enter Debit Value" name="general_debit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_debit" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_credit" value="0.00" required placeholder="Enter Credit Value" name="general_credit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_credit" ></label>
                    </td>
                    <td style="text-align: center;">
                      <a href="javascript:" class="fa fa-plus add_general" title="Add" style="margin-top: 10px;"></a>
                      <a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; margin-left: 10px; display: none;"></a>
                    </td>
                  </tr>
                </tbody>
                <tr>
                    <td style="width: 300px; border-top:1px solid #a3a3a3;"></td>
                    <td align="right" style="border-top:1px solid #a3a3a3;">Total:</td>
                    <td style="width: 200px; border-top:1px solid #a3a3a3;">
                      <input type="text" class="general_debit_total" name="" readonly style="text-align: right">
                    </td>
                    <td style="width: 200px; border-top:1px solid #a3a3a3;">
                      <input type="text" class="general_credit_total" name="" readonly style="text-align: right">
                    </td>
                    <td style="width: 100px; border-top:1px solid #a3a3a3;"></td>
                  </tr>
              </table>
            </div>
          </div>
      </div>
      <div class="modal-footer" style="clear:both; border-top:0px;">        
        <input type="button" value="Save" name="" class="common_black_button save_general_journal_button">        
      </div>
      @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade notify_bank_account_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Client Statements – Bank Account Notification</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <iframe id="notify_bank_iframe" src="<?php echo URL::to('user/notify_bank_account_clients'); ?>" style="width:100%;border: 0px;height:635px;"></iframe>
      </div>
      <div class="modal-footer" style="clear:both; border-top:0px;">        
        <input type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close" value="Close">  
      </div>
    </div>
  </div>
</div>
<div class="modal fade my_day_calendar_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:40%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">My Day - Staff Calendar</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <!-- <div id='calendar-container' style="width:100%;float:left">
            <div id='calendar_myday'></div>
          </div> -->
      </div>
      <div class="modal-footer" style="clear:both; border-top:0px;">        
      </div>
    </div>
  </div>
</div>

<?php 
$currentData = date('Y-m-d');
?>
<script>
<?php
if(isset($_GET['active_clients_clear'])) {
  ?>
  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:22px;font-weight:600;color:green">Quick Time Added Successfully.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">As you have allocated time to these active clients, would you like your active client list cleared to avoid creating duplicate entries later?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" class="common_black_button yes_clear_quick_time">Yes</a><a href="javascript:" class="common_black_button no_clear_quick_time">No</a></p>',fixed:true,width:"800px"});
  history.pushState({}, null, "<?php echo URL::to('user/time_track'); ?>");
  <?php
}
?>
// $('.my_day_calendar_modal').on('shown.bs.modal', function() {
//     $(document).off('focusin.modal');
// });
function showMyDayCalendar(current_date) {
  var calendarE3 = document.getElementById('calendar_myday');
  var calendar_myday = new FullCalendar.Calendar(calendarE3, {
      initialDate: current_date,
      initialView: 'timeGridDay',
      customButtons: {
        myCustomButton: {
          text: 'Calendar Full View',
          click: function() {
            $(".start_load").show();
            window.location.replace("<?php echo URL::to('user/staff_calendar'); ?>");
          }
        }
      },
      headerToolbar: {
        left: 'prev,next today myCustomButton',
        center: '',
        right: 'title'
      },
      height: "700px",
      selectable: true,
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: "<?php echo URL::to('user/fetchevents'); ?>", // Fetch all events
      allDay: false,
      disableResizing: true,
      select: function(arg) {
        var selectedDate = change_date_format(arg.start);
        var selectedTime = moment(arg.startStr).format('HH:mm');
        var currentView = 'dayGridMonth';
        Swal.fire({
          title: 'Add New Event',
          width: '500px',
          height: '700px',
          customClass: {
            container: 'swalmydayerrorcontainer',
          },
          showCancelButton: true,
          confirmButtonText: 'Create',
          html:
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_myday" class="form-control" placeholder="Event name" required>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_myday" class="form-control" placeholder="Client name" required><input type="hidden" id="eventclientid_myday" class="form-control">' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_myday" class="form-control" placeholder="Event description" required></textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_myday" class="form-control" placeholder="Event Start Date" value="'+selectedDate+'"></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_myday" class="form-control" placeholder="Event End Date" value="'+selectedDate+'"></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_myday" class="form-control" placeholder="Event Start Time" value="'+selectedTime+'"></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_myday" class="form-control" placeholder="Event End Time" value="'+selectedTime+'"></div></div></div></div>',
          didOpen: () => {
                $("#eventstartdate_myday").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                })
                $("#eventenddate_myday").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                   minDate: moment(arg.start).hour(00).minute(00),
                })
                $("#eventstarttime_myday").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventendtime_myday").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventstartdate_myday").on("dp.change", function (e) {
                    $('#eventenddate_myday').data("DateTimePicker").minDate(e.date);
                });
                $("#eventstarttime_myday").on("dp.change", function (e) {
                    $('#eventendtime_myday').data("DateTimePicker").minDate(e.date);
                    $('#eventendtime_myday').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
                });
                $("#eventclient_myday").autocomplete({
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
                    $("#eventclientid_myday").val(ui.item.id);
                  }
                });
          },
          focusConfirm: false,
          preConfirm: () => {
            return [
                document.getElementById('eventtitle_myday').value,
                document.getElementById('eventdescription_myday').value,
                document.getElementById('eventstartdate_myday').value,
                document.getElementById('eventenddate_myday').value,
                document.getElementById('eventstarttime_myday').value,
                document.getElementById('eventendtime_myday').value,
                document.getElementById('eventclient_myday').value,
                document.getElementById('eventclientid_myday').value
            ]
          }
        }).then((result) => {
          if (result.isConfirmed) {
            var title = result.value[0].trim();
            var description = result.value[1].trim();
            var start_date = result.value[2].trim();
            var end_date = result.value[3].trim();
            var start_time = result.value[4].trim();
            var end_time = result.value[5].trim();
            var client_name = result.value[6].trim();
            var client_id = result.value[7].trim();
            if(title != '' && description != '' && client_id != ''){
              $.ajax({
                url: "<?php echo URL::to('user/calendarEvents'); ?>",
                type: 'post',
                data: {type: 'addEvent',title: title,description: description,start_date: start_date,end_date: end_date,start_time:start_time,end_time:end_time,client_id:client_id},
                dataType: 'json',
                success: function(response){
                  if(response.status == 1){
                    showMyDayCalendar(arg.startStr);
                    Swal.fire(response.message,'','success');
                  }else{
                    Swal.fire(response.message,'','error');
                  }
                }
              });
            }
            else{
              Swal.fire('Please Enter all the Mandatory Fields','','error');
            }
          }
        })
        calendar_myday.unselect()
      },
      eventDrop: function (event, delta) {
        var eventid = event.event.extendedProps.eventid;
        var newStart_date = event.event.startStr;
        var newEnd_date = event.event.startStr;
        if(event.event.endStr){
          newEnd_date = event.event.endStr;
        }
        var droppedEvent = event.event;
        var newStartDate = droppedEvent.start;
        var start_time = getTimeFormat(newStartDate);
        var newEndDate = droppedEvent.start;
        if(droppedEvent.end) {
          newEndDate = droppedEvent.end;
        }
        var end_time = getTimeFormat(newEndDate);
        var currentView = 'dayGridMonth';
        $.ajax({
          url: "<?php echo URL::to('user/calendarEvents'); ?>",
          type: 'post',
          data: {type: 'moveEvent',eventid: eventid,start_date: newStart_date, end_date: newEnd_date, start_time:start_time, end_time:end_time},
          dataType: 'json',
          async: false,
          success: function(response){
          }
        }); 
      },
      eventClick: function(arg) { 
        var eventid = arg.event._def.extendedProps.eventid;
        var description = arg.event._def.extendedProps.description;
        var title = arg.event._def.extendedProps.title_name;
        var client_name = arg.event._def.extendedProps.client_name;
        var client_id = arg.event._def.extendedProps.client_id;
        var start_date = change_date_format(arg.event.start);
        var start_time = getTimeFormat(arg.event.start);
        var end_date = change_date_format(arg.event.start);
        var end_time = getTimeFormat(arg.event.start);
        if(arg.event.end){
          var end_date = change_date_format(arg.event.end);
          var end_time = getTimeFormat(arg.event.end);
        }
        var currentView = 'dayGridMonth';
        // Alert box to edit and delete event
        Swal.fire({
          title: 'Edit Event',
          width: '500px',
          height: '700px',
          customClass: {
            container: 'swalmydayerrorcontainer',
          },
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Update',
          denyButtonText: 'Delete',
          html:
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_myday" class="form-control" placeholder="Event name" value="'+title+'" required>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_myday" class="form-control" placeholder="Client name" value="'+client_name+'" required><input type="hidden" id="eventclientid_myday" class="form-control" value="'+client_id+'">' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_myday" class="form-control" placeholder="Event description" required>'+description+'</textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_myday" class="form-control" placeholder="Event Start Date" value="'+start_date+'"></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_myday" class="form-control" placeholder="Event End Date" value="'+end_date+'"></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_myday" class="form-control" placeholder="Event Start Time" value="'+start_time+'"></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_myday" class="form-control" placeholder="Event End Time" value="'+end_time+'"></div></div></div></div>',
          didOpen: () => {
                $("#eventstartdate_myday").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                })
                $("#eventenddate_myday").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                   minDate: arg.startStr,
                })
                $("#eventstarttime_myday").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventendtime_myday").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventstartdate_myday").on("dp.change", function (e) {
                    $('#eventenddate_myday').data("DateTimePicker").minDate(e.date);
                });
                $("#eventstarttime_myday").on("dp.change", function (e) {
                    $('#eventendtime_myday').data("DateTimePicker").minDate(e.date);
                    $('#eventendtime_myday').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
                });
                $("#eventclient_myday").autocomplete({
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
                    $("#eventclientid_myday").val(ui.item.id);
                  }
                });
          },
          focusConfirm: false,
          preConfirm: () => {
            return [
                document.getElementById('eventtitle_myday').value,
                document.getElementById('eventdescription_myday').value,
                document.getElementById('eventstartdate_myday').value,
                document.getElementById('eventenddate_myday').value,
                document.getElementById('eventstarttime_myday').value,
                document.getElementById('eventendtime_myday').value,
                document.getElementById('eventclient_myday').value,
                document.getElementById('eventclientid_myday').value
            ]
          }
        }).then((result) => {
          if (result.isConfirmed) {
            var newtitle = result.value[0].trim();
            var newdescription = result.value[1].trim();
            var newstart_date = result.value[2].trim();
            var newend_date = result.value[3].trim();
            var newstart_time = result.value[4].trim();
            var newend_time = result.value[5].trim();
            var client_name = result.value[6].trim();
            var client_id = result.value[7].trim();
            if(newtitle != '' && newdescription != '' && client_id != ''){
              $.ajax({
                url: "<?php echo URL::to('user/calendarEvents'); ?>",
                type: 'post',
                data: {type: 'editEvent',eventid: eventid,title: newtitle, description: newdescription,start_date: newstart_date,end_date: newend_date,start_time:newstart_time,end_time:newend_time,client_id:client_id},
                dataType: 'json',
                async: false,
                success: function(response){
                  if(response.status == 1){
                    showMyDayCalendar(arg.event.startStr);
                    Swal.fire(response.message,'','success');
                  }else{
                    Swal.fire(response.message,'','error');
                  }
                }
              }); 
            }
            else{
              Swal.fire('Please Enter all the Mandatory Fields','','error');
            }
          } else if (result.isDenied) {
            $.ajax({
              url: "<?php echo URL::to('user/calendarEvents'); ?>",
              type: 'post',
              data: {type: 'deleteEvent',eventid: eventid},
              dataType: 'json',
              async: false,
              success: function(response){
                if(response.status == 1){
                  arg.event.remove();
                  Swal.fire(response.message, '', 'success');
                }else{
                  Swal.fire(response.message, '', 'error');
                } 
              }
            }); 
          }
        })
      }
  });
  calendar_myday.render();
}
function validateNumericInput(inputElement) {
    // Remove any non-numeric characters using a regular expression
    inputElement.value = inputElement.value.replace(/[^0-9]/g, '');
}
function change_date_format(date)
{
    var monthNames=["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
    var todayDate = new Date(date);
    var date = todayDate.getDate().toString();
    var month = todayDate.getMonth().toString(); 
    var year = todayDate.getFullYear().toString(); 
    var formattedMonth = (todayDate.getMonth() < 10) ? "0" + month : month;
    var formattedDay = (todayDate.getDate() < 10) ? "0" + date : date;
    var result  = formattedDay + '-' + monthNames[todayDate.getMonth()].substr(0,3) + '-' + year.substr(2);
    return result;
}
function getTimeFormat(date)
{
    var todayDate = new Date(date);
    var hour = todayDate.getHours().toString();
    var min = todayDate.getMinutes().toString();
    var formattedHour = (todayDate.getHours() < 10) ? "0" + hour : hour;
    var formattedMinute = (todayDate.getMinutes() < 10) ? "0" + min : min;
    var result  = formattedHour + ':' + formattedMinute;
    return result;
}
document.addEventListener('DOMContentLoaded', function() {
  showMyDayCalendar('<?php echo $currentData; ?>');
});
$.ajaxQueue = [];
var que = $.ajaxQueue;
$.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function(){
        if (this.queue) {
            que.push(this);
        }
        else {
            return true;
        }
        if (que.length > 1) {
            return false;
        }
    },
    complete: function(){
        que.shift();
        var newReq = que[0];
        if (newReq) {
            // setup object creation should be automated 
            // and include all properties in queued AJAX request
            // this version is just a demonstration. 
            var setup = {
                url: newReq.url,
                success: newReq.success
            };
            $.ajax(setup);
        }
        //$("body").attr("class", "body_bg");
    },
    error: function(xhr, textStatus, errorThrown){
        //$("body").attr("class", "body_bg");
    },
});
$(window).load(function(){
  setTimeout(function(){ $(".start_load").hide(); },1000);
});
 CKEDITOR.replace('editor999',
              {
                height: '150px',
                enterMode: CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                  autoParagraph: false,
                  entities: false,
              });
 CKEDITOR.replace('editor_9999',
              {
                height: '150px',
                enterMode: CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                  autoParagraph: false,
                  entities: false,
              });
  CKEDITOR.replace('editor_distribute',
              {
                height: '150px',
                enterMode: CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                  autoParagraph: false,
                  entities: false,
              });
           initSample(); 
          $("#update_admin_form" ).validate({
              rules: {
                  admin_username : {required:true,},
                  newadmin_password : {required: true,},
                  confirmadmin_password : {required: true, equalTo: newadmin_password},    
              },
              messages: {
                  admin_username : "Username is required",
                  newadmin_password : "New Password is required",
                  confirmadmin_password : {
                      required: "Confirm Password is required",
                      equalTo : "Does not Match the password",
                  },
              },
          });
          $("#update_user_form" ).validate({
              rules: {
                  user_username : {required:true,},
                  newuser_password : {required: true,},
                  confirmuser_password : {required: true, equalTo: newuser_password},    
              },
              messages: {
                  user_username : "Username is required",
                  newuser_password : "New Password is required",
                  confirmuser_password : {
                      required: "Confirm Password is required",
                      equalTo : "Does not Match the password",
                  },
              },
          });
function detectPopupBlocker() {
  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");
  if (!myTest) {
    return 1;
  } else {
    myTest.close();
    return 0;
  }
}
function SaveToDisk(fileURL, fileName) {
  var idval = detectPopupBlocker();
  if(idval == 1)
  {
    alert("A popup blocker was detected. Please Allow the popups to download the file.");
  }
  else{
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
  $("body").removeClass("loading");
}
</script>
<!-- <?php 
if(($segment1 == "opening_balance_manager" || $segment1 == "client_opening_balance_manager" || $segment1 == "import_opening_balance_manager"))
{
  ?>
  <div class="footer_row">
    <div class="col-md-4">
      <spam style="float: left;margin-left: 45px;font-size: 17px;font-weight: 700;margin-top:3px">Global Opening Balanace Date: </spam>
      <input type="text" name="global_opening_date" class="input global_opening_date" value="" placeholder="DD-MMM-YYYY" style="float: left;margin-left: 20px;height: 31px;outline: none;">
      <input type="button" class="common_black_button set_global_opening_bal_date_now" name="set_global_opening_bal_date_now" value="Set Now">
    </div>
    <div class="col-md-4">
      © Copyright <?php echo date('Y'); ?> All Rights Reserved Bubble
    </div>
    <div class="col-md-4">
       
    </div>
  </div>
  <?php
}
else{
  ?>
  <div class="footer_row">
    © Copyright <?php echo date('Y'); ?> All Rights Reserved Bubble
  </div>
  <?php
}
?> -->
<script>
$(document).ready(function() {
    $(".page_title h4:first").addClass('menu-logo');
    $(".modal-title").addClass('menu-logo');
    $("body").removeClass("loading");
    $(".global_opening_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
       widgetPositioning: { horizontal: 'left', vertical: 'top'}
    });
    $(".opening_financial_date").datetimepicker({     
      format: 'L',
      format: 'DD-MMM-YYYY',
    });
    $('#client_financial').DataTable({
      fixedHeader: {
        header: true,
        headerOffset: 500,
      },
      autoWidth: false,
      scrollX: false,
      searching: false,
      paging: false,
      info: false,
      ordering: false,
    });
    $('#detail_analysis').DataTable({
      fixedHeader: {
        header: true,
        headerOffset: 500,
      },
      autoWidth: false,
      scrollX: false,
      searching: false,
      paging: false,
      info: false,
      ordering: false,
    });
    $("#payroll_access_expand").DataTable({        
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        //order: [[0, 'asc']],
    });
    $('#notify_bank_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: [],
        });
    $('#active_client_quick_time_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: [],
          aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1,-2,-3] /* 1st one, start by the right */
            }],
        });
    $('.debit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });   
     $('.credit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });   
    $(".client_search_class_quick_time").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/timesystem_client_search'); ?>",
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
          $("#client_search_quick_time").val(ui.item.id);          
          $.ajax({
            url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              if(ui.item.active_status == 2)
              {
                var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
                if(r)
                {
                  $(".task_details_quick_time").html(result);
                  $(".task-choose_quick_time:first-child").text("Select Tasks");
                  $("#idtask_quick_time").val('');
                  $(".tasks_group_quick_time").show();
                }
                else{
                  $(".client_search_class").val('');
                  $(".task_details").html('');
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#idtask").val('');
                  $(".tasks_group").hide();
                }
              }
              else{
                $(".task_details_quick_time").html(result);
                $(".task-choose_quick_time:first-child").text("Select Tasks")
                $("#idtask_quick_time").val('');
                $(".tasks_group_quick_time").show();
              }
            }
          })
        }
  });
})
$(".active_list_dropdown_quick_time").on("change",function(){
  var client_id=$(this).val();
  var active_status = $(".active_list_dropdown_quick_time option:selected").data('activestatus');
  $.ajax({
    url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
    data:{value:client_id},
    success: function(result){
      if(active_status == 2)
      {
        var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
        if(r)
        {
          $(".task_details_quick_time").html(result);
          $(".task-choose_quick_time:first-child").text("Select Tasks");
          $("#idtask_quick_time").val('');
          $(".tasks_group_quick_time").show();
        }
        else{
          $(".client_search_class_quick_time").val('');
          $(".task_details_quick_time").html('');
          $(".task-choose_quick_time:first-child").text("Select Tasks");
          $("#idtask_quick_time").val('');
          $(".tasks_group_quick_time").hide();
        }
      }
      else{
        $(".task_details_quick_time").html(result);
        $(".task-choose_quick_time:first-child").text("Select Tasks")
        $("#idtask_quick_time").val('');
        $(".tasks_group_quick_time").show();
      }
    }
  })
});
// $(document).ajaxComplete(function (event, request, settings) {
//     var str = request.responseText.toLocaleLowerCase();
//     console.log(str);
//     if (str.includes("user login") === true) {
//        //window.location.replace("<?php echo URL::to('/'); ?>");
//     }
// });
$(window).change(function(e) {
  if($(e.target).hasClass('select_user_author')) {
    var value = $(e.target).val();
    if(value != "") {
      $("#author_email-error").hide();  
      $(".author_email").removeClass("error");  
    }
  }
  if($(e.target).hasClass('select_user_AM')){
    var user_id=$(e.target).val();
    $("#tbl_active_manager_list").dataTable().fnDestroy();
    if(user_id!=''){
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/get_active_client_list'); ?>",
        type:"post",
        data:{'user_id':user_id},
        success:function(result)
        {
          $("#active_manager_tbody").html(result);
          $('#tbl_active_manager_list').DataTable({        
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
    }
  }
})
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('.system_img').attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
$( "#logging_form" ).validate({
rules:
{
  password_logging : {required: true},
  cpassword_logging : {required: true,equalTo: "#password_logging"},
},
messages: {
  password_logging : { required : "Please Enter Password", },
  cpassword_logging : { required : "Please Enter Confirm Password", equalTo : "Confirm Password should match the above field." },
}
// submitHandler: function(form) {
//      if (grecaptcha.getResponse()) {
//          form.submit();
//      } else {
//          $(".error_captcha").html("Please confirm google captcha to proceed");
//          return false;
//      }
//  },
});
function accept_reconciliation(round,count)
{
  $("#transaction_table").dataTable().fnDestroy();
  $("#reconcile_report").dataTable().fnDestroy();
  var bank_id = $(".select_reconcile_bank").val();
  $.ajax({
    url:"<?php echo URL::to('user/create_journal_reconciliation'); ?>",
    type:"post",
    data:{bank_id:bank_id},
    success:function(result){
        if(parseInt(round) == parseInt(count))
        {
          $(".payment_clear").removeClass("process_journal");
          $(".receipt_clear").removeClass("process_journal");
          var bank_id = atob($(".select_reconcile_bank").val());
          var stmt_bal = $(".input_balance_bank").val();
          var stmt_date = $(".date_balance_bank").val();
          var total_os_items = $(".refresh_input_outstanding").val();
          var cb = $(".class_close_balance").html();
          var cd = $(".class_difference").html();
          var receipt_id = $(".receipt_id").val();
          var payment_id = $(".payment_id").val();
          $(".first_rec_div").hide();
          $(".second_rec_div").show();
          $.ajax({
            url:"<?php echo URL::to('user/generate_reconcile_csv_after_reconciliation'); ?>",
            type:"post",
            dataType:"json",
            data:{bank_id:bank_id,stmt_bal:stmt_bal,stmt_date:stmt_date,total_os_items:total_os_items,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
            success:function(result){
                $(".tbody_transaction").html(result['output_payment']);
                $("#reconcile_tbody").append(result['output']);
                $('#transaction_table').DataTable({        
                    // autoWidth: true,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                    order: [[1, 'asc'], [3, 'desc']]
                });
                $('#reconcile_report').DataTable({        
                    // autoWidth: true,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                });
                $("#bank_rec_tbody").find(".bank_"+bank_id).find("td").eq(7).html(result['rec_date']);
                $("#bank_rec_tbody").find(".bank_"+bank_id).find("td").eq(8).html(result['stmt_date']);
                $("#bank_rec_tbody").find(".bank_"+bank_id).find("td").eq(9).html(result['stmt_bal']);
                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">The Bank Reconciliation process has Finished and the Journals have been posted.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button ok_proceed" value="OK"></p>',fixed:true,width:"800px"});
                $("body").removeClass("loading_reconcilation");
                //$("#apply_first").html('0');
            }
          })
        }
        else{
          var next_round = parseInt(round) + 1;
          $("#apply_rec_first").html(next_round);
          accept_reconciliation(next_round,count);
        }
    }
  })
}
// function notify_of_bank_accounts(page) {
//   var prevpage = parseInt(page) - 1;
//   var offset = parseInt(prevpage) * 100;
//   var limit = parseInt(page) * 100;
//   var clientids = '';
//   for(var i=offset; i<limit; i++) {
//     var clientid = $(".select_notify_bank_client:checked").eq(i).attr("data-element");
//     if(typeof clientid !== 'undefined') {
//       if(clientids == '') {
//         clientids = clientid;
//       }
//       else {
//         clientids = clientids+','+clientid;
//       }
//     }
//   }
//   if(clientids != '') {
//     setTimeout(function() {
//       $.ajax({
//         url:"<?php echo URL::to('user/send_notify_bank_account_emails'); ?>",
//         type:"post",
//         data:{clientids:clientids},
//         success:function(result) {
//           $("#notify_first").html(limit);
//           var nextpage = parseInt(page) + 1;
//           notify_of_bank_accounts(nextpage);
//         }
//       })
//     },1000);
//   }
//   else{
//     $("body").removeClass("loading_notify");
//     $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Emails Sent Suucessfully.</p>',fixed:true,width:"800px"});
//   }
// }
$(document).ready(function() {
  $('.dropdown-toggle').click(function(e) {
    $(".myday_calendar_link").removeClass("active");
    $("#day-view-iframe-container").hide();
    $(".swal2-container").hide();
  });
});
$(window).click(function(e) {
  <?php
  if($page_segment == 'staff_calendar' || $page_segment == 'dashboard') { ?>
    if($(e.target).hasClass('myday_calendar_link')) {
      Swal.fire({
        customClass: {
            container: 'swalerrorcontainer',
        },
        icon: 'error',
        title: 'Sorry',
        text: "You cannot accesss MyDay Calendar when you are in Staff Calendar and Dashboard User Profile Page."
      })
    }
  <?php } else { ?>
    if($(e.target).hasClass('myday_calendar_link')) {
      $("#day-view-iframe-container").show();
      $(".myday_calendar_link").addClass("active");
      showMyDayCalendar('<?php echo $currentData; ?>');
    }
    else if($(e.target).parents("#day-view-iframe-container").length > 0) {
      $(".myday_calendar_link").addClass("active");
      $("#day-view-iframe-container").show();
    }
    else if($(e.target).parents("#swal2-html-container").length > 0) {
      $(".myday_calendar_link").addClass("active");
      $("#day-view-iframe-container").show();
    }
    else if($(e.target).parents(".swal2-actions").length > 0) {
      $(".myday_calendar_link").addClass("active");
      $("#day-view-iframe-container").show();
    }
    else if($(e.target).parents("#swal2-actions").length > 0) {
      $(".myday_calendar_link").addClass("active");
      $("#day-view-iframe-container").show();
    }
    else if($(e.target).parents(".ui-autocomplete").length > 0) {
      if($("#day-view-iframe-container").is(":visible")) {
        $(".myday_calendar_link").addClass("active");
        $("#day-view-iframe-container").show();
      }
    }
    else{
      $(".myday_calendar_link").removeClass("active");
      $("#day-view-iframe-container").hide();
      $(".swal2-container").hide();
    }
  <?php } ?>
  // if($(e.target).hasClass('notify_bank_submit')) {
  //   var clientcount = $(".select_notify_bank_client:checked").length;
  //   if(clientcount > 0) {
  //     $("body").addClass("loading_notify");
  //     $("#notify_first").html("0");
  //     $("#notify_last").html(clientcount);
  //     notify_of_bank_accounts(1);
  //   }
  //   else{
  //     alert("Please select atleast 1 client to send the Bank Account Email Notification.")
  //   }
  // }
  // if($(e.target).hasClass('hide_inactive_notify_bank_clients')){
  //   if($(e.target).is(":checked")){
  //     $(".inactive_notify_clients").hide();
  //     $(".inactive_notify_clients").find(".select_notify_bank_client").prop("checked",false);
  //   }
  //   else{
  //     $(".notify_bank_clients_tr").show();
  //   }
  // }
  // if($(e.target).hasClass('select_all_notify_bank_clients')){
  //   if($(e.target).is(":checked")){
  //     $(".select_notify_bank_client:visible").prop("checked",true);
  //   }
  //   else{
  //     $(".select_notify_bank_client").prop("checked",false);
  //   }
  // }

  if($(e.target).hasClass('twobillmenu')) {
    $.ajax({
        url:"<?php echo URL::to('user/twobill_manager_authenticate'); ?>",
        type:"post",
        success:function(result) {
          window.location.replace("<?php echo URL::to('user/two_bill_manager'); ?>");
        }
    });
  }
  if($(e.target).hasClass('vat_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the VAT Email Header Image?");
    if(r) {
      $(".vat_change_header_image").modal("show");
      Dropzone.forElement("#ImageUploadEmailVat").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('pms_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Payroll Email Header Image?");
    if(r) {
      $(".pms_change_header_image").modal("show");
      Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('paye_mrs_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Payroll Email Header Image?");
    if(r) {
      $(".paye_mrs_change_header_image").modal("show");
      Dropzone.forElement("#ImageUploadEmailPayemrs").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('notify_bank_account_btn')) {
    $(".notify_bank_account_modal").modal("show");
  }
  if($(e.target).hasClass('create_quick_time')) {
    $.ajax({
      url:"<?php echo URL::to('user/check_quick_time_availability'); ?>",
      type:"post",
      dataType:"json",
      success:function(result) {
        if(result['not_closed'] == 1){
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">  You cannot use the Quick Time feature if you have an active Quick Job.</p>',fixed:true,width:"800px"});
        }
        else{
          $('#active_client_quick_time_expand').DataTable().destroy();
          $("#mark_internal_quick_time").prop("checked",false);
          $("#mark_activeclient_quick_time").prop("checked",false);
          $(".active_client_group_quick_time").hide();
          $(".tasks_group_quick_time").hide();
          $(".internal_tasks_group_quick_time").hide();
          $(".client_group_quick_time").show();
          $(".client_search_class_quick_time").val("");
          $("#client_search_quick_time").val("");
          $(".quick_time_minutes").val("");
          $(".quick_time_comments").val("");
          $("#create_quick_time_type").val("1");
          $(".create_quick_time_model").modal("show");
          $(".quick_time_minutes_div").show();
          $(".quick_time_comments_div").show();
          $(".quick_time_button_name").val("Create a Quick Time");
          $("#active_client_quick_tbody").html(result['output']);
          $('#active_client_quick_time_expand').DataTable({
            autoWidth: false,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            order: [],
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1,-2,-3] /* 1st one, start by the right */
            }],
          });
        }
      }
    })
  }
  if($(e.target).hasClass('create_quick_time_menu')) {
    $.ajax({
      url:"<?php echo URL::to('user/check_quick_time_availability'); ?>",
      type:"post",
      dataType:"json",
      success:function(result) {
        if(result['not_closed'] == 1){
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">  You cannot use the Quick Time feature if you have an active Quick Job.</p>',fixed:true,width:"800px"});
        }
        else{
          $('#active_client_quick_time_expand').DataTable().destroy();
          <?php
          if($page_segment == "time_track") {
            ?>
            $("#mark_internal_quick_time").prop("checked",false);
            $("#mark_activeclient_quick_time").prop("checked",false);
            $(".active_client_group_quick_time").hide();
            $(".tasks_group_quick_time").hide();
            $(".internal_tasks_group_quick_time").hide();
            $(".client_group_quick_time").show();
            $(".client_search_class_quick_time").val("");
            $("#client_search_quick_time").val("");
            $(".quick_time_minutes").val("");
            $(".quick_time_comments").val("");
            $("#create_quick_time_type").val("1");
            $(".create_quick_time_model").modal("show");
            <?php
          }else {
            ?>
            $("#mark_internal_quick_time").prop("checked",false);
            $("#mark_activeclient_quick_time").prop("checked",false);
            $(".active_client_group_quick_time").hide();
            $(".tasks_group_quick_time").hide();
            $(".internal_tasks_group_quick_time").hide();
            $(".client_group_quick_time").show();
            $(".client_search_class_quick_time").val("");
            $("#client_search_quick_time").val("");
            $(".quick_time_minutes").val("");
            $(".quick_time_comments").val("");
            $("#create_quick_time_type").val("2");
            $(".create_quick_time_model").modal("show");
            <?php
          }
          ?>
          $(".quick_time_minutes_div").show();
          $(".quick_time_comments_div").show();
          $(".quick_time_button_name").val("Create a Quick Time");
          $("#active_client_quick_tbody").html(result['output']);
          $('#active_client_quick_time_expand').DataTable({
            autoWidth: false,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [-1,-2,-3],
            }],
          });
        }
      }
    })
  }
  if($(e.target).hasClass('quick_time_button_name')) {
    var minutes = $(".quick_time_minutes").val();
    var comments = $(".quick_time_comments").val();
    var type= $("#create_quick_time_type").val();
    if($(".mark_internal_quick_time").is(":checked")) {
      var taskid = $("#idtask_quick_time").val();
      if(taskid == ""){
        alert("Please select the Task");
      }
      else{
        if($("#create_quick_time_form").valid()) {
          if(type == "1") {
            $("#create_quick_time_form").submit();
          }
          else{
            var formData = $("#create_quick_time_form").serialize();
            $.ajax({
              url: "<?php echo URL::to('user/quick_time_add'); ?>",
              type: "POST",
              data: formData,
              processData: false,
              success:function(result) {
                if(result == 0) {
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red">Cannot Create a Quick Time as the total time exceeds the current time for Today.</p>',fixed:true,width:"800px"});
                }
                else{
                  $(".create_quick_time_model").modal("hide");
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Quick Time Created Successfully</p>',fixed:true,width:"800px"});
                }
              }
            });
          }
        }
      }
    }
    else if($(".mark_activeclient_quick_time").is(":checked")) {
      var minutes = $('.quick_time_active_minutes').toArray().every(function(input) {
                    return $(input).val() !== '';
                });
      var task = $('.idtask_quick_time_active').toArray().every(function(input) {
                    return $(input).val() !== '';
                });
      var countval = $('.quick_time_active_minutes').length;
      if(countval == 0) {
        alert('No Active Clients are Found to create a Quick Time.');
      }
      else if(!minutes){
        alert('Please enter the minutes for all of the Active Clients');
      }
      else if(!task){
        alert('Please select the Task for all of the Active Clients');
      }
      else{
        if($("#create_quick_time_form").valid()) {
          if(type == "1") {
            $("#create_quick_time_form").submit();
          }
          else{
            var formData = $("#create_quick_time_form").serialize();
            $.ajax({
              url: "<?php echo URL::to('user/quick_time_add'); ?>",
              type: "POST",
              data: formData,
              processData: false,
              success:function(result) {
                if(result == 0) {
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red">Cannot Create a Quick Time as the total time exceeds the current time for Today.</p>',fixed:true,width:"800px"});
                }
                else{
                  $(".create_quick_time_model").modal("hide");
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:22px;font-weight:600;color:green">Quick Time Added Successfully.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">As you have allocated time to these active clients, would you like your active client list cleared to avoid creating duplicate entries later?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" class="common_black_button yes_clear_quick_time">Yes</a><a href="javascript:" class="common_black_button no_clear_quick_time">No</a></p>',fixed:true,width:"800px"});
                }
              }
            });
          }
        }
      }
    }
    else{
      var client_id = $("#client_search_quick_time").val();
      var taskid = $("#idtask_quick_time").val();
      if(client_id == ""){
        alert("Please select the Client");
      }
      else if(taskid == ""){
        alert("Please select the Task");
      }
      else{
        if($("#create_quick_time_form").valid()) {
          if(type == "1") {
            $("#create_quick_time_form").submit();
          }
          else{
            var formData = $("#create_quick_time_form").serialize();
            $.ajax({
              url: "<?php echo URL::to('user/quick_time_add'); ?>",
              type: "POST",
              data: formData,
              processData: false,
              success:function(result) {
                if(result == 0) {
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red">Cannot Create a Quick Time as the total time exceeds the current time for Today.</p>',fixed:true,width:"800px"});
                }
                else{
                  $(".create_quick_time_model").modal("hide");
                  $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Quick Time Created Successfully</p>',fixed:true,width:"800px"});
                }
              }
            });
          }
        }
      }
    }
  }
  if($(e.target).hasClass('yes_clear_quick_time')) {
    var user_id="<?php echo Session::get('userid'); ?>";
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/remove_all_active_client_list'); ?>",
      type:"post",
      data:{'user_id':user_id},
      success:function(result)
      {
        $("#active_manager_tbody tr").remove();
        $.colorbox.close(); 
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('no_clear_quick_time')) {
    $.colorbox.close();
  }
  if($(e.target).hasClass('mark_internal_quick_time'))
  {
    if($(e.target).is(":checked"))
    {    
      var activelist_checked=$("#mark_activeclient_quick_time").is(":checked");
      if(activelist_checked){
        $(".mark_activeclient_quick_time").click();
      }
      $(".active_client_group_quick_time").hide();
      $(".internal_type_quick_time").val('0');
      $(".client_group_quick_time").hide();
      $(".internal_tasks_group_quick_time").show();
      $(".tasks_group_quick_time").hide();
      $(".client_search_class_quick_time").val('');
      $(".client_search_class_quick_time").prop("required",false);
      $("#client_search_quick_time").val('');
      $(".task_details_quick_time").html('');
      $("#idtask_quick_time").val('');
      var child_value = $(".tasks_li_internal_quick_time:first").text();
      $(".task-choose_internal_quick_time:first-child").text(child_value);
      $(".quick_time_minutes_div").show();
      $(".quick_time_comments_div").show();
      $(".quick_time_button_name").val("Create a Quick Time");
    }
    else{
      $(".internal_type_quick_time").val('1');
      $(".client_group_quick_time").show();
      $(".internal_tasks_group_quick_time").hide();
      $(".tasks_group_quick_time").hide();
      $(".client_search_class_quick_time").prop("required",true);
      $("#idtask_quick_time").val('');
      $(".quick_time_minutes_div").show();
      $(".quick_time_comments_div").show();
      $(".quick_time_button_name").val("Create a Quick Time");
    }
  }
  if($(e.target).hasClass('mark_activeclient_quick_time')){
    if($(e.target).is(":checked")){
      $(".active_client_group_quick_time").show();
      $(".quick_time_active_minutes").val('');
      $(".idtask_quick_time_active").val('');
      $(".quick_time_active_comments").val('');
      var internal_checked=$("#mark_internal_quick_time").is(":checked");
      if(internal_checked){
        $(".mark_internal_quick_time").click();
      }
      $(".client_group_quick_time").hide();
      $(".internal_type_quick_time").val('2');
      $(".internal_tasks_group_quick_time").hide();
      $(".tasks_group_quick_time").hide();
      $(".client_search_class_quick_time").val('');
      $(".client_search_class_quick_time").prop("required",false);
      $("#idtask_quick_time").val('');
      $(".quick_time_minutes_div").hide();
      $(".quick_time_comments_div").hide();
      $(".quick_time_button_name").val("Create Batch Quick Time");
    }
    else{
      $(".active_client_group_quick_time").hide();
      $(".internal_type_quick_time").val('1');
      $(".client_group_quick_time").show();
      $(".internal_tasks_group_quick_time").hide();
      $(".tasks_group_quick_time").hide();
      $(".client_search_class_quick_time").prop("required",true);
      $("#idtask_quick_time").val('');
      $(".quick_time_minutes_div").show();
      $(".quick_time_comments_div").show();
      $(".quick_time_button_name").val("Create a Quick Time");
    }
  }
  if($(e.target).hasClass('tasks_li'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_quick_time").val(taskid);
    $(".task-choose_quick_time:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('tasks_li_internal_quick_time'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_quick_time").val(taskid);
    $(".task-choose_internal_quick_time:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('export_active_clients_list')) {
    var user_id = $(".select_user_AM").val();
    if(user_id!='') {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/export_active_client_list'); ?>",
        type:"post",
        data:{'user_id':user_id},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);  
          $("body").removeClass("loading");    
        }
      })
    }
    else{
      alert("Please select the User");
    }
  }
  if($(e.target).hasClass('open_client_review'))
  {
    var client_id = $(e.target).attr("data-element");
    var src = "<?php echo URL::to('user/client_account_review_summary'); ?>?client_id="+client_id;
    $("#client_revew_iframe").attr("src", src);
    $(".open_client_review_modal").modal("show");
  }
  if($(e.target).hasClass('active_client_list_tm'))
  {
    var client_id=$(".entered_client_id").val();
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
  if($(e.target).hasClass('active_client_list_pms_quick_time'))
  {
    var client_id=$("#client_search_quick_time").val();
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
  if($(e.target).hasClass('active_client_list_tm1'))
  {
    var client_id=$(e.target).data('iden');
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
if($(e.target).hasClass('open_user_logging_modal'))
{
  var user_id = $(e.target).attr("data-element");
  var firstname = $(e.target).attr("data-firstname");
  var lastname = $(e.target).attr("data-lastname");
  var email = $(e.target).attr("data-email");
  var image = $(e.target).attr("data-image");
  $("#firstname_logging").val(firstname);
  $("#lastname_logging").val(lastname);
  $("#email_logging").val(email);
  $('.system_img').attr('src', image);
  $("#hidden_user_id").val(user_id);
  $(".user_logging_modal").modal("show");
}
if($(e.target).hasClass('system_img')){
  $('#imgupload').trigger('click');
}
if($(e.target).hasClass('manage_employer_users'))
{
  $("body").addClass("loading");
  var emp_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/manage_employer_users'); ?>",
    type:"post",
    dataType:"json",
    data:{emp_id:emp_id},
    success:function(result){
      $(".emp_no_add").val(result['emp_no']);
      $(".emp_name_add").val(result['emp_name']);
      $("#employers_users_tbody").html(result['html']);
      $("#hidden_employer_payroll_id").val(emp_id);
      $(".employer_users_modal").modal("show");
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('add_users_to_emp')){
  var email = $(".email_user_add").val();
  var password = $(".password_user_add").val();
  var emp_id = $("#hidden_employer_payroll_id").val();
  if($("#emp_users_form").valid()){
    $.ajax({
      url:"<?php echo URL::to('user/insert_employer_user'); ?>",
      type:"post",
      dataType:"json",
      data:{emp_id:emp_id,email:email,password:password},
      success:function(result){
        if(result['error'] == "1"){
          alert("The Email is already exists. Please try with another email");
        }
        else{
          var countval = $("#employers_users_tbody").find(".delete_emp_users").length;
          if(countval > 0){
            $("#employers_users_tbody").append(result['html']);
          }
          else{
            $("#employers_users_tbody").html(result['html']);
          }
          $(".email_user_add").val('');
          $(".password_user_add").val('');
          $(".emp_users_count_"+emp_id).html(result['user_count']);
        }
      }
    });
  }
}
if($(e.target).hasClass('change_user_status')){
  var status = $(e.target).attr("data-element");
  var id = $(e.target).attr("data-id");
  var r = confirm("Are you sure you want to approve this user?");
  if(r){
    $.ajax({
      url:"<?php echo URL::to('user/change_employer_user_status'); ?>",
      type:"post",
      data:{status:status,id:id},
      success:function(result){
        if(status == "1"){
          $(e.target).parents("tr").find(".change_user_status").attr("class","fa fa-minus change_user_status");
          $(e.target).parents("tr").find(".change_user_status").attr("data-element","0");
          $(e.target).parents("tr").find(".change_user_status").css("color","red");
          $(e.target).parents("tr").find(".user_status_td").html("Awaiting Approval");
        }
        else{
          $(e.target).parents("tr").find(".change_user_status").attr("class","fa fa-check");
          $(e.target).parents("tr").find(".fa-check").attr("data-element","1");
          $(e.target).parents("tr").find(".fa-check").css("color","green");
          $(e.target).parents("tr").find(".user_status_td").html("Approved");
        }
      }
    });
  }
}
if($(e.target).hasClass('delete_emp_users')){
  var emp_id = $(e.target).attr("data-element");
  var r = confirm("Are you sure you want to delete this user?");
  if(r){
    $.ajax({
      url:"<?php echo URL::to('user/delete_employer_users'); ?>",
      type:"post",
      data:{emp_id:emp_id},
      success:function(result){
        $(e.target).parents("tr").detach();
      }
    })
  }
}
if($(e.target).hasClass('add_to_employer')){
  var emp_no = $(".select_payroll_employer").val();
  $('#payroll_access_expand').DataTable().destroy();
  var emp_name = $(".select_payroll_employer").children("option:selected").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/add_to_employer_list'); ?>",
    type:"post",
    data:{emp_no:emp_no,emp_name:emp_name},
    success:function(result){
      var counttr = $("#employers_tbody").find(".manage_employer_users").length;
      $(".select_payroll_employer").children("option:selected").detach();
      if(counttr > 0){
        $("#employers_tbody").append(result);
      }
      else{
        $("#employers_tbody").html(result);
      }
      $("#payroll_access_expand").DataTable({        
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
      });
    }
  })
}
if($(e.target).hasClass('check_cro'))
{
  var cro = $(e.target).attr("data-element");
  if(cro == "N/A"){
    alert("The Cro Number for this Client is Empty. The Company Details will be displayed only based on the CRO Number");
  } else {
    $("body").addClass("loading");
    $(".company_number").val(cro);
    $(".search_company_modal").modal("show");
    $("#indicator_1").prop("checked",true);
    setTimeout( function() { 
      $(".search_company_btn").trigger("click");
    },1000);
  }
}
if($(e.target).hasClass('search_company_btn'))
{
  var checked = $(".indicator:checked").length;
  var company_number = $(".company_number").val();
  var indicator = $(".indicator:checked").val();
  if(checked < 1)
  {
    alert("Please select the Company / Business indicator to search for a Company");
  }
  else if(company_number == "")
  {
    alert("Please enter the Company Number to search for a Company");
  }
  else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/get_company_details_cro'); ?>",
      type:"post",
      data:{company_number:company_number,indicator:indicator},
      success:function(result)
      {
        $(".table_api").html(result);
        //$(".search_company_modal").modal("hide");
        $("body").removeClass("loading");
      }
    });
  }
}
if($(e.target).hasClass('send_company_info'))
{
  if (CKEDITOR.instances.editor2) CKEDITOR.instances.editor2.destroy();
  CKEDITOR.replace('editor2',
     {
      height: '150px',
     });
  var company_number = $(".company_number").val();
  var indicator_text = $('.indicator_text').val();
  var company_name = $(".company_name").val();
  var company_address = $(".company_address").val();
  var company_reg_date = $(".company_reg_date").val();
  var company_status_desc = $(".company_status_desc").val();
  var company_status_date= $(".company_status_date").val();
  var next_ar_date = $(".next_ar_date").val();
  var last_ar_date = $(".last_ar_date").val();
  var last_acc_date = $(".last_acc_date").val();
  var comp_type_desc = $(".comp_type_desc").val();
  var company_type_code = $(".company_type_code").val();
  var company_status_code = $(".company_status_code").val();
  var place_of_business = $(".place_of_business").val();
  var eircode = $(".eircode").val();
  if(company_number == "")
  {
    alert("Please enter the Company Number to search for a Company");
  }
  $.ajax({
    url:"<?php echo URL::to('user/get_client_from_cronumber'); ?>",
    type:"get",
    data:{company_number:company_number,indicator_text:indicator_text,company_name:company_name,company_address:company_address,company_reg_date:company_reg_date,company_status_desc:company_status_desc,company_status_date:company_status_date,next_ar_date:next_ar_date,last_ar_date:last_ar_date,last_acc_date:last_acc_date,comp_type_desc:comp_type_desc,company_type_code:company_type_code,company_status_code:company_status_code,place_of_business:place_of_business,eircode:eircode},
    dataType:"json",
    success:function(result){
      CKEDITOR.instances['editor2'].setData(result['html']);
      $(".subject_company").val(result['subject']);
      $("#to_user_company").val(result['to']);
      $("#hidden_client_id_search_company").val(result['client_id'])
      $(".emailcompany").modal('show');
    }
  });
}
if($(e.target).hasClass('pdf_company_info'))
{
  var company_number = $(".company_number").val();
  var indicator_text = $('.indicator_text').val();
  var company_name = $(".company_name").val();
  var company_address = $(".company_address").val();
  var company_reg_date = $(".company_reg_date").val();
  var company_status_desc = $(".company_status_desc").val();
  var company_status_date= $(".company_status_date").val();
  var next_ar_date = $(".next_ar_date").val();
  var last_ar_date = $(".last_ar_date").val();
  var last_acc_date = $(".last_acc_date").val();
  var comp_type_desc = $(".comp_type_desc").val();
  var company_type_code = $(".company_type_code").val();
  var company_status_code = $(".company_status_code").val();
  var place_of_business = $(".place_of_business").val();
  var eircode = $(".eircode").val();
  if(company_number == "")
  {
    alert("Please enter the Company Number to search for a Company");
  }
  else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/pdf_company_info'); ?>",
      type:"get",
      data:{company_number:company_number,indicator_text:indicator_text,company_name:company_name,company_address:company_address,company_reg_date:company_reg_date,company_status_desc:company_status_desc,company_status_date:company_status_date,next_ar_date:next_ar_date,last_ar_date:last_ar_date,last_acc_date:last_acc_date,comp_type_desc:comp_type_desc,company_type_code:company_type_code,company_status_code:company_status_code,place_of_business:place_of_business,eircode:eircode},
      success:function(result){
        $("#pdf_placeholder").html(result);
        $("#download_pdf_file")[0].click()
        $("body").removeClass("loading");
      }
    });
  }
}
if($(e.target).hasClass('email_company_files_btn'))
{
  for (instance in CKEDITOR.instances) 
  {
      CKEDITOR.instances['editor2'].updateElement();
  }
  if($("#email_company_form").valid())
  {
    $("body").addClass("loading");
    $('#email_company_form').ajaxForm({
        success:function(result){
          $("body").removeClass("loading");
          $(".emailcompany").modal("hide");
        }
    }).submit();
  }
}
if($(e.target).hasClass('taskmanager_rate_input')){
  e.stopPropagation();
    e.preventDefault();
  var value = $(e.target).val();
  var id = $(e.target).attr("data-element");
  $(e.target).parents(".taskmanager_rate").find("#hidden_star_rating_taskmanager").val(value);
  $(e.target).parents(".taskmanager_rate:first").find(".taskmanager_rate_input").removeClass('checked_input');
  if(value == "5"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(1).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(0).addClass("checked_input");
  }
  if(value == "4"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(1).addClass("checked_input");
  }
  if(value == "3"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
  }
  if(value == "2"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
  }
  if(value == "1"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
  }
  if(id != ""){
    $.ajax({
      url:"<?php echo URL::to('user/store_user_rating_taskmanager'); ?>",
      type:"post",
      data:{value:value,id:id},
      success:function(result){
        return false;
      }
    })
  }
}
if($(e.target).hasClass('outstanding_payment_receipt_download')){
  var id = $("#tbody_outstanding_payment_receipt_id").val();
  var type = $("#tbody_outstanding_payment_receipt_type").val();
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/outstanding_payment_receipt_download'); ?>",
        type:"post",          
        data:{id:id, type:type },
        success:function(result){
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);          
          $("body").removeClass("loading");
        }
    })
  },500);
}
if($(e.target).hasClass('outstanding_payment_receipt_delete')){
  var id = $("#tbody_outstanding_payment_receipt_id").val();
  var type = $("#tbody_outstanding_payment_receipt_type").val();
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/outstanding_payment_receipt_delete'); ?>",
        type:"post",
        dataType:"json",
        data:{id:id, type:type },
        success:function(result){
          $(".modal_outstanding_payment_receipt").modal('hide');
          $(".receipt_payment_"+id).detach();
          /*alert(result['message']);*/
          $("body").removeClass("loading");
        }
    })
  },500);
}
if($(e.target).hasClass('delete_outstading')){
  var id = $(e.target).attr("data-element");
  var type = $(e.target).attr("type");
  $.ajax({
    url:"<?php echo URL::to('user/payment_receipdt_delete_outstading'); ?>",
    type:"post",
    dataType:"json",
    data:{id:id,type:type},
    success:function(result){
      $("#outstanding_payment_receipt_message").html(result['message']);
      $("#tbody_outstanding_payment_receipt").html(result['output']);
      $("#tbody_outstanding_payment_receipt_type").val(result['type']);
      $("#tbody_outstanding_payment_receipt_id").val(result['id']);
      $(".modal_outstanding_payment_receipt_title").html(result['title']);
      $(".modal_outstanding_payment_receipt").modal('show');
    }
  })
}
if($(e.target).hasClass('receipt_viewer_class')){
  var id = $(e.target).attr("data-element");
  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_client_receipt_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:0},
      success: function(result)
      {
        $(".receipt_payment_title").html(result['page_title']);
        $("#result_payment_receipt").html(result['output']);
        $(".receipt_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);
}
if($(e.target).hasClass('payment_viewer_class')){
  var id = $(e.target).attr("data-element");
  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_client_receipt_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:1},
      success: function(result)
      {
        $(".receipt_payment_title").html(result['page_title']);
        $("#result_payment_receipt").html(result['output']);
        $(".receipt_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);
}
  if($(e.target).hasClass('payroll_settings_btn'))
  {
    $(".payroll_settings_modal").modal("show");
  }
  if($(e.target).hasClass('payroll_access_btn'))
  {
    $(".payroll_access_modal").modal("show");
  }
  if($(e.target).hasClass('load_journal_viewer_pdf'))
  {
    var journal_id = $(".journal_viewer_text_id").val();
    if(journal_id == "")
    {
      alert("Please Enter the Journal Reference id to Download the PDF");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/download_journal_viewer_by_journal_id'); ?>",
        type:"post",
        data:{journal_id:journal_id},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      })
    }
  }
  if($(e.target).hasClass('journal_id_viewer'))
  {
    $("body").addClass("loading");
    var journal_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_journal_viewer_by_journal_id'); ?>",
      type:"post",
      data:{journal_id:journal_id},
      dataType:"json",
      success:function(result)
      {
        var split_journal = journal_id.split(".");
        $("#journal_viewer_tbody").html(result['output']);
        $(".journal_viewer_debit_total").html(result['total_debit']);
        $(".journal_viewer_credit_total").html(result['total_credit']);
        $(".journal_viewer_text_id").val(split_journal[0]);
        $(".journal_viewer_modal").modal("show");
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('journal_viewer_btn'))
  {
    var journal_id = $(".journal_viewer_text_id").val();
    if(journal_id == "")
    {
      alert("Please Enter the Journal Reference id to Load");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/show_journal_viewer_by_journal_id'); ?>",
        type:"post",
        data:{journal_id:journal_id},
        dataType:"json",
        success:function(result)
        {
          $("#journal_viewer_tbody").html(result['output']);
          $(".journal_viewer_debit_total").html(result['total_debit']);
          $(".journal_viewer_credit_total").html(result['total_credit']);
          $(".journal_viewer_text_id").val(journal_id);
          $(".journal_viewer_modal").modal("show");
          $("body").removeClass("loading");
        },
        error: function(data)
        {
          $("body").removeClass("loading");
          if(data.status == "500")
          {
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Journal ID doesnt exists, please check the Journal ID and then click on the Load Button.</p>',fixed:true,width:"800px"});
          }
        }
      })
    }
  }
  if($(e.target).hasClass('journal_source_link'))
  {
    $(".journal_source_viewer_modal").modal("show");
  }
  if($(e.target).hasClass('set_global_opening_bal_date_now'))
  {
    var global_date = $(".global_opening_date").val();
    if(global_date == "")
    {
      alert("Please enter the Global Opening Date");
    }
    else{
      $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">You are about to set the Global Opening Balance Date to '+global_date+'. Are you sure you want to continue?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-element="'+global_date+'" class="common_black_button yes_set_global_date">Yes</a><a href="javascript:" class="common_black_button no_set_global_date">No</a></p>',fixed:true,width:"800px"});
    }
  }
  if($(e.target).hasClass('yes_set_global_date'))
  {
    var global_date = $(".global_opening_date").val();
    $.ajax({
      url:"<?php echo URL::to('user/set_global_opening_bal_date'); ?>",
      type:"post",
      data:{global_date:global_date},
      success: function(result)
      {
        $(".global_opening_date").val("");
        $(".opening_balance_date").val(global_date);
        $.colorbox.close();
        window.location.reload();
      }
    })
  }
  if($(e.target).hasClass('no_set_global_date'))
  {
    $(".global_opening_date").val("");
    $.colorbox.close();
  }
});
</script>
<script type="text/javascript">
$(".top_client_common_search").autocomplete({
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
    $("#client_search_hidden_top_menu").val(ui.item.id);
  }
  });
$(document).ready(function(){
  blurfunction_gj();
  $(".general_journal_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });
});
function keypressonlynumber($this)
{
  var replaced_value = $this.value.replace(/[^0-9.-]/g, '').replace(/(\..*?)\..*/g, '$1');
  $($this).val(replaced_value);
}
function blurfunction_gj(){
  $(".general_debit").blur(function(){
      var value = $(this).val();
      if((value == '') || (value == 0)){
        $(this).val('0.00');
        $(this).parents("tr").find(".general_credit").attr("readonly", false).removeClass('disabled_input');
      }  
      else{
        $(this).parents("tr").find(".general_credit").attr("readonly", true).addClass('disabled_input');
        $(this).val(parseFloat(value).toFixed(2));
      }
      var total_debit = 0.00;
      var total_credit = 0.00;
      $('.general_debit').each(function() {
        var debit_val = $(this).val();
        var floatdebit = parseFloat(debit_val);
        total_debit = parseFloat(total_debit + floatdebit);
      });
      $('.general_credit').each(function() {
        var credit_val = $(this).val();
        var floatcredit = parseFloat(credit_val);
        total_credit = parseFloat(total_credit + floatcredit);
      });
      $(".general_debit_total").val(total_debit.toFixed(2));
      $(".general_credit_total").val(total_credit.toFixed(2));
      var debitval = total_debit.toFixed(2);
      var creditval = total_credit.toFixed(2);
      if(debitval == creditval){
        $(".save_general_journal_button").show();
      } else{
        $(".save_general_journal_button").hide();
      }
  });
  $(".general_credit").blur(function(){
    var value = $(this).val();
    if((value == '') || (value == 0)){
      $(this).val('0.00');
      $(this).parents("tr").find(".general_debit").attr("readonly", false).removeClass('disabled_input');
    }  
    else{
      $(this).parents("tr").find(".general_debit").attr("readonly", true).addClass('disabled_input');
      $(this).val(parseFloat(value).toFixed(2));
    }
    var total_debit = 0.00;
    var total_credit = 0.00;
    $('.general_debit').each(function() {
      var debit_val = $(this).val();
      var floatdebit = parseFloat(debit_val);
      total_debit = parseFloat(total_debit + floatdebit);
    });
    $('.general_credit').each(function() {
      var credit_val = $(this).val();
      var floatcredit = parseFloat(credit_val);
      total_credit = parseFloat(total_credit + floatcredit);
    });
    $(".general_debit_total").val(total_debit.toFixed(2));
    $(".general_credit_total").val(total_credit.toFixed(2));
    var debitval = total_debit.toFixed(2);
    var creditval = total_credit.toFixed(2);
    if(debitval == creditval){
      $(".save_general_journal_button").show();
    } else{
      $(".save_general_journal_button").hide();
    }
  });
}
$(window).change(function(e){
  if($(e.target).hasClass('general_nominal')){
    var code = $(e.target).val();
  if(code == '712'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Debtors Control Account.');
    $(".save_general_journal_button").attr("disabled", true);
    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Creditors Control Account.');
    $(".save_general_journal_button").attr("disabled", true);
    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813A'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Client holding account Account.');
    $(".save_general_journal_button").attr("disabled", true);
    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if((code >= '771') && (code < '772')){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Bank Nominal accounts Account.');
    $(".save_general_journal_button").attr("disabled", true);
    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else{
    $(e.target).parents("tr").find(".error-general-nominal").hide();
    $(".general_nominal").attr("disabled", false);
    $(".save_general_journal_button").attr("disabled", false);
  }
}
if($(e.target).hasClass('primary_grp_add'))
{
  var value = $(e.target).val();
  if(value == "Profit & Loss")
  {
    $(".debit_group_div").show();
    $(".credit_group_div").hide();
    $(".debit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
    $(".credit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
  }
  else{
    $(".debit_group_div").show();
    $(".credit_group_div").show();
    $(".debit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
    $(".credit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
  }
}
if($(e.target).hasClass('debit_grp_add'))
{
  var primary = $(".primary_grp_add").val();
  var debit = $(".debit_grp_add").val();
  var credit = $(".credit_grp_add").val();
  if(primary == "Balance Sheet")
  {
    if(debit == credit)
    {
      alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
      $(".debit_grp_add").val("");
      return false;
    }
  }
}
if($(e.target).hasClass('credit_grp_add'))
{
  var primary = $(".primary_grp_add").val();
  var debit = $(".debit_grp_add").val();
  var credit = $(".credit_grp_add").val();
  if(primary == "Balance Sheet")
  {
    if(debit == credit)
    {
      alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
      $(".credit_grp_add").val("");
      return false;
    }
  }
}
});
$(window).click(function(e){
if($(e.target).hasClass('standard_minutes_link')) {
    $(".standard_minutes_modal").modal("show");
    $(".standard_minutes_text").val("");
}
if($(e.target).hasClass('standard_comments_link')) {
    $(".standard_comment_modal").modal("show");
    $(".standard_comment_text").val("");
}
if($(e.target).hasClass('standard_task_link')) {
    $(".standard_task_modal").modal("show");
    $(".standard_task_select").val("");
}
if($(e.target).hasClass('standard_minutes_btn')) {
    var minutes = $(".standard_minutes_text").val();
    if(minutes == "") {
        alert("Please enter the Standard Minutes.");
    }
    else{
        $(".quick_time_active_minutes").each(function() {
            var value = $(this).val();
            if(value == "") {
                $(this).val(minutes);
            }
        });
        $(".standard_minutes_modal").modal("hide");
    }
}
if($(e.target).hasClass('standard_comment_btn')) {
    var comment = $(".standard_comment_text").val();
    if(comment == "") {
        alert("Please enter the Standard Comment.");
    }
    else{
        $(".quick_time_active_comments").each(function() {
            var value = $(this).val();
            if(value == "") {
                $(this).val(comment);
            }
        });
        $(".standard_comment_modal").modal("hide");
    }
}
if($(e.target).hasClass('standard_task_btn')) {
    var task = $(".standard_task_select").val();
    if(task == "") {
        alert("Please select the Standard Task.");
    }
    else{
        $(".idtask_quick_time_active").each(function() {
            var value = $(this).val();
            if(value == "") {
                $(this).val(task);
            }
        });
        $(".standard_task_modal").modal("hide");
    }
}
if($(e.target).hasClass('add_general')){
  $(e.target).parents("table").find(".general_credit").removeClass('general_credit_last');
  var general_nominal = $(".general_nominal_hidden_for_add_ajax").html();
  $("#general_journal_tboday").append('<tr><td><select class="general_nominal" name="general_nominal[]">'+general_nominal+'</select><label class="error error-general-nominal" ></label></td><td><input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription" ></label></td><td><input type="text" style="text-align: right;" class="general_debit" value="0.00" required placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit" ></label></td><td><input type="text" style="text-align: right;" class="general_credit general_credit_last" value="0.00" required placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit" ></label></td><td style="text-align: center;"><a href="javascript:" class="fa fa-plus add_general" style="margin-top: 10px;display:none"></a><a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; " title="Delete"></a></td></tr>');
    blurfunction_gj();
}
if($(e.target).hasClass('delete_general')){
  var r = confirm('Are you sure want delete this row?');
  if(r){
    $(e.target).parents("tr").remove();
    blurfunction_gj();
  }
}
if($(e.target).hasClass('save_general_journal_button')){  
    $(".errror").show();
    var errorcount = 1;
    $(".general_journal_date").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(".error-general_journal_date").show();
        $(".error-general_journal_date").html("Please select date.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general_journal_date").html("");  
      }      
    });
    $(".general_nominal").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(this).parents("tr").find(".error-general-nominal").show();
        $(this).parents("tr").find(".error-general-nominal").html("Please select Nominal Code.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general-nominal").html("");  
      }      
    });
    $(".general_journal_desription").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(this).parents("tr").find(".error-general_journal_desription").show();
        $(this).parents("tr").find(".error-general_journal_desription").html("Please enter Journal Desription.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general_journal_desription").html("");  
      }      
    });
    if(errorcount == 1){
      var nominal_date = $(".general_journal_date").val();
      var nominal_codes = $(".general_nominal").serialize();
      var journal_desription = $(".general_journal_desription").serialize();
      var debitvalues = $(".general_debit").serialize();
      var creditvalues = $(".general_credit").serialize();
      $.ajax({
        url:"<?php echo URL::to('user/save_general_journals'); ?>",
        type:"post",
        data:{nominal_date:nominal_date,nominal_codes:nominal_codes,journal_desription:journal_desription,debitvalues:debitvalues,creditvalues:creditvalues},
        success:function(result){
          $(".general_journal_modal").modal("hide");
        }
      })
    }
}
if($(e.target).hasClass('class_general_journal')){
  $(".general_journal_modal").modal('show');
  $(".general_journal_date").val("");
  $(".general_debit_total").val("");
  $(".general_credit_total").val("");
  var general_nominal = $(".general_nominal_hidden_for_add_ajax").html();
  $("#general_journal_tboday").html('<tr><td><select class="general_nominal" required="" name="general_nominal[]">'+general_nominal+'</select><label class="error error-general-nominal"></label></td><td><input type="type" class="general_journal_desription" required="" placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription"></label></td><td><input type="text" style="text-align: right;" class="general_debit" required="" value="0.00" placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit"></label></td><td><input type="text" style="text-align: right;" class="general_credit" required="" value="0.00" placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit"></label></td><td></td></tr><tr><td><select class="general_nominal" name="general_nominal[]">'+general_nominal+'</select> <label class="error error-general-nominal"></label></td><td><input type="type" class="general_journal_desription" required="" placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription"></label></td><td><input type="text" style="text-align: right;" class="general_debit" value="0.00" required="" placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit"></label></td><td><input type="text" style="text-align: right;" class="general_credit general_credit_last" value="0.00" required="" placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit"></label></td><td style="text-align: center;"><a href="javascript:" class="fa fa-plus add_general" title="Add" style="margin-top: 10px;"></a><a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; margin-left: 10px; display: none;"></a></td></tr>');
    blurfunction_gj();
}
if($(e.target).hasClass('financial_setup'))
{
  $(".nominal_codes_modal").modal("show");
}
if($(e.target).hasClass('add_nominal'))
{
  $(".add_nominal_modal").modal("show");
  $(".add_nominal_modal").find(".modal-title").html("Add Nominal");
  $(".add_nominal_btn").val("Add Nominal");
  $(".nominal_code_add").prop("disabled",false);
  $(".nominal_code_add").val('');
  $(".description_add").val('');
  $(".primary_grp_add").val('');
  $(".debit_grp_add").val('');
  $(".credit_grp_add").val('');
  $(".debit_group_div").hide();
  $(".credit_group_div").hide();
}
if($(e.target).hasClass('add_nominal_btn'))
{
  if($("#add_nominal_form").valid())
  {
    var code = $(".nominal_code_add").val();
    var description = $(".description_add").val();
    var primary = $(".primary_grp_add").val();
    var debit = $(".debit_grp_add").val();
    var credit = $(".credit_grp_add").val();
    if(primary == "Profit & Loss")
    {
      if(debit == "")
      {
        alert("Please select the Debit Group");
        return false;
      }
    }
    else{
      if(debit == "")
      {
        alert("Please select the Debit Group");
        return false;
      }
      else if(credit == ""){
        alert("Please select the Credit Group");
        return false;
      }
    }
    $.ajax({
      url:"<?php echo URL::to('user/add_nominal_code_financial'); ?>",
      type:"post",
      dataType:"json",
      data:{code:code,description:description,primary:primary,debit:debit,credit:credit},
      success:function(result)
      {
        $(".general_nominal").html(result['dropdown_output']);
        $(".general_nominal_hidden_for_add_ajax").html(result['dropdown_output']);
        if(result['table_type'] == 0)
        {
          if(primary == "Profit & Loss")
          {
            $("#nominal_tbody").append('<tr class="code_'+code+'"><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td></tr>');  
            $(".add_nominal_modal").modal("hide");
          }
          else{
            $("#nominal_tbody").append('<tr class="code_'+code+'"><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+credit+'</a></td></tr>');  
            $(".add_nominal_modal").modal("hide");
          }
        }
        else{
          if(primary == "Profit & Loss")
          {
            $(".code_"+code).html('<td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td>');
            $(".add_nominal_modal").modal("hide");
          }
          else{
            $(".code_"+code).html('<td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+credit+'</a></td>');
            $(".add_nominal_modal").modal("hide");
          }
        }
      }
    })
  }
}
$('#add_nominal_form').validate({
    rules: {
        nominal_code_add : {required: true,remote:"<?php echo URL::to('user/check_nominal_code'); ?>"},
        description_add : {required: true,},
        primary_grp_add : {required:true,},
    },
    messages: {
        nominal_code_add : {
          required : "Nominal Code is Required",
          remote : "Nominal Code is Already created",
        },
        description_add : { 
          required : "Description is Required",
        },
        primary_grp_add : { 
          required : "Primary Group is Required",
        },
    },
});
if($(e.target).hasClass('edit_opening_balance_btn'))
{
  $(".opening_date_spam").hide();
  $(e.target).hide();
  $(".opening_financial_date").show();
  $(".save_opening_balance_btn").show();
}
if($(e.target).hasClass('save_opening_balance_btn'))
{
  var opening_balance_date = $(".opening_financial_date").val();
  $.ajax({
    url:"<?php echo URL::to('user/save_opening_balance_date'); ?>",
    type:"post",
    data:{opening_balance_date:opening_balance_date},
    success:function(result)
    {
      $(".opening_date_spam").show();
      $(".edit_opening_balance_btn").show();
      $(".opening_financial_date").hide();
      $(".save_opening_balance_btn").hide();
      $(".opening_date_spam").html(opening_balance_date);
    }
  })
}
if($(e.target).hasClass('accounting_period')){
  $(".accounting_period_modal").modal('show');
}
if($(e.target).hasClass('ac_save')){
  if($("#accounting_period_form").valid()){
    var start = $(".ac_start_date").val();
    var end = $(".ac_end_date").val();
    var desc = $(".ac_description").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/accounting_period_save'); ?>",
      type:"post",
      dataType:"json",
      data:{start:start, end:end, desc:desc},
      success:function(result){
        $("#tbody_account_period").html(result['output']);
        $(".ac_accounting_id").val(result['period_id']);
        $(".ac_start_date ").val('');
        $(".ac_end_date ").val('');
        $(".ac_description ").val('');
        $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('account_period_active')){
  var pop = confirm('Do you want to mark this period as default?');
  if(pop){
    $("body").addClass("loading");
    var period_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/accounting_period_set_default'); ?>",
      type:"post",
      dataType:"json",
      data:{period_id:period_id},
      success:function(result){
        $(".default_account_period").addClass("fa fa-times account_period_active");
        $(".default_account_period").css({"color":"#E11B1C"});
        $(".default_account_period").attr("title", 'Active Default Period');
        $(e.target).removeClass();
        $(e.target).addClass('fa fa-check default_account_period');
        $(e.target).css({"color":"#33CC66"});
        $(e.target).attr("title", '');
        $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('class_account_unlock')){
  var pop = confirm('Do you want to unlock this period?');
  if(pop){
    $("body").addClass("loading");
    var period_id = $(e.target).attr("data-element");
    var type = $(e.target).attr("type");
    $.ajax({
      url:"<?php echo URL::to('user/accounting_period_lock_unlock'); ?>",
      type:"post",
      dataType:"json",
      data:{period_id:period_id, type:type},
      success:function(result){
       $(e.target).removeClass();
       $(e.target).addClass('fa fa-unlock-alt class_account_lock');
       $(e.target).css({"color":"#33CC66"});
       $(e.target).attr("title", 'Lock Accounting Period');
       $(e.target).attr("type", '1');
       $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('class_account_lock')){
  var pop = confirm('Do you want to lock this period?');
  if(pop){
    $("body").addClass("loading");
    var period_id = $(e.target).attr("data-element");
    var type = $(e.target).attr("type");
    $.ajax({
      url:"<?php echo URL::to('user/accounting_period_lock_unlock'); ?>",
      type:"post",
      dataType:"json",
      data:{period_id:period_id, type:type},
      success:function(result){
       $(e.target).removeClass();
       $(e.target).addClass('fa fa-lock class_account_unlock');
       $(e.target).css({"color":"#E11B1C"});
       $(e.target).attr("title", 'Unlock Accounting Period');
       $(e.target).attr("type", '0');
       $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('reconcile_icon'))
{
  var value = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/finance_get_bank_details'); ?>",
    type:"post",
    dataType:"json",
    data:{id:value},
    success:function(result){
      $(".select_reconcile_bank").val(value);
      $(".td_bank_name").html(result['bank_name']);
      $(".tb_ac_name").html(result['account_name']);
      $(".td_ac_number").html(result['account_number']);
      $(".td_ac_description").html(result['description']);
      $(".td_nominal_code").html(result['nominal_code']);
      $(".table_bank_details").show();
      $(".reconcilation_section").hide();
      $(".transactions_section").hide();
      $(".reconcile_modal").modal("show");
      $("#unreconciled_alert_text").hide();
      $("#unreconciled_count").html("0");
      $("#unreconciled_count_text").html("0");
    }
  });  
}
if($(e.target).hasClass('practice_overview'))
{
  $(".practice_overview_modal").modal("show");
  $("#client_review_div").hide();
  $("#staff_review_div").hide();
  $(".tbody_staff_load").html("");
  $(".tbody_client_load").html("");
  $(".tbody_turnover_load").html("");
}
if($(e.target).hasClass('export_turnover')){
  $("body").addClass("loading");
  setTimeout(function(){ 
    $.ajax({
      url: "<?php echo URL::to('user/practice_review_export') ?>",
      data:{type:1},
      type:"post",
      success:function(result){
        SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    });
  }, 500);
}
if($(e.target).hasClass('load_turnover_review')){
  var first_year = $(".practice_first_year").val();
    if(first_year != "") {
        $("body").addClass('loading_pratice');
        $("#practice_turnover_table").dataTable().fnDestroy();
        $(".tbody_turnover_load").html("");
        $(".modal_pratice_span1").html("Loading Turnover Data for ");
        $.ajax({
            url:"<?php echo URL::to('user/practice_load_review'); ?>",
            type:"post",
            dataType:"json",
            data:{year:first_year},
            success:function(result)
            {
              setTimeout( function() {
                $(".modal_pratice_span2").html(first_year);
                $("#practice_turnover_table").show();
                $(".tbody_turnover_load").append(result['output']);
                practice_next_year(first_year);
              },200);
            }
        });
    }
    else{
        alert("No Data Found");
    }
}
if($(e.target).hasClass('practice_load_icon'))
{
  //$(".practice_icon").toggle();
  $("#client_review_div").show();
  $("#staff_review_div").show();
  $(".tbody_staff_load").html("");
  $(".tbody_client_load").html("");
}
if($(e.target).hasClass('export_client_review')){
  $("body").addClass("loading");
  setTimeout(function(){ 
    $.ajax({
      url: "<?php echo URL::to('user/practice_review_export') ?>",
      data:{type:2},
      type:"post",
      success:function(result){
        SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    });
  }, 500);
}
if($(e.target).hasClass('load_client_review_financial')){
  var client_count = $(".client_review_total").val();
  if($(".redact_name").is(":checked")){
    var redact = 1;
  }
  else{
    var redact = 0;
  }
  $("body").addClass('loading_pratice');
  $("#practice_client_table").dataTable().fnDestroy();
  $(".tbody_client_load").html("");
  $(".modal_pratice_span1").html("Loading Client Review Data for ");
  var count = '1';
  $.ajax({
    url:"<?php echo URL::to('user/practice_load_client_review'); ?>",
    type:"post",
    dataType:"json",
    data:{count:count,redact:redact},
    success:function(result)
    {
      setTimeout( function() {
        $(".modal_pratice_span2").html(result['client_id']);
        $("#practice_turnover_table").show();
        $(".tbody_client_load").append(result['output']);        
        practice_next_client(count);
      },200);
    }
  });
}
if($(e.target).hasClass('export_staff_review')){
  $("body").addClass("loading");
  setTimeout(function(){ 
    $.ajax({
      url: "<?php echo URL::to('user/practice_review_export') ?>",
      data:{type:3},
      type:"post",
      success:function(result){
        SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    });
  }, 500);
}
if($(e.target).hasClass('load_staff_review')){  
  $("body").addClass('loading_pratice');
  $("#practice_staff_table").dataTable().fnDestroy();
  $(".tbody_staff_load").html("");
  $(".modal_pratice_span1").html("Loading Staff Review Data");
  $(".modal_pratice_span2").html("");
  var count = '1';
  $.ajax({
    url:"<?php echo URL::to('user/practice_load_staff_review'); ?>",
    type:"post",
    dataType:"json",
    data:{count:count},
    success:function(result)
    {
      setTimeout( function() {
        $(".tbody_staff_load").append(result['output']);    
        $("body").removeClass("loading_pratice");
        $('#practice_staff_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });
      },200);
    }
  });
}
if($(e.target).hasClass('redact_name')){  
  if($(e.target).is(":checked")){
    $(".practice_client_name").hide();
  }
  else{
    $(".practice_client_name").show();
  }
}
if($(e.target).hasClass('client_finance_account_btn'))
{
  var date = $(".opening_date_spam").html();
  $(".opening_balance_date_spam").html(date);
  $(".client_finance_modal").modal("show");
}
if($(e.target).hasClass('export_csv_client_opening'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/export_csv_client_opening'); ?>",
    type:"post",
    success:function(result)
    {
      SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      $("body").removeClass("loading");
    }
  })
}
if(e.target.id == "analysis-tab")
{
  $(".export_csv_summary").show();
  $(".export_csv_client_opening").hide();
  $(".export_detail_analysis").hide();
}
if(e.target.id == "opening-balance-tab")
{
  $(".export_csv_summary").hide();
  $(".export_csv_client_opening").show();
  $(".export_detail_analysis").hide();
}
if(e.target.id == "detail-analysis-tab")
{
  $(".export_csv_summary").hide();
  $(".export_csv_client_opening").hide();
  $(".export_detail_analysis").show();
}
if($(e.target).hasClass('export_csv_summary'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/summary_export_csv'); ?>",
    type:"post",
    success:function(result)
    {
      SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('export_detail_analysis')){
  $(".detail_analysis_modal").modal('show');
}
if($(e.target).hasClass('load_summary_clients'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/summary_clients_list'); ?>",
    type:"post",
    success:function(result)
    {
      $("#summary_tbody").html(result);
      $("#summary_financial").show();
      setTimeout(function() {
        $("body").removeClass("loading");
        load_opening_balance();
      },1000);
    }
  });
}
if($(e.target).hasClass('load_details_analysis')){
$("body").addClass("loading_balance");
setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/finance_load_details_analysis'); ?>",
      type:"post",
      dataType:"json",
      data:{},
      success:function(result){
        $(".div_details_analysis").html(result['output']);
        $("body").removeClass("loading_balance");
      }
  })
},1000);
}
if($(e.target).hasClass('export_detail_analysis')){
  $(".detail_analysis_modal").modal('show');
}
if($(e.target).hasClass('analysis_report_button')){
  var type = $(".analysis_report_type_input").val();
  var format = $(".analysis_report_format_input").val();
  if(type == ''){
    $(".error_report_type").html('Please select Report type');
    $(".error_report_type").show();
  }
  else if(format == ''){
    $(".error_report_type").hide();
    $(".error_report_format").html('Please select export format');
  }
  else{
    if(format == "3"){
      $("body").addClass("loading_delay");
    }
    else{
      $("body").addClass("loading");
    }
    $(".error_report_type").hide();
    $(".error_report_format").hide();
    setTimeout(function() {
      $.ajax({
          url:"<?php echo URL::to('user/finance_analysis_report'); ?>",
          type:"post",          
          data:{type:type, format:format },
          success:function(result){
            SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
            $("body").removeClass("loading_delay");
            $("body").removeClass("loading");
          }
      })
    },500);
  }
}
if($(e.target).hasClass('report_type_class')){
  var value = $(e.target).val();  
  $(".analysis_report_type_input").val(value);
  $(".error_report_type").hide();
}
if($(e.target).hasClass('report_format_class')){
  var value = $(e.target).val();  
  $(".analysis_report_format_input").val(value);
  $(".error_report_format").hide();
}
if($(e.target).hasClass('reconcile_load')){
  var value = $(".select_reconcile_bank").val();
  $("body").addClass("loading");
  setTimeout(function() {
    $("#transaction_table").dataTable().fnDestroy();
    $("#reconcile_report").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/finance_reconcile_load'); ?>",
      type:"post",
      dataType:"json",
      data:{id:value},
      success:function(result){
        $(".receipt_id").val(result['receipt_ids']);
        $(".payment_id").val(result['payment_ids']);
        $(".balance_tran_class").val(result['balance_transaction']);
        $(".input_total_outstanding").val(result['outstanding']);
        $(".class_total_outstanding").html(result['outstanding_html']);
        $(".class_total_outstanding_html").html(result['outstanding_html']);
        $("#reconcile_tbody").html(result['reconcile_output']);
        $(".tbody_transaction").html(result['transactions']);
        $(".tbody_reconcilation").html(result['reconcilation']);
        $("#unreconciled_count").html(result['unreconciled']);
        $("#unreconciled_count_text").html(result['unreconciled']);
        if(result['unreconciled'] > 0){
          $("#unreconciled_alert_text").show();
          $(".accept_all_button").prop("disabled",true);
          $(".accept_reconciliation").prop("disabled",true);
        }
        else{
          $("#unreconciled_alert_text").hide();
          $(".accept_all_button").prop("disabled",false);
          $(".accept_reconciliation").prop("disabled",false);
        }
        $(".transactions_section").show();
        $(".reconcilation_section").show();
        $(".date_balance_bank").datetimepicker({
           defaultDate: "",
           format: 'L',
           format: 'DD/MM/YYYY',
        });
        $('#transaction_table').DataTable({        
            // autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            order: [[1, 'asc'], [3, 'desc']]
        });
        $('#reconcile_report').DataTable({        
            // autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
        });
        $("body").removeClass("loading");
      }
    });  
  },500);  
}
if($(e.target).hasClass('accept_all_button')){
  var pop = confirm('You are about to set the Clearance Date of All Transactions (Payments/receipts and General Journals) to the Transaction Date.  This will lock the Bank Account and Value on the Payments and Receipts systems for these transactions and you will not be able to change them.  Do you Want to Continue? ');
  if(pop){
    $("#transaction_table").dataTable().fnDestroy();
    var receipt_id = $(".receipt_id").val();
    var payment_id = $(".payment_id").val();
    var select_bank = $(".select_reconcile_bank").val();
    $.ajax({
      url:"<?php echo URL::to('user/finance_bank_all_accept'); ?>",
      type:"post",
      dataType:"json",
      data:{select_bank:select_bank},
      success:function(result){
        $(".tbody_transaction").html(result['transactions']);
        $(".class_total_outstanding").html(result['total_outstanding']);
        $(".input_total_outstanding").val(result['total_outstanding']);
        $(".class_total_outstanding").css({"color":"orange", "font-weight":"bold"});
        $('#transaction_table').DataTable({     
            // autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            //order: [[1, 'asc'], [3, 'desc']]
        });
      }
    })
  }
  else{
    console.log('false');
  }
}
if($(e.target).hasClass('refresh_button')){
  var input_total_outstanding = $(".input_total_outstanding").val();
  var input_balance_bank = $(".input_balance_bank").val();
  var input_bala_transaction = $(".balance_tran_class").val();
  $.ajax({
      url:"<?php echo URL::to('user/finance_bank_refresh'); ?>",
      type:"post",
      dataType:"json",
      data:{input_total_outstanding:input_total_outstanding, input_balance_bank:input_balance_bank,input_bala_transaction:input_bala_transaction},
      success:function(result){
        $(".input_close_balance").val(result['close_balance']);
        $(".class_close_balance").html(result['close_balance_span']);
        $(".input_difference").val(result['diffence']);
        $(".class_difference").html(result['diffence_span']);
        $(".refresh_input_outstanding").val(result['outstanding']);
        $(".class_total_outstanding_refresh").html(result['outstanding_span']);
        $(".class_total_outstanding_refresh").removeClass('orange_value_refresh');
      }
  })
}
if($(e.target).hasClass('accept_reconciliation')){
  if($(".class_total_outstanding_refresh").hasClass('orange_value_refresh'))
  {
    alert("You can not accept the Reconciliation while there are Differences Due to updated Cleared Items and the Bank Statement Balance is Selected");
    return false;
  }
  if(($(".input_balance_bank").val() == '') || ($(".input_balance_bank").val() == '0') || ($(".input_balance_bank").val() == '0.00')){
    alert("You can not accept the Reconciliation while there are Differences Due to updated Cleared Items and the Bank Statement Balance is Selected");
    return false;
  }
  var countval = $(".process_journal").length;
  var round = Math.ceil(countval / 1000);
  if(countval > 0)
  {
    var r = confirm("The Reconciliation Process will post the journals of the Cleared Items, do you wish to continue");
    if(r)
    {
      $(".first_rec_div").show();
      $(".second_rec_div").hide();
      $("body").addClass('loading_reconcilation')
      $("#apply_rec_first").html(1);
      $("#apply_rec_last").html(round);
      setTimeout(function(){
        accept_reconciliation(1,round);
      },1000);
    }
  }
  else{
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">There are no Cleared Items on the Transaction Grid. Please set the Clearence Date and then click on the Accept Reconciliation Button.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button ok_proceed" value="OK"></p>',fixed:true,width:"800px"});
  }
}
if($(e.target).hasClass("ok_proceed"))
{
  $.colorbox.close();
}
if($(e.target).hasClass('reconciliation_pdf'))
{
  var bank_id = atob($(".select_reconcile_bank").val());
  var input = $(".input_balance_bank").val();
  var date = $(".date_balance_bank").val();
  var tor = $(".refresh_input_outstanding").val();
  var cb = $(".class_close_balance").html();
  var cd = $(".class_difference").html();
  if(cb == ""){
    alert("The Closing Balance is Empty so you cant Generate the Pdf File.");
    return false;
  }
  if(cd == ""){
    alert("The Difference is Empty so you cant Generate the Pdf File.");
    return false;
  }
  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/generate_reconcile_pdf'); ?>",
    type:"post",
    data:{bank_id:bank_id,input:input,date:date,tor:tor,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('reconciliation_csv'))
{
  var bank_id = atob($(".select_reconcile_bank").val());
  var input = $(".input_balance_bank").val();
  var date = $(".date_balance_bank").val();
  var tor = $(".refresh_input_outstanding").val();
  var cb = $(".class_close_balance ").html();
  var cd = $(".class_difference").html();
  if(cb == ""){
    alert("The Closing Balance is Empty so you cant Generate the Pdf File.");
    return false;
  }
  if(cd == ""){
    alert("The Difference is Empty so you cant Generate the Pdf File.");
    return false;
  }
  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/generate_reconcile_csv'); ?>",
    type:"post",
    data:{bank_id:bank_id,input:input,date:date,tor:tor,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('commit_btn'))
{
  var client_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/commit_client_account_opening_balance'); ?>",
    type:"post",
    data:{client_id:client_id},
    success:function(result)
    {
      $(e.target).parents("td:first").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>')
    }
  })
}
if($(e.target).hasClass('active_client_list'))
{
  var client_id=$("#client_search_hidden_top_menu").val();
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
if($(e.target).hasClass('a_remove_success_msg'))
{
  $("#success_msg_active_list").html('');
}
if($(e.target).hasClass('active_client_manager'))
{
  $(".select_user_AM").change();
  $(".active_client_manager_modal").modal("show"); 
}
if($(e.target).hasClass('remove_active_list'))
{
  var id=$(e.target).attr("data-element");
  var pop = confirm('Are you sure you want to delete this Client from your list?');
  if(pop){
    if(id!=''){
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/remove_active_client_list'); ?>",
        type:"post",
        data:{'id':id},
        success:function(result)
        {
          $(".select_user_AM").change();
        }
      })
    }
  }
}
if($(e.target).hasClass('delete_all_active_list'))
{
  var user_id=$(".select_user_AM").val();
  var pop = confirm('Are you sure you want to delete all the Clients from this user?');
  if(pop){
    if(user_id!=''){
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/remove_all_active_client_list'); ?>",
        type:"post",
        data:{'user_id':user_id},
        success:function(result)
        {
          $("#active_manager_tbody tr").remove();
          $("body").removeClass("loading");
          alert("All active clients are deleted for this user");
        }
      })
    }
  }
}
});
$(function () {
  $(".ac_start_date").datetimepicker({
     format: 'L',
     format: 'DD MMM YYYY',
     icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-clock-o",
        clear: "fa fa-trash-o"
    },
  });
  $(".ac_end_date").datetimepicker({
     format: 'L',
     format: 'DD MMM YYYY',
     icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-clock-o",
        clear: "fa fa-trash-o"
    },
  });
  $(".ac_start_date").on("dp.change", function (e) {
    $(".ac_end_date").val("");
    $(".ac_description").val("");
    $('.ac_end_date').data("DateTimePicker").minDate(e.date);
  });
  $(".ac_end_date").on("dp.change", function (e) {
    var start = $(".ac_start_date").val();
    var end = $(".ac_end_date").val();
    $(".ac_description").val(start+' - '+end);
  });
});
$('#ac_period_table').DataTable({        
    // autoWidth: true,
    scrollX: false,
    fixedColumns: false,
    searching: false,
    paging: false,
    info: false,
    order: [[ 1, "asc" ]]
});
function practice_next_year(year)
{
  var year = parseInt(year)+1;
  var last_year = $(".practice_last_year").val();
  if(year > last_year){
    $("body").removeClass("loading_pratice");
    $('#practice_turnover_table').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/practice_load_review'); ?>",
      type:"post",
      dataType:"json",
      data:{year:year},
      success:function(result)
      {
        setTimeout( function() {
          $(".tbody_turnover_load").append(result['output']);
          $(".modal_pratice_span2").html(year);
          practice_next_year(year);
        },200);
      }
    });
  }
}
function practice_next_client(count)
{
  var count = parseInt(count)+1;
  var total_client = $(".client_review_total").val();
  if(count > total_client){
    $("body").removeClass("loading_pratice");
    $('#practice_client_table').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
  }
  else{
    if($(".redact_name").is(":checked")){
      var redact = 1;
    }
    else{
      var redact = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/practice_load_client_review'); ?>",
      type:"post",
      dataType:"json",
      data:{count:count,redact:redact},
      success:function(result)
      {
        setTimeout( function() {
          $(".tbody_client_load").append(result['output']);
          $(".modal_pratice_span2").html(result['client_id']);
          practice_next_client(count);
        },200);
      }
    });
  }
}
$('#accounting_period_form').validate({
    rules: {
        ac_accounting_id : {required: true,},
        ac_start_date : {required: true,},
        ac_end_date : {required:true,},
        ac_description : {required:true,},        
    },
    messages: {
        ac_accounting_id : {
          required : "Account Period Id",
        },
        ac_start_date : { 
          required : "Please Select Start Date",
        },
        ac_end_date : { 
          required : "Please Select End Date",
        },
        ac_description : {
          required : "Please Enter Description",
        }
    },
});
$(".debit_fin_sort_val").blur(function() {
  var debit = $(this).val();
  var client_id = $(this).attr("data-element");
  var credit = $(".credit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});
$(".credit_fin_sort_val").blur(function() {
  var credit = $(this).val();
  var client_id = $(this).attr("data-element");
  var debit = $(".debit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var valueInterval_client = 1000;  //time in ms, 5 second for example 
    if($(e.target).hasClass('bank_name_add'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_add'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('bank_name_edit'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_edit'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('debit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var credit = $(".credit_fin_sort_val_"+client_id).val();
      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,input_val,credit,client_id);   
    }
    if($(e.target).hasClass('credit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var debit = $(".debit_fin_sort_val_"+client_id).val();
      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,debit,input_val,client_id);   
    }
});
function doneTyping (bank_name,account_no) {
  $(".nominal_description_add").val(bank_name+' '+account_no);
}
function doneTyping_edit (bank_name,account_no) {
  $(".nominal_description_edit").val(bank_name+' '+account_no);
}
function doneTyping_debit (debit,credit,client_id) {
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
}
function load_opening_balance()
{
  $("body").addClass("loading_balance");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_opening_balance'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(4).html(result['output'][i]);
        i++;
      });
      $(".total_opening_balance_summary").html(result['total']);
      setTimeout(function() {
          $("body").removeClass("loading_balance");
          load_receipts();
      },1000);
    }
  });
}
function load_receipts()
{
  $("body").addClass("loading_receipts");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_receipts'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(5).html(result['output'][i]);
        i++;
      });
      $(".total_receipt_summary").html(result['total']);
      setTimeout(function() {
          $("body").removeClass("loading_receipts");
          load_payments();
      },1000);
    }
  });
}
function load_payments()
{
  $("body").addClass("loading_payments");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_payments'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(6).html(result['output'][i]);
        i++;
      });
      $(".total_payment_summary").html(result['total']);
      setTimeout(function() {
         $("body").removeClass("loading_payments");
          calculate_payments();
      },1000);
    }
  });
}
function calculate_payments()
{
  $("body").addClass("loading_calculations");
  $.ajax({
    url:"<?php echo URL::to('user/summary_calculations'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(7).html(result['output'][i]);
        i++;
      });
      $(".total_balance_summary").html(result['total']);
      setTimeout(function() {
         $("body").removeClass("loading_calculations");
      },1000);
    }
  });
}
Dropzone.options.ImageUploadEmailVat = {
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
              Dropzone.forElement("#ImageUploadEmailVat").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".vat_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".vat_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailVat").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".vat_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".vat_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".vat_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailVat").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
Dropzone.options.ImageUploadEmailPms = {
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
              Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".pms_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".pms_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".pms_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".pms_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".pms_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPms").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
Dropzone.options.ImageUploadEmailPayemrs = {
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
              Dropzone.forElement("#ImageUploadEmailPayemrs").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".paye_mrs_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".paye_mrs_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailPayemrs").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Paye MRS Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".paye_mrs_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPayemrs").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".paye_mrs_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".paye_mrs_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailPayemrs").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Paye M.R.S Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
$('#add_purchase_invoice_form').validate({
    rules: {
        ac_accounting_id : {required: true,},               
    },
    messages: {
        ac_accounting_id : {
          required : "You cannot save this Invoice to a Locked Accounting Period.",
        }
    },
});
</script>
</body>
</html>