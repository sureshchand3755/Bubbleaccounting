<html>
<head>
<title>Bubble - Facilty</title>
</head>
<style>
@import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
.login-block{
    background: #2e9fe1;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #b5e0f2, #2e9fe1);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #b5e0f2, #2e9fe1); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
float:left;
width:100%;
padding : 50px 0;
}
.banner-sec{background:url('../public/assets/images/login_screen/login_bg_01.jpg')  no-repeat left bottom; background-size:cover; min-height:500px; border-radius: 0 10px 10px 0; padding:0;}
.container{background:#fff; border-radius: 10px; box-shadow:15px 20px 0px rgba(0,0,0,0.1);}
.carousel-inner{border-radius:0 10px 10px 0;}
.carousel-caption{text-align:left; left:5%;}
.login-sec{padding: 50px 30px; position:relative;}
.login-sec .copy-text{position:absolute; width:80%; bottom:20px; font-size:13px; text-align:center;}
.login-sec .copy-text i{color:#FEB58A;}
.login-sec .copy-text a{color:#E36262;}
.login-sec h2{margin-bottom:30px; font-weight:800; font-size:30px; color: #2e9fe1;}
.login-sec h2:after{content:" "; width:100px; height:5px; background:#FEB58A; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
.btn-login{background: #2e9fe1; color:#fff; font-weight:600;}
.banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
.banner-text h2{color:#fff; font-weight:600;}
.banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
.banner-text p{color:#fff;}
</style>
<body>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.validate.js') ?>"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
  .error{ color:#f00; }
</style>
<section class="login-block" style="height: 100%;">
  <div class="container">
    <div class="row" style="height: 670px;margin-top: 7%;">
      <div class="col-md-4 login-sec">
        <div style="width: 100%; height: auto; text-align: center; float: left; margin-bottom: 20px;"><img src="<?php echo URL::to('public/assets/images/bubble-color-logo-horizontal.png')?>" style="width: 60%;"></div>
        <h2 class="text-center">Reset Password</h2>
        <div style="clear: both">
          <?php
          if(Session::has('message')) { ?>
              <p class="alert alert-success"><?php echo Session::get('message'); ?><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a></p>
          <?php }
          ?>
          <?php
          if(Session::has('error')) { ?>
              <p class="alert alert-danger"><?php echo Session::get('error'); ?><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a></p>
          <?php }
          ?>
        </div> 
        <form action="<?php echo URL::to('facility/update_reset_password'); ?>" method="post" class="login-form">
          <div class="form-group">
            <label for="exampleInputEmail1" class="text-uppercase">New Password:</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter New Password" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1" class="text-uppercase">Conirm Password:</label>
            <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Enter Confirm Password" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1" class="text-uppercase">Enter OTP:</label>
            <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter OTP" required>
          </div>
          <div class="form-check" style="padding: 0px;">
            <input type="hidden" name="verify_email" id="verify_email" value="<?php echo $_GET['verify']; ?>">
            <button type="submit" class="btn btn-login ">Submit</button>
            <label class="form-check-label float-right">
              <a href="<?php echo URL::to('facility'); ?>"><strong>Back to Login</strong></a>
            </label>
            
          </div>  
          
          @csrf
        </form>
      </div>
      <div class="col-md-8 banner-sec">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item">
              <img class="d-block img-fluid" src="<?php echo URL::to('public/assets/images/login_screen/login_bg_01.jpg'); ?>" alt="First slide">
            </div>
          </div>     
        </div>
      </div>
    </div>
  </div>
</section>

<script>
$( ".login-form" ).validate({
    rules:
    {
      password : {required: true},
      cpassword: {required: true, equalTo: "#password"},
      otp: {required: true},
    },
    messages: {
      password: {required: "Please enter your Password"},
      cpassword: {required: "Please Confirm your Password", equalTo: "Password does not match"},
      otp: {required: "Please enter OTP to Verify"},
    }
});
</script>
</body>
</html>



