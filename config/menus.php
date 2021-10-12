<?php

/*
|--------------------------------------------------------------------------
| Plugin Menus routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the menu routes for a plugin.
| In this context, the route are the menu link.
|
*/

return [
  'html_sitemap_slug_menu' => [
    "page_title" => "HTML Sitemap Editor",
    "menu_title" => "HTML Sitemap",
    'capability' => 'read',
    'icon'       => 'dashicons-rest-api',
    'items'      => [
      [
        "page_title" => "Main View",
        "menu_title" => "Main View",
        'capability' => 'read',
        'route'      => [
          'get' => 'Dashboard\DashboardController@index'
        ],
      ],
    ]
  ]
];