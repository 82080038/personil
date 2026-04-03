<?php
/**
 * Modul Personel - List Data Personel
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Data Personel';
$active_menu = 'personel';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filter
$search = $_GET['search'] ?? '';
$filter_satfung = $_GET['satfung'] ?? '';
$filter_pangkat = $_GET['pangkat'] ?? '';

// Query untuk data personel - struktur personil_db
$sql = "SELECT p.id, p.nrp, p.nama, p.jabatan, 
               p.is_active, p.no_telepon as no_hp,
               k.kode_pangkat as pangkat, k.level as hirarki_level,
               s.nama_satfung
        FROM personil p
        JOIN master_pangkat k ON p.id_pangkat = k.id
        JOIN master_satuan_fungsi s ON p.id_satuan_fungsi = s.id
        WHERE p.is_active = 1";
$params = [];

if ($search) {
    $sql .= " AND (p.nama LIKE :search OR p.nrp LIKE :search)";
    $params['search'] = "%$search%";
}

if ($filter_satfung) {
    $sql .= " AND p.id_satuan_fungsi = :satfung";
    $params['satfung'] = $filter_satfung;
}

if ($filter_pangkat) {
    $sql .= " AND p.id_pangkat = :pangkat";
    $params['pangkat'] = $filter_pangkat;
}

// Hitung total untuk pagination
$count_sql = str_replace("SELECT p.id, p.nrp, p.nama, p.jabatan, 
               p.is_active, p.no_telepon as no_hp,
               k.kode_pangkat as pangkat, k.level as hirarki_level,
               s.nama_satfung", "SELECT COUNT(*)", $sql);
$stmt = $db->prepare($count_sql);
$stmt->execute($params);
$total_data = $stmt->fetch()['total'];
$total_pages = ceil($total_data / $limit);

// Tambah order dan limit
$sql .= " ORDER BY k.hirarki_level ASC, p.nama ASC LIMIT :limit OFFSET :offset";
$sql .= " ORDER BY k.hirarki_level ASC, p.nama_lengkap ASC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$personel = $stmt->fetchAll();

// Ambil data satfung untuk filter
$stmt = $db->query("SELECT id, nama_satfung FROM master_satuan_fungsi WHERE is_active = 1 ORDER BY nama_satfung");
$satfung_list = $stmt->fetchAll();

// Ambil data pangkat untuk filter  
$stmt = $db->query("SELECT id, kode_pangkat, nama_pangkat FROM master_pangkat WHERE is_active = 1 ORDER BY level ASC");
$pangkat_list = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people me-2"></i>Data Personel</h4>
    <a href="form.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Personel
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama atau NRP..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="satfung" class="form-select">
                    <option value="">-- Semua Satfung --</option>
                    <?php foreach ($satfung_list as $s): ?>
                        <option value="<?php echo $s['id_satfung']; ?>" 
                            <?php echo $filter_satfung == $s['id_satfung'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['nama_satfung']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="pangkat" class="form-select">
                    <option value="">-- Semua Pangkat --</option>
                    <?php foreach ($pangkat_list as $p): ?>
                        <option value="<?php echo $p['id_pangkat']; ?>" 
                            <?php echo $filter_pangkat == $p['id_pangkat'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['singkatan']); ?>
                        </option>
                    <?php endforeach; ?>
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

<!-- Tabel Personel -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NRP/NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Pangkat</th>
                        <th>Jabatan</th>
                        <th>Satfung</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($personel) > 0): ?>
                        <?php foreach ($personel as $i => $p): 
                            $badge_class = $p['status_aktif'] ? 'success' : 'secondary';
                            $status_text = $p['status_aktif'] ? 'Aktif' : 'Nonaktif';
                        ?>
                            <tr>
                                <td><?php echo $offset + $i + 1; ?></td>
                                <td><?php echo maskNRP($p['nomor_induk']); ?></td>
                                <td><?php echo htmlspecialchars($p['nama_lengkap']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo getPangkatBadgeClass($p['hirarki_level']); ?>">
                                        <?php echo htmlspecialchars($p['pangkat']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($p['jabatan'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($p['nama_satfung']); ?></td>
                                <td><span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <a href="form.php?id=<?php echo $p['id_personel']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="hapusPersonel(<?php echo $p['id_personel']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">Data tidak ditemukan</td>
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
                        <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">
                            Previous
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script>
function hapusPersonel(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete.php?id=' + id;
        }
    });
}
</script>

<?php require_once '../../includes/footer.php'; ?>
