@extends('facilityheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.css'); ?>" />
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins/dropzone/dist/dropzone.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.css') ?>" type="text/css" />
<script src="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.js'); ?>"></script>

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
body {
  padding:0px;
}
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
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.remove_dropzone_attach_task{
  color:#f00 !important;
  margin-left:10px;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
  margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
  float: left;
  clear: both;
}
.dropzone .dz-preview p {
  font-size:12px !important;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999999999;
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
#cropped_img{
  width:50%;
  padding: 10px;
border: 1px solid #c3c3c3;
border-radius: 10px;
}
#edit_cropped_img{
  width:50%;
  padding: 10px;
border: 1px solid #c3c3c3;
border-radius: 10px;
}

.edit_avatar{
  cursor: pointer;
}
.jcrop-centered{
  width:100% !important;
}

.jcrop-holder{
  width:100% !important;
}
.col-md-2 {
  padding: 10px 10px 0px 0px;
}
</style>

<!-- Content Header (Page header) -->
<div class="modal fade edit_avatar_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:533px">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Edit User Avatar</h4>
          </div>
          <div class="modal-body edit_modal_body" style="min-height:280px">  
              <div class="row">
                <!-- <div class="col-lg-12" id="edit_wrapper_img" style="width:100% !important">
                  <img class="edit_adopt_image" id="edit_cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">
                </div> -->
                <div class="col-lg-12" id="edit_wrapper_img">
                  <img class="edit_adopt_image" id="edit_cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" id="edit_setx" value="">
            <input type="hidden" id="edit_sety" value="">
            <input type="hidden" id="edit_setw" value="">
            <input type="hidden" id="edit_seth" value="">
            <input type="hidden" id="edit_original_upload_dir" value="">
            <input type="hidden" id="edit_original_filename" value="">

            <a href="javascript:" class="btn btn-primary edit_crop" align="left" style="margin-left:7px; float:right;margin-top:-10px;margin-bottom:10px;display:none">Crop</a>
          </div>
        </div>
  </div>
</div>

<div class="modal fade upload_avatar_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Add User Avatar</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-6">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">
                        <form action="<?php echo URL::to('facility/upload_user_avatar_images'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadprogress" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        @csrf
                        </form>
                        <p style="color:#f00;font-weight: 600">Note: Please Upload only jpeg, jpg and png file formats.</p>
                     </div>
                  </div>
                </div>
                <div class="col-lg-6" id="wrapper_img">
                  <img class="adopt_image" id="cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" id="setx" value="">
            <input type="hidden" id="sety" value="">
            <input type="hidden" id="setw" value="">
            <input type="hidden" id="seth" value="">
            <input type="hidden" id="original_upload_dir" value="">
            <input type="hidden" id="original_filename" value="">

            <a href="javascript:" class="btn btn-primary crop" align="left" style="margin-left:7px; float:right;margin-top:-10px;margin-bottom:10px;display:none">Crop</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade cropped_img_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Cropped Image</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
            <div class="row" style="text-align: center;">
               <img src="javascript:" id="cropped_img">
            </div>
          </div>
          <div class="modal-footer">  
              <input type="hidden" id="hidden_original_image_path" value="">
              <input type="hidden" id="hidden_cropped_image_path" value="">

              <input type="hidden" id="cropped_upload_dir" value="">
              <input type="hidden" id="cropped_filename" value="">

              <a href="javascript:" class="btn btn-primary submit_cropped" style="float:right">Save Cropped Image</a>

              <a href="javascript:" class="btn btn-primary close_cropped_model" style="float:right; margin-right:10px">Abort</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade edit_cropped_img_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Cropped Image</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
            <div class="row" style="text-align: center;">
               <img src="javascript:" id="edit_cropped_img">
            </div>
          </div>
          <div class="modal-footer">  
              <input type="hidden" id="edit_hidden_original_image_path" value="">
              <input type="hidden" id="edit_hidden_cropped_image_path" value="">

              <input type="hidden" id="edit_cropped_upload_dir" value="">
              <input type="hidden" id="edit_cropped_filename" value="">

              <input type="hidden" id="hidden_edit_avatar_id" value="">

              <a href="javascript:" class="btn btn-primary edit_submit_cropped" style="float:right">Update Cropped Image</a>
          </div>
        </div>
  </div>
</div>

