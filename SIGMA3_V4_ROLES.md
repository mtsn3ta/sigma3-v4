# SIGMA3_V4_ROLES.md

Status:
🟩 Final v2

---

# TUJUAN

Dokumen ini mendefinisikan seluruh role resmi yang digunakan dalam SIGMA3 V4.

Role digunakan sebagai dasar untuk:

- Authentication
- Authorization
- Permission Matrix
- Dashboard
- Filament Navigation
- Approval Workflow
- Spatie Permission

---

# DESIGN PRINCIPLE

SIGMA3 V4 menggunakan:

- Multi Role
- Spatie Permission

Role tidak bersifat hierarki.

Satu user dapat memiliki lebih dari satu role.

Hak akses user merupakan gabungan seluruh permission dari role yang dimiliki.

Contoh:

```text
guru
piket
pembina
pelatih
```

akan mendapatkan seluruh permission:

- Guru
- Piket
- Pembina
- Pelatih

sekaligus.

---

# ROLE ARCHITECTURE

SIGMA3 V4 memiliki 12 role resmi:

```text
super_admin

kepala_madrasah

guru

waka_kesiswaan
waka_kurikulum
waka_humas
waka_sarpras

bk
piket

pembina
pelatih

siswa
```

---

# ROLE DEFINITIONS

## 1. Super Admin

Role sistem.

Memiliki akses penuh terhadap seluruh modul.

Nama Role:

```php
super_admin
```

Hak akses:

- Seluruh modul
- Seluruh dashboard
- Seluruh approval
- Manajemen user
- Manajemen role
- Manajemen permission
- System Settings

---

## 2. Kepala Madrasah

Role monitoring dan approval tertinggi.

Nama Role:

```php
kepala_madrasah
```

Tugas:

- Monitoring seluruh sistem
- Monitoring seluruh dashboard
- Approval Proposal Kegiatan
- Approval Laporan Bulanan
- Monitoring BK
- Monitoring Kehadiran
- Monitoring Ekstrakurikuler
- Monitoring Publikasi

---

## 3. Guru

Role dasar pegawai pengajar.

Nama Role:

```php
guru
```

Tugas:

- Jurnal Mengajar
- Guru Berhalangan

Catatan:

Seluruh guru wajib memiliki role:

```text
guru
```

---

## 4. Waka Kesiswaan

Role tambahan untuk Guru.

Nama Role:

```php
waka_kesiswaan
```

Tugas:

- Monitoring Kehadiran Sekolah
- Monitoring Kehadiran Ibadah
- Monitoring BK
- Monitoring Ekstrakurikuler
- Monitoring Prestasi
- Approval Proposal Kegiatan
- Approval Laporan Bulanan

---

## 5. Waka Kurikulum

Role tambahan untuk Guru.

Nama Role:

```php
waka_kurikulum
```

Tugas:

- Monitoring Tahun Ajaran
- Monitoring Kelas
- Monitoring Jurnal Mengajar

---

## 6. Waka Humas

Role tambahan untuk Guru.

Nama Role:

```php
waka_humas
```

Tugas:

- Monitoring Prestasi
- Monitoring Pengumuman
- Monitoring Galeri

---

## 7. Waka Sarpras

Role tambahan untuk Guru.

Nama Role:

```php
waka_sarpras
```

Tugas:

- Monitoring sesuai kebutuhan Sarana dan Prasarana

Catatan:

Modul Sarpras belum termasuk pada SIGMA3 V4 tahap awal.

---

## 8. BK

Role tambahan untuk Guru.

Nama Role:

```php
bk
```

Tugas:

- Konseling Siswa
- Permasalahan Siswa
- Monitoring Kehadiran
- Izin Keluar
- Dashboard BK

---

## 9. Piket

Role tambahan untuk Guru.

Nama Role:

```php
piket
```

Tugas:

- Monitoring Kehadiran Sekolah
- Monitoring Kehadiran Ibadah
- Rekap Kehadiran
- Distribusi Tugas Guru
- Izin Keluar

---

## 10. Pembina

Role tambahan untuk Guru.

Nama Role:

```php
pembina
```

Tugas:

- Penanggung Jawab Ekstrakurikuler
- Kelola Anggota Ekskul
- Monitoring Sesi Kegiatan
- Monitoring Kehadiran Ekskul
- Membuat Proposal Kegiatan
- Membuat Laporan Bulanan
- Review Proposal Kegiatan
- Review Laporan Bulanan
- Approval Proposal Kegiatan
- Approval Laporan Bulanan
- Monitoring Statistik Ekskul

