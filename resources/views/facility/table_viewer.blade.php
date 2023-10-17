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
.table_content_body_td{
  max-width: 800px;
word-wrap: break-word;
max-height: 200px;
overflow: scroll;
scrollbar-color: #3db0e6 #f5f5f5;
scrollbar-width: thin;
}
</style>
<div class="admin_content_section"> 
  <div class="col-md-12">
    <h2>Table Viewer
      <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?php echo URL::to('facility/profile'); ?>">Home</a></li>
      <li class="breadcrumb-item active">Table Viewer</li>
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
        <label style="float:left;margin-top:12px">Select Table: </label>
        <select name="select_table" class="form-control select_table" style="float:left;width:20%;margin-left:20px;margin-top:7px">
            <option value="">Select one table</option>
            <?php
            if(($tablelist)){
              foreach($tablelist as $key => $table){
                echo '<option value="'.$table.'" data-element="'.$key.'">'.$table.'</option>';
              }
            }
            ?>
        </select>
        <input type="button" class="btn btn-primary show_table_viewer" id="show_table_viewer" value="Load Table" style="margin-top:7px; margin-left:10px"> <br/>
        <textarea name="table_notes" class="form-control table_notes" value="" style="display:none"></textarea>
    </div>
    <div class="col-lg-12">
      <style type="text/css">
        .refresh_icon{margin-left: 10px;}
        .refresh_icon:hover{text-decoration: none;}
        .datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
        .bootstrap-datetimepicker-widget{width: 300px !important;}
        .image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
        </style>

        <div class="table_viewer_content" style="margin-top: 20px;"></div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<script>
$(window).change(function(e) {
    if($(e.target).hasClass('select_table'))
    {
      var value=$(e.target).val();
      if(value == ''){
        $(".table_notes").hide();
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('facility/get_table_notes'); ?>",
          type:"post",
          data:{value:value},
          success:function(result){
            $(".table_notes").show();
            $(".table_notes").val(result);
          }
        });
      }
    }
});
// function isScrolledIntoView(elem)
// {
//     var docViewTop = $(window).scrollTop();
//     var docViewBottom = docViewTop + $(window).height();

//     var elemTop = elem.offset().top;
//     var elemBottom = elemTop + $(elem).height();

//     return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
// }




// $(window).scroll(function() {
  
//   // if (isScrolledIntoView($('.load_more_content')))
//   // {
//   //     
//   // }
// });
function waypointsval() {
  $("#load_more_content").hide();
  Waypoint.destroyAll();
  var table_name = $(".select_table").val();
  var page = $("#hidden_page").val();

  var nextpage = parseInt(page) + 1;
  $("body").addClass("loading");
  $.ajax({
     url:"<?php echo URL::to('facility/show_table_viewer_append'); ?>",
     type:"post",
     dataType:"json",
     data:{table_name:table_name,page:nextpage},
     success:function(result){
       $("#table_viewer_tbody").append(result['output']);
       if(result['show_load_btn'] == 1){
         $(".common_btn").addClass('load_more_content');
         $(".common_btn").show();
         $("#hidden_page").val(nextpage);
         $('#load_more_content').waypoint(function() {
            waypointsval();
        }, {
            offset: '100%'
        });
       }
       else{
         $(".common_btn").removeClass('load_more_content');
         $(".common_btn").hide();
         $("#hidden_page").val(0);
       }
       $("body").removeClass("loading");
     }
  })
}


$(window).click(function(e) {
  if($(e.target).hasClass('show_table_viewer'))
  {
    var table_name = $(".select_table").val();
    if(table_name == ""){
      alert("Please select the table");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('facility/show_table_viewer'); ?>",
        type:"post",
        dataType:"json",
        data:{table_name:table_name,page:"1"},
        success:function(result){
          $(".table_viewer_content").html(result['output']);
          $("body").removeClass("loading");
          $('#load_more_content').waypoint(function() {
              waypointsval();
          }, {
              offset: '100%'
          });
        }
      })
    }
  }
});

$(".table_notes").on("blur", function() {
  var notes = $(this).val();
  var table = $(".select_table").val();

  $.ajax({
    url:"<?php echo URL::to('facility/update_table_notes'); ?>",
    type:"post",
    data:{notes:notes,table:table},
    success:function(result){
      
    }
  });
});
</script>
@stop