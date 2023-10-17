<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\RctController;
use App\Http\Controllers\user\AdminuserController;

Route::get("/user/logout", [UserController::class, "logout"]);
Route::get("/user/manage_week", [UserController::class, "manageweek"]);
Route::get("/user/downloadpdf", [UserController::class, "downloadpdf"]);
Route::get("/user/week_manage/{id?}", [UserController::class, "weekmanage"]);
Route::get("/user/select_week/{id?}", [UserController::class, "selectweek"]);
Route::post("/user/add_new_task/", [UserController::class, "addnewtask"]);
Route::get("/user/delete_task/{id?}", [UserController::class, "deletetask"]);
Route::get("/user/task_enterhours", [UserController::class, "task_enterhours"]);
Route::get(
    "/user/task_started_checkbox",
    [UserController::class, "task_started_checkbox"]
);
Route::get("/user/notify_tasks", [UserController::class, "notify_tasks"]);
Route::get(
    "/user/notify_tasks_month",
    [UserController::class, "notify_tasks_month"]
);
Route::post(
    "/user/taskmanager_upload_images",
    [UserController::class, "taskmanager_upload_images"]
);
Route::post(
    "/user/remove_dropzone_attachment",
    [UserController::class, "remove_dropzone_attachment"]
);
Route::get("/user/task_holiday", [UserController::class, "task_holiday"]);
Route::get("/user/task_process", [UserController::class, "task_process"]);
Route::get("/user/task_payslips", [UserController::class, "task_payslips"]);
Route::get("/user/task_email", [UserController::class, "task_email"]);
Route::get("/user/task_upload", [UserController::class, "task_upload"]);
Route::get("/user/task_date_update", [UserController::class, "task_date_update"]);
Route::get("/user/task_email_update", [UserController::class, "task_email_update"]);
Route::get("/user/task_users_update", [UserController::class, "task_users_update"]);
Route::get(
    "/user/task_comments_update",
    [UserController::class, "task_comments_update"]
);
Route::get(
    "/user/task_liability_update",
    [UserController::class, "task_liability_update"]
);
Route::get(
    "/user/task_classified_update",
    [UserController::class, "task_classified_update"]
);
Route::post("/user/task_image_upload", [UserController::class, "task_image_upload"]);
Route::post(
    "/user/task_notepad_upload",
    [UserController::class, "task_notepad_upload"]
);
Route::post("/user/copy_task", [UserController::class, "copy_task"]);
Route::get("/user/task_delete_image", [UserController::class, "task_delete_image"]);
Route::get(
    "/user/task_delete_all_image",
    [UserController::class, "task_delete_all_image"]
);
Route::get(
    "/user/task_delete_all_image_attachments",
    [UserController::class, "task_delete_all_image_attachments"]
);
Route::get(
    "/user/task_status_update",
    [UserController::class, "task_status_update"]
);
Route::get("/user/get_week_by_year", [UserController::class, "get_week_by_year"]);
Route::get("/user/get_month_by_year", [UserController::class, "get_month_by_year"]);
Route::post(
    "/user/email_unsent_files",
    [UserController::class, "email_unsent_files"]
);
Route::get("/user/email_report_pdf", [UserController::class, "email_report_pdf"]);
Route::get("/user/email_notify_pdf", [UserController::class, "email_notify_pdf"]);
Route::get(
    "/user/email_notify_tasks_pdf",
    [UserController::class, "email_notify_tasks_pdf"]
);
Route::get(
    "/user/email_notify_pdf_month",
    [UserController::class, "email_notify_pdf_month"]
);
Route::post("/user/email_report_send", [UserController::class, "email_report_send"]);
Route::get(
    "/user/close_create_new_week/{id?}",
    [UserController::class, "close_create_new_week"]
);
Route::get(
    "/user/close_create_new_month/{id?}",
    [UserController::class, "close_create_new_month"]
);
Route::get("/user/manage_month", [UserController::class, "managemonth"]);
Route::get("/user/month_manage/{id?}", [UserController::class, "monthmanage"]);
Route::get("/user/select_month/{id?}", [UserController::class, "selectmonth"]);
Route::post(
    "/user/add_new_task_month/",
    [UserController::class, "addnewtask_month"]
);
Route::get(
    "/user/email_report_pdf_month",
    [UserController::class, "email_report_pdf_month"]
);
Route::get(
    "/user/alltask_report_pdf_month",
    [UserController::class, "alltask_report_pdf_month"]
);
Route::get(
    "/user/task_complete_report_pdf_month",
    [UserController::class, "task_complete_report_pdf_month"]
);
Route::get(
    "/user/task_incomplete_report_pdf_month",
    [UserController::class, "task_incomplete_report_pdf_month"]
);
Route::get(
    "/user/alltask_report_pdf",
    [UserController::class, "alltask_report_pdf"]
);
Route::get(
    "/user/task_complete_report_pdf",
    [UserController::class, "task_complete_report_pdf"]
);
Route::get(
    "/user/task_incomplete_report_pdf",
    [UserController::class, "task_incomplete_report_pdf"]
);
Route::get("/user/edit_task_name", [UserController::class, "edit_task_name"]);
Route::get(
    "/user/edit_email_unsent_files",
    [UserController::class, "edit_email_unsent_files"]
);
Route::post("/user/edit_task_details", [UserController::class, "edit_task_details"]);
Route::post(
    "/user/update_incomplete_status",
    [UserController::class, "update_incomplete_status"]
);
Route::post(
    "/user/update_incomplete_status_month",
    [UserController::class, "update_incomplete_status_month"]
);
Route::get("/user/vat_clients", [UserController::class, "vatclients"]);
Route::get("/user/vat_review", [UserController::class, "vat_review"]);
Route::post(
    "/user/load_all_vat_clients",
    [UserController::class, "load_all_vat_clients"]
);
Route::post("/user/show_prev_month", [UserController::class, "show_prev_month"]);
Route::post("/user/show_next_month", [UserController::class, "show_next_month"]);
Route::post(
    "/user/change_period_vat_reviews",
    [UserController::class, "change_period_vat_reviews"]
);
Route::post(
    "/user/check_submitted_date_vat_reviews",
    [UserController::class, "check_submitted_date_vat_reviews"]
);
Route::post("/user/vat_upload_images", [UserController::class, "vat_upload_images"]);
Route::post("/user/vat_upload_csv", [UserController::class, "vat_upload_csv"]);
Route::get("/user/remove_vat_csv/{id?}", [UserController::class, "remove_vat_csv"]);
Route::post(
    "/user/delete_submitted_vat_review",
    [UserController::class, "delete_submitted_vat_review"]
);
Route::post(
    "/user/check_valid_ros_due",
    [UserController::class, "check_valid_ros_due"]
);
Route::post(
    "/user/save_vat_review_date",
    [UserController::class, "save_vat_review_date"]
);
Route::post(
    "/user/save_textval_review",
    [UserController::class, "save_textval_review"]
);
Route::post("/user/save_t1_textbox", [UserController::class, "save_t1_textbox"]);
Route::post("/user/save_t2_textbox", [UserController::class, "save_t2_textbox"]);
Route::post(
    "/user/change_t_approve_status",
    [UserController::class, "change_t_approve_status"]
);
Route::post("/user/delete_vat_files", [UserController::class, "delete_vat_files"]);
Route::get(
    "/user/process_vat_reviews/{id?}",
    [UserController::class, "process_vat_reviews"]
);
Route::get(
    "/user/process_vat_reviews_one/{id?}",
    [UserController::class, "process_vat_reviews_one"]
);
Route::get(
    "/user/deactive_vat_clients/{id?}",
    [UserController::class, "deactivevatclients"]
);
Route::get(
    "/user/active_vat_clients/{id?}",
    [UserController::class, "activevatclients"]
);
Route::post(
    "/user/edit_vat_clients/{id?}",
    [UserController::class, "editvatclients"]
);
Route::post("/user/add_vat_clients", [UserController::class, "addvatclients"]);
Route::post(
    "/user/update_vat_clients/",
    [UserController::class, "updatevatclients"]
);
Route::get("/user/check_client_email", [UserController::class, "checkclientemail"]);
Route::get(
    "/user/check_client_taxnumber",
    [UserController::class, "checkclienttaxnumber"]
);
Route::post("/user/import_form", [UserController::class, "import_form"]);
Route::get("/user/import_form_one", [UserController::class, "import_form_one"]);
Route::post("/user/compare_form", [UserController::class, "compare_form"]);
Route::get("/user/compare_form_one", [UserController::class, "compare_form_one"]);
Route::get("/user/vat_notifications", [UserController::class, "vat_notifications"]);
Route::get("/user/import_sessions", [UserController::class, "import_sessions"]);
Route::get(
    "/user/import_sessions_one",
    [UserController::class, "import_sessions_one"]
);
Route::get(
    "/user/email_vatnotifications",
    [UserController::class, "email_vatnotifications"]
);
Route::get("/user/email_sents", [UserController::class, "email_sents"]);
Route::get(
    "/user/email_sents_save_pdf",
    [UserController::class, "email_sents_save_pdf"]
);
Route::get("/user/pdf_without_email", [UserController::class, "pdf_without_email"]);
Route::get("/user/pdf_with_email", [UserController::class, "pdf_with_email"]);
Route::get("/user/pdf_disabled", [UserController::class, "pdf_disabled"]);
Route::post(
    "/user/save_disclose_liability",
    [UserController::class, "save_disclose_liability"]
);
Route::post(
    "/user/getclientcompanyname",
    [UserController::class, "getclientcompanyname"]
);
Route::post("/user/getclientemail", [UserController::class, "getclientemail"]);
Route::post(
    "/user/getclientemail_secondary",
    [UserController::class, "getclientemail_secondary"]
);
Route::post(
    "/user/getclient_salutation",
    [UserController::class, "getclient_salutation"]
);
Route::get("/user/vat_client_search", [UserController::class, "vat_client_search"]);
Route::get(
    "/user/vat_client_search_select",
    [UserController::class, "vat_clientsearchselect"]
);
Route::get("/user/dashboard", [UserController::class, "dashboard"]);
Route::get("/user/dashboard_new", [UserController::class, "dashboard_new"]);
Route::get(
    "/user/task_client_search",
    [UserController::class, "task_client_search"]
);
Route::get(
    "/user/task_client_search_select",
    [UserController::class, "task_clientsearchselect"]
);
Route::post(
    "/user/load_dashboard_tiles",
    [UserController::class, "load_dashboard_tiles"]
);
Route::post(
    "/user/load_all_dashboard_tiles",
    [UserController::class, "load_all_dashboard_tiles"]
);
Route::get(
    "/user/resendedit_email_unsent_files",
    [UserController::class, "resendedit_email_unsent_files"]
);
Route::get(
    "/user/task_complete_update/",
    [UserController::class, "task_complete_update"]
);
Route::get("/user/time_track", [UserController::class, "time_track"]);
Route::get(
    "/user/email_report_generator",
    [UserController::class, "email_report_generator"]
);
Route::get(
    "/user/donot_complete_task_details/",
    [UserController::class, "donot_complete_task_details"]
);
Route::get(
    "/user/task_complete_update_new/",
    [UserController::class, "task_complete_update_new"]
);
Route::get(
    "/user/task_default_users_update",
    [UserController::class, "task_default_users_update"]
);
Route::post(
    "/admin/manage_user_costing",
    "admin\UserController@manageusercosting"
);
Route::post(
    "/admin/user_costing_update",
    "admin\UserController@usercostingupdate"
);
Route::post(
    "/admin/manage_user_cost_add",
    "admin\UserController@manageusercostadd"
);
Route::post(
    "/admin/manage_user_costing_delete",
    "admin\UserController@manageusercostingdelete"
);
Route::post(
    "/user/get_clientname_from_pms",
    [UserController::class, "get_clientname_from_pms"]
);
Route::post("/user/add_scheme", [UserController::class, "add_scheme"]);
Route::post(
    "/user/set_scheme_for_task",
    [UserController::class, "set_scheme_for_task"]
);
Route::post(
    "/user/check_previous_week",
    [UserController::class, "check_previous_week"]
);
Route::post(
    "/user/check_previous_month",
    [UserController::class, "check_previous_month"]
);
Route::get(
    "/user/change_scheme_status",
    [UserController::class, "change_scheme_status"]
);
Route::post(
    "/user/secret_task_button",
    [UserController::class, "secret_task_button"]
);
Route::post(
    "/user/get_pms_file_attachments",
    [UserController::class, "get_pms_file_attachments"]
);
Route::post(
    "/user/current_payroll_list",
    [UserController::class, "current_payroll_list"]
);
Route::post("/user/start_rating", [UserController::class, "start_rating"]);
Route::post(
    "/user/update_records_received",
    [UserController::class, "update_records_received"]
);
Route::post(
    "/user/show_month_in_overlay",
    [UserController::class, "show_month_in_overlay"]
);
Route::post(
    "/user/export_month_in_overlay",
    [UserController::class, "export_month_in_overlay"]
);
Route::post(
    "/user/update_email_setting",
    [UserController::class, "update_email_setting"]
);
Route::post(
    "/user/show_journal_viewer_by_journal_id",
    [UserController::class, "show_journal_viewer_by_journal_id"]
);
Route::post(
    "/user/download_journal_viewer_by_journal_id",
    [UserController::class, "download_journal_viewer_by_journal_id"]
);
Route::post(
    "/user/get_client_review_for_year",
    [UserController::class, "get_client_review_for_year"]
);
Route::post(
    "/user/download_selected_periods_vat_attachments",
    [UserController::class, "download_selected_periods_vat_attachments"]
);
Route::get("/user/payroll_settings", [UserController::class, "payroll_settings"]);
Route::post(
    "/user/save_payroll_settings",
    [UserController::class, "save_payroll_settings"]
);
Route::get(
    "/user/updatestatus_timetrack/",
    [UserController::class, "updatestatus_timetrack"]
);

