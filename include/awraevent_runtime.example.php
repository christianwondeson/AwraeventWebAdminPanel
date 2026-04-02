<?php
/**
 * Server-only overrides (optional).
 *
 * ON THE VPS: copy this file to the same folder as:
 *   include/awraevent_runtime.php
 * That filename is gitignored — it will not be overwritten by git pull.
 *
 * eventconfig.php loads it automatically at startup (before DB/session logic).
 */
declare(strict_types=1);

// Exact name (no typo): AWRAEVENT_SKIP_ENVATO_SCRIPT
// Value must be the string "1".
// Effect: footer no longer echoes tbl_validate.data (Envato / purchase-code JS),
//         and validate_domain.php redirects to index.php (normal login).
putenv('AWRAEVENT_SKIP_ENVATO_SCRIPT=1');

// Uncomment if you prefer to set DB here instead of php-fpm / .env:
// putenv('DB_HOST=127.0.0.1');
// putenv('DB_USER=your_user');
// putenv('DB_PASS=your_pass');
// putenv('DB_NAME=your_db');
// putenv('DB_PORT=3306');
