@extends('clientportalheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.css') ?>" type="text/css" />
<script src="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.js'); ?>"></script>

<style>
  .col-md-2{
    padding: 10px 10px 0px 0px;
  }
  .dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
    margin-top: 50px;
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
    .own_drop_down:hover, .own_drop_down:active, .own_drop_down:focus, .own_drop_down:visited,.own_drop_down:focus-visible,.own_drop_down:focus-within,.own_drop_down:target{
        color: #000 !important;
        background: #e7e7e7 !important;
    }
    .image_container {
      position: relative;
    }

    .edit_avatar {
      opacity: 1;
      display: block;
      height: auto;
      transition: .5s ease;
      backface-visibility: hidden;
    }

    .middle {
      transition: .5s ease;
      opacity: 0;
      position: absolute;
      top: 55%;
      left: 48%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      text-align: center;
    }

    .image_container:hover .edit_avatar {
      opacity: 0.3;
    }

    .image_container:hover .middle {
      opacity: 1;
    }

    .text {
      background-color: #2e9fe1;
      color: white !important;
      font-size: 16px;
      padding: 10px 10px;
    }
    .current_text {
      background-color: green;
      color: white !important;
      font-size: 16px;
      padding: 10px 10px;
    }
    #cropped_img{
  width:50%;
  padding: 10px;
border: 1px solid #c3c3c3;
border-radius: 10px;
}

.custom_container .edit_avatar {
    border: 5px solid green !important;
}
.selected_container .edit_avatar {
  border: 5px solid green !important;
}
.table tbody tr td {border: 0px}
</style>
<?php
$user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
$img = URL::to('public/assets/user_avatar/ciaranguilfoyle-09.png');
if($user_details->cropped_url != "" && $user_details->cropped_filename != ""){
  $img = URL::to($user_details->cropped_url.'/'.$user_details->cropped_filename);
}
?>
<div class="modal fade" id="module_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Task Details</h4>
          </div>
          <div class="modal-body">
              <label>Enter Salutation : </label>
              <div class="form-group">            
                  <input class="form-control" name="salutation_module" id="salutation_module" placeholder="Enter Salutation" type="text">
              </div>
              <label>Enter Primary Email : </label>
              <div class="form-group">            
                  <input class="form-control" name="pemail_module" id="pemail_module" placeholder="Enter Primary Email" type="text">
              </div>
              <label>Enter Secondary Email : </label>
              <div class="form-group">            
                  <input class="form-control" name="semail_module" id="semail_module" placeholder="Enter Secondary Email" type="text">
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="task_type" id="task_type" value="">
              <input type="hidden" name="task_id_module" id="task_id_module" value="">
              <input type="submit" class="common_black_button" id="submit_module_update" value="Submit">
          </div>
      </div>
  </div>
</div>
<div class="modal fade show_messageus_last_screen_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index:9999999">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title menu-logo">MessageUs Summary</h4>
          </div>
          <div class="modal-body" id="show_messageus_body" style="max-height: 600px;height:600px; overflow-y: scroll;">
              
          </div>
          <div class="modal-footer">
              <button type="button" class="common_black_button" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
      </div>
  </div>
