<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Pendaftaran;
use App\Models\RequestWorkshop;
use Illuminate\Http\Request;
use App\Models\Absensi;
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

        return view('User.dashboard', compact('workshopSaya', 'terdaftar', 'totalRequest', 'riwayatWorkshop', 'popular_workshops'));
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
                abort(404);
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
                abort(404);
            }
        } catch (\Exception $e) {
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // Cek apakah kuota sudah penuh
        $kuotaTerisi = $workshop->kuota_terisi ?? 0;
        $kuotaMax = $workshop->kuota ?? 0;
        $isFull = $kuotaMax > 0 && $kuotaTerisi >= $kuotaMax;

        // Get keywords list
        $keywords = $workshop->keywords->pluck('keyword')->toArray();

        // Format tanggal dan waktu (WIB)
        $tanggalFormatted = Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y');
        $waktuFormatted = $workshop->waktu
            ? Carbon::parse($workshop->waktu, 'Asia/Jakarta')->setTimezone('Asia/Jakarta')->format('H.i') . ' WIB'
            : '-';

        // Public view for Daftar Workshop
        return view('User.workshop_detail_public', [
            'workshop' => $workshop,
            'keywords' => $keywords,
            'kuotaTerisi' => $kuotaTerisi,
            'kuotaMax' => $kuotaMax,
            'isFull' => $isFull,
            'userRegistered' => $userRegistered,
            'tanggalFormatted' => $tanggalFormatted,
            'waktuFormatted' => $waktuFormatted,
        ]);
    }

    public function myWorkshopDetail($workshop_id)
    {
        try {
            $workshop = Workshop::where('workshop_id', $workshop_id)
                ->with(['pemateri', 'keywords', 'materi'])
                ->first();

            if (!$workshop) {
                abort(404);
            }

            $userRegistered = false;
            if (Auth::check()) {
                $pendaftaran = Pendaftaran::where('workshop_id', $workshop->workshop_id)
                    ->where('user_id', Auth::id())
                    ->first();
                $userRegistered = $pendaftaran !== null;
            }

            if ($workshop->status_workshop !== 'aktif' && !$userRegistered) {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $kuotaTerisi = $workshop->kuota_terisi ?? 0;
        $kuotaMax = $workshop->kuota ?? 0;
        $isFull = $kuotaMax > 0 && $kuotaTerisi >= $kuotaMax;

        $keywords = $workshop->keywords->pluck('keyword')->toArray();
        $tanggalFormatted = Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y');
        $waktuFormatted = $workshop->waktu
            ? Carbon::parse($workshop->waktu, 'Asia/Jakarta')->setTimezone('Asia/Jakarta')->format('H.i') . ' WIB'
            : '-';

        $user_id = Auth::id();
        $absensi = Absensi::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        // Cek ketersediaan absensi (20 menit setelah workshop dimulai)
        // Set timezone ke Asia/Jakarta (WIB)
        $workshopDateTime = Carbon::parse($workshop->tanggal . ' ' . $workshop->waktu)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $startTime = $workshopDateTime;
        $endTime = $workshopDateTime->copy()->addMinutes(20);

        $canTakeAttendance = false;
        $attendanceMessage = '';

        if ($now < $startTime) {
            $attendanceMessage = 'Absensi belum tersedia. Workshop dimulai pada ' . $startTime->translatedFormat('d F Y, H:i') . ' WIB';
        } elseif ($now >= $startTime && $now <= $endTime) {
            $canTakeAttendance = true;
            $remainingMinutes = $now->diffInMinutes($endTime, false);
            $attendanceMessage = $remainingMinutes > 0 
                ? 'Absensi tersedia. ' 
                : 'Absensi tersedia. Segera ambil absensi!';
        } else {
            $attendanceMessage = 'Waktu absensi telah berakhir. Absensi hanya tersedia selama 20 menit setelah workshop dimulai.';
        }

        // Cek status absensi user
        $hasAttended = $absensi !== null;
        $attendanceStatus = null;
        if ($hasAttended) {
            $attendanceStatus = [
                'waktu_absensi' => Carbon::parse($absensi->waktu_absensi)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB',
                'status_absensi' => $absensi->status_absensi
            ];
        }

        // Private view for My Workshop (dengan materi & sertifikat)
        return view('User.workshop_detail', [
            'workshop' => $workshop,
            'keywords' => $keywords,
            'kuotaTerisi' => $kuotaTerisi,
            'kuotaMax' => $kuotaMax,
            'isFull' => $isFull,
            'userRegistered' => $userRegistered,
            'tanggalFormatted' => $tanggalFormatted,
            'waktuFormatted' => $waktuFormatted,
            'absensi' => $absensi,
            'canTakeAttendance' => $canTakeAttendance,
            'attendanceMessage' => $attendanceMessage,
            'hasAttended' => $hasAttended,
            'attendanceStatus' => $attendanceStatus,
            'startTime' => $startTime,
            'endTime' => $endTime,
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

    public function checkAttendanceAvailability($workshop_id)
    {
        $user_id = Auth::id();
        
        // Cek apakah user terdaftar
        $pendaftaran = Pendaftaran::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar pada workshop ini'
            ], 403);
        }

        // Cek apakah sudah absensi
        $absensi = Absensi::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if ($absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengambil absensi untuk workshop ini',
                'already_attended' => true,
                'attendance_time' => Carbon::parse($absensi->waktu_absensi)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB'
            ], 400);
        }

        // Ambil workshop
        $workshop = Workshop::find($workshop_id);
        if (!$workshop) {
            return response()->json([
                'success' => false,
                'message' => 'Workshop tidak ditemukan'
            ], 404);
        }

        // Cek waktu absensi - Set timezone ke Asia/Jakarta (WIB)
        $workshopDateTime = Carbon::parse($workshop->tanggal . ' ' . $workshop->waktu)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $startTime = $workshopDateTime;
        $endTime = $workshopDateTime->copy()->addMinutes(20);

        $canTakeAttendance = false;
        $message = '';

        if ($now < $startTime) {
            $message = 'Absensi belum tersedia. Workshop dimulai pada ' . $startTime->translatedFormat('d F Y, H:i') . ' WIB';
        } elseif ($now >= $startTime && $now <= $endTime) {
            $canTakeAttendance = true;
            $remainingMinutes = $now->diffInMinutes($endTime, false);
            $message = $remainingMinutes > 0 
                ? 'Absensi tersedia. Sisa waktu: ' . $remainingMinutes . ' menit'
                : 'Absensi tersedia. Segera ambil absensi!';
        } else {
            $message = 'Waktu absensi telah berakhir. Absensi hanya tersedia selama 20 menit setelah workshop dimulai.';
        }

        return response()->json([
            'success' => true,
            'can_take_attendance' => $canTakeAttendance,
            'message' => $message,
            'workshop_datetime' => $workshopDateTime->toDateTimeString(),
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString(),
            'current_time' => $now->toDateTimeString(),
            'remaining_minutes' => $canTakeAttendance ? $now->diffInMinutes($endTime, false) : 0
        ]);
    }

    public function submitAttendance(Request $request, $workshop_id)
    {
        $user_id = Auth::id();
        
        // Cek apakah user terdaftar
        $pendaftaran = Pendaftaran::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar pada workshop ini'
            ], 403);
        }

        // Cek apakah sudah absensi
        $existingAbsensi = Absensi::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengambil absensi untuk workshop ini',
                'attendance_time' => Carbon::parse($existingAbsensi->waktu_absensi)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB'
            ], 400);
        }

        // Ambil workshop
        $workshop = Workshop::find($workshop_id);
        if (!$workshop) {
            return response()->json([
                'success' => false,
                'message' => 'Workshop tidak ditemukan'
            ], 404);
        }

        // Cek waktu absensi (20 menit setelah workshop dimulai) - Set timezone ke Asia/Jakarta (WIB)
        $workshopDateTime = Carbon::parse($workshop->tanggal . ' ' . $workshop->waktu)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $startTime = $workshopDateTime;
        $endTime = $workshopDateTime->copy()->addMinutes(20);

        if ($now < $startTime) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi belum tersedia. Workshop dimulai pada ' . $startTime->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB'
            ], 400);
        }

        if ($now > $endTime) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absensi telah berakhir. Absensi hanya tersedia selama 20 menit setelah workshop dimulai.'
            ], 400);
        }

        // Simpan absensi
        try {
            DB::beginTransaction();

            $absensi = Absensi::create([
                'user_id' => $user_id,
                'workshop_id' => $workshop_id,
                'waktu_absensi' => $now,
                'status_absensi' => 'hadir'
            ]);

            DB::commit();

            Log::info('Attendance submitted', [
                'absensi_id' => $absensi->absensi_id,
                'user_id' => $user_id,
                'workshop_id' => $workshop_id,
                'waktu_absensi' => $absensi->waktu_absensi
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil diambil!',
                'attendance' => [
                    'waktu_absensi' => Carbon::parse($absensi->waktu_absensi)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB',
                    'status_absensi' => $absensi->status_absensi
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error submitting attendance', [
                'user_id' => $user_id,
                'workshop_id' => $workshop_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil absensi: ' . $e->getMessage()
            ], 500);
        }
    }

}
