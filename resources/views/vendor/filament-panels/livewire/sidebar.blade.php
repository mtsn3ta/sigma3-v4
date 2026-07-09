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
   1. SIDEBAR MENGAMBANG (Balanced Glass - Responsive)
   ========================================= */
        .fi-main-sidebar {
            /* 🔽 Mengurangi tinggi total dari 2rem ke 2.5rem agar bagian bawah tidak jebol saat atasnya diturunkan */
            height: calc(100vh - 2.5rem) !important;

            /* 🔽 Menurunkan batas atas sidebar dari 1rem menjadi 1.25rem (Sejajar presisi dengan topbar) */
            margin: 1.25rem 1rem 1.25rem 1rem !important;
            border-radius: 24px !important;

            /* Glassmorphism */
            background:
                linear-gradient(180deg,
                    rgba(255, 255, 255, .82),
                    rgba(255, 255, 255, .68)) !important;

            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, .75);

            box-shadow:
                0 20px 45px rgba(15, 23, 42, .08),
                0 8px 18px rgba(34, 197, 94, .04),
                inset 0 1px rgba(255, 255, 255, .8);

            overflow: hidden !important;
            transition: transform 0.3s ease, width 0.3s ease !important;
        }

        /* =========================================
   PENEGAKAN JARAK KONTEN (Anti-Tabrakan Topbar)
   ========================================= */

        /* 1. Mendorong Konten Utama (Kanal Kanan) Turun ke Bawah */
        .fi-main {
            padding-top: 5.5rem !important;
            /* Memberikan ruang kosong yang pas di bawah topbar */
        }

        /* 2. Mendorong Isi Menu Sidebar agar Tidak Tertutup Header Topbar */
        .fi-sidebar-nav {
            padding-top: 1.5rem !important;
            /* Dirapatkan kembali karena tidak ada tabrakan lagi */
            background: transparent !important;
        }

        /* 💻 PENYESUAIAN KHUSUS LAYAR BESAR (Desktop) */
        @media (min-width: 1024px) {
            .fi-main {
                padding-top: 5.5rem !important;
                /* Tetap menjaga jarak dari sidebar desktop Anda */
            }
        }

        /* 📱 PENYESUAIAN KHUSUS LAYAR KECIL (Mobile) */
        @media (max-width: 1023px) {
            .fi-main {
                padding-top: 6rem !important;
                /* Sedikit lebih longgar di mobile agar header halaman aman */
                margin-left: 0 !important;
            }
        }

        /* 💻 ATURAN KHUSUS UNTUK LAYAR BESAR (Desktop / Min-width: 1024px) */
        @media (min-width: 1024px) {
            .fi-main-sidebar {
                width: 320px !important;
                /* Lebar ekstra hanya aktif di desktop */
                max-width: 320px !important;
                margin: 1rem 0 1rem 1rem !important;
                /* Mengembalikan margin asal */
            }
        }

        /* 📱 ATURAN KHUSUS UNTUK LAYAR KECIL / DRAWER (Tablet & Mobile / Max-width: 1023px) */
        @media (max-width: 1023px) {
            .fi-main-sidebar {
                width: 280px !important;
                /* Ukuran proporsional saat menjadi drawer laci di mobile */
                max-width: 280px !important;
                height: calc(100vh - 2rem) !important;
                z-index: 50 !important;
                /* Memastikan melayang di atas konten utama */
            }
        }

        /* Mode Gelap Tetap Sama */
        .dark .fi-main-sidebar {
            background:
                linear-gradient(180deg,
                    rgba(15, 23, 42, .86),
                    rgba(2, 6, 23, .78)) !important;
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow:
                0 30px 60px rgba(0, 0, 0, .45),
                inset 0 1px rgba(255, 255, 255, .04);
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
           2. SIDEBAR NAVIGATION (Premium)
           ========================================= */
        .fi-sidebar-nav {
            background: transparent !important;
        }

        .fi-sidebar-nav-groups {
            padding: 1rem .6rem 1.25rem !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 1.5rem !important;
            overflow-x: hidden !important;
        }

        .fi-sidebar-group-items {
            display: flex !important;
            flex-direction: column !important;
            gap: .25rem !important;
        }

        /* =========================================
           GROUP TITLE
           ========================================= */
        .fi-sidebar-group-label {
            position: relative;
            padding-left: .9rem !important;
            margin-bottom: .6rem !important;
            font-size: .62rem !important;
            font-weight: 800 !important;
            letter-spacing: .14em !important;
            text-transform: uppercase !important;
            color: #94a3b8 !important;
            opacity: .9;
        }

        .fi-sidebar-group-label::after {
            content: "";
            display: block;
            width: 24px;
            height: 2px;
            margin-top: 6px;
            border-radius: 999px;
            background: linear-gradient(90deg,
                    #16a34a,
                    /* Hijau Premium */
                    #14b8a6) !important;
            opacity: .35;
        }

        .dark .fi-sidebar-group-label {
            color: #64748b !important;
        }

        /* =========================================
           MENU ITEM
           ========================================= */
        .fi-sidebar-item>a {
            display: flex !important;
            align-items: center !important;
            gap: .8rem !important;
            padding: .72rem .9rem !important;
            border-radius: 14px !important;
            border: 1px solid transparent !important;
            background: transparent !important;
            color: #475569 !important;
            font-size: .88rem !important;
            font-weight: 600 !important;
            transition: all .25s cubic-bezier(.4, 0, .2, 1) !important;
            position: relative;
            overflow: hidden;
        }

        /* =========================================
           ICON
           ========================================= */
        .fi-sidebar-item-icon {
            width: 18px !important;
            height: 18px !important;
            flex-shrink: 0;
            color: #94a3b8 !important;
            transition: all .25s ease !important;
        }

        /* =========================================
           LABEL
           ========================================= */
        .fi-sidebar-item-label {
            transition: all .25s ease;
        }

        /* =========================================
           HOVER (Light Mode)
           ========================================= */
        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover,
        .fi-sidebar-item:not(.fi-active)>a:hover {
            transform: translateX(3px);
            background: rgba(255, 255, 255, .65) !important;
            border-color: rgba(34, 197, 94, .12) !important;
            /* Border hijau transparan */
            box-shadow: 0 8px 18px rgba(15, 23, 42, .03);
            color: #15803d !important;
            /* Teks hijau gelap saat hover */
        }

        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon,
        .fi-sidebar-item:not(.fi-active)>a:hover .fi-sidebar-item-icon {
            color: #16a34a !important;
            /* Icon hijau saat hover */
        }

        /* =========================================
           ACTIVE (Light Mode)
           ========================================= */
        .fi-sidebar-item-active>a,
        .fi-sidebar-item.fi-active>a {
            background: linear-gradient(135deg, #f0fdf4, #fcfdfd) !important;
            /* Gradasi Hijau Premium Lembut */
            border: 1px solid rgba(34, 197, 94, .2) !important;
            color: #15803d !important;
            box-shadow:
                0 8px 20px rgba(34, 197, 94, .08),
                inset 0 1px rgba(255, 255, 255, .85);
        }

        .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #16a34a !important;
            transform: scale(1.08);
        }

        /* =========================================
           FOOTER
           ========================================= */
        .fi-sidebar-footer {
            background: transparent !important;
            border-top: 1px solid rgba(148, 163, 184, .12) !important;
            backdrop-filter: none !important;
            padding-top: .85rem;
        }

        /* =========================================
           DARK MODE
           ========================================= */
        .dark .fi-sidebar-item>a {
            color: #cbd5e1 !important;
        }

        .dark .fi-sidebar-item-icon {
            color: #64748b !important;
        }

        /* Hover (Dark Mode) */
        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover,
        .dark .fi-sidebar-item:not(.fi-active)>a:hover {
            background: rgba(255, 255, 255, .04) !important;
            border-color: rgba(34, 197, 94, .15) !important;
            color: #4ade80 !important;
            /* Teks hijau terang neon di dark mode */
        }

        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon,
        .dark .fi-sidebar-item:not(.fi-active)>a:hover .fi-sidebar-item-icon {
            color: #4ade80 !important;
        }

        /* Active (Dark Mode) */
        .dark .fi-sidebar-item-active>a,
        .dark .fi-sidebar-item.fi-active>a {
            background:
                linear-gradient(135deg,
                    rgba(34, 197, 94, .15),
                    rgba(34, 197, 94, .04)) !important;
            /* Gradasi hijau gelap transparan */
            border: 1px solid rgba(34, 197, 94, .25) !important;
            color: #4ade80 !important;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, .3),
                inset 0 1px rgba(255, 255, 255, .03);
        }

        .dark .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .dark .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #4ade80 !important;
        }

        /* =========================================
   3. HOVER & ACTIVE STATE (Premium - Green Edition)
   ========================================= */

        /* ---------- Hover (Light Mode) ---------- */
        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover,
        .fi-sidebar-item:not(.fi-active)>a:hover {
            transform: translateX(3px);
            background: rgba(255, 255, 255, .60) !important;
            border-color: rgba(34, 197, 94, .12) !important;
            /* Border hijau muda transparan */
            color: #15803d !important;
            /* Teks hijau gelap premium */
            box-shadow: 0 8px 18px rgba(15, 23, 42, .03);
        }

        .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon,
        .fi-sidebar-item:not(.fi-active)>a:hover .fi-sidebar-item-icon {
            color: #16a34a !important;
            /* Ikon hijau */
            transform: scale(1.08);
        }

        /* ---------- Active (Light Mode) ---------- */
        .fi-sidebar-item-active>a,
        .fi-sidebar-item.fi-active>a {
            background: linear-gradient(135deg, #f0fdf4, #fcfdfd) !important;
            /* Gradasi Emerald 50 premium */
            border: 1px solid rgba(34, 197, 94, .20) !important;
            color: #15803d !important;
            box-shadow:
                0 10px 24px rgba(34, 197, 94, .08),
                /* Bayangan berpendar hijau tipis */
                inset 0 1px rgba(255, 255, 255, .85);
        }

        .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #16a34a !important;
            transform: scale(1.1);
        }

        /* =========================================
           DARK MODE (Green Edition)
           ========================================= */

        /* ---------- Hover (Dark Mode) ---------- */
        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover,
        .dark .fi-sidebar-item:not(.fi-active)>a:hover {
            background: rgba(255, 255, 255, .05) !important;
            border-color: rgba(74, 222, 128, .12) !important;
            color: #4ade80 !important;
            /* Teks hijau emerald cerah */
            box-shadow: 0 8px 18px rgba(0, 0, 0, .25);
        }

        .dark .fi-sidebar-item:not(.fi-sidebar-item-active)>a:hover .fi-sidebar-item-icon,
        .dark .fi-sidebar-item:not(.fi-active)>a:hover .fi-sidebar-item-icon {
            color: #4ade80 !important;
            transform: scale(1.08);
        }

        /* ---------- Active (Dark Mode) ---------- */
        .dark .fi-sidebar-item-active>a,
        .dark .fi-sidebar-item.fi-active>a {
            background:
                linear-gradient(135deg,
                    rgba(34, 197, 94, .16),
                    rgba(34, 197, 94, .05)) !important;
            border: 1px solid rgba(74, 222, 128, .20) !important;
            color: #bbf7d0 !important;
            /* Teks hijau mint lembut */
            box-shadow:
                inset 0 1px rgba(255, 255, 255, .05),
                0 10px 24px rgba(0, 0, 0, .30);
        }

        .dark .fi-sidebar-item-active>a .fi-sidebar-item-icon,
        .dark .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
            color: #4ade80 !important;
            transform: scale(1.1);
        }

        /* =========================================
   4. PREMIUM SCROLLBAR (Ultra Thin & Clean Force)
   ========================================= */

        /* Target universal untuk sidebar navigasi Filament */
        .fi-sidebar-nav,
        .fi-sidebar-nav *,
        [aria-label="Sidebar navigation"] {
            scrollbar-width: thin !important;
            scrollbar-color: rgba(34, 197, 94, .25) transparent !important;
        }

        /* Mengunci ukuran scrollbar Chrome / Safari / Edge secara absolut */
        .fi-sidebar-nav::-webkit-scrollbar,
        .fi-sidebar-nav *::-webkit-scrollbar,
        [aria-label="Sidebar navigation"]::-webkit-scrollbar {
            width: 3px !important;
            /* Memaksa lebar 3px */
            height: 3px !important;
        }

        /* Menyembunyikan tombol panah atas/bawah scrollbar yang membuat tebal */
        .fi-sidebar-nav::-webkit-scrollbar-button,
        [aria-label="Sidebar navigation"]::-webkit-scrollbar-button {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Jalur lintasan scrollbar */
        .fi-sidebar-nav::-webkit-scrollbar-track,
        [aria-label="Sidebar navigation"]::-webkit-scrollbar-track {
            background: transparent !important;
            margin: .5rem 6px !important;
            /* Menambah jarak kosong dari tepi */
        }

        /* Batang scrollbar utama */
        .fi-sidebar-nav::-webkit-scrollbar-thumb,
        [aria-label="Sidebar navigation"]::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, .20) !important;
            border-radius: 999px !important;
            border: none !important;
        }

        /* Efek Hover Navigasi */
        .fi-sidebar-nav:hover::-webkit-scrollbar-thumb,
        [aria-label="Sidebar navigation"]:hover::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, .45) !important;
        }

        /* ---------- Dark Mode Force ---------- */
        .dark .fi-sidebar-nav,
        .dark [aria-label="Sidebar navigation"] {
            scrollbar-color: rgba(74, 222, 128, .25) transparent !important;
        }

        .dark .fi-sidebar-nav::-webkit-scrollbar-thumb,
        .dark [aria-label="Sidebar navigation"]::-webkit-scrollbar-thumb {
            background: rgba(74, 222, 128, .20) !important;
        }

        .dark .fi-sidebar-nav:hover::-webkit-scrollbar-thumb,
        .dark [aria-label="Sidebar navigation"]:hover::-webkit-scrollbar-thumb {
            background: rgba(74, 222, 128, .45) !important;
        }

        /* =========================================
   5. RESPONSIVE GLASS FIX (For Mobile Drawer Wrapper)
   ========================================= */
        @media (max-width: 1023px) {

            /* Memaksa elemen pembungkus terluar sidebar mobile menjadi transparan */
            aside.fi-sidebar,
            .fi-sidebar,
            [fixed-sidebar="false"] aside {
                background-color: transparent !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            /* Memastikan elemen navigasi internal tidak memblokir efek kaca */
            .fi-sidebar-nav {
                background: transparent !important;
            }

            /* Opsional: Jika backdrop (area gelap di luar sidebar) terlalu pekat,
           bisa kita buat efek blur yang selaras */
            .fi-sidebar-close-overlay {
                backdrop-filter: blur(4px) !important;
                background-color: rgba(15, 23, 42, 0.3) !important;
                /* Kegelapan latar belakang luar dikurangi */
            }
        }

        /* 📱 RESPONSIVE PREMIUM GLASSMOPHISM (Max-width: 1023px) */
        @media (max-width: 1023px) {

            /* 1. Mengatur ulang kontainer bawaan Filament agar efek kaca tidak tertutup */
            aside.fi-sidebar,
            .fi-sidebar,
            [fixed-sidebar="false"] aside {
                background: transparent !important;
                background-color: transparent !important;
                box-shadow: none !important;
            }

            /* 2. Mengaktifkan Kaca Transparan Mewah dengan Blur Tinggi di Mobile */
            .fi-main-sidebar {
                width: 280px !important;
                max-width: 280px !important;
                height: calc(100vh - 2rem) !important;
                margin: 1rem !important;
                border-radius: 24px !important;

                /* Lapisan putih transparan agar teks di atas tabel tetap terbaca */
                background: rgba(255, 255, 255, 0.85) !important;

                /* Menghalangi/memburamkan data tabel di belakangnya */
                backdrop-filter: blur(20px) saturate(160%) !important;
                -webkit-backdrop-filter: blur(20px) saturate(160%) !important;

                border: 1px solid rgba(255, 255, 255, 0.6) !important;
                box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15) !important;
                z-index: 50 !important;
                overflow: hidden !important;
            }

            /* 3. Pengaturan Kaca untuk Mode Gelap (Dark Mode) */
            .dark .fi-main-sidebar {
                background: rgba(15, 23, 42, 0.85) !important;
                backdrop-filter: blur(20px) saturate(160%) !important;
                -webkit-backdrop-filter: blur(20px) saturate(160%) !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5) !important;
            }

            /* 4. Warna Teks Menu Utama (Dibuat Gelap Tajam agar Kontras di atas Kaca Putih) */
            .fi-main-sidebar .fi-sidebar-item>a {
                color: #1e293b !important;
                /* Warna Slate 800 yang tegas */
            }

            .fi-main-sidebar .fi-sidebar-item-icon {
                color: #475569 !important;
            }

            .fi-main-sidebar .fi-sidebar-group-label {
                color: #64748b !important;
            }

            /* 5. Menjaga Warna Menu AKTIF (Tetap Hijau Premium) */
            .fi-main-sidebar .fi-sidebar-item-active>a,
            .fi-main-sidebar .fi-sidebar-item.fi-active>a {
                background: linear-gradient(135deg, #f0fdf4, #fcfdfd) !important;
                border: 1px solid rgba(34, 197, 94, 0.25) !important;
                color: #15803d !important;
            }

            .fi-main-sidebar .fi-sidebar-item-active>a .fi-sidebar-item-icon,
            .fi-main-sidebar .fi-sidebar-item.fi-active>a .fi-sidebar-item-icon {
                color: #16a34a !important;
            }
        }

        /* =========================================
           7. PENANGANAN TEKS TERPOTONG (ELEGANT WRAP)
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
