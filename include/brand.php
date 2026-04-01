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

if (!function_exists('awraevent_normalize_webname')) {
  function awraevent_normalize_webname(array &$set) {
    if (!isset($set['webname']) || trim((string) $set['webname']) === '') {
      $set['webname'] = 'Awra Event';
      return;
    }
    $norm = strtolower(preg_replace('/\s+/u', ' ', trim($set['webname'])));
    if ($norm === 'go event' || $norm === 'goevent') {
      $set['webname'] = 'Awra Event';
    }
  }
}
