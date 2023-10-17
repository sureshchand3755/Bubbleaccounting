@extends('adminuserheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Vendors Scripts -->
<!-- v1.0.0 -->


<!-- <script src="<?php //echo URL::to('public/assets/vendors/html5-form-validation/dist/jquery.validation.min.js') ?>"></script> -->
<script src="<?php echo URL::to('public/assets/vendors/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo URL::to('public/assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>


<style>
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
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.admin_content_section {margin-top:20px;}
.error{
  color:#f00;
}
.modal-header button.close{
    padding: 15px !important;
}
.alert{
  padding: 12px 15px !important;
}
.modal-header {
  padding: 0px 4px 0px 19px !important;
}
.menu-logo{
  background-size: 29px 22px;    
  padding-left: 35px;         
  background-repeat: no-repeat;
  background-image:url(<?php echo URL::to('public/assets/images/bubble-icon-color.png'); ?>);
  
}
.modal-header .menu-logo{
        padding-top: 15px;
        padding-bottom: 15px;
        background-position: left;
  }
</style>

<?php
$settings = DB::table('settings')->where('source','practice')->first();
if(($settings))
{
  $settingsval = unserialize($settings->settings);
}
?>

<div class="modal fade add_edit_user" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form id="add_user_form" action="<?php echo URL::to('user/add_user'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title menu-logo">Add User</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter First Name:</label><input type="text" name="name" class="form-control" placeholder="Enter First Name" required></td>
                </tr>
                <tr>
                    <td><label>Enter Last Name:</label><input type="text" name="lname" class="form-control" placeholder="Enter Last Name" required></td>
                </tr>
                <tr>
                    <td><label>Enter Email ID:</label><input type="email" name="email" class="form-control" placeholder="Enter Email ID" required></td>
                </tr>
                <tr>
                    <td><label>Enter Initial:</label><input type="text" name="initial" class="form-control" placeholder="Enter Initial" required></td>
                </tr>
                <tr>
                    <td><label>Enter Practice Code:</label><input type="text" name="practice_code" class="form-control" placeholder="Enter Practice Code" value="<?php echo (isset($settingsval['practice_code']))?$settingsval['practice_code']:'GBS'; ?>" required readonly style="background-color: #dfdfdf !important;"></td>
                </tr>

            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button float_right">
          </div>
        </div>
    @csrf
</form>

    <form id="edit_user_form" action="<?php echo URL::to('user/update_user'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title menu-logo">Edit User</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter First Name:</label><input type="text" name="name" class="form-control name_class" placeholder="Enter First Name" required></td>
                </tr>
                <tr>
                    <td><label>Enter Last Name:</label><input type="text" name="lname" class="form-control lname_class" placeholder="Enter Last Name" required></td>
                </tr>
                <tr>
                    <td><label>Enter Email ID:</label><input type="email" name="email" class="form-control email_class" placeholder="Enter Email ID" required>
                      <input type="hidden" name="id" class="form-control name_id"></td>
                </tr>
                <tr>
                    <td><label>Enter Initial:</label><input type="text" name="initial" class="form-control initial_class" placeholder="Enter Initial" required>
                </tr>
                <tr class="password_div">
                    <td><label>Enter New Password:</label><input type="text" name="new_password" class="form-control new_password" id="new_password" placeholder="Enter New Password" value=""></td>
                </tr>
                <tr class="password_div">
                    <td><label>Confirm New Password:</label><input type="password" name="confirm_password" class="form-control confirm_password" id="confirm_password" placeholder="Confirm New Password" value=""></td>
                </tr>
                <tr>
                    <td><label>Enter Practice Code:</label><input type="text" name="practice_code" class="form-control practice_class" placeholder="Enter Practice Code" value="" required readonly style="background-color: #dfdfdf !important;"></td>
                </tr>
                <tr class="not_password_div">
                    <td style="color:blue">Note: Create Password is Disabled as this User has not Logged-In yet for the first time.</td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Update" class="btn common_black_button">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>



<style type="text/css">
.table2 tr td{border-top: 0px !important; padding-left: 0px !important; line-height: 30px !important;}
.form-control[readonly]{background-color: #dcdcdc !important}
</style>

<div class="modal fade costing_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
    <form action="<?php echo URL::to('user/add_user'); ?>" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title menu-logo"><span class="staff_name"></span> Costing</h4>
          </div>
          <div class="modal-body">

            <div class="row">

            <div class="col-lg-5 ">
              <b>New Cost</b>
              <table class="table" style="margin-top:10px">
                <tr>
                  <td style="border:0px solid">From</td>
                  <td style="border:0px solid;width: 200px;">
                    <div class="form-group date_group">
                        <label class="input-group">
                            <input type="text" class="form-control datepicker-only-init" id="from_date" name="from_date" style="font-weight: 500;" required placeholder="Ex: <?php echo date('F d, Y'); ?>" autocomplete="off"/>
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </label>
                    </div>
                  </td>
                  <td style="border:0px solid"></td>
                </tr>
                <tr>
                  <td style="border:0px solid">Cost</td>
                  <td style="border:0px solid"><input type="number" class="form-control" id="new_cost" ></td>
                  <td style="border:0px solid"><a href="javascript:" style="width: 100%" class="common_black_button float_right apply_button">Apply</a></td>
                </tr>
              </table>
              <div class="staff_cost_details">
              
            </div>
            </div>

            

            <div class="col-lg-7" style="border-left: 1px solid #dfdfdf;padding-left: 55px;"><b>Calculate Cost</b>
            <table class="table table2">
                <tr>
                    <td>Annual Base Salary:</td>
                    <td><input type="number" name="name" class="form-control annual_class" placeholder="Enter Annual Base Salary" required></td>
                    <td style="width: 15px;"></td>
                </tr>
                <tr>
                    <td>Annual Bonus Payments:</td>
                    <td><input type="number" name="name" class="form-control annual_bonus_class" placeholder="Enter Annual Bonus Payments" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Other Annual Additions to Salary:</td>
                    <td><input type="number" name="name" class="form-control annual_other_class" placeholder="Enter Other Annual Additions to Salary" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Total Salary:</td>
                    <td><input type="number" name="name" class="form-control total_salary_class" placeholder="Total Salary" required readonly></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Standard Hours Per week:</td>
                    <td><input type="number" name="name" class="form-control standard_hour_class" placeholder="Enter Standard Hours Per week" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Holiday Days per year:</td>
                    <td><input type="number" name="name" class="form-control holidays_class" placeholder="Enter Holiday Days per year" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Rate of Social Insurance:</td>
                    <td><input type="number" name="name" class="form-control rate_class" placeholder="Enter Rate of Social Insurance" required></td>
                    <td>%</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Summary:</b></td>
                </tr>
                <tr>
                    <td>Total Salary:</td>
                    <td><input type="number" name="name" class="form-control total_salary_class" placeholder="Total Salary" required readonly></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Social Insurance:</td>
                    <td><input type="number" name="name" class="form-control insurance_class" placeholder="Social Insurance" required readonly></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Cost Per hour:</td>
                    <td><input type="number" name="name" class="form-control cost_per_class" placeholder="Cost Per hour
" required readonly></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Holiday Cost Per Hour:</td>
                    <td><input type="number" name="name" class="form-control holiday_cost_class" placeholder="Holiday Cost Per Hour
" required readonly></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Final Cost Per hour:</td>
                    <td><input type="number" name="name" class="form-control final_cost_class" placeholder="Final Cost Per hour
" required readonly></td>
                    <td></td>
                </tr>
            </table>
            </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" class="user_id_class" id="user_id" value="" name="">
          </div>
        </div>
    @csrf
</form>
  </div>
</div>




<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
    <div>
      <?php
      if(Session::has('message')) { ?>
          <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
    <div class="col-lg-12 padding_00">
      <div class="col-lg-6 text-left padding_00">
        <div class="sub_title">Manage User</div>
      </div>
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right">Add New User</a>
        <a href="<?php echo URL::to('user/task_overview?framebox=1'); ?>" class="common_black_button float_right">Go to Task Overview</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">First Name</th>
              <th style="text-align: left;">Last Name</th>
              <th style="text-align: left;">Email</th>
              <th style="text-align: left;">Password</th>
              <th style="text-align: left;">Initial</th>
              <th style="text-align: right;">Current Cost</th>
              <th style="text-align: left;">Log In's</th>
              <th style="text-align: left;">Practice</th>
              <th style="text-align: left;" width="15%">Action</th>
              
          </tr>
        </thead>
        <tbody>
          <?php
            $i=1;
            if(($userlist)){              
              foreach($userlist as $user){
          ?>
          <tr>            
            <td style="text-align: left;"><?php echo $i;?></td>            
            <td style="text-align: left;"><a href="javascript:" class="costing_class" data-element="<?php echo base64_encode($user->user_id) ?>"><?php echo $user->firstname; ?></a></td>
            <td style="text-align: left;"><?php echo $user->lastname; ?></td>
            <td style="text-align: left;"><?php echo $user->email; ?></td>
            <td style="text-align: left;"><?php echo ($user->password != "") ? Crypt::decrypt($user->password) : ""; ?></td>
            <td style="text-align: left;"><?php echo $user->initial; ?></td>
            <td style="text-align: right;">&euro; 
                <?php
                $check_date_from = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user->user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) DESC LIMIT 1');
                if(($check_date_from))
                {
                  echo number_format_invoice($check_date_from[0]->cost);
                }
                else{
                  echo 0.00;
                }
                ?>
            </td>
            <td style="text-align: right;"><?php echo $user->logins; ?></td>
            <td style="text-align: left;"><?php echo $user->practice; ?></td>
            <td style="text-align: left;">
                <?php
                if($user->user_status ==0){
                  echo'<a href="'.URL::to('user/deactive_user',base64_encode($user->user_id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                }
                else{
                  echo'<a href="'.URL::to('user/active_user',base64_encode($user->user_id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                }
                ?>

                &nbsp; &nbsp;

                <a href="#" id="<?php echo base64_encode($user->user_id); ?>" class="fa fa-pencil-square editclass"></a>&nbsp; &nbsp;

                <?php
                if($user->disabled ==0){
                  ?>
                  <a href="<?php echo URL::to('user/delete_user/'.base64_encode($user->user_id).'?status=1'); ?>" class="delete_user" title="Disable this User"><i class="fa fa-user delete_user" aria-hidden="true" style="color:green"></i></a>&nbsp; &nbsp;
                  <?php
                }
                else{
                  ?>
                  <a href="<?php echo URL::to('user/delete_user/'.base64_encode($user->user_id).'?status=0');  ?>" class="enable_user" title="Enable this User"><i class="fa fa-ban enable_user" aria-hidden="true" style="color:#f00"></i></a>&nbsp; &nbsp;
                  <?php
                }
                ?>
                
                <a href="javascript:"><i class="fa fa-eur costing_class" data-element="<?php echo base64_encode($user->user_id) ?>" aria-hidden="true"></i></a>
            </td>
          </tr>
          <?php
              $i++;
              }              
            }
            else{
              echo'<tr><td colspan="5" align="center">Empty</td></tr>';
            }
          ?>
          
        </tbody>
    </table>
  </div>
</div>
</div>
<div class="modal_load"></div>
<script>
$.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
  });
$(".costing_modal").on('hidden.bs.modal', function(){
    $("body").addClass("loading");
    setTimeout( function() {
      window.location.reload();
    },1000);
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Are You Sure you want to Disable this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  if($(e.target).hasClass('enable_user'))
  {
    var r = confirm("Are You Sure you want to Enable this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  if($(e.target).hasClass('addclass')) {
    $(".add_edit_user").modal("show");
    $(".addsp").show();
    $(".editsp").hide();
  }
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    console.log(editid);
    $.ajax({
        url: "<?php echo URL::to('user/edit_user') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".add_edit_user").modal("show");
           $(".editsp").show();
           $(".addsp").hide();
           $(".name_class").val(result['name']);
           $(".lname_class").val(result['lname']);
           $(".email_class").val(result['email']);
           $(".initial_class").val(result['initial']);
           $(".practice_class").val(result['practice']);
           $(".name_id").val(result['id']);
           if(result['login_count'] > 0) {
            $(".password_div").show();
            $(".not_password_div").hide();
           }
           else{
            $(".password_div").hide();
            $(".not_password_div").show();
           }
      }
    })
  }
  if($(e.target).hasClass('delete_cost'))
  {
    var r = confirm("Are You Sure want to delete this cost?");
    if (r == true) {
      var user_id = $("#user_id").val();
      var id = $(e.target).attr("data-element");
      $.ajax({
      url: "<?php echo URL::to('user/manage_user_costing_delete') ?>",
      dataType:"json",
      type:"post",
      data:{cost_id:id, user_id:user_id},
      success:function(result){
        $(".staff_cost_details").html(result['output_cost']);
      }
    })
      
       
    } 
    else {
        return false;       
    }
  }

  if($(e.target).hasClass('costing_class'))
  {
    $("#from_date").val('');
    $("#new_cost").val('');

    var id = $(e.target).attr("data-element");
    $.ajax({
      url: "<?php echo URL::to('user/manage_user_costing') ?>",
      dataType:"json",
      type:"post",
      data:{id:id},
      success:function(result){
        $(".annual_class").val(result['base_salary']);
        $(".annual_bonus_class").val(result['annual_bonus']);
        $(".annual_other_class").val(result['other_annual']);
        $(".total_salary_class").val(result['total_salary']);
        $(".standard_hour_class").val(result['standard_hour']);
        $(".holidays_class").val(result['holiday_day']);
        $(".rate_class").val(result['rate_social_insurance']);
        $(".insurance_class").val(result['social_insurance']);
        $(".cost_per_class").val(result['cost_per_hour']);
        $(".holiday_cost_class").val(result['holiday_cost_per_hour']);
        $(".final_cost_class").val(result['final_cost_per_hour']);
        $(".user_id_class").val(result['user_id']);
        $(".staff_name").html(result['staff_name']);
        $(".staff_cost_details").html(result['output_cost']);
        $(".costing_modal").modal('show');
    }
  })
  }


  if($(e.target).hasClass('apply_button'))
  {
    var user_id = $("#user_id").val();    
    var from_date = $("#from_date").val();
    var new_cost = $("#new_cost").val();

    if(from_date == "")
    {
      alert("Please select date.");
    }
    else if(new_cost == ""){
      alert("Please enter Cost.");
    }
    else{
          $.ajax({
          url: "<?php echo URL::to('user/manage_user_cost_add') ?>",
          dataType:"json",
          type:"post",
          data:{user_id:user_id,from_date:from_date,new_cost:new_cost},
          success:function(result){
            if(result['alert'] != "")
            {
              alert(result['alert']);
            }
            else{
              $(".staff_cost_details").html(result['output_cost']);
              $("#from_date").val("");
              $("#new_cost").val("");
            }
        }
      })
    }
  }
});
</script>

<script>

$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $annual_value = $('.annual_class');
    var $annual_bonus_value = $('.annual_bonus_class');
    var $annual_other_value = $('.annual_other_class');
    var $standard_hour_value = $('.standard_hour_class');
    var $holidays_value = $('.holidays_class');
    var $rate_value = $('.rate_class');
    if($(e.target).hasClass('annual_class'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, user_id, that);   
    }
    if($(e.target).hasClass('annual_bonus_class'))
    {        
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_annual_bonus, valueInterval,input_val, user_id, that);   
    }
    if($(e.target).hasClass('annual_other_class'))
    {        
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_annual_other, valueInterval,input_val, user_id, that);   
    }
    if($(e.target).hasClass('standard_hour_class'))
    {        
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_standard_hour, valueInterval,input_val, user_id, that);   
    }
    if($(e.target).hasClass('holidays_class'))
    {        
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_holidays, valueInterval,input_val, user_id, that);   
    }
    if($(e.target).hasClass('rate_class'))
    {        
        var input_val = $(e.target).val();  
        var user_id = $("#user_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_rate, valueInterval,input_val, user_id, that);   
    }
});


