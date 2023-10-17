<html>
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php
    header('Cache-Control: max-age=60, must-revalidate');
 ?>
 <?php
 $page_segment =  Request::segment(2);
 if($page_segment == "dashboard") { $title = 'Payroll - Dashboard'; }
 elseif($page_segment == "manage_week") { $title = 'Payroll - Weekly Payroll Management'; }
 elseif($page_segment == "week_manage") { $title = 'Payroll - Weekly Payroll Management'; }
 elseif($page_segment == "select_week") { $title = 'Payroll - Weekly Payroll Management'; }
 elseif($page_segment == "manage_month") { $title = 'Payroll - Monthly Payroll Management'; }
 elseif($page_segment == "month_manage") { $title = 'Payroll - Monthly Payroll Management'; }
 elseif($page_segment == "select_month") { $title = 'Payroll - Monthly Payroll Management'; }
 else{ $title = 'Payroll'; }
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
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>

<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main.js'); ?>"></script>
<style>
.body_bg{
      }
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
</style>
</head>

<body class="body_bg">
<div class="top_row" style="z-index: 999999999999999999999999999999999">
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
          <a class="navbar-brand" href="<?php echo URL::to('payroll/dashboard')?>"><img style=" height:55px;" src="<?php echo URL::to('public/assets/images/bubble-color-logo-horizontal.png')?>" class="img-responsive" /></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right menu" style="margin-right: 1%;">
            
            <li><a href="<?php echo URL::to('payroll/logout')?>" title="Logout" style="margin-top: -3px;"><i class="fa fa-sign-out fa-2x" style="font-size:2em !important;" aria-hidden="true"></i></a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
  </div>
</div>
@yield('content')
<script>
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
    }
});
</script>

</body>

</html>