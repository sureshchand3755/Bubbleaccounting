@extends('userheader')
@section('content')
<style>
.link_div{ cursor: pointer;margin-top:10px; }
.highlight_group td{ background: #ffff00 !important; }


.hide_group_div {display:none;}

#group_tbody td{ cursor: pointer }

/*#group_table th,#client_table th,#selected_table th{ background: #000;color:#fff; }*/

.selected_donot_complete td{ color:blue !important; }
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
<div class="modal fade create_new_group_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 13%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:25%">
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
            <div class="col-md-12" style="margin-top:15px">
              <input type="checkbox" name="pms_group_clients" id="pms_group_clients" class="pms_group_clients" value=""><label for="pms_group_clients">Group based on clients from PMS</label>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">
            <input type="button" class="common_black_button create_group_button" id="create_group_button" value="Create New Group">
          </div>
        </div>
  </div>
</div>
<div class="modal fade pms_group_messageus_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 4%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">PMS Group Members</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">  
            <input type="checkbox" name="select_all_pms_clients" class="select_all_pms_clients" id="select_all_pms_clients"><label for="select_all_pms_clients">Select All</label>
            <div style="max-height: 500px;overflow-y: scroll">
              <table class="table" id="messageus_pms_expand">
                <thead>
                  <th style="width:10%">S.No</th>
                  <th style="width:12%">Client ID</th>
                  <th style="width:46%">Client Name</th>
                  <th>Active Processing clients</th>
                </thead>
                <tbody id="messageus_pms_tbody">

                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="common_black_button finalise_group_setup" id="finalise_group_setup" value="Finalise Group Setup">
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
      <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
        <a href="javascript:" class="nav-link" id="profile-tab">Groups</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_saved_messages'); ?>" class="nav-link" id="profile-tab">Saved Messages</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="col-md-12" style="background: #fff;padding:20px;min-height:700px">
        <div class="col-md-5">
            <div class="col-md-12">
              <a href="javascript:" class="create_new_group common_black_button" style="float:right">Create New Group</a>
            </div>
            <div class="col-md-12">
              <h5 style="font-weight:600">Groups on File:</h5>
            </div>
            <div class="col-md-12">
              
                <table class="table display nowrap fullviewtablelist own_table_white2" id="group_table">
                  <thead>
                    <th style="text-align: left">Group Name</th>
                    <th style="text-align: left">Members</th>
                  </thead>
                  <tbody id="group_tbody">
                    <?php
                    if(($groups))
                    {
                      foreach($groups as $group)
                      {
                        $exp_group_clients = explode(",",$group->client_ids);
                        if($group->client_ids == "")
                        {
                          $group_client_count = 0;
                        }
                        else{
                          $group_client_count = count($exp_group_clients);
                        }
                        echo '<tr class="group_tr" id="group_tr_'.$group->id.'" data-element="'.$group->id.'">
                          <td class="group_td" width:60%">'.$group->group_name.'</td>
                          <td class="group_td" style="width:40%;text-align:right">'.$group_client_count.'</td>
                        </tr>';
                      }
                    }
                    ?>
                  </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-7 hide_group_div" style="margin-top: 30px;">
            <label style="font-weight:600;margin-top: 8px;"> Clients that are NOT in the Selected Group:</label>
          </div>
          <div class="col-md-5 hide_group_div" style="margin-top: 30px;">
            <label style="font-weight:600;margin-top: 8px;"> Clients that are in the Selected Group:</label>
            <label style="font-weight:600;text-decoration: underline" id="selected_grp_name"></label>
            <a href="javascript:" class="common_black_button delete_group" data-element="" style="float:right;display:none">Delete Group</a>
            <a href="javascript:" class="common_black_button update_group" data-element="1" style="float:right">Update Group</a>
            <a href="javascript:" class="common_black_button remove_inactive remove_payroll_inactive" data-element="1" style="float:right">Remove Inactive Payroll Clients from Group</a>
          </div>
          <div class="col-md-5 hide_group_div" style="margin-top:10px;background: #fff;padding:10px;min-height:400px;max-height: 400px;overflow-y: scroll;">
            <table class="table display nowrap fullviewtablelist own_table_white2" id="client_table" style="">
              <thead>
              	<th style="text-align: left"></th>
                <th style="text-align: left">ClientID</th>
                <th style="text-align: left">Company</th>
                <th style="text-align: left">Email</th>
              </thead>
              <tbody id="client_tbody">
                  <?php
                  if(($clients))
                  {
                    foreach($clients as $client)
                    {
                      if($client->active == "2") { $cls= 'client_inactive'; } 
                      else { $cls = 'client_active'; }

                      echo '<tr class="client_tr '.$cls.'" id="client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">
                      	<td class="client_td"><input type="checkbox" name="client_exclude" class="client_exclude" value="'.$client->client_id.'"><label>&nbsp;</label></td>
                        <td class="client_td">'.$client->client_id.'</td>
                        <td class="client_td">'.$client->company.'</td>
                        <td class="client_td">'.$client->email.'</td>
                      </tr>';
                    }
                  }
                  ?>
              </tbody>
            </table>
          </div>
          <div class="col-md-2 hide_group_div" style="margin-top:10px;vertical-align: middle;">
            <div class="common_black_button link_div add_all" style="font-size:14px;width:100%;margin-top:80px">Add All Members <i class="fa fa-arrow-right" aria-hidden="true"></i></div>
            <div class="common_black_button link_div add_selected" style="font-size:14px;width:100%;">Add Selected Members <i class="fa fa-arrow-right" aria-hidden="true"></i></div>
            <div class="common_black_button link_div hide_inactive" style="font-size:14px;width:100%">Hide Inactive Members</div><br/><br/>
            <div class="common_black_button link_div remove_selected" style="font-size:14px;width:100%"><i class="fa fa-arrow-left" aria-hidden="true"></i> Remove Selected Members</div>
            <div class="common_black_button link_div remove_inactive" style="font-size:14px;width:100%"><i class="fa fa-arrow-left" aria-hidden="true"></i> Remove Inactive Members</div>
          </div>
          <div class="col-md-5 hide_group_div" style="margin-top:10px;background: #fff;padding:10px;min-height:400px;max-height: 400px;overflow-y: scroll;">
            <table class="table display nowrap fullviewtablelist own_table_white2" id="selected_table" style="">
              <thead>
              	<th style="text-align: left"></th>
                <th style="text-align: left">ClientID</th>
                <th style="text-align: left">Company</th>
                <th style="text-align: left">Email</th>
              </thead>
              <tbody id="selected_tbody">
                  
              </tbody>
            </table>
          </div>
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
  $("body").addClass("loading");
  $('#group_table').DataTable({
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
  $('#selected_table').DataTable({
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
  $('#messageus_pms_expand').DataTable({
      fixedHeader: false,
      autoWidth: false,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false,
      columnDefs: [
         { orderable: false, targets: -1 }
      ]
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
    setTimeout(function() {
      $(".group_td:first").trigger("click");
    },1000)
    
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
  if($(e.target).hasClass("delete_group"))
  {
  	$("#group_table").dataTable().fnDestroy();
  	$("#client_table").dataTable().fnDestroy();
  	$("#selected_table").dataTable().fnDestroy();

    var r = confirm("Are you sure you want to delete this Group?");
    if(r)
    {
      var grp_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/delete_messageus_groups'); ?>",
        type:"post",
        data:{grp_id:grp_id},
        success:function(result)
        {
          $(".hide_group_div").hide();
          $("#group_tr_"+grp_id).detach();

          $('#group_table').DataTable({
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
		  $('#selected_table').DataTable({
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
        }
      })
    }
  }
  if($(e.target).hasClass("update_group"))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      $("#group_table").dataTable().fnDestroy();
      $("#client_table").dataTable().fnDestroy();
      $("#selected_table").dataTable().fnDestroy();

      var r = confirm("Are you sure you want to update this Group from PMS current week and month?");
      if(r)
      {
        var grp_id = $(e.target).attr("data-element");
        $.ajax({
          url:"<?php echo URL::to('user/update_pms_groups'); ?>",
          type:"post",
          data:{grp_id:grp_id},
          dataType:"json",
          success:function(result)
          {
            $("#selected_tbody").html(result['selected']);

            var clientids = result['client_ids'].split(",");
            $.each(clientids, function(index,value) {
              $("#client_tr_"+value).hide();
            });
            $(".client_exclude").prop("checked",false);
          	$(".client_include").prop("checked",false);
            $("#hidden_client_ids").val("");
            $("#hidden_selected_ids").val("");

            $('#group_table').DataTable({
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
            $('#selected_table').DataTable({
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
            $("body").removeClass("loading");
          }
        })
      }
    },1000);
  }
  if($(e.target).hasClass('group_td'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
    	$("#group_table").dataTable().fnDestroy();
    	$("#client_table").dataTable().fnDestroy();
    	$("#selected_table").dataTable().fnDestroy();

      $("#group_tbody").find("tr").removeClass("highlight_group");
      var group_id = $(e.target).parents("tr").attr("data-element");
      $(e.target).parents("tr").addClass("highlight_group");
      $(".client_exclude").prop("checked",false);
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
          if(group_id == "1")
          {
            $(".delete_group").hide();
            $(".update_group").show();
            $(".remove_payroll_inactive").show();
          }
          else{
            $(".update_group").hide();
            $(".delete_group").show();
            $(".remove_payroll_inactive").hide();
          }
          $('#group_table').DataTable({
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
  		  $('#selected_table').DataTable({
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
        $("body").removeClass("loading");
        }
      });
    },1000);
  }
  if($(e.target).hasClass('client_exclude'))
  {
    var client_ids = '';
    $(".client_exclude:checked").each(function(){
      var cli_id = $(this).val();
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
  if($(e.target).hasClass('client_include'))
  {
    var client_ids = '';
    $(".client_include:checked").each(function(){
      var cli_id = $(this).val();
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
      $(".pms_group_clients").prop("checked",false);
  }
  if($(e.target).hasClass("select_all_pms_clients")){
    if($(e.target).is(":checked")){
      $(".select_messageus_pms_client:visible").prop("checked",true);
    }
    else{
      $(".select_messageus_pms_client").prop("checked",false);
    }
  }
  if($(e.target).hasClass('donot_process_message_us')){
    if($(e.target).is(":checked")){
      if($(e.target).hasClass('until_further_notice')){
        $(e.target).parents("tr").find("td").css("color","#f00");
        $(e.target).parents("tr").find("label").css("color","#f00");
        $(e.target).parents("tr").find(".this_period_only").prop("checked",false);
      }
      else{
        $(e.target).parents("tr").find("td").css("color","blue");
        $(e.target).parents("tr").find("label").css("color","blue");
        $(e.target).parents("tr").find(".until_further_notice").prop("checked",false);
      }
      $(e.target).parents("tr").find(".select_messageus_pms_client").prop("checked",false);
      $(e.target).parents("tr").find(".select_messageus_pms_client").hide();
      $(e.target).parents("tr").find(".select_messageus_pms_client_label").hide();
    }
    else{
      $(e.target).parents("tr").find(".this_period_only").prop("checked",false);
      $(e.target).parents("tr").find(".until_further_notice").prop("checked",false);

      $(e.target).parents("tr").find("td").css("color","#000");
      $(e.target).parents("tr").find("label").css("color","#000");

      $(e.target).parents("tr").find(".select_messageus_pms_client").prop("checked",false);
      $(e.target).parents("tr").find(".select_messageus_pms_client").show();
      $(e.target).parents("tr").find(".select_messageus_pms_client_label").show();
    }
  }
  if($(e.target).hasClass('create_group_button'))
  {
    var grp_name = $(".group_name_new").val();
    if(grp_name == "")
    {
      alert("Please enter the Group Name");
    }
    else{
      if($(".pms_group_clients").is(":checked")){
        $("body").addClass('loading');
        $("#messageus_pms_expand").dataTable().fnDestroy();
        setTimeout(function(result){
          $.ajax({
            url:"<?php echo URL::to('user/get_pms_clients'); ?>",
            type:"post",
            success:function(result){
              $("body").removeClass('loading');
              $(".pms_group_messageus_overlay").modal("show");
              $("#messageus_pms_tbody").html(result);
              $("#select_all_pms_clients").prop("checked",false);

              $('#messageus_pms_expand').DataTable({
                  fixedHeader: false,
                  autoWidth: false,
                  scrollX: false,
                  fixedColumns: false,
                  searching: false,
                  paging: false,
                  info: false,
                  columnDefs: [
                     { orderable: false, targets: -1 }
                  ]
              });
            }
          })
        },500);
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/create_group_name'); ?>",
          type:"post",
          data:{grp_name:grp_name},
          dataType:"json",
          success:function(result)
          {
            $("#group_table").dataTable().fnDestroy();
            $("#client_table").dataTable().fnDestroy();
            $("#selected_table").dataTable().fnDestroy();
            $("#group_tbody").find("tr").removeClass("highlight_group");
            $("#group_tbody").append(result['group_tr']);
            $("#selected_grp_name").html(result['group_name']);
            $(".delete_group").attr("data-element",result['group_id']);
            $(".group_name_new").val("");
            $(".hide_group_div").show();
            $(".create_new_group_modal").modal("hide");
            $("#selected_tbody").html("");
            $(".client_tr").show();
            $('#group_table').DataTable({
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
            $('#selected_table').DataTable({
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
          }
        })
      }
    }
  }
  if($(e.target).hasClass('finalise_group_setup')){
    var clientid = '';
    $(".select_messageus_pms_client:checked").each(function(){
      if(clientid == ''){
        clientid = $(this).attr("data-element");
      }
      else{
        clientid = clientid+','+$(this).attr("data-element");
      }
    });

    var group_name = $(".group_name_new").val();
    $.ajax({
      url:"<?php echo URL::to('user/create_messageus_pms_groups'); ?>",
      type:"post",
      data:{group_name:group_name,clientid:clientid},
      dataType:"json",
      success:function(result){
        $("#group_table").dataTable().fnDestroy();
        $("#client_table").dataTable().fnDestroy();
        $("#selected_table").dataTable().fnDestroy();
        $("#group_tbody").find("tr").removeClass("highlight_group");
        $("#group_tbody").append(result['group_tr']);
        $("#selected_grp_name").html(result['group_name']);
        $(".delete_group").attr("data-element",result['group_id']);
        $(".group_name_new").val("");
        $(".hide_group_div").show();
        $(".create_new_group_modal").modal("hide");
        $(".pms_group_messageus_overlay").modal("hide");
        $("#selected_tbody").html("");
        $(".client_tr").show();
        $('#group_table').DataTable({
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
        $('#selected_table').DataTable({
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

        $(".highlight_group").find("td").trigger("click");
      }
    })
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
      $("body").addClass("loading");
      setTimeout(function() {
        $("#group_table").dataTable().fnDestroy();
        $("#client_table").dataTable().fnDestroy();
        $("#selected_table").dataTable().fnDestroy();
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
            $(".client_exclude").prop("checked",false);
            $(".client_include").prop("checked",false);
            $("#hidden_client_ids").val("");
            $("#hidden_selected_ids").val("");
            var len = $(".selected_tr").length;
            $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);
            $('#group_table').DataTable({
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
            $('#selected_table').DataTable({
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
            $("body").removeClass("loading");
          }
        })
      },1000);
    }
  }
  if($(e.target).hasClass('add_all'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      var client_ids = "";
      $(".client_active:visible").each(function() {
        var client_id = $(this).find(".client_exclude").val();
        if(client_ids == "")
        {
          client_ids = client_id;
        }
        else{
          client_ids = client_ids+','+client_id;
        }
      });
      if(client_ids == ""){
        $("body").removeClass("loading");
        alert("Please select atleast one client to add in this Group.");
      }
      else{
        $("#group_table").dataTable().fnDestroy();
        $("#client_table").dataTable().fnDestroy();
        $("#selected_table").dataTable().fnDestroy();
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
            $(".client_exclude").prop("checked",false);
            $(".client_include").prop("checked",false);
            $("#hidden_client_ids").val("");
            $("#hidden_selected_ids").val("");
            var len = $(".selected_tr").length;
            $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);
            $('#group_table').DataTable({
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
            $('#selected_table').DataTable({
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
            $("body").removeClass("loading");
          }
        })
      }
    },1000);
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
          $("#group_table").dataTable().fnDestroy();
			$("#client_table").dataTable().fnDestroy();
			$("#selected_table").dataTable().fnDestroy();
          var clientids = client_ids.split(",");
          $.each(clientids, function(index,value) {
            $("#selected_tr_"+value).detach();
            $("#client_tr_"+value).show();
          });

          $(".client_exclude").prop("checked",false);
          $(".client_include").prop("checked",false);

          $("#hidden_client_ids").val("");
          $("#hidden_selected_ids").val("");

          var len = $(".selected_tr").length;
          $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);

          $('#group_table').DataTable({
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
		  $('#selected_table').DataTable({
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
      $("#group_table").dataTable().fnDestroy();
		$("#client_table").dataTable().fnDestroy();
		$("#selected_table").dataTable().fnDestroy();
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

          $(".client_exclude").prop("checked",false);
          $(".client_include").prop("checked",false);

          var len = $(".selected_tr").length;
            $("#group_tr_"+grp_id).find("td:nth-child(2)").html(len);

            $('#group_table').DataTable({
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
		  $('#selected_table').DataTable({
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
</script>
@stop
