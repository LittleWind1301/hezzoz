<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('management-class', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-class'));
        });

        Gate::define('management-subject', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-subject'));
        });

        Gate::define('management-student', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-student'));
        });

        Gate::define('management-exam', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-exam'));
        });

        Gate::define('management-question', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-question'));
        });

        Gate::define('management-group-question', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-group-question'));
        });
        
        Gate::define('management-user', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-user'));
        });

        Gate::define('management-permission', function($user){
            return $user->checkPermissionAccess(config('permissions.access.management-permission'));
        });
    }
}
