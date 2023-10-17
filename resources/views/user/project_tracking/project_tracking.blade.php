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
<link rel="stylesheet" href="<?php echo URL::to('public/assets/css/easypayroll.css'); ?>">
<style>
  #selected_invoice_expand tbody tr td{
  padding:0px 8px !important;
  border-top: 0px;
}
#edit_invoice_expand tbody tr td{
  padding:0px 8px !important;
  border-top: 0px;
}

#edit_invoice_expand thead tr th{
  border-bottom: 0px;
}

 #selected_invoice_expand tbody .empty_line_row{
  height:40px;
  border-top: 0px;
  border-bottom: 0px;
}
 #edit_invoice_expand tbody .empty_line_row{
  height:40px;
  border-top: 0px;
  border-bottom: 0px;
}
 .display_table_expand tbody .empty_line_row{
  height:25px;
}
.header_info_title{
  width:75px;
  margin-bottom: 0px !important;
}
.header_info_to_title{
  width:30px;
  margin-bottom: 0px !important;
}

.footer_info_title{
  margin-bottom: 0px !important;
  height:35px !important;
  font-weight: 700 !important;
}
.right{
  text-align: right;
}
.right_start{
  text-align: right;
}
.input-sm{
  font-size:14px;
}
#settings_table tbody tr td{
height: 44px;
}
#settings2_table tbody tr td {
  height: 44px;
}
#settings2_table thead tr th {
  height: 44px;
}
.prin_pdf{
  float:left;
  width:99%;
  margin-top:8px;
  text-align: left;
}
#colorbox{
  z-index:9999999;
}
input[type="checkbox"]:not(old) + label, input[type="radio"]:not(old) + label{
  margin-left:0px;
}
input[type="checkbox"]:not(old), input[type="radio"]:not(old){
  display:none;
}
.complex_project_div{
  float:left;
  margin-bottom:10px;
  width: 100%;
  background: #dfdfdf;
  padding: 15px;
}
.close_computation{
  color: #f00;
  font-size: 18px;
  float: right;
}
.import_ul{
float: left;
width: 100%;
margin-left: -38px;
}
.import_li{
  list-style: none;
float: left;
width: 20%;
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
.project_value, .project_comment, .project_date {
  height: 45px;
  background: #f5f5f5;
  border: 1px solid #e1e1e1;
  border-top: 0px;
  border-bottom: 0px;
}
.project_tracking_clients_div > .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
  padding: 0px;
}
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>
<div class="modal fade tracking_project_manager_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document" style="width: 45%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Project Management</h4>
      </div>
      <div class="modal-body">
        <div id="step1_div" style="width:100%;">
          <h4>Project List <a href="javascript:" class="common_black_button add_tracking_project" style="float:right;font-size:14px">Add Tracking Project</a></h4>
          <div class="project_management_div" style="width:100%;min-height: 400px;max-height: 500px;overflow-y: scroll;margin-top:25px">
            <table class="table" id="project_expand">
              <thead>
                <th>Project Name</th>
                <th>Project Type</th>
                <th>Creation Date</th>
              </thead>
              <tbody id="projects_tbody">
                  <?php
                  $projects = DB::table('tracking_projects')->where('practice_code',Session::get('user_practice_code'))->get();
                  if(($projects))
                  {
                    foreach($projects as $project){
                      $project_type_details = DB::table('tracking_type')->where('id',$project->tracking_type)->first();

                      echo '<tr class="project_list_tr">
                        <td>'.$project->project_name.'</td>
                        <td>'.$project_type_details->tracking_type.'</td>
                        <td>'.date('d-M-Y', strtotime($project->creation_date)).'</td>
                      </tr>';
                    }
                  }
                  else{
                    echo '<tr>
                      <td>No Projects Found</td>
                      <td></td>
                      <td></td>
                    </tr>';
                  }
                  ?>
              </tbody>
            </table>
          </div>
        </div>
        <div id="step2_div" style="display:none">
          <h4>Add New Tracking Project</h4>
          <form name="add_tracking_project_form" id="add_tracking_project_form" method="post">
            <div class="col-lg-12" style="padding:0px;margin-top:25px;">
              <div class="col-md-12">
                <label>Project Name:</label>
                <div class="form-group">
                    <input type="text" class="form-control project_name" name="project_name" id="project_name" placeholder="Enter Project Name" value="" required />
                </div>  
              </div>
              <div class="col-md-12">
                <label>Creation Date:</label>
                <div class="form-group">
                    <input type="text" class="form-control creation_date" name="creation_date" id="creation_date" placeholder="Enter Creation Date" value="" required/> 
                </div>  
              </div>
              <div class="col-md-12" style="padding:0px;text-align: center;">
                <input type="button" class="cancel_add_project common_black_button" value="Cancel" style="width:47%">
                <input type="button" class="next_add_project common_black_button" value="Next" style="width:47%">
              </div>                
            </div>
          @csrf
