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
.fa-sort {
  cursor:pointer;
}
</style>
<div class="modal fade add_nominal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Add a Nominal</h4>
      </div>
      <div class="modal-body">
        <form name="add_nominal_form" id="add_nominal_form" method="post">
          <h4>Enter Nominal Code:</h4>
          <input type="text" name="nominal_code_add" class="form-control nominal_code_add" id="nominal_code_add" value="">
          <h4>Enter Description:</h4>
          <input type="text" name="description_add" class="form-control description_add" id="description_add" value="">
          <h4>Select Primary Group:</h4>
          <select name="primary_grp_add" class="form-control primary_grp_add" id="primary_grp_add">
              <option value="">Select Value</option>
              <option value="Profit & Loss">Profit & Loss</option>
              <option value="Balance Sheet">Balance Sheet</option>
          </select>
          <div class="debit_group_div" style="display:none">
            <h4>Select Debit Group:</h4>
            <select name="debit_grp_add" class="form-control debit_grp_add" id="debit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
          <div class="credit_group_div" style="display:none">
            <h4>Select Credit Group:</h4>
            <select name="credit_grp_add" class="form-control credit_grp_add" id="credit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
        @csrf
</form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary add_nominal_btn" value="Add Nominal">
      </div>
    </div>
  </div>
</div>
<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div class="table-responsive">
    <div class="col-md-12">
      <h2>Default Nominal Codes

        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Default Nominal Codes</li>
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
    
      <div class="col-lg-12 padding_00">
        <div class="col-lg-6 text-left padding_00">
          <div class="sub_title"></div>
        </div>      
        <div class="col-lg-6 text-right" style="padding:0px">
          <a href="javascript:" class="add_nominal btn btn-primary float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add Default Nominal Code</a>
        </div>
      </div>
      <div class="col-md-12 nominal_codes_div" style="margin-top:20px;padding:0px;max-height: 600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
        <table class="table own_table_white tablefixedheader">
          <thead>
            <th style="text-align: left">Code <i class="fa fa-sort code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            <th style="text-align: left">Description <i class="fa fa-sort des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            <th style="text-align: left">Primary Group <i class="fa fa-sort primary_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            <th style="text-align: left">Debit Group <i class="fa fa-sort debit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            <th style="text-align: left">Credit Group <i class="fa fa-sort credit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Action </th>
          </thead>
          <tbody id="nominal_tbody">
          <?php 
            if(($nominal_codes))
            {
              foreach($nominal_codes as $codes)
              {
                $des_code = $codes->description;
                echo '<tr class="des_tr_'.$codes->code.'">
                  <td class="code_sort_val">'.$codes->code.' <i class="fa fa-lock" title="Core Nominal"></i></td>
                  <td class="des_sort_val">'.$des_code.'</td>
                  <td class="primary_sort_val">'.$codes->primary_group.'</td>
                  <td class="debit_sort_val">'.$codes->debit_group.'</td>
                  <td class="credit_sort_val">'.$codes->credit_group.'</td>
                  <td><a href="javascript:" class="edit_nominal_code fa fa-edit" data-element="'.$codes->code.'" title="Edit Nominal Code"></a></td>
                </tr>';
              }
            }
          ?>
          </tbody>
        </table>
      </div>
      <div class="col-lg-12 text-right" style="margin-top:20px">
          <a href="javascript:" class="populate_nominal_code btn btn-primary float_right">Populate</a>
      </div>
    </div>
  </div>
</div>


