<x-filament-panels::page>
    <div style="margin-bottom: 24px;">
        <div
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background-color: #ffffff; padding: 16px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 16px;">

            <div style="display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: 600; color: #374151;">Pilih Kelas:</label>
                <select wire:model.live="kelasId"
                    style="padding: 8px; border-radius: 6px; border: 1px solid #d1d5db; min-width: 150px;">
                    @foreach ($daftarKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <h2 style="font-size: 1.125rem; font-weight: 600; color: #374151; margin: 0;">Matriks Jadwal</h2>

            <div style="display: flex; gap: 12px;">
                <x-filament::button color="info" icon="heroicon-o-arrow-down-tray"
                    wire:click="mountAction('importJadwal')">
                    Import CSV
                </x-filament::button>
                <x-filament::button color="primary" icon="heroicon-o-cog-6-tooth"
                    x-on:click="$dispatch('open-modal', { id: 'set-kode-guru' })">
                    Kelola Guru &amp; Mapel
                </x-filament::button>
            </div>
        </div>

        <div
            style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 12px; font-size: 0.75rem; color: #4b5563;">
            <span style="display:flex; align-items:center; gap:6px;"><span
                    style="width:10px;height:10px;border-radius:3px;background:#ffffff;border:1px solid #d1d5db;"></span>
                Kosong (klik untuk isi)</span>
            <span style="display:flex; align-items:center; gap:6px;"><span
                    style="width:10px;height:10px;border-radius:3px;background:#dbeafe;border:1px solid #bfdbfe;"></span>
                Terisi</span>
            <span style="display:flex; align-items:center; gap:6px;"><span
                    style="width:10px;height:10px;border-radius:3px;background:#dcfce7;"></span> Ubudiyah</span>
            <span style="display:flex; align-items:center; gap:6px;"><span
                    style="width:10px;height:10px;border-radius:3px;background:#fef9c3;"></span> Istirahat</span>
            <span style="display:flex; align-items:center; gap:6px;"><span
                    style="width:10px;height:10px;border-radius:3px;background:#ffedd5;"></span> Zuhur</span>
        </div>

        <div wire:loading.class="opacity-50"
            style="overflow-x: auto; background-color: #ffffff; padding: 16px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-top: 16px; transition: opacity 0.15s;">
            <table
                style="width: 100%; min-width: 760px; table-layout: fixed; border-collapse: collapse; border: 1px solid #e5e7eb; font-size: 0.875rem; text-align: center;">
                <colgroup>
                    <col style="width: 90px;">
                    @for ($i = 0; $i < 6; $i++)
                        <col style="width: calc((100% - 90px) / 6);">
                    @endfor
                </colgroup>
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th
                            style="position: sticky; left: 0; z-index: 2; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 12px; text-align: left;">
                            Jam Ke
                        </th>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <th style="border: 1px solid #e5e7eb; padding: 12px;">{{ $hari }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Struktur baris ini mengikuti pola Senin/Selasa/Rabu/Kamis/Sabtu
                        // (lihat referensi jadwal): 0,1,2,3,4,UBDYH,IST,5,6,ZUHUR,7,8,9.
                        // Jumat TIDAK punya baris IST tersendiri di antara Jam3 & Jam4 —
                        // strukturnya sendiri berbeda total (UBDYH duluan, IST pendek
                        // setelah Jam3, tanpa ZUHUR/Jam6-9). Karena itu Jumat ditangani
                        // per-baris secara khusus di bawah, TANPA menambah baris baru,
                        // supaya kolom hari lain tidak ikut "kepotong" baris IST palsu.
                        $barisJadwal = [
                            ['id' => 'jam_0', 'label' => 'Jam 0'],
                            ['id' => 'jam_1', 'label' => 'Jam 1'],
                            ['id' => 'jam_2', 'label' => 'Jam 2'],
                            ['id' => 'jam_3', 'label' => 'Jam 3'],
                            ['id' => 'jam_4', 'label' => 'Jam 4'],
                            ['id' => 'ubdyh', 'label' => 'UBDYH'],
                            ['id' => 'ist', 'label' => 'IST'],
                            ['id' => 'jam_5', 'label' => 'Jam 5'],
                            ['id' => 'jam_6', 'label' => 'Jam 6'],
                            ['id' => 'zuhur', 'label' => 'ZUHUR'],
                            ['id' => 'jam_7', 'label' => 'Jam 7'],
                            ['id' => 'jam_8', 'label' => 'Jam 8'],
                            ['id' => 'jam_9', 'label' => 'Jam 9'],
                        ];
                    @endphp

                    @foreach ($barisJadwal as $baris)
                        <tr wire:key="row-{{ $baris['id'] }}">
                            <td
                                style="position: sticky; left: 0; z-index: 1; border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; background-color: #f9fafb; white-space: nowrap;">
                                {{ $baris['label'] }}
                            </td>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                @php
                                    $isClickable = false;
                                    $text = '';
                                    $bgColor = '#f9fafb';
                                    $textColor = '#6b7280';
                                    // $jam = nomor jam sebenarnya yang dipakai untuk data/wire:click.
                                    // Untuk baris 'ubdyh'/'ist' pada kolom Jumat, ini akan diisi
                                    // manual (4 dan 5) karena baris tsb dipakai ulang untuk
                                    // menampilkan Jam 4 & Jam 5 asli hari Jumat.
                                    $jam = null;

                                    if (str_starts_with($baris['id'], 'jam_')) {
                                        $rowJam = (int) str_replace('jam_', '', $baris['id']);

                                        if ($hari == 'Jumat') {
                                            // Struktur Jumat: UBDYH (di slot jam_0), Jam1-3,
                                            // lalu ISTIRAHAT pendek (di slot jam_4), lalu Jam4 &
                                            // Jam5 asli ditampilkan lewat baris ubdyh/ist di bawah.
                                            if ($rowJam == 0) {
                                                $text = 'UBUDIYAH';
                                                $bgColor = '#dcfce7';
                                                $textColor = '#166534';
                                            } elseif (in_array($rowJam, [1, 2, 3])) {
                                                $isClickable = true;
                                                $jam = $rowJam;
                                                $text = 'Kosong';
                                                $bgColor = '#ffffff';
                                                $textColor = '#9ca3af';
                                            } elseif ($rowJam == 4) {
                                                $text = 'ISTIRAHAT';
                                                $bgColor = '#fef9c3';
                                                $textColor = '#854d0e';
                                            } else {
                                                // jam_5..jam_9: sudah tidak dipakai, Jumat berakhir
                                                // di Jam 5 (ditampilkan lewat baris 'ist' di bawah).
                                                $bgColor = '#e5e7eb';
                                            }
                                        } else {
                                            if ($rowJam == 0) {
                                                if ($hari == 'Senin') {
                                                    $text = 'UPACARA';
                                                    $bgColor = '#f3f4f6';
                                                } elseif ($hari == 'Sabtu') {
                                                    $text = 'SABTU SEHAT';
                                                    $bgColor = '#f3f4f6';
                                                } else {
                                                    // Selasa/Rabu/Kamis: tidak ada Jam 0
                                                    $bgColor = '#e5e7eb';
                                                }
                                            } elseif ($rowJam == 9 && $hari != 'Selasa') {
                                                // hanya Selasa yang punya Jam 9
                                                $bgColor = '#e5e7eb';
                                            } else {
                                                $isClickable = true;
                                                $jam = $rowJam;
                                                $text = 'Kosong';
                                                $bgColor = '#ffffff';
                                                $textColor = '#9ca3af';
                                            }
                                        }
                                    } elseif ($baris['id'] == 'ubdyh') {
                                        if ($hari == 'Jumat') {
                                            // Baris ini dipakai ulang untuk menampilkan Jam 4 asli
                                            // hari Jumat (setelah istirahat pendek di atas).
                                            $isClickable = true;
                                            $jam = 4;
                                            $text = 'Kosong';
                                            $bgColor = '#ffffff';
                                            $textColor = '#9ca3af';
                                        } else {
                                            $text = 'UBUDIYAH';
                                            $bgColor = '#dcfce7';
                                            $textColor = '#166534';
                                        }
                                    } elseif ($baris['id'] == 'ist') {
                                        if ($hari == 'Jumat') {
                                            // Baris ini dipakai ulang untuk menampilkan Jam 5 asli
                                            // hari Jumat — jam terakhir sebelum sekolah usai.
                                            $isClickable = true;
                                            $jam = 5;
                                            $text = 'Kosong';
                                            $bgColor = '#ffffff';
                                            $textColor = '#9ca3af';
                                        } else {
                                            $text = 'ISTIRAHAT';
                                            $bgColor = '#fef9c3';
                                            $textColor = '#854d0e';
                                        }
                                    } elseif ($baris['id'] == 'zuhur') {
                                        if ($hari != 'Jumat') {
                                            $text = 'ZUHUR';
                                            $bgColor = '#ffedd5';
                                            $textColor = '#9a3412';
                                        } else {
                                            $bgColor = '#e5e7eb';
                                        }
                                    }

                                    $terisi = $isClickable && $jam !== null && isset($jadwalData[$hari][$jam]);
                                    if ($terisi) {
                                        $bgColor = '#dbeafe';
                                    }
                                @endphp

                                <td wire:key="cell-{{ $hari }}-{{ $baris['id'] }}"
                                    title="{{ $isClickable ? ($terisi ? 'Klik untuk ubah jadwal' : 'Klik untuk isi jadwal') : '' }}"
                                    style="border: 1px solid #e5e7eb; padding: 12px; text-align: center; background-color: {{ $bgColor }}; {{ $isClickable ? 'cursor: pointer; transition: background 0.15s, box-shadow 0.15s;' : 'cursor: not-allowed;' }}"
                                    @if ($isClickable) onmouseover="this.style.backgroundColor='{{ $terisi ? '#bfdbfe' : '#eff6ff' }}'; this.style.boxShadow='inset 0 0 0 1px #3b82f6';"
                                        onmouseout="this.style.backgroundColor='{{ $bgColor }}'; this.style.boxShadow='none';"
                                        wire:click="pilihSelJadwal('{{ $hari }}', '{{ $jam }}')" @endif>

                                    @if ($isClickable)
                                        @if ($terisi)
                                            <div
                                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; width: 100%; overflow: hidden;">
                                                <span
                                                    style="font-weight: 800; color: #1f2937; font-size: 0.8rem; background-color: #ffffff; padding: 2px 8px; border-radius: 4px; border: 1px solid #bfdbfe; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="{{ $jadwalData[$hari][$jam]['kode_guru'] }}">
                                                    {{ $jadwalData[$hari][$jam]['kode_guru'] }}
                                                </span>
                                                <span
                                                    style="font-size: 0.65rem; color: #4b5563; font-weight: 600; text-transform: uppercase; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="{{ $jadwalData[$hari][$jam]['mapel'] }}">
                                                    {{ $jadwalData[$hari][$jam]['mapel'] }}
                                                </span>
                                                <span
                                                    style="font-size: 0.6rem; color: #9ca3af; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="{{ $jadwalData[$hari][$jam]['nama_guru'] }}">
                                                    {{ $jadwalData[$hari][$jam]['nama_guru'] }}
                                                </span>
                                            </div>
                                        @else
                                            <span
                                                style="color: {{ $textColor }}; font-size: 0.75rem; font-style: italic;">{{ $text }}</span>
                                        @endif
                                    @else
                                        <span
                                            style="color: {{ $textColor }}; font-size: 0.75rem; font-weight: bold;">{{ $text }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Kelola Guru & Mapel (tidak berubah, sudah bekerja dengan baik) --}}
    <x-filament::modal id="set-kode-guru" width="4xl">
        <x-slot name="heading">Kelola Guru &amp; Mapel</x-slot>
        <div style="padding: 20px;">
            {{ $this->table }}
        </div>
    </x-filament::modal>

    {{-- Modal Isi Jadwal — modal manual, BUKAN Filament Action, agar tidak kena
         bug modal Action yang ke-clip/tersembunyi pada custom page + HasTable. --}}
    <x-filament::modal id="isi-jadwal" width="md">
        <x-slot name="heading">
            Isi Jadwal: {{ $selectedHari }} @if ($selectedJam)
                - Jam ke-{{ $selectedJam }}
            @endif
        </x-slot>

        <div style="display: flex; flex-direction: column; gap: 16px; padding: 4px;">
            <div>
                <label style="display:block; font-weight:600; color:#374151; margin-bottom:6px; font-size:0.875rem;">
                    Pilih Guru &amp; Mata Pelajaran <span style="color:#ef4444;">*</span>
                </label>
                <select wire:model="selectedPlotingGuruId"
                    style="width:100%; padding:9px; border-radius:8px; border:1px solid #d1d5db; font-size:0.875rem;">
                    <option value="">— Pilih guru & mapel —</option>
                    @foreach ($this->plotingGuruOptions as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('selectedPlotingGuruId')
                    <p style="color:#ef4444; font-size:0.75rem; margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; flex-wrap: wrap;">
                <div style="display:flex; gap:8px;">
                    <x-filament::button color="success" wire:click="simpanJadwal" wire:loading.attr="disabled">
                        Submit
                    </x-filament::button>
                    <x-filament::button color="gray" wire:click="closeJadwalModal">
                        Cancel
                    </x-filament::button>
                </div>

                @if ($selectedJadwalId)
                    <x-filament::button color="danger" icon="heroicon-o-trash" wire:click="hapusJadwal"
                        wire:confirm="Yakin ingin menghapus jadwal pada sel ini?">
                        Hapus Jadwal
                    </x-filament::button>
                @endif
            </div>
        </div>
    </x-filament::modal>
</x-filament-panels::page>
