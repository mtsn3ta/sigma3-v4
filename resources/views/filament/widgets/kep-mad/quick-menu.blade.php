<x-filament-widgets::widget>
    {{-- CSS Khusus untuk Efek Hover Premium --}}
    <style>
        .waka-quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 1rem;
        }

        .premium-quick-link {
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

        .premium-quick-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #10b981, #06b6d4);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .premium-quick-link:hover {
            transform: translateY(-4px);
            border-color: #a7f3d0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 12px 20px -8px rgba(16, 185, 129, 0.15), 0 4px 6px -4px rgba(16, 185, 129, 0.05);
        }

        .premium-quick-link:hover::before {
            opacity: 1;
        }

        .quick-icon-wrapper {
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

        .premium-quick-link:hover .quick-icon-wrapper {
            background: #ecfdf5;
            color: #059669;
            border-color: #a7f3d0;
            transform: scale(1.05);
        }

        .quick-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            transition: color 0.3s ease;
            text-align: center;
        }

        .premium-quick-link:hover .quick-label {
            color: #1e293b;
        }
    </style>

    <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">

        <div
            style="
            padding: 2rem;
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        ">
            {{-- Pendaran Halus di Pojok (Warna Emerald) --}}
            <div
                style="
                position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
                background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255,255,255,0) 70%);
                border-radius: 50%; pointer-events: none; filter: blur(20px);
            ">
            </div>

            {{-- Header Title --}}
            <div
                style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; position: relative; z-index: 10;">
                <div
                    style="
                    display: flex; align-items: center; justify-content: center;
                    width: 36px; height: 36px; background: #ecfdf5; border-radius: 0.5rem; color: #10b981;
                    box-shadow: 0 2px 10px rgba(16, 185, 129, 0.15);
                ">
                    {{-- Shield Check Icon (Melambangkan Approval/Otorisasi) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">
                    Akses Cepat (Approval)
                </h2>
            </div>

            {{-- Grid Menu Container --}}
            <div class="waka-quick-grid" style="position: relative; z-index: 10;">
                @foreach ([['label' => 'Approval Proposal', 'icon' => 'heroicon-o-document-check', 'url' => '/admin/proposal-kegiatans'], ['label' => 'Approval Laporan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => '/admin/laporan-kegiatans'], ['label' => 'Laporan Bulanan', 'icon' => 'heroicon-o-calendar-days', 'url' => '/admin/laporan-bulanans'], ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => '/admin/prestasis']] as $menu)
                    <a href="{{ $menu['url'] }}" class="premium-quick-link">
                        <div class="quick-icon-wrapper">
                            <x-filament::icon :icon="$menu['icon']" style="width: 24px; height: 24px;" />
                        </div>
                        <span class="quick-label">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
