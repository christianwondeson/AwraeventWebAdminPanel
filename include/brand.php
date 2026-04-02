<?php

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
