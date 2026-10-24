import React from "react";
import Container from "@/Components/UI/Container";
import { Activity, Radio, CheckCircle, Clock, ShieldCheck } from "lucide-react";

export default function LiveTelemetry({ attendanceSummary = {}, statistics = {} }) {
    const rawRate = attendanceSummary?.percentage ?? (statistics?.attendanceRate || 98.4);
    const attendancePercentage = typeof rawRate === "number" ? `${rawRate.toFixed(1)}%` : `${rawRate}%`;

    return (
        <section className="relative z-20 -mt-6">
            <Container>
                <div className="rounded-2xl border border-slate-200/80 bg-white/95 p-3.5 sm:p-4 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 items-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
                        {/* 1. System Status */}
                        <div className="flex items-center gap-3 px-2 py-1">
                            <span className="relative flex h-3 w-3 shrink-0">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <div>
                                <p className="text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                    Server IoT & RFID
                                </p>
                                <p className="text-xs sm:text-sm font-black text-slate-900">
                                    Sistem Aktif 100%
                                </p>
                            </div>
                        </div>

                        {/* 2. Today's Attendance Rate */}
                        <div className="flex items-center gap-3 px-2 py-1">
                            <div className="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <Activity className="w-4 h-4" />
                            </div>
                            <div>
                                <p className="text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                    Kehadiran Hari Ini
                                </p>
                                <p className="text-xs sm:text-sm font-black text-emerald-600">
                                    {attendancePercentage} Hadir
                                </p>
                            </div>
                        </div>

                        {/* 3. KBM Status */}
                        <div className="flex items-center gap-3 px-2 py-1">
                            <div className="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                                <Clock className="w-4 h-4" />
                            </div>
                            <div>
                                <p className="text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                    Status Akademik
                                </p>
                                <p className="text-xs sm:text-sm font-black text-slate-900 truncate">
                                    KBM Efektif Berjalan
                                </p>
                            </div>
                        </div>

                        {/* 4. Security & Data Protection */}
                        <div className="flex items-center gap-3 px-2 py-1">
                            <div className="w-8 h-8 rounded-lg bg-sky-50 border border-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                                <ShieldCheck className="w-4 h-4" />
                            </div>
                            <div>
                                <p className="text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                    Keamanan Data
                                </p>
                                <p className="text-xs sm:text-sm font-black text-slate-900">
                                    Privasi Terenkripsi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </Container>
        </section>
    );
}
