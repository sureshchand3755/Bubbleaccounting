<?php
use App\Http\Controllers\user\RctControllerNew;

Route::get("/user/rct_system", [RctControllerNew::class, "rctsystem"]);
Route::get(
    "/user/rct_client_manager/{id?}",
    [RctControllerNew::class, "rctclientmanager"]
);
Route::post("/user/rct_add_tax/", [RctControllerNew::class, "rctaddtax"]);
Route::get(
    "/user/rct_tax_number_check",
    [RctControllerNew::class, "rcttaxnumbercheck"]
);
Route::post(
    "/user/rct_add_submission/",
    [RctControllerNew::class, "rctaddsubmission"]
);
Route::get(
    "/user/rct_submission_check",
    [RctControllerNew::class, "rctsubmissioncheck"]
);
Route::post(
    "/user/rct_delete_submission/",
    [RctControllerNew::class, "rctdeletesubmission"]
);
Route::post(
    "/user/rct_edit_submission/",
    [RctControllerNew::class, "rcteditsubmission"]
);
Route::post(
    "/user/rct_edit_submission_update/",
    [RctControllerNew::class, "rcteditsubmissionupdate"]
);
Route::post("/user/rct_saveaspdf/", [RctControllerNew::class, "rctsaveaspdf"]);
Route::post(
    "/user/save_as_rct_pdf_download/",
    [RctControllerNew::class, "save_as_rct_pdf_download"]
);
Route::get(
    "/user/rct_liability_assessment/{id?}",
    [RctControllerNew::class, "rctliabilityassessment"]
);
Route::post(
    "/user/rct_submission_view/",
    [RctControllerNew::class, "rctsubmissionview"]
);
Route::post(
    "/user/rct_liability_filter/",
    [RctControllerNew::class, "rctliabilityfilter"]
);
Route::post(
    "/user/set_rct_active_month/",
    [RctControllerNew::class, "set_rct_active_month"]
);
Route::post(
    "/user/set_rct_active_month_individual/",
    [RctControllerNew::class, "set_rct_active_month_individual"]
);
Route::post(
    "/user/rct_load_all_liabilities/",
    [RctControllerNew::class, "rct_load_all_liabilities"]
);
Route::post(
    "/user/rct_extract_csv_liabilities/",
    [RctControllerNew::class, "rct_extract_csv_liabilities"]
);
Route::post(
    "/user/rct_rebuild_all_liabilities/",
    [RctControllerNew::class, "rct_rebuild_all_liabilities"]
);
Route::post(
    "/user/rct_rebuild_single_liabilities/",
    [RctControllerNew::class, "rct_rebuild_single_liabilities"]
);
Route::post(
    "/user/rctsaveaspdf_multiple/",
    [RctControllerNew::class, "rctsaveaspdf_multiple"]
);
Route::post(
    "/user/rctsaveascsv_multiple/",
    [RctControllerNew::class, "rctsaveascsv_multiple"]
);
Route::post(
    "/user/rctsendemail_multiple/",
    [RctControllerNew::class, "rctsendemail_multiple"]
);
Route::post(
    "/user/rct_send_bulk_email/",
    [RctControllerNew::class, "rct_send_bulk_email"]
);
Route::post(
    "/user/get_ckeditor_content_single/",
    [RctControllerNew::class, "get_ckeditor_content_single"]
);
Route::post(
    "/user/upload_rct_html_form/",
    [RctControllerNew::class, "upload_rct_html_form"]
);
Route::post(
    "/user/upload_html_form/",
    [RctControllerNew::class, "upload_html_form"]
);
Route::get(
    "/user/delete_tax_number/",
    [RctControllerNew::class, "delete_tax_number"]
);
Route::post(
    "/user/send_batch_email_single/",
    [RctControllerNew::class, "send_batch_email_single"]
);
Route::post(
    "/user/edit_rctsalution/{id?}",
    [RctControllerNew::class, "editsalution"]
);
Route::post(
    "/user/update_rctsalution/",
    [RctControllerNew::class, "updatesalution"]
);
Route::post(
    "/user/update_rctletterpad/",
    [RctControllerNew::class, "updateletterpad"]
);
Route::post(
    "/user/edit_rctletterpad/{id?}",
    [RctControllerNew::class, "editletterpad"]
);
Route::get("/user/rct_summary", [RctControllerNew::class, "rct_summary"]);
Route::post(
    "/user/rct_summary_filter",
    [RctControllerNew::class, "rct_summary_filter"]
);
Route::post(
    "/user/rct_summary_result",
    [RctControllerNew::class, "rct_summary_result"]
);
Route::post(
    "/user/extract_rct_summary_data",
    [RctControllerNew::class, "extract_rct_summary_data"]
);
Route::get(
    "/user/rct_liability_disclosure",
    [RctControllerNew::class, "rct_liability_disclosure"]
);
Route::post(
    "/user/save_email_monthly_disclosure",
    [RctControllerNew::class, "save_email_monthly_disclosure"]
);
Route::post(
    "/user/save_pay_liability",
    [RctControllerNew::class, "save_pay_liability"]
);
Route::post(
    "/user/save_pay_from_rct",
    [RctControllerNew::class, "save_pay_from_rct"]
);
Route::post(
    "/user/extract_rct_active_month_data",
    [RctControllerNew::class, "extract_rct_active_month_data"]
);
Route::post(
    "/user/edit_rct_header_image",
    [RctControllerNew::class, 'edit_rct_header_image']
);
Route::post(
    "/user/update_rct_settings",
    [RctControllerNew::class, "update_rct_settings"]
);