<div class="admin_content_section"> 
  <div class="table-responsive">
      <div class="col-md-12">
        <h2>User Avatar
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo URL::to('facility/profile'); ?>">Home</a></li>
          <li class="breadcrumb-item active">User Avatar</li>
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
          <h4 class="col-md-12 padding_00" style="font-weight: 600;margin-top:10px">Default User Avatar List: <a href="javascript:" class="add_new_avatar btn btn-primary" style="float:right;margin-top: -10px;">Add New Avatar</a></h4>

          <div class="col-md-12 user_avatar_list_div" style="padding: 15px;max-height:800px;height: 800px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
              <?php
              if(is_countable($avatarlist) && count($avatarlist) > 0) {
                foreach($avatarlist as $avatar) {
                  ?>
                  <div class="col-md-2">
                      <img src="<?php echo URL::to($avatar->crop_url).'/'.$avatar->crop_filename; ?>" class="edit_avatar" data-element="<?php echo $avatar->id; ?>" data-original="<?php echo URL::to($avatar->url.'/'.$avatar->filename); ?>" data-cropped="<?php echo URL::to($avatar->crop_url.'/'.$avatar->crop_filename); ?>" data-original_upload_dir="<?php echo $avatar->url; ?>" data-original_filename="<?php echo $avatar->filename; ?>" data-cropped_upload_dir="<?php echo $avatar->crop_url; ?>" data-cropped_filename="<?php echo $avatar->crop_filename; ?>" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
                  </div>
                  <?php
                }
              }
              ?>
          </div>
        </div>
      </div>
  </div>
