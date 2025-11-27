<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_form(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'nim_nidn' => '123',
            'nama' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login_error');
        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_admin_dashboard_after_login(): void
    {
        $admin = User::create([
            'nim_nidn' => 'A001',
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'prodi_fakultas' => 'TI',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_pemateri_is_redirected_to_pemateri_dashboard_after_login(): void
    {
        $pemateri = User::create([
            'nim_nidn' => 'P001',
            'nama' => 'Pemateri User',
            'email' => 'pemateri@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pemateri',
            'prodi_fakultas' => 'TI',
        ]);

        $response = $this->post('/login', [
            'email' => 'pemateri@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('pemateri.dashboard'));
        $this->assertAuthenticatedAs($pemateri);
    }

    public function test_pengguna_is_redirected_to_pengguna_dashboard_after_login(): void
    {
        $user = User::create([
            'nim_nidn' => 'U001',
            'nama' => 'Pengguna User',
            'email' => 'pengguna@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);

        $response = $this->post('/login', [
            'email' => 'pengguna@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('pengguna.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_uses_intended_url_if_present(): void
    {
        $user = User::create([
            'nim_nidn' => 'U002',
            'nama' => 'Pengguna Intended',
            'email' => 'intended@example.com',
            'password' => Hash::make('secret'),
            'role' => 'pengguna',
            'prodi_fakultas' => 'TI',
        ]);

        $intended = '/pengguna/daftar-workshop';

        $response = $this
            ->withSession(['url.intended' => $intended])
            ->post('/login', [
                'email' => 'intended@example.com',
                'password' => 'secret',
            ]);

        $response->assertRedirect($intended);
        $this->assertAuthenticatedAs($user);
    }
}
