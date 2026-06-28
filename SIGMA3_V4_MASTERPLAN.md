# SIGMA3_V4_MASTERPLAN.md

Status:
🟩 Final

Version:
v1.0

Last Updated:
2026-06-23

Repository:
main

Framework:

- Laravel 13
- Filament 4
- Spatie Permission

Instansi:

MTsN 3 Tulungagung

---

# VISI PROYEK

SIGMA3 V4 merupakan sistem informasi madrasah terintegrasi yang menggantikan Sigma3 Lama.

Target sistem:

- Satu Database
- Satu Sistem Login
- Multi Role
- RFID Ready
- Mudah Dikembangkan
- Mudah Dipelihara
- Mendukung Migrasi Sigma3 Lama
- Mendukung Pelatih Eksternal
- Siap Pengembangan Jangka Panjang

Modul utama:

- Master Data
- Akademik
- Kehadiran
- BK
- Guru
- Ekstrakurikuler
- Publikasi
- System

---

# DOKUMEN ACUAN RESMI

Semua pengembangan wajib mengacu pada:

1. SIGMA3_V4_DECISIONS.md
2. SIGMA3_V4_DATABASE_BLUEPRINT.md
3. SIGMA3_V4_ERD.md
4. SIGMA3_V4_ROLES.md
5. SIGMA3_V4_PERMISSION_MATRIX.md

Apabila terjadi konflik:

```text
Decision
↓
ERD
↓
Database Blueprint
↓
Migration
↓
Implementation
```

---

# STATUS PROYEK SAAT INI

CURRENT:

🟨 PHASE 7 — BK

NEXT:

⬜ PHASE 8 — GURU

---

# PHASE 0 — FOUNDATION PROJECT

Tujuan:

Membangun fondasi proyek.

Checklist:

- [x] Laravel 13
- [x] Filament 4
- [x] Spatie Permission
- [x] Git Repository
- [x] Environment Setup
- [x] Local Development Setup
- [x] Branch Strategy
- [x] Tag v0.1-foundation

Status:

✅ SELESAI

---

# PHASE 1 — ANALISIS SISTEM LAMA

Tujuan:

Memahami Sigma3 Lama sebelum redesign.

## Audit Database

- [x] Users
- [x] Pegawai
- [x] Siswa
- [x] Kelas
- [x] Tahun Ajaran
- [x] Kelas Siswa

## Audit Kehadiran

- [x] Absensi Harian
- [x] Log RFID
- [x] Kehadiran Sekolah
- [x] Kehadiran Ibadah

## Audit BK

- [x] Konseling
- [x] Permasalahan
- [x] Izin Keluar

## Audit Guru

- [x] Jurnal Mengajar
- [x] Guru Berhalangan

## Audit Ekstrakurikuler

- [x] Ekstrakurikuler
- [x] Pengampu
- [x] Anggota
- [x] Kehadiran
- [x] Sesi Kegiatan
- [x] Proposal
- [x] Laporan Bulanan

## Audit Publikasi

- [x] Prestasi
- [x] Pengumuman
- [x] Galeri

Status:

✅ SELESAI

---

# PHASE 2 — SYSTEM ARCHITECTURE

Tujuan:

Membekukan aturan bisnis.

Output Wajib:

- [x] Decisions Final
- [x] Roles Final
- [x] Permission Matrix Final
- [x] Database Blueprint Final
- [x] ERD Final

Status:

✅ SELESAI

---

# PHASE 3 — FOUNDATION

---

## Sprint 3.1 User Architecture

Output:

- [x] User Architecture Review
- [x] Pegawai Relation Review
- [x] Siswa Relation Review
- [x] Login Strategy
- [x] Decision Update

Status:

✅ SELESAI

---

## Sprint 3.2 Authentication

Output:

- [x] Username Login
- [x] NIK/NIP Login
- [x] NISN Login
- [x] Must Change Password
- [x] Profile Management
- [x] Authentication Testing

Status:

✅ SELESAI

---

## Sprint 3.3 Filament Setup

Output:

- [x] Install Filament
- [x] Admin Panel
- [x] Navigation Group
- [x] Dashboard Setup

Status:

✅ SELESAI

---

## Sprint 3.4 Authorization

Output:

- [x] Roles Final
- [x] Permission Matrix Final

Status:

✅ SELESAI

---

## Sprint 3.5 Permission Foundation

Output:

### Permission

- [x] Install Spatie Permission

### Seeder Blueprint

- [x] Roles Seeder Blueprint
- [x] Permission Seeder Blueprint

