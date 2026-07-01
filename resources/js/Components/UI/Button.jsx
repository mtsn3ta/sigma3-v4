import { Link } from "@inertiajs/react";
import { cn, css, glass, radius, shadow } from "@/Theme";

const base = cn(
    "inline-flex items-center justify-center px-6 py-3 text-sm font-semibold transition-all duration-300 active:scale-95 disabled:pointer-events-none disabled:opacity-50",
    radius.full,
    css.transition,
);

const variants = {
    primary: cn("bg-teal-600 text-white hover:bg-teal-700", shadow.medium),
    secondary: cn(
        "bg-teal-50 text-teal-700 border border-teal-200 hover:bg-teal-100",
        shadow.low,
    ),
    outline:
        "border border-slate-300 bg-transparent text-slate-700 hover:bg-slate-50 hover:border-slate-400 hover:text-teal-600",
    glass: cn(
        glass.default,
        "text-slate-800 hover:bg-teal-50/60 hover:text-teal-700",
        shadow.low,
    ),
    danger: cn("bg-red-600 text-white hover:bg-red-700", shadow.medium),
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
