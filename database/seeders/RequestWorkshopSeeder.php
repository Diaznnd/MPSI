<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestWorkshopSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel request_workshop.
     */
    public function run(): void
    {
        $requests = [
            [
                'user_id' => 5,
                'judul' => 'Manajemen Waktu untuk Mahasiswa Baru',
                'deskripsi' => 'Workshop ini membantu mahasiswa baru mengatur waktu kuliah dan kegiatan organisasi secara efektif.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 6,
                'judul' => 'Pelatihan Public Speaking Dasar',
                'deskripsi' => 'Meningkatkan kemampuan berbicara di depan umum untuk mahasiswa berbagai jurusan.',
                'status_request' => 'disetujui',
                'tanggal_tanggapan' => Carbon::now()->subDays(3)->toDateString(),
                'catatan_admin' => 'Disetujui karena tema menarik dan relevan dengan kebutuhan kampus.',
            ],
            [
                'user_id' => 7,
                'judul' => 'Pelatihan Desain Grafis dengan Canva',
                'deskripsi' => 'Workshop praktis membuat konten digital kreatif untuk media sosial kampus.',
                'status_request' => 'ditolak',
                'tanggal_tanggapan' => Carbon::now()->subDays(7)->toDateString(),
                'catatan_admin' => 'Tema sudah pernah diadakan bulan lalu.',
            ],
            [
                'user_id' => 8,
                'judul' => 'Pengenalan Artificial Intelligence bagi Pemula',
                'deskripsi' => 'Workshop untuk memperkenalkan konsep dasar AI kepada mahasiswa non-teknik.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 9,
                'judul' => 'Strategi Membangun Startup Mahasiswa',
                'deskripsi' => 'Pelatihan wirausaha digital yang memotivasi mahasiswa untuk berinovasi.',
                'status_request' => 'disetujui',
                'tanggal_tanggapan' => Carbon::now()->subDay()->toDateString(),
                'catatan_admin' => 'Disetujui dengan syarat kerjasama dengan Inkubator Bisnis UNAND.',
            ],
        ];

        $requests = [
            [
                'user_id' => 20,
                'judul' => 'Pengembangan Soft Skill Mahasiswa Baru',
                'deskripsi' => 'Workshop ini berfokus pada peningkatan kemampuan komunikasi, kerja tim, dan manajemen diri untuk mahasiswa baru.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 21,
                'judul' => 'Pelatihan Dasar Microsoft Excel untuk Penelitian',
                'deskripsi' => 'Memberikan pemahaman dasar penggunaan Excel untuk analisis data sederhana bagi mahasiswa semua jurusan.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 22,
                'judul' => 'Kreativitas Konten Digital untuk Promosi Kampus',
                'deskripsi' => 'Membantu mahasiswa memahami cara membuat konten digital menarik untuk keperluan promosi kegiatan kampus.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 23,
                'judul' => 'Pengenalan Data Science bagi Mahasiswa Non-Teknik',
                'deskripsi' => 'Workshop pengantar yang menjelaskan konsep dasar data science dan penerapannya di berbagai bidang studi.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
            [
                'user_id' => 24,
                'judul' => 'Pelatihan Menulis Artikel Ilmiah untuk Jurnal Kampus',
                'deskripsi' => 'Pelatihan tentang cara menyusun artikel ilmiah dengan struktur yang benar sesuai standar jurnal kampus.',
                'status_request' => 'menunggu',
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ],
        ];

        DB::table('request_workshop')->insert($requests);
    }
}
