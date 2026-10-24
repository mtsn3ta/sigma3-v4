# Redesain Landing Page SIGMA-3 (Smart Madrasah Ecosystem) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengupgrade tampilan Landing Page MTsN 3 Tulungagung (SIGMA-3) menjadi bergaya "Smart Campus / Apple-grade" modern, berwibawa, terang, bersih, dan interaktif (RFID e-Card showcase, live telemetry, fast NISN search, integrasi WhatsApp AI) tanpa merusak atau menghapus menu unggulan di Navbar (Monitoring, Student Intelligence) dan Bento Grid Layanan Unggulan.

**Architecture:** Memecah komponen monolithic `Landing.jsx` (1060 baris) menjadi modul-modul terstruktur di bawah `resources/js/Components/Landing/`: `SmartHero`, `LiveTelemetry`, `QuickServiceGrid`, `BentoFeatures`, `PrincipalVision`, dan `CallToAction`. Mempertahankan 100% kontrak Inertia props (`settings`, `statistics`, `latestAchievements`, `featuredExtracurriculars`, `latestGallery`, `attendanceSummary`).

**Tech Stack:** React 18 / Inertia.js v2, Tailwind CSS 3, Lucide React icons, Framer Motion (`motion/react`), Vite.

**Spec:** Mockup visual yang diunggah pengguna (`media_1792866026577_c7630753.png` untuk Hero Smart Campus & `media_1792866200611_b7c7d39c.png` untuk Bento Grid Layanan Unggulan).

## Global Constraints

- Menu Navbar (`Navbar.jsx`): Rute `/`, `/#fitur-modul`, `/#features`, `/monitoring`, `/intelligence`, dan tombol login/portal `/login` serta `/admin` TIDAK BOLEH dihapus atau diubah rutenya.
- Bento Grid Layanan Unggulan (`#features`): Wajib tetap menampilkan live RFID Attendance counters (Hadir, Terlambat, Sakit, Izin, Alfa, Persentase), Hall of Fame Prestasi dengan slider foto juara, Ekstrakurikuler, dan Galeri Foto.
- Sambutan Kepala Madrasah (`WelcomePopup.jsx` & card visi): Wajib tetap aktif dan terpasang.
- Kemudahan Rollback: File asli dicadangkan di `resources/js/Pages/Landing.backup.jsx` dan commit git `5c78428`.

## Review Focus

1. Validasi Input NISN: Input hanya angka, trim spasi, redirect lancar ke `/wali_murid/{nisn}`.
2. Responsivitas Layar HP: Kartu RFID dan mockup WhatsApp harus collapse dengan mulus tanpa overflow horizontal di layar < 375px.
3. Fallback Data Kosong: Jika `latestAchievements` atau `latestGallery` kosong, halaman tidak boleh crash (render fallback elegant).
4. Live Telemetry Data: Menggunakan nilai riil dari `attendanceSummary` yang dikirim controller, dengan fallback nilai default jika libur.
5. Build Verification: `npm run build` wajib berhasil (exit code 0) dengan 0 error sintaks.

---

### Task 1: Buat Komponen `SmartHero.jsx` & Interactive RFID e-Card Showcase

**Files:**
- Create: `resources/js/Components/Landing/SmartHero.jsx`
- Test: Verify interactive search, hover glow, and card tilt in browser & build check

- [ ] Buat file `resources/js/Components/Landing/SmartHero.jsx` yang memuat:
  - Sisi Kiri: Badge status ("Smart Madrasah Ecosystem • IoT & AI Ready"), Headline utama ("Digitalisasi Terpadu MTsN 3 Tulungagung: Satu Kartu, Segudang Kemudahan"), deskripsi ringkas, dan Fast NISN Search Console (form pencarian NISN dengan validasi input angka dan tombol "Cari").
  - Sisi Kanan: Showcase 3D-tilt e-Kartu Pelajar RFID Digital MATSANEGA dengan logo Kemenag/Madrasah, foto siswa, barcode, nomor RFID, dan 3 indikator sensor bercahaya interaktif:
    1. *Presensi Gerbang* (Tap Masuk/Pulang)
    2. *Masjid & Ibadah* (Dhuha & Dhuhur)
    3. *Akses Layanan* (Perpustakaan & Konseling)
