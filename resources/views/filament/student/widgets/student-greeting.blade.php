<x-filament-widgets::widget>
    {{-- Hilangkan padding & background bawaan section --}}
    <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">

        {{-- Main Container --}}
        <div
            style="
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05), 0 10px 15px -5px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            backdrop-filter: blur(10px);
        ">

            {{-- Futuristic Soft Glow Effects (Pastel Orbs) --}}
            <div
                style="
                position: absolute; top: -30%; left: -5%; width: 350px; height: 350px;
                background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, rgba(255,255,255,0) 70%);
                border-radius: 50%; pointer-events: none; filter: blur(20px);
            ">
            </div>
            <div
                style="
                position: absolute; bottom: -40%; right: -5%; width: 300px; height: 300px;
                background: radial-gradient(circle, rgba(139, 92, 246, 0.08) 0%, rgba(255,255,255,0) 70%);
                border-radius: 50%; pointer-events: none; filter: blur(20px);
            ">
            </div>

            {{-- Text Content --}}
            <div style="position: relative; z-index: 10;">

                {{-- Greeting Text with Sharp Gradient --}}
                <h2
                    style="
                    font-size: 2.25rem;
                    font-weight: 800;
                    margin: 0;
                    letter-spacing: -0.025em;
                    background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                ">
                    {{ $greeting }}, {{ $nama }} 👋
                </h2>

                {{-- Badges & Info --}}
                <div
                    style="
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 1.25rem;
                    margin-top: 1.25rem;
                    font-size: 0.875rem;
                    color: #64748b;
                ">
                    {{-- Kelas Badge (Soft Blue Pill) --}}
                    <span
                        style="
                        background: #eff6ff;
                        border: 1px solid #dbeafe;
                        color: #2563eb;
                        padding: 0.35rem 1rem;
                        border-radius: 9999px;
                        font-weight: 700;
                        letter-spacing: 0.05em;
                        text-transform: uppercase;
                        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.05);
                    ">
                        Kelas {{ $namaKelas }}
                    </span>

                    <span style="color: #cbd5e1;">•</span>

                    {{-- NISN (Tech/Monospace Font) --}}
                    <span
                        style="letter-spacing: 0.05em; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;">
                        NISN: <strong style="color: #1e293b; font-weight: 700;">{{ $nisn }}</strong>
                    </span>

                    <span style="color: #cbd5e1;">•</span>

                    {{-- Tahun Ajaran --}}
                    <span style="letter-spacing: 0.05em;">
                        TA <strong style="color: #1e293b; font-weight: 700;">{{ $tahunAjaranAktif }}</strong>
                    </span>
                </div>
            </div>

            {{-- Avatar Profile --}}
            <div style="position: relative; z-index: 10;">
                @if ($foto)
                    <img src="{{ $foto }}" alt="{{ $nama }}"
                        style="
                        width: 85px;
                        height: 85px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 4px solid #ffffff;
                        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.15), 0 4px 10px -5px rgba(37, 99, 235, 0.1);
                        background: #ffffff;
                    ">
                @else
                    <div
                        style="
                        width: 85px;
                        height: 85px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 2.25rem;
                        font-weight: 800;
                        color: #ffffff;
                        border: 4px solid #ffffff;
                        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2), 0 4px 10px -5px rgba(37, 99, 235, 0.1);
                        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    ">
                        {{ $initial }}
                    </div>
                @endif
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
