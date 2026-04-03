<?php
/**
 * Helper Functions SMO-BAGOPS
 */

require_once __DIR__ . '/../config/constants.php';

/**
 * Format tanggal Indonesia
 */
function formatTanggalIndo($tanggal, $format = 'long') {
    if (empty($tanggal)) return '-';
    
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $date = strtotime($tanggal);
    $tgl = date('d', $date);
    $bln = $bulan[(int)date('m', $date)];
    $thn = date('Y', $date);
    
    if ($format === 'short') {
        return date('d-m-Y', $date);
    }
    
    return $tgl . ' ' . $bln . ' ' . $thn;
}

/**
 * Format tanggal untuk database
 */
function formatTanggalDB($tanggal) {
    if (empty($tanggal)) return null;
    $date = DateTime::createFromFormat('d-m-Y', $tanggal);
    return $date ? $date->format('Y-m-d') : null;
}

/**
 * Masking NRP (tampilkan 4 digit terakhir saja)
 */
function maskNRP($nrp) {
    if (empty($nrp) || strlen($nrp) < 4) return $nrp;
    return str_repeat('*', strlen($nrp) - 4) . substr($nrp, -4);
}

/**
 * Format nama pangkat lengkap
 * Versi 2.0 - Lengkap dengan 27 pangkat sesuai regulasi
 */
function formatPangkat($singkatan, $nama_lengkap = '') {
    $pangkat_lengkap = [
        // Perwira Menengah (Pamen)
        'Kombes Pol' => 'Komisaris Besar Polisi',
        'AKBP' => 'Ajun Komisaris Besar Polisi',
        'Kompol' => 'Komisaris Polisi',
        // Perwira Pertama (Pama)
        'AKP' => 'Ajun Komisaris Polisi',
        'Iptu' => 'Inspektur Polisi Satu',
        'Ipda' => 'Inspektur Polisi Dua',
        // Bintara Tinggi
        'Aiptu' => 'Ajun Inspektur Polisi Satu',
        'Aipda' => 'Ajun Inspektur Polisi Dua',
        // Bintara
        'Bripka' => 'Brigadir Polisi Kepala',
        'Brigpol' => 'Brigadir Polisi',
        'Briptu' => 'Brigadir Polisi Satu',
        'Bripda' => 'Brigadir Polisi Dua',
        // Tamtama (BARU)
        'Abrip' => 'Ajun Brigadir Polisi',
        'Abriptu' => 'Ajun Brigadir Polisi Satu',
        'Abripda' => 'Ajun Brigadir Polisi Dua',
        'Bharaka' => 'Bhayangkara Kepala',
        'Bharatu' => 'Bhayangkara Satu',
        'Bharada' => 'Bhayangkara Dua',
        // ASN Polri
        'Pembina Tk I' => 'Pembina Utama Muda',
        'Pembina' => 'Pembina',
        'Penata Tk I' => 'Penata Tingkat I',
        'Penata' => 'Penata',
        'Penda Tk I' => 'Penata Muda Tingkat I',
        'Penda' => 'Penata Muda',
        'Peng Tk I' => 'Pengatur Tingkat I',
        'Pengatur' => 'Pengatur',
        'Juru' => 'Juru'
    ];
    
    $pangkat = isset($pangkat_lengkap[$singkatan]) ? $pangkat_lengkap[$singkatan] : $singkatan;
    
    if (!empty($nama_lengkap)) {
        return $pangkat . ' ' . $nama_lengkap;
    }
    
    return $pangkat;
}

/**
 * Generate nomor Sprin otomatis
 */
function generateNoSprin($kode_ops, $tahun = null) {
    if (!$tahun) $tahun = date('Y');
    
    // Format: Sprin/XXX/[BULAN_ROMAWI]/[KODE_OPS]/[TAHUN]
    $bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $bulan = (int)date('m') - 1;
    
    // TODO: Query ke database untuk mendapatkan nomor urut terakhir
    $nomor_urut = '001'; // Placeholder
    
    return "Sprin/{$nomor_urut}/{$bulan_romawi[$bulan]}/{$kode_ops}/{$tahun}";
}

