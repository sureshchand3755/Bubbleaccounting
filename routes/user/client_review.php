<?php
use App\Http\Controllers\user\ClientreviewController;

Route::get(
    "/user/client_account_review",
    [ClientreviewController::class, "client_account_review"]
);
Route::get(
    "/user/client_review_client_common_search",
    [ClientreviewController::class, "client_review_commonclient_search"]
);
Route::get(
    "/user/client_review_client_select",
    [ClientreviewController::class, "client_review_client_select"]
);
Route::post(
    "/user/update_cro_ard_date",
    [ClientreviewController::class, "update_cro_ard_date"]
);
Route::post(
    "/user/client_review_load_all_client_invoice",
    [ClientreviewController::class, "client_review_load_all_client_invoice"]
);
Route::post(
    "/user/client_review_load_all_client_receipt",
    [ClientreviewController::class, "client_review_load_all_client_receipt"]
);
Route::post(
    "/user/invoice_download_selected_pdfs",
    [ClientreviewController::class, "invoice_download_selected_pdfs"]
);
Route::post(
    "/user/invoice_email_selected_pdfs",
    [ClientreviewController::class, "invoice_email_selected_pdfs"]
);
Route::post(
    "/user/client_review_email_selected_pdf",
    [ClientreviewController::class, "client_review_email_selected_pdf"]
);
Route::post(
    "/user/get_client_account_review_listing",
    [ClientreviewController::class, "get_client_account_review_listing"]
);
Route::post(
    "/user/export_client_account_review_listing",
    [ClientreviewController::class, "export_client_account_review_listing"]
);
Route::post(
    "/user/get_transaction_review_listing",
    [ClientreviewController::class, "get_transaction_review_listing"]
);
Route::post(
    "/user/export_transaction_review_listing",
    [ClientreviewController::class, "export_transaction_review_listing"]
);
Route::get(
    "/user/client_account_review_summary",
    [ClientreviewController::class, "client_account_review_summary"]
);
Route::post(
    "/user/load_single_client_receipt_payment",
    [ClientreviewController::class, "load_single_client_receipt_payment"]
);
Route::post(
    "/user/invoice_export_selected_csvs",
    [ClientreviewController::class, "invoice_export_selected_csvs"]
);
Route::post(
    "/user/edit_car_header_image",
    [ClientreviewController::class, "edit_car_header_image"]
);
Route::post(
    "/user/save_car_settings",
    [ClientreviewController::class, "save_car_settings"]
);