import { motion } from "motion/react";

import Button from "./Button";
import GlassCard from "./GlassCard";

import { cn, motion as motionTheme } from "@/Theme";

export default function ErrorPage({
    code = 404,
    title = "Halaman Tidak Ditemukan",
    description = "Halaman yang Anda cari mungkin telah dipindahkan atau sudah tidak tersedia.",
    buttonText = "Kembali ke Beranda",
    buttonHref = "/",
    showBackButton = true,
}) {
    return (
        <div
            className="
                flex
                min-h-screen
                items-center
                justify-center
                bg-slate-50
                px-6
                py-12
            "
        >
            <motion.div
                initial={{
                    opacity: 0,
                    y: 30,
                }}
                animate={{
                    opacity: 1,
                    y: 0,
                }}
                transition={motionTheme.normal}
                className="w-full max-w-2xl"
            >
                <GlassCard
                    className="
                        text-center
                    "
                >
                    <motion.div
                        initial={{
                            scale: 0.9,
                            opacity: 0,
                        }}
                        animate={{
                            scale: 1,
                            opacity: 1,
                        }}
                        transition={{
                            ...motionTheme.normal,
                            delay: 0.1,
                        }}
                    >
                        <div
                            className="
                                text-8xl
                                font-black
                                tracking-tight
                                text-emerald-600
                                md:text-9xl
                            "
                        >
                            {code}
                        </div>

                        <h1
                            className="
                                mt-6
                                text-3xl
                                font-black
                                tracking-tight
                                text-slate-900
                                md:text-4xl
                            "
                        >
                            {title}
                        </h1>

                        <p
                            className="
                                mx-auto
                                mt-5
                                max-w-xl
                                text-base
                                leading-8
                                text-slate-500
                            "
                        >
                            {description}
                        </p>

                        <div
                            className="
                                mt-10
                                flex
                                flex-wrap
                                items-center
                                justify-center
                                gap-4
                            "
                        >
                            <Button href={buttonHref} variant="primary">
                                {buttonText}
                            </Button>

                            {showBackButton && (
                                <Button
                                    variant="outline"
                                    onClick={() => window.history.back()}
                                >
                                    Halaman Sebelumnya
                                </Button>
                            )}
                        </div>
                    </motion.div>
                </GlassCard>
            </motion.div>
        </div>
    );
}
