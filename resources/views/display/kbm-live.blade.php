<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live TV Monitoring KBM - MTsN 3 Tulungagung</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        kiosk: {
                            bg: '#090d16',
                            card: '#111827',
                            border: '#1f293d',
                            green: '#10b981',
                            red: '#ef4444',
                            yellow: '#f59e0b',
                            blue: '#3b82f6',
                        }
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 15px rgba(239, 68, 68, 0.4)', borderColor: '#ef4444' },
                            '50%': { boxShadow: '0 0 25px rgba(239, 68, 68, 0.8)', borderColor: '#f87171' },
                        },
                        subtleGlow: {
                            '0%, 100%': { borderColor: 'rgba(16, 185, 129, 0.4)' },
                            '50%': { borderColor: 'rgba(16, 185, 129, 0.9)' },
                        }
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 1.5s infinite',
                        'subtle-glow': 'subtleGlow 3s infinite',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #080c14;
            color: #f3f4f6;
            overflow-x: hidden;
            user-select: none;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between p-3 lg:p-4 text-slate-100">

    <!-- TOP BAR: IDENTITAS & JAM DIGITAL -->
    <header class="bg-slate-900/90 border border-slate-800 rounded-2xl p-3 lg:px-6 lg:py-3.5 backdrop-blur-md shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 mb-3">
        <!-- Logo & Title -->
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-xl lg:text-2xl font-black tracking-tight text-white uppercase">MONITORING KBM</h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">LIVE</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium tracking-wide">MTsN 3 Tulungagung • Ruang Guru Display</p>
            </div>
        </div>

        <!-- Center: Slot Status Banner -->
        <div id="slot-banner" class="flex items-center gap-3 bg-slate-950/80 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl border border-slate-800 w-full sm:w-auto justify-center sm:justify-start">
            <div class="relative flex h-3 w-3 sm:h-3.5 sm:w-3.5 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 sm:h-3.5 sm:w-3.5 bg-emerald-500"></span>
            </div>
            <div class="text-left">
                <div id="slot-title" class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider">Memuat Slot...</div>
                <div id="slot-desc" class="text-xs sm:text-sm lg:text-base font-extrabold text-white">Mendeteksi Waktu KBM...</div>
            </div>
        </div>

        <!-- Right: Digital Clock & Fullscreen -->
        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
            <div class="text-left sm:text-right">
                <div id="digital-clock" class="text-xl sm:text-2xl lg:text-3xl font-extrabold font-mono-num text-emerald-400 tracking-wider">00:00:00</div>
                <div id="digital-date" class="text-[11px] sm:text-xs text-slate-400 font-medium">Memuat Tanggal...</div>
            </div>
            <button onclick="toggleFullscreen()" title="Toggle Fullscreen" class="p-2 sm:p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition border border-slate-700/60 shadow-md shrink-0">
                <svg id="fullscreen-icon" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- QUICK STATS BAR -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 mb-3">
        <!-- Total Kelas -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-2.5 px-3.5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Kelas</p>
                <p id="stat-total" class="text-2xl font-black font-mono-num text-white">33</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
        <!-- Hadir / Mengajar -->
        <div class="bg-emerald-950/30 border border-emerald-900/50 rounded-xl p-2.5 px-3.5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400">Sudah Masuk</p>
                <p id="stat-hadir" class="text-2xl font-black font-mono-num text-emerald-400">0</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-900/40 flex items-center justify-center text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <!-- Belum Masuk -->
        <div class="bg-rose-950/30 border border-rose-900/50 rounded-xl p-2.5 px-3.5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-400">Belum Masuk</p>
                <p id="stat-belum" class="text-2xl font-black font-mono-num text-rose-400">0</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-rose-900/40 flex items-center justify-center text-rose-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
        <!-- Guru Izin -->
        <div class="bg-amber-950/30 border border-amber-900/50 rounded-xl p-2.5 px-3.5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-amber-400">Guru Izin</p>
                <p id="stat-izin" class="text-2xl font-black font-mono-num text-amber-400">0</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-900/40 flex items-center justify-center text-amber-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <!-- Jam Kosong -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-2.5 px-3.5 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Jam Kosong</p>
                <p id="stat-kosong" class="text-2xl font-black font-mono-num text-slate-300">0</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
        </div>
    </div>

    <!-- MAIN GRID CONTAINER: KELAS 7, 8, 9 -->
    <main id="classes-container" class="flex-1 space-y-3 overflow-y-auto">
        <!-- Rendered dynamically via JavaScript from initialData & AJAX Polling -->
    </main>

    <!-- FOOTER BAR: KETERANGAN & STATUS KONEKSI -->
    <footer class="mt-3 bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-2 flex flex-wrap items-center justify-between text-xs text-slate-400 gap-2">
        <div class="flex items-center gap-4 flex-wrap">
            <span class="font-bold text-slate-300">Indikator:</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Sudah di Kelas</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-white inline-block"></span> Jurnal Terisi</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> Menunggu Guru (≤10 mnt)</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span> Belum Masuk (>10 mnt)</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500 inline-block"></span> Guru Izin</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-600 inline-block"></span> Jam Kosong / Break</span>
        </div>
        <div class="flex items-center gap-2">
            <span id="last-update" class="font-mono-num text-[11px]">Update: -</span>
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping" title="Sync Aktif"></span>
        </div>
    </footer>

    <!-- CLIENT SCRIPT -->
    <script>
        let currentData = @json($initialData);

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('digital-clock').innerText = `${hours}:${minutes}:${seconds} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Render Data into DOM
        function renderMonitoring(data) {
            if (!data) return;

            // Date & Slot Banner
            document.getElementById('digital-date').innerText = data.tanggal_id || '';
            const slot = data.slot || {};
            const slotTitleEl = document.getElementById('slot-title');
            const slotDescEl = document.getElementById('slot-desc');

            if (slot.is_kbm) {
                slotTitleEl.innerText = `KBM AKTIF: ${slot.label}`;
                slotDescEl.innerText = `${slot.start} - ${slot.end} (${slot.remaining_minutes} mnt tersisa)`;
            } else if (slot.is_break) {
                slotTitleEl.innerText = 'WAKTU ISTIRAHAT / UBUDIYAH';
                slotDescEl.innerText = `${slot.label} (${slot.start} - ${slot.end})`;
            } else {
                slotTitleEl.innerText = 'STATUS KBM';
                slotDescEl.innerText = slot.label || 'Di Luar Jam KBM';
            }

            // Summary Stats
            const summary = data.summary || {};
            document.getElementById('stat-total').innerText = summary.total_kelas ?? 33;
            document.getElementById('stat-hadir').innerText = summary.total_hadir ?? 0;
            document.getElementById('stat-belum').innerText = summary.total_belum_masuk ?? 0;
            document.getElementById('stat-izin').innerText = summary.total_izin ?? 0;
            document.getElementById('stat-kosong').innerText = summary.total_kosong ?? 0;

            // Render Groups (Kelas 7, Kelas 8, Kelas 9)
            const grouped = data.grouped || {};
            const container = document.getElementById('classes-container');
            container.innerHTML = '';

            const tingkats = Object.keys(grouped).sort();

            tingkats.forEach(tingkat => {
                const classesInTingkat = grouped[tingkat] || [];

                const section = document.createElement('div');
                section.className = 'bg-slate-900/60 border border-slate-800/80 rounded-2xl p-2.5 lg:p-3 shadow-xl backdrop-blur-sm';

                // Section Header
                const sectionHeader = document.createElement('div');
                sectionHeader.className = 'flex items-center justify-between mb-2.5 px-1';
                sectionHeader.innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                        <h2 class="text-sm lg:text-base font-extrabold text-white tracking-wide uppercase">KELAS ${tingkat}</h2>
                        <span class="text-xs text-slate-400 font-medium font-mono-num">(${classesInTingkat.length} Rombel)</span>
                    </div>
                `;
                section.appendChild(sectionHeader);

                // Grid of Classes in Tingkat (11 columns responsive)
                const grid = document.createElement('div');
                grid.className = 'grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2';

                classesInTingkat.forEach(item => {
                    const card = createClassCard(item);
                    grid.appendChild(card);
                });

                section.appendChild(grid);
                container.appendChild(section);
            });

            // Update timestamp
            const now = new Date();
            document.getElementById('last-update').innerText = `Update: ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
        }

        // Create Individual Class Card
        function createClassCard(item) {
            const card = document.createElement('div');

            let borderClass = 'border-slate-800';
            let bgClass = 'bg-slate-900/90';
            let badgeColorClass = 'bg-slate-800 text-slate-300';
            let animClass = '';

            switch (item.status_code) {
                case 'HADIR_LENGKAP':
                    borderClass = 'border-emerald-500/80 ring-1 ring-emerald-500/40';
                    bgClass = 'bg-gradient-to-b from-emerald-950/40 to-slate-900';
                    badgeColorClass = 'bg-emerald-500 text-slate-950 font-black';
                    break;
                case 'HADIR':
                    borderClass = 'border-emerald-600/60 animate-subtle-glow';
                    bgClass = 'bg-emerald-950/30';
                    badgeColorClass = 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                    break;
                case 'BELUM_MASUK':
                    borderClass = 'border-rose-500 animate-pulse-glow';
                    bgClass = 'bg-rose-950/40';
                    badgeColorClass = 'bg-rose-500 text-white font-black';
                    break;
                case 'MENUNGGU':
                    borderClass = 'border-amber-500/60';
                    bgClass = 'bg-amber-950/30';
                    badgeColorClass = 'bg-amber-500/20 text-amber-300 border border-amber-500/40';
                    break;
                case 'IZIN':
                    borderClass = 'border-yellow-500/50';
                    bgClass = 'bg-yellow-950/20';
                    badgeColorClass = 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/40';
                    break;
                case 'BREAK':
                    borderClass = 'border-blue-900/40';
                    bgClass = 'bg-blue-950/20';
                    badgeColorClass = 'bg-blue-500/20 text-blue-300';
                    break;
                default:
                    borderClass = 'border-slate-800/80';
                    bgClass = 'bg-slate-900/60';
                    badgeColorClass = 'bg-slate-800 text-slate-400';
            }

            card.className = `relative rounded-xl border ${borderClass} ${bgClass} p-2.5 flex flex-col justify-between transition-all duration-300 shadow-md ${animClass} min-h-[105px]`;

            // Card Header: Nama Kelas & Status Badge
            const headerHtml = `
                <div class="flex items-center justify-between gap-1 mb-1.5">
                    <span class="text-base font-black tracking-tight text-white">${item.nama_kelas}</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded uppercase font-bold tracking-wider ${badgeColorClass}">
                        ${item.status_code === 'HADIR_LENGKAP' ? '✓ HADIR' : (item.status_code === 'BELUM_MASUK' ? 'BELUM' : item.status_label)}
                    </span>
                </div>
            `;

            // Card Body: Guru & Mapel
            let guruDisplay = item.guru_nama || '-';
            if (guruDisplay.length > 20) {
                guruDisplay = guruDisplay.substring(0, 18) + '...';
            }

            let mapelDisplay = item.mapel || '-';
            if (mapelDisplay.length > 20) {
                mapelDisplay = mapelDisplay.substring(0, 18) + '...';
            }

            const bodyHtml = `
                <div class="space-y-0.5 flex-1">
                    <p class="text-[12px] font-bold text-slate-100 leading-tight truncate" title="${item.guru_nama || ''}">${guruDisplay}</p>
                    <p class="text-[10px] text-slate-400 font-medium truncate" title="${item.mapel || ''}">${mapelDisplay}</p>
                </div>
            `;

            // Card Footer: Checkin info / Jurnal status
            let footerDetail = '';
            if (item.status_code === 'HADIR' || item.status_code === 'HADIR_LENGKAP') {
                footerDetail = `
                    <div class="flex items-center justify-between text-[9px] text-emerald-400 font-mono-num mt-1 pt-1 border-t border-emerald-900/40">
                        <span>${item.waktu_presensi ? item.waktu_presensi + ' WIB' : 'Hadir'}</span>
                        <span>${item.metode ? item.metode.toUpperCase() : ''}</span>
                    </div>
                `;
            } else if (item.status_code === 'BELUM_MASUK') {
                footerDetail = `
                    <div class="flex items-center justify-between text-[9px] text-rose-400 font-bold mt-1 pt-1 border-t border-rose-900/40">
                        <span>Piket Harap Cek!</span>
                        <span class="animate-ping w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    </div>
                `;
            } else if (item.status_code === 'MENUNGGU') {
                footerDetail = `
                    <div class="text-[9px] text-amber-400 font-medium mt-1 pt-1 border-t border-amber-900/40">
                        Toleransi awal jam
                    </div>
                `;
            } else {
                footerDetail = `
                    <div class="text-[9px] text-slate-500 font-medium mt-1 pt-1 border-t border-slate-800">
                        ${item.status_code === 'BREAK' ? 'Istirahat' : (item.status_code === 'IZIN' ? 'Ada Tugas' : '-')}
                    </div>
                `;
            }

            card.innerHTML = headerHtml + bodyHtml + footerDetail;
            return card;
        }

        // Fullscreen Toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error("Fullscreen error:", err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Polling Data Every 15 Seconds
        async function fetchUpdate() {
            try {
                const response = await fetch(window.location.href + (window.location.search ? '&json=1' : '?json=1'), {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const data = await response.json();
                    currentData = data;
                    renderMonitoring(data);
                }
            } catch (err) {
                console.warn("Polling update failed:", err);
            }
        }

        // Initial Render
        renderMonitoring(currentData);

        // Interval Polling: 15 seconds
        setInterval(fetchUpdate, 15000);
    </script>
</body>
</html>
