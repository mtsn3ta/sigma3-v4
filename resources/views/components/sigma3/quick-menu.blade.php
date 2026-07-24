@props([
    'title',
    'headerIcon',
    'theme' => 'emerald',
    'items' => [],
])

<div {{ $attributes->merge(['class' => 'sigma3-quick-menu']) }} data-theme="{{ $theme }}">
    <div class="sigma3-quick-menu__glow"></div>

    <div class="sigma3-quick-menu__header">
        <div class="sigma3-quick-menu__header-icon">
            <x-filament::icon :icon="$headerIcon" class="size-5" />
        </div>
        <h2 class="sigma3-quick-menu__title">{{ $title }}</h2>
    </div>

    <div class="sigma3-quick-menu__grid">
        @foreach ($items as $item)
            <a href="{{ $item['url'] }}" class="sigma3-quick-menu__link">
                <div class="sigma3-quick-menu__icon-wrap">
                    <x-filament::icon :icon="$item['icon']" class="size-6" />
                </div>
                <span class="sigma3-quick-menu__label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
