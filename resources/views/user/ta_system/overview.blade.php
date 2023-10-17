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
  .quick_links img{
    width: 34px;
    transition: 0.5s all ease-in-out;
  }
  .quick_links:hover img{
    transform: scale(1.5);
  }
  .quick_links:hover{
    color:yellow;
    background-color:black;
  }
body{
  background: #f5f5f5 !important;
}
.common_black_button:hover{
  background: #5f5f5f;
    color: #fff;
    border: 0px;
    box-shadow: none;
    background-color: #5f5f5f !important;
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

<img id="coupon" />





<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Invoices</h4>
        </div>
        <div class="modal-body" style="height: 600px; font-size: 14px; overflow-y: scroll;">
          <style type="text/css">
            .account_table .account_row .account_row_td{font-size: 14px; line-height: 20px; float:left;}
            .account_table .account_row .account_row_td.left{width:40%;}
            .account_table .account_row .account_row_td.right{width:60%;}

            .tax_table_div{width: 100%; height: auto; float: left;}
            .tax_table{width: 80%; height: auto; float: left;margin-left: 10%;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; border-top:2px solid #000; }

            .class_row{width: 100%; height: 20px;}

            .details_table .class_row .class_row_td, .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .details_table .class_row .class_row_td.left{width:70%;min-height:10px; text-align: left; float: left; height:20px;}
            .details_table .class_row .class_row_td.left_corner{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right_start{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right{width:10%;text-align: right; padding-right: 10px; float: right;height:20px;}

            .tax_table .tax_row .tax_row_td.left{width:70%;text-align: left; float: left;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; float: right;}

            .details_table .class_row, .tax_table .tax_row{line-height: 30px; clear: both;}

            .company_details_class{width: 100%; margin: 0px auto; height: auto;}

            .company_details_div{width: 40%; height: auto; float: left; margin-top: 220px; margin-left: 10%}
            .firstname_div{width: 100%; float: left; margin-top: 55px;}
            .aib_account{ width: 200px; height: auto; float: right; line-height: 20px; color: #ccc; font-size: 12px; }
            .account_details_div{width: 50%; height:auto; float: left; margin-top: 220px;}
            .account_details_main_address_div{width: 100%; height: auto; float: right;}
            .account_details_address_div{width: 100%; height: auto; float: left; }
            .account_details_invoice_div{width: 200px; height: auto; float: right; clear: both; margin-top: 20px;}
            .invoice_label{width: 100%; height: auto; float: left; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px;}
            .tax_details_class_maindiv{width: 100%; min-height: 539px; float: left;}
          </style>
          <div id="letterpad_modal" style="width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('public/assets/invoice_letterpad.jpg');?>') no-repeat">
            <div class="company_details_class"></div>
            <div class="tax_details_class_maindiv">
              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                <div class="class_row class_row1"></div>
                <div class="class_row class_row2"></div>
                <div class="class_row class_row3"></div>
                <div class="class_row class_row4"></div>
                <div class="class_row class_row5"></div>
                <div class="class_row class_row6"></div>
                <div class="class_row class_row7"></div>
                <div class="class_row class_row8"></div>
                <div class="class_row class_row9"></div>
                <div class="class_row class_row10"></div>
                <div class="class_row class_row11"></div>
                <div class="class_row class_row12"></div>
                <div class="class_row class_row13"></div>
                <div class="class_row class_row14"></div>
                <div class="class_row class_row15"></div>
                <div class="class_row class_row16"></div>
                <div class="class_row class_row17"></div>
                <div class="class_row class_row18"></div>
                <div class="class_row class_row19"></div>
                <div class="class_row class_row20"></div>
              </div>
            </div>
            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
            <div class="tax_details_class"></div> 
          </div>
        </div>

        <div class="modal-footer">

            <input type="button" class="common_black_button saveas_pdf" value="Save as PDF">
            <input type="button" class="common_black_button print_pdf" value="Print">

        </div>

      </div>

  </div>

</div>


<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $companydetails_val = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
  $companyname_val = $companydetails_val->company.'-'.$client_id;
  $hiddenval = $_GET['client_id'];
}
else{
  $companyname_val = '';
  $hiddenval = '';
}
$year = DB::table('year_end_year')->where('status', 0)->orderBy('year','desc')->first();
$year_clients = DB::table('year_client')->where('year', $year->year)->where('client_id',$client_id)->first(); 
$prevdate = date("Y-m-05", strtotime("first day of -1 months"));
$prev_date2 = date('Y-m', strtotime($prevdate));
?>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title"  style="display:flex;">TA System for <?php echo $companydetails_val->company.' ('.$client_id.')'?>
      <span style="margin-left:40px; margin-top:-8px;">   
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/rct_client_manager/'.$client_id.'?active_month='.$prev_date2)?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-university" title="RCT Manager" aria-hidden="true"></i>
        </a></div>  
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/client_management?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-users" title="Client Management System" aria-hidden="true"></i>
        </a></div>      
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/client_request_manager/'.base64_encode($client_id))?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-envelope" title="Clinet Request System" aria-hidden="true"></i>
        </a></div>      
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/infile_search?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-file-archive-o" title="Infile" aria-hidden="true"></i>
        </a></div>    
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/key_docs?client_id='.$client_id)?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-key" title="Keydocs" aria-hidden="true"></i>
        </a></div>      
        <?php if(($year_clients)){ ?>
        <div style="display:inline-flex;"><a class="quick_links" href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_clients->id))?>" style="padding:10px; text-decoration:none;">
        <i class="fa fa-calendar" title="Yearend Manager" aria-hidden="true"></i>
        </a></div>   
        <?php }  ?>   
      </span> 
    </h4>
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 
    <div class="col-lg-12">
      <div class="col-lg-4" style="padding: 0px">
        <?php
        if(isset($_GET['client_id']))
        {
          $client_details = DB::table('cm_clients')->where('practice_code',Session::get('user_practice_code'))->where('client_id',$_GET['client_id'])->first();
          if(($client_details))
          {
            $clientname = $client_details->company.'-'.$_GET['client_id'];
            $client_id = $_GET['client_id'];
          }
          else{
            $clientname = '';
            $client_id = '';
          }
        }
        else{
          $clientname = '';
          $client_id = '';
        }
        ?>
        <input type="text" class="form-control" id="search_clientid" placeholder="Enter Client ID" name="" value="<?php echo $clientname; ?>" style="width:94%; display:inline;">
        <img class="active_client_list_pms" src="<?php echo URL::to('public/assets/images/active_client_list.png'); ?>" style="width:24px; cursor:pointer;" title="Add to active client list" />
        <input type="hidden" id="client_search" class="client_search" value="<?php echo $client_id; ?>">
      </div>
    </div> 
<?php
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $joblist = DB::table('task_job')->where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();

  $result_unallocated_time_job='';
  $unallocated_total=0;
  $sub_hour_calculate_unallocated=0;
  $sub_minutes_calculate_unallocated=0;
  $sub_total_minutes_unallocated=0;
  if(($joblist)){
    foreach ($joblist as $jobs) {

      $user_details = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
      $time_task = DB::table('time_task')->where('id', $jobs->task_id)->first();

      $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

      //-----------Job Time Start----------------
      $explode_job_minutes = explode(":",$jobs->job_time);
      $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

      $rate_result = '0';
      $cost = '0';

      if(($ratelist)){
        foreach ($ratelist as $rate) {
          $job_date = strtotime($jobs->job_date);
          $from_date = strtotime($rate->from_date);
          $to_date = strtotime($rate->to_date);

          if($rate->to_date != '0000-00-00'){                         
            if($job_date >= $from_date  && $job_date <= $to_date){
              
              $rate_result = $rate->cost;                        
              $cost = ($rate_result/60)*$total_minutes;
            }
          }
          else{
            if($job_date >= $from_date){
              $rate_result = $rate->cost;
              $cost = ($rate_result/60)*$total_minutes;
            }
          }
        }                      
      }

      $ta_count = DB::table('ta_client_invoice')->where('client_id', $client_id)->first();
      if(($ta_count)){
        $unserialize = unserialize($ta_count->tasks);
        //$filter_jobs = strrpos($ta_count->tasks, '"'.$jobs->id.'"');
        if (!in_array_r($jobs->id, $unserialize)) { 
          $unallocated_total = $unallocated_total+$cost;
          $sub_total_minutes_unallocated = $sub_total_minutes_unallocated+$total_minutes;
          $sub_hour_calculate_unallocated = strtok(($sub_total_minutes_unallocated/60), '.');
          $sub_minutes_calculate_unallocated = $sub_total_minutes_unallocated-($sub_hour_calculate_unallocated*60);

          if($sub_hour_calculate_unallocated <= 9){
            $sub_hour_calculate_unallocated = '0'.$sub_hour_calculate_unallocated;
          }
          else{
            $sub_hour_calculate_unallocated = $sub_hour_calculate_unallocated;
          }

          if($sub_minutes_calculate_unallocated <= 9){
            $sub_minutes_calculate_unallocated = '0'.$sub_minutes_calculate_unallocated;
          }
          else{
            $sub_minutes_calculate_unallocated = $sub_minutes_calculate_unallocated;
          }
          if($jobs->comments != "")
          {
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
          }
          else{
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
          }
          $result_unallocated_time_job.='<tr>                    
                <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                <td>'.$time_task->task_name.'</td>
                <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                <td>'.$jobs->job_time.' '.$comments.'</td>
                <td style="text-align:right;">'.number_format_invoice_without_decimal($cost).'</td>                    
              </tr>';  
        }         
      }
      else{
        $unallocated_total = $unallocated_total+$cost;
        $sub_total_minutes_unallocated = $sub_total_minutes_unallocated+$total_minutes;
        $sub_hour_calculate_unallocated = strtok(($sub_total_minutes_unallocated/60), '.');
        $sub_minutes_calculate_unallocated = $sub_total_minutes_unallocated-($sub_hour_calculate_unallocated*60);
        if($sub_hour_calculate_unallocated <= 9){
          $sub_hour_calculate_unallocated = '0'.$sub_hour_calculate_unallocated;
        }
        else{
          $sub_hour_calculate_unallocated = $sub_hour_calculate_unallocated;
        }

        if($sub_minutes_calculate_unallocated <= 9){
          $sub_minutes_calculate_unallocated = '0'.$sub_minutes_calculate_unallocated;
        }
        else{
          $sub_minutes_calculate_unallocated = $sub_minutes_calculate_unallocated;
        }
        if($jobs->comments != "")
      {
        $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
 data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
      }
      else{
        $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
 data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
      }
      if($time_task){
        $tskname = $time_task->task_name;
      }
      else{
        $tskname = '';
      }
        $result_unallocated_time_job.='<tr>                    
          <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td>'.$tskname.'</td>
          <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
          <td>'.$jobs->job_time.' '.$comments.'</td>
          <td style="text-align:right;">'.number_format_invoice_without_decimal($cost).'</td>                    
        </tr>';
      }
    }
    $result_unallocated_time_job.='
    <tr>
      <th class="sorting_disabled">Total</td>
      <th class="sorting_disabled"></td>
      <th class="sorting_disabled"></td>
      <th class="sorting_disabled">'.$sub_hour_calculate_unallocated.':'.$sub_minutes_calculate_unallocated.':00</td>
      <th class="sorting_disabled" style="text-align:right;">'.number_format_invoice_without_decimal($unallocated_total).'</td>        
    </tr>';
  }
  else{
    $result_unallocated_time_job.='<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Empty</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>';
  }
  $invoice_list = DB::table('invoice_system')->where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
  $result_invoice_total=0;
  $result_invoice_cross=0;
  $result_invoice_vat=0;
  $result_invoice_net=0;
  $result_invoice_profit=0;
  $result_invoice_percentage=0;        
  $result_invoice='';

    if(($invoice_list)){
      foreach ($invoice_list as $key => $invoice) {   
        $invoices = DB::table('ta_client_invoice')->where('client_id', $client_id)->first();
        $invoice_number = $invoice->invoice_number;

        if(isset($invoices->tasks)){
          $unserialize1 = unserialize($invoices->tasks);
        }
        
        
        if(isset($unserialize1[$invoice_number])){
          
          $unserialize = $unserialize1[$invoice_number];
        } 
        else{
          $unserialize = array();
        }
      
        

        $invoice_total_cost='0';
        $result_final_percentage='0';

        if(($unserialize)){              
          foreach ($unserialize as $single_task) {                
            
            $jobs = DB::table('task_job')->where('id', $single_task)->first();                               
            
            if(($jobs)){
              
               
                $explode_job_minutes = explode(":",$jobs->job_time);
                $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                $rate_result = '0';
                $cost = '0';

                if(($ratelist)){
                  foreach ($ratelist as $rate) {
                    $job_date = strtotime($jobs->job_date);
                    $from_date = strtotime($rate->from_date);
                    $to_date = strtotime($rate->to_date);

                    if($rate->to_date != '0000-00-00'){                         
                      if($job_date >= $from_date  && $job_date <= $to_date){
                        
                        $rate_result = $rate->cost;                        
                        $cost = ($rate_result/60)*$total_minutes;
                      }
                    }
                    else{
                      if($job_date >= $from_date){
                        $rate_result = $rate->cost;
                        $cost = ($rate_result/60)*$total_minutes;
                      }
                    }
                  }                      
                }    
                $invoice_total_cost = $invoice_total_cost+$cost;                                    
              
            }

            $excluded = '<a href="javascript:" title="Move to Excluded List"><i class="fa fa-arrow-circle-right job_excluded"></i></a>';
          }
        }
        else{
          $invoice_total_cost = 0;
          $excluded = '<a href="javascript:" title="Move to Excluded List"><i class="fa fa-arrow-circle-right job_empty" data-element="'.$invoice->invoice_number.'"></i></a>';
        }

        $profit = $invoice->inv_net-$invoice_total_cost;
        if($profit == 0)
        {
        	$profit_percentage = 0*100;
        }
        else{
        	$profit_percentage = ($profit/$invoice->inv_net)*100;
        }
        if($profit < 0){
          $color = 'color:#f00';
        }
        else{
          $color = '';
        }

        $ta_excluded = DB::table('ta_excluded') ->where('excluded_client_id', $client_id)->first();

        if(($ta_excluded)){
          $explode_excluded = explode(',', $ta_excluded->excluded_invoice);
        }
        else{
          $explode_excluded = array();
        }

        if(!in_array($invoice->invoice_number, $explode_excluded)){

          $invoice_count = count($invoice_list)-count($explode_excluded);


          $result_invoice.='<tr>
            <td style="'.$color.'"><a href="javascript:" data-element="'.$invoice->invoice_number.'" class="invoice_class">'.$invoice->invoice_number.'</a></td>
            <td style="text-align:right;'.$color.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
            <td style="text-align:right;'.$color.'">'.number_format_invoice_without_decimal($invoice->gross).'</td>
            <td style="text-align:right;'.$color.'">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
            <td style="text-align:right;'.$color.'">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>        
            <td style="text-align:right;'.$color.'">'.number_format_invoice_without_decimal($invoice_total_cost).'</td>
            <td style="text-align:right;'.$color.'">'.number_format_invoice_without_decimal($profit).'</td>
            <td style="'.$color.'">'.number_format_invoice_without_decimal($profit_percentage).' %</td>
            <td style="text-align:right;"  align="center">
            '.$excluded.'&nbsp;&nbsp;
            <a href="javascript:" title="Review"><i class="fa fa-files-o review_class" data-element="'.$invoice->invoice_number.'"></i></a>
            </td>
          </tr>'; 
          $result_invoice_cross = $result_invoice_cross+number_format_invoice_without_comma($invoice->gross);
          $result_invoice_vat = $result_invoice_vat+number_format_invoice_without_comma($invoice->vat_value);
          $result_invoice_net = $result_invoice_net+number_format_invoice_without_comma($invoice->inv_net);
          $result_invoice_total = $result_invoice_total+number_format_invoice_without_comma($invoice_total_cost);
          $result_invoice_profit = $result_invoice_profit+number_format_invoice_without_comma($profit);
          $result_invoice_percentage = $result_invoice_percentage+number_format_invoice_without_comma($profit_percentage);
          if($result_invoice_percentage == 0)
          {
            $result_final_percentage = 0;
          }
          elseif($invoice_count == 0)
          {
            $result_final_percentage = 0;
          }
          else{
            $result_final_percentage = $result_invoice_percentage/$invoice_count;
          }
        }
      }
      if($result_invoice_profit == 0)
      {
        $result_final_percentage = 0 * 100;
      }
      elseif($result_invoice_net == 0)
      {
        $result_final_percentage = 0 * 100;
      }
      else{
        $result_final_percentage = ($result_invoice_profit / $result_invoice_net) * 100;
      }
      $result_invoice.='
          <tr>                  
            <th style="text-align:right" class="sorting_disabled"></th>
            <th style="text-align:right" class="sorting_disabled"></th>
            <th style="text-align:right" class="sorting_disabled">'.number_format_invoice_without_decimal($result_invoice_cross).'</th>
            <th style="text-align:right" class="sorting_disabled">'.number_format_invoice_without_decimal($result_invoice_vat).'</th>
            <th style="text-align:right" class="sorting_disabled">'.number_format_invoice_without_decimal($result_invoice_net).'</th>
            <th style="text-align:right" class="sorting_disabled">'.number_format_invoice_without_decimal($result_invoice_total).'</th>
            <th style="text-align:right" class="sorting_disabled">'.number_format_invoice_without_decimal($result_invoice_profit).'</th>
            <th class="sorting_disabled">'.number_format_invoice_without_decimal($result_final_percentage).' %</th>
            <th style="text-align:right" class="sorting_disabled"></th>
          </tr>';
      $result_summary='
        <tr>                  
          <td style="text-align: left;">Total of All Invoices included for allocation</td>
          <td style="text-align: right;">'.number_format_invoice_without_decimal($result_invoice_net).'</td>
        </tr>
        <tr>                  
          <td style="text-align: left;">Total Costs Allcoated</td>
          <td style="text-align: right;">'.number_format_invoice_without_decimal($result_invoice_total).'</td>
        </tr>
        <tr>                  
          <td style="text-align: left;">Profit / Loss</td>
          <td style="text-align: right;">'.number_format_invoice_without_decimal($result_invoice_profit).'</td>
        </tr>
        <tr>                  
          <td style="text-align: left;">Average % Profit / Loss for Allocated Invoices</td>
          <td style="text-align: right;">'.number_format_invoice_without_decimal($result_final_percentage).' %</td>
        </tr>
        <tr>                  
          <td style="text-align: left;">Total Cost of Unallocated time</td>
          <td style="text-align: right;">'.number_format_invoice_without_decimal($unallocated_total).'</td>
        </tr>';            
    }
    else{
    $result_invoice = '<tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="center">Invoice Not found in allocation</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>';
    $result_summary='
    <tr>                  
        <td style="text-align: left;">Total of All Invoices included for allocation</td>
        <td style="text-align: right;">0.00</td>
      </tr>
      <tr>                  
        <td style="text-align: left;">Total Costs Allcoated</td>
        <td style="text-align: right;">0.00</td>
      </tr>
      <tr>                  
        <td style="text-align: left;">Profit / Loss</td>
        <td style="text-align: right;">0.00</td>
      </tr>
      <tr>                  
        <td style="text-align: left;">Average % Profit / Loss for Allocated Invoices</td>
        <td style="text-align: right;">0.00</td>
      </tr>
      <tr>                  
        <td style="text-align: left;">Total Cost of Unallocated time</td>
        <td style="text-align: right;">'.number_format_invoice_without_decimal($unallocated_total).'</td>
      </tr>';
    }

  $ta_excluded = DB::table('ta_excluded')->where('excluded_client_id', $client_id)->first();

      if(($ta_excluded))
      {
        if($ta_excluded->excluded_invoice != ""){

            $invoicelist = explode(',', $ta_excluded->excluded_invoice);

            $total_excluded_gross=0;
            $total_excluded_vat=0;
            $total_excluded_net=0;


            $result_excluded='';
            if(($invoicelist)){
              foreach ($invoicelist as $key => $invoice) {
                $ta_count = DB::table('ta_client_invoice')->where('client_id', $client_id)->first();

                $invoice_details = DB::table('invoice_system')->where('invoice_number', $invoice)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->first();

                  $result_excluded.='
                  <tr>
                    <td><a href="javascript:" class="invoice_class" data-element="'.$invoice_details->invoice_number.'">'.$invoice_details->invoice_number.'</a></td>
                    <td><spam style="display:none">'.strtotime($invoice_details->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice_details->invoice_date)).'</td>
                    <td align="right">'.number_format_invoice_without_decimal($invoice_details->gross).'</td>
                    <td align="right">'.number_format_invoice_without_decimal($invoice_details->vat_value).'</td>
                    <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>              
                    <td align="center"><a href="javascript:"><i class="fa fa-check include_invoice" data-element="'.$key.'" id="'.$invoice_details->invoice_number.'"></i></a></td>
                  </tr>
                  ';
                  $total_excluded_gross = $total_excluded_gross+$invoice_details->gross;
                  $total_excluded_vat = $total_excluded_vat+$invoice_details->vat_value;
                  $total_excluded_net = $total_excluded_net+$invoice_details->inv_net;
              }
              $result_excluded.='
                <tr>
                  <td class="sorting_disabled"></td>
                  <td class="sorting_disabled"></td>
                  <td class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($total_excluded_gross).'</td>
                  <td class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($total_excluded_vat).'</td>
                  <td class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($total_excluded_net).'</td>
                  <td class="sorting_disabled"></td>            
                </tr>
                ';
            }
        }
        else{
          $result_excluded='
          <tr>
            <td></td>
            <td></td>
            <td align="right">Empty</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          ';
        }
      }
      else{
          $result_excluded='
          <tr>
            <td></td>
            <td></td>
            <td align="right">Empty</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          ';
      }
}
else{
  $result_invoice = '';
  $result_summary = '';
  $result_unallocated_time_job = '';
  $result_excluded = '';
}
?>
  <div class="col-lg-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs" role="tablist">
            <li><a href="<?php echo URL::to('user/ta_allocation?client_id='.$_GET['client_id'])?>">Allocation</a></li>
            <li><a href="<?php echo URL::to('user/ta_auto_allocation?client_id='.$_GET['client_id'])?>">Auto Alloction</a></li>
            <li class="active"><a href="<?php echo URL::to('user/ta_overview?client_id='.$_GET['client_id'])?>">Overview</a></li>
            <li style="float:right"><a href="<?php echo URL::to('user/ta_system')?>" class="common_black_button">Back to TA System</a></li>
      </ul>
    </div>
  <div class="col-lg-12" style="padding-top: 20px;">
    <div class="tab-content">
      <div class="result_div">

        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-left" style="font-size: 15px; font-weight: bold; margin-top: 20px;">Summary
            
  </div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table" id="summary_expand">
              <tbody class="result_summary">
                <?php echo $result_summary; ?>
              </tbody>
            </table>
          </div>
        </div>


        <div class="col-lg-7" style="margin-top: 50px;">          
          
          <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Overview</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px; max-height: 500px; overflow: hidden; overflow-y: scroll;">
            <table class="own_table" id="overview_expand">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Invoice No</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Gross</th>
                  <th style="text-align: left;">VAT</th>
                  <th style="text-align: left;">Net</th>
                  <th style="text-align: left;">Total Cost</th>
                  <th style="text-align: left;">Profit / (Loss)</th>
                  <th style="text-align: left;">% Profit / (Loss)</th>                  
                  <th style="text-align: left;">Action</th>                  

              </tr>
              </thead>
              <tbody class="result_invoice">
                <?php echo $result_invoice; ?>
                
              </tbody>
            </table>
            
          </div>

        </div>
        <div class="col-lg-5" style="margin-top: 50px;">
          <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Invoices Excluded from the Profit & Loss Monitor</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px; max-height: 500px; overflow: hidden; overflow-y: scroll;">
            <table class="own_table" id="invoice_expand">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Invoice No</th>
                  <th style="text-align: left;">Date</th>                  
                  <th style="text-align: left;">Gross</th>                  
                  <th style="text-align: left;">Vat</th>
                  <th style="text-align: left;">Net</th>
                  <th style="text-align: left;">Action</th>
              </tr>
              </thead>
              <tbody class="result_excluded">
                <?php echo $result_excluded; ?>
                
              </tbody>
            </table>
          </div>          
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-center" style="font-size: 18px; font-weight: bold; margin-top: 70px;">Invoice <span class="invoice_number"></span>Review</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table" id="review_expand">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Invoice NO</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Gross</th>
                  <th style="text-align: left;">VAT</th>
                  <th style="text-align: left;">Net</th>
              </tr>
              </thead>
              <tbody class="result_invoice_single">
                <tr>                  
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>                  
                </tr>
              </tbody>
            </table>
          </div>
          
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-left" style="font-size: 15px; font-weight: bold; margin-top: 20px;">Cost Analysis - Overview
</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table" id="cost_expand">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Staff</th>
                  <th style="text-align: left;">Rate</th>
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: left;">Time</th>
                  <th style="text-align: left;">Cost</th>
              </tr>
              </thead>
              <tbody class="result_cost_analysis">
                <tr>                  
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>                
              </tbody>
            </table>
          </div>
          
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-left" style="font-size: 15px; font-weight: bold; margin-top: 20px;">Cost Analysis - Task</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: left;">Time</th>
                  <th style="text-align: left;">Cost</th>
              </tr>
              </thead>
              <tbody class="result_cost_analysis_task">
                <tr>                  
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>                
              </tbody>
            </table>
          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-left" style="font-size: 15px; font-weight: bold; margin-top: 20px;">Cost Analysis - User</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">User</th>
                  <th style="text-align: left;">Time</th>
                  <th style="text-align: left;">Cost</th>
              </tr>
              </thead>
              <tbody class="result_cost_analysis_user">
                <tr>                  
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>                
              </tbody>
            </table>
          </div>
          
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">          
          <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold; margin-top: 70px;">Unallocated Time</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table" id="unallocated_expand">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: left;">User (Rate)</th>
                  <th style="text-align: left;">Job Time</th>
                  <th style="text-align: left;">Cost</th>
              </tr>
              </thead>
              <tbody class="result_unallocated_time_job">
                <?php echo $result_unallocated_time_job; ?>           
              </tbody>
            </table>
          </div>
          
        </div>


        

        
      </div>
    </div>
  </div>
</div>

    <!-- End  -->
<div class="main-backdrop"><!-- --></div>



<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<script>
$(window).click(function(e) {
  $('[data-toggle="popover"]').popover();  
  if($(e.target).hasClass('active_client_list_pms'))
  {
    var client_id=$("#client_search").val();
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
if($(e.target).hasClass('invoice_class')){

  var editid = $(e.target).attr("data-element");
  $.ajax({
        url: "<?php echo URL::to('user/invoices_print_view') ?>",
        data:{id:editid},
        dataType:'json',
        type:"post",
        success:function(result){      
           $(".invoice_modal").modal("show");
           $("body").removeClass("loading");  
           $("#invoice_number_pdf").val(editid);
           $(".company_details_class").html(result['companyname']);
           $(".tax_details_class").html(result['taxdetails']);
           $(".class_row1").html(result['row1']);
           $(".class_row2").html(result['row2']);
           $(".class_row3").html(result['row3']);
           $(".class_row4").html(result['row4']);
           $(".class_row5").html(result['row5']);
           $(".class_row6").html(result['row6']);
           $(".class_row7").html(result['row7']);
           $(".class_row8").html(result['row8']);
           $(".class_row9").html(result['row9']);
           $(".class_row10").html(result['row10']);
           $(".class_row11").html(result['row11']);
           $(".class_row12").html(result['row12']);
           $(".class_row13").html(result['row13']);
           $(".class_row14").html(result['row14']);
           $(".class_row15").html(result['row15']);
           $(".class_row16").html(result['row16']);
           $(".class_row17").html(result['row17']);
           $(".class_row18").html(result['row18']);
           $(".class_row19").html(result['row19']);
           $(".class_row20").html(result['row20']);
    }

  });


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
if($(e.target).hasClass('invoice_item_class')){
  var invoice = $(e.target).attr("data-element");
  $(".select_invoice").val(invoice);
}

if($(e.target).hasClass('review_class')){
  $("body").addClass("loading");
  var invoice = $(e.target).attr("data-element"); 
  var client_id = $("#client_search").val();
  $(".invoice_number").html('#'+invoice+' ');
  $.ajax({
    url:"<?php echo URL::to('user/ta_overview_invoice'); ?>",
    data:{invoice:invoice, client_id:client_id},
    type:"post",
    dataType:"json",
    success: function(result)
    {
      $(".result_invoice_single").html(result['result_invoice_single']);
      $(".result_cost_analysis").html(result['result_cost_analysis']);
      $(".result_cost_analysis_task").html(result['result_cost_analysis_task']);
      $(".result_cost_analysis_user").html(result['result_cost_analysis_user']);
      $("body").removeClass("loading");

    }

  });
}
if($(e.target).hasClass('job_excluded')){
  alert("You cannot Exclude an invoice with Tasks allocated to it from the Profit & Loss Monitor");
}

if($(e.target).hasClass('job_empty')){
  var invoice = $(e.target).attr("data-element");
  var r = confirm("You are about to move #"+invoice+". Do you want to continue?");
  if(r == true){
    $("body").addClass("loading");
    
    var client_id = $("#client_search").val();
    $("#overview_expand").dataTable().fnDestroy();
    $("#invoice_expand").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/ta_excluded'); ?>",
      data:{invoice:invoice, client_id:client_id},
      type:"post",
      dataType:"json",
      success: function(result){
        $(".result_excluded").html(result['result_excluded']);
        $(".result_invoice").html(result['result_invoice']);
        $(".result_summary").html(result['result_summary']);
        alert(result['excluded_message']);
        $("body").removeClass("loading");
        $('#overview_expand').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            ordering: true,
            order: [[ 1, "desc" ]],
            aoColumnDefs : [{
              "bSortable" : false,
              "aTargets" : [ "sorting_disabled" ]
            }]
        });
        $('#invoice_expand').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            ordering: true,
            order: [[ 1, "desc" ]],
            aoColumnDefs : [{
              "bSortable" : false,
              "aTargets" : [ "sorting_disabled" ]
            }]
        });
      }

    })
  }
  else{
    return false
  }
  
}

