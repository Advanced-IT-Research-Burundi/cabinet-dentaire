<?php

namespace App\Providers;

 use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('is-dentiste', function ($user) {
            return $user->isDentiste();
        });

        Gate::define('is-secretaire', function ($user) {
            return $user->isSecretaire();
        });

        Gate::define('is-pharmacist', function ($user) {
            return $user->isPharmacist();
        });

    }

}