- [ ] Tambahkan tombol navigasi cepat CTA: "Pantau Presensi Live", "Portal Wali Murid", dan "Asisten AI WhatsApp".

---

### Task 2: Buat Komponen `LiveTelemetry.jsx` & `QuickServiceGrid.jsx`

**Files:**
- Create: `resources/js/Components/Landing/LiveTelemetry.jsx`
- Create: `resources/js/Components/Landing/QuickServiceGrid.jsx`

- [ ] Buat file `LiveTelemetry.jsx`:
  - Menampilkan strip telemetry horizontal modern di bawah hero:
    - Chip status: Hijau berdenyut "Sistem Aktif (IoT Cloud Online)".
    - Kehadiran Hari Ini: Persentase dinamis dari `attendanceSummary.percentage` (contoh: 98.4%).
    - Status KBM: "KBM Efektif Berlangsung" / agenda madrasah.
    - Jam Server: Jam digital live WIB.
- [ ] Buat file `QuickServiceGrid.jsx`:
  - 4 Kartu Layanan Cepat yang bersih (Clean White Card + Aksen Hijau Zamrud):
    1. **Presensi Real-time** (Akses cepat monitoring & gate log)
    2. **Akses Perpustakaan & KBM** (Jurnal mengajar & modul belajar)
    3. **Ibadah & Karakter** (Pantauan sholat dhuha & dhuhur berjamaah)
    4. **Laporan Orang Tua** (Portal wali murid transparan)
  - Card Mockup Smartphone WhatsApp Min AI:
    - Simulasi layar chat WhatsApp resmi madrasah dengan pesan sambutan Min AI dan jawaban cepat informasi madrasah.
    - Tombol interaktif langsung buka WhatsApp official madrasah.

---

### Task 3: Buat Komponen `BentoFeatures.jsx` (Layanan Unggulan)

**Files:**
- Create: `resources/js/Components/Landing/BentoFeatures.jsx`

- [ ] Pindahkan dan poles Bento Grid dari gambar pengguna ke dalam `BentoFeatures.jsx`:
  - Header: Badge "Layanan Unggulan", Judul "Satu Platform. Beragam Solusi Digital."
  - Card 1: Monitoring Presensi RFID (Hadir, Terlambat, Sakit, Izin, Alfa, progress bar Tingkat Kehadiran, tombol Buka Dashboard Live -> `/monitoring`).
  - Card 2: Portal Prestasi (Badge Hall of Fame, slider foto juara dengan nama lomba, total piala -> `/prestasi`).
  - Card 3: Ekstrakurikuler (Total cabang pilihan & list cabang aktif -> `/ekstrakurikuler`).
  - Card 4: Galeri Madrasah (Preview foto kegiatan nyata siswa -> `/galeri`).

---

### Task 4: Buat Komponen `PrincipalVision.jsx` & `CallToAction.jsx`

**Files:**
- Create: `resources/js/Components/Landing/PrincipalVision.jsx`
- Create: `resources/js/Components/Landing/CallToAction.jsx`

- [ ] Buat file `PrincipalVision.jsx`:
  - Sambutan & Visi Kepala Madrasah: Menampilkan foto resmi Kepala Madrasah (`settings.principal_photo` atau fallback default), nama lengkap beserta gelar & NIP, serta kutipan visi resmi MTsN 3 Tulungagung dengan desain editorial yang berwibawa.
- [ ] Buat file `CallToAction.jsx`:
  - CTA Penerimaan Peserta Didik Baru (PPDB Online) dengan tombol eksternal ke situs PPDB dan tombol konsultasi WhatsApp.

---

### Task 5: Assembling `Landing.jsx`, Verifikasi Build, & Pengetesan

**Files:**
- Modify: `resources/js/Pages/Landing.jsx`

- [ ] Rangkai semua modul ke dalam `Landing.jsx`.
- [ ] Pastikan seluruh metadata SEO (title, description, canonical, open graph) tetap lengkap.
- [ ] Jalankan `npm run build` untuk memverifikasi bundle terkompilasi 100% tanpa error.
- [ ] Uji responsivitas pada resolusi mobile (360px), tablet (768px), dan desktop (1280px).
- [ ] Uji pencarian NISN untuk memastikan navigasi ke `/wali_murid/{nisn}` berfungsi normal.
