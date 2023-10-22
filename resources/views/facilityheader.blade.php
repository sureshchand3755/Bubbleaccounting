<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
     $menu_logo_img = '';
     $page_segment =  Request::segment(2);
     if($page_segment == "profile") { $title = 'Bubble - Profile'; }
     elseif($page_segment == "practice_screen") { $title = 'Bubble - Practices'; }
     elseif($page_segment == "table_viewer") { $title = 'Bubble - Table Viewer'; }
     elseif($page_segment == "manage_avatar") { $title = 'Bubble - User Avatar'; }
     elseif($page_segment == "default_email_image") { $title = 'Bubble - Default Email Header Image'; }
     elseif($page_segment == "system_setting_review") { $title = 'Bubble - System Setting Review'; }
     elseif($page_segment == "manage_cm_paper") { $title = 'Bubble - CMS Print Label'; }
     elseif($page_segment == "manage_cm_class") { $title = 'Bubble - CMS Manage Class'; }
     elseif($page_segment == "default_nominal_codes") { $title = 'Bubble - Default Nominal Codes'; }
     elseif($page_segment == "report_categories") { $title = 'Bubble - Report Categories'; }
     elseif($page_segment == "categories_sub_sections") { $title = 'Bubble - Categories Sub Sections'; }
     else { $title = 'Bubble'; }
     ?>
    <title><?php echo $title; ?></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="<?php echo URL::to('public/assets/images/fav_icon.png'); ?>" sizes="16x16 32x32 64x64" type="image/png"/>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/bootstrap-theme.min.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/font-awesome-4.2.0/css/font-awesome.css')?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL::to('public/assets/css/datepicker/jquery-ui.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/facility_style.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.css'); ?>" />
    <link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">

    <script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery-2.1.3.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo URL::to('public/assets/js/jquery.validate.js')?>"></script>
    <script type="text/javascript" src="<?php echo URL::to('public/assets/js/popper.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo URL::to('public/assets/js/bootstrap_3.3.5.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.js'); ?>"></script>
    <script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
    <script src="<?php echo URL::to('public/assets/js/jscolor/jscolor.js') ?>"></script>
</head>
<body>
<style>
    html {
  scrollbar-color: #3db0e6 #f5f5f5;
  scrollbar-width: thin;
}
.treeview-submenu li a {
    padding-left: 80px !important;
}
</style>
    <div class="wrapper">
        <!--Top menu -->
        <div class="sidebar">
            <div class="profile">
                <img src="<?php echo URL::to('public/assets/user_avatar/ciaranguilfoyle-16.png'); ?>" alt="profile_picture">
                <h3>Sandra</h3>
                <p>Facility Admin</p>
            </div>

            <ul>
                <li>
                    <a href="<?php echo URL::to('facility/profile'); ?>" class="<?php if($page_segment == 'profile') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="item">Admin Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/practice_screen'); ?>" class="<?php if($page_segment == 'practice_screen') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-users"></i></span>
                        <span class="item">Practice Screen</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/table_viewer'); ?>" class="<?php if($page_segment == 'table_viewer') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-database"></i></span>
                        <span class="item">Table Viewer</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/manage_avatar'); ?>" class="<?php if($page_segment == 'manage_avatar') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-user-tie"></i></span>
                        <span class="item">User Avatar</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/default_email_image'); ?>" class="<?php if($page_segment == 'default_email_image') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-envelope"></i></span>
                        <span class="item">Default Email Image</span>
                    </a>
                </li>
                <li class="treeview-item">
                    <a href="javascript:" class="<?php if($page_segment == 'manage_cm_paper' || $page_segment == 'manage_cm_class') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-bars"></i></span>
                        <span class="item">CM System Management </span>
                    </a>
                    <ul class="treeview-submenu" style="<?php if($page_segment == 'manage_cm_paper' || $page_segment == 'manage_cm_class') { echo ''; } else { echo 'display: none'; } ?>">
                        <li>
                            <a href="<?php echo URL::to('facility/manage_cm_paper')?>" class="<?php if($page_segment == 'manage_cm_paper') { echo 'active'; } ?>">Manage Print Label</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('facility/manage_cm_class')?>" class="<?php if($page_segment == 'manage_cm_class') { echo 'active'; } ?>">Add/Remove Class</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/system_setting_review'); ?>" class="<?php if($page_segment == 'system_setting_review') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-cogs"></i></span>
                        <span class="item">System Settings Review</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL::to('facility/default_nominal_codes'); ?>" class="<?php if($page_segment == 'default_nominal_codes') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-bars"></i></span>
                        <span class="item">Default Nominal Codes</span>
                    </a>
                </li>
                <li style="position: absolute;bottom: 0px;width: 100%;background: #000;">
                    <a href="<?php echo URL::to('facility/logout'); ?>" style="text-decoration: none">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="item">Logout</span>
                    </a>
                </li>
                <li class="treeview-item">
                    <a href="javascript:" class="<?php if($page_segment == 'categories' || $page_segment == 'subcategories') { echo 'active'; } ?>">
                        <span class="icon"><i class="fas fa-bars"></i></span>
                        <span class="item">AI Accounts Report </span>
                    </a>
                    <ul class="treeview-submenu" style="<?php if($page_segment == 'categories' || $page_segment == 'subcategories') { echo ''; } else { echo 'display: none'; } ?>">
                        <li>
                            <a href="<?php echo URL::to('facility/categories')?>" class="<?php if($page_segment == 'categories') { echo 'active'; } ?>">Report Categories</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('facility/subcategories')?>" class="<?php if($page_segment == 'subcategories') { echo 'active'; } ?>">Category Sub Sections</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="section">
            <div class="top_navbar">
                <div class="hamburger">
                    <a href="#">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
  <script>
    $(document).ready(function() {
        $('.treeview-item > a').click(function() {
            $(this).next('.treeview-submenu').slideToggle();
            $(this).toggleClass("active");
        });
    });
    var hamburger = document.querySelector(".hamburger");
    hamburger.addEventListener("click", function(){
        document.querySelector("body").classList.toggle("active");
    })
    $.ajaxSetup({
        headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
  </script>
</body>
</html>
