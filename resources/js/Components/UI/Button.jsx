import { Link } from "@inertiajs/react";
import { cn, css, glass, radius, shadow } from "@/Theme";

const base = cn(
    "inline-flex items-center justify-center px-6 py-3 text-sm font-semibold transition-all duration-300 active:scale-95 disabled:pointer-events-none disabled:opacity-50 cursor-pointer select-none",
    radius.full,
    css.transition,
);

const variants = {
    primary: cn(
        "bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-600/20 hover:shadow-emerald-600/30",
        shadow.medium,
    ),
    secondary: cn(
        "bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300",
        shadow.low,
    ),
    outline:
        "border border-slate-300 bg-white/60 text-slate-700 hover:bg-slate-50 hover:border-slate-400 hover:text-emerald-700",
    glass: cn(
        glass.default,
        "text-slate-800 hover:bg-emerald-50/80 hover:text-emerald-700 hover:border-emerald-200",
        shadow.low,
    ),
    danger: cn("bg-rose-600 text-white hover:bg-rose-700 shadow-rose-600/20", shadow.medium),
};

export default function Button({
    href,
    external = false,
    children,
    variant = "primary",
    className = "",
    ...props
}) {
    const classes = cn(base, variants[variant] ?? variants.primary, className);

    if (href) {
        if (external) {
            return (
                <a href={href} className={classes} {...props}>
                    {children}
                </a>
            );
        }

        return (
            <Link href={href} className={classes} {...props}>
                {children}
            </Link>
        );
    }

    return (
        <button className={classes} {...props}>
            {children}
        </button>
    );
}
