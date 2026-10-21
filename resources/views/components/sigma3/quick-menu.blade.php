@props([
    'title',
    'headerIcon',
    'theme' => 'emerald',
    'items' => [],
])

<div {{ $attributes->merge(['class' => 'sigma3-quick-menu']) }} data-theme="{{ $theme }}">
    <style>
        @media (max-width: 640px) {
            .sigma3-quick-menu {
                padding: 1rem 0.875rem !important;
                border-radius: 0.875rem !important;
            }
            .sigma3-quick-menu__glow {
                width: 140px !important;
                height: 140px !important;
                top: -30px !important;
                right: -30px !important;
            }
            .sigma3-quick-menu__header {
                margin-bottom: 0.75rem !important;
                padding-bottom: 0.625rem !important;
                gap: 0.5rem !important;
            }
            .sigma3-quick-menu__header-icon {
                width: 1.875rem !important;
                height: 1.875rem !important;
            }
            .sigma3-quick-menu__title {
                font-size: 0.95rem !important;
                font-weight: 700 !important;
            }
            .sigma3-quick-menu__grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 0.5rem !important;
            }
            .sigma3-quick-menu__link {
                padding: 0.75rem 0.375rem !important;
                gap: 0.45rem !important;
                border-radius: 0.75rem !important;
            }
            .sigma3-quick-menu__icon-wrap {
                width: 2.35rem !important;
                height: 2.35rem !important;
                border-radius: 0.5rem !important;
            }
            .sigma3-quick-menu__icon-wrap svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }
            .sigma3-quick-menu__label {
                font-size: 0.7rem !important;
                line-height: 1.2 !important;
                font-weight: 600 !important;
                display: -webkit-box !important;
                -webkit-line-clamp: 2 !important;
                -webkit-box-orient: vertical !important;
                overflow: hidden !important;
                word-break: break-word !important;
            }
        }
        @media (max-width: 380px) {
            .sigma3-quick-menu__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.5rem !important;
            }
        }
    </style>

    <div class="sigma3-quick-menu__glow"></div>

    <div class="sigma3-quick-menu__header">
        <div class="sigma3-quick-menu__header-icon">
            <x-filament::icon :icon="$headerIcon" class="size-5" />
        </div>
        <h2 class="sigma3-quick-menu__title">{{ $title }}</h2>
    </div>

    <div class="sigma3-quick-menu__grid">
        @foreach ($items as $item)
            @php
                $isExternalOrNonSpa = str_contains($item['url'], 'scan-kelas') || str_contains($item['url'], 'display');
            @endphp
            <a href="{{ $item['url'] }}" class="sigma3-quick-menu__link" @if($isExternalOrNonSpa) wire:navigate="false" data-navigate-ignore="true" @endif>
                <div class="sigma3-quick-menu__icon-wrap">
                    <x-filament::icon :icon="$item['icon']" class="size-6" />
                </div>
                <span class="sigma3-quick-menu__label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
