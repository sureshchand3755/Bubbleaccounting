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
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
  margin-top:10%;
}
</style>
<!-- Content Header (Page header) -->
<div class="modal fade change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">Upload Image</h4>
        <button type="button" class="close close_modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="close_modal">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('facility/edit_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
                  <input name="preview_image_id" type="hidden" id="preview_image_id" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
      <div class="col-md-12">
        <h2>Default Email Header Image

          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo URL::to('facility/profile'); ?>">Home</a></li>
          <li class="breadcrumb-item active">Default Email Header Image</li>
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
              <th>Default Image</th>
              <th>Action</th>
            </thead>
            <tbody>
              <?php
              if(($email_header_images)) {
                $i = 1;
                foreach($email_header_images as $image) {
                  if($image->url == "") {
                    $imageval1 = URL::to("public/assets/images/easy_payroll_logo.png");
                    $imageval = '<img src="'.$imageval1.'" class="img_tag_'.$image->id.'" style="width:200px" />';
                  }
                  else{
                    $imageval1 = URL::to($image->url.'/'.$image->filename);
                    $imageval = '<img src="'.$imageval1.'" class="img_tag_'.$image->id.'" style="width:200px" />';
                  }
                  echo '<tr>
                    <td style="vertical-align:middle">'.$i.'</td>
                    <td style="vertical-align:middle">'.$imageval.'</td>
                    <td style="vertical-align:middle">
                      <a href="javascript:" class="fa fa-upload edit_header_image" data-element="'.$image->id.'" data-image="'.$imageval1.'" title="Change Email Header Image" style="font-size: 22px;font-weight: 800;text-align: center;"></a>
                    </td>
                  </tr>';
                  $i++;
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 31px;"><strong>User Guidance:</strong><br/> The Default Email Image is used as the header graphic for emails sent from the system. Each module that engages in email communication will have the option to set a specific header image.</p>
      </div>
  </div>
</div>
</div>
<script>
$(window).click(function(e) {
  if($(e.target).hasClass('close_modal')) {
        $(".change_header_image").modal("hide");   
  }
  if($(e.target).hasClass('edit_header_image')) {
    var r = confirm("Are you sure you want to change the Default Email Header Image?");
    if(r) {
      var image_id = $(e.target).attr("data-element");
      var image = $(e.target).attr("data-image");

      $(".change_header_image").modal("show");
      $("#preview_image_id").val(image_id);

      Dropzone.forElement("#ImageUpload").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('close_modal')) {
    $(".practice_modal").modal("hide");
  }
})
//$.ajaxSetup({async:false});
Dropzone.options.ImageUpload = {
    maxFiles: 1,
    acceptedFiles: ".png",
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
            if(obj.error == 1) {
              $("body").removeClass("loading");
              Dropzone.forElement("#ImageUpload").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".img_tag_"+obj.image_id).attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUpload").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Default Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUpload").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $("#preview_image_header").attr("src",obj.image);
            $(".img_tag_"+obj.image_id).attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUpload").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Default Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
    },
};
</script>
@stop