/*******************************************************************/
Route::get("/user/manage_user", [AdminuserController::class, "manageuser"]);
Route::get(
    "/user/deactive_user/{id?}",
    [AdminuserController::class, "deactiveuser"]
);
Route::get("/user/active_user/{id?}", [AdminuserController::class, "activeuser"]);
Route::post("/user/add_user/", [AdminuserController::class, "adduser"]);
Route::get("/user/delete_user/{id?}", [AdminuserController::class, "deleteuser"]);
Route::post("/user/edit_user/{id?}", [AdminuserController::class, "edituser"]);
Route::post("/user/update_user/", [AdminuserController::class, "updateuser"]);
Route::post(
    "/user/manage_user_costing",
    [AdminuserController::class, "manageusercosting"]
);
Route::post(
    "/user/user_costing_update",
    [AdminuserController::class, "usercostingupdate"]
);
Route::post(
    "/user/manage_user_cost_add",
    [AdminuserController::class, "manageusercostadd"]
);
Route::post(
    "/user/manage_user_costing_delete",
    [AdminuserController::class, "manageusercostingdelete"]
);
Route::get(
    "/user/check_user_email",
    [AdminuserController::class, "check_user_email"]
);
/****************************************************/
Route::post(
    "/user/update_practice_setting",
    [UserController::class, "update_practice_setting"]
);
Route::post(
    "/user/vat_commit_upload_images",
    [UserController::class, "vat_commit_upload_images"]
);
Route::get(
    "/user/reset_vat_reviews_folder",
    [UserController::class, "reset_vat_reviews_folder"]
);
Route::post(
    "/user/save_distribute_email",
    [UserController::class, "save_distribute_email"]
);
Route::post(
    "/user/vat_refresh_upload_images",
    [UserController::class, "vat_refresh_upload_images"]
);
Route::post(
    "/user/add_to_employer_list",
    [UserController::class, "add_to_employer_list"]
);
Route::post(
    "/user/manage_employer_users",
    [UserController::class, "manage_employer_users"]
);
Route::post(
    "/user/insert_employer_user",
    [UserController::class, "insert_employer_user"]
);
Route::post(
    "/user/change_employer_user_status",
    [UserController::class, "change_employer_user_status"]
);
Route::post(
    "/user/delete_employer_users",
    [UserController::class, "delete_employer_users"]
);
Route::post(
    "/user/update_distribute_link",
    [UserController::class, "update_distribute_link"]
);
Route::get(
    "/user/edit_email_unsent_files_distribute_by_link",
    [UserController::class, "edit_email_unsent_files_distribute_by_link"]
);
Route::post(
    "/user/user_logging_password_after_login",
    [UserController::class, "user_logging_password_after_login"]
);
Route::get("/user/audit_trail", [UserController::class, "audit_trail"]);
Route::get("/user/filter_by_user", [UserController::class, "filter_by_user"]);
Route::get("/user/filter_by_module", [UserController::class, "filter_by_module"]);
Route::get(
    "/user/show_more_user_audit",
    [UserController::class, "show_more_user_audit"]
);
Route::get(
    "/user/show_more_module_audit",
    [UserController::class, "show_more_module_audit"]
);
Route::post(
    "/user/update_bi_payroll_status",
    [UserController::class, "update_bi_payroll_status"]
);
Route::post(
    "/user/refresh_vat_approval_count",
    [UserController::class, "refresh_vat_approval_count"]
);
Route::post(
    "/user/show_vat_submission_approval_for_month",
    [UserController::class, "show_vat_submission_approval_for_month"]
);
Route::post(
    "/user/save_approval_comments_textbox",
    [UserController::class, "save_approval_comments_textbox"]
);
Route::post(
    "/user/approval_summary_content",
    [UserController::class, "approval_summary_content"]
);
Route::post(
    "/user/getclientlist_for_timemetask",
    [UserController::class, "getclientlist_for_timemetask"]
);
Route::get(
    "/user/remove_duplicate_journals",
    [UserController::class, "remove_duplicate_journals"]
);
Route::post(
     "/user/update_user_notification",
     [UserController::class, 'update_user_notification']  
);
Route::post(
    "/user/update_user_signature",
     [UserController::class, 'update_user_signature']  
);
Route::get(
    "/user/rctclient_checkemail",
     [RctController::class, 'client_checkemail']  
);

Route::post(
    "/user/update_vat_review_settings",
     [UserController::class, 'update_vat_review_settings']  
);

Route::post(
    "/user/email_vat_notification_details",
     [UserController::class, 'email_vat_notification_details']  
);
Route::post(
    "/user/send_email_vat_review_notification",
     [UserController::class, 'send_email_vat_review_notification']  
);

