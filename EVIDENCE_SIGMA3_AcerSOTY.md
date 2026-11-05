# LEMBAR PENJELASAN EVIDENCE / ARTEFAK AI
### Acer School of the Year — Kategori School of the Year

---

## IDENTITAS PESERTA

| | |
| :--- | :--- |
| **Kategori** | School of the Year |
| **Nama Peserta / Sekolah** | MTsN 3 Tulungagung (MATSANEGA) |
| **Judul Project** | SIGMA-3 — Sistem Informasi Digital Terpadu Madrasah |
| **Jenis Dokumen** | Evidence & Artefak Implementasi AI |

> **Format Penamaan File/Folder:**
> `School of the Year_MTsN3Tulungagung_SIGMA3_EvidenceAI`

---

## DESKRIPSI SINGKAT PROJECT

**SIGMA-3** (*Sistem Informasi Digital Terpadu MTsN 3 Tulungagung*) adalah platform Smart School berskala *enterprise* yang dikembangkan secara mandiri oleh tim IT madrasah. Sistem ini mengintegrasikan seluruh ekosistem digital madrasah — mulai dari presensi RFID/NFC kecepatan tinggi, pemantauan KBM *live* berbasis layar TV, hingga asisten virtual berbasis **Google Gemini AI** yang melayani wali murid 24 jam nonstop melalui WhatsApp.

---

## DAFTAR EVIDENCE & ARTEFAK YANG DILAMPIRKAN

### 🗂️ Dokumen Tertulis

| No. | Jenis Dokumen | Nama File | Keterangan |
| :---: | :--- | :--- | :--- |
| 1 | Laporan Fitur Lengkap (Markdown) | `LAPORAN_FITUR_SIGMA3_ACER_SMART_SCHOOL.md` | Deskripsi mendalam 17 fitur unggulan, arsitektur teknologi, matriks peran, dan dampak implementasi |
| 2 | Lembar Penjelasan Evidence (Dokumen ini) | `EVIDENCE_SIGMA3_AcerSOTY.md` | Formulir pengantar & indeks artefak yang diserahkan |

### 🌐 Repositori & Source Code

| No. | Platform | Tautan | Keterangan |
| :---: | :--- | :--- | :--- |
| 3 | GitHub / Repository | `https://github.com/mtsn3ta/sigma3-v4` | Source code lengkap aplikasi SIGMA-3 (Laravel 12 + React + Node.js WhatsApp Sidecar). **Catatan: Repositori bersifat private — tim penilai dapat mengajukan permintaan akses *read-only collaborator* kepada Tim IT MTsN 3 Tulungagung.** |

### 🖥️ Demonstrasi & Dashboard Live

| No. | Jenis Artefak | Tautan / Lokasi | Keterangan |
| :---: | :--- | :--- | :--- |
| 4 | Website / Aplikasi Live | `https://sigma3.mtsn3tulungagung.sch.id/` | Portal utama publik SIGMA-3 (Landing Page, Galeri, Prestasi, Monitoring Absensi) |
| 5 | Live KBM Display | `https://sigma3.mtsn3tulungagung.sch.id/display/kbm-live` | Layar TV Command Center monitoring KBM real-time seluruh kelas |
| 6 | Student Intelligence Scanner | `https://sigma3.mtsn3tulungagung.sch.id/intelligence` | Pemindai profil holistik 360° siswa via RFID/NFC dengan Radar Chart AI |
| 7 | Monitoring Absensi Publik | `https://sigma3.mtsn3tulungagung.sch.id/monitoring` | Rekapitulasi kehadiran siswa hari ini secara transparan |

### 🤖 Implementasi Kecerdasan Buatan (AI)

| No. | Jenis Artefak AI | Keterangan |
| :---: | :--- | :--- |
| 8 | **CS AI Matsanega (WhatsApp Bot)** | Asisten AI resmi madrasah berbasis **Google Gemini** (dual-tier dengan OpenRouter fallback). Melayani wali murid 24 jam: cek absensi, profil siswa, prestasi, portal izin, hingga konsultasi umum. |
| 9 | **Student Intelligence AI Engine** | Analitik pintar 360° yang membangun *radar profile* siswa dari data terpadu: kehadiran, ibadah, pelanggaran BK, nilai e-Rapor Kemenag, ekskul, dan prestasi lomba. |
| 10 | **Caller Context Recognition** | Fitur pengenalan identitas otomatis nomor telepon orang tua → AI menyapa dengan nama siswa yang bersangkutan tanpa perlu input data ulang. |
| 11 | **Multi-Database e-Rapor Bridge** | Integrasi langsung dengan basis data resmi Kementerian Agama (e-Rapor) untuk menampilkan timeline nilai akademik siswa lintas semester. |

---

## RINGKASAN IMPLEMENTASI AI PADA SIGMA-3

