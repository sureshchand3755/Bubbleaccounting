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
#practice_tbody tr td {
  border: 0px;
}
</style>
<!-- Content Header (Page header) -->
<div class="modal fade practice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">Practice Information</h4>
        <button type="button" class="close close_modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="close_modal">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table">
            <tbody id="practice_tbody">
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close_modal common_button_gray" data-bs-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
      <div class="col-md-12">
        <h2>Registered Practices

          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo URL::to('facility/profile'); ?>">Home</a></li>
          <li class="breadcrumb-item active">Registered Practices</li>
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
        <div class="col-lg-12 text-left padding_00" style="border: 1px solid #c3c3c3;padding: 15px;border-radius: 5px;background: #fff">
          <table class="table">
            <thead>
              <th>S.No</th>
              <th>Practice Code</th>
              <th>Email Address</th>
              <th>Practice Name</th>
              <th>OneTimeCode</th>
              <th>Validated Time</th>
            </thead>
            <tbody>
              <?php
              if(count($practices)) {
                $i = 1;
                foreach($practices as $practice) {
                  echo '<tr>
                    <td>'.$i.'</td>
                    <td><a href="javascript:" class="show_practice_informations" data-element="'.$practice->practice.'">'.$practice->practice.'</td>
                    <td>'.$practice->email.'</td>
                    <td>'.$practice->practice_name.'</td>
                    <td>'.$practice->otp.'</td>
                    <td>'.date('d-M-Y @ H:i', strtotime($practice->created_at)).'</td>
                    
                  </tr>';
                  $i++;
                }
              }
              ?>
            </tbody>
          </table>

          <!-- <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
              <?php
              $totals = DB::table('user')->where('user_type',1)->where('user_role','admin')->get();
              $total_page_count = floor(count($totals) / 10);
              $pageno = 1;
              if(isset($_GET['page'])) {
                $pageno = $_GET['page'];
              }
              $cls_disabled = '';
              $last_cls_disabled = '';
              $move_to_first = URL::to('facility/practice_screen?page=1');
              $move_to_last = URL::to('facility/practice_screen?page='.$total_page_count.'');
              if($pageno == 1) {
                $cls_disabled = 'disabled';
                $pagenos = ['1','2','3'];
                $move_to_first= '#';
              }
              elseif($pageno == $total_page_count) {
                $prev2 = $total_page_count - 2;
                $prev1 = $total_page_count - 1;
                $prev = $total_page_count;

                $last_cls_disabled = 'disabled';
                $pagenos = [$prev2,$prev1,$prev];
                $move_to_last = '#';
              }
              else{
                $prev = $pageno - 1;
                $after = $pageno + 1;
                $pageno = $pageno;

                $pagenos = [$prev,$pageno,$after];

              }
              ?>
              <li class="page-item <?php echo $cls_disabled; ?>">
                <a class="page-link" href="<?php echo $move_to_first; ?>" tabindex="-1"><<</a>
              </li>
              <li class="page-item <?php echo $cls_disabled; ?>">
                <a class="page-link" href="#" tabindex="-1"><</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item <?php echo $last_cls_disabled; ?>">
                <a class="page-link" href="#">></a>
              </li>
              <li class="page-item <?php echo $last_cls_disabled; ?>">
                <a class="page-link" href="<?php echo $move_to_first; ?>">>></a>
              </li>
            </ul>
          </nav> -->
        </div>
      </div>

      
  </div>
</div>
</div>
<script>
$(window).click(function(e) {
  if($(e.target).hasClass('show_practice_informations')) {
    var code = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('facility/show_practice_informations'); ?>",
      type:"post",
      data:{code:code},
      success:function(result) {
        $("#practice_tbody").html(result);
        $(".practice_modal").modal("show");
      }
    })
  }
  if($(e.target).hasClass('close_modal')) {
    $(".practice_modal").modal("hide");
  }
})
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