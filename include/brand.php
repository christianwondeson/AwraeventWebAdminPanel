<?php

if (!function_exists('awraevent_media_url')) {
  /**
   * Absolute URL for DB-stored paths (e.g. images/event/123.jpg) for mobile apps.
   * Set PUBLIC_BASE_URL on the server (https://admin.example.com) when behind a proxy.
   */
  function awraevent_media_url(?string $path): string {
    if ($path === null) {
      return '';
    }
    $path = trim($path);
    if ($path === '') {
      return '';
    }
    if (preg_match('#^https?://#i', $path)) {
      return $path;
    }
    return rtrim(awraevent_public_base_url(), '/') . '/' . ltrim(str_replace('\\', '/', $path), '/');
  }
}

if (!function_exists('awraevent_media_urls_list')) {
  /** @param list<string> $paths */
  function awraevent_media_urls_list(array $paths): array {
    $out = [];
    foreach ($paths as $p) {
      $out[] = awraevent_media_url(is_string($p) ? $p : '');
    }
    return $out;
  }
}

if (!function_exists('awraevent_user_for_api')) {
  /**
   * Strip password and normalize media URLs for JSON (Flutter).
   */
  function awraevent_user_for_api(array $row): array {
    unset($row['password']);
    if (isset($row['pro_pic']) && $row['pro_pic'] !== null && (string) $row['pro_pic'] !== '') {
      $row['pro_pic'] = awraevent_media_url((string) $row['pro_pic']);
    }
    return $row;
  }
}

if (!function_exists('awraevent_public_base_url')) {
  function awraevent_public_base_url() {
    $fromEnv = getenv('PUBLIC_BASE_URL');
    if (is_string($fromEnv) && $fromEnv !== '' && preg_match('#^https?://#i', $fromEnv)) {
      return rtrim($fromEnv, '/');
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
  }
}

if (!function_exists('awraevent_asset_url')) {
  /**
   * URL path for static assets (vendor/*, css/*, js/*) that works in a subdirectory install.
   * Override with APP_ASSET_BASE_PATH (no trailing slash), e.g. /admin or https://cdn.example.com/app
   */
  function awraevent_asset_url(string $path): string {
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $env = getenv('APP_ASSET_BASE_PATH');
    if (is_string($env) && $env !== '') {
      return rtrim($env, '/') . '/' . $path;
    }
    $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $dir = str_replace('\\', '/', dirname($script));
    if ($dir === '/' || $dir === '' || $dir === '.') {
      return '/' . $path;
    }
    return rtrim($dir, '/') . '/' . $path;
  }
}

if (!function_exists('awraevent_asset_h')) {
  function awraevent_asset_h(string $path): string {
    return htmlspecialchars(awraevent_asset_url($path), ENT_QUOTES, 'UTF-8');
  }
}

if (!function_exists('awraevent_force_domain_script')) {
  function awraevent_force_domain_script() {
    return getenv('AWRAEVENT_FORCE_DOMAIN_SCRIPT') === '1';
  }
}

if (!function_exists('awraevent_skip_envato_footer_script')) {
  /**
   * When true, footer.php will not echo tbl_validate.data (Codecanyon/Envato domain + purchase-code UI).
   * Set AWRAEVENT_SKIP_ENVATO_SCRIPT=1 in PHP-FPM / server env after you have licensed the product.
   * Alternatively clear tbl_validate.data in MySQL to the same effect.
   */
  function awraevent_skip_envato_footer_script(): bool {
    return getenv('AWRAEVENT_SKIP_ENVATO_SCRIPT') === '1';
  }
}

if (!function_exists('awraevent_use_envato_footer_only')) {
  /**
   * When AWRAEVENT_USE_ENVATO_FOOTER_ONLY=1, footer.php uses only tbl_validate.data for scripts (legacy).
   * Default (unset): scripts load from disk (vendor/global.min.js, js/custom.min.js) so the admin preloader
   * and menus work even if the DB blob is empty, blocked, or outdated (common on production VPS).
   */
  function awraevent_use_envato_footer_only(): bool {
    return getenv('AWRAEVENT_USE_ENVATO_FOOTER_ONLY') === '1';
  }
}

if (!function_exists('awraevent_is_local_request')) {
  function awraevent_is_local_request() {
    if (awraevent_force_domain_script()) {
      return false;
    }
    if (getenv('AWRAEVENT_SKIP_DOMAIN_SCRIPT') === '1') {
      return true;
    }
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '') {
      return false;
    }
    if (preg_match('/^(127\.0\.0\.1|localhost|\[::1\])(:\d+)?$/i', $host)) {
      return true;
    }
    if (preg_match('/^192\.168\.\d{1,3}\.\d{1,3}(:\d+)?$/', $host)) {
      return true;
    }
    if (preg_match('/^10\.\d{1,3}\.\d{1,3}\.\d{1,3}(:\d+)?$/', $host)) {
      return true;
    }
    return false;
  }
}

if (!function_exists('awraevent_normalize_webname')) {
  function awraevent_normalize_webname(array &$set) {
    $envName = getenv('APP_DISPLAY_NAME');
    if (is_string($envName) && trim($envName) !== '') {
      $set['webname'] = trim($envName);
      return;
    }
    $default = 'Awra Event';
    if (!isset($set['webname']) || trim((string) $set['webname']) === '') {
      $set['webname'] = $default;
      return;
    }
    $raw = trim((string) $set['webname']);
    $alnum = strtolower(preg_replace('/[^a-z0-9]/i', '', $raw));
    if (strpos($alnum, 'awra') === false && strpos($alnum, 'goevent') !== false) {
      $set['webname'] = $default;
      return;
    }
    if (preg_match('/^go\s*event\b/iu', $raw)) {
      $set['webname'] = $default;
      return;
    }
    if ($alnum === 'goevent' || $alnum === 'goeventadmin') {
      $set['webname'] = $default;
      return;
    }
    if (preg_match('/^goevent\d*$/', $alnum)) {
      $set['webname'] = $default;
      return;
    }
    $norm = strtolower(preg_replace('/\s+/u', ' ', $raw));
    if ($norm === 'go event' || $norm === 'goevent') {
      $set['webname'] = $default;
    }
  }
}
