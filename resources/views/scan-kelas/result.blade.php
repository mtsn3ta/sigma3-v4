<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Presensi Kelas - MTsN 3 Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-num { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-slate-900 border {{ $success ? 'border-emerald-500/60' : 'border-rose-500/60' }} rounded-3xl p-6 shadow-2xl text-center relative overflow-hidden">
        
        <!-- Glow effect -->
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-48 h-48 rounded-full {{ $success ? 'bg-emerald-500/20' : 'bg-rose-500/20' }} blur-3xl pointer-events-none"></div>

        @if($success)
            <!-- Icon Success -->
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-500 mx-auto flex items-center justify-center text-emerald-400 mb-4 shadow-lg shadow-emerald-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 inline-block mb-2">
                {{ !empty($already_recorded) ? 'SUDAH TERCATAT' : 'PRESENSI BERHASIL' }}
            </span>

            <h1 class="text-2xl font-black text-white tracking-tight mb-2">
                {{ $data['guru'] ?? auth()->user()->name }}
            </h1>
            <p class="text-xs text-slate-400 mb-6">{{ $message }}</p>

            <!-- Card Info -->
            <div class="bg-slate-950/80 rounded-2xl p-4 border border-slate-800 text-left space-y-2 mb-6">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Ruang Kelas:</span>
                    <span class="font-extrabold text-white">Kelas {{ $kelas?->nama_kelas ?? ($data['kelas'] ?? '-') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Mata Pelajaran:</span>
                    <span class="font-bold text-emerald-300">{{ $data['mapel'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Jam Pelajaran:</span>
                    <span class="font-bold text-white">Jam Ke-{{ $data['jam_ke'] ?? '1' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Waktu Masuk:</span>
                    <span class="font-mono-num font-bold text-white">{{ $data['waktu'] ?? now()->format('H:i') }} WIB</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Metode Presensi:</span>
                    <span class="font-bold text-slate-300 uppercase">{{ $data['metode'] ?? 'QR CODE' }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="/admin/jurnal-mengajar" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition shadow-lg shadow-emerald-600/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Isi Jurnal Mengajar</span>
                </a>
                <a href="/admin" class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition">
                    Kembali ke Beranda
                </a>
            </div>

        @else
            <!-- Icon Failed -->
            <div class="w-20 h-20 rounded-full bg-rose-500/20 border-2 border-rose-500 mx-auto flex items-center justify-center text-rose-400 mb-4 shadow-lg shadow-rose-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>

            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/40 inline-block mb-2">
                PRESENSI GAGAL
            </span>

            <h1 class="text-xl font-black text-white tracking-tight mb-3">Tidak Dapat Memproses</h1>
            <p class="text-sm text-slate-400 mb-6">{{ $message }}</p>

            <a href="/scan-kelas" class="w-full inline-flex items-center justify-center py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition">
                Coba Scan Ulang
            </a>
        @endif

    </div>

</body>
</html>
