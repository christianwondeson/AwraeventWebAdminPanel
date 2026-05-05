<?php

/**
 * Mobile normalization for eapi login / register / mobile_check (same rules everywhere).
 */

function awraevent_eapi_mobile_candidates(array $data): array {
  $raw = trim((string) ($data['mobile'] ?? ''));
  $out = [];
  if ($raw !== '') {
    $out[] = $raw;
  }
  $digits = preg_replace('/\D+/', '', $raw);
  if ($digits === '') {
    return array_values(array_unique(array_filter($out, static function ($v) {
      return $v !== null && $v !== '';
    })));
  }

  $out[] = $digits;
  if (str_starts_with($digits, '0')) {
    $out[] = ltrim($digits, '0');
  }
  if (str_starts_with($digits, '251') && strlen($digits) >= 12) {
    $nat = substr($digits, 3);
    $out[] = ltrim($nat, '0');
    $out[] = $nat;
  }
  $ccode = isset($data['ccode']) ? trim((string) $data['ccode']) : '';
  if ($ccode !== '') {
    $ccDigits = preg_replace('/\D+/', '', $ccode);
    if ($ccDigits !== '' && str_starts_with($digits, $ccDigits)) {
      $national = substr($digits, strlen($ccDigits));
      $national = ltrim($national, '0');
      if ($national !== '') {
        $out[] = $national;
        $out[] = '+' . $ccDigits . $national;
        $out[] = $ccDigits . $national;
      }
    }
  }

  $nationalNoZero = $digits;
  if (str_starts_with($nationalNoZero, '251') && strlen($nationalNoZero) >= 12) {
    $nationalNoZero = ltrim(substr($nationalNoZero, 3), '0');
  } else {
    $nationalNoZero = ltrim($nationalNoZero, '0');
  }
  if ($nationalNoZero !== '' && preg_match('/^9\d{8,9}$/', $nationalNoZero)) {
    $out[] = $nationalNoZero;
    $out[] = '0' . $nationalNoZero;
  }

  $out = array_values(array_unique(array_filter($out, static function ($v) {
    return $v !== null && $v !== '';
  })));
  return $out;
}

/**
 * Canonical national digits for tbl_user.mobile on registration (no leading 0, no 251 prefix).
 */
function awraevent_eapi_mobile_canonical_storage(array $data): string {
  $raw = trim((string) ($data['mobile'] ?? ''));
  $digits = preg_replace('/\D+/', '', $raw);
  if ($digits === '') {
    return '';
  }
  if (str_starts_with($digits, '251') && strlen($digits) >= 12) {
    $digits = substr($digits, 3);
  }
  $ccode = isset($data['ccode']) ? preg_replace('/\D+/', '', trim((string) $data['ccode'])) : '';
  if ($ccode !== '' && str_starts_with($digits, $ccode)) {
    $digits = substr($digits, strlen($ccode));
  }
  return ltrim($digits, '0');
}
