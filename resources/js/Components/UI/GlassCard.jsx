import { cn, css, glass, radius, shadow, spacing } from "@/Theme";

export default function GlassCard({
    children,
    variant = "default",
    elevation = "medium",
    className = "",
    padding = true,
    ...props
}) {
    return (
        <div
            className={cn(
                glass[variant] ?? glass.default,
                shadow[elevation] ?? shadow.medium,
                radius.xl,
                spacing.card,
                css.transition,
                className,
            )}
            {...props}
        >
            {children}
        </div>
    );
}
