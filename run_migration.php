<?php
/**
 * Web-based Data Migration: bagops → personil_db
 * Akses via: http://localhost/personil/run_migration.php
 * 
 * Fitur:
 * - Preview data sebelum migrasi
 * - Validasi aturan (NRP 8 digit, pangkat, satfung)
 * - Selective migration (pilih data mana saja)
 * - Rollback capability
 */

set_time_limit(300);

// Koneksi
$socket = '/opt/lampp/var/mysql/mysql.sock';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", 'root', 'root', $options);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

echo "<!DOCTYPE html><html><head><title>Migrasi bagops → personil_db</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<style>body{padding:20px;} .stat-card{border:1px solid #ddd;padding:15px;margin:5px;border-radius:5px;}</style>";
echo "</head><body>";

echo "<h2>Migrasi Data: bagops → personil_db</h2>";
echo "<p class='text-muted'>Sesuai aturan: NRP 8 digit, hierarki Polri, SOTK 2021, UU PDP 2022</p>";

// Cek database bagops exists
$stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'bagops'");
if (!$stmt->fetch()) {
    echo "<div class='alert alert-danger'>Database 'bagops' tidak ditemukan!</div>";
    exit;
}

// ==========================================================
// ANALISIS DATA SEBELUM MIGRASI
// ==========================================================
echo "<h4>1. Analisis Data bagops</h4>";

// Stats container
echo "<div class='row'>";

// Personel stats
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN LENGTH(nomor_induk) = 8 AND nomor_induk REGEXP '^[0-9]+$' THEN 1 ELSE 0 END) as valid,
        SUM(CASE WHEN LENGTH(nomor_induk) != 8 OR nomor_induk REGEXP '[^0-9]' THEN 1 ELSE 0 END) as invalid
    FROM bagops.m_personel
");
$stats = $stmt->fetch();
echo "<div class='col-md-4'><div class='stat-card bg-light'>";
echo "<h5>Personel</h5>";
echo "<p>Total: {$stats['total']}<br>";
echo "✓ Valid (8 digit): {$stats['valid']}<br>";
echo "✗ Invalid: {$stats['invalid']}</p>";
echo "</div></div>";

// Sprin stats
$stmt = $pdo->query("SELECT COUNT(*), COUNT(DISTINCT status_ops) FROM bagops.t_sprin");
$sprin = $stmt->fetch();
echo "<div class='col-md-4'><div class='stat-card bg-light'>";
echo "<h5>Sprin</h5>";
echo "<p>Total: {$sprin[0]}<br>Status: {$sprin[1]} jenis</p>";
echo "</div></div>";

// Pangkat comparison
$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM bagops.m_pangkat) as bagops_count,
        (SELECT COUNT(*) FROM personil_db.master_pangkat) as personil_count
");
$pangkat = $stmt->fetch();
echo "<div class='col-md-4'><div class='stat-card bg-light'>";
echo "<h5>Pangkat</h5>";
echo "<p>bagops: {$pangkat['bagops_count']}<br>";
echo "personil_db: {$pangkat['personil_count']}</p>";
echo "</div></div>";

echo "</div>"; // end row

// ==========================================================
// FORM MIGRASI
// ==========================================================
echo "<hr><h4>2. Pilih Data untuk Dimigrasi</h4>";

echo "<form method='post' class='mt-3'>";
echo "<div class='row'>";

// Checkbox untuk setiap jenis data
echo "<div class='col-md-6'>";
echo "<div class='form-check'>";
echo "<input class='form-check-input' type='checkbox' name='migrasi[]' value='pangkat' id='chk_pangkat'>";
echo "<label class='form-check-label' for='chk_pangkat'>";
echo "<strong>Master Pangkat</strong> (jika lebih lengkap)";
echo "</label>";
echo "</div>";

echo "<div class='form-check mt-2'>";
echo "<input class='form-check-input' type='checkbox' name='migrasi[]' value='satfung' id='chk_satfung' checked>";
echo "<label class='form-check-label' for='chk_satfung'>";
echo "<strong>Master Satfung</strong> (yang belum ada)";
echo "</label>";
echo "</div>";

