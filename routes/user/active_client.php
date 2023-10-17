<?php

use App\Http\Controllers\user\ActiveClientController;

Route::post(
    "/user/add_to_active_client_list",
    [ActiveClientController::class,'add_to_active_client_list']);
Route::post(
    "/user/remove_active_client_list",
    [ActiveClientController::class,'remove_active_client_list']);
Route::post(
    "/user/get_active_client_list",
    [ActiveClientController::class,'get_active_client_list']);
Route::post(
    "/user/remove_all_active_client_list",
    [ActiveClientController::class,'remove_all_active_client_list']);
Route::post(
    "/user/export_active_client_list",
    [ActiveClientController::class, 'export_active_client_list']
);