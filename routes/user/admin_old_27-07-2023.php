<?php
use App\Http\Controllers\admin\AdminauthenticateController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\YearController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\TaskyearController;
use App\Http\Controllers\admin\CmsystemController;
use App\Http\Controllers\admin\RequestController;
use App\Http\Controllers\admin\RctClientsController;
use App\Http\Controllers\admin\SalutionController;
use App\Http\Controllers\admin\LetterController;

Route::get("/admin", [AdminauthenticateController::class, 'login']);
Route::post("/admin/login", [AdminauthenticateController::class, 'postLogin']);
Route::get(
    "/admin/adminlogin",
    [AdminauthenticateController::class, 'adminpostLogin']
);
Route::get("/admin/manage_users", [AdminController::class, 'manage_users']);
Route::get("/admin/table_viewer", [AdminController::class, 'table_viewer']);
Route::post(
    "/admin/show_table_viewer",
    [AdminController::class, 'show_table_viewer']
);
Route::post(
    "/admin/show_table_viewer_append",
    [AdminController::class, 'show_table_viewer_append']
);
Route::post("/admin/add_user_login", [AdminController::class, 'add_user_login']);
Route::post("/admin/edit_user_login", [AdminController::class, 'edit_user_login']);
Route::get(
    "/admin/edit_user_login_page/{id?}",
    [AdminController::class, 'edit_user_login_page']
);
Route::get("/admin/logout", [AdminController::class, 'logout']);
Route::get("/admin/profile", [AdminController::class, 'profile']);
Route::get("/admin/vat_profile", [AdminController::class, 'vatprofile']);
Route::get("/admin/email_settings", [AdminController::class, 'email_settings']);
Route::get("/admin/p30_profile", [AdminController::class, 'p30profile']);
Route::post(
    "/admin/update_admin_setting",
    [AdminController::class, 'update_admin_setting']
);
Route::post(
    "/admin/update_user_notification",
    [AdminController::class, 'update_user_notification']
);
Route::post(
    "/admin/update_user_signature",
    [AdminController::class, 'update_user_signature']
);
Route::post(
    "/admin/update_user_setting",
    [AdminController::class, 'update_user_setting']
);
Route::post(
    "/admin/update_email_setting",
    [AdminController::class, 'update_email_setting']
);