function doneTyping (annual_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:annual_value, user_id:user_id, type:1},
        success: function(result) { 
          $(".total_salary_class").val(result['result']);  
          $(".insurance_class").val(result['social_insurance']);
          $(".cost_per_class").val(result['per_hour']);
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
        } 
  });          
            
}

function doneTyping_annual_bonus (annual_bonus_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:annual_bonus_value, user_id:user_id, type:2},
        success: function(result) { 
          $(".total_salary_class").val(result['result']); 
          $(".insurance_class").val(result['social_insurance']);
          $(".cost_per_class").val(result['per_hour']); 
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
        } 
  });          
            
}

function doneTyping_annual_other (annual_other_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:annual_other_value, user_id:user_id, type:3},
        success: function(result) { 
          $(".total_salary_class").val(result['result']); 
          $(".insurance_class").val(result['social_insurance']);
          $(".cost_per_class").val(result['per_hour']);
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
        } 
  });          
            
}

function doneTyping_standard_hour (standard_hour_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:standard_hour_value, user_id:user_id, type:4},
        success: function(result) { 
          $(".cost_per_class").val(result['per_hour']);
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
          
        } 
  });          
            
}

function doneTyping_holidays (holidays_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:holidays_value, user_id:user_id, type:5},
        success: function(result) { 
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
          
        } 
  });          
            
}

