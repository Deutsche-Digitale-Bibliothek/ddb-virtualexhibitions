<?php
echo js_tag('vendor/jwplayer/jwplayer') . "\n";
echo js_tag('vendor/jwplayer/key') . "\n";
echo js_tag('options.min') . "\n";
echo '<script type="text/javascript">' . "\n";
echo 'window.litfassOptions = {';
  echo 'anchors: [' . $sectionAnchors . '], ';
  echo 'sectionsColor: [' . $sectionColors . ']';
echo '};';
echo '</script>'. "\n";
echo js_tag('bundle.min');
?>

<!-- Piwik -->
<script type="text/javascript">
var _paq = _paq || [];

_paq.push(['setVisitorCookieTimeout', '604800']);
_paq.push(['setSessionCookieTimeout', '0']);
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);

(function() {
var u=(("https:" == document.location.protocol) ? "https" : "http") + "://report.deutsche-digitale-bibliothek.de/";
_paq.push(["setTrackerUrl", u+"piwik.php"]);
_paq.push(["setSiteId", "5"]);
var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
})();
</script>
<noscript><img src="https://report.deutsche-digitale-bibliothek.de/piwik.php?idsite=5&amp;rec=1" style="border:0" alt="" /></noscript>
<!-- End Piwik Code -->
