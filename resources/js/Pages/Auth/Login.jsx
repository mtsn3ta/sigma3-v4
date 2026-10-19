import { useForm, Link, Head, usePage } from "@inertiajs/react";
import { motion } from "motion/react";
import { useState } from "react";
import FrontendLayout from "@/Layouts/FrontendLayout";
import Container from "@/Components/UI/Container";
import Button from "@/Components/UI/Button";
import { cn, glass, radius, shadow, motion as motionTheme } from "@/Theme";
import {
    User,
    Lock,
    Eye,
    EyeOff,
    ArrowLeft,
    ArrowRight,
    ShieldCheck,
    MessageCircle,
    AlertCircle,
    Loader2,
} from "lucide-react";

export default function Login() {
    const { settings = {} } = usePage().props;
    const [showPassword, setShowPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        username: "",
        password: "",
        remember: false,
    });

    function submit(e) {
        e.preventDefault();
        post("/login", {
            onFinish: () => reset("password"),
        });
    }

    return (
        <FrontendLayout>
            <Head title={`Masuk Portal - ${settings.school_name || "SIGMA-3"}`} />

            <div className="relative min-h-[90vh] flex items-center justify-center py-20 px-4 overflow-hidden bg-gradient-to-br from-emerald-50/60 via-slate-50 to-teal-50/50">
                {/* Background Ambience */}
                <div className="absolute inset-0 pointer-events-none overflow-hidden">
                    <div className="absolute -top-32 left-1/2 -translate-x-1/2 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl" />
                    <div className="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-cyan-400/15 blur-3xl" />
                </div>

                <Container className="relative z-10 max-w-lg">
                    {/* Top back link */}
                    <div className="mb-6 flex items-center justify-between">
                        <Link
                            href="/"
                            className="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-emerald-700 transition-colors bg-white/70 hover:bg-white px-3.5 py-1.5 rounded-full border border-slate-200/80 shadow-xs backdrop-blur-md"
                        >
                            <ArrowLeft className="w-4 h-4" />
                            <span>Kembali ke Beranda</span>
                        </Link>

                        <div className="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-3 py-1 rounded-full">
                            <span className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" />
                            <span>Portal Terpadu</span>
                        </div>
                    </div>

                    {/* Main Login Card */}
                    <motion.div
                        initial={{ opacity: 0, y: 16 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={motionTheme.normal}
                        className={cn(
                            glass.default,
                            radius.xl,
                            shadow.medium,
                            "w-full border border-white/80 bg-white/75 backdrop-blur-2xl p-7 sm:p-10 rounded-3xl shadow-[0_12px_40px_rgb(0,0,0,0.06)]",
                        )}
                    >
                        {/* Header Branding */}
                        <div className="mb-8 text-center flex flex-col items-center">
                            {settings.logo ? (
                                <div className="relative mb-4">
                                    <div className="absolute inset-0 rounded-2xl bg-emerald-500/20 blur-lg" />
                                    <img
                                        src={settings.logo}
                                        alt={settings.school_name || "Logo"}
                                        className="relative h-16 w-16 object-contain drop-shadow-md"
                                    />
                                </div>
                            ) : (
                                <div className="h-14 w-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-xl mb-4 shadow-lg shadow-emerald-600/30">
                                    S3
                                </div>
                            )}

                            <h1 className="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                                Masuk ke Portal
                            </h1>
                            <p className="mt-1 text-sm font-semibold text-emerald-700">
                                SIGMA-3 &bull; MTsN 3 Tulungagung
                            </p>
                            <p className="mt-1 text-xs text-slate-500 max-w-xs">
                                Akses untuk Guru, Tenaga Kependidikan, dan Siswa
                            </p>
                        </div>

                        {/* Login Form */}
                        <form onSubmit={submit} className="space-y-5">
                            {/* Username Input */}
                            <div>
                                <label
                                    htmlFor="username"
                                    className="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700"
                                >
                                    Username / NIP / NISN
                                </label>
                                <div className="relative">
                                    <User className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input
                                        id="username"
                                        type="text"
                                        autoFocus
                                        autoComplete="username"
                                        placeholder="Masukkan username Anda"
                                        value={data.username}
                                        onChange={(e) =>
                                            setData("username", e.target.value)
                                        }
                                        className={cn(
                                            "w-full rounded-2xl border bg-white/90 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400",
                                            errors.username
                                                ? "border-rose-300 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/15"
                                                : "border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15",
                                        )}
                                    />
                                </div>
                                {errors.username && (
                                    <div className="mt-1.5 flex items-center gap-1.5 text-xs text-rose-600">
                                        <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                                        <span>{errors.username}</span>
                                    </div>
                                )}
                            </div>

                            {/* Password Input */}
                            <div>
                                <label
                                    htmlFor="password"
                                    className="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700"
                                >
                                    Password
                                </label>
                                <div className="relative">
                                    <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input
                                        id="password"
                                        type={showPassword ? "text" : "password"}
                                        autoComplete="current-password"
                                        placeholder="Masukkan kata sandi"
                                        value={data.password}
                                        onChange={(e) =>
                                            setData("password", e.target.value)
                                        }
                                        className={cn(
                                            "w-full rounded-2xl border bg-white/90 pl-11 pr-11 py-3 text-sm text-slate-900 shadow-inner outline-none transition-all duration-200 placeholder:text-slate-400",
                                            errors.password
                                                ? "border-rose-300 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/15"
                                                : "border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15",
                                        )}
                                    />
                                    <button
                                        type="button"
                                        tabIndex={-1}
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
                                        aria-label={showPassword ? "Sembunyikan password" : "Tampilkan password"}
                                    >
                                        {showPassword ? (
                                            <EyeOff className="w-4 h-4" />
                                        ) : (
                                            <Eye className="w-4 h-4" />
                                        )}
                                    </button>
                                </div>
                                {errors.password && (
                                    <div className="mt-1.5 flex items-center gap-1.5 text-xs text-rose-600">
                                        <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                                        <span>{errors.password}</span>
                                    </div>
                                )}
                            </div>

                            {/* Remember me */}
                            <div className="flex items-center justify-between text-xs sm:text-sm">
                                <label className="flex items-center gap-2 font-medium text-slate-600 cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={(e) =>
                                            setData("remember", e.target.checked)
                                        }
                                        className="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer"
                                    />
                                    <span>Ingat saya di perangkat ini</span>
                                </label>
                            </div>

                            {/* Submit Button */}
                            <Button
                                type="submit"
                                variant="primary"
                                disabled={processing}
                                className="w-full py-3.5 text-base font-bold shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer"
                            >
                                {processing ? (
                                    <>
                                        <Loader2 className="w-4 h-4 animate-spin" />
                                        <span>Memproses Masuk...</span>
                                    </>
                                ) : (
                                    <>
                                        <span>Masuk ke Dashboard</span>
                                        <ArrowRight className="w-4 h-4" />
                                    </>
                                )}
                            </Button>
                        </form>

                        {/* Help Footer */}
                        <div className="mt-8 pt-6 border-t border-slate-100 text-center">
                            <p className="text-xs text-slate-500 mb-2">
                                Mengalami kendala saat login atau lupa kata sandi?
                            </p>
                            <a
                                href="https://wa.me/62881081297786?text=Halo%20Admin%20SIGMA-3,%20saya%20butuh%20bantuan%20login"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 transition-colors"
                            >
                                <MessageCircle className="w-3.5 h-3.5 text-emerald-600" />
                                <span>Hubungi Layanan Bantuan CS AI / IT Madrasah</span>
                            </a>
                        </div>
                    </motion.div>

                    {/* Security Notice */}
                    <div className="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500">
                        <ShieldCheck className="w-4 h-4 text-emerald-600" />
                        <span>Koneksi aman terenkripsi &bull; MTsN 3 Tulungagung</span>
                    </div>
                </Container>
            </div>
        </FrontendLayout>
    );
}
