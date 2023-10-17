<?php
use App\Http\Controllers\payroll\PayrollController;

Route::get("/payroll/dashboard", [PayrollController::class, "dashboard"]);
Route::get("/payroll/logout", [PayrollController::class, "logout"]);
Route::post(
    "/payroll/load_weekly_payroll_tasks",
    [PayrollController::class, "load_weekly_payroll_tasks"]
);
Route::post(
    "/payroll/load_monthly_payroll_tasks",
    [PayrollController::class, "load_monthly_payroll_tasks"]
);
Route::post(
    "/payroll/load_year_tasks",
    [PayrollController::class, "load_year_tasks"]
);