# SIGMA3_V4_ERD.md

Status:
🟩 Final

Last Updated:
2026-06-23

---

# TUJUAN

Dokumen ini mendefinisikan Entity Relationship Diagram (ERD) final SIGMA3 V4.

Dokumen ini harus selalu konsisten dengan:

- SIGMA3_V4_DECISIONS.md
- SIGMA3_V4_DATABASE_BLUEPRINT.md
- SIGMA3_V4_ROLES.md
- SIGMA3_V4_PERMISSION_MATRIX.md

---

# AUTHENTICATION

## Struktur User

```text
users
│
├── pegawai
│
└── siswa
```

Role menggunakan:

```text
Spatie Permission
```

```text
users
│
├── model_has_roles
│
├── model_has_permissions
│
└── roles
```

---

## users

- id
- username
- email
- password
- must_change_password
- email_verified_at
- remember_token
- status
- created_at
- updated_at

### Status

- aktif
- nonaktif

### Keterangan

- Semua pengguna wajib memiliki akun.
- Username pegawai menggunakan NIK/NIP.
- Username pelatih eksternal menggunakan NIK.
- Username siswa menggunakan NISN.
- Login pertama wajib mengganti password.

---

# PEGAWAI

## pegawai

- id
- user_id
- nama
- nik_nip
- jenis_pegawai
- no_hp
- rfid_uid
- jenis_kelamin
- tempat_lahir
- tanggal_lahir
- alamat
- email
- foto
- ttd_digital
- status
- created_at
- updated_at

### Jenis Pegawai

- internal
- eksternal

### Status

- aktif
- nonaktif

### Relasi

```text
pegawai
belongsTo users

pegawai
hasMany jurnal_mengajar

pegawai
hasMany tugas_guru_izin

pegawai
hasMany permasalahan_siswa

pegawai
hasMany konseling_siswa
```

---

# SISWA

## siswa

- id
- user_id
- nisn
- nama
- jenis_kelamin
- tempat_lahir
- tanggal_lahir
- alamat
- no_hp
- foto

- nama_ayah
- nama_ibu

- pekerjaan_ayah
- pekerjaan_ibu

- nik_ayah
- nik_ibu

- alamat_ortu
- telpon_ortu

- nama_wali
- pekerjaan_wali
- alamat_wali
- telpon_wali

- anak_ke
- sekolah_asal

- rfid_uid

- status

- created_at
- updated_at

### Status

- aktif
- mutasi
- lulus

### Relasi

```text
siswa
belongsTo users
```

---

# AKADEMIK

## tahun_ajaran

- id
- tahun
- aktif
- created_at
- updated_at

### Keterangan

Hanya satu tahun ajaran yang aktif.

---

## kelas

- id
- nama_kelas
- tingkat
- status
- created_at
- updated_at

### Status

- aktif
- nonaktif

---

## kelas_siswa

- id
- siswa_id
- kelas_id
- tahun_ajaran_id
- status
- created_at
- updated_at

### Status

- aktif
- naik
- lulus

### Relasi

```text
siswa
hasMany kelas_siswa

kelas
hasMany kelas_siswa

tahun_ajaran
hasMany kelas_siswa
```

### Keterangan

Riwayat kelas tidak pernah dihapus.

---

# EKSTRAKURIKULER

## ekstrakurikuler

- id
- nama
- deskripsi
- jadwal
- kuota
- status
- created_at
- updated_at

### Status

- aktif
- nonaktif

---

## ekstrakurikuler_pengampu

- id
- ekstrakurikuler_id
- pegawai_id
- role
- created_at
- updated_at

### Role

- pembina
- pelatih

### Relasi

```text
ekstrakurikuler
belongsToMany pegawai

pegawai
belongsToMany ekstrakurikuler
```

### Keterangan

- Satu ekskul dapat memiliki banyak pembina.
- Satu ekskul dapat memiliki banyak pelatih.
- Satu pegawai dapat mengampu banyak ekskul.

---

## anggota_ekskul

- id
- siswa_id
- ekstrakurikuler_id
- tahun_ajaran_id
- tanggal_daftar
- status
- created_at
- updated_at

### Status

- aktif
- nonaktif

### Keterangan

Keanggotaan disimpan per tahun ajaran.

---

## sesi_kegiatan

- id
- ekstrakurikuler_id
- pelatih_id
- tanggal
- waktu_mulai
- waktu_selesai
- status
- created_at
- updated_at

### Status

- aktif
- selesai
- batal

---

## kehadiran_ekskul

- id
- sesi_kegiatan_id
- siswa_id
- status
- sumber
- petugas_id
- waktu_scan
- created_at
- updated_at

### Status

- hadir
- izin
- sakit
- alpa

### Sumber

- rfid
- manual

---

## laporan_bulanan

- id
- ekstrakurikuler_id
- tahun_ajaran_id
- bulan
- tahun

- tujuan
- pencapaian
- evaluasi
- kendala
- solusi

- tempat_kegiatan
- dokumentasi

- rata_kehadiran

