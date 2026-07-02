// resources/js/Components/RFID/NFCButton.jsx
import { useState } from "react";

export default function NFCButton({ onScan, disabled = false }) {
    const [loading, setLoading] = useState(false);

    async function startScan() {
        if (!("NDEFReader" in window)) {
            alert("Perangkat ini belum mendukung Web NFC.");
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
            disabled={disabled}
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
                border-(--rk-emerald)/40
                bg-(--rk-emerald)/10
                px-5
                py-2
                text-[clamp(0.7rem,0.9vw,0.85rem)]
                font-semibold
                uppercase
                tracking-[0.2em]
                text-(--rk-emerald)
                transition-all
                duration-300
                hover:bg-(--rk-emerald)/20
                disabled:cursor-not-allowed
                disabled:opacity-40
            "
        >
            <span className="absolute inset-0 -translate-x-full bg-linear-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-full" />
            {loading ? "Menunggu NFC..." : "Aktifkan NFC"}
        </button>
    );
}