<input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
<input type="hidden" name="des_sortoptions" id="des_sortoptions" value="asc">
<input type="hidden" name="primary_sortoptions" id="primary_sortoptions" value="asc">
<input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
<input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">
<script>
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseFloat(value);
}
$(window).change(function(e){
  if($(e.target).hasClass('general_nominal')){
    var code = $(e.target).val();
    if(code == '712') {
      $(e.target).parents("tr").find(".error-general-nominal").show();
      $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Debtors Control Account.');
      $(".save_general_journal_button").attr("disabled", true);
      $(".general_nominal").attr("disabled", true);
      $(e.target).not().attr("disabled", false);
    }
    else if(code == '813') {
      $(e.target).parents("tr").find(".error-general-nominal").show();
      $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Creditors Control Account.');
      $(".save_general_journal_button").attr("disabled", true);
      $(".general_nominal").attr("disabled", true);
      $(e.target).not().attr("disabled", false);
    }
    else if(code == '813A') {
      $(e.target).parents("tr").find(".error-general-nominal").show();
      $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Client holding account Account.');
      $(".save_general_journal_button").attr("disabled", true);
      $(".general_nominal").attr("disabled", true);
      $(e.target).not().attr("disabled", false);
    }
    else if((code >= '771') && (code < '772')) {
      $(e.target).parents("tr").find(".error-general-nominal").show();
      $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Bank Nominal accounts Account.');
      $(".save_general_journal_button").attr("disabled", true);
      $(".general_nominal").attr("disabled", true);
      $(e.target).not().attr("disabled", false);
    }
    else {
      $(e.target).parents("tr").find(".error-general-nominal").hide();
      $(".general_nominal").attr("disabled", false);
      $(".save_general_journal_button").attr("disabled", false);
    }
}
if($(e.target).hasClass('primary_grp_add'))
{
  var value = $(e.target).val();
  if(value == "Profit & Loss")
  {
    $(".debit_group_div").show();
    $(".credit_group_div").hide();
    $(".debit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
    $(".credit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
  }
  else{
    $(".debit_group_div").show();
    $(".credit_group_div").show();
    $(".debit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
    $(".credit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
  }
}
if($(e.target).hasClass('debit_grp_add'))
{
  var primary = $(".primary_grp_add").val();
  var debit = $(".debit_grp_add").val();
  var credit = $(".credit_grp_add").val();
  if(primary == "Balance Sheet")
  {
    if(debit == credit)
    {
      alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
      $(".debit_grp_add").val("");
      return false;
    }
  }
}
if($(e.target).hasClass('credit_grp_add'))
{
  var primary = $(".primary_grp_add").val();
  var debit = $(".debit_grp_add").val();
  var credit = $(".credit_grp_add").val();
  if(primary == "Balance Sheet")
  {
    if(debit == credit)
    {
      alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
      $(".credit_grp_add").val("");
      return false;
    }
  }
}
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Deleting this User will leads to remove from task alloted to this user. Are You Sure want to delete this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  if($(e.target).hasClass('add_nominal'))
  {
    $(".add_nominal_modal").modal("show");
    $(".add_nominal_modal").find(".modal-title").html("Add Default Nominal Code");
    $(".add_nominal_btn").val("Add Default Nominal Code");
    $(".nominal_code_add").prop("disabled",false);
    $(".nominal_code_add").val('');
    $(".description_add").val('');
    $(".primary_grp_add").val('');
    $(".debit_grp_add").val('');
    $(".credit_grp_add").val('');
    $(".debit_group_div").hide();
    $(".credit_group_div").hide();
  }

  if($(e.target).hasClass('add_nominal_btn'))
  {
    if($("#add_nominal_form").valid())
    {
      var code = $(".nominal_code_add").val();
      var description = $(".description_add").val();
      var primary = $(".primary_grp_add").val();
      var debit = $(".debit_grp_add").val();
      var credit = $(".credit_grp_add").val();
      if(primary == "Profit & Loss")
      {
        if(debit == "")
        {
          alert("Please select the Debit Group");
          return false;
        }
      }
      else{
        if(debit == "")
        {
          alert("Please select the Debit Group");
          return false;
        }
        else if(credit == ""){
          alert("Please select the Credit Group");
          return false;
        }
      }
      $.ajax({
        url:"<?php echo URL::to('facility/add_nominal_code_financial'); ?>",
        type:"post",
        dataType:"json",
        data:{code:code,description:description,primary:primary,debit:debit,credit:credit},
        success:function(result)
        {
          $(".general_nominal").html(result['dropdown_output']);
          $(".general_nominal_hidden_for_add_ajax").html(result['dropdown_output']);
          if(result['table_type'] == 0)
          {
            if(primary == "Profit & Loss")
            {
              $("#nominal_tbody").append('<tr class="code_'+code+'"><td>'+code+'<i class="fa fa-lock" title="Core Nominal"></i></td><td>'+description+'</td><td>'+primary+'</td><td>'+debit+'</td> <td>'+debit+'</td><td><a href="javascript:" class="edit_nominal_code fa fa-edit" data-element="'+code+'"></a></td></tr>');  
              $(".add_nominal_modal").modal("hide");
            }
            else{
              $("#nominal_tbody").append('<tr class="code_'+code+'"><td>'+code+'<i class="fa fa-lock" title="Core Nominal"></i></td><td>'+description+'</td><td>'+primary+'</td><td>'+debit+'</td> <td>'+credit+'</td><td><a href="javascript:" class="edit_nominal_code fa fa-edit" data-element="'+code+'"></a></td></tr>');  
              $(".add_nominal_modal").modal("hide");
            }
          }
          else{
            if(primary == "Profit & Loss")
            {
              $(".code_"+code).html('<td>'+code+'<i class="fa fa-lock" title="Core Nominal"></i></td><td>'+description+'</td><td>'+primary+'</td><td>'+debit+'</td> <td>'+credit+'</td><td><a href="javascript:" class="edit_nominal_code fa fa-edit" data-element="'+code+'"></a></td>');
              $(".add_nominal_modal").modal("hide");
            }
            else{
              $(".code_"+code).html('<td>'+code+' <i class="fa fa-lock" title="Core Nominal"></i></td><td>'+description+'</td><td>'+primary+'</td><td>'+debit+'</td> <td>'+credit+'</td><td><a href="javascript:" class="edit_nominal_code fa fa-edit" data-element="'+code+'"></a></td>');
              $(".add_nominal_modal").modal("hide");
            }
          }

          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Default Nominal Code Added Successfully.</p>',fixed:true,width:"800px"});
        }
      })
    }
  }
  if($(e.target).hasClass('populate_nominal_code')) {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('facility/populate_nominal_code'); ?>",
      type:"post",
      success:function(result) {
        $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Default Nominal Code Populated Successfully.</p>',fixed:true,width:"800px"});
        $("body").removeClass("loading");
      }
    })
  }
    if($(e.target).hasClass('code_sort'))
  {
    var sort = $("#code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#code_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#code_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('des_sort'))
  {
    var sort = $("#des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#des_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#des_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('primary_sort'))
  {
    var sort = $("#primary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#primary_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_sort_val').html()) <
        convertToNumber($(b).find('.primary_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#primary_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_sort_val').html()) <
        convertToNumber($(b).find('.primary_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('debit_sort'))
  {
    var sort = $("#debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('credit_sort'))
  {
    var sort = $("#credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('edit_nominal_code'))
  {
    
    var code = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('facility/edit_nominal_code_finance'); ?>",
        type:"post",
        dataType:"json",
        data:{code:code},
        success:function(result)
        {

          if(result['primary'] == "Profit & Loss")
          {
            $(".debit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
            $(".credit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');

            $(".debit_group_div").show();
            $(".credit_group_div").hide();
          }
          else{
            $(".debit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
            $(".credit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');

            $(".debit_group_div").show();
            $(".credit_group_div").show();
          }
          $(".add_nominal_modal").find(".modal-title").html("Update Nominal Code");
          $(".add_nominal_btn").val("Update Nominal");
          $(".nominal_code_add").prop("disabled",true);
          $(".add_nominal_modal").modal("show");
          $(".nominal_code_add").val(result['code']);
          $(".description_add").val(result['description']);
          $(".primary_grp_add").val(result['primary']);
          $(".debit_grp_add").val(result['debit']);
          $(".credit_grp_add").val(result['credit']);
        }
    });
  }
});

$('#add_nominal_form').validate({
  rules: {
      nominal_code_add : {required: true,remote:"<?php echo URL::to('user/check_nominal_code'); ?>"},
      description_add : {required: true,},
      primary_grp_add : {required:true,},
  },
  messages: {
      nominal_code_add : {
        required : "Nominal Code is Required",
        remote : "Nominal Code is Already created",
      },
      description_add : { 
        required : "Description is Required",
      },
      primary_grp_add : { 
        required : "Primary Group is Required",
      },
  },
});
</script>
@stop