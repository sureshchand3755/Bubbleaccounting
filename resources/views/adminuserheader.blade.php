 <html>
<head>
<meta charset="UTF-8">
<?php
    //header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
 ?>
 
<title></title>
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
</style>
</head>
<body class="body_bg">
@yield('content')
<script>
$.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});
$(document).ready(function() {
    $("body").removeClass("loading");
    $(".global_opening_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
       widgetPositioning: { horizontal: 'left', vertical: 'top'}
    });
})
</script>

</body>
</html>