</div>
<?php
$result = \App\Models\CMClients::where("practice_code",Session::get("user_practice_code"))->where("client_id", $_GET['client_id'])->first();
?>
<div class="content_section">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
        <?php echo $result->company.' - '.$result->client_id; ?>
    </h4>
  </div>
  <div class="col-md-12 padding_00 client_portal_content about_me_content">
    <div class="col-md-3 text-center" style="height:100%;border-right: 1px solid #c3c3c3;">
      <?php $img = URL::to('public/assets/user_avatar/ciaranguilfoyle-09.png'); ?>
      <img class="user_avatar_img" src="<?php echo $img; ?>" style="width:300px;padding: 10px;border: 1px solid #c3c3c3;margin-top: 12px">
      <!-- <div class="col-md-12 padding_00" style="margin-top:10px">
          <a href="javascript:" class="fa fa-edit edit_avatar" title="Change your Avatar" style="font-size: 25px"></a>
      </div> -->

        <?php
        $result = \App\Models\CMClients::where("practice_code",Session::get("user_practice_code"))->where("client_id", $_GET['client_id'])->first();

        $clientid = ($result->client_id != "") ? $result->client_id : 'N/A';
        $client_added = ($result->client_added != "") ? $result->client_added : 'N/A';
        $firstname = ($result->firstname != "") ? $result->firstname : 'N/A';
        $surname = ($result->surname != "") ? $result->surname : 'N/A';
        $company = ($result->company != "") ? $result->company : 'N/A';
        $address1 = ($result->address1 != "") ? $result->address1 : 'N/A';
        $address2 = ($result->address2 != "") ? $result->address2 : 'N/A';
        $address3 = ($result->address3 != "") ? $result->address3 : 'N/A';
        $address4 = ($result->address4 != "") ? $result->address4 : 'N/A';
        $address5 = ($result->address5 != "") ? $result->address5 : 'N/A';
        $email = ($result->email != "") ? $result->email : 'N/A';
        $tye = ($result->tye != "") ? $result->tye : 'N/A';
        $active = ($result->active != "") ? $result->active : 'N/A';
        $tax_reg1 = ($result->tax_reg1 != "") ? $result->tax_reg1 : 'N/A';
        $tax_reg2 = ($result->tax_reg2 != "") ? $result->tax_reg2 : 'N/A';
        $tax_reg3 = ($result->tax_reg3 != "") ? $result->tax_reg3 : 'N/A';
        $email2 = ($result->email2 != "") ? $result->email2 : 'N/A';
        $phone = ($result->phone != "") ? $result->phone : 'N/A';
        $linkcode = ($result->linkcode != "") ? $result->linkcode : 'N/A';
        $cro = ($result->cro != "") ? $result->cro : 'N/A';
        $ard = ($result->ard != "") ? $result->ard : 'N/A';
        $trade_status = ($result->trade_status != "") ? $result->trade_status : 'N/A';
        $directory = ($result->directory != "") ? $result->directory : 'N/A';
        $employer_no = ($result->employer_no != "") ? $result->employer_no : 'N/A';
        $salutation = ($result->salutation != "") ? $result->salutation : 'N/A';
        $status = ($result->status != "") ? $result->status : 'N/A';
        $practice_code = ($result->practice_code != "") ? $result->practice_code : 'N/A';
        $id = ($result->id != "") ? $result->id : 'N/A';
        $client_note = ($result->notes != "") ? $result->notes : 'N/A';
        $bank_client_id = ($result->client_id != "") ? $result->client_id : 'N/A';
        ?>
        <table class="table" style="margin-top:30px">
          <tbody>
            <tr>
              <td><label>Practice Code:</label></td>
              <td><?php echo $practice_code; ?></td>
            </tr>
            <tr>
              <td><label>Employer No:</label></td>
              <td><?php echo $employer_no; ?></td>
            </tr>
            <tr>
              <td><label>Client ID:</label></td>
              <td><?php echo $clientid; ?></td>
            </tr>
            <tr>
              <td><label>Firstname:</label></td>
              <td><?php echo $firstname; ?></td>
            </tr>
            <tr>
              <td><label>Surname:</label></td>
              <td><?php echo $surname; ?></td>
            </tr>
            <tr>
              <td><label>Company Name:</label></td>
              <td><?php echo $company; ?></td>
            </tr>
            <tr>
              <td><label>Primary Email:</label></td>
              <td><?php echo $email; ?></td>
            </tr>
            <tr>
              <td><label>Secondary Email:</label></td>
              <td><?php echo $email2; ?></td>
            </tr>
            <tr>
              <td><label>Phone:</label></td>
              <td><?php echo $phone; ?></td>
            </tr>
          </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table">
            <tbody>
              
              <tr>
                <td rowspan="4" style="width:15%;"><label>Address:</label></td>
                <td><?php echo $address1; ?></td>
              </tr>
              <tr>
                <td><?php echo $address2; ?></td>
              </tr>
              <tr>
                <td><?php echo $address3; ?></td>
              </tr>
              <tr>
                <td><?php echo $address4; ?></td>
              </tr>
              <tr>
                <td><label>CRO:</label></td>
                <td><?php echo $cro; ?></td>
              </tr>
              <tr>
                <td><label>Client Added:</label></td>
                <td><?php echo $client_added; ?></td>
              </tr>
              <tr>
                <td><label>Trade Status:</label></td>
                <td><?php echo $trade_status; ?></td>
              </tr>
              <tr>
                <td><label>Tax Reg1:</label></td>
                <td><?php echo $tax_reg1; ?></td>
              </tr>
              <tr>
                <td><label>Tax Reg2:</label></td>
                <td><?php echo $tax_reg2; ?></td>
              </tr>
              <tr>
                <td><label>Tax Reg3:</label></td>
                <td><?php echo $tax_reg3; ?></td>
              </tr>
              
              <tr>
                <td><label>Type:</label></td>
                <td><?php echo $tye; ?></td>
              </tr>
              <tr>
                <td><label>Class:</label></td>
                <td><?php echo $active; ?></td>
              </tr>
              <tr>
                <td><label>Directory:</label></td>
                <td><?php echo $directory; ?></td>
              </tr>
              <tr>
                <td><label>Link Code:</label></td>
                <td><?php echo $linkcode; ?></td>
              </tr>
              
              <tr>
                <td><label>Salutation:</label></td>
                <td><?php echo $salutation; ?></td>
              </tr>
            </tbody>
        </table>
    </div>
  </div>
  <div class="col-md-12 padding_00 client_portal_content email_received_content" style="display:none">
    <?php
    
    $outputmessage = '<table class="table"  id="message_expand">
        <thead>
          <th>#</th>
          <th>Subject</th>
          <th>Message From</th>
          <th>Date Sent</th>
          <th>Source</th>
          <th>Body</th>
          <th>Attachments</th>
          <th>VIEW</th>
        </thead>
        <tbody>';
    $messageus = \App\Models\Messageus::where('practice_code',Session::get('user_practice_code'))->where("status", 1)->where("client_ids", "LIKE", "%" . $_GET['client_id'] . "%")->orderBy('id','desc')->get();
    if (count($messageus)) {
        $i = 1;
        foreach ($messageus as $message) {
            $from = $message->message_from;
            if ($from == 0) {
                $mess_from = "Admin";
            } else {
                $userdetails =\App\Models\User::where('practice',Session::get('user_practice_code'))->where("user_id", $from)->first();
                $mess_from =
                    $userdetails->lastname . " " . $userdetails->firstname;
            }
            $count_files = \App\Models\MessageusFiles::where(
                "message_id",
                $message->id
            )->count();
            if ($message->source == "") {
                $source = "MessageUS";
                $mess = substr($message->message, 0, 30) . "...";
                $count_files = $count_files;
                $link =
                    '<a href="javascript:" class="fa fa-eye view_message" title="View Message" data-element="' .
                    $message->id .
                    '" style="margin-left:20px"></a>';
            } else {
                $source = $message->source;
                $strip = strip_tags($message->message);
                $mess = mb_substr(trim($strip), 0, 30) . "...";
                if ($message->attachments == "") {
                    $count_files = 0;
                } else {
                    $explodeattach = explode("||", $message->attachments);
                    $count_files = count($explodeattach);
                }
                $link =
                    '<a href="javascript:" class="fa fa-eye view_message" title="View Message" data-element="' .
                    $message->id .
                    '" style="margin-left:20px"></a>';
            }
            $outputmessage .=
                '<tr>
              <td>' .
                $i .
                '</td>
              <td>' .
                $message->subject .
                '</td>
              <td>' .
                $mess_from .
                '</td>
              <td>' .
                date("d-M-Y @ H:i", strtotime($message->date_sent)) .
                '</td>
              <td>' .
                $source .
                '</td>
              <td>' .
                $mess .
                '</td>
              <td>' .
                $count_files .
                '</td>
              <td>
                ' .
                $link .
                '
              </td>
            </tr>';
            $i++;
        }
    } else {
        $outputmessage .= '<tr>
          <td colspan="8">No Message Found</td>
      </tr>';
    }
    $outputmessage.='</tbody>
    </table>';
    echo $outputmessage;
    ?>
  </div>
  <div class="col-md-12 padding_00 client_portal_content coms_content" style="display:none">
    <?php
    $i = 1;
    
    $current_week = \App\Models\week::where('practice_code', Session::get('user_practice_code'))->orderBy("week_id", "desc")->first();
    $current_month = \App\Models\Month::where('practice_code', Session::get('user_practice_code'))->orderBy("month_id", "desc")->first();
    $week_tasks = \App\Models\task::where("client_id", $_GET['client_id'])
            ->where("task_week", $current_week->week_id)
            ->get();
    $month_tasks = \App\Models\task::where("client_id", $_GET['client_id'])
        ->where("task_month", $current_month->month_id)
        ->get();
    $vats =\App\Models\vatClients::where("cm_client_id", $_GET['client_id'])->get();
    $statement = \App\Models\ClientStatement::where("client_id",$_GET['client_id'])->first();
    $outputmodule = '<table class="table" id="module_expand">
      <thead>
          <th style="text-align:left">#</th>
          <th style="text-align:left">Module</th>
          <th style="text-align:left">Salute</th>
          <th style="text-align:left">Primary Email</th>
          <th style="text-align:left">Secondary Email</th>
          <th style="text-align:left">Action</th>
      </thead>
      <tbody>';
    if (($week_tasks)) {
      foreach ($week_tasks as $task) {
        $outputmodule .=
          '<tr class="pms_module_'.$task->task_id .'">
            <td>'.$i.'</td>
            <td>PMS - Weekly</td>
            <td class="salutation_mod">'.$task->salutation.'</td>
            <td class="primary_mod">'.$task->task_email.'</td>
            <td class="secondary_mod">'.$task->secondary_email.'</td>
            <td><a href="javascript:" class="edit_task_module" data-element="'.$task->task_id .'" data-type="1" data-salutation="'.$task->salutation.'" data-primary="'.$task->task_email.'" data-secondary="'.$task->secondary_email.'">Edit</a></td>
          </tr>';
          $i++;
      }
    }
    if (($month_tasks)) {
      foreach ($month_tasks as $task) {
          $outputmodule .=
            '<tr class="pms_module_'.$task->task_id .'">
            <td>'.$i.'</td>
            <td>PMS - Monthly</td>
            <td class="salutation_mod">'.$task->salutation.'</td>
            <td class="primary_mod">'.$task->task_email.'</td>
            <td class="secondary_mod">'.$task->secondary_email.'</td>
            <td><a href="javascript:" class="edit_task_module" data-element="'.$task->task_id .'" data-type="1" data-salutation="'.$task->salutation.'" data-primary="'.$task->task_email.'" data-secondary="'.$task->secondary_email.'">Edit</a></td>
          </tr>';
          $i++;
      }
    }
    if (($vats)) {
      foreach ($vats as $vat) {
          $outputmodule .=
            '<tr class="vat_module_'.$vat->client_id .'">
            <td>'.$i.'</td>
            <td>Vat System</td>
            <td class="salutation_mod">'.$vat->salutation.'</td>
            <td class="primary_mod">'.$vat->pemail.'</td>
            <td class="secondary_mod">'.$vat->semail.'</td>
            <td><a href="javascript:" class="edit_task_module" data-element="'.$vat->client_id.'" data-type="2" data-salutation="'.$vat->salutation.'" data-primary="'.$vat->pemail.'" data-secondary="'.$vat->semail.'">Edit</a></td>
          </tr>';
          $i++;
      }
    }
    if ($statement) {
      $outputmodule .=
      '<tr class="statement_module_'.$statement->client_id.'">
          <td>'.$i.'</td>
          <td>Statement</td>
          <td class="salutation_mod">'.$statement->salutation.'</td>
          <td class="primary_mod">'.$statement->email.'</td>
          <td class="secondary_mod">'.$statement->email2.'</td>
          <td><a href="javascript:" class="edit_task_module" data-element="'.$statement->client_id.'" data-type="3" data-salutation="'.$statement->salutation.'" data-primary="'.$statement->email.'" data-secondary="'.$statement->email2.'">Edit</a></td>
      </tr>';
      $i++;
    }
    $outputmodule .=
      '<tr class="keydocs_module_'.$result->client_id.'">
        <td>'.$i.'</td>
        <td>Key Docs</td>
        <td class="keydocs_mod">' .$result->salutation .'</td>
        <td class="primary_mod">' .$result->email .'</td>
        <td class="secondary_mod">' .$result->email2 .'</td>
        <td><a href="javascript:" class="edit_task_module" data-element="' .$result->client_id .'" data-type="4" data-salutation="' .$result->salutation .'" data-primary="' .$result->email .'" data-secondary="' .$result->email2 .'">Edit</a></td>
      </tr>';
    $outputmodule.='</tbody>
    </table>';
    echo $outputmodule;
    ?>
  </div>
  <div class="col-md-12 padding_00 client_portal_content key_documents_content" style="display:none">
      <h4 style="color:#f00">Functionality for this section will be implemented in the future.</h4>
  </div>
