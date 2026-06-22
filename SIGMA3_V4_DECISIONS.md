# SIGMA3_V4_DECISIONS.md

Status:
🟩 Final

Last Updated:
2026-06-23

---

# TUJUAN DOKUMEN

Dokumen ini berisi seluruh keputusan bisnis, workflow, dan aturan sistem yang telah disepakati.

Dokumen ini menjadi referensi utama sebelum membuat:

- ERD
- Database Blueprint
- Migration
- Model
- Filament Resource
- Permission Matrix
- Dashboard

Jika terdapat perubahan aturan bisnis, dokumen ini wajib diperbarui terlebih dahulu.

---

# D-001

## Authentication Architecture

SIGMA3 V4 menggunakan satu akun untuk seluruh sistem.

Struktur:

users
├── pegawai
└── siswa

Role menggunakan Spatie Permission.

Status:
✅ Final

---

# D-002

## Multi Role System

Satu user dapat memiliki lebih dari satu role.

Contoh:

- guru + bk
- guru + piket
- guru + pembina
- guru + pelatih
- guru + pembina + pelatih

Permission merupakan gabungan seluruh role yang dimiliki.

Status:
✅ Final

---

# D-003

## Role Final Sistem

Role resmi:

- super_admin
- kepala_madrasah
- guru
- waka_kesiswaan
- waka_kurikulum
- waka_humas
- waka_sarpras
- bk
- piket
- pembina
- pelatih
- siswa

Total:

12 Roles

Status:
✅ Final

---

# D-004

## Kepala Madrasah

Kepala Madrasah berfungsi sebagai monitoring dan approver tertinggi.

Dapat:

- Monitoring seluruh modul
- Monitoring dashboard
- Approval akhir proposal
- Approval akhir laporan

Tidak dapat:

- Guru
- BK
- Piket
- Pembina
- Pelatih

Status:
✅ Final

---

# D-005

## Waka

Jenis:

- Waka Kesiswaan
- Waka Kurikulum
- Waka Humas
- Waka Sarpras

Semua Waka wajib memiliki role:

- guru

Waka tidak dapat memiliki role:

- pembina
- pelatih

Status:
✅ Final

---

# D-006

## Guru

Role dasar pegawai pengajar.

Hak akses:

- Jurnal Mengajar
- Guru Berhalangan

Role tambahan:

- BK
- Piket
- Pembina
- Pelatih

Status:
✅ Final

---

# D-007

## BK

Role tambahan Guru.

Hak akses:

- Konseling Siswa
- Permasalahan Siswa
- Monitoring Kehadiran
- Izin Keluar
- Dashboard BK

Status:
✅ Final

---

# D-008

## Piket

Role tambahan Guru.

Hak akses:

- Monitoring Kehadiran
- Koreksi Kehadiran Manual
- Rekap Kehadiran
- Distribusi Tugas Guru Berhalangan
- Izin Keluar

Status:
✅ Final

---

# D-009

## Pembina

Pembina merupakan penanggung jawab ekstrakurikuler.

Hak akses:

- Kelola Anggota Ekskul
- Monitoring Sesi Kegiatan
- Monitoring Kehadiran Ekskul
- Membuat Proposal Kegiatan
- Membuat Laporan Bulanan
- Review Proposal
- Review Laporan
- Approve Proposal
- Approve Laporan
- Reject Proposal
- Reject Laporan
- Monitoring Statistik Ekskul

Catatan:

- Satu pembina dapat mengampu lebih dari satu ekstrakurikuler.
- Pembina dapat merangkap sebagai pelatih.

Status:
✅ Final

---

# D-010

## Pelatih

Pelatih merupakan pelaksana operasional ekstrakurikuler.

Hak akses:

- Kelola Anggota Ekskul
- Membuat Sesi Kegiatan
- Mengelola Kehadiran Ekskul
- Membuat Proposal Kegiatan
- Membuat Laporan Bulanan

Pelatih dapat berasal dari:

- Pegawai Internal
- Pegawai Eksternal

Status:
✅ Final

---

# D-011

## Pembina Merangkap Pelatih

Jika user memiliki role:

- pembina
- pelatih

Maka seluruh permission kedua role digabungkan.

Status:
✅ Final

---

# D-012

## Pelatih Eksternal

Pelatih eksternal tetap memiliki akun login sendiri.

Jenis Pegawai:

- internal
- eksternal

Pelatih eksternal:

- Dapat Login
- Dapat Kelola Anggota
- Dapat Membuat Sesi
- Dapat Mengelola Kehadiran
- Dapat Membuat Proposal
- Dapat Membuat Laporan

Tidak dapat:

- Mengakses Akademik
- Mengakses BK
- Mengakses Modul Guru

