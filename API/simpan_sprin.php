<?php
header("Content-Type: application/json");
require_once '../config/database.php';

// Ambil input JSON dari AJAX
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
    exit;
}

try {
    $db->beginTransaction(); // Mulai transaksi agar data konsisten

    // 1. Simpan Header Sprin
    $sql_head = "INSERT INTO t_sprin (no_sprin, nama_giat, tgl_mulai, tgl_selesai, dasar_hukum) 
                 VALUES (:no, :giat, :mulai, :selesai, :dasar)";
    $stmt = $db->prepare($sql_head);
    $stmt->execute([
        'no'      => $input['no_sprin'],
        'giat'    => $input['nama_giat'],
        'mulai'   => $input['tgl_mulai'],
        'selesai' => $input['tgl_selesai'],
        'dasar'   => $input['dasar_hukum']
    ]);
    
    $id_sprin = $db->lastInsertId();

    // 2. Simpan Detail Anggota (Looping)
    $sql_det = "INSERT INTO t_sprin_detail (id_sprin, id_personel, peran_tugas) VALUES (?, ?, ?)";
    $stmt_det = $db->prepare($sql_det);

    foreach ($input['anggota'] as $agt) {
        $stmt_det->execute([$id_sprin, $agt['id_personel'], $agt['peran']]);
    }

    $db->commit(); // Permanenkan perubahan
    echo json_encode(["status" => "success", "message" => "Sprin berhasil diterbitkan"]);

} catch (Exception $e) {
    $db->rollBack(); // Batalkan jika ada error
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