function doneTyping_rate (rate_value,user_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/user_costing_update')?>",
        type:"post",
        dataType:"json",
        data:{value:rate_value, user_id:user_id, type:6},
        success: function(result) { 
          $(".insurance_class").val(result['social_insurance']);
          $(".cost_per_class").val(result['per_hour']);
          $(".holiday_cost_class").val(result['holiday_result']);
          $(".final_cost_class").val(result['final_cost_result'])
          
        } 
  });          
            
}
</script>
<script type="text/javascript">
$(function () {       
    $('.datepicker-only-init').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'LL'
    });        
});

$(".datepicker-only-init").keyup(function(e) {
  e.preventDefault();
    e.stopPropagation();
    return false;
});
$('#add_user_form').validate({
    rules: {
        name : {required: true},
        lname : {required: true},
        email : {required: true,
                  remote: { 
                    url : "<?php echo URL::to('user/check_user_email'); ?>",
                    type:"get",
                  },
                  email:true,},
        initial:{required: true},
    },
    messages: {
        name : {
          required : "Firstname is Required",
        },
        lname : {
          required : "Lastname is Required",
        },
        email : {
          required : "Email is Required",
          remote: "Email ALready Exists",
          email: "Please enter the valid email",
        },
        initial: {
          required:"Initial is Required",
        }
    },
});

$('#edit_user_form').validate({
    rules: {
        name : {required: true},
        lname : {required: true},
        email : {required: true,
                  remote: { 
                    url : "<?php echo URL::to('user/check_user_email'); ?>",
                    type:"get",
                    data: {'id':function(){return $('.name_id').val()}},
                    async:false 
                    },
                  email:true,},
        initial:{required: true},
        confirm_password : {equalTo: "#new_password"}
    },
    messages: {
        name : {
          required : "Firstname is Required",
        },
        lname : {
          required : "Lastname is Required",
        },
        email : {
          required : "Email is Required",
          remote: "Email ALready Exists",
          email: "Please enter the valid email",
        },
        initial: {
          required:"Initial is Required",
        },
        confirm_password : { equalTo: "Password Does Not Match."}
    },
});


</script>
@stop
