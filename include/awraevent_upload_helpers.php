<?php

declare(strict_types=1);

/**
 * Upload dirs + validation for include/event.php (admin AJAX).
 */

if (!function_exists('awraevent_ensure_upload_dir')) {
  function awraevent_ensure_upload_dir(string $absDir): bool {
    if (is_dir($absDir)) {
      return is_writable($absDir);
    }
    if (!@mkdir($absDir, 0775, true)) {
      return false;
    }
    return is_writable($absDir);
  }
}

if (!function_exists('awraevent_upload_ext_from_name')) {
  function awraevent_upload_ext_from_name(string $name): string {
    $name = strtolower(trim($name));
    $ext = $name !== '' ? (string) pathinfo($name, PATHINFO_EXTENSION) : '';
    $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?? '';
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    if ($ext !== '' && in_array($ext, $allowed, true)) {
      return $ext;
    }
    return 'jpg';
  }
}

if (!function_exists('awraevent_require_upload')) {
  /**
   * @param array<string, mixed> $file One $_FILES entry
   */
  function awraevent_require_upload(array $file, string $label): void {
    if (!isset($file['error'])) {
      throw new InvalidArgumentException('Missing upload: ' . $label);
    }
    $err = (int) $file['error'];
    if ($err === UPLOAD_ERR_NO_FILE) {
      throw new InvalidArgumentException('No file uploaded: ' . $label);
    }
    if ($err !== UPLOAD_ERR_OK) {
      $msg = 'Upload failed (' . $label . '): code ' . $err;
      if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        $msg .= ' (file too large — raise upload_max_filesize / post_max_size on the server)';
      }
      throw new InvalidArgumentException($msg);
    }
    $tmp = $file['tmp_name'] ?? '';
    if ($tmp === '' || !is_uploaded_file($tmp)) {
      throw new InvalidArgumentException('Invalid temp file: ' . $label);
    }
  }
}
