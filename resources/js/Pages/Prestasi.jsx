import { motion, AnimatePresence } from "motion/react";
import { Head, Link, usePage } from "@inertiajs/react";
import { router } from "@inertiajs/react";
import { useEffect, useState } from "react";
import FrontendLayout from "@/Layouts/FrontendLayout";

import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import Button from "@/Components/UI/Button";
import GlassCard from "@/Components/UI/GlassCard";

import {
    cn,
    background,
    motion as motionTheme,
    spacing,
    glass,
    radius,
} from "@/Theme";

export default function Prestasi() {
    const {
        settings,
        statistics = {},

        achievements = { data: [] },

        filters = {},

        levels = {},

        years = [],

        extracurriculars = [],
    } = usePage().props;
    const [search, setSearch] = useState(filters.search ?? "");
    const [level, setLevel] = useState(filters.tingkat ?? "");
    const [year, setYear] = useState(filters.tahun ?? "");
    const [extracurricular, setExtracurricular] = useState(
        filters.ekstrakurikuler ?? "",
    );
    const applyFilter = () => {
        router.get(
            "/prestasi",
            {
                search,
                tingkat: level,
                tahun: year,
                ekstrakurikuler: extracurricular,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };
    const [selectedAchievement, setSelectedAchievement] = useState(null);
    useEffect(() => {
        if (!selectedAchievement) return;

        const handleKeyDown = (e) => {
            if (e.key === "Escape") {
                setSelectedAchievement(null);
            }
        };

        window.addEventListener("keydown", handleKeyDown);

        return () => {
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, [selectedAchievement]);

    useEffect(() => {
        document.body.style.overflow = selectedAchievement ? "hidden" : "";

        return () => {
            document.body.style.overflow = "";
        };
    }, [selectedAchievement]);
    // Ikon kecil dipakai berulang di select filter — murni presentasi
    const SelectChevron = () => (
        <svg
            className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            strokeWidth={2}
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="m19 9-7 7-7-7"
            />
        </svg>
    );

    return (
        <FrontendLayout>
            <Head title={`Prestasi - ${settings?.school_name ?? "SIGMA-3"}`} />

            <main className={cn(background.gradient, "relative bg-[#f7faf9]")}>
                {/* =======================================================
                    HERO
                ======================================================= */}

                <section
                    className={cn(
                        background.hero,
                        spacing.pageHeroTop,
                        spacing.pageHeroBottom,
                        "relative overflow-hidden",
                    )}
                >
                    {/* Ambient futuristic backdrop */}
                    <div className="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
                        <svg
                            className="absolute inset-0 h-full w-full opacity-[0.06]"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <defs>
                                <pattern
                                    id="grid-prestasi"
                                    width="42"
                                    height="42"
                                    patternUnits="userSpaceOnUse"
                                >
                                    <path
                                        d="M 42 0 L 0 0 0 42"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="1"
                                        className="text-slate-900"
                                    />
                                </pattern>
                            </defs>
                            <rect
                                width="100%"
                                height="100%"
                                fill="url(#grid-prestasi)"
                            />
                        </svg>

                        <motion.div
                            className="absolute -left-32 -top-24 h-104 w-104 rounded-full bg-emerald-300/30 blur-[110px]"
                            animate={{
                                x: [0, 40, 0],
                                y: [0, 30, 0],
                            }}
                            transition={{
                                duration: 14,
                                repeat: Infinity,
                                ease: "easeInOut",
                            }}
                        />

                        <motion.div
                            className="absolute -right-24 top-10 h-88 w-88 rounded-full bg-cyan-300/25 blur-[100px]"
                            animate={{
                                x: [0, -30, 0],
                                y: [0, 40, 0],
                            }}
                            transition={{
                                duration: 16,
                                repeat: Infinity,
                                ease: "easeInOut",
                            }}
                        />

                        <motion.div
                            className="absolute bottom-24 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-teal-200/30 blur-[100px]"
                            animate={{ opacity: [0.4, 0.7, 0.4] }}
                            transition={{
                                duration: 8,
                                repeat: Infinity,
                                ease: "easeInOut",
                            }}
                        />
                    </div>

                    <Container>
                        <div className="mx-auto max-w-4xl text-center">
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="mb-5 inline-flex"
                            >
                                <Badge
                                    variant="primary"
                                    className="gap-2 border border-emerald-200/70 bg-white/60 shadow-[0_0_0_1px_rgba(16,185,129,0.15)] backdrop-blur-md"
                                >
                                    <span className="relative flex h-2 w-2">
                                        <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                                        <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-500" />
                                    </span>
                                    Hall of Fame
                                </Badge>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-5xl font-black tracking-tight text-slate-900 md:text-7xl"
                            >
                                Portal{" "}
                                <span className="bg-linear-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent">
                                    Prestasi
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{
                                    ...motionTheme.normal,
                                    delay: 0.1,
                                }}
                                className="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-600"
                            >
                                Seluruh prestasi siswa dan ekstrakurikuler MTs
                                Negeri 3 Tulungagung terdokumentasi dalam satu
                                portal.
                            </motion.p>
                        </div>

                        <motion.div
                            initial={{ opacity: 0, y: 24 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ ...motionTheme.normal, delay: 0.2 }}
                            className="mt-14 grid grid-cols-2 gap-4 md:grid-cols-4"
                        >
                            {[
                                {
                                    label: "Prestasi",
                                    value: statistics.achievements ?? 0,
                                    icon: "🏆",
                                },
                                {
                                    label: "Siswa",
                                    value: statistics.students ?? 0,
                                    icon: "🎓",
                                },
                                {
                                    label: "Ekstrakurikuler",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: "⚡",
                                },
                                {
                                    label: "Tahun Ini",
                                    value: statistics.this_year ?? 0,
                                    icon: "✦",
                                },
                            ].map((stat) => (
                                <GlassCard
                                    key={stat.label}
                                    className="group relative overflow-hidden border border-white/70 bg-white/50 p-6 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(16,185,129,0.15)]"
                                >
                                    <div className="pointer-events-none absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-emerald-400/60 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100" />

                                    <div className="mb-1 text-lg opacity-70">
                                        {stat.icon}
                                    </div>

                                    <p className="text-3xl font-black tracking-tight text-slate-900">
                                        {stat.value}
                                    </p>

                                    <p className="mt-2 text-xs font-bold uppercase tracking-widest text-slate-500">
                                        {stat.label}
                                    </p>
                                </GlassCard>
                            ))}
                        </motion.div>
                    </Container>
                </section>

                {/* =======================================================
    FILTER
======================================================= */}

                <section className={cn("relative z-10", spacing.sectionY)}>
                    <Container>
                        <GlassCard className="relative overflow-hidden border border-white/70 bg-white/55 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.06)] backdrop-blur-xl">
                            <div className="pointer-events-none absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-emerald-400/50 to-transparent" />

                            <div className="grid gap-4 lg:grid-cols-[1fr_220px_220px_180px]">
                                <div className="relative">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth={2}
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"
                                        />
                                    </svg>

                                    <input
                                        type="text"
                                        value={search}
                                        onChange={(e) =>
                                            setSearch(e.target.value)
                                        }
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") {
                                                applyFilter();
                                            }
                                        }}
                                        placeholder="Cari prestasi, lomba, atau siswa..."
                                        className="h-12 w-full rounded-2xl border border-white bg-white/70 pl-12 pr-4 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    />
                                </div>

                                <div className="relative">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth={2}
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M9 6L3 12l6 6M15 6l6 6-6 6"
                                        />
                                    </svg>

                                    <select
                                        value={level}
                                        onChange={(e) => {
                                            setLevel(e.target.value);
                                            applyFilter();
                                        }}
                                        className="h-12 w-full appearance-none rounded-2xl border border-white bg-white/70 pl-11 pr-9 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    >
                                        <option value="">Semua Tingkat</option>

                                        {Object.entries(levels).map(
                                            ([value, label]) => (
                                                <option
                                                    key={value}
                                                    value={value}
                                                >
                                                    {label}
                                                </option>
                                            ),
                                        )}
                                    </select>

                                    <SelectChevron />
                                </div>

                                <div className="relative">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth={2}
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>

                                    <select
                                        value={extracurricular}
                                        onChange={(e) => {
                                            setExtracurricular(e.target.value);
                                            applyFilter();
                                        }}
                                        className="h-12 w-full appearance-none rounded-2xl border border-white bg-white/70 pl-11 pr-9 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    >
                                        <option value="">
                                            Semua Ekstrakurikuler
                                        </option>

                                        {Object.entries(extracurriculars).map(
                                            ([id, nama]) => (
                                                <option key={id} value={id}>
                                                    {nama}
                                                </option>
                                            ),
                                        )}
                                    </select>

                                    <SelectChevron />
                                </div>

                                <div className="relative">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth={2}
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>

                                    <select
                                        value={year}
                                        onChange={(e) => {
                                            setYear(e.target.value);
                                            applyFilter();
                                        }}
                                        className="h-12 w-full appearance-none rounded-2xl border border-white bg-white/70 pl-11 pr-9 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    >
                                        <option value="">Semua Tahun</option>

                                        {years.map((tahun) => (
                                            <option key={tahun} value={tahun}>
                                                {tahun}
                                            </option>
                                        ))}
                                    </select>

                                    <SelectChevron />
                                </div>
                            </div>
                        </GlassCard>
                    </Container>
                </section>

                {/* =======================================================
                    GRID
                ======================================================= */}

                <section className={spacing.sectionBottom}>
                    <Container>
                        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {achievements.data.map((achievement, idx) => (
                                <motion.div
                                    key={achievement.id}
                                    initial={{ opacity: 0, y: 24 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, margin: "-40px" }}
                                    transition={{
                                        ...motionTheme.normal,
                                        delay: Math.min(idx * 0.04, 0.3),
                                    }}
                                >
                                    <GlassCard
                                        onClick={() =>
                                            setSelectedAchievement(achievement)
                                        }
                                        className="group relative cursor-pointer overflow-hidden border border-white/70 bg-white/60 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-[0_24px_50px_rgba(16,185,129,0.16)]"
                                    >
                                        <div className="relative aspect-video overflow-hidden bg-slate-100">
                                            {achievement.photo ? (
                                                <img
                                                    src={achievement.photo}
                                                    alt={achievement.title}
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                />
                                            ) : (
                                                <div className="flex h-full items-center justify-center bg-linear-to-br from-emerald-50 to-cyan-50 text-6xl">
                                                    🏆
                                                </div>
                                            )}

                                            <div className="pointer-events-none absolute inset-0 bg-linear-to-t from-slate-900/70 via-slate-900/0 to-slate-900/0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" />

                                            <div className="absolute left-4 top-4">
                                                <Badge
                                                    variant="primary"
                                                    className="border border-white/40 bg-white/85 text-emerald-700 shadow-sm backdrop-blur-md"
                                                >
                                                    {achievement.level}
                                                </Badge>
                                            </div>

                                            <span className="absolute right-4 top-4 rounded-full bg-slate-900/50 px-3 py-1 text-xs font-medium text-white backdrop-blur-md">
                                                {achievement.date}
                                            </span>
                                        </div>

                                        <div className="p-6">
                                            <h3 className="line-clamp-2 text-xl font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-600">
                                                {achievement.title}
                                            </h3>

                                            <p className="mt-2 text-sm text-slate-500">
                                                {achievement.competition}
                                            </p>

                                            <div className="mt-4 border-t border-slate-100 pt-4">
                                                <p className="text-xs uppercase tracking-widest text-slate-400">
                                                    Peserta
                                                </p>

                                                <p className="font-semibold text-slate-900">
                                                    {
                                                        achievement.participant_names
                                                    }
                                                </p>

                                                {achievement.participant_count >
                                                    2 && (
                                                    <p className="text-xs text-emerald-600">
                                                        +
                                                        {achievement.participant_count -
                                                            2}{" "}
                                                        siswa lainnya
                                                    </p>
                                                )}
                                            </div>
                                        </div>

                                        <div className="pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-linear-to-r from-emerald-400 via-teal-400 to-cyan-400 transition-transform duration-500 group-hover:scale-x-100" />
                                    </GlassCard>
                                </motion.div>
                            ))}
                        </div>

                        {achievements.data.length === 0 && (
                            <GlassCard className="border border-white/70 bg-white/50 py-20 text-center backdrop-blur-xl">
                                <div className="text-6xl">🏆</div>

                                <h3 className="mt-6 text-2xl font-black text-slate-900">
                                    Tidak ditemukan prestasi yang sesuai dengan
                                    pencarian Anda.
                                </h3>

                                <p className="mt-3 text-slate-500">
                                    Data prestasi akan ditampilkan di sini.
                                </p>
                            </GlassCard>
                        )}
                    </Container>
                </section>
                {/* =======================================================
    PAGINATION
======================================================= */}

                {achievements.last_page > 1 && (
                    <div className="mt-12 flex justify-center pb-4">
                        <div className="flex flex-wrap items-center gap-2 rounded-2xl border border-white/70 bg-white/50 p-2 shadow-sm backdrop-blur-xl">
                            {achievements.links.map((link, index) => (
                                <button
                                    key={index}
                                    disabled={!link.url}
                                    onClick={() => {
                                        if (!link.url) return;

                                        router.visit(link.url, {
                                            preserveScroll: true,
                                            preserveState: true,
                                        });
                                    }}
                                    className={cn(
                                        "flex h-11 min-w-11 items-center justify-center rounded-2xl px-4 text-sm font-semibold transition-all duration-300",
                                        link.active
                                            ? "bg-linear-to-r from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-200"
                                            : "bg-transparent text-slate-700 hover:bg-emerald-50",
                                        !link.url &&
                                            "cursor-not-allowed opacity-40",
                                    )}
                                    dangerouslySetInnerHTML={{
                                        __html: link.label,
                                    }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </main>

            {/* =======================================================
                MODAL DETAIL PRESTASI — CERTIFICATE STYLE
            ======================================================= */}
            <AnimatePresence>
                {selectedAchievement && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-xl"
                        onClick={() => setSelectedAchievement(null)}
                    >
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 15 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 15 }}
                            transition={{
                                type: "spring",
                                damping: 25,
                                stiffness: 300,
                            }}
                            onClick={(e) => e.stopPropagation()}
                            // Desain kontainer dibuat memanjang (portrait) tanpa banyak border tebal
                            className="relative flex w-full max-w-105 max-h-[90vh] flex-col overflow-hidden rounded-3xl bg-[#0A1F16] shadow-[0_20px_60px_-15px_rgba(201,162,75,0.15)]"
                            style={{
                                border: "1px solid rgba(201, 162, 75, 0.2)",
                                background:
                                    "radial-gradient(120% 100% at 50% 0%, #113826 0%, #06120D 100%)",
                            }}
                        >
                            {/* Font Fraunces (Pastikan ini dipindah ke index.css atau _document jika memungkinkan) */}
                            <style>{`
                    @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&display=swap');
                `}</style>

                            {/* SVG Dekoratif (Lintasan) - Dibuat sangat halus sebagai watermark di belakang */}
                            <svg
                                viewBox="0 0 400 400"
                                className="pointer-events-none absolute -right-32 -top-32 z-0 h-125 w-125 opacity-[0.06]"
                            >
                                {[80, 140, 200, 260, 320].map((r) => (
                                    <circle
                                        key={r}
                                        cx="200"
                                        cy="200"
                                        r={r}
                                        fill="none"
                                        stroke="#C9A24B"
                                        strokeWidth="1"
                                    />
                                ))}
                            </svg>

                            {/* Tombol Close Elegan */}
                            <button
                                onClick={() => setSelectedAchievement(null)}
                                className="absolute right-4 top-4 z-50 flex h-8 w-8 items-center justify-center rounded-full bg-black/40 text-[#C9A24B] backdrop-blur-md transition hover:bg-[#C9A24B] hover:text-black"
                            >
                                ✕
                            </button>

                            {/* KONTEN UTAMA (Scrollable) */}
                            <div className="relative z-10 flex flex-col overflow-y-auto custom-scrollbar">
                                {/* 1. HEADER & LOGO (Tengah, Minimalis) */}
                                <div className="flex flex-col items-center pt-8 pb-4 text-center">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-[#1A4D38] to-[#0A1F16] border border-[#C9A24B]/30 shadow-lg">
                                        {settings?.logo ? (
                                            <img
                                                src={settings.logo}
                                                alt="Logo"
                                                className="h-3/4 w-3/4 object-contain"
                                            />
                                        ) : (
                                            <span className="text-sm font-bold text-[#C9A24B]">
                                                S3
                                            </span>
                                        )}
                                    </div>
                                    <p className="mt-4 text-[10px] font-medium uppercase tracking-[0.3em] text-[#C9A24B]/80">
                                        Penghargaan Prestasi
                                    </p>
                                    <h3
                                        className="mt-1 px-6 text-3xl italic leading-tight text-[#F6F1E4] sm:text-4xl"
                                        style={{
                                            fontFamily: "'Fraunces', serif",
                                            fontWeight: 400,
                                        }}
                                    >
                                        Selamat &amp; Bangga
                                    </h3>
                                </div>

                                {/* 2. HERO IMAGE (Foto proporsional dengan sentuhan gradasi transisi) */}
                                <div className="relative mx-6 mt-2 overflow-hidden rounded-2xl border border-[#C9A24B]/20 bg-[#06120D] shadow-inner">
                                    <div className="aspect-4/3 w-full">
                                        {selectedAchievement.photo ? (
                                            <img
                                                src={selectedAchievement.photo}
                                                alt="Perwakilan siswa"
                                                className="h-full w-full object-cover opacity-90 transition-opacity hover:opacity-100"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center text-6xl opacity-50">
                                                🏅
                                            </div>
                                        )}
                                    </div>
                                    {/* Aksen pita kecil di atas foto */}
                                    <div className="absolute right-0 top-0 rounded-bl-xl bg-[#C9A24B] px-3 py-1">
                                        <span className="text-[9px] font-bold uppercase tracking-wider text-[#06120D]">
                                            {selectedAchievement.extracurricular ??
                                                "Sekolah"}
                                        </span>
                                    </div>
                                </div>

                                {/* 3. DETAIL PRESTASI (Editorial Style - Garis Tipis, Tanpa Background Kotak) */}
                                <div className="flex flex-col px-8 pb-10 pt-6 text-center">
                                    <h2
                                        className="text-2xl leading-snug sm:text-3xl"
                                        style={{
                                            fontFamily: "'Fraunces', serif",
                                            fontWeight: 700,
                                            background:
                                                "linear-gradient(180deg, #F6F1E4, #C9A24B 80%)",
                                            WebkitBackgroundClip: "text",
                                            WebkitTextFillColor: "transparent",
                                        }}
                                    >
                                        {selectedAchievement.title}
                                    </h2>

                                    {/* Pemisah Tipis */}
                                    <div className="mx-auto my-5 flex w-full max-w-50 items-center justify-center gap-3 opacity-60">
                                        <div className="h-px w-full bg-linear-to-r from-transparent to-[#C9A24B]" />
                                        <div className="h-1.5 w-1.5 rotate-45 border border-[#C9A24B]" />
                                        <div className="h-px w-full bg-linear-to-l from-transparent to-[#C9A24B]" />
                                    </div>

                                    {/* Nama Siswa */}
                                    <div className="mb-4">
                                        <p className="mb-1.5 text-[9px] font-medium uppercase tracking-[0.2em] text-[#C9A24B]/70">
                                            Diberikan Kepada
                                        </p>
                                        <div className="flex flex-col gap-1">
                                            {selectedAchievement.participants?.map(
                                                (student) => (
                                                    <p
                                                        key={student.id}
                                                        className="text-lg font-semibold text-[#F6F1E4] tracking-wide"
                                                    >
                                                        {student.nama}
                                                    </p>
                                                ),
                                            )}
                                        </div>
                                    </div>

                                    {/* Detail Event */}
                                    <div className="mt-2 flex flex-col items-center justify-center gap-1.5 rounded-lg border border-[#C9A24B]/15 bg-[#113826]/30 p-3">
                                        <p className="text-[11px] font-medium uppercase tracking-widest text-[#C9A24B]">
                                            {selectedAchievement.competition}
                                        </p>
                                        <div className="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-wider text-[#F6F1E4]/70">
                                            <span>
                                                Tingkat{" "}
                                                {selectedAchievement.level}
                                            </span>
                                            <span className="h-2 w-px bg-[#C9A24B]/50" />
                                            <span>
                                                {selectedAchievement.date}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </FrontendLayout>
    );
}
