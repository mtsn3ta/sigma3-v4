<x-filament-panels::page>

    <div style="display:flex;flex-direction:column;gap:1.5rem;max-width:42rem;">

        {{-- ===== GANTI PASSWORD ===== --}}
        <div
            style="background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.75rem;box-shadow:0 1px 3px rgba(0,0,0,.05);">

            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1.25rem;">
                <span style="font-size:1.4rem;">🔒</span>
                <h2 style="margin:0;font-size:1.15rem;font-weight:700;color:#0f172a;">Ganti Password</h2>
            </div>

            <form wire:submit="updatePassword">

                {{ $this->passwordForm }}

                <div style="margin-top:1.5rem;">
                    <button type="submit"
                        style="width:100%;padding:.9rem 1.5rem;background:#2563eb;color:#fff;border:none;border-radius:.85rem;font-weight:700;font-size:1rem;cursor:pointer;box-shadow:0 4px 10px rgba(37,99,235,.25);">
                        💾 Simpan Password
                    </button>
                </div>

            </form>

        </div>

        {{-- ===== SIGNATURE PAD ===== --}}
        <div x-data="sigPadComponent()" x-init="init()"
            style="background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.75rem;box-shadow:0 1px 3px rgba(0,0,0,.05);">

            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;">
                <span style="font-size:1.4rem;">✍️</span>
                <h2 style="margin:0;font-size:1.15rem;font-weight:700;color:#0f172a;">Tanda Tangan Digital</h2>
            </div>

            <p style="margin:0 0 1.25rem;font-size:.9rem;color:#64748b;line-height:1.5;">
                Tanda tangan ini akan otomatis muncul pada dokumen (proposal, laporan kegiatan, laporan bulanan)
                setiap kali Anda menyetujui/approve dokumen tersebut.
            </p>

            @if ($pegawai->ttd_digital)
                <div
                    style="margin-bottom:1.25rem;padding:.85rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:.85rem;">
                    <div style="font-size:.8rem;font-weight:600;color:#64748b;margin-bottom:.5rem;">Tanda tangan
                        tersimpan saat ini</div>
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pegawai->ttd_digital) }}"
                        style="height:70px;background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:.4rem;">
                </div>
            @endif

            <div style="border:2px dashed #cbd5e1;border-radius:.85rem;overflow:hidden;background:#f8fafc;">
                <canvas x-ref="canvas"
                    style="width:100%;height:200px;touch-action:none;cursor:crosshair;display:block;"></canvas>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem;">

                <button type="button" @click="clearPad()"
                    style="flex:1;min-width:140px;padding:.85rem 1rem;background:#f1f5f9;color:#334155;border:none;border-radius:.75rem;font-weight:600;cursor:pointer;">
                    🧹 Hapus Coretan
                </button>

                <button type="button" @click="save()"
                    style="flex:1;min-width:140px;padding:.85rem 1rem;background:#16a34a;color:#fff;border:none;border-radius:.75rem;font-weight:700;cursor:pointer;box-shadow:0 3px 8px rgba(22,163,74,.25);">
                    💾 Simpan Tanda Tangan
                </button>

                @if ($pegawai->ttd_digital)
                    <button type="button" wire:click="clearSignatureFile" wire:confirm="Hapus tanda tangan tersimpan?"
                        style="flex:1;min-width:140px;padding:.85rem 1rem;background:#fef2f2;color:#dc2626;border:none;border-radius:.75rem;font-weight:600;cursor:pointer;">
                        🗑️ Hapus TTD Tersimpan
                    </button>
                @endif

            </div>

        </div>

    </div>

    @assets
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    @endassets

    @script
        <script>
            window.sigPadComponent = function() {
                return {
                    pad: null,
                    init() {
                        const canvas = this.$refs.canvas;
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);

                        const resize = () => {
                            const data = this.pad ? this.pad.toData() : null;
                            canvas.width = canvas.offsetWidth * ratio;
                            canvas.height = canvas.offsetHeight * ratio;
                            canvas.getContext('2d').scale(ratio, ratio);
                            if (this.pad && data) this.pad.fromData(data);
                        };

                        this.pad = new SignaturePad(canvas, {
                            backgroundColor: 'rgba(255,255,255,0)',
                            penColor: '#0f172a',
                        });

                        resize();
                        window.addEventListener('resize', resize);
                    },
                    clearPad() {
                        this.pad.clear();
                    },
                    // Memotong area transparan di sekeliling coretan tanda tangan
                    trimCanvas(sourceCanvas) {
                        const ctx = sourceCanvas.getContext('2d');
                        const w = sourceCanvas.width;
                        const h = sourceCanvas.height;
                        const pixels = ctx.getImageData(0, 0, w, h).data;

                        let top = null,
                            bottom = null,
                            left = null,
                            right = null;

                        for (let y = 0; y < h; y++) {
                            for (let x = 0; x < w; x++) {
                                const alpha = pixels[(y * w + x) * 4 + 3];
                                if (alpha > 0) {
                                    if (top === null) top = y;
                                    bottom = y;
                                    if (left === null || x < left) left = x;
                                    if (right === null || x > right) right = x;
                                }
                            }
                        }

                        if (top === null) {
                            return sourceCanvas;
                        }

                        const padding = 12;
                        const cropX = Math.max(0, left - padding);
                        const cropY = Math.max(0, top - padding);
                        const cropW = Math.min(w, right + padding) - cropX;
                        const cropH = Math.min(h, bottom + padding) - cropY;

                        const trimmed = document.createElement('canvas');
                        trimmed.width = cropW;
                        trimmed.height = cropH;
                        trimmed.getContext('2d').drawImage(
                            sourceCanvas,
                            cropX, cropY, cropW, cropH,
                            0, 0, cropW, cropH,
                        );

                        return trimmed;
                    },
                    save() {
                        if (this.pad.isEmpty()) {
                            alert('Silakan buat tanda tangan terlebih dahulu.');
                            return;
                        }

                        const trimmed = this.trimCanvas(this.$refs.canvas);
                        const dataUrl = trimmed.toDataURL('image/png');

                        $wire.set('signatureBase64', dataUrl).then(() => {
                            $wire.call('saveSignature').then(() => this.pad.clear());
                        });
                    },
                }
            };
        </script>
    @endscript

</x-filament-panels::page>
