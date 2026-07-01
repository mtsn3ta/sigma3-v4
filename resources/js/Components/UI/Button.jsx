// resources/js/Components/UI/Button.jsx

import { Link } from "@inertiajs/react";
import { cn, css, glass, radius, shadow } from "@/Theme";

const base = cn(
    "inline-flex items-center justify-center px-6 py-3 text-sm font-semibold transition-all duration-300 active:scale-95 disabled:pointer-events-none disabled:opacity-50",
    radius.full,
    css.transition,
);

const variants = {
    primary: cn("bg-slate-900 text-white hover:bg-slate-800", shadow.medium),
    secondary: cn(
        "bg-emerald-600 text-white hover:bg-emerald-700",
        shadow.medium,
    ),
    outline:
        "border border-slate-300 bg-transparent text-slate-700 hover:bg-slate-50",
    glass: cn(glass.default, "text-slate-800 hover:bg-white/60", shadow.low),
    danger: cn("bg-red-600 text-white hover:bg-red-700", shadow.medium),
};

export default function Button({
    href,
    children,
    variant = "primary",
    className = "",
    ...props
}) {
    const classes = cn(base, variants[variant] ?? variants.primary, className);

    if (href) {
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
