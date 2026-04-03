# 🛡️ SMO-BAGOPS (Sistem Manajemen Operasional Bag Ops)

**Versi:** 1.0.0-Stable  
**Status:** Pengembangan Mandiri (Internal Bag Ops)  
**Tujuan:** Digitalisasi, Otomatisasi, dan Akuntabilitas Administrasi Operasional Polri.

---

## 📝 1. Deskripsi Proyek
**SMO-BAGOPS** adalah aplikasi berbasis web lokal (Localhost) yang dirancang untuk membantu personel **Bagian Operasional (Bag Ops)** di tingkat Polres dalam mengelola data master personel, perencanaan operasi, hingga otomatisasi pembuatan Surat Perintah (Sprin). 

Aplikasi ini mengatasi masalah klasik seperti *double plotting* (bentrok jadwal anggota), pendataan personel yang tidak urut hierarki, serta lambatnya proses pencarian arsip laporan operasi untuk kebutuhan Wasrik/Itwasda.

---

## 🏛️ 2. Kepatuhan Regulasi & Hukum
Pengembangan aplikasi ini wajib mengacu pada:
1. **UU No. 2 Tahun 2002** tentang Kepolisian Negara Republik Indonesia.
2. **Perkap No. 2 Tahun 2021** tentang SOTK Tingkat Polres dan Polsek.
3. **Perkap No. 8 Tahun 2021** tentang Sistem, Manajemen, dan Standar Keberhasilan Operasional Polri.
4. **UU No. 27 Tahun 2022** tentang Pelindungan Data Pribadi (UU PDP).

---

## 🚀 3. Fitur Utama
- **Smart Sprin Generator:** Membuat draf Sprin format dinas otomatis (.docx) berbasis template.
- **Conflict Checker (AJAX):** Deteksi otomatis jika anggota yang dipilih sudah masuk dalam Sprin lain pada tanggal yang sama.
- **Hierarchical Sorting:** Pengurutan daftar personel otomatis berdasarkan pangkat dan senioritas (NRP).
- **Dashboard Anev Visual:** Grafik persebaran kekuatan personel dan beban kerja (fairness system).
- **Digital Archive (Wasrik Ready):** Manajemen folder otomatis untuk dokumen Renops, Rendis, dan Perwabkeu.
- **Master Data Terintegrasi:** Data Pangkat, Satfung, Eselon, dan Status Personel (Polri/ASN/PHL).

---

## 🛠️ 4. Spesifikasi Teknis (Tech Stack)
- **Backend:** PHP 8.x (PDO MySQL)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Library JS:** jQuery 3.6, Select2 (Searchable Dropdown), SweetAlert2
- **Document Engine:** PHPWord (untuk .docx) & DomPDF (untuk .pdf)

---

## 🗃️ 5. Skema Database Ringkas


| Tabel | Fungsi |
|-------|--------|
| `m_personel` | Data utama anggota (NRP, Nama, Pangkat, Satfung, Jabatan). |
| `m_pangkat` | Referensi pangkat dengan bobot hierarki untuk pengurutan. |
| `m_satfung` | Daftar satuan fungsi (Reskrim, Lantas, Binmas, dll). |
| `t_sprin` | Header data Surat Perintah (Nomor, Dasar, Giat, Tgl). |
| `t_sprin_detail` | Tabel relasi yang menghubungkan Personel dengan Sprin. |
| `t_log` | Audit trail untuk mencatat setiap perubahan data. |

---

## 📂 6. Struktur Direktori Proyek
```text
smo-bagops/
├── 📂 api/          # Endpoint AJAX (Cek bentrok, search personel)
├── 📂 assets/       # CSS, JS, Images (Logo Polri)
├── 📂 config/       # database.php & constants.php
├── 📂 core/         # Fungsi Helper & Query Builder
├── 📂 modules/      # Modul Personel, Sprin, & Laporan
├── 📂 templates/    # Template .docx & hasil export
├── 📄 index.php     # Dashboard utama
└── 📄 login.php     # Gerbang akses
