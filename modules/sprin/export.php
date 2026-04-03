<?php
/**
 * Modul Sprin - Export ke DOCX
 * Menggunakan template dan PHPWord (simplified version)
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

// Ambil data Sprin lengkap
$stmt = $db->prepare("
    SELECT s.*, 
           COUNT(d.id_detail) as jumlah_anggota
    FROM t_sprin s
    LEFT JOIN t_sprin_detail d ON s.id_sprin = d.id_sprin
    WHERE s.id_sprin = :id
    GROUP BY s.id_sprin
");
$stmt->execute(['id' => $id]);
$sprin = $stmt->fetch();

if (!$sprin) {
    header('Location: list.php');
    exit;
}

// Ambil anggota dengan detail lengkap
$stmt = $db->prepare("
    SELECT d.peran_tugas, 
           p.nama_lengkap, p.nomor_induk, 
           k.singkatan, k.nama_pangkat,
           s.nama_satfung, j.nama_jenis
    FROM t_sprin_detail d
    JOIN m_personel p ON d.id_personel = p.id_personel
    JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
    JOIN m_satfung s ON p.id_satfung = s.id_satfung
    JOIN m_jenis_personel j ON p.id_jenis = j.id_jenis
    WHERE d.id_sprin = :id
    ORDER BY k.hirarki_level ASC, p.nama_lengkap ASC
");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetchAll();

// Generate nama file
$filename = 'SPRIN_' . preg_replace('/[^A-Za-z0-9]/', '_', $sprin['no_sprin']) . '.doc';

// Set header untuk download
header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Generate dokumen Word sederhana (HTML-based)
$bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

$tgl_mulai = date('d', strtotime($sprin['tgl_mulai'])) . ' ' . 
             $bulan[date('m', strtotime($sprin['tgl_mulai']))] . ' ' . 
             date('Y', strtotime($sprin['tgl_mulai']));
             
$tgl_selesai = date('d', strtotime($sprin['tgl_selesai'])) . ' ' . 
               $bulan[date('m', strtotime($sprin['tgl_selesai']))] . ' ' . 
               date('Y', strtotime($sprin['tgl_selesai']));
?>
<html xmlns:o='urn:schemas-microsoft-com:office:office' 
      xmlns:w='urn:schemas-microsoft-com:office:word' 
      xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <title><?php echo $sprin['no_sprin']; ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .anggota-table th, .anggota-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .anggota-table th {
            background-color: #f0f0f0;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .tembusan {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <b>SURAT PERINTAH</b><br>
        Nomor: <?php echo $sprin['no_sprin']; ?>
    </div>
    
    <table>
        <tr>
            <td width="100"><b>Dasar</b></td>
            <td width="10">:</td>
            <td><?php echo nl2br($sprin['dasar_hukum']); ?></td>
        </tr>
        <?php if ($sprin['pertimbangan']): ?>
        <tr>
            <td><b>Pertimbangan</b></td>
            <td>:</td>
            <td><?php echo nl2br($sprin['pertimbangan']); ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="title">
        MEMERINTAHKAN
    </div>
    
    <p>Kepada:</p>
    
    <table class="anggota-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="150">NRP/NIP</th>
                <th>Nama Lengkap</th>
                <th width="150">Pangkat</th>
                <th width="150">Peran/Tugas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anggota as $i => $a): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo $a['nomor_induk']; ?></td>
                <td><?php echo $a['nama_lengkap']; ?></td>
                <td><?php echo $a['singkatan']; ?></td>
                <td><?php echo $a['peran_tugas']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p>Untuk:</p>
    <ol>
        <li>Melaksanakan tugas pengamanan <?php echo $sprin['nama_giat']; ?>;</li>
        <li>Pelaksanaan tugas mulai tanggal <?php echo $tgl_mulai; ?> s/d <?php echo $tgl_selesai; ?>;</li>
        <?php if ($sprin['lokasi_giat']): ?>
        <li>Lokasi giat: <?php echo $sprin['lokasi_giat']; ?>;</li>
        <?php endif; ?>
        <li>Melaporkan hasil pelaksanaan tugas kepada pimpinan;</li>
        <li>Surat Perintah ini berlaku sejak tanggal ditetapkan.</li>
    </ol>
    
    <div class="signature">
        <p>Ditetapkan di: <?php echo POLRES_NAMA; ?></p>
        <p>Pada tanggal: <?php echo date('d') . ' ' . $bulan[date('m')] . ' ' . date('Y'); ?></p>
        <br><br>
        <p><b>a.n KAPOLRES <?php echo strtoupper(POLRES_SINGKATAN); ?></b></p>
        <p>Kasubag Ops</p>
        <br><br><br>
        <p><b><u>( NAMA KASUBAG OPS )</u></b></p>
        <p><?php echo formatPangkat('AKP', ''); ?></p>
        <p>NRP. ............</p>
    </div>
    
    <div class="tembusan">
        <p>Tembusan:</p>
        <ol>
            <li>Kapolres <?php echo POLRES_SINGKATAN; ?>;</li>
            <li>Wakapolres <?php echo POLRES_SINGKATAN; ?>;</li>
            <li>Para Kasat/Kasubag di lingkungan <?php echo POLRES_SINGKATAN; ?>;</li>
            <li>Arsip.
        </ol>
    </div>
</body>
</html>
