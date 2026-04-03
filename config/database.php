<?php
/**
 * Konfigurasi Database SMO-BAGOPS v3.0 Hybrid
 * Database: personil_db (Integrasi personil_db + db_bagops)
 * Versi: 3.0
 * Update: April 2026 - Integrasi hybrid terbaik
 */

// Keterangan Database:
// - personil_db: Database utama yang telah diintegrasikan dengan fitur terbaik
//   dari db_bagops (modul Sprin, audit log, master data lengkap)
// - 27 pangkat lengkap sesuai regulasi Polri
// - 11 tabel master + 3 tabel transaksi operasional
// - Fitur unggulan: penugasan sementara, riwayat karir, modul Sprin

// XAMPP specific settings
$socket = '/opt/lampp/var/mysql/mysql.sock';

// Coba socket XAMPP dulu, fallback ke TCP
define('DB_HOST', 'localhost');
define('DB_NAME', 'personil_db');
define('DB_USER', 'root');
define('DB_PASS', 'root'); // XAMPP password
define('DB_CHARSET', 'utf8mb4');
define('DB_SOCKET', $socket);

// Opsi PDO untuk keamanan dan performa
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
];

// DSN dengan socket XAMPP
if (file_exists($socket)) {
    $dsn = "mysql:unix_socket=" . DB_SOCKET . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
} else {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
}

try {
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Fallback ke TCP jika socket gagal
    try {
        $dsn = "mysql:host=127.0.0.1;port=3306;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e2) {
        error_log("Database Connection Error: " . $e2->getMessage());
        die(json_encode([
            "status" => "error",
            "message" => "Koneksi database gagal. Silakan hubungi administrator."
        ]));
    }
}

/**
 * Fungsi untuk mendapatkan koneksi database
 * @return PDO
 */
function getDB() {
    global $db;
    return $db;
}
