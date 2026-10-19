<div class="fi-topbar-ctn">

    <style>
        /* =========================================
           TOPBAR MODERN (Docked Glassmorphism)
           ========================================= */
        .fi-topbar-ctn {
            position: sticky !important;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 30 !important;
        }

        .fi-topbar {
            position: relative !important;
            min-height: 4rem !important;
            height: 4rem !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(16px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            border-radius: 0 !important;
            margin: 0 !important;
            left: 0 !important;
            top: 0 !important;
        }

        .dark .fi-topbar {
            background: rgba(15, 23, 42, 0.85) !important;
            border-bottom: 1px solid rgba(51, 65, 85, 0.8) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2) !important;
        }

        .fi-topbar-start {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }

        .fi-topbar-end {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            margin-left: auto !important;
        }

        /* Brand Typography */
        .sigma-brand {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-left: 0.25rem;
            line-height: 1;
        }

        .sigma-brand-title {
            display: flex;
            align-items: center;
            gap: 2px;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .sigma-brand-title span:first-child {
            color: #042f2e;
        }

        .sigma-brand-title span:last-child {
            color: #10b981;
        }

        .dark .sigma-brand-title span:first-child {
            color: #ffffff;
        }

        .dark .sigma-brand-title span:last-child {
            color: #34d399;
        }

        /* Badges */
        .sigma-context-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
        }

        .sigma-context-badge-role {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .dark .sigma-context-badge-role {
            background-color: rgba(6, 78, 59, 0.5);
            color: #6ee7b7;
            border-color: rgba(5, 150, 105, 0.4);
        }

        .sigma-context-badge-ta {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .dark .sigma-context-badge-ta {
            background-color: rgba(30, 41, 59, 0.8);
            color: #cbd5e1;
            border-color: #334155;
        }

        .sigma-portal-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .sigma-portal-btn:hover {
            color: #059669;
            background: #f0fdf4;
            border-color: #a7f3d0;
        }

        .dark .sigma-portal-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #cbd5e1;
            border-color: #334155;
        }

        .dark .sigma-portal-btn:hover {
            color: #34d399;
            background: rgba(6, 78, 59, 0.4);
            border-color: rgba(5, 150, 105, 0.4);
        }
    </style>

    @php
        $isRtl = __('filament-panels::layout.direction') === 'rtl';
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
        $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
        $hasTopNavigation = filament()->hasTopNavigation();
        $hasNavigation = filament()->hasNavigation();
        $hasTenancy = filament()->hasTenancy();
        $activeRoleName = \App\Support\ActiveRole::get();
        $academicYear = \App\Support\TahunAjaranHelper::aktif()?->tahun;
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
                            : \Filament\Support\Icons\Heroicon::OutlinedChevronRight"
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
                            : \Filament\Support\Icons\Heroicon::OutlinedChevronLeft"
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
                <a {{ \Filament\Support\generate_href_html($homeUrl) }} style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <x-filament-panels::logo />
                    <div class="sigma-brand">
                        <div class="sigma-brand-title">
                            <span>SIGMA</span>
                            <span>3</span>
                        </div>
                    </div>
                </a>
            @else
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament-panels::logo />
                    <div class="sigma-brand">
                        <div class="sigma-brand-title">
                            <span>SIGMA</span>
                            <span>3</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Context Badges (Active Role & Tahun Ajaran) --}}
            @if ($activeRoleName || $academicYear)
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: 0.75rem; padding-left: 0.75rem; border-left: 1px solid #e2e8f0;">
                    @if ($activeRoleName)
                        <span class="sigma-context-badge sigma-context-badge-role">
                            <span style="display: inline-block; width: 6px; height: 6px; border-radius: 9999px; background-color: #10b981;"></span>
                            {{ \App\Support\ActiveRole::label($activeRoleName) }}
                        </span>
                    @endif

                    @if ($academicYear)
                        <span class="sigma-context-badge sigma-context-badge-ta">
                            TA {{ $academicYear }}
                        </span>
                    @endif
                </div>
            @endif

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

            <a href="/" target="_blank" class="sigma-portal-btn" title="Buka Website Portal Depan">
                <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" style="width: 14px; height: 14px;" />
                <span>Lihat Web</span>
            </a>

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
