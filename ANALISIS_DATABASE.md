# ANALISIS DATABASE SMO-BAGOPS vs REGULASI KEPOLISIAN

## Tanggal Analisis: April 2026
## Regulasi Acuan:
- Perpol No. 2 Tahun 2021 (SOTK Polres/Polsek)
- Perpol No. 7 Tahun 2025 (Perubahan SOTK - Penambahan Satres PPA & PPO)
- Perkap terkait kepangkatan Polri

---

## 1. ANALISIS MASTER PANGKAT

### Database Saat Ini (17 pangkat):
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 1 | AKBP | AKBP | 1 | ✓ |
| 2 | Kompol | Kompol | 2 | ✓ |
| 3 | AKP | AKP | 3 | ✓ |
| 4 | Iptu | Iptu | 4 | ✓ |
| 5 | Ipda | Ipda | 5 | ✓ |
| 6 | Aiptu | Aiptu | 6 | ✓ |
| 7 | Aipda | Aipda | 7 | ✓ |
| 8 | Bripka | Bripka | 8 | ✓ |
| 9 | Brigpol | Brigpol | 9 | ✓ |
| 10 | Briptu | Briptu | 10 | ✓ |
| 11 | Bripda | Bripda | 11 | ✓ |
| 12 | Pembina | Pembina | 12 | ASN |
| 13 | Penata Tk I | Penata Tk I | 13 | ASN |
| 14 | Penata | Penata | 14 | ASN |
| 15 | Penda Tk I | Penda Tk I | 15 | ASN |
| 16 | Penda | Penda | 16 | ASN |
| 17 | Pengatur | Pengatur | 17 | ASN |

### Regulasi (22 pangkat Polri + 9 pangkat ASN = 31 total):

#### A. POLRI - Perwira Tinggi (Pati) - Level Polres jarang ada:
| No | Pangkat | Singkatan | Hirarki | Catatan |
|----|---------|-----------|---------|---------|
| - | Jenderal Polisi | Jend | - | Biasanya di Polda/Mabes |
| - | Komisaris Jenderal | Komjen | - | Biasanya di Polda/Mabes |
| - | Inspektur Jenderal | Irjen | - | Biasanya di Polda/Mabes |
| - | Brigadir Jenderal | Brigjen | - | Kadiv/Kabapolres besar |

#### B. POLRI - Perwira Menengah (Pamen):
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 1 | Kombes Pol | Kombes | 1 | ✗ TIDAK ADA |
| 2 | AKBP | AKBP | 2 | ✓ Ada |
| 3 | Kompol | Kompol | 3 | ✓ Ada |

#### C. POLRI - Perwira Pertama (Pama):
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 4 | AKP | AKP | 4 | ✓ Ada |
| 5 | Iptu | Iptu | 5 | ✓ Ada |
| 6 | Ipda | Ipda | 6 | ✓ Ada |

#### D. POLRI - Bintara Tinggi:
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 7 | Aiptu | Aiptu | 7 | ✓ Ada |
| 8 | Aipda | Aipda | 8 | ✓ Ada |

#### E. POLRI - Bintara:
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 9 | Bripka | Bripka | 9 | ✓ Ada |
| 10 | Brigpol | Brigpol | 10 | ✓ Ada |
| 11 | Briptu | Briptu | 11 | ✓ Ada |
| 12 | Bripda | Bripda | 12 | ✓ Ada |

#### F. POLRI - Tamtama (6 pangkat - SEMUA TIDAK ADA!):
| No | Pangkat | Singkatan | Hirarki | Status |
|----|---------|-----------|---------|--------|
| 13 | Ajun Brigadir | Abrip | 13 | ✗ TIDAK ADA |
| 14 | Ajun Brigadir Satu | Abriptu | 14 | ✗ TIDAK ADA |
| 15 | Ajun Brigadir Dua | Abripda | 15 | ✗ TIDAK ADA |
| 16 | Bhayangkara Kepala | Bharaka | 16 | ✗ TIDAK ADA |
| 17 | Bhayangkara Satu | Bharatu | 17 | ✗ TIDAK ADA |
| 18 | Bhayangkara Dua | Bharada | 18 | ✗ TIDAK ADA |

#### G. ASN POLRI (9 pangkat - HANYA 6 ADA!):
| No | Pangkat | Singkatan | Status |
|----|---------|-----------|--------|
| 19 | Pembina Tk I | Pembina Tk I | ✗ TIDAK ADA |
| 20 | Pembina | Pembina | ✓ Ada |
| 21 | Penata Tk I | Penata Tk I | ✓ Ada |
| 22 | Penata | Penata | ✓ Ada |
| 23 | Penata Muda Tk I | Penda Tk I | ✓ Ada |
| 24 | Penata Muda | Penda | ✓ Ada |
| 25 | Pengatur Tk I | Peng Tk I | ✗ TIDAK ADA |
| 26 | Pengatur | Pengatur | ✓ Ada |
| 27 | Juru | Juru | ✗ TIDAK ADA |

### KEKURANGAN PANGKAT:
1. **Tamtama Polri (6 pangkat)** - SEMUA TIDAK ADA
2. **ASN Polri (3 pangkat)** - Pembina Tk I, Pengatur Tk I, Juru
3. **Perwira Tinggi (4 pangkat)** - Untuk kompatibilitas dengan Polda

---

## 2. ANALISIS MASTER SATUAN FUNGSI (SATFUNG)

