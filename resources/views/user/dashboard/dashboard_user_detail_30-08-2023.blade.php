@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('public/assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('public/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.css') ?>" type="text/css" />
<script src="<?php echo URL::to('public/assets/cropimage/jquery.Jcrop.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins') ?>/fullcalendar/dist/index.global.min.js"></script>
<!-- Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

.fc-scroller-liquid-absolute{
  overflow-y: scroll;
  scrollbar-color: #3db0e6 #fff;
  scrollbar-width: thin;
}
.datetimepicker_div{
  height:150px;
}
.datetimepicker {
  position: absolute;
width: 92%;
z-index: 99999999999; /* Adjust the value as needed */
}
div:where(.swal2-container) div:where(.swal2-actions){
  z-index: 0;
}
.selected-cell .fc-daygrid-day-number{
  background: #2e9fe1;
  color:#fff;
  border-radius: 27px;
  font-weight: 600;
}
.fc table{
  border-collapse: inherit !important;
}

/* Hide all-day events */
.fc-timeGridWeek-view table tbody .fc-scrollgrid-section:nth-child(1) {
  display: none !important;
} 
.fc-day-today .fc-daygrid-day-number {
  
  background: #0c71ac;
  color:#fff;
  border-radius: 27px;
  font-weight: 600;
}
.fc .fc-daygrid-day.fc-day-today {
  background-color: #f5f5f5;
}
:root {
  --fc-today-bg-color: #f5f5f5;
}
.fc-daygrid-day-number{
  padding:10px !important;
  width: 37px;
  text-align: center;
  margin-top: 3px;
  margin-right: 3px;
}
/* Hide all-day events */
.fc-timeGridDay-view table tbody .fc-scrollgrid-section:nth-child(1) {
  display: none !important;
} 
.fc-daygrid-day{
  cursor: pointer;
}
 .fc-timegrid-slot{
  cursor: pointer;
}
.fc-button{
  text-transform: capitalize !important;
}
</style>
<?php
$user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
$img = URL::to('public/assets/user_avatar/ciaranguilfoyle-09.png');
if($user_details->cropped_url != "" && $user_details->cropped_filename != ""){
  $img = URL::to($user_details->cropped_url.'/'.$user_details->cropped_filename);
}
?>
<div class="modal fade change_avatar_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-md" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Change your Profile Picture</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <p class="col-lg-12" style="font-weight: 600;font-size: 18px;">Select an image that will identify your profile.</p>
                <div class="col-lg-12" id="user_avatar_div" style="height: 550px;max-height: 550px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                  <?php
                  $avatarlist = DB::table('user_avatar')->where('status',0)->get();
                  $custom = 1;
                  if(is_countable($avatarlist) && count($avatarlist) > 0) {
                    foreach($avatarlist as $avatar) {
                      if($img == URL::to($avatar->crop_url.'/'.$avatar->crop_filename)) {
                        $selected_container = 'selected_container';
                        $custom = 0;
                      }
                      else{
                        $selected_container = '';
                      }
                      ?>
                      <div class="col-md-2 image_container <?php echo $selected_container; ?>">
                          <img src="<?php echo URL::to($avatar->crop_url).'/'.$avatar->crop_filename; ?>" class="edit_avatar" data-element="<?php echo $avatar->id; ?>" data-original="<?php echo URL::to($avatar->url.'/'.$avatar->filename); ?>" data-cropped="<?php echo URL::to($avatar->crop_url.'/'.$avatar->crop_filename); ?>" data-original_upload_dir="<?php echo $avatar->url; ?>" data-original_filename="<?php echo $avatar->filename; ?>" data-cropped_upload_dir="<?php echo $avatar->crop_url; ?>" data-cropped_filename="<?php echo $avatar->crop_filename; ?>" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
                          <div class="middle">
                            <?php
                            if($img == URL::to($avatar->crop_url.'/'.$avatar->crop_filename)) {
                              
                              ?>
                              <a href="javascript:" class="common_avatar current_apply_avatar current_text" data-element="<?php echo $avatar->id; ?>" data-original="<?php echo URL::to($avatar->url.'/'.$avatar->filename); ?>" data-cropped="<?php echo URL::to($avatar->crop_url.'/'.$avatar->crop_filename); ?>" data-original_upload_dir="<?php echo $avatar->url; ?>" data-original_filename="<?php echo $avatar->filename; ?>" data-cropped_upload_dir="<?php echo $avatar->crop_url; ?>" data-cropped_filename="<?php echo $avatar->crop_filename; ?>">Current</a>
                              <?php
                            }
                            else {
                              ?>
                              <a href="javascript:" class="common_avatar apply_avatar text" data-element="<?php echo $avatar->id; ?>" data-original="<?php echo URL::to($avatar->url.'/'.$avatar->filename); ?>" data-cropped="<?php echo URL::to($avatar->crop_url.'/'.$avatar->crop_filename); ?>" data-original_upload_dir="<?php echo $avatar->url; ?>" data-original_filename="<?php echo $avatar->filename; ?>" data-cropped_upload_dir="<?php echo $avatar->crop_url; ?>" data-cropped_filename="<?php echo $avatar->crop_filename; ?>">Apply</a>
                              <?php
                            }
                            ?>
                          </div>
                      </div>
                      <?php
                    }
                  }
                  if($custom == 1 && $user_details->cropped_url != "") { ?> 
                      <div class="col-md-2 image_container custom_container">
                        <img src="<?php echo URL::to($user_details->cropped_url).'/'.$user_details->cropped_filename; ?>" class="edit_avatar" data-original="<?php echo URL::to($user_details->url.'/'.$user_details->filename); ?>" data-cropped="<?php echo URL::to($user_details->cropped_url.'/'.$user_details->cropped_filename); ?>" data-original_upload_dir="<?php echo $user_details->url; ?>" data-original_filename="<?php echo $user_details->filename; ?>" data-cropped_upload_dir="<?php echo $user_details->cropped_url; ?>" data-cropped_filename="<?php echo $user_details->cropped_filename; ?>" style="width:100%;padding:10px;border: 1px solid #c3c3c3">
                        <div class="middle">
                          <a href="javascript:" class="common_avatar current_apply_avatar current_text" data-original="<?php echo URL::to($user_details->url.'/'.$user_details->filename); ?>" data-cropped="<?php echo URL::to($user_details->cropped_url.'/'.$user_details->cropped_filename); ?>" data-original_upload_dir="<?php echo $user_details->url; ?>" data-original_filename="<?php echo $user_details->filename; ?>" data-cropped_upload_dir="<?php echo $user_details->cropped_url; ?>" data-cropped_filename="<?php echo $user_details->cropped_filename; ?>">Current</a>
                        </div>
                      </div>
                    <?php
                  }
                  ?>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="javascript:" class="common_black_button custom_image_btn" style="margin-left:7px; margin-top:-10px;margin-bottom:10px;float:left">Set Custom Image</a>
            <a href="javascript:" class="common_black_button cancel_btn" style="margin-left:7px; float:right;margin-top:-10px;margin-bottom:10px;">Cancel</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade upload_avatar_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Upload Your Profile Picture</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-6">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">
                        <form action="<?php echo URL::to('user/upload_user_avatar_images'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadavatar" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        @csrf
                        </form>
                        <p style="color:#f00;font-weight: 600">Note: Please Upload only jpeg, jpg and png file formats.</p>
                     </div>
                  </div>
                </div>
                <div class="col-lg-6" id="wrapper_img">
                  <img class="adopt_image" id="cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" id="setx" value="">
            <input type="hidden" id="sety" value="">
            <input type="hidden" id="setw" value="">
            <input type="hidden" id="seth" value="">
            <input type="hidden" id="original_upload_dir" value="">
            <input type="hidden" id="original_filename" value="">

            <a href="javascript:" class="common_black_button crop" align="left" style="margin-left:7px; float:right;margin-top:-10px;margin-bottom:10px;display:none">Crop</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade cropped_img_overlay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title job_title menu-logo" style="font-weight:700;font-size:20px">Cropped Image</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
            <div class="row" style="text-align: center;">
               <img src="javascript:" id="cropped_img">
            </div>
          </div>
          <div class="modal-footer">  
              <input type="hidden" id="hidden_original_image_path" value="">
              <input type="hidden" id="hidden_cropped_image_path" value="">

              <input type="hidden" id="cropped_upload_dir" value="">
              <input type="hidden" id="cropped_filename" value="">
              
              <a href="javascript:" class="common_black_button submit_cropped" style="float:right">Save Cropped Image</a>

              <a href="javascript:" class="common_black_button close_cropped_model" style="float:right">Abort</a>
          </div>
        </div>
  </div>
</div>
<div class="content_section">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
        <a href="<?php echo url('user/dashboard_analytics'); ?>" style="float:left;margin-right:15px">Dashboard</a>
        <div class="dropdown" style="float: left;">
            <button class="dropdown-toggle own_drop_down" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="border: 0px;margin-top: 3px;">
              <i class="fa fa-bars" aria-hidden="true"></i>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="width: 300px;left: 10%;" aria-labelledby="dropdownMenu1">
              <li class="active"><a href="<?php echo URL::to('user/dashboard'); ?>">Dashboard - User Profile</a></li>
              <li><a href="<?php echo URL::to('user/dashboard_analytics'); ?>">Dashboard - User Statistics </a></li>
              <li><a href="<?php echo URL::to('user/dashboard_system_summary'); ?>">Dashboard - System Summary </a></li>
              
            </ul>
        </div>
    </h4>
  </div>
  <div class="col-md-3 text-center" style="height:100%;border-right: 1px solid #c3c3c3;">
    <?php $user = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', Session::get('userid'))->first(); ?>
    <h2 style="font-weight: 600;">Welcome! <?php echo $user->lastname.' '.$user->firstname ; ?></h2>

    
    <img class="user_avatar_img" src="<?php echo $img; ?>" style="width:300px;padding: 10px;border: 1px solid #c3c3c3;margin-top: 12px">
    <div class="col-md-12 padding_00" style="margin-top:10px">
        <a href="javascript:" class="fa fa-edit edit_avatar" title="Change your Avatar" style="font-size: 25px"></a>
    </div>
    <div id='calendar-container' style="width:100%;float:left">
      <div id='calendar_day'></div>
    </div>
  </div>
  <div class="col-md-9" >
    <h4 style="font-weight: 600;margin-top: 27px;">Your Active Time Sheet:</h4>
    <div style="max-height: 220px; overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
        <table class="table tablefixedheader own_table_white" style="width:100%; background: #fff">
            <thead>
                <tr style="background: #fff;">
                  <th width="2%" style="text-align: left;">S.No</th>
                  <th style="text-align: left;">Client Name</th>
                  <th style="text-align: left;">Task Name</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Start Time</th>
                  <th style="text-align: left;">Stop Time</th>
                  <th style="text-align: left;">Job Time</th>
                </tr>
            </thead>
            <tbody id="tbody_active">
                <?php
                $output='';
                $i=1;            
                if(is_countable($joblist) && count($joblist) > 0){              
                  foreach ($joblist as $jobs) {
                    if($jobs->quick_job == 0 || $jobs->quick_job == 1){
                      if($jobs->status == 0){
                        $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $jobs->client_id)->first();
                        if(($client_details) != ''){
                          $client_name = $client_details->company.' ('.$jobs->client_id.')';
                        }
                        else{
                          $client_name = 'N/A';
                        }
                        $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();
                        if(($task_details) != ''){
                          $task_name = $task_details->task_name;
                        }
                        else{
                          $task_name = 'N/A';
                        }
                        $created_date = $jobs->job_created_date;
                        $jobstart = strtotime($created_date.' '.$jobs->start_time);
                        $jobend   = strtotime($created_date.' '.date('H:i:s'));

                        if($jobend < $jobstart)
                        {
                          if($created_date == date('Y-m-d'))
                          {
                              $negative = '-';
                              $jobdiff  = $jobstart - $jobend;
                          }
                          else{
                            $negative = '';
                            $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                            $jobend   = strtotime($todate.' '.date('H:i:s'));
                            $jobdiff  = $jobend - $jobstart;
                          }
                        }
                        else{
                          $negative = '';
                          $jobdiff  = $jobend - $jobstart;
                        }

                        //$todate = date('Y-m-d', strtotime("+1 day", strtotime($jobstart)));
                        $hours = floor($jobdiff / (60 * 60));
                        $minutes = $jobdiff - $hours * (60 * 60);
                        $minutes = floor( $minutes / 60 );
                        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                        if($hours <= 9)
                        {
                          $hours = '0'.$hours;
                        }
                        else{
                          $hours = $hours;
                        }
                        if($minutes <= 9)
                        {
                          $minutes = '0'.$minutes;
                        }
                        else{
                          $minutes = $minutes;
                        }
                        if($second <= 9)
                        {
                          $second = '0'.$second;
                        }
                        else{
                          $second = $second;
                        }
                        $jobtime =   $hours.':'.$minutes.':'.$second;

                        $explode_job_minutes = explode(":",$jobtime);
                        $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                        //-----------Job Time End----------------
                        $current_date = date('Y-m-d');
                        if($current_date != $jobs->job_date)
                        {
                          $redcolor = 'color:#f00;';
                        }
                        elseif($jobs->color == 1){
                         $redcolor = 'color:#0f9600';
                        }
                        elseif($jobs->color == 0){
                          $redcolor = 'color:#666';
                        }
                        else{
                          $redcolor = '';
                        }
                        $inv_no = '';
                        if($jobs->job_type !=  0)
                        {
                          $client_id = $jobs->client_id;
                          $get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
                          $get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
                          if(($get_invoice))
                          {
                            $unserialize = unserialize($get_invoice->auto_tasks);
                            if(($unserialize))
                            {
                              foreach($unserialize as $key => $arrayval)
                              {
                                if(in_array($jobs->task_id, $arrayval))
                                {
                                  $inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
                                }
                              }
                            }
                          }
                        }


                        $output.='
                        <tr>
                          <td align="left" style="'.$redcolor.'">'.$i.'</td>
                          <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                          <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                          <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                          <td align="left" style="'.$redcolor.'">'.$data['start_time'] = date('H:i:s', strtotime($jobs->start_time)).'</td>
                          <td align="left" style="'.$redcolor.'">N/A</td>
                          <td align="left" style="'.$redcolor.'">
                          '.$jobtime.' ('.$total_minutes.')
                          </td>
                        </tr>';
                          
                          $userid = Session::get('task_job_user');
                          $joblist_child = DB::table('task_job')->where('user_id',$userid)->where('active_id',$jobs->id)->get();
                          $childi = 1;
                          if(is_countable($joblist_child) && count($joblist_child) > 0){
                            foreach ($joblist_child as $child) {
                              if($child->quick_job == 0 || $child->quick_job == 1){
                                if($child->status == 0){
                                  $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $child->client_id)->first();
                                  if(($client_details) != ''){
                                    $client_name = $client_details->company.' ('.$child->client_id.')';
                                  }
                                  else{
                                    $client_name = 'N/A';
                                  }
                                  $task_details = DB::table('time_task')->where('id', $child->task_id)->first();
                                  if(($task_details) != ''){
                                    $task_name = $task_details->task_name;
                                  }
                                  else{
                                    $task_name = 'N/A';
                                  }
                                  
                                  $created_date = $child->job_created_date;
                                  $jobstart = strtotime($created_date.' '.$child->start_time);
                                  $jobend   = strtotime($created_date.' '.date('H:i:s'));
                                  if($jobend < $jobstart)
                                  {
                                    if($created_date == date('Y-m-d'))
                                    {
                                        $childnegative = '-';
                                        $jobdiff  = $jobstart - $jobend;
                                    }
                                    else{
                                      $childnegative = '';
                                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                                      $jobdiff  = $jobend - $jobstart;
                                    }
                                  }
                                  else{
                                    $childnegative = '';
                                    $jobdiff  = $jobend - $jobstart;
                                  }
                                  $hours = floor($jobdiff / (60 * 60));
                                  $minutes = $jobdiff - $hours * (60 * 60);
                                  $minutes = floor( $minutes / 60 );
                                  $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                                  if($hours <= 9)
                                  {
                                    $hours = '0'.$hours;
                                  }
                                  else{
                                    $hours = $hours;
                                  }
                                  if($minutes <= 9)
                                  {
                                    $minutes = '0'.$minutes;
                                  }
                                  else{
                                    $minutes = $minutes;
                                  }
                                  if($second <= 9)
                                  {
                                    $second = '0'.$second;
                                  }
                                  else{
                                    $second = $second;
                                  }
                                  $jobtime =   $hours.':'.$minutes.':'.$second;
                                  $explode_job_minutes = explode(":",$jobtime);
                                  $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                                  if($child->stop_time != "00:00:00")
                                  {
                                    $explode_job_minutes = explode(":",$child->job_time);
                                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                                  }
                                  //-----------Job Time End----------------
                                  $current_date = date('Y-m-d');
                                  if($current_date != $child->job_date)
                                  {
                                    $redcolor = 'color:#f00;';
                                  }
                                  elseif($child->color == 1){
                                   $redcolor = 'color:#0f9600';
                                  }
                                  elseif($child->color == 0){
                                    $redcolor = 'color:#666';
                                  }
                                  else{
                                    $redcolor = '';
                                  }
                                  $inv_no = '';
                                  if($child->job_type !=  0)
                                  {
                                    $client_id = $child->client_id;
                                    $get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
                                    $get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
                                    if(($get_invoice))
                                    {
                                      $unserialize = unserialize($get_invoice->auto_tasks);
                                      if(($unserialize))
                                      {
                                        foreach($unserialize as $key => $arrayval)
                                        {
                                          if(in_array($child->task_id, $arrayval))
                                          {
                                            $inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
                                          }
                                        }
                                      }
                                    }
                                  }

                              $output.='
                              <tr>
                                <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                                <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                                <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                                <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                                <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->start_time)).'</td>

                                ';
                                if($child->stop_time != "00:00:00")
                                {
                                  $output.='<td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->stop_time)).'</td>';
                                }
                                else{
                                  $output.='<td align="left" style="'.$redcolor.'">N/A</td>';
                                }

                                $output.='<td align="left" style="'.$redcolor.'">';
                                if($child->stop_time != "00:00:00")
                                {
                                  $output.=$child->job_time.' ('.$total_minutes.')';
                                }
                                else{
                                  $output.=$childnegative.' '.$jobtime.' ('.$total_minutes.')';
                                }
                                $output.='</td>
                              </tr>';
                                $childi++;
                              }
                            }
                          }
                        }
                        $i++;
                      }
                    }
                  }              
                }
                if($i == 1){
                  $output.= '<tr>
                            <td align="left"></td>
                            <td align="left"></td>
                            <td align="center">Empty</td>
                            <td align="left"></td>
                            <td align="left"></td>
                            <td align=""></td>
                            </tr>';
                }
                echo $output;           
                ?>
            </tbody>
        </table>
    </div>
    
    <h4 style="font-weight: 600;margin-top: 27px;">Your System Login’s:</h4>
    <div style="max-height: 220px; overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
        <table class="table tablefixedheader own_table_white" style="width:100%; background: #fff">
            <thead>
              <tr>
                <th>User</th>
                <th>Date</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody id="tbody_show_tasks">
              <?php
                if(is_countable($audit_trails) && count($audit_trails) > 0){
                  foreach($audit_trails as $trails){
                    $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$trails->user_id)->first();
                    if($trails->module == "Login"){
                      $ref = $user_details->lastname.' '.$user_details->firstname;
                    }
                    echo '<tr>
                      <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
                      <td>'.date('d-M-Y', strtotime($trails->updatetime)).'</td>
                      <td>'.date('H:i', strtotime($trails->updatetime)).'</td>
                    </tr>';
                  }
                }
                else{
                  echo '<tr><td colspan="5" style="text-align:center">No Audit Trails Found</td></tr>';
                }
              ?>
            </tbody>
        </table>
    </div>

    <h4 style="font-weight: 600;margin-top: 40px;">Your Tasks:</h4>
    <div style="max-height: 320px; overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
        <table class="table tablefixedheader own_table_white" id="task_details_expand" style="width:100%; background: #fff">
            <thead>
              <tr>
                <th>Task ID</th>
                <th>Client/Task Name</th>
                <th>Author Name</th>
                <th>Subject</th>
                <th>Date Created</th>
                <th>Priority</th>
              </tr>
            </thead>
            <tbody>
                <?php
                  $outputtask = '';
                  if(is_countable($taskslist) && count($taskslist) > 0)
                  {
                    foreach($taskslist as $task)
                    {
                      $two_bill_icon = '';
                      if($task->two_bill == "1")
                      {
                        $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                      }
                      if($task->client_id == "")
                      {
                        $title_lable = 'Task Name:';
                        $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
                        if(($task_details))
                        {
                          $title = $task_details->task_name;
                          $tasktitle = $task_details->task_name;
                          $internaltask = 'yes';
                        }
                        else{
                          $title = '';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                      }
                      else{
                        $title_lable = 'Client:';
                        $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
                        if(($client_details))
                        {
                          $title = $client_details->company.' ('.$task->client_id.')';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                        else{
                          $title = '';
                          $tasktitle = '';
                          $internaltask = '';
                        }
                      }


                      if($task->author != 0)
                      {
                        $author_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id',$task->author)->first();
                        $author_to = $author_details->lastname.' '.$author_details->firstname;
                      }
                      else{
                        $author_to = '-';
                      }
                      if($task->status == 1) { $color = 'color:#f00'; $tr_status= 'tr_closed'; }
                      else { $color = ''; $tr_status= ''; }
                      $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$tr_status.'">
                        <td class="taskid_td" style="'.$color.'">'.$task->taskid.'</td>
                        <td class="taskid_td task_name_val" style="'.$color.'">'.$title.' '.$two_bill_icon.'</td>
                        <td class="author_td" style="'.$color.'">'.$author_to.'</td>
                        <td class="subject_td" style="'.$color.'">'.$task->subject.'</td>
                        <td class="due_date_td" style="'.$color.'"><spam style="display:none">'.strtotime($task->creation_date).'</spam>'.date('d-M-Y', strtotime($task->creation_date)).'</td>
                        <td>'.user_rating($task->id).'</td>
                      </tr>';
                    }
                  }
                  else{
                    $outputtask.='<td colspan="5" style="text-align:center">No Tasks Found</td>';
                  }
                  echo $outputtask;
                  ?>
            </tbody>
        </table>
    </div>
  </div>
</div>
<?php 
$currentData = date('Y-m-d');
?>
<script>
function showDayCalendar2(current_date) {
  var calendarE2 = document.getElementById('calendar_day');
  var calendar_day = new FullCalendar.Calendar(calendarE2, {
      initialDate: current_date,
      initialView: 'timeGridDay',
      headerToolbar: {
        left: 'title',
        center: '',
        right: ''
      },
      height: "500px",
      selectable: true,
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: "<?php echo URL::to('user/fetchevents'); ?>", // Fetch all events
      allDay: false,
      select: function(arg) {
        var selectedDate = change_date_format(arg.start);
        var selectedTime = moment(arg.startStr).format('HH:mm');
        var currentView = 'dayGridMonth';

        // Swal.fire({
        //   title: 'Add New Event',
        //   width: '500px',
        //   height: '700px',
        //   showCancelButton: true,
        //   confirmButtonText: 'Create',
        //   html:
        //   '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_day" class="form-control" placeholder="Event name" required>' +
        //   '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_day" class="form-control" placeholder="Client name" required><input type="hidden" id="eventclientid_day" class="form-control">' +
        //   '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_day" class="form-control" placeholder="Event description" required></textarea>' + 
        //   '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_day" class="form-control" placeholder="Event Start Date" value="'+selectedDate+'"></div>' + 
        //   '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_day" class="form-control" placeholder="Event End Date" value="'+selectedDate+'"></div>' + 
        //   '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_day" class="form-control" placeholder="Event Start Time" value="'+selectedTime+'"></div>' +
        //   '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_day" class="form-control" placeholder="Event End Time" value="'+selectedTime+'"></div></div></div></div>',
        //   didOpen: () => {
        //         $("#eventstartdate_day").datetimepicker({
        //            format: 'L',
        //            format: 'DD-MMM-YYYY',
        //         })
        //         $("#eventenddate_day").datetimepicker({
        //            format: 'L',
        //            format: 'DD-MMM-YYYY',
        //            minDate: moment(arg.start).hour(00).minute(00),
        //         })
        //         $("#eventstarttime_day").datetimepicker({
        //            format: 'L',
        //            format: 'HH:mm',
        //         })
        //         $("#eventendtime_day").datetimepicker({
        //            format: 'L',
        //            format: 'HH:mm',
        //         })
        //         $("#eventstartdate_day").on("dp.change", function (e) {
        //             $('#eventenddate_day').data("DateTimePicker").minDate(e.date);
        //         });
        //         $("#eventstarttime_day").on("dp.change", function (e) {
        //             $('#eventendtime_day').data("DateTimePicker").minDate(e.date);
        //             $('#eventendtime_day').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
        //         });

        //         $("#eventclient_day").autocomplete({
        //           source: function(request, response) {        
        //             $.ajax({
        //               url:"<?php echo URL::to('user/client_review_client_common_search'); ?>",
        //               dataType: "json",
        //               data: {
        //                   term : request.term
        //               },
        //               success: function(data) {
        //                   response(data);
        //               }
        //             });
        //           },
        //           minLength: 1,
        //           select: function( event, ui ) {
        //             $("#eventclientid_day").val(ui.item.id);
        //           }
        //         });
        //   },
        //   focusConfirm: false,
        //   preConfirm: () => {
        //     return [
        //         document.getElementById('eventtitle_day').value,
        //         document.getElementById('eventdescription_day').value,
        //         document.getElementById('eventstartdate_day').value,
        //         document.getElementById('eventenddate_day').value,
        //         document.getElementById('eventstarttime_day').value,
        //         document.getElementById('eventendtime_day').value,
        //         document.getElementById('eventclient_day').value,
        //         document.getElementById('eventclientid_day').value
        //     ]
        //   }
        // }).then((result) => {
        //   if (result.isConfirmed) {
        //     var title = result.value[0].trim();
        //     var description = result.value[1].trim();
        //     var start_date = result.value[2].trim();
        //     var end_date = result.value[3].trim();
        //     var start_time = result.value[4].trim();
        //     var end_time = result.value[5].trim();
        //     var client_name = result.value[6].trim();
        //     var client_id = result.value[7].trim();

        //     if(title != '' && description != '' && client_id != ''){
        //       $.ajax({
        //         url: "<?php echo URL::to('user/calendarEvents'); ?>",
        //         type: 'post',
        //         data: {type: 'addEvent',title: title,description: description,start_date: start_date,end_date: end_date,start_time:start_time,end_time:end_time,client_id:client_id},
        //         dataType: 'json',
        //         success: function(response){
        //           if(response.status == 1){
        //             showDayCalendar(arg.startStr,currentView);
        //             Swal.fire(response.message,'','success');
        //           }else{
        //             Swal.fire(response.message,'','error');
        //           }
        //           showDayCalendar2(arg.startStr);
        //         }
        //       });
        //     }
        //     else{
        //       Swal.fire('Please Enter all the Mandatory Fields','','error');
        //     }
        //   }
        // })
        
        calendar_day.unselect()
      },
      eventDrop: function (event, delta) {
        var eventid = event.event.extendedProps.eventid;
        var newStart_date = event.event.startStr;
        var newEnd_date = event.event.startStr;
        if(event.event.endStr){
          newEnd_date = event.event.endStr;
        }
        var droppedEvent = event.event;
        var newStartDate = droppedEvent.start;
        var start_time = getTimeFormat(newStartDate);
        var newEndDate = droppedEvent.start;
        if(droppedEvent.end) {
          newEndDate = droppedEvent.end;
        }
        var end_time = getTimeFormat(newEndDate);
        var currentView = 'dayGridMonth';

        $.ajax({
          url: "<?php echo URL::to('user/calendarEvents'); ?>",
          type: 'post',
          data: {type: 'moveEvent',eventid: eventid,start_date: newStart_date, end_date: newEnd_date, start_time:start_time, end_time:end_time},
          dataType: 'json',
          async: false,
          success: function(response){
            showDayCalendar(event.event.startStr,currentView);
            showDayCalendar2(event.event.startStr);
          }
        }); 
      },
      eventClick: function(arg) { 
        var eventid = arg.event._def.extendedProps.eventid;
        var description = arg.event._def.extendedProps.description;
        var title = arg.event._def.extendedProps.title_name;
        var client_name = arg.event._def.extendedProps.client_name;
        var client_id = arg.event._def.extendedProps.client_id;

        var start_date = change_date_format(arg.event.start);
        var start_time = getTimeFormat(arg.event.start);
        
        var end_date = change_date_format(arg.event.start);
        var end_time = getTimeFormat(arg.event.start);

        if(arg.event.end){
          var end_date = change_date_format(arg.event.end);
          var end_time = getTimeFormat(arg.event.end);
        }
        var currentView = 'dayGridMonth';

        // Alert box to edit and delete event
        Swal.fire({
          title: 'Edit Event',
          width: '500px',
          height: '700px',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Update',
          denyButtonText: 'Delete',
          html:
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_day" class="form-control" placeholder="Event name" value="'+title+'" required disabled>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_day" class="form-control" placeholder="Client name" value="'+client_name+'" required disabled><input type="hidden" id="eventclientid_day" class="form-control" value="'+client_id+'" disabled>' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_day" class="form-control" placeholder="Event description" required disabled>'+description+'</textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_day" class="form-control" placeholder="Event Start Date" value="'+start_date+'" disabled></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_day" class="form-control" placeholder="Event End Date" value="'+end_date+'" disabled></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_day" class="form-control" placeholder="Event Start Time" value="'+start_time+'" disabled></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_day" class="form-control" placeholder="Event End Time" value="'+end_time+'" disabled></div></div></div></div>',
          didOpen: () => {
                $("#eventstartdate_day").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                })
                $("#eventenddate_day").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                   minDate: arg.startStr,
                })
                $("#eventstarttime_day").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventendtime_day").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventstartdate_day").on("dp.change", function (e) {
                    $('#eventenddate_day').data("DateTimePicker").minDate(e.date);
                });
                $("#eventstarttime_day").on("dp.change", function (e) {
                    $('#eventendtime_day').data("DateTimePicker").minDate(e.date);
                    $('#eventendtime_day').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
                });

                $("#eventclient_day").autocomplete({
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
                    $("#eventclientid_day").val(ui.item.id);
                  }
                });
          },
          focusConfirm: false,
          preConfirm: () => {
            return [
                document.getElementById('eventtitle_day').value,
                document.getElementById('eventdescription_day').value,
                document.getElementById('eventstartdate_day').value,
                document.getElementById('eventenddate_day').value,
                document.getElementById('eventstarttime_day').value,
                document.getElementById('eventendtime_day').value,
                document.getElementById('eventclient_day').value,
                document.getElementById('eventclientid_day').value
            ]
          }
        }).then((result) => {
          if (result.isConfirmed) {
            var newtitle = result.value[0].trim();
            var newdescription = result.value[1].trim();
            var newstart_date = result.value[2].trim();
            var newend_date = result.value[3].trim();
            var newstart_time = result.value[4].trim();
            var newend_time = result.value[5].trim();
            var client_name = result.value[6].trim();
            var client_id = result.value[7].trim();

            if(newtitle != '' && newdescription != '' && client_id != ''){
              $.ajax({
                url: "<?php echo URL::to('user/calendarEvents'); ?>",
                type: 'post',
                data: {type: 'editEvent',eventid: eventid,title: newtitle, description: newdescription,start_date: newstart_date,end_date: newend_date,start_time:newstart_time,end_time:newend_time,client_id:client_id},
                dataType: 'json',
                async: false,
                success: function(response){
                  if(response.status == 1){
                    showDayCalendar(arg.event.startStr,currentView);
                    Swal.fire(response.message,'','success');
                  }else{
                    Swal.fire(response.message,'','error');
                  }

                  showDayCalendar2(arg.event.startStr);
                }
              }); 
            }
            else{
              Swal.fire('Please Enter all the Mandatory Fields','','error');
            }
          } else if (result.isDenied) {
            $.ajax({
              url: "<?php echo URL::to('user/calendarEvents'); ?>",
              type: 'post',
              data: {type: 'deleteEvent',eventid: eventid},
              dataType: 'json',
              async: false,
              success: function(response){
                if(response.status == 1){
                  showDayCalendar(arg.event.startStr,currentView);
                  showDayCalendar2(arg.event.startStr);
                  arg.event.remove();
                  Swal.fire(response.message, '', 'success');
                }else{
                  Swal.fire(response.message, '', 'error');
                } 
              }
            }); 
          }
        })
      }
  });
  calendar_day.render();
}


