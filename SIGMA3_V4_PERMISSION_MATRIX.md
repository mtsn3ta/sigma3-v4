# SIGMA3 V4 PERMISSION MATRIX

Status:
🟩 Final v1

---

# ROLE STRUCTURE

Super Admin

Kepala Madrasah

Waka
├─ Waka Kesiswaan
├─ Waka Kurikulum
├─ Waka Humas
└─ Waka Sarpras

Guru
├─ BK
├─ Piket
├─ Pembina
└─ Pelatih

Siswa

---

# ROLE RULES

## Kepala Madrasah

Hanya monitoring.

Tidak menjadi:

- Guru
- BK
- Piket
- Pembina
- Pelatih

---

## Waka

Wajib memiliki role:

- guru

Tambahan:

- waka_kesiswaan
- waka_kurikulum
- waka_humas
- waka_sarpras

Tidak dapat menjadi:

- pembina
- pelatih

---

## Guru

Role dasar pegawai pengajar.

Dapat ditambah:

- bk
- piket
- pembina
- pelatih

---

## Pelatih Eksternal

Hanya:

- pelatih

Tidak memiliki role guru.

---

# MASTER DATA

| Permission     | Super | Kepala | Waka | Guru | BK  | Piket | Pembina | Pelatih | Siswa |
| -------------- | ----- | ------ | ---- | ---- | --- | ----- | ------- | ------- | ----- |
| users.view     | ✅    | 👁     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |
| users.manage   | ✅    | ❌     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |
| roles.manage   | ✅    | ❌     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |
| pegawai.view   | ✅    | 👁     | 👁   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |
| pegawai.manage | ✅    | ❌     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |
| siswa.view     | ✅    | 👁     | 👁   | 👁   | 👁  | 👁    | 👁      | 👁      | Self  |
| siswa.manage   | ✅    | ❌     | ❌   | ❌   | ❌  | ❌    | ❌      | ❌      | ❌    |

---

# AKADEMIK

## Tahun Ajaran

| Permission          | Super | Kepala | Waka Kurikulum |
| ------------------- | ----- | ------ | -------------- |
| tahun_ajaran.view   | ✅    | 👁     | 👁             |
| tahun_ajaran.manage | ✅    | ❌     | ✅             |

---

## Kelas

| Permission   | Super | Kepala | Waka Kurikulum |
| ------------ | ----- | ------ | -------------- |
| kelas.view   | ✅    | 👁     | 👁             |
| kelas.manage | ✅    | ❌     | ✅             |

---

# MODUL GURU

## Jurnal Mengajar

| Permission    | Super | Kepala | Waka Kurikulum | Guru |
| ------------- | ----- | ------ | -------------- | ---- |
| jurnal.view   | ✅    | 👁     | 👁             | Own  |
| jurnal.create | ✅    | ❌     | ❌             | ✅   |
| jurnal.edit   | ✅    | ❌     | ❌             | Own  |

---

## Guru Berhalangan

| Permission            | Super | Kepala | Waka Kurikulum | Guru | Piket |
| --------------------- | ----- | ------ | -------------- | ---- | ----- |
| tugas_izin.view       | ✅    | 👁     | 👁             | Own  | ✅    |
| tugas_izin.create     | ✅    | ❌     | ❌             | ✅   | ❌    |
| tugas_izin.distribute | ✅    | ❌     | ❌             | ❌   | ✅    |

---

# KEHADIRAN SEKOLAH

## Kehadiran Sekolah

| Permission       | Super | Kepala | BK  | Piket |
| ---------------- | ----- | ------ | --- | ----- |
| kehadiran.view   | ✅    | 👁     | 👁  | 👁    |
| kehadiran.manual | ✅    | ❌     | ❌  | ✅    |
| kehadiran.export | ✅    | 👁     | 👁  | 👁    |

---

## Kehadiran Ibadah

| Permission    | Super | Kepala | BK  | Piket |
| ------------- | ----- | ------ | --- | ----- |
| ibadah.view   | ✅    | 👁     | 👁  | 👁    |
| ibadah.manual | ✅    | ❌     | ❌  | ✅    |

---

# BK

## Konseling

| Permission       | Super | Kepala | BK  |
| ---------------- | ----- | ------ | --- |
| konseling.view   | ✅    | 👁     | ✅  |
| konseling.create | ✅    | ❌     | ✅  |
| konseling.edit   | ✅    | ❌     | ✅  |

---

## Permasalahan

| Permission     | Super | Kepala | BK  |
| -------------- | ----- | ------ | --- |
| masalah.view   | ✅    | 👁     | ✅  |
| masalah.create | ✅    | ❌     | ✅  |
| masalah.edit   | ✅    | ❌     | ✅  |

---

## Dashboard BK

| Permission        | Super | Kepala | BK  |
| ----------------- | ----- | ------ | --- |
| dashboard_bk.view | ✅    | 👁     | ✅  |

---

## Izin Keluar

| Permission         | Super | Kepala | BK  | Piket |
| ------------------ | ----- | ------ | --- | ----- |
| izin_keluar.view   | ✅    | 👁     | ✅  | ✅    |
| izin_keluar.create | ✅    | ❌     | ✅  | ✅    |
| izin_keluar.edit   | ✅    | ❌     | ✅  | ✅    |
| izin_keluar.print  | ✅    | ❌     | ✅  | ✅    |

