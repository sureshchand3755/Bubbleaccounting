@extends('userheader')
@section('content')
<link rel="stylesheet" href="<?php echo URL::to('public/assets/js/c3/c3.css')?>">
<script src="<?php echo URL::to('public/assets/js/c3/d3p.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/assets/js/c3/c3.min.js'); ?>"></script>
<style>
.own_drop_down:hover, .own_drop_down:active, .own_drop_down:focus, .own_drop_down:visited,.own_drop_down:focus-visible,.own_drop_down:focus-within,.own_drop_down:target{
    color: #000 !important;
    background: #e7e7e7 !important;
}
body{
    background-color:#f5f5f5;
}
.c3-legend-item {
  font-size: 16px;
}
.c3-axis-y text 
{
  font-size: 14px; //change the size of the fonts
}

.c3-axis-x text 
{
  font-size: 14px; //change the size of the fonts
}
</style>
<div class="content_section">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title"><a href="<?php echo url('user/dashboard_system_summary'); ?>" style="float:left;margin-right:15px">Dashboard</a>
        <div class="dropdown" style="float: left;">
            <button class="dropdown-toggle own_drop_down" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="border: 0px;margin-top: 3px;">
              <i class="fa fa-bars" aria-hidden="true"></i>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="width: 300px;left: 10%;" aria-labelledby="dropdownMenu1">
              <li><a href="<?php echo URL::to('user/dashboard'); ?>">Dashboard - User Profile</a></li>
              <li class="active"><a href="<?php echo URL::to('user/dashboard_analytics'); ?>">Dashboard - User Statistics </a></li>
              <li><a href="<?php echo URL::to('user/dashboard_system_summary'); ?>">Dashboard - System Summary </a></li>
              
            </ul>
        </div>
        <a href="javascript:" class="fa fa-cog dashboard_settings_email common_black_button" style="margin-top: -3px;float:right" title="Settings">
        </a>
    </h4>
  </div>
      <h2>INVOICING SECTION</h2>
      <div class="col-md-4 col-md-offset-2">
        <p style="font-size:16px;text-align: center">Net Sales Per Year</p>
        <div id="invoicechart"></div>
      </div>

      <div class="col-md-6">
        <p style="font-size:16px;text-align: center">3 Month Net Sales Analysis</p>
        <div id="invoicechart2"></div>
      </div>
        <?php
        $userslist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
        $total_open_task_count = 0;
        if(($userslist)) {
            foreach($userslist as $user){
                $open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$user->user_id."' OR (`allocated_to` = '0' AND `author` != '".$user->user_id."'))");
                $total_open_task_count = $total_open_task_count + count($open_task_count);
            }
        }
        ?>
      <h2 style="margin-top:50px;float:left;clear:both">TASK SECTION</h2>
      <div class="col-md-12" style="padding: 0px;">
        <p style="font-size:18px;">Total Open Tasks: <?php echo $total_open_task_count; ?></p>
      </div>

      <div class="col-md-4 col-md-offset-2">
        <p style="font-size:16px;text-align: center">Total Task Per User</p>
        <div id="invoicechart3"></div>
      </div>

      <div class="col-md-6">
        <p style="font-size:16px;text-align: center">3 Month New Task Analysis
        </p>
        <div id="invoicechart4"></div>
      </div>
