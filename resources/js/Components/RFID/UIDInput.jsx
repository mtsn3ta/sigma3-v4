// resources/js/Components/RFID/UIDInput.jsx
import { forwardRef, useEffect, useImperativeHandle, useRef } from "react";

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
            placeholder="Tempelkan kartu atau ketik UID..."
            className="
                rk-mono
                w-full
                rounded-xl
                border
                border-(--rk-line)
                bg-(--rk-panel)/40
                px-4
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
                placeholder:text-(--rk-muted)/60
                focus:border-(--rk-emerald)/50
                focus:text-(--rk-text)
                focus:ring-2
                focus:ring-(--rk-emerald)/20
            "
        />
    );
});

export default UIDInput;
