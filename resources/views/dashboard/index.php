<!--
 |
 | In $plugin you'll find an instance of Plugin class.
 | If you'd like can pass variable to this view, for example:
 |
 | return PluginClassName()->view( 'dashboard.index', [ 'var' => 'value' ] );
 |
-->

<div class="html-sitemap wrap">
  <h1><?php echo $plugin->Name ?> Editor</h1>
  <div id="ak-html-sitemap-editor-app-root"></div>
  <script>
    var nonce = "<?php echo $nonce; ?>";
  </script>
  <?php if(AK_SITEMAP_DEV_MODE):?>
    <link rel="stylesheet" type="text/css" href="http://localhost:1234/index.css"></link>
    <script src="http://localhost:1234/index.js"></script>
  <?php endif?>

</div>