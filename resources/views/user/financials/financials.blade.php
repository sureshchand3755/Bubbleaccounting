@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>

<style>
  .table_pl > tbody > tr > td, .table_pl > tbody > tr > th, .table_pl > tfoot > tr > td, .table_pl > tfoot > tr > th, .table_pl > thead > tr > td, .table_pl > thead > tr > th {
    padding: 10px 0px;
    border-top: 0px;
  }
  #dropdownMenuButton:hover, #dropdownMenuButton:focus, #dropdownMenuButton:active{
    text-decoration: none !important;
    background: #000;
    opacity: 1;
    color: #ff0 !important;
  }
  .dropdown-item{
    width: 100%;
    float: left;
    padding: 7;
  }
  .dropdown-item:hover{
    background: #000;
    text-decoration: none;
    color: #ff0 !important;
  }
  .modal_load_apply {
    display:    none;
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
#colorbox{
  z-index:99999999;
}
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
  margin-left: 8px;
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
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}
.ui-tooltip{
  margin-top:-50px !important;
}
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.field_check
{
  width:24%;
}
.import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
}
.selectall_div{
  position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
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
.ui-widget{z-index: 999999999}

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

.img_div_add{

        border: 1px solid #000;

    width: 300px;

    position: absolute !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}

.dropzone.dz-clickable{margin-bottom: 0px !important;}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }


