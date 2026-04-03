-- ==========================================================
-- DATABASE: db_bagops
-- DESKRIPSI: Manajemen Operasional Polres (SMO-BAGOPS)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS db_bagops;
USE db_bagops;

-- 1. TABEL MASTER JENIS PERSONEL
CREATE TABLE m_jenis_personel (
    id_jenis INT PRIMARY KEY AUTO_INCREMENT,
    nama_jenis VARCHAR(50) NOT NULL -- Anggota Polri, ASN, PHL
) ENGINE=InnoDB;

INSERT INTO m_jenis_personel (nama_jenis) VALUES 
('Anggota POLRI'), ('ASN POLRI'), ('PHL / Honorer');

-- 2. TABEL MASTER PANGKAT (URUT HIERARKI)
CREATE TABLE m_pangkat (
    id_pangkat INT PRIMARY KEY AUTO_INCREMENT,
    nama_pangkat VARCHAR(100) NOT NULL,
    singkatan VARCHAR(20) NOT NULL,
    hirarki_level INT NOT NULL, -- 1 tertinggi (AKBP), 17 terendah
    jenis_id INT, -- Relasi ke jenis personel
    FOREIGN KEY (jenis_id) REFERENCES m_jenis_personel(id_jenis)
) ENGINE=InnoDB;

INSERT INTO m_pangkat (nama_pangkat, singkatan, hirarki_level, jenis_id) VALUES 
('Ajun Komisaris Besar Polisi', 'AKBP', 1, 1),
('Komisaris Polisi', 'Kompol', 2, 1),
('Ajun Komisaris Polisi', 'AKP', 3, 1),
('Inspektur Polisi Satu', 'Iptu', 4, 1),
('Inspektur Polisi Dua', 'Ipda', 5, 1),
('Ajun Inspektur Polisi Satu', 'Aiptu', 6, 1),
('Ajun Inspektur Polisi Dua', 'Aipda', 7, 1),
('Brigadir Polisi Kepala', 'Bripka', 8, 1),
('Brigadir Polisi', 'Brigpol', 9, 1),
('Brigadir Polisi Satu', 'Briptu', 10, 1),
('Brigadir Polisi Dua', 'Bripda', 11, 1),
('Pembina', 'Pembina', 12, 2),
('Penata Tingkat I', 'Penata Tk I', 13, 2),
('Penata', 'Penata', 14, 2),
('Penata Muda Tingkat I', 'Penda Tk I', 15, 2),
('Penata Muda', 'Penda', 16, 2),
('Pengatur', 'Pengatur', 17, 2);

-- 3. TABEL MASTER SATUAN FUNGSI (SATFUNG)
CREATE TABLE m_satfung (
    id_satfung INT PRIMARY KEY AUTO_INCREMENT,
    nama_satfung VARCHAR(100) NOT NULL,
    kategori_unsur ENUM('Pimpinan', 'Staf', 'Pelaksana', 'Pendukung', 'Wilayah') NOT NULL
) ENGINE=InnoDB;

INSERT INTO m_satfung (nama_satfung, kategori_unsur) VALUES 
('Unsur Pimpinan', 'Pimpinan'),
('Bagian Operasional', 'Staf'),
('Bagian Perencanaan', 'Staf'),
('Bagian SDM', 'Staf'),
('Bagian Logistik', 'Staf'),
('Satuan Intelkam', 'Pelaksana'),
('Satuan Reskrim', 'Pelaksana'),
('Satuan Narkoba', 'Pelaksana'),
('Satuan Samapta', 'Pelaksana'),
('Satuan Lantas', 'Pelaksana'),
('Satuan Binmas', 'Pelaksana'),
('Seksi Propam', 'Pendukung'),
('Seksi Humas', 'Pendukung'),
('Seksi TI', 'Pendukung'),
('Seksi Dokkes', 'Pendukung'),
('Polsek Jajaran', 'Wilayah');

-- 4. TABEL MASTER PERSONEL
CREATE TABLE m_personel (
    id_personel INT PRIMARY KEY AUTO_INCREMENT,
    nomor_induk VARCHAR(25) UNIQUE NOT NULL, -- NRP atau NIP
    nama_lengkap VARCHAR(150) NOT NULL,
    id_jenis INT,
    id_pangkat INT,
    id_satfung INT,
    jabatan VARCHAR(100),
    eselon VARCHAR(10), -- IIB2, IIIA2, dll
    no_hp VARCHAR(20),
    status_aktif TINYINT(1) DEFAULT 1, -- 1: Aktif, 0: Tidak Aktif
    FOREIGN KEY (id_jenis) REFERENCES m_jenis_personel(id_jenis),
    FOREIGN KEY (id_pangkat) REFERENCES m_pangkat(id_pangkat),
    FOREIGN KEY (id_satfung) REFERENCES m_satfung(id_satfung)
) ENGINE=InnoDB;

-- 5. TABEL TRANSAKSI SPRIN (HEADER)
CREATE TABLE t_sprin (
    id_sprin INT PRIMARY KEY AUTO_INCREMENT,
    no_sprin VARCHAR(100) UNIQUE NOT NULL, -- Contoh: Sprin/123/X/OPS.1.1./2024
    dasar_hukum TEXT,
    pertimbangan TEXT,
    nama_giat VARCHAR(200) NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    lokasi_giat TEXT,
    pejabat_ttd_nama VARCHAR(150),
    pejabat_ttd_pangkat VARCHAR(50),
    pejabat_ttd_jabatan VARCHAR(100),
    tgl_cetak_sprin DATE,
    status_ops ENUM('Draft', 'Aktif', 'Selesai', 'Batal') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 6. TABEL DETAIL PERSONEL DALAM SPRIN (RELASI)
CREATE TABLE t_sprin_detail (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_sprin INT,
    id_personel INT,
    peran_tugas VARCHAR(100) DEFAULT 'Anggota Pengamanan',
    FOREIGN KEY (id_sprin) REFERENCES t_sprin(id_sprin) ON DELETE CASCADE,
    FOREIGN KEY (id_personel) REFERENCES m_personel(id_personel),
    UNIQUE KEY (id_sprin, id_personel) -- Mencegah anggota input ganda di satu Sprin
) ENGINE=InnoDB;

-- 7. TABEL LOG AKTIVITAS (AUDIT TRAIL)
CREATE TABLE t_log (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nrp_operator VARCHAR(20),
    aksi VARCHAR(100), -- Simpan Sprin, Hapus Personel, dll
    keterangan TEXT
) ENGINE=InnoDB;
