<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="<?php echo URL::to('public/assets/images/fav_icon.png'); ?>" sizes="16x16 32x32 64x64" type="image/png"/>
  <title>Bubble Accounting</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo URL::to('public/assets/css/bootstrap-theme.min.css'); ?>" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?php echo URL::to('public/assets/new_home/css/app.css')?>"/>
</head>

<body>

<style>
.error, .error_captcha{
  color:#f00;
}
.login_section{
  width: 280px;
  height: auto;
  padding: 25px;
  box-shadow: 0px 0px 10px #9f9f9f;
  position: absolute;
  z-index: 9;
  right: 133px;
  background: #fff;
  top: 69px;
  border-top: 5px solid #93c90e;
}
.common_button {
  font-size: 14px;
  display: inline-block;
  margin: 0;
  border: 2px solid #fff;
    border-top-color: rgb(255, 255, 255);
    border-right-color: rgb(255, 255, 255);
    border-bottom-color: rgb(255, 255, 255);
    border-left-color: rgb(255, 255, 255);
  border-radius: 40px;
  width: auto;
  min-width: auto;
  text-align: center;
  border-color: #0094d6;
  padding: 6px 25px;
  color: #fff;
  background: #0094d6;
}
.common_button_gray {
  font-size: 14px;
  display: inline-block;
  margin: 0;
  border: 2px solid #c9c9c9;
    border-top-color: rgb(201, 201, 201);
    border-right-color: rgb(201, 201, 201);
    border-bottom-color: rgb(201, 201, 201);
    border-left-color: rgb(201, 201, 201);
  border-radius: 40px;
  width: auto;
  min-width: auto;
  text-align: center;
  border-color: #c9c9c9;
  padding: 6px 25px;
  color: #000;
  background: #c9c9c9;
}
</style>

<div class="modal fade register_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" style="margin-top: 10%;">
    <div class="modal-content">
      <form id="reg_form" action="<?php echo URL::to('user/user_registration'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">Create an Account</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div style="clear: both">
          <?php
          if(Session::has('message')) { ?>
              <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
          <?php } ?>
        </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Enter Practice Name:</label>
                <input type="text" name="practice_name" id="practice_name" class="form-control" placeholder="Enter Practice Name" required>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Address:</label>
                <input type="text" name="address1" id="address1" class="form-control" placeholder="Enter Address" required>
              </div>
              <div class="form-group">            
                <input type="text" name="address2" id="address2" class="form-control" placeholder="Enter Address">
              </div>
              <div class="form-group">    
                <input type="text" name="address3" id="address3" class="form-control" placeholder="Enter Address">
              </div>
              <div class="form-group">   
                <input type="text" name="address4" id="address4" class="form-control" placeholder="Enter Address">
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Practice Logo:</label>
                <input type="file" name="practice_logo" id="practice_logo" class="form-control" placeholder="Choose Practice Logo" required style="background: #fff;">
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Telephone Number:</label>
                <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Enter Telephone Number" required>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Administration User:</label>
                <input type="text" name="admin_user" id="admin_user" class="form-control" placeholder="Enter Administration User" required>
              </div>
            </div>
            <!-- <div class="col-md-8">
              <div class="not-robot">
                <script src='https://www.google.com/recaptcha/api.js'></script>
                <div class="g-recaptcha" data-sitekey="6Ld5rXAUAAAAACzAVEc4dhZv5iNZj1YizfJfirdO"></div>
                <div style="margin-top: -3%;color: #f00;font-size: 13px;"></div>
                <p class="error_captcha"></p>
              </div>
            </div> -->
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary common_button_gray" data-bs-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary common_button">REGISTER</button>
      </div>
       @csrf
</form>
    </div>
  </div>
</div>
<div class="modal fade user_logging_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="margin-top: 10%;">
    <div class="modal-content">
      <form id="logging_form" action="<?php echo URL::to('user/user_logging_password'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">User Account Manager</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
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
        <button type="button" class="btn btn-secondary common_button_gray" data-bs-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary common_button submit_logging">SUBMIT</button>
      </div>
       @csrf
</form>
    </div>
  </div>
</div>