if($(e.target).hasClass('include_invoice')){
  $("body").addClass("loading");
  var key = $(e.target).attr("data-element");
  var invoice = $(e.target).attr("id");
  console.log(key);
  var client_id = $("#client_search").val();
  $("#overview_expand").dataTable().fnDestroy();
    $("#invoice_expand").dataTable().fnDestroy();
  $.ajax({
    url:"<?php echo URL::to('user/ta_include'); ?>",
    data:{invoice:invoice, client_id:client_id,key:key},
    type:"post",
    dataType:"json",
    success: function(result){
      $(".result_excluded").html(result['result_excluded']);
      $(".result_invoice").html(result['result_invoice']);
      $(".result_summary").html(result['result_summary']);
      alert(result['excluded_message']);
      $("body").removeClass("loading");
      $('#overview_expand').DataTable({        
          autoWidth: true,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          ordering: true,
          order: [[ 1, "desc" ]],
          aoColumnDefs : [{
            "bSortable" : false,
            "aTargets" : [ "sorting_disabled" ]
          }]
      });
      $('#invoice_expand').DataTable({        
          autoWidth: true,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          ordering: true,
          order: [[ 1, "desc" ]],
          aoColumnDefs : [{
            "bSortable" : false,
            "aTargets" : [ "sorting_disabled" ]
          }]
      });
    }

  })
}




})

