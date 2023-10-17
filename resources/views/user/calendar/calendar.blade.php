@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<!-- Fullcalendar  -->
<script type="text/javascript" src="<?php echo URL::to('public/assets/plugins') ?>/fullcalendar/dist/index.global.min.js"></script>
<!-- Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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
<div class="content_section">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">Staff Calendar</h4>   
  </div>
  <div id='calendar-container' style="width:70%;float:left">
    <div id='calendar'></div>
  </div>
  <div id='calendar-container' style="width:25%;margin-left:3%;float:left">
    <div id='calendar_day'></div>
  </div>
</div>
<!-- Calendar Container -->

<?php 
$currentData = date('Y-m-d');
?>
<script>
$(window).click(function(e) {
  // if(!$("#day-view-iframe-container").is(":visible")) {
  //     showDayCalendar('<?php echo $currentData; ?>','dayGridMonth');
  //     showDayCalendar2('<?php echo $currentData; ?>');
  // }
  if($(e.target).hasClass('fc-daygrid-day-number')) {
    e.stopPropagation();
  }
})
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
      height: "1000px",
      selectable: true,
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: "<?php echo URL::to('user/fetchevents'); ?>", // Fetch all events
      allDay: false,
      select: function(arg) {
        var selectedDate = change_date_format(arg.start);
        var selectedTime = moment(arg.startStr).format('HH:mm');
        var currentView = 'dayGridMonth';

        Swal.fire({
          title: 'Add New Event',
          width: '500px',
          height: '700px',
          showCancelButton: true,
          confirmButtonText: 'Create',
          html:
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_day" class="form-control" placeholder="Event name" required>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_day" class="form-control" placeholder="Client name" required><input type="hidden" id="eventclientid_day" class="form-control">' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_day" class="form-control" placeholder="Event description" required></textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_day" class="form-control" placeholder="Event Start Date" value="'+selectedDate+'"></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_day" class="form-control" placeholder="Event End Date" value="'+selectedDate+'"></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_day" class="form-control" placeholder="Event Start Time" value="'+selectedTime+'"></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_day" class="form-control" placeholder="Event End Time" value="'+selectedTime+'"></div></div></div></div>',
          didOpen: () => {
                $("#eventstartdate_day").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                })
                $("#eventenddate_day").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                   minDate: moment(arg.start).hour(00).minute(00),
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
            var title = result.value[0].trim();
            var description = result.value[1].trim();
            var start_date = result.value[2].trim();
            var end_date = result.value[3].trim();
            var start_time = result.value[4].trim();
            var end_time = result.value[5].trim();
            var client_name = result.value[6].trim();
            var client_id = result.value[7].trim();

            if(title != '' && description != '' && client_id != ''){
              $.ajax({
                url: "<?php echo URL::to('user/calendarEvents'); ?>",
                type: 'post',
                data: {type: 'addEvent',title: title,description: description,start_date: start_date,end_date: end_date,start_time:start_time,end_time:end_time,client_id:client_id},
                dataType: 'json',
                success: function(response){
                  if(response.status == 1){
                    showDayCalendar(arg.startStr,currentView);
                    Swal.fire(response.message,'','success');
                  }else{
                    Swal.fire(response.message,'','error');
                  }
                  showDayCalendar2(arg.startStr);
                }
              });
            }
            else{
              Swal.fire('Please Enter all the Mandatory Fields','','error');
            }
          }
        })
        
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
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle_day" class="form-control" placeholder="Event name" value="'+title+'" required>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient_day" class="form-control" placeholder="Client name" value="'+client_name+'" required><input type="hidden" id="eventclientid_day" class="form-control" value="'+client_id+'">' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription_day" class="form-control" placeholder="Event description" required>'+description+'</textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate_day" class="form-control" placeholder="Event Start Date" value="'+start_date+'"></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate_day" class="form-control" placeholder="Event End Date" value="'+end_date+'"></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime_day" class="form-control" placeholder="Event Start Time" value="'+start_time+'"></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime_day" class="form-control" placeholder="Event End Time" value="'+end_time+'"></div></div></div></div>',
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
function showDayCalendar(date,view) {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
      initialDate: date,
      initialView: view,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek'
      },
      height: "1000px",
      selectable: true,
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: "<?php echo URL::to('user/fetchevents'); ?>", // Fetch all events
     viewDidMount: function(view) {
          var viewType = calendar.view.type; // Get the current view type
          if(viewType == "timeGridWeek"){
             customizeWeekHeaders();
          }
           
        },
      select: function(arg) {
        if (arg.jsEvent.target.classList.contains('fc-daygrid-day-number')) {
          $(".fc-day").removeClass("selected-cell");
          var selectedDate = change_date_format(arg.start);
          var selectedTime = moment(arg.startStr).format('HH:mm');
          var currentView = calendar.view.type;

          var selectedStart = moment(arg.startStr).format('YYYY-MM-DD');
          var dayCells = calendarEl.querySelectorAll('.fc-day[data-date]');
          dayCells.forEach(function(dayCell) {
            var cellDate = dayCell.getAttribute('data-date');
            if (cellDate == selectedStart) {
              dayCell.classList.add('selected-cell');

            }
          });
          showDayCalendar2(arg.startStr);
        }
        else{
          $(".fc-day").removeClass("selected-cell");
          var selectedDate = change_date_format(arg.start);
          var selectedTime = moment(arg.startStr).format('HH:mm');
          var currentView = calendar.view.type;

          var selectedStart = moment(arg.startStr).format('YYYY-MM-DD');
          var dayCells = calendarEl.querySelectorAll('.fc-day[data-date]');
          dayCells.forEach(function(dayCell) {
            var cellDate = dayCell.getAttribute('data-date');
            if (cellDate == selectedStart) {
              dayCell.classList.add('selected-cell');

            }
          });
          showDayCalendar2(arg.startStr);

          Swal.fire({
            title: 'Add New Event',
            width: '500px',
            height: '700px',
            showCancelButton: true,
            confirmButtonText: 'Create',
            html:
            '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle" class="form-control" placeholder="Event name" required>' +
            '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient" class="form-control" placeholder="Client name" required><input type="hidden" id="eventclientid" class="form-control">' +
            '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription" class="form-control" placeholder="Event description" required></textarea>' + 
            '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate" class="form-control" placeholder="Event Start Date" value="'+selectedDate+'"></div>' + 
            '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate" class="form-control" placeholder="Event End Date" value="'+selectedDate+'"></div>' + 
            '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime" class="form-control" placeholder="Event Start Time" value="'+selectedTime+'"></div>' +
            '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime" class="form-control" placeholder="Event End Time" value="'+selectedTime+'"></div></div></div></div>',
            didOpen: () => {
                  $("#eventstartdate").datetimepicker({
                     format: 'L',
                     format: 'DD-MMM-YYYY',
                  })
                  $("#eventenddate").datetimepicker({
                     format: 'L',
                     format: 'DD-MMM-YYYY',
                     minDate: moment(arg.start).hour(00).minute(00),
                  })
                  $("#eventstarttime").datetimepicker({
                     format: 'L',
                     format: 'HH:mm',
                  })
                  $("#eventendtime").datetimepicker({
                     format: 'L',
                     format: 'HH:mm',
                  })
                  $("#eventstartdate").on("dp.change", function (e) {
                      $('#eventenddate').data("DateTimePicker").minDate(e.date);
                  });
                  $("#eventstarttime").on("dp.change", function (e) {
                      $('#eventendtime').data("DateTimePicker").minDate(e.date);
                      $('#eventendtime').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
                  });

                  $("#eventclient").autocomplete({
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
                      $("#eventclientid").val(ui.item.id);
                    }
                  });
            },
            focusConfirm: false,
            preConfirm: () => {
              return [
                  document.getElementById('eventtitle').value,
                  document.getElementById('eventdescription').value,
                  document.getElementById('eventstartdate').value,
                  document.getElementById('eventenddate').value,
                  document.getElementById('eventstarttime').value,
                  document.getElementById('eventendtime').value,
                  document.getElementById('eventclient').value,
                  document.getElementById('eventclientid').value
              ]
            }
          }).then((result) => {
            if (result.isConfirmed) {
              var title = result.value[0].trim();
              var description = result.value[1].trim();
              var start_date = result.value[2].trim();
              var end_date = result.value[3].trim();
              var start_time = result.value[4].trim();
              var end_time = result.value[5].trim();
              var client_name = result.value[6].trim();
              var client_id = result.value[7].trim();

              if(title != '' && description != '' && client_id != ''){
                $.ajax({
                  url: "<?php echo URL::to('user/calendarEvents'); ?>",
                  type: 'post',
                  data: {type: 'addEvent',title: title,description: description,start_date: start_date,end_date: end_date,start_time:start_time,end_time:end_time,client_id:client_id},
                  dataType: 'json',
                  success: function(response){
                    if(response.status == 1){
                      showDayCalendar(arg.startStr,currentView);
                      Swal.fire(response.message,'','success');
                    }else{
                      Swal.fire(response.message,'','error');
                    }

                    showDayCalendar2(arg.startStr);
                  }
                });
              }
              else{
                Swal.fire('Please Enter all the Mandatory Fields','','error');
              }
            }
          })
          
          calendar.unselect()
        }
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
        var currentView = calendar.view.type;

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
        var currentView = calendar.view.type;

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
          '<div><h5 style="text-align:left"><strong>Enter Event Name: </strong></h5><input id="eventtitle" class="form-control" placeholder="Event name" value="'+title+'" required>' +
          '<h5 style="text-align:left"><strong>Enter Client Name: </strong></h5><input id="eventclient" class="form-control" placeholder="Client name" value="'+client_name+'" required><input type="hidden" id="eventclientid" class="form-control" value="'+client_id+'">' +
          '<h5 style="text-align:left"><strong>Enter Event Description: </strong></h5><textarea id="eventdescription" class="form-control" placeholder="Event description" required>'+description+'</textarea>' + 
          '<div class="datetimepicker_div"><div class="col-md-12 padding_00 datetimepicker"><div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event Start Date: </strong></h5><input id="eventstartdate" class="form-control" placeholder="Event Start Date" value="'+start_date+'"></div>' + 
          '<div class="col-md-6 padding_00"><h5 style="text-align:left"><strong>Select Event End Date: </strong></h5><input id="eventenddate" class="form-control" placeholder="Event End Date" value="'+end_date+'"></div>' + 
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event Start Time: </strong></h5><input id="eventstarttime" class="form-control" placeholder="Event Start Time" value="'+start_time+'"></div>' +
          '<div class="col-md-6 padding_00" style="marin-top:80px"><h5 style="text-align:left"><strong>Select Event End Time: </strong></h5><input id="eventendtime" class="form-control" placeholder="Event End Time" value="'+end_time+'"></div></div></div></div>',
          didOpen: () => {
                $("#eventstartdate").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                })
                $("#eventenddate").datetimepicker({
                   format: 'L',
                   format: 'DD-MMM-YYYY',
                   minDate: arg.startStr,
                })
                $("#eventstarttime").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventendtime").datetimepicker({
                   format: 'L',
                   format: 'HH:mm',
                })
                $("#eventstartdate").on("dp.change", function (e) {
                    $('#eventenddate').data("DateTimePicker").minDate(e.date);
                });
                $("#eventstarttime").on("dp.change", function (e) {
                    $('#eventendtime').data("DateTimePicker").minDate(e.date);
                    $('#eventendtime').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
                });

                $("#eventclient").autocomplete({
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
                    $("#eventclientid").val(ui.item.id);
                  }
                });
          },
          focusConfirm: false,
          preConfirm: () => {
            return [
                document.getElementById('eventtitle').value,
                document.getElementById('eventdescription').value,
                document.getElementById('eventstartdate').value,
                document.getElementById('eventenddate').value,
                document.getElementById('eventstarttime').value,
                document.getElementById('eventendtime').value,
                document.getElementById('eventclient').value,
                document.getElementById('eventclientid').value
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
  function customizeWeekHeaders() {
        var weekHeaders = calendarEl.querySelectorAll('.fc-col-header-cell-cushion');
        weekHeaders.forEach(function(header) {
            var dateString = header.getAttribute('aria-label');
            var date = new Date(dateString);
            var formattedDate = date.toLocaleDateString('en', { weekday: 'short', day: 'numeric', month: 'short' });
            header.innerHTML = formattedDate;
        });
    }
  calendar.render();
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
  showDayCalendar('<?php echo $currentData; ?>','dayGridMonth');
  showDayCalendar2('<?php echo $currentData; ?>');
});
</script>
<!-- 
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.min.css" rel="stylesheet">
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/web-component@6.1.8/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js'></script>
<div id="calender" style="width:70%;margin-top:7%">
<full-calendar shadow options='{
    "headerToolbar": {
      "left": "prev,next today",
      "center": "title",
      "right": "dayGridMonth,dayGridWeek"
    }
  }' />
</div>

<script>
const fullCalendarElement = document.querySelector('full-calendar')

fullCalendarElement.options = {
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek'
  },
  editable: true,
  selectable: true,
  selectHelper: true,
}
</script> -->

@stop