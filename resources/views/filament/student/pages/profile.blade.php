<x-filament-panels::page>

    <div style="display: flex; flex-direction: column; gap: 0.05rem;">

        {{-- ========================================================================= --}}
        {{-- DATA PRIBADI --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 2rem;
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            ">
                <h2
                    style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem 0; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    Data Pribadi
                </h2>

                <div style="display: flex; flex-wrap: wrap; gap: 2.5rem; align-items: flex-start;">

                    {{-- Avatar --}}
                    <div style="flex-shrink: 0; width: 100%; max-width: 140px; display: flex; justify-content: center;">
                        @if ($this->siswa->foto)
                            <img src="{{ asset('storage/' . $this->siswa->foto) }}" alt="{{ $this->siswa->nama }}"
                                style="
                                height: 140px; width: 140px; border-radius: 50%; object-fit: cover;
                                border: 4px solid #f8fafc;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                            ">
                        @else
                            <div
                                style="
                                height: 140px; width: 140px; border-radius: 50%;
                                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                                display: flex; align-items: center; justify-content: center;
                                font-size: 3.5rem; font-weight: 700; color: #94a3b8;
                                border: 4px solid #ffffff;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                            ">
                                {{ strtoupper(substr($this->siswa->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Data Grid --}}
                    <div
                        style="flex: 1; min-width: 250px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                NISN</div>
                            <div
                                style="font-size: 1rem; color: #111827; font-weight: 500; font-family: ui-monospace, monospace;">
                                {{ $this->siswa->nisn }}</div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                Nama Lengkap</div>
                            <div style="font-size: 1rem; color: #111827; font-weight: 600;">{{ $this->siswa->nama }}
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                Jenis Kelamin</div>
                            <div style="font-size: 1rem; color: #374151; font-weight: 500;">
                                {{ $this->siswa->jenis_kelamin }}</div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                Tempat, Tanggal Lahir</div>
                            <div style="font-size: 1rem; color: #374151; font-weight: 500;">
                                {{ $this->siswa->tempat_lahir }}
                                @if ($this->siswa->tanggal_lahir)
                                    , {{ $this->siswa->tanggal_lahir->translatedFormat('d F Y') }}
                                @endif
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem; grid-column: 1 / -1;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                Alamat</div>
                            <div
                                style="font-size: 1rem; color: {{ $this->siswa->alamat ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                                {{ $this->siswa->alamat ?: 'Tidak ada data' }}
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <div
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                                No. HP</div>
                            <div
                                style="font-size: 1rem; color: {{ $this->siswa->no_hp ? '#374151' : '#9ca3af' }}; font-weight: 500; font-family: ui-monospace, monospace;">
                                {{ $this->siswa->no_hp ?: 'Tidak ada data' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </x-filament::section>

        {{-- ========================================================================= --}}
        {{-- DATA AKADEMIK --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 2rem; background: #ffffff; border-radius: 1rem;
                border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            ">
                <h2
                    style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem 0; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    Data Akademik
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Kelas Aktif</div>
                        <div style="font-size: 1rem; color: #2563eb; font-weight: 700;">
                            {{ $this->siswa->nama_kelas_aktif ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Tahun Ajaran</div>
                        <div style="font-size: 1rem; color: #374151; font-weight: 500;">
                            {{ $this->siswa->kelasAktif?->tahunAjaran?->tahun ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Sekolah Asal</div>
                        <div style="font-size: 1rem; color: #374151; font-weight: 500;">
                            {{ $this->siswa->sekolah_asal ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Status Siswa</div>
                        <div>
                            <span
                                style="
                                display: inline-block; padding: 0.35rem 0.85rem; border-radius: 9999px;
                                font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
                                background: {{ strtolower($this->siswa->status) === 'aktif' ? '#ecfdf5' : '#fef2f2' }};
                                color: {{ strtolower($this->siswa->status) === 'aktif' ? '#059669' : '#dc2626' }};
                                border: 1px solid {{ strtolower($this->siswa->status) === 'aktif' ? '#a7f3d0' : '#fecaca' }};
                            ">
                                {{ $this->siswa->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- ========================================================================= --}}
        {{-- DATA ORANG TUA --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 2rem; background: #ffffff; border-radius: 1rem;
                border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            ">
                <h2
                    style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0 0 1.5rem 0; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    Data Orang Tua
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Nama Ayah</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->nama_ayah ? '#111827' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->nama_ayah ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Nama Ibu</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->nama_ibu ? '#111827' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->nama_ibu ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Pekerjaan Ayah</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->pekerjaan_ayah ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->pekerjaan_ayah ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Pekerjaan Ibu</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->pekerjaan_ibu ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->pekerjaan_ibu ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem; grid-column: 1 / -1;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Alamat Orang Tua</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->alamat_ortu ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->alamat_ortu ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            No. HP Orang Tua</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->telpon_ortu ? '#374151' : '#9ca3af' }}; font-weight: 500; font-family: ui-monospace, monospace;">
                            {{ $this->siswa->telpon_ortu ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- ========================================================================= --}}
        {{-- DATA WALI --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 2rem; background: #ffffff; border-radius: 1rem;
                border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            ">
                <h2
                    style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem 0; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    Data Wali
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Nama Wali</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->nama_wali ? '#111827' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->nama_wali ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Pekerjaan Wali</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->pekerjaan_wali ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->pekerjaan_wali ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem; grid-column: 1 / -1;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            Alamat Wali</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->alamat_wali ? '#374151' : '#9ca3af' }}; font-weight: 500;">
                            {{ $this->siswa->alamat_wali ?: '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <div
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600;">
                            No. HP Wali</div>
                        <div
                            style="font-size: 1rem; color: {{ $this->siswa->telpon_wali ? '#374151' : '#9ca3af' }}; font-weight: 500; font-family: ui-monospace, monospace;">
                            {{ $this->siswa->telpon_wali ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>
