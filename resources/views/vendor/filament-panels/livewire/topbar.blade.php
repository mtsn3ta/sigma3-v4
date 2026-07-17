<div class="fi-topbar-ctn">

    <style>
        /* =========================================
       1. GAYA TOPBAR (Floating Oval / Pill)
       ========================================= */
        /* 1. Tambahkan ini untuk mengatur container pembungkusnya */
        .fi-topbar-ctn {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 40 !important;
            pointer-events: none;
            /* Penting: Agar area kosong di luar topbar tidak menghalangi klik ke halaman utama */
        }

        /* 2. Ubah sedikit .fi-topbar milikmu */
        .fi-topbar {
            /* GANTI dari fixed menjadi absolute */
            position: absolute !important;

            /* Aktifkan kembali fungsi klik khusus untuk elemen topbar */
            pointer-events: auto;

            /* === SISA KODEMU TETAP SAMA PERSIS DI BAWAH INI === */
            top: 1.25rem !important;
            border-radius: 9999px !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            background-color: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(24px) saturate(150%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(150%) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow:
                0 10px 30px -10px rgba(34, 197, 94, 0.15),
                inset 0 1px 1px rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            left: 1.5rem !important;
            width: calc(100% - 3rem) !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* (Media queries dan dark mode milikmu biarkan seperti semula) */

        /* Mode Desktop: Topbar di sebelah kanan Sidebar */
        @media (min-width: 1024px) {
            .fi-topbar {
                /* 320px (Lebar Sidebar) + 1rem (Margin Kiri Sidebar) + 1rem (Jarak antar elemen) */
                left: calc(320px + 2rem) !important;

                /* Sisa ruang layar dikurangi lebar sidebar dan margin */
                width: calc(100% - 320px - 3.5rem) !important;
            }
        }

        /* Mode Gelap untuk Topbar */
        .dark .fi-topbar {
            background-color: rgba(15, 23, 42, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow:
                0 10px 30px -10px rgba(0, 0, 0, 0.6),
                inset 0 1px 1px rgba(255, 255, 255, 0.05) !important;
        }

        /* =========================================
           2. TEMA BRAND SIGMA (Premium Typography)
           ========================================= */
        .sigma-brand {
            margin-left: .75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.1;
        }

        .sigma-brand-title {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .sigma-brand-title span:first-child {
            font-size: 1.125rem;
            font-weight: 900;
            color: #042f2e;
            /* Hijau super pekat */
            letter-spacing: -.04em;
            transition: color 0.3s ease;
        }

        .sigma-brand-title span:last-child {
            font-size: 1.125rem;
            font-weight: 900;
            color: #0d9488;
            /* Aksen Teal identitas kita */
            transition: color 0.3s ease;
        }

        .sigma-brand-subtitle {
            margin-top: 4px;
            font-size: .56rem;
            font-weight: 800;
            letter-spacing: .20em;
            color: #475569;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }

        .sigma-brand-school {
            margin-top: 2px;
            font-size: .50rem;
            font-weight: 600;
            letter-spacing: .28em;
            color: #64748b;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }

        /* Support Dark Mode untuk Teks Brand */
        .dark .sigma-brand-title span:first-child {
            color: #ffffff;
        }

        .dark .sigma-brand-title span:last-child {
            color: #2dd4bf;
            /* Teal menyala untuk background gelap */
        }

        .dark .sigma-brand-subtitle {
            color: #94a3b8;
        }

        .dark .sigma-brand-school {
            color: #64748b;
        }
    </style>

    @php
        $isRtl = __('filament-panels::layout.direction') === 'rtl';
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
        $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
        $hasTopNavigation = filament()->hasTopNavigation();
        $hasNavigation = filament()->hasNavigation();
        $hasTenancy = filament()->hasTenancy();
    @endphp

    <nav class="fi-topbar">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_START) }}

        @if ($hasNavigation)
            <x-filament::icon-button color="gray" :icon="\Filament\Support\Icons\Heroicon::OutlinedBars3" :icon-alias="\Filament\View\PanelsIconAlias::TOPBAR_OPEN_SIDEBAR_BUTTON" icon-size="lg" :label="__('filament-panels::layout.actions.sidebar.expand.label')"
                x-cloak x-data="{}" x-on:click="$store.sidebar.open()" x-show="! $store.sidebar.isOpen"
                class="fi-topbar-open-sidebar-btn" />

            <x-filament::icon-button color="gray" :icon="\Filament\Support\Icons\Heroicon::OutlinedXMark" :icon-alias="\Filament\View\PanelsIconAlias::TOPBAR_CLOSE_SIDEBAR_BUTTON" icon-size="lg" :label="__('filament-panels::layout.actions.sidebar.collapse.label')"
                x-cloak x-data="{}" x-on:click="$store.sidebar.close()" x-show="$store.sidebar.isOpen"
                class="fi-topbar-close-sidebar-btn" />
        @endif

        <div class="fi-topbar-start">
            @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
                <div x-show="$store.sidebar.isOpen || @js($isSidebarCollapsibleOnDesktop)"
                    class="fi-topbar-collapse-sidebar-btn-ctn">
                    @if ($isSidebarCollapsibleOnDesktop)
                        <x-filament::icon-button color="gray" :icon="$isRtl
                            ? \Filament\Support\Icons\Heroicon::OutlinedChevronLeft
                            : \Filament\Support\Icons\Heroicon::OutlinedChevronRight" {{-- @deprecated Use `PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL` instead of `PanelsIconAlias::SIDEBAR_EXPAND_BUTTON` for RTL. --}}
                            :icon-alias="$isRtl
                                ? [
                                    \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL,
                                    \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON,
                                ]
                                : \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON" icon-size="lg" :label="__('filament-panels::layout.actions.sidebar.expand.label')" x-cloak x-data="{}"
                            x-on:click="$store.sidebar.open()" x-show="! $store.sidebar.isOpen"
                            class="fi-topbar-open-collapse-sidebar-btn" />
                    @endif

                    @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop)
                        <x-filament::icon-button color="gray" :icon="$isRtl
                            ? \Filament\Support\Icons\Heroicon::OutlinedChevronRight
                            : \Filament\Support\Icons\Heroicon::OutlinedChevronLeft" {{-- @deprecated Use `PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL` instead of `PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON` for RTL. --}}
                            :icon-alias="$isRtl
                                ? [
                                    \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL,
                                    \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON,
                                ]
                                : \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON" icon-size="lg" :label="__('filament-panels::layout.actions.sidebar.collapse.label')" x-cloak x-data="{}"
                            x-on:click="$store.sidebar.close()" x-show="$store.sidebar.isOpen"
                            class="fi-topbar-close-collapse-sidebar-btn" />
                    @endif
                </div>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_LOGO_BEFORE) }}

            @if ($homeUrl = filament()->getHomeUrl())
                <a {{ \Filament\Support\generate_href_html($homeUrl) }}>
                    <x-filament-panels::logo />
                </a>
            @else
                <x-filament-panels::logo />
            @endif

            {{-- Bagian Brand Anda --}}
            <div class="sigma-brand">
                <div class="sigma-brand-title">
                    <span>SIGMA</span>
                    <span>3</span>
                </div>
            </div>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_LOGO_AFTER) }}
        </div>

        @if ($hasTopNavigation || !$hasNavigation)
            @if ($hasTenancy && filament()->hasTenantMenu())
                <x-filament-panels::tenant-menu teleport />
            @endif

            @if ($hasNavigation)
                @php
                    $navigation = filament()->getNavigation();
                @endphp

                <ul class="fi-topbar-nav-groups">
                    @foreach ($navigation as $group)
                        @php
                            $groupLabel = $group->getLabel();
                            $groupExtraTopbarAttributeBag = $group->getExtraTopbarAttributeBag();
                            $isGroupActive = $group->isActive();
                            $groupIcon = $group->getIcon();
                        @endphp

                        @if ($groupLabel)
                            <x-filament::dropdown placement="bottom-start" teleport :attributes="\Filament\Support\prepare_inherited_attributes($groupExtraTopbarAttributeBag)">
                                <x-slot name="trigger">
                                    <x-filament-panels::topbar.item :active="$isGroupActive" :icon="$groupIcon">
                                        {{ $groupLabel }}
                                    </x-filament-panels::topbar.item>
                                </x-slot>

                                @php
                                    $lists = [];

                                    foreach ($group->getItems() as $item) {
                                        if ($childItems = $item->getChildItems()) {
                                            $lists[] = [$item, ...$childItems];
                                            $lists[] = [];

                                            continue;
                                        }

                                        if (empty($lists)) {
                                            $lists[] = [$item];

                                            continue;
                                        }

                                        $lists[count($lists) - 1][] = $item;
                                    }

                                    if (empty($lists[count($lists) - 1])) {
                                        array_pop($lists);
                                    }
                                @endphp

                                @foreach ($lists as $list)
                                    <x-filament::dropdown.list>
                                        @foreach ($list as $item)
                                            @php
                                                $isItemActive = $item->isActive();
                                                $itemBadge = $item->getBadge();
                                                $itemBadgeColor = $item->getBadgeColor($itemBadge);
                                                $itemBadgeTooltip = $item->getBadgeTooltip($itemBadge);
                                                $itemUrl = $item->getUrl();
                                                $itemIcon = $isItemActive
                                                    ? $item->getActiveIcon() ?? $item->getIcon()
                                                    : $item->getIcon();
                                                $shouldItemOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
                                                $itemExtraAttributes = $item->getExtraAttributeBag();
                                            @endphp

                                            <x-filament::dropdown.list.item :badge="$itemBadge" :badge-color="$itemBadgeColor"
                                                :badge-tooltip="$itemBadgeTooltip" :color="$isItemActive ? 'primary' : 'gray'" :href="$itemUrl"
                                                :icon="$itemIcon" tag="a" :target="$shouldItemOpenUrlInNewTab ? '_blank' : null" :attributes="\Filament\Support\prepare_inherited_attributes(
                                                    $itemExtraAttributes,
                                                )">
                                                {{ $item->getLabel() }}
                                            </x-filament::dropdown.list.item>
                                        @endforeach
                                    </x-filament::dropdown.list>
                                @endforeach
                            </x-filament::dropdown>
                        @else
                            @foreach ($group->getItems() as $item)
                                @php
                                    $isItemActive = $item->isActive();
                                    $itemActiveIcon = $item->getActiveIcon();
                                    $itemBadge = $item->getBadge();
                                    $itemBadgeColor = $item->getBadgeColor($itemBadge);
                                    $itemBadgeTooltip = $item->getBadgeTooltip($itemBadge);
                                    $itemIcon = $item->getIcon();
                                    $shouldItemOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
                                    $itemUrl = $item->getUrl();
                                    $itemExtraAttributes = $item->getExtraAttributeBag();
                                @endphp

                                <x-filament-panels::topbar.item :active="$isItemActive" :active-icon="$itemActiveIcon" :badge="$itemBadge"
                                    :badge-color="$itemBadgeColor" :badge-tooltip="$itemBadgeTooltip" :icon="$itemIcon" :should-open-url-in-new-tab="$shouldItemOpenUrlInNewTab"
                                    :url="$itemUrl" :attributes="\Filament\Support\prepare_inherited_attributes($itemExtraAttributes)">
                                    {{ $item->getLabel() }}
                                </x-filament-panels::topbar.item>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            @endif
        @endif

        <div @if ($hasTenancy) x-persist="topbar.end.panel-{{ filament()->getId() }}.tenant-{{ filament()->getTenant()?->getKey() }}"
            @else
                x-persist="topbar.end.panel-{{ filament()->getId() }}" @endif
            class="fi-topbar-end">
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::GLOBAL_SEARCH_BEFORE) }}

            @if (filament()->isGlobalSearchEnabled() &&
                    filament()->getGlobalSearchPosition() === \Filament\Enums\GlobalSearchPosition::Topbar)
                @livewire(Filament\Livewire\GlobalSearch::class)
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::GLOBAL_SEARCH_AFTER) }}

            @if (filament()->auth()->check())
                @if (filament()->hasDatabaseNotifications() &&
                        filament()->getDatabaseNotificationsPosition() === \Filament\Enums\DatabaseNotificationsPosition::Topbar)
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                    ])
                @endif

                @if (filament()->hasUserMenu() && filament()->getUserMenuPosition() === \Filament\Enums\UserMenuPosition::Topbar)
                    <x-filament-panels::user-menu />
                @endif
            @endif
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_END) }}
    </nav>

    <x-filament-actions::modals />
</div>
