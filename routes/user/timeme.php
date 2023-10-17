<?php
use App\Http\Controllers\user\TimemeController;

Route::get("/user/time_task", [TimemeController::class, "time_task"]);
Route::post(
    "/user/time_task_client_details",
    [TimemeController::class, "time_task_client_details"]
);
Route::post("/user/time_task_add", [TimemeController::class, "time_task_add"]);
Route::post("/user/time_task_update", [TimemeController::class, "time_task_update"]);
Route::post(
    "/user/time_task_client_counts",
    [TimemeController::class, "time_task_client_counts"]
);
Route::post(
    "/user/timetasklock_unlock",
    [TimemeController::class, "timetasklock_unlock"]
);
Route::post("/user/timetask_edit", [TimemeController::class, "timetask_edit"]);
Route::post("/user/time_task_review", [TimemeController::class, "time_task_review"]);
Route::post(
    "/user/time_task_review_all",
    [TimemeController::class, "time_task_review_all"]
);
Route::post(
    "/user/import_client_list_timeme",
    [TimemeController::class, "import_client_list_timeme"]
);