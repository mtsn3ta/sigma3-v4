# SIGMA3 V4 DECISIONS

Status:
🟨 Active

Last Updated:
2026-06-22

---

# TUJUAN DOKUMEN

Dokumen ini berisi seluruh keputusan bisnis, workflow, dan aturan sistem yang telah disepakati.

Dokumen ini menjadi referensi utama sebelum membuat:

- Database Blueprint
- Migration
- Model
- Filament Resource
- Permission Matrix

Jika ada perubahan bisnis, dokumen ini harus diperbarui terlebih dahulu.

---

# D-001

## Arsitektur Authentication

Keputusan:

Menggunakan satu tabel users sebagai identitas login utama.

Struktur:

users
├── pegawai
└── siswa

Role menggunakan Spatie Permission.

Alasan:

- Satu sistem login
- Mendukung multi role
- Mendukung pelatih eksternal
- Mudah dikelola
- Mudah dikembangkan

Status:

✅ Final

---

# D-002

## Multi Role System

Satu user dapat memiliki lebih dari satu role.

Contoh:

Guru + BK

Guru + Piket

Guru + Pembina

Guru + Pelatih

Guru + Pembina + Pelatih

Status:

✅ Final

---

# D-003

## Role Final Sistem

Role yang digunakan:

- super_admin

- kepala

- waka_kesiswaan
- waka_kurikulum
- waka_humas
- waka_sarpras

- guru

- bk

- piket

- pembina

- pelatih

- siswa

Status:

✅ Final

---

# D-004

## Kepala Madrasah

Kepala Madrasah hanya berfungsi sebagai monitoring dan approval.

Dapat:

- Monitoring seluruh modul
- Monitoring dashboard
- Approval akhir laporan
- Approval akhir proposal

Tidak dapat:

- Menjadi Guru
- Menjadi BK
- Menjadi Piket
- Menjadi Pembina
- Menjadi Pelatih

Status:

✅ Final

---

# D-005

## Waka

Jenis Waka:

- Waka Kesiswaan
- Waka Kurikulum
- Waka Humas
- Waka Sarpras

Aturan:

Semua Waka otomatis memiliki role Guru.

Waka tidak dapat menjadi:

- Pembina
- Pelatih

Status:

✅ Final

---

# D-006

## Guru

Hak akses:

- Jurnal Mengajar
- Tugas Saat Berhalangan
- Monitoring Riwayat Tugas
- Monitoring Riwayat Jurnal

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

BK merupakan role tambahan dari Guru.

Hak akses:

- Monitoring Kehadiran Sekolah
- Monitoring Kehadiran Ibadah
- Input Permasalahan Siswa
- Input Konseling Siswa
- Dashboard BK

Status:

✅ Final

---

# D-008

## Piket

Piket merupakan role tambahan dari Guru.

Hak akses:

- Monitoring Kehadiran Sekolah
- Koreksi Kehadiran Manual
- Menentukan status siswa
- Mengelola Izin Keluar
- Menyalurkan Tugas Guru Berhalangan

Status:

✅ Final

---

# D-009

## Pembina

Pembina merupakan penanggung jawab ekstrakurikuler.

Hak akses:

- Monitoring Anggota
- Monitoring Kehadiran
- Monitoring Kegiatan
- Menentukan Pelatih
- Verifikasi Laporan Bulanan
- Monitoring Statistik

Tidak wajib:

- Membuat Sesi Kegiatan
- Mengisi Kehadiran
- Membuat Laporan

Status:

✅ Final

---

# D-010

## Pelatih

Pelatih merupakan pelaksana operasional ekstrakurikuler.

Hak akses:

- Membuat Sesi Kegiatan
- Mengelola Kehadiran Ekskul
- Membuat Laporan Bulanan
- Mengelola Kegiatan Operasional

Status:

✅ Final

---

# D-011

## Pembina Merangkap Pelatih

Jika seseorang memiliki role:

- pembina
- pelatih

Maka mendapatkan seluruh hak akses keduanya.

Status:

✅ Final

---

# D-012

## Pelatih Eksternal

Pelatih eksternal tetap memiliki akun sendiri.

Jenis Pegawai:

- internal
- eksternal

Pelatih eksternal:

- Dapat Login
- Dapat Membuat Sesi
- Dapat Mengisi Kehadiran
- Dapat Membuat Laporan

