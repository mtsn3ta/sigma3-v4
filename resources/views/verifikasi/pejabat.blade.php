<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pejabat - {{ $schoolName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, .15), transparent 35%),
                radial-gradient(circle at bottom right, rgba(59, 130, 246, .15), transparent 35%),
                linear-gradient(135deg, #020617, #0f172a, #111827);
            color: white;
        }

        .glass {
            background: rgba(15, 23, 42, .75);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 0 40px rgba(0, 0, 0, .4);
        }

        .scanline {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .badge-valid {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, .3);
        }

        .badge-invalid {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, .3);
        }
    </style>
</head>

<body>
    <div class="scanline"></div>
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="glass rounded-3xl max-w-5xl w-full overflow-hidden">
            <div class="grid md:grid-cols-2">

                <div class="bg-slate-900 flex items-end justify-center p-8">
                    <img src="{{ $fotoKepala ?? asset('img/kepala-default.png') }}" alt="Kepala Madrasah"
                        class="max-h-150 object-contain translate-y-10">
                </div>

                <div class="p-10 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-8">
                        @if ($logo)
                            <img src="{{ $logo }}" class="w-16 h-16 object-contain">
                        @endif
                        @if ($logoResmi)
                            <img src="{{ $logoResmi }}" class="w-16 h-16 object-contain">
                        @endif
                    </div>

                    <div class="text-green-400 tracking-[5px] text-sm font-bold">
                        DIGITAL SIGNATURE VERIFICATION
                    </div>

                    <h1 class="text-5xl font-black mt-4">SIGMA-3</h1>

                    <div class="text-2xl mt-3 font-bold text-white">
                        Sistem Informasi Global<br>{{ $schoolName }}
                    </div>

                    <div class="mt-8">
                        @if ($valid)
                            <div class="inline-block px-5 py-3 rounded-2xl badge-valid font-bold">
                                ✓ TANDA TANGAN DIGITAL VALID
                            </div>
                        @else
                            <div class="inline-block px-5 py-3 rounded-2xl badge-invalid font-bold">
                                ✕ VERIFIKASI TIDAK VALID
                            </div>
                        @endif
                    </div>

                    <div class="mt-10 space-y-5">
                        <div>
                            <div class="text-slate-400 text-sm uppercase tracking-[3px]">Nama Pejabat yang Mengesahkan
                            </div>
                            <div class="text-2xl font-black text-yellow-300">
                                {{ $kepala->nama ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-sm uppercase tracking-[3px]">Jabatan</div>
                            <div class="text-xl font-bold">
                                Kepala {{ $schoolName }}
                            </div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-sm uppercase tracking-[3px]">NIK / NIP</div>
                            <div class="text-xl font-bold">{{ $kepala->nik_nip ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-400 text-sm uppercase tracking-[3px]">Waktu Verifikasi</div>
                            <div class="text-lg font-semibold">{{ $waktu }} WIB</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
