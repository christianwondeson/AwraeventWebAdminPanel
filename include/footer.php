<?php
require_once __DIR__ . '/brand.php';

if (!isset($validate)) {
  require_once __DIR__ . '/eventconfig.php';
}

// Prefer bundled JS from disk unless explicitly opting into legacy Envato-only footer (see brand.php).
$useDiskFooter = awraevent_is_local_request()
  || awraevent_skip_envato_footer_script()
  || !awraevent_use_envato_footer_only();

if (!$useDiskFooter && !empty($validate['data'])) {
  echo $validate['data'];
} else {
  require __DIR__ . '/layout_footer_assets.php';
}