### Implementation

- [x] Roles Seeder
- [x] Permission Seeder
- [x] Role Permission Seeder

### Testing

- [x] Permission Testing

Status:

🟩 SELESAI

---

# PHASE 4 — MASTER DATA

Tujuan:

Membangun seluruh data induk sistem.

---

## Sprint 4.1 Pegawai

Urutan:

- [x] Migration
- [x] Model
- [x] Factory
- [x] Seeder
- [x] Resource
- [x] Policy
- [x] Testing
- [x] Commit

---

## Sprint 4.2 Siswa

Urutan:

- [x] Migration
- [x] Model
- [x] Factory
- [x] Seeder
- [x] Resource
- [x] Policy
- [x] Testing
- [x] Commit

---

## Sprint 4.3 Tahun Ajaran

Urutan:

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing
- [x] Commit

---

## Sprint 4.4 Kelas

Urutan:

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing
- [x] Commit

---

## Sprint 4.5 Kelas Siswa

Urutan:

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing
- [x] Commit

---

## Sprint 4.6 Role Management

Urutan:

- [x] Role Resource
- [x] Permission Resource
- [x] Assign Role UI
- [x] Testing
- [x] Commit

Status:

🟩 SELESAI

---

# PHASE 5 — EKSTRAKURIKULER

Tujuan:

Menyelesaikan seluruh workflow ekstrakurikuler dengan akses berbasis pengampu, approval berjenjang, dan integrasi kehadiran.

---

## Sprint 5.1 Ekstrakurikuler

- [x] Migration
- [x] Model
- [x] Resource
- [x] Testing

---

## Sprint 5.2 Pengampu

- [x] Migration
- [x] Model
- [x] Resource
- [x] Testing

---

## Sprint 5.3 Anggota Ekskul

- [x] Migration
- [x] Model
- [x] Resource
- [x] Testing

---

## Sprint 5.4 Sesi Kegiatan

- [x] Migration
- [x] Model
- [x] Resource
- [x] Testing

---

## Sprint 5.5 Kehadiran Ekskul

- [x] Migration
- [x] Model
- [x] Resource
- [x] Auto Session Attendance Generation
- [-] RFID Integration (Deferred)
- [x] Testing

---

## Sprint 5.6 Scope & Role Engine

Tujuan:

Menyelaraskan Role Sistem dengan Pengampu Ekskul dan membatasi akses berdasarkan ekskul yang diampu.

### Auto Role Synchronization

- [x] Pengampu → Assign Role Pembina
- [x] Pengampu → Assign Role Pelatih
- [x] Auto Remove Role

### Scope Engine

- [x] User::isPembinaEkskul()
- [x] User::isPelatihEkskul()
- [x] User::managesEkstrakurikuler()

### Resource Scope

- [x] Scope Anggota Ekskul
- [x] Scope Sesi Kegiatan
- [x] Scope Kehadiran Ekskul
- [x] Scope Proposal Kegiatan
- [-] Scope Laporan Bulanan (Sprint 5.8)

### Testing

- [x] Multi Ekskul
- [x] Multi Pengampu
- [x] Pembina + Pelatih Bersamaan

---

## Sprint 5.7 Proposal Kegiatan

STATUS: ±99% COMPLETE

Tujuan:

Membangun sistem Proposal Kegiatan Ekstrakurikuler yang dapat diajukan, disetujui, dicetak, dan diarsipkan secara digital.

---

### 5.7.1 Core Proposal

#### Database

[x] Redesign proposal_kegiatan
[x] Migration Final Schema
[x] Model ProposalKegiatan
[x] Resource ProposalKegiatan

#### Informasi Kegiatan

[x] Nama Kegiatan
[x] Jenis Kegiatan
[x] Tempat Kegiatan
[x] Tanggal Mulai
[x] Tanggal Selesai

#### Status Workflow

[x] draft
[x] pending_pembina
[x] pending_waka
[x] pending_kepala
[x] approved
[x] rejected

#### Scope

[x] Scope Proposal Kegiatan
[x] Scope Form Proposal
[x] Scope Peserta Proposal

---

### 5.7.2 Template Proposal Engine

#### BAB I Pendahuluan

[x] Latar Belakang
[x] Dasar Kegiatan

#### BAB II Tujuan

[x] Tujuan Kegiatan

#### BAB III Peserta Kegiatan

##### Database

[x] Migration proposal_peserta
[x] Model ProposalPeserta

##### Peserta

