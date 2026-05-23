<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ECardRegistration;
use App\Models\Registration;
use App\Models\WebsiteSettings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->ensureWebsiteSettingsTable();

        $settings = WebsiteSettings::query()->first() ?? new WebsiteSettings;
        $settings->maintenance_mode = false;
        $settings->maintenance_title = null;
        $settings->maintenance_message = null;
        $settings->save();

        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_maintenance_mode_blocks_public_but_allows_admin_login(): void
    {
        $this->ensureWebsiteSettingsTable();

        $settings = WebsiteSettings::query()->first() ?? new WebsiteSettings;
        $settings->maintenance_mode = true;
        $settings->maintenance_title = 'Planned Maintenance';
        $settings->maintenance_message = 'We will be back shortly.';
        $settings->save();

        $this->get('/')
            ->assertStatus(503)
            ->assertSeeText('Planned Maintenance')
            ->assertSeeText('We will be back shortly.');

        $this->get('/admin/login')->assertOk();
    }

    public function test_ecard_user_report_lists_self_registered_users_from_both_sources(): void
    {
        $this->withoutMiddleware();
        $this->ensureEcardRegistrationTables();

        $parent = ECardRegistration::query()->create([
            'parent_id' => null,
            'department_level' => 'state_level',
            'user_id' => 'UP12345678',
            'password' => bcrypt('secret'),
            'first_name' => 'Parent',
            'last_name' => 'User',
            'email_id' => 'parent@example.com',
            'mobile_no' => '9999999999',
            'state' => 'Test State',
            'district' => 'Test District',
            'city' => 'Test City',
            'status' => 'active',
        ]);

        $now = Carbon::parse('2025-01-01 12:00:00');

        $regChild = Registration::query()->create([
            'parent_id' => $parent->id,
            'department_level' => 'customer',
            'user_id' => 'CUST0001',
            'password' => bcrypt('secret'),
            'first_name' => 'Reg',
            'last_name' => 'Child',
            'email_id' => 'reg.child@example.com',
            'mobile_no' => '8888888888',
            'state' => 'Test State',
            'district' => 'Test District',
            'city' => 'Test City',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $ecardChild = ECardRegistration::query()->create([
            'parent_id' => $parent->id,
            'department_level' => 'district_level',
            'user_id' => 'EC0002',
            'password' => bcrypt('secret'),
            'first_name' => 'ECard',
            'last_name' => 'Child',
            'email_id' => 'ecard.child@example.com',
            'mobile_no' => '7777777777',
            'state' => 'Test State',
            'district' => 'Test District',
            'city' => 'Test City',
            'status' => 'active',
            'created_at' => $now->copy()->addDay(),
            'updated_at' => $now->copy()->addDay(),
        ]);

        $resp = $this->actingAs($parent, 'ecard')
            ->getJson('/ecard/users/report/data?draw=1&start=0&length=25');

        $resp->assertStatus(200);

        $json = $resp->json();
        $this->assertSame(2, (int) ($json['recordsFiltered'] ?? 0));
        $this->assertSame(2, (int) ($json['recordsTotal'] ?? 0));

        $ids = collect($json['data'] ?? [])->pluck('id')->values()->all();
        $this->assertContains('reg-'.$regChild->id, $ids);
        $this->assertContains('ecard-'.$ecardChild->id, $ids);

        $regPrintUrl = collect($json['data'] ?? [])->firstWhere('id', 'reg-'.$regChild->id)['print_url'] ?? null;
        $ecardPrintUrl = collect($json['data'] ?? [])->firstWhere('id', 'ecard-'.$ecardChild->id)['print_url'] ?? null;
        $this->assertNotEmpty($regPrintUrl);
        $this->assertNotEmpty($ecardPrintUrl);
        $regPrintPath = (string) (parse_url($regPrintUrl, PHP_URL_PATH) ?: $regPrintUrl);
        $ecardPrintPath = (string) (parse_url($ecardPrintUrl, PHP_URL_PATH) ?: $ecardPrintUrl);

        $this->assertNotNull(
            DB::table('registrations')->where('id', $regChild->id)->where('parent_id', $parent->id)->first()
        );
        $this->assertNotNull(
            DB::table('ecard_registrations')->where('id', $ecardChild->id)->where('parent_id', $parent->id)->first()
        );

        $this->actingAs($parent, 'ecard')
            ->get($regPrintPath)
            ->assertStatus(200);

        $this->actingAs($parent, 'ecard')
            ->get($ecardPrintPath)
            ->assertStatus(200);

        $selfPrintUrl = route('ecard.users.report.print', $parent->id);
        $selfPrintPath = (string) (parse_url($selfPrintUrl, PHP_URL_PATH) ?: $selfPrintUrl);
        $this->actingAs($parent, 'ecard')
            ->get($selfPrintPath)
            ->assertStatus(200);

        $this->actingAs($parent, 'ecard')
            ->get('/ecard/users/report/ecard/bulk?ids='.urlencode('reg-'.$regChild->id.','.'ecard-'.$ecardChild->id).'&autoprint=1')
            ->assertStatus(200);
    }

    private function ensureWebsiteSettingsTable(): void
    {
        if (Schema::hasTable('website_settings')) {
            return;
        }

        Schema::create('website_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_title')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->string('maintenance_title', 255)->nullable();
            $table->text('maintenance_message')->nullable();
            $table->timestamps();
        });
    }

    private function ensureEcardRegistrationTables(): void
    {
        if (! Schema::hasTable('ecard_registrations')) {
            Schema::create('ecard_registrations', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('department_level')->nullable();
                $table->string('user_id')->nullable();
                $table->string('password')->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email_id')->nullable();
                $table->string('gmail_id')->nullable();
                $table->string('mobile_no')->nullable();
                $table->string('state')->nullable();
                $table->string('district')->nullable();
                $table->string('city')->nullable();
                $table->string('status')->nullable();
                $table->string('profile_image')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('department_level')->nullable();
                $table->string('user_id')->nullable();
                $table->string('password')->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email_id')->nullable();
                $table->string('gmail_id')->nullable();
                $table->string('mobile_no')->nullable();
                $table->string('state')->nullable();
                $table->string('district')->nullable();
                $table->string('city')->nullable();
                $table->string('status')->nullable();
                $table->string('profile_image')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->timestamps();
            });
        }

        DB::table('registrations')->truncate();
        DB::table('ecard_registrations')->truncate();
    }
}
