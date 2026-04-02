<?php
require_once __DIR__ . '/brand.php';

if (!isset($validate)) {
  require_once __DIR__ . '/eventconfig.php';
}

$useDiskFooter = awraevent_is_local_request() || awraevent_skip_envato_footer_script();

if ($useDiskFooter) {
  require __DIR__ . '/layout_footer_assets.php';
} elseif (!empty($validate['data'])) {
  echo $validate['data'];
} else {
  require __DIR__ . '/layout_footer_assets.php';
}
