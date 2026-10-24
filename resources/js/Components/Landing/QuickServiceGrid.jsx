import React from "react";
import { Link } from "@inertiajs/react";
import Container from "@/Components/UI/Container";
import {
    Activity,
    BookOpen,
    CreditCard,
    HeartPulse,
    MessageCircle,
    ArrowUpRight,
    Sparkles,
    ShieldCheck,
    CheckCheck,
    ExternalLink,
} from "lucide-react";
import Button from "@/Components/UI/Button";

export default function QuickServiceGrid() {
    const services = [
        {
            title: "Presensi Real-time",
            desc: "Pencatatan tap RFID gerbang otomatis tanpa antre, tercatat live detik demi detik.",
            icon: Activity,
            href: "/monitoring",
            tag: "IoT Gate",
            color: "text-emerald-600 bg-emerald-50 border-emerald-100",
        },
        {
            title: "Akses Perpustakaan",
            desc: "Identitas satu kartu untuk peminjaman buku perpustakaan & validasi presensi KBM kelas.",
            icon: BookOpen,
            href: "/login",
            tag: "Smart KBM",
            color: "text-teal-600 bg-teal-50 border-teal-100",
        },
        {
            title: "Ibadah & Karakter",
            desc: "Presensi digital Sholat Dhuha dan Dhuhur berjamaah di masjid madrasah.",
            icon: HeartPulse,
            href: "/monitoring",
            tag: "Religius",
            color: "text-emerald-700 bg-emerald-50 border-emerald-100",
        },
        {
            title: "Laporan Orang Tua",
            desc: "Portal transparansi wali murid untuk memantau presensi, keikutsertaan ibadah, dan perizinan.",
            icon: CreditCard,
            href: "/login",
            tag: "Transparan",
            color: "text-sky-600 bg-sky-50 border-sky-100",
        },
    ];

    const waLink = "https://wa.me/62881081297786?text=Assalamualaikum%20Min%20AI%20MTsN%203%20Tulungagung";

    return (
        <section id="fitur-modul" className="py-16 md:py-20 bg-slate-50/60 border-b border-slate-100">
            <Container>
                {/* Section Header */}
                <div className="mb-12 text-center max-w-2xl mx-auto">
                    <span className="inline-block px-3 py-1 rounded-full bg-emerald-100/70 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-3">
                        Ekosistem Layanan Utama
                    </span>
                    <h2 className="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                        Integrasi Layanan Tanpa Batas
                    </h2>
                    <p className="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        Kemudahan akses informasi madrasah dari gerbang kedatangan, ruang kelas,
                        hingga komunikasi cerdas berbasis Artificial Intelligence di genggaman Anda.
                    </p>
                </div>

                <div className="grid lg:grid-cols-12 gap-6 items-stretch">
                    {/* 4 Clean Quick Service Tiles (8 Cols) */}
                    <div className="lg:col-span-7 grid sm:grid-cols-2 gap-4">
                        {services.map((item, idx) => {
                            const Icon = item.icon;
                            return (
                                <Link
                                    key={idx}
                                    href={item.href}
                                    className="group p-5 rounded-2xl bg-white border border-slate-200/80 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_10px_30px_rgb(0,0,0,0.06)] hover:border-emerald-300 transition-all flex flex-col justify-between"
                                >
                                    <div>
                                        <div className="flex items-center justify-between mb-4">
                                            <div
                                                className={`w-11 h-11 rounded-xl flex items-center justify-center border shadow-xs ${item.color}`}
                                            >
                                                <Icon className="w-5 h-5" />
                                            </div>
                                            <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-100">
                                                {item.tag}
                                            </span>
                                        </div>

                                        <h3 className="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                            {item.title}
                                        </h3>
                                        <p className="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                            {item.desc}
                                        </p>
                                    </div>

                                    <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-700">
                                        <span>Buka Layanan</span>
                                        <ArrowUpRight className="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                                    </div>
                                </Link>
                            );
                        })}
                    </div>

                    {/* WhatsApp AI Assistant Showcase (5 Cols) */}
                    <div className="lg:col-span-5">
                        <div className="h-full rounded-2xl bg-gradient-to-br from-emerald-900 via-emerald-950 to-slate-950 border border-emerald-800/40 p-6 text-white shadow-xl flex flex-col justify-between relative overflow-hidden">
                            {/* Ambient Glow */}
                            <div className="absolute top-0 right-0 w-44 h-44 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none" />

                            <div>
                                {/* Header Mockup */}
                                <div className="flex items-center justify-between pb-4 border-b border-white/10">
                                    <div className="flex items-center gap-2.5">
                                        <div className="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-300 font-black text-sm">
                                            AI
                                        </div>
                                        <div>
                                            <div className="flex items-center gap-1.5">
                                                <span className="text-sm font-bold text-white">Min AI MATSANEGA</span>
                                                <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
                                            </div>
                                            <span className="text-[10px] text-emerald-300 font-medium">
                                                Asisten Virtual Resmi 24 Jam
                                            </span>
                                        </div>
                                    </div>
                                    <span className="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300">
                                        WhatsApp Bot
                                    </span>
                                </div>

                                {/* Chat Bubbles Preview */}
                                <div className="my-5 space-y-3 text-xs">
                                    {/* User message */}
                                    <div className="flex justify-end">
                                        <div className="max-w-[85%] rounded-2xl rounded-tr-xs bg-emerald-700/80 p-3 text-white shadow-xs">
                                            <p>Besok anak-anak seragamnya apa ya min?</p>
                                            <span className="text-[9px] text-emerald-200 mt-1 flex items-center justify-end gap-1">
                                                20:15 <CheckCheck className="w-3 h-3 text-emerald-300" />
                                            </span>
                                        </div>
                                    </div>

                                    {/* Bot message */}
                                    <div className="flex justify-start">
                                        <div className="max-w-[90%] rounded-2xl rounded-tl-xs bg-white/10 backdrop-blur-md border border-white/15 p-3 text-slate-100 shadow-xs">
                                            <p className="leading-relaxed">
                                                Halo Bapak/Ibu! Besok hari Senin mengenakan seragam <strong className="text-emerald-300 font-semibold">Pramuka Lengkap</strong>. Pastikan putra/putri membawa kartu RFID untuk presensi gerbang & sholat dhuha ya! 😊
                                            </p>
                                            <span className="text-[9px] text-slate-400 mt-1.5 block">
                                                Dijawab instan oleh Min AI
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* CTA Action */}
                            <div className="pt-4 border-t border-white/10">
                                <a
                                    href={waLink}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="w-full py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-emerald-500/20 group"
                                >
                                    <MessageCircle className="w-4 h-4 fill-slate-950" />
                                    <span>Tanya Informasi via WhatsApp</span>
                                    <ExternalLink className="w-3.5 h-3.5 transition-transform group-hover:scale-110" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </Container>
        </section>
    );
}
