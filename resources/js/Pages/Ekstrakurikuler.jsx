import { motion, AnimatePresence } from "motion/react";
import { Head, usePage } from "@inertiajs/react";
import { router } from "@inertiajs/react";
import { useEffect, useState } from "react";
import FrontendLayout from "@/Layouts/FrontendLayout";

import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import GlassCard from "@/Components/UI/GlassCard";

import {
    cn,
    background,
    motion as motionTheme,
    spacing,
    glass,
    radius,
} from "@/Theme";

export default function Ekstrakurikuler() {
    const {
        settings,
        statistics,

        extracurriculars,

        filters,
    } = usePage().props;
    const [search, setSearch] = useState(filters.search ?? "");

    const [status, setStatus] = useState(filters.status ?? "");

    const [selectedExtracurricular, setSelectedExtracurricular] =
        useState(null);
    useEffect(() => {
        if (!selectedExtracurricular) return;

        const handleKeyDown = (e) => {
            if (e.key === "Escape") {
                setSelectedExtracurricular(null);
            }
        };

        window.addEventListener("keydown", handleKeyDown);

        return () => window.removeEventListener("keydown", handleKeyDown);
    }, [selectedExtracurricular]);

    useEffect(() => {
        document.body.style.overflow = selectedExtracurricular ? "hidden" : "";

        return () => {
            document.body.style.overflow = "";
        };
    }, [selectedExtracurricular]);
    const applyFilter = () => {
        router.get(
            "/ekstrakurikuler",
            {
                search,
                status,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

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
            <Head
                title={`Ekstrakurikuler - ${settings?.school_name ?? "SIGMA-3"}`}
            />

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
                                    id="grid-ekstrakurikuler"
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
                                fill="url(#grid-ekstrakurikuler)"
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
                                    Life Beyond Classroom
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
                                    Ekstrakurikuler
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
                                Seluruh kegiatan ekstrakurikuler MTs Negeri 3
                                Tulungagung sebagai wadah pengembangan minat,
                                bakat, karakter, dan prestasi siswa.
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
                                    label: "Ekstrakurikuler",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: "🏅",
                                },
                                {
                                    label: "Siswa",
                                    value: statistics.students ?? 0,
                                    icon: "🎓",
                                },
                                {
                                    label: "Prestasi",
                                    value: statistics.achievements ?? 0,
                                    icon: "🏆",
                                },
                                {
                                    label: "Aktif",
                                    value:
                                        extracurriculars?.data?.filter(
                                            (item) => item.status === "Aktif",
                                        ).length ?? 0,
                                    icon: "⚡",
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

                            <div className="grid gap-4 lg:grid-cols-[1fr_220px]">
                                {/* Search */}

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
                                        placeholder="Cari ekstrakurikuler..."
                                        className="h-12 w-full rounded-2xl border border-white bg-white/70 pl-12 pr-4 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    />
                                </div>

                                {/* Status */}

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
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>

                                    <select
                                        value={status}
                                        onChange={(e) => {
                                            setStatus(e.target.value);
                                            applyFilter();
                                        }}
                                        className="h-12 w-full appearance-none rounded-2xl border border-white bg-white/70 pl-11 pr-9 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">
                                            Tidak Aktif
                                        </option>
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
                            {extracurriculars.data.map(
                                (extracurricular, idx) => (
                                    <motion.div
                                        key={extracurricular.id}
                                        initial={{ opacity: 0, y: 24 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{
                                            once: true,
                                            margin: "-40px",
                                        }}
                                        transition={{
                                            ...motionTheme.normal,
                                            delay: Math.min(idx * 0.04, 0.3),
                                        }}
                                    >
                                        <GlassCard
                                            onClick={() =>
                                                setSelectedExtracurricular(
                                                    extracurricular,
                                                )
                                            }
                                            className="group relative cursor-pointer overflow-hidden border border-white/70 bg-white/60 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-[0_24px_50px_rgba(16,185,129,0.16)]"
                                        >
                                            {/* Cover */}

                                            <div className="relative aspect-video overflow-hidden bg-linear-to-br from-emerald-50 via-teal-50 to-cyan-50">
                                                <div className="flex h-full items-center justify-center text-7xl">
                                                    🏅
                                                </div>

                                                <div className="absolute left-4 top-4">
                                                    <Badge
                                                        variant={
                                                            extracurricular.status ===
                                                            "Aktif"
                                                                ? "primary"
                                                                : "secondary"
                                                        }
                                                    >
                                                        {extracurricular.status}
                                                    </Badge>
                                                </div>

                                                <div className="pointer-events-none absolute inset-0 bg-linear-to-t from-slate-900/60 to-transparent opacity-0 transition duration-500 group-hover:opacity-100" />
                                            </div>

                                            {/* Content */}

                                            <div className="p-6">
                                                <h3 className="text-xl font-black text-slate-900 transition group-hover:text-emerald-600">
                                                    {extracurricular.name}
                                                </h3>

                                                <p className="mt-2 line-clamp-2 text-sm text-slate-500">
                                                    {
                                                        extracurricular.description
                                                    }
                                                </p>

                                                <div className="mt-6 grid grid-cols-2 gap-3">
                                                    <GlassCard className="p-3 text-center">
                                                        <p className="text-2xl font-black text-slate-900">
                                                            {
                                                                extracurricular.members
                                                            }
                                                        </p>

                                                        <p className="mt-1 text-xs uppercase tracking-widest text-slate-400">
                                                            Anggota
                                                        </p>
                                                    </GlassCard>

                                                    <GlassCard className="p-3 text-center">
                                                        <p className="text-2xl font-black text-slate-900">
                                                            {
                                                                extracurricular.achievement_count
                                                            }
                                                        </p>

                                                        <p className="mt-1 text-xs uppercase tracking-widest text-slate-400">
                                                            Prestasi
                                                        </p>
                                                    </GlassCard>
                                                </div>

                                                <div className="mt-5 border-t border-slate-100 pt-4">
                                                    <p className="text-xs uppercase tracking-widest text-slate-400">
                                                        Pembina
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {extracurricular
                                                            .supervisors.length
                                                            ? extracurricular
                                                                  .supervisors[0]
                                                            : "-"}
                                                    </p>
                                                </div>
                                                <div className="mt-5 border-t border-slate-100 pt-4">
                                                    <p className="text-xs uppercase tracking-widest text-slate-400">
                                                        Pelatih
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {extracurricular.coaches
                                                            .length
                                                            ? extracurricular
                                                                  .coaches[0]
                                                            : "-"}
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-linear-to-r from-emerald-400 via-teal-400 to-cyan-400 transition-transform duration-500 group-hover:scale-x-100" />
                                        </GlassCard>
                                    </motion.div>
                                ),
                            )}
                        </div>

                        {extracurriculars.data.length === 0 && (
                            <GlassCard className="border border-white/70 bg-white/50 py-20 text-center backdrop-blur-xl">
                                <div className="text-7xl">🏅</div>

                                <h3 className="mt-6 text-2xl font-black text-slate-900">
                                    Tidak ditemukan ekstrakurikuler.
                                </h3>

                                <p className="mt-3 text-slate-500">
                                    Data ekstrakurikuler akan ditampilkan di
                                    sini.
                                </p>
                            </GlassCard>
                        )}
                    </Container>
                </section>
                {/* =======================================================
    PAGINATION
======================================================= */}

                {extracurriculars.last_page > 1 && (
                    <div className="mt-12 flex justify-center pb-4">
                        <div className="flex flex-wrap items-center gap-2 rounded-2xl border border-white/70 bg-white/50 p-2 shadow-sm backdrop-blur-xl">
                            {extracurriculars.links.map((link, index) => (
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
    MODAL DETAIL PRESTASI
======================================================= */}

            <AnimatePresence>
                {selectedExtracurricular && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-md"
                        onClick={() => setSelectedExtracurricular(null)}
                    >
                        <motion.div
                            initial={{ opacity: 0, y: 24, scale: 0.97 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, y: 16, scale: 0.97 }}
                            transition={{ duration: 0.25, ease: "easeOut" }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative max-h-[90vh] w-full max-w-5xl overflow-hidden rounded-3xl border border-white/60 bg-white shadow-2xl"
                        >
                            <div className="pointer-events-none absolute inset-x-0 top-0 h-1 bg-linear-to-r from-emerald-400 via-teal-400 to-cyan-400" />

                            {/* Header */}

                            <div className="flex items-center justify-between border-b border-slate-100 px-8 py-6">
                                <div>
                                    <p className="text-xs font-bold uppercase tracking-[0.2em] text-emerald-500">
                                        Detail Ekstrakurikuler
                                    </p>

                                    <h2 className="mt-2 text-3xl font-black text-slate-900">
                                        {selectedExtracurricular.name}
                                    </h2>
                                </div>

                                <button
                                    onClick={() =>
                                        setSelectedExtracurricular(null)
                                    }
                                    className="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-xl text-slate-500 transition hover:bg-slate-200"
                                >
                                    ✕
                                </button>
                            </div>

                            <div className="grid gap-8 overflow-y-auto p-8 lg:grid-cols-2">
                                {/* Cover */}

                                <div className="flex aspect-4/3 items-center justify-center rounded-3xl bg-linear-to-br from-emerald-50 via-teal-50 to-cyan-50 text-8xl">
                                    🏅
                                </div>

                                {/* Informasi */}

                                <div className="space-y-6">
                                    <div className="grid grid-cols-2 gap-4">
                                        <GlassCard className="border border-emerald-100 bg-emerald-50/50 p-4">
                                            <p className="text-xs uppercase tracking-widest text-slate-400">
                                                Status
                                            </p>

                                            <p className="mt-1 font-bold text-emerald-600">
                                                {selectedExtracurricular.status}
                                            </p>
                                        </GlassCard>

                                        <GlassCard className="border border-slate-100 bg-slate-50/60 p-4">
                                            <p className="text-xs uppercase tracking-widest text-slate-400">
                                                Anggota
                                            </p>

                                            <p className="mt-1 font-bold text-slate-900">
                                                {
                                                    selectedExtracurricular.members
                                                }{" "}
                                                Siswa
                                            </p>
                                        </GlassCard>
                                    </div>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                                            Pembina
                                        </p>

                                        <div className="mt-3 flex flex-wrap gap-2">
                                            {selectedExtracurricular.supervisors
                                                .length ? (
                                                selectedExtracurricular.supervisors.map(
                                                    (name, index) => (
                                                        <span
                                                            key={index}
                                                            className="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700"
                                                        >
                                                            {name}
                                                        </span>
                                                    ),
                                                )
                                            ) : (
                                                <span className="text-sm text-slate-400">
                                                    Belum ada pembina
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                                            Pelatih
                                        </p>

                                        <div className="mt-3 flex flex-wrap gap-2">
                                            {selectedExtracurricular.coaches
                                                .length ? (
                                                selectedExtracurricular.coaches.map(
                                                    (name, index) => (
                                                        <span
                                                            key={index}
                                                            className="rounded-full bg-cyan-50 px-3 py-1 text-sm font-medium text-cyan-700"
                                                        >
                                                            {name}
                                                        </span>
                                                    ),
                                                )
                                            ) : (
                                                <span className="text-sm text-slate-400">
                                                    Belum ada pelatih
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                                            Jadwal Kegiatan
                                        </p>

                                        <div className="mt-3 rounded-2xl bg-slate-50 p-4">
                                            <div className="mt-3 flex flex-wrap gap-2">
                                                {selectedExtracurricular
                                                    .schedule?.length ? (
                                                    selectedExtracurricular.schedule.map(
                                                        (day) => (
                                                            <span
                                                                key={day}
                                                                className="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700"
                                                            >
                                                                {day
                                                                    .charAt(0)
                                                                    .toUpperCase() +
                                                                    day.slice(
                                                                        1,
                                                                    )}
                                                            </span>
                                                        ),
                                                    )
                                                ) : (
                                                    <span className="text-sm text-slate-400">
                                                        Belum ada jadwal.
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                                            Deskripsi
                                        </p>

                                        <p className="mt-2 leading-7 text-slate-600">
                                            {selectedExtracurricular.description ||
                                                "Belum ada deskripsi."}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Prestasi */}

                            <div className="border-t border-slate-100 px-8 py-6">
                                <h3 className="text-lg font-black text-slate-900">
                                    Prestasi Terbaru
                                </h3>

                                <div className="mt-5 space-y-3">
                                    {selectedExtracurricular.achievements
                                        .length ? (
                                        selectedExtracurricular.achievements.map(
                                            (achievement, index) => (
                                                <GlassCard
                                                    key={index}
                                                    className="flex items-center justify-between p-4"
                                                >
                                                    <div>
                                                        <p className="font-bold text-slate-900">
                                                            {achievement.title}
                                                        </p>

                                                        <p className="text-sm text-slate-500">
                                                            {
                                                                achievement.competition
                                                            }
                                                        </p>
                                                    </div>

                                                    <Badge variant="primary">
                                                        {achievement.date}
                                                    </Badge>
                                                </GlassCard>
                                            ),
                                        )
                                    ) : (
                                        <p className="text-sm text-slate-500">
                                            Belum ada prestasi.
                                        </p>
                                    )}
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </FrontendLayout>
    );
}
