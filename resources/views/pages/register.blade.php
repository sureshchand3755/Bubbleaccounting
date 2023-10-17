@extends('homeheader')
@section('content')
<style>
.success { color: green; }
</style>
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="sign_sec form" style="padding:30px">
              <form  id="validate_form" method="post" enctype="multipart/form-data">
                <div class="formHeading">
                  <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" class="mb-3" width="90" alt="bubble logo" />
                </div>
                <h4>We will take and validate your email address as the initial part of the registration process</h4>
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3">
                      <label for="user_email" class="form-label">Enter Email Address:</label>
                      <input type="email" name="user_email" id="user_email" class="form-control"  placeholder="Enter Email Address">
                      <label id="user_email-error" class="error" for="user_email" style=""></label>
                    </div>
                    <div class="mb-3">
                      <label for="confirm_user_email" class="form-label">Confirm Email Address:</label>
                      <input type="email" name="confirm_user_email" id="confirm_user_email" class="form-control"  placeholder="Confirm Email Address">
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-primary validate_email_address">Validate Your Email Address</button>
                 @csrf
              </form>
              <form  id="verify_form" method="post" enctype="multipart/form-data" style="display:none">
                <div class="formHeading">
                  <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" class="mb-3" width="90" alt="bubble logo" />
                </div>
                <h4 style="margin-bottom: 6%;">An Email will be received to your email address from sandra@bubble.ie with a 4 digit validation code.  Please enter the validation code here.</h4>
                
                <spam style="font-size:18px;font-weight:500;float:left;margin-top:7px;margin-right: 10px;">Enter OTP:</spam>
                <input type="text" name="otp_input" id="otp_input" class="form-control otp_input" max-length="4" value="" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  onKeyPress="if(this.value.length==4) return false;" style="width: 36%;font-size: 18px;letter-spacing: 16px;text-align: center;margin-left:10px;float:left">
                <a href="javascript:" class="resent_otp" style="font-size: 18px;float: left;margin-left: 10px;background: #3db1e5;padding: 8px 16px;color: #fff;border-radius: 5px;">Resend OTP</a>
                <label id="otp_input-error" class="error" for="otp_input" style=""></label>
                <label id="resend_email_success" class="success resend_email_success"></label>
                <button type="button" class="btn btn-primary verify_otp_btn" style="margin-top:10px">Verify OTP</button>
                 @csrf
              </form>
              <form  id="reg_form" action="<?php echo URL::to('user/user_registration'); ?>" method="post" enctype="multipart/form-data" style="display:none">
                <div class="formHeading">
                  <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" class="mb-3" width="90" alt="bubble logo" />
                </div>
                <h4>Create an Account</h4>
                <div style="clear: both">
                  <?php
                  if(Session::has('message')) { ?>
                      <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
                  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="practice_name" class="form-label">Practice Name</label>
                      <input type="text" name="practice_name" id="practice_name" class="form-control"  placeholder="Practice Name">
                    </div>
                    <div class="mb-3">
                      <label for="practice_code" class="form-label">Practice Code</label>
                      <input type="text" name="practice_code" id="practice_code" class="form-control" placeholder="Practice Code auto-generated" readonly required style="background: #f2f5fc">
                    </div>
                    <div class="mb-3">
                      <label for="telephone" class="form-label">Telephone Number</label>
                      <input type="text" name="telephone" class="form-control" id="telephone" placeholder="Enter Telephone Number" required>
                    </div>
                    <div class="mb-3">
                      <label for="admin_firstname" class="form-label">Admin Firstname</label>
                      <input type="text" name="admin_firstname" id="admin_firstname" class="form-control" placeholder="Enter Admin Firstname" required>
                    </div>
                    <div class="mb-3">
                      <label for="admin_password" class="form-label">Password</label>
                      <input type="text" name="admin_password" id="admin_password" class="form-control" placeholder="Enter Admin Pasword" required>
                    </div>
                    <div class="mb-3">
                      <label for="admin_email" class="form-label">Admin Email Address</label>
                      <input type="text" name="admin_email" id="admin_email" class="form-control" placeholder="Enter Admin Email Address" required readonly style="background: #f2f5fc">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="practice_logo" class="form-label">Practice Logo</label>
                      <input type="file" name="practice_logo" id="practice_logo" class="form-control" placeholder="Choose Practice Logo" style="background: #fff;">
                    </div>
                    <div class="mb-3">
                      <label for="address" class="form-label">Address</label>
                      <input type="text" name="address1" id="address1" class="form-control" id="address" placeholder="Enter Address" required>
                      <input type="text" name="address2" id="address2" class="form-control my-1" placeholder="Enter Address">
                      <input type="text" name="address3" id="address3" class="form-control" placeholder="Enter Address">
                      <input type="text" name="address4" id="address4" class="form-control" placeholder="Enter Address" style="display: none">
                    </div>
                    <div class="mb-3">
                      <label for="admin_surname" class="form-label">Admin Surname</label>
                      <input type="text" name="admin_surname" id="admin_surname" class="form-control" placeholder="Enter Admin Surname" required>
                    </div>
                    <div class="mb-3">
                      <label for="admin_cpassword" class="form-label">Confirm Password</label>
                      <input type="password" name="admin_cpassword" id="admin_cpassword" class="form-control" placeholder="Enter Admin Confirm Pasword" required>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="verified_otp" id="verified_otp" value="">
                <button type="submit" class="btn btn-primary">Register</button>
                 @csrf
              </form>
            </div>

          </div>
          <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/bubble_register.png'); ?>" alt="Bubble ie login">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
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
  $("#practice_name").blur(function(e) {
    var value = $(this).val();
    if(value != "" && value.length > 2) {
      $.ajax({
        url:"<?php echo URL::to('user/check_practice_name'); ?>",
        type:"post",
        data:{value:value},
        success:function(result) {
            $("#practice_code").val(result);
        }
      })
    }
  })
  $.ajaxSetup({async:false});
      $( "#reg_form" ).validate({
        rules:
        {
          practice_name : {required: true},
          practice_code : {required: true},
          address1 : {required: true},
          telephone : {required: true},
          admin_firstname : {required: true},
          admin_surname : {required: true},
          admin_email : {required: true, email: true},
          admin_password : {required: true},
          admin_cpassword: {required: true, equalTo: "#admin_password"},
        },
        messages: {
          practice_name : { required : "Please Enter Practice Name", },
          practice_code : { required : "Practice Code is Required", },
          address1 : { required : "Please Enter Address", },
          telephone : { required : "Please Enter Telephone Number", },
          admin_firstname : { required : "Please Enter Firstname", },
          admin_surname : { required : "Please Enter Surname", },
          admin_email : { required : "Please Enter Email", email: "Please Enter Valid Email"},
          admin_password: {required: "Please enter your Password"},
          admin_cpassword: {required: "Please Confirm your Password", equalTo: "Password does not match"},
        }
      });
      $( "#validate_form" ).validate({
        rules:
        {
          user_email : {required: true, email: true},
          confirm_user_email : {required: true, email: true, equalTo:"#user_email"},
        },
        messages: {
          user_email : { required : "Please Enter Email Address", email:"Please Enter your valid Email Address" },
          confirm_user_email : { required : "Please Confirm Email Address", email:"Please Enter your valid Email Address", equalTo:"Confirm Email Address Should be same as Email Address" },
        }
      });
      $( "#verify_form" ).validate({
        rules:
        {
          otp_input : {required: true, number: true},
        },
        messages: {
          otp_input : { required : "Please Enter OTP to Verify", number:"Please enter 4 Numeric values" },
        }
      });
      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            $('.system_img').attr('src', e.target.result);
          };

          reader.readAsDataURL(input.files[0]);
        }
      }
      $(window).click(function(e) {
        if($(e.target).hasClass('resent_otp')) {
          var email_address = $("#user_email").val();
          $.ajax({
            url:"<?php echo URL::to('user/check_email_address'); ?>",
            type:"post",
            data:{email_address:email_address},
            success:function(result) {
              $(".resend_email_success").html("A 4 Digit Validation code has been resent to "+email_address+"");
              $("#user_email-error").html("");
              $("#user_email-error").hide();
              $("#validate_form").hide();
              $("#verify_form").show();
              $("#verified_otp").val("");
            }
          })
        }
        if($(e.target).hasClass('verify_otp_btn')) {
          if($("#verify_form").valid()) {
            var otp = $("#otp_input").val();
            var user_email = $("#user_email").val();
            $(".resend_email_success").html("");
            $.ajax({
              url:"<?php echo URL::to('user/verify_otp_register'); ?>",
              type:"post",
              data:{otp:otp, user_email:user_email},
              success:function(result) {
                if(result == 1) {
                  $("#otp_input-error").html("You have entered the Invalid OTP.");
                  $("#otp_input-error").show();
                }
                else{
                  $("#admin_email").val(user_email);
                  $("#verified_otp").val(otp);
                  $("#otp_input-error").html("");
                  $("#otp_input-error").hide();
                  $("#validate_form").hide();
                  $("#verify_form").hide();
                  $("#reg_form").show();
                }
              }
            })
          }
        }
        if($(e.target).hasClass('validate_email_address')) {
          if($("#validate_form").valid()) {
            $(".resend_email_success").html("");
            var email_address = $("#user_email").val();
            $.ajax({
              url:"<?php echo URL::to('user/check_email_address'); ?>",
              type:"post",
              data:{email_address:email_address},
              success:function(result) {
                if(result == 1) {
                  $("#user_email-error").html("Email Address already Exists");
                  $("#user_email-error").show();
                  return false;
                }
                else{
                  $("#user_email-error").html("");
                  $("#user_email-error").hide();
                  $("#validate_form").hide();
                  $("#verify_form").show();
                  $("#verified_otp").val("");
                }
              }
            })
          }
        }
      })
  </script>
@stop