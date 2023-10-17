@extends('userheader')
@section('content')

<script src="<?php echo URL::to('public/assets/plugins/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('public/assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
#table_administration_wrapper{ width:98%; margin-top: 25px; }
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
</style>

<div class="content_section" style="margin-bottom:200px">
  <div style="width:100%;position: fixed; background: #f5f5f5; z-index: 99; top: 83px;">
  <div class="page_title" style="z-index:999">
    <h4 class="col-lg-12 padding_00 new_main_title">Audit Trails</h4>
    <div class="col-lg-4 padding_00">
      <label class="col-md-3 padding_00" style="line-height: 35px; width: 65px">Filter By:</label>
            <div class="col-md-3">
                <select class="form-control filter_by" name="filter_by">
                  <option value="1">User</option>
                  <option value="2">Module</option>
                </select>
            </div>
            <label class="col-md-3 padding_00 filter_label" style="line-height: 35px; width: 110px">Select User:</label>
            <div class="col-md-5 filter_by_user_div">
                <select class="form-control filter_by_user" name="filter_by_user">
                  <option value="">Select User</option>
                  <?php
                    if(($userlist))
                    {
                      foreach($userlist as $user){
                        if($user->user_id == Session::get('userid')) { $selected = 'selected'; } else { $selected = ''; }
                        echo '<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.' '.$user->firstname.'</option>';
                      }
                    }
                  ?>
                </select>
            </div>
            <div class="col-md-5 filter_by_module_div" style="display:none">
                <select class="form-control filter_by_module" name="filter_by_module">
                  <option value="">Select Module</option>
                  <option value="1">Login Module</option>
                  <option value="2">Task Manager</option>
                  <option value="3">2Bill Manager</option>
                  <option value="4">CRO ARD</option>
                </select>
            </div>
    </div>
    <div class="col-lg-7 padding_00" style="text-align:center;font-size:20px; width: 1250px">
      

      <a href="javascript:" class="load_audit_trails common_black_button" style="float:left;">Load Audit Trails</a>
    </div>
  </div>
  </div>
    <div style="width: 100%; float: left;margin-top:170px; background: #fff">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
            <table class="table own_table_white" id="table_administration" style="width:100%; background: #fff">
                <thead>
                  <tr>
                    <th>Date / Time</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Event</th>
                    <th>Reference</th>
                  </tr>
                </thead>
                <tbody id="tbody_show_tasks">
                  <?php
                    if(($audit_trails)){
                      foreach($audit_trails as $trails){
                        $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
                        if($trails->module == "Login"){
                          $ref = $user_details->lastname.' '.$user_details->firstname;
                        }
                        elseif($trails->module == "Task Manager"){
                          $ref = 'Task ID - A'.$trails->reference;
                        }
                        else{
                          $ref = $trails->reference;
                        }
                        echo '<tr>
                          <td>'.date('d-M-Y H:i', strtotime($trails->updatetime)).'</td>
                          <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
                          <td>'.$trails->module.'</td>
                          <td>'.$trails->event.'</td>
                          <td>'.$ref.'</td>
                        </tr>';
                      }
                    }
                    else{
                      echo '<tr><td colspan="5" style="text-align:center">No Audit Trails Found</td></tr>';
                    }
                  ?>
                </tbody>
            </table>
            <?php
            $total_audits = DB::table('audit_trails')->where('user_id',Session::get('userid'))->get();
            ?>
            <a href="javascript:" class="load_all_audit common_black_button" <?php if(count($total_audits) > 100) { echo 'style="display:block"'; } else { echo 'style="display:none"'; } ?>>Load All</a>
            <input type="hidden" id="hidden_audit_count" name="hidden_audit_count" value="<?php echo count($total_audits); ?>">
        </div>
      </div>
    </div>
</div>
<input type="hidden" id="hidden_page_no" value="1">
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Audit Trails are Loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Checking File: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<script>
$(function(){
    $('#table_administration').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        order: [],
    });
});


function load_all_audit(count)
{
  var page_no = $("#hidden_page_no").val();
  var audit_count = $("#hidden_audit_count").val();
  var next_count = count + 1;
  var total_round = parseInt(audit_count) / 100;
  total_round = Math.ceil(total_round);
  var offset = (parseInt(page_no) - 1) * 100;
  $("#apply_first").html(offset);
  if(next_count <= total_round)
  {
    var filter_by = $(".filter_by").val();
    var filter_by_user = $(".filter_by_user").val();
    var filter_by_module = $(".filter_by_module").val();

    if(filter_by == "1")
    {
      if(filter_by_user == ""){
        alert("Please select the user to load audit trails");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/show_more_user_audit'); ?>",
          type:"get",
          data:{page_no:page_no,filter_by_user:filter_by_user},
          dataType:"json",
          success: function(result)
          {
            $("#tbody_show_tasks").append(result['output']);
            var pageno = parseInt(page_no) + 1;
            $("#hidden_page_no").val(pageno);
            load_all_audit(next_count);
          }
        });
      }
    } else{
      if(filter_by_module == ""){
        alert("Please select the module to load audit trails");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/show_more_module_audit'); ?>",
          type:"get",
          data:{page_no:page_no,filter_by_module:filter_by_module},
          dataType:"json",
          success: function(result)
          {
            $("#tbody_show_tasks").append(result['output']);
            var pageno = parseInt(page_no) + 1;
            $("#hidden_page_no").val(pageno);
            load_all_audit(next_count);
          }
        });
      }
    }
  }
  else{
    $('#table_administration').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        order: [],
    });
    $(".load_all_audit").hide();
    $("body").removeClass("loading_apply");
  }
}