</form>
        </div>
        <div id="step3_div" style="display:none">
          <h4>Add Tracking Overlay - Tracking Type</h4>
          <div class="col-lg-12" style="margin-top:25px;">
            <label>Select Tracking Type:</label>
            <?php
              $types = DB::table("tracking_type")->get();
              if(($types))
              {
                foreach($types as $type){
                  echo '<p><input type="radio" name="tracking_type_select" class="tracking_type_select" id="tracking_type_select_'.$type->id.'" value="'.$type->id.'" data-element="'.$type->description.'"><label for="tracking_type_select_'.$type->id.'">'.$type->tracking_type.'</label></p>';
                }
              }
            ?>
            <div id="type_description_select"></div>
          </div>
          <div class="col-md-6 monthly_value_div" style="margin-top:25px;display:none">
                <label>Select year:</label>
                <div class="form-group">
                    <select name="monthly_value_select" class="form-control monthly_value_select">
                      <option value="">Select Year</option>
                      <?php 
                      $current_year = date('Y');
                      $from_year = $current_year - 20;
                      $to_year = $current_year + 10;

                      for($i=$from_year; $i<=$to_year; $i++){
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                      ?>
                    </select>
                </div>  
              </div>
          <div class="col-md-12" style="margin-top:20px">
            <input type="button" class="back_to_project common_black_button" value="Back" style="width:48%">
            <input type="button" class="submit_add_project common_black_button" value="Submit" style="width:48%">
            <input type="button" class="next_add_project2 common_black_button" value="Next" style="width:48%;display:none">
          </div>     
        </div>
        <div id="step4_div" style="display:none">
          <h4>Complex Project Rule Construction <a href="javascript:" class="common_black_button add_project_complex" style="float:right;font-size:14px">Add Project</a></h4>
          <form id="complex_form" method="post">
            <div class="col-lg-12 complex_project_main_div" style="margin-top:25px;">
                <div class="complex_project_div">
                  <button type="button" class="close_computation">&times;</button>
                  <div class="col-md-12 padding_00">
                    <div class="col-md-3 padding_00">
                        <label>Select Project:</label>
                    </div>
                    <div class="col-md-6 select_project_complex_div padding_00">
                        <select name="select_complex_project" class="form-control select_complex_project">
                          <option value="">Select Project</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-12 padding_00" style="margin-top:20px">
                    <div class="col-md-3 padding_00">
                        <label>Select Computation:</label>
                    </div>
                    <div class="col-md-6 select_project_computation_div padding_00">
                        <select name="select_computation" class="form-control select_computation">
                          <option value="1">+ (Plus)</option>
                          <option value="2">- (Minus)</option>
                          <option value="3">/ (Division)</option>
                          <option value="4">* (Multiplications)</option>
                        </select>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-12 complex_value_div" style="margin-top:25px;">
              <div class="col-md-3 padding_00">
                <label>Enter Value:</label>
              </div>
              <div class="col-md-6 padding_00">
                <div class="form-group">
                  <input type="text" name="complex_value" class="form-control complex_value" id="complex_value" value="">
                </div>
              </div>
            </div>
          @csrf
</form>
          <div class="col-md-12" style="margin-top:20px">
            <input type="button" class="back_to_project_construction common_black_button" value="Back" style="width:48%">
            <input type="button" class="submit_add_project_construction common_black_button" value="Submit" style="width:48%">
          </div>     
        </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          
      </div>
    </div>
  </div>
</div>
<div class="modal fade add_clients_to_project_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Clients</h4>
      </div>
      <div class="modal-body">
          <input type="checkbox" name="select_all_clients_tracking" id="select_all_clients_tracking" class="select_all_clients_tracking"><label for="select_all_clients_tracking" style="margin-right: 20px">Select All</label>
          <input type="checkbox" name="hide_deactivated_clients_tracking" id="hide_deactivated_clients_tracking" class="hide_deactivated_clients_tracking"><label for="hide_deactivated_clients_tracking">Hide Deactivated</label>
          <div id="add_clients_to_project_tbody" style="width:100%;max-height: 500px;overflow-y: scroll">
          </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          <input type="button" class="submit_clients_to_project common_black_button" value="Add Clients" style="float:right">
      </div>
    </div>
  </div>
</div>
<div class="modal fade alert_box" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          This is a complex project, the project is dependant on other projects, the data on these projects may have changed since you last viewed this project.  Would you like to re-load all the data from the dependency projects?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_complex">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_complex">No</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade copy_paste_alert_box" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          Can't paste because the selection is larger than the destination
        </div>
      </div>
    </div>
</div>
<div class="modal fade complex_analysis_project_overlay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" style="width:35%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="exampleModalLabel">Complex Project Client Dependancy Analysis</h5>
        </div>
        <div class="modal-body" id="complex_analysis_project_tbody">
          
        </div>
        <div class="modal-footer" style="clear:both">
          <button type="button" class="common_black_button" data-dismiss="modal" style="float:right">Close</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade import_project_overlay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" style="width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="exampleModalLabel">Import Project Client Dependancy Analysis</h5>
        </div>
        <div class="modal-body" id="import_tbody">
          
        </div>
        <div class="modal-footer" style="clear:both">
          <button type="button" class="common_black_button submit_imported_project_tracking" style="float:right;display:none">Submit</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade complex_project_overlay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="exampleModalLabel">Complex Project Rule Construction</h5>
        </div>
        <div class="modal-body" id="complex_project_tbody">
          
        </div>
        <div class="modal-footer" style="clear:both">
          <button type="button" class="common_black_button" data-dismiss="modal" style="float:right">Close</button>
        </div>
      </div>
    </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  	<div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title">Client Project Tracking System <!-- <a href="javascript:" class="common_black_button build_invoice_settings" title="Build Invoice Settings" style="float:right;margin-right:20px"><i class="fa fa-cogs"></i> Settings</a> --></h4>
        <div class="col-lg-12" style="padding-right: 0px;">
        	<div class="col-lg-1 padding_00">
    				<h5 style="font-weight: 600">Tracking Projects: </h5>
    			</div>
    			<div class="col-lg-2" style="padding: 0px;">
    				<div class="form-group">
                <select class="form-control project_common_search">
                  <option value="">Select Project</option>
                  <?php
                  $projects = DB::table('tracking_projects')->where('practice_code',Session::get('user_practice_code'))->get();
                  if(is_countable($projects) && count($projects)){
                    foreach($projects as $project){
                      echo '<option value="'.$project->id.'">'.$project->project_name.'</option>';
                    }
                  }
                  ?>
                </select>
    				    <!-- <input type="text" class="form-control project_common_search" placeholder="Enter Project Name" style="font-weight: 500; width:100%; display:inline;" value="" required />    -->      
    				    <input type="hidden" class="project_search_common" id="project_search_hidden_tracking" value="" name="project_id">
    				</div>                  
    			</div>
    			<div class="col-md-2" style="padding: 0px">
    				<input type="button" name="load_client_review" class="common_black_button load_tracking_project" value="Load">
            <input type="button" name="tracking_project_manager_btn" class="common_black_button tracking_project_manager_btn" value="Tracking Projects Manager">
    			</div>
        </div>

        <div class="col-lg-12 project_details" style="padding-right: 0px;display:none">
          <div class="col-lg-1 padding_00">
            <h5 style="font-weight: 600">Project Type: </h5>
          </div>
          <div class="col-lg-2" style="padding: 0px;">
            <div class="form-group">
                <select name="project_type" class="form-control project_type">
                  <option value="">Select Project type</option>
                  <?php
                  $project_types = DB::table('tracking_type')->get();
                  if(count($project_types) > 0)
                  {
                    foreach($project_types as $type){
                      echo '<option value="'.$type->id.'">'.$type->tracking_type.'</option>';
                    }
                  }
                  ?>
                </select>
            </div>                  
          </div>
          <div class="col-md-2" style="padding: 0px">
            <input type="button" name="show_computation_icon" class="common_black_button show_computation_icon" value="..." title="Complex Project Construction">
          </div>
        </div>

        <div class="col-lg-12 project_details" style="padding-right: 0px;display:none">
          <div class="col-lg-1 padding_00">
            &nbsp;
          </div>
          <div class="col-lg-4 project_description" style="padding: 0px;">
               
          </div>
        </div>

        <div class="col-lg-12 project_details" style="padding-right: 0px;display:none">
          <div class="col-lg-1 padding_00">
            <h5 style="font-weight: 600">Creation Date: </h5>
          </div>
          <div class="col-lg-4 project_creation_date" style="padding: 0px;margin-top: 7px;">
               
          </div>
        </div>

        <div class="col-lg-12 project_details" style="padding-right: 0px;margin-top:30px;display:none">
          <h5 style="font-weight: 600">Project Data: <a href="javascript:" class="common_black_button add_clients_to_project_btn" style="margin-left:20px">Add Clients</a>

            <a href="javascript:" class="common_black_button import_project" style="margin-left:20px">Import</a>
            <a href="javascript:" class="common_black_button export_project" style="margin-left:20px">Export</a>
          </h5>
          <div class="project_tracking_clients_div">

          </div>
        </div>
	</div>
    <!-- End  -->
	<div class="main-backdrop"><!-- --></div>
	<div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p class="first_cls_div" style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait untill all the project Data being loaded.</p>
  </div>
	<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
</div>
<script>
  $.ajaxSetup({async:false});
$('#add_tracking_project_form').validate({
    rules: {
        project_name : {required: true,remote:"<?php echo URL::to('user/check_project_tracking_project_name'); ?>"},
        creation_date : {required: true}
    },
    messages: {
        project_name : {
          required : "Project Name is Required",
          remote : "Project Name is Already created",
        },
        creation_date : {
          required : "Creation Date is Required",
        }
    },
});
$(document).ready(function() {
  $('#project_expand').DataTable({
      autoWidth: false,
      scrollX: false,
      fixedColumns: false,
      searching: false,
      paging: false,
      info: false,
      order: []
  });
  var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  $(".creation_date").datetimepicker({
     defaultDate: fullDate,       
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
	// $(".project_common_search").autocomplete({
	// 	source: function(request, response) {        
	// 		$.ajax({
	// 		  url:"<?php echo URL::to('user/tracking_project_common_search'); ?>",
	// 		  dataType: "json",
	// 		  data: {
	// 		      term : request.term
	// 		  },
	// 		  success: function(data) {
	// 		      response(data);
	// 		  }
	// 		});
	// 	},
	// 	minLength: 1,
	// 	select: function( event, ui ) {
	// 		$("#project_search_hidden_tracking").val(ui.item.id);
 //      $(".project_type").val(ui.item.project_type);
 //      $(".project_description").html(ui.item.project_description);
 //      $(".project_creation_date").html(ui.item.creation_date);
	// 	}
 //  });
  
});

$(window).change(function(e) {
  if($(e.target).hasClass('project_common_search')) {
    var value = $(e.target).val();
    if(value != "") {
      $.ajax({
        url:"<?php echo URL::to('user/tracking_project_common_search'); ?>",
        type:"get",
        data:{value:value},
        dataType: "json",
        success: function(result) {
            $("#project_search_hidden_tracking").val(result['id']);
            $(".project_type").val(result['project_type']);
            $(".project_description").html(result['project_description']);
            $(".project_creation_date").html(result['creation_date']);
        }
      });
    }
  }
})
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumeric = function(value){
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
       return parseInt(value.toLowerCase());
}
function afterajaxrequest(){
  $('.project_value').keypress(function (e) {    
     var charCode = (e.which) ? e.which : event.keyCode    
     if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
         return false;                        
  });   
  $(".project_date").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".project_date").on("dp.hide", function (e) {
    var id = $(this).attr("data-element");
    var datevalue = $(this).val();
    $.ajax({
        url:"<?php echo URL::to('user/save_tracking_project_date'); ?>",
        type:"post",
        data:{id:id,datevalue:datevalue},
        success:function(result){
          
        }
    })
  });

  $(".project_comment").blur(function(e){
    var id = $(this).attr("data-element");
    var comment = $(this).val();

    $.ajax({
        url:"<?php echo URL::to('user/save_tracking_project_comment'); ?>",
        type:"post",
        data:{id:id,comment:comment},
        success:function(result){
          
        }
    })
  });

  $(".project_value").blur(function(e){
    var id = $(this).attr("data-element");
    var value = $(this).val();
    var key = $(this).attr("data-value");
    var project_id = $("#project_search_hidden_tracking").val();

    $.ajax({
        url:"<?php echo URL::to('user/save_tracking_project_value'); ?>",
        type:"post",
        data:{id:id,value:value,project_id:project_id,key:key},
        success:function(result){
          
        }
    })
  });
}

function copyPasteTableData(){
  $(document).on('paste', 'input',function (e) {
    var $start = $(this);
    projectTrackClientId=$start.attr("data-element");
    var source
 
    //check for access to clipboard from window or event
    if (window.clipboardData !== undefined) {
        source = window.clipboardData
    } else {
        source = e.originalEvent.clipboardData;
    }
    var data = source.getData("Text");
    if (data.length > 0) {
        if (data.indexOf("\t") > -1) {
            var columns = data.split("\n"); 
            var copyDataLength = data.split("\t").length;
            var colsLength = $start.parents('td').nextAll('td').length + 1;
            var arrData=[];
            var arrCols=[];
            
            if(copyDataLength <= colsLength){           
              $.each(columns, function () {
                  var values = this.split("\t");
                  $.each(values, function () {                 
                      $start.val($.trim(this));
                      arrData.push($.trim(this));
                      arrCols.push($start.attr("data-value"));
                      
                      if ($start.closest('td').next('td').find('input')[0] != undefined || $start.closest('td').next('td').find('input')[0] != undefined) {
                        $start = $start.closest('td').next('td').find('input');
                      }
                      else
                      {
                      return false;  
                      }
                      
                  });
                  $start = $start.closest('td').parent().next('tr').children('td:first').find('input');
              });
              
              $.ajax({
                  url:"<?php echo URL::to('user/insert_tracking_project_copy_pasted_data'); ?>",
                  type:"post",
                  data: {id:projectTrackClientId,value:JSON.stringify(arrData), key:JSON.stringify(arrCols)},
                  success:function(result){
                  }
              })
            }else{
              alert("Alert! can't paste because the selection is larger than the destination.")
            }
            e.preventDefault();
        }
    }
  });
}

$(window).click(function(e) {
  if($(e.target).hasClass('export_project')){
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/export_client_project_tracking'); ?>",
      type:"post",
      data:{project_id:project_id,project_type:project_type},
      success:function(result){
          SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    })
  }
  if($(e.target).hasClass('import_project')){
    var project_id = $("#project_search_hidden_tracking").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/import_client_project_tracking'); ?>",
      type:"post",
      data:{project_id:project_id},
      success:function(result){
        $(".import_project_overlay").modal("show");
        $("#import_tbody").html(result);
        $(".submit_imported_project_tracking").hide();
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('check_imported_file'))
  {
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();

    var file = $(".import_client_project").val();
    if(file == "")
    {
      alert("Please select the CSV file to import");
    }
    else{
      $("body").addClass("loading");
      var formData = $("#import_project_form").submit(function (e) {
        return;
      });
      var formData = new FormData(formData[0]);
      $.ajax({
          url: "<?php echo URL::to('user/check_import_csv_project_tracking'); ?>",
          type: 'POST',
          data: formData,
          dataType:"json",
          success: function (data) {
            if(data['error_code'] == "1"){
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">The Headers on the CSV File you imported does not match the criteria.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
            }
            else if(data['error_code'] == "2"){
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Invalid CSV File.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
            }
            else if(data['error_code'] == "3"){
              $("#checked_content_tbody").html(data['output']);
              $(".submit_imported_project_tracking").hide();
            }
            else{
              $("#checked_content_tbody").html(data['output']);
              $(".submit_imported_project_tracking").show();
            }

            $("body").removeClass("loading");
          },
          cache: false,
          contentType: false,
          processData: false
      });
    }
  }
  if($(e.target).hasClass('submit_imported_project_tracking')){
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();
    var count_selected = $(".select_column_tracking:checked").length;

    if(count_selected > 0){
      var file = $(".import_client_project").val();
      if(file == "")
      {
        alert("Please select the CSV file to import");
      }
      else{
        $("body").addClass("loading");
        var formData = $("#import_project_form").submit(function (e) {
          return;
        });
        var formData = new FormData(formData[0]);
        $.ajax({
            url: "<?php echo URL::to('user/submit_import_csv_project_tracking'); ?>",
            type: 'POST',
            data: formData,
            success: function (data) {
              $("body").removeClass("loading");
              $(".import_project_overlay").modal("hide");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Clients Imported Successfully.</p>'});
              if(project_type == "6" || project_type == "7"){
                $(".no_complex").trigger('click');
              }
              else{
                $(".load_tracking_project").trigger('click');
              }
            },
            cache: false,
            contentType: false,
            processData: false
        });
      }
    }
    else{
      alert("Please select atlease one column to import");
    }
  }
  if($(e.target).hasClass('show_computation_icon')){
    var project_id = $("#project_search_hidden_tracking").val();
    if(project_id != ""){
      $.ajax({
        url:"<?php echo URL::to('user/show_complex_project_construction'); ?>",
        type:"post",
        data:{project_id:project_id},
        success:function(result){
          $(".complex_project_overlay").modal("show");
          $("#complex_project_tbody").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('show_computation_icon_client')){
    var client_id = $(e.target).attr("data-element");
    var project_id = $("#project_search_hidden_tracking").val();
    if(project_id != ""){
      $.ajax({
        url:"<?php echo URL::to('user/show_complex_project_construction_client'); ?>",
        type:"post",
        data:{project_id:project_id,client_id:client_id},
        success:function(result){
          $(".complex_analysis_project_overlay").modal("show");
          $("#complex_analysis_project_tbody").html(result);
        }
      })
    }
  }
  
  if($(e.target).hasClass('select_all_clients_tracking')){
    if($(e.target).is(":checked")){
      $('#project_tracking_expand').find("tr:visible").find(".select_client_tracking").prop("checked",true);
    }
    else{
      $(".select_client_tracking").prop("checked",false);
    }
  }
  if($(e.target).hasClass('hide_deactivated_clients_tracking')){
    if($(e.target).is(":checked")){
      $(".inactive_clients").hide();
      $(".inactive_clients").find(".select_client_tracking").prop("checked",false);
    }
    else{
      $(".inactive_clients").show();
    }
  }
  if($(e.target).hasClass('submit_clients_to_project')){
    var ids = '';
    $(".select_client_tracking:checked").each(function(index, value){
      var client_id = $(this).attr("data-element");
      if(ids == ''){
        ids = client_id;
      }
      else{
        ids = ids+','+client_id;
      }
    });
    var project_id = $("#project_search_hidden_tracking").val();
    if(ids != ""){
      $("body").addClass('loading_apply');
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/submit_clients_to_project_tracking'); ?>",
          type:"post",
          data:{ids:ids,project_id:project_id},
          success:function(result){
            $(".add_clients_to_project_overlay").modal("hide");
            if(result == "6" || result == "7"){
              $(".no_complex").trigger('click');
            }
            else{
              $(".load_tracking_project").trigger('click');
            }
          }
        });
      },1000);
    }
    else{
      alert("Please select atleast one client to add to project");
    }
  }
  if($(e.target).hasClass('add_clients_to_project_btn'))
  {
    $("#project_tracking_expand").dataTable().fnDestroy();
    var project_id = $("#project_search_hidden_tracking").val();
    if(project_id != ""){
      $.ajax({
        url:"<?php echo URL::to('user/cliets_list_for_project_tracking'); ?>",
        type:"post",
        data:{project_id:project_id},
        success:function(result){
          $(".add_clients_to_project_overlay").modal("show");
          $("#add_clients_to_project_tbody").html(result);

          $('#project_tracking_expand').DataTable({
                autoWidth: false,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false,
                order: []
            });
        }
      })
    }
  }
  if($(e.target).hasClass('tracking_project_manager_btn'))
  {
    $(".tracking_project_manager_overlay").modal("show");
      $("#step1_div").show();
      $("#step2_div").hide();
      $("#step3_div").hide();
      $("#step4_div").hide();
  }
  if($(e.target).hasClass('add_tracking_project'))
  {
      $("#step1_div").hide();
      $("#step2_div").show();
      $("#step3_div").hide();
      $("#step4_div").hide();

      $("#project_name").val("");
      $("#creation_date").val("");
      $(".tracking_type_select").prop("checked",false);
  }
  if($(e.target).hasClass('next_add_project'))
  {
    if($("#add_tracking_project_form").valid())
    {
      $("#step1_div").hide();
      $("#step2_div").hide();
      $("#step3_div").show();
      $("#step4_div").hide();

      $(".next_add_project2").hide();
      $(".submit_add_project").show();
    }
  }
  if($(e.target).hasClass('next_add_project2'))
  {
    var checked_length = $(".tracking_type_select:checked").length;

    var project_name = $(".project_name").val();
    var project_type = $(".tracking_type_select:checked").val();
    var creation_date = $(".creation_date").val();
    var monthly_value = $(".monthly_value_select").val();

    if(checked_length  == 0){
      alert("Please select the tracking type.");
      return false;
    }
    else if(project_type == "5" && monthly_value == ""){
      alert("Please select the monthly value");
      return false;
    }
    else if(project_type == "7" && monthly_value == ""){
      alert("Please select the monthly value");
      return false;
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_computation_projects'); ?>",
        type:"post",
        data:{project_type:project_type},
        success:function(result){
          var html = $(".complex_project_div").eq(0).html();
          $(".complex_project_main_div").html('<div class="complex_project_div">'+html+'</div>');

          $(".select_complex_project").html(result);
          $(".select_computation").val("1");

          $("#step1_div").hide();
          $("#step2_div").hide();
          $("#step3_div").hide();
          $("#step4_div").show();

          $(".close_computation").show();
          $(".close_computation").eq(0).hide();
        }
      })
    }
  }
  if($(e.target).hasClass('add_project_complex')){
    var html = $(".complex_project_div").eq(0).html();
    $(".complex_project_main_div").append('<div class="complex_project_div">'+html+'</div>');
    $(".close_computation").show();
    $(".close_computation").eq(0).hide();
  }
  if($(e.target).hasClass('close_computation')){
    $(e.target).parents(".complex_project_div").detach();
    $(".close_computation").show();
    $(".close_computation").eq(0).hide();
  }
  if($(e.target).hasClass('back_to_project'))
  {
      $("#step1_div").hide();
      $("#step2_div").show();
      $("#step3_div").hide();
      $("#step4_div").hide();
  }
  if($(e.target).hasClass('back_to_project_construction'))
  {
      $("#step1_div").hide();
      $("#step2_div").hide();
      $("#step3_div").show();
      $("#step4_div").hide();
  }
  if($(e.target).hasClass('cancel_add_project'))
  {
      $("#step1_div").show();
      $("#step2_div").hide();
      $("#step3_div").hide();
      $("#step4_div").hide();
  }
  if($(e.target).hasClass('submit_add_project'))
  {
    if($(".tracking_type_select:checked").length > 0)
    {
      var project_name = $(".project_name").val();
      var project_type = $(".tracking_type_select:checked").val();
      var creation_date = $(".creation_date").val();
      var monthly_value = $(".monthly_value_select").val();
      if(project_type == "5"){
        if(monthly_value == ""){
          alert("Please select the monthly value");
          return false;
        }
      }
      $("#project_expand").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/create_project_tracking_project'); ?>",
        type:"post",
        data:{project_name:project_name,project_type:project_type,creation_date:creation_date,monthly_value:monthly_value},
        success:function(result)
        {
          $("#step1_div").show();
          $("#step2_div").hide();
          $("#step3_div").hide();
          $("#step4_div").hide();

          $(".tracking_project_manager_overlay").modal("hide");
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green;margin-top:15px;>Project Created Successfully.</p>",width:"30%",fixed:true});

          // if($("#projects_tbody").find(".project_list_tr").length > 0)
          // {
          //   $("#projects_tbody").append(result);
          // }
          // else{
          //   $("#projects_tbody").html(result);
          // }

          // $('#project_expand').DataTable({
          //     autoWidth: false,
          //     scrollX: false,
          //     fixedColumns: false,
          //     searching: false,
          //     paging: false,
          //     info: false,
          //     order: []
          // });
        }
      })
    }
    else{
      alert("Please select the tracking type.");
      return false;
    }
  }
  if($(e.target).hasClass('submit_add_project_construction'))
  {
    if($(".tracking_type_select:checked").length > 0)
    {
      $("body").addClass("loading");
      var project_name = $(".project_name").val();
      var project_type = $(".tracking_type_select:checked").val();
      var creation_date = $(".creation_date").val();
      var monthly_value = $(".monthly_value_select").val();

      var error = 0;
      var project_ids = '';
      $(".select_complex_project").each(function(index, value){
        var project_id = $(this).val();
        if(project_id != ""){
          if(project_ids == ''){
            project_ids = project_id;
          }
          else{
            project_ids = project_ids+','+project_id;
          }
        }
        else{
          error = 1;
        }
      });

      var computations = '';
      $(".select_computation").each(function(index, value){
        var computation = $(this).val();
        if(computation != ""){
          if(computations == ''){
            computations = computation;
          }
          else{
            computations = computations+','+computation;
          }
        }
        else{
          error = 2;
        }
      });

      var complex_value = $(".complex_value").val();
      if(complex_value == ""){
        error = 3;
      }

      setTimeout(function(){
        if(error == 0){
          $("#project_expand").dataTable().fnDestroy();
          $.ajax({
            url:"<?php echo URL::to('user/create_project_tracking_project_computation'); ?>",
            type:"post",
            data:{project_name:project_name,project_type:project_type,creation_date:creation_date,monthly_value:monthly_value,project_ids:project_ids,computations:computations,complex_value:complex_value},
            success:function(result)
            {

              $("#step1_div").show();
              $("#step2_div").hide();
              $("#step3_div").hide();
              $("#step4_div").hide();

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Project Created Successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});

              if($("#projects_tbody").find(".project_list_tr").length > 0)
              {
                $("#projects_tbody").append(result);
              }
              else{
                $("#projects_tbody").html(result);
              }

              $('#project_expand').DataTable({
                  autoWidth: false,
                  scrollX: false,
                  fixedColumns: false,
                  searching: false,
                  paging: false,
                  info: false,
                  order: []
              });

              $("body").removeClass("loading");
            }
          })
        }
        else{
          $("body").removeClass("loading");
          if(error == 1){
            alert("Please select the project for complex project");
          }
          else if(error == 2){
            alert("Please select the computation for complex project");
          }
          else{
            alert("Please enter the value for complex project");
          }

          return false;
        }
      },3000);
    }
    else{
      alert("Please select the tracking type.");
      return false;
    }
  }
  if($(e.target).hasClass('tracking_type_select')){
    var description= $(e.target).attr("data-element");

    $("#type_description_select").html("Note: "+description);

    var id = $(e.target).val();
    if(id == "5"){
      $(".monthly_value_div").show();
      $(".next_add_project2").hide();
      $(".submit_add_project").show();
    }
    else if(id == "6"){
      $(".monthly_value_div").hide();
      $(".next_add_project2").show();
      $(".submit_add_project").hide();
    }
    else if(id == "7"){
      $(".monthly_value_div").show();
      $(".next_add_project2").show();
      $(".submit_add_project").hide();
    }
    else{
      $(".monthly_value_div").hide();
      $(".next_add_project2").hide();
      $(".submit_add_project").show();
    }
  }
  if($(e.target).hasClass('load_tracking_project'))
  {
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();

    if(project_id != ""){
      if(project_type < 6){
        $(".show_computation_icon").hide();
        $(".project_details").show();
        $("body").addClass("loading_apply");
        setTimeout(function(){
          $.ajax({
            url:"<?php echo URL::to('user/load_project_tracking_clients'); ?>",
            type:"post",
            data:{project_id:project_id},
            success:function(result){
              $(".project_tracking_clients_div").html(result);
              $("body").removeClass("loading_apply");              
              afterajaxrequest();
              copyPasteTableData();
            }
          })
        },1000);
      }
      else{
        $(".show_computation_icon").show();
        $(".alert_box").modal("show");
      }
    }
    else{
      alert("Please search and select the project");
      return false;
    }
  }
  if($(e.target).hasClass('no_complex')){
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();

    $(".project_details").show();
    $("body").addClass("loading_apply");
    setTimeout(function(result){
      $.ajax({
        url:"<?php echo URL::to('user/load_project_tracking_clients_complex'); ?>",
        type:"post",
        data:{project_id:project_id,status:'no'},
        success:function(result){
          $(".alert_box").modal("hide");
          $(".project_tracking_clients_div").html(result);
          $("body").removeClass("loading_apply");
          afterajaxrequest();
          copyPasteTableData();
        }
      })
    },1000);
    
  }
  if($(e.target).hasClass('yes_complex')){
    var project_id = $("#project_search_hidden_tracking").val();
    var project_type = $(".project_type").val();

    $(".project_details").show();
    $("body").addClass("loading_apply");
    setTimeout(function(){
      $.ajax({
        url:"<?php echo URL::to('user/load_project_tracking_clients_complex'); ?>",
        type:"post",
        data:{project_id:project_id,status:'yes'},
        success:function(result){
          $(".alert_box").modal("hide");
          $(".project_tracking_clients_div").html(result);
          $("body").removeClass("loading_apply");
          afterajaxrequest();
          copyPasteTableData();
        }
      });
    },1000);
  }
  if($(e.target).hasClass('refresh_project_analysis')){
    var project_id = $(e.target).attr("data-element");
    var client_id = $(e.target).attr("data-client");

    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/update_complex_project_client_dependency'); ?>",
      type:"post",
      data:{project_id:project_id,client_id:client_id},
      dataType:"json",
      success:function(result){
        $(".complex_analysis_project_overlay").modal("hide");
        var project_client_id = result['project_client_id'];
        if(result['project_type'] == "6"){
          $(".project_value_"+project_client_id).val(result['value']);
        }
        else{
          $(".project_value_"+project_client_id).eq(0).val(result['month_1']);
          $(".project_value_"+project_client_id).eq(1).val(result['month_2']);
          $(".project_value_"+project_client_id).eq(2).val(result['month_3']);
          $(".project_value_"+project_client_id).eq(3).val(result['month_4']);
          $(".project_value_"+project_client_id).eq(4).val(result['month_5']);
          $(".project_value_"+project_client_id).eq(5).val(result['month_6']);
          $(".project_value_"+project_client_id).eq(6).val(result['month_7']);
          $(".project_value_"+project_client_id).eq(7).val(result['month_8']);
          $(".project_value_"+project_client_id).eq(8).val(result['month_9']);
          $(".project_value_"+project_client_id).eq(9).val(result['month_10']);
          $(".project_value_"+project_client_id).eq(10).val(result['month_11']);
          $(".project_value_"+project_client_id).eq(11).val(result['month_12']);
          $(".project_value_"+project_client_id).eq(12).val(result['month_13']);
          $(".project_value_"+project_client_id).eq(13).val(result['month_14']);
        }
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('project_status'))
  {
    var id = $(e.target).attr("data-element");
    if($(e.target).is(":checked")) {
      var status = 1;
    }
    else{
      var status = 0;
    }

    $.ajax({
        url:"<?php echo URL::to('user/save_tracking_project_status'); ?>",
        type:"post",
        data:{id:id,status:status},
        success:function(result){
          
        }
    })
  }
  if($(e.target).hasClass('active_client_list_pms'))
  {
    var client_id=$("#client_search_hidden_infile").val();
    if(client_id!=''){
      $.ajax({
        url:"<?php echo URL::to('user/add_to_active_client_list'); ?>",
        type:"post",
        data:{'client_id':client_id},
        success:function(result)
        {
          if(result=="0"){
            alert("Details Already Existed");
          }
          else{
            $("#success_msg_active_list").html(result);
          }
        }
      })
    }
    else{
      alert('Please Select Client');
    }  
  }
  if($(e.target).hasClass('print_invoice_btn'))
  {
    $(".print_invoice_overlay").modal("show");
  }
  if($(e.target).hasClass("print_invoice_with_background"))
  {
    var style = '<style>@media print {* {-webkit-print-color-adjust: exact !important;color-adjust: exact !important;}.right{text-align: right;}.right_start{text-align: right;}.header_info_title{width:75px;margin-bottom: 0px !important;}.header_info_to_title{width:30px;margin-bottom: 0px !important;}.footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;}}</style>';
    $(".invoice_display_h3").hide();
    var divToPrint=document.getElementById('invoice_display_main_div');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+' '+style+'</body></html>');
    newWin.document.close();
    //newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }
    setTimeout(function(){newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }},1000);
  }
  if($(e.target).hasClass("print_invoice_with_no_background"))
  {
    var style = '<style>@media print {* {-webkit-print-color-adjust: exact !important;color-adjust: exact !important;}.right{text-align: right;}.right_start{text-align: right;}.header_info_title{width:75px;margin-bottom: 0px !important;}.header_info_to_title{width:30px;margin-bottom: 0px !important;}.footer_info_title{margin-bottom: 0px !important;height:35px !important;font-weight: 700 !important;} .display_letterpad_modal{background:#fff !important}}</style>';
    $(".invoice_display_h3").hide();
    var divToPrint=document.getElementById('invoice_display_main_div');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+' '+style+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.onfocus=function(){ newWin.close(); $(".invoice_display_h3").show(); }},1000);
  }
  if($(e.target).hasClass("print_invoice_pdf_with_no_background"))
  {
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"1"},
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass("print_invoice_pdf_with_background"))
  {
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"2"},
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass("email_invoice"))
  {
    if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
    var htmlcontent = $(".display_letterpad_modal").html();
    var inv_no = $("#hidden_invoice_no").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no,type:"2"},
      type:"post",
      success: function(result)
      {
         CKEDITOR.replace('editor',
         {
          height: '150px',
         });
          $(".email_invoice_overlay").modal("show");
          $(".subject_unsent").val("Invoice Detail for Invoice Number: "+inv_no);
          $(".pdf_name").html(result);
      }
    });
  }
  if($(e.target).hasClass('email_printed_invoice'))
  {
    var from = $("#from_user").val();
    var to = $("#to_user").val();
    var cc = $(".cc_unsent").val();
    var subject = $(".subject_unsent").val();
    var content = CKEDITOR.instances['editor'].getData();
    var attachment = $(".pdf_name").html();
    var client_id = $("#client_search_hidden_infile").val();

    if(from == "") { alert("Please select the From User to send the email."); return false; }
    else if(to == "") { alert("Please enter the To User to send the email."); return false; }
    else if(cc == "") { alert("Please enter the CC to send the email."); return false; }
    else if(subject == "") { alert("Please enter the Subject to send the email."); return false; }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/email_invoice_submit'); ?>",
        type:"post",
        data:{from:from,to:to,subject:subject,cc:cc,content:content,attachment:attachment,client_id:client_id},
        success:function(result)
        {
          $(".email_invoice_overlay").modal("hide");
          $(".print_invoice_overlay").modal("hide");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Mail Sent Successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
        }
      })
    }
  }
  if($(e.target).hasClass('save_build_invoice_settings'))
  {
    var offset_lines = $(".offset_lines").val();
    var plus_invoice_heading = $(".plus_invoice_heading").val();
    var minus_invoice_heading = $(".minus_invoice_heading").val();
    var suboffset_lines = $(".suboffset_lines").val();
    var iban_field = $(".iban_field").val();
    var bic_field = $(".bic_field").val();
    var first_invoice_number = $(".first_invoice_number").val();
    var left_margin = $(".left_margin").val();
    var top_margin = $(".top_margin").val();

    var inv_to_text = $(".inv_to_text").val();
    var inv_date_location_text = $(".inv_date_location_text").val();
    var inv_iban_text = $(".inv_iban_text").val();
    var inv_bic_text = $(".inv_bic_text").val();
    var inv_no_text = $(".inv_no_text").val();
    var client_id_text = $(".client_id_text").val();
    var net_text = $(".net_text").val();
    var vat_text = $(".vat_text").val();
    var gross_text = $(".gross_text").val();

    var inv_to_offset = $(".inv_to_offset").val();
    var client_id_offset = $(".client_id_offset").val();
    var inv_no_offset = $(".inv_no_offset").val();
    var inv_date_location_offset = $(".inv_date_location_offset").val();
    var inv_iban_offset = $(".inv_iban_offset").val();
    var inv_bic_offset = $(".inv_bic_offset").val();
    var net_offset = $(".net_offset").val();
    var vat_offset = $(".vat_offset").val();
    var gross_offset = $(".gross_offset").val();

    var inv_to_left_offset = $(".inv_to_left_offset").val();
    var client_id_left_offset = $(".client_id_left_offset").val();
    var inv_no_left_offset = $(".inv_no_left_offset").val();
    var inv_date_location_left_offset = $(".inv_date_location_left_offset").val();
    var inv_iban_left_offset = $(".inv_iban_left_offset").val();
    var inv_bic_left_offset = $(".inv_bic_left_offset").val();
    var net_left_offset = $(".net_left_offset").val();
    var vat_left_offset = $(".vat_left_offset").val();
    var gross_left_offset = $(".gross_left_offset").val();

    if(offset_lines == "") { alert("Please enter the offset_lines"); return false; }
    if(plus_invoice_heading == "") { alert("Please enter the plus_invoice_heading"); return false; }
    if(minus_invoice_heading == "") { alert("Please enter the minus_invoice_heading"); return false; }
    if(suboffset_lines == "") { alert("Please enter the suboffset_lines"); return false; }
    if(iban_field == "") { alert("Please enter the iban_field"); return false; }
    if(bic_field == "") { alert("Please enter the bic_field"); return false; }
    if(first_invoice_number == "") { alert("Please enter the first_invoice_number"); return false; }
    if(left_margin == "") { alert("Please enter the left_margin"); return false; }
    if(top_margin == "") { alert("Please enter the top_margin"); return false; }
    if(inv_to_text == "") { alert("Please enter the inv_to_text"); return false; }
    if(inv_date_location_text == "") { alert("Please enter the inv_date_location_text"); return false; }
    if(inv_iban_text == "") { alert("Please enter the inv_iban_text"); return false; }
    if(inv_bic_text == "") { alert("Please enter the inv_bic_text"); return false; }
    if(inv_no_text == "") { alert("Please enter the inv_no_text"); return false; }
    if(client_id_text == "") { alert("Please enter the client_id_text"); return false; }
    if(net_text == "") { alert("Please enter the net_text"); return false; }
    if(vat_text == "") { alert("Please enter the vat_text"); return false; }
    if(gross_text == "") { alert("Please enter the gross_text"); return false; }
    if(inv_to_offset == "") { alert("Please enter the inv_to_offset"); return false; }
    if(client_id_offset == "") { alert("Please enter the client_id_offset"); return false; }
    if(inv_no_offset == "") { alert("Please enter the inv_no_offset"); return false; }
    if(inv_date_location_offset == "") { alert("Please enter the inv_date_location_offset"); return false; }
    if(inv_iban_offset == "") { alert("Please enter the inv_iban_offset"); return false; }
    if(inv_bic_offset == "") { alert("Please enter the inv_bic_offset"); return false; }
    if(net_offset == "") { alert("Please enter the net_offset"); return false; }
    if(vat_offset == "") { alert("Please enter the vat_offset"); return false; }
    if(gross_offset == "") { alert("Please enter the gross_offset"); return false; }
    if(inv_to_left_offset == "") { alert("Please enter the inv_to_left_offset"); return false; }
    if(client_id_left_offset == "") { alert("Please enter the client_id_left_offset"); return false; }
    if(inv_no_left_offset == "") { alert("Please enter the inv_no_left_offset"); return false; }
    if(inv_date_location_left_offset == "") { alert("Please enter the inv_date_location_left_offset"); return false; }
    if(inv_iban_left_offset == "") { alert("Please enter the inv_iban_left_offset"); return false; }
    if(inv_bic_left_offset == "") { alert("Please enter the inv_bic_left_offset"); return false; }
    if(net_left_offset == "") { alert("Please enter the net_left_offset"); return false; }
    if(vat_left_offset == "") { alert("Please enter the vat_left_offset"); return false; }
    if(gross_left_offset == "") { alert("Please enter the gross_left_offset"); return false; }

    if(parseInt(suboffset_lines) <= parseInt(offset_lines)) { alert("The Number of Sub Offset Lines should be Greater than the Offset Lines"); return false; }
    if(parseInt(inv_to_offset) >= parseInt(offset_lines)) { alert("The Invoice To Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(client_id_offset) >= parseInt(offset_lines)) { alert("The Client ID Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_no_offset) >= parseInt(offset_lines)) { alert("The Invoice Number Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_date_location_offset) >= parseInt(offset_lines)) { alert("The Date Location Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_iban_offset) >= parseInt(offset_lines)) { alert("The IBAN Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_bic_offset) >= parseInt(offset_lines)) { alert("The BIC Offset should be Lesser than the Offset Lines"); return false; }

    var datavalues = [];

    datavalues.push({offset_lines: $(".offset_lines").val() });
    datavalues.push({plus_invoice_heading: $(".plus_invoice_heading").val() });
    datavalues.push({minus_invoice_heading: $(".minus_invoice_heading").val() });
    datavalues.push({suboffset_lines: $(".suboffset_lines").val() });
    datavalues.push({iban_field: $(".iban_field").val() });
    datavalues.push({bic_field: $(".bic_field").val() });
    datavalues.push({first_invoice_number: $(".first_invoice_number").val() });
    datavalues.push({left_margin: $(".left_margin").val() });
    datavalues.push({top_margin: $(".top_margin").val() });
    datavalues.push({inv_to_text: $(".inv_to_text").val() });
    datavalues.push({inv_date_location_text: $(".inv_date_location_text").val() });
    datavalues.push({inv_iban_text: $(".inv_iban_text").val() });
    datavalues.push({inv_bic_text: $(".inv_bic_text").val() });
    datavalues.push({inv_no_text: $(".inv_no_text").val() });
    datavalues.push({client_id_text: $(".client_id_text").val() });
    datavalues.push({net_text: $(".net_text").val() });
    datavalues.push({vat_text: $(".vat_text").val() });
    datavalues.push({gross_text: $(".gross_text").val() });
    datavalues.push({inv_to_offset: $(".inv_to_offset").val() });
    datavalues.push({client_id_offset: $(".client_id_offset").val() });
    datavalues.push({inv_no_offset: $(".inv_no_offset").val() });
    datavalues.push({inv_date_location_offset: $(".inv_date_location_offset").val() });
    datavalues.push({inv_iban_offset: $(".inv_iban_offset").val() });
    datavalues.push({inv_bic_offset: $(".inv_bic_offset").val() });
    datavalues.push({net_offset: $(".net_offset").val() });
    datavalues.push({vat_offset: $(".vat_offset").val() });
    datavalues.push({gross_offset: $(".gross_offset").val() });
    datavalues.push({inv_to_left_offset: $(".inv_to_left_offset").val() });
    datavalues.push({client_id_left_offset: $(".client_id_left_offset").val() });
    datavalues.push({inv_no_left_offset: $(".inv_no_left_offset").val() });
    datavalues.push({inv_date_location_left_offset: $(".inv_date_location_left_offset").val() });
    datavalues.push({inv_iban_left_offset: $(".inv_iban_left_offset").val() });
    datavalues.push({inv_bic_left_offset: $(".inv_bic_left_offset").val() });
    datavalues.push({net_left_offset: $(".net_left_offset").val() });
    datavalues.push({vat_left_offset: $(".vat_left_offset").val() });
    datavalues.push({gross_left_offset: $(".gross_left_offset").val() });

    $.ajax({
        type: "POST",
        url: "<?php echo URL::to('user/update_build_invoice_setings'); ?>",
        data: {datas:JSON.stringify(datavalues)},
        success: function(result){
          $(".build_invoice_settings_overlay").modal("hide");
          var countval = $(".highlight_inv").length;
          if(countval > 0){
            $(".highlight_inv").eq(0).trigger("click");
          }
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Settings has been updated successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
        }
    });
  }
  if($(e.target).hasClass('build_invoice_settings'))
  {
    $(".build_invoice_settings_overlay").modal("show");
  }
  if($(e.target).hasClass('edit_invoice_lines'))
  {
    $("#invoice_lines_tbody").hide();
    $("#edit_invoice_lines_tbody").show();
    $(e.target).hide();
    $("#save_editted_invoice_lines").show();
  }
  if($(e.target).hasClass('expand_invoice_lines'))
  {
    var inv_no = $("#hidden_invoice_no").val();
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/show_invoice_lines_for_invoice') ?>",
      type:"post",
      data:{inv_no:inv_no},
      dataType:"json",
      success:function(result){
        $("#invoice_lines_tbody").html(result['invoice_lines']);
        $("#edit_invoice_lines_tbody").html(result['edit_invoice_lines']);

        $("#edit_invoice_tbody").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($("#edit_invoice_tbody").find("tr").eq(0).hasClass('empty_line_tr')){
          $("#edit_invoice_tbody").find("tr").eq(0).detach();
        }
        if($("#edit_invoice_tbody").find("tr").last().hasClass('empty_line_tr')){
          $("#edit_invoice_tbody").find("tr").last().detach();
        }

        $("#invoice_lines_tbody").show();
        $("#edit_invoice_lines_tbody").hide();
        $("#save_editted_invoice_lines").hide();

        if(inv_no != ""){
          $(".edit_invoice_lines").show();
        }
        else{
          $(".edit_invoice_lines").hide();
        }

        $(".invoice_lines_overlay").modal("show");
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('save_editted_invoice_lines'))
  {
    var inv_no = $("#hidden_invoice_no").val();
    var datavalues = [];
    datavalues.push({f_row1: $("#f_row1").val() });
    datavalues.push({g_row2: $("#g_row2").val() });
    datavalues.push({h_row3: $("#h_row3").val() });
    datavalues.push({i_row4: $("#i_row4").val() });
    datavalues.push({j_row5: $("#j_row5").val() });
    datavalues.push({k_row6: $("#k_row6").val() });
    datavalues.push({l_row7: $("#l_row7").val() });
    datavalues.push({m_row8: $("#m_row8").val() });
    datavalues.push({n_row9: $("#n_row9").val() });
    datavalues.push({o_row10: $("#o_row10").val() });
    datavalues.push({p_row11: $("#p_row11").val() });
    datavalues.push({q_row12: $("#q_row12").val() });
    datavalues.push({r_row13: $("#r_row13").val() });
    datavalues.push({s_row14: $("#s_row14").val() });
    datavalues.push({t_row15: $("#t_row15").val() });
    datavalues.push({u_row16: $("#u_row16").val() });
    datavalues.push({v_row17: $("#v_row17").val() });
    datavalues.push({w_row18: $("#w_row18").val() });
    datavalues.push({x_row19: $("#x_row19").val() });
    datavalues.push({y_row20: $("#y_row20").val() });

    datavalues.push({z_row1: $("#z_row1").val() });
    datavalues.push({aa_row2: $("#aa_row2").val() });
    datavalues.push({ab_row3: $("#ab_row3").val() });
    datavalues.push({ac_row4: $("#ac_row4").val() });
    datavalues.push({ad_row5: $("#ad_row5").val() });
    datavalues.push({ae_row6: $("#ae_row6").val() });
    datavalues.push({af_row7: $("#af_row7").val() });
    datavalues.push({ag_row8: $("#ag_row8").val() });
    datavalues.push({ah_row9: $("#ah_row9").val() });
    datavalues.push({ai_row10: $("#ai_row10").val() });
    datavalues.push({aj_row11: $("#aj_row11").val() });
    datavalues.push({ak_row12: $("#ak_row12").val() });
    datavalues.push({al_row13: $("#al_row13").val() });
    datavalues.push({am_row14: $("#am_row14").val() });
    datavalues.push({an_row15: $("#an_row15").val() });
    datavalues.push({ao_row16: $("#ao_row16").val() });
    datavalues.push({ap_row17: $("#ap_row17").val() });
    datavalues.push({aq_row18: $("#aq_row18").val() });
    datavalues.push({ar_row19: $("#ar_row19").val() });
    datavalues.push({as_row20: $("#as_row20").val() });

    datavalues.push({at_row1: $("#at_row1").val() });
    datavalues.push({au_row2: $("#au_row2").val() });
    datavalues.push({av_row3: $("#av_row3").val() });
    datavalues.push({aw_row4: $("#aw_row4").val() });
    datavalues.push({ax_row5: $("#ax_row5").val() });
    datavalues.push({ay_row6: $("#ay_row6").val() });
    datavalues.push({az_row7: $("#az_row7").val() });
    datavalues.push({ba_row8: $("#ba_row8").val() });
    datavalues.push({bb_row9: $("#bb_row9").val() });
    datavalues.push({bc_row10: $("#bc_row10").val() });
    datavalues.push({bd_row11: $("#bd_row11").val() });
    datavalues.push({be_row12: $("#be_row12").val() });
    datavalues.push({bf_row13: $("#bf_row13").val() });
    datavalues.push({bg_row14: $("#bg_row14").val() });
    datavalues.push({bh_row15: $("#bh_row15").val() });
    datavalues.push({bi_row16: $("#bi_row16").val() });
    datavalues.push({bj_row17: $("#bj_row17").val() });
    datavalues.push({bk_row18: $("#bk_row18").val() });
    datavalues.push({bl_row19: $("#bl_row19").val() });
    datavalues.push({bm_row20: $("#bm_row20").val() });

    datavalues.push({bn_row1: $("#bn_row1").val() });
    datavalues.push({bo_row2: $("#bo_row2").val() });
    datavalues.push({bp_row3: $("#bp_row3").val() });
    datavalues.push({bq_row4: $("#bq_row4").val() });
    datavalues.push({br_row5: $("#br_row5").val() });
    datavalues.push({bs_row6: $("#bs_row6").val() });
    datavalues.push({bt_row7: $("#bt_row7").val() });
    datavalues.push({bu_row8: $("#bu_row8").val() });
    datavalues.push({bv_row9: $("#bv_row9").val() });
    datavalues.push({bw_row10: $("#bw_row10").val() });
    datavalues.push({bx_row11: $("#bx_row11").val() });
    datavalues.push({by_row12: $("#by_row12").val() });
    datavalues.push({bz_row13: $("#bz_row13").val() });
    datavalues.push({ca_row14: $("#ca_row14").val() });
    datavalues.push({cb_row15: $("#cb_row15").val() });
    datavalues.push({cc_row16: $("#cc_row16").val() });
    datavalues.push({cd_row17: $("#cd_row17").val() });
    datavalues.push({ce_row18: $("#ce_row18").val() });
    datavalues.push({cf_row19: $("#cf_row19").val() });
    datavalues.push({cg_row20: $("#cg_row20").val() });

    datavalues.push({inv_net: $("#inv_net").val() });
    datavalues.push({vat_value: $("#vat_value").val() });
    datavalues.push({gross: $("#gross").val() });

    // console.log(JSON.parse(JSON.stringify(datavalues)));
    // return false;
    $.ajax({
        type: "POST",
        url: "<?php echo URL::to('user/update_invoice_lines_for_invoice'); ?>",
        data: {datas:JSON.stringify(datavalues),inv_no:inv_no},
        success: function(result){
          $(".invoice_lines_overlay").modal("hide");
          $(".highlight_inv").eq(0).trigger("click");
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Invoice Lines for the Invoice '+inv_no+' has been updated successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>'});
        }
    });

  }
  if($(e.target).hasClass('ok_proceed'))
  {
    $.colorbox.close();
  }
  if($(e.target).hasClass('show_grid_lines'))
  {
    if($(e.target).is(":checked"))
    {
      $(".display_table_expand").find("td").css("border","1px solid #d0d0d0");
    }
    else{
      $(".display_table_expand").find("td").css("border","0px");
    }
  }
  if($(e.target).hasClass('invoice_td'))
  {
    $("#issued_invoice_tbody").find(".invoice_td").removeClass("highlight_inv");
    var inv_no = $(e.target).attr("data-element");
    $(e.target).parents("tr").find(".invoice_td").addClass("highlight_inv");
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/build_issued_invoice_lines'); ?>",
      type:"post",
      data:{inv_no:inv_no},
      dataType:"json",
      success:function(result){
        $("#selected_invoice_number").html(inv_no);
        $(".selected_invoice_div").html(result['invoice_lines']);
        $("#hidden_invoice_no").val(inv_no);
        $("#selected_invoice_tbody").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($("#selected_invoice_tbody").find("tr").eq(0).hasClass('empty_line_tr')){
          $("#selected_invoice_tbody").find("tr").eq(0).detach();
        }
        if($("#selected_invoice_tbody").find("tr").last().hasClass('empty_line_tr')){
          $("#selected_invoice_tbody").find("tr").last().detach();
        }

        $(".display_letterpad_modal").html(result['display_output']);

        $(".display_table_expand").find(".empty_line_tr").each(function(index, value){
          if($(this).next("tr").hasClass('empty_line_tr') || $(this).prev("tr").hasClass('empty_line_tr'))
          {
            $(this).detach();
          }
        });
        if($(".display_table_expand").find("tr").eq(0).hasClass('empty_line_tr')){
          $(".display_table_expand").find("tr").eq(0).detach();
        }
        if($(".display_table_expand").find("tr").last().hasClass('empty_line_tr')){
          $(".display_table_expand").find("tr").last().detach();
        }

        $(".selected_invoice_main_div").show();
        $(".invoice_display_main_div").show();
        $("#show_grid_lines").prop("checked",false);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('load_client_account_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_client_account_review_listing'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".opening_bal_h5").show();
          $(".client_account_table").show();
          $(".export_client_account_details").show();
          $('#opening_bal_client').html(result['opening_balance']);
          $("#client_account_tbody").html(result['output']);
        }
      });
    }
  }
	if($(e.target).hasClass('saveas_pdf'))
	{
	    var htmlcontent = $("#letterpad_modal").html();
	    var inv_no = $("#invoice_number_pdf").val();
	    $.ajax({
	      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
	      data:{htmlcontent:htmlcontent,inv_no:inv_no},
	      type:"post",
	      success: function(result)
	      {
	        SaveToDisk("<?php echo URL::to('public/papers'); ?>/"+result,result);
	      }
	    });
	}
	if($(e.target).hasClass('print_pdf'))
	{
	    var htmlcontent = $("#letterpad_modal").html();
	    $.ajax({
	      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
	      data:{htmlcontent:htmlcontent},
	      type:"post",
	      success: function(result)
	      {
	        $("#pdfDocument").attr("src","<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
	        printPdf("<?php echo URL::to('public/papers/Invoice Report.pdf'); ?>");
	      }
	    });
	}
	if($(e.target).hasClass('invoice_date_option'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		if(client_id == "")
		{
			alert("Please select the Client and click on the Load Button");
			$(".invoice_date_option").prop("checked",false);
		}
		else{
			var value = $(e.target).val();
			if(value == "1")
			{
				$(".invoice_year_div").show();
				$(".custom_date_div").hide();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
				$(".email_selected_pdf").hide();
			}
			else if(value == "2")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").hide();
				$("body").addClass("loading");
			    setTimeout(function(){ 
			        var client_id = $("#client_search_hidden_infile").val();
			          $(".copy_clients").attr("data-element", client_id);
			          $(".print_clients").attr("data-element", client_id);
			          $(".download_clients").attr("data-element", client_id);
			          $.ajax({
			              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
			              data:{client_id:client_id,type:"2"},
			              dataType:"json",
			              type:"post",
			              success:function(result){
			                $(".invoice_table_div").html(result['invoiceoutput']);
			                $(".invoice_table_div").show();
			                $(".download_selected_pdf").show();
							$(".email_selected_pdf").show();
              $(".selected_export_csv").show();
			                $("body").removeClass("loading");
			                $('#invoice_expand').DataTable({
			                    autoWidth: true,
			                    scrollX: false,
			                    fixedColumns: false,
			                    searching: false,
			                    paging: false,
			                    info: false,
			                    ordering: false
			                });
			                
			          }
			        });
			    }, 2000);
			}
			else if(value == "3")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").show();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
				$(".email_selected_pdf").hide();
			}
		}
	}
  if($(e.target).hasClass('receipt_date_option'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
      $(".receipt_date_option").prop("checked",false);
    }
    else{
      var value = $(e.target).val();
      if(value == "1")
      {
        $(".receipt_year_div").show();
        $(".custom_date_div_receipt").hide();
        $(".receipt_table_div").html("");
      }
      else if(value == "2")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").hide();
        $("body").addClass("loading");
          setTimeout(function(){ 
              var client_id = $("#client_search_hidden_infile").val();
                $.ajax({
                    url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                    data:{client_id:client_id,type:"2"},
                    dataType:"json",
                    type:"post",
                    success:function(result){
                      $(".receipt_table_div").html(result['receiptoutput']);
                      $(".receipt_table_div").show();
                      $("body").removeClass("loading");
                      $('#receipt_expand').DataTable({
                          autoWidth: true,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          ordering: false
                      });
                }
              });
          }, 2000);
      }
      else if(value == "3")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").show();
        $(".receipt_table_div").html("");
      }
    }
  }
	if($(e.target).hasClass('load_client_review'))
	{
		var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
    }
    else{
      $("body").addClass("loading");
      $("#invoice_build_expand").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/build_invoice_client_select'); ?>",
        type:"get",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".company_name").val(result['company']);
          $(".client_details_div").html(result['client_details']);
          $(".client_email").val(result['client_email']);
          $(".company_name").css("color","#000 !important");
          $(".issued_invoice_div").html(result['invoices']);

          var emails = '';
          if(result['client_email'] != ""){
            emails = result['client_email'];
          }
          if(result['client_email2'] != "")
          {
            if(emails == ''){
              emails = result['client_email2'];
            }
            else{
              emails = emails+', '+result['client_email2'];
            }
          }
          $("#to_user").val(emails)

          $('#invoice_build_expand').DataTable({
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false
          });

          $(".issued_invoice_main_div").show();
          $(".selected_invoice_main_div").hide();
          $(".invoice_display_main_div").hide();

          
          $("body").removeClass("loading");
        }
      })
    }
	}
})
</script>
@stop