<?php
namespace AKSitemap\Http\Controllers\PageData;

use AKSitemap\Http\Controllers\Controller;
use AKSitemap\WPBones\Routing\API\Route;

class PageDataController extends Controller
{
    public function get() 
    {
        $pages = get_pages();
        return Route::response([
                "options" => get_option("AK_SITEMAP_DATA"),
                "pages"   => $pages
            ]);
    }

    public function save(\WP_REST_Request $request) 
    {
        $data = $request->get_body();
        $decoded = \json_decode($data, true);
        update_option("AK_SITEMAP_DATA", $decoded);
        return Route::response(["success"=>true]);
    }
}