[x] Ambil Anggota Ekskul Aktif
[x] Pilih Peserta Kegiatan
[x] Simpan ke proposal_peserta

##### Output

[x] Tabel Peserta Otomatis
[x] Nomor Urut Peserta
[x] Tampilkan Kelas Aktif

#### BAB IV Rencana Anggaran Biaya

##### Database

[x] Migration proposal_rab
[x] Model ProposalRab

##### RAB

[x] Relation Manager RAB
[x] CRUD Item RAB
[x] Auto Subtotal

#### Total Anggaran

[x] Auto Calculate Total
[x] Format Rupiah
[x] Ringkasan Anggaran

#### BAB V Penutup

[x] Penutup
[x] Editable oleh Pembina/Pelatih

---

### 5.7.3 Template Otomatis

[x] Auto Fill Latar Belakang
[x] Auto Fill Dasar Kegiatan
[x] Auto Fill Tujuan
[x] Auto Fill Penutup
[x] Dynamic Template Proposal

---

### 5.7.4 Workflow Approval

#### Submit

[x] Submit Proposal

#### Approval

[x] Approval Pembina
[x] Approval Waka Kesiswaan
[x] Approval Kepala Madrasah

#### Reject

[x] Reject Workflow

#### Security

[x] Lock Edit Setelah Approved
[x] Lock RAB Setelah Approved
[x] Lock Peserta Setelah Approved

---

### 5.7.5 Approval Logs

#### Audit Trail

[x] Migration proposal_approval_log
[x] Model ProposalApprovalLog

#### Auto Logging

[x] Auto Log Submit
[x] Auto Log Approve Pembina
[x] Auto Log Approve Waka
[x] Auto Log Approve Kepala
[x] Auto Log Reject

---

### 5.7.6 Preview Proposal

#### Backend

[x] Proposal Preview Page
[x] Route Preview Proposal
[x] Tombol Preview Proposal

#### Cover

[ ] Logo Madrasah (Final Asset)
[x] Nama Madrasah
[x] Judul Proposal
[x] Informasi Kegiatan

#### Isi Proposal

[x] BAB I Pendahuluan
[x] BAB II Tujuan
[x] BAB III Peserta
[x] BAB IV RAB
[x] Total Anggaran
[x] Ringkasan Anggaran
[x] BAB V Penutup

#### Lembar Pengesahan

[x] Layout Lembar Pengesahan
[x] Nama Pelatih Otomatis
[x] Nama Pembina Otomatis
[x] Nama Waka Kesiswaan Otomatis
[x] Nama Kepala Madrasah Otomatis

#### UI

[x] Layout A4 Portrait
[x] Print Friendly

---

### 5.7.7 PDF Engine

#### Output

[x] Generate PDF
[x] Cover Proposal
[x] Tabel Peserta
[x] Tabel RAB
[x] Ringkasan Anggaran
[x] Halaman Persetujuan

---

### 5.7.8 Testing

#### Workflow

[x] Pelatih → Submit
[x] Pembina → Approve
[x] Waka → Approve
[x] Kepala → Approve

#### Audit

[x] Approval Logs

#### Output

[x] Preview Proposal
[x] Generate PDF

#### End-to-End

[x] End-to-End Workflow

---

## Sprint 5.7.9 Foundation Improvement

### 5.7.9.1 Permission & Policy Refactor

#### Permission

- [x] Refactor Proposal Permission
- [x] Tambah Permission Laporan Kegiatan
- [x] Tambah Permission Laporan Bulanan

#### Role Permission

- [x] Super Admin
- [x] Kepala Madrasah
- [x] Waka Kesiswaan
- [x] Pembina
- [x] Pelatih

#### Policies

- [x] ProposalKegiatanPolicy
- [x] LaporanKegiatanPolicy (Sprint 5.8)
- [x] LaporanBulananPolicy (Sprint 5.8)

#### Resource Visibility

- [x] Hide Menu by Permission
- [x] Hide Action by Permission
- [x] Hide Relation Manager by Permission

---

### 5.7.9.2 Revision Engine

#### Foundation

- [x] Migration revision_logs
- [x] Model RevisionLog
- [x] Proposal Morph Relation

#### Workflow

- [x] Status Revision
- [x] Request Revision
- [x] Resubmit

#### Revision Notes

- [x] Catatan Revisi
- [x] Riwayat Revisi

#### Approval

- [x] Pembina Revision
- [x] Waka Revision
- [x] Kepala Revision

#### Logs

- [x] Timeline Revision

---

