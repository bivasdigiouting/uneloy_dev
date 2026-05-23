<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Check and add missing columns only if they don't exist
            if (! Schema::hasColumn('vendors', 'business_registration_category')) {
                $table->string('business_registration_category')->nullable()->after('business_name');
            }
            if (! Schema::hasColumn('vendors', 'gmail_id')) {
                $table->string('gmail_id')->nullable()->after('business_registration_category');
            }
            if (! Schema::hasColumn('vendors', 'business_gst_no')) {
                $table->string('business_gst_no')->nullable()->after('gmail_id');
            }
            if (! Schema::hasColumn('vendors', 'business_full_address')) {
                $table->text('business_full_address')->nullable()->after('business_gst_no');
            }
            if (! Schema::hasColumn('vendors', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('business_full_address');
            }
            if (! Schema::hasColumn('vendors', 'contact_person_designation')) {
                $table->string('contact_person_designation')->nullable()->after('contact_person');
            }
            if (! Schema::hasColumn('vendors', 'facility')) {
                $table->text('facility')->nullable()->after('contact_person_designation');
            }
            if (! Schema::hasColumn('vendors', 'about_us')) {
                $table->text('about_us')->nullable()->after('facility');
            }
            if (! Schema::hasColumn('vendors', 'business_location')) {
                $table->string('business_location')->nullable()->after('about_us');
            }
            if (! Schema::hasColumn('vendors', 'product_categories')) {
                $table->json('product_categories')->nullable()->after('business_location');
            }
            if (! Schema::hasColumn('vendors', 'mobile_country_code')) {
                $table->string('mobile_country_code', 5)->default('+91')->after('product_categories');
            }
            if (! Schema::hasColumn('vendors', 'mobile_no')) {
                $table->string('mobile_no')->nullable()->after('mobile_country_code');
            }
            if (! Schema::hasColumn('vendors', 'whatsapp_country_code')) {
                $table->string('whatsapp_country_code', 5)->default('+91')->after('mobile_no');
            }
            if (! Schema::hasColumn('vendors', 'whatsapp_no')) {
                $table->string('whatsapp_no')->nullable()->after('whatsapp_country_code');
            }

            // Bank Details
            if (! Schema::hasColumn('vendors', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('whatsapp_no');
            }
            if (! Schema::hasColumn('vendors', 'branch_name')) {
                $table->string('branch_name')->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('vendors', 'account_holder_name')) {
                $table->string('account_holder_name')->nullable()->after('branch_name');
            }
            if (! Schema::hasColumn('vendors', 'account_no')) {
                $table->string('account_no')->nullable()->after('account_holder_name');
            }
            if (! Schema::hasColumn('vendors', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable()->after('account_no');
            }
            if (! Schema::hasColumn('vendors', 'pan_no')) {
                $table->string('pan_no')->nullable()->after('ifsc_code');
            }
            if (! Schema::hasColumn('vendors', 'aadhar_no')) {
                $table->string('aadhar_no')->nullable()->after('pan_no');
            }
            if (! Schema::hasColumn('vendors', 'upi_no')) {
                $table->string('upi_no')->nullable()->after('aadhar_no');
            }

            // Personal Details
            if (! Schema::hasColumn('vendors', 'vendor_type')) {
                $table->string('vendor_type')->nullable()->after('upi_no');
            }
            if (! Schema::hasColumn('vendors', 'first_name')) {
                $table->string('first_name')->nullable()->after('vendor_type');
            }
            if (! Schema::hasColumn('vendors', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('vendors', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (! Schema::hasColumn('vendors', 'fathers_name')) {
                $table->string('fathers_name')->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('vendors', 'mothers_name')) {
                $table->string('mothers_name')->nullable()->after('fathers_name');
            }
            if (! Schema::hasColumn('vendors', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('mothers_name');
            }
            if (! Schema::hasColumn('vendors', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('blood_group');
            }
            if (! Schema::hasColumn('vendors', 'gender')) {
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('vendors', 'marital_status')) {
                $table->enum('marital_status', ['Single', 'Married', 'Others'])->nullable()->after('gender');
            }

            // Contact Details
            if (! Schema::hasColumn('vendors', 'current_address')) {
                $table->text('current_address')->nullable()->after('marital_status');
            }
            if (! Schema::hasColumn('vendors', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('current_address');
            }
            if (! Schema::hasColumn('vendors', 'nationality')) {
                $table->string('nationality')->default('Indian')->after('permanent_address');
            }
            if (! Schema::hasColumn('vendors', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable()->after('nationality');
            }
            if (! Schema::hasColumn('vendors', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('state_id');
            }
            if (! Schema::hasColumn('vendors', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('district_id');
            }
            if (! Schema::hasColumn('vendors', 'pincode')) {
                $table->string('pincode', 6)->nullable()->after('city_id');
            }
            if (! Schema::hasColumn('vendors', 'contact_mobile_country_code')) {
                $table->string('contact_mobile_country_code', 5)->default('+91')->after('pincode');
            }
            if (! Schema::hasColumn('vendors', 'contact_mobile_no')) {
                $table->string('contact_mobile_no')->nullable()->after('contact_mobile_country_code');
            }
            if (! Schema::hasColumn('vendors', 'contact_whatsapp_country_code')) {
                $table->string('contact_whatsapp_country_code', 5)->default('+91')->after('contact_mobile_no');
            }
            if (! Schema::hasColumn('vendors', 'contact_whatsapp_no')) {
                $table->string('contact_whatsapp_no')->nullable()->after('contact_whatsapp_country_code');
            }
            if (! Schema::hasColumn('vendors', 'contact_gmail_id')) {
                $table->string('contact_gmail_id')->nullable()->after('contact_whatsapp_no');
            }
            if (! Schema::hasColumn('vendors', 'current_live_location')) {
                $table->text('current_live_location')->nullable()->after('contact_gmail_id');
            }

            // Education & Qualification Details
            if (! Schema::hasColumn('vendors', 'last_qualification')) {
                $table->string('last_qualification')->nullable()->after('current_live_location');
            }
            if (! Schema::hasColumn('vendors', 'work_type')) {
                $table->string('work_type')->nullable()->after('last_qualification');
            }
            if (! Schema::hasColumn('vendors', 'work_experience')) {
                $table->string('work_experience')->nullable()->after('work_type');
            }

            // Terms & Conditions
            if (! Schema::hasColumn('vendors', 'terms_accepted')) {
                $table->boolean('terms_accepted')->default(false)->after('work_experience');
            }
        });

        // Add foreign key constraints if they don't exist
        if (Schema::hasColumn('vendors', 'state_id') && Schema::hasTable('states')) {
            try {
                Schema::table('vendors', function (Blueprint $table) {
                    $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
                });
            } catch (Exception $e) {
                // Foreign key might already exist
            }
        }

        if (Schema::hasColumn('vendors', 'district_id') && Schema::hasTable('districts')) {
            try {
                Schema::table('vendors', function (Blueprint $table) {
                    $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
                });
            } catch (Exception $e) {
                // Foreign key might already exist
            }
        }

        if (Schema::hasColumn('vendors', 'city_id') && Schema::hasTable('cities')) {
            try {
                Schema::table('vendors', function (Blueprint $table) {
                    $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
                });
            } catch (Exception $e) {
                // Foreign key might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Drop foreign key constraints first if they exist
            try {
                $table->dropForeign(['state_id']);
            } catch (Exception $e) {
            }
            try {
                $table->dropForeign(['district_id']);
            } catch (Exception $e) {
            }
            try {
                $table->dropForeign(['city_id']);
            } catch (Exception $e) {
            }

            // Drop columns if they exist
            $columnsToCheck = [
                'business_registration_category', 'gmail_id', 'business_gst_no', 'business_full_address',
                'contact_person', 'contact_person_designation', 'facility', 'about_us', 'business_location',
                'product_categories', 'mobile_country_code', 'mobile_no', 'whatsapp_country_code', 'whatsapp_no',
                'bank_name', 'branch_name', 'account_holder_name', 'account_no', 'ifsc_code', 'pan_no',
                'aadhar_no', 'upi_no', 'vendor_type', 'first_name', 'middle_name', 'last_name',
                'fathers_name', 'mothers_name', 'blood_group', 'date_of_birth', 'gender', 'marital_status',
                'current_address', 'permanent_address', 'nationality', 'state_id', 'district_id', 'city_id',
                'pincode', 'contact_mobile_country_code', 'contact_mobile_no', 'contact_whatsapp_country_code',
                'contact_whatsapp_no', 'contact_gmail_id', 'current_live_location', 'last_qualification',
                'work_type', 'work_experience', 'terms_accepted',
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('vendors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
