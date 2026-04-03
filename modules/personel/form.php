<?php
/**
 * Modul Personel - Form Tambah/Edit Personel
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Form Personel';
$active_menu = 'personel';

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$personel = null;

// Ambil data master
$jenis_list = $db->query("SELECT * FROM m_jenis_personel ORDER BY id_jenis")->fetchAll();
$pangkat_list = $db->query("SELECT * FROM m_pangkat ORDER BY hirarki_level")->fetchAll();
$satfung_list = $db->query("SELECT * FROM m_satfung ORDER BY nama_satfung")->fetchAll();

if ($is_edit) {
    $stmt = $db->prepare("
        SELECT * FROM m_personel WHERE id_personel = :id
    ");
    $stmt->execute(['id' => $id]);
    $personel = $stmt->fetch();
    
    if (!$personel) {
        header('Location: list.php');
        exit;
    }
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nomor_induk' => $_POST['nomor_induk'],
        'nama_lengkap' => $_POST['nama_lengkap'],
        'id_jenis' => $_POST['id_jenis'],
        'id_pangkat' => $_POST['id_pangkat'],
        'id_satfung' => $_POST['id_satfung'],
        'jabatan' => $_POST['jabatan'],
        'eselon' => $_POST['eselon'],
        'no_hp' => $_POST['no_hp'],
        'status_aktif' => isset($_POST['status_aktif']) ? 1 : 0
    ];
    
    try {
        if ($is_edit) {
            // Update
            $sql = "UPDATE m_personel SET 
                    nomor_induk = :nomor_induk, nama_lengkap = :nama_lengkap,
                    id_jenis = :id_jenis, id_pangkat = :id_pangkat, id_satfung = :id_satfung,
                    jabatan = :jabatan, eselon = :eselon, no_hp = :no_hp, status_aktif = :status_aktif
                    WHERE id_personel = :id";
            $data['id'] = $id;
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            
            logActivity($_SESSION['nrp'], 'UPDATE_PERSONEL', "Update personel: {$data['nama_lengkap']}");
            $success_msg = "Data personel berhasil diperbarui";
        } else {
            // Insert
            $sql = "INSERT INTO m_personel (nomor_induk, nama_lengkap, id_jenis, id_pangkat, 
                    id_satfung, jabatan, eselon, no_hp, status_aktif) 
                    VALUES (:nomor_induk, :nama_lengkap, :id_jenis, :id_pangkat, 
                    :id_satfung, :jabatan, :eselon, :no_hp, :status_aktif)";
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            
            logActivity($_SESSION['nrp'], 'CREATE_PERSONEL', "Tambah personel: {$data['nama_lengkap']}");
            $success_msg = "Data personel berhasil ditambahkan";
        }
        
        // Redirect ke list
        header("Location: list.php?success=" . urlencode($success_msg));
        exit;
        
    } catch (PDOException $e) {
        $error = "Gagal menyimpan data: " . $e->getMessage();
    }
}

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-person-<?php echo $is_edit ? 'gear' : 'plus'; ?> me-2"></i>
        <?php echo $is_edit ? 'Edit Personel' : 'Tambah Personel Baru'; ?>
    </h4>
    <a href="list.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NRP / NIP <span class="text-danger">*</span></label>
                <input type="text" name="nomor_induk" class="form-control" required
                       value="<?php echo $personel['nomor_induk'] ?? ''; ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control" required
                       value="<?php echo $personel['nama_lengkap'] ?? ''; ?>">
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Jenis Personel <span class="text-danger">*</span></label>
                <select name="id_jenis" class="form-select" required>
                    <option value="">Pilih...</option>
                    <?php foreach ($jenis_list as $j): ?>
                        <option value="<?php echo $j['id_jenis']; ?>" 
                            <?php echo ($personel['id_jenis'] ?? '') == $j['id_jenis'] ? 'selected' : ''; ?>>
                            <?php echo $j['nama_jenis']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Pangkat <span class="text-danger">*</span></label>
                <select name="id_pangkat" class="form-select" required>
                    <option value="">Pilih...</option>
                    <?php foreach ($pangkat_list as $p): ?>
                        <option value="<?php echo $p['id_pangkat']; ?>" 
                            <?php echo ($personel['id_pangkat'] ?? '') == $p['id_pangkat'] ? 'selected' : ''; ?>>
                            <?php echo $p['singkatan'] . ' - ' . $p['nama_pangkat']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Satuan Fungsi <span class="text-danger">*</span></label>
                <select name="id_satfung" class="form-select" required>
                    <option value="">Pilih...</option>
                    <?php foreach ($satfung_list as $s): ?>
                        <option value="<?php echo $s['id_satfung']; ?>" 
                            <?php echo ($personel['id_satfung'] ?? '') == $s['id_satfung'] ? 'selected' : ''; ?>>
                            <?php echo $s['nama_satfung']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control"
                       value="<?php echo $personel['jabatan'] ?? ''; ?>">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Eselon</label>
                <select name="eselon" class="form-select">
                    <option value="">-</option>
                    <option value="IIB2" <?php echo ($personel['eselon'] ?? '') == 'IIB2' ? 'selected' : ''; ?>>IIB2</option>
                    <option value="IIIA2" <?php echo ($personel['eselon'] ?? '') == 'IIIA2' ? 'selected' : ''; ?>>IIIA2</option>
                    <option value="IIIA1" <?php echo ($personel['eselon'] ?? '') == 'IIIA1' ? 'selected' : ''; ?>>IIIA1</option>
                    <option value="IIIB" <?php echo ($personel['eselon'] ?? '') == 'IIIB' ? 'selected' : ''; ?>>IIIB</option>
                    <option value="IIIC" <?php echo ($personel['eselon'] ?? '') == 'IIIC' ? 'selected' : ''; ?>>IIIC</option>
                    <option value="IVA" <?php echo ($personel['eselon'] ?? '') == 'IVA' ? 'selected' : ''; ?>>IVA</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="<?php echo $personel['no_hp'] ?? ''; ?>">
            </div>
            
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="status_aktif" class="form-check-input" value="1" 
                           <?php echo ($is_edit ? ($personel['status_aktif'] ? 'checked' : '') : 'checked'); ?>>
                    <label class="form-check-label">Status Aktif</label>
                </div>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="list.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
