import React, { useState } from "react";
import { router, Link } from "@inertiajs/react";
import { motion } from "motion/react";
import {
    Search,
    ShieldCheck,
    Sparkles,
    CreditCard,
    Radio,
    Wifi,
    CheckCircle2,
    ArrowRight,
    QrCode,
    Activity,
    Compass,
    Building2,
} from "lucide-react";
import Container from "@/Components/UI/Container";
import Button from "@/Components/UI/Button";
import { cn } from "@/Theme";

export default function SmartHero({ settings = {} }) {
    const [nisnQuery, setNisnQuery] = useState("");
    const [nisnError, setNisnError] = useState("");
    const [activeHotspot, setActiveHotspot] = useState(null);

    const handleNisnSearch = (e) => {
        e.preventDefault();
        const clean = nisnQuery.trim();
        if (!clean) {
            setNisnError("Masukkan NISN putra/putri Anda");
            return;
        }
        if (!/^\d+$/.test(clean)) {
            setNisnError("NISN harus berupa kombinasi angka");
            return;
        }
        setNisnError("");
        router.visit(`/wali_murid/${clean}`);
    };

    const schoolName = settings.school_name || "MTsN 3 Tulungagung";

    const hotspots = [
        {
            id: "gerbang",
            label: "Presensi Gerbang",
            desc: "Tap in/out otomatis saat tiba & pulang",
            badgeColor: "bg-emerald-500",
            top: "22%",
            right: "-12px",
        },
        {
            id: "masjid",
            label: "Presensi Sholat",
            desc: "Pantau Sholat Dhuha & Dhuhur berjamaah",
            badgeColor: "bg-teal-500",
            top: "54%",
            right: "-16px",
        },
        {
            id: "layanan",
            label: "Perpustakaan & Konseling",
            desc: "Akses digital KBM, buku, dan BK",
            badgeColor: "bg-emerald-600",
            top: "84%",
            right: "-10px",
        },
    ];

    return (
        <section className="relative overflow-hidden pt-28 pb-16 md:pt-36 md:pb-24 bg-gradient-to-b from-white via-slate-50/70 to-emerald-50/20 border-b border-slate-100">
            {/* Subtle Engineering Grid & Soft Radial Spotlight */}
            <div className="absolute inset-0 bg-[linear-gradient(to_right,#0596690a_1px,transparent_1px),linear-gradient(to_bottom,#0596690a_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] pointer-events-none" />
            <div className="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-b from-emerald-100/60 to-transparent blur-3xl pointer-events-none" />

            <Container className="relative z-10">
                <div className="grid lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                    {/* ==================================================
                        LEFT COLUMN: Institutional Value & Quick Portal
                    ================================================== */}
                    <div className="lg:col-span-7 flex flex-col items-start">
                        {/* Innovation Pill */}
                        <motion.div
                            initial={{ opacity: 0, y: -10 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.4 }}
                            className="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-emerald-200/90 bg-emerald-50/90 text-emerald-800 text-xs font-semibold shadow-xs mb-6 backdrop-blur-md"
                        >
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span className="font-bold tracking-wide uppercase text-[11px] text-emerald-900">
                                Smart Madrasah Ecosystem
                            </span>
                            <span className="text-emerald-300">•</span>
                            <span className="text-emerald-700 font-medium">IoT & AI Ready</span>
                        </motion.div>

                        {/* Main Title */}
                        <motion.h1
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.5, delay: 0.1 }}
                            className="text-4xl sm:text-5xl lg:text-[3.4rem] font-black leading-[1.12] tracking-tight text-slate-900"
                        >
                            Digitalisasi Terpadu <br />
                            <span className="text-slate-900">{schoolName}:</span> <br />
                            <span className="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 bg-clip-text text-transparent">
                                Satu Kartu, Segudang Kemudahan.
                            </span>
                        </motion.h1>

                        {/* Subtitle */}
                        <motion.p
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.5, delay: 0.2 }}
                            className="mt-5 text-base sm:text-lg leading-relaxed text-slate-600 max-w-xl"
                        >
                            Ekosistem teknologi cerdas yang mengintegrasikan gerbang RFID nirsentuh,
                            pemantauan ibadah digital, keterbukaan informasi wali murid, dan layanan asisten AI resmi madrasah.
                        </motion.p>

                        {/* Fast NISN Search Console (Modern Utility Box) */}
                        <motion.div
                            initial={{ opacity: 0, y: 16 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.5, delay: 0.3 }}
                            className="w-full mt-7 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 shadow-[0_12px_36px_rgb(0,0,0,0.06)] relative"
                        >
                            <div className="flex items-center justify-between mb-3">
                                <div className="flex items-center gap-2">
                                    <ShieldCheck className="w-4 h-4 text-emerald-600" />
                                    <span className="text-xs font-bold uppercase tracking-wider text-slate-700">
                                        Fast NISN Search Console
                                    </span>
                                </div>
                                <span className="text-[11px] text-slate-500 font-medium hidden sm:inline">
                                    Akses Langsung Wali Murid
                                </span>
                            </div>

                            <form onSubmit={handleNisnSearch} className="flex flex-col sm:flex-row gap-2.5">
                                <div className="relative flex-1">
                                    <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input
                                        type="text"
                                        placeholder="Cari NISN Siswa (contoh: 0081234567)..."
                                        value={nisnQuery}
                                        onChange={(e) => {
                                            setNisnQuery(e.target.value);
                                            if (nisnError) setNisnError("");
                                        }}
                                        className={cn(
                                            "w-full rounded-xl border bg-slate-50/80 pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all",
                                            nisnError ? "border-rose-300 bg-rose-50/50" : "border-slate-200",
                                        )}
                                    />
                                    {nisnError && (
                                        <p className="absolute -bottom-5 left-1 text-[11px] font-medium text-rose-600">
                                            {nisnError}
                                        </p>
                                    )}
                                </div>
                                <Button
                                    type="submit"
                                    variant="primary"
                                    className="px-6 py-2.5 text-sm font-bold shadow-md shadow-emerald-600/20 flex items-center justify-center gap-1.5 shrink-0"
                                >
                                    <span>Cek Data</span>
                                    <ArrowRight className="w-4 h-4" />
                                </Button>
                            </form>
                        </motion.div>

                        {/* Quick Trust Chips */}
                        <div className="mt-8 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-600">
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>RFID 13.56 MHz Nirsentuh</span>
                            </div>
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>Portal Wali Realtime</span>
                            </div>
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>Asisten WhatsApp AI 24 Jam</span>
                            </div>
                        </div>
                    </div>

                    {/* ==================================================
                        RIGHT COLUMN: Interactive RFID Smart Card Showcase
                    ================================================== */}
                    <div className="lg:col-span-5 flex justify-center">
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95 }}
                            animate={{ opacity: 1, scale: 1 }}
                            transition={{ duration: 0.6, delay: 0.2 }}
                            className="relative w-full max-w-[430px]"
                        >
                            {/* Decorative Background Glows */}
                            <div className="absolute -inset-4 bg-gradient-to-r from-emerald-500/15 via-teal-500/10 to-emerald-600/15 rounded-3xl blur-2xl" />

                            {/* Outer Card Container / High-Tech Frame */}
                            <div className="relative rounded-3xl border border-slate-200/90 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 p-6 sm:p-7 shadow-2xl text-white overflow-hidden group">
                                {/* Subtle Hologram & Radial Highlights */}
                                <div className="absolute -top-24 -right-24 w-52 h-52 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none" />
                                <div className="absolute bottom-0 left-0 w-44 h-44 bg-teal-500/15 rounded-full blur-2xl pointer-events-none" />
                                <div className="absolute inset-0 bg-[radial-gradient(#ffffff08_1px,transparent_1px)] bg-[size:16px_16px] pointer-events-none opacity-60" />

                                {/* Card Header */}
                                <div className="flex items-center justify-between pb-5 border-b border-white/10 relative z-10">
                                    <div className="flex items-center gap-3">
                                        <div className="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center p-1.5 backdrop-blur-md">
                                            {settings.logo ? (
                                                <img
                                                    src={settings.logo}
                                                    alt="Logo Madrasah"
                                                    className="w-full h-full object-contain"
                                                />
                                            ) : (
                                                <Building2 className="w-5 h-5 text-emerald-400" />
                                            )}
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-bold tracking-widest uppercase text-emerald-400">
                                                e-Kartu Pelajar Digital
                                            </p>
                                            <h2 className="text-sm font-bold text-white tracking-wide">
                                                {schoolName}
                                            </h2>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-[10px] font-bold text-emerald-300">
                                        <Wifi className="w-3 h-3 animate-pulse" />
                                        <span>RFID / NFC</span>
                                    </div>
                                </div>

                                {/* Card Body (Student Persona & RFID Chip) */}
                                <div className="my-6 relative z-10 flex items-center gap-4">
                                    <div className="w-16 h-20 rounded-xl bg-gradient-to-b from-white/20 to-white/5 border border-white/20 flex flex-col items-center justify-center relative overflow-hidden shadow-inner">
                                        <div className="w-7 h-7 rounded-full bg-emerald-400/20 border border-emerald-300/40 flex items-center justify-center mb-1">
                                            <span className="text-xs font-black text-emerald-300">M</span>
                                        </div>
                                        <span className="text-[8px] font-semibold text-slate-300">MATSANEGA</span>
                                    </div>

                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-1.5">
                                            <div className="w-8 h-6 rounded-md bg-amber-400/20 border border-amber-300/50 flex items-center justify-center">
                                                <CreditCard className="w-4 h-4 text-amber-300" />
                                            </div>
                                            <span className="text-[10px] font-mono tracking-wider text-slate-400">
                                                ID: 88.04.19.A2
                                            </span>
                                        </div>
                                        <h3 className="text-base font-bold text-white tracking-tight">
                                            Siswa Teladan MTsN 3
                                        </h3>
                                        <p className="text-xs text-slate-300 font-mono">
                                            NISN: 0084920193
                                        </p>
                                    </div>
                                </div>

                                {/* Barcode & Brand Footer */}
                                <div className="pt-4 border-t border-white/10 flex items-end justify-between relative z-10">
                                    <div>
                                        {/* Barcode Mockup */}
                                        <div className="h-6 flex items-center gap-[2.5px] opacity-75">
                                            {[3, 1, 4, 1, 2, 4, 1, 3, 2, 1, 4, 2, 1, 3, 1, 2, 4, 1, 2, 3].map(
                                                (h, idx) => (
                                                    <span
                                                        key={idx}
                                                        className="w-[2px] bg-white rounded-xs"
                                                        style={{ height: `${h * 5}px` }}
                                                    />
                                                ),
                                            )}
                                        </div>
                                        <span className="text-[9px] font-mono tracking-widest text-slate-400 mt-1 block">
                                            SIGMA-3 SMART CAMPUS
                                        </span>
                                    </div>

                                    <span className="text-xs font-black tracking-wider text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2.5 py-1 rounded-md">
                                        MATSANEGA
                                    </span>
                                </div>

                                {/* Hotspot Badges Branching Off the Card */}
                                <div className="mt-5 pt-4 border-t border-white/5 space-y-2">
                                    {hotspots.map((hs) => (
                                        <div
                                            key={hs.id}
                                            className="flex items-center justify-between p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors"
                                        >
                                            <div className="flex items-center gap-2.5">
                                                <span className={cn("w-2 h-2 rounded-full", hs.badgeColor)} />
                                                <span className="text-xs font-bold text-white">{hs.label}</span>
                                            </div>
                                            <span className="text-[11px] text-emerald-300/80 font-medium">
                                                {hs.desc}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </motion.div>
                    </div>
                </div>
            </Container>
        </section>
    );
}
