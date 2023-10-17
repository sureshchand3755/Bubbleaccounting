<?php
use App\Http\Controllers\user\TaskmanagerController;

Route::get("/user/task_manager", [TaskmanagerController::class, "task_manager"]);
Route::get("/user/park_task", [TaskmanagerController::class, "park_task"]);
Route::get(
    "/user/taskmanager_search",
    [TaskmanagerController::class, "taskmanager_search"]
);
Route::get(
    "/user/task_administration",
    [TaskmanagerController::class, "task_administration"]
);
Route::post("/user/show_infiles", [TaskmanagerController::class, "show_infiles"]);
Route::post(
    "/user/show_progress_infiles",
    [TaskmanagerController::class, "show_progress_infiles"]
);
Route::post(
    "/user/show_completion_infiles",
    [TaskmanagerController::class, "show_completion_infiles"]
);
Route::post(
    "/user/infile_upload_images_taskmanager_add",
    [TaskmanagerController::class, "infile_upload_images_taskmanager_add"]
);
Route::post(
    "/user/infile_upload_images_taskmanager_progress",
    [TaskmanagerController::class, "infile_upload_images_taskmanager_progress"]
);
Route::post(
    "/user/infile_upload_images_taskmanager_completion",
    [TaskmanagerController::class, "infile_upload_images_taskmanager_completion"]
);
Route::post(
    "/user/add_taskmanager_notepad_contents",
    [TaskmanagerController::class, "add_taskmanager_notepad_contents"]
);
Route::post(
    "/user/taskmanager_notepad_contents_progress",
    [TaskmanagerController::class, "taskmanager_notepad_contents_progress"]
);
Route::post(
    "/user/taskmanager_notepad_contents_completion",
    [TaskmanagerController::class, "taskmanager_notepad_contents_completion"]
);
Route::post(
    "/user/clear_session_task_attachments",
    [TaskmanagerController::class, "clear_session_task_attachments"]
);
Route::post(
    "/user/tasks_remove_dropzone_attachment",
    [TaskmanagerController::class, "tasks_remove_dropzone_attachment"]
);
Route::post(
    "/user/tasks_remove_notepad_attachment",
    [TaskmanagerController::class, "tasks_remove_notepad_attachment"]
);
Route::post(
    "/user/show_linked_infiles",
    [TaskmanagerController::class, "show_linked_infiles"]
);
Route::post(
    "/user/show_linked_progress_infiles",
    [TaskmanagerController::class, "show_linked_progress_infiles"]
);
Route::post(
    "/user/show_linked_completion_infiles",
    [TaskmanagerController::class, "show_linked_completion_infiles"]
);
Route::post(
    "/user/create_new_taskmanager_task",
    [TaskmanagerController::class, "create_new_taskmanager_task"]
);
Route::post(
    "/user/change_taskmanager_user",
    [TaskmanagerController::class, "change_taskmanager_user"]
);
Route::get(
    "/user/delete_taskmanager_files",
    [TaskmanagerController::class, "delete_taskmanager_files"]
);
Route::get(
    "/user/delete_taskmanager_notepad",
    [TaskmanagerController::class, "delete_taskmanager_notepad"]
);
Route::get(
    "/user/delete_taskmanager_infiles",
    [TaskmanagerController::class, "delete_taskmanager_infiles"]
);
Route::get(
    "/user/delete_taskmanager_yearend",
    [TaskmanagerController::class, "delete_taskmanager_yearend"]
);
Route::post(
    "/user/taskmanager_change_due_date",
    [TaskmanagerController::class, "taskmanager_change_due_date"]
);
Route::post(
    "/user/taskmanager_change_allocations",
    [TaskmanagerController::class, "taskmanager_change_allocations"]
);
Route::post(
    "/user/show_existing_comments",
    [TaskmanagerController::class, "show_existing_comments"]
);
Route::post(
    "/user/add_comment_specifics",
    [TaskmanagerController::class, "add_comment_specifics"]
);
Route::post(
    "/user/download_pdf_specifics",
    [TaskmanagerController::class, "download_pdf_specifics"]
);
Route::post(
    "/user/show_all_allocations",
    [TaskmanagerController::class, "show_all_allocations"]
);
Route::post(
    "/user/download_pdf_history",
    [TaskmanagerController::class, "download_pdf_history"]
);
Route::post(
    "/user/download_csv_history",
    [TaskmanagerController::class, "download_csv_history"]
);
Route::post(
    "/user/copy_task_details",
    [TaskmanagerController::class, "copy_task_details"]
);
Route::post(
    "/user/get_taskmanager_task_files",
    [TaskmanagerController::class, "get_taskmanager_task_files"]
);
Route::post(
    "/user/refresh_taskmanager",
    [TaskmanagerController::class, "refresh_taskmanager"]
);
Route::post(
    "/user/refresh_parktask",
    [TaskmanagerController::class, "refresh_parktask"]
);
Route::post(
    "/user/taskmanager_mark_complete",
    [TaskmanagerController::class, "taskmanager_mark_complete"]
);
Route::post(
    "/user/taskmanager_mark_incomplete",
    [TaskmanagerController::class, "taskmanager_mark_incomplete"]
);
Route::post(
    "/user/search_taskmanager_task",
    [TaskmanagerController::class, "search_taskmanager_task"]
);

