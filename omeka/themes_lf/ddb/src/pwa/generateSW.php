<?php
$files = [
  'javascripts/vendor/jwplayer/jwplayer.html5.js',
  'images/favicon.ico',
  'images/menu_icon_3d_bg.svg',
  'images/menu_icon_3d.svg',
  'images/menu_icon_audio.svg',
  'images/menu_icon_home.svg',
  'images/menu_icon_img.svg',
  'images/menu_icon_legal.svg',
  'images/menu_icon_privacy.svg',
  'images/menu_icon_slider.svg',
  'images/menu_icon_team.svg',
  'images/menu_icon_text.svg',
  'images/menu_icon_video.svg',
  'images/1_dbb_siegel_de_service_rot_invert.png',
  'images/2_ddb_api_dt_rot.png',
  'images/3_DDB_Logo_2_s_pos_RGB_R_96dpi.png',
  'images/ddb-studio-logo-small.svg',
  'images/ddb-studio-logo-small-inverse.svg',
  'images/ddb-studio-logo-small.png',
  'images/logo.png',
  'images/licenses.png',
  'images/licenses_dark.png',
  'images/icons/icon_facebook_dark.svg',
  'images/icons/icon_facebook.svg',
  'images/icons/icon_next_page_left.svg',
  'images/icons/icon_next_page_right.svg',
  'images/icons/icon_pinterest_dark.svg',
  'images/icons/icon_pinterest.svg',
  'images/icons/icon_tumblr_dark.svg',
  'images/icons/icon_tumblr.svg',
  'images/icons/icon_twitter_dark.svg',
  'images/icons/icon_twitter.svg',
  'images/icons/x.svg',
  'images/icons/zoom/icon_zoom-hint_move_drag.svg',
  'images/icons/zoom/icon_zoom-hint_move_keys.svg',
  'images/icons/zoom/icon_zoom-hint_move_mouse.svg',
  'images/icons/zoom/icon_zoom-hint_quit_click.svg',
  'images/icons/zoom/icon_zoom-hint_quit_keys.svg',
  'images/icons/zoom/icon_zoom-hint_quit_mouse.svg',
  'images/icons/zoom/icon_zoom-hint_zoom_keys.svg',
  'images/icons/zoom/icon_zoom-hint_zoom_mouse.svg',
  'images/icons/zoom/icon_zoom-hint_zoom_pinch.svg',
  'plugins/X3d/views/shared/javascripts/x3dom.js',
];

$precache = '[' . "\n"
  . '    {"revision":null,"url":FALLBACK_IMAGE_URL},' . "\n"
  . '    {"revision":null,"url":self.registration.scope + "manifest"},' . "\n"
  . '    {"revision":"00000000000000000000000000000002","url":self.registration.scope},' . "\n"
  . '    {"revision":"00000000000000000000000000000001","url":self.registration.scope + "exhibits/colorpalettesjs"},' . "\n"
  . '    {"revision":null,"url":"themes/ddb/css/spa.min.css?v='
    . date("YmdHis", filemtime(__DIR__ . '/../../css/spa.min.css')) . '"},' . "\n"
  . '    {"revision":null,"url":"themes/ddb/javascripts/bundle.min.js?v='
    . date("YmdHis", filemtime(__DIR__ . '/../../javascripts/bundle.min.js')) . '"},' . "\n";

foreach ($files as $file) {
  $path = false;
  $pathPrefix = '';
  if (substr($file, 0, 11) === 'javascripts' || substr($file, 0, 6) === 'images') {
    $path = __DIR__ . '/../../' . $file;
    $pathPrefix = 'themes/ddb/';
  } elseif (substr($file, 0, 7) === 'plugins') {
    $path = __DIR__ . '/../../../../' . $file;
  }
  if ($path) {
    $precache .= '    {"revision":"' . md5_file($path) . '","url":"' . $pathPrefix . $file . '"},' . "\n";
  }
}
$precache .= '  ]';

$sw = file_get_contents(__DIR__ . '/sw.js');

$swPrecache = str_replace('self.__WB_MANIFEST', $precache, $sw);

file_put_contents(__DIR__ . '/../../javascripts/sw.js', $swPrecache);