$(window).click(function(e) {
  if($(e.target).hasClass('load_all_audit'))
  {
    $("body").addClass("loading_apply");
    $('#table_administration').DataTable().destroy();
    var page_no = $("#hidden_page_no").val();
    var audit_count = $("#hidden_audit_count").val();
    $("#apply_last").html(audit_count);

    var total_round = parseInt(audit_count) / 100;
    total_round = Math.ceil(total_round);

    var count = 1;
    var offset = (parseInt(page_no) - 1) * 100;
    $("apply_first").html(offset);
    if(count <= total_round)
    {
      var filter_by = $(".filter_by").val();
      var filter_by_user = $(".filter_by_user").val();
      var filter_by_module = $(".filter_by_module").val();

      if(filter_by == "1")
      {
        if(filter_by_user == ""){
          alert("Please select the user to load audit trails");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/show_more_user_audit'); ?>",
            type:"get",
            data:{page_no:page_no,filter_by_user:filter_by_user},
            dataType:"json",
            success: function(result)
            {
              $("#tbody_show_tasks").append(result['output']);
              var pageno = parseInt(page_no) + 1;
              $("#hidden_page_no").val(pageno);
              load_all_audit(count);
            }
          });
        }
      } else{
        if(filter_by_module == ""){
          alert("Please select the module to load audit trails");
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/show_more_module_audit'); ?>",
            type:"get",
            data:{page_no:page_no,filter_by_module:filter_by_module},
            dataType:"json",
            success: function(result)
            {
              $("#tbody_show_tasks").append(result['output']);
              var pageno = parseInt(page_no) + 1;
              $("#hidden_page_no").val(pageno);
              load_all_audit(count);
            }
          });
        }
      }
    }
    else{
      $('#table_administration').DataTable({
          fixedHeader: {
            headerOffset: 75
          },
          autoWidth: true,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: [],
      });
      $(".load_all_audit").hide();
      $("body").removeClass("loading_apply");
    }
  }
  if($(e.target).hasClass('load_audit_trails'))
  {
    var filter_by = $(".filter_by").val();
    var filter_by_user = $(".filter_by_user").val();
    var filter_by_module = $(".filter_by_module").val();

    if(filter_by == "1")
    {
      if(filter_by_user == ""){
        alert("Please select the user to load audit trails");
      }
      else{
        $("body").addClass("loading");
        $('#table_administration').DataTable().destroy();
        $.ajax({
          url:"<?php echo URL::to('user/filter_by_user'); ?>",
          type:"get",
          data:{filter_by:filter_by, filter_by_user:filter_by_user},
          dataType:"json",
          success:function(result){
            $("#tbody_show_tasks").html(result['output']);
            $("#hidden_audit_count").val(result['count']);
            $('#table_administration').DataTable({
                fixedHeader: {
                  headerOffset: 75
                },
                autoWidth: true,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false,
                order: [],
            });
            if(result['count'] > 100){
              $(".load_all_audit").show();
            }
            else{
              $(".load_all_audit").hide();
            }
            $("body").removeClass("loading");
          }
        })
      }
    }
    else{
      if(filter_by_module == ""){
        alert("Please select the module to load audit trails");
      }
      else{
        $("body").addClass("loading");
        $('#table_administration').DataTable().destroy();
        $.ajax({
          url:"<?php echo URL::to('user/filter_by_module'); ?>",
          type:"get",
          data:{filter_by:filter_by, filter_by_module:filter_by_module},
          dataType:"json",
          success:function(result){
            $("#tbody_show_tasks").html(result['output']);
            $("#hidden_audit_count").val(result['count']);

            $('#table_administration').DataTable({
                fixedHeader: {
                  headerOffset: 75
                },
                autoWidth: true,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false,
                order: [],
            });

            if(result['count'] > 100){
              $(".load_all_audit").show();
            }
            else{
              $(".load_all_audit").hide();
            }

            $("body").removeClass("loading");
          }
        })
      }
    }
  }
})
$(window).change(function(e) {
  if($(e.target).hasClass('filter_by')){
    var value = $(e.target).val();
    if(value == "1"){
      $(".filter_by_user_div").show();
      $(".filter_by_module_div").hide();
      $(".filter_by_user").val("");
      $(".filter_by_module").val("");
      $(".filter_label").html("Select User:");
    }
    else{
      $(".filter_by_user_div").hide();
      $(".filter_by_module_div").show();
      $(".filter_by_user").val("");
      $(".filter_by_module").val("");
      $(".filter_label").html("Select Module:");
    }
  }
})
</script>
@stop
