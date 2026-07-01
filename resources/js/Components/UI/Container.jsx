import { cn, container } from "@/Theme";

export default function Container({
    children,
    variant = "default",
    className = "",
    ...props
}) {
    return (
        <div
            className={cn(container[variant] ?? container.default, className)}
            {...props}
        >
            {children}
        </div>
    );
}