Route::post(
    "/user/search_taskmanager_consolidate",
    [TaskmanagerController::class, "search_taskmanager_consolidate_task"]
);
Route::post(
    "/user/consolidate_process",
    [TaskmanagerController::class, "consolidate_process"]
);
Route::post(
    "/user/update_taskmanager_details",
    [TaskmanagerController::class, "update_taskmanager_details"]
);
Route::post(
    "/user/show_more_tasks",
    [TaskmanagerController::class, "show_more_tasks"]
);
Route::post(
    "/user/download_taskmanager_task_pdf",
    [TaskmanagerController::class, "download_taskmanager_task_pdf"]
);
Route::get(
    "/user/view_taskmanager_task/{task_id?}",
    [TaskmanagerController::class, "view_taskmanager_task"]
);
Route::post(
    "/user/set_progress_value",
    [TaskmanagerController::class, "set_progress_value"]
);
Route::post(
    "/user/set_avoid_email_taskmanager",
    [TaskmanagerController::class, "set_avoid_email_taskmanager"]
);
Route::post(
    "/user/get_task_redline_notification",
    [TaskmanagerController::class, "get_task_redline_notification"]
);
Route::post(
    "/user/request_update",
    [TaskmanagerController::class, "request_update"]
);
Route::post(
    "/user/add_comment_and_allocate",
    [TaskmanagerController::class, "add_comment_and_allocate"]
);
Route::post(
    "/user/change_task_name_taskmanager",
    [TaskmanagerController::class, "change_task_name_taskmanager"]
);
Route::post(
    "/user/park_task_complete",
    [TaskmanagerController::class, "park_task_complete"]
);
Route::post(
    "/user/park_task_incomplete",
    [TaskmanagerController::class, "park_task_incomplete"]
);
Route::post(
    "/user/reactivate_park_task",
    [TaskmanagerController::class, "reactivate_park_task"]
);
Route::post(
    "/user/change_auto_close_status",
    [TaskmanagerController::class, "change_auto_close_status"]
);
Route::post(
    "/user/add_comment_and_allocate_to",
    [TaskmanagerController::class, "add_comment_and_allocate_to"]
);

Route::post(
    "/user/show_completion_yearend",
    [TaskmanagerController::class, "show_completion_yearend"]
);
Route::post(
    "/user/show_linked_completion_yearend",
    [TaskmanagerController::class, "show_linked_completion_yearend"]
);

Route::post(
    "/user/change_taskmanager_park_status",
    [TaskmanagerController::class, "change_taskmanager_park_status"]
);
Route::post(
    "/user/edit_task_details_admin_screen",
    [TaskmanagerController::class, "edit_task_details_admin_screen"]
);
Route::post(
    "/user/download_export_csv_task_manager",
    [TaskmanagerController::class, "download_export_csv_task_manager"]
);
Route::post(
    "/user/download_export_csv_task_search",
    [TaskmanagerController::class, "download_export_csv_task_search"]
);
Route::get(
    "/user/taskmanager_download_all_progress_files",
    [TaskmanagerController::class, "taskmanager_download_all_progress_files"]
);
Route::post(
    "/user/create_new_taskmanager_task_vat",
    [TaskmanagerController::class, "create_new_taskmanager_task_vat"]
);
Route::post(
    "/user/create_new_taskmanager_task_croard",
    [TaskmanagerController::class, "create_new_taskmanager_task_croard"]
);
Route::post(
    "/user/integrity_check_all_tasks",
    [TaskmanagerController::class, "integrity_check_all_tasks"]
);
Route::post(
    "/user/export_integrity_tasks",
    [TaskmanagerController::class, "export_integrity_tasks"]
);
Route::post(
    "/user/get_author_email_for_taskmanager",
    [TaskmanagerController::class, "get_author_email_for_taskmanager"]
);
Route::post(
    "/user/check_integrity_for_task",
    [TaskmanagerController::class, "check_integrity_for_task"]
);
Route::get("/user/task_overview", [TaskmanagerController::class, "task_overview"]);
Route::post(
    "/user/load_employee_details_overview",
    [TaskmanagerController::class, "load_employee_details_overview"]
);
Route::post(
    "/user/load_client_details_overview",
    [TaskmanagerController::class, "load_client_details_overview"]
);
Route::post(
    "/user/add_edit_project",
    [TaskmanagerController::class, "add_edit_project"]
);
Route::post(
    "/user/project_details",
    [TaskmanagerController::class, "project_details"]
);
Route::post(
    "/user/save_attachments_messages",
    [TaskmanagerController::class, "save_attachments_messages"]
);
Route::post(
    "/user/store_user_rating_taskmanager",
    [TaskmanagerController::class, "store_user_rating_taskmanager"]
);
Route::post(
    "/user/change_taskmanager_view_layout",
    [TaskmanagerController::class, "change_taskmanager_view_layout"]
);
Route::get("/user/task_analysis", [TaskmanagerController::class, "task_analysis"]);
Route::post(
    "/user/load_task_analysis",
    [TaskmanagerController::class, "load_task_analysis"]
);
Route::post(
    "/user/user_get_linked_infile_items",
    [TaskmanagerController::class, "user_get_linked_infile_items"]
);
Route::get("/user/infile_download_bpso_all_image_taskmanager", [TaskmanagerController::class, "infile_download_bpso_all_image_taskmanager"]);
Route::post("/user/user_download_completion_files", [TaskmanagerController::class, "user_download_completion_files"]);
Route::post("/user/user_download_completion_files_only", [TaskmanagerController::class, "user_download_completion_files_only"]);
Route::post(
    "/user/edit_taskmanager_header_image",
    [TaskmanagerController::class, 'edit_taskmanager_header_image']
);
Route::post(
    "/user/save_taskmanager_settings",
    [TaskmanagerController::class, 'save_taskmanager_settings']
);
Route::post(
    "/user/quick_task_view",
    [TaskmanagerController::class, 'quick_task_view']
);