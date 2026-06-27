<div
    style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
    ">

    <div style="
            text-align:center;
            margin-bottom:40px;
        ">

        <div
            style="
                font-size:22px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            LAMPIRAN

        </div>

        <div
            style="
                margin-top:10px;
                font-size:18px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            DOKUMENTASI KEGIATAN

        </div>

    </div>

    @if (!empty($record->dokumentasi))

        <table style="
                width:100%;
                border-collapse:collapse;
            ">

            @foreach ($record->dokumentasi as $foto)
                @if ($loop->first || $loop->iteration % 2 == 1)
                    <tr>
                @endif

                <td
                    style="
                        width:50%;
                        padding:10px;
                        text-align:center;
                        vertical-align:top;
                    ">

                    <img src="{{ Storage::disk('public')->url($foto) }}"
                        style="
                            max-width:100%;
                            max-height:220px;
                            border:1px solid #999;
                            padding:4px;
                        ">

                    <div
                        style="
                            margin-top:8px;
                            font-size:12px;
                        ">

                        Dokumentasi {{ $loop->iteration }}

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
                margin-top:60px;
                text-align:center;
                font-style:italic;
                color:#666;
            ">

            Belum ada dokumentasi yang diunggah.

        </div>

    @endif

</div>