</div>

<script>
$(window).click(function(e) {
  if($(e.target).hasClass('view_message'))
  {
    var message_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_messageus_sample_screen_portal'); ?>",
      type:"post",
      data:{message_id:message_id},
      success:function(result)
      {
        $(".show_messageus_last_screen_modal").modal("show");
        $("#show_messageus_body").html(result);
      }
    })
  }
  if($(e.target).hasClass('edit_task_module'))
  {
    var type = $(e.target).attr("data-type");
    var taskid = $(e.target).attr("data-element");
    var salutation = $(e.target).attr("data-salutation");
    var primary = $(e.target).attr("data-primary");
    var secondary = $(e.target).attr("data-secondary");

    $("#task_type").val(type);
    $("#task_id_module").val(taskid);
    $("#salutation_module").val(salutation);
    $("#pemail_module").val(primary);
    $("#semail_module").val(secondary);

    $("#module_modal").modal("show");
  }
  if(e.target.id == "submit_module_update")
  {
    var type = $("#task_type").val();
    var taskid = $("#task_id_module").val();
    var salutation = $("#salutation_module").val();
    var primary = $("#pemail_module").val();
    var secondary = $("#semail_module").val();

    $.ajax({
      url:"<?php echo URL::to('user/update_pms_vat_module'); ?>",
      type:"post",
      data:{type:type,taskid:taskid,salutation:salutation,primary:primary,secondary:secondary},
      success: function(result)
      {
        if(type == "2")
        {
          $(".vat_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".vat_module_"+taskid).find(".primary_mod").html(primary);
          $(".vat_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".vat_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else if(type == "3")
        {
          $(".statement_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".statement_module_"+taskid).find(".primary_mod").html(primary);
          $(".statement_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".statement_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else if(type == "4")
        {
          $(".keydocs_module_"+taskid).find(".keydocs_mod").html(salutation);
          $(".keydocs_module_"+taskid).find(".primary_mod").html(primary);
          $(".keydocs_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".keydocs_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }
        else{
          $(".pms_module_"+taskid).find(".salutation_mod").html(salutation);
          $(".pms_module_"+taskid).find(".primary_mod").html(primary);
          $(".pms_module_"+taskid).find(".secondary_mod").html(secondary);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-salutation",salutation);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-primary",primary);
          $(".pms_module_"+taskid).find(".edit_task_module").attr("data-secondary",secondary);
        }

        $("#module_modal").modal("hide");
      }
    })
  }
})
</script>
@stop