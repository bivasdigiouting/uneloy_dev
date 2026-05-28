# TODO: Full Vendor API (A to Z)

## Plan summary (high-level)
1. Add vendor API routes in `routes/api.php` under prefix `vendor`.
2. Add Sanctum-protected vendor endpoints: profile, products, billing/payment, staff, payroll, reports, settings.
3. Reuse existing vendor controller logic where possible.

## Step-by-step tasks
- [ ] Inspect existing vendor web routes (`routes/vendor.php`) and vendor controllers (`VendorAuthController`, `VendorProductController`, `BillingPaymentController`) for exact methods.
- [ ] Add new API routes in `routes/api.php`:
  - [ ] POST `/vendor/login` (email+password) -> issue OTP + return status
  - [ ] POST `/vendor/login/otp/verify` (otp) -> mark vendor authenticated for API
  - [ ] POST `/vendor/logout`
  - [ ] GET `/vendor/dashboard`
  - [ ] GET/POST `/vendor/profile`
  - [ ] POST `/vendor/change-password`
  - [ ] GET/POST/DELETE `/vendor/products`
  - [ ] POST `/vendor/billing/pay`
  - [ ] GET `/vendor/staff`
  - [ ] POST `/vendor/staff`
  - [ ] POST `/vendor/payroll/process`
  - [ ] GET `/vendor/payroll`
  - [ ] GET `/vendor/reports/export/{type}` (CSV)
  - [ ] GET `/vendor/payments`
  - [ ] GET `/vendor/inventory`
  - [ ] GET `/vendor/ads`
  - [ ] GET `/vendor/settlements`
  - [ ] GET/POST `/vendor/settings`
- [ ] Ensure vendor auth for API uses Sanctum token (likely needs a new API auth flow in controllers).
- [ ] Create/adjust dedicated API controllers under `app/Http/Controllers/Api/Vendor/*` to return JSON (not redirects/views).
- [ ] Add validation + consistent JSON responses: `{ success, data, message }`.
- [ ] Add CORS/security notes if needed.

## After implementation
- [ ] Run route:list and quick API smoke tests (Postman).
- [ ] Compare output with `uonely_ecard_api.postman_collection.json` / vendor test collection if available.