body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.modal_load_trial {
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
body.loading_trial {
    overflow: hidden;   
}
body.loading_trial .modal_load_trial {
    display: block;
}
.modal_load_balance {
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
body.loading_balance {
    overflow: hidden;   
}
body.loading_balance .modal_load_balance {
    display: block;
}

.modal_load_receipt {
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
body.loading_receipts {
    overflow: hidden;   
}
body.loading_receipts .modal_load_receipt {
    display: block;
}

.modal_load_pratice {
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
body.loading_pratice {
    overflow: hidden;   
}
body.loading_pratice .modal_load_pratice {
    display: block;
}





.modal_load_payment {
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
body.loading_payments {
    overflow: hidden;   
}
body.loading_payments .modal_load_payment {
    display: block;
}

.modal_load_delay {
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
body.loading_delay {
    overflow: hidden;   
}
body.loading_delay .modal_load_delay {
    display: block;
}


.modal_load_calculation {
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
body.loading_calculations {
    overflow: hidden;   
}
body.loading_calculations .modal_load_calculation {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
    body #coupon {
      display: none;
    }
    @media print {
      body * {
        display: none;
      }
      body #coupon {
        display: block;
      }
    }
.table-fixed-header {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header thead tr th {
  background: white;
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}
.orange_value_refresh{
  color:orange;
  font-weight:600;
}
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>



<div class="modal fade trial_balance_journal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Journals</h4>
      </div>
      <div class="modal-body">
          <a href="javascript:" class="common_black_button extract_view_journal_csv" style="float:right">Extract to CSV</a>
          <div id="trial_balance_journal_tbody" style="width:100%;margin-top:20px;min-height:500px;max-height: 600px;overflow-y:scroll">

          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_view_journal_nominal_code" id="hidden_view_journal_nominal_code" value="">
        <input type="hidden" name="hidden_view_journal_debit" id="hidden_view_journal_debit" value="">
        <input type="hidden" name="hidden_view_journal_credit" id="hidden_view_journal_credit" value="">
        <input type="hidden" name="hidden_view_journal_opening" id="hidden_view_journal_opening" value="">
      </div>
    </div>
  </div>
</div>


<div class="modal fade profit_loss_journal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Journals</h4>
      </div>
      <div class="modal-body">
          <a href="javascript:" class="common_black_button extract_view_journal_csv_pl" style="float:right">Extract to CSV</a>
          <div id="profit_loss_journal_tbody" style="width:100%;margin-top:20px;min-height:500px;max-height: 600px;overflow-y:scroll">

          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_view_journal_nominal_code" id="hidden_view_journal_nominal_code_pl" value="">
        <input type="hidden" name="hidden_view_journal_month_pl" id="hidden_view_journal_month_pl" value="">
      </div>
    </div>
  </div>
</div>

<div class="content_section" style="">
    <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">Financials </h4>
        <?php
          $first_date = date('d/m/Y',strtotime('first day of this month'));
          $last_date = date('d/m/Y',strtotime('last day of this month'));
          $curr_year = date('Y');
          $prev_year = date('Y') - 1;
          ?>
         <div class="dropdown" style="position: absolute; top:98px;right:38px">
            <button class="common_black_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu">
              <i class="fa fa-bars"></i> Menu
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="margin-left: -103px;padding: 5px;">
              <a class="dropdown-item journal_source" href="javascript:">Journal Source</a>
              <a href="javascript:" class="dropdown-item question_mark_btn">Initial Journal Process</a>
              <!-- <a class="dropdown-item financial_setup" href="javascript:">Financial Setup</a>
              <a href="javascript:" class="dropdown-item bank_account_manager">Bank Account Manager</a>
              <a href="javascript:" class="dropdown-item practice_overview">Practice Overview</a>
              <a href="javascript:" class="dropdown-item client_finance_account_btn">Client Finance Account</a>  -->
              <!-- <div class="row">
                <div class="col-lg-12">
                  <input type="button" name="journal_source" class="common_black_button journal_source" value="Journal Source" style="float: left; width: 100%; margin-left: 0px; padding: 7px 0px;">
                </div>
                <div class="col-lg-12" style="margin-top: 7px;">
                  <input type="button" name="financial_setup" class="common_black_button financial_setup" value="Financial Setup" style="float: right; width: 100%; margin-left: 0px; padding: 7px 0px;">          
                </div>
                <div class="col-lg-12" style="margin-top: 7px;">
                  <a href="javascript:" class="common_black_button question_mark_btn" title="Initial Journal Process" style="width: 100%; padding: 7px 0px; float: left; margin-left: 0px; ">Initial Journal Process</a>
                </div>
                <div class="col-lg-12" style="margin-top: 7px;">
                  <a href="javascript:" class="common_black_button bank_account_manager" style="float: right; width: 100%; margin-left: 0px; padding: 7px 0px;">Bank Account Manager</a>
                </div>
                <div class="col-lg-12" style="margin-top: 7px;">
                  <a href="javascript:" class="common_black_button practice_overview" style="float: left; width: 100%; margin-left: 0px; padding: 7px 0px;">Practice Overview</a>
                </div>
                <div class="col-lg-12" style="margin-top: 7px;">
                  <input type="button" class="common_black_button client_finance_account_btn" value="Client Finance Account" style="float:right;font-size: 14px; width: 100%; margin-left: 0px; padding: 7px 0px;"> 
                </div>
              </div> -->
            </div>
          </div> 
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="journal-listing-tab" data-toggle="tab" href="#journallistingtab" role="tab" aria-controls="journallistingtab" aria-selected="false">NOMINAL JOURNAL LISTING</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="trial-balance-tab" data-toggle="tab" href="#trialbalancetab" role="tab" aria-controls="trialbalancetab" aria-selected="false">TRIAL BALANCE</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profit-loss-tab" data-toggle="tab" href="#profitlosstab" role="tab" aria-controls="profitlosstab" aria-selected="false">PROFIT & LOSS</a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active in" id="journallistingtab" role="tabpanel" aria-labelledby="journal-listing">
            <div class="col-md-12">
              <div class="col-md-12" style="margin-top:10px">
                  <input type="radio" name="date_selection" class="date_selection" id="curr_year" value="1" data-from="01/01/<?php echo $curr_year; ?>" data-to="31/12/<?php echo $curr_year; ?>"><label for="curr_year">Current Year</label>
                  <input type="radio" name="date_selection" class="date_selection" id="prev_year" value="2" data-from="01/01/<?php echo $prev_year; ?>" data-to="31/12/<?php echo $prev_year; ?>"><label for="prev_year">Previous Year</label>
                  <input type="radio" name="date_selection" class="date_selection" id="curr_month" value="3" data-from="<?php echo $first_date; ?>" data-to="<?php echo $last_date; ?>" checked><label for="curr_month">Current Month</label>
                  <input type="radio" name="date_selection" class="date_selection" id="custom" value="4"><label for="custom">Custom</label>
              </div>
              <div class="col-md-12" style="margin-top:10px">
                <div class="col-md-3" style="margin-top: 10px;" >
                  <label class="col-md-1 padding_00" style="margin-top: 6px;text-align: left;">From:</label>
                  <div class="col-md-5">
                    <input type="text" name="from_custom_date" class="form-control from_custom_date" value="<?php echo $first_date; ?>" disabled>
                  </div>

                  <label class="col-md-1" style="margin-top: 6px;text-align: right;">To:</label>
                  <div class="col-md-5">
                    <input type="text" name="to_custom_date" class="form-control to_custom_date" value="<?php echo $last_date; ?>" disabled>
                  </div>
                </div>
                <div class="col-md-4" style="margin-top: 17px;">
                    <a href="javascript:" class="common_black_button load_journals">Load Journals</a>
                    <a href="javascript:" class="class_general_journal common_black_button">General Journal</a>
                </div>
              </div>
              <div class="col-md-12 load_journal_div" style="margin-top:20px;background: #fff; height:650px;max-height: 650px;overflow-y: scroll">
                <table class="table own_table_white" id="journal_table">
                  <thead>
                    <tr>
                      <th style="width:120px;text-align:left">Journal ID <i class="fa fa-sort journal_id_sort" style="float: right"></i></th>
                      <th style="text-align:left">Journal Date <i class="fa fa-sort journal_date_sort" style="float: right"></i></th>
                      <th style="text-align:left">Journal Description <i class="fa fa-sort journal_des_sort" style="float: right"></i></th>
                      <th style="text-align:left">Nominal Code <i class="fa fa-sort nominal_code_sort" style="float: right"></i></th>
                      <th style="text-align:left">Nominal Code Description <i class="fa fa-sort nominal_des_sort" style="float: right"></i></th>
                      <th style="text-align:left">Journal Source <i class="fa fa-sort source_sort" style="float: right"></i></th>
                      <th style="width:120px;text-align: right">Debit Value <i class="fa fa-sort debit_journal_sort" style="float: right"></i></th>
                      <th style="width:120px;text-align: right">Credit Value <i class="fa fa-sort credit_journal_sort" style="float: right"></i></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="trialbalancetab" role="tabpanel" aria-labelledby="trial-balance-tab">
            <div class="col-md-12">
              <div class="col-md-12" style="margin-top:10px">
                  <input type="radio" name="date_selection_trial" class="date_selection_trial" id="curr_year_trial" value="1" data-from="01/01/<?php echo $curr_year; ?>" data-to="31/12/<?php echo $curr_year; ?>"><label for="curr_year_trial">Current Year</label>
                  <input type="radio" name="date_selection_trial" class="date_selection_trial" id="prev_year_trial" value="2" data-from="01/01/<?php echo $prev_year; ?>" data-to="31/12/<?php echo $prev_year; ?>"><label for="prev_year_trial">Previous Year</label>
                  <input type="radio" name="date_selection_trial" class="date_selection_trial" id="curr_month_trial" value="3" data-from="<?php echo $first_date; ?>" data-to="<?php echo $last_date; ?>" checked><label for="curr_month_trial">Current Month</label>
                  <input type="radio" name="date_selection_trial" class="date_selection_trial" id="custom_trial" value="4"><label for="custom_trial">Custom</label>
              </div>
              <div class="col-md-12" style="margin-top:10px">
                <div class="col-md-3" style="margin-top: 10px;" >
                    <label class="col-md-1 padding_00" style="margin-top: 6px;text-align: left;">From:</label>
                    <div class="col-md-5">
                      <input type="text" name="from_custom_date_trial" class="form-control from_custom_date_trial" value="<?php echo $first_date; ?>" disabled>
                    </div>

                    <label class="col-md-1" style="margin-top: 6px;text-align: right;">To:</label>
                    <div class="col-md-5">
                      <input type="text" name="to_custom_date_trial" class="form-control to_custom_date_trial" value="<?php echo $last_date; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-9" style="margin-top: 17px;">
                    <a href="javascript:" class="common_black_button load_balance">Load Trial Balance</a>
                    <a href="javascript:" class="common_black_button remove_nil_balances" style="display:none">Remove Nil Balance</a>
                    <a href="javascript:" class="common_black_button extract_trial_pdf" style="display:none">Extract as PDF</a>
                    <a href="javascript:" class="common_black_button extract_trial_csv" style="display:none">Extract as CSV</a>
                    <inout type="hidden" id="hidden_remove_nil_balance" value="0">
                </div>
              </div>
              <div class="col-md-9 load_balance_div" style="margin-top:20px;background: #fff; height:650px;max-height: 650px;overflow-y: scroll">
                <table class="table own_table_white">
                  <thead>
                    <th style="width:120px">Nominal Code <i class="fa fa-sort trial_code_sort" style="float: right"></i></th>
                    <th>Nominal Description <i class="fa fa-sort trial_des_sort" style="float: right"></i></th>
                    <th style="width:200px;">Primary Group <i class="fa fa-sort trial_primary_sort" style="float: right"></i></th>
                    <th style="width:300px;">Sub Group <i class="fa fa-sort trial_secondary_sort" style="float: right"></i></th>
                    <th style="width:120px;text-align: right">Debit Value <i class="fa fa-sort trial_debit_sort" style="float: right"></i></th>
                    <th style="width:120px;text-align: right">Credit Value <i class="fa fa-sort trial_credit_sort" style="float: right"></i></th>
                  </thead>
                  <tbody id="trial_balance_tbody">
                    <tr>
                      <td colspan="5" style="text-align:center">No Data Found</td>
                    </tr>
                  </tbody>
                  <tr class="total_debit_credit_tr" style="display: none">
                    <td colspan="4">Total</td>
                    <td class="total_nominal_debit" style="text-align:right"></td>
                    <td class="total_nominal_credit" style="text-align:right"></td>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="profitlosstab" role="tabpanel" aria-labelledby="profit-loss-tab">
            <div class="col-md-12">
              <div class="col-md-12" style="margin-top:10px">
                  <label style="float:left;margin-top:10px;margin-right:20px">From: </label>
                  <select name="from_month" class="form-control from_month" style="float:left;width:10%">
                    <option value="">Select Month</option>
                    <?php
                    $current_month = date('M-Y');
                    $current_monthh = date('m-Y');
                    $curr_str_month = date('Y-m-01');
                    $opening_month = DB::table('user_login')->first();
                    $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
                    $edate = strtotime($curr_str_month);
                    $bdate = strtotime($opening_bal_month);
                    $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
                    echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'" data-element="'.strtotime($opening_bal_month).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
                    for($i= 1; $i<=$age; $i++)
                    {
                      $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
                      if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
                      echo '<option value="'.$datevalll.'" '.$selected.' data-element="'.strtotime('first day of next month', strtotime($opening_bal_month)).'">'.$datevall.'</option>';
                      $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
                    }

                    ?>
                  </select>
                  <label style="float:left;margin-top:10px;margin-left:20px;margin-right:20px">To: </label>
                  <select name="to_month" class="form-control to_month" style="float:left;width:10%">
                    <option value="">Select Month</option>
                    <?php
                    $current_month = date('M-Y');
                    $current_monthh = date('m-Y');
                    $curr_str_month = date('Y-m-01');
                    $opening_month = DB::table('user_login')->first();
                    $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
                    $edate = strtotime($curr_str_month);
                    $bdate = strtotime($opening_bal_month);
                    $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
                    echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'" data-element="'.strtotime($opening_bal_month).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
                    for($i= 1; $i<=$age; $i++)
                    {
                      $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
                      $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
                      if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
                      echo '<option value="'.$datevalll.'" '.$selected.' data-element="'.strtotime('first day of next month', strtotime($opening_bal_month)).'">'.$datevall.'</option>';
                      $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
                    }
                    ?>
                  </select>
                  <input type="button" name="load_profit_loss" class="common_black_button load_profit_loss" value="Load Values">
                  <input type="button" name="extract_profit_loss" class="common_black_button extract_profit_loss" value="Extract as CSV" style="display:none">
              </div>
              
              <div class="col-md-12" style="overflow-x: scroll;margin-top:30px">
                  <div class="load_profit_loss_div">

                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div class="loading_pratice modal_load_pratice text-center">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%; padding-top: 35px;">
      <span class="modal_pratice_span1"></span> <span class="modal_pratice_span2"></span>
    </p>
  </div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
  <p class="first_cls_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Posting Reconciling Journals, Please do not close your browser window, or restart your Computer until this process has finished.</p>
  <p class="first_cls_div" style="font-size:18px;font-weight: 600;">Reconciliation will be processed in batches of 1000. Processing the batches <span id="apply_first"></span> of <span id="apply_last"></span></p>

  <p class="second_cls_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Now, generating the Reconciliation Report in CSV format to be added in the Rec file and resorting the Transaction grid. Please wait.</p>
</div>
  <div class="modal_load_balance" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Opening Balance</p> </div>
  <div class="modal_load_trial" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Nominal Details</p> </div>
  <div class="modal_load_receipt" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Client Money Received</p> </div>
  <div class="modal_load_payment" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Payments Made</p> </div>
  <div class="modal_load_delay" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait as this may take upto 5 minutes to generate a PDF Report.</p> </div>
  <div class="modal_load_calculation" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating the Sum of Balance.</p> </div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">

  <input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
  <input type="hidden" name="des_sortoptions" id="des_sortoptions" value="asc">
  <input type="hidden" name="primary_sortoptions" id="primary_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">

  <input type="hidden" name="journal_id_sortoptions" id="journal_id_sortoptions" value="asc">
  <input type="hidden" name="journal_date_sortoptions" id="journal_date_sortoptions" value="asc">
  <input type="hidden" name="journal_des_sortoptions" id="journal_des_sortoptions" value="asc">
  <input type="hidden" name="nominal_code_sortoptions" id="nominal_code_sortoptions" value="asc">
  <input type="hidden" name="nominal_des_sortoptions" id="nominal_des_sortoptions" value="asc">
  <input type="hidden" name="source_sortoptions" id="source_sortoptions" value="asc">
  <input type="hidden" name="debit_journal_sortoptions" id="debit_journal_sortoptions" value="asc">
  <input type="hidden" name="credit_journal_sortoptions" id="credit_journal_sortoptions" value="asc">

  <input type="hidden" name="trial_code_sortoptions" id="trial_code_sortoptions" value="asc">
  <input type="hidden" name="trial_des_sortoptions" id="trial_des_sortoptions" value="asc">
  <input type="hidden" name="trial_primary_sortoptions" id="trial_primary_sortoptions" value="asc">
  <input type="hidden" name="trial_secondary_sortoptions" id="trial_secondary_sortoptions" value="asc">
  <input type="hidden" name="trial_debit_sortoptions" id="trial_debit_sortoptions" value="asc">
  <input type="hidden" name="trial_credit_sortoptions" id="trial_credit_sortoptions" value="asc">


  <input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
  <input type="hidden" name="surname_sortoptions" id="surname_sortoptions" value="asc">
  <input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
  <input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
  <input type="hidden" name="debit_fin_sortoptions" id="debit_fin_sortoptions" value="asc">
  <input type="hidden" name="credit_fin_sortoptions" id="credit_fin_sortoptions" value="asc">
  <input type="hidden" name="balance_fin_sortoptions" id="balance_fin_sortoptions" value="asc">

  <input type="hidden" name="client_summary_sortoptions" id="client_summary_sortoptions" value="asc">
  <input type="hidden" name="surname_summary_sortoptions" id="surname_summary_sortoptions" value="asc">
  <input type="hidden" name="firstname_summary_sortoptions" id="firstname_summary_sortoptions" value="asc">
  <input type="hidden" name="company_summary_sortoptions" id="company_summary_sortoptions" value="asc">

  <input type="hidden" name="opening_bal_summary_sortoptions" id="opening_bal_summary_sortoptions" value="asc">
  <input type="hidden" name="receipt_summary_sortoptions" id="receipt_summary_sortoptions" value="asc">
  <input type="hidden" name="payment_summary_sortoptions" id="payment_summary_sortoptions" value="asc">
  <input type="hidden" name="balance_summary_sortoptions" id="balance_summary_sortoptions" value="asc">

</div>

<script>

$(window).change(function(e){

if($(e.target).hasClass('input_balance_bank')){
  var input_balance_bank = $(e.target).val();
  var input_total_outstanding = $(".refresh_input_outstanding").val();
  var input_bala_transaction = $(".balance_tran_class").val();

  $.ajax({
      url:"<?php echo URL::to('user/balance_per_bank'); ?>",
      type:"post",
      dataType:"json",
      data:{input_balance_bank:input_balance_bank, input_total_outstanding:input_total_outstanding, input_bala_transaction:input_bala_transaction},
      success:function(result){

        $(".input_close_balance").val(result['close_balance']);
        $(".class_close_balance").html(result['close_balance_span']);

        $(".input_difference").val(result['diffence']);
        $(".class_difference").html(result['diffence_span']);
        
        
      }
    }); 

  

}


})





$(window).dblclick(function(e){

if($(e.target).hasClass('single_accept')){
  var type = $(e.target).attr("type");
  var id = $(e.target).attr("data-element");
  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();

  $.ajax({
      url:"<?php echo URL::to('user/finance_bank_single_accept'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:type, receipt_id:receipt_id, payment_id:payment_id},
      success:function(result){
        if(type == 1){
          $("#receipt_out_"+id).html(result['outstanding']);
          $("#receipt_out_"+id).css({"color":"blue"});

          $("#receipt_clear_"+id).html(result['clearance_date']);
          $("#receipt_clear_"+id).css({"color":"orange", "font-weight":"bold"});
          $("#receipt_clear_"+id).addClass('process_journal');
        }
        else{
          $("#payment_out_"+id).html(result['outstanding']);
          $("#payment_out_"+id).css({"color":"blue"});

          $("#payment_clear_"+id).html(result['clearance_date']);
          $("#payment_clear_"+id).css({"color":"orange", "font-weight":"bold"});
          $("#payment_clear_"+id).addClass('process_journal');
        }
        $(".class_total_outstanding").css({"color":"orange", "font-weight":"bold"});
        $(".class_total_outstanding_refresh").addClass('orange_value_refresh');

        $(".class_total_outstanding").html(result['total_outstanding_html']);
        $(".input_total_outstanding").val(result['total_outstanding']);
      }
  })
}






})
function accept_reconciliation(round,count)
{
  $("#transaction_table").dataTable().fnDestroy();
  $("#reconcile_report").dataTable().fnDestroy();
  var bank_id = $(".select_reconcile_bank").val();
  $.ajax({
    url:"<?php echo URL::to('user/create_journal_reconciliation'); ?>",
    type:"post",
    data:{bank_id:bank_id},
    success:function(result){
        if(parseInt(round) == parseInt(count))
        {
          $(".payment_clear").removeClass("process_journal");
          $(".receipt_clear").removeClass("process_journal");

          var bank_id = atob($(".select_reconcile_bank").val());
          var stmt_bal = $(".input_balance_bank").val();
          var stmt_date = $(".date_balance_bank").val();
          var total_os_items = $(".refresh_input_outstanding").val();

          var cb = $(".class_close_balance").html();
          var cd = $(".class_difference").html();
          var receipt_id = $(".receipt_id").val();
          var payment_id = $(".payment_id").val();

          $(".first_cls_div").hide();
          $(".second_cls_div").show();

          $.ajax({
            url:"<?php echo URL::to('user/generate_reconcile_csv_after_reconciliation'); ?>",
            type:"post",
            dataType:"json",
            data:{bank_id:bank_id,stmt_bal:stmt_bal,stmt_date:stmt_date,total_os_items:total_os_items,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
            success:function(result){
                $(".tbody_transaction").html(result['output_payment']);
                $("#reconcile_tbody").append(result['output']);
                $('#transaction_table').DataTable({        
                    // autoWidth: true,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                    order: [[1, 'asc'], [3, 'desc']]
                });

                $('#reconcile_report').DataTable({        
                    // autoWidth: true,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                });

                $("#bank_tbody").find(".bank_"+bank_id).find("td").eq(7).html(result['rec_date']);
                $("#bank_tbody").find(".bank_"+bank_id).find("td").eq(8).html(result['stmt_date']);
                $("#bank_tbody").find(".bank_"+bank_id).find("td").eq(9).html(result['stmt_bal']);

                $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">The Bank Reconciliation process has Finished and the Journals have been posted.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button ok_proceed" value="OK"></p>',fixed:true,width:"800px"});

                $("body").removeClass("loading_apply");
                //$("#apply_first").html('0');
            }
          })
        }
        else{
          var next_round = parseInt(round) + 1;
          $("#apply_first").html(next_round);
          accept_reconciliation(next_round,count);
        }
    }
  })
}
// function accept_reconciliation(count)
// {
//   $("#transaction_table").dataTable().fnDestroy();
//   $("#reconcile_report").dataTable().fnDestroy();

//   var id = $(".process_journal").eq(0).attr("data-element");
//   var bank_id = $(".select_reconcile_bank").val();

//   if($(".process_journal").eq(0).hasClass('receipt_clear'))
//   {
//     var type = '1';
//   }
//   else{
//     var type = '2';
//   }

//   $.ajax({
//     url:"<?php echo URL::to('user/create_journal_reconciliation'); ?>",
//     type:"post",
//     data:{id:id,type:type,bank_id:bank_id},
//     success:function(result){
        
//         if(type == '1')
//         {
//           $("#receipt_clear_"+id).removeClass('process_journal');
//           $("#receipt_clear_"+id).parents("tr").find(".journal_td").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>');
//         }
//         else{
//           $("#payment_clear_"+id).removeClass('process_journal');
//           $("#payment_clear_"+id).parents("tr").find(".journal_td").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>')
//         }
//         var countval = count + 1;
//         if($(".process_journal").eq(0).length > 0)
//         {
//           accept_reconciliation(countval);
//           $("#apply_first").html(countval);
//         }
//         else{
//           var bank_id = atob($(".select_reconcile_bank").val());

//           var stmt_bal = $(".input_balance_bank").val();
//           var stmt_date = $(".date_balance_bank").val();
//           var total_os_items = $(".refresh_input_outstanding").val();

//           var cb = $(".class_close_balance").html();
//           var cd = $(".class_difference").html();
          
//           var receipt_id = $(".receipt_id").val();
//           var payment_id = $(".payment_id").val();

//           $.ajax({
//             url:"<?php echo URL::to('user/generate_reconcile_csv_after_reconciliation'); ?>",
//             type:"post",
//             data:{bank_id:bank_id,stmt_bal:stmt_bal,stmt_date:stmt_date,total_os_items:total_os_items,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
//             success:function(result){
//                 $("#reconcile_tbody").append(result);
//                 $('#transaction_table').DataTable({        
//                     // autoWidth: true,
//                     scrollX: false,
//                     fixedColumns: false,
//                     searching: false,
//                     paging: false,
//                     info: false,
//                     order: [[1, 'asc'], [3, 'desc']]
//                 });

//                 $('#reconcile_report').DataTable({        
//                     // autoWidth: true,
//                     scrollX: false,
//                     fixedColumns: false,
//                     searching: false,
//                     paging: false,
//                     info: false,
//                 });

                

//                 $("body").removeClass("loading_apply");
//                 $("#apply_first").html('0');
//             }
//           })
//         }
//     }
//   })
// }

$(window).click(function(e){
if($(e.target).hasClass('view_journals_pl'))
{
  var month = $(e.target).attr("data-element");
  var code = $(e.target).attr("data-code");
  if(month == "total")
  {
    var from_month = $(".from_month").val();
    var to_month = $(".to_month").val();
    $.ajax({
      url:"<?php echo URL::to('user/view_journal_for_profit_loss'); ?>",
      type:"post",
      data:{from_month:from_month,to_month:to_month,code:code},
      dataType:"json",
      success:function(result){
        if(result['error'] == "0"){
          alert("There is no journals created for this Nominal Code");
          $("body").removeClass('loading');
        }else{
          // $("#pl_viewer_extend").dataTable().fnDestroy();
          $(".profit_loss_journal_modal").modal("show");
          $("#profit_loss_journal_tbody").html(result['output']);

          $("#hidden_view_journal_nominal_code_pl").val(code);
          $("#hidden_view_journal_month_pl").val(month);

          $('#pl_viewer_extend').DataTable({        
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [[ 1, "asc" ]]
          });

          $("body").removeClass("loading");
        }
      }
    });
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/view_journal_for_profit_loss_single_month'); ?>",
      type:"post",
      data:{month:month,code:code},
      dataType:"json",
      success:function(result){
        if(result['error'] == "0"){
          alert("There is no journals created for this Nominal Code");
          $("body").removeClass('loading');
        }else{
          $("#pl_viewer_extend").dataTable().fnDestroy();
          $(".profit_loss_journal_modal").modal("show");
          $("#profit_loss_journal_tbody").html(result['output']);

          $("#hidden_view_journal_nominal_code").val(code);

          $('#pl_viewer_extend').DataTable({        
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false,
              order: [[ 1, "asc" ]]
          });

          $("body").removeClass("loading");
        }
      }
    });
  }
}
if($(e.target).hasClass('extract_view_journal_csv_pl'))
{
  var month = $("#hidden_view_journal_month_pl").val();
  var code = $("#hidden_view_journal_nominal_code_pl").val();

  if(month == "total")
  {
    var from_month = $(".from_month").val();
    var to_month = $(".to_month").val();
    $.ajax({
      url:"<?php echo URL::to('user/extract_journal_for_profit_loss'); ?>",
      type:"post",
      data:{from_month:from_month,to_month:to_month,code:code},
      success:function(result){
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    });
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/extract_journal_for_profit_loss_single_month'); ?>",
      type:"post",
      data:{month:month,code:code},
      success:function(result){
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    });
  }
}
if($(e.target).hasClass('load_profit_loss'))
{
  var from_month = $(".from_month").val();
  var to_month = $(".to_month").val();
  var from_timestamp = $(".from_month").find(":selected").attr("data-element");
  var to_timestamp = $(".from_month").find(":selected").attr("data-element");

  if(from_timestamp > to_timestamp)
  {
    alert("Please select the correct from and to month range to show the profit and loss values.")
  }else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/get_profit_loss_values'); ?>",
      type:"post",
      data:{from_month:from_month,to_month:to_month},
      dataType:"json",
      success:function(result)
      {
        $("body").removeClass("loading");
        $(".load_profit_loss_div").html(result['output']);
        $(".extract_profit_loss").show();

        var widthval = parseInt(result['countmonth']) * 150;
        var width = parseInt(widthval) + 450;

        $(".load_profit_loss_div").css("max-width",width+"px");
        $(".load_profit_loss_div").css("width",width+"px");
      }
    })
  }
}
if($(e.target).hasClass('extract_profit_loss'))
{
  var from_month = $(".from_month").val();
  var to_month = $(".to_month").val();
  var from_timestamp = $(".from_month").find(":selected").attr("data-element");
  var to_timestamp = $(".from_month").find(":selected").attr("data-element");

  if(from_timestamp > to_timestamp)
  {
    alert("Please select the correct from and to month range to show the profit and loss values.")
  }else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/extract_profit_loss_values'); ?>",
      type:"post",
      data:{from_month:from_month,to_month:to_month},
      success:function(result)
      {
        $("body").removeClass("loading");
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    })
  }
}

})

$(document).ready(function() {
$('#detail_analysis').DataTable({
    fixedHeader: {
      header: true,
      headerOffset: 500,
    },
    autoWidth: false,
    scrollX: false,
    searching: false,
    paging: false,
    info: false,
    ordering: false,
});

//   $('#client_financial').DataTable({
//     fixedHeader: {
//       header: true,
//       headerOffset: 500,
//     },
//     autoWidth: false,
//     scrollX: false,
//     searching: false,
//     paging: false,
//     info: false,
//     ordering: false,
// });
  $(".opening_financial_date").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".from_custom_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".to_custom_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  $(".from_custom_date_trial").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".to_custom_date_trial").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".date_balance_bank").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  


  // $(".opening_financial_date").on("dp.hide", function (e) {
  //     var opening_balance_date = $(".opening_financial_date").val();
  //     $.ajax({
  //       url:"<?php echo URL::to('user/save_opening_balance_date'); ?>",
  //       type:"post",
  //       data:{opening_balance_date:opening_balance_date},
  //       success:function(result)
  //       {

  //       }
  //     })
  // });
  $(".client_common_search").autocomplete({
    source: function(request, response) {        
      $.ajax({
        url:"<?php echo URL::to('user/client_review_client_common_search'); ?>",
        dataType: "json",
        data: {
            term : request.term
        },
        success: function(data) {
            response(data);
        }
      });
    },
    minLength: 1,
    select: function( event, ui ) {
      $("#client_search_hidden_infile").val(ui.item.id);
      $("#hidden_client_id").val(ui.item.id);
    }
    });
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'YYYY-MM-DD',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'YYYY-MM-DD',
    });
});


