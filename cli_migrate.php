<?php
/**
 * CLI Migration Runner
 * Jalankan: cd /opt/lampp/htdocs/personil && sudo /opt/lampp/bin/php cli_migrate.php
 */

require_once 'config/database.php';

echo "=== MIGRASI DATA BAGOPS → PERSONIL_DB ===\n\n";

// 1. CEK DATABASE BAGOPS
try {
    $check = $db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'bagops'");
    if (!$check->fetch()) {
        die("✗ Database 'bagops' tidak ditemukan!\n");
    }
    echo "✓ Database 'bagops' ditemukan\n";
} catch (PDOException $e) {
    die("✗ Error: " . $e->getMessage() . "\n");
}

// 2. STATS SEBELUM MIGRASI
echo "\n--- Stats bagops ---\n";
$stats = $db->query("
    SELECT 'Personel Total' as label, COUNT(*) as val FROM bagops.m_personel
    UNION ALL SELECT 'Personel Valid (NRP 8 digit)', COUNT(*) FROM bagops.m_personel WHERE LENGTH(nomor_induk) = 8 AND nomor_induk REGEXP '^[0-9]+$'
    UNION ALL SELECT 'Sprin', COUNT(*) FROM bagops.t_sprin
    UNION ALL SELECT 'Satfung', COUNT(*) FROM bagops.m_satfung
");
foreach ($stats as $row) {
    echo "  {$row['label']}: {$row['val']}\n";
}

// 3. MIGRASI SATFUNG
echo "\n[1/4] Migrasi Satfung...\n";
try {
    $result = $db->exec("
        INSERT INTO master_satuan_fungsi (kode_satfung, nama_satfung, nama_lengkap, jenis, urutan, is_active)
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
        LEFT JOIN master_satuan_fungsi p ON LOWER(b.nama_satfung) = LOWER(p.nama_satfung)
        WHERE p.id IS NULL
    ");
    echo "  ✓ {$result} satfung dimigrasi\n";
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

// 4. MIGRASI PERSONEL (NRP 8 digit valid)
echo "\n[2/4] Migrasi Personel (NRP 8 digit valid)...\n";
try {
    $result = $db->exec("
        INSERT INTO personil (nrp, nama, nama_panggilan, jabatan, eselon, no_telepon, status_kepegawaian, is_active, created_at, updated_at, created_by, updated_by)
        SELECT 
            b.nomor_induk,
            TRIM(b.nama_lengkap),
            SUBSTRING_INDEX(TRIM(b.nama_lengkap), ' ', 1),
            b.jabatan,
            b.eselon,
            b.no_hp,
            CASE b.status_aktif WHEN 1 THEN 'Aktif' ELSE 'Nonaktif' END,
            b.status_aktif,
            NOW(), NOW(),
            'migration_bagops', 'migration_bagops'
        FROM bagops.m_personel b
        WHERE LENGTH(b.nomor_induk) = 8
          AND b.nomor_induk REGEXP '^[0-9]+$'
          AND NOT EXISTS (SELECT 1 FROM personil p WHERE p.nrp = b.nomor_induk)
    ");
    echo "  ✓ {$result} personel dimigrasi\n";
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

// 5. MIGRASI SPRIN
echo "\n[3/4] Migrasi Sprin...\n";
try {
    $result = $db->exec("
        INSERT INTO t_sprin (no_sprin, kode_ops, dasar_hukum, pertimbangan, nama_giat, tgl_mulai, tgl_selesai, lokasi_giat, pejabat_ttd_nama, pejabat_ttd_pangkat, pejabat_ttd_jabatan, status_ops, created_by, created_at)
        SELECT 
            b.no_sprin, b.kode_ops, b.dasar_hukum, b.pertimbangan,
            b.nama_giat, b.tgl_mulai, b.tgl_selesai, b.lokasi_giat,
            b.pejabat_ttd_nama, b.pejabat_ttd_pangkat, b.pejabat_ttd_jabatan,
            b.status_ops, 'migration_bagops', COALESCE(b.created_at, NOW())
        FROM bagops.t_sprin b
        WHERE b.no_sprin IS NOT NULL AND TRIM(b.no_sprin) != ''
          AND b.tgl_mulai <= b.tgl_selesai
          AND NOT EXISTS (SELECT 1 FROM t_sprin p WHERE p.no_sprin = b.no_sprin)
    ");
    echo "  ✓ {$result} sprin dimigrasi\n";
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

// 6. MIGRASI LOG (max 1000)
echo "\n[4/4] Migrasi Log (max 1000)...\n";
try {
    $result = $db->exec("
        INSERT INTO t_log (nrp_operator, aksi, tabel_terkait, id_record, keterangan, timestamp)
        SELECT 
            CASE WHEN LENGTH(b.nrp_operator) = 8 THEN b.nrp_operator ELSE 'system' END,
            b.aksi, b.tabel_terkait, b.id_record, b.keterangan, b.timestamp
        FROM bagops.t_log b
        WHERE b.timestamp IS NOT NULL
          AND NOT EXISTS (SELECT 1 FROM t_log p WHERE p.timestamp = b.timestamp AND p.aksi = b.aksi)
        LIMIT 1000
    ");
    echo "  ✓ {$result} log dimigrasi\n";
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n";
}

// 7. VERIFIKASI
echo "\n=== VERIFIKASI HASIL ===\n";
$verify = $db->query("
    SELECT 'Personel' as item, COUNT(*) as count FROM personil WHERE created_by LIKE '%migration%'
    UNION ALL SELECT 'Sprin', COUNT(*) FROM t_sprin WHERE created_by LIKE '%migration%'
    UNION ALL SELECT 'Log', COUNT(*) FROM t_log
");
foreach ($verify as $row) {
    echo "  ✓ {$row['item']}: {$row['count']} records\n";
}

echo "\n=== MIGRASI SELESAI ===\n";
echo "\nUntuk menghapus database bagops, jalankan:\n";
echo "  sudo /opt/lampp/bin/mysql -u root -proot -e 'DROP DATABASE bagops'\n";
echo "\nAkses aplikasi: http://localhost/personil/login.php\n";
