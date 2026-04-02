<?php

if (!function_exists('awraevent_default_timezone')) {
  function awraevent_default_timezone(): string {
    $e = getenv('APP_DEFAULT_TIMEZONE');
    return (is_string($e) && trim($e) !== '') ? trim($e) : 'Africa/Addis_Ababa';
  }
}

if (!function_exists('awraevent_default_currency')) {
  function awraevent_default_currency(): string {
    $e = getenv('APP_DEFAULT_CURRENCY');
    return (is_string($e) && trim($e) !== '') ? trim($e) : 'ETB';
  }
}

if (!function_exists('awraevent_apply_setting_defaults')) {
  /**
   * Ensures empty timezone/currency in tbl_setting row get Ethiopia-friendly defaults in memory.
   */
  function awraevent_apply_setting_defaults(?array &$set): void {
    if (!is_array($set)) {
      return;
    }
    if (!isset($set['timezone']) || trim((string) $set['timezone']) === '') {
      $set['timezone'] = awraevent_default_timezone();
    }
    if (!isset($set['currency']) || trim((string) $set['currency']) === '') {
      $set['currency'] = awraevent_default_currency();
    }
  }
}

if (!function_exists('awraevent_sanitize_timezone')) {
  function awraevent_sanitize_timezone(string $tz): string {
    static $allowed = null;
    if ($allowed === null) {
      $allowed = array_flip(DateTimeZone::listIdentifiers(DateTimeZone::ALL));
    }
    return isset($allowed[$tz]) ? $tz : awraevent_default_timezone();
  }
}

if (!function_exists('awraevent_sanitize_latlng')) {
  function awraevent_sanitize_latlng($value, bool $isLat): string {
    if ($value === null || $value === '') {
      return $isLat ? '9.032' : '38.7469';
    }
    if (is_string($value)) {
      $value = str_replace(',', '.', trim($value));
    }
    if (!is_numeric($value)) {
      return $isLat ? '9.032' : '38.7469';
    }
    $f = (float) $value;
    if ($isLat && ($f < -90.0 || $f > 90.0)) {
      return '9.032';
    }
    if (!$isLat && ($f < -180.0 || $f > 180.0)) {
      return '38.7469';
    }
    return (string) $f;
  }
}
