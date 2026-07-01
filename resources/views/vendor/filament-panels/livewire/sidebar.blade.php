<div style="height: 100%; position: relative;">

    <style>
        /* =========================================
           0. GLOBAL PAGE BACKGROUND
           ========================================= */
        body {
            background-attachment: fixed !important;
        }

        .fi-layout,
        .fi-main {
            background: transparent !important;
        }

        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #020617 100%) !important;
        }

        .dark body::before {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px) !important;
        }

        /* =========================================
           1. SIDEBAR MENGAMBANG (Balanced Glass)
           ========================================= */
        .fi-main-sidebar {
            height: calc(100vh - 2rem) !important;
            margin: 1rem 0 1rem 1rem !important;
            border-radius: 24px !important;

            /* Glassmorphism yang menonjol namun tidak berlebihan */
            background-color: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;

            /* Border halus untuk memisahkan dari background */
            border: 1px solid rgba(255, 255, 255, 0.9) !important;

            /* Shadow lembut agar menonjol selaras dengan topbar */
            box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.1) !important;

            overflow: hidden !important;
        }

        .dark .fi-main-sidebar {
            background-color: rgba(15, 23, 42, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4) !important;
        }

        .fi-main-sidebar::before {
            display: none !important;
        }

        .fi-sidebar-header-ctn {
            padding: 0 !important;
            border-bottom: none !important;
            min-height: 0 !important;
        }

        /* =========================================
           2. LAYOUT MENU (Spasi & Tipografi)
           ========================================= */
        .fi-sidebar-nav-groups {
            padding: 1rem 0.5rem !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 1.25rem !important;
            overflow-x: hidden !important;
            /* Mencegah overflow horizontal dari animasi teks */
        }

        .fi-sidebar-group-items {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.15rem !important;
        }

        .fi-sidebar-group-label {
            font-size: 0.65rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            margin-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            color: #64748b !important;
        }

        .fi-sidebar-item>a {
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #4b5563 !important;
            transition: all 0.2s ease !important;
            overflow: hidden;
            /* Penting untuk efek marquee */
        }

        .fi-sidebar-item-icon {
            color: #94a3b8 !important;
            transition: color 0.2s ease !important;
            flex-shrink: 0;
            /* Mencegah ikon menyusut saat teks memanjang */
        }

        .fi-sidebar-nav {
            background: transparent !important;
        }

        .fi-sidebar-footer {
            background: rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.7) !important;
        }

        .dark .fi-sidebar-footer {
            background: rgba(0, 0, 0, 0.2) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .dark .fi-sidebar-group-label {
            color: #71717a !important;
        }

        .dark .fi-sidebar-item>a {
            color: #a1a1aa !important;
        }

        .dark .fi-sidebar-item-icon {
            color: #71717a !important;
        }

        /* =========================================
           3. HOVER & ACTIVE STATE
           ========================================= */
        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover {
            background-color: rgba(13, 148, 136, 0.08) !important;
            /* Teal sangat pudar agar elegan */
            color: #0891b2 !important;
        }

        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon {
            color: #0891b2 !important;
        }

        .fi-sidebar-item-active>a,
        .fi-sidebar-item.fi-active>a {
            background-color: rgba(8, 145, 178, 0.2) !important;
            border: 1px solid #ffffff !important;
            color: #0d9488 !important;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.08) !important;
        }

        .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #0d9488 !important;
        }

        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover {
            background-color: rgba(8, 145, 178, 0.1) !important;
            color: #22d3ee !important;
        }

        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon {
            color: #22d3ee !important;
        }

        .dark .fi-sidebar-item-active>a,
        .dark .fi-sidebar-item.fi-active>a {
            background-color: rgba(8, 145, 178, 0.2) !important;
            border: 1px solid rgba(34, 211, 238, 0.15) !important;
            color: #2dd4bf !important;
            box-shadow: none !important;
        }

        .dark .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .dark .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #2dd4bf !important;
        }

        /* =========================================
           4. CUSTOM SCROLLBAR MODERN
           ========================================= */
        .fi-sidebar-nav::-webkit-scrollbar {
            width: 4px;
            /* Sangat ramping */
            height: 4px;
        }

        .fi-sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
            margin: 1rem 0;
        }

        .fi-sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(13, 148, 136, 0.15);
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .fi-sidebar-nav:hover::-webkit-scrollbar-thumb {
            background: rgba(13, 148, 136, 0.4);
        }

        .fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(13, 148, 136, 0.7);
        }

        .dark .fi-sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }

        .dark .fi-sidebar-nav:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
        }

        .dark .fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .fi-sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: rgba(13, 148, 136, 0.4) transparent;
        }

        /* =========================================
           5. PENANGANAN TEKS TERPOTONG (ELEGANT WRAP)
           ========================================= */
        /* Kondisi Normal: Teks panjang dirapikan dengan titik-titik (...) */
        .fi-sidebar-item-label {
            display: block !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            max-width: 100% !important;
            line-height: 1.4 !important;
        }

        /* Kondisi Hover: Saat disorot, izinkan teks turun ke baris baru */
        .fi-sidebar-item:hover .fi-sidebar-item-label {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            word-wrap: break-word !important;
        }

        /* Pastikan tinggi tombol menu menyesuaikan teks dan ikon tetap di tengah */
        .fi-sidebar-item>a {
            height: auto !important;
            align-items: center !important;
            padding-top: 0.65rem !important;
            padding-bottom: 0.65rem !important;
        }

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

        /* Memaksa logo dan teks brand sejajar ke samping */
        .fi-sidebar-header-logo-ctn {
            display: flex !important;
            align-items: center !important;
        }

        /* Mencegah logo menyusut jika teks terlalu panjang */
        .fi-sidebar-header-logo-ctn>a,
        .fi-sidebar-header-logo-ctn>svg,
        .fi-sidebar-header-logo-ctn>img {
            flex-shrink: 0 !important;
        }
    </style>

    @php
        $navigation = filament()->getNavigation();
        $isRtl = __('filament-panels::layout.direction') === 'rtl';
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
        $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
        $hasNavigation = filament()->hasNavigation();
        $hasTopbar = filament()->hasTopbar();
    @endphp

    {{-- format-ignore-start --}}
    <aside x-data="{}" @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop) x-cloak
        @else x-cloak="-lg" @endif
        x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }" class="fi-sidebar fi-main-sidebar">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_START) }}

        <div class="fi-sidebar-header-ctn">
            <header class="fi-sidebar-header">
                @if (!$hasTopbar && $isSidebarCollapsibleOnDesktop)
                    <x-filament::icon-button color="gray" :icon="$isRtl
                        ? \Filament\Support\Icons\Heroicon::OutlinedChevronLeft
                        : \Filament\Support\Icons\Heroicon::OutlinedChevronRight" :icon-alias="$isRtl
                        ? [
                            \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL,
                            \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON,
                        ]
                        : \Filament\View\PanelsIconAlias::SIDEBAR_EXPAND_BUTTON" icon-size="lg"
                        :label="__('filament-panels::layout.actions.sidebar.expand.label')" x-cloak x-data="{}" x-on:click="$store.sidebar.open()"
                        x-show="! $store.sidebar.isOpen" class="fi-sidebar-open-collapse-sidebar-btn" />
                @endif

                @if (!$hasTopbar && ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop))
                    <x-filament::icon-button color="gray" :icon="$isRtl
                        ? \Filament\Support\Icons\Heroicon::OutlinedChevronRight
                        : \Filament\Support\Icons\Heroicon::OutlinedChevronLeft" :icon-alias="$isRtl
                        ? [
                            \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL,
                            \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON,
                        ]
                        : \Filament\View\PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON" icon-size="lg"
                        :label="__('filament-panels::layout.actions.sidebar.collapse.label')" x-cloak x-data="{}" x-on:click="$store.sidebar.close()"
                        x-show="$store.sidebar.isOpen" class="fi-sidebar-close-collapse-sidebar-btn" />
                @endif

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_BEFORE) }}

                <div @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop) x-show="$store.sidebar.isOpen" @endif
                    class="fi-sidebar-header-logo-ctn">
                    @if ($homeUrl = filament()->getHomeUrl())
                        <a {{ \Filament\Support\generate_href_html($homeUrl) }}>
                            <x-filament-panels::logo />
                        </a>
                    @else
                        <x-filament-panels::logo />
                    @endif
                    <div class="sigma-brand">
                        <div class="sigma-brand-title">
                            <span>SIGMA</span>
                            <span>3</span>
                        </div>

                        <div class="sigma-brand-subtitle">
                            SISTEM INFORMASI GLOBAL
                        </div>

                        <div class="sigma-brand-school">
                            MTsN 3 TULUNGAGUNG
                        </div>
                    </div>
                </div>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_AFTER) }}
            </header>
        </div>

        @if (filament()->hasTenancy() && filament()->hasTenantMenu())
            <x-filament-panels::tenant-menu />
        @endif

        @if (filament()->isGlobalSearchEnabled() &&
                filament()->getGlobalSearchPosition() === \Filament\Enums\GlobalSearchPosition::Sidebar)
            <div @if ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop) x-show="$store.sidebar.isOpen" @endif style="padding: 0.5rem 1rem;">
                @livewire(Filament\Livewire\GlobalSearch::class)
            </div>
        @endif

        <nav class="fi-sidebar-nav">
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_START) }}

            <ul class="fi-sidebar-nav-groups">
                @foreach ($navigation as $group)
                    @php
                        $isGroupActive = $group->isActive();
                        $isGroupCollapsible = $group->isCollapsible();
                        $groupIcon = $group->getIcon();
                        $groupItems = $group->getItems();
                        $groupLabel = $group->getLabel();
                        $groupExtraSidebarAttributeBag = $group->getExtraSidebarAttributeBag();
                    @endphp

                    <x-filament-panels::sidebar.group :active="$isGroupActive" :collapsible="$isGroupCollapsible" :icon="$groupIcon"
                        :items="$groupItems" :label="$groupLabel" :attributes="\Filament\Support\prepare_inherited_attributes($groupExtraSidebarAttributeBag)" />
                @endforeach
            </ul>

            <script>
                var collapsedGroups = JSON.parse(localStorage.getItem('collapsedGroups'))
                if (collapsedGroups === null || collapsedGroups === 'null') {
                    localStorage.setItem('collapsedGroups', JSON.stringify(@js(collect($navigation)->filter(fn(\Filament\Navigation\NavigationGroup $group): bool => $group->isCollapsed())->map(fn(\Filament\Navigation\NavigationGroup $group): string => $group->getLabel())->values()->all())))
                }
                collapsedGroups = JSON.parse(localStorage.getItem('collapsedGroups'))
                document.querySelectorAll('.fi-sidebar-group').forEach((group) => {
                    if (!collapsedGroups.includes(group.dataset.groupLabel)) return;
                    group.querySelector('.fi-sidebar-group-items').style.display = 'none'
                    group.classList.add('fi-collapsed')
                })
            </script>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_END) }}
        </nav>

        @php
            $isAuthenticated = filament()->auth()->check();
            $hasDatabaseNotificationsInSidebar =
                filament()->hasDatabaseNotifications() &&
                filament()->getDatabaseNotificationsPosition() ===
                    \Filament\Enums\DatabaseNotificationsPosition::Sidebar;
            $hasUserMenuInSidebar =
                filament()->hasUserMenu() &&
                filament()->getUserMenuPosition() === \Filament\Enums\UserMenuPosition::Sidebar;
            $shouldRenderFooter = $isAuthenticated && ($hasDatabaseNotificationsInSidebar || $hasUserMenuInSidebar);
        @endphp

        @if ($shouldRenderFooter)
            <div class="fi-sidebar-footer" style="padding: 1rem;">
                @if ($hasDatabaseNotificationsInSidebar)
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                    ])
                @endif

                @if ($hasUserMenuInSidebar)
                    <x-filament-panels::user-menu />
                @endif
            </div>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_FOOTER) }}
    </aside>
    {{-- format-ignore-end --}}

    <x-filament-actions::modals />
</div>
