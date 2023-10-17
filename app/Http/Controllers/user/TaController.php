<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel;
use PHPWord; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class TaController extends Controller {
  /*
  |--------------------------------------------------------------------------
  | Welcome Controller
  |--------------------------------------------------------------------------
  |
  | This controller renders the "marketing page" for the application and
  | is configured to only allow guests. Like most of the other sample
  | controllers, you are free to modify or remove it as you desire.
  |
  */
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('userauth');
    date_default_timezone_set("Europe/Dublin");
    require_once app_path("Http/helpers.php");
  }
  /**
   * Show the application welcome screen to the user.
   *
   * @return Response
   */
    public function ta_system(Request $request){
      $client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();   
      // if(($client))
      // {
      //   foreach($client as $cli)
      //   {
      //     $get_invoices = \App\Models\InvoiceSystem::where('client_id',$cli->client_id)->get();
      //     $get_excluded = \App\Models\taExcluded::where('excluded_client_id',$cli->client_id)->first();
      //     if(($get_excluded))
      //     {
      //       $invoices = 0;
      //       $exploded = explode(',',$get_excluded->excluded_invoice);
      //       if(($get_invoices))
      //       {
      //         foreach($get_invoices as $invoice)
      //         {
      //           $strtotime = strtotime($invoice->invoice_date);
      //           $checktime = strtotime('2019-01-01');
      //           if($strtotime < $checktime)
      //           {
      //             if(!in_array($invoice->invoice_number,$exploded))
      //             {
      //               if($invoices == "")
      //               {
      //                 $invoices = $invoice->invoice_number;
      //               }
      //               else{
      //                 $invoices = $invoices.','.$invoice->invoice_number;
      //               }
      //             }
      //           }
      //         }
      //       }
      //       if($get_excluded->excluded_invoice == "")
      //       {
      //         if($invoices == "")
      //         {
      //           $datainvoice['excluded_invoice'] = "";
      //         }
      //         else{
      //           $datainvoice['excluded_invoice'] = $invoices;
      //         }
      //       }
      //       else{
      //         if($invoices == "")
      //         {
      //           $datainvoice['excluded_invoice'] = $get_excluded->excluded_invoice;
      //         }
      //         else{
      //           $datainvoice['excluded_invoice'] = $get_excluded->excluded_invoice.','.$invoices;
      //         }
      //       }
      //       \App\Models\taExcluded::where('excluded_client_id',$cli->client_id)->update($datainvoice);
      //     }
      //     else{
      //       $invoices = 0;
      //       if(($get_invoices))
      //       {
      //         foreach($get_invoices as $invoice)
      //         {
      //           $strtotime = strtotime($invoice->invoice_date);
      //           $checktime = strtotime('2019-01-01');
      //           if($strtotime < $checktime)
      //           {
      //             if($invoices == "")
      //             {
      //               $invoices = $invoice->invoice_number;
      //             }
      //             else{
      //               $invoices = $invoices.','.$invoice->invoice_number;
      //             }
      //           }
      //         }
      //       }
      //       $datainvoice_insert['excluded_client_id'] = $cli->client_id;
      //       $datainvoice_insert['excluded_invoice'] = $invoices;
      //       \App\Models\taExcluded::where('excluded_client_id',$cli->client_id)->insert($datainvoice_insert);
      //     }
      //   }
      // }         
      return view('user/ta_system/ta_system', array('title' => 'TA System', 'clientlist' => $client));
    }
    public function ta_system_ajax_response(Request $request)
    {
      $clients = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
      $i=1;
      $outputclients = 0;
      if(($clients))
      {
        foreach($clients as $client)
        {
              $task_job_details = \App\Models\taskJob::where('client_id',$client->client_id)->where('status',1)->select('id', 'job_time', 'client_id')->get();
              if(($task_job_details))
              {
                $allocated_time = 0;
                $unallocated_time = 0;
                foreach($task_job_details as $jobs)
                {
                  $client_id = $jobs->client_id;
                  $get_client_invoice = \App\Models\taClientInvoice::where('client_id',$client_id)->first();
                  if(($get_client_invoice))
                  {
                    if(strpos($get_client_invoice->tasks, '"'.$jobs->id.'"') !== false) {
                      $explode_job_minutes = explode(":",$jobs->job_time);
                      $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                      $allocated_time = $allocated_time + $total_minutes;
                    }
                    else{
                      $explode_job_minutes = explode(":",$jobs->job_time);
                      $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                      $unallocated_time = $unallocated_time + $total_minutes;
                    }
                  }
                  else{
                    $explode_job_minutes = explode(":",$jobs->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                    $unallocated_time = $unallocated_time + $total_minutes;
                  }
                }
                $hour_calculate = strtok(($allocated_time/60), '.');
                $minutes_calculate = $allocated_time-($hour_calculate*60);
                if($hour_calculate <= 9){
                  $hour_calculate = '0'.$hour_calculate;
                }
                else{
                  $hour_calculate = $hour_calculate;
                }
                if($minutes_calculate <= 9){
                  $minutes_calculate = '0'.$minutes_calculate;
                }
                else{
                  $minutes_calculate = $minutes_calculate;
                }
                $hour_calculate_unallocated = strtok(($unallocated_time/60), '.');
                $minutes_calculate_unallocated = $unallocated_time-($hour_calculate_unallocated*60);
                if($hour_calculate_unallocated <= 9){
                  $hour_calculate_unallocated = '0'.$hour_calculate_unallocated;
                }
                else{
                  $hour_calculate_unallocated = $hour_calculate_unallocated;
                }
                if($minutes_calculate_unallocated <= 9){
                  $minutes_calculate_unallocated = '0'.$minutes_calculate_unallocated;
                }
                else{
                  $minutes_calculate_unallocated = $minutes_calculate_unallocated;
                }
              }
              else{
                $allocated_time = 0;
                $unallocated_time = 0;
                $hour_calculate = '00';
                $minutes_calculate = '00';
                $hour_calculate_unallocated = '00';
                $minutes_calculate_unallocated = '00';
              } 
              if($client->company == ""){ $clientname = $client->firstname.' & '.$client->surname; } else { $clientname = $client->company; }
              $outputclients.='<tr class="edit_task edit_task_'.$client->client_id.'">
                  <td align="left"><a href="javascript:" class="load_unallocated fa fa-cog" data-element="'.$client->client_id.'" title="Apply time allocations to this Client"></a></td>
                  <td>'.$i.'</td>
                  <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->client_id.'</a></td>
                  <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$clientname.'</a></td>
                  <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->firstname.'</a></td>
                  <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->surname.'</a></td>
                  <td align="left" class="allocated_time_client allocated_time_client_'.$client->client_id.'">'.$hour_calculate.':'.$minutes_calculate.':00 ('.$allocated_time.' Mins)'.'</td>
                  <td align="left" class="unallocated_time_client unallocated_time_client_'.$client->client_id.'">'.$hour_calculate_unallocated.':'.$minutes_calculate_unallocated.':00 ('.$unallocated_time.' Mins)'.'</td>
              </tr>';
            $i++;
        }
      }
      if($i == 1)
      {
        $outputclients.='<tr><td colspan="7" align="center">Empty</td></tr>';
      }
      echo $outputclients;
    }
    public function load_unallocated_time_for_client(Request $request)
    {
      $client_id = $request->get('client_id');
      $task_job_details = \App\Models\taskJob::where('client_id',$client_id)->where('status',1)->select('id', 'job_time', 'client_id')->get();
      if(($task_job_details))
      {
        $allocated_time = 0;
        $unallocated_time = 0;
        foreach($task_job_details as $jobs)
        {
          $client_id = $jobs->client_id;
          $get_client_invoice = \App\Models\taClientInvoice::where('client_id',$client_id)->first();
          if(($get_client_invoice))
          {
            if(strpos($get_client_invoice->tasks, '"'.$jobs->id.'"') !== false) {
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              $allocated_time = $allocated_time + $total_minutes;
            }
            else{
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              $unallocated_time = $unallocated_time + $total_minutes;
            }
          }
          else{
            $explode_job_minutes = explode(":",$jobs->job_time);
            $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
            $unallocated_time = $unallocated_time + $total_minutes;
          }
        }
        $hour_calculate = strtok(($allocated_time/60), '.');
        $minutes_calculate = $allocated_time-($hour_calculate*60);
        if($hour_calculate <= 9){
          $hour_calculate = '0'.$hour_calculate;
        }
        else{
          $hour_calculate = $hour_calculate;
        }
        if($minutes_calculate <= 9){
          $minutes_calculate = '0'.$minutes_calculate;
        }
        else{
          $minutes_calculate = $minutes_calculate;
        }
        $hour_calculate_unallocated = strtok(($unallocated_time/60), '.');
        $minutes_calculate_unallocated = $unallocated_time-($hour_calculate_unallocated*60);
        if($hour_calculate_unallocated <= 9){
          $hour_calculate_unallocated = '0'.$hour_calculate_unallocated;
        }
        else{
          $hour_calculate_unallocated = $hour_calculate_unallocated;
        }
        if($minutes_calculate_unallocated <= 9){
          $minutes_calculate_unallocated = '0'.$minutes_calculate_unallocated;
        }
        else{
          $minutes_calculate_unallocated = $minutes_calculate_unallocated;
        }
      }
      else{
        $allocated_time = 0;
        $unallocated_time = 0;
        $hour_calculate = '00';
        $minutes_calculate = '00';
        $hour_calculate_unallocated = '00';
        $minutes_calculate_unallocated = '00';
      } 
      $allocated = $hour_calculate.':'.$minutes_calculate.':00 ('.$allocated_time.' Mins)';
      $unallocated = $hour_calculate_unallocated.':'.$minutes_calculate_unallocated.':00 ('.$unallocated_time.' Mins)';
      echo json_encode(array("allocated" => $allocated, "unallocated" => $unallocated));
    }
    public function taallocation(Request $request){
          $client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();            
          return view('user/ta_system/allocation', array('title' => 'TA System', 'clientlist' => $client));
    }
    public function taoverview(Request $request){
      $client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();            
          return view('user/ta_system/overview', array('title' => 'TA System', 'clientlist' => $client));
    }
    public function taautoallocation(Request $request){
      $client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();            
      return view('user/ta_system/autoallocation', array('title' => 'TA System', 'clientlist' => $client));
    }
    public function ta_allocation_client_search(Request $request){
          $value = $request->get('term');
          $details = \App\Models\CMClients::where(
              "practice_code",
              Session::get("user_practice_code")
          )
              ->where("status", 0)
              ->where(function ($q) use ($value) {
                  $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
              })->get();
          $data=array();
          foreach ($details as $single) {
                      if($single->company != "")
                      {
                            $company = $single->company;
                      }
                      else{
                            $company = $single->firstname.' & '.$single->surname;
                      }
              $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id,'active_status'=>$single->active);
      }
       if(($data))
           return $data;
      else
          return ['value'=>'No Result Found','id'=>''];
    }
    public function ta_allocation_client_search_result(Request $request){
          $client_id = $request->get('value');
          $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
          $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_id)->first();
          $client_name = $client_details->firstname.' '.$client_details->surname;
          $result_time_job=0;
          if(($joblist)){
            foreach ($joblist as $jobs) {
              $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
              $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
              $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
              $key=0;
              $disable = 0;
              $invoice = 0;
              $class = 'select_task';
              $allocated_class = 'unallocated_row';
              if(($ta_count)){
                // $explode_invoice = explode(',', $ta_count->invoice);
                $unserialize = unserialize($ta_count->tasks);
                if($ta_count->tasks != ''){
                  foreach ($unserialize as $key => $siglelist) {                   
                    if(in_array($jobs->id, $siglelist)){
                      $disable = 'disabled';
                      $invoice = $key;
                      $class = 0;
                      $allocated_class = 'allocated_row';
                    }
                  }
                }
              }
              $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
              //-----------Job Time Start----------------
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              //-----------Job Time End----------------
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
                $tskname = 0;
              }
              $result_time_job.='               
                      <tr class="'.$allocated_class.'">
                        <td><input type="checkbox" name="tasks_job" '.$disable.' class="'.$class.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                        <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                        <td>'.$tskname.'</td>
                        <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                        <td>'.$jobs->job_time.' '.$comments.'</td>
                        <td align="right">'.number_format_invoice_without_decimal($cost).'</td>
                        <td>'.$invoice.'</td>
                      </tr>
              ';
            }              
          }
          else{
            $result_time_job.='
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Empty</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
          }
        $invoicelist = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
        $result_invoice=0;
        if(($invoicelist)){
          foreach ($invoicelist as $invoice) {
            $result_invoice.='
              <tr>
                <td><input type="radio" name="invoice_item" class="invoice_item_class" data-element="'.$invoice->invoice_number.'" name=""><label>&nbsp;</label></td>
                <td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</td>
                <td><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
                <td align="right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>
                <td align="right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
                <td align="right">'.number_format_invoice_without_decimal($invoice->gross).'</td>                  
              </tr>';
          }
        }
        else{
          $result_invoice.='
              <tr>
                <td></td>
                <td></td>
                <td align="right">Empty</td>
                <td></td>
                <td></td>
                <td></td>                  
              </tr>';
        }
        echo json_encode(array('result_time_job' => $result_time_job, 'result_invoice' => $result_invoice, 'client_name' => $client_name ));
    }
    public function tainvoiceupdate(Request $request){
      $client_id = $request->get('client_id');
      $invoice = $request->get('invoice');
      $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      if(($ta_count)){
        $explode = explode(',', $ta_count->invoice);
        if(!in_array($invoice, $explode)){
          if($ta_count->invoice == ""){
            $invoices = $invoice;
          }
          else{
            $invoices = $ta_count->invoice.','.$invoice;
          }
          \App\Models\taClientInvoice::where('client_id', $client_id)->update(['invoice' => $invoices]);
        }
      }
      else{
        $data['client_id'] = $client_id;
        $data['invoice'] = $invoice;
        \App\Models\taClientInvoice::insert($data);
      }
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      if(($invoice_details)){
        $output_invoice='<tr>                  
                <<td><a href="javascript:" class="invoice_class" data-element="'.$invoice_details->invoice_number.'">'.$invoice_details->invoice_number.'</td>
              <td><spam style="display:none">'.strtotime($invoice_details->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice_details->invoice_date)).'</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($invoice_details->vat_value).'</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($invoice_details->gross).'</td>                   
              </tr>';
      }
      $task_details = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      $datas = unserialize($task_details->tasks);
      if($task_details->tasks != ''){
        if(isset($datas[$invoice])){
          $invoice_task = $datas[$invoice];
          $result_invoice_task=0;
          $sub_total_time = 0;      
          $sub_total_cost = 0;
          $task_array = array();
          $task_cost_array = array();
          $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
          $task_total=0;
          $hour_calculate=0;
          $minutes_calculate=0;
          $sub_hour_calculate=0;
          $sub_minutes_calculate=0;
          if(($joblist)){
            foreach ($joblist as $jobs) {       
              if(in_array($jobs->id, $invoice_task)){
                $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
                $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
                $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
                //-----------Job Time Start----------------
                $explode_job_minutes = explode(":",$jobs->job_time);
                $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                //-----------Job Time End----------------
                $rate_result = '0';
                $cost = '0';
                $task_cost = 0;
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
                $sub_total_time = $sub_total_time+$total_minutes;
                $hour_calculate = strtok(($sub_total_time/60), '.');
                $minutes_calculate = $sub_total_time-($hour_calculate*60);
                if($hour_calculate <= 9){
                  $hour_calculate = '0'.$hour_calculate;
                }
                else{
                  $hour_calculate = $hour_calculate;
                }
                if($minutes_calculate <= 9){
                  $minutes_calculate = '0'.$minutes_calculate;
                }
                else{
                  $minutes_calculate = $minutes_calculate;
                }
                $sub_total_cost = $sub_total_cost+$cost;
                if(isset($datausercost['task_'.$jobs->task_id]))
                {
                  $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
                }
                else{
                  $datausercost['task_'.$jobs->task_id] = $cost;
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
                $result_invoice_task.= '
                <tr>
                  <td><input type="checkbox" name="tasks_job" class="select_task_unallocate" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                  <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                  <td>'.$time_task->task_name.'</td>
                  <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                  <td>'.$jobs->job_time.' '.$comments.'</td>
                  <td  style="text-align:right">'.number_format_invoice_without_decimal($cost).'</td>
                  <td>'.$invoice.'</td>
                </tr>';
              }
            }
          }
          if($minutes_calculate != ''){
            $hour_minsutes_second = '
              <td style="border:0px;">Total</td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="width:140px; border:0px; font-weight:800">'.$hour_calculate.':'.$minutes_calculate.':'.'00</td>
              <td style="text-align:right; width:137px; border:0px; font-weight:800">'.number_format_invoice_without_decimal($sub_total_cost).'</td>
              <td style="width:130px; border:0px;"></td>
            ';
          }
          else{
            $hour_minsutes_second = 0;
          }
          $result_invoice_total_row= $hour_minsutes_second;
          $summaryjobs = $datas[$invoice];
          $result_summary=0;
          // $summarjoblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->groupBy('task_id')->get();
          $summarjoblist = \App\Models\taskJob::join('time_task', 'task_job.task_id', '=', 'time_task.id')
                          ->whereIn('task_job.id', $summaryjobs)
                          ->where('task_job.client_id',$client_id)
                          ->where('task_job.status',1)
                          ->select('task_job.*', 'time_task.task_name')
                          ->groupBy('task_job.task_id')                     
                          ->get();
          $sub_total_minutes=0;
          if(($summarjoblist)){
            foreach ($summarjoblist as $jobs) {
              $get_time = \App\Models\taskJob::whereIn('id', $summaryjobs)->get();
              $total_minutes = 0;
              if(($get_time))
              {
                 $rate_result = '0';
                    $cost = '0';
                foreach($get_time as $time)
                {
                  if($jobs->task_id == $time->task_id)
                  {
                    $explode_job_minutes = explode(":",$time->job_time);
                    $total_minutes_1 = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                    $total_minutes = $total_minutes + $total_minutes_1;
                    $ratelist =\App\Models\userCost::where('user_id', $time->user_id)->get();
                    if(($ratelist)){
                      foreach ($ratelist as $rate) {
                        $job_date = strtotime($time->job_date);
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
                  }
                }
              }
              $hour_calculate = strtok(($total_minutes/60), '.');
              $minutes_calculate = $total_minutes-($hour_calculate*60);
              if($hour_calculate <= 9){
                $hour_calculate = '0'.$hour_calculate;
              }
              else{
                $hour_calculate = $hour_calculate;
              }
              if($minutes_calculate <= 9){
                $minutes_calculate = '0'.$minutes_calculate;
              }
              else{
                $minutes_calculate = $minutes_calculate;
              }
              $sub_total_minutes = $sub_total_minutes+$total_minutes;
              $sub_hour_calculate = strtok(($sub_total_minutes/60), '.');
              $sub_minutes_calculate = $sub_total_minutes-($sub_hour_calculate*60);
              if($sub_hour_calculate <= 9){
                $sub_hour_calculate = '0'.$sub_hour_calculate;
              }
              else{
                $sub_hour_calculate = $sub_hour_calculate;
              }
              if($sub_minutes_calculate <= 9){
                $sub_minutes_calculate = '0'.$sub_minutes_calculate;
              }
              else{
                $sub_minutes_calculate = $sub_minutes_calculate;
              }
              if(in_array($jobs->id, $summaryjobs)){  
              $task_total = $task_total+ $datausercost['task_'.$jobs->task_id];         
              if($jobs->comments != "")
              {
                $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
         data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
              }
              else{
                $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
         data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
              }
              $result_summary.='<tr>                  
                      <td>'.$jobs->task_name.'</td>
                      <td>'.$hour_calculate.':'.$minutes_calculate.':00 '.$comments.'</td>
                      <td style="text-align:right">'.number_format_invoice_without_decimal($datausercost['task_'.$jobs->task_id]).'</td>
                      <td><a href="javascript:"><i class="fa fa-download single_summary" data-element="'.$jobs->task_id.'" aria-hidden="true"></i></a></td>
                    </tr>';
              }
            }
          }
          $profit = $invoice_details->inv_net - $task_total;
          if($profit < 0){
            $color = 'color:#f00';
          }
          else{
            $color = 0;
          }
          $gross_profit = ($profit/$invoice_details->inv_net)*100;
          if($gross_profit < 0){
            $gross_color = 'color:#f00';
          }
          else{
            $gross_color = 0;
          }
          if($sub_minutes_calculate != ''){
            $result_summary.='
                      <tr>                  
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>                  
                        <td>Total</td>
                        <td>'.$sub_hour_calculate.':'.$sub_minutes_calculate.':00</td>
                        <td style="text-align:right">'.number_format_invoice_without_decimal($task_total).'</td>
                        <td></td>
                      </tr>
                      <tr>                  
                        <td>Profit/(Loss)</td>
                        <td></td>
                        <td style="'.$color.'; text-align:right">'.number_format_invoice_without_decimal($profit).'</td>
                        <td></td>
                      </tr>
                      <tr>                  
                        <td>Gross Profit %</td>
                        <td></td>
                        <td style="'.$gross_color.'; text-align:right">'.number_format_invoice_without_decimal($gross_profit).' %</td>
                        <td></td>
                      </tr>';
          }
          else{
            $result_invoice_task='
              <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>';
              $result_invoice_total_row= 0;
          }
        }
        else{
          $result_invoice_task='
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
          $result_summary='<tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>  
            <td></td>          
          </tr>';
          $result_invoice_total_row= 0;
        }
      }
      else{
        $result_invoice_task='
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          ';
          $result_summary='<tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>            
          </tr>';
          $result_invoice_total_row= 0;
      }
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      $result_net='
        <tr>                  
                  <td>'.$invoice_details->invoice_number.'</td>
                  <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
                </tr>';
      echo json_encode(array('output_invoice' => $output_invoice, 'result_invoice_task' => $result_invoice_task, 'result_net' => $result_net, 'result_summary' => $result_summary, 'result_invoice_total_row' => $result_invoice_total_row));
    }
    public function tatasksupdate(Request $request){
      $ids = explode(",",$request->get('value'));
      $invoice = $request->get('invoice');
      $client_id = $request->get('client_id');
      $get_tasks = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      if(($get_tasks)){
        if($get_tasks->tasks != ''){          
          $task_val = unserialize($get_tasks->tasks);
          if(isset($task_val[$invoice])){
            $already_task = $task_val[$invoice];
            $array_unique = array_unique(array_merge($already_task,$ids), SORT_REGULAR);
/*            $implode = implode(',', $already_task);
            $exp_id = $implode.','.$request->get('value');
            $implode_val = explode(',', $exp_id);*/
            $task_val[$invoice] = $array_unique;
          }
          else{
            $task_val[$invoice] = $ids;
          }
          $data['tasks'] = serialize($task_val);
        }
        else{
          $array_value = array($invoice => $ids);
          $data['tasks'] = serialize($array_value);          
        }
        \App\Models\taClientInvoice::where('client_id', $client_id)->update($data);
      }
      $task_details = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      $datas = unserialize($task_details->tasks);
      $invoice_task = $datas[$invoice];
      $result_invoice_task=0;
      $sub_total_time = 0;      
      $sub_total_cost = 0;
      $task_array = array();
      $task_cost_array = array();
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $task_total=0;
      if(($joblist)){
          foreach ($joblist as $jobs) {          
          if(in_array($jobs->id, $invoice_task)){
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
          $rate_result = '0';
          $cost = '0';
          $task_cost = 0;
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
          $sub_total_time = $sub_total_time+$total_minutes;
          $hour_calculate = strtok(($sub_total_time/60), '.');
          $minutes_calculate = $sub_total_time-($hour_calculate*60);
          if($hour_calculate <= 9){
            $hour_calculate = '0'.$hour_calculate;
          }
          else{
            $hour_calculate = $hour_calculate;
          }
          if($minutes_calculate <= 9){
            $minutes_calculate = '0'.$minutes_calculate;
          }
          else{
            $minutes_calculate = $minutes_calculate;
          }
          $sub_total_cost = $sub_total_cost+$cost;
          if(isset($datausercost['task_'.$jobs->task_id]))
          {
            $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
          }
          else{
            $datausercost['task_'.$jobs->task_id] = $cost;
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
          $result_invoice_task.= '
          <tr>
            <td><input type="checkbox" name="tasks_job" class="select_task_unallocate" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
            <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
            <td>'.$time_task->task_name.'</td>
            <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
            <td>'.$jobs->job_time.' '.$comments.'</td>
            <td style="text-align:right;">'.number_format_invoice_without_decimal($cost).'</td>
            <td>'.$invoice.'</td>
          </tr>';
        }
      }
    }
      $result_invoice_total_row = '
        <td style="border:0px;">Total</td>
        <td style="border:0px;"></td>
        <td style="border:0px;"></td>
        <td style="border:0px;"></td>
        <td style="border:0px; width:140px;">'.$hour_calculate.':'.$minutes_calculate.':00</td>
        <td style="text-align:right; border:0px; width:140px;">'.number_format_invoice_without_decimal($sub_total_cost).'</td>
        <td  style="border:0px; width:130px;"></td>';
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      $result_net='
        <tr>                  
                  <td>'.$invoice_details->invoice_number.'</td>
                  <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
                </tr>';
      $summaryjobs = $datas[$invoice];
      $result_summary=0;
      $summarjoblist = \App\Models\taskJob::join('time_task', 'task_job.task_id', '=', 'time_task.id')
                      ->whereIn('task_job.id', $summaryjobs)
                      ->where('task_job.client_id',$client_id)
                      ->where('task_job.status',1)
                      ->select('task_job.*', 'time_task.task_name')
                      ->groupBy('task_job.task_id')                     
                      ->get();
      $sub_total_minutes=0;
      if(($summarjoblist)){
        foreach ($summarjoblist as $jobs) {
          $get_time = \App\Models\taskJob::whereIn('id', $summaryjobs)->get();
          $total_minutes = 0;
          if(($get_time))
          {
             $rate_result = '0';
                $cost = '0';
            foreach($get_time as $time)
            {
              if($jobs->task_id == $time->task_id)
              {
                $explode_job_minutes = explode(":",$time->job_time);
                $total_minutes_1 = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                $total_minutes = $total_minutes + $total_minutes_1;
                $ratelist =\App\Models\userCost::where('user_id', $time->user_id)->get();
                if(($ratelist)){
                  foreach ($ratelist as $rate) {
                    $job_date = strtotime($time->job_date);
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
              }
            }
          }
          $hour_calculate = strtok(($total_minutes/60), '.');
          $minutes_calculate = $total_minutes-($hour_calculate*60);
          if($hour_calculate <= 9){
            $hour_calculate = '0'.$hour_calculate;
          }
          else{
            $hour_calculate = $hour_calculate;
          }
          if($minutes_calculate <= 9){
            $minutes_calculate = '0'.$minutes_calculate;
          }
          else{
            $minutes_calculate = $minutes_calculate;
          }
          $sub_total_minutes = $sub_total_minutes+$total_minutes;
          $sub_hour_calculate = strtok(($sub_total_minutes/60), '.');
          $sub_minutes_calculate = $sub_total_minutes-($sub_hour_calculate*60);
          if($sub_hour_calculate <= 9){
            $sub_hour_calculate = '0'.$sub_hour_calculate;
          }
          else{
            $sub_hour_calculate = $sub_hour_calculate;
          }
          if($sub_minutes_calculate <= 9){
            $sub_minutes_calculate = '0'.$sub_minutes_calculate;
          }
          else{
            $sub_minutes_calculate = $sub_minutes_calculate;
          }
          if(in_array($jobs->id, $summaryjobs)){  
          $task_total = $task_total+ $datausercost['task_'.$jobs->task_id];         
          if($jobs->comments != "")
          {
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
          }
          else{
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
          }
          $result_summary.='<tr>                  
                  <td>'.$jobs->task_name.'</td>
                  <td>'.$hour_calculate.':'.$minutes_calculate.':00 '.$comments.'</td>
                  <td  style="text-align:right;">'.number_format_invoice_without_decimal($datausercost['task_'.$jobs->task_id]).'</td>
                  <td><a href="javascript:"><i class="fa fa-download single_summary" data-element="'.$jobs->task_id.'" aria-hidden="true"></i></a></td>
                </tr>';
        }
      }
    }
    $profit = $invoice_details->inv_net - $task_total;
    if($profit < 0){
      $color = 'color:#f00';
    }
    else{
      $color = 0;
    }
    $gross_profit = ($profit/$invoice_details->inv_net)*100;
    if($gross_profit < 0){
      $gross_color = 'color:#f00';
    }
    else{
      $gross_color = 0;
    }
      $result_summary.='
                <tr>                  
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>                  
                  <td>Total</td>
                  <td>'.$sub_hour_calculate.':'.$sub_minutes_calculate.':00</td>
                  <td style="text-align:right;">'.number_format_invoice_without_decimal($task_total).'</td>
                  <td></td>
                </tr>
                <tr>                  
                  <td>Profit/(Loss)</td>
                  <td></td>
                  <td style="'.$color.'; text-align:right;">'.number_format_invoice_without_decimal($profit).'</td>
                  <td></td>
                </tr>
                <tr>                  
                  <td>Gross Profit %</td>
                  <td></td>
                  <td style="'.$gross_color.'; text-align:right;">'.number_format_invoice_without_decimal($gross_profit).' %</td>
                  <td></td>
                </tr>';
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_id)->first();
      $client_name = $client_details->firstname.' '.$client_details->surname;
      $result_time_job=0;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          $key=0;
          $disable = 0;
          $invoice = 0;
          $class = 'select_task';
          $allocated_class = 'unallocated_row';
          if(($ta_count)){
            // $explode_invoice = explode(',', $ta_count->invoice);
            $unserialize = unserialize($ta_count->tasks);
            if($ta_count->tasks != ''){
              foreach ($unserialize as $key => $siglelist) {                   
                if(in_array($jobs->id, $siglelist)){
                  $disable = 'disabled';
                  $invoice = $key;
                  $class = 0;
                  $allocated_class = 'allocated_row';
                }
              }
            }
          }
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
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
          if($jobs->comments != "")
          {
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
          }
          else{
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
          }
          $result_time_job.='               
                  <tr class="'.$allocated_class.'">
                    <td><input type="checkbox" name="tasks_job" '.$disable.' class="'.$class.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$time_task->task_name.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.' '.$comments.'</td>
                    <td style="text-align:right;">'.number_format_invoice_without_decimal($cost).'</td>
                    <td>'.$invoice.'</td>
                  </tr>
          ';
        }              
      }
      else{
        $result_time_job.='
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Empty</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
      }
      echo json_encode(array('result_invoice_task' => $result_invoice_task, 'result_net' => $result_net, 'result_summary' => $result_summary, 'result_time_job' => $result_time_job, 'result_invoice_total_row' => $result_invoice_total_row));
    }
    public function tatasksupdateunallocate(Request $request){
      $ids = explode(",",$request->get('value'));
      $invoice = $request->get('invoice');
      $client_id = $request->get('client_id');
      $ta_client_invoice = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      $unallocated = unserialize($ta_client_invoice->tasks);
      $remove_task = $unallocated[$invoice];
      if(($ids)){
        foreach ($ids as $value) {
          $get_key = array_search($value, $remove_task);
          unset($remove_task[$get_key]);
          $remove_value = array_values($remove_task);
          $unallocated[$invoice] = $remove_value;
        }
      }
      $serialize = serialize($unallocated);
      \App\Models\taClientInvoice::where('client_id', $client_id)->update(['tasks' => $serialize ]);
      $task_details = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      $datas = unserialize($task_details->tasks);
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      if($task_details->tasks != ''){
        if(isset($datas[$invoice])){
          $invoice_task = $datas[$invoice];
          $result_invoice_task=0;
          $sub_total_time = 0;      
          $sub_total_cost = 0;
          $hour_calculate=0;
          $minutes_calculate=0;
          $sub_hour_calculate=0;
          $sub_minutes_calculate=0;
          $task_array = array();
          $task_cost_array = array();
          $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
          $task_total=0;
          if(($joblist)){
              foreach ($joblist as $jobs) {          
              if(in_array($jobs->id, $invoice_task)){
              $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
              $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
              $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
              //-----------Job Time Start----------------
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              //-----------Job Time End----------------
              $rate_result = '0';
              $cost = '0';
              $task_cost = 0;
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
              $sub_total_time = $sub_total_time+$total_minutes;
              $hour_calculate = strtok(($sub_total_time/60), '.');
              $minutes_calculate = $sub_total_time-($hour_calculate*60);
              if($hour_calculate <= 9){
                $hour_calculate = '0'.$hour_calculate;
              }
              else{
                $hour_calculate = $hour_calculate;
              }
              if($minutes_calculate <= 9){
                $minutes_calculate = '0'.$minutes_calculate;
              }
              else{
                $minutes_calculate = $minutes_calculate;
              }
              $sub_total_cost = $sub_total_cost+$cost;
              if(isset($datausercost['task_'.$jobs->task_id]))
              {
                $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
              }
              else{
                $datausercost['task_'.$jobs->task_id] = $cost;
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
              $result_invoice_task.= '
              <tr>
                <td><input type="checkbox" name="tasks_job" class="select_task_unallocate" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                <td>'.$time_task->task_name.'</td>
                <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                <td>'.$jobs->job_time.' '.$comments.'</td>
                <td style="text-align:right">'.number_format_invoice_without_decimal($cost).'</td>
                <td>'.$invoice.'</td>
              </tr>';
            }
          }
        }
        if($minutes_calculate != ''){
          $hour_minsutes_second = '
          <td style="border:0px;">Total</td>
          <td style="border:0px;"></td>
          <td style="border:0px;"></td>
          <td style="border:0px;"></td>
          <td style="border:0px; width:140px; font-weight:800">'.$hour_calculate.':'.$minutes_calculate.':'.'00</td>
          <td style="text-align:right; border:0px; width:140px; font-weight:800">'.number_format_invoice_without_decimal($sub_total_cost).'</td>
          <td style="border:0px; width:130px;"></td>
        ';
        }
        else{
          $hour_minsutes_second = 0;
        }
        $result_invoice_total_row= $hour_minsutes_second;
        $summaryjobs = $datas[$invoice];
        $result_summary=0;
        // $summarjoblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->groupBy('task_id')->get();
        $summarjoblist = \App\Models\taskJob::join('time_task', 'task_job.task_id', '=', 'time_task.id')
                        ->whereIn('task_job.id', $summaryjobs)
                        ->where('task_job.client_id',$client_id)
                        ->where('task_job.status',1)
                        ->select('task_job.*', 'time_task.task_name')
                        ->groupBy('task_job.task_id')                     
                        ->get();
        $sub_total_minutes=0;
        if(($summarjoblist)){
          foreach ($summarjoblist as $jobs) {
            $get_time = \App\Models\taskJob::whereIn('id', $summaryjobs)->get();
            $total_minutes = 0;
            if(($get_time))
            {
               $rate_result = '0';
                  $cost = '0';
              foreach($get_time as $time)
              {
                if($jobs->task_id == $time->task_id)
                {
                  $explode_job_minutes = explode(":",$time->job_time);
                  $total_minutes_1 = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                  $total_minutes = $total_minutes + $total_minutes_1;
                  $ratelist =\App\Models\userCost::where('user_id', $time->user_id)->get();
                  if(($ratelist)){
                    foreach ($ratelist as $rate) {
                      $job_date = strtotime($time->job_date);
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
                }
              }
            }
            $hour_calculate = strtok(($total_minutes/60), '.');
            $minutes_calculate = $total_minutes-($hour_calculate*60);
            if($hour_calculate <= 9){
              $hour_calculate = '0'.$hour_calculate;
            }
            else{
              $hour_calculate = $hour_calculate;
            }
            if($minutes_calculate <= 9){
              $minutes_calculate = '0'.$minutes_calculate;
            }
            else{
              $minutes_calculate = $minutes_calculate;
            }
            $sub_total_minutes = $sub_total_minutes+$total_minutes;
            $sub_hour_calculate = strtok(($sub_total_minutes/60), '.');
            $sub_minutes_calculate = $sub_total_minutes-($sub_hour_calculate*60);
            if($sub_hour_calculate <= 9){
              $sub_hour_calculate = '0'.$sub_hour_calculate;
            }
            else{
              $sub_hour_calculate = $sub_hour_calculate;
            }
            if($sub_minutes_calculate <= 9){
              $sub_minutes_calculate = '0'.$sub_minutes_calculate;
            }
            else{
              $sub_minutes_calculate = $sub_minutes_calculate;
            }
            if(in_array($jobs->id, $summaryjobs)){  
            $task_total = $task_total+ $datausercost['task_'.$jobs->task_id];         
            if($jobs->comments != "")
            {
              $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
       data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
            }
            else{
              $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
       data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
            }
            $result_summary.='<tr>                  
                    <td>'.$jobs->task_name.'</td>
                    <td>'.$hour_calculate.':'.$minutes_calculate.':00 '.$comments.'</td>
                    <td style="text-align:right">'.number_format_invoice_without_decimal($datausercost['task_'.$jobs->task_id]).'</td>
                    <td><a href="javascript:"><i class="fa fa-download single_summary" data-element="'.$jobs->task_id.'" aria-hidden="true"></i></a></td>
                  </tr>';
          }
        }
      }
      $profit = $invoice_details->inv_net - $task_total;
      if($profit < 0){
        $color = 'color:#f00';
      }
      else{
        $color = 0;
      }
      $gross_profit = ($profit/$invoice_details->inv_net)*100;
      if($gross_profit < 0){
        $gross_color = 'color:#f00';
      }
      else{
        $gross_color = 0;
      }
        $result_summary.='
                  <tr>                  
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>                  
                    <td>Total</td>
                    <td>'.$sub_hour_calculate.':'.$sub_minutes_calculate.':00</td>
                    <td style="text-align:right">'.number_format_invoice_without_decimal($task_total).'</td>
                    <td></td>
                  </tr>
                  <tr>                  
                    <td>Profit/(Loss)</td>
                    <td></td>
                    <td style="'.$color.'; text-align:right">'.number_format_invoice_without_decimal($profit).'</td>
                    <td></td>
                  </tr>
                  <tr>                  
                    <td>Gross Profit %</td>
                    <td></td>
                    <td style="'.$gross_color.'; text-align:right">'.number_format_invoice_without_decimal($gross_profit).' %</td>
                    <td></td>
                  </tr>';
        }
        else{
          $result_invoice_task='
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>';
          $result_summary='<tr>
            <td></td>
            <td></td>
            <td></td>  
            <td></td>          
          </tr>';
          $result_invoice_total_row='
          <td>&nbsp;</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          ';
        }
      }
      else{
        $result_invoice_task='
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          ';
          $result_summary='<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>            
          </tr>';
          $result_invoice_total_row='<td>&nbsp;</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>';
      }
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      $result_net='
        <tr>                  
                  <td>'.$invoice_details->invoice_number.'</td>
                  <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
                </tr>';
      $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_id)->first();
      $client_name = $client_details->firstname.' '.$client_details->surname;
      $result_time_job=0;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          $key=0;
          $disable = 0;
          $invoice = 0;
          $class = 'select_task';
          $allocated_class = 'unallocated_row';
          if(($ta_count)){
            // $explode_invoice = explode(',', $ta_count->invoice);
            $unserialize = unserialize($ta_count->tasks);
            if($ta_count->tasks != ''){
              foreach ($unserialize as $key => $siglelist) {                   
                if(in_array($jobs->id, $siglelist)){
                  $disable = 'disabled';
                  $invoice = $key;
                  $class = 0;
                  $allocated_class = 'allocated_row';
                }
              }
            }
          }
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
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
          if($jobs->comments != "")
          {
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="'.$jobs->comments.'"></a>';
          }
          else{
            $comments = '<a href="javascript:" class="fa fa-comment" data-trigger="focus"
     data-toggle="popover" data-placement="bottom" data-content="No Comments found"></a>';
          }
          $result_time_job.='               
                  <tr class="'.$allocated_class.'">
                    <td><input type="checkbox" name="tasks_job" '.$disable.' class="'.$class.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$time_task->task_name.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.' '.$comments.'</td>
                    <td style="text-align:right">'.number_format_invoice_without_decimal($cost).'</td>
                    <td>'.$invoice.'</td>
                  </tr>
          ';
        }              
      }
      else{
        $result_time_job.='
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Empty</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
      }
      echo json_encode(array('result_invoice_task' => $result_invoice_task, 'result_net' => $result_net, 'result_summary' => $result_summary, 'result_time_job' => $result_time_job, 'result_invoice_total_row' => $result_invoice_total_row));
    }
    public function ta_overview_client_search(Request $request){
      $value = $request->get('term');
      $details = \App\Models\CMClients::where(
            "practice_code",
            Session::get("user_practice_code")
        )
            ->where("status", 0)
            ->where(function ($q) use ($value) {
                $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
            })->get();
      $data=array();
      foreach ($details as $single) {
                  if($single->company != "")
                  {
                        $company = $single->company;
                  }
                  else{
                        $company = $single->firstname.' & '.$single->surname;
                  }
          $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id,'active_status'=>$single->active);
      }
       if(($data))
           return $data;
      else
          return ['value'=>'No Result Found','id'=>''];
    }
    public function ta_overview_client_search_result(Request $request){
      $client_id = $request->get('value');
      /*$invoices = \App\Models\taClientInvoice::where('client_id', $client_id)->first();*/
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $result_unallocated_time_job=0;
      $unallocated_total=0;
      $sub_hour_calculate_unallocated=0;
      $sub_minutes_calculate_unallocated=0;
      $sub_total_minutes_unallocated=0;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
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
         /* $datas = unserialize($invoices->tasks);         
          $invoice_task = $datas[$invoice];
*/
         /* if(!in_array($jobs->id, $invoice_task)){*/
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          /*$key=0;
          $disable = 0;
          $invoice = 0;
          $class = 'select_task';*/
          if(($ta_count)){
            // $explode_invoice = explode(',', $ta_count->invoice);
            $unserialize = unserialize($ta_count->tasks);
           /* if($ta_count->tasks != ''){
              foreach ($unserialize as $key => $siglelist) {                   
                if(in_array($jobs->id, $siglelist)){*/
                  /*if(array_search($jobs->id, array_column($unserialize)) === False) {*/
                    $filter_jobs = strrpos($ta_count->tasks, '"'.$jobs->id.'"');
                    if ($filter_jobs === false) { // note: three equal signs
                        // not found...
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
          $result_unallocated_time_job.='               
                  <tr>                    
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$time_task->task_name.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.' '.$comments.'</td>
                    <td align="right">'.number_format_invoice_without_decimal($cost).'</td>                    
                  </tr>
          ';
        /*}  */   
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
            $tskname = 0;
          }
          $result_unallocated_time_job.='               
                  <tr>                    
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$tskname.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.' '.$comments.'</td>
                    <td align="right">'.number_format_invoice_without_decimal($cost).'</td>                    
                  </tr>
          ';
        /*}  */  
        }
      }
      $result_unallocated_time_job.='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>'.$sub_hour_calculate_unallocated.':'.$sub_minutes_calculate_unallocated.':00</td>
        <td align="right">'.number_format_invoice_without_decimal($unallocated_total).'</td>        
      </tr>
      ';
    }
              /*}
            }
          }*/
      else{
        $result_unallocated_time_job.='
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Empty</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
      }
      $invoice_list = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
      $result_invoice_total=0;
      $result_invoice_cross=0;
      $result_invoice_vat=0;
      $result_invoice_net=0;
      $result_invoice_profit=0;
      $result_invoice_percentage=0;        
      $result_invoice=0;
        if(($invoice_list)){
          foreach ($invoice_list as $key => $invoice) {   
            $invoices = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
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
                $jobs = \App\Models\taskJob::where('id', $single_task)->first();                               
                if(($jobs)){
                    $explode_job_minutes = explode(":",$jobs->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                    $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
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
              $color = 0;
            }
            $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
            if(($ta_excluded)){
              $explode_excluded = explode(',', $ta_excluded->excluded_invoice);
            }
            else{
              $explode_excluded = array();
            }
            if(!in_array($invoice->invoice_number, $explode_excluded)){
              $invoice_count = ($invoice_list)-count($explode_excluded);
            $result_invoice.='<tr>
              <td style="'.$color.'"><a href="javascript:" data-element="'.$invoice->invoice_number.'" class="invoice_class">'.$invoice->invoice_number.'</a></td>
              <td style="'.$color.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->gross).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>        
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice_total_cost).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($profit).'</td>
              <td style="'.$color.'" >'.number_format_invoice_without_decimal($profit_percentage).' %</td>
              <td style=""  align="center">
              '.$excluded.'&nbsp;&nbsp;
              <a href="javascript:" title="Review"><i class="fa fa-files-o review_class" data-element="'.$invoice->invoice_number.'"></i></a>
              </td>
            </tr>'; 
            $result_invoice_cross = $result_invoice_cross+$invoice->gross;
            $result_invoice_vat = $result_invoice_vat+$invoice->vat_value;
            $result_invoice_net = $result_invoice_net+$invoice->inv_net;
            $result_invoice_total = $result_invoice_total+$invoice_total_cost;
            $result_invoice_profit = $result_invoice_profit+$profit;
            $result_invoice_percentage = $result_invoice_percentage+$profit_percentage;
            $result_final_percentage = $result_invoice_percentage/$invoice_count;
          }
          }
          $result_final_percentage = ($result_invoice_profit / $result_invoice_net) * 100;
          $result_invoice.='
              <tr>                  
                <td></td>
                <td></td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_cross).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_vat).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_net).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_total).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_profit).'</td>
                <td>'.number_format_invoice_without_decimal($result_final_percentage).' %</td>
                <td></td>
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
            <td style="text-align: right;">0.00</td>
          </tr>';
      }
      $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
      if(($ta_excluded))
      {
        if($ta_excluded->excluded_invoice != ""){
            $invoicelist = explode(',', $ta_excluded->excluded_invoice);
            $total_excluded_gross=0;
            $total_excluded_vat=0;
            $total_excluded_net=0;
            $result_excluded=0;
            if(($invoicelist)){
              foreach ($invoicelist as $key => $invoice) {
                $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
                $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
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
                  <td></td>
                  <td></td>
                  <td align="right">'.number_format_invoice_without_decimal($total_excluded_gross).'</td>
                  <td align="right">'.number_format_invoice_without_decimal($total_excluded_vat).'</td>
                  <td align="right">'.number_format_invoice_without_decimal($total_excluded_net).'</td>
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
      echo json_encode(array('result_invoice' => $result_invoice, 'result_summary' => $result_summary, 'result_unallocated_time_job' => $result_unallocated_time_job, 'result_excluded' => $result_excluded));
    }
    public function taoverviewinvoice(Request $request){
      $invoice = $request->get('invoice');
      $client_id = $request->get('client_id');
      $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
      $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
      $unserialize_ta = unserialize($ta_count->tasks);
      if(isset($unserialize_ta[$invoice])){
        $unserialize = $unserialize_ta[$invoice];
      }
      else{
        $unserialize = array();
      }
      $invoice_total_cost='0';
      if(($unserialize)){              
        foreach ($unserialize as $single_task) {                
          $jobsingle = \App\Models\taskJob::where('id', $single_task)->first();                               
          if(($jobsingle)){
            /*foreach ($joblist as $jobsingle) {*/
              $explode_job_minutes = explode(":",$jobsingle->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              $ratelist =\App\Models\userCost::where('user_id', $jobsingle->user_id)->get();
              $rate_result = '0';
              $cost = '0';
              if(($ratelist)){
                foreach ($ratelist as $rate) {
                  $job_date = strtotime($jobsingle->job_date);
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
            /*}  */                
          }
        }
      }
      else{
        $invoice_total_cost = 0;
      }
      $profit = $invoice_details->inv_net-$invoice_total_cost;
      if($profit < 0){
        $color = 'color:#f00';
      }
      else{
        $color = 0;
      }
      $profit_percentage = ($profit/$invoice_details->inv_net)*100;
      $result_invoice_single='
          <tr>
            <td>'.$invoice.'</td>
            <td><spam style="display:none">'.strtotime($invoice_details->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice_details->invoice_date)).'</td>
            <td align="right">'.number_format_invoice_without_decimal($invoice_details->gross).'</td>            
            <td align="right">'.number_format_invoice_without_decimal($invoice_details->vat_value).'</td>
            <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
          </tr>
          <tr>                  
            <td>Total Cost</td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">'.number_format_invoice_without_decimal($invoice_total_cost).'</td>                  
          </tr>
          <tr>                  
            <td>Profit / Loss</td>
            <td></td>
            <td></td>
            <td></td>
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($profit).'</td>                  
          </tr>
          <tr>                  
            <td>Profit / Loss %</td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">'.number_format_invoice_without_decimal($profit_percentage).' %</td>                  
          </tr>
          ';
    $datausertimeval = array();
      $datausercostval = array();
    if(isset($unserialize)){
      $result_cost_analysis=0;
      $sub_total_time = 0;      
      $sub_total_cost = 0;
      $task_array = array();
      $task_cost_array = array();
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $task_total=0;
      $hour_calculate=0;
      $minutes_calculate=0;
      $sub_hour_calculate=0;
      $sub_minutes_calculate=0;
      if(($joblist)){
          foreach ($joblist as $jobs) {          
          if(in_array($jobs->id, $unserialize)){
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
          $rate_result = '0';
          $cost = '0';
          $task_cost = 0;
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
          $sub_total_time = $sub_total_time+$total_minutes;
          $hour_calculate = strtok(($sub_total_time/60), '.');
          $minutes_calculate = $sub_total_time-($hour_calculate*60);
          if($hour_calculate <= 9){
            $hour_calculate = '0'.$hour_calculate;
          }
          else{
            $hour_calculate = $hour_calculate;
          }
          if($minutes_calculate <= 9){
            $minutes_calculate = '0'.$minutes_calculate;
          }
          else{
            $minutes_calculate = $minutes_calculate;
          }
          $sub_total_cost = $sub_total_cost+$cost;
          if(isset($datausercost['task_'.$jobs->task_id]))
          {
            $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
          }
          else{
            $datausercost['task_'.$jobs->task_id] = $cost;
          }
          if(isset($datausertimeval['user_'.$jobs->user_id]))
          {
            $datausertimeval['user_'.$jobs->user_id] = $datausertimeval['user_'.$jobs->user_id] + $total_minutes;
          }
          else{
            $datausertimeval['user_'.$jobs->user_id] = $total_minutes;
          }
          if(isset($datausercostval['usercost_'.$jobs->user_id]))
          {
            $datausercostval['usercost_'.$jobs->user_id] = $datausercostval['usercost_'.$jobs->user_id] + $cost;
          }
          else{
            $datausercostval['usercost_'.$jobs->user_id] = $cost;
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
          $result_cost_analysis.= '
          <tr>
            <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
            <td>'.$user_details->lastname.' '.$user_details->firstname.'</td>
            <td>'.$rate_result.'</td>
            <td>'.$time_task->task_name.'</td>            
            <td>'.$jobs->job_time.' '.$comments.'</td>
            <td align="right">'.number_format_invoice_without_decimal($cost).'</td>            
          </tr>';
        }
      }
    }
    if($minutes_calculate != ''){
      $hour_minsutes_second = '
      <tr>
      <td>Total</td>
      <td></td>      
      <td></td>
      <td></td>
      <td>'.$hour_calculate.':'.$minutes_calculate.':'.'00</td>
      <td align="right">'.number_format_invoice_without_decimal($sub_total_cost).'</td>      
    </tr>';
    }
    else{
      $hour_minsutes_second = 0;
      $result_cost_analysis= '
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td align="right">Empty</td>
      <td></td>      
      <td></td>
      <td></td>      
    </tr>';
    }
    $result_cost_analysis.= '
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>      
    </tr>
    '.$hour_minsutes_second;
    $summaryjobs = $unserialize;
    $result_cost_analysis_task=0;
    // $summarjoblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->groupBy('task_id')->get();
    $summarjoblist = \App\Models\taskJob::join('time_task', 'task_job.task_id', '=', 'time_task.id')
                    ->whereIn('task_job.id', $summaryjobs)
                    ->where('task_job.client_id',$client_id)
                    ->where('task_job.status',1)
                    ->select('task_job.*', 'time_task.task_name')
                    ->groupBy('task_job.task_id')                     
                    ->get();
    $sub_total_minutes=0;
    if(($summarjoblist)){
      foreach ($summarjoblist as $singlesummaryjobs) {
        $get_time = \App\Models\taskJob::whereIn('id', $summaryjobs)->get();
        $total_minutes = 0;
        if(($get_time))
        {
           $rate_result = '0';
              $cost = '0';
          foreach($get_time as $time)
          {
            if($singlesummaryjobs->task_id == $time->task_id)
            {
              $explode_job_minutes = explode(":",$time->job_time);
              $total_minutes_1 = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              $total_minutes = $total_minutes + $total_minutes_1;
              $ratelist =\App\Models\userCost::where('user_id', $time->user_id)->get();
              if(($ratelist)){
                foreach ($ratelist as $rate) {
                  $job_date = strtotime($time->job_date);
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
            }
          }
        }
        $hour_calculate = strtok(($total_minutes/60), '.');
        $minutes_calculate = $total_minutes-($hour_calculate*60);
        if($hour_calculate <= 9){
          $hour_calculate = '0'.$hour_calculate;
        }
        else{
          $hour_calculate = $hour_calculate;
        }
        if($minutes_calculate <= 9){
          $minutes_calculate = '0'.$minutes_calculate;
        }
        else{
          $minutes_calculate = $minutes_calculate;
        }
        $sub_total_minutes = $sub_total_minutes+$total_minutes;
        $sub_hour_calculate = strtok(($sub_total_minutes/60), '.');
        $sub_minutes_calculate = $sub_total_minutes-($sub_hour_calculate*60);
        if($sub_hour_calculate <= 9){
          $sub_hour_calculate = '0'.$sub_hour_calculate;
        }
        else{
          $sub_hour_calculate = $sub_hour_calculate;
        }
        if($sub_minutes_calculate <= 9){
          $sub_minutes_calculate = '0'.$sub_minutes_calculate;
        }
        else{
          $sub_minutes_calculate = $sub_minutes_calculate;
        }
        if(in_array($singlesummaryjobs->id, $summaryjobs)){  
        $task_total = $task_total+ $datausercost['task_'.$singlesummaryjobs->task_id];         
        $result_cost_analysis_task.='<tr>                  
                <td>'.$singlesummaryjobs->task_name.'</td>
                <td>'.$hour_calculate.':'.$minutes_calculate.':00</td>
                <td align="right">'.number_format_invoice_without_decimal($datausercost['task_'.$singlesummaryjobs->task_id]).'</td>                
              </tr>';
      }
    }
  }
  else{
    $result_cost_analysis_task='
    <tr>
      <td></td>
      <td align="center">Empty</td>
      <td></td>      
    </tr>
    ';
  }
  $userjoblist = \App\Models\taskJob::join('user', 'task_job.user_id', '=', 'user.user_id')
                    ->whereIn('task_job.id', $summaryjobs)
                    ->where('task_job.client_id',$client_id)
                    ->where('task_job.status',1)
                    ->select('task_job.*', 'user.lastname',  'user.firstname')
                    ->groupBy('user.user_id')                     
                    ->get();
  $result_cost_analysis_user=0;
  $usertasksubtimetotal = 0;
  $usertasksubcosttotal = 0;
   if(($userjoblist)){
    foreach ($userjoblist as $usersingle) {
      /*$get_time = \App\Models\taskJob::whereIn('id', $summaryjobs)->get();
      $user_total_minutes = 0;
      if(($get_time))
      {
         $rate_result = '0';
            $cost = '0';
        foreach($get_time as $time)
        {
          if($usersingle->user_id == $time->user_id)
          {
            $explode_job_minutes = explode(":",$time->job_time);
            $total_minutes_1 = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
            $total_minutes = $total_minutes + $total_minutes_1;
            $ratelist =\App\Models\userCost::where('user_id', $time->user_id)->get();
            if(($ratelist)){
              foreach ($ratelist as $rate) {
                $job_date = strtotime($time->job_date);
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
            $user_total_minutes = $user_total_minutes+$total_minutes;
          }
        }
      }*/
      /*$hour_calculate = strtok(($total_minutes/60), '.');
      $minutes_calculate = $total_minutes-($hour_calculate*60);
      if($hour_calculate <= 9){
        $hour_calculate = '0'.$hour_calculate;
      }
      else{
        $hour_calculate = $hour_calculate;
      }
      if($minutes_calculate <= 9){
        $minutes_calculate = '0'.$minutes_calculate;
      }
      else{
        $minutes_calculate = $minutes_calculate;
      }*/
      $task_time_total = $datausertimeval['user_'.$usersingle->user_id];
      $usertasksubtimetotal = $usertasksubtimetotal + $task_time_total;
      $usertasksubcosttotal = $usertasksubcosttotal + $datausercostval['usercost_'.$usersingle->user_id];
      $hour_calculate = strtok(($task_time_total/60), '.');
      $minutes_calculate = $task_time_total-($hour_calculate*60);
      if($hour_calculate <= 9){
        $hour_calculate = '0'.$hour_calculate;
      }
      else{
        $hour_calculate = $hour_calculate;
      }
      if($minutes_calculate <= 9){
        $minutes_calculate = '0'.$minutes_calculate;
      }
      else{
        $minutes_calculate = $minutes_calculate;
      }
      $result_cost_analysis_user.='<tr>
      <td>'.$usersingle->lastname.' '.$usersingle->firstname.'</td>
      <td>'.$hour_calculate.':'.$minutes_calculate.':00</td>
      <td align="right">'.number_format_invoice_without_decimal($datausercostval['usercost_'.$usersingle->user_id]).'</td>
      </tr>';
    }
    $usertotal_sub_hour_calculate = strtok(($usertasksubtimetotal/60), '.');
    $usertotal_sub_minutes_calculate = $usertasksubtimetotal-($usertotal_sub_hour_calculate*60);
    if($usertotal_sub_hour_calculate <= 9){
      $usertotal_sub_hour_calculate = '0'.$usertotal_sub_hour_calculate;
    }
    else{
      $usertotal_sub_hour_calculate = $usertotal_sub_hour_calculate;
    }
    if($usertotal_sub_minutes_calculate <= 9){
      $usertotal_sub_minutes_calculate = '0'.$usertotal_sub_minutes_calculate;
    }
    else{
      $usertotal_sub_minutes_calculate = $usertotal_sub_minutes_calculate;
    }
    $result_cost_analysis_user.='
    <tr>
      <td></td>
      <td>'.$usertotal_sub_hour_calculate.':'.$usertotal_sub_minutes_calculate.':00</td>
      <td align="right">'.number_format_invoice_without_decimal($usertasksubcosttotal).'</td>
    </tr>
    ';
   }
   else{
    $result_cost_analysis_user='<tr><td></td><td align="center">Empty</td><td></td></tr>';
   }
    }
      echo json_encode(array('result_invoice_single' => $result_invoice_single , 'result_cost_analysis' => $result_cost_analysis, 'result_cost_analysis_task' => $result_cost_analysis_task, 'result_cost_analysis_user' => $result_cost_analysis_user));
  }
  public function ta_autoalloaction_client_search(Request $request){
          $value = $request->get('term');
          $details = \App\Models\CMClients::where(
              "practice_code",
              Session::get("user_practice_code")
          )
              ->where("status", 0)
              ->where(function ($q) use ($value) {
                  $q->where("client_id", "like", "%" . $value . "%")->orWhere("company", "like", "%" . $value . "%");
              })->get();
          $data=array();
          foreach ($details as $single) {
                      if($single->company != "")
                      {
                            $company = $single->company;
                      }
                      else{
                            $company = $single->firstname.' & '.$single->surname;
                      }
              $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id,'active_status'=>$single->active);
      }
       if(($data))
           return $data;
      else
          return ['value'=>'No Result Found','id'=>''];
    }
    public function ta_autoalloaction_client_search_result(Request $request){
      $client_id = $request->get('value');
      $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $client_id)->first();
      $invoicelist = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
      $result_invoice=0;
      if(($invoicelist)){
        foreach ($invoicelist as $invoice) {
          $result_invoice.='
          <tr>
            <td style="width:50px;"><input type="radio" name="invoice_item" class="select_invoice" data-element="'.$invoice->invoice_number.'" /><label>&nbsp;</label></td>
            <td><a href="javascript:" data-element="'.$invoice->invoice_number.'" class="invoice_class">'.$invoice->invoice_number.'</a></td>
            <td><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
            <td align="right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>
            <td align="right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>            
            <td align="right">'.number_format_invoice_without_decimal($invoice->gross).'</td>            
          </tr>
          ';
        }
      }
      else{
        $result_invoice='
        <tr>
          <td></td>
          <td></td>
          <td align="right">Empty Invoice</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        ';
      }
      $company = 'to'. $client_details->company;
      echo json_encode(array('result_invoice' => $result_invoice, 'company_name' => $company));
  }
  public function taautoallocationinvoice(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
    if(($invoice_details)){
      $result_invoice_single ='
      <tr>
        <td><a href="javascript:" data-element="'.$invoice_details->invoice_number.'" class="invoice_class">'.$invoice_details->invoice_number.'</a></td>
        <td><spam style="display:none">'.strtotime($invoice_details->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice_details->invoice_date)).'</td>
        <td align="right">'.number_format_invoice_without_decimal($invoice_details->inv_net).'</td>
        <td align="right">'.number_format_invoice_without_decimal($invoice_details->vat_value).'</td>            
        <td align="right">'.number_format_invoice_without_decimal($invoice_details->gross).'</td>  
      </tr>';
    }
    else{
      $result_invoice_single ='
        <tr>
          <td></td>
          <td></td>
          <td align="center">Empty</td>
          <td></td>
          <td></td>
        </tr>';
    }
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $time_task_client = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client_id.'%')->orderBy('task_name', 'asc')->get();
    $result_time_task=0;
    if(($time_task_client)){
      foreach ($time_task_client as $time_task) {
        if(isset($ta_count->auto_tasks)){
            $filter_task = strrpos($ta_count->auto_tasks, '"'.$time_task->id.'"');
            if ($filter_task === false) {
            $result_time_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
              <td>'.$time_task->task_name.'</td>
            </tr>
            ';
          }
        }
        else{
          $result_time_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
            <td>'.$time_task->task_name.'</td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_time_task='
      <tr>
        <td colspan="2" align="center">Empty</td>
      </tr>
      ';
    }
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();    
    $result_allocated_task=0;
    if(isset($ta_count->auto_tasks)){  
      $unserialize = unserialize($ta_count->auto_tasks);    
      if(isset($unserialize[$invoice])){
        $taskslist = $unserialize[$invoice];
        if(($taskslist)){
          foreach ($taskslist as $single_tasks) {
            $task_details = \App\Models\timeTask::where('id', $single_tasks)->first();
            $result_allocated_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item_unallocated" value="'.$single_tasks.'" data-element="'.$single_tasks.'" /><label>&nbsp;</label></td>
              <td>'.$task_details->task_name.'</td>
              <td align="center"><a href="javascript:" data-element="'.$single_tasks.'" title="Allocate all unallocated tasks to this invoice" class="fa fa-check at_auto_allocation_yes" aria-hidden="true"></a></td>
            </tr>
            ';
          }
        }
        else{
          $result_allocated_task='
          <tr>
            <td colspan="3" align="center">Empty</td>
          </tr>
          ';
        }
      }
      else{
        $result_allocated_task='
        <tr>
          <td colspan="3" align="center">Empty</td>
        </tr>
        ';
      }
    }
    else{
      $result_allocated_task='
      <tr>
        <td colspan="3" align="center">Empty</td>
      </tr>
      ';
    }
    $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
    $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $invoice_details->client_id)->first();
    if(($client_details) == ''){
      $companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
      echo json_encode(array('companyname' => $companyname, 'result_invoice_single' => $result_invoice_single, 'result_time_task' => $result_time_task, 'result_allocated_task' => $result_allocated_task));
    }
    else{
      $company_firstname='
          <div class="company_details_div">
                <div class="firstname_div">
                  <b>To:</b><br/>
                  '.$client_details->firstname.' '.$client_details->surname.'<br/>  
                  '.$client_details->company.'<br/>  
                  '.$client_details->address1.'<br/>  
                  '.$client_details->address2.'<br/>
                  '.$client_details->address3.'
                </div>
              </div>
              <div class="account_details_div">
                <div class="account_details_main_address_div">
                    <div class="aib_account">
                      AIB Account: 48870061<br/>
                      Sort Code: 93-72-23<br/>
                      VAT Number: 9754009E<br/>
                      Company Number: 485123
                  </div>                   
                    <div class="account_details_invoice_div">
                      <div class="account_table">
                        <div class="account_row">
                          <div class="account_row_td left"><b>Account:</b></div>
                          <div class="account_row_td right">'.$client_details->client_id.'</div>
                        </div>
                        <div class="account_row">
                          <div class="account_row_td left"><b>Invoice:</b></div>
                          <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
                        </div>
                        <div class="account_row">
                          <div class="account_row_td left"><b>Date:</b></div>
                          <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="invoice_label">
              INVOICE
            </div>';
            if($invoice_details->bn_row1 != "")
            {
              $bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
            }
            else{
              $bn_row1_add_zero = 0;
            }
              $row1 = '
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';
           if($invoice_details->bo_row2 != "")
           {
            $bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
           }
           else{
            $bo_row2_add_zero = 0;
           }
              $row2 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
            if($invoice_details->bp_row3 != "")
            {
              $bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
            }
            else{
              $bp_row3_add_zero = 0;
            }
              $row3 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
            if($invoice_details->bq_row4 != "")
            {
              $bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
            }
            else{
              $bq_row4_add_zero = 0;
            }
              $row4 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
           if($invoice_details->br_row5 != "")
           {
            $br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
           }
           else{
            $br_row5_add_zero = 0;
           }
              $row5 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
            if($invoice_details->bs_row6 != "")
            {
              $bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
            }
            else{
              $bs_row6_add_zero = 0;
            }
              $row6 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
            if($invoice_details->bt_row7 != "")
            {
              $bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
            }
            else{
              $bt_row7_add_zero = 0;
            }
              $row7 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';
            if($invoice_details->bu_row8 != "")
            {
              $bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
            }
            else{
              $bu_row8_add_zero = 0;
            }
              $row8 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
            if($invoice_details->bv_row9 != "")
            {
              $bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
            }
            else{
              $bv_row9_add_zero = 0;
            }
              $row9 = '             
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
           if($invoice_details->bw_row10 != "")
           {
            $bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
           }
           else{
            $bw_row10_add_zero = 0;
           }
              $row10 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
            if($invoice_details->bx_row11 != "")
            {
              $bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
            }
            else{
              $bx_row11_add_zero = 0;
            }
              $row11 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
           if($invoice_details->by_row12 != "")
           {
            $by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
           }
           else{
            $by_row12_add_zero = 0;
           }
              $row12 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
            if($invoice_details->bz_row13 != "")
            {
              $bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
            }
            else{
              $bz_row13_add_zero = 0;
            }
              $row13 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
            if($invoice_details->ca_row14 != "")
            {
              $ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
            }
            else{
              $ca_row14_add_zero = 0;
            }
              $row14 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
            if($invoice_details->cb_row15 != "")
            {
              $cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
            }
            else{
              $cb_row15_add_zero = 0;
            }
              $row15 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
           if($invoice_details->cc_row16 != "")
           {
            $cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
           }
           else{
            $cc_row16_add_zero = 0;
           }
              $row16 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
            if($invoice_details->cd_row17 != "")
            {
              $cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
            }
            else{
              $cd_row17_add_zero = 0;
            }
              $row17 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
           if($invoice_details->ce_row18 != "")
           {
            $ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
           }
           else{
            $ce_row18_add_zero = 0;
           }
              $row18 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
            if($invoice_details->cf_row19 != "")
            {
              $cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
            }
            else{
              $cf_row19_add_zero = 0;
            }
              $row19 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
            if($invoice_details->cg_row20 != "")
            {
              $cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
            }
            else{
              $cg_row20_add_zero = 0;
            }
              $row20 = '              
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
           $tax_details ='
           <div class="tax_table_div">
              <div class="tax_table">
                <div class="tax_row">
                  <div class="tax_row_td left">Total Fees (as agreed)</div>
                  <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
                </div>
                <div class="tax_row">
                  <div class="tax_row_td left">VAT @ 23%</div>
                  <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
                </div>
                <div class="tax_row">
                  <div class="tax_row_td left" style="color:#fff">.</div>
                  <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
                </div>
                <div class="tax_row">
                  <div class="tax_row_td left">Outlay @ 0%</div>
                  <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
                </div>
                <div class="tax_row">
                  <div class="tax_row_td left">Total Fees Due</div>
                  <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
                </div>
              </div>
            </div>
           ';
           //echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'detailsrow' => $roww_details ));
           echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'row4' => $row4, 'row5' => $row5, 'row6' => $row6, 'row7' => $row7, 'row8' => $row8,'row9' => $row9, 'row10' => $row10, 'row11' => $row11, 'row12' => $row12, 'row13' => $row13, 'row14' => $row14, 'row15' => $row15, 'row16' => $row16, 'row17' => $row17, 'row18' => $row18, 'row19' => $row19, 'row20' => $row20, 'result_invoice_single' => $result_invoice_single, 'result_time_task' => $result_time_task, 'result_allocated_task' => $result_allocated_task  ));
       }
  }
  public function taautoallocationtasks(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $ids = explode(",",$request->get('value'));
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    if(($ta_count)){
      $explode = explode(',', $ta_count->auto_invoice);
      if(!in_array($invoice, $explode)){
        if($ta_count->auto_invoice == ""){
          $invoices = $invoice;
        }
        else{
          $invoices = $ta_count->auto_invoice.','.$invoice;
        }
        \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update(['auto_invoice' => $invoices]);
      }
      if($ta_count->auto_tasks != ''){          
        $task_val = unserialize($ta_count->auto_tasks);
        if(isset($task_val[$invoice])){
          $already_task = $task_val[$invoice];
          $array_unique = array_unique(array_merge($already_task,$ids), SORT_REGULAR);
          $task_val[$invoice] = $array_unique;
        }
        else{
          $task_val[$invoice] = $ids;
        }
        $data['auto_tasks'] = serialize($task_val);
      }
      else{
        $array_value = array($invoice => $ids);
        $data['auto_tasks'] = serialize($array_value);          
      }
      \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update($data);
    }
    else{
      $data['auto_client_id'] = $client_id;
      $data['auto_invoice'] = $invoice;
      \App\Models\taAutoAllocation::insert($data);
      $array_value = array($invoice => $ids);
      $data['auto_tasks'] = serialize($array_value);
      \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update($data);
    }
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $unserialize = unserialize($ta_count->auto_tasks);
    $result_allocated_task=0;
    if($ta_count->auto_tasks != ''){
      $taskslist = $unserialize[$invoice];
      if(($taskslist)){
        foreach ($taskslist as $single_tasks) {
          $task_details = \App\Models\timeTask::where('id', $single_tasks)->first();
          $result_allocated_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item_unallocated" value="'.$single_tasks.'" data-element="'.$single_tasks.'" /><label>&nbsp;</label></td>
            <td>'.$task_details->task_name.'</td>
            <td align="center"><a href="javascript:" data-element="'.$single_tasks.'" title="Allocate all unallocated tasks to this invoice" class="fa fa-check at_auto_allocation_yes" aria-hidden="true"></a></td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_allocated_task='
      <tr>
        <td colspan="2">Empty</td>
      </tr>
      ';
    }
    //$unserialize = unserialize($ta_count->auto_tasks);
    //$unassigntask = $unserialize[$invoice];
    $time_task_client = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client_id.'%')->orderBy('task_name', 'asc')->get();
    $result_time_task=0;
    if(($time_task_client)){
      foreach ($time_task_client as $time_task) {
          if(isset($ta_count->auto_tasks)){
            $filter_task = strrpos($ta_count->auto_tasks, '"'.$time_task->id.'"');
            if ($filter_task === false) {
            $result_time_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
              <td>'.$time_task->task_name.'</td>
            </tr>
            ';
          }
        }
        else{
          $result_time_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
            <td>'.$time_task->task_name.'</td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_time_task='
      <tr>
        <td colspan="2">Empty</td>        
      </tr>
      ';
    }
    echo json_encode(array('result_allocated_task' => $result_allocated_task, 'result_time_task' => $result_time_task));    
  }
  public function taautoallocationtasks_yes(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $ids = explode(",",$request->get('value'));
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $get_allocated_inv = \App\Models\taClientInvoice::where('client_id',$client_id)->first();
    if(($get_allocated_inv))
    {
      $serialize_inv = unserialize($get_allocated_inv->tasks);
      if(isset($serialize_inv[$invoice]))
      {
        $allocated_tasks = $serialize_inv[$invoice];
      }
      else{
        $allocated_tasks = array();
      }
    }
    else{
      $allocated_tasks = array();
    }
    $get_task_details = \App\Models\taskJob::where('client_id',$client_id)->whereIn('task_id',$ids)->get();
    $task_yet_to_allocate = 0;
    if(($get_task_details))
    {
      foreach($get_task_details as $tasksval)
      {
        $tak_id = $tasksval->id;
        if(($get_allocated_inv))
        {
          if(strpos($get_allocated_inv->tasks, '"'.$tak_id.'"') === false) {
            if($task_yet_to_allocate == '')
            {
              $task_yet_to_allocate = $tak_id;
            }
            else{
              $task_yet_to_allocate = $task_yet_to_allocate.','.$tak_id;
            }   
          }
        }
        else{
          if($task_yet_to_allocate == '')
          {
            $task_yet_to_allocate = $tak_id;
          }
          else{
            $task_yet_to_allocate = $task_yet_to_allocate.','.$tak_id;
          }
        }
      }
    }
    $explode = explode(',',$task_yet_to_allocate);
    if(($get_allocated_inv))
    {
      $serialize_inv = unserialize($get_allocated_inv->tasks);
      if(isset($serialize_inv[$invoice]))
      {
        $allocated_tasks = $serialize_inv[$invoice];
      }
      else{
        $allocated_tasks = array();
      }
      $merge_array = array_merge($allocated_tasks,$explode);
      $serialize_inv[$invoice] = $merge_array;
      $task_allocated_to_inv['tasks'] = serialize($serialize_inv);
      \App\Models\taClientInvoice::where('client_id',$client_id)->update($task_allocated_to_inv);
    }
    else{
      $serialize_inv[$invoice] = $explode;
      $task_allocated_to_inv['client_id'] = $client_id;
      $task_allocated_to_inv['invoice'] = $invoice;
      $task_allocated_to_inv['tasks'] = serialize($serialize_inv);
      \App\Models\taClientInvoice::insert($task_allocated_to_inv);
    }
    if(($ta_count)){
      $explode = explode(',', $ta_count->auto_invoice);
      if(!in_array($invoice, $explode)){
        if($ta_count->auto_invoice == ""){
          $invoices = $invoice;
        }
        else{
          $invoices = $ta_count->auto_invoice.','.$invoice;
        }
        \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update(['auto_invoice' => $invoices]);
      }
      if($ta_count->auto_tasks != ''){          
        $task_val = unserialize($ta_count->auto_tasks);
        if(isset($task_val[$invoice])){
          $already_task = $task_val[$invoice];
          $array_unique = array_unique(array_merge($already_task,$ids), SORT_REGULAR);
          $task_val[$invoice] = $array_unique;
        }
        else{
          $task_val[$invoice] = $ids;
        }
        $data['auto_tasks'] = serialize($task_val);
      }
      else{
        $array_value = array($invoice => $ids);
        $data['auto_tasks'] = serialize($array_value);          
      }
      \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update($data);
    }
    else{
      $data['auto_client_id'] = $client_id;
      $data['auto_invoice'] = $invoice;
      \App\Models\taAutoAllocation::insert($data);
      $array_value = array($invoice => $ids);
      $data['auto_tasks'] = serialize($array_value);
      \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update($data);
    }
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $unserialize = unserialize($ta_count->auto_tasks);
    $result_allocated_task=0;
    if($ta_count->auto_tasks != ''){
      $taskslist = $unserialize[$invoice];
      if(($taskslist)){
        foreach ($taskslist as $single_tasks) {
          $task_details = \App\Models\timeTask::where('id', $single_tasks)->first();
          $result_allocated_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item_unallocated" value="'.$single_tasks.'" data-element="'.$single_tasks.'" /><label>&nbsp;</label></td>
            <td>'.$task_details->task_name.'</td>
            <td align="center"><a href="javascript:" data-element="'.$single_tasks.'" title="Allocate all unallocated tasks to this invoice" class="fa fa-check at_auto_allocation_yes" aria-hidden="true"></a></td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_allocated_task='
      <tr>
        <td colspan="2">Empty</td>
      </tr>
      ';
    }
    //$unserialize = unserialize($ta_count->auto_tasks);
    //$unassigntask = $unserialize[$invoice];
    $time_task_client = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client_id.'%')->orderBy('task_name', 'asc')->get();
    $result_time_task=0;
    if(($time_task_client)){
      foreach ($time_task_client as $time_task) {
          if(isset($ta_count->auto_tasks)){
            $filter_task = strrpos($ta_count->auto_tasks, '"'.$time_task->id.'"');
            if ($filter_task === false) {
            $result_time_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
              <td>'.$time_task->task_name.'</td>
            </tr>
            ';
          }
        }
        else{
          $result_time_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
            <td>'.$time_task->task_name.'</td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_time_task='
      <tr>
        <td colspan="2">Empty</td>        
      </tr>
      ';
    }
    echo json_encode(array('result_allocated_task' => $result_allocated_task, 'result_time_task' => $result_time_task));    
  }
  public function taautoallocationtasks_yes_individual(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $ids = $request->get('value');
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $get_allocated_inv = \App\Models\taClientInvoice::where('client_id',$client_id)->first();
    if(($get_allocated_inv))
    {
      $serialize_inv = unserialize($get_allocated_inv->tasks);
      if(isset($serialize_inv[$invoice]))
      {
        $allocated_tasks = $serialize_inv[$invoice];
      }
      else{
        $allocated_tasks = array();
      }
    }
    else{
      $allocated_tasks = array();
    }
    $get_task_details = \App\Models\taskJob::where('client_id',$client_id)->where('task_id',$ids)->get();
    $task_yet_to_allocate = 0;
    if(($get_task_details))
    {
      foreach($get_task_details as $tasksval)
      {
        $tak_id = $tasksval->id;
        if(strpos($get_allocated_inv->tasks, '"'.$tak_id.'"') === false) {
          if($task_yet_to_allocate == '')
          {
            $task_yet_to_allocate = $tak_id;
          }
          else{
            $task_yet_to_allocate = $task_yet_to_allocate.','.$tak_id;
          }   
        }
      }
    }
    $explode = explode(',',$task_yet_to_allocate);
    if(($get_allocated_inv))
    {
      $serialize_inv = unserialize($get_allocated_inv->tasks);
      if(isset($serialize_inv[$invoice]))
      {
        $allocated_tasks = $serialize_inv[$invoice];
      }
      else{
        $allocated_tasks = array();
      }
      $merge_array = array_merge($allocated_tasks,$explode);
      $serialize_inv[$invoice] = $merge_array;
      $task_allocated_to_inv['tasks'] = serialize($serialize_inv);
      \App\Models\taClientInvoice::where('client_id',$client_id)->update($task_allocated_to_inv);
    }
    else{
      $serialize_inv[$invoice] = $explode;
      $task_allocated_to_inv['client_id'] = $client_id;
      $task_allocated_to_inv['invoice'] = $invoice;
      $task_allocated_to_inv['tasks'] = serialize($serialize_inv);
      \App\Models\taClientInvoice::insert($task_allocated_to_inv);
    }  
  }
  public function taautounallocationtasks(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $ids = explode(",",$request->get('value'));
    $ta_client_invoice = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $unallocated = unserialize($ta_client_invoice->auto_tasks);
    $remove_task = $unallocated[$invoice];
    if(($ids)){
      foreach ($ids as $value) {
        $get_key = array_search($value, $remove_task);
        unset($remove_task[$get_key]);
        $remove_value = array_values($remove_task);
        $unallocated[$invoice] = $remove_value;
      }
    }
    $serialize = serialize($unallocated);
    \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->update(['auto_tasks' => $serialize ]);
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $time_task_client = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('clients','like','%'.$client_id.'%')->orderBy('task_name', 'asc')->get();
    $result_time_task=0;
    if(($time_task_client)){
      foreach ($time_task_client as $time_task) {
        if(isset($ta_count->auto_tasks)){
            $filter_task = strrpos($ta_count->auto_tasks, '"'.$time_task->id.'"');
            if ($filter_task === false) {
            $result_time_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
              <td>'.$time_task->task_name.'</td>
            </tr>
            ';
          }
        }
        else{
          $result_time_task.='
          <tr>
            <td style="width:50px;"><input type="checkbox" class="task_item" data-element="'.$time_task->id.'" value="'.$time_task->id.'" /><label>&nbsp;</label></td>
            <td>'.$time_task->task_name.'</td>
          </tr>
          ';
        }
      }
    }
    else{
      $result_time_task='
      <tr>
        <td colspan="2">Empty</td>        
      </tr>
      ';
    }
    $ta_count = \App\Models\taAutoAllocation::where('auto_client_id', $client_id)->first();
    $unserialize = unserialize($ta_count->auto_tasks);
    $result_allocated_task=0;
    if($ta_count->auto_tasks != ''){
      if(isset($unserialize[$invoice])){
        $taskslist = $unserialize[$invoice];
        if(($taskslist)){
          foreach ($taskslist as $single_tasks) {
            $task_details = \App\Models\timeTask::where('id', $single_tasks)->first();
            $result_allocated_task.='
            <tr>
              <td style="width:50px;"><input type="checkbox" class="task_item_unallocated" value="'.$single_tasks.'" data-element="'.$single_tasks.'" /><label>&nbsp;</label></td>
              <td>'.$task_details->task_name.'</td>
              <td align="center"><a href="javascript:" data-element="'.$single_tasks.'" title="Allocate all unallocated tasks to this invoice" class="fa fa-check at_auto_allocation_yes" aria-hidden="true"></a></td>
            </tr>
            ';
          }
        }
        else{
          $result_allocated_task='
          <tr>
            <td colspan="3" align="center">Empty</td>
          </tr>
          ';
        }
      }
      else{
        $result_allocated_task='
        <tr>
          <td colspan="3" align="center">Empty</td>
        </tr>
        ';
      }
    }
    else{
      $result_allocated_task='
      <tr>
        <td colspan="3" align="center">Empty</td>
      </tr>
      ';
    }
    echo json_encode(array('result_time_task' => $result_time_task, 'result_allocated_task' => $result_allocated_task));
  }
  public function taexcluded(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
    if(($ta_excluded)){
      if($ta_excluded->excluded_invoice == ""){
          $invoices = $invoice;
          $excluded_message = 'Invoice #'.$invoice.' is successfully Excluded from the Profit & Loss Monitor';
        }
        else{
          $explode_invoice = explode(',', $ta_excluded->excluded_invoice);
          if(in_array($invoice, $explode_invoice)){
            $invoices = $ta_excluded->excluded_invoice;
            $excluded_message = 'Invoice #'.$invoice.' already excluded in Profit & Loss Monitor';
          }
          else{
            $invoices = $ta_excluded->excluded_invoice.','.$invoice;
            $excluded_message = 'Invoice #'.$invoice.' is successfully Excluded from the Profit & Loss Monitor';
          }
        }
        \App\Models\taExcluded::where('excluded_client_id', $client_id)->update(['excluded_invoice' => $invoices]);
    }
    else{
      $data['excluded_client_id'] = $client_id;
      $data['excluded_invoice'] = $invoice;
      \App\Models\taExcluded::insert($data);
      $excluded_message = 'Invoice #'.$invoice.' is successfully Excluded from the Profit & Loss Monitor';
    }
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $result_unallocated_time_job=0;
      $unallocated_total=0;
      $sub_hour_calculate_unallocated=0;
      $sub_minutes_calculate_unallocated=0;
      $sub_total_minutes_unallocated=0;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
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
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          if(($ta_count)){
            $unserialize = unserialize($ta_count->tasks);
            $filter_jobs = strrpos($ta_count->tasks, '"'.$jobs->id.'"');
            if ($filter_jobs === false) { 
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
              $result_unallocated_time_job.='<tr>                    
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$time_task->task_name.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.'</td>
                    <td align="right">'.number_format_invoice_without_decimal($cost).'</td>                    
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
            $result_unallocated_time_job.='<tr>                    
              <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
              <td>'.$time_task->task_name.'</td>
              <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
              <td>'.$jobs->job_time.' '.$comments.'</td>
              <td align="right">'.number_format_invoice_without_decimal($cost).'</td>                    
            </tr>';
          }
        }
        $result_unallocated_time_job.='
        <tr>
          <th class="sorting_disabled">Total</td>
          <th class="sorting_disabled"></td>
          <th class="sorting_disabled"></td>
          <th class="sorting_disabled">'.$sub_hour_calculate_unallocated.':'.$sub_minutes_calculate_unallocated.':00</td>
          <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($unallocated_total).'</td>        
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
      $invoice_list = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
      $result_invoice_total=0;
      $result_invoice_cross=0;
      $result_invoice_vat=0;
      $result_invoice_net=0;
      $result_invoice_profit=0;
      $result_invoice_percentage=0;        
      $result_invoice=0;
      if(($invoice_list)){
        foreach ($invoice_list as $key => $invoice) {   
          $invoices = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
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
              $jobs = \App\Models\taskJob::where('id', $single_task)->first();                               
              if(($jobs)){
                  $explode_job_minutes = explode(":",$jobs->job_time);
                  $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                  $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
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
            $color = 0;
          }
          $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
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
            <td style="'.$color.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->gross).'</td>
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>        
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice_total_cost).'</td>
            <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($profit).'</td>
            <td style="'.$color.'">'.number_format_invoice_without_decimal($profit_percentage).' %</td>
            <td style=""  align="center">
            '.$excluded.'&nbsp;&nbsp;
            <a href="javascript:" title="Review"><i class="fa fa-files-o review_class" data-element="'.$invoice->invoice_number.'"></i></a>
            </td>
          </tr>'; 
          $result_invoice_cross = $result_invoice_cross+$invoice->gross;
          $result_invoice_vat = $result_invoice_vat+$invoice->vat_value;
          $result_invoice_net = $result_invoice_net+$invoice->inv_net;
          $result_invoice_total = $result_invoice_total+$invoice_total_cost;
          $result_invoice_profit = $result_invoice_profit+$profit;
          $result_invoice_percentage = $result_invoice_percentage+$profit_percentage;
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
              <th class="sorting_disabled"></td>
              <th class="sorting_disabled"></td>
              <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($result_invoice_cross).'</td>
              <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($result_invoice_vat).'</td>
              <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($result_invoice_net).'</td>
              <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($result_invoice_total).'</td>
              <th class="sorting_disabled" align="right">'.number_format_invoice_without_decimal($result_invoice_profit).'</td>
              <th class="sorting_disabled">'.number_format_invoice_without_decimal($result_final_percentage).' %</td>
              <th class="sorting_disabled"></td>
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
            <td style="text-align: right;">0.00</td>
          </tr>';
      }
      $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
      if(($ta_excluded))
      {
        if($ta_excluded->excluded_invoice != ""){
            $invoicelist = explode(',', $ta_excluded->excluded_invoice);
            $total_excluded_gross=0;
            $total_excluded_vat=0;
            $total_excluded_net=0;
            $result_excluded=0;
            if(($invoicelist)){
              foreach ($invoicelist as $key => $invoice) {
                $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
                $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->first();
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
    echo json_encode(array('result_excluded' => $result_excluded, 'excluded_message' => $excluded_message ,'result_invoice' => $result_invoice, 'result_summary' => $result_summary));
  }
  public function tainclude(Request $request){
    $invoice = $request->get('invoice');
    $client_id = $request->get('client_id');
    $key = $request->get('key');
    $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
    if(($ta_excluded)){
      $explode_ta = explode(',', $ta_excluded->excluded_invoice);
      unset($explode_ta[$key]);      
      $implode_invoice = implode(',', $explode_ta);
      \App\Models\taExcluded::where('excluded_client_id', $client_id)->update(['excluded_invoice' => $implode_invoice]);
      $excluded_message = 'Invoice #'.$invoice.' is successfully Include to the Profit & Loss Monitor';
    }
    $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
    if($ta_excluded->excluded_invoice != ''){
      $invoicelist = explode(',', $ta_excluded->excluded_invoice);
      $total_excluded_gross=0;
      $total_excluded_vat=0;
      $total_excluded_net=0;
      $result_excluded=0;
      if(($invoicelist)){
        foreach ($invoicelist as $key => $invoice) {
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
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
          <td></td>
          <td></td>
          <td align="right">'.number_format_invoice_without_decimal($total_excluded_gross).'</td>
          <td align="right">'.number_format_invoice_without_decimal($total_excluded_vat).'</td>
          <td align="right">'.number_format_invoice_without_decimal($total_excluded_net).'</td>
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
    $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $result_unallocated_time_job=0;
      $unallocated_total=0;
      $sub_hour_calculate_unallocated=0;
      $sub_total_minutes_unallocated=0;
      $sub_minutes_calculate_unallocated=0;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
          //-----------Job Time Start----------------
          $explode_job_minutes = explode(":",$jobs->job_time);
          $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
          //-----------Job Time End----------------
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
         /* $datas = unserialize($invoices->tasks);         
          $invoice_task = $datas[$invoice];
*/
         /* if(!in_array($jobs->id, $invoice_task)){*/
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          /*$key=0;
          $disable = 0;
          $invoice = 0;
          $class = 'select_task';*/
          if(($ta_count)){
            // $explode_invoice = explode(',', $ta_count->invoice);
            $unserialize = unserialize($ta_count->tasks);
           /* if($ta_count->tasks != ''){
              foreach ($unserialize as $key => $siglelist) {                   
                if(in_array($jobs->id, $siglelist)){*/
                  /*if(array_search($jobs->id, array_column($unserialize)) === False) {*/
                    $filter_jobs = strrpos($ta_count->tasks, '"'.$jobs->id.'"');
                    if ($filter_jobs === false) { // note: three equal signs
                        // not found...
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
          $result_unallocated_time_job.='               
                  <tr>                    
                    <td><spam style="display:none">'.strtotime($jobs->job_date).'</spam>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                    <td>'.$time_task->task_name.'</td>
                    <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                    <td>'.$jobs->job_time.' '.$comments.'</td>
                    <td align="right">'.number_format_invoice_without_decimal($cost).'</td>                    
                  </tr>
          ';
        /*}  */   
          }         
        }
      }
      $result_unallocated_time_job.='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>'.$sub_hour_calculate_unallocated.':'.$sub_minutes_calculate_unallocated.':00</td>
        <td align="right">'.number_format_invoice_without_decimal($unallocated_total).'</td>        
      </tr>
      ';
    }
              /*}
            }
          }*/
      else{
        $result_unallocated_time_job.='
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Empty</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
      }
    $invoice_list = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
      $result_invoice_total=0;
      $result_invoice_cross=0;
      $result_invoice_vat=0;
      $result_invoice_net=0;
      $result_invoice_profit=0;
      $result_invoice_percentage=0;        
      $result_invoice=0;
        if(($invoice_list)){
          foreach ($invoice_list as $key => $invoice) {   
            $invoices = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
            $invoice_number = $invoice->invoice_number;
            if(isset($invoice_number->tasks)){
              $unserialize1 = unserialize($invoices->tasks);
            }
            if(isset($unserialize1[$invoice_number])){
            $unserialize = $unserialize1[$invoice_number];
            } 
            else{
              $unserialize = array();
            }
            $invoice_total_cost='0';
            if(($unserialize)){              
              foreach ($unserialize as $single_task) {                
                $jobs = \App\Models\taskJob::where('id', $single_task)->first();                               
                if(($jobs)){
                    $explode_job_minutes = explode(":",$jobs->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                    $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
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
              $color = 0;
            }
            $ta_excluded = \App\Models\taExcluded::where('excluded_client_id', $client_id)->first();
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
              <td style="'.$color.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->gross).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>        
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($invoice_total_cost).'</td>
              <td style="'.$color.'" align="right">'.number_format_invoice_without_decimal($profit).'</td>
              <td style="'.$color.'">'.number_format_invoice_without_decimal($profit_percentage).' %</td>
              <td style=""  align="center">
              '.$excluded.'&nbsp;&nbsp;
              <a href="javascript:" title="Review"><i class="fa fa-files-o review_class" data-element="'.$invoice->invoice_number.'"></i></a>
              </td>
            </tr>'; 
            $result_invoice_cross = $result_invoice_cross+$invoice->gross;
            $result_invoice_vat = $result_invoice_vat+$invoice->vat_value;
            $result_invoice_net = $result_invoice_net+$invoice->inv_net;
            $result_invoice_total = $result_invoice_total+$invoice_total_cost;
            $result_invoice_profit = $result_invoice_profit+$profit;
            $result_invoice_percentage = $result_invoice_percentage+$profit_percentage;
            $result_final_percentage = $result_invoice_percentage/$invoice_count;
          }
          }
          $result_final_percentage = ($result_invoice_profit / $result_invoice_net) * 100;
          $result_invoice.='
              <tr>                  
                <td></td>
                <td></td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_cross).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_vat).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_net).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_total).'</td>
                <td align="right">'.number_format_invoice_without_decimal($result_invoice_profit).'</td>
                <td>'.number_format_invoice_without_decimal($result_final_percentage).' %</td>
                <td></td>
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
            <td style="text-align: right;">0.00</td>
          </tr>';
      }
    echo json_encode(array('result_excluded' => $result_excluded, 'excluded_message' => $excluded_message ,'result_invoice' => $result_invoice, 'result_summary' => $result_summary));
  }
  public function download_csv_allocated_tasks(Request $request)
  {
    $client_id = $request->get('client_id');
    $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
    $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=Time for ".$client_details->company."-".$client_details->client_id.".csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
            );
    $columns = array('', '', 'Time for '.$client_details->company.'-'.$client_details->client_id.'.csv', '', '', '');
      $file = fopen('public/papers/Time for '.$client_details->company.'-'.$client_details->client_id.'.csv', 'w');
      fputcsv($file, $columns);
      $columns1 = array('Date', 'Task', 'User (Rate)', 'Job Time', 'Cost', 'Allocation');
      fputcsv($file, $columns1);
      $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
      $client_name = $client_details->company.'-'.$client_details->client_id;
      if(($joblist)){
        foreach ($joblist as $jobs) {
          $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
          $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
          $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
          $invoice = 0;
          if(($ta_count)){
            $unserialize = unserialize($ta_count->tasks);
            if($ta_count->tasks != ''){
              foreach ($unserialize as $key => $siglelist) {                   
                if(in_array($jobs->id, $siglelist)){
                  $invoice = $key;
                }
              }
            }
          }
          $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
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
          $columns2 = array(date('d-M-Y', strtotime($jobs->job_date)),$time_task->task_name,$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')',$jobs->job_time,number_format_invoice_without_decimal($cost),$invoice);
          fputcsv($file, $columns2);
        }              
      }
      fclose($file);
    echo "Time for ".$client_details->company."-".$client_details->client_id.".csv";
  }
  public function download_csv_allocated_invoices(Request $request)
  {
    $client_id = $request->get('client_id');
    $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
    $file = fopen('public/papers/Invoices for '.$client_details->company.'-'.$client_details->client_id.'.csv', 'w');
    $columns = array('', '', 'Invoices for '.$client_details->company.'-'.$client_details->client_id.'.csv', '', '', '');
    fputcsv($file, $columns);
    $columns1 = array('S.No', 'Invoice No', 'Date', 'Net', 'Vat', 'Gross');
    fputcsv($file, $columns1);
    $invoicelist = \App\Models\InvoiceSystem::where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
    $result_invoice=0;
    if(($invoicelist)){
      $i = 1;
      foreach ($invoicelist as $invoice) {
        $columns2 =array($i, $invoice->invoice_number,date('d-M-Y', strtotime($invoice->invoice_date)), number_format_invoice_without_decimal($invoice->inv_net),number_format_invoice_without_decimal($invoice->vat_value),number_format_invoice_without_decimal($invoice->gross));
        fputcsv($file, $columns2);
        $i++;
      }
    }
    fclose($file);
    echo "Invoices for ".$client_details->company."-".$client_details->client_id.".csv";
  }
  public function download_csv_active_invoices(Request $request)
  {
    $client_id = $request->get('client_id');
    $invoice = $request->get('invoice');
    $client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
    $ta_count = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
    $invoice_details = \App\Models\InvoiceSystem::where('invoice_number', $invoice)->first();
    $task_details = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
    $datas = unserialize($task_details->tasks);
    $file = fopen('public/papers/Time Allocated to Invoice Number '.$invoice.'.csv', 'w');
    $columns = array('', '', 'Time Allocated to Invoice Number #'.$invoice.'.csv', '', '', '','');
    fputcsv($file, $columns);
    $columns1 = array('S.No', 'Date', 'Task', 'User (Rate)', 'Job Time', 'Cost','Allocation');
    fputcsv($file, $columns1);
    if($task_details->tasks != ''){
      if(isset($datas[$invoice])){
        $invoice_task = $datas[$invoice];
        $result_invoice_task=0;
        $sub_total_time = 0;      
        $sub_total_cost = 0;
        $task_array = array();
        $task_cost_array = array();
        $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();
        $task_total=0;
        $hour_calculate=0;
        $minutes_calculate=0;
        $sub_hour_calculate=0;
        $sub_minutes_calculate=0;
        $i = 1;
        if(($joblist)){
          foreach ($joblist as $jobs) {       
            if(in_array($jobs->id, $invoice_task)){
              $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
              $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
              $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
              //-----------Job Time Start----------------
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              //-----------Job Time End----------------
              $rate_result = '0';
              $cost = '0';
              $task_cost = 0;
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
              $sub_total_time = $sub_total_time+$total_minutes;
              $hour_calculate = strtok(($sub_total_time/60), '.');
              $minutes_calculate = $sub_total_time-($hour_calculate*60);
              if($hour_calculate <= 9){
                $hour_calculate = '0'.$hour_calculate;
              }
              else{
                $hour_calculate = $hour_calculate;
              }
              if($minutes_calculate <= 9){
                $minutes_calculate = '0'.$minutes_calculate;
              }
              else{
                $minutes_calculate = $minutes_calculate;
              }
              $sub_total_cost = $sub_total_cost+$cost;
              if(isset($datausercost['task_'.$jobs->task_id]))
              {
                $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
              }
              else{
                $datausercost['task_'.$jobs->task_id] = $cost;
              }
              $columns2 = array($i,date('d-M-Y', strtotime($jobs->job_date)),$time_task->task_name,$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')',$jobs->job_time,number_format_invoice_without_decimal($cost),$invoice);
              fputcsv($file, $columns2);
              $i++;
            }
          }
        }
        if($minutes_calculate != 0){
          $columns4 = array('','','','','','','');
          fputcsv($file, $columns4);
          $columns3 = array('Total','','','',$hour_calculate.':'.$minutes_calculate.':'.'00',number_format_invoice_without_decimal($sub_total_cost),'');
          fputcsv($file, $columns3);
        }
        else{
          $hour_minsutes_second = 0;
        }
      }
    }
    echo "Time Allocated to Invoice Number ".$invoice.".csv";
  }
  public function download_csv_task_summary(Request $request)
  {
    $client_id = $request->get('client_id');
    $invoice = $request->get('invoice');
    $task_type = $request->get('task_type');
    $file = fopen('public/papers/Time Allocated to Invoice Number '.$invoice.'.csv', 'w');
    $columns = array('', '', 'Time Allocated to Invoice Number #'.$invoice, '', '', '','');
    fputcsv($file, $columns);
    $columns1 = array('S.No', 'Date', 'Task', 'User (Rate)', 'Job Time', 'Cost','Allocation');
    fputcsv($file, $columns1);
    $task_details = \App\Models\taClientInvoice::where('client_id', $client_id)->first();
    $datas = unserialize($task_details->tasks);
    if($task_details->tasks != ''){
      if(isset($datas[$invoice])){
        $invoice_task = $datas[$invoice];
        $result_invoice_task=0;
        $sub_total_time = 0;      
        $sub_total_cost = 0;
        $task_array = array();
        $task_cost_array = array();
        $joblist = \App\Models\taskJob::where('client_id', $client_id)->where('task_id',$task_type)->where('status', 1)->orderBy('job_date', 'DESC')->get();
        $task_total=0;
        $hour_calculate=0;
        $minutes_calculate=0;
        $sub_hour_calculate=0;
        $sub_minutes_calculate=0;
        $isno = 1;
        if(($joblist)){
          foreach ($joblist as $jobs) {       
            if(in_array($jobs->id, $invoice_task)){
              $user_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id', $jobs->user_id)->first();
              $time_task = \App\Models\timeTask::where('id', $jobs->task_id)->first();
              $ratelist =\App\Models\userCost::where('user_id', $jobs->user_id)->get();
              //-----------Job Time Start----------------
              $explode_job_minutes = explode(":",$jobs->job_time);
              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
              //-----------Job Time End----------------
              $rate_result = '0';
              $cost = '0';
              $task_cost = 0;
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
              $sub_total_time = $sub_total_time+$total_minutes;
              $hour_calculate = strtok(($sub_total_time/60), '.');
              $minutes_calculate = $sub_total_time-($hour_calculate*60);
              if($hour_calculate <= 9){
                $hour_calculate = '0'.$hour_calculate;
              }
              else{
                $hour_calculate = $hour_calculate;
              }
              if($minutes_calculate <= 9){
                $minutes_calculate = '0'.$minutes_calculate;
              }
              else{
                $minutes_calculate = $minutes_calculate;
              }
              $sub_total_cost = $sub_total_cost+$cost;
              if(isset($datausercost['task_'.$jobs->task_id]))
              {
                $datausercost['task_'.$jobs->task_id] = $datausercost['task_'.$jobs->task_id] + $cost;
              }
              else{
                $datausercost['task_'.$jobs->task_id] = $cost;
              }
              $columns2 = array($isno, date('d-M-Y', strtotime($jobs->job_date)), $time_task->task_name, $user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')', $jobs->job_time, number_format_invoice_without_decimal($cost),$invoice);
              fputcsv($file, $columns2);
              $isno++;
            }
          }
        }
        if($minutes_calculate != ''){
          $columns3 = array('Total', '', '', '', $hour_calculate.':'.$minutes_calculate.':'.'00',number_format_invoice_without_decimal($sub_total_cost),'');
              fputcsv($file, $columns3);
        }
        else{
          $columns3 = array('', '', '', '', '','','');
              fputcsv($file, $columns3);
        }
      }
    }
    else{
    }
    echo 'Time Allocated to Invoice Number '.$invoice.'.csv';
  }
  public function load_all_clients_ta_system(Request $request){
    $clientlist = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();    
    $i=1;
    $output_client=0;
    if(($clientlist)){              
      foreach($clientlist as $client){
        if($client->company == ""){$client_company = $client->firstname.' & '.$client->surname;}else{$client_company = $client->company;}
        if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
        $output_client.='
        <tr class="edit_task edit_task_'.$client->client_id.' '.$inactive_cls.'" style="font-size:13px !important;">
          <td align="left"><a href="javascript:" class="load_unallocated fa fa-cog" data-element="'.$client->client_id.'" title="Apply time allocations to this Client"></a></td>
          <td>'.$i.'</td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->client_id.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client_company.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->firstname.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->surname.'</a></td>
          <td align="left" class="allocated_time_client allocated_time_client_'.$client->client_id.'"></td>
          <td align="left" class="unallocated_time_client unallocated_time_client_'.$client->client_id.'"></td>
        </tr>';
        $i++;
      }
    }
    else{
      $output_client='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:center; font-weight: normal !important;">No Data found</td>
        <td></td>
        <td></td>
        <td></td>       
      </tr>
      ';
    }
    echo $output_client;
  }
  public function load_single_client_ta_system(Request $request){
    $client_id = $request->get('client_id');
    $client = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id',$client_id)->first();
    $i=1;
    if(($client)){      
        if($client->company == ""){$client_company = $client->firstname.' & '.$client->surname;}else{$client_company = $client->company;}       
        $output_client ='
        <tr class="edit_task edit_task_'.$client->client_id.'" style="font-size:13px !important;">
          <td align="left"><a href="javascript:" class="load_unallocated fa fa-cog" data-element="'.$client->client_id.'" title="Apply time allocations to this Client"></a></td>
          <td>'.$i.'</td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->client_id.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client_company.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->firstname.'</a></td>
          <td align="left"><a href="'.URL::to('user/ta_allocation?client_id='.$client->client_id).'">'.$client->surname.'</a></td>
          <td align="left" class="allocated_time_client allocated_time_client_'.$client->client_id.'"></td>
          <td align="left" class="unallocated_time_client unallocated_time_client_'.$client->client_id.'"></td>
        </tr>';
        $i++;
    }
    else{
      $output_client='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:center; font-weight: normal !important;">No Data found</td>
        <td></td>
        <td></td>
        <td></td>       
      </tr>
      ';
    }
    echo $output_client;
  }
}