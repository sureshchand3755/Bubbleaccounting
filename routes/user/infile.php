<?php
use App\Http\Controllers\user\InfileController;

Route::get("/user/in_file/", [InfileController::class, "infile"]);
Route::get("/user/in_file_advance/", [InfileController::class, "infile_advance"]);
Route::get(
    "/user/infile_user_update/",
    [InfileController::class, "infile_userupdate"]
);
Route::get(
    "/user/infile_complete_date/",
    [InfileController::class, "infile_completedate"]
);
Route::get(
    "/user/in_file_status_update/",
    [InfileController::class, "in_file_statusupdate"]
);
Route::post(
    "/user/in_file_show_incomplete/",
    [InfileController::class, "in_file_showincomplete"]
);
Route::get(
    "/user/infile_client_search",
    [InfileController::class, "infile_client_search"]
);
Route::get(
    "/user/infile_client_search_select",
    [InfileController::class, "infile_clientsearchselect"]
);
Route::post(
    "/user/infile_image_upload",
    [InfileController::class, "infile_imageupload"]
);
Route::post(
    "/user/infile_upload_images",
    [InfileController::class, "infile_upload_images"]
);
Route::post(
    "/user/infile_remove_dropzone_attachment",
    [InfileController::class, "remove_dropzone_attachment"]
);
Route::get(
    "/user/infile_delete_image",
    [InfileController::class, "infile_delete_image"]
);
Route::get(
    "/user/infile_delete_all_image",
    [InfileController::class, "infile_delete_all_image"]
);
Route::get(
    "/user/infile_download_image",
    [InfileController::class, "infile_download_image"]
);
Route::get(
    "/user/infile_download_all_image",
    [InfileController::class, "infile_download_all_image"]
);
Route::get(
    "/user/infile_download_rename_all_image",
    [InfileController::class, "infile_download_rename_all_image"]
);
Route::get(
    "/user/infile_download_bpso_all_image",
    [InfileController::class, "infile_download_bpso_all_image"]
);
Route::post(
    "/user/infile_notepad_upload",
    [InfileController::class, "infile_notepad_upload"]
);
Route::post(
    "/user/infile_notepad_upload_notes",
    [InfileController::class, "infile_notepad_upload_notes"]
);
Route::get(
    "/user/infile_delete_all_notes_only",
    [InfileController::class, "infile_delete_all_notes_only"]
);
Route::get(
    "/user/infile_delete_all_notes",
    [InfileController::class, "infile_delete_all_notes"]
);
Route::get(
    "/user/infile_download_all_notes_only",
    [InfileController::class, "infile_download_all_notes_only"]
);
Route::get(
    "/user/infile_download_all_notes",
    [InfileController::class, "infile_download_all_notes"]
);
Route::get(
    "/user/task_client_common_search",
    [InfileController::class, "infile_commonclient_search"]
);
Route::get(
    "/user/task_client_common_search_select",
    [InfileController::class, "infile_commonclientsearchselect"]
);
Route::post(
    "/user/add_notepad_contents",
    [InfileController::class, "add_notepad_contents"]
);
Route::post(
    "/user/infile_upload_images_add",
    [InfileController::class, "infile_upload_images_add"]
);
Route::post("/user/create_new_file", [InfileController::class, "create_new_file"]);
Route::post(
    "/user/clear_session_attachments",
    [InfileController::class, "clear_session_attachments"]
);
Route::post("/user/delete_file_link", [InfileController::class, "delete_file_link"]);
Route::get("/user/infile_search", [InfileController::class, "infile_search"]);
Route::get("/user/infile_internal", [InfileController::class, "infile_internal"]);
Route::post(
    "/user/fileattachment_status",
    [InfileController::class, "fileattachment_status"]
);
Route::get(
    "/user/infile_email_notify_tasks_pdf",
    [InfileController::class, "infile_email_notify_tasks_pdf"]
);
Route::get(
    "/user/change_attachment_text_status",
    [InfileController::class, "change_attachment_text_status"]
);
Route::get(
    "/user/remove_attachment_text_status",
    [InfileController::class, "remove_attachment_text_status"]
);
Route::get(
    "/user/update_fileattachment_textval",
    [InfileController::class, "update_fileattachment_textval"]
);
Route::get(
    "/user/get_attachment_details",
    [InfileController::class, "get_attachment_details"]
);
Route::get(
    "/user/infile_task_client_search",
    [InfileController::class, "infile_task_client_search"]
);

