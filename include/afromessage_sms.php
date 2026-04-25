<?php
/**
 * AfroMessage (api.afromessage.com) – challenge/verify for OTP. Used when tbl_setting.sms_type contains "afro" (case-insensitive).
 *
 * Secret: set AFROMESSAGE_API_TOKEN in PHP environment, or place the Bearer token in tbl_setting.auth_key when using Afro.
 * Optional: AFROMESSAGE_FROM (identifier), AFROMESSAGE_SENDER (sender ID), AFROMESSAGE_RETURN_OTP=0 to omit code from API JSON.
 */

if (!function_exists('awraevent_sms_e164')) {
  /** Normalize to +digits for AfroMessage. */
  function awraevent_sms_e164(string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') {
      return $raw;
    }
    return '+' . $digits;
  }
}

if (!function_exists('awraevent_sms_type_is_afro')) {
  function awraevent_sms_type_is_afro(?string $smsType): bool {
    if ($smsType === null || $smsType === '') {
      return false;
    }
    return stripos($smsType, 'afro') !== false;
  }
}

if (!function_exists('awraevent_afro_api_token')) {
  /** @param array $set tbl_setting row */
  function awraevent_afro_api_token(array $set): string {
    $env = getenv('AFROMESSAGE_API_TOKEN');
    if (is_string($env) && $env !== '') {
      return $env;
    }
    return trim((string) ($set['auth_key'] ?? ''));
  }
}

if (!function_exists('awraevent_afro_from_id')) {
  function awraevent_afro_from_id(array $set): string {
    $env = getenv('AFROMESSAGE_FROM');
    if (is_string($env) && $env !== '') {
      return $env;
    }
    return trim((string) ($set['otp_id'] ?? ''));
  }
}

if (!function_exists('awraevent_afro_sender_id')) {
  function awraevent_afro_sender_id(array $set): string {
    $env = getenv('AFROMESSAGE_SENDER');
    if (is_string($env) && $env !== '') {
      return $env;
    }
    return trim((string) (getenv('AFROMESSAGE_SENDER_NAME') ?: ''));
  }
}

if (!function_exists('awraevent_afro_error_message_from_body')) {
  /** Human-readable message when acknowledge is not success. */
  function awraevent_afro_error_message_from_body(?array $data): string {
    if (!is_array($data) || !isset($data['response'])) {
      return 'AfroMessage request failed';
    }
    $r = $data['response'];
    if (is_string($r) && $r !== '') {
      return $r;
    }
    if (!is_array($r)) {
      return 'AfroMessage request failed';
    }
    if (!empty($r['errors']) && is_array($r['errors'])) {
      return implode(' ', array_map('strval', $r['errors']));
    }
    if (isset($r['message']) && (string) $r['message'] !== '') {
      return (string) $r['message'];
    }
    return 'AfroMessage request failed';
  }
}

if (!function_exists('awraevent_afro_http_get_json')) {
  /**
   * @return array{ok:bool,http_code:int,body?:array,raw?:string,error?:string}
   */
  function awraevent_afro_http_get_json(string $url, string $bearer): array {
    if (!function_exists('curl_init')) {
      return [
        'ok' => false,
        'http_code' => 0,
        'error' => 'PHP cURL is not enabled. Install php-curl for your PHP version (e.g. sudo apt install php8.2-curl) and restart php-fpm.',
      ];
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $bearer,
        'Accept: application/json',
      ],
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false) {
      return ['ok' => false, 'http_code' => $code, 'error' => $err !== '' ? $err : 'curl failed'];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
      $preview = is_string($raw) ? substr(preg_replace('/\s+/', ' ', $raw), 0, 200) : '';
      return [
        'ok' => false,
        'http_code' => $code,
        'raw' => $raw,
        'error' => 'Invalid JSON from AfroMessage (HTTP ' . $code . '). Preview: ' . $preview,
      ];
    }
    $ack = isset($data['acknowledge']) ? strtolower((string) $data['acknowledge']) : '';
    return ['ok' => $code === 200 && $ack === 'success', 'http_code' => $code, 'body' => $data, 'raw' => $raw];
  }
}

if (!function_exists('awraevent_afro_send_challenge')) {
  /**
   * Send OTP via /api/challenge (GET).
   *
   * @return array{ok:bool,message?:string,verificationId?:string,code?:string,raw?:array}
   */
  function awraevent_afro_send_challenge(
    string $bearer,
    string $toE164,
    string $fromId = '',
    string $sender = '',
    int $len = 6,
    int $ttlSeconds = 600
  ): array {
    $q = [
      'to' => $toE164,
      'len' => $len,
      't' => 0,
      'ttl' => $ttlSeconds,
      'pr' => '',
      'ps' => '',
      'sb' => 0,
      'sa' => 0,
    ];
    if ($fromId !== '') {
      $q['from'] = $fromId;
    }
    if ($sender !== '') {
      $q['sender'] = $sender;
    }

    $url = 'https://api.afromessage.com/api/challenge?' . http_build_query($q, '', '&', PHP_QUERY_RFC3986);
    $res = awraevent_afro_http_get_json($url, $bearer);
    if (!$res['ok']) {
      $errMsg = $res['error'] ?? 'AfroMessage request failed';
      if (isset($res['body']) && is_array($res['body'])) {
        $fromBody = awraevent_afro_error_message_from_body($res['body']);
        if ($fromBody !== 'AfroMessage request failed') {
          $errMsg = $fromBody;
        }
      }
      return [
        'ok' => false,
        'message' => $errMsg,
        'raw' => $res['body'] ?? null,
      ];
    }
    $b = $res['body'] ?? [];
    $resp = is_array($b['response'] ?? null) ? $b['response'] : [];
    $vid = (string) ($resp['verificationId'] ?? $resp['verification_id'] ?? '');
    $code = (string) ($resp['code'] ?? '');
    return [
      'ok' => $vid !== '',
      'message' => (string) ($resp['status'] ?? 'OK'),
      'verificationId' => $vid,
      'code' => $code,
      'raw' => $b,
    ];
  }
}

if (!function_exists('awraevent_afro_verify_code')) {
  /**
   * @return array{ok:bool,message?:string,body?:array|null}
   */
  function awraevent_afro_verify_code(string $bearer, string $toE164, string $verificationId, string $code): array {
    $q = [
      'to' => $toE164,
      'vc' => $verificationId,
      'code' => $code,
    ];
    $url = 'https://api.afromessage.com/api/verify?' . http_build_query($q, '', '&', PHP_QUERY_RFC3986);
    $res = awraevent_afro_http_get_json($url, $bearer);
    if (!empty($res['ok']) && is_array($res['body'] ?? null)) {
      $resp = $res['body']['response'] ?? null;
      if (is_array($resp) || (is_string($resp) && $resp !== '')) {
        return ['ok' => true, 'message' => 'Verified', 'body' => is_array($resp) ? $resp : null];
      }
    }
    $msg = isset($res['error']) ? (string) $res['error'] : 'Verification failed';
    if (is_array($res['body'] ?? null)) {
      $fromBody = awraevent_afro_error_message_from_body($res['body']);
      if ($fromBody !== 'AfroMessage request failed') {
        $msg = $fromBody;
      }
    }
    return ['ok' => false, 'message' => $msg, 'body' => $res['body'] ?? null];
  }
}
