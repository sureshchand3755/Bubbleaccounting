<?php
use App\Http\Controllers\user\CroardController;

Route::get("/user/manage_croard", [CroardController::class, "manage_croard"]);
Route::get("/user/croard_monthly", [CroardController::class, "croard_monthly"]);
Route::post(
    "/user/search_croard_month_year",
    [CroardController::class, "search_croard_month_year"]
);
Route::post(
    "/user/get_company_details_cro",
    [CroardController::class, "get_company_details_cro"]
);
Route::get("/user/refresh_cro_ard", [CroardController::class, "refresh_cro_ard"]);
Route::get(
    "/user/refresh_blue_cro_ard",
    [CroardController::class, "refresh_blue_cro_ard"]
);
Route::post("/user/update_cro_notes", [CroardController::class, "update_cro_notes"]);
Route::post(
    "/user/update_rbo_submission",
    [CroardController::class, "update_rbo_submission"]
);
Route::post(
    "/user/croard_upload_images",
    [CroardController::class, "croard_upload_images"]
);
Route::post(
    "/user/get_company_details_next_crd",
    [CroardController::class, "get_company_details_next_crd"]
);
Route::get(
    "/user/edit_email_unsent_files_croard",
    [CroardController::class, "edit_email_unsent_files_croard"]
);
Route::post(
    "/user/email_unsent_files_croard",
    [CroardController::class, "email_unsent_files_croard"]
);
Route::post(
    "/user/email_company_files_croard",
    [CroardController::class, "email_company_files_croard"]
);
Route::post(
    "/user/change_yellow_status_croard",
    [CroardController::class, "change_yellow_status_croard"]
);
Route::post(
    "/user/save_croard_signature_date",
    [CroardController::class, "save_croard_signature_date"]
);
Route::post(
    "/user/croard_get_yellow_status_clients",
    [CroardController::class, "croard_get_yellow_status_clients"]
);
Route::post("/user/check_cro_in_api", [CroardController::class, "check_cro_in_api"]);
Route::post(
    "/user/save_croard_settings",
    [CroardController::class, "save_croard_settings"]
);
Route::post("/user/rbo_review_list", [CroardController::class, "rbo_review_list"]);
Route::post("/user/report_csv_rbo", [CroardController::class, "report_csv_rbo"]);
Route::post(
    "/user/remove_croard_refresh",
    [CroardController::class, "remove_croard_refresh"]
);
Route::post(
    "/user/remove_blue_croard_refresh",
    [CroardController::class, "remove_blue_croard_refresh"]
);
Route::get(
    "/user/get_client_from_cronumber",
    [CroardController::class, "get_client_from_cronumber"]
);
Route::get("/user/pdf_company_info", [CroardController::class, "pdf_company_info"]);
Route::get("/user/pdf_company_info", [CroardController::class, "pdf_company_info"]);
Route::post(
    "/user/edit_croard_header_image",
    [CroardController::class, 'edit_croard_header_image']
);
Route::post(
    "/user/global_core_call_entry",
    [CroardController::class, 'global_core_call_entry']
);
Route::post(
    "/user/update_gcc_finishtime",
    [CroardController::class, 'update_gcc_finishtime']
);
Route::post(
    "/user/show_global_corecall_details",
    [CroardController::class, 'show_global_corecall_details']
);



