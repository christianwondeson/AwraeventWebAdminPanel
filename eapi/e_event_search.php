<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || !array_key_exists('uid', $data)) {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went wrong  try again !',
    'SearchData' => [],
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$uid = (int) $data['uid'];
$titleRaw = isset($data['title']) ? trim((string) $data['title']) : '';

/** Same shape as e_home_data empty sponsor row (Flutter-safe). */
$emptySponsore = [
  'sponsore_id' => '',
  'sponsore_img' => '',
  'sponsore_title' => '',
];

$v = [];

$baseWhere = 'status=1 AND (event_status IS NULL OR event_status <> \'Completed\')';
$wild = ['', 'all', 'ALL', 'All', '*', '%'];
$useAll = in_array($titleRaw, $wild, true);

if ($useAll) {
  $eventlist = $event->query(
    'SELECT * FROM tbl_event WHERE ' . $baseWhere . ' ORDER BY id DESC LIMIT 300'
  );
} else {
  $esc = $event->real_escape_string($titleRaw);
  $eventlist = $event->query(
    "SELECT * FROM tbl_event WHERE {$baseWhere} AND title LIKE '%{$esc}%' ORDER BY id DESC LIMIT 300"
  );
}

while ($ev = $eventlist->fetch_assoc()) {
  $nav = [];
  $nav['event_id'] = $ev['id'];
  $nav['event_title'] = $ev['title'];
  // Relative path so Flutter can do Config.base_url + event_img (same as legacy stock app).
  $cover = trim((string) ($ev['cover_img'] ?? ''));
  $img = trim((string) ($ev['img'] ?? ''));
  $nav['event_img'] = $cover !== '' ? $cover : $img;
  $date = date_create((string) ($ev['sdate'] ?? ''));
  $nav['event_sdate'] = $date ? date_format($date, 'd F') : '';
  $nav['event_address'] = (string) ($ev['address'] ?? '');
  // Map tab expects these keys (see SearchPage markers / PageView camera).
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

// Valid request with zero rows: still 200 so the app can render an empty map/list without treating it as a hard error.
$returnArr = [
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => count($v) > 0 ? 'Search Data Get Successfully!' : 'Search Data Not Get!!',
  'SearchData' => $v,
];

echo json_encode($returnArr, JSON_UNESCAPED_UNICODE);
