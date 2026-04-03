# ANALISIS KOMPARATIF DATABASE
## personil_db vs db_bagops

### Tanggal Analisis: April 2026

---

## RINGKASAN EKSEKUTIF

Database `personil_db` memiliki struktur yang **signifikan lebih lengkap dan profesional** dibandingkan `db_bagops`. `personil_db` mengikuti standar Perkap dengan implementasi:
- Master data yang komprehensif (11 tabel vs 7 tabel)
- Riwayat/audit trail lengkap
- Penanganan penugasan sementara (PS, Plt, Pjs, Plh, Pj)
- View SQL untuk reporting

---

## 1. PERBANDINGAN STRUKTUR TABEL

| No | personil_db (11 Tabel) | db_bagops (7 Tabel) | Status |
|----|------------------------|---------------------|--------|
| 1 | `master_jabatan` | ❌ Tidak ada | personil_db lebih lengkap |
| 2 | `master_jenis_penugasan` | ❌ Tidak ada | personil_db lebih lengkap |
| 3 | `master_pangkat` | `m_pangkat` | ✅ Sama |
| 4 | `master_satuan_fungsi` | `m_satfung` | ✅ Sama |
| 5 | `master_unit_pendukung` | ❌ Tidak ada | personil_db lebih lengkap |
| 6 | `master_unsur` | ❌ Tidak ada | personil_db lebih lengkap |
| 7 | `penugasan_sementara` | ❌ Tidak ada | personil_db lebih lengkap |
| 8 | `personil` | `m_personel` | ⚠️ personil_db lebih detail |
| 9 | `riwayat_jabatan` | ❌ Tidak ada | personil_db lebih lengkap |
| 10 | `riwayat_pangkat` | ❌ Tidak ada | personil_db lebih lengkap |
| 11 | `users` | ❌ Tidak ada | personil_db lebih lengkap |
| 12 | ❌ Tidak ada | `m_jenis_personel` | db_bagops punya |
| 13 | ❌ Tidak ada | `t_sprin` | db_bagops punya |
| 14 | ❌ Tidak ada | `t_sprin_detail` | db_bagops punya |
| 15 | ❌ Tidak ada | `t_log` | db_bagops punya |

**Skor:**
- personil_db: 11 tabel (fokus kepegawaian)
- db_bagops: 7 tabel (fokus operasional)

---

## 2. PERBANDINGAN MASTER PANGKAT

### personil_db: master_pangkat (10 records, hanya aktif)
```sql
Struktur:
- id, kode_pangkat, nama_pangkat, nama_pangkat_lengkap
- golongan, jenjang, level, urutan
- is_active, created_at, updated_at
```

**Kekurangan:** Hanya 10 pangkat aktif, tidak ada Tamtama (Bharada-Bharaka)

### db_bagops: m_pangkat (27 records, lengkap)
```sql
Struktur:
- id_pangkat, nama_pangkat, singkatan
- hirarki_level, jenis_id (Polri/ASN)
- golongan, kategori
```

**Kelebihan:** 27 pangkat lengkap termasuk 6 Tamtama

**Rekomendasi:** Gabungkan struktur personil_db dengan data lengkap db_bagops

---

## 3. PERBANDINGAN TABEL PERSONIL

### personil_db: personil (35 fields) - SANGAT LENGKAP
```
Data Pribadi:
✓ nrp (8 digit PERKAP)
✓ nama, gelar_depan, gelar_belakang
✓ tempat_lahir, tanggal_lahir
✓ jenis_kelamin, agama, status_nikah

Data Kepegawaian:
✓ id_pangkat, golongan, tmt_pangkat
✓ id_jabatan, id_unsur, id_satuan_fungsi, id_unit_pendukung
✓ is_penugasan_definitif, status_kepegawaian
✓ tmt_pengangkatan, tmt_pensiun

Data Kontak & Dokumen:
✓ alamat, no_telepon, email
✓ no_karpeg, no_ktp, no_npwp

Data Pendidikan & Keluarga:
✓ pendidikan_terakhir, jurusan, tahun_lulus
✓ jumlah_anak

File & Metadata:
✓ foto
✓ is_active, created_at, updated_at, created_by, updated_by
```

