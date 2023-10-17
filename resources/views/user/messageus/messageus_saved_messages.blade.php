@extends('userheader')
@section('content')
<style>
.highlight_group td{ background: green !important;color:#fff !important; }

.highlight_client td{ background: green !important;color:#fff !important; }
.highlight_selected td{ background: green !important;color:#fff !important; }

.hide_group_div {display:none;}

#group_tbody td{ cursor: pointer }
#client_tbody td,#selected_tbody td{ cursor: pointer }

.client_inactive td,.selected_inactive td {color:#f00 !important;}
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
.messageus_attachment:nth-child(n+9) {
   display:none;
}
</style>
<script src="<?php echo URL::to('public/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/ckeditor/samples/js/sample.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>

<!-- <link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>"> -->
<link rel="stylesheet" href="<?php echo URL::to('public/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<div class="modal fade create_new_group_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Create New Group</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">  
            <div class="col-md-12">
              <h5>Group Name:</h5>
            </div>
            <div class="col-md-12">
              <input type="text" name="group_name_new" class="form-control group_name_new" value="">
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="common_black_button create_group_button" id="create_group_button" value="Create New Group">
          </div>
        </div>
  </div>
</div>
<div class="content_section">
	<div id="fixed-header" style="width:100%;">
	  <div class="page_title" style="z-index:999;">
	  	<h4 class="col-lg-12 padding_00 new_main_title">
                MessageUs - Central Client Messaging System             
            </h4>
	  </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/directmessaging'); ?>" class="nav-link">
          Direct Messaging
        </a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="javascript:" class="nav-link" id="profile-tab">Invoice Distribution</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_groups'); ?>" class="nav-link" id="profile-tab">Groups</a>
      </li>
      <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
        <a href="" class="nav-link" id="profile-tab">Saved Messages</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane active in"  id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="col-md-12" style="padding:20px; background: #fff">
        <div class="col-md-12">
          <h5 style="font-weight:800">Saved Messages</h5>
        </div>
        <div class="col-md-12">
            <table class="table display nowrap fullviewtablelist own_table_white2" id="table_saved_message">
              <thead>
                <tr>
                  <th style="text-align: left">Date Saved</th>
                  <th style="text-align: left">Message From</th>
                  <th style="text-align: left">Subject</th>
                  <th style="text-align: left">Number Of Attachment</th>
                  <th style="text-align: left">Action</th>
                </tr>
              </thead>
              <tbody id="group_tbody">
                <?php
                if(($messages))
                {
                  foreach($messages as $message)
                  {
                    $from = $message->message_from;
                    if($from == 0)
                    {
                      $mess_from = '';
                    }
                    else{
                      $userdetails = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$from)->first();
                      $mess_from = $userdetails->lastname.' '.$userdetails->firstname;
                    }
                    $count_files = DB::table('messageus_files')->where('message_id',$message->id)->count();

                    echo '<tr>
                      <td>'.date('d-M-Y', strtotime($message->date_saved)).'</td>
                      <td>'.$mess_from.'</td>
                      <td>'.$message->subject.'</td>
                      <td>'.$count_files.'</td>
                      <td>
                        <a href="javascript:" data-element="'.URL::to('user/delete_saved_message?message_id='.$message->id).'" class="fa fa-trash delete_message" title="Delete Messsage"></a>
                        <a href="'.URL::to('user/directmessaging?message_id='.$message->id).'" class="fa fa-arrow-right" title="Activate Message" style="margin-left:20px"></a>
                      </td>
                    </tr>';
                  }
                }
                ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<input type="hidden" name="hidden_client_ids" id="hidden_client_ids" value="">
<input type="hidden" name="hidden_selected_ids" id="hidden_selected_ids" value="">
<input type="hidden" name="hidden_inactive" id="hidden_inactive" value="0">

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
  if($(e.target).hasClass("delete_message"))
  {
    e.preventDefault();
    var r = confirm("Are you sure you want to delete this Message?");
    if(r)
    {
      var href = $(e.target).attr("data-element");
      window.location.replace(href);
    }
  }
  if($(e.target).hasClass('group_td'))
  {
    $("#group_tbody").find("tr").removeClass("highlight_group");
    var group_id = $(e.target).parents("tr").attr("data-element");
    $(e.target).parents("tr").addClass("highlight_group");
    $("#client_tbody").find("tr").removeClass("highlight_client");
    $(".hide_inactive").html("Hide Inactive Members");
    $(".hide_inactive").removeClass('show_inactive');

    $(".client_tr").show();
    $.ajax({
      url:"<?php echo URL::to('user/select_messageus_group'); ?>",
      type:"post",
      data:{group_id:group_id},
      dataType:"json",
      success:function(result)
      {
        $("#selected_grp_name").html(result['group_name']);
        $(".delete_group").attr("data-element",group_id);
        $("#selected_tbody").html(result['selected_clients']);
        var clientids = result['client_ids'].split(",");

        $.each(clientids, function(index,value) {
          $("#client_tr_"+value).hide();
        });
        $(".hide_group_div").show();
      }
    });
  }
  if($(e.target).hasClass('client_td'))
  {
    var client_id = $(e.target).parents("tr").attr("data-element");
    if($(e.target).parents("tr").hasClass("highlight_client"))
    {
      $(e.target).parents("tr").removeClass("highlight_client");
    }
    else{
      $(e.target).parents("tr").addClass("highlight_client");
    }
    $("#selected_tbody").find("tr").removeClass("highlight_selected");
    $("#hidden_selected_ids").val("");

    var client_ids = '';
    $(".highlight_client").each(function(){
      var cli_id = $(this).attr("data-element");
      if(client_ids == "")
      {
        client_ids = cli_id;
      }
      else{
        client_ids = client_ids+','+cli_id;
      }
    });
    $("#hidden_client_ids").val(client_ids);
  }
  if($(e.target).hasClass('selected_td'))
  {
    var client_id = $(e.target).parents("tr").attr("data-element");
    if($(e.target).parents("tr").hasClass("highlight_selected"))
    {
      $(e.target).parents("tr").removeClass("highlight_selected");
    }
    else{
      $(e.target).parents("tr").addClass("highlight_selected");
    }
    $("#client_tbody").find("tr").removeClass("highlight_client");
    $("#hidden_client_ids").val("");
    
    var client_ids = '';
    $(".highlight_selected").each(function(){
      var cli_id = $(this).attr("data-element");
      if(client_ids == "")
      {
        client_ids = cli_id;
      }
      else{
        client_ids = client_ids+','+cli_id;
      }
    });
    $("#hidden_selected_ids").val(client_ids);
  }
  if($(e.target).hasClass('create_new_group'))
  {
      $(".create_new_group_modal").modal("show");
  }
  if($(e.target).hasClass('create_group_button'))
  {
    var grp_name = $(".group_name_new").val();
    if(grp_name == "")
    {
      alert("Please enter the Group Name");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/create_group_name'); ?>",
        type:"post",
        data:{grp_name:grp_name},
        dataType:"json",
        success:function(result)
        {
          $("#group_tbody").append(result['group_tr']);
          $("#selected_grp_name").html(result['group_name']);
          $(".delete_group").attr("data-element",result['group_id']);

          $(".hide_group_div").show();
          $(".create_new_group_modal").modal("hide");
        }
      })
    }
  }
  if($(e.target).hasClass('hide_inactive'))
  {
    if($(e.target).hasClass('show_inactive'))
    {
      $("#client_tbody").find(".client_inactive").show();
      $(e.target).html("Hide Inactive Members");
      $(e.target).removeClass('show_inactive');
    }
    else{
      $("#client_tbody").find(".client_inactive").hide();
      $(e.target).html("Show Inactive Members");
      $(e.target).addClass('show_inactive');
    }
  }
  if($(e.target).hasClass('add_selected'))
  {
    var client_ids = $("#hidden_client_ids").val();
    if(client_ids == "")
    {
      alert("Please select atleast one client to add in this Group.");
      return false;
    }
    else{
      var grp_id = $(".highlight_group").attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/add_selected_member_to_group'); ?>",
        type:"post",
        data:{grp_id:grp_id,client_ids:client_ids},
        success:function(result)
        {
          $("#selected_tbody").append(result);
          var clientids = client_ids.split(",");
          $.each(clientids, function(index,value) {
            $("#client_tr_"+value).hide();
          });
          $(".client_tr").removeClass("highlight_client");
          $(".selected_tr").removeClass("highlight_selected");
          $("#hidden_client_ids").val("");
          $("#hidden_selected_ids").val("");
          var len = $(".selected_tr").length;
          $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);
        }
      })
    }
  }
  if($(e.target).hasClass('remove_selected'))
  {
    var client_ids = $("#hidden_selected_ids").val();
    if(client_ids == "")
    {
      alert("Please select atleast one client to remove from this Group.");
      return false;
    }
    else{
      var grp_id = $(".highlight_group").attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/remove_selected_member_to_group'); ?>",
        type:"post",
        data:{grp_id:grp_id,client_ids:client_ids},
        success:function(result)
        {
          var clientids = client_ids.split(",");
          $.each(clientids, function(index,value) {
            $("#selected_tr_"+value).detach();
            $("#client_tr_"+value).show();
          });

          $(".client_tr").removeClass("highlight_client");
          $(".selected_tr").removeClass("highlight_selected");

          $("#hidden_client_ids").val("");
          $("#hidden_selected_ids").val("");

          var len = $(".selected_tr").length;
          $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);
        }
      })
    }
  }
  if($(e.target).hasClass('remove_inactive'))
  {
    var r = confirm("Are you sure you want to remove all the Inactive Clients from this Group?");
    if(r)
    {
      var client_ids = '';
      $(".selected_inactive").each(function() {
        if(client_ids == "")
        {
          client_ids = $(this).attr("data-element");
        }
        else{
          client_ids = client_ids+','+$(this).attr("data-element");
        }
      });

      var grp_id = $(".highlight_group").attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/remove_selected_member_to_group'); ?>",
        type:"post",
        data:{grp_id:grp_id,client_ids:client_ids},
        success:function(result)
        {
          var clientids = client_ids.split(",");
          $.each(clientids, function(index,value) {
            $("#selected_tr_"+value).detach();
            $("#client_tr_"+value).show();
          });

          $(".client_tr").removeClass("highlight_client");
          $(".selected_tr").removeClass("highlight_selected");

          var len = $(".selected_tr").length;
            $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);
        }
      });
    }
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

$(function(){
$('#table_saved_message').DataTable({
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
})
</script>
@stop
