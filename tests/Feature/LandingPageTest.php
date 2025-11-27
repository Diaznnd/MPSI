<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        $response->assertViewHas('statistics');
    }

    public function test_redirect_route_sends_guest_to_login(): void
    {
        $response = $this->get(route('workshop.redirect', ['workshop' => 1]));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_redirect_route_sends_admin_to_admin_workshop_show(): void
    {
        $pemateri = User::create([
            'nim_nidn' => 'P100',
            'nama' => 'Pemateri',
            'email' => 'pemateri-landing@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);

        $admin = User::create([
            'nim_nidn' => 'A100',
            'nama' => 'Admin',
            'email' => 'admin-landing@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'prodi_fakultas' => 'TI',
        ]);

        $workshop = Workshop::create([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Admin',
            'deskripsi' => 'Desc',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '10:00',
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('workshop.redirect', ['workshop' => $workshop->workshop_id]));

        $response->assertRedirect(route('admin.workshop.show', $workshop->workshop_id));
    }

    public function test_redirect_route_sends_pemateri_to_pemateri_workshop_show(): void
    {
        $pemateri = User::create([
            'nim_nidn' => 'P200',
            'nama' => 'Pemateri 2',
            'email' => 'pemateri2-landing@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);

        $workshop = Workshop::create([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Pemateri',
            'deskripsi' => 'Desc',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '11:00',
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ]);

        $this->actingAs($pemateri);

        $response = $this->get(route('workshop.redirect', ['workshop' => $workshop->workshop_id]));

        $response->assertRedirect(route('pemateri.workshop.show', $workshop->workshop_id));
    }

    public function test_redirect_route_sends_pengguna_to_pengguna_workshop_detail(): void
    {
        $pemateri = User::create([
            'nim_nidn' => 'P300',
            'nama' => 'Pemateri 3',
            'email' => 'pemateri3-landing@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);

        $pengguna = User::create([
            'nim_nidn' => 'U300',
            'nama' => 'Pengguna 3',
            'email' => 'pengguna3-landing@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);

        $workshop = Workshop::create([
            'pemateri_id' => $pemateri->user_id,
            'judul' => 'Workshop Pengguna',
            'deskripsi' => 'Desc',
            'tanggal' => Carbon::now()->addDay()->toDateString(),
            'waktu' => '12:00',
            'lokasi' => 'Online',
            'kuota' => 10,
            'kuota_terisi' => 0,
            'status_workshop' => 'aktif',
        ]);

        $this->actingAs($pengguna);

        $response = $this->get(route('workshop.redirect', ['workshop' => $workshop->workshop_id]));

        $response->assertRedirect(route('pengguna.workshop.detail', $workshop->workshop_id));
    }
}
