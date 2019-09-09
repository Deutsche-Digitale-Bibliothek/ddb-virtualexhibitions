<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <?php
        if (isset($title)) { $titleParts[] = strip_formatting($title); }
        $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
    <link href="<?php echo img('favicon.ico'); ?>" rel="shortcut icon">
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>">
    <?php endif; ?>
    <?php
    echo js_tag('vendor/jwplayer/jwplayer') . "\n";
    echo js_tag('vendor/jwplayer/key') . "\n";
    ?>
    <?php queue_css_file('spa.min', 'all', false, 'css', date("YmdHis", filemtime(dirname(__FILE__) . '/../css/spa.min.css'))); ?>
    <?php echo head_css(); ?>
    <?php echo ExhibitDdbHelper::getSpaCss(ExhibitDdbHelper::getMenuColor($colors), $exhibitType, $navcolor); ?>
</head>
<body class="no-js exhibit-type-<?php echo $exhibitType; ?><?php echo ($exhibitType == 'litfass_ddb')? ' nav_color_' . $navcolor : ''; ?>">
<script>
    !function(){var e=document.getElementsByTagName("body")[0];e.className=e.className.replace(/\bno-js\b/g,"")}();
</script>
<div class="noscript">
  <div class="noscript-cont">
    <div class="container-fluid">
      <div>
        <h2>JavaScript Required</h2>
        <p>
            We're sorry, but this virtual exhibition cannot be viewed properly without JavaScript enabled.
            Please consider enabling JavaScript or installing a JavaScript capable browser.
        </p>
        <p>
            If you're interested in DDBstudio, the virtual exhibition platform provided by the
            German Digital Library,<br>
            please visit <a href="https://pro.deutsche-digitale-bibliothek.de/ddbstudio"
            target="_blank" rel="noopener">this page</a> (in German).
        </p>
      </div>
    </div>
  </div>
</div class="noscript">
