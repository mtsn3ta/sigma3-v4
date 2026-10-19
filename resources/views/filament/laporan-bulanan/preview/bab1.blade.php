<div class="page-container mt-30">

    {{-- Judul BAB --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            BAB I
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            PENDAHULUAN
        </h2>
    </div>

    {{-- Sub BAB A --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            A. Pendahuluan
        </h3>

        <div class="rich-text-content">
            {!! $record->pendahuluan !!}
        </div>
    </div>

    {{-- Sub BAB B --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            B. Maksud dan Tujuan
        </h3>

        <div class="rich-text-content">
            {!! $record->tujuan !!}
        </div>
    </div>

</div>
