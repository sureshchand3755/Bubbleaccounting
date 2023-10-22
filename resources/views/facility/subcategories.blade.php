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
    <form action="<?php echo URL::to('facility/addsubcategory'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add SubCategory</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td>
                      <select name="cat_id" class="form-control cat_id" required>
                        <option value="">Select Category</option>
                        <?php
                        if(is_countable($categories) && count($categories) > 0) {
                          foreach($categories as $cat) {
                            echo '<option value="'.$cat->id.'">'.$cat->categoryname.'</option>';
                          }
                        }
                        ?>
                      </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="name" class="form-control" placeholder="Enter SubCategory Name" required></td>
                </tr>
                <tr>
                    <td>
                      <input type="radio" name="status" class="status" id="cat_status_enable" value="0" checked> <label for="cat_status_enable">Enable</label>
                      <input type="radio" name="status" class="status" id="cat_status_disable" value="1"> <label for="cat_status_disable">Disable</label>
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

    <form action="<?php echo URL::to('facility/updatesubcategory'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Sub Category</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td>
                      <select name="cat_id" class="form-control cat_id" id="cat_id" required>
                        <option value="">Select Category</option>
                        <?php
                        if(is_countable($categories) && count($categories) > 0) {
                          foreach($categories as $cat) {
                            echo '<option value="'.$cat->id.'">'.$cat->categoryname.'</option>';
                          }
                        }
                        ?>
                      </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="name" class="form-control name_class" placeholder="Enter Sub Category Name" required></td>
                </tr>
                <tr>
                    <td>
                      <input type="radio" name="status" class="status" id="update_cat_status_enable" value="0" checked> <label for="update_cat_status_enable">Enable</label>
                      <input type="radio" name="status" class="status" id="update_cat_status_disble" value="1"> <label for="update_cat_status_disble">Disable</label>
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
      <h2>Manage Sub Categories

        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Manage Sub Categories</li>
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
          <a href="javascript:" class="addsubcategory btn btn-primary float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Sub Category</a>
        </div>
      </div>
      <table class="table">
          <thead>
            <tr>
                <th width="5%" style="text-align: left;">S.No <a class="fa fa-sort code_sort"></a></th>
                <th style="text-align: left;">Name <a class="fa fa-sort subcategory_sort"></a></th>
                <th style="text-align: left;">Category Name <a class="fa fa-sort category_sort"></a></th>
                <th style="text-align: left;" width="15%">Action</th>
                
            </tr>
          </thead>
          <tbody id="category_tbody">
            <?php
              $i=1;
              if(($subcategorieslist)){              
                foreach($subcategorieslist as $subcategory){
                  $category_details = DB::table('ai_accrep_category_default')->where('id',$subcategory->category_id)->first();
            ?>
            <tr>            
              <td class="code_sort_val"><?php echo $i;?></td>            
              <td class="subcategory_sort_val"><?php echo $subcategory->name; ?></td>
              <td class="category_sort_val"><?php echo $category_details->categoryname; ?></td>
              <td>
                  <?php
                  if($subcategory->status ==0){
                    echo'<a href="'.URL::to('facility/deactivesubcategory',base64_encode($subcategory->id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                  }
                  else{
                    echo'<a href="'.URL::to('facility/activesubcategory',base64_encode($subcategory->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                  ?>

                  &nbsp; &nbsp;

                  <a href="javascript:" id="<?php echo base64_encode($subcategory->id); ?>" class="editsubcategory"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
<input type="hidden" name="subcategory_sortoptions" id="subcategory_sortoptions" value="asc">
<input type="hidden" name="category_sortoptions" id="category_sortoptions" value="asc">

<script>
$(".addsubcategory").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editsubcategory").click( function(){
  var editid = $(this).attr("id");
  $.ajax({
      url: "<?php echo URL::to('facility/editsubcategory') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $("#cat_id").val(result['category_id']);
         $(".name_id").val(result['id']);
         if(result['status'] == 1) {
          $("#update_cat_status_disble").prop("checked",true);
          $("#update_cat_status_enable").prop("checked",false);
         }
         else{
          $("#update_cat_status_disble").prop("checked",false);
          $("#update_cat_status_enable").prop("checked",true);
         }
    }
  })
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseFloat(value);
}
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

  if($(e.target).hasClass('subcategory_sort'))
  {
    var sort = $("#subcategory_sortoptions").val();
    if(sort == 'asc')
    {
      $("#subcategory_sortoptions").val('desc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.subcategory_sort_val').html()) <
        convertToNumber($(b).find('.subcategory_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#subcategory_sortoptions").val('asc');
      var sorted = $('#category_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.subcategory_sort_val').html()) <
        convertToNumber($(b).find('.subcategory_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#category_tbody').html(sorted);
  }
});
</script>
@stop