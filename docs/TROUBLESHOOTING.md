# Troubleshooting (Awra Event Admin)

Main setup and deployment: see [README.md](../README.md).

## Admin UI stuck on full-screen “Loading” (spinner / dots)

### Symptom

After login, any page (including **Settings** / `setting.php`) shows only the theme preloader (`#preloader` in `include/top.php`). Locally on `localhost` the same build works.

### Why it happens

1. **Footer scripts**  
   The preloader is removed in `js/custom.min.js` when the browser fires **`window` `load`** and runs `ventic.load()`, which calls `$("#preloader").fadeOut(...)`.  
   If **jQuery / `custom.min.js` never load**, that never runs.

2. **Legacy Envato-only footer**  
   Older logic used **`tbl_validate.data`** as the *entire* footer output when the request was not “local” and `AWRAEVENT_SKIP_ENVATO_SCRIPT` was not set. If that database field is empty, points to blocked CDNs, or is outdated, production never gets the same JS bundle you get on localhost (where the app already preferred disk assets).

3. **Blocked or slow subresources**  
   The `load` event waits for stylesheets, images, and deferred scripts on the page. A **hanging** third-party asset can delay `load` (the safety timeout in `layout_footer_assets.php` mitigates “forever” stuck).

### What to verify

1. **Browser DevTools → Network**  
   - Reload while on a stuck page.  
   - Confirm **`vendor/global/global.min.js`** and **`js/custom.min.js`** return **200** (not 404 HTML).  
   - If they 404, fix **`APP_ASSET_BASE_PATH`** or your web root / subdirectory mapping (see README).

2. **Console errors**  
   Red errors before `load` can prevent theme code from running. Fix any reported 404 or MIME-type issues.

3. **Server environment**  
   - Deploy includes `include/layout_footer_assets.php` and the updated `include/footer.php` (default: load bundled JS from disk).  
   - Set **`AWRAEVENT_SKIP_ENVATO_SCRIPT=1`** on production after licensing (`include/awraevent_runtime.php` or PHP-FPM pool `env[]`).  
   - Optionally run `sql/clear_envato_validate_script.sql` so `tbl_validate.data` is not injecting conflicting markup.

4. **Legacy opt-in**  
   Only set **`AWRAEVENT_USE_ENVATO_FOOTER_ONLY=1`** if you intentionally want the database blob to replace the disk footer (not recommended on most VPS installs).

### Safety net in code

`include/layout_footer_assets.php` appends a small inline script that fades `#preloader` after **`load`** or after **12 seconds**, so the UI should recover even when `window.load` is very late.

## Settings page vs “General Information” row with a spinning gear icon

The heading *“General Information”* in `setting.php` uses a decorative **Font Awesome spinning icon** (`fa-spin`). That is not the full-page loader. If the **entire screen** is still the white preloader overlay, follow the section above—not this cosmetic icon.
