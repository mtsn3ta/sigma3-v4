import { motion } from "motion/react";
import { Head, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import axios from "axios";

import FrontendLayout from "@/Layouts/FrontendLayout";
import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import GlassCard from "@/Components/UI/GlassCard";
import { cn, background, motion as motionTheme, spacing } from "@/Theme";
import {
    CheckCircle2,
    Clock,
    XCircle,
    Radio,
    Search,
    Calendar,
    ChevronDown,
    RotateCw,
    Users,
    Sun,
    Sunset,
    LogOut,
    LogIn,
    ChevronLeft,
    ChevronRight,
    WifiOff,
} from "lucide-react";

export default function Monitoring() {
    const searchTimeout = useRef(null);

    const {
        attendance: initialAttendance,
        statistics: initialStatistics,
        settings,
        filters: initialFilters = {},
        classes = {},
    } = usePage().props;

    const getJakartaDateString = () => {
        const options = {
            timeZone: "Asia/Jakarta",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        };
        const formatter = new Intl.DateTimeFormat("en-CA", options);
        return formatter.format(new Date());
    };

    const [attendance, setAttendance] = useState(initialAttendance || { data: [], links: [] });
    const [statistics, setStatistics] = useState(initialStatistics || {});
    const [loading, setLoading] = useState(false);
    const [lastUpdated, setLastUpdated] = useState(new Date().toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" }));

    const [filters, setFilters] = useState({
        search: initialFilters.search ?? "",
        kelas: initialFilters.kelas ?? "",
        tanggal:
            initialFilters.tanggal && initialFilters.tanggal !== ""
                ? initialFilters.tanggal
                : getJakartaDateString(),
    });

    const loadAttendance = async (
        currentFilters = filters,
        page = attendance.current_page ?? 1,
    ) => {
        setLoading(true);
        try {
            const { data } = await axios.get("/api/monitoring", {
                params: {
                    ...currentFilters,
                    page,
                },
            });

            setAttendance(data.attendance);
            setStatistics(data.statistics);
            setLastUpdated(new Date().toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" }));
        } catch (error) {
            console.error("Gagal memuat monitoring:", error);
        } finally {
            setLoading(false);
        }
    };

    const applyFilter = (changes = {}) => {
        const newFilters = {
            ...filters,
            ...changes,
        };
        setFilters(newFilters);
        loadAttendance(newFilters, 1);
    };

    useEffect(() => {
        const interval = setInterval(() => {
            loadAttendance(filters);
        }, 30000);

        return () => clearInterval(interval);
    }, [filters]);

    return (
        <FrontendLayout>
            <Head title={`Monitoring Absensi - ${settings?.school_name ?? "SIGMA-3"}`} />

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
                        <div className="absolute -left-32 -top-24 h-96 w-96 rounded-full bg-emerald-400/20 blur-[120px]" />
                        <div className="absolute -right-24 top-10 h-80 w-80 rounded-full bg-cyan-400/15 blur-[100px]" />
                    </div>

                    <Container>
                        <div className="mx-auto max-w-4xl text-center">
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="mb-4 inline-flex items-center gap-2 rounded-full border border-emerald-200/80 bg-emerald-50/90 px-4 py-1.5 text-xs font-semibold text-emerald-800 shadow-xs backdrop-blur-md"
                            >
                                <span className="relative flex h-2 w-2">
                                    <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                                    <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-500" />
                                </span>
                                <span>RFID Live Scanner Dashboard</span>
                                <span className="text-emerald-300">•</span>
                                <span className="text-emerald-700 font-medium flex items-center gap-1">
                                    <RotateCw className={cn("w-3 h-3", loading && "animate-spin")} />
                                    <span>Sync: {lastUpdated}</span>
                                </span>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900"
                            >
                                Monitoring{" "}
                                <span className="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">
                                    Presensi Siswa
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 12 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ ...motionTheme.normal, delay: 0.1 }}
                                className="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600"
                            >
                                Pemantauan kehadiran gerbang RFID, pelaksanaan ibadah Dhuha & Dzuhur,
                                serta status dispensasi siswa secara realtime.
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
                                    label: "Hadir Lengkap",
                                    value: statistics.hadir ?? 0,
                                    icon: CheckCircle2,
                                    color: "text-emerald-600",
                                    bg: "bg-emerald-50 border-emerald-100",
                                },
                                {
                                    label: "Terlambat",
                                    value: statistics.terlambat ?? 0,
                                    icon: Clock,
                                    color: "text-amber-500",
                                    bg: "bg-amber-50 border-amber-100",
                                },
                                {
                                    label: "Tidak Hadir / Izin",
                                    value: statistics.tidak_hadir ?? 0,
                                    icon: XCircle,
                                    color: "text-rose-600",
                                    bg: "bg-rose-50 border-rose-100",
                                },
                                {
                                    label: "Scan RFID Hari Ini",
                                    value: statistics.rfid ?? 0,
                                    icon: Radio,
                                    color: "text-sky-600",
                                    bg: "bg-sky-50 border-sky-100",
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
                    FILTER BAR
                ======================================================= */}
                <section className="relative z-10 py-6">
                    <Container>
                        <GlassCard className="border border-white/80 bg-white/75 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl rounded-3xl">
                            <div className="grid items-center gap-3 md:grid-cols-[1fr_220px_200px]">
                                {/* Search Input */}
                                <div className="relative">
                                    <Search className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <input
                                        type="text"
                                        value={filters.search}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            setFilters((current) => ({
                                                ...current,
                                                search: value,
                                            }));

                                            clearTimeout(searchTimeout.current);
                                            searchTimeout.current = setTimeout(() => {
                                                applyFilter({ search: value });
                                            }, 350);
                                        }}
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") {
                                                applyFilter(filters);
                                            }
                                        }}
                                        placeholder="Cari NISN atau nama siswa..."
                                        className="h-11 w-full rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-4 text-sm text-slate-800 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15"
                                    />
                                </div>

                                {/* Kelas Select */}
                                <div className="relative">
                                    <Users className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                    <select
                                        value={filters.kelas}
                                        onChange={(e) =>
                                            applyFilter({ kelas: e.target.value })
                                        }
                                        className="h-11 w-full appearance-none rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-10 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                    >
                                        <option value="">Semua Kelas</option>
                                        {Object.entries(classes).map(([id, nama]) => (
                                            <option key={id} value={id}>
                                                {nama}
                                            </option>
                                        ))}
                                    </select>
                                    <ChevronDown className="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                </div>

                                {/* Tanggal Picker + Quick Hari Ini */}
                                <div className="relative flex items-center gap-2">
                                    <div className="relative flex-1">
                                        <Calendar className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                        <input
                                            type="date"
                                            value={filters.tanggal}
                                            onChange={(e) =>
                                                applyFilter({ tanggal: e.target.value })
                                            }
                                            className="h-11 w-full rounded-2xl border border-slate-200/80 bg-white/80 pl-11 pr-3 text-sm font-medium text-slate-700 shadow-inner outline-none transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/15 cursor-pointer"
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => applyFilter({ tanggal: getJakartaDateString() })}
                                        className="h-11 px-3.5 rounded-2xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200/70 text-xs font-bold text-slate-600 transition-colors whitespace-nowrap cursor-pointer"
                                        title="Kembali ke Hari Ini"
                                    >
                                        Hari Ini
                                    </button>
                                </div>
                            </div>
                        </GlassCard>
                    </Container>
                </section>

                {/* =======================================================
                    ATTENDANCE TABLE SECTION
                ======================================================= */}
                <section className="pb-20">
                    <Container>
                        <GlassCard className="overflow-hidden border border-white/80 bg-white/75 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl rounded-3xl">
                            {/* =========================
                                DESKTOP TABLE
                            ========================= */}
                            <div className="hidden lg:block overflow-x-auto">
                                {loading ? (
                                    <div className="py-24 text-center">
                                        <RotateCw className="w-8 h-8 animate-spin text-emerald-600 mx-auto mb-3" />
                                        <p className="text-sm font-bold text-slate-600">
                                            Memperbarui data kehadiran...
                                        </p>
                                    </div>
                                ) : !attendance.data || attendance.data.length === 0 ? (
                                    <div className="py-24 text-center max-w-sm mx-auto">
                                        <div className="h-16 w-16 rounded-3xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                                            <WifiOff className="w-8 h-8" />
                                        </div>
                                        <h3 className="text-xl font-black text-slate-900">
                                            Tidak Ada Data Monitoring
                                        </h3>
                                        <p className="mt-2 text-xs text-slate-500 leading-relaxed">
                                            Belum terdapat data kehadiran untuk filter pencarian, kelas, atau tanggal yang dipilih.
                                        </p>
                                    </div>
                                ) : (
                                    <table className="min-w-full divide-y divide-slate-100">
                                        <thead className="bg-slate-50/80">
                                            <tr className="text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                                <th className="px-6 py-4">NISN</th>
                                                <th className="px-6 py-4">Nama Siswa</th>
                                                <th className="px-4 py-4 text-center">Datang</th>
                                                <th className="px-4 py-4 text-center">Dhuha</th>
                                                <th className="px-4 py-4 text-center">Dzuhur</th>
                                                <th className="px-4 py-4 text-center">Pulang</th>
                                                <th className="px-6 py-4 text-center">Status</th>
                                                <th className="px-6 py-4 text-center">RFID</th>
                                            </tr>
                                        </thead>

                                        <tbody className="divide-y divide-slate-100 bg-white/40">
                                            {attendance.data.map((student, idx) => (
                                                <tr
                                                    key={student.id}
                                                    className="transition-colors hover:bg-emerald-50/50"
                                                >
                                                    <td className="px-6 py-4 font-mono text-xs font-semibold text-slate-600">
                                                        {student.nisn}
                                                    </td>

                                                    <td className="px-6 py-4">
                                                        <div>
                                                            <p className="font-bold text-slate-900 text-sm">
                                                                {student.nama}
                                                            </p>
                                                            <p className="text-xs text-slate-500">
                                                                {student.kelas || "Kelas —"}
                                                            </p>
                                                        </div>
                                                    </td>

                                                    {/* Jam Datang */}
                                                    <td className="px-4 py-4 text-center font-mono text-xs font-semibold text-slate-700">
                                                        {student.jam_datang ? (
                                                            <span className="inline-flex items-center gap-1 text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                                                <LogIn className="w-3 h-3 text-emerald-600" />
                                                                {student.jam_datang}
                                                            </span>
                                                        ) : (
                                                            <span className="text-slate-300">—</span>
                                                        )}
                                                    </td>

                                                    {/* Jam Dhuha */}
                                                    <td className="px-4 py-4 text-center font-mono text-xs font-semibold text-slate-700">
                                                        {student.jam_dhuha ? (
                                                            <span className="inline-flex items-center gap-1 text-teal-800 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-100">
                                                                <Sun className="w-3 h-3 text-teal-600" />
                                                                {student.jam_dhuha}
                                                            </span>
                                                        ) : (
                                                            <span className="text-slate-300">—</span>
                                                        )}
                                                    </td>

                                                    {/* Jam Dzuhur */}
                                                    <td className="px-4 py-4 text-center font-mono text-xs font-semibold text-slate-700">
                                                        {student.jam_dzuhur ? (
                                                            <span className="inline-flex items-center gap-1 text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                                                <Sunset className="w-3 h-3 text-indigo-600" />
                                                                {student.jam_dzuhur}
                                                            </span>
                                                        ) : (
                                                            <span className="text-slate-300">—</span>
                                                        )}
                                                    </td>

                                                    {/* Jam Pulang */}
                                                    <td className="px-4 py-4 text-center font-mono text-xs font-semibold text-slate-700">
                                                        {student.jam_pulang ? (
                                                            <span className="inline-flex items-center gap-1 text-sky-800 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100">
                                                                <LogOut className="w-3 h-3 text-sky-600" />
                                                                {student.jam_pulang}
                                                            </span>
                                                        ) : (
                                                            <span className="text-slate-300">—</span>
                                                        )}
                                                    </td>

                                                    {/* Status Badge */}
                                                    <td className="px-6 py-4 text-center">
                                                        <Badge variant={student.status?.color || "neutral"}>
                                                            {student.status?.label || "Status"}
                                                        </Badge>
                                                    </td>

                                                    {/* RFID Source */}
                                                    <td className="px-6 py-4 text-center">
                                                        <span
                                                            className={cn(
                                                                "inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold rounded-full border",
                                                                student.rfid?.code === "rfid"
                                                                    ? "border-emerald-200 bg-emerald-50 text-emerald-800"
                                                                    : "border-slate-200 bg-slate-50 text-slate-600",
                                                            )}
                                                        >
                                                            <Radio className="w-3 h-3" />
                                                            {student.rfid?.label || "Manual"}
                                                        </span>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                )}
                            </div>

                            {/* =========================
                                MOBILE CARD LIST
                            ========================= */}
                            <div className="space-y-4 p-4 lg:hidden">
                                {loading ? (
                                    <div className="py-20 text-center">
                                        <RotateCw className="w-8 h-8 animate-spin text-emerald-600 mx-auto mb-2" />
                                        <p className="text-xs font-bold text-slate-600">
                                            Memperbarui data...
                                        </p>
                                    </div>
                                ) : !attendance.data || attendance.data.length === 0 ? (
                                    <div className="py-16 text-center">
                                        <WifiOff className="w-10 h-10 text-slate-300 mx-auto mb-2" />
                                        <h3 className="text-lg font-black text-slate-900">
                                            Tidak Ada Data
                                        </h3>
                                        <p className="text-xs text-slate-500 mt-1">
                                            Belum ada presensi untuk filter ini.
                                        </p>
                                    </div>
                                ) : (
                                    attendance.data.map((student) => (
                                        <div
                                            key={student.id}
                                            className="rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-xs"
                                        >
                                            <div className="flex items-start justify-between gap-2">
                                                <div>
                                                    <h3 className="text-base font-black text-slate-900 leading-snug">
                                                        {student.nama}
                                                    </h3>
                                                    <p className="mt-0.5 text-xs text-slate-500">
                                                        {student.nisn} &bull; {student.kelas}
                                                    </p>
                                                </div>
                                                <Badge variant={student.status?.color || "neutral"}>
                                                    {student.status?.label}
                                                </Badge>
                                            </div>

                                            {/* Grid Timestamps */}
                                            <div className="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                                                <div className="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                                        Datang
                                                    </span>
                                                    <span className="font-bold text-slate-800">
                                                        {student.jam_datang || "—"}
                                                    </span>
                                                </div>
                                                <div className="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                                        Pulang
                                                    </span>
                                                    <span className="font-bold text-slate-800">
                                                        {student.jam_pulang || "—"}
                                                    </span>
                                                </div>
                                                <div className="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                                        Dhuha
                                                    </span>
                                                    <span className="font-bold text-slate-800">
                                                        {student.jam_dhuha || "—"}
                                                    </span>
                                                </div>
                                                <div className="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                                    <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                                        Dzuhur
                                                    </span>
                                                    <span className="font-bold text-slate-800">
                                                        {student.jam_dzuhur || "—"}
                                                    </span>
                                                </div>
                                            </div>

                                            <div className="mt-3 flex items-center justify-between text-[11px] text-slate-500">
                                                <span>Verifikasi RFID:</span>
                                                <span className="font-bold text-slate-700">
                                                    {student.rfid?.label || "Manual"}
                                                </span>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </GlassCard>

                        {/* =======================================================
                            PAGINATION
                        ======================================================= */}
                        {attendance.last_page > 1 && (
                            <div className="mt-8 flex justify-center">
                                <div className="flex flex-wrap items-center gap-1.5 rounded-2xl border border-white/80 bg-white/70 p-2 shadow-xs backdrop-blur-xl">
                                    {attendance.links?.map((link, index) => (
                                        <button
                                            key={index}
                                            disabled={!link.url}
                                            onClick={() => {
                                                if (!link.url) return;
                                                const urlObj = new URL(link.url, window.location.origin);
                                                const page = urlObj.searchParams.get("page") || 1;
                                                loadAttendance(filters, page);
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
        </FrontendLayout>
    );
}
