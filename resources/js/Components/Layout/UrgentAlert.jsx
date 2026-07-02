import { Link, usePage } from "@inertiajs/react";
import { AnimatePresence, motion } from "motion/react";
import { useEffect, useState } from "react";

import { cn, css, motion as motionTheme, radius } from "@/Theme";

// Gaya Kaca Berwarna (Tinted Glass) khas Apple
const TYPE_STYLES = {
    info: {
        icon: "ℹ",
        className: "bg-sky-300/15 border-sky-400/30 text-sky-900",
        btnHover: "hover:bg-sky-400/20",
    },
    success: {
        icon: "✓",
        className: "bg-emerald-300/15 border-emerald-400/30 text-emerald-900",
        btnHover: "hover:bg-emerald-400/20",
    },
    warning: {
        icon: "⚠",
        className: "bg-amber-300/15 border-amber-400/30 text-amber-900",
        btnHover: "hover:bg-amber-400/20",
    },
    danger: {
        icon: "⛔",
        className: "bg-rose-300/15 border-rose-400/30 text-rose-900",
        btnHover: "hover:bg-rose-400/20",
    },
};

export default function UrgentAlert({ visible, onClose }) {
    const { urgentAnnouncements = [] } = usePage().props;
    const [index, setIndex] = useState(0);

    useEffect(() => {
        if (urgentAnnouncements.length <= 1) return;

        const interval = setInterval(() => {
            setIndex((current) =>
                current === urgentAnnouncements.length - 1 ? 0 : current + 1,
            );
        }, 5000);

        return () => clearInterval(interval);
    }, [urgentAnnouncements]);

    if (!visible) {
        return null;
    }

    const announcement = urgentAnnouncements[index];
    const style = TYPE_STYLES[announcement?.tipe] ?? TYPE_STYLES.info;

    return (
        <AnimatePresence mode="wait">
            <motion.div
                key={announcement.id}
                initial={{
                    opacity: 0,
                    y: -100, // Mulai dari luar layar atas
                }}
                animate={{
                    opacity: 1,
                    y: 0,
                }}
                exit={{
                    opacity: 0,
                    y: -100,
                }}
                transition={motionTheme.normal}
                className={cn(
                    "fixed inset-x-0 top-0 z-60 border-b backdrop-blur-2xl backdrop-saturate-150",
                    style.className,
                )}
            >
                <div className="mx-auto flex h-14 min-h-14 max-w-350 items-center justify-between gap-4 px-6 md:px-8">
                    {/* ==========================
                        LEFT: ICON & TEXT
                    ========================== */}
                    <div className="flex min-w-0 items-center gap-3">
                        <div className="flex h-6 w-6 items-center justify-center rounded-full bg-white/40 text-sm shadow-sm backdrop-blur-md">
                            {style.icon}
                        </div>

                        <div className="truncate text-sm font-semibold tracking-wide">
                            {announcement.judul}
                        </div>
                    </div>

                    {/* ==========================
                        RIGHT: ACTION & CLOSE
                    ========================== */}
                    <div className="flex shrink-0 items-center gap-2">
                        <Link
                            href={`/pengumuman/${announcement.id}`}
                            className={cn(
                                css.transition,
                                radius.full,
                                "hidden px-4 py-1.5 text-xs font-bold uppercase tracking-wider",
                                "bg-white/40 shadow-sm hover:bg-white/60 md:inline-flex",
                                "border border-white/20",
                            )}
                        >
                            Lihat Detail
                        </Link>

                        <button
                            type="button"
                            onClick={onClose}
                            aria-label="Tutup pengumuman"
                            className={cn(
                                css.transition,
                                radius.full,
                                style.btnHover,
                                "flex h-8 w-8 items-center justify-center text-sm font-medium",
                            )}
                        >
                            ✕
                        </button>
                    </div>
                </div>
            </motion.div>
        </AnimatePresence>
    );
}
