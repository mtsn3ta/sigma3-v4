<div class="space-y-5">
    @if ($record->dokumentasi_foto)
        <div>
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Foto Kegiatan KBM
            </p>
            <img
                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($record->dokumentasi_foto) }}"
                alt="Dokumentasi KBM {{ $record->tanggal?->translatedFormat('d F Y') }}"
                class="w-full rounded-lg border border-gray-200 object-cover dark:border-gray-700"
            >
        </div>
    @endif

    @if ($record->dokumentasi_video)
        <div>
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Video Kegiatan KBM
            </p>
            <video controls class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($record->dokumentasi_video) }}">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
    @endif

    @if (! $record->dokumentasi_foto && ! $record->dokumentasi_video)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Belum ada dokumentasi foto/video untuk jurnal ini.
        </p>
    @endif
</div>
