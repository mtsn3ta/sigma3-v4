# SIGMA3 V4 DATABASE BLUEPRINT

Status:
🟩 Final Blueprint v1

---

# DATABASE PRINCIPLES

1. Satu akun untuk seluruh sistem
2. Role menggunakan Spatie Permission
3. Riwayat tidak boleh dihapus
4. Soft Delete hanya untuk data tertentu
5. Semua FK menggunakan cascade sesuai kebutuhan
6. Mendukung migrasi Sigma3 Lama
7. Mendukung multi role pegawai

---

# MIGRATION ORDER

01_users
02_permissions
03_pegawai
04_siswa
05_tahun_ajaran
06_kelas
07_kelas_siswa

08_ekstrakurikuler
09_ekstrakurikuler_pengampu
10_anggota_ekskul
11_sesi_kegiatan
12_kehadiran_ekskul
13_laporan_bulanan
14_proposal_kegiatan

15_kehadiran_sekolah
16_kehadiran_ibadah

17_jurnal_mengajar
18_tugas_guru_izin
19_izin_keluar

20_permasalahan_siswa
21_konseling_siswa

22_prestasi
23_prestasi_siswa
24_pengumuman
25_galeri

26_activity_logs
27_approval_logs
28_settings

---

# AUTHENTICATION

## users

id BIGINT PK

username VARCHAR(50) UNIQUE

email VARCHAR(150) NULLABLE

password VARCHAR(255)

must_change_password BOOLEAN DEFAULT TRUE

email_verified_at TIMESTAMP NULL

remember_token VARCHAR(100) NULL

status ENUM

- aktif
- nonaktif

created_at
updated_at

INDEX

username
email

---

# PEGAWAI

## pegawai

id BIGINT PK

user_id FK UNIQUE

nama VARCHAR(150)

nik_nip VARCHAR(50) UNIQUE

jenis_pegawai ENUM

- internal
- eksternal

no_hp VARCHAR(30)

rfid_uid VARCHAR(100) NULLABLE UNIQUE

jenis_kelamin ENUM

- L
- P

tempat_lahir VARCHAR(100)

tanggal_lahir DATE

alamat TEXT

email VARCHAR(150) NULLABLE

foto VARCHAR(255) NULL

ttd_digital VARCHAR(255) NULL

status ENUM

- aktif
- nonaktif

created_at
updated_at

INDEX

user_id
nik_nip
rfid_uid

---

# SISWA

## siswa

id BIGINT PK

user_id FK UNIQUE

nisn VARCHAR(30) UNIQUE

nama VARCHAR(150)

jenis_kelamin ENUM

- L
- P

tempat_lahir VARCHAR(100)

tanggal_lahir DATE

alamat TEXT

no_hp VARCHAR(30)

foto VARCHAR(255) NULL

nama_ayah VARCHAR(150)
nama_ibu VARCHAR(150)

pekerjaan_ayah VARCHAR(150)
pekerjaan_ibu VARCHAR(150)

nik_ayah VARCHAR(50)
nik_ibu VARCHAR(50)

alamat_ortu TEXT
telpon_ortu VARCHAR(30)

nama_wali VARCHAR(150)
pekerjaan_wali VARCHAR(150)
alamat_wali TEXT
telpon_wali VARCHAR(30)

anak_ke INTEGER

sekolah_asal VARCHAR(150)

rfid_uid VARCHAR(100) NULLABLE
UNIQUE

status ENUM

- aktif
- mutasi
- lulus

created_at
updated_at

INDEX

nisn
rfid_uid

---

# AKADEMIK

## tahun_ajaran

id BIGINT PK

tahun VARCHAR(20)

aktif BOOLEAN

created_at
updated_at

---

## kelas

id BIGINT PK

nama_kelas VARCHAR(50)

tingkat INTEGER

status ENUM

- aktif
- nonaktif

created_at
updated_at

---

## kelas_siswa

id BIGINT PK

siswa_id FK

kelas_id FK

tahun_ajaran_id FK

status ENUM

- aktif
- naik
- lulus

created_at
updated_at

INDEX

siswa_id
kelas_id
tahun_ajaran_id

UNIQUE

siswa_id + tahun_ajaran_id

---

# EKSTRAKURIKULER

## ekstrakurikuler

id BIGINT PK

nama VARCHAR(150)

deskripsi TEXT

jadwal TEXT

kuota INTEGER NULL

status ENUM

- aktif
- nonaktif

created_at
updated_at

---

## ekstrakurikuler_pengampu

id BIGINT PK

ekstrakurikuler_id FK

pegawai_id FK

role ENUM

- pembina
- pelatih

created_at
updated_at

INDEX

pegawai_id
ekstrakurikuler_id

---

## anggota_ekskul

id BIGINT PK

siswa_id FK

ekstrakurikuler_id FK

tahun_ajaran_id FK

tanggal_daftar DATE

status ENUM

- aktif
- nonaktif

created_at
updated_at

---

## sesi_kegiatan

id BIGINT PK

ekstrakurikuler_id FK

pelatih_id FK

tanggal DATE

waktu_mulai TIME

waktu_selesai TIME

status ENUM

- aktif
- selesai
- batal

created_at
updated_at

---

## kehadiran_ekskul

id BIGINT PK

