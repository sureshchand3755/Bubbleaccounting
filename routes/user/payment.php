<?php
use App\Http\Controllers\user\PaymentController;

Route::get(
    "/user/payment_management",
    [PaymentController::class, "payment_management"]
);
Route::post(
    "/user/payment_move_to_allowable_list",
    [PaymentController::class, "payment_move_to_allowable_list"]
);
Route::post(
    "/user/payment_save_details",
    [PaymentController::class, "payment_save_details"]
);
Route::post(
    "/user/payment_update_details",
    [PaymentController::class, "payment_update_details"]
);
Route::get(
    "/user/payment_common_client_search",
    [PaymentController::class, "payment_commonclient_search"]
);
Route::post("/user/load_payment", [PaymentController::class, "load_payment"]);
Route::post(
    "/user/export_load_payment",
    [PaymentController::class, "export_load_payment"]
);
Route::post(
    "/user/payment_change_to_unhold",
    [PaymentController::class, "payment_change_to_unhold"]
);
Route::post(
    "/user/import_new_payment",
    [PaymentController::class, "import_new_payment"]
);
Route::get(
    "/user/import_new_payment_one",
    [PaymentController::class, "import_new_payment_one"]
);
Route::post(
    "/user/add_payment_export_csv",
    [PaymentController::class, "add_payment_export_csv"]
);
Route::post(
    "/user/check_import_csv_payment",
    [PaymentController::class, "check_import_csv_payment"]
);
Route::get(
    "/user/check_import_csv_one_payment/{id?}",
    [PaymentController::class, "check_import_csv_one_payment"]
);
Route::post(
    "/user/get_payment_bank_transfer_details",
    [PaymentController::class, "get_payment_bank_transfer_details"]
);
Route::post(
    "/user/prepare_payment_receipt_entry",
    [PaymentController::class, "prepare_payment_receipt_entry"]
);
Route::post(
    "/user/show_batch_payment_informations",
    [PaymentController::class, "show_batch_payment_informations"]
);
Route::post(
    "/user/delete_outstanding_payments_from_batch",
    [PaymentController::class, "delete_outstanding_payments_from_batch"]
);
Route::post(
    "/user/load_all_payment",
    [PaymentController::class, "load_all_payment"]
);
Route::post(
    "/user/show_payments_batch_list",
    [PaymentController::class, "show_payments_batch_list"]
);
Route::post(
    "/user/edit_payment_details",
    [PaymentController::class, "edit_payment_details"]
);
Route::post(
    "/user/update_payment_details_editted",
    [PaymentController::class, "update_payment_details_editted"]
);