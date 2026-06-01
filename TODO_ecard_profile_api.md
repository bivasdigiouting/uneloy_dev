# TODO: ECard Seva API - Profile + Transactions + Devices + Security + Account Info

## Step 1 — Payment enable/disable + shares breakdown (best-effort)
- Implemented endpoints (best-effort, inferred from `ecard_wallet_transactions`).
  - `GET /api/ecard/transactions/settings`
  - `POST /api/ecard/transactions/settings`
  - `GET /api/ecard/transactions/payment-shares?from=&to=`

## Step 2 — Extend Profile API response structure

- Update `ProfileController@getProfile()` to group personal vs business info.
- Keep backward compatibility (existing keys remain).

## Step 3 — Device permission API alignment
- Update `ProfileDetailController` response fields to match UI naming (allowed device count).
- Ensure logged-in devices response is consistent.

## Step 4 — Security APIs
- Add endpoints:
  - change password
  - biometric toggle
  - nfc toggle
  - alias for mpin update/change if needed
- Implement using existing schema if columns exist; otherwise store toggles in `theme_settings` JSON (best-effort, no migrations).

## Step 5 — Language / Refer & Earn / Help / About / Logout
- Implement endpoints (static or DB-backed if models exist):
  - `GET/POST /api/ecard/language`
  - `GET /api/ecard/refer-earn-code`
  - `GET /api/ecard/help`
  - `GET /api/ecard/support`
  - `GET /api/ecard/about`
  - logout already exists.

## Step 6 — Wiring & verification
- Update `routes/api.php` to register all new endpoints under `ecard` prefix with `auth:sanctum`.
- Run quick route check and minimal API smoke tests with Postman collection.