function change_date_format(date)
{
    var monthNames=["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
    var todayDate = new Date(date);
                                        
    var date = todayDate.getDate().toString();
    var month = todayDate.getMonth().toString(); 
    var year = todayDate.getFullYear().toString(); 


    var formattedMonth = (todayDate.getMonth() < 10) ? "0" + month : month;
    var formattedDay = (todayDate.getDate() < 10) ? "0" + date : date;

    var result  = formattedDay + '-' + monthNames[todayDate.getMonth()].substr(0,3) + '-' + year.substr(2);
    return result;
}
function getTimeFormat(date)
{
    var todayDate = new Date(date);
                                        
    var hour = todayDate.getHours().toString();
    var min = todayDate.getMinutes().toString();


    var formattedHour = (todayDate.getHours() < 10) ? "0" + hour : hour;
    var formattedMinute = (todayDate.getMinutes() < 10) ? "0" + min : min;

    var result  = formattedHour + ':' + formattedMinute;
    return result;
}
document.addEventListener('DOMContentLoaded', function() {
  showDayCalendar2('<?php echo $currentData; ?>');
});
$(function(){
    $('#task_details_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
    });
});
$(window).click(function(e) {
    if($(e.target).hasClass('close_cropped_model')) {
      $(".cropped_img_overlay").modal("hide");
      $(".upload_avatar_modal").modal("hide");
    }
    if($(e.target).hasClass('apply_avatar')) {
      var avatar_id = $(e.target).attr("data-element");
      var original = $(e.target).attr("data-original");
      var cropped = $(e.target).attr("data-cropped");

      var original_upload_dir = $(e.target).attr("data-original_upload_dir");
      var original_filename = $(e.target).attr("data-original_filename");
      var cropped_upload_dir = $(e.target).attr("data-cropped_upload_dir");
      var cropped_filename = $(e.target).attr("data-cropped_filename");
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/change_avathar_user_profile'); ?>",
        type:"post",
        data:{original_upload_dir:original_upload_dir, original_filename:original_filename, cropped_upload_dir:cropped_upload_dir, cropped_filename:cropped_filename},
        success:function(result) {
          $(".user_avatar_img").attr("src",cropped);
          $(".change_avatar_modal").modal("hide");
          $("body").removeClass("loading");
          $(".common_avatar").removeClass("current_apply_avatar");
          $(".common_avatar").removeClass("current_text");
          $(".common_avatar").addClass("apply_avatar");
          $(".common_avatar").addClass("text");
          $(".common_avatar").html("Apply");
          $(e.target).html("Current");
          $(e.target).removeClass("apply_avatar");
          $(e.target).removeClass("text");
          $(e.target).addClass("current_apply_avatar");
          $(e.target).addClass("current_text");
          $(".image_container").removeClass("selected_container")
          $(e.target).parents(".image_container").addClass("selected_container")

          $(".custom_container").detach();
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Your Profile Picture has been Updated Successfully.</p>',fixed:true,width:"600px"});
        }
      })
    }
    if($(e.target).hasClass('custom_image_btn')){
      $(".upload_avatar_modal").modal("show");
      Dropzone.forElement("#imageUploadavatar").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");

      $("#cropped_img").attr('src',"#");
      $('#wrapper_img').empty();

      $(".crop").hide();

      var img = $('<img class="adopt_image" id="cropbox" src="<?php echo URL::to('public/assets/images/no-image.png'); ?>" style="width:100%">');
      img.appendTo('#wrapper_img');
      $("#original_upload_dir").val("");
      $("#original_filename").val("");
      $("#cropped_upload_dir").val("");
      $("#cropped_filename").val("");
      $("#hidden_original_image_path").val("");
      $("#hidden_cropped_image_path").val("");
      $("#setx").val("");
      $("#sety").val("");
      $("#setw").val("");
      $("#seth").val("");
    }
    if($(e.target).hasClass('edit_avatar')){
        $(".change_avatar_modal").modal("show");
    }
    if($(e.target).hasClass('cancel_btn')){
        $(".change_avatar_modal").modal("hide");
    }
    if($(e.target).hasClass('crop')){
      var img = $("#cropbox").attr('src');
      var x = $("#setx").val();
      var y = $("#sety").val();
      var w = $("#setw").val();
      var h = $("#seth").val();
      var upload_dir = $("#original_upload_dir").val();


      var elewidth = $("#cropbox").width();
      var eleheight = $("#cropbox").height();

      $.ajax({
        url:"<?php echo URL::to('user/show_cropped_image'); ?>",
        type:"post",
        dataType:"json",
        data:{x:x,y:y,w:w,h:h,img:img,upload_dir:upload_dir,elewidth:elewidth,eleheight:eleheight},
        success:function(result) {
          $(".cropped_img_overlay").modal("show");
          $("#cropped_img").attr('src',result['image_path']);

          $("#hidden_original_image_path").val(img);
          $("#hidden_cropped_image_path").val(result);

          $("#cropped_upload_dir").val(result['upload_dir']);
          $("#cropped_filename").val(result['filename']);
        }
      });
      
    }
    if($(e.target).hasClass('submit_cropped')) {
      var original_image = $("#hidden_original_image_path").val();
      var cropped_image = $("#hidden_cropped_image_path").val();

      var original_upload_dir = $("#original_upload_dir").val();
      var original_filename = $("#original_filename").val();

      var cropped_upload_dir = $("#cropped_upload_dir").val();
      var cropped_filename = $("#cropped_filename").val();
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::To('user/save_cropped_image'); ?>",
        type: "post",
        dataType:"json",
        data:{original_upload_dir:original_upload_dir,original_filename:original_filename,cropped_upload_dir:cropped_upload_dir,cropped_filename:cropped_filename},
        success:function(result){
          $(".upload_avatar_modal").modal("hide");
          $(".cropped_img_overlay").modal("hide");
          $(".change_avatar_modal").modal("hide");

          $("#original_upload_dir").val("");
          $("#original_filename").val("");
          $("#cropped_upload_dir").val("");
          $("#cropped_filename").val("");

          $(".user_avatar_img").attr("src",result['cropped_img']);
          $(".change_avatar_modal").modal("hide");
          $("body").removeClass("loading");
          $(".common_avatar").removeClass("current_apply_avatar");
          $(".common_avatar").removeClass("current_text");
          $(".common_avatar").addClass("apply_avatar");
          $(".common_avatar").addClass("text");
          $(".common_avatar").html("Apply");
          $("#user_avatar_div").html(result['output']);
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">Your Profile Picture has been Updated Successfully.</p>',fixed:true,width:"600px"});


        }
      })
    }
})
Dropzone.options.imageUploadavatar = {
    maxFiles: 1,
    acceptedFiles: "image/jpeg,image/jpg,image/png",
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
            //$(".adopt_image").attr("src", obj.image_path)
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadavatar").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");

            $('#wrapper_img').empty();
            var img = $('<img class="adopt_image" id="cropbox" src="'+obj.image_path+'" style="width:100%">');
            img.appendTo('#wrapper_img');
            $("#original_upload_dir").val(obj.upload_dir);
            $("#original_filename").val(obj.filename);

            $("#cropped_upload_dir").val("");
            $("#cropped_filename").val("");

            var size;
            $('#cropbox').Jcrop({
              aspectRatio: 1,
              onSelect: function(c){
                $("#setx").val(c.x);
                $("#sety").val(c.y);
                $("#setw").val(c.w);
                $("#seth").val(c.h);
               $(".crop").show();     
              }
            });
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadavatar").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE ONE file <br/>OR just drop ONE file here to upload");
          }
        });
        this.on("error", function (file) {
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
        });
    },
};
</script>
@stop