@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}
.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}
.modal { overflow: auto !important;z-index: 999999;}
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
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}
.table thead th:focus{background: #ddd !important;}
.form-control{border-radius: 0px;}
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}

.modal_max_height{max-height: 700px; overflow: hidden; overflow-y: scroll;}
</style>

<div class="modal fade alert_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          You are about to rename all the Attachment Files, Do you wish to continue?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_hit">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_hit">No</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade alert_post_all" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          Journals are posted successfully for all the clients with Opening Balance less or greater than zero.
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button ok_hit">Ok</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade single_client_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-lg" style="width: 60%;">
    <div class="modal-content">
      <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Opening Balance Review</h4>
        </div>
        <div class="modal-body modal_max_height">
          <div class="row">
            <div class="col-lg-8">
              <div class="single_client_information" style="font-size: 17px;">
                
              </div>
            </div>
            <div class="col-lg-4 text-right">
              <div class="row">
                <div class="col-lg-6 padding_00">
                  <h5 style="font-size: 15px; font-weight: 600; text-align: left;">Total Gross Value: </h5>
                </div>
                <div class="col-lg-6 padding_00" style="padding-right: 20px;">
                  <h5 style="font-size: 15px; font-weight: 600;"><span class="span_gross_value"></span></h5>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 padding_00">
                  <h5 style="font-size: 15px; font-weight: 600; text-align: left;">Total Outstanding Balance: </h5>
                </div>
                <div class="col-lg-6 padding_00" style="padding-right: 20px;"><h5 style="font-size: 15px; font-weight: 600;"><span class="span_outstanding_balance"></span></h5></div>
              </div>
             
             
            </div>
            <div class="col-lg-12">              
              <table class="display nowrap fullviewtablelist own_table_white" id="invoice_opeining_table"  width="100%">
                <thead>
                  <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Gross</th>
                    <th>Balance Outstading</th>
                  </tr>
                </thead>
                <tbody id="tbody_opening_review">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            
        </div>
      </div>
    </div>
