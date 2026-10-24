import React from "react";
import Container from "@/Components/UI/Container";
import { Quote, Sparkles, GraduationCap, CheckCircle2 } from "lucide-react";

export default function PrincipalVision({ settings = {} }) {
    const principalName = settings.principal || "Kepala Madrasah MTsN 3 Tulungagung";
    const principalPhoto = settings.principal_photo || null;
    const schoolName = settings.school_name || "MTsN 3 Tulungagung";

    return (
        <section id="sambutan" className="py-16 md:py-24 bg-gradient-to-b from-slate-50/60 to-white border-b border-slate-100 relative">
            <Container>
                <div className="max-w-5xl mx-auto rounded-3xl border border-emerald-100 bg-white/90 p-8 sm:p-12 shadow-[0_12px_40px_rgb(0,0,0,0.04)] backdrop-blur-xl relative overflow-hidden">
                    {/* Background Accent */}
                    <div className="absolute top-0 right-0 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none" />
                    <div className="absolute -left-6 -bottom-6 text-emerald-600/5 pointer-events-none">
                        <Quote className="w-56 h-56 -rotate-12 stroke-1" />
                    </div>

                    <div className="relative z-10 grid md:grid-cols-12 gap-8 md:gap-12 items-center">
                        {/* Photo Column */}
                        <div className="md:col-span-4 flex flex-col items-center text-center">
                            <div className="relative w-44 h-52 sm:w-48 sm:h-56 rounded-2xl overflow-hidden bg-gradient-to-b from-emerald-100 to-slate-100 border-2 border-emerald-500/30 shadow-lg p-1.5 mb-4 group">
                                <div className="w-full h-full rounded-xl overflow-hidden bg-white flex items-center justify-center">
                                    {principalPhoto ? (
                                        <img
                                            src={principalPhoto}
                                            alt={principalName}
                                            className="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
                                        />
                                    ) : (
                                        <GraduationCap className="w-20 h-20 text-emerald-600 stroke-1" />
                                    )}
                                </div>
                            </div>

                            <h3 className="text-base font-black text-slate-900 tracking-tight">
                                {principalName}
                            </h3>
                            <p className="text-xs font-semibold text-emerald-700 mt-0.5">
                                Kepala {schoolName}
                            </p>
                        </div>

                        {/* Content & Quote Column */}
                        <div className="md:col-span-8 flex flex-col justify-center">
                            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-4 self-start">
                                <Sparkles className="w-3.5 h-3.5 text-emerald-600" />
                                <span>Sambutan & Visi Kepemimpinan</span>
                            </div>

                            <h2 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug mb-4">
                                "Mendidik dengan Hati, Membangun Generasi dengan Teknologi & Akhlak Mulia."
                            </h2>

                            <p className="text-sm sm:text-base text-slate-600 leading-relaxed mb-5">
                                Selamat datang di ekosistem digital resmi <strong className="text-slate-800 font-semibold">{schoolName}</strong>. Melalui platform terpadu SIGMA-3, kami berkomitmen menghadirkan transparansi penuh bagi wali murid, pembiasaan ibadah istiqomah bagi para santri/siswa, serta kemudahan akses layanan pendidikan yang berdaya saing global.
                            </p>

                            {/* Official Vision Quote Box */}
                            <div className="rounded-xl border border-emerald-200/80 bg-emerald-50/50 p-4 border-l-4 border-l-emerald-600">
                                <p className="text-xs uppercase font-bold tracking-wider text-emerald-800 mb-1">
                                    Visi Resmi Madrasah:
                                </p>
                                <p className="text-sm font-semibold text-slate-800 italic">
                                    "Terwujudnya generasi pionir yang religius, berbudaya, terampil dan kompetitif serta peduli lingkungan."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </Container>
        </section>
    );
}