</div>
<?php
    /**********************************************************************************/
    $invoice_details = DB::table('invoice_system')->select(DB::raw('EXTRACT(year FROM invoice_date) AS invoice_year'), DB::raw('SUM(inv_net) AS total_net'))->groupBy(DB::raw('EXTRACT(year FROM invoice_date)'))->orderBy(DB::raw('EXTRACT(year FROM invoice_date)'), 'desc')->limit(5)->get();
    $label = ['Label'];
    $value = ['NET Amount'];
    if(($invoice_details))
    {
        foreach($invoice_details as $invoice){
            array_push($label, $invoice->invoice_year);
            array_push($value, number_format_invoice_without_comma($invoice->total_net));
        }
    }
    /**********************************************************************************/
    $invoice_past_months = DB::table('invoice_system')->select(DB::raw('EXTRACT(year FROM invoice_date) AS invoice_year'), DB::raw('MONTHNAME(invoice_date) AS invoice_month'), DB::raw('SUM(inv_net) AS total_net'))->groupBy(DB::raw('EXTRACT(year_month FROM invoice_date)'))->orderBy(DB::raw('EXTRACT(year_month FROM invoice_date)'), 'desc')->limit(3)->get();
    $label_month = [];
    $value_month = [];
    if(($invoice_past_months))
    {
        foreach($invoice_past_months as $invoice_month){
            array_push($label_month, $invoice_month->invoice_month.' '.$invoice_month->invoice_year);
            array_push($value_month, number_format_invoice_without_comma($invoice_month->total_net));
        }
    }

    /**********************************************************************************/
    
    $userslist = DB::table('user')->where('practice',Session::get('user_practice_code'))->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
    $userlabel = ['Label'];
    $uservalue = ['Task Count'];
    if(($userslist)) {
        foreach($userslist as $user){
            array_push($userlabel, $user->lastname.'     '.$user->firstname);
            $task_count = DB::table('taskmanager_specifics')->where('to_user',$user->user_id)->where('status','<',3)->groupBy('task_id')->get();
            array_push($uservalue, count($task_count));

            $open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$user->user_id."' OR (`allocated_to` = '0' AND `author` != '".$user->user_id."'))");

        }
    }
    /**********************************************************************************/

    $tasks_past_months = DB::table('taskmanager')->where('practice_code', Session::get('user_practice_code'))->select(DB::raw('EXTRACT(year FROM creation_date) AS task_year'), DB::raw('MONTHNAME(creation_date) AS task_month'), DB::raw('COUNT(id) AS total_tasks'))->groupBy(DB::raw('EXTRACT(year_month FROM creation_date)'))->orderBy(DB::raw('EXTRACT(year_month FROM creation_date)'), 'desc')->limit(3)->get();

    $task_label_month = [];
    $task_value_month = [];
    if(($tasks_past_months))
    {
        foreach($tasks_past_months as $task_month){
            array_push($task_label_month, $task_month->task_month.' '.$task_month->task_year);
            array_push($task_value_month, $task_month->total_tasks);
        }
    }

?>
@include('user/system_settings')
<script>
var barchartLabel = <?php echo json_encode($label); ?>;
var barchartTransactions = <?php echo json_encode($value); ?>;

var barchartUserLabel = <?php echo json_encode($userlabel); ?>;
var barchartUserValue = <?php echo json_encode($uservalue); ?>;

c3.generate({
    size: {
        height: 300,
    },
    bindto: "#invoicechart",
    data: {
        x: "Label",
        columns: [
            barchartLabel,
            barchartTransactions,
        ],
        type:"bar",
    },
    axis: {
        x: {
            type: 'category',
        }
    },
    color: {
        pattern: ['#ef9b1d'],
    },
    padding: {
      bottom: 25 //adjust chart padding bottom
    }
});


c3.generate({
    size: {
        height: 300,
    },
    bindto: "#invoicechart3",
    data: {
        x: "Label",
        columns: [
            barchartUserLabel,
            barchartUserValue,
        ],
        type:"bar",
    },
    axis: {
        x: {
            type: 'category',
        }
    },
    color: {
        pattern: ['#ef9b1d'],
    },
    padding: {
      bottom: 25 //adjust chart padding bottom
    }
});

c3.generate({
    size: {
        height: 300,
    },
    bindto: '#invoicechart2',
    data: {
        columns: [
            ['<?php echo $label_month[0]; ?>', '<?php echo $value_month[0]; ?>'],
            ['<?php echo $label_month[1]; ?>', '<?php echo $value_month[1]; ?>'],
            ['<?php echo $label_month[2]; ?>', '<?php echo $value_month[2]; ?>']
        ],
        type: 'pie',
        onclick: function(d, i) { console.log("onclick", d, i); },
        onmouseover: function(d, i) { console.log("onmouseover", d, i); },
        onmouseout: function(d, i) { console.log("onmouseout", d, i); },
    },
    pie: {
        label: {
          format: function(value, ratio, id) {
            return value;
          }
        }
    },
    tooltip: {
        format: {
            value: function(value, ratio, id) {
                return value;
            }
        }
    }
})


c3.generate({
    size: {
        height: 300,
    },
    bindto: '#invoicechart4',
    data: {
        columns: [
            ['<?php echo $task_label_month[0]; ?>', '<?php echo $task_value_month[0]; ?>'],
            ['<?php echo $task_label_month[1]; ?>', '<?php echo $task_value_month[1]; ?>'],
            ['<?php echo $task_label_month[2]; ?>', '<?php echo $task_value_month[2]; ?>']
        ],
        type: 'pie',
        onclick: function(d, i) { console.log("onclick", d, i); },
        onmouseover: function(d, i) { console.log("onmouseover", d, i); },
        onmouseout: function(d, i) { console.log("onmouseout", d, i); },
    },
    pie: {
        label: {
          format: function(value, ratio, id) {
            return value;
          }
        }
    },
    tooltip: {
        format: {
            value: function(value, ratio, id) {
                return value;
            }
        }
    }
})
</script>
@stop