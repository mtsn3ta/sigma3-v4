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
@php
    $isWrongClass = ($status_code ?? '') === 'WRONG_CLASS';
@endphp
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-slate-900 border {{ $success ? 'border-emerald-500/60' : ($isWrongClass ? 'border-amber-500/60' : 'border-rose-500/60') }} rounded-3xl p-6 shadow-2xl text-center relative overflow-hidden">
        
        <!-- Glow effect -->
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-48 h-48 rounded-full {{ $success ? 'bg-emerald-500/20' : ($isWrongClass ? 'bg-amber-500/20' : 'bg-rose-500/20') }} blur-3xl pointer-events-none"></div>

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

        @elseif($isWrongClass)
            <!-- Icon Warning Salah Kelas -->
            <div class="w-20 h-20 rounded-full bg-amber-500/20 border-2 border-amber-500 mx-auto flex items-center justify-center text-amber-400 mb-4 shadow-lg shadow-amber-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40 inline-block mb-2">
                SALAH RUANG KELAS
            </span>

            <h1 class="text-xl font-black text-white tracking-tight mb-2">Ruang Kelas Tidak Sesuai</h1>
            <p class="text-xs text-amber-200/90 mb-5 leading-relaxed">{{ $message }}</p>

            <!-- Card Perbandingan Kelas -->
            <div class="bg-slate-950/80 rounded-2xl p-4 border border-slate-800 text-left space-y-2.5 mb-6">
                <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-800/80">
                    <span class="text-slate-400">Guru:</span>
                    <span class="font-extrabold text-white">{{ $data['guru'] ?? auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Kelas di-scan:</span>
                    <span class="inline-flex items-center gap-1 font-bold text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/30">
                        <span>Kelas {{ $data['kelas_di_scan'] ?? $kelas?->nama_kelas }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Kelas Seharusnya:</span>
                    <span class="inline-flex items-center gap-1 font-extrabold text-emerald-300 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/30">
                        <span>Kelas {{ $data['kelas_seharusnya'] ?? '-' }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </span>
                </div>
                @if(!empty($data['mapel']))
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Mata Pelajaran:</span>
                    <span class="font-bold text-white">{{ $data['mapel'] }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Jam Mengajar:</span>
                    <span class="font-bold text-amber-300">{{ $data['jam_label_seharusnya'] ?? ('Jam Ke-' . ($data['jam_ke'] ?? '')) }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="/scan-kelas" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm transition shadow-lg shadow-amber-600/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span>Scan QR di Kelas {{ $data['kelas_seharusnya'] ?? 'yang Benar' }}</span>
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
