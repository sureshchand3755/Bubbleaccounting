<?php
use App\Http\Controllers\user\SupplierController;

Route::get(
    "/user/supplier_management",
    [SupplierController::class, "supplier_management"]
);
Route::post("/user/store_supplier", [SupplierController::class, "store_supplier"]);
Route::post("/user/edit_supplier", [SupplierController::class, "edit_supplier"]);
Route::post(
    "/user/get_supplier_transaction_list",
    [SupplierController::class, "get_supplier_transaction_list"]
);
Route::post(
    "/user/refresh_supplier_counts",
    [SupplierController::class, "refresh_supplier_counts"]
);

Route::post(
    "/user/export_suppliers_list",
    [SupplierController::class, "export_suppliers_list"]
);
Route::post(
    "/user/load_single_invoice_payment",
    [SupplierController::class, "load_single_invoice_payment"]
);
Route::post(
    "/user/refresh_all_supplier_counts",
    [SupplierController::class, "refresh_all_supplier_counts"]
);
Route::get(
    "/user/supplier_opening_balance",
    [SupplierController::class, "supplier_opening_balance"]
);
Route::post(
    "/user/supplier_journal_create",
    [SupplierController::class, "supplier_journal_create"]
);
Route::post(
    "/user/run_creditors_listing",
    [SupplierController::class, "run_creditors_listing"]
);
Route::post(
    "/user/export_creditors_listing",
    [SupplierController::class, "export_creditors_listing"]
);