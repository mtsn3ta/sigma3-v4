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
    MODAL DETAIL PRESTASI (POSTER STYLE - SIGMA THEME)
======================================================= */}
            <AnimatePresence>
                {selectedAchievement && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 p-2 backdrop-blur-md sm:p-4"
                        onClick={() => setSelectedAchievement(null)}
                    >
                        <motion.div
                            initial={{ opacity: 0, scale: 0.9, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.9, y: 20 }}
                            transition={{
                                type: "spring",
                                damping: 25,
                                stiffness: 300,
                            }}
                            onClick={(e) => e.stopPropagation()}
                            // KONTEMPORER: Menggunakan garis tepi Emerald dan efek cahaya hijau dari brand primary
                            className="relative flex w-full max-w-3xl flex-col overflow-hidden rounded-3xl border-4 border-emerald-500 bg-slate-900 shadow-[0_0_40px_rgba(5,150,105,0.3)] sm:aspect-4/5"
                        >
                            {/* 1. Background Placeholder */}
                            <div
                                className="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-luminosity"
                                style={{
                                    backgroundImage: `url('https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&q=80')`,
                                }}
                            />
                            {/* Overlay gradasi: Emerald gelap ke Slate */}
                            <div className="absolute inset-0 bg-linear-to-b from-emerald-950/70 via-slate-900/80 to-slate-950" />

                            {/* Tombol Close */}
                            <button
                                onClick={() => setSelectedAchievement(null)}
                                className="absolute right-4 top-4 z-50 flex h-10 w-10 items-center justify-center rounded-full bg-slate-800/80 text-white backdrop-blur-md transition-colors hover:bg-emerald-600"
                            >
                                ✕
                            </button>

                            {/* KONTEN POSTER (Z-Index di atas background) */}
                            <div className="relative z-10 flex h-full flex-col justify-between overflow-y-auto p-6 sm:overflow-hidden sm:p-8">
                                {/* BAGIAN ATAS: Judul Selamat & Bangga + Logo */}
                                <div className="flex w-full items-start justify-between gap-4">
                                    <div className="flex flex-col items-start">
                                        {/* Bayangan tulisan diubah menggunakan Accent Color (Sky 500) */}
                                        <h3 className="text-4xl font-black italic tracking-tighter text-white drop-shadow-[2px_2px_0_#0EA5E9] sm:text-6xl">
                                            Selamat & <br /> Bangga
                                        </h3>
                                        {/* Pita menggunakan warna Emerald */}
                                        <div className="mt-2 -skew-x-12 border-b-4 border-emerald-400 bg-emerald-900 px-6 py-1 shadow-lg">
                                            <p className="font-bold uppercase tracking-widest text-emerald-300">
                                                Atas Prestasi
                                            </p>
                                        </div>
                                    </div>

                                    {/* LOGO SEKOLAH SIGMA-3 */}
                                    <div className="hidden items-center gap-3.5 rounded-2xl border border-(--rk-line) bg-(--rk-panel-soft) py-2.5 pl-2.5 pr-5 backdrop-blur-xl sm:flex">
                                        <div className="relative flex h-[clamp(3rem,7vh,4.25rem)] w-[clamp(3rem,7vh,4.25rem)] items-center justify-center rounded-xl border border-(--rk-line) bg-(--rk-panel)">
                                            {settings?.logo ? (
                                                <img
                                                    src={settings.logo}
                                                    alt={
                                                        settings?.school_name ||
                                                        "Logo Sekolah"
                                                    }
                                                    className="h-2/3 w-2/3 object-contain"
                                                />
                                            ) : (
                                                <span className="rk-mono text-lg font-bold text-(--rk-emerald)">
                                                    S3
                                                </span>
                                            )}
                                        </div>

                                        <div className="text-left">
                                            <div className="rk-mono text-[clamp(1.05rem,2.4vh,1.5rem)] font-bold leading-none tracking-[0.3em] text-(--rk-gold)">
                                                SIGMA-3
                                            </div>
                                            <div className="mt-1.5 text-[clamp(0.62rem,1.4vh,0.85rem)] font-medium uppercase leading-none tracking-[0.28em] text-(--rk-muted)">
                                                {settings?.school_name ??
                                                    "Sistem Informasi Global"}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* BAGIAN TENGAH & BAWAH: Layout Grid Dinamis */}
                                <div className="mt-8 flex h-full flex-col gap-6 sm:mt-0 sm:flex-row sm:items-end sm:justify-between">
                                    {/* KIRI: Area Foto Siswa & Nama Tag */}
                                    <div className="flex flex-1 flex-col items-center justify-end sm:items-start">
                                        {/* Bingkai foto oval atas */}
                                        <div className="relative z-0 h-64 w-52 shrink-0 overflow-hidden rounded-t-full border-4 border-b-0 border-emerald-500 bg-slate-100 shadow-2xl sm:h-80 sm:w-64">
                                            {selectedAchievement.photo ? (
                                                <img
                                                    src={
                                                        selectedAchievement.photo
                                                    }
                                                    alt="Winner"
                                                    className="h-full w-full object-cover"
                                                />
                                            ) : (
                                                <div className="flex h-full items-center justify-center bg-linear-to-b from-slate-200 to-slate-400 text-6xl">
                                                    🏆
                                                </div>
                                            )}
                                        </div>

                                        {/* Pita Nama Siswa (Gelap dengan aksen Emerald) */}
                                        <div className="relative z-10 -mt-6 flex w-full max-w-70 -skew-x-6 flex-col border-2 border-emerald-500 bg-slate-800 p-3 shadow-2xl">
                                            {selectedAchievement.participants?.map(
                                                (student) => (
                                                    <p
                                                        key={student.id}
                                                        className="text-center text-lg font-black uppercase leading-tight text-white sm:text-xl"
                                                    >
                                                        {student.nama}
                                                    </p>
                                                ),
                                            )}
                                            {/* Keterangan Tambahan Siswa */}
                                            <div className="mt-1 bg-emerald-500 py-1 text-center">
                                                <p className="text-xs font-bold uppercase text-slate-950">
                                                    Perwakilan Siswa
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* KANAN: Detail Juara (Teks Raksasa) */}
                                    <div className="flex flex-col items-center pb-4 text-center sm:items-end sm:pb-0 sm:text-right">
                                        {/* Juara X (Menyesuaikan warna Emerald Gradient ala metalik) */}
                                        <h2 className="bg-linear-to-b from-emerald-100 via-emerald-500 to-emerald-700 bg-clip-text text-6xl font-black uppercase tracking-tighter text-transparent drop-shadow-[0_4px_4px_rgba(0,0,0,0.8)] sm:text-8xl">
                                            {selectedAchievement.title}
                                        </h2>

                                        {/* Pita Cabang Lomba (Menggunakan Accent Color Sky 500) */}
                                        <div className="-mt-2 -skew-x-12 border-y-4 border-sky-300 bg-sky-500 px-8 py-2 shadow-xl sm:mt-0">
                                            <p className="text-2xl font-black uppercase tracking-wider text-slate-950 sm:text-4xl">
                                                {selectedAchievement.extracurricular ??
                                                    "Sekolah"}
                                            </p>
                                        </div>

                                        {/* Detail Nama & Tingkat Lomba (Menggunakan Glass/Background theme) */}
                                        <div className="mt-4 flex w-full max-w-sm flex-col border border-emerald-500/40 bg-slate-900/80 p-4 shadow-lg backdrop-blur-md sm:max-w-md">
                                            <p className="text-sm font-bold uppercase text-emerald-400">
                                                Nomor Lomba / Event
                                            </p>
                                            <p className="mt-1 text-xl font-black uppercase text-white sm:text-2xl">
                                                {
                                                    selectedAchievement.competition
                                                }
                                            </p>
                                            <div className="mt-3 flex items-center justify-center gap-2 sm:justify-end">
                                                <span className="bg-emerald-600 px-3 py-1 text-xs font-bold uppercase text-white">
                                                    Tingkat{" "}
                                                    {selectedAchievement.level}
                                                </span>
                                                <span className="border border-slate-700 bg-slate-800 px-3 py-1 text-xs font-bold uppercase text-sky-400">
                                                    {selectedAchievement.date}
                                                </span>
                                            </div>
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
