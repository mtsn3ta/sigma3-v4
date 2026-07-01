import { Link, usePage } from "@inertiajs/react";

import { cn, css } from "@/Theme";
import Container from "../UI/Container";

const PORTAL_LINKS = [
    { title: "Prestasi", href: "/prestasi" },
    { title: "Monitoring RFID", href: "/rfid" },
    { title: "Student Intelligence", href: "/student-intelligence" },
];

const SCHOOL_LINKS = [
    { title: "Profil Madrasah", href: "/profil" },
    { title: "Galeri", href: "/galeri" },
    { title: "Kontak", href: "/kontak" },
];

export default function Footer() {
    const { settings } = usePage().props;

    return (
        <footer className="relative mt-20 overflow-hidden bg-[#021a16] text-slate-300">
            {/* ==========================
                FUTURISTIC EDGE & GLOW
            ========================== */}
            {/* Garis atas bercahaya (Apple-style top highlight) */}
            <div className="absolute top-0 left-0 right-0 h-px bg-linear-to-r from-transparent via-emerald-500/30 to-transparent" />

            {/* Bias cahaya lembut dari atas tengah */}
            <div className="absolute -top-24 left-1/2 h-48 w-150 -translate-x-1/2 rounded-full bg-emerald-500/5 blur-[100px] pointer-events-none" />

            <Container className="relative z-10 py-20">
                {/* ==========================
                    BRANDING
                ========================== */}
                <div className="mb-16">
                    <Link
                        href="/"
                        className="group inline-flex items-center gap-5"
                    >
                        {settings.logo ? (
                            <div className="relative">
                                {/* Efek glow di belakang logo saat hover */}
                                <div className="absolute inset-0 rounded-full bg-emerald-500/20 blur-xl transition-opacity opacity-0 group-hover:opacity-100" />
                                <img
                                    src={settings.logo}
                                    alt={settings.school_name}
                                    className={cn(
                                        css.transition,
                                        "relative z-10 h-16 w-16 object-contain drop-shadow-xl group-hover:scale-105",
                                    )}
                                />
                            </div>
                        ) : (
                            <div className="h-16 w-16 rounded-full bg-white/5 border border-white/10 backdrop-blur-md" />
                        )}

                        <div>
                            <h2 className="text-3xl font-black tracking-tight bg-linear-to-r from-white to-emerald-200 bg-clip-text text-transparent">
                                SIGMA-3
                            </h2>

                            <p className="mt-1 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600/80">
                                Sistem Informasi Global
                            </p>

                            <p className="mt-1 text-sm text-slate-400">
                                {settings.school_name}
                            </p>
                        </div>
                    </Link>
                </div>

                {/* ==========================
                    CONTENT LINKS
                ========================== */}
                <div className="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                    {/* PORTAL */}
                    <div>
                        <h3 className="mb-6 text-xs font-bold uppercase tracking-[0.2em] text-white">
                            Portal
                        </h3>
                        <div className="flex flex-col gap-3">
                            {PORTAL_LINKS.map((item) => (
                                <Link
                                    key={item.title}
                                    href={item.href}
                                    className="group flex items-center text-sm font-medium text-slate-400 transition-colors hover:text-emerald-400"
                                >
                                    <span className="inline-block transition-transform duration-300 group-hover:translate-x-1">
                                        {item.title}
                                    </span>
                                </Link>
                            ))}
                        </div>
                    </div>

                    {/* MADRASAH */}
                    <div>
                        <h3 className="mb-6 text-xs font-bold uppercase tracking-[0.2em] text-white">
                            Madrasah
                        </h3>
                        <div className="flex flex-col gap-3">
                            {SCHOOL_LINKS.map((item) => (
                                <Link
                                    key={item.title}
                                    href={item.href}
                                    className="group flex items-center text-sm font-medium text-slate-400 transition-colors hover:text-emerald-400"
                                >
                                    <span className="inline-block transition-transform duration-300 group-hover:translate-x-1">
                                        {item.title}
                                    </span>
                                </Link>
                            ))}
                        </div>
                    </div>

                    {/* KONTAK */}
                    <div>
                        <h3 className="mb-6 text-xs font-bold uppercase tracking-[0.2em] text-white">
                            Kontak
                        </h3>
                        <div className="space-y-5 text-sm text-slate-400">
                            <div>
                                <div className="mb-1 text-[10px] uppercase tracking-widest text-emerald-700">
                                    Alamat
                                </div>
                                <p className="font-medium leading-relaxed text-slate-300">
                                    {settings.school_address}
                                </p>
                            </div>
                            <div>
                                <div className="mb-1 text-[10px] uppercase tracking-widest text-emerald-700">
                                    Telepon
                                </div>
                                <p className="font-medium text-slate-300">
                                    {settings.school_phone}
                                </p>
                            </div>
                            <div>
                                <div className="mb-1 text-[10px] uppercase tracking-widest text-emerald-700">
                                    Email
                                </div>
                                <p className="font-medium text-slate-300">
                                    {settings.school_email}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* QUICK ACCESS */}
                    <div>
                        <h3 className="mb-6 text-xs font-bold uppercase tracking-[0.2em] text-white">
                            Quick Access
                        </h3>
                        <div className="flex flex-col gap-3">
                            {[
                                { title: "Dashboard", href: "/admin/login" },
                                {
                                    title: "Prestasi Terbaru",
                                    href: "/prestasi",
                                },
                                { title: "Galeri Kegiatan", href: "/galeri" },
                            ].map((item) => (
                                <Link
                                    key={item.title}
                                    href={item.href}
                                    className="group flex items-center text-sm font-medium text-slate-400 transition-colors hover:text-emerald-400"
                                >
                                    <span className="inline-block transition-transform duration-300 group-hover:translate-x-1">
                                        {item.title}
                                    </span>
                                </Link>
                            ))}
                        </div>
                    </div>
                </div>

                {/* ==========================
                    BOTTOM BAR
                ========================== */}
                <div className="mt-20 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-xs font-medium text-slate-500 md:flex-row">
                    <p>
                        © {new Date().getFullYear()} {settings.school_name}. Hak
                        Cipta Dilindungi.
                    </p>

                    <p className="flex items-center gap-1.5">
                        Powered by{" "}
                        <span className="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2 py-0.5 font-bold tracking-wider text-emerald-400">
                            IanRizky
                        </span>
                    </p>
                </div>
            </Container>
        </footer>
    );
}
