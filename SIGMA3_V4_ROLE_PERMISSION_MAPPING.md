# SIGMA3_V4_ROLE_PERMISSION_MAPPING.md

Status:
🟩 Final

---

# TUJUAN

Dokumen ini mendefinisikan mapping antara Role dan Permission.

Dokumen ini digunakan sebagai dasar:

- PermissionSeeder.php
- Policy
- Filament Navigation
- Dashboard Authorization
- Resource Authorization

Dokumen ini harus selalu sinkron dengan:

- SIGMA3_V4_ROLES.md
- SIGMA3_V4_PERMISSION_MATRIX.md
- SIGMA3_V4_DATABASE_BLUEPRINT.md

---

# SUPER ADMIN

Hak Akses:

Semua Permission.

Implementasi:

```php
Gate::before(fn ($user) =>
    $user->hasRole('super_admin') ? true : null
);
```

---

# KEPALA MADRASAH

## Monitoring

```text
users.view

pegawai.view
siswa.view

tahun_ajaran.view
kelas.view
kelas_siswa.view

jurnal.view
tugas_izin.view

kehadiran_sekolah.view
kehadiran_sekolah.export

kehadiran_ibadah.view
kehadiran_ibadah.export

kehadiran_ekskul.view

rekap_kehadiran.view
rekap_kehadiran.export

konseling.view
masalah.view

izin_keluar.view

ekskul.view
anggota.view
sesi.view

proposal.view
laporan.view

prestasi.view
pengumuman.view
galeri.view

settings.view
activity_logs.view
approval_logs.view
```

## Approval

```text
proposal.approve
proposal.reject

laporan.approve
laporan.reject
```

---

# GURU

```text
siswa.view

jurnal.view
jurnal.create
jurnal.edit

tugas_izin.view
tugas_izin.create
tugas_izin.edit
```

---

# WAKA KESISWAAN

Mendapat seluruh permission Guru +

```text
pegawai.view
siswa.view

kehadiran_sekolah.view
kehadiran_sekolah.export

kehadiran_ibadah.view
kehadiran_ibadah.export

rekap_kehadiran.view
rekap_kehadiran.export

konseling.view
masalah.view

izin_keluar.view

ekskul.view
ekskul.manage

pengampu.view
pengampu.manage

anggota.view

proposal.view
proposal.review
proposal.approve
proposal.reject

laporan.view
laporan.review
laporan.approve
laporan.reject

pengumuman.view
pengumuman.create
pengumuman.edit

approval_logs.view
settings.view
```

---

# WAKA KURIKULUM

Mendapat seluruh permission Guru +

```text
pegawai.view

tahun_ajaran.view
kelas.view
kelas_siswa.view

jurnal.view

tugas_izin.view

approval_logs.view
settings.view
```

---

# WAKA HUMAS

Mendapat seluruh permission Guru +

```text
pegawai.view

prestasi.view
prestasi.create
prestasi.edit

pengumuman.view
pengumuman.create
pengumuman.edit

galeri.view
galeri.create
galeri.edit

approval_logs.view
settings.view
```

---

# WAKA SARPRAS

Mendapat seluruh permission Guru +

```text
pegawai.view

pengumuman.view
pengumuman.create
pengumuman.edit

approval_logs.view
settings.view
```

---

# BK

Mendapat seluruh permission Guru +

```text
kehadiran_sekolah.view
kehadiran_ibadah.view

rekap_kehadiran.view
rekap_kehadiran.export

konseling.view
konseling.create
konseling.edit

masalah.view
masalah.create
masalah.edit

izin_keluar.view
izin_keluar.create
izin_keluar.edit
izin_keluar.print
```

---

# PIKET

Mendapat seluruh permission Guru +

```text
kehadiran_sekolah.view
kehadiran_sekolah.manual
kehadiran_sekolah.export

kehadiran_ibadah.view
kehadiran_ibadah.manual
kehadiran_ibadah.export

rekap_kehadiran.view
rekap_kehadiran.export

tugas_izin.distribute

izin_keluar.view
izin_keluar.create
izin_keluar.edit
izin_keluar.print
```

---

# PEMBINA

Mendapat seluruh permission Guru +

```text
siswa.view

ekskul.view

pengampu.view

anggota.view
anggota.create
anggota.edit
anggota.nonaktif

sesi.view

kehadiran_ekskul.view
kehadiran_ekskul.manage

proposal.view
proposal.create
proposal.edit_draft
proposal.submit
proposal.review
proposal.approve
proposal.reject

laporan.view
laporan.create
laporan.edit_draft
laporan.submit
laporan.review
laporan.approve
laporan.reject

prestasi.view
prestasi.create
prestasi.edit

pengumuman.view
pengumuman.create
pengumuman.edit

galeri.view
galeri.create
galeri.edit
```

Catatan:

Pembina TIDAK memiliki:

```text
sesi.create
sesi.edit
sesi.delete
```

Hak tersebut diperoleh hanya jika user juga memiliki role:

```text
pelatih
```

---

# PELATIH

```text
siswa.view

ekskul.view

anggota.view
anggota.create
anggota.edit
anggota.nonaktif

sesi.view
sesi.create
sesi.edit
sesi.delete

kehadiran_ekskul.view
kehadiran_ekskul.manage

proposal.view
proposal.create
proposal.edit_draft
proposal.submit

laporan.view
laporan.create
laporan.edit_draft
laporan.submit

prestasi.view
prestasi.create
prestasi.edit

pengumuman.view
pengumuman.create
pengumuman.edit

galeri.view
galeri.create
galeri.edit
```

---

# SISWA

```text
siswa.view

prestasi.view
pengumuman.view
```

---

# MULTI ROLE

Permission merupakan gabungan seluruh role yang dimiliki user.

Contoh:

Guru BK:

```text
guru
bk
```

Guru Piket:

```text
guru
piket
```

Guru Pembina:

```text
guru
pembina
```

Guru Pelatih:

```text
guru
pelatih
```

Guru Pembina + Pelatih:

```text
guru
pembina
pelatih
```

Guru BK + Piket + Pembina + Pelatih:

```text
guru
bk
piket
pembina
pelatih
```

Semua permission akan digabung otomatis oleh Spatie Permission.

---

# STATUS

Roles Sync
🟩 Final

Permission Matrix Sync
🟩 Final

Database Sync
🟩 Final

Seeder Ready
🟩 Ready

Policy Ready
🟩 Ready

Filament Authorization Ready
🟩 Ready
