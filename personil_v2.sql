-- ==========================================================
-- DATABASE: db_bagops - VERSI 2.0
-- DESKRIPSI: Manajemen Operasional Polres (SMO-BAGOPS)
-- UPDATE: April 2026 - Perbaikan sesuai Regulasi Kepolisian
-- REGULASI: 
--   - Perpol No. 2 Tahun 2021 (SOTK Polres/Polsek)
--   - Perpol No. 7 Tahun 2025 (Penambahan Satres PPA & PPO)
--   - Perkap tentang Kepangkatan Polri
-- ==========================================================

CREATE DATABASE IF NOT EXISTS db_bagops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_bagops;

-- ==========================================================
-- 1. TABEL MASTER JENIS PERSONEL
-- ==========================================================
DROP TABLE IF EXISTS m_jenis_personel;
CREATE TABLE m_jenis_personel (
    id_jenis INT PRIMARY KEY AUTO_INCREMENT,
    nama_jenis VARCHAR(50) NOT NULL,
    keterangan VARCHAR(100)
) ENGINE=InnoDB;

INSERT INTO m_jenis_personel (nama_jenis, keterangan) VALUES 
('Anggota POLRI', 'Personel Polri aktif'),
('ASN POLRI', 'PNS di lingkungan Polri'),
('PHL / Honorer', 'Pegawai Harian Lepas dan Tenaga Honorer');

-- ==========================================================
-- 2. TABEL MASTER PANGKAT (27 PANGKAT - LENGKAP)
-- ==========================================================
-- Perwira Tinggi (Pati) - Level Polda/Kabapolres besar
-- Perwira Menengah (Pamen) - Level Polres
-- Perwira Pertama (Pama) - Level Polres/Polsek
-- Bintara Tinggi - Level Polsek
-- Bintara - Level Polsek
-- Tamtama - Level Polsek (BARU - 6 pangkat)
-- ASN Polri - Semua level (BARU - 3 pangkat)

DROP TABLE IF EXISTS m_pangkat;
CREATE TABLE m_pangkat (
    id_pangkat INT PRIMARY KEY AUTO_INCREMENT,
    nama_pangkat VARCHAR(100) NOT NULL,
    singkatan VARCHAR(30) NOT NULL,
    hirarki_level INT NOT NULL COMMENT '1 tertinggi, 27 terendah',
    jenis_id INT,
    golongan VARCHAR(20) COMMENT 'Golongan kepangkatan',
    kategori VARCHAR(30) COMMENT 'Pati/Pamen/Pama/Bintara/Tamtama/ASN',
    FOREIGN KEY (jenis_id) REFERENCES m_jenis_personel(id_jenis),
    INDEX idx_hirarki (hirarki_level),
    INDEX idx_jenis (jenis_id)
) ENGINE=InnoDB;

-- GOLONGAN IV - PERWIRA MENENGAH (PAMEN)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Komisaris Besar Polisi', 'Kombes Pol', 1, 1, 'IV/c', 'Perwira Menengah'),
('Ajun Komisaris Besar Polisi', 'AKBP', 2, 1, 'IV/b', 'Perwira Menengah'),
('Komisaris Polisi', 'Kompol', 3, 1, 'IV/a', 'Perwira Menengah');

-- GOLONGAN III - PERWIRA PERTAMA (PAMA)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Ajun Komisaris Polisi', 'AKP', 4, 1, 'III/c', 'Perwira Pertama'),
('Inspektur Polisi Satu', 'Iptu', 5, 1, 'III/b', 'Perwira Pertama'),
('Inspektur Polisi Dua', 'Ipda', 6, 1, 'III/a', 'Perwira Pertama');

-- GOLONGAN II - BINTARA TINGGI
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Ajun Inspektur Polisi Satu', 'Aiptu', 7, 1, 'II/d', 'Bintara Tinggi'),
('Ajun Inspektur Polisi Dua', 'Aipda', 8, 1, 'II/c', 'Bintara Tinggi');

-- GOLONGAN II - BINTARA
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Brigadir Polisi Kepala', 'Bripka', 9, 1, 'II/b', 'Bintara'),
('Brigadir Polisi', 'Brigpol', 10, 1, 'II/a', 'Bintara'),
('Brigadir Polisi Satu', 'Briptu', 11, 1, 'II/a', 'Bintara'),
('Brigadir Polisi Dua', 'Bripda', 12, 1, 'II/a', 'Bintara');

