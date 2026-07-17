<div style="display: flex; flex-direction: column; gap: 2rem; width: 100%;">

    <div style="width: 100%;">
        {{ $this->form }}
    </div>

    <div
        style="
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.875rem;
        color: #4b5563;
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        width: fit-content;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    ">
        <div style="display: flex; align-items: center; gap: 0.375rem;">
            <span style="font-weight: 600; color: #059669;">✓</span>
            <span>= Hadir</span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.375rem;">
            <span style="font-weight: 600; color: #d97706;">M</span>
            <span>= Menstruasi</span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.375rem;">
            <span style="font-weight: 600; color: #dc2626;">✕</span>
            <span>= Tidak Hadir</span>
        </div>
    </div>

    <div style="width: 100%;">
        {{ $this->table }}
    </div>

    <x-filament-actions::modals />

</div>
