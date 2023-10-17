<?php
use App\Http\Controllers\user\MessageusController;

Route::get("/user/directmessaging", [MessageusController::class, "directmessaging"]);
Route::post(
    "/user/messageus_upload_images_add",
    [MessageusController::class, "messageus_upload_images_add"]
);
Route::post(
    "/user/messageus_remove_dropzone_attachment",
    [MessageusController::class, "messageus_remove_dropzone_attachment"]
);
Route::post(
    "/user/messageus_add_comment_to_attachment",
    [MessageusController::class, "messageus_add_comment_to_attachment"]
);
Route::post(
    "/user/get_attachment_notes",
    [MessageusController::class, "get_attachment_notes"]
);
Route::post(
    "/user/message_remove_dropzone_attachment",
    [MessageusController::class, "message_remove_dropzone_attachment"]
);
Route::post(
    "/user/save_message_page_one",
    [MessageusController::class, "save_message_page_one"]
);
Route::get(
    "/user/directmessaging_page_two",
    [MessageusController::class, "directmessaging_page_two"]
);
Route::get(
    "/user/directmessaging_page_three",
    [MessageusController::class, "directmessaging_page_three"]
);
Route::get(
    "/user/messageus_groups",
    [MessageusController::class, "messageus_groups"]
);
Route::get(
    "/user/messageus_saved_messages",
    [MessageusController::class, "messageus_saved_messages"]
);
Route::post(
    "/user/save_message_page_two",
    [MessageusController::class, "save_message_page_two"]
);
Route::post(
    "/user/send_message_later",
    [MessageusController::class, "send_message_later"]
);
Route::post(
    "/user/send_message_now",
    [MessageusController::class, "send_message_now"]
);
Route::post(
    "/user/create_group_name",
    [MessageusController::class, "create_group_name"]
);
Route::post(
    "/user/select_messageus_group",
    [MessageusController::class, "select_messageus_group"]
);
Route::post(
    "/user/add_selected_member_to_group",
    [MessageusController::class, "add_selected_member_to_group"]
);
Route::post(
    "/user/remove_selected_member_to_group",
    [MessageusController::class, "remove_selected_member_to_group"]
);
Route::post(
    "/user/delete_messageus_groups",
    [MessageusController::class, "delete_messageus_groups"]
);
Route::get(
    "/user/delete_saved_message",
    [MessageusController::class, "delete_saved_message"]
);
Route::post(
    "/user/choose_messageus_from",
    [MessageusController::class, "choose_messageus_from"]
);
Route::post(
    "/user/show_messageus_sample_screen",
    [MessageusController::class, "show_messageus_sample_screen"]
);
Route::post(
    "/user/update_pms_groups",
    [MessageusController::class, "update_pms_groups"]
);
Route::post(
    "/user/get_pms_clients",
    [MessageusController::class, "get_pms_clients"]
);
Route::post(
    "/user/create_messageus_pms_groups",
    [MessageusController::class, "create_messageus_pms_groups"]
);
Route::post(
    "/user/edit_messageus_header_image",
    [MessageusController::class, 'edit_messageus_header_image']
);
Route::post(
    "/user/update_messageus_settings",
    [MessageusController::class, "update_messageus_settings"]
);