<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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

        // Kustomisasi Tampilan dan Konten Email Verifikasi (Bahasa Indonesia)
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $appName = config('app.name', 'FitCore Gym');

            return (new MailMessage)
                ->subject("Verifikasi Alamat Email - {$appName}")
                ->greeting("Halo, {$notifiable->name}!")
                ->line("Terima kasih telah mendaftar di {$appName}. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:")
                ->action('Verifikasi Email Saya', $url)
                ->line('Tautan verifikasi ini berlaku selama 60 menit.')
                ->line("Jika Anda tidak merasa mendaftar akun di {$appName}, Anda dapat mengabaikan email ini.")
                ->salutation("Salam hangat,\nTim {$appName}");
        });
    }
}
