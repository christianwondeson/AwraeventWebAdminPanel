<?php
/**
 * Server-side verify for AfroMessage OTP (Flutter calls this when verificationId is set).
 * POST JSON: code, mobile, verificationId and/or verification_id
 */
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/afromessage_sms.php';

header('Content-Type: application/json; charset=utf-8');

function awraevent_e_verify_json_out(array $payload): void {
  $flags = JSON_UNESCAPED_UNICODE;
  if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
    $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
  }
  $j = json_encode($payload, $flags);
  echo ($j !== false) ? $j : '{"ResponseCode":"500","Result":"false","ResponseMsg":"JSON encode failed"}';
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
  awraevent_e_verify_json_out([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Invalid request',
  ]);
  exit;
}

$code = trim((string) ($input['code'] ?? ''));
$mobile = trim((string) ($input['mobile'] ?? ''));
$vid = trim((string) ($input['verificationId'] ?? $input['verification_id'] ?? ''));

if ($code === '' || $mobile === '' || $vid === '') {
  awraevent_e_verify_json_out([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Missing code, mobile, or verification id',
  ]);
  exit;
}

if (!awraevent_sms_type_is_afro((string) ($set['sms_type'] ?? ''))) {
  awraevent_e_verify_json_out([
    'ResponseCode' => '400',
    'Result' => 'false',
    'ResponseMsg' => 'AfroMessage verification is only used when SMS type is set to AfroMessage.',
  ]);
  exit;
}

$token = awraevent_afro_api_token($set);
if ($token === '') {
  awraevent_e_verify_json_out([
    'ResponseCode' => '500',
    'Result' => 'false',
    'ResponseMsg' => 'AfroMessage token not configured.',
  ]);
  exit;
}

if (!function_exists('curl_init')) {
  awraevent_e_verify_json_out([
    'ResponseCode' => '500',
    'Result' => 'false',
    'ResponseMsg' => 'PHP cURL is not enabled on this server. Install php-curl and restart php-fpm.',
  ]);
  exit;
}

$e164 = awraevent_sms_e164($mobile);
$vr = awraevent_afro_verify_code($token, $e164, $vid, $code);
if (!empty($vr['ok'])) {
  awraevent_e_verify_json_out([
    'ResponseCode' => '200',
    'Result' => 'true',
    'ResponseMsg' => 'Verified successfully!',
  ]);
  exit;
}

awraevent_e_verify_json_out([
  'ResponseCode' => '401',
  'Result' => 'false',
  'ResponseMsg' => $vr['message'] ?? 'Invalid or expired code',
]);
