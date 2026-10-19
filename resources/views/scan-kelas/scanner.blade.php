<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pindai QR Kelas - MTsN 3 Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #reader video {
            border-radius: 1.25rem;
            object-fit: cover;
        }
        #reader {
            border: none !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between p-4">

    <!-- HEADER -->
    <header class="flex items-center justify-between mb-4">
        <a href="/admin" class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div class="text-center">
            <h1 class="text-base font-black tracking-tight text-white uppercase">SCAN QR KELAS</h1>
            <p class="text-[11px] text-slate-400">Arahkan kamera ke stiker QR di kelas</p>
        </div>
        <div class="w-10"></div>
    </header>

    <!-- CAMERA SCANNER CONTAINER -->
    <main class="flex-1 flex flex-col items-center justify-center max-w-sm mx-auto w-full">
        <div class="relative w-full aspect-square rounded-3xl overflow-hidden bg-slate-900 border-2 border-dashed border-emerald-500/60 p-2 shadow-2xl flex items-center justify-center">
            <div id="reader" class="w-full h-full"></div>
            <!-- Overlay scanner aim -->
            <div class="pointer-events-none absolute inset-8 border-2 border-emerald-400 rounded-2xl opacity-60"></div>
        </div>

        <div id="scan-status" class="mt-4 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Kamera Aktif • Mencari Kode QR...</span>
            </div>
        </div>
    </main>

    <!-- FOOTER INSTRUCTION -->
    <footer class="mt-4 text-center max-w-sm mx-auto w-full space-y-2">
        <p class="text-xs text-slate-500">
            Pastikan Anda telah mengizinkan akses kamera pada browser HP.
        </p>
    </footer>

    <!-- SCANNER SCRIPT -->
    <script>
        let html5QrcodeScanner = null;
        let isScanned = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanned) return;
            isScanned = true;

            document.getElementById('scan-status').innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-xs text-emerald-300 font-bold">
                    <span>Kode Ditemukan! Memproses...</span>
                </div>
            `;

            // Jika decodedText adalah URL lengkap (misal: https://.../scan-kelas?token=xxx)
            try {
                if (decodedText.includes('token=')) {
                    window.location.href = decodedText;
                    return;
                }
            } catch (e) {}

            // Jika decodedText hanya string token langsung
            window.location.href = `/scan-kelas?token=${encodeURIComponent(decodedText)}`;
        }

        function onScanFailure(error) {
            // Biarkan scanning berjalan
        }

        window.addEventListener('load', () => {
            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 15, qrbox: { width: 250, height: 250 } };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                document.getElementById('scan-status').innerHTML = `
                    <div class="p-3 bg-rose-950/60 border border-rose-800 rounded-xl text-rose-300 text-xs">
                        Gagal mengakses kamera: ${err}. Pastikan izin kamera aktif.
                    </div>
                `;
            });
        });
    </script>
</body>
</html>
