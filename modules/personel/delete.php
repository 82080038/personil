<?php
/**
 * Modul Personel - Delete Handler
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

try {
    // Cek apakah personel sudah pernah dipakai di Sprin
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM t_sprin_detail WHERE id_personel = :id");
    $stmt->execute(['id' => $id]);
    $count = $stmt->fetch()['total'];
    
    if ($count > 0) {
        // Soft delete: set status nonaktif
        $stmt = $db->prepare("UPDATE m_personel SET status_aktif = 0 WHERE id_personel = :id");
        $stmt->execute(['id' => $id]);
        
        logActivity($_SESSION['nrp'], 'SOFT_DELETE_PERSONEL', "Nonaktifkan personel ID: $id");
        $msg = "Personel dinonaktifkan (sudah memiliki riwayat Sprin)";
    } else {
        // Hard delete
        $stmt = $db->prepare("DELETE FROM m_personel WHERE id_personel = :id");
        $stmt->execute(['id' => $id]);
        
        logActivity($_SESSION['nrp'], 'DELETE_PERSONEL', "Hapus personel ID: $id");
        $msg = "Personel berhasil dihapus";
    }
    
    header("Location: list.php?success=" . urlencode($msg));
    exit;
    
} catch (PDOException $e) {
    header("Location: list.php?error=" . urlencode("Gagal menghapus: " . $e->getMessage()));
    exit;
}
