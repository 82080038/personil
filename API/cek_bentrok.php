<?php
header("Content-Type: application/json");
require_once '../config/database.php';

$id_personel = $_POST['id_personel'];
$tgl_mulai   = $_POST['tgl_mulai'];
$tgl_selesai = $_POST['tgl_selesai'];

try {
    // Logika: Mencari irisan tanggal (Overlap)
    $query = "SELECT s.no_sprin, s.nama_giat 
              FROM t_sprin_detail d
              JOIN t_sprin s ON d.id_sprin = s.id_sprin
              WHERE d.id_personel = :id_personel 
              AND (s.tgl_mulai <= :tgl_selesai AND s.tgl_selesai >= :tgl_mulai)
              AND s.status_ops != 'Batal'";

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
            "data" => $bentrok
        ]);
    } else {
        echo json_encode(["status" => "clear"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
