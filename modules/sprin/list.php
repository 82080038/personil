<?php
/**
 * Modul Sprin - List Data Sprin
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Data Surat Perintah';
$active_menu = 'sprin';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Filter
$search = $_GET['search'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_bulan = $_GET['bulan'] ?? '';

// Query untuk data sprin
$sql = "SELECT s.id_sprin, s.no_sprin, s.nama_giat, s.tgl_mulai, s.tgl_selesai, 
               s.status_ops, s.created_at, s.dasar_hukum,
               COUNT(d.id_detail) as jumlah_anggota
        FROM t_sprin s
        LEFT JOIN t_sprin_detail d ON s.id_sprin = d.id_sprin
        WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (s.no_sprin LIKE :search OR s.nama_giat LIKE :search)";
    $params['search'] = "%$search%";
}

if ($filter_status) {
    $sql .= " AND s.status_ops = :status";
    $params['status'] = $filter_status;
}

if ($filter_bulan) {
    $sql .= " AND MONTH(s.tgl_mulai) = :bulan AND YEAR(s.tgl_mulai) = YEAR(CURDATE())";
    $params['bulan'] = $filter_bulan;
}

$sql .= " GROUP BY s.id_sprin ORDER BY s.created_at DESC";

// Hitung total
$count_sql = str_replace("SELECT s.id_sprin, s.no_sprin, s.nama_giat, s.tgl_mulai, s.tgl_selesai, 
               s.status_ops, s.created_at, s.dasar_hukum,
               COUNT(d.id_detail) as jumlah_anggota", "SELECT COUNT(*) as total", $sql);
$stmt = $db->prepare($count_sql);
$stmt->execute($params);
$total_data = $stmt->fetch()['total'];
$total_pages = ceil($total_data / $limit);

// Tambah limit
$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$sprin_list = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Data Surat Perintah</h4>
    <a href="form.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Buat Sprin Baru
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nomor atau nama giat..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="Draft" <?php echo $filter_status == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="Aktif" <?php echo $filter_status == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Selesai" <?php echo $filter_status == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                    <option value="Batal" <?php echo $filter_status == 'Batal' ? 'selected' : ''; ?>>Batal</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="bulan" class="form-select">
                    <option value="">-- Semua Bulan --</option>
                    <?php for ($i = 1; $i <= 12; $i++): 
                        $bulan_nama = date('F', mktime(0, 0, 0, $i, 1));
                    ?>
                        <option value="<?php echo $i; ?>" <?php echo $filter_bulan == $i ? 'selected' : ''; ?>>
                            <?php echo $bulan_nama; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Sprin -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>No. Sprin</th>
                        <th>Nama Giat</th>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($sprin_list) > 0): ?>
                        <?php foreach ($sprin_list as $i => $s): 
                            $badge_classes = [
                                'Draft' => 'secondary',
                                'Aktif' => 'success',
                                'Selesai' => 'info',
                                'Batal' => 'danger'
                            ];
                            $badge_class = $badge_classes[$s['status_ops']] ?? 'secondary';
                        ?>
                            <tr>
                                <td><?php echo $offset + $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($s['no_sprin']); ?></td>
                                <td><?php echo htmlspecialchars($s['nama_giat']); ?></td>
                                <td>
                                    <?php echo formatTanggalIndo($s['tgl_mulai'], 'short'); ?> -<br>
                                    <?php echo formatTanggalIndo($s['tgl_selesai'], 'short'); ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?php echo $s['jumlah_anggota']; ?> orang</span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $badge_class; ?>">
                                        <?php echo $s['status_ops']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detail.php?id=<?php echo $s['id_sprin']; ?>" 
                                       class="btn btn-sm btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="form.php?id=<?php echo $s['id_sprin']; ?>" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="export.php?id=<?php echo $s['id_sprin']; ?>" 
                                       class="btn btn-sm btn-success" title="Export DOCX">
                                        <i class="bi bi-file-earmark-word"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
