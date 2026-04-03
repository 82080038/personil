-- ==========================================================
-- PERBAIKAN DATABASE: TAMBAH PANGKAT TAMTAMA POLRI
-- ==========================================================
-- Sesuai Perkap Kepolisian tentang Kepangkatan
-- 6 pangkat Tamtama yang sebelumnya tidak ada
-- ==========================================================

USE db_bagops;

-- Hapus data pangkat lama dan re-insert dengan data lengkap
-- (karena ada perubahan hirarki_level)

-- Backup data personel terlebih dahulu
-- (Perlu migrasi data jika sudah ada personel dengan pangkat lama)

-- 1. DISABLE FOREIGN KEY CHECKS sementara
SET FOREIGN_KEY_CHECKS = 0;

-- 2. KOSONGKAN TABEL PANGKAT (hati-hati jika sudah ada data personel!)
-- Jika sudah ada data personel, HINDARI menjalankan ini langsung
-- dan lakukan migrasi manual

-- Untuk instalasi baru, ini aman:
TRUNCATE TABLE m_pangkat;

-- 3. INSERT DATA PANGKAT LENGKAP (22 pangkat Polri + 9 pangkat ASN)

-- ==========================================================
-- GOLONGAN IV - PERWIRA MENENGAH (PAMEN)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Komisaris Besar Polisi', 'Kombes Pol', 1, 1),           -- Perwira Menengah (Pati junior)
('Ajun Komisaris Besar Polisi', 'AKBP', 2, 1),          -- Sudah ada
('Komisaris Polisi', 'Kompol', 3, 1);                    -- Sudah ada

-- ==========================================================
-- GOLONGAN III - PERWIRA PERTAMA (PAMA)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Ajun Komisaris Polisi', 'AKP', 4, 1),                 -- Sudah ada
('Inspektur Polisi Satu', 'Iptu', 5, 1),                -- Sudah ada
('Inspektur Polisi Dua', 'Ipda', 6, 1);                 -- Sudah ada

-- ==========================================================
-- GOLONGAN II - BINTARA TINGGI
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Ajun Inspektur Polisi Satu', 'Aiptu', 7, 1),           -- Sudah ada
('Ajun Inspektur Polisi Dua', 'Aipda', 8, 1);           -- Sudah ada

-- ==========================================================
-- GOLONGAN II - BINTARA
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Brigadir Polisi Kepala', 'Bripka', 9, 1),              -- Sudah ada
('Brigadir Polisi', 'Brigpol', 10, 1),                  -- Sudah ada
('Brigadir Polisi Satu', 'Briptu', 11, 1),              -- Sudah ada
('Brigadir Polisi Dua', 'Bripda', 12, 1);               -- Sudah ada

-- ==========================================================
-- GOLONGAN I - TAMTAMA (BARU - 6 PANGKAT)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Ajun Brigadir Polisi', 'Abrip', 13, 1),               -- BARU
('Ajun Brigadir Polisi Satu', 'Abriptu', 14, 1),      -- BARU
('Ajun Brigadir Polisi Dua', 'Abripda', 15, 1),       -- BARU
('Bhayangkara Kepala', 'Bharaka', 16, 1),              -- BARU
('Bhayangkara Satu', 'Bharatu', 17, 1),                -- BARU
('Bhayangkara Dua', 'Bharada', 18, 1);                 -- BARU

-- ==========================================================
-- ASN POLRI - GOLONGAN IV (PEMBINA)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Pembina Utama Muda', 'Pembina Tk I', 19, 2),         -- Pembina Tk I
('Pembina', 'Pembina', 20, 2);                          -- Sudah ada

-- ==========================================================
-- ASN POLRI - GOLONGAN III (PENATA)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Penata Tingkat I', 'Penata Tk I', 21, 2),            -- Sudah ada
('Penata', 'Penata', 22, 2);                           -- Sudah ada

-- ==========================================================
-- ASN POLRI - GOLONGAN II (PENATA MUDA)
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Penata Muda Tingkat I', 'Penda Tk I', 23, 2),        -- Sudah ada
('Penata Muda', 'Penda', 24, 2);                       -- Sudah ada

-- ==========================================================
-- ASN POLRI - GOLONGAN I (PENGATUR & JURU) - BARU
-- ==========================================================
INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Pengatur Tingkat I', 'Peng Tk I', 25, 2),            -- BARU
('Pengatur', 'Pengatur', 26, 2),                        -- Sudah ada
('Juru', 'Juru', 27, 2);                                -- BARU

-- 4. ENABLE FOREIGN KEY CHECKS kembali
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================================
-- VERIFIKASI
-- ==========================================================
SELECT 'Total Pangkat' as keterangan, COUNT(*) as jumlah FROM m_pangkat
UNION ALL
SELECT 'Pangkat Polri', COUNT(*) FROM m_pangkat WHERE jenis_id = 1
UNION ALL
SELECT 'Pangkat ASN', COUNT(*) FROM m_pangkat WHERE jenis_id = 2
ORDER BY keterangan;
