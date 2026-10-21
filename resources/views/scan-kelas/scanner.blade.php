<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pindai QR Kelas - MTsN 3 Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Primary Local & CDN Fallback for html5-qrcode -->
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script>
        if (typeof Html5Qrcode === 'undefined') {
            document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"><\/script>');
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        #reader {
            width: 100% !important;
            border: none !important;
        }
        #reader video {
            border-radius: 1.25rem !important;
            object-fit: cover !important;
            width: 100% !important;
            max-height: 380px !important;
        }
        #reader__scan_region {
            background: transparent !important;
        }
        #reader__dashboard {
            display: none !important;
        }
        @keyframes scanBeam {
            0% { top: 10%; opacity: 0.8; }
            50% { top: 85%; opacity: 1; }
            100% { top: 10%; opacity: 0.8; }
        }
        .scan-laser {
            position: absolute;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #10b981, #34d399, transparent);
            box-shadow: 0 0 15px #10b981;
            animation: scanBeam 2.5s infinite ease-in-out;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between p-3 sm:p-5">

    <!-- TOP HEADER -->
    <header class="flex items-center justify-between mb-3">
        <a href="/admin" class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white transition shadow-md active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div class="text-center">
            <h1 class="text-base sm:text-lg font-black tracking-tight text-white uppercase">SCAN QR KELAS</h1>
            <p class="text-[11px] text-emerald-400 font-semibold tracking-wide">MTsN 3 Tulungagung</p>
        </div>
        <!-- Camera switch toggle button -->
        <button id="btn-flip-camera" onclick="switchCamera()" title="Ganti Kamera" class="hidden p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white transition shadow-md active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>
        <div id="btn-flip-placeholder" class="w-10"></div>
    </header>

    <!-- HTTPS WARNING BANNER (If accessed through insecure HTTP) -->
    <div id="https-warning" class="hidden mb-3 p-3 bg-amber-950/80 border border-amber-500/50 rounded-2xl text-amber-300 text-xs text-center">
        <p class="font-bold mb-1">⚠️ Kamera Membutuhkan Koneksi HTTPS</p>
        <p class="text-[11px] text-amber-200/80 mb-2">Browser memblokir izin kamera jika dibuka tanpa enkripsi HTTPS.</p>
        <a id="https-redirect-link" href="#" class="inline-block px-3 py-1.5 rounded-lg bg-amber-500 text-slate-950 font-bold text-xs">
            Beralih ke HTTPS Sekarang
        </a>
    </div>

    <!-- MAIN CAMERA VIEWPORT -->
    <main class="flex-1 flex flex-col items-center justify-center max-w-sm mx-auto w-full">
        
        <!-- Video Box -->
        <div class="relative w-full aspect-square max-h-[380px] rounded-3xl overflow-hidden bg-slate-900 border-2 border-slate-800 shadow-2xl flex items-center justify-center p-1">
            <div id="reader" class="w-full h-full flex items-center justify-center"></div>

            <!-- Scanner Frame Overlay -->
            <div id="scanner-frame" class="hidden pointer-events-none absolute inset-6 border-2 border-emerald-400/80 rounded-2xl">
                <!-- Laser line -->
                <div class="scan-laser"></div>
                <!-- Corner highlights -->
                <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 border-emerald-400 rounded-tl"></div>
                <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 border-emerald-400 rounded-tr"></div>
                <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 border-emerald-400 rounded-bl"></div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 border-emerald-400 rounded-br"></div>
            </div>

            <!-- Start Camera Button (If browser requires explicit tap) -->
            <div id="permission-prompt" class="absolute inset-0 bg-slate-900/95 flex flex-col items-center justify-center p-6 text-center z-20">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 mb-4 shadow-lg shadow-emerald-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Akses Kamera Diperlukan</h3>
                <p class="text-xs text-slate-400 mb-5 max-w-xs">Tekan tombol di bawah lalu pilih <strong>"Allow / Izinkan"</strong> pada pop-up izin browser Anda.</p>
                <button onclick="requestCameraStart()" class="w-full py-3 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition shadow-lg shadow-emerald-600/30 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Nyalakan Kamera</span>
                </button>
            </div>
        </div>

        <!-- Status Message -->
        <div id="scan-status" class="mt-4 text-center w-full">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span id="status-text">Menyiapkan kamera...</span>
            </div>
        </div>

        <!-- Fallback Button: Upload / Snap Photo directly using Native Camera -->
        <div class="mt-4 w-full text-center">
            <input type="file" id="qr-file-input" accept="image/*" capture="environment" class="hidden" onchange="handleQrFile(this)">
            <button onclick="document.getElementById('qr-file-input').click()" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition active:scale-95">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Atau Ambil Foto QR (Kamera Bawaan HP)</span>
            </button>
        </div>

    </main>

    <!-- FOOTER TIPS -->
    <footer class="mt-4 text-center max-w-sm mx-auto w-full space-y-1">
        <p class="text-[11px] text-slate-500">
            Arahkan kamera ke stiker QR Code yang terpasang di ruang kelas.
        </p>
    </footer>

    <!-- SCANNER SCRIPT -->
    <script>
        let html5QrCode = null;
        let isScanned = false;
        let currentCameraIndex = 0;
        let availableCameras = [];

        // 1. Check HTTPS (Browser security requirement for Camera API)
        if (!window.isSecureContext && location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            const httpsUrl = 'https:' + window.location.href.substring(window.location.protocol.length);
            document.getElementById('https-warning').classList.remove('hidden');
            document.getElementById('https-redirect-link').href = httpsUrl;
            // Attempt auto redirect to HTTPS
            setTimeout(() => { window.location.href = httpsUrl; }, 1000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanned) return;
            isScanned = true;

            // Haptic feedback (Vibrate)
            if (navigator.vibrate) {
                navigator.vibrate(150);
            }

            document.getElementById('status-text').innerText = 'Kode QR Ditemukan! Memproses...';
            document.getElementById('scan-status').innerHTML = `
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-xs text-emerald-300 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Presensi Berhasil Dicatat! Mengalihkan...</span>
                </div>
            `;

            // Stop scanner
            if (html5QrCode) {
                try {
                    html5QrCode.stop().catch(() => {});
                } catch(e) {}
            }

            // If decodedText is a full URL containing token
            if (decodedText.includes('token=')) {
                window.location.href = decodedText;
                return;
            }

            // If decodedText is the raw token string
            window.location.href = `/scan-kelas?token=${encodeURIComponent(decodedText)}`;
        }

        function onScanFailure(error) {
            // Passive scan frame search
        }

        async function requestCameraStart() {
            const promptEl = document.getElementById('permission-prompt');
            const statusText = document.getElementById('status-text');
            const scanStatus = document.getElementById('scan-status');

            if (promptEl) promptEl.classList.add('hidden');
            if (statusText) statusText.innerText = 'Menghubungkan kamera...';

            try {
                // Pastikan library Html5Qrcode siap
                if (typeof Html5Qrcode === 'undefined') {
                    for (let i = 0; i < 30; i++) {
                        if (typeof Html5Qrcode !== 'undefined') break;
                        await new Promise(r => setTimeout(r, 100));
                    }
                }

                if (typeof Html5Qrcode === 'undefined') {
                    throw new Error("Library pemindai QR belum siap.");
                }

                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                const config = { 
                    fps: 15, 
                    qrbox: { width: 230, height: 230 },
                    aspectRatio: 1.0
                };

                // 1. Prioritaskan start langsung dengan { facingMode: "environment" }
                // Ini langsung memicu notifikasi "Izinkan Kamera" dari browser secara otomatis pada klik pertama
                try {
                    await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure);
                } catch (facingErr) {
                    console.warn("FacingMode start failed, trying device list fallback:", facingErr);
                    
                    // Fallback jika facingMode ditolak atau butuh deviceId spesifik
                    availableCameras = await Html5Qrcode.getCameras();
                    if (availableCameras && availableCameras.length > 0) {
                        let backCamIndex = availableCameras.findIndex(c => 
                            c.label.toLowerCase().includes('back') || 
                            c.label.toLowerCase().includes('rear') || 
                            c.label.toLowerCase().includes('belakang') ||
                            c.label.toLowerCase().includes('environment')
                        );
                        if (backCamIndex === -1) backCamIndex = availableCameras.length - 1;
                        currentCameraIndex = backCamIndex;
                        await html5QrCode.start(availableCameras[currentCameraIndex].id, config, onScanSuccess, onScanFailure);
                    } else {
                        throw facingErr;
                    }
                }

                // 2. Setelah kamera aktif (izin sudah granted), baru deteksi kamera lain untuk tombol switch
                try {
                    availableCameras = await Html5Qrcode.getCameras();
                    if (availableCameras && availableCameras.length > 1) {
                        const btnFlip = document.getElementById('btn-flip-camera');
                        const placeholderFlip = document.getElementById('btn-flip-placeholder');
                        if (btnFlip) btnFlip.classList.remove('hidden');
                        if (placeholderFlip) placeholderFlip.classList.add('hidden');
                    }
                } catch (camListErr) {
                    // Abaikan jika enumerasi kedua gagal, kamera utama sudah jalan
                }

                document.getElementById('scanner-frame').classList.remove('hidden');
                if (statusText) statusText.innerText = 'Kamera Aktif • Arahkan ke QR Kelas';

            } catch (err) {
                console.error("Camera start error:", err);
                if (promptEl) promptEl.classList.remove('hidden');
                if (statusText) statusText.innerText = 'Kamera belum aktif';
                
                if (scanStatus) {
                    scanStatus.innerHTML = `
                        <div class="p-3 bg-rose-950/80 border border-rose-800 rounded-xl text-rose-300 text-xs space-y-1">
                            <p class="font-bold">Izin Kamera Diperlukan</p>
                            <p class="text-[11px] text-rose-200/80">Silakan klik tombol <strong>"Nyalakan Kamera"</strong> di atas atau periksa izin kamera pada pengaturan browser HP Anda.</p>
                        </div>
                    `;
                }
            }
        }

        async function switchCamera() {
            if (!html5QrCode || availableCameras.length < 2) return;
            try {
                await html5QrCode.stop();
                currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
                const nextCamId = availableCameras[currentCameraIndex].id;
                const config = { fps: 15, qrbox: { width: 230, height: 230 }, aspectRatio: 1.0 };
                await html5QrCode.start(nextCamId, config, onScanSuccess, onScanFailure);
            } catch (err) {
                console.warn("Switch camera failed:", err);
            }
        }

        // Native Camera Capture / File Fallback
        async function handleQrFile(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            
            document.getElementById('status-text').innerText = 'Membaca gambar QR...';

            try {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }
                const decodedText = await html5QrCode.scanFile(file, true);
                onScanSuccess(decodedText, null);
            } catch (err) {
                alert("QR Code tidak terdeteksi pada gambar. Silakan coba lagi dengan foto yang lebih dekat dan terang.");
                document.getElementById('status-text').innerText = 'QR Code tidak terdeteksi.';
            }
        }

        // Inisialisasi otomatis pemindai kamera
        let hasInitialized = false;

        async function initScanner() {
            if (hasInitialized || (html5QrCode && html5QrCode.isScanning)) return;
            hasInitialized = true;

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                await requestCameraStart();
            } else {
                const promptEl = document.getElementById('permission-prompt');
                if (promptEl) promptEl.classList.remove('hidden');
                const statusText = document.getElementById('status-text');
                if (statusText) statusText.innerText = 'Klik tombol untuk menyalakan kamera';
            }
        }

        // Langsung jalankan saat DOM siap (tidak menunggu window load / reload)
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initScanner, 50);
        } else {
            document.addEventListener('DOMContentLoaded', initScanner);
            window.addEventListener('load', initScanner);
        }

        // Dukungan jika dibuka via SPA / Livewire
        document.addEventListener('livewire:navigated', () => {
            hasInitialized = false;
            setTimeout(initScanner, 50);
        });
    </script>
</body>
</html>
