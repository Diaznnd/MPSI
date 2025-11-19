PANDUAN TEMPLATE SERTIFIKAT
============================

1. Letakkan file template sertifikat di folder ini dengan nama: 
   - certificate-template.png (prioritas)
   - certificate-template.jpg (alternatif)

2. Format file: PNG atau JPG/JPEG
3. Ukuran template: Disarankan minimal 2480x3508 pixels (A4 portrait) untuk kualitas tinggi

PENTING:
- Template akan digunakan sebagai background sertifikat
- Sistem akan menambahkan teks berikut ke template:
  * Nama peserta (warna: Navy Blue, font besar)
  * Teks penghargaan lengkap: "Sebagai penghargaan atas berpartisipasinya dalam workshop "(judul workshop)" yang berlangsung pada (tanggal) di (lokasi)"
  * Semua informasi otomatis diambil dari database

FONT:
- Sistem akan mencari font TrueType di folder public/fonts/ dengan urutan:
  1. times-new-roman.ttf (prioritas - font serif elegan)
  2. times.ttf
  3. arial.ttf
  4. calibri.ttf
- Jika tidak ada font TrueType, akan menggunakan built-in font (kurang bagus)
- Untuk hasil terbaik, letakkan file font Times New Roman di public/fonts/

WARNA TEKS:
- Nama peserta: Navy Blue (RGB: 25, 25, 112)
- Teks penghargaan: Dark Gray (RGB: 64, 64, 64)
- Semua teks di-center secara otomatis

PENYESUAIAN POSISI TEKS:
Jika posisi teks di template Anda berbeda, silakan edit file:
app/Http/Controllers/Pengguna/PenggunaController.php
pada method generateCertificate(), bagian:
- $namaY = $height * 0.38;         // Posisi nama (persentase dari tinggi gambar)
- $penghargaanY = $height * 0.48;  // Posisi teks penghargaan
- $garisY = $height * 0.55;         // Posisi garis hijau
- $teksBawahY = $height * 0.62;     // Posisi teks di bawah garis
- Sesuaikan nilai persentase sesuai template Anda

UKURAN FONT:
- Nama peserta: 85pt (font Kugile/script)
- Teks penghargaan: 22pt (font standar)
- Teks di bawah garis: 48pt (font standar)

WARNA:
- Nama peserta: Dark Green (RGB: 0, 80, 0)
- Teks penghargaan: Hitam (RGB: 0, 0, 0)
- Garis dan teks bawah: Dark Green (RGB: 0, 80, 0)

