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
CREATE DATABASE IF NOT EXISTS `sigma3_v4` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sigma3_v4`;

-- Dumping structure for table sigma3_v4.users
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigma3_v4.users: ~9 rows (approximately)
REPLACE INTO `users` (`id`, `username`, `email`, `password`, `must_change_password`, `email_verified_at`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'superadmin', NULL, '$2y$12$504tY0aXGfhhPEW4FFGcwuFR09cMS/I1NB.bacHzURlbSkh9ii4Hu', 0, NULL, NULL, 'aktif', '2026-06-23 00:56:01', '2026-06-23 00:56:01'),
	(2, '19850101001', NULL, '$2y$12$a838ppD6Iu2yGQDVlk.eE.Wuea/eCVW4fOb8Xtwyl0RCtsCyuUoQ.', 1, NULL, NULL, 'aktif', '2026-06-23 01:07:03', '2026-06-23 01:07:03'),
	(3, '1234567890', NULL, '$2y$12$zyq2IiI2pDstkBVrs/.MauqDEf9KdPZMoX8eG/72qiDx7sW8Hb.0S', 1, NULL, NULL, 'aktif', '2026-06-23 05:27:15', '2026-06-23 05:27:15'),
	(4, '19850101002', NULL, '$2y$12$lLqeYd6vs9cOWEpj0BJaa.tC3viq4TcXy/yHzC3h41owP1h/eS98u', 1, NULL, NULL, 'aktif', '2026-06-23 07:06:15', '2026-06-23 07:06:15'),
	(5, '19850101003', NULL, '$2y$12$rcHvcSxcn7pPCig5Ee2siu0MdRmmRhHKOInUnH4p2SxRaKW7bhsmS', 1, NULL, NULL, 'aktif', '2026-06-25 11:14:59', '2026-06-25 11:14:59'),
	(6, '1234567899', NULL, '$2y$12$hg9WcvXOV8xBlii8gNspOO0uyIG9HDoNOM6H.Mo6WJKTbY8cRiyzS', 1, NULL, NULL, 'aktif', '2026-07-04 09:38:11', '2026-07-04 09:38:11'),
	(8, '0123456789', NULL, '$2y$12$MoKPCe6UHv5RKeh5Xj6nzeK0stuGcjTkErYw9wuOo.EzWDutyAPam', 1, NULL, NULL, 'aktif', '2026-07-04 12:09:59', '2026-07-04 12:09:59'),
	(9, '123456787', NULL, '$2y$12$utolyPObRI1yFsW5V3FZrep5nFxcPekEz/WzJ6VRWt5yvqG.eV5/y', 1, NULL, NULL, 'aktif', '2026-07-04 15:51:17', '2026-07-04 15:51:17'),
	(10, '123456786', NULL, '$2y$12$n/.4l9jEjsrXtSWIs5eqgukdnd1xOsP2yZ/S5gjc6u4UGHfNzKgrC', 1, NULL, NULL, 'aktif', '2026-07-04 15:51:17', '2026-07-04 15:51:17');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
