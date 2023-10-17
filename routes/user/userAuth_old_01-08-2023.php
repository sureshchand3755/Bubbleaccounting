<?php
use App\Http\Controllers\user\UserauthenticateController;

Route::get("/", [UserauthenticateController::class, "home"]);
Route::get("about", [UserauthenticateController::class, "about"]);
Route::get("modules", [UserauthenticateController::class, "modules"]);
Route::get("access-payroll", [UserauthenticateController::class, "access_payroll"]);
Route::get("bubble-books", [UserauthenticateController::class, "bubble_books"]);
Route::get("contact", [UserauthenticateController::class, "contact"]);
Route::get("schedule", [UserauthenticateController::class, "schedule"]);
Route::get("demo", [UserauthenticateController::class, "demo"]);
Route::get("login", [UserauthenticateController::class, "login"]);
Route::get("register", [UserauthenticateController::class, "register"]);

Route::get("payroll_index", [UserauthenticateController::class, "payroll_index"]);
Route::post("payroll_login", [UserauthenticateController::class, "payroll_login"]);
Route::post("verify_emp_no", [UserauthenticateController::class, "verify_emp_no"]);
Route::post(
    "payroll_register",
    [UserauthenticateController::class, "payroll_register"]
);
Route::get(
    "client-invoice-management",
    [UserauthenticateController::class, "client_invoice_management"]
);
Route::get(
    "the-2-bill-manager",
    [UserauthenticateController::class, "the_2_bill_manager"]
);
Route::get(
    "practice-financials",
    [UserauthenticateController::class, "practice_financials"]
);
Route::get(
    "the-payroll-management-system",
    [UserauthenticateController::class, "the_payroll_management_system"]
);
Route::get(
    "the-payroll-modern-reporting-system",
    [UserauthenticateController::class, "the_payroll_modern_reporting_system"]
);
Route::get(
    "the-year-end-manager",
    [UserauthenticateController::class, "the_year_end_manager"]
);
Route::get(
    "the-client-request-system",
    [UserauthenticateController::class, "the_client_request_system"]
);
Route::get(
    "the-vat-management-system",
    [UserauthenticateController::class, "the_vat_management_system"]
);
Route::get("the-rct-System", [UserauthenticateController::class, "the_rct_System"]);
Route::get(
    "the-cro-ard-system",
    [UserauthenticateController::class, "the_cro_ard_system"]
);
Route::get(
    "time-management-tools",
    [UserauthenticateController::class, "time_management_tools"]
);
Route::get(
    "the-infiles-system",
    [UserauthenticateController::class, "the_infiles_system"]
);
Route::get(
    "bubble-accounting",
    [UserauthenticateController::class, "bubble_accounting"]
);
Route::get("home", [UserauthenticateController::class, "login"]);
Route::post("/user/login", [UserauthenticateController::class, "postLogin"]);
Route::post(
    "user/user_registration",
    [UserauthenticateController::class, "user_registration"]
);
Route::post(
    "/user/check_user_login_count",
    [UserauthenticateController::class, "check_user_login_count"]
);
Route::post(
    "/user/user_logging_password",
    [UserauthenticateController::class, "user_logging_password"]
);
