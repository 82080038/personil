<?php
/**
 * Database Setup Script
 * Run this to create database and import schema
 * Usage: cd /opt/lampp/htdocs/personil && /opt/lampp/bin/php setup_database.php
 */

echo "=== SMO-BAGOPS Database Setup ===\n\n";

// Koneksi ke MySQL tanpa memilih database (untuk create DB)
$socket = '/opt/lampp/var/mysql/mysql.sock';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Coba koneksi dengan socket XAMPP
$dsn = "mysql:unix_socket=$socket;charset=utf8mb4";
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✓ Connected to MySQL via socket\n";
} catch (PDOException $e) {
    // Coba via TCP
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8mb4", $user, $pass, $options);
        echo "✓ Connected to MySQL via TCP\n";
    } catch (PDOException $e2) {
        die("✗ Failed to connect: " . $e2->getMessage() . "\n");
    }
}

// Create database
try {
    $pdo->exec("DROP DATABASE IF EXISTS db_bagops");
    $pdo->exec("CREATE DATABASE db_bagops CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database 'db_bagops' created\n";
} catch (PDOException $e) {
    die("✗ Failed to create database: " . $e->getMessage() . "\n");
}

// Connect to the new database
try {
    $pdo->exec("USE db_bagops");
} catch (PDOException $e) {
    die("✗ Failed to select database: " . $e->getMessage() . "\n");
}

// Read and execute SQL file
$sql_file = file_get_contents(__DIR__ . '/personil.sql');

// Remove comments and split into statements
$sql_file = preg_replace('/--.*$/m', '', $sql_file);
$sql_file = preg_replace('/\/\*.*?\*\//s', '', $sql_file);

$statements = array_filter(array_map('trim', explode(';', $sql_file)));

$success = 0;
$failed = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    try {
        $pdo->exec($statement);
        $success++;
        echo ".";
    } catch (PDOException $e) {
        $failed++;
        echo "X";
    }
}

echo "\n\n=== Setup Complete ===\n";
echo "✓ Success: $success statements\n";
if ($failed > 0) {
    echo "✗ Failed: $failed statements\n";
}

// Verify tables
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n✓ Tables created (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "   - $table\n";
    }
    
    // Verify master data
    echo "\n✓ Master data inserted:\n";
    
    $jenis = $pdo->query("SELECT COUNT(*) FROM m_jenis_personel")->fetchColumn();
    echo "   - m_jenis_personel: $jenis records\n";
    
    $pangkat = $pdo->query("SELECT COUNT(*) FROM m_pangkat")->fetchColumn();
    echo "   - m_pangkat: $pangkat records\n";
    
    $satfung = $pdo->query("SELECT COUNT(*) FROM m_satfung")->fetchColumn();
    echo "   - m_satfung: $satfung records\n";
    
    echo "\n✅ Database siap digunakan!\n";
    echo "\nSilakan login dengan:\n";
    echo "   URL: http://localhost/personil/login.php\n";
    echo "   NRP: (masukkan NRP apapun)\n";
    echo "   Password: sama dengan NRP\n";
    
} catch (PDOException $e) {
    echo "\n✗ Failed to verify: " . $e->getMessage() . "\n";
}
