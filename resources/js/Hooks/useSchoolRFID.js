import { useCallback, useRef } from "react";
import axios from "axios";

import {
    RFID_ERROR,
    RFID_SCREEN,
} from "@/Lib/rfid";

const ENDPOINT = "/api/rfid/sekolah";

const RESET_DELAY = 800;
const DEBOUNCE_DELAY = 150; // jeda tunggu sebelum eksekusi scan

export default function useSchoolRFID({

    uid,

    setUid,

    setScanResult,

    setLastScans,

    uidInputRef,

}) {

    const loadingRef = useRef(false);
    const debounceRef = useRef(null);

    const reset = useCallback(() => {

        setUid("");

        setScanResult({

            screen: RFID_SCREEN.IDLE,

            student: null,

            error: null,

        });

        uidInputRef.current?.focus();

    }, [

        setUid,

        setScanResult,

        uidInputRef,

    ]);

    const executeScan = useCallback(async (uidValue) => {
        if (loadingRef.current) {
            return;
        }

        loadingRef.current = true;
        try {
            const { data } = await axios.post(ENDPOINT, {
                uid: uidValue.trim(),
            });

            if (data.status) {
                setScanResult({
                    screen: RFID_SCREEN.SUCCESS,
                    student: data.student,
                    error: null,
                });

                setLastScans((current) => [
                    {
                        ...data.student,
                        action: data.action,
                        jam: data.server_time,
                        scannedAt: Date.now(),
                    },
                    ...current,
                ].slice(0, 6));
            } else {
                // PERBAIKAN: Tangkap pesan dari backend (data.message)
                setScanResult({
                    screen: RFID_SCREEN.ERROR,
                    student: data.student ?? null,
                    error: {
                        type: data.error ?? RFID_ERROR.SYSTEM_ERROR,
                        message: data.message ?? "Terjadi kesalahan sistem.", // <-- PESAN DITANGKAP DI SINI
                    },
                });
            }
        } catch (error) {
            console.error("Scan Error:", error);
            setScanResult({
                screen: RFID_SCREEN.ERROR,
                student: null,
                error: {
                    type: RFID_ERROR.SYSTEM_ERROR,
                    message: error.response?.data?.message ?? "Gagal terhubung ke server.", // <-- PESAN DITANGKAP DARI SERVER
                },
            });
        } finally {
            setTimeout(() => {
                loadingRef.current = false;
                reset();
            }, RESET_DELAY);
        }
    }, [reset, setLastScans, setScanResult]);

    const scan = useCallback(() => {

        if (!uid?.trim()) {

            return;

        }

        // Batalkan timer sebelumnya kalau masih ada karakter baru masuk
        if (debounceRef.current) {
            clearTimeout(debounceRef.current);
        }

        const currentUid = uid;

        // Tunggu sampai tidak ada perubahan uid selama DEBOUNCE_DELAY ms
        debounceRef.current = setTimeout(() => {
            executeScan(currentUid);
        }, DEBOUNCE_DELAY);

    }, [

        uid,

        executeScan,

    ]);

    return {

        scan,

    };

}
