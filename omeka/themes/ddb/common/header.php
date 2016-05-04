<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
    queue_css_url('//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
    queue_css_file('style');
    // queue_css_file('../javascripts/vendor/colorbox/colorbox');

    echo head_css();
    ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <?php
    queue_js_file('vendor/jquery.min');
    queue_js_file('vendor/bootstrap.min');
    // queue_js_file('ddb');
    // queue_js_file('messages');
    queue_js_file('ddbtooltip');
    queue_js_file('globals');
    queue_js_file('vendor/colorbox/jquery.colorbox-min');
    queue_js_file('vendor/colorbox/i18n/jquery.colorbox-de');
    queue_js_url('//code.jquery.com/ui/1.10.3/jquery-ui.js');
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
                    <span>Ihr Browser kann nicht alle Inhalte dieser Seite anzeigen. Sie benötigen den Flash Player und/oder JavaScript, um die Seite vollständig darzustellen.</span>
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
          Diese Website setzt Cookies ein. Für die Nutzungsanalyse wird die Software Piwik verwendet. Wenn Sie der Nutzungsanalyse widersprechen oder mehr über Cookies erfahren möchten, klicken Sie bitte auf die <a href="https://www.deutsche-digitale-bibliothek.de/content/privacy">Datenschutzerklärung</a>.
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
      <a href="https://www.deutsche-digitale-bibliothek.de/" class="brand" title="Zur Startseite" tabindex="-1">
        <img src="<?php echo img('logo-phone.png'); ?>" alt="Logo: Deutsche Digitale Bibliothek">
      </a>
      <div class="nav-collapse collapse">
        <ul class="nav nav-list">
          <li class=""><form action="/search" method="get" class="navbar-search pull-left" role="search" id="form-search-header-mobile">
              <input class="query" name="query" placeholder="Suche" type="search">
              <button type="submit">
                Suche
              </button>
              <a href="https://www.deutsche-digitale-bibliothek.de/">Suche im DDB-Portal</a>
            </form></li>

          <li class="">
            <a href="https://www.deutsche-digitale-bibliothek.de/content/about">
              Über uns
            </a>
<!--             <ul class="nav">
              <li class="">
                <a href="https://www.deutsche-digitale-bibliothek.de/content/news">Aktuelles</a>
              </li>
              <li class="">
                <a href="https://www.deutsche-digitale-bibliothek.de/about-us/institutions#list">Institutionen</a>
              </li>
              <li class="">
                <a href="https://www.deutsche-digitale-bibliothek.de/content/ddb">Mitmachen!</a>
              </li>
              <li class="">
                <a href="https://www.deutsche-digitale-bibliothek.de/content/competence-network">Kompetenznetzwerk</a>
              </li>
              <li class="">
                <a href="https://www.deutsche-digitale-bibliothek.de/content/faq">Fragen &amp; Antworten</a>
              </li>
            </ul> -->
          </li>
          <li>
            <a href="https://www.deutsche-digitale-bibliothek.de/content/help">Hilfe</a>
          </li>
          <li class="active">
            <a href="https://www.deutsche-digitale-bibliothek.de/content/exhibits/">Entdecken</a>
<!--             <ul class="nav">
              <li class="active">
                <a href="/content/exhibits/">Ausstellungen</a>
              </li>
              <li>
                <a href="/lists">Favoritenlisten</a>
              </li>
              <li>
                <a href="/persons">Personen</a>
              </li>
            </ul> -->
          </li>
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
            <a href="https://www.deutsche-digitale-bibliothek.de/" class="navigation-header-logo" title="Zur Startseite" tabindex="-1">
              <img src="<?php echo img('logo.png'); ?>" alt="Logo: Deutsche Digitale Bibliothek">
            </a>
            <div role="navigation">
              <ul class="navigation inline">
                <li class="root">
                  <a href="https://www.deutsche-digitale-bibliothek.de/">Startseite</a><br>
                </li>
                <li>
                  <a href="https://www.deutsche-digitale-bibliothek.de/content/about">Über uns</a>
                  <!-- <ul>
                    <li class="">
                      <a href="https://www.deutsche-digitale-bibliothek.de/content/news">Aktuelles</a>
                    </li>
                    <li class="">
                      <a href="https://www.deutsche-digitale-bibliothek.de/about-us/institutions">Institutionen</a>
                    </li>
                    <li class="">
                      <a href="https://www.deutsche-digitale-bibliothek.de/content/ddb">Mitmachen!</a>
                    </li>
                    <li class="">
                      <a href="https://www.deutsche-digitale-bibliothek.de/content/competence-network">Kompetenznetzwerk</a>
                    </li>
                    <li class="">
                      <a href="https://www.deutsche-digitale-bibliothek.de/content/faq">Fragen &amp; Antworten</a>
                    </li>
                  </ul> -->
                </li>
                <li>
                  <a href="https://www.deutsche-digitale-bibliothek.de/content/help">Hilfe</a>
                </li>
                <li class="keep-in-front active-default">
                    <a href="https://www.deutsche-digitale-bibliothek.de/content/exhibits/">Entdecken</a>
                <?php
                // $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                // $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
            /* Controllers are:
            exhibits
            items
            collections
            */
            ?>
<!--                   <ul>
                    <li class="<?php // echo ($controller == 'exhibits' && $action == 'browse')? 'active-default': ''; ?>">
                      <a href="/exhibits">Liste der Ausstellungen</a>
                    </li>
                    <li class="<?php // echo ($controller == 'collections' && $action == 'browse')? 'active-default': ''; ?>">
                      <a href="/collections/browse">Sammlungen</a>
                    </li>
                    <li class="<?php // echo ($controller == 'items' && $action == 'browse')? 'active-default': ''; ?>">
                      <a href="/items/browse">Objekte</a>
                    </li>
                  </ul> -->
                </li>
              </ul>
            </div>
          </div>
          <div class="span5 toolbar">
            <div class="status-bar" style="height:20px">

<!--                 <div class="login-wrapper">
                  <a href="https://www.deutsche-digitale-bibliothek.de/login">Anmelden</a>
                </div> -->


              <!-- <div class="header-spacer"></div> -->
<!--               <div class="language-wrapper">
                <a href="#"> Deutsch
                </a>
                <ul class="selector language">
                  <li><a class="nopointer">Deutsch</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/help?lang=en">
                      English
                    </a></li>
                </ul>
              </div> -->
            </div>
            <div class="search-header hidden-phone">

              <?php echo search_form(array(
                'show_advanced' => false,
                'form_attributes' => array(
                  'id' => 'form-search-header',
                  'role' => 'search',
                ))); ?>

<!--               <form action="/searchresults" method="get" role="search" id="form-search-header-x">
                <label for="search-small"> <span>Suchtext-Feld</span>
                </label>
                <input id="querycache" value="" type="hidden">
                <input id="search-small" class="query" name="query" autocomplete="off" type="search">
                <span class="contextual-help hidden-phone hidden-tablet" data-content="Geben Sie Ihren Suchbegriff in das Suchfeld ein. Klicken Sie auf das Lupensymbol oder drücken Sie die Eingabetaste. &lt;a href=&quot;/content/help/search-simple&quot;&gt; Hilfe zur einfachen Suche &lt;/a&gt;">
                </span>
                <a href="https://www.deutsche-digitale-bibliothek.de/advancedsearch" class="link-adv-search">
                  Erweiterte Suche
                </a>
                <div style="display: none;" class="tooltip hasArrow">Geben Sie Ihren Suchbegriff in das Suchfeld ein. Klicken Sie auf das Lupensymbol oder drücken Sie die Eingabetaste. <a href="https://www.deutsche-digitale-bibliothek.de/content/help/search-simple"> Hilfe zur einfachen Suche </a><div class="arrow"></div></div>
              </form> -->
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
