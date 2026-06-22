# SIGMA3_V4_PERMISSION_MATRIX.md

Status:
🟩 Final v2

---

# TUJUAN

Dokumen ini mendefinisikan seluruh hak akses (permission) pada SIGMA3 V4.

Dokumen ini menjadi dasar untuk:

- Spatie Permission
- PermissionSeeder.php
- RolePermissionSeeder.php
- Filament Authorization
- Dashboard Authorization
- Approval Workflow

Dokumen ini harus selalu konsisten dengan:

- SIGMA3_V4_DATABASE_BLUEPRINT.md
- SIGMA3_V4_ROLES.md

---

# ROLE STRUCTURE

```text
Super Admin

Kepala Madrasah

Guru

Waka Kesiswaan
Waka Kurikulum
Waka Humas
Waka Sarpras

BK
Piket

Pembina
Pelatih

Siswa
```

---

# ACCESS LEGEND

| Simbol | Keterangan         |
| ------ | ------------------ |
| ✅     | Full Access        |
| 👁     | View Only          |
| Own    | Data Milik Sendiri |
| Self   | Data Diri Sendiri  |
| ❌     | Tidak Memiliki Hak |

---

# MASTER DATA

## Users

| Permission   | Super | Kepala | Lainnya |
| ------------ | ----- | ------ | ------- |
| users.view   | ✅    | 👁     | ❌      |
| users.manage | ✅    | ❌     | ❌      |

---

## Roles

| Permission   | Super |
| ------------ | ----- |
| roles.view   | ✅    |
| roles.manage | ✅    |

---

## Pegawai

| Permission     | Super | Kepala | Waka |
| -------------- | ----- | ------ | ---- |
| pegawai.view   | ✅    | 👁     | 👁   |
| pegawai.manage | ✅    | ❌     | ❌   |

---

## Siswa

| Permission   | Super | Kepala | Waka | Guru | BK  | Piket | Pembina | Pelatih | Siswa |
| ------------ | ----- | ------ | ---- | ---- | --- | ----- | ------- | ------- | ----- |
| siswa.view   | ✅    | 👁     | 👁   | 👁   | 👁  | 👁    | 👁      | 👁      | Self  |
| siswa.manage | ✅    | ❌     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |

---

# AKADEMIK

## Tahun Ajaran

| Permission          | Super | Kepala | Waka Kurikulum |
| ------------------- | ----- | ------ | -------------- |
| tahun_ajaran.view   | ✅    | 👁     | 👁             |
| tahun_ajaran.manage | ✅    | ❌     | ❌             |

---

## Kelas

| Permission   | Super | Kepala | Waka Kurikulum |
| ------------ | ----- | ------ | -------------- |
| kelas.view   | ✅    | 👁     | 👁             |
| kelas.manage | ✅    | ❌     | ❌             |

---

## Kelas Siswa

| Permission         | Super | Kepala | Waka Kurikulum |
| ------------------ | ----- | ------ | -------------- |
| kelas_siswa.view   | ✅    | 👁     | 👁             |
| kelas_siswa.manage | ✅    | ❌     | ❌             |

---

# MODUL GURU

## Jurnal Mengajar

| Permission    | Super | Kepala | Waka Kurikulum | Guru |
| ------------- | ----- | ------ | -------------- | ---- |
| jurnal.view   | ✅    | 👁     | 👁             | Own  |
| jurnal.create | ✅    | ❌     | ❌             | ✅   |
| jurnal.edit   | ✅    | ❌     | ❌             | Own  |
| jurnal.delete | ✅    | ❌     | ❌             | ❌   |

---

## Guru Berhalangan

| Permission            | Super | Kepala | Waka Kurikulum | Guru | Piket |
| --------------------- | ----- | ------ | -------------- | ---- | ----- |
| tugas_izin.view       | ✅    | 👁     | 👁             | Own  | ✅    |
| tugas_izin.create     | ✅    | ❌     | ❌             | ✅   | ❌    |
| tugas_izin.edit       | ✅    | ❌     | ❌             | Own  | ❌    |
| tugas_izin.distribute | ✅    | ❌     | ❌             | ❌   | ✅    |

---

# MONITORING KEHADIRAN

## Kehadiran Sekolah

| Permission               | Super | Kepala | Waka Kesiswaan | BK  | Piket |
| ------------------------ | ----- | ------ | -------------- | --- | ----- |
| kehadiran_sekolah.view   | ✅    | 👁     | 👁             | 👁  | 👁    |
| kehadiran_sekolah.manual | ✅    | ❌     | ❌             | ❌  | ✅    |
| kehadiran_sekolah.export | ✅    | 👁     | 👁             | 👁  | 👁    |

