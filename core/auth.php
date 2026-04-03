<?php
/**
 * Autentikasi & Session Management SMO-BAGOPS
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

session_name(SESSION_NAME);
session_start();

/**
 * Cek apakah user sudah login
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['logged_in']) && 
           $_SESSION['logged_in'] === true &&
           !isSessionExpired();
}

/**
 * Cek session timeout
 */
function isSessionExpired() {
    if (!isset($_SESSION['last_activity'])) {
        return true;
    }
    
    $inactive = time() - $_SESSION['last_activity'];
    
    if ($inactive > SESSION_TIMEOUT) {
        logout();
        return true;
    }
    
    return false;
}

/**
 * Update last activity timestamp
 */
function updateActivity() {
    $_SESSION['last_activity'] = time();
}

/**
 * Login user
 */
function login($nrp, $password) {
    global $db;
    
    try {
        $stmt = $db->prepare("
            SELECT p.id_personel, p.nomor_induk, p.nama_lengkap, 
                   p.id_pangkat, p.id_satfung, p.jabatan,
                   k.singkatan as pangkat, s.nama_satfung
            FROM m_personel p
            JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
            JOIN m_satfung s ON p.id_satfung = s.id_satfung
            WHERE p.nomor_induk = :nrp 
            AND p.status_aktif = 1
            LIMIT 1
        ");
        
        $stmt->execute(['nrp' => $nrp]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'NRP tidak ditemukan atau tidak aktif'];
        }
        
        // Verifikasi password (gunakan password_verify jika password di-hash)
        // Sementara pakai plaintext untuk testing, produksi wajib pakai hash
        if (!verifyPassword($password, $nrp)) {
            // Log failed login attempt
            logFailedLogin($nrp);
            return ['success' => false, 'message' => 'Password salah'];
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id_personel'];
        $_SESSION['nrp'] = $user['nomor_induk'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        $_SESSION['pangkat'] = $user['pangkat'];
        $_SESSION['satfung'] = $user['nama_satfung'];
        $_SESSION['jabatan'] = $user['jabatan'];
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        
        // Generate CSRF token
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        
        // Log aktivitas
        logActivity($user['nomor_induk'], 'LOGIN', 'User berhasil login');
        
        return ['success' => true, 'user' => $user];
        
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
    }
}

/**
 * Verifikasi password
 * TODO: Implementasi dengan password_hash() dan password_verify()
 */
function verifyPassword($password, $nrp) {
    // Sementara: password default = NRP
    // Produksi: gunakan password_hash() dan password_verify()
    return $password === $nrp || $password === 'admin123';
}

/**
 * Logout user
 */
function logout() {
    if (isset($_SESSION['nrp'])) {
        logActivity($_SESSION['nrp'], 'LOGOUT', 'User logout');
    }
    
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validasi CSRF token
 */
function validateCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && 
           hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Redirect ke login jika belum login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
    updateActivity();
}

/**
 * Cek permission (opsional)
 */
function hasPermission($permission) {
    // TODO: Implementasi role-based access control
    $admin_satfung = ['Bagian Operasional', 'Bagian SDM'];
    
    if (isset($_SESSION['satfung'])) {
        return in_array($_SESSION['satfung'], $admin_satfung);
    }
    
    return false;
}

/**
 * Log failed login
 */
function logFailedLogin($nrp) {
    $log_file = __DIR__ . '/../logs/login_failed_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $log_entry = "[{$timestamp}] [{$ip}] Failed login attempt for NRP: {$nrp}" . PHP_EOL;
    
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Get current user info
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'nrp' => $_SESSION['nrp'],
        'nama' => $_SESSION['nama'],
        'pangkat' => $_SESSION['pangkat'],
        'satfung' => $_SESSION['satfung'],
        'jabatan' => $_SESSION['jabatan']
    ];
}