Route::post("/user/report_infile", [InfileController::class, "report_infile"]);
Route::post(
    "/user/infile_report_pdf",
    [InfileController::class, "infile_report_pdf"]
);
Route::post(
    "/user/download_infile_report_pdf",
    [InfileController::class, "download_infile_report_pdf"]
);
Route::post(
    "/user/infile_report_csv",
    [InfileController::class, "infile_report_csv"]
);
Route::post(
    "/user/infile_report_csv_single",
    [InfileController::class, "infile_report_csv_single"]
);
Route::post(
    "/user/infile_report_pdf_single",
    [InfileController::class, "infile_report_pdf_single"]
);
Route::post(
    "/user/download_infile_report_pdf_single",
    [InfileController::class, "download_infile_report_pdf_single"]
);
Route::post(
    "/user/infile_report_incomplete",
    [InfileController::class, "infile_report_incomplete"]
);
Route::post(
    "/user/change_attachment_bpso_status",
    [InfileController::class, "change_attachment_bpso_status"]
);
Route::post(
    "/user/infile_incomplete_status",
    [InfileController::class, "infile_incomplete_status"]
);
Route::post("/user/bpso_all_check", [InfileController::class, "bpso_all_check"]);
Route::post(
    "/user/get_supplier_names_from_infile",
    [InfileController::class, "get_supplier_names_from_infile"]
);
Route::post(
    "/user/set_supplier_names_from_infile",
    [InfileController::class, "set_supplier_names_from_infile"]
);
Route::post(
    "/user/change_percent_value",
    [InfileController::class, "change_percent_value"]
);
Route::get(
    "/user/infile_supplier_search",
    [InfileController::class, "infile_supplier_search"]
);
Route::post(
    "/user/infile_supplier_search_select",
    [InfileController::class, "infile_supplier_search_select"]
);
Route::get(
    "/user/update_supplier_infile_attachment",
    [InfileController::class, "update_supplier_infile_attachment"]
);
Route::get(
    "/user/update_percent_one_infile_attachment",
    [InfileController::class, "update_percent_one_infile_attachment"]
);
Route::get(
    "/user/update_percent_two_infile_attachment",
    [InfileController::class, "update_percent_two_infile_attachment"]
);
Route::get(
    "/user/update_percent_three_infile_attachment",
    [InfileController::class, "update_percent_three_infile_attachment"]
);
Route::get(
    "/user/update_percent_four_infile_attachment",
    [InfileController::class, "update_percent_four_infile_attachment"]
);
Route::get(
    "/user/update_percent_five_infile_attachment",
    [InfileController::class, "update_percent_five_infile_attachment"]
);
Route::post(
    "/user/infile_attachment_date_filled",
    [InfileController::class, "infile_attachment_date_filled"]
);
Route::post(
    "/user/infile_attachment_code_filled",
    [InfileController::class, "infile_attachment_code_filled"]
);
Route::post(
    "/user/infile_attachment_currency_filled",
    [InfileController::class, "infile_attachment_currency_filled"]
);
Route::post(
    "/user/infile_attachment_value_filled",
    [InfileController::class, "infile_attachment_value_filled"]
);
Route::post(
    "/user/save_imported_status",
    [InfileController::class, "save_imported_status"]
);
Route::post(
    "/user/save_imported_date",
    [InfileController::class, "save_imported_date"]
);
Route::get(
    "/user/infile_download_bpso_all_image_csv",
    [InfileController::class, "infile_download_bpso_all_image_csv"]
);
Route::get(
    "/user/infile_download_bpso_all_image_both",
    [InfileController::class, "infile_download_bpso_all_image_both"]
);
Route::post(
    "/user/change_show_hide_ps_status",
    [InfileController::class, "change_show_hide_ps_status"]
);
Route::get(
    "/user/file_not_supported",
    [InfileController::class, "file_not_supported"]
);
Route::post("/user/check_pdf_pages", [InfileController::class, "check_pdf_pages"]);
Route::post(
    "/user/change_flag_status",
    [InfileController::class, "change_flag_status"]
);
Route::post(
    "/user/add_new_secondary_line",
    [InfileController::class, "add_new_secondary_line"]
);