Catatan:

- Satu Pembina dapat mengampu lebih dari satu ekstrakurikuler.
- Pembina dapat merangkap sebagai Pelatih.
- Jika Pembina juga memiliki role Pelatih, maka dapat:
    - Kelola Anggota Ekskul
    - Membuat Sesi Kegiatan
    - Mengelola Kehadiran Ekskul
    - Membuat Proposal Kegiatan
    - Membuat Laporan Bulanan

---

## 11. Pelatih

Role operasional ekstrakurikuler.

Nama Role:

```php
pelatih
```

Tugas:

- Kelola Anggota Ekskul
- Membuat Sesi Kegiatan
- Mengelola Kehadiran Ekskul
- Membuat Proposal Kegiatan
- Membuat Laporan Bulanan

Catatan:

Pelatih dapat berasal dari:

- Pegawai Internal
- Pegawai Eksternal

Pelatih dapat dirangkap oleh Pembina pada ekstrakurikuler yang sama maupun berbeda.

---

## 12. Siswa

Role peserta didik.

Nama Role:

```php
siswa
```

Tugas:

- Melihat Profil
- Melihat Kehadiran Sekolah
- Melihat Kehadiran Ibadah
- Melihat Riwayat Ekskul
- Melihat Prestasi
- Melihat Pengumuman

---

# MULTI ROLE RULES

SIGMA3 V4 mendukung Multi Role.

Contoh kombinasi valid:

## Guru Biasa

```text
guru
```

---

## Guru BK

```text
guru
bk
```

---

## Guru Piket

```text
guru
piket
```

---

## Guru Pembina

```text
guru
pembina
```

---

## Guru Pelatih

```text
guru
pelatih
```

---

## Guru Pembina + Pelatih

```text
guru
pembina
pelatih
```

---

## Guru Piket + Pembina

```text
guru
piket
pembina
```

---

## Guru Piket + Pelatih

```text
guru
piket
pelatih
```

---

## Guru Piket + Pembina + Pelatih

```text
guru
piket
pembina
pelatih
```

---

## Guru BK + Piket

```text
guru
bk
piket
```

---

## Guru BK + Pembina

```text
guru
bk
pembina
```

---

## Guru BK + Pelatih

```text
guru
bk
pelatih
```

---

## Guru BK + Piket + Pembina + Pelatih

```text
guru
bk
piket
pembina
pelatih
```

---

## Waka Kesiswaan

```text
guru
waka_kesiswaan
```

---

## Waka Kurikulum

```text
guru
waka_kurikulum
```

---

## Waka Humas

```text
guru
waka_humas
```

---

## Waka Sarpras

```text
guru
waka_sarpras
```

---

## Pelatih Eksternal

```text
pelatih
```

Tanpa role guru.

---

# ROLE RESTRICTIONS

## Kepala Madrasah

Tidak dapat memiliki role:

```text
guru
bk
piket
pembina
pelatih
```

---

## Waka

Tidak dapat memiliki role:

```text
pembina
pelatih
```

Karena berfungsi sebagai pihak monitoring dan approval.

---

## Siswa

Tidak dapat memiliki role pegawai.

---

# ROLE SEEDER OUTPUT

Roles yang dibuat oleh:

```php
RolesSeeder.php
```

Daftar Role:

```php
super_admin

kepala_madrasah

guru

waka_kesiswaan
waka_kurikulum
waka_humas
waka_sarpras

bk
piket

pembina
pelatih

siswa
```

Total:

```text
12 Roles
```

---

# RELASI DENGAN DATABASE

Role disimpan menggunakan:

```text
Spatie Permission
```

Tabel:

```text
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

Pegawai:

```text
users
↓
pegawai
```

Siswa:

```text
users
↓
siswa
```

Satu user dapat memiliki lebih dari satu role.

---

# STATUS

Role Architecture
🟩 Final

Multi Role Rules
🟩 Final

Role Restrictions
🟩 Final

Roles Seeder Blueprint
🟩 Final

Database Consistency
🟩 Final

Siap digunakan untuk:

- RolesSeeder.php
- PermissionSeeder.php
- RolePermissionSeeder.php
- Filament Navigation
- Authorization Layer
- Dashboard Configuration
