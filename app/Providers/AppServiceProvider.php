<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\View\Components\PublicLayout;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Blade::component('layouts.public', PublicLayout::class);
        Blade::component('public-layout', PublicLayout::class);

        // Hydrate operational settings from database if available
        if (Schema::hasTable('settings')) {
            try {
                $settings = Setting::all()->pluck('value', 'key');
                if ($settings->has('change_deadline_hours')) {
                    config(['pt_booking.change_deadline_hours' => (int) $settings->get('change_deadline_hours')]);
                }
                if ($settings->has('min_booking_notice_hours')) {
                    config(['pt_booking.min_booking_notice_hours' => (int) $settings->get('min_booking_notice_hours')]);
                }
                if ($settings->has('session_duration_minutes')) {
                    config(['pt_booking.session_duration_minutes' => (int) $settings->get('session_duration_minutes')]);
                }
                if ($settings->has('auto_cancel_pending_hours_before')) {
                    config(['pt_booking.auto_cancel_pending_hours_before' => (int) $settings->get('auto_cancel_pending_hours_before')]);
                }
            } catch (\Throwable) {
                // Ignore during pre-migration bootstrapping
            }
        }

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
