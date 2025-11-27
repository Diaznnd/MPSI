<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PenggunaRegisterWorkshopTest extends TestCase
{
    use RefreshDatabase;

    private function createPemateri(): User
    {
        return User::create([
            'nim_nidn' => 'PEM001',
            'nama' => 'Pemateri',
            'email' => 'pemateri-register@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createPengguna(): User
    {
        return User::create([
            'nim_nidn' => 'USR001',
            'nama' => 'Pengguna',
            'email' => 'pengguna-register@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createActiveWorkshop(User $pemateri, array $overrides = []): Workshop
    {
        $data = array_merge([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Registrasi',
            'deskripsi' => 'Desc',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '10:00',
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ], $overrides);

        return Workshop::create($data);
    }

    public function test_guest_cannot_register_workshop(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createActiveWorkshop($pemateri);

        $response = $this->postJson('/pengguna/workshop/' . $workshop->workshop_id . '/register');

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function test_pengguna_can_register_active_workshop(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createActiveWorkshop($pemateri);

        $this->actingAs($pengguna);

        $response = $this->postJson('/pengguna/workshop/' . $workshop->workshop_id . '/register');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('pendaftaran', [
            'user_id' => $pengguna->user_id,
            'workshop_id' => $workshop->workshop_id,
        ]);
    }

    public function test_pengguna_cannot_register_same_workshop_twice(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createActiveWorkshop($pemateri);

        $this->actingAs($pengguna);

        // First registration
        $this->postJson('/pengguna/workshop/' . $workshop->workshop_id . '/register')
            ->assertStatus(200);

        // Second registration should fail
        $response = $this->postJson('/pengguna/workshop/' . $workshop->workshop_id . '/register');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_pengguna_cannot_register_full_workshop(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createActiveWorkshop($pemateri, [
            'kuota' => 1,
            'kuota_terisi' => 1,
        ]);

        $this->actingAs($pengguna);

        $response = $this->postJson('/pengguna/workshop/' . $workshop->workshop_id . '/register');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }
}
