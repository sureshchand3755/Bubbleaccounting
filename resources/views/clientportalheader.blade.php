<html>
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php
$menu_logo_img = '';
$page_segment =  Request::segment(2);
if($page_segment == "cms_client_portal") { $title = 'Bubble - Client Portal'; }
elseif($page_segment == "cms_client_portal") { $title = 'Bubble - Client Portal'; }
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
.body_bg{
    background: #03d4b7;
}
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
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
<div class="modal_load"></div>

<div class="top_row" style="z-index: 999999999">
  <div class="col-lg-12 padding_00" style="height:84px">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo URL::to('user/dashboard')?>" style="float:left"><img style=" height:55px;" src="<?php echo URL::to('public/assets/images/bubble-color-logo-horizontal.png')?>" class="img-responsive" /></a>

          <div style="float:left;border-left: 2px solid #bdbdbd;margin-top: 7px;">
            <spam style="font-size: 32px;margin-top: 3px;float: left;margin-left: 10px;">Client</spam><br/>
            <spam style="margin-top: -12px;float: left;font-size: 21px;margin-left: 13px;">Portal</spam>
          </div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right menu portal_menu">
            <li class="active">
              <a href="javascript:" class="portal_link about_me_portal">About Me</a>
            </li>
            <li class="">
              <a href="javascript:" class="portal_link email_received_portal">Emails I have Received</a>
            </li>
            <li class="">
              <a href="javascript:" class="portal_link coms_portal">My Coms</a>
            </li>
            <li class="">
              <a href="javascript:" class="portal_link key_documents_portal">Key Documents</a>
            </li>
            <li><a href="<?php echo URL::to('user/logout')?>" title="Logout" style="margin-top: -3px;"><i class="fa fa-sign-out fa-2x" style="font-size:2em !important;" aria-hidden="true"></i></a></li>
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
        //$("body").attr("class", "body_bg");
    },
    error: function(xhr, textStatus, errorThrown){
        //$("body").attr("class", "body_bg");
    },
});
$(window).load(function(){
  setTimeout(function(){ $(".start_load").hide(); },1000);
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
$(document).ready(function() {
    $(".page_title h4:first").addClass('menu-logo');
    $(".modal-title").addClass('menu-logo');
})
$(window).click(function(e) {
    if($(e.target).hasClass('portal_link')) {
        $(".portal_menu").find("li").removeClass("active");
        $(e.target).parents("li:first").addClass("active");

        if($(e.target).hasClass('about_me_portal')) {
            $(".client_portal_content").hide();
            $(".about_me_content").show();
        }
        if($(e.target).hasClass('email_received_portal')) {
            $(".client_portal_content").hide();
            $(".email_received_content").show();
        }
        if($(e.target).hasClass('coms_portal')) {
            $(".client_portal_content").hide();
            $(".coms_content").show();
        }
        if($(e.target).hasClass('key_documents_portal')) {
            $(".client_portal_content").hide();
            $(".key_documents_content").show();
        }
    }
})
</script>
</body>
</html>