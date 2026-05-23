<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$state = App\Models\State::first();
$district = App\Models\District::first();
$city = App\Models\City::first();

if (!$state || !$district || !$city) {
    echo "Missing location data\n";
    exit;
}

$request = Illuminate\Http\Request::create('/admin/vendors', 'POST', [
    'business_registration_category' => 'Private Limited',
    'business_name' => 'Test Business',
    'mobile_country_code' => '+91',
    'mobile_no' => '9998887776',
    'whatsapp_country_code' => '+91',
    'whatsapp_no' => '9998887776',
    'gmail_id' => 'testvendor1@example.com',
    'business_full_address' => 'Test Address 123',
    'business_gst_no' => '22AAAAA0000A1Z5',
    'contact_person' => 'John Doe',
    'contact_person_designation' => 'CEO',
    'facility' => 'Test Facility',
    'about_us' => 'Info about us',
    'business_location' => 'Some location',
    'product_categories' => ['Medical', 'Something Else'],
    'bank_name' => 'State Bank of India',
    'branch_name' => 'Test Branch',
    'account_holder_name' => 'John Doe',
    'account_no' => '1234567890123',
    'ifsc_code' => 'SBIN0001234',
    'pan_no' => 'ABCDE1234F',
    'aadhar_no' => '123412341234',
    'upi_no' => 'testupi@sbi',
    'vendor_type' => 'Some New Type',
    'first_name' => 'John',
    'middle_name' => 'M',
    'last_name' => 'Doe',
    'fathers_name' => 'Jane Doe',
    'mothers_name' => 'Mary Doe',
    'blood_group' => 'A+',
    'date_of_birth' => '1990-01-01',
    'gender' => 'Male',
    'marital_status' => 'Single',
    'current_address' => 'Current address',
    'permanent_address' => 'Permanent address',
    'nationality' => 'Indian',
    'state_id' => $state->id,
    'district_id' => $district->id,
    'city_id' => $city->id,
    'pincode' => '123456',
    'contact_mobile_country_code' => '+91',
    'contact_mobile_no' => '9998887775',
    'contact_whatsapp_country_code' => '+91',
    'contact_whatsapp_no' => '9998887775',
    'contact_gmail_id' => 'contact1@example.com',
    'current_live_location' => 'Location',
    'last_qualification' => 'B.Tech',
    'work_type' => 'IT',
    'work_experience' => '5 years',
    'terms_accepted' => '1',
    'status' => 'active',
]);

$controller = app()->make(App\Http\Controllers\Admin\VendorController::class);
try {
    $response = app()->call([$controller, 'store'], ['request' => $request]);
    $out = [];
    if ($response instanceof Illuminate\Http\RedirectResponse) {
        $session = app('session.store');
        if ($session->has('errors')) $out['validation'] = $session->get('errors')->all();
        if ($session->has('error')) $out['error'] = $session->get('error');
    }

    $vendor = App\Models\Vendor::where('gmail_id', 'testvendor1@example.com')->first();
    if ($vendor) {
        $out['result'] = 'success';
        $vendor->delete(); // cleanup
    } else {
        $out['result'] = 'failed to find in DB';
    }
    file_put_contents('test_vendor_out.json', json_encode($out, JSON_PRETTY_PRINT));
} catch (\Exception $e) {
    file_put_contents('test_vendor_out.json', json_encode(['exception' => $e->getMessage()]));
}
