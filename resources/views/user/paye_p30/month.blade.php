@extends('userheader')
@section('content')

<style>
body{background-color:#f5f5f5; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}
  .paye_p30_year_div{
    width: 30%;
    background: rgba(255, 255, 255,0.5);
    padding: 10px;

  }
</style>
<style>
.page_title{color:#000;}
</style>
<div class="content_section">
  <p style="clear: both;font-size: 18px;font-weight: 800;color: #000; position: absolute;bottom:8%;text-align: center;width: 98%;">
      You Are In The PAYE M.R.S System
    <br/>
    <spam style="position: absolute;bottom: 66%;right: 0%;">MANAGE PAYE M.R.S</spam>
    <a href="<?php echo URL::to('user/p30_task_leval'); ?>" class="common_black_button" style="float:right">Manage Task Level</a>
  </p>
  <div class="page_title" >
    PAYE Modern Reporting Management Module 
  </div>
  <div class="select_button">
      <?php
        if(($paye_p30_year))
        {
          echo '<ul>';
            foreach($paye_p30_year as $year){
              if($year->year_status == 0){
                ?>  
                   <li><a class="show_alert" href="<?php echo URL::to('user/paye_p30_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name; ?></a></li>
                <?php
              }
            }
          echo '</ul>';
        }
        else{
          ?>
          <div class="paye_p30_year_div">
            <p>Note: Since No Year is created in Paye M.R.S. So Please select the year from dropdown which will be your First Year in Paye M.R.S System.</p>
            <h6 style="font-weight:800">SELECT YEAR: </h6>
            <select name="select_paye_p30_year" id="select_paye_p30_year" class="form-control input-sm">
                <option value="">Select Year</option>
                <?php
                $starting_year = 2019;
                for($i = $starting_year; $i<=2040; $i++)
                {
                  echo '<option value="'.$i.'">'.$i.'</option>';
                }
                ?> 
            </select>
            <input type="button" name="update_paye_p30_year" id="update_paye_p30_year" class="common_black_button" value="Submit" style="margin-top:10px">
          </div>
          <?php
        }
      ?>
  </div>
</div>
<script>
$(window).click(function(e){
  if($(e.target).hasClass('show_alert'))
  {
    e.preventDefault()
    var href = $(e.target).attr("href");
    alert("Please Note that the system may take up to 2 to 3 minutes to load and show all the data at once.");
    window.location.replace(href);
  }
  if(e.target.id == "update_paye_p30_year")
  {
    var year = $("#select_paye_p30_year").val();
    if(year != "")
    {
      var r =confirm("About to create your First year, once you create this year you CAN NOT create an older year, Are you sure you wish to continue creating this year?");
      if(r)
      {
        $.ajax({
          url:"<?php echo URL::to('user/update_paye_p30_first_year'); ?>",
          type:"post",
          data:{year:year},
          success: function(result)
          {
            window.location.reload();
          }
        })
      }
    }
    else{
      alert("Please Select the Year from dropdown.")
    }
  }
});
</script>
@stop
