<?php
use App\Http\Controllers\user\SupplementaryController;

Route::get(
    "/user/supplementary_manager",
    [SupplementaryController::class, "supplementary_manager"]
);
Route::post(
    "/user/supplementary_add",
    [SupplementaryController::class, "supplementary_add"]
);
Route::get(
    "/user/supple_number_check",
    [SupplementaryController::class, "supple_number_check"]
);
Route::get(
    "/user/supplementary_note_create/{id}",
    [SupplementaryController::class, "supplementary_note_create"]
);
Route::post(
    "/user/supple_value_update",
    [SupplementaryController::class, "supple_value_update"]
);
Route::post(
    "/user/supple_type_update",
    [SupplementaryController::class, "supple_type_update"]
);
Route::post(
    "/user/supple_comboone_update",
    [SupplementaryController::class, "supple_comboone_update"]
);
Route::post(
    "/user/supple_formula_update",
    [SupplementaryController::class, "supple_formula_update"]
);
Route::post(
    "/user/supple_combotwo_update",
    [SupplementaryController::class, "supple_combotwo_update"]
);
Route::post(
    "/user/supplementary_load",
    [SupplementaryController::class, "supplementary_load"]
);
Route::post(
    "/user/update_fixed_text",
    [SupplementaryController::class, "update_fixed_text"]
);
Route::post(
    "/user/save_supplementary_note",
    [SupplementaryController::class, "save_supplementary_note"]
);
Route::post(
    "/user/update_supplementary_note",
    [SupplementaryController::class, "update_supplementary_note"]
);
Route::get(
    "/user/edit_supplementary_note/{id}",
    [SupplementaryController::class, "edit_supplementary_note"]
);
Route::post(
    "/user/supple_comboone_update_edit",
    [SupplementaryController::class, "supple_comboone_update_edit"]
);
Route::post(
    "/user/supple_formula_update_edit",
    [SupplementaryController::class, "supple_formula_update_edit"]
);
Route::post(
    "/user/supple_combotwo_update_edit",
    [SupplementaryController::class, "supple_combotwo_update_edit"]
);
Route::post(
    "/user/update_fixed_text_edit",
    [SupplementaryController::class, "update_fixed_text_edit"]
);
Route::post(
    "/user/supple_type_update_edit",
    [SupplementaryController::class, "supple_type_update_edit"]
);
Route::post(
    "/user/supple_value_update_edit",
    [SupplementaryController::class, "supple_value_update_edit"]
);
Route::get(
    "/user/delete_supplementary_note/{id}",
    [SupplementaryController::class, "delete_supplementary_note"]
);
Route::get(
    "/user/download_supplementary_note",
    [SupplementaryController::class, "download_supplementary_note"]
);