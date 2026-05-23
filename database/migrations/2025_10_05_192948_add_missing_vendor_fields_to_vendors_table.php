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
            // Business Details
            if (! Schema::hasColumn('vendors', 'business_name')) {
                $table->string('business_name')->nullable()->after('vendor_name');
            }
            if (! Schema::hasColumn('vendors', 'business_type')) {
                $table->string('business_type')->nullable()->after('business_name');
            }
            if (! Schema::hasColumn('vendors', 'business_category')) {
                $table->string('business_category')->nullable()->after('business_type');
            }
            if (! Schema::hasColumn('vendors', 'business_address')) {
                $table->text('business_address')->nullable()->after('business_category');
            }
            if (! Schema::hasColumn('vendors', 'business_state_id')) {
                $table->unsignedBigInteger('business_state_id')->nullable()->after('business_address');
            }
            if (! Schema::hasColumn('vendors', 'business_district_id')) {
                $table->unsignedBigInteger('business_district_id')->nullable()->after('business_state_id');
            }
            if (! Schema::hasColumn('vendors', 'business_city_id')) {
                $table->unsignedBigInteger('business_city_id')->nullable()->after('business_district_id');
            }
            if (! Schema::hasColumn('vendors', 'business_pincode')) {
                $table->string('business_pincode', 10)->nullable()->after('business_city_id');
            }
            if (! Schema::hasColumn('vendors', 'gst_no')) {
                $table->string('gst_no', 15)->nullable()->after('business_pincode');
            }
            if (! Schema::hasColumn('vendors', 'pan_no')) {
                $table->string('pan_no', 10)->nullable()->after('gst_no');
            }
            if (! Schema::hasColumn('vendors', 'aadhar_no')) {
                $table->string('aadhar_no', 12)->nullable()->after('pan_no');
            }

            // Bank Details
            if (! Schema::hasColumn('vendors', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('aadhar_no');
            }
            if (! Schema::hasColumn('vendors', 'account_holder_name')) {
                $table->string('account_holder_name')->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('vendors', 'account_no')) {
                $table->string('account_no')->nullable()->after('account_holder_name');
            }
            if (! Schema::hasColumn('vendors', 'ifsc_code')) {
                $table->string('ifsc_code', 11)->nullable()->after('account_no');
            }
            if (! Schema::hasColumn('vendors', 'branch_name')) {
                $table->string('branch_name')->nullable()->after('ifsc_code');
            }

            // Personal Details
            if (! Schema::hasColumn('vendors', 'first_name')) {
                $table->string('first_name')->nullable()->after('branch_name');
            }
            if (! Schema::hasColumn('vendors', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('vendors', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('vendors', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('vendors', 'marital_status')) {
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('vendors', 'personal_address')) {
                $table->text('personal_address')->nullable()->after('marital_status');
            }
            if (! Schema::hasColumn('vendors', 'personal_state_id')) {
                $table->unsignedBigInteger('personal_state_id')->nullable()->after('personal_address');
            }
            if (! Schema::hasColumn('vendors', 'personal_district_id')) {
                $table->unsignedBigInteger('personal_district_id')->nullable()->after('personal_state_id');
            }
            if (! Schema::hasColumn('vendors', 'personal_city_id')) {
                $table->unsignedBigInteger('personal_city_id')->nullable()->after('personal_district_id');
            }
            if (! Schema::hasColumn('vendors', 'personal_pincode')) {
                $table->string('personal_pincode', 10)->nullable()->after('personal_city_id');
            }

            // Contact Details
            if (! Schema::hasColumn('vendors', 'mobile_no')) {
                $table->string('mobile_no', 15)->nullable()->after('personal_pincode');
            }
            if (! Schema::hasColumn('vendors', 'gmail_id')) {
                $table->string('gmail_id')->nullable()->after('mobile_no');
            }
            if (! Schema::hasColumn('vendors', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable()->after('gmail_id');
            }
            if (! Schema::hasColumn('vendors', 'contact_mobile_no')) {
                $table->string('contact_mobile_no', 15)->nullable()->after('contact_person_name');
            }
            if (! Schema::hasColumn('vendors', 'contact_gmail_id')) {
                $table->string('contact_gmail_id')->nullable()->after('contact_mobile_no');
            }

            // Education Details
            if (! Schema::hasColumn('vendors', 'qualification')) {
                $table->string('qualification')->nullable()->after('contact_gmail_id');
            }
            if (! Schema::hasColumn('vendors', 'experience_years')) {
                $table->integer('experience_years')->nullable()->after('qualification');
            }
            if (! Schema::hasColumn('vendors', 'skills')) {
                $table->text('skills')->nullable()->after('experience_years');
            }

            // Terms and Conditions
            if (! Schema::hasColumn('vendors', 'terms_accepted')) {
                $table->boolean('terms_accepted')->default(false)->after('skills');
            }
        });

        // Add foreign key constraints only if they don't exist
        Schema::table('vendors', function (Blueprint $table) {
            // Check if foreign keys exist by trying to add them in a try-catch
            try {
                $table->foreign('business_state_id')->references('id')->on('states')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }

            try {
                $table->foreign('business_district_id')->references('id')->on('districts')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }

            try {
                $table->foreign('business_city_id')->references('id')->on('cities')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }

            try {
                $table->foreign('personal_state_id')->references('id')->on('states')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }

            try {
                $table->foreign('personal_district_id')->references('id')->on('districts')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }

            try {
                $table->foreign('personal_city_id')->references('id')->on('cities')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key already exists, ignore
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);

            // Drop columns
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'marital_status',
                'current_address',
                'permanent_address',
                'nationality',
                'state_id',
                'district_id',
                'city_id',
                'pincode',
                'contact_mobile_country_code',
                'contact_mobile_no',
                'contact_whatsapp_country_code',
                'contact_whatsapp_no',
                'contact_gmail_id',
                'current_live_location',
                'last_qualification',
                'work_type',
                'work_experience',
                'terms_accepted',
            ]);
        });
    }
};
