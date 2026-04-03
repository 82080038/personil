<?php
/**
 * API: Get Personel untuk Select2
 * Mencari personel berdasarkan nama atau NRP
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../config/database.php';

// Validasi input
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Query untuk struktur personil_db
    $sql = "SELECT p.id, p.nrp, p.nama, 
                   k.kode_pangkat, k.level as hirarki_level, s.nama_satfung
            FROM personil p
            JOIN master_pangkat k ON p.id_pangkat = k.id
            JOIN master_satuan_fungsi s ON p.id_satuan_fungsi = s.id
            WHERE p.is_active = 1";
    
    $params = [];
    
    // Jika ada search term
    if (!empty($search)) {
        $sql .= " AND (p.nama LIKE :search OR p.nrp LIKE :search)";
        $params['search'] = "%$search%";
    }
    
    // Urutkan berdasarkan hierarki (pangkat tertinggi dulu)
    $sql .= " ORDER BY k.level ASC, p.nama ASC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    
    // Bind parameter search jika ada
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute($params);
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format hasil untuk Select2
    $formatted = array_map(function($item) {
        return [
            'id' => $item['id'],
            'text' => $item['kode_pangkat'] . ' ' . $item['nama'] . ' (' . $item['nrp'] . ')',
            'nrp' => $item['nrp'],
            'nama' => $item['nama'],
            'pangkat' => $item['kode_pangkat'],
            'satfung' => $item['nama_satfung'],
            'hirarki_level' => $item['hirarki_level']
        ];
    }, $result);

    echo json_encode([
        "status" => "success", 
        "data" => $formatted,
        "pagination" => [
            "more" => count($result) === $limit
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Get Personel Error: " . $e->getMessage());
    echo json_encode([
        "status" => "error", 
        "message" => "Gagal mengambil data personel"
    ]);
}
