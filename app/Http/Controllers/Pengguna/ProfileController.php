<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('User.profile.index', compact('user'));
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('User.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto_profil.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = [
            'nama' => $request->nama,
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

        // Update ke database
        $user->update($data);

        return redirect()->route('pengguna.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}