-- GOLONGAN I - TAMTAMA (BARU - 6 PANGKAT)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Ajun Brigadir Polisi', 'Abrip', 13, 1, 'I/d', 'Tamtama'),
('Ajun Brigadir Polisi Satu', 'Abriptu', 14, 1, 'I/c', 'Tamtama'),
('Ajun Brigadir Polisi Dua', 'Abripda', 15, 1, 'I/b', 'Tamtama'),
('Bhayangkara Kepala', 'Bharaka', 16, 1, 'I/b', 'Tamtama'),
('Bhayangkara Satu', 'Bharatu', 17, 1, 'I/a', 'Tamtama'),
('Bhayangkara Dua', 'Bharada', 18, 1, 'I/a', 'Tamtama');

-- ASN POLRI - GOLONGAN IV (PEMBINA)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Pembina Utama Muda', 'Pembina Tk I', 19, 2, 'IV/c', 'ASN'),
('Pembina', 'Pembina', 20, 2, 'IV/a', 'ASN');

-- ASN POLRI - GOLONGAN III (PENATA)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Penata Tingkat I', 'Penata Tk I', 21, 2, 'III/c', 'ASN'),
('Penata', 'Penata', 22, 2, 'III/b', 'ASN');

-- ASN POLRI - GOLONGAN II (PENATA MUDA)
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Penata Muda Tingkat I', 'Penda Tk I', 23, 2, 'II/c', 'ASN'),
('Penata Muda', 'Penda', 24, 2, 'II/a', 'ASN');

-- ASN POLRI - GOLONGAN I (PENGATUR & JURU) - BARU
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id, golongan, kategori) VALUES 
('Pengatur Tingkat I', 'Peng Tk I', 25, 2, 'I/d', 'ASN'),
('Pengatur', 'Pengatur', 26, 2, 'I/c', 'ASN'),
('Juru', 'Juru', 27, 2, 'I/a', 'ASN');

-- ==========================================================
-- 3. TABEL MASTER SATUAN FUNGSI (SATFUNG) - DIPERBARUI
-- ==========================================================
-- Sesuai Perpol No. 2/2021 & Perpol No. 7/2025

