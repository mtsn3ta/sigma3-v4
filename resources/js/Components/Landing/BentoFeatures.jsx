import React, { useState, useEffect } from "react";
import { Link } from "@inertiajs/react";
import Container from "@/Components/UI/Container";
import GlassCard from "@/Components/UI/GlassCard";
import {
    Activity,
    ArrowRight,
    Compass,
    Image as ImageIcon,
    Trophy,
} from "lucide-react";
import { cn } from "@/Theme";

export default function BentoFeatures({
    attendanceSummary = {},
    statistics = {},
    latestAchievements = [],
    featuredExtracurriculars = [],
    latestGallery = [],
}) {
    const [achievementIndex, setAchievementIndex] = useState(0);

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

    return (
        <section id="features" className="py-16 md:py-24 bg-white border-b border-slate-100 relative z-10">
            <Container>
                {/* Header Section */}
                <div className="mb-14 text-center max-w-3xl mx-auto">
                    <span className="inline-block px-3.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-3">
                        Layanan Unggulan
                    </span>
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
                        <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/80 border-slate-200/90 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
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
                        <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-gradient-to-b from-white via-white to-emerald-50/40 border-slate-200/90 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] rounded-3xl">
                            <div className="absolute inset-x-0 top-0 h-1/2 bg-[radial-gradient(ellipse_at_top,rgba(16,185,129,0.1),transparent_70%)] pointer-events-none" />

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
                                    <div className="rounded-2xl border border-slate-100 bg-white/95 p-4 shadow-sm group-hover:shadow-md transition-shadow">
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
                                                <span className="font-semibold text-emerald-700 truncate pr-2">
                                                    {featuredAchievement.competition}
                                                </span>
                                                <span className="shrink-0">{featuredAchievement.date}</span>
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
                        <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/80 border-slate-200/90 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
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
                        <GlassCard className="relative h-full w-full p-6 lg:p-8 flex flex-col justify-between overflow-hidden bg-white/80 border-slate-200/90 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:bg-white rounded-3xl">
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
    );
}
