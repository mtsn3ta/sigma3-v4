import { Link, usePage } from "@inertiajs/react";
import { AnimatePresence, motion } from "motion/react";
import { useEffect, useState } from "react";

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
                    className="
                        fixed inset-0 z-100 flex items-center justify-center
                        bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6
                    "
                >
                    <motion.div
                        initial={{ opacity: 0, y: 30, scale: 0.95 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 20, scale: 0.95 }}
                        transition={motionTheme.normal}
                        className="w-full max-w-5xl"
                    >
                        <GlassCard
                            padding={false}
                            className="relative flex flex-col md:flex-row w-full max-h-[90vh] sm:max-h-[85vh] overflow-hidden"
                        >
                            {/* ==========================
                                TOMBOL CLOSE ABSOLUTE
                            ========================== */}
                            <button
                                type="button"
                                onClick={closePopup}
                                aria-label="Tutup sambutan"
                                className={cn(
                                    glass.default,
                                    radius.full,
                                    shadow.low,
                                    css.transition,
                                    "absolute top-4 right-4 sm:top-5 sm:right-5 z-50 flex h-9 w-9 items-center justify-center text-sm font-bold text-slate-600 hover:text-slate-900 hover:scale-110 active:scale-95 bg-white/50 backdrop-blur-md",
                                )}
                            >
                                ✕
                            </button>

                            {/* ==========================
                                LEFT SECTION (VISUAL & PHOTO)
                            ========================== */}
                            <div className="relative flex w-full md:w-5/12 shrink-0 flex-col items-center justify-end bg-emerald-50/50 pt-8 sm:pt-12 px-6 overflow-hidden">
                                {/* Background Effects */}
                                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.15),transparent_70%)]" />

                                {/* Logo & Portal Name */}
                                <div className="absolute top-6 left-6 z-20 flex items-center gap-3">
                                    {settings.logo && (
                                        <img
                                            src={settings.logo}
                                            alt="Logo"
                                            className="h-10 w-10 sm:h-12 sm:w-12 object-contain drop-shadow-sm"
                                        />
                                    )}
                                    <div className="flex flex-col">
                                        <span className="text-[10px] font-black uppercase tracking-widest text-emerald-800">
                                            SIGMA-3
                                        </span>
                                        <span className="text-[9px] font-bold tracking-wider text-slate-500">
                                            PORTAL
                                        </span>
                                    </div>
                                </div>

                                {/* Photo */}
                                <div className="relative z-10 mt-14 flex w-full flex-1 items-end justify-center">
                                    {settings.principal_photo ? (
                                        <img
                                            src={settings.principal_photo}
                                            alt={settings.principal}
                                            className="
                                                relative z-10 max-h-48 sm:max-h-64 md:max-h-104 w-auto
                                                object-contain object-bottom drop-shadow-[0_15px_25px_rgba(0,0,0,0.15)]
                                                pointer-events-none select-none
                                            "
                                        />
                                    ) : (
                                        <div className="h-48 w-40 sm:h-64 sm:w-48 rounded-t-3xl bg-slate-200/50" />
                                    )}
                                </div>

                                {/* Floating Badge */}
                                <div className="relative z-20 mb-4 md:mb-8 w-full max-w-[16rem] rounded-2xl bg-white/80 backdrop-blur-md border border-white/60 p-3 sm:p-4 text-center shadow-lg shadow-emerald-900/5">
                                    <h3 className="text-sm sm:text-base font-black text-slate-900 tracking-tight leading-none">
                                        {settings.principal}
                                    </h3>
                                    <p className="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700 mt-1">
                                        Kepala Madrasah
                                    </p>
                                </div>
                            </div>

                            {/* ==========================
                                RIGHT SECTION (CONTENT)
                            ========================== */}
                            <div className="flex-1 flex flex-col bg-white/60 p-6 sm:p-10 md:p-12 overflow-y-auto overscroll-contain">
                                <div className="max-w-2xl my-auto">
                                    <p className="text-[11px] sm:text-xs font-bold uppercase tracking-[0.25em] text-emerald-600">
                                        Sambutan Kepala Madrasah
                                    </p>

                                    <h2 className="mt-2 sm:mt-3 text-2xl sm:text-3xl md:text-4xl font-black leading-tight tracking-tight text-slate-900">
                                        Selamat Datang di{" "}
                                        <span className="bg-linear-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent drop-shadow-sm">
                                            SIGMA-3
                                        </span>
                                    </h2>

                                    <div className="mt-6 sm:mt-8 space-y-4 sm:space-y-5 text-sm sm:text-[15px] leading-relaxed text-slate-600">
                                        <p className="font-bold text-slate-900">
                                            Assalamu'alaikum Warahmatullahi
                                            Wabarakatuh.
                                        </p>

                                        <p>
                                            Selamat datang di Sistem Informasi
                                            Global MTs Negeri 3 Tulungagung
                                            (SIGMA-3), platform digital terpadu
                                            yang menghubungkan layanan akademik,
                                            kehadiran, ekstrakurikuler,
                                            publikasi prestasi, serta berbagai
                                            layanan madrasah dalam satu
                                            antarmuka modern.
                                        </p>

                                        <p>
                                            Melalui SIGMA-3, kami berkomitmen
                                            menghadirkan layanan yang
                                            transparan, cepat, dan mudah diakses
                                            oleh seluruh ekosistem madrasah:
                                            peserta didik, orang tua, pendidik,
                                            maupun masyarakat.
                                        </p>

                                        <p>
                                            Semoga inovasi ini menjadi langkah
                                            konkret dalam mewujudkan madrasah
                                            yang unggul, berkarakter, dan
                                            berdaya saing global di era digital.
                                        </p>

                                        <p className="font-bold text-slate-900">
                                            Wassalamu'alaikum Warahmatullahi
                                            Wabarakatuh.
                                        </p>
                                    </div>

                                    <div className="mt-8 sm:mt-10">
                                        <Button
                                            href={
                                                auth?.user ? "/admin" : "/login"
                                            }
                                            external={true}
                                            onClick={closePopup}
                                            variant="primary"
                                            className="w-full md:w-auto h-12 sm:h-14 px-8 sm:px-10 text-sm sm:text-base font-bold shadow-emerald-900/20 shadow-xl"
                                        >
                                            {auth?.user
                                                ? "Ke Dashboard"
                                                : "Masuk ke Portal"}
                                        </Button>
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
