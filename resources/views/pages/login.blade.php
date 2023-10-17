@extends('homeheader')
@section('content')
    <div class="modal fade register_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" style="margin-top: 5%;">
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
  <div class="modal-dialog modal-md" style="margin-top: 5%;">
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

    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="sign_sec">
              <form id="login-form" class="form" action="<?php echo URL::to('user/login'); ?>" method="post">
                <div class="formHeading">
                  <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" alt="bubble logo" />
                  <p>Login into Bubble.ie enterprise level Accountancy Practice Management Solution</p>
                </div>
                <div style="clear: both">
                  <?php
                  if(Session::has('message')) { ?>
                      <p class="alert alert-info" style="font-size: 14px;"><?php echo Session::get('message'); ?>
                        <button type="button" class="close close_alert" data-dismiss="alert" aria-label="Close" style="float: right;background: #cff4fc;border: 0px;font-weight: 600;font-size: 15px;">
                          <span aria-hidden="true" class="close_alert">&times;</span>
                      </button>
                      </p>
                  <?php } ?>
                </div>
                <div class="mb-3">
                  <label for="login-email" class="form-label">Email</label>
                  <input type="email" name="userid" id="userid" class="form-control" placeholder="Enter Email" required>
                </div>
                <div class="mb-3 passworddiv">
                  <label for="login-password" class="form-label">Password</label>
                  <input type="Password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <div class="mb-3">
                  <label for="practice_code" class="form-label">Practice Code</label>
                  <input type="text" name="practice_code" class="form-control practice_code" id="practice_code" placeholder="Enter Practice Code" required>
                </div>
                <div class="mb-3">
                  <a href="javascript:" class="loginlink" style="font-size: 14px;margin-top:15px;margin-bottom: 15px;color:#000;float:left;text-decoration: underline;">LOGGING IN FOR FIRST TIME?</a>
                  <a href="javascript:" class="havepassword" style="font-size: 13px;display: none">Have Password?</a>
                  <label class="logg_text" style="font-weight: 800; color:#f00;width: 50%;margin-left: 4%;margin-top: 13px;"></label>
                </div>
                <button type="button" class="btn btn-primary login_btn">Login</button>
                @csrf
              </form>
            </div>

          </div>
          <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/bubble_login.png'); ?>" alt="Bubble ie login">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  <script>
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

      $("#login-form").validate({
        rules:
        {
          userid : {required: true},
          password : {required: true},
          practice_code : {required: true},
        },
        messages: {
          userid : { required : "Please Enter your Email Address", },
          password : { required : "Please Enter your Password", },
          practice_code : {required: "Please Enter the Practice Code", },
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
      // $("#userid").blur(function(e) {
      //   var value = $(this).val();
      //   $.ajax({
      //     url:"<?php echo URL::to('get_user_practice_code'); ?>",
      //     type:"post",
      //     data:{value:value},
      //     success:function(result) {
      //         $("#practice_code").html(result);
      //         if($("#practice_code").val() == "") {
      //           $("#practice_code").css('color',"#f00");
      //         }
      //         else{
      //           $("#practice_code").css('color',"#000");
      //         }
      //     }
      //   })
      // });
      $(window).click(function(e) {
        if($(e.target).hasClass('login_btn')) {
          if($("#login-form").valid()) {
            $("#login-form").submit();
          }
        }
        if($(e.target).hasClass('close_alert')) {
          $(".alert-info").hide();
        }
        if($(e.target).hasClass('loginlink'))
        {
          var email = $("#userid").val();
          var practice_code = $("#practice_code").val();
          if(email == ""){
            alert("Please enter the email to check whether the user is previously logged in or not.")
          }
          else if(practice_code == "") {
            alert("Please enter your Practice code to check if you are logging in for the First time or not");
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('user/check_user_login_count'); ?>",
              type:"post",
              data:{email:email,practice_code:practice_code},
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
  @stop
