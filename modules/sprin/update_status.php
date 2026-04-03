<?php
/**
 * Modul Sprin - Update Status Handler
 */

header("Content-Type: application/json");

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

if (!isLoggedIn()) {
    echo json_encode(["status" => "error", "message" => "Sesi telah berakhir"]);
    exit;
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

$allowed_status = ['Draft', 'Aktif', 'Selesai', 'Batal'];
if (!in_array($status, $allowed_status)) {
    echo json_encode(["status" => "error", "message" => "Status tidak valid"]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE t_sprin SET status_ops = :status WHERE id_sprin = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
    
    logActivity($_SESSION['nrp'], 'UPDATE_STATUS', "Update Sprin ID $id ke status: $status");
    
    echo json_encode([
        "status" => "success",
        "message" => "Status berhasil diupdate ke $status"
    ]);
    
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Gagal update status"]);
}
