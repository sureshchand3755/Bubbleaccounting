<?php
use App\Http\Controllers\user\ProjecttrackingController;

Route::get(
    "/user/tracking_project",
    [ProjecttrackingController::class, "tracking_project"]
);
Route::get(
    "/user/tracking_project_common_search",
    [ProjecttrackingController::class, "tracking_project_common_search"]
);
Route::get(
    "/user/check_project_tracking_project_name",
    [ProjecttrackingController::class, "check_project_tracking_project_name"]
);
Route::post(
    "/user/create_project_tracking_project",
    [ProjecttrackingController::class, "create_project_tracking_project"]
);
Route::post(
    "/user/load_project_tracking_clients",
    [ProjecttrackingController::class, "load_project_tracking_clients"]
);
Route::post(
    "/user/cliets_list_for_project_tracking",
    [ProjecttrackingController::class, "cliets_list_for_project_tracking"]
);
Route::post(
    "/user/submit_clients_to_project_tracking",
    [ProjecttrackingController::class, "submit_clients_to_project_tracking"]
);
Route::post(
    "/user/save_tracking_project_status",
    [ProjecttrackingController::class, "save_tracking_project_status"]
);
Route::post(
    "/user/save_tracking_project_date",
    [ProjecttrackingController::class, "save_tracking_project_date"]
);
Route::post(
    "/user/save_tracking_project_comment",
    [ProjecttrackingController::class, "save_tracking_project_comment"]
);
Route::post(
    "/user/save_tracking_project_value",
    [ProjecttrackingController::class, "save_tracking_project_value"]
);
Route::post(
    "/user/get_computation_projects",
    [ProjecttrackingController::class, "get_computation_projects"]
);
Route::post(
    "/user/create_project_tracking_project_computation",
    [ProjecttrackingController::class, "create_project_tracking_project_computation"]
);
Route::post(
    "/user/load_project_tracking_clients_complex",
    [ProjecttrackingController::class, "load_project_tracking_clients_complex"]
);
Route::post(
    "/user/save_tracking_project_comment_monthly",
    [ProjecttrackingController::class, "save_tracking_project_comment_monthly"]
);
Route::post(
    "/user/show_complex_project_construction",
    [ProjecttrackingController::class, "show_complex_project_construction"]
);
Route::post(
    "/user/show_complex_project_construction_client",
    [ProjecttrackingController::class, "show_complex_project_construction_client"]
);
Route::post(
    "/user/update_complex_project_client_dependency",
    [ProjecttrackingController::class, "update_complex_project_client_dependency"]
);
Route::post(
    "/user/import_client_project_tracking",
    [ProjecttrackingController::class, "import_client_project_tracking"]
);
Route::post(
    "/user/check_import_csv_project_tracking",
    [ProjecttrackingController::class, "check_import_csv_project_tracking"]
);
Route::post(
    "/user/submit_import_csv_project_tracking",
    [ProjecttrackingController::class, "submit_import_csv_project_tracking"]
);
Route::post(
    "/user/export_client_project_tracking",
    [ProjecttrackingController::class, "export_client_project_tracking"]
);
Route::post(
    "/user/insert_tracking_project_copy_pasted_data",
    [ProjecttrackingController::class, "insertTrackingProjectCopyPastedData"]
);