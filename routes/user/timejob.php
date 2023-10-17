<?php
use App\Http\Controllers\user\TimejobController;

Route::get(
    "/user/timesystem_client_search",
    [TimejobController::class, "timesystem_client_search"]
);
Route::get(
    "/user/timesystem_client_search_select",
    [TimejobController::class, "timesystem_clientsearchselect"]
);
Route::get(
    "/user/timesystem_client_search_select_tasks",
    [TimejobController::class, "timesystem_client_search_select_tasks"]
);
Route::post("/user/time_job_add", [TimejobController::class, "timejobadd"]);
Route::post("/user/time_job_edit", [TimejobController::class, "time_job_edit"]);
Route::post("/user/time_job_stop", [TimejobController::class, "timejobstop"]);
Route::post(
    "/user/time_job_stop_quick",
    [TimejobController::class, "timejobstopquick"]
);
Route::post(
    "/user/edit_time_job_update",
    [TimejobController::class, "edit_time_job_update"]
);
Route::get("/user/stop_job_details", [TimejobController::class, "stop_job_details"]);
Route::post("/user/job_add_break", [TimejobController::class, "jobaddbreak"]);
Route::get(
    "/user/break_time_details",
    [TimejobController::class, "breaktimedetails"]
);
Route::get("/user/job_user_filter", [TimejobController::class, "jobuserfilter"]);
Route::get("/user/time_me_overview", [TimejobController::class, "time_active_job"]);
Route::get(
    "/user/time_me_joboftheday",
    [TimejobController::class, "time_joboftheday"]
);
Route::get(
    "/user/time_me_client_review",
    [TimejobController::class, "time_client_review"]
);
Route::get("/user/time_me_all_job", [TimejobController::class, "time_all_job"]);
Route::get("/user/staff_review", [TimejobController::class, "staff_review"]);
Route::get(
    "/user/search_staff_review",
    [TimejobController::class, "search_staff_review"]
);
Route::get(
    "/user/staff_review_download_as_pdf",
    [TimejobController::class, "staff_review_download_as_pdf"]
);
Route::post(
    "/user/job_time_count_refresh",
    [TimejobController::class, "jobtimecountrefresh"]
);
Route::post(
    "/user/active_job_report_csv",
    [TimejobController::class, "active_job_report_csv"]
);
Route::post(
    "/user/active_job_report_pdf",
    [TimejobController::class, "active_jobreportpdf"]
);
Route::post(
    "/user/active_job_report_pdf_download",
    [TimejobController::class, "active_jobreportpdfdownload"]
);
Route::post(
    "/user/all_job_report_csv",
    [TimejobController::class, "all_job_report_csv"]
);
Route::post(
    "/user/all_job_report_pdf",
    [TimejobController::class, "all_jobreportpdf"]
);
Route::post(
    "/user/all_job_report_pdf_download",
    [TimejobController::class, "all_jobreportpdfdownload"]
);
Route::post(
    "/user/joboftheday_report_csv",
    [TimejobController::class, "joboftheday_report_csv"]
);
Route::post(
    "/user/joboftheday_report_pdf",
    [TimejobController::class, "joboftheday_reportpdf"]
);
Route::post(
    "/user/joboftheday_report_pdf_download",
    [TimejobController::class, "joboftheday_report_pdf_download"]
);
Route::post(
    "/user/clientreview_report_csv",
    [TimejobController::class, "clientreview_report_csv"]
);
Route::post(
    "/user/clientreview_report_pdf",
    [TimejobController::class, "clientreview_report_pdf"]
);
Route::post(
    "/user/clientreview_report_pdf_download",
    [TimejobController::class, "clientreview_report_pdf_download"]
);
Route::get("/user/search_job_of_day", [TimejobController::class, "searchjobofday"]);
Route::get(
    "/user/search_client_review",
    [TimejobController::class, "search_client_review"]
);
Route::get("/user/get_job_details", [TimejobController::class, "get_job_details"]);
Route::post(
    "/user/check_time_me_user_active_job",
    [TimejobController::class, "check_time_me_user_active_job"]
);
Route::post(
    "/user/check_last_finished_job_time",
    [TimejobController::class, "check_last_finished_job_time"]
);
Route::get(
    "/user/get_quick_break_details",
    [TimejobController::class, "get_quick_break_details"]
);
Route::get(
    "/user/calculate_job_time",
    [TimejobController::class, "calculate_job_time"]
);
Route::get(
    "/user/calculate_break_time",
    [TimejobController::class, "calculate_break_time"]
);
Route::get("/user/edit_time_job", [TimejobController::class, "edit_time_job"]);
Route::post(
    "/user/select_presets_group",
    [TimejobController::class, "select_presets_group"]
);
Route::post(
    "/user/check_quick_time_availability",
    [TimejobController::class, "check_quick_time_availability"]
);
Route::post(
    "/user/quick_time_add",
    [TimejobController::class, "quick_time_add"]
);

