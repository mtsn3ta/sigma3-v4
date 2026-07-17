// resources/js/Components/RFID/LastScanList.jsx
import { AnimatePresence, motion } from "motion/react";

import Avatar from "@/Components/UI/Avatar";

export default function LastScanList({ items = [], maxItems = 6 }) {
    const visible = items.slice(0, maxItems);
    const ghostCount = Math.max(maxItems - visible.length, 0);

    return (
        <div className="flex h-full min-h-0 flex-col">
            <div className="mb-[clamp(0.6rem,1.5vh,1.1rem)] flex shrink-0 items-center justify-between">
                <h3 className="text-[clamp(0.6rem,1.3vh,0.8rem)] font-semibold uppercase tracking-[0.35em] text-(--rk-muted)">
                    Scan Terakhir
                </h3>
                <span className="rk-mono text-[clamp(0.55rem,1.1vh,0.7rem)] text-(--rk-muted)">
                    {String(visible.length).padStart(2, "0")} /{" "}
                    {String(maxItems).padStart(2, "0")}
                </span>
            </div>

            <div className="grid min-h-0 flex-1 auto-rows-fr gap-[clamp(0.4rem,1.1vh,0.85rem)] overflow-hidden">
                <AnimatePresence initial={false}>
                    {visible.map((student, index) => {
                        const isCheckout = student.action === "checkout";
                        const accent = isCheckout
                            ? "var(--rk-sky)"
                            : "var(--rk-emerald)";

                        return (
                            <motion.div
                                key={`${student.id}-${student.jam}`}
                                layout
                                initial={{ opacity: 0, x: 16 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, scale: 0.96 }}
                                transition={{ duration: 0.25 }}
                                className="
                                    relative
                                    flex
                                    min-h-0
                                    items-center
                                    gap-3
                                    overflow-hidden
                                    rounded-2xl
                                    border
                                    border-(--rk-line)
                                    bg-(--rk-panel)/55
                                    px-[clamp(0.6rem,1vw,1rem)]
                                    backdrop-blur-xl
                                "
                                style={
                                    index === 0
                                        ? {
                                              boxShadow: `0 0 0 1px ${accent}55, 0 0 20px -6px ${accent}66`,
                                          }
                                        : undefined
                                }
                            >
                                <span
                                    className="absolute inset-y-0 left-0 w-1"
                                    style={{
                                        background: accent,
                                        boxShadow: `0 0 10px ${accent}`,
                                    }}
                                />

                                <Avatar
                                    name={student.nama}
                                    photo={student.photo}
                                    className="
                                        h-[clamp(2.1rem,5.5vh,3.25rem)]
                                        w-[clamp(2.1rem,5.5vh,3.25rem)]
                                        shrink-0
                                        rounded-xl
                                        border
                                        border-(--rk-line)
                                    "
                                />

                                <div className="min-w-0 flex-1 text-left">
                                    <h4 className="truncate text-[clamp(0.78rem,1.7vh,1.05rem)] font-bold text-(--rk-text)">
                                        {student.nama}
                                    </h4>
                                    <p className="truncate text-[clamp(0.62rem,1.2vh,0.8rem)] text-(--rk-muted)">
                                        {student.kelas}
                                    </p>
                                </div>

                                <div className="shrink-0 text-right">
                                    <div
                                        className="mb-1 inline-flex rounded-full px-2 py-0.5 text-[0.55rem] font-bold uppercase tracking-wider"
                                        style={{
                                            color: accent,
                                            background: `${accent}1f`,
                                        }}
                                    >
                                        {isCheckout ? "Pulang" : "Datang"}
                                    </div>
                                    <div className="rk-mono text-[clamp(0.82rem,1.7vh,1.15rem)] font-semibold text-(--rk-text)">
                                        {student.jam}
                                    </div>
                                </div>
                            </motion.div>
                        );
                    })}
                </AnimatePresence>

                {Array.from({ length: ghostCount }).map((_, i) => (
                    <div
                        key={`ghost-${i}`}
                        className="flex min-h-0 items-center justify-center gap-3 rounded-2xl border border-dashed border-(--rk-line) bg-(--rk-panel)/20 px-4"
                        style={{ opacity: 0.55 - i * 0.06 }}
                    >
                        <span className="h-1.5 w-1.5 rounded-full bg-(--rk-muted)/40" />
                        <span className="rk-mono text-[clamp(0.58rem,1.15vh,0.72rem)] uppercase tracking-[0.25em] text-(--rk-muted)/70">
                            Menunggu scan
                        </span>
                    </div>
                ))}
            </div>
        </div>
    );
}
