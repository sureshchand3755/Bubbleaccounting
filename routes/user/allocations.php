<?php
use App\Http\Controllers\user\AllocationController;

Route::get("/user/allocation_system", [AllocationController::class, "allocation_system"]);
Route::post(
    "/user/load_allocation_clients",
    [AllocationController::class, "load_allocation_clients"]
);
Route::post(
    "/user/export_allocation_clients",
    [AllocationController::class, "export_allocation_clients"]
);