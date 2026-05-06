<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || !array_key_exists('uid', $data) || !isset($data['cid']) || trim((string) $data['cid']) === '') {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went wrong  try again !',
    'SearchData' => [],
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$uid = (int) $data['uid'];
$cidEsc = $event->real_escape_string(trim((string) $data['cid']));

$emptySponsore = [
  'sponsore_id' => '',
  'sponsore_img' => '',
  'sponsore_title' => '',
];

$v = [];
$baseWhere = 'status=1 AND (event_status IS NULL OR event_status <> \'Completed\')';

$eventlist = $event->query(
  "SELECT * FROM tbl_event WHERE {$baseWhere} AND cid REGEXP '[[:<:]]{$cidEsc}[[:>:]]' ORDER BY id DESC LIMIT 300"
);

while ($ev = $eventlist->fetch_assoc()) {
  $nav = [];
  $nav['event_id'] = $ev['id'];
  $nav['event_title'] = $ev['title'];
  $cover = trim((string) ($ev['cover_img'] ?? ''));
  $img = trim((string) ($ev['img'] ?? ''));
  $nav['event_img'] = $cover !== '' ? $cover : $img;
  $date = date_create((string) ($ev['sdate'] ?? ''));
  $nav['event_sdate'] = $date ? date_format($date, 'd F') : '';
  $nav['event_address'] = (string) ($ev['address'] ?? '');
  $lat = trim((string) ($ev['latitude'] ?? ''));
  $lng = trim((string) ($ev['longtitude'] ?? ''));
  $nav['latitude'] = $lat !== '' ? $lat : '0';
  $nav['longtitude'] = $lng !== '' ? $lng : '0';

  $nav['IS_BOOKMARK'] = $event->query(
    'SELECT 1 FROM tbl_fav WHERE uid=' . $uid . ' AND eid=' . (int) $ev['id'] . ' LIMIT 1'
  )->num_rows;

  $spon = $event->query(
    'SELECT * FROM tbl_sponsore WHERE eid=' . (int) $ev['id'] . ' AND status=1'
  );
  $s = [];
  while ($row = $spon->fetch_assoc()) {
    $s[] = [
      'sponsore_id' => $row['id'],
      'sponsore_img' => awraevent_media_url((string) ($row['img'] ?? '')),
      'sponsore_title' => (string) ($row['title'] ?? ''),
    ];
  }
  $nav['sponsore_list'] = isset($s[0]) ? $s[0] : $emptySponsore;

  $ulist = $event->query(
    'SELECT uid,eid FROM tbl_ticket WHERE eid=' . (int) $ev['id'] . ' GROUP BY uid'
  );
  $member = [];
  while ($rp = $ulist->fetch_assoc()) {
    $getpic = $event->query('SELECT * FROM tbl_user WHERE id=' . (int) $rp['uid'] . ' LIMIT 1')->fetch_assoc();
    if (is_array($getpic) && trim((string) ($getpic['pro_pic'] ?? '')) !== '') {
      $member[] = awraevent_media_url((string) $getpic['pro_pic']);
    }
  }
  $nav['member_list'] = $member;

  $ticket = $event->query(
    'SELECT SUM(`ticket_book`) AS books FROM tbl_type_price WHERE eid=' . (int) $ev['id'] . ''
  )->fetch_assoc();
  $nav['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);

  $v[] = $nav;
}

$returnArr = [
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => count($v) > 0 ? 'Search Data Get Successfully!' : 'Search Data Not Get!!',
  'SearchData' => $v,
];

echo json_encode($returnArr, JSON_UNESCAPED_UNICODE);
