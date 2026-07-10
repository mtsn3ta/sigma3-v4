@php
    $availableRoles = \App\Support\ActiveRole::availableRoles();
    $activeRole = \App\Support\ActiveRole::get();
@endphp

@if (count($availableRoles) > 1)

    <div
        style="
        margin:0px 12px;
        padding-top:12px;
        border-top:1px solid rgba(148,163,184,.18);
    ">

        {{-- Header --}}
        <div
            style="
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:10px;
        ">

            <span
                style="
                font-size:10px;
                font-weight:700;
                letter-spacing:.12em;
                text-transform:uppercase;
                color:#2d2e30;
            ">
                Mode Aktif
            </span>

            <span
                style="
                width:6px;
                height:6px;
                border-radius:999px;
                background:#22c55e;
                box-shadow:0 0 10px rgba(34,197,94,.5);
            ">
            </span>

        </div>

        <div style="
            display:flex;
            flex-direction:column;
            gap:4px;
        ">

            @foreach ($availableRoles as $role)
                @php
                    $isActive = $activeRole === $role;
                @endphp

                <form method="POST" action="{{ route('active-role.switch') }}" style="margin:0;">

                    @csrf

                    <input type="hidden" name="role" value="{{ $role }}">

                    <button type="submit"
                        style="
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        background: {{ $isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : 'transparent' }};
        color: {{ $isActive ? '#15803d' : '#475569' }};
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: {{ $isActive ? '0 6px 18px rgba(34,197,94,.08), inset 0 1px 0 rgba(255,255,255,.8)' : 'none' }};
        border: 1px solid {{ $isActive ? 'rgba(34,197,94,.2)' : 'transparent' }};
    "
                        onmouseover="
        this.style.background = '{{ $isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : '#f8fafc' }}';
        this.style.transform = 'translateX(2px)';
    "
                        onmouseout="
        this.style.background = '{{ $isActive ? 'linear-gradient(135deg, #f0fdf4, #fcfdfd)' : 'transparent' }}';
        this.style.transform = 'translateX(0)';
    ">


                        {{-- Icon --}}

                        <x-filament::icon :icon="\App\Support\ActiveRole::icon($role)"
                            style="
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        color: {{ $isActive ? '#16a34a' : '#64748b' }};
    " />


                        {{-- Label --}}

                        <span
                            style="
                            flex:1;
                            text-align:left;
                            font-size:13px;
                            font-weight:
                                {{ $isActive ? '600' : '500' }};
                        ">

                            {{ \App\Support\ActiveRole::label($role) }}

                        </span>

                        {{-- Active Indicator --}}

                        @if ($isActive)
                            <span
                                style="
                                width:8px;
                                height:8px;
                                border-radius:999px;
                                background:#22c55e;
                                box-shadow:0 0 12px rgba(37,99,235,.45);
                                flex-shrink:0;
                            ">
                            </span>
                        @endif

                    </button>

                </form>
            @endforeach

        </div>

    </div>

@endif

{{-- ===== MENU EDIT PROFIL ===== --}}
<div id="sigma3-edit-profil-block"
    style="
    margin:0.5px 6px 0;
    padding-top:0.5px;
    padding-bottom:8px;
    border-top:1px solid rgba(148,163,184,.18);
">

    <a href="{{ \App\Filament\Pages\EditProfile::getUrl() }}"
        style="
        width:100%;
        display:flex;
        align-items:center;
        gap:12px;
        padding:10px 12px;
        border-radius:12px;
        background:transparent;
        color:#475569;
        text-decoration:none;
        cursor:pointer;
        transition:all .2s ease;
        border:1px solid transparent;
        box-sizing:border-box;
    "
        onmouseover="
        this.style.background='#f8fafc';
        this.style.transform='translateX(2px)';
    "
        onmouseout="
        this.style.background='transparent';
        this.style.transform='translateX(0)';
    ">

        <x-filament::icon icon="heroicon-o-user-circle"
            style="
            width:18px;
            height:18px;
            flex-shrink:0;
            color:#64748b;
        " />

        <span style="flex:1;text-align:left;font-size:13px;font-weight:500;">
            Edit Profil
        </span>

    </a>

</div>

<style>
    #sigma3-edit-profil-block+div,
    #sigma3-edit-profil-block+nav,
    #sigma3-edit-profil-block+ul {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
</style>
