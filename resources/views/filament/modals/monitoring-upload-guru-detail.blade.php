<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0;">
            {{ $pegawai->nama }}
        </h3>
        <p style="font-size: 0.85rem; color: #6b7280; margin: 0.25rem 0 0;">
            NIP: {{ $pegawai->nik_nip }}
        </p>
    </div>

    {{-- Perangkat Pembelajaran --}}
    <div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #065f46; margin: 0 0 0.5rem;">
            Perangkat Pembelajaran (5 terbaru)
        </h4>

        @forelse ($perangkat as $item)
            <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 0.4rem; background: #f9fafb;">
                <div style="display: flex; justify-content: space-between; gap: 0.75rem; align-items: baseline;">
                    <strong style="font-size: 0.875rem; color: #111827;">{{ $item->judul }}</strong>
                    <span style="font-size: 0.75rem; color: #6b7280; white-space: nowrap;">
                        {{ $item->created_at?->format('d M Y') }}
                    </span>
                </div>
                <div style="font-size: 0.8rem; color: #4b5563;">
                    {{ $item->jenis }}
                    @if ($item->mapel) &middot; {{ $item->mapel }} @endif
                    @if ($item->kelas) &middot; {{ $item->kelas->nama_kelas }} @endif
                </div>
            </div>
        @empty
            <p style="font-size: 0.85rem; color: #9ca3af; margin: 0;">Belum ada perangkat pembelajaran.</p>
        @endforelse
    </div>

    {{-- Daftar Nilai --}}
    <div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #92400e; margin: 0 0 0.5rem;">
            Daftar Nilai (5 terbaru)
        </h4>

        @forelse ($daftarNilai as $item)
            <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 0.4rem; background: #f9fafb;">
                <div style="display: flex; justify-content: space-between; gap: 0.75rem; align-items: baseline;">
                    <strong style="font-size: 0.875rem; color: #111827;">{{ $item->judul }}</strong>
                    <span style="font-size: 0.75rem; color: #6b7280; white-space: nowrap;">
                        {{ $item->tanggal?->format('d M Y') ?? $item->created_at?->format('d M Y') }}
                    </span>
                </div>
                <div style="font-size: 0.8rem; color: #4b5563;">
                    {{ $item->mapel }}
                    @if ($item->kelas) &middot; {{ $item->kelas->nama_kelas }} @endif
                </div>
                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.2rem;">
                    {{ $item->jenis_nilai ?? 'Nilai' }}
                    &middot; {{ $item->nilai_siswa_count ?? 0 }} siswa
                    @if ($item->nilai_siswa_avg_nilai !== null)
                        &middot; rata-rata {{ number_format((float) $item->nilai_siswa_avg_nilai, 2, ',', '.') }}
                    @endif
                </div>
            </div>
        @empty
            <p style="font-size: 0.85rem; color: #9ca3af; margin: 0;">Belum ada daftar nilai.</p>
        @endforelse
    </div>

    {{-- Jurnal & Dokumentasi --}}
    <div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e40af; margin: 0 0 0.5rem;">
            Jurnal Mengajar (5 terbaru)
        </h4>

        @forelse ($jurnal as $item)
            <div style="padding: 0.6rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 0.4rem; background: #f9fafb;">
                <div style="display: flex; justify-content: space-between; gap: 0.75rem; align-items: baseline;">
                    <strong style="font-size: 0.875rem; color: #111827;">
                        {{ $item->mapel }} &middot; {{ $item->kelas?->nama_kelas }} (Jam ke-{{ $item->jam_ke }})
                    </strong>
                    <span style="font-size: 0.75rem; color: #6b7280; white-space: nowrap;">
                        {{ $item->tanggal?->format('d M Y') }}
                    </span>
                </div>

                <div style="font-size: 0.8rem; color: #4b5563;">
                    {{ \Illuminate\Support\Str::limit($item->materi, 120) }}
                </div>

                @if ($item->dokumentasi_foto || $item->dokumentasi_video)
                    <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.4rem;">
                        @if ($item->dokumentasi_foto)
                            <a
                                href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->dokumentasi_foto) }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->dokumentasi_foto) }}"
                                    alt="Dokumentasi KBM"
                                    style="width: 64px; height: 64px; object-fit: cover; border-radius: 0.4rem; border: 1px solid #e5e7eb;"
                                >
                            </a>
                        @endif

                        @if ($item->dokumentasi_video)
                            <a
                                href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->dokumentasi_video) }}"
                                target="_blank"
                                rel="noopener"
                                style="font-size: 0.8rem; color: #2563eb; text-decoration: underline;"
                            >
                                Buka video KBM
                            </a>
                        @endif
                    </div>
                @else
                    <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">
                        Tanpa dokumentasi foto/video.
                    </div>
                @endif
            </div>
        @empty
            <p style="font-size: 0.85rem; color: #9ca3af; margin: 0;">Belum ada jurnal mengajar.</p>
        @endforelse
    </div>

</div>
