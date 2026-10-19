<div class="space-y-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
            {{ $record->labelKategori() }}
        </p>

        @if ($record->labelReferensi())
            <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                {{ $record->labelReferensi() }}
            </p>
        @endif

        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Dari {{ $record->user?->name ?? 'Kepala Madrasah' }}
            &middot; {{ $record->created_at?->translatedFormat('l, d F Y H:i') }}
        </p>
    </div>

    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
        <p class="whitespace-pre-line text-sm leading-relaxed text-gray-800 dark:text-gray-100">
            {{ $record->catatan }}
        </p>
    </div>

    <p class="text-xs text-gray-500 dark:text-gray-400">
        {{ $record->dibaca_pada ? 'Dibaca pada ' . $record->dibaca_pada->translatedFormat('d F Y H:i') : 'Belum dibaca' }}
    </p>
</div>
