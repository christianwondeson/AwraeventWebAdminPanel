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

// Legacy: use ONLY tbl_validate.data for footer JS (not recommended on VPS). Default is bundled scripts from disk.
// putenv('AWRAEVENT_USE_ENVATO_FOOTER_ONLY=1');

// Payment gateways shown in the mobile app (eapi/e_paymentgateway.php).
// Comma-separated tbl_payment_list.id values. Omit or leave empty = all published methods.
// Addis Ababa / international card & wallet: usually PayPal (1) + Stripe (2) — confirm IDs in Payment Management.
// putenv('AWRAEVENT_PAYMENT_GATEWAY_IDS=1,2');

// AfroMessage (Settings → SMS type = AfroMessage). Prefer token in env, not only in the database.
// putenv('AFROMESSAGE_API_TOKEN=your_bearer_token');
// putenv('AFROMESSAGE_FROM='); // optional identifier / short code id (omit if unsure; do not put Msg91 template id here)
// putenv('AFROMESSAGE_SENDER='); // optional verified sender name
// putenv('AFROMESSAGE_OTP_PREFIX=Awra Event — your verification code: '); // SMS text before the code (Afro pr=)
// putenv('AFROMESSAGE_OTP_SUFFIX= . Do not share this code.'); // SMS text after the code (Afro ps=)
// putenv('AFROMESSAGE_RETURN_OTP=0'); // optional: do not return otp in msg_otp.php JSON
