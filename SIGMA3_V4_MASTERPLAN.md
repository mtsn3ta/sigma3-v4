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

PHASE:

✅ PHASE 1.1 Audit Sistem Lama

✅ PHASE 1.2 ERD & Domain Design

✅ PHASE 2 Database Blueprint

CURRENT:

🟨 PHASE 3 Foundation

NEXT:

⬜ PHASE 4 — Master Data

Status:

🟨 READY TO START

---

# PHASE 0 — FOUNDATION PROJECT

- [x] Laravel 13
- [x] Filament 4
- [x] Spatie Permission
- [x] Git Repository
- [x] v0.1-foundation Tag

Status:

✅ SELESAI

# PHASE 1 — ANALISIS

Tujuan:

Memahami Sigma3 Lama sebelum pembangunan Sigma3 V4.

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

# PHASE 2 — DATABASE BLUEPRINT

Tujuan:

Menyusun struktur tabel final Sigma3 V4.

Output:

- [x] Daftar tabel final
- [x] Relasi tabel final
- [x] Naming convention
- [x] Migration plan

Status:

✅ SELESAI

---

# PHASE 3 — FOUNDATION

## Authentication

- [ ] User Architecture Review
- [ ] User Model Customization
- [ ] Username Login (NISN/NIK)
- [ ] Profile Management

## Filament

- [x] Install Filament
- [ ] Admin Panel Setup
- [ ] Dashboard Setup

## Permission

- [x] Install Spatie
- [ ] Roles Seeder
- [ ] Permission Seeder

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

## Pegawai

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Testing

## Siswa

- [ ] Migration
- [ ] Model
- [ ] Resource
- [ ] Testing

## Tahun Ajaran

- [ ] Migration
- [ ] Resource

## Kelas

- [ ] Migration
- [ ] Resource

## Role Management

- [ ] Resource
- [ ] Testing

Status:

⬜ BELUM DIMULAI

---

# PHASE 5 — EKSTRAKURIKULER

## Ekstrakurikuler

- [ ] Migration
- [ ] Resource

## Pengampu

- [ ] Migration
- [ ] Resource

## Anggota

- [ ] Migration
- [ ] Resource

## Sesi Kegiatan

- [ ] Migration
- [ ] Resource

## Kehadiran Ekskul

- [ ] Migration
- [ ] Resource

## Laporan Bulanan

- [ ] Migration
- [ ] Resource
- [ ] Workflow Approval

## Proposal Kegiatan

- [ ] Migration
- [ ] Resource
- [ ] Approval Workflow

Status:

⬜ BELUM DIMULAI

---

# PHASE 6 — KEHADIRAN

## Kehadiran Sekolah

- [ ] Migration
- [ ] Resource

## Kehadiran Ibadah

- [ ] Migration
- [ ] Resource

## RFID Integration

- [ ] Reader
- [ ] Endpoint
- [ ] Testing

Catatan:

Kehadiran Ekskul dikerjakan pada Phase 5.

Status:

⬜ BELUM DIMULAI

---

# PHASE 7 — BK

## Konseling

- [ ] Migration
- [ ] Resource

## Permasalahan

- [ ] Migration
- [ ] Resource

## Dashboard BK

- [ ] Widget
- [ ] Statistik

## Izin Keluar

- [ ] Migration
- [ ] Resource

Status:

⬜ BELUM DIMULAI

---

# PHASE 8 — GURU

## Jurnal Mengajar

- [ ] Migration
- [ ] Resource

## Guru Berhalangan

- [ ] Migration
- [ ] Resource

## Distribusi Tugas

- [ ] Workflow
- [ ] Testing

Status:

⬜ BELUM DIMULAI

---

# PHASE 9 — PUBLIKASI

## Prestasi

- [ ] Migration
- [ ] Resource

## Pengumuman

- [ ] Migration
- [ ] Resource

## Galeri

- [ ] Migration
- [ ] Resource

Status:

⬜ BELUM DIMULAI

---

# PHASE 10 — MIGRASI DATA

## Master Data

- [ ] Pegawai
- [ ] Users
- [ ] Roles
- [ ] Siswa

## Akademik

- [ ] Tahun Ajaran
- [ ] Kelas

## Ekstrakurikuler

- [ ] Pengampu
- [ ] Anggota

## BK

- [ ] Konseling
- [ ] Permasalahan

## Kehadiran

- [ ] Kehadiran Sekolah
- [ ] Kehadiran Ibadah

## Publikasi

- [ ] Prestasi
- [ ] Pengumuman
- [ ] Galeri

Status:

⬜ BELUM DIMULAI

---

# PHASE 11 — TESTING

## UAT

- [ ] Kepala
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

## Deploy

- [ ] VPS
- [ ] SSL
- [ ] Queue
- [ ] Backup

## Cut Over

- [ ] Import Produksi
- [ ] Sigma3 Lama Read Only
- [ ] Sigma3 V4 Aktif

- [ ] Training Operator

Status:

⬜ BELUM DIMULAI

---

# GIT HISTORY

## v0.1-foundation

✅ Laravel 13

✅ Filament 4

✅ Spatie Permission

✅ Git Repository

✅ Audit Awal Sigma3 Lama

---

# RULES

Urutan kerja wajib:

1. Blueprint
2. Migration
3. Model
4. Resource
5. Testing
6. Commit
7. Update Checklist

Dilarang membuat Resource sebelum Migration final.
