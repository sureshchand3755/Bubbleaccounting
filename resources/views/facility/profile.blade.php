@extends('facilityheader')
@section('content')
<style>
.sub_title{
    font-size: 18px;
    margin-bottom: 20px;

}
.border{
    padding: 10px;
    line-height: 3;
}
.error{
      color: #f00;
    line-height: 1;
}
.top_row{
  z-index:99999;
}
.breadcrumb{
  width: 30%;
float: right;
font-size: 18px;
font-weight: 600;
text-align: right;
}
.breadcrumb li {
  font-size: 20px;
  font-weight:600;
}
</style>
<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
      <div class="col-md-12">
        <h2>Admin Profile

          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
          </ol>
        </h2>
        <hr>
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
        <div class="col-lg-6 text-left padding_00" style="border: 1px solid #c3c3c3;padding: 15px;border-radius: 5px;background: #fff">
          <h4><strong>Update Settings</strong></h4><hr style="border-bottom: 1px solid #c3c3c3">
          <form action="<?php echo URL::to('facility/update_facility_setting'); ?>" method="post" id="update_facility_form" autocomplete="off">
            <div class="form-group">
              <label>Enter Email:</label>
              <input type="email" name="admin_username" id="admin_username" class="form-control" value="<?php echo $facility_details->email; ?>" required>                          
            </div> 
            
            <div class="form-group">
              <label>Enter Practice Code:</label>
              <input type="text" name="admin_practice_code" id="admin_practice_code" class="form-control" value="<?php echo $facility_details->practice_code; ?>" required>                        
            </div> 

            <div class="form-group">
              <label>New Password:</label>
              <input type="password" name="newadmin_password" id="newadmin_password" class="form-control" value="" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" style="background: #fff">                       
            </div> 

            <div class="form-group">
              <label>Confirm Password:</label>
              <input type="password" name="confirmadmin_password" id="confirmadmin_password" class="form-control" value="">                      
            </div> 
            
            <div class="row">
              <div class="col-md-12" style="text-align:center;margin-top: 10px">
                  <input type="submit" name="admin_submit" id="admin_submit" class="btn btn-primary" value="Update Settings" style="width:100%">
              </div>
            </div>
          @csrf
          </form>
        </div>
      </div>

      
  </div>
</div>
</div>
<script>
$("#update_facility_form" ).validate({

    rules: {
        admin_username : {required:true,},

        admin_practice_code : {required:true,},

        confirmadmin_password : {equalTo: "#newadmin_password"},    

    },

    messages: {

        admin_username : "Username is required",

        admin_practice_code : "Prctice Code is required",

        newadmin_password : "New Password is required",

        confirmadmin_password : {

            required: "Confirm Password is required",

            equalTo : "Does not Match the password",

        },

    },

});
$("#update_user_form" ).validate({

    rules: {
        user_username : {required:true,},

        newuser_password : {required: true,},

        confirmuser_password : {required: true, equalTo: newuser_password},    

    },

    messages: {

        user_username : "Username is required",

        newuser_password : "New Password is required",

        confirmuser_password : {

            required: "Confirm Password is required",

            equalTo : "Does not Match the password",

        },

    },

});
</script>
@stop