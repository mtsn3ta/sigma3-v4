import { usePage } from "@inertiajs/react";
import { AnimatePresence, motion } from "motion/react";
import { useEffect, useState } from "react";
import {
    ArrowRight,
    BookOpen,
    GraduationCap,
    Quote,
    Sparkles,
    X,
} from "lucide-react";

import { cn, css, glass, motion as motionTheme, radius, shadow } from "@/Theme";

import Button from "../UI/Button";
import GlassCard from "../UI/GlassCard";

const STORAGE_KEY = "sigma3-welcome-popup";

export default function WelcomePopup() {
    const { settings = {}, auth = {} } = usePage().props;
    const [open, setOpen] = useState(false);

    useEffect(() => {
        const hidden = sessionStorage.getItem(STORAGE_KEY);

        if (!hidden) {
            const timer = setTimeout(() => {
                setOpen(true);
            }, 700);

            return () => clearTimeout(timer);
        }
    }, []);

    useEffect(() => {
        if (open) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "unset";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [open]);

    const closePopup = () => {
        sessionStorage.setItem(STORAGE_KEY, "closed");
        setOpen(false);
    };

    return (
        <AnimatePresence>
            {open && (
                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    transition={motionTheme.fast}
                    className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 backdrop-blur-md p-4 sm:p-6"
                    onClick={(e) => {
                        if (e.target === e.currentTarget) closePopup();
                    }}
                >
                    <motion.div
                        initial={{ opacity: 0, y: 36, scale: 0.94 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 24, scale: 0.94 }}
                        transition={motionTheme.normal}
                        className="w-full max-w-5xl"
                    >
                        <GlassCard
                            padding={false}
                            className="relative flex flex-col md:flex-row w-full max-h-[92vh] sm:max-h-[88vh] overflow-hidden border border-white/20 shadow-2xl shadow-emerald-950/30"
                        >
                            {/* =========================================
                                TOMBOL CLOSE
                            ========================================= */}
                            <motion.button
                                type="button"
                                onClick={closePopup}
                                aria-label="Tutup sambutan"
                                whileHover={{ scale: 1.12 }}
                                whileTap={{ scale: 0.92 }}
                                className={cn(
                                    glass.default,
                                    radius.full,
                                    shadow.low,
                                    css.transition,
                                    "absolute top-4 right-4 sm:top-5 sm:right-5 z-50",
                                    "flex h-9 w-9 items-center justify-center",
                                    "bg-white/60 backdrop-blur-xl border border-white/40",
                                    "text-slate-500 hover:text-slate-900",
                                )}
                            >
                                <X className="w-4 h-4" />
                            </motion.button>

                            {/* =========================================
                                KIRI — VISUAL & FOTO KEPALA MADRASAH
                            ========================================= */}
                            <div className="relative flex w-full md:w-5/12 shrink-0 flex-col items-center justify-end bg-gradient-to-br from-emerald-50 via-emerald-100/60 to-teal-100/40 pt-8 sm:pt-12 px-6 overflow-hidden">

                                {/* Animated orbs background */}
                                <div className="pointer-events-none absolute inset-0 overflow-hidden">
                                    <motion.div
                                        animate={{ scale: [1, 1.15, 1], opacity: [0.15, 0.25, 0.15] }}
                                        transition={{ duration: 6, repeat: Infinity, ease: "easeInOut" }}
                                        className="absolute -top-16 -left-16 h-64 w-64 rounded-full bg-emerald-400/30 blur-3xl"
                                    />
                                    <motion.div
                                        animate={{ scale: [1, 1.2, 1], opacity: [0.1, 0.2, 0.1] }}
                                        transition={{ duration: 8, repeat: Infinity, ease: "easeInOut", delay: 1 }}
                                        className="absolute bottom-0 right-0 h-48 w-48 rounded-full bg-teal-400/25 blur-2xl"
                                    />
                                </div>

                                {/* Logo & label SIGMA-3 */}
                                <div className="absolute top-5 left-5 z-20 flex items-center gap-2.5">
                                    {settings.logo && (
                                        <img
                                            src={settings.logo}
                                            alt="Logo"
                                            className="h-10 w-10 sm:h-12 sm:w-12 object-contain drop-shadow-sm"
                                        />
                                    )}
                                    <div className="flex flex-col leading-none">
                                        <span className="text-[11px] font-black uppercase tracking-widest text-emerald-800">
                                            SIGMA-3
                                        </span>
                                        <span className="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-500">
                                            Portal
                                        </span>
                                    </div>
                                </div>

                                {/* Foto kepala madrasah */}
                                <div className="relative z-10 mt-14 flex w-full flex-1 items-end justify-center">
                                    {settings.principal_photo ? (
                                        <img
                                            src={settings.principal_photo}
                                            alt={settings.principal}
                                            className="relative z-10 max-h-52 sm:max-h-64 md:max-h-[28rem] w-auto object-contain object-bottom drop-shadow-[0_20px_30px_rgba(0,0,0,0.18)] pointer-events-none select-none"
                                        />
                                    ) : (
                                        <div className="h-48 w-40 sm:h-64 sm:w-48 rounded-t-3xl bg-slate-200/50 flex items-end justify-center pb-6">
                                            <GraduationCap className="w-20 h-20 text-emerald-300" />
                                        </div>
                                    )}
                                </div>

                                {/* Badge nama kepala madrasah */}
                                <div className="relative z-20 mb-5 md:mb-8 w-full max-w-[17rem] rounded-2xl bg-white/80 backdrop-blur-xl border border-white/60 p-3.5 sm:p-4 text-center shadow-lg shadow-emerald-900/10">
                                    <div className="flex items-center justify-center gap-1.5 mb-1">
                                        <Sparkles className="w-3 h-3 text-emerald-500" />
                                        <span className="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">
                                            Kepala Madrasah
                                        </span>
                                        <Sparkles className="w-3 h-3 text-emerald-500" />
                                    </div>
                                    <h3 className="text-sm sm:text-base font-black text-slate-900 tracking-tight leading-tight">
                                        {settings.principal || "—"}
                                    </h3>
                                </div>
                            </div>

                            {/* =========================================
                                KANAN — KONTEN SAMBUTAN
                            ========================================= */}
                            <div className="flex-1 flex flex-col bg-white/65 backdrop-blur-xl p-6 sm:p-10 md:p-12 overflow-y-auto overscroll-contain">
                                <div className="max-w-2xl my-auto">

                                    {/* Label atas */}
                                    <div className="flex items-center gap-2 mb-3">
                                        <div className="h-px flex-1 bg-emerald-200" />
                                        <span className="text-[11px] sm:text-xs font-bold uppercase tracking-[0.25em] text-emerald-600">
                                            Sambutan Kepala Madrasah
                                        </span>
                                        <div className="h-px flex-1 bg-emerald-200" />
                                    </div>

                                    {/* Judul */}
                                    <h2 className="text-2xl sm:text-3xl md:text-4xl font-black leading-tight tracking-tight text-slate-900">
                                        Selamat Datang di{" "}
                                        <span className="bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">
                                            SIGMA-3
                                        </span>
                                    </h2>

                                    {/* Quote dekoratif */}
                                    <div className="relative mt-6 sm:mt-8 pl-5 border-l-4 border-emerald-400">
                                        <Quote className="absolute -top-1 -left-3 w-5 h-5 text-emerald-400 rotate-180" />
                                        <p className="text-[13px] sm:text-sm font-semibold italic text-emerald-700 leading-relaxed">
                                            Assalamu'alaikum Warahmatullahi Wabarakatuh.
                                        </p>
                                    </div>

                                    {/* Isi sambutan */}
                                    <div className="mt-5 sm:mt-6 space-y-3.5 sm:space-y-4 text-sm sm:text-[15px] leading-relaxed text-slate-600">
                                        <p>
                                            Selamat datang di <span className="font-semibold text-slate-800">Sistem Informasi Global MTs Negeri 3 Tulungagung (SIGMA-3)</span>, platform digital terpadu yang menghubungkan layanan akademik, kehadiran, ekstrakurikuler, publikasi prestasi, serta berbagai layanan madrasah dalam satu antarmuka modern.
                                        </p>

                                        <p>
                                            Melalui SIGMA-3, kami berkomitmen menghadirkan layanan yang transparan, cepat, dan mudah diakses oleh seluruh ekosistem madrasah: peserta didik, orang tua, pendidik, maupun masyarakat.
                                        </p>

                                        <p>
                                            Semoga inovasi ini menjadi langkah konkret dalam mewujudkan madrasah yang unggul, berkarakter, dan berdaya saing global di era digital.
                                        </p>

                                        <p className="font-semibold italic text-emerald-700 text-[13px] sm:text-sm">
                                            Wassalamu'alaikum Warahmatullahi Wabarakatuh.
                                        </p>
                                    </div>

                                    {/* Signature strip */}
                                    <div className="mt-6 sm:mt-7 flex items-center gap-3 rounded-xl bg-emerald-50/80 border border-emerald-100 px-4 py-3">
                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-500/10">
                                            <BookOpen className="w-4 h-4 text-emerald-600" />
                                        </div>
                                        <div className="leading-tight">
                                            <p className="text-xs font-black text-slate-800">
                                                {settings.principal || "Kepala Madrasah"}
                                            </p>
                                            <p className="text-[10px] font-medium text-slate-500 mt-0.5">
                                                {settings.school_name || "MTs Negeri 3 Tulungagung"}
                                            </p>
                                        </div>
                                    </div>

                                    {/* CTA button */}
                                    <div className="mt-7 sm:mt-8 flex flex-col sm:flex-row gap-3">
                                        <Button
                                            href={auth?.user ? "/admin" : "/login"}
                                            external={true}
                                            onClick={closePopup}
                                            variant="primary"
                                            className="flex-1 sm:flex-none h-12 sm:h-14 px-8 sm:px-10 text-sm sm:text-base font-bold shadow-xl shadow-emerald-900/20 flex items-center justify-center gap-2"
                                        >
                                            {auth?.user ? "Ke Dashboard" : "Masuk ke Portal"}
                                            <ArrowRight className="w-4 h-4" />
                                        </Button>

                                        <button
                                            type="button"
                                            onClick={closePopup}
                                            className={cn(
                                                css.transition,
                                                "h-12 sm:h-14 px-6 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100/80 border border-slate-200 flex items-center justify-center gap-1.5",
                                            )}
                                        >
                                            Lihat Portal Dulu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </GlassCard>
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
