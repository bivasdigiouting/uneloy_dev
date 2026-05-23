<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\\Models\\Model' => 'App\\Policies\\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Allow Super Admin to bypass all permission checks
        Gate::before(function ($user, $ability) {
            if (! method_exists($user, 'hasRole')) {
                return null;
            }
            // Check Super Admin role across common guards to avoid mismatches
            if ($user->hasRole('Super Admin') || $user->hasRole('Super Admin', 'admin') || $user->hasRole('Super Admin', 'web')) {
                return true;
            }

            return null;
        });
    }
}
