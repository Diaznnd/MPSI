<?php

namespace App\Http\Controllers\Pemateri;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\MateriWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar workshop yang diajar oleh pemateri
     */
    public function index()
    {
        $pemateriId = Auth::id();
        
        // Get workshops where user is pemateri
        $workshops = Workshop::where('pemateri_id', $pemateriId)
            ->with(['materi'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('Pemateri.materi.index', compact('workshops'));
    }

    /**
     * Menampilkan form upload materi untuk workshop tertentu
     */
    public function create($workshop_id)
    {
        $pemateriId = Auth::id();
        
        // Verify that the workshop belongs to this pemateri
        $workshop = Workshop::where('workshop_id', $workshop_id)
            ->where('pemateri_id', $pemateriId)
            ->firstOrFail();

        return view('Pemateri.materi.create', compact('workshop'));
    }

    /**
     * Store uploaded materi
     */
    public function store(Request $request, $workshop_id)
    {
        $pemateriId = Auth::id();
        
        // Verify that the workshop belongs to this pemateri
        $workshop = Workshop::where('workshop_id', $workshop_id)
            ->where('pemateri_id', $pemateriId)
            ->firstOrFail();

        // Validate file
        $request->validate([
            'materi_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // Max 10MB
        ], [
            'materi_file.required' => 'File materi wajib diupload',
            'materi_file.file' => 'File harus berupa file yang valid',
            'materi_file.mimes' => 'File harus berupa PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'materi_file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            // Upload file
            $file = $request->file('materi_file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $filePath = $file->storeAs('workshop_materials', $fileName, 'public');

            // Save to database
            $materi = MateriWorkshop::create([
                'workshop_id' => $workshop_id,
                'pemateri_id' => $pemateriId,
                'nama_file' => $originalName,
                'file_path' => $filePath,
                'tanggal_upload' => Carbon::now(),
            ]);

            return redirect()->route('pemateri.materi.index')
                ->with('success', 'Materi workshop berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal upload materi: ' . $e->getMessage());
        }
    }

    /**
     * Download materi (for users who are registered in the workshop or pemateri)
     */
    public function download($materi_id)
    {
        $materi = MateriWorkshop::findOrFail($materi_id);
        $workshop = $materi->workshop;
        
        // Check if user is registered in this workshop
        $userRegistered = DB::table('pendaftaran')
            ->where('workshop_id', $workshop->workshop_id)
            ->where('user_id', Auth::id())
            ->exists();

        // Check if user is the pemateri of this workshop
        $isPemateri = $workshop->pemateri_id === Auth::id();

        // Only allow download if user is registered or is the pemateri
        if (!$userRegistered && !$isPemateri) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh materi ini. Hanya peserta yang terdaftar atau pemateri yang dapat mengunduh materi.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        // Return file download
        return Storage::disk('public')->download($materi->file_path, $materi->nama_file);
    }

    /**
     * Delete materi
     */
    public function destroy($materi_id)
    {
        $pemateriId = Auth::id();
        
        $materi = MateriWorkshop::where('materi_id', $materi_id)
            ->where('pemateri_id', $pemateriId)
            ->firstOrFail();

        // Delete file from storage
        if (Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        // Delete from database
        $materi->delete();

        return redirect()->route('pemateri.materi.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}