$(window).change(function(e){

// if($(e.target).hasClass('general_debit')){
//   var value = $(e.target).val();
//   if((value == '') || (value == 0)){
//     $(e.target).val('0.00');
//     $(e.target).parents("tr").find(".general_credit").attr("disabled", false);
//   }  
//   else{
//     $(e.target).parents("tr").find(".general_credit").attr("disabled", true);
//   }
//   var total_debit = 0.00;
//   var total_credit = 0.00;

//   console.log($('.general_debit').length);
//   $('.general_debit').each(function() {
//     var debit_val = $(this).val();
//     console.log(debit_val);
//     var floatdebit = parseFloat(debit_val).toFixed(2);
//     total_debit = parseFloat(total_debit + floatdebit).toFixed(2);
//   });

//   $('.general_credit').each(function() {
//     var credit_val = $(this).val();
//     var floatcredit = parseFloat(credit_val).toFixed(2);
//     total_credit = parseFloat(total_credit + floatcredit).toFixed(2);
//   });

//   $(".general_debit_total").val(total_debit);
//   $(".general_credit_total").val(total_credit);
// }

// if($(e.target).hasClass('general_credit')){
//   var value = $(e.target).val();
//   if((value == '') || (value == 0)){
//     $(e.target).val('0.00');
//     $(e.target).parents("tr").find(".general_debit").attr("disabled", false);
//   }  
//   else{
//     $(e.target).parents("tr").find(".general_debit").attr("disabled", true);
//   }

//   var total_debit = 0.00;
//   var total_credit = 0.00;

//   $('.general_debit').each(function() {
//     var debit_val = $(this).val();
//     var floatdebit = parseFloat(debit_val).toFixed(2);
//     total_debit = parseFloat(total_debit + floatdebit).toFixed(2);
//   });

//   $('.general_credit').each(function() {
//     var credit_val = $(this).val();
//     var floatcredit = parseFloat(credit_val).toFixed(2);
//     total_credit = parseFloat(total_credit + floatcredit).toFixed(2);
//   });

//   $(".general_debit_total").val(total_debit);
//   $(".general_credit_total").val(total_credit);
// }

/*if($(e.target).hasClass('general_nominal')){
  var code = $(e.target).val();

  

  if(code == '712'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Debtors Control Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Creditors Control Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813A'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Client holding account Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if((code >= '771') && (code < '772')){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Bank Nominal accounts Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else{
    $(e.target).parents("tr").find(".error-general-nominal").hide();
    $(".general_nominal").attr("disabled", false);
    $(".save_general_journal_button").show();
  }

}*/
  if($(e.target).hasClass('select_reconcile_bank')){
    var value = $(e.target).val();

    if(value == ''){
      $(".error_select_bank").show();
      $(".table_bank_details").hide();
    }
    else{
      $(".error_select_bank").hide();
      $.ajax({
        url:"<?php echo URL::to('user/finance_get_bank_details'); ?>",
        type:"post",
        dataType:"json",
        data:{id:value},
        success:function(result){
          $(".td_bank_name").html(result['bank_name']);
          $(".tb_ac_name").html(result['account_name']);
          $(".td_ac_number").html(result['account_number']);
          $(".td_ac_description").html(result['description']);
          $(".td_nominal_code").html(result['nominal_code']);
          $(".table_bank_details").show();

          $("#unreconciled_alert_text").hide();
          $("#unreconciled_count_text").html("0");
          $("#unreconciled_count").html("0");
          
          $(".transactions_section").hide();
          $(".reconcilation_section").hide();
        }
      });
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
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseFloat(value);
}
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

$(window).click(function(e) { 





  var ascending = false;
  if($(e.target).hasClass('journal_id_sort'))
  {
    var sort = $("#journal_id_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_id_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_id_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_id_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_id_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_id_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_id_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('journal_date_sort'))
  {
    var sort = $("#journal_date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_date_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_date_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_date_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_date_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_date_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_date_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('journal_des_sort'))
  {
    var sort = $("#journal_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_des_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.journal_des_sortval').html()) <
        convertToNumber($(b).find('.journal_des_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_des_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.journal_des_sortval').html()) <
        convertToNumber($(b).find('.journal_des_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('nominal_code_sort'))
  {
    var sort = $("#nominal_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_code_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.nominal_code_sortval').html()) <
        parseconvertToNumber($(b).find('.nominal_code_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_code_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.nominal_code_sortval').html()) <
        parseconvertToNumber($(b).find('.nominal_code_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('nominal_des_sort'))
  {
    var sort = $("#nominal_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_des_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sortval').html()) <
        convertToNumber($(b).find('.nominal_des_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_des_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sortval').html()) <
        convertToNumber($(b).find('.nominal_des_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('source_sort'))
  {
    var sort = $("#source_sortoptions").val();
    if(sort == 'asc')
    {
      $("#source_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.source_sortval').html()) <
        convertToNumber($(b).find('.source_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#source_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.source_sortval').html()) <
        convertToNumber($(b).find('.source_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('debit_journal_sort'))
  {
    var sort = $("#debit_journal_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_journal_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.debit_journal_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_journal_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.debit_journal_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('credit_journal_sort'))
  {
    var sort = $("#credit_journal_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_journal_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.credit_journal_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_journal_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.credit_journal_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }


  if($(e.target).hasClass('trial_code_sort'))
  {
    var sort = $("#trial_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_code_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_code_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_des_sort'))
  {
    var sort = $("#trial_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_des_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_trial_sort_val').html()) <
        convertToNumber($(b).find('.des_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_des_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_trial_sort_val').html()) <
        convertToNumber($(b).find('.des_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_primary_sort'))
  {
    var sort = $("#trial_primary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_primary_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_trial_sort_val').html()) <
        convertToNumber($(b).find('.primary_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_primary_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_trial_sort_val').html()) <
        convertToNumber($(b).find('.primary_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }
  if($(e.target).hasClass('trial_secondary_sort'))
  {
    var sort = $("#trial_secondary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_secondary_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.secondary_trial_sort_val').html()) <
        convertToNumber($(b).find('.secondary_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_secondary_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.secondary_trial_sort_val').html()) <
        convertToNumber($(b).find('.secondary_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_debit_sort'))
  {
    var sort = $("#trial_debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_debit_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.debit_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_debit_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.debit_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_credit_sort'))
  {
    var sort = $("#trial_credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_credit_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.credit_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_credit_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.credit_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
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

  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('surname_sort'))
  {
    var sort = $("#surname_sortoptions").val();
    if(sort == 'asc')
    {
      $("#surname_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_sort_val').html()) <
        convertToNumber($(b).find('.surname_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#surname_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_sort_val').html()) <
        convertToNumber($(b).find('.surname_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('firstname_sort'))
  {
    var sort = $("#firstname_sortoptions").val();
    if(sort == 'asc')
    {
      $("#firstname_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_sort_val').html()) <
        convertToNumber($(b).find('.firstname_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#firstname_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_sort_val').html()) <
        convertToNumber($(b).find('.firstname_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('company_sort'))
  {
    var sort = $("#company_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#company_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('client_summary_sort'))
  {
    var sort = $("#client_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.client_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.client_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('surname_summary_sort'))
  {
    var sort = $("#surname_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#surname_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.surname_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#surname_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.surname_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('firstname_summary_sort'))
  {
    var sort = $("#firstname_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#firstname_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.firstname_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#firstname_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.firstname_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('company_summary_sort'))
  {
    var sort = $("#company_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.company_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#company_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.company_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('debit_fin_sort'))
  {
    var sort = $("#debit_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.debit_fin_sort_val').val();
        var bval = $(b).find('.debit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#debit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.debit_fin_sort_val').val();
        var bval = $(b).find('.debit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('credit_fin_sort'))
  {
    var sort = $("#credit_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.credit_fin_sort_val').val();
        var bval = $(b).find('.credit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#credit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.credit_fin_sort_val').val();
        var bval = $(b).find('.credit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('balance_fin_sort'))
  {
    var sort = $("#balance_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#balance_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_fin_sort_val').val();
        var bval = $(b).find('.balance_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#balance_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_fin_sort_val').val();
        var bval = $(b).find('.balance_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('opening_bal_summary_sort'))
  {
    var sort = $("#opening_bal_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#opening_bal_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.opening_bal_summary_sort_val').html();
        var bval = $(b).find('.opening_bal_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#opening_bal_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.opening_bal_summary_sort_val').html();
        var bval = $(b).find('.opening_bal_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('receipt_summary_sort'))
  {
    var sort = $("#receipt_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.receipt_summary_sort_val').html();
        var bval = $(b).find('.receipt_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.receipt_summary_sort_val').html();
        var bval = $(b).find('.receipt_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('payment_summary_sort'))
  {
    var sort = $("#payment_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#payment_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.payment_summary_sort_val').html();
        var bval = $(b).find('.payment_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#payment_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.payment_summary_sort_val').html();
        var bval = $(b).find('.payment_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('balance_summary_sort'))
  {
    var sort = $("#balance_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#balance_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_summary_sort_val').html();
        var bval = $(b).find('.balance_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#balance_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_summary_sort_val').html();
        var bval = $(b).find('.balance_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  
  
  
  if($(e.target).hasClass('commit_btn'))
  {
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/commit_client_account_opening_balance'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $(e.target).parents("td:first").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>')
      }
    })
  }
  if($(e.target).hasClass('journal_source'))
  {
    $(".journal_source_viewer_modal").modal("show");
  }
  
  
  if($(e.target).hasClass('question_mark_btn'))
  {
    $(".question_mark_modal").modal("show");
  }
  

  
  if($(e.target).hasClass('load_journals'))
  {
    $("body").addClass("loading");
    var selection = $(".date_selection:checked").val();
    var from = $(".from_custom_date").val();
    var to = $(".to_custom_date").val();

    $.ajax({
      url:"<?php echo URL::to('user/load_journals_financials'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to},
      success:function(result)
      {
        $(".load_journal_div").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('load_balance'))
  {
    $("body").addClass("loading_trial");
    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();

    $.ajax({
      url:"<?php echo URL::to('user/load_trial_balance_nominals'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to},
      dataType:"json",
      success:function(result)
      {
        $("#trial_balance_tbody").html(result['output']);
        $(".total_nominal_debit").html(result['total_nominal_debit']);
        $(".total_nominal_credit").html(result['total_nominal_credit']);
        $(".total_debit_credit_tr").show();
        $(".remove_nil_balances").show();
        $(".extract_trial_pdf").show();
        $(".extract_trial_csv").show();
        $("body").removeClass("loading_trial");
      }
    })
  }
  if($(e.target).hasClass('extract_trial_pdf'))
  {
    $("body").addClass("loading_trial");
    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();
    var nil_balance = $("#hidden_remove_nil_balance").val();

    $.ajax({
      url:"<?php echo URL::to('user/extract_trial_balance_nominals_pdf'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to,nil_balance:nil_balance},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading_trial");
      }
    })
  }
  if($(e.target).hasClass('extract_trial_csv'))
  {
    $("body").addClass("loading_trial");
    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();
    var nil_balance = $("#hidden_remove_nil_balance").val();

    $.ajax({
      url:"<?php echo URL::to('user/extract_trial_balance_nominals_csv'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to,nil_balance:nil_balance},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
        $("body").removeClass("loading_trial");
      }
    })
  }
  if($(e.target).hasClass('remove_nil_balances'))
  {
    $("#trial_balance_tbody").find(".nil_balance_tr").detach();
    $("#hidden_remove_nil_balance").val(1);
  }
  if($(e.target).hasClass('get_nominal_code_journals'))
  {
    $("#journal_viewer_extend").dataTable().fnDestroy();
    var code = $(e.target).attr("data-element");
    var debit = $(e.target).parents("tr").find(".debit_trial_sort_val").html();
    var credit = $(e.target).parents("tr").find(".credit_trial_sort_val").html();
    var opening = $(e.target).attr("data-opening");

    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();
    $("body").addClass('loading');

      $.ajax({
        url:"<?php echo URL::to('user/load_trial_balance_journals_for_nominal'); ?>",
        type:"post",
        dataType:"json",
        data:{selection:selection,from:from,to:to,code:code,opening:opening},
        success:function(result)
        {
          if(result['error'] == "0"){
            alert("There is no journals created for this Nominal Code");
            $("body").removeClass('loading');
          }else{
            $(".trial_balance_journal_modal").modal("show");
            $("#trial_balance_journal_tbody").html(result['output']);

            $("#hidden_view_journal_nominal_code").val(code);
            $("#hidden_view_journal_debit").val(debit);
            $("#hidden_view_journal_credit").val(credit);
            $("#hidden_view_journal_opening").val(opening);

            $('#journal_viewer_extend').DataTable({        
                autoWidth: false,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false,
                order: [[ 1, "asc" ]]
            });
            $("body").removeClass("loading");
          }
        }
      })
  }
  if($(e.target).hasClass('extract_view_journal_csv'))
  {
    var code = $("#hidden_view_journal_nominal_code").val();
    var debit = $("#hidden_view_journal_debit").val()
    var credit = $("#hidden_view_journal_credit").val()
    var opening = $("#hidden_view_journal_opening").val()

    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();
    $("body").addClass('loading');
    if((debit == "0.00" || debit == "0" || debit == "")&& (credit == "0.00" || credit == "0" || credit == ""))
    {
      alert("There is no journals created for this Nominal Code");
      $("body").removeClass('loading');
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/extract_trial_balance_journals_for_nominal_csv'); ?>",
        type:"post",
        data:{selection:selection,from:from,to:to,code:code,opening:opening},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      })
    }
  }
  
  if($(e.target).hasClass('edit_nominal_code'))
  {
    var code = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('user/edit_nominal_code_finance'); ?>",
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
          $(".add_nominal_modal").find(".modal-title").html("Update Nominal");
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
  
  if($(e.target).hasClass('date_selection'))
  {
    var type = $(e.target).val();
    if(type == "4")
    {
      $(".from_custom_date").prop("disabled",false);
      $(".to_custom_date").prop("disabled",false);

      $(".from_custom_date").val("");
      $(".to_custom_date").val("");
    }
    else{
      $(".from_custom_date").prop("disabled",true);
      $(".to_custom_date").prop("disabled",true);

      var from = $(".date_selection:checked").attr("data-from");
      var to = $(".date_selection:checked").attr("data-to");

      $(".from_custom_date").val(from);
      $(".to_custom_date").val(to);
    }
  }
  if($(e.target).hasClass('date_selection_trial'))
  {
    var type = $(e.target).val();
    if(type == "4")
    {
      $(".from_custom_date_trial").prop("disabled",false);
      $(".to_custom_date_trial").prop("disabled",false);

      $(".from_custom_date_trial").val("");
      $(".to_custom_date_trial").val("");
    }
    else{
      $(".from_custom_date_trial").prop("disabled",true);
      $(".to_custom_date_trial").prop("disabled",true);

      var from = $(".date_selection_trial:checked").attr("data-from");
      var to = $(".date_selection_trial:checked").attr("data-to");

      $(".from_custom_date_trial").val(from);
      $(".to_custom_date_trial").val(to);
    }
  }
});


$(document).ready(function () {    
    $('.debit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });   
     $('.credit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });    
}); 

$(".debit_fin_sort_val").blur(function() {
  var debit = $(this).val();
  var client_id = $(this).attr("data-element");
  var credit = $(".credit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});
$(".credit_fin_sort_val").blur(function() {
  var credit = $(this).val();
  var client_id = $(this).attr("data-element");
  var debit = $(".debit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});

$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var valueInterval_client = 1000;  //time in ms, 5 second for example 
    if($(e.target).hasClass('bank_name_add'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_add'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('bank_name_edit'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_edit'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('debit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var credit = $(".credit_fin_sort_val_"+client_id).val();

      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,input_val,credit,client_id);   
    }
    if($(e.target).hasClass('credit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var debit = $(".debit_fin_sort_val_"+client_id).val();
      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,debit,input_val,client_id);   
    }
});
function doneTyping (bank_name,account_no) {
  $(".nominal_description_add").val(bank_name+' '+account_no);
}
function doneTyping_edit (bank_name,account_no) {
  $(".nominal_description_edit").val(bank_name+' '+account_no);
}
function doneTyping_debit (debit,credit,client_id) {
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
}

$(window).keydown(function(e) {

if($(e.target).hasClass('general_credit_last')){
  var keyCode = e.keyCode || e.which; 

  if (keyCode == 9) { 
    e.preventDefault(); 
    var general_nominal = $(".general_nominal_hidden_for_add_ajax").html();

    $("#general_journal_tboday").append('<tr><td><select class="general_nominal" name="general_nominal[]">'+general_nominal+'</select><label class="error error-general-nominal" ></label></td><td><input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription" ></label></td><td><input type="text" style="text-align: right;" class="general_debit" value="0.00" required placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit" ></label></td><td><input type="text" style="text-align: right;" class="general_credit general_credit_last" value="0.00" required placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit" ></label></td><td style="text-align: center;"><a href="javascript:" class="fa fa-plus add_general" style="margin-top: 10px;display:none"></a><a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; " title="Delete"></a></td></tr>');

      blurfunction_gj();


  }
  $(e.target).removeClass("general_credit_last");
  
}

})


$('#practice_turnover_table').DataTable({        
    autoWidth: true,
    scrollX: false,
    fixedColumns: false,
    searching: false,
    paging: false,
    info: false
});

$('#practice_client_table').DataTable({        
    autoWidth: true,
    scrollX: false,
    fixedColumns: false,
    searching: false,
    paging: false,
    info: false
});

$('#practice_staff_table').DataTable({        
    autoWidth: true,
    scrollX: false,
    fixedColumns: false,
    searching: false,
    paging: false,
    info: false
});

</script>

<script>





</script>

<!-- <script>
$(document).ready(function() {
    $(".accounting_period_modal").modal('show');
});
</script> -->




@stop