</script>


<!-- Page Scripts -->
<script>

function printPdf(url) {
  var iframe = this._printIframe;
  if (!this._printIframe) {
    iframe = this._printIframe = document.createElement('iframe');
    document.body.appendChild(iframe);

    iframe.style.display = 'none';
    iframe.onload = function() {
      setTimeout(function() {
        iframe.focus();
        iframe.contentWindow.print();
      }, 1);
    };
  }

  iframe.src = url;
}



$(function(){
    $('#overview_expand').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true,
        order: [[ 1, "desc" ]],
        aoColumnDefs : [{
          "bSortable" : false,
          "aTargets" : [ "sorting_disabled" ]
        }]
    });
    $('#invoice_expand').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true,
        order: [[ 1, "desc" ]],
        aoColumnDefs : [{
          "bSortable" : false,
          "aTargets" : [ "sorting_disabled" ]
        }]
    });
    $('#unallocated_expand').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true,
        order: [[ 0, "asc" ]]
    });
    
    
});

</script>

<script>
$(document).ready(function() {     
   $('[data-toggle="popover"]').popover();   
   $("#search_clientid").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/ta_overview_client_search'); ?>",
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
        $("body").addClass("loading");
        $("#client_search").val(ui.item.id);
        $("#invoice_expand").dataTable().fnDestroy();
        $.ajax({
          
          url:"<?php echo URL::to('user/ta_overview_client_search_result'); ?>",
          data:{value:ui.item.id},
          dataType: "json",
          success: function(result){
            $(".result_invoice").html(result['result_invoice']);
            $(".result_summary").html(result['result_summary']);
            $(".result_unallocated_time_job").html(result['result_unallocated_time_job']);
            $(".result_excluded").html(result['result_excluded']);


            
          $('#invoice_expand').DataTable({        
              autoWidth: true,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false
          });

          var url = '/user/ta_overview?client_id='+ui.item.id;
            window.location.replace("<?php echo URL::to('/'); ?>"+url)
          $("body").removeClass("loading");
            
          }
        })
      }
  });     
});
</script>
@stop