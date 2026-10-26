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
        // Roles
        Gate::define('admin', fn (User $user): bool => $user->isAdmin());
        Gate::define('trainer', fn (User $user): bool => $user->isTrainer());
        Gate::define('member', fn (User $user): bool => $user->isMember());

        // Dashboards
        Gate::define('access-admin-dashboard', fn (User $user): bool => $user->isAdmin());
        Gate::define('access-trainer-dashboard', fn (User $user): bool => $user->isTrainer());
        Gate::define('access-member-dashboard', fn (User $user): bool => $user->isMember());

        // Permissions & Features
        Gate::define('manage-membership-packages', fn (User $user): bool => $user->isAdmin());
        Gate::define('verify-payments', fn (User $user): bool => $user->isAdmin());
        Gate::define('manage-members', fn (User $user): bool => $user->isAdmin());
        Gate::define('manage-pt-sessions', fn (User $user): bool => $user->isAdmin() || $user->isTrainer());
        Gate::define('book-pt-session', fn (User $user): bool => $user->isMember());
    }
}
