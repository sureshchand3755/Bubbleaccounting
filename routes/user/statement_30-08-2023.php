<?php
use App\Http\Controllers\user\StatementController;

Route::get("/user/statement_list", [StatementController::class, "statement_list"]);
Route::get(
    "/user/full_view_statement",
    [StatementController::class, "full_view_statement"]
);
Route::get(
    "/user/client_specific_statement",
    [StatementController::class, "client_specific_statement"]
);
Route::post(
    "/user/load_statement_clients",
    [StatementController::class, "load_statement_clients"]
);
Route::post(
    "/user/load_statement_client_single",
    [StatementController::class, "load_statement_client_single"]
);
Route::post(
    "/user/export_statement_clients",
    [StatementController::class, "export_statement_clients"]
);
Route::post(
    "/user/get_client_opening_balance",
    [StatementController::class, "get_client_opening_balance"]
);
Route::post(
    "/user/get_client_statement_values",
    [StatementController::class, "get_client_statement_values"]
);
Route::post(
    "/user/get_client_statement_values_single",
    [StatementController::class, "get_client_statement_values_single"]
);
Route::post(
    "/user/build_statement_for_single_client",
    [StatementController::class, "build_statement_for_single_client"]
);
Route::post(
    "/user/get_invoice_list_statement",
    [StatementController::class, "get_invoice_list_statement"]
);
Route::post(
    "/user/get_receipt_list_statement",
    [StatementController::class, "get_receipt_list_statement"]
);
Route::post(
    "/user/save_statement_settings",
    [StatementController::class, "save_statement_settings"]
);
Route::post("/user/delete_bg", [StatementController::class, "delete_bg"]);
Route::post(
    "/user/build_statement/",
    [StatementController::class, "build_statement"]
);
Route::post(
    "/user/buildall_statement/",
    [StatementController::class, "buildall_statement"]
);
Route::post(
    "/user/deleteall_statement/",
    [StatementController::class, "deleteall_statement"]
);
Route::post(
    "/user/load_statement_clients_current_view",
    [StatementController::class, "load_statement_clients_current_view"]
);
Route::post(
    "/user/export_statement_clients_current_view",
    [StatementController::class, "export_statement_clients_current_view"]
);
Route::get(
    "/user/monthly_statement",
    [StatementController::class, "monthly_statement"]
);
Route::post(
    "/user/load_statement_clients_monthly_view",
    [StatementController::class, "load_statement_clients_monthly_view"]
);
Route::post(
    "/user/build_statement_monthly_view",
    [StatementController::class, "build_statement_monthly_view"]
);
Route::post(
    "/user/build_all_statement_monthly_view",
    [StatementController::class, "build_all_statement_monthly_view"]
);
Route::post(
    "/user/deleteall_statement_monthly",
    [StatementController::class, "deleteall_statement_monthly"]
);
Route::post(
    "/user/export_statement_clients_monthly_view",
    [StatementController::class, "export_statement_clients_monthly_view"]
);
Route::post(
    "/user/save_statement_from_month_value",
    [StatementController::class, "save_statement_from_month_value"]
);
Route::post(
    "/user/save_statement_to_month_value",
    [StatementController::class, "save_statement_to_month_value"]
);
Route::post(
    "/user/clear_prev_loaded_data",
    [StatementController::class, "clear_prev_loaded_data"]
);
Route::post(
    "/user/send_notify_bank_account_emails",
    [StatementController::class, "send_notify_bank_account_emails"]
);