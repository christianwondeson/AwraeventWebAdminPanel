<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/afromessage_sms.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$mobile = is_array($input) ? trim((string) ($input['mobile'] ?? '')) : '';

if ($mobile === '') {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went Wrong!',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$smsType = (string) ($set['sms_type'] ?? '');

if (awraevent_sms_type_is_afro($smsType)) {
  $token = awraevent_afro_api_token($set);
  if ($token === '') {
    echo json_encode([
      'ResponseCode' => '500',
      'Result' => 'false',
      'ResponseMsg' => 'AfroMessage: set AFROMESSAGE_API_TOKEN in the server environment or put the API token in Auth Key (Settings) when using AfroMessage.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }

  $e164 = awraevent_sms_e164($mobile);
  $from = awraevent_afro_from_id($set);
  $sender = awraevent_afro_sender_id($set);
  $out = awraevent_afro_send_challenge($token, $e164, $from, $sender, 6, 600);

  if (!$out['ok']) {
    $msg = $out['message'] ?? 'Could not send SMS';
    echo json_encode([
      'ResponseCode' => '401',
      'Result' => 'false',
      'ResponseMsg' => $msg,
    ], JSON_UNESCAPED_UNICODE);
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

  echo json_encode($returnArr, JSON_UNESCAPED_UNICODE);
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
echo json_encode($returnArr, JSON_UNESCAPED_UNICODE);
