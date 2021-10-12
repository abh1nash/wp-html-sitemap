<?php

/*
|--------------------------------------------------------------------------
| Plugin activation
|--------------------------------------------------------------------------
|
| This file is included when the plugin is activated the first time.
| Usually you will use this file to register your custom post types or
| to perform some db delta process.
|
*/

if ( !get_option("AK_SITEMAP_DATA") ) {
    update_option("AK_SITEMAP_DATA", [
        [
            "name"      => "Default",
            "contain"   => "unlisted",
            "parent"    => 0,
            "includes"  => [],
            "excludes"  => [],
            "isDefault" => true
        ]
]);
}