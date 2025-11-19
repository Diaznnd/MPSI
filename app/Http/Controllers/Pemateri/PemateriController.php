<?php

namespace App\Http\Controllers\Pemateri;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Models\RequestWorkshop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PemateriController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = Auth::user();

        // Hitung jumlah workshop yang dibuat user (jika user adalah pemateri)
        $workshopSaya = 0;
        if ($user->role === 'pemateri') {
            $workshopSaya = Workshop::where('pemateri_id', $user_id)->count();
        }

        $popular_workshops = Workshop::where('status_workshop', 'aktif')
            ->withCount('pendaftaran')
            ->with('pemateri')
            ->orderByDesc('pendaftaran_count')
            ->limit(3)
            ->get();


        // Hitung jumlah pendaftaran user
        $terdaftar = Pendaftaran::where('user_id', $user_id)->count();

        // Hitung jumlah request workshop user
        $totalRequest = RequestWorkshop::where('user_id', $user_id)->count();

        // Ambil riwayat workshop terbaru (pendaftaran user)
        $riwayatWorkshop = Pendaftaran::with(['workshop.pemateri'])
            ->where('user_id', $user_id)
            ->orderBy('tanggal_daftar', 'desc')
            ->limit(5)
            ->get();
        
        return view('Pemateri.dashboard', compact(
            'workshopSaya', 'terdaftar', 'totalRequest', 'riwayatWorkshop', 'popular_workshops'
        ));
    }

    public function workshops(Request $request)
    {
        $user_id = Auth::id();
        $search = $request->get('search', '');

        $baseQuery = Workshop::where('pemateri_id', $user_id)
            ->with(['materi', 'pemateri'])
            ->withCount('pendaftaran')
            ->withCount('materi');

        if ($search) {
            $baseQuery = (clone $baseQuery)->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $today = now()->toDateString();

        $upcomingWorkshops = (clone $baseQuery)
            ->where('status_workshop', 'aktif')
            ->whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal', 'asc')
            ->get();

        $historyWorkshops = (clone $baseQuery)
            ->where(function($q) use ($today) {
                $q->where('status_workshop', 'selesai')
                  ->orWhereDate('tanggal', '<', $today);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('Pemateri.workshop.index', compact('upcomingWorkshops', 'historyWorkshops', 'search'));
    }

    public function show($workshop)
    {
        $userId = Auth::id();

        $data = Workshop::where('workshop_id', $workshop)
            ->where('pemateri_id', $userId)
            ->with(['materi', 'pemateri'])
            ->withCount('pendaftaran')
            ->firstOrFail();

        return view('Pemateri.workshop.show', [
            'workshop' => $data,
        ]);
    }

    public function storeRequestWorkshop(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:2000',
        ], [
            'judul.required' => 'Judul workshop wajib diisi',
            'judul.max' => 'Judul workshop maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi workshop wajib diisi',
            'deskripsi.max' => 'Deskripsi workshop maksimal 2000 karakter',
        ]);

        try {
            // Buat request workshop
            $requestWorkshop = RequestWorkshop::create([
                'user_id' => Auth::id(),
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'status_request' => 'menunggu', // Default status
                'tanggal_tanggapan' => null,
                'catatan_admin' => null,
            ]);

            Log::info('Request workshop created', [
                'request_id' => $requestWorkshop->request_id,
                'user_id' => Auth::id(),
                'judul' => $validated['judul']
            ]);

            return redirect()->route('pemateri.requestWorkshop')
                ->with('success', 'Request workshop berhasil dikirim! Admin akan meninjau request Anda.');
        } catch (\Exception $e) {
            Log::error('Error creating request workshop', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim request workshop: ' . $e->getMessage());
        }
    }

    public function requestWorkshop()
    {
        // Get user's requests
        $myRequests = RequestWorkshop::where('user_id', Auth::id())
            ->orderBy('request_id', 'desc')
            ->limit(10)
            ->get();

        return view('pemateri.request.requestworkshop', compact('myRequests'));
    }
}
