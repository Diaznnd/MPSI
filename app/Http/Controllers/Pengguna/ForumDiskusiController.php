<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\ForumDiskusi;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForumDiskusiController extends Controller
{
    /**
     * Tampilkan halaman forum diskusi untuk workshop tertentu
     */
    public function index($workshop_id)
    {
        $workshop = Workshop::with('pemateri')
            ->where('workshop_id', $workshop_id)
            ->firstOrFail();

        // Ambil semua diskusi untuk workshop ini (tidak termasuk yang dihapus)
        $diskusi = ForumDiskusi::with(['user'])
            ->where('workshop_id', $workshop_id)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('User.forum.index', compact('workshop', 'diskusi'));
    }

    /**
     * Simpan pesan diskusi baru
     */
    public function store(Request $request, $workshop_id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string' => 'Pesan harus berupa teks.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah workshop ada
        $workshop = Workshop::where('workshop_id', $workshop_id)->firstOrFail();

        // Buat diskusi baru
        $diskusi = ForumDiskusi::create([
            'workshop_id' => $workshop_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('pengguna.forum.index', $workshop_id)
            ->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Update pesan diskusi
     */
    public function update(Request $request, $workshop_id, $discussion_id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string' => 'Pesan harus berupa teks.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari diskusi
        $diskusi = ForumDiskusi::where('discussion_id', $discussion_id)
            ->where('workshop_id', $workshop_id)
            ->where('user_id', Auth::id()) // Hanya user yang membuat bisa edit
            ->firstOrFail();

        // Update pesan
        $diskusi->update([
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil diperbarui.',
            'data' => [
                'discussion_id' => $diskusi->discussion_id,
                'message' => $diskusi->message,
                'updated_at' => $diskusi->updated_at->format('d M Y, H:i'),
            ]
        ]);
    }

    /**
     * Hapus pesan diskusi (soft delete)
     */
    public function destroy($workshop_id, $discussion_id)
    {
        // Cari diskusi
        $diskusi = ForumDiskusi::where('discussion_id', $discussion_id)
            ->where('workshop_id', $workshop_id)
            ->where('user_id', Auth::id()) // Hanya user yang membuat bisa hapus
            ->firstOrFail();

        // Soft delete
        $diskusi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus.'
        ]);
    }
}
