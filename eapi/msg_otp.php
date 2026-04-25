<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/afromessage_sms.php';

header('Content-Type: application/json; charset=utf-8');

function awraevent_msg_otp_json_out(array $payload): void {
  $flags = JSON_UNESCAPED_UNICODE;
  if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
    $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
  }
  $j = json_encode($payload, $flags);
  if ($j === false) {
    $j = json_encode([
      'ResponseCode' => '500',
      'Result' => 'false',
      'ResponseMsg' => 'JSON encode failed: ' . json_last_error_msg(),
    ], JSON_UNESCAPED_UNICODE);
    if ($j === false) {
      $j = '{"ResponseCode":"500","Result":"false","ResponseMsg":"internal_error"}';
    }
  }
  echo $j;
}

$input = json_decode(file_get_contents('php://input'), true);
$mobile = is_array($input) ? trim((string) ($input['mobile'] ?? '')) : '';

if ($mobile === '') {
  awraevent_msg_otp_json_out([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went Wrong!',
  ]);
  exit;
}

if (!function_exists('curl_init')) {
  awraevent_msg_otp_json_out([
    'ResponseCode' => '500',
    'Result' => 'false',
    'ResponseMsg' => 'PHP cURL is not enabled on this server. Install php-curl (e.g. sudo apt install php8.2-curl) and restart php-fpm, then retry.',
  ]);
  exit;
}

$smsType = (string) ($set['sms_type'] ?? '');

if (awraevent_sms_type_is_afro($smsType)) {
  $token = awraevent_afro_api_token($set);
  if ($token === '') {
    awraevent_msg_otp_json_out([
      'ResponseCode' => '500',
      'Result' => 'false',
      'ResponseMsg' => 'AfroMessage: set AFROMESSAGE_API_TOKEN in the server environment or put the API token in Auth Key (Settings) when using AfroMessage.',
    ]);
    exit;
  }

  $e164 = awraevent_sms_e164($mobile);
  $from = awraevent_afro_from_id($set);
  $sender = awraevent_afro_sender_id($set);
  $out = awraevent_afro_send_challenge($token, $e164, $from, $sender, 6, 600);

  if (!$out['ok']) {
    $msg = $out['message'] ?? 'Could not send SMS';
    awraevent_msg_otp_json_out([
      'ResponseCode' => '401',
      'Result' => 'false',
      'ResponseMsg' => $msg,
    ]);
    exit;
  }

  $returnOtp = getenv('AFROMESSAGE_RETURN_OTP');
  $includeOtp = $returnOtp !== '0' && strcasecmp((string) $returnOtp, 'false') !== 0;

  $returnArr = [
    'ResponseCode' => '200',
    'Result' => 'true',
    'ResponseMsg' => 'OTP sent successfully!!',
    'verificationId' => $out['verificationId'],
    'verification_id' => $out['verificationId'],
  ];
  if ($includeOtp && ($out['code'] ?? '') !== '') {
    $returnArr['otp'] = $out['code'];
  }

  awraevent_msg_otp_json_out($returnArr);
  exit;
}

// Msg91 (default)
$ch = curl_init();
$url = 'https://control.msg91.com/api/v5/otp?template_id=' . $set['otp_id'] . '&mobile=' . rawurlencode($mobile) . '&authkey=' . rawurlencode($set['auth_key']);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/JSON'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$otp = random_int(111111, 999999);
$data = json_encode(array('otp' => $otp));
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

$returnArr = array(
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'OTP sent successfully!!',
  'otp' => $otp,
);
awraevent_msg_otp_json_out($returnArr);
