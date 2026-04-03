<?php
/**
 * Konstanta Aplikasi SMO-BAGOPS
 */

// Info Polres
define('POLRES_NAMA', 'Polres [Nama Wilayah]');
define('POLRES_SINGKATAN', '[NAMA POLRES]');
define('SATWIL', 'POLDA [NAMA POLDA]');

// URL & Path
define('BASE_URL', 'http://localhost/personil/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('API_URL', BASE_URL . 'API/');

// Path fisik
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('MODULES_PATH', ROOT_PATH . 'modules/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');
define('TEMPLATES_PATH', ROOT_PATH . 'templates/');
define('EXPORTS_PATH', ROOT_PATH . 'exports/');

// Session
define('SESSION_NAME', 'smo_bagops_session');
define('SESSION_TIMEOUT', 3600); // 1 jam dalam detik

// Keamanan
define('CSRF_TOKEN_NAME', 'csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 menit

// Versi Aplikasi
define('APP_VERSION', '1.0.0');
define('APP_NAME', 'SMO-BAGOPS');
define('APP_FULLNAME', 'Sistem Manajemen Operasional Bag Ops');

// Format Tanggal
define('DATE_FORMAT', 'd-m-Y');
define('DATETIME_FORMAT', 'd-m-Y H:i:s');
define('DB_DATE_FORMAT', 'Y-m-d');
