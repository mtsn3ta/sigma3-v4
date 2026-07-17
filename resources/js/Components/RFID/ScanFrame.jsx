// resources/js/Components/RFID/ScanFrame.jsx
export default function ScanFrame({
    children,
    size = "2rem",
    thickness = 2,
    color = "var(--rk-emerald)",
    offset = "0.9rem",
    className = "",
}) {
    const cornerBase = {
        position: "absolute",
        width: size,
        height: size,
        borderColor: color,
        borderStyle: "solid",
        filter: `drop-shadow(0 0 6px ${color})`,
    };

    return (
        <div className={`relative ${className}`}>
            <span
                style={{
                    ...cornerBase,
                    top: `-${offset}`,
                    left: `-${offset}`,
                    borderTopWidth: thickness,
                    borderLeftWidth: thickness,
                    borderRadius: "0.9rem 0 0 0",
                }}
            />
            <span
                style={{
                    ...cornerBase,
                    top: `-${offset}`,
                    right: `-${offset}`,
                    borderTopWidth: thickness,
                    borderRightWidth: thickness,
                    borderRadius: "0 0.9rem 0 0",
                }}
            />
            <span
                style={{
                    ...cornerBase,
                    bottom: `-${offset}`,
                    left: `-${offset}`,
                    borderBottomWidth: thickness,
                    borderLeftWidth: thickness,
                    borderRadius: "0 0 0 0.9rem",
                }}
            />
            <span
                style={{
                    ...cornerBase,
                    bottom: `-${offset}`,
                    right: `-${offset}`,
                    borderBottomWidth: thickness,
                    borderRightWidth: thickness,
                    borderRadius: "0 0 0.9rem 0",
                }}
            />
            {children}
        </div>
    );
}
