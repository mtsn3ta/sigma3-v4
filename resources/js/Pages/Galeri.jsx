import { motion, AnimatePresence } from "motion/react";
import { Head, usePage, router } from "@inertiajs/react";
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
} from "@/Theme";
import {
    Folder,
    Camera,
    Image as ImageIcon,
    Trophy,
    Compass,
    Search,
    Calendar,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    X,
    RotateCcw,
    Maximize2,
    ArrowRight,
} from "lucide-react";

export default function Galeri() {
    const {
        settings,
        statistics = {},
        albums = { data: [], links: [] },
        filters = {},
        years = [],
    } = usePage().props;

    const [search, setSearch] = useState(filters.search ?? "");
    const [year, setYear] = useState(filters.tahun ?? "");
    const [selectedAlbum, setSelectedAlbum] = useState(null);
    const [selectedPhoto, setSelectedPhoto] = useState(0);
    const [lightboxOpen, setLightboxOpen] = useState(false);

    const hasActiveFilters = Boolean(search || year);

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
                    Math.min(v + 1, (selectedAlbum.photos?.length || 1) - 1),
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

    const applyFilter = (customParams = {}) => {
        router.get(
            "/galeri",
            {
                search,
                tahun: year,
                ...customParams,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const resetFilters = () => {
        setSearch("");
        setYear("");
        router.get(
            "/galeri",
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    return (
        <FrontendLayout>
            <Head title={`Galeri Dokumentasi - ${settings?.school_name ?? "SIGMA-3"}`} />

            <main className={cn(background.gradient, "relative bg-[#f8faf9] min-h-screen")}>
                {/* =======================================================
                    HERO & STATS SECTION
                ======================================================= */}
                <section
                    className={cn(
                        background.hero,
                        spacing.pageHeroTop,
                        "relative pb-10 overflow-hidden",
                    )}
                >
                    {/* Background Ambience */}
                    <div className="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
                        <div className="absolute -left-32 -top-24 h-96 w-96 rounded-full bg-teal-400/20 blur-[120px]" />
                        <div className="absolute -right-24 top-10 h-80 w-80 rounded-full bg-emerald-400/20 blur-[100px]" />
                    </div>

                    <Container>
                        <div className="mx-auto max-w-4xl text-center">
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="mb-4 inline-flex items-center gap-2 rounded-full border border-teal-200/80 bg-teal-50/90 px-4 py-1.5 text-xs font-semibold text-teal-800 shadow-xs backdrop-blur-md"
                            >
                                <Camera className="h-3.5 w-3.5 text-teal-600" />
                                <span>Dokumentasi Visual &bull; Momen Madrasah</span>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900"
                            >
                                Portal{" "}
                                <span className="bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 bg-clip-text text-transparent">
                                    Galeri Foto
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 12 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ ...motionTheme.normal, delay: 0.1 }}
                                className="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600"
                            >
                                Arsip dokumentasi kegiatan madrasah, upacara resmi, festival seni,
                                serta karya dan momen istimewa siswa MTsN 3 Tulungagung.
                            </motion.p>
                        </div>

                        {/* 4 Metric Cards */}
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ ...motionTheme.normal, delay: 0.2 }}
                            className="mt-10 grid grid-cols-2 gap-4 md:grid-cols-4"
                        >
                            {[
                                {
                                    label: "Album Galeri",
                                    value: albums?.total ?? 0,
                                    icon: Folder,
                                    color: "text-teal-600",
                                    bg: "bg-teal-50 border-teal-100",
                                },
                                {
                                    label: "Total Foto",
                                    value:
                                        albums?.data?.reduce(
                                            (total, album) => total + (album.photo_count || 0),
                                            0,
                                        ) ?? 0,
                                    icon: Camera,
                                    color: "text-emerald-600",
                                    bg: "bg-emerald-50 border-emerald-100",
                                },
                                {
                                    label: "Ekstrakurikuler",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: Compass,
                                    color: "text-indigo-600",
                                    bg: "bg-indigo-50 border-indigo-100",
                                },
                                {
                                    label: "Prestasi Terkait",
                                    value: statistics.achievements ?? 0,
                                    icon: Trophy,
                                    color: "text-amber-500",
                                    bg: "bg-amber-50 border-amber-100",
                                },
                            ].map((stat, idx) => {
                                const IconComponent = stat.icon;
                                return (
                                    <GlassCard
                                        key={idx}
                                        className="group relative overflow-hidden border border-white/80 bg-white/70 p-5 sm:p-6 text-center shadow-[0_4px_20px_rgb(0,0,0,0.03)] backdrop-blur-xl rounded-3xl transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)]"
                                    >
                                        <div className="flex items-center justify-center mb-3">
                                            <div className={cn("p-2 rounded-2xl border", stat.bg)}>
                                                <IconComponent className={cn("w-5 h-5", stat.color)} />
                                            </div>
                                        </div>

                                        <p className="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 group-hover:text-emerald-700 transition-colors">
                                            {stat.value}
                                        </p>

                                        <p className="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                            {stat.label}
                                        </p>
                                    </GlassCard>
                                );
                            })}
                        </motion.div>
                    </Container>
                </section>

                {/* =======================================================
                    FILTER SECTION
                ======================================================= */}
                <section className="relative z-10 py-6">
                    <Container>
                        <GlassCard className="border border-white/80 bg-white/75 p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl rounded-3xl">
                            <div className="grid gap-3 lg:grid-cols-[1fr_220px]">
                                {/* Search */}
                                <div className="relative">
                                    <Search className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <input
                                        type="text"
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") applyFilter();
                                        }}
                                        placeholder="Cari album kegiatan, acara, atau kata kunci..."
                                        className="h-11 w-full rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-4 text-sm text-slate-800 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15"
                                    />
                                </div>

                                {/* Tahun */}
                                <div className="relative">
                                    <Calendar className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <select
                                        value={year}
                                        onChange={(e) => {
                                            setYear(e.target.value);
                                            applyFilter({ tahun: e.target.value });
                                        }}
                                        className="h-11 w-full appearance-none rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-10 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                    >
                                        <option value="">Semua Tahun</option>
                                        {years?.map((t) => (
                                            <option key={t} value={t}>
                                                {t}
                                            </option>
                                        ))}
                                    </select>
                                    <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                </div>
                            </div>

                            {/* Active Filters Pill Bar */}
                            {hasActiveFilters && (
                                <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span className="text-slate-500 font-medium">
                                        Menampilkan album terfilter
                                    </span>
                                    <button
                                        type="button"
                                        onClick={resetFilters}
                                        className="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors cursor-pointer"
                                    >
                                        <RotateCcw className="w-3.5 h-3.5" />
                                        <span>Reset Filter</span>
                                    </button>
                                </div>
                            )}
                        </GlassCard>
                    </Container>
                </section>

                {/* =======================================================
                    ALBUMS GRID SECTION
                ======================================================= */}
                <section className="pb-20">
                    <Container>
                        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {albums.data?.map((album, idx) => (
                                <motion.div
                                    key={album.id}
                                    initial={{ opacity: 0, y: 20 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, margin: "-40px" }}
                                    transition={{
                                        ...motionTheme.normal,
                                        delay: Math.min(idx * 0.04, 0.25),
                                    }}
                                >
                                    <GlassCard
                                        onClick={() => {
                                            setSelectedAlbum(album);
                                            setSelectedPhoto(0);
                                        }}
                                        className="group relative cursor-pointer overflow-hidden border border-white/80 bg-white/70 backdrop-blur-xl rounded-3xl transition-all duration-400 hover:-translate-y-1.5 hover:border-emerald-200 hover:bg-white hover:shadow-[0_16px_36px_rgba(16,185,129,0.12)] flex flex-col justify-between"
                                    >
                                        <div>
                                            {/* Cover Image */}
                                            <div className="relative aspect-video overflow-hidden bg-slate-100 rounded-t-3xl">
                                                {album.cover ? (
                                                    <img
                                                        src={album.cover}
                                                        alt={album.title}
                                                        loading="lazy"
                                                        decoding="async"
                                                        className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                    />
                                                ) : (
                                                    <div className="flex h-full items-center justify-center bg-gradient-to-br from-teal-50 to-emerald-50 text-teal-600">
                                                        <Camera className="w-16 h-16 stroke-1 drop-shadow-sm" />
                                                    </div>
                                                )}

                                                <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100" />

                                                {/* Category Badge */}
                                                <div className="absolute left-3.5 top-3.5">
                                                    <Badge
                                                        variant="primary"
                                                        className="border border-white/60 bg-white/90 text-emerald-800 shadow-xs backdrop-blur-md"
                                                    >
                                                        {album.category || "Dokumentasi"}
                                                    </Badge>
                                                </div>

                                                {/* Date Pill */}
                                                <span className="absolute right-3.5 top-3.5 rounded-full bg-slate-900/60 px-2.5 py-0.5 text-[11px] font-semibold text-white backdrop-blur-md">
                                                    {album.date}
                                                </span>
                                            </div>

                                            {/* Card Content */}
                                            <div className="p-6">
                                                <h3 className="line-clamp-2 text-lg font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-700 leading-snug">
                                                    {album.title}
                                                </h3>

                                                <div
                                                    className="mt-2 line-clamp-2 text-xs text-slate-500 leading-relaxed"
                                                    dangerouslySetInnerHTML={{
                                                        __html:
                                                            album.description ||
                                                            "Dokumentasi momen kegiatan resmi civitas madrasah.",
                                                    }}
                                                />

                                                {/* Meta Info */}
                                                <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600">
                                                    <div className="flex items-center gap-1.5 text-teal-700">
                                                        <Camera className="w-4 h-4" />
                                                        <span>{album.photo_count || 0} Foto</span>
                                                    </div>
                                                    {album.extracurricular && (
                                                        <span className="text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md truncate max-w-[150px]">
                                                            {album.extracurricular}
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        </div>

                                        <div className="px-6 pb-5 pt-0 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition-colors">
                                            <span>Buka Album</span>
                                            <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                        </div>
                                    </GlassCard>
                                </motion.div>
                            ))}
                        </div>

                        {/* Empty State */}
                        {(!albums.data || albums.data.length === 0) && (
                            <GlassCard className="border border-white/80 bg-white/70 py-20 text-center backdrop-blur-xl rounded-3xl max-w-lg mx-auto">
                                <div className="h-16 w-16 rounded-3xl bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center mx-auto mb-4">
                                    <ImageIcon className="w-8 h-8 stroke-1" />
                                </div>

                                <h3 className="text-xl font-black text-slate-900">
                                    Belum Ada Album Galeri
                                </h3>

                                <p className="mt-2 text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                                    Belum terdapat dokumentasi foto untuk pencarian atau tahun yang Anda pilih.
                                </p>

                                {hasActiveFilters && (
                                    <button
                                        type="button"
                                        onClick={resetFilters}
                                        className="mt-5 inline-flex items-center gap-2 rounded-2xl bg-emerald-600 text-white text-xs font-bold px-5 py-2.5 shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors cursor-pointer"
                                    >
                                        <RotateCcw className="w-3.5 h-3.5" />
                                        <span>Reset Filter</span>
                                    </button>
                                )}
                            </GlassCard>
                        )}

                        {/* Pagination */}
                        {albums.last_page > 1 && (
                            <div className="mt-12 flex justify-center">
                                <div className="flex flex-wrap items-center gap-1.5 rounded-2xl border border-white/80 bg-white/70 p-2 shadow-xs backdrop-blur-xl">
                                    {albums.links?.map((link, index) => (
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
                                                "flex h-9 min-w-9 items-center justify-center rounded-xl px-3 text-xs font-bold transition-all cursor-pointer",
                                                link.active
                                                    ? "bg-emerald-600 text-white shadow-md shadow-emerald-600/25"
                                                    : "bg-transparent text-slate-700 hover:bg-emerald-50 hover:text-emerald-700",
                                                !link.url && "cursor-not-allowed opacity-40",
                                            )}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </Container>
                </section>
            </main>

            {/* =======================================================
                MODAL DETAIL ALBUM
            ======================================================= */}
            <AnimatePresence>
                {selectedAlbum && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-md"
                        onClick={() => setSelectedAlbum(null)}
                    >
                        <motion.div
                            initial={{ opacity: 0, y: 20, scale: 0.98 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, y: 20, scale: 0.98 }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative max-h-[92vh] w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-2xl flex flex-col"
                        >
                            {/* Modal Header */}
                            <div className="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                                <div>
                                    <div className="flex items-center gap-2">
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                            {selectedAlbum.category || "Album"}
                                        </span>
                                        <span className="text-xs text-slate-400">&bull;</span>
                                        <span className="text-xs font-semibold text-slate-500">
                                            {selectedAlbum.date}
                                        </span>
                                    </div>
                                    <h2 className="mt-1 text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                                        {selectedAlbum.title}
                                    </h2>
                                </div>

                                <button
                                    onClick={() => setSelectedAlbum(null)}
                                    className="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer"
                                    aria-label="Tutup modal album"
                                >
                                    <X className="w-5 h-5" />
                                </button>
                            </div>

                            {/* Modal Body */}
                            <div className="grid gap-6 overflow-y-auto p-6 lg:grid-cols-[2fr_1fr]">
                                {/* Main Selected Photo */}
                                <div>
                                    {selectedAlbum.photos && selectedAlbum.photos.length > 0 ? (
                                        <div className="relative group rounded-2xl overflow-hidden bg-slate-900">
                                            <img
                                                src={selectedAlbum.photos[selectedPhoto]?.url}
                                                alt={selectedAlbum.title}
                                                loading="lazy"
                                                decoding="async"
                                                onClick={() => setLightboxOpen(true)}
                                                className="aspect-video w-full cursor-zoom-in object-contain bg-slate-950 transition hover:opacity-95"
                                            />
                                            <button
                                                onClick={() => setLightboxOpen(true)}
                                                className="absolute bottom-3 right-3 bg-black/60 hover:bg-black/80 text-white text-xs font-semibold px-3 py-1.5 rounded-full backdrop-blur-md flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                                            >
                                                <Maximize2 className="w-3.5 h-3.5" />
                                                <span>Layar Penuh</span>
                                            </button>
                                        </div>
                                    ) : (
                                        <div className="flex aspect-video items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <Camera className="w-16 h-16 stroke-1" />
                                        </div>
                                    )}

                                    {/* Thumbnails Row */}
                                    {selectedAlbum.photos && selectedAlbum.photos.length > 1 && (
                                        <div className="mt-4 flex gap-2.5 overflow-x-auto pb-2">
                                            {selectedAlbum.photos.map((photo, index) => (
                                                <button
                                                    key={photo.id || index}
                                                    onClick={() => setSelectedPhoto(index)}
                                                    className={cn(
                                                        "overflow-hidden rounded-xl border-2 shrink-0 transition cursor-pointer",
                                                        selectedPhoto === index
                                                            ? "border-emerald-500 ring-2 ring-emerald-500/20"
                                                            : "border-transparent opacity-60 hover:opacity-100",
                                                    )}
                                                >
                                                    <img
                                                        src={photo.url}
                                                        alt="Thumbnail"
                                                        className="h-16 w-24 object-cover"
                                                    />
                                                </button>
                                            ))}
                                        </div>
                                    )}
                                </div>

                                {/* Sidebar Info */}
                                <div className="space-y-4 text-xs sm:text-sm">
                                    <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                        <span className="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-1">
                                            Total Koleksi
                                        </span>
                                        <p className="text-xl font-black text-slate-900">
                                            {selectedAlbum.photo_count || 0} Foto
                                        </p>
                                    </div>

                                    {selectedAlbum.extracurricular && (
                                        <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                            <span className="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-1">
                                                Ekstrakurikuler Terkait
                                            </span>
                                            <p className="font-bold text-slate-900">
                                                {selectedAlbum.extracurricular}
                                            </p>
                                        </div>
                                    )}

                                    <div>
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">
                                            Deskripsi Kegiatan
                                        </span>
                                        <div
                                            className="text-slate-600 leading-relaxed text-xs sm:text-sm"
                                            dangerouslySetInnerHTML={{
                                                __html:
                                                    selectedAlbum.description ||
                                                    "<p>Dokumentasi resmi kegiatan MTsN 3 Tulungagung.</p>",
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>

            {/* =======================================================
                FULLSCREEN LIGHTBOX
            ======================================================= */}
            <AnimatePresence>
                {lightboxOpen && selectedAlbum && selectedAlbum.photos?.length > 0 && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed inset-0 z-60 flex items-center justify-center bg-black/95 p-4"
                        onClick={() => setLightboxOpen(false)}
                    >
                        {/* Close Lightbox */}
                        <button
                            onClick={() => setLightboxOpen(false)}
                            className="absolute right-6 top-6 z-20 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md hover:bg-white/20 transition cursor-pointer"
                            aria-label="Tutup layar penuh"
                        >
                            <X className="w-5 h-5" />
                        </button>

                        {/* Prev Photo */}
                        {selectedPhoto > 0 && (
                            <button
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setSelectedPhoto((v) => v - 1);
                                }}
                                className="absolute left-6 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md hover:bg-white/20 transition cursor-pointer"
                                aria-label="Foto sebelumnya"
                            >
                                <ChevronLeft className="w-6 h-6" />
                            </button>
                        )}

                        {/* Image */}
                        <img
                            src={selectedAlbum.photos[selectedPhoto]?.url}
                            alt=""
                            onClick={(e) => e.stopPropagation()}
                            className="max-h-[90vh] max-w-[90vw] rounded-2xl object-contain shadow-2xl select-none"
                        />

                        {/* Next Photo */}
                        {selectedPhoto < selectedAlbum.photos.length - 1 && (
                            <button
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setSelectedPhoto((v) => v + 1);
                                }}
                                className="absolute right-6 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md hover:bg-white/20 transition cursor-pointer"
                                aria-label="Foto berikutnya"
                            >
                                <ChevronRight className="w-6 h-6" />
                            </button>
                        )}

                        {/* Counter Pill */}
                        <div className="absolute bottom-6 rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold text-white backdrop-blur-md">
                            {selectedPhoto + 1} / {selectedAlbum.photos.length}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </FrontendLayout>
    );
}
