<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$mode = $argv[1] ?? 'user';

if ($mode === 'vendor') {
    echo "Creating demo vendor...\n";

    $vendorTypeValue = 'Self Vendor';
    try {
        $col = DB::selectOne("SHOW COLUMNS FROM `vendors` LIKE 'vendor_type'");
        if ($col && isset($col->Type) && is_string($col->Type) && str_starts_with($col->Type, 'enum(')) {
            preg_match_all("/'((?:\\\\'|[^'])*)'/", $col->Type, $m);
            $allowed = array_map('stripslashes', $m[1] ?? []);
            if (! empty($allowed)) {
                $vendorTypeValue = $allowed[0];
            }
        }
    } catch (Exception $e) {
    }

    $state = State::query()->first();
    $district = $state ? District::where('state_id', $state->id)->first() : null;
    $city = $district ? City::where('district_id', $district->id)->first() : null;

    if (! $state || ! $district || ! $city) {
        echo "Cannot create vendor: missing location data (states/districts/cities).\n";
        exit(1);
    }

    $category = ProductCategory::where('status', 'active')->first();
    $productCategories = $category ? [$category->name] : ['Medical'];

    $plainPassword = 'Demo@12345';

    $suffix = null;
    $gmail = null;
    $contactGmail = null;
    $mobile = null;
    $contactMobile = null;
    $pan = null;
    $aadhar = null;
    $upi = null;

    for ($i = 0; $i < 50; $i++) {
        $suffix = $i === 0 ? '' : (string) $i;
        $gmail = "demo.vendor{$suffix}@example.com";
        $contactGmail = "contact.vendor{$suffix}@example.com";
        $mobile = (string) (9000000001 + $i);
        $contactMobile = (string) (9000000101 + $i);
        $pan = 'DEMOV'.str_pad((string) $i, 4, '0', STR_PAD_LEFT).'A';
        $aadhar = str_pad((string) (123412341234 + $i), 12, '0', STR_PAD_LEFT);
        $upi = "demo.vendor{$suffix}@upi";

        $exists = Vendor::where('gmail_id', $gmail)->exists()
            || Vendor::where('contact_gmail_id', $contactGmail)->exists()
            || Vendor::where('mobile_no', $mobile)->exists()
            || Vendor::where('contact_mobile_no', $contactMobile)->exists()
            || Vendor::where('pan_no', $pan)->exists()
            || Vendor::where('aadhar_no', $aadhar)->exists();

        if (! $exists) {
            break;
        }
    }

    if (! $gmail) {
        echo "Cannot create vendor: unable to find free demo identifiers.\n";
        exit(1);
    }

    do {
        $vendorNumber = str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    } while (Vendor::where('vendor_number', $vendorNumber)->exists());

    $data = [
        'vendor_number' => $vendorNumber,
        'status' => 'active',
        'password' => Hash::make($plainPassword),
        'email_verified_at' => now(),
        'business_registration_category' => 'Proprietorship',
        'business_name' => "Demo Vendor Shop{$suffix}",
        'mobile_country_code' => '+91',
        'mobile_no' => $mobile,
        'whatsapp_country_code' => '+91',
        'whatsapp_no' => $mobile,
        'gmail_id' => $gmail,
        'business_full_address' => 'Demo Address',
        'business_gst_no' => null,
        'contact_person' => 'Demo Person',
        'contact_person_designation' => 'Owner',
        'facility' => 'Demo Facility',
        'about_us' => 'Demo vendor for testing vendor registration.',
        'business_location' => $city->city_name ?? 'Demo City',
        'product_categories' => $productCategories,
        'bank_name' => 'Demo Bank',
        'branch_name' => 'Main Branch',
        'account_holder_name' => 'Demo Vendor',
        'account_no' => '123456789012',
        'ifsc_code' => 'DEMO0000001',
        'pan_no' => $pan,
        'aadhar_no' => $aadhar,
        'upi_no' => $upi,
        'vendor_type' => $vendorTypeValue,
        'first_name' => 'Demo',
        'middle_name' => null,
        'last_name' => 'Vendor',
        'fathers_name' => 'Demo Father',
        'mothers_name' => 'Demo Mother',
        'blood_group' => 'O+',
        'date_of_birth' => '1990-01-01',
        'gender' => 'Male',
        'marital_status' => 'Single',
        'current_address' => 'Demo Current Address',
        'permanent_address' => 'Demo Permanent Address',
        'nationality' => 'Indian',
        'state_id' => $state->id,
        'district_id' => $district->id,
        'city_id' => $city->id,
        'pincode' => '700001',
        'contact_mobile_country_code' => '+91',
        'contact_mobile_no' => $contactMobile,
        'contact_whatsapp_country_code' => '+91',
        'contact_whatsapp_no' => $contactMobile,
        'contact_gmail_id' => $contactGmail,
        'current_live_location' => null,
        'last_qualification' => 'Graduate',
        'work_type' => 'Business',
        'work_experience' => '5 years',
        'terms_accepted' => true,
    ];

    $columns = Schema::getColumnListing('vendors');
    $data = array_intersect_key($data, array_flip($columns));

    try {
        $vendor = Vendor::create($data);
        echo "Vendor created successfully!\n";
        echo 'ID: '.$vendor->id."\n";
        echo 'Vendor Number: '.$vendor->vendor_number."\n";
        echo 'Business Name: '.$vendor->business_name."\n";
        echo 'Email: '.$vendor->gmail_id."\n";
        echo 'Password: '.$plainPassword."\n";
        echo 'Mobile: '.$vendor->mobile_no."\n";
        echo "Open admin vendors and search by email/mobile/vendor number.\n";
        exit(0);
    } catch (Exception $e) {
        echo 'Error creating vendor: '.$e->getMessage()."\n";
        exit(1);
    }
}

echo "Creating test user...\n";

// Check if user already exists
$existingUser = User::where('email', 'test@example.com')->orWhere('user_id', 'TEST123')->first();
if ($existingUser) {
    echo 'User already exists with ID: '.$existingUser->id."\n";
    echo 'Email: '.$existingUser->email."\n";
    echo 'User ID: '.$existingUser->user_id."\n";
} else {
    // Create a test user with user_id
    try {
        $user = new User;
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = Hash::make('password');
        $user->user_id = 'TEST123';
        $user->save();

        echo "User created successfully!\n";
        echo 'ID: '.$user->id."\n";
        echo 'Name: '.$user->name."\n";
        echo 'Email: '.$user->email."\n";
        echo 'User ID: '.$user->user_id."\n";

    } catch (Exception $e) {
        echo 'Error creating user: '.$e->getMessage()."\n";
    }
}

echo "\nTest user setup completed.\n";
