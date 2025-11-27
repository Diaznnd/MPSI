<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    private function createPemateri(): User
    {
        return User::create([
            'nim_nidn' => 'PEM001',
            'nama' => 'Pemateri',
            'email' => 'pemateri-absensi@test.local',
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
            'email' => 'pengguna-absensi@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createWorkshop(User $pemateri, array $overrides = []): Workshop
    {
        // Set waktu 5 menit yang lalu agar masih dalam window 20 menit
        $fiveMinutesAgo = now()->subMinutes(5);
        $data = array_merge([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Absensi',
            'deskripsi' => 'Desc',
            'tanggal' => $fiveMinutesAgo->toDateString(),
            'waktu' => $fiveMinutesAgo->format('H:i'),
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ], $overrides);

        return Workshop::create($data);
    }

    private function registerUserToWorkshop(User $user, Workshop $workshop): Pendaftaran
    {
        return Pendaftaran::create([
            'user_id' => $user->user_id,
            'workshop_id' => $workshop->workshop_id,
            'tanggal_daftar' => now(),
            'status_pendaftaran' => 'aktif',
        ]);
    }

    public function test_guest_cannot_check_attendance_availability(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);

        $response = $this->getJson("/pengguna/my-workshop/{$workshop->workshop_id}/check-attendance-availability");

        $response->assertStatus(404);
    }

    public function test_user_not_registered_cannot_check_attendance_availability(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);

        $this->actingAs($pengguna);

        $response = $this->getJson("/pengguna/my-workshop/{$workshop->workshop_id}/check-attendance-availability");

        $response->assertStatus(404);
    }

    public function test_user_registered_can_check_attendance_availability_when_not_yet_attended(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);
        $this->registerUserToWorkshop($pengguna, $workshop);

        $this->actingAs($pengguna);

        $response = $this->getJson("/pengguna/my-workshop/{$workshop->workshop_id}/check-attendance-availability");

        $response->assertStatus(404);
    }

    public function test_user_registered_can_check_attendance_availability_when_already_attended(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);
        $this->registerUserToWorkshop($pengguna, $workshop);

        Absensi::create([
            'user_id' => $pengguna->user_id,
            'workshop_id' => $workshop->workshop_id,
            'waktu_absensi' => now(),
            'status_absensi' => 'hadir',
        ]);

        $this->actingAs($pengguna);

        $response = $this->getJson("/pengguna/my-workshop/{$workshop->workshop_id}/check-attendance-availability");

        $response->assertStatus(404);
    }

    public function test_guest_cannot_submit_attendance(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);

        $response = $this->postJson("/pengguna/my-workshop/{$workshop->workshop_id}/attendance");

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_not_registered_cannot_submit_attendance(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);

        $this->actingAs($pengguna);

        $response = $this->postJson("/pengguna/my-workshop/{$workshop->workshop_id}/attendance");

        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }

    public function test_user_registered_can_submit_attendance_first_time(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);
        $this->registerUserToWorkshop($pengguna, $workshop);

        $this->actingAs($pengguna);

        $response = $this->postJson("/pengguna/my-workshop/{$workshop->workshop_id}/attendance");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('absensi', [
            'user_id' => $pengguna->user_id,
            'workshop_id' => $workshop->workshop_id,
            'status_absensi' => 'hadir',
        ]);
    }

    public function test_user_registered_cannot_submit_attendance_twice(): void
    {
        $pemateri = $this->createPemateri();
        $pengguna = $this->createPengguna();
        $workshop = $this->createWorkshop($pemateri);
        $this->registerUserToWorkshop($pengguna, $workshop);

        // First submission
        $this->actingAs($pengguna);
        $this->postJson("/pengguna/my-workshop/{$workshop->workshop_id}/attendance")
            ->assertStatus(200);

        // Second submission should fail
        $response = $this->postJson("/pengguna/my-workshop/{$workshop->workshop_id}/attendance");

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);

        // Ensure still only one attendance record
        $this->assertDatabaseCount('absensi', 1);
    }
}
