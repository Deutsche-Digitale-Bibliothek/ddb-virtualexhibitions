<?php require __DIR__ . DIRECTORY_SEPARATOR . 'spa_zoom_hint.php';  ?>
<script type="text/javascript" src="<?php echo WEB_PLUGIN; ?>/X3d/views/shared/javascripts/x3dom.js"></script>
<?php
$userAgentIpad = (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') === false)? 'false' : 'true';

// echo js_tag('options.min') . "\n";
echo '<script type="text/javascript" src="' . url('exhibits/colorpalettesjs') . '" charset="utf-8"></script>';
echo '<script type="text/javascript">' . "\n";
echo 'window.litfassOptions = {';
    echo 'anchors: [' . $sectionAnchors . '], ';
    echo 'sectionsColor: [' . $sectionColors . '], ';
    echo 'palette: \'' . $colorpalette . '\', ';
    echo 'path_images: \'' . web_path_to('images') . '\', ';
    echo 'is_ipad: ' . $userAgentIpad . ', ';
    echo (file_exists(BASE_DIR . '/sw.js'))? 'has_sw: true' : 'has_sw: false';
echo '};';
echo '</script>'. "\n";
echo js_tag('bundle.min', 'javascripts', date("YmdHis", filemtime(dirname(__FILE__) . '/../javascripts/bundle.min.js')));
?>

<!-- Piwik -->
<?php
$matomoHost = 'report.deutsche-digitale-bibliothek.de';
if($_SERVER['HTTP_HOST'] == 'ausstellungen-q1.deutsche-digitale-bibliothek.de') {
  $matomoHost = 'report-t.deutsche-digitale-bibliothek.de';
}
?>
<script type="text/javascript">
var _paq = _paq || [];

_paq.push(['setVisitorCookieTimeout', '604800']);
_paq.push(['setSessionCookieTimeout', '0']);
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);

(function() {
  var u=(("https:" == document.location.protocol) ? "https" : "http") + "://<?php echo $matomoHost; ?>/";
  _paq.push(["setTrackerUrl", u+"piwik.php"]);
  _paq.push(["setSiteId", "5"]);
  var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
  g.type="text/javascript";
  g.defer=true;
  g.async=true;
  g.src=u+"piwik.js";
  s.parentNode.insertBefore(g,s);
  var currentUrl = location.href;
  window.addEventListener('hashchange', function () {
    _paq.push(['setReferrerUrl', currentUrl]);
    currentUrl = location.href;
    _paq.push(['setCustomUrl', currentUrl]);
    _paq.push(['setDocumentTitle', document.title + ' · ' + window.location.hash.substr(1)]);
    _paq.push(['setGenerationTimeMs', 0]);
    _paq.push(['trackPageView']);
  });
})();
</script>
<noscript><img src="https://<?php echo $matomoHost; ?>/piwik.php?idsite=5&amp;rec=1" style="border:0" alt="" /></noscript>
<!-- End Piwik Code -->
