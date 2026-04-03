<?php
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $query = "SELECT MONTHNAME(tgl_mulai) as bulan, COUNT(*) as jumlah 
              FROM t_sprin 
              WHERE YEAR(tgl_mulai) = YEAR(CURDATE())
              GROUP BY MONTH(tgl_mulai), status_ops
              ORDER BY bulan_num";
    
    $stmt = $db->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total per status
    $status_query = "SELECT status_ops, COUNT(*) as total 
                     FROM t_sprin 
                     WHERE YEAR(tgl_mulai) = YEAR(CURDATE())
                     GROUP BY status_ops";
    $status_stmt = $db->query($status_query);
    $status_data = $status_stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success", 
        "data" => $data,
        "status_summary" => $status_data,
        "year" => date('Y')
    ]);
    
} catch (PDOException $e) {
    error_log("Statistik Ops Error: " . $e->getMessage());
    echo json_encode([
        "status" => "error", 
        "message" => "Gagal mengambil data statistik"
    ]);
}
