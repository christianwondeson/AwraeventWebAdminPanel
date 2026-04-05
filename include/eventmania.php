<?php
require_once __DIR__ . '/eventconfig.php';
require_once __DIR__ . '/awraevent_password.php';
require_once __DIR__ . '/brand.php';
awraevent_normalize_webname($set);
$GLOBALS['event'] = $event;

class Eventmania {

  function eventlogin($username, $password, $tblname) {
    $e = $GLOBALS['event'];
    $u = $e->real_escape_string((string) $username);
    $t = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $tblname);
    if ($t === '') {
      return 0;
    }
    $res = $e->query("SELECT password FROM `{$t}` WHERE username='{$u}' LIMIT 1");
    if (!$res || $res->num_rows === 0) {
      return 0;
    }
    $row = $res->fetch_assoc();
    $stored = (string) ($row['password'] ?? '');
    if (!awraevent_password_verify((string) $password, $stored)) {
      return 0;
    }
    if (!awraevent_password_is_modern_hash($stored)) {
      $newh = $e->real_escape_string(awraevent_password_hash((string) $password));
      $e->query("UPDATE `{$t}` SET password='{$newh}' WHERE username='{$u}' LIMIT 1");
    }
    return 1;
  }

  private static function escape_insert_values(mysqli $e, array $data): string {
    $parts = [];
    foreach ($data as $v) {
      $parts[] = $e->real_escape_string((string) $v);
    }
    return implode("','", $parts);
  }

  function eventinsertdata($field, $data, $table) {
    $e = $GLOBALS['event'];
    $field_values = implode(',', $field);
    $data_values = self::escape_insert_values($e, $data);
    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    return $e->query($sql);
  }

  function eventinsertdata_id($field, $data, $table) {
    $e = $GLOBALS['event'];
    $field_values = implode(',', $field);
    $data_values = self::escape_insert_values($e, $data);
    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $e->query($sql);
    return $e->insert_id;
  }

  function eventinsertdata_Api($field, $data, $table) {
    $e = $GLOBALS['event'];
    $field_values = implode(',', $field);
    $data_values = self::escape_insert_values($e, $data);
    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    return $e->query($sql);
  }

  function eventinsertdata_Api_Id($field, $data, $table) {
    $e = $GLOBALS['event'];
    $field_values = implode(',', $field);
    $data_values = self::escape_insert_values($e, $data);
    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $e->query($sql);
    return $e->insert_id;
  }

  private static function build_update_set(mysqli $e, array $field): array {
    $cols = [];
    foreach ($field as $key => $val) {
      if ($val === null) {
        continue;
      }
      $keySafe = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $key);
      if ($keySafe === '') {
        continue;
      }
      $cols[] = '`' . $keySafe . "` = '" . $e->real_escape_string((string) $val) . "'";
    }
    return $cols;
  }

  function eventupdateData($field, $table, $where) {
    $e = $GLOBALS['event'];
    $cols = self::build_update_set($e, $field);
    if (count($cols) === 0) {
      return false;
    }
    $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $cols) . ' ' . $where;
    return $e->query($sql);
  }

  function eventupdateData_Api($field, $table, $where) {
    return $this->eventupdateData($field, $table, $where);
  }

  function eventupdateData_single($field, $table, $where) {
    $query = "UPDATE $table SET $field";
    $sql = $query . ' ' . $where;
    return $GLOBALS['event']->query($sql);
  }

  function eventDeleteData($where, $table) {
    $sql = "Delete From $table $where";
    return $GLOBALS['event']->query($sql);
  }

  function eventDeleteData_Api($where, $table) {
    $sql = "Delete From $table $where";
    return $GLOBALS['event']->query($sql);
  }
}
