<?php

namespace App\Providers;

use App\Models\User;
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
        // Pure Laravel Native Authorization (Gates) - No third-party / Spatie
        Gate::define('admin', fn (User $user) => $user->role === 'admin');
        Gate::define('trainer', fn (User $user) => $user->role === 'trainer');
        Gate::define('member', fn (User $user) => $user->role === 'member');
    }
}
