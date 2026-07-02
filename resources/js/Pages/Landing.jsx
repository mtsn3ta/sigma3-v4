import { motion } from "motion/react";
import { useEffect, useState } from "react";
import { usePage, Link } from "@inertiajs/react";
import FrontendLayout from "@/Layouts/FrontendLayout";
import Container from "@/Components/UI/Container";
import {
    cn,
    background,
    motion as motionTheme,
    spacing,
    glass,
    radius,
} from "@/Theme";
import Badge from "@/Components/UI/Badge";
import Button from "@/Components/UI/Button";
import GlassCard from "@/Components/UI/GlassCard";

// Animasi Stagger untuk konten kanan
const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.15 } },
};

const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0 },
};

export default function Landing() {
    const {
        settings,
        statistics,
        latestAchievements,
        featuredExtracurriculars,
        latestGallery,
        attendanceSummary,
    } = usePage().props;

    const [achievementIndex, setAchievementIndex] = useState(0);

    useEffect(() => {
        if (latestAchievements.length <= 1) return;

        const interval = setInterval(() => {
            setAchievementIndex((current) =>
                current === latestAchievements.length - 1 ? 0 : current + 1,
            );
        }, 5000);

        return () => clearInterval(interval);
    }, [latestAchievements]);

    const featuredAchievement = latestAchievements[achievementIndex] ?? null;
    return (
        <FrontendLayout>
            <section
                id="hero"
                className={cn(
                    background.hero,
                    "relative min-h-[90vh] flex flex-col justify-center pt-32 pb-20", // pt-32 memberi ruang di bawah Navbar fixed
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

                <Container className={spacing.heroTop}>
                    <div className="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                        {/* LEFT CONTENT */}
                        <motion.div
                            initial={{ opacity: 0, x: -20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={motionTheme.normal}
                        >
                            <Badge variant="danger" className="mb-6">
                                Smart Madrasah Platform
                            </Badge>

                            <h1 className="text-5xl md:text-7xl font-black leading-[1.1] tracking-tight text-slate-900">
                                Teknologi Cerdas <br />
                                <span className="text-emerald-700">
                                    Untuk Generasi Emas
                                </span>
                            </h1>

                            <p className="mt-8 text-lg leading-relaxed text-slate-900 max-w-lg">
                                Platform digital terpadu yang mengintegrasikan
                                layanan madrasah dalam satu sistem yang modern,
                                transparan, dan realtime.
                            </p>

                            <div className="mt-10 flex flex-wrap gap-4">
                                <Button
                                    href="/admin/login"
                                    variant="secondary"
                                    className="px-8"
                                >
                                    Masuk ke Dashboard
                                </Button>
                                <Button
                                    href="/prestasi"
                                    variant="glass"
                                    className="px-8"
                                >
                                    Lihat Prestasi
                                </Button>
                            </div>
                        </motion.div>

                        {/* RIGHT CONTENT */}
                        <motion.div
                            variants={containerVariants}
                            initial="hidden"
                            animate="visible"
                        >
                            {/* PERUBAHAN: bg-white/60, border-white, dan tambahan shadow halus */}
                            <GlassCard className="p-6 md:p-8 relative border-white bg-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] backdrop-blur-2xl">
                                <div className="mb-8 flex items-center justify-between">
                                    <h3 className="text-xl font-black text-slate-900">
                                        SIGMA-3 Stats
                                    </h3>
                                    <Badge variant="danger">Live Update</Badge>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    {[
                                        {
                                            label: "Siswa Aktif",
                                            value: statistics.students,
                                        },
                                        {
                                            label: "Prestasi",
                                            value: statistics.achievements,
                                        },
                                        {
                                            label: "Ekstrakurikuler",
                                            value: statistics.extracurriculars,
                                        },
                                        {
                                            label: "Tahun Ajaran",
                                            value: settings.academic_year,
                                        },
                                    ].map((stat, i) => (
                                        <motion.div
                                            key={i}
                                            variants={itemVariants}
                                        >
                                            {/* PERUBAHAN: bg-white/50 dan border putih tipis */}
                                            <GlassCard className="p-4 bg-white/50 border border-white shadow-sm hover:bg-white/80 transition-colors">
                                                <p className="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    {stat.label}
                                                </p>
                                                <h4 className="text-2xl font-black text-slate-900 mt-1">
                                                    {stat.value}
                                                </h4>
                                            </GlassCard>
                                        </motion.div>
                                    ))}
                                </div>
                            </GlassCard>
                        </motion.div>
                    </div>
                </Container>
            </section>
            {/* ==========================================================
                BENTO GRID FEATURES
            ========================================================== */}
            <section
                id="features"
                className={cn("relative z-10", spacing.sectionY)}
            >
                <Container>
                    {/* Header Section */}
                    <div className="mb-16 text-center">
                        <Badge variant="primary" className="mb-4">
                            Layanan Unggulan
                        </Badge>
                        <h2 className="text-3xl md:text-5xl font-black tracking-tight text-slate-900">
                            Satu Platform. <br />
                            <span className="text-emerald-500">
                                Beragam Solusi.
                            </span>
                        </h2>
                    </div>

                    {/* Bento Grid Container - Diperbesar menjadi 320px agar konten tidak tercekik */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 auto-rows-auto lg:auto-rows-80">
                        {/* 1. MONITORING ABSENSI (Lebar 2 Kolom) */}
                        <Link
                            href="/monitoring"
                            className="group block lg:col-span-2 h-full outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col overflow-hidden bg-white/60 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white/80">
                                <div className="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl transition-transform duration-500 group-hover:scale-150" />

                                <div className="relative z-10">
                                    <div className="mb-2 flex items-center gap-3">
                                        <div className="h-2 w-2 rounded-full bg-red-500 animate-pulse" />
                                        <p className="text-[10px] font-bold uppercase tracking-widest text-emerald-600">
                                            Real-Time Tracking
                                        </p>
                                    </div>

                                    <h3 className="text-2xl lg:text-3xl font-black text-slate-900">
                                        Monitoring RFID
                                    </h3>

                                    <p className="mt-1 text-sm font-medium text-slate-500">
                                        {attendanceSummary.today}
                                    </p>
                                </div>

                                {/* Grid Data dirapatkan agar muat */}
                                <div className="relative z-10 mt-4 grid grid-cols-2 gap-x-8 gap-y-2 flex-1">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-slate-500">
                                            🟢 Hadir
                                        </span>
                                        <span className="font-black text-emerald-600">
                                            {attendanceSummary.hadir}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-slate-500">
                                            🟡 Terlambat
                                        </span>
                                        <span className="font-black text-amber-500">
                                            {attendanceSummary.terlambat}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-slate-500">
                                            🔵 Sakit
                                        </span>
                                        <span className="font-black text-sky-600">
                                            {attendanceSummary.sakit}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-slate-500">
                                            🟠 Izin
                                        </span>
                                        <span className="font-black text-orange-500">
                                            {attendanceSummary.izin}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-slate-500">
                                            🔴 Alfa
                                        </span>
                                        <span className="font-black text-red-500">
                                            {attendanceSummary.alfa}
                                        </span>
                                    </div>
                                </div>

                                {/* FOOTER GABUNGAN: Persentase dan Tombol dijadikan 1 baris */}
                                <div className="relative z-10 mt-4 flex items-end justify-between border-t border-white/60 pt-4">
                                    <div>
                                        <div className="text-3xl lg:text-4xl font-black text-emerald-600 leading-none">
                                            {attendanceSummary.persentase}%
                                        </div>
                                        <p className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                            Kehadiran Hari Ini
                                        </p>
                                    </div>

                                    <div className="flex items-center gap-3">
                                        <span className="hidden sm:block text-xs font-bold uppercase tracking-wider text-slate-500 transition-colors group-hover:text-emerald-600">
                                            Pantau
                                        </span>
                                        <div className="flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-white text-emerald-600 shadow-sm transition-all duration-300 group-hover:translate-x-1 group-hover:border-emerald-500 group-hover:bg-emerald-500 group-hover:text-white">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                strokeWidth={2.5}
                                                stroke="currentColor"
                                                className="h-5 w-5"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 2. PRESTASI (Tinggi 2 Baris) */}
                        <Link
                            href="/prestasi"
                            className="group block lg:row-span-2 h-full outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col overflow-hidden bg-linear-to-b from-white/70 to-emerald-50/80 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)]">
                                <div className="absolute inset-x-0 top-0 h-1/2 bg-[radial-gradient(ellipse_at_top,rgba(16,185,129,0.1),transparent_70%)] pointer-events-none" />

                                <div className="relative z-10 mb-4 lg:mb-6">
                                    <p className="mb-1 text-[10px] font-bold uppercase tracking-widest text-emerald-500">
                                        Hall of Fame
                                    </p>
                                    <h3 className="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                                        Portal Prestasi
                                    </h3>
                                </div>

                                <div className="relative z-10 flex-1 flex flex-col">
                                    {featuredAchievement && (
                                        <div className="flex-1 flex flex-col">
                                            {/* Tinggi Frame Foto disesuaikan (h-36) agar teks di bawahnya tidak tertekan */}
                                            <div className="relative overflow-hidden rounded-2xl border-2 border-white shadow-sm bg-white/50 group-hover:shadow-md transition-shadow duration-500">
                                                {featuredAchievement.photo ? (
                                                    <img
                                                        src={
                                                            featuredAchievement.photo
                                                        }
                                                        alt={
                                                            featuredAchievement.title
                                                        }
                                                        className="h-36 lg:h-44 w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                    />
                                                ) : (
                                                    <div className="flex h-36 lg:h-44 items-center justify-center bg-linear-to-br from-emerald-50 to-emerald-100/50 text-5xl">
                                                        🏆
                                                    </div>
                                                )}
                                            </div>

                                            <div className="mt-4 flex-1">
                                                <div className="flex items-start justify-between gap-2">
                                                    <span className="inline-flex items-center rounded-full border border-white bg-white/60 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-600 shadow-sm backdrop-blur-md">
                                                        {
                                                            featuredAchievement.level
                                                        }
                                                    </span>
                                                    <span className="text-[10px] font-medium text-slate-400 shrink-0">
                                                        {
                                                            featuredAchievement.date
                                                        }
                                                    </span>
                                                </div>

                                                <h4 className="mt-2 line-clamp-2 text-base lg:text-lg font-bold leading-snug text-slate-900">
                                                    {featuredAchievement.title}
                                                </h4>

                                                <p className="mt-1 text-xs font-medium text-slate-500 line-clamp-1">
                                                    {
                                                        featuredAchievement.competition
                                                    }
                                                </p>
                                            </div>

                                            {latestAchievements &&
                                                latestAchievements.length >
                                                    1 && (
                                                    <div className="mt-4 flex justify-center gap-1.5">
                                                        {latestAchievements.map(
                                                            (_, index) => (
                                                                <div
                                                                    key={index}
                                                                    className={cn(
                                                                        "h-1.5 rounded-full transition-all duration-500",
                                                                        achievementIndex ===
                                                                            index
                                                                            ? "w-6 bg-emerald-500 shadow-sm"
                                                                            : "w-1.5 bg-slate-300/50",
                                                                    )}
                                                                />
                                                            ),
                                                        )}
                                                    </div>
                                                )}
                                        </div>
                                    )}
                                </div>

                                <div className="relative z-10 mt-6 pt-5 border-t border-white/60 flex items-center justify-between">
                                    <div>
                                        <p className="text-2xl font-black text-slate-900 leading-none">
                                            {statistics.achievements || 0}
                                        </p>
                                        <p className="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                            Total Prestasi
                                        </p>
                                    </div>

                                    <div className="flex items-center gap-2 text-xs font-bold text-emerald-600 bg-white/50 px-3 py-1.5 rounded-full border border-white shadow-sm transition-colors group-hover:bg-emerald-50">
                                        Lihat Semua
                                        <span className="transition-transform duration-300 group-hover:translate-x-1">
                                            →
                                        </span>
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 3. EKSTRAKURIKULER (Standar) */}
                        <Link
                            href="/ekstrakurikuler"
                            className="group block h-full outline-none"
                        >
                            <GlassCard className="relative flex h-full w-full flex-col overflow-hidden border-white bg-white/60 p-6 lg:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:bg-white/80 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)]">
                                <div className="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-emerald-400/10 blur-3xl" />

                                <div className="relative z-10 flex-1 flex flex-col">
                                    <div>
                                        <p className="mb-1 text-[10px] font-bold uppercase tracking-widest text-emerald-600">
                                            Pengembangan Diri
                                        </p>
                                        <h3 className="text-xl lg:text-2xl font-black text-slate-900">
                                            Ekstrakurikuler
                                        </h3>
                                        <div className="mt-2 flex items-baseline gap-2">
                                            <span className="text-3xl font-black tracking-tighter text-emerald-600">
                                                {statistics.extracurriculars ||
                                                    0}
                                            </span>
                                            <span className="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>

                                    <div className="mt-4 flex-1 space-y-2">
                                        {featuredExtracurriculars
                                            ?.slice(0, 2)
                                            .map((item) => (
                                                <div
                                                    key={item.id}
                                                    className="flex items-center justify-between rounded-xl border border-white/60 bg-white/50 px-3 py-2 shadow-sm backdrop-blur-sm"
                                                >
                                                    <div className="flex items-center gap-2.5">
                                                        <span className="h-2 w-2 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]" />
                                                        <span className="text-sm font-bold text-slate-700 line-clamp-1">
                                                            {item.nama}
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                    </div>
                                </div>

                                <div className="relative z-10 mt-4 flex items-center justify-between border-t border-white/60 pt-4">
                                    <span className="text-xs font-bold uppercase tracking-wider text-slate-500 transition-colors group-hover:text-emerald-600">
                                        Lihat Semua
                                    </span>
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-emerald-100 text-emerald-600 shadow-sm transition-all group-hover:translate-x-1 group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            strokeWidth={2.5}
                                            stroke="currentColor"
                                            className="w-4 h-4"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>

                        {/* 4. GALERI (Standar) */}
                        <Link
                            href="/galeri"
                            className="group block h-full outline-none"
                        >
                            <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col overflow-hidden bg-white/60 border-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white/80">
                                <div className="absolute right-0 top-1/2 flex -translate-y-1/2 translate-x-1/4 rotate-12 gap-3 opacity-40 transition-all duration-500 group-hover:-translate-x-4 group-hover:rotate-6">
                                    <div className="h-32 w-24 overflow-hidden rounded-xl border-4 border-white bg-slate-200 shadow-lg">
                                        {latestGallery?.[0]?.cover ? (
                                            <img
                                                src={latestGallery[0].cover}
                                                alt={latestGallery[0].title}
                                                className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center bg-slate-100 text-3xl">
                                                🖼️
                                            </div>
                                        )}
                                    </div>
                                    <div className="-translate-y-6 h-32 w-24 overflow-hidden rounded-xl border-4 border-white bg-slate-300 shadow-lg">
                                        {latestGallery?.[1]?.cover ? (
                                            <img
                                                src={latestGallery[1].cover}
                                                alt={latestGallery[1].title}
                                                className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            />
                                        ) : (
                                            <div className="flex h-full items-center justify-center bg-slate-100 text-3xl">
                                                🖼️
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="relative z-10 flex-1">
                                    <p className="text-[10px] font-bold uppercase tracking-widest text-emerald-600 mb-1">
                                        Dokumentasi
                                    </p>
                                    <h3 className="text-xl lg:text-2xl font-black text-slate-900">
                                        Galeri
                                    </h3>
                                </div>

                                <div className="relative z-10 mt-4 flex items-center justify-between border-t border-white/60 pt-4">
                                    <span className="text-xs font-bold uppercase tracking-wider text-slate-500 transition-colors group-hover:text-emerald-600">
                                        Buka Galeri
                                    </span>
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-emerald-100 text-emerald-600 shadow-sm transition-all group-hover:translate-x-1 group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            strokeWidth={2.5}
                                            stroke="currentColor"
                                            className="w-4 h-4"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </GlassCard>
                        </Link>
                    </div>
                </Container>
            </section>
            <section
                id="fitur-modul"
                className={cn("relative z-10", spacing.sectionY)}
            >
                <Container>
                    <div className="mb-16 text-center">
                        <Badge variant="primary" className="mb-4">
                            Modul Sistem
                        </Badge>

                        <h2 className="text-3xl font-black tracking-tight text-slate-900 md:text-5xl">
                            Ekosistem Digital
                            <br />
                            <span className="text-emerald-500">SIGMA-3</span>
                        </h2>

                        <p className="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-slate-500">
                            Seluruh layanan madrasah terintegrasi dalam satu
                            platform, mulai dari kehadiran, pembelajaran,
                            bimbingan konseling, ekstrakurikuler, hingga
                            publikasi informasi.
                        </p>
                    </div>

                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {[
                            {
                                icon: "💳",
                                title: "eKartu Pelajar",
                                desc: "RFID, kehadiran sekolah, serta monitoring ibadah.",
                            },
                            {
                                icon: "📊",
                                title: "Monitoring Absensi",
                                desc: "Monitoring kehadiran siswa secara realtime.",
                            },
                            {
                                icon: "🎓",
                                title: "Ekstrakurikuler",
                                desc: "Manajemen anggota, kegiatan, proposal, dan laporan.",
                            },
                            {
                                icon: "🧠",
                                title: "BK Digital",
                                desc: "Konseling, permasalahan siswa, dan tindak lanjut.",
                            },
                            {
                                icon: "👨‍🏫",
                                title: "Jurnal Mengajar",
                                desc: "Presensi pembelajaran dan jurnal guru.",
                            },
                            {
                                icon: "🏆",
                                title: "Prestasi",
                                desc: "Publikasi prestasi akademik maupun non akademik.",
                            },
                            {
                                icon: "🖼️",
                                title: "Galeri",
                                desc: "Dokumentasi kegiatan dan publikasi madrasah.",
                            },
                            {
                                icon: "📢",
                                title: "Pengumuman",
                                desc: "Informasi penting dan urgent alert madrasah.",
                            },
                        ].map((fitur, index) => (
                            <GlassCard
                                key={index}
                                className="
                        group
                        border-white
                        bg-white/60
                        p-6
                        transition-all
                        duration-300
                        hover:-translate-y-2
                        hover:border-emerald-200
                        hover:bg-white
                    "
                            >
                                <div className="mb-5 text-5xl transition-transform duration-300 group-hover:scale-110">
                                    {fitur.icon}
                                </div>

                                <h3 className="mb-3 text-lg font-black text-slate-900">
                                    {fitur.title}
                                </h3>

                                <p className="text-sm leading-relaxed text-slate-500">
                                    {fitur.desc}
                                </p>
                            </GlassCard>
                        ))}
                    </div>
                </Container>
            </section>
            <section
                id="keunggulan"
                className={cn("relative z-10 bg-slate-50", spacing.sectionY)}
            >
                <Container>
                    {/* Header Section (Rata Tengah) */}
                    <div className="mb-16 flex flex-col items-center text-center max-w-3xl mx-auto">
                        {/* Badge tetap di atas */}
                        <Badge variant="primary" className="mb-6">
                            Keunggulan Madrasah
                        </Badge>

                        <h2 className="text-3xl md:text-5xl font-black tracking-tight text-slate-900 mb-6">
                            Mengapa Memilih <br />
                            <span className="text-emerald-500">
                                MTsN 3 Tulungagung?
                            </span>
                        </h2>

                        {/* Visi dalam bentuk kutipan rata tengah */}
                        <div className="relative px-6 border-l-4 border-r-4 border-emerald-500">
                            <p className="text-xl md:text-2xl font-medium text-slate-700 italic leading-relaxed">
                                "Terwujudnya generasi pionir yang religius,
                                berbudaya, terampil dan kompetitif serta peduli
                                lingkungan."
                            </p>
                        </div>
                    </div>

                    {/* 3x2 Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {/* Kartu 1: Smart School */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                📡
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Smart School
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Sistem RFID realtime, monitoring kehadiran,
                                serta layanan digital terintegrasi melalui
                                SIGMA-3.
                            </p>
                        </GlassCard>

                        {/* Kartu 2: Prestasi */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                🏆
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Budaya Berprestasi
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Prestasi akademik maupun non akademik terus
                                berkembang hingga tingkat nasional.
                            </p>
                        </GlassCard>

                        {/* Kartu 3: Religius */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                🕌
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Budaya Islami
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Monitoring Dhuha, Dzuhur, serta pembinaan
                                karakter siswa setiap hari.
                            </p>
                        </GlassCard>

                        {/* Kartu 4: Ekstrakurikuler */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                🎓
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Pengembangan Bakat
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Puluhan pilihan ekstrakurikuler untuk
                                mengembangkan minat dan bakat siswa.
                            </p>
                        </GlassCard>

                        {/* Kartu 5: Guru Profesional */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                👨‍🏫
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Guru Profesional
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Didukung tenaga pendidik yang kompeten,
                                berpengalaman, dan berdedikasi tinggi.
                            </p>
                        </GlassCard>

                        {/* Kartu 6: Lingkungan */}
                        <GlassCard className="p-8 bg-white/60 border-white hover:bg-white/80 transition-all duration-300">
                            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-2xl shadow-sm border border-emerald-100">
                                🌿
                            </div>
                            <h3 className="text-xl font-black text-slate-900 mb-3">
                                Lingkungan Nyaman
                            </h3>
                            <p className="text-sm text-slate-500 leading-relaxed">
                                Madrasah hijau, bersih, aman, dan nyaman untuk
                                mendukung proses belajar.
                            </p>
                        </GlassCard>
                    </div>
                </Container>
            </section>
            <section
                id="cta"
                className={cn(
                    background.hero,
                    "relative overflow-hidden",
                    spacing.sectionY, // Menggantikan py-24
                )}
            >
                {/* Background Elements (Menggunakan yang sama dengan Hero) */}
                <div className={background.aurora}>
                    <div className={background.glowTop} />
                    <div className={background.glowLeft} />
                    <div className={background.glowRight} />
                </div>
                <div className={background.noise} />
                <div className={background.grid} />

                <Container className="relative z-10">
                    <GlassCard className="p-12 md:p-20 text-center border-white bg-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-2xl">
                        <h2 className="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">
                            Siap Menjadi Bagian dari <br />
                            <span className="text-emerald-600">
                                Generasi Emas?
                            </span>
                        </h2>

                        <p className="text-slate-600 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                            Jadilah pionir masa depan bersama MTsN 3
                            Tulungagung. Pendaftaran siswa baru kini lebih
                            mudah, transparan, dan terintegrasi.
                        </p>

                        <div className="flex flex-col sm:flex-row justify-center gap-4">
                            <Button
                                href="https://ppdb.mtsn3tulungagung.sch.id"
                                variant="primary"
                                className="px-8 py-4 text-lg bg-emerald-600 hover:bg-emerald-700 border-none"
                            >
                                Daftar PPDB Sekarang
                            </Button>

                            <Button
                                href="/kontak"
                                variant="glass"
                                className="px-8 py-4 text-lg border-emerald-200 text-slate-700 hover:bg-white/50"
                            >
                                Hubungi Kami
                            </Button>
                        </div>

                        <p className="mt-8 text-sm text-slate-500">
                            Sudah memiliki akun?{" "}
                            <Link
                                href="/admin/login"
                                className="text-emerald-600 font-bold hover:underline"
                            >
                                Masuk ke Dashboard
                            </Link>
                        </p>
                    </GlassCard>
                </Container>
            </section>
        </FrontendLayout>
    );
}
