<?php
/**
 * Modul Sprin - Form Buat/Edit Sprin
 */

require_once '../../core/auth.php';
require_once '../../core/helpers.php';
require_once '../../config/database.php';

requireLogin();

$page_title = 'Form Sprin';
$active_menu = 'sprin_baru';

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$sprin = null;
$anggota_sprin = [];

// Generate nomor Sprin baru
$bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
$no_sprin_baru = 'Sprin/___/' . $bulan_romawi[(int)date('m')-1] . '/OPS.1.1./' . date('Y');

if ($is_edit) {
    // Ambil data Sprin
    $stmt = $db->prepare("SELECT * FROM t_sprin WHERE id_sprin = :id");
    $stmt->execute(['id' => $id]);
    $sprin = $stmt->fetch();
    
    if (!$sprin) {
        header('Location: list.php');
        exit;
    }
    
    // Ambil anggota yang sudah ada
    $stmt = $db->prepare("
        SELECT d.id_personel, d.peran_tugas, 
               p.nama_lengkap, p.nomor_induk, k.singkatan
        FROM t_sprin_detail d
        JOIN m_personel p ON d.id_personel = p.id_personel
        JOIN m_pangkat k ON p.id_pangkat = k.id_pangkat
        WHERE d.id_sprin = :id
        ORDER BY k.hirarki_level ASC
    ");
    $stmt->execute(['id' => $id]);
    $anggota_sprin = $stmt->fetchAll();
}

require_once '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-file-earmark-text me-2"></i>
        <?php echo $is_edit ? 'Edit Surat Perintah' : 'Buat Surat Perintah Baru'; ?>
    </h4>
    <a href="list.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<form id="formSprin" class="row g-3">
    <!-- Header Sprin -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-info-circle me-2"></i>Informasi Surat Perintah
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor Sprin <span class="text-danger">*</span></label>
                    <input type="text" name="no_sprin" id="no_sprin" class="form-control" required
                           value="<?php echo $sprin['no_sprin'] ?? $no_sprin_baru; ?>">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required
                           value="<?php echo $sprin['tgl_mulai'] ?? ''; ?>">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required
                           value="<?php echo $sprin['tgl_selesai'] ?? ''; ?>">
                </div>
                
                <div class="col-12">
                    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_giat" id="nama_giat" class="form-control" required
                           value="<?php echo $sprin['nama_giat'] ?? ''; ?>">
                </div>
                
                <div class="col-12">
                    <label class="form-label">Dasar Hukum</label>
                    <textarea name="dasar_hukum" id="dasar_hukum" class="form-control" rows="2"><?php 
                        echo $sprin['dasar_hukum'] ?? 'Surat Perintah Kapolres [Nama Wilayah] Nomor ...'; 
                    ?></textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Pertimbangan</label>
                    <textarea name="pertimbangan" id="pertimbangan" class="form-control" rows="2"><?php 
                        echo $sprin['pertimbangan'] ?? 'Bahwa untuk kelancaran pelaksanaan kegiatan ...'; 
                    ?></textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Lokasi Giat</label>
                    <textarea name="lokasi_giat" id="lokasi_giat" class="form-control" rows="2"><?php 
                        echo $sprin['lokasi_giat'] ?? ''; 
                    ?></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tambah Anggota -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Daftar Anggota</span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Cari Personel</label>
                        <select id="select_personel" class="form-select" style="width: 100%;"></select>
                        <div id="pesan_bentrok" class="mt-2"></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Peran/Tugas</label>
                        <select id="peran_tugas" class="form-select">
                            <option value="Anggota Pengamanan">Anggota Pengamanan</option>
                            <option value="Koordinator">Koordinator</option>
                            <option value="Wakil Koordinator">Wakil Koordinator</option>
                            <option value="Pengendali">Pengendali</option>
                            <option value="Ketua Tim">Ketua Tim</option>
                            <option value="Anggota">Anggota</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" id="btn_tambah" class="btn btn-success w-100" disabled>
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                    </div>
                </div>
                
                <!-- Tabel Anggota -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_anggota">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NRP/NIP</th>
                                <th>Nama</th>
                                <th>Pangkat</th>
                                <th>Peran/Tugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($is_edit && count($anggota_sprin) > 0): ?>
                                <?php foreach ($anggota_sprin as $i => $a): ?>
                                    <tr data-id="<?php echo $a['id_personel']; ?>">
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $a['nomor_induk']; ?></td>
                                        <td><?php echo $a['nama_lengkap']; ?></td>
                                        <td><?php echo $a['singkatan']; ?></td>
                                        <td><?php echo $a['peran_tugas']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus-anggota">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <input type="hidden" name="anggota" id="anggota_json" value="">
            </div>
        </div>
    </div>
    
    <!-- Submit -->
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save me-2"></i>Simpan Sprin
        </button>
        <a href="list.php" class="btn btn-secondary btn-lg">Batal</a>
    </div>
</form>

<script>
// Data anggota yang sudah ditambahkan
let daftarAnggota = <?php echo json_encode($anggota_sprin); ?>;
let selectedPersonel = null;

$(document).ready(function() {
    // Inisialisasi Select2
    $('#select_personel').select2({
        placeholder: 'Ketik Nama atau NRP...',
        ajax: {
            url: '<?php echo API_URL; ?>get_personel.php',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term, page: params.page };
            },
            processResults: function(data, params) {
                return {
                    results: data.data,
                    pagination: { more: data.pagination.more }
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        theme: 'bootstrap-5'
    });
    
    // Event saat personel dipilih
    $('#select_personel').on('select2:select', function(e) {
        selectedPersonel = e.params.data;
        cekBentrok();
    });
    
    // Event saat personel di-clear
    $('#select_personel').on('select2:clear', function() {
        selectedPersonel = null;
        $('#pesan_bentrok').html('');
        $('#btn_tambah').prop('disabled', true);
    });
    
    // Fungsi cek bentrok
    function cekBentrok() {
        if (!selectedPersonel) return;
        
        const tgl_mulai = $('#tgl_mulai').val();
        const tgl_selesai = $('#tgl_selesai').val();
        
        if (!tgl_mulai || !tgl_selesai) {
            $('#pesan_bentrok').html(`
                <div class="alert alert-warning py-1 mb-0">
                    <small>Isi tanggal giat terlebih dahulu</small>
                </div>
            `);
            $('#btn_tambah').prop('disabled', true);
            return;
        }
        
        $.ajax({
            url: '<?php echo API_URL; ?>cek_bentrok.php',
            type: 'POST',
            data: {
                id_personel: selectedPersonel.id,
                tgl_mulai: tgl_mulai,
                tgl_selesai: tgl_selesai
            },
            success: function(response) {
                if (response.status === 'conflict') {
                    let info = response.data[0];
                    $('#pesan_bentrok').html(`
                        <div class="alert alert-danger py-1 mb-0">
                            <small><i class="bi bi-exclamation-triangle me-1"></i>
                            Sudah terdaftar di <strong>${info.no_sprin}</strong></small>
                        </div>
                    `);
                    $('#btn_tambah').prop('disabled', true);
                } else {
                    $('#pesan_bentrok').html(`
                        <div class="alert alert-success py-1 mb-0">
                            <small><i class="bi bi-check-circle me-1"></i>Tersedia</small>
                        </div>
                    `);
                    $('#btn_tambah').prop('disabled', false);
                }
            }
        });
    }
    
    // Event tanggal berubah
    $('#tgl_mulai, #tgl_selesai').on('change', function() {
        if (selectedPersonel) {
            cekBentrok();
        }
    });
    
    // Tambah anggota
    $('#btn_tambah').on('click', function() {
        if (!selectedPersonel) return;
        
        // Cek duplikat
        let exists = daftarAnggota.find(a => a.id_personel == selectedPersonel.id);
        if (exists) {
            Swal.fire('Peringatan', 'Personel sudah ada dalam daftar', 'warning');
            return;
        }
        
        let anggota = {
            id_personel: selectedPersonel.id,
            nomor_induk: selectedPersonel.nomor_induk,
            nama_lengkap: selectedPersonel.nama_lengkap,
            pangkat: selectedPersonel.pangkat,
            peran: $('#peran_tugas').val()
        };
        
        daftarAnggota.push(anggota);
        renderTabelAnggota();
        
        // Reset select
        $('#select_personel').val(null).trigger('change');
        $('#pesan_bentrok').html('');
        $('#btn_tambah').prop('disabled', true);
        selectedPersonel = null;
    });
    
    // Hapus anggota
    $(document).on('click', '.btn-hapus-anggota', function() {
        let row = $(this).closest('tr');
        let id = row.data('id');
        
        daftarAnggota = daftarAnggota.filter(a => a.id_personel != id);
        renderTabelAnggota();
    });
    
    // Render tabel
    function renderTabelAnggota() {
        let html = '';
        daftarAnggota.forEach((a, i) => {
            html += `
                <tr data-id="${a.id_personel}">
                    <td>${i + 1}</td>
                    <td>${a.nomor_induk}</td>
                    <td>${a.nama_lengkap}</td>
                    <td>${a.pangkat}</td>
                    <td>${a.peran}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus-anggota">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#tabel_anggota tbody').html(html);
        $('#anggota_json').val(JSON.stringify(daftarAnggota));
    }
    
    // Submit form
    $('#formSprin').on('submit', function(e) {
        e.preventDefault();
        
        if (daftarAnggota.length === 0) {
            Swal.fire('Error', 'Minimal 1 anggota harus ditambahkan', 'error');
            return;
        }
        
        let formData = {
            no_sprin: $('#no_sprin').val(),
            nama_giat: $('#nama_giat').val(),
            tgl_mulai: $('#tgl_mulai').val(),
            tgl_selesai: $('#tgl_selesai').val(),
            dasar_hukum: $('#dasar_hukum').val(),
            pertimbangan: $('#pertimbangan').val(),
            lokasi_giat: $('#lokasi_giat').val(),
            anggota: daftarAnggota.map(a => ({
                id_personel: a.id_personel,
                peran: a.peran
            }))
        };
        
        showLoading();
        
        $.ajax({
            url: '<?php echo API_URL; ?>simpan_sprin.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                hideLoading();
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'list.php';
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                hideLoading();
                Swal.fire('Error', 'Gagal menyimpan data', 'error');
            }
        });
    });
});
</script>

<?php require_once '../../includes/footer.php'; ?>
