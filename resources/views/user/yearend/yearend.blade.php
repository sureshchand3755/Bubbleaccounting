@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('public/assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
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
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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

.report_div{
    position: absolute;
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
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
    #colorbox, #cboxWrapper { z-index:99999999999; }
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
<div class="modal fade setting_crypt_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;">
      <div class="modal-content">
        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">YearEnd Settings</h4>
          </div>
          <div class="modal-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                  <a class="nav-link active" id="yearenddocuments-tab" data-toggle="tab" href="#yearenddocumentstab" role="tab" aria-controls="yearenddocumentstab" aria-selected="false">Documents</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="yearendemail-tab" data-toggle="tab" href="#yearendemailtab" role="tab" aria-controls="yearendemailtab" aria-selected="false">Email Settings</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active in" id="yearenddocumentstab" role="tabpanel" aria-labelledby="yearenddocuments-tab">
                  <div class="col-lg-12 padding_00">
                    <div class="form-group" style="width:80%">
                      <div class="setting_drop" style="margin-top: 21px;"></div>
                      <div class="setting_submit" style="margin-top: 18px;"></div>
                    </div>  
                  </div> 
                </div>
                <div class="tab-pane fade" id="yearendemailtab" role="tabpanel" aria-labelledby="yearendemail-tab">
                    <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('user/update_yearend_settings'); ?>">
                      <div class="col-lg-12 padding_00">
                        <div>
                          <?php
                          if(Session::has('emailmessage')) { ?>
                              <p class="alert alert-info" style="width:90%"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                          <?php }
                          ?>
                        </div>
                        <?php
                        $yearend_settings = DB::table('yearend_settings')->where('practice_code', Session::get('user_practice_code'))->first();
                        $yearend_cc_email = '';
                        if($yearend_settings){
                          $yearend_cc_email = $yearend_settings->yearend_cc_email;
                        }
                        ?>
                          <div class="form-group">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                              <?php
                              if($yearend_settings->email_header_url == '') {
                                $default_image = DB::table("email_header_images")->first();
                                if($default_image->url == "") {
                                  $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                                }
                                else{
                                  $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                                }
                              }
                              else {
                                $image_url = URL::to($yearend_settings->email_header_url.'/'.$yearend_settings->email_header_filename);
                              }
                              ?>
                              <img src="<?php echo $image_url; ?>" class="yearend_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                              <input type="button" name="yearend_email_header_img_btn" class="common_black_button yearend_email_header_img_btn" value="Browse"> 
                              <h4>Yearend CC Email ID</h4>
                              <input id="validation-cc-email"
                                     class="form-control"
                                     placeholder="Enter Yearend CC Email ID"
                                     value="<?php echo $yearend_cc_email; ?>"
                                     name="yearend_cc_email"
                                     type="text"
                                     required>                                 
                          </div>
                      </div>
                      <div class="col-lg-12 padding_00">
                        <input type="submit" class="common_black_button" id="yearend_submit" value="Submit">
                      </div>
                      @csrf
                    </form>
                </div>
              </div>
          </div>
          <div class="modal-footer" style="clear:both">
              
          </div>
      </div>
  </div>
</div>
<div class="modal fade change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
      </div>
      <div class="modal-body">
          <form action="<?php echo URL::to('user/edit_yearend_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmail" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<style>
.page_title{color:#fff; text-shadow: 0px 1px 2px #000}
</style>




<div class="content_section">
  <div class="page_title" style="margin-bottom: 2px;text-shadow: none;">
          <div class="col-lg-3" style="padding: 0px;color:#000">
                YEAR END MANAGER          
            </div>
            <div class="col-lg-7 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-2 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right;position: absolute;top: 27px;right: 0;">
                  <li><a href="javascript:" class="fa fa-cog setting_class" style="font-size: 13px; font-weight: 500;" title="YearEnd Settings"></a></li>                
              </ul>
            </div>                        
  </div>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info" style="width:90%"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 


</div>

<div class="select_button">
        <ul>
          <?php
          $output='';
          if(($yearlist)){
            foreach ($yearlist as $year) {
              $output.='<li><a href="'.URL::to('user/yeadend_clients/'.base64_encode($year->year)).'">'.$year->year.'</a></li>';
            }
          }
          else{
            $output.='
            <div style="width:300px; height:auto; margin:0px auto;" class="add_modal">
            <form action="'.URL::to("user/year_first_create").'" method="post" class="add_new_form" id="form-validation">
              <div class="year_input_group">              
                <div class="form-group">
                  <div class="year_drop"></div>
                </div>
              </div>
              
            
            
                <input type="button" class="common_black_button crypt_button" value="Submit">
                <div class="button_submit"></div>         
          @csrf
</form>
          </div>

            ';
          }
          echo $output;
          ?>                        
        </ul>       
    </div>
<div class="page_title" style="margin-top: 20px; float: left;color:#000;text-shadow: none">
  YEAR END MANAGER – Liability Assessment


</div>
<div class="select_button">
    <ul>
      <?php
      $output2='';
      if(($yearlist)){
        foreach ($yearlist as $year) {
          $output2.='<li><a href="'.URL::to('user/yeadend_liability/'.base64_encode($year->year)).'">'.$year->year.'</a></li>';
        }
      }
      else{
        $output2='<div style="color:#fff">Year Empty</div>';
      }
      echo $output2;
      ?>
    </ul>
  </div>
    <!-- End  -->

<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">





<script>

$(window).click(function(e) {
if($(e.target).hasClass('yearend_email_header_img_btn')) {
  var r = confirm("Are you sure you want to change the Yearend Email Header Image?");
  if(r) {
    $(".change_header_image").modal("show");

    Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
  }
}
if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".year_input_group").hide();
    $(".year_drop").prop('required', false);
}
if($(e.target).hasClass('crypt_button')){
  $.ajax({
    url:"<?php echo URL::to('user/yearend_crypt_validdation')?>",
    type:"post",
    dataType:"json",
    data:{type:0},
    success:function(result){
      if(result['security'] == true){
        $(".year_input_group").show();
        $(".year_drop").html(result['drop']);
        $(".year_button").show();
        $(".year_class").prop('required', true);
        $(".button_submit").html(result['create_button']);
      }
      else{
        $(".year_input_group").hide();
        $(".year_button").hide();
      }

    }
  })
}
if($(e.target).hasClass('setting_class')){
    $.ajax({
      url:"<?php echo URL::to('user/yearend_crypt_validdation')?>",
      type:"post",
      dataType:"json",
      data:{type:1},
      success:function(result){
        if(result['security'] == true){       
          $('.setting_crypt_modal').modal("show");
          $(".setting_drop").show();
          $(".setting_drop").html(result['drop']);
          $(".setting_submit").html(result['create_button']);
        }
        else{
          $(".year_input_group").hide();
          $(".year_button").hide();
        }

      }
    })
}


if($(e.target).hasClass('setting_button')){
  var setting_type = $(".setting_type").val();
  if(setting_type == "" || typeof setting_type === "undefined")
  {
    alert("Please select type");
    return false;
  }
}



if($(e.target).hasClass('year_button')){
  var year_class = $(".year_class").val();

  if(year_class == "" || typeof year_class === "undefined")
  {
    alert("Please select year");
    return false;
  }
  else{
    var r = confirm("Warning, once you create this year no year prior to this can be created.  Do you wish to Proceed with Creating the year?");
    if (r == true) {      

    } else {
        return false;
    }
  }
}
});

$(window).change(function(e){
  if($(e.target).hasClass('setting_type')){
    var level = $(e.target).val();  
    if (level == 1) {     
      $(".setting_button").attr("href", "<?php echo URL::to('user/supplementary_manager')?>");
    }

    else if (level == 2) {     
      $(".setting_button").attr("href", "<?php echo URL::to('user/yearend_setting')?>");
    }
    else{
      $(".setting_button").attr("href", "#");
    }
  }
});
$(".add_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});

Dropzone.options.ImageUploadEmail = {
    maxFiles: 1,
    acceptedFiles: ".png",
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
            if(obj.error == 1) {
              $("body").removeClass("loading");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              $(".yearend_email_header_img").attr("src",obj.image);
              $("body").removeClass("loading");
              $(".change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Yearend Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            $(".yearend_email_header_img").attr("src",obj.image);
            $("body").removeClass("loading");
            $(".change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmail").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Yearend Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
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