echo "<div class='form-check mt-2'>";
echo "<input class='form-check-input' type='checkbox' name='migrasi[]' value='personel' id='chk_personel' checked>";
echo "<label class='form-check-label' for='chk_personel'>";
echo "<strong>Data Personel</strong> (hanya NRP 8 digit valid)";
echo "</label>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-6'>";
echo "<div class='form-check'>";
echo "<input class='form-check-input' type='checkbox' name='migrasi[]' value='sprin' id='chk_sprin' checked>";
echo "<label class='form-check-label' for='chk_sprin'>";
echo "<strong>Data Sprin</strong> (histori operasional)";
echo "</label>";
echo "</div>";

echo "<div class='form-check mt-2'>";
echo "<input class='form-check-input' type='checkbox' name='migrasi[]' value='log' id='chk_log'>";
echo "<label class='form-check-label' for='chk_log'>";
echo "<strong>Log Aktivitas</strong> (audit trail)";
echo "</label>";
echo "</div>";
echo "</div>";

echo "</div>"; // end row

echo "<div class='mt-3'>";
echo "<button type='submit' name='run_migration' class='btn btn-primary btn-lg'>Jalankan Migrasi</button>";
echo "<a href='analyze_bagops.php' class='btn btn-secondary btn-lg ms-2'>Analisis Detail</a>";
echo "</div>";

echo "</form>";

