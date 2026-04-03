<?php
/**
 * Web-based Database Setup
 * Akses via: http://localhost/personil/install.php
 */

// Coba koneksi dengan berbagai cara
$socket = '/opt/lampp/var/mysql/mysql.sock';
$connected = false;
$pdo = null;

// Coba 1: Socket XAMPP
try {
    $pdo = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $connected = true;
} catch (PDOException $e) {
    // Coba 2: TCP localhost
    try {
        $pdo = new PDO("mysql:host=localhost;port=3306;charset=utf8mb4", 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $connected = true;
    } catch (PDOException $e2) {
        // Coba 3: TCP 127.0.0.1
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8mb4", 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $connected = true;
        } catch (PDOException $e3) {
            $error = $e3->getMessage();
        }
    }
}

// HTML Header
echo '<!DOCTYPE html>
<html>
<head>
    <title>SMO-BAGOPS - Database Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; padding: 50px; }
        .setup-box { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .log { background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="setup-box">
        <h2 class="mb-4"><i class="bi bi-database"></i> Database Setup - SMO-BAGOPS</h2>';

if (!$connected) {
    echo '<div class="alert alert-danger">
        <h5>Koneksi MySQL Gagal</h5>
        <p>Error: ' . htmlspecialchars($error) . '</p>
        <hr>
        <p><strong>Solusi:</strong></p>
        <ol>
            <li>Pastikan XAMPP MySQL sudah running (buka XAMPP Control Panel)</li>
            <li>Import file <code>personil.sql</code> via phpMyAdmin:<br>
                <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a>
            </li>
            <li>Buat database manual dengan nama <code>db_bagops</code></li>
            <li>Import file SQL ke dalam database tersebut</li>
        </ol>
    </div>';
    exit;
}

// Koneksi berhasil, lanjutkan setup
echo '<div class="alert alert-success">✓ Terkoneksi ke MySQL</div>';

$log = [];

// 1. Create database
try {
    $pdo->exec("DROP DATABASE IF EXISTS db_bagops");
    $pdo->exec("CREATE DATABASE db_bagops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $log[] = "✓ Database 'db_bagops' created";
} catch (PDOException $e) {
    $log[] = "✗ Gagal create database: " . $e->getMessage();
    showErrorAndExit($log);
}

// 2. Use database
$pdo->exec("USE db_bagops");

// 3. Read SQL file
$sql_file = file_get_contents(__DIR__ . '/personil.sql');

// Remove comments
$sql_file = preg_replace('/--.*$/m', '', $sql_file);
$sql_file = preg_replace('/\/\*.*?\*\//s', '', $sql_file);

// Split and execute
$statements = array_filter(array_map('trim', explode(';', $sql_file)));
$success = 0;
$failed = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    try {
        $pdo->exec($statement);
        $success++;
    } catch (PDOException $e) {
        $failed++;
        $log[] = "✗ SQL Error: " . $e->getMessage();
    }
}

$log[] = "✓ Executed: $success statements";
if ($failed > 0) {
    $log[] = "✗ Failed: $failed statements";
}

// 4. Verify tables
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $log[] = "✓ Tables created: " . count($tables);
    
    // Count master data
    $jenis = $pdo->query("SELECT COUNT(*) FROM m_jenis_personel")->fetchColumn();
    $pangkat = $pdo->query("SELECT COUNT(*) FROM m_pangkat")->fetchColumn();
    $satfung = $pdo->query("SELECT COUNT(*) FROM m_satfung")->fetchColumn();
    
    $log[] = "✓ Master data: $jenis jenis personel, $pangkat pangkat, $satfung satfung";
    
} catch (PDOException $e) {
    $log[] = "✗ Verification error: " . $e->getMessage();
}

// Show log
echo '<h5>Setup Log:</h5>';
echo '<div class="log">' . implode("\n", $log) . '</div>';

// Success message
echo '<div class="alert alert-success mt-4">
    <h5>✅ Setup Berhasil!</h5>
    <p>Database sudah siap digunakan.</p>
    <a href="login.php" class="btn btn-primary">Ke Halaman Login</a>
</div>';

echo '</div></body></html>';

function showErrorAndExit($log) {
    echo '<div class="alert alert-danger"><h5>Setup Gagal</h5></div>';
    echo '<div class="log">' . implode("\n", $log) . '</div>';
    echo '</div></body></html>';
    exit;
}
