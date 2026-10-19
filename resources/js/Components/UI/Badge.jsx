// resources/js/Components/UI/Badge.jsx
import { cn, glass, radius, shadow } from "@/Theme";

const variants = {
    primary: "border-emerald-200/80 bg-emerald-50 text-emerald-800 shadow-emerald-500/5",
    secondary: "border-slate-200 bg-slate-100 text-slate-700 shadow-slate-500/5",
    neutral: "border-slate-200 bg-slate-100 text-slate-800 shadow-slate-500/5",
    gray: "border-slate-200 bg-slate-100 text-slate-700 shadow-slate-500/5",
    success: "border-green-200/80 bg-green-50 text-green-800 shadow-green-500/5",
    warning: "border-amber-200/80 bg-amber-50 text-amber-900 shadow-amber-500/5",
    danger: "border-rose-200/80 bg-rose-50 text-rose-800 shadow-rose-500/5",
    info: "border-sky-200/80 bg-sky-50 text-sky-800 shadow-sky-500/5",
    pink: "border-pink-200/80 bg-pink-50 text-pink-800 shadow-pink-500/5",
    purple: "border-purple-200/80 bg-purple-50 text-purple-800 shadow-purple-500/5",
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
