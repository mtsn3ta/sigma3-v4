import { motion, AnimatePresence } from "motion/react";
import { Head, usePage, router } from "@inertiajs/react";
import { useEffect, useState } from "react";

import FrontendLayout from "@/Layouts/FrontendLayout";

import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import GlassCard from "@/Components/UI/GlassCard";

import { cn, background, motion as motionTheme, spacing } from "@/Theme";

export default function Galeri() {
    const {
        settings,
        statistics,

        albums,

        filters,

        years,
    } = usePage().props;

    const [search, setSearch] = useState(filters.search ?? "");

    const [year, setYear] = useState(filters.tahun ?? "");

    const [selectedAlbum, setSelectedAlbum] = useState(null);

    const [selectedPhoto, setSelectedPhoto] = useState(0);
    const [lightboxOpen, setLightboxOpen] = useState(false);

    useEffect(() => {
        if (!selectedAlbum) return;

        const handleKeyDown = (e) => {
            if (e.key === "Escape") {
                if (lightboxOpen) {
                    setLightboxOpen(false);
                } else {
                    setSelectedAlbum(null);
                }
            }

            if (!lightboxOpen) return;

            if (e.key === "ArrowRight") {
                setSelectedPhoto((v) =>
                    Math.min(v + 1, selectedAlbum.photos.length - 1),
                );
            }

            if (e.key === "ArrowLeft") {
                setSelectedPhoto((v) => Math.max(v - 1, 0));
            }
        };

        window.addEventListener("keydown", handleKeyDown);

        return () => window.removeEventListener("keydown", handleKeyDown);
    }, [selectedAlbum, lightboxOpen]);

    useEffect(() => {
        document.body.style.overflow = selectedAlbum ? "hidden" : "";

        return () => {
            document.body.style.overflow = "";
        };
    }, [selectedAlbum]);

    const applyFilter = () => {
        router.get(
            "/galeri",
            {
                search,
                tahun: year,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

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
            <Head title={`Galeri - ${settings?.school_name ?? "SIGMA-3"}`} />

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
                                    id="grid-gallery"
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
                                fill="url(#grid-gallery)"
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
                                    Visual Stories
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
                                    Galeri
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
                                Seluruh dokumentasi kegiatan, prestasi, dan
                                momen terbaik MTs Negeri 3 Tulungagung dalam
                                satu portal galeri.
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
                                    label: "Album",
                                    value: albums?.total ?? 0,
                                    icon: "📁",
                                },
                                {
                                    label: "Foto",
                                    value:
                                        albums?.data?.reduce(
                                            (total, album) =>
                                                total + album.photo_count,
                                            0,
                                        ) ?? 0,
                                    icon: "📷",
                                },
                                {
                                    label: "Ekstrakurikuler",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: "🏅",
                                },
                                {
                                    label: "Prestasi",
                                    value: statistics.achievements ?? 0,
                                    icon: "🏆",
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
                                        placeholder="Cari album galeri..."
                                        className="h-12 w-full rounded-2xl border border-white bg-white/70 pl-12 pr-4 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    />
                                </div>

                                {/* Tahun */}

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
                            {albums.data.map((album, idx) => (
                                <motion.div
                                    key={album.id}
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
                                        onClick={() => {
                                            setSelectedAlbum(album);
                                            setSelectedPhoto(0);
                                        }}
                                        className="group relative cursor-pointer overflow-hidden border border-white/70 bg-white/60 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-[0_24px_50px_rgba(16,185,129,0.16)]"
                                    >
                                        {/* Cover */}

                                        <div className="relative aspect-video overflow-hidden bg-slate-100">
                                            {album.cover ? (
                                                <img
                                                    src={album.cover}
                                                    alt={album.title}
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                />
                                            ) : (
                                                <div className="flex h-full items-center justify-center bg-linear-to-br from-emerald-50 to-cyan-50 text-7xl">
                                                    📷
                                                </div>
                                            )}

                                            <div className="absolute left-4 top-4">
                                                <Badge variant="primary">
                                                    {album.category}
                                                </Badge>
                                            </div>

                                            <div className="absolute right-4 top-4 rounded-full bg-slate-900/50 px-3 py-1 text-xs font-medium text-white backdrop-blur-md">
                                                {album.date}
                                            </div>

                                            <div className="pointer-events-none absolute inset-0 bg-linear-to-t from-slate-900/70 via-transparent to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100" />
                                        </div>

                                        {/* Content */}

                                        <div className="p-6">
                                            <h3 className="line-clamp-2 text-xl font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-600">
                                                {album.title}
                                            </h3>

                                            <div
                                                className="prose prose-sm mt-2 line-clamp-2 max-w-none text-slate-500"
                                                dangerouslySetInnerHTML={{
                                                    __html:
                                                        album.description ||
                                                        "<p>Dokumentasi kegiatan MTs Negeri 3 Tulungagung.</p>",
                                                }}
                                            />

                                            <div className="mt-6 grid grid-cols-2 gap-3">
                                                <GlassCard className="p-3 text-center">
                                                    <p className="text-2xl font-black text-slate-900">
                                                        {album.photo_count}
                                                    </p>

                                                    <p className="mt-1 text-xs uppercase tracking-widest text-slate-400">
                                                        Foto
                                                    </p>
                                                </GlassCard>

                                                <GlassCard className="p-3 text-center">
                                                    <p className="truncate text-sm font-bold text-slate-900">
                                                        {album.extracurricular ??
                                                            "-"}
                                                    </p>

                                                    <p className="mt-1 text-xs uppercase tracking-widest text-slate-400">
                                                        Ekstrakurikuler
                                                    </p>
                                                </GlassCard>
                                            </div>
                                        </div>

                                        <div className="pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-linear-to-r from-emerald-400 via-teal-400 to-cyan-400 transition-transform duration-500 group-hover:scale-x-100" />
                                    </GlassCard>
                                </motion.div>
                            ))}
                        </div>

                        {albums.data.length === 0 && (
                            <GlassCard className="border border-white/70 bg-white/50 py-20 text-center backdrop-blur-xl">
                                <div className="text-7xl">📷</div>

                                <h3 className="mt-6 text-2xl font-black text-slate-900">
                                    Belum ada album galeri.
                                </h3>

                                <p className="mt-3 text-slate-500">
                                    Album dokumentasi akan ditampilkan di sini.
                                </p>
                            </GlassCard>
                        )}
                    </Container>
                </section>
                {/* =======================================================
    PAGINATION
======================================================= */}

                {albums.last_page > 1 && (
                    <div className="mt-12 flex justify-center pb-4">
                        <div className="flex flex-wrap items-center gap-2 rounded-2xl border border-white/70 bg-white/50 p-2 shadow-sm backdrop-blur-xl">
                            {albums.links.map((link, index) => (
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
                {selectedAlbum && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-md"
                        onClick={() => {
                            setSelectedPhoto(index);
                            setLightboxOpen(true);
                        }}
                    >
                        <motion.div
                            initial={{ opacity: 0, y: 24, scale: 0.98 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, y: 24, scale: 0.98 }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative max-h-[92vh] w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-2xl"
                        >
                            {/* Header */}

                            <div className="flex items-center justify-between border-b px-8 py-6">
                                <div>
                                    <p className="text-xs font-bold uppercase tracking-[0.2em] text-emerald-500">
                                        Album Galeri
                                    </p>

                                    <h2 className="mt-2 text-3xl font-black">
                                        {selectedAlbum.title}
                                    </h2>
                                </div>

                                <button
                                    onClick={() => setSelectedAlbum(null)}
                                    className="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-xl"
                                >
                                    ✕
                                </button>
                            </div>

                            <div className="grid gap-8 overflow-y-auto p-8 lg:grid-cols-[2fr_1fr]">
                                {/* FOTO BESAR */}

                                <div>
                                    {selectedAlbum.photos.length ? (
                                        <img
                                            src={
                                                selectedAlbum.photos[
                                                    selectedPhoto
                                                ].url
                                            }
                                            alt=""
                                            loading="lazy"
                                            decoding="async"
                                            onClick={() =>
                                                setLightboxOpen(true)
                                            }
                                            className="aspect-video w-full cursor-zoom-in rounded-3xl object-cover transition hover:opacity-95"
                                        />
                                    ) : (
                                        <div className="flex aspect-video items-center justify-center rounded-3xl bg-slate-100 text-8xl">
                                            📷
                                        </div>
                                    )}

                                    {/* Thumbnail */}

                                    <div className="mt-5 flex gap-3 overflow-x-auto">
                                        {selectedAlbum.photos.map(
                                            (photo, index) => (
                                                <button
                                                    key={photo.id}
                                                    onClick={() =>
                                                        setSelectedPhoto(index)
                                                    }
                                                    className={cn(
                                                        "overflow-hidden rounded-xl border-2 transition",
                                                        selectedPhoto === index
                                                            ? "border-emerald-500"
                                                            : "border-transparent",
                                                    )}
                                                >
                                                    <img
                                                        src={photo.url}
                                                        className="h-20 w-28 object-cover"
                                                    />
                                                </button>
                                            ),
                                        )}
                                    </div>
                                </div>

                                {/* SIDEBAR */}

                                <div className="space-y-6">
                                    <GlassCard className="p-5">
                                        <p className="text-xs uppercase tracking-widest text-slate-400">
                                            Kategori
                                        </p>

                                        <p className="mt-1 font-bold">
                                            {selectedAlbum.category}
                                        </p>
                                    </GlassCard>

                                    <GlassCard className="p-5">
                                        <p className="text-xs uppercase tracking-widest text-slate-400">
                                            Tanggal
                                        </p>

                                        <p className="mt-1 font-bold">
                                            {selectedAlbum.date}
                                        </p>
                                    </GlassCard>

                                    <GlassCard className="p-5">
                                        <p className="text-xs uppercase tracking-widest text-slate-400">
                                            Ekstrakurikuler
                                        </p>

                                        <p className="mt-1 font-bold">
                                            {selectedAlbum.extracurricular ??
                                                "-"}
                                        </p>
                                    </GlassCard>

                                    <GlassCard className="p-5">
                                        <p className="text-xs uppercase tracking-widest text-slate-400">
                                            Jumlah Foto
                                        </p>

                                        <p className="mt-1 text-2xl font-black">
                                            {selectedAlbum.photo_count}
                                        </p>
                                    </GlassCard>

                                    <div>
                                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">
                                            Deskripsi
                                        </p>

                                        <div
                                            className="prose prose-slate mt-2 max-w-none"
                                            dangerouslySetInnerHTML={{
                                                __html:
                                                    selectedAlbum.description ||
                                                    "<p>Belum ada deskripsi.</p>",
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
            <AnimatePresence>
                {lightboxOpen && selectedAlbum && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed inset-0 z-60 flex items-center justify-center bg-black/95"
                        onClick={() => setLightboxOpen(false)}
                    >
                        <button
                            onClick={() => setLightboxOpen(false)}
                            className="absolute right-8 top-8 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-2xl text-white backdrop-blur hover:bg-white/20"
                        >
                            ✕
                        </button>

                        {/* Previous */}

                        {selectedPhoto > 0 && (
                            <button
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setSelectedPhoto((v) => v - 1);
                                }}
                                className="absolute left-6 rounded-full bg-white/10 p-4 text-3xl text-white backdrop-blur"
                            >
                                ‹
                            </button>
                        )}

                        {/* Image */}

                        <img
                            src={selectedAlbum.photos[selectedPhoto].url}
                            alt=""
                            onClick={(e) => e.stopPropagation()}
                            className="max-h-[92vh] max-w-[92vw] rounded-2xl object-contain"
                        />

                        {/* Next */}

                        {selectedPhoto < selectedAlbum.photos.length - 1 && (
                            <button
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setSelectedPhoto((v) => v + 1);
                                }}
                                className="absolute right-6 rounded-full bg-white/10 p-4 text-3xl text-white backdrop-blur"
                            >
                                ›
                            </button>
                        )}

                        {/* Counter */}

                        <div className="absolute bottom-8 rounded-full bg-white/10 px-5 py-2 text-sm font-semibold text-white backdrop-blur">
                            {selectedPhoto + 1} / {selectedAlbum.photos.length}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </FrontendLayout>
    );
}
