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
    Compass,
    Users,
    Trophy,
    Sparkles,
    Search,
    ChevronDown,
    X,
    RotateCcw,
    ArrowRight,
    UserCheck,
    Calendar,
    Clock,
} from "lucide-react";

export default function Ekstrakurikuler() {
    const {
        settings,
        statistics = {},
        extracurriculars = { data: [], links: [] },
        filters = {},
    } = usePage().props;

    const [search, setSearch] = useState(filters.search ?? "");
    const [status, setStatus] = useState(filters.status ?? "");
    const [selectedExtracurricular, setSelectedExtracurricular] = useState(null);

    const hasActiveFilters = Boolean(search || status);

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

    const applyFilter = (customParams = {}) => {
        router.get(
            "/ekstrakurikuler",
            {
                search,
                status,
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
        setStatus("");
        router.get(
            "/ekstrakurikuler",
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
            <Head title={`Ekstrakurikuler - ${settings?.school_name ?? "SIGMA-3"}`} />

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
                        <div className="absolute -left-32 -top-24 h-96 w-96 rounded-full bg-indigo-400/20 blur-[120px]" />
                        <div className="absolute -right-24 top-10 h-80 w-80 rounded-full bg-emerald-400/20 blur-[100px]" />
                    </div>

                    <Container>
                        <div className="mx-auto max-w-4xl text-center">
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="mb-4 inline-flex items-center gap-2 rounded-full border border-indigo-200/80 bg-indigo-50/90 px-4 py-1.5 text-xs font-semibold text-indigo-900 shadow-xs backdrop-blur-md"
                            >
                                <Compass className="h-3.5 w-3.5 text-indigo-600" />
                                <span>Pengembangan Bakat & Minat Siswa</span>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900"
                            >
                                Cabang{" "}
                                <span className="bg-gradient-to-r from-indigo-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                    Ekstrakurikuler
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 12 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ ...motionTheme.normal, delay: 0.1 }}
                                className="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600"
                            >
                                Wadah pembinaan karakter, kepemimpinan, olahraga, seni, sains, dan keagamaan
                                bagi seluruh peserta didik MTsN 3 Tulungagung.
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
                                    label: "Cabang Ekskul",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: Compass,
                                    color: "text-indigo-600",
                                    bg: "bg-indigo-50 border-indigo-100",
                                },
                                {
                                    label: "Total Siswa Aktif",
                                    value: statistics.students ?? 0,
                                    icon: Users,
                                    color: "text-emerald-600",
                                    bg: "bg-emerald-50 border-emerald-100",
                                },
                                {
                                    label: "Prestasi Ekskul",
                                    value: statistics.achievements ?? 0,
                                    icon: Trophy,
                                    color: "text-amber-500",
                                    bg: "bg-amber-50 border-amber-100",
                                },
                                {
                                    label: "Status Aktif",
                                    value:
                                        extracurriculars?.data?.filter(
                                            (item) => item.status?.toLowerCase() === "aktif",
                                        ).length || (extracurriculars?.data?.length ?? 0),
                                    icon: Sparkles,
                                    color: "text-teal-600",
                                    bg: "bg-teal-50 border-teal-100",
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
                                        placeholder="Cari ekstrakurikuler, pembina, atau kata kunci..."
                                        className="h-11 w-full rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-4 text-sm text-slate-800 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15"
                                    />
                                </div>

                                {/* Status */}
                                <div className="relative">
                                    <Sparkles className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <select
                                        value={status}
                                        onChange={(e) => {
                                            setStatus(e.target.value);
                                            applyFilter({ status: e.target.value });
                                        }}
                                        className="h-11 w-full appearance-none rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-10 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Tidak Aktif</option>
                                    </select>
                                    <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                </div>
                            </div>

                            {/* Active Filters Pill Bar */}
                            {hasActiveFilters && (
                                <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span className="text-slate-500 font-medium">
                                        Menampilkan hasil pencarian ekskul
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
                    EXTRACURRICULARS GRID SECTION
                ======================================================= */}
                <section className="pb-20">
                    <Container>
                        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {extracurriculars.data?.map((ekskul, idx) => (
                                <motion.div
                                    key={ekskul.id}
                                    initial={{ opacity: 0, y: 20 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, margin: "-40px" }}
                                    transition={{
                                        ...motionTheme.normal,
                                        delay: Math.min(idx * 0.04, 0.25),
                                    }}
                                >
                                    <GlassCard
                                        onClick={() => setSelectedExtracurricular(ekskul)}
                                        className="group relative cursor-pointer overflow-hidden border border-white/80 bg-white/70 backdrop-blur-xl rounded-3xl transition-all duration-400 hover:-translate-y-1.5 hover:border-emerald-200 hover:bg-white hover:shadow-[0_16px_36px_rgba(16,185,129,0.12)] flex flex-col justify-between"
                                    >
                                        <div>
                                            {/* Top Banner Frame */}
                                            <div className="relative aspect-video overflow-hidden bg-gradient-to-br from-indigo-50 via-teal-50 to-emerald-50 rounded-t-3xl flex items-center justify-center text-indigo-600">
                                                <Compass className="w-16 h-16 stroke-1 transition-transform duration-700 group-hover:scale-110 drop-shadow-sm" />

                                                {/* Status Pill */}
                                                <div className="absolute left-3.5 top-3.5">
                                                    <Badge
                                                        variant={
                                                            ekskul.status?.toLowerCase() === "aktif"
                                                                ? "success"
                                                                : "secondary"
                                                        }
                                                        className="border border-white/60 bg-white/90 shadow-xs backdrop-blur-md"
                                                    >
                                                        {ekskul.status || "Aktif"}
                                                    </Badge>
                                                </div>

                                                <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
                                            </div>

                                            {/* Card Content */}
                                            <div className="p-6">
                                                <h3 className="text-xl font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-700 leading-snug">
                                                    {ekskul.name}
                                                </h3>

                                                <p className="mt-2 line-clamp-2 text-xs text-slate-500 leading-relaxed">
                                                    {ekskul.description ||
                                                        "Pengembangan minat, bakat, dan karakter unggul siswa."}
                                                </p>

                                                {/* Stats Mini Grid */}
                                                <div className="mt-5 grid grid-cols-2 gap-2.5">
                                                    <div className="p-3 rounded-2xl bg-slate-50/90 border border-slate-100 text-center">
                                                        <span className="text-lg font-black text-slate-900 block">
                                                            {ekskul.members || 0}
                                                        </span>
                                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">
                                                            Anggota
                                                        </span>
                                                    </div>

                                                    <div className="p-3 rounded-2xl bg-slate-50/90 border border-slate-100 text-center">
                                                        <span className="text-lg font-black text-amber-600 block">
                                                            {ekskul.achievement_count || 0}
                                                        </span>
                                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">
                                                            Prestasi
                                                        </span>
                                                    </div>
                                                </div>

                                                {/* Pembina Info */}
                                                {ekskul.supervisors && ekskul.supervisors.length > 0 && (
                                                    <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                                        <span className="text-slate-400 font-medium">Pembina:</span>
                                                        <span className="font-bold text-slate-700 truncate max-w-[180px]">
                                                            {ekskul.supervisors[0]}
                                                        </span>
                                                    </div>
                                                )}
                                            </div>
                                        </div>

                                        <div className="px-6 pb-5 pt-0 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition-colors">
                                            <span>Lihat Detail Ekskul</span>
                                            <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                        </div>
                                    </GlassCard>
                                </motion.div>
                            ))}
                        </div>

                        {/* Empty State */}
                        {(!extracurriculars.data || extracurriculars.data.length === 0) && (
                            <GlassCard className="border border-white/80 bg-white/70 py-20 text-center backdrop-blur-xl rounded-3xl max-w-lg mx-auto">
                                <div className="h-16 w-16 rounded-3xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-4">
                                    <Compass className="w-8 h-8 stroke-1" />
                                </div>

                                <h3 className="text-xl font-black text-slate-900">
                                    Tidak Ditemukan Ekstrakurikuler
                                </h3>

                                <p className="mt-2 text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                                    Tidak ada cabang ekskul yang cocok dengan filter pencarian Anda.
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
                        {extracurriculars.last_page > 1 && (
                            <div className="mt-12 flex justify-center">
                                <div className="flex flex-wrap items-center gap-1.5 rounded-2xl border border-white/80 bg-white/70 p-2 shadow-xs backdrop-blur-xl">
                                    {extracurriculars.links?.map((link, index) => (
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
                MODAL DETAIL EKSTRAKURIKULER
            ======================================================= */}
            <AnimatePresence>
                {selectedExtracurricular && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-md"
                        onClick={() => setSelectedExtracurricular(null)}
                    >
                        <motion.div
                            initial={{ opacity: 0, y: 20, scale: 0.98 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, y: 20, scale: 0.98 }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative max-h-[92vh] w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl flex flex-col"
                        >
                            {/* Modal Header */}
                            <div className="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                                <div>
                                    <div className="flex items-center gap-2">
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                            {selectedExtracurricular.status || "Aktif"}
                                        </span>
                                        <span className="text-xs text-slate-400">&bull;</span>
                                        <span className="text-xs font-semibold text-slate-500">
                                            {selectedExtracurricular.members || 0} Siswa Terdaftar
                                        </span>
                                    </div>
                                    <h2 className="mt-1 text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                                        {selectedExtracurricular.name}
                                    </h2>
                                </div>

                                <button
                                    onClick={() => setSelectedExtracurricular(null)}
                                    className="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer"
                                    aria-label="Tutup modal ekskul"
                                >
                                    <X className="w-5 h-5" />
                                </button>
                            </div>

                            {/* Modal Body */}
                            <div className="grid gap-6 overflow-y-auto p-6 md:grid-cols-2">
                                {/* Left Frame Illustration */}
                                <div className="flex aspect-4/3 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 via-teal-50 to-emerald-50 text-indigo-600 border border-indigo-100/50">
                                    <Compass className="w-24 h-24 stroke-1 drop-shadow-md" />
                                </div>

                                {/* Right Information */}
                                <div className="space-y-4 text-xs sm:text-sm">
                                    {/* Pembina & Pelatih */}
                                    <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-3">
                                        <div>
                                            <span className="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-1">
                                                Pembina
                                            </span>
                                            <div className="flex flex-wrap gap-1.5">
                                                {selectedExtracurricular.supervisors?.length ? (
                                                    selectedExtracurricular.supervisors.map((name, i) => (
                                                        <span key={i} className="font-bold text-slate-800 bg-white px-2.5 py-1 rounded-xl border border-slate-200/80 shadow-xs">
                                                            {name}
                                                        </span>
                                                    ))
                                                ) : (
                                                    <span className="text-slate-400 italic">Belum ditentukan</span>
                                                )}
                                            </div>
                                        </div>

                                        <div>
                                            <span className="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-1">
                                                Pelatih
                                            </span>
                                            <div className="flex flex-wrap gap-1.5">
                                                {selectedExtracurricular.coaches?.length ? (
                                                    selectedExtracurricular.coaches.map((name, i) => (
                                                        <span key={i} className="font-bold text-slate-800 bg-white px-2.5 py-1 rounded-xl border border-slate-200/80 shadow-xs">
                                                            {name}
                                                        </span>
                                                    ))
                                                ) : (
                                                    <span className="text-slate-400 italic">Belum ditentukan</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Jadwal Latihan */}
                                    {selectedExtracurricular.schedule?.length > 0 && (
                                        <div className="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                            <span className="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-2">
                                                Jadwal Kegiatan / Latihan
                                            </span>
                                            <div className="flex flex-wrap gap-1.5">
                                                {selectedExtracurricular.schedule.map((day, i) => (
                                                    <span key={i} className="font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/80 px-3 py-1 rounded-full text-xs">
                                                        {day.charAt(0).toUpperCase() + day.slice(1)}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Deskripsi */}
                                    <div>
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                                            Deskripsi Ekstrakurikuler
                                        </span>
                                        <p className="text-slate-600 leading-relaxed text-xs sm:text-sm">
                                            {selectedExtracurricular.description || "Belum ada keterangan tambahan."}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Prestasi yang Pernah Diraih */}
                            {selectedExtracurricular.achievements && selectedExtracurricular.achievements.length > 0 && (
                                <div className="border-t border-slate-100 px-6 py-5 bg-slate-50/50">
                                    <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                                        <Trophy className="w-4 h-4 text-amber-500" />
                                        <span>Prestasi Terkini Cabang Ini</span>
                                    </h3>
                                    <div className="space-y-2 max-h-40 overflow-y-auto">
                                        {selectedExtracurricular.achievements.map((item, idx) => (
                                            <div key={idx} className="flex items-center justify-between p-3 rounded-2xl bg-white border border-slate-100 shadow-xs text-xs">
                                                <div className="truncate pr-2">
                                                    <p className="font-bold text-slate-900 truncate">{item.title}</p>
                                                    <p className="text-slate-500 text-[11px] truncate">{item.competition}</p>
                                                </div>
                                                <Badge variant="primary" className="shrink-0">{item.date}</Badge>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </FrontendLayout>
    );
}
