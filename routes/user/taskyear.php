<?php
use App\Http\Controllers\user\TaskyearController;

Route::get("/user/manage_task", [TaskyearController::class, "taskyear"]);
Route::post("/user/add_taskyear/", [TaskyearController::class, "addtaskyear"]);
Route::get(
    "/user/deactive_taskyear/{id?}",
    [TaskyearController::class, "deactivetaskyear"]
);
Route::get(
    "/user/active_taskyear/{id?}",
    [TaskyearController::class, "activetaskyear"]
);
Route::get(
    "/user/delete_taskyear/{id?}",
    [TaskyearController::class, "deletetaskyear"]
);
Route::get("/user/edit_taskyear/{id?}", [TaskyearController::class, "edittaskyear"]);
Route::post("/user/update_taskyear/", [TaskyearController::class, "updatetaskyear"]);