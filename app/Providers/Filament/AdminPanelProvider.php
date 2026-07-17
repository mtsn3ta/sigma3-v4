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
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use App\Support\SettingHelper;
use Filament\View\PanelsRenderHook;
use App\Filament\Pages\EditProfile;
use Filament\Navigation\MenuItem;

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
            //->spa()
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
                    ->visible(fn() => \App\Support\ActiveRole::is('bk'))
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
                    ->visible(fn() => \App\Support\ActiveRole::is('piket'))
                    ->url(fn(): string => IzinKeluarResource::getUrl())
                    ->isActiveWhen(
                        fn() =>
                        request()->routeIs(
                            IzinKeluarResource::getRouteBaseName() . '.*'
                        )
                    ),

            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn() => view('filament.sidebar.role-switcher')
            )
            ->userMenuItems([

                MenuItem::make()
                    ->label('Edit Profil')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn(): string => EditProfile::getUrl()),

            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // SuperAdmin
                \App\Filament\Widgets\SuperAdmin\SuperAdminStatsWidget::class,
                \App\Filament\Widgets\SuperAdmin\SuperAdminMasterDataWidget::class,
                \App\Filament\Widgets\SuperAdmin\SuperAdminQuickMenuWidget::class,
                \App\Filament\Widgets\SuperAdmin\SuperAdminActivityWidget::class,
                // Kepala Madrasah
                \App\Filament\Widgets\KepMad\KepMadStatsWidget::class,
                \App\Filament\Widgets\KepMad\KepMadApprovalWidget::class,
                \App\Filament\Widgets\KepMad\KepMadInformasiWidget::class,
                \App\Filament\Widgets\KepMad\KepMadQuickMenuWidget::class,
                \App\Filament\Widgets\KepMad\KepMadTugasGuruWidget::class,
                // Waka Kesiswaan
                \App\Filament\Widgets\Waka\WakaStatsWidget::class,
                \App\Filament\Widgets\Waka\WakaApprovalWidget::class,
                \App\Filament\Widgets\Waka\WakaIzinKeluarWidget::class,
                \App\Filament\Widgets\Waka\WakaPrestasiWidget::class,
                \App\Filament\Widgets\Waka\WakaQuickMenuWidget::class,
                // BK
                \App\Filament\Widgets\BK\BKStatsWidget::class,
                \App\Filament\Widgets\BK\BKRankingPoinWidget::class,
                \App\Filament\Widgets\BK\BKRawanAlpaWidget::class,
                \App\Filament\Widgets\BK\BKKasusTerbaruWidget::class,
                \App\Filament\Widgets\BK\BKIzinKeluarWidget::class,
                // Guru
                \App\Filament\Widgets\Guru\GuruStatsWidget::class,
                \App\Filament\Widgets\Guru\GuruJurnalWidget::class,
                \App\Filament\Widgets\Guru\GuruTugasIzinWidget::class,
                // Guru Piket
                \App\Filament\Widgets\Piket\PiketStatsWidget::class,
                \App\Filament\Widgets\Piket\PiketKehadiranWidget::class,
                \App\Filament\Widgets\Piket\PiketIzinKeluarWidget::class,
                \App\Filament\Widgets\Piket\PiketTugasGuruWidget::class,
                \App\Filament\Widgets\Piket\PiketQuickMenuWidget::class,
                // Pembina
                \App\Filament\Widgets\Pembina\PembinaStatsWidget::class,
                \App\Filament\Widgets\Pembina\PembinaEkskulWidget::class,
                \App\Filament\Widgets\Pembina\PembinaKegiatanWidget::class,
                \App\Filament\Widgets\Pembina\PembinaQuickMenuWidget::class,
                // Pelatih
                \App\Filament\Widgets\Pelatih\PelatihStatsWidget::class,
                \App\Filament\Widgets\Pelatih\PelatihEkskulWidget::class,
                \App\Filament\Widgets\Pelatih\PelatihLatihanWidget::class,
                \App\Filament\Widgets\Pelatih\PelatihPrestasiWidget::class,
                \App\Filament\Widgets\Pelatih\PelatihQuickMenuWidget::class,
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
