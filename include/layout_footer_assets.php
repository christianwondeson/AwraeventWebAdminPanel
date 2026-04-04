<?php
/**
 * Core admin JS/CSS normally injected via tbl_validate.data on production.
 * When that script is skipped (e.g. local dev), load the same vendor bundle from disk.
 */
if (!function_exists('awraevent_asset_h')) {
  require_once __DIR__ . '/brand.php';
}
?>
<script src="<?php echo awraevent_asset_h('vendor/global/global.min.js'); ?>"></script>
<script src="<?php echo awraevent_asset_h('js/custom.min.js'); ?>"></script>
<script>window.AWRAEVENT_ADMIN_POST_URL=<?php echo json_encode(awraevent_asset_url('include/event.php')); ?>;</script>
<script src="<?php echo awraevent_asset_h('js/admin_post_handler.js'); ?>"></script>
