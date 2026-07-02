import { Head, Link, usePage } from "@inertiajs/react";
import { motion } from "motion/react";

import FrontendLayout from "@/Layouts/FrontendLayout";

import Container from "@/Components/UI/Container";
import Badge from "@/Components/UI/Badge";
import GlassCard from "@/Components/UI/GlassCard";

import { cn, background, motion as motionTheme, spacing } from "@/Theme";

export default function Show() {
    const { settings, announcement, latestAnnouncements } = usePage().props;
    const BADGE_VARIANTS = {
        info: "primary",
        success: "success",
        warning: "warning",
        danger: "danger",
    };
    return (
        <FrontendLayout>
            <Head title={`${announcement.title} - ${settings.school_name}`} />

            <main className={cn(background.gradient, "relative bg-[#f7faf9]")}>
                {/* =======================================================
                    HERO
                ======================================================= */}

                <section
                    className={cn(
                        background.hero,
                        spacing.pageHeroTop,
                        spacing.pageHeroBottom,
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
                                    id="grid-pengumuman"
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
                                fill="url(#grid-pengumuman)"
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
                                    Official Announcement
                                </Badge>
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={motionTheme.normal}
                                className="text-5xl font-black tracking-tight text-slate-900 md:text-7xl"
                            >
                                <span className="bg-linear-to-r from-emerald-500 via-teal-500 to-cyan-500 bg-clip-text text-transparent">
                                    Pengumuman
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
                                Informasi resmi, pengumuman sekolah, dan
                                pemberitahuan penting MTs Negeri 3 Tulungagung.
                            </motion.p>
                        </div>
                    </Container>
                </section>
                <section className={cn("relative z-10", spacing.sectionY)}>
                    <Container>
                        <div className="grid gap-8 lg:grid-cols-[1fr_320px]">
                            <GlassCard className="border border-white/70 bg-white/60 p-8 backdrop-blur-xl">
                                <Badge
                                    variant={
                                        BADGE_VARIANTS[announcement.type] ??
                                        "primary"
                                    }
                                >
                                    {announcement.type_label}
                                </Badge>

                                <h1 className="mt-5 text-4xl font-black text-slate-900">
                                    {announcement.title}
                                </h1>

                                <div className="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                                    <span>📅 {announcement.start_date}</span>

                                    {announcement.extracurricular && (
                                        <>
                                            <span>•</span>

                                            <span>
                                                ⚡{" "}
                                                {announcement.extracurricular}
                                            </span>
                                        </>
                                    )}

                                    {announcement.author && (
                                        <>
                                            <span>•</span>

                                            <span>
                                                👤 {announcement.author}
                                            </span>
                                        </>
                                    )}
                                </div>

                                <div
                                    className="prose prose-slate mt-10 max-w-none"
                                    dangerouslySetInnerHTML={{
                                        __html: announcement.content,
                                    }}
                                />

                                {announcement.attachment && (
                                    <div className="mt-10 border-t border-slate-100 pt-6">
                                        <a
                                            href={announcement.attachment}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            download
                                            className="inline-flex items-center gap-2 rounded-2xl bg-linear-to-r from-emerald-500 to-teal-500 px-5 py-3 font-semibold text-white shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                                        >
                                            <span>📄</span>

                                            <span>Download Lampiran</span>
                                        </a>
                                    </div>
                                )}
                            </GlassCard>
                            <GlassCard className="border border-white/70 bg-white/60 p-6 backdrop-blur-xl">
                                <h3 className="text-lg font-black text-slate-900">
                                    Pengumuman Terbaru
                                </h3>

                                <div className="mt-6 space-y-4">
                                    {latestAnnouncements.length === 0 ? (
                                        <div className="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                                            <div className="text-4xl">📭</div>

                                            <p className="mt-3 text-sm text-slate-500">
                                                Belum ada pengumuman lainnya.
                                            </p>
                                        </div>
                                    ) : (
                                        latestAnnouncements.map((item) => (
                                            <Link
                                                key={item.id}
                                                href={`/pengumuman/${item.id}`}
                                                className="block rounded-2xl border border-slate-100 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40"
                                            >
                                                <Badge
                                                    variant={
                                                        BADGE_VARIANTS[
                                                            item.type
                                                        ] ?? "primary"
                                                    }
                                                >
                                                    {item.type}
                                                </Badge>

                                                <h4 className="mt-3 font-bold text-slate-900">
                                                    {item.title}
                                                </h4>

                                                <p className="mt-2 text-sm text-slate-500">
                                                    {item.date}
                                                </p>
                                            </Link>
                                        ))
                                    )}
                                </div>
                            </GlassCard>
                        </div>
                    </Container>
                </section>
            </main>
        </FrontendLayout>
    );
}
