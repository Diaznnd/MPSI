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
use Illuminate\Support\Facades\Log;

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

        // Validate input
        $request->validate([
            // judul_topik diabaikan; akan diisi dari judul workshop
            'materi_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // Max 10MB
        ], [
            'materi_file.required' => 'File materi wajib diupload',
            'materi_file.file' => 'File harus berupa file yang valid',
            'materi_file.mimes' => 'File harus berupa PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'materi_file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            return DB::transaction(function() use ($request, $workshop_id, $workshop) {
                // Upload file
                $file = $request->file('materi_file');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('public');
                $filePath = $disk->putFileAs('workshop_materials', $file, $fileName);

                // Judul materi diambil dari judul workshop
                $title = $workshop->judul ?? pathinfo($originalName, PATHINFO_FILENAME);

                try {
                    // Save to database
                    $materi = MateriWorkshop::create([
                        'workshop_id' => $workshop_id,
                        'judul_topik' => $title,
                        'file_materi_url' => $filePath,
                    ]);
                } catch (\Throwable $dbEx) {
                    // Rollback file if DB insert fails
                    if ($disk->exists($filePath)) {
                        $disk->delete($filePath);
                    }
                    throw $dbEx;
                }

                return redirect()->route('pemateri.workshop.show', $workshop_id)
                    ->with('success', 'Materi workshop berhasil diupload!');
            });
        } catch (\Exception $e) {
            Log::error('Upload materi gagal', [
                'workshop_id' => $workshop_id,
                'user_id' => $pemateriId,
                'error' => $e->getMessage(),
            ]);
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

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        // Check if file exists
        if (!$disk->exists($materi->file_materi_url)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        // Return file download
        $downloadName = basename($materi->file_materi_url);
        return $disk->download($materi->file_materi_url, $downloadName);
    }

    /**
     * View materi inline (PDF and some formats) with access control.
     */
    public function view($materi_id)
    {
        $materi = MateriWorkshop::findOrFail($materi_id);
        $workshop = $materi->workshop;

        $userRegistered = DB::table('pendaftaran')
            ->where('workshop_id', $workshop->workshop_id)
            ->where('user_id', Auth::id())
            ->exists();

        $isPemateri = $workshop->pemateri_id === Auth::id();

        if (!$userRegistered && !$isPemateri) {
            abort(403, 'Anda tidak memiliki akses untuk melihat materi ini. Hanya peserta yang terdaftar atau pemateri yang dapat melihat materi.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        if (!$disk->exists($materi->file_materi_url)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        $filename = basename($materi->file_materi_url);
        // Force inline display when possible
        return $disk->response($materi->file_materi_url, $filename, [
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Delete materi
     */
    public function destroy($materi_id)
    {
        $pemateriId = Auth::id();
        
        // Authorize by ensuring the materi belongs to a workshop taught by this pemateri
        $materi = MateriWorkshop::where('materi_id', $materi_id)
            ->whereHas('workshop', function($q) use ($pemateriId) {
                $q->where('pemateri_id', $pemateriId);
            })
            ->firstOrFail();

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        // Delete file from storage
        if ($disk->exists($materi->file_materi_url)) {
            $disk->delete($materi->file_materi_url);
        }

        // Keep workshop id for redirect before deleting record
        $workshopId = $materi->workshop_id;
        // Delete from database
        $materi->delete();

        return redirect()->route('pemateri.workshop.show', $workshopId)
            ->with('success', 'Materi berhasil dihapus!');
    }
}