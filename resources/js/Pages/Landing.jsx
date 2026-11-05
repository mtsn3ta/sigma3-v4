import { motion, AnimatePresence } from "motion/react";
import { useEffect, useState } from "react";
import { usePage, Link, Head, router } from "@inertiajs/react";
import FrontendLayout from "@/Layouts/FrontendLayout";
import Container from "@/Components/UI/Container";
import {
    cn,
    background,
    motion as motionTheme,
    spacing,
} from "@/Theme";
import Badge from "@/Components/UI/Badge";
import Button from "@/Components/UI/Button";
import GlassCard from "@/Components/UI/GlassCard";
import {
    Sparkles,
    Trophy,
    Users,
    Calendar,
    ArrowRight,
    Activity,
    CreditCard,
    Compass,
    HeartPulse,
    BookOpen,
    Image as ImageIcon,
    Bell,
    Radio,
    GraduationCap,
    Trees,
    HeartHandshake,
    Search,
    ExternalLink,
    ChevronRight,
    CheckCircle2,
    ShieldCheck,
    MessageCircle,
    ArrowUpRight,
} from "lucide-react";

// Stagger variants for right content
const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.12 } },
};

const itemVariants = {
    hidden: { opacity: 0, y: 16 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.4 } },
};

export default function Landing() {
    const {
        settings = {},
        statistics = {},
        latestAchievements = [],
        featuredExtracurriculars = [],
        latestGallery = [],
        attendanceSummary = {},
    } = usePage().props;

    const [achievementIndex, setAchievementIndex] = useState(0);
    const [nisnQuery, setNisnQuery] = useState("");
    const [nisnError, setNisnError] = useState("");

    // Auto rotate achievements
    useEffect(() => {
        if (!latestAchievements || latestAchievements.length <= 1) return;

        const interval = setInterval(() => {
            setAchievementIndex((current) =>
                current === latestAchievements.length - 1 ? 0 : current + 1,
            );
        }, 5000);

        return () => clearInterval(interval);
    }, [latestAchievements]);

    const featuredAchievement = latestAchievements?.[achievementIndex] ?? null;

    const handleNisnSearch = (e) => {
        e.preventDefault();
        const clean = nisnQuery.trim();
        if (!clean) {
            setNisnError("Silakan masukkan NISN siswa");
            return;
        }
        if (!/^\d+$/.test(clean)) {
            setNisnError("NISN hanya boleh berupa angka");
            return;
        }
        setNisnError("");
        router.visit(`/wali_murid/${clean}`);
    };

    return (
        <FrontendLayout>
            <Head>
                <title>{`SIGMA-3 - ${settings.school_name || "MTsN 3 Tulungagung"}`}</title>
                <meta name="description" content="SIGMA-3: Sistem Informasi Digital Terpadu MTsN 3 Tulungagung. Presensi RFID pintar, monitoring ibadah, portal wali murid, rekap nilai, dan layanan asisten AI resmi madrasah." />
                <link rel="canonical" href="https://sigma3.mtsn3tulungagung.sch.id/" />
                <meta property="og:title" content={`SIGMA-3 - ${settings.school_name || "MTsN 3 Tulungagung"}`} />
                <meta property="og:description" content="Sistem Informasi Digital Terpadu MTsN 3 Tulungagung: Presensi RFID, portal wali murid & layanan madrasah." />
                <meta property="og:url" content="https://sigma3.mtsn3tulungagung.sch.id/" />
                <meta property="og:type" content="website" />
                {settings.logo && <meta property="og:image" content={settings.logo} />}
            </Head>

            {/* ==========================================================
                HERO SECTION
            ========================================================== */}
            <section
                id="hero"
                className={cn(
                    background.hero,
                    "relative flex flex-col justify-center pb-16 md:min-h-[92vh] md:pt-32 md:pb-20 overflow-hidden",
                )}
            >
                {/* Background Elements */}
                <div className={background.aurora}>
                    <div className={background.glowTop} />
                    <div className={background.glowLeft} />
                    <div className={background.glowRight} />
                </div>
                <div className={background.noise} />
                <div className={background.grid} />

                <Container className={cn("relative z-10", spacing.heroTop)}>
                    <div className="grid lg:grid-cols-12 gap-12 lg:gap-14 items-center">
                        {/* LEFT CONTENT */}
                        <motion.div
                            initial={{ opacity: 0, x: -24 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={motionTheme.normal}
                            className="lg:col-span-7"
                        >
                            {/* Futuristic Badge */}
                            <div className="inline-flex items-center gap-2.5 rounded-full border border-emerald-200/90 bg-emerald-50/90 px-4 py-1.5 text-xs font-semibold text-emerald-800 shadow-xs backdrop-blur-md mb-6">
                                <span className="relative flex h-2 w-2">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <Sparkles className="h-3.5 w-3.5 text-emerald-600" />
                                <span>Smart Madrasah Ecosystem</span>
                                <span className="text-emerald-300">•</span>
                                <span className="text-emerald-600 font-medium">IoT & AI Ready</span>
                            </div>

                            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.12] tracking-tight text-slate-900">
                                Inovasi Cerdas Madrasah, <br />
                                <span className="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 bg-clip-text text-transparent">
                                    Mencetak Generasi Emas
                                </span>
                            </h1>

                            <p className="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-xl">
                                Platform digital terpadu <strong className="font-semibold text-slate-800">SIGMA-3</strong> mengintegrasikan
                                presensi RFID realtime, pemantauan ibadah, rekam jejak prestasi, bimbingan konseling,
                                dan portal wali murid dalam satu ekosistem modern & transparan.
                            </p>

                            {/* Dual CTAs & Quick Action */}
                            <div className="mt-8 flex flex-wrap items-center gap-4">
                                <Button
                                    href="/monitoring"
                                    variant="primary"
                                    className="px-7 py-3.5 text-sm sm:text-base font-bold shadow-lg shadow-emerald-600/25 flex items-center gap-2 group cursor-pointer"
                                >
                                    <span>Pantau Presensi Live</span>
                                    <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                </Button>
                                <Button
                                    href="/login"
                                    variant="secondary"
                                    className="px-7 py-3.5 text-sm sm:text-base font-bold cursor-pointer"
                                >
                                    Masuk ke Portal
                                </Button>
                                <a
                                    href="#fitur-modul"
                                    className="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-700 hover:text-emerald-800 transition-colors py-2 px-1 cursor-pointer"
                                >
                                    Jelajahi Fitur
                                    <ChevronRight className="w-4 h-4" />
                                </a>
                            </div>

                            {/* Trust Highlights */}
                            <div className="mt-10 pt-6 border-t border-slate-200/60 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div className="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                    <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
                                    <span>Presensi RFID Realtime</span>
                                </div>
                                <div className="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                    <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
                                    <span>Portal Wali Murid</span>
                                </div>
                                <div className="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                    <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
                                    <span>Layanan CS AI WhatsApp</span>
                                </div>
                            </div>
                        </motion.div>

                        {/* RIGHT CONTENT (Interactive Status & Stats Card) */}
                        <motion.div
                            variants={containerVariants}
                            initial="hidden"
                            animate="visible"
                            className="lg:col-span-5"
                        >
                            <GlassCard className="p-6 md:p-8 relative border-white bg-white/70 shadow-[0_12px_40px_rgb(0,0,0,0.06)] backdrop-blur-2xl rounded-3xl">
                                <div className="mb-6 flex items-center justify-between pb-4 border-b border-slate-100">
                                    <div>
                                        <p className="text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                                            Status Ekosistem
                                        </p>
                                        <h2 className="text-xl font-black text-slate-900 mt-0.5">
                                            SIGMA-3 Live Stats
                                        </h2>
                                    </div>
                                    <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                        <span className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" />
                                        Sinkron Realtime
                                    </span>
                                </div>

                                {/* 2x2 Grid of Key Metrics */}
                                <div className="grid grid-cols-2 gap-3.5">
                                    {[
                                        {
                                            label: "Siswa Aktif",
                                            value: statistics.students ?? "—",
                                            icon: Users,
                                            color: "text-emerald-600",
                                            bg: "bg-emerald-50 border-emerald-100",
                                        },
                                        {
                                            label: "Prestasi Juara",
                                            value: statistics.achievements ?? "—",
                                            icon: Trophy,
                                            color: "text-amber-500",
                                            bg: "bg-amber-50 border-amber-100",
                                        },
                                        {
                                            label: "Ekstrakurikuler",
                                            value: statistics.extracurriculars ?? "—",
                                            icon: Compass,
                                            color: "text-sky-600",
                                            bg: "bg-sky-50 border-sky-100",
                                        },
                                        {
                                            label: "Tahun Ajaran",
                                            value: settings.academic_year ?? "Aktif",
                                            icon: Calendar,
                                            color: "text-indigo-600",
                                            bg: "bg-indigo-50 border-indigo-100",
                                        },
                                    ].map((stat, i) => {
                                        const IconComponent = stat.icon;
                                        return (
                                            <motion.div
                                                key={i}
                                                variants={itemVariants}
                                                className="min-w-0"
                                            >
                                                <div className="p-4 rounded-2xl bg-white/70 border border-white shadow-xs hover:bg-white hover:shadow-md transition-all duration-300 min-w-0 group">
                                                    <div className="flex items-center justify-between mb-2">
                                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                            {stat.label}
                                                        </span>
                                                        <div className={cn("p-1.5 rounded-xl border", stat.bg)}>
                                                            <IconComponent className={cn("w-4 h-4", stat.color)} />
                                                        </div>
                                                    </div>
                                                    <h3 className="text-xl sm:text-2xl font-black text-slate-900 tracking-tight truncate group-hover:text-emerald-700 transition-colors">
                                                        {stat.value}
                                                    </h3>
                                                </div>
                                            </motion.div>
                                        );
                                    })}
                                </div>

                                {/* Mini Attendance Live Widget in Hero Card */}
                                <div className="mt-5 pt-4 border-t border-slate-100 bg-slate-50/70 -mx-2 -mb-2 p-4 rounded-2xl">
                                    <div className="flex items-center justify-between text-xs font-semibold mb-2">
                                        <div className="flex items-center gap-2 text-slate-700">
                                            <span className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
                                            <span>Kehadiran Hari Ini</span>
                                        </div>
                                        <span className="font-black text-emerald-700 text-sm">
                                            {attendanceSummary.persentase ?? 0}%
                                        </span>
                                    </div>

                                    {/* Progress Bar */}
                                    <div className="h-2 w-full rounded-full bg-slate-200 overflow-hidden">
                                        <div
                                            className="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-1000 shadow-xs"
                                            style={{
                                                width: `${Math.min(100, Math.max(0, attendanceSummary.persentase || 0))}%`,
                                            }}
                                        />
                                    </div>

                                    <div className="mt-3 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>{attendanceSummary.today || "Hari Ini"}</span>
                                        <Link
                                            href="/monitoring"
                                            className="font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group"
                                        >
                                            Pantau Selengkapnya
                                            <ArrowRight className="w-3 h-3 transition-transform group-hover:translate-x-0.5" />
                                        </Link>
                                    </div>
                                </div>
                            </GlassCard>
                        </motion.div>
                    </div>
                </Container>
            </section>

            {/* ==========================================================
                PORTAL WALI MURID (QUICK NISN CHECK)
            ========================================================== */}
            <section className="relative z-10 -mt-6">
                <Container>
                    <div className="rounded-3xl border border-emerald-100 bg-white/90 p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                        <div className="grid lg:grid-cols-12 gap-6 items-center">
                            <div className="lg:col-span-5 flex items-start gap-4">
                                <div className="h-12 w-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-xs">
                                    <ShieldCheck className="w-6 h-6" />
                                </div>
                                <div>
                                    <span className="inline-block text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full mb-1">
                                        Layanan Orang Tua
                                    </span>
                                    <h3 className="text-xl font-black text-slate-900">
                                        Portal Cepat Wali Murid
                                    </h3>
                                    <p className="text-xs sm:text-sm text-slate-600 mt-1">
                                        Pantau presensi, keterlambatan, dan pengajuan izin putra/putri Anda secara transparan.
                                    </p>
                                </div>
                            </div>

                            <div className="lg:col-span-7">
                                <form onSubmit={handleNisnSearch} className="flex flex-col sm:flex-row gap-3">
                                    <div className="relative flex-1">
                                        <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                        <input
                                            type="text"
                                            placeholder="Masukkan NISN Siswa (contoh: 0081234567)"
                                            value={nisnQuery}
                                            onChange={(e) => {
                                                setNisnQuery(e.target.value);
                                                if (nisnError) setNisnError("");
                                            }}
                                            className={cn(
                                                "w-full rounded-2xl border bg-slate-50/70 pl-11 pr-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all",
                                                nisnError ? "border-rose-300 bg-rose-50/50" : "border-slate-200",
                                            )}
                                        />
                                        {nisnError && (
                                            <p className="absolute -bottom-5 left-2 text-[11px] font-medium text-rose-600">
                                                {nisnError}
                                            </p>
                                        )}
                                    </div>
                                    <button
                                        type="submit"
                                        className="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 shadow-md shadow-emerald-600/20 active:scale-95 transition-all shrink-0 cursor-pointer"
                                    >
                                        <span>Periksa Presensi</span>
                                        <ArrowRight className="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </Container>
            </section>

            {/* ==========================================================
                MODUL SISTEM (FITUR UTAMA)
            ========================================================== */}
            <section
                id="fitur-modul"
                className={cn("relative z-10", spacing.sectionY)}
            >
                <Container>
                    <div className="mb-14 text-center max-w-3xl mx-auto">
                        <Badge variant="primary" className="mb-4">
                            Modul Terintegrasi
                        </Badge>

                        <h2 className="text-3xl font-black tracking-tight text-slate-900 md:text-5xl">
                            Ekosistem Digital{" "}
                            <span className="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                SIGMA-3
                            </span>
                        </h2>

                        <p className="mt-4 text-base sm:text-lg leading-relaxed text-slate-600">
                            Semua layanan madrasah terintegrasi dalam satu sistem,
                            mulai dari kehadiran RFID, pemantauan ibadah, bimbingan konseling,
                            ekstrakurikuler, hingga publikasi prestasi.
                        </p>
                    </div>

                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {[
                            {
                                icon: CreditCard,
                                title: "Absensi Gerbang RFID",
                                desc: "Presensi kedatangan & kepulangan siswa di gerbang madrasah secara otomatis via kartu RFID/NFC.",
                                href: "/absensi",
                                tag: "IoT Realtime",
                                iconColor: "text-emerald-600",
                                iconBg: "bg-emerald-50 border-emerald-100",
                            },
                            {
                                icon: HeartHandshake,
                                title: "Absensi Ibadah Digital",
                                desc: "Pemantauan kepatuhan Shalat Dhuha & Shalat Dzuhur berjamaah siswa setiap hari secara terdata.",
                                href: "/absensi/ibadah",
                                tag: "Karakter Islami",
                                iconColor: "text-pink-600",
                                iconBg: "bg-pink-50 border-pink-100",
                            },
                            {
                                icon: Trees,
                                title: "Absensi Ekstrakurikuler",
                                desc: "Pencatatan kehadiran anggota ekskul saat sesi latihan berlangsung via RFID & NFC madrasah.",
                                href: "/absensi/ekskul",
                                tag: "Minat & Bakat",
                                iconColor: "text-lime-600",
                                iconBg: "bg-lime-50 border-lime-100",
                            },
                            {
                                icon: Activity,
                                title: "Monitoring Presensi",
                                desc: "Pantau rekap kehadiran harian siswa, status sakit, izin, dan persentase kehadiran live.",
                                href: "/monitoring",
                                tag: "Live Update",
                                iconColor: "text-sky-600",
                                iconBg: "bg-sky-50 border-sky-100",
                            },
                            {
                                icon: Compass,
                                title: "Ekstrakurikuler",
                                desc: "Manajemen anggota, jadwal kegiatan, proposal event, dan laporan pertanggungjawaban.",
                                href: "/ekstrakurikuler",
                                tag: "Minat & Bakat",
                                iconColor: "text-indigo-600",
                                iconBg: "bg-indigo-50 border-indigo-100",
                            },
                            {
                                icon: HeartPulse,
                                title: "BK Digital & Konseling",
                                desc: "Konseling privat, pencatatan permasalahan, kedisiplinan, dan rekam pembinaan karakter.",
                                href: "/login",
                                tag: "Bimbingan Siswa",
                                iconColor: "text-rose-600",
                                iconBg: "bg-rose-50 border-rose-100",
                            },
                            {
                                icon: BookOpen,
                                title: "Jurnal Mengajar Guru",
                                desc: "Presensi tatap muka kelas, agenda pembelajaran harian guru, dan rekap jurnal digital.",
                                href: "/login",
                                tag: "Akademik",
                                iconColor: "text-violet-600",
                                iconBg: "bg-violet-50 border-violet-100",
                            },
                            {
                                icon: Trophy,
                                title: "Portal Prestasi",
                                desc: "Publikasi medali kejuaraan siswa di bidang sains, riset madrasah, olahraga, dan seni.",
                                href: "/prestasi",
                                tag: "Hall of Fame",
                                iconColor: "text-amber-600",
                                iconBg: "bg-amber-50 border-amber-100",
                            },
                            {
                                icon: ImageIcon,
                                title: "Galeri Dokumentasi",
                                desc: "Arsip dokumentasi foto dan video kegiatan madrasah, upacara, dan perayaan penting.",
                                href: "/galeri",
                                tag: "Dokumentasi",
                                iconColor: "text-teal-600",
                                iconBg: "bg-teal-50 border-teal-100",
                            },
                            {
                                icon: Bell,
                                title: "Pengumuman Resmi",
                                desc: "Distribusi edaran resmi madrasah, agenda akademik, serta urgent alert ke seluruh civitas.",
                                href: "#cta",
                                tag: "Informasi",
                                iconColor: "text-orange-600",
                                iconBg: "bg-orange-50 border-orange-100",
                            },
                        ].map((fitur, index) => {
                            const IconComponent = fitur.icon;
                            return (
                                <Link
                                    key={index}
                                    href={fitur.href}
                                    className="group block outline-none"
                                >
                                    <GlassCard
                                        className="h-full border-white bg-white/70 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 hover:-translate-y-1.5 hover:border-emerald-200 hover:bg-white hover:shadow-[0_12px_30px_rgb(0,0,0,0.08)] flex flex-col justify-between rounded-3xl"
                                    >
                                        <div>
                                            <div className="flex items-center justify-between mb-5">
                                                <div className={cn(
                                                    "h-12 w-12 rounded-2xl flex items-center justify-center border shadow-xs transition-transform duration-300 group-hover:scale-110",
                                                    fitur.iconBg,
                                                )}>
                                                    <IconComponent className={cn("w-6 h-6", fitur.iconColor)} />
                                                </div>
                                                <span className="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                                    {fitur.tag}
                                                </span>
                                            </div>

                                            <h3 className="mb-2 text-lg font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                                                {fitur.title}
                                            </h3>

                                            <p className="text-sm leading-relaxed text-slate-600">
                                                {fitur.desc}
                                            </p>
                                        </div>

                                        <div className="mt-5 pt-3 border-t border-slate-100/80 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition-colors">
                                            <span>Buka Layanan</span>
                                            <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                                        </div>
                                    </GlassCard>
                                </Link>
                            );
                        })}
                    </div>
                </Container>
            </section>

            {/* ==========================================================
                BENTO GRID: LAYANAN UNGGULAN
            ========================================================== */}
            <section
                id="features"
                className={cn("relative z-10", spacing.sectionY)}
            >
                <Container>
                    {/* Header Section */}
                    <div className="mb-14 text-center max-w-3xl mx-auto">
                        <Badge variant="primary" className="mb-4">
                            Layanan Unggulan
                        </Badge>
                        <h2 className="text-3xl md:text-5xl font-black tracking-tight text-slate-900">
                            Satu Platform. <br />
                            <span className="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                Beragam Solusi Digital.
                            </span>
                        </h2>
                        <p className="mt-4 text-base sm:text-lg text-slate-600">
                            Akses cepat ke modul-modul utama yang paling sering digunakan oleh guru, siswa, dan orang tua.
                        </p>
                    </div>

                    {/* Bento Grid Container */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 auto-rows-auto">
                        {/* 1. MONITORING ABSENSI (Lebar 2 Kolom) */}
                        <Link
                            href="/monitoring"
                            className="group block lg:col-span-2 outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/70 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
                                <div className="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl transition-transform duration-500 group-hover:scale-150 pointer-events-none" />

                                <div className="relative z-10">
                                    <div className="flex items-center justify-between mb-4">
                                        <div className="flex items-center gap-2.5">
                                            <span className="relative flex h-2.5 w-2.5">
                                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                                            </span>
                                            <p className="text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                                                IoT Scanner Live
                                            </p>
                                        </div>
                                        <span className="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                            {attendanceSummary.today || "Hari Ini"}
                                        </span>
                                    </div>

                                    <h3 className="text-2xl lg:text-3xl font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                                        Monitoring Presensi RFID
                                    </h3>
                                    <p className="mt-1 text-sm text-slate-600">
                                        Data kehadiran harian terintegrasi mesin tap RFID secara langsung tanpa rekap manual.
                                    </p>
                                </div>

                                {/* Status Grid Chips */}
                                <div className="relative z-10 mt-6 grid grid-cols-2 sm:grid-cols-5 gap-3">
                                    <div className="p-3 rounded-2xl bg-emerald-50/80 border border-emerald-100 text-center">
                                        <span className="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block">
                                            Hadir
                                        </span>
                                        <span className="text-xl sm:text-2xl font-black text-emerald-800 mt-1 block">
                                            {attendanceSummary.hadir ?? 0}
                                        </span>
                                    </div>
                                    <div className="p-3 rounded-2xl bg-amber-50/80 border border-amber-100 text-center">
                                        <span className="text-[11px] font-bold uppercase tracking-wider text-amber-700 block">
                                            Terlambat
                                        </span>
                                        <span className="text-xl sm:text-2xl font-black text-amber-800 mt-1 block">
                                            {attendanceSummary.terlambat ?? 0}
                                        </span>
                                    </div>
                                    <div className="p-3 rounded-2xl bg-sky-50/80 border border-sky-100 text-center">
                                        <span className="text-[11px] font-bold uppercase tracking-wider text-sky-700 block">
                                            Sakit
                                        </span>
                                        <span className="text-xl sm:text-2xl font-black text-sky-800 mt-1 block">
                                            {attendanceSummary.sakit ?? 0}
                                        </span>
                                    </div>
                                    <div className="p-3 rounded-2xl bg-orange-50/80 border border-orange-100 text-center">
                                        <span className="text-[11px] font-bold uppercase tracking-wider text-orange-700 block">
                                            Izin
                                        </span>
                                        <span className="text-xl sm:text-2xl font-black text-orange-800 mt-1 block">
                                            {attendanceSummary.izin ?? 0}
                                        </span>
                                    </div>
                                    <div className="p-3 rounded-2xl bg-rose-50/80 border border-rose-100 text-center col-span-2 sm:col-span-1">
                                        <span className="text-[11px] font-bold uppercase tracking-wider text-rose-700 block">
                                            Alfa
                                        </span>
                                        <span className="text-xl sm:text-2xl font-black text-rose-800 mt-1 block">
                                            {attendanceSummary.alfa ?? 0}
                                        </span>
                                    </div>
                                </div>

                                {/* Progress Bar & Footer */}
                                <div className="relative z-10 mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div className="flex-1 max-w-sm">
                                        <div className="flex items-baseline justify-between mb-1.5">
                                            <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                                Tingkat Kehadiran
                                            </span>
                                            <span className="text-2xl font-black text-emerald-700">
                                                {attendanceSummary.persentase ?? 0}%
                                            </span>
                                        </div>
                                        <div className="h-2.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                            <div
                                                className="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full"
                                                style={{
                                                    width: `${Math.min(100, Math.max(0, attendanceSummary.persentase || 0))}%`,
                                                }}
                                            />
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-2 text-sm font-bold text-emerald-700 group-hover:text-emerald-800">
                                        <span>Buka Dashboard Live</span>
                                        <div className="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 transition-all duration-300 group-hover:translate-x-1 group-hover:bg-emerald-600 group-hover:text-white">
                                            <ArrowRight className="h-4 w-4" />
                                        </div>
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 2. PRESTASI (Tinggi 2 Baris / Side Card) */}
                        <Link
                            href="/prestasi"
                            className="group block lg:row-span-2 outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-gradient-to-b from-white/80 via-white/70 to-emerald-50/60 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] rounded-3xl">
                                <div className="absolute inset-x-0 top-0 h-1/2 bg-[radial-gradient(ellipse_at_top,rgba(16,185,129,0.12),transparent_70%)] pointer-events-none" />

                                <div className="relative z-10">
                                    <div className="flex items-center justify-between mb-4">
                                        <span className="text-[10px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 border border-amber-200/80 px-2.5 py-1 rounded-full">
                                            ★ Hall of Fame
                                        </span>
                                        <span className="text-xs font-bold text-slate-500">
                                            {statistics.achievements || 0} Terdata
                                        </span>
                                    </div>
                                    <h3 className="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">
                                        Portal Prestasi
                                    </h3>
                                    <p className="mt-1 text-xs sm:text-sm text-slate-600">
                                        Apresiasi bagi para siswa berprestasi di kancah daerah hingga nasional.
                                    </p>
                                </div>

                                <div className="relative z-10 my-6 flex-1 flex flex-col justify-center">
                                    {featuredAchievement ? (
                                        <div className="rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-sm group-hover:shadow-md transition-shadow">
                                            {/* Photo container */}
                                            <div className="relative overflow-hidden rounded-xl bg-slate-100 h-44 w-full">
                                                {featuredAchievement.photo ? (
                                                    <img
                                                        src={featuredAchievement.photo}
                                                        alt={featuredAchievement.title}
                                                        className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                    />
                                                ) : (
                                                    <div className="flex h-full items-center justify-center bg-gradient-to-br from-emerald-50 to-teal-50 text-emerald-600">
                                                        <Trophy className="w-16 h-16 stroke-1" />
                                                    </div>
                                                )}
                                                <div className="absolute top-2 left-2">
                                                    <span className="inline-flex items-center rounded-full bg-slate-900/80 text-white px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider backdrop-blur-md">
                                                        {featuredAchievement.level || "Tingkat Nasional"}
                                                    </span>
                                                </div>
                                            </div>

                                            <div className="mt-3.5">
                                                <div className="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                                                    <span className="font-semibold text-emerald-700">
                                                        {featuredAchievement.competition}
                                                    </span>
                                                    <span>{featuredAchievement.date}</span>
                                                </div>
                                                <h4 className="text-base font-bold text-slate-900 line-clamp-2 leading-snug">
                                                    {featuredAchievement.title}
                                                </h4>
                                            </div>

                                            {/* Carousel indicator dots */}
                                            {latestAchievements && latestAchievements.length > 1 && (
                                                <div className="mt-4 flex justify-center gap-1.5">
                                                    {latestAchievements.map((_, index) => (
                                                        <div
                                                            key={index}
                                                            className={cn(
                                                                "h-1.5 rounded-full transition-all duration-300",
                                                                achievementIndex === index
                                                                    ? "w-5 bg-emerald-600"
                                                                    : "w-1.5 bg-slate-200",
                                                            )}
                                                        />
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    ) : (
                                        <div className="text-center py-10 text-slate-400">
                                            <Trophy className="w-12 h-12 mx-auto mb-2 text-slate-300" />
                                            <p className="text-sm">Belum ada data prestasi terbaru</p>
                                        </div>
                                    )}
                                </div>

                                <div className="relative z-10 pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">
                                            Total Kejuaraan
                                        </span>
                                        <span className="text-2xl font-black text-slate-900">
                                            {statistics.achievements || 0}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200/60 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                        <span>Lihat Semua</span>
                                        <ArrowRight className="w-3.5 h-3.5" />
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 3. EKSTRAKURIKULER */}
                        <Link
                            href="/ekstrakurikuler"
                            className="group block outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/70 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
                                <div>
                                    <div className="flex items-center justify-between mb-3">
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full">
                                            Bakat & Karakter
                                        </span>
                                        <Compass className="w-5 h-5 text-indigo-600" />
                                    </div>
                                    <h3 className="text-xl lg:text-2xl font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                                        Ekstrakurikuler
                                    </h3>
                                    <div className="mt-2 flex items-baseline gap-2">
                                        <span className="text-3xl font-black text-slate-900">
                                            {statistics.extracurriculars || 0}
                                        </span>
                                        <span className="text-xs font-semibold uppercase tracking-wider text-emerald-700">
                                            Cabang Pilihan
                                        </span>
                                    </div>

                                    {/* Sample list of extracurriculars */}
                                    <div className="mt-4 space-y-2">
                                        {featuredExtracurriculars?.slice(0, 2).map((item) => (
                                            <div
                                                key={item.id}
                                                className="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/80 px-3.5 py-2 text-xs font-bold text-slate-700"
                                            >
                                                <div className="flex items-center gap-2">
                                                    <span className="h-2 w-2 rounded-full bg-emerald-500" />
                                                    <span className="line-clamp-1">{item.nama}</span>
                                                </div>
                                                <span className="text-[10px] text-slate-400 font-normal">Aktif</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-emerald-700 transition-colors">
                                    <span>Jelajahi Semua Ekskul</span>
                                    <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 4. GALERI DOKUMENTASI */}
                        <Link
                            href="/galeri"
                            className="group block outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/70 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
                                <div>
                                    <div className="flex items-center justify-between mb-3">
                                        <span className="text-[10px] font-bold uppercase tracking-wider text-teal-700 bg-teal-50 border border-teal-100 px-2.5 py-1 rounded-full">
                                            Dokumentasi
                                        </span>
                                        <ImageIcon className="w-5 h-5 text-teal-600" />
                                    </div>
                                    <h3 className="text-xl lg:text-2xl font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                                        Galeri Madrasah
                                    </h3>
                                    <p className="mt-1 text-xs text-slate-600">
                                        Rekam jejak visual kegiatan, peringatan hari besar, dan karya siswa.
                                    </p>

                                    {/* Preview Photos */}
                                    <div className="mt-4 flex items-center gap-3">
                                        {latestGallery?.slice(0, 2).map((item, idx) => (
                                            <div
                                                key={item.id || idx}
                                                className="h-20 flex-1 overflow-hidden rounded-xl border border-white shadow-xs bg-slate-100"
                                            >
                                                {item.cover ? (
                                                    <img
                                                        src={item.cover}
                                                        alt={item.title || "Galeri"}
                                                        className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                    />
                                                ) : (
                                                    <div className="flex h-full items-center justify-center bg-slate-100 text-slate-400">
                                                        <ImageIcon className="w-6 h-6 stroke-1" />
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-emerald-700 transition-colors">
                                    <span>Buka Galeri Foto</span>
                                    <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                </div>
                            </GlassCard>
                        </Link>
                    </div>
                </Container>
            </section>

            {/* ==========================================================
                KEUNGGULAN MADRASAH
            ========================================================== */}
            <section
                id="keunggulan"
                className={cn("relative z-10 bg-slate-100/50", spacing.sectionY)}
            >
                <Container>
                    {/* Header Section */}
                    <div className="mb-14 flex flex-col items-center text-center max-w-3xl mx-auto">
                        <Badge variant="primary" className="mb-4">
                            Keunggulan Madrasah
                        </Badge>

                        <h2 className="text-3xl md:text-5xl font-black tracking-tight text-slate-900 mb-6">
                            Mengapa Memilih <br />
                            <span className="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                {settings.school_name || "MTsN 3 Tulungagung"}?
                            </span>
                        </h2>

                        {/* Vision Quote Card */}
                        <div className="w-full rounded-2xl border border-emerald-200/80 bg-white/80 p-6 md:p-8 shadow-xs backdrop-blur-md relative overflow-hidden">
                            <div className="absolute top-0 left-0 h-full w-1.5 bg-emerald-500" />
                            <p className="text-lg md:text-xl font-medium text-slate-800 italic leading-relaxed">
                                "Terwujudnya generasi pionir yang religius, berbudaya, terampil dan kompetitif serta peduli lingkungan."
                            </p>
                            <p className="mt-3 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                — Visi Resmi MTsN 3 Tulungagung
                            </p>
                        </div>
                    </div>

                    {/* 3x2 Grid with Crisp Lucide Icons */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {[
                            {
                                icon: Radio,
                                title: "Smart School & IoT",
                                desc: "Presensi RFID realtime, pemantauan ibadah digital, serta layanan operasional terintegrasi melalui SIGMA-3.",
                                color: "text-emerald-600",
                                bg: "bg-emerald-50 border-emerald-100",
                            },
                            {
                                icon: Trophy,
                                title: "Budaya Berprestasi",
                                desc: "Pembinaan intensif olimpiade sains, riset madrasah (Myres), kejuaraan olahraga, dan seni hingga level nasional.",
                                color: "text-amber-600",
                                bg: "bg-amber-50 border-amber-100",
                            },
                            {
                                icon: HeartHandshake,
                                title: "Pembiasaan Karakter Islami",
                                desc: "Pelaksanaan salat Dhuha, Dzuhur berjamaah, tahfidz Al-Qur'an, dan pembinaan adab santun setiap hari.",
                                color: "text-teal-600",
                                bg: "bg-teal-50 border-teal-100",
                            },
                            {
                                icon: Sparkles,
                                title: "Pengembangan Minat & Bakat",
                                desc: "Puluhan cabang ekstrakurikuler kepanduan, robotika, seni musik, riset ilmiah, dan kebahasaan.",
                                color: "text-sky-600",
                                bg: "bg-sky-50 border-sky-100",
                            },
                            {
                                icon: GraduationCap,
                                title: "Pendidik Berdedikasi",
                                desc: "Didukung tenaga pendidik berkualifikasi tinggi, profesional, berprestasi, dan ramah terhadap peserta didik.",
                                color: "text-indigo-600",
                                bg: "bg-indigo-50 border-indigo-100",
                            },
                            {
                                icon: Trees,
                                title: "Adiwiyata & Ramah Anak",
                                desc: "Lingkungan madrasah hijau, asri, bersih, bebas perundungan, dan nyaman untuk proses pembelajaran.",
                                color: "text-emerald-600",
                                bg: "bg-emerald-50 border-emerald-100",
                            },
                        ].map((item, idx) => {
                            const IconComponent = item.icon;
                            return (
                                <GlassCard
                                    key={idx}
                                    className="p-7 bg-white/70 border-white hover:bg-white hover:shadow-lg transition-all duration-300 rounded-3xl"
                                >
                                    <div className={cn("mb-5 flex h-13 w-13 items-center justify-center rounded-2xl border shadow-xs", item.bg)}>
                                        <IconComponent className={cn("w-6 h-6", item.color)} />
                                    </div>
                                    <h3 className="text-xl font-black text-slate-900 mb-2">
                                        {item.title}
                                    </h3>
                                    <p className="text-sm text-slate-600 leading-relaxed">
                                        {item.desc}
                                    </p>
                                </GlassCard>
                            );
                        })}
                    </div>
                </Container>
            </section>

            {/* ==========================================================
                CALL TO ACTION (PPDB & CONTACT)
            ========================================================== */}
            <section
                id="cta"
                className={cn(
                    background.hero,
                    "relative overflow-hidden",
                    spacing.sectionY,
                )}
            >
                <div className={background.aurora}>
                    <div className={background.glowTop} />
                    <div className={background.glowLeft} />
                    <div className={background.glowRight} />
                </div>
                <div className={background.noise} />
                <div className={background.grid} />

                <Container className="relative z-10">
                    <GlassCard className="p-10 md:p-16 text-center border-white bg-white/60 shadow-[0_12px_40px_rgb(0,0,0,0.06)] backdrop-blur-2xl rounded-3xl max-w-4xl mx-auto">
                        <Badge variant="primary" className="mb-6">
                            Penerimaan Peserta Didik Baru
                        </Badge>

                        <h2 className="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">
                            Siap Menjadi Bagian dari <br />
                            <span className="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 bg-clip-text text-transparent">
                                Generasi Emas Madrasah?
                            </span>
                        </h2>

                        <p className="text-slate-600 text-base sm:text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
                            Jadilah pionir masa depan bersama {settings.school_name || "MTsN 3 Tulungagung"}.
                            Pendaftaran siswa baru kini lebih mudah, transparan, dan terintegrasi secara digital.
                        </p>

                        <div className="flex flex-col sm:flex-row justify-center items-center gap-4">
                            <Button
                                href="https://ppdb.mtsn3tulungagung.sch.id"
                                external
                                variant="primary"
                                className="w-full sm:w-auto px-8 py-4 text-base font-bold shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 group"
                            >
                                <span>Daftar PPDB Sekarang</span>
                                <ExternalLink className="w-4 h-4 transition-transform group-hover:scale-110" />
                            </Button>

                            <Button
                                href="https://wa.me/62881081297786?text=Assalamualaikum%20Warahmatullahi%20Wabarakatuh"
                                external
                                target="_blank"
                                rel="noopener noreferrer"
                                variant="glass"
                                className="w-full sm:w-auto px-8 py-4 text-base font-bold border-emerald-200 text-slate-700 hover:bg-white flex items-center justify-center gap-2"
                            >
                                <MessageCircle className="w-5 h-5 text-emerald-600" />
                                <span>Konsultasi CS AI</span>
                            </Button>
                        </div>

                        {/* Reassurance pills */}
                        <div className="mt-10 pt-6 border-t border-slate-200/60 flex flex-wrap justify-center gap-6 text-xs font-semibold text-slate-600">
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>Terakreditasi A (Unggul)</span>
                            </div>
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>Resmi Kemenag RI</span>
                            </div>
                            <div className="flex items-center gap-1.5">
                                <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                                <span>Bantuan Layanan 24/7</span>
                            </div>
                        </div>

                        <p className="mt-8 text-sm text-slate-500">
                            Sudah memiliki akun pengajar atau siswa?{" "}
                            <Link
                                href="/login"
                                className="text-emerald-700 font-bold hover:underline"
                            >
                                Masuk ke Portal SIGMA-3 →
                            </Link>
                        </p>
                    </GlassCard>
                </Container>
            </section>
        </FrontendLayout>
    );
}
