<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use App\Models\Ekstrakurikuler;
use App\Policies\EkstrakurikulerPolicy;
use App\Models\EkstrakurikulerPengampu;
use App\Policies\EkstrakurikulerPengampuPolicy;
use App\Models\SesiKegiatan;
use App\Policies\SesiKegiatanPolicy;
use App\Models\Setting;
use App\Policies\SettingPolicy;
use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    public function boot(): void
    {
        if (app()->isProduction() || str_contains(config('app.url'), 'https://') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('super_admin')
                ? true
                : null;
        });

        Gate::policy(
            Role::class,
            RolePolicy::class
        );

        Gate::policy(
            Setting::class,
            SettingPolicy::class
        );

        Gate::policy(
            Ekstrakurikuler::class,
            EkstrakurikulerPolicy::class
        );
        Gate::policy(
            EkstrakurikulerPengampu::class,
            EkstrakurikulerPengampuPolicy::class
        );
        Gate::policy(
            SesiKegiatan::class,
            SesiKegiatanPolicy::class
        );
        Event::listen(Login::class, function (Login $event) {

            ActivityLogService::log(
                'Login',
                $event->user->name . ' berhasil login.'
            );
        });

        Event::listen(Logout::class, function (Logout $event) {

            ActivityLogService::log(
                'Logout',
                $event->user->name . ' berhasil logout.'
            );
        });
        Carbon::setLocale('id');

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('super_admin')
                ? true
                : null;
        });
    }
}