sesi_kegiatan_id FK

siswa_id FK

status ENUM

- hadir
- izin
- sakit
- alpa

sumber ENUM

- rfid
- manual

petugas_id FK NULL

waktu_scan DATETIME NULL

created_at
updated_at

---

## laporan_bulanan

id BIGINT PK

ekstrakurikuler_id FK

tahun_ajaran_id FK

bulan INTEGER

tahun INTEGER

tujuan LONGTEXT

pencapaian LONGTEXT

evaluasi LONGTEXT

kendala LONGTEXT

solusi LONGTEXT

tempat_kegiatan LONGTEXT

dokumentasi LONGTEXT

rata_kehadiran DECIMAL(5,2)

status ENUM

- draft
- pending_pembina
- pending_waka
- pending_kepala
- approved
- rejected

submitted_at DATETIME NULL

approved_at DATETIME NULL

created_at
updated_at

---

## proposal_kegiatan

id BIGINT PK

ekstrakurikuler_id FK

nama_kegiatan VARCHAR(150)

jenis_kegiatan VARCHAR(150)

tempat VARCHAR(150)

tanggal_kegiatan DATE

peserta LONGTEXT

estimasi_biaya DECIMAL(15,2)

tujuan LONGTEXT

deskripsi LONGTEXT

status ENUM

- draft
- pending_waka
- pending_kepala
- approved
- rejected

submitted_at DATETIME NULL

approved_at DATETIME NULL

created_at
updated_at

---

# KEHADIRAN

## kehadiran_sekolah

id BIGINT PK

siswa_id FK

tanggal DATE

jenis ENUM

- datang
- pulang

status ENUM

- hadir
- telat
- izin
- sakit
- alpa

sumber ENUM

- rfid
- manual

keterangan TEXT NULL

waktu_scan DATETIME NULL

created_at
updated_at

---

## kehadiran_ibadah

id BIGINT PK

siswa_id FK

tanggal DATE

jenis ENUM

- dhuha
- dzuhur

status ENUM

- hadir
- tidak_hadir

sumber ENUM

- rfid
- manual

petugas_id FK NULL

waktu_scan DATETIME NULL

created_at
updated_at

---

# MODUL GURU

## jurnal_mengajar

id BIGINT PK

pegawai_id FK

kelas_id FK

tanggal DATE

jam_ke VARCHAR(20)

mapel VARCHAR(100)

materi LONGTEXT

siswa_tidak_hadir LONGTEXT

siswa_bolos LONGTEXT

catatan LONGTEXT

created_at
updated_at

---

## tugas_guru_izin

id BIGINT PK

pegawai_id FK

kelas_id FK

tanggal DATE

mapel VARCHAR(100)

jam_ke VARCHAR(20)

isi_tugas LONGTEXT

status ENUM

- baru
- disampaikan
- selesai

created_at
updated_at

---

## izin_keluar

id BIGINT PK

siswa_id FK

tanggal DATE

jam_keluar TIME

jam_kembali TIME NULL

keperluan LONGTEXT

tujuan VARCHAR(150)

status_proses ENUM

- keluar
- kembali
- pulang

created_at
updated_at

---

# BK

## permasalahan_siswa

id BIGINT PK

siswa_id FK

pegawai_id FK

tanggal DATE

permasalahan LONGTEXT

penyelesaian LONGTEXT

poin INTEGER

created_at
updated_at

---

## konseling_siswa

id BIGINT PK

siswa_id FK

pegawai_id FK

tanggal DATE

permasalahan LONGTEXT

hasil_konseling LONGTEXT

tindak_lanjut LONGTEXT

created_at
updated_at

---

# PUBLIKASI

## prestasi

id BIGINT PK

ekstrakurikuler_id FK NULL

nama_prestasi VARCHAR(150)

nama_lomba VARCHAR(150)

jenis ENUM

- individu
- tim

tingkat VARCHAR(100)

tanggal DATE

keterangan LONGTEXT

foto VARCHAR(255)

created_by_id FK

created_at
updated_at

---

## prestasi_siswa

id BIGINT PK

prestasi_id FK

siswa_id FK

---

## pengumuman

id BIGINT PK

judul VARCHAR(150)

isi LONGTEXT

lampiran VARCHAR(255)

tanggal_mulai DATE

tanggal_selesai DATE

status ENUM

- draft
- publish
- arsip

created_by_id FK

created_at
updated_at

---

## galeri

id BIGINT PK

judul VARCHAR(150)

deskripsi LONGTEXT

foto VARCHAR(255)

tanggal DATE

kategori ENUM

- sekolah
- ekstrakurikuler
- prestasi
- kegiatan

created_by_id FK

created_at
updated_at

---

# SYSTEM

## activity_logs

id BIGINT PK

user_id FK

aksi VARCHAR(150)

deskripsi LONGTEXT

ip_address VARCHAR(100)

user_agent LONGTEXT

created_at

---

## approval_logs

id BIGINT PK

modul VARCHAR(100)

record_id BIGINT

pegawai_id FK

aksi VARCHAR(100)

catatan LONGTEXT

created_at

---

## settings

id BIGINT PK

nama_madrasah

alamat

telepon

email

logo

favicon

kepala_aktif_id FK

tahun_ajaran_aktif_id FK

created_at
updated_at
