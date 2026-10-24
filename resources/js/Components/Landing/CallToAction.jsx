import React from "react";
import Container from "@/Components/UI/Container";
import Button from "@/Components/UI/Button";
import { ExternalLink, MessageCircle, Sparkles, ArrowRight, ShieldCheck } from "lucide-react";

export default function CallToAction({ settings = {} }) {
    const schoolName = settings.school_name || "MTsN 3 Tulungagung";

    return (
        <section id="cta" className="py-20 md:py-28 bg-white relative overflow-hidden">
            {/* Subtle Grid & Gradient Accent */}
            <div className="absolute inset-0 bg-[radial-gradient(#0596690a_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none" />
            <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-gradient-to-t from-emerald-100/50 to-transparent blur-3xl pointer-events-none" />

            <Container className="relative z-10">
                <div className="rounded-3xl border border-slate-200/90 bg-gradient-to-b from-white via-slate-50/50 to-emerald-50/20 p-8 sm:p-14 text-center max-w-4xl mx-auto shadow-[0_16px_50px_rgb(0,0,0,0.05)] relative">
                    <span className="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100/70 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-5">
                        <Sparkles className="w-3.5 h-3.5 text-emerald-600" />
                        <span>Penerimaan Peserta Didik Baru (PPDB)</span>
                    </span>

                    <h2 className="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-5">
                        Siap Menjadi Bagian dari <br />
                        <span className="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 bg-clip-text text-transparent">
                            Generasi Emas {schoolName}?
                        </span>
                    </h2>

                    <p className="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed mb-8">
                        Raih prestasi akademik dan kemuliaan akhlak bersama madrasah berbasis teknologi digital terdepan. Pendaftaran siswa baru kini lebih mudah, transparan, dan terintegrasi online.
                    </p>

                    <div className="flex flex-col sm:flex-row justify-center items-center gap-3.5">
                        <Button
                            href="https://ppdb.mtsn3tulungagung.sch.id"
                            external
                            variant="primary"
                            className="w-full sm:w-auto px-8 py-3.5 text-base font-bold shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 group"
                        >
                            <span>Daftar PPDB Online</span>
                            <ExternalLink className="w-4 h-4 transition-transform group-hover:scale-110" />
                        </Button>

                        <Button
                            href="https://wa.me/62881081297786?text=Assalamualaikum%20Warahmatullahi%20Wabarakatuh%20Admin%20PPDB%20MTsN%203%20Tulungagung"
                            external
                            target="_blank"
                            variant="secondary"
                            className="w-full sm:w-auto px-8 py-3.5 text-base font-bold flex items-center justify-center gap-2"
                        >
                            <MessageCircle className="w-4 h-4 text-emerald-600" />
                            <span>Konsultasi via WhatsApp</span>
                        </Button>
                    </div>

                    <div className="mt-8 pt-6 border-t border-slate-200/60 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 font-medium">
                        <div className="flex items-center gap-1.5">
                            <ShieldCheck className="w-4 h-4 text-emerald-600" />
                            <span>Proses Resmi Kemenag</span>
                        </div>
                        <div className="flex items-center gap-1.5">
                            <ShieldCheck className="w-4 h-4 text-emerald-600" />
                            <span>Seleksi Transparan & Akuntabel</span>
                        </div>
                        <div className="flex items-center gap-1.5">
                            <ShieldCheck className="w-4 h-4 text-emerald-600" />
                            <span>Beasiswa Prestasi & Tahfidz</span>
                        </div>
                    </div>
                </div>
            </Container>
        </section>
    );
}
