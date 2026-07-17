<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- ================= TAB SWITCHER MODE ================= --}}
        <div
            style="display: inline-flex; background: #f3f4f6; border-radius: 0.65rem; padding: 0.25rem; gap: 0.25rem; width: fit-content;">
            <button wire:click="ubahMode('per_kelas')" type="button"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.1rem; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer;
                       background: {{ $modeTampilan === 'per_kelas' ? '#fff' : 'transparent' }};
                       color: {{ $modeTampilan === 'per_kelas' ? '#111827' : '#6b7280' }};
                       box-shadow: {{ $modeTampilan === 'per_kelas' ? '0 1px 3px rgba(0,0,0,0.1)' : 'none' }};">
                <x-heroicon-o-calendar-days style="width: 18px; height: 18px;" />
                Per Kelas (Rentang Tanggal)
            </button>
            <button wire:click="ubahMode('semua_kelas')" type="button"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.1rem; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer;
                       background: {{ $modeTampilan === 'semua_kelas' ? '#fff' : 'transparent' }};
                       color: {{ $modeTampilan === 'semua_kelas' ? '#111827' : '#6b7280' }};
                       box-shadow: {{ $modeTampilan === 'semua_kelas' ? '0 1px 3px rgba(0,0,0,0.1)' : 'none' }};">
                <x-heroicon-o-building-office-2 style="width: 18px; height: 18px;" />
                Semua Kelas (1 Hari)
            </button>
        </div>

        @if ($modeTampilan === 'semua_kelas')

            {{-- ================= FILTER: SEMUA KELAS ================= --}}
            <x-filament::section>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <x-filament::button size="sm" color="gray"
                            wire:click="setTanggalSemuaKelas('kemarin')">Kemarin</x-filament::button>
                        <x-filament::button size="sm" color="gray"
                            wire:click="setTanggalSemuaKelas('hari_ini')">Hari Ini</x-filament::button>
                    </div>

                    <div style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
                        <div style="min-width: 220px;">
                            <label
                                style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Tanggal</label>
                            <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                                wire:model="tanggalSemuaKelas">
                        </div>
                        <x-filament::button wire:click="tampilkanSemuaKelas" wire:loading.attr="disabled"
                            wire:target="tampilkanSemuaKelas" icon="heroicon-o-magnifying-glass">
                            <span wire:loading.remove wire:target="tampilkanSemuaKelas">Tampilkan Rekap Sekolah</span>
                            <span wire:loading wire:target="tampilkanSemuaKelas">Memuat...</span>
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            @if ($sudahDicariSemuaKelas)

                {{-- Ringkasan sekolah hari itu --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div
                        style="background: {{ $totalAlpaSemuaKelas > 0 ? '#fef2f2' : '#f9fafb' }}; border: 2px solid {{ $totalAlpaSemuaKelas > 0 ? '#fca5a5' : '#e5e7eb' }}; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 9999px; background: {{ $totalAlpaSemuaKelas > 0 ? '#fee2e2' : '#f3f4f6' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <x-heroicon-o-exclamation-triangle
                                style="width: 26px; height: 26px; color: {{ $totalAlpaSemuaKelas > 0 ? '#dc2626' : '#9ca3af' }};" />
                        </div>
                        <div>
                            <span
                                style="font-size: 0.8rem; font-weight: 600; color: {{ $totalAlpaSemuaKelas > 0 ? '#991b1b' : '#6b7280' }}; text-transform: uppercase;">Belum
                                Isi Jurnal</span>
                            <div
                                style="font-size: 1.75rem; font-weight: 800; color: {{ $totalAlpaSemuaKelas > 0 ? '#dc2626' : '#111827' }};">
                                {{ $totalAlpaSemuaKelas }} <span
                                    style="font-size: 0.9rem; font-weight: 500;">jam</span></div>
                        </div>
                    </div>

                    <div
                        style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 9999px; background: #dbeafe; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <x-heroicon-o-building-office-2 style="width: 26px; height: 26px; color: #2563eb;" />
                        </div>
                        <div>
                            <span
                                style="font-size: 0.8rem; font-weight: 600; color: #1e40af; text-transform: uppercase;">Total
                                Kelas</span>
                            <div style="font-size: 1.75rem; font-weight: 800; color: #2563eb;">
                                {{ $totalKelasSemuaKelas }}</div>
                        </div>
                    </div>

                    <div
                        style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 9999px; background: #dcfce7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <x-heroicon-o-check-circle style="width: 26px; height: 26px; color: #16a34a;" />
                        </div>
                        <div>
                            <span
                                style="font-size: 0.8rem; font-weight: 600; color: #166534; text-transform: uppercase;">Mengajar</span>
                            <div style="font-size: 1.75rem; font-weight: 800; color: #16a34a;">
                                {{ $totalMengajarSemuaKelas }} <span
                                    style="font-size: 0.9rem; font-weight: 500;">jam</span></div>
                        </div>
                    </div>

                    <div
                        style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 9999px; background: #fef3c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <x-heroicon-o-document-text style="width: 26px; height: 26px; color: #d97706;" />
                        </div>
                        <div>
                            <span
                                style="font-size: 0.8rem; font-weight: 600; color: #92400e; text-transform: uppercase;">Izin</span>
                            <div style="font-size: 1.75rem; font-weight: 800; color: #d97706;">
                                {{ $totalIzinSemuaKelas }} <span
                                    style="font-size: 0.9rem; font-weight: 500;">jam</span></div>
                        </div>
                    </div>
                </div>

                {{-- ================= DAFTAR GURU BELUM ISI JURNAL (paling dicari waka) ================= --}}
                @if (count($this->guruBelumIsi) > 0)
                    <x-filament::section>
                        <x-slot name="heading">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <x-heroicon-o-exclamation-triangle style="width: 22px; height: 22px; color: #dc2626;" />
                                <span style="color: #991b1b;">Guru yang Belum Mengisi Jurnal —
                                    {{ \Carbon\Carbon::parse($tanggalSemuaKelas)->locale('id')->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </x-slot>

                        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                            @foreach ($this->guruBelumIsi as $g)
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1.1rem; border-radius: 0.6rem; background: #fef2f2; border: 1px solid #fecaca; flex-wrap: wrap; gap: 0.5rem;">
                                    <div>
                                        <strong style="font-size: 1rem; color: #111827;">{{ $g['guru'] }}</strong>
                                        <span
                                            style="font-size: 0.85rem; color: #6b7280; margin-left: 0.5rem;">{{ $g['mapel'] ?: 'Mapel tidak diketahui' }}</span>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <x-filament::badge color="gray">Kelas {{ $g['kelas'] }}</x-filament::badge>
                                        <x-filament::badge color="danger">Jam
                                            ke-{{ $g['jam'] }}</x-filament::badge>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>
                @else
                    <div
                        style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
                        <x-heroicon-o-check-badge style="width: 28px; height: 28px; color: #16a34a; flex-shrink: 0;" />
                        <span style="font-size: 0.95rem; color: #166534; font-weight: 600;">Semua guru sudah mengisi
                            jurnal untuk tanggal ini. Tidak ada yang perlu ditindaklanjuti.</span>
                    </div>
                @endif

                {{-- ================= RINCIAN PER KELAS (accordion, prioritas kelas bermasalah di atas) ================= --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @forelse ($timelineSemuaKelas as $index => $item)
                        <div x-data="{ terbuka: {{ $item['totalAlpa'] > 0 ? 'true' : 'false' }} }"
                            style="border: 1px solid {{ $item['totalAlpa'] > 0 ? '#fca5a5' : '#e5e7eb' }}; border-radius: 0.75rem; overflow: hidden; background: #fff;">

                            <button type="button" @click="terbuka = !terbuka"
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: {{ $item['totalAlpa'] > 0 ? '#fef2f2' : '#f9fafb' }}; border: none; cursor: pointer; text-align: left;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if ($item['totalAlpa'] > 0)
                                        <x-heroicon-o-exclamation-triangle
                                            style="width: 20px; height: 20px; color: #dc2626; flex-shrink: 0;" />
                                    @else
                                        <x-heroicon-o-check-circle
                                            style="width: 20px; height: 20px; color: #16a34a; flex-shrink: 0;" />
                                    @endif
                                    <strong style="font-size: 1.05rem; color: #111827;">Kelas
                                        {{ $item['kelas']->nama_kelas }}</strong>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="display: flex; gap: 0.4rem; font-size: 0.72rem; font-weight: 600;">
                                        <span
                                            style="background: rgba(5,150,105,0.1); color: #059669; padding: 0.2rem 0.6rem; border-radius: 9999px;">Mengajar:
                                            {{ $item['totalMengajar'] }}</span>
                                        @if ($item['totalAlpa'] > 0)
                                            <span
                                                style="background: #dc2626; color: #fff; padding: 0.2rem 0.6rem; border-radius: 9999px;">Belum
                                                Diisi: {{ $item['totalAlpa'] }}</span>
                                        @endif
                                    </div>
                                    <x-heroicon-o-chevron-down x-show="!terbuka"
                                        style="width: 18px; height: 18px; color: #6b7280;" />
                                    <x-heroicon-o-chevron-up x-show="terbuka"
                                        style="width: 18px; height: 18px; color: #6b7280;" x-cloak />
                                </div>
                            </button>

                            <div x-show="terbuka" x-cloak style="padding: 1rem 1.25rem;">
                                <div style="overflow-x: auto; border-radius: 0.6rem; border: 1px solid #e5e7eb;">
                                    <table
                                        style="width: 100%; font-size: 0.85rem; text-align: left; border-collapse: collapse;">
                                        <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <th
                                                    style="padding: 0.6rem 0.9rem; font-weight: 600; color: #374151; width: 55px; text-align: center;">
                                                    Jam</th>
                                                <th
                                                    style="padding: 0.6rem 0.9rem; font-weight: 600; color: #374151; text-align: center;">
                                                    Status</th>
                                                <th style="padding: 0.6rem 0.9rem; font-weight: 600; color: #374151;">
                                                    Guru</th>
                                                <th style="padding: 0.6rem 0.9rem; font-weight: 600; color: #374151;">
                                                    Mapel</th>
                                                <th style="padding: 0.6rem 0.9rem; font-weight: 600; color: #374151;">
                                                    Materi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item['timeline'] as $row)
                                                @php
                                                    $rowBg = match ($row['status']) {
                                                        'alpa' => '#fef2f2',
                                                        'kosong' => '#fafafa',
                                                        default => 'transparent',
                                                    };
                                                @endphp
                                                <tr
                                                    style="border-bottom: 1px solid #f3f4f6; background-color: {{ $rowBg }};">
                                                    <td
                                                        style="padding: 0.6rem 0.9rem; text-align: center; font-weight: 700;">
                                                        {{ $row['jam'] }}</td>
                                                    <td style="padding: 0.6rem 0.9rem; text-align: center;">
                                                        @switch($row['status'])
                                                            @case('mengajar')
                                                                <x-filament::badge color="success">Mengajar</x-filament::badge>
                                                            @break

                                                            @case('izin')
                                                                <x-filament::badge color="warning">Izin</x-filament::badge>
                                                            @break

                                                            @case('libur')
                                                                <x-filament::badge
                                                                    color="info">Libur/Agenda</x-filament::badge>
                                                            @break

                                                            @case('kegiatan')
                                                                <x-filament::badge color="primary">Agenda
                                                                    Kelas</x-filament::badge>
                                                            @break

                                                            @case('alpa')
                                                                <x-filament::badge color="danger">⚠ Belum
                                                                    Diisi</x-filament::badge>
                                                            @break

                                                            @default
                                                                <x-filament::badge color="gray">Kosong</x-filament::badge>
                                                        @endswitch
                                                    </td>
                                                    <td style="padding: 0.6rem 0.9rem; font-weight: 500;">
                                                        {{ $row['guru'] ?: '-' }}</td>
                                                    <td style="padding: 0.6rem 0.9rem;">{{ $row['mapel'] ?: '-' }}
                                                    </td>
                                                    <td style="padding: 0.6rem 0.9rem;">{{ $row['materi'] ?: '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @empty
                            <x-filament::section>
                                <div style="text-align: center; padding: 3rem 1rem; color: #6b7280;">Belum ada kelas
                                    terdaftar.</div>
                            </x-filament::section>
                        @endforelse
                    </div>

                @endif
            @else
                {{-- ================= FILTER SECTION ================= --}}
                <x-filament::section>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">

                        {{-- Shortcut tanggal --}}
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <x-filament::button size="sm" color="gray" wire:click="setRentang('hari_ini')">
                                Hari Ini
                            </x-filament::button>
                            <x-filament::button size="sm" color="gray" wire:click="setRentang('minggu_ini')">
                                Minggu Ini
                            </x-filament::button>
                            <x-filament::button size="sm" color="gray" wire:click="setRentang('bulan_ini')">
                                Bulan Ini
                            </x-filament::button>
                        </div>

                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: flex-end;">

                            <div>
                                <label
                                    style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                                    Tanggal Mulai
                                </label>
                                <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                                    wire:model="tanggalMulai">
                            </div>

                            <div>
                                <label
                                    style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                                    Tanggal Selesai
                                </label>
                                <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                                    wire:model="tanggalSelesai">
                            </div>

                            <div>
                                <label
                                    style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                                    Kelas <span style="color: #dc2626;">*</span>
                                </label>
                                <select class="fi-select block w-full rounded-lg border-gray-300" wire:model="kelasId">
                                    <option value="">— Pilih Kelas —</option>
                                    @foreach ($this->daftarKelas as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                                {{-- Catatan: monitoring saat ini per-kelas, opsi "Semua Kelas" dihapus karena
                             MonitoringJurnalService::buildTimeline() mewajibkan satu kelasId. --}}
                            </div>

                            <div style="display: flex;">
                                <x-filament::button wire:click="tampilkan" wire:loading.attr="disabled"
                                    wire:target="tampilkan" icon="heroicon-o-magnifying-glass" style="width: 100%;">
                                    <span wire:loading.remove wire:target="tampilkan">Tampilkan Monitoring</span>
                                    <span wire:loading wire:target="tampilkan">Memuat...</span>
                                </x-filament::button>
                            </div>

                        </div>
                    </div>
                </x-filament::section>

                @if ($sudahDicari && $kelasId)

                    {{-- ================= RINGKASAN PERIODE (Kartu Besar) ================= --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">

                        {{-- Kartu paling penting: Belum Diisi — ditaruh PALING KIRI + paling mencolok --}}
                        <div
                            style="background: {{ $totalAlpa > 0 ? '#fef2f2' : '#f9fafb' }}; border: 2px solid {{ $totalAlpa > 0 ? '#fca5a5' : '#e5e7eb' }}; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 9999px; background: {{ $totalAlpa > 0 ? '#fee2e2' : '#f3f4f6' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <x-heroicon-o-exclamation-triangle
                                    style="width: 26px; height: 26px; color: {{ $totalAlpa > 0 ? '#dc2626' : '#9ca3af' }};" />
                            </div>
                            <div>
                                <span
                                    style="font-size: 0.8rem; font-weight: 600; color: {{ $totalAlpa > 0 ? '#991b1b' : '#6b7280' }}; text-transform: uppercase; letter-spacing: 0.03em;">
                                    Belum Isi Jurnal
                                </span>
                                <div
                                    style="font-size: 1.75rem; font-weight: 800; color: {{ $totalAlpa > 0 ? '#dc2626' : '#111827' }}; line-height: 1.1;">
                                    {{ $totalAlpa }} <span style="font-size: 0.9rem; font-weight: 500;">jam</span>
                                </div>
                            </div>
                        </div>

                        <div
                            style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 9999px; background: #dcfce7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <x-heroicon-o-check-circle style="width: 26px; height: 26px; color: #16a34a;" />
                            </div>
                            <div>
                                <span
                                    style="font-size: 0.8rem; font-weight: 600; color: #166534; text-transform: uppercase; letter-spacing: 0.03em;">Mengajar</span>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #16a34a; line-height: 1.1;">
                                    {{ $totalMengajar }} <span style="font-size: 0.9rem; font-weight: 500;">jam</span>
                                </div>
                            </div>
                        </div>

                        <div
                            style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 9999px; background: #fef3c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <x-heroicon-o-document-text style="width: 26px; height: 26px; color: #d97706;" />
                            </div>
                            <div>
                                <span
                                    style="font-size: 0.8rem; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.03em;">Izin
                                    (Tugas)</span>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #d97706; line-height: 1.1;">
                                    {{ $totalIzin }} <span style="font-size: 0.9rem; font-weight: 500;">jam</span>
                                </div>
                            </div>
                        </div>

                        <div
                            style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 9999px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <x-heroicon-o-minus-circle style="width: 26px; height: 26px; color: #6b7280;" />
                            </div>
                            <div>
                                <span
                                    style="font-size: 0.8rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.03em;">Jam
                                    Kosong</span>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #6b7280; line-height: 1.1;">
                                    {{ $totalKosong }} <span style="font-size: 0.9rem; font-weight: 500;">jam</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= INFO PERIODE & KELAS ================= --}}
                    <x-filament::section>
                        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
                            <div style="flex: 1; min-width: 250px;">
                                <span
                                    style="font-size: 0.875rem; color: #6b7280; display: block; margin-bottom: 0.25rem;">Periode
                                    Monitoring</span>
                                <strong style="font-size: 1.125rem; color: #111827;">
                                    @if ($tanggalMulai && $tanggalSelesai)
                                        {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') }}
                                        <span style="color: #9ca3af; font-weight: 400; margin: 0 0.5rem;">s/d</span>
                                        {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>

                            <div style="flex: 1; min-width: 250px;">
                                <span
                                    style="font-size: 0.875rem; color: #6b7280; display: block; margin-bottom: 0.25rem;">Kelas
                                    Terpilih</span>
                                <strong style="font-size: 1.125rem; color: #111827;">
                                    {{ $this->kelas?->nama_kelas ?? '-' }}
                                </strong>
                            </div>
                        </div>
                    </x-filament::section>

                    {{-- ================= LOMPAT CEPAT KE HARI BERMASALAH ================= --}}
                    @if (count($this->hariBermasalah) > 0)
                        <div
                            style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 0.75rem; padding: 1rem 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <x-heroicon-o-flag style="width: 20px; height: 20px; color: #dc2626;" />
                                <strong style="font-size: 0.95rem; color: #991b1b;">
                                    {{ count($this->hariBermasalah) }} hari perlu perhatian Anda:
                                </strong>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @foreach ($this->hariBermasalah as $hb)
                                    <a href="#hari-{{ $hb['tanggal']->format('Y-m-d') }}"
                                        style="display: inline-flex; align-items: center; gap: 0.4rem; background: #fff; border: 1px solid #fca5a5; color: #b91c1c; padding: 0.4rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; text-decoration: none;">
                                        {{ $hb['tanggal']->locale('id')->translatedFormat('d M') }}
                                        @if ($hb['totalAlpa'] > 0)
                                            <span
                                                style="background: #fee2e2; padding: 0.1rem 0.4rem; border-radius: 9999px;">{{ $hb['totalAlpa'] }}
                                                belum isi</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ================= TIMELINE HARIAN ================= --}}
                    @forelse($timeline as $hari)
                        <x-filament::section id="hari-{{ $hari['tanggal']->format('Y-m-d') }}">

                            <div
                                style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">

                                <h3
                                    style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    {{ $hari['tanggal']->locale('id')->translatedFormat('l, d F Y') }}
                                    @if ($hari['totalAlpa'] > 0)
                                        <x-heroicon-o-exclamation-triangle
                                            style="width: 20px; height: 20px; color: #dc2626;"
                                            title="Ada guru belum mengisi jurnal" />
                                    @endif
                                </h3>

                                <div
                                    style="display: flex; gap: 0.75rem; font-size: 0.75rem; font-weight: 600; flex-wrap: wrap;">
                                    <div
                                        style="background-color: rgba(5, 150, 105, 0.1); color: #059669; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(5, 150, 105, 0.2);">
                                        Mengajar: {{ $hari['totalMengajar'] }}
                                    </div>
                                    <div
                                        style="background-color: rgba(217, 119, 6, 0.1); color: #d97706; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(217, 119, 6, 0.2);">
                                        Izin: {{ $hari['totalIzin'] }}
                                    </div>
                                    @if ($hari['totalAlpa'] > 0)
                                        <div
                                            style="background-color: #dc2626; color: #fff; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid #dc2626;">
                                            Belum Diisi: {{ $hari['totalAlpa'] }}
                                        </div>
                                    @endif
                                    <div
                                        style="background-color: rgba(107, 114, 128, 0.1); color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(107, 114, 128, 0.2);">
                                        Kosong: {{ $hari['totalKosong'] }}
                                    </div>
                                </div>

                            </div>

                            <div
                                style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(12px);">
                                <table
                                    style="width: 100%; font-size: 0.875rem; text-align: left; border-collapse: collapse;">
                                    <thead
                                        style="background-color: rgba(249, 250, 251, 0.7); border-bottom: 1px solid #e5e7eb;">
                                        <tr>
                                            <th
                                                style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; width: 60px; text-align: center;">
                                                Jam</th>
                                            <th
                                                style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; text-align: center;">
                                                Status</th>
                                            <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Guru</th>
                                            <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Mapel</th>
                                            <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Materi /
                                                Tugas
                                            </th>
                                            <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Tidak
                                                Hadir
                                            </th>
                                            <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Catatan
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody style="color: #4b5563;">
                                        @foreach ($hari['timeline'] as $row)
                                            @php
                                                // Baris "alpa" (belum isi jurnal) dan "kosong" diberi latar mencolok
                                                // agar langsung terlihat tanpa perlu membaca badge satu per satu.
                                                $rowBg = match ($row['status']) {
                                                    'alpa' => '#fef2f2',
                                                    'kosong' => '#fafafa',
                                                    default => 'transparent',
                                                };
                                                $rowBorderLeft =
                                                    $row['status'] === 'alpa'
                                                        ? '4px solid #dc2626'
                                                        : '4px solid transparent';
                                            @endphp
                                            <tr
                                                style="border-bottom: 1px solid #f3f4f6; background-color: {{ $rowBg }}; border-left: {{ $rowBorderLeft }};">

                                                <td
                                                    style="padding: 0.75rem 1rem; text-align: center; font-weight: 700; color: #111827;">
                                                    {{ $row['jam'] }}
                                                </td>

                                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                                    @switch($row['status'])
                                                        @case('mengajar')
                                                            <x-filament::badge color="success">Mengajar</x-filament::badge>
                                                        @break

                                                        @case('izin')
                                                            <x-filament::badge color="warning">Izin</x-filament::badge>
                                                        @break

                                                        @case('libur')
                                                            <x-filament::badge color="info">Libur/Agenda</x-filament::badge>
                                                        @break

                                                        @case('kegiatan')
                                                            <x-filament::badge color="primary">Agenda Kelas</x-filament::badge>
                                                        @break

                                                        @case('alpa')
                                                            <x-filament::badge color="danger">⚠ Belum Diisi</x-filament::badge>
                                                        @break

                                                        @default
                                                            <x-filament::badge color="gray">Kosong</x-filament::badge>
                                                    @endswitch
                                                </td>

                                                <td style="padding: 0.75rem 1rem; font-weight: 500; color: #111827;">
                                                    {{ $row['guru'] ?: '-' }}
                                                </td>

                                                <td style="padding: 0.75rem 1rem;">
                                                    {{ $row['mapel'] ?: '-' }}
                                                </td>

                                                <td
                                                    style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem; {{ $row['status'] === 'alpa' ? 'color: #991b1b; font-weight: 600;' : '' }}">
                                                    {{ $row['materi'] ?: '-' }}
                                                </td>

                                                <td
                                                    style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem; color: #dc2626;">
                                                    {{ $row['tidak_hadir'] ?: '-' }}
                                                </td>

                                                <td
                                                    style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem; font-style: italic;">
                                                    {{ $row['catatan'] ?: '-' }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </x-filament::section>
                        @empty
                            <x-filament::section>
                                <div style="text-align: center; padding: 4rem 1rem;">
                                    <div
                                        style="display: inline-flex; justify-content: center; align-items: center; width: 4rem; height: 4rem; border-radius: 9999px; background-color: #f3f4f6; margin-bottom: 1rem;">
                                        <svg style="width: 2rem; height: 2rem; color: #9ca3af;" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                                        Tidak
                                        ada data untuk rentang ini</h3>
                                    <p style="font-size: 0.875rem; color: #6b7280;">Coba ubah tanggal atau periksa kembali
                                        kelas
                                        yang dipilih.</p>
                                </div>
                            </x-filament::section>
                        @endforelse
                    @elseif ($sudahDicari && !$kelasId)
                        <x-filament::section>
                            <div style="text-align: center; padding: 3rem 1rem;">
                                <x-heroicon-o-information-circle
                                    style="width: 2.5rem; height: 2.5rem; color: #9ca3af; margin: 0 auto 0.75rem auto;" />
                                <h3 style="font-size: 1.05rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">Pilih
                                    kelas terlebih dahulu</h3>
                                <p style="font-size: 0.875rem; color: #6b7280;">Monitoring jurnal ditampilkan per kelas agar
                                    mudah
                                    dipantau.</p>
                            </div>
                        </x-filament::section>
                    @else
                        <x-filament::section>
                            <div style="text-align: center; padding: 4rem 1rem;">
                                <div
                                    style="display: inline-flex; justify-content: center; align-items: center; width: 4rem; height: 4rem; border-radius: 9999px; background-color: #f3f4f6; margin-bottom: 1rem;">
                                    <svg style="width: 2rem; height: 2rem; color: #9ca3af;" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Belum
                                    ada
                                    data monitoring</h3>
                                <p style="font-size: 0.875rem; color: #6b7280;">Silakan pilih tanggal dan kelas, lalu klik
                                    'Tampilkan Monitoring'.</p>
                            </div>
                        </x-filament::section>
                    @endif
                @endif

            </div>
        </x-filament-panels::page>
