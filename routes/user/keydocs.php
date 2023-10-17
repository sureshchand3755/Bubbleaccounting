<?php
use App\Http\Controllers\user\KeydocsController;

Route::get("/user/key_docs", [KeydocsController::class, "key_docs"]);
Route::post(
    "/user/load_year_end_docs",
    [KeydocsController::class, "load_year_end_docs"]
);
Route::post(
    "/user/download_year_end_documents",
    [KeydocsController::class, "download_year_end_documents"]
);
Route::post(
    "/user/upload_key_docs_letter",
    [KeydocsController::class, "upload_key_docs_letter"]
);
Route::post(
    "/user/download_key_docs_letters",
    [KeydocsController::class, "download_key_docs_letters"]
);
Route::post(
    "/user/download_key_docs_otherdocs",
    [KeydocsController::class, "download_key_docs_otherdocs"]
);
Route::post(
    "/user/download_key_docs_tax",
    [KeydocsController::class, "download_key_docs_tax"]
);
Route::post(
    "/user/save_letter_notes",
    [KeydocsController::class, "save_letter_notes"]
);
Route::post("/user/delete_letter", [KeydocsController::class, "delete_letter"]);
Route::post("/user/delete_tax", [KeydocsController::class, "delete_tax"]);
Route::post(
    "/user/delete_current_tax",
    [KeydocsController::class, "delete_current_tax"]
);
Route::get(
    "/user/key_docs_client_select",
    [KeydocsController::class, "key_docs_client_select"]
);
Route::post(
    "/user/save_keydocs_settings",
    [KeydocsController::class, "save_keydocs_settings"]
);
Route::post(
    "/user/keydocs_email_selected_pdf",
    [KeydocsController::class, "keydocs_email_selected_pdf"]
);
Route::post(
    "/user/delete_company_formation",
    [KeydocsController::class, "delete_company_formation"]
);
Route::post(
    "/user/download_key_docs_company_formation_zip",
    [KeydocsController::class, "download_key_docs_company_formation_zip"]
);
Route::post(
    "/user/email_key_docs_company_formation_zip",
    [KeydocsController::class, "email_key_docs_company_formation_zip"]
);
Route::post(
    "/user/email_key_docs_company_formation_multiple",
    [KeydocsController::class, "email_key_docs_company_formation_multiple"]
);
Route::post(
    "/user/company_formation_email",
    [KeydocsController::class, "company_formation_email"]
);
Route::post(
    "/user/add_document_batches_key_docs",
    [KeydocsController::class, "add_document_batches_key_docs"]
);
Route::post(
    "/user/update_document_batches_key_docs",
    [KeydocsController::class, "update_document_batches_key_docs"]
);
Route::post(
    "/user/manage_document_batches_key_docs",
    [KeydocsController::class, "manage_document_batches_key_docs"]
);
Route::post(
    "/user/change_document_batch_status",
    [KeydocsController::class, "change_document_batch_status"]
);
Route::post(
    "/user/delete_document_batch_keydocs",
    [KeydocsController::class, "delete_document_batch_keydocs"]
);
Route::post(
    "/user/get_document_batch_keydocs_downdown_list",
    [KeydocsController::class, "get_document_batch_keydocs_downdown_list"]
);
Route::post(
    "/user/get_document_list_for_batch",
    [KeydocsController::class, "get_document_list_for_batch"]
);
Route::post(
    "/user/invoice_save_selected_pdfs",
    [KeydocsController::class, "invoice_save_selected_pdfs"]
);
Route::post(
    "/user/save_year_end_documents",
    [KeydocsController::class, "save_year_end_documents"]
);
Route::post(
    "/user/delete_document_batch_attachment",
    [KeydocsController::class, "delete_document_batch_attachment"]
);
Route::post(
    "/user/email_setup_document_batch_keydocs",
    [KeydocsController::class, "email_setup_document_batch_keydocs"]
);
Route::post(
    "/user/send_document_batches_email_to_client",
    [KeydocsController::class, "send_document_batches_email_to_client"]
);
Route::post(
    "/user/get_document_batch_email_history",
    [KeydocsController::class, "get_document_batch_email_history"]
);
Route::post(
    "/user/show_messageus_sample_screen_keydocs",
    [KeydocsController::class, "show_messageus_sample_screen_keydocs"]
);
Route::post(
    "/user/delete_otherdocs",
    [KeydocsController::class, "delete_otherdocs"]
);
Route::post(
    "/user/save_otherdocs_notes",
    [KeydocsController::class, "save_otherdocs_notes"]
);
Route::post(
    "/user/edit_keydocs_header_image",
    [KeydocsController::class, 'edit_keydocs_header_image']
);
