<?php
/**
 * Modul Laporan - Analisa & Evaluasi (Anev)
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Analisa & Evaluasi';
$active_menu = 'laporan';

// Default filter tahun ini
$tahun = $_GET['tahun'] ?? date('Y');
$bulan = $_GET['bulan'] ?? '';

// Statistik per satfung
$stat_satfung = $db->query("
    SELECT s.nama_satfung, COUNT(DISTINCT d.id_personel) as jumlah_personel,
           COUNT(DISTINCT d.id_sprin) as jumlah_sprin
    FROM m_satfung s
    LEFT JOIN m_personel p ON s.id_satfung = p.id_satfung AND p.status_aktif = 1
    LEFT JOIN t_sprin_detail d ON p.id_personel = d.id_personel
    LEFT JOIN t_sprin spr ON d.id_sprin = spr.id_sprin 
        AND YEAR(spr.tgl_mulai) = $tahun
        " . ($bulan ? "AND MONTH(spr.tgl_mulai) = $bulan" : "") . "
    GROUP BY s.id_satfung
    ORDER BY jumlah_sprin DESC
")->fetchAll();

// Statistik beban kerja personel (top 10)
$beban_kerja = $db->query("
    SELECT p.nama_lengkap, k.singkatan, s.nama_satfung,
           COUNT(d.id_sprin) as jumlah_sprin
    FROM m_personel p
    JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
    JOIN m_satfung s ON p.id_satfung = s.id_satfung
    JOIN t_sprin_detail d ON p.id_personel = d.id_personel
    JOIN t_sprin spr ON d.id_sprin = spr.id_sprin
    WHERE spr.status_ops != 'Batal'
        AND YEAR(spr.tgl_mulai) = $tahun
        " . ($bulan ? "AND MONTH(spr.tgl_mulai) = $bulan" : "") . "
    GROUP BY p.id_personel
    ORDER BY jumlah_sprin DESC
    LIMIT 10
")->fetchAll();

// Statistik per bulan
$stat_bulanan = $db->query("
    SELECT MONTH(tgl_mulai) as bulan, COUNT(*) as jumlah_sprin
    FROM t_sprin
    WHERE YEAR(tgl_mulai) = $tahun
        AND status_ops != 'Batal'
    GROUP BY MONTH(tgl_mulai)
    ORDER BY bulan
")->fetchAll();

// Total statistik
$total_sprin = $db->query("
    SELECT COUNT(*) as total FROM t_sprin 
    WHERE YEAR(tgl_mulai) = $tahun AND status_ops != 'Batal'
")->fetch()['total'];

$total_personel_terlibat = $db->query("
    SELECT COUNT(DISTINCT id_personel) as total FROM t_sprin_detail d
    JOIN t_sprin s ON d.id_sprin = s.id_sprin
    WHERE YEAR(s.tgl_mulai) = $tahun AND s.status_ops != 'Batal'
")->fetch()['total'];

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-graph-up me-2"></i>Analisa & Evaluasi</h4>
    <a href="export_anev.php?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>" 
       class="btn btn-success">
        <i class="bi bi-download me-1"></i>Export PDF
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                        <option value="<?php echo $y; ?>" <?php echo $y == $tahun ? 'selected' : ''; ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Bulan (Opsional)</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    <?php for ($m = 1; $m <= 12; $m++): 
                        $bulan_nama = date('F', mktime(0, 0, 0, $m, 1));
                    ?>
                        <option value="<?php echo $m; ?>" <?php echo $m == $bulan ? 'selected' : ''; ?>>
                            <?php echo $bulan_nama; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Total Sprin</h5>
                <h2><?php echo number_format($total_sprin); ?></h2>
                <p class="mb-0">Tahun <?php echo $tahun; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>Personel Terlibat</h5>
                <h2><?php echo number_format($total_personel_terlibat); ?></h2>
                <p class="mb-0">Orang yang pernah terploting</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Statistik Bulanan -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Statistik Sprin per Bulan
            </div>
            <div class="card-body">
                <canvas id="chartBulanan" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top 10 Beban Kerja -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-person-workspace me-2"></i>Top 10 Beban Kerja
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Sprin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($beban_kerja as $bk): ?>
                                <tr>
                                    <td>
                                        <small><?php echo $bk['singkatan']; ?></small><br>
                                        <?php echo htmlspecialchars($bk['nama_lengkap']); ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $bk['jumlah_sprin'] > 5 ? 'danger' : 'primary'; ?>">
                                            <?php echo $bk['jumlah_sprin']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik per Satfung -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-building me-2"></i>Statistik per Satuan Fungsi
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Satuan Fungsi</th>
                        <th class="text-center">Jumlah Personel</th>
                        <th class="text-center">Total Keterlibatan</th>
                        <th>Rata-rata/Personel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stat_satfung as $s): 
                        $rata = $s['jumlah_personel'] > 0 
                            ? round($s['jumlah_sprin'] / $s['jumlah_personel'], 1) 
                            : 0;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['nama_satfung']); ?></td>
                            <td class="text-center"><?php echo $s['jumlah_personel']; ?></td>
                            <td class="text-center">
                                <span class="badge bg-info"><?php echo $s['jumlah_sprin']; ?></span>
                            </td>
                            <td><?php echo $rata; ?> kali/personel</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Data untuk chart
const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const bulanData = <?php echo json_encode(array_column($stat_bulanan, 'jumlah_sprin')); ?>;
const bulanIndex = <?php echo json_encode(array_column($stat_bulanan, 'bulan')); ?>;

// Fill data array
const dataArray = new Array(12).fill(0);
bulanIndex.forEach((bulan, i) => {
    dataArray[bulan - 1] = bulanData[i];
});

// Chart Bulanan
const ctx = document.getElementById('chartBulanan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Jumlah Sprin',
            data: dataArray,
            backgroundColor: 'rgba(0, 51, 102, 0.8)',
            borderColor: 'rgba(0, 51, 102, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