<div class="topbar">
  <div class="fluid-container px-5">
    <div class="col-lg-12 col-md-12 d-flex justify-content-lg-end align-items-center justify-content-around">
      <div class="me-auto">
        <a href="javascript:"><span>Call us on :</span> 1800 900009</a>
      </div>
      <a href="javascript:" class="btn btn-outline-light register_modal_btn">Register</a>
      <a href="javascript:" class="btn btn-light login_buton">Login</a>
      <a class="" href="javascript:">Contact</a>
      <div class="login_section login_section_main" style="display: none;">
        <div class="row">
          <div class="col-lg-12">
            <h3 style="margin-top: 0px;">Login</h3>
            <div style="clear: both">
            <?php
            if(Session::has('message')) { ?>
                <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
            <?php }
            ?>
            </div> 
          </div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form action="<?php echo URL::to('user/login'); ?>" method="post">
              <div class="form-group">
                <label>Enter Email</label>
                <input type="email" name="userid" id="userid" class="form-control" placeholder="Enter Email" required>
              </div>
              <div class="form-group passworddiv">
                <label>Enter Password</label>
                <input type="Password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
              </div>
              <div class="form-group">
                <label>Practice Code</label>
                <input type="text" class="form-control" placeholder="Enter Practice Code" name="practice_code" value="GBS" readonly="" style="background: #dfdfdf">
              </div>
              <div class="form-group">
                <a href="javascript:" class="loginlink" style="font-size: 14px;margin-top:15px;margin-bottom: 15px;color:#000;float:left;text-decoration: underline;">LOGGING IN FOR FIRST TIME?</a>
                <a href="javascript:" class="havepassword" style="font-size: 13px;display: none">Have Password?</a>
                <label class="logg_text" style="font-weight: 800; color:#f00"></label>
              </div>
              <div class="form-group">
                <input type="submit" class="common_button float_right" value="LOG IN" style="font-weight: 700">
              </div>
            @csrf
</form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="header">
  <nav class="navbar navbar-expand-xl">
    <div class="container-fluid">

      <a class="navbar-brand" href="home.html">
        <img src="<?php echo URL::to('public/assets/new_home/img/Bubblelogo-white.png')?>" class="" alt="bubble logo" />
        <!-- <img src="./img/bubble-color-logo.png" class="logo logo_sm" alt="bubble logo" /> -->
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="javascript:">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:">Modules</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:">Bubble Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link access_payroll_buton" href="<?php echo URL::to('payroll_index'); ?>">Access Payroll</a>
          </li>
        </ul>
      </div>
  </nav>
  <div class="herosection">
    <div class="container">
      <h1>Complete practice management software</h1>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="leftimg">
              <img class="d-sm-none d-lg-block" src="<?php echo URL::to('public/assets/new_home/img/Circular-slidergraphic.png')?>" alt="slider graphic">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 pt-5">
          <p>For professional accounting practices to <br> manage interactivity between status, tasks <br> and clients all on one platform</p>
          <a class="moreBtn" href="#">view demo</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Main Section -->
<div class="main">
  <div class="container">
    <div class="home_content">
      <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
          <img src="<?php echo URL::to('public/assets/new_home/img/options-visual-1.png')?>" alt="visual options" class="d-sm-none d-lg-block">
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
          <div class="dboard">
            <img src="<?php echo URL::to('public/assets/new_home/img/Screen-display-graphic.png')?>" alt="display graphic">
            <p>Bubble Accounting incorporates a strong Client
                Management and Invoicing System along with associated
                tracking tools to support your practice and incorporate a
                lean process to client management & billing system</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="howitworks">
    <div class="container">
      <h2>How it works</h2>
      <div class="gridbox">
      <div class="card">
        <img src="<?php echo URL::to('public/assets/new_home/img/how-it-works-visual-1.png')?>" alt="how it works">
        <h3>Streamline workflows</h3>
        <p>Centralize workflows, automate daily routines, and eliminate manual data entry with one system of record.</p>
      </div>
      <div class="card">
        <img src="<?php echo URL::to('public/assets/new_home/img/how-it-works-visual-2.png')?>" alt="how it works">
        <h3>Focus on billable activities</h3>
        <p>Ensure accurate time recordings, and make sure every minute is always accounted for.</p>
      </div>
      <div class="card">
        <img src="<?php echo URL::to('public/assets/new_home/img/how-it-works-visual-3.png')?>" alt="how it works">
        <h3>Real-time financial insights</h3>
        <p>Monitor performance and finances, identify bottlenecks, and more with customizable dashboards and</p>
      </div>
      </div>
      <a class="moreBtn" href="javascript:">Find out More</a>
    </div>
  </div>
</div>
<!-- videosection section -->
<div class="videosection">
  <div class="container">
    <h2>Everything you need in one application</h2>
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="sec1">
          <div class="row">
            <div class="col-lg-6"><img src="<?php echo URL::to('public/assets/new_home/img/clock-cog-computer-graphic.png')?>" alt="clock cog computer"></div>
            <div class="col-lg-6"><p>
            As part of the overall Bubble Accounting System a practice carries out task on behalf of client with the ultimate view of billing the client for those tasks.
          </p></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 ps-lg-5">
        <div class="sec2">
          <div class="ratio ratio-16x9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/1tWySqrEiZA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          </div>
          
          
          <div class="imgbg">
            <img src="<?php echo URL::to('public/assets/new_home/img/pale-orange-bubble.png')?>" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- Footer section -->
