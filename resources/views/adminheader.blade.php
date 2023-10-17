<html>
<head>
<title>Bubble - TaskAdmin</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
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

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<link href="<?php echo URL::to('public/assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/new_style.css')?>">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery-2.1.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery.validate.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/datepicker/jquery-ui.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/js/bootstrap_3.3.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('public/assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>

<style type="text/css">
  .table thead th:focus{background: #ddd !important;}
  .navbar-default, .footer_row{
    background: #e7e7e7 !important;
  }
</style>
</head>
<body>

<div class="top_row">
  <div class="col-lg-12 padding_00">
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
          <a class="navbar-brand" href="#"><img src="<?php echo URL::to('public/assets/images/bubble-color-logo-horizontal.png')?>" style="height:55px;" class="img-responsive" /></a>
        </div>
<?php $segment1 =  Request::segment(2); ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right menu">
            <!-- <li class="dropdown <?php if($segment1 == "profile" || $segment1 == "vat_profile" || $segment1 == "manage_cro") { echo 'active'; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/profile')?>">Manage Admin/User Login</a></li>
                </ul>
            </li> -->
            <li class="<?php if($segment1 == "profile" || $segment1 == "vat_profile" || $segment1 == "manage_cro") { echo 'active'; } ?>"><a href="<?php echo URL::to('admin/profile')?>">Manage Admin/User Login</a></li>

            <li><a href="<?php echo URL::to('admin/logout')?>">Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
  </div>
   
</div>
@yield('content')
<div class="footer_row">© Copyright <?php echo date('Y'); ?> All Rights Reserved BubbleAccounting</div>

<script>
$.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});
$(window).click(function(e) {
    if($(e.target).hasClass('clear_receipt_system')) {
        var r = confirm("Are you sure you want to delete all data's from the Receipt System?");
        var hrefval = $(e.target).attr("data-element");
        if(r){
            window.location.replace(hrefval);
        }
    }
    if($(e.target).hasClass('clear_payment_system')) {
        var r = confirm("Are you sure you want to delete all data's from the Payment System?");
        var hrefval = $(e.target).attr("data-element");
        if(r){
            window.location.replace(hrefval);
        }
    }
});
</script>
</body>
</html>