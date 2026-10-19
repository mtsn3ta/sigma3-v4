<div style="width:100%;">

    <style>
        @keyframes vkfModalFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes vkfModalPopIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .vkf-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .vkf-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
    </style>

    <div
        style="width:100%;background:#ffffff;border-radius:1.25rem;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;">

        {{-- Tab Navigation --}}
        <div style="display:flex;background:#f8fafc;border-bottom:2px solid #e5e7eb;padding:0.5rem 0.5rem 0;gap:0.5rem;">

            <button type="button" wire:click="$set('tab','parent')"
                style="flex:1;display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.9rem 1rem;font-size:1rem;font-weight:600;border:none;border-radius:0.75rem 0.75rem 0 0;cursor:pointer;
                {{ $tab === 'parent'
                    ? 'background:#ffffff;color:#2563eb;box-shadow:0 -2px 0 0 #2563eb inset;'
                    : 'background:transparent;color:#64748b;' }}">
                <span style="font-size:1.15rem;">📨</span>
                Pengajuan Orang Tua
            </button>

            <button type="button" wire:click="$set('tab','manual')"
                style="flex:1;display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.9rem 1rem;font-size:1rem;font-weight:600;border:none;border-radius:0.75rem 0.75rem 0 0;cursor:pointer;
                {{ $tab === 'manual'
                    ? 'background:#ffffff;color:#2563eb;box-shadow:0 -2px 0 0 #2563eb inset;'
                    : 'background:transparent;color:#64748b;' }}">
                <span style="font-size:1.15rem;">✍️</span>
                Verifikasi Manual
            </button>

        </div>

        <div style="padding:1.5rem;">

            @if ($tab === 'parent')
                @include('filament.guru-piket.parent-leave-request')
            @endif

            @if ($tab === 'manual')
                <div>
                    <form wire:submit="submit">

                        {{ $this->form }}

                        <div style="padding-top:1.5rem;">
                            <button type="submit"
                                style="width:100%;display:flex;align-items:center;justify-content:center;gap:0.6rem;padding:1rem 1.5rem;background:#2563eb;color:#ffffff;font-size:1.05rem;font-weight:700;border:none;border-radius:0.85rem;cursor:pointer;box-shadow:0 4px 10px rgba(37,99,235,.25);">
                                <span style="font-size:1.2rem;">✅</span>
                                Simpan Verifikasi
                            </button>
                        </div>

                    </form>
                </div>
            @endif

        </div>

    </div>

    <x-filament-actions::modals />

    {{-- ===================== MODAL: PREVIEW PENGAJUAN ===================== --}}
    @if ($showPreview && $previewRequest)
        <div wire:click="$set('showPreview', false)"
            style="position:fixed;inset:0;background:rgba(15,23,42,.55);display:flex;align-items:center;justify-content:center;z-index:9999;padding:1rem;animation:vkfModalFadeIn .18s ease-out;">

            <div onclick="event.stopPropagation()"
                style="background:#ffffff;border-radius:1.25rem;width:100%;max-width:56rem;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,.25);animation:vkfModalPopIn .2s ease-out;overflow:hidden;">

                {{-- Header --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;background:#f8fafc;">
                    <h2
                        style="margin:0;font-size:1.25rem;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:0.6rem;">
                        <span style="font-size:1.3rem;">🗂️</span>
                        Detail Pengajuan Orang Tua
                    </h2>
                    <button wire:click="$set('showPreview', false)"
                        style="width:2.5rem;height:2.5rem;border-radius:9999px;border:none;background:#f1f5f9;color:#475569;font-size:1.2rem;cursor:pointer;">
                        ✕
                    </button>
                </div>

                {{-- Body --}}
                <div class="vkf-scroll" style="padding:1.5rem;overflow-y:auto;">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;">

                        {{-- Data --}}
                        <div>
                            <h3 style="margin:0 0 .75rem;font-size:1rem;font-weight:700;color:#0f172a;">Data Pengajuan</h3>

                            <div style="display:flex;flex-direction:column;gap:.6rem;font-size:.95rem;color:#334155;">
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Nama Siswa</span>
                                    <span style="font-weight:600;">{{ $previewRequest->siswa->nama }}</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Kelas</span>
                                    <span style="font-weight:600;">{{ $previewRequest->siswa->nama_kelas_aktif ?? '-' }}</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Wali / Pengirim</span>
                                    <span style="font-weight:600;">{{ $previewRequest->nama_pengirim }} ({{ ucfirst($previewRequest->hubungan) }})</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Jenis Izin</span>
                                    <span style="font-weight:600;">{{ ucfirst($previewRequest->jenis) }}</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Tanggal Izin</span>
                                    <span style="font-weight:600;text-align:right;">
                                        {{ $previewRequest->tanggal_mulai->format('d M Y') }}
                                        @if ($previewRequest->tanggal_mulai != $previewRequest->tanggal_selesai)
                                            <br>s/d {{ $previewRequest->tanggal_selesai->format('d M Y') }}
                                        @endif
                                    </span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;border-bottom:1px dashed #e2e8f0;padding-bottom:.4rem;">
                                    <span style="color:#64748b;">Status Verifikasi</span>
                                    <span>
                                        @if ($previewRequest->status === 'approved')
                                            <span style="padding:.2rem .6rem;border-radius:9999px;background:#dcfce7;color:#15803d;font-weight:700;font-size:.8rem;">✓ Disetujui</span>
                                        @elseif ($previewRequest->status === 'rejected')
                                            <span style="padding:.2rem .6rem;border-radius:9999px;background:#fee2e2;color:#b91c1c;font-weight:700;font-size:.8rem;">✕ Ditolak</span>
                                        @else
                                            <span style="padding:.2rem .6rem;border-radius:9999px;background:#fef3c7;color:#92400e;font-weight:700;font-size:.8rem;">Menunggu</span>
                                        @endif
                                    </span>
                                </div>
                                @if ($previewRequest->verified_at)
                                    <div
                                        style="display:flex;justify-content:space-between;padding-bottom:.4rem;">
                                        <span style="color:#64748b;">Diverifikasi Oleh</span>
                                        <span style="font-weight:600;text-align:right;">
                                            {{ $previewRequest->verifier?->nama ?? 'Guru Piket' }}<br>
                                            <span style="font-size:.8rem;color:#94a3b8;font-weight:normal;">{{ $previewRequest->verified_at->format('d M Y, H:i') }} WIB</span>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div
                                style="margin-top:1.25rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:.75rem;padding:1rem;">
                                <div style="font-weight:700;color:#0f172a;margin-bottom:.4rem;">Keterangan Orang Tua</div>
                                <div style="color:#334155;font-size:.9rem;line-height:1.5;">
                                    {{ $previewRequest->keterangan ?: 'Tidak ada catatan tambahan.' }}</div>
                            </div>

                            @if ($previewRequest->verification_note)
                                <div
                                    style="margin-top:.75rem;background:#fef2f2;border:1px solid #fee2e2;border-radius:.75rem;padding:1rem;">
                                    <div style="font-weight:700;color:#991b1b;margin-bottom:.4rem;">Catatan Verifikasi Guru</div>
                                    <div style="color:#7f1d1d;font-size:.9rem;line-height:1.5;">
                                        {{ $previewRequest->verification_note }}</div>
                                </div>
                            @endif
                        </div>

                        {{-- Lampiran --}}
                        <div>
                            <h3 style="margin:0 0 .75rem;font-size:1rem;font-weight:700;color:#0f172a;">Lampiran</h3>

                            <div style="margin-bottom:1.25rem;">
                                <div style="font-weight:600;color:#334155;margin-bottom:.5rem;font-size:.9rem;">📷
                                    Selfie Orang Tua</div>
                                @if ($previewRequest->parent_selfie)
                                    <img src="{{ asset('storage/' . $previewRequest->parent_selfie) }}"
                                        style="width:100%;max-height:220px;object-fit:cover;border-radius:.75rem;border:1px solid #e2e8f0;">
                                @else
                                    <div
                                        style="padding:1rem;background:#f8fafc;border-radius:.75rem;color:#94a3b8;font-size:.85rem;text-align:center;">
                                        Tidak tersedia</div>
                                @endif
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <div style="font-weight:600;color:#334155;margin-bottom:.5rem;font-size:.9rem;">✍️ Tanda
                                    Tangan</div>
                                @if ($previewRequest->parent_signature)
                                    <img src="{{ $previewRequest->parent_signature }}"
                                        style="width:100%;max-height:120px;object-fit:contain;border:1px solid #e2e8f0;border-radius:.75rem;background:#fff;">
                                @else
                                    <div
                                        style="padding:1rem;background:#f8fafc;border-radius:.75rem;color:#94a3b8;font-size:.85rem;text-align:center;">
                                        Tidak tersedia</div>
                                @endif
                            </div>

                            @if ($previewRequest->doctor_letter)
                                <a href="{{ asset('storage/' . $previewRequest->doctor_letter) }}" target="_blank"
                                    style="display:inline-flex;align-items:center;gap:.5rem;color:#2563eb;font-weight:600;text-decoration:none;font-size:.9rem;">
                                    📄 Buka Surat Dokter (Dokumen/Foto)
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div
                    style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb;background:#f8fafc;display:flex;justify-content:flex-end;">
                    <button wire:click="$set('showPreview', false)"
                        style="padding:.75rem 1.5rem;background:#e2e8f0;color:#334155;border:none;border-radius:.75rem;font-weight:600;cursor:pointer;">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- ===================== MODAL: TOLAK PENGAJUAN ===================== --}}
    @if ($showRejectModal)
        <div wire:click="$set('showRejectModal', false)"
            style="position:fixed;inset:0;background:rgba(15,23,42,.55);display:flex;align-items:center;justify-content:center;z-index:9999;padding:1rem;animation:vkfModalFadeIn .18s ease-out;">

            <div onclick="event.stopPropagation()"
                style="background:#ffffff;border-radius:1.25rem;width:100%;max-width:32rem;box-shadow:0 20px 50px rgba(0,0,0,.25);animation:vkfModalPopIn .2s ease-out;overflow:hidden;">

                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #fee2e2;background:#fef2f2;">
                    <h2
                        style="margin:0;font-size:1.15rem;font-weight:700;color:#991b1b;display:flex;align-items:center;gap:.6rem;">
                        <span style="font-size:1.3rem;">⚠️</span>
                        Tolak Pengajuan
                    </h2>
                    <button wire:click="$set('showRejectModal', false)"
                        style="width:2.5rem;height:2.5rem;border-radius:9999px;border:none;background:#ffffff;color:#991b1b;font-size:1.2rem;cursor:pointer;">
                        ✕
                    </button>
                </div>

                <div style="padding:1.5rem;">
                    <label style="display:block;font-weight:600;color:#334155;margin-bottom:.5rem;font-size:.95rem;">
                        Alasan Penolakan
                    </label>
                    <textarea wire:model="verificationNote" rows="5" placeholder="Tuliskan alasan penolakan pengajuan ini..."
                        style="width:100%;border:1px solid #cbd5e1;border-radius:.75rem;padding:.85rem 1rem;font-size:.95rem;color:#0f172a;resize:vertical;box-sizing:border-box;"></textarea>
                </div>

                <div
                    style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb;background:#f8fafc;display:flex;justify-content:flex-end;gap:.75rem;">
                    <button wire:click="$set('showRejectModal', false)"
                        style="padding:.75rem 1.5rem;background:#e2e8f0;color:#334155;border:none;border-radius:.75rem;font-weight:600;cursor:pointer;">
                        Batal
                    </button>
                    <button wire:click="reject"
                        style="padding:.75rem 1.5rem;background:#dc2626;color:#ffffff;border:none;border-radius:.75rem;font-weight:700;cursor:pointer;box-shadow:0 4px 10px rgba(220,38,38,.25);">
                        Tolak Pengajuan
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
