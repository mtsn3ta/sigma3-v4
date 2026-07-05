import { useForm } from "@inertiajs/react";
import { motion } from "motion/react";

import FrontendLayout from "@/Layouts/FrontendLayout";
import Container from "@/Components/UI/Container";
import Button from "@/Components/UI/Button";
import { cn, glass, radius, shadow, motion as motionTheme } from "@/Theme";

export default function Login() {
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
            <div className="flex min-h-screen items-center justify-center bg-linear-to-br from-teal-50 via-sky-50 to-emerald-50 px-4 py-24">
                <Container>
                    <div className="flex justify-center">
                        <motion.div
                            initial={{ opacity: 0, y: 16 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={motionTheme.normal}
                            className={cn(
                                glass.default,
                                radius.xl,
                                shadow.medium,
                                "w-full max-w-md border p-10",
                            )}
                        >
                            <div className="mb-8 text-center">
                                <h1 className="text-3xl font-black tracking-tight text-slate-900">
                                    Masuk
                                </h1>
                                <p className="mt-2 text-sm font-medium text-slate-500">
                                    SIGMA-3 &middot; Sistem Informasi Global
                                </p>
                            </div>

                            <form onSubmit={submit} className="space-y-5">
                                <div>
                                    <label
                                        htmlFor="username"
                                        className="mb-1 block text-sm font-semibold text-slate-700"
                                    >
                                        Username
                                    </label>
                                    <input
                                        id="username"
                                        type="text"
                                        autoFocus
                                        autoComplete="username"
                                        value={data.username}
                                        onChange={(e) =>
                                            setData("username", e.target.value)
                                        }
                                        className="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm text-slate-900 shadow-inner focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/15"
                                    />
                                    {errors.username && (
                                        <p className="mt-1 text-sm text-red-600">
                                            {errors.username}
                                        </p>
                                    )}
                                </div>

                                <div>
                                    <label
                                        htmlFor="password"
                                        className="mb-1 block text-sm font-semibold text-slate-700"
                                    >
                                        Password
                                    </label>
                                    <input
                                        id="password"
                                        type="password"
                                        autoComplete="current-password"
                                        value={data.password}
                                        onChange={(e) =>
                                            setData("password", e.target.value)
                                        }
                                        className="w-full rounded-xl border border-slate-200 bg-white/80 px-4 py-3 text-sm text-slate-900 shadow-inner focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/15"
                                    />
                                    {errors.password && (
                                        <p className="mt-1 text-sm text-red-600">
                                            {errors.password}
                                        </p>
                                    )}
                                </div>

                                <label className="flex items-center gap-2 text-sm font-medium text-slate-600">
                                    <input
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={(e) =>
                                            setData(
                                                "remember",
                                                e.target.checked,
                                            )
                                        }
                                        className="rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                                    />
                                    Ingat saya
                                </label>

                                <Button
                                    type="submit"
                                    variant="primary"
                                    disabled={processing}
                                    className="w-full py-4 text-base"
                                >
                                    {processing ? "Memproses..." : "Masuk"}
                                </Button>
                            </form>
                        </motion.div>
                    </div>
                </Container>
            </div>
        </FrontendLayout>
    );
}
