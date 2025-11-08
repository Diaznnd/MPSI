<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('Admin.profile.index', compact('user'));
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah.'])->withInput();
        }

        // Set session untuk verifikasi password (valid selama 15 menit)
        session(['profile_verified' => true, 'profile_verified_at' => now()]);

        return redirect()->route('admin.profile.edit');
    }

    public function edit()
    {
        // Cek apakah password sudah diverifikasi dalam 15 menit terakhir
        if (!session('profile_verified') || 
            !session('profile_verified_at') || 
            now()->diffInMinutes(session('profile_verified_at')) > 15) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Silakan verifikasi password terlebih dahulu untuk mengakses halaman edit.');
        }

        $user = Auth::user();
        return view('Admin.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Cek apakah password sudah diverifikasi
        if (!session('profile_verified') || 
            !session('profile_verified_at') || 
            now()->diffInMinutes(session('profile_verified_at')) > 15) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan verifikasi password kembali.');
        }

        $user = Auth::user();

        $request->validate([
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nomor_telepon' => $request->nomor_telepon ?? null,
            'alamat' => $request->alamat ?? null,
        ];

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil_url && Storage::disk('public')->exists($user->foto_profil_url)) {
                Storage::disk('public')->delete($user->foto_profil_url);
            }

            // Simpan foto baru
            $file = $request->file('foto_profil');
            $filename = 'profile_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');
            $data['foto_profil_url'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        // Cek apakah password sudah diverifikasi
        if (!session('profile_verified') || 
            !session('profile_verified_at') || 
            now()->diffInMinutes(session('profile_verified_at')) > 15) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan verifikasi password kembali.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = Auth::user();

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.'])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Clear session verification setelah update password
        session()->forget(['profile_verified', 'profile_verified_at']);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
