<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="<?php echo URL::to('public/assets/images/fav_icon.png'); ?>" sizes="16x16 32x32 64x64" type="image/png"/>
  <?php
  //header('Set-Cookie: fileDownload=true; path=/');
  header('Cache-Control: max-age=60, must-revalidate');
  $menu_logo_img = '';
  $page_segment =  Request::segment(1);

  if($page_segment == "") { $title = 'Bubble - Home'; }
  elseif($page_segment == "about") { $title = 'Bubble - AboutUS'; }
  elseif($page_segment == "modules") { $title = 'Bubble - Modules'; }
  elseif($page_segment == "access-payroll") { $title = 'Bubble - Access Payroll'; }
  elseif($page_segment == "bubble-books") { $title = 'Bubble - Bubble Books'; }
  elseif($page_segment == "contact") { $title = 'Bubble - Contact'; }
  elseif($page_segment == "schedule") { $title = 'Bubble - Schedule a Call'; }
  elseif($page_segment == "demo") { $title = 'Bubble - Demo'; }
  elseif($page_segment == "login") { $title = 'Bubble - Login'; }
  elseif($page_segment == "register") { $title = 'Bubble - Register'; }
  else{ $title = 'Bubble'; }

  ?>
  <title><?php echo $title; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo URL::to('public/staticpages/css/app.css'); ?>" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="<?php echo URL::to('public/assets/js/jquery.validate.js'); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<style>
.error {color:#f00;}
</style>
<body>
  <div id="header">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand me-auto" href="<?php echo URL::to('/'); ?>">
          <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" class="" alt="bubble logo" />
        </a>
        <button class="navbar-toggler order-last" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
            <a class="logo_small" href="<?php echo URL::to('/'); ?>"><img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" alt="bubble logo" /></a>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "about") { echo 'active'; } ?>" href="<?php echo URL::to('about'); ?>">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "modules") { echo 'active'; } ?>" href="<?php echo URL::to('modules'); ?>">Modules</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "access-payroll") { echo 'active'; } ?>" href="<?php echo URL::to('access-payroll'); ?>">Access Payroll</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "bubble-books") { echo 'active'; } ?>" href="<?php echo URL::to('bubble-books'); ?>">Bubble Books</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "contact" || $page_segment == "schedule") { echo 'active'; } ?>" href="<?php echo URL::to('contact'); ?>">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if($page_segment == "demo") { echo 'active'; } ?>" href="<?php echo URL::to('demo'); ?>">Demo</a>
            </li>
          </ul>
        </div>
        <div class="d-flex signup">
          <li class="nav-item">
            <a class="nav-link btn login" href="<?php echo URL::to('login'); ?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn" href="<?php echo URL::to('register'); ?>">Register</a>
          </li>
        </div>
    </nav>
    @yield('content')
    <!-- Footer section -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
            <a href="#"><img src="<?php echo URL::to('public/staticpages/img/Bubblelogo-white.png'); ?>" alt="bubble logo"></a>
            <p>Unit 3E Deerpark Business Centre, Oranmore, Galway.</p>
            <ul>
              <li>
                <a href="tel:091447178"><span>Tel: </span>(091) 447178</a>
                <a href="tel:0879188907"><span>Mob:</span> (087) 9188907</a>
                <a href="mailto:info@gbsco.ie"><span>Email:</span> info@gbsco.ie</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <h3>About us</h3>
            <ul>
              <li>
                <a href="#">Support</a>
                <a href="<?php echo URL::to('contact'); ?>">Contact</a>
                <a href="#">Careers</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <h3>Modules</h3>
            <ul>
              <li>
                <a href="#">Client & Client Invoice</a>
                <a href="#">Management</a>
                <a href="#">The 2 Bill Manager</a>
                <a href="#">Practice Financials</a>
                <a href="#">Payroll Management System</a>
                <a href="#">Payroll Modern Reporting System</a>
                <a href="#">Year End Manager</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <h3>Modules</h3>
            <ul>
              <li>
                <a href="#">Client Request System</a>
                <a href="#">VAT Management System</a>
                <a href="#">RCT System</a>
                <a href="#">CRO ARD System</a>
                <a href="#">Time Management Tools</a>
                <a href="#">Infiles System</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="copyright">
        <p>© <?php echo date('Y'); ?> Bubble Accounting, All rights reserved.</p>
      </div>
    </footer>
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