Status:
✅ Final

---

# D-013

## Arsitektur Kehadiran

Kehadiran dipisah menjadi:

1. Kehadiran Sekolah
2. Kehadiran Ibadah
3. Kehadiran Ekstrakurikuler

Status:
✅ Final

---

# D-014

## Kehadiran Sekolah

Metode:

- RFID
- Manual oleh Piket

Status yang digunakan:

- hadir
- telat
- izin
- sakit
- alpa

Status:
✅ Final

---

# D-015

## Kehadiran Ibadah

Jenis:

- dhuha
- dzuhur

Metode:

- RFID
- Manual

Status:
✅ Final

---

# D-016

## Kehadiran Ekstrakurikuler

Metode:

- RFID
- Manual oleh Pelatih

Alur:

Pelatih membuat sesi
↓
Siswa hadir
↓
RFID / Manual
↓
Statistik Kehadiran

Status:
✅ Final

---

# D-017

## Workflow Laporan Bulanan

Pembuat:

- Pembina
- Pelatih

Workflow:

Draft
↓
Pending Pembina
↓
Pending Waka Kesiswaan
↓
Pending Kepala Madrasah
↓
Approved / Rejected

Status:
✅ Final

---

# D-018

## Workflow Proposal Kegiatan

Pembuat:

- Pembina
- Pelatih

Workflow:

Draft
↓
Pending Pembina
↓
Pending Waka Kesiswaan
↓
Pending Kepala Madrasah
↓
Approved / Rejected

Status:
✅ Final

---

# D-019

## Dashboard BK

Data sumber:

- Kehadiran Sekolah
- Kehadiran Ibadah
- Konseling
- Permasalahan

Menampilkan:

- Ranking Pelanggaran
- Siswa Rawan Alpa
- Siswa Rawan Telat
- Statistik Konseling
- Statistik Pelanggaran

Status:
✅ Final

---

# D-020

## Data Pegawai

Admin menginput:

- Nama
- NIK/NIP
- No HP
- RFID

Pegawai melengkapi sendiri:

- Email
- Foto
- Alamat
- Tempat Lahir
- Tanggal Lahir
- TTD Digital

Status:
✅ Final

---

# D-021

## Data Siswa

Struktur siswa mengikuti Sigma3 Lama.

Tujuan:

- Memudahkan migrasi
- Meminimalkan perubahan operasional

Status:
✅ Final

---

# D-022

## Migrasi Data Sigma Lama

Tahapan:

1. Struktur Final
2. Testing
3. Mapping
4. Import
5. Verifikasi
6. Go Live

Status:
✅ Final

---

# D-023

## Authentication Strategy

Username:

- Pegawai Internal = NIK/NIP
- Pelatih Eksternal = NIK
- Siswa = NISN

Password Awal:

mtsn3ta123

Login pertama:

must_change_password = true

Wajib mengganti password.

Status:
✅ Final

---

# D-024

## RFID Attendance Architecture

Pencatatan RFID dilakukan melalui frontend khusus.

Filament digunakan untuk:

- Monitoring
- Koreksi
- Rekap
- Pelaporan

Status:
✅ Final

---

# D-025

## Izin Keluar

Izin Keluar bukan bagian dari BK.

Dapat diakses oleh:

- BK
- Piket

Data tambahan:

- pegawai_id
- waktu_cetak

Status:
✅ Final

---

# D-026

## Navigation Structure

Menu utama:

- Dashboard
- Master Data
- Monitoring Kehadiran
- BK
- Guru
- Guru Piket
- Ekstrakurikuler
- Publikasi
- System

Status:
✅ Final

---

# D-027

## Publikasi Prestasi

Dapat dikelola oleh:

- Super Admin
- Waka Humas
- Pembina
- Pelatih

Status:
✅ Final

---

# D-028

## Publikasi Pengumuman

Dapat dikelola oleh:

- Super Admin
- Waka Kesiswaan
- Waka Kurikulum
- Waka Humas
- Waka Sarpras
- Pembina
- Pelatih

Status:
✅ Final

---

# D-029

## Publikasi Galeri

Dapat dikelola oleh:

- Super Admin
- Waka Humas
- Pembina
- Pelatih

Status:
✅ Final

---

# D-030

## System Module

Modul System terdiri dari:

- Settings
- Activity Logs
- Approval Logs

Settings hanya dapat diubah oleh:

- Super Admin

Status:
✅ Final

---

# D-031

## Prinsip Pengembangan

Urutan kerja wajib:

1. Decisions
2. ERD
3. Database Blueprint
4. Migration
5. Model
6. Resource
7. Testing
8. Commit
9. Checklist Update

Dilarang membuat Resource sebelum Migration final.

Status:
✅ Final
