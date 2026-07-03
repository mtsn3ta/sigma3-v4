import { motion } from "motion/react";
import { Head, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import axios from "axios";

import FrontendLayout from "@/Layouts/FrontendLayout";

import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import GlassCard from "@/Components/UI/GlassCard";

import { cn, background, motion as motionTheme, spacing } from "@/Theme";

export default function Monitoring() {
    const searchTimeout = useRef(null);
    /*
    |--------------------------------------------------------------------------
    | Initial Data (Inertia)
    |--------------------------------------------------------------------------
    */

    const {
        attendance: initialAttendance,
        statistics: initialStatistics,
        settings,
        filters: initialFilters = {},
        classes = {},
    } = usePage().props;

    /*
|--------------------------------------------------------------------------
| React State
|--------------------------------------------------------------------------
*/

    const [attendance, setAttendance] = useState(initialAttendance);

    const [statistics, setStatistics] = useState(initialStatistics);

    const [loading, setLoading] = useState(false);

    const [filters, setFilters] = useState({
        search: initialFilters.search ?? "",
        kelas: initialFilters.kelas ?? "",
        tanggal: initialFilters.tanggal ?? "",
    });

    /*
|--------------------------------------------------------------------------
| Load Attendance
|--------------------------------------------------------------------------
*/

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
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    /*
|--------------------------------------------------------------------------
| Apply Filter
|--------------------------------------------------------------------------
*/

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
    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    */

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
                d="M19 9l-7 7-7-7"
            />
        </svg>
    );

    return (
        <FrontendLayout>
            <Head
                title={`Monitoring Absensi - ${settings?.school_name ?? "SIGMA-3"}`}
            />

            <main className={cn(background.gradient, "relative bg-[#f7faf9]")}>
                {/* =======================================================
                    HERO
                ======================================================= */}

                <section
                    className={cn(
                        background.hero,
                        spacing.pageHeroTop,
                        spacing.pageHeroBottomMonitoring,
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
                                    id="grid-monitoring"
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
                                fill="url(#grid-monitoring)"
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
                                    RFID Live Monitoring
                                </Badge>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-5xl font-black tracking-tight text-slate-900 md:text-7xl"
                            >
                                Monitoring{" "}
                                <span className="bg-linear-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent">
                                    Absensi
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
                                Monitoring kehadiran siswa secara real-time
                                meliputi kehadiran sekolah, ibadah, serta status
                                RFID dalam satu dashboard.
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
                                    label: "Hadir",
                                    value: statistics.hadir ?? 0,
                                    icon: "🟢",
                                },
                                {
                                    label: "Terlambat",
                                    value: statistics.terlambat ?? 0,
                                    icon: "🟡",
                                },
                                {
                                    label: "Tidak Hadir",
                                    value: statistics.tidak_hadir ?? 0,
                                    icon: "🔴",
                                },
                                {
                                    label: "RFID",
                                    value: statistics.rfid ?? 0,
                                    icon: "📡",
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

                <section
                    className={cn("relative z-10", spacing.sectionYMonitoring)}
                >
                    <Container>
                        <GlassCard className="relative overflow-hidden border border-white/70 bg-white/55 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.06)] backdrop-blur-xl">
                            <div className="pointer-events-none absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-emerald-400/50 to-transparent" />

                            <div className="grid items-center gap-3 lg:grid-cols-[1fr_200px_190px]">
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
                                        value={filters.search}
                                        onChange={(e) => {
                                            const value = e.target.value;

                                            setFilters((current) => ({
                                                ...current,
                                                search: value,
                                            }));

                                            clearTimeout(searchTimeout.current);

                                            searchTimeout.current = setTimeout(
                                                () => {
                                                    applyFilter({
                                                        search: value,
                                                    });
                                                },
                                                300,
                                            );
                                        }}
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") {
                                                applyFilter(filters);
                                            }
                                        }}
                                        placeholder="Cari NISN atau nama siswa..."
                                        className="h-11 w-full rounded-2xl border border-white bg-white/70 pl-12 pr-4 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    />
                                </div>

                                {/* Kelas */}

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
                                            d="M3 7h18M6 12h12M10 17h4"
                                        />
                                    </svg>

                                    <select
                                        value={filters.kelas}
                                        onChange={(e) =>
                                            applyFilter({
                                                kelas: e.target.value,
                                            })
                                        }
                                        className="h-11 w-full appearance-none rounded-2xl border border-white bg-white/70 pl-11 pr-9 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    >
                                        <option value="">Semua Kelas</option>

                                        {Object.entries(classes).map(
                                            ([id, nama]) => (
                                                <option key={id} value={id}>
                                                    {nama}
                                                </option>
                                            ),
                                        )}
                                    </select>

                                    <SelectChevron />
                                </div>

                                {/* Tanggal */}

                                <div className="relative">
                                    <input
                                        type="date"
                                        value={filters.tanggal}
                                        onChange={(e) =>
                                            applyFilter({
                                                tanggal: e.target.value,
                                            })
                                        }
                                        className="h-11 w-full rounded-2xl border border-white bg-white/70 px-4 text-sm text-slate-700 shadow-sm outline-none transition-all duration-300 focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                    />
                                </div>
                            </div>
                        </GlassCard>
                    </Container>
                </section>

                {/* =======================================================
                    TABLE
                ======================================================= */}

                <section className={spacing.sectionBottom}>
                    <Container>
                        <GlassCard className="overflow-hidden border border-white/70 bg-white/60 backdrop-blur-xl">
                            {/* =========================
        Desktop
    ========================= */}
                            <div className="hidden lg:block overflow-x-auto">
                                {loading ? (
                                    <div className="py-16 text-center text-slate-500">
                                        Memuat data...
                                    </div>
                                ) : attendance.data.length === 0 ? (
                                    <div className="py-20 text-center">
                                        <div className="text-6xl">📡</div>

                                        <h3 className="mt-6 text-2xl font-black text-slate-900">
                                            Tidak ada data monitoring.
                                        </h3>

                                        <p className="mt-3 text-slate-500">
                                            Belum terdapat data kehadiran untuk
                                            filter yang dipilih.
                                        </p>
                                    </div>
                                ) : (
                                    <table className="min-w-full divide-y divide-slate-100">
                                        <thead className="bg-slate-50/80">
                                            <tr className="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                                <th className="px-6 py-4">
                                                    NISN
                                                </th>
                                                <th className="px-6 py-4">
                                                    Nama
                                                </th>
                                                <th className="px-6 py-4">
                                                    Datang
                                                </th>
                                                <th className="px-6 py-4">
                                                    Dhuha
                                                </th>
                                                <th className="px-6 py-4">
                                                    Dzuhur
                                                </th>
                                                <th className="px-6 py-4">
                                                    Pulang
                                                </th>
                                                <th className="px-6 py-4">
                                                    Status
                                                </th>
                                                <th className="px-6 py-4">
                                                    RFID
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody className="divide-y divide-slate-100 bg-white/40">
                                            {attendance.data.map(
                                                (student, idx) => (
                                                    <motion.tr
                                                        key={student.id}
                                                        initial={{
                                                            opacity: 0,
                                                            y: 12,
                                                        }}
                                                        whileInView={{
                                                            opacity: 1,
                                                            y: 0,
                                                        }}
                                                        viewport={{
                                                            once: true,
                                                        }}
                                                        transition={{
                                                            ...motionTheme.normal,
                                                            delay: Math.min(
                                                                idx * 0.03,
                                                                0.3,
                                                            ),
                                                        }}
                                                        className="transition-colors hover:bg-emerald-50/40"
                                                    >
                                                        <td className="px-6 py-4 font-mono text-sm text-slate-600">
                                                            {student.nisn}
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            <div>
                                                                <p className="font-bold text-slate-900">
                                                                    {
                                                                        student.nama
                                                                    }
                                                                </p>

                                                                <p className="text-xs text-slate-500">
                                                                    {
                                                                        student.kelas
                                                                    }
                                                                </p>
                                                            </div>
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            {student.jam_datang ??
                                                                "-"}
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            {student.jam_dhuha ??
                                                                "-"}
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            {student.jam_dzuhur ??
                                                                "-"}
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            {student.jam_pulang ??
                                                                "-"}
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            <Badge
                                                                variant={
                                                                    student
                                                                        .status
                                                                        .color
                                                                }
                                                            >
                                                                {
                                                                    student
                                                                        .status
                                                                        .icon
                                                                }{" "}
                                                                {
                                                                    student
                                                                        .status
                                                                        .label
                                                                }
                                                            </Badge>
                                                        </td>

                                                        <td className="px-6 py-4">
                                                            <Badge
                                                                variant={
                                                                    student.rfid
                                                                        .code ===
                                                                    "rfid"
                                                                        ? "primary"
                                                                        : "secondary"
                                                                }
                                                            >
                                                                {
                                                                    student.rfid
                                                                        .label
                                                                }
                                                            </Badge>
                                                        </td>
                                                    </motion.tr>
                                                ),
                                            )}
                                        </tbody>
                                    </table>
                                )}
                            </div>

                            {/* =========================
        Mobile
    ========================= */}

                            <div className="space-y-4 p-4 lg:hidden">
                                {loading ? (
                                    <div className="py-16 text-center text-slate-500">
                                        Memuat data...
                                    </div>
                                ) : attendance.data.length === 0 ? (
                                    <div className="py-20 text-center">
                                        <div className="text-6xl">📡</div>

                                        <h3 className="mt-6 text-xl font-black text-slate-900">
                                            Tidak ada data monitoring.
                                        </h3>

                                        <p className="mt-3 text-slate-500">
                                            Belum terdapat data kehadiran untuk
                                            filter yang dipilih.
                                        </p>
                                    </div>
                                ) : (
                                    attendance.data.map((student) => (
                                        <GlassCard
                                            key={student.id}
                                            className="rounded-3xl border border-white/70 bg-white/60 p-5 backdrop-blur-xl"
                                        >
                                            <div className="flex items-start justify-between">
                                                <div>
                                                    <h3 className="text-lg font-black text-slate-900">
                                                        {student.nama}
                                                    </h3>

                                                    <p className="mt-1 text-sm text-slate-500">
                                                        {student.nisn} •{" "}
                                                        {student.kelas}
                                                    </p>
                                                </div>

                                                <Badge
                                                    variant={
                                                        student.status.color
                                                    }
                                                >
                                                    {student.status.icon}{" "}
                                                    {student.status.label}
                                                </Badge>
                                            </div>

                                            <div className="my-5 border-t border-slate-100" />

                                            <div className="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <p className="text-slate-400">
                                                        Datang
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {student.jam_datang ??
                                                            "-"}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p className="text-slate-400">
                                                        Pulang
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {student.jam_pulang ??
                                                            "-"}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p className="text-slate-400">
                                                        Dhuha
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {student.jam_dhuha ??
                                                            "-"}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p className="text-slate-400">
                                                        Dzuhur
                                                    </p>

                                                    <p className="mt-1 font-semibold text-slate-900">
                                                        {student.jam_dzuhur ??
                                                            "-"}
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="my-5 border-t border-slate-100" />

                                            <div className="flex items-center justify-between">
                                                <span className="text-sm text-slate-500">
                                                    Sumber Kehadiran
                                                </span>

                                                <Badge
                                                    variant={
                                                        student.rfid.code ===
                                                        "rfid"
                                                            ? "primary"
                                                            : "secondary"
                                                    }
                                                >
                                                    {student.rfid.label}
                                                </Badge>
                                            </div>
                                        </GlassCard>
                                    ))
                                )}
                            </div>
                        </GlassCard>
                    </Container>
                </section>
                {/* =======================================================
    PAGINATION
======================================================= */}

                {attendance.last_page > 1 && (
                    <div className="mt-12 flex justify-center pb-4">
                        <div className="flex flex-wrap items-center gap-2 rounded-2xl border border-white/70 bg-white/50 p-2 shadow-sm backdrop-blur-xl">
                            {attendance.links.map((link, index) => (
                                <button
                                    key={index}
                                    type="button"
                                    disabled={!link.url || loading}
                                    onClick={async () => {
                                        if (!link.url || loading) {
                                            return;
                                        }

                                        setLoading(true);

                                        try {
                                            const { data } = await axios.get(
                                                link.url,
                                            );

                                            setAttendance(data.attendance);

                                            setStatistics(data.statistics);
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            setLoading(false);
                                        }
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
        </FrontendLayout>
    );
}
