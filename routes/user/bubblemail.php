<?php
use App\Http\Controllers\user\BubblemailController;

Route::get("/user/bubble_mail", [BubblemailController::class, "bubble_mail"]);
Route::post(
    "/user/get_client_message_list",
    [BubblemailController::class, "get_client_message_list"]
);
Route::post(
    "/user/view_bubble_mail_message",
    [BubblemailController::class, "view_bubble_mail_message"]
);
Route::post(
    "/user/bubble_load_all_clients",
    [BubblemailController::class, "bubble_load_all_clients"]
);