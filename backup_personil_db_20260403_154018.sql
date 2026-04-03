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
-- Dumping data for table `master_jabatan`
--

LOCK TABLES `master_jabatan` WRITE;
/*!40000 ALTER TABLE `master_jabatan` DISABLE KEYS */;
INSERT INTO `master_jabatan` VALUES (1,'KAPOLRES','Kapolres',NULL,'II',2,NULL,NULL,'pimpinan',NULL,1,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'WAKAPOLRES','Wakapolres',NULL,'II',2,NULL,NULL,'pimpinan',NULL,1,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'KABAG_OPS','Kabag Ops',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'KABAG_SDM','Kabag SDM',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'KABAG_INTEL','Kabag Intel',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'KABAG_LOG','Kabag Log',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(7,'KASAT_RESKRIM','Kasat Reskrim',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(8,'KASAT_RESNARKOBA','Kasat Resnarkoba',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(9,'KASAT_LANTAS','Kasat Lantas',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(10,'KASAT_SAMAPTA','Kasat Samapta',NULL,'III',3,NULL,NULL,'pembantu_pimpinan',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(11,'KANIT_1','Kanit 1',NULL,'IV',4,NULL,NULL,'pelaksana_tugas_pokok',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(12,'KANIT_2','Kanit 2',NULL,'IV',4,NULL,NULL,'pelaksana_tugas_pokok',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(13,'KANIT_3','Kanit 3',NULL,'IV',4,NULL,NULL,'pelaksana_tugas_pokok',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(14,'PANIT_1','Panit 1',NULL,'V',5,NULL,NULL,'pelaksana_tugas_pokok',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(15,'PANIT_2','Panit 2',NULL,'V',5,NULL,NULL,'pelaksana_tugas_pokok',NULL,0,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_jabatan` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `master_jenis_penugasan`
--

LOCK TABLES `master_jenis_penugasan` WRITE;
/*!40000 ALTER TABLE `master_jenis_penugasan` DISABLE KEYS */;
INSERT INTO `master_jenis_penugasan` VALUES (1,'Definitif','Definitif','Penugasan definitif/tetap tanpa batas waktu',NULL,1,0,0,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'PS','Pejabat Sementara','Menggantikan pejabat yang berhalangan tetap',6,1,1,1,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'Plt','Pelaksana Tugas','Melaksanakan tugas jabatan yang kosong',3,1,1,0,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'Pjs','Pejabat Sementara','Menggantikan pejabat untuk sementara',6,1,1,0,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'Plh','Pelaksana Harian','Pelaksanaan tugas harian jabatan',1,1,1,0,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'Pj','Penjabat','Menjabat sementara untuk belajar/persiapan',12,1,1,0,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_jenis_penugasan` ENABLE KEYS */;
UNLOCK TABLES;

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
  `jenjang` varchar(20) NOT NULL COMMENT 'bintara, perwira, dll',
  `level` int(11) NOT NULL COMMENT 'Numeric level for comparison',
  `urutan` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pangkat` (`kode_pangkat`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_pangkat`
--

LOCK TABLES `master_pangkat` WRITE;
/*!40000 ALTER TABLE `master_pangkat` DISABLE KEYS */;
INSERT INTO `master_pangkat` VALUES (1,'BRIGADIR','Brigadir','Brigadir Polisi','II/a','Bintara',10,1,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'BRIPKA','Bripka','Brigadir Polisi Kepala','II/b','Bintara',11,2,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'AIPDA','Aipda','Ajun Inspektur Polisi Dua','II/c','Bintara',12,3,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'AIPTU','Aiptu','Ajun Inspektur Polisi Satu','II/d','Bintara',13,4,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'IPDA','Ipda','Inspektur Polisi Dua','III/a','Perwira Pertama',20,5,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'IPTU','Iptu','Inspektur Polisi Satu','III/b','Perwira Pertama',21,6,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(7,'AKP','AKP','Ajun Komisaris Polisi','III/c','Perwira Pertama',22,7,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(8,'KOMPOL','KOMPOL','Komisaris Polisi','III/d','Perwira Pertama',23,8,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(9,'AKBP','AKBP','Ajun Komisaris Besar Polisi','IV/a','Perwira Menengah',30,9,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(10,'KOMBIN','KOMBIN','Komisaris Besar Polisi','IV/b','Perwira Menengah',31,10,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_pangkat` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `master_satuan_fungsi`
--

LOCK TABLES `master_satuan_fungsi` WRITE;
/*!40000 ALTER TABLE `master_satuan_fungsi` DISABLE KEYS */;
INSERT INTO `master_satuan_fungsi` VALUES (1,'SAT_RESKRIM','Sat Reskrim','Satuan Reserse Kriminal',3,'reskrim',1,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'SAT_RESNARKOBA','Sat Resnarkoba','Satuan Reserse Narkoba',3,'resnarkoba',2,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'SAT_LANTAS','Sat Lantas','Satuan Lalu Lintas',3,'lantas',3,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'SAT_INTELKAM','Sat Intelkam','Satuan Intelkam',3,'intelkam',4,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'SAT_SAMAPTA','Sat Samapta','Satuan Samapta',3,'samapta',5,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'SAT_BINMAS','Sat Binmas','Satuan Binmas',3,'binmas',6,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(7,'SAT_SABHARA','Sat Sabhara','Satuan Sabhara',3,'sabhara',7,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(8,'SAT_TAMSIL','Sat Tamsil','Satuan Tamsil',3,'tamsil',8,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(9,'SAT_LABBAK','Sat Labbak','Satuan Labfor & Olah TKP',3,'labbak',9,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(10,'SPKT','SPKT','Sentra Pelayanan Kepolisian Terpadu',3,'spkt',10,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_satuan_fungsi` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `master_unit_pendukung`
--

LOCK TABLES `master_unit_pendukung` WRITE;
/*!40000 ALTER TABLE `master_unit_pendukung` DISABLE KEYS */;
INSERT INTO `master_unit_pendukung` VALUES (1,'UNIT_KEU','Unit Keuangan','Unit Keuangan','keuangan',1,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'UNIT_UMUM','Unit Umum','Unit Umum & Kepegawaian','umum',2,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'UNIT_BMN','Unit BMN','Unit Barang Milik Negara','bmn',3,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'UNIT_TIU','Unit TIU','Unit Tata Usaha','tiu',4,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'UNIT_TIK','Unit TIK','Unit Teknologi Informasi','tik',5,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'UNIT_DOKKES','Unit Dokkes','Unit Dokter & Kesehatan','dokkes',6,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(7,'UNIT_PROVOS','Unit Provos','Unit Provost','provos',7,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(8,'UNIT_WABPROF','Unit Wabprof','Unit Pengawasan Profesional','wabprof',8,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_unit_pendukung` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `master_unsur`
--

LOCK TABLES `master_unsur` WRITE;
/*!40000 ALTER TABLE `master_unsur` DISABLE KEYS */;
INSERT INTO `master_unsur` VALUES (1,'UNSUR_PIMPINAN','Unsur Pimpinan','Unsur Pimpinan POLRES','pimpinan',1,1,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(2,'UNSUR_PEMBANTU','Unsur Pembantu','Unsur Pembantu Pimpinan','pembantu_pimpinan',2,2,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(3,'UNSUR_PELAKSANA','Unsur Pelaksana','Unsur Pelaksana Tugas Pokok','pelaksana_tugas_pokok',3,3,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(4,'UNSUR_KEWILAYAHAN','Unsur Kewilayahan','Unsur Pelaksana Kewilayahan','pelaksana_kewilayahan',4,4,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(5,'UNSUR_PENDUKUNG','Unsur Pendukung','Unsur Pendukung Administrasi','pendukung',5,5,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06'),(6,'UNSUR_LAINNYA','Unsur Lainnya','Unsur Lainnya','lainnya',6,6,NULL,1,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `master_unsur` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `penugasan_sementara`
--

LOCK TABLES `penugasan_sementara` WRITE;
/*!40000 ALTER TABLE `penugasan_sementara` DISABLE KEYS */;
/*!40000 ALTER TABLE `penugasan_sementara` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `personil`
--

LOCK TABLES `personil` WRITE;
/*!40000 ALTER TABLE `personil` DISABLE KEYS */;
/*!40000 ALTER TABLE `personil` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `riwayat_jabatan`
--

LOCK TABLES `riwayat_jabatan` WRITE;
/*!40000 ALTER TABLE `riwayat_jabatan` DISABLE KEYS */;
/*!40000 ALTER TABLE `riwayat_jabatan` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `riwayat_pangkat`
--

LOCK TABLES `riwayat_pangkat` WRITE;
/*!40000 ALTER TABLE `riwayat_pangkat` DISABLE KEYS */;
/*!40000 ALTER TABLE `riwayat_pangkat` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrator','admin@polres.go.id','admin',1,NULL,'2026-04-02 17:30:06','2026-04-02 17:30:06');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `v_penugasan_aktif`
--

DROP TABLE IF EXISTS `v_penugasan_aktif`;
/*!50001 DROP VIEW IF EXISTS `v_penugasan_aktif`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_penugasan_aktif` AS SELECT
 1 AS `id`,
  1 AS `id_personil`,
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
-- Final view structure for view `v_penugasan_aktif`
--

/*!50001 DROP VIEW IF EXISTS `v_penugasan_aktif`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_penugasan_aktif` AS select `ps`.`id` AS `id`,`ps`.`id_personil` AS `id_personil`,`ps`.`id_jabatan` AS `id_jabatan`,`ps`.`id_jenis_penugasan` AS `id_jenis_penugasan`,`ps`.`no_sk_penugasan` AS `no_sk_penugasan`,`ps`.`tanggal_sk` AS `tanggal_sk`,`ps`.`tanggal_mulai` AS `tanggal_mulai`,`ps`.`tanggal_selesai` AS `tanggal_selesai`,`ps`.`alasan_penugasan` AS `alasan_penugasan`,`ps`.`keterangan` AS `keterangan`,`ps`.`status` AS `status`,`ps`.`id_riwayat_jabatan` AS `id_riwayat_jabatan`,`ps`.`created_at` AS `created_at`,`ps`.`updated_at` AS `updated_at`,`p`.`nrp` AS `nrp`,`p`.`nama` AS `nama_personil`,`mp`.`nama_pangkat` AS `nama_pangkat`,`mj`.`nama_jabatan` AS `nama_jabatan`,`mj`.`eselon` AS `eselon`,`mjp`.`kode_penugasan` AS `kode_penugasan`,`mjp`.`nama_penugasan` AS `nama_penugasan`,to_days(`ps`.`tanggal_selesai`) - to_days(curdate()) AS `hari_sisa` from ((((`penugasan_sementara` `ps` join `personil` `p` on(`ps`.`id_personil` = `p`.`id`)) join `master_pangkat` `mp` on(`p`.`id_pangkat` = `mp`.`id`)) join `master_jabatan` `mj` on(`ps`.`id_jabatan` = `mj`.`id`)) join `master_jenis_penugasan` `mjp` on(`ps`.`id_jenis_penugasan` = `mjp`.`id`)) where `ps`.`status` = 'Aktif' and `ps`.`tanggal_selesai` >= curdate() */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_personil_detail`
--

/*!50001 DROP VIEW IF EXISTS `v_personil_detail`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_personil_detail` AS select `p`.`id` AS `id`,`p`.`nrp` AS `nrp`,`p`.`nama` AS `nama`,`p`.`gelar_depan` AS `gelar_depan`,`p`.`gelar_belakang` AS `gelar_belakang`,`p`.`tempat_lahir` AS `tempat_lahir`,`p`.`tanggal_lahir` AS `tanggal_lahir`,`p`.`jenis_kelamin` AS `jenis_kelamin`,`p`.`agama` AS `agama`,`p`.`status_nikah` AS `status_nikah`,`p`.`id_pangkat` AS `id_pangkat`,`p`.`golongan` AS `golongan`,`p`.`tmt_pangkat` AS `tmt_pangkat`,`p`.`id_jabatan` AS `id_jabatan`,`p`.`id_unsur` AS `id_unsur`,`p`.`id_satuan_fungsi` AS `id_satuan_fungsi`,`p`.`id_unit_pendukung` AS `id_unit_pendukung`,`p`.`is_penugasan_definitif` AS `is_penugasan_definitif`,`p`.`status_kepegawaian` AS `status_kepegawaian`,`p`.`tmt_pengangkatan` AS `tmt_pengangkatan`,`p`.`tmt_pensiun` AS `tmt_pensiun`,`p`.`alamat` AS `alamat`,`p`.`no_telepon` AS `no_telepon`,`p`.`email` AS `email`,`p`.`no_karpeg` AS `no_karpeg`,`p`.`no_ktp` AS `no_ktp`,`p`.`no_npwp` AS `no_npwp`,`p`.`pendidikan_terakhir` AS `pendidikan_terakhir`,`p`.`jurusan` AS `jurusan`,`p`.`tahun_lulus` AS `tahun_lulus`,`p`.`jumlah_anak` AS `jumlah_anak`,`p`.`foto` AS `foto`,`p`.`is_active` AS `is_active`,`p`.`created_at` AS `created_at`,`p`.`updated_at` AS `updated_at`,`p`.`created_by` AS `created_by`,`p`.`updated_by` AS `updated_by`,`mp`.`nama_pangkat` AS `nama_pangkat`,`mj`.`nama_jabatan` AS `nama_jabatan`,`mj`.`eselon` AS `eselon`,`mu`.`nama_unsur` AS `nama_unsur`,`msf`.`nama_satfung` AS `nama_satfung`,`mup`.`nama_unit` AS `nama_unit_pendukung` from (((((`personil` `p` left join `master_pangkat` `mp` on(`p`.`id_pangkat` = `mp`.`id`)) left join `master_jabatan` `mj` on(`p`.`id_jabatan` = `mj`.`id`)) left join `master_unsur` `mu` on(`p`.`id_unsur` = `mu`.`id`)) left join `master_satuan_fungsi` `msf` on(`p`.`id_satuan_fungsi` = `msf`.`id`)) left join `master_unit_pendukung` `mup` on(`p`.`id_unit_pendukung` = `mup`.`id`)) where `p`.`is_active` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_ps_percentage`
--

/*!50001 DROP VIEW IF EXISTS `v_ps_percentage`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_ps_percentage` AS select count(case when `mjp`.`kode_penugasan` = 'PS' then 1 end) AS `jumlah_ps`,count(0) AS `total_jabatan_struktural`,round(count(case when `mjp`.`kode_penugasan` = 'PS' then 1 end) / count(0) * 100,2) AS `ps_percentage` from ((`master_jabatan` `mj` left join `penugasan_sementara` `ps` on(`mj`.`id` = `ps`.`id_jabatan` and `ps`.`status` = 'Aktif')) left join `master_jenis_penugasan` `mjp` on(`ps`.`id_jenis_penugasan` = `mjp`.`id`)) where `mj`.`is_struktural` = 1 and `mj`.`is_active` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-03 15:40:18