/**
 * Sanitasi input untuk keamanan
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi tanggal range
 */
function validateDateRange($tgl_mulai, $tgl_selesai) {
    $start = strtotime($tgl_mulai);
    $end = strtotime($tgl_selesai);
    
    if (!$start || !$end) {
        return ['valid' => false, 'message' => 'Format tanggal tidak valid'];
    }
    
    if ($end < $start) {
        return ['valid' => false, 'message' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai'];
    }
    
    return ['valid' => true];
}

/**
 * Format nomor telepon Indonesia
 */
function formatNoHP($no_hp) {
    if (empty($no_hp)) return '-';
    
    // Hapus karakter non-digit
    $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
    
    // Format: 08xx-xxxx-xxxx
    if (strlen($no_hp) >= 10) {
        return substr($no_hp, 0, 4) . '-' . substr($no_hp, 4, 4) . '-' . substr($no_hp, 8);
    }
    
    return $no_hp;
}

/**
 * Convert hirarki level ke kelas CSS untuk badge
 * Versi 2.0 - 27 level hirarki
 */
function getPangkatBadgeClass($hirarki_level) {
    if ($hirarki_level <= 3) return 'danger';      // Perwira Menengah (Kombes, AKBP, Kompol)
    if ($hirarki_level <= 6) return 'warning';     // Perwira Pertama (AKP, Iptu, Ipda)
    if ($hirarki_level <= 8) return 'info';        // Bintara Tinggi (Aiptu, Aipda)
    if ($hirarki_level <= 12) return 'primary';     // Bintara (Bripka, Brigpol, Briptu, Bripda)
    if ($hirarki_level <= 18) return 'secondary';  // Tamtama (Abrip-Abriptu-Abripda-Bharaka-Bharatu-Bharada)
    return 'dark';                                  // ASN (Pembina, Penata, Penda, Pengatur, Juru)
}

/**
 * Log aktivitas ke file
 */
function logActivity($nrp_operator, $aksi, $keterangan) {
    $log_file = __DIR__ . '/../logs/activity_' . date('Y-m') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] [{$nrp_operator}] {$aksi}: {$keterangan}" . PHP_EOL;
    
    // Buat direktori logs jika belum ada
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Get kategori pangkat berdasarkan hirarki level
 * Untuk personil_db dengan struktur 27 pangkat
 */
function getPangkatKategori($hirarki_level) {
    if ($hirarki_level <= 3) return 'Perwira Menengah';
    if ($hirarki_level <= 6) return 'Perwira Pertama';
    if ($hirarki_level <= 8) return 'Bintara Tinggi';
    if ($hirarki_level <= 12) return 'Bintara';
    if ($hirarki_level <= 18) return 'Tamtama';
    return 'ASN';
}

/**
 * Format singkatan pangkat untuk tampilan
 */
function formatSingkatanPangkat($singkatan) {
    $mapping = [
        'Penata Tk I' => 'Penata Tk. I',
        'Penda Tk I' => 'Penda Tk. I',
        'Pembina Tk I' => 'Pembina Tk. I',
        'Peng Tk I' => 'Peng. Tk. I',
        'Kombes Pol' => 'Kombes'
    ];
    
    return isset($mapping[$singkatan]) ? $mapping[$singkatan] : $singkatan;
}

/**
 * Log aktivitas ke database (t_log)
 * Versi 3.0 - Integration with personil_db
 */
function logActivityDB($pdo, $nrp_operator, $aksi, $tabel_terkait = null, $id_record = null, $keterangan = '') {
    try {
        $stmt = $pdo->prepare("INSERT INTO t_log (nrp_operator, aksi, tabel_terkait, id_record, keterangan, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nrp_operator,
            $aksi,
            $tabel_terkait,
            $id_record,
            $keterangan,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        return true;
    } catch (PDOException $e) {
        // Fallback to file log
        logActivity($nrp_operator, $aksi, $keterangan . ' [DB Error: ' . $e->getMessage() . ']');
        return false;
    }
}
