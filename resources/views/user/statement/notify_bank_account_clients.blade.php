@extends('adminuserheader')
@section('content')
<style>
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
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
.modal_load_notify {
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
body.loading_notify {
    overflow: hidden;   
}
body.loading_notify .modal_load_notify {
    display: block;
}
body {
  background: #fff !important;
  margin-bottom: 0px !important;
}
.tablefixedheader {
      text-align: left;
      position: relative;
      border-collapse: collapse; 
    }
    .tablefixedheader thead tr th {
      position: sticky;
        top: 0;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        background-color: #fff !important;
    }
</style>
<div class="modal fade notify_bank_from_user_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">User Selection</h4>
      </div>
      <div class="modal-body" style="clear:both">
        <h4>Who is Sending this bank Notification?</h4>
        <select name="notify_sender_name" class="form-control notify_sender_name" id="notify_sender_name">
            <option value="">Select User</option>        
            <?php
            $userlist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
            if(($userlist)){
              foreach ($userlist as $user) {
            ?>
              <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
            <?php
              }
            }
            ?>
        </select>
      </div>
      <div class="modal-footer" style="clear:both; border-top:0px;">        
        <input type="button" class="common_black_button select_sender_btn" value="Send Notification">  
      </div>
    </div>
  </div>
</div>
<!-- Content Header (Page header) -->
<div class="admin_content_section" style="margin-top: 0px;background: #fff">  
  <div>
  <div class="table-responsive">
    <div>
      <?php
      if(Session::has('message')) { ?>
          <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
    <div class="col-md-8">
      <input type="checkbox" name="hide_inactive_notify_bank_clients" class="hide_inactive_notify_bank_clients" id="hide_inactive_notify_bank_clients"><label for="hide_inactive_notify_bank_clients">Hide Inactive Clients</label> 
      <div style="width:100%;max-height:600px;overflow-y: scroll;overflow-x:hidden;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
        <table class="table own_table_white2 tablefixedheader" id="notify_bank_expand">
          <thead>
            <th>ClientID</th>
            <th>Client Name</th>
            <th>First Name</th>
            <th>Surname</th>
            <th><input type="checkbox" name="select_all_notify_bank_clients" class="select_all_notify_bank_clients" id="select_all_notify_bank_clients" value=""><label for="select_all_notify_bank_clients">SelectAll</label></th>
          </thead>
          <tbody id="notify_bank_clients_body">
              <?php
              $clientlist = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'email', 'active', 'id')->orderBy('id','asc')->get();
              $class = DB::table('cm_class')->where('status', 0)->get();
              $output = '';
              $i = 1;
              if(($clientlist))
              {
                foreach($clientlist as $client){
                  $disabled = '';
                  if($client->active != "")
                  {
                    if($client->active == 2)
                    {
                      $disabled='inactive_notify_clients';
                    }
                    $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                    $style="color:#".$check_color->classcolor." !important";
                  }
                  else{
                    $style="color:#000 !important";
                  }
                  $output.='<tr class="notify_bank_clients_tr '.$disabled.'" data-element="'.$client->client_id.'">
                    <td class="notify_bank_client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->client_id.'</td>
                    <td class="notify_bank_client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->company.'</td>
                    <td class="notify_bank_client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->firstname.'</td>
                    <td class="notify_bank_client_td" data-element="'.$client->client_id.'" style="'.$style.'">'.$client->surname.'</td>
                    <td>
                      <input type="checkbox" name="select_notify_bank_client" class="select_notify_bank_client" data-element="'.$client->client_id.'"><label>&nbsp;</label>
                    </td>
                  </tr>';
                  $i++;
                }
              }
              echo $output;
              ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-4">
        <h4>Bank Account Details</h4>
        <?php
        $settingsval = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
        $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
        ?>
        <table class="table" style="border:0px">
          <tr>
            <td style="border:0px;vertical-align: middle;"><label>Practice Name:</label></td>
            <td style="border:0px"><input type="text" name="notify_bank_practice_name" class="form-control notify_bank_practice_name" id="notify_bank_practice_name" value="<?php echo $practice_details->practice_name; ?>" disabled></td>
          </tr>
          <tr>
            <td style="border:0px;vertical-align: middle;"><label>IBan:</label></td>
            <td style="border:0px"><input type="text" name="notify_bank_iban" class="form-control notify_bank_iban" id="notify_bank_iban" value="<?php echo (isset($settingsval->payments_to_iban))?$settingsval->payments_to_iban:''; ?>" disabled></td>
          </tr>
          <tr>
            <td style="border:0px;vertical-align: middle;"><label>BIC:</label></td>
            <td style="border:0px"><input type="text" name="notify_bank_bic" class="form-control notify_bank_bic" id="notify_bank_bic" value="<?php echo (isset($settingsval->payments_to_bic))?$settingsval->payments_to_bic:''; ?>" disabled></td>
          </tr>
        </table>
        <p><a href="javascript:" class="common_black_button notify_bank_submit" style="width:100%;margin-top:20px;float: left;">Notify of Bank Account</a></p>
    </div>
  </div>
</div>
</div>
<div class="modal_load"></div>
<div class="modal_load_notify" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">We are currently in the process of sending emails in batches of 100 to ensure efficient and fast delivery.</p>
  <p style="font-size:18px;font-weight: 600;">Sending Emails to Clients <span id="notify_first"></span> of <span id="notify_last"></span></p>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="year_sortoptions" id="year_sortoptions" value="asc">
<input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
<script>
$( function() {
  $('#notify_bank_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: [],
        });
} );
function notify_of_bank_accounts(page) {
  var prevpage = parseInt(page) - 1;
  var offset = parseInt(prevpage) * 100;
  var limit = parseInt(page) * 100;
  var clientids = '';
  for(var i=offset; i<limit; i++) {
    var clientid = $(".select_notify_bank_client:checked").eq(i).attr("data-element");
    if(typeof clientid !== 'undefined') {
      if(clientids == '') {
        clientids = clientid;
      }
      else {
        clientids = clientids+','+clientid;
      }
    }
  }
  var sender = $(".notify_sender_name").val();
  if(clientids != '') {
    setTimeout(function() {
      $.ajax({
        url:"<?php echo URL::to('user/send_notify_bank_account_emails'); ?>",
        type:"post",
        data:{clientids:clientids,sender:sender},
        success:function(result) {
          $("#notify_first").html(limit);
          var nextpage = parseInt(page) + 1;
          notify_of_bank_accounts(nextpage);
        }
      })
    },1000);
  }
  else{
    $("body").removeClass("loading_notify");
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Emails Sent Suucessfully.</p>',fixed:true,width:"800px"});
  }
}
$(window).click(function(e) {
  if($(e.target).hasClass('select_sender_btn')) {
    var user = $(".notify_sender_name").val();
    if(user == ""){
      alert("Please select the Sender");
    }
    else{
      $(".notify_bank_from_user_modal").modal("hide");
      $("body").addClass("loading_notify");
      $("#notify_first").html("0");
      $("#notify_last").html(clientcount);
      notify_of_bank_accounts(1);
    }
  }
  if($(e.target).hasClass('notify_bank_submit')) {
    var clientcount = $(".select_notify_bank_client:checked").length;
    if(clientcount > 0) {
      $(".notify_sender_name").val("");
      $('.notify_bank_from_user_modal').modal('show');
    }
    else{
      alert("Please select atleast 1 client to send the Bank Account Email Notification.")
    }
  }
  if($(e.target).hasClass('hide_inactive_notify_bank_clients')){
    if($(e.target).is(":checked")){
      $(".inactive_notify_clients").hide();
      $(".inactive_notify_clients").find(".select_notify_bank_client").prop("checked",false);
    }
    else{
      $(".notify_bank_clients_tr").show();
    }
  }
  if($(e.target).hasClass('select_all_notify_bank_clients')){
    if($(e.target).is(":checked")){
      $(".select_notify_bank_client:visible").prop("checked",true);
    }
    else{
      $(".select_notify_bank_client").prop("checked",false);
    }
  }
});
</script>
@stop