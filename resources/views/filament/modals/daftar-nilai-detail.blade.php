<div style="display: flex; flex-direction: column; gap: 1.25rem;">

    @php
        $items = $record->nilaiSiswa->sortBy(fn ($item) => $item->siswa?->nama ?? '');
        $nilaiTerisi = $items->pluck('nilai')->filter(fn ($nilai) => $nilai !== null);
        $rataRata = $nilaiTerisi->isNotEmpty()
            ? number_format((float) $nilaiTerisi->avg(), 2, ',', '.')
            : '-';
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem;">
        <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Kelas</div>
            <div style="font-size: 0.95rem; font-weight: 700; color: #111827;">{{ $record->kelas?->nama_kelas ?? '-' }}</div>
        </div>

        <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Mata Pelajaran</div>
            <div style="font-size: 0.95rem; font-weight: 700; color: #111827;">{{ $record->mapel }}</div>
        </div>

        <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Jenis / Tanggal</div>
            <div style="font-size: 0.95rem; font-weight: 700; color: #111827;">
                {{ $record->jenis_nilai ?? '-' }}
                @if ($record->tanggal)
                    &middot; {{ $record->tanggal->format('d M Y') }}
                @endif
            </div>
        </div>

        <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">Sudah Dinilai</div>
            <div style="font-size: 0.95rem; font-weight: 700; color: #111827;">
                {{ $nilaiTerisi->count() }} / {{ $items->count() }} siswa
            </div>
        </div>

        <div style="padding: 0.6rem 0.75rem; border: 1px solid #d1fae5; border-radius: 0.5rem; background: #ecfdf5;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: #047857;">Rata-rata</div>
            <div style="font-size: 0.95rem; font-weight: 700; color: #065f46;">{{ $rataRata }}</div>
        </div>
    </div>

    @if ($record->deskripsi)
        <div style="font-size: 0.85rem; color: #4b5563;">
            {{ $record->deskripsi }}
        </div>
    @endif

    @if ($items->isEmpty())
        <p style="font-size: 0.85rem; color: #9ca3af; margin: 0;">
            Belum ada siswa pada penilaian ini.
        </p>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f3f4f6; text-align: left;">
                        <th style="padding: 0.5rem 0.6rem; border: 1px solid #e5e7eb; width: 40px;">No</th>
                        <th style="padding: 0.5rem 0.6rem; border: 1px solid #e5e7eb;">Nama Siswa</th>
                        <th style="padding: 0.5rem 0.6rem; border: 1px solid #e5e7eb; width: 110px; text-align: center;">Nilai</th>
                        <th style="padding: 0.5rem 0.6rem; border: 1px solid #e5e7eb;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td style="padding: 0.45rem 0.6rem; border: 1px solid #e5e7eb; text-align: center;">
                                {{ $index + 1 }}
                            </td>
                            <td style="padding: 0.45rem 0.6rem; border: 1px solid #e5e7eb;">
                                {{ $item->siswa?->nama ?? '-' }}
                            </td>
                            <td style="padding: 0.45rem 0.6rem; border: 1px solid #e5e7eb; text-align: center; font-weight: 700;">
                                {{ $item->nilai !== null ? number_format((float) $item->nilai, 2, ',', '.') : '-' }}
                            </td>
                            <td style="padding: 0.45rem 0.6rem; border: 1px solid #e5e7eb; color: #4b5563;">
                                {{ $item->catatan ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
