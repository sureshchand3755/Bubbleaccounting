@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login-form">Login</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link reg" id="register-tab" data-bs-toggle="tab" href="#register-form">Register</a>
                </li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane fade show active" id="login-form">
                  <form method="post" id="payroll_login_form">
                    <div class="formHeading">
                      <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" alt="bubble logo" />
                      <h4>"Employer secure Login to access your Staff Payroll & Associated Reports"</h4>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Employer No</label>
                      <input type="text" name="payroll_emp_no" class="form-control payroll_emp_no" placeholder="Enter Employer No" required="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control payroll_email" placeholder="Enter Email" name="payroll_email" value="" required="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="payroll_password" class="form-control payroll_password" placeholder="Enter Password" required="" autocomplete="new-password">
                    </div>
                    <spam class="error_login" style="color:#f00"></spam>
                    <button type="button" class="btn btn-primary payroll_login_btn">Login</button>
                    @csrf
                  </form>
                </div>
                <div class="tab-pane fade" id="register-form">
                  <form method="post" id="payroll_register_form">
                    <div class="formHeading">
                      <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" alt="bubble logo" />
                      <h4>"Employer Register to access your Staff Payroll & Associated Reports"</h4>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" style="width:100%">Employer No</label>
                      <input type="text" name="payroll_reg_emp_no" class="form-control payroll_reg_emp_no" placeholder="Enter Employer No" required="" style="width:85%;float:left">
                      <input type="button" class="common_button btn btn-primary verify_employer" value="Verify" style="font-weight: 700;margin-left: 10px;width:11%">
                      <label id="payroll_reg_emp_no-error" class="error" for="payroll_reg_emp_no"></label>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Employer Name</label>
                      <input type="text" class="form-control payroll_emp_name" placeholder="Enter Employer Name" name="payroll_emp_name" value="" disabled="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control payroll_reg_email" placeholder="Enter Email" name="payroll_reg_email" value="" required="" disabled="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="payroll_reg_password" class="form-control payroll_reg_password" id="payroll_reg_password" placeholder="Enter Password" required="" autocomplete="new-password" disabled="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Confirm Password</label>
                      <input type="password" name="cpayroll_password" class="form-control cpayroll_password" placeholder="Enter Confirm Password" required="" autocomplete="new-password" disabled="">
                    </div>
                    <spam class="error_register" style="color:#f00"></spam>
                    <button type="button" class="btn btn-primary payroll_register_btn">Register</button>
                    @csrf
                  </form>
                </div>
              </div>
            </div>

          </div>
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="rtimg hide_sm">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/accesspayroll.png'); ?>" alt="Access Payroll">
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
      // submitHandler: function(form) {
      //      if (grecaptcha.getResponse()) {
      //          form.submit();
      //      } else {
      //          $(".error_captcha").html("Please confirm google captcha to proceed");
      //          return false;
      //      }
      //  },
    });

    $( "#payroll_login_form" ).validate({
      rules:
      {
        payroll_emp_no : {required: true},
        payroll_email : {required: true, email: true},
        payroll_password : {required: true},
      },
      messages: {
        payroll_emp_no : { required : "Please Enter Employer Number", },
        payroll_email : { required : "Please Enter Email Address", email : "Please enter the valid Email Address" },
        payroll_password : { required : "Please enter the Password", },
      }
    });
    $( "#payroll_register_form" ).validate({
      rules:
      {
        payroll_reg_emp_no : {required: true},
        payroll_reg_email : {required: true, email: true},
        payroll_reg_password : {required: true},
        cpayroll_password : {required: true, equalTo: "#payroll_reg_password"},
      },
      messages: {
        payroll_reg_emp_no : { required : "Please Enter Employer Number", },
        payroll_reg_email : { required : "Please Enter Email Address", email : "Please enter the valid Email Address" },
        payroll_reg_password : { required : "Please enter the Password", },
      }
    });

    $(window).click(function(e) {
        if($(e.target).hasClass('payroll_login_btn')){
          if($("#payroll_login_form").valid()){
            var payroll_emp_no = $(".payroll_emp_no").val();
            var payroll_email = $(".payroll_email").val();
            var payroll_password = $(".payroll_password").val();

            $.ajax({
              url:"<?php echo URL::to('payroll_login'); ?>",
              type:"post",
              data:{payroll_emp_no:payroll_emp_no,payroll_email:payroll_email,payroll_password:payroll_password},
              dataType:"json",
              success:function(result){
                if(result['error'] == "1"){
                  $(".error_login").html(result['message']);
                }
                else{
                  $(".access_payroll_modal").modal("hide");
                  window.location.replace("<?php echo URL::to('payroll/dashboard'); ?>");
                  //alert(result['message']);
                }
              }
            })
          }
        }
        if(e.target.id == "home-tab"){
            $(".payroll_emp_no").val('');
            $(".payroll_email").val('');
            $(".payroll_password").val('');

            $(".payroll_reg_emp_no").val('');
            $(".payroll_reg_email").val('');
            $(".payroll_reg_password").val('');
            $(".cpayroll_password").val('');
            $(".payroll_emp_name").val('');

            $(".emp_name_div").hide();

            $(".payroll_reg_email").prop('disabled',true);
            $(".payroll_reg_password").prop('disabled',true);
            $(".cpayroll_password").prop('disabled',true);

            $(".error_login").html("");
            $(".error_register").html("");
        }
        if(e.target.id == "profile-tab"){
            $(".payroll_emp_no").val('');
            $(".payroll_email").val('');
            $(".payroll_password").val('');

            $(".payroll_reg_emp_no").val('');
            $(".payroll_reg_email").val('');
            $(".payroll_reg_password").val('');
            $(".cpayroll_password").val('');
            $(".payroll_emp_name").val('');

            $(".emp_name_div").hide();

            $(".payroll_reg_email").prop('disabled',true);
            $(".payroll_reg_password").prop('disabled',true);
            $(".cpayroll_password").prop('disabled',true);

            $(".error_login").html("");
            $(".error_register").html("");
        }
        if($(e.target).hasClass('access_payroll_buton')){
            $(".payroll_emp_no").val('');
            $(".payroll_email").val('');
            $(".payroll_password").val('');

            $(".payroll_reg_emp_no").val('');
            $(".payroll_reg_email").val('');
            $(".payroll_reg_password").val('');
            $(".cpayroll_password").val('');
            $(".payroll_emp_name").val('');

            $(".emp_name_div").hide();

            $(".payroll_reg_email").prop('disabled',true);
            $(".payroll_reg_password").prop('disabled',true);
            $(".cpayroll_password").prop('disabled',true);

            $(".error_login").html("");
            $(".error_register").html("");
        }
        if($(e.target).hasClass('verify_employer'))
        {
          var empno = $(".payroll_reg_emp_no").val();
          if(empno == ""){
            $(".error_register").html("Please enter the Employer No");
            $(".payroll_reg_email").prop('disabled',true);
            $(".payroll_reg_password").prop('disabled',true);
            $(".cpayroll_password").prop('disabled',true);
            return false;
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('verify_emp_no'); ?>",
              type:"post",
              data:{empno:empno},
              success:function(result){
                if(result == ""){
                  $(".error_register").html("The Employer No doesn't exists.");
                  $(".emp_name_div").hide();
                  $(".payroll_emp_name").val("");
                  $(".payroll_reg_email").prop('disabled',true);
                  $(".payroll_reg_password").prop('disabled',true);
                  $(".cpayroll_password").prop('disabled',true);
                }
                else{
                  $(".error_register").html("");
                  $(".emp_name_div").show();
                  $(".payroll_emp_name").val(result);
                  $(".payroll_reg_email").prop('disabled',false);
                  $(".payroll_reg_password").prop('disabled',false);
                  $(".cpayroll_password").prop('disabled',false);
                }
              }
            })
          }
        }
        if($(e.target).hasClass('payroll_register_btn')){
          if($("#payroll_register_form").valid()){
            var payroll_emp_no = $(".payroll_reg_emp_no").val();
            var payroll_email = $(".payroll_reg_email").val();
            var payroll_password = $(".payroll_reg_password").val();

            $.ajax({
              url:"<?php echo URL::to('payroll_register'); ?>",
              type:"post",
              data:{payroll_emp_no:payroll_emp_no,payroll_email:payroll_email,payroll_password:payroll_password},
              dataType:"json",
              success:function(result){
                if(result['error'] == "1"){
                  $(".error_register").html(result['message']);
                }
                else{
                  $(".access_payroll_modal").modal("hide");
                  alert(result['message']);
                  window.location.reload();
                }
              }
            })
          }
        }
    });
</script>
@stop