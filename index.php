<?php
/**
 * Dashboard Utama SMO-BAGOPS
 */

require_once 'core/auth.php';
require_once 'core/helpers.php';
require_once 'config/database.php';

requireLogin();

$page_title = 'Dashboard';
$active_menu = 'dashboard';

// Ambil statistik dari database
try {
    // Total personel
    $stmt = $db->query("SELECT COUNT(*) as total FROM m_personel WHERE status_aktif = 1");
    $total_personel = $stmt->fetch()['total'];
    
    // Total Sprin aktif
    $stmt = $db->query("SELECT COUNT(*) as total FROM t_sprin WHERE status_ops = 'Aktif'");
    $total_sprin_aktif = $stmt->fetch()['total'];
    
    // Total Sprin bulan ini
    $stmt = $db->query("SELECT COUNT(*) as total FROM t_sprin 
                        WHERE MONTH(tgl_mulai) = MONTH(CURDATE()) 
                        AND YEAR(tgl_mulai) = YEAR(CURDATE())");
    $total_sprin_bulan_ini = $stmt->fetch()['total'];
    
    // Statistik per satfung
    $stmt = $db->query("
        SELECT s.nama_satfung, COUNT(p.id_personel) as jumlah
        FROM m_satfung s
        LEFT JOIN m_personel p ON s.id_satfung = p.id_satfung AND p.status_aktif = 1
        GROUP BY s.id_satfung
        ORDER BY jumlah DESC
        LIMIT 5
    ");
    $statistik_satfung = $stmt->fetchAll();
    
    // Personel terbaru
    $stmt = $db->query("
        SELECT p.id_personel, p.nomor_induk, p.nama_lengkap, 
               k.singkatan, s.nama_satfung
        FROM m_personel p
        JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
        JOIN m_satfung s ON p.id_satfung = s.id_satfung
        WHERE p.status_aktif = 1
        ORDER BY p.id_personel DESC
        LIMIT 5
    ");
    $personel_terbaru = $stmt->fetchAll();
    
    // Sprin terbaru
    $stmt = $db->query("
        SELECT id_sprin, no_sprin, nama_giat, tgl_mulai, tgl_selesai, status_ops
        FROM t_sprin
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $sprin_terbaru = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $total_personel = 0;
    $total_sprin_aktif = 0;
    $total_sprin_bulan_ini = 0;
    $statistik_satfung = [];
    $personel_terbaru = [];
    $sprin_terbaru = [];
}

require_once 'includes/header.php';
?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Personel</h6>
                        <h3 class="mb-0"><?php echo number_format($total_personel); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
                <a href="modules/personel/list.php" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Sprin Aktif</h6>
                        <h3 class="mb-0"><?php echo number_format($total_sprin_aktif); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-file-text fs-1 opacity-50"></i>
                    </div>
                </div>
                <a href="modules/sprin/list.php" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Sprin Bulan Ini</h6>
                        <h3 class="mb-0"><?php echo number_format($total_sprin_bulan_ini); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
                <span class="small">Bulan <?php echo date('F Y'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Quick Action</h6>
                        <h5 class="mb-0">Buat Sprin</h5>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-plus-circle fs-1 opacity-50"></i>
                    </div>
                </div>
                <a href="modules/sprin/form.php" class="text-dark text-decoration-none small">
                    Buat Baru <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Statistik -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Statistik Personel per Satfung</span>
            </div>
            <div class="card-body">
                <canvas id="satfungChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Statistik Ringkas -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-building me-2"></i>5 Satfung Terbesar
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($statistik_satfung as $stat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($stat['nama_satfung']); ?>
                            <span class="badge bg-primary rounded-pill">
                                <?php echo $stat['jumlah']; ?> orang
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Personel Terbaru -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Personel Terbaru</span>
                <a href="modules/personel/list.php" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Pangkat</th>
                                <th>Satfung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personel_terbaru as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($p['singkatan']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nama_satfung']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sprin Terbaru -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2"></i>Sprin Terbaru</span>
                <a href="modules/sprin/list.php" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Sprin</th>
                                <th>Giat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sprin_terbaru as $s): 
                                $badge_class = [
                                    'Draft' => 'secondary',
                                    'Aktif' => 'success',
                                    'Selesai' => 'info',
                                    'Batal' => 'danger'
                                ][$s['status_ops']] ?? 'secondary';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($s['no_sprin']); ?></td>
                                    <td><?php echo htmlspecialchars($s['nama_giat']); ?></td>
                                    <td><span class="badge bg-<?php echo $badge_class; ?>">
                                        <?php echo $s['status_ops']; ?></span>
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

<script>
// Chart.js untuk statistik satfung
const ctx = document.getElementById('satfungChart').getContext('2d');
const satfungChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($statistik_satfung, 'nama_satfung')); ?>,
        datasets: [{
            label: 'Jumlah Personel',
            data: <?php echo json_encode(array_column($statistik_satfung, 'jumlah')); ?>,
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

<?php require_once 'includes/footer.php'; ?>
