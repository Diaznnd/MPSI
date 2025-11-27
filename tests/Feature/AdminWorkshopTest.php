<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminWorkshopTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'nim_nidn' => 'ADM001',
            'nama' => 'Admin',
            'email' => 'admin@test.local',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createPemateri(string $suffix = '001'): User
    {
        return User::create([
            'nim_nidn' => 'PEM' . $suffix,
            'nama' => 'Pemateri ' . $suffix,
            'email' => 'pemateri' . $suffix . '@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);
    }

    public function test_admin_can_view_workshop_index(): void
    {
        $admin = $this->createAdmin();
        $pemateri = $this->createPemateri();

        Workshop::create([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Index Workshop 1',
            'deskripsi' => 'Desc 1',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '09:00',
            'lokasi' => 'Online',
            'kuota' => 20,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/workshops');

        $response->assertStatus(200);
        $response->assertViewIs('admin.workshop.index');
        $response->assertViewHas('workshops');
    }

    public function test_admin_can_store_valid_workshop(): void
    {
        $admin = $this->createAdmin();
        $pemateri = $this->createPemateri('010');

        $this->actingAs($admin);

        $payload = [
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Baru',
            'deskripsi' => 'Deskripsi lengkap workshop baru',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '10:00',
            'lokasi' => 'Ruang A',
            'kuota' => 30,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
            'keywords' => ['laravel', 'testing'],
        ];

        $response = $this->post('/admin/workshops/store', $payload);

        $response->assertRedirect(route('admin.workshop.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('workshops', [
            'judul' => 'Workshop Baru',
            'pemateri_id' => $pemateri->user_id,
            'status_workshop' => 'aktif',
        ]);

        $this->assertDatabaseHas('keywords', ['keyword' => 'laravel']);
        $this->assertDatabaseHas('keywords', ['keyword' => 'testing']);
    }
}