</div>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">
        <?php
        $user = DB::table('user_login')->first();
        $financial_date = $user->opening_balance_date;
        ?>
        Client Opening Balance Manager (Balances at <?php echo date('d F Y', strtotime($financial_date)); ?>)
      </h4>
      <div class="col-md-2 padding_00">
        
        <!-- <input type="checkbox" name="hide_active_clients" class="hide_active_clients" id="hide_active_clients" value="1"><label for="hide_active_clients">Hide Inactive Clients</label> -->
      </div>
      <!-- <div class="col-lg-10 padding_00">
        <div class="select_button">
          <ul>
            <li><a href="<?php echo URL::to('user/import_opening_balance_manager'); ?>" class="common_black_button" style="float:right">Import Opening Balance Manager</a></li>
          </ul>
        </div>
      </div> -->
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
         <?php }   
        ?>
      </div> 
      <div class="col-lg-12 padding_00" style="margin-top: 19px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
              <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
                <spam id="open_task_count">Client Opening Balances</spam>
              </a>
            </li>
            <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
              <a href="<?php echo URL::to('user/opening_balance_invoices_issued'); ?>" class="nav-link" id="profile-tab">Invoices Issued</a>
            </li>
            <a href="javascript:" class="common_black_button export_all" style="float: right;">Export as CSV</a>
          </ul>
      </div>
      <div class="col-lg-12 padding_00">
        <table class="table own_table_white" id="client_expand" style="width:70%;margin-left: 0px;left: 0px">
          <thead>
            <tr style="background: #fff;">
                <th style="text-align: left;width:5%">S.No</th>
                <th style="text-align: left; width: 10%;">Client&nbsp;ID</th>
                <th style="text-align: left;width:30%">Client</th>
                <th style="width:10%; text-align: right; width: 15%;">Invoices&nbsp;Issued&nbsp;at&nbsp;O/B&nbsp;Date</th>
                <th style="width:10%; text-align: right; width: 15%;">OS&nbsp;invoices&nbsp;at&nbsp;OB&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" class="fa fa-refresh refresh_os_invoice" style="font-size: 20px;font-weight: 800;" title="Refresh OS invoices at OB Date"></a></th>
                <th style="text-align: right; width: 10%;">Opening&nbsp;Balance</th>
                <th width="10%" style="text-align: left; width: 15%; text-align: center;">Journal&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" class="common_black_button post_all_journals" style="font-size: 20px;font-weight: 800;" title="Post All Journals">Post&nbsp;All</a></th>
            </tr>
          </thead>                            
          <tbody id="clients_tbody">
              <?php
                $i=1;
                $total_invoice_issued=0.00;
                $total_balance=0.00;
                if(($clientlist)){              
                  foreach($clientlist as $key => $client){
                      $disabled='';
                      $journal = '';
                      if($client->active == "2")
                      {
                        $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                        $style="color:#f00";
                        $active_cli = 'inactive_clients';
                      }
                      else{
                        $style="color:#000";
                        $active_cli = 'active_clients';
                      }
                      $balance_check = DB::table('opening_balance')->where('client_id',$client->client_id)->first();
                      
                      if(($balance_check))
                      {
                        if($balance_check->opening_balance == "")
                        {
                          $balance = '<spam style="color:#000">-</spam>';
                        }
                        elseif($balance_check->opening_balance == 0)
                        {
                          $balance = '<a href="javascript:" data-element="'.$client->client_id.'" class="bal_td_spam" style="color:#000">0.00</a>';
                        }
                        elseif($balance_check->opening_balance < 0)
                        {
                          $balance = '<a href="javascript:" title="Opening Balance Review" style="color:#f00" data-element="'.$client->client_id.'" class="bal_td_spam">'.number_format_invoice($balance_check->opening_balance).'</a>';
                        }
                        else{
                          $balance = '<a href="javascript:" title="Opening Balance Review" style="color:blue" data-element="'.$client->client_id.'" class="bal_td_spam">'.number_format_invoice($balance_check->opening_balance).'</a>';
                        }

                        
                        if($balance_check->journal_id != 0){
                          $journal = '<a href="javascript:" class="journal_id_viewer journal_id_sortval" data-element="'.$balance_check->journal_id.'">'.$balance_check->journal_id.'</a>';
                        }else{
                          if($balance_check->opening_balance < 0)
                          {
                            $journal = '<a href="javascript:" class="common_black_button post_journal" data-element="'.$client->client_id.'" style="width: 88%;float: left;">Post Journal</a>';
                          }
                          elseif($balance_check->opening_balance > 0){
                            $journal = '<a href="javascript:" class="common_black_button post_journal" data-element="'.$client->client_id.'" style="width: 88%;float: left;">Post Journal</a>';
                          }
                        }

                        if($total_balance == '' || $total_balance == 0.00 || $total_balance == 0){
                          $total_balance = $balance_check->opening_balance;
                        }
                        else{
                          $total_balance = $total_balance+(float)$balance_check->opening_balance;
                        }



                      }
                      else{
                        $balance = '<spam style="color:#000">-</spam>';
                        $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=1').'" class="fa fa-unlock" style="color:green;font-size:18px !important"></a>';
                      }

                      ?>
                      <tr class="edit_task <?php echo $active_cli; ?> <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->id; ?>">
                        <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                        <td align="left" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></td>
                        <td align="left" style="<?php echo $style; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></td>

                        <td align="left" style="text-align: right;">
                          <?php
                          
                          $invoice_issued = DB::table('invoice_system')->where('client_id',$client->client_id)->where('invoice_date','<=',$financial_date)->sum('gross');                         

                          if($total_invoice_issued == '' || $total_invoice_issued == 0){
                            $total_invoice_issued = $invoice_issued;
                          }
                          else{
                            $total_invoice_issued = $total_invoice_issued+$invoice_issued;
                          }
                          ?>
                          <a href="javascript:" title="Opening Balance Review" data-element="<?php echo $client->client_id?>" class="single_client_invoice" style="<?php echo $style; ?>"><?php echo number_format_invoice($invoice_issued); ?></a>
                        </td>
                        <td align="left" class="os_td" style="text-align: right;"></td>
                        <td align="left" class="bal_td" style="text-align: right;">
                          <?php echo $balance; ?>
                        </td>
                        <td align="left" style="<?php echo $style; ?>;">
                          <?php echo $journal; ?>
                        </td>
                      </tr>
                      <?php
                        $i++;                      
                  }          
                  ?>
                  <tr>
                    <td><span style="display: none;"><?php echo $i; ?></span></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">Invoices Issued <br/>at O./B Date<br/>(<?php echo number_format_invoice($total_invoice_issued) ?>)</td>
                    <td style="text-align: right" class="os_total_val"></td>
                    <td style="text-align: right">Balance<br/> (<?php echo number_format_invoice($total_balance) ?>)</td>
                    <td></td>
                  </tr>

                  <?php    
                }
                else
                {
                  echo'<tr><td colspan="11" align="center">Empty</td></tr>';
                }
              ?> 
          </tbody>
        </table>
      </div>
  </div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
  <p class="first_cls_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Posting Journals, Please do not close your browser window, or restart your Computer until this process is finished.</p>
  <p class="first_cls_div" style="font-size:18px;font-weight: 600;">Journals will be processed in batches of 100 clients per batch. Processing <span id="apply_first"></span> of <span id="apply_last"></span> Clients.</p>
  </div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
