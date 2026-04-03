# 🛡️ SMO-BAGOPS (Sistem Manajemen Operasional Bag Ops)

**Versi:** 1.0.0-Production  
**Status:** Production Ready  
**Tujuan:** Digitalisasi, Otomatisasi, dan Akuntabilitas Administrasi Operasional Polri.

---

## 📝 1. Deskripsi Proyek

**SMO-BAGOPS** adalah aplikasi berbasis web lokal (Localhost) yang dirancang untuk membantu personel **Bagian Operasional (Bag Ops)** di tingkat Polres.

Aplikasi ini mengatasi masalah:
- **Double plotting** (bentrok jadwal anggota)
- Pendataan personel tidak terurut hierarki
- Lambatnya pencarian arsip laporan untuk Wasrik/Itwasda

---

## 🏛️ 2. Kepatuhan Regulasi

1. **UU No. 2 Tahun 2002** (Kepolisian)
2. **Perkap No. 2 Tahun 2021** (SOTK Polres/Polsek)
3. **Perkap No. 8 Tahun 2021** (Operasional Polri)
4. **UU No. 27 Tahun 2022** (Perlindungan Data Pribadi)

---

## 🚀 3. Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🔐 **Autentikasi** | Session, CSRF protection, timeout 1 jam |
| 👥 **Manajemen Personel** | CRUD lengkap dengan hierarki pangkat |
| 📄 **Sprin Generator** | Export otomatis ke format Word |
| ⚠️ **Conflict Checker** | Deteksi bentrok jadwal real-time (AJAX) |
| 📊 **Dashboard Anev** | Grafik statistik dengan Chart.js |
| 🔍 **Smart Search** | Select2 dengan pencarian real-time |
| 📝 **Audit Trail** | Log semua aktivitas user |
| 🛡️ **Keamanan** | Input validation, prepared statements |

---

## 🛠️ 4. Tech Stack

- **Backend:** PHP 8.x (PDO MySQL)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, Bootstrap 5.3, jQuery 3.6
- **Libraries:** Select2, SweetAlert2, Chart.js, Bootstrap Icons

---

## 📂 5. Struktur Direktori

```
smo-bagops/
├── 📂 API/                    # Endpoint AJAX
│   ├── cek_bentrok.php        # Cek konflik jadwal
│   ├── get_personel.php       # Search personel
│   ├── simpan_sprin.php       # Simpan Sprin
│   └── statistik_ops.php      # Data statistik
├── 📂 assets/                 # CSS, JS, Images
├── 📂 config/                 # Konfigurasi
│   ├── database.php           # Koneksi PDO
│   └── constants.php          # Konstanta
├── 📂 core/                   # Logika bisnis
│   ├── auth.php               # Autentikasi
│   └── helpers.php            # Utility functions
├── 📂 includes/               # UI Components
│   ├── header.php             # Navbar, sidebar
│   └── footer.php             # JS libraries
├── 📂 modules/                # Modul Aplikasi
│   ├── personel/              # CRUD Personel
│   ├── sprin/                 # Manajemen Sprin
│   └── laporan/               # Analisa & Evaluasi
├── 📂 templates/              # Template dokumen
├── 📂 exports/                # Hasil export
├── 📂 logs/                   # Audit trail
├── 📄 .htaccess               # Security config
├── 📄 index.php               # Dashboard
├── 📄 login.php               # Login page
├── 📄 logout.php              # Logout handler
├── 📄 cari_nama.html          # Demo page
└── 📄 personil.sql            # Database schema
```

---

## 🗃️ 6. Skema Database

| Tabel | Fungsi |
|-------|--------|
| `m_jenis_personel` | Jenis: Polri, ASN, PHL |
| `m_pangkat` | Pangkat dengan hirarki_level |
| `m_satfung` | Satuan Fungsi |
| `m_personel` | Data personel |
| `t_sprin` | Header Surat Perintah |
| `t_sprin_detail` | Relasi personel-Sprin |
| `t_log` | Audit trail |

---

## ⚡ 7. Cara Install

### Setup Database
```bash
mysql -u root -p < personil.sql
```

### Konfigurasi
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_bagops');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Edit `config/constants.php`:
```php
define('POLRES_NAMA', 'Polres [Wilayah]');
define('BASE_URL', 'http://localhost/personil/');
```

---

## � 8. Keamanan

- ✅ PDO Prepared Statements (anti SQL injection)
- ✅ XSS Protection (htmlspecialchars)
- ✅ CSRF Token Validation
- ✅ Session Timeout (1 jam)
- ✅ Data Masking (NRP tersembunyi)
- ✅ Audit Log (semua aktivitas)

---

## 🎯 9. Panduan Penggunaan

### Login
- URL: `http://localhost/personil/login.php`
- Default: NRP = password

### Membuat Sprin
1. Klik "Buat Sprin Baru"
2. Isi data giat dan tanggal
3. Pilih personel (conflict checker otomatis)
4. Simpan dan export ke Word

---

## 📞 10. Kontak

**Pengembang:** Bag Ops  
**Versi:** 1.0.0-Production  
**© 2024** - Dilindungi UU PDP 2022
