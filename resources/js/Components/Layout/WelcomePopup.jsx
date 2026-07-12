import { Link, usePage } from "@inertiajs/react";
import { AnimatePresence, motion } from "motion/react";
import { useEffect, useState } from "react";

import { cn, css, glass, motion as motionTheme, radius, shadow } from "@/Theme";

import Button from "../UI/Button";
import GlassCard from "../UI/GlassCard";

const STORAGE_KEY = "sigma3-welcome-popup";

export default function WelcomePopup() {
    const { settings } = usePage().props;
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
                    className="
                        fixed inset-0 z-100 flex items-start sm:items-center justify-center
                        overflow-y-auto overscroll-contain
                        bg-slate-900/40 backdrop-blur-md p-4 sm:p-6 py-8 sm:py-6
                    "
                >
                    <motion.div
                        initial={{
                            opacity: 0,
                            y: 40,
                            scale: 0.96,
                        }}
                        animate={{
                            opacity: 1,
                            y: 0,
                            scale: 1,
                        }}
                        exit={{
                            opacity: 0,
                            y: 30,
                            scale: 0.96,
                        }}
                        transition={motionTheme.normal}
                        className="w-full max-w-5xl my-auto"
                    >
                        <GlassCard
                            padding={false}
                            className="relative overflow-hidden group max-h-[85vh] sm:max-h-[90vh] flex flex-col"
                        >
                            {/* ==========================
                                EFEK GLOW LATAR BELAKANG
                            ========================== */}
                            <div className="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-emerald-500/20 blur-[100px] pointer-events-none" />
                            <div className="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-sky-500/10 blur-[100px] pointer-events-none" />

                            {/* Tombol Close Frosted Glass - sticky supaya selalu terlihat & bisa diklik */}
                            <button
                                type="button"
                                onClick={closePopup}
                                aria-label="Tutup sambutan"
                                className={cn(
                                    glass.default,
                                    radius.full,
                                    shadow.low,
                                    css.transition,
                                    "sticky float-right top-4 right-4 sm:top-6 sm:right-6 z-30 flex h-10 w-10 items-center justify-center text-sm font-bold text-slate-700 hover:scale-105 active:scale-95 mr-4 mt-4 sm:mr-6 sm:mt-6",
                                )}
                            >
                                ✕
                            </button>

                            <div className="flex flex-col lg:flex-row relative z-10 overflow-y-auto">
                                {/* ==========================
    LEFT SECTION (UPDATED)
========================== */}
                                <div
                                    className="
        relative flex w-full shrink-0 flex-col items-center justify-end
        bg-white/5 lg:w-100 pt-12 px-6 overflow-hidden
    "
                                >
                                    {/* Background Glow */}
                                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.1),transparent_70%)]" />

                                    {/* Header Section di sisi kiri */}
                                    <div className="absolute left-8 top-8 z-20 flex flex-col gap-1">
                                        {settings.logo && (
                                            <img
                                                src={settings.logo}
                                                alt="Logo"
                                                className="h-12 w-12 object-contain"
                                            />
                                        )}
                                        <div className="mt-2 text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700/80">
                                            SIGMA-3 Portal
                                        </div>
                                    </div>

                                    {/* Area Foto - Dibuat sedikit lebih dinamis */}
                                    <div className="relative z-10 mt-20 flex w-full flex-1 items-end justify-center">
                                        {settings.principal_photo ? (
                                            <img
                                                src={settings.principal_photo}
                                                alt={settings.principal}
                                                className="
                    relative z-10 max-h-64 lg:max-h-105 w-auto object-contain object-bottom
                    drop-shadow-[0_20px_30px_rgba(0,0,0,0.2)]
                    pointer-events-none select-none
                "
                                            />
                                        ) : (
                                            <div
                                                className={cn(
                                                    radius.xl,
                                                    "h-72 w-48 bg-slate-200/50",
                                                )}
                                            />
                                        )}
                                    </div>

                                    {/* Floating Badge yang lebih modern */}
                                    <div
                                        className="
            relative z-20 mb-8 w-full max-w-70 rounded-2xl
            bg-white/60 backdrop-blur-xl border border-white/50
            p-4 text-center shadow-lg
        "
                                    >
                                        <h3 className="text-lg font-black text-slate-900 tracking-tight">
                                            {settings.principal}
                                        </h3>
                                        <p className="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700 mt-0.5">
                                            Kepala MTsN 3 Tulungagung
                                        </p>
                                    </div>
                                </div>

                                {/* ==========================
                                    RIGHT SECTION (CONTENT)
                                ========================== */}
                                <div
                                    className="
                                        flex-1 border-t border-white/20 bg-white/40
                                        px-8 py-10 sm:p-12 lg:border-l lg:border-t-0
                                    "
                                >
                                    <div className="h-full flex flex-col justify-center">
                                        <p className="text-xs font-bold uppercase tracking-[0.25em] text-emerald-700">
                                            Sambutan Kepala Madrasah
                                        </p>

                                        {/* Gradient Text Futuristik */}
                                        <h2 className="mt-3 text-3xl font-black leading-tight tracking-tight text-slate-900 md:text-4xl lg:text-[2.75rem]">
                                            Selamat Datang di{" "}
                                            <span className="bg-linear-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent drop-shadow-sm">
                                                SIGMA-3
                                            </span>
                                        </h2>

                                        <div className="mt-8 space-y-5 text-[15px] leading-relaxed text-slate-600">
                                            <p className="font-bold text-slate-900">
                                                Assalamu'alaikum Warahmatullahi
                                                Wabarakatuh.
                                            </p>

                                            <p>
                                                Selamat datang di Sistem
                                                Informasi Global MTs Negeri 3
                                                Tulungagung (SIGMA-3), platform
                                                digital terpadu yang
                                                menghubungkan layanan akademik,
                                                kehadiran, ekstrakurikuler,
                                                publikasi prestasi, serta
                                                berbagai layanan madrasah dalam
                                                satu antarmuka modern.
                                            </p>

                                            <p>
                                                Melalui SIGMA-3, kami
                                                berkomitmen menghadirkan layanan
                                                yang transparan, cepat, dan
                                                mudah diakses oleh seluruh
                                                ekosistem madrasah: peserta
                                                didik, orang tua, pendidik,
                                                maupun masyarakat.
                                            </p>

                                            <p>
                                                Semoga inovasi ini menjadi
                                                langkah konkret dalam mewujudkan
                                                madrasah yang unggul,
                                                berkarakter, dan berdaya saing
                                                global di era digital.
                                            </p>

                                            <p className="font-bold text-slate-900">
                                                Wassalamu'alaikum Warahmatullahi
                                                Wabarakatuh.
                                            </p>
                                        </div>

                                        <div className="mt-10 flex items-center gap-4">
                                            <Button
                                                onClick={closePopup}
                                                variant="primary"
                                                className="w-full sm:w-auto h-12 px-8 text-base shadow-emerald-900/20 shadow-xl"
                                            >
                                                Masuk ke Portal
                                            </Button>
                                        </div>
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
