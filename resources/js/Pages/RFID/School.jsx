import { useEffect, useRef, useState } from "react";

import RFIDLayout from "@/Layouts/RFIDLayout";
import UIDInput from "@/Components/RFID/UIDInput";
import NFCButton from "@/Components/RFID/NFCButton";
import IdleScreen from "@/Components/RFID/IdleScreen";
import SuccessScreen from "@/Components/RFID/SuccessScreen";
import ErrorScreen from "@/Components/RFID/ErrorScreen";
import useSchoolRFID from "@/Hooks/useSchoolRFID";
import LastScanList from "@/Components/RFID/LastScanList";
import { RFID_SCREEN } from "@/Lib/rfid";

export default function School() {
    const uidInputRef = useRef(null);

    /*
    |--------------------------------------------------------------------------
    | Screen State
    |--------------------------------------------------------------------------
    */
    const [scanResult, setScanResult] = useState({
        screen: RFID_SCREEN.IDLE,
        student: null,
        error: null,
    });

    /*
    |--------------------------------------------------------------------------
    | RFID UID
    |--------------------------------------------------------------------------
    */
    const [uid, setUid] = useState("");

    /*
    |--------------------------------------------------------------------------
    | Last Scan
    |--------------------------------------------------------------------------
    */
    const [lastScans, setLastScans] = useState([]);

    /*
    |--------------------------------------------------------------------------
    | Hook
    |--------------------------------------------------------------------------
    */
    const { scan } = useSchoolRFID({
        uid,
        setUid,
        setScanResult,
        setLastScans,
        uidInputRef,
    });

    /*
    |--------------------------------------------------------------------------
    | Live Clock
    |--------------------------------------------------------------------------
    */
    const [time, setTime] = useState("");
    const [date, setDate] = useState("");

    useEffect(() => {
        const updateClock = () => {
            const now = new Date();
            setTime(now.toLocaleTimeString("id-ID"));
            setDate(
                now.toLocaleDateString("id-ID", {
                    weekday: "long",
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                }),
            );
        };
        updateClock();
        const timer = setInterval(updateClock, 1000);
        return () => clearInterval(timer);
    }, []);

    /*
    |--------------------------------------------------------------------------
    | Auto Focus RFID Reader
    |--------------------------------------------------------------------------
    */
    useEffect(() => {
        if (scanResult.screen === RFID_SCREEN.IDLE) {
            uidInputRef.current?.focus();
        }
    }, [scanResult.screen]);

    /*
    |--------------------------------------------------------------------------
    | RFID Workflow
    |--------------------------------------------------------------------------
    */
    useEffect(() => {
        if (!uid.trim()) return;
        scan();
    }, [uid, scan]);

    return (
        <RFIDLayout
            title="Absensi Digital"
            subtitle="Scan Datang & Pulang"
            sidebar={<LastScanList items={lastScans} mode="school" />}
        >
            <div className="mx-auto mb-[clamp(0.4rem,1vh,0.85rem)] flex w-full max-w-md shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                <UIDInput ref={uidInputRef} value={uid} onChange={setUid} />
                <NFCButton onScan={setUid} />
            </div>

            <div className="flex min-h-0 flex-1 flex-col overflow-hidden">
                {scanResult.screen === RFID_SCREEN.IDLE && (
                    <IdleScreen mode="school" time={time} date={date} />
                )}

                {scanResult.screen === RFID_SCREEN.SUCCESS && (
                    <SuccessScreen mode="school" data={scanResult} />
                )}

                {scanResult.screen === RFID_SCREEN.ERROR && (
                    <ErrorScreen
                        mode="school"
                        type={scanResult.error?.type}
                        student={scanResult.student}
                        message={scanResult.error?.message}
                    />
                )}
            </div>
        </RFIDLayout>
    );
}
