<?php
use App\Http\Controllers\admin\AdminauthenticateController;
use App\Http\Controllers\admin\AdminController;


Route::get("/admin", [AdminauthenticateController::class, 'login']);
Route::post("/admin/login", [AdminauthenticateController::class, 'postLogin']);
Route::get(
    "/admin/adminlogin",
    [AdminauthenticateController::class, 'adminpostLogin']
);
Route::get("/admin/manage_users", [AdminController::class, 'manage_users']);
Route::post("/admin/add_user_login", [AdminController::class, 'add_user_login']);
Route::post("/admin/edit_user_login", [AdminController::class, 'edit_user_login']);
Route::get(
    "/admin/edit_user_login_page/{id?}",
    [AdminController::class, 'edit_user_login_page']
);
Route::get("/admin/logout", [AdminController::class, 'logout']);
Route::get("/admin/profile", [AdminController::class, 'profile']);

Route::post(
    "/admin/update_admin_setting",
    [AdminController::class, 'update_admin_setting']
);
Route::post(
    "/admin/update_user_setting",
    [AdminController::class, 'update_user_setting']
);



