<?php
use App\Http\Controllers\user\AmlController;

Route::get("/user/aml_system", [AmlController::class, "aml_system"]);
Route::post(
    "/user/update_aml_incomplete_status",
    [AmlController::class, "update_aml_incomplete_status"]
);
Route::post(
    "/user/aml_system_client_source_refresh",
    [AmlController::class, "aml_system_client_source_refresh"]
);
Route::post(
    "/user/aml_system_risk_update",
    [AmlController::class, "aml_system_risk_update"]
);
Route::get("/user/aml_client_search", [AmlController::class, "aml_client_search"]);
Route::get(
    "/user/aml_client_search_select",
    [AmlController::class, "aml_clientsearchselect"]
);
Route::post(
    "/user/aml_system_other_client",
    [AmlController::class, "aml_system_other_client"]
);
Route::post(
    "/user/aml_system_partner",
    [AmlController::class, "aml_system_partner"]
);
Route::post("/user/aml_system_note", [AmlController::class, "aml_system_note"]);
Route::post(
    "/user/aml_system_add_bank",
    [AmlController::class, "aml_system_add_bank"]
);
Route::post(
    "/user/aml_system_bank_details",
    [AmlController::class, "aml_system_bank_details"]
);

Route::post("/user/aml_system_review", [AmlController::class, "aml_system_review"]);
Route::post(
    "/user/aml_system_review_edit",
    [AmlController::class, "aml_system_review_edit"]
);
Route::post(
    "/user/aml_system_review_edit_update",
    [AmlController::class, "aml_system_review_edit_update"]
);
Route::post(
    "/user/aml_system_review_delete",
    [AmlController::class, "aml_system_review_delete"]
);
Route::post(
    "/user/aml_upload_images_add",
    [AmlController::class, "aml_upload_images_add"]
);
Route::post(
    "/user/aml_system_image_upload",
    [AmlController::class, "aml_system_image_upload"]
);
Route::post(
    "/user/aml_system_delete_attached",
    [AmlController::class, "aml_system_delete_attached"]
);
Route::post(
    "/user/aml_system_client_since",
    [AmlController::class, "aml_system_client_since"]
);
Route::post("/user/aml_report_pdf", [AmlController::class, "aml_report_pdf"]);
Route::post("/user/aml_report_csv", [AmlController::class, "aml_report_csv"]);
Route::post(
    "/user/aml_report_pdf_single",
    [AmlController::class, "aml_report_pdf_single"]
);
Route::post(
    "/user/aml_download_report_pdfs",
    [AmlController::class, "aml_download_report_pdfs"]
);
Route::post(
    "/user/aml_remove_dropzone_attachment",
    [AmlController::class, "aml_remove_dropzone_attachment"]
);
Route::get("/user/notify_tasks_aml", [AmlController::class, "notify_tasks_aml"]);
Route::get("/user/email_notify_aml", [AmlController::class, "email_notify_aml"]);
Route::get(
    "/user/aml_edit_email_unsent_files",
    [AmlController::class, "aml_edit_email_unsent_files"]
);
Route::post(
    "/user/aml_email_unsent_files",
    [AmlController::class, "aml_email_unsent_files"]
);
Route::get("/user/standard_file_name", [AmlController::class, "standard_file_name"]);
Route::get(
    "/user/generate_aml_text_file",
    [AmlController::class, "generate_aml_text_file"]
);
Route::post(
    "/user/aml_system_add_trade",
    [AmlController::class, "aml_system_add_trade"]
);
Route::get("/user/get_trade_details", [AmlController::class, "get_trade_details"]);
Route::post(
    "/user/get_aml_client_content",
    [AmlController::class, "get_aml_client_content"]
);

Route::post(
    "/user/import_aml_clients_details",
    [AmlController::class, "import_aml_clients_details"]
);
Route::get(
    "/user/import_aml_clients_details_one",
    [AmlController::class, "import_aml_clients_details_one"]
);
Route::post(
    "/user/get_aml_client_identity_files",
    [AmlController::class, "get_aml_client_identity_files"]
);
Route::get(
    "/user/standard_file_name_single",
    [AmlController::class, "standard_file_name_single"]
);
Route::post(
    "/user/set_identity_expiry_date",
    [AmlController::class, "set_identity_expiry_date"]
);
Route::post(
    "/user/set_attachment_identity_type",
    [AmlController::class, "set_attachment_identity_type"]
);
Route::post(
    "/user/email_request_updated_id_files",
    [AmlController::class, "email_request_updated_id_files"]
);
Route::post(
    "/user/email_request_id_files",
    [AmlController::class, "email_request_id_files"]
);
Route::post(
    "/user/update_aml_settings",
    [AmlController::class, "update_aml_settings"]
);

Route::post(
    "/user/edit_aml_header_image",
    [AmlController::class, 'edit_aml_header_image']
);