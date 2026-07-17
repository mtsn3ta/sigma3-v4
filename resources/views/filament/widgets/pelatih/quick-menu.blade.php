<x-filament-widgets::widget>
    {{-- CSS Khusus untuk Efek Hover Premium (Sky Theme) --}}
    <style>
        .pelatih-menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 1rem;
        }

        .premium-pelatih-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.85rem;
            padding: 1.25rem 1rem;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
            position: relative;
            overflow: hidden;
        }

        .premium-pelatih-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0ea5e9, #0284c7);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .premium-pelatih-link:hover {
            transform: translateY(-4px);
            border-color: #bae6fd;
            background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%);
            box-shadow: 0 12px 20px -8px rgba(14, 165, 233, 0.15), 0 4px 6px -4px rgba(14, 165, 233, 0.05);
        }

        .premium-pelatih-link:hover::before {
            opacity: 1;
        }

        .icon-wrapper-sky {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .premium-pelatih-link:hover .icon-wrapper-sky {
            background: #e0f2fe;
            color: #0284c7;
            border-color: #bae6fd;
            transform: scale(1.05);
        }

        .label-sky {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            transition: color 0.3s ease;
            text-align: center;
        }

        .premium-pelatih-link:hover .label-sky {
            color: #0369a1;
        }
    </style>

    {{-- Widget Container (Tanpa Section Nested) --}}
    <div
        style="
        padding: 2rem;
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    ">
        {{-- Pendaran Halus (Sky Blue) --}}
        <div
            style="
            position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.08) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%; pointer-events: none; filter: blur(20px);
        ">
        </div>

        {{-- Header --}}
        <div
            style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; position: relative; z-index: 10;">
            <div
                style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #e0f2fe; border-radius: 0.5rem; color: #0284c7; box-shadow: 0 2px 10px rgba(14, 165, 233, 0.15);">
                <x-filament::icon icon="heroicon-o-presentation-chart-line" style="width: 20px; height: 20px;" />
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Kontrol Pelatih</h2>
        </div>

        {{-- Grid Menu --}}
        <div class="pelatih-menu-grid" style="position: relative; z-index: 10;">
            @foreach ([['label' => 'Input Latihan', 'icon' => 'heroicon-o-calendar-days', 'url' => '/admin/sesi-kegiatans/create'], ['label' => 'Absensi Latihan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => '/admin/sesi-kegiatans'], ['label' => 'Anggota', 'icon' => 'heroicon-o-user-group', 'url' => '/admin/anggota-ekskuls'], ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => '/admin/prestasis']] as $menu)
                <a href="{{ $menu['url'] }}" class="premium-pelatih-link">
                    <div class="icon-wrapper-sky">
                        <x-filament::icon :icon="$menu['icon']" style="width: 24px; height: 24px;" />
                    </div>
                    <span class="label-sky">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
