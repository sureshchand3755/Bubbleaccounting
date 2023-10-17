@extends('userheader')
@section('content')
<style>
.fa-trash, .fa-text-width, .fa-ellipsis-h { margin-left:10px; }
.add_attachment_comment{
    width: 100px;
    margin-top: 10px;
    clear: both;
    float: left;
    font-weight: 600;
    font-size: 16px;
}
.messageus_attachment_a, #messageus_attachment_p{
  font-weight:700;
  text-decoration: underline;
  color:blue;
}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.fa-sort{ cursor:pointer; }
.error{
  color:#f00;
  float:right;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.file_attachments{
	color: #002bff;
    text-decoration: underline;
    font-weight: 700;
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
.file_attachment_div{width:100%;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dz-message span{text-transform: capitalize !important; font-weight: bold;}
.trash_imageadd{
  cursor:pointer;
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
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
/*.messageus_attachment:nth-child(n+9) {
   display:none;
}*/
#colorbox, #cboxWrapper { z-index:99999999999; }
.dz-message{margin-top: 75px !important;}
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>

<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<div class="modal fade messageus_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;">
      <div class="modal-content">
        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">MessageUs Settings</h4>
          </div>
          <div class="modal-body">
            <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('user/update_messageus_settings'); ?>">
              <div class="col-lg-12 padding_00">
                <div>
                  <?php
                  if(Session::has('emailmessage')) { ?>
                      <p class="alert alert-info" style="width:90%"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                  <?php }
                  ?>
                </div>
                <?php
                $messageus_settings = DB::table('messageus_settings')->where('practice_code', Session::get('user_practice_code'))->first();
                $messageus_cc_email = '';
                if($messageus_settings){
                  $messageus_cc_email = $messageus_settings->messageus_cc_email;
                }
                ?>
                  <div class="form-group">
                      <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                      <?php
                      if($messageus_settings->email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($messageus_settings->email_header_url.'/'.$messageus_settings->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="messageus_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="messageus_email_header_img_btn" class="common_black_button messageus_email_header_img_btn" value="Browse"> 
                      <h4>MessageUs CC Email ID</h4>
                      <input id="validation-cc-email"
                             class="form-control"
                             placeholder="Enter MessageUs CC Email ID"
                             value="<?php echo $messageus_cc_email; ?>"
                             name="messageus_cc_email"
                             type="text"
                             required>                                 
                  </div>
              </div>
              <div class="col-lg-12 padding_00">
                <input type="submit" class="common_black_button" id="messageus_submit" value="Submit">
              </div>
              @csrf
            </form>
          </div>
          <div class="modal-footer" style="clear:both">
              
          </div>
      </div>
  </div>
</div>
<div class="modal fade change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_messageus_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<div class="content_section">
	<div id="fixed-header" style="width:100%;">
	  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title menu-logo">
                MessageUs - Central Client Messaging System      

                <a href="javascript:" class="fa fa-cog setting_class common_black_button" style="font-weight: 500;float:right" title="MessageUs Settings"></a>          
            </h4>
	  </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
          <spam id="open_task_count">Direct Messaging</spam>
        </a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="javascript:" class="nav-link" id="profile-tab">Invoice Distribution</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_groups'); ?>" class="nav-link" id="profile-tab">Groups</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_saved_messages'); ?>" class="nav-link" id="profile-tab">Saved Messages</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="row" style="background: #fff;">
        <div class="col-md-6" style="padding:20px">
          <div class="col-md-12">
            <h5 style="font-weight:600">Message Subject: <spam class="error error_subject"></spam> </h5>
            <input type="text" name="message_subject" class="form-control message_subject" value="<?php echo $subject; ?>">
          </div>
          <div class="col-md-12" style="margin-top:20px">
            <h5 style="font-weight:600">Message Body: <spam class="error error_body"></spam></h5>
            <textarea name="message_body" class="form-control message_body" id="editor_2"><?php echo $message_body; ?></textarea>
          </div>
        </div>
        <div class="col-md-6" style="padding:20px">
          <div class="col-md-12">
            <div class="col-md-4" style="padding:10px;background: #f5f5f5">
              <div class="image_div_attachments" >
                <form action="<?php echo URL::to('user/messageus_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:250px;background: #fff;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <?php
                          if(isset($_GET['message_id']))
                          {
                            $messageid = $_GET['message_id'];
                          }
                          else{
                            $messageid = '';
                          }
                          ?>
                          <input name="message_id" type="hidden" value="<?php echo $messageid; ?>">
                      @csrf
</form>
              </div>
            </div>
            <div class="col-md-8">
              <h5 style="font-weight:600">Files to be appended to this message:</h5>
              <div id="add_attachments_div">
                <?php
                $fileoutput = '';
                if(isset($_GET['message_id']))
                {
                  $files = DB::table('messageus_files')->where('message_id',$_GET['message_id'])->get();
                  if(($files))
                  {
                    foreach($files as $file)
                    {
                      if($file->content == "") { $display="style='display:none'"; }
                      else{ $display="style='display:initial'"; }
                      $fileoutput.="<div class='messageus_attachment' style='width:100%'>
                        <a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a> 
                        <a href='javascript:' class='fa fa-trash remove_dropzone_attach' data-task='".$file->id."'></a> 
                        <a href='javascript:' class='fa fa-text-width notes_attachment' data-task='".$file->id."'></a>
                        <a href='javascript:' class='fa fa-ellipsis-h attach_content attach_content_".$file->id."' title='".$file->content."' ".$display."></a>
                      </div>";
                    }
                  }
                }
                echo $fileoutput;
                ?>
              </div>
              <a href="javascript:" class="fa fa-ellipsis-h view_attachments" data-placement="right" data-popover-content="#add_attachments_div" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="View all Attachments" style="display:none"></a>
            </div>
          </div>
          <div class="col-md-12" id="attachment_content_div" style="margin-top:20px;display:none">
            <h5 id="attachment_content_title" style="font-weight:800"></h5>
            <textarea name="attachment_content" class="form-control attachment_content" id="editor_1"></textarea>
            <a href="javascript:" class="common_black_button add_attachment_comment">Save</a>
          </div>
        </div>
        <div class="col-md-12" style="padding:20px">
          <div class="col-md-12" style="margin-top:20px">
            <a href="javascript:" class="common_black_button send_message_page1" style="font-size:20px;float:right">Choose who to Send this message to</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<script>
<?php
if(isset($_GET['mail_send']))
{
  ?>
  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
  <?php
}
if(isset($_GET['mail_saved']))
{
  ?>
  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Message Saved Successfully</p>", fixed:true});
  <?php
}
?>
$(function(){
  $.ajaxSetup({async:false});
  CKEDITOR.replace('editor_2',
 {
  height: '500px',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode: CKEDITOR.ENTER_P,
  autoParagraph: false,
 });
    $("[data-toggle=popover]").popover({
        html : true,
        content: function() {
          var content = $(this).attr("data-popover-content");
          return $(content).children(".popover-body").html();
        },
        title: function() {
          var title = $(this).attr("data-popover-content");
          return $(title).children(".popover-heading").html();
        }
    });
});
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
$(window).click(function(e) {
  if($(e.target).hasClass('setting_class')) {
    $(".messageus_settings_modal").modal("show");
  }
  if($(e.target).hasClass('messageus_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the MessageUs Email Header Image?");
    if(r) {
      $(".change_header_image").modal("show");

      Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('add_attachment_comment'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      var fileid = $(".attachment_content").attr("data-element");
      var textarea = CKEDITOR.instances['editor_1'].getData();
      <?php
      if(isset($_GET['message_id']))
      {
        $type = "1";
      }
      else{
        $type = "0";
      }
      ?>
      $.ajax({
        url:"<?php echo URL::to('user/messageus_add_comment_to_attachment'); ?>",
        type:"post",
        data:{fileid:fileid,textarea:textarea,type:"<?php echo $type; ?>"},
        success:function(result)
        {
          $("#attachment_content_div").hide();
          if(textarea != "")
          {
            $(".attach_content_"+fileid).show();
          }
          else{
            $(".attach_content_"+fileid).hide();
          }

          $(".attach_content_"+fileid).attr("title",textarea);
          $("body").removeClass("loading");
        }
      })
    },2000);
  }
  if($(e.target).hasClass('send_message_page1'))
  {
    var subject = $(".message_subject").val();
    var message_body = CKEDITOR.instances['editor_2'].getData();
    message_body = message_body.trim();
    <?php
    if(isset($_GET['message_id']))
    {
      $message_id = $_GET['message_id'];
    }
    else{
      $message_id = '';
    }
    ?>
    if(subject == "" && message_body == "")
    {
      $(".error_subject").html("Please enter the Message Subject");
      $(".error_body").html("Please enter the Message Body");
      return false;
    }
    else if(subject == "")
    {
      $(".error_subject").html("Please enter the Message Subject");
      $(".error_body").html("");
      return false;
    }
    else if(message_body == "")
    {
      $(".error_subject").html("");
      $(".error_body").html("Please enter the Message Body");
      return false;
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        $(".error_subject").html("");
        $(".error_body").html("");
        $.ajax({
          url:"<?php echo URL::to('user/save_message_page_one'); ?>",
          type:"post",
          data:{subject:subject,message_body:message_body,message_id:"<?php echo $message_id; ?>"},
          success:function(result)
          {
            window.location.replace("<?php echo URL::to('user/directmessaging_page_two?message_id='); ?>"+result);
          }
        })
      },1000);
    }
  }
  if($(e.target).hasClass('add_notes_attachment'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var fileid = $(e.target).attr("data-task");
      var filename = $(e.target).parents(".messageus_attachment").find(".messageus_attachment_a").html();
      $("#attachment_content_div").show();
      $("#attachment_content_title").html("<spam id='messageus_attachment_p'>"+filename+"</spam> COMMENT:");
      $(".attachment_content").attr("data-element",fileid);

      CKEDITOR.replace('editor_1',
       {
        height: '100px',
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
       });

      $.ajax({
        url:"<?php echo URL::to('user/get_attachment_notes'); ?>",
        type:"post",
        data:{fileid:fileid,type:"0"},
        success:function(result)
        {
          CKEDITOR.instances['editor_1'].setData(result);
          $("body").removeClass("loading");
        }
      })
    },1000);
  }
  if($(e.target).hasClass('notes_attachment'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var fileid = $(e.target).attr("data-task");
      var filename = $(e.target).parents(".messageus_attachment").find(".messageus_attachment_a").html();
      $("#attachment_content_div").show();
      $("#attachment_content_title").html("<spam id='messageus_attachment_p'>"+filename+"</spam> COMMENT:");
      $(".attachment_content").attr("data-element",fileid);

      CKEDITOR.replace('editor_1',
       {
        height: '100px',
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
       });

      $.ajax({
        url:"<?php echo URL::to('user/get_attachment_notes'); ?>",
        type:"post",
        data:{fileid:fileid,type:"1"},
        success:function(result)
        {
          CKEDITOR.instances['editor_1'].setData(result);
          $("body").removeClass("loading");
        }
      })
    },1000);
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/message_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents(".messageus_attachment").detach();
      }
    })
  }

  if($(e.target).hasClass('remove_dropzone_attach_add'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/messageus_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $("#add_attachments_div").html(result);
      }
    })
  }
});

