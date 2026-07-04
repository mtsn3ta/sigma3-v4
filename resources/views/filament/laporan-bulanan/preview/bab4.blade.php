<style>
    .rich-text-content p {
        text-align: justify;
        line-height: 1.6;
        font-size: 1rem;
        color: #374151;
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .rich-text-content h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .rich-text-content h4:first-child {
        margin-top: 0;
    }

    /* Perbaikan List Ordered (Angka) */
    .rich-text-content ol {
        list-style-type: decimal;
        /* Ini yang memunculkan angka 1, 2, 3 */
        margin-top: 0;
        margin-bottom: 1rem;
        padding-left: 2rem;
        /* Jarak kiri agar angka tidak terpotong */
        color: #374151;
        line-height: 1.6;
    }

    /* Perbaikan List Unordered (Bullet) */
    .rich-text-content ul {
        list-style-type: disc;
        /* Ini yang memunculkan bullet */
        margin-top: 0;
        margin-bottom: 1rem;
        padding-left: 2rem;
        color: #374151;
        line-height: 1.6;
    }

    .rich-text-content li {
        margin-bottom: 0.5rem;
        text-align: justify;
        padding-left: 0.25rem;
        /* Jarak ekstra antara angka/bullet dengan teks */
    }

    .rich-text-content strong {
        font-weight: 600;
        color: #111827;
    }
</style>
<div
    style="
    width: 210mm;
    min-height: 297mm;
    margin: 2rem auto;
    background: #ffffff;
    padding: 25mm;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    box-sizing: border-box;
    color: #111827;
">

    {{-- Judul BAB --}}
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
