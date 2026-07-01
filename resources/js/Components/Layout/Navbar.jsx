import { Link, usePage } from "@inertiajs/react";
import { AnimatePresence, motion } from "motion/react";
import { useEffect, useState } from "react";

import { cn, css, glass, motion as motionTheme, radius, shadow } from "@/Theme";

import Button from "../UI/Button";
import Container from "../UI/Container";

const MENUS = [
    {
        title: "Beranda",
        href: "/",
    },
    {
        title: "Fitur",
        href: "#fitur-modul",
    },
    {
        title: "Prestasi",
        href: "#features",
    },
    {
        title: "Monitoring Absensi",
        href: "#monitoring",
    },
    {
        title: "Student Intelligence",
        href: "#student-intelligence",
    },
];

const SECTIONS = {
    prestasi: "Prestasi",
    monitoring: "Monitoring Absensi",
    "student-intelligence": "Student Intelligence",
};

export default function Navbar({ hasUrgentAlert = false }) {
    const { settings } = usePage().props;

    const [mobileOpen, setMobileOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const [active, setActive] = useState("Beranda");

    // Efek untuk mendeteksi posisi scroll dan menu aktif
    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 20);

            let current = "Beranda";

            Object.entries(SECTIONS).forEach(([id, title]) => {
                const section = document.getElementById(id);

                if (!section) return;

                if (window.scrollY >= section.offsetTop - 160) {
                    current = title;
                }
            });

            setActive(current);
        };

        window.addEventListener("scroll", handleScroll);

        handleScroll();

        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    // Efek untuk mengunci scroll body saat mobile menu terbuka
    useEffect(() => {
        if (mobileOpen) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "unset";
        }
        return () => {
            document.body.style.overflow = "unset";
        };
    }, [mobileOpen]);

    const navbarClass = cn(
        glass.default,
        radius.xl,
        css.transition,
        scrolled ? shadow.medium : shadow.none,
    );
    const topOffset = hasUrgentAlert ? 72 : 0;

    return (
        <>
            <header
                className={cn(
                    "fixed inset-x-0 z-50 transition-all duration-300",
                    hasUrgentAlert ? "top-18" : "top-2",
                )}
            >
                <Container>
                    <motion.nav
                        initial={false}
                        animate={{
                            height: scrolled ? 64 : 80,
                        }}
                        transition={motionTheme.normal}
                        className={cn(
                            navbarClass,
                            "flex items-center justify-between border px-6 md:px-8",
                        )}
                    >
                        {/* ==========================
                            BRAND
                        ========================== */}

                        <Link
                            href="/"
                            className="group flex shrink-0 items-center gap-4"
                        >
                            {settings.logo ? (
                                <img
                                    src={settings.logo}
                                    alt={settings.school_name}
                                    className={cn(
                                        css.transition,
                                        "h-10 w-10 object-contain group-hover:scale-105 md:h-11 md:w-11",
                                    )}
                                />
                            ) : (
                                <div
                                    className={cn(
                                        radius.full,
                                        "h-10 w-10 bg-linear-to-tr from-slate-200 to-slate-100 md:h-11 md:w-11",
                                    )}
                                />
                            )}

                            <div className="hidden leading-tight sm:block">
                                <div className="text-[16px] font-black uppercase tracking-widest text-slate-900 md:text-[18px]">
                                    SIGMA-3
                                </div>

                                <div className="text-[9px] font-bold uppercase tracking-[0.25em] text-slate-500 md:text-[10px]">
                                    Sistem Informasi Global
                                </div>
                            </div>
                        </Link>

                        {/* ==========================
                            DESKTOP MENU
                        ========================== */}

                        <div
                            className={cn(
                                glass.subtle,
                                radius.full,
                                shadow.low,
                                "hidden items-center gap-2 p-1.5 xl:flex",
                            )}
                        >
                            {MENUS.map((menu) => {
                                const isActive = active === menu.title;

                                return (
                                    <Link
                                        key={menu.title}
                                        href={menu.href}
                                        className={cn(
                                            css.transition,
                                            "relative z-10 px-5 py-2 text-sm font-semibold",
                                            isActive
                                                ? "text-slate-900"
                                                : "text-slate-600 hover:text-slate-900",
                                        )}
                                    >
                                        {menu.title}

                                        {isActive && (
                                            <motion.div
                                                layoutId="navbar-active-pill"
                                                transition={motionTheme.fast}
                                                className={cn(
                                                    radius.full,
                                                    shadow.low,
                                                    "absolute inset-0 -z-10 bg-white",
                                                )}
                                            />
                                        )}
                                    </Link>
                                );
                            })}
                        </div>
                        {/* ==========================
                            RIGHT ACTION
                        ========================== */}

                        <div className="flex items-center gap-3">
                            <Button
                                href="/admin/login"
                                variant="primary"
                                className="hidden h-10 px-6 md:inline-flex"
                            >
                                Login
                            </Button>

                            <button
                                type="button"
                                aria-label="Buka menu navigasi"
                                aria-expanded={mobileOpen}
                                onClick={() => setMobileOpen((value) => !value)}
                                className={cn(
                                    glass.default,
                                    radius.full,
                                    shadow.low,
                                    css.transition,
                                    "flex h-10 w-10 items-center justify-center xl:hidden",
                                )}
                            >
                                <motion.div
                                    animate={{
                                        rotate: mobileOpen ? 90 : 0,
                                    }}
                                    transition={motionTheme.fast}
                                    className="text-lg text-slate-800"
                                >
                                    {mobileOpen ? "✕" : "☰"}
                                </motion.div>
                            </button>
                        </div>
                    </motion.nav>
                </Container>
            </header>

            {/* ==========================
                MOBILE DRAWER
            ========================== */}

            <AnimatePresence>
                {mobileOpen && (
                    <>
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={motionTheme.fast}
                            onClick={() => setMobileOpen(false)}
                            style={{
                                top: topOffset,
                            }}
                            className="
    fixed
    inset-x-0
    bottom-0
    z-40
    bg-slate-900/20
    backdrop-blur-sm
"
                        />

                        <motion.aside
                            initial={{
                                x: "100%",
                                opacity: 0.5,
                            }}
                            animate={{
                                x: 0,
                                opacity: 1,
                            }}
                            exit={{
                                x: "100%",
                                opacity: 0.5,
                            }}
                            transition={motionTheme.normal}
                            style={{
                                top: topOffset,
                                height: `calc(100vh - ${topOffset}px)`,
                            }}
                            className={cn(
                                glass.strong,
                                radius.xl,
                                shadow.high,
                                "fixed right-0 z-50 flex w-full max-w-[320px] flex-col border-l p-8",
                            )}
                        >
                            <div className="mt-4 mb-12">
                                <div className="text-2xl font-black tracking-tight text-slate-900">
                                    SIGMA-3
                                </div>

                                <div className="mt-1 text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">
                                    Sistem Informasi Global
                                </div>

                                <div className="mt-3 text-sm font-medium text-slate-700">
                                    {settings.school_name}
                                </div>
                            </div>

                            <div className="flex flex-col gap-2">
                                {MENUS.map((menu) => {
                                    const isActive = active === menu.title;

                                    return (
                                        <Link
                                            key={menu.title}
                                            href={menu.href}
                                            onClick={() => setMobileOpen(false)}
                                            className={cn(
                                                css.transition,
                                                radius.lg,
                                                "px-4 py-3 text-lg font-semibold",
                                                isActive
                                                    ? cn(
                                                          glass.subtle,
                                                          shadow.low,
                                                          "text-slate-900",
                                                      )
                                                    : "text-slate-600 hover:bg-white/40 hover:text-slate-900",
                                            )}
                                        >
                                            {menu.title}
                                        </Link>
                                    );
                                })}
                            </div>

                            <div className="mt-auto pb-4">
                                <Button
                                    href="/admin/login"
                                    variant="primary"
                                    className="w-full py-4 text-lg"
                                >
                                    Masuk ke Dashboard
                                </Button>
                            </div>
                        </motion.aside>
                    </>
                )}
            </AnimatePresence>
        </>
    );
}