```
┌─────────────────────────────────────────────────────────────────────┐
│               ARSITEKTUR AI SIGMA-3 MATSANEGA                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  LAYER INPUT                                                        │
│  ┌────────────┐  ┌──────────────┐  ┌──────────────────────────┐    │
│  │ WhatsApp   │  │  RFID Card   │  │  Admin / Guru Portal     │    │
│  │ Wali Murid │  │  NFC Scan    │  │  (Multi-Role Dashboard)  │    │
│  └─────┬──────┘  └──────┬───────┘  └────────────┬─────────────┘    │
│        │                │                       │                   │
│  LAYER PROCESSING — AI ENGINE                   │                   │
│  ┌─────▼────────────────▼───────────────────────▼──────────────┐   │
│  │  SIGMA-3 Core (Laravel 12 + PHP 8.3)                        │   │
│  │  ┌──────────────────────┐  ┌──────────────────────────────┐ │   │
│  │  │  CS AI Matsanega Bot │  │  Student Intelligence Engine │ │   │
│  │  │  ┌────────────────┐  │  │  ┌──────────────────────┐   │ │   │
│  │  │  │  Tier 1:       │  │  │  │  Radar Profile 6D    │   │ │   │
│  │  │  │  Google Gemini │  │  │  │  Absensi + Ibadah +  │   │ │   │
│  │  │  │  AI (Primary)  │  │  │  │  BK + eRapor +       │   │ │   │
│  │  │  ├────────────────┤  │  │  │  Ekskul + Prestasi   │   │ │   │
│  │  │  │  Tier 2:       │  │  │  └──────────────────────┘   │ │   │
│  │  │  │  OpenRouter    │  │  │  ┌──────────────────────┐   │ │   │
│  │  │  │  (Auto-Fallback│  │  │  │  e-Rapor Bridge      │   │ │   │
│  │  │  │  Gemma/MiniMax)│  │  │  │  (Kemenag DB Live)   │   │ │   │
│  │  │  └────────────────┘  │  │  └──────────────────────┘   │ │   │
│  │  └──────────────────────┘  └──────────────────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  LAYER OUTPUT                                                       │
│  ┌────────────┐  ┌──────────┐  ┌───────────┐  ┌───────────────┐    │
│  │  WA Chat   │  │  TV      │  │  PDF Auto │  │  Parent Portal│    │
│  │  AI Reply  │  │  Display │  │  Generator│  │  Dashboard    │    │
│  └────────────┘  └──────────┘  └───────────┘  └───────────────┘    │
└─────────────────────────────────────────────────────────────────────┘
```

---

## INOVASI UTAMA YANG MEMENUHI KRITERIA AI

### ✅ 1. Penggunaan AI Generatif (Generative AI)
Aplikasi menggunakan **Google Gemini AI** (model `gemini-3.5-flash-lite` / `gemini-3.5-flash`) sebagai otak asisten virtual CS AI Matsanega. AI mampu memahami pertanyaan kontekstual, mengenali identitas penanya, dan merespons secara humanis bernuansa islami dalam Bahasa Indonesia.

### ✅ 2. AI untuk Analitik Data Siswa (Predictive & Descriptive Analytics)
Fitur *Student Intelligence* membangun **profil holistik siswa secara otomatis** dari 6 dimensi data yang sebelumnya tersebar di sistem berbeda (presensi, ibadah, nilai, BK, ekskul, prestasi) dan menyajikannya dalam satu visualisasi grafik radar.

### ✅ 3. Kecerdasan Buatan Terintegrasi Perangkat Keras IoT
AI Engine berinteraksi langsung dengan data mentah dari perangkat keras: pembaca RFID/NFC di gerbang madrasah, kamera selfie wali murid, dan printer thermal bluetooth untuk menghasilkan keputusan otomatis (Hadir / Terlambat / Izin Resmi / Pelanggaran).

### ✅ 4. Ketahanan Sistem AI (99.9% Availability — Multi-Model Failover)
Arsitektur *dual-tier* memastikan layanan AI tidak pernah berhenti:
- Jika Gemini mengalami gangguan → sistem beralih otomatis ke model pengganti (OpenRouter: MiniMax Flash, Llama, Gemma) **tanpa intervensi manual**.

### ✅ 5. Dampak Nyata & Terukur di Lingkungan Pendidikan
- **Efisiensi kertas > 90%** melalui digitalisasi proposal, laporan, jurnal, dan dispensasi.
- **Respons wali murid 24 jam** tanpa jam kerja administratif, mengurangi beban staf tata usaha.
- **Transparansi penuh** rekam jejak siswa untuk wali kelas, BK, pimpinan, dan orang tua.

---

## INFORMASI TEKNIS TAMBAHAN

| Aspek Teknis | Detail |
| :--- | :--- |
| **Framework Utama** | Laravel 12 (PHP 8.3), Filament v3, Inertia.js, React 18 |
| **AI Provider** | Google Gemini AI (Primary) + OpenRouter Free Models (Fallback) |
| **WhatsApp Engine** | Node.js, Express.js, whatsapp-web.js (Puppeteer headless) |
| **Hardware Terintegrasi** | RFID Reader 13.56MHz, Smartphone Web NFC, Thermal Printer Bluetooth 58mm, Smart TV |
| **Database** | MySQL (Multi-connection: SIGMA-3 DB + Kemenag e-Rapor DB) |
| **Keamanan Dokumen** | HMAC Signed URL untuk verifikasi keaslian dokumen digital |
| **Peran Pengguna** | 10+ role (Kepala, Waka x4, Guru, BK, Piket, Pembina, Pelatih, Siswa) |
| **Deployment** | Web Server Linux + PM2 Process Manager (Sidecar Node.js) |

---

## PERNYATAAN

Dengan ini kami menyatakan bahwa seluruh bukti implementasi yang terlampir dalam dokumen ini adalah **asli, otentik, dan benar-benar telah diterapkan** di lingkungan MTsN 3 Tulungagung serta dapat diakses dan diverifikasi oleh tim penilai Acer School of the Year.

| | |
| :--- | :--- |
| **Lembaga** | MTsN 3 Tulungagung (MATSANEGA) |
| **Alamat** | Tulungagung, Jawa Timur |
| **Tim Pengembang** | Tim IT & Inovasi Digital MTsN 3 Tulungagung |
| **Tanggal Penyerahan** | November 2026 |

---
*Dokumen ini adalah formulir pengantar resmi yang disertakan bersama seluruh tautan dan berkas Evidence / Artefak AI pada submission lomba Acer School of the Year.*
