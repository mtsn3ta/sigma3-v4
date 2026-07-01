import { cn, glass, radius, shadow } from "@/Theme";

const variants = {
    primary: "border-emerald-500/20 bg-emerald-50/60 text-emerald-700",

    neutral: "border-slate-300/40 bg-white/60 text-slate-700",

    success: "border-green-500/20 bg-green-50/60 text-green-700",

    warning: "border-amber-500/20 bg-amber-50/60 text-amber-700",

    danger: "border-red-500/20 bg-red-50/60 text-red-700",
};

export default function Badge({
    children,
    variant = "primary",
    className = "",
}) {
    return (
        <span
            className={cn(
                "inline-flex items-center px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]",

                radius.full,

                glass.subtle,

                shadow.low,

                variants[variant],

                className,
            )}
        >
            {children}
        </span>
    );
}