### Database Saat Ini (16 satfung):
| No | Nama Satfung | Kategori | Status |
|----|--------------|----------|--------|
| 1 | Unsur Pimpinan | Pimpinan | ✓ |
| 2 | Bagian Operasional | Staf | ✓ |
| 3 | Bagian Perencanaan | Staf | ✓ |
| 4 | Bagian SDM | Staf | ✓ |
| 5 | Bagian Logistik | Staf | ✓ |
| 6 | Satuan Intelkam | Pelaksana | ✓ |
| 7 | Satuan Reskrim | Pelaksana | ⚠️ NAMA SALAH |
| 8 | Satuan Narkoba | Pelaksana | ✓ |
| 9 | Satuan Samapta | Pelaksana | ✓ |
| 10 | Satuan Lantas | Pelaksana | ✓ |
| 11 | Satuan Binmas | Pelaksana | ✓ |
| 12 | Seksi Propam | Pendukung | ✓ |
| 13 | Seksi Humas | Pendukung | ✓ |
| 14 | Seksi TI | Pendukung | ✓ |
| 15 | Seksi Dokkes | Pendukung | ⚠️ NAMA SALAH |
| 16 | Polsek Jajaran | Wilayah | ✓ |

### Per SOTK Polres 2021 + Perpol 7/2025:

#### A. UNSUR PIMPINAN:
- ✓ Kapolres (ada implisit)
- ✓ Wakapolres

#### B. BAGIAN (Staf Ahli):
| No | Nama | Status |
|----|------|--------|
| 1 | Bagops | ✓ Ada |
| 2 | Bagren/Bag Renmin | ✗ SALAH NAMA - saat ini "Perencanaan" |
| 3 | Bensat/Bag SDM | ✓ Ada |
| 4 | Bidkeu/Bag Keu | ✗ TIDAK ADA |
| 5 | Bag Protap | ✗ TIDAK ADA |
| 6 | Bag Logistik | ✓ Ada |

#### C. SATUAN FUNGSI (Pelaksana):
| No | Nama | Status |
|----|------|--------|
| 1 | Intelkam | ✓ Ada |
| 2 | Satreskrim | ⚠️ SALAH - saat ini "Reskrim" |
| 3 | Satresnarkoba/Narkoba | ✓ Ada |
| 4 | Samapta | ✓ Ada |
| 5 | Lantas/Korlantas | ✓ Ada |
| 6 | Binmas | ✓ Ada |
| 7 | **Satres PPA** | ✗ TIDAK ADA (Perpol 7/2025) |
| 8 | **Satres PPO** | ✗ TIDAK ADA (Perpol 7/2025) |

#### D. SEKSI (Pelayanan Umum/Pendukung):
| No | Nama | Status |
|----|------|--------|
| 1 | Propam | ✓ Ada |
| 2 | Humas | ✓ Ada |
| 3 | TIK/TI | ✓ Ada |
| 4 | Sidokkes | ⚠️ SALAH - saat ini "Dokkes" |
| 5 | Itwasda | ✗ TIDAK ADA |
| 6 | Itwasum | ✗ TIDAK ADA |

#### E. POLSEK (Wilayah):
- ✓ Polsek Jajaran

### KESALAHAN & KEKURANGAN SATFUNG:
1. "Bagian Perencanaan" → seharusnya "Bagren" atau "Bag Renmin"
2. "Reskrim" → seharusnya "Satreskrim" (Satuan Reserse Kriminal)
3. "Dokkes" → seharusnya "Sidokkes" (Seksi Dokumen dan Kesehatan)
4. **TIDAK ADA:** Bag Keuangan, Bag Protap, Satres PPA, Satres PPO, Itwasda, Itwasum

---

## 3. ANALISIS MASTER JENIS PERSONEL

### Database Saat Ini:
- Anggota POLRI
- ASN POLRI
- PHL / Honorer

### Regulasi:
✓ SUDAH SESUAI

---

## 4. ANALISIS STRUKTUR TABEL LAINNYA

### Tabel m_personel:
**Kekurangan field:**
- Tidak ada field foto/profile picture
- Tidak ada field tanggal lahir (penting untuk pensiun)
- Tidak ada field tanggal masuk/jabatan
- Tidak ada field alamat
- Tidak ada field email
- Tidak ada field jenis kelamin

### Tabel t_sprin:
**Kekurangan field:**
- Tidak ada field kode_ops untuk auto-generate nomor
- Tidak ada field created_by (siapa yang membuat)
- Tidak ada updated_at
- Tidak ada lampiran/upload file

### Tabel t_log:
**Kekurangan:**
- Tidak ada IP address logging
- Tidak ada user_agent logging

---

## 5. REKOMENDASI PERBAIKAN

### PRIORITAS TINGGI:
1. Tambahkan 9 pangkat Tamtama Polri
2. Tambahkan 3 pangkat ASN yang kurang
3. Perbaiki nama satfung: Reskrim→Satreskrim, Dokkes→Sidokkes
4. Tambahkan Satres PPA dan PPO (Perpol 7/2025)

### PRIORITAS MENENGAH:
1. Tambahkan field di m_personel: tanggal_lahir, tanggal_masuk, foto, alamat
2. Tambahkan field di t_sprin: created_by, updated_at
3. Tambahkan tabel untuk upload dokumen Sprin

### PRIORITAS RENDAH:
1. Tambahkan pangkat Perwira Tinggi untuk kompatibilitas Polda
2. Indexing untuk optimasi query

---

## RINGKASAN:

| Aspek | Total Seharusnya | Saat Ini | Kekurangan |
|-------|------------------|----------|------------|
| Pangkat Polri | 22 | 11 | 11 pangkat |
| Pangkat ASN | 9 | 6 | 3 pangkat |
| Satfung | 22 | 16 | 6 satfung |
| Field Personel | 15 | 9 | 6 field |

