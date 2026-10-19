<div class="page-container mt-30">

    {{-- Judul LAMPIRAN --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            LAMPIRAN
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            DOKUMENTASI KEGIATAN
        </h2>
    </div>

    {{-- Grid Dokumentasi Responsif --}}
    @if (!empty($record->dokumentasi))
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
            @foreach ($record->dokumentasi as $foto)
                <div style="border: 1px solid #e5e7eb; padding: 0.75rem; border-radius: 0.5rem; background-color: #f9fafb; text-align: center;">
                    <img src="{{ Storage::disk('public')->url($foto) }}"
                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.375rem; display: block;"
                        alt="Dokumentasi {{ $loop->iteration }}">

                    <div style="margin-top: 0.75rem; font-size: 0.875rem; color: #4b5563; font-weight: 500;">
                        Dokumentasi #{{ $loop->iteration }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            style="
            margin-top: 4rem;
            text-align: center;
            font-style: italic;
            color: #6b7280;
            font-size: 0.875rem;
        ">
            Belum ada dokumentasi kegiatan yang diunggah pada periode ini.
        </div>
    @endif

</div>
