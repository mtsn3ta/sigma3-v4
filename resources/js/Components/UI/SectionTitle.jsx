import { cn } from "@/Theme";

export default function SectionTitle({
    badge,
    title,
    subtitle,
    align = "center",
    className = "",
}) {
    const alignment =
        align === "center"
            ? "text-center mx-auto flex flex-col items-center"
            : "text-left";

    return (
        <div className={cn("mb-16", alignment, className)}>
            {badge && (
                <span
                    className="
                        mb-4
                        inline-flex
                        rounded-full
                        bg-emerald-50
                        px-4
                        py-1.5
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.2em]
                        text-emerald-700
                    "
                >
                    {badge}
                </span>
            )}

            <h2
                className="
                    text-3xl
                    font-black
                    tracking-tight
                    text-slate-900
                    md:text-4xl
                    lg:text-5xl
                "
            >
                {title}
            </h2>

            {subtitle && (
                <p
                    className="
                        mt-5
                        max-w-2xl
                        text-base
                        font-medium
                        leading-relaxed
                        text-slate-500
                        md:text-lg
                    "
                >
                    {subtitle}
                </p>
            )}
        </div>
    );
}