### db_bagops: m_personel (18 fields) - DASAR
```
Data Dasar:
✓ nomor_induk, nama_lengkap, nama_panggilan
✓ id_jenis, id_pangkat, id_satfung
✓ jabatan, eselon

Data Pribadi (Baru ditambahkan V2):
✓ tempat_lahir, tanggal_lahir, jenis_kelamin
✓ alamat, no_hp, email

Data Kepegawaian (Baru ditambahkan V2):
✓ tanggal_masuk, tanggal_pengangkatan
✓ masa_kerja_tahun, masa_kerja_bulan
✓ status_kepegawaian

File & Metadata (Baru ditambahkan V2):
✓ foto
✓ created_at, updated_at, created_by, updated_by
```

**Analisis:**
- personil_db lebih sesuai PERKAP dengan NRP 8 digit
- personil_db memiliki sistematika yang lebih terstruktur
- db_bagops baru mengejar fitur personil_db di V2

---

## 4. FITUR UNIK personil_db

### A. Master Jabatan (master_jabatan)
```sql
- Kode jabatan, nama jabatan, nama lengkap
- Eselon dan tingkat eselon
- Pangkat minimal & maksimal untuk jabatan
- Unsur kategori (pimpinan, pembantu, pelaksana, kewilayahan, pendukung)
- Unit organisasi
- is_pimpinan, is_struktural
```
**Manfaat:** Validasi penempatan personel sesuai jenjang

### B. Master Unsur (master_unsur)
```sql
- Kode unsur, nama unsur
- Kategori: pimpinan, pembantu_pimpinan, pelaksana_tugas_pokok,
  pelaksana_kewilayahan, pendukung, lainnya
- Level unsur untuk hirarki
```
**Manfaat:** Klasifikasi struktur organisasi Polres sesuai SOTK

### C. Master Unit Pendukung (master_unit_pendukung)
```sql
- Unit keuangan, umum, BMN/P, kepegawaian, dll
```
**Manfaat:** Pemetaan unit pendukung operasional

### D. Master Jenis Penugasan (master_jenis_penugasan)
```sql
- Definitif, PS, Plt, Pjs, Plh, Pj
- Maksimal durasi, persyaratan SK
- PS percentage tracking
```
**Manfaat:** Pengelolaan penugasan non-definitif (penting untuk Wasrik!)

### E. Penugasan Sementara (penugasan_sementara)
```sql
- Tracking PS/Plt/Pjs/Plh/Pj per personil
- Masa berlaku dan status
- Link ke riwayat jabatan
```
**Manfaat:** Monitoring batas waktu penugasan

### F. Riwayat Jabatan (riwayat_jabatan)
```sql
- Histori mutasi (Promosi, Mutasi, Rotasi, Demosi)
- No SK, TMT, alasan mutasi
```
**Manfaat:** Audit trail karir personel

### G. Riwayat Pangkat (riwayat_pangkat)
```sql
- Histori kenaikan pangkat
- Jenis: Reguler, Luar Biasa, Prestasi, Penghargaan
- Masa kerja saat kenaikan
```
**Manfaat:** Tracking kenaikan pangkat

### H. Users (Authentication)
```sql
- username, password (hashed)
- role: admin, operator, viewer
- last_login tracking
```
**Manfaat:** Sistem autentikasi bawaan

---

## 5. FITUR UNIK db_bagops

### A. Modul Sprin (t_sprin, t_sprin_detail)
- Manajemen Surat Perintah
- Conflict checker personel
- Export dokumen

### B. Audit Log (t_log)
- Tracking aktivitas user
- IP address dan user agent

### C. Master Jenis Personel
- Polri, ASN, PHL/Honorer

---

