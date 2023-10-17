<?php
use App\Http\Controllers\user\RequestController;

Route::get(
    "/user/admin_deactive_request/{id?}",
    [RequestController::class, "deactiverequest"]
);
Route::get(
    "/user/admin_active_request/{id?}",
    [RequestController::class, "activerequest"]
);
Route::get(
    "/user/admin_delete_request/{id?}",
    [RequestController::class, "deleterequest"]
);
Route::post(
    "/user/admin_request_signature",
    [RequestController::class, "requestsignature"]
);
Route::post("/user/admin_request_add", [RequestController::class, "requestadd"]);
Route::get(
    "/user/admin_request_edit_category",
    [RequestController::class, "request_edit_category"]
);
Route::post(
    "/user/admin_request_edit_form",
    [RequestController::class, "request_edit_form"]
);