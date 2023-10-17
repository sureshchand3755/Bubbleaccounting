<?php
use App\Http\Controllers\user\CrmController;

Route::get(
    "/user/client_request_system",
    [CrmController::class, "clientrequestsystem"]
);
Route::get(
    "/user/client_request_manager/{id?}",
    [CrmController::class, "clientrequestmanager"]
);
Route::get(
    "/user/client_request_edit/{id?}",
    [CrmController::class, "client_requestedit"]
);
Route::post(
    "/user/client_request_modal",
    [CrmController::class, "clientrequestmodal"]
);
Route::post(
    "/user/request_add_bank_statement",
    [CrmController::class, "requestaddbankstatement"]
);
Route::get(
    "/user/request_delete_statement/{id?}",
    [CrmController::class, "requestdeletestatement"]
);
Route::post("/user/request_add_others", [CrmController::class, "requestaddothers"]);
Route::get(
    "/user/request_delete_other/{id?}",
    [CrmController::class, "requestdeleteother"]
);
Route::post("/user/request_add_cheque", [CrmController::class, "requestaddcheque"]);
Route::get(
    "/user/request_delete_cheque/{id?}",
    [CrmController::class, "requestdeletecheque"]
);
Route::get(
    "/user/request_bank_received/{id?}",
    [CrmController::class, "requestbankreceived"]
);
Route::get(
    "/user/request_cheque_received/{id?}",
    [CrmController::class, "requestchequereceived"]
);
Route::get(
    "/user/request_cheque_notreceived/{id?}",
    [CrmController::class, "requestchequenotreceived"]
);
Route::get(
    "/user/request_other_received/{id?}",
    [CrmController::class, "requestotherreceived"]
);
Route::get(
    "/user/request_other_notreceived/{id?}",
    [CrmController::class, "requestothernotreceived"]
);
Route::post(
    "/user/request_purchase_invoice_add",
    [CrmController::class, "requestpurchaseinvoiceadd"]
);
Route::get(
    "/user/request_purchase_received/{id?}",
    [CrmController::class, "requestpurchasereceived"]
);
Route::get(
    "/user/request_purchase_notreceived/{id?}",
    [CrmController::class, "requestpurchasenotreceived"]
);
Route::get(
    "/user/request_delete_purchase/{id?}",
    [CrmController::class, "requestdeletepurchase"]
);
Route::get(
    "/user/request_delete_purchase_attach/{id?}",
    [CrmController::class, "requestdeletepurchaseattach"]
);
Route::get(
    "/user/request_delete_cheque_attach/{id?}",
    [CrmController::class, "requestdeletechequeattach"]
);
Route::get(
    "/user/request_sales_received_attach/{id?}",
    [CrmController::class, "requestsalesreceivedattach"]
);
Route::get(
    "/user/request_sales_notreceived_attach/{id?}",
    [CrmController::class, "requestsalesnotreceivedattach"]
);
Route::get(
    "/user/request_bank_statement/{id?}",
    [CrmController::class, "requestbankstatement"]
);
Route::get(
    "/user/request_bank_statement_notreceived/{id?}",
    [CrmController::class, "requestbankstatementnotreceived"]
);
Route::get(
    "/user/request_cheque_received_attach/{id?}",
    [CrmController::class, "requestchequereceivedattach"]
);
Route::get(
    "/user/request_cheque_notreceived_attach/{id?}",
    [CrmController::class, "requestchequenotreceivedattach"]
);
Route::get(
    "/user/request_purchase_received_attach/{id?}",
    [CrmController::class, "requestpurchasereceivedattach"]
);
Route::get(
    "/user/request_purchase_notreceived_attach/{id?}",
    [CrmController::class, "requestpurchasenotreceivedattach"]
);
Route::post("/user/request_add_sales", [CrmController::class, "requestaddsales"]);
Route::get(
    "/user/request_sales_received/{id?}",
    [CrmController::class, "requestsalesreceived"]
);
Route::get(
    "/user/request_sales_notreceived/{id?}",
    [CrmController::class, "requestsalesnotreceived"]
);
Route::get(
    "/user/request_delete_sales/{id?}",
    [CrmController::class, "requestdeletesales"]
);
Route::get(
    "/user/request_delete_sales_attach/{id?}",
    [CrmController::class, "requestdeletesalesattach"]
);
Route::post(
    "/user/client_request_year_category_user/",
    [CrmController::class, "clientrequestyearcategoryuser"]
);
Route::get(
    "/user/request_received_all/{id?}",
    [CrmController::class, "requestreceivedall"]
);
Route::get("/user/request_new_add/{id?}", [CrmController::class, "requestnewadd"]);
Route::get("/user/request_delete/{id?}", [CrmController::class, "requestdelete"]);
Route::post(
    "/user/client_request_view/",
    [CrmController::class, "client_requestview"]
);
Route::post(
    "/user/download_request_view/",
    [CrmController::class, "download_request_view"]
);
Route::post(
    "/user/crm_upload_images_purchase",
    [CrmController::class, "crm_upload_images_purchase"]
);
Route::post(
    "/user/crm_upload_images_sales",
    [CrmController::class, "crm_upload_images_sales"]
);
Route::post(
    "/user/crm_upload_images_cheque",
    [CrmController::class, "crm_upload_images_cheque"]
);
Route::post(
    "/user/clear_session_attachments_purchase",
    [CrmController::class, "clear_session_attachments_purchase"]
);
Route::post(
    "/user/clear_session_attachments_sales",
    [CrmController::class, "clear_session_attachments_sales"]
);
Route::post(
    "/user/clear_session_attachments_cheque",
    [CrmController::class, "clear_session_attachments_cheque"]
);
Route::post(
    "/user/send_request_for_approval_edit",
    [CrmController::class, "send_request_for_approval_edit"]
);
Route::post(
    "/user/send_request_to_client_edit",
    [CrmController::class, "send_request_to_client_edit"]
);
Route::post(
    "/user/send_request_to_client_edit_none_received",
    [CrmController::class, "send_request_to_client_edit_none_received"]
);
Route::post(
    "/user/send_request_to_client_some_not_edit",
    [CrmController::class, "send_request_to_client_some_not_edit"]
);
Route::post("/user/email_to_client", [CrmController::class, "email_to_client"]);
Route::post(
    "/user/email_for_approval",
    [CrmController::class, "email_for_approval"]
);
Route::post(
    "/user/edit_crm_header_image",
    [CrmController::class, "edit_crm_header_image"]
);
Route::post(
    "/user/save_crm_settings",
    [CrmController::class, "save_crm_settings"]
);
