// resources/js/Components/UI/Badge.jsx
import { cn, glass, radius, shadow } from "@/Theme";

const variants = {
    primary: "border-emerald-500/30 bg-emerald-500/10 text-emerald-300",
    neutral: "border-slate-500/30 bg-slate-500/10 text-slate-300",
    success: "border-green-500/30 bg-green-500/10 text-green-300",
    warning: "border-amber-500/30 bg-amber-500/10 text-amber-300",
    danger: "border-red-500/30 bg-red-500/10 text-red-300",
};

export default function Badge({
    children,
    variant = "primary",
    className = "",
}) {
    return (
        <span
            className={cn(
                "inline-flex items-center px-3 py-1 text-[10px] font-bold uppercase tracking-[0.15em] border backdrop-blur-md",
                radius.full,
                shadow.low,
                variants[variant],
                className,
            )}
        >
            {children}
        </span>
    );
}
