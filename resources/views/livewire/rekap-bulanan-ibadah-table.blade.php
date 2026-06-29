<div>

    {{ $this->form }}
    <div class="mb-4 flex flex-wrap gap-6 text-sm">

        <div>
            <span class="font-semibold text-green-600">✓</span>
            = Hadir
        </div>

        <div>
            <span class="font-semibold text-yellow-600">M</span>
            = Menstruasi
        </div>

        <div>
            <span class="font-semibold text-red-600">✕</span>
            = Tidak Hadir
        </div>

    </div>
    <div class="mt-6">

        {{ $this->table }}

    </div>

    <x-filament-actions::modals />

</div>
