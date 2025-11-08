<?php

// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert akun admin
        for ($i = 6; $i <= 40; $i++) {
    User::create([
        'user_id' => $i,
        'nim_nidn' => '23115220' . str_pad($i, 2, '0', STR_PAD_LEFT),
        'nama' => 'Pengguna ' . $i,
        'email' => 'pengguna' . $i . '@gmail.com',
        'password' => Hash::make('password' . $i),
        'role' => 'pengguna',
        'prodi_fakultas' => fake()->randomElement([
            'Fakultas Kedokteran',
    'Fakultas Hukum',
    'Fakultas Pertanian',
    'Fakultas Teknik',
    'Fakultas Ekonomi dan Bisnis',
    'Fakultas Ilmu Budaya',
    'Fakultas Matematika dan Ilmu Pengetahuan Alam',
    'Fakultas Ilmu Sosial dan Ilmu Politik',
    'Fakultas Peternakan',
    'Fakultas Teknologi Pertanian',
    'Fakultas Farmasi',
    'Fakultas Kesehatan Masyarakat',
    'Fakultas Keperawatan',
    'Fakultas Teknologi Informasi',
    'Fakultas Kedokteran Gigi',
        ]),
        'foto_profil_url' => 'default_profile.jpg',
    ]);
}
    }
}
