// resources/js/Components/RFID/SuccessScreen.jsx
import { motion } from "motion/react";

import ScanFrame from "./ScanFrame";
import Avatar from "@/Components/UI/Avatar";

export default function SuccessScreen({ data }) {
    if (!data?.student) {
        return null;
    }

    const { student, action, message } = data;

    return (
        <motion.div
            initial={{ opacity: 0, scale: 0.97 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.3 }}
            className="flex h-full min-h-0 flex-1 flex-col items-center justify-center gap-[clamp(0.4rem,1.1vh,0.9rem)] text-center"
        >
            <motion.div
                initial={{ scale: 0 }}
                animate={{ scale: 1 }}
                transition={{ delay: 0.1, type: "spring", stiffness: 240 }}
                className="
                    flex
                    h-[clamp(3.75rem,11.5vh,6.5rem)]
                    w-[clamp(3.75rem,11.5vh,6.5rem)]
                    items-center
                    justify-center
                    rounded-full
                    border
                    border-(--rk-emerald)/40
                    bg-[radial-gradient(circle_at_50%_35%,rgba(34,230,168,0.28),transparent_70%)]
                "
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    className="h-[45%] w-[45%] text-(--rk-emerald) drop-shadow-[0_0_14px_var(--rk-emerald)]"
                >
                    <path
                        d="M4 12.5l5 5L20 6.5"
                        stroke="currentColor"
                        strokeWidth="2.6"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                    />
                </svg>
            </motion.div>

            <p className="rk-mono text-[clamp(0.62rem,1.6vh,0.95rem)] font-semibold uppercase tracking-[0.35em] text-(--rk-emerald)">
                {action === "checkout"
                    ? "Absen Berhasil"
                    : "Hati-Hati Di Jalan"}
            </p>

            <ScanFrame size="0.7rem" offset="0.45rem" color="var(--rk-emerald)">
                <Avatar
                    name={student.nama}
                    photo={student.photo}
                    className="
                        h-[clamp(5.25rem,17vh,9rem)]
                        w-[clamp(5.25rem,17vh,9rem)]
                        rounded-3xl
                        border
                        border-(--rk-line)]
                        shadow-2xl
                    "
                />
            </ScanFrame>

            <motion.h1
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.15 }}
                className="text-[clamp(1.35rem,4.5vh,2.6rem)] font-bold text-(--rk-text)"
            >
                {student.nama}
            </motion.h1>

            <p className="text-[clamp(0.85rem,1.9vh,1.2rem)] font-medium text-(--rk-muted)">
                {student.kelas}
            </p>

            <p className="text-[clamp(0.78rem,1.65vh,1.05rem)] font-medium text-(--rk-emerald)">
                {message}
            </p>

            <div className="mt-[clamp(0.2rem,0.6vh,0.5rem)] rounded-2xl border border-(--rk-line) bg-(--rk-panel)/50 px-7 py-2.5">
                <div className="text-[0.58rem] font-semibold uppercase tracking-[0.3em] text-(--rk-muted)">
                    Waktu Scan
                </div>
                <div className="rk-mono mt-0.5 text-[clamp(1.2rem,3.1vh,1.85rem)] font-bold text-(--rk-emerald)">
                    {student.jam}
                </div>
            </div>
        </motion.div>
    );
}
