<?php
use App\Http\Controllers\user\YearendController;

Route::get(
    "/user/year_end_manager",
    [YearendController::class, "YearendController"]
);
Route::post(
    "/user/yearend_crypt_validdation",
    [YearendController::class, "yearend_crypt_validdation"]
);
Route::post(
    "/user/year_first_create",
    [YearendController::class, "year_first_create"]
);
Route::get("/user/yearend_setting", [YearendController::class, "yearend_setting"]);
Route::post(
    "/user/year_setting_create",
    [YearendController::class, "year_setting_create"]
);
Route::post("/user/active_checkbox", [YearendController::class, "active_checkbox"]);
Route::post(
    "/user/year_setting_edit",
    [YearendController::class, "year_setting_edit"]
);
Route::post(
    "/user/yearend_crypt_setting_add",
    [YearendController::class, "yearend_crypt_setting_add"]
);
Route::post(
    "/user/year_setting_update",
    [YearendController::class, "year_setting_update"]
);
Route::get(
    "/user/yeadend_clients/{id}",
    [YearendController::class, "yeadend_clients"]
);
Route::get(
    "/user/yearend_individualclient/{id}",
    [YearendController::class, "yearend_individualclient"]
);
Route::post(
    "/user/year_setting_copy_to_year/",
    [YearendController::class, "year_setting_copy_to_year"]
);
Route::post(
    "/user/dist_emailupdate",
    [YearendController::class, "dist_emailupdate"]
);
Route::post(
    "/user/yearend_upload_images_add",
    [YearendController::class, "yearend_upload_images_add"]
);
Route::post(
    "/user/yearend_clear_session_attachments",
    [YearendController::class, "yearend_clear_session_attachments"]
);
Route::post(
    "/user/remove_all_attachments",
    [YearendController::class, "remove_all_attachments"]
);
Route::post(
    "/user/yearend_upload_images_edit",
    [YearendController::class, "yearend_upload_images_edit"]
);
Route::post(
    "/user/remove_year_setting_attachment",
    [YearendController::class, "remove_year_setting_attachment"]
);
Route::post(
    "/user/yearend_individual_attachment",
    [YearendController::class, "yearend_individual_attachment"]
);
Route::post(
    "/user/yearend_attachment_individual",
    [YearendController::class, "yearend_attachment_individual"]
);
Route::post(
    "/user/yearend_delete_image",
    [YearendController::class, "yearend_delete_image"]
);
Route::post(
    "/user/yearend_delete_all_image",
    [YearendController::class, "yearend_delete_all_image"]
);
Route::post(
    "/user/yearend_delete_all_image_aml",
    [YearendController::class, "yearend_delete_all_image_aml"]
);
Route::post(
    "/user/remove_yearend_dropzone_attachment",
    [YearendController::class, "remove_yearend_dropzone_attachment"]
);
Route::post(
    "/user/remove_yearend_dropzone_attachment_aml",
    [YearendController::class, "remove_yearend_dropzone_attachment_aml"]
);
Route::post(
    "/user/distribution_future",
    [YearendController::class, "distribution_future"]
);
Route::post(
    "/user/distribution1_future",
    [YearendController::class, "distribution1_future"]
);
Route::post(
    "/user/distribution2_future",
    [YearendController::class, "distribution2_future"]
);
Route::post(
    "/user/distribution3_future",
    [YearendController::class, "distribution3_future"]
);
Route::post(
    "/user/setting_active_update",
    [YearendController::class, "setting_active_update"]
);
Route::get(
    "/user/check_already_attached",
    [YearendController::class, "check_already_attached"]
);
Route::post(
    "/user/insert_notes_yearend",
    [YearendController::class, "insert_notes_yearend"]
);
Route::post(
    "/user/yearend_delete_note",
    [YearendController::class, "yearend_delete_note"]
);
Route::post(
    "/user/yearend_delete_all_note",
    [YearendController::class, "yearend_delete_all_note"]
);
Route::get(
    "/user/yearend_create_new_year",
    [YearendController::class, "yearend_create_new_year"]
);
Route::get(
    "/user/review_get_clients",
    [YearendController::class, "review_get_clients"]
);
Route::post(
    "/user/review_clients_update",
    [YearendController::class, "review_clients_update"]
);
Route::post(
    "/user/download_email_format",
    [YearendController::class, "download_email_format"]
);
Route::get(
    "/user/edit_yearend_email_unsent_files",
    [YearendController::class, "edit_yearend_email_unsent_files"]
);
Route::post(
    "/user/yearend_email_unsent_files",
    [YearendController::class, "yearend_email_unsent_files"]
);
Route::post(
    "/user/make_client_disable",
    [YearendController::class, "make_client_disable"]
);
Route::post("/user/select_template", [YearendController::class, "select_template"]);
Route::post("/user/save_user_note", [YearendController::class, "save_user_note"]);
Route::post(
    "/user/set_client_year_end_date",
    [YearendController::class, "set_client_year_end_date"]
);
Route::post(
    "/user/update_na_status",
    [YearendController::class, "update_na_status"]
);
Route::post(
    "/user/yearend_liability_update",
    [YearendController::class, "yearendliabilityupdate"]
);
Route::get(
    "/user/yeadend_liability/{id?}",
    [YearendController::class, "yeadendliability"]
);
Route::post(
    "/user/yearend_liability_setting_result",
    [YearendController::class, "yearendliabilitysettingresult"]
);
Route::post(
    "/user/yearend_liability_payment",
    [YearendController::class, "yearendliabilitypayment"]
);
Route::post(
    "/user/yearend_liability_prelim",
    [YearendController::class, "yearendliabilityprelim"]
);
Route::post(
    "/user/yearend_liability_export",
    [YearendController::class, "yearendliabilityexport"]
);
Route::post(
    "/user/yearend_export_to_csv",
    [YearendController::class, "yearend_export_to_csv"]
);
Route::post(
    "/user/save_yearend_date_status",
    [YearendController::class, "save_yearend_date_status"]
);
Route::post(
    "/user/save_yearend_liability_date",
    [YearendController::class, "save_yearend_liability_date"]
);
Route::post(
    "/user/yearend_notes_update",
    [YearendController::class, "yearend_notes_update"]
);
Route::post(
    "/user/yearend_status_notes_update",
    [YearendController::class, "yearend_status_notes_update"]
);
Route::post(
    "/user/remove_client_from_year",
    [YearendController::class, "remove_client_from_year"]
);
Route::post(
    "/user/yearend_aml_risk_attachment",
    [YearendController::class, "yearend_aml_risk_attachment"]
);
Route::post(
    "/user/edit_yearend_header_image",
    [YearendController::class, 'edit_yearend_header_image']
);
Route::post(
    "/user/update_yearend_settings",
    [YearendController::class, "update_yearend_settings"]
);