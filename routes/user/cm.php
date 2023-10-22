<?php
use App\Http\Controllers\user\CmController;

Route::get("/user/client_management", [CmController::class, "clientmanagement"]);
Route::get(
    "/user/clientmanagement_paginate",
    [CmController::class, "clientmanagement_paginate"]
);
Route::post("/user/add_cm_clients", [CmController::class, "addcmclients"]);
Route::post("/user/edit_cm_client/{id?}", [CmController::class, "editcmclient"]);
Route::post("/user/copy_cm_client/{id?}", [CmController::class, "copycmclient"]);
Route::post("/user/cm_status_clients/", [CmController::class, "cm_status_clients"]);
Route::post("/user/save_image/", [CmController::class, "save_image"]);
Route::post("/user/cm_print_details/", [CmController::class, "cm_print_details"]);
Route::post("/user/update_cm_clients/", [CmController::class, "updatecmclients"]);
Route::get("/user/cm_search_clients", [CmController::class, "cm_search_clients"]);
Route::post(
    "/user/update_cm_incomplete_status",
    [CmController::class, "update_cm_incomplete_status"]
);
Route::post("/user/cm_report_pdf", [CmController::class, "cm_report_pdf"]);
Route::post(
    "/user/cm_report_pdf_type_2",
    [CmController::class, "cm_report_pdf_type_2"]
);
Route::post(
    "/user/download_report_pdfs",
    [CmController::class, "download_report_pdfs"]
);
Route::post("/user/cm_bulkreport_pdf", [CmController::class, "cm_bulkreport_pdf"]);
Route::post("/user/cm_report_csv", [CmController::class, "cm_report_csv"]);
Route::post("/user/cm_upload", [CmController::class, "cm_upload"]);
Route::post("/user/cm_bulk_email", [CmController::class, "cm_bulk_email"]);
Route::post(
    "/user/email_check_crypt_pin",
    [CmController::class, "email_check_crypt_pin"]
);
Route::get(
    "/user/get_cm_report_clients",
    [CmController::class, "get_cm_report_clients"]
);
Route::get(
    "/user/get_cm_bulk_clients",
    [CmController::class, "get_cm_bulk_clients"]
);
Route::get(
    "/user/get_cm_import_clients",
    [CmController::class, "get_cm_import_clients"]
);
Route::post("/user/import_new_clients", [CmController::class, "import_new_clients"]);
Route::get(
    "/user/import_new_clients_one",
    [CmController::class, "import_new_clients_one"]
);
Route::post(
    "/user/import_existing_clients",
    [CmController::class, "import_existing_clients"]
);
Route::get(
    "/user/import_existing_clients_one",
    [CmController::class, "import_existing_clients_one"]
);
Route::post(
    "/user/cm_statement_update",
    [CmController::class, "cm_statement_update"]
);
Route::post("/user/cm_client_invoice", [CmController::class, "cm_client_invoice"]);
Route::post("/user/cm_client_payroll", [CmController::class, "cm_client_payroll"]);
Route::post(
    "/user/cm_invoice_report_csv",
    [CmController::class, "cm_invoice_report_csv"]
);
Route::post(
    "/user/cm_get_csv_filename",
    [CmController::class, "cm_get_csv_filename"]
);
Route::post(
    "/user/cm_invoice_report_pdf",
    [CmController::class, "cm_invoice_report_pdf"]
);
Route::post(
    "/user/cm_invoice_download_report_pdfs",
    [CmController::class, "cm_invoice_download_report_pdfs"]
);
Route::get(
    "/user/print_selected_invoice",
    [CmController::class, "print_selected_invoice"]
);
Route::post("/user/cm_note_update", [CmController::class, "cm_note_update"]);
Route::post("/user/cm_client_add_bank", [CmController::class, "cm_client_add_bank"]);
Route::post(
    "/user/cm_load_all_client_invoice",
    [CmController::class, "cm_load_all_client_invoice"]
);
Route::post(
    "/user/cm_load_all_client_message",
    [CmController::class, "cm_load_all_client_message"]
);
Route::post(
    "/user/update_pms_vat_module",
    [CmController::class, "update_pms_vat_module"]
);
Route::post(
    "/user/change_send_statement_status",
    [CmController::class, "change_send_statement_status"]
);
Route::post(
    "/user/load_all_clients_cm_system",
    [CmController::class, "load_all_clients_cm_system"]
);
Route::post(
    "/user/load_single_cm_system",
    [CmController::class, "load_single_cm_system"]
);
Route::post(
    "/user/update_statement_invoice",
    [CmController::class, "update_statement_invoice"]
);
Route::post("/user/get_client_year_id", [CmController::class, "get_client_year_id"]);
Route::post(
    "/user/mui_icons_for_taskspecifics",
    [CmController::class, "mui_icons_for_taskspecifics"]
);
Route::get(
    "/user/cms_client_portal",
    [CmController::class, "cms_client_portal"]
);
Route::post(
    "/user/show_messageus_sample_screen_portal",
    [CmController::class, "show_messageus_sample_screen_portal"]
);