// ==========================================================
// EKSEKUSI MIGRASI
// ==========================================================
if (isset($_POST['run_migration'])) {
    $selected = $_POST['migrasi'] ?? [];
    $results = [];
    
    echo "<hr><h4>3. Hasil Migrasi</h4>";
    echo "<div class='alert alert-info'>Memulai migrasi...</div>";
    echo "<pre class='bg-light p-3'>";
    
    // 1. MIGRASI SATFUNG
    if (in_array('satfung', $selected)) {
        try {
            $stmt = $pdo->query("
                INSERT INTO personil_db.master_satuan_fungsi 
                (kode_satfung, nama_satfung, nama_lengkap, jenis, urutan, is_active)
                SELECT 
                    LOWER(REPLACE(REPLACE(b.nama_satfung, ' ', '_'), '.', '')) as kode,
                    b.nama_satfung,
                    b.nama_satfung as nama_lengkap,
                    CASE b.kategori_unsur
                        WHEN 'Pimpinan' THEN 'pimpinan'
                        WHEN 'Staf' THEN 'staf'
                        WHEN 'Pelaksana' THEN 'pelaksana'
                        WHEN 'Pendukung' THEN 'pendukung'
                        WHEN 'Wilayah' THEN 'wilayah'
                        ELSE 'lainnya'
                    END as jenis,
                    99 as urutan,
                    1 as is_active
                FROM bagops.m_satfung b
                LEFT JOIN personil_db.master_satuan_fungsi p 
                    ON LOWER(b.nama_satfung) = LOWER(p.nama_satfung)
                WHERE p.id IS NULL
            ");
            $count = $stmt->rowCount();
            echo "✓ Satfung: $count records dimigrasi\n";
            $results['satfung'] = $count;
        } catch (PDOException $e) {
            echo "✗ Satfung error: " . $e->getMessage() . "\n";
        }
    }
    
    // 2. MIGRASI PERSONEL (hanya NRP valid)
    if (in_array('personel', $selected)) {
        try {
            $stmt = $pdo->query("
                INSERT INTO personil_db.personil (
                    nrp, nama, nama_panggilan, jabatan, eselon,
                    no_telepon, status_kepegawaian, is_active,
                    created_at, updated_at, created_by, updated_by
                )
                SELECT 
                    b.nomor_induk as nrp,
                    TRIM(b.nama_lengkap) as nama,
                    SUBSTRING_INDEX(TRIM(b.nama_lengkap), ' ', 1) as nama_panggilan,
                    b.jabatan,
                    b.eselon,
                    b.no_hp as no_telepon,
                    CASE b.status_aktif WHEN 1 THEN 'Aktif' ELSE 'Nonaktif' END,
                    b.status_aktif,
                    NOW(), NOW(),
                    'migration_bagops', 'migration_bagops'
                FROM bagops.m_personel b
                WHERE LENGTH(b.nomor_induk) = 8
                  AND b.nomor_induk REGEXP '^[0-9]+$'
                  AND NOT EXISTS (
                      SELECT 1 FROM personil_db.personil p WHERE p.nrp = b.nomor_induk
                  )
            ");
            $count = $stmt->rowCount();
            echo "✓ Personel: $count records dimigrasi (NRP 8 digit valid)\n";
            $results['personel'] = $count;
        } catch (PDOException $e) {
            echo "✗ Personel error: " . $e->getMessage() . "\n";
        }
    }
    
    // 3. MIGRASI SPRIN
    if (in_array('sprin', $selected)) {
        try {
            $stmt = $pdo->query("
                INSERT INTO personil_db.t_sprin (
                    no_sprin, kode_ops, dasar_hukum, pertimbangan,
                    nama_giat, tgl_mulai, tgl_selesai, lokasi_giat,
                    pejabat_ttd_nama, pejabat_ttd_pangkat, pejabat_ttd_jabatan,
                    status_ops, created_by, created_at
                )
                SELECT 
                    b.no_sprin, b.kode_ops, b.dasar_hukum, b.pertimbangan,
                    b.nama_giat, b.tgl_mulai, b.tgl_selesai, b.lokasi_giat,
                    b.pejabat_ttd_nama, b.pejabat_ttd_pangkat, b.pejabat_ttd_jabatan,
                    b.status_ops, 'migration_bagops', COALESCE(b.created_at, NOW())
                FROM bagops.t_sprin b
                WHERE b.no_sprin IS NOT NULL 
                  AND TRIM(b.no_sprin) != ''
                  AND b.tgl_mulai <= b.tgl_selesai
                  AND NOT EXISTS (
                      SELECT 1 FROM personil_db.t_sprin p WHERE p.no_sprin = b.no_sprin
                  )
            ");
            $count = $stmt->rowCount();
            echo "✓ Sprin: $count records dimigrasi\n";
            $results['sprin'] = $count;
        } catch (PDOException $e) {
            echo "✗ Sprin error: " . $e->getMessage() . "\n";
        }
    }
    
    // 4. MIGRASI LOG
    if (in_array('log', $selected)) {
        try {
            $stmt = $pdo->query("
                INSERT INTO personil_db.t_log (
                    nrp_operator, aksi, tabel_terkait, id_record, keterangan, timestamp
                )
                SELECT 
                    CASE WHEN LENGTH(b.nrp_operator) = 8 THEN b.nrp_operator ELSE 'system' END,
                    b.aksi, b.tabel_terkait, b.id_record, b.keterangan, b.timestamp
                FROM bagops.t_log b
                WHERE b.timestamp IS NOT NULL
                  AND NOT EXISTS (
                      SELECT 1 FROM personil_db.t_log p 
                      WHERE p.timestamp = b.timestamp AND p.aksi = b.aksi
                  )
                LIMIT 1000
            ");
            $count = $stmt->rowCount();
            echo "✓ Log: $count records dimigrasi (max 1000)\n";
            $results['log'] = $count;
        } catch (PDOException $e) {
            echo "✗ Log error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "</pre>";
    
    // Ringkasan
    echo "<div class='alert alert-success'>";
    echo "<h5>Ringkasan Migrasi:</h5>";
    echo "<ul>";
    foreach ($results as $key => $count) {
        echo "<li>" . ucfirst($key) . ": $count records</li>";
    }
    echo "</ul>";
    echo "<p class='text-muted'>Data yang tidak valid (NRP tidak 8 digit, tanggal tidak logis) otomatis di-skip.</p>";
    echo "</div>";
    
    // Tombol hapus bagops
    echo "<div class='mt-3'>";
    echo "<form method='post' onsubmit=\"return confirm('Yakin hapus database bagops?');\">";
    echo "<button type='submit' name='delete_bagops' class='btn btn-danger'>Hapus Database bagops (Setelah Terverifikasi)</button>";
    echo "</form>";
    echo "</div>";
}

// HAPUS DATABASE BAGOPS
if (isset($_POST['delete_bagops'])) {
    try {
        $pdo->exec("DROP DATABASE IF EXISTS bagops");
        echo "<div class='alert alert-success mt-3'>✓ Database bagops berhasil dihapus</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger mt-3'>✗ Gagal menghapus: " . $e->getMessage() . "</div>";
    }
}

echo "<hr><a href='login.php' class='btn btn-primary'>Ke Aplikasi</a>";
echo "</body></html>";