Route::get("/admin/manage_year", [YearController::class, 'manageyear']);
Route::get("/admin/deactive_year/{id?}", [YearController::class, 'deactiveyear']);
Route::get("/admin/active_year/{id?}", [YearController::class, 'activeyear']);
Route::post("/admin/add_year/", [YearController::class, 'addyear']);
Route::get("/admin/delete_year/{id?}", [YearController::class, 'deleteyear']);
Route::post("/admin/edit_year/{id?}", [YearController::class, 'edityear']);
Route::post("/admin/update_year/", [YearController::class, 'updateyear']);
Route::post("/admin/check_year/", [YearController::class, 'checkyear']);
Route::get("/admin/manage_user", [UserController::class, 'manageuser']);
Route::get("/admin/deactive_user/{id?}", [UserController::class, 'deactiveuser']);
Route::get("/admin/active_user/{id?}", [UserController::class, 'activeuser']);
Route::post("/admin/add_user/", [UserController::class, 'adduser']);
Route::get("/admin/delete_user/{id?}", [UserController::class, 'deleteuser']);
Route::post("/admin/edit_user/{id?}", [UserController::class, 'edituser']);
Route::post("/admin/update_user/", [UserController::class, 'updateuser']);
Route::get(
    "/admin/central_locations",
    [AdminController::class, 'central_locations']
);
Route::post(
    "/admin/update_central_locations",
    [AdminController::class, 'update_central_locations']
);
Route::post(
    "/admin/update_central_locations_form",
    [AdminController::class, 'update_central_locations_form']
);
Route::get("/admin/manage_task/", [TaskyearController::class, 'taskyear']);
Route::post("/admin/add_taskyear/", [TaskyearController::class, 'addtaskyear']);
Route::get(
    "/admin/deactive_taskyear/{id?}",
    [TaskyearController::class, 'deactivetaskyear']
);
Route::get(
    "/admin/active_taskyear/{id?}",
    [TaskyearController::class, 'activetaskyear']
);
Route::get(
    "/admin/delete_taskyear/{id?}",
    [TaskyearController::class, 'deletetaskyear']
);
Route::get(
    "/admin/edit_taskyear/{id?}",
    [TaskyearController::class, 'edittaskyear']
);
Route::post(
    "/admin/update_taskyear/",
    [TaskyearController::class, 'updatetaskyear']
);
Route::get(
    "/admin/clear_receipt_system",
    [AdminController::class, 'clear_receipt_system']
);
Route::get(
    "/admin/clear_payment_system",
    [AdminController::class, 'clear_payment_system']
);
Route::get("/admin/cm_profile", [CmsystemController::class, 'cmprofile']);
Route::post("/admin/update_cm_crypt", [CmsystemController::class, 'updatecmcrypt']);
Route::get(
    "/admin/cm_clients_list",
    [CmsystemController::class, 'cm_clients_list']
);
Route::get("/admin/manage_cm_class", [CmsystemController::class, 'cmclass']);
Route::post("/admin/add_cm_class", [CmsystemController::class, 'addclass']);
Route::post(
    "/admin/edit_cm_class/{id?}",
    [CmsystemController::class, 'editcmclass']
);
Route::post(
    "/admin/update_cm_class/",
    [CmsystemController::class, 'updatecmclass']
);
Route::get(
    "/admin/deactive_cm_class/{id?}",
    [CmsystemController::class, 'deactivecmclass']
);
Route::get(
    "/admin/active_cm_class/{id?}",
    [CmsystemController::class, 'activecmclass']
);
Route::get("/admin/manage_cm_paper", [CmsystemController::class, 'cmpaper']);
Route::post("/admin/add_cm_paper", [CmsystemController::class, 'addpaper']);
Route::post(
    "/admin/edit_cm_paper/{id?}",
    [CmsystemController::class, 'editcmpaper']
);
Route::post(
    "/admin/update_cm_paper/",
    [CmsystemController::class, 'updatecmpaper']
);
Route::get(
    "/admin/deactive_cm_paper/{id?}",
    [CmsystemController::class, 'deactivecmpaper']
);
Route::get(
    "/admin/active_cm_paper/{id?}",
    [CmsystemController::class, 'activecmpaper']
);
Route::get("/admin/manage_cm_fields", [CmsystemController::class, 'cmfields']);
Route::post("/admin/add_cm_field", [CmsystemController::class, 'addfield']);
Route::post("/admin/edit_cm_field/{id?}", [CmsystemController::class, 'editfield']);
Route::post(
    "/admin/update_cm_field/",
    [CmsystemController::class, 'updatecmfield']
);
Route::get(
    "/admin/deactive_cm_field/{id?}",
    [CmsystemController::class, 'deactivefield']
);
Route::get(
    "/admin/active_cm_field/{id?}",
    [CmsystemController::class, 'activefield']
);
Route::get(
    "/admin/cm_client_checkfield/",
    [CmsystemController::class, 'cm_client_checkfield']
);
Route::get(
    "/admin/cm_search_clients",
    [CmsystemController::class, 'cm_search_clients']
);
Route::post(
    "/admin/update_cm_incomplete_status",
    [CmsystemController::class, 'update_cm_incomplete_status']
);
Route::post(
    "/admin/change_cm_client_class",
    [CmsystemController::class, 'change_cm_client_class']
);
Route::get(
    "/admin/setup_request_category",
    [RequestController::class, 'setuprequestcategory']
);
Route::get(
    "/admin/deactive_request/{id?}",
    [RequestController::class, 'deactiverequest']
);
Route::get(
    "/admin/active_request/{id?}",
    [RequestController::class, 'activerequest']
);
Route::get(
    "/admin/delete_request/{id?}",
    [RequestController::class, 'deleterequest']
);
Route::post(
    "/admin/request_signature",
    [RequestController::class, 'requestsignature']
);
Route::post("/admin/request_add", [RequestController::class, 'requestadd']);
Route::get(
    "/admin/request_edit_category",
    [RequestController::class, 'request_edit_category']
);
Route::post(
    "/admin/request_edit_form",
    [RequestController::class, 'request_edit_form']
);
Route::get("/admin/manage_cro", [AdminController::class, 'manage_cro']);
Route::post(
    "/admin/update_cro_setting",
    [AdminController::class, 'update_cro_setting']
);
Route::get(
    "/admin/clear_opening_balance",
    [AdminController::class, 'clear_opening_balance']
);
Route::post(
    "/admin/clear_all_opening_balance",
    [AdminController::class, 'clear_all_opening_balance']
);
Route::post(
    "/admin/clear_opening_balance_for_client",
    [AdminController::class, 'clear_opening_balance_for_client']
);
Route::get("admin/table_viewer", [AdminController::class, 'table_viewer']);
Route::post("admin/get_table_notes", [AdminController::class, 'get_table_notes']);
Route::post(
    "admin/update_table_notes",
    [AdminController::class, 'update_table_notes']
);
Route::post(
    "admin/show_table_viewer",
    [AdminController::class, 'show_table_viewer']
);