var valueTimmer;                //timer identifier
var valueInterval = 1000;  //time in ms, 5 second for example
CKEDITOR.on('instanceCreated', function (e) {
  e.editor.on('contentDom', function() {
      e.editor.document.on('keyup', function(event) {
          clearTimeout(valueTimmer);
          var value = CKEDITOR.instances['editor_1'].getData();
          valueTimmer = setTimeout(doneTyping, valueInterval,value);
      });
  });
});
function doneTyping(textarea) {
    var fileid = $(".attachment_content").attr("data-element");
    <?php
    if(isset($_GET['message_id']))
    {
      $type = "1";
    }
    else{
      $type = "0";
    }
    ?>
    $.ajax({
      url:"<?php echo URL::to('user/messageus_add_comment_to_attachment'); ?>",
      type:"post",
      data:{fileid:fileid,textarea:textarea,type:"<?php echo $type; ?>"},
      success:function(result)
      {

        if(textarea != "")
          {
            $(".attach_content_"+fileid).show();
            $(".attach_content_"+fileid).attr("title",textarea);
          }
          else{
            $(".attach_content_"+fileid).hide();
            $(".attach_content_"+fileid).attr("title",textarea);
          }
      }
    })
}

fileList = new Array();
Dropzone.options.imageUpload1 = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width notes_attachment' data-task='"+obj.file_id+"'></a><a href='javascript:' class='fa fa-ellipsis-h attach_content attach_content_"+obj.file_id+"' title='' style='display:none'></a></div>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach_add' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width add_notes_attachment' data-task='"+obj.file_id+"'></a><a href='javascript:' class='fa fa-ellipsis-h attach_content attach_content_"+obj.file_id+"' title='' style='display:none'></a></div>");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUpload1").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
          }
          var pcount = $(".messageus_attachment").length;
          if(pcount > 8)
          {
            // $(".view_attachments").show();

            $("[data-toggle=popover]").popover({
                html : true,
                content: function() {
                  var content = $(this).attr("data-popover-content");
                  return $(content).children(".popover-body").html();
                },
                title: function() {
                  var title = $(this).attr("data-popover-content");
                  return $(title).children(".popover-heading").html();
                }
            });
          }
          else{
            $(".view_attachments").hide();
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
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

Dropzone.options.ImageUploadEmail = {
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
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".messageus_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">MessageUs Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".messageus_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">MessageUs Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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
