@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
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
.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
    display: block;
}
.modal_load_import {
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
body.loading_import {
    overflow: hidden;   
}
body.loading_import .modal_load_import {
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

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
               Opening Balance Manager - Import Balances
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
      <form id="import_balance_form" action="<?php echo URL::to('user/import_opening_balance'); ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-12">
          <div class="col-md-12">
            <input type="radio" name="import_balance" class="import_balance" id="import_balance_1" value="1" checked><label for="import_balance_1" style="font-size:18px;font-weight:700">Import Balances Only</label>
          </div>
          <div class="col-md-12" style="margin-top:10px">
            <input type="radio" name="import_balance" class="import_balance" id="import_balance_2" value="2"><label for="import_balance_2" style="font-size:18px;font-weight:700">Import Balances from Outstanding Invoices</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-12">
            <h4 style="line-height: 27px;" id="import_note">Import Balances Only – Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import</h4>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <div class="col-md-5">
            <h4 style="font-weight:700">Import File:</h4>
          </div>
          <div class="col-md-7">
            <input type="file" name="balance_file" class="form-control balance_file" value="">
          </div>
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <div class="col-md-12">
            <input type="button" class="common_black_button activate_file" id="activate_file" value="Activate File" style="width:100%">
          </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-12">
          	<h4 style="font-weight:700">File Information:</h4>
            <h4 style="line-height: 27px;" id="import_note_1">
              <p>The file must be in CSV format.<br/>
              The date must be in either dd-mm-yyyy (for Ex: 07-09-2020) OR dd/mm/yyyy (for Ex: 07/09/2020) format.<br/>
              The titles must be<br/>
              1. Code<br/>
              2. Balance<br/>
              3. Date<br/>
              In that order<br/>
              No blank line after the Title Line</p>
            </h4>
          </div>
        </div>
      @csrf
</form>
    </div>
    <div class="col-md-5">
      <div class="col-md-12 import_table" style="margin-top:10px;display:none">
        <div class="col-md-5">
          <h4 style="font-weight:700">Opening Balance Date:</h4>
        </div>
        <div class="col-md-7">
          <input type="text" name="opening_balance_date" class="form-control opening_balance_date" value="" style="cursor:pointer">
        </div>
      </div>
      <div class="col-md-12" style="margin-bottom: 20px">
        <input type="button" class="common_black_button start_import" id="activate_file" value="Start Import" style="width:100%;display:none">
      </div>
      <table class="table own_table_white import_table" style="display:none;">
        <thead>
          <tr >
            <th colspan="6" style="border-bottom:3px solid #fff !important;" >File Content</th>
          </tr>
          <tr>
            <th style="text-align: left">Client ID</th>
            <th style="text-align: left">Inv No</th>
            <th style="text-align: right">Balance</th>
            <th style="text-align: left">ID Check</th>
            <th style="text-align: left">Value Check</th>
            <th style="text-align: left">Invoice No Check</th>
          </tr>
        </thead>
        <tbody id="import_tbody_list">
         
        </tbody>
      </table>
    </div>
    <div class="col-md-1">&nbsp;</div>
  </div>
  <div class="modal_load"></div>
  <div class="modal_load_content" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the data from the CSV file are verified.</p>
    <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
  </div>
  <div class="modal_load_import" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the verified data are imported.</p>
    <p style="font-size:18px;font-weight: 600;">Importing Invoices for <span id="import_first">0</span> of <span id="import_last"></span> Clients</p>
  </div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
  <input type="hidden" name="hidden_filename" id="hidden_filename" value="">
  <input type="hidden" name="hidden_session_id" id="hidden_session_id" value="">
</div>
<script type="text/javascript">

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

function import_opening_balance_function(filename,import_type,page,session_id,count,totalcount)
{
  var countval = parseInt(count) + 100;
  $.ajax({
    url:"<?php echo URL::to('user/import_opening_balance'); ?>",
    type:"post",
    dataType:"json",
    data:{filename:filename,import_type:import_type,page:page,session_id:session_id},
    success: function(result)
    {
      if(result['page'] > "0")
      {
      	$("#count_first").html(countval);
        $("#count_last").html(totalcount);
        $("#import_tbody_list").append(result['output']);
        import_opening_balance_function(filename,import_type,result['page'],session_id,countval,totalcount);
      }
      else{
        $(".opening_balance_date").val("");
        if(result['error'] == "0")
        {
        	$.ajax({
          		url:"<?php echo URL::to('user/get_client_counts_opening_balance'); ?>",
          		type:"post",
          		data:{session_id:session_id},
          		success: function(eresult)
          		{
          			$("#import_last").html(eresult);
          			$(".import_table").show();
			        $(".start_import").show();
			        $("#import_tbody_list").append(result['output']);
			        $("#hidden_filename").val(result['upload_dir']);
			        $("#hidden_session_id").val(result['session_id']);
			        $("#count_first").html("");
			        $("#count_last").html("");
			        $("body").removeClass("loading_content");
          		}
          	});
        }
        else{
          $("#import_last").html("");
          $(".import_table").hide();
          $(".start_import").hide();
          $("#count_first").html("");
          $("#count_last").html("");
          $("body").removeClass("loading_content");
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>"+result['message']+"</p>",width:"30%",fixed:true});
        } 
      }
    }
  });
}
function import_opening_balance_to_clients(filename,bal_date,import_type,session_id,page)
{
	$.ajax({
      url:"<?php echo URL::to('user/import_opening_balance_to_clients'); ?>",
      type:"post",
      dataType:"json",
      data:{filename:filename,bal_date:bal_date,import_type:import_type,session_id:session_id,page:page},
      success: function(result)
      {
      	if(result['status'] == "start")
      	{
      		$("#import_first").html(result['page']);
      		import_opening_balance_to_clients(filename,bal_date,import_type,session_id,result['page']);
      	}
      	else{
      		$("#import_tbody_list").html("");
	        $("#hidden_filename").val("");
	        $("#hidden_session_id").val("");
	        $(".opening_balance_date").val("");
	        $(".balance_file").val("");

	        $(".import_table").hide();
	        $(".start_import").hide();
	        $("body").removeClass("loading_import");
	        $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>File Imported Successfully</p>",width:"30%",fixed:true});
      	}
      }
    });
}
$(window).click(function(e){
  if(e.target.id == "import_balance_1")
  {
    $("#import_note").html("Import Balances Only – Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import");
    $("#import_note_1").html("<p>The file must be in CSV format.<br/>The date must be in the format dd-mm-yyyy (for Ex: 07-09-2020)<br/>The titles must be<br/>1. Code<br/>2. Balance<br/>3. Date<br/>In that order<br/>No blank line after the Title Line</p>");
  }
  if(e.target.id == "import_balance_2")
  {
    $("#import_note").html("Import Balances From Outstanding Invoices – Select a CSV file that has an invoice number and an unpaid value for that invoice");
    $("#import_note_1").html("<p>The file must be in CSV format.<br/>The date must be in the format dd-mm-yyyy (for Ex: 07-09-2020)<br/>The titles must be<br/>1. Inv No<br/>2. Gross<br/>3. Date<br/>In that order<br/>No blank line after the Title Line</p>");
  }
  if($(e.target).hasClass('activate_file'))
  {
    var file = $(".balance_file").val();
    if(file == "")
    {
      if($("#import_balance_1").is(":checked"))
      {
        alert("Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import");
      }
      else{
        alert("Please Select a CSV file that has Invoice ID and Balance.  Balances will be imported for each invoices and will not be locked on import");
      }
    }
    else{
      $("body").addClass("loading");
      setTimeout(function(){ 
          $('#import_balance_form').ajaxForm({
              dataType:"json",
              data:{page:"1"},
              success:function(e){
                if(e['page'] > "0")
                {
                	$("#count_first").html("100");
                	$("#count_last").html(e['highestRow']);
                  $("body").removeClass("loading");
                  $("#import_tbody_list").html(e['output']);
                  $("body").addClass("loading_content");
                  import_opening_balance_function(e['upload_dir'],e['import_type'],e['page'],e['session_id'],"100",e['highestRow']);
                }
                else{
                  $(".opening_balance_date").val("");
                  if(e['error'] == "0")
                  {
                  	$.ajax({
                  		url:"<?php echo URL::to('user/get_client_counts_opening_balance'); ?>",
                  		type:"post",
                  		data:{session_id:e['session_id']},
                  		success: function(result)
                  		{
                  			$("#import_last").html(result);
                  			$(".import_table").show();
		                    $(".start_import").show();
		                    $("#import_tbody_list").html(e['output']);
		                    $("#hidden_filename").val(e['upload_dir']);
		                    $("#hidden_session_id").val(e['session_id']);
		                    $("#count_first").html("");
		                    $("#count_last").html("");
		                    $("body").removeClass("loading");
                  		}
                  	});
                  }
                  else{
                  	$("#import_last").html("");
                    $(".import_table").hide();
                    $(".start_import").hide();
                    $("#count_first").html("");
                    $("#count_last").html("");
                    $("body").removeClass("loading");
                    $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>"+e['message']+"</p>",width:"30%",fixed:true});
                  } 
                }
              }
          }).submit();
      }, 2000);
    }
  }
  if($(e.target).hasClass('start_import'))
  {
    var errors = $(".error_import").length;
    var bal_date = $(".opening_balance_date").val();
    var session_id = $("#hidden_session_id").val();
    if(errors > 0)
    {
      $.ajax({
        url:"<?php echo URL::to('user/clear_import_opening_balance'); ?>",
        type:"post",
        data:{session_id:session_id},
        success: function(result)
        {
          alert("Itseems some of the Data failed in the csv file you are trying to import. Please check the failed data then replace the csv file and start importing.");
        }
      });
    }
    else if(bal_date == "")
    {
      alert("Please Enter the Opening Balance Date and then click on to Start Import button.")
    }
    else{
      $("body").addClass("loading_import");
	    var import_type = $(".import_balance:checked").val();
	    var filename = $("#hidden_filename").val();
	    $("#import_first").html("0");
	    $.ajax({
	      url:"<?php echo URL::to('user/import_opening_balance_to_clients'); ?>",
	      type:"post",
	      dataType:"json",
	      data:{filename:filename,bal_date:bal_date,import_type:import_type,session_id:session_id,page:"0"},
	      success: function(result)
	      {
	      	if(result['status'] == "start")
	      	{
	      		$("#import_first").html(result['page']);
	      		import_opening_balance_to_clients(filename,bal_date,import_type,session_id,result['page']);
	      	}
	      	else{
	      		$("#import_tbody_list").html("");
		        $("#hidden_filename").val("");
		        $("#hidden_session_id").val("");
		        $(".opening_balance_date").val("");
		        $(".balance_file").val("");

		        $(".import_table").hide();
		        $(".start_import").hide();
		        $("body").removeClass("loading_import");
		        $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>File Imported Successfully</p>",width:"30%",fixed:true});
	      	}
	      }
	    });
    }
  }
});
</script>
@stop