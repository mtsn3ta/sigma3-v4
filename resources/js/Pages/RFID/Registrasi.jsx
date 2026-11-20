import { Head } from "@inertiajs/react";
import axios from "axios";
import {
    AlertTriangle,
    CheckCircle2,
    ChevronRight,
    CreditCard,
    Lock,
    LogOut,
    RefreshCw,
    ScanLine,
    Search,
    ShieldCheck,
    SkipForward,
    UserCheck,
    Users,
    Volume2,
    VolumeX,
} from "lucide-react";
import { useCallback, useEffect, useMemo, useRef, useState } from "react";

function playTone(type, muted) {
    if (muted || typeof window === "undefined") return;
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const now = ctx.currentTime;

        const playNote = (freq, start, duration, wave = "sine", gainVal = 0.14) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = wave;
            osc.frequency.setValueAtTime(freq, start);
            gain.gain.setValueAtTime(gainVal, start);
            gain.gain.exponentialRampToValueAtTime(0.001, start + duration);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(start);
            osc.stop(start + duration);
        };

        if (type === "success") {
            playNote(880, now, 0.08, "sine", 0.14);
            playNote(1318.5, now + 0.08, 0.11, "sine", 0.16);
        } else {
            playNote(210, now, 0.18, "sawtooth", 0.12);
        }

        setTimeout(() => ctx.close().catch(() => {}), 400);
    } catch {
        // Ignore audio context restrictions
    }
}