---

## Kehadiran Ibadah

| Permission              | Super | Kepala | Waka Kesiswaan | BK  | Piket |
| ----------------------- | ----- | ------ | -------------- | --- | ----- |
| kehadiran_ibadah.view   | ✅    | 👁     | 👁             | 👁  | 👁    |
| kehadiran_ibadah.manual | ✅    | ❌     | ❌             | ❌  | ✅    |
| kehadiran_ibadah.export | ✅    | 👁     | 👁             | 👁  | 👁    |

---

## Kehadiran Ekskul

| Permission              | Super | Kepala | Pembina | Pelatih |
| ----------------------- | ----- | ------ | ------- | ------- |
| kehadiran_ekskul.view   | ✅    | 👁     | ✅      | ✅      |
| kehadiran_ekskul.manage | ✅    | ❌     | ✅      | ✅      |

---

## Rekap Kehadiran

| Permission             | Super | Kepala | Waka Kesiswaan | BK  | Piket |
| ---------------------- | ----- | ------ | -------------- | --- | ----- |
| rekap_kehadiran.view   | ✅    | 👁     | 👁             | 👁  | 👁    |
| rekap_kehadiran.export | ✅    | 👁     | 👁             | 👁  | 👁    |

---

# BK

## Konseling

| Permission       | Super | Kepala | Waka Kesiswaan | BK  |
| ---------------- | ----- | ------ | -------------- | --- |
| konseling.view   | ✅    | 👁     | 👁             | ✅  |
| konseling.create | ✅    | ❌     | ❌             | ✅  |
| konseling.edit   | ✅    | ❌     | ❌             | ✅  |
| konseling.delete | ✅    | ❌     | ❌             | ❌  |

---

## Permasalahan

| Permission     | Super | Kepala | Waka Kesiswaan | BK  |
| -------------- | ----- | ------ | -------------- | --- |
| masalah.view   | ✅    | 👁     | 👁             | ✅  |
| masalah.create | ✅    | ❌     | ❌             | ✅  |
| masalah.edit   | ✅    | ❌     | ❌             | ✅  |
| masalah.delete | ✅    | ❌     | ❌             | ❌  |

---

## Izin Keluar

| Permission         | Super | Kepala | Waka Kesiswaan | BK  | Piket |
| ------------------ | ----- | ------ | -------------- | --- | ----- |
| izin_keluar.view   | ✅    | 👁     | 👁             | ✅  | ✅    |
| izin_keluar.create | ✅    | ❌     | ❌             | ✅  | ✅    |
| izin_keluar.edit   | ✅    | ❌     | ❌             | ✅  | ✅    |
| izin_keluar.print  | ✅    | ❌     | ❌             | ✅  | ✅    |

---

# EKSTRAKURIKULER

## Ekstrakurikuler

| Permission    | Super | Waka Kesiswaan | Pembina | Pelatih |
| ------------- | ----- | -------------- | ------- | ------- |
| ekskul.view   | ✅    | 👁             | 👁      | 👁      |
| ekskul.manage | ✅    | ✅             | ❌      | ❌      |

---

## Pengampu

| Permission      | Super | Waka Kesiswaan | Pembina |
| --------------- | ----- | -------------- | ------- |
| pengampu.view   | ✅    | 👁             | 👁      |
| pengampu.manage | ✅    | ✅             | ❌      |

---

## Anggota Ekskul

| Permission       | Super | Kepala | Waka Kesiswaan | Pembina | Pelatih |
| ---------------- | ----- | ------ | -------------- | ------- | ------- |
| anggota.view     | ✅    | 👁     | 👁             | ✅      | ✅      |
| anggota.create   | ✅    | ❌     | ❌             | ✅      | ✅      |
| anggota.edit     | ✅    | ❌     | ❌             | ✅      | ✅      |
| anggota.nonaktif | ✅    | ❌     | ❌             | ✅      | ✅      |

---

## Sesi Kegiatan

| Permission  | Super | Kepala | Pembina | Pelatih |
| ----------- | ----- | ------ | ------- | ------- |
| sesi.view   | ✅    | 👁     | ✅      | ✅      |
| sesi.create | ✅    | ❌     | ❌      | ✅      |
| sesi.edit   | ✅    | ❌     | ❌      | ✅      |
| sesi.delete | ✅    | ❌     | ❌      | ✅      |

---

# PROPOSAL KEGIATAN

## Workflow

```text
Pelatih / Pembina
↓
Submit

Pembina
↓
Approve / Reject

Waka Kesiswaan
↓
Approve / Reject

Kepala Madrasah
↓
Final Approve / Reject
```

