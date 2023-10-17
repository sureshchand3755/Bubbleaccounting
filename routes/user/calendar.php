<?php
use App\Http\Controllers\user\CalendarController;

Route::get("/user/staff_calendar", [CalendarController::class, "staff_calenders"]);
Route::get("/user/fetchevents", [CalendarController::class, "fetchevents"]);
Route::post("/user/calendarEvents", [CalendarController::class, "calendarEvents"]);