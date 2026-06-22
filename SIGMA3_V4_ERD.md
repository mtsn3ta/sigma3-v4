# SIGMA3 V4 ERD

Status: 🟨 Draft v1

---

# AUTHENTICATION

## Struktur

users
│
├── pegawai
│
└── siswa

users
│
└── roles (Spatie Permission)

---

id
username
email
password

must_change_password
true
false

status

created_at
updated_at

### Status

- aktif
- nonaktif

---

# PEGAWAI

## pegawai

- id
- user_id (nullable)

- nama
- nik_nip

- jenis_pegawai

- no_hp
- email

- rfid_uid

- jenis_kelamin
- tempat_lahir
- tanggal_lahir
- alamat

- foto
- ttd_digital

- status

### Jenis Pegawai

- internal
- eksternal

### Keterangan

Admin hanya mengisi data dasar:

- Nama
- NIP/NIK
- No HP
- RFID

Data lainnya dapat dilengkapi sendiri oleh pegawai setelah login.

---

# SISWA

## siswa

- id
- user_id (nullable)

- nis
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

---

# AKADEMIK

## tahun_ajaran

- id
- tahun
- status

### Contoh

- 2025/2026
- 2026/2027

### Keterangan

Hanya satu tahun ajaran yang aktif.

---

## kelas

- id
- nama_kelas
- tingkat
- status

### Contoh

- 7A
- 7B
- 8A
- 9G

---

## kelas_siswa

- id

- siswa_id
- kelas_id
- tahun_ajaran_id

- status

### Status

- aktif
- naik
- lulus

### Keterangan

Riwayat kelas siswa tidak pernah dihapus.

Saat naik kelas akan dibuat record baru pada tahun ajaran berikutnya.

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

- printed_at

- created_at
- updated_at

### Status

- keluar
- kembali
- pulang

### Keterangan

pegawai_id adalah petugas yang membuat izin keluar.

Petugas dapat berupa:

- BK
- Piket

Dokumen izin keluar dapat dicetak menggunakan printer termal.

### Status

- keluar
- kembali
- pulang

---

# EKSTRAKURIKULER

## ekstrakurikuler

- id

- nama
- deskripsi

- jadwal
- kuota

- status

### Status

- aktif
- nonaktif

---

## ekstrakurikuler_pengampu

Menghubungkan pegawai dengan ekstrakurikuler.

- id

- ekstrakurikuler_id
- pegawai_id

- role

### Role

- pembina
- pelatih

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

### Status

- aktif
- nonaktif

### Keterangan

Keanggotaan ekskul disimpan per tahun ajaran.

---

## sesi_kegiatan

- id

- ekstrakurikuler_id
- pelatih_id

- tanggal

- waktu_mulai
- waktu_selesai

- status

### Status

- aktif
- selesai
- batal

### Keterangan

Pelatih yang memimpin sesi dicatat pada tabel ini.

---

## kehadiran_ekskul

- id

- sesi_kegiatan_id
- siswa_id

- status
- sumber

- petugas_id (nullable)

- waktu_scan

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

- created_at
- updated_at

### Status

- draft
- pending_pembina
- pending_waka
- pending_kepala
- approved
- rejected

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

- created_at
- updated_at

### Status

- draft
- pending_waka
- pending_kepala
- approved
- rejected

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

- petugas_id (nullable)

- waktu_scan

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

# BK (BIMBINGAN KONSELING)

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

### Keterangan

BK menulis permasalahan dan poin secara manual sesuai kejadian.

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

### Keterangan

Riwayat konseling tidak boleh dihapus.

---

# PUBLIKASI

## prestasi

- id

- ekstrakurikuler_id (nullable)

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

### Keterangan

Prestasi dapat berasal dari:

- Individu
- Tim

---

## prestasi_siswa

- id

- prestasi_id
- siswa_id

### Keterangan

Satu prestasi dapat dimiliki banyak siswa.

Contoh:

- Futsal
- Pramuka
- Hadrah
- PBB

Menggunakan relasi many-to-many.

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
