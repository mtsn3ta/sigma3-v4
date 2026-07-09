<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Global Madrasah</title>

    {{-- Memanggil Tailwind bawaan project Laravel Anda --}}
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #020617;
            /* Slate 950 fallback */
            margin: 0;
            overflow-x: hidden;
        }

        .terminal-wrapper {
            background: radial-gradient(circle at top left, rgba(34, 197, 94, .1), transparent 40%),
                radial-gradient(circle at bottom right, rgba(59, 130, 246, .1), transparent 40%),
                #020617;
            color: #fff;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .terminal-wrapper::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .glow-green {
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .glow-blue {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .glow-red {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .glow-yellow {
            box-shadow: 0 0 15px rgba(234, 179, 8, 0.15);
            border-color: rgba(234, 179, 8, 0.3);
        }

        .data-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 12px;
        }

        .alert-screen {
            display: none;
        }

        .alert-screen.show {
            display: flex;
            animation: pulseRed 1s infinite alternate;
        }

        @keyframes pulseRed {
            from {
                background: rgba(15, 23, 42, 0.95);
            }

            to {
                background: rgba(80, 10, 10, 0.95);
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-white">

    <div class="terminal-wrapper p-4 lg:p-6">

        <div id="accessDenied"
            class="alert-screen fixed inset-0 z-50 flex-col items-center justify-center backdrop-blur-sm">
            <div
                class="text-red-500 text-5xl md:text-7xl font-black tracking-widest drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]">
                AKSES DITOLAK</div>
            <div class="mt-4 text-slate-300 text-xl font-mono">DATA SISWA TIDAK DITEMUKAN</div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="glass-panel rounded-xl p-3 glow-green">
                    @if (\App\Support\SettingHelper::schoolLogo())
                        <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolLogo()) }}"
                            alt="Logo Madrasah"
                            style="display: block; width: 110px; height: auto; margin: 0 auto 20px auto;">
                    @endif
                </div>
                <div>
                    <div class="text-[10px] tracking-widest text-green-400 font-bold uppercase">Terminal Informasi
                        Cerdas</div>
                    <h1 class="text-2xl md:text-3xl font-black text-white">PEMINDAI PROFIL SIGMA-3</h1>
                </div>
            </div>

            <div class="flex gap-3 relative z-10">
                <a href="/"
                    class="glass-panel rounded-xl px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 flex items-center transition-all">
                    <span class="text-xs font-bold tracking-wider">KEMBALI KE BERANDA</span>
                </a>

                <div class="glass-panel rounded-xl px-6 py-3 glow-green text-right text-white">
                    <div id="clock" class="text-2xl font-black text-green-400 font-mono tracking-wider">00:00:00
                    </div>
                    <div id="date" class="text-xs text-slate-400 mt-1 uppercase font-semibold">MEMUAT WAKTU...
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-6 relative z-10">

            <div class="lg:col-span-3 flex flex-col gap-6">
                <div class="glass-panel rounded-2xl p-5 glow-blue">
                    <div class="text-[10px] uppercase tracking-widest text-blue-400 font-bold mb-3">Input Sistem</div>
                    <input id="rfid_input" type="text"
                        class="w-full bg-slate-900/50 border border-blue-500/30 rounded-xl p-4 text-center text-lg text-white outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 transition-all font-mono placeholder:text-slate-600"
                        placeholder="TEMPELKAN KARTU (RFID/NFC)" autofocus>

                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <button onclick="scanNFC()"
                            class="bg-linear-to-r from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 text-white text-xs font-bold py-3 rounded-xl transition-all shadow-lg shadow-green-900/50">
                            NFC HP
                        </button>
                        <button onclick="resetUI()"
                            class="bg-linear-to-r from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white text-xs font-bold py-3 rounded-xl transition-all">
                            BERSIHKAN
                        </button>
                    </div>
                    <div id="scanStatus"
                        class="mt-4 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                        SIAP UNTUK MEMINDAI...
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 glow-green flex flex-col items-center">
                    <div
                        class="w-48 h-60 rounded-xl overflow-hidden border-2 border-green-500/30 bg-slate-900 relative shadow-[0_0_20px_rgba(34,197,94,0.1)] flex items-center justify-center">
                        <img id="foto"
                            src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23334155'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z'/%3E%3C/svg%3E"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="mt-4 w-full text-center">
                        <div id="nama" class="text-xl font-bold text-white uppercase wrap-break-words">MENUNGGU
                            PEMINDAIAN
                        </div>
                        <div id="kelas" class="text-sm text-green-400 font-semibold mt-1">-</div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 w-full mt-4">
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">NISN</div>
                            <div id="nisn" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">Jenis Kelamin</div>
                            <div id="jk" class="font-bold text-sm text-white">-</div>
                        </div>
                    </div>
                    <div class="glass-panel rounded-2xl p-4 glow-yellow w-full mt-4 relative">
                        <canvas id="radarChart" height="200"></canvas>

                        <div id="chartPlaceholder"
                            class="absolute inset-0 flex items-center justify-center text-[10px] text-slate-600 font-bold tracking-widest uppercase">
                            MENUNGGU DATA
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 flex flex-col gap-6">
                <div class="glass-panel rounded-2xl p-5 glow-blue">
                    <div
                        class="text-[10px] uppercase tracking-widest text-blue-400 font-bold mb-4 border-b border-blue-500/20 pb-2">
                        Biodata & Informasi Keluarga</div>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="data-card md:col-span-2">
                            <div class="text-[10px] text-slate-400 uppercase">Tempat, Tanggal Lahir</div>
                            <div id="ttl" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card md:col-span-2">
                            <div class="text-[10px] text-slate-400 uppercase">Alamat Domisili</div>
                            <div id="alamat" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">Asal Sekolah</div>
                            <div id="asal" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">No. HP Siswa</div>
                            <div id="hp" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">Nama Ayah</div>
                            <div id="ayah" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">Nama Ibu</div>
                            <div id="ibu" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">Nama Wali</div>
                            <div id="wali" class="font-bold text-sm text-white">-</div>
                        </div>
                        <div class="data-card">
                            <div class="text-[10px] text-slate-400 uppercase">No. HP Orang Tua / Wali</div>
                            <div id="ortu_hp" class="font-bold text-sm text-white">-</div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 glow-green flex-1 flex flex-col">
                    <div
                        class="text-[10px] uppercase tracking-widest text-green-400 font-bold mb-4 border-b border-green-500/20 pb-2">
                        Riwayat Akademik (Berdasarkan e-Rapor)</div>
                    <div id="raporTimelineList" class="flex-1 overflow-y-auto pr-2 space-y-3 max-h-100">
                        <div class="text-sm text-slate-500 italic">Menunggu pemindaian kartu...</div>
                    </div>
                </div>
                <div class="glass-panel rounded-2xl p-4 glow-green w-full mt-4 relative">
                    <div class="text-[10px] uppercase tracking-widest text-green-400 font-bold mb-2">Tren Nilai
                        Akademik</div>
                    <canvas id="trendChart" height="100"></canvas>
                    <div id="trendPlaceholder"
                        class="absolute inset-0 flex items-center justify-center text-[10px] text-slate-600 font-bold uppercase">
                        MENUNGGU DATA
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 flex flex-col gap-6">
                <div class="glass-panel rounded-2xl p-5 glow-red">
                    <div
                        class="text-[10px] uppercase tracking-widest text-red-400 font-bold mb-4 border-b border-red-500/20 pb-2">
                        Status Kedisiplinan (BK)</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="data-card text-center">
                            <div class="text-[10px] text-slate-400 uppercase">Total Pelanggaran</div>
                            <div id="bkKasus" class="text-3xl font-black text-red-500">0</div>
                        </div>
                        <div class="data-card text-center">
                            <div class="text-[10px] text-slate-400 uppercase">Poin Pelanggaran</div>
                            <div id="bkPoin" class="text-3xl font-black text-red-500">0</div>
                        </div>
                        <div class="data-card text-center col-span-2">
                            <div class="text-[10px] text-slate-400 uppercase">Jumlah Sesi Konseling</div>
                            <div id="konseling" class="text-2xl font-black text-blue-400">0</div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 glow-blue">
                    <div
                        class="text-[10px] uppercase tracking-widest text-blue-400 font-bold mb-3 border-b border-blue-500/20 pb-2">
                        Kehadiran Hari Ini</div>
                    <div id="absensiList" class="space-y-2 text-sm text-slate-300">
                        <div class="text-sm text-slate-500 italic">Belum ada riwayat kehadiran</div>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 glow-yellow flex-1">
                    <div
                        class="text-[10px] uppercase tracking-widest text-yellow-400 font-bold mb-3 border-b border-yellow-500/20 pb-2">
                        Aktivitas & Penghargaan</div>

                    <div class="mb-2 text-xs font-semibold text-white">Ekstrakurikuler yang Diikuti:</div>
                    <div id="ekskulList" class="text-xs text-slate-300 mb-4 flex flex-wrap gap-1">
                        -
                    </div>

                    <div class="mb-2 text-xs font-semibold text-white">Prestasi Terbaru:</div>
                    <div id="prestasiList" class="space-y-2 max-h-37.5 overflow-y-auto pr-1">
                        <div class="text-xs text-slate-500 italic">Belum ada data prestasi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rfidInput = document.getElementById('rfid_input');
        const statusBox = document.getElementById('scanStatus');
        let scanBuffer = '';
        let scanTimeout = null;
        let isLoading = false;

        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }, 1000);

        function toggleTimeline(btn) {
            const detail = btn.nextElementSibling;
            if (detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
                btn.innerHTML = '▲ SEMBUNYIKAN NILAI';
            } else {
                detail.classList.add('hidden');
                btn.innerHTML = '▼ LIHAT RINCIAN NILAI';
            }
        }
        const DEFAULT_AVATAR =
            "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23334155'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z'/%3E%3C/svg%3E";

        function resetUI() {
            // 1. Reset Foto
            document.getElementById('foto').src = DEFAULT_AVATAR;

            // 2. Hancurkan Chart jika ada
            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null; // Reset ke null agar canvas kosong
            }

            // 3. Reset Data Text
            ['nama', 'nisn', 'kelas', 'jk', 'ttl', 'hp', 'alamat', 'asal', 'ayah', 'ibu', 'ortu_hp', 'wali'].forEach(id => {
                document.getElementById(id).innerText = (id === 'nama') ? 'MENUNGGU PEMINDAIAN' : '-';
            });

            // 4. Reset Statistik
            ['bkKasus', 'bkPoin', 'konseling'].forEach(id => {
                document.getElementById(id).innerText = '0';
            });
            if (trendChartInstance) {
                trendChartInstance.destroy();
                trendChartInstance = null;
            }
            document.getElementById('trendPlaceholder').style.display = 'flex';

            // 5. Reset List
            document.getElementById('absensiList').innerHTML =
                '<div class="text-sm text-slate-500 italic">Belum ada riwayat kehadiran</div>';
            document.getElementById('ekskulList').innerHTML = '-';
            document.getElementById('prestasiList').innerHTML =
                '<div class="text-xs text-slate-500 italic">Belum ada data prestasi</div>';
            document.getElementById('raporTimelineList').innerHTML =
                '<div class="text-sm text-slate-500 italic">Menunggu pemindaian kartu...</div>';
            document.getElementById('chartPlaceholder').style.display = 'flex'; // Tampilkan teks saat reset
            statusBox.innerHTML = 'SIAP UNTUK MEMINDAI...';
            rfidInput.value = '';
            rfidInput.focus();
        }

        function showDenied() {
            const denied = document.getElementById('accessDenied');
            denied.classList.add('show');
            setTimeout(() => denied.classList.remove('show'), 2500);
        }

        function formatTanggalIndonesia(tanggal) {
            if (!tanggal) return '-';
            const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const parts = tanggal.split('-');
            if (parts.length !== 3) return tanggal;
            return `${parseInt(parts[2],10)} ${bulan[parseInt(parts[1],10) - 1]} ${parts[0]}`;
        }
        let chartInstance = null;
        let trendChartInstance = null;
        async function loadSiswa(uid) {
            if (!uid) return;
            statusBox.innerHTML = 'MENCARI DATA SISWA...';

            try {
                const res = await fetch(`/api/scan-siswa?uid=${encodeURIComponent(uid)}`);
                const json = await res.json();

                if (!json.status) {
                    showDenied();
                    statusBox.innerHTML = 'AKSES DITOLAK';
                    rfidInput.value = '';
                    return;
                }

                const d = json.data;

                // Cari baris ini di dalam loadSiswa:
                document.getElementById('foto').src = d.foto || DEFAULT_AVATAR;
                document.getElementById('nama').innerText = d.nama || '-';
                document.getElementById('nisn').innerText = d.nisn || '-';
                document.getElementById('kelas').innerText = d.kelas || '-';
                document.getElementById('jk').innerText = d.jenis_kelamin || '-';
                document.getElementById('ttl').innerText = (d.tempat_lahir || '-').toUpperCase() + ', ' +
                    formatTanggalIndonesia(d.tanggal_lahir);
                document.getElementById('hp').innerText = d.no_hp || '-';
                document.getElementById('alamat').innerText = d.alamat ? d.alamat.toUpperCase() : '-';
                document.getElementById('asal').innerText = d.sekolah_asal ? d.sekolah_asal.toUpperCase() : '-';

                document.getElementById('ayah').innerText = d.nama_ayah || '-';
                document.getElementById('ibu').innerText = d.nama_ibu || '-';
                document.getElementById('ortu_hp').innerText = d.telpon_ortu || '-';
                document.getElementById('wali').innerText = d.nama_wali || '-';

                document.getElementById('bkKasus').innerText = d.bk.total_kasus || 0;
                document.getElementById('bkPoin').innerText = d.bk.total_poin || 0;
                document.getElementById('konseling').innerText = d.konseling || 0;

                let absenHtml = d.absensi.length ? d.absensi.map(item => `
                    <div class="data-card !py-2 flex justify-between items-center">
                        <div><span class="font-bold text-white text-xs">${item.jenis}</span> <span class="text-[10px] text-slate-400 block">${item.jam} WIB</span></div>
                        <span class="bg-blue-900/50 text-blue-300 text-[10px] px-2 py-1 rounded border border-blue-500/30">${item.status}</span>
                    </div>
                `).join('') : '<div class="text-sm text-slate-500 italic">Belum ada riwayat kehadiran</div>';
                document.getElementById('absensiList').innerHTML = absenHtml;

                document.getElementById('ekskulList').innerHTML = d.ekskul.length ? d.ekskul.map(item =>
                    `<span class="bg-slate-800 px-2 py-1 rounded text-[10px] border border-slate-700">${item}</span>`
                ).join('') : 'Tidak mengikuti ekstrakurikuler';

                document.getElementById('prestasiList').innerHTML = d.prestasi.length ? d.prestasi.map(item => `
                    <div class="data-card !py-2">
                        <div class="text-xs font-bold text-white">${item.nama_lomba}</div>
                        <div class="text-[10px] text-slate-400">Tingkat ${item.tingkat} • Tahun ${item.tahun}</div>
                    </div>
                `).join('') : '<div class="text-xs text-slate-500 italic">Belum ada data prestasi</div>';

                let raporHtml = d.rapor_timeline.length ? d.rapor_timeline.map(item => `
                    <div class="data-card mb-3">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleTimeline(this)">
                            <div>
                                <div class="font-bold text-sm text-white">Tahun Ajaran ${item.tahun}</div>
                                <div class="text-[10px] text-slate-400">Semester ${item.semester}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-slate-400 uppercase mb-1">Rata-Rata Nilai</div>
                                <div class="text-lg font-black text-green-400 leading-none">${item.average}</div>
                                <div class="text-[9px] mt-2 text-green-600 uppercase hover:text-green-300">▼ LIHAT RINCIAN NILAI</div>
                            </div>
                        </div>
                        <div class="hidden mt-3 pt-3 border-t border-slate-700 space-y-1">
                            ${item.detail.map(m => `
                                                                                                                                                                                            <div class="flex justify-between text-xs">
                                                                                                                                                                                                <span class="text-slate-300 truncate w-3/4">${m.mapel}</span>
                                                                                                                                                                                                <span class="font-bold text-yellow-400">${m.nilai}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        `).join('')}
                        </div>
                    </div>
                `).join('') : '<div class="text-sm text-slate-500 italic">Belum ada data nilai e-Rapor</div>';
                document.getElementById('raporTimelineList').innerHTML = raporHtml;

                statusBox.innerHTML = 'PROFIL BERHASIL DITEMUKAN';
                rfidInput.value = '';

                renderCharts(d);
                renderTrendChart(d);

                rfidInput.value = '';

            } catch (err) {
                console.error(err);
                statusBox.innerHTML = 'KESALAHAN SISTEM / DATABASE TERPUTUS';
            }
        }

        function renderCharts(d) {
            const ctx = document.getElementById('radarChart').getContext('2d');
            document.getElementById('chartPlaceholder').style.display = 'none'; // Sembunyikan teks saat grafik ada
            // Hancurkan chart lama jika sudah ada
            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Kehadiran', 'Disiplin', 'Akademik', 'Bakat', 'Prestasi'],
                    datasets: [{
                        label: 'Profil Siswa',
                        data: [
                            d.absensi.length > 0 ? 80 : 50,
                            (100 - (d.bk.total_poin > 100 ? 100 : d.bk.total_poin)),
                            d.rapor_timeline.length > 0 ? d.rapor_timeline[0].average : 75,
                            d.ekskul.length > 0 ? 90 : 40,
                            d.prestasi.length > 0 ? 85 : 30
                        ],
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: '#22c55e',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            grid: {
                                color: 'rgba(255,255,255,0.1)'
                            },
                            pointLabels: {
                                color: '#94a3b8',
                                font: {
                                    size: 10
                                }
                            },
                            ticks: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function renderTrendChart(d) {
            const ctx = document.getElementById('trendChart').getContext('2d');

            if (trendChartInstance) trendChartInstance.destroy();

            // Ambil data untuk grafik (Semester 1, 2, 3, dst)
            const labels = d.rapor_timeline.map(item => `${item.tahun} - ${item.semester}`);
            const dataPoints = d.rapor_timeline.map(item => item.average);

            document.getElementById('trendPlaceholder').style.display = 'none';

            trendChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-Rata',
                        data: dataPoints,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
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

        window.scanNFC = async function() {
            if (!('NDEFReader' in window)) {
                alert('Browser/Peramban ini tidak mendukung pemindaian NFC. Gunakan Google Chrome di HP Android.');
                return;
            }
            try {
                statusBox.innerHTML = 'PEMINDAI NFC AKTIF. TEMPELKAN KARTU...';
                const ndef = new NDEFReader();
                await ndef.scan();
                ndef.onreading = async (event) => {
                    let uid = event.serialNumber || '';
                    if (!uid) return statusBox.innerHTML = 'KARTU TIDAK TERBACA';
                    if (uid.includes(':')) {
                        let parts = uid.split(':').map(x => x.trim()).reverse();
                        uid = parseInt(parts.join(''), 16).toString();
                    }
                    rfidInput.value = uid;
                    loadSiswa(uid);
                };
            } catch (err) {
                console.error(err);
                statusBox.innerHTML = 'PEMINDAIAN NFC GAGAL';
            }
        };
    </script>
</body>

</html>
