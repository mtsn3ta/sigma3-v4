<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Portal Wali Murid — {{ $student->nama }}</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .signature-grid {
            background-size: 20px 20px;
            background-image: 
                linear-gradient(to right, rgba(226, 232, 240, 0.6) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(226, 232, 240, 0.6) 1px, transparent 1px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen pb-32 md:pb-16 select-none sm:select-auto" x-data="{ tab: 'izin' }">

    {{-- Aksen Top Atmosphere (Emerald Palette) --}}
    <div class="fixed top-0 left-0 right-0 h-72 bg-gradient-to-b from-emerald-500/15 via-teal-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-xl mx-auto px-4 pt-4 sm:pt-6 relative z-10">

        {{-- ========================================================================= --}}
        {{-- TOP BAR / BRANDING MADRASAH                                               --}}
        {{-- ========================================================================= --}}
        <header class="flex items-center justify-between mb-4 px-1">
            <div class="flex items-center gap-2.5">
                @if (\App\Support\SettingHelper::schoolLogo())
                    <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolLogo()) }}"
                         alt="Logo Madrasah"
                         class="w-8 h-8 object-contain drop-shadow-sm">
                @else
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-black text-xs">
                        S3
                    </div>
                @endif
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-700 block leading-tight">
                        PORTAL WALI MURID
                    </span>
                    <span class="text-xs font-bold text-slate-600">
                        {{ \App\Support\SettingHelper::schoolName() }}
                    </span>
                </div>
            </div>

            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100/80 border border-emerald-200 text-emerald-800 text-[11px] font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>TA {{ \App\Support\SettingHelper::tahunAjaranLabel() }}</span>
            </div>
        </header>

        {{-- ========================================================================= --}}
        {{-- HEADER: KARTU IDENTITAS SISWA                                             --}}
        {{-- ========================================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm shadow-slate-200/50 p-4 sm:p-5 relative overflow-hidden">
            {{-- Radial Orb Decor --}}
            <div class="absolute -top-12 -right-12 w-36 h-36 bg-gradient-to-br from-emerald-400/20 to-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center gap-4 relative z-10">
                <div class="relative shrink-0">
                    @if ($student->foto)
                        <img src="{{ asset('storage/' . $student->foto) }}" alt="{{ $student->nama }}"
                            class="w-20 h-24 sm:w-22 sm:h-26 rounded-2xl object-cover border-2 border-emerald-500/30 shadow-md">
                    @else
                        <div class="w-20 h-24 sm:w-22 sm:h-26 rounded-2xl bg-gradient-to-br from-emerald-100 via-teal-100 to-emerald-200 border-2 border-emerald-500/20 shadow-md flex items-center justify-center text-3xl font-black text-emerald-600">
                            {{ strtoupper(substr($student->nama, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 bg-emerald-500 w-4 h-4 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    </span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="inline-block px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200/80 text-[10px] font-black uppercase tracking-wider text-emerald-700">
                        Peserta Didik Aktif
                    </div>
                    <h1 class="text-lg sm:text-xl font-black text-slate-900 truncate mt-1 leading-tight">
                        {{ $student->nama }}
                    </h1>

                    <div class="grid grid-cols-2 gap-2 mt-2.5">
                        <div class="bg-slate-50 rounded-xl p-2 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">NISN</span>
                            <span class="text-xs sm:text-sm font-black text-slate-800 tracking-wide">{{ $student->nisn }}</span>
                        </div>
                        <div class="bg-emerald-50/60 rounded-xl p-2 border border-emerald-100">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Kelas</span>
                            <span class="text-xs sm:text-sm font-black text-emerald-800">{{ $student->nama_kelas_aktif ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NOTIFIKASI SUKSES / ERROR --}}
        @if (session('success'))
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-4 flex items-start gap-3 shadow-sm animate-fade-in">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <div class="font-black text-emerald-900 text-base">Surat Berhasil Dikirim!</div>
                    <div class="text-emerald-800 text-xs sm:text-sm font-medium mt-0.5 leading-relaxed">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50/90 p-4 flex items-start gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-black text-rose-900 text-sm sm:text-base">Mohon Lengkapi Data Berikut:</div>
                    <ul class="mt-1 list-disc pl-4 text-xs sm:text-sm text-rose-700 font-medium space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TOP TAB SWITCHER (DESKTOP / ALTERNATIF HP)                                 --}}
        {{-- ========================================================================= --}}
        <div class="mt-4 p-1 bg-slate-200/80 rounded-2xl flex items-center gap-1">
            <button type="button" @click="tab='izin'"
                class="flex-1 py-2 px-3 rounded-xl font-black text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5"
                :class="tab == 'izin' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span>Ajukan Izin</span>
            </button>

            <button type="button" @click="tab='riwayat'"
                class="flex-1 py-2 px-3 rounded-xl font-black text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5"
                :class="tab == 'riwayat' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span>Riwayat Izin</span>
            </button>

            <button type="button" @click="tab='bk'"
                class="flex-1 py-2 px-3 rounded-xl font-black text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5"
                :class="tab == 'bk' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                </svg>
                <span>Catatan BK</span>
            </button>
        </div>

        {{-- ========================================================================= --}}
        {{-- KONTEN AREA (TABULASI CONTAINER)                                         --}}
        {{-- ========================================================================= --}}
        <main class="mt-4 mb-8">

            {{-- ───────────────────────────────────────────────────────────────── --}}
            {{-- TAB 1: FORMULIR AJUKAN IZIN                                      --}}
            {{-- ───────────────────────────────────────────────────────────────── --}}
            <div x-show="tab=='izin'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6">
                    <form action="{{ route('wali_murid.store', $student->nisn) }}" method="POST"
                        enctype="multipart/form-data" x-data="{ 
                            jenis: 'sakit', 
                            surat: '0',
                            selfiePreview: null,
                            suratPreview: null,
                            handleSelfie(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    this.selfiePreview = URL.createObjectURL(file);
                                }
                            },
                            handleSurat(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    this.suratPreview = file.name;
                                }
                            }
                        }" class="space-y-6">
                        @csrf

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
                                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                    Surat Permohonan Izin
                                </h2>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 font-medium pl-4">
                                Harap mengisi informasi dan bukti permohonan dengan jujur dan jelas.
                            </p>
                        </div>

                        {{-- 1. PILIH ALASAN (RADIO CARD) --}}
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-600 block mb-2">
                                1. Alasan Ketidakhadiran <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all select-none text-center"
                                    :class="jenis == 'sakit' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 shadow-sm ring-2 ring-emerald-600/20' :
                                        'border-slate-200 bg-slate-50/50 text-slate-600 hover:border-slate-300'">
                                    <input type="radio" value="sakit" name="jenis" x-model="jenis" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center"
                                         :class="jenis == 'sakit' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 text-slate-500'">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-black">Sakit</span>
                                    <span class="text-[10px] text-slate-500 font-medium mt-0.5">Disertai surat / gejala</span>
                                </label>

                                <label
                                    class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all select-none text-center"
                                    :class="jenis == 'izin' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 shadow-sm ring-2 ring-emerald-600/20' :
                                        'border-slate-200 bg-slate-50/50 text-slate-600 hover:border-slate-300'">
                                    <input type="radio" value="izin" name="jenis" x-model="jenis" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center"
                                         :class="jenis == 'izin' ? 'bg-emerald-600 text-white' : 'bg-slate-200/80 text-slate-500'">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-black">Izin Penting</span>
                                    <span class="text-[10px] text-slate-500 font-medium mt-0.5">Keperluan keluarga / dinas</span>
                                </label>
                            </div>
                        </div>

                        {{-- 2. RENTANG TANGGAL --}}
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-600 block mb-2">
                                2. Periode Izin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-bold text-slate-500 block mb-1">Mulai Tanggal:</span>
                                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50/80 px-4 py-3 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                                        required>
                                </div>
                                <div>
                                    <span class="text-[11px] font-bold text-slate-500 block mb-1">Sampai Tanggal (Selesai):</span>
                                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50/80 px-4 py-3 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                                        required>
                                </div>
                            </div>
                        </div>

                        {{-- 3. DETAIL KHUSUS SAKIT --}}
                        <div x-show="jenis=='sakit'" x-transition class="space-y-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <div>
                                <label class="text-xs font-black uppercase tracking-wider text-slate-700 block mb-2">
                                    Surat Dokter Tersedia?
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2.5 bg-white p-3 rounded-xl border border-slate-200 cursor-pointer select-none">
                                        <input type="radio" name="punya_surat_dokter" value="1" x-model="surat" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-xs font-black text-slate-800">Ada Surat Dokter</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 bg-white p-3 rounded-xl border border-slate-200 cursor-pointer select-none">
                                        <input type="radio" name="punya_surat_dokter" value="0" x-model="surat" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-xs font-black text-slate-800">Tidak Ada</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Upload Surat Dokter --}}
                            <div x-show="surat == '1'" x-transition>
                                <label class="text-xs font-bold text-slate-700 block mb-1.5">Foto / Berkas Surat Keterangan Dokter:</label>
                                <div class="relative">
                                    <input type="file" name="surat_dokter" accept="image/*,application/pdf" @change="handleSurat"
                                        class="w-full bg-white text-xs border border-slate-300 rounded-xl p-3 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                </div>
                                <template x-if="suratPreview">
                                    <div class="mt-2 text-xs text-emerald-700 font-bold flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span x-text="suratPreview"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Gejala Penyakit --}}
                            <div x-show="surat == '0'" x-transition>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Jelaskan Gejala / Sakit yang Dialami:</label>
                                <textarea name="keterangan" rows="3"
                                    class="w-full border border-slate-300 bg-white rounded-xl p-3 text-sm placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none transition-all"
                                    placeholder="Contoh: Demam sejak semalam, flu berat, perlu istirahat dokter..."></textarea>
                            </div>
                        </div>

                        {{-- 3. DETAIL KHUSUS IZIN --}}
                        <div x-show="jenis=='izin'" x-transition class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <label class="text-xs font-black uppercase tracking-wider text-slate-700 block mb-1.5">
                                Alasan / Keperluan Izin: <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="keterangan" rows="3"
                                class="w-full border border-slate-300 bg-white rounded-xl p-3 text-sm placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none transition-all"
                                placeholder="Contoh: Menghadiri pernikahan saudara kandung di luar kota, urusan administrasi keluarga..."></textarea>
                        </div>

                        <div class="border-t border-dashed border-slate-200 pt-2"></div>

                        {{-- 4. IDENTITAS ORANG TUA / WALI --}}
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-600 block mb-2">
                                3. Identitas Pengirim Surat <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-bold text-slate-500 block mb-1">Nama Orang Tua / Wali:</span>
                                    <input type="text" name="nama_pengirim"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50/80 px-4 py-3 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                                        placeholder="Nama lengkap bapak/ibu" required>
                                </div>
                                <div>
                                    <span class="text-[11px] font-bold text-slate-500 block mb-1">Hubungan Keluarga:</span>
                                    <select name="hubungan"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50/80 px-4 py-3 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none">
                                        <option value="ayah">Ayah Kandung</option>
                                        <option value="ibu">Ibu Kandung</option>
                                        <option value="wali">Wali / Keluarga Lain</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- 5. SELFIE BUKTI PENGIRIM --}}
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-600 block mb-1">
                                4. Foto Selfie Orang Tua <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 mb-2 font-medium">Sebagai validasi keaslian surat yang dikirim oleh wali murid.</p>

                            <div class="flex items-center gap-4">
                                <label class="flex-1 flex items-center justify-center gap-2 px-4 py-3.5 rounded-2xl border-2 border-dashed border-emerald-400 bg-emerald-50/50 hover:bg-emerald-50 cursor-pointer text-emerald-800 font-bold text-xs sm:text-sm transition-all select-none">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                    </svg>
                                    <span>Ambil Foto Kamera</span>
                                    <input type="file" name="selfie" accept="image/*" capture="user" @change="handleSelfie" class="sr-only" required>
                                </label>

                                <template x-if="selfiePreview">
                                    <div class="relative w-14 h-14 rounded-2xl overflow-hidden border-2 border-emerald-500 shadow-sm shrink-0">
                                        <img :src="selfiePreview" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- 6. TANDA TANGAN DIGITAL --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-black uppercase tracking-wider text-slate-600 block">
                                    5. Tanda Tangan Digital <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" id="clear-signature"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    <span>Hapus / Ulangi</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-500 mb-2 font-medium">Goreskan jari Anda di dalam kotak di bawah ini:</p>

                            <div class="relative bg-white rounded-2xl border-2 border-slate-300 overflow-hidden shadow-inner signature-grid">
                                <canvas id="signature-pad" height="180" class="w-full touch-none block cursor-crosshair"></canvas>
                                <input type="hidden" name="signature" id="signature">
                            </div>
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-4 px-6 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white font-black text-base sm:text-lg rounded-2xl shadow-lg shadow-emerald-700/25 hover:from-emerald-700 hover:to-teal-800 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                <span>Kirim Permohonan Izin</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ───────────────────────────────────────────────────────────────── --}}
            {{-- TAB 2: RIWAYAT IZIN & KEHADIRAN SISWA                             --}}
            {{-- ───────────────────────────────────────────────────────────────── --}}
            <div x-show="tab=='riwayat'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-6">
                    
                    {{-- Header Riwayat --}}
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                Riwayat Surat Permohonan
                            </h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-medium pl-4">
                            Status verifikasi surat izin yang pernah diajukan oleh wali murid.
                        </p>
                    </div>

                    {{-- Daftar Surat --}}
                    <div class="space-y-3">
                        @forelse($student->parentLeaveRequests as $item)
                            <div class="border border-slate-200/80 rounded-2xl bg-slate-50/60 p-4 transition-all hover:bg-slate-50">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-black uppercase tracking-wider px-2 py-0.5 rounded-lg {{ $item->jenis == 'sakit' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($item->jenis) }}
                                            </span>
                                            
                                            @if($item->status == 'approved')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-black text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md border border-emerald-200">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                    Disetujui
                                                </span>
                                            @elseif($item->status == 'pending')
                                                <span class="inline-flex items-center gap-1 text-[11px] font-black text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-md border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                                    Menunggu
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[11px] font-black text-rose-700 bg-rose-100/80 px-2 py-0.5 rounded-md border border-rose-200">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs sm:text-sm text-slate-800 font-black mt-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            {{ $item->tanggal_mulai->format('d M Y') }} &mdash; {{ $item->tanggal_selesai->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold shrink-0">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-200/70 text-xs text-slate-600 space-y-1">
                                    <div class="flex items-center gap-1">
                                        <span class="text-slate-400">Pengirim:</span>
                                        <strong class="text-slate-700">{{ $item->nama_pengirim }} ({{ ucfirst($item->hubungan) }})</strong>
                                    </div>
                                    @if($item->keterangan)
                                        <div class="bg-white p-2.5 rounded-xl border border-slate-200/80 text-slate-700 italic text-xs mt-1">
                                            "{{ $item->keterangan }}"
                                        </div>
                                    @endif
                                    @if($item->verification_note)
                                        <div class="bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 text-emerald-800 text-xs mt-1">
                                            <strong>Catatan Guru Piket:</strong> {{ $item->verification_note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-4 border-2 border-dashed border-slate-200 rounded-3xl">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-bold text-slate-700">Belum Ada Pengajuan</div>
                                <div class="text-xs text-slate-400 mt-0.5">Surat izin yang bapak/ibu ajukan akan muncul di sini.</div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Seksi Kehadiran Harian Terbaru --}}
                    @if(isset($student->kehadiranSekolah) && $student->kehadiranSekolah->isNotEmpty())
                        <div class="border-t border-slate-200 pt-5">
                            <h3 class="text-sm font-black text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>10 Presensi Terakhir di Madrasah</span>
                            </h3>

                            <div class="space-y-2">
                                @foreach($student->kehadiranSekolah as $presensi)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full {{ $presensi->status === 'hadir' ? 'bg-emerald-500' : ($presensi->status === 'terlambat' ? 'bg-amber-500' : ($presensi->status === 'sakit' ? 'bg-blue-500' : 'bg-rose-500')) }}"></span>
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $presensi->tanggal->format('d M Y') }}</div>
                                                <div class="text-[10px] text-slate-400">
                                                    @if($presensi->jam_datang)
                                                        Masuk: {{ substr($presensi->jam_datang, 0, 5) }}
                                                    @endif
                                                    @if($presensi->jam_pulang)
                                                        &bull; Pulang: {{ substr($presensi->jam_pulang, 0, 5) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-md font-bold uppercase text-[10px] {{ $presensi->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : ($presensi->status === 'terlambat' ? 'bg-amber-100 text-amber-800' : ($presensi->status === 'sakit' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800')) }}">
                                            {{ $presensi->status_label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ───────────────────────────────────────────────────────────────── --}}
            {{-- TAB 3: CATATAN BIMBINGAN KONSELING (BK)                           --}}
            {{-- ───────────────────────────────────────────────────────────────── --}}
            <div x-show="tab=='bk'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-6">

                    {{-- Sub-Seksi Permasalahan & Catatan Disiplin --}}
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-6 rounded-full bg-rose-500 inline-block"></span>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                Catatan Kedisiplinan Siswa
                            </h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-medium pl-4">
                            Laporan kendala atau pelanggaran tata tertib madrasah.
                        </p>

                        <div class="mt-4 space-y-3">
                            @forelse($student->permasalahan as $item)
                                <div class="border border-rose-100 rounded-2xl bg-rose-50/30 p-4 shadow-sm">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-black text-rose-700 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            {{ $item->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="text-slate-500 font-bold">Guru: {{ $item->pegawai?->nama ?? '-' }}</span>
                                    </div>
                                    <div class="mt-2.5">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Kejadian / Pelanggaran:</span>
                                        <p class="text-xs sm:text-sm font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-100 mt-1 leading-relaxed">
                                            {{ $item->permasalahan }}
                                        </p>
                                    </div>
                                    @if ($item->penyelesaian)
                                        <div class="mt-3 pt-3 border-t border-dashed border-rose-200">
                                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-wider block">Solusi / Arahan Guru:</span>
                                            <p class="text-xs sm:text-sm font-bold text-emerald-800 mt-0.5 leading-relaxed">
                                                {{ $item->penyelesaian }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-xs font-black text-slate-700">Siswa Berkelakuan Baik</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Tidak ada catatan pelanggaran atau kendala disiplin.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Sub-Seksi Konseling --}}
                    <div class="border-t border-slate-200 pt-5">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-6 rounded-full bg-teal-500 inline-block"></span>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                Riwayat Bimbingan Konseling
                            </h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-medium pl-4">
                            Sesi pendampingan minat, bakat, serta konsultasi belajar.
                        </p>

                        <div class="mt-4 space-y-3">
                            @forelse($student->konseling as $item)
                                <div class="border border-slate-200 rounded-2xl bg-slate-50/60 p-4 shadow-sm">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-black text-slate-700 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            {{ $item->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="text-slate-500 font-bold">Konselor: {{ $item->pegawai?->nama ?? '-' }}</span>
                                    </div>
                                    <div class="mt-2.5 space-y-2 text-xs sm:text-sm">
                                        <div>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Topik Konseling:</span>
                                            <p class="font-bold text-slate-800 mt-0.5">{{ $item->permasalahan }}</p>
                                        </div>
                                        @if ($item->hasil_konseling)
                                            <div class="bg-white p-3 rounded-xl border border-slate-100">
                                                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-wider block">Hasil Pertemuan:</span>
                                                <p class="font-medium text-slate-700 mt-0.5 leading-relaxed">{{ $item->hasil_konseling }}</p>
                                            </div>
                                        @endif
                                        @if ($item->tindak_lanjut)
                                            <div>
                                                <span class="text-[10px] font-black text-teal-600 uppercase tracking-wider block">Rencana Tindak Lanjut:</span>
                                                <p class="font-bold text-slate-800 mt-0.5">{{ $item->tindak_lanjut }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                        </svg>
                                    </div>
                                    <div class="text-xs font-black text-slate-700">Belum Ada Sesi Konseling</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Catatan pendampingan BK akan ditampilkan di sini.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>

    {{-- ========================================================================= --}}
    {{-- BOTTOM NAVIGATION: FLOATING MOBILE TAB BAR                                --}}
    {{-- ========================================================================= --}}
    <div class="fixed bottom-0 left-0 right-0 p-3 z-50 pointer-events-none">
        <div class="max-w-md mx-auto bg-white/90 backdrop-blur-xl border border-slate-200/90 rounded-3xl p-1.5 shadow-2xl shadow-slate-900/15 pointer-events-auto flex items-center justify-around">
            {{-- Tombol Navigasi Izin --}}
            <button type="button" @click="tab='izin'"
                class="flex-1 flex flex-col items-center gap-1 py-2 px-2 rounded-2xl transition-all duration-200 select-none outline-none"
                :class="tab == 'izin' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30 font-black' : 'text-slate-500 hover:text-slate-800 font-bold'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span class="text-[10px] tracking-tight">Kirim Izin</span>
            </button>

            {{-- Tombol Navigasi Riwayat --}}
            <button type="button" @click="tab='riwayat'"
                class="flex-1 flex flex-col items-center gap-1 py-2 px-2 rounded-2xl transition-all duration-200 select-none outline-none"
                :class="tab == 'riwayat' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30 font-black' : 'text-slate-500 hover:text-slate-800 font-bold'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span class="text-[10px] tracking-tight">Riwayat</span>
            </button>

            {{-- Tombol Navigasi BK --}}
            <button type="button" @click="tab='bk'"
                class="flex-1 flex flex-col items-center gap-1 py-2 px-2 rounded-2xl transition-all duration-200 select-none outline-none"
                :class="tab == 'bk' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30 font-black' : 'text-slate-500 hover:text-slate-800 font-bold'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                </svg>
                <span class="text-[10px] tracking-tight">Catatan BK</span>
            </button>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- CANVAS SIGNATURE ENGINE                                                   --}}
    {{-- ========================================================================= --}}
    <script>
        const canvas = document.getElementById('signature-pad');
        const hidden = document.getElementById('signature');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);

            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
        }

        window.addEventListener('resize', resizeCanvas);
        setTimeout(resizeCanvas, 150);

        let drawing = false;

        function position(e) {
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches ? e.touches[0] : e;
            return {
                x: touch.clientX - rect.left,
                y: touch.clientY - rect.top,
            };
        }

        function start(e) {
            drawing = true;
            const p = position(e);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
        }

        function move(e) {
            if (!drawing) return;
            e.preventDefault();
            const p = position(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
        }

        function end() {
            if (!drawing) return;
            drawing = false;
            hidden.value = canvas.toDataURL();
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        window.addEventListener('mouseup', end);

        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove', move, { passive: false });
        canvas.addEventListener('touchend', end);

        document.getElementById('clear-signature').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hidden.value = '';
            resizeCanvas();
        });
    </script>
</body>

</html>
