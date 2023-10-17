<?php
use App\Http\Controllers\user\TaController;

Route::get("/user/ta_system", [TaController::class, "ta_system"]);
Route::post(
    "/user/ta_system_ajax_response",
    [TaController::class, "ta_system_ajax_response"]
);
Route::post(
    "/user/load_unallocated_time_for_client",
    [TaController::class, "load_unallocated_time_for_client"]
);
Route::get("/user/ta_allocation", [TaController::class, "taallocation"]);
Route::get("/user/ta_overview", [TaController::class, "taoverview"]);
Route::get("/user/ta_auto_allocation", [TaController::class, "taautoallocation"]);
Route::get(
    "/user/ta_allocation_client_search",
    [TaController::class, "ta_allocation_client_search"]
);
Route::get(
    "/user/ta_allocation_client_search_result",
    [TaController::class, "ta_allocation_client_search_result"]
);
Route::post("/user/ta_invoice_update", [TaController::class, "tainvoiceupdate"]);
Route::post("/user/ta_tasks_update", [TaController::class, "tatasksupdate"]);
Route::post(
    "/user/ta_tasks_update_unallocate",
    [TaController::class, "tatasksupdateunallocate"]
);
Route::get(
    "/user/ta_overview_client_search",
    [TaController::class, "ta_overview_client_search"]
);
Route::get(
    "/user/ta_overview_client_search_result",
    [TaController::class, "ta_overview_client_search_result"]
);
Route::post("/user/ta_overview_invoice", [TaController::class, "taoverviewinvoice"]);
Route::get(
    "/user/ta_autoalloaction_client_search",
    [TaController::class, "ta_autoalloaction_client_search"]
);
Route::get(
    "/user/ta_autoalloaction_client_search_result",
    [TaController::class, "ta_autoalloaction_client_search_result"]
);
Route::post(
    "/user/ta_auto_allocation_invoice",
    [TaController::class, "taautoallocationinvoice"]
);
Route::post(
    "/user/ta_auto_allocation_tasks",
    [TaController::class, "taautoallocationtasks"]
);
Route::post(
    "/user/ta_auto_allocation_tasks_yes",
    [TaController::class, "taautoallocationtasks_yes"]
);
Route::post(
    "/user/ta_auto_allocation_tasks_yes_individual",
    [TaController::class, "taautoallocationtasks_yes_individual"]
);
Route::post(
    "/user/ta_auto_unallocation_tasks",
    [TaController::class, "taautounallocationtasks"]
);
Route::post("/user/ta_excluded", [TaController::class, "taexcluded"]);
Route::post("/user/ta_include", [TaController::class, "tainclude"]);
Route::post(
    "/user/download_csv_allocated_tasks",
    [TaController::class, "download_csv_allocated_tasks"]
);
Route::post(
    "/user/download_csv_allocated_invoices",
    [TaController::class, "download_csv_allocated_invoices"]
);
Route::post(
    "/user/download_csv_active_invoices",
    [TaController::class, "download_csv_active_invoices"]
);
Route::post(
    "/user/download_csv_task_summary",
    [TaController::class, "download_csv_task_summary"]
);
Route::post(
    "/user/load_all_clients_ta_system",
    [TaController::class, "load_all_clients_ta_system"]
);
Route::post(
    "/user/load_single_client_ta_system",
    [TaController::class, "load_single_client_ta_system"]
);