/*----------------------RCT START----------------------*/
Route::get(
    "/admin/manage_rctclients",
    [RctClientsController::class, 'managerctclients']
);
Route::get(
    "/admin/deactive_rctclients/{id?}",
    [RctClientsController::class, 'deactiverctclients']
);
Route::get(
    "/admin/active_rctclients/{id?}",
    [RctClientsController::class, 'activerctclients']
);
Route::post(
    "/admin/add_rctclients/",
    [RctClientsController::class, 'addrctclients']
);
Route::get(
    "/admin/delete_rctclients/{id?}",
    [RctClientsController::class, 'deleterctclients']
);
Route::post(
    "/admin/edit_rctclients/{id?}",
    [RctClientsController::class, 'editrctclients']
);
Route::post(
    "/admin/update_rctclients/",
    [RctClientsController::class, 'updaterctclients']
);
Route::get(
    "/admin/rctclient_checkemail/",
    [RctClientsController::class, 'client_checkemail']
);
Route::get(
    "/admin/rctclient_checktax/",
    [RctClientsController::class, 'client_checktax']
);
Route::get(
    "/admin/manage_rctemail_salution",
    [SalutionController::class, 'manage_salution']
);
Route::get(
    "/admin/manage_rctbackground",
    [LetterController::class, 'manage_letterpad']
);
Route::get(
    "/admin/rctclient_search",
    [RctClientsController::class, 'rctclientsearch']
);
Route::get(
    "/admin/rctclient_search_select",
    [RctClientsController::class, 'rctclientsearchselect']
);
Route::get(
    "/admin/rctclient_tax_search",
    [RctClientsController::class, 'clienttaxsearch']
);
Route::get(
    "/admin/rctclient_tax_search_select",
    [RctClientsController::class, 'clienttaxsearchselect']
);
Route::get(
    "/admin/rctclient_email_search",
    [RctClientsController::class, 'clientemailsearch']
);
Route::get(
    "/admin/rctclient_email_search_select",
    [RctClientsController::class, 'clientemailsearchselect']
);
Route::get(
    "/admin/manage_avatar",
    [AdminController::class, 'manage_avatar']
);
Route::post(
    "/admin/upload_user_avatar_images",
    [AdminController::class, 'upload_user_avatar_images']
);
Route::post(
    "/admin/show_cropped_image",
    [AdminController::class, 'show_cropped_image']
);
Route::post(
    "/admin/save_cropped_image",
    [AdminController::class, 'save_cropped_image']
);