export default function Registrasi({
    unlocked: initialUnlocked,
    petugasName: initialPetugas,
    kelasList: initialKelasList,
    selectedKelasId: initialSelectedKelasId,
    requestedKelas,
}) {
    const [unlocked, setUnlocked] = useState(Boolean(initialUnlocked));
    const [petugasName, setPetugasName] = useState(initialPetugas || "");
    const [kelasList, setKelasList] = useState(initialKelasList || []);
    const [selectedKelasId, setSelectedKelasId] = useState(
        initialSelectedKelasId ? String(initialSelectedKelasId) : "",
    );

    // PIN Gate State
    const [pinInput, setPinInput] = useState("");
    const [petugasInput, setPetugasInput] = useState("");
    const [unlockError, setUnlockError] = useState("");
    const [unlocking, setUnlocking] = useState(false);

    // Workspace State
    const [mode, setMode] = useState("assign"); // "assign" | "check"
    const [statusFilter, setStatusFilter] = useState("empty"); // "empty" | "all" | "filled"
    const [search, setSearch] = useState("");
    const [students, setStudents] = useState([]);
    const [loadingStudents, setLoadingStudents] = useState(false);

    const [activeStudentId, setActiveStudentId] = useState(null);
    const [tapUid, setTapUid] = useState("");
    const [submitting, setSubmitting] = useState(false);
    const [muted, setMuted] = useState(false);
    const [feedback, setFeedback] = useState(null); // { type: 'success'|'error'|'info', title, detail }
    const [checkResult, setCheckResult] = useState(null);
    const [recentLogs, setRecentLogs] = useState([]);

    const tapInputRef = useRef(null);
    const searchInputRef = useRef(null);
    const pinInputRef = useRef(null);

    const focusTapInput = useCallback(() => {
        setTimeout(() => {
            if (document.activeElement !== searchInputRef.current) {
                tapInputRef.current?.focus();
            }
        }, 30);
    }, []);

    // Load students when kelasId or search (when no kelas) changes
    const fetchStudents = useCallback(
        async (kelasIdValue, searchValue) => {
            if (!unlocked) return;
            if (!kelasIdValue && searchValue.trim().length < 2) {
                setStudents([]);
                return;
            }
            setLoadingStudents(true);
            try {
                const res = await axios.get("/registrasi-rfid/students", {
                    params: {
                        kelas_id: kelasIdValue || undefined,
                        search: !kelasIdValue ? searchValue.trim() : undefined,
                    },
                });
                setStudents(res.data.students || []);
            } catch (err) {
                if (err.response?.status === 401) {
                    setUnlocked(false);
                }
            } finally {
                setLoadingStudents(false);
            }
        },
        [unlocked],
    );

    useEffect(() => {
        if (!unlocked) return;
        const timer = setTimeout(() => {
            fetchStudents(selectedKelasId, search);
        }, selectedKelasId ? 0 : 250);
        return () => clearTimeout(timer);
    }, [unlocked, selectedKelasId, search, fetchStudents]);

    // Filter displayed students
    const filteredStudents = useMemo(() => {
        const q = search.trim().toLowerCase();
        return students.filter((s) => {
            if (statusFilter === "empty" && s.rfid_uid) return false;
            if (statusFilter === "filled" && !s.rfid_uid) return false;
            if (q && selectedKelasId) {
                const matchName = s.nama.toLowerCase().includes(q);
                const matchNisn = (s.nisn || "").toLowerCase().includes(q);
                if (!matchName && !matchNisn) return false;
            }
            return true;
        });
    }, [students, statusFilter, search, selectedKelasId]);

    const stats = useMemo(() => {
        const total = students.length;
        const filled = students.filter((s) => Boolean(s.rfid_uid)).length;
        const pct = total > 0 ? Math.round((filled / total) * 100) : 0;
        return { total, filled, empty: total - filled, pct };
    }, [students]);

    // Keep activeStudentId valid & auto-select first available
    useEffect(() => {
        if (filteredStudents.length === 0) {
            setActiveStudentId(null);
            return;
        }
        const stillExists = filteredStudents.some((s) => s.id === activeStudentId);
        if (!stillExists) {
            const firstEmpty = filteredStudents.find((s) => !s.rfid_uid);
            setActiveStudentId((firstEmpty || filteredStudents[0]).id);
        }
    }, [filteredStudents, activeStudentId]);

    const activeStudent = useMemo(
        () => students.find((s) => s.id === activeStudentId) || null,
        [students, activeStudentId],
    );

    // Focus tap input when ready
    useEffect(() => {
        if (unlocked && (mode === "check" || activeStudent)) {
            focusTapInput();
        }
    }, [unlocked, mode, activeStudent, focusTapInput]);

    useEffect(() => {
        if (!unlocked) {
            setTimeout(() => pinInputRef.current?.focus(), 50);
        }
    }, [unlocked]);

    const handleUnlock = async (e) => {
        e.preventDefault();
        if (!pinInput.trim() || unlocking) return;
        setUnlocking(true);
        setUnlockError("");
        try {
            const res = await axios.post("/registrasi-rfid/unlock", {
                pin: pinInput.trim(),
                petugas: petugasInput.trim(),
                kelas: requestedKelas || undefined,
            });
            setUnlocked(true);
            setPetugasName(res.data.petugasName || "Petugas");
            setKelasList(res.data.kelasList || []);
            if (res.data.selectedKelasId) {
                setSelectedKelasId(String(res.data.selectedKelasId));
            }
            setPinInput("");
        } catch (err) {
            setUnlockError(
                err.response?.data?.message || "Gagal memverifikasi PIN.",
            );
            playTone("error", muted);
        } finally {
            setUnlocking(false);
        }
    };

    const handleLock = async () => {
        try {
            await axios.post("/registrasi-rfid/lock");
        } finally {
            setUnlocked(false);
            setStudents([]);
            setActiveStudentId(null);
            setFeedback(null);
            setCheckResult(null);
        }
    };

    const advanceToNextEmpty = useCallback(
        (currentId, updatedList) => {
            const list = updatedList || filteredStudents;
            const idx = list.findIndex((s) => s.id === currentId);
            const after = list.slice(idx + 1).find((s) => !s.rfid_uid);
            const before = list.slice(0, Math.max(0, idx)).find((s) => !s.rfid_uid);
            const next = after || before || null;
            setActiveStudentId(next ? next.id : null);
        },
        [filteredStudents],
    );

    const handleSkip = () => {
        if (!activeStudent) return;
        const idx = filteredStudents.findIndex((s) => s.id === activeStudent.id);
        if (filteredStudents.length <= 1) return;
        const next = filteredStudents[(idx + 1) % filteredStudents.length];
        setActiveStudentId(next.id);
        setFeedback(null);
        focusTapInput();
    };

    const handleTapSubmit = async (e) => {
        e.preventDefault();
        const cleanedUid = tapUid.replace(/\s+/g, "").trim();
        if (!cleanedUid || submitting) return;

        setTapUid("");

        if (mode === "check") {
            setSubmitting(true);
            try {
                const res = await axios.post("/registrasi-rfid/check", {
                    rfid_uid: cleanedUid,
                });
                setCheckResult(res.data);
                playTone(res.data.found ? "success" : "error", muted);
            } catch (err) {
                if (err.response?.status === 401) {
                    setUnlocked(false);
                    return;
                }
                setFeedback({
                    type: "error",
                    title: "Gagal Cek Kartu",
                    detail: err.response?.data?.message || "Terjadi kesalahan.",
                });
                playTone("error", muted);
            } finally {
                setSubmitting(false);
                focusTapInput();
            }
            return;
        }

        if (!activeStudent) return;

        setSubmitting(true);
        try {
            const res = await axios.post("/registrasi-rfid/assign", {
                siswa_id: activeStudent.id,
                rfid_uid: cleanedUid,
            });

            const updatedStudent = res.data.student;
            const nextStudents = students.map((s) =>
                s.id === updatedStudent.id ? updatedStudent : s,
            );
            setStudents(nextStudents);

            setFeedback({
                type: "success",
                title: `${updatedStudent.nama} (${updatedStudent.kelas})`,
                detail: `UID ${updatedStudent.rfid_uid} tersimpan.`,
            });
            setRecentLogs((prev) => [
                {
                    id: `${Date.now()}-${updatedStudent.id}`,
                    time: new Date().toLocaleTimeString("id-ID", {
                        hour: "2-digit",
                        minute: "2-digit",
                        second: "2-digit",
                    }),
                    nama: updatedStudent.nama,
                    kelas: updatedStudent.kelas,
                    uid: updatedStudent.rfid_uid,
                    ok: true,
                },
                ...prev.slice(0, 9),
            ]);
            playTone("success", muted);

            // Auto-advance to next student without RFID
            const nextFiltered = nextStudents.filter((s) => {
                if (statusFilter === "empty" && s.rfid_uid) return false;
                if (statusFilter === "filled" && !s.rfid_uid) return false;
                return true;
            });
            advanceToNextEmpty(updatedStudent.id, nextFiltered);
        } catch (err) {
            if (err.response?.status === 401) {
                setUnlocked(false);
                return;
            }
            const msg =
                err.response?.data?.message || "Gagal menyimpan RFID.";
            setFeedback({
                type: "error",
                title: "Tap Ditolak",
                detail: msg,
            });
            setRecentLogs((prev) => [
                {
                    id: `${Date.now()}-err`,
                    time: new Date().toLocaleTimeString("id-ID", {
                        hour: "2-digit",
                        minute: "2-digit",
                        second: "2-digit",
                    }),
                    nama: activeStudent.nama,
                    kelas: activeStudent.kelas,
                    uid: cleanedUid,
                    ok: false,
                    error: msg,
                },
                ...prev.slice(0, 9),
            ]);
            playTone("error", muted);
        } finally {
            setSubmitting(false);
            focusTapInput();
        }
    };

    /* ======================================================================
     * PIN GATE SCREEN
     * ====================================================================== */
    if (!unlocked) {
        return (
            <>
                <Head title="Registrasi RFID Siswa — Kunci Petugas" />
                <div className="min-h-screen bg-[#05070c] text-[#f3f6fc] flex items-center justify-center p-4 relative overflow-hidden">
                    <div
                        className="pointer-events-none absolute inset-0 opacity-30"
                        style={{
                            backgroundImage:
                                "radial-gradient(circle at 50% 25%, rgba(34, 230, 168, 0.16), transparent 55%)",
                        }}
                    />

                    <form
                        onSubmit={handleUnlock}
                        className="relative z-10 w-full max-w-md rounded-2xl border border-slate-800/90 bg-[#0b1120]/95 p-7 shadow-2xl backdrop-blur"
                    >
                        <div className="flex items-center gap-3 mb-5">
                            <div className="h-11 w-11 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                                <Lock className="h-5 w-5" />
                            </div>
                            <div>
                                <h1 className="text-lg font-bold tracking-tight">
                                    Registrasi RFID Siswa
                                </h1>
                                <p className="text-xs text-slate-400">
                                    Halaman Kiosk Petugas • Mode Aman Aktif
                                </p>
                            </div>
                        </div>

                        <div className="space-y-4">
                            <div>
                                <label className="block text-xs font-medium text-slate-300 mb-1.5">
                                    Nama Petugas (Opsional)
                                </label>
                                <input
                                    type="text"
                                    value={petugasInput}
                                    onChange={(e) => setPetugasInput(e.target.value)}
                                    placeholder="Misal: Pak Budi / Piket OSIS"
                                    className="w-full rounded-xl border border-slate-700/80 bg-[#05070c] px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-emerald-400 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label className="block text-xs font-medium text-slate-300 mb-1.5">
                                    PIN Petugas
                                </label>
                                <input
                                    ref={pinInputRef}
                                    type="password"
                                    value={pinInput}
                                    onChange={(e) => setPinInput(e.target.value)}
                                    placeholder="Masukkan PIN..."
                                    autoFocus
                                    required
                                    className="w-full rounded-xl border border-slate-700/80 bg-[#05070c] px-3.5 py-2.5 text-sm tracking-widest text-white placeholder-slate-500 focus:border-emerald-400 focus:outline-none"
                                />
                            </div>

                            {unlockError && (
                                <div className="rounded-xl border border-rose-500/40 bg-rose-500/10 px-3.5 py-2.5 text-xs text-rose-300 flex items-center gap-2">
                                    <AlertTriangle className="h-4 w-4 shrink-0" />
                                    <span>{unlockError}</span>
                                </div>
                            )}

                            <button
                                type="submit"
                                disabled={unlocking}
                                className="w-full rounded-xl bg-emerald-500 hover:bg-emerald-400 disabled:opacity-60 text-slate-950 font-semibold py-2.5 text-sm transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                {unlocking ? "Memverifikasi..." : "Buka Sesi Registrasi"}
                                <ChevronRight className="h-4 w-4" />
                            </button>
                        </div>

                        <p className="mt-5 text-[11px] text-slate-500 text-center">
                            Mode Aman: Halaman ini hanya dapat mengisi siswa yang belum memiliki kartu RFID.
                        </p>
                    </form>
                </div>
            </>
        );
    }

    /* ======================================================================
     * MAIN WORKSPACE
     * ====================================================================== */
    return (
        <>
            <Head title="Registrasi RFID Siswa" />
            <div className="h-screen w-screen overflow-hidden bg-[#05070c] text-[#f3f6fc] flex flex-col">
                {/* Top Bar */}
                <header className="shrink-0 border-b border-slate-800/80 bg-[#0b1120]/90 px-4 lg:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div className="flex items-center gap-3">
                        <div className="h-9 w-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <CreditCard className="h-5 w-5" />
                        </div>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-base font-bold tracking-tight">
                                    Registrasi RFID Siswa
                                </h1>
                                <span className="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-300">
                                    <ShieldCheck className="h-3 w-3" />
                                    Mode Aman (Isi Kosong)
                                </span>
                            </div>
                            <p className="text-xs text-slate-400">
                                Petugas: <span className="text-slate-200 font-medium">{petugasName}</span>
                            </p>
                        </div>
                    </div>

                    <div className="flex items-center gap-2">
                        {/* Mode Toggle */}
                        <div className="inline-flex rounded-xl border border-slate-700/80 bg-[#05070c] p-0.5">
                            <button
                                type="button"
                                onClick={() => {
                                    setMode("assign");
                                    setCheckResult(null);
                                    focusTapInput();
                                }}
                                className={`px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer ${
                                    mode === "assign"
                                        ? "bg-emerald-500 text-slate-950 font-semibold"
                                        : "text-slate-300 hover:text-white"
                                }`}
                            >
                                Tap Massal
                            </button>
                            <button
                                type="button"
                                onClick={() => {
                                    setMode("check");
                                    setFeedback(null);
                                    focusTapInput();
                                }}
                                className={`px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer ${
                                    mode === "check"
                                        ? "bg-sky-400 text-slate-950 font-semibold"
                                        : "text-slate-300 hover:text-white"
                                }`}
                            >
                                Cek Kartu
                            </button>
                        </div>

                        <button
                            type="button"
                            onClick={() => {
                                setMuted((m) => !m);
                                focusTapInput();
                            }}
                            title={muted ? "Aktifkan Suara" : "Bisukan Suara"}
                            className="p-2 rounded-xl border border-slate-700/80 bg-[#05070c] text-slate-300 hover:text-white hover:border-slate-600 transition cursor-pointer"
                        >
                            {muted ? (
                                <VolumeX className="h-4 w-4 text-rose-400" />
                            ) : (
                                <Volume2 className="h-4 w-4 text-emerald-400" />
                            )}
                        </button>

                        <button
                            type="button"
                            onClick={handleLock}
                            className="inline-flex items-center gap-1.5 rounded-xl border border-slate-700/80 bg-[#05070c] px-3 py-2 text-xs font-medium text-slate-300 hover:text-rose-300 hover:border-rose-500/40 transition cursor-pointer"
                        >
                            <LogOut className="h-3.5 w-3.5" />
                            Kunci
                        </button>
                    </div>
                </header>

                {/* Main Content: 2 Columns */}
                <div className="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-12 gap-4 p-4 lg:p-6">
                    {/* LEFT COLUMN: Queue & Filters */}
                    <section className="lg:col-span-5 flex flex-col min-h-0 rounded-2xl border border-slate-800/80 bg-[#0b1120]/80 overflow-hidden">
                        {/* Filters */}
                        <div className="p-4 border-b border-slate-800/80 space-y-3 shrink-0">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div>
                                    <label className="block text-[11px] font-medium text-slate-400 mb-1">
                                        Pilih Kelas
                                    </label>
                                    <select
                                        value={selectedKelasId}
                                        onChange={(e) => {
                                            setSelectedKelasId(e.target.value);
                                            setFeedback(null);
                                            focusTapInput();
                                        }}
                                        className="w-full rounded-xl border border-slate-700/80 bg-[#05070c] px-3 py-2 text-sm text-white focus:border-emerald-400 focus:outline-none"
                                    >
                                        <option value="">-- Cari Global / Pilih Kelas --</option>
                                        {kelasList.map((k) => (
                                            <option key={k.id} value={k.id}>
                                                {k.nama_kelas}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-[11px] font-medium text-slate-400 mb-1">
                                        Cari Nama / NISN
                                    </label>
                                    <div className="relative">
                                        <Search className="h-3.5 w-3.5 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2" />
                                        <input
                                            ref={searchInputRef}
                                            type="text"
                                            value={search}
                                            onChange={(e) => setSearch(e.target.value)}
                                            placeholder={
                                                selectedKelasId
                                                    ? "Filter di kelas..."
                                                    : "Ketik min 2 huruf..."
                                            }
                                            className="w-full rounded-xl border border-slate-700/80 bg-[#05070c] pl-8 pr-3 py-2 text-sm text-white placeholder-slate-500 focus:border-emerald-400 focus:outline-none"
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Status Filter & Progress */}
                            <div className="flex flex-wrap items-center justify-between gap-2">
                                <div className="inline-flex rounded-lg border border-slate-800 bg-[#05070c] p-0.5 text-[11px]">
                                    {[
                                        { key: "empty", label: `Belum RFID (${stats.empty})` },
                                        { key: "all", label: `Semua (${stats.total})` },
                                        { key: "filled", label: `Sudah (${stats.filled})` },
                                    ].map((tab) => (
                                        <button
                                            key={tab.key}
                                            type="button"
                                            onClick={() => {
                                                setStatusFilter(tab.key);
                                                focusTapInput();
                                            }}
                                            className={`px-2.5 py-1 rounded-md transition cursor-pointer ${
                                                statusFilter === tab.key
                                                    ? "bg-slate-800 text-white font-semibold"
                                                    : "text-slate-400 hover:text-slate-200"
                                            }`}
                                        >
                                            {tab.label}
                                        </button>
                                    ))}
                                </div>

                                <button
                                    type="button"
                                    onClick={() => {
                                        fetchStudents(selectedKelasId, search);
                                        focusTapInput();
                                    }}
                                    title="Muat ulang daftar"
                                    className="p-1.5 rounded-lg border border-slate-800 bg-[#05070c] text-slate-400 hover:text-white transition cursor-pointer"
                                >
                                    <RefreshCw
                                        className={`h-3.5 w-3.5 ${
                                            loadingStudents ? "animate-spin" : ""
                                        }`}
                                    />
                                </button>
                            </div>

                            {stats.total > 0 && (
                                <div className="space-y-1">
                                    <div className="flex justify-between text-[11px] text-slate-400">
                                        <span>Progress Kelas</span>
                                        <span className="font-mono text-emerald-400">
                                            {stats.filled} / {stats.total} ({stats.pct}%)
                                        </span>
                                    </div>
                                    <div className="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
                                        <div
                                            className="h-full bg-emerald-400 transition-all duration-300"
                                            style={{ width: `${stats.pct}%` }}
                                        />
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Student Queue List */}
                        <div className="flex-1 min-h-0 overflow-y-auto divide-y divide-slate-800/60">
                            {!selectedKelasId && search.trim().length < 2 ? (
                                <div className="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400">
                                    <Users className="h-9 w-9 text-slate-600 mb-2" />
                                    <p className="text-sm font-medium text-slate-300">
                                        Pilih Kelas atau Cari Nama Siswa
                                    </p>
                                    <p className="text-xs text-slate-500 mt-1 max-w-xs">
                                        Pilih kelas di atas untuk mulai tap beruntun, atau ketik nama/NISN jika siswa datang acak.
                                    </p>
                                </div>
                            ) : loadingStudents ? (
                                <div className="p-8 text-center text-xs text-slate-400">
                                    Memuat daftar siswa...
                                </div>
                            ) : filteredStudents.length === 0 ? (
                                <div className="p-8 text-center text-xs text-slate-400">
                                    {statusFilter === "empty" && stats.total > 0
                                        ? "Semua siswa di daftar ini sudah memiliki RFID."
                                        : "Tidak ada siswa yang cocok dengan filter."}
                                </div>
                            ) : (
                                filteredStudents.map((s) => {
                                    const isActive = s.id === activeStudentId;
                                    const hasRfid = Boolean(s.rfid_uid);
                                    return (
                                        <button
                                            key={s.id}
                                            type="button"
                                            onClick={() => {
                                                setActiveStudentId(s.id);
                                                setFeedback(null);
                                                focusTapInput();
                                            }}
                                            className={`w-full text-left px-4 py-3 flex items-center justify-between gap-3 transition cursor-pointer ${
                                                isActive
                                                    ? "bg-emerald-500/12 border-l-4 border-l-emerald-400"
                                                    : "hover:bg-slate-800/40 border-l-4 border-l-transparent"
                                            }`}
                                        >
                                            <div className="min-w-0">
                                                <p
                                                    className={`text-sm font-semibold truncate ${
                                                        isActive
                                                            ? "text-emerald-300"
                                                            : "text-slate-100"
                                                    }`}
                                                >
                                                    {s.nama}
                                                </p>
                                                <p className="text-xs text-slate-400 font-mono">
                                                    {s.nisn || "-"} • {s.kelas}
                                                </p>
                                            </div>
                                            <div className="shrink-0">
                                                {hasRfid ? (
                                                    <span className="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 text-[11px] font-mono text-emerald-300">
                                                        <CheckCircle2 className="h-3 w-3" />
                                                        {s.rfid_uid}
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center rounded-full bg-amber-500/10 border border-amber-500/30 px-2 py-0.5 text-[11px] text-amber-300">
                                                        Kosong
                                                    </span>
                                                )}
                                            </div>
                                        </button>
                                    );
                                })
                            )}
                        </div>
                    </section>

                    {/* RIGHT COLUMN: Active Tap Area + Recent Logs */}
                    <section className="lg:col-span-7 flex flex-col min-h-0 gap-4">
                        <div className="rounded-2xl border border-slate-800/80 bg-[#0b1120]/90 p-5 lg:p-6 flex flex-col gap-5">
                            {mode === "check" ? (
                                <>
                                    <div>
                                        <span className="inline-flex items-center gap-1.5 rounded-full bg-sky-500/15 border border-sky-500/30 px-2.5 py-1 text-xs font-medium text-sky-300">
                                            <ScanLine className="h-3.5 w-3.5" />
                                            Mode Cek Kartu (Read-Only)
                                        </span>
                                        <h2 className="text-xl font-bold mt-2">
                                            Tap Kartu untuk Cek Pemilik
                                        </h2>
                                        <p className="text-xs text-slate-400">
                                            Tidak mengubah data apa pun. Cocok untuk mengecek kartu yang tercecer.
                                        </p>
                                    </div>

                                    <form onSubmit={handleTapSubmit} className="flex gap-2">
                                        <div className="relative flex-1">
                                            <ScanLine className="h-4 w-4 text-sky-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                                            <input
                                                ref={tapInputRef}
                                                type="text"
                                                value={tapUid}
                                                onChange={(e) => setTapUid(e.target.value)}
                                                placeholder="Tempelkan kartu di reader..."
                                                autoComplete="off"
                                                disabled={submitting}
                                                className="w-full rounded-xl border-2 border-sky-500/40 bg-[#05070c] pl-10 pr-4 py-3 text-base font-mono text-white placeholder-slate-500 focus:border-sky-400 focus:outline-none"
                                            />
                                        </div>
                                        <button
                                            type="submit"
                                            disabled={submitting || !tapUid.trim()}
                                            className="px-5 rounded-xl bg-sky-400 hover:bg-sky-300 disabled:opacity-40 text-slate-950 font-semibold text-sm transition cursor-pointer"
                                        >
                                            Cek
                                        </button>
                                    </form>

                                    {checkResult && (
                                        <div
                                            className={`rounded-xl border p-4 ${
                                                checkResult.found
                                                    ? "border-emerald-500/40 bg-emerald-500/10"
                                                    : "border-amber-500/40 bg-amber-500/10"
                                            }`}
                                        >
                                            {checkResult.found ? (
                                                <div className="flex items-center gap-3">
                                                    <UserCheck className="h-6 w-6 text-emerald-400 shrink-0" />
                                                    <div>
                                                        <p className="text-xs text-emerald-300 font-medium">
                                                            Terdaftar sebagai {checkResult.owner_type}
                                                        </p>
                                                        <p className="text-base font-bold text-white">
                                                            {checkResult.owner.nama}
                                                        </p>
                                                        <p className="text-xs text-slate-300 font-mono">
                                                            {checkResult.owner.kelas} • NISN/NIP: {checkResult.owner.nisn || "-"} • UID: {checkResult.owner.rfid_uid}
                                                        </p>
                                                    </div>
                                                </div>
                                            ) : (
                                                <div className="flex items-center gap-3">
                                                    <AlertTriangle className="h-6 w-6 text-amber-400 shrink-0" />
                                                    <div>
                                                        <p className="text-sm font-bold text-amber-200">
                                                            Kartu Belum Terdaftar
                                                        </p>
                                                        <p className="text-xs text-amber-300/80 font-mono">
                                                            UID {checkResult.rfid_uid} masih kosong dan siap digunakan.
                                                        </p>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </>
                            ) : activeStudent ? (
                                <>
                                    {/* Active Student Card */}
                                    <div className="flex items-start justify-between gap-4">
                                        <div className="flex items-center gap-4 min-w-0">
                                            {activeStudent.foto_url ? (
                                                <img
                                                    src={activeStudent.foto_url}
                                                    alt={activeStudent.nama}
                                                    className="h-16 w-16 rounded-2xl object-cover border border-slate-700 shrink-0"
                                                />
                                            ) : (
                                                <div className="h-16 w-16 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-xl font-bold text-emerald-300 shrink-0">
                                                    {activeStudent.nama
                                                        .split(" ")
                                                        .slice(0, 2)
                                                        .map((w) => w[0])
                                                        .join("")
                                                        .toUpperCase()}
                                                </div>
                                            )}
                                            <div className="min-w-0">
                                                <span className="text-[11px] font-medium uppercase tracking-wider text-emerald-400">
                                                    Target Tap Aktif
                                                </span>
                                                <h2 className="text-2xl font-bold text-white truncate">
                                                    {activeStudent.nama}
                                                </h2>
                                                <p className="text-sm text-slate-300 font-mono">
                                                    NISN: {activeStudent.nisn || "-"} • Kelas{" "}
                                                    <span className="text-white font-semibold">
                                                        {activeStudent.kelas}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            onClick={handleSkip}
                                            className="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-slate-700 bg-[#05070c] px-3 py-2 text-xs font-medium text-slate-300 hover:text-white hover:border-slate-500 transition cursor-pointer"
                                        >
                                            <SkipForward className="h-3.5 w-3.5" />
                                            Lewati
                                        </button>
                                    </div>

                                    {/* Tap Input */}
                                    {activeStudent.rfid_uid ? (
                                        <div className="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 flex items-center justify-between gap-3">
                                            <div>
                                                <p className="text-sm font-semibold text-emerald-300">
                                                    Siswa ini sudah memiliki RFID: {activeStudent.rfid_uid}
                                                </p>
                                                <p className="text-xs text-slate-400 mt-0.5">
                                                    Mode Aman aktif: penggantian kartu hanya dapat dilakukan melalui panel Admin.
                                                </p>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={handleSkip}
                                                className="shrink-0 rounded-lg bg-slate-800 hover:bg-slate-700 px-3 py-1.5 text-xs font-medium text-white cursor-pointer"
                                            >
                                                Lanjut
                                            </button>
                                        </div>
                                    ) : (
                                        <form onSubmit={handleTapSubmit} className="space-y-2">
                                            <div className="flex gap-2">
                                                <div className="relative flex-1">
                                                    <ScanLine className="h-5 w-5 text-emerald-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                                                    <input
                                                        ref={tapInputRef}
                                                        type="text"
                                                        value={tapUid}
                                                        onChange={(e) => setTapUid(e.target.value)}
                                                        placeholder="Siap Tap Kartu RFID (otomatis simpan saat Enter)..."
                                                        autoComplete="off"
                                                        disabled={submitting}
                                                        className="w-full rounded-xl border-2 border-emerald-500/50 bg-[#05070c] pl-11 pr-4 py-3.5 text-base font-mono text-white placeholder-slate-500 focus:border-emerald-400 focus:outline-none"
                                                    />
                                                </div>
                                                <button
                                                    type="submit"
                                                    disabled={submitting || !tapUid.trim()}
                                                    className="px-5 rounded-xl bg-emerald-500 hover:bg-emerald-400 disabled:opacity-40 text-slate-950 font-semibold text-sm transition cursor-pointer"
                                                >
                                                    {submitting ? "..." : "Simpan"}
                                                </button>
                                            </div>
                                            <p className="text-[11px] text-slate-400">
                                                Kursor terkunci otomatis di kolom tap. Setelah kartu ditempel, sorotan otomatis pindah ke siswa berikutnya.
                                            </p>
                                        </form>
                                    )}
                                </>
                            ) : (
                                <div className="py-10 text-center text-slate-400">
                                    <CheckCircle2 className="h-10 w-10 text-emerald-400/70 mx-auto mb-2" />
                                    <p className="text-sm font-medium text-slate-200">
                                        {selectedKelasId
                                            ? "Tidak ada siswa yang menunggu input di filter ini."
                                            : "Pilih kelas di sebelah kiri untuk mulai."}
                                    </p>
                                </div>
                            )}

                            {/* Feedback Banner */}
                            {feedback && (
                                <div
                                    className={`rounded-xl border px-4 py-3 flex items-start gap-3 ${
                                        feedback.type === "success"
                                            ? "border-emerald-500/40 bg-emerald-500/10 text-emerald-200"
                                            : "border-rose-500/40 bg-rose-500/10 text-rose-200"
                                    }`}
                                >
                                    {feedback.type === "success" ? (
                                        <CheckCircle2 className="h-5 w-5 text-emerald-400 shrink-0 mt-0.5" />
                                    ) : (
                                        <AlertTriangle className="h-5 w-5 text-rose-400 shrink-0 mt-0.5" />
                                    )}
                                    <div className="min-w-0">
                                        <p className="text-sm font-semibold">{feedback.title}</p>
                                        <p className="text-xs opacity-90">{feedback.detail}</p>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Recent Session Logs */}
                        <div className="flex-1 min-h-0 rounded-2xl border border-slate-800/80 bg-[#0b1120]/70 flex flex-col overflow-hidden">
                            <div className="px-4 py-2.5 border-b border-slate-800/80 flex items-center justify-between shrink-0">
                                <span className="text-xs font-semibold text-slate-300">
                                    Riwayat Tap Sesi Ini
                                </span>
                                <span className="text-[11px] font-mono text-slate-500">
                                    {recentLogs.length} aktivitas terakhir
                                </span>
                            </div>
                            <div className="flex-1 min-h-0 overflow-y-auto divide-y divide-slate-800/50">
                                {recentLogs.length === 0 ? (
                                    <div className="p-6 text-center text-xs text-slate-500">
                                        Belum ada aktivitas tap pada sesi ini.
                                    </div>
                                ) : (
                                    recentLogs.map((log) => (
                                        <div
                                            key={log.id}
                                            className="px-4 py-2.5 flex items-center justify-between gap-3 text-xs"
                                        >
                                            <div className="min-w-0">
                                                <p className="font-medium text-slate-200 truncate">
                                                    {log.nama}{" "}
                                                    <span className="text-slate-500">
                                                        ({log.kelas})
                                                    </span>
                                                </p>
                                                {log.error && (
                                                    <p className="text-[11px] text-rose-400 truncate">
                                                        {log.error}
                                                    </p>
                                                )}
                                            </div>
                                            <div className="shrink-0 flex items-center gap-2 font-mono">
                                                <span
                                                    className={`px-2 py-0.5 rounded-md ${
                                                        log.ok
                                                            ? "bg-emerald-500/15 text-emerald-300"
                                                            : "bg-rose-500/15 text-rose-300"
                                                    }`}
                                                >
                                                    {log.uid}
                                                </span>
                                                <span className="text-slate-500">{log.time}</span>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </>
    );
}