</div>
<div class="modal_load"></div>
<script>
$(window).click(function(e) {
  if($(e.target).hasClass('close_cropped_model')) {
    $(".cropped_img_overlay").modal("hide");
    $(".upload_avatar_modal").modal("hide");
  }
  if($(e.target).hasClass('edit_avatar')) {
    var avatar_id = $(e.target).attr("data-element");
    var original = $(e.target).attr("data-original");
    var cropped = $(e.target).attr("data-cropped");

    var original_upload_dir = $(e.target).attr("data-original_upload_dir");
    var original_filename = $(e.target).attr("data-original_filename");
    var cropped_upload_dir = $(e.target).attr("data-cropped_upload_dir");
    var cropped_filename = $(e.target).attr("data-cropped_filename");

    $(".edit_avatar_modal").modal("show");
    $("#hidden_edit_avatar_id").val(avatar_id);

    $(".edit_crop").hide();

    $("#edit_cropped_img").attr('src',"#");
    $('#edit_wrapper_img').empty();
    var img = $('<img class="edit_adopt_image" id="edit_cropbox" src="'+original+'" style="width:100%">');
    img.appendTo('#edit_wrapper_img');

    $('#edit_cropbox').Jcrop({
      aspectRatio: 1,
      boxWidth: 500,
      addClass: 'jcrop-centered',
      onSelect: function(c){
        $("#edit_setx").val(c.x);
        $("#edit_sety").val(c.y);
        $("#edit_setw").val(c.w);
        $("#edit_seth").val(c.h);
       $(".edit_crop").show();     
      }
    });

    $("#edit_original_upload_dir").val(original_upload_dir);
    $("#edit_original_filename").val(original_filename);
    $("#edit_cropped_upload_dir").val(cropped_upload_dir);
    $("#edit_cropped_filename").val(cropped_filename);
    $("#edit_hidden_original_image_path").val(original);
    $("#edit_hidden_cropped_image_path").val(cropped);
  }
  if($(e.target).hasClass('add_new_avatar')){
    $(".upload_avatar_modal").modal("show");
    Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");

    $(".crop").hide();

    $("#cropped_img").attr('src',"#");
    $('#wrapper_img').empty();
    var img = $('<img class="adopt_image" id="cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">');
    img.appendTo('#wrapper_img');
    $("#original_upload_dir").val("");
    $("#original_filename").val("");
    $("#cropped_upload_dir").val("");
    $("#cropped_filename").val("");
    $("#hidden_original_image_path").val("");
    $("#hidden_cropped_image_path").val("");
    $("#setx").val("");
    $("#sety").val("");
    $("#setw").val("");
    $("#seth").val("");

  }
  if($(e.target).hasClass('edit_crop')){
    var img = $("#edit_cropbox").attr('src');
    var x = $("#edit_setx").val();
    var y = $("#edit_sety").val();
    var w = $("#edit_setw").val();
    var h = $("#edit_seth").val();
    var upload_dir = $("#edit_original_upload_dir").val();


    var elewidth = $("#edit_cropbox").width();
    var eleheight = $("#edit_cropbox").height();

    $.ajax({
      url:"<?php echo URL::to('facility/show_cropped_image'); ?>",
      type:"post",
      dataType:"json",
      data:{x:x,y:y,w:w,h:h,img:img,upload_dir:upload_dir,elewidth:elewidth,eleheight:eleheight},
      success:function(result) {
        $(".edit_cropped_img_overlay").modal("show");
        $("#edit_cropped_img").attr('src',result['image_path']);

        $("#edit_hidden_original_image_path").val(img);

        $("#edit_cropped_upload_dir").val(result['upload_dir']);
        $("#edit_cropped_filename").val(result['filename']);
      }
    });
    
  }
  if($(e.target).hasClass('crop')){
    var img = $("#cropbox").attr('src');
    var x = $("#setx").val();
    var y = $("#sety").val();
    var w = $("#setw").val();
    var h = $("#seth").val();
    var upload_dir = $("#original_upload_dir").val();


    var elewidth = $("#cropbox").width();
    var eleheight = $("#cropbox").height();

    $.ajax({
      url:"<?php echo URL::to('facility/show_cropped_image'); ?>",
      type:"post",
      dataType:"json",
      data:{x:x,y:y,w:w,h:h,img:img,upload_dir:upload_dir,elewidth:elewidth,eleheight:eleheight},
      success:function(result) {
        $(".cropped_img_overlay").modal("show");
        $("#cropped_img").attr('src',result['image_path']);

        $("#hidden_original_image_path").val(img);
        $("#hidden_cropped_image_path").val(result);

        $("#cropped_upload_dir").val(result['upload_dir']);
        $("#cropped_filename").val(result['filename']);
      }
    });
    
  }
  if($(e.target).hasClass('submit_cropped')) {
    var original_image = $("#hidden_original_image_path").val();
    var cropped_image = $("#hidden_cropped_image_path").val();

    var original_upload_dir = $("#original_upload_dir").val();
    var original_filename = $("#original_filename").val();

    var cropped_upload_dir = $("#cropped_upload_dir").val();
    var cropped_filename = $("#cropped_filename").val();

    $.ajax({
      url:"<?php echo URL::To('facility/save_cropped_image'); ?>",
      type: "post",
      data:{original_upload_dir:original_upload_dir,original_filename:original_filename,cropped_upload_dir:cropped_upload_dir,cropped_filename:cropped_filename},
      success:function(result){
        $(".upload_avatar_modal").modal("hide");
        $(".cropped_img_overlay").modal("hide");

        $("#original_upload_dir").val("");
        $("#original_filename").val("");
        $("#cropped_upload_dir").val("");
        $("#cropped_filename").val("");

        $(".user_avatar_list_div").html(result);
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">User Avatar Saved Successfully.</p>',fixed:true,width:"800px"});


      }
    })
  }
  if($(e.target).hasClass('edit_submit_cropped')) {
    var avatar_id = $("#hidden_edit_avatar_id").val();
    var original_image = $("#edit_hidden_original_image_path").val();
    var cropped_image = $("#edit_hidden_cropped_image_path").val();

    var original_upload_dir = $("#edit_original_upload_dir").val();
    var original_filename = $("#edit_original_filename").val();

    var cropped_upload_dir = $("#edit_cropped_upload_dir").val();
    var cropped_filename = $("#edit_cropped_filename").val();

    $.ajax({
      url:"<?php echo URL::To('facility/update_cropped_image'); ?>",
      type: "post",
      data:{original_upload_dir:original_upload_dir,original_filename:original_filename,cropped_upload_dir:cropped_upload_dir,cropped_filename:cropped_filename,avatar_id:avatar_id},
      success:function(result){
        $(".edit_avatar_modal").modal("hide");
        $(".edit_cropped_img_overlay").modal("hide");

        $("#edit_original_upload_dir").val("");
        $("#edit_original_filename").val("");
        $("#edit_cropped_upload_dir").val("");
        $("#edit_cropped_filename").val("");

        $(".user_avatar_list_div").html(result);
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">User Avatar Saved Successfully.</p>',fixed:true,width:"800px"});


      }
    })
  }
})
Dropzone.options.imageUploadprogress = {
    maxFiles: 1,
    acceptedFiles: "image/jpeg,image/jpg,image/png",
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            //$(".adopt_image").attr("src", obj.image_path)
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");

            $('#wrapper_img').empty();
            var img = $('<img class="adopt_image" id="cropbox" src="'+obj.image_path+'" style="width:100%">');
            img.appendTo('#wrapper_img');
            $("#original_upload_dir").val(obj.upload_dir);
            $("#original_filename").val(obj.filename);

            $("#cropped_upload_dir").val("");
            $("#cropped_filename").val("");

            var size;
            $('#cropbox').Jcrop({
              aspectRatio: 1,
              onSelect: function(c){
                $("#setx").val(c.x);
                $("#sety").val(c.y);
                $("#setw").val(c.w);
                $("#seth").val(c.h);
               $(".crop").show();     
              }
            });
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");
          }
        });
        this.on("error", function (file) {
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
        });
    },
};
</script>
@stop