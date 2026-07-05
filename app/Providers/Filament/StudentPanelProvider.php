<?php

namespace App\Providers\Filament;

use App\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Support\SettingHelper;

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->id('siswa')
            ->path('siswa')

            ->login(\App\Filament\Auth\Login::class)

            ->colors([
                'primary' => Color::Emerald,
                'success' => Color::Emerald,
                'danger'  => Color::Rose,
                'warning' => Color::Amber,
                'info'     => Color::Sky,
                'gray'     => Color::Slate,
            ])

            ->maxContentWidth('full')

            ->sidebarWidth('18rem')

            ->viteTheme('resources/css/filament/admin/theme.css')

            ->font('Plus Jakarta Sans')

            ->brandName('SIGMA-3 Student')

            ->brandLogo(
                SettingHelper::schoolLogoUrl()
            )

            ->brandLogoHeight('3rem')

            ->favicon(
                SettingHelper::schoolFaviconUrl()
            )

            ->spa()

            ->navigationGroups([
                'Dashboard',
                'Profil',
                'Akademik',
                'Ekstrakurikuler',
                'BK',
            ])

            ->discoverResources(
                in: app_path('Filament/Student/Resources'),
                for: 'App\\Filament\\Student\\Resources',
            )

            ->discoverPages(
                in: app_path('Filament/Student/Pages'),
                for: 'App\\Filament\\Student\\Pages',
            )

            ->pages([
                \App\Filament\Student\Pages\Dashboard::class, // Ini file kustom yang baru kita buat
            ])

            ->discoverWidgets(
                in: app_path('Filament/Student/Widgets'),
                for: 'App\\Filament\\Student\\Widgets',
            )

            ->widgets([
                \App\Filament\Student\Widgets\StudentGreeting::class,
                \App\Filament\Student\Widgets\StudentDailyStats::class,
                \App\Filament\Student\Widgets\StudentKehadiranStats::class,
                \App\Filament\Student\Widgets\StudentPrestasiWidget::class,
                \App\Filament\Student\Widgets\StudentPengumumanWidget::class,
                \App\Filament\Student\Widgets\StudentEkskulActivity::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
