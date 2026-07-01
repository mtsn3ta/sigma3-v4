import { motion, AnimatePresence } from "motion/react";
import { useEffect, useState } from "react";
import { usePage } from "@inertiajs/react";

import GlassCard from "../UI/GlassCard";

import { cn, motion as motionTheme } from "@/Theme";

const DOTS = [0, 1, 2, 3, 4];

export default function LoadingScreen() {
    const { settings } = usePage().props;

    const [visible, setVisible] = useState(true);

    useEffect(() => {
        const timer = setTimeout(() => {
            setVisible(false);
        }, 900);

        return () => clearTimeout(timer);
    }, []);

    return (
        <AnimatePresence>
            {visible && (
                <motion.div
                    initial={{ opacity: 1 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    transition={motionTheme.normal}
                    className="
                        fixed
                        inset-0
                        z-200
                        flex
                        items-center
                        justify-center
                        bg-slate-50/90
                        backdrop-blur-xl
                    "
                >
                    <GlassCard
                        className="
                            w-full
                            max-w-md
                            text-center
                        "
                    >
                        {settings.logo && (
                            <img
                                src={settings.logo}
                                alt={settings.school_name}
                                className="
                                    mx-auto
                                    h-20
                                    w-20
                                    object-contain
                                    select-none
                                "
                            />
                        )}

                        <h1
                            className="
                                mt-6
                                text-4xl
                                font-black
                                tracking-tight
                                text-slate-900
                            "
                        >
                            SIGMA-3
                        </h1>

                        <p
                            className="
                                mt-2
                                text-sm
                                font-semibold
                                uppercase
                                tracking-[0.25em]
                                text-slate-500
                            "
                        >
                            Sistem Informasi Global
                        </p>

                        <p
                            className="
                                mt-2
                                text-sm
                                text-slate-500
                            "
                        >
                            {settings.school_name}
                        </p>

                        {/* ==========================
                            Loading Indicator
                        ========================== */}

                        <div
                            className="
                                mt-10
                                flex
                                items-center
                                justify-center
                                gap-2
                            "
                        >
                            {DOTS.map((dot) => (
                                <motion.div
                                    key={dot}
                                    animate={{
                                        opacity: [0.3, 1, 0.3],
                                        scale: [0.8, 1.2, 0.8],
                                    }}
                                    transition={{
                                        duration: 1,
                                        repeat: Infinity,
                                        delay: dot * 0.15,
                                    }}
                                    className="
                                        h-3
                                        w-3
                                        rounded-full
                                        bg-emerald-600
                                    "
                                />
                            ))}
                        </div>

                        <p
                            className="
                                mt-8
                                text-sm
                                font-medium
                                text-slate-500
                            "
                        >
                            Memuat Portal Digital...
                        </p>
                    </GlassCard>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
