<?php
use App\Http\Controllers\user\OpeningbalanceController;

Route::get(
    "/user/opening_balance_manager",
    [OpeningbalanceController::class, "opening_balance_manager"]
);
Route::get(
    "/user/opening_balance_invoices_issued",
    [OpeningbalanceController::class, "opening_balance_invoices_issued"]
);
Route::post(
    "/user/update_outstanding_invoice",
    [OpeningbalanceController::class, "update_outstanding_invoice"]
);
Route::post(
    "/user/invoice_outstanding_upload_csv",
    [OpeningbalanceController::class, "invoice_outstanding_upload_csv"]
);
Route::post(
    "/user/check_invoice_issued_csv_file",
    [OpeningbalanceController::class, "check_invoice_issued_csv_file"]
);
Route::post(
    "/user/upload_invoice_issued_csv_file",
    [OpeningbalanceController::class, "upload_invoice_issued_csv_file"]
);
Route::get(
    "/user/client_opening_balance_manager",
    [OpeningbalanceController::class, "client_opening_balance_manager"]
);
Route::get(
    "/user/import_opening_balance_manager",
    [OpeningbalanceController::class, "import_opening_balance_manager"]
);
Route::get(
    "/user/lock_client_opening_balance",
    [OpeningbalanceController::class, "lock_client_opening_balance"]
);
Route::post(
    "/user/change_opening_balance",
    [OpeningbalanceController::class, "change_opening_balance"]
);
Route::post(
    "/user/change_opening_balance_date",
    [OpeningbalanceController::class, "change_opening_balance_date"]
);
Route::post(
    "/user/auto_allocate_opening_balance",
    [OpeningbalanceController::class, "auto_allocate_opening_balance"]
);
Route::post(
    "/user/import_opening_balance",
    [OpeningbalanceController::class, "import_opening_balance"]
);
Route::post(
    "/user/import_opening_balance_to_clients",
    [OpeningbalanceController::class, "import_opening_balance_to_clients"]
);
Route::post(
    "/user/clear_import_opening_balance",
    [OpeningbalanceController::class, "clear_import_opening_balance"]
);
Route::post(
    "/user/get_client_counts_opening_balance",
    [OpeningbalanceController::class, "get_client_counts_opening_balance"]
);
Route::post(
    "/user/set_global_opening_bal_date",
    [OpeningbalanceController::class, "set_global_opening_bal_date"]
);
Route::post(
    "/user/refresh_os_invoice",
    [OpeningbalanceController::class, "refresh_os_invoice"]
);
Route::post(
    "/user/set_balance_for_opening_balance",
    [OpeningbalanceController::class, "set_balance_for_opening_balance"]
);
Route::post(
    "/user/opening_balance_review",
    [OpeningbalanceController::class, "opening_balance_review"]
);
Route::post(
    "/user/opening_balance_export_all",
    [OpeningbalanceController::class, "opening_balance_export_all"]
);
Route::post(
    "/user/remove_balance_for_opening_balance",
    [OpeningbalanceController::class, "remove_balance_for_opening_balance"]
);
Route::post(
    "/user/export_opening_balance_invoice_issued_csv",
    [OpeningbalanceController::class, "export_opening_balance_invoice_issued_csv"]
);
Route::post(
    "/user/create_opening_balance_journal",
    [OpeningbalanceController::class, "create_opening_balance_journal"]
);
Route::post(
    "/user/create_journals_for_opening_balance",
    [OpeningbalanceController::class, "create_journals_for_opening_balance"]
);