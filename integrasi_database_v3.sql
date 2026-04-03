-- ==========================================================
-- INTEGRASI DATABASE: personil_db + db_bagops
-- Versi: 3.0 Hybrid
-- Tanggal: April 2026
-- Deskripsi: Merge fitur terbaik dari kedua database
-- ==========================================================

USE personil_db;

-- ==========================================================
-- 1. UPDATE MASTER PANGKAT (10 → 27 pangkat lengkap)
-- ==========================================================
-- Hapus data lama dan insert ulang dengan data lengkap
-- PERHATIAN: Jika sudah ada data personil, perlu migrasi data!

-- Backup data personil terlebih dahulu
-- CREATE TABLE temp_personil_backup AS SELECT * FROM personil;

-- Disable foreign key checks sementara
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus dan re-create master_pangkat dengan struktur optimal
DROP TABLE IF EXISTS master_pangkat;

CREATE TABLE master_pangkat (
  id INT NOT NULL AUTO_INCREMENT,
  kode_pangkat VARCHAR(10) NOT NULL,
  nama_pangkat VARCHAR(50) NOT NULL,
  nama_pangkat_lengkap VARCHAR(100) NOT NULL,
  golongan VARCHAR(20) NOT NULL,
  jenjang VARCHAR(20) NOT NULL COMMENT 'perwira_tinggi, perwira_menengah, perwira_pertama, bintara_tinggi, bintara, tamtama, asn',
  level INT NOT NULL COMMENT 'Numeric level for comparison (1-27)',
  urutan INT NOT NULL,
  jenis_personel ENUM('Polri', 'ASN') DEFAULT 'Polri',
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY kode_pangkat (kode_pangkat),
  INDEX idx_level (level),
  INDEX idx_jenjang (jenjang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert 27 pangkat lengkap sesuai regulasi

-- PERWIRA MENENGAH (PAMEN) - Golongan IV
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('KOMBES', 'Kombes', 'Komisaris Besar Polisi', 'IV/c', 'perwira_menengah', 1, 1, 'Polri'),
('AKBP', 'AKBP', 'Ajun Komisaris Besar Polisi', 'IV/b', 'perwira_menengah', 2, 2, 'Polri'),
('KOMPOL', 'Kompol', 'Komisaris Polisi', 'IV/a', 'perwira_menengah', 3, 3, 'Polri');

-- PERWIRA PERTAMA (PAMA) - Golongan III
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('AKP', 'AKP', 'Ajun Komisaris Polisi', 'III/c', 'perwira_pertama', 4, 4, 'Polri'),
('IPTU', 'Iptu', 'Inspektur Polisi Satu', 'III/b', 'perwira_pertama', 5, 5, 'Polri'),
('IPDA', 'Ipda', 'Inspektur Polisi Dua', 'III/a', 'perwira_pertama', 6, 6, 'Polri');

-- BINTARA TINGGI - Golongan II
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('AIPTU', 'Aiptu', 'Ajun Inspektur Polisi Satu', 'II/d', 'bintara_tinggi', 7, 7, 'Polri'),
('AIPDA', 'Aipda', 'Ajun Inspektur Polisi Dua', 'II/c', 'bintara_tinggi', 8, 8, 'Polri');

-- BINTARA - Golongan II
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('BRIPKA', 'Bripka', 'Brigadir Polisi Kepala', 'II/b', 'bintara', 9, 9, 'Polri'),
('BRIGPOL', 'Brigpol', 'Brigadir Polisi', 'II/a', 'bintara', 10, 10, 'Polri'),
('BRIPTU', 'Briptu', 'Brigadir Polisi Satu', 'II/a', 'bintara', 11, 11, 'Polri'),
('BRIPDA', 'Bripda', 'Brigadir Polisi Dua', 'II/a', 'bintara', 12, 12, 'Polri');

-- TAMTAMA - Golongan I (BARU - 6 pangkat)
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('ABRIP', 'Abrip', 'Ajun Brigadir Polisi', 'I/d', 'tamtama', 13, 13, 'Polri'),
('ABRIPTU', 'Abriptu', 'Ajun Brigadir Polisi Satu', 'I/c', 'tamtama', 14, 14, 'Polri'),
('ABRIPDA', 'Abripda', 'Ajun Brigadir Polisi Dua', 'I/b', 'tamtama', 15, 15, 'Polri'),
('BHARAKA', 'Bharaka', 'Bhayangkara Kepala', 'I/b', 'tamtama', 16, 16, 'Polri'),
('BHARATU', 'Bharatu', 'Bhayangkara Satu', 'I/a', 'tamtama', 17, 17, 'Polri'),
('BHARADA', 'Bharada', 'Bhayangkara Dua', 'I/a', 'tamtama', 18, 18, 'Polri');

-- ASN POLRI - Golongan IV (PEMBINA)
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('PEMBINA_TK_I', 'Pembina Tk.I', 'Pembina Utama Muda', 'IV/c', 'asn', 19, 19, 'ASN'),
('PEMBINA', 'Pembina', 'Pembina', 'IV/a', 'asn', 20, 20, 'ASN');

-- ASN POLRI - Golongan III (PENATA)
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('PENATA_TK_I', 'Penata Tk.I', 'Penata Tingkat I', 'III/c', 'asn', 21, 21, 'ASN'),
('PENATA', 'Penata', 'Penata', 'III/b', 'asn', 22, 22, 'ASN');

-- ASN POLRI - Golongan II (PENATA MUDA)
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('PENDA_TK_I', 'Penda Tk.I', 'Penata Muda Tingkat I', 'II/c', 'asn', 23, 23, 'ASN'),
('PENDA', 'Penda', 'Penata Muda', 'II/a', 'asn', 24, 24, 'ASN');

-- ASN POLRI - Golongan I (PENGATUR & JURU) - BARU
INSERT INTO master_pangkat (kode_pangkat, nama_pangkat, nama_pangkat_lengkap, golongan, jenjang, level, urutan, jenis_personel) VALUES 
('PENG_TK_I', 'Peng. Tk.I', 'Pengatur Tingkat I', 'I/d', 'asn', 25, 25, 'ASN'),
('PENGATUR', 'Pengatur', 'Pengatur', 'I/c', 'asn', 26, 26, 'ASN'),
('JURU', 'Juru', 'Juru', 'I/a', 'asn', 27, 27, 'ASN');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

SELECT '✓ Master Pangkat updated: 27 pangkat' as status;

-- ==========================================================
-- 2. UPDATE MASTER SATUAN FUNGSI (Tambah Satres PPA & PPO)
-- ==========================================================

-- Update nama yang salah dan tambahkan yang baru
UPDATE master_satuan_fungsi SET 
  nama_satfung = 'Satuan Reserse Kriminal (Satreskrim)',
  nama_lengkap = 'Satuan Reserse Kriminal'
WHERE kode_satfung = 'RESKRIM' OR nama_satfung LIKE '%Reskrim%';

-- Tambahkan Satres PPA (Perpol 7/2025)
INSERT IGNORE INTO master_satuan_fungsi (kode_satfung, nama_satfung, nama_lengkap, jenis, urutan, is_active) VALUES 
('SATRES_PPA', 'Satres PPA', 'Satuan Reserse Perlindungan Perempuan dan Anak', 'ppa', 15, 1),
('SATRES_PPO', 'Satres PPO', 'Satuan Reserse Perampasan dan Penipuan', 'ppo', 16, 1);

SELECT '✓ Master Satfung updated: Satres PPA & PPO ditambahkan' as status;

-- ==========================================================
-- 3. BUAT TABEL m_jenis_personel (dari db_bagops)
-- ==========================================================
DROP TABLE IF EXISTS m_jenis_personel;

CREATE TABLE m_jenis_personel (
  id_jenis INT PRIMARY KEY AUTO_INCREMENT,
  nama_jenis VARCHAR(50) NOT NULL,
  keterangan VARCHAR(100),
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO m_jenis_personel (nama_jenis, keterangan) VALUES 
('Anggota POLRI', 'Personel Polri aktif'),
('ASN POLRI', 'PNS di lingkungan Polri'),
('PHL / Honorer', 'Pegawai Harian Lepas dan Tenaga Honorer');

SELECT '✓ Tabel m_jenis_personel created' as status;

-- ==========================================================
-- 4. BUAT TABEL t_sprin & t_sprin_detail (dari db_bagops)
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
  
  -- Metadata
  created_by VARCHAR(20) COMMENT 'NRP pembuat',
  updated_by VARCHAR(20) COMMENT 'NRP pengubah terakhir',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_no_sprin (no_sprin),
  INDEX idx_status (status_ops),
  INDEX idx_tanggal (tgl_mulai, tgl_selesai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE t_sprin_detail (
  id_detail INT PRIMARY KEY AUTO_INCREMENT,
  id_sprin INT NOT NULL,
  id_personel INT NOT NULL,
  peran_tugas VARCHAR(100) DEFAULT 'Anggota Pengamanan',
  keterangan TEXT COMMENT 'Keterangan khusus untuk personel ini',
  
  FOREIGN KEY (id_sprin) REFERENCES t_sprin(id_sprin) ON DELETE CASCADE,
  FOREIGN KEY (id_personel) REFERENCES personil(id),
  UNIQUE KEY (id_sprin, id_personel),
  INDEX idx_personel (id_personel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✓ Tabel t_sprin & t_sprin_detail created' as status;

-- ==========================================================
-- 5. BUAT TABEL t_log (Audit Trail)
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
  
  -- Data Teknis
  ip_address VARCHAR(45),
  user_agent VARCHAR(255),
  
  INDEX idx_timestamp (timestamp),
  INDEX idx_operator (nrp_operator),
  INDEX idx_aksi (aksi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✓ Tabel t_log created' as status;

-- ==========================================================
-- 6. BUAT TABEL t_lampiran (Opsional - untuk upload dokumen)
-- ==========================================================
DROP TABLE IF EXISTS t_lampiran;

CREATE TABLE t_lampiran (
  id_lampiran INT PRIMARY KEY AUTO_INCREMENT,
  id_sprin INT,
  jenis_lampiran ENUM('Foto', 'Surat', 'Dokumen Pendukung', 'Lainnya') DEFAULT 'Lainnya',
  nama_file VARCHAR(255),
  path_file VARCHAR(255),
  ukuran_file INT COMMENT 'Dalam KB',
  uploaded_by VARCHAR(20),
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (id_sprin) REFERENCES t_sprin(id_sprin) ON DELETE CASCADE,
  INDEX idx_sprin (id_sprin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✓ Tabel t_lampiran created' as status;

-- ==========================================================
-- 7. UPDATE VIEW v_personil_detail (Tambah kolom baru)
-- ==========================================================
DROP VIEW IF EXISTS v_personil_detail;

CREATE VIEW v_personil_detail AS
SELECT 
  p.id,
  p.nrp,
  p.nama,
  p.gelar_depan,
  p.gelar_belakang,
  CONCAT_WS(' ', p.gelar_depan, p.nama, p.gelar_belakang) as nama_lengkap,
  p.tempat_lahir,
  p.tanggal_lahir,
  p.jenis_kelamin,
  p.agama,
  p.status_nikah,
  p.id_pangkat,
  p.golongan,
  p.tmt_pangkat,
  p.id_jabatan,
  p.id_unsur,
  p.id_satuan_fungsi,
  p.id_unit_pendukung,
  p.is_penugasan_definitif,
  p.status_kepegawaian,
  p.tmt_pengangkatan,
  p.tmt_pensiun,
  p.alamat,
  p.no_telepon,
  p.email,
  p.no_karpeg,
  p.no_ktp,
  p.no_npwp,
  p.pendidikan_terakhir,
  p.jurusan,
  p.tahun_lulus,
  p.jumlah_anak,
  p.foto,
  p.is_active,
  p.created_at,
  p.updated_at,
  p.created_by,
  p.updated_by,
  mp.kode_pangkat,
  mp.nama_pangkat,
  mp.nama_pangkat_lengkap,
  mp.jenjang as jenjang_pangkat,
  mp.level as level_pangkat,
  mp.jenis_personel,
  mj.kode_jabatan,
  mj.nama_jabatan,
  mj.eselon,
  mj.tingkat_eselon,
  mj.unsur_kategori,
  mu.kode_unsur,
  mu.nama_unsur,
  mu.kategori as kategori_unsur,
  msf.kode_satfung,
  msf.nama_satfung,
  msf.nama_lengkap as nama_satfung_lengkap,
  mup.kode_unit,
  mup.nama_unit as nama_unit_pendukung
FROM (((((personil p 
  LEFT JOIN master_pangkat mp ON p.id_pangkat = mp.id)
  LEFT JOIN master_jabatan mj ON p.id_jabatan = mj.id)
  LEFT JOIN master_unsur mu ON p.id_unsur = mu.id)
  LEFT JOIN master_satuan_fungsi msf ON p.id_satuan_fungsi = msf.id)
  LEFT JOIN master_unit_pendukung mup ON p.id_unit_pendukung = mup.id)
WHERE p.is_active = 1;

SELECT '✓ View v_personil_detail updated' as status;

-- ==========================================================
-- 8. BUAT VIEW v_sprin_detail (BARU)
-- ==========================================================
DROP VIEW IF EXISTS v_sprin_detail;

CREATE VIEW v_sprin_detail AS
SELECT 
  s.*,
  COUNT(d.id_personel) as jumlah_anggota,
  GROUP_CONCAT(DISTINCT CONCAT(mp.kode_pangkat, ' ', p.nama) SEPARATOR ', ') as daftar_personel
FROM t_sprin s
LEFT JOIN t_sprin_detail d ON s.id_sprin = d.id_sprin
LEFT JOIN personil p ON d.id_personel = p.id
LEFT JOIN master_pangkat mp ON p.id_pangkat = mp.id
GROUP BY s.id_sprin;

SELECT '✓ View v_sprin_detail created' as status;

-- ==========================================================
-- VERIFIKASI AKHIR
-- ==========================================================
SELECT '=== VERIFIKASI INTEGRASI ===' as info;

SELECT 
  'Master Pangkat' as tabel, 
  COUNT(*) as total,
  SUM(CASE WHEN jenis_personel='Polri' THEN 1 ELSE 0 END) as polri,
  SUM(CASE WHEN jenis_personel='ASN' THEN 1 ELSE 0 END) as asn
FROM master_pangkat
UNION ALL
SELECT 
  'Master Satfung', 
  COUNT(*), 
  NULL, NULL
FROM master_satuan_fungsi
UNION ALL
SELECT 
  'Master Jabatan', 
  COUNT(*), 
  NULL, NULL
FROM master_jabatan
UNION ALL
SELECT 
  'Master Unsur', 
  COUNT(*), 
  NULL, NULL
FROM master_unsur
UNION ALL
SELECT 
  'Master Unit Pendukung', 
  COUNT(*), 
  NULL, NULL
FROM master_unit_pendukung
UNION ALL
SELECT 
  'Jenis Personel', 
  COUNT(*), 
  NULL, NULL
FROM m_jenis_personel
UNION ALL
SELECT 
  'Tabel Sprin', 
  COUNT(*), 
  NULL, NULL
FROM t_sprin
UNION ALL
SELECT 
  'Tabel Log', 
  COUNT(*), 
  NULL, NULL
FROM t_log;

SELECT '=== INTEGRASI SELESAI ===' as info;
