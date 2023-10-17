@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<style>
.disabled{
  pointer-events: none;
  cursor: not-allowed;
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
</style>

<div class="content_section">

<div class="page_title" style="z-index:999;">
  <h4 class="col-lg-12 padding_00 new_main_title">Bank Account Manager</h4>
</div>
<div class="row">
<?php
  if(Session::has('message')) { ?>
         <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
<?php } ?>
<h4 style="text-align: right"><a href="javascript:" class="common_black_button add_bank" style="margin-right: 14px;">Add a Bank Account</a></h4>
<table class="table own_table_white" id="bankaccount_expand" style="margin-top: 30px !important;">
  <thead>
    <th style="text-align: left">Bank Name</th>
    <th style="text-align: left">Account Name</th>
    <th style="text-align: left">Account Number</th>
    <th style="text-align: left">Description</th>
    <th style="text-align: left">Nominal Code</th>
    <th style="text-align: left">Opening Balance</th>
    <th style="text-align: left">O/B Journal</th>
    <th style="text-align: left">Last Rec Date</th>
    <th style="text-align: left">Last Rec Stmnt Date</th>
    <th style="text-align: left">Last Rec Stmnt Bal</th>
    <th style="text-align: left">Action</th>
  </thead>
  <tbody id="bank_rec_tbody">
  <?php 
    $banks = DB::table('financial_banks')->where('practice_code',Session::get('user_practice_code'))->get();
    if(($banks))
    {
      foreach($banks as $bank)
      {
        $get_reconciliations = DB::table('reconciliations')->where('bank_id',$bank->id)->orderBy('id','desc')->first();

        if($bank->debit_balance != "")
        {
          $baln = number_format_invoice($bank->debit_balance);
        }
        elseif($bank->credit_balance != "")
        {
          $baln = '-'.number_format_invoice($bank->credit_balance);
        }
        else{
          $baln = '';
        }

        if($bank->journal_id == 0)
        {
          $journal_id = '';
        }
        else{
          $journal_id = $bank->journal_id;
        }
        $rec_date = '-';
        $stmt_date = '-';
        $stmt_bal = '-';
        if(($get_reconciliations))
        {
          $rec_date = date('d-M-Y', strtotime($get_reconciliations->rec_date));
          $stmt_date = date('d-M-Y', strtotime($get_reconciliations->stmt_date));
          $stmt_bal = number_format_invoice($get_reconciliations->stmt_bal);
        }
        echo '<tr class="bank_'.$bank->id.'">
            <td>'.$bank->bank_name.'</td>
            <td>'.$bank->account_name.'</td>
            <td>'.$bank->account_number.'</td>
            <td>'.$bank->description.'</td>
            <td>'.$bank->nominal_code.'</td>
            <td style="text-align:right">'.$baln.'</td>
            <td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal_id.'">'.$journal_id.'</a></td>
            <td>'.$rec_date.'</td>
            <td>'.$stmt_date.'</td>
            <td style="text-align:right">'.$stmt_bal.'</td>
            <td>
              <a href="javascript:" class="fa fa-edit edit_bank_account" data-element="'.$bank->id.'" title="Edit Bank Description"></a>
              <a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'.$bank->id.'"><img src="'.URL::to('public/assets/images/opening_balance.png').'" class="edit_opening_balance" data-element="'.$bank->id.'" style="width:30px"></a>&nbsp;&nbsp;
              <a href="javascript:" title="Reconcile">
                <img src="'.url('public/assets/images/r-icon.png').'" class="reconcile_icon" data-element="'.base64_encode($bank->id).'" style="width:20px" title="Bank Reconciliation"/>
              </a>
            </td>
        </tr>';
      }
    }
    else{
      echo '<tr>
        <td colspan="4">No Bank Accounts Found</td>
      </tr>';
    }
  ?>
  </tbody>
</table>
</div>
</div>
<div class="modal_load"></div>
<script>
$(document).ready(function() {
  $('#bankaccount_expand').DataTable({        
    autoWidth: false,
    scrollX: false,
    fixedColumns: false,
    searching: false,
    paging: false,
    info: false
  });
})
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
$(window).click(function(e) {  
if($(e.target).hasClass('add_bank'))
{
  $(".add_bank_modal").modal("show");
  $(".add_bank_modal").find(".modal-title").html('Add a Bank Account');
  $(".add_bank_btn").val("Add Bank");
  $(".bank_name_add").prop("disabled",false);
  $(".account_name_add").val('');
  $(".account_no_add").val('');
  $(".nominal_description_add").val('');
  $(".bank_code_add").val('');

  $.ajax({
    url:"<?php echo URL::to('user/get_nominal_codes_for_bank'); ?>",
    type:"post",
    success:function(result)
    {
      $(".bank_code_add").html(result);
    }
  })
}
if($(e.target).hasClass('add_bank_btn'))
{
  if($("#add_bank_form").valid())
  {
    var bank_name = $(".bank_name_add").val();
    var account_name = $(".account_name_add").val();
    var account_no = $(".account_no_add").val();
    var description = $(".nominal_description_add").val();
    var code = $(".bank_code_add").val();

    $.ajax({
      url:"<?php echo URL::to('user/add_bank_financial'); ?>",
      type:"post",
      dataType:"json",
      data:{bank_name:bank_name,account_name:account_name,account_no:account_no,description:description,code:code},
      success:function(result)
      {
        if(result['bank_counts'] == 0)
        {
          var image = '<?php echo URL::to('public/assets/images/opening_balance.png'); ?>';
          var ricon = '<?php echo URL::to('public/assets/images/r-icon.png'); ?>';
          var baseid = btoa(result['id']);
          $("#bank_rec_tbody").html('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td></td><td></td><td></td><td></td><td></td><td><a href="javascript:" class="fa fa-edit edit_bank_account" data-element="'+result['id']+'" title="Edit Bank Description"></a>&nbsp;<a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="'+image+'" class="edit_opening_balance" data-element="'+result['id']+'" style="width:30px"></a>&nbsp;&nbsp;<a href="javascript:" title="Reconcile"><img src="'+ricon+'" class="reconcile_icon" data-element="'+baseid+'" style="width:20px" title="Bank Reconciliation"/></a></td></tr>');  
            $(".add_bank_modal").modal("hide");
        }
        else{
          var image = '<?php echo URL::to('public/assets/images/opening_balance.png'); ?>';
          var ricon = '<?php echo URL::to('public/assets/images/r-icon.png'); ?>';
          var baseid = btoa(result['id']);
          $("#bank_rec_tbody").append('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td></td><td></td><td></td><td></td><td></td><td><a href="javascript:" class="fa fa-edit edit_bank_account" data-element="'+result['id']+'" title="Edit Bank Description"></a>&nbsp;<a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="'+image+'" class="edit_opening_balance" data-element="'+result['id']+'" style="width:30px"></a>&nbsp;&nbsp;<a href="javascript:" title="Reconcile"><img src="'+ricon+'" class="reconcile_icon" data-element="'+baseid+'" style="width:20px" title="Bank Reconciliation"/></a></td></tr>');  
            $(".add_bank_modal").modal("hide");
        }
        $(".select_reconcile_bank").html(result['output_bank']);
        $(".des_tr_"+code).find("td").eq(1).html(description);
        
        // if(result['table_type'] == 0)
        // {
        //   $("#nominal_tbody").append('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+code+'</td></tr>');  
        //     $(".add_bank_modal").modal("hide");
        // }
        // else{
        //    $(".bank_"+code).html('<td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+code+'</td>');
        //     $(".add_bank_modal").modal("hide");
        // }
      }
    })
  }
}
if($(e.target).hasClass('edit_bank_account'))
{
  var id = $(e.target).attr("data-element");
  $.ajax({
      url:"<?php echo URL::to('user/edit_bank_account_finance'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id},
      success:function(result)
      {
        $(".edit_bank_modal").modal("show");
        $(".bank_name_edit").val(result['bank_name']);
        $(".account_name_edit").val(result['account_name']);
        $(".account_no_edit").val(result['account_no']);
        $(".nominal_description_edit").val(result['description']);
        $("#hidden_bank_id_update").val(id);
      }
  });
}
if($(e.target).hasClass('edit_opening_balance'))
{
  var id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/financial_opening_balance_show'); ?>",
    type:"post",
    dataType:"json",
    data:{id:id},
    success:function(result)
    {
      $(".opening_balance_modal").modal("show");
      $("#bank_name_des").html(result['bank_name']);
      $("#bank_acc_des").html(result['description']);
      $("#acc_name").html(result['account_name']);
      $("#acc_no").html(result['account_no']);
      $(".debit_balance_add").val(result['debit_balance']);
      $(".credit_balance_add").val(result['credit_balance']);
      
      if(result['debit_balance'] == "" || result['debit_balance'] == "0.00" || result['debit_balance'] == "0") { 
        $(".debit_balance_add").prop("disabled",true); 
        $(".credit_balance_add").prop("disabled",false); 
      }

      if(result['credit_balance'] == "" || result['credit_balance'] == "0.00" || result['credit_balance'] == "0") { 
        $(".debit_balance_add").prop("disabled",false); 
        $(".credit_balance_add").prop("disabled",true); 
      }

      if(result['debit_balance'] == "" && result['credit_balance'] == ""){
        $(".debit_balance_add").prop("disabled",false); 
        $(".credit_balance_add").prop("disabled",false);
      }

      $(".opening_financial_date_val").val(result['opening_balance_date']);
      $("#hidden_bank_id").val(id);
    }
  })
}
if($(e.target).hasClass('update_bank_btn'))
{
  if($("#edit_bank_form").valid())
  {
    var bank_name = $(".bank_name_edit").val();
    var account_name = $(".account_name_edit").val();
    var account_no = $(".account_no_edit").val();
    var description = $(".nominal_description_edit").val();
    var bank_id = $("#hidden_bank_id_update").val();

    $.ajax({
      url:"<?php echo URL::to('user/update_bank_financial'); ?>",
      type:"post",
      dataType:"json",
      data:{bank_name:bank_name,account_name:account_name,account_no:account_no,description:description,bank_id:bank_id},
      success:function(result)
      {
        $(".bank_"+bank_id).find("td").eq(0).html(result['bank_name']);
        $(".bank_"+bank_id).find("td").eq(1).html(result['account_name']);
        $(".bank_"+bank_id).find("td").eq(2).html(result['account_no']);
        $(".bank_"+bank_id).find("td").eq(3).html(result['description']);
        $(".edit_bank_modal").modal("hide");
        $(".des_tr_"+result['code']).find("td").eq(1).html(result['description']);
      }
    })
  }
}
if($(e.target).hasClass('save_opening_balance'))
{
  var debit_balance = $(".debit_balance_add").val();
  var credit_balance = $(".credit_balance_add").val();
  var id = $("#hidden_bank_id").val();
  $.ajax({
    url:"<?php echo URL::to('user/save_opening_balance_values'); ?>",
    type:"post",
    data:{id:id,debit_balance:debit_balance,credit_balance:credit_balance},
    success:function(result)
    {
      $(".opening_balance_modal").modal("hide");
      if(debit_balance != "")
      {
        $(".bank_"+id).find("td").eq(5).html(debit_balance);
      }
      else{
        $(".bank_"+id).find("td").eq(5).html('-'+credit_balance);
      }

      $(".bank_"+id).find("td").eq(6).html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>');
    }
  })
}
});
$(window).keyup(function(e) {
  if($(e.target).hasClass('debit_balance_add'))
  {
    var debitvalue = $(e.target).val();
    if(debitvalue != "")
    {
      $(".credit_balance_add").prop("disabled",true);
      $(".credit_balance_add").val("");
    }
    else{
      $(".credit_balance_add").prop("disabled",false);
    }
  }
  if($(e.target).hasClass('credit_balance_add'))
  {
    var creditvalue = $(e.target).val();
    if(creditvalue != "")
    {
      $(".debit_balance_add").prop("disabled",true);
      $(".debit_balance_add").val("");
    }
    else{
      $(".debit_balance_add").prop("disabled",false);
    }
  }
});
$('#add_bank_form').validate({
    rules: {
        bank_name_add : {required: true,},
        account_name_add : {required: true,},
        account_no_add : {required:true,},
        nominal_description_add : {required:true,},
        bank_code_add : {required:true,},
    },
    messages: {
        bank_name_add : {
          required : "Bank Name is Required",
        },
        account_name_add : { 
          required : "Account Name is Required",
        },
        account_no_add : { 
          required : "Account No is Required",
        },
        nominal_description_add : {
          required : "Description is Required",
        },
        bank_code_add : {
          required : "Nominal Code is Required",
        }
    },
});
$('#edit_bank_form').validate({
    rules: {
        bank_name_edit : {required: true,},
        account_name_edit : {required: true,},
        account_no_edit : {required:true,},
        nominal_description_edit : {required:true,},
    },
    messages: {
        bank_name_edit : {
          required : "Bank Name is Required",
        },
        account_name_edit : { 
          required : "Account Name is Required",
        },
        account_no_edit : { 
          required : "Account No is Required",
        },
        nominal_description_edit : {
          required : "Description is Required",
        }
    },
});
</script>
@stop