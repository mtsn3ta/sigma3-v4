<div class="space-y-5">
    @if ($record->dokumentasi_foto)
        <div>
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Foto Kegiatan KBM
            </p>
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($record->dokumentasi_foto) }}"
                    alt="Dokumentasi KBM {{ $record->tanggal?->translatedFormat('d F Y') }}"
                    class="w-full max-h-[480px] object-contain mx-auto"
                >
            </div>
        </div>
    @endif

    @if ($record->dokumentasi_video)
        <div>
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Video Kegiatan KBM
            </p>
            @php
                $video = $record->dokumentasi_video;
                $isUrl = str_starts_with($video, 'http://') || str_starts_with($video, 'https://');
                $youtubeId = null;
                if ($isUrl && (str_contains($video, 'youtube.com') || str_contains($video, 'youtu.be'))) {
                    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([\w-]{11})/', $video, $matches)) {
                        $youtubeId = $matches[1];
                    }
                }
            @endphp

            @if ($youtubeId)
                <div class="aspect-video w-full overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <iframe
                        class="w-full h-full"
                        src="https://www.youtube.com/embed/{{ $youtubeId }}"
                        title="Video KBM YouTube"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            @elseif ($isUrl)
                <div class="rounded-lg border border-blue-200 bg-blue-50/60 p-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-200 min-w-0">
                            <x-heroicon-o-link class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0" />
                            <span class="truncate font-medium">{{ $video }}</span>
                        </div>
                        <a
                            href="{{ $video }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 transition shrink-0"
                        >
                            <span>Tonton Video</span>
                            <x-heroicon-m-arrow-top-right-on-square class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            @else
                <video controls class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
                    <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($video) }}">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
            @endif
        </div>
    @endif

    @if (! $record->dokumentasi_foto && ! $record->dokumentasi_video)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Belum ada dokumentasi foto/video untuk jurnal ini.
        </p>
    @endif
</div>
