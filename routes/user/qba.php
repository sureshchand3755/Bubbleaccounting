<?php
use App\Http\Controllers\user\QbaController;

Route::get("/user/qba", [QbaController::class, "qba"]);
Route::post("/user/qba_upload_file", [QbaController::class, "qba_upload_file"]);
Route::post(
    "/user/update_qba_settings",
    [QbaController::class, "update_qba_settings"]
);
Route::post(
    "/user/qba_data_alloations",
    [QbaController::class, "qba_data_alloations"]
);
Route::post(
    "/user/save_qba_data_links",
    [QbaController::class, "save_qba_data_links"]
);
Route::post(
    "/user/empty_qba_allocations",
    [QbaController::class, "empty_qba_allocations"]
);
Route::post(
    "/user/qba_data_validations",
    [QbaController::class, "qba_data_validations"]
);