| Permission          | Super | Kepala | Waka Kesiswaan | Pembina | Pelatih |
| ------------------- | ----- | ------ | -------------- | ------- | ------- |
| proposal.view       | ✅    | 👁     | 👁             | 👁      | 👁      |
| proposal.create     | ✅    | ❌     | ❌             | ✅      | ✅      |
| proposal.edit_draft | ✅    | ❌     | ❌             | ✅      | ✅      |
| proposal.submit     | ✅    | ❌     | ❌             | ✅      | ✅      |
| proposal.review     | ✅    | ❌     | ✅             | ✅      | ❌      |
| proposal.approve    | ✅    | ✅     | ✅             | ✅      | ❌      |
| proposal.reject     | ✅    | ✅     | ✅             | ✅      | ❌      |

---

# LAPORAN BULANAN

## Workflow

```text
Pelatih / Pembina
↓
Submit

Pembina
↓
Approve / Reject

Waka Kesiswaan
↓
Approve / Reject

Kepala Madrasah
↓
Final Approve / Reject
```

| Permission         | Super | Kepala | Waka Kesiswaan | Pembina | Pelatih |
| ------------------ | ----- | ------ | -------------- | ------- | ------- |
| laporan.view       | ✅    | 👁     | 👁             | 👁      | 👁      |
| laporan.create     | ✅    | ❌     | ❌             | ✅      | ✅      |
| laporan.edit_draft | ✅    | ❌     | ❌             | ✅      | ✅      |
| laporan.submit     | ✅    | ❌     | ❌             | ✅      | ✅      |
| laporan.review     | ✅    | ❌     | ✅             | ✅      | ❌      |
| laporan.approve    | ✅    | ✅     | ✅             | ✅      | ❌      |
| laporan.reject     | ✅    | ✅     | ✅             | ✅      | ❌      |

---

# PUBLIKASI

## Prestasi

| Permission      | Super | Waka Humas | Pembina | Pelatih |
| --------------- | ----- | ---------- | ------- | ------- |
| prestasi.view   | ✅    | 👁         | 👁      | 👁      |
| prestasi.create | ✅    | ✅         | ✅      | ✅      |
| prestasi.edit   | ✅    | Own        | Own     | Own     |
| prestasi.delete | ✅    | ❌         | ❌      | ❌      |

---

## Pengumuman

| Permission        | Super | Semua Waka | Pembina | Pelatih |
| ----------------- | ----- | ---------- | ------- | ------- |
| pengumuman.view   | ✅    | 👁         | 👁      | 👁      |
| pengumuman.create | ✅    | ✅         | ✅      | ✅      |
| pengumuman.edit   | ✅    | Own        | Own     | Own     |
| pengumuman.delete | ✅    | ❌         | ❌      | ❌      |

---

## Galeri

| Permission    | Super | Waka Humas | Pembina | Pelatih |
| ------------- | ----- | ---------- | ------- | ------- |
| galeri.view   | ✅    | 👁         | 👁      | 👁      |
| galeri.create | ✅    | ✅         | ✅      | ✅      |
| galeri.edit   | ✅    | Own        | Own     | Own     |
| galeri.delete | ✅    | ❌         | ❌      | ❌      |

---

# SYSTEM

## Settings

| Permission      | Super | Kepala | Waka |
| --------------- | ----- | ------ | ---- |
| settings.view   | ✅    | 👁     | 👁   |
| settings.manage | ✅    | ❌     | ❌   |

---

## Activity Logs

| Permission         | Super | Kepala |
| ------------------ | ----- | ------ |
| activity_logs.view | ✅    | 👁     |

---

## Approval Logs

| Permission         | Super | Kepala | Waka |
| ------------------ | ----- | ------ | ---- |
| approval_logs.view | ✅    | 👁     | 👁   |

---

# SYSTEM NAVIGATION

System
├── Settings
├── Activity Logs
└── Approval Logs

---

# SISWA

## Dashboard Siswa

Dapat melihat:

- Profil
- Kehadiran Sekolah
- Kehadiran Ibadah
- Kehadiran Ekskul
- Prestasi
- Pengumuman

Tidak dapat mengubah data akademik.

---

# STATUS

Database Blueprint Sync
🟩 Final

Roles Sync
🟩 Final

Workflow Sync
🟩 Final

Permission Architecture
🟩 Final

Siap digunakan untuk:

- PermissionSeeder.php
- RolePermissionSeeder.php
- Policies
- Filament Authorization
- Dashboard Authorization
