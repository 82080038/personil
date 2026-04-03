-- ==========================================================
-- MIGRASI DATA DARI bagops KE personil_db
-- Sesuai Aturan:
--   - NRP 8 digit PERKAP
--   - Pangkat hierarki Polri
--   - Satfung SOTK 2021
--   - UU PDP 2022 (data pribadi)
-- ==========================================================

-- ==========================================================
-- 1. MIGRASI MASTER PANGKAT
-- Ambil dari bagops hanya jika lebih lengkap
-- ==========================================================

-- Cek perbandingan pangkat
SELECT 'bagops' as sumber, COUNT(*) as jumlah FROM bagops.m_pangkat
UNION ALL
SELECT 'personil_db', COUNT(*) FROM personil_db.master_pangkat;

-- Jika bagops lebih lengkap, migrasi data pangkat
-- (Tetap gunakan struktur master_pangkat yang sudah diupdate dengan 27 pangkat)

-- ==========================================================
-- 2. MIGRASI MASTER SATFUNG
-- Tambahkan satfung dari bagops yang belum ada di personil_db
-- ==========================================================

-- Cek satfung yang ada di bagops tapi tidak ada di personil_db
SELECT b.nama_satfung, b.kategori_unsur
FROM bagops.m_satfung b
LEFT JOIN personil_db.master_satuan_fungsi p 
    ON LOWER(b.nama_satfung) = LOWER(p.nama_satfung)
    OR LOWER(b.nama_satfung) LIKE CONCAT('%', LOWER(p.nama_satfung), '%')
    OR LOWER(p.nama_satfung) LIKE CONCAT('%', LOWER(b.nama_satfung), '%')
WHERE p.id IS NULL;

-- Insert satfung yang belum ada
INSERT INTO personil_db.master_satuan_fungsi (kode_satfung, nama_satfung, nama_lengkap, jenis, urutan, is_active)
SELECT 
    LOWER(REPLACE(REPLACE(b.nama_satfung, ' ', '_'), '.', '')),
    b.nama_satfung,
    b.nama_satfung,
    CASE b.kategori_unsur
        WHEN 'Pimpinan' THEN 'pimpinan'
        WHEN 'Staf' THEN 'staf'
        WHEN 'Pelaksana' THEN 'pelaksana'
        WHEN 'Pendukung' THEN 'pendukung'
        WHEN 'Wilayah' THEN 'wilayah'
        ELSE 'lainnya'
    END,
    99,
    1
FROM bagops.m_satfung b
LEFT JOIN personil_db.master_satuan_fungsi p 
    ON LOWER(b.nama_satfung) = LOWER(p.nama_satfung)
WHERE p.id IS NULL;

-- ==========================================================
-- 3. MIGRASI DATA PERSONEL
-- Hanya yang NRP valid (8 digit) dan sesuai format PERKAP
-- ==========================================================

-- Cek data personel di bagops
SELECT 
    COUNT(*) as total_personel,
    SUM(CASE WHEN LENGTH(nomor_induk) = 8 THEN 1 ELSE 0 END) as nrp_valid_8_digit,
    SUM(CASE WHEN LENGTH(nomor_induk) != 8 THEN 1 ELSE 0 END) as nrp_tidak_valid
FROM bagops.m_personel;