## 6. REKOMENDASI INTEGRASI

### Strategi: "personil_db sebagai CORE + db_bagops sebagai OPS"

#### Opsi 1: Merge ke personil_db (DIREKOMENDASIKAN)
Tambahkan ke personil_db:
1. ✅ `m_jenis_personel` (master data)
2. ✅ `t_sprin` & `t_sprin_detail` (modul operasional)
3. ✅ `t_log` (audit trail)
4. ✅ Update `master_pangkat` dengan 27 pangkat lengkap
5. ✅ Update `master_satuan_fungsi` dengan Satres PPA & PPO

#### Opsi 2: Perbaikan db_bagops ke V3
Upgrade db_bagops dengan fitur personil_db:
1. Tambahkan `master_jabatan` dengan eselon
2. Tambahkan `master_unsur`
3. Tambahkan `master_jenis_penugasan` & `penugasan_sementara`
4. Tambahkan `riwayat_jabatan` & `riwayat_pangkat`
5. Tambahkan `users` table
6. Perbaiki `m_personel` dengan NRP 8 digit format
7. Tambahkan views seperti `v_personil_detail`

---

## 7. KESESUAIAN REGULASI

| Aspek | personil_db | db_bagops |
|-------|-------------|-----------|
| NRP 8 digit (Perkap) | ✅ Ya | ⚠️ Format bebas |
| Eselon Jabatan | ✅ Ya | ⚠️ Field saja |
| Penugasan Sementara | ✅ Lengkap | ❌ Tidak ada |
| Riwayat Karir | ✅ Lengkap | ❌ Tidak ada |
| SOTK Unsur | ✅ Ya | ❌ Tidak ada |
| Pangkat Lengkap | ⚠️ 10 saja | ✅ 27 lengkap |
| Satfung Update 2025 | ❌ Cek manual | ⚠️ Perlu cek |

---

## 8. KESIMPULAN

### Pemenang per Kategori:

| Kategori | Pemenang | Alasan |
|----------|----------|--------|
| **Kepegawaian/HR** | 🏆 personil_db | Fitur lengkap, audit trail, riwayat |
| **Operasional/OPS** | 🏆 db_bagops | Modul Sprin, conflict checker |
| **Regulasi/SOTK** | 🏆 personil_db | NRP 8 digit, eselon, unsur |
| **Master Data** | 🤝 Seri | personil_db (struktur) vs db_bagops (jumlah pangkat) |
| **Report/View** | 🏆 personil_db | Multiple SQL views |
| **Autentikasi** | 🏆 personil_db | Users table dengan role |

### Rekomendasi Akhir:

**Gunakan `personil_db` sebagai fondasi database utama**, kemudian:
1. Tambahkan modul Sprin dari db_bagops
2. Update master pangkat dengan 27 pangkat lengkap
3. Update master satfung dengan Satres PPA & PPO
4. Tambahkan t_log untuk audit trail aplikasi

**Hasil: Database hybrid terbaik untuk SMO-BAGOPS v3.0**

---

## LAMPIRAN: Struktur Lengkap personil_db

### Master Tables (6):
1. master_jabatan - Data jabatan dengan eselon
2. master_jenis_penugasan - PS/Plt/Pjs/Plh/Pj
3. master_pangkat - Data pangkat (perlu update)
4. master_satuan_fungsi - Satfung (perlu update)
5. master_unit_pendukung - Unit pendukung
6. master_unsur - Struktur SOTK

### Transaction Tables (3):
7. penugasan_sementara - Tracking penugasan non-definitif
8. personil - Data lengkap personel (35 fields)
9. riwayat_jabatan - Histori mutasi jabatan
10. riwayat_pangkat - Histori kenaikan pangkat

### System Tables (1):
11. users - Autentikasi dengan role

### Views (3):
- v_personil_detail - View lengkap personil
- v_penugasan_aktif - View penugasan aktif dengan countdown
- v_ps_percentage - View statistik PS
