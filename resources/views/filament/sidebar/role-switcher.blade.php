@php
    $availableRoles = \App\Support\ActiveRole::availableRoles();
    $activeRole = \App\Support\ActiveRole::get();
@endphp

@if (count($availableRoles) > 1)
    <div class="sigma3-role-switcher">
        <div class="sigma3-role-switcher__header">
            <span class="sigma3-role-switcher__label">Mode Aktif</span>
            <span class="sigma3-role-switcher__status"></span>
        </div>

        <div class="sigma3-role-switcher__list">
            @foreach ($availableRoles as $role)
                @php
                    $isActive = $activeRole === $role;
                @endphp

                <form method="POST" action="{{ route('active-role.switch') }}" class="sigma3-role-switcher__form">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}">

                    <button
                        type="submit"
                        @class([
                            'sigma3-role-switcher__btn',
                            'sigma3-role-switcher__btn--active' => $isActive,
                        ])
                    >
                        <x-filament::icon
                            :icon="\App\Support\ActiveRole::icon($role)"
                            class="sigma3-role-switcher__icon"
                        />

                        <span class="sigma3-role-switcher__text">
                            {{ \App\Support\ActiveRole::label($role) }}
                        </span>

                        @if ($isActive)
                            <span class="sigma3-role-switcher__dot"></span>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@endif
