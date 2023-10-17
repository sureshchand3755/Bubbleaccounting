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
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
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
.table thead th:focus{background: #ddd !important;}
.form-control{border-radius: 0px;}
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
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
<?php
    $check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
    $locked = $check_client->locked;
    if($locked == 1)
    {
      $disabled = 'disabled';
    }
    else{
      $disabled = '';
    }
?>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title">
                Client Opening Balance Manager
            </h4>
      
      <div class="col-md-12">
        <a href="<?php echo URL::to('user/opening_balance_manager'); ?>" class="common_black_button" style="float:right">Back To Opening Balance Manager</a>
      </div>
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
         <?php }   
        ?>
      </div>
  </div>
  <div class="row" style="background: #fff; padding-top: 50px; padding-bottom: 100px;">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-5">
      <div class="col-md-12">
        <div class="col-md-5">
          <label>Client ID:</label>          
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;"><?php echo $client_details->client_id; ?></label>           
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Company:</label>          
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;"><?php echo $client_details->company; ?></label>          
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Name:</label>          
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;"><?php echo $client_details->firstname.' & '.$client_details->surname; ?></label>          
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Primary Email:</label>          
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;"><?php echo $client_details->email; ?></label>          
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Secondary Email:</label>          
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;"><?php echo ($client_details->email2 == "")?"-":$client_details->email2; ?></label>          
        </div>
      </div>
      <?php
      $opening_balance = DB::table('opening_balance')->where('client_id',$client_id)->first();
      if(($opening_balance))
      {
        if($opening_balance->opening_balance == "")
        {
          $balance = "";
        }
        else{
          $balance = number_format_invoice_without_comma($opening_balance->opening_balance);
        }
        
        if($opening_balance->opening_date == "0000-00-00")
        {
          $date = "";
        }
        else{
          $date = date('d-M-Y', strtotime($opening_balance->opening_date));
        }
        if($balance == "0" || $balance == "0.00" || $balance == "0.0" || $balance == "00.00" || $balance == "00.0" || $balance == "")
        {
          $color = 'color:#000';
        }
        elseif($balance > 0)
        {
          $color = 'color:blue';
        }
        else{
          $color = 'color:#f00';
        }
      }
      else{
        $balance = "";
        $date = "";
        $color = 'color:#000';
      }

      ?>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Opening Balance:</label>
          
        </div>
        <div class="col-md-7">
          <input type="text" name="opening_balance" class="form-control opening_balance" style="<?php echo $color; ?>" value="<?php echo number_format_invoice($balance); ?>" pattern="[0-9]*" onkeypress="preventNonNumericalInput(event)" <?php echo $disabled; ?>>
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          &nbsp;
        </div>
        <div class="col-md-7">
          <label style="font-weight: 600;">Note: Negative Balance on the opening Balance shows clients who have an overpaid account.</label>
          
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <label>Opening Balance Date:</label>          
        </div>
        <div class="col-md-7">
          <input type="text" name="opening_balance_date" class="form-control opening_balance_date" value="<?php echo $date; ?>" <?php echo $disabled; ?> style="cursor:pointer">
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <h3 style="margin-top: 0px; margin-bottom: 20px;">Balance Break Down</h3>
      <table class="table own_table_white " style="background: #fff">
        <thead>          
          <tr>
            <th style="text-align: left">Invoice No</th>
            <th style="text-align: left">Invoice Date</th>
            <th style="text-align: right">Invoice Gross</th>
            <th style="text-align: right">Outstanding Balance from CSV</th>
            <th style="text-align: right">Opening Balance Breakdown</th>
          </tr>
        </thead>
        <tbody id="invoice_tbody_list">
          <?php
          $get_invoices = DB::select('SELECT * from `invoice_system` WHERE `client_id` = "'.$client_id.'" AND `invoice_date` <= "'.$opening_balance->opening_date.'" ORDER BY `invoice_date` DESC');
          $total_remaining = 0;
          $total_breakdown = 0;
          if(($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              if (strpos($invoice->gross, '-') !== false) { $breakdown = '-'; $balance_remaining = '-'; }
              else{
                if($invoice->balance_remaining != "") { 
                  $balance_remaining = number_format_invoice($invoice->balance_remaining); 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                } 
                else { 
                  $balance_remaining = '0.00'; 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                }
              }
              ?>
              <tr>
                <td><?php echo $invoice->invoice_number; ?></td>
                <td><?php echo date("d-M-Y", strtotime($invoice->invoice_date)); ?></td>
                <td style="text-align: right"><?php echo number_format_invoice($invoice->gross); ?></td>
                <td style="text-align: right"><?php echo number_format_invoice($invoice->import_balance); ?></td>
                <td style="text-align: right"><?php echo $balance_remaining; ?></td>
              </tr>
              <?php
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);

              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              
              $total_remaining = $total_remaining + $balance_remaining;
              $total_breakdown = $total_breakdown + $breakdown;
            }
          }
          else{
            echo '<tr><td colspan="4">No Invoice are available on or before the given opening balance date</td></tr>';
          }
          $unallocated = $balance - $total_remaining;
          ?>

          <tr>
            <td colspan="4" style="font-weight:700">Total</td>
            <td style="background: #ddd;text-align: right"><?php echo number_format_invoice($total_remaining); ?></td>
          </tr>
          <tr>
            <td colspan="4" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right"><?php echo number_format_invoice($unallocated); ?></td>
          </tr>
        </tbody>
      </table>
      <input type="button" class="common_black_button auto_allocate" id="auto_allocate" value="Auto Allocate" style="width:100%" <?php echo $disabled; ?>>
      <?php
        if($check_client->locked == 0)
        {
          echo '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$check_client->client_id.'&locked=1').'" class="common_black_button" style="width:40%; margin-top:20px;float:left;clear:both">Lock Opening Balance</a>
            <i class="fa fa-unlock" style="font-size: 35px;margin-left: 20px;color: green;margin-top: 20px;"></i>';
        }
        else{
          echo '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$check_client->client_id.'&locked=0').'" class="common_black_button" style="width:40%; margin-top:20px;float:left;clear:both">Unlock Opening Balance</a> 
            <i class="fa fa-lock" style="font-size: 35px;margin-left: 20px;color: #f00;margin-top: 20px;"></i>';
        }
      ?>
    </div>
    <div class="col-md-1">&nbsp;</div>
  </div>
  <div class="modal_load"></div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
