<div>

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            LAMPIRAN
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            DOKUMENTASI KEGIATAN
        </h2>
    </div>

    {{-- Grid Dokumentasi --}}
    @if (!empty($record->dokumentasi))
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            @foreach ($record->dokumentasi as $foto)
                @if ($loop->first || $loop->iteration % 2 == 1)
                    <tr>
                @endif

                <td
                    style="
                    width: 50%;
                    padding: 0.75rem;
                    text-align: center;
                    vertical-align: top;
                ">
                    {{-- Frame Foto --}}
                    <div
                        style="
                        border: 1px solid #e5e7eb;
                        padding: 0.5rem;
                        border-radius: 0.5rem;
                        background-color: #f9fafb;
                    ">
                        <img src="{{ Storage::disk('public')->url($foto) }}"
                            style="
                            max-width: 100%;
                            height: 220px;
                            object-fit: cover;
                            border-radius: 0.25rem;
                            display: block;
                            margin: 0 auto;
                        "
                            alt="Dokumentasi {{ $loop->iteration }}">

                        <div
                            style="
                            margin-top: 0.75rem;
                            font-size: 0.875rem;
                            color: #4b5563;
                            font-weight: 500;
                        ">
                            Gambar {{ $loop->iteration }}
                        </div>
                    </div>
                </td>

                @if ($loop->last || $loop->iteration % 2 == 0)
                    </tr>
                @endif
            @endforeach
        </table>
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
