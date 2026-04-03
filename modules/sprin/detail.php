<?php
/**
 * Modul Sprin - Detail View
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Detail Sprin';
$active_menu = 'sprin';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

// Ambil data Sprin
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

// Ambil anggota
$stmt = $db->prepare("
    SELECT d.*, p.nama_lengkap, p.nomor_induk, k.singkatan, k.hirarki_level,
           s.nama_satfung
    FROM t_sprin_detail d
    JOIN m_personel p ON d.id_personel = p.id_personel
    JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
    JOIN m_satfung s ON p.id_satfung = s.id_satfung
    WHERE d.id_sprin = :id
    ORDER BY k.hirarki_level ASC, p.nama_lengkap ASC
");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetchAll();

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Detail Surat Perintah</h4>
    <div>
        <a href="export.php?id=<?php echo $id; ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-word me-1"></i>Export DOCX
        </a>
        <a href="list.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-info-circle me-2"></i>Informasi Sprin</span>
                <span class="badge bg-<?php 
                    echo ['Draft' => 'secondary', 'Aktif' => 'success', 'Selesai' => 'info', 'Batal' => 'danger'][$sprin['status_ops']]; 
                ?>"><?php echo $sprin['status_ops']; ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Nomor Sprin</strong></td>
                        <td>: <?php echo htmlspecialchars($sprin['no_sprin']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Giat</strong></td>
                        <td>: <?php echo htmlspecialchars($sprin['nama_giat']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>: <?php echo formatTanggalIndo($sprin['tgl_mulai'], 'long'); ?> s/d 
                              <?php echo formatTanggalIndo($sprin['tgl_selesai'], 'long'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Dasar Hukum</strong></td>
                        <td>: <?php echo nl2br(htmlspecialchars($sprin['dasar_hukum'])); ?></td>
                    </tr>
                    <?php if ($sprin['pertimbangan']): ?>
                    <tr>
                        <td><strong>Pertimbangan</strong></td>
                        <td>: <?php echo nl2br(htmlspecialchars($sprin['pertimbangan'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($sprin['lokasi_giat']): ?>
                    <tr>
                        <td><strong>Lokasi</strong></td>
                        <td>: <?php echo nl2br(htmlspecialchars($sprin['lokasi_giat'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>Daftar Anggota 
                <span class="badge bg-primary"><?php echo $sprin['jumlah_anggota']; ?> orang</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NRP/NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Pangkat</th>
                                <th>Satfung</th>
                                <th>Peran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($anggota as $i => $a): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo maskNRP($a['nomor_induk']); ?></td>
                                    <td><?php echo htmlspecialchars($a['nama_lengkap']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo getPangkatBadgeClass($a['hirarki_level']); ?>">
                                            <?php echo $a['singkatan']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($a['nama_satfung']); ?></td>
                                    <td><?php echo htmlspecialchars($a['peran_tugas']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-2"></i>Aksi
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="form.php?id=<?php echo $id; ?>" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit Sprin
                    </a>
                    
                    <?php if ($sprin['status_ops'] === 'Draft'): ?>
                        <button type="button" class="btn btn-success" onclick="updateStatus('Aktif')">
                            <i class="bi bi-check-circle me-1"></i>Aktifkan
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($sprin['status_ops'] === 'Aktif'): ?>
                        <button type="button" class="btn btn-info" onclick="updateStatus('Selesai')">
                            <i class="bi bi-check-all me-1"></i>Tandai Selesai
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($sprin['status_ops'] !== 'Batal'): ?>
                        <button type="button" class="btn btn-danger" onclick="updateStatus('Batal')">
                            <i class="bi bi-x-circle me-1"></i>Batalkan
                        </button>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <a href="export.php?id=<?php echo $id; ?>&format=pdf" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    let title, text, icon;
    
    switch(status) {
        case 'Aktif':
            title = 'Aktifkan Sprin?';
            text = 'Sprin akan diaktifkan dan siap diterbitkan';
            icon = 'question';
            break;
        case 'Selesai':
            title = 'Tandai Selesai?';
            text = 'Sprin akan ditandai sebagai selesai';
            icon = 'info';
            break;
        case 'Batal':
            title = 'Batalkan Sprin?';
            text = 'Sprin yang dibatalkan tidak dapat diaktifkan kembali';
            icon = 'warning';
            break;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            
            $.ajax({
                url: 'update_status.php',
                type: 'POST',
                data: {
                    id: <?php echo $id; ?>,
                    status: status,
                    csrf_token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    hideLoading();
                    
                    if (response.status === 'success') {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>

<?php require_once '../../includes/footer.php'; ?>
