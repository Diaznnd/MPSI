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

        DB::table('request_workshop')->insert($requests);
    }
}
