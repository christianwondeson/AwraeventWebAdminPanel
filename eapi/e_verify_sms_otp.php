<?php
/**
 * Server-side verify for AfroMessage OTP (Flutter calls this when verificationId is set).
 * POST JSON: code, mobile, verificationId and/or verification_id
 */
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/afromessage_sms.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Invalid request',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$code = trim((string) ($input['code'] ?? ''));
$mobile = trim((string) ($input['mobile'] ?? ''));
$vid = trim((string) ($input['verificationId'] ?? $input['verification_id'] ?? ''));

if ($code === '' || $mobile === '' || $vid === '') {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Missing code, mobile, or verification id',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

if (!awraevent_sms_type_is_afro((string) ($set['sms_type'] ?? ''))) {
  echo json_encode([
    'ResponseCode' => '400',
    'Result' => 'false',
    'ResponseMsg' => 'AfroMessage verification is only used when SMS type is set to AfroMessage.',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$token = awraevent_afro_api_token($set);
if ($token === '') {
  echo json_encode([
    'ResponseCode' => '500',
    'Result' => 'false',
    'ResponseMsg' => 'AfroMessage token not configured.',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$e164 = awraevent_sms_e164($mobile);
$vr = awraevent_afro_verify_code($token, $e164, $vid, $code);
if (!empty($vr['ok'])) {
  echo json_encode([
    'ResponseCode' => '200',
    'Result' => 'true',
    'ResponseMsg' => 'Verified successfully!',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

echo json_encode([
  'ResponseCode' => '401',
  'Result' => 'false',
  'ResponseMsg' => $vr['message'] ?? 'Invalid or expired code',
], JSON_UNESCAPED_UNICODE);
