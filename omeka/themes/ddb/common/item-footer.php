        </div><!-- end content -->

    </div><!-- end wrap -->


    <!--[if lt IE 9]>
  <div class="footer container" role="contentinfo">
<![endif]-->
<footer class="container">
    <div class="row">
        <h1 class="invisible-but-readable">Website-Fußzeile</h1>
        <div class="span12 legal">
            <div class="inner">
                <ul>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/datenschutzerklaerung/">Datenschutz</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/impressum/">Impressum</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/sitemap/">Sitemap</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/presse-medien/">Presse</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/downloads/">Downloads</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/user/newsletter/">Newsletter</a></li>
                    <li><a href="https://www.deutsche-digitale-bibliothek.de/content/kontakt/">Kontakt</a></li>
                </ul>
                <div class="social-icons pull-right">
                    Folgen:
                    <a target="_blank" href="https://facebook.com/ddbkultur" class="facebook-icon">Facebook</a>
                    <a target="_blank" href="https://twitter.com/ddbkultur" class="twitter-icon">Twitter</a>
                </div>
            </div>
        </div>
    </div>
    <div id="footer-text">
        <?php echo get_theme_option('Footer Text'); ?>
        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
        <p><?php echo $copyright; ?></p>
    <?php endif; ?>
    <!-- <p><?php echo __('Proudly powered by <a href="http://omeka.org">Omeka</a>.'); ?></p> -->
</div>
<?php fire_plugin_hook('public_footer'); ?>
</footer>
<!--[if lt IE 9]>
  </div>
<![endif]-->
<!-- end footer -->
<script type="text/javascript">
    if (typeof variable === 'undefined') {
        de = {};
        de.ddb = {};
        de.ddb.next = {};
    }
</script>
<?php echo js_tag('searchCookie'); ?>
<script type="text/javascript">

var GinaConfig = {server: {server_name: '<?php echo $_SERVER['SERVER_NAME']; ?>'}};

$(window).on('load', function () {

  $('#cookie-notice').each(function () {
    var cookieBar = $(this),
    p = cookieBar.find('p'),
    closeButton = cookieBar.find('.close');

    var cookie = de.ddb.next.search.readCookie("cb_cookie_notice");

    window.setTimeout(function(){
      if(cookie!=1){
        cookieBar.fadeIn('fast');
        document.cookie = 'cb_cookie_notice=1';
      }
    },300);


    closeButton.on('click', function (evt) {
      evt.preventDefault();

      cookieBar.removeClass('visible');
      cookieBar.fadeOut('fast');

      return false;
    });

  });
});
</script>
<?php $currentTheme = Theme::getTheme('ddb'); ?>

<script type="text/javascript" src="<?php echo $currentTheme->getAssetPath(); ?>/javascripts/imgmapinfo.min.js"></script>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://report.deutsche-digitale-bibliothek.de/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "5"]);
    _paq.push(['setVisitorCookieTimeout', '604800']);
    _paq.push(['setSessionCookieTimeout', '0']);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><img src="https://report.deutsche-digitale-bibliothek.de/piwik.php?idsite=5&amp;rec=1" style="border:0" alt="" /></noscript>
<!-- End Piwik Code -->
</body>
</html>