Route::post(
    "/user/check_integrity_files",
    [InfileController::class, "check_integrity_files"]
);
Route::post(
    "/user/check_files_in_files",
    [InfileController::class, "check_files_in_files"]
);
Route::post(
    "/user/check_integrity_files_client_id",
    [InfileController::class, "check_integrity_files_client_id"]
);
Route::post(
    "/user/show_attachments_infile",
    [InfileController::class, "show_attachments_infile"]
);
Route::post(
    "/user/check_missing_files",
    [InfileController::class, "check_missing_files"]
);
Route::post(
    "/user/import_available_files",
    [InfileController::class, "import_available_files"]
);
Route::post(
    "/user/get_infile_check_reports",
    [InfileController::class, "get_infile_check_reports"]
);
Route::post(
    "/user/build_supplier_names_for_client_id",
    [InfileController::class, "build_supplier_names_for_client_id"]
);
Route::post(
    "/user/update_infile_textvalue_item",
    [InfileController::class, "update_infile_textvalue_item"]
);
Route::post(
    "/user/renumber_infile_textvalue_item",
    [InfileController::class, "renumber_infile_textvalue_item"]
);
Route::post(
    "/user/check_secondary_line_has_value",
    [InfileController::class, "check_secondary_line_has_value"]
);
Route::post(
    "/user/load_all_clients_infile_advanced",
    [InfileController::class, "load_all_clients_infile_advanced"]
);
Route::post(
    "/user/load_single_client_infile_advanced",
    [InfileController::class, "load_single_client_infile_advanced"]
);
Route::post(
    "/user/infile_edit_description",
    [InfileController::class, "infile_edit_description"]
);
Route::post(
    "/user/update_edit_description",
    [InfileController::class, "update_edit_description"]
);
Route::post(
    "/user/get_summary_infile_attachments",
    [InfileController::class, "get_summary_infile_attachments"]
);

Route::post(
    "user/change_file_status_to_zero",
    [InfileController::class, "change_file_status_to_zero"]
);
Route::post(
    "/user/calculate_infile_attachments_counts",
    [InfileController::class, "calculate_infile_attachments_counts"]
);
Route::post(
    "/user/get_supplier_names_from_infile_client_id",
    [InfileController::class, "get_supplier_names_from_infile_client_id"]
);
Route::post(
    "/user/build_supplier_names_client_for_client_id",
    [InfileController::class, "build_supplier_names_client_for_client_id"]
);
Route::post(
    "/user/save_supplier_names_for_client",
    [InfileController::class, "save_supplier_names_for_client"]
);
Route::post(
    "/user/submit_imported_supplier_for_client",
    [InfileController::class, "submit_imported_supplier_for_client"]
);
Route::post(
    "/user/export_supplier_names_for_client",
    [InfileController::class, "export_supplier_names_for_client"]
);
Route::post(
    "/user/save_apply_supplier_names_for_client",
    [InfileController::class, "save_apply_supplier_names_for_client"]
);
Route::post(
    "/user/clear_supplier_for_client",
    [InfileController::class, "clear_supplier_for_client"]
);
Route::post(
    "/user/update_infile_settings",
    [InfileController::class, "update_infile_settings"]
);
Route::post(
    "/user/get_ps_data_items_count",
    [InfileController::class, "get_ps_data_items_count"]
);
Route::post(
    "/user/compare_ps_data",
    [InfileController::class, "compare_ps_data"]
);
Route::post(
    "/user/export_compare_ps_data",
    [InfileController::class, "export_compare_ps_data"]
);
Route::post(
    "/user/edit_infile_header_image",
    [InfileController::class, 'edit_infile_header_image']
);