@extends('userheader')
@section('content')
<style>
.inactive_client td { color:#f00; }
.table td, .table th{ text-align:left; background: #fff }
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
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>

<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">

<div class="content_section">
	<div id="fixed-header" style="width:100%;">
	  <div class="page_title" style="z-index:999;">
	  	<h4 class="col-lg-12 padding_00 new_main_title">
                MessageUs - Central Client Messaging System             
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
      <div class="col-md-12" style="background: #fff;padding:20px">
        <div class="col-md-12">
          <h5>Send Message To:</h5>
          <?php
          $message_details = DB::table('messageus')->where('id',$_GET['message_id'])->first();
          ?>
          <div class="col-md-12"><input type="radio" name="group_type" class="group_type" id="group_type_1" value="1" checked><label for="group_type_1">All Clients</label></div>
          <div class="col-md-12"><input type="radio" name="group_type" class="group_type" id="group_type_2" value="2" <?php if($message_details->group_type == "2") { echo 'checked'; } ?>><label for="group_type_2">Active Clients</label></div>
          <div class="col-md-12"><input type="radio" name="group_type" class="group_type" id="group_type_3" value="3" <?php if($message_details->group_type == "3") { echo 'checked'; } ?>><label for="group_type_3">Inactive Clients</label></div>
          <div class="col-md-3">
            <input type="radio" name="group_type" class="group_type" id="group_type_4" value="4" <?php if($message_details->group_type == "4") { echo 'checked'; } ?>><label for="group_type_4">Group</label>
          </div>
          <div class="col-md-9 group_select_div" style="<?php if($message_details->group_type == "4") { echo 'display:block'; } else { echo 'display:none'; } ?>">
            <select name="select_group" class="select_group form-control" style="width:40%;">
              <option value="">Select Group</option>
              <?php
              $groups = DB::table('messageus_groups')->get();
              if(($groups))
              {
                foreach($groups as $group)
                {
                  if($message_details->group_id == $group->id) { $selected = 'selected'; }
                  else { $selected = ''; }
                  echo '<option value="'.$group->id.'" '.$selected.'>'.$group->group_name.'</option>';

                }
              }
              ?>
            </select>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:20px; ">
          <h5 style="font-weight:600; ">List of Clients to Send this Message to: <spam class="error error_subject"></spam> </h5>
          <div class="table-responsive" style="min-height:400px;max-height: 300px;overflow-y: scroll;">
            <input type="checkbox" name="select_all_client" class="select_all_client" value="1" id="select_all_client" ><label for="select_all_client">Select All Clients</label>

                  <input type="checkbox" name="deselect_all_inactive_client" class="deselect_all_inactive_client" value="1" id="deselect_all_inactive_client" ><label for="deselect_all_inactive_client">Deselect all Inactive Clients</label>
          <table class="table display nowrap fullviewtablelist own_table_white2" id="client_table" style="margin-top: 30px;">
            <thead>
              
              <tr>
                <th style=""></th>
                <th>Code</th>
                <th>Details</th>
                <th>Primary Email</th>
                <th>Secondary Email</th>
              </tr>
            </thead>
            <tbody id="clients_tbody">
              <?php
              $client_ids = explode(",",$message_details->client_ids);
              $primary_emails = explode(",",$message_details->primary_emails);
              $secondary_emails = explode(",",$message_details->secondary_emails);

              $i = 1;
              if(($clients))
              {
                foreach($clients as $client)
                {
                  $grp_cls = '';
                  if(($groups))
                  {
                    foreach($groups as $group)
                    {
                      $group_clients = explode(",",$group->client_ids);
                      if(in_array($client->client_id,$group_clients))
                      {
                        if($grp_cls == "")
                        {
                          $grp_cls = 'group_'.$group->id;
                        }
                        else{
                          $grp_cls = $grp_cls.' group_'.$group->id;
                        }
                      }
                    }
                  }

                  if($client->active == "2") { $cls = 'inactive_client'; }
                  else{ $cls = 'active_client'; }

                  
                  if(in_array($client->client_id,$client_ids)) { $client_selected = 'checked'; }
                  else { $client_selected = ''; }

                  if(in_array($client->email,$primary_emails)) { $primary_selected = 'checked'; }
                  else { $primary_selected = ''; }

                  if(in_array($client->email2,$secondary_emails)) { $secondary_selected = 'checked'; }
                  else { $secondary_selected = ''; }
                  ?>
                  <tr class="client_tr <?php echo $cls; ?> <?php echo $grp_cls; ?>">
                    <td><input type="checkbox" name="select_client" class="select_client" value="<?php echo $client->client_id; ?>" id="select_client_<?php echo $client->client_id; ?>" <?php echo $client_selected; ?>><label for="select_client_<?php echo $client->client_id; ?>">&nbsp;</label></td>
                    <td><?php echo $client->client_id; ?></td>
                    <td><?php echo $client->company; ?></td>
                    <td>
                      <?php
                      if($client->email != "")
                      {
                        ?>
                        <input type="checkbox" name="primary_email" class="primary_email" value="<?php echo $client->email; ?>" id="primary_email_<?php echo $client->client_id; ?>" <?php echo $primary_selected; ?>>
                        <?php
                      }
                      ?>
                      <label for="primary_email_<?php echo $client->client_id; ?>"><?php echo $client->email; ?></label>
                    </td>
                    <td>
                      <?php
                      if($client->email2 != "")
                      {
                        ?>
                        <input type="checkbox" name="secondary_email" class="secondary_email" value="<?php echo $client->email2; ?>" id="secondary_email_<?php echo $client->client_id; ?>" <?php echo $secondary_selected; ?>>
                        <?php
                      }
                      ?>
                      <label for="secondary_email_<?php echo $client->client_id; ?>"><?php echo $client->email2; ?></label>
                    </td>
                  </tr>
                  <?php
                }
              }
              ?>
            </tbody>
          </table>
          </div>
        </div>
        
        <div class="col-md-12" style="margin-top:20px">
          <a href="<?php echo URL::to('user/directmessaging?message_id='.$_GET['message_id']); ?>" class="common_black_button" style="font-size:20px;float: left;">Back to Message Screen</a>
          <a href="javascript:" class="common_black_button send_message_page2" style="font-size:20px;float:right">Summary and Send</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<script>

$(function(){
  $.ajaxSetup({async:false});
  $('#client_table').DataTable({
      fixedHeader: {
        headerOffset: 75
      },
      autoWidth: true,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false
  });
  <?php if($message_details->group_type == "1") { ?>$(".client_tr").show(); <?php } 
   elseif($message_details->group_type == "2") { ?>$(".client_tr").hide(); $(".active_client").show(); <?php } 
   elseif($message_details->group_type == "3") { ?>$(".client_tr").hide(); $(".inactive_client").show(); <?php } 
   elseif($message_details->group_type == "4") { ?>$(".client_tr").hide(); $(".group_<?php echo $message_details->group_id ?>").show(); <?php } ?>
});
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
$(window).change(function(e){
  if($(e.target).hasClass('select_group'))
  {
    var grp_id = $(e.target).val();
    $(".select_client").prop("checked",false);
    $(".primary_email").prop("checked",false);
    $(".secondary_email").prop("checked",false);
    $(".select_all_client").prop("checked",false);
    $(".deselect_all_inactive_client").prop("checked",false);
    
    $(".client_tr").hide();
    $(".group_"+grp_id).show();
  }
})
$(window).click(function(e) {
  if($(e.target).hasClass('select_all_client'))
  {
    if($(e.target).is(":checked"))
    {
      $(".select_client:visible").prop("checked",true);
      $(".primary_email:visible").prop("checked",true);
      $(".secondary_email:visible").prop("checked",true);
    }
    else{
      $(".select_client:visible").prop("checked",false);
      $(".primary_email:visible").prop("checked",false);
      $(".secondary_email:visible").prop("checked",false);
    }
    $(".deselect_all_inactive_client").prop("checked",false);
  }
  if($(e.target).hasClass('deselect_all_inactive_client'))
  {
    if($(e.target).is(":checked"))
    {
      $(".inactive_client").find(".select_client").prop("checked",false);
      $(".inactive_client").find(".primary_email").prop("checked",false);
      $(".inactive_client").find(".secondary_email").prop("checked",false);
    }
    $(".select_all_client").prop("checked",false);
  }
  if($(e.target).hasClass('select_client'))
  {
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("tr").find(".primary_email").prop("checked",true);
      $(e.target).parents("tr").find(".secondary_email").prop("checked",true);
    }
    else{
      $(e.target).parents("tr").find(".primary_email").prop("checked",false);
      $(e.target).parents("tr").find(".secondary_email").prop("checked",false);
    }
  }
  if($(e.target).hasClass('primary_email'))
  {
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("tr").find(".select_client").prop("checked",true);
    }
    else{
      if($(e.target).parents("tr").find(".secondary_email").is(":checked"))
      {
        $(e.target).parents("tr").find(".select_client").prop("checked",true);
      }
      else{
        $(e.target).parents("tr").find(".select_client").prop("checked",false);
      }
    }
  }
  if($(e.target).hasClass('secondary_email'))
  {
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("tr").find(".select_client").prop("checked",true);
    }
    else{
      if($(e.target).parents("tr").find(".primary_email").is(":checked"))
      {
        $(e.target).parents("tr").find(".select_client").prop("checked",true);
      }
      else{
        $(e.target).parents("tr").find(".select_client").prop("checked",false);
      }
    }
  }
  if($(e.target).hasClass('group_type'))
  {
    $(".select_client").prop("checked",false);
    $(".primary_email").prop("checked",false);
    $(".secondary_email").prop("checked",false);
    $(".select_all_client").prop("checked",false);
    $(".deselect_all_inactive_client").prop("checked",false);

    var value = $(".group_type:checked").val();
    if(value == "4")
    {
      $(".client_tr").hide();
      $(".group_select_div").show();
    }
    else{
      if(value == "1")
      {
        $(".client_tr").show();
      }
      else if(value == "2")
      {
        $(".client_tr").hide();
        $(".active_client").show();
      }
      else if(value == "3")
      {
        $(".client_tr").hide();
        $(".inactive_client").show();
      }
      $(".group_select_div").hide();
    }
  }
  if($(e.target).hasClass('send_message_page2'))
  {
    var primary_emails = "";
    var secondary_emails = "";
    var client_ids = '';
    $(".primary_email:checked").each(function() {
      if(primary_emails == "")
      {
        primary_emails = $(this).val();
      }
      else{
        primary_emails = primary_emails+","+$(this).val();
      }
    });
    $(".secondary_email:checked").each(function() {
      if(secondary_emails == "")
      {
        secondary_emails = $(this).val();
      }
      else{
        secondary_emails = secondary_emails+","+$(this).val();
      }
    });   
    $(".select_client:checked").each(function() {
      if(client_ids == "")
      {
        client_ids = $(this).val();
      }
      else{
        client_ids = client_ids+","+$(this).val();
      }
    });    
    if(primary_emails == "" && secondary_emails == "")
    {
      alert("Please Select atleast any one of the client email address to send the email");
      return false;
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        var group_type = $(".group_type:checked").val();
        var group_id = $(".select_group").val();
        $.ajax({
          url:"<?php echo URL::to('user/save_message_page_two'); ?>",
          type:"post",
          data:{primary_emails:primary_emails,secondary_emails:secondary_emails,client_ids:client_ids,message_id:"<?php echo $_GET['message_id']; ?>",group_type:group_type,group_id:group_id},
          success:function(result)
          {
            window.location.replace("<?php echo URL::to('user/directmessaging_page_three?message_id='.$_GET['message_id']); ?>");
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
      $("#attachment_content_title").html(filename+" COMMENT:");
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
      $("#attachment_content_title").html(filename+" COMMENT:");
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
              file.previewElement.innerHTML = "<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width notes_attachment' data-task='"+obj.file_id+"'></a></div>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach_add' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width add_notes_attachment' data-task='"+obj.file_id+"'></a></div>");
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
            $(".view_attachments").show();

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
</script>
@stop
