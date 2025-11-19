PANDUAN FONT SERTIFIKAT
========================

Folder ini untuk menyimpan font TrueType (.ttf) yang akan digunakan untuk generate sertifikat.

FONT YANG DIPERLUKAN:
1. FONT SCRIPT UNTUK NAMA (PRIORITAS TINGGI):
   - kugile.ttf - PRIORITAS UTAMA - Font Kugile untuk nama
   - brush-script.ttf - Font script elegan seperti di template
   - lucida-calligraphy.ttf - Alternatif font script
   - comic-sans.ttf - Fallback
   - arial.ttf - Fallback

2. FONT STANDAR UNTUK TEKS LAINNYA:
   - times-new-roman.ttf - Font serif formal (PRIORITAS)
   - times.ttf - Alternatif
   - arial.ttf - Alternatif
   - calibri.ttf - Alternatif

CARA MENGGUNAKAN:
1. Download font TrueType (.ttf) yang Anda inginkan
2. Letakkan file font di folder ini (public/fonts/)
3. Pastikan nama file sesuai dengan yang dicari sistem:
   FONT SCRIPT (untuk nama):
   - brush-script.ttf (PRIORITAS)
   - lucida-calligraphy.ttf
   - comic-sans.ttf
   
   FONT STANDAR (untuk teks lainnya):
   - times-new-roman.ttf (PRIORITAS)
   - times.ttf
   - arial.ttf
   - calibri.ttf

CATATAN:
- Sistem akan otomatis mencari font script untuk nama dengan urutan prioritas
- Sistem akan otomatis mencari font standar untuk teks lainnya
- Jika tidak ada font TrueType, sistem akan menggunakan built-in font (hasil kurang bagus)
- Untuk hasil terbaik, gunakan Brush Script untuk nama dan Times New Roman untuk teks lainnya
- Font script akan membuat nama terlihat elegan dan formal seperti di template

