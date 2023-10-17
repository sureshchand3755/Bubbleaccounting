<?php
use App\Http\Controllers\user\Payep30Controller;

Route::get("/user/p30", [Payep30Controller::class, "p30"]);
Route::get("/user/p30_task_leval", [Payep30Controller::class, "tasklevel"]);
Route::get(
    "/user/deactive_p30_tasklevel/{id?}",
    [Payep30Controller::class, "deactivetasklevel"]
);
Route::get(
    "/user/active_p30_tasklevel/{id?}",
    [Payep30Controller::class, "activetasklevel"]
);
Route::post("/user/add_p30_tasklevel/", [Payep30Controller::class, "addtasklevel"]);
Route::post(
    "/user/edit_p30_tasklevel/{id?}",
    [Payep30Controller::class, "edittasklevel"]
);
Route::post(
    "/user/update_p30_tasklevel/",
    [Payep30Controller::class, "updatetasklevel"]
);
Route::post(
    "/user/update_paye_p30_first_year",
    [Payep30Controller::class, "update_paye_p30_first_year"]
);
Route::get(
    "/user/paye_p30_manage/{id?}",
    [Payep30Controller::class, "paye_p30_manage"]
);
Route::get(
    "/user/paye_p30_ros_liabilities/{id?}",
    [Payep30Controller::class, "paye_p30_ros_liabilities"]
);
Route::get(
    "/user/paye_p30_review_year/{id?}",
    [Payep30Controller::class, "paye_p30_review_year"]
);
Route::post(
    "/user/paye_p30_periods_update/",
    [Payep30Controller::class, "paye_p30_periods_update"]
);
Route::get(
    "/user/refresh_paye_p30_liability",
    [Payep30Controller::class, "refresh_paye_p30_liability"]
);
Route::post(
    "/user/paye_p30_periods_remove",
    [Payep30Controller::class, "paye_p30_periods_remove"]
);
Route::post(
    "/user/paye_p30_ros_update",
    [Payep30Controller::class, "paye_p30_ros_update"]
);
Route::post("/user/paye_p30_apply", [Payep30Controller::class, "paye_p30_apply"]);
Route::post(
    "/user/paye_p30_single_month",
    [Payep30Controller::class, "paye_p30_single_month"]
);
Route::post(
    "/user/paye_p30_all_month",
    [Payep30Controller::class, "paye_p30_all_month"]
);
Route::get(
    "/user/paye_p30_email_distribution/{id?}",
    [Payep30Controller::class, "paye_p30_email_distribution"]
);
Route::post(
    "/user/paye_p30_periods_month_update/",
    [Payep30Controller::class, "paye_p30_periods_month_update"]
);
Route::post(
    "/user/paye_p30_periods_month_remove",
    [Payep30Controller::class, "paye_p30_periods_month_remove"]
);
Route::post(
    "/user/paye_p30_active_periods",
    [Payep30Controller::class, "paye_p30_active_periods"]
);
Route::post(
    "/user/paye_p30_all_periods",
    [Payep30Controller::class, "paye_p30_all_periods"]
);
Route::get(
    "/user/paye_p30_edit_email_unsent_files",
    [Payep30Controller::class, "paye_p30_edit_email_unsent_files"]
);
Route::post(
    "/user/paye_p30_email_unsent_files",
    [Payep30Controller::class, "paye_p30_email_unsent_files"]
);
Route::post("/user/load_table_info", [Payep30Controller::class, "load_table_info"]);
Route::get(
    "/user/paye_p30_create_new_year",
    [Payep30Controller::class, "paye_p30_create_new_year"]
);
Route::post(
    "/user/paye_p30_week_selected",
    [Payep30Controller::class, "paye_p30_week_selected"]
);
Route::post(
    "/user/paye_p30_month_selected",
    [Payep30Controller::class, "paye_p30_month_selected"]
);
Route::post(
    "/user/update_paye_p30_clients_status",
    [Payep30Controller::class, "update_paye_p30_clients_status"]
);
Route::post(
    "/user/update_paye_p30_year_email_clients_status",
    [Payep30Controller::class, "update_paye_p30_year_email_clients_status"]
);
Route::post(
    "/user/update_paye_p30_year_disabled_status",
    [Payep30Controller::class, "update_paye_p30_year_disabled_status"]
);
Route::post(
    "/user/paye_p30_create_csv",
    [Payep30Controller::class, "paye_p30_create_csv"]
);
Route::get(
    "/user/check_paye_task_details",
    [Payep30Controller::class, "check_paye_task_details"]
);
Route::get(
    "/user/update_paye_task_details",
    [Payep30Controller::class, "update_paye_task_details"]
);
Route::post(
    "/user/payments_attachment",
    [Payep30Controller::class, "payments_attachment"]
);
Route::post(
    "/user/paye_p30_payment_update",
    [Payep30Controller::class, "paye_p30_payment_update"]
);
Route::post(
    "/user/update_ros_liability",
    [Payep30Controller::class, "update_ros_liability"]
);
Route::get("/user/load_table_all", [Payep30Controller::class, "load_table_all"]);
Route::get(
    "/user/get_employee_numbers",
    [Payep30Controller::class, "get_employee_numbers"]
);
Route::post(
    "/user/get_paye_email_distribution_table",
    [Payep30Controller::class, "get_paye_email_distribution_table"]
);
Route::post(
    "/user/send_email_to_paye_client",
    [Payep30Controller::class, "send_email_to_paye_client"]
);
Route::post(
    "/user/apply_task_to_ros",
    [Payep30Controller::class, "apply_task_to_ros"]
);
Route::post(
    "/user/report_active_month_csv",
    [Payep30Controller::class, "report_active_month_csv"]
);
Route::get(
    "/user/update_paye_task_liability",
    [Payep30Controller::class, "update_paye_task_liability"]
);
Route::get(
    "/user/set_client_id_paye_p30",
    [Payep30Controller::class, "set_client_id_paye_p30"]
);

