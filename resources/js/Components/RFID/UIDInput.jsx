// resources/js/Components/RFID/UIDInput.jsx
import { forwardRef, useEffect, useImperativeHandle, useRef } from "react";
import { ScanLine } from "lucide-react";

const UIDInput = forwardRef(function UIDInput(
    { value, onChange, disabled = false, autoFocus = true },
    ref,
) {
    const inputRef = useRef(null);

    useImperativeHandle(ref, () => ({
        focus() {
            inputRef.current?.focus();
        },
        clear() {
            onChange("");
            inputRef.current?.focus();
        },
    }));

    useEffect(() => {
        if (autoFocus && !disabled) {
            inputRef.current?.focus();
        }
    }, [autoFocus, disabled]);

    return (
        <div className="relative flex-1">
            {/* scan icon left */}
            <span className="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                <ScanLine
                    className="h-3.5 w-3.5 transition-colors duration-300"
                    style={{ color: "color-mix(in srgb, var(--rk-emerald) 55%, transparent)" }}
                />
            </span>

            <input
                ref={inputRef}
                type="text"
                value={value}
                autoFocus={autoFocus}
                autoComplete="off"
                spellCheck={false}
                autoCorrect="off"
                autoCapitalize="off"
                inputMode="text"
                disabled={disabled}
                onChange={(e) => onChange(e.target.value)}
                placeholder="Tempelkan kartu atau ketik UID…"
                className="
                    rk-mono
                    w-full
                    rounded-xl
                    border
                    border-(--rk-line)
                    bg-(--rk-panel)/40
                    pl-9
                    pr-4
                    py-2
                    text-center
                    text-[clamp(0.75rem,0.9vw,0.9rem)]
                    font-medium
                    tracking-[0.2em]
                    uppercase
                    text-(--rk-muted)
                    outline-none
                    transition-all
                    duration-300
                    placeholder:text-(--rk-muted)/50
                    focus:border-(--rk-emerald)/60
                    focus:text-(--rk-text)
                    focus:ring-2
                    focus:ring-(--rk-emerald)/20
                    disabled:opacity-40
                "
            />
        </div>
    );
});

export default UIDInput;
