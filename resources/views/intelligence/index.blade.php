<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA-3 Intelligence — Pemindai Profil Siswa</title>

    {{-- Tailwind bawaan project --}}
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ─── Base ─────────────────────────────────────────── */
        body {
            background-color: #020617;
            margin: 0;
            overflow-x: hidden;
        }

        /* ─── Grid background ───────────────────────────────── */
        .grid-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* ─── Glow borders ──────────────────────────────────── */
        .glow-emerald { box-shadow: 0 0 0 1px rgba(16,185,129,.25), 0 4px 24px rgba(16,185,129,.08); }
        .glow-blue    { box-shadow: 0 0 0 1px rgba(59,130,246,.25),  0 4px 24px rgba(59,130,246,.08); }
        .glow-red     { box-shadow: 0 0 0 1px rgba(239,68,68,.25),   0 4px 24px rgba(239,68,68,.08); }
        .glow-amber   { box-shadow: 0 0 0 1px rgba(245,158,11,.25),  0 4px 24px rgba(245,158,11,.08); }

        /* ─── Glass panel ───────────────────────────────────── */
        .glass {
            background: rgba(15,23,42,.65);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,.07);
        }

        /* ─── Mini data card ────────────────────────────────── */
        .data-chip {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 10px;
            padding: 10px 12px;
        }

        /* ─── Scan pulse ring ───────────────────────────────── */
        @keyframes pingRing {
            0%   { transform: scale(1);   opacity: .6; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .ping-ring { animation: pingRing 1.4s ease-out infinite; }

        /* ─── Access denied flash ───────────────────────────── */
        .alert-screen { display: none; }
        .alert-screen.show {
            display: flex;
            animation: flashRed 1s infinite alternate;
        }
        @keyframes flashRed {
            from { background: rgba(10,10,20,.96); }
            to   { background: rgba(80,10,10,.96); }
        }

        /* ─── Scrollbar ─────────────────────────────────────── */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 99px; }
    </style>
</head>

<body class="font-sans antialiased text-white">

    {{-- ═══════════════════════════════════════
         ACCESS DENIED OVERLAY
    ════════════════════════════════════════ --}}
    <div id="accessDenied"
         class="alert-screen fixed inset-0 z-[200] flex-col items-center justify-center backdrop-blur-sm">
        <div class="text-red-500 text-5xl md:text-8xl font-black tracking-widest drop-shadow-[0_0_20px_rgba(239,68,68,0.9)] select-none">
            AKSES DITOLAK
        </div>
        <div class="mt-4 text-slate-300 text-base font-mono tracking-widest uppercase">
            Data siswa tidak ditemukan
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         MAIN WRAPPER
    ════════════════════════════════════════ --}}
    <div class="relative min-h-screen grid-bg overflow-hidden"
         style="background: radial-gradient(ellipse at 10% 0%, rgba(16,185,129,.07) 0%, transparent 50%),
                            radial-gradient(ellipse at 90% 100%, rgba(59,130,246,.06) 0%, transparent 50%),
                            #020617;">

        <div class="relative z-10 p-4 lg:p-6 flex flex-col gap-5">

            {{-- ─── TOP BAR ─────────────────────────────── --}}
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                {{-- Brand --}}
                <div class="flex items-center gap-4">
                    <div class="glass rounded-xl p-2.5 glow-emerald shrink-0">
                        @if (\App\Support\SettingHelper::schoolLogo())
                            <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolLogo()) }}"
                                 alt="Logo Madrasah"
                                 class="h-12 w-12 object-contain block">
                        @else
                            <div class="h-12 w-12 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-[10px] tracking-[0.25em] text-emerald-400 font-bold uppercase mb-0.5">
                            Terminal Informasi Cerdas
                        </div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-none">
                            PEMINDAI PROFIL <span class="text-emerald-400">SIGMA-3</span>
                        </h1>
                    </div>
                </div>

                {{-- Right controls --}}
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <a href="/"
                       class="glass rounded-xl px-4 py-2.5 text-slate-400 hover:text-white hover:bg-white/5 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        <span class="text-xs font-bold tracking-wider hidden sm:block">BERANDA</span>
                    </a>

                    <div class="glass rounded-xl px-5 py-2.5 glow-emerald text-right min-w-[9rem]">
                        <div id="clock" class="text-xl font-black text-emerald-400 font-mono tracking-widest tabular-nums">
                            00:00:00
                        </div>
                        <div id="date" class="text-[10px] text-slate-500 mt-0.5 uppercase font-semibold tracking-wide">
                            Memuat…
                        </div>
                    </div>
                </div>
            </header>

            {{-- ─── MAIN GRID ───────────────────────────── --}}
            <div class="grid lg:grid-cols-12 gap-5">

                {{-- ════════════════════════════
                     COL A — SCAN + FOTO SISWA
                ════════════════════════════ --}}
                <aside class="lg:col-span-3 flex flex-col gap-5">

                    {{-- Scan input panel --}}
                    <div class="glass rounded-2xl p-5 glow-blue flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase tracking-[0.2em] text-blue-400 font-bold">Input Sistem</span>
                            {{-- Live pulse indicator --}}
                            <div class="relative flex h-2.5 w-2.5">
                                <span class="ping-ring absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            </div>
                        </div>

                        <input id="rfid_input" type="text"
                               class="w-full bg-slate-900/60 border border-blue-500/20 rounded-xl p-4 text-center text-base text-white outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/15 transition-all font-mono placeholder:text-slate-600 tracking-widest"
                               placeholder="TEMPELKAN KARTU (RFID/NFC)"
                               autofocus>

                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="scanNFC()"
                                    class="bg-gradient-to-br from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 active:scale-95 text-white text-[11px] font-bold py-3 rounded-xl transition-all shadow-lg shadow-emerald-900/40 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z"/>
                                </svg>
                                NFC HP
                            </button>
                            <button onclick="resetUI()"
                                    class="bg-slate-800 hover:bg-slate-700 active:scale-95 text-slate-300 text-[11px] font-bold py-3 rounded-xl transition-all border border-slate-700/50 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                RESET
                            </button>
                        </div>

                        {{-- Status chip --}}
                        <div id="scanStatus"
                             class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] bg-slate-900/40 rounded-lg py-2 px-3 border border-slate-700/30">
                            SIAP UNTUK MEMINDAI…
                        </div>
                    </div>

                    {{-- Foto + identitas pokok --}}
                    <div class="glass rounded-2xl p-5 glow-emerald flex flex-col items-center gap-4">
                        {{-- Foto --}}
                        <div class="relative w-44 h-56 rounded-2xl overflow-hidden border border-emerald-500/20 bg-slate-900 shadow-[0_0_28px_rgba(16,185,129,.08)]">
                            <img id="foto"
                                 src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e293b'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z'/%3E%3C/svg%3E"
                                 class="w-full h-full object-cover"
                                 alt="Foto Siswa">
                        </div>

                        {{-- Nama + kelas --}}
                        <div class="w-full text-center">
                            <div id="nama" class="text-base font-black text-white uppercase leading-tight break-words">
                                MENUNGGU PEMINDAIAN
                            </div>
                            <div id="kelas" class="text-xs text-emerald-400 font-semibold mt-1 tracking-wide">—</div>
                        </div>

                        {{-- NISN & JK chips --}}
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">NISN</div>
                                <div id="nisn" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Jenis Kelamin</div>
                                <div id="jk" class="font-bold text-sm text-white">—</div>
                            </div>
                        </div>

                        {{-- Radar chart --}}
                        <div class="glass rounded-xl p-3 glow-amber w-full relative">
                            <div class="text-[9px] uppercase tracking-[0.2em] text-amber-400 font-bold mb-2">Radar Profil</div>
                            <canvas id="radarChart" height="190"></canvas>
                            <div id="chartPlaceholder"
                                 class="absolute inset-0 flex items-center justify-center text-[9px] text-slate-700 font-bold tracking-widest uppercase pointer-events-none">
                                MENUNGGU DATA
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- ════════════════════════════
                     COL B — BIODATA + AKADEMIK
                ════════════════════════════ --}}
                <section class="lg:col-span-6 flex flex-col gap-5">

                    {{-- Biodata & Keluarga --}}
                    <div class="glass rounded-2xl p-5 glow-blue">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-blue-500/15">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-blue-400 font-bold">Biodata &amp; Informasi Keluarga</span>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-2.5">
                            <div class="data-chip sm:col-span-2">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Tempat, Tanggal Lahir</div>
                                <div id="ttl" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip sm:col-span-2">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Alamat Domisili</div>
                                <div id="alamat" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Asal Sekolah</div>
                                <div id="asal" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">No. HP Siswa</div>
                                <div id="hp" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Nama Ayah</div>
                                <div id="ayah" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Nama Ibu</div>
                                <div id="ibu" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">Nama Wali</div>
                                <div id="wali" class="font-bold text-sm text-white">—</div>
                            </div>
                            <div class="data-chip">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-0.5">No. HP Orang Tua / Wali</div>
                                <div id="ortu_hp" class="font-bold text-sm text-white">—</div>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Akademik --}}
                    <div class="glass rounded-2xl p-5 glow-emerald flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-emerald-500/15">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-emerald-400 font-bold">Riwayat Akademik — e-Rapor</span>
                        </div>

                        <div id="raporTimelineList"
                             class="flex-1 overflow-y-auto custom-scroll space-y-2.5 max-h-64 pr-1">
                            <div class="text-sm text-slate-600 italic">Menunggu pemindaian kartu…</div>
                        </div>
                    </div>

                    {{-- Tren nilai chart --}}
                    <div class="glass rounded-2xl p-5 glow-emerald relative">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-emerald-400 font-bold">Tren Nilai Akademik</span>
                        </div>
                        <canvas id="trendChart" height="90"></canvas>
                        <div id="trendPlaceholder"
                             class="absolute inset-0 flex items-center justify-center text-[9px] text-slate-700 font-bold uppercase tracking-widest pointer-events-none">
                            MENUNGGU DATA
                        </div>
                    </div>
                </section>

                {{-- ════════════════════════════
                     COL C — BK + ABSENSI + EKSKUL
                ════════════════════════════ --}}
                <aside class="lg:col-span-3 flex flex-col gap-5">

                    {{-- Status BK --}}
                    <div class="glass rounded-2xl p-5 glow-red">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-red-500/15">
                            <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-red-400 font-bold">Status Kedisiplinan (BK)</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="data-chip text-center">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-1">Total Pelanggaran</div>
                                <div id="bkKasus" class="text-3xl font-black text-red-500 tabular-nums">0</div>
                            </div>
                            <div class="data-chip text-center">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-1">Poin Pelanggaran</div>
                                <div id="bkPoin" class="text-3xl font-black text-red-500 tabular-nums">0</div>
                            </div>
                            <div class="data-chip text-center col-span-2">
                                <div class="text-[9px] text-slate-500 uppercase tracking-wide mb-1">Sesi Konseling</div>
                                <div id="konseling" class="text-2xl font-black text-blue-400 tabular-nums">0</div>
                            </div>
                        </div>
                    </div>

                    {{-- Kehadiran Hari Ini --}}
                    <div class="glass rounded-2xl p-5 glow-blue">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-blue-500/15">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-blue-400 font-bold">Kehadiran Hari Ini</span>
                        </div>
                        <div id="absensiList" class="space-y-2 text-sm text-slate-300 custom-scroll max-h-48 overflow-y-auto pr-1">
                            <div class="text-sm text-slate-600 italic">Belum ada riwayat kehadiran</div>
                        </div>
                    </div>

                    {{-- Ekskul & Prestasi --}}
                    <div class="glass rounded-2xl p-5 glow-amber flex-1 flex flex-col gap-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-amber-500/15">
                            <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                            </svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-amber-400 font-bold">Aktivitas &amp; Penghargaan</span>
                        </div>

                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wide font-semibold mb-2">Ekstrakurikuler</div>
                            <div id="ekskulList" class="text-xs text-slate-300 flex flex-wrap gap-1.5">
                                <span class="text-slate-600 italic text-[11px]">—</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="text-[10px] text-slate-400 uppercase tracking-wide font-semibold mb-2">Prestasi Terbaru</div>
                            <div id="prestasiList" class="space-y-2 max-h-40 overflow-y-auto custom-scroll pr-1">
                                <div class="text-xs text-slate-600 italic">Belum ada data prestasi</div>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>{{-- /main grid --}}
        </div>{{-- /inner padding --}}
    </div>{{-- /terminal-wrapper --}}

    {{-- ═══════════════════════════════════════
         SCRIPTS
    ════════════════════════════════════════ --}}
    <script>
        /* ─── Clock ─────────────────────────────────────────── */
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        }, 1000);

        /* ─── Refs & state ───────────────────────────────────── */
        const rfidInput  = document.getElementById('rfid_input');
        const statusBox  = document.getElementById('scanStatus');
        let scanBuffer   = '';
        let scanTimeout  = null;
        let isLoading    = false;
        let chartInstance      = null;
        let trendChartInstance = null;

        const DEFAULT_AVATAR =
            "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e293b'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z'/%3E%3C/svg%3E";

        /* ─── Timeline toggle ────────────────────────────────── */
        function toggleTimeline(btn) {
            const detail = btn.nextElementSibling;
            const hidden = detail.classList.toggle('hidden');
            btn.querySelector('.toggle-label').textContent = hidden ? '▼ LIHAT RINCIAN NILAI' : '▲ SEMBUNYIKAN';
        }

        /* ─── Reset UI ───────────────────────────────────────── */
        function resetUI() {
            document.getElementById('foto').src = DEFAULT_AVATAR;

            if (chartInstance)      { chartInstance.destroy();      chartInstance      = null; }
            if (trendChartInstance) { trendChartInstance.destroy(); trendChartInstance = null; }

            ['nama','nisn','kelas','jk','ttl','hp','alamat','asal','ayah','ibu','ortu_hp','wali']
                .forEach(id => {
                    document.getElementById(id).innerText = id === 'nama' ? 'MENUNGGU PEMINDAIAN' : '—';
                });

            ['bkKasus','bkPoin','konseling'].forEach(id => {
                document.getElementById(id).innerText = '0';
            });

            document.getElementById('trendPlaceholder').style.display  = 'flex';
            document.getElementById('chartPlaceholder').style.display  = 'flex';
            document.getElementById('absensiList').innerHTML   = '<div class="text-sm text-slate-600 italic">Belum ada riwayat kehadiran</div>';
            document.getElementById('ekskulList').innerHTML    = '<span class="text-slate-600 italic text-[11px]">—</span>';
            document.getElementById('prestasiList').innerHTML  = '<div class="text-xs text-slate-600 italic">Belum ada data prestasi</div>';
            document.getElementById('raporTimelineList').innerHTML = '<div class="text-sm text-slate-600 italic">Menunggu pemindaian kartu…</div>';

            setStatus('SIAP UNTUK MEMINDAI…', 'neutral');
            rfidInput.value = '';
            rfidInput.focus();
        }

        /* ─── Status chip helper ─────────────────────────────── */
        function setStatus(text, type = 'neutral') {
            const colors = {
                neutral : 'text-slate-400',
                loading : 'text-blue-400',
                success : 'text-emerald-400',
                error   : 'text-red-400',
            };
            statusBox.className = `text-center text-[10px] font-bold uppercase tracking-[0.2em] bg-slate-900/40 rounded-lg py-2 px-3 border border-slate-700/30 ${colors[type] || colors.neutral}`;
            statusBox.innerText = text;
        }

        /* ─── Access denied flash ────────────────────────────── */
        function showDenied() {
            const denied = document.getElementById('accessDenied');
            denied.classList.add('show');
            setTimeout(() => denied.classList.remove('show'), 2500);
        }

        /* ─── Date format ────────────────────────────────────── */
        function formatTanggalIndonesia(tanggal) {
            if (!tanggal) return '—';
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                           'Juli','Agustus','September','Oktober','November','Desember'];
            const parts = tanggal.split('-');
            if (parts.length !== 3) return tanggal;
            return `${parseInt(parts[2],10)} ${bulan[parseInt(parts[1],10)-1]} ${parts[0]}`;
        }

        /* ─── Load siswa ─────────────────────────────────────── */
        async function loadSiswa(uid) {
            if (!uid) return;
            setStatus('MENCARI DATA SISWA…', 'loading');

            try {
                const res  = await fetch(`/api/scan-siswa?uid=${encodeURIComponent(uid)}`);
                const json = await res.json();

                if (!json.status) {
                    showDenied();
                    setStatus('AKSES DITOLAK — KARTU TIDAK DIKENAL', 'error');
                    rfidInput.value = '';
                    return;
                }

                const d = json.data;

                /* ── Basic info ── */
                document.getElementById('foto').src          = d.foto || DEFAULT_AVATAR;
                document.getElementById('nama').innerText    = d.nama || '—';
                document.getElementById('nisn').innerText    = d.nisn || '—';
                document.getElementById('kelas').innerText   = d.kelas || '—';
                document.getElementById('jk').innerText      = d.jenis_kelamin || '—';
                document.getElementById('ttl').innerText     = (d.tempat_lahir || '—').toUpperCase() + ', ' + formatTanggalIndonesia(d.tanggal_lahir);
                document.getElementById('hp').innerText      = d.no_hp || '—';
                document.getElementById('alamat').innerText  = d.alamat ? d.alamat.toUpperCase() : '—';
                document.getElementById('asal').innerText    = d.sekolah_asal ? d.sekolah_asal.toUpperCase() : '—';
                document.getElementById('ayah').innerText    = d.nama_ayah || '—';
                document.getElementById('ibu').innerText     = d.nama_ibu || '—';
                document.getElementById('ortu_hp').innerText = d.telpon_ortu || '—';
                document.getElementById('wali').innerText    = d.nama_wali || '—';

                /* ── BK ── */
                document.getElementById('bkKasus').innerText  = d.bk.total_kasus  || 0;
                document.getElementById('bkPoin').innerText   = d.bk.total_poin   || 0;
                document.getElementById('konseling').innerText = d.konseling       || 0;

                /* ── Absensi ── */
                document.getElementById('absensiList').innerHTML = d.absensi.length
                    ? d.absensi.map(item => `
                        <div class="data-chip flex justify-between items-center py-2">
                            <div>
                                <span class="font-bold text-white text-xs">${item.jenis}</span>
                                <span class="text-[10px] text-slate-500 block">${item.jam} WIB</span>
                            </div>
                            <span class="bg-blue-900/40 text-blue-300 text-[9px] px-2 py-1 rounded-lg border border-blue-500/20">${item.status}</span>
                        </div>
                    `).join('')
                    : '<div class="text-sm text-slate-600 italic">Belum ada riwayat kehadiran</div>';

                /* ── Ekskul ── */
                document.getElementById('ekskulList').innerHTML = d.ekskul.length
                    ? d.ekskul.map(item =>
                        `<span class="bg-amber-900/30 text-amber-300 text-[10px] px-2.5 py-1 rounded-lg border border-amber-500/20 font-semibold">${item}</span>`
                      ).join('')
                    : '<span class="text-slate-600 italic text-[11px]">Tidak mengikuti ekstrakurikuler</span>';

                /* ── Prestasi ── */
                document.getElementById('prestasiList').innerHTML = d.prestasi.length
                    ? d.prestasi.map(item => `
                        <div class="data-chip py-2">
                            <div class="text-xs font-bold text-white">${item.nama_lomba}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5">Tingkat ${item.tingkat} &bull; Tahun ${item.tahun}</div>
                        </div>
                    `).join('')
                    : '<div class="text-xs text-slate-600 italic">Belum ada data prestasi</div>';

                /* ── Rapor timeline ── */
                document.getElementById('raporTimelineList').innerHTML = d.rapor_timeline.length
                    ? d.rapor_timeline.map(item => `
                        <div class="data-chip">
                            <div class="flex justify-between items-center cursor-pointer" onclick="toggleTimeline(this)">
                                <div>
                                    <div class="font-bold text-sm text-white">TA ${item.tahun}</div>
                                    <div class="text-[10px] text-slate-500">Semester ${item.semester}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[9px] text-slate-500 uppercase mb-0.5">Rata-Rata</div>
                                    <div class="text-xl font-black text-emerald-400 leading-none tabular-nums">${item.average}</div>
                                    <div class="toggle-label text-[9px] mt-1.5 text-emerald-600 hover:text-emerald-300 uppercase tracking-wide">▼ LIHAT RINCIAN NILAI</div>
                                </div>
                            </div>
                            <div class="hidden mt-3 pt-3 border-t border-slate-700/50 space-y-1.5">
                                ${item.detail.map(m => `
                                    <div class="flex justify-between text-xs gap-2">
                                        <span class="text-slate-400 truncate">${m.mapel}</span>
                                        <span class="font-bold text-amber-400 shrink-0">${m.nilai}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')
                    : '<div class="text-sm text-slate-600 italic">Belum ada data nilai e-Rapor</div>';

                setStatus('✓ PROFIL BERHASIL DITEMUKAN', 'success');
                rfidInput.value = '';

                renderRadarChart(d);
                renderTrendChart(d);

            } catch (err) {
                console.error(err);
                setStatus('KESALAHAN SISTEM / DATABASE TERPUTUS', 'error');
            }
        }

        /* ─── Radar chart ────────────────────────────────────── */
        function renderRadarChart(d) {
            const ctx = document.getElementById('radarChart').getContext('2d');
            document.getElementById('chartPlaceholder').style.display = 'none';
            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Kehadiran','Disiplin','Akademik','Bakat','Prestasi'],
                    datasets: [{
                        label: 'Profil Siswa',
                        data: [
                            d.absensi.length > 0 ? 80 : 50,
                            100 - Math.min(d.bk.total_poin, 100),
                            d.rapor_timeline.length > 0 ? d.rapor_timeline[0].average : 75,
                            d.ekskul.length > 0 ? 90 : 40,
                            d.prestasi.length > 0 ? 85 : 30,
                        ],
                        backgroundColor : 'rgba(16,185,129,0.15)',
                        borderColor     : '#10b981',
                        borderWidth     : 2,
                        pointBackgroundColor : '#10b981',
                        pointRadius     : 3,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            min: 0, max: 100,
                            grid:        { color: 'rgba(255,255,255,0.07)' },
                            angleLines:  { color: 'rgba(255,255,255,0.07)' },
                            pointLabels: { color: '#64748b', font: { size: 9 } },
                            ticks:       { display: false },
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        /* ─── Trend chart ────────────────────────────────────── */
        function renderTrendChart(d) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            if (trendChartInstance) trendChartInstance.destroy();

            const labels     = d.rapor_timeline.map(i => `${i.tahun} - S${i.semester}`);
            const dataPoints = d.rapor_timeline.map(i => i.average);

            document.getElementById('trendPlaceholder').style.display = 'none';

            trendChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Rata-Rata',
                        data: dataPoints,
                        borderColor         : '#10b981',
                        backgroundColor     : 'rgba(16,185,129,0.08)',
                        fill                : true,
                        tension             : 0.4,
                        pointRadius         : 4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor    : '#fff',
                        pointBorderWidth    : 1.5,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 0, max: 100,
                            grid:  { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#475569', font: { size: 9 } },
                        },
                        x: {
                            grid:  { display: false },
                            ticks: { color: '#475569', font: { size: 9 } },
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        /* ─── RFID hardware scan ─────────────────────────────── */
        document.addEventListener('keydown', function(e) {
            if (isLoading) return;
            if (e.key === 'Enter') {
                const uid = scanBuffer.trim();
                if (uid.length >= 6) {
                    isLoading = true;
                    loadSiswa(uid).finally(() => isLoading = false);
                }
                scanBuffer = '';
                return;
            }
            if (/^[0-9a-zA-Z]$/.test(e.key)) {
                scanBuffer += e.key;
                clearTimeout(scanTimeout);
                scanTimeout = setTimeout(() => scanBuffer = '', 300);
            }
        });

        rfidInput.addEventListener('change', function() {
            loadSiswa(this.value.trim());
        });

        /* ─── NFC scan (mobile) ──────────────────────────────── */
        window.scanNFC = async function() {
            if (!('NDEFReader' in window)) {
                alert('Browser ini tidak mendukung NFC. Gunakan Google Chrome di HP Android.');
                return;
            }
            try {
                setStatus('PEMINDAI NFC AKTIF — TEMPELKAN KARTU…', 'loading');
                const ndef = new NDEFReader();
                await ndef.scan();
                ndef.onreading = async (event) => {
                    let uid = event.serialNumber || '';
                    if (!uid) return setStatus('KARTU TIDAK TERBACA', 'error');
                    if (uid.includes(':')) {
                        const parts = uid.split(':').map(x => x.trim()).reverse();
                        uid = parseInt(parts.join(''), 16).toString();
                    }
                    rfidInput.value = uid;
                    loadSiswa(uid);
                };
            } catch (err) {
                console.error(err);
                setStatus('PEMINDAIAN NFC GAGAL', 'error');
            }
        };
    </script>

</body>
</html>