DROP TABLE IF EXISTS m_satfung;
CREATE TABLE m_satfung (
    id_satfung INT PRIMARY KEY AUTO_INCREMENT,
    nama_satfung VARCHAR(150) NOT NULL,
    singkatan VARCHAR(20),
    kategori_unsur ENUM('Pimpinan', 'Staf', 'Pelaksana', 'Pendukung', 'Wilayah') NOT NULL,
    kode VARCHAR(10),
    deskripsi TEXT,
    aktif TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- UNSUR PIMPINAN
INSERT INTO m_satfung (nama_satfung, singkatan, kategori_unsur, kode, deskripsi) VALUES 
('Kepolisian Resort', 'Polres', 'Pimpinan', 'RES', 'Kepala dan Wakil Kepala Polres');

-- BAGIAN (Staf Ahli) - DIPERBARUI
INSERT INTO m_satfung (nama_satfung, singkatan, kategori_unsur, kode, deskripsi) VALUES 
('Bagian Operasional', 'Bagops', 'Staf', 'OPS', 'Bagian Operasional - koordinasi operasional'),
('Bagian Perencanaan dan Pengamanan', 'Bag Renmin', 'Staf', 'REN', 'Bagian Perencanaan dan Renmin - PERBAIKAN NAMA'),
('Bagian Sumber Daya Manusia', 'Bag SDM', 'Staf', 'SDM', 'Bagian SDM - Bensat'),
('Bagian Keuangan', 'Bidkeu', 'Staf', 'KEU', 'Bagian Keuangan - BARU'),
('Bagian Protap', 'Bag Protap', 'Staf', 'PRT', 'Bagian Protap - BARU'),
('Bagian Logistik', 'Bag Log', 'Staf', 'LOG', 'Bagian Logistik');

-- SATUAN FUNGSI (Pelaksana) - DIPERBARUI
INSERT INTO m_satfung (nama_satfung, singkatan, kategori_unsur, kode, deskripsi) VALUES 
('Satuan Intelkam', 'Intelkam', 'Pelaksana', 'INT', 'Intelijen dan Keamanan'),
('Satuan Reserse Kriminal', 'Satreskrim', 'Pelaksana', 'RESKRIM', 'Reserse Kriminal - PERBAIKAN NAMA'),
('Satuan Reserse Narkoba', 'Satresnarkoba', 'Pelaksana', 'NARKOBA', 'Reserse Narkoba'),
('Satuan Samapta', 'Samapta', 'Pelaksana', 'SMP', 'Kesamaptaan'),
('Satuan Lalu Lintas', 'Lantas', 'Pelaksana', 'LNT', 'Lalu Lintas dan Korlantas'),
('Satuan Binmas', 'Binmas', 'Pelaksana', 'BIN', 'Bina Masyarakat'),
('Satuan Reserse PPA', 'Satres PPA', 'Pelaksana', 'PPA', 'Perlindungan Perempuan dan Anak - BARU Perpol 7/2025'),
('Satuan Reserse PPO', 'Satres PPO', 'Pelaksana', 'PPO', 'Perampasan dan Penipuan - BARU Perpol 7/2025');

-- SEKSI (Pelayanan Umum/Pendukung) - DIPERBARUI
INSERT INTO m_satfung (nama_satfung, singkatan, kategori_unsur, kode, deskripsi) VALUES 
('Seksi Profesi dan Pengamanan', 'Sek Propam', 'Pendukung', 'PRP', 'Propam - Internal affairs'),
('Seksi Hubungan Masyarakat', 'Sek Humas', 'Pendukung', 'HMS', 'Humas - Public relations'),
('Seksi Teknologi Informasi', 'Sek TI', 'Pendukung', 'TI', 'TIK - Information technology'),
('Seksi Dokumen dan Kesehatan', 'Sidokkes', 'Pendukung', 'DKS', 'Dokumen dan Kesehatan - PERBAIKAN NAMA'),
('Inspektorat Pengawasan Daerah', 'Itwasda', 'Pendukung', 'ITD', 'Pengawasan Daerah - BARU'),
('Inspektorat Pengawasan Umum', 'Itwasum', 'Pendukung', 'ITU', 'Pengawasan Umum - BARU');

-- POLSEK (Wilayah)
INSERT INTO m_satfung (nama_satfung, singkatan, kategori_unsur, kode, deskripsi) VALUES 
('Polsek Jajaran', 'Polsek', 'Wilayah', 'SEK', 'Kepolisian Sektor di wilayah hukum Polres');

-- ==========================================================
-- 4. TABEL MASTER PERSONEL (DIPERLUAS)
-- ==========================================================
DROP TABLE IF EXISTS m_personel;
CREATE TABLE m_personel (
    id_personel INT PRIMARY KEY AUTO_INCREMENT,
    nomor_induk VARCHAR(25) UNIQUE NOT NULL COMMENT 'NRP untuk Polri, NIP untuk ASN',
    nama_lengkap VARCHAR(150) NOT NULL,
    nama_panggilan VARCHAR(50),
    
    -- Data Jabatan
    id_jenis INT,
    id_pangkat INT,
    id_satfung INT,
    jabatan VARCHAR(150),
    eselon VARCHAR(10) COMMENT 'IIB2, IIIA2, dll',
    
    -- Data Pribadi - BARU
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P') DEFAULT 'L',
    alamat TEXT,
    no_hp VARCHAR(20),
    email VARCHAR(100),
    
    -- Data Kepegawaian - BARU
    tanggal_masuk DATE COMMENT 'TMT masuk Polri/ASN',
    tanggal_pengangkatan DATE COMMENT 'TMT pangkat terakhir',
    masa_kerja_tahun INT DEFAULT 0,
    masa_kerja_bulan INT DEFAULT 0,
    
    -- Status
    status_aktif TINYINT(1) DEFAULT 1 COMMENT '1:Aktif, 0:Nonaktif',
    status_kepegawaian ENUM('Aktif', 'Pensiun', 'Mutasi', 'Cuti', 'Dinas Luar') DEFAULT 'Aktif',
    
    -- File - BARU
    foto VARCHAR(255) COMMENT 'Path foto profil',
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by VARCHAR(20),
    updated_by VARCHAR(20),
    
    FOREIGN KEY (id_jenis) REFERENCES m_jenis_personel(id_jenis),
    FOREIGN KEY (id_pangkat) REFERENCES m_pangkat(id_pangkat),
    FOREIGN KEY (id_satfung) REFERENCES m_satfung(id_satfung),
    
    INDEX idx_nama (nama_lengkap),
    INDEX idx_nrp (nomor_induk),
    INDEX idx_pangkat (id_pangkat),
    INDEX idx_satfung (id_satfung),
    INDEX idx_status (status_aktif)
) ENGINE=InnoDB;

-- ==========================================================
-- 5. TABEL TRANSAKSI SPRIN (DIPERLUAS)
-- ==========================================================
DROP TABLE IF EXISTS t_sprin_detail;
DROP TABLE IF EXISTS t_sprin;

CREATE TABLE t_sprin (
    id_sprin INT PRIMARY KEY AUTO_INCREMENT,
    no_sprin VARCHAR(100) UNIQUE NOT NULL COMMENT 'Format: Sprin/XXX/Romawi/Kode/Tahun',
    kode_ops VARCHAR(20) COMMENT 'Kode operasi untuk auto-generate',
    
    -- Isi Sprin
    dasar_hukum TEXT,
    pertimbangan TEXT,
    nama_giat VARCHAR(200) NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    lokasi_giat TEXT,
    
    -- Penandatangan
    pejabat_ttd_nama VARCHAR(150),
    pejabat_ttd_pangkat VARCHAR(50),
    pejabat_ttd_jabatan VARCHAR(100),
    pejabat_ttd_nrp VARCHAR(25),
    
    -- Status
    status_ops ENUM('Draft', 'Aktif', 'Selesai', 'Batal') DEFAULT 'Draft',
    keterangan_status TEXT COMMENT 'Alasan pembatalan/selesai',
    
    -- Dokumen
    file_sprin VARCHAR(255) COMMENT 'Path file hasil export',
    tgl_cetak_sprin DATE,
    
    -- Metadata - BARU
    created_by VARCHAR(20) COMMENT 'NRP pembuat',
    updated_by VARCHAR(20) COMMENT 'NRP pengubah terakhir',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_no_sprin (no_sprin),
    INDEX idx_status (status_ops),
    INDEX idx_tanggal (tgl_mulai, tgl_selesai)
) ENGINE=InnoDB;

-- ==========================================================
-- 6. TABEL DETAIL PERSONEL DALAM SPRIN
-- ==========================================================
CREATE TABLE t_sprin_detail (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_sprin INT NOT NULL,
    id_personel INT NOT NULL,
    peran_tugas VARCHAR(100) DEFAULT 'Anggota Pengamanan',
    keterangan TEXT COMMENT 'Keterangan khusus untuk personel ini',
    
    FOREIGN KEY (id_sprin) REFERENCES t_sprin(id_sprin) ON DELETE CASCADE,
    FOREIGN KEY (id_personel) REFERENCES m_personel(id_personel),
    UNIQUE KEY (id_sprin, id_personel),
    INDEX idx_personel (id_personel)
) ENGINE=InnoDB;

-- ==========================================================
-- 7. TABEL LOG AKTIVITAS (DIPERLUAS)
-- ==========================================================
DROP TABLE IF EXISTS t_log;
CREATE TABLE t_log (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nrp_operator VARCHAR(20),
    nama_operator VARCHAR(100),
    aksi VARCHAR(100) COMMENT 'Simpan Sprin, Hapus Personel, dll',
    tabel_terkait VARCHAR(50) COMMENT 'Nama tabel yang dimodifikasi',
    id_record INT COMMENT 'ID record yang terdampak',
    keterangan TEXT,
    
    -- Data Teknis - BARU
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    
    INDEX idx_timestamp (timestamp),
    INDEX idx_operator (nrp_operator),
    INDEX idx_aksi (aksi)
) ENGINE=InnoDB;

-- ==========================================================
-- 8. TABEL LAMPIRAN/DOKUMEN (BARU)
-- ==========================================================
CREATE TABLE t_lampiran (
    id_lampiran INT PRIMARY KEY AUTO_INCREMENT,
    id_sprin INT,
    jenis_lampiran ENUM('Foto', 'Surat', 'Dokumen Pendukung', 'Lainnya') DEFAULT 'Lainnya',
    nama_file VARCHAR(255),
    path_file VARCHAR(255),
    ukuran_file INT COMMENT 'Dalam KB',
    uploaded_by VARCHAR(20),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_sprin) REFERENCES t_sprin(id_sprin) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================================
-- 9. VIEW UNTUK REPORTING (BARU)
-- ==========================================================
CREATE OR REPLACE VIEW v_personel_lengkap AS
SELECT 
    p.*,
    j.nama_jenis,
    k.singkatan as pangkat_singkat,
    k.nama_pangkat,
    k.kategori as pangkat_kategori,
    s.nama_satfung,
    s.singkatan as satfung_singkat,
    s.kategori_unsur as satfung_kategori
FROM m_personel p
LEFT JOIN m_jenis_personel j ON p.id_jenis = j.id_jenis
LEFT JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
LEFT JOIN m_satfung s ON p.id_satfung = s.id_satfung;

CREATE OR REPLACE VIEW v_sprin_lengkap AS
SELECT 
    s.*,
    COUNT(d.id_personel) as jumlah_anggota
FROM t_sprin s
LEFT JOIN t_sprin_detail d ON s.id_sprin = d.id_sprin
GROUP BY s.id_sprin;

-- ==========================================================
-- VERIFIKASI DATA
-- ==========================================================
SELECT 'VERIFIKASI DATA MASTER' as info;

SELECT 
    'Pangkat' as kategori,
    COUNT(*) as total,
    SUM(CASE WHEN jenis_id=1 THEN 1 ELSE 0 END) as polri,
    SUM(CASE WHEN jenis_id=2 THEN 1 ELSE 0 END) as asn
FROM m_pangkat
UNION ALL
SELECT 
    'Satfung' as kategori,
    COUNT(*),
    NULL, NULL
FROM m_satfung
UNION ALL
SELECT 
    'Jenis Personel' as kategori,
    COUNT(*),
    NULL, NULL
FROM m_jenis_personel;

SELECT 'Setup database lengkap!' as pesan;
