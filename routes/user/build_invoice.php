<?php
use App\Http\Controllers\user\BuildinvoiceController;

Route::get("/user/build_invoice", [BuildinvoiceController::class, "build_invoice"]);
Route::get(
    "/user/build_invoice_client_select",
    [BuildinvoiceController::class, "build_invoice_client_select"]
);
Route::post(
    "/user/build_issued_invoice_lines",
    [BuildinvoiceController::class, "build_issued_invoice_lines"]
);
Route::post(
    "/user/show_invoice_lines_for_invoice",
    [BuildinvoiceController::class, "show_invoice_lines_for_invoice"]
);
Route::post(
    "/user/update_invoice_lines_for_invoice",
    [BuildinvoiceController::class, "update_invoice_lines_for_invoice"]
);
Route::post(
    "/user/update_build_invoice_setings",
    [BuildinvoiceController::class, "update_build_invoice_setings"]
);
Route::post(
    "/user/build_invoice_saveas_pdf",
    [BuildinvoiceController::class, "build_invoice_saveas_pdf"]
);
Route::post(
    "/user/email_invoice_submit",
    [BuildinvoiceController::class, "email_invoice_submit"]
);
Route::post(
    "/user/edit_invoice_header_image",
    [BuildinvoiceController::class, 'edit_invoice_header_image']
);
Route::post(
    "/user/update_invoice_email_settings",
    [BuildinvoiceController::class, 'update_invoice_email_settings']
);
