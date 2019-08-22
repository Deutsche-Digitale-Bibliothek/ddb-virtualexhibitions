<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<body class="exhibit-type-<?php echo $exhibitType; ?><?php echo ($exhibitType == 'litfass_ddb')? ' nav_color_' . $navcolor : ''; ?>">
