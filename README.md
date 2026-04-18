# Awra Event — Web Admin Panel

PHP + MySQL admin dashboard for the GoEvent / Awra Event booking stack. It serves the **browser admin UI**, **`eapi/` REST-style endpoints** used by the mobile apps, and payment return URLs (Stripe, PayPal, etc.).

---

## Requirements

- **PHP** 8.0+ with extensions: `mysqli`, `curl`, `json`, `mbstring`, `openssl` (match your theme / gateway needs).
- **MySQL** or **MariaDB** with the application schema (import the SQL bundle supplied with your Codecanyon purchase if this repo does not ship full dumps).
- **Web server**: Apache or Nginx + PHP-FPM, with `mod_rewrite` or equivalent if you use clean URLs.

---

## First-time setup

1. **Clone or upload** the project to your web root or a subdirectory (e.g. `/var/www/awraevent` or `https://example.com/admin/`).

2. **Database**
   - Create a database and user.
   - Import the schema from your product package.

3. **Local config (not in git)**  
   Copy the example and edit credentials:

   ```bash
   cp include/eventconfig.example.php include/eventconfig.php
   ```

   Adjust `DB_*` (or use `getenv('DB_HOST')` etc. as in the example).  
   `include/eventconfig.php` is listed in `.gitignore` so secrets are not committed.

4. **Optional server overrides**  
   Copy `include/awraevent_runtime.example.php` → `include/awraevent_runtime.php` on the server (also gitignored). `eventconfig.php` loads it automatically when present.

5. **Permissions**  
   Ensure PHP can write upload directories your features use (e.g. `images/event/`, `images/gallery/`, etc.), typically `www-data` ownership or group write as appropriate.

6. **Log in**  
   Open `index.php`, sign in with an admin row from your DB (`tbl_user` / admin table per your schema).

---

## Environment variables (PHP-FPM / Apache / `awraevent_runtime.php`)

| Variable | Purpose |
|----------|---------|
| `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT` | Database connection (if used by `eventconfig.php`). |
| `PUBLIC_BASE_URL` | Full site URL (`https://yourdomain.com`) when auto-detection behind proxies is wrong. |
| `APP_ASSET_BASE_PATH` | Prefix for static assets if the app lives in a subdirectory or uses a CDN prefix (no trailing slash). |
| `APP_DEFAULT_TIMEZONE` | Fallback timezone (default `Africa/Addis_Ababa`). |
| `APP_DEFAULT_CURRENCY` | Fallback currency code (default `ETB`). |
| `APP_DISPLAY_NAME` | Override empty/generic site name from `tbl_setting`. |
| `AWRAEVENT_SKIP_ENVATO_SCRIPT` | Set to `1` after licensing: skips injecting `tbl_validate.data` as the **only** footer script and adjusts `validate_domain.php`. Recommended on production. |
| `AWRAEVENT_USE_ENVATO_FOOTER_ONLY` | Set to `1` **only** for legacy behavior: footer JS comes **only** from `tbl_validate.data`. **Default (unset)** loads bundled `vendor/global.min.js` + `js/custom.min.js` from disk (recommended). |
| `AWRAEVENT_PAYMENT_GATEWAY_IDS` | Comma-separated `tbl_payment_list.id` values returned by `eapi/e_paymentgateway.php` (e.g. `1,2` for PayPal + Stripe). Empty = all published methods. |

---

## CI/CD (GitHub Actions)

Workflow: `.github/workflows/deploy.yml`

- **Trigger:** push to `master`.
- **Behavior:** SSH to your VPS, `cd` to `VPS_APP_DIR`, then `git fetch` + `git reset --hard origin/master`, optional `composer install`, optional PHP-FPM reload.

**Repository secrets (GitHub → Settings → Secrets and variables → Actions):**

- `VPS_HOST`, `VPS_USER`, `VPS_SSH_KEY`, `VPS_APP_DIR` (absolute path to the git clone on the server).

The server directory must be a clone of **this** repo with `origin` pointing at GitHub.

---

## Troubleshooting

More detail: [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md).

### Dashboard pages stuck on “Loading” (preloader never clears)

**Cause:** The theme hides `#preloader` in `js/custom.min.js` on the browser **`window` `load`** event. If core JS never loads or an old setup used **only** `tbl_validate.data` for scripts, production can sit on the loader forever while local (localhost) used disk bundles.

**What we changed**

- By default, **`include/footer.php` loads scripts from disk** (`include/layout_footer_assets.php`). Legacy “Envato-only footer” is opt-in via `AWRAEVENT_USE_ENVATO_FOOTER_ONLY=1`.
- A **safety script** in `layout_footer_assets.php` fades the preloader after `load` or after **12 seconds** maximum.

**What you should do on the VPS**

1. Deploy the current code.
2. Set `AWRAEVENT_SKIP_ENVATO_SCRIPT=1` in `awraevent_runtime.php` or PHP-FPM env (recommended for licensed installs).
3. Optionally clear stale footer HTML in MySQL: set `tbl_validate.data` to empty if you no longer need the injected blob (see `sql/clear_envato_validate_script.sql` if present).
4. Hard-refresh the browser (Ctrl+Shift+R). Check DevTools → **Network** for 404s on `/vendor/global/global.min.js` or `/js/custom.min.js`.

### Wrong CSS/JS paths (subdirectory install)

If the panel lives under e.g. `https://example.com/admin/`, set `APP_ASSET_BASE_PATH` to that base (e.g. `https://example.com/admin` or `/admin`) so `awraevent_asset_url()` resolves `vendor/*` and `js/*` correctly.

### Settings page feels slow

`setting.php` builds a full HTML `<select>` of all PHP timezones, which is large but normally fine. If the server is very constrained, consider raising PHP `max_execution_time` / memory for admin only (rarely needed).

---

## Payments

Gateways are rows in **`tbl_payment_list`**. Published rows (`status = 1`) are exposed to the app unless restricted by `AWRAEVENT_PAYMENT_GATEWAY_IDS`.

PHP folders such as `stripe/`, `paypal/`, `Khalti/`, `paystack/`, etc. map to **fixed IDs** in the database—confirm IDs in **Payment Management** before changing production data.

---

## Security notes

- Never commit `include/eventconfig.php` or `include/awraevent_runtime.php` with real secrets.
- Keep upload directories outside web execution if your server allows, or block script execution in `images/` via server config.
- Use HTTPS in production; set `PUBLIC_BASE_URL` if termination is at a reverse proxy.

---

## Project layout (short)

| Path | Role |
|------|------|
| `include/eventconfig.php` | DB bootstrap, session, `$set` from `tbl_setting`. |
| `include/event.php` | Backend actions / JSON for many admin forms. |
| `include/top.php` / `sidebar.php` | Authenticated layout shell. |
| `eapi/*.php` | JSON API consumed by mobile clients. |
| `js/admin_post_handler.js` | AJAX posts from `.content-body` forms to `include/event.php`. |

---

## License

Awra Event

Use according to your Codecanyon / Envato license. Envato verification flows are optional once you set `AWRAEVENT_SKIP_ENVATO_SCRIPT=1` and rely on bundled assets as documented above.
