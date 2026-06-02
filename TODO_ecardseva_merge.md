# ECardSeva recharge merge plan (Ecard folder only)

## Files to merge
- `resources/views/ecardseva/recharge/mobile.blade.php`
- `resources/views/ecardseva/recharge/dth.blade.php`
- `resources/views/ecardseva/recharge/fastag.blade.php`
- `resources/views/ecardseva/recharge/bbps.blade.php`
- `resources/views/ecardseva/recharge/confirm.blade.php`
- `_partials/service-recharge-*.blade.php`

## Target locations
- Move into `resources/views/ecard/recharge/*` (or `resources/views/ecard/_partials/recharge/*`)
- Update `ECardSevaRechargeController` to render moved views.

## Routes
- Ensure routes still work after view move.

## Cleanup
- After successful routing + UI, delete `resources/views/ecardseva/recharge/*` folder.

