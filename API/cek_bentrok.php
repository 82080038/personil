<?php
/**
 * API: Cek Bentrok Personel
 * Mengecek apakah personel sudah terploting di Sprin lain pada tanggal yang sama
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/helpers.php';

// Validasi input
$id_personel = filter_input(INPUT_POST, 'id_personel', FILTER_VALIDATE_INT);
$tgl_mulai = filter_input(INPUT_POST, 'tgl_mulai', FILTER_SANITIZE_SPECIAL_CHARS);
$tgl_selesai = filter_input(INPUT_POST, 'tgl_selesai', FILTER_SANITIZE_SPECIAL_CHARS);

// Validasi required fields
if (!$id_personel) {
    echo json_encode([
        "status" => "error", 
        "message" => "ID Personel wajib diisi dan harus berupa angka"
    ]);
    exit;
}

// Validasi format tanggal
if (!$tgl_mulai || !$tgl_selesai) {
    echo json_encode([
        "status" => "error", 
        "message" => "Tanggal mulai dan selesai wajib diisi"
    ]);
    exit;
}

// Validasi range tanggal
$date_validation = validateDateRange($tgl_mulai, $tgl_selesai);
if (!$date_validation['valid']) {
    echo json_encode([
        "status" => "error", 
        "message" => $date_validation['message']
    ]);
    exit;
}

try {
    // Query untuk mencari irisan tanggal (overlap)
    // Note: Struktur t_sprin dan t_sprin_detail sudah sama di personil_db
    $query = "SELECT s.id_sprin, s.no_sprin, s.nama_giat, s.tgl_mulai, s.tgl_selesai, s.status_ops
              FROM t_sprin_detail d
              JOIN t_sprin s ON d.id_sprin = s.id_sprin
              WHERE d.id_personel = :id_personel 
              AND (s.tgl_mulai <= :tgl_selesai AND s.tgl_selesai >= :tgl_mulai)
              AND s.status_ops IN ('Draft', 'Aktif')";

    $stmt = $db->prepare($query);
    $stmt->execute([
        'id_personel' => $id_personel,
        'tgl_mulai'   => $tgl_mulai,
        'tgl_selesai' => $tgl_selesai
    ]);
    
    $bentrok = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($bentrok) > 0) {
        echo json_encode([
            "status" => "conflict", 
            "message" => "Anggota sudah terploting di Sprin lain!",
            "data" => $bentrok,
            "conflict_count" => count($bentrok)
        ]);
    } else {
        echo json_encode([
            "status" => "clear",
            "message" => "Personel tersedia untuk tanggal tersebut"
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Cek Bentrok Error: " . $e->getMessage());
    echo json_encode([
        "status" => "error", 
        "message" => "Terjadi kesalahan sistem. Silakan coba lagi."
    ]);
}
