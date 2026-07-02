// resources/js/Components/RFID/ErrorScreen.jsx
import { motion } from "motion/react";

import ScanFrame from "./ScanFrame";
import Avatar from "@/Components/UI/Avatar";

const ICONS = {
    unknown_card: (
        <path
            d="M6 6l12 12M18 6L6 18"
            stroke="currentColor"
            strokeWidth="2.4"
            strokeLinecap="round"
        />
    ),
    already_scanned: (
        <path
            d="M12 4l9 16H3l9-16zM12 10v4m0 3h.01"
            stroke="currentColor"
            strokeWidth="2.2"
            strokeLinecap="round"
            strokeLinejoin="round"
        />
    ),
    outside_time: (
        <>
            <circle
                cx="12"
                cy="12"
                r="8.5"
                stroke="currentColor"
                strokeWidth="2.2"
            />
            <path
                d="M12 7.5V12l3 2"
                stroke="currentColor"
                strokeWidth="2.2"
                strokeLinecap="round"
                strokeLinejoin="round"
            />
        </>
    ),
    checkin_required: (
        <>
            <path
                d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"
                stroke="currentColor"
                strokeWidth="2.1"
                strokeLinejoin="round"
            />
            <circle
                cx="12"
                cy="9.5"
                r="2.3"
                stroke="currentColor"
                strokeWidth="2.1"
            />
        </>
    ),
    system_error: (
        <path
            d="M13 3L4 14h6l-1 7 9-11h-6l1-7z"
            stroke="currentColor"
            strokeWidth="2.1"
            strokeLinejoin="round"
            strokeLinecap="round"
        />
    ),
};

const TYPES = {
    unknown_card: { color: "var(--rk-red)", title: "KARTU TIDAK TERDAFTAR" },
    already_scanned: {
        color: "var(--rk-amber)",
        title: "SUDAH MELAKUKAN SCAN",
    },
    outside_time: {
        color: "var(--rk-amber)",
        title: "DI LUAR JAM OPERASIONAL",
    },
    checkin_required: {
        color: "var(--rk-sky)",
        title: "SCAN DATANG TERLEBIH DAHULU",
    },
    system_error: { color: "var(--rk-red)", title: "TERJADI KESALAHAN SISTEM" },
};

export default function ErrorScreen({ type = "system_error", student = null }) {
    const style = TYPES[type] ?? TYPES.system_error;
    const icon = ICONS[type] ?? ICONS.system_error;

    return (
        <motion.div
            initial={{ opacity: 0, scale: 0.97 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.25 }}
            className="flex h-full min-h-0 flex-1 flex-col items-center justify-center gap-[clamp(0.4rem,1.1vh,0.9rem)] text-center"
        >
            <div
                className="flex h-[clamp(3.75rem,11.5vh,6.5rem)] w-[clamp(3.75rem,11.5vh,6.5rem)] items-center justify-center rounded-full border"
                style={{
                    borderColor: `${style.color}66`,
                    background: `radial-gradient(circle at 50% 35%, ${style.color}33, transparent 70%)`,
                }}
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    className="h-[42%] w-[42%]"
                    style={{
                        color: style.color,
                        filter: `drop-shadow(0 0 12px ${style.color})`,
                    }}
                >
                    {icon}
                </svg>
            </div>

            <h1
                className="text-[clamp(1.2rem,3.8vh,2.35rem)] font-bold tracking-tight"
                style={{ color: style.color }}
            >
                {style.title}
            </h1>

            {student && (
                <>
                    <ScanFrame
                        size="0.7rem"
                        offset="0.45rem"
                        color={style.color}
                    >
                        <Avatar
                            name={student.nama}
                            photo={student.photo}
                            className="
                                h-[clamp(4.75rem,14.5vh,7.75rem)]
                                w-[clamp(4.75rem,14.5vh,7.75rem)]
                                rounded-3xl
                                border
                                border-(--rk-line)
                                shadow-2xl
                            "
                        />
                    </ScanFrame>

                    <h2 className="text-[clamp(1.2rem,3.6vh,2.1rem)] font-bold text-(--rk-text)">
                        {student.nama}
                    </h2>

                    <p className="text-[clamp(0.85rem,1.9vh,1.25rem)] text-(--rk-muted)">
                        {student.kelas}
                    </p>
                </>
            )}
        </motion.div>
    );
}
