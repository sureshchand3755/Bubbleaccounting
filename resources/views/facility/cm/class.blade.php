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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('facility/add_cm_class'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add CM Class</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><input type="text" name="name" class="form-control" placeholder="Enter Class Name" required></td>
                </tr>
                <tr>
                    <td>
                      <input type="hidden" name="color" id="chosen-value" class="form-control" value="000000" placeholder="Enter Class Code" required>
                      <button style="width: 35px; height: 35px; border:1px solid #fff; float: left; box-shadow: 0px 0px 5px #666;" class="jscolor {valueElement:'chosen-value', onFineChange:'setTextColor(this)'}">  </button>
                    </td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="btn btn-success float_right">
          </div>
        </div>
        @csrf
    </form>

    <form action="<?php echo URL::to('facility/update_cm_class'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit CM Class</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><input type="text" name="name" class="form-control name_class" placeholder="Enter Task Level" required></td>
                </tr>
                <tr>
                    <td>
                      <input type="hidden" id="chosen-value-2" name="color" class="form-control color_class" value="" placeholder="Enter color Code" required>
                      <button style="width: 35px; height: 35px; border:1px solid #fff; float: left; box-shadow: 0px 0px 5px #666;" class="jscoloredit jscolor {valueElement:'chosen-value-2', onFineChange:'setTextColor(this)'}">  </button>
                    </td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" class="form-control name_id">
            <input type="submit" value="Update" class="btn btn-success">
          </div>
        </div>
    @csrf
    </form>
  </div>
</div>
<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div class="table-responsive">
    <div class="col-md-12">
      <h2>Manage CM Class

        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Manage CM Class</li>
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
    
      <div class="col-lg-12 padding_00">
        <div class="col-lg-6 text-left padding_00">
          <div class="sub_title"></div>
        </div>      
        <div class="col-lg-6 text-right">
          <a href="javascript:" class="addclass btn btn-primary float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Class</a>
        </div>
      </div>
      <table class="table">
          <thead>
            <tr>
                <th width="5%" style="text-align: left;">S.No</th>
                <th style="text-align: left;">Name</th>
                <th style="text-align: left;">Color</th>
                <th style="text-align: left;" width="15%">Action</th>
                
            </tr>
          </thead>
          <tbody>
            <?php
              $i=1;
              if(($cmclasslist)){              
                foreach($cmclasslist as $cmclass){
            ?>
            <tr>            
              <td><?php echo $i;?></td>            
              <td><?php echo $cmclass->classname; ?></td>
              <td><div style="width: 20px; height: 20px; background:#<?php echo $cmclass->classcolor; ?>"></div></td>
              <td>
                  <?php
                  if($cmclass->status ==0){
                    echo'<a href="'.URL::to('facility/deactive_cm_class',base64_encode($cmclass->id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                  }
                  else{
                    echo'<a href="'.URL::to('facility/active_cm_class',base64_encode($cmclass->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                  ?>

                  &nbsp; &nbsp;

                  <a href="javascript:" id="<?php echo base64_encode($cmclass->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
<script>
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('facility/edit_cm_class') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".color_class").val(result['color']);
         $(".name_id").val(result['id']);
         $(".jscoloredit").css({"background-color":result['color']});
    }
  })
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Deleting this User will leads to remove from task alloted to this user. Are You Sure want to delete this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});
</script>
@stop