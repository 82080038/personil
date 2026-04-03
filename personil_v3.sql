-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: personil_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `master_jabatan`
--

DROP TABLE IF EXISTS `master_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_jabatan` varchar(20) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `eselon` varchar(10) NOT NULL COMMENT 'II, III, IV, V',
  `tingkat_eselon` int(11) NOT NULL,
  `id_pangkat_minimal` int(11) DEFAULT NULL,
  `id_pangkat_maksimal` int(11) DEFAULT NULL,
  `unsur_kategori` varchar(50) NOT NULL COMMENT 'pimpinan, pembantu, pelaksana, kewilayahan, pendukung',
  `unit_organisasi` varchar(100) DEFAULT NULL,
  `is_pimpinan` tinyint(1) DEFAULT 0,
  `is_struktural` tinyint(1) DEFAULT 1,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_jabatan` (`kode_jabatan`),
  KEY `id_pangkat_minimal` (`id_pangkat_minimal`),
  KEY `id_pangkat_maksimal` (`id_pangkat_maksimal`),
  CONSTRAINT `master_jabatan_ibfk_1` FOREIGN KEY (`id_pangkat_minimal`) REFERENCES `master_pangkat` (`id`),
  CONSTRAINT `master_jabatan_ibfk_2` FOREIGN KEY (`id_pangkat_maksimal`) REFERENCES `master_pangkat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_jenis_penugasan`
--

DROP TABLE IF EXISTS `master_jenis_penugasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_jenis_penugasan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_penugasan` varchar(10) NOT NULL COMMENT 'Definitif, PS, Plt, Pjs, Plh, Pj',
  `nama_penugasan` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `max_durasi_bulan` int(11) DEFAULT NULL COMMENT 'NULL untuk definitif',
  `memerlukan_sk` tinyint(1) DEFAULT 1,
  `is_sementara` tinyint(1) DEFAULT 0,
  `ps_percentage_applies` tinyint(1) DEFAULT 0 COMMENT 'TRUE untuk PS',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_penugasan` (`kode_penugasan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_pangkat`
--

DROP TABLE IF EXISTS `master_pangkat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_pangkat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pangkat` varchar(10) NOT NULL,
  `nama_pangkat` varchar(50) NOT NULL,
  `nama_pangkat_lengkap` varchar(100) NOT NULL,
  `golongan` varchar(20) NOT NULL,
  `jenjang` varchar(20) NOT NULL COMMENT 'perwira_tinggi, perwira_menengah, perwira_pertama, bintara_tinggi, bintara, tamtama, asn',
  `level` int(11) NOT NULL COMMENT 'Numeric level for comparison (1-27)',
  `urutan` int(11) NOT NULL,
  `jenis_personel` enum('Polri','ASN') DEFAULT 'Polri',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pangkat` (`kode_pangkat`),
  KEY `idx_level` (`level`),
  KEY `idx_jenjang` (`jenjang`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_satuan_fungsi`
--

DROP TABLE IF EXISTS `master_satuan_fungsi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_satuan_fungsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_satfung` varchar(20) NOT NULL,
  `nama_satfung` varchar(100) NOT NULL,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `id_unsur` int(11) DEFAULT NULL,
  `jenis` varchar(50) NOT NULL COMMENT 'reskrim, intelkam, samapta, dll',
  `urutan` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_satfung` (`kode_satfung`),
  KEY `id_unsur` (`id_unsur`),
  CONSTRAINT `master_satuan_fungsi_ibfk_1` FOREIGN KEY (`id_unsur`) REFERENCES `master_unsur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_unit_pendukung`
--

DROP TABLE IF EXISTS `master_unit_pendukung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_unit_pendukung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_unit` varchar(20) NOT NULL,
  `nama_unit` varchar(100) NOT NULL,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `jenis` varchar(50) NOT NULL COMMENT 'keuangan, umum, bmnp, kepegawaian, dll',
  `urutan` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_unit` (`kode_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_unsur`
--

DROP TABLE IF EXISTS `master_unsur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_unsur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_unsur` varchar(30) NOT NULL,
  `nama_unsur` varchar(100) NOT NULL,
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL COMMENT 'pimpinan, pembantu_pimpinan, pelaksana_tugas_pokok, pelaksana_kewilayahan, pendukung, lainnya',
  `level_unsur` int(11) NOT NULL,
  `urutan` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_unsur` (`kode_unsur`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `penugasan_sementara`
--

DROP TABLE IF EXISTS `penugasan_sementara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penugasan_sementara` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personil` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `id_jenis_penugasan` int(11) NOT NULL,
  `no_sk_penugasan` varchar(50) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `alasan_penugasan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Aktif','Diperpanjang','Berakhir') DEFAULT 'Aktif',
  `id_riwayat_jabatan` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_jabatan` (`id_jabatan`),
  KEY `id_riwayat_jabatan` (`id_riwayat_jabatan`),
  KEY `idx_personil` (`id_personil`),
  KEY `idx_status` (`status`),
  KEY `idx_jenis` (`id_jenis_penugasan`),
  CONSTRAINT `penugasan_sementara_ibfk_1` FOREIGN KEY (`id_personil`) REFERENCES `personil` (`id`),
  CONSTRAINT `penugasan_sementara_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `master_jabatan` (`id`),
  CONSTRAINT `penugasan_sementara_ibfk_3` FOREIGN KEY (`id_jenis_penugasan`) REFERENCES `master_jenis_penugasan` (`id`),
  CONSTRAINT `penugasan_sementara_ibfk_4` FOREIGN KEY (`id_riwayat_jabatan`) REFERENCES `riwayat_jabatan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personil`
--

DROP TABLE IF EXISTS `personil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nrp` varchar(8) NOT NULL COMMENT '8 digit angka - PERKAP',
  `nama` varchar(100) NOT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `gelar_belakang` varchar(20) DEFAULT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `status_nikah` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT 'Belum Kawin',
  `id_pangkat` int(11) DEFAULT NULL,
  `golongan` varchar(20) DEFAULT NULL,
  `tmt_pangkat` date DEFAULT NULL COMMENT 'Terhitung Mulai Tanggal pangkat',
  `id_jabatan` int(11) DEFAULT NULL,
  `id_unsur` int(11) DEFAULT NULL,
  `id_satuan_fungsi` int(11) DEFAULT NULL,
  `id_unit_pendukung` int(11) DEFAULT NULL,
  `is_penugasan_definitif` tinyint(1) DEFAULT 1,
  `status_kepegawaian` enum('Aktif','Cuti','Tugas Belajar','Dinas Luar','Pensiun','Non Aktif') DEFAULT 'Aktif',
  `tmt_pengangkatan` date DEFAULT NULL,
  `tmt_pensiun` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_karpeg` varchar(20) DEFAULT NULL,
  `no_ktp` varchar(16) DEFAULT NULL,
  `no_npwp` varchar(20) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `tahun_lulus` int(11) DEFAULT NULL,
  `jumlah_anak` int(11) DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nrp` (`nrp`),
  KEY `id_satuan_fungsi` (`id_satuan_fungsi`),
  KEY `id_unit_pendukung` (`id_unit_pendukung`),
  KEY `idx_nrp` (`nrp`),
  KEY `idx_nama` (`nama`),
  KEY `idx_pangkat` (`id_pangkat`),
  KEY `idx_jabatan` (`id_jabatan`),
  KEY `idx_unsur` (`id_unsur`),
  KEY `idx_status` (`status_kepegawaian`),
  CONSTRAINT `personil_ibfk_1` FOREIGN KEY (`id_pangkat`) REFERENCES `master_pangkat` (`id`),
  CONSTRAINT `personil_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `master_jabatan` (`id`),
  CONSTRAINT `personil_ibfk_3` FOREIGN KEY (`id_unsur`) REFERENCES `master_unsur` (`id`),
  CONSTRAINT `personil_ibfk_4` FOREIGN KEY (`id_satuan_fungsi`) REFERENCES `master_satuan_fungsi` (`id`),
  CONSTRAINT `personil_ibfk_5` FOREIGN KEY (`id_unit_pendukung`) REFERENCES `master_unit_pendukung` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `riwayat_jabatan`
--

DROP TABLE IF EXISTS `riwayat_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `riwayat_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personil` int(11) NOT NULL,
  `id_jabatan_lama` int(11) DEFAULT NULL,
  `id_jabatan_baru` int(11) DEFAULT NULL,
  `id_jenis_penugasan` int(11) NOT NULL,
  `jenis_mutasi` enum('Promosi','Mutasi','Rotasi','Demosi') DEFAULT 'Mutasi',
  `no_sk` varchar(50) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `tmt_jabatan` date NOT NULL,
  `tmt_berakhir` date DEFAULT NULL,
  `alasan_mutasi` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_personil` (`id_personil`),
  KEY `id_jabatan_lama` (`id_jabatan_lama`),
  KEY `id_jabatan_baru` (`id_jabatan_baru`),
  KEY `id_jenis_penugasan` (`id_jenis_penugasan`),
  CONSTRAINT `riwayat_jabatan_ibfk_1` FOREIGN KEY (`id_personil`) REFERENCES `personil` (`id`),
  CONSTRAINT `riwayat_jabatan_ibfk_2` FOREIGN KEY (`id_jabatan_lama`) REFERENCES `master_jabatan` (`id`),
  CONSTRAINT `riwayat_jabatan_ibfk_3` FOREIGN KEY (`id_jabatan_baru`) REFERENCES `master_jabatan` (`id`),
  CONSTRAINT `riwayat_jabatan_ibfk_4` FOREIGN KEY (`id_jenis_penugasan`) REFERENCES `master_jenis_penugasan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `riwayat_pangkat`
--

DROP TABLE IF EXISTS `riwayat_pangkat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `riwayat_pangkat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personil` int(11) NOT NULL,
  `id_pangkat_lama` int(11) DEFAULT NULL,
  `id_pangkat_baru` int(11) NOT NULL,
  `jenis_kenaikan` enum('Reguler','Luar Biasa','Prestasi','Penghargaan') DEFAULT 'Reguler',
  `no_sk` varchar(50) NOT NULL,
  `tanggal_sk` date NOT NULL,
  `tmt_pangkat` date NOT NULL,
  `masa_kerja_tahun` int(11) DEFAULT NULL,
  `masa_kerja_bulan` int(11) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_personil` (`id_personil`),
  KEY `id_pangkat_lama` (`id_pangkat_lama`),
  KEY `id_pangkat_baru` (`id_pangkat_baru`),
  CONSTRAINT `riwayat_pangkat_ibfk_1` FOREIGN KEY (`id_personil`) REFERENCES `personil` (`id`),
  CONSTRAINT `riwayat_pangkat_ibfk_2` FOREIGN KEY (`id_pangkat_lama`) REFERENCES `master_pangkat` (`id`),
  CONSTRAINT `riwayat_pangkat_ibfk_3` FOREIGN KEY (`id_pangkat_baru`) REFERENCES `master_pangkat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','operator','viewer') DEFAULT 'operator',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `v_penugasan_aktif`
--

DROP TABLE IF EXISTS `v_penugasan_aktif`;
/*!50001 DROP VIEW IF EXISTS `v_penugasan_aktif`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_penugasan_aktif` AS SELECT
 1 AS `id`,
  1 AS mysqldump: Couldn't execute 'SHOW FUNCTION STATUS WHERE Db = 'personil_db'': Column count of mysql.proc is wrong. Expected 21, found 20. Created with MariaDB 100108, now running 100432. Please use mysql_upgrade to fix this error (1558)
`id_personil`,
  1 AS `id_jabatan`,
  1 AS `id_jenis_penugasan`,
  1 AS `no_sk_penugasan`,
  1 AS `tanggal_sk`,
  1 AS `tanggal_mulai`,
  1 AS `tanggal_selesai`,
  1 AS `alasan_penugasan`,
  1 AS `keterangan`,
  1 AS `status`,
  1 AS `id_riwayat_jabatan`,
  1 AS `created_at`,
  1 AS `updated_at`,
  1 AS `nrp`,
  1 AS `nama_personil`,
  1 AS `nama_pangkat`,
  1 AS `nama_jabatan`,
  1 AS `eselon`,
  1 AS `kode_penugasan`,
  1 AS `nama_penugasan`,
  1 AS `hari_sisa` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_personil_detail`
--

DROP TABLE IF EXISTS `v_personil_detail`;
/*!50001 DROP VIEW IF EXISTS `v_personil_detail`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_personil_detail` AS SELECT
 1 AS `id`,
  1 AS `nrp`,
  1 AS `nama`,
  1 AS `gelar_depan`,
  1 AS `gelar_belakang`,
  1 AS `tempat_lahir`,
  1 AS `tanggal_lahir`,
  1 AS `jenis_kelamin`,
  1 AS `agama`,
  1 AS `status_nikah`,
  1 AS `id_pangkat`,
  1 AS `golongan`,
  1 AS `tmt_pangkat`,
  1 AS `id_jabatan`,
  1 AS `id_unsur`,
  1 AS `id_satuan_fungsi`,
  1 AS `id_unit_pendukung`,
  1 AS `is_penugasan_definitif`,
  1 AS `status_kepegawaian`,
  1 AS `tmt_pengangkatan`,
  1 AS `tmt_pensiun`,
  1 AS `alamat`,
  1 AS `no_telepon`,
  1 AS `email`,
  1 AS `no_karpeg`,
  1 AS `no_ktp`,
  1 AS `no_npwp`,
  1 AS `pendidikan_terakhir`,
  1 AS `jurusan`,
  1 AS `tahun_lulus`,
  1 AS `jumlah_anak`,
  1 AS `foto`,
  1 AS `is_active`,
  1 AS `created_at`,
  1 AS `updated_at`,
  1 AS `created_by`,
  1 AS `updated_by`,
  1 AS `nama_pangkat`,
  1 AS `nama_jabatan`,
  1 AS `eselon`,
  1 AS `nama_unsur`,
  1 AS `nama_satfung`,
  1 AS `nama_unit_pendukung` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_ps_percentage`
--

DROP TABLE IF EXISTS `v_ps_percentage`;
/*!50001 DROP VIEW IF EXISTS `v_ps_percentage`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_ps_percentage` AS SELECT
 1 AS `jumlah_ps`,
  1 AS `total_jabatan_struktural`,
  1 AS `ps_percentage` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'personil_db'
--
