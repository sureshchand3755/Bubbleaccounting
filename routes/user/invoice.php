<?php
use App\Http\Controllers\user\InvoiceController;

Route::get(
    "/user/invoice_management",
    [InvoiceController::class, "invoicemanagement"]
);
Route::get("/user/invoice_search", [InvoiceController::class, "invoice_search"]);
Route::post("/user/show_statement", [InvoiceController::class, "show_statement"]);
Route::get(
    "/user/invoicemanagement_paginate",
    [InvoiceController::class, "invoicemanagement_paginate"]
);
Route::post(
    "/user/report_client_invoice",
    [InvoiceController::class, "report_client_invoice"]
);
Route::post(
    "/user/invoice_report_csv",
    [InvoiceController::class, "invoice_report_csv"]
);
Route::post(
    "/user/invoice_report_pdf",
    [InvoiceController::class, "invoice_report_pdf"]
);
Route::post(
    "/user/invoice_download_report_pdfs",
    [InvoiceController::class, "invoice_download_report_pdfs"]
);
Route::post(
    "/user/import_new_invoice",
    [InvoiceController::class, "import_new_invoice"]
);
Route::get(
    "/user/import_new_invoice_one",
    [InvoiceController::class, "import_new_invoice_one"]
);
Route::post(
    "/user/report_client_invoice_date_filter",
    [InvoiceController::class, "report_client_invoice_date_filter"]
);
Route::get(
    "/user/invoicemanagement_paginate",
    [InvoiceController::class, "invoicemanagement_paginate"]
);
Route::post(
    "/user/invoices_print_view",
    [InvoiceController::class, "invoicesprintview"]
);
Route::post(
    "/user/invoice_saveas_pdf",
    [InvoiceController::class, "invoice_saveas_pdf"]
);
Route::post(
    "/user/invoice_print_pdf",
    [InvoiceController::class, "invoice_print_pdf"]
);
Route::post(
    "/user/get_loaded_client_inv_year",
    [InvoiceController::class, "get_loaded_client_inv_year"]
);
Route::post(
    "/user/load_all_client_invoice",
    [InvoiceController::class, "load_all_client_invoice"]
);
Route::post(
    "/user/insert_update_invoice_nominals",
    [InvoiceController::class, "insert_update_invoice_nominals"]
);
Route::post(
    "/user/check_financial_opening_bal_date",
    [InvoiceController::class, "check_financial_opening_bal_date"]
);
Route::post(
    "/user/load_invoice_for_nominal",
    [InvoiceController::class, "load_invoice_for_nominal"]
);
Route::post(
    "/user/export_invoice_for_nominal",
    [InvoiceController::class, "export_invoice_for_nominal"]
);