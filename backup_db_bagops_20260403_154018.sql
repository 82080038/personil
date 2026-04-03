-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: db_bagops
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
-- Table structure for table `m_jenis_personel`
--

DROP TABLE IF EXISTS `m_jenis_personel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_jenis_personel` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) NOT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_jenis_personel`
--

LOCK TABLES `m_jenis_personel` WRITE;
/*!40000 ALTER TABLE `m_jenis_personel` DISABLE KEYS */;
INSERT INTO `m_jenis_personel` VALUES (1,'Anggota POLRI'),(2,'ASN POLRI'),(3,'PHL / Honorer');
/*!40000 ALTER TABLE `m_jenis_personel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_pangkat`
--

DROP TABLE IF EXISTS `m_pangkat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_pangkat` (
  `id_pangkat` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pangkat` varchar(100) NOT NULL,
  `singkatan` varchar(20) NOT NULL,
  `hirarki_level` int(11) NOT NULL,
  `jenis_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pangkat`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `m_pangkat_ibfk_1` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis_personel` (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_pangkat`
--

LOCK TABLES `m_pangkat` WRITE;
/*!40000 ALTER TABLE `m_pangkat` DISABLE KEYS */;
INSERT INTO `m_pangkat` VALUES (1,'Komisaris Besar Polisi','Kombes Pol',1,1),(2,'Ajun Komisaris Besar Polisi','AKBP',2,1),(3,'Komisaris Polisi','Kompol',3,1),(4,'Ajun Komisaris Polisi','AKP',4,1),(5,'Inspektur Polisi Satu','Iptu',5,1),(6,'Inspektur Polisi Dua','Ipda',6,1),(7,'Ajun Inspektur Polisi Satu','Aiptu',7,1),(8,'Ajun Inspektur Polisi Dua','Aipda',8,1),(9,'Brigadir Polisi Kepala','Bripka',9,1),(10,'Brigadir Polisi','Brigpol',10,1),(11,'Brigadir Polisi Satu','Briptu',11,1),(12,'Brigadir Polisi Dua','Bripda',12,1),(13,'Ajun Brigadir Polisi','Abrip',13,1),(14,'Ajun Brigadir Polisi Satu','Abriptu',14,1),(15,'Ajun Brigadir Polisi Dua','Abripda',15,1),(16,'Bhayangkara Kepala','Bharaka',16,1),(17,'Bhayangkara Satu','Bharatu',17,1),(18,'Bhayangkara Dua','Bharada',18,1),(19,'Pembina Utama Muda','Pembina Tk I',19,2),(20,'Pembina','Pembina',20,2),(21,'Penata Tingkat I','Penata Tk I',21,2),(22,'Penata','Penata',22,2),(23,'Penata Muda Tingkat I','Penda Tk I',23,2),(24,'Penata Muda','Penda',24,2),(25,'Pengatur Tingkat I','Peng Tk I',25,2),(26,'Pengatur','Pengatur',26,2),(27,'Juru','Juru',27,2);
/*!40000 ALTER TABLE `m_pangkat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_personel`
--

DROP TABLE IF EXISTS `m_personel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_personel` (
  `id_personel` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_induk` varchar(25) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `id_jenis` int(11) DEFAULT NULL,
  `id_pangkat` int(11) DEFAULT NULL,
  `id_satfung` int(11) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `eselon` varchar(10) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_personel`),
  UNIQUE KEY `nomor_induk` (`nomor_induk`),
  KEY `id_jenis` (`id_jenis`),
  KEY `id_pangkat` (`id_pangkat`),
  KEY `id_satfung` (`id_satfung`),
  CONSTRAINT `m_personel_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `m_jenis_personel` (`id_jenis`),
  CONSTRAINT `m_personel_ibfk_2` FOREIGN KEY (`id_pangkat`) REFERENCES `m_pangkat` (`id_pangkat`),
  CONSTRAINT `m_personel_ibfk_3` FOREIGN KEY (`id_satfung`) REFERENCES `m_satfung` (`id_satfung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_personel`
--

LOCK TABLES `m_personel` WRITE;
/*!40000 ALTER TABLE `m_personel` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_personel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_satfung`
--

DROP TABLE IF EXISTS `m_satfung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_satfung` (
  `id_satfung` int(11) NOT NULL AUTO_INCREMENT,
  `nama_satfung` varchar(100) NOT NULL,
  `kategori_unsur` enum('Pimpinan','Staf','Pelaksana','Pendukung','Wilayah') NOT NULL,
  PRIMARY KEY (`id_satfung`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_satfung`
--

LOCK TABLES `m_satfung` WRITE;
/*!40000 ALTER TABLE `m_satfung` DISABLE KEYS */;
INSERT INTO `m_satfung` VALUES (1,'Unsur Pimpinan','Pimpinan'),(2,'Bagian Operasional','Staf'),(3,'Bagian Perencanaan dan Pengamanan (Bag Renmin)','Staf'),(4,'Bagian SDM','Staf'),(5,'Bagian Logistik','Staf'),(6,'Satuan Intelkam','Pelaksana'),(7,'Satuan Reserse Kriminal (Satreskrim)','Pelaksana'),(8,'Satuan Narkoba','Pelaksana'),(9,'Satuan Samapta','Pelaksana'),(10,'Satuan Lantas','Pelaksana'),(11,'Satuan Binmas','Pelaksana'),(12,'Seksi Propam','Pendukung'),(13,'Seksi Humas','Pendukung'),(14,'Seksi TI','Pendukung'),(15,'Seksi Dokumen dan Kesehatan (Sidokkes)','Pendukung'),(16,'Polsek Jajaran','Wilayah'),(17,'Bagian Keuangan (Bidkeu)','Staf'),(18,'Bagian Protap (Bag Protap)','Staf'),(19,'Satuan Reserse Perlindungan Perempuan dan Anak (Satres PPA)','Pelaksana'),(20,'Satuan Reserse Perampasan dan Penipuan (Satres PPO)','Pelaksana'),(21,'Inspektorat Pengawasan Daerah (Itwasda)','Pendukung'),(22,'Inspektorat Pengawasan Umum (Itwasum)','Pendukung');
/*!40000 ALTER TABLE `m_satfung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_log`
--

DROP TABLE IF EXISTS `t_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `nrp_operator` varchar(20) DEFAULT NULL,
  `aksi` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_log`
--

LOCK TABLES `t_log` WRITE;
/*!40000 ALTER TABLE `t_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sprin`
--

DROP TABLE IF EXISTS `t_sprin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sprin` (
  `id_sprin` int(11) NOT NULL AUTO_INCREMENT,
  `no_sprin` varchar(100) NOT NULL,
  `dasar_hukum` text DEFAULT NULL,
  `pertimbangan` text DEFAULT NULL,
  `nama_giat` varchar(200) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `lokasi_giat` text DEFAULT NULL,
  `pejabat_ttd_nama` varchar(150) DEFAULT NULL,
  `pejabat_ttd_pangkat` varchar(50) DEFAULT NULL,
  `pejabat_ttd_jabatan` varchar(100) DEFAULT NULL,
  `tgl_cetak_sprin` date DEFAULT NULL,
  `status_ops` enum('Draft','Aktif','Selesai','Batal') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_sprin`),
  UNIQUE KEY `no_sprin` (`no_sprin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sprin`
--

LOCK TABLES `t_sprin` WRITE;
/*!40000 ALTER TABLE `t_sprin` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sprin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sprin_detail`
--

DROP TABLE IF EXISTS `t_sprin_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sprin_detail` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_sprin` int(11) DEFAULT NULL,
  `id_personel` int(11) DEFAULT NULL,
  `peran_tugas` varchar(100) DEFAULT 'Anggota Pengamanan',
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `id_sprin` (`id_sprin`,`id_personel`),
  KEY `id_personel` (`id_personel`),
  CONSTRAINT `t_sprin_detail_ibfk_1` FOREIGN KEY (`id_sprin`) REFERENCES `t_sprin` (`id_sprin`) ON DELETE CASCADE,
  CONSTRAINT `t_sprin_detail_ibfk_2` FOREIGN KEY (`id_personel`) REFERENCES `m_personel` (`id_personel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sprin_detail`
--

LOCK TABLES `t_sprin_detail` WRITE;
/*!40000 ALTER TABLE `t_sprin_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sprin_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-03 15:40:18
