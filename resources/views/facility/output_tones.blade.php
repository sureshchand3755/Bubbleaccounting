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
  <div class="modal-dialog modal-sm" role="document" style="width: 25%">
    <form action="<?php echo URL::to('facility/add_output_tone'); ?>" method="post" class="addsp" id="add_output_tone">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Output Tone</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td>
                        <label>Target:</label>
                        <textarea name="target" class="form-control" cols="15" rows="3" placeholder="Enter Target..." required ></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Command:</label>
                        <textarea name="command" class="form-control" cols="15" rows="3" placeholder="Enter Command..." required></textarea>
                    </td>
                </tr>
                <tr>
                    <td>

                      <input type="radio" name="status" class="status" id="status_enable" value="0" checked> <label for="status_enable">Enable</label>
                      <input type="radio" name="status" class="status" id="status_disable" value="1"> <label for="status_disable">Disable</label>
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

    <form action="<?php echo URL::to('facility/update_output_tone'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Output Tone</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td>
                        <label>Target:</label>
                        <textarea name="target" class="form-control target_class" cols="15" rows="3" placeholder="Enter Target..." required ></textarea>
                    </td>

                </tr>
                <tr>
                    <td>
                        <label>Command:</label>
                        <textarea name="command" class="form-control command_class" cols="15" rows="3" placeholder="Enter Command..." required></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                      <input type="radio" name="status" class="status" id="update_status_enable" value="0" checked> <label for="update_status_enable">Enable</label>
                      <input type="radio" name="status" class="status" id="update_status_disble" value="1"> <label for="update_status_disble">Disable</label>
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
      <h2>Manage Output Tones

        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Manage Output Tones</li>
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
          <a href="javascript:" class="addcategory btn btn-primary float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Output Tone</a>
        </div>
      </div>
      <table class="table">
          <thead>
            <tr>
                <th width="5%" style="text-align: left;">S.No <a class="fa fa-sort code_sort"></a></th>
                <th style="text-align: left;">Target <a class="fa fa-sort category_sort"></a></th>
                <th style="text-align: left;">Command <a class="fa fa-sort category_sort"></a></th>
                <th style="text-align: left;" width="15%">Action</th>

            </tr>
          </thead>
          <tbody id="category_tbody">
            <?php
              $i=1;
              if(($outputtonelist)){
                foreach($outputtonelist as $outputtone){
            ?>
            <tr>
              <td class="code_sort_val"><?php echo $i;?></td>
              <td class="category_sort_val"><?php echo $outputtone->target; ?></td>
              <td class="category_sort_val"><?php echo $outputtone->command; ?></td>
              <td>
                  <?php
                  if($outputtone->status ==0){
                    echo'<a href="'.URL::to('facility/deactive_output_tone',base64_encode($outputtone->id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                  }
                  else{
                    echo'<a href="'.URL::to('facility/active_output_tone',base64_encode($outputtone->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                  ?>

                  &nbsp; &nbsp;

                  <a href="javascript:" id="<?php echo base64_encode($outputtone->id); ?>" class="editcategory"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
<input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
<input type="hidden" name="category_sortoptions" id="category_sortoptions" value="asc">

<script>
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseFloat(value);
}
$(".addcategory").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editcategory").click( function(){
  var editid = $(this).attr("id");
  $.ajax({
      url: "<?php echo URL::to('facility/edit_output_tone') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".target_class").val(result['target']);
         $(".command_class").val(result['command']);
         $(".name_id").val(result['id']);
         if(result['status'] == 1) {
          $("#update_status_disble").prop("checked",true);
          $("#update_status_enable").prop("checked",false);
         }
         else{
          $("#update_status_disble").prop("checked",false);
          $("#update_status_enable").prop("checked",true);
         }
    }
  })
});

$(document).ready(function($) {
    $("#add_output_tone").validate({
            rules: {
                target: "required",
                command: "required",
            },
            // Specify validation error messages
            messages: {
                target: "Please enter target",
                command: "Please enter command",
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
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

  if($(e.target).hasClass('code_sort'))
  {
    var sort = $("#code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#code_sortoptions").val('desc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#code_sortoptions").val('asc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#category_tbody').html(sorted);
  }

  if($(e.target).hasClass('category_sort'))
  {
    var sort = $("#category_sortoptions").val();
    if(sort == 'asc')
    {
      $("#category_sortoptions").val('desc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.category_sort_val').html()) <
        convertToNumber($(b).find('.category_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#category_sortoptions").val('asc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.category_sort_val').html()) <
        convertToNumber($(b).find('.category_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#category_tbody').html(sorted);
  }

});
</script>
@stop
