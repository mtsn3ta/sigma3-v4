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
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Support\SettingHelper;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\Login::class)
            ->colors([
                'primary' => Color::Emerald,
                'success' => Color::Emerald,
                'danger' => Color::Rose,
                'warning' => Color::Amber,
                'info' => Color::Sky,
                'gray' => Color::Slate,
            ])
            ->maxContentWidth('full')
            ->sidebarWidth('18rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Plus Jakarta Sans')      // Opsional: Gunakan font yang sama dengan frontend
            ->brandName('SIGMA-3')

            ->brandLogo(
                SettingHelper::schoolLogoUrl()
            )
            ->brandLogoHeight('3rem')
            ->favicon(
                SettingHelper::schoolFaviconUrl()
            )           // Mengunci ke Light Mode agar tidak merusak tema Glass
            ->spa()
            ->navigationGroups([
                'Master Data',
                'Akademik',
                'Guru',
                'BK',
                'Guru Piket',
                'Monitoring',
                'Ekstrakurikuler',
                'Publikasi',
                'Pengaturan',
            ])
            ->navigationItems([

                NavigationItem::make('Izin Keluar')
                    ->group('BK')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->visible(function (): bool {

                        /** @var User|null $user */
                        $user = Auth::user();

                        return $user?->hasRole('bk') ?? false;
                    })
                    ->url(fn(): string => IzinKeluarResource::getUrl())
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(
                            IzinKeluarResource::getRouteBaseName() . '.*'
                        )
                    ),

                NavigationItem::make('Izin Keluar')
                    ->group('Guru Piket')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->visible(function (): bool {

                        /** @var User|null $user */
                        $user = Auth::user();

                        return $user?->hasRole('piket') ?? false;
                    })
                    ->url(fn(): string => IzinKeluarResource::getUrl())
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(
                            IzinKeluarResource::getRouteBaseName() . '.*'
                        )
                    ),

            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
