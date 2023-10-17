@extends('facilityheader')
@section('content')
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/waypoints/lib/jquery.waypoints.js'); ?>"></script>
<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
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
.table_notes{
  margin-top:20px;
}
.wrapper .sidebar{
  z-index: 99999;
}
.tablefixedheader {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.tablefixedheader thead tr th {
  position: sticky;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    background-color: #fff !important;
}
</style>
<div class="admin_content_section"> 
  <div class="col-md-12">
    <h2>System Setting Review
      <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?php echo URL::to('facility/profile'); ?>">Home</a></li>
      <li class="breadcrumb-item active">System Setting Review</li>
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
        <input type="button" class="btn btn-primary show_table_viewer" id="show_table_viewer" value="Load System Settings Review" style="margin-top:7px; margin-left:10px"> 

        <input type="button" class="btn btn-primary fix_missing_records" id="fix_missing_records" value="Fix Missing Records" style="margin-top:7px; margin-left:10px;display:none"> <br/>
    </div>
    <div class="col-lg-12">
        <div class="table_viewer_content" style="margin-top: 20px;"></div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<script>

$(window).click(function(e) {
  if($(e.target).hasClass('show_table_viewer'))
  {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('facility/show_system_module_settings'); ?>",
        type:"post",
        success:function(result){
          $(".table_viewer_content").html(result);
          $("#fix_missing_records").show();
          $("body").removeClass("loading");
        }
      })
  }
  if($(e.target).hasClass('fix_missing_records'))
  {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('facility/fix_missing_records'); ?>",
        type:"post",
        success:function(result){
          $(".table_viewer_content").html(result);
          $("#fix_missing_records").show();
          $("body").removeClass("loading");
        }
      })
  }
});


</script>
@stop