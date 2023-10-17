<?php
use App\Http\Controllers\user\TwobillController;

Route::get("/user/two_bill_manager", [TwobillController::class, "two_bill_manager"]);
Route::post(
    "/user/get_tasks_invoices",
    [TwobillController::class, "get_tasks_invoices"]
);
Route::post(
    "/user/update_invoice_for_task",
    [TwobillController::class, "update_invoice_for_task"]
);
Route::post(
    "/user/remove_2bill_status",
    [TwobillController::class, "remove_2bill_status"]
);
Route::post(
    "/user/change_billing_status",
    [TwobillController::class, "change_billing_status"]
);
Route::post(
    "/user/twobill_manager_authenticate",
    [TwobillController::class, "twobill_manager_authenticate"]
);
