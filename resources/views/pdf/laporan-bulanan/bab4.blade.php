<div>

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            BAB IV
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            EVALUASI PEMBINAAN
        </h2>
    </div>

    {{-- Sub BAB A --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            A. Evaluasi
        </h3>

        <div class="rich-text-content">
            {!! $record->evaluasi !!}
        </div>
    </div>

    {{-- Sub BAB B --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            B. Kendala di Lapangan
        </h3>

        <div class="rich-text-content">
            {!! $record->kendala !!}
        </div>
    </div>

    {{-- Sub BAB C --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            C. Solusi dan Tindak Lanjut
        </h3>

        <div class="rich-text-content">
            {!! $record->solusi !!}
        </div>
    </div>

</div>
