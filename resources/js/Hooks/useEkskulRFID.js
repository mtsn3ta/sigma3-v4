import { useCallback, useRef } from "react";
import axios from "axios";

import {
    RFID_ERROR,
    RFID_SCREEN,
} from "@/Lib/rfid";

const ENDPOINT = "/api/rfid/ekskul";

const RESET_DELAY = 800;
const DEBOUNCE_DELAY = 150;

export default function useEkskulRFID({
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

            activity: null,

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
            const { data } = await axios.post(
                ENDPOINT,
                {
                    uid: uidValue.trim(),
                },
            );

            if (data.status) {
                setScanResult({
                    screen: RFID_SCREEN.SUCCESS,

                    student: data.student,

                    activity: data.activity,

                    message: data.message,

                    serverTime: data.server_time,

                    error: null,
                });

                setLastScans((current) => [
                    {
                        ...data.student,

                        activity: data.activity?.nama,

                        jam: data.server_time,

                        scannedAt: Date.now(),
                    },

                    ...current,
                ].slice(0, 6));
            } else {
                setScanResult({
                    screen: RFID_SCREEN.ERROR,

                    student: data.student ?? null,

                    activity: null,

                    error: {
                        type:
                            data.error ??
                            RFID_ERROR.SYSTEM_ERROR,
                    },
                });
            }
        } catch (error) {
            console.error(error);

            setScanResult({
                screen: RFID_SCREEN.ERROR,

                student: null,

                activity: null,

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
    }, [
        reset,
        setLastScans,
        setScanResult,
    ]);

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
    }, [
        uid,
        executeScan,
    ]);

    return {
        scan,
    };
}