- status

- submitted_at
- approved_at

- created_by_id

- created_at
- updated_at

### Status

- draft
- pending_pembina
- pending_waka
- pending_kepala
- approved
- rejected

### Workflow

```text
Pembina / Pelatih
↓
Pembina
↓
Waka Kesiswaan
↓
Kepala Madrasah
```

---

## proposal_kegiatan

- id
- ekstrakurikuler_id

- nama_kegiatan
- jenis_kegiatan

- tempat
- tanggal_kegiatan

- peserta

- estimasi_biaya

- tujuan
- deskripsi

- status

- submitted_at
- approved_at

- created_by_id

- created_at
- updated_at

### Status

- draft
- pending_pembina
- pending_waka
- pending_kepala
- approved
- rejected

### Workflow

```text
Pembina / Pelatih
↓
Pembina
↓
Waka Kesiswaan
↓
Kepala Madrasah
```

---

# KEHADIRAN

## kehadiran_sekolah

- id
- siswa_id
- tanggal
- jenis
- status
- sumber
- keterangan
- waktu_scan
- created_at
- updated_at

### Jenis

- datang
- pulang

### Status

- hadir
- telat
- izin
- sakit
- alpa

### Sumber

- rfid
- manual

---

## kehadiran_ibadah

- id
- siswa_id
- tanggal
- jenis
- status
- sumber
- petugas_id
- waktu_scan
- created_at
- updated_at

### Jenis

- dhuha
- dzuhur

### Status

- hadir
- tidak_hadir

### Sumber

- rfid
- manual

---

# MODUL GURU

## jurnal_mengajar

- id
- pegawai_id
- kelas_id
- tanggal
- jam_ke
- mapel
- materi
- siswa_tidak_hadir
- siswa_bolos
- catatan
- created_at
- updated_at

---

## tugas_guru_izin

- id
- pegawai_id
- kelas_id
- tanggal
- mapel
- jam_ke
- isi_tugas
- status
- created_at
- updated_at

### Status

- baru
- disampaikan
- selesai

---

## izin_keluar

- id
- nomor_izin
- siswa_id
- pegawai_id
- tanggal
- jam_keluar
- jam_kembali
- keperluan
- tujuan
- status_proses
- waktu_cetak
- created_at
- updated_at

### Status

- keluar
- kembali
- pulang

### Keterangan

Petugas:

- BK
- Piket

---

# BK

## permasalahan_siswa

- id
- siswa_id
- pegawai_id
- tanggal
- permasalahan
- penyelesaian
- poin
- created_at
- updated_at

---

## konseling_siswa

- id
- siswa_id
- pegawai_id
- tanggal
- permasalahan
- hasil_konseling
- tindak_lanjut
- created_at
- updated_at

---

# PUBLIKASI

## prestasi

- id
- ekstrakurikuler_id
- nama_prestasi
- nama_lomba
- jenis
- tingkat
- tanggal
- keterangan
- foto
- created_by_id
- created_at
- updated_at

### Jenis

- individu
- tim

---

## prestasi_siswa

- id
- prestasi_id
- siswa_id

### Relasi

```text
prestasi
belongsToMany siswa
```

---

## pengumuman

- id
- judul
- isi
- lampiran
- tanggal_mulai
- tanggal_selesai
- status
- created_by_id
- created_at
- updated_at

### Status

- draft
- publish
- arsip

---

## galeri

- id
- judul
- deskripsi
- foto
- tanggal
- kategori
- created_by_id
- created_at
- updated_at

### Kategori

- sekolah
- ekstrakurikuler
- prestasi
- kegiatan

---

# SYSTEM

## activity_logs

- id
- user_id
- aksi
- deskripsi
- ip_address
- user_agent
- created_at

---

## approval_logs

- id
- modul
- record_id
- pegawai_id
- aksi
- catatan
- created_at

---

## settings

- id

- nama_madrasah
- alamat
- telepon
- email

- logo
- favicon

- kepala_aktif_id
- tahun_ajaran_aktif_id

- created_at
- updated_at

### Keterangan

Hanya terdapat satu record aktif.

Dikelola oleh:

- Super Admin

---

# RELATION SUMMARY

```text
users
├── pegawai (1:1)
└── siswa (1:1)

pegawai
├── jurnal_mengajar
├── tugas_guru_izin
├── konseling_siswa
├── permasalahan_siswa
└── ekstrakurikuler_pengampu

siswa
├── kelas_siswa
├── anggota_ekskul
├── kehadiran_sekolah
├── kehadiran_ibadah
├── kehadiran_ekskul
├── konseling_siswa
├── permasalahan_siswa
└── prestasi_siswa

ekstrakurikuler
├── pengampu
├── anggota
├── sesi_kegiatan
├── laporan_bulanan
└── proposal_kegiatan
```

---

# STATUS

Database Sync
🟩 Final

Roles Sync
🟩 Final

Permission Matrix Sync
🟩 Final

Decision Sync
🟩 Final

Ready For Migration
🟩 Yes