-- Migrasi personel dengan NRP 8 digit dan valid
-- Mapping kolom: bagops.m_personel -> personil_db.personil
INSERT INTO personil_db.personil (
    nrp,
    nama,
    nama_panggilan,
    id_pangkat,
    id_jabatan,
    id_satuan_fungsi,
    jabatan,
    eselon,
    tempat_lahir,
    tanggal_lahir,
    jenis_kelamin,
    alamat,
    no_telepon,
    email,
    status_kepegawaian,
    is_active,
    created_at,
    updated_at,
    created_by,
    updated_by,
    -- Data sensitif di-mask sesuai UU PDP 2022
    no_ktp,
    no_npwp
)
SELECT 
    -- NRP: harus 8 digit
    CASE 
        WHEN LENGTH(b.nomor_induk) = 8 THEN b.nomor_induk
        ELSE NULL  -- Skip data tidak valid
    END,
    
    -- Nama: bersihkan dari karakter aneh
    TRIM(REGEXP_REPLACE(b.nama_lengkap, '[^a-zA-Z0-9 \.\,\-]', '')),
    
    -- Nama panggilan: ambil kata pertama dari nama lengkap
    SUBSTRING_INDEX(TRIM(b.nama_lengkap), ' ', 1),
    
    -- Mapping pangkat
    (SELECT id FROM personil_db.master_pangkat mp 
     WHERE mp.nama_pangkat = (SELECT nama_pangkat FROM bagops.m_pangkat WHERE id_pangkat = b.id_pangkat)
     OR mp.kode_pangkat = (SELECT singkatan FROM bagops.m_pangkat WHERE id_pangkat = b.id_pangkat)
     LIMIT 1),
    
    -- Jabatan: default null (perlu mapping manual)
    NULL,
    
    -- Mapping satfung
    (SELECT id FROM personil_db.master_satuan_fungsi msf 
     WHERE LOWER(msf.nama_satfung) LIKE CONCAT('%', LOWER((SELECT nama_satfung FROM bagops.m_satfung WHERE id_satfung = b.id_satfung)), '%')
     OR LOWER((SELECT nama_satfung FROM bagops.m_satfung WHERE id_satfung = b.id_satfung)) LIKE CONCAT('%', LOWER(msf.nama_satfung), '%')
     LIMIT 1),
    
    -- Jabatan string
    b.jabatan,
    
    -- Eselon
    b.eselon,
    
    -- Data pribadi (minimal sesuai UU PDP)
    NULL,  -- tempat_lahir (tidak ada di bagops)
    NULL,  -- tanggal_lahir (tidak ada di bagops)
    'L',   -- default L (perlu update manual)
    NULL,  -- alamat (tidak ada di bagops)
    b.no_hp,
    NULL,  -- email (tidak ada di bagops)
    
    -- Status
    CASE b.status_aktif 
        WHEN 1 THEN 'Aktif'
        ELSE 'Nonaktif'
    END,
    b.status_aktif,
    
    -- Metadata
    COALESCE(b.created_at, NOW()),
    COALESCE(b.updated_at, NOW()),
    'migration_from_bagops',
    'migration_from_bagops',
    
    -- Data sensitif: NULL (tidak ada di bagops, perlu input manual)
    NULL,  -- no_ktp
    NULL   -- no_npwp
FROM bagops.m_personel b
WHERE 
    -- Filter: hanya NRP 8 digit
    LENGTH(b.nomor_induk) = 8
    -- Filter: hanya karakter numeric
    AND b.nomor_induk REGEXP '^[0-9]+$'
    -- Filter: tidak duplikat
    AND NOT EXISTS (
        SELECT 1 FROM personil_db.personil p 
        WHERE p.nrp = b.nomor_induk
    );

-- ==========================================================
-- 4. MIGRASI DATA SPRIN (Histori Operasional)
-- ==========================================================

-- Cek data Sprin di bagops
SELECT 
    COUNT(*) as total_sprin,
    COUNT(DISTINCT id_sprin) as unique_sprin,
    MIN(tgl_mulai) as tanggal_terlama,
    MAX(tgl_mulai) as tanggal_terbaru,
    GROUP_CONCAT(DISTINCT status_ops) as status_list
FROM bagops.t_sprin;

-- Migrasi data Sprin
INSERT INTO personil_db.t_sprin (
    no_sprin,
    kode_ops,
    dasar_hukum,
    pertimbangan,
    nama_giat,
    tgl_mulai,
    tgl_selesai,
    lokasi_giat,
    pejabat_ttd_nama,
    pejabat_ttd_pangkat,
    pejabat_ttd_jabatan,
    pejabat_ttd_nrp,
    status_ops,
    tgl_cetak_sprin,
    created_by,
    created_at,
    updated_at
)
SELECT 
    b.no_sprin,
    b.kode_ops,
    b.dasar_hukum,
    b.pertimbangan,
    b.nama_giat,
    b.tgl_mulai,
    b.tgl_selesai,
    b.lokasi_giat,
    b.pejabat_ttd_nama,
    b.pejabat_ttd_pangkat,
    b.pejabat_ttd_jabatan,
    -- Validasi NRP penandatangan
    CASE 
        WHEN LENGTH(b.pejabat_ttd_nrp) = 8 THEN b.pejabat_ttd_nrp
        ELSE NULL
    END,
    b.status_ops,
    b.tgl_cetak_sprin,
    'migration_from_bagops',
    COALESCE(b.created_at, NOW()),
    COALESCE(b.updated_at, b.created_at, NOW())
FROM bagops.t_sprin b
WHERE 
    -- Validasi: nomor Sprin tidak kosong
    b.no_sprin IS NOT NULL 
    AND TRIM(b.no_sprin) != ''
    -- Validasi: tanggal logis
    AND b.tgl_mulai <= b.tgl_selesai
    -- Validasi: tidak duplikat
    AND NOT EXISTS (
        SELECT 1 FROM personil_db.t_sprin p 
        WHERE p.no_sprin = b.no_sprin
    );

-- ==========================================================
-- 5. MIGRASI DETAIL PERSONEL DALAM SPRIN
-- ==========================================================