</div>
<script type="text/javascript">
function preventNonNumericalInput(e) {
  e = e || window.event;
  var charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
  var charStr = String.fromCharCode(charCode);

  if (!charStr.match(/^[0-9-.,]+$/))
    e.preventDefault();
}
$(function(){
    $('#client_expand').DataTable({
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
    $(".opening_balance_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
       ignoreReadonly: true
    });
});

var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.opening_balance');
//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_balance, doneTypingInterval,input_val);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
  var input_val = $(this).val();
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_balance, doneTypingInterval,input_val);
});
//user is "finished typing," do something
function doneTyping_balance (input) {
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");
  input = input.replace(",", "");

  $.ajax({
        url:"<?php echo URL::to('user/change_opening_balance'); ?>",
        type:"post",
        data:{client_id:"<?php echo $client_id; ?>",balance:input},
        success: function(result) {
            if(input == "0" || input == "0.00" || input == "00.00" || input == "")
            {
              $(".opening_balance").css("color","#000");
            }
            else if(input > 0)
            {
              $(".opening_balance").css("color","blue");
            }
            else{
              $(".opening_balance").css("color","#f00");
            }
            // $(".opening_balance").val(result);
        }
      });
}

$(".opening_balance_date").on("dp.hide", function (e) {
    var input = $(".opening_balance_date").val();
    $.ajax({
        url:"<?php echo URL::to('user/change_opening_balance_date'); ?>",
        type:"post",
        data:{client_id:"<?php echo $client_id; ?>",dateval:input},
        success: function(result) {
          window.location.reload();
        }
      });
});

$(window).click(function(e){
  if($(e.target).hasClass('auto_allocate'))
  {
    var opening_balance = $(".opening_balance").val();

    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");
    opening_balance = opening_balance.replace(",", "");


    var opening_balance_date = $(".opening_balance_date").val();
    if(opening_balance == "0.00" || opening_balance == "0" || opening_balance == "" || opening_balance == "00.00" || opening_balance == " " || opening_balance == "0.0" || opening_balance == "00.0" || opening_balance == "00")
    {
      alert("Please Enter the Opening Balance and then click on to auto allocation button.");
    }
    else if(opening_balance_date == "")
    {
      alert("Please Enter the Opening Balance Date and then click on to auto allocation button.");
    }
    else{
      $("body").addClass("loading");
      var client_id = "<?php echo $client_id; ?>";
      $.ajax({
        url:"<?php echo URL::to('user/auto_allocate_opening_balance'); ?>",
        type:"post",
        data:{client_id:client_id,opening_balance:opening_balance},
        success: function(result)
        {
          $("#invoice_tbody_list").html(result);
          $("body").removeClass("loading");
        }
      });
    }
  }
});
</script>
@stop