<footer class="footer">
  <div class="container">
    <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6">
      <a href="#"><img src="<?php echo URL::to('public/assets/new_home/img/Bubblelogo-white.png')?>" alt="bubble logo"></a>
      <p>Unit 3E Deerpark Business Centre, Oranmore, Galway.</p>
      <ul>
        <li>
          <a href="javascript:"><span>Tel: </span>(091) 447178</a>
          <a href="javascript:"><span>Mob:</span> (087) 9188907</a>
          <a href="mailto: info@gbsco.ie"><span>Email:</span> info@gbsco.ie</a>
        </li>
      </ul>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <h3>About us</h3>
      <ul>
        <li>
          <a href="javascript:">Support</a>
          <a href="javascript:">Contact</a>
          <a href="javascript:">Careers</a>
        </li>
      </ul>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <h3>Modules</h3>
      <ul>
        <li>
          <a href="javascript:">Client & Client Invoice</a>
          <a href="javascript:">Management</a>
          <a href="javascript:">The 2 Bill Manager</a>
          <a href="javascript:">Practice Financials</a>
          <a href="javascript:">Payroll Management System</a>
          <a href="javascript:">Payroll Modern Reporting System</a>
          <a href="javascript:">Year End Manager</a>
        </li>
      </ul>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <h3>Modules</h3>
      <ul>
        <li>
          <a href="javascript:">Client Request System</a>
          <a href="javascript:">VAT Management System</a>
          <a href="javascript:">RCT System</a>
          <a href="javascript:">CRO ARD System</a>
          <a href="javascript:">Time Management Tools</a>
          <a href="javascript:">Infiles System</a>
        </li>
      </ul>
    </div>
  </div>
  </div>
  <div class="copyright">
    <p>© <?php echo date('Y'); ?> Bubble Accounting, All rights reserved.</p>
  </div>
</footer>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="<?php echo URL::to('public/assets/js/jquery.validate.js'); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>-->

<script>
$(".login_buton").click(function(){
  $(".login_section_main").slideToggle();
});
$(".register_modal_btn").click(function(){
  $(".register_modal").modal("show");
})
$.ajaxSetup({async:false});
    $( "#reg_form" ).validate({
      rules:
      {
        practice_name : {required: true},
        address1 : {required: true},
        practice_logo : {required: true},
        telephone : {required: true},
        admin_user : {required: true},
      },
      messages: {
        practice_name : { required : "Please Enter Practice Name", },
        address1 : { required : "Please Enter Address", },
        practice_logo : { required : "Please Choose the Practice Logo", },
        telephone : { required : "Please Enter Telephone Number", },
        admin_user : { required : "Please Enter Administration User", },
      }
    });

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
    });
    $(window).click(function(e) {
      if($(e.target).hasClass('loginlink'))
      {
        var email = $("#userid").val();
        if(email == ""){
          alert("Please enter the email to check whether the user is previously logged in or not.")
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/check_user_login_count'); ?>",
            type:"post",
            data:{email:email},
            dataType:"json",
            success:function(result){
              if(result['status'] == 0){
                $(".passworddiv").show();
                $(".logg_text").html(result['msg']);
                $(e.target).show();
                $(".havepassword").hide();
              }
              else if(result['status'] == 1){
                $(".passworddiv").hide();
                $(".logg_text").html(result['msg']);
                $(e.target).hide();
                $(".havepassword").show();

                $(".user_logging_modal").modal("show");
                $("#firstname_logging").val(result['firstname']);
                $("#lastname_logging").val(result['lastname']);
                $("#email_logging").val(result['email']);
                $("#hidden_user_id").val(result['user_id']);
              }
              else if(result['status'] == 2){
                $(".passworddiv").show();
                $(".logg_text").html(result['msg']);
                $(e.target).show();
                $(".havepassword").hide();
              }

              setTimeout(function(){
                $(".logg_text").html("");
              },5000)
            }
          })
        }
      }
      if($(e.target).hasClass('havepassword'))
      {
        $("#password").val("");

        $(".passworddiv").show();
        $(e.target).hide();
        $(".loginlink").show();
      }
      if($(e.target).hasClass('system_img')){
        $('#imgupload').trigger('click');
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
</script>
</body>
</html>



