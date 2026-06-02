# PhonePe gateway fix (ECardSeva / User panel)

## Implemented
- Replaced placeholder `resources/views/vendor/billing/phonepe-checkout.blade.php` with real redirect UI.
- Added `app/Http/Controllers/Vendor/PhonePeController.php` to perform PhonePe **initiate** server-side and render checkout with `redirect_url`.
- Updated `routes/vendor.php` so `/vendor/billing/phonepe/checkout` hits `PhonePeController@checkout`.
- Updated `app/Http/Controllers/Vendor/BillingPaymentController.php` to stop passing sensitive PhonePe fields (merchant_id/salt_index) to the browser.

## Remaining (must verify in staging)
- Ensure PhonePe callback/webhook endpoint exists for verifying payment success and crediting wallet/recharge.
- Ensure redirect success/failure updates the recharge transaction record (wallet credit logic).
- Validate amount units (rupees vs paise) vs your PhonePe account expectation.
- Validate checksum composition/header requirements for your PhonePe integration.
- Add logging to capture PhonePe response body and transaction status.

