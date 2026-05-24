# TODO - ECardSeva Registration Changes

## Step 1: Identify & remove fields
- Update `resources/views/ecard/registration/create.blade.php`
  - Remove phone no input field
  - Remove live location map input field
  - Make blood group optional
  - Add `cadr_number` input (optional, up to 16 digits)

## Step 2: Backend validation & payload (Portal)
- Update `app/Http/Controllers/ECardPortalRegistrationController.php`
  - Change `blood_group` rule to nullable
  - Remove validation rules for `phone_no` and `live_location_map`
  - Add validation rule for `cadr_number`
  - Remove `phone_no` and `live_location_map` from payload
  - Add `cadr_number` to payload

## Step 3: Backend validation & payload (Admin)
- Update `app/Http/Controllers/Admin/ECardRegistrationController.php`
  - Ensure admin store validation does not require phone_no/live_location_map
  - Add `cadr_number` validation
  - Ensure `cadr_number` is saved (fillable/payload via create/update)

## Step 4: Add DB column
- Create migration to add `cadr_number` to `ecard_registrations` table

## Step 5: Model fillable
- Update `app/Models/ECardRegistration.php` to add `cadr_number` in `$fillable`

## Step 6: Admin create view (if exists)
- If `resources/views/admin/ecard-registrations/create.blade.php` exists, update it similarly

## Step 7: Run migrations & basic checks
- Run: `php artisan migrate`
- Manually submit portal + admin registration forms to confirm

