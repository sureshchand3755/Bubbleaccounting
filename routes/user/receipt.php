<?php
use App\Http\Controllers\user\ReceiptController;

Route::get(
    "/user/receipt_management",
    [ReceiptController::class, "receipt_management"]
);
Route::get("/user/receipt_settings", [ReceiptController::class, "receipt_settings"]);
Route::post(
    "/user/move_to_allowable_list",
    [ReceiptController::class, "move_to_allowable_list"]
);
Route::post(
    "/user/get_nominal_code_description",
    [ReceiptController::class, "get_nominal_code_description"]
);
Route::get(
    "/user/receipt_common_client_search",
    [ReceiptController::class, "receipt_commonclient_search"]
);
Route::post(
    "/user/save_receipt_details",
    [ReceiptController::class, "save_receipt_details"]
);
Route::post(
    "/user/update_receipt_details",
    [ReceiptController::class, "update_receipt_details"]
);
Route::post(
    "/user/import_new_receipts",
    [ReceiptController::class, "import_new_receipts"]
);
Route::get(
    "/user/import_new_receipts_one",
    [ReceiptController::class, "import_new_receipts_one"]
);
Route::post(
    "/user/change_receipt_status",
    [ReceiptController::class, "change_receipt_status"]
);
Route::post("/user/load_receipt", [ReceiptController::class, "load_receipt"]);
Route::post(
    "/user/change_to_unhold",
    [ReceiptController::class, "change_to_unhold"]
);
Route::post(
    "/user/add_receipt_export_csv",
    [ReceiptController::class, "add_receipt_export_csv"]
);
Route::post(
    "/user/export_load_receipt",
    [ReceiptController::class, "export_load_receipt"]
);
Route::post(
    "/user/check_import_csv",
    [ReceiptController::class, "check_import_csv"]
);
Route::get(
    "/user/check_import_csv_one/{id?}",
    [ReceiptController::class, "check_import_csv_one"]
);
Route::post(
    "/user/payment_receipdt_delete_outstading",
    [ReceiptController::class, "payment_receipdt_delete_outstading"]
);
Route::post(
    "/user/outstanding_payment_receipt_download",
    [ReceiptController::class, "outstanding_payment_receipt_download"]
);
Route::post(
    "/user/outstanding_payment_receipt_delete",
    [ReceiptController::class, "outstanding_payment_receipt_delete"]
);
Route::post(
    "/user/get_receipt_bank_transfer_details",
    [ReceiptController::class, "get_receipt_bank_transfer_details"]
);
Route::post(
    "/user/prepare_receipt_payment_entry",
    [ReceiptController::class, "prepare_receipt_payment_entry"]
);
Route::post(
    "/user/show_batch_receipt_informations",
    [ReceiptController::class, "show_batch_receipt_informations"]
);
Route::post(
    "/user/delete_outstanding_receipts_from_batch",
    [ReceiptController::class, "delete_outstanding_receipts_from_batch"]
);
Route::post(
    "/user/load_all_receipt",
    [ReceiptController::class, "load_all_receipt"]
);
Route::post(
    "/user/show_receipts_batch_list",
    [ReceiptController::class, "show_receipts_batch_list"]
);
Route::post(
    "/user/edit_receipt_details",
    [ReceiptController::class, "edit_receipt_details"]
);
Route::post(
    "/user/update_receipt_details_editted",
    [ReceiptController::class, "update_receipt_details_editted"]
);