<?php
/**
 * API: Simpan Sprin
 * Menyimpan header sprin dan detail anggota
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/helpers.php';

// Verifikasi login
if (!isLoggedIn()) {
    echo json_encode(["status" => "error", "message" => "Sesi telah berakhir. Silakan login kembali."]);
    exit;
}

// Ambil input JSON dari AJAX
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Format data tidak valid"]);
    exit;
}

// Validasi required fields
$required = ['no_sprin', 'nama_giat', 'tgl_mulai', 'tgl_selesai', 'anggota'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        echo json_encode(["status" => "error", "message" => "Field $field wajib diisi"]);
        exit;
    }
}

// Validasi anggota tidak kosong
if (empty($input['anggota']) || !is_array($input['anggota'])) {
    echo json_encode(["status" => "error", "message" => "Minimal 1 anggota harus dipilih"]);
    exit;
}

// Validasi tanggal
$date_validation = validateDateRange($input['tgl_mulai'], $input['tgl_selesai']);
if (!$date_validation['valid']) {
    echo json_encode(["status" => "error", "message" => $date_validation['message']]);
    exit;
}

// Sanitasi input
$no_sprin = sanitizeInput($input['no_sprin']);
$nama_giat = sanitizeInput($input['nama_giat']);
$dasar_hukum = sanitizeInput($input['dasar_hukum'] ?? '');
$pertimbangan = sanitizeInput($input['pertimbangan'] ?? '');
$lokasi_giat = sanitizeInput($input['lokasi_giat'] ?? '');

try {
    $db->beginTransaction();

    // 1. Simpan Header Sprin
    $sql_head = "INSERT INTO t_sprin (no_sprin, nama_giat, tgl_mulai, tgl_selesai, 
                  dasar_hukum, pertimbangan, lokasi_giat, status_ops, created_at) 
                 VALUES (:no, :giat, :mulai, :selesai, :dasar, :pertimbangan, :lokasi, 'Draft', NOW())";
    $stmt = $db->prepare($sql_head);
    $stmt->execute([
        'no'      => $no_sprin,
        'giat'    => $nama_giat,
        'mulai'   => $input['tgl_mulai'],
        'selesai' => $input['tgl_selesai'],
        'dasar'   => $dasar_hukum,
        'pertimbangan' => $pertimbangan,
        'lokasi'  => $lokasi_giat
    ]);
    
    $id_sprin = $db->lastInsertId();

    // 2. Simpan Detail Anggota
    $sql_det = "INSERT INTO t_sprin_detail (id_sprin, id_personel, peran_tugas) VALUES (?, ?, ?)";
    $stmt_det = $db->prepare($sql_det);

    foreach ($input['anggota'] as $agt) {
        if (empty($agt['id_personel'])) continue;
        
        $stmt_det->execute([
            $id_sprin, 
            (int)$agt['id_personel'], 
            sanitizeInput($agt['peran'] ?? 'Anggota')
        ]);
    }

    $db->commit();
    
    // Log aktivitas
    logActivity($_SESSION['nrp'] ?? 'SYSTEM', 'CREATE_SPRIN', "Membuat Sprin: $no_sprin");

    echo json_encode([
        "status" => "success", 
        "message" => "Sprin berhasil diterbitkan",
        "id_sprin" => $id_sprin,
        "no_sprin" => $no_sprin
    ]);

} catch (Exception $e) {
    $db->rollBack();
    error_log("Simpan Sprin Error: " . $e->getMessage());
    echo json_encode([
        "status" => "error", 
        "message" => "Gagal menyimpan Sprin: " . $e->getMessage()
    ]);
}
