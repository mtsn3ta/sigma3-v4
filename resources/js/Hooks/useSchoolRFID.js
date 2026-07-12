import { useCallback, useRef } from "react";
import axios from "axios";

import {
    RFID_ERROR,
    RFID_SCREEN,
} from "@/Lib/rfid";

const ENDPOINT = "/api/rfid/sekolah";

const RESET_DELAY = 1200; // Ditingkatkan sedikit agar user sempat membaca pesan error di luar jam
const DEBOUNCE_DELAY = 150;

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
    }, [setUid, setScanResult, uidInputRef]);

    const executeScan = useCallback(async (uidValue) => {
        if (loadingRef.current) {
            return;
        }

        loadingRef.current = true;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI JAM ABSENSI DI SISI FRONTEND (23:00 s/d 06:59)
        |--------------------------------------------------------------------------
        */
        const sekarang = new Date();
        const jamSekarang = sekarang.getHours();
        const menitSekarang = sekarang.getMinutes();

        // Konversi waktu saat ini ke angka menit total (misal jam 01:30 = 90 menit)
        const totalMenitSekarang = (jamSekarang * 60) + menitSekarang;

        const batasMulaiMenit = (23 * 60) + 0;    // 23:00
        const batasSelesaiMenit = (6 * 60) + 59;  // 06:59

        // Karena melewati tengah malam (23:00 s/d 06:59), kondisinya menggunakan OR (||)
        const isValidTime = (totalMenitSekarang >= batasMulaiMenit || totalMenitSekarang <= batasSelesaiMenit);

        if (!isValidTime) {
            setScanResult({
                screen: RFID_SCREEN.ERROR,
                student: null,
                error: {
                    type: "DI_LUAR_JAM_ABSENSI", // Anda bisa sesuaikan key error ini dengan konstanta di rfid.js Anda
                    message: "Akses ditolak! Sekarang di luar jam absensi sekolah."
                },
            });

            setTimeout(() => {
                loadingRef.current = false;
                reset();
            }, RESET_DELAY);
            return;
        }

        // --- Batas Validasi Jam ---

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
                setScanResult({
                    screen: RFID_SCREEN.ERROR,
                    student: data.student ?? null,
                    error: {
                        type: data.error ?? RFID_ERROR.SYSTEM_ERROR,
                    },
                });
            }
        } catch (error) {
            console.error(error);
            setScanResult({
                screen: RFID_SCREEN.ERROR,
                student: null,
                error: {
                    type: RFID_ERROR.SYSTEM_ERROR,
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

        if (debounceRef.current) {
            clearTimeout(debounceRef.current);
        }

        const currentUid = uid;

        debounceRef.current = setTimeout(() => {
            executeScan(currentUid);
        }, DEBOUNCE_DELAY);
    }, [uid, executeScan]);

    return {
        scan,
    };
}