Tidak dapat:

- Mengakses Akademik
- Mengakses BK
- Mengakses Modul Guru

Alasan:

Sigma3 Lama menggunakan sistem registrasi mandiri pelatih eksternal.

Status:

✅ Final

---

# D-013

## Kehadiran Dipisah Menjadi Tiga Modul

Jenis Kehadiran:

1. Kehadiran Sekolah
2. Kehadiran Ibadah
3. Kehadiran Ekstrakurikuler

Alasan:

Setiap kehadiran memiliki tujuan monitoring berbeda.

Status:

✅ Final

---

# D-014

## Workflow Kehadiran Sekolah

Lokasi:

Gerbang Madrasah

Metode:

- RFID
- Koreksi Manual Piket

Alur:

1. Siswa scan RFID saat datang.
2. Sistem mencatat otomatis.
3. Setelah batas waktu masuk:
    - Sistem menampilkan siswa yang belum scan.
4. Guru Piket melakukan pengecekan.
5. Guru Piket menentukan status:
    - Telat
    - Izin
    - Sakit
    - Alpa
6. Siswa scan RFID saat pulang.

Status:

✅ Final

---

# D-015

## Workflow Kehadiran Ibadah

Lokasi:

Masjid

Jenis:

- Dhuha
- Dzuhur

Metode:

- RFID
- Manual

Alur:

1. Siswa scan RFID.
2. Sistem mencatat kehadiran ibadah.
3. Petugas dapat melakukan koreksi bila diperlukan.

Status:

✅ Final

---

# D-016

## Workflow Kehadiran Ekstrakurikuler

Lokasi:

Area Kegiatan Ekstrakurikuler

Metode:

- RFID
- Manual oleh Pelatih

Alur:

1. Pelatih membuat sesi kegiatan.
2. Siswa melakukan scan RFID.
3. Pelatih melakukan koreksi bila diperlukan.
4. Kehadiran masuk ke statistik dan laporan.

Status:

✅ Final

---

# D-017

## Workflow Laporan Bulanan Ekstrakurikuler

Alur Approval:

Pelatih
↓
Submit Laporan

Pembina
↓
Verifikasi

Waka Kesiswaan
↓
Verifikasi

Kepala Madrasah
↓
Final Approval

Status Laporan:

- draft
- pending_pembina
- pending_waka
- pending_kepala
- approved
- rejected

Status:

✅ Final

---

# D-018

## Workflow Proposal Kegiatan

Alur Approval:

Pembina atau Pelatih
↓
Membuat Proposal

Waka Kesiswaan
↓
Verifikasi

Kepala Madrasah
↓
Final Approval

Status Proposal:

- draft
- pending_waka
- pending_kepala
- approved
- rejected

Status:

✅ Final

---

# D-019

## Dashboard BK

Dashboard BK mengambil data dari:

- Kehadiran Sekolah
- Kehadiran Ibadah
- Permasalahan Siswa
- Konseling Siswa

Menampilkan:

- Ranking Poin Tertinggi
- Siswa Rawan Alpa
- Siswa Rawan Telat
- Siswa Tidak Mengikuti Ibadah
- Konseling Bulan Berjalan
- Kasus Terbaru
- Statistik Pelanggaran

Status:

✅ Final

---

# D-020

## Data Pegawai

Admin hanya menginput data dasar:

- Nama
- NIP/NIK
- No HP
- RFID

Pegawai dapat melengkapi sendiri:

- Foto
- Alamat
- Email
- Tempat Lahir
- Tanggal Lahir
- Tanda Tangan Digital

Status:

✅ Final

---

# D-021

## Data Siswa

Struktur siswa mengikuti Sigma3 Lama.

Alasan:

- Data sudah lengkap
- Memudahkan migrasi
- Meminimalkan perubahan operasional

Status:

✅ Final

---

# D-022

## Migrasi Data Sigma Lama

Sigma3 Lama tetap menjadi sumber data utama.

Migrasi dilakukan setelah seluruh modul Sigma3 V4 selesai.

Tahapan:

1. Struktur Final
2. Testing
3. Mapping Data
4. Import
5. Verifikasi
6. Go Live

Status:

✅ Final

---

# D-023

## Prinsip Pengembangan

Urutan kerja wajib:

1. Decision
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
