<?php
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $query = "SELECT MONTHNAME(tgl_mulai) as bulan, COUNT(*) as jumlah 
              FROM t_sprin 
              WHERE YEAR(tgl_mulai) = YEAR(CURDATE())
              GROUP BY MONTH(tgl_mulai)";
    
    $stmt = $db->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $data]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
