<?php
/**
 * Database Analyzer for bagops
 * Analisis struktur dan data database bagops
 * Akses via: http://localhost/personil/analyze_bagops.php
 */

// Koneksi ke MySQL
$socket = '/opt/lampp/var/mysql/mysql.sock';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", 'root', 'root', $options);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

echo "<!DOCTYPE html><html><head><title>Analisis Database bagops</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<style>body{padding:20px;} .table th{background:#343a40;color:white;}</style>";
echo "</head><body>";

echo "<h2>Analisis Database: bagops</h2>";

// 1. CEK DATABASE ADA
$stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'bagops'");
$db_exists = $stmt->fetch();

if (!$db_exists) {
    echo "<div class='alert alert-danger'>Database 'bagops' tidak ditemukan!</div>";
    
    // Coba cari database lain yang mirip
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%bag%' OR SCHEMA_NAME LIKE '%ops%' OR SCHEMA_NAME LIKE '%person%'");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if ($databases) {
        echo "<div class='alert alert-info'>Database yang tersedia (yang mirip):<br>";
        echo implode(', ', $databases);
        echo "</div>";
    }
    exit;
}

echo "<div class='alert alert-success'>✓ Database 'bagops' ditemukan</div>";

// 2. ANALISIS TABEL
$pdo->exec("USE bagops");

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "<h4>Struktur Database</h4>";
echo "<table class='table table-bordered'>";
echo "<tr><th>No</th><th>Nama Tabel</th><th>Jumlah Record</th><th>Engine</th><th>Kolom</th></tr>";

foreach ($tables as $i => $table) {
    // Hitung records
    $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
    $count = $stmt->fetchColumn();
    
    // Info tabel
    $stmt = $pdo->query("SELECT ENGINE FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'bagops' AND TABLE_NAME = '$table'");
    $engine = $stmt->fetchColumn() ?? '-';
    
    // Hitung kolom
    $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bagops' AND TABLE_NAME = '$table'");
    $columns = $stmt->fetchColumn();
    
    echo "<tr>";
    echo "<td>" . ($i + 1) . "</td>";
    echo "<td><strong>$table</strong></td>";
    echo "<td>$count</td>";
    echo "<td>$engine</td>";
    echo "<td>$columns kolom</td>";
    echo "</tr>";
}
echo "</table>";

// 3. ANALISIS DETAIL PER TABEL
foreach ($tables as $table) {
    echo "<h5>Struktur: $table</h5>";
    
    $stmt = $pdo->query("DESCRIBE `$table`");
    $columns = $stmt->fetchAll();
    
    echo "<table class='table table-sm table-bordered'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Sample data (maks 3 baris)
    $stmt = $pdo->query("SELECT * FROM `$table` LIMIT 3");
    $data = $stmt->fetchAll();
    
    if ($data) {
        echo "<p><strong>Sample Data (3 baris):</strong></p>";
        echo "<pre class='bg-light p-2'>";
        print_r($data);
        echo "</pre>";
    }
}

// 4. ANALISIS PERBANDINGAN DENGAN personil_db
echo "<hr><h4>Analisis Perbandingan dengan personil_db</h4>";

$comparison = [];

foreach ($tables as $table) {
    // Cek apakah tabel serupa ada di personil_db
    $similar_table = null;
    
    // Mapping nama tabel yang serupa
    $mappings = [
        'm_personel' => 'personil',
        'm_pangkat' => 'master_pangkat',
        'm_satfung' => 'master_satuan_fungsi',
        'm_jenis_personel' => null,
        't_sprin' => null,
        't_sprin_detail' => null,
        't_log' => null
    ];
    
    if (isset($mappings[$table])) {
        $similar_table = $mappings[$table];
    }
    
    if ($similar_table) {
        // Cek apakah tabel ada di personil_db
        $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'personil_db' AND TABLE_NAME = '$similar_table'");
        $exists_in_personil = $stmt->fetchColumn();
        
        if ($exists_in_personil) {
            $comparison[] = [
                'bagops' => $table,
                'personil_db' => $similar_table,
                'status' => '✓ Sudah ada di personil_db'
            ];
        } else {
            $comparison[] = [
                'bagops' => $table,
                'personil_db' => $similar_table,
                'status' => '⚠️ Perlu ditambahkan'
            ];
        }
    } else {
        $comparison[] = [
            'bagops' => $table,
            'personil_db' => '-',
            'status' => 'ℹ️ Tabel khusus bagops'
        ];
    }
}

echo "<table class='table table-bordered'>";
echo "<tr><th>bagops</th><th>personil_db</th><th>Status</th></tr>";
foreach ($comparison as $comp) {
    echo "<tr>";
    echo "<td>{$comp['bagops']}</td>";
    echo "<td>{$comp['personil_db']}</td>";
    echo "<td>{$comp['status']}</td>";
    echo "</tr>";
}
echo "</table>";

// 5. REKOMENDASI MIGRASI
echo "<hr><h4>Rekomendasi Data untuk Dimigrasi</h4>";
echo "<div class='alert alert-info'>";
echo "<p><strong>Data yang bisa diambil dari bagops (sesuai aturan):</strong></p>";
echo "<ul>";
echo "<li>✓ Master Pangkat - jika lebih lengkap</li>";
echo "<li>✓ Master Satfung - jika ada yang belum ada</li>";
echo "<li>✓ Data Personel - jika NRP valid (8 digit PERKAP)</li>";
echo "<li>✓ Data Sprin - untuk histori operasional</li>";
echo "<li>✓ Log Aktivitas - untuk audit trail</li>";
echo "</ul>";

echo "<p><strong>Aturan yang harus dipatuhi:</strong></p>";
echo "<ul>";
echo "<li>NRP harus 8 digit sesuai Perkap</li>";
echo "<li>Pangkat harus sesuai hierarki Polri</li>";
echo "<li>Satfung harus sesuai SOTK 2021</li>";
echo "<li>Data pribadi harus sesuai UU PDP 2022</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
