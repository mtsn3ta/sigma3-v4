import { useCallback, useRef } from "react";
import axios from "axios";

import {
    RFID_ERROR,
    RFID_SCREEN,
} from "@/Lib/rfid";

const ENDPOINT = "/api/rfid/sekolah";

const RESET_DELAY = 800;

export default function useSchoolRFID({

    uid,

    setUid,

    setScanResult,

    setLastScans,

    uidInputRef,

}) {

    const loadingRef = useRef(false);

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

    const scan = useCallback(async () => {

        if (!uid?.trim()) {

            return;

        }

        if (loadingRef.current) {

            return;

        }

        loadingRef.current = true;

        try {

            const { data } = await axios.post(

                ENDPOINT,

                {

                    uid: uid.trim(),

                }

            );

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

        uid,

        reset,

        setLastScans,

        setScanResult,

    ]);

    return {

        scan,

    };

}
