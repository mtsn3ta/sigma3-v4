export default function ScanStatus({
    status = "waiting",

    message = "Menunggu scan kartu...",
}) {
    const styles = {
        waiting: "bg-slate-100 text-slate-700",

        success: "bg-emerald-100 text-emerald-700",

        error: "bg-rose-100 text-rose-700",
    };

    return (
        <div
            className={`
                rounded-3xl
                px-6
                py-5
                text-center
                text-xl
                font-bold
                ${styles[status]}
            `}
        >
            {message}
        </div>
    );
}
