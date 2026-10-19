<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi RFID Guru Kelas - MTsN 3 Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        kiosk: {
                            bg: '#0a0f1d',
                            card: '#111827',
                            border: '#1f293d',
                        }
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
            background-color: #090d16;
            color: #f3f4f6;
            user-select: none;
            overflow: hidden;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
        }
        .pulse-card {
            animation: pulseCard 2s infinite ease-in-out;
        }
        @keyframes pulseCard {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(16, 185, 129, 0.2)); }
            50% { transform: scale(1.02); filter: drop-shadow(0 0 25px rgba(16, 185, 129, 0.4)); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between p-4 md:p-8" onclick="focusRfidInput()">

    <!-- INVISIBLE RFID SCANNER INPUT -->
    <input type="text" id="rfid-input" autofocus autocomplete="off" class="opacity-0 absolute -top-96 left-0">

    <!-- TOP HEADER -->
    <header class="flex items-center justify-between bg-slate-900/80 border border-slate-800 rounded-2xl p-4 backdrop-blur-md shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <h1 class="text-lg font-black tracking-tight text-white uppercase">TAP KARTU GURU DI KELAS</h1>
                <p class="text-xs text-slate-400">MTsN 3 Tulungagung • Reader KBM Kelas</p>
            </div>
        </div>

        <!-- Room Selector Dropdown / Badge -->
        <div class="flex items-center gap-3">
            <div class="bg-slate-950 px-3.5 py-1.5 rounded-xl border border-slate-800 flex items-center gap-2">
                <span class="text-xs text-slate-400 font-semibold">RUANG:</span>
                <select id="kelas-select" onchange="onClassChange()" class="bg-transparent text-emerald-400 font-black text-sm outline-none cursor-pointer">
                    <option value="" disabled {{ !$selectedKelas ? 'selected' : '' }}>-- Pilih Kelas Ini --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelas?->id == $k->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                            Kelas {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="text-right">
                <div id="clock" class="text-xl font-extrabold font-mono-num text-emerald-400">00:00:00</div>
                <div class="text-[11px] text-slate-400">{{ $slot['label'] ?? 'KBM' }}</div>
            </div>
        </div>
    </header>

    <!-- CENTER AREA: IDLE OR SUCCESS CARD -->
    <main class="flex-1 flex items-center justify-center p-4">
        <!-- IDLE STATE -->
        <div id="state-idle" class="text-center max-w-lg w-full flex flex-col items-center">
            <div class="w-32 h-32 rounded-3xl bg-slate-900 border-2 border-slate-800 flex items-center justify-center text-emerald-400 mb-6 shadow-2xl pulse-card">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.962 7.962 0 001.328 4.385"></path></svg>
            </div>
            <h2 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Tempelkan Kartu RFID Anda</h2>
            <p class="text-slate-400 text-sm max-w-sm mb-4">Dekatkan kartu identitas / kartu pegawai Anda pada reader USB untuk check-in mengajar di kelas ini.</p>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-xs text-slate-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Reader Siap Membaca</span>
            </div>
        </div>

        <!-- SUCCESS STATE (Hidden by default) -->
        <div id="state-success" class="hidden text-center max-w-md w-full bg-slate-900/90 border border-emerald-500/80 rounded-3xl p-6 lg:p-8 shadow-2xl backdrop-blur-md">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-500 mx-auto flex items-center justify-center text-emerald-400 mb-4 shadow-lg shadow-emerald-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-emerald-400 text-xs font-bold tracking-widest uppercase mb-1">PRESENSI BERHASIL</h3>
            <h2 id="res-guru" class="text-2xl font-black text-white tracking-tight mb-2">Nama Guru</h2>
            <div class="bg-slate-950/80 rounded-2xl p-4 border border-slate-800/80 mb-4 space-y-1.5 text-left">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Ruang Kelas:</span>
                    <span id="res-kelas" class="font-bold text-white">Kelas 7A</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Mata Pelajaran:</span>
                    <span id="res-mapel" class="font-bold text-emerald-300">-</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Jam Pelajaran:</span>
                    <span id="res-jam" class="font-bold text-white">Jam Ke-1</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Waktu Masuk:</span>
                    <span id="res-waktu" class="font-mono-num font-bold text-white">07:35 WIB</span>
                </div>
            </div>
            <p id="res-msg" class="text-xs text-slate-400">Status monitor di Ruang Guru telah diperbarui.</p>
        </div>

        <!-- ERROR STATE (Hidden by default) -->
        <div id="state-error" class="hidden text-center max-w-md w-full bg-slate-900/90 border border-rose-500/80 rounded-3xl p-6 lg:p-8 shadow-2xl backdrop-blur-md">
            <div class="w-20 h-20 rounded-full bg-rose-500/20 border-2 border-rose-500 mx-auto flex items-center justify-center text-rose-400 mb-4 shadow-lg shadow-rose-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h3 class="text-rose-400 text-xs font-bold tracking-widest uppercase mb-1">GAGAL PRESENSI</h3>
            <p id="err-msg" class="text-white text-base font-bold mb-4">Kartu tidak terdaftar.</p>
            <p class="text-xs text-slate-400">Silakan hubungi administrator madrasah untuk registrasi kartu.</p>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center text-xs text-slate-500">
        Klik di mana saja pada layar jika reader tidak merespons (menjaga fokus input).
    </footer>

    <!-- SCRIPT -->
    <script>
        const input = document.getElementById('rfid-input');
        const selectKelas = document.getElementById('kelas-select');

        // Restore saved class from localStorage if not in URL
        const savedKelasId = localStorage.getItem('sigma3_kiosk_kelas_id');
        if (!selectKelas.value && savedKelasId) {
            selectKelas.value = savedKelasId;
        }

        function onClassChange() {
            if (selectKelas.value) {
                localStorage.setItem('sigma3_kiosk_kelas_id', selectKelas.value);
            }
            focusRfidInput();
        }

        function focusRfidInput() {
            input.focus();
        }
        setInterval(focusRfidInput, 2000);
        focusRfidInput();

        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = 
                `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Text To Speech
        function speak(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = 'id-ID';
                utter.rate = 1.0;
                window.speechSynthesis.speak(utter);
            }
        }

        // Handle RFID Buffer & Submission
        let isProcessing = false;
        input.addEventListener('keydown', async function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const uid = input.value.trim();
                input.value = '';

                if (!uid || isProcessing) return;

                const kelasId = selectKelas.value;
                if (!kelasId) {
                    alert('Silakan pilih Ruang Kelas terlebih dahulu pada dropdown pojok kanan atas!');
                    return;
                }

                await processRfidCheckin(uid, kelasId);
            }
        });

        async function processRfidCheckin(uid, kelasId) {
            isProcessing = true;
            try {
                const response = await fetch('/api/rfid/kelas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        uid: uid,
                        kelas_id: kelasId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccess(data);
                    if (data.tts) speak(data.tts);
                } else {
                    showError(data.message || 'Kartu gagal diproses.');
                    if (data.tts) speak(data.tts);
                }
            } catch (err) {
                showError('Koneksi server gagal terhubung.');
            } finally {
                setTimeout(() => {
                    resetToIdle();
                    isProcessing = false;
                    focusRfidInput();
                }, 4000);
            }
        }

        function showSuccess(data) {
            document.getElementById('state-idle').classList.add('hidden');
            document.getElementById('state-error').classList.add('hidden');
            const sc = document.getElementById('state-success');
            sc.classList.remove('hidden');

            const item = data.data || {};
            document.getElementById('res-guru').innerText = item.guru || 'Guru';
            document.getElementById('res-kelas').innerText = `Kelas ${item.kelas || ''}`;
            document.getElementById('res-mapel').innerText = item.mapel || '-';
            document.getElementById('res-jam').innerText = `Jam Ke-${item.jam_ke || '1'}`;
            document.getElementById('res-waktu').innerText = `${item.waktu || ''} WIB`;
            document.getElementById('res-msg').innerText = data.message || 'Presensi berhasil dicatat.';
        }

        function showError(msg) {
            document.getElementById('state-idle').classList.add('hidden');
            document.getElementById('state-success').classList.add('hidden');
            const er = document.getElementById('state-error');
            er.classList.remove('hidden');
            document.getElementById('err-msg').innerText = msg;
        }

        function resetToIdle() {
            document.getElementById('state-success').classList.add('hidden');
            document.getElementById('state-error').classList.add('hidden');
            document.getElementById('state-idle').classList.remove('hidden');
        }
    </script>
</body>
</html>
