<?php
use App\Http\Controllers\user\SupplierinvoiceController;
Route::get(
    "/user/supplier_invoice_management",
    [SupplierinvoiceController::class, "supplier_invoice_management"]
);
Route::get(
    "/user/purchase_invoice_to_process",
    [SupplierinvoiceController::class, "purchase_invoice_to_process"]
);
Route::post(
    "/user/store_supplier_vat_rate",
    [SupplierinvoiceController::class, "store_supplier_vat_rate"]
);
Route::post(
    "/user/change_supplier_vat_status",
    [SupplierinvoiceController::class, "change_supplier_vat_status"]
);
Route::post(
    "/user/get_supplier_info_details",
    [SupplierinvoiceController::class, "get_supplier_info_details"]
);
Route::post(
    "/user/store_purchase_invoice",
    [SupplierinvoiceController::class, "store_purchase_invoice"]
);
Route::post(
    "/user/supplier_upload_global_files",
    [SupplierinvoiceController::class, "supplier_upload_global_files"]
);
Route::post(
    "/user/purchase_invoice_files",
    [SupplierinvoiceController::class, "purchase_invoice_files"]
);
Route::post(
    "/user/edit_purchase_invoice_supplier",
    [SupplierinvoiceController::class, "edit_purchase_invoice_supplier"]
);
Route::post(
    "/user/load_all_global_invoice",
    [SupplierinvoiceController::class, "load_all_global_invoice"]
);
Route::post(
    "/user/export_all_global_invoice",
    [SupplierinvoiceController::class, "export_all_global_invoice"]
);
Route::post(
    "/user/export_supplier_transaction_list",
    [SupplierinvoiceController::class, "export_supplier_transaction_list"]
);
Route::post(
    "/user/delete_supplier_global_attachment",
    [SupplierinvoiceController::class, "delete_supplier_global_attachment"]
);
Route::post(
    "/user/view_purchase_invoice_supplier",
    [SupplierinvoiceController::class, "view_purchase_invoice_supplier"]
);
Route::post(
    "/user/check_supplier_journal_repost",
    [SupplierinvoiceController::class, "check_supplier_journal_repost"]
);
Route::post(
    "/user/store_supplier_invoice",
    [SupplierinvoiceController::class, "store_supplier_invoice"]
);
Route::post(
    "/user/change_supplier_files_ignore_file",
    [SupplierinvoiceController::class, "change_supplier_files_ignore_file"]
);
Route::post(
    "/user/change_supplier_files_inv_date",
    [SupplierinvoiceController::class, "change_supplier_files_inv_date"]
);
Route::post(
    "/user/change_supplier_files_supplier_id",
    [SupplierinvoiceController::class, "change_supplier_files_supplier_id"]
);
Route::post(
    "/user/delete_purchase_files",
    [SupplierinvoiceController::class, "delete_purchase_files"]
);
Route::post(
    "/user/get_purchase_invoice_files_details",
    [SupplierinvoiceController::class, "get_purchase_invoice_files_details"]
);
Route::post(
    "/user/supplier_invoice_report_download",
    [SupplierinvoiceController::class, "supplier_invoice_report_download"]
);
Route::post(
    "/user/supplier_invoice_report_preview",
    [SupplierinvoiceController::class, "supplier_invoice_report_preview"]
);
Route::get(
    "/user/invoice_setacperiod",
    [SupplierinvoiceController::class, "invoice_setacperiod"]
);