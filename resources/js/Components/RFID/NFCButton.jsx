// resources/js/Components/RFID/NFCButton.jsx
import { Wifi, WifiOff } from "lucide-react";
import { useState } from "react";

export default function NFCButton({ onScan, disabled = false }) {
    const [loading, setLoading] = useState(false);
    const [unsupported, setUnsupported] = useState(false);

    async function startScan() {
        if (!("NDEFReader" in window)) {
            setUnsupported(true);
            setTimeout(() => setUnsupported(false), 3000);
            return;
        }

        try {
            setLoading(true);
            const ndef = new NDEFReader();
            await ndef.scan();
            ndef.onreading = ({ serialNumber }) => {
                onScan(serialNumber);
            };
        } catch (error) {
            alert(error.message);
        } finally {
            setLoading(false);
        }
    }

    return (
        <button
            type="button"
            disabled={disabled || unsupported}
            onClick={startScan}
            className="
                group
                relative
                flex
                shrink-0
                items-center
                justify-center
                gap-2
                overflow-hidden
                rounded-xl
                border
                px-5
                py-2
                text-[clamp(0.7rem,0.9vw,0.85rem)]
                font-semibold
                uppercase
                tracking-[0.2em]
                transition-all
                duration-300
                disabled:cursor-not-allowed
                disabled:opacity-40
                active:scale-95
            "
            style={
                unsupported
                    ? {
                          borderColor: "var(--rk-red)",
                          background: "color-mix(in srgb, var(--rk-red) 12%, transparent)",
                          color: "var(--rk-red)",
                      }
                    : {
                          borderColor: "color-mix(in srgb, var(--rk-emerald) 40%, transparent)",
                          background: "color-mix(in srgb, var(--rk-emerald) 10%, transparent)",
                          color: "var(--rk-emerald)",
                      }
            }
        >
            {/* shimmer */}
            <span className="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-full" />

            {loading ? (
                <>
                    {/* spinning ring */}
                    <span
                        className="h-3.5 w-3.5 shrink-0 animate-spin rounded-full border-2"
                        style={{
                            borderColor: "color-mix(in srgb, var(--rk-emerald) 25%, transparent)",
                            borderTopColor: "var(--rk-emerald)",
                        }}
                    />
                    <span>Menunggu NFC…</span>
                </>
            ) : unsupported ? (
                <>
                    <WifiOff className="h-3.5 w-3.5 shrink-0" />
                    <span>NFC Tidak Didukung</span>
                </>
            ) : (
                <>
                    <Wifi className="h-3.5 w-3.5 shrink-0" />
                    <span>Aktifkan NFC</span>
                </>
            )}
        </button>
    );
}
