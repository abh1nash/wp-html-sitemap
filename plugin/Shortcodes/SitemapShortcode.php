<?php

namespace AKSitemap\Shortcodes;

use AKSitemap\WPBones\Foundation\WordPressShortcodesServiceProvider;

class SitemapShortcode extends WordPressShortcodesServiceProvider {

  protected $shortcodes = [
    'abhinash-html-sitemap' => 'renderSitemap'
  ];

  public function renderSitemap( $atts = [ ], $content = null )
  {
    $categories = get_option('AK_SITEMAP_DATA');
    $pages = get_pages();
    $parsedData = [];

    function canList($page, $category) 
    {
      $list = true;

      if($category['contain']=='selected') {
        $list = false;
      }

      if( $category['contain'] == 'children' && 
          $page->post_parent != $category['parent']) 
      {
        $list = false;
      }

      if(in_array($page->ID, $category['includes'])) 
      {
        $list = true;
      }

      if(in_array($page->ID, $category['excludes'])) 
      {
        $list = false;
      }

      return $list;
    }

    foreach ($categories as $index => $category) {
      $parsedData[$index] = [
        "pages" => [],
        "name" => $category["name"],
        "url" => $category["url"]
      ];
      foreach ($pages as $page) {
        if (canList($page, $category)) {
         array_push( $parsedData[$index]["pages"], $page );
        }
      }
    }
    return AKSitemap()->view( 'sitemap.index' )
                      ->with(['data' => $parsedData]);
  }

}