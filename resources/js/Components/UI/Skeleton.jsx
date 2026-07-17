import { cn } from "@/Theme";

export default function Skeleton({ className = "" }) {
    return (
        <div
            className={cn(
                `
                    animate-pulse
                    rounded-2xl
                    bg-slate-200/70
                `,
                className,
            )}
        />
    );
}
