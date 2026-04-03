# 📂 DOKUMEN PANDUAN PENGEMBANGAN: SISTEM MANAJEMEN OPERASIONAL (SMO-BAGOPS)

## 📌 1. PENDAHULUAN
Dokumen ini berfungsi sebagai cetak biru (blueprint) pembangunan aplikasi mandiri untuk efisiensi tugas di Bagian Operasional (Bag Ops) Polres. Aplikasi ini bertujuan mengotomatisasi administrasi operasional sesuai standar Perkap Nomor 8 Tahun 2021.

---

## 🏛️ 2. ARSITEKTUR DATA MASTER (STANDARDISASI POLRI)
Data master wajib disusun mengikuti nomenklatur resmi agar sinkron dengan SIPP (Sistem Informasi Personel Polri).

### A. Master Personel (Hierarki & Status)
- **Jenis Personel:** Polri, ASN, PHL/Honorer.
- **Pangkat:** Berdasarkan `hirarki_level` (1=AKBP s/d 17=Pengatur) untuk pengurutan otomatis.
- **Satuan Fungsi (Satfung):** Pimpinan, Staf, Pelaksana Pokok, Pendukung, Kewilayahan.
- **Eselon & Jabatan:** Penentuan kewenangan tanda tangan (IIB2, IIIA2, dsb).
- **Atribut Khusus:** Masa berlaku Kartu Senpi, Sertifikasi (Gada Pratama, dll), dan Status Kesehatan.

### B. Master Administrasi & Operasi
- **Dasar Hukum:** Library UU No. 2/2002, Perkap SOTK, dan Renja Tahunan.
- **Jenis Giat:** Operasi Terpusat, Kewilayahan, KRYD, Pengamanan Giat Masyarakat.
- **Sandi Operasi:** Daftar sandi resmi (Lilin, Ketupat, Mantap Brata, dll).

---

## 🛠️ 3. SPESIFIKASI TEKNIS (TECH STACK)
Aplikasi dibangun dengan arsitektur web lokal (Intranet/Localhost):
- **Bahasa Pemrograman:** PHP 8.x (Prosedural/OOP).
- **Database:** MySQL/MariaDB.
- **Frontend:** HTML5, Bootstrap 5 (Responsive), jQuery 3.6.
- **Komunikasi Data:** AJAX (untuk validasi real-time tanpa refresh).
- **Library Output:** PHPWord (Generate .docx) & DomPDF (Generate .pdf).

---

## ⚙️ 4. LOGIKA FITUR UNGGULAN (CORE LOGIC)

### A. Smart Ploting & Conflict Checker
Sistem harus mengecek ketersediaan personel sebelum dimasukkan ke Sprin baru.
- **Logic:** `SELECT NRP WHERE NOT IN (SELECT NRP FROM t_sprin_detail WHERE tgl_giat BETWEEN x AND y)`.
- **Warning:** Munculkan notifikasi jika anggota sudah terploting di >2 Sprin aktif.

### B. Pemerataan Beban Kerja (Fairness System)
Fitur untuk melihat statistik penugasan anggota.
- **Indikator:** Menampilkan jumlah keterlibatan Sprin per anggota dalam 1 bulan terakhir untuk menghindari "orang yang itu-itu saja".

### C. Digital Archive & Folder Auto-Gen
Integrasi database dengan struktur folder fisik (Wasrik Ready):
- Otomatis membuat folder: `[TAHUN]/[BULAN]/[NAMA_OPS]/[SUB_FOLDER_RENOPS_SPRIN_LAPORAN]`.

---

## 📑 5. STRUKTUR DATABASE (FINAL DDL)
Pastikan relasi antar tabel terjaga (Foreign Key) untuk integritas data:
1. `m_jenis_personel` -> `m_pangkat` -> `m_satfung`
2. `m_personel` (Junction Table utama)
3. `t_sprin` (Head) -> `t_sprin_detail` (Item Anggota)
4. `t_log_activity` (Audit Trail)

---

## 🛡️ 6. KEAMANAN & PRIVASI (UU PDP & KERAHASIAAN POLRI)
Mengingat data kepolisian bersifat sensitif:
1. **Localhost Only:** Aplikasi hanya boleh diakses melalui IP `127.0.0.1`.
2. **Access Level:** Pembedaan hak akses (Operator Bag Ops vs View Only).
3. **Data Masking:** Menyembunyikan 4 digit terakhir NIK/NRP pada tampilan umum.
4. **Backup Plan:** Fitur ekspor database berkala ke format `.sql` terenkripsi.

---

## 📝 7. TAHAPAN PENGEMBANGAN (ROADMAP)
1. **Minggu 1:** Setup Database Master (Pangkat, Satfung, Personel).
2. **Minggu 2:** Pembuatan Form Input Sprin & Logika AJAX Conflict Checker.
3. **Minggu 3:** Integrasi Template PHPWord (Ekspor dokumen format dinas).
4. **Minggu 4:** Pengujian (UAT) dengan data riil Bag Ops dan Hardening Security.

---
**Disetujui untuk Pengembangan Mandiri**
*Bag Ops Polres [Nama Wilayah]*
