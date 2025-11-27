<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SertifikatTest extends TestCase
{
    use RefreshDatabase;

    private function createPemateri(): User
    {
        return User::create([
            'nim_nidn' => 'PEM001',
            'nama' => 'Pemateri',
            'email' => 'pemateri-sertifikat@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createPengguna(string $suffix = '001'): User
    {
        return User::create([
            'nim_nidn' => 'USR' . $suffix,
            'nama' => 'Pengguna ' . $suffix,
            'email' => 'pengguna-sertifikat' . $suffix . '@test.local',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);
    }

    private function createWorkshop(User $pemateri, array $overrides = []): Workshop
    {
        $data = array_merge([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Sertifikat',
            'deskripsi' => 'Desc',
            'tanggal' => Carbon::now()->subDay()->toDateString(), // Past date to allow certificate
            'waktu' => '09:00',
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'selesai',
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

    private function markAttendance(User $user, Workshop $workshop): Absensi
    {
        return Absensi::create([
            'user_id' => $user->user_id,
            'workshop_id' => $workshop->workshop_id,
            'waktu_absensi' => now(),
            'status_absensi' => 'hadir',
        ]);
    }

    private function createCertificate(Pendaftaran $pendaftaran, string $fileUrl = 'certs/test.pdf'): Sertifikat
    {
        return Sertifikat::create([
            'pendaftaran_id' => $pendaftaran->pendaftaran_id,
            'file_url' => $fileUrl,
            'tanggal_generate' => now(),
        ]);
    }

    public function test_guest_cannot_download_certificate(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);
        $pengguna = $this->createPengguna();
        $pendaftaran = $this->registerUserToWorkshop($pengguna, $workshop);
        $sertifikat = $this->createCertificate($pendaftaran);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_download_certificate_for_other_user(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);
        $owner = $this->createPengguna('001');
        $other = $this->createPengguna('002');
        $pendaftaran = $this->registerUserToWorkshop($owner, $workshop);
        $sertifikat = $this->createCertificate($pendaftaran);

        $this->actingAs($other);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }

    public function test_user_can_download_own_certificate(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);
        $pengguna = $this->createPengguna();
        $pendaftaran = $this->registerUserToWorkshop($pengguna, $workshop);
        $this->markAttendance($pengguna, $workshop);
        $sertifikat = $this->createCertificate($pendaftaran);

        $this->actingAs($pengguna);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }

    public function test_user_cannot_download_certificate_if_not_attended(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri);
        $pengguna = $this->createPengguna();
        $pendaftaran = $this->registerUserToWorkshop($pengguna, $workshop);
        // No attendance record
        $sertifikat = $this->createCertificate($pendaftaran);

        $this->actingAs($pengguna);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }

    public function test_user_cannot_download_certificate_if_workshop_not_completed(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri, ['status_workshop' => 'aktif']);
        $pengguna = $this->createPengguna();
        $pendaftaran = $this->registerUserToWorkshop($pengguna, $workshop);
        $this->markAttendance($pengguna, $workshop);
        $sertifikat = $this->createCertificate($pendaftaran);

        $this->actingAs($pengguna);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }

    public function test_user_can_download_certificate_when_attended_and_workshop_completed(): void
    {
        $pemateri = $this->createPemateri();
        $workshop = $this->createWorkshop($pemateri, ['status_workshop' => 'selesai']);
        $pengguna = $this->createPengguna();
        $pendaftaran = $this->registerUserToWorkshop($pengguna, $workshop);
        $this->markAttendance($pengguna, $workshop);
        $sertifikat = $this->createCertificate($pendaftaran);

        $this->actingAs($pengguna);

        $response = $this->get("/pengguna/certificate/{$workshop->workshop_id}/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }

    public function test_download_nonexistent_certificate_returns_404(): void
    {
        $pengguna = $this->createPengguna();
        $this->actingAs($pengguna);

        $response = $this->get("/pengguna/certificate/99999/download");

        $response->assertRedirect();
        $targetUrl = $response->getTargetUrl();
        $this->assertStringContainsString('pengguna/my-workshop', $targetUrl);
    }
}
