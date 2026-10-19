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
    Trophy,
    GraduationCap,
    Sparkles,
    Award,
    Search,
    ChevronDown,
    Calendar,
    Compass,
    RotateCcw,
    X,
    ArrowRight,
    MapPin,
} from "lucide-react";

export default function Prestasi() {
    const {
        settings,
        statistics = {},
        achievements = { data: [], links: [] },
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

    const hasActiveFilters = Boolean(search || level || year || extracurricular);

    const applyFilter = (customParams = {}) => {
        router.get(
            "/prestasi",
            {
                search,
                tingkat: level,
                tahun: year,
                ekstrakurikuler: extracurricular,
                ...customParams,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const resetFilters = () => {
        setSearch("");
        setLevel("");
        setYear("");
        setExtracurricular("");
        router.get(
            "/prestasi",
            {},
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
        return () => window.removeEventListener("keydown", handleKeyDown);
    }, [selectedAchievement]);

    useEffect(() => {
        document.body.style.overflow = selectedAchievement ? "hidden" : "";
        return () => {
            document.body.style.overflow = "";
        };
    }, [selectedAchievement]);

    return (
        <FrontendLayout>
            <Head title={`Prestasi - ${settings?.school_name ?? "SIGMA-3"}`} />

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
                        <div className="absolute -left-32 -top-24 h-96 w-96 rounded-full bg-amber-400/15 blur-[120px]" />
                        <div className="absolute -right-24 top-10 h-80 w-80 rounded-full bg-emerald-400/20 blur-[100px]" />
                    </div>

                    <Container>
                        <div className="mx-auto max-w-4xl text-center">
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="mb-4 inline-flex items-center gap-2 rounded-full border border-amber-200/80 bg-amber-50/90 px-4 py-1.5 text-xs font-semibold text-amber-900 shadow-xs backdrop-blur-md"
                            >
                                <Trophy className="h-3.5 w-3.5 text-amber-600" />
                                <span>Hall of Fame &bull; Etalase Prestasi Siswa</span>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900"
                            >
                                Portal{" "}
                                <span className="bg-gradient-to-r from-amber-500 via-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                    Prestasi Siswa
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 12 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ ...motionTheme.normal, delay: 0.1 }}
                                className="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600"
                            >
                                Rekam jejak kejuaraan dan capaian membanggakan siswa MTs Negeri 3 Tulungagung
                                di bidang akademik, riset sains, olahraga, dan seni.
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
                                    label: "Total Prestasi",
                                    value: statistics.achievements ?? 0,
                                    icon: Trophy,
                                    color: "text-amber-500",
                                    bg: "bg-amber-50 border-amber-100",
                                },
                                {
                                    label: "Siswa Juara",
                                    value: statistics.students ?? 0,
                                    icon: GraduationCap,
                                    color: "text-emerald-600",
                                    bg: "bg-emerald-50 border-emerald-100",
                                },
                                {
                                    label: "Cabang Ekskul",
                                    value: statistics.extracurriculars ?? 0,
                                    icon: Compass,
                                    color: "text-indigo-600",
                                    bg: "bg-indigo-50 border-indigo-100",
                                },
                                {
                                    label: "Prestasi Tahun Ini",
                                    value: statistics.this_year ?? 0,
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
                            <div className="grid gap-3 lg:grid-cols-[1fr_200px_200px_160px]">
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
                                        placeholder="Cari nama prestasi, kejuaraan, atau siswa..."
                                        className="h-11 w-full rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-4 text-sm text-slate-800 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15"
                                    />
                                </div>

                                {/* Tingkat */}
                                <div className="relative">
                                    <Award className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <select
                                        value={level}
                                        onChange={(e) => {
                                            setLevel(e.target.value);
                                            applyFilter({ tingkat: e.target.value });
                                        }}
                                        className="h-11 w-full appearance-none rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-10 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                    >
                                        <option value="">Semua Tingkat</option>
                                        {Object.entries(levels).map(([val, label]) => (
                                            <option key={val} value={val}>
                                                {label}
                                            </option>
                                        ))}
                                    </select>
                                    <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                </div>

                                {/* Ekstrakurikuler */}
                                <div className="relative">
                                    <Compass className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <select
                                        value={extracurricular}
                                        onChange={(e) => {
                                            setExtracurricular(e.target.value);
                                            applyFilter({ ekstrakurikuler: e.target.value });
                                        }}
                                        className="h-11 w-full appearance-none rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-10 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                    >
                                        <option value="">Semua Ekskul</option>
                                        {Object.entries(extracurriculars).map(([id, nama]) => (
                                            <option key={id} value={id}>
                                                {nama}
                                            </option>
                                        ))}
                                    </select>
                                    <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
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
                                        {years.map((t) => (
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
                                        Menampilkan hasil filter aktif
                                    </span>
                                    <button
                                        type="button"
                                        onClick={resetFilters}
                                        className="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors cursor-pointer"
                                    >
                                        <RotateCcw className="w-3.5 h-3.5" />
                                        <span>Reset Semua Filter</span>
                                    </button>
                                </div>
                            )}
                        </GlassCard>
                    </Container>
                </section>

                {/* =======================================================
                    ACHIEVEMENTS GRID SECTION
                ======================================================= */}
                <section className="pb-20">
                    <Container>
                        <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {achievements.data?.map((achievement, idx) => (
                                <motion.div
                                    key={achievement.id}
                                    initial={{ opacity: 0, y: 20 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, margin: "-40px" }}
                                    transition={{
                                        ...motionTheme.normal,
                                        delay: Math.min(idx * 0.04, 0.25),
                                    }}
                                >
                                    <GlassCard
                                        onClick={() => setSelectedAchievement(achievement)}
                                        className="group relative cursor-pointer overflow-hidden border border-white/80 bg-white/70 backdrop-blur-xl rounded-3xl transition-all duration-400 hover:-translate-y-1.5 hover:border-emerald-200 hover:bg-white hover:shadow-[0_16px_36px_rgba(16,185,129,0.12)] flex flex-col justify-between"
                                    >
                                        <div>
                                            {/* Photo / Trophy Frame */}
                                            <div className="relative aspect-video overflow-hidden bg-slate-100 rounded-t-3xl">
                                                {achievement.photo ? (
                                                    <img
                                                        src={achievement.photo}
                                                        alt={achievement.title}
                                                        loading="lazy"
                                                        decoding="async"
                                                        className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                    />
                                                ) : (
                                                    <div className="flex h-full items-center justify-center bg-gradient-to-br from-amber-50 via-emerald-50 to-teal-50 text-amber-500">
                                                        <Trophy className="w-16 h-16 stroke-1 drop-shadow-sm" />
                                                    </div>
                                                )}

                                                {/* Gradient Overlay for text contrast */}
                                                <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100" />

                                                {/* Level Pill */}
                                                <div className="absolute left-3.5 top-3.5">
                                                    <Badge
                                                        variant="primary"
                                                        className="border border-white/60 bg-white/90 text-emerald-800 shadow-xs backdrop-blur-md"
                                                    >
                                                        {achievement.level || "Tingkat Nasional"}
                                                    </Badge>
                                                </div>

                                                {/* Date Pill */}
                                                <span className="absolute right-3.5 top-3.5 rounded-full bg-slate-900/60 px-2.5 py-0.5 text-[11px] font-semibold text-white backdrop-blur-md">
                                                    {achievement.date}
                                                </span>
                                            </div>

                                            {/* Card Content */}
                                            <div className="p-6">
                                                <p className="text-xs font-bold uppercase tracking-wider text-emerald-700 line-clamp-1 mb-1">
                                                    {achievement.competition}
                                                </p>

                                                <h3 className="line-clamp-2 text-lg font-black text-slate-900 transition-colors duration-300 group-hover:text-emerald-700 leading-snug">
                                                    {achievement.title}
                                                </h3>

                                                {/* Participants */}
                                                <div className="mt-4 pt-3 border-t border-slate-100">
                                                    <p className="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                                        Penerima Penghargaan
                                                    </p>
                                                    <p className="mt-0.5 font-bold text-slate-800 text-sm line-clamp-1">
                                                        {achievement.participant_names || "Siswa MTsN 3"}
                                                    </p>
                                                    {achievement.participant_count > 2 && (
                                                        <p className="text-[11px] font-semibold text-emerald-600 mt-0.5">
                                                            + {achievement.participant_count - 2} siswa lainnya
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Card Footer action */}
                                        <div className="px-6 pb-5 pt-0 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition-colors">
                                            <span>Lihat Piagam &bull; Detail</span>
                                            <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                        </div>
                                    </GlassCard>
                                </motion.div>
                            ))}
                        </div>

                        {/* Empty State */}
                        {(!achievements.data || achievements.data.length === 0) && (
                            <GlassCard className="border border-white/80 bg-white/70 py-20 text-center backdrop-blur-xl rounded-3xl max-w-lg mx-auto">
                                <div className="h-16 w-16 rounded-3xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                                    <Trophy className="w-8 h-8 stroke-1" />
                                </div>

                                <h3 className="text-xl font-black text-slate-900">
                                    Tidak Ada Data Prestasi
                                </h3>

                                <p className="mt-2 text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                                    Tidak ditemukan kejuaraan yang sesuai dengan filter pencarian atau kategori yang Anda pilih.
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
                        {achievements.last_page > 1 && (
                            <div className="mt-12 flex justify-center">
                                <div className="flex flex-wrap items-center gap-1.5 rounded-2xl border border-white/80 bg-white/70 p-2 shadow-xs backdrop-blur-xl">
                                    {achievements.links?.map((link, index) => (
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
                            className="relative flex w-full max-w-md max-h-[90vh] flex-col overflow-hidden rounded-3xl bg-[#0A1F16] shadow-[0_20px_60px_-15px_rgba(201,162,75,0.2)]"
                            style={{
                                border: "1px solid rgba(201, 162, 75, 0.25)",
                                background:
                                    "radial-gradient(120% 100% at 50% 0%, #113826 0%, #06120D 100%)",
                            }}
                        >
                            {/* Tombol Close */}
                            <button
                                onClick={() => setSelectedAchievement(null)}
                                className="absolute right-4 top-4 z-50 flex h-8 w-8 items-center justify-center rounded-full bg-black/50 text-[#C9A24B] backdrop-blur-md transition hover:bg-[#C9A24B] hover:text-black cursor-pointer"
                                aria-label="Tutup modal"
                            >
                                <X className="w-4 h-4" />
                            </button>

                            {/* Konten Scrollable */}
                            <div className="relative z-10 flex flex-col overflow-y-auto">
                                {/* Header Minimalis */}
                                <div className="flex flex-col items-center pt-8 pb-3 text-center">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#1A4D38] to-[#0A1F16] border border-[#C9A24B]/40 shadow-lg">
                                        {settings?.logo ? (
                                            <img
                                                src={settings.logo}
                                                alt="Logo"
                                                className="h-3/4 w-3/4 object-contain"
                                            />
                                        ) : (
                                            <Trophy className="w-6 h-6 text-[#C9A24B]" />
                                        )}
                                    </div>
                                    <p className="mt-3 text-[10px] font-bold uppercase tracking-[0.25em] text-[#C9A24B]/90">
                                        Penghargaan Prestasi Siswa
                                    </p>
                                    <h3 className="mt-1 px-6 text-2xl font-serif italic text-[#F6F1E4]">
                                        Selamat &amp; Bangga
                                    </h3>
                                </div>

                                {/* Photo Frame */}
                                <div className="relative mx-6 mt-2 overflow-hidden rounded-2xl border border-[#C9A24B]/20 bg-[#06120D]">
                                    <div className="aspect-4/3 w-full">
                                        {selectedAchievement.photo ? (
                                            <img
                                                src={selectedAchievement.photo}
                                                alt="Foto siswa"
                                                className="h-full w-full object-cover"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center text-amber-400">
                                                <Trophy className="w-16 h-16 stroke-1 opacity-70" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="absolute right-0 top-0 rounded-bl-xl bg-[#C9A24B] px-3 py-1">
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-[#06120D]">
                                            {selectedAchievement.extracurricular ?? "Madrasah"}
                                        </span>
                                    </div>
                                </div>

                                {/* Detail Text */}
                                <div className="flex flex-col px-7 pb-8 pt-5 text-center">
                                    <h2 className="text-xl font-bold font-serif leading-snug text-[#F6F1E4]">
                                        {selectedAchievement.title}
                                    </h2>

                                    {/* Divider */}
                                    <div className="mx-auto my-4 flex w-36 items-center justify-center gap-2 opacity-60">
                                        <div className="h-px w-full bg-gradient-to-r from-transparent to-[#C9A24B]" />
                                        <div className="h-1.5 w-1.5 rotate-45 border border-[#C9A24B]" />
                                        <div className="h-px w-full bg-gradient-to-l from-transparent to-[#C9A24B]" />
                                    </div>

                                    {/* Nama Siswa */}
                                    <div className="mb-4">
                                        <p className="mb-1 text-[10px] font-medium uppercase tracking-[0.2em] text-[#C9A24B]/80">
                                            Diberikan Kepada
                                        </p>
                                        <div className="flex flex-col gap-1">
                                            {selectedAchievement.participants?.map((student) => (
                                                <p
                                                    key={student.id}
                                                    className="text-base font-bold text-[#F6F1E4] tracking-wide"
                                                >
                                                    {student.nama}
                                                </p>
                                            ))}
                                            {(!selectedAchievement.participants || selectedAchievement.participants.length === 0) && (
                                                <p className="text-base font-bold text-[#F6F1E4]">
                                                    {selectedAchievement.participant_names || "Siswa MTsN 3 Tulungagung"}
                                                </p>
                                            )}
                                        </div>
                                    </div>

                                    {/* Event Badge */}
                                    <div className="rounded-xl border border-[#C9A24B]/20 bg-[#113826]/40 p-3">
                                        <p className="text-xs font-bold uppercase tracking-wider text-[#C9A24B]">
                                            {selectedAchievement.competition}
                                        </p>
                                        <div className="mt-1 flex items-center justify-center gap-2 text-[11px] font-semibold uppercase tracking-wider text-[#F6F1E4]/80">
                                            <span>Tingkat {selectedAchievement.level}</span>
                                            <span>&bull;</span>
                                            <span>{selectedAchievement.date}</span>
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
