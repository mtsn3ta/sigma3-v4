<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Wali Murid</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Menggunakan font Inter/Plus Jakarta Sans agar keterbacaan di HP sangat tajam --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-[#0f172a] antialiased pb-28 md:pb-12" x-data="{ tab: 'izin' }">

    {{-- Efek Pendaran Futuristik Latar Belakang --}}
    <div
        class="fixed top-0 left-0 right-0 h-80 bg-gradient-to-b from-indigo-500/10 via-purple-500/5 to-transparent pointer-events-none z-0">
    </div>

    <div class="max-w-xl mx-auto px-4 pt-6 relative z-10">

        {{-- ========================================================================= --}}
        {{-- HEADER: PROFIL SISWA (Modern Hero Card)                                  --}}
        {{-- ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden">
            {{-- Aksen Dekorasi Futuristik --}}
            <div
                class="absolute -top-10 -right-10 w-32 height-32 bg-indigo-500/10 rounded-full blur-xl pointer-events-none">
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    @if ($student->foto)
    <img src="{{ asset('storage/' . $student->foto) }}" alt="{{ $student->nama }}"
        class="w-20 h-24 rounded-2xl object-cover border-2 border-indigo-500/20 shadow-sm">
@else
    <div
        class="w-20 h-24 rounded-2xl bg-gradient-to-br from-indigo-100 to-indigo-200 border-2 border-indigo-500/20 shadow-sm flex items-center justify-center text-3xl font-bold text-indigo-400">
        {{ strtoupper(substr($student->nama, 0, 1)) }}
    </div>
@endif
                    <span
                        class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-indigo-600 block">Siswa
                        Aktif</span>
                    <h1 class="text-xl font-extrabold text-slate-900 truncate mt-0.5 leading-tight">
                        {{ $student->nama }}
                    </h1>
                    <div class="flex flex-col gap-1 mt-2 text-sm text-slate-600 font-medium">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 0 1 2 2m4 0a6 6 0 0 1-7.743 5.743L11 17H9v2H7v2H4a1 1 0 0 1-1-1v-2.586a1 1 0 0 1 .293-.707l5.964-5.964A6 6 0 1 1 21 9z" />
                            </svg>
                            <span>NISN: <strong
                                    class="text-slate-800 font-semibold">{{ $student->nisn }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5" />
                            </svg>
                            <span>Kelas: <strong
                                    class="text-indigo-600 font-bold text-base">{{ $student->nama_kelas_aktif }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NOTIFIKASI SYSTEM --}}
        @if (session('success'))
            <div
                class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4 flex items-start gap-3 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <div class="font-bold text-green-800 text-base">Berhasil Dikirim</div>
                    <div class="text-green-700 text-sm font-medium mt-0.5">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <div class="font-bold text-red-800 text-base">Periksa Kembali Data Anda</div>
                    <ul class="mt-1 list-disc pl-4 text-sm text-red-700 font-medium space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- KONTEN AREA (TABULASI CONTAINER)                                         --}}
        {{-- ========================================================================= --}}
        <div class="mt-5 mb-10">

            {{-- TAB 1: FORMULIR AJUKAN IZIN --}}
            <div x-show="tab=='izin'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <form action="{{ route('wali_murid.store', $student->nisn) }}" method="POST"
                        enctype="multipart/form-data" x-data="{ jenis: 'sakit', surat: '0' }" class="space-y-6">
                        @csrf

                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 attachment 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Formulir Pengajuan Izin
                            </h2>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Mohon isi semua data di bawah ini dengan
                                benar.</p>
                        </div>

                        {{-- Input Jenis Pengajuan (Rombak Menjadi Card Pilihan Besar) --}}
                        <div>
                            <label class="text-base font-bold text-slate-800 block mb-2.5">Pilih Alasan Izin :</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none"
                                    :class="jenis == 'sakit' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-700 font-bold' :
                                        'border-slate-200 bg-white text-slate-600 font-medium hover:bg-slate-50'">
                                    <input type="radio" value="sakit" name="jenis" x-model="jenis" class="sr-only">
                                    <svg class="w-7 h-7 mb-1.5"
                                        :class="jenis == 'sakit' ? 'text-indigo-600' : 'text-slate-400'" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="text-base">Anak Sakit</span>
                                </label>

                                <label
                                    class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 select-none"
                                    :class="jenis == 'izin' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-700 font-bold' :
                                        'border-slate-200 bg-white text-slate-600 font-medium hover:bg-slate-50'">
                                    <input type="radio" value="izin" name="jenis" x-model="jenis"
                                        class="sr-only">
                                    <svg class="w-7 h-7 mb-1.5"
                                        :class="jenis == 'izin' ? 'text-indigo-600' : 'text-slate-400'" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                    </svg>
                                    <span class="text-base">Kepentingan Lain</span>
                                </label>
                            </div>
                        </div>

                        {{-- Input Tanggal (Diperbesar & Native Date Picker Modern) --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-base font-bold text-slate-800 block">Mulai Tanggal :</label>
                                <input type="date" name="tanggal_mulai"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 mt-1.5 text-base font-medium focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="text-base font-bold text-slate-800 block">Sampai Tanggal (Selesai)
                                    :</label>
                                <input type="date" name="tanggal_selesai"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 mt-1.5 text-base font-medium focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none"
                                    required>
                            </div>
                        </div>

                        {{-- DYNAMIC FIELD: JIKA SAKIT --}}
                        <div x-show="jenis=='sakit'"
                            class="space-y-5 bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <div>
                                <label class="text-base font-bold text-slate-800 block mb-2">Apakah ada surat
                                    keterangan dokter?</label>
                                <div class="flex gap-4">
                                    <label
                                        class="flex items-center gap-3 bg-white px-5 py-3 rounded-lg border border-slate-200 flex-1 cursor-pointer select-none">
                                        <input type="radio" name="punya_surat_dokter" value="1"
                                            x-model="surat" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-base font-bold text-slate-700">Ada Surat</span>
                                    </label>
                                    <label
                                        class="flex items-center gap-3 bg-white px-5 py-3 rounded-lg border border-slate-200 flex-1 cursor-pointer select-none">
                                        <input type="radio" name="punya_surat_dokter" value="0"
                                            x-model="surat" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-base font-bold text-slate-700">Tidak Ada</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="surat == '1'" class="animate-fade-in">
                                <label class="text-sm font-bold text-slate-700 block mb-1.5">Foto / Upload Surat Dokter
                                    :</label>
                                <input type="file" name="surat_dokter"
                                    class="w-full bg-white text-sm border border-slate-300 rounded-xl p-3 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>

                            <div x-show="surat == '0'" class="animate-fade-in">
                                <label class="text-sm font-bold text-slate-700 block">Tuliskan Penyakit/Gejala Anak
                                    :</label>
                                <textarea name="keterangan" rows="3"
                                    class="w-full border border-slate-300 bg-white rounded-xl px-4 py-3 mt-1.5 text-base placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all"
                                    placeholder="Contoh: Demam tinggi, flu berat, muntah-muntah..."></textarea>
                            </div>
                        </div>

                        {{-- DYNAMIC FIELD: JIKA KEPERLUAN LAIN --}}
                        <div x-show="jenis=='izin'"
                            class="bg-slate-50 p-4 rounded-xl border border-slate-200 animate-fade-in">
                            <label class="text-base font-bold text-slate-800 block">Alasan/Keperluan Penting :</label>
                            <textarea name="keterangan" rows="3"
                                class="w-full border border-slate-300 bg-white rounded-xl px-4 py-3 mt-1.5 text-base placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all"
                                placeholder="Contoh: Mengikuti upacara adat di desa, menghadiri pernikahan kakak kandung, dll..."></textarea>
                        </div>

                        <div class="border-t border-dashed border-slate-200 pt-5"></div>

                        {{-- Data Identitas Pengirim --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-base font-bold text-slate-800 block">Nama Orang Tua / Wali :</label>
                                <input type="text" name="nama_pengirim"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 mt-1.5 text-base font-medium focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none"
                                    placeholder="Tulis nama lengkap bapak/ibu" required>
                            </div>

                            <div>
                                <label class="text-base font-bold text-slate-800 block">Hubungan Keluarga :</label>
                                <select name="hubungan"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 mt-1.5 text-base font-bold text-slate-700 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                                    <option value="ayah">Ayah Kandung</option>
                                    <option value="ibu">Ibu Kandung</option>
                                    <option value="wali">Wali / Keluarga Lain</option>
                                </select>
                            </div>
                        </div>

                        {{-- Media Input: Selfie Kamera HP --}}
                        <div>
                            <label class="text-base font-bold text-slate-800 block">Ambil Foto Selfie Orang Tua
                                :</label>
                            <p class="text-xs text-slate-500 mb-2 font-medium">Klik tombol di bawah untuk langsung
                                membuka kamera HP bapak/ibu.</p>
                            <input type="file" name="selfie" accept="image/*" capture="user"
                                class="w-full bg-slate-50 text-sm border border-slate-300 rounded-xl p-3.5 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-extrabold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer shadow-inner"
                                required>
                        </div>

                        {{-- Media Input: Tanda Tangan Canvas Digital --}}
                        <div>
                            <label class="text-base font-bold text-slate-800 block mb-1">Tanda Tangan Digital :</label>
                            <p class="text-xs text-slate-500 mb-2.5 font-medium">Silakan usap/goreskan jari bapak/ibu
                                langsung di dalam kotak abu-abu di bawah ini.</p>
                            <div class="relative bg-slate-100 rounded-xl border-2 border-slate-300 p-1">
                                <canvas id="signature-pad" height="200"
                                    class="w-full bg-white rounded-lg touch-none shadow-inner"></canvas>
                                <input type="hidden" name="signature" id="signature">
                            </div>
                            <div class="mt-2.5 flex justify-end">
                                <button type="button" id="clear-signature"
                                    class="inline-flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Ulangi Tanda Tangan
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Submit Besar --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-extrabold text-lg rounded-xl shadow-md hover:from-indigo-700 hover:to-indigo-800 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 2 9 18zm0 0v-8" />
                                </svg>
                                Kirim Surat Pengajuan Izin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TAB 2: RIWAYAT IZIN --}}
            <div x-show="tab=='riwayat'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <h2 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Daftar Riwayat Izin
                    </h2>

                    @forelse($student->parentLeaveRequests as $item)
                        <div class="border border-slate-100 rounded-xl bg-slate-50/70 p-4 mb-3 last:mb-0 shadow-sm">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-extrabold text-slate-800 text-base">{{ ucfirst($item->jenis) }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-md font-bold"
                                            :class="'{{ $item->status }}'
                                            === 'approved' ? 'bg-green-100 text-green-800' : ('{{ $item->status }}'
                                                === 'pending' ? 'bg-amber-100 text-yellow-800' :
                                                'bg-red-100 text-red-800')">
                                            {{ $item->status == 'approved' ? 'Disetujui' : ($item->status == 'pending' ? 'Menunggu' : 'Ditolak') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-indigo-600 font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                        </svg>
                                        {{ $item->tanggal_mulai->format('d M Y') }} -
                                        {{ $item->tanggal_selesai->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="mt-3 pt-3 border-t border-slate-200/60 text-sm text-slate-600 font-medium space-y-1">
                                <div><span class="text-slate-400">Oleh:</span> <strong
                                        class="text-slate-700 font-semibold">{{ $item->nama_pengirim }}
                                        ({{ ucfirst($item->hubungan) }})</strong></div>
                                <div
                                    class="bg-white p-2.5 rounded-lg border border-slate-100 text-slate-700 mt-1 italic text-xs">
                                    "{{ $item->keterangan }}"
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-10 font-medium">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5" />
                            </svg>
                            Belum ada riwayat pengajuan izin.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TAB 3: CATATAN BIMBINGAN KONSELING (BK) --}}
            <div x-show="tab=='bk'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-6">

                    {{-- Sub-Seksi Permasalahan --}}
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Laporan Kasus & Kendala
                        </h2>

                        @forelse($student->permasalahan as $item)
                            <div class="border border-red-100 rounded-xl bg-red-50/30 p-4 mb-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="font-extrabold text-red-700 text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                        </svg>
                                        {{ $item->tanggal->format('d M Y') }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500">Guru:
                                        {{ $item->pegawai?->nama }}</span>
                                </div>
                                <div class="mt-2.5">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kejadian:
                                    </div>
                                    <p
                                        class="text-sm font-semibold text-slate-800 bg-white p-2 rounded-lg border border-slate-100 mt-1">
                                        {{ $item->permasalahan }}</p>
                                </div>
                                @if ($item->penyelesaian)
                                    <div class="mt-3 pt-3 border-t border-dashed border-red-200/60">
                                        <div class="text-xs font-bold text-green-600 uppercase tracking-wider">
                                            Solusi/Penyelesaian:</div>
                                        <p class="text-sm font-bold text-green-800 mt-0.5">{{ $item->penyelesaian }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-slate-400 py-6 border border-dashed rounded-xl font-medium">
                                Tidak ada catatan pelanggaran atau kendala.</div>
                        @endforelse
                    </div>

                    {{-- Sub-Seksi Konseling --}}
                    <div class="border-t border-slate-200 pt-5">
                        <h2 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 8h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2v4l-4-4H9a1.994 1.994 0 0 1-1.414-.586m0 0L11 14h4a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2v4l.586-.586z" />
                            </svg>
                            Riwayat Pendampingan (Konseling)
                        </h2>

                        @forelse($student->konseling as $item)
                            <div class="border border-slate-200 rounded-xl bg-slate-50/70 p-4 mb-3 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="font-extrabold text-slate-700 text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                        </svg>
                                        {{ $item->tanggal->format('d M Y') }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500">Konselor:
                                        {{ $item->pegawai?->nama }}</span>
                                </div>
                                <div class="mt-3 space-y-2 text-sm">
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 uppercase block">Topik
                                            Obrolan:</span>
                                        <p class="font-semibold text-slate-800">{{ $item->permasalahan }}</p>
                                    </div>
                                    @if ($item->hasil_konseling)
                                        <div class="bg-white p-2.5 rounded-lg border border-slate-100">
                                            <span class="text-xs font-bold text-indigo-600 uppercase block">Hasil
                                                Konseling:</span>
                                            <p class="font-medium text-slate-700 mt-0.5">{{ $item->hasil_konseling }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($item->tindak_lanjut)
                                        <div>
                                            <span class="text-xs font-bold text-green-600 uppercase block">Rencana
                                                Tindak Lanjut:</span>
                                            <p class="font-bold text-slate-800 mt-0.5">{{ $item->tindak_lanjut }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 py-6 border border-dashed rounded-xl font-medium">
                                Belum ada sesi bimbingan konseling yang tercatat.</div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- BOTTOM NAVIGATION: NAVBAR BAWAH (Sangat Ramah Layar Sentuh HP)            --}}
    {{-- ========================================================================= --}}
    <div
        class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-slate-200 px-4 py-2 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50 md:max-w-xl md:mx-auto md:rounded-t-2xl">
        <nav class="flex justify-around items-center max-w-xl mx-auto">

            {{-- Tombol Navigasi Izin --}}
            <button type="button" @click="tab='izin'"
                class="flex flex-col items-center gap-1 py-2 px-3 rounded-xl transition-all duration-200 shrink-0 w-24 select-none outline-none"
                :class="tab == 'izin' ? 'text-indigo-600 transform scale-105' : 'text-slate-400 hover:text-slate-600'">
                <svg class="w-6 h-6" :class="tab == 'izin' ? 'stroke-[2.5]' : 'stroke-2'" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-xs font-extrabold tracking-tight">Kirim Izin</span>
            </button>

            {{-- Tombol Navigasi Riwayat --}}
            <button type="button" @click="tab='riwayat'"
                class="flex flex-col items-center gap-1 py-2 px-3 rounded-xl transition-all duration-200 shrink-0 w-24 select-none outline-none"
                :class="tab == 'riwayat' ? 'text-indigo-600 transform scale-105' : 'text-slate-400 hover:text-slate-600'">
                <svg class="w-6 h-6" :class="tab == 'riwayat' ? 'stroke-[2.5]' : 'stroke-2'" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-extrabold tracking-tight">Riwayat</span>
            </button>

            {{-- Tombol Navigasi BK --}}
            <button type="button" @click="tab='bk'"
                class="flex flex-col items-center gap-1 py-2 px-3 rounded-xl transition-all duration-200 shrink-0 w-24 select-none outline-none"
                :class="tab == 'bk' ? 'text-indigo-600 transform scale-105' : 'text-slate-400 hover:text-slate-600'">
                <svg class="w-6 h-6" :class="tab == 'bk' ? 'stroke-[2.5]' : 'stroke-2'" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-xs font-extrabold tracking-tight">Catatan BK</span>
            </button>

        </nav>
    </div>

    {{-- ========================================================================= --}}
    {{-- LOGIK ENGINE: CANVAS SIGNATURE PAD                                        --}}
    {{-- ========================================================================= --}}
    <script>
        const canvas = document.getElementById('signature-pad');
        const hidden = document.getElementById('signature');
        const ctx = canvas.getContext('2d');

        // Mengatur resolusi internal canvas berdasarkan ukuran render CSS (mencegah distorsi tanda tangan pecah)
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);

            // Konfigurasi ketebalan kuas agar tebal dan halus dibaca orang tua
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a'; // Warna tinta hitam legam
        }

        window.addEventListener('resize', resizeCanvas);
        setTimeout(resizeCanvas, 100);

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
            e.preventDefault(); // Menahan layar agar tidak ikut tergulung (scroll) saat tanda tangan
            const p = position(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
        }

        function end() {
            if (!drawing) return;
            drawing = false;
            hidden.value = canvas.toDataURL();
        }

        // Event listener mouse & layar sentuh HP
        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        window.addEventListener('mouseup', end);

        canvas.addEventListener('touchstart', start);
        canvas.addEventListener('touchmove', move, {
            passive: false
        });
        canvas.addEventListener('touchend', end);

        // Aksi hapus tanda tangan
        document.getElementById('clear-signature').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hidden.value = '';
            resizeCanvas();
        });
    </script>
</body>

</html>