# Sprint 5.8 Laporan Kegiatan & Laporan Bulanan

STATUS: 99%

Tujuan:

Membangun sistem pelaporan kegiatan ekstrakurikuler yang terintegrasi dengan Proposal Kegiatan serta menghasilkan Laporan Bulanan otomatis berbasis data kegiatan, sesi latihan, dan kehadiran.

---

## 5.8.1 Laporan Kegiatan

### Database

- [x] Migration laporan_kegiatan
- [x] Model LaporanKegiatan
- [x] Resource LaporanKegiatan

### Integrasi Proposal

- [x] 1 Proposal → 1 Laporan Kegiatan
- [x] Auto Ambil Data Proposal
- [x] Auto Ambil Peserta Proposal
- [x] Auto Ambil Pendamping Proposal
- [x] Auto Ambil RAB Proposal

### Isi Laporan

#### BAB I Pendahuluan

- [x] Latar Belakang
- [x] Tujuan
- [x] Identitas Kegiatan

#### BAB II Pelaksanaan

- [x] Waktu Pelaksanaan
- [x] Tempat
- [x] Peserta
- [x] Uraian Pelaksanaan

#### BAB III Hasil Kegiatan

- [x] Pencapaian
- [x] Prestasi
- [x] Evaluasi

#### BAB IV Realisasi Anggaran

- [x] Auto Ambil RAB Proposal
- [x] Realisasi Anggaran
- [x] Selisih Anggaran

#### BAB V Penutup

- [x] Penutup

### Lampiran

- [x] Upload Dokumentasi
- [x] Upload Sertifikat
- [x] Upload Berita Acara (Opsional)

### Workflow

- [x] Submit
- [x] Approval Pembina
- [x] Approval Waka
- [x] Approval Kepala
- [x] Request Revision
- [x] Reject Workflow

### Approval Logs

- [x] Auto Log Submit
- [x] Auto Log Approval
- [x] Auto Log Reject

### Preview & PDF

- [x] Preview
- [x] Generate PDF
- [x] Lembar Pengesahan

---

## 5.8.2 Laporan Bulanan Ekstrakurikuler

### Database

- [x] Migration laporan_bulanan
- [x] Model LaporanBulanan
- [x] Resource LaporanBulanan

### Auto Generate Engine

#### BAB I

- [x] Pendahuluan
- [x] Tujuan
- [x] Identitas Ekstrakurikuler

#### BAB II Program Latihan

- [x] Auto Ambil Sesi Kegiatan
- [x] Auto Ambil Program Latihan
- [x] Auto Rekap Kehadiran

#### BAB III Prestasi

- [x] Auto Ambil Proposal Bulan Ini
- [x] Auto Ambil Laporan Kegiatan
- [x] Auto Ambil Prestasi

#### BAB IV Evaluasi

- [x] Evaluasi
- [x] Kendala
- [x] Solusi

#### BAB V Penutup

- [x] Penutup

### Statistik Otomatis

- [x] Jumlah Anggota Aktif
- [x] Jumlah Sesi
- [x] Persentase Kehadiran
- [x] Jumlah Proposal
- [x] Jumlah Kegiatan
- [x] Jumlah Prestasi

### Lampiran

- [x] Dokumentasi Bulanan

### Workflow

- [x] Submit
- [x] Approval Pembina
- [x] Approval Waka
- [x] Approval Kepala
- [x] Reject Workflow

### Approval Logs

- [x] Auto Logging

### Preview & PDF

- [x] Preview
- [x] Generate PDF
- [x] Lembar Pengesahan

---

## 5.8.3 Testing

### Laporan Kegiatan

- [x] End-to-End Workflow
- [x] Preview
- [x] PDF

### Laporan Bulanan

- [x] Auto Generate
- [x] Statistik
- [x] Preview
- [x] PDF
- [x] End-to-End Workflow

---

# PHASE 6 — PUBLIKASI

---

## Sprint 6.1 Prestasi

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing

---

## Sprint 6.2 Pengumuman

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing

---

## Sprint 6.3 Galeri

- [x] Migration
- [x] Model
- [x] Resource
- [x] Policy
- [x] Testing

---

# PHASE 7 — BK

---

## Sprint 7.1 Konseling

- [x] Migration

- [x] Model

- [x] Resource

- [x] Testing

---

## Sprint 7.2 Permasalahan

- [x] Migration

- [x] Model

- [x] Resource

- [x] Testing

---

## Sprint 7.4 Izin Keluar

- [x] Migration

- [x] Model

