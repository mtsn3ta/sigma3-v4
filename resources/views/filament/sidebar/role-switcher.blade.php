@php
    $availableRoles = \App\Support\ActiveRole::availableRoles();
    $activeRole = \App\Support\ActiveRole::get();
@endphp

@if (count($availableRoles) > 1)
    <div class="sigma-role-dropdown-container" x-data="{ open: false }">
        <style>
            [x-cloak] { display: none !important; }
            .sigma-role-dropdown-container {
                position: relative;
                margin: 0.25rem 0.75rem 0.75rem 0.75rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid #f1f5f9;
            }
            .dark .sigma-role-dropdown-container {
                border-bottom-color: #1e293b;
            }
            .sigma-role-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.35rem;
                padding: 0 0.25rem;
            }
            .sigma-role-header-title {
                font-size: 0.625rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #94a3b8;
            }
            .sigma-role-header-dot {
                width: 6px;
                height: 6px;
                border-radius: 9999px;
                background-color: #10b981;
                box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
            }
            .sigma-role-toggle-btn {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                padding: 0.5rem 0.75rem;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                font-size: 0.8125rem;
                font-weight: 600;
                color: #1e293b;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            }
            .sigma-role-toggle-btn:hover {
                background: #f1f5f9;
                border-color: #cbd5e1;
            }
            .dark .sigma-role-toggle-btn {
                background: #1e293b;
                border-color: #334155;
                color: #f1f5f9;
            }
            .dark .sigma-role-toggle-btn:hover {
                background: #334155;
            }
            .sigma-role-badge-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 1.5rem;
                height: 1.5rem;
                border-radius: 0.5rem;
                background: #ecfdf5;
                color: #059669;
                flex-shrink: 0;
            }
            .dark .sigma-role-badge-icon {
                background: rgba(6, 78, 59, 0.5);
                color: #34d399;
            }
            .sigma-role-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                margin-top: 0.35rem;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
                padding: 0.35rem;
                z-index: 50;
            }
            .dark .sigma-role-menu {
                background: #0f172a;
                border-color: #334155;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            }
            .sigma-role-item-btn {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                padding: 0.5rem 0.625rem;
                border-radius: 0.5rem;
                font-size: 0.75rem;
                font-weight: 500;
                color: #475569;
                background: transparent;
                border: none;
                cursor: pointer;
                transition: all 0.15s ease;
                text-align: left;
            }
            .sigma-role-item-btn:hover {
                background: #f1f5f9;
                color: #0f172a;
            }
            .sigma-role-item-btn.active {
                background: #ecfdf5;
                color: #047857;
                font-weight: 700;
            }
            .dark .sigma-role-item-btn {
                color: #cbd5e1;
            }
            .dark .sigma-role-item-btn:hover {
                background: #1e293b;
                color: #ffffff;
            }
            .dark .sigma-role-item-btn.active {
                background: rgba(6, 78, 59, 0.4);
                color: #6ee7b7;
            }
        </style>

        <div class="sigma-role-header">
            <span class="sigma-role-header-title">Mode Peran Aktif</span>
            <span class="sigma-role-header-dot"></span>
        </div>

        <button
            type="button"
            x-on:click="open = !open"
            class="sigma-role-toggle-btn"
        >
            <div style="display: flex; align-items: center; gap: 0.5rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <div class="sigma-role-badge-icon">
                    <x-filament::icon :icon="\App\Support\ActiveRole::icon($activeRole)" style="width: 14px; height: 14px;" />
                </div>
                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ \App\Support\ActiveRole::label($activeRole) }}</span>
            </div>
            <x-filament::icon icon="heroicon-m-chevron-up-down" style="width: 16px; height: 16px; color: #94a3b8; flex-shrink: 0;" />
        </button>

        <div
            x-show="open"
            x-cloak
            x-on:click.outside="open = false"
            class="sigma-role-menu"
        >
            @foreach ($availableRoles as $role)
                @php
                    $isActive = $activeRole === $role;
                @endphp
                <form method="POST" action="{{ route('active-role.switch') }}" style="margin: 0; padding: 0;">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}">
                    <button
                        type="submit"
                        class="sigma-role-item-btn {{ $isActive ? 'active' : '' }}"
                    >
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <x-filament::icon
                                :icon="\App\Support\ActiveRole::icon($role)"
                                style="width: 16px; height: 16px; color: {{ $isActive ? '#059669' : '#94a3b8' }}; flex-shrink: 0;"
                            />
                            <span>{{ \App\Support\ActiveRole::label($role) }}</span>
                        </div>
                        @if ($isActive)
                            <x-filament::icon icon="heroicon-m-check" style="width: 14px; height: 14px; color: #059669; flex-shrink: 0;" />
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@endif
