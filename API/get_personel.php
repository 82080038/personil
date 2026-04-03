<?php
header("Content-Type: application/json");
require_once '../config/database.php'; // Pastikan file koneksi sudah ada

$search = isset($_GET['q']) ? $_GET['q'] : '';

try {
    $query = "SELECT p.id_personel, p.nomor_induk, p.nama_lengkap, k.singkatan 
              FROM m_personel p
              JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
              WHERE p.nama_lengkap LIKE :search OR p.nomor_induk LIKE :search
              ORDER BY k.hirarki_level ASC LIMIT 10";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $result]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