- [x] Resource

- [ ] Thermal Print

- [ ] Testing

# PHASE 8 — GURU

---

## Sprint 8.1 Jurnal Mengajar

- [ ] Migration

- [ ] Model

- [ ] Resource

- [ ] Testing

---

## Sprint 8.2 Guru Berhalangan

- [ ] Migration

- [ ] Model

- [ ] Resource

- [ ] Testing

# PHASE 9 — GURU PIKET

## Sprint 9.1 Distribusi Tugas

- [ ] Workflow

- [ ] Resource

- [ ] Testing

## Izin Keluar sama seperti BK

Status:

⬜ BELUM DIMULAI

---

# PHASE 10 — KEHADIRAN

---

## PHASE 10.1 Backend Kehadiran

### Kehadiran Sekolah

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Rekap
- [ ] Export

### Kehadiran Ibadah

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Rekap
- [ ] Export

### Dashboard Kehadiran

- [ ] Widget
- [ ] Statistik
- [ ] Monitoring

Status:

⬜ BELUM DIMULAI

---

## PHASE 10.2 Frontend RFID

### RFID Sekolah

- [ ] Scan Datang
- [ ] Scan Pulang

### RFID Ibadah

- [ ] Scan Dhuha
- [ ] Scan Dzuhur

### RFID Ekstrakurikuler

- [ ] Scan Kehadiran Ekskul

### Monitoring Device

- [ ] Device Validation
- [ ] UID Mapping
- [ ] Error Handling

Status:

⬜ BELUM DIMULAI

---

# PHASE 11 — SYSTEM

---

## Sprint 11.1 Settings

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Testing

---

## Sprint 11.2 Activity Logs

- [ ] Logging Engine
- [ ] Resource
- [ ] Testing

---

## Sprint 11.3 Approval Logs

- [ ] Logging Engine
- [ ] Resource
- [ ] Testing

Status:

⬜ BELUM DIMULAI

---

# PHASE 12 — MIGRASI DATA

Tujuan:

Migrasi Sigma3 Lama ke Sigma3 V4.

Urutan:

- [ ] Users
- [ ] Pegawai
- [ ] Siswa
- [ ] Akademik
- [ ] Ekstrakurikuler
- [ ] Kehadiran
- [ ] BK
- [ ] Publikasi

Status:

⬜ BELUM DIMULAI

---

# PHASE 13 — TESTING

## Internal Testing

- [ ] Super Admin
- [ ] Kepala Madrasah
- [ ] Waka
- [ ] Guru
- [ ] BK
- [ ] Piket
- [ ] Pembina
- [ ] Pelatih
- [ ] Siswa

---

## User Acceptance Test

- [ ] Operator
- [ ] Guru
- [ ] BK
- [ ] Piket
- [ ] Pembina
- [ ] Pelatih

---

## Security Testing

- [ ] Permission
- [ ] Policy
- [ ] Authorization
- [ ] RFID Endpoint

Status:

⬜ BELUM DIMULAI

---

# PHASE 13 — GO LIVE

- [ ] VPS Production
- [ ] Domain
- [ ] SSL
- [ ] Queue Worker
- [ ] Scheduler
- [ ] Backup Strategy
- [ ] Production Import
- [ ] Training Operator
- [ ] Sigma3 Lama Read Only
- [ ] Sigma3 V4 Production

Status:

⬜ BELUM DIMULAI

---

# PHASE 14 — ENHANCEMENT

Future Roadmap:

- [ ] WhatsApp Notification
- [ ] Mobile App
- [ ] E-Raport
- [ ] Sarpras Module
- [ ] PPDB Integration
- [ ] API Integration
- [ ] Parent Portal
- [ ] Teacher Mobile Portal

Status:

⬜ FUTURE

---

# DEVELOPMENT RULES

Urutan wajib:

1. Decision
2. ERD
3. Database Blueprint
4. Migration
5. Model
6. Factory
7. Seeder
8. Policy
9. Resource
10. Testing
11. Commit
12. Checklist Update

Dilarang:

- Membuat Resource sebelum Migration selesai
- Membuat Policy sebelum Permission final
- Membuat Seeder sebelum Model selesai
- Mengubah Database tanpa Update Decision

---

# PROJECT STATUS

Architecture:
🟩 Final

Database:
🟩 Final

Roles:
🟩 Final

Permission:
🟩 Final

Decision:
🟩 Final

ERD:
🟩 Final

Masterplan:
🟩 Final

Ready For Development:
🟩 YES
