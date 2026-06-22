# SIGMA3 V4 MASTERPLAN

## INFORMASI PROYEK

Nama Sistem : SIGMA3 V4

Instansi : MTs Negeri 3 Tulungagung

Framework:

- Laravel 13
- Filament 4
- Spatie Permission

Repository:

- main

Status:

🟨 Development

---

# VISI PROYEK

SIGMA3 V4 adalah sistem terintegrasi untuk:

- Akademik
- Kesiswaan
- Kehadiran
- BK
- Ekstrakurikuler
- Guru
- Publikasi

Dengan:

- Satu database
- Satu sistem akun
- Multi role
- Mudah dikembangkan
- Siap jangka panjang

---

# CURRENT SPRINT

CURRENT:

🟨 PHASE 3 — FOUNDATION

NEXT:

⬜ PHASE 4 — MASTER DATA

---

# PHASE 0 — FOUNDATION PROJECT

- [x] Laravel 13
- [x] Filament 4
- [x] Spatie Permission
- [x] Git Repository
- [x] Tag v0.1-foundation

Status:

✅ SELESAI

---

# PHASE 1 — ANALISIS

Tujuan:

Memahami Sigma3 Lama.

## Audit Database

- [x] Users
- [x] Siswa
- [x] Guru Role
- [x] Pembina Pelatih
- [x] Ekstrakurikuler
- [x] Anggota Ekskul
- [x] Ekskul Pengampu
- [x] Absensi
- [x] Absensi Harian
- [x] Log Absensi
- [x] BK Konseling
- [x] BK Permasalahan
- [x] Kelas
- [x] Kelas Siswa
- [x] Tahun Ajaran

## Audit Lanjutan

- [x] Jurnal Mengajar
- [x] Tugas Guru Izin
- [x] Izin Keluar
- [x] Proposal Kegiatan
- [x] Sesi Kegiatan
- [x] Prestasi
- [x] Pengumuman
- [x] Galeri

Status:

✅ SELESAI

---

# PHASE 2 — ARSITEKTUR

Output:

- [x] ERD Final
- [x] Database Blueprint
- [x] Decision Log
- [x] Permission Matrix

Status:

✅ SELESAI

---

# PHASE 3 — FOUNDATION

## Sprint 3.1 User Architecture

- [x] Review Users Blueprint
- [x] Review Pegawai Relation
- [x] Review Siswa Relation
- [x] Final Login Strategy
- [x] Update Decision Log

## Sprint 3.2 Authentication

- [ ] User Model Customization
- [ ] Username Login (NIK/NISN)
- [ ] Profile Management
- [ ] Authentication Testing

## Sprint 3.3 Filament Setup

- [x] Install Filament
- [ ] Admin Panel Setup
- [ ] Dashboard Setup

## Sprint 3.4 Permission Foundation

- [x] Install Spatie Permission
- [ ] Roles Seeder
- [ ] Permission Seeder
- [ ] Permission Testing

Status:

⬜ BELUM DIMULAI

---

# PHASE 3.5 — AUTHORIZATION

## Roles

- [ ] Super Admin
- [ ] Kepala Madrasah
- [ ] Waka
- [ ] Guru
- [ ] BK
- [ ] Piket
- [ ] Pembina
- [ ] Pelatih
- [ ] Siswa

## Permission Matrix

- [ ] Final Review
- [ ] Seeder
- [ ] Testing

Status:

⬜ BELUM DIMULAI

---

# PHASE 4 — MASTER DATA

## Sprint 4.1 Pegawai

- [ ] Migration
- [ ] Model
- [ ] Seeder
- [ ] Resource
- [ ] Testing
- [ ] Commit

## Sprint 4.2 Siswa

- [ ] Migration
- [ ] Model
- [ ] Seeder
- [ ] Resource
- [ ] Testing
- [ ] Commit

## Sprint 4.3 Tahun Ajaran

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Testing
- [ ] Commit

## Sprint 4.4 Kelas

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Testing
- [ ] Commit

## Sprint 4.5 Role Management

- [ ] Resource
- [ ] Testing
- [ ] Commit

Status:

⬜ BELUM DIMULAI

---

# PHASE 5 — EKSTRAKURIKULER

- [ ] Ekstrakurikuler
- [ ] Pengampu
- [ ] Anggota Ekskul
- [ ] Sesi Kegiatan
- [ ] Kehadiran Ekskul
- [ ] Laporan Bulanan
- [ ] Proposal Kegiatan

Status:

⬜ BELUM DIMULAI

---

# PHASE 6 — KEHADIRAN

- [ ] Kehadiran Sekolah
- [ ] Kehadiran Ibadah
- [ ] RFID Integration
- [ ] Dashboard Kehadiran

Status:

⬜ BELUM DIMULAI

---

# PHASE 7 — BK

- [ ] Konseling
- [ ] Permasalahan
- [ ] Dashboard BK
- [ ] Izin Keluar

Status:

⬜ BELUM DIMULAI

---

# PHASE 8 — GURU

- [ ] Jurnal Mengajar
- [ ] Guru Berhalangan
- [ ] Distribusi Tugas

Status:

⬜ BELUM DIMULAI

---

# PHASE 9 — PUBLIKASI

- [ ] Prestasi
- [ ] Pengumuman
- [ ] Galeri

Status:

⬜ BELUM DIMULAI

---

# PHASE 10 — MIGRASI DATA

- [ ] Master Data
- [ ] Akademik
- [ ] Ekstrakurikuler
- [ ] Kehadiran
- [ ] BK
- [ ] Publikasi

Status:

⬜ BELUM DIMULAI

---

# PHASE 11 — TESTING

## User Acceptance Test

- [ ] Kepala Madrasah
- [ ] Waka
- [ ] Guru
- [ ] BK
- [ ] Piket
- [ ] Pembina
- [ ] Pelatih
- [ ] Siswa

Status:

⬜ BELUM DIMULAI

---

# PHASE 12 — GO LIVE

- [ ] Deploy VPS
- [ ] SSL
- [ ] Queue Worker
- [ ] Backup
- [ ] Import Produksi
- [ ] Training Operator
- [ ] Sigma3 Lama Read Only
- [ ] Sigma3 V4 Aktif

Status:

⬜ BELUM DIMULAI

---

# RULES

Urutan pekerjaan wajib:

1. Decision
2. ERD
3. Blueprint
4. Migration
5. Model
6. Seeder
7. Resource
8. Testing
9. Commit
10. Update Checklist

Dilarang membuat Resource sebelum Migration selesai dan diuji.
