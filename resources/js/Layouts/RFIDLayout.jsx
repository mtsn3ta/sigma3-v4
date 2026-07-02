// resources/js/Layouts/RFIDLayout.jsx
import { Head, usePage } from "@inertiajs/react";
import { motion } from "motion/react";

import ScanFrame from "@/Components/RFID/ScanFrame";

export default function RFIDLayout({ title, subtitle, sidebar, children }) {
    const { settings } = usePage().props;

    return (
        <>
            <Head title={title}>
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link
                    rel="preconnect"
                    href="https://fonts.gstatic.com"
                    crossOrigin="anonymous"
                />
                <link
                    href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
                    rel="stylesheet"
                />
                <style>{`
                    html, body {
                        height: 100%;
                        overflow: hidden;
                        overscroll-behavior: none;
                    }
                    .rk-root {
                        --rk-void: #05070c;
                        --rk-panel: #0b1120;
                        --rk-panel-soft: rgba(15, 21, 36, 0.72);
                        --rk-line: rgba(148, 163, 196, 0.16);
                        --rk-emerald: #22e6a8;
                        --rk-gold: #d9b26a;
                        --rk-red: #ff4d6d;
                        --rk-amber: #ffb454;
                        --rk-sky: #4fd1e8;
                        --rk-text: #f3f6fc;
                        --rk-muted: #8b93a8;
                        font-family: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
                    }
                    .rk-mono { font-family: "JetBrains Mono", ui-monospace, monospace; }
                    .rk-grid {
                        background-image:
                            linear-gradient(var(--rk-line) 1px, transparent 1px),
                            linear-gradient(90deg, var(--rk-line) 1px, transparent 1px);
                        background-size: clamp(28px, 3vw, 48px) clamp(28px, 3vw, 48px);
                        -webkit-mask-image: radial-gradient(ellipse 80% 65% at 50% 40%, #000 40%, transparent 85%);
                        mask-image: radial-gradient(ellipse 80% 65% at 50% 40%, #000 40%, transparent 85%);
                    }
                    @keyframes rk-float {
                        0%, 100% { transform: translate3d(0, 0, 0); }
                        50% { transform: translate3d(0, -14px, 0); }
                    }
                    @keyframes rk-pulse {
                        0%, 100% { opacity: 0.45; }
                        50% { opacity: 1; }
                    }
                    @keyframes rk-scan {
                        0% { transform: translateY(-110%); opacity: 0; }
                        10% { opacity: 1; }
                        90% { opacity: 1; }
                        100% { transform: translateY(110%); opacity: 0; }
                    }
                    @keyframes rk-spin-slow {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                    .rk-orb { animation: rk-float 9s ease-in-out infinite; }
                    .rk-pulse-dot { animation: rk-pulse 2s ease-in-out infinite; }
                    .rk-scanline { animation: rk-scan 2.6s linear infinite; }
                    .rk-spin-slow { animation: rk-spin-slow 22s linear infinite; }
                `}</style>
            </Head>

            <main
                className="
                    rk-root
                    fixed
                    inset-0
                    flex
                    h-dvh
                    w-dvw
                    flex-col
                    overflow-hidden
                    bg-(--rk-void)
                    text-(--rk-text)
                    px-[clamp(1rem,2.2vw,2.25rem)]
                    py-[clamp(0.85rem,1.8vh,1.5rem)]
                "
            >
                {/* AMBIENT BACKDROP */}
                <div className="pointer-events-none absolute inset-0">
                    <div className="rk-grid absolute inset-0 opacity-60" />
                    <div className="rk-orb absolute -left-24 -top-24 h-104 w-104 rounded-full bg-(--rk-emerald)/10 blur-[110px]" />
                    <div
                        className="rk-orb absolute -bottom-28 -right-16 h-112 w-md rounded-full bg-(--rk-gold)/10 blur-[130px]"
                        style={{ animationDelay: "2.4s" }}
                    />
                </div>

                {/* BRAND BAR */}
                <div className="relative z-10 flex shrink-0 items-center justify-between pb-[clamp(0.75rem,1.6vh,1.25rem)]">
                    <div className="flex items-center gap-3.5 rounded-2xl border border-(--rk-line) bg-(--rk-panel-soft) py-2.5 pl-2.5 pr-5 backdrop-blur-xl">
                        <div className="relative flex h-[clamp(3rem,7vh,4.25rem)] w-[clamp(3rem,7vh,4.25rem)] items-center justify-center rounded-xl border border-(--rk-line) bg-(--rk-panel)">
                            {settings.logo ? (
                                <img
                                    src={settings.logo}
                                    alt={settings.school_name}
                                    className="h-2/3 w-2/3 object-contain"
                                />
                            ) : (
                                <span className="rk-mono text-lg font-bold text-(--rk-emerald)">
                                    S3
                                </span>
                            )}
                        </div>

                        <div className="text-left">
                            <div className="rk-mono text-[clamp(1.05rem,2.4vh,1.5rem)] font-bold leading-none tracking-[0.3em] text-(--rk-gold)">
                                SIGMA-3
                            </div>
                            <div className="mt-1.5 text-[clamp(0.62rem,1.4vh,0.85rem)] font-medium uppercase leading-none tracking-[0.28em] text-(--rk-muted)">
                                {settings.school_name ??
                                    "Sistem Informasi Global"}
                            </div>
                        </div>
                    </div>

                    <div className="flex items-center gap-4">
                        <div className="hidden text-right sm:block">
                            <p className="text-[clamp(0.65rem,1.5vh,0.85rem)] font-semibold uppercase tracking-[0.35em] text-(--rk-gold)">
                                {title}
                            </p>
                            <p className="text-[clamp(0.58rem,1.25vh,0.75rem)] text-(--rk-muted)">
                                {subtitle}
                            </p>
                        </div>

                        <div className="rk-mono flex items-center gap-2 rounded-full border border-(--rk-line) bg-(--rk-panel-soft) px-4 py-2.5 text-[clamp(0.6rem,1.3vh,0.78rem)] uppercase tracking-[0.25em] text-(--rk-muted) backdrop-blur-xl">
                            <span className="rk-pulse-dot h-1.5 w-1.5 rounded-full bg-(--rk-emerald) shadow-[0_0_10px_var(--rk-emerald)]" />
                            Aktif
                        </div>
                    </div>
                </div>

                {/* MAIN SPLIT STAGE */}
                <div className="relative z-10 grid min-h-0 flex-1 grid-cols-1 gap-[clamp(0.85rem,1.6vw,1.5rem)] lg:grid-cols-[1.8fr_1fr]">
                    {/* LEFT: SCAN STAGE */}
                    <ScanFrame
                        size="clamp(0.9rem,1.5vw,1.5rem)"
                        offset="0.6rem"
                        color="var(--rk-emerald)"
                        className="h-full min-h-0"
                    >
                        <motion.div
                            initial={{ opacity: 0, y: 16 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{
                                duration: 0.6,
                                ease: [0.16, 1, 0.3, 1],
                            }}
                            className="
                                relative
                                flex
                                h-full
                                min-h-0
                                flex-col
                                overflow-hidden
                                rounded-[clamp(1rem,2vw,1.75rem)]
                                border
                                border-(--rk-line)
                                bg-(--rk-panel-soft)
                                px-[clamp(1.1rem,2.6vw,2.5rem)]
                                py-[clamp(0.85rem,1.8vh,1.5rem)]
                                shadow-[0_0_0_1px_rgba(255,255,255,0.03),0_40px_100px_-30px_rgba(0,0,0,0.8)]
                                backdrop-blur-2xl
                            "
                        >
                            {/* decorative radar rings behind content */}
                            <div className="pointer-events-none absolute inset-0 flex items-center justify-center">
                                <div className="h-[62vh] w-[62vh] rounded-full border border-(--rk-emerald)/5" />
                                <div className="absolute h-[44vh] w-[44vh] rounded-full border border-dashed border-(--rk-emerald)/6" />
                            </div>

                            {/* decorative corner ticks */}
                            <span className="pointer-events-none absolute left-4 top-4 h-3 w-3 border-l border-t border-(--rk-line)" />
                            <span className="pointer-events-none absolute right-4 top-4 h-3 w-3 border-r border-t border-(--rk-line)" />
                            <span className="pointer-events-none absolute bottom-4 left-4 h-3 w-3 border-b border-l border-(--rk-line)" />
                            <span className="pointer-events-none absolute bottom-4 right-4 h-3 w-3 border-b border-r border-(--rk-line)" />

                            <div className="relative flex h-full min-h-0 flex-col">
                                {children}
                            </div>
                        </motion.div>
                    </ScanFrame>

                    {/* RIGHT: LAST SCAN SIDEBAR */}
                    <ScanFrame
                        size="clamp(0.9rem,1.5vw,1.5rem)"
                        offset="0.6rem"
                        color="var(--rk-gold)"
                        className="h-full min-h-0"
                    >
                        <div
                            className="
                                flex
                                h-full
                                min-h-0
                                flex-col
                                overflow-hidden
                                rounded-[clamp(1rem,2vw,1.75rem)]
                                border
                                border-(--rk-line)
                                bg-(--rk-panel-soft)
                                px-[clamp(0.9rem,1.6vw,1.5rem)]
                                py-[clamp(0.85rem,1.8vh,1.5rem)]
                                shadow-[0_0_0_1px_rgba(255,255,255,0.03),0_40px_100px_-30px_rgba(0,0,0,0.8)]
                                backdrop-blur-2xl
                            "
                        >
                            {sidebar}
                        </div>
                    </ScanFrame>
                </div>
            </main>
        </>
    );
}
