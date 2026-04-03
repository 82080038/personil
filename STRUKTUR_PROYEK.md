# 📂 STRUKTUR DIREKTORI PROYEK (SMO-BAGOPS)

Struktur ini menggunakan pola **Separation of Concerns**, di mana file logika (PHP), tampilan (HTML), dan aset (CSS/JS) dipisahkan agar kode tidak berantakan.

## 🌳 Pohon Direktori

```text
smo-bagops/
├── 📂 assets/              # Aset Statis
│   ├── 📂 css/             # File Bootstrap & Custom CSS
│   ├── 📂 js/              # jQuery, Select2, & Custom AJAX
│   ├── 📂 img/             # Logo Polri, Foto Personel, Ikon
│   └── 📂 vendor/          # Library Pihak Ketiga (Bootstrap, FontAwesome)
│
├── 📂 config/              # Pengaturan Sistem
│   ├── 📄 database.php     # Koneksi MySQL (PDO/MySQLi)
│   └── 📄 constants.php    # Nama Polres, Satwil, & Base URL
│
├── 📂 core/                # Logika Bisnis (Backend)
│   ├── 📄 auth.php         # Verifikasi Login/Sesi
│   ├── 📄 helpers.php      # Fungsi Format Tanggal & Pangkat Polri
│   └── 📄 query_builder.php# Fungsi CRUD Global
│
├── 📂 modules/             # Modul Fitur Aplikasi
│   ├── 📂 personel/        # Manajemen Data Anggota
│   ├── 📂 sprin/           # Manajemen Pembuatan Sprin
│   ├── 📂 operasional/     # Manajemen Giat Ops/KRYD
│   └── 📂 laporan/         # Anev & Rekapitulasi
│
├── 📂 api/                 # Endpoint untuk AJAX
│   ├── 📄 cek_bentrok.php  # Logic Conflict Checker (JSON)
│   ├── 📄 get_personel.php # Search Personel via Select2
│   └── 📄 save_sprin.php   # Handler Input Sprin
│
├── 📂 templates/           # Output Dokumen
│   ├── 📂 docx/            # Template .docx (Format Sprin Resmi)
│   └── 📂 exports/         # Hasil Generate Sprin (Auto-Saved)
│
├── 📂 includes/            # Komponen UI Reusable
│   ├── 📄 header.php       # Menu Navigasi Atas
│   ├── 📄 sidebar.php      # Menu Samping
│   └── 📄 footer.php       # Copyright & JS Loader
│
├── 📄 index.php            # Dashboard Utama (Anev Visual)
├── 📄 login.php            # Halaman Akses
└── 📄 .htaccess            # Keamanan Folder & URL Friendly
