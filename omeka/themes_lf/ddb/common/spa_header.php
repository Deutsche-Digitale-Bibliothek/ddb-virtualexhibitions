<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php if (file_exists(BASE_DIR . '/sw.js')): ?>
    <link rel="manifest" href="<?php echo WEB_ROOT; ?>/manifest">
    <meta name="theme-color" content="#333333">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <link rel="apple-touch-icon" href="<?php echo WEB_THEME; ?>/ddb/images/icons/app/192x192.png">
    <?php endif; ?>
    <link href="<?php echo img('favicon.ico'); ?>" rel="shortcut icon">
    <meta name="format-detection" content="telephone=no">
    <?php
        if (isset($title)) { $titleParts[] = strip_formatting($title); }
        $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
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
    <?php $browserLang = ExhibitDdbHelper::getBrowserLanguage(); ?>
</head>
<body class="no-js exhibit-type-<?php echo $exhibitType; ?><?php echo ($exhibitType == 'litfass_ddb')? ' nav_color_' . $navcolor : ''; ?>">
<script>
    !function(){var e=document.getElementsByTagName("body")[0];e.className=e.className.replace(/\bno-js\b/g,"")}();
</script>
<div class="noscript">
  <div class="noscript-cont">
    <div class="container-fluid">
      <div>
        <?php if ($browserLang === 'de'): ?>
        <h2>JavaScript ist in deinem Browser deaktiviert</h2>
        <p>
            Bitte aktiviere JavaScript in deinem Browser oder installiere einen Browser, der JavaScript unterstützt,
            um diese virtuelle Ausstellung anschauen zu können.
        </p>
        <p>
            Weitere Informationen zu DDBstudio, der virtuellen Ausstellungsplattform der
            Deutschen Digitalen Bibliothek findest du
            <a href="https://pro.deutsche-digitale-bibliothek.de/ddbstudio"
            target="_blank" rel="noopener">hier</a>.
        </p>
        <?php else: ?>
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
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<nav aria-label="Sprunglinks" id="jump-to-navigation" class="jump-to-navigation" tabindex="0">
    <a href="#menu" id="jump-to-navigation-control" class="jump-to-navigation-control">
      Zur Navigation springen
    </a>
</nav>
