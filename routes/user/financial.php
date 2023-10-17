<?php
use App\Http\Controllers\user\FinancialController;

Route::get("/user/financials", [FinancialController::class, "financials"]);
Route::get(
    "/user/check_nominal_code",
    [FinancialController::class, "check_nominal_code"]
);
Route::post(
    "/user/add_nominal_code_financial",
    [FinancialController::class, "add_nominal_code_financial"]
);
Route::post(
    "/user/add_bank_financial",
    [FinancialController::class, "add_bank_financial"]
);
Route::post(
    "/user/edit_nominal_code_finance",
    [FinancialController::class, "edit_nominal_code_finance"]
);
Route::post(
    "/user/get_nominal_codes_for_bank",
    [FinancialController::class, "get_nominal_codes_for_bank"]
);
Route::post(
    "/user/financial_opening_balance_show",
    [FinancialController::class, "financial_opening_balance_show"]
);
Route::post(
    "/user/save_opening_balance_values",
    [FinancialController::class, "save_opening_balance_values"]
);
Route::post(
    "/user/save_opening_balance_date",
    [FinancialController::class, "save_opening_balance_date"]
);
Route::post(
    "/user/save_debit_credit_finance_client",
    [FinancialController::class, "save_debit_credit_finance_client"]
);
Route::post(
    "/user/load_journals_financials",
    [FinancialController::class, "load_journals_financials"]
);
Route::post(
    "/user/export_csv_client_opening",
    [FinancialController::class, "export_csv_client_opening"]
);
Route::post(
    "/user/commit_client_account_opening_balance",
    [FinancialController::class, "commit_client_account_opening_balance"]
);
Route::post(
    "/user/edit_bank_account_finance",
    [FinancialController::class, "edit_bank_account_finance"]
);
Route::post(
    "/user/update_bank_financial",
    [FinancialController::class, "update_bank_financial"]
);
Route::post(
    "/user/summary_clients_list",
    [FinancialController::class, "summary_clients_list"]
);
Route::post(
    "/user/summary_load_opening_balance",
    [FinancialController::class, "summary_load_opening_balance"]
);
Route::post(
    "/user/summary_load_receipts",
    [FinancialController::class, "summary_load_receipts"]
);
Route::post(
    "/user/summary_load_payments",
    [FinancialController::class, "summary_load_payments"]
);
Route::post(
    "/user/summary_calculations",
    [FinancialController::class, "summary_calculations"]
);
Route::post(
    "/user/summary_export_csv",
    [FinancialController::class, "summary_export_csv"]
);
Route::post(
    "/user/load_trial_balance_nominals",
    [FinancialController::class, "load_trial_balance_nominals"]
);
Route::post(
    "/user/load_trial_balance_journals_for_nominal",
    [FinancialController::class, "load_trial_balance_journals_for_nominal"]
);
Route::post(
    "/user/finance_get_bank_details",
    [FinancialController::class, "finance_get_bank_details"]
);
Route::post(
    "/user/finance_reconcile_load",
    [FinancialController::class, "finance_reconcile_load"]
);
Route::post(
    "/user/finance_bank_single_accept",
    [FinancialController::class, "finance_bank_single_accept"]
);
Route::post(
    "/user/balance_per_bank",
    [FinancialController::class, "balance_per_bank"]
);
Route::post(
    "/user/finance_bank_refresh",
    [FinancialController::class, "finance_bank_refresh"]
);
Route::post(
    "/user/finance_bank_all_accept",
    [FinancialController::class, "finance_bank_all_accept"]
);
Route::post(
    "/user/check_bank_nominal_code",
    [FinancialController::class, "check_bank_nominal_code"]
);
Route::post(
    "/user/create_journal_reconciliation",
    [FinancialController::class, "create_journal_reconciliation"]
);
Route::post(
    "/user/generate_reconcile_pdf",
    [FinancialController::class, "generate_reconcile_pdf"]
);
Route::post(
    "/user/generate_reconcile_csv",
    [FinancialController::class, "generate_reconcile_csv"]
);

Route::post(
    "/user/save_general_journals",
    [FinancialController::class, "save_general_journals"]
);
Route::post(
    "/user/finance_load_details_analysis",
    [FinancialController::class, "finance_load_details_analysis"]
);
Route::post(
    "/user/finance_analysis_report",
    [FinancialController::class, "finance_analysis_report"]
);
Route::post(
    "user/practice_load_review",
    [FinancialController::class, "practice_load_review"]
);
Route::post(
    "user/practice_load_client_review",
    [FinancialController::class, "practice_load_client_review"]
);
Route::post(
    "user/practice_load_staff_review",
    [FinancialController::class, "practice_load_staff_review"]
);
Route::post(
    "user/practice_review_export",
    [FinancialController::class, "practice_review_export"]
);
Route::post(
    "/user/accounting_period_save",
    [FinancialController::class, "accounting_period_save"]
);
Route::post(
    "/user/accounting_period_set_default",
    [FinancialController::class, "accounting_period_set_default"]
);
Route::post(
    "/user/accounting_period_lock_unlock",
    [FinancialController::class, "accounting_period_lock_unlock"]
);
Route::post(
    "/user/generate_reconcile_csv_after_reconciliation",
    [FinancialController::class, "generate_reconcile_csv_after_reconciliation"]
);
Route::post(
    "/user/extract_trial_balance_journals_for_nominal_csv",
    [FinancialController::class, "extract_trial_balance_journals_for_nominal_csv"]
);
Route::post(
    "/user/extract_trial_balance_nominals_pdf",
    [FinancialController::class, "extract_trial_balance_nominals_pdf"]
);
Route::post(
    "/user/extract_trial_balance_nominals_csv",
    [FinancialController::class, "extract_trial_balance_nominals_csv"]
);
Route::post(
    "/user/get_profit_loss_values",
    [FinancialController::class, "get_profit_loss_values"]
);
Route::post(
    "/user/view_journal_for_profit_loss",
    [FinancialController::class, "view_journal_for_profit_loss"]
);
Route::post(
    "/user/view_journal_for_profit_loss_single_month",
    [FinancialController::class, "view_journal_for_profit_loss_single_month"]
);
Route::post(
    "/user/extract_journal_for_profit_loss",
    [FinancialController::class, "extract_journal_for_profit_loss"]
);
Route::post(
    "/user/extract_journal_for_profit_loss_single_month",
    [FinancialController::class, "extract_journal_for_profit_loss_single_month"]
);
Route::post(
    "/user/extract_profit_loss_values",
    [FinancialController::class, "extract_profit_loss_values"]
);
Route::get(
    "/user/bank_account_manager",
    [FinancialController::class, "bank_account_manager"]
);