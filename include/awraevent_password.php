<?php

declare(strict_types=1);

/**
 * Password handling for GoEvent schema (tbl_user.password, admin.password — text).
 * Supports legacy plaintext (goevent.sql seed) and password_hash() (bcrypt/argon2).
 */

if (!function_exists('awraevent_password_is_modern_hash')) {
  function awraevent_password_is_modern_hash(string $stored): bool {
    if ($stored === '') {
      return false;
    }
    return strncmp($stored, '$2y$', 4) === 0
      || strncmp($stored, '$2a$', 4) === 0
      || strncmp($stored, '$2b$', 4) === 0
      || strncmp($stored, '$argon2', 7) === 0;
  }
}

if (!function_exists('awraevent_password_hash')) {
  function awraevent_password_hash(string $plain): string {
    return password_hash($plain, PASSWORD_DEFAULT);
  }
}

if (!function_exists('awraevent_password_verify')) {
  function awraevent_password_verify(string $plain, string $stored): bool {
    if ($stored === '') {
      return false;
    }
    if (awraevent_password_is_modern_hash($stored)) {
      return password_verify($plain, $stored);
    }
    return hash_equals($stored, $plain);
  }
}