---

# EKSTRAKURIKULER

## Ekstrakurikuler

| Permission    | Super | Kepala | Waka Kesiswaan | Pembina | Pelatih |
| ------------- | ----- | ------ | -------------- | ------- | ------- |
| ekskul.view   | ✅    | 👁     | 👁             | 👁      | 👁      |
| ekskul.manage | ✅    | ❌     | ✅             | ❌      | ❌      |

---

## Anggota Ekskul

| Permission     | Super | Kepala | Waka Kesiswaan | Pembina | Pelatih |
| -------------- | ----- | ------ | -------------- | ------- | ------- |
| anggota.view   | ✅    | 👁     | 👁             | 👁      | 👁      |
| anggota.manage | ✅    | ❌     | ✅             | ❌      | ❌      |

---

## Sesi Kegiatan

| Permission  | Super | Kepala | Pembina | Pelatih |
| ----------- | ----- | ------ | ------- | ------- |
| sesi.view   | ✅    | 👁     | 👁      | 👁      |
| sesi.create | ✅    | ❌     | ❌      | ✅      |
| sesi.edit   | ✅    | ❌     | ❌      | ✅      |

---

## Kehadiran Ekskul

| Permission              | Super | Kepala | Pembina | Pelatih |
| ----------------------- | ----- | ------ | ------- | ------- |
| kehadiran_ekskul.view   | ✅    | 👁     | 👁      | 👁      |
| kehadiran_ekskul.manage | ✅    | ❌     | ❌      | ✅      |

---

## Laporan Bulanan

Workflow:

Pelatih
↓
Pembina
↓
Waka Kesiswaan
↓
Kepala Madrasah

### Pelatih

- Create
- Edit draft
- Submit

### Pembina

- Review
- Approve
- Reject

### Waka Kesiswaan

- Review
- Approve
- Reject

### Kepala Madrasah

- Final Approve
- Final Reject

---

## Proposal Kegiatan

Workflow:

Pembina
↓
Waka Kesiswaan
↓
Kepala Madrasah

### Pembina

- Create
- Edit Draft
- Submit

### Waka Kesiswaan

- Review
- Approve
- Reject

### Kepala Madrasah

- Final Approve
- Final Reject

---

# PUBLIKASI

## Prestasi

| Permission      | Super | Kepala | Waka Kesiswaan |
| --------------- | ----- | ------ | -------------- |
| prestasi.view   | ✅    | 👁     | 👁             |
| prestasi.manage | ✅    | ❌     | ✅             |

---

## Pengumuman

| Permission        | Super | Kepala | Waka |
| ----------------- | ----- | ------ | ---- |
| pengumuman.view   | ✅    | 👁     | 👁   |
| pengumuman.manage | ✅    | ❌     | ✅   |

---

## Galeri

| Permission    | Super | Kepala | Waka |
| ------------- | ----- | ------ | ---- |
| galeri.view   | ✅    | 👁     | 👁   |
| galeri.manage | ✅    | ❌     | ✅   |

---

# SISWA

## Dashboard Siswa

Dapat melihat:

- Profil
- Riwayat Kehadiran Sekolah
- Riwayat Ibadah
- Riwayat Ekskul
- Prestasi
- Pengumuman

Tidak dapat mengubah data akademik.

---

# DASHBOARD

## Kepala Madrasah

Dashboard Monitoring & Approval:

- Statistik Madrasah
- Kehadiran Sekolah
- Kehadiran Ibadah
- BK
- Ekstrakurikuler
- Prestasi

Approval:

- Proposal Kegiatan
- Laporan Bulanan Ekskul

---

## Waka Kesiswaan

Dashboard Kesiswaan & Approval:

Monitoring:

- Kehadiran Sekolah
- Kehadiran Ibadah
- BK
- Ekstrakurikuler
- Prestasi

Approval:

- Proposal Kegiatan
- Laporan Bulanan Ekskul

## Waka Kurikulum

Dashboard Kurikulum:

- Tahun Ajaran
- Kelas
- Jurnal Mengajar

---

## Waka Humas

Dashboard Humas:

- Pengumuman
- Galeri
- Prestasi

---

## Waka Sarpras

Dashboard Sarpras:

Monitoring sesuai kebutuhan sarana prasarana.

---

## Guru

Dashboard aktivitas mengajar:

- Jurnal Mengajar
- Tugas Guru Berhalangan

---

## BK

Dashboard BK:

- Konseling
- Permasalahan Siswa
- Monitoring Kehadiran
- Izin Keluar

---

## Piket

Dashboard Kehadiran:

- Monitoring Kehadiran
- Kehadiran Manual
- Distribusi Tugas Guru
- Izin Keluar

---

## Pembina

Dashboard Monitoring Ekstrakurikuler:

- Anggota
- Kehadiran
- Laporan Bulanan

---

## Pelatih

Dashboard Operasional Ekstrakurikuler:

- Sesi Kegiatan
- Kehadiran Ekskul
- Laporan Bulanan

---

## Siswa

Dashboard Siswa:

- Profil
- Kehadiran
- Prestasi
- Pengumuman
