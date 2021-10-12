<?php

use AKSitemap\WPBones\Routing\API\Route;
use AKSitemap\Http\Controllers\PageData\PageDataController;

Route::get(
    "/page-data", 
    [PageDataController::class, 'get'], 
    ["permission_callback" => function() {
        return current_user_can('edit_posts');
    }]
);

Route::post(
    "/page-data",
    [PageDataController::class, 'save'],
    ["permission_callback" => function() {
        return current_user_can('edit_posts');
    }]
);