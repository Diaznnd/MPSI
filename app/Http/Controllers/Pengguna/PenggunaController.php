<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Pendaftaran;
use App\Models\RequestWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenggunaController extends Controller
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

        // Hitung jumlah pendaftaran user
        $terdaftar = Pendaftaran::where('user_id', $user_id)->count();

        // Hitung jumlah request workshop user
        $request = RequestWorkshop::where('user_id', $user_id)->count();

        // Ambil riwayat workshop terbaru (pendaftaran user)
        $riwayatWorkshop = Pendaftaran::with(['workshop.pemateri'])
            ->where('user_id', $user_id)
            ->orderBy('tanggal_daftar', 'desc')
            ->limit(5)
            ->get();

        return view('User.dashboard', compact('workshopSaya', 'terdaftar', 'request', 'riwayatWorkshop'));
    }

    public function myWorkshop(Request $request)
    {
        $search = $request->get('search', '');
        $user_id = Auth::id();

        // Query pendaftaran user dengan workshop
        $query = Pendaftaran::with(['workshop.pemateri'])
            ->where('user_id', $user_id)
            ->orderBy('tanggal_daftar', 'desc');

        // Filter berdasarkan search
        if ($search) {
            $query->whereHas('workshop', function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%')
                  ->orWhereHas('pemateri', function($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $pendaftarans = $query->paginate(12)->withQueryString();

        return view('User.myworkshop', compact('pendaftarans', 'search'));
    }

    public function daftarWorkshop(Request $request)
    {
        $search = $request->get('search', '');

        // Query workshop dengan status aktif
        $query = Workshop::with('pemateri')
            ->where('status_workshop', 'aktif')
            ->orderBy('tanggal', 'asc');

        // Filter berdasarkan search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%')
                  ->orWhereHas('pemateri', function($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $workshops = $query->paginate(12)->withQueryString();

        return view('User.daftarworkshop', compact('workshops', 'search'));
    }

    public function workshopDetail($workshop_id)
    {
        try {
            // Find workshop by workshop_id with relationships
            // Allow both active and inactive workshops (for My Workshop page)
            $workshop = Workshop::where('workshop_id', $workshop_id)
                ->with(['pemateri', 'keywords', 'materi'])
                ->first();

            if (!$workshop) {
                return response()->json([
                    'error' => 'Workshop tidak ditemukan'
                ], 404);
            }

            // Cek apakah user sudah terdaftar (for inactive workshops in My Workshop)
            $userRegistered = false;
            if (Auth::check()) {
                $pendaftaran = Pendaftaran::where('workshop_id', $workshop->workshop_id)
                    ->where('user_id', Auth::id())
                    ->first();
                $userRegistered = $pendaftaran !== null;
            }

            // If workshop is inactive, only allow if user is registered
            if ($workshop->status_workshop !== 'aktif' && !$userRegistered) {
                return response()->json([
                    'error' => 'Workshop tidak aktif'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

        // Cek apakah kuota sudah penuh
        $kuotaTerisi = $workshop->kuota_terisi ?? 0;
        $kuotaMax = $workshop->kuota ?? 0;
        $isFull = $kuotaMax > 0 && $kuotaTerisi >= $kuotaMax;

        // Get keywords
        $keywords = $workshop->keywords->pluck('keyword')->toArray();

        // Get materi (files)
        $materi = $workshop->materi->map(function($item) {
            return [
                'materi_id' => $item->materi_id,
                'nama_file' => $item->nama_file,
                'file_path' => $item->file_path,
                'tanggal_upload' => $item->tanggal_upload ? Carbon::parse($item->tanggal_upload)->translatedFormat('d F Y') : null
            ];
        })->toArray();

        return response()->json([
            'workshop_id' => $workshop->workshop_id,
            'judul' => $workshop->judul,
            'deskripsi' => $workshop->deskripsi,
            'tanggal' => $workshop->tanggal,
            'tanggal_formatted' => Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y'),
            'waktu' => $workshop->waktu,
            'waktu_formatted' => $workshop->waktu ? date('H.i', strtotime($workshop->waktu)) . ' WIB' : '-',
            'lokasi' => $workshop->lokasi,
            'kuota' => $workshop->kuota,
            'kuota_terisi' => $kuotaTerisi,
            'is_full' => $isFull,
            'sampul_poster_url' => $workshop->sampul_poster_url,
            'user_registered' => $userRegistered,
            'keywords' => $keywords,
            'materi' => $materi,
            'pemateri' => $workshop->pemateri ? [
                'nama' => $workshop->pemateri->nama,
                'email' => $workshop->pemateri->email
            ] : null
        ]);
    }

    public function registerWorkshop(Request $request, $workshop_id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        try {
            // Convert workshop_id to integer if it's a string
            $workshop_id = (int) $workshop_id;
            
            // Log for debugging
            Log::info('Register workshop request', [
                'workshop_id' => $workshop_id,
                'user_id' => Auth::id(),
                'workshop_id_type' => gettype($workshop_id)
            ]);
            
            // Find workshop by workshop_id
            $workshop = Workshop::where('workshop_id', $workshop_id)
                ->where('status_workshop', 'aktif')
                ->first();

            if (!$workshop) {
                Log::warning('Workshop not found or not active', [
                    'workshop_id' => $workshop_id,
                    'user_id' => Auth::id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Workshop tidak ditemukan atau tidak aktif. Workshop ID: ' . $workshop_id
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error finding workshop', [
                'workshop_id' => $workshop_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

        // Cek apakah user sudah terdaftar
        $existingRegistration = Pendaftaran::where('workshop_id', $workshop_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar pada workshop ini'
            ], 400);
        }

        // Cek apakah kuota sudah penuh
        $kuotaTerisi = $workshop->kuota_terisi ?? 0;
        $kuotaMax = $workshop->kuota ?? 0;
        
        if ($kuotaMax > 0 && $kuotaTerisi >= $kuotaMax) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota workshop sudah penuh'
            ], 400);
        }

        // Mulai transaction
        DB::beginTransaction();
        
        try {
            // Update kuota terisi dulu
            $workshop->kuota_terisi = ($workshop->kuota_terisi ?? 0) + 1;
            $workshop->save();

            // Buat pendaftaran (event handler akan otomatis cek kuota dan nonaktifkan jika penuh)
            // Kolom status_pendaftaran mungkin ENUM dengan nilai terbatas atau CHAR dengan panjang terbatas
            // Coba dengan nilai yang lebih pendek atau tidak set jika kolom punya default value
            $pendaftaran = Pendaftaran::create([
                'user_id' => Auth::id(),
                'workshop_id' => $workshop_id,
                'tanggal_daftar' => Carbon::now()
                // Tidak set status_pendaftaran - biarkan database menggunakan default value jika ada
                // Jika kolom required, mungkin perlu nilai seperti 'aktif' atau nilai ENUM lainnya
            ]);

            // Refresh workshop untuk mendapatkan status terbaru
            $workshop->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil!',
                'kuota_terisi' => $workshop->kuota_terisi,
                'kuota' => $workshop->kuota,
                'is_full' => $workshop->kuota > 0 && $workshop->kuota_terisi >= $workshop->kuota
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar workshop: ' . $e->getMessage()
            ], 500);
        }
    }

    public function requestWorkshop()
    {
        // Get user's requests
        $myRequests = RequestWorkshop::where('user_id', Auth::id())
            ->orderBy('request_id', 'desc')
            ->limit(10)
            ->get();

        return view('User.requestworkshop', compact('myRequests'));
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

            return redirect()->route('pengguna.request-workshop')
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
}
