<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Recurso;
use App\Models\Canal;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate to verify if user is allowed to upload/create resources or channels
        Gate::define('create-content', function (User $user) {
            return $user->rol === 'admin' || $user->rol === 'maestro';
        });

        // Gate to verify if user can manage a specific channel
        Gate::define('manage-canal', function (User $user, Canal $canal) {
            return $user->rol === 'admin' || 
                   ($user->rol === 'maestro' && $canal->maestro_id === $user->id);
        });

        // Gate to verify if user can manage a specific resource
        Gate::define('manage-recurso', function (User $user, Recurso $recurso) {
            return $user->rol === 'admin' || 
                   ($user->rol === 'maestro' && $recurso->canal->maestro_id === $user->id);
        });
    }
}