-- Migrasi detail anggota Sprin
-- Hanya yang personelnya sudah ada di personil_db
INSERT INTO personil_db.t_sprin_detail (
    id_sprin,
    id_personel,
    peran_tugas,
    keterangan
)
SELECT 
    (SELECT id_sprin FROM personil_db.t_sprin WHERE no_sprin = (SELECT no_sprin FROM bagops.t_sprin WHERE id_sprin = d.id_sprin)),
    (SELECT id FROM personil_db.personil WHERE nrp = (SELECT nomor_induk FROM bagops.m_personel WHERE id_personel = d.id_personel)),
    d.peran_tugas,
    d.keterangan
FROM bagops.t_sprin_detail d
WHERE 
    -- Pastikan Sprin ada di personil_db
    EXISTS (
        SELECT 1 FROM personil_db.t_sprin s 
        WHERE s.no_sprin = (SELECT no_sprin FROM bagops.t_sprin WHERE id_sprin = d.id_sprin)
    )
    -- Pastikan personel ada di personil_db
    AND EXISTS (
        SELECT 1 FROM personil_db.personil p 
        WHERE p.nrp = (SELECT nomor_induk FROM bagops.m_personel WHERE id_personel = d.id_personel)
    )
    -- Hindari duplikat
    AND NOT EXISTS (
        SELECT 1 FROM personil_db.t_sprin_detail pd
        WHERE pd.id_sprin = (SELECT id_sprin FROM personil_db.t_sprin WHERE no_sprin = (SELECT no_sprin FROM bagops.t_sprin WHERE id_sprin = d.id_sprin))
        AND pd.id_personel = (SELECT id FROM personil_db.personil WHERE nrp = (SELECT nomor_induk FROM bagops.m_personel WHERE id_personel = d.id_personel))
    );

-- ==========================================================
-- 6. MIGRASI LOG AKTIVITAS
-- ==========================================================

INSERT INTO personil_db.t_log (
    nrp_operator,
    aksi,
    tabel_terkait,
    id_record,
    keterangan,
    timestamp
)
SELECT 
    -- Validasi NRP operator
    CASE 
        WHEN LENGTH(b.nrp_operator) = 8 THEN b.nrp_operator
        ELSE 'system'
    END,
    b.aksi,
    b.tabel_terkait,
    b.id_record,
    b.keterangan,
    b.timestamp
FROM bagops.t_log b
WHERE 
    -- Filter: timestamp valid
    b.timestamp IS NOT NULL
    -- Hindari duplikat (berdasarkan timestamp dan aksi)
    AND NOT EXISTS (
        SELECT 1 FROM personil_db.t_log p 
        WHERE p.timestamp = b.timestamp 
        AND p.aksi = b.aksi
        AND p.nrp_operator = CASE WHEN LENGTH(b.nrp_operator) = 8 THEN b.nrp_operator ELSE 'system' END
    );

-- ==========================================================
-- VERIFIKASI HASIL MIGRASI
-- ==========================================================
SELECT '=== VERIFIKASI MIGRASI ===' as info;

-- Personel
SELECT 
    'Personel dimigrasi' as kategori,
    COUNT(*) as jumlah
FROM personil_db.personil 
WHERE created_by = 'migration_from_bagops';

-- Sprin
SELECT 
    'Sprin dimigrasi' as kategori,
    COUNT(*) as jumlah
FROM personil_db.t_sprin 
WHERE created_by = 'migration_from_bagops';

-- Detail Sprin
SELECT 
    'Detail Sprin dimigrasi' as kategori,
    COUNT(*) as jumlah
FROM personil_db.t_sprin_detail sd
JOIN personil_db.t_sprin s ON sd.id_sprin = s.id_sprin
WHERE s.created_by = 'migration_from_bagops';

-- Log
SELECT 
    'Log dimigrasi' as kategori,
    COUNT(*) as jumlah
FROM personil_db.t_log;

-- ==========================================================
-- LAPORAN DATA YANG TIDAK VALID (TIDAK DIMIGRASI)
-- ==========================================================
SELECT '=== DATA TIDAK VALID (SKIP MIGRASI) ===' as info;

-- Personel dengan NRP tidak valid
SELECT 
    'Personel NRP tidak valid' as kategori,
    COUNT(*) as jumlah,
    GROUP_CONCAT(DISTINCT LENGTH(nomor_induk)) as panjang_nrp
FROM bagops.m_personel
WHERE LENGTH(nomor_induk) != 8
   OR nomor_induk REGEXP '[^0-9]';

-- Sprin dengan tanggal tidak logis
SELECT 
    'Sprin tanggal tidak logis' as kategori,
    COUNT(*) as jumlah
FROM bagops.t_sprin
WHERE tgl_mulai > tgl_selesai;

-- ==========================================================
-- SELESAI
-- ==========================================================
SELECT 'Migrasi selesai! Periksa data hasil migrasi.' as pesan;
