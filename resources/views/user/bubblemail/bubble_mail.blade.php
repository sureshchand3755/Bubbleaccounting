@extends('userheader')
@section('content')
<style>
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

.modal_load_apply {
    display:    block;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('public/assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}

.highlight_tr td{
  background: green !important;
  color:#fff !important;
}
.own_table_white2 .highlight_tr:hover td {
  background: green !important;
}
.own_table_white2 .highlight_tr:hover td:first-child {
  background: green !important;
}

.highlight_message_tr td{
  background: green !important;
  color:#fff !important;
}
.own_table_white2 .highlight_message_tr:hover td {
  background: green !important;
}
.own_table_white2 .highlight_message_tr:hover td:first-child {
  background: green !important;
}

.dataTables_scrollBody {
    width: 98% !important;
}
</style>

<div class="content_section">
	<div id="fixed-header" style="width:100%;">
	  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">Bubble Mail</h4>
	  </div>
  </div>
  <div class="row">
    <div class="col-md-5" style="overflow-y: scroll;max-height: 800px;">
      <input type="checkbox" name="hide_inactive_clients" class="hide_inactive_clients" id="hide_inactive_clients"><label for="hide_inactive_clients">Hide Inactive Clients</label> 

      <table class="table own_table_white2" id="client_expand">
        <thead>
          <th>Client Code</th>
          <th>Active Client</th>
          <th>Client Name</th>
          <th>Message Count</th>
        </thead>
        <tbody id="client_code_body">
            
        </tbody>
      </table>
    </div>
    <div class="col-md-7">
          <div class="col-md-12 message_div" style="display:none;margin-top: 30px;max-height: 400px;overflow-y: scroll;">
          
            <table class="table own_table_white2" id="message_expand">
                <thead>
                  <th>Date Sent</th>
                  <th>From</th>
                  <th>Subject</th>
                </thead>
                <tbody id="maillist_body">
                  <tr>
                    <td>No Data Found</td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
            </table>
          </div>
          <hr>
          <div class="message_div" id="show_messageus_body">
          </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients are loaded.</p>
</div>
<script>

$('#message_expand').DataTable({
  autoWidth: false,
  scrollX: false,
  fixedColumns: false,
  searching: false,
  paging: false,
  info: false,
  order: [],
});

$(document).ready(function() {
  $.ajax({
    url:"<?php echo URL::to('user/bubble_load_all_clients'); ?>",
    type:"post",
    success:function(result){
      $("#client_code_body").html(result);
        $('#client_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: [],
        });
        
        $(".modal_load_apply").hide();
    }
  })
})
$(window).click(function(e) {
  if($(e.target).hasClass('hide_inactive_clients')){
    if($(e.target).is(":checked")){
      $(".inactive_clients").hide();
    }
    else{
      $(".clients_tr").show();
    }
  }
  if($(e.target).hasClass('client_td')){
    $(".clients_tr").removeClass('highlight_tr');
    $(e.target).parents(".clients_tr").addClass('highlight_tr');
    $(".message_div").show();
    var client_id = $(e.target).attr("data-element");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/get_client_message_list'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result){
        $("#message_expand").dataTable().fnDestroy();
        $("#maillist_body").html(result);
        $("#show_messageus_body").html("");
        $("#show_messageus_body").hide();

        $('#message_expand').DataTable({
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [],
          });
          $("body").removeClass("loading");
      }
    })
  }

  if($(e.target).hasClass('message_td')){
    $(".message_tr").removeClass('highlight_message_tr');
    $(e.target).parents(".message_tr").addClass('highlight_message_tr');
    var message_id = $(e.target).attr("data-element");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/view_bubble_mail_message'); ?>",
      type:"post",
      data:{message_id:message_id},
      success:function(result)
      {
        $("#show_messageus_body").show();
        $("#show_messageus_body").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('message_to')){
    $("#to_clients_list_modal").modal("show");
  }
});
</script>
@stop
