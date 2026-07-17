// resources/js/Components/RFID/IdleScreen.jsx
import { motion } from "motion/react";

import ScanFrame from "./ScanFrame";

export default function IdleScreen({ mode = "school", time, date }) {
    const CONFIG = {
        school: {
            title: (
                <>
                    TEMPELKAN{" "}
                    <span className="text-(--rk-emerald)">KARTU PELAJAR</span>
                </>
            ),
            subtitle:
                "Tempelkan kartu RFID pada reader untuk melakukan absensi sekolah.",
            status: "Reader Absensi Sekolah Siap",
        },

        ibadah: {
            title: (
                <>
                    TEMPELKAN{" "}
                    <span className="text-(--rk-emerald)">KARTU PELAJAR</span>
                </>
            ),
            subtitle:
                "Tempelkan kartu RFID untuk mencatat kehadiran sholat Dhuha atau Dzuhur.",
            status: "Reader Absensi Ibadah Siap",
        },

        ekskul: {
            title: (
                <>
                    TEMPELKAN{" "}
                    <span className="text-(--rk-emerald)">KARTU PELAJAR</span>
                </>
            ),
            subtitle:
                "Tempelkan kartu RFID untuk mencatat kehadiran ekstrakurikuler.",
            status: "Reader Ekstrakurikuler Siap",
        },
    };

    const config = CONFIG[mode] ?? CONFIG.school;
    return (
        <div className="flex h-full min-h-0 flex-1 flex-col items-center justify-center gap-[clamp(0.85rem,2.2vh,2rem)] text-center">
            <div className="relative flex items-center justify-center">
                <div className="rk-spin-slow absolute h-[clamp(10.5rem,29vh,17rem)] w-[clamp(10.5rem,29vh,17rem)] rounded-full border border-dashed border-(--rk-emerald)/15" />
                <div className="absolute h-[clamp(8.5rem,24vh,13.5rem)] w-[clamp(8.5rem,24vh,13.5rem)] rounded-full border border-(--rk-emerald)/10" />

                <ScanFrame
                    size="0.85rem"
                    offset="0.5rem"
                    color="var(--rk-emerald)"
                >
                    <motion.div
                        animate={{ scale: [1, 1.05, 1] }}
                        transition={{
                            duration: 2.4,
                            repeat: Infinity,
                            ease: "easeInOut",
                        }}
                        className="
                            relative
                            flex
                            h-[clamp(6.5rem,19vh,11.5rem)]
                            w-[clamp(6.5rem,19vh,11.5rem)]
                            items-center
                            justify-center
                            overflow-hidden
                            rounded-full
                            border
                            border-(--rk-emerald)/30
                            bg-[radial-gradient(circle_at_50%_40%,rgba(34,230,168,0.18),transparent_70%)]
                        "
                    >
                        <div className="rk-scanline pointer-events-none absolute inset-x-0 h-1/3 bg-linear-to-b from-transparent via-(--rk-emerald)/40 to-transparent" />

                        <svg
                            viewBox="0 0 48 48"
                            fill="none"
                            className="h-[45%] w-[45%] text-(--rk-emerald) drop-shadow-[0_0_14px_var(--rk-emerald)]"
                        >
                            <rect
                                x="4"
                                y="10"
                                width="40"
                                height="28"
                                rx="5"
                                stroke="currentColor"
                                strokeWidth="2.2"
                            />
                            <path
                                d="M15 20a6 6 0 0 1 8 0M12 17a11 11 0 0 1 14 0"
                                stroke="currentColor"
                                strokeWidth="2.2"
                                strokeLinecap="round"
                            />
                            <circle
                                cx="19"
                                cy="26"
                                r="1.6"
                                fill="currentColor"
                            />
                        </svg>
                    </motion.div>
                </ScanFrame>
            </div>

            <div className="flex items-center gap-2.5 rounded-full border border-(--rk-line) bg-(--rk-panel-soft)/60 px-4 py-2">
                <span className="rk-pulse-dot h-1.5 w-1.5 rounded-full bg-(--rk-emerald) shadow-[0_0_10px_var(--rk-emerald)]" />
                <span className="rk-mono text-[clamp(0.6rem,1.5vh,0.9rem)] font-medium uppercase tracking-[0.3em] text-(--rk-emerald)">
                    {config.status}
                </span>
            </div>

            <h1 className="max-w-2xl text-[clamp(1.5rem,5.4vh,3.25rem)] font-bold leading-[1.1] tracking-tight text-(--rk-text)">
                {config.title}
            </h1>

            <p className="max-w-md text-[clamp(0.78rem,1.7vh,1.1rem)] text-(--rk-muted)">
                {config.subtitle}
            </p>

            <div className="mt-[clamp(0.2rem,0.8vh,0.6rem)]">
                <div className="rk-mono text-[clamp(2.25rem,8vh,5.25rem)] font-semibold leading-none tracking-tight text-(--rk-text) [text-shadow:0_0_28px_rgba(34,230,168,0.35)]">
                    {time}
                </div>
                <div className="mt-2 text-[clamp(0.6rem,1.5vh,0.95rem)] uppercase tracking-[0.3em] text-(--rk-muted)">
                    {date}
                </div>
            </div>
        </div>
    );
}
