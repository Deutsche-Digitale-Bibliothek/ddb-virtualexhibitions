<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php $ddbBaseDomain = 'https://www.deutsche-digitale-bibliothek.de'; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo img('favicon.ico'); ?>" rel="shortcut icon" />
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php echo auto_discovery_link_tags(); ?>
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>
    <?php
    queue_css_file('bootstrap-responsive.min');
    queue_css_file('ddb-01');
    queue_css_file('ddb-02');
    // queue_css_url('//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
    queue_css_file('style');
    echo head_css();
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <?php
    queue_js_file('vendor/jquery.min');
    queue_js_file('vendor/bootstrap.min');
    queue_js_file('ddbtooltip.min');
    queue_js_file('globals.min');
    queue_js_file('vendor/colorbox/jquery.colorbox-min');
    queue_js_file('vendor/colorbox/i18n/jquery.colorbox-de');
    // queue_js_url('//code.jquery.com/ui/1.10.3/jquery-ui.js');
    queue_js_file('vendor/caroufredsel/jquery.carouFredSel-6.2.1-packed');
    queue_js_file('vendor/jwplayer/jwplayer');
    queue_js_file('vendor/jwplayer/key');
    echo head_js();
    ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
<noscript>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 warning">
                <span>Ihr Browser kann nicht alle Inhalte dieser Seite anzeigen.
                  Sie benötigen den Flash Player und/oder JavaScript, um die Seite vollständig darzustellen.</span>
            </div>
        </div>
    </div>
</noscript>
<?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
<!--[if lt IE 9]>
  <div class="header" role="contentinfo">
<![endif]-->
<!--[if !IE]><!-->
<div class="cookie-notice visible" id="cookie-notice">
  <div class="container">
    <div class="row">
      <div class="span12">
        <p>
          Diese Website setzt Cookies ein. Für die Nutzungsanalyse wird die Software Piwik verwendet.
          Wenn Sie der Nutzungsanalyse widersprechen oder mehr über Cookies erfahren möchten,
          klicken Sie bitte auf die <a href="https://www.deutsche-digitale-bibliothek.de/content/privacy">Datenschutzerklärung</a>.
        </p>
        <a class="close" aria-controls="cookie-notice"></a>
      </div>
    </div>
  </div>
</div>

<header class="navbar navbar-fixed-top visible-phone">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar" style="visibility: hidden;"></span>
      </button>
      <a href="<?php echo $ddbBaseDomain; ?>/" class="brand" title="Zur Startseite" tabindex="-1">
        <img src="<?php echo img('logo-phone.png'); ?>" alt="Logo: Deutsche Digitale Bibliothek">
      </a>
      <div class="nav-collapse navbar-collapse collapse">
        <ul class="nav nav-list">
          <li>
            <form action="/search" method="get" class="navbar-search pull-left" role="search" id="form-search-header-mobile">
              <input class="query" name="query" placeholder="Suche" type="search">
              <button type="submit">Suche</button>
              <a href="<?php echo $ddbBaseDomain; ?>/">Suche im DDB-Portal</a>
            </form>
          </li>
          <li><a href="<?php echo $ddbBaseDomain; ?>/">Startseite</a></li>
          <li>
            <a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns">Über uns</a>
            <div class="arrow-container"><div class="arrow-up"></div></div>
            <ul class="nav">
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns">Übersicht</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/mitmachen">Mitmachen</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/ddbpro">DDBPro</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/kompetenznetzwerk">Kompetenznetzwerk</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/fragen-antworten">Fragen &amp; Antworten</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/about-us/institutions">Institutionen</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/about-us/collections">Aus den Sammlungen</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/termine">Termine</a></li>
            </ul>
          </li>
          <li class="keep-in-front">
            <a href="<?php echo $ddbBaseDomain; ?>/content/journal">Journal</a>
            <div class="arrow-container"><div class="arrow-up"></div></div>
            <ul class="nav">
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal">Übersicht</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/aktuell">Aktuell</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/entdecken">Entdecken</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/hintergrund">Hintergrund</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/ausstellungen">Ausstellungen</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/journal/daily">Kalenderblatt</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/journal/persons">Personen</a></li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/archiv">Archiv</a></li>
            </ul>
          </li>
          <li><a href="<?php echo $ddbBaseDomain; ?>/content/help">Hilfe</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>
<header class="hidden-phone">
<!--<![endif]-->
<!--[if IE]>
<header class="ie-mobile">
<![endif]-->
  <h1 class="invisible-but-readable">Website-Kopfzeile</h1>
  <div class="container">
    <div class="row">
      <!--[if lt IE 9]>
          <div class="nav widget span12" data-widget="NavigationWidget">
      <![endif]-->
      <nav class="widget span12" data-widget="NavigationWidget">
        <div class="row">
          <div class="span7">
            <a href="<?php echo $ddbBaseDomain; ?>/" class="navigation-header-logo" title="Zur Startseite" tabindex="-1">
              <img src="<?php echo img('logo.png'); ?>" alt="Logo: Deutsche Digitale Bibliothek">
            </a>
            <div role="navigation">
              <ul class="navigation inline">
                <li class="root">
                  <a href="<?php echo $ddbBaseDomain; ?>/">Startseite</a>
                </li>
                <li class="keep-in-front">
                  <a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns">Über uns</a>
                  <div class="arrow-container"><div class="arrow-up"></div></div>
                  <ul>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns">Übersicht</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/mitmachen">Mitmachen</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/ddbpro">DDBPro</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/kompetenznetzwerk">Kompetenznetzwerk</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/fragen-antworten">Fragen &amp; Antworten</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/about-us/institutions">Institutionen</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/about-us/collections">Aus den Sammlungen</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/ueber-uns/termine">Termine</a></li>
                  </ul>
                </li>
                <li class="keep-in-front">
                  <a href="<?php echo $ddbBaseDomain; ?>/content/journal">Journal</a>
                  <div class="arrow-container"><div class="arrow-up"></div></div>
                  <ul>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal">Übersicht</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/aktuell">Aktuell</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/entdecken">Entdecken</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/hintergrund">Hintergrund</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/ausstellungen">Ausstellungen</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/journal/daily">Kalenderblatt</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/journal/persons">Personen</a></li>
                      <li><a href="<?php echo $ddbBaseDomain; ?>/content/journal/archiv">Archiv</a></li>
                  </ul>
                </li>
                <li><a href="<?php echo $ddbBaseDomain; ?>/content/help">Hilfe</a></li>
              </ul>
            </div>
          </div>
          <div class="span5 toolbar">
            <div class="status-bar ddb-exhibit-topnav-status-bar"></div>
            <div class="search-header hidden-phone ddb-exhibit-topnav-search-container">
              <?php echo search_form(array(
                'show_advanced' => false,
                'form_attributes' => array(
                  'id' => 'form-search-header',
                  'role' => 'search'
                ))); ?>
            </div>
          </div>
        </div>
      </nav>
      <!--[if lt IE 9]>
        </div>
      <![endif]-->
    </div>
  </div>
</header>
<!--[if lt IE 9]>
  </div>
<![endif]-->
<div id="wrap" class="container">
    <div id="content">
        <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
