<?php

namespace AKSitemap\Http\Controllers\Dashboard;

use AKSitemap\Http\Controllers\Controller;

class DashboardController extends Controller
{

  public function index()
  {
    return AKSitemap()->view( 'dashboard.index' )
                      ->with(['nonce' => wp_create_nonce( 'wp_rest' )])
                      ->withAdminScripts('index', array(), "1.0.0")
                      ->withAdminStyles('index', array(), "1.0.0");
  }
}