</div>
<script type="text/javascript">
$(function(){
    $('#client_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });

$('#invoice_opeining_table').DataTable({
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


});
function post_all_journals(round,count)
{
  //$("#client_expand").dataTable().fnDestroy();
  $.ajax({
    url:"<?php echo URL::to('user/create_journals_for_opening_balance'); ?>",
    type:"post",
    success:function(result){
        if(parseInt(round) == parseInt(count))
        {
          $("body").removeClass("loading_apply");
          $(".alert_post_all").modal("show");
        }
        else{
          var next_round = parseInt(round) + 1;
          var processing = parseInt(round) * 100;
          $("#apply_first").html(processing);
          post_all_journals(next_round,count);
        }
    }
  })
}
$(window).click(function(e) {
if($(e.target).hasClass('post_journal'))
{
  var client_id = $(e.target).attr("data-element");
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/create_opening_balance_journal'); ?>",
    type:"post",
    data:{client_id:client_id},
    success:function(result){
      $(e.target).parents("td").html(result);
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass("post_all_journals"))
{
  var postcount = $(".post_journal").length;
  var round = Math.ceil(postcount / 100);
  if(postcount > 0)
  {
    var r = confirm("You are about to Post the Journals for All the Clients with Opening balance less or greater than zero, do you wish to continue?");
    if(r)
    {
      $("body").addClass('loading_apply')
      $("#apply_first").html(0);
      $("#apply_last").html(postcount);
      post_all_journals(1,round);
    }
  }
  else{
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">No clients to be processed with Opening Balance less or greater than Zero.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button ok_proceed" value="OK"></p>',fixed:true,width:"800px"});
  }
}
if($(e.target).hasClass("ok_proceed"))
{
  $.colorbox.close();
}
if($(e.target).hasClass("ok_hit"))
{
  window.location.reload();
}
if($(e.target).hasClass('export_all')){
  $("body").addClass("loading");
  setTimeout( function() {
    $.ajax({
      url:"<?php echo URL::to('user/opening_balance_export_all'); ?>",
      type:"post",
      success:function(result){
        SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  },200);


}



if($(e.target).hasClass('single_client_invoice'))
{
  setTimeout(function() {
    $("body").addClass("loading");
    var client_id = $(e.target).attr("data-element");
    $("#invoice_opeining_table").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/opening_balance_review'); ?>",
      type:"post",
      dataType:"json",
      data:{client_id:client_id},
      success:function(result){
        $(".single_client_information").html(result['client_details']);
        $("#tbody_opening_review").html(result['output_invoice_issued']);
        $(".span_gross_value").html(result['total_gross']);
        $(".span_outstanding_balance").html(result['total_outstanding_balance']);

        $(".single_client_invoice_modal").modal('show');

        $('#invoice_opeining_table').DataTable({
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
  },500);
}


  if($(e.target).hasClass('refresh_os_invoice'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/refresh_os_invoice'); ?>",
      type:"post",
      dataType:"json",
      success:function(result){
        $(".os_td").each(function(index,value){
          $(this).html(result['os_data'][index]);
        });
        $('.os_total_val').html('OS Invoices <br/>at OB Date<br/>('+result['os_data_total']+')');

        $("body").removeClass("loading");
      }
    })
  }
	if($(e.target).hasClass('hide_active_clients'))
	{
		if($(e.target).is(":checked"))
		{
			$(".inactive_clients").hide();
		}
		else{
			$(".inactive_clients").show();
		}
	}

});
$(window).dblclick(function(e){
  if($(e.target).hasClass('os_td_spam')){
    var client_id = $(e.target).attr("data-element");
    var value = $(e.target).html();
    $.ajax({
      url:"<?php echo URL::to('user/set_balance_for_opening_balance'); ?>",
      type:"post",
      data:{client_id:client_id,value:value},
      success:function(result){
        if(result == 1){
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000;">Client is locked, so you cannot set the Balance.</p>',fixed:true,width:"800px"});
        }
        else{
          if(parseFloat(value) > 0){
            $(e.target).parents("tr").find(".bal_td").html(value);
            $(e.target).parents("tr").find(".bal_td").css("color","blue");
          }
          else if(parseFloat(value) < 0){
            $(e.target).parents("tr").find(".bal_td").html(value);
            $(e.target).parents("tr").find(".bal_td").css("color","#f00");
          }
          else{
            $(e.target).parents("tr").find(".bal_td").html('<a href="javascript:" class="os_td_spam" data-element="'+client_id+'">0.00</a>');
            $(e.target).parents("tr").find(".bal_td").css("color","#000");
          }
          $(e.target).parents("tr").find(".bal_td").find("a").removeClass("os_td_spam").addClass("bal_td_spam")
        }
      }
    })
  }
  if($(e.target).hasClass('bal_td_spam')){
    var r = confirm("You Are About to Remove this Opening Balance for this client, do you want to continue?");
    if(r){
      var client_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/remove_balance_for_opening_balance'); ?>",
        type:"post",
        data:{client_id:client_id,value:value},
        success:function(result){
          if(result == 1){
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000;">You can not remove this opening balance as the balance is locked.</p>',fixed:true,width:"800px"});
          }
          else{
            $(e.target).parents("tr").find(".bal_td").html('<spam style="color:#000">-</spam>');
          }
        }
      })
    }
  }
})
</script>
@stop