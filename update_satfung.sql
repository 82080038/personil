-- ==========================================================
-- PERBAIKAN DATABASE: UPDATE SATUAN FUNGSI (SATFUNG)
-- ==========================================================
-- Sesuai Perpol No. 2 Tahun 2021 & Perpol No. 7 Tahun 2025
-- ==========================================================

USE db_bagops;

-- ==========================================================
-- 1. UPDATE NAMA SATFUNG YANG SALAH
-- ==========================================================

-- "Reskrim" → "Satreskrim"
UPDATE m_satfung SET 
    nama_satfung = 'Satuan Reserse Kriminal (Satreskrim)',
    kategori_unsur = 'Pelaksana'
WHERE nama_satfung LIKE '%Reskrim%';

-- "Dokkes" → "Sidokkes"
UPDATE m_satfung SET 
    nama_satfung = 'Seksi Dokumen dan Kesehatan (Sidokkes)',
    kategori_unsur = 'Pendukung'
WHERE nama_satfung LIKE '%Dokkes%';

-- "Perencanaan" → "Bagian Perencanaan dan Pengamanan Masyarakat (Bagren/Bag Renmin)"
UPDATE m_satfung SET 
    nama_satfung = 'Bagian Perencanaan dan Pengamanan (Bag Renmin)',
    kategori_unsur = 'Staf'
WHERE nama_satfung LIKE '%Perencanaan%';

-- ==========================================================
-- 2. TAMBAH SATFUNG YANG BELUM ADA
-- ==========================================================

-- Bagian Keuangan
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Bagian Keuangan (Bidkeu)', 'Staf');

-- Bagian Protap
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Bagian Protap (Bag Protap)', 'Staf');

-- Satres PPA (Perpol No. 7 Tahun 2025 - BARU)
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Satuan Reserse Perlindungan Perempuan dan Anak (Satres PPA)', 'Pelaksana');

-- Satres PPO (Perpol No. 7 Tahun 2025 - BARU)
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Satuan Reserse Perampasan dan Penipuan (Satres PPO)', 'Pelaksana');

-- Itwasda
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Inspektorat Pengawasan Daerah (Itwasda)', 'Pendukung');

-- Itwasum
INSERT IGNORE INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Inspektorat Pengawasan Umum (Itwasum)', 'Pendukung');

-- ==========================================================
-- 3. UPDATE KATEGORI JIKA DIPERLUKAN
-- ==========================================================

-- Pastikan semua satuan pelaksana sudah benar kategorinya
UPDATE m_satfung SET kategori_unsur = 'Pelaksana' 
WHERE nama_satfung LIKE 'Satuan%' OR nama_satfung LIKE 'Satres%';

-- Pastikan semua seksi/bagian sudah benar kategorinya
UPDATE m_satfung SET kategori_unsur = 'Staf' 
WHERE nama_satfung LIKE 'Bagian%';

UPDATE m_satfung SET kategori_unsur = 'Pendukung' 
WHERE nama_satfung LIKE 'Seksi%' OR nama_satfung LIKE 'Inspektorat%';

-- ==========================================================
-- VERIFIKASI HASIL
-- ==========================================================
SELECT kategori_unsur, COUNT(*) as jumlah, 
       GROUP_CONCAT(nama_satfung SEPARATOR ', ') as daftar
FROM m_satfung 
GROUP BY kategori_unsur
ORDER BY FIELD(kategori_unsur, 'Pimpinan', 'Staf', 'Pelaksana', 'Pendukung', 'Wilayah');

-- Total semua
SELECT 'Total Satfung' as keterangan, COUNT(*) as jumlah FROM m_satfung;
