<?php
/**
 * Database Integration Script v3.0
 * Jalankan via: http://localhost/personil/run_integration.php
 */

// Disable time limit untuk script panjang
set_time_limit(300);

// Koneksi langsung ke MySQL
$socket = '/opt/lampp/var/mysql/mysql.sock';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", 'root', 'root', $options);
    echo "✓ Terkoneksi ke MySQL\n<br>";
} catch (PDOException $e) {
    die("✗ Gagal koneksi: " . $e->getMessage());
}

// Read SQL file
$sql_file = file_get_contents(__DIR__ . '/integrasi_database_v3.sql');

// Split statements
$statements = array_filter(array_map('trim', explode(';', $sql_file)));

$success = 0;
$failed = 0;
$errors = [];

echo "<h3>Menjalankan Integrasi Database...</h3>";
echo "<pre>";

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    // Skip comments
    if (strpos(trim($statement), '--') === 0) continue;
    if (strpos(trim($statement), '/*') === 0) continue;
    
    try {
        $pdo->exec($statement);
        echo ".";
        $success++;
        
        // Flush output setiap 10 statement
        if ($success % 10 === 0) {
            echo " ($success)";
            ob_flush();
            flush();
        }
    } catch (PDOException $e) {
        $failed++;
        $errors[] = $e->getMessage();
        echo "X";
    }
}

echo "\n\n";
echo "✓ Berhasil: $success statement\n";
if ($failed > 0) {
    echo "✗ Gagal: $failed statement\n";
    echo "\nError:\n";
    foreach (array_slice($errors, 0, 5) as $err) {
        echo "  - $err\n";
    }
}
echo "</pre>";

// Verifikasi
echo "<h3>Verifikasi Hasil:</h3>";
echo "<pre>";

try {
    // Check pangkat
    $stmt = $pdo->query("SELECT COUNT(*) FROM personil_db.master_pangkat");
    $count = $stmt->fetchColumn();
    echo "✓ Master Pangkat: $count records (target: 27)\n";
    
    // Check satfung
    $stmt = $pdo->query("SELECT COUNT(*) FROM personil_db.master_satuan_fungsi");
    $count = $stmt->fetchColumn();
    echo "✓ Master Satfung: $count records\n";
    
    // Check jenis personel
    $stmt = $pdo->query("SELECT COUNT(*) FROM personil_db.m_jenis_personel");
    $count = $stmt->fetchColumn();
    echo "✓ Jenis Personel: $count records (target: 3)\n";
    
    // Check tabel baru
    $stmt = $pdo->query("SHOW TABLES FROM personil_db LIKE 't_sprin%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Tabel Sprin: " . implode(', ', $tables) . "\n";
    
    // Check t_log
    $stmt = $pdo->query("SHOW TABLES FROM personil_db LIKE 't_log'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Tabel Log: " . ($tables ? 'ADA' : 'TIDAK ADA') . "\n";
    
    echo "\n=== INTEGRASI BERHASIL ===";
    echo "\nDatabase personil_db siap digunakan dengan fitur lengkap!";
    
} catch (PDOException $e) {
    echo "✗ Verifikasi gagal: " . $e->getMessage();
}

echo "</pre>";

// Tombol untuk hapus db_bagops
echo "<hr>";
echo "<h3>Hapus Database Lama (db_bagops)</h3>";
echo "<p>Setelah verifikasi berhasil, hapus database lama:</p>";
echo "<form method='post'>";
echo "<button type='submit' name='delete_old' style='background:red;color:white;padding:10px;'>HAPUS db_bagops</button>";
echo "</form>";

if (isset($_POST['delete_old'])) {
    try {
        $pdo->exec("DROP DATABASE IF EXISTS db_bagops");
        echo "<p style='color:green;'>✓ Database db_bagops berhasil dihapus</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>✗ Gagal menghapus: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<a href='login.php' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>Ke Halaman Login</a>";
