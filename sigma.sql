-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sigma3_v4
DROP DATABASE IF EXISTS `sigma3_v4`;
CREATE DATABASE IF NOT EXISTS `sigma3_v4` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sigma3_v4`;

-- Dumping structure for table sigma3_v4.activity_logs
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `aksi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_aksi_index` (`aksi`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.anggota_ekskul
DROP TABLE IF EXISTS `anggota_ekskul`;
CREATE TABLE IF NOT EXISTS `anggota_ekskul` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `ekstrakurikuler_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anggota_ekskul_unique` (`siswa_id`,`ekstrakurikuler_id`,`tahun_ajaran_id`),
  KEY `anggota_ekskul_siswa_id_index` (`siswa_id`),
  KEY `anggota_ekskul_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `anggota_ekskul_tahun_ajaran_id_index` (`tahun_ajaran_id`),
  CONSTRAINT `anggota_ekskul_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_ekskul_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_ekskul_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.ekstrakurikuler
DROP TABLE IF EXISTS `ekstrakurikuler`;
CREATE TABLE IF NOT EXISTS `ekstrakurikuler` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `jadwal` json DEFAULT NULL,
  `kuota` int DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ekstrakurikuler_nama_index` (`nama`),
  KEY `ekstrakurikuler_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.ekstrakurikuler_pengampu
DROP TABLE IF EXISTS `ekstrakurikuler_pengampu`;
CREATE TABLE IF NOT EXISTS `ekstrakurikuler_pengampu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `role` enum('pembina','pelatih') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ep_role_unique` (`ekstrakurikuler_id`,`pegawai_id`,`role`),
  KEY `ekstrakurikuler_pengampu_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `ekstrakurikuler_pengampu_pegawai_id_index` (`pegawai_id`),
  CONSTRAINT `ekstrakurikuler_pengampu_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ekstrakurikuler_pengampu_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.galeri
DROP TABLE IF EXISTS `galeri`;
CREATE TABLE IF NOT EXISTS `galeri` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` longtext COLLATE utf8mb4_unicode_ci,
  `tanggal` date NOT NULL,
  `kategori` enum('sekolah','ekstrakurikuler','prestasi','kegiatan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galeri_created_by_id_foreign` (`created_by_id`),
  KEY `galeri_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `galeri_kategori_index` (`kategori`),
  KEY `galeri_tanggal_index` (`tanggal`),
  CONSTRAINT `galeri_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `galeri_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.galeri_foto
DROP TABLE IF EXISTS `galeri_foto`;
CREATE TABLE IF NOT EXISTS `galeri_foto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `galeri_id` bigint unsigned NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galeri_foto_galeri_id_index` (`galeri_id`),
  CONSTRAINT `galeri_foto_galeri_id_foreign` FOREIGN KEY (`galeri_id`) REFERENCES `galeri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.izin_keluar
DROP TABLE IF EXISTS `izin_keluar`;
CREATE TABLE IF NOT EXISTS `izin_keluar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jam_keluar` time NOT NULL,
  `jam_kembali` time DEFAULT NULL,
  `waktu_cetak` datetime DEFAULT NULL,
  `tujuan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_proses` enum('keluar','kembali','pulang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'keluar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `izin_keluar_siswa_id_foreign` (`siswa_id`),
  KEY `izin_keluar_pegawai_id_foreign` (`pegawai_id`),
  KEY `izin_keluar_tanggal_status_proses_index` (`tanggal`,`status_proses`),
  KEY `izin_keluar_tanggal_index` (`tanggal`),
  CONSTRAINT `izin_keluar_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `izin_keluar_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.jurnal_mengajar
DROP TABLE IF EXISTS `jurnal_mengajar`;
CREATE TABLE IF NOT EXISTS `jurnal_mengajar` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jam_ke` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jurnal_mengajar_unique` (`pegawai_id`,`kelas_id`,`tanggal`,`jam_ke`),
  KEY `jurnal_mengajar_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `jurnal_mengajar_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `jurnal_mengajar_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kehadiran_ekskul
DROP TABLE IF EXISTS `kehadiran_ekskul`;
CREATE TABLE IF NOT EXISTS `kehadiran_ekskul` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sesi_kegiatan_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `status` enum('hadir','tidak_hadir') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sumber` enum('rfid','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `petugas_id` bigint unsigned DEFAULT NULL,
  `waktu_scan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kehadiran_ekskul_unique` (`sesi_kegiatan_id`,`siswa_id`),
  KEY `kehadiran_ekskul_petugas_id_foreign` (`petugas_id`),
  KEY `kehadiran_ekskul_sesi_kegiatan_id_index` (`sesi_kegiatan_id`),
  KEY `kehadiran_ekskul_siswa_id_index` (`siswa_id`),
  CONSTRAINT `kehadiran_ekskul_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kehadiran_ekskul_sesi_kegiatan_id_foreign` FOREIGN KEY (`sesi_kegiatan_id`) REFERENCES `sesi_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kehadiran_ekskul_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kehadiran_ibadah
DROP TABLE IF EXISTS `kehadiran_ibadah`;
CREATE TABLE IF NOT EXISTS `kehadiran_ibadah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('dhuha','dzuhur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('hadir','tidak_hadir','menstruasi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sumber` enum('rfid','manual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `petugas_id` bigint unsigned DEFAULT NULL,
  `waktu_scan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kehadiran_ibadah_siswa_id_tanggal_jenis_unique` (`siswa_id`,`tanggal`,`jenis`),
  KEY `kehadiran_ibadah_petugas_id_foreign` (`petugas_id`),
  KEY `kehadiran_ibadah_siswa_id_tanggal_index` (`siswa_id`,`tanggal`),
  CONSTRAINT `kehadiran_ibadah_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kehadiran_ibadah_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kehadiran_pembelajaran
DROP TABLE IF EXISTS `kehadiran_pembelajaran`;
CREATE TABLE IF NOT EXISTS `kehadiran_pembelajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jurnal_mengajar_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bolos, ke_bk, ke_uks, lomba, dispensasi, lainnya',
  `keterangan` text COLLATE utf8mb4_unicode_ci COMMENT 'Keterangan tambahan jika diperlukan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kehadiran_pembelajaran_jurnal_siswa_unique` (`jurnal_mengajar_id`,`siswa_id`),
  KEY `kehadiran_pembelajaran_status_index` (`status`),
  KEY `kehadiran_pembelajaran_siswa_id_index` (`siswa_id`),
  CONSTRAINT `kehadiran_pembelajaran_jurnal_mengajar_id_foreign` FOREIGN KEY (`jurnal_mengajar_id`) REFERENCES `jurnal_mengajar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kehadiran_pembelajaran_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kehadiran_sekolah
DROP TABLE IF EXISTS `kehadiran_sekolah`;
CREATE TABLE IF NOT EXISTS `kehadiran_sekolah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','terlambat','izin','sakit','alfa') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Status kehadiran final',
  `sumber` enum('rfid','manual') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sumber pencatatan kehadiran',
  `jam_datang` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `petugas_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kehadiran_sekolah_siswa_tanggal_unique` (`siswa_id`,`tanggal`),
  KEY `kehadiran_sekolah_petugas_id_foreign` (`petugas_id`),
  KEY `kehadiran_sekolah_siswa_id_index` (`siswa_id`),
  KEY `kehadiran_sekolah_tanggal_index` (`tanggal`),
  KEY `kehadiran_sekolah_status_index` (`status`),
  CONSTRAINT `kehadiran_sekolah_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kehadiran_sekolah_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kelas
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE IF NOT EXISTS `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` tinyint unsigned NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_nama_kelas_unique` (`nama_kelas`),
  KEY `kelas_tingkat_index` (`tingkat`),
  KEY `kelas_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.kelas_siswa
DROP TABLE IF EXISTS `kelas_siswa`;
CREATE TABLE IF NOT EXISTS `kelas_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `status` enum('aktif','naik','lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_siswa_siswa_id_tahun_ajaran_id_unique` (`siswa_id`,`tahun_ajaran_id`),
  KEY `kelas_siswa_siswa_id_index` (`siswa_id`),
  KEY `kelas_siswa_kelas_id_index` (`kelas_id`),
  KEY `kelas_siswa_tahun_ajaran_id_index` (`tahun_ajaran_id`),
  CONSTRAINT `kelas_siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_siswa_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.konseling_siswa
DROP TABLE IF EXISTS `konseling_siswa`;
CREATE TABLE IF NOT EXISTS `konseling_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `permasalahan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasil_konseling` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindak_lanjut` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `konseling_siswa_siswa_id_foreign` (`siswa_id`),
  KEY `konseling_siswa_pegawai_id_foreign` (`pegawai_id`),
  KEY `konseling_siswa_tanggal_index` (`tanggal`),
  CONSTRAINT `konseling_siswa_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `konseling_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_approval_logs
DROP TABLE IF EXISTS `laporan_approval_logs`;
CREATE TABLE IF NOT EXISTS `laporan_approval_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laporan_kegiatan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_approval_logs_laporan_kegiatan_id_foreign` (`laporan_kegiatan_id`),
  KEY `laporan_approval_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `laporan_approval_logs_laporan_kegiatan_id_foreign` FOREIGN KEY (`laporan_kegiatan_id`) REFERENCES `laporan_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_approval_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_bulanan
DROP TABLE IF EXISTS `laporan_bulanan`;
CREATE TABLE IF NOT EXISTS `laporan_bulanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned NOT NULL,
  `bulan` tinyint unsigned NOT NULL,
  `tahun` smallint unsigned NOT NULL,
  `created_by_id` bigint unsigned NOT NULL,
  `pendahuluan` longtext COLLATE utf8mb4_unicode_ci,
  `tujuan` longtext COLLATE utf8mb4_unicode_ci,
  `evaluasi` longtext COLLATE utf8mb4_unicode_ci,
  `kendala` longtext COLLATE utf8mb4_unicode_ci,
  `solusi` longtext COLLATE utf8mb4_unicode_ci,
  `penutup` longtext COLLATE utf8mb4_unicode_ci,
  `dokumentasi` json DEFAULT NULL,
  `status` enum('draft','pending_pembina','pending_waka','pending_kepala','revision','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laporan_bulanan_ekstrakurikuler_id_bulan_tahun_unique` (`ekstrakurikuler_id`,`bulan`,`tahun`),
  KEY `laporan_bulanan_status_index` (`status`),
  KEY `laporan_bulanan_created_by_id_index` (`created_by_id`),
  CONSTRAINT `laporan_bulanan_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_bulanan_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_bulanan_approval_logs
DROP TABLE IF EXISTS `laporan_bulanan_approval_logs`;
CREATE TABLE IF NOT EXISTS `laporan_bulanan_approval_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laporan_bulanan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `aksi` enum('submit','approve_pembina','approve_waka','approve_kepala','revision','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_bulanan_approval_logs_laporan_bulanan_id_index` (`laporan_bulanan_id`),
  KEY `laporan_bulanan_approval_logs_user_id_index` (`user_id`),
  KEY `laporan_bulanan_approval_logs_aksi_index` (`aksi`),
  CONSTRAINT `laporan_bulanan_approval_logs_laporan_bulanan_id_foreign` FOREIGN KEY (`laporan_bulanan_id`) REFERENCES `laporan_bulanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_bulanan_approval_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_kegiatan
DROP TABLE IF EXISTS `laporan_kegiatan`;
CREATE TABLE IF NOT EXISTS `laporan_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_kegiatan_id` bigint unsigned NOT NULL,
  `created_by_id` bigint unsigned NOT NULL,
  `uraian_pelaksanaan` longtext COLLATE utf8mb4_unicode_ci,
  `pencapaian` longtext COLLATE utf8mb4_unicode_ci,
  `prestasi` longtext COLLATE utf8mb4_unicode_ci,
  `evaluasi` longtext COLLATE utf8mb4_unicode_ci,
  `penutup` longtext COLLATE utf8mb4_unicode_ci,
  `dokumentasi` json DEFAULT NULL,
  `sertifikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `berita_acara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','pending_pembina','pending_waka','pending_kepala','revision','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laporan_kegiatan_proposal_kegiatan_id_unique` (`proposal_kegiatan_id`),
  KEY `laporan_kegiatan_status_index` (`status`),
  KEY `laporan_kegiatan_created_by_id_index` (`created_by_id`),
  CONSTRAINT `laporan_kegiatan_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_kegiatan_proposal_kegiatan_id_foreign` FOREIGN KEY (`proposal_kegiatan_id`) REFERENCES `proposal_kegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_kegiatan_pendamping
DROP TABLE IF EXISTS `laporan_kegiatan_pendamping`;
CREATE TABLE IF NOT EXISTS `laporan_kegiatan_pendamping` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laporan_kegiatan_id` bigint unsigned NOT NULL,
  `proposal_pendamping_id` bigint unsigned DEFAULT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `peran` enum('pelatih','pembina','guru_pendamping','official','medis','transportasi','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('hadir','izin','sakit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_kegiatan_pendamping_laporan_kegiatan_id_foreign` (`laporan_kegiatan_id`),
  KEY `laporan_kegiatan_pendamping_proposal_pendamping_id_foreign` (`proposal_pendamping_id`),
  KEY `laporan_kegiatan_pendamping_pegawai_id_foreign` (`pegawai_id`),
  CONSTRAINT `laporan_kegiatan_pendamping_laporan_kegiatan_id_foreign` FOREIGN KEY (`laporan_kegiatan_id`) REFERENCES `laporan_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_kegiatan_pendamping_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_kegiatan_pendamping_proposal_pendamping_id_foreign` FOREIGN KEY (`proposal_pendamping_id`) REFERENCES `proposal_pendamping` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_kegiatan_peserta
DROP TABLE IF EXISTS `laporan_kegiatan_peserta`;
CREATE TABLE IF NOT EXISTS `laporan_kegiatan_peserta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laporan_kegiatan_id` bigint unsigned NOT NULL,
  `proposal_peserta_id` bigint unsigned DEFAULT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_kegiatan_peserta_laporan_kegiatan_id_foreign` (`laporan_kegiatan_id`),
  KEY `laporan_kegiatan_peserta_proposal_peserta_id_foreign` (`proposal_peserta_id`),
  KEY `laporan_kegiatan_peserta_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `laporan_kegiatan_peserta_laporan_kegiatan_id_foreign` FOREIGN KEY (`laporan_kegiatan_id`) REFERENCES `laporan_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_kegiatan_peserta_proposal_peserta_id_foreign` FOREIGN KEY (`proposal_peserta_id`) REFERENCES `proposal_peserta` (`id`) ON DELETE SET NULL,
  CONSTRAINT `laporan_kegiatan_peserta_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.laporan_realisasi_anggaran
DROP TABLE IF EXISTS `laporan_realisasi_anggaran`;
CREATE TABLE IF NOT EXISTS `laporan_realisasi_anggaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laporan_kegiatan_id` bigint unsigned NOT NULL,
  `proposal_rab_id` bigint unsigned DEFAULT NULL,
  `uraian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int unsigned NOT NULL DEFAULT '1',
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_realisasi_anggaran_laporan_kegiatan_id_foreign` (`laporan_kegiatan_id`),
  KEY `laporan_realisasi_anggaran_proposal_rab_id_foreign` (`proposal_rab_id`),
  CONSTRAINT `laporan_realisasi_anggaran_laporan_kegiatan_id_foreign` FOREIGN KEY (`laporan_kegiatan_id`) REFERENCES `laporan_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_realisasi_anggaran_proposal_rab_id_foreign` FOREIGN KEY (`proposal_rab_id`) REFERENCES `proposal_rab` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.pegawai
DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_nip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_pegawai` enum('internal','eksternal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid_uid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ttd_digital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_user_id_unique` (`user_id`),
  UNIQUE KEY `pegawai_nik_nip_unique` (`nik_nip`),
  UNIQUE KEY `pegawai_rfid_uid_unique` (`rfid_uid`),
  CONSTRAINT `pegawai_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.pengumuman
DROP TABLE IF EXISTS `pengumuman`;
CREATE TABLE IF NOT EXISTS `pengumuman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` enum('info','success','warning','danger') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('draft','publish','arsip') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengumuman_created_by_id_foreign` (`created_by_id`),
  KEY `pengumuman_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `pengumuman_status_index` (`status`),
  KEY `pengumuman_tanggal_mulai_index` (`tanggal_mulai`),
  KEY `pengumuman_tanggal_selesai_index` (`tanggal_selesai`),
  KEY `pengumuman_is_pinned_index` (`is_pinned`),
  CONSTRAINT `pengumuman_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumuman_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.permasalahan_siswa
DROP TABLE IF EXISTS `permasalahan_siswa`;
CREATE TABLE IF NOT EXISTS `permasalahan_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `permasalahan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelesaian` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `poin` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permasalahan_siswa_siswa_id_foreign` (`siswa_id`),
  KEY `permasalahan_siswa_pegawai_id_foreign` (`pegawai_id`),
  KEY `permasalahan_siswa_tanggal_index` (`tanggal`),
  CONSTRAINT `permasalahan_siswa_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permasalahan_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.prestasi
DROP TABLE IF EXISTS `prestasi`;
CREATE TABLE IF NOT EXISTS `prestasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned DEFAULT NULL,
  `nama_prestasi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lomba` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('individu','tim') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` longtext COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestasi_created_by_id_foreign` (`created_by_id`),
  KEY `prestasi_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `prestasi_tanggal_index` (`tanggal`),
  KEY `prestasi_jenis_index` (`jenis`),
  CONSTRAINT `prestasi_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestasi_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.prestasi_siswa
DROP TABLE IF EXISTS `prestasi_siswa`;
CREATE TABLE IF NOT EXISTS `prestasi_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestasi_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prestasi_siswa_prestasi_id_siswa_id_unique` (`prestasi_id`,`siswa_id`),
  KEY `prestasi_siswa_prestasi_id_index` (`prestasi_id`),
  KEY `prestasi_siswa_siswa_id_index` (`siswa_id`),
  CONSTRAINT `prestasi_siswa_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestasi_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.proposal_approval_log
DROP TABLE IF EXISTS `proposal_approval_log`;
CREATE TABLE IF NOT EXISTS `proposal_approval_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_kegiatan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` enum('submit','approve_pembina','approve_waka','approve_kepala','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_approval_log_proposal_kegiatan_id_foreign` (`proposal_kegiatan_id`),
  KEY `proposal_approval_log_user_id_foreign` (`user_id`),
  CONSTRAINT `proposal_approval_log_proposal_kegiatan_id_foreign` FOREIGN KEY (`proposal_kegiatan_id`) REFERENCES `proposal_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_approval_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.proposal_kegiatan
DROP TABLE IF EXISTS `proposal_kegiatan`;
CREATE TABLE IF NOT EXISTS `proposal_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned NOT NULL,
  `nama_kegiatan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kegiatan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat_kegiatan` enum('kecamatan','kabupaten','karesidenan','provinsi','nasional','internasional') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_surat_undangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_surat_undangan` date DEFAULT NULL,
  `surat_undangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brosur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `latar_belakang` longtext COLLATE utf8mb4_unicode_ci,
  `dasar_kegiatan` longtext COLLATE utf8mb4_unicode_ci,
  `tujuan` longtext COLLATE utf8mb4_unicode_ci,
  `penutup` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','pending_pembina','pending_waka','pending_kepala','revision','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_kegiatan_status_index` (`status`),
  KEY `proposal_kegiatan_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `proposal_kegiatan_created_by_id_foreign` (`created_by_id`),
  CONSTRAINT `proposal_kegiatan_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_kegiatan_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.proposal_pendamping
DROP TABLE IF EXISTS `proposal_pendamping`;
CREATE TABLE IF NOT EXISTS `proposal_pendamping` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_kegiatan_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned NOT NULL,
  `peran` enum('pelatih','pembina','guru_pendamping','official','medis','transportasi','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_pendamping_proposal_kegiatan_id_foreign` (`proposal_kegiatan_id`),
  KEY `proposal_pendamping_pegawai_id_foreign` (`pegawai_id`),
  CONSTRAINT `proposal_pendamping_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_pendamping_proposal_kegiatan_id_foreign` FOREIGN KEY (`proposal_kegiatan_id`) REFERENCES `proposal_kegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.proposal_peserta
DROP TABLE IF EXISTS `proposal_peserta`;
CREATE TABLE IF NOT EXISTS `proposal_peserta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_kegiatan_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_peserta_proposal_kegiatan_id_foreign` (`proposal_kegiatan_id`),
  KEY `proposal_peserta_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `proposal_peserta_proposal_kegiatan_id_foreign` FOREIGN KEY (`proposal_kegiatan_id`) REFERENCES `proposal_kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_peserta_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.proposal_rab
DROP TABLE IF EXISTS `proposal_rab`;
CREATE TABLE IF NOT EXISTS `proposal_rab` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_kegiatan_id` bigint unsigned NOT NULL,
  `uraian` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'item',
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_rab_proposal_kegiatan_id_foreign` (`proposal_kegiatan_id`),
  CONSTRAINT `proposal_rab_proposal_kegiatan_id_foreign` FOREIGN KEY (`proposal_kegiatan_id`) REFERENCES `proposal_kegiatan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.revision_logs
DROP TABLE IF EXISTS `revision_logs`;
CREATE TABLE IF NOT EXISTS `revision_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `revisionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status_from` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_to` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revision_logs_revisionable_type_revisionable_id_index` (`revisionable_type`,`revisionable_id`),
  KEY `revision_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `revision_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.sesi_kegiatan
DROP TABLE IF EXISTS `sesi_kegiatan`;
CREATE TABLE IF NOT EXISTS `sesi_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ekstrakurikuler_id` bigint unsigned NOT NULL,
  `pelatih_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `program_latihan` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','selesai','batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sesi_kegiatan_ekstrakurikuler_id_index` (`ekstrakurikuler_id`),
  KEY `sesi_kegiatan_pelatih_id_index` (`pelatih_id`),
  KEY `sesi_kegiatan_tanggal_index` (`tanggal`),
  CONSTRAINT `sesi_kegiatan_ekstrakurikuler_id_foreign` FOREIGN KEY (`ekstrakurikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sesi_kegiatan_pelatih_id_foreign` FOREIGN KEY (`pelatih_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_madrasah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_resmi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_madrasah_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_aktif_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran_aktif_id` bigint unsigned DEFAULT NULL,
  `rfid_school_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `rfid_school_checkin_start` time NOT NULL DEFAULT '06:00:00',
  `rfid_school_checkin_end` time NOT NULL DEFAULT '07:10:00',
  `rfid_school_checkout_start` time NOT NULL DEFAULT '14:30:00',
  `rfid_school_checkout_end` time NOT NULL DEFAULT '17:00:00',
  `rfid_ibadah_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `rfid_ibadah_dhuha_start` time NOT NULL DEFAULT '06:40:00',
  `rfid_ibadah_dhuha_end` time NOT NULL DEFAULT '07:00:00',
  `rfid_ibadah_dzuhur_start` time NOT NULL DEFAULT '11:45:00',
  `rfid_ibadah_dzuhur_end` time NOT NULL DEFAULT '12:20:00',
  `rfid_ibadah_disable_friday` tinyint(1) NOT NULL DEFAULT '1',
  `rfid_general_uid_lock_seconds` tinyint unsigned NOT NULL DEFAULT '2',
  `rfid_general_enable_web_nfc` tinyint(1) NOT NULL DEFAULT '1',
  `rfid_general_enable_usb_reader` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_kepala_aktif_id_foreign` (`kepala_aktif_id`),
  KEY `settings_tahun_ajaran_aktif_id_foreign` (`tahun_ajaran_aktif_id`),
  CONSTRAINT `settings_kepala_aktif_id_foreign` FOREIGN KEY (`kepala_aktif_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL,
  CONSTRAINT `settings_tahun_ajaran_aktif_id_foreign` FOREIGN KEY (`tahun_ajaran_aktif_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.siswa
DROP TABLE IF EXISTS `siswa`;
CREATE TABLE IF NOT EXISTS `siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nisn` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ayah` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ayah` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ibu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ortu` text COLLATE utf8mb4_unicode_ci,
  `telpon_ortu` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_wali` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_wali` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_wali` text COLLATE utf8mb4_unicode_ci,
  `telpon_wali` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anak_ke` int DEFAULT NULL,
  `sekolah_asal` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfid_uid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','mutasi','lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswa_user_id_unique` (`user_id`),
  UNIQUE KEY `siswa_nisn_unique` (`nisn`),
  UNIQUE KEY `siswa_rfid_uid_unique` (`rfid_uid`),
  KEY `siswa_user_id_index` (`user_id`),
  KEY `siswa_nisn_index` (`nisn`),
  KEY `siswa_rfid_uid_index` (`rfid_uid`),
  CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.tahun_ajaran
DROP TABLE IF EXISTS `tahun_ajaran`;
CREATE TABLE IF NOT EXISTS `tahun_ajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun_ajaran_tahun_unique` (`tahun`),
  KEY `tahun_ajaran_aktif_index` (`aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.tugas_guru_izin
DROP TABLE IF EXISTS `tugas_guru_izin`;
CREATE TABLE IF NOT EXISTS `tugas_guru_izin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `petugas_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_ke` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_tugas` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('baru','disampaikan','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_guru_izin_pegawai_id_index` (`pegawai_id`),
  KEY `tugas_guru_izin_petugas_id_index` (`petugas_id`),
  KEY `tugas_guru_izin_kelas_id_index` (`kelas_id`),
  KEY `tugas_guru_izin_tanggal_index` (`tanggal`),
  KEY `tugas_guru_izin_status_index` (`status`),
  CONSTRAINT `tugas_guru_izin_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_guru_izin_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_guru_izin_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table sigma3_v4.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
