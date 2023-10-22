<?php
use App\Http\Controllers\facility\FacilityauthenticateController;
use App\Http\Controllers\facility\FacilityController;

Route::get("/facility", [FacilityauthenticateController::class, 'login']);
Route::post("/facility/login", [FacilityauthenticateController::class, 'postLogin']);
Route::get("/facility/forgot_password", [FacilityauthenticateController::class, 'forgot_password']);
Route::get("/facility/reset_password", [FacilityauthenticateController::class, 'reset_password']);
Route::post("/facility/update_reset_password", [FacilityauthenticateController::class, 'update_reset_password']);

Route::get("/facility/profile", [FacilityController::class, 'profile']);
Route::get("/facility/practice_screen", [FacilityController::class, 'practice_screen']);
Route::post("/facility/update_facility_setting", [FacilityController::class, 'update_facility_setting']);
Route::get("/facility/logout", [FacilityController::class, 'logout']);
Route::post("/facility/show_practice_informations", [FacilityController::class, 'show_practice_informations']);


Route::get("/facility/table_viewer", [FacilityController::class, 'table_viewer']);
Route::post("/facility/get_table_notes", [FacilityController::class, 'get_table_notes']);
Route::post(
    "/facility/update_table_notes",
    [FacilityController::class, 'update_table_notes']
);
Route::post(
    "/facility/show_table_viewer",
    [FacilityController::class, 'show_table_viewer']
);
Route::post(
    "/facility/show_table_viewer_append",
    [FacilityController::class, 'show_table_viewer_append']
);
Route::get(
    "/facility/manage_avatar",
    [FacilityController::class, 'manage_avatar']
);
Route::post(
    "/facility/upload_user_avatar_images",
    [FacilityController::class, 'upload_user_avatar_images']
);
Route::post(
    "/facility/show_cropped_image",
    [FacilityController::class, 'show_cropped_image']
);
Route::post(
    "/facility/save_cropped_image",
    [FacilityController::class, 'save_cropped_image']
);

Route::post(
    "/facility/edit_user_avatar",
    [FacilityController::class, 'edit_user_avatar']
);
Route::post(
    "/facility/update_cropped_image",
    [FacilityController::class, 'update_cropped_image']
);
Route::get(
    "/facility/default_email_image",
    [FacilityController::class, 'default_email_image']
);
Route::post(
    "/facility/edit_header_image",
    [FacilityController::class, 'edit_header_image']
);

Route::get("/facility/manage_cm_class", [FacilityController::class, 'cmclass']);
Route::post("/facility/add_cm_class", [FacilityController::class, 'addclass']);
Route::post(
    "/facility/edit_cm_class/{id?}",
    [FacilityController::class, 'editcmclass']
);
Route::post(
    "/facility/update_cm_class/",
    [FacilityController::class, 'updatecmclass']
);
Route::get(
    "/facility/deactive_cm_class/{id?}",
    [FacilityController::class, 'deactivecmclass']
);
Route::get(
    "/facility/active_cm_class/{id?}",
    [FacilityController::class, 'activecmclass']
);
Route::get("/facility/manage_cm_paper", [FacilityController::class, 'cmpaper']);
Route::post("/facility/add_cm_paper", [FacilityController::class, 'addpaper']);
Route::post(
    "/facility/edit_cm_paper/{id?}",
    [FacilityController::class, 'editcmpaper']
);
Route::post(
    "/facility/update_cm_paper/",
    [FacilityController::class, 'updatecmpaper']
);
Route::get(
    "/facility/deactive_cm_paper/{id?}",
    [FacilityController::class, 'deactivecmpaper']
);
Route::get(
    "/facility/active_cm_paper/{id?}",
    [FacilityController::class, 'activecmpaper']
);
Route::get(
    "/facility/system_setting_review",
    [FacilityController::class, 'system_setting_review']
);
Route::post(
    "/facility/show_system_module_settings",
    [FacilityController::class, 'show_system_module_settings']
);
Route::post(
    "/facility/fix_missing_records",
    [FacilityController::class, 'fix_missing_records']
);
Route::get(
    "/facility/default_nominal_codes",
    [FacilityController::class, 'default_nominal_codes']
);
Route::post(
    "/facility/add_nominal_code_financial",
    [FacilityController::class, 'add_nominal_code_financial']
);
Route::get(
    "/facility/copy_nominal_codes",
    [FacilityController::class, 'copy_nominal_codes']
);
Route::post(
    "/facility/populate_nominal_code",
    [FacilityController::class, 'populate_nominal_code']
);

Route::post(
    "/facility/edit_nominal_code_finance",
    [FacilityController::class, "edit_nominal_code_finance"]
);

Route::get(
    "/facility/categories",
    [FacilityController::class, "categories"]
);
Route::post(
    "/facility/addcategory",
    [FacilityController::class, "addcategory"]
);
Route::post(
    "/facility/editcategory/{id?}",
    [FacilityController::class, 'editcategory']
);
Route::post(
    "/facility/updatecategory/",
    [FacilityController::class, 'updatecategory']
);
Route::get(
    "/facility/deactivecategory/{id?}",
    [FacilityController::class, 'deactivecategory']
);
Route::get(
    "/facility/activecategory/{id?}",
    [FacilityController::class, 'activecategory']
);


Route::get(
    "/facility/subcategories",
    [FacilityController::class, "subcategories"]
);
Route::post(
    "/facility/addsubcategory",
    [FacilityController::class, "addsubcategory"]
);
Route::post(
    "/facility/editsubcategory/{id?}",
    [FacilityController::class, 'editsubcategory']
);
Route::post(
    "/facility/updatesubcategory/",
    [FacilityController::class, 'updatesubcategory']
);
Route::get(
    "/facility/deactivesubcategory/{id?}",
    [FacilityController::class, 'deactivesubcategory']
);
Route::get(
    "/facility/activesubcategory/{id?}",
    [FacilityController::class, 'activesubcategory']
);













