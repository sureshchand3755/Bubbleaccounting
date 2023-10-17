@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('public/assets/css/easypayroll.css'); ?>">
<style>
#colorbox{
  z-index:9999999;
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
.tableqba{
  border: 1px solid #ddd;
}
.data_allocations_table > thead > tr > th{
  padding:10px;
}
.tableqba > thead > tr > th, .tableqba > tbody > tr > td{
  border:1px solid #ddd;
}
.qba_imported_sub_div{
  padding:0px;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
  margin-top:10%;
}
.tableqba > tbody > tr:first-child td {
    background: #fff;
    font-weight:700;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    vertical-align: middle;
}

.tableqba_main {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.tableqba_main tbody tr:first-child td {
  background: white;
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  z-index:999;
}
body{
  margin-bottom: 0px !important;
}
</style>
<div class="modal fade qba_verification_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document" style="width:85%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">QBA Verification</h4>
      </div>
      <div class="modal-body">
          <div class="col-md-12 verification_div">
          </div>
          <div class="col-md-12">
            <input type="hidden" id="hidden_total_column_count" name="hidden_total_column_count" value="">
            <p><input type="checkbox" name="qba_header_row" id="qba_header_row" value="1"> <label for="qba_header_row">Does your Source file have a header row</label></p>
            <p><input type="checkbox" name="qba_secondary_row" id="qba_secondary_row" value="1"> <label for="qba_secondary_row">Does your Source file have a secondary blank row</label></p>
          </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          <input type="button" class="ok_qba_verification common_black_button" value="Ok" style="float:right">
      </div>
    </div>
  </div>
</div>

<div class="modal fade qba_data_allocations_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document" style="width:85%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Data Allocations</h4>
      </div>
      <div class="modal-body" style="max-height: 800px;overflow-y: scroll">
        <div class="col-md-12 qba_allocations_div">

        </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          <input type="button" class="qba_allocations_btn common_black_button" value="Submit" style="float:right">
      </div>
    </div>
  </div>
</div>
<div class="modal fade qba_data_validations_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Data Validations</h4>
      </div>
      <div class="modal-body" style="max-height: 800px;overflow-y: scroll">
        <div class="col-md-12 qba_validations_div">

        </div>
      </div>
      <div class="modal-footer" style="clear: both;">
          
      </div>
    </div>
  </div>
</div>
<div class="content_section">
    <div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title">Quick Bank Analysis</h4>
        <div class="col-md-12 padding_00">
            <div class="col-md-12 padding_00">
              <label style="margin-top:11px;font-size:18px;margin-top: -4px;font-weight:600">Source File: </label>
              <input type="button" class="common_black_button qba_settings_btn" id="qba_settings_btn" value="Settings" title="Not Available yet" style="float:right">
              <input type="button" class="common_black_button qba_data_allocations_btn" id="qba_data_allocations_btn" value="Data Allocations" style="float:right;display:none">
            </div>
            <div class="col-md-5 padding_00" style="margin-top: -7px;">
              <form action="<?php echo URL::to('user/qba_upload_file'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="qbaImageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              @csrf
</form>
            </div>
            <?php
                $output_header = '<div class="col-md-7 header_main_div">

                </div>';
                $get_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
                if(($get_details)) {
                  if($get_details->table_content != ''){
                    $header_row = $get_details->header_row;
                    $secondary_row = $get_details->secondary_row;
                    $full_array = json_decode($get_details->table_content);
                    $allocations = json_decode($get_details->allocations);
                    $header_color = 'color:#f00';
                    $secondary_color = 'color:#f00';
                    if($header_row == "1"){
                      $header_color = 'color:green';
                    }
                    if($secondary_row == "1"){
                      $secondary_color = 'color:green';
                    }
                    $output_header = '<div class="col-md-7 header_main_div">
                      <label style="font-size:18px;'.$header_color.';width:100%">* Source file has a header row</label>
                      <label style="font-size:18px;'.$secondary_color.';width:100%">* Second line of Source file is blank</label>
                    </div>';
                  }
                }
                echo $output_header;
            ?>
            <div class="col-md-12 qba_imported_div padding_00" style="margin-top:30px">
                <?php
                if(($get_details)) {
                  if($get_details->table_content != ''){
                    $output = '
                    <div class="col-md-12 padding_00">
                      <label style="margin-top:11px;font-size:18px">Source File Content:</label>
                      <a href="javascript:" class="common_black_button process_source_file" style="float:right">Process Source File</a>
                    </div>
                    <div class="col-md-12 qba_imported_sub_div" style="height:500px;max-height: 500px;overflow-y: scroll;">
                    <table class="table tableqba tableqba_main">';
                    if(($full_array)){
                      foreach($full_array as $keyc => $datacontent){
                        $output.='<tr>';
                        $countdata = count($datacontent);
                        
                        if(($datacontent)){
                          for($i = 0; $i <= $countdata - 1; $i++){
                            if(isset($datacontent[$i])) { $datai = $datacontent[$i]; }
                            else { $datai = ''; }
                            if($keyc == 0){
                              if(isset($allocations[$i])){
                                $datav = $datai.' / '.$allocations[$i];
                                $datav_color = 'color:green';
                              }else{
                                $datav = $datai;
                                $datav_color = 'color:#000';
                              }

                              $output.='<td><a href="javascript:" title="'.$datav.'" style="'.$datav_color.'">'.$datai.'</a></td>';
                            }
                            else{
                              $output.='<td>'.$datai.'</td>';
                            }
                          }
                        }
                        $output.='</tr>';
                      }
                    }
                    else{
                      $output.='<tr>
                        <td colspan="4">No Data Found.</td>
                      </tr>';
                    }
                    $output.='</table></div>';

                    echo $output;
                  }
                }
                ?>
            </div>
        </div>
  </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div class="modal_load"></div>
</div>
<script>
$(window).load(function(){
  Dropzone.forElement("#qbaImageUpload").removeAllFiles(true);
  $(".dz-message").find("span").html("Click here to BROWSE the CSV file OR just drop the CSV file here to be Analysed.");
})
$(window).change(function(e) {
  if($(e.target).hasClass('default_debit_link_header')){
    var value = $(e.target).val();
    if(value != ""){
      if(value == "Debit/Credit Value"){
        $(".data_allocations_link_header").each(function(index,value){
          var value = $(this).val();
          if(value == "Debit/Credit Identifier"){
            $(".debit_static_text").hide();
            $(".debit_dynamic_text").show();
            $(".debit_dynamic_text").css("color","green");
            $(".debit_dynamic_text").html("Valid Link");
            return false;
          }
          else{
            $(".debit_static_text").hide();
            $(".debit_dynamic_text").show();
            $(".debit_dynamic_text").css("color","#f00");
            $(".debit_dynamic_text").html("Link Not Valid");
          }
        });
      }
      else{
        $(".debit_static_text").show();
        $(".debit_dynamic_text").hide();
      }
    }
    else{
      $(".debit_static_text").show();
        $(".debit_dynamic_text").hide();
    }
  }

  if($(e.target).hasClass('default_credit_link_header')){
    var value = $(e.target).val();
    if(value != ""){
      if(value == "Debit/Credit Value"){
        $(".data_allocations_link_header").each(function(index,value){
          var value = $(this).val();
          if(value == "Debit/Credit Identifier"){
            $(".credit_static_text").hide();
            $(".credit_dynamic_text").show();
            $(".credit_dynamic_text").css("color","green");
            $(".credit_dynamic_text").html("Valid Link");
            return false;
          }
          else{
            $(".credit_static_text").hide();
            $(".credit_dynamic_text").show();
            $(".credit_dynamic_text").css("color","#f00");
            $(".credit_dynamic_text").html("Link Not Valid");
          }
        });
      }
      else{
        $(".credit_static_text").show();
        $(".credit_dynamic_text").hide();
      }
    }
    else{
      $(".credit_static_text").show();
        $(".credit_dynamic_text").hide();
    }
  }

  if($(e.target).hasClass('data_allocations_link_header')){
    var arrayval = [];
    $(".data_allocations_link_header").each(function(index,value){
      var value = $(this).val();
      arrayval.push(value);
    });
    if(jQuery.inArray("Date",arrayval) != -1){
      $(".date_static_text").html("Valid");
      $(".date_static_text").css("color","green");
    }else{
      $(".date_static_text").html("No Date Specified");
      $(".date_static_text").css("color","#f00");
    }

    if(jQuery.inArray("Debit/Withdrawal Value",arrayval) != -1 || jQuery.inArray("Value Debit is Minus",arrayval) != -1 || jQuery.inArray("Value Debit is in Brackets",arrayval) != -1){
      $(".debit_static_text").html("Valid Link");
      $(".debit_static_text").css("color","green");
    }
    else{
      $(".debit_static_text").html("Link Not Valid");
      $(".debit_static_text").css("color","#f00");
    }

    if(jQuery.inArray("Credit/Lodgement Value",arrayval) != -1) {
      $(".credit_static_text").html("Valid Link");
      $(".credit_static_text").css("color","green");
    }
    else{
      $(".credit_static_text").html("Link Not Valid");
      $(".credit_static_text").css("color","#f00");
    }
    
    var valuee = $(e.target).val();

    if(valuee == "Descript Narrative 1" || valuee == "Descript Narrative 2" || valuee == "Descript Narrative 3" || valuee == "Descript Narrative 4" || valuee == "Descript Narrative 5" || valuee == "Descript Narrative 6"){
      $(e.target).parents("tr").find("td").eq(2).html("Valid");
      $(e.target).parents("tr").find("td").eq(2).css("color","green");
    }
    else{
      $(e.target).parents("tr").find("td").eq(2).html("");
      $(e.target).parents("tr").find("td").eq(2).css("color","#000");
    }
  }
});
function event_load()
{
  var contentlength = $(".qba_imported_sub_div").length;
  if(contentlength > 0){
    $(window).bind('beforeunload', function() {
      return 'You have unsaved changes which will not be saved.';
    });
  }
}
$(document).ready(function(){
  var ascending = false;
  event_load();
})
$(window).click(function(e) {
  var ascending = false;
  event_load();
  if($(e.target).hasClass('qba_link')){
    $(".qba_overlay").modal("show");
    Dropzone.forElement("#qbaImageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the CSV file OR just drop the CSV file here to be Analysed.");
    //$(".qba_imported_div").hide();
  }
  if($(e.target).hasClass('close_qba')){
    $.ajax({
      url:"<?php echo URL::to('user/empty_qba_allocations'); ?>",
      type:"post",
      success:function(result){
        $(".qba_imported_div").hide();
        $(".qba_imported_div").html("");
      }
    });
  }
  if($(e.target).hasClass('ok_qba_verification'))
  {
    var header_row = "0";
    if($('#qba_header_row').is(":checked")){
      var header_row = "1";
    }
    var secondary_row = "0";
    if($('#qba_secondary_row').is(":checked")){
      var secondary_row = "1";
    }
    var column_count = $("#hidden_total_column_count").val();
    $.ajax({
      url:"<?php echo URL::to('user/update_qba_settings'); ?>",
      type:"post",
      data:{header_row:header_row,secondary_row:secondary_row,column_count:column_count},
      dataType:"json",
      success:function(result){
        $(".qba_imported_div").show();
        $(".qba_verification_overlay").modal("hide");
        $(".qba_imported_div").html(result['output']);
        $(".header_main_div").html(result['header_output']);
        $(".qba_data_allocations_btn").show();
        var parse_column_count = parseInt(column_count);
        if(parse_column_count < 5){
          $(".qba_imported_sub_div").css("overflow-x","unset");
        }
        else{
          $(".qba_imported_sub_div").css("overflow-x","scroll");
        }
      }
    })
  }
  if($(e.target).hasClass('qba_data_allocations_btn')){
    $.ajax({
      url:"<?php echo URL::to('user/qba_data_alloations'); ?>",
      type:"post",
      success:function(result){
        $(".qba_data_allocations_overlay").modal("show");
        $(".qba_allocations_div").html(result);

        var countlength = $(".sample_loaded_table").find("tr").eq(0).find("td").length;
        if(countlength < 5){
          $(".sample_loaded_div").css("overflow-x","unset");
        }
        else{
          $(".sample_loaded_div").css("overflow-x","scroll");
        }
      }
    })
  }
  if($(e.target).hasClass('process_source_file')){
    $.ajax({
      url:"<?php echo URL::to('user/qba_data_validations'); ?>",
      type:"post",
      success:function(result){
        $(".qba_data_validations_overlay").modal("show");
        $(".qba_validations_div").html(result);
      }
    });
  }
  if($(e.target).hasClass('qba_allocations_btn')){
    var links = [];
    $(".data_link_header").each(function(index,value){
      var link_value = $(this).val();
      links.push(link_value);
    });
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/save_qba_data_links'); ?>",
      type:"post",
      data:{links:JSON.stringify(links)},
      cache: false,
      success:function(result)
      {
        $("body").removeClass("loading");
        $(".qba_data_allocations_overlay").modal("hide");
        $(".data_link_header").each(function(index,value){
          var valuee = $(this).val();
          if(valuee != ""){
            var htmlval = $(".qba_imported_sub_div").find("table").find("tr").eq(0).find("td").eq(index).text();

            $(".qba_imported_sub_div").find("table").find("tr").eq(0).find("td").eq(index).html('<a href="javascript:" title="'+htmlval+' / '+valuee+'" style="color:green">'+htmlval+'</a>');
          }
          else{
            var htmlval = $(".qba_imported_sub_div").find("table").find("tr").eq(0).find("td").eq(index).text();
            $(".qba_imported_sub_div").find("table").find("tr").eq(0).find("td").eq(index).html('<a href="javascript:" title="'+htmlval+'">'+htmlval+'</a>');
          }
        });
        
        $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Data Links Saved Successfully. </p>',fixed:true,width:"800px"});

      }
    })
  }
});
//$.ajaxSetup({async:false});
Dropzone.options.qbaImageUpload = {
    maxFiles: 1,
    acceptedFiles: ".csv",
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
            $("#hidden_total_column_count").val(obj.column_count);
            if(obj.column_count < 5){
              $(".verification_div").css("overflow-x","unset");
            }
            else{
              $(".verification_div").css("overflow-x","scroll");
            }
            $(".verification_div").html(obj.output);
            $(".qba_verification_overlay").modal("show");

            $("#qba_header_row").prop("checked",false);
            $("#qba_secondary_row").prop("checked",false);
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            Dropzone.forElement("#qbaImageUpload").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the CSV file OR just drop the CSV file here to be Analysed.");
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
    },